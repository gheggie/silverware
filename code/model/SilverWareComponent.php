<?php

/**
 * An extension of the site tree class for a SilverWare component.
 */
class SilverWareComponent extends SiteTree implements Flushable, PermissionProvider
{
    private static $singular_name = "Component";
    private static $plural_name   = "Components";
    
    private static $description = "A component within a SilverWare template or layout";
    
    private static $icon = "silverware/images/icons/SilverWareComponent.png";
    
    private static $can_be_root = false;
    
    private static $db = array(
        'StyleID' => 'Varchar(255)',
        'StyleClasses' => 'Varchar(255)',
        'CacheEnabled' => 'Boolean',
        'CacheLifetime' => 'Int'
    );
    
    private static $has_one = array(
        'StyleRules' => 'ComponentStyle',
        'ChildRules' => 'ComponentStyle'
    );
    
    private static $has_many = array(
        'DeviceRules' => 'DeviceRule',
        'LinkedStyles' => 'LinkedStyle'
    );
    
    private static $defaults = array(
        'ShowInMenus' => 0,
        'ShowInSearch' => 0,
        'CacheEnabled' => 0,
        'CacheLifetime' => 300
    );
    
    private static $custom_styles = array();
    
    protected $extraClasses    = array();
    protected $extraAttributes = array();
    
    protected $cacheAllChildren;
    protected $cacheEnabledChildren;
    
    /**
     * Clears all render caches upon flush.
     */
    public static function flush()
    {
        if (DB::is_active()) {
            
            foreach (SilverWareComponent::get()->filter(array('CacheEnabled' => 1)) as $Component) {
                $Component->flushRenderCache();
            }
            
        }
    }
    
    /**
     * Answers true if default records have been created.
     *
     * @return boolean
     */
    public static function has_defaults()
    {
        return (
            SilverWareTemplate::has_default() &&
            SilverWareLayout::has_default() &&
            SilverWarePageType::has_default()
        );
    }
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Add Component Class to Root Tab Set:
        
        $fields->fieldByName('Root')->addExtraClass($this->ClassName);
        
        // Remove Field Objects:
        
        $fields->removeFieldsFromTab('Root.Main', array('Content', 'Metadata'));
        
        // Update Field Objects:
        
        $fields->dataFieldByName('Title')->setTitle(_t('SilverWareComponent.TITLE', 'Title'));
        $fields->dataFieldByName('MenuTitle')->addExtraClass('hidden');
        
        // Create Style Tab:
        
        $fields->findOrMakeTab('Root.Style', _t('SilverWareComponent.STYLE', 'Style'));
        
        // Create Style Fields:
        
        $fields->addFieldsToTab(
            'Root.Style',
            array(
                ToggleCompositeField::create(
                    'StyleGeneralToggle',
                    _t('SilverWareComponent.GENERAL', 'General'),
                    array(
                        TextField::create(
                            'StyleID',
                            _t('SilverWareComponent.STYLEID', 'Style ID')
                        )->setRightTitle(
                            _t(
                                'SilverWareComponent.STYLEIDRIGHTTITLE',
                                'Allows you to define a custom style ID for the component.'
                            )
                        ),
                        TextField::create(
                            'StyleClasses',
                            _t('SilverWareComponent.STYLECLASSES', 'Style Classes')
                        )->setRightTitle(
                            _t(
                                'SilverWareComponent.STYLECLASSESRIGHTTITLE',
                                'Allows you to add additional style classes for the component (space-separated).'
                            )
                        )
                    )
                ),
                $styleRules = ToggleCompositeField::create(
                    'StyleRulesToggle',
                    _t('SilverWareComponent.RULES', 'Rules'),
                    $this->getStyleRulesFields()
                ),
                $deviceRules = ToggleCompositeField::create(
                    'DeviceRulesToggle',
                    _t('SilverWareComponent.DEVICES', 'Devices'),
                    array(
                        GridField::create(
                            'DeviceRules',
                            _t('SilverWareComponent.RULES', 'Rules'),
                            $this->DeviceRules(),
                            $config = GridFieldConfig_RecordEditor::create()
                        )
                    )
                )->addExtraClass('has-grid-field')
            )
        );
        
