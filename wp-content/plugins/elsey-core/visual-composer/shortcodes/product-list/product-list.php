<?php
/* ==========================================================
  Product
=========================================================== */

if ( class_exists( 'WooCommerce' ) ) {

  if ( !function_exists( 'elsey_product_list_function') ) {

    function elsey_product_list_function( $atts, $content = NULL ) {

	    extract (shortcode_atts( array( 
	      'pr_list_style'   => 'pr-bestsell',
	      'pr_list_limit'   => '3',
	      'pr_list_order'   => 'ASC',
	      'pr_list_orderby' => '',
	      'class'           => '',
	    ), $atts));

	    $e_uniqid = uniqid();
	    $pr_list_styled_class  = 'els-pr-list-'. $e_uniqid;

	    // Turn output buffer on
	    ob_start(); ?>

	    <div class="els-pr-list-products <?php echo esc_attr($pr_list_styled_class.' '.$class); ?>">

	    <?php
	    $meta_query = WC()->query->get_meta_query();
			$tax_query  = WC()->query->get_tax_query();

			$args = array(
		    'post_type'           => 'product',
		    'post_status'			    => 'publish',
				'ignore_sticky_posts'	=> 1,
		    'posts_per_page'      => (int)$pr_list_limit,
		  );

		  if ( $pr_list_style === 'pr-bestsell' ) {

		  	echo '<h5 class="els-pr-list-title"><span>'.esc_html__( 'Best Selling Products', 'elsey-core' ).'</span></h5>';

		  	$args['meta_key'] = 'total_sales';
		  	$args['orderby']  = 'meta_value_num';

	    } else if ( $pr_list_style === 'pr-featured' ) {

	    	echo '<h5 class="els-pr-list-title"><span>'.esc_html__( 'Featured Products', 'elsey-core' ).'</span></h5>';

	    	$tax_query[] = array(
	        'taxonomy' => 'product_visibility',
	        'field'    => 'name',
	        'terms'    => 'featured',
	        'operator' => 'IN',
	      );

	      $args['order']   = $pr_list_order;
	    	$args['orderby'] = $pr_list_orderby;

	    } else if ( $pr_list_style === 'pr-random' ) {

	    	echo '<h5 class="els-pr-list-title"><span>'.esc_html__( 'Random Products', 'elsey-core' ).'</span></h5>';

	    	$args['order']   = $pr_list_order;
	    	$args['orderby'] = 'rand';

	    } else if ( $pr_list_style === 'pr-recent' ) {

	    	echo '<h5 class="els-pr-list-title"><span>'.esc_html__( 'Recent Products', 'elsey-core' ).'</span></h5>';

	    	$args['order']   = $pr_list_order;
	    	$args['orderby'] = 'date';

	    } else if ( $pr_list_style === 'pr-onsales' ) {

	    	echo '<h5 class="els-pr-list-title"><span>'.esc_html__( 'Sales Products', 'elsey-core' ).'</span></h5>';

	    	$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
	    	$args['order']   = $pr_list_order;
	    	$args['orderby'] = $pr_list_orderby;

	    } else if ( $pr_list_style === 'pr-toprated' ) {

	    	echo '<h5 class="els-pr-list-title"><span>'.esc_html__( 'Top Rated Products', 'elsey-core' ).'</span></h5>';

	    	$args['meta_key'] = '_wc_average_rating';
	    	$args['order']   = $pr_list_order;
	    	$args['orderby'] = $pr_list_orderby;

	    } 
	 		
	    $args['meta_query'] = $meta_query;
	    $args['tax_query']  = $tax_query;

			//print_r($args);
			$elsey_products_list = new WP_Query($args); ?>

		    <?php
				if ( $elsey_products_list->have_posts() ) : while ( $elsey_products_list->have_posts() ) : $elsey_products_list->the_post(); 
					global $product;

					if (has_post_thumbnail($product->get_id())) {
	        	$layout_class_pr_info = 'col-lg-9 col-md-12 col-sm-12 col-xs-12';
	        } else {
	          $layout_class_pr_info = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
	        } 

	        echo '<div class="row">';

	        if (has_post_thumbnail($product->get_id())) { 
	          echo '<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 els-pr-list-img">';
							echo get_the_post_thumbnail( $product->get_id(), array( 75, 75 ) );
	          echo '</div>';
	        } ?>

					<div class="<?php echo esc_attr($layout_class_pr_info); ?> els-pr-list-info">
					  <div class="els-pr-list-name">
					    <a href="<?php echo get_the_permalink( $product->get_id() ); ?>">
					      <?php echo esc_attr( get_the_title( $product->get_id() ) ); ?>
					    </a>
					  </div>
						<div class="els-pr-list-price price"><?php echo $product->get_price_html(); ?></div>
				  </div>

					<?php
				  echo '</div>';

				endwhile; endif;
				wp_reset_postdata();
				?>

			</div>

			<?php
			// Return outbut buffer
    	return ob_get_clean();
    }

  }

  add_shortcode( 'elsey_product_list', 'elsey_product_list_function' );

}
