<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    wen_map_marker
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'wen_map_marker_settings' );
