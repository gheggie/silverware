<?php

/**
 * An extension of the base component class for a tile component.
 */
class TileComponent extends BaseComponent
{
    private static $singular_name = "Tile Component";
    private static $plural_name   = "Tile Components";
    
    private static $description = "Shows a list of items as a series of tiles";
    
    private static $icon = "silverware/images/icons/components/TileComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'ShowCaptions' => 'Boolean',
        'ImageWidth' => 'Varchar(16)',
        'ImageHeight' => 'Varchar(16)',
        'ImageResize' => 'Varchar(32)',
        'CaptionForegroundColor' => 'Color',
        'CaptionBackgroundColor' => 'Color'
    );
    
    private static $defaults = array(
        'ShowCaptions' => 1,
        'ImageWidth' => 640,
        'ImageHeight' => 480,
        'ImageResize' => 'fill',
        'CaptionForegroundColor' => 'ffffff',
        'CaptionBackgroundColor' => '139fda'
    );
    
    private static $extensions = array(
        'ListSourceExtension'
    );
    
    private static $required_themed_css = array(
        'tile-component'
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
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'TileComponentStyle',
                $this->i18n_singular_name(),
                array(
                    ColorField::create(
                        'CaptionForegroundColor',
                        _t('TileComponent.CAPTIONFOREGROUNDCOLOR', 'Caption foreground color')
                    ),
                    ColorField::create(
                        'CaptionBackgroundColor',
                        _t('TileComponent.CAPTIONBACKGROUNDCOLOR', 'Caption background color')
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'TileComponentOptions',
                $this->i18n_singular_name(),
                array(
                    FieldGroup::create(
                        _t('TileComponent.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('ImageWidth', '')->setAttribute(
                                'placeholder',
                                _t('TileComponent.WIDTH', 'Width')
                            ),
                            LiteralField::create('ImageBy', '<i class="fa fa-times by"></i>'),
                            TextField::create('ImageHeight', '')->setAttribute(
                                'placeholder',
                                _t('TileComponent.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ImageResize',
                        _t('TileComponent.IMAGERESIZE', 'Image resize'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('TileComponent.NONE', 'None')),
                    CheckboxField::create(
                        'ShowCaptions',
                        _t('TileComponent.SHOWCAPTIONS', 'Show captions')
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a string of class names for the list wrapper.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the list wrapper.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = array('items');
        
        return $classes;
    }
    
    /**
     * Renders the list of items for the template.
     *
     * @return string
     */
    public function RenderListItems()
    {
        $output = array();
        
        if ($items = $this->getListItems()) {
            
            $data = array('Component' => $this);
            
            foreach ($items as $key => $item) {
                
                $data['IsFirst'] = ($key == 0);
                $data['IsLast']  = ($key == (count($items) - 1));
                
                $output[] = $item->RenderListItem($data, 'TileComponent_ListItem');
                
            }
            
        }
        
        return implode("\n", $output);
    }
}

/**
 * An extension of the base component controller class for a tile component.
 */
class TileComponent_Controller extends BaseComponent_Controller
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
