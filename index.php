<?php


/**
 * A simple PHP MVC skeleton
 *
 * @package mnu
 * @author Panique
 * @link http://www.mnu.net
 * @link https://github.com/panique/mnu/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// load the (optional) Composer auto-loader
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
}

// load application config (error reporting etc.)
require 'application/config/config.php';

// load application class
require 'application/libs/application.php';
require 'application/libs/controller.php';

function endsWith(string $haystack, string $needle)
{
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : true;
}

// start the application
$app = new Application();
