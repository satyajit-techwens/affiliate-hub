<?php

	if ( ! defined( 'ABSPATH' ) ) {
    	exit; // Exit if accessed directly
	}

	use AffiliateHub\Settings\AFFH_SETTINGS;

	class AFFH_IMPACT {
	    public function __construct() {
	    	add_action("affh_fetch_catalogs_impact" , array($this , "affh_fetch_catalogs_impact"));
	    	do_action("affh_fetch_catalogs_impact");
	    }

	    public function affh_fetch_catalogs_impact($catalogs) {

	    	$imapct_settings = new AFFH_SETTINGS();
			$imapct_data = $imapct_settings->affh_get_credentails("impact");

			// Step 1: Access the affiliate_data property
			$affiliate_data = $imapct_data[0]->affiliate_data;

			// Step 2: Unserialize the data
			$unserialized_data = maybe_unserialize($affiliate_data);

			// Step 3: Extract account_id and auth_token
			$account_id = $unserialized_data['account_id'] ?? null; // Use null coalescing operator to avoid undefined index notice
			$auth_token = $unserialized_data['auth_token'] ?? null;

		    // Encode the credentials properly
		    $credentials = base64_encode($account_id . ':' . $auth_token);
		    $endpoint = 'https://api.impact.com/Mediapartners/' . $account_id . '/Catalogs?pagesize=500';
		    
		    // Call the API
		    $catalogs = $this->apiCall($endpoint, $credentials);

		    if (!$catalogs['error']) {
			    $dropdown_html = "<form><div class='form-group'><select class='form-control' id='catalog' name='catalog_id'>"
			        . "   <option value=''>-- Select Catalog--</option>";
			    foreach ($catalogs['data']['Catalogs'] as $catalog) {
			        $dropdown_html .= "<option  value='" . $catalog['Id'] . "' data-name='".$catalog['Name']."' data-affiliate='impact' data-itemsTotal = '".$catalog['NumberOfItems']."'> "  . $catalog['Name'] . " (" . $catalog['AdvertiserName'] . ")" . "</option>";
			    }
			    $dropdown_html .= "</select></div>";
			    
			} else {
			    $dropdown_html = "Error fetching catalogs.";
			}
			echo $dropdown_html;
		}

		protected function apiCall($endpoint, $credentials){

					    try {

					        $data = [];

					        $curl = curl_init();

					        curl_setopt_array(

					            $curl,

					            array(

					                CURLOPT_URL => $endpoint,

					                CURLOPT_RETURNTRANSFER => true,

					                CURLOPT_HTTPHEADER => array(

					                    'Accept: application/json',

					                    // Set Authorization header with properly encoded credentials

					                    'Authorization: Basic ' . $credentials

					                )

					            )

					        );



					        // Execute the cURL request

					        $response = curl_exec($curl);



					        if ($response === false) {

					            $error_message = curl_error($curl);

					            // Handle error

					            error_log('cURL error: ' . $error_message);

					            $data['error'] = true;

					            $data['message'] = $error_message;

					            $data['data'] = [];
					        } else {

					            $res = json_decode($response, true);

					            if ($res === null) {

					                $data['error'] = true;

					                $data['message'] = 'Error decoding JSON response';

					                $data['data'] = [];
					            } else {

					                $data['error'] = false;

					                $data['message'] = "Successfully got response";

					                $data['data'] = $res;
					            }
					        }



					        // Close cURL session

					        curl_close($curl);

					        return $data;
					    } catch (Exception $exception) {

					        error_log("An error occurred in apiCall: " . $exception->getMessage());

					        return [

					            'error' => true,

					            'message' => 'An error occurred',

					            'data' => []

					        ];
					    }
					}

	}

	new AFFH_IMPACT;
