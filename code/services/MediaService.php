<?php

/**
 * An extension of the viewable data class for the abstract parent class of media service implementations.
 */
abstract class MediaService extends ViewableData
{
    /**
    * @var string URL of the media resource
    */
    protected $url;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        $this->setUrl($url);
    }
    
    /**
     * Defines the value of the $url attribute.
     *
     * @param string $url
     * @return MediaService
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
        
        return $this;
    }
    
    /**
     * Answers the value of the $url attribute.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Extracts the media ID from the URL attribute.
     *
     * @return string
     */
    abstract public function getID();
    
    /**
     * Answers the embed URL for the resource.
     *
     * @return string
     */
    abstract public function getEmbedURL();
    
    /**
     * Answers the media URL for the resource.
     *
     * @return string
     */
    abstract public function getMediaURL();
    
    /**
     * Answers the thumbnail for the resource.
     *
     * @return string
     */
    abstract public function getThumbnail();
    
    /**
     * Answers true if the service handles the given URL.
     *
     * @param string $URL
     * @return boolean
     */
    public function handlesURL($url)
    {
        return (boolean) self::create($url)->getID();
    }
    
    /**
     * Answers the embed code for the resource.
     *
     * @return string
     */
    public function getEmbed()
    {
        return $this->renderWith($this->class . '_embed');
    }
}
