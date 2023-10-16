<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://magnigeeks.com
 * @since      1.0.0
 *
 * @package    Rpress_Delivery_Restricted
 * @subpackage Rpress_Delivery_Restricted/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rpress_Delivery_Restricted
 * @subpackage Rpress_Delivery_Restricted/includes
 * @author     Magnigeeks <reema.pattnaik@magnigeeks.com>
 */
class Rpress_Delivery_Restricted_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rpress-delivery-restricted',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
