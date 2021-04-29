<?php

/**
 * An extension of the selection group class for a labeled selection group.
 */
class LabeledSelectionGroup extends SelectionGroup
{
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $name
     * @param string|null $title
     * @param array $items
     * @param mixed $value
     */
    public function __construct($name, $title = null, $items = array(), $value = null)
    {
        // Construct Parent:
        
        parent::__construct($name, $items, $value);
        
        // Construct Object:
        
        if (is_null($title)) {
            $this->title = self::name_to_label($name);
        } else {
            $this->title = $title;
        }
    }
    
    /**
     * Renders the form field holder.
     *
     * @param array $properties
     * @return string
     */
    public function FieldHolder($properties = array())
    {
        Requirements::css(SILVERWARE_DIR . '/css/forms/LabeledSelectionGroup.css');
        
        return parent::FieldHolder($properties);
    }
}
