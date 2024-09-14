<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

if ( ! class_exists( 'SOCHM_SHORTCODE' ) )
{
	class SOCHM_SHORTCODE
	{
		function __construct()
		{
			add_shortcode( 'sochm_display_table', [ $this, 'shortcode' ] );
		}
		
		public function shortcode( $atts, $content, $tag )
		{
			if ( apply_filters( 'sochm_show_timezone_in_shortcode', true ) )
			{
				echo '<p class=" ' . apply_filters( 'sochm_timezone_shortcode_classes', 'sochm_timezone' ) . ' ">' . apply_filters( 'sochm_timezone_title_in_shortcode', 'Timezone : ' ), SOCHM_UTIL::get_option( 'timezone', 'sochm_basic_settings' ) . '</p>';
			}

			$weekDaysTable = SOCHM_UTIL::get_table_settings();

			$daysCount = array( 'monday' => 0, 'tuesday' => 0, 'wednesday' => 0, 'thursday' => 0, 'friday' => 0, 'saturday' => 0, 'sunday' => 0 );

			//output
			?>
				<table class='week_days_table'>
		    		<thead>
		    			<tr>
		    				<th><?php echo __( 'Day', 'store-opening-closing-hours-manager' );  ?></th>
		    				<th><?php echo __( 'Status', 'store-opening-closing-hours-manager' ); ?></th>
		    				<th><?php echo __( 'From', 'store-opening-closing-hours-manager' ); ?></th>
		    				<th><?php echo __( 'To', 'store-opening-closing-hours-manager' ); ?></th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    		<?php foreach( $weekDaysTable as $key => $week ) : $daysCount[$week["weekName"]]++; ?>
			    		<tr class='<?php echo esc_html( $week["weekName"] ); ?>'>
							<td <?php echo esc_html( $daysCount[$week["weekName"]] ) > 1 ? 'style="color:transparent;"' : ''; ?>><?php echo esc_html( $week["weekFullName"] ); ?></td>
							<td><?php echo ucfirst( esc_html( $week["status"] ) ); ?></td>
							<td><?php echo esc_html( $week["selected_opening_time_hr"] ) . ':' . esc_html( $week["selected_opening_time_min"] ); ?></td>
							<td><?php echo esc_html( $week["selected_closing_time_hr"] ) . ':' . esc_html( $week["selected_closing_time_min"] ); ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
		    	</table>
			<?php
		}
	}

	$SOCHM_SHORTCODE = new SOCHM_SHORTCODE;
}