        // Hide Style Rules Toggle (if empty):
        
        if ($styleRules->getChildren()->count() == 0) {
            $styleRules->addExtraClass('hidden');
        }
        
        // Create Options Tab:
        
        $fields->findOrMakeTab('Root.Options', _t('SilverWareComponent.OPTIONS', 'Options'));
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.Options',
            array(
                ToggleCompositeField::create(
                    'CacheOptions',
                    _t('SilverWareComponent.CACHE', 'Cache'),
                    array(
                        NumericField::create(
                            'CacheLifetime',
                            _t('SilverWareComponent.CACHELIFETIME', 'Cache lifetime (in seconds)')
                        ),
                        CheckboxField::create(
                            'CacheEnabled',
                            _t('SilverWareComponent.CACHEENABLED', 'Cache enabled')
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'FixtureOptions',
                    _t('SilverWareComponent.FIXTURES', 'Fixtures'),
                    array(
                        TextField::create(
                            'Identifier',
                            _t('SilverWareComponent.IDENTIFIER', 'Identifier')
                        )->setRightTitle(
                            _t(
                                'SilverWareComponent.IDENTIFIERRIGHTTITLE',
                                'Used to identify this component when building the database from YAML fixtures.'
                            )
                        )
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Update Attributes:
        
        $this->StyleClasses = trim(preg_replace('/\s+/', ' ', $this->StyleClasses));
    }
    
    /**
     * Creates any required default records (if they do not already exist).
     */
    public function requireDefaultRecords()
    {
        // Require Default Records (from parent):
        
        parent::requireDefaultRecords();
        
        // Require Default Records:
        
        try {
            
            // Create Folder Objects:
            
            SilverWareFolder::create_folders();
            
            // Load Defined Fixtures:
            
            if (!self::has_defaults() || (isset($_GET['loadfixtures']) && $_GET['loadfixtures'])) {
                SilverWare::load_fixtures();
            }
            
        } catch (Exception $e) {
            
            // Report Exception Message:
            
            DB::alteration_message($e->getMessage(), 'error');
            
        }
    }
    
    /**
     * Event method called after the receiver is written to the database.
     */
    public function onAfterWrite()
    {
        // Call Parent Event:
        
        parent::onAfterWrite();
        
        // Save Linked Styles:
        
        if ($request = Controller::curr()->getRequest()) {
            
            if ($linked_styles = $request->postVar('LinkedStyles')) {
                
                if (is_array($linked_styles)) {
                    
                    foreach ($this->LinkedStyles() as $Style) {
                        
                        if (!in_array($Style->Name, array_keys($linked_styles))) {
                            $this->LinkedStyles()->remove($Style);
                        }
                        
                    }
                    
                    foreach ($linked_styles as $name => $id) {
                        
                        $this->LinkedStyles()->add($this->findOrMakeStyle($name, $id));
                        
                    }
                    
                }
                
            }
            
        }
    }
    
    /**
     * Answers true if the member can create an instance of the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canCreate($member = null)
    {
        // Obtain SiteTree Context:
        
        if (func_num_args() > 1) {
            
            $context = func_get_arg(1);
            
            if (isset($context['Parent'])) {
                
                // Disallow Creation as children of Pages:
                
                return !($context['Parent'] instanceof Page);
                
            }
            
        }
        
        // Check Permissions for Member:
        
        return Permission::checkMember($member, array('ADMIN', 'SILVERWARE_COMPONENT_CREATE'));
    }
    
    /**
     * Answers true if the member can view the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::checkMember($member, array('ADMIN', 'SILVERWARE_COMPONENT_VIEW'));
    }
    
    /**
     * Answers true if the member can edit the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return Permission::checkMember($member, array('ADMIN', 'SILVERWARE_COMPONENT_EDIT'));
    }
    
    /**
     * Answers true if the member can delete the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return Permission::checkMember($member, array('ADMIN', 'SILVERWARE_COMPONENT_DELETE'));
    }
    
    /**
     * Provides the permissions for the security interface.
     *
     * @return array
     */
    public function providePermissions()
    {
        return array(
            
            'SILVERWARE_COMPONENT_CREATE' => array(
                'category' => _t('SilverWareComponent.PERMISSION_CATEGORY', 'SilverWare components'),
                'name' => _t('SilverWareComponent.PERMISSION_CREATE_NAME', 'Create components'),
                'help' => _t('SilverWareComponent.PERMISSION_CREATE_HELP', 'Ability to create SilverWare components.'),
                'sort' => 100
            ),
            
            'SILVERWARE_COMPONENT_VIEW' => array(
                'category' => _t('SilverWareComponent.PERMISSION_CATEGORY', 'SilverWare components'),
                'name' => _t('SilverWareComponent.PERMISSION_VIEW_NAME', 'View components'),
                'help' => _t('SilverWareComponent.PERMISSION_VIEW_HELP', 'Ability to view SilverWare components.'),
                'sort' => 200
            ),
            
            'SILVERWARE_COMPONENT_EDIT' => array(
                'category' => _t('SilverWareComponent.PERMISSION_CATEGORY', 'SilverWare components'),
                'name' => _t('SilverWareComponent.PERMISSION_EDIT_NAME', 'Edit components'),
                'help' => _t('SilverWareComponent.PERMISSION_EDIT_HELP', 'Ability to edit SilverWare components.'),
                'sort' => 300
            ),
            
            'SILVERWARE_COMPONENT_DELETE' => array(
                'category' => _t('SilverWareComponent.PERMISSION_CATEGORY', 'SilverWare components'),
                'name' => _t('SilverWareComponent.PERMISSION_DELETE_NAME', 'Delete components'),
                'help' => _t('SilverWareComponent.PERMISSION_DELETE_HELP', 'Ability to delete SilverWare components.'),
                'sort' => 400
            )
            
        );
    }
    
    /**
     * Answers the type of component as a string.
     *
     * @return string
     */
    public function getComponentType()
    {
        return $this->i18n_singular_name();
    }
    
    /**
     * Defines the value of an extra attribute for the receiver.
     *
     * @param string $name
     * @param string $value
     * @return SilverWareComponent
     */
    public function setAttribute($name, $value)
    {
        $this->extraAttributes[$name] = $value;
        
        return $this;
    }
    
    /**
     * Answers the value of an extra attribute for the receiver.
     *
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name)
    {
        $attributes = $this->getAttributes();
        
        if (isset($attributes[$name])) {
            return $attributes[$name];
        }
        
        return null;
    }
    
    /**
     * Answers the array of attributes for the receiver.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array(
            'id' => $this->getHTMLID(),
            'class' => $this->getHTMLClass()
        );
        
        $attributes = array_merge($attributes, $this->extraAttributes);
        
        $this->extend('updateAttributes', $attributes);
        
        return $attributes;
    }
    
    /**
     * Answers the attributes of the receiver as HTML.
     *
     * @param array|null $attributes Optional array of attributes to convert to HTML.
     * @param string|null $prefix Optional prefix for HTML attribute names.
     * @return string
     */
    public function getAttributesHTML($attributes = null, $prefix = null)
    {
        $pairs = array();
        
        if (!$attributes) {
            $attributes = $this->getAttributes();
        }
        
        foreach ($attributes as $name => $value) {
            
            if (substr($value, 0, 2) == '->') {
                $value = $this->getPropertyValue(substr($value, 2));
            }
            
            if ($prefix) {
                $name = $prefix . '-' . $name;
            }
            
            if ($value === true) {
                $pairs[] = sprintf('%s="%s"', $name, $name);
            } else {
                $pairs[] = sprintf('%s="%s"', $name, Convert::raw2att($value));
            }
            
        }
        
        return implode(' ', $pairs);
    }
    
    /**
     * Answers a unique object ID for the receiver.
     *
     * @return string
     */
    public function getObjectID()
    {
        return "{$this->ClassName}_{$this->ID}";
    }
    
    /**
     * Defines the style ID for the receiver from the given data object.
     *
     * @param DataObject $object
     * @return SilverWareComponent
     */
    public function setStyleIDFrom(DataObject $object)
    {
        $ids = array(
            $object->ClassName,
            $this->ClassName,
            $object->ID
        );
        
        $this->StyleID = implode('_', $ids);
        
        return $this;
    }
    
    /**
     * Answers a unique ID for the HTML template.
     *
     * @return string
     */
    public function getHTMLID()
    {
        return $this->StyleID ? $this->StyleID : $this->getObjectID();
    }
    
    /**
     * Answers a string of class names for the HTML template.
     *
     * @return string
     */
    public function getHTMLClass()
    {
        return implode(' ', $this->getClassNames());
    }
    
    /**
     * Answers an array of class names for the receiver.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = array('component');
        
        $classes = array_merge($classes, $this->getComponentAncestry());
        
        if ($this->extraClasses) {
            $classes = array_merge($classes, $this->extraClasses);
        }
        
        if ($this->StyleClasses) {
            $classes = array_merge($classes, preg_split('/\s+/', trim($this->StyleClasses)));
        }
        
        $this->extend('updateClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Adds one or more extra class names to the receiver.
     *
     * @param string $class (space-delimited for multiple class names)
     * @return SilverWareComponent
     */
    public function addExtraClass($class)
    {
        $classes = preg_split('/\s+/', trim($class));
        
        foreach ($classes as $class) {
            $this->extraClasses[$class] = $class;
        }
        
        return $this;
    }
    
    /**
     * Answers the prefix CSS required for the template.
     *
     * @return array
     */
    public function getPrefixCSS()
    {
        return array($this->getCSSID() . " {");
    }
    
    /**
     * Answers the suffix CSS required for the template.
     *
     * @return array
     */
    public function getSuffixCSS()
    {
        return array("}\n");
    }
    
    /**
     * Answers an array containing the style rules fields for the receiver.
     *
     * @return array
     */
    public function getStyleRulesFields()
    {
        // Create Rules Field Array:
        
        $rules_fields = array();
        
        // Obtain Component Style Options:
        
        $styles = ComponentStyle::get()->map();
        
        // Do We Have Component Styles?
        
        if ($styles->count() > 0) {
            
            // Create Rules Field for This Component:
            
            $rules_fields[] = DropdownField::create(
                'StyleRulesID',
                _t('SilverWareComponent.THISCOMPONENT', 'This Component'),
                $styles
            )->setEmptyString(' ');
            
            // Create Rules Field for Child Components:
            
            if ($this->config()->allowed_children != 'none') {
                
                $rules_fields[] = DropdownField::create(
                    'ChildRulesID',
                    _t('SilverWareComponent.CHILDCOMPONENTS', 'Child Components'),
                    $styles
                )->setEmptyString(' ');
                
            }
            
        }
        
        // Create Custom Rules Fields:
        
        if ($custom_styles = $this->config()->custom_styles) {
            
            foreach ($custom_styles as $name => $type) {
                $rules_fields[] = $this->getLinkedStyleField($name, $type);
            }
            
        }
        
        // Answer Rules Fields Array:
        
        return $rules_fields;
    }
    
    /**
     * Answers a linked style object with the given name.
     *
     * @param string $name
     * @return LinkedStyle
     */
    public function getLinkedStyle($name)
    {
        return $this->LinkedStyles()->filter('Name', $name)->first();
    }
    
    /**
     * Finds or creates a linked style object with the given name.
     *
     * @param string $name Name of the linked style.
     * @param integer $id ID of the custom style to link.
     * @return LinkedStyle
     */
    public function findOrMakeStyle($name, $id)
    {
        $Style = $this->getLinkedStyle($name);
        
        if (!$Style) {
            $Style = LinkedStyle::create();
        }
        
        $Style->Name = $name;
        $Style->StyleID = $id;
        
        return $Style;
    }
    
    /**
     * Renders the receiver for the HTML template.
     *
     * @param string $layout
     * @param string $title
     * @return HTMLText|false
     */
    public function Render($layout = null, $title = null)
    {
        // Initialise HTML:
        
        $html = false;
        
        // Load Cached HTML:
        
        if ($this->CacheEnabled) {
            
            $html = $this->getRenderCache()->load($this->ID);
            
        }
        
        // Render HTML (if does not exist):
        
        if (!$html) {
            
            // Render Template:
            
            $html = $this->RenderTemplate($layout, $title);
            
            // Save HTML to Cache:
            
            $this->getRenderCache()->save($html, $this->ID);
            
        } elseif (isset($_REQUEST['cachedebug']) && Director::isDev()) {
            
            Debug::message("Rendered from cache: {$this->ObjectID}; lifetime: {$this->CacheLifetime}", false);
            
        }
        
        // Answer HTML:
        
        return $html;
    }
    
    /**
     * Renders the receiver using the appropriate template file.
     *
     * @param string $layout
     * @param string $title
     * @return HTMLText
     */
    public function RenderTemplate($layout = null, $title = null)
    {
        return $this->getController()->customise(
            array(
                'Title' => $title,
                'Layout' => $layout,
                'Component' => $this
            )
        )->renderWith($this->ClassName);
    }
    
    /**
     * Renders the receiver for CMS preview.
     *
     * @return HTMLText
     */
    public function RenderPreview()
    {
        // Load Requirements:
        
        Requirements::css('silverware/css/cms/cms-preview.css');
        Requirements::customCSS($this->getPreviewCSSAsString());
        
        // Render Component:
        
        return $this->forTemplate();
    }
    
    /**
     * Answers an array of custom CSS required for the CMS preview.
     *
     * @return array
     */
    public function getPreviewCSS()
    {
        // Create CSS Array:
        
        $css = array();
        
        // Merge Custom CSS from Dummy Page:
        
        $css = array_merge($css, Page::create()->getCustomCSS());
        
        // Merge Custom CSS from Style Rules:
        
        if ($Style = $this->MyStyle()) {
            
            $css = array_merge($Style->getCustomCSS(array($this->getCSSID())), $css);
            
        }
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Answers a string of custom CSS required for the CMS preview.
     *
     * @return string
     */
    public function getPreviewCSSAsString()
    {
        return implode("\n", $this->getPreviewCSS());
    }
    
    /**
     * Answers true if the receiver is enabled within the template.
     *
     * @return boolean
     */
    public function Enabled()
    {
        return !$this->Disabled();
    }
    
    /**
     * Answers true if the receiver is disabled within the template (subclass override).
     *
     * @return boolean
     */
    public function Disabled()
    {
        return false;
    }
    
    /**
     * Answers all children within the receiver (overrides extension method for performance reasons).
     *
     * @return DataList
     */
    public function AllChildren()
    {
        // Answer Cached Child Objects (if available):
        
        if ($this->cacheAllChildren) {
            return $this->cacheAllChildren;
        }
        
        // Obtain Child Objects:
        
        $this->cacheAllChildren = $this->stageChildren(true);
        
        // Answer Child Objects:
        
        return $this->cacheAllChildren;
    }
    
    /**
     * Answers all of the styles associated with the receiver.
     *
     * @return ArrayList
     */
    public function AllStyles()
    {
        $Styles = ArrayList::create();
        
        if ($this->StyleRulesID) {
            $Styles->merge($this->StyleRules());
        }
        
        if ($this->ChildRulesID) {
            $Styles->merge($this->ChildRules());
        }
        
        return $Styles;
    }
    
    /**
     * Answers the appropriate style for the receiver.
     *
     * @return ComponentStyle
     */
    public function MyStyle()
    {
        // Answer Style from Receiver:
        
        if ($this->StyleRulesID) {
            return $this->StyleRules();
        }
        
        // Answer Style from Parent:
        
        $Parent = $this->Parent();
        
        while ($Parent instanceof SilverWareComponent) {
            
            if ($Parent->ChildRulesID) {
                return $Parent->ChildRules();
            }
            
            $Parent = $Parent->Parent();
            
        }
    }
    
    /**
     * Answers a list of all the components within the receiver.
     *
     * @return ArrayList
     */
    public function getAllComponents()
    {
        $Components = ArrayList::create();
        
        foreach ($this->AllChildren() as $Child) {
            
            $Components->push($Child);
            
            $Components->merge($Child->getAllComponents());
            
        }
        
        return $Components;
    }
    
    /**
     * Answers a list of all the enabled components within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledComponents()
    {
        $Components = ArrayList::create();
        
        foreach ($this->getEnabledChildren() as $Child) {
            
            $Components->push($Child);
                
            $Components->merge($Child->getEnabledComponents());
            
        }
        
        return $Components;
    }
    
    /**
     * Answers a list of the enabled children within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledChildren()
    {
        // Answer Cached Child Objects (if available):
        
        if ($this->cacheEnabledChildren) {
            return $this->cacheEnabledChildren;
        }
        
        // Obtain Child Objects:
        
        $Children = array();
        
        foreach ($this->AllChildren() as $Child) {
            
            if (($Child instanceof SilverWareComponent) && $Child->Enabled()) {
                
                $Children[] = $Child;
                
            }
            
        }
        
        // Record Child Objects in Cache:
        
        $this->cacheEnabledChildren = ArrayList::create($Children);
        
        // Answer Child Objects:
        
        return $this->cacheEnabledChildren;
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
        
        // Update CSS via Extensions:
        
        $this->extend('updateCustomCSS', $css);
        
        // Merge Prefix & Suffix CSS:
        
        if (!empty($css)) {
            
            $css = array_merge($this->getPrefixCSS(), $css, $this->getSuffixCSS());
            
        }
        
        // Merge CSS from Device Rules:
        
        foreach ($this->DeviceRules() as $rule) {
            
            $css = array_merge($css, $rule->getCustomCSS());
            
        }
        
        // Merge CSS from Template File:
        
        $template = "{$this->ClassName}_css";
        
        if (SSViewer::hasTemplate($template)) {
            
            $css = array_merge($css, preg_split("/\r\n|\n|\r/", $this->renderWith($template)));
            
        }
        
        // Filter CSS Array:
        
        $css = array_filter($css);
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Answers a string of custom CSS required for the template.
     *
     * @return string
     */
    public function getCustomCSSAsString()
    {
        return implode("\n", $this->getCustomCSS());
    }
    
    /**
     * Loads the custom CSS required for the template.
     */
    public function getCustomCSSRequirements()
    {
        Requirements::customCSS($this->getCustomCSSAsString());
    }
    
    /**
     * Flushes the render cache for the receiver.
     *
     * @return boolean
     */
    public function flushRenderCache()
    {
        return $this->getRenderCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
    }
    
    /**
     * Answers the class name of the current page.
     *
     * @return string
     */
    public function getCurrentPageClass()
    {
        if ($Page = $this->getCurrentPage()) {
            
            return $Page->ClassName;
            
        }
    }
    
    /**
     * Renders the receiver when embedded into a template.
     *
     * @return HTMLText
     */
    public function forTemplate()
    {
        // Load Requirements:
        
        $this->getRequirements();
        
        // Load Custom CSS:
        
        $this->getCustomCSSRequirements();
        
        // Render Component:
        
        return $this->Render();
    }
    
    /**
     * Answers the current site config object.
     *
     * @return SiteConfig
     */
    public function getSiteConfig()
    {
        return SiteConfig::current_site_config();
    }
    
    /**
     * Answers the current page.
     *
     * @param string $class Optional class restriction.
     * @return SiteTree
     */
    public function getCurrentPage($class = null)
    {
        $Page = Director::get_current_page();
        
        if (!$class || ($class && $Page instanceof $class)) {
            return $Page;
        }
    }
    
    /**
     * Answers the current controller.
     *
     * @param string $class Optional class restriction.
     * @return Controller
     */
    public function getCurrentController($class = null)
    {
        $Controller = Controller::curr();
        
        if (!$class || ($class && $Controller instanceof $class)) {
            return $Controller;
        }
    }
    
    /**
     * Answers the current controller (only if it is an instance of ContentController).
     *
     * @return ContentController
     */
    public function getCurrentContentController()
    {
        if ($Controller = $this->getCurrentController('ContentController')) {
            return $Controller;
        }
    }
    
    /**
     * Answers the controller for the receiver.
     *
     * @return Controller
     */
    public function getController()
    {
        return ModelAsController::controller_for($this);
    }
    
    /**
     * Answers the ID of the custom style linked with the given name.
     *
     * @param string $name
     * @return integer
     */
    public function getLinkedStyleID($name)
    {
        if ($Style = $this->getLinkedStyle($name)) {
            return $Style->StyleID;
        }
    }
    
    /**
     * Answers the config for the custom style with the given name.
     *
     * @param string $name
     * @return array
     */
    public function getCustomStyleConfig($name)
    {
        $config = array();
        
        if ($custom_styles = $this->config()->custom_styles) {
            
            if (isset($custom_styles[$name]) && is_array($custom_styles[$name])) {
                $config = $custom_styles[$name];
            }
            
        }
        
        return $config;
    }
    
    /**
     * Answers the CSS prefix for the custom style with the given name.
     *
     * @param string $name
     * @return string
     */
    public function getCustomStylePrefix($name)
    {
        if ($config = $this->getCustomStyleConfig($name)) {
            
            if (isset($config['prefix'])) {
                return $this->getCSSID() . " " . $config['prefix'];
            }
            
        }
        
        return $this->getCSSID();
    }
    
    /**
     * Answers the mappings for the custom style with the given name.
     *
     * @param string $name
     * @return array
     */
    public function getCustomStyleMappings($name)
    {
        $mappings = array();
        
        if ($config = $this->getCustomStyleConfig($name)) {
            
            if (isset($config['mappings']) && is_array($config['mappings'])) {
                $mappings = $config['mappings'];
            }
            
        }
        
        return $mappings;
    }
    
    /**
     * Defines a status message for the HTTP response to be shown in the CMS.
     *
     * @param string $message
     */
    protected function setResponseStatus($message)
    {
        if ($Controller = $this->getCurrentController()) {
            
            $Controller->getResponse()->addHeader('X-Status', $message);
            
        }
    }
    
    /**
     * Answers a dropdown field for the specified linked style.
     *
     * @param string $name
     * @param string|array $config
     * @return DropdownField
     */
    protected function getLinkedStyleField($name, $config)
    {
        // Obtain Type (if array):
        
        $type = $config;
        
        if (is_array($config) && isset($config['type'])) {
            $type = $config['type'];
        }
        
        // Answer Field Object:
        
        return DropdownField::create(
            "LinkedStyles[{$name}]",
            FormField::name_to_label($name),
            CustomStyle::get()->filter('StyleType', (string) $type)->map(),
            $this->getLinkedStyleID($name)
        )->setEmptyString(' ');
    }
    
    /**
     * Answers an array containing the class names of the ancestors of the receiver.
     *
     * @return array
     */
    protected function getComponentAncestry()
    {
        $classes = array();
        
        foreach (array_reverse(ClassInfo::ancestry($this)) as $className) {
            
            $classes[] = strtolower($className);
            
            if ($className == 'SilverWareComponent') {
                break;
            }
            
        }
        
        return $classes;
    }
    
    /**
     * Answers the value of the property with the given name (either via method or field).
     *
     * @param string $name
     * @return mixed
     */
    protected function getPropertyValue($name)
    {
        if ($this->hasMethod($name)) {
            return $this->$name();
        }
        
        return $this->$name;
    }
    
    /**
     * Answers the render cache for the receiver.
     *
     * @return Zend_Cache_Core
     */
    protected function getRenderCache()
    {
        return SS_Cache::factory(
            $this->getRenderCacheID(),
            'Output',
            array(
                'lifetime' => $this->CacheLifetime,
                'automatic_serialization' => true
            )
        );
    }
    
    /**
     * Answers the render cache ID for the receiver.
     *
     * @return string
     */
    protected function getRenderCacheID()
    {
        return $this->getObjectID();
    }
}

/**
 * An extension of the content controller class for a SilverWare component.
 */
class SilverWareComponent_Controller extends ContentController
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
