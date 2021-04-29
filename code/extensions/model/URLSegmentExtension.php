<?php

/**
 * An extension of the data extension class to add a URL segment to the extended object.
 */
class URLSegmentExtension extends DataExtension
{
    private static $db = array(
        'URLSegment' => 'Varchar(255)'
    );
    
    private static $indexes = array(
        'URLSegment' => true
    );
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Generate URL Segment:
        
        $this->owner->URLSegment = $this->owner->generateURLSegment($this->owner->Title);
        
        // Check for Duplicates:
        
        $count = 2;
        
        while (!$this->owner->validURLSegment()) {
            
            $this->owner->URLSegment = preg_replace('/-[0-9]+$/', null, $this->owner->URLSegment) . '-' . $count;
            
            $count++;
            
        }
    }
    
    /**
     * Creates any required default records (if they do not already exist).
     */
    public function requireDefaultRecords()
    {
        $class = $this->owner->class;
        
        $records = DataObject::get($class)->where("URLSegment IS NULL");
        
        if ($records->exists()) {
            
            foreach ($records as $record) {
                $record->write();
            }
            
            DB::alteration_message("Updated {$class} records without URL segments", "changed");
        }
    }
    
    /**
     * Answers true if the extended object has a valid URL segment.
     *
     * @return boolean
     */
    public function validURLSegment()
    {
        $list = DataList::create($this->owner->ClassName)->filter(array('URLSegment' => $this->owner->URLSegment));
        
        if ($this->owner->ID) {
            
            $list = $list->exclude(array('ID' => $this->owner->ID));
            
        }
        
        return !($list->exists());
    }
    
    /**
     * Generates a URL segment for the extended object based on the given title.
     *
     * @param string $title
     * @return string
     */
    public function generateURLSegment($title)
    {
        if (!$title) {
            
            $title = $this->owner->ClassName . '-' . $this->owner->ID;
            
        }
        
        $segment = URLSegmentFilter::create()->filter($title);
        
        $this->owner->extend('updateURLSegment', $segment, $title);
        
        return $segment;
    }
}
