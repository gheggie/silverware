<?php

/**
 * An extension of the SilverWare style for a component style.
 */
class ComponentStyle extends SilverWareStyle
{
    private static $singular_name = "Component Style";
    private static $plural_name   = "Component Styles";
    
    private static $has_many = array(
        'StyleComponents' => 'SilverWareComponent.StyleRules',
        'ChildComponents' => 'SilverWareComponent.ChildRules'
    );
    
    /**
     * Answers an array of prefixes for the components associated with this style.
     *
     * @return array
     */
    public function getPrefixes()
    {
        $prefixes = array();
        
        foreach ($this->StyleComponents() as $Component) {
            $prefixes[] = $Component->getCSSID();
        }
        
        foreach ($this->ChildComponents() as $Component) {
            $prefixes[] = $Component->getCSSID() . ' div.basecomponent';
        }
        
        return $prefixes;
    }
}
