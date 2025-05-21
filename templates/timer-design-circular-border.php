<?php
/**
 * Template for timer design (circular border).
 *
 * This file is used to markup the circular border timer design.
 *
 * This template can be overridden by copying it to yourtheme/sochm/timer-design-circular-border.php.
 *
 * @since         2.0.0
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/templates
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * An associative array of data passed to the template.
 * @var array $data {
 *   @type string $time_txt     Localized text for "Remaining Time To Open/Close".
 *   @type string $type         Type of timer (e.g., 'store_is_going_to_open_soon_remaining_time').
 *   @type int    $time_days    Number of remaining days.
 *   @type int    $time_hours   Number of remaining hours.
 *   @type int    $time_minutes Number of remaining minutes.
 *   @type int    $time_seconds Number of remaining seconds.
 *   @type int    $seconds      Number of total seconds.
 * }
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>
<div id="sochm-timer-design-circular-border">
	<?php echo esc_html( $data['time_txt'] ); ?>
	<div id="<?php echo esc_attr( $data['type'] ); ?>">
		<div id="sochm-days">
			<span>
				<?php printf( '%02d %s', esc_html( $data['time_days'] ), esc_html__( 'Days', 'store-opening-closing-hours-manager' ) ); ?>
			</span>
			<svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
				<g>
					<circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/>
				</g>
			</svg>
		</div>
		<div id="sochm-hours">
			<span>
				<?php printf( '%02d %s', esc_html( $data['time_hours'] ), esc_html__( 'Hours', 'store-opening-closing-hours-manager' ) ); ?>
			</span>
			<svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
				<g>
					<circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/>
				</g>
			</svg>
		</div>
		<div id="sochm-minutes">
			<span>
				<?php printf( '%02d %s', esc_html( $data['time_minutes'] ), esc_html__( 'Minutes', 'store-opening-closing-hours-manager' ) ); ?>
			</span>
			<svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
				<g>
					<circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/>
				</g>
			</svg>
		</div>
		<div id="sochm-seconds">
			<span>
				<?php printf( '%02d %s', esc_html( $data['time_seconds'] ), esc_html__( 'Seconds', 'store-opening-closing-hours-manager' ) ); ?>
			</span>
			<svg width="160" height="160" xmlns="http://www.w3.org/2000/svg">
				<g>
					<circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#ff0000" fill="none"/>
				</g>
			</svg>
		</div>
	</div>
</div>