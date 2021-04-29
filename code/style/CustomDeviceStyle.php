<?php

/**
 * An extension of the device style class for a custom device style.
 */
class CustomDeviceStyle extends DeviceStyle
{
    private static $singular_name = "Device Style";
    private static $plural_name   = "Device Styles";
    
    private static $has_many = array(
        'Rules' => 'CustomRule'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Modify Rules Fields (if saved):
        
        if ($this->ID) {
            
            // Create Grid Field Config:
            
            $rulesConfig = GridFieldConfig_OrderableEditor::create();
            
            // Obtain Edit Form Component:
            
            $editComponent = $rulesConfig->getComponentByType('GridFieldDetailForm');
            
            // Pass $this as $self (compatibility with PHP 5.3):
            
            $self = $this;
            
            // Define Edit Form Callback:
            
            $editComponent->setItemEditFormCallback(function ($form, $itemRequest) use ($self) {
                
                // Obtain Child Record:
                
                $record = $form->getRecord();
                
                // Add Dropdown Field:
                
                $form->Fields()->addFieldToTab(
                    'Root.Main',
                    DropdownField::create(
                        'RuleType',
                        _t('CustomRule.TYPE', 'Type'),
                        $self->getEditableRuleTypes(),
                        $record->RuleType
                    ),
                    'State'
                );
                
            });
            
            // Obtain Grid Field Object:
            
            if ($grid = $fields->fieldByName('Root.Rules.Rules')) {
                
                $grid->setConfig($rulesConfig);
                
            }
            
        }
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers an array of custom CSS generated by the rules for this device.
     *
     * @param array $prefixes
     * @return array
     */
    public function getRulesCSS($prefixes = array())
    {
        $css = parent::getRulesCSS($prefixes);
        
        foreach ($this->NonEditableRules() as $Rule) {
            
            $css = array_merge($css, $this->indent($Rule->getCustomCSS($prefixes)));
            
        }
        
        return $css;
    }
    
    /**
     * Answers the first non-disabled rule object found of the specified type.
     *
     * @param string $type
     * @return CustomRule
     */
    public function getRuleByType($type)
    {
        return $this->Rules()->filter(
            array(
                'RuleType' => $type,
                'Disabled' => 0
            )
        )->first();
    }
    
    /**
     * Answers the mapped attribute with the given name.
     *
     * @param string $name
     * @return string
     */
    public function getMappedAttribute($name)
    {
        return $this->Style()->getMappedAttribute($name);
    }
    
    /**
     * Answers a map of the editable rule types for this style and device.
     *
     * @return array
     */
    public function getEditableRuleTypes()
    {
        return $this->Style()->getEditableRuleTypes($this);
    }
    
    /**
     * Answers the selector for the given rule.
     *
     * @param CustomRule $rule
     * @return string|array
     */
    public function getSelectorForRule(CustomRule $rule)
    {
        return $this->Style()->getSelectorForRule($rule);
    }
    
    /**
     * Answers a list of non-editable rule objects defined by configuration.
     *
     * @return ArrayList
     */
    public function NonEditableRules()
    {
        return $this->Style()->NonEditableRules($this);
    }
}
