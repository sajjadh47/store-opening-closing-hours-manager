<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since      2.0.0
 * @package    Store_Opening_Closing_Hours_Manager
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

/**
 * Remove plugin options on uninstall/delete
 */
delete_option( 'sochm_basic_settings' );

/**
 * Remove plugin options on uninstall/delete
 */
delete_option( 'sochm_hours_table' );
