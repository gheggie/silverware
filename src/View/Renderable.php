<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\View
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware
 */

namespace SilverWare\View;

use Psr\SimpleCache\CacheInterface;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\Debug;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Requirements;
use SilverStripe\View\SSViewer;
use SilverWare\Extensions\ControllerExtension;
use SilverWare\Tools\ClassTools;
use SilverWare\Tools\ViewTools;

/**
 * Allows an object to use extra classes and attributes for HTML rendering.
 *
 * @package SilverWare\View
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware
 */
trait Renderable
{
    /**
     * An array of required JavaScript files.
     *
     * @var array
     * @config
     */
    private static $required_js = [];
    
    /**
     * An array of required CSS files.
     *
     * @var array
     * @config
     */
    private static $required_css = [];
    
    /**
     * An array of required themed JavaScript files.
     *
     * @var array
     * @config
     */
    private static $required_themed_js = [];
    
    /**
     * An array of required themed CSS files.
     *
     * @var array
     * @config
     */
    private static $required_themed_css = [];
    
    /**
     * An array of required JavaScript template files.
     *
     * @var array
     * @config
     */
    private static $required_js_templates = [];
    
    /**
     * Extra classes for the HTML tag.
     *
     * @var array
     */
    protected $extraClasses = [];
    
    /**
     * Extra attributes for the HTML tag.
     *
     * @var array
     */
    protected $extraAttributes = [];
    
    /**
     * Answers the render cache object.
     *
     * @return CacheInterface
     */
    public static function getRenderCache()
    {
        return Injector::inst()->get(CacheInterface::class . '.SilverWare_render');
    }
    
    /**
     * Clears the contents of the entire render cache.
     *
     * @return boolean
     */
    public static function flushRenderCache()
    {
        return self::getRenderCache()->clear();
    }
    
    /**
     * Loads the requirements for the object.
     *
     * @return void
     */
    public function loadRequirements()
    {
        // Load Required Themed JavaScript:
        
        if (ContentController::config()->load_themed_js) {
            
            foreach ($this->getRequiredThemedJS() as $js) {
                Requirements::themedJavascript($js);
            }
            
        }
        
        // Load Required Themed CSS:
        
        if (ContentController::config()->load_themed_css) {
            
            foreach ($this->getRequiredThemedCSS() as $css => $media) {
                Requirements::themedCSS($css, $media);
            }
            
        }
        
        // Load Required JavaScript:
        
        if (ContentController::config()->load_js) {
            
            foreach ($this->getRequiredJS() as $js) {
                Requirements::javascript($js);
            }
            
        }
        
        // Load Required CSS:
        
        if (ContentController::config()->load_css) {
            
            foreach ($this->getRequiredCSS() as $css => $media) {
                Requirements::css($css, $media);
            }
            
        }
        
        // Load Required Custom CSS:
        
        if (ContentController::config()->load_custom_css) {
            
            if ($css = $this->getCustomCSSAsString()) {
                Requirements::customCSS($css, $this->HTMLID);
            }
            
        }
        
        // Load Required JavaScript Templates:
        
        foreach ($this->getRequiredJSTemplates() as $file => $params) {
            ViewTools::singleton()->loadJSTemplate($file, $params['vars'], $params['id']);
        }
    }
    
    /**
     * Answers an array of JavaScript files required by the object.
     *
     * @return array
     */
    public function getRequiredJS()
    {
        $js = $this->config()->required_js;
        
        $this->extend('updateRequiredJS', $js);
        
        return $js;
    }
    
    /**
     * Answers an array of CSS files required by the object.
     *
     * @return array
     */
    public function getRequiredCSS()
    {
        $css = $this->config()->required_css;
        
        $this->extend('updateRequiredCSS', $css);
        
        return $this->processCSSConfig($css);
    }
    
    /**
     * Answers an array of themed JavaScript files required by the object.
     *
     * @return array
     */
    public function getRequiredThemedJS()
    {
        $js =  $this->config()->required_themed_js;
        
        $this->extend('updateRequiredThemedJS', $js);
        
        return $js;
    }
    
    /**
     * Answers an array of themed CSS files required by the object.
     *
     * @return array
     */
    public function getRequiredThemedCSS()
    {
        $css =  $this->config()->required_themed_css;
        
        $this->extend('updateRequiredThemedCSS', $css);
        
        return $this->processCSSConfig($css);
    }
    
    /**
     * Answers an array of JavaScript templates required by the object.
     *
     * @return array
     */
    public function getRequiredJSTemplates()
    {
        $js = $this->config()->required_js_templates;
        
        $this->extend('updateRequiredJSTemplates', $js);
        
        return $this->processJSTemplateConfig($js);
    }
    
