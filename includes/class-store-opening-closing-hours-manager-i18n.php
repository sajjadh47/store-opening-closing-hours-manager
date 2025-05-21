<?php
/**
 * This file contains the definition of the Store_Opening_Closing_Hours_Manager_I18n class, which
 * is used to load the plugin's internationalization.
 *
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since    2.0.0
 */
class Store_Opening_Closing_Hours_Manager_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'store-opening-closing-hours-manager',
			false,
			dirname( STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
