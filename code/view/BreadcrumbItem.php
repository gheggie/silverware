<?php

/**
 * An extension of the viewable data class for a breadcrumb item.
 */
class BreadcrumbItem extends ViewableData implements Crumbable
{
    /**
    * @var string
    */
    protected $link;
    
    /**
    * @var string
    */
    protected $title;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $url
     * @param string $title
     */
    public function __construct($link = null, $title = null)
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        $this->setLink($link);
        $this->setTitle($title);
    }
    
    /**
     * Defines the link of the receiver.
     *
     * @param string $link
     * @return BreadcrumbItem
     */
    public function setLink($link)
    {
        $this->link = (string) $link;
        
        return $this;
    }
    
    /**
     * Defines the title of the receiver.
     *
     * @param string $title
     * @return BreadcrumbItem
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        
        return $this;
    }
    
    /**
     * Defines the link and title of the receiver.
     *
     * @param string $link
     * @param string $title
     * @return BreadcrumbItem
     */
    public function setItem($link, $title)
    {
        $this->setLink($link);
        $this->setTitle($title);
        
        return $this;
    }
    
    /**
     * Answers the menu title for the receiver.
     *
     * @return string
     */
    public function getMenuTitle()
    {
        return $this->title;
    }
    
    /**
     * Answers the link for the receiver.
     *
     * @return string
     */
    public function Link($action = null)
    {
        return Controller::join_links($this->link, $action);
    }
}
