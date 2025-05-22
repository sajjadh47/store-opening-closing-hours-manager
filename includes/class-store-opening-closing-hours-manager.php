<?php
/**
 * This file contains the definition of the Store_Opening_Closing_Hours_Manager class, which
 * is used to begin the plugin's functionality.
 *
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since    2.0.0
 */
class Store_Opening_Closing_Hours_Manager {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       Store_Opening_Closing_Hours_Manager_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		$this->version     = defined( 'STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_VERSION' ) ? STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'store-opening-closing-hours-manager';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Store_Opening_Closing_Hours_Manager_Loader. Orchestrates the hooks of the plugin.
	 * - Store_Opening_Closing_Hours_Manager_i18n.   Defines internationalization functionality.
	 * - Sajjad_Dev_Settings_API.                    Provides an interface for interacting with the WordPress Settings API.
	 * - Store_Opening_Closing_Hours_Manager_Widget. Register a WordPress widget.
	 * - Store_Opening_Closing_Hours_Manager_Admin.  Defines all hooks for the admin area.
	 * - Store_Opening_Closing_Hours_Manager_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'includes/class-store-opening-closing-hours-manager-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'includes/class-store-opening-closing-hours-manager-i18n.php';

		/**
		 * The class responsible for defining an interface for interacting with the WordPress Settings API.
		 */
		require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'includes/class-sajjad-dev-settings-api.php';

		/**
		 * The class responsible for defining a WordPress widget.
		 */
		require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'includes/class-store-opening-closing-hours-manager-widget.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'admin/class-store-opening-closing-hours-manager-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . 'public/class-store-opening-closing-hours-manager-public.php';

		$this->loader = new Store_Opening_Closing_Hours_Manager_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Store_Opening_Closing_Hours_Manager_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function set_locale() {
		$plugin_i18n = new Store_Opening_Closing_Hours_Manager_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Store_Opening_Closing_Hours_Manager_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'plugin_action_links_' . STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_BASENAME, $plugin_admin, 'add_plugin_action_links' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );
		$this->loader->add_action( 'admin_bar_menu', $plugin_admin, 'admin_bar_menu', 500 );

		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widget' );

		$this->loader->add_action( 'before_woocommerce_init', $plugin_admin, 'declare_compatibility_with_wc_custom_order_tables' );

		$this->loader->add_action( 'wp_ajax_sochm_save_week_table', $plugin_admin, 'save_week_table' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_public_hooks() {
		$plugin_public = new Store_Opening_Closing_Hours_Manager_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'template_redirect', $plugin_public, 'store_is_closed' );

		$this->loader->add_action( 'wp_ajax_sochm_flush_cache', $plugin_public, 'clear_cache' );
		$this->loader->add_action( 'wp_ajax_nopriv_sochm_flush_cache', $plugin_public, 'clear_cache' );

		$this->loader->add_action( 'wp_ajax_sochm_get_remaining_time', $plugin_public, 'get_remaining_time' );
		$this->loader->add_action( 'wp_ajax_nopriv_sochm_get_remaining_time', $plugin_public, 'get_remaining_time' );

		add_shortcode( 'sochm_display_table', array( $plugin_public, 'shortcode' ) );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    Store_Opening_Closing_Hours_Manager_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieves the value of a specific settings field.
	 *
	 * This method fetches the value of a settings field from the WordPress options database.
	 * It retrieves the entire option group for the given section and then extracts the
	 * value for the specified field.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $option        The name of the settings field.
	 * @param     string $section       The name of the section this field belongs to. This corresponds
	 *                                  to the option name used in `register_setting()`.
	 * @param     string $default_value Optional. The default value to return if the field's value
	 *                                  is not found in the database. Default is an empty string.
	 * @return    string|mixed          The value of the settings field, or the default value if not found.
	 */
	public static function get_option( $option, $section, $default_value = '' ) {
		$options = get_option( $section ); // Get all options for the section.

		// Check if the option exists within the section's options array.
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ]; // Return the option value.
		}

