<?php

/**
 * An extension of the SilverWare section class for a layout section.
 */
class LayoutSection extends SilverWareSection
{
    private static $singular_name = "Layout Section";
    private static $plural_name   = "Layout Sections";
    
    private static $description = "A layout section within a SilverWare template";
    
    private static $icon = "silverware/images/icons/sections/LayoutSection.png";
    
    private static $allowed_children = "none";
    
    /**
     * Answers the layout for the current page.
     *
     * @return SilverWareLayout
     */
    public function PageLayout()
    {
        if ($Page = $this->getCurrentPage()) {
            
            if ($Page instanceof Page) {
                return $Page->PageLayout();
            }
            
        }
        
        return Page::create()->PageLayout();
    }
    
    /**
     * Renders the receiver for the HTML template (overrides parent method for performance reasons).
     *
     * @param string $layout
     * @param string $title
     * @return string
     */
    public function Render($layout = null, $title = null)
    {
        // Initialise:
        
        $notfound = false;
        
        // Start Layout Wrapper:
        
        $output = sprintf("<main %s>\n", $this->getAttributesHTML());
        
        // Check Layout Existence:
        
        if ($this->PageLayout()->isInDB()) {
            
            // Check PageComponent Existence (required for rendering layout):
            
            if ($this->PageLayout()->hasPageComponent()) {
                
                // Render Layout Sections:
                
                foreach ($this->PageLayout()->Sections() as $Section) {
                    $output .= $Section->Render($layout, $title);
                }
                
            } else {
                
                // Page Component Not Found:
                
                $notfound = _t('LAYOUTSECTION.PageComponent', 'Page Component');
                
            }
            
        } else {
            
            // Layout Not Found:
            
            $notfound = _t('LAYOUTSECTION.Layout', 'Layout');
            
        }
        
        // Render Not Found Error:
        
        if ($notfound) {
            
            // Create Template Data:
            
            $data = ArrayData::create(
                array(
                    'Title' => $title,
                    'Layout' => $layout,
                    'Type' => $notfound
                )
            );
            
            // Render Not Found Template:
            
            $output .= $data->renderWith('Error_NotFound');
            
        }
        
        // Finish Layout Wrapper:
        
        $output .= "</main>\n";
        
        // Answer Output:
        
        return $output;
    }
}

/**
 * An extension of the SilverWare section controller class for a layout section.
 */
class LayoutSection_Controller extends SilverWareSection_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
