<?php
/**
 * Add Custom Params
 */

/* 1. Notice */
if(!class_exists('ELSEY_Notice_Field'))
{
	class ELSEY_Notice_Field
	{
		function __construct()
		{
			if( function_exists( 'vc_add_shortcode_param' ) )
			{
				vc_add_shortcode_param( 'notice' , array( &$this, 'notice_settings_field' ) );
			}
		}

		function notice_settings_field( $settings, $value )
		{
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$param_heading = isset($settings['heading']) ? $settings['heading'] : '';
			$type = isset($settings['type']) ? $settings['type'] : '';
			$class = isset($settings['class']) ? $settings['class'] : '';

			$output = '<div class="cs-field-'. $type .' wpb_vc_param_value '. $param_name .'" name="' . esc_attr( $settings['param_name'] ) . '"><div class="cs-'. $type .' '. $class .'">'. $param_heading .'</div><div class="clear"></div></div>';
			return $output;
		}

	}
}

if( class_exists( 'ELSEY_Notice_Field' ) )
{
	$elsNoticeParam = new ELSEY_Notice_Field();
}

/* 2. Switcher */
if(!class_exists('VcSwitcherField'))
{
	class VcSwitcherField {

		public function __construct() {
			add_action( 'vc_load_default_params', array(
				&$this,
				'vc_load_vc_switcher_param',
			) );

			add_action( 'vc_backend_editor_render', array(
				&$this,
				'vc_enqueue_editor_scripts_befe',
			) );
		}

		public function vc_enqueue_editor_scripts_befe() {
			wp_enqueue_script( 'core-scripts', ELSEY_PLUGIN_ASTS . '/core-scripts.js' );
		}

		/**
		 * Add custom param to system
		 */
		public function vc_load_vc_switcher_param() {
			vc_add_shortcode_param( 'switcher', array(
				&$this,
				'vc_switcher_form_field',
			) );
		}

		/**
		 * Checkbox shortcode attribute type.
		 *
		 * @param $settings
		 * @param string $value
		 *
		 * @return string - html string.
		 */
		public function vc_switcher_form_field( $settings, $value ) {
			$output = '';
			$on_text = isset($settings['on_text']) ? $settings['on_text'] : 'On';
			$off_text = isset($settings['off_text']) ? $settings['off_text'] : 'Off';
			$current_value = is_string( $value ) ? ( strlen( $value ) > 0 ? explode( ',', $value ) : array() ) : (array) $value;
			$values = isset( $settings['value'] ) && is_array( $settings['value'] ) ? $settings['value'] : array( '' => 'true' );
			if ( ! empty( $values ) ) {
				foreach ( $values as $label => $v ) {
					$checked = count( $current_value ) > 0 && in_array( $v, $current_value ) ? ' checked' : '';
					$output .= '<div class="cs-field-switcher"><div class="cs-fieldset"><label class="vc_checkbox-label"><input id="' . $settings['param_name'] . '-' . $v . '" value="' . $v . '" class="wpb_vc_param_value ' . $settings['param_name'] . ' ' . $settings['type'] . '" type="checkbox" name="' . $settings['param_name'] . '"' . $checked . '> ' . $label . '<em data-on="'. $on_text .'" data-off="'. $off_text .'"></em><span></span></label></div></div>';
				}
			}

			return $output;
		}
	}
}

if( class_exists( 'VcSwitcherField' ) )
{
	$switcher = new VcSwitcherField();
}

/* 3. Icon Picker */
if(!class_exists('ELSEY_Icon_Picker_Field'))
{
	class ELSEY_Icon_Picker_Field
	{
		function __construct()
		{
			if( function_exists( 'vc_add_shortcode_param' ) )
			{
				vc_add_shortcode_param( 'elsey_icon' , array( &$this, 'elsey_icon_settings_field' ) );
			}
		}

		function elsey_icon_settings_field( $settings, $value )
		{

			$hidden    = ( empty( $value ) ) ? ' hidden' : '';
		  $icon      = ( !empty( $value ) ) ? ' class="'. $value . '"' : '';

		  $output    = '<div class="cs-field-icon"><div class="cs-icon-select"><span class="cs-icon-preview'. $hidden .'"><i '. $icon .'></i></span><a href="#" class="button button-primary cs-icon-add">Add Icon</a><a href="#" class="button cs-warning-primary cs-icon-remove'. $hidden .'">Remove Icon</a><input type="hidden" name="'. $settings['param_name'] .'" class="wpb_vc_param_value vc_cs_icon icon-value '. $settings['param_name'] .' '. $settings['type'] .'" value="'. $value .'"/></div></div>';

		  return $output;
		}

	}
}

if( class_exists( 'ELSEY_Icon_Picker_Field' ) )
{
	$elsVtIconParam = new ELSEY_Icon_Picker_Field();
}
