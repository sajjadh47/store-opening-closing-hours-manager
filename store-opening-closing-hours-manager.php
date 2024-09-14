<?php
/*
Plugin Name: Store Opening Closing Hours Manager
Plugin URI : https://wordpress.org/plugins/store-opening-closing-hours-manager/
Description: Setup your Woocomerce store opening and closing hours to manage your business at ease!
Version: 1.0.5
Author: Sajjad Hossain Sagor
Author URI: https://profiles.wordpress.org/sajjad67
Text Domain: store-opening-closing-hours-manager
Domain Path: /languages

License: GPL2
This WordPress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This free software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ---------------------------------------------------------
// Checking if Woocommerce is either installed or active
// ---------------------------------------------------------
register_activation_hook( __FILE__, 'sochm_check_woocommerce_activation_status' );

add_action( 'admin_init', 'sochm_check_woocommerce_activation_status' );

function sochm_check_woocommerce_activation_status()
{
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
	{
		// Deactivate the plugin
		deactivate_plugins( __FILE__ );

		// Throw an error in the wordpress admin console
		$error_message = __( '"Store Opening Closing Hours Manager" plugin requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> plugin to be active! <a href="javascript:history.back()"> Go back & activate Woocommerce. </a>', 'woocommerce');

		wp_die( $error_message, 'WooCommerce Not Found' );
	}
}

// ---------------------------------------------------------
// Define Plugin Folders Path
// ---------------------------------------------------------
if ( ! defined( 'SOCHM_PLUGIN_PATH' ) ) define( 'SOCHM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'SOCHM_PLUGIN_URL' ) ) define( 'SOCHM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// ---------------------------------------------------------
// Load Language Translations
// ---------------------------------------------------------
add_action( 'plugins_loaded', 'sochm_load_plugin_textdomain' );

if ( ! function_exists( 'sochm_load_plugin_textdomain' ) )
{
	function sochm_load_plugin_textdomain()
	{
		load_plugin_textdomain( 'store-opening-closing-hours-manager', '', basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}

// ---------------------------------------------------------
// Load Admin Settings
// ---------------------------------------------------------
require_once SOCHM_PLUGIN_PATH . 'includes/admin_settings.php';

// ---------------------------------------------------------
// Load Util Class
// ---------------------------------------------------------
require_once SOCHM_PLUGIN_PATH . 'includes/util.php';

// ---------------------------------------------------------
// Load Public Settings
// ---------------------------------------------------------
require_once SOCHM_PLUGIN_PATH . 'includes/public.php';

// ---------------------------------------------------------
// Load Widget
// ---------------------------------------------------------
require_once SOCHM_PLUGIN_PATH . 'includes/widget.php';

// ---------------------------------------------------------
// Load Shortcode
// ---------------------------------------------------------
require_once SOCHM_PLUGIN_PATH . 'includes/shortcode.php';

// ---------------------------------------------------------
// Load Admin Settings
// ---------------------------------------------------------
require_once SOCHM_PLUGIN_PATH . 'includes/admin.php';

// ---------------------------------------------------------
// Add Go To Settings Page Link in Plugin List Table
// ---------------------------------------------------------
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sochm_add_goto_settings_link' );

if ( ! function_exists( 'sochm_add_goto_settings_link' ) )
{
	function sochm_add_goto_settings_link( $links )
	{ 	
		$goto_settings_link = array( '<a href="' . admin_url( 'options-general.php?page=store-opening-closing-hours-manager.php' ) . '">' . __( "Settings", 'store-opening-closing-hours-manager' ) . '</a>' );
		
		return array_merge( $links, $goto_settings_link );
	}
}

// ---------------------------------------------------------
// Enqueue Plugin Scripts & Stylesheets in Admin
// ---------------------------------------------------------
add_action( 'admin_enqueue_scripts', 'sochm_admin_enqueue_scripts' );

if ( ! function_exists( 'sochm_admin_enqueue_scripts' ) )
{
	function sochm_admin_enqueue_scripts()
	{
		wp_enqueue_style( 'sochm_admin_stylesheet', plugins_url( '/assets/admin/css/style.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/admin/css/style.css' ), false );

		global $current_screen;

		if ( $current_screen->id !== 'settings_page_store-opening-closing-hours-manager' ) return;

		$weekDaysTable = SOCHM_UTIL::get_table_settings();

		wp_enqueue_script( 'sochm_angularjs_script', plugins_url( '/assets/admin/js/angular.min.js', __FILE__ ) );
		
		wp_enqueue_script( 'sochm_admin_script', plugins_url( '/assets/admin/js/script.js', __FILE__ ), array( 'jquery', 'sochm_angularjs_script' ) );

		wp_localize_script( 'sochm_admin_script', 'SOCHM', array(
			'savingText' 		=> __( 'Saving... Please Wait!', 'store-opening-closing-hours-manager' ),
			'savedText' 		=> __( 'Saved!', 'store-opening-closing-hours-manager' ),
			'saveText' 			=> __( 'Save Changes', 'store-opening-closing-hours-manager' ),
			'addBtnText' 		=> __( 'Add', 'store-opening-closing-hours-manager' ),
			'removeBtnText' 	=> __( 'Remove', 'store-opening-closing-hours-manager' ),
			'confirnDeleteMsg' 	=> __( 'Are you sure, you want to remove this?', 'store-opening-closing-hours-manager' ),
			'weekDaysTable' 	=> wp_json_encode( $weekDaysTable ),
			'_wpnonce' 			=> wp_create_nonce( 'sochm_ajax_nonce' )
		) );
	}
}

// ---------------------------------------------------------
// Enqueue Plugin Scripts & Stylesheets in Front
// ---------------------------------------------------------
add_action( 'wp_enqueue_scripts', 'sochm_enqueue_scripts' );

if ( ! function_exists( 'sochm_enqueue_scripts' ) )
{
	function sochm_enqueue_scripts()
	{
		wp_enqueue_style( 'sochm_stylesheet', plugins_url( '/assets/public/css/style.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/public/css/style.css' ), false );

		wp_enqueue_script( 'sochm_toast_script', plugins_url( '/assets/public/js/toast.js', __FILE__ ), array( 'jquery' ) );
		
		wp_enqueue_script( 'sochm_flipdown_script', plugins_url( '/assets/public/js/flipdown.js', __FILE__ ), array( 'jquery' ) );

		wp_enqueue_script( 'sochm_script', plugins_url( '/assets/public/js/script.js', __FILE__ ), array( 'jquery', 'sochm_toast_script', 'jquery-ui-dialog', 'sochm_flipdown_script' ) );

		$notice_text_color = SOCHM_UTIL::get_option( 'notice_text_color', 'sochm_basic_settings', '#FFFFFF' );
		
		$notice_boxbg_color = SOCHM_UTIL::get_option( 'notice_boxbg_color', 'sochm_basic_settings', '#FF0000' );

		$sochm_script = [];

		$remaining_time_to_close = $remaining_time_to_open = '';

		$remaining_time_txt = apply_filters( 'sochm_store_close_remaining_time_text', __( 'Remaining Time To Close', 'store-opening-closing-hours-manager' ) );

		$notice_type = SOCHM_UTIL::get_option( 'notice_type', 'sochm_basic_settings' );

		if ( SOCHM_UTIL::get_option( 'show_notice_in_front', 'sochm_basic_settings' ) == 'on' && ! SOCHM_UTIL::isStoreClosed() && SOCHM_UTIL::isStoreGoingToCloseSoon() )
		{
			$message = SOCHM_UTIL::get_option( 'store_going_to_close_soon_notice_message', 'sochm_basic_settings' );
			
			if ( SOCHM_UTIL::get_option( 'enable_timer', 'sochm_basic_settings' ) == 'on' )
			{
				$seconds = $sochm_script['remaining_time_to_close'] = SOCHM_UTIL::storeGoingToCloseSoonRemainingSeconds();

				$sochm_script['timer_design'] = SOCHM_UTIL::get_option( 'timer_design', 'sochm_basic_settings', '0' );

				if ( $sochm_script['timer_design'] == '0' )
				{
					$remaining_time_to_close = ' <div> ' . $remaining_time_txt . ' <span id="store_is_going_to_close_soon_remaining_time" class="default">' . sprintf( '%02dd:%02dh:%02dm:%02ds', ( $seconds / 86400 ), ( $seconds / 3600 ), ( $seconds / 60 % 60 ), $seconds % 60 ) . '</span></div>';
				}
				elseif ( $sochm_script['timer_design'] == '1' )
				{
					$remaining_time_to_close = ' <div id="sochm-timer-design-boxed"> ' . $remaining_time_txt . ' <div id="store_is_going_to_close_soon_remaining_time">
						<div id="sochm-days"">' . sprintf( '%02d', ( $seconds / 86400 ) ) . '<span>Days<span></div>
						<div id="sochm-hours"">' . sprintf( '%02d', ( $seconds / 3600 ) ) . '<span>Hours<span></div>
						<div id="sochm-minutes"">' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . '<span>Minutes<span></div>
						<div id="sochm-seconds"">' . sprintf( '%02d', ( $seconds % 60 ) ) . '<span>Seconds<span></div>
					</div>';
				}
				elseif ( $sochm_script['timer_design'] == '2' )
				{
					wp_enqueue_style( 'sochm_flipdown_stylesheet', plugins_url( '/assets/public/css/flipdown.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/public/css/flipdown.css' ), false );
					
					$remaining_time_to_close = ' <div id="sochm-timer-design-boxed-with-flipping"> ' . $remaining_time_txt . ' <div id="store_is_going_to_close_soon_remaining_time">
						<div id="flipdown" class="flipdown"></div>
					</div>';
				}
				elseif ( $sochm_script['timer_design'] == '3' )
				{
					$remaining_time_to_close = '
					<div id="sochm-timer-design-circular-border"> ' . $remaining_time_txt . '
						<div id="store_is_going_to_close_soon_remaining_time">
							<div id="sochm-days"><span>' . sprintf( '%02d', ( $seconds / 86400 ) ) . ' Days</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-hours"><span>' . sprintf( '%02d', ( $seconds / 3600 ) ) . ' Hours</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-minutes"><span>' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . ' Minutes</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-seconds"><span>' . sprintf( '%02d', ( $seconds % 60 ) ) . ' Seconds</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
						</div>
					</div>';
				}
				elseif ( $sochm_script['timer_design'] == '4' )
				{
					$remaining_time_to_close = '
					<div id="sochm-timer-design-circular-border"> ' . $remaining_time_txt . '
						<div id="store_is_going_to_close_soon_remaining_time">
							<div id="sochm-days"><span>' . sprintf( '%02d', ( $seconds / 86400 ) ) . ' Days</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-hours"><span>' . sprintf( '%02d', ( $seconds / 3600 ) ) . ' Hours</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-minutes"><span>' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . ' Minutes</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-seconds"><span>' . sprintf( '%02d', ( $seconds % 60 ) ) . ' Seconds</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
						</div>
					</div>';
				}
			}

			if ( $notice_type == '0' )
			{
				wp_enqueue_style( 'sochm_toast_stylesheet', plugins_url( '/assets/public/css/toast.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/public/css/toast.css' ), false );

				$sochm_script['toast_html'] = '
				<div id="sochm-toast" class="sochm-toast store_going_to_close_soon_toast_message">
					<div class="sochm-toast-content">
						<div class="sochm-toast-message">
							<span class="sochm-toast-text sochm-toast-text-2"></span>
						</div>
					</div>
				</div>';

				$sochm_script['toast_message'] = $message . $remaining_time_to_close;

				$sochm_script['toast_type'] = 'error';
			}

			if ( $notice_type == '1' )
			{
				wp_enqueue_style( 'wp-jquery-ui-dialog' );

				$sochm_script['dialog_html'] = '
				<div id="sochm-dialog">
				  <div>' . $message . $remaining_time_to_close . '</div>
				</div>';
			}

			if ( $notice_type == '2' )
			{
				$topValue = is_admin_bar_showing() ? '32px' : '0';
				
				$sochm_script['sticky_header_html'] = '
				<div id="sochm-sticky-header">
					<div>' . $message . $remaining_time_to_close . '</div>
				</div>
				<style type="text/css" media="screen">div#sochm-sticky-header {background: ' . $notice_boxbg_color . ';color: #ff0000;text-align: center;padding: 15px;position: fixed;top: ' . $topValue . ';right: 0;left: 0;z-index: 999999;}</style>';
			}

			if ( $notice_type == '3' )
			{
				$sochm_script['sticky_footer_html'] = '
				<div id="sochm-sticky-footer">
					<div>' . $message . $remaining_time_to_close . '</div>
				</div>
				<style type="text/css" media="screen">div#sochm-sticky-footer {background: ' . $notice_boxbg_color . ';color: ' . $notice_text_color . ';text-align: center;padding: 15px;position: fixed;bottom: 0;right: 0;left: 0;z-index: 999999;}</style>';
			}

			if ( $notice_type == '4' )
			{
				$sochm_script['single_page'] = '
				<div class="sochm-single-page-container">
					<i class="sochm-icon-close">
						<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</i>
				  <div class="middle">
					<h3>' . $message . '</h3>
					<hr>
					<div>' . $remaining_time_to_close . '</div>
				  </div>
				</div>
				<style type="text/css" media="screen">body, html{overflow: hidden!important}.sochm-single-page-container {background: ' . $notice_boxbg_color . ';color: ' . $notice_text_color . ';}.sochm-single-page-container h3{color: ' . $notice_text_color . ';}</style>';
			}
		}
		elseif ( SOCHM_UTIL::get_option( 'show_notice_in_front', 'sochm_basic_settings' ) == 'on' && SOCHM_UTIL::isStoreClosed() )
		{
			$message = SOCHM_UTIL::get_option( 'notice_message', 'sochm_basic_settings' );

			$remaining_time_txt = apply_filters( 'sochm_store_open_remaining_time_text', __( 'Remaining Time To Open', 'store-opening-closing-hours-manager' ) );

			if ( SOCHM_UTIL::get_option( 'enable_timer', 'sochm_basic_settings' ) == 'on' )
			{
				$seconds = $sochm_script['remaining_time_to_open'] = SOCHM_UTIL::storeOpeningRemainingSeconds();

				$sochm_script['timer_design'] = SOCHM_UTIL::get_option( 'timer_design', 'sochm_basic_settings', '0' );

				if ( $sochm_script['timer_design'] == '0' )
				{
					$remaining_time_to_open = ' <div> ' . $remaining_time_txt . ' <span id="store_is_going_to_open_soon_remaining_time" class="default">' . sprintf( '%02dd:%02dh:%02dm:%02ds', ( $seconds / 86400 ), ( $seconds / 3600 ), ( $seconds / 60 % 60 ), $seconds % 60 ) . '</span></div>';
				}
				elseif ( $sochm_script['timer_design'] == '1' )
				{
					$remaining_time_to_open = ' <div id="sochm-timer-design-boxed"> ' . $remaining_time_txt . ' <div id="store_is_going_to_open_soon_remaining_time">
						<div id="sochm-days"">' . sprintf( '%02d', ( $seconds / 86400 ) ) . '<span>Days<span></div>
						<div id="sochm-hours"">' . sprintf( '%02d', ( $seconds / 3600 ) ) . '<span>Hours<span></div>
						<div id="sochm-minutes"">' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . '<span>Minutes<span></div>
						<div id="sochm-seconds"">' . sprintf( '%02d', ( $seconds % 60 ) ) . '<span>Seconds<span></div>
					</div>';
				}
				elseif ( $sochm_script['timer_design'] == '2' )
				{
					wp_enqueue_style( 'sochm_flipdown_stylesheet', plugins_url( '/assets/public/css/flipdown.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/public/css/flipdown.css' ), false );
					
					$remaining_time_to_open = ' <div id="sochm-timer-design-boxed-with-flipping"> ' . $remaining_time_txt . ' <div id="store_is_going_to_open_soon_remaining_time">
						<div id="flipdown" class="flipdown"></div>
					</div>';
				}
				elseif ( $sochm_script['timer_design'] == '3' )
				{
					$remaining_time_to_open = '
					<div id="sochm-timer-design-circular-border"> ' . $remaining_time_txt . '
						<div id="store_is_going_to_open_soon_remaining_time">
							<div id="sochm-days"><span>' . sprintf( '%02d', ( $seconds / 86400 ) ) . ' Days</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-hours"><span>' . sprintf( '%02d', ( $seconds / 3600 ) ) . ' Hours</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-minutes"><span>' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . ' Minutes</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-seconds"><span>' . sprintf( '%02d', ( $seconds % 60 ) ) . ' Seconds</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
						</div>
					</div>';
				}
				elseif ( $sochm_script['timer_design'] == '4' )
				{
					$remaining_time_to_open = '
					<div id="sochm-timer-design-circular-border"> ' . $remaining_time_txt . '
						<div id="store_is_going_to_open_soon_remaining_time">
							<div id="sochm-days"><span>' . sprintf( '%02d', ( $seconds / 86400 ) ) . ' Days</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-hours"><span>' . sprintf( '%02d', ( $seconds / 3600 ) ) . ' Hours</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-minutes"><span>' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . ' Minutes</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
							<div id="sochm-seconds"><span>' . sprintf( '%02d', ( $seconds % 60 ) ) . ' Seconds</span><svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/></g></svg>
							</div>
						</div>
					</div>';
				}
			}

			if ( $notice_type == '0' )
			{
				wp_enqueue_style( 'sochm_toast_stylesheet', plugins_url( '/assets/public/css/toast.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/public/css/toast.css' ), false );

				$sochm_script['toast_html'] = '
				<div id="sochm-toast" class="sochm-toast store_going_to_close_soon_toast_message">
					<div class="sochm-toast-content">
						<div class="sochm-toast-message">
							<span class="sochm-toast-text sochm-toast-text-2"></span>
						</div>
					</div>
				</div>';

				$sochm_script['toast_message'] = $message . $remaining_time_to_open;

				$sochm_script['toast_type'] = 'error';
			}

			if ( $notice_type == '1' )
			{
				wp_enqueue_style( 'wp-jquery-ui-dialog' );

				$sochm_script['dialog_html'] = '
				<div id="sochm-dialog">
				  <div>' . $message . $remaining_time_to_open . '</div>
				</div>';
			}

			if ( $notice_type == '2' )
			{
				$topValue = is_admin_bar_showing() ? '32px' : '0';
				
				$sochm_script['sticky_header_html'] = '
				<div id="sochm-sticky-header">
					<div>' . $message . $remaining_time_to_open . '</div>
				</div>
				<style type="text/css" media="screen">div#sochm-sticky-header {background: ' . $notice_boxbg_color . ';color: #ff0000;text-align: center;padding: 15px;position: fixed;top: ' . $topValue . ';right: 0;left: 0;z-index: 999999;}</style>';
			}

			if ( $notice_type == '3' )
			{
				$sochm_script['sticky_footer_html'] = '
				<div id="sochm-sticky-footer">
					<div>' . $message . $remaining_time_to_open . '</div>
				</div>
				<style type="text/css" media="screen">div#sochm-sticky-footer {background: ' . $notice_boxbg_color . ';color: ' . $notice_text_color . ';text-align: center;padding: 15px;position: fixed;bottom: 0;right: 0;left: 0;z-index: 999999;}</style>';
			}

			if ( $notice_type == '4' )
			{
				$sochm_script['single_page'] = '
				<div class="sochm-single-page-container">
					<i class="sochm-icon-close">
						<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</i>
				  <div class="middle">
					<h3>' . $message . '</h3>
					<hr>
					<div>' . $remaining_time_to_open . '</div>
				  </div>
				</div>
				<style type="text/css" media="screen">body, html{overflow: hidden!important}.sochm-single-page-container {background: ' . $notice_boxbg_color . ';color: ' . $notice_text_color . ';}.sochm-single-page-container h3{color: ' . $notice_text_color . ';}</style>';
			}
		}

		wp_localize_script( 'sochm_script', 'SOCHM', $sochm_script );
	}
}

add_action( 'wp_ajax_sochm_save_weekTable', 'sochm_save_weekTable' );

if ( ! function_exists( 'sochm_save_weekTable' ) )
{
	function sochm_save_weekTable()
	{
		// Check for nonce security      
		if ( ! check_admin_referer( 'sochm_ajax_nonce', '_wpnonce' ) )
		{
			wp_send_json_error( [ 'message' => __( "Cheatin Huh!", 'store-opening-closing-hours-manager' ) ] );
		}

		if ( ! current_user_can( 'manage_options' ) )
		{
			wp_send_json_error( [ 'message' => __( "You don't have permission to access this page!", 'store-opening-closing-hours-manager' ) ] );
		}

		if ( isset( $_POST['payload'] ) )
		{
			parse_str( $_POST['payload'], $payload );

			if ( isset( $payload['store_open_close'] ) )
			{
				if ( is_array( $payload['store_open_close'] ) )
				{
					$sanitized_value = [];
					
					foreach ( $payload['store_open_close'] as $value )
					{
						$value = array_map( 'sanitize_text_field', $value );

						$sanitized_value[] = $value;						
					}	
					
					update_option( 'sochm_table_data', $sanitized_value );

					wp_send_json_error( [ 'message' => 'Success!' ] );
				}
			}
		}

		die();
	}	
}
