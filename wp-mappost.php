<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://glaucussoft.com
 * @since             1.0.0
 * @package           Wp_Mappost
 *
 * @wordpress-plugin
 * Plugin Name:       MapeaPost
 * Plugin URI:        https://glaucussoft.com/mapeapost
 * Description:       Mapea la geolocalización de las entradas del blog para mostrarlas en un mapa y enlazar los markers a la propia entrada
 * Version:           1.0.1
 * Author:            GlaucusSoft
 * Author URI:        https://glaucussoft.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-mappost
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MAPEAPOST_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-mappost-activator.php
 */
function mappost_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mappost-activator.php';
	Wp_Mappost_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-mappost-deactivator.php
 */
function mappost_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mappost-deactivator.php';
	Wp_Mappost_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'mappost_activate' );
register_deactivation_hook( __FILE__, 'mappost_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-mappost.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function mappost_run() {

	$plugin = new Wp_Mappost();
	$plugin->run();

}
mappost_run();
