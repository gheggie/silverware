<?php

/**
 * An extension of the viewable data class for a calendar event.
 */
class CalendarEvent extends ViewableData
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $start;
    
    /**
     * @var string
     */
    protected $end;
    
    /**
     * @var string
     */
    protected $url;
    
    /**
     * @var string
     */
    protected $styleClasses;
    
    /**
     * @var string
     */
    protected $backgroundColor;
    
    /**
     * @var string
     */
    protected $foregroundColor;
    
    /**
     * @var array
     */
    protected $extraClasses = array();
    
    private static $casting = array(
        'Start' => 'SS_Datetime',
        'End'   => 'SS_Datetime',
        'BackgroundColor' => 'Color',
        'ForegroundColor' => 'Color'
    );
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($title = null, $start = null, $end = null, $url = null)
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        $this->setTitle($title);
        $this->setStart($start);
        $this->setEnd($title);
        $this->setURL($url);
    }
    
    /**
     * Defines the value of the title attribute.
     *
     * @param string $title
     * @return CalendarEvent
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * Answers the value of the title attribute.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Defines the value of the start attribute.
     *
     * @param string $start
     * @return CalendarEvent
     */
    public function setStart($start)
    {
        $this->start = $start;
        
        return $this;
    }
    
    /**
     * Answers the value of the start attribute.
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }
    
    /**
     * Defines the value of the end attribute.
     *
     * @param string $end
     * @return CalendarEvent
     */
    public function setEnd($end)
    {
        $this->end = $end;
        
        return $this;
    }
    
    /**
     * Answers the value of the end attribute.
     *
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }
    
    /**
     * Defines the value of the URL attribute.
     *
     * @param string $url
     * @return CalendarEvent
     */
    public function setURL($url)
    {
        $this->url = $url;
        
        return $this;
    }
    
    /**
     * Answers the value of the URL attribute.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }
    
    /**
     * Defines the value of the styleClasses attribute.
     *
     * @param string $styleClasses
     * @return CalendarEvent
     */
    public function setStyleClasses($styleClasses)
    {
        $this->styleClasses = $styleClasses;
        
        return $this;
    }
    
    /**
     * Answers the value of the styleClasses attribute.
     *
     * @return string
     */
    public function getStyleClasses()
    {
        return $this->styleClasses;
    }
    
    /**
     * Defines the value of the backgroundColor attribute.
     *
     * @param string $backgroundColor
     * @return CalendarEvent
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        
        return $this;
    }
    
    /**
     * Answers the value of the backgroundColor attribute.
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }
    
    /**
     * Defines the value of the foregroundColor attribute.
     *
     * @param string $foregroundColor
     * @return CalendarEvent
     */
    public function setForegroundColor($foregroundColor)
    {
        $this->foregroundColor = $foregroundColor;
        
        return $this;
    }
    
    /**
     * Answers the value of the foregroundColor attribute.
     *
     * @return string
     */
    public function getForegroundColor()
    {
        return $this->foregroundColor;
    }
    
    /**
     * Answers a string of class names for the HTML template.
     *
     * @return string
     */
    public function getHTMLClass()
    {
        return implode(' ', $this->getClassNames());
    }
    
    /**
     * Answers an array of class names for the receiver.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = array();
        
        if ($this->extraClasses) {
            $classes = array_merge($classes, $this->extraClasses);
        }
        
        if ($this->styleClasses) {
            $classes = array_merge($classes, preg_split('/\s+/', trim($this->styleClasses)));
        }
        
        $this->extend('updateClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Adds one or more extra class names to the receiver.
     *
     * @param string $class (space-delimited for multiple class names)
     * @return CalendarEvent
     */
    public function addExtraClass($class)
    {
        $classes = preg_split('/\s+/', trim($class));
        
        foreach ($classes as $class) {
            $this->extraClasses[$class] = $class;
        }
        
        return $this;
    }
    
    /**
     * Converts the receiver to a map array.
     *
     * @return array
     */
    public function toMap()
    {
        // Create Map Array:
        
        $map = array();
        
        // Define Map Array:
        
        $map['title'] = $this->Title;
        
        if ($this->Start) {
            $map['start'] = $this->obj('Start')->Rfc2822();
        }
        
        if ($this->End) {
            $map['end'] = $this->obj('End')->Rfc2822();
        }
        
        if ($this->URL) {
            $map['url'] = $this->URL;
        }
        
        if ($this->HTMLClass) {
            $map['className'] = $this->HTMLClass;
        }
        
        if ($this->BackgroundColor) {
            $map['color'] = "#{$this->BackgroundColor}";
        }
        
        if ($this->ForegroundColor) {
            $map['textColor'] = "#{$this->ForegroundColor}";
        }
        
        // Answer Map Array:
        
        return $map;
    }
}
