<?php

/**
 * An extension of the data object class for a SilverWare slide.
 */
class SilverWareSlide extends DataObject
{
    private static $singular_name = "Slide";
    private static $plural_name   = "Slides";
    
    private static $default_sort = "Sort";
    
    private static $asset_folder = "Slides";
    
    private static $db = array(
        'Sort' => 'Int',
        'Title' => 'Varchar(255)',
        'Caption' => 'Varchar(255)',
        'Disabled' => 'Boolean',
        'HideTitle' => 'Boolean',
        'HideCaption' => 'Boolean',
        'LinkURL' => 'Varchar(2048)',
        'LinkDisabled' => 'Boolean',
        'OpenLinkInNewTab' => 'Boolean'
    );
    
    private static $has_one = array(
        'Image' => 'Image',
        'LinkPage' => 'SiteTree'
    );
    
    private static $defaults = array(
        'Disabled' => 0,
        'HideTitle' => 1,
        'HideCaption' => 0,
        'LinkDisabled' => 0,
        'OpenLinkInNewTab' => 0
    );
    
    private static $summary_fields = array(
        'CMSThumbnail' => 'Image',
        'Title' => 'Title',
        'CaptionLimited' => 'Caption',
        'Disabled.Nice' => 'Disabled'
    );
    
    private static $cms_thumbnail_width = 50;
    private static $cms_thumbnail_height = 50;
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Create Field Tab Set:
        
        $fields = FieldList::create(TabSet::create('Root'));
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create(
                    'Title',
                    _t('SilverWareSlide.TITLE', 'Title')
                ),
                TextField::create(
                    'Caption',
                    _t('SilverWareSlide.CAPTION', 'Caption')
                ),
                TreeDropdownField::create(
                    'LinkPageID',
                    _t('SilverWareSlide.LINKPAGE', 'Link Page'),
                    'SiteTree'
                ),
                TextField::create(
                    'LinkURL',
                    _t('SilverWareSlide.LINKURL', 'Link URL')
                ),
                CheckboxField::create(
                    'HideTitle',
                    _t('SilverWareSlide.HIDETITLE', 'Hide title')
                ),
                CheckboxField::create(
                    'HideCaption',
                    _t('SilverWareSlide.HIDECAPTION', 'Hide caption')
                ),
                CheckboxField::create(
                    'OpenLinkInNewTab',
                    _t('SilverWareSlide.OPENLINKINNEWTAB', 'Open link in new tab')
                ),
                CheckboxField::create(
                    'LinkDisabled',
                    _t('SilverWareSlide.LINKDISABLED', 'Link disabled')
                ),
                CheckboxField::create(
                    'Disabled',
                    _t('SilverWareSlide.DISABLED', 'Disabled')
                )
            )
        );
        
        // Create Image Tab:
        
        $fields->findOrMakeTab('Root.Image', _t('SilverWareSlide.IMAGE', 'Image'));
        
        // Create Image Field:
        
        $fields->addFieldToTab(
            'Root.Image',
            UploadField::create(
                'Image',
                _t('SilverWareSlide.IMAGE', 'Image')
            )->setAllowedFileCategories('image')->setFolderName($this->config()->asset_folder)
        );
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
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
                'Title'
            )
        );
    }
    
    /**
     * Renders the receiver for the HTML template.
     *
     * @return HTMLText
     */
    public function forTemplate()
    {
        if (SSViewer::hasTemplate($this->ClassName)) {
            $this->renderWith($this->ClassName);
        }
        
        return $this->renderWith('SilverWareSlide');
    }
    
    /**
     * Answers a thumbnail of the image for the CMS.
     *
     * @return Image
     */
    public function CMSThumbnail()
    {
        return $this->Image()->FillPriority(
            $this->config()->cms_thumbnail_width,
            $this->config()->cms_thumbnail_height
        );
    }
    
    /**
     * Answers a string of class names for the wrapper element.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the wrapper element.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        return array('slide', strtolower($this->ClassName));
    }
    
    /**
     * Answers the appropriate link for the receiver.
     *
     * @return string
     */
    public function getLink()
    {
        if ($this->LinkURL) {
            return $this->dbObject('LinkURL')->URL();
        }
        
        if ($this->LinkPageID) {
            return $this->LinkPage()->Link();
        }
    }
    
    /**
     * Answers true if the receiver has a link.
     *
     * @return boolean
     */
    public function HasLink()
    {
        return (boolean) $this->getLink();
    }
    
    /**
     * Answers true if the receiver has an image.
     *
     * @return boolean
     */
    public function HasImage()
    {
        return $this->Image()->exists();
    }
    
    /**
     * Answers true if the receiver has a title.
     *
     * @return boolean
     */
    public function HasTitle()
    {
        return (boolean) $this->Title;
    }
    
    /**
     * Answers true if the receiver has a caption.
     *
     * @return boolean
     */
    public function HasCaption()
    {
        return (boolean) $this->Caption;
    }
    
    /**
     * Answers true if the receiver has a parent object.
     *
     * @return boolean
     */
    public function HasParent()
    {
        return (boolean) $this->getParent();
    }
    
    /**
     * Answers the parent object for the slide.
     *
     * @return SiteTree|null
     */
    public function getParent()
    {
        return null;
    }
    
    /**
     * Answers true if the content area is to be shown.
     *
     * @return boolean
     */
    public function ContentShown()
    {
        return ($this->TitleShown() || $this->CaptionShown());
    }
    
    /**
     * Answers true if the link is to be shown.
     *
     * @return boolean
     */
    public function LinkShown()
    {
        return ($this->HasLink() && !$this->LinkDisabled);
    }
    
    /**
     * Answers true if the image is to be shown.
     *
     * @return boolean
     */
    public function ImageShown()
    {
        return $this->HasImage();
    }
    
    /**
     * Answers true if the title is to be shown.
     *
     * @return boolean
     */
    public function TitleShown()
    {
        return ($this->HasTitle() && !$this->HideTitle);
    }
    
    /**
     * Answers true if the caption is to be shown.
     *
     * @return boolean
     */
    public function CaptionShown()
    {
        return ($this->HasCaption() && !$this->HideCaption);
    }
    
    /**
     * Answers the caption of the receiver limited to the specified number of words.
     *
     * @param integer $words
     * @return string
     */
    public function CaptionLimited($words = 15)
    {
        return $this->dbObject('Caption')->LimitWordCount($words);
    }
    
    /**
     * Answers the resized image for the template.
     *
     * @return Image
     */
    public function ImageResized()
    {
        if ($this->HasImage() && $this->HasParent()) {
            
            if ($Parent = $this->getParent()) {
                
                return ImageTools::resize_image(
                    $this->Image(),
                    $Parent->ImageWidth,
                    $Parent->ImageHeight,
                    $Parent->ImageResize
                );
                
            }
            
            return $this->Image();
            
        }
    }
}
