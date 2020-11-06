<?php
/*
Plugin Name: A2z trade product management
Plugin URI:
Description:
Author: Sagar Dash
Version: 1.6.0
Author URI: sagardash.com
Requires at least: 4.5
Tested up to: 5.3
License: GPLv2 or later
Text Domain: wpet
 */

if (!defined('A2ZTRADE_HACK_MSG')) {
    define('A2ZTRADE_HACK_MSG', __('Sorry cowboy! This is not your place', 'WPET'));
}

/**
 * Protect direct access
 */
if (!defined('ABSPATH')) {
    die(A2ZTRADE_HACK_MSG);
}

/**
 * Defining constants
 */
$prefix = "bdtap";
if (!defined('A2ZTRADE_PREFIX')) {
    define('A2ZTRADE_PREFIX', $prefix);
}

if (!defined('A2ZTRADE_VERSION')) {
    define('A2ZTRADE_VERSION', '1.0.0');
}

if (!defined('A2ZTRADE_MENU_POSITION')) {
    define('A2ZTRADE_MENU_POSITION', 5);
}

if (!defined('A2ZTRADE_PLUGIN_DIR')) {
    define('A2ZTRADE_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('A2ZTRADE_PLUGIN_URI')) {
    define('A2ZTRADE_PLUGIN_URI', plugins_url('', __FILE__));
}

if (!defined('A2ZTRADE_FILES_DIR')) {
    define('A2ZTRADE_FILES_DIR', A2ZTRADE_PLUGIN_DIR . 'assets');
}

if (!defined('A2ZTRADE_FILES_URI')) {
    define('A2ZTRADE_FILES_URI', A2ZTRADE_PLUGIN_URI . '/assets');
}

// require_once( ABSPATH . '/wp-admin/includes/plugin.php');
// require_once( ABSPATH . '/wp-admin/includes/media.php');
// require_once( ABSPATH . '/wp-admin/includes/file.php');
// require_once( ABSPATH . '/wp-admin/includes/image.php');
// autoloading class
spl_autoload_register(function ($class) {
    // namespace prefix
    $prefix = 'A2ZTRADE\\';

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

// media_sideload_image('https://www.dfshop.com/wsshop/Dipius/pict/0007204812.jpg', 10);

add_action('init', 'do_stuff');
function do_stuff()
{

    // ...
}

// function upload_image($url, $post_id)
// {
//     $image_url = $url;

//     $upload_dir = wp_upload_dir();

//     $image_data = file_get_contents($image_url);

//     $filename = basename($image_url);

//     if (wp_mkdir_p($upload_dir['path'])) {
//         $file = $upload_dir['path'] . '/' . $filename;
//     } else {
//         $file = $upload_dir['basedir'] . '/' . $filename;
//     }

//     file_put_contents($file, $image_data);

//     $wp_filetype = wp_check_filetype($filename, null);

//     $attachment = array(
//         'post_mime_type' => $wp_filetype['type'],
//         'post_title' => sanitize_file_name($filename),
//         'post_content' => '',
//         'post_status' => 'inherit',
//     );

//     $attach_id = wp_insert_attachment($attachment, $file);
//     require_once ABSPATH . 'wp-admin/includes/image.php';
//     $attach_data = wp_generate_attachment_metadata($attach_id, $file);
//     wp_update_attachment_metadata($attach_id, $attach_data);
// }

// upload_image('https://www.dfshop.com/wsshop/Dipius/pict/0007204812.jpg', 10);
// instatiate class
new A2ZTRADE\A2ZRestApi();
// new A2ZTRADE\BDTimesScripts();
