<?php

/**
 * An extension of the data extension class to add component area functionality to pages.
 */
class SilverWareAreaExtension extends DataExtension
{
    /**
     * Answers the SilverWare panel associated with the given area component.
     *
     * @param AreaComponent $Area
     * @return SilverWarePanel|null
     */
    public function getPanelForArea(AreaComponent $Area)
    {
        // Answer Panel from Owner:
        
        foreach ($this->owner->Panels() as $Panel) {
            
            if ($Panel->HasArea($Area)) {
                return $Panel;
            }
            
        }
        
        // Answer Panel from Parent:
        
        $Parent = $this->owner->Parent();
        
        if ($Parent instanceof Page) {
            return $Parent->getPanelForArea($Area);
        }
        
        // Answer Panel for All Pages:
        
        foreach ($Area->Panels() as $Panel) {
            
            if ($Panel->ShowOnAll()) {
                return $Panel;
            }
            
        }
    }
}
