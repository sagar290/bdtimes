<?php
/*
Plugin Name: BD TIMES 
Plugin URI:
Description:
Author: Sagar
Version: 1.6.0
Author URI: sagardash.com
Requires at least: 4.5
Tested up to: 5.3
License: GPLv2 or later
Text Domain: wpet
 */

if (!defined('BDTAP_HACK_MSG')) {
    define('BDTAP_HACK_MSG', __('Sorry cowboy! This is not your place', 'WPET'));
}

/**
 * Protect direct access
 */
if (!defined('ABSPATH')) {
    die(BDTAP_HACK_MSG);
}

/**
 * Defining constants
 */
$prefix = "bdtap";
if (!defined('BDTAP_PREFIX')) {
    define('BDTAP_PREFIX', $prefix);
}

if (!defined('BDTAP_VERSION')) {
    define('BDTAP_VERSION', '1.0.0');
}

if (!defined('BDTAP_MENU_POSITION')) {
    define('BDTAP_MENU_POSITION', 5);
}

if (!defined('BDTAP_PLUGIN_DIR')) {
    define('BDTAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('BDTAP_PLUGIN_URI')) {
    define('BDTAP_PLUGIN_URI', plugins_url('', __FILE__));
}

if (!defined('BDTAP_FILES_DIR')) {
    define('BDTAP_FILES_DIR', BDTAP_PLUGIN_DIR . 'assets');
}

if (!defined('BDTAP_FILES_URI')) {
    define('BDTAP_FILES_URI', BDTAP_PLUGIN_URI . '/assets');
}

// autoloading class
spl_autoload_register(function ($class) {
    // namespace prefix
    $prefix = 'BDTAP\\';

    // check if this is a class from our project
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    // nope, it isn't

    // get the relative class name
    // remove the namespace name
    $className = substr($class, $len);

    // base directory of classes
    $baseDir = __DIR__ . '/classes/';
    // relative including
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {

        if (!class_exists($className)) {
            require $file;
        }
    }
});

// instatiate class
new BDTAP\BDTimesRestApi();
new BDTAP\BDTimesScripts();
