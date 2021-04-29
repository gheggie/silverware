<?php

/**
 * An extension of the base component class for a content component.
 */
class ContentComponent extends BaseComponent
{
    private static $singular_name = "Content Component";
    private static $plural_name   = "Content Components";
    
    private static $description = "A component to show a block of editable content";
    
    private static $icon = "silverware/images/icons/components/ContentComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
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
            'Root.Main',
            HtmlEditorField::create('Content', _t('ContentComponent.CONTENT', 'Content'))
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the content for the HTML template.
     *
     * @param string $layout
     * @param string $title
     * @return string
     */
    public function Content($layout = null, $title = null)
    {
        return $this->dbObject('Content');
    }
}

/**
 * An extension of the base component controller class for a content component.
 */
class ContentComponent_Controller extends BaseComponent_Controller
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
