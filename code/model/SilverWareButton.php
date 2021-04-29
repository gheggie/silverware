<?php

/**
 * An extension of the SilverWare link class for a button.
 */
class SilverWareButton extends SilverWareLink
{
    private static $singular_name = "Button";
    private static $plural_name   = "Buttons";
    
    private static $db = array(
        'ButtonType' => 'Varchar(16)'
    );
    
    private static $defaults = array(
        'ButtonType' => 'primary'
    );
    
    private static $types = array(
        'primary' => 'Primary',
        'secondary' => 'Secondary'
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
        
        // Create Field Objects:
        
        $fields->addFieldToTab(
            'Root.Main',
            DropdownField::create(
                'ButtonType',
                _t('SilverWareButton.BUTTONTYPE', 'Button type'),
                $this->config()->types
            ),
            'LinkPageID'
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers an array of class names for the receiver.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = parent::getClassNames();
        
        $classes[] = "button";
        
        $classes[] = $this->ButtonType;
        
        return $classes;
    }
    
    /**
     * Answers false to disable fixed width font icons.
     *
     * @return boolean
     */
    public function getFontIconFixedWidth()
    {
        return false;
    }
}
