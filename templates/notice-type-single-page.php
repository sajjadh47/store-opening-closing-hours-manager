<?php
/**
 * Template for notice type (single page).
 *
 * This file is used to markup the single page notice type.
 *
 * This template can be overridden by copying it to yourtheme/sochm/notice-type-single-page.php.
 *
 * @since         2.0.0
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/templates
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * An associative array of data passed to the template.
 * @var array $data {
 *   @type string $message             The main message text.
 *   @type string $remaining_time_html HTML for the remaining time.
 *   @type string $notice_boxbg_color  Background color for the notice box.
 *   @type string $notice_text_color   Text color for the notice.
 * }
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>
<div class="sochm-single-page-container">
	<i class="sochm-icon-close">
		<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
			<line x1="18" y1="6" x2="6" y2="18"></line>
			<line x1="6" y1="6" x2="18" y2="18"></line>
		</svg>
	</i>
	<div class="middle">
		<h3>
			<?php echo esc_html( $data['message'] ); ?>
		</h3>
		<div>
			<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $data['remaining_time_html'];
			?>
		</div>
	</div>
</div>
<style type="text/css" media="screen">
	body, html {
		overflow: hidden !important;
	}
	.sochm-single-page-container {
		background: <?php echo esc_attr( $data['notice_boxbg_color'] ); ?>;
		color: <?php echo esc_attr( $data['notice_text_color'] ); ?>;
	}
	.sochm-single-page-container h3 {
		color: <?php echo esc_attr( $data['notice_text_color'] ); ?>;
	}
</style>