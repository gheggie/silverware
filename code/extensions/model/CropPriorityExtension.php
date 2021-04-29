<?php

/**
 * An extension of the data extension class which allows extended objects to set a priority for image cropping.
 */
class CropPriorityExtension extends DataExtension
{
    private static $db = array(
        'CropPriority' => "Enum('Center, Top, Left, Bottom, Right', 'Center')"
    );
    
    private static $defaults = array(
        'CropPriority' => 'Center'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Main',
            DropdownField::create(
                'CropPriority',
                _t('CropPriorityExtension.CROPPRIORITY', 'Crop priority'),
                $this->owner->dbObject('CropPriority')->enumValues()
            )
        );
    }
    
    /**
     * Resize and crop image to fill specified dimensions, using crop priority as the source region.
     *
     * @param integer $width Width to crop to.
     * @param integer $height Height to crop to.
     * @return Image
     */
    public function FillPriority($width, $height)
    {
        // Obtain Crop Priority:
        
        $priority = $this->owner->CropPriority;
        
        // Check Crop Priority Value:
        
        if (!$priority || $priority == 'Center') {
            
            // Perform Regular Fill, No Priority:
            
            return $this->owner->getFormattedImage('Fill', $width, $height);
            
        } else {
            
            // Perform Priority Fill:
            
            return $this->owner->getFormattedImage('FillPriority', $width, $height, $priority);
            
        }
    }
    
    /**
     * Resize and crop image to fill specified dimensions, using crop priority as the source region.
     *
     * @param GDBackend $gd
     * @param integer $width Width to crop to.
     * @param integer $height Height to crop to.
     * @param string $priority Region of image for crop priority.
     * @return GDBackend
     */
    public function generateFillPriority(GDBackend $gd, $width, $height, $priority)
    {
        // Get Source Image:
        
        $image = $this->owner;
        
        // Round Crop Dimensions:
        
        $width  = round($width);
        $height = round($height);
        
        // Get Source Dimensions:
        
        $src_w = $image->getWidth();
        $src_h = $image->getHeight();
        
        // Check Resize Required:
        
        if ($width == $src_w && $height == $src_h) {
            return $gd;
        }
        
        // Determine Aspect Ratios:
        
        $src_ar = $src_w / $src_h;
        $dst_ar = $width / $height;
        
        // Compare Aspect Ratios:
        
        if ($dst_ar < $src_ar) {
            
            // Destination Narrower:
            
            $gd = $gd->resizeByHeight($height);
            
            // Determine Overwidth Value:
            
            $overwidth = round($gd->getWidth() - $width);
            
            // Perform Crop:
            
            if ($priority == 'Left') {
                $gd = $gd->crop(0, 0, $width, $height);
            } elseif ($priority == 'Right') {
                $gd = $gd->crop(0, $overwidth, $width, $height);
            } else {
                $gd = $gd->crop(0, $overwidth / 2, $width, $height);
            }
            
        } else {
            
            // Destination Shorter:
            
            $gd = $gd->resizeByWidth($width);
            
            // Determine Overheight Value:
            
            $overheight = round($gd->getHeight() - $height);
            
            // Perform Crop:
            
            if ($priority == 'Top') {
                $gd = $gd->crop(0, 0, $width, $height);
            } elseif ($priority == 'Bottom') {
                $gd = $gd->crop($overheight, 0, $width, $height);
            } else {
                $gd = $gd->crop($overheight / 2, 0, $width, $height);
            }
            
        }
        
        // Answer Image Backend:
        
        return $gd;
    }
}
