<?php

/**
 * Interface for classes which can be rendered as breadcrumbs within the template (typically DataObject subclasses).
 */
interface Crumbable
{
        
    /**
     * Answers the menu title for the receiver.
     *
     * @return string
     */
    public function getMenuTitle();
    
    /**
     * Answers the link for the receiver.
     *
     * @param string $action
     * @return string
     */
    public function Link($action = null);
}
