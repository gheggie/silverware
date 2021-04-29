<?php

/**
 * An extension of the base component class for an image component.
 */
class ImageComponent extends BaseComponent
{
    private static $singular_name = "Image Component";
    private static $plural_name   = "Image Components";
    
    private static $description = "A component to show an image";
    
    private static $icon = "silverware/images/icons/components/ImageComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $asset_folder = "Images";
    
    private static $db = array(
        'ImageWidth' => 'Varchar(16)',
        'ImageHeight' => 'Varchar(16)',
        'ImageResize' => 'Varchar(32)',
        'ImageCaption' => 'Text',
        'LinkImage' => 'Boolean',
        'HideCaption' => 'Boolean'
    );
    
    private static $has_one = array(
        'Image' => 'Image'
    );
    
    private static $defaults = array(
        'LinkImage' => 0,
        'HideCaption' => 0
    );
    
    private static $required_themed_css = array(
        'image-component'
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
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                UploadField::create(
                    'Image',
                    _t('ImageComponent.IMAGE', 'Image')
                )->setAllowedFileCategories('image')->setFolderName($this->config()->asset_folder),
                ToggleCompositeField::create(
                    'ImageCaptionToggle',
                    _t('ImageComponent.CAPTION', 'Caption'),
                    array(
                        TextareaField::create(
                            'ImageCaption',
                            _t('ImageComponent.CAPTION', 'Caption')
                        )
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'ImageComponentOptions',
                $this->i18n_singular_name(),
                array(
                    FieldGroup::create(
                        _t('ImageComponent.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('ImageWidth', '')->setAttribute(
                                'placeholder',
                                _t('ImageComponent.WIDTH', 'Width')
                            ),
                            LiteralField::create('ImageBy', '<i class="fa fa-times by"></i>'),
                            TextField::create('ImageHeight', '')->setAttribute(
                                'placeholder',
                                _t('ImageComponent.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ImageResize',
                        _t('ImageComponent.IMAGERESIZE', 'Image resize'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('ImageComponent.NONE', 'None')),
                    CheckboxField::create(
                        'LinkImage',
                        _t('ImageComponent.LINKIMAGE', 'Link image')
                    ),
                    CheckboxField::create(
                        'HideCaption',
                        _t('ImageComponent.HIDECAPTION', 'Hide caption')
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create(
            array(
                'Image'
            )
        );
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Fix Image Dimensions:
        
        $this->ImageWidth  = SilverWareTools::integer_or_null($this->ImageWidth);
        $this->ImageHeight = SilverWareTools::integer_or_null($this->ImageHeight);
    }
    
    /**
     * Answers a string of class names for the image link.
     *
     * @return string
     */
    public function getImageLinkClass()
    {
        return implode(' ', $this->getImageLinkClassNames());
    }
    
    /**
     * Answers an array of class names for the image link.
     *
     * @return array
     */
    public function getImageLinkClassNames()
    {
        return array('image-link', 'popup');
    }
    
    /**
     * Answers true if the receiver has an image to display.
     *
     * @return boolean
     */
    public function HasImage()
    {
        return $this->ImageExists();
    }
    
    /**
     * Answers true if an image exists for the receiver.
     *
     * @return boolean
     */
    public function ImageExists()
    {
        return $this->Image()->exists();
    }
    
    /**
     * Answers the resized image for the template.
     *
     * @return Image
     */
    public function ImageResized()
    {
        if ($this->HasImage()) {
            
            return ImageTools::resize_image(
                $this->Image(),
                $this->ImageWidth,
                $this->ImageHeight,
                $this->ImageResize
            );
            
        }
    }
    
    /**
     * Answers true if the image caption is to be shown.
     *
     * @return boolean
     */
    public function ShowCaption()
    {
        return ($this->ImageCaption && !$this->HideCaption);
    }
}

/**
 * An extension of the base component controller class for an image component.
 */
class ImageComponent_Controller extends BaseComponent_Controller
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
