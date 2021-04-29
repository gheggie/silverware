<?php

/**
 * An extension of the data object class for a SilverWare link.
 */
class SilverWareLink extends DataObject
{
    private static $singular_name = "Link";
    private static $plural_name   = "Links";
    
    private static $default_sort = "Sort";
    
    private static $db = array(
        'Sort' => 'Int',
        'Name' => 'Varchar(255)',
        'LinkURL' => 'Varchar(2048)',
        'Disabled' => 'Boolean',
        'OpenInNewTab' => 'Boolean'
    );
    
    private static $has_one = array(
        'LinkPage' => 'SiteTree',
        'Component' => 'SilverWareComponent'
    );
    
    private static $extensions = array(
        'SilverWareFontIconExtension'
    );
    
    private static $defaults = array(
        'Disabled' => 0,
        'OpenInNewTab' => 0
    );
    
    private static $summary_fields = array(
        'Name' => 'Name',
        'Link' => 'Link',
        'Disabled.Nice' => 'Disabled'
    );
    
    protected $extraClasses = array();
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Create Field Tab Set:
        
        $fields = FieldList::create(TabSet::create('Root'));
        
        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create(
                    'Name',
                    _t('SilverWareLink.NAME', 'Name')
                ),
                TreeDropdownField::create(
                    'LinkPageID',
                    _t('SilverWareLink.LINKPAGE', 'Link Page'),
                    'SiteTree'
                ),
                TextField::create(
                    'LinkURL',
                    _t('SilverWareLink.LINKURL', 'Link URL')
                ),
                CheckboxField::create(
                    'OpenInNewTab',
                    _t('SilverWareLink.OPENINNEWTAB', 'Open in new tab')
                ),
                CheckboxField::create(
                    'Disabled',
                    _t('SilverWareLink.DISABLED', 'Disabled')
                )
            )
        );
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create(
            array(
                'Name'
            )
        );
    }
    
    /**
     * Answers a unique ID for the HTML template.
     *
     * @return string
     */
    public function getHTMLID()
    {
        return "{$this->ClassName}_{$this->ID}";
    }
    
    /**
     * Answers a unique ID for a CSS stylesheet.
     *
     * @return string
     */
    public function getCSSID()
    {
        return "#" . $this->getHTMLID();
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
        $classes = array(strtolower($this->ClassName));
        
        if ($this->extraClasses) {
            $classes = array_merge($classes, $this->extraClasses);
        }
        
        return $classes;
    }
    
    /**
     * Adds one or more extra class names to the receiver.
     *
     * @param string $class (space-delimited for multiple class names)
     * @return SilverWareLink
     */
    public function addExtraClass($class)
    {
        $classes = preg_split('/\s+/', $class);
        
        foreach ($classes as $class) {
            $this->extraClasses[$class] = $class;
        }
        
        return $this;
    }
    
    /**
     * Answers the appropriate link for the receiver.
     *
     * @return string
     */
    public function getLink()
    {
        if ($this->LinkURL) {
            return $this->dbObject('LinkURL')->URL();
        }
        
        if ($this->LinkPageID) {
            return $this->LinkPage()->Link();
        }
    }
    
    /**
     * Renders the receiver when embedded into a template.
     *
     * @return HTMLText
     */
    public function forTemplate()
    {
        foreach (array_reverse(ClassInfo::ancestry($this)) as $className) {
            
            if (SSViewer::hasTemplate($className)) {
                return $this->renderWith($className);
            }
            
        }
        
        return $this->renderWith('SilverWareLink');
    }
    
    /**
     * Answers a string of Font Awesome icon class names for the HTML template.
     *
     * @return string
     */
    public function getFontIconClass()
    {
        $classes = $this->getFontIconClassNames();
        
        if ($this->Component()->hasExtension('SilverWareFontIconExtension')) {
            
            if (!$this->FontIconSize && ($size = $this->Component()->FontIconSize)) {
                $classes[] =  $size;
            }
            
        }
        
        return implode(' ', $classes);
    }
    
    /**
     * Answers true to use fixed width font icons.
     *
     * @return boolean
     */
    public function getFontIconFixedWidth()
    {
        return true;
    }
    
    /**
     * Renders the icon tag for the HTML template.
     *
     * @return string
     */
    public function FontIconTag()
    {
        if ($this->HasFontIcon()) {
            return $this->renderWith('FontIconTag');
        }
        
        if ($this->Component()->hasExtension('SilverWareFontIconExtension')) {
            return $this->Component()->FontIconTag();
        }
    }
}
