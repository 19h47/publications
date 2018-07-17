<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Images
 * @subpackage	Images/includes
 */


/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since		1.0.0
 * @package		Images
 * @subpackage	Images/includes
 * @author		Jérémy Levron <jeremylevron@19h47.fr>
 */
class Images_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since	1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'images',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
