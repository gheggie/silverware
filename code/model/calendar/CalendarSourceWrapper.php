<?php

/**
 * An wrapper class to allow regular list objects to operate as calendar sources.
 */
class CalendarSourceWrapper extends Object implements CalendarSource
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
     * Answers a list of calendar events.
     *
     * @return SS_List
     */
    public function getCalendarEvents()
    {
        return $this->list;
    }
}