    /**
     * Answers an array of variables required by a JavaScript template.
     *
     * @return array
     */
    public function getJSVars()
    {
        if (in_array(Renderable::class, class_uses(self::class))) {
            return ['HTMLID' => $this->getHTMLID(), 'CSSID' => $this->getCSSID()];
        }
        
        return [];
    }
    
    /**
     * Defines the value of the extra classes attribute.
     *
     * @param array $classes
     *
     * @return $this
     */
    public function setExtraClasses($classes = [])
    {
        $this->extraClasses = (array) $classes;
        
        return $this;
    }
    
    /**
     * Answers the value of the extra classes attribute.
     *
     * @return array
     */
    public function getExtraClasses()
    {
        return $this->extraClasses;
    }
    
    /**
     * Adds one or more extra class names to the receiver.
     *
     * @param string $class Class name(s), space-delimited for multiple class names.
     *
     * @return $this
     */
    public function addExtraClass($class)
    {
        $classes = preg_split('/\s+/', trim($class));
        
        foreach ($classes as $class) {
            $this->extraClasses[$class] = $class;
        }
        
        return $this;
    }
    
    /**
     * Answers an array of class names for the HTML template.
     *
     * @return array
     */
    public function getClassNames()
    {
        // Obtain Class Names:
        
        $classes = array_merge(
            $this->getStyleClassNames(),
            $this->getCustomClassNames(),
            $this->getAncestorClassNames()
        );
        
        // Obtain Extra Class Names:
        
        $classes = array_merge($classes, $this->getExtraClasses());
        
        // Apply Extensions:
        
        $this->extend('updateClassNames', $classes);
        
        // Answer Class Names:
        
        return $classes;
    }
    
    /**
     * Answers an array of style class names for the HTML template.
     *
     * @return array
     */
    public function getStyleClassNames()
    {
        return explode(' ', $this->getField('StyleClasses'));
    }
    
    /**
     * Answers an array of custom style class names for the HTML template.
     *
     * @return array
     */
    public function getCustomClassNames()
    {
        $classes = [];
        
        if (is_array($this->CustomStylesMappings)) {
            return array_values($this->CustomStylesMappings);
        }
        
        return $classes;
    }
    
    /**
     * Answers an array of ancestor class names for the HTML template.
     *
     * @param boolean $removeNamespaces
     *
     * @return array
     */
    public function getAncestorClassNames($removeNamespaces = true)
    {
        return ViewTools::singleton()->getAncestorClassNames($this, self::class, $removeNamespaces);
    }
    
    /**
     * Answers an array of style attributes for the HTML template.
     *
     * @return array
     */
    public function getStyleAttributes()
    {
        return [
            'id' => $this->getHTMLID(),
            'class' => $this->getHTMLClass()
        ];
    }
    
    /**
     * Defines an attribute with the given name and value.
     *
     * @param string $name Attribute name.
     * @param string $value Attribute value.
     *
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        $this->extraAttributes[$name] = $value;
        
        return $this;
    }
    
    /**
     * Defines the value of the extra attributes attribute.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setExtraAttributes($attributes = [])
    {
        $this->extraAttributes = (array) $attributes;
        
        return $this;
    }
    
    /**
     * Answers the value of the extra attributes attribute.
     *
     * @return array
     */
    public function getExtraAttributes()
    {
        return $this->extraAttributes;
    }
    
    /**
     * Answers an array of HTML tag attributes for the object.
     *
     * @return array
     */
    public function getAttributes()
    {
        // Obtain Attributes:
        
        $attributes = $this->getStyleAttributes();
        
        // Obtain Extra Attributes:
        
        $attributes = array_merge($attributes, $this->getExtraAttributes());
        
        // Apply Extensions:
        
        $this->extend('updateAttributes', $attributes);
        
        // Answer Attributes:
        
        return $attributes;
    }
    
    /**
     * Answers the HTML tag attributes of the object as a string.
     *
     * If $attributes is a string, all arguments act as named attributes to exclude from rendering.
     *
     * @param array|string $attributes
     *
     * @return string
     */
    public function getAttributesHTML($attributes = null)
    {
        // Initialise:
        
        $remove = [];
        
        // Detect Excluded Attributes:
        
        if (is_string($attributes)) {
            $remove = func_get_args();
        }
        
        // Detect Passed Attributes:
        
        if (!$attributes || is_string($attributes)) {
            $attributes = $this->getAttributes();
        }
        
        // Ensure Array Data Type:
        
        $attributes = (array) $attributes;
        
        // Remove Excluded Attributes:
        
        if (!empty($remove)) {
            $attributes = array_diff_key($attributes, array_flip($remove));
        }
        
        // Answer Markup:
        
        return ViewTools::singleton()->getAttributesHTML($attributes);
    }
    
