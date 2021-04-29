<?php

/**
 * An extension of the viewable data class for a calendar event source.
 */
class CalendarEventSource extends ViewableData
{
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
        'BackgroundColor' => 'Color',
        'ForegroundColor' => 'Color'
    );
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($url = null)
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        $this->setURL($url);
    }
    
    /**
     * Defines the value of the URL attribute.
     *
     * @param string $url
     * @return CalendarEventSource
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
     * @return CalendarEventSource
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