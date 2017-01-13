<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/vishalkakadiya
 * @since             1.0.0
 * @package           Songs_Mania
 *
 * @wordpress-plugin
 * Plugin Name:       Songs Mania
 * Plugin URI:        https://github.com/vishalkakadiya
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Vishal Kakadiya
 * Author URI:        https://github.com/vishalkakadiya
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       songs-mania
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-songs-mania-activator.php
 */
function activate_songs_mania() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-songs-mania-activator.php';
	Songs_Mania_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-songs-mania-deactivator.php
 */
function deactivate_songs_mania() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-songs-mania-deactivator.php';
	Songs_Mania_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_songs_mania' );
register_deactivation_hook( __FILE__, 'deactivate_songs_mania' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-songs-mania.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_songs_mania() {

	$plugin = new Songs_Mania();
	$plugin->run();

}
run_songs_mania();

