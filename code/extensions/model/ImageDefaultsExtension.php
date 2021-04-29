<?php

/**
 * An extension of the data extension class which allows extended objects to set defaults for images.
 */
class ImageDefaultsExtension extends DataExtension
{
    private static $db = array(
        'ImageDefaultWidth' => 'Varchar(16)',
        'ImageDefaultHeight' => 'Varchar(16)',
        'ImageDefaultResize' => 'Varchar(32)',
        'ImageDefaultLinks' => 'Varchar(32)',
        'ImageDefaultAlignment' => 'Varchar(32)',
        'ThumbnailDefaultWidth' => 'Varchar(16)',
        'ThumbnailDefaultHeight' => 'Varchar(16)',
        'ThumbnailDefaultResize' => 'Varchar(32)',
        'ThumbnailDefaultLinksTo' => 'Varchar(16)',
        'ThumbnailDefaultAlignment' => 'Varchar(32)'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Create Options Tab:
        
        $fields->findOrMakeTab('Root.Options', _t('ImageDefaultsExtension.OPTIONS', 'Options'));
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'ImageDefaultsOptions',
                _t('ImageDefaultsExtension.IMAGEDEFAULTS', 'Image Defaults'),
                array(
                    DropdownField::create(
                        'ImageDefaultAlignment',
                        _t('ImageDefaultsExtension.IMAGEALIGNMENT', 'Image alignment'),
                        ImageTools::get_image_alignments()
                    )->setEmptyString(_t('ImageDefaultsExtension.DEFAULT', 'Default')),
                    FieldGroup::create(
                        _t('ImageDefaultsExtension.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('ImageDefaultWidth', '')->setAttribute(
                                'placeholder',
                                _t('ImageDefaultsExtension.WIDTH', 'Width')
                            ),
                            LiteralField::create('ImageDefaultBy', '<i class="fa fa-times by"></i>'),
                            TextField::create('ImageDefaultHeight', '')->setAttribute(
                                'placeholder',
                                _t('ImageDefaultsExtension.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ImageDefaultResize',
                        _t('ImageDefaultsExtension.IMAGERESIZEMETHOD', 'Image resize method'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('ImageDefaultsExtension.DEFAULT', 'Default')),
                    DropdownField::create(
                        'ImageDefaultLinks',
                        _t('ImageDefaultsExtension.IMAGELINKS', 'Image links'),
                        SilverWareMetaExtension::get_toggle_options()
                    )->setEmptyString(_t('ImageDefaultsExtension.DEFAULT', 'Default')),
                    DropdownField::create(
                        'ThumbnailDefaultAlignment',
                        _t('ImageDefaultsExtension.THUMBNAILALIGNMENT', 'Thumbnail alignment'),
                        ImageTools::get_thumbnail_alignments()
                    )->setEmptyString(_t('ImageDefaultsExtension.DEFAULT', 'Default')),
                    FieldGroup::create(
                        _t('ImageDefaultsExtension.THUMBNAILDIMENSIONS', 'Thumbnail dimensions'),
                        array(
                            TextField::create('ThumbnailDefaultWidth', '')->setAttribute(
                                'placeholder',
                                _t('ImageDefaultsExtension.WIDTH', 'Width')
                            ),
                            LiteralField::create('ThumbnailDefaultBy', '<i class="fa fa-times by"></i>'),
                            TextField::create('ThumbnailDefaultHeight', '')->setAttribute(
                                'placeholder',
                                _t('ImageDefaultsExtension.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ThumbnailDefaultResize',
                        _t('ImageDefaultsExtension.THUMBNAILRESIZEMETHOD', 'Thumbnail resize method'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('ImageDefaultsExtension.DEFAULT', 'Default')),
                    DropdownField::create(
                        'ThumbnailDefaultLinksTo',
                        _t('ImageDefaultsExtension.THUMBNAILLINKSTO', 'Thumbnail links to'),
                        ImageTools::get_link_to_options()
                    )->setEmptyString(_t('ImageDefaultsExtension.DEFAULT', 'Default'))
                )
            )
        );
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        $this->owner->ImageDefaultWidth  = SilverWareTools::integer_or_null($this->owner->ImageDefaultWidth);
        $this->owner->ImageDefaultHeight = SilverWareTools::integer_or_null($this->owner->ImageDefaultHeight);
        
        $this->owner->ThumbnailDefaultWidth  = SilverWareTools::integer_or_null($this->owner->ThumbnailDefaultWidth);
        $this->owner->ThumbnailDefaultHeight = SilverWareTools::integer_or_null($this->owner->ThumbnailDefaultHeight);
    }
    
    /**
     * Answers the default image width.
     *
     * @return integer|null
     */
    public function getDefaultImageWidth()
    {
        if ($width = $this->owner->ImageDefaultWidth) {
            return $width;
        }
        
        return $this->getAttributeFromParent('ImageDefaultWidth');
    }
    
    /**
     * Answers the default image height.
     *
     * @return integer|null
     */
    public function getDefaultImageHeight()
    {
        if ($height = $this->owner->ImageDefaultHeight) {
            return $height;
        }
        
        return $this->getAttributeFromParent('ImageDefaultHeight');
    }
    
    /**
     * Answers the default image resize method.
     *
     * @return string|null
     */
    public function getDefaultImageResize()
    {
        if ($resize = $this->owner->ImageDefaultResize) {
            return $resize;
        }
        
        return $this->getAttributeFromParent('ImageDefaultResize');
    }
    
    /**
     * Answers the default image alignment.
     *
     * @return string|null
     */
    public function getDefaultImageAlignment()
    {
        if ($alignment = $this->owner->ImageDefaultAlignment) {
            return $alignment;
        }
        
        return $this->getAttributeFromParent('ImageDefaultAlignment');
    }
    
    /**
     * Answers the default image links setting.
     *
     * @return string|null
     */
    public function getDefaultImageLinks()
    {
        if ($links = $this->owner->ImageDefaultLinks) {
            return $links;
        }
        
        return $this->getAttributeFromParent('ImageDefaultLinks');
    }
    
    /**
     * Answers the default thumbnail width.
     *
     * @return integer|null
     */
    public function getDefaultThumbnailWidth()
    {
        if ($width = $this->owner->ThumbnailDefaultWidth) {
            return $width;
        }
        
        return $this->getAttributeFromParent('ThumbnailDefaultWidth');
    }
    
    /**
     * Answers the default thumbnail height.
     *
     * @return integer|null
     */
    public function getDefaultThumbnailHeight()
    {
        if ($height = $this->owner->ThumbnailDefaultHeight) {
            return $height;
        }
        
        return $this->getAttributeFromParent('ThumbnailDefaultHeight');
    }
    
    /**
     * Answers the default thumbnail resize method.
     *
     * @return string|null
     */
    public function getDefaultThumbnailResize()
    {
        if ($resize = $this->owner->ThumbnailDefaultResize) {
            return $resize;
        }
        
        return $this->getAttributeFromParent('ThumbnailDefaultResize');
    }
    
    /**
     * Answers the default thumbnail links to option.
     *
     * @return string|null
     */
    public function getDefaultThumbnailLinksTo()
    {
        if ($links_to = $this->owner->ThumbnailDefaultLinksTo) {
            return $links_to;
        }
        
        return $this->getAttributeFromParent('ThumbnailDefaultLinksTo');
    }
    
    /**
     * Answers the default thumbnail alignment.
     *
     * @return string|null
     */
    public function getDefaultThumbnailAlignment()
    {
        if ($alignment = $this->owner->ThumbnailDefaultAlignment) {
            return $alignment;
        }
        
        return $this->getAttributeFromParent('ThumbnailDefaultAlignment');
    }
    
    /**
     * Answers the value of the specified attribute from the parent.
     *
     * @param string $attribute
     * @return mixed
     */
    private function getAttributeFromParent($attribute)
    {
        $value = null;
        
        if ($this->owner->ParentID) {
            
            $Parent = $this->owner->Parent();
            
            while (!$value && ($Parent instanceof Page)) {
                
                $value = $Parent->{$attribute};
                
                $Parent = $Parent->Parent();
                
            }
            
        }
        
        return $value;
    }
}
