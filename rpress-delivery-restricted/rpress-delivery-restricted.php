<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://magnigeeks.com
 * @since             1.0.0
 * @package           Rpress_Delivery_Restricted
 *
 * @wordpress-plugin
 * Plugin Name:       RestroPress-Delivery Area Restrictions
 * Plugin URI:        https://https://magnigeeks.com
 * Description:       This plugin helps to make restrict of delivery location  for Restropress.
 * Version:           1.0.0
 * Author:            Magnigeeks
 * Author URI:        https://https://magnigeeks.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rpress-delivery-restricted
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}
if (!defined('RP_ADDRESS_RESTRICT_FILE')) {
	define('RP_ADDRESS_RESTRICT_FILE', __FILE__);
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('RPRESS_DELIVERY_RESTRICTED_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rpress-delivery-restricted-activator.php
 */
function activate_rpress_delivery_restricted()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-rpress-delivery-restricted-activator.php';
	Rpress_Delivery_Restricted_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rpress-delivery-restricted-deactivator.php
 */
function deactivate_rpress_delivery_restricted()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-rpress-delivery-restricted-deactivator.php';
	Rpress_Delivery_Restricted_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_rpress_delivery_restricted');
register_deactivation_hook(__FILE__, 'deactivate_rpress_delivery_restricted');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-rpress-delivery-restricted.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rpress_delivery_restricted()
{

	$plugin = new Rpress_Delivery_Restricted();
	$plugin->run();
}
run_rpress_delivery_restricted();
