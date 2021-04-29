<?php

/**
 * An extension of the data extension class which allows pages to use SilverWare.
 */
class SilverWarePageExtension extends DataExtension implements PermissionProvider
{
    private static $db = array(
        'PopulatedClasses' => 'Text',
        'DisablePageComponent' => 'Boolean'
    );
    
    private static $defaults = array(
        'DisablePageComponent' => 0
    );
    
    private static $has_one = array(
        'MyLayout' => 'SilverWareLayout',
        'MyTemplate' => 'SilverWareTemplate'
    );
    
    private static $belongs_many_many = array(
        'Panels' => 'SilverWarePanel'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Check Defaults Populated for Class:
        
        if (!$this->owner->defaultsPopulatedFor($this->owner->ClassName)) {
            $this->owner->forcePopulateDefaults();
        }
        
        // Add Populated Classes Hidden Field:
        
        $fields->push(HiddenField::create('PopulatedClasses', ''));
        
        // Add Badge Number Update Hidden Field:
        
        $fields->push(
            HiddenField::create("BadgeNumberUpdate{$this->owner->ID}", '', $this->owner->getBadgeNumber())
                ->addExtraClass('update-number-badge')
                ->setAttribute('data-tree-id', "#record-{$this->owner->ID}")
                ->setAttribute('data-text', $this->owner->getBadgeText())
        );
    }
    
    /**
     * Forces the population of default values (helps after changing the page type).
     */
    public function forcePopulateDefaults()
    {
        // Populate Defaults (via method):
        
        $this->owner->populateDefaults();
        
        // Populate Defaults from Parent Classes (via config):
        
        $classes = ClassInfo::ancestry($this->owner, true);  // only classes with database tables
        
        // Add Class of Extended Object:
        
        $classes[$this->owner->class] = $this->owner->class;
        
        // Iterate Classes:
        
        foreach ($classes as $class) {
            
            // Populate Defaults for Parent:
            
            if (!$this->owner->defaultsPopulatedFor($class)) {
                
                $defaults = Config::inst()->get($class, 'defaults', Config::UNINHERITED);
                
                if ($defaults && is_array($defaults)) {
                    
                    foreach ($defaults as $fieldName => $fieldValue) {
                        
                        $this->owner->$fieldName = $fieldValue;
                        
                        if (is_array($fieldValue) && $this->owner->manyManyComponent($fieldName)) {
                            $manyManyJoin = $this->owner->$fieldName();
                            $manyManyJoin->setByIdList($fieldValue);
                        }
                        
                    }
                    
                }
                
                // Record Parent as Populated:
                
                $this->owner->setPopulatedStatus($class, true);
                
            }
            
        }
        
        // Record Owner as Populated:
        
        $this->owner->setPopulatedStatus($this->owner->ClassName, true);
    }
    
    /**
     * Answers true if defaults have been populated for the specified class.
     *
     * @param string $class
     * @return boolean
     */
    public function defaultsPopulatedFor($class)
    {
        if ($classes = $this->getPopulatedClassesArray()) {
            
            if (isset($classes[$class])) {
                return (boolean) $classes[$class];
            }
            
        }
        
        return false;
    }
    
    /**
     * Defines the populated status for the specified class.
     *
     * @param string $class
     * @param boolean $status
     * @return Page
     */
    public function setPopulatedStatus($class, $status = true)
    {
        $classes = $this->getPopulatedClassesArray();
        
        $classes[$class] = (boolean) $status;
        
        $this->setPopulatedClassesArray($classes);
        
        return $this->owner;
    }
    
    /**
     * Defines the array of populated classes for the extended object.
     *
     * @param array $classes
     * @return Page
     */
    public function setPopulatedClassesArray($classes = array())
    {
        $this->owner->PopulatedClasses = serialize($classes);
        
        return $this->owner;
    }
    
    /**
     * Answers the array of populated classes for the extended object.
     *
     * @return array
     */
    public function getPopulatedClassesArray()
    {
        $classes = array();
        
        if ($this->owner->PopulatedClasses) {
            $classes = unserialize($this->owner->PopulatedClasses);
        }
        
        return $classes;
    }
    
