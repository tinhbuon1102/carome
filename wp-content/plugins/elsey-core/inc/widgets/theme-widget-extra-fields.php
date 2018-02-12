<?php
/*
 * Add Extra Field for WordPress Widgets
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

// Add Fields for All WordPress Default Widgets
function elsey_in_widget_form($t,$return,$instance) {

  $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'elsey_custom_class' => '') );
  if ( !isset($instance['elsey_custom_class']) ) $instance['elsey_custom_class'] = null;
  
?>

  <p class="els-widget-field cs-element">
    <label for="<?php echo esc_attr($t->get_field_id('elsey_custom_class')); ?>"><?php esc_html_e('Custom Class:', 'elsey-core'); ?></label>
    <input class="widefat" type="text" name="<?php echo esc_attr($t->get_field_name('elsey_custom_class')); ?>" id="<?php echo esc_attr($t->get_field_id('elsey_custom_class')); ?>" value="<?php echo esc_attr($instance['elsey_custom_class']);?>" />
    <span class="cs-text-desc"><?php echo __('Add your custom classes.', 'elsey-core'); ?></span>
    <div class="clear"></div>
  </p>

<?php
  $retrun = null;
  return array($t,$return,$instance);

}
add_action('in_widget_form', 'elsey_in_widget_form',5,3);

// Update Fields Data
function elsey_in_widget_form_update($instance, $new_instance, $old_instance){

  $instance['elsey_custom_class'] = strip_tags($new_instance['elsey_custom_class']);
  return $instance;

}
add_filter('widget_update_callback', 'elsey_in_widget_form_update',5,3);

// Display Fields Output
function elsey_dynamic_sidebar_params($params){

  global $wp_registered_widgets;
  $widget_id = $params[0]['widget_id'];
  $widget_obj = $wp_registered_widgets[$widget_id];
  $widget_opt = get_option($widget_obj['callback'][0]->option_name);
  $widget_num = $widget_obj['params'][0]['number'];

  if(isset($widget_opt[$widget_num]['elsey_custom_class'])) {
    $elsey_custom_class = $widget_opt[$widget_num]['elsey_custom_class'];
  } else {
    $elsey_custom_class = '';
  }
  
  $params[0]['before_widget'] = preg_replace('/class="/', 'class="'.$elsey_custom_class.' ',  $params[0]['before_widget'], 1);
  
  return $params;

}
add_filter('dynamic_sidebar_params', 'elsey_dynamic_sidebar_params');