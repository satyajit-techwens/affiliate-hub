<?php

	if ( ! defined( 'ABSPATH' ) ) {
    	exit; // Exit if accessed directly
	}



	class AFFH_DASHBOARD {
	    public function __construct() {
	        add_filter("affh_page",array($this,"affh_page"));
	    }

	   	public function affh_page($file) {
	   			require_once AFFH_TEMPLATE_PATH.$file;
	   	}
	}

	new AFFH_DASHBOARD;