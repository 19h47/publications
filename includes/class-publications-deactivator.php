<?php

/**
 * Fired during plugin deactivation
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Publications
 * @subpackage	Publications/includes
 */


/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since		1.0.0
 * @package		Publications
 * @subpackage	Publications/includes
 * @author		Jérémy Levron <jeremylevron@19h47.fr>
 */
class Publications_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since	1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'import_instagrams_post_as_posts' );
	}
}
