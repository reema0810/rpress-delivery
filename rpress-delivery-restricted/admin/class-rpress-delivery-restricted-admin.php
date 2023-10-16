<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://magnigeeks.com
 * @since      1.0.0
 *
 * @package    Rpress_Delivery_Restricted
 * @subpackage Rpress_Delivery_Restricted/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rpress_Delivery_Restricted
 * @subpackage Rpress_Delivery_Restricted/admin
 * @author     Magnigeeks <reema.pattnaik@magnigeeks.com>
 */
class Rpress_Delivery_Restricted_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		//for restropress setting under general setting one custom setting
		add_filter('rpress_settings_sections_general', array($this, 'rpress_delivery_restricted_settings_section'));
		//under custom general setting their other custom setting fields
		add_filter('rpress_settings_general', array($this, 'Rpress_Delivery_Restricted_Admin'), 10, 1);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rpress_Delivery_Restricted_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rpress_Delivery_Restricted_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rpress-delivery-restricted-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rpress_Delivery_Restricted_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rpress_Delivery_Restricted_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/rpress-delivery-restricted-admin.js', array('jquery'), $this->version, false);
	}
	//for restropress setting under general setting one custom setting function
	public function rpress_delivery_restricted_settings_section($section)
	{
		$section['address'] = __('Rpress Delivery Restricted', 'rp-rpress-delivery-restricted');
		return $section;
	}
	//under custom general setting their other custom setting fields functions
	public function rpress_Delivery_Restricted_Admin($general_settings)
	{
		//for api key setting levels
		$general_settings['address']['address_heading'] = array(
			'id'            => 'api_key_setting',
			'name'          => '<h3>' . __(' API Key Setting', 'rp-rpress-delivery-restricted ') . '</h3>',
			'desc'          => '',
			'type'          => 'header',
			'tooltip_title' => __(' API Key Setting', 'rp-rpress-delivery-restricted '),
		);
		//for auto restricted enable checkbox
		$general_settings['address']['auto_restriced_enable'] = array(
			'id'            => 'auto_restriced_enable',
			'name'          =>  __('Auto restricted Enable', 'rp-rpress-delivery-restricted'),
			'desc'          => __('Enable this if you want to add auto restriced enable location', 'rp-rpress-delivery-restricted'),
			'type'          => 'checkbox',
			'tooltip_title' => __('Auto restricted Enable', 'rp-rpress-delivery-restricted '),
		);

		//for error message 
		$general_settings['address']['error_message'] = array(
			'id'            => 'error_message',
			'name'          => __('Error message for unavailable zip/postal code/location', 'rp-rpress-delivery-restricted'),
			'desc'          => __('Set the error message for unavailable location', 'rp-rpress-delivery-restricted'),
			'type'          => 'text',
			'placeholder'   => __('Error message', 'rp-rpress-delivery-restricted'), // Add this line for the placeholder
		);
		//radio button for toggles between zip code and distance
		$general_settings['address']['select_delivery_location_method'] = array(
			'id'    => 'select_delivery_location_method',
			'name'  => __('Select Delivery Location Method', 'rp-rpress-delivery-restricted'),
			'type'  => 'radio',
			'options' => array(
				'option1' => __('ZIP based', 'rp-rpress-delivery-restricted'), // Add your radio button options here
				'option2' => __('LOCATION based', 'rp-rpress-delivery-restricted'),
			),
		);

		//for input restricted zip code
		$general_settings['address']['restricted_zip_code'] = array(
			'id'            => 'restricted_zip_code',
			'name'          => __('restricted zip code', 'rp-rpress-delivery-restricted'),
			'desc'          => __('Set your required restricted zip/postal codes with comma separated.', 'rp-rpress-delivery-restricted'),
			'type'          => 'text',
			'placeholder'   => __('e.g.56001,56002', 'rp-rpress-delivery-restricted'), // Add this line for the placeholder
		);
		//for google map api key
		$general_settings['address']['google_map_api_key'] = array(
			'id'    => 'google_map_api_key',
			'name'  => __('Google map api key', 'rp-rpress-delivery-restricted'),
			'desc'  => __('Add your Google map API for restriced location', 'rp-rpress-delivery-restricted'),
			'type'  => 'text',
		);
		//for store latitude
		$general_settings['address']['store_latitude'] = array(
			'id'    => 'store_latitude',
			'name'  => __('store Latitude', 'rp-rpress-delivery-restricted'),
			'desc'  => __('Enter the latitude for the store location.', 'rp-rpress-delivery-restricted'),
			'placeholder'   => __('e.g., 41.40338', 'rp-rpress-delivery-restricted'),
			'type'  => 'text',
		);
		//for store longitude
		$general_settings['address']['store_longitude'] = array(
			'id'    => 'store_longitude',
			'name'  => __('Store Longitude', 'rp-rpress-delivery-restricted'),
			'desc'  => __('Enter the longitude for the store location.', 'rp-rpress-delivery-restricted'),
			'placeholder'   => __('e.g., 2.17403', 'rp-rpress-delivery-restricted'),
			'type'  => 'text',
		);

		//make a drop down button for select options
		$general_settings['address']['distance_unit_select'] = array(
			'id'    => 'distance unit select',
			'name'  => __('Distance Unit', 'rp-rpress-delivery-restricted'),
			'desc'  => __('Select the distance unit for the store location.', 'rp-rpress-delivery-restricted'),
			'type'  => 'select',
			'options' => array(
				'km'    => __('Kilometers', 'rp-rpress-delivery-restricted'),
				'miles' => __('Miles', 'rp-rpress-delivery-restricted')
			),
		);
		//for distance unit
		$general_settings['address']['distance_unit_text'] = array(
			'id'            => 'distance_unit_text',
			'name'          => __('restricted distance', 'rp-rpress-delivery-restricted'),
			'desc'          => __('Enter the restriced area you want to deliver.', 'rp-rpress-delivery-restricted'),
			'type'          => 'number',

		);

		return $general_settings;
	}
}
