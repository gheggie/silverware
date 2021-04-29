<?php

/**
 * An extension of the dropdown field class which allows the user to select an icon.
 */
class IconDropdownField extends DropdownField
{
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $name
     * @param string $title
     * @param array|ArrayAccess $source
     * @param string $value
     * @param Form $form
     */
    public function __construct($name, $title = null, $source = array(), $value = '', $form = null)
    {
        parent::__construct($name, $title, SilverWareFontIconExtension::get_icon_map($source), $value, $form);
    }
    
    /**
     * Renders the receiver for the HTML template.
     *
     * @param array $properties
     * @return string
     */
    public function Field($properties = array())
    {
        // Load Required Scripts:
        
        Requirements::javascript(SILVERWARE_DIR . '/javascript/forms/IconDropdownField.js');
        
        // Render Field as HTML:
        
        return parent::Field($properties);
    }
}
