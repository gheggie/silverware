<?php

/**
 * An extension of the data extension class to add SilverWare settings to site config.
 */
class SilverWareConfigExtension extends DataExtension
{
    private static $db = array(
        'SilverWareConfigExtensions' => 'Text'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        if (!$fields->fieldByName('Root.SilverWare')) {
            
            $fields->addFieldToTab(
                'Root',
                TabSet::create(
                    'SilverWare',
                    _t('SilverWareConfigExtension.SILVERWARE', 'SilverWare')
                )
            );
            
        }
    }
    
    /**
     * Writes the defaults from the extension to site configuration.
     */
    public function requireDefaultRecords()
    {
        if ($config = SiteConfig::current_site_config()) {
            
            SilverWareTools::write_defaults_to_config($config, $this->class);
            
        }
    }
    
    /**
     * Defines the status of the specified config extension class.
     *
     * @param string $class
     * @param boolean $status
     * @return SiteConfig
     */
    public function setConfigExtension($class, $status)
    {
        $extensions = $this->owner->getConfigExtensions();
        
        if (!$extensions) {
            $extensions = array();
        }
        
        $extensions[$class] = (boolean) $status;
        
        $this->setConfigExtensions($extensions);
        
        return $this->owner;
    }
    
    /**
     * Defines the array of installed config extensions.
     *
     * @param array $extensions
     * @return SiteConfig
     */
    public function setConfigExtensions($extensions = array())
    {
        $this->owner->SilverWareConfigExtensions = serialize($extensions);
        
        return $this->owner;
    }
    
    /**
     * Answers the array of installed config extensions.
     *
     * @return array
     */
    public function getConfigExtensions()
    {
        return unserialize($this->owner->SilverWareConfigExtensions);
    }
    
    /**
     * Answers true if the specified config extension has been configured.
     *
     * @return boolean
     */
    public function hasConfigExtension($class)
    {
        if ($extensions = $this->owner->getConfigExtensions()) {
            
            if (isset($extensions[$class])) {
                
                return (boolean) $extensions[$class];
                
            }
            
        }
        
        return false;
    }
    
    /**
     * Answers an array of custom CSS from config extension instances.
     *
     * @return array
     */
    public function getConfigExtensionCSS()
    {
        $css = array();
        
        foreach ($this->owner->getExtensionInstances() as $ClassName => $Extension) {
            
            $Template = $ClassName . '_css';
            
            if (($Extension instanceof SilverWareConfigExtension) && SSViewer::hasTemplate($Template)) {
                
                $css[] = $this->owner->renderWith($Template);
                
            }
            
        }
        
        return $css;
    }
    
    /**
     * Answers an string of custom CSS from config extension instances.
     *
     * @return string
     */
    public function getConfigExtensionCSSAsString()
    {
        return implode("\n", $this->owner->getConfigExtensionCSS());
    }
}
