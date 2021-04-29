<?php

/**
 * An extension of the SilverWare component class for a SilverWare section.
 */
class SilverWareSection extends SilverWareComponent
{
    private static $singular_name = "Section";
    private static $plural_name   = "Sections";
    
    private static $description = "A section within a SilverWare template or layout";
    
    private static $icon = "silverware/images/icons/SilverWareSection.png";
    
    private static $hide_ancestor = "SilverWareComponent";
    
    private static $default_child = "GridRow";
    
    private static $db = array(
        'FullWidthContainer' => 'Boolean'
    );
    
    private static $defaults = array(
        'FullWidthContainer' => 0
    );
    
    private static $allowed_children = array(
        'GridRow'
    );
    
    protected $tag = "div";
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Field Objects:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'StyleGridToggle',
                _t('SilverWareSection.GRID', 'Grid'),
                array(
                    CheckboxField::create(
                        'FullWidthContainer',
                        _t('SilverWareSection.USEFULLWIDTHCONTAINER', 'Use full width container')
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a string of container class names for the HTML template.
     *
     * @return string
     */
    public function getContainerClass()
    {
        $classes = array('container');
        
        if ($this->FullWidthContainer) {
            $classes[] = "u-full-width u-max-full-width";
        }
        
        return implode(' ', $classes);
    }
    
    /**
     * Answers the grid rows within the receiver.
     *
     * @return DataList
     */
    public function Rows()
    {
        return $this->AllChildren()->filter('ClassName', 'GridRow');
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
        $output = sprintf(
            "<%s %s>\n<div class=\"%s\">\n",
            $this->tag,
            $this->getAttributesHTML(),
            $this->getContainerClass()
        );
        
        foreach ($this->Rows() as $Row) {
            $output .= $Row->Render($layout, $title);
        }
        
        $output .= sprintf("</div>\n</%s>\n", $this->tag);
        
        return $output;
    }
}

/**
 * An extension of the SilverWare component controller class for a SilverWare section.
 */
class SilverWareSection_Controller extends SilverWareComponent_Controller
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
