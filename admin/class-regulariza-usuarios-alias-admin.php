<?php
include_once PLUGIN_DIR .'/includes/class-proceso-correcion-usuarios.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://decodecms.com
 * @since      1.0.0
 *
 * @package    Regulariza_Usuarios_Alias
 * @subpackage Regulariza_Usuarios_Alias/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Regulariza_Usuarios_Alias
 * @subpackage Regulariza_Usuarios_Alias/admin
 * @author     Jhon Marreros G. <admin@decodecms.com>
 */
class Regulariza_Usuarios_Alias_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	public function dcms_regulariza_usuarios_admin_menu() {
		add_menu_page(
						'Regulariza Usuarios',
						'Regulariza Usuarios',
						'manage_options',
						'regulariza-usuarios',
						array( $this, 'dcms_regulariza_usuarios'),
						'dashicons-chart-pie' );
	}


	public function dcms_regulariza_usuarios_form_1(){
		$regulariza = new Proceso_Correccion_Usuarios();
		$regulariza->limpia_datos_iniciales();
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=1'));
	}


	public function dcms_regulariza_usuarios_form_2(){
		$regulariza = new Proceso_Correccion_Usuarios();
		$regulariza->crear_tabla_temporal();
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=2'));
	}


	public function dcms_regulariza_usuarios_form_3(){
		error_log(print_r('Entro paso 1',true));
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=3'));
	}

	public function dcms_regulariza_usuarios_form_4(){
		error_log(print_r('Entro paso 1',true));
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=4'));
	}

	// Muestra la opción de menú de regularización de usuarios
	public function dcms_regulariza_usuarios(){
		include_once PLUGIN_DIR . 'admin/partials/regulariza-usuarios-alias-admin-display.php';
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/regulariza-usuarios-alias-admin.css', array(), $this->version, 'all' );
	}


	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/regulariza-usuarios-alias-admin.js', array( 'jquery' ), $this->version, false );
	}

}
