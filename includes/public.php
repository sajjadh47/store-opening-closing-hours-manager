<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

/**
 * Class to handle plugin public functionality
 */
if ( ! class_exists( 'SOCHM_PUBLIC' ) )
{
	class SOCHM_PUBLIC
	{
		public static function run()
		{
			if ( ! SOCHM_UTIL::isStoreClosed() && SOCHM_UTIL::isStoreGoingToCloseSoon() && ! is_admin() )
			{
				add_action( 'template_redirect', array( 'SOCHM_PUBLIC', 'store_is_going_to_close_soon' ) );				
			}

			if ( SOCHM_UTIL::isStoreClosed() && ! is_admin() )
			{
				add_action( 'template_redirect', array( 'SOCHM_PUBLIC', 'store_is_closed' ) );
			}
		}

		public static function store_is_going_to_close_soon()
		{
			$notice_type = SOCHM_UTIL::get_option( 'notice_type', 'sochm_basic_settings' );

			if ( SOCHM_UTIL::get_option( 'show_notice_in_front', 'sochm_basic_settings' ) == 'on' &&  $notice_type == '5' )
			{
				wc_clear_notices();

				$remaining_time_to_close = '';

				$remaining_time_txt = apply_filters( 'sochm_store_close_remaining_time_text', __( 'Remaining Time To Close', 'store-opening-closing-hours-manager' ) );

				if ( SOCHM_UTIL::get_option( 'enable_timer', 'sochm_basic_settings' ) == 'on' )
				{
					$seconds = SOCHM_UTIL::storeGoingToCloseSoonRemainingSeconds();

					$timer_design = SOCHM_UTIL::get_option( 'timer_design', 'sochm_basic_settings', '0' );

					if ( $timer_design == '0' )
					{
						$remaining_time_to_close = ' <div> ' . $remaining_time_txt . ' <span id="store_is_going_to_close_soon_remaining_time" class="default">' . sprintf( '%02dd:%02dh:%02dm:%02ds', ( $seconds / 86400 ), ( $seconds / 3600 ), ( $seconds / 60 % 60 ), $seconds % 60 ) . '</span></div>';
					}
					elseif ( $timer_design == '1' )
					{
						$remaining_time_to_close = ' <div id="sochm-timer-design-boxed"> ' . $remaining_time_txt . ' <div id="store_is_going_to_close_soon_remaining_time">
							<div id="sochm-days"">' . sprintf( '%02d', ( $seconds / 86400 ) ) . '<span>Days<span></div>
							<div id="sochm-hours"">' . sprintf( '%02d', ( $seconds / 3600 ) ) . '<span>Hours<span></div>
							<div id="sochm-minutes"">' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . '<span>Minutes<span></div>
							<div id="sochm-seconds"">' . sprintf( '%02d', ( $seconds % 60 ) ) . '<span>Seconds<span></div>
						</div>';
					}
					elseif ( $timer_design == '2' )
					{						
						$remaining_time_to_close = ' <div id="sochm-timer-design-boxed-with-flipping"> ' . $remaining_time_txt . ' <div id="store_is_going_to_close_soon_remaining_time">
							<div id="flipdown" class="flipdown"></div>
						</div>';
					}
					elseif ( $timer_design == '3' )
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
					elseif ( $timer_design == '4' )
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

				wc_add_notice( SOCHM_UTIL::get_option( 'store_going_to_close_soon_notice_message', 'sochm_basic_settings' ) . $remaining_time_to_close, 'error' );
			}
		}

		public static function store_is_closed()
		{
		    // clear all carts if store closed
		    if ( SOCHM_UTIL::get_option( 'auto_clear_carts', 'sochm_basic_settings' ) == 'on' && WC()->cart !== null )
		    {
		        WC()->cart->empty_cart();
		    }

		    // disable add to cart functionality if store closed
		    if ( SOCHM_UTIL::get_option( 'disable_add_to_cart', 'sochm_basic_settings' ) == 'on' )
		    {
		    	add_filter( 'woocommerce_add_to_cart_validation', array( 'SOCHM_PUBLIC', 'disable_add_to_cart' ), 10, 3 );
			}
			
			if( SOCHM_UTIL::get_option( 'remove_proceed_to_checkout_button', 'sochm_basic_settings' ) == 'on' )
			{
		   		remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
			}

			if( SOCHM_UTIL::get_option( 'remove_add_to_cart_button', 'sochm_basic_settings' ) == 'on' )
			{
		   		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		   		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			}

			if( SOCHM_UTIL::get_option( 'disable_checkout', 'sochm_basic_settings' ) == 'on' )
			{
		   		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
			}

			$notice_type = SOCHM_UTIL::get_option( 'notice_type', 'sochm_basic_settings' );

			if ( SOCHM_UTIL::get_option( 'show_notice_in_front', 'sochm_basic_settings' ) == 'on' &&  $notice_type == '5' )
			{
				wc_clear_notices();

				$remaining_time_to_open = '';

				$remaining_time_txt = apply_filters( 'sochm_store_open_remaining_time_text', __( 'Remaining Time To Open', 'store-opening-closing-hours-manager' ) );

				if ( SOCHM_UTIL::get_option( 'enable_timer', 'sochm_basic_settings' ) == 'on' )
				{
					$seconds = SOCHM_UTIL::storeOpeningRemainingSeconds();

					$timer_design = SOCHM_UTIL::get_option( 'timer_design', 'sochm_basic_settings', '0' );

					if ( $timer_design == '0' )
					{
						$remaining_time_to_open = ' <div> ' . $remaining_time_txt . ' <span id="store_is_going_to_open_soon_remaining_time" class="default">' . sprintf( '%02dd:%02dh:%02dm:%02ds', ( $seconds / 86400 ), ( $seconds / 3600 ), ( $seconds / 60 % 60 ), $seconds % 60 ) . '</span></div>';
					}
					elseif ( $timer_design == '1' )
					{
						$remaining_time_to_open = ' <div id="sochm-timer-design-boxed"> ' . $remaining_time_txt . ' <div id="store_is_going_to_open_soon_remaining_time">
							<div id="sochm-days"">' . sprintf( '%02d', ( $seconds / 86400 ) ) . '<span>Days<span></div>
							<div id="sochm-hours"">' . sprintf( '%02d', ( $seconds / 3600 ) ) . '<span>Hours<span></div>
							<div id="sochm-minutes"">' . sprintf( '%02d', ( $seconds / 60 % 60 ) ) . '<span>Minutes<span></div>
							<div id="sochm-seconds"">' . sprintf( '%02d', ( $seconds % 60 ) ) . '<span>Seconds<span></div>
						</div>';
					}
					elseif ( $timer_design == '2' )
					{						
						$remaining_time_to_open = ' <div id="sochm-timer-design-boxed-with-flipping"> ' . $remaining_time_txt . ' <div id="store_is_going_to_open_soon_remaining_time">
							<div id="flipdown" class="flipdown"></div>
						</div>';
					}
					elseif ( $timer_design == '3' )
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
					elseif ( $timer_design == '4' )
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

				wc_add_notice( SOCHM_UTIL::get_option( 'notice_message', 'sochm_basic_settings' ) . $remaining_time_to_open, 'error' );
			}
		}

		public static function disable_add_to_cart( $passed, $product_id, $quantity )
		{
			wc_clear_notices();
		    
		    wc_add_notice( SOCHM_UTIL::get_option( 'disable_add_to_cart_message', 'sochm_basic_settings' ), 'error' );
		    
		    return false;
		}
	}
	
	SOCHM_PUBLIC::run();
}
