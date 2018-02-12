<?php
/*
 * Instagram Widget
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

class elsey_instagram_feed extends WP_Widget {

  /**
   * Specifies the widget name, description, class name and instatiates it
   */
  public function __construct() {
    parent::__construct(
      'elsey-instagram-feed',
      __( ELSEY_NAME . ': Instagram Feed', 'elsey-core' ),
      array(
        'classname'    => 'els-instagram-feed',
        'description'  => __( ELSEY_NAME . ' widget that displays instagram images.', 'elsey-core' )
      )
    );
  }

  /**
   * Generates the back-end layout for the widget
   */
  public function form( $instance ) {

    // Default Values
    $instance = wp_parse_args( $instance, array(
      'title'          => __( 'Instagram', 'elsey-core' ),
      'style'          => '',    
      'user_name'      => '',
      'userid'         => '',
      'accesstoken'    => '',
      'limit'          => '',
      'sortby'         => 'random',
      'follow_us_text' => '',
    ));
    
    // Style
    $style_value  = esc_attr( $instance['style'] );
    $style_field  = array(
      'id'             => $this->get_field_name('style'),
      'name'           => $this->get_field_name('style'),
      'type'           => 'select',
      'options'        => array(
        'ONE'          => 'Style One (Sidebar)',
        'TWO'          => 'Style Two (Footer)',
      ),
      'default_option' => __( 'Select Style', 'elsey-core' ),
      'title'          => __( 'Style :', 'elsey-core' ),
    );
    echo cs_add_element( $style_field, $style_value );

    // Title
    $title_value = esc_attr( $instance['title'] );
    $title_field = array(
      'id'             => $this->get_field_name('title'),
      'name'           => $this->get_field_name('title'),
      'type'           => 'text',
      'title'          => __( 'Title :', 'elsey-core' ),
      'wrap_class'     => 'els-cs-widget-fields',
    );
    echo cs_add_element( $title_field, $title_value );
    
    // User ID
    $userid_value = esc_attr( $instance['userid'] );
    $userid_field = array(
      'id'             => $this->get_field_name('userid'),
      'name'           => $this->get_field_name('userid'),
      'type'           => 'text',
      'title'          => __( 'User ID :', 'elsey-core' ),
      'wrap_class'     => 'els-cs-widget-fields',
    );
    echo cs_add_element( $userid_field, $userid_value );  
    
    // Access Token
    $accesstoken_value = esc_attr( $instance['accesstoken'] );
    $accesstoken_field = array(
      'id'             => $this->get_field_name('accesstoken'),
      'name'           => $this->get_field_name('accesstoken'),
      'type'           => 'text',
      'title'          => __( 'Access Token :', 'elsey-core' ),
      'wrap_class'     => 'els-cs-widget-fields',
    );
    echo cs_add_element( $accesstoken_field, $accesstoken_value );
   
    // Limit
    $limit_value = esc_attr( $instance['limit'] );
    $limit_field = array(
      'id'             => $this->get_field_name('limit'),
      'name'           => $this->get_field_name('limit'),
      'type'           => 'text',
      'title'          => __( 'Limit :', 'elsey-core' ),
      'help'           => __( 'How many images want to show?', 'elsey-core' ),
    );
    echo cs_add_element( $limit_field, $limit_value );
    
    // SortBy
    $sortby_value = esc_attr( $instance['sortby'] );
    $sortby_field = array(
      'id'             => $this->get_field_name('sortby'),
      'name'           => $this->get_field_name('sortby'),
      'type'           => 'select',
      'options'        => array(         
        'most_recent'  => __('Most Recent', 'elsey-core'),
        'least_recent' => __('Least Recent', 'elsey-core'),
        'most_liked'   => __('Most Liked', 'elsey-core'),
        'least_liked'  => __('Least Liked', 'elsey-core'),
        'most_comtd'   => __('Most Commented', 'elsey-core'),
        'least_comtd'  => __('Least Commented', 'elsey-core'),
        'random'       => __('Random', 'elsey-core'),      
        'none'         => __('None', 'elsey-core')
      ),
      'default_option' => __( 'Select SortBy', 'elsey-core' ),
      'title'          => __( 'SortBy :', 'elsey-core' ),
    );
    echo cs_add_element( $sortby_field, $sortby_value ); 
  }

  /**
   * Processes the widget's values
   */
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    // Update values
    $instance['title']       = strip_tags( stripslashes( $new_instance['title'] ) );
    $instance['user_name']   = strip_tags( stripslashes( $new_instance['user_name'] ) );
    $instance['userid']      = strip_tags( stripslashes( $new_instance['userid'] ) );
    $instance['accesstoken'] = strip_tags( stripslashes( $new_instance['accesstoken'] ) );
    $instance['limit']       = strip_tags( stripslashes( $new_instance['limit'] ) );    
    $instance['sortby']      = strip_tags( stripslashes( $new_instance['sortby'] ) );
    $instance['style']       = strip_tags( stripslashes( $new_instance['style'] ) );

    return $instance;
  }

  /**
   * Output the contents of the widget
   */
  public function widget( $args, $instance ) {
    // Extract the arguments
    extract( $args );

    $title       = apply_filters( 'widget_title', $instance['title'] );
    $userid      = $instance['userid'];
    $user_name   = $instance['user_name'];
    $accesstoken = $instance['accesstoken'];
    $limit       = $instance['limit'];
    $sortby      = $instance['sortby'] ? $instance['sortby'] : 'random';
    $style       = $instance['style'];
    
    // Display the markup before the widget
    echo $before_widget;

    if ( $title ) {
      echo $before_title . $title . $after_title;
    }
    
    if ($style === 'ONE') {

      if ($userid && $accesstoken) {
        $e_uniqid = uniqid();
        $id_name  = 'els-sidebar-instagram-'. $e_uniqid;
        $limit    = ($limit) ? $limit : '8' ; ?>
              
        <div class="row" id="<?php echo $id_name; ?>"></div>     

        <script type="text/javascript">
          var instaFeed_<?php echo $e_uniqid; ?> = new Instafeed({
            get:         'user',
            userId:      <?php echo $userid; ?>,
            accessToken: '<?php echo $accesstoken; ?>',
            target:      '<?php echo $id_name; ?>',
            limit:       <?php echo $limit; ?>,
            sortBy:      '<?php echo $sortby; ?>',
            resolution:  'standard_resolution',
            template:    '<div class="col-lg-6 col-md-6 col-sm-3 col-xs-3 box"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></div>',
          });  
          window.addEventListener('load', instaFeed_<?php echo $e_uniqid; ?>.run(), false);
        </script> 

      <?php 
      } else {
        esc_html_e( '<p>No value found for <strong>User ID</strong> or <strong>AccessToken</strong>.</p>', 'elsey-core' );
      }

    } else { 

      if ($userid && $accesstoken) {  
        $e_uniqid = uniqid();
        $id_name  = 'els-footer-instagram-'. $e_uniqid;
        $limit    = ($limit) ? $limit : '9' ; ?>

        <!-- Instagram Feed Start -->
        <div class="els-instagram-wrap">
          <div class="row" id="<?php echo $id_name; ?>"></div>    
        </div>
        <!-- Instagram Feed End -->

        <script type="text/javascript">
          var instaFeed_<?php echo $e_uniqid; ?> = new Instafeed({
            get:         'user',
            userId:       <?php echo $userid; ?>,
            accessToken: '<?php echo $accesstoken; ?>',
            target:      '<?php echo $id_name; ?>',
            limit:        <?php echo $limit; ?>,
            sortBy:      '<?php echo $sortby; ?>',
            resolution:  'standard_resolution',
            template:    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 box"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></div>',
          });  
          window.addEventListener('load', instaFeed_<?php echo $e_uniqid; ?>.run(), false);
        </script> 

    <?php    
      } else {
        esc_html_e( '<p>No value found for <strong>User ID</strong> or <strong>AccessToken</strong>.</p>', 'elsey-core' );
      }    

    }     

    echo $after_widget;  
  }
}

// Register the widget using an annonymous function
add_action( 'widgets_init', create_function( '', 'register_widget( "elsey_instagram_feed" );' ) );
