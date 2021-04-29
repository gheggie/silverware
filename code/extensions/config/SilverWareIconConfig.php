<?php

/**
 * An extension of the SilverWare config extension class to add icon settings to site config.
 */
class SilverWareIconConfig extends SilverWareConfigExtension
{
    private static $has_one = array(
        'SilverWareSiteIconSmall' => 'Image',
        'SilverWareSiteIconLarge' => 'Image',
        'SilverWareSiteIconTouch' => 'Image'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects (from parent):
        
        parent::updateCMSFields($fields);
        
        // Create Icons Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.Icons', _t('SilverWareIconConfig.ICONS', 'Icons'));
        
        // Add Fields to Icons Tab:
        
        $fields->addFieldsToTab(
            'Root.SilverWare.Icons',
            array(
                $small_icon = UploadField::create(
                    'SilverWareSiteIconSmall',
                    _t('SilverWareIconConfig.SMALLICON', 'Small icon')
                ),
                $large_icon = UploadField::create(
                    'SilverWareSiteIconLarge',
                    _t('SilverWareIconConfig.LARGEICON', 'Large icon')
                ),
                $touch_icon = UploadField::create(
                    'SilverWareSiteIconTouch',
                    _t('SilverWareIconConfig.TOUCHICON', 'Touch icon')
                )
            )
        );
        
        // Obtain Asset Folder:
        
        $asset_folder = Config::inst()->get('SilverWareIconConfig', 'asset_folder');
        
        // Define Small Icon Field:
        
        $small_icon->setAllowedFileCategories('image');
        $small_icon->setFolderName($asset_folder);
        $small_icon->setRightTitle(
            _t(
                'SilverWareIconConfig.SMALLICONRIGHTTITLE',
                'Used for the bookmark icon (16&times;16 pixels recommended).'
            )
        );
        
        // Define Large Icon Field:
        
        $large_icon->setAllowedFileCategories('image');
        $large_icon->setFolderName($asset_folder);
        $large_icon->setRightTitle(
            _t(
                'SilverWareIconConfig.LARGEICONRIGHTTITLE',
                'Used for social media sharing (at least 500&times;500 pixels recommended).'
            )
        );
        
        // Define Touch Icon Field:
        
        $touch_icon->setAllowedFileCategories('image');
        $touch_icon->getValidator()->setAllowedExtensions(array('png'));
        $touch_icon->setFolderName($asset_folder);
        $touch_icon->setRightTitle(
            _t(
                'SilverWareIconConfig.TOUCHICONRIGHTTITLE',
                'Used for touch icons (at least 500&times;500 pixels recommended, PNG format required).'
            )
        );
    }
    
    /**
     * Answers the mime type of the small site icon image.
     *
     * @return string
     */
    public function SiteIconSmallType()
    {
        if ($this->owner->SiteIconSmallExists()) {
            return HTTP::get_mime_type($this->owner->SilverWareSiteIconSmall()->Filename);
        }
    }
    
    /**
     * Answers true if the small site icon image exists.
     *
     * @return boolean
     */
    public function SiteIconSmallExists()
    {
        return $this->owner->SilverWareSiteIconSmall()->exists();
    }
    
    /**
     * Answers a resized copy of the small site icon image.
     *
     * @param integer $width
     * @param integer $height
     * @return Image
     */
    public function SiteIconSmallResized($width = 16, $height = 16)
    {
        if ($this->owner->SiteIconSmallExists()) {
            return $this->owner->SilverWareSiteIconSmall()->ResizedImage($width, $height);
        }
    }
    
    /**
     * Answers the mime type of the large site icon image.
     *
     * @return string
     */
    public function SiteIconLargeType()
    {
        if ($this->owner->SiteIconLargeExists()) {
            return HTTP::get_mime_type($this->owner->SilverWareSiteIconLarge()->Filename);
        }
    }
    
    /**
     * Answers true if the large site icon image exists.
     *
     * @return boolean
     */
    public function SiteIconLargeExists()
    {
        return $this->owner->SilverWareSiteIconLarge()->exists();
    }
    
    /**
     * Answers a resized copy of the large site icon image.
     *
     * @param integer $width
     * @param integer $height
     * @return Image
     */
    public function SiteIconLargeResized($width = 500, $height = 500)
    {
        if ($this->owner->SiteIconLargeExists()) {
            return $this->owner->SilverWareSiteIconLarge()->Fit($width, $height);
        }
    }
    
    /**
     * Answers the mime type of the touch site icon image.
     *
     * @return string
     */
    public function SiteIconTouchType()
    {
        if ($this->owner->SiteIconTouchExists()) {
            return HTTP::get_mime_type($this->owner->SilverWareSiteIconTouch()->Filename);
        }
    }
    
    /**
     * Answers true if the touch site icon image exists.
     *
     * @return boolean
     */
    public function SiteIconTouchExists()
    {
        return $this->owner->SilverWareSiteIconTouch()->exists();
    }
    
    /**
     * Answers a resized copy of the touch site icon image.
     *
     * @param integer $width
     * @param integer $height
     * @return Image
     */
    public function SiteIconTouchResized($width = 500, $height = 500)
    {
        if ($this->owner->SiteIconTouchExists()) {
            return $this->owner->SilverWareSiteIconTouch()->ResizedImage($width, $height);
        }
    }
    
    /**
     * Answers the mime type of the favicon image.
     *
     * @return string
     */
    public function FavIconType()
    {
        if ($this->owner->SiteIconSmallExists()) {
            return $this->owner->SiteIconSmallType();
        }
        
        return "image/x-icon";
    }
    
    /**
     * Answers the URL of the favicon image.
     *
     * @return string
     */
    public function FavIconURL()
    {
        if ($this->owner->SiteIconSmallExists()) {
            return $this->owner->SiteIconSmallResized(16, 16)->URL;
        }
        
        return Director::absoluteBaseURL() . "favicon.ico";
    }
}
