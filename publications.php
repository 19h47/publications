<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link				http://19h47.fr
 * @since				1.0.0
 * @package				Publications
 *
 * @wordpress-plugin
 * Plugin Name:			Publications
 * Plugin URI:			https://github.com/19h47/publications
 * Description:			Instagram's post to WordPress Post
 * Version:				1.0.0
 * Author:				Jérémy Levron
 * Author URI:			http://19h47.fr
 * License:				GPL-2.0+
 * License URI:			http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:			pblctns
 * Domain Path:			/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer	https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PUBLICATIONS', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-publications-activator.php
 */
function activate_publications() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-publications-activator.php';
	Publications_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-publications-deactivator.php
 */
function deactivate_publications() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-publications-deactivator.php';
	Publications_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'activate_publications' );
register_deactivation_hook( __FILE__, 'deactivate_publications' );


/**
 * Autoload
 */
require_once( __DIR__ . '/vendor/autoload.php' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-publications.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since				1.0.0
 */
function run_publications() {

	$plugin = new Publications();
	$plugin->run();
}
run_publications();
