<?php

/**
 * An extension of the data extension class which allows extended objects to use token mappings.
 */
class TokenMappingExtension extends DataExtension
{
    /**
     * Returns the token mappings for the extended object.
     *
     * @return array
     */
    public function getTokenMappings()
    {
        // Create Mappings Array:
        
        $mappings = array();
        
        // Define Mappings Array:
        
        if (is_array($this->owner->config()->token_mappings)) {
            
            foreach ($this->owner->config()->token_mappings as $name => $spec) {
                
                if (!is_array($spec)) {
                    $spec = array('property' => $spec);
                }
                
                $mappings[$name] = $spec;
                
            }
            
        }
        
        // Answer Mappings Array:
        
        return $mappings;
    }
    
    /**
     * Renders an HTML legend for the available token mappings.
     *
     * @param string $title
     * @return string
     */
    public function getTokenMappingLegend($title = null)
    {
        // Define Title:
        
        if (is_null($title)) {
            $title = _t('TokenMappingExtension.TOKENS', 'Tokens');
        }
        
        // Create Mappings List:
        
        $mappings = ArrayList::create();
        
        // Iterate Token Mappings:
        
        foreach ($this->owner->getTokenMappings() as $name => $spec) {
            
            $mappings->push(
                ArrayData::create(
                    array(
                        'Token' => strtoupper($name),
                        'Property' => (isset($spec['property']) ? $spec['property'] : ''),
                        'Description' => (isset($spec['description']) ? $spec['description'] : '')
                    )
                )
            );
            
        }
        
        // Render Legend:
        
        return ArrayData::create(
            array(
                'Title' => $title,
                'Mappings' => $mappings
            )
        )->renderWith('TokenMapping_Legend');
    }
    
    /**
     * Answers a literal field containing the token mapping legend.
     *
     * @param string $name
     * @param string $title
     * @return LiteralField
     */
    public function getTokenMappingLegendField($name = 'TokenMappingLegend', $title = null)
    {
        return LiteralField::create($name, $this->owner->getTokenMappingLegend($title));
    }
    
    /**
     * Replaces tokens found within the given text with their mapped value.
     *
     * @throws Exception
     *
     * @param string $text
     * @param array $tokens
     * @return string
     */
    public function replaceTokens($text, $tokens = array())
    {
        // Obtain Token Mappings:
        
        $mappings = $this->owner->getTokenMappings();
        
        // Iterate Tokens Argument:
        
        if (is_array($tokens)) {
            
            foreach ($tokens as $name => $value) {
                $text = str_ireplace("{{$name}}", $value, $text);
            }
            
        }
        
        // Iterate Token Mappings:
        
        foreach ($mappings as $name => $spec) {
            
            if (!isset($spec['custom']) || $spec['custom'] == false) {
                
                // Check Property Defined:
                
                if (!isset($spec['property'])) {
                    throw new Exception(sprintf('Property is undefined for token mapping "%s"', $name));
                }
                
                // Obtain Property Name:
                
                $property = $spec['property'];
                
                // Perform Token Replacement:
                
                $text = str_ireplace("{{$name}}", $this->getPropertyValue($property), $text);
                
            }
            
        }
        
        // Answer Processed Text:
        
        return $text;
    }
    
    /**
     * Answers the value of the property with the given name from the extended object (either via method or field).
     *
     * @param string $name
     * @return mixed
     */
    protected function getPropertyValue($name)
    {
        if (strpos($name, '.') !== false) {
            return $this->owner->relField($name);
        }
        
        if ($this->owner->hasMethod($name)) {
            return $this->owner->$name();
        }
        
        return $this->owner->$name;
    }
}
