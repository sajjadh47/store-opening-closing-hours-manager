<?php
/**
 * This file contains the definition of the Store_Opening_Closing_Hours_Manager_Deactivator class, which
 * is used during plugin deactivation.
 *
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin deactivation.
 *
 * @since    2.0.0
 */
class Store_Opening_Closing_Hours_Manager_Deactivator {
	/**
	 * Deactivation hook.
	 *
	 * This function is called when the plugin is deactivated. It can be used to
	 * perform tasks such as cleaning up temporary data, unscheduling cron jobs,
	 * or removing options.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 */
	public static function on_deactivate() {}
}
