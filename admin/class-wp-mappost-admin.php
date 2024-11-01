<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://glaucussoft.com
 * @since      1.0.0
 *
 * @package    Wp_Mappost
 * @subpackage Wp_Mappost/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Mappost
 * @subpackage Wp_Mappost/admin
 * @author     GlaucusSoft <dev@glaucuss.com>
 */
class Wp_Mappost_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mappost_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mappost_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-mappost-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mappost_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mappost_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-mappost-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Añadir página de configuración dentro del submenú ajustes
	 *
	 * @since	1.0.0
	 */
	public function agregar_pagina_opciones(){
		$this->plugin_screen_hook_suffix = add_options_page(
					__('Configuración MapeaPost', $this->plugin_name),
					__('MapeaPost', $this->plugin_name),
					'manage_options',
					$this->plugin_name,
					array($this, 'mostrar_pagina_opciones'));
	}
	
	/**
	 * Muestra la página de configuración del plugin
	 *
	 * @since	1.0.0
	 */
	public function mostrar_pagina_opciones(){
		include_once('partials/wp-mappost-admin-display.php');
	}
	
	/**
	 * Registra todas las opciones de configuración del plugin
	 * 
	 * @since	1.0.0
	 */
	public function registrar_configuracion(){
		add_settings_section(
			$this->plugin_name . '_general',
			__('General', $this->plugin_name),
			array($this, 'general_cb'),
			$this->plugin_name);
		add_settings_field(
			$this->plugin_name . '_google_key',
			__('Google API Key', $this->plugin_name),
			array($this, 'google_key_cb'),
			$this->plugin_name,
			$this->plugin_name . '_general',
			array('label_for' => $this->plugin_name . '_google_key'));
		register_setting($this->plugin_name, $this->plugin_name . '_google_key', array($this, 'sanitize_google_api_key'));
	}
	
	/**
	 * Renderiza el texto para sección general
	 * 
	 * @since	1.0.0
	 */
	public function general_cb(){
		echo '<p>' . __('Por favor establece los campos según corresponda.', $this->plugin_name) . '</p>';
	}
	
	/**
	 * Renderiza el campo de entrada para introducir la clave de la API de google Maps
	 *
	 * @since	1.0.0
	 */
	public function google_key_cb(){
		$apiKey = get_option($this->plugin_name . '_google_key');
		$campoId = $this->plugin_name . '_google_key';
		echo <<<EOT
<input type="text" size="50" name="$campoId" id="$campoId" value="$apiKey" />
EOT;
	}
	
	/**
	 * Sanea la cadena de caracteres que representa la API key
	 * 
	 * @since	1.0.0
	 */
	public function sanitize_google_api_key($apiKey){
		return sanitize_text_field($apiKey);
	}
	
	/**
	 * Registra los metaboxes en la edición de los posts
	 *
	 * @since	1.0.0
	 */
	public function registrar_meta_boxes(){
		add_meta_box($this->plugin_name . '_meta_localizacion',
			__('Localización', $this->plugin_name),
			array($this, 'localizacion_entrada'),
			'post',
			'side');
	}
	
	/**
	 * Renderiza el formulario de localización
	 *
	 * @since	1.0.0
	 */
	public function localizacion_entrada($post){
		include_once('partials/wp-mappost-localizacion-display.php');
	}
	
	private function extraer_coordenada($valor, $minimo, $maximo){
		if(is_numeric($valor)){
			$decimal = floatval($valor);
			if($decimal < $minimo){
				return $minimo;
			}else if($decimal > $maximo){
				return $maximo;
			}else{
				return $decimal;
			}
		}else{
			return '';
		}
	}
	
	/**
	 * Guarda los datos de localización de la entrada
	 *
	 * @since	1.0.0
	 */
	public function guardar_localizacion_entrada($post_id){
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if(!isset($_POST['mappost_meta_localizacion']) || wp_verify_nonce($POST['mappost_meta_localizacion'], 'mappost_meta_localizacion')) return;
		if(!current_user_can('edit_post')) return;
		
		$k_lat = $this->plugin_name . '_meta_latitud';
		$k_lon = $this->plugin_name . '_meta_longitud';
		$k_etiqueta = $this->plugin_name . '_meta_etiqueta';
		if(isset($_POST[$k_lat])){
			update_post_meta($post_id, $k_lat, $this->extraer_coordenada($_POST[$k_lat], -90, 90));
		}
		if(isset($_POST[$k_lon])){
			update_post_meta($post_id, $k_lon, $this->extraer_coordenada($_POST[$k_lon], -180, 180));
		}
		if(isset($_POST[$k_etiqueta])){
			update_post_meta($post_id, $k_etiqueta, sanitize_text_field($_POST[$k_etiqueta]));
		}
	}
	

}