    /**
     * Updates the CMS settings fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateSettingsFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Settings',
            $settings = ToggleCompositeField::create(
                'SilverWareSettings',
                _t('SilverWarePageExtension.SILVERWARE', 'SilverWare'),
                array(
                    $template = DropdownField::create(
                        'MyTemplateID',
                        _t('SilverWarePageExtension.TEMPLATE', 'Template'),
                        SilverWareTemplate::get()->map()
                    )->setEmptyString(_t('SilverWarePageExtension.DROPDOWNDEFAULT', '(default)')),
                    $layout = DropdownField::create(
                        'MyLayoutID',
                        _t('SilverWarePageExtension.LAYOUT', 'Layout'),
                        SilverWareLayout::get()->map()
                    )->setEmptyString(_t('SilverWarePageExtension.DROPDOWNDEFAULT', '(default)')),
                    $disablepc = CheckboxField::create(
                        'DisablePageComponent',
                        _t('SilverWarePageExtension.DISABLEPAGECOMPONENT', 'Disable Page Component')
                    )
                )
            )
        );
        
        // Check Permissions and Modify Fields:
        
        if (!Permission::check('ADMIN') && !Permission::check('SILVERWARE_PAGE_TEMPLATE_CHANGE')) {
            $fields->makeFieldReadonly($template);
        }
        
        if (!Permission::check('ADMIN') && !Permission::check('SILVERWARE_PAGE_LAYOUT_CHANGE')) {
            $fields->makeFieldReadonly($layout);
        }
        
        if (!Permission::check('ADMIN')) {
            $fields->makeFieldReadonly($disablepc);
        }
    }
    
    /**
     * Event handler method triggered after the content controller has initialised.
     */
    public function contentcontrollerInit(Controller $controller)
    {
        // Force Security to use SilverWare Controller Extension:
        
        if ($controller instanceof Page_Controller) {
            
            if ($controller->URLSegment == 'Security') {
                $controller->extend('onBeforeInit');
                $controller->extend('onAfterInit');
            }
            
        }
    }
    
    /**
     * Provides the permissions for the security interface.
     *
     * @return array
     */
    public function providePermissions()
    {
        return array(
            
            'SILVERWARE_PAGE_TEMPLATE_CHANGE' => array(
                'category' => _t('SilverWarePageExtension.PERMISSION_CATEGORY', 'SilverWare pages'),
                'name' => _t('SilverWarePageExtension.PERMISSION_TEMPLATE_CHANGE_NAME', 'Change templates for pages'),
                'help' => _t(
                    'SilverWarePageExtension.PERMISSION_TEMPLATE_CHANGE_HELP',
                    'Ability to change templates for pages.'
                ),
                'sort' => 100
            ),
            
            'SILVERWARE_PAGE_LAYOUT_CHANGE' => array(
                'category' => _t('SilverWarePageExtension.PERMISSION_CATEGORY', 'SilverWare pages'),
                'name' => _t('SilverWarePageExtension.PERMISSION_LAYOUT_CHANGE_NAME', 'Change layouts for pages'),
                'help' => _t(
                    'SilverWarePageExtension.PERMISSION_LAYOUT_CHANGE_HELP',
                    'Ability to change layouts for pages.'
                ),
                'sort' => 200
            ),
            
            'SILVERWARE_PAGE_SETTINGS_CHANGE' => array(
                'category' => _t('SilverWarePageExtension.PERMISSION_CATEGORY', 'SilverWare pages'),
                'name' => _t('SilverWarePageExtension.PERMISSION_SETTINGS_CHANGE_NAME', 'Change settings for pages'),
                'help' => _t(
                    'SilverWarePageExtension.PERMISSION_SETTINGS_CHANGE_HELP',
                    'Ability to change settings for pages.'
                ),
                'sort' => 300
            )
            
        );
    }
    
    /**
     * Updates the status flags of the extended object.
     *
     * @param array $flags
     */
    public function updateStatusFlags(&$flags)
    {
        if ($num = $this->owner->getBadgeNumber()) {
            
            $text = str_replace('{num}', $num, $this->owner->getBadgeText());
            
            $flags['number-badge'] = array(
                'text' => $text,
                'title' => $text
            );
            
            $flags['number-badge-val'] = array(
                'text' => '',
                'title' => $num
            );
            
        }
    }
    
    /**
     * Answers the number to display as a site tree badge.
     *
     * @return integer
     */
    public function getBadgeNumber()
    {
        return 0;
    }
    
    /**
     * Answers the text to display as a site tree badge.
     *
     * @return string
     */
    public function getBadgeText()
    {
        return "";
    }
    
