<?php

/**
 * An extension of the data extension class to allow pages to make use of the display grid.
 */
class SilverWareGridExtension extends DataExtension implements StyleTemplate
{
    /**
     * Answers all enabled components set to hide on the specified display type.
     *
     * @param string $display_type
     * @return DataList
     */
    public function HiddenComponents($display_type)
    {
        return $this->owner->getEnabledComponents()->filter(array('HideOn' => $display_type));
    }
    
    /**
     * Answers all enabled components set to show on the specified display type.
     *
     * @param string $display_type
     * @return DataList
     */
    public function ShownComponents($display_type)
    {
        return $this->owner->getEnabledComponents()->filter(array('ShowOn' => $display_type));
    }
    
    /**
     * Answers an array list containing shown/hidden components and the associated display rules.
     *
     * @return ArrayList
     */
    public function DisplayGrid()
    {
        // Create Grid Array List:
        
        $grid = ArrayList::create();
        
        // Iterate Display Grid Config:
        
        foreach (SilverWareGrid::inst()->getDisplayGrid() as $Display => $MinWidth) {
            
            // Obtain Shown and Hidden Components:
            
            $Shown =  $this->owner->ShownComponents($Display);
            $Hidden = $this->owner->HiddenComponents($Display);
            
            // Add Components and Display Rules:
            
            if ($Shown->count() || $Hidden->count()) {
                
                $grid->push(
                    ArrayData::create(
                        array(
                            'Shown' => $Shown,
                            'Hidden' => $Hidden,
                            'Display' => $Display,
                            'MinWidth' => $MinWidth
                        )
                    )
                );
                
            }
            
        }
        
        // Answer Grid Array List:
        
        return $grid;
    }
}
