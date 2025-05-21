<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since         2.0.0
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/public/views
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$timezone = Store_Opening_Closing_Hours_Manager::get_option( 'timezone', 'sochm_basic_settings', 'Asia/Dhaka' );
$date_now = new DateTime( 'now', new DateTimezone( $timezone ) );
$today    = $date_now->format( 'l' );

// Get the table data.
$week_days_table = Store_Opening_Closing_Hours_Manager::get_table_settings();

// Initialize an array to track day counts for repeated days.
$days_count = array(
	'monday'    => 0,
	'tuesday'   => 0,
	'wednesday' => 0,
	'thursday'  => 0,
	'friday'    => 0,
	'saturday'  => 0,
	'sunday'    => 0,
);

$today_text = __( 'Today', 'store-opening-closing-hours-manager' );

// Output the table.
?>
<table class='week_days_table'>
	<thead>
		<tr>
			<th><?php esc_html_e( 'Day', 'store-opening-closing-hours-manager' ); ?></th>
			<th><?php esc_html_e( 'Status', 'store-opening-closing-hours-manager' ); ?></th>
			<th><?php esc_html_e( 'From', 'store-opening-closing-hours-manager' ); ?></th>
			<th><?php esc_html_e( 'To', 'store-opening-closing-hours-manager' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $week_days_table as $key => $week ) : ?>
			<?php
				// phpcs:ignore Universal.Operators.DisallowStandalonePostIncrementDecrement.PostIncrementFound
				$days_count[ $week['week_name'] ]++;
			?>
			<tr class='<?php echo esc_html( $week['week_name'] ); ?> <?php echo strtolower( $today ) === strtolower( $week['week_full_name'] ) ? 'today' : ''; ?>'>
				<td <?php echo esc_html( $days_count[ $week['week_name'] ] ) > 1 ? 'style="color:transparent;"' : ''; ?>>
					<?php echo esc_html( $week['week_full_name'] ); ?>
					<?php echo strtolower( $today ) === strtolower( $week['week_full_name'] ) ? '<sup><small>' . esc_html( $today_text ) . '</small></sup>' : ''; ?>
				</td>
				<td>
					<?php echo esc_html( ucfirst( $week['status'] ) ); ?>
				</td>
				<td>
					<?php echo esc_html( $week['selected_opening_time_hr'] ) . ':' . esc_html( $week['selected_opening_time_min'] ); ?>
				</td>
				<td>
					<?php echo esc_html( $week['selected_closing_time_hr'] ) . ':' . esc_html( $week['selected_closing_time_min'] ); ?>	
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>