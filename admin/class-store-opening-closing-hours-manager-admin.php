<?php
/**
 * This file contains the definition of the Store_Opening_Closing_Hours_Manager_Admin class, which
 * is used to load the plugin's admin-specific functionality.
 *
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/admin
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Store_Opening_Closing_Hours_Manager_Admin {
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
	 * The plugin options api wrapper object.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       array $settings_api Holds the plugin options api wrapper class object.
	 */
	private $settings_api;

	/**
	 * The list of timezones around the world.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       array $timezones Holds the list of timezones.
	 */
	private $timezones;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of this plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->settings_api = new Sajjad_Dev_Settings_API();

		$this->timezones = array(
			'Pacific/Midway'       => '(GMT-11:00) Midway Island',
			'US/Samoa'             => '(GMT-11:00) Samoa',
			'US/Hawaii'            => '(GMT-10:00) Hawaii',
			'US/Alaska'            => '(GMT-09:00) Alaska',
			'US/Pacific'           => '(GMT-08:00) Pacific Time (US &amp; Canada)',
			'America/Tijuana'      => '(GMT-08:00) Tijuana',
			'US/Arizona'           => '(GMT-07:00) Arizona',
			'US/Mountain'          => '(GMT-07:00) Mountain Time (US &amp; Canada)',
			'America/Chihuahua'    => '(GMT-07:00) Chihuahua',
			'America/Mazatlan'     => '(GMT-07:00) Mazatlan',
			'America/Mexico_City'  => '(GMT-06:00) Mexico City',
			'America/Monterrey'    => '(GMT-06:00) Monterrey',
			'Canada/Saskatchewan'  => '(GMT-06:00) Saskatchewan',
			'US/Central'           => '(GMT-06:00) Central Time (US &amp; Canada)',
			'US/Eastern'           => '(GMT-05:00) Eastern Time (US &amp; Canada)',
			'US/East-Indiana'      => '(GMT-05:00) Indiana (East)',
			'America/Bogota'       => '(GMT-05:00) Bogota',
			'America/Lima'         => '(GMT-05:00) Lima',
			'America/Caracas'      => '(GMT-04:30) Caracas',
			'Canada/Atlantic'      => '(GMT-04:00) Atlantic Time (Canada)',
			'America/La_Paz'       => '(GMT-04:00) La Paz',
			'America/Santiago'     => '(GMT-04:00) Santiago',
			'Canada/Newfoundland'  => '(GMT-03:30) Newfoundland',
			'America/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
			'Greenland'            => '(GMT-03:00) Greenland',
			'Atlantic/Stanley'     => '(GMT-02:00) Stanley',
			'Atlantic/Azores'      => '(GMT-01:00) Azores',
			'Atlantic/Cape_Verde'  => '(GMT-01:00) Cape Verde Is.',
			'Europe/Dublin'        => '(GMT+00:00) Dublin',
			'Europe/Lisbon'        => '(GMT+00:00) Lisbon',
			'Europe/London'        => '(GMT+00:00) London',
			'Africa/Monrovia'      => '(GMT+00:00) Monrovia',
			'Africa/Casablanca'    => '(GMT+01:00) Casablanca',
			'Europe/Amsterdam'     => '(GMT+01:00) Amsterdam',
			'Europe/Belgrade'      => '(GMT+01:00) Belgrade',
			'Europe/Berlin'        => '(GMT+01:00) Berlin',
			'Europe/Bratislava'    => '(GMT+01:00) Bratislava',
			'Europe/Brussels'      => '(GMT+01:00) Brussels',
			'Europe/Budapest'      => '(GMT+01:00) Budapest',
			'Europe/Copenhagen'    => '(GMT+01:00) Copenhagen',
			'Europe/Ljubljana'     => '(GMT+01:00) Ljubljana',
			'Europe/Madrid'        => '(GMT+01:00) Madrid',
			'Europe/Paris'         => '(GMT+01:00) Paris',
			'Europe/Prague'        => '(GMT+01:00) Prague',
			'Europe/Rome'          => '(GMT+01:00) Rome',
			'Europe/Sarajevo'      => '(GMT+01:00) Sarajevo',
			'Europe/Skopje'        => '(GMT+01:00) Skopje',
			'Europe/Stockholm'     => '(GMT+01:00) Stockholm',
			'Europe/Vienna'        => '(GMT+01:00) Vienna',
			'Europe/Warsaw'        => '(GMT+01:00) Warsaw',
			'Europe/Zagreb'        => '(GMT+01:00) Zagreb',
			'Europe/Athens'        => '(GMT+02:00) Athens',
			'Europe/Bucharest'     => '(GMT+02:00) Bucharest',
			'Africa/Cairo'         => '(GMT+02:00) Cairo',
			'Africa/Harare'        => '(GMT+02:00) Harare',
			'Europe/Helsinki'      => '(GMT+02:00) Helsinki',
			'Europe/Istanbul'      => '(GMT+02:00) Istanbul',
			'Asia/Jerusalem'       => '(GMT+02:00) Jerusalem',
			'Europe/Kiev'          => '(GMT+02:00) Kyiv',
			'Europe/Minsk'         => '(GMT+02:00) Minsk',
			'Europe/Riga'          => '(GMT+02:00) Riga',
			'Europe/Sofia'         => '(GMT+02:00) Sofia',
			'Europe/Tallinn'       => '(GMT+02:00) Tallinn',
			'Europe/Vilnius'       => '(GMT+02:00) Vilnius',
			'Asia/Baghdad'         => '(GMT+03:00) Baghdad',
			'Asia/Kuwait'          => '(GMT+03:00) Kuwait',
			'Africa/Nairobi'       => '(GMT+03:00) Nairobi',
			'Asia/Riyadh'          => '(GMT+03:00) Riyadh',
			'Europe/Moscow'        => '(GMT+03:00) Moscow',
			'Asia/Tehran'          => '(GMT+03:30) Tehran',
			'Asia/Baku'            => '(GMT+04:00) Baku',
			'Europe/Volgograd'     => '(GMT+04:00) Volgograd',
			'Asia/Muscat'          => '(GMT+04:00) Muscat',
			'Asia/Tbilisi'         => '(GMT+04:00) Tbilisi',
			'Asia/Yerevan'         => '(GMT+04:00) Yerevan',
			'Asia/Kabul'           => '(GMT+04:30) Kabul',
			'Asia/Karachi'         => '(GMT+05:00) Karachi',
			'Asia/Tashkent'        => '(GMT+05:00) Tashkent',
			'Asia/Kolkata'         => '(GMT+05:30) Kolkata',
			'Asia/Kathmandu'       => '(GMT+05:45) Kathmandu',
			'Asia/Yekaterinburg'   => '(GMT+06:00) Ekaterinburg',
			'Asia/Almaty'          => '(GMT+06:00) Almaty',
			'Asia/Dhaka'           => '(GMT+06:00) Dhaka',
			'Asia/Novosibirsk'     => '(GMT+07:00) Novosibirsk',
			'Asia/Bangkok'         => '(GMT+07:00) Bangkok',
			'Asia/Jakarta'         => '(GMT+07:00) Jakarta',
			'Asia/Krasnoyarsk'     => '(GMT+08:00) Krasnoyarsk',
			'Asia/Chongqing'       => '(GMT+08:00) Chongqing',
			'Asia/Hong_Kong'       => '(GMT+08:00) Hong Kong',
			'Asia/Kuala_Lumpur'    => '(GMT+08:00) Kuala Lumpur',
			'Australia/Perth'      => '(GMT+08:00) Perth',
			'Asia/Singapore'       => '(GMT+08:00) Singapore',
			'Asia/Taipei'          => '(GMT+08:00) Taipei',
			'Asia/Ulaanbaatar'     => '(GMT+08:00) Ulaan Bataar',
			'Asia/Urumqi'          => '(GMT+08:00) Urumqi',
			'Asia/Irkutsk'         => '(GMT+09:00) Irkutsk',
			'Asia/Seoul'           => '(GMT+09:00) Seoul',
			'Asia/Tokyo'           => '(GMT+09:00) Tokyo',
			'Australia/Adelaide'   => '(GMT+09:30) Adelaide',
			'Australia/Darwin'     => '(GMT+09:30) Darwin',
			'Asia/Yakutsk'         => '(GMT+10:00) Yakutsk',
			'Australia/Brisbane'   => '(GMT+10:00) Brisbane',
			'Australia/Canberra'   => '(GMT+10:00) Canberra',
			'Pacific/Guam'         => '(GMT+10:00) Guam',
			'Australia/Hobart'     => '(GMT+10:00) Hobart',
			'Australia/Melbourne'  => '(GMT+10:00) Melbourne',
			'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
			'Australia/Sydney'     => '(GMT+10:00) Sydney',
			'Asia/Vladivostok'     => '(GMT+11:00) Vladivostok',
			'Asia/Magadan'         => '(GMT+12:00) Magadan',
			'Pacific/Auckland'     => '(GMT+12:00) Auckland',
			'Pacific/Fiji'         => '(GMT+12:00) Fiji',
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		$current_screen = get_current_screen();

		// check if current page is plugin settings page.
		if ( 'toplevel_page_store-opening-closing-hours-manager' === $current_screen->id ) {
			wp_enqueue_script( $this->plugin_name, STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery', 'wp-util' ), $this->version, false );

			$week_days_table = Store_Opening_Closing_Hours_Manager::get_table_settings();

			$timezone = Store_Opening_Closing_Hours_Manager::get_option( 'timezone', 'sochm_basic_settings', 'Asia/Dhaka' );
			$date_now = new DateTime( 'now', new DateTimezone( $timezone ) );
			$today    = strtolower( $date_now->format( 'l' ) );

			wp_localize_script(
				$this->plugin_name,
				'StoreOpeningClosingHoursManager',
				array(
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'savingText'       => __( 'Saving... Please Wait!', 'store-opening-closing-hours-manager' ),
					'savedText'        => __( 'Saved!', 'store-opening-closing-hours-manager' ),
					'saveText'         => __( 'Save Changes', 'store-opening-closing-hours-manager' ),
					'addBtnText'       => __( 'Add', 'store-opening-closing-hours-manager' ),
					'removeBtnText'    => __( 'Remove', 'store-opening-closing-hours-manager' ),
					'statusOpen'       => __( 'Open', 'store-opening-closing-hours-manager' ),
					'statusClosed'     => __( 'Closed', 'store-opening-closing-hours-manager' ),
					'confirnDeleteMsg' => __( 'Are you sure, you want to remove this?', 'store-opening-closing-hours-manager' ),
					'weekDaysTable'    => wp_json_encode( $week_days_table ),
					'_wpnonce'         => wp_create_nonce( 'sochm_ajax_nonce' ),
					'today'            => $today,
					'todayText'        => __( 'Today', 'store-opening-closing-hours-manager' ),
				)
			);
		}
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $links The existing array of plugin action links.
	 * @return    array $links The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=store-opening-closing-hours-manager' ) ), __( 'Settings', 'store-opening-closing-hours-manager' ) );

		return $links;
	}

	/**
	 * Adds the plugin settings page to the WordPress dashboard menu.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Store O/C Hours Manager', 'store-opening-closing-hours-manager' ),
			__( 'Store O/C Hours Manager', 'store-opening-closing-hours-manager' ),
			'manage_options',
			'store-opening-closing-hours-manager',
			array( $this, 'menu_page' ),
			'dashicons-admin-tools'
		);
	}

	/**
	 * Renders the plugin menu page content.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function menu_page() {
		$this->settings_api->show_forms();
	}

	/**
	 * Initializes admin-specific functionality.
	 *
	 * This function is hooked to the 'admin_init' action and is used to perform
	 * various administrative tasks, such as registering settings, enqueuing scripts,
	 * or adding admin notices.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_init() {
		// set the settings.
		$this->settings_api->set_sections( $this->get_settings_sections() );

		$this->settings_api->set_fields( $this->get_settings_fields() );

		// initialize settings.
		$this->settings_api->admin_init();
	}

	/**
	 * Returns the settings sections for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings sections, where each section is an array
	 *                  with 'id' and 'title' keys.
	 */
	public function get_settings_sections() {
		$settings_sections = array(
			array(
				'id'    => 'sochm_basic_settings',
				'title' => __( 'General Settings', 'store-opening-closing-hours-manager' ),
			),
			array(
				'id'    => 'sochm_hours_table',
				'title' => __( 'Opening Closing Hours Table', 'store-opening-closing-hours-manager' ),
			),
		);

		/**
		 * Filters the plugin settings sections.
		 *
		 * This filter allows you to modify the plugin settings sections.
		 * You can use this filter to add/remove/edit any settings sections.
		 *
		 * @since     2.0.0
		 * @param     array $settings_sections Default settings sections.
		 * @return    array $settings_sections Modified settings sections.
		 */
		return apply_filters( 'sochm_settings_sections', $settings_sections );
	}

	/**
	 * Returns all the settings fields for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings fields, organized by section ID.  Each
	 *                  section ID is a key in the array, and the value is an array
	 *                  of settings fields for that section. Each settings field is
	 *                  an array with 'name', 'label', 'type', 'desc', and other keys
	 *                  depending on the field type.
	 */
	public function get_settings_fields() {
		ob_start();

			require STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . '/admin/views/plugin-admin-display.php';

		$table = ob_get_clean();

		$used_cache_system = Store_Opening_Closing_Hours_Manager::get_used_cache_system();

		$cache_plugins = array(
			'breeze'           => __( 'Breeze Cache', 'store-opening-closing-hours-manager' ),
			'cacheenabler'     => __( 'Cache Enabler', 'store-opening-closing-hours-manager' ),
			'godaddy'          => __( 'GoDaddy Cache', 'store-opening-closing-hours-manager' ),
			'kinsta'           => __( 'Kinsta Cache', 'store-opening-closing-hours-manager' ),
			'litespeed'        => __( 'LiteSpeed Cache', 'store-opening-closing-hours-manager' ),
			'nitropack'        => __( 'NitroPack', 'store-opening-closing-hours-manager' ),
			'siteground'       => __( 'Speed Optimizer', 'store-opening-closing-hours-manager' ),
			'superpagecache'   => __( 'Super Page Cache', 'store-opening-closing-hours-manager' ),
			'wp_fastest_cache' => __( 'WP Fastest Cache', 'store-opening-closing-hours-manager' ),
			'wp_optimize'      => __( 'WP Optimize Cache', 'store-opening-closing-hours-manager' ),
			'wp_cache'         => __( 'WP Super Cache', 'store-opening-closing-hours-manager' ),
			'w3tc'             => __( 'W3 Total Cache', 'store-opening-closing-hours-manager' ),
			'wp_rocket'        => __( 'WP Rocket', 'store-opening-closing-hours-manager' ),
			'wpengine'         => __( 'WPEngine Cache', 'store-opening-closing-hours-manager' ),
		);

		$compatibility_plugins = '<ul>';
		foreach ( $cache_plugins as $key => $label ) {
			$is_active = ( isset( $used_cache_system['key'] ) && $used_cache_system['key'] === $key ) ? __( ' Currently Active', 'store-opening-closing-hours-manager' ) : '';

			$compatibility_plugins .= '<li>' . esc_html( $label . ' ✅' . $is_active ) . '</li>';
		}
		$compatibility_plugins .= '</ul>';

		$settings_fields = array(
			'sochm_basic_settings' => array(
				array(
					'name'  => 'enable_manager',
					'label' => __( 'Enable The Manager', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will enable the plugin functionality.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'enable_widget',
					'label' => __( 'Enable Widget', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will add a new widget to show Store Opening & Closing Hours in a table.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'close_store',
					'label' => __( 'Close The Store Manually', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will put the store in a closed state immediately regardless of the time.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'enable_timer',
					'label' => __( 'Enable Timer', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will add a countdown timer for remaining time to open the store & store closing soon notice.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'    => 'timer_design',
					'label'   => __( 'Timer Design', 'store-opening-closing-hours-manager' ),
					'type'    => 'select',
					'options' => array(
						'0' => __( 'Default', 'store-opening-closing-hours-manager' ),
						'1' => __( 'Boxed', 'store-opening-closing-hours-manager' ),
						'2' => __( 'Boxed With Flipping', 'store-opening-closing-hours-manager' ),
						'3' => __( 'Circular Border', 'store-opening-closing-hours-manager' ),
						'4' => __( 'Circular Border With Filling', 'store-opening-closing-hours-manager' ),
					),
					'default' => '0',
					'desc'    => __( 'Select the timer design type for front end user to see when store is closed.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'    => 'timezone',
					'label'   => __( 'Timezone', 'store-opening-closing-hours-manager' ),
					'type'    => 'select',
					'options' => $this->timezones,
					'desc'    => __( 'Select timezone for your store to use in calculating the store opening/closing hours.', 'store-opening-closing-hours-manager' ),
					'default' => 'Asia/Dhaka',
				),
				array(
					'name'  => 'show_notice_in_wp_admin',
					'label' => __( 'Show Notice In WP Admin', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will add a new "Menu Bar" showing Open/Closed status of store for the current time.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'show_notice_in_front',
					'label' => __( 'Show Notice In Front', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will show notice in front.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'    => 'notice_type',
					'label'   => __( 'Notice Type', 'store-opening-closing-hours-manager' ),
					'type'    => 'select',
					'options' => array(
						'0' => __( 'Toast', 'store-opening-closing-hours-manager' ),
						'1' => __( 'Dialog', 'store-opening-closing-hours-manager' ),
						'2' => __( 'Sticky Header', 'store-opening-closing-hours-manager' ),
						'3' => __( 'Sticky Footer', 'store-opening-closing-hours-manager' ),
						'4' => __( 'Static Single Page', 'store-opening-closing-hours-manager' ),
						'5' => __( 'Woocommerce Notice', 'store-opening-closing-hours-manager' ),
					),
					'default' => '4',
					'desc'    => __( 'Select the notice type for front end user to see when store is closed. Default is Static Single Page.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'    => 'notice_message',
					'label'   => __( 'Store Closed Notice Message', 'store-opening-closing-hours-manager' ),
					'type'    => 'textarea',
					'desc'    => __( 'Add notice message for front end user to see when store is closed.', 'store-opening-closing-hours-manager' ),
					'default' => __( "Store is closed right now! You can't purchase any items. Please come back when store is open again!", 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'enable_store_going_to_close_soon_notice',
					'label' => __( "Enable 'Store Going To Close Soon Notice'", 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will show a notice that store is closing soon.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'    => 'minutes_before_store_going_to_close_soon_notice',
					'label'   => __( "Minutes Before Enabling 'Store Going To Close Soon Notice'", 'store-opening-closing-hours-manager' ),
					'type'    => 'number',
					'desc'    => __( 'How many minutes before do you want to show the notice to end user?', 'store-opening-closing-hours-manager' ),
					'default' => 30,
				),
				array(
					'name'    => 'store_going_to_close_soon_notice_message',
					'label'   => __( "'Store Going To Close Soon Notice' Message", 'store-opening-closing-hours-manager' ),
					'type'    => 'textarea',
					'desc'    => __( 'Add notice message for front end user to see when store is about to close.', 'store-opening-closing-hours-manager' ),
					'default' => __( 'Store is going to close soon! Please complete your purchase before store get closed!', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'auto_clear_carts',
					'label' => __( 'Auto Clear Existed Carts', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Do you want to clear old already added carts products when store is closed?', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'disable_checkout',
					'label' => __( 'Disable Checkout', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Do you want to disable the checkout option when store is closed?', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'remove_proceed_to_checkout_button',
					'label' => __( 'Remove The "Proceed To Checkout" Button', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Remove The "Proceed To Checkout" Button Entirely When Store is Closed.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'disable_add_to_cart',
					'label' => __( 'Disable Add To Cart', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Do you want to prevent user from adding products to cart when store is closed?', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'  => 'remove_add_to_cart_button',
					'label' => __( 'Remove The "Add To Cart" Button', 'store-opening-closing-hours-manager' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Remove The "Add To Cart" Button Entirely When Store is Closed.', 'store-opening-closing-hours-manager' ),
				),
				array(
					'name'    => 'notice_text_color',
					'label'   => __( 'Notice Text Color', 'store-opening-closing-hours-manager' ),
					'type'    => 'color',
					'desc'    => __( 'What Color Should be the notice text? Default is White #FFFFFF.', 'store-opening-closing-hours-manager' ),
					'default' => '#FF0000',
				),
				array(
					'name'    => 'notice_boxbg_color',
					'label'   => __( 'Notice Box Background Color', 'store-opening-closing-hours-manager' ),
					'type'    => 'color',
					'desc'    => __( 'What Color Should be the notice Box Background? Default is Red #FF0000.', 'store-opening-closing-hours-manager' ),
					'default' => '#FFFFFF',
				),
				array(
					'name'  => 'clear_cache',
					'label' => __( 'Compatibity With Cache Plugins', 'store-opening-closing-hours-manager' ),
					'type'  => 'html',
					'desc'  => sprintf(
						'<strong>%s</strong>%s',
						__( 'Works with the following cache plugins:', 'store-opening-closing-hours-manager' ),
						$compatibility_plugins,
					),
				),
			),
			'sochm_hours_table'    => array(
				array(
					'name' => 'hours_table',
					'type' => 'html',
					'desc' => $table,
				),
			),
		);

		/**
		 * Filters the plugin settings fields.
		 *
		 * This filter allows you to modify the plugin settings fields.
		 * You can use this filter to add/remove/edit any settings field.
		 *
		 * @since     2.0.0
		 * @param     array $settings_fields Default settings fields.
		 * @return    array $settings_fields Modified settings fields.
		 */
		return apply_filters( 'sochm_settings_fields', $settings_fields );
	}

	/**
	 * Displays admin notices in the admin area.
	 *
	 * This function checks if the required plugin is active.
	 * If not, it displays a warning notice and deactivates the current plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_notices() {
		// Check if required plugin is active.
		if ( ! class_exists( 'WooCommerce', false ) ) {
			sprintf(
				'<div class="notice notice-warning is-dismissible"><p>%s <a href="%s">%s</a> %s</p></div>',
				__( 'Store Opening Closing Hours Manager requires', 'store-opening-closing-hours-manager' ),
				esc_url( 'https://wordpress.org/plugins/woocommerce/' ),
				__( 'WooCommerce', 'store-opening-closing-hours-manager' ),
				__( 'plugin to be active!', 'store-opening-closing-hours-manager' ),
			);

			// Deactivate the plugin.
			deactivate_plugins( STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_BASENAME );
		}
	}

	/**
	 * Adds a menu item to the WordPress admin bar indicating the store's open/closed status.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $wp_admin_bar class WP_Admin_Bar object.
	 */
	public function admin_bar_menu( $wp_admin_bar ) {
		// check if plugin is enabled.
		$enabled        = Store_Opening_Closing_Hours_Manager::get_option( 'enable_manager', 'sochm_basic_settings' );
		$show_admin_bar = Store_Opening_Closing_Hours_Manager::get_option( 'show_notice_in_wp_admin', 'sochm_basic_settings' );

		if ( 'on' !== $enabled && 'on' !== $show_admin_bar ) {
			return;
		}

		// Check if the admin bar is showing.
		if ( is_admin_bar_showing() ) {
			$is_store_closed = Store_Opening_Closing_Hours_Manager::is_store_closed();

			$args = array(
				'id'    => $is_store_closed ? 'sochm_notice_in_wp_admin_closed' : 'sochm_notice_in_wp_admin_open',
				'title' => $is_store_closed
						? __( 'Store Status : Closed', 'store-opening-closing-hours-manager' )
						: __( 'Store Status : Open', 'store-opening-closing-hours-manager' ),
			);

			$wp_admin_bar->add_node( $args );
		}
	}

	/**
	 * Registers the Store Opening Closing Hours Manager widget.
	 *
	 * This function registers the `Store_Opening_Closing_Hours_Manager_Widget` widget, making it available for
	 * use in WordPress sidebars.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function register_widget() {
		$register_widget = Store_Opening_Closing_Hours_Manager::get_option( 'enable_widget', 'sochm_basic_settings' );

		if ( 'on' !== $register_widget ) {
			return;
		}

		register_widget( 'Store_Opening_Closing_Hours_Manager_Widget' );
	}

	/**
	 * Declares compatibility with WooCommerce's custom order tables feature.
	 *
	 * This function is hooked into the `before_woocommerce_init` action and checks
	 * if the `FeaturesUtil` class exists in the `Automattic\WooCommerce\Utilities`
	 * namespace. If it does, it declares compatibility with the 'custom_order_tables'
	 * feature. This is important for ensuring the plugin works correctly with
	 * WooCommerce versions that support this feature.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function declare_compatibility_with_wc_custom_order_tables() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * Saves the store opening and closing hours data to the database.
	 *
	 * This function handles saving the weekly store hours data submitted via an AJAX request.
	 * It performs several security checks, including nonce verification and user capability
	 * checks, before processing and saving the data. The data is sanitized before being
	 * stored in the `sochm_table_data` option in the WordPress database.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    void Sends a JSON response indicating success or failure.
	 */
	public function save_week_table() {
		// Check for nonce security.
		if ( ! check_admin_referer( 'sochm_ajax_nonce', '_wpnonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Cheatin Huh!', 'store-opening-closing-hours-manager' ) ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( "You don't have permission to access this page!", 'store-opening-closing-hours-manager' ) ) );
		}

		if ( isset( $_POST['payload'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			parse_str( wp_unslash( $_POST['payload'] ), $payload );

			$payload = map_deep( $payload, 'sanitize_text_field' );

			if ( isset( $payload['store_open_close'] ) ) {
				if ( is_array( $payload['store_open_close'] ) ) {
					update_option( 'sochm_table_data', $payload['store_open_close'] );

					wp_send_json_success( array( 'message' => 'Success!' ) );
				}
			}
		}

		wp_send_json_error( array( 'message' => 'Something went wrong! Please try again.' ) );
	}
}
