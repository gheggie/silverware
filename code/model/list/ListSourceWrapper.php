<?php

/**
 * An wrapper class to allow regular list objects to operate as list sources.
 */
class ListSourceWrapper extends Object implements ListSource
{
    /**
    * @var SS_List
    */
    protected $list;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param SS_List $list
     */
    public function __construct(SS_List $list)
    {
        $this->list = $list;
        
        parent::__construct();
    }
    
    /**
     * Answers a list of items.
     *
     * @return SS_List
     */
    public function getListItems()
    {
        return $this->list;
    }
}
