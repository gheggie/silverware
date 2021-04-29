<?php

/**
 * An extension of the extension class which allows controllers to use SilverWare.
 */
class SilverWareControllerExtension extends Extension implements Flushable
{
    /**
     * Define constants.
     */
    const PATTERN_RELATIVE_URL = "/url\(('|\")?\.\.\//";
    
    /**
     * Deletes any processed CSS files upon flush.
     */
    public static function flush()
    {
        self::delete_processed_css_files();
    }
    
    /**
     * Deletes any processed CSS files within the combined files folder.
     */
    public static function delete_processed_css_files()
    {
        $path = Director::getAbsFile(self::get_processed_css_folder());
        
        if (file_exists($path)) {
            Filesystem::removeFolder($path, true);
        }
    }
    
    /**
     * Answers the folder relative to the webroot for processed CSS files.
     *
     * @return string
     */
    public static function get_processed_css_folder()
    {
        return Requirements::backend()->getCombinedFilesFolder() . '/css';
    }
    
    /**
     * Event handler method triggered before the controller has initialised.
     */
    public function onBeforeInit()
    {
        // Ignore Ajax Requests:
        
        if ($this->isAjaxRequest()) {
            return;
        }
        
        // Load Core JavaScript Libraries:
        
        $this->owner->loadjQuery();
        $this->owner->loadEntwine();
    }
    
    /**
     * Event handler method triggered after the controller has initialised.
     */
    public function onAfterInit()
    {
        // Ignore Ajax Requests:
        
        if ($this->isAjaxRequest()) {
            return;
        }
        
        // Load Requirements:
        
        if (defined('SILVERWARE_DIR')) {
            
            // Load Font Awesome CSS:
            
            Requirements::css(SILVERWARE_DIR . '/thirdparty/font-awesome/css/font-awesome.min.css');
            
            // Load Animate CSS:
            
            Requirements::css(SILVERWARE_DIR . '/thirdparty/animate/animate.min.css');
            
            // Load jQuery Plugin CSS:
            
            Requirements::css(SILVERWARE_DIR . '/thirdparty/faloading/faloading.min.css');
            
            // Load Themed CSS:
            
            SilverWareTheme::load_css();
            
            // Load Custom CSS:
            
            Requirements::customCSS($this->getCustomCSSAsString());
            
            // Load Required Scripts:
            
            Requirements::javascript(SILVERWARE_DIR . '/thirdparty/squery/squery.min.js');
            Requirements::javascript(SILVERWARE_DIR . '/thirdparty/scrollto/scrollto.min.js');
            Requirements::javascript(SILVERWARE_DIR . '/thirdparty/modernizr/modernizr.min.js');
            
            // Load jQuery Plugin Scripts:
            
            Requirements::javascript(SILVERWARE_DIR . '/thirdparty/basictable/basictable.min.js');
            Requirements::javascript(SILVERWARE_DIR . '/javascript/basictable/basictable.init.js');
            
            Requirements::javascript(SILVERWARE_DIR . '/thirdparty/faloading/faloading.min.js');
            Requirements::javascript(SILVERWARE_DIR . '/javascript/faloading/faloading.init.js');
            
            Requirements::javascript(SILVERWARE_DIR . '/thirdparty/stellar/stellar.min.js');
            Requirements::javascript(SILVERWARE_DIR . '/javascript/stellar/stellar.init.js');
            
            // Load Validation Scripts:
            
            if (defined('ZENVALIDATOR_PATH')) {
                Requirements::javascript(ZENVALIDATOR_PATH . '/javascript/parsley/parsley.remote.min.js');
                Requirements::javascript(ZENVALIDATOR_PATH . '/javascript/zenvalidator.js');
            }
            
            // Load Component Requirements:
            
            $this->getComponentRequirements();
            
            // Load Enabled Style Sheets:
            
            $this->loadEnabledStyleSheets();
            
            // Combine Files:
            
            $this->combineFiles();
            
        }
    }
    
