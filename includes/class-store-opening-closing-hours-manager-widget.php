<?php
/**
 * This file contains the definition of the Store_Opening_Closing_Hours_Manager_Widget class, which
 * is used to register a widget.
 *
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Widget class for displaying store opening and closing hours.
 *
 * This class defines a WordPress widget that displays a table of the store's
 * opening and closing times. It extends the WP_Widget class and provides
 * methods for constructing the widget, displaying its content, handling
 * form input in the admin panel, and updating widget settings.
 *
 * @since    2.0.0
 */
class Store_Opening_Closing_Hours_Manager_Widget extends WP_Widget {
	/**
	 * Constructor for the widget.
	 *
	 * Initializes the widget's ID, name, and description.  Calls the parent
	 * constructor.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		parent::__construct(
			'sochm_widget',
			__( 'Store Opening & Closing Times Table', 'store-opening-closing-hours-manager' ),
			array( 'description' => __( 'Add A Table Of Your Store Opening & Closing Times.', 'store-opening-closing-hours-manager' ) )
		);
	}

	/**
	 * Displays the content of the widget.
	 *
	 * This method generates the HTML output for the widget, including the
	 * title (if provided) and the table of opening and closing times.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $args     Widget arguments provided by WordPress.
	 * @param     array $instance Instance settings for the widget.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		// If a title is present, display it.
		if ( ! empty( $title ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		// Display the timezone, if enabled.
		if ( apply_filters( 'sochm_show_timezone_in_widget', true ) ) {
			echo '<p class=" ' . esc_attr( apply_filters( 'sochm_timezone_widget_classes', 'sochm_timezone' ) ) . ' ">' . esc_html( apply_filters( 'sochm_timezone_title_in_widget', __( 'Timezone : ', 'store-opening-closing-hours-manager' ) ) ), esc_html( Store_Opening_Closing_Hours_Manager::get_option( 'timezone', 'sochm_basic_settings' ) ) . '</p>';
		}

		require STORE_OPENING_CLOSING_HOURS_MANAGER_PLUGIN_PATH . '/public/views/plugin-public-display.php';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}

	/**
	 * Displays the widget form in the admin panel.
	 *
	 * This method generates the HTML for the widget's settings form, which
	 * allows administrators to configure the widget's title.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $instance Current instance settings.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Store Opening & Closing Times', 'store-opening-closing-hours-manager' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Updates the widget settings.
	 *
	 * This method processes and saves the widget's settings when the form
	 * is submitted in the admin panel.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $new_instance New settings for this instance as input by the user.
	 * @param     array $old_instance Old settings for this instance.
	 * @return    array               The filtered instance settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}