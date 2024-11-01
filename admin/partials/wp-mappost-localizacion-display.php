<?php

/**
 * Provee la vista para incluir la localización de un post
 *
 * @link       https://glaucussoft.com
 * @since      1.0.0
 *
 * @package    Wp_Mappost
 * @subpackage Wp_Mappost/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
	$valores = get_post_custom($post->ID);
	$k_lat = $this->plugin_name . '_meta_latitud';
	$k_lon = $this->plugin_name . '_meta_longitud';
	$k_eti = $this->plugin_name . '_meta_etiqueta';
	$latitud = isset($valores[$k_lat]) ? esc_attr($valores[$k_lat][0]) : '';
	$longitud = isset($valores[$k_lon]) ? esc_attr($valores[$k_lon][0]) : '';
	$etiqueta = isset($valores[$k_eti]) ? esc_attr($valores[$k_eti][0]) : '';
	wp_nonce_field('mappost_meta_localizacion', 'mappost_meta_localizacion');
?>
<p>
	<label for="<?php echo $k_lat;?>"><?php _e('Latitud', $this->plugin_name);?></label>
	<input type="text" id="<?php echo $k_lat;?>" name="<?php echo $k_lat;?>" value="<?php echo $latitud; ?>" />
</p>
<p>
	<label for="<?php echo $l_lon;?>"><?php _e('Longitud', $this->plugin_name);?></label>
	<input type="text" id="<?php echo $k_lon;?>" name="<?php echo $k_lon;?>" value="<?php echo $longitud; ?>" />
</p>
<p>
	<label for="<?php echo $k_eti;?>"><?php _e('Etiqueta', $this->plugin_name);?></label>
	<input type="text" id="<?php echo $k_eti;?>" name="<?php echo $k_eti;?>" value="<?php echo $etiqueta; ?>" />
</p>