    /**
     * Answers true if the HTTP request is an Ajax request.
     *
     * @return boolean
     */
    public function isAjaxRequest()
    {
        return $this->owner->getRequest()->isAjax();
    }
    
    /**
     * Answers the custom CSS required for the template.
     *
     * @return string
     */
    public function getCustomCSSAsString()
    {
        // Create CSS Array:
        
        $css = array();
        
        // Merge Custom CSS from Page:
        
        if ($this->owner instanceof Page_Controller) {
            
            $css = array_merge($css, $this->owner->getCustomCSS());
            
        }
        
        // Create CSS String:
        
        $css = implode("\n", $css);
        
        // Remove Empty Lines from CSS:
        
        $css = SilverWareTools::remove_empty_lines($css);
        
        // Minify and Wrap CSS (if not dev environment):
        
        if (!Director::isDev() || isset($_GET['minifycss'])) {
            
            $css = SilverWareTools::minify_and_wrap_css($css);
            
        }
        
        // Trim CSS String:
        
        $css = trim($css);
        
        // Answer CSS String:
        
        return $css;
    }
    
    /**
     * Loads the requirements for all components within the page.
     */
    public function getComponentRequirements()
    {
        if ($this->owner instanceof Page_Controller) {
            
            foreach ($this->owner->getEnabledComponents() as $Component) {
                $Component->getRequirements();
            }
            
        }
    }
    
    /**
     * Loads the appropriate jQuery library as defined by configuration.
     */
    public function loadjQuery()
    {
        if (Config::inst()->get('SilverWareControllerExtension', 'use_custom_jquery')) {
            
            // Use Custom jQuery:
            
            Requirements::block(THIRDPARTY_DIR . '/jquery/jquery.js');
            Requirements::javascript(SILVERWARE_DIR . '/thirdparty/jquery/jquery.min.js');
            
        } else {
            
            // Use Framework jQuery:
            
            Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
            
        }
    }
    
    /**
     * Loads the standard jQuery Entwine library.
     */
    public function loadEntwine()
    {
        Requirements::javascript(THIRDPARTY_DIR . '/jquery-entwine/dist/jquery.entwine-dist.js');
    }
    
    /**
     * Loads the enabled style sheets from site configuration.
     */
    public function loadEnabledStyleSheets()
    {
        SiteConfig::current_site_config()->getEnabledStyleSheets()->each(function ($StyleSheet) {
            $StyleSheet->load();
        });
    }
    
    /**
     * Combines required CSS and JavaScript files to increase performance.
     */
    private function combineFiles()
    {
        // Check Environment / Combine Files Status:
        
        if ((Director::isDev() && !isset($_REQUEST['combine'])) || !Requirements::get_combined_files_enabled()) {
            return;
        }
        
        // Combine Required Stylesheets:
        
        $css = $this->getCombinableCSS();
        
        Requirements::combine_files($this->hashFiles($css, 'css'), $css);
        
        // Combine Required Scripts:
        
        $js = $this->getCombinableJS();
        
        Requirements::combine_files($this->hashFiles($js, 'js'), $js);
    }
    
    /**
     * Answers an array of CSS files to combine (filters thirdparty, http, https and protocol relative URLs).
     *
     * @return array
     */
    private function getCombinableCSS()
    {
        // Obtain Files:
        
        $files = array_keys(Requirements::backend()->get_css());
        
        // Remove Protocol-Specific Files:
        
        $files = array_filter(
            $files,
            function ($path) {
                return !preg_match('/^(https?:)?\/\//', $path);
            }
        );
        
        // Remove Non-Existing Files:
        
        $files = array_filter(
            $files,
            array($this, 'checkFileExists')
        );
        
        // Process Third-Party CSS:
        
        $files = $this->processThirdPartyFiles($files);
        
        // Order Remaining Files:
        
        $theme_files = array();
        $other_files = array();
        
        foreach ($files as $file) {
            
            if (strpos($file, THEMES_DIR) === 0) {
                $theme_files[] = $file;
            } else {
                $other_files[] = $file;
            }
            
        }
        
        // Answer Combinable Files:
        
        return array_merge($theme_files, $other_files);
    }
    
