<?php

/**
 * An extension of the data extension class which allows images to automatically rotate based on EXIF metadata.
 */
class AutoRotateExtension extends DataExtension
{
    /**
     * Event method called after the file is uploaded.
     */
    public function onAfterUpload()
    {
        // Check Site Configuration:
        
        if (!($Config = SiteConfig::current_site_config()) || !$Config->SilverWareImageAutoRotate) {
            return;
        }
        
        // Verify File Existence:
        
        if ($this->owner->exists() && extension_loaded('exif')) {
            
            // Obtain File Extension:
            
            $ext = strtolower($this->owner->getExtension());
            
            // Verify File is JPEG:
            
            if (in_array($ext, array('jpg', 'jpeg'))) {
                
                // Obtain Full Path for Image:
                
                $path = $this->owner->getFullPath();
                
                // Read EXIF Data from Image:
                
                $exif = @exif_read_data($path);
                
                // Obtain Orientation Metadata:
                
                if (isset($exif['Orientation']) && function_exists('imagecreatefromjpeg')) {
                    
                    // Create Image Resource from Source:
                    
                    if ($source = imagecreatefromjpeg($path)) {
                        
                        // Initialise Vars:
                        
                        $rotated = false;
                        
                        // Determine Orientation and Rotate:
                        
                        switch ($exif['Orientation']) {
                            
                            // Rotate 180 Degrees:
                            
                            case 3:
                                $rotated = imagerotate($source, 180, 0);
                                break;
                            
                            // Rotate 90 Degrees Clockwise:
                            
                            case 6:
                                $rotated = imagerotate($source, -90, 0);
                                break;
                            
                            // Rotate 90 Degrees Counter Clockwise:
                            
                            case 8:
                                $rotated = imagerotate($source, 90, 0);
                                break;
                            
                        }
                        
                        // Write Rotated Image to File System:
                        
                        if ($rotated) {
                            imagejpeg($rotated, $path, 100);
                        }
                        
                        // Delete Cached Formatted Images:
                        
                        $this->owner->deleteFormattedImages();
                        
                    }
                    
                }
                
            }
            
        }
    }
}
