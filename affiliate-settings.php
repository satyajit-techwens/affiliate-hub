<?php

	if ( ! defined( 'ABSPATH' ) ) {
    	exit; // Exit if accessed directly
	}

	class AFFH_SETTINGS{
	    public function __construct(){
	        add_action("affiliate_settings",array($this,"affh_admin_menu"));
	    }

	    public function affh_admin_menu() {
	        add_action('admin_menu', array($this, 'affh_menus'));
	    }
	    public function affh_menus(){
	        // Add a top-level menu
		    add_menu_page(
		        'Affiliate',           // Page title
		        'Affiliate',           // Menu title
		        'manage_options',      // Capability
		        'affiliate-hub',    // Menu slug
		        array($this,'affiliate_dashboard'), // Function to display the page
		        'dashicons-admin-generic', // Icon (optional)
		        6                      // Position (optional)
		    );

		    // Add a submenu under the top-level menu
		    // add_submenu_page(
		    //     'my-custom-plugin',     // Parent slug
		    //     'Settings',             // Page title
		    //     'Settings',             // Menu title
		    //     'manage_options',       // Capability
		    //     'my-custom-plugin-settings', // Submenu slug
		    //     'my_custom_plugin_settings_page' // Function to display the submenu page
		    // );
	    }
	    public function affiliate_dashboard(){
    			require_once AFFH_TEMPLATES_PATH.'affiliate-dashboard.php';
    			apply_filters("affh_page","home-page.php");
	    }
	}

new AFFH_SETTINGS;