    /**
     * Answers an array of JavaScript files to combine.
     *
     * @return array
     */
    private function getCombinableJS()
    {
        // Obtain Files:
        
        $files = Requirements::backend()->get_javascript();
        
        // Remove Non-Existing Files:
        
        return array_filter(
            $files,
            array($this, 'checkFileExists')
        );
    }
    
    /**
     * Generates a hash for the given array of files and answers a filename with the hash and extension.
     *
     * @return string
     */
    private function hashFiles($files, $ext)
    {
        return sprintf(
            'combined_%s.%s',
            md5(implode(',', $files)),
            $ext
        );
    }
    
    /**
     * Answers true if the specified requirements file exists.
     *
     * @param string $path
     * @return boolean
     */
    private function checkFileExists($path)
    {
        return file_exists(Director::getAbsFile($path));
    }
    
    /**
     * Processes the third-party CSS files within the given array.
     *
     * @param array $files
     * @return array
     */
    public function processThirdPartyFiles($files)
    {
        // Get Current Directory:
        
        $cwd = getcwd();
        
        // Iterate Array of Files:
        
        foreach ($files as $key => $file) {
            
            // Detect Third-Party File:
            
            if (strpos($file, 'thirdparty') !== false) {
                
                // Load File Contents:
                
                $contents = file_get_contents(Director::getAbsFile($file));
                
                // Check for Relative URLs:
                
                if (preg_match(self::PATTERN_RELATIVE_URL, $contents)) {
                    
                    // Change Directory to File Folder:
                    
                    chdir(dirname(Director::getAbsFile($file)));
                    
                    // Find All URLs within File Contents:
                    
                    preg_match_all('/url\((\'|")?(.*?)(\'|")?\)/', $contents, $matches);
                    
                    // Initialise Search / Replace Arrays:
                    
                    $search  = array();
                    $replace = array();
                    
                    // Iterate Matching URLs:
                    
                    foreach ($matches[2] as $url) {
                        
                        // Identify Relative URL:
                        
                        if (strpos($url, '../') === 0) {
                            
                            // Add URL to Search Array:
                            
                            $search[] = $url;
                            
                            // Split URL into File Path and Query String:
                            
                            list($fp, $qs) = array_pad(explode('?', $url), 2, '');
                            
                            // Split File Path by Directory Separator:
                            
                            $parts = explode(DIRECTORY_SEPARATOR, realpath($fp));
                            
                            // Identify Key of Third-Party Folder:
                            
                            $tp = array_search('thirdparty', $parts);
                            
                            // Add Replacement to Replace Array:
                            
                            if ($tp > 0) {
                                
                                $replace[] = sprintf(
                                    '/%s%s',
                                    implode('/', array_slice($parts, $tp - 1)),
                                    ($qs ? "?{$qs}" : '')
                                );
                                
                            } else {
                                
                                $replace[] = $url;
                                
                            }
                            
                        }
                        
                    }
                    
                    // Get Full Processed CSS Folder Path:
                    
                    $folder_path = Director::getAbsFile(self::get_processed_css_folder());
                    
                    // Define Target File and Path:
                    
                    $target_file = basename($file);
                    $target_path = $folder_path . '/' . $target_file;
                    
                    // Create Folder (if required):
                    
                    if (!file_exists($folder_path)) {
                        Filesystem::makeFolder($folder_path);
                    }
                    
                    // Create Target File (if required):
                    
                    if (!file_exists($target_path)) {
                        file_put_contents($target_path, str_replace($search, $replace, $contents));
                    }
                    
                    // Replace Original Required File with Processed File:
                    
                    $files[$key] = self::get_processed_css_folder() . '/' . $target_file;
                    
                    // Block Original Required File:
                    
                    Requirements::block($file);
                    
                }
                
            }
            
        }
        
        // Restore Current Directory:
        
        chdir($cwd);
        
        // Answer Array of Files:
        
        return $files;
    }
}
