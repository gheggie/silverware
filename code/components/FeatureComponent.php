<?php

/**
 * An extension of the base component class for a feature component.
 */
class FeatureComponent extends BaseComponent
{
    private static $singular_name = "Feature Component";
    private static $plural_name   = "Feature Components";
    
    private static $description = "A component used to feature a particular page";
    
    private static $icon = "silverware/images/icons/components/FeatureComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $asset_folder = "Features";
    
    private static $db = array(
        'Summary' => 'Text',
        'ButtonLabel' => 'Varchar(128)',
        'ImageWidth' => 'Varchar(16)',
        'ImageHeight' => 'Varchar(16)',
        'ImageResize' => 'Varchar(32)',
        'HeadingTag' => "Enum('h1, h2, h3, h4, h5, h6', 'h4')",
        'ShowPageTitle' => 'Boolean',
        'ShowButton' => 'Boolean',
        'ShowImage' => 'Boolean',
        'LinkTitle' => 'Boolean',
    );
    
    private static $has_one = array(
        'Image' => 'Image',
        'FeaturedPage' => 'SiteTree'
    );
    
    private static $defaults = array(
        'ShowImage' => 1,
        'LinkTitle' => 1,
        'ShowButton' => 1,
        'ShowPageTitle' => 0,
        'HeadingTag' => 'h4'
    );
    
    private static $required_themed_css = array(
        'feature-component'
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
                TreeDropdownField::create(
                    'FeaturedPageID',
                    _t('FeatureComponent.FEATUREDPAGE', 'Featured Page'),
                    'SiteTree'
                ),
                ToggleCompositeField::create(
                    'SummaryToggle',
                    _t('FeatureComponent.SUMMARY', 'Summary'),
                    array(
                        TextareaField::create(
                            'Summary',
                            _t('FeatureComponent.SUMMARY', 'Summary')
                        )->setRightTitle(
                            _t(
                                'FeatureComponent.SUMMARYRIGHTTITLE',
                                'Overrides the summary of the featured page (if available).'
                            )
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'ImageToggle',
                    _t('FeatureComponent.IMAGE', 'Image'),
                    array(
                        UploadField::create(
                            'Image',
                            _t('FeatureComponent.IMAGE', 'Image')
                        )->setRightTitle(
                            _t(
                                'FeatureComponent.IMAGERIGHTTITLE',
                                'Overrides the image of the featured page (if available).'
                            )
                        )->setAllowedFileCategories('image')->setFolderName($this->config()->asset_folder)
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'FeatureComponentOptions',
                $this->i18n_singular_name(),
                array(
                    TextField::create(
                        'ButtonLabel',
                        _t('FeatureComponent.BUTTONLABEL', 'Button label')
                    ),
                    FieldGroup::create(
                        _t('FeatureComponent.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('ImageWidth', '')->setAttribute(
                                'placeholder',
                                _t('FeatureComponent.WIDTH', 'Width')
                            ),
                            LiteralField::create('ImageBy', '<i class="fa fa-times by"></i>'),
                            TextField::create('ImageHeight', '')->setAttribute(
                                'placeholder',
                                _t('FeatureComponent.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ImageResize',
                        _t('FeatureComponent.IMAGERESIZE', 'Image resize'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('FeatureComponent.NONE', 'None')),
                    DropdownField::create(
                        'HeadingTag',
                        _t('FeatureComponent.HEADINGTAG', 'Heading tag'),
                        $this->dbObject('HeadingTag')->enumValues()
                    ),
                    CheckboxField::create(
                        'LinkTitle',
                        _t('FeatureComponent.LINKTITLE', 'Link title')
                    ),
                    CheckboxField::create(
                        'ShowPageTitle',
                        _t('FeatureComponent.SHOWTITLE', 'Show title')
                    ),
                    CheckboxField::create(
                        'ShowImage',
                        _t('FeatureComponent.SHOWIMAGE', 'Show image')
                    ),
                    CheckboxField::create(
                        'ShowButton',
                        _t('FeatureComponent.SHOWBUTTON', 'Show button')
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
                'FeaturedPageID'
            )
        );
    }
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->ButtonLabel = _t('FeatureComponent.DEFAULTBUTTONLABEL', 'Read More');
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
     * Answers true if the receiver has an image to display.
     *
     * @return boolean
     */
    public function HasImage()
    {
        return ($this->ImageExists() || ($this->FeaturedPageID && $this->FeaturedPage()->HasMetaImage()));
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
            
            if ($this->ImageExists()) {
                
                return ImageTools::resize_image(
                    $this->Image(),
                    $this->ImageWidth,
                    $this->ImageHeight,
                    $this->ImageResize
                );
                
            } else {
                
                return $this->FeaturedPage()->MetaImageResized(
                    $this->ImageWidth,
                    $this->ImageHeight,
                    $this->ImageResize
                );
                
            }
            
        }
    }
}

/**
 * An extension of the base component controller class for a feature component.
 */
class FeatureComponent_Controller extends BaseComponent_Controller
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
