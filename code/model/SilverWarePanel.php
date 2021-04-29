<?php

/**
 * An extension of the SilverWare component class for a SilverWare panel.
 */
class SilverWarePanel extends SilverWareComponent
{
    private static $singular_name = "Panel";
    private static $plural_name   = "Panels";
    
    private static $description = "An individual SilverWare panel";
    
    private static $icon = "silverware/images/icons/SilverWarePanel.png";
    
    private static $hide_ancestor = "SilverWareComponent";
    
    private static $can_be_root = false;
    
    private static $db = array(
        'ShowOn' => 'Varchar(16)'
    );
    
    private static $defaults = array(
        'ShowOn' => 'all'
    );
    
    private static $many_many = array(
        'Pages' => 'Page',
        'Areas' => 'AreaComponent'
    );
    
    private static $allowed_children = array(
        'BaseComponent'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Remove Field Objects:
        
        $fields->fieldByName('Root')->removeByName('Style');
        $fields->fieldByName('Root')->removeByName('Options');

        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                CheckboxSetField::create(
                    'Areas',
                    _t('SilverWarePanel.AREAS', 'Areas'),
                    AreaComponent::get()->map()
                ),
                LabeledSelectionGroup::create(
                    'ShowOn',
                    _t('SilverWarePanel.SHOWON', 'Show On'),
                    array(
                        SelectionGroup_Item::create(
                            'all',
                            null,
                            _t('SilverWarePanel.ALLPAGES', 'All Pages')
                        ),
                        SelectionGroup_Item::create(
                            'selection',
                            TreeMultiselectField::create(
                                'Pages',
                                '',
                                'Page'
                            ),
                            _t('SilverWarePanel.ONLYTHESEPAGES', 'Only These Pages')
                        )
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers true if the panel is to be shown on all pages.
     *
     * @return boolean
     */
    public function ShowOnAll()
    {
        return ($this->ShowOn == 'all');
    }
    
    /**
     * Answers true if the panel is associated with the given area component.
     *
     * @return boolean
     */
    public function HasArea(AreaComponent $Area)
    {
        return !is_null($this->Areas()->find('ID', $Area->ID));
    }
}

/**
 * An extension of the base component controller class for a SilverWare panel.
 */
class SilverWarePanel_Controller extends BaseComponent_Controller
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
