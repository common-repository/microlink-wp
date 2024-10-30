<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://microlink.io/
 * @since             1.0.0
 * @package           Microlink
 *
 * @wordpress-plugin
 * Plugin Name:       Microlink WP
 * Plugin URI:        https://microlink.io/
 * Description:       Microlink easily converts your links into beautiful previews
 * Version:           1.0.0
 * Author:            Jon Torrado
 * Author URI:        https://www.linkedin.com/in/jontorrado
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       microlink
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'MICROLINK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-microlink-activator.php
 */
function activate_microlink() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-microlink-activator.php';
	Microlink_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-microlink-deactivator.php
 */
function deactivate_microlink() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-microlink-deactivator.php';
	Microlink_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_microlink' );
register_deactivation_hook( __FILE__, 'deactivate_microlink' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-microlink.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_microlink() {

	$plugin = new Microlink();
	$plugin->run();

}
run_microlink();
