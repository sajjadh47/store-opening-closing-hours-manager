<?php
/**
 * This file contains the definition of the Store_Opening_Closing_Hours_Manager_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Store_Opening_Closing_Hours_Manager_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_styles() {
		// check if plugin is enabled.
		$enabled = Store_Opening_Closing_Hours_Manager::get_option( 'enable_manager', 'sochm_basic_settings' );

		if ( 'on' !== $enabled ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name, STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_URL . 'public/css/public.css', array( 'wp-jquery-ui-dialog' ), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		// check if plugin is enabled.
		$enabled              = Store_Opening_Closing_Hours_Manager::get_option( 'enable_manager', 'sochm_basic_settings' );
		$show_notice_in_front = Store_Opening_Closing_Hours_Manager::get_option( 'show_notice_in_front', 'sochm_basic_settings' );

		if ( 'on' !== $enabled && 'on' !== $show_notice_in_front ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name, STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_URL . 'public/js/public.js', array( 'jquery', 'jquery-ui-dialog' ), $this->version, false );

		$sochm_script            = array();
		$sochm_script['ajaxurl'] = admin_url( 'admin-ajax.php' );

		wp_localize_script(
			$this->plugin_name,
			'StoreOpeningClosingHoursManager',
			$sochm_script,
		);
	}

	/**
	 * Handles actions to be taken when the store is closed.
	 *
	 * This function checks if the store is closed and the user is an admin. If both conditions are true,
	 * it returns without taking any action. Otherwise, it retrieves various settings related to store closure,
	 * such as options to clear the cart, disable adding to cart, remove checkout elements, and display a notice.
	 * It then performs the actions based on these settings, such as clearing the cart,
	 * adding filters to disable add to cart, removing checkout buttons, and displaying a notice
	 * with a timer (if enabled) showing the time until the store opens.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 */
	public static function store_is_closed() {
		if ( ! Store_Opening_Closing_Hours_Manager::is_store_closed() || is_admin() ) {
			return;
		}

		$auto_clear_carts                  = Store_Opening_Closing_Hours_Manager::get_option( 'auto_clear_carts', 'sochm_basic_settings' );
		$disable_add_to_cart               = Store_Opening_Closing_Hours_Manager::get_option( 'disable_add_to_cart', 'sochm_basic_settings' );
		$remove_proceed_to_checkout_button = Store_Opening_Closing_Hours_Manager::get_option( 'remove_proceed_to_checkout_button', 'sochm_basic_settings' );
		$remove_add_to_cart_button         = Store_Opening_Closing_Hours_Manager::get_option( 'remove_add_to_cart_button', 'sochm_basic_settings' );
		$disable_checkout                  = Store_Opening_Closing_Hours_Manager::get_option( 'disable_checkout', 'sochm_basic_settings' );

		// clear all carts if store closed.
		if ( 'on' === $auto_clear_carts && WC()->cart !== null ) {
			WC()->cart->empty_cart();
		}

		// disable add to cart functionality if store closed.
		if ( 'on' === $disable_add_to_cart ) {
			add_filter( 'woocommerce_add_to_cart_validation', array( 'Store_Opening_Closing_Hours_Manager_Public', 'disable_add_to_cart' ), 10, 3 );
		}

		if ( 'on' === $remove_proceed_to_checkout_button ) {
			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
		}

		if ( 'on' === $remove_add_to_cart_button ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}

		if ( 'on' === $disable_checkout ) {
			remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		}
	}

	/**
	 * Adds all necessary URLs to be purged and triggers the purge process
	 * specifically for GoDaddy Cache (WPaaS Cache).
	 *
	 * This method checks if the `\WPaaS\Cache` class exists and if a ban is already
	 * in progress to prevent redundant operations. It then removes the default
	 * `purge` action and adds a `ban` action to the `shutdown` hook, which
	 * effectively clears the GoDaddy cache for all URLs.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 */
	public static function sochm_purge_godaddy_cache() {
		if ( ! class_exists( '\WPaaS\Cache' ) ) {
			return;
		}

		if ( \WPaaS\Cache::has_ban() ) {
			return;
		}

		// Remove the default purge action to replace it with a ban action.
		remove_action( 'shutdown', array( '\WPaaS\Cache', 'purge' ), PHP_INT_MAX );

		// Add the ban action to clear the entire cache on shutdown.
		add_action( 'shutdown', array( '\WPaaS\Cache', 'ban' ), PHP_INT_MAX );
	}

	/**
	 * Sets up the cache clearing process to run during the WordPress shutdown sequence.
	 *
	 * This method hooks the `flush_all_caches` method to the `shutdown` action,
	 * ensuring that cache clearing operations are performed safely after all
	 * other WordPress processes have completed.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function clear_cache() {
		add_action( 'shutdown', array( $this, 'flush_all_caches' ) );
	}

	/**
	 * Clears various WordPress caching systems.
	 *
	 * This method detects common caching plugins and hosting-level caches,
	 * then triggers the appropriate purge action for each detected system.
	 * It supports a wide range of caching solutions including:
	 * Breeze, Cache Enabler, GoDaddy Cache, Kinsta Cache, LiteSpeed Cache,
	 * NitroPack, SiteGround Speed Optimizer, WP Cloudflare Super Page Cache,
	 * WP Fastest Cache, WP Optimize, WP Super Cache, W3 Total Cache,
	 * WP Engine Cache, and WP Rocket.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @global    object $wp_fastest_cache Global instance of WP Fastest Cache.
	 * @global    object $kinsta_cache     Global instance of Kinsta Cache.
	 * @global    string $file_prefix      Prefix for WP Super Cache files.
	 * @global    string $supercachedir    Directory for WP Super Cache.
	 */
	public function flush_all_caches() {
		global $wp_fastest_cache, $kinsta_cache;
		if ( class_exists( 'Breeze_Admin' ) ) {
			// Purge Breeze Cache.
			do_action( 'breeze_clear_all_cache' );
		} elseif ( class_exists( 'Cache_Enabler' ) ) {
			// Purge Cache Enabler Cache.
			Cache_Enabler::clear_total_cache();
		} elseif ( class_exists( '\WPaaS\Cache' ) ) {
			// Purge Godaddy Cache.
			self::sochm_purge_godaddy_cache();
		} elseif ( class_exists( '\Kinsta\Cache' ) && ! empty( $kinsta_cache ) ) {
			// Purge Kinsta Cache.
			$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
		} elseif ( defined( 'LSCWP_V' ) ) {
			// Purge Litespeed Cache.
			do_action( 'litespeed_purge_all' );
		} elseif ( class_exists( 'NitroPack\SDK\PurgeService' ) ) {
			// Purge Nitropack Cache.
			try {
				$purge_service = new \NitroPack\SDK\PurgeService();
				$purge_service->purgeEverything(); // This purges the entire NitroPack cache.
			} catch ( \Exception $e ) {
				// Nothing to log.
				return;
			}
		} elseif ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			// Purge Speed Optimizer by Siteground Cache.
			sg_cachepress_purge_cache();
		} elseif ( is_plugin_active( 'wp-cloudflare-page-cache/wp-cloudflare-super-page-cache.php' ) ) {
			// Purge Super Page Cache.
			do_action( 'swcfpc_purge_cache' );
		} elseif ( method_exists( 'WpFastestCache', 'deleteCache' ) && ! empty( $wp_fastest_cache ) ) {
			// Purge WP Fastest Cache.
			$wp_fastest_cache->deleteCache( true );
		} elseif ( class_exists( 'WP_Optimize' ) && defined( 'WPO_PLUGIN_MAIN_PATH' ) ) {
			// Purge WP Optimize Cache.
			if ( ! class_exists( 'WP_Optimize_Cache_Commands' ) ) {
				include_once WPO_PLUGIN_MAIN_PATH . 'cache/class-wp-optimize-cache-commands.php';
			}

			if ( class_exists( 'WP_Optimize_Cache_Commands' ) ) {
				$wpoptimize_cache_commands = new WP_Optimize_Cache_Commands();
				$wpoptimize_cache_commands->purge_page_cache();
			}
		} elseif ( function_exists( 'wp_cache_clean_cache' ) ) {
			// Purge WP Super Cache.
			global $file_prefix, $supercachedir;
			if ( empty( $supercachedir ) && function_exists( 'get_supercache_dir' ) ) {
				$supercachedir = get_supercache_dir();
			}
			wp_cache_clean_cache( $file_prefix );
		} elseif ( function_exists( 'w3tc_pgcache_flush' ) ) {
			// Purge W3 Total Cache.
			w3tc_pgcache_flush();
		} elseif ( class_exists( 'WpeCommon' ) ) {
			// Purge WPE Cache.
			if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
				WpeCommon::purge_memcached();
			}

			if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
				WpeCommon::clear_maxcdn_cache();
			}

			if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
				WpeCommon::purge_varnish_cache();
			}
		} elseif ( function_exists( 'rocket_clean_domain' ) ) {
			// Purge WP Rocket Cache.
			rocket_clean_domain();
		}
	}

	/**
	 * Disables the ability to add products to the cart.
	 *
	 * This filter callback function is used to prevent users from adding products to the cart
	 * when the store is closed. It disables the cart, and returns false to
	 * prevent the product from being added to the cart.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     bool $passed      Whether the product can be added to the cart.
	 * @param     int  $product_id  The ID of the product being added to the cart.
	 * @param     int  $quantity    The quantity of the product being added.
	 * @return    bool              False to prevent the product from being added to the cart.
	 */
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed, Squiz.Commenting.FunctionComment.Missing
	public function disable_add_to_cart( $passed, $product_id, $quantity ) {
		return false;
	}

	/**
	 * Generate the front notice html.
	 *
	 * This function is used generate front notice html markup which get's called via ajax
	 * to bypass cache plugin static data.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function get_remaining_time() {
		// check if plugin is enabled.
		$enabled              = Store_Opening_Closing_Hours_Manager::get_option( 'enable_manager', 'sochm_basic_settings' );
		$show_notice_in_front = Store_Opening_Closing_Hours_Manager::get_option( 'show_notice_in_front', 'sochm_basic_settings' );

		if ( 'on' !== $enabled && 'on' !== $show_notice_in_front ) {
			return;
		}

		$notice_text_color  = Store_Opening_Closing_Hours_Manager::get_option( 'notice_text_color', 'sochm_basic_settings', '#FFFFFF' );
		$notice_boxbg_color = Store_Opening_Closing_Hours_Manager::get_option( 'notice_boxbg_color', 'sochm_basic_settings', '#FF0000' );
		$sochm_script       = array();

		$remaining_time_to_close = '';
		$remaining_time_to_open  = '';

		$notice_type   = Store_Opening_Closing_Hours_Manager::get_option( 'notice_type', 'sochm_basic_settings' );
		$timer_enabled = Store_Opening_Closing_Hours_Manager::get_option( 'enable_timer', 'sochm_basic_settings' );
		$timer_design  = Store_Opening_Closing_Hours_Manager::get_option( 'timer_design', 'sochm_basic_settings', '0' );

		$sochm_script['timerDesign'] = $timer_design;
		$sochm_script['notice_type'] = $notice_type;

		if ( ! Store_Opening_Closing_Hours_Manager::is_store_closed() && Store_Opening_Closing_Hours_Manager::is_store_going_to_close_soon() ) {
			$message = Store_Opening_Closing_Hours_Manager::get_option( 'store_going_to_close_soon_notice_message', 'sochm_basic_settings' );

			if ( 'on' === $timer_enabled ) {
				$seconds                              = (int) Store_Opening_Closing_Hours_Manager::store_going_to_close_soon_remaining_seconds();
				$sochm_script['remainingTimeToClose'] = $seconds;
				$remaining_time_to_close              = Store_Opening_Closing_Hours_Manager::get_timer_html( $timer_design, $seconds, 'store_is_going_to_close_soon_remaining_time' );
			}

			$generated_notice_html = Store_Opening_Closing_Hours_Manager::generate_notice_html( $notice_type, $message, $remaining_time_to_close, $notice_boxbg_color, $notice_text_color, true );

			$sochm_script = array_merge( $sochm_script, $generated_notice_html );
		} elseif ( Store_Opening_Closing_Hours_Manager::is_store_closed() ) {
			$message = Store_Opening_Closing_Hours_Manager::get_option( 'notice_message', 'sochm_basic_settings' );

			if ( 'on' === $timer_enabled ) {
				$seconds                             = (int) Store_Opening_Closing_Hours_Manager::store_opening_remaining_seconds();
				$sochm_script['remainingTimeToOpen'] = $seconds;
				$remaining_time_to_open              = Store_Opening_Closing_Hours_Manager::get_timer_html( $timer_design, $seconds, 'store_is_going_to_open_soon_remaining_time' );
			}

			$generated_notice_html = Store_Opening_Closing_Hours_Manager::generate_notice_html( $notice_type, $message, $remaining_time_to_open, $notice_boxbg_color, $notice_text_color, false );

			$sochm_script = array_merge( $sochm_script, $generated_notice_html );
		}

		$sochm_script['ajaxurl']    = admin_url( 'admin-ajax.php' );
		$sochm_script['daysTxt']    = __( 'Days', 'store-opening-closing-hours-manager' );
		$sochm_script['hoursTxt']   = __( 'Hours', 'store-opening-closing-hours-manager' );
		$sochm_script['minutesTxt'] = __( 'Minutes', 'store-opening-closing-hours-manager' );
		$sochm_script['secondsTxt'] = __( 'Seconds', 'store-opening-closing-hours-manager' );

		wp_send_json_success( $sochm_script );
	}

	/**
	 * Shortcode handler for displaying the store opening and closing times table.
	 *
	 * This function is used to display the store opening and closing times table
	 * using a shortcode.  It retrieves the table data and generates the HTML
	 * output.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function shortcode() {
		ob_start();

		if ( apply_filters( 'sochm_show_timezone_in_shortcode', true ) ) {
			echo '<p class=" ' . esc_attr( apply_filters( 'sochm_timezone_shortcode_classes', 'sochm_timezone' ) ) . ' ">' . esc_html( apply_filters( 'sochm_timezone_title_in_shortcode', __( 'Timezone : ', 'store-opening-closing-hours-manager' ) ) ), esc_html( Store_Opening_Closing_Hours_Manager::get_option( 'timezone', 'sochm_basic_settings' ) ) . '</p>';
		}

		require STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . '/public/views/plugin-public-display.php';

		$table = ob_get_clean();

		return $table;
	}
}
