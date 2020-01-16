<?php
/**
 * Provides helper functions.
 *
 * @since	  1.0.0
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
 * @since		1.0.0
 *
 * @return		AccessForAll_Last_Modified_Date_Shortcode
 */
function ACCESSFORALLLASTMODIFIEDDATESHORTCODE() {
	return AccessForAll_Last_Modified_Date_Shortcode::instance();
}