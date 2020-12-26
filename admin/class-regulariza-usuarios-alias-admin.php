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
	private $regulariza;

	public function __construct( $plugin_name, $version ) {

		$this->regulariza = new Proceso_Correccion_Usuarios();

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


	// Paso 1 --- Limpieza de datos iniciales en wp_options
	public function dcms_regulariza_usuarios_form_1(){

		$this->regulariza->limpia_datos_iniciales();
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=1'));
	}

	// Paso 2 ---  Creación de tabla tempora y llenado de datos iniciales
	public function dcms_regulariza_usuarios_form_2(){

		$this->regulariza->crear_tabla_temporal();
		$this->regulariza->completar_datos_tabla_temporal();

		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=2'));
	}

	// Paso 3 --- Creacion de usuarios
	public function dcms_regulariza_usuarios_form_3(){
		$this->regulariza->creacion_usuarios();
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=3'));
	}

	// Paso 4 --- Relacionar usuarios con la entrada
	public function dcms_regulariza_usuarios_form_4(){
		$this->regulariza->regulariza_entrada_usuario();
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=4'));
	}


	// BATCH
	public function dcms_regulariza_usuarios_form_5(){
		wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=5'));
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



	// // Paso 3-1 ---Eliminacion de usuarios
	// public function dcms_regulariza_usuarios_form_3_1(){
	// 	$this->regulariza->eliminar_usuarios();
	// 	wp_redirect(admin_url('/admin.php?page=regulariza-usuarios&paso=3_1'));
	// }

