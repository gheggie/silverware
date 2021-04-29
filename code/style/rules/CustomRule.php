<?php

/**
 * An extension of the style rule class for a custom rule.
 */
class CustomRule extends StyleRule
{
    private static $singular_name = "Custom Rule";
    private static $plural_name   = "Custom Rules";
    
    private static $db = array(
        'RuleType' => 'Varchar(128)'
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
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a string describing the type of rule.
     *
     * @return string
     */
    public function getType()
    {
        if ($this->RuleType) {
            return $this->RuleType . " " . _t('CustomRule.RULE', 'Rule');
        }
        
        return parent::getType();
    }
    
    /**
     * Answers the CSS selector for the receiver.
     *
     * @return string|array
     */
    public function getSelector()
    {
        return $this->DeviceStyle()->getSelectorForRule($this);
    }
}
