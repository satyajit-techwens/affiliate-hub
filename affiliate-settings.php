<?php

	if ( ! defined( 'ABSPATH' ) ) {
    	exit; // Exit if accessed directly
	}
// Report all PHP errors
error_reporting(E_ALL);

// Report all PHP errors
error_reporting(-1);
	class AFFH_SETTINGS{
	    public function __construct(){

	        add_action("affiliate_settings",array($this,"affh_admin_menu"));
	        add_action("wp_ajax_affh_settings_by_provider",array($this,"affh_settings_by_provider"));
	        add_action("wp_ajax_norpiv_affh_settings_by_provider",array($this,"affh_settings_by_provider"));
	        add_filter("affh_provider_settings_form",array($this,"affh_provider_settings_form"));
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
	    }
	    public function affiliate_dashboard(){
    			require_once AFFH_TEMPLATES_PATH.'affiliate-dashboard.php';
    			apply_filters("affh_page","home-page.php");
	    }

	    public function affh_settings_by_provider(){
	    		$provider = $_POST['data'];
	    		$err_msg = (!check_ajax_referer('update_settings_validation', 'nonce', false))?'Invalid nonce. Please refresh the page and try again.':'nonce verified';
	    	    $err_msg = empty($err_msg)?empty($provider)?'Select an option.':'':'';

	    	    if($err_msg){
	    	    	wp_send_json_error(array(
		            	'data' => $err_msg
		        	));
	    	    }else{
	    	    	$provider = $provider.".php";
	    	    	$form_template = apply_filters("affh_provider_settings_form",$provider);
	    	    	wp_send_json_success(array(
		            	'template_data' => $form_template
		        	));
	    	    }
				

				wp_die();
	    }

	    public function affh_provider_settings_form($provider){
	    	        ob_start();
	   					require_once AFFH_SETTINGS_PATH.$provider;
	   				return ob_get_clean(); // Returns the HTML content of the included file
	    }
	}

new AFFH_SETTINGS;