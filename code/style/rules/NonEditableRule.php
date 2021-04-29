<?php

/**
 * An extension of the style rule class for a non-editable rule.
 */
class NonEditableRule extends StyleRule
{
    private static $singular_name = "Non-Editable Rule";
    private static $plural_name   = "Non-Editable Rules";
    
    protected $selector = null;
    protected $mappings = array();
    
    /**
     * Defines the value of the selector attribute.
     *
     * @param array|string $selector
     * @return NonEditableRule
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
        
        return $this;
    }
    
    /**
     * Answers the value of the selector attribute.
     *
     * @return array|string
     */
    public function getSelector()
    {
        return $this->selector;
    }
    
    /**
     * Defines the value of the mappings attribute.
     *
     * @param array $mappings
     * @return NonEditableRule
     */
    public function setMappings(array $mappings)
    {
        $this->mappings = $mappings;
        
        return $this;
    }
    
    /**
     * Answers the value of the mappings attribute.
     *
     * @return array
     */
    public function getMappings()
    {
        return $this->mappings;
    }
    
    /**
     * Answers an array of custom CSS required for the template.
     *
     * @param array $prefixes
     * @return array
     */
    public function getCustomCSS($prefixes = array())
    {
        // Create CSS Array:
        
        $css = array();
        
        // Obtain Selectors:
        
        $selectors = $this->getSelectors($prefixes);
        
        // Generate CSS (if selectors available):
        
        if (!empty($selectors)) {
            
            // Process CSS Mappings:
            
            $css = $this->processMappings();
            
            // Filter CSS Array:
            
            $css = array_filter($css);
            
            // Merge Prefix & Suffix CSS:
            
            if (!empty($css)) {
                
                $css = array_merge($this->getPrefixCSS($selectors), $css, $this->getSuffixCSS());
                
            }
            
        }
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Processes the mappings of the receiver and answers an array of CSS.
     *
     * @return array
     */
    public function processMappings()
    {
        $css = array();
        
        foreach ($this->mappings as $attribute => $mapping) {
            
            if ($value = $this->processMapping($mapping)) {
                
                $attribute = $this->processAttribute($attribute);
                
                $css[] = "  {$attribute}: {$value};";
                
            }
            
        }
        
        return $css;
    }
    
    /**
     * Processes the given mapping and answers the value.
     *
     * @param string $mapping
     * @return string
     */
    protected function processMapping($mapping)
    {
        if (strpos($mapping, '.') !== false) {
            
            list($type, $property) = explode('.', $mapping);
            
            if ($rule = $this->DeviceStyle()->getRuleByType($type)) {
                return $rule->$property;
            }
        }
    }
    
    /**
     * Processes the given attribute and answers the name.
     *
     * @param string $attribute
     * @return string
     */
    protected function processAttribute($attribute)
    {
        if (substr($attribute, 0, 1) == '$') {
            return $this->DeviceStyle()->getMappedAttribute($attribute);
        }
        
        return $attribute;
    }
}
