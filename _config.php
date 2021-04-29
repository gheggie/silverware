<?php

/**
 * SilverWare module configuration file
 *
 * @author Colin Tucker <colin@praxis.net.au>
 * @package silverware
 */

// Define Module Directory:

if (!defined('SILVERWARE_DIR')) {
    define('SILVERWARE_DIR', basename(__DIR__));
}

// Block Moderno Admin Font Awesome CSS (to avoid potential problems):

if (defined('MODERNO_ADMIN_DIR')) {
    Requirements::block(MODERNO_ADMIN_DIR . '/css/font-awesome.min.css');
}

// Extend Data Objects with Identifier Extension:

SilverWareIdentifierExtension::extend();
