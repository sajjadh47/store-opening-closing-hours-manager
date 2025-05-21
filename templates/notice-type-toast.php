<?php
/**
 * Template for notice type (toast).
 *
 * This file is used to markup the toast notice type.
 *
 * This template can be overridden by copying it to yourtheme/sochm/notice-type-toast.php.
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
<div id="sochm-toast" class="sochm-toast <?php echo esc_attr( $data['toast_class'] ); ?>">
	<div class="sochm-toast-content">
		<div class="sochm-toast-message">
			<span class="sochm-toast-text sochm-toast-text-2"></span>
		</div>
	</div>
</div>