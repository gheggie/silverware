<?php

/**
 * An extension of the data extension class to add meta data features to the extended object.
 */
class MetaDataExtension extends DataExtension
{
    /**
     * Answers the link for the extended object.
     *
     * @return string
     */
    public function MetaLink()
    {
        if ($this->owner->hasMethod('Link')) {
            return $this->owner->Link();
        }
    }
    
    /**
     * Answers true if the extended object has a link.
     *
     * @return boolean
     */
    public function HasMetaLink()
    {
        return (boolean) $this->owner->MetaLink();
    }
    
    /**
     * Answers the absolute link for the extended object.
     *
     * @return string
     */
    public function MetaAbsoluteLink()
    {
        if ($this->owner->hasMethod('AbsoluteLink')) {
            return $this->owner->AbsoluteLink();
        }
    }
    
    /**
     * Answers the title for the extended object.
     *
     * @return string
     */
    public function MetaTitle()
    {
        return $this->owner->Title;
    }
    
    /**
     * Answers the appropriate link for the title.
     *
     * @param string $type Type of link to return.
     * @return string
     */
    public function MetaTitleLink($type = null)
    {
        switch (strtolower($type)) {
            
            case 'file':
                return ($image = $this->owner->MetaImage()) ? $image->getAbsoluteURL() : null;
            
            default:
                return $this->owner->MetaLink();
            
        }
    }
    
    /**
     * Answers true if the extended object has a meta title link of the specified type.
     *
     * @param string $type Type of link to return.
     * @return boolean
     */
    public function HasMetaTitleLink($type = null)
    {
        return (boolean) $this->owner->MetaTitleLink($type);
    }
    
    /**
     * Answers true if the extended object has a date.
     *
     * @return boolean
     */
    public function HasMetaDate()
    {
        return (boolean) $this->owner->MetaDate();
    }
    
    /**
     * Answers an arbitrary date for the extended object (defaults to created date).
     *
     * @return SS_Datetime
     */
    public function MetaDate()
    {
        return $this->owner->MetaCreated();
    }
    
    /**
     * Answers the created date/time for the extended object.
     *
     * @return SS_Datetime
     */
    public function MetaCreated()
    {
        return $this->owner->dbObject('Created');
    }
    
    /**
     * Answers the modified date/time for the extended object.
     *
     * @return SS_Datetime
     */
    public function MetaModified()
    {
        return $this->owner->dbObject('LastEdited');
    }
    
    /**
     * Answers the summary for the extended object.
     *
     * @todo Remove encoding hack? HTMLText->Summary() sometimes returns UTF-8 and sometimes ASCII :(
     * @return string
     */
    public function MetaSummary()
    {
        $Summary = null;
        
        if ($this->owner->hasField('MetaSummary')) {
            $Summary = $this->owner->getField('MetaSummary');
        }
        
        if (!$Summary && $this->owner->hasField('Summary')) {
            $Summary = $this->owner->getField('Summary');
        }
        
        if (!$Summary && $this->owner->hasField('Content')) {
            $Summary = $this->owner->dbObject('Content')->Summary();
        }
        
        if ($Summary) {
            return mb_convert_encoding($Summary, 'UTF-8');
        }
    }
    
    /**
     * Answers true if the extended object has a summary.
     *
     * @return boolean
     */
    public function HasMetaSummary()
    {
        return (boolean) $this->owner->MetaSummary();
    }
    
    /**
     * Answers a string of class names for the extended object.
     *
     * @return string
     */
    public function MetaClass()
    {
        return implode(' ', $this->owner->MetaClassNames());
    }
    
    /**
     * Answers an array of class names for the extended object.
     *
     * @return array
     */
    public function MetaClassNames()
    {
        $classes = array();
        
        $classes[] = $this->owner->HasMetaImage() ? 'has-image' : 'no-image';
        
        return $classes;
    }
    
    /**
     * Answers a string of class names for the meta image.
     *
     * @return string
     */
    public function MetaImageClass()
    {
        return implode(' ', $this->owner->MetaImageClassNames());
    }
    
    /**
     * Answers an array of class names for the meta image.
     *
     * @return array
     */
    public function MetaImageClassNames()
    {
        $classes = array();
        
        if ($alignment = $this->owner->getDefaultMetaImageAlignment()) {
            
            $classes[] = $alignment;
            
        }
        
        return $classes;
    }
    
    /**
     * Answers a string of class names for the meta image link.
     *
     * @return string
     */
    public function MetaImageLinkClass()
    {
        return implode(' ', $this->owner->MetaImageLinkClassNames());
    }
    
    /**
     * Answers an array of class names for the meta image link.
     *
     * @return array
     */
    public function MetaImageLinkClassNames()
    {
        return array('image-link', 'popup');
    }
    
    /**
     * Answers a string of class names for the meta image caption.
     *
     * @return string
     */
    public function MetaImageCaptionClass()
    {
        return implode(' ', $this->owner->MetaImageCaptionClassNames());
    }
    
    /**
     * Answers an array of class names for the meta image caption.
     *
     * @return array
     */
    public function MetaImageCaptionClassNames()
    {
        $classes = array('caption');
        
        if ($alignment = $this->owner->getDefaultMetaImageAlignment()) {
            
            $classes[] = $alignment;
            
        }
        
        return $classes;
    }
    
