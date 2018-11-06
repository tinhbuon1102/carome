<?php

function woo_quick_view_template($slug, $vars = array(), $return = false) {

	$plugin = woo_quick_view();	
	$plugin_path = $plugin->plugin_path('public');
	$template_path = $plugin->template_path();
	$debug_mode = defined('WOOQV_TEMPLATE_DEBUG_MODE') && WOOQV_TEMPLATE_DEBUG_MODE;
	
	$template = '';

	// Look in yourtheme/woo-quick-view/slug.php
	if ( empty($template) && ! $debug_mode ) {
		
		$template = locate_template( array( $template_path . "{$slug}.php" ) );
	}
	
	// Get default slug.php
	if ( empty($template) && file_exists( $plugin_path . "templates/{$slug}.php" ) ) {
		$template = $plugin_path . "templates/{$slug}.php";
	}
	
	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'woo_quick_view_template', $template, $slug );

	if ( $template ) {
		extract($vars);
		
		if(!$return) {
			require($template);
		}else{
			ob_start();
			require($template);
			$output = ob_get_contents(); 
			ob_end_clean();
			return $output;
		}	
	}
}

function wooqv_class() {
	
	$classes = array('woo-quick-view woocommerce');
	
	$gallery_enabled = wooqv_option('modal_slider_thumb_gallery_enabled', 0);
	$gallery_visible_onhover = wooqv_option('modal_slider_thumb_gallery_visible_hover', 0);
	
	if(!empty($gallery_enabled) && !empty($gallery_visible_onhover)) {
		$classes[] = 'wooqv-thumbs-visible-onhover';
	}
	
	$slider_grayscale_transition = wooqv_option('wqv_', '0');
	if(!empty($slider_grayscale_transition)) {
		$classes[] = 'wooqv-grayscale-transition';
	}

	$classes = apply_filters('wooqv_modal_class', $classes);
	
	echo implode(' ', $classes);
}

function wooqv_attributes() {
	
	$attributes = array(
		'wooqv-close-on-added' 			=> wooqv_option('close_modal_on_added', '0'),
		'wooqv-lightbox' 				=> wooqv_option('modal_slider_lightbox_enabled', 0),
		'wooqv-slider-animation' 		=> wooqv_option('modal_slider_animation', 'slide'),
		'wooqv-slider-autoplay' 		=> wooqv_option('modal_slider_autoplay', '0'),
		'wooqv-mobile-slider-width' 	=> wooqv_option('modal_slider_width_mobile', 350),
		'wooqv-mobile-slider-height' 	=> wooqv_option('modal_slider_height_mobile', 350),
		'wooqv-desktop-slider-width' 	=> wooqv_option('modal_slider_width_desktop', 400),
		'wooqv-desktop-slider-height' 	=> wooqv_option('modal_slider_height_desktop', 400),
		'wooqv-slider-arrows-enabled' 	=> wooqv_option('modal_slider_arrows_enabled', '0'),
		'wooqv-slider-arrow' 			=> wooqv_option('modal_slider_arrow', ''),
		'wooqv-slider-gallery' 			=> wooqv_option('modal_slider_thumb_gallery_enabled', '0'),
		'wooqv-slider-gallery-thumbs' 	=> wooqv_option('modal_slider_thumb_gallery_visible', '6'),
		'wooqv-box-shadow-blur' 		=> wooqv_option('modal_box_shadow_blur', '30'),
		'wooqv-box-shadow-spread' 		=> wooqv_option('modal_box_shadow_spread', '0'),
		'wooqv-box-shadow-color' 		=> wooqv_option('modal_box_shadow_color', 'rgba(0,0,0,0.3)')
		
	);
	
	$attributes = apply_filters('wooqv_modal_attributes', $attributes);

	$data_string = '';
	foreach($attributes as $key => $value) {
		$data_string .= ' '.$key.'="'.esc_attr($value).'"';
	}

	echo $data_string;
}

function wooqv_trigger_cart_icon_class() {
	
	$classes = array('wooqv-trigger-icon');
	
	$icon_type = wooqv_option('trigger_icon_type', 'font');
	
	if(empty($icon_type)) {
		return '';
	}
	
	if($icon_type == 'font') {
		
		$icon = wooqv_option('trigger_icon_font');
		
		if(!empty($icon)) {
			$classes[] = $icon;
		}
	}

	$classes = apply_filters('wooqv_trigger_cart_icon_class', $classes);
	
	return implode(' ', $classes);	
}


function wooqv_modal_close_icon_class() {

	$classes = array('wooqv-close-icon');
	
	$close_button_enabled = wooqv_option('modal_close_enabled', '1');
	
	if(!empty($close_button_enabled)) {
		$icon = wooqv_option('modal_close_icon');
		
		if(!empty($icon)) {
			$classes[] = $icon;
		}
	}

	$classes = apply_filters('wooqv_modal_close_icon_class', $classes);
	
	return implode(' ', $classes);	
}

function wooqv_nav_icon_class() {

	$classes = array('wooqv-nav-icon');
	
	$icon = wooqv_option('modal_nav_icon');
	
	if(!empty($icon)) {
		$classes[] = $icon;
	}

	$classes = apply_filters('wooqv_nav_icon_class', $classes);
	
	return implode(' ', $classes);	
}

function wooqv_get_spinner() {
	
	if(isset($_POST['customized']) && is_object($_POST['customized'])) {
		$customized = $_POST['customized'];
		if(!empty($customized->wooqv["modal_overlay_spinner"])) {
			return $customized->wooqv["modal_overlay_spinner"];
		}
	}
	return wooqv_option('modal_overlay_spinner', '7-three-bounce');	
}

