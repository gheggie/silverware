<?php

/**
 * An extension of the extension class which adds extra breadcrumbs functionality to controllers.
 */
class SilverWareBreadcrumbsExtension extends Extension
{
    /**
     * Returns a breadcrumb trail for the current page.
     *
     * @param int $maxDepth
     * @param boolean $unlinked
     * @param boolean|string $stopAtPageType
     * @param boolean $showHidden
     * @return HTMLText
     */
    public function Breadcrumbs($maxDepth = 20, $unlinked = false, $stopAtPageType = false, $showHidden = false)
    {
        $pages = $this->getBreadcrumbItems($maxDepth, $stopAtPageType, $showHidden);
        
        $template = new SSViewer('BreadcrumbsTemplate');
        
        return $template->process(
            $this->owner->customise(
                array(
                    'Pages' => $pages,
                    'Unlinked' => $unlinked
                )
            )
        );
    }
    
    /**
     * Returns a list of breadcrumbs for the current page.
     *
     * @param int $maxDepth
     * @param boolean|string $stopAtPageType
     * @param boolean $showHidden
     * @return ArrayList
     */
    public function getBreadcrumbItems($maxDepth = 20, $stopAtPageType = false, $showHidden = false)
    {
        $items = $this->owner->data()->getBreadcrumbItems($maxDepth, $stopAtPageType, $showHidden);
        
        $extra = $this->owner->getExtraBreadcrumbItems();
        
        $extra->each(function($item) {
            
            if (!($item instanceof SiteTree) && !($item instanceof Crumbable)) {
                user_error(
                    sprintf(
                        "Item of class '%s' is not a SiteTree object or an implementor of Crumbable",
                        get_class($item)
                    ),
                    E_USER_ERROR
                );
            }
            
        });
        
        $items->merge($extra);
        
        return $items;
    }
    
    /**
     * Answers a list of extra breadcrumb items for the template (depending on controller state).
     *
     * @return ArrayList
     */
    public function getExtraBreadcrumbItems()
    {
        return ArrayList::create();
    }
}
