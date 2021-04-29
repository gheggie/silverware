<?php

/**
 * An extension of the controller class which provides additional features for the admin section.
 */
class SilverWareAdminController extends Controller
{
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        'editorcss'
    );
    
    /**
     * Answers a string of CSS for the admin editor field.
     *
     * @return string
     */
    public function editorcss()
    {
        // Create Template Data Array:
        
        $data = array();
        
        // Define Response Content-Type:
        
        $this->response->addHeader('Content-Type', 'text/css');
        
        // Add SiteConfig to Template Data:
        
        $data['SiteConfig'] = SiteConfig::current_site_config();
        
        // Add Extra CSS to Template Data:
        
        $data['ExtraCSS'] = $this->getExtraCSS();
        
        // Answer Rendered Editor CSS:
        
        return $this->customise($data)->renderWith(__CLASS__ . '_editorcss');
    }
    
    /**
     * Answers the page currently being edited in the CMS.
     *
     * @return SiteTree
     */
    protected function getCurrentPage()
    {
        return SiteTree::get()->byID($this->getCurrentPageID());
    }
    
    /**
     * Answers the ID of the page currently being edited in the CMS.
     *
     * @return integer
     */
    protected function getCurrentPageID()
    {
        return Session::get('CMSMain.currentPage');
    }
    
    /**
     * Answers the extra CSS to be included in the admin editor field CSS.
     *
     * @return string
     */
    protected function getExtraCSS()
    {
        $extra = array();
        
        foreach (SilverWareTheme::get_extra_typography_css() as $file) {
            $extra[] = file_get_contents(Director::getAbsFile($file));
        }
        
        return implode("\n", $extra);
    }
}
