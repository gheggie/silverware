<?php

/**
 * An extension of the data extension class to add calendar source functionality to the extended object.
 */
class CalendarSourceExtension extends DataExtension
{
    private static $db = array(

    );
    
    private static $defaults = array(

    );
    
    private static $many_many = array(
        'CalendarSources' => 'SiteTree'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                CheckboxSetField::create(
                    'CalendarSources',
                    _t('CalendarSourceExtension.CALENDARSOURCES', 'Calendar Sources'),
                    SilverWareTools::implementor_map('CalendarSource')
                )->addExtraClass('object-list')
            )
        );
        
         // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'CalendarSourceOptions',
                _t('CalendarSourceExtension.CALENDARSOURCE', 'Calendar Source'),
                array(
                    
                )
            )
        );
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {

    }
    
    /**
     * Answers the list of events from the calendar sources.
     *
     * @param Date $from
     * @param Date $to
     * @return ArrayList
     */
    public function getCalendarEvents(Date $from, Date $to)
    {
        // Create Events List:
        
        $events = ArrayList::create();
        
        // Obtain Calendar Events:
        
        if ($sources = $this->owner->getSources()) {
            
            foreach ($sources as $source) {
                $events->merge($source->getCalendarEvents($from, $to));
            }
            
        }
        
        // Answer Events List:
        
        return $events;
    }
    
    /**
     * Adds the given calendar source to the extended object.
     *
     * @param CalendarSource|SS_List $source
     */
    public function addSource($source)
    {
        if ($source instanceof CalendarSource) {
            $this->owner->CalendarSources()->add($source);
        }
        
        if ($source instanceof SS_List) {
            $this->owner->CalendarSources()->add(CalendarSourceWrapper::create($source));
        }
    }
    
    /**
     * Answers the calendar sources for the extended object.
     *
     * @return SS_List
     */
    public function getSources()
    {
        return $this->owner->CalendarSources();
    }
    
    /**
     * Answers the calendar event sources for the extended object.
     *
     * @return ArrayList
     */
    public function getEventSources()
    {
        $eventSources = ArrayList::create();
        
        if ($sources = $this->getSources()) {
            
            foreach ($sources as $source) {
                
                if ($eventSource = $source->getCalendarEventSource()) {
                    $eventSources->push($eventSource);
                }
                
            }
            
        }
        
        return $eventSources;
    }
}
