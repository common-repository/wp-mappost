<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://glaucussoft.com
 * @since      1.0.0
 *
 * @package    Wp_Mappost
 * @subpackage Wp_Mappost/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Mappost
 * @subpackage Wp_Mappost/public
 * @author     GlaucusSoft <dev@glaucuss.com>
 */
class Wp_Mappost_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->wp_mappost_options = get_option($this->plugin_name);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-mappost-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-mappost-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function mostrar_shortcode($atts){
		// Atributos
		$atts = shortcode_atts(
			array(
				'categoria' => '',
				'centro' => '37.025473,-4.5594437',
				'zoom' => 6
			),
			$atts
		);
		$categoria = $atts['categoria'];
		if(preg_match('/(?<latitud>-?[0-9]+([.][0-9]*)?),(?<longitud>-?[0-9]+([.][0-9]*)?)/', $atts['centro'], $coincidencias) && isset($coincidencias['latitud']) && isset($coincidencias['longitud'])){
			$latitud = max(-180, min((float) $coincidencias['latitud'], 180));
			$longitud = max(-90, min((float) $coincidencias['longitud'], 90));
			$centro = array('lat' => $latitud, 'lng' => $longitud);
		}else{
			$centro = array('lat' => 37.025473, 'lng' => -4.5594437);
		}
		if(is_numeric($atts['zoom'])){
			$zoom = max(0, min(20, (int) $atts['zoom']));
		}
		$clave = get_option($this->plugin_name . '_google_key');
		$k_lat = $this->plugin_name . '_meta_latitud';
		$k_lon = $this->plugin_name . '_meta_longitud';
		$k_eti = $this->plugin_name . '_meta_etiqueta';
		if($clave != ''){
			$s_centro = json_encode($centro);
			$html = <<<EOT
<div id="mapa"></div>
<script>
	function iniciarMapa(){
		var map = new google.maps.Map(document.getElementById('mapa'), {
			zoom: $zoom,
			center: $s_centro
		});
		var resenas = new Array();\n
EOT;
			$entradas = get_posts(array('category_name' => $categoria));
			foreach($entradas as $entrada){
				$valores = get_post_custom($entrada->ID);
				$latitud = isset($valores[$k_lat]) ? $valores[$k_lat][0] : '';
				$longitud = isset($valores[$k_lon]) ? $valores[$k_lon][0] : '';
				$etiqueta = isset($valores[$k_eti]) ? $valores[$k_eti][0] : '';
				if($latitud == '' || $longitud == ''){
					continue;
				}
				$titulo = $entrada->post_title;
				$enlace = get_permalink($entrada->ID);
				$datos = array('titulo' => $entrada->post_title, 'enlace' => get_permalink($entrada->ID), 'etiqueta' => $etiqueta, 'posicion' => array('lat' => (float) $latitud, 'lng' => (float) $longitud));
				$s_datos = json_encode($datos);
				$html .= <<<EOT
		resenas.push($s_datos);\n
EOT;
			}
			$html .= <<<EOT
		var indice;
		var infowindow = new google.maps.InfoWindow();
		for(indice = 0; indice < resenas.length; ++indice){
			resena = resenas[indice];
			var marcador = new google.maps.Marker({
				position: resena.posicion,
				map: map,
				animation: google.maps.Animation.DROP,
				title: resena.titulo,
				label: resena.etiqueta
			});
			google.maps.event.addListener(marcador, 'click', (function(marcador, resena){
				return function(){
					infowindow.setContent('<div id="globo"><p>' + resena.titulo + '</p><p><a href="' + resena.enlace + '">Ver reseña</a></p></div>');
					infowindow.open(map, marcador);
				}
			})(marcador, resena));
		}
	}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=$clave&callback=iniciarMapa"></script>
EOT;
			return $html;
		}
		return '';
	}
	
	public function crear_shortcode(){
		add_shortcode('mappost_mapa', array($this, 'mostrar_shortcode'));
	}

}
