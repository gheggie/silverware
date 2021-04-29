<?php

/**
 * Static class providing utility functions for use with images.
 */
class ImageTools
{
    /**
     * Answers an array of available image resize methods.
     *
     * @return array
     */
    public static function get_resize_methods()
    {
        return Config::inst()->get(__CLASS__, 'resize_methods');
    }
    
    /**
     * Answers an array of available image alignments (courtesy of HTMLEditorField).
     *
     * @return array
     */
    public static function get_image_alignments()
    {
        return array(
            'leftAlone' => _t('HtmlEditorField.CSSCLASSLEFTALONE', 'On the left, on its own.'),
            'center' => _t('HtmlEditorField.CSSCLASSCENTER', 'Centered, on its own.'),
            'left' => _t('HtmlEditorField.CSSCLASSLEFT', 'On the left, with text wrapping around.'),
            'right' => _t('HtmlEditorField.CSSCLASSRIGHT', 'On the right, with text wrapping around.')
        );
    }
    
    /**
     * Answers an array of available thumbnail alignments.
     *
     * @return array
     */
    public static function get_thumbnail_alignments()
    {
        return array(
            'None' => _t('ImageTools.NONE', 'None'),
            'Left' => _t('ImageTools.LEFT', 'Left'),
            'Right' => _t('ImageTools.RIGHT', 'Right'),
            'Stagger' => _t('ImageTools.STAGGER', 'Stagger')
        );
    }
    
    /**
     * Answers an array of available image linking options (via ListComponent).
     *
     * @todo Put these in a better spot than the enum on ListComponent?
     * @return array
     */
    public static function get_link_to_options()
    {
        return singleton('ListComponent')->dbObject('ImageLinksTo')->enumValues();
    }
    
    /**
     * Resizes the given image using the specified parameters.
     *
     * @param Image $image
     * @param integer $width
     * @param integer $height
     * @param string $method
     * @return Image
     */
    public static function resize_image(Image $image, $width = null, $height = null, $method = null)
    {
        // Obtain Image Dimensions:
        
        $i_width  = $image->getWidth();
        $i_height = $image->getHeight();
        
        // Calculate Width and Height (if required):
        
        if ($width && !$height && $i_width) {
            
            $height = round(($width / $i_width) * $i_height);
            
        } elseif (!$width && $height && $i_height) {
            
            $width = round(($height / $i_height) * $i_width);
            
        }
        
        // Perform Image Resizing:
        
        if ($width && $height) {
            
            switch (strtolower($method)) {
                
                case 'crop-width':
                    return $image->CropWidth($width);
                
                case 'crop-height':
                    return $image->CropHeight($height);
                
                case 'fill':
                    return $image->Fill($width, $height);
                
                case 'fill-max':
                    return $image->FillMax($width, $height);
                
                case 'fill-priority':
                    return $image->FillPriority($width, $height);
                
                case 'fit-max':
                    return $image->FitMax($width, $height);
                
                case 'pad':
                    return $image->Pad($width, $height);
                
                case 'scale-width':
                    return $image->ScaleWidth($width);
                
                case 'scale-height':
                    return $image->ScaleHeight($height);
                
                case 'scale-max-width':
                    return $image->ScaleMaxWidth($width);
                
                case 'scale-max-height':
                    return $image->ScaleMaxHeight($height);
                
                default:
                    return $image->Fit($width, $height);
                
            }
            
        }
        
        return $image;
    }
}