    /**
     * Answers the layout defined for the extended object.
     *
     * @return SilverWareLayout
     */
    public function PageLayout()
    {
        if ($this->owner->MyLayout()->isInDB()) {
            return $this->owner->MyLayout();
        }
        
        return SiteConfig::current_site_config()->getLayoutForPage($this->owner);
    }
    
    /**
     * Answers the template defined for the extended object.
     *
     * @return SilverWareTemplate
     */
    public function PageTemplate()
    {
        if ($this->owner->MyTemplate()->isInDB()) {
            return $this->owner->MyTemplate();
        }
        
        return SiteConfig::current_site_config()->getTemplateForPage($this->owner);
    }
    
    /**
     * Answers an array of custom CSS required for the template.
     *
     * @return array
     */
    public function getCustomCSS()
    {
        // Create CSS Array:
        
        $css = array();
        
        // Merge CSS from Style Templates:
        
        $css = array_merge($css, $this->getStyleTemplateCSS());
        
        // Merge CSS from Config Extensions:
        
        $css = array_merge($css, $this->getConfigExtensionCSS());
        
        // Merge CSS from Template:
        
        if ($Template = $this->owner->PageTemplate()) {
            
            $css = array_merge($css, $Template->getCustomCSS());
            
        }
        
        // Merge CSS from Layout:
        
        if ($Layout = $this->owner->PageLayout()) {
            
            $css = array_merge($css, $Layout->getCustomCSS());
            
        }
        
        // Merge CSS from Style Rules:
        
        foreach ($this->owner->getEnabledStyles() as $Style) {
            
            $css = array_merge($css, $Style->getCustomCSS());
            
        }
        
        // Merge CSS from Custom Style Rules:
        
        foreach ($this->owner->getEnabledCustomStyles() as $Style) {
            
            $css = array_merge($css, $Style->getCustomCSS());
            
        }
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Answers a list of all the components within the receiver.
     *
     * @return ArrayList
     */
    public function getAllComponents()
    {
        // Create Components List:
        
        $Components = ArrayList::create();
        
        // Merge Components from Template:
        
        if ($Template = $this->owner->PageTemplate()) {
            
            $Components->merge($Template->getAllComponents());
            
        }
        
        // Merge Components from Layout:
        
        if ($Layout = $this->owner->PageLayout()) {
            
            $Components->merge($Layout->getAllComponents());
            
        }
        
        // Answer Components List:
        
        return $Components;
    }
    
    /**
     * Answers a list of all the enabled components within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledComponents()
    {
        // Create Components List:
        
        $Components = ArrayList::create();
        
        // Merge Components from Template:
        
        if ($Template = $this->owner->PageTemplate()) {
            
            $Components->merge($Template->getEnabledComponents());
            
        }
        
        // Merge Components from Layout:
        
        if ($Layout = $this->owner->PageLayout()) {
            
            $Components->merge($Layout->getEnabledComponents());
            
        }
        
        // Answer Components List:
        
        return $Components;
    }
    
    /**
     * Answers a list of styles for enabled components within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledStyles()
    {
        $Styles = array();
        
        foreach ($this->getEnabledComponents() as $Component) {
            
            foreach ($Component->AllStyles() as $Style) {
                
                if (!isset($Styles[$Style->ID])) {
                    $Styles[$Style->ID] = $Style;
                }
                
            }
            
        }
        
        return ArrayList::create($Styles);
    }
    
    /**
     * Answers a list of enabled custom styles for the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledCustomStyles()
    {
        $Styles = array();
        
        foreach (CustomStyle::get() as $Style) {
            
            if (!isset($Styles[$Style->ID])) {
                $Styles[$Style->ID] = $Style;
            }
            
        }
        
        return ArrayList::create($Styles);
    }
    
    /**
     * Answers an array of CSS rendered by implementors of the style template interface.
     *
     * @return array
     */
    private function getStyleTemplateCSS()
    {
        $css = array();
        
        foreach (ClassInfo::implementorsOf('StyleTemplate') as $ClassName) {
            $css[] = $this->owner->renderWith("{$ClassName}_css");
        }
        
        return $css;
    }
    
    /**
     * Answers an array of CSS rendered by site config extensions.
     *
     * @return array
     */
    private function getConfigExtensionCSS()
    {
        $css = array();
        
        if ($Config = SiteConfig::current_site_config()) {
            $css = array_merge($css, $Config->getConfigExtensionCSS());
        }
        
        return $css;
    }
}
