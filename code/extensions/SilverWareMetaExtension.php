<?php

/**
 * An extension of the data extension class which allows extended objects to use SilverWare meta fields.
 */
class SilverWareMetaExtension extends DataExtension
{
    private static $db = array(
        'MetaSummary' => 'Text',
        'MetaImageLinks' => 'Varchar(16)',
        'MetaImageWidth' => 'Varchar(16)',
        'MetaImageHeight' => 'Varchar(16)',
        'MetaImageHidden' => 'Boolean',
        'MetaImageResize' => 'Varchar(32)',
        'MetaImageCaption' => 'Varchar(255)',
        'MetaImageAlignment' => 'Varchar(32)'
    );
    
    private static $has_one = array(
        'MetaImageFile' => 'Image'
    );
    
    /**
     * Answers an array of toggle options for a dropdown field.
     *
     * @return array
     */
    public static function get_toggle_options()
    {
        return array(
            'Enabled' => _t('SilverWareMetaExtension.ENABLED', 'Enabled'),
            'Disabled' => _t('SilverWareMetaExtension.DISABLED', 'Disabled')
        );
    }
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Create Summary Field:
        
        $fields->addFieldToTab(
            'Root.Main',
            ToggleCompositeField::create(
                'MetaSummaryToggle',
                _t('SilverWareMetaExtension.SUMMARY', 'Summary'),
                array(
                    TextareaField::create(
                        'MetaSummary',
                        _t('SilverWareMetaExtension.SUMMARY', 'Summary')
                    )
                )
            ),
            'Metadata'
        );
        
        // Obtain Asset Folder:
        
        $asset_folder = Config::inst()->get('SilverWareMetaExtension', 'asset_folder');
        
        // Create Image Fields:
        
        $fields->addFieldToTab(
            'Root.Main',
            ToggleCompositeField::create(
                'MetaImageToggle',
                _t('SilverWareMetaExtension.IMAGE', 'Image'),
                array(
                    UploadField::create(
                        'MetaImageFile',
                        _t('SilverWareMetaExtension.IMAGE', 'Image')
                    )->setAllowedFileCategories('image')->setFolderName($asset_folder),
                    TextField::create(
                        'MetaImageCaption',
                        _t('SilverWareMetaExtension.IMAGECAPTION', 'Image caption')
                    ),
                    DropdownField::create(
                        'MetaImageAlignment',
                        _t('SilverWareMetaExtension.IMAGEALIGNMENT', 'Image alignment'),
                        ImageTools::get_image_alignments()
                    )->setEmptyString(_t('SilverWareMetaExtension.DEFAULT', 'Default')),
                    FieldGroup::create(
                        _t('SilverWareMetaExtension.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('MetaImageWidth', '')->setAttribute(
                                'placeholder',
                                _t('SilverWareMetaExtension.WIDTH', 'Width')
                            ),
                            LiteralField::create('MetaImageBy', '<i class="fa fa-times by"></i>'),
                            TextField::create('MetaImageHeight', '')->setAttribute(
                                'placeholder',
                                _t('SilverWareMetaExtension.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'MetaImageResize',
                        _t('SilverWareMetaExtension.IMAGERESIZEMETHOD', 'Image resize method'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('SilverWareMetaExtension.DEFAULT', 'Default')),
                    DropdownField::create(
                        'MetaImageLinks',
                        _t('SilverWareMetaExtension.IMAGELINK', 'Image link'),
                        self::get_toggle_options()
                    )->setEmptyString(_t('SilverWareMetaExtension.DEFAULT', 'Default')),
                    CheckboxField::create(
                        'MetaImageHidden',
                        _t('SilverWareMetaExtension.HIDEIMAGE', 'Hide image')
                    )
                )
            ),
            'Metadata'
        );
    }
    
    /**
     * Event method called before the extended object is written to the database.
     */
    public function onBeforeWrite()
    {
        $this->owner->MetaImageWidth  = SilverWareTools::integer_or_null($this->owner->MetaImageWidth);
        $this->owner->MetaImageHeight = SilverWareTools::integer_or_null($this->owner->MetaImageHeight);
    }
    
    /**
     * Provides a shortcut to set the folder name of the meta image file field.
     *
     * @param FieldList $fields
     * @param string $folder
     */
    public function setMetaImageFolder(FieldList $fields, $folder)
    {
        if ($field = $fields->dataFieldByName('MetaImageFile')) {
            $field->setFolderName($folder);
        }
    }
    
    /**
     * Answers true if an meta image file exists for the extended object.
     *
     * @return boolean
     */
    public function MetaImageFileExists()
    {
        if ($Image = $this->owner->MetaImageFile()) {
            return $Image->exists();
        }
        
        return false;
    }
}