function wooqv_spinner_html($return = false, $wrapSpinner = true) {
	
	$spinner_class = 'wooqv-spinner';
	$spinner_type = wooqv_get_spinner();
	
	if(empty($spinner_type)) {
		if($return) {
			return "";
		}	
	}

	switch($spinner_type) {
		
		case '1-rotating-plane':
		
			$spinner = '<div class="'.esc_attr($spinner_class).' wooqv-spinner-rotating-plane"></div>';
			break;
		  
		case '2-double-bounce':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-double-bounce">
		        <div class="wooqv-spinner-child wooqv-spinner-double-bounce1"></div>
		        <div class="wooqv-spinner-child wooqv-spinner-double-bounce2"></div>
		    </div>';
			break;
		
		case '3-wave':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-wave">
		        <div class="wooqv-spinner-rect wooqv-spinner-rect1"></div>
		        <div class="wooqv-spinner-rect wooqv-spinner-rect2"></div>
		        <div class="wooqv-spinner-rect wooqv-spinner-rect3"></div>
		        <div class="wooqv-spinner-rect wooqv-spinner-rect4"></div>
		        <div class="wooqv-spinner-rect wooqv-spinner-rect5"></div>
		    </div>';
			break;
		
		case '4-wandering-cubes':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-wandering-cubes">
		        <div class="wooqv-spinner-cube wooqv-spinner-cube1"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube2"></div>
		    </div>';
			break;
		
		case '5-pulse':
		
			$spinner = '<div class="'.esc_attr($spinner_class).' wooqv-spinner-spinner-pulse"></div>';
			break;
		
		case '6-chasing-dots':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-chasing-dots">
		        <div class="wooqv-spinner-child wooqv-spinner-dot1"></div>
		        <div class="wooqv-spinner-child wooqv-spinner-dot2"></div>
		    </div>';
			break;
		
		case '7-three-bounce':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-three-bounce">
		        <div class="wooqv-spinner-child wooqv-spinner-bounce1"></div>
		        <div class="wooqv-spinner-child wooqv-spinner-bounce2"></div>
		        <div class="wooqv-spinner-child wooqv-spinner-bounce3"></div>
		    </div>';
			break;
		
		case '8-circle':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-circle">
		        <div class="wooqv-spinner-circle1 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle2 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle3 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle4 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle5 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle6 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle7 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle8 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle9 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle10 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle11 wooqv-spinner-child"></div>
		        <div class="wooqv-spinner-circle12 wooqv-spinner-child"></div>
		    </div>';
			break;
		
		case '9-cube-grid':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-cube-grid">
		        <div class="wooqv-spinner-cube wooqv-spinner-cube1"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube2"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube3"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube4"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube5"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube6"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube7"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube8"></div>
		        <div class="wooqv-spinner-cube wooqv-spinner-cube9"></div>
		    </div>';
			break;
			
		case '10-fading-circle':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-fading-circle">
		        <div class="wooqv-spinner-circle1 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle2 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle3 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle4 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle5 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle6 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle7 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle8 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle9 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle10 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle11 wooqv-spinner-circle"></div>
		        <div class="wooqv-spinner-circle12 wooqv-spinner-circle"></div>
		    </div>';
			break;
		
		case '11-folding-cube':
		
			$spinner = '
			<div class="'.esc_attr($spinner_class).' wooqv-spinner-folding-cube">
		        <div class="wooqv-spinner-cube1 wooqv-spinner-cube"></div>
		        <div class="wooqv-spinner-cube2 wooqv-spinner-cube"></div>
		        <div class="wooqv-spinner-cube4 wooqv-spinner-cube"></div>
		        <div class="wooqv-spinner-cube3 wooqv-spinner-cube"></div>
		    </div>';
			break;
			
		case 'loading-text':
			
			$spinner = '<div class="'.esc_attr($spinner_class).' wooqv-spinner-loading-text">'.esc_html__('Loading...', 'woo-quick-view').'</div>';	
			break;
	}
	
	$spinner = '<div class="wooqv-spinner-inner">'.$spinner.'</div>';
	
	if($wrapSpinner) {
		$spinner = '<div class="wooqv-spinner-wrap">'.$spinner.'</div>';
	}	
	
	if($return) {
		return $spinner;
	}
	
	echo $spinner;
}

function wooqv_option($id, $default = null) {
	
	if(defined('WOOQV_LITE_PLUGIN') && isset($default)) {
		return $default;
	}
	
	if(class_exists('Woo_Quick_View_Customizer')) {
		
		if(!empty($_POST['customized'])) {

			$options = json_decode(stripslashes($_POST['customized']), true);

			if(isset($options['wooqv['.$id.']'])) {
				return $options['wooqv['.$id.']'];
			}
		}
			
		return Woo_Quick_View_Customizer::get_option($id);
		
	}else{
		
		$options = get_option('wooqv');	
		
		if(isset($options[$id])) {
			return $options[$id];
		}
			
		return $default;
	}
}

function wooqv_update_option($id, $value) {
	
	$options = get_option('wooqv');
	
	$options[$id] = $value;
	
	update_option('wooqv', $options);
}

function wooqv_delete_option($id) {
	
	$options = get_option('wooqv');
	
	if(isset($options[$id])) {
		unset($options[$id]);
	}
	
	update_option('wooqv', $options);
}

function wooqv_is_action($action) {
	
	if(!empty($_GET['wooqvaction']) && $_GET['wooqvaction'] == $action) {
		return true;
	}
	return false;
}

