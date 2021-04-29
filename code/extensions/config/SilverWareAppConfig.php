<?php

/**
 * An extension of the SilverWare config extension class to define app defaults from config.
 */
class SilverWareAppConfig extends SilverWareConfigExtension
{
    /**
     * @config
     * @var boolean
     */
    private static $force_defaults = true;
    
    /**
     * Creates any required default records (if they do not already exist).
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
    }
}
