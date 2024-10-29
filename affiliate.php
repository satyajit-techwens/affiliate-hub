<?php
/*
Plugin Name:  Affiliate Hub
Plugin URI:   https://techwens.com/
Description:  An affiliate plugin enhances your website by simplifying affiliate link management, tracking conversions, and optimizing commissions for 					better revenue generation.
Version:      1.0
Author:       Satyajit Ghosh
Author URI:   https://techwens.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  affh
*/



if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define plugin path
if ( ! defined( 'AFFH_PATH' ) ) {
    define( 'AFFH_PATH', plugin_dir_path( __FILE__ ) );
}

// Define plugin URL
if ( ! defined( 'AFFH_URI' ) ) {
    define( 'AFFH_URI', plugin_dir_url( __FILE__ ) );
}

//Define CSS path (uncomment and adjust the path as needed)
if ( ! defined( 'AFFH_CSS_PATH' ) ) {
    define( 'AFFH_CSS_URI', AFFH_URI . 'css/' );
}

// Define JS path (uncomment and adjust the path as needed)
if ( ! defined( 'AFFH_JS_PATH' ) ) {
    define( 'AFFH_JS_URI', AFFH_URI . 'js/' );
}

// Define JS path (uncomment and adjust the path as needed)
if ( ! defined( 'AFFH_INC' ) ) {
    define( 'AFFH_INC', AFFH_PATH . 'inc/' );
}

if ( ! defined( 'AFFH_TEMPLATES_PATH' ) ) {
    define( 'AFFH_TEMPLATES_PATH', AFFH_PATH . 'templates/' );
}

if ( ! defined( 'AFFH_TEMPLATE_PATH' ) ) {
    define( 'AFFH_TEMPLATE_PATH', AFFH_PATH . 'templates/template/' );
}

if ( ! defined( 'AFFH_IMAGES_PATH' ) ) {
    define( 'AFFH_IMAGES_PATH', AFFH_URI . 'images/' );
}



class AFFH_INIT{
    public function __construct(){
        register_activation_hook(__FILE__, array($this, 'affh_activate'));
        register_deactivation_hook(__FILE__, array($this, 'affh_deactivate'));
        add_action("admin_enqueue_scripts", array($this, 'affh_scripts'));

        require_once AFFH_PATH.'affiliate-callback.php';
    }

    public function affh_activate(){
    }

    public function affh_deactivate(){
    }

    public function affh_scripts(){
          // Enqueue CSS
    wp_enqueue_style(
        'affh-admin-style', // Handle
        AFFH_CSS_URI . 'admin-style.css', // Path to CSS file
        array(), // Dependencies
        '1.0' // Version
    );

    // Enqueue JS
    wp_enqueue_script(
        'affh-admin-script', // Handle
        AFFH_JS_URI . 'admin-script.js', // Path to JS file
        array('jquery'), // Dependencies (if any)
        '1.0', // Version
        true // Load in footer
    );
    }
}

new AFFH_INIT;