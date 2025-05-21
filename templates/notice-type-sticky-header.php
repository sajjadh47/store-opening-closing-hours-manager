<?php
/**
 * Template for notice type (sticky header).
 *
 * This file is used to markup the sticky header notice type.
 *
 * This template can be overridden by copying it to yourtheme/sochm/notice-type-sticky-header.php.
 *
 * @since         2.0.0
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/templates
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * An associative array of data passed to the template.
 * @var array $data {
 *   @type string $message_with_time  Combined message and remaining time HTML.
 *   @type string $notice_boxbg_color Background color for the notice box.
 *   @type string $notice_text_color  Text color for the notice.
 *   @type string $top_value          CSS 'top' value (e.g., '32px' or '0').
 * }
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>
<div id="sochm-sticky-header">
	<div>
		<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $data['message_with_time'];
		?>
	</div>
</div>
<style type="text/css" media="screen">
	div#sochm-sticky-header {
		background: <?php echo esc_attr( $data['notice_boxbg_color'] ); ?>;
		color: <?php echo esc_attr( $data['notice_text_color'] ); ?>;
		text-align: center;
		padding: 15px;
		position: sticky;
		top: <?php echo esc_attr( $data['top_value'] ); ?>;
		right: 0;
		left: 0;
		z-index: 999999;
		border-bottom: 1px solid <?php echo esc_attr( $data['notice_text_color'] ); ?>;
	}
</style>