    /**
     * Answers a unique ID for the HTML template.
     *
     * @return string
     */
    public function getHTMLID()
    {
        return Convert::raw2att($this->StyleID ? $this->StyleID : $this->getDefaultStyleID());
    }
    
    /**
     * Answers a unique ID for a CSS stylesheet.
     *
     * @param string $id Optional ID argument.
     *
     * @return string
     */
    public function getCSSID($id = null)
    {
        return sprintf('#%s', $id ? $id : $this->getHTMLID());
    }
    
    /**
     * Answers a string of class names for the HTML template.
     *
     * @return string
     */
    public function getHTMLClass()
    {
        return ViewTools::singleton()->array2att($this->getClassNames());
    }
    
    /**
     * Answers the default style ID for the HTML template.
     *
     * @return string
     */
    public function getDefaultStyleID()
    {
        return $this->getClassNameWithID();
    }
    
    /**
     * Defines the style ID of the receiver from the given data object and optional suffix.
     *
     * @param DataObject $object
     * @param string $suffix
     *
     * @return $this
     */
    public function setStyleIDFrom(DataObject $object, $suffix = null)
    {
        $this->StyleID = sprintf(
            '%s_%s',
            ClassTools::singleton()->getClassWithoutNamespace(get_class($object)),
            ClassTools::singleton()->getClassWithoutNamespace(get_class($this))
        );
        
        if (!is_null($suffix)) {
            $this->StyleID = sprintf(
                '%s_%s',
                $this->StyleID,
                $suffix
            );
        }
        
        $this->StyleID = $this->cleanStyleID($this->StyleID);
        
        return $this;
    }
    
    /**
     * Answers the class name and ID of the object as a string.
     *
     * @param boolean $includeNamespace If true, also include the namespace of the object.
     *
     * @return string
     */
    public function getClassNameWithID($includeNamespace = false)
    {
        $className = static::class;
        
        if (!$includeNamespace) {
            $className = ClassTools::singleton()->getClassWithoutNamespace($className);
        }
        
        return sprintf(
            '%s_%s',
            $className,
            $this->ID
        );
    }
    
    /**
     * Answers true if the object is enabled within the template.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return !$this->isDisabled();
    }
    
    /**
     * Answers true if the object is disabled within the template.
     *
     * @return boolean
     */
    public function isDisabled()
    {
        return (boolean) $this->getField('Disabled');
    }
    
