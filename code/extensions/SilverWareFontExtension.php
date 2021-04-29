<?php

/**
 * An extension of the extension class which allows controllers to use SilverWare fonts.
 */
class SilverWareFontExtension extends Extension
{
    /**
     * Event handler method triggered after the controller has initialised.
     */
    public function onAfterInit()
    {
        if ($Config = SiteConfig::current_site_config()) {
            
            foreach ($Config->SilverWareFonts()->filter(array('Disabled' => 0)) as $Font) {
                
                Requirements::css($Font->getURL());
                
            }
            
        }
    }
}
