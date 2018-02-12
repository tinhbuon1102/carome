<?php
/*
 * Text Widget
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

class elsey_text_widget extends WP_Widget {

  /**
   * Specifies the widget name, description, class name and instatiates it
   */
  public function __construct() {
    parent::__construct(
      'elsey-text-widget',
      ELSEY_NAME_P . __( ': Text Widget', 'elsey-core' ),
      array(
        'classname'   => 'els-text-widget',
        'description' => ELSEY_NAME_P . __( ' widget that displays contents.', 'elsey-core' )
      )
    );
  }

  /**
   * Generates the back-end layout for the widget
   */
  public function form( $instance ) {

    // Default Values
    $instance   = wp_parse_args( $instance, array(
      'title'   => '',
      'content' => ''
    ));

    // Title
    $title_value   = esc_attr( $instance['title'] );
    $title_field   = array(
      'id'         => $this->get_field_name('title'),
      'name'       => $this->get_field_name('title'),
      'type'       => 'text',
      'title'      => __( 'Title :', 'elsey-core' ),
      'wrap_class' => 'elsey-cs-widget-fields',
    );
    echo cs_add_element( $title_field, $title_value );

    // Content
    $content_value = esc_attr( $instance['content'] );
    $content_field = array(
      'id'         => $this->get_field_name('content'),
      'name'       => $this->get_field_name('content'),
      'type'       => 'textarea',
      'shortcode'  => true,
      'attributes' => array(
        'rows'     => 16,
        'cols'     => 20,
      ),
      'title'      => __( 'Content :', 'elsey-core' ),
    );
    echo cs_add_element( $content_field, $content_value );
  }

  /**
   * Processes the widget's values
   */
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    // Update values
    $instance['title']      = strip_tags( stripslashes( $new_instance['title'] ) );
    $instance['content']    = strip_tags( stripslashes( $new_instance['content'] ) );

    return $instance;
  }

  /**
   * Output the contents of the widget
   */
  public function widget( $args, $instance ) {
    // Extract the arguments
    extract( $args );

    $title      = apply_filters( 'widget_title', $instance['title'] );
    $content    = $instance['content'];

    // Display the markup before the widget
    echo $before_widget;

    if ( $title ) {
      echo $before_title . $title . $after_title;
    }

    echo do_shortcode($content);

    // Display the markup after the widget
    echo $after_widget;
  }

}

// Register the widget using an annonymous function
add_action( 'widgets_init', create_function( '', 'register_widget( "elsey_text_widget" );' ) );