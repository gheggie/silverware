<?php

/**
 * An extension of the data extension class which allows extended objects to be rendered in list components.
 */
class ListItemExtension extends DataExtension
{
    /**
     * Renders the extended object as a list item.
     *
     * @param array $data
     * @param string $template (optional)
     * @return HTMLText
     */
    public function RenderListItem($data, $template = null)
    {
        if (!$template) {
            $template = $this->getListItemTemplate();
        }
        
        return $this->owner->customise($data)->renderWith($template);
    }
    
    /**
     * Answers a string of class names for the list item.
     *
     * @return string
     */
    public function getListItemClass()
    {
        return implode(' ', $this->getListItemClassNames());
    }
    
    /**
     * Answers an array of class names for the list item.
     *
     * @return array
     */
    public function getListItemClassNames()
    {
        $classes = array('item');
        
        $classes[] = strtolower($this->owner->ClassName);
        
        if ($this->owner->hasMethod('MetaClassNames')) {
            
            $classes = array_merge($classes, $this->owner->MetaClassNames());
            
        }
        
        return $classes;
    }
    
    /**
     * Answers the name of the template for rendering the list item.
     *
     * @return string
     */
    public function getListItemTemplate()
    {
        $template = "{$this->owner->ClassName}_ListItem";
        
        if (SSViewer::hasTemplate($template)) {
            return $template;
        }
        
        return "ListItem";
    }
}