    /**
     * Answers an array of custom CSS required for the template.
     *
     * @return array
     */
    public function getCustomCSS()
    {
        // Create CSS Array:
        
        $css = [];
        
        // Merge Custom CSS from Template:
        
        foreach (ClassTools::singleton()->getObjectAncestry($this, self::class) as $class) {
            
            $template = $this->getCustomCSSTemplate($class);
            
            if (SSViewer::hasTemplate($template)) {
                $css = ViewTools::singleton()->renderCSS($this, $template, $css);
            }
            
        }
        
        // Update CSS via Extensions:
        
        $this->extend('updateCustomCSS', $css);
        
        // Filter CSS Array:
        
        $css = array_filter($css);
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Answers the custom CSS required for the template as a string.
     *
     * @return string
     */
    public function getCustomCSSAsString()
    {
        // Create CSS String:
        
        $css = implode("\n", $this->getCustomCSS());
        
        // Remove Empty Lines:
        
        $css = ViewTools::singleton()->removeEmptyLines($css);
        
        // Trim CSS String:
        
        $css = trim($css);
        
        // Minify CSS String:
        
        if (!Director::isDev()) {
            $css = ViewTools::singleton()->minifyCSS($css);
        }
        
        // Answer CSS String:
        
        return $css;
    }
    
    /**
     * Answers the name of a template used to render custom CSS for the receiver.
     *
     * @param string $class
     *
     * @return string
     */
    public function getCustomCSSTemplate($class = null)
    {
        return sprintf('%s\CustomCSS', $class ? $class : static::class);
    }
    
    /**
     * Cleans the given style ID string.
     *
     * @param string $string String containing a style ID.
     *
     * @return string
     */
    public function cleanStyleID($string)
    {
        return preg_replace('/[^a-zA-Z0-9_-]+/', '', $string);
    }
    
    /**
     * Cleans the given style classes string.
     *
     * @param string $string String containing a series of style classes.
     *
     * @return string
     */
    public function cleanStyleClasses($string)
    {
        return preg_replace('/[^a-zA-Z0-9_-]+|[\s]+/', ' ', trim($string));
    }
    
    /**
     * Answers true if the receiver is being previewed within the CMS.
     *
     * @return boolean
     */
    public function isCMSPreview()
    {
        return (isset($_GET['CMSPreview']) && $_GET['CMSPreview']);
    }
    
    /**
     * Answers true if the object uses a render cache.
     *
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return ($this->getField('Cached') && !ControllerExtension::isCacheDisabled());
    }
    
    /**
     * Clears the render cache for the receiver.
     *
     * @return boolean
     */
    public function clearRenderCache()
    {
        return self::getRenderCache()->delete($this->getRenderCacheID());
    }
    
    /**
     * Answers the render cache ID for the receiver.
     *
     * @return string
     */
    public function getRenderCacheID()
    {
        return Convert::raw2htmlid($this->getClassNameWithID(true));
    }
    
    /**
     * Answers the render cache lifetime for the receiver.
     *
     * @return integer
     */
    public function getRenderCacheLifetime()
    {
        return (integer) $this->getField('CacheLifetime');
    }
    
    /**
     * Renders the object for the HTML template.
     *
     * @return DBHTMLText|string
     */
    public function forTemplate()
    {
        // Render Object:
        
        return $this->render();
    }
    
    /**
     * Renders the object for the HTML template.
     *
     * @param string $layout Page layout passed from template.
     * @param string $title Page title passed from template.
     *
     * @return DBHTMLText|string
     */
    public function render($layout = null, $title = null)
    {
        // Initialise:
        
        $html = '';
        
        // Load Requirements:
        
        $this->loadRequirements();
        
        // Obtain Cached HTML (if enabled):
        
        if ($this->isCacheEnabled()) {
            $html = self::getRenderCache()->get($this->getRenderCacheID());
        }
        
        // Render HTML:
        
        if (!$html) {
            
            // Render Self:
            
            $html = (string) $this->renderSelf($layout, $title);
            
            // Cache Rendered HTML:
            
            if ($this->isCacheEnabled()) {
                self::getRenderCache()->set($this->getRenderCacheID(), $html, $this->getRenderCacheLifetime());
            }
            
        } elseif (isset($_REQUEST['debug_cache'])) {
            
            // Output Debug Information:
            
            Debug::message(
                sprintf(
                    'Rendered from cache: "%s" [%s]; lifetime: %d second(s)',
                    $this->getTitle(),
                    $this->getRenderCacheID(),
                    $this->getRenderCacheLifetime()
                ),
                false
            );
            
        }
        
        // Answer HTML:
        
        return $html;
    }
    
    /**
     * Processes the given CSS config and answers an array suitable for loading requirements.
     *
     * @param array $config
     *
     * @return array
     */
    protected function processCSSConfig($config)
    {
        if (!ArrayLib::is_associative($config)) {
            return array_fill_keys(array_values($config), null); 
        }
        
        return $config;
    }
    
    /**
     * Processes the given JavaScript template config and answers an array suitable for loading requirements.
     *
     * @param array $config
     *
     * @return array
     */
    protected function processJSTemplateConfig($config)
    {
        $templates = [];
        
        foreach ($config as $key => $value) {
            
            if (is_integer($key)) {
                
                $templates[$value] = [
                    'vars' => $this->getJSVars(),
                    'id'   => $this->getHTMLID()
                ];
                
            } else {
                
                $templates[$key] = [
                    'vars' => $this->getJSTemplateVars(array_shift($value)),
                    'id'   => $this->getJSTemplateID(array_shift($value))
                ];
                
            }
            
        }
        
        return $templates;
    }
    
    /**
     * Answers an array of variables for a required JavaScript template.
     *
     * @param string|array $vars Array of variables or method name.
     *
     * @return array
     */
    protected function getJSTemplateVars($vars)
    {
        if (is_array($vars)) {
            return $vars;
        }
        
        if ($this->hasMethod($vars)) {
            return $this->{$vars}();
        }
        
        return [];
    }
    
    /**
     * Answers the ID for a required JavaScript template.
     *
     * @param string $id ID or method name.
     *
     * @return string
     */
    protected function getJSTemplateID($id)
    {
        if ($this->hasMethod($id)) {
            return $this->{$id}();
        }
        
        return $id;
    }
}
