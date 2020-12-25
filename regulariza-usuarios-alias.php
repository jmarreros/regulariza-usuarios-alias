<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://decodecms.com
 * @since             1.0.0
 * @package           Regulariza_Usuarios_Alias
 *
 * @wordpress-plugin
 * Plugin Name:       Regulariza usuarios alias
 * Plugin URI:        https://decodecms.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jhon Marreros G.
 * Author URI:        https://decodecms.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       regulariza-usuarios-alias
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
define( 'REGULARIZA_USUARIOS_ALIAS_VERSION', '1.0.0' );

define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );




/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-regulariza-usuarios-alias-activator.php
 */
function activate_regulariza_usuarios_alias() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-regulariza-usuarios-alias-activator.php';
	Regulariza_Usuarios_Alias_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-regulariza-usuarios-alias-deactivator.php
 */
function deactivate_regulariza_usuarios_alias() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-regulariza-usuarios-alias-deactivator.php';
	Regulariza_Usuarios_Alias_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_regulariza_usuarios_alias' );
register_deactivation_hook( __FILE__, 'deactivate_regulariza_usuarios_alias' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-regulariza-usuarios-alias.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_regulariza_usuarios_alias() {

	$plugin = new Regulariza_Usuarios_Alias();
	$plugin->run();

}
run_regulariza_usuarios_alias();
