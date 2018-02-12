<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/*
 * Framework Extend : VictorThemes Changes Mentioned by "Custom Changes"
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

/**
 *
 * Field: Switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_switcher extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();
    $label = ( isset( $this->field['label'] ) ) ? '<div class="cs-text-desc">'. $this->field['label'] . '</div>' : '';

    // Custom Changes - Added on_text & off_text
    // Custom Attribute for On/Off Texts
    $on_text = ( isset( $this->field['on_text'] ) ) ? $this->field['on_text'] : esc_html__( 'On', 'elsey' );
    $off_text = ( isset( $this->field['off_text'] ) ) ? $this->field['off_text'] : esc_html__( 'Off', 'elsey' );

    echo '<label><input type="checkbox" name="'. $this->element_name() .'" value="1"'. $this->element_class() . $this->element_attributes() . checked( $this->element_value(), 1, false ) .'/><em data-on="'. $on_text .'" data-off="'. $off_text .'"></em><span></span></label>' . $label;
    echo $this->element_after();

  }

}
