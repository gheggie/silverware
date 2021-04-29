<?php

/**
 * An extension of the base component class for a masonry component.
 */
class MasonryComponent extends BaseComponent
{
    private static $singular_name = "Masonry Component";
    private static $plural_name   = "Masonry Components";
    
    private static $description = "A component to show a masonry layout of images";
    
    private static $icon = "silverware/images/icons/components/MasonryComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'Gutter' => 'Int',
        'ImageWidth' => 'Varchar(16)',
        'ImageHeight' => 'Varchar(16)',
        'ImageResize' => 'Varchar(32)',
        'WidthMode' => "Enum('Device, Component', 'Device')",
        'ColumnWidthWide' => 'Decimal(9,3,23)',
        'ColumnWidthNarrow' => 'Decimal(9,3,46)',
        'ImageLinksTo' => "Enum('File, Item', 'File')"
    );
    
    private static $defaults = array(
        'Gutter' => 15,
        'ImageItems' => 1,
        'WidthMode' => 'Device',
        'ColumnWidthWide' => 23,
        'ColumnWidthNarrow' => 46,
        'ImageLinksTo' => 'File'
    );
    
    private static $extensions = array(
        'ListSourceExtension'
    );
    
    private static $required_themed_css = array(
        'masonry-component'
    );
    
    private static $required_js = array(
        'silverware/thirdparty/masonry/masonry.min.js',
        'silverware/thirdparty/imagesloaded/imagesloaded.min.js'
    );
    
    private static $required_js_templates = array(
        'silverware/javascript/masonry/masonry.init.js'
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
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'MasonryComponentStyle',
                $this->i18n_singular_name(),
                array(
                    FieldGroup::create(
                        _t('MasonryComponent.COLUMNWIDTHWIDE', 'Column width (wide)'),
                        array(
                            NumericField::create(
                                'ColumnWidthWide',
                                ''
                            ),
                            LiteralField::create('WidePercent', '<i class="fa fa-percent"></i>'),
                        )
                    ),
                    FieldGroup::create(
                        _t('MasonryComponent.COLUMNWIDTHNARROW', 'Column width (narrow)'),
                        array(
                            NumericField::create(
                                'ColumnWidthNarrow',
                                ''
                            ),
                            LiteralField::create('NarrowPercent', '<i class="fa fa-percent"></i>'),
                        )
                    ),
                    NumericField::create(
                        'Gutter',
                        _t('MasonryComponent.GUTTERINPIXELS', 'Gutter (in pixels)')
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'MasonryComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'WidthMode',
                        _t('MasonryComponent.DETECTWIDTHOF', 'Detect width of'),
                        $this->dbObject('WidthMode')->enumValues()
                    ),
                    DropdownField::create(
                        'ImageLinksTo',
                        _t('MasonryComponent.IMAGELINKSTO', 'Image links to'),
                        $this->dbObject('ImageLinksTo')->enumValues()
                    ),
                    FieldGroup::create(
                        _t('MasonryComponent.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('ImageWidth', '')->setAttribute(
                                'placeholder',
                                _t('MasonryComponent.WIDTH', 'Width')
                            ),
                            LiteralField::create('ImageBy', '<i class="fa fa-times"></i>'),
                            TextField::create('ImageHeight', '')->setAttribute(
                                'placeholder',
                                _t('MasonryComponent.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ImageResize',
                        _t('MasonryComponent.IMAGERESIZE', 'Image resize'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('MasonryComponent.NONE', 'None')),
                )
            )
        );
        
        // Modify Options Fields:
        
        $fields->removeByName('PaginateItems');
        $fields->dataFieldByName('SortItemsBy')->setRightTitle('');
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a unique ID for the wrapper element.
     *
     * @return string
     */
    public function getWrapperID()
    {
        return $this->getHTMLID() . "_Wrapper";
    }
    
    /**
     * Answers a string of class names for the image links.
     *
     * @return string
     */
    public function getImageLinkClass()
    {
        return implode(' ', $this->getImageLinkClassNames());
    }
    
    /**
     * Answers an array of class names for the image links.
     *
     * @return array
     */
    public function getImageLinkClassNames()
    {
        $classes = array('image-link');
        
        if (!$this->ImageLinksTo || $this->ImageLinksTo == 'File') {
            $classes[] = "popup";
        }
        
        return $classes;
    }
    
    /**
     * Answers an array of variables required by the initialisation script.
     *
     * @return array
     */
    public function getJSVars()
    {
        $vars = parent::getJSVars();
        
        $vars['WrapperID'] = $this->getWrapperID();
        
        $vars['Gutter'] = $this->Gutter;
        
        return $vars;
    }
    
    /**
     * Answers true if the component should detect the device width.
     *
     * @return boolean
     */
    public function DetectDeviceWidth()
    {
        return ($this->WidthMode == 'Device');
    }
}

/**
 * An extension of the base component controller class for a masonry component.
 */
class MasonryComponent_Controller extends BaseComponent_Controller
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
