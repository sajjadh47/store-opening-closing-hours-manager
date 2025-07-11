<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Store_Opening_Closing_Hours_Manager
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Store Opening Closing Hours Manager
 * Plugin URI:        https://wordpress.org/plugins/store-opening-closing-hours-manager/
 * Description:       Setup your WooComerce store opening and closing hours to manage your business at ease!
 * Version:           2.0.1
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       store-opening-closing-hours-manager
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_VERSION', '2.0.1' );

/**
 * Define Plugin Folders Path
 */
define( 'STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-store-opening-closing-hours-manager-activator.php
 *
 * @since    2.0.0
 */
function on_activate_store_opening_closing_hours_manager() {
	require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'includes/class-store-opening-closing-hours-manager-activator.php';

	Store_Opening_Closing_Hours_Manager_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_store_opening_closing_hours_manager' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-store-opening-closing-hours-manager-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_store_opening_closing_hours_manager() {
	require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'includes/class-store-opening-closing-hours-manager-deactivator.php';

	Store_Opening_Closing_Hours_Manager_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_store_opening_closing_hours_manager' );

/**
 * The core plugin class that is used to define admin-specific and public-facing hooks.
 *
 * @since    2.0.0
 */
require STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'includes/class-store-opening-closing-hours-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_store_opening_closing_hours_manager() {
	$plugin = new Store_Opening_Closing_Hours_Manager();

	$plugin->run();
}

run_store_opening_closing_hours_manager();
