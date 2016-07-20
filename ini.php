<?php

/**
 * ini.php
 * Library initialization to include required files
 * 
 * @category   API
 * @package    paqure-php-api
 * @author     Roderic Linguri <linguri@digices.com>
 * @copyright  2016 Digices LLC
 * @license    https://github.com/digices-llc/paqure-lamp-backend/blob/master/LICENSE
 * @version    0.0.1
 * @link       https://github.com/digices-llc/paqure-php-api.git
 */

// alias the php directory separator constant
if (!defined ('DS')) {
    define ('DS',DIRECTORY_SEPARATOR);
}

// central link to the paqure-lamp-backend library
require_once (dirname(__DIR__).DS.'paqure-lamp-backend'.DS.'ini.php');