		return $default_value; // Return the default value if the option is not found.
	}

	/**
	 * Loads a template part, prioritizing theme overrides over plugin defaults.
	 *
	 * This function first checks if a template file exists in the active theme's
	 * directory (within a 'sochm' subdirectory). If not found, it falls back
	 * to the default template located within the plugin's 'templates' directory.
	 * The final resolved path can be filtered for advanced customization.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    private
	 * @param     string $template_name The base name of the template file (e.g., 'timer-design-0').
	 * @param     array  $data          An associative array of data to pass to the template.
	 * @return    string                The rendered HTML content of the template.
	 */
	private static function get_template_part( $template_name, $data = array() ) {
		$template_file = $template_name . '.php'; // e.g., 'timer-design-0.php'.
		$template_path = ''; // Initialize the path.

		// 1. Check in the active child theme's 'sochm' subdirectory.
		// If a child theme is active, get_stylesheet_directory() points to the child theme.
		$child_theme_override_path = get_stylesheet_directory() . '/templates/sochm/' . $template_file;
		if ( file_exists( $child_theme_override_path ) ) {
			$template_path = $child_theme_override_path;
		}

		// 2. If not found in child theme, check in the active parent theme's 'sochm' subdirectory.
		// get_template_directory() always points to the parent theme.
		// Only check parent if a child theme is active
		if ( empty( $template_path ) && get_stylesheet_directory() !== get_template_directory() ) {
			$parent_theme_override_path = get_template_directory() . '/templates/sochm/' . $template_file;
			if ( file_exists( $parent_theme_override_path ) ) {
				$template_path = $parent_theme_override_path;
			}
		}

		// 3. If not found in theme, fall back to the plugin's default template.
		if ( empty( $template_path ) ) {
			$plugin_default_path = STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . '/templates/' . $template_file;
			if ( file_exists( $plugin_default_path ) ) {
				$template_path = $plugin_default_path;
			}
		}

		/**
		 * Filters the path to a specific template part file.
		 *
		 * Allows overriding the template file used for a given part.
		 *
		 * @since    2.0.0
		 * @param    string $template_path The full path to the template file.
		 * @param    string $template_name The base name of the template (e.g., 'timer-design-0').
		 * @param    array  $data          The data array passed to the template.
		 */
		$template_path = apply_filters( 'sochm_get_template_part_path_' . $template_name, $template_path, $template_name, $data );

		// If after all checks, template_path is still empty or provided file doesn't exists, it means the file wasn't found.
		if ( empty( $template_path ) || ! file_exists( $template_path ) ) {
			return '';
		}

		ob_start(); // Start output buffering.
		include $template_path; // Include the template file.
		return ob_get_clean(); // Get the buffered content and clean the buffer.
	}

	/**
	 * Checks if the store is currently closed based on the configured opening and closing hours.
	 *
	 * This function determines the store's status by checking if the current time falls
	 * outside the defined opening hours for the current day. It takes into account the
	 * plugin's global settings, timezone, and the opening/closing hours table.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    bool True if the store is closed, false if the store is open.
	 */
	public static function is_store_closed() {
		$store_is_closed = false;

		// Check if the plugin is enabled.
		$enabled = self::get_option( 'enable_manager', 'sochm_basic_settings' );

		// Get the configured timezone.
		$timezone = self::get_option( 'timezone', 'sochm_basic_settings' );

		// check if close store is enabled.
		$close_store = self::get_option( 'close_store', 'sochm_basic_settings' );

		if ( 'on' === $close_store ) {
			return true;
		}

		// Get the opening and closing hours table data.
		$hours_table = get_option( 'sochm_table_data', array() );

		// If the hours table is not an array or is empty, the store is considered closed.
		if ( ! is_array( $hours_table ) || empty( $hours_table ) ) {
			return $store_is_closed;
		}

		// If the plugin is not enabled or the timezone is not set, the store is considered closed.
		if ( 'on' !== $enabled || empty( $timezone ) ) {
			return $store_is_closed;
		}

		// Create a DateTime object with the specified timezone.
		$dt = new DateTime( 'now', new DateTimezone( $timezone ) );

		// Get the current day of the week.
		$today = $dt->format( 'l' );

		// Get the current timestamp.
		$current_timestamp = (int) strtotime( $dt->format( 'Y-m-d H:i:s' ) );

		// Filter the hours table to get the opening and closing hours for the current day.
		$today_opening_closing_hours = array_filter(
			$hours_table,
			function ( $arr ) use ( $today ) {
				return isset( $arr['name'] ) && strtolower( $arr['name'] ) === strtolower( $today );
			}
		);

		// If there are opening and closing hours for today.
		if ( $today_opening_closing_hours ) {
			// Iterate through each opening/closing time slot for today.
			foreach ( $today_opening_closing_hours as $value ) {
				// if opening and closing time is 00:00, then continue.
				if ( '00' === $value['opening_time_hr'] && '00' === $value['opening_time_min'] && '00' === $value['closing_time_hr'] && '00' === $value['closing_time_min'] ) {
					continue;
				}

				// Get the opening timestamp for the current time slot.
				$opening_timestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );

				// Get the closing timestamp for the current time slot.
				$closing_timestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );

				// Check the status.
				if ( 'open' === $value['status'] ) {
					// Store is closed if current time is before opening or after closing.
					if ( ( $current_timestamp < $opening_timestamp ) || ( $current_timestamp > $closing_timestamp ) ) {
						$store_is_closed = true;
					} else {
						$store_is_closed = false;
					}
				} elseif ( 'closed' === $value['status'] ) {
					if ( ( $current_timestamp > $opening_timestamp ) && ( $current_timestamp < $closing_timestamp ) ) {
						$store_is_closed = true;
					} else {
						$store_is_closed = false;
					}
				}
			}
		}

		return $store_is_closed;
	}

	/**
	 * Checks if the store is going to close soon.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    bool
	 */
	public static function is_store_going_to_close_soon() {
		// check if plugin is active.
		$enabled               = self::get_option( 'enable_manager', 'sochm_basic_settings' );
		$timezone              = self::get_option( 'timezone', 'sochm_basic_settings' );
		$hours_table           = get_option( 'sochm_table_data', array() );
		$store_is_closing_soon = false;

		if ( ! is_array( $hours_table ) || empty( $hours_table ) ) {
			return $store_is_closing_soon;
		}

		$enable_store_going_to_close_soon_notice_enabled = self::get_option( 'enable_store_going_to_close_soon_notice', 'sochm_basic_settings' );

		if ( 'on' !== $enabled || empty( $timezone ) || ! $hours_table || 'on' !== $enable_store_going_to_close_soon_notice_enabled ) {
			return $store_is_closing_soon;
		}

		$minutes_before_store_going_to_close_soon_notice = self::get_option( 'minutes_before_store_going_to_close_soon_notice', 'sochm_basic_settings', 30 );

		$dt = new DateTime( 'now', new DateTimezone( $timezone ) );

		$dt->add( new DateInterval( 'PT' . ( $minutes_before_store_going_to_close_soon_notice * 60 ) . 'S' ) );

		$today = $dt->format( 'l' );

		$current_timestamp = (int) strtotime( $dt->format( 'Y-m-d H:i:s' ) );

		$today_opening_closing_hours = array_filter(
			$hours_table,
			function ( $arr ) use ( $today ) {
				return isset( $arr['name'] ) && strtolower( $arr['name'] ) === strtolower( $today );
			}
		);

		if ( $today_opening_closing_hours ) {
			foreach ( $today_opening_closing_hours as $value ) {
				if ( '00' === $value['opening_time_hr'] && '00' === $value['opening_time_min'] && '00' === $value['closing_time_hr'] && '00' === $value['closing_time_min'] ) {
					continue;
				}

				$opening_timestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );
				$closing_timestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );

				if ( 'open' === $value['status'] ) {
					if ( ( $current_timestamp < $opening_timestamp ) || ( $current_timestamp > $closing_timestamp ) ) {
						$store_is_closing_soon = true;
					} else {
						$store_is_closing_soon = false;
					}
				} elseif ( 'closed' === $value['status'] ) {
					if ( ( $current_timestamp > $opening_timestamp ) && ( $current_timestamp < $closing_timestamp ) ) {
						$store_is_closing_soon = true;
					} else {
						$store_is_closing_soon = false;
					}
				}
			}
		}

		return $store_is_closing_soon;
	}

	/**
	 * Returns the remaining seconds until the store is going to close.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    int
	 */
	public static function store_going_to_close_soon_remaining_seconds() {
		// check if plugin is active.
		$enabled        = self::get_option( 'enable_manager', 'sochm_basic_settings' );
		$timezone       = self::get_option( 'timezone', 'sochm_basic_settings' );
		$hours_table    = get_option( 'sochm_table_data', array() );
		$remaining_time = 0;

		$enable_store_going_to_close_soon_notice_enabled = self::get_option( 'enable_store_going_to_close_soon_notice', 'sochm_basic_settings' );

		if ( 'on' !== $enabled || empty( $timezone ) || ! $hours_table || 'on' !== $enable_store_going_to_close_soon_notice_enabled ) {
			return $remaining_time;
		}

		$minutes_before_store_going_to_close_soon_notice = self::get_option( 'minutes_before_store_going_to_close_soon_notice', 'sochm_basic_settings', 30 );

		$dt_cloned = new DateTime( 'now', new DateTimezone( $timezone ) );
		$dt        = new DateTime( 'now', new DateTimezone( $timezone ) );

		$dt->add( new DateInterval( 'PT' . ( $minutes_before_store_going_to_close_soon_notice * 60 ) . 'S' ) );

		$today             = $dt->format( 'l' );
		$current_timestamp = (int) strtotime( $dt->format( 'Y-m-d H:i:s' ) );

		$today_opening_closing_hours = array_filter(
			$hours_table,
			function ( $arr ) use ( $today ) {
				return isset( $arr['name'] ) && strtolower( $arr['name'] ) === strtolower( $today );
			}
		);

		if ( $today_opening_closing_hours ) {
			foreach ( $today_opening_closing_hours as $value ) {
				if ( '00' === $value['opening_time_hr'] && '00' === $value['opening_time_min'] && '00' === $value['closing_time_hr'] && '00' === $value['closing_time_min'] ) {
					continue;
				}

				$opening_timestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );
				$closing_timestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );

				if ( 'open' === $value['status'] ) {
					if ( ( $current_timestamp < $opening_timestamp ) || ( $current_timestamp > $closing_timestamp ) ) {
						$remaining_time = $closing_timestamp - (int) strtotime( $dt_cloned->format( 'Y-m-d H:i:s' ) );
					}
				} elseif ( 'closed' === $value['status'] ) {
					if ( ( $current_timestamp > $opening_timestamp ) && ( $current_timestamp < $closing_timestamp ) ) {
						$remaining_time = $opening_timestamp - (int) strtotime( $dt_cloned->format( 'Y-m-d H:i:s' ) );
					}
				}
			}
		}

		return round( $remaining_time );
	}

	/**
	 * Calculates the remaining seconds until the store opens.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    int
	 */
	public static function store_opening_remaining_seconds() {
		// check if plugin is active.
		$enabled        = self::get_option( 'enable_manager', 'sochm_basic_settings' );
		$timezone       = self::get_option( 'timezone', 'sochm_basic_settings' );
		$hours_table    = get_option( 'sochm_table_data', array() );
		$remaining_time = 0;

		if ( ! is_array( $hours_table ) || empty( $hours_table ) ) {
			return $remaining_time;
		}

		if ( 'on' !== $enabled || empty( $timezone ) || ! $hours_table ) {
			return $remaining_time;
		}

		$today_opening_closing_hours = array();

		$i = 0;

		$store_is_open = false;

		while ( ! $store_is_open ) {
			$dt                = ( new DateTime( 'now', new DateTimezone( $timezone ) ) )->add( new DateInterval( "P{$i}D" ) );
			$today             = $dt->format( 'l' );
			$dt_cloned         = new DateTime( 'now', new DateTimezone( $timezone ) );
			$current_timestamp = (int) strtotime( $dt_cloned->format( 'Y-m-d H:i:s' ) );

			$today_opening_closing_hours = array_filter(
				$hours_table,
				function ( $arr ) use ( $today ) {
					return isset( $arr['name'] ) && strtolower( $arr['name'] ) === strtolower( $today );
				}
			);

			$store_is_open = self::is_store_open( $today_opening_closing_hours, $current_timestamp, $dt->format( 'Y-m-d' ) );

			if ( ! $store_is_open ) {
				// phpcs:ignore Universal.Operators.DisallowStandalonePostIncrementDecrement.PostIncrementFound
				$i++;
			}

			if ( $i > 60 ) {
				break;
			}
		}

		return intval( $store_is_open ) ? round( intval( $store_is_open ) - $current_timestamp ) : 0;
	}

	/**
	 * Checks if the store is open and returns the opening timestamp if it is.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     array  $today_opening_closing_hours Data of today opening/closing hours.
	 * @param     int    $current_timestamp Current timestamp.
	 * @param     string $current_date      Current date.
	 * @return    int|bool                  True if store is open.
	 */
	public static function is_store_open( $today_opening_closing_hours, $current_timestamp, $current_date ) {
		$store_is_open = 0;

		if ( $today_opening_closing_hours ) {
			foreach ( $today_opening_closing_hours as $value ) {
				if ( '00' === $value['opening_time_hr'] && '00' === $value['opening_time_min'] && '00' === $value['closing_time_hr'] && '00' === $value['closing_time_min'] ) {
					continue;
				}

				$opening_timestamp = strtotime( $current_date . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );
				$closing_timestamp = strtotime( $current_date . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );

				if ( 'open' === $value['status'] && $opening_timestamp > $current_timestamp ) {
					return $opening_timestamp;
				} elseif ( 'closed' === $value['status'] ) {
					// same day.
					if ( gmdate( 'l', $current_timestamp ) === gmdate( 'l', $opening_timestamp ) ) {
						return $closing_timestamp;
					}

					if ( $opening_timestamp > $current_timestamp ) {
						return strtotime( $current_date . ' 00:00:00' );
					}
				}
			}
		}

		return $store_is_open;
	}

	/**
	 * Retrieves the store's table settings.
	 *
	 * This function fetches the store's table data, which includes the week days,
	 * their opening and closing hours, and other related settings. It then formats
	 * this data into a structured array for use in the application.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    array An array containing the formatted table settings.
	 */
	public static function get_table_settings() {
		// Get the raw week days data from the database.
		$week_days       = get_option( 'sochm_table_data', array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ) );
		$week_days_table = array();

		// If the week days data is not an array or is empty, return an empty array.
		if ( ! is_array( $week_days ) || empty( $week_days ) ) {
			return $week_days_table;
		}

		// Process each week day.
		foreach ( $week_days as $week_day ) {
			$tmp = array();

			// Set the week day name.
			$tmp['week_name'] = isset( $week_day['name'] ) ? $week_day['name'] : $week_day;

			// Set the full week day name (e.g., "Monday").
			$tmp['week_full_name'] = ucfirst( $tmp['week_name'] );

			// Set the status (open/closed) for the week day.
			$tmp['status'] = isset( $week_day['status'] ) ? $week_day['status'] : 'open';

			// Generate arrays for opening and closing hours and minutes.
			foreach ( range( 0, 23 ) as $num ) {
				$tmp['opening_time_hr'][ $num ] = sprintf( '%02d', $num );
				$tmp['closing_time_hr'][ $num ] = sprintf( '%02d', $num );

				// Set the selected opening hour if it matches the current hour.
				if ( isset( $week_day['opening_time_hr'] ) && sprintf( '%02d', $num ) === $week_day['opening_time_hr'] ) {
					$tmp['selected_opening_time_hr'] = sprintf( '%02d', $num );
				}

				// Set the selected closing hour if it matches the current hour.
				if ( isset( $week_day['closing_time_hr'] ) && sprintf( '%02d', $num ) === $week_day['closing_time_hr'] ) {
					$tmp['selected_closing_time_hr'] = sprintf( '%02d', $num );
				}
			}

			foreach ( range( 0, 59 ) as $num ) {
				$tmp['opening_time_min'][ $num ] = sprintf( '%02d', $num );
				$tmp['closing_time_min'][ $num ] = sprintf( '%02d', $num );

				// Set the selected opening minute if it matches the current minute.
				if ( isset( $week_day['opening_time_min'] ) && sprintf( '%02d', $num ) === $week_day['opening_time_min'] ) {
					$tmp['selected_opening_time_min'] = sprintf( '%02d', $num );
				}

				// Set the selected closing minute if it matches the current minute.
				if ( isset( $week_day['closing_time_min'] ) && sprintf( '%02d', $num ) === $week_day['closing_time_min'] ) {
					$tmp['selected_closing_time_min'] = sprintf( '%02d', $num );
				}
			}

			// Add the processed week day data to the main array.
			$week_days_table[] = $tmp;
		}

		return $week_days_table;
	}

	/**
	 * Generates the HTML for the store closing/opening timer based on the selected design.
	 *
	 * This function takes the remaining time components (days, hours, minutes, seconds) and the timer design
	 * option, and returns the corresponding HTML markup for displaying the timer.  It handles different
	 * timer designs, including default, boxed, flipping, and circular designs.  It also includes the
	 * "Remaining Time To Open/Close" text, and makes use of the translation function.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $timer_design The selected timer design (0-4).
	 * @param     int    $seconds      The number of seconds remaining.
	 * @param     string $type         The type of timer (remaining opening time or soon to open time).
	 * @return    string               The HTML markup for the timer.
	 */
	public static function get_timer_html( $timer_design, $seconds, $type ) {
		if ( 'store_is_going_to_open_soon_remaining_time' === $type ) {
			$time_txt = apply_filters( 'sochm_store_open_remaining_time_text', __( 'Remaining Time To Open', 'store-opening-closing-hours-manager' ) );
		} elseif ( 'store_is_going_to_close_soon_remaining_time' === $type ) {
			$time_txt = apply_filters( 'sochm_store_close_remaining_time_text', __( 'Remaining Time To Close', 'store-opening-closing-hours-manager' ) );
		}

		$time_days    = (int) ( $seconds / 86400 );
		$time_hours   = (int) ( $seconds / 3600 );
		$time_minutes = (int) ( $seconds / 60 ) % 60;
		$time_seconds = $seconds % 60;
		$html         = '';

		// Data array to pass to all templates.
		$template_data = array(
			'time_txt'     => $time_txt,
			'type'         => $type,
			'time_days'    => $time_days,
			'time_hours'   => $time_hours,
			'time_minutes' => $time_minutes,
			'time_seconds' => $time_seconds,
			'seconds'      => $seconds,
		);

		switch ( $timer_design ) {
			case '0':
				$html = self::get_template_part( 'timer-design-default', $template_data );
				break;
			case '1':
				$html = self::get_template_part( 'timer-design-boxed', $template_data );
				break;
			case '2':
				$html = self::get_template_part( 'timer-design-boxed-with-flipping', $template_data );
				break;
			case '3':
				$html = self::get_template_part( 'timer-design-circular-border', $template_data );
				break;
			case '4':
				$html = self::get_template_part( 'timer-design-circular-border-with-filling', $template_data );
				break;
		}

		/**
		 * Filters the HTML output for the timer display.
		 *
		 * @since     2.0.0
		 * @param     string $html         The generated HTML for the timer.
		 * @param     string $timer_design The selected timer design (e.g., '0', '1', '2').
		 * @param     int    $seconds      The total remaining seconds for the timer.
		 * @param     string $type         The type of timer ('store_is_going_to_open_soon_remaining_time' or
		 *                                 'store_is_going_to_close_soon_remaining_time').
		 * @param     string $time_txt     The localized text for "Remaining Time To Open/Close".
		 * @param     int    $time_days    The number of remaining days.
		 * @param     int    $time_hours   The number of remaining hours.
		 * @param     int    $time_minutes The number of remaining minutes.
		 * @param     int    $time_seconds The number of remaining seconds.
		 */
		return apply_filters(
			'sochm_get_timer_html',
			$html,
			$timer_design,
			$seconds,
			$type,
			$time_txt,
			$time_days,
			$time_hours,
			$time_minutes,
			$time_seconds
		);
	}

	/**
	 * Generates the HTML for a store notice based on the notice type.
	 *
	 * This function constructs the HTML markup for different types of store notices (toast, dialog,
	 * sticky header, sticky footer, single page) based on the provided notice type, message,
	 * remaining time, and styling options.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $notice_type           The type of notice to generate (0-4).
	 * @param     string $message               The main message text for the notice.
	 * @param     string $remaining_time_html   The HTML for the remaining time until opening/closing.
	 * @param     string $notice_boxbg_color    The background color of the notice box.
	 * @param     string $notice_text_color     The text color of the notice.
	 * @param     bool   $is_store_closing      Flag indicating if the notice is for store closing.
	 * @return    array                         An array containing the HTML for the generated notice elements.
	 *                                          The array keys are: 'toastHtml', 'dialogHtml', 'stickyHeaderHtml',
	 *                                          'stickyFooterHtml', and 'singlePage'.  Values may be empty strings
	 *                                          if the corresponding notice type is not selected.
	 */
	public static function generate_notice_html( $notice_type, $message, $remaining_time_html, $notice_boxbg_color, $notice_text_color, $is_store_closing = true ) {
		$sochm_script = array(
			'toastHtml'        => '',
			'dialogHtml'       => '',
			'stickyHeaderHtml' => '',
			'stickyFooterHtml' => '',
			'singlePage'       => '',
		);

		$message_with_time = $message . $remaining_time_html;
		$toast_class       = $is_store_closing ? 'store_going_to_close_soon_toast_message' : 'store_going_to_open_soon_toast_message';

		// Data array to pass to all notice templates.
		$template_data = array(
			'message'             => $message,
			'remaining_time_html' => $remaining_time_html,
			'message_with_time'   => $message_with_time,
			'notice_boxbg_color'  => $notice_boxbg_color,
			'notice_text_color'   => $notice_text_color,
			'is_store_closing'    => $is_store_closing,
			'toast_class'         => $toast_class,
		);

		switch ( $notice_type ) {
			case '0':
				$sochm_script['toastHtml']    = self::get_template_part( 'notice-type-toast', $template_data );
				$sochm_script['toastMessage'] = $message_with_time;
				$sochm_script['toastType']    = 'error'; // This specific 'toastType' is not part of the HTML itself, so it remains here.
				break;
			case '1':
				$sochm_script['dialogHtml'] = self::get_template_part( 'notice-type-dialog', $template_data );
				break;
			case '2':
				$template_data['top_value']       = function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() ? '32px' : '0';
				$sochm_script['stickyHeaderHtml'] = self::get_template_part( 'notice-type-sticky-header', $template_data );
				break;
			case '3':
				$sochm_script['stickyFooterHtml'] = self::get_template_part( 'notice-type-sticky-footer', $template_data );
				break;
			case '4':
				$sochm_script['singlePageHtml'] = self::get_template_part( 'notice-type-single-page', $template_data );
				break;
			case '5':
				$sochm_script['wcNoticeHtml'] = '<li>' . $message_with_time . '</li>';
				break;
		}

		/**
		 * Filters the array of script data for the Coming Soon/Maintenance Mode.
		 *
		 * @since     2.0.0
		 * @param     array  $sochm_script        The array of script data.
		 * @param     string $message             The main message text.
		 * @param     string $remaining_time_html The HTML for the remaining time.
		 * @param     bool   $is_store_closing    True if the store is going to close, false if opening.
		 * @param     string $notice_type         The type of notice (e.g., '0' for toast, '1' for dialog).
		 * @param     string $notice_boxbg_color  The background color of the notice box.
		 * @param     string $notice_text_color   The text color of the notice.
		 */
		return apply_filters(
			'sochm_script_data',
			$sochm_script,
			$message,
			$remaining_time_html,
			$is_store_closing,
			$notice_type,
			$notice_boxbg_color,
			$notice_text_color
		);
	}

	/**
	 * Get the currently used cache system.
	 *
	 * This method checks for the presence of various caching plugins and hosting
	 * environments to identify the active caching system on the WordPress site.
	 * It returns an array containing the key and name of the detected cache system.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    array An associative array with 'key' and 'name' of the used cache system.
	 *                  Returns an empty array if no known cache system is detected.
	 */
	public static function get_used_cache_system() {
		$used_cache_system = array();

		switch ( true ) {
			case class_exists( 'Breeze_Admin' ):
				$used_cache_system['key']  = 'breeze';
				$used_cache_system['name'] = __( 'Breeze', 'store-opening-closing-hours-manager' );
				break;
			case class_exists( 'Cache_Enabler' ):
				$used_cache_system['key']  = 'cacheenabler';
				$used_cache_system['name'] = 'Cache Enabler';
				break;
			case class_exists( '\WPaaS\Cache' ):
				$used_cache_system['key']  = 'godaddy';
				$used_cache_system['name'] = 'GoDaddy Cache';
				break;
			case class_exists( '\Kinsta\Cache' ):
				$used_cache_system['key']  = 'kinsta';
				$used_cache_system['name'] = 'Kinsta Cache';
				break;
			case defined( 'LSCWP_V' ):
				$used_cache_system['key']  = 'litespeed';
				$used_cache_system['name'] = 'LiteSpeed Cache';
				break;
			case function_exists( 'nitropack_sdk' ):
				$used_cache_system['key']  = 'nitropack';
				$used_cache_system['name'] = 'NitroPack';
				break;
			case function_exists( 'sg_cachepress_purge_cache' ):
				$used_cache_system['key']  = 'siteground';
				$used_cache_system['name'] = 'Speed Optimizer (SiteGround)';
				break;
			case is_plugin_active( 'wp-cloudflare-page-cache/wp-cloudflare-super-page-cache.php' ):
				$used_cache_system['key']  = 'superpagecache';
				$used_cache_system['name'] = 'Super Page Cache';
				break;
			case method_exists( 'WpFastestCache', 'deleteCache' ):
				$used_cache_system['key']  = 'wp_fastest_cache';
				$used_cache_system['name'] = 'WP Fastest Cache';
				break;
			case class_exists( 'WP_Optimize' ) && defined( 'WPO_PLUGIN_MAIN_PATH' ):
				$used_cache_system['key']  = 'wp_optimize';
				$used_cache_system['name'] = 'WP Optimize';
				break;
			case function_exists( 'wp_cache_clean_cache' ):
				$used_cache_system['key']  = 'wp_cache';
				$used_cache_system['name'] = 'WP Super Cache';
				break;
			case function_exists( 'w3tc_pgcache_flush' ):
				$used_cache_system['key']  = 'w3tc';
				$used_cache_system['name'] = 'W3 Total Cache';
				break;
			case function_exists( 'rocket_clean_domain' ):
				$used_cache_system['key']  = 'wp_rocket';
				$used_cache_system['name'] = 'WP Rocket';
				break;
			case class_exists( 'WpeCommon' ):
				$used_cache_system['key']  = 'wpengine';
				$used_cache_system['name'] = 'WPEngine Cache';
				break;
			default:
				break;
		}

		return $used_cache_system;
	}
}
