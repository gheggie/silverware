<?php

/**
 * An extension of the data extension class to apply styles to the extended object.
 */
class SilverWareStyleExtension extends DataExtension
{
    /**
     * Define constants.
     */
    const PATTERN_SIZE = '/^([\d.]+)([\w]*)$/i';
    
    /**
     * An array of CSS properties associated with method names.
     *
     * @var array
     */
    protected $css = array();
    
    /**
     * Updates the array of custom CSS for the extended object.
     *
     * @param array $css
     */
    public function updateCustomCSS(&$css)
    {
        foreach ($this->css as $property => $method) {
            
            if ($value = $this->$method()) {
                
                $css[] = "  {$property}: {$value};";
                
            }
            
        }
    }
    
    /**
     * Defines the value of the specified size property.
     *
     * @param string $value
     * @param string $size
     * @param string $unit
     * @return DataObject
     */
    protected function setSizeFromValue($value, $property)
    {
        // Check Value Type:
        
        if (is_numeric($value)) {
            
            // Define Numeric Value for 'All' Property:
            
            $this->owner->setField($property, $value);
            
        } else {
            
            // Obtain Parsed Values:
            
            $sizes = $this->parseSizeValue($value);
            
            if (count($sizes) <= 1) {
                
                // Define 'All' Property:
                
                $this->setSizeProperty((count($sizes) == 1 ? $sizes[0] : null), $property);
                
            } else {
                
                // Obtain Values:
                
                list($t, $r, $b, $l) = $sizes;
                
                // Nullify 'All' Property:
                
                $this->owner->setField($property, null);
                
                // Define Size Properties:
                
                $this->setSizeProperty($t, "{$property}Top");
                $this->setSizeProperty($r, "{$property}Right");
                $this->setSizeProperty($b, "{$property}Bottom");
                $this->setSizeProperty($l, "{$property}Left");
                
            }
            
        }
        
        // Answer Extended Object:
        
        return $this->owner;
    }
    
    /**
     * Defines the value of the specified size and unit properties.
     *
     * @param string $value
     * @param string $size
     * @param string $unit
     * @return DataObject
     */
    protected function setSizeProperty($value, $size, $unit = null)
    {
        // Define Size Property:
        
        $psize = $size;
        
        // Define Unit Property (if required):
        
        $punit = $unit ? $unit : "{$size}Unit";
        
        // Check Value:
        
        if (!is_null($value) && $value !== '') {
            
            // Perform Pattern Match:
            
            preg_match(self::PATTERN_SIZE, $value, $matches);
            
            // Obtain Matched Values:
            
            list($match, $vsize, $vunit) = $matches;
            
            // Define Size Property:
            
            $this->owner->setField($psize, $vsize);
            
            // Define Unit Property:
            
            if ($vunit) {
                $this->owner->setField($punit, $vunit);
            }
            
        } else {
            
            // Nullify Size Property:
            
            $this->owner->setField($psize, null);
            
        }
        
        // Answer Extended Object:
        
        return $this->owner;
    }
    
    /**
     * Parses the given size value into an array of strings.
     *
     * @param string $value
     * @return array
     */
    protected function parseSizeValue($value)
    {
        // Parse Size Values:
        
        list($t, $r, $b, $l) = sscanf($value, '%s %s %s %s');
        
        // Check Null Values:
        
        if (is_null($t) && is_null($r) && is_null($b) && is_null($l)) {
            return array();
        }
        
        // Determine Answer Format:
        
        if (is_null($r) && is_null($b) && is_null($l)) {
            return array($t);
        } elseif (is_null($b) && is_null($l)) {
            return array($t, $r, $t, $r);
        } elseif (is_null($l)) {
            return array($t, $r, $b, $r);
        }
        
        // Answer All Sizes:
        
        return array($t, $r, $b, $l);
    }
}
