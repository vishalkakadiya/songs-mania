<?php
/**
 *
 * @link              https://github.com/vishalkakadiya
 * @since             1.0.0
 * @package           Songs_Mania
 *
 * @wordpress-plugin
 * Plugin Name:       Songs Mania
 * Plugin URI:        https://github.com/vishalkakadiya
 * Description:       Basically this plugin manages your songs albums and listing.
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
