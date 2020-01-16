<?php
/**
 * Provides helper functions.
 *
 * @since	  {{VERSION}}
 *
 * @package	AccessForAll_Last_Modified_Date_Shortcode
 * @subpackage AccessForAll_Last_Modified_Date_Shortcode/core
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Returns the main plugin object
 *
 * @since		{{VERSION}}
 *
 * @return		AccessForAll_Last_Modified_Date_Shortcode
 */
function ACCESSFORALLLASTMODIFIEDDATESHORTCODE() {
	return AccessForAll_Last_Modified_Date_Shortcode::instance();
}