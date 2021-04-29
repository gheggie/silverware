<?php

/**
 * An extension of the object class for the core SilverWare object.
 */
class SilverWare extends Object
{
    /**
     * @config
     * @var array
     */
    private static $fixtures = array();
    
    /**
     * @config
     * @var array
     */
    private static $default_fixtures = array();
    
    /**
     * @config
     * @var array
     */
    private static $blueprints = array();
    
    /**
     * @config
     * @var string
     */
    private static $default_blueprint = "SilverWareBlueprint";
    
    /**
     * @var array
     */
    private static $loaded = array();
    
    /**
     * Loads the object records from the fixtures defined by configuration.
     *
     * @param string $name
     * @param boolean $force
     * @return boolean
     * 
     * @throws Exception
     */
    public static function load_fixtures($name = 'fixtures', $force = false)
    {
        // Answer True (if loaded and not forced):
        
        if (isset(self::$loaded[$name]) && !$force) {
            return true;
        }
        
        // Flag as Loaded:
        
        self::$loaded[$name] = true;
        
        // Create Fixture Factory:
        
        $factory = Injector::inst()->create('SilverWareFixtureFactory');
        
        // Obtain Fixture List:
        
        $fixtures = self::config()->get($name);
        
        if (!is_array($fixtures)) {
            
            throw new InvalidArgumentException(
                sprintf(
                    'SilverWare::load_fixtures() cannot find fixture list: "%s"',
                    $name
                )
            );
            
        }
        
        // Load Fixture File YAML:
        
        foreach ($fixtures as $file)
        {
            try {
                
                // Show Loading Message:
                
                DB::alteration_message(
                    sprintf(
                        'Loading fixture file "%s"',
                        $file
                    ),
                    'notice'
                );
                
                // Attempt Fixture Loading:
                
                YamlFixture::create($file)->writeInto($factory);
                
            } catch (InvalidArgumentException $e) {
                
                // Invalid Argument Exception:
                
                $message = $e->getMessage();
                
                if (strpos($message, 'YamlFixture::') === 0 && strpos($message, 'not found') !== false) {
                    $message = "cannot load fixture file: '{$file}'";
                }
                
                DB::alteration_message(
                    sprintf(
                        'SilverWare::load_fixtures() invalid argument exception: "%s"',
                        $message
                    ),
                    'error'
                );
                
            } catch (Exception $e) {
                
                // Other Exceptions:
                
                DB::alteration_message(
                    sprintf(
                        'SilverWare::load_fixtures() exception: "%s"',
                        $e->getMessage()
                    ),
                    'error'
                );
                
            }
        }
        
        // Answer Success:
        
        return true;
    }
}
