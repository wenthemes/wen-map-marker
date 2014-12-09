<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           wen_map_maker
 *
 * @wordpress-plugin
 * Plugin Name:       WEN Map Marker
 * Plugin URI:        http://webexpertsnepal.com/wen-map-maker
 * Description:       An extreamly easy way to add Google Map on the WordPress sites.
 * Version:           1.0.0
 * Author:            WEN Team
 * Author URI:        http://webexpertsnepal.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wen-map-maker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wen-map-maker-activator.php
 */
function activate_wen_map_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wen-map-maker-activator.php';
	wen_map_maker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wen-map-maker-deactivator.php
 */
function deactivate_wen_map_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wen-map-maker-deactivator.php';
	wen_map_maker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wen_map_maker' );
register_deactivation_hook( __FILE__, 'deactivate_wen_map_maker' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wen-map-maker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wen_map_maker() {

	$plugin = new wen_map_maker();
	$plugin->run();

}
run_wen_map_maker();