    /**
     * Answers the width of the meta image wrapper.
     *
     * @return integer
     */
    public function MetaImageWrapperWidth()
    {
        if ($this->owner->HasMetaImage() && $this->owner->MetaImageCaption) {
            
            return $this->owner->MetaImageResized()->getWidth();
            
        }
        
        return 0;
    }
    
    /**
     * Answers a string of class names for the meta image wrapper.
     *
     * @return string
     */
    public function MetaImageWrapperClass()
    {
        return implode(' ', $this->MetaImageWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the meta image wrapper.
     *
     * @return array
     */
    public function MetaImageWrapperClassNames()
    {
        $classes = array();
        
        $classes[] = $this->owner->MetaImageCaption ? 'captionImage' : 'image';
        
        if ($alignment = $this->owner->getDefaultMetaImageAlignment()) {
            
            $classes[] = $alignment;
            
        }
        
        return $classes;
    }
    
    /**
     * Answers the meta image for the extended object.
     *
     * @return Image
     */
    public function MetaImage()
    {
        if ($this->owner->hasOne('MetaImageFile')) {
            return $this->owner->MetaImageFile();
        }
    }
    
    /**
     * Answers true if the extended object has an image.
     *
     * @return boolean
     */
    public function HasMetaImage()
    {
        return $this->owner->MetaImageExists();
    }
    
    /**
     * Answers true if a meta image exists for the extended object.
     *
     * @return boolean
     */
    public function MetaImageExists()
    {
        if ($Image = $this->owner->MetaImage()) {
            return $Image->exists();
        }
        
        return false;
    }
    
    /**
     * Answers true if the image is to be shown.
     *
     * @return boolean
     */
    public function MetaImageShown()
    {
        return ($this->owner->HasMetaImage() && !$this->owner->MetaImageHidden);
    }
    
    /**
     * Answers true if the meta image is linked.
     *
     * @return boolean
     */
    public function MetaImageLinked()
    {
        return ($this->owner->getDefaultMetaImageLinks() == 'Enabled');
    }
    
    /**
     * Answers true if the extended object has an image caption.
     *
     * @return boolean
     */
    public function HasMetaImageCaption()
    {
        return (boolean) $this->owner->MetaImageCaption;
    }
    
    /**
     * Answers the appropriate link for the image.
     *
     * @param string $type Type of link to return.
     * @return string
     */
    public function MetaImageLink($type = null)
    {
        switch (strtolower($type)) {
            
            case 'item':
                return $this->owner->MetaLink();
            
            default:
                return ($image = $this->owner->MetaImage()) ? $image->getAbsoluteURL() : null;
            
        }
    }
    
    /**
     * Answers true if the extended object has a meta image link of the specified type.
     *
     * @param string $type Type of link to return.
     * @return boolean
     */
    public function HasMetaImageLink($type = null)
    {
        return (boolean) $this->owner->MetaImageLink($type);
    }
    
    /**
     * Answers a resized version of the image for the extended object.
     *
     * @param integer $width
     * @param integer $height
     * @param string $method
     * @return Image
     */
    public function MetaImageResized($width = null, $height = null, $method = null)
    {
        if ($this->owner->MetaImageExists()) {
            
            if (!$width) {
                $width = $this->owner->getDefaultMetaImageWidth();
            }
            
            if (!$height) {
                $height = $this->owner->getDefaultMetaImageHeight();
            }
            
            if (!$method) {
                $method = $this->owner->getDefaultMetaImageResize();
            }
            
            return ImageTools::resize_image($this->owner->MetaImage(), $width, $height, $method);
            
        }
    }
    
    /**
     * Answers the default meta image width.
     *
     * @return integer
     */
    public function getDefaultMetaImageWidth()
    {
        if ($width = $this->owner->MetaImageWidth) {
            return $width;
        }
        
        return $this->getAttributeFromParent('DefaultImageWidth');
    }
    
    /**
     * Answers the default meta image height.
     *
     * @return integer
     */
    public function getDefaultMetaImageHeight()
    {
        if ($height = $this->owner->MetaImageHeight) {
            return $height;
        }
        
        return $this->getAttributeFromParent('DefaultImageHeight');
    }
    
    /**
     * Answers the default meta image resize method.
     *
     * @return string
     */
    public function getDefaultMetaImageResize()
    {
        if ($resize = $this->owner->MetaImageResize) {
            return $resize;
        }
        
        return $this->getAttributeFromParent('DefaultImageResize');
    }
    
    /**
     * Answers the default meta image alignment.
     *
     * @return string
     */
    public function getDefaultMetaImageAlignment()
    {
        if ($alignment = $this->owner->MetaImageAlignment) {
            return $alignment;
        }
        
        return $this->getAttributeFromParent('DefaultImageAlignment');
    }
    
    /**
     * Answers the default meta image links setting.
     *
     * @return string
     */
    public function getDefaultMetaImageLinks()
    {
        if ($links = $this->owner->MetaImageLinks) {
            return $links;
        }
        
        return $this->getAttributeFromParent('DefaultImageLinks');
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
        
        if ($this->owner->hasExtension('Hierarchy')) {
            
            if ($this->owner->ParentID) {
                
                $Parent = $this->owner->Parent();
                
                while (!$value && ($Parent instanceof Page)) {
                    
                    $value = $Parent->{$attribute};
                    
                    $Parent = $Parent->Parent();
                    
                }
                
            }
            
        }
        
        return $value;
    }
}
