<?php

	if ( ! defined( 'ABSPATH' ) ) {
    	exit; // Exit if accessed directly
	}
// // Report all PHP errors
// error_reporting(E_ALL);

// // Report all PHP errors
// error_reporting(-1);
	class AFFH_SETTINGS{
	    public function __construct(){

	        add_action("affiliate_settings",array($this,"affh_admin_menu"));
	        add_action("wp_ajax_affh_settings_by_provider",array($this,"affh_settings_by_provider"));
	        add_action("wp_ajax_norpiv_affh_settings_by_provider",array($this,"affh_settings_by_provider"));
	        add_filter("affh_provider_settings_form",array($this,"affh_provider_settings_form"));
	        add_action("wp_ajax_affh_save_settings",array($this,"affh_save_settings"));
	        add_action("wp_ajax_norpiv_affh_save_settings",array($this,"affh_save_settings"));

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
	    	 	if (isset($_POST['data'])) {
	    	 		$provider = sanitize_text_field(wp_unslash($_POST['data']));
			    }
			        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'my_nonce_action')) {
			    		$err_msg = (!check_ajax_referer('update_settings_validation', 'nonce', false))?'Invalid nonce. Please refresh the page and try again.':'nonce verified';
			    	    $err_msg = empty($err_msg)?empty($provider)?'Select an option.':'':'';
			    	}

	    	    if($err_msg){
	    	    	wp_send_json_error(array(
		            	'data' => $err_msg
		        	));
	    	    }else{
	    	    	$form_template = apply_filters("affh_provider_settings_form",$provider);
	    	    	wp_send_json_success(array(
		            	'template_data' => $form_template
		        	));
	    	    }
				

				wp_die();
	    }

	    public function affh_provider_settings_form($provider){
	    	        ob_start();
	    	        	$results = $this->affh_get_credentails($provider);
	    	        	$credentials = maybe_unserialize($results[0]->affiliate_data);
	    	    		$provider = $provider.".php";
	   					require_once AFFH_SETTINGS_PATH.$provider;
	   				return ob_get_clean(); // Returns the HTML content of the included file
	    }

	   	public function affh_save_settings(){
	   			global $wpdb;
	   			$table_name = $wpdb->prefix . 'affiliate';

	    	 	if (isset($_POST['data']) && is_array($_POST['data'])) {
	    	 		$settings_data = array_map('sanitize_text_field', wp_unslash($_POST['data']));

			    }
			        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'my_nonce_action')) {
			    		$err_msg = (!check_ajax_referer('update_settings_validation', 'nonce', false))?'Invalid nonce. Please refresh the page and try again.':'nonce verified';
			    	    $err_msg = empty($err_msg)?empty($formdata)?'Select an option.':'':'';
			    	}

				
			    	$current_user_id = get_current_user_id();
			    	$option = $settings_data['option'];
			    	unset($settings_data['option']);
			    	$insertdata = array(
			    		'affiliate_name' => $option,
			    		'user_id' => $current_user_id,
			    		'affiliate_data' => maybe_serialize($settings_data),
			    		'created_at' => current_time('mysql')
			    	);
					$inserted = $wpdb->insert($table_name, $insertdata);

					if ($inserted) {
					    $msg = 'Data successfully inserted.';
					    wp_send_json_success($msg);
					} else {
					    $msg = 'Failed to insert data.';
					    wp_send_json_error($msg);

					}

				wp_die();
	    }

	    public function affh_get_credentails($affiliate_name){
	    	global $wpdb;

			$table_name = $wpdb->prefix . 'affiliate'; // Replace with your actual table name

			// Prepare and execute the query
			$results = $wpdb->get_results(
			    $wpdb->prepare(
			        "SELECT affiliate_data FROM $table_name WHERE affiliate_name = %s",
			        $affiliate_name
			    )
			);

			if(!empty($results)){
				return $results;
			}
	    }

	    public function affh_nonce_verification(){

	    }
	}

new AFFH_SETTINGS;