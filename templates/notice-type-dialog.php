<?php
/**
 * Template for notice type (dialog).
 *
 * This file is used to markup the dialog notice type.
 *
 * This template can be overridden by copying it to yourtheme/sochm/notice-type-dialog.php.
 *
 * @since         2.0.0
 * @package       Store_Opening_Closing_Hours_Manager
 * @subpackage    Store_Opening_Closing_Hours_Manager/templates
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * An associative array of data passed to the template.
 * @var array $data {
 *   @type string $toast_class CSS class for the toast.
 * }
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>
<div id="sochm-dialog">
	<div>
		<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $data['message_with_time'];
		?>
	</div>
</div>