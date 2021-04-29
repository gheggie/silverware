<?php

/**
 * An extension of the data extension class which adds requirements functionality to extended objects.
 */
class SilverWareRequirementsExtension extends DataExtension
{
    /**
     * An array of required JavaScript files.
     *
     * @var array
     */
    private static $required_js = array();
    
    /**
     * An array of required CSS files.
     *
     * @var array
     */
    private static $required_css = array();
    
    /**
     * An array of required themed CSS files.
     *
     * @var array
     */
    private static $required_themed_css = array();
    
    /**
     * An array of required JavaScript template files.
     *
     * @var array
     */
    private static $required_js_templates = array();
    
    /**
     * Loads the CSS and scripts required by the receiver.
     */
    public function getRequirements()
    {
        // Load Required CSS:
        
        foreach ($this->owner->getRequiredCSS() as $css => $media) {
            
            Requirements::css($css, $media);
            
        }
        
        // Load Required Themed CSS:
        
        foreach ($this->owner->getRequiredThemedCSS() as $css => $media) {
            
            Requirements::themedCSS($css, $this->owner->getModuleName(), $media);
            
        }
        
        // Load Required JavaScript (if enabled):
        
        if (!$this->owner->RequiredJSDisabled) {
            
            // Load Required JavaScript:
            
            foreach ($this->owner->getRequiredJS() as $js) {
                
                Requirements::javascript($js);
                
            }
            
            // Load Required JavaScript Templates:
            
            foreach ($this->owner->getRequiredJSTemplates() as $file => $params) {
                
                SilverWareTools::load_javascript_template($file, $params['vars'], $params['id']);
                
            }
            
        }
    }
    
    /**
     * Answers an array of JavaScript files required by the receiver.
     *
     * @return array
     */
    public function getRequiredJS()
    {
        $js = $this->owner->config()->required_js;
        
        $this->owner->extend('updateRequiredJS', $js);
        
        return $js;
    }
    
    /**
     * Answers an array of CSS files required by the receiver.
     *
     * @return array
     */
    public function getRequiredCSS()
    {
        $css = $this->owner->config()->required_css;
        
        $this->owner->extend('updateRequiredCSS', $css);
        
        if (!ArrayLib::is_associative($css)) {
            return array_fill_keys(array_values($css), null); 
        }
        
        return $css;
    }
    
    /**
     * Answers an array of themed CSS files required by the receiver.
     *
     * @return array
     */
    public function getRequiredThemedCSS()
    {
        $css =  $this->owner->config()->required_themed_css;
        
        $this->owner->extend('updateRequiredThemedCSS', $css);
        
        if (!ArrayLib::is_associative($css)) {
            return array_fill_keys(array_values($css), null); 
        }
        
        return $css;
    }
    
    /**
     * Answers an array of JavaScript templates required by the receiver.
     *
     * @return array
     */
    public function getRequiredJSTemplates()
    {
        $config = $this->owner->config()->required_js_templates;
        
        $this->owner->extend('updateRequiredJSTemplates', $config);
        
        return $this->owner->processJSTemplateConfig($config);
    }
    
    /**
     * Answers true if the required JavaScript is disabled.
     *
     * @return boolean
     */
    public function getRequiredJSDisabled()
    {
        return false;
    }
    
    /**
     * Answers a unique ID for the HTML template.
     *
     * @return string
     */
    public function getHTMLID()
    {
        return "{$this->owner->ClassName}_{$this->owner->ID}";
    }
    
    /**
     * Answers a unique ID for a CSS stylesheet.
     *
     * @return string
     */
    public function getCSSID()
    {
        return "#" . $this->owner->getHTMLID();
    }
    
    /**
     * Answers the CSS ID path for the extended object.
     *
     * @return string
     */
    public function getCSSIDPath()
    {
        $ids = array();
        
        $ids[] = $this->owner->getCSSID();
        
        $Parent = $this->owner->Parent();
        
        while ($Parent instanceof SilverWareComponent) {
            
            $ids[] = $Parent->getCSSID();
            
            $Parent = $Parent->Parent();
            
        }
        
        return implode(' ', array_reverse($ids));
    }
    
    /**
     * Answers an array of variables required by a JavaScript template.
     *
     * @return array
     */
    public function getJSVars()
    {
        return array('HTMLID' => $this->owner->getHTMLID(), 'CSSID' => $this->owner->getCSSID());
    }
    
    /**
     * Answers an array of variables for a required JavaScript template.
     *
     * @param string|array $vars Array of variables or method name.
     * @return array
     */
    public function getJSTemplateVars($vars)
    {
        if (is_array($vars)) {
            return $vars;
        }
        
        if ($this->owner->hasMethod($vars)) {
            return $this->owner->{$vars}();
        }
        
        return array();
    }
    
    /**
     * Answers the ID for a required JavaScript template.
     *
     * @param string $id ID or method name.
     * @return string
     */
    public function getJSTemplateID($id)
    {
        if ($this->owner->hasMethod($id)) {
            return $this->owner->{$id}();
        }
        
        return $id;
    }
    
    /**
     * Processes the given JavaScript template config and returns an array suitable for loading requirements.
     *
     * @param array $config
     * @return array
     */
    public function processJSTemplateConfig($config)
    {
        $templates = array();
        
        foreach ($config as $key => $value) {
            
            if (is_integer($key)) {
                
                $templates[$value] = array(
                    'vars' => $this->owner->getJSVars(),
                    'id'   => $this->owner->getHTMLID()
                );
                
            } else {
                
                $templates[$key] = array(
                    'vars' => $this->owner->getJSTemplateVars(array_shift($value)),
                    'id'   => $this->owner->getJSTemplateID(array_shift($value))
                );
                
            }
            
        }
        
        return $templates;
    }
    
    /**
     * Answers the name of the module for the receiver.
     *
     * @return string
     */
    public function getModuleName()
    {
        $classLoader = SS_ClassLoader::instance();
        
        $classPath = $classLoader->getItemPath($this->owner->ClassName);
        
        foreach ($classLoader->getManifest()->getModules() as $moduleName => $modulePath) {
            
            if (strpos($classPath, $modulePath . '/') !== false) {
                return $moduleName;
            }
            
        }
    }
}
