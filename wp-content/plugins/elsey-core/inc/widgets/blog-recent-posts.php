<?php
/*
 * Recent Post Widget
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

class elsey_recent_posts extends WP_Widget {

  /**
   * Specifies the widget name, description, class name and instatiates it
   */
  public function __construct() {
    parent::__construct(
      'elsey-recent-blog',
      ELSEY_NAME . esc_html__( ' : Recent Posts', 'elsey-core' ),
      array(
        'classname'   => 'els-recent-blog-widget',
        'description' => ELSEY_NAME . esc_html__( ' widget that displays recent posts.', 'elsey-core' )
      )
    );
  }

  /**
   * Generates the back-end layout for the widget
   */
  public function form( $instance ) {
    // Default Values
    $instance = wp_parse_args( $instance, array(
      'title'           => esc_html__( 'Recent Posts', 'elsey-core' ),
      'ptypes'          => 'post',
      'limit'           => '3',
      'date'            => true,
      'category'        => '',
      'order'           => '',
      'orderby'         => '',
      'style'           => '',
    ));

    // Title
    $title_value = esc_attr( $instance['title'] );
    $title_field = array(
      'id'              => $this->get_field_name('title'),
      'name'            => $this->get_field_name('title'),
      'type'            => 'text',
      'title'           => esc_html__( 'Title :', 'elsey-core' ),
      'wrap_class'      => 'els-cs-widget-fields',
    );
    echo cs_add_element( $title_field, $title_value );

    // Style
    $style_value  = esc_attr( $instance['style'] );
    $style_field  = array(
      'id'              => $this->get_field_name('style'),
      'name'            => $this->get_field_name('style'),
      'type'            => 'select',
      'options'         => array(
        'ONE'           => 'Style One (Sidebar)',
        'TWO'           => 'Style Two (Footer)',
      ),
      'default_option'  => __( 'Select Style', 'elsey-core' ),
      'title'           => __( 'Style :', 'elsey-core' ),
    );
    echo cs_add_element( $style_field, $style_value );

    // Post Type
    $ptypes_value = esc_attr( $instance['ptypes'] );
    $ptypes_field = array(
      'id'              => $this->get_field_name('ptypes'),
      'name'            => $this->get_field_name('ptypes'),
      'type'            => 'select',
      'options'         => 'post_types',
      'default_option'  => esc_html__( 'Select Post Type', 'elsey-core' ),
      'title'           => esc_html__( 'Post Type :', 'elsey-core' ),
    );
    echo cs_add_element( $ptypes_field, $ptypes_value );

    // Limit
    $limit_value = esc_attr( $instance['limit'] );
    $limit_field = array(
      'id'              => $this->get_field_name('limit'),
      'name'            => $this->get_field_name('limit'),
      'type'            => 'text',
      'title'           => esc_html__( 'Limit :', 'elsey-core' ),
      'help'            => esc_html__( 'How many posts want to show?', 'elsey-core' ),
    );
    echo cs_add_element( $limit_field, $limit_value );

    // Date
    $date_value = esc_attr( $instance['date'] );
    $date_field = array(
      'id'              => $this->get_field_name('date'),
      'name'            => $this->get_field_name('date'),
      'type'            => 'switcher',
      'on_text'         => esc_html__( 'Yes', 'elsey-core' ),
      'off_text'        => esc_html__( 'No', 'elsey-core' ),
      'title'           => esc_html__( 'Display Date :', 'elsey-core' ),
    );
    echo cs_add_element( $date_field, $date_value );

    // Category
    $category_value = esc_attr( $instance['category'] );
    $category_field = array(
      'id'              => $this->get_field_name('category'),
      'name'            => $this->get_field_name('category'),
      'type'            => 'text',
      'title'           => esc_html__( 'Category :', 'elsey-core' ),
      'help'            => esc_html__( 'Enter category slugs with comma(,) for multiple items', 'elsey-core' ),
    );
    echo cs_add_element( $category_field, $category_value );

    // Order
    $order_value = esc_attr( $instance['order'] );
    $order_field = array(
      'id'              => $this->get_field_name('order'),
      'name'            => $this->get_field_name('order'),
      'type'            => 'select',
      'options'         => array(
        'ASC'           => 'Ascending',
        'DESC'          => 'Descending',
      ),
      'default_option'  => esc_html__( 'Select Order', 'elsey-core' ),
      'title'           => esc_html__( 'Order :', 'elsey-core' ),
    );
    echo cs_add_element( $order_field, $order_value );

    // Orderby
    $orderby_value = esc_attr( $instance['orderby'] );
    $orderby_field = array(
      'id'              => $this->get_field_name('orderby'),
      'name'            => $this->get_field_name('orderby'),
      'type'            => 'select',
      'options'         => array(
        'none'          => esc_html__('None', 'elsey-core'),
        'ID'            => esc_html__('ID', 'elsey-core'),
        'author'        => esc_html__('Author', 'elsey-core'),
        'title'         => esc_html__('Title', 'elsey-core'),
        'name'          => esc_html__('Name', 'elsey-core'),
        'type'          => esc_html__('Type', 'elsey-core'),
        'date'          => esc_html__('Date', 'elsey-core'),
        'modified'      => esc_html__('Modified', 'elsey-core'),
        'rand'          => esc_html__('Random', 'elsey-core'),
      ),
      'default_option'  => esc_html__( 'Select OrderBy', 'elsey-core' ),
      'title'           => esc_html__( 'OrderBy :', 'elsey-core' ),
    );
    echo cs_add_element( $orderby_field, $orderby_value );

  }

  /**
   * Processes the widget's values
   */
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    // Update values
    $instance['title']     = strip_tags( stripslashes( $new_instance['title'] ) );
    $instance['ptypes']    = strip_tags( stripslashes( $new_instance['ptypes'] ) );
    $instance['limit']     = strip_tags( stripslashes( $new_instance['limit'] ) );
    $instance['date']      = strip_tags( stripslashes( $new_instance['date'] ) );
    $instance['category']  = strip_tags( stripslashes( $new_instance['category'] ) );
    $instance['order']     = strip_tags( stripslashes( $new_instance['order'] ) );
    $instance['orderby']   = strip_tags( stripslashes( $new_instance['orderby'] ) );
    $instance['style']     = strip_tags( stripslashes( $new_instance['style'] ) );

    return $instance;
  }

  /**
   * Output the contents of the widget
   */
  public function widget( $args, $instance ) {
    // Extract the arguments
    extract( $args );

    $title        = apply_filters( 'widget_title', $instance['title'] );
    $ptypes       = $instance['ptypes'];
    $limit        = $instance['limit'];
    $display_date = $instance['date'];
    $category     = $instance['category'];
    $order        = $instance['order'];
    $orderby      = $instance['orderby'];
    $pstyle       = $instance['style'];

    $args = array(
      'post_type'           => esc_attr($ptypes),
      'posts_per_page'      => (int)$limit,
      'orderby'             => esc_attr($orderby),
      'order'               => esc_attr($order),
      'category_name'       => esc_attr($category),
      'ignore_sticky_posts' => 1,
    );

    $elsey_rpw = new WP_Query( $args );
    global $post;

    // Display the markup before the widget
    echo $before_widget;

    if ($title) {
      echo $before_title . $title . $after_title;
    }

    if ($pstyle === 'TWO') { 
      echo '<div class="els-recent-blog-footer"><ul>';
    } else {
      echo '<div class="els-recent-blog-sidebar">';
    }

    if ($elsey_rpw->have_posts()) : while ($elsey_rpw->have_posts()) : $elsey_rpw->the_post();

      if ($pstyle === 'TWO') {  ?>

        <li>
          <h4><a href="<?php esc_url(the_permalink()) ?>"><?php the_title(); ?></a></h4>
          <?php if ($display_date === '1') { ?>
            <label><?php echo get_the_date('M'). ' ' .get_the_date('d'). ' ' .get_the_date('Y'); ?></label>
          <?php } ?>
        </li>

      <?php } else { 
        
        if(has_post_thumbnail(get_the_ID())) {
          $layout_class_rp = 'col-lg-8 col-md-8 col-sm-10 col-xs-10 boxright';
        } else {
          $layout_class_rp = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 boxright';
        } ?>

        <div class="row">
          <?php if(has_post_thumbnail(get_the_ID())) { ?>
            <div class=" col-lg-4 col-md-4 col-sm-2 col-xs-2 box">
              <a href="<?php esc_url(the_permalink()) ?>"><?php the_post_thumbnail(  array(80, 80) ); ?></a>
            </div>
          <?php } ?>
          <div class="<?php echo esc_attr($layout_class_rp); ?>">
            <h4>
              <a href="<?php esc_url(the_permalink()) ?>"><?php the_title(); ?></a>
            </h4>
            <?php if ($display_date === '1') { ?>
            <label>
              <?php echo get_the_date('M'). ' ' .get_the_date('d'). ' ' .get_the_date('Y'); ?>
            </label>
            <?php } ?>
          </div>
        </div>

      <?php }

    endwhile; endif;
    wp_reset_postdata();

    if ($pstyle === 'TWO') { 
      echo '</ul></div>';
    } else {
      echo '</div>';
    }

    // Display the markup after the widget
    echo $after_widget;

  }
}

// Register the widget using an annonymous function
add_action( 'widgets_init', create_function( '', 'register_widget( "elsey_recent_posts" );' ) );
