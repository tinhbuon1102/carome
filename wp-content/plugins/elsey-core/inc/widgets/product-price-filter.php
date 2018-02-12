<?php
/*
 * Product Price Filter Widget
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

  class elsey_product_price_filter extends WP_Widget {

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
      parent::__construct(
        'els-product-price-filter',
        ELSEY_NAME_P . esc_html__( ' : Product Price Filter', 'elsey-core' ),
        array(
          'classname'   => 'els-product-price-filter els-filter-column',
          'description' => ELSEY_NAME_P . esc_html__( ' widget that works for product price filtering.', 'elsey-core' )
        )
      );
    }

    /**
     * Generates the back-end layout for the widget
     */
    public function form( $instance ) {

      // Default Values
      $instance    = wp_parse_args( $instance, array(
        'title'    => esc_html__( 'Filter By Price', 'elsey-core' ),
      ));

      // Title
      $title_value = esc_attr( $instance['title'] );
      $title_field = array(
        'id'         => $this->get_field_name('title'),
        'name'       => $this->get_field_name('title'),
        'type'       => 'text',
        'title'      => esc_html__( 'Title', 'elsey-core' ),
        'wrap_class' => 'els-cs-widget-fields',
      );
      echo cs_add_element( $title_field, $title_value );

    }

    /**
     * Processes the widget's values
     */
    public function update( $new_instance, $old_instance ) {
      $instance = $old_instance;

      $instance['title']    = strip_tags( stripslashes( $new_instance['title'] ) );

      return $instance;
    }

    /**
     * Output the contents of the widget
     */
    public function widget( $args, $instance ) {
      global $wp_query, $wp;

      extract( $args );

      $title  = apply_filters( 'widget_title', $instance['title'] );
      $output = '';

      $e_uniqid    = uniqid();
      $unique_name = 'els-price-filter-'. $e_uniqid;

      // Display the markup before the widget
      echo $before_widget;

      $output.= $before_title;
      if ( $title ) { $output.= $title; }
      $output.= $after_title;

      $output.= '<div class="'.esc_attr( $unique_name ).' els-filter-content" id="els-price-filter">';

      wp_enqueue_script( 'wc-price-slider' );

      // Find min and max price in current result set
      $prices = $this->get_filtered_price();
      $min    = floor($prices->min_price);
      $max    = ceil($prices->max_price);

      if ( $min === $max ) {
        return;
  	  }

      if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {

     	  $tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
     	  $class_max   = $max;

        foreach ( $tax_classes as $tax_class ) {
  		    if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
  		      $class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );
  		    }
  		  }

  	  	$max = $class_max;
  		}

  	  $output .= '<div class="price_slider_wrapper">
                  <div class="price_slider" style="display:none;"></div>
      			 				<div class="price_slider_amount">
  			    				<input type="text" id="min_price" name="min_price" value="'.esc_attr($min).'" data-min="'.esc_attr(apply_filters('woocommerce_price_filter_widget_min_amount', $min)).'" placeholder="'.esc_html__('Min price', 'elsey-core').'" />
  			    				<input type="text" id="max_price" name="max_price" value="'.esc_attr($max).'" data-max="'.esc_attr(apply_filters('woocommerce_price_filter_widget_max_amount', $max)).'" placeholder="'.esc_html__('Max price', 'elsey-core').'" />
  			    				<button type="button" class="button" id="els-price-filter-submit">'.esc_html__( 'Filter', 'elsey-core' ).'</button>
  			    				<div class="price_label" style="display:none;">'.esc_html__('Range: ', 'elsey-core').'<span class="from"></span> &mdash; <span class="to"></span></div>
  			    				<div class="clear"></div>
      			 			</div>
  		       		  </div>';

  	  $output .= '</div>';

  	  echo $output;

  	  // Display the markup after the widget
  	  echo $after_widget;
    }

    /**
    * Get filtered min price for current products.
    * @return int
    */
    protected function get_filtered_price() {
  		global $wpdb, $wp_the_query;

  		$args       = $wp_the_query->query_vars;
  		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
  		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

  		if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
  			$tax_query[] = array(
  				'taxonomy' => $args['taxonomy'],
  				'terms'    => array( $args['term'] ),
  				'field'    => 'slug',
  			);
  		}

  		foreach ( $meta_query as $key => $query ) {
  			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
  				unset( $meta_query[ $key ] );
  			}
  		}

  		$meta_query = new WP_Meta_Query( $meta_query );
  		$tax_query  = new WP_Tax_Query( $tax_query );

  		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
  		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

  		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
  		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
  		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
  					AND {$wpdb->posts}.post_status = 'publish'
  					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
  					AND price_meta.meta_value > '' ";
  		$sql .= $tax_query_sql['where'].$meta_query_sql['where'];

  		return $wpdb->get_row( $sql );
    }

  }

  // Register the widget using an annonymous function
  add_action( 'widgets_init', create_function( '', 'register_widget( "elsey_product_price_filter" );' ) );

}
