<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/vishalkakadiya
 * @since      1.0.0
 *
 * @package    Songs_Mania
 * @subpackage Songs_Mania/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Songs_Mania
 * @subpackage Songs_Mania/includes
 * @author     Vishal Kakadiya <vishalkakadiya123@gmail.com>
 */
class Songs_Mania_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'songs-mania',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
