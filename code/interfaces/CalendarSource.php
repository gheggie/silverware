<?php

/**
 * Interface for classes which provide events for a calendar component.
 */
interface CalendarSource
{
    /**
     * Answers a list of calendar events between the specified dates.
     *
     * @param Date $from
     * @param Date $to
     * @return SS_List
     */
    public function getCalendarEvents(Date $from, Date $to);
    
    /**
     * Answers an array containing calendar event source configuration.
     *
     * @return CalendarEventSource
     */
    public function getCalendarEventSource();
}
