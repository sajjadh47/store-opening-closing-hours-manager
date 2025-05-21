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
	'%s: <strong>%s</strong>',
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
	<script type="text/html" id="tmpl-store-hours-table-template">
		<# _.each(data.weekDaysTable, function(week, key) { #>
			<tr class="weekDay{{ week.week_name }}">
				<td>
					<input type="hidden" name="store_open_close[{{ key }}][fullName]" value="{{ week.week_full_name }}">
					<input type="hidden" name="store_open_close[{{ key }}][name]" value="{{ week.week_name }}">
					{{ week.week_full_name }} <# if (week.week_name === data.today) { #><sup><small>{{ data.todayText }}</small></sup><# } #>
				</td>

				<td>
					<select class="store_open_close_status" name="store_open_close[{{ key }}][status]">
						<option value="open" <# if (week.status === 'open') { #>selected<# } #> >{{ data.statusOpen }}</option>
						<option value="closed" <# if (week.status === 'closed') { #>selected<# } #> >{{ data.statusClosed }}</option>
					</select>
				</td>

				<td>
					<select class="time_dropdown" name="store_open_close[{{ key }}][opening_time_hr]">
						<# _.each(week.opening_time_hr, function(opening_time_hr) { #>
							<option value="{{ opening_time_hr }}" <# if (opening_time_hr === week.selected_opening_time_hr) { #>selected<# } #>>
								{{ opening_time_hr }}
							</option>
						<# }); #>
					</select>

					<select class="time_dropdown" name="store_open_close[{{ key }}][opening_time_min]">
						<# _.each(week.opening_time_min, function(opening_time_min) { #>
							<option value="{{ opening_time_min }}" <# if (opening_time_min === week.selected_opening_time_min) { #>selected<# } #>>
								{{ opening_time_min }}
							</option>
						<# }); #>
					</select>
				</td>

				<td style="min-width: 20rem;">
					<select class="time_dropdown" name="store_open_close[{{ key }}][closing_time_hr]">
						<# _.each(week.closing_time_hr, function(closing_time_hr) { #>
						<option value="{{ closing_time_hr }}" <# if (closing_time_hr === week.selected_closing_time_hr) { #>selected<# } #>>
							{{ closing_time_hr }}
						</option>
						<# }); #>
					</select>

					<select class="time_dropdown" name="store_open_close[{{ key }}][closing_time_min]">
						<# _.each(week.closing_time_min, function(closing_time_min) { #>
							<option value="{{ closing_time_min }}" <# if (closing_time_min === week.selected_closing_time_min) { #>selected<# } #>>
								{{ closing_time_min }}
							</option>
						<# }); #>
					</select>

					<button class="button addNewOpeningClosing" type="button">{{ data.addBtnText }}</button>
					<button class="button removeOpeningClosing" type="button">{{ data.removeBtnText }}</button>
				</td>
			</tr>
		<# }); #>
	</script>
</div>