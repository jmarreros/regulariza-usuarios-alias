<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://decodecms.com
 * @since      1.0.0
 *
 * @package    Regulariza_Usuarios_Alias
 * @subpackage Regulariza_Usuarios_Alias/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Regulariza_Usuarios_Alias
 * @subpackage Regulariza_Usuarios_Alias/includes
 * @author     Jhon Marreros G. <admin@decodecms.com>
 */
class Regulariza_Usuarios_Alias_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'regulariza-usuarios-alias',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
