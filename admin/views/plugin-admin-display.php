<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since         2.0.0
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/admin/views
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$timezone = Store_Opening_Closing_Hours_Manager::get_option( 'timezone', 'sochm_basic_settings', 'Asia/Dhaka' );
$date_now = new DateTime( 'now', new DateTimezone( $timezone ) );

printf(
	'%s : <strong>%s</strong>',
	esc_html__( 'Current Server Time', 'store-opening-closing-hours-manager' ),
	esc_html( $date_now->format( 'Y-m-d H:i:s' ) ),
);
?>
<div>
	<table class="week_days_table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Day', 'store-opening-closing-hours-manager' ); ?></th>
				<th><?php esc_html_e( 'Status', 'store-opening-closing-hours-manager' ); ?></th>
				<th><?php esc_html_e( 'From', 'store-opening-closing-hours-manager' ); ?></th>
				<th><?php esc_html_e( 'To', 'store-opening-closing-hours-manager' ); ?></th>
			</tr>
		</thead>
		<tbody id="store-hours-table-body"></tbody>
	</table>
</div>