<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://magnigeeks.com
 * @since      1.0.0
 *
 * @package    Rpress_Delivery_Restricted
 * @subpackage Rpress_Delivery_Restricted/public
 */

class Rpress_Delivery_Restricted_Public
{
	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version)
	{
		add_action('rpress_before_service_time', array($this, 'restricted_distance_delivery'));
		add_filter('rpress_check_service_slot', array($this, 'handle_post_data'));
		add_filter('rpress_customer_delivery_address', array($this, 'rpress_fill_user_delivery_address'));
		//store plugin name and version
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	//enqueue css style
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rpress-delivery-restricted-public.css', array(), $this->version, 'all');
	}
	public function enqueue_scripts()
	{
		//get plugin setting
		$data = get_option('rpress_settings', array());
		$restriction_type = $data['select_delivery_location_method'];
		$google_map_api_key = $data['google_map_api_key'];

		//determine the delivery restricted method
		$delivery_restricted_method = ($restriction_type === "option1") ? 'zip_based' : 'location_based';
		if ($delivery_restricted_method === 'location_based') {

			//enqueue google map api key
			wp_enqueue_script('gmap_api_script',  'https://maps.googleapis.com/maps/api/js?key=' . $google_map_api_key . '&libraries=places', array(), false);

			//define javascript parameters
			$params = array(
				'delivery_restricted_method' => $delivery_restricted_method,
				'google_map_api_key' => $google_map_api_key,
			);
		} else {
			$params = array(
				'delivery_restricted_method' => $delivery_restricted_method,
			);
		}
		//enqueue the main JavaScript file and localize it with parameters
		wp_enqueue_script('rp-delivery-restricted', plugin_dir_url(__FILE__) . 'js/rpress-delivery-restricted-public.js', array('jquery'), $this->version, false);
		wp_localize_script('rp-delivery-restricted', 'DeliveryrestrictedVars', $params);
	}
	public function restricted_distance_delivery($service_type)
	{
		//get plugin setting
		$data = get_option('rpress_settings', array());
		$restriction_type = $data['select_delivery_location_method'];
		//determine the placeholder text
		$placeholder = ($restriction_type === "option1") ? 'Enter your zip' : 'Enter your location';
		//generate html input field	
?>
		<div class="rpress-delivery-zone-wrapper">
			<input type="text" placeholder="<?php echo $placeholder; ?>" id="rp_delivery_input" name="rp_delivery_input" class="rpress-delivery rpress-allowed-delivery-hrs rpress-hrs rp-form-control" value="">
		</div>
<?php
	}
	public function handle_post_data($postdata)
	{
		//get plugin setting
		$data = get_option('rpress_settings', array());
		$is_enable = $data['auto_restriced_enable']; //for auto restricted enable 
		if ($is_enable == 1) {

			$restriction_type = $data['select_delivery_location_method'];
			$restriction_msg = !empty($data['error_message']) ? $data['error_message'] : 'outside deliver zone';
			$delivery_restricted_method = ($restriction_type === "option1") ? 'zip_based' : 'location_based';
			//apply a filter before processing
			$response = apply_filters('rp_delivery_restriction_multi_branch_before', array(), $postdata);
			//Zip-Based Delivery Method
			if ($delivery_restricted_method === 'zip_based') {
				//Validation for zip-based delivery restriction
				//Retrieve the user's entered zip code from $postdata
				$delivery_location  = isset($postdata['delivery_zip']) ? (trim($postdata['delivery_zip'])) : '';
				//Check if the entered zip code is empty
				if (empty($delivery_location)) {
					//Set an error message in the response
					$response['status'] = 'error';
					$response['msg']  = __('Please enter your zip', 'rpress-delivery-restricted');
				} else {
					// If the zip code is not empty
					//Retrieve the list of restricted zip codes from $data
					$delivery_zip_codes = $data['restricted_zip_code'];
					//Format the restricted zip codes by removing spaces and splitting them into an array
					$delivery_zip_codes = str_replace(' ', '', $delivery_zip_codes);
					$delivery_zip_codes_arr = explode(",", $delivery_zip_codes);
					//Check if the user's entered zip code exists in the list of restricted zip codes
					$user_zip_code = $postdata['delivery_zip'];
					if (!in_array($user_zip_code, $delivery_zip_codes_arr)) {
						//If the user's zip code is not in the list, set an error message in the response
						$response['status'] = 'error';
						$response['msg']  = __($restriction_msg, 'rpress-delivery-restricted');
					} else {
						//If the user's zip code is valid, set a user cookie with the zip code
						setcookie('user_zip_code', $user_zip_code, time() + (60 * 60), "/");
					}
				}
				//Location-Based Delivery Method
			} elseif ($delivery_restricted_method === 'location_based') {
				//Validation for location-based delivery restriction
				//Retrieve the user's entered location from $postdata
				$delivery_location  = isset($postdata['delivery_location']) ? (trim($postdata['delivery_location'])) : '';
				//Check if the entered location is empty
				if (empty($delivery_location)) {
					//Set an error message in the response
					$response['status'] = 'error';
					$response['msg']  = __('Please enter your location', 'rpress-delivery-restricted');
				} else {
					// If the location is not empty
					//Retrieve location-related data from $data
					$store_lat = $data['store_latitude'];
					$store_lng = $data['store_longitude'];
					$store_dis_unit = $data['distance_unit_select'];
					$res_distance = $data['distance_unit_text'];
					$user_lat = $postdata['user_lat'];
					$user_lng = $postdata['user_lng'];
					$street_address = $postdata['street_address'];
					$city = $postdata['city'];
					$postcode = $postdata['postcode'];
					//Calculate the adjusted restriction distance if needed
					$res_distance = ($store_dis_unit === "miles") ? $res_distance * 1.60934 : $res_distance;
					//Check if the user's location is within the allowed distance from the store
					$isWithinDistance = $this->is_within_distance($store_lat, $store_lng, $user_lat, $user_lng, $res_distance);
					if (!$isWithinDistance) {
						//If the user's location is outside the allowed distance, set an error message in the response
						$response['status'] = 'error';
						$response['msg']  = __($restriction_msg, 'rpress-delivery-restricted');
					} else {
						//If the user's location is valid, set user cookies with location-related data
						setcookie('user_lat', $user_lat, time() + (60 * 60), "/");
						setcookie('user_lng', $user_lng, time() + (60 * 60), "/");
						setcookie('street_address', $street_address, time() + (60 * 60), "/");
						setcookie('city', $city, time() + (60 * 60), "/");
						setcookie('postcode', $postcode, time() + (60 * 60), "/");
					}
				}
			}
			// Apply filter after processing
			return apply_filters('rp_delivery_restriction_multi_branch_after', $response, $postdata);
		}
	}
	public function is_within_distance($storeLat, $storeLng, $otherLat, $otherLng, $radiusInKilometers)
	{
		// Earth's radius in kilometers
		$earthRadius = 6371;
		// Calculate the differences in latitude and longitude
		$latDiff = $otherLat - $storeLat;
		$lngDiff = $otherLng - $storeLng;
		// Calculate the distance using the Haversine formula
		$a = sin(deg2rad($latDiff) / 2) * sin(deg2rad($latDiff) / 2) + cos(deg2rad($storeLat)) * cos(deg2rad($otherLat)) * sin(deg2rad($lngDiff) / 2) * sin(deg2rad($lngDiff) / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$distance = $earthRadius * $c;
		// Check if the distance is less than or equal to the specified radius
		return $distance <= $radiusInKilometers;
	}
	//Method to fill user delivery address based on the selected delivery method
	public function rpress_fill_user_delivery_address($delivery_address)
	{
		//Get plugin settings
		$data = get_option('rpress_settings', array());
		//Check if auto-restriction is enabled
		$is_enable = $data['auto_restriced_enable'];
		//Determine the delivery method based on settings
		$restriction_type = $data['select_delivery_location_method'];
		$delivery_restricted_method = ($restriction_type === "option1") ? 'zip_based' : 'location_based';
		//If auto-restriction is enabled
		if ($is_enable == 1) {
			//Retrieve user data from cookies
			$postcode       = isset($_COOKIE['postcode']) ? $_COOKIE['postcode'] : '';
			$street_address = isset($_COOKIE['street_address']) ? $_COOKIE['street_address'] : '';
			$city           = isset($_COOKIE['city']) ? $_COOKIE['city'] : '';
			$user_zip_code  = isset($_COOKIE['user_zip_code']) ? $_COOKIE['user_zip_code'] : '';
			//Check if the delivery method is 'location_based'
			if ($delivery_restricted_method == 'location_based') {
				//If delivery_address is an array, update its components
				if (is_array($delivery_address)) {
					$delivery_address['postcode'] = $postcode;
					$delivery_address['city']     = $city;
					$delivery_address['address']  = $street_address;
				}
			} else {
				//If the delivery method is 'zip_based', update the postcode
				if (is_array($delivery_address))

					$delivery_address['postcode'] = $user_zip_code;
			}
			//Return the modified delivery_address
			return $delivery_address;
		}
		//Return the original delivery_address if auto-restriction is not enabled
		return $delivery_address;
	}
}
