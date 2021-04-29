<?php

/**
 * An extension of the data object class for a style rule.
 */
class StyleRule extends DataObject
{
    private static $singular_name = "Rule";
    private static $plural_name   = "Rules";
    
    private static $selector = null;
    
    private static $default_sort = "Sort";
    
    private static $db = array(
        'Sort' => 'Int',
        'Name' => 'Varchar(255)',
        'State' => 'Varchar(128)',
        'Disabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'DeviceStyle' => 'DeviceStyle'
    );
    
    private static $defaults = array(
        'Disabled' => 0
    );
    
    private static $summary_fields = array(
        'Type' => 'Type',
        'Name' => 'Name',
        'State' => 'State',
        'Description' => 'Description',
        'Disabled.Nice' => 'Disabled'
    );
    
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
                    _t('StyleRule.NAME', 'Name')
                ),
                DropdownField::create(
                    'State',
                    _t('StyleRule.STATE', 'State'),
                    $this->config()->state_options
                )->setEmptyString(' '),
                CheckboxField::create(
                    'Disabled',
                    _t('StyleRule.DISABLED', 'Disabled')
                )
            )
        );
        
        // Create Style Tab:
        
        $fields->findOrMakeTab('Root.Style', _t('StyleRule.STYLE', 'Style'));
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->Name = $this->getType();
    }
    
    /**
     * Answers a string describing the type of rule.
     *
     * @return string
     */
    public function getType()
    {
        return $this->i18n_singular_name();
    }
    
    /**
     * Answers a short string describing the type of rule.
     *
     * @return string
     */
    public function getShortType()
    {
        if ($type = $this->getType()) {
            
            $pos = strpos($type, 'Rule');
            
            if ($pos !== false) {
                $type = substr($type, 0, ($pos - 1));
            }
            
            if ($this->State) {
                $type .= ":{$this->State}";
            }
            
            return $type;
            
        }
    }
    
    /**
     * Answers a description for the rule which is displayed in the grid field.
     *
     * @return string
     */
    public function getDescription()
    {
        return null;
    }
    
    /**
     * Answers true if the receiver has a CSS selector.
     *
     * @return boolean
     */
    public function hasSelector()
    {
        return (boolean) $this->getSelector();
    }
    
    /**
     * Answers the CSS selector for the receiver.
     *
     * @return string|array
     */
    public function getSelector()
    {
        return $this->config()->selector;
    }
    
    /**
     * Answers true if the rule is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return !$this->Disabled;
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
        
        // Generate CSS (if selectors available and enabled):
        
        if (!empty($selectors) && $this->isEnabled()) {
            
            // Update CSS via Extensions:
            
            $this->extend('updateCustomCSS', $css);
            
            // Filter CSS Array:
            
            $css = array_filter($css);
            
            // Merge Prefix & Suffix CSS:
            
            if (!empty($css)) {
                
                $css = array_merge(
                    $this->getPreRuleCSS(),
                    $this->getRuleCSS($selectors, $css),
                    $this->getPostRuleCSS()
                );
                
            }
            
            // Filter CSS Array:
            
            $css = array_filter($css);
            
        }
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Answers an array of CSS to merge before the rule CSS.
     *
     * @return array
     */
    public function getPreRuleCSS()
    {
        return array();
    }
    
    /**
     * Answers an array of CSS to merge after the rule CSS.
     *
     * @return array
     */
    public function getPostRuleCSS()
    {
        return array();
    }
    
    /**
     * Answers an array of prefix CSS required for the template.
     *
     * @param array $prefixes
     * @return array
     */
    public function getPrefixCSS($prefixes = array())
    {
        return array(implode(",\n", $prefixes) . " {");
    }
    
    /**
     * Answers the CSS for the style rule.
     *
     * @param array $selectors
     * @param array $css
     * @return array
     */
    public function getRuleCSS($selectors = array(), $css = array())
    {
        return $this->processRuleCSS(
            array_merge(
                $this->getPrefixCSS($selectors),
                $css,
                $this->getSuffixCSS()
            )
        );
    }
    
    /**
     * Answers an array of suffix CSS required for the template.
     *
     * @return array
    */
    public function getSuffixCSS()
    {
        return array("}\n");
    }
    
    /**
     * Answers an array containing the selectors of the components using this style.
     *
     * @param array $prefixes
     * @return array
     */
    public function getSelectors($prefixes = array())
    {
        // Create Selectors Array:
        
        $selectors = array();
        
        // Obtain Prefixes (from device):
        
        if (empty($prefixes)) {
            $prefixes = $this->getPrefixes();
        }
        
        // Iterate Selector Prefixes:
        
        foreach ($prefixes as $prefix) {
            
            if ($this->hasSelector()) {
                
                $selector = $this->getSelector();
                
                if (is_array($selector)) {
                    
                    foreach ($selector as $suffix) {
                        $selectors[] = $prefix . ' ' . $suffix;
                    }
                    
                } else {
                    
                    $selectors[] = $prefix . ' ' . $selector;
                    
                }
                
            } else {
                
                $selectors[] = $prefix;
                
            }
            
        }
        
        // Apply State Pseudo Class (if required):
        
        if ($this->State) {
            
            for ($i = 0; $i < count($selectors); $i++) {
                $selectors[$i] .= ":{$this->State}";
            }
            
        }
        
        // Answer Selectors Array:
        
        return $selectors;
    }
    
    /**
     * Answers an array containing the prefixes of the components using this style.
     *
     * @return array
     */
    public function getPrefixes()
    {
        return $this->DeviceStyle()->getPrefixes();
    }
    
    /**
     * Processes the given rule CSS array.
     *
     * @param array $css
     * @return array
     */
    protected function processRuleCSS($css = array())
    {
        return $css;
    }
}
