<?php
/* ==========================================================
  Product
=========================================================== */

if ( class_exists( 'WooCommerce' ) ) {

  if ( !function_exists('elsey_product_function')) {

    function elsey_product_function( $atts, $content = NULL ) {

	    extract(shortcode_atts(array(
        'pr_title'     => '',
	      'pr_style'     => 'shop-default',
	      'pr_limit'     => '12',
        'pr_column'    => 'shop-col-3',
        'pr_mscol'     => 'mscol-one',
	      // Enable & Disable
        'pr_result'    => '',
        'pr_sort_by'   => '',
        'pr_view_icon' => '',
	      'pr_nav'       => '',
	      'pr_nav_style' => 'els-pagination-one',
	      // Listing
	      'pr_order'     => 'ASC',
	      'pr_orderby'   => 'title',
	      'pr_filter'    => '',
	      'pr_cats'      => '',
        'pr_ids'       => '',
	      'class'        => '',
	    ), $atts));

	    if($pr_column === 'shop-col-6') {
        $pr_col_number = 6;
      } else if($pr_column === 'shop-col-5') {
	      $pr_col_number = 5;
	    } else if($pr_column === 'shop-col-4') {
	      $pr_col_number = 4;
	    } else {
	      $pr_col_number = 3;
	    }

      if ($pr_style === 'shop-fullgrid') {
	      $pr_style_class = 'els-shop-fullgrid els-shop-fullgrid-product';
        $pr_col_class   = 'woo-col-'.$pr_col_number;
      } else {  
      	if ($pr_style === 'shop-masonry') {
      		$pr_style_class = 'els-shop-masonry';
          $pr_col_class   = '';
      		if ($pr_mscol === 'mscol-two') {
      		  $pr_style_class.= ' els-shop-mscol-two';
      		} else {
      			$pr_style_class.= ' els-shop-mscol-one';
      		}
      	} else {
      		$pr_style_class = 'els-shop-default';
          $pr_col_class   = 'woo-col-'.$pr_col_number;
      	}
      }

      $data_attr_value = '';
      if (elsey_framework_active()) {
        $woo_lazy_load  = cs_get_option('woo_lazy_load');
        $woo_dload_size = cs_get_option('woo_dload_size');
        $woo_lazy_url   = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/lazy-load.jpg';
        if ( $woo_lazy_load === 'els-dload-small' ) {
          $data_attr_value.= 'data-dload=els-dload-small';
          $data_attr_value.= ( !empty($woo_dload_size) ) ? ' data-sload='.esc_attr($woo_dload_size).' ' : ' data-sload=767';
          $data_attr_value.= ' data-ldurl='.$woo_lazy_url;
        } 
      }
      
      $pr_pagtn_class = ($pr_nav_style === 'els-pagination-one' || $pr_nav_style === 'els-pagination-two') ? 'els-no-ajax' : 'els-ajax';

      if ($pr_title && $pr_view_icon) {
        $pr_title_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
        $pr_icon_class  = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
      } else if ($pr_title || $pr_view_icon) {
        $pr_title_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
        $pr_icon_class  = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
      } else {
        $pr_title_class = '';
        $pr_icon_class  = '';
      }

	    $e_uniqid = uniqid();
	    $pr_styled_class  = 'els-products-'. $e_uniqid;
	    $pr_shop_page_url = get_permalink(wc_get_page_id('shop'));

	    global $paged;
	    if( get_query_var( 'paged' ) )
	      $my_page = get_query_var( 'paged' );
	    else {
	      if( get_query_var( 'page' ) )
	        $my_page = get_query_var( 'page' );
	      else
	        $my_page = 1;
	      set_query_var( 'paged', $my_page );
	      $paged = $my_page;
	    }

	    $args = array(
        'paged'          => (int)$my_page,
        'post_type'      => 'product',
	      'posts_per_page' => (int)$pr_limit,
        'order'          => $pr_order,
        'orderby'        => $pr_orderby,
        'tax_query'      => array(
                              array(
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => 'exclude-from-catalog',
                                'operator' => 'NOT IN',
                              )
                            ), 
	    );

	    if ( ( $pr_filter === 'pr-filter-cats' ) && ( $pr_cats !== '' ) ) {
	      $args['product_cat'] = esc_attr($pr_cats);
	    }

      if ( ( $pr_filter === 'pr-filter-ids' ) &&  ( $pr_ids !== '' ) ) {
      	$args_pr_ids = explode(',', $pr_ids);
        $args['post__in'] = $args_pr_ids;
      }

	    $elsey_products = new WP_Query($args);

	    // Turn output buffer on
	    ob_start(); ?>

      <div class="woocommerce els-prsc-products <?php echo esc_attr($pr_styled_class.' '.$class); ?>">
        <div class="els-shop-wrapper <?php echo esc_attr($pr_col_class.' '.$pr_pagtn_class); ?>">
          
          <?php if ($pr_result || $pr_sort_by) { ?>
            <div class="els-shop-filter els-prsc-shop-filter row">
              <?php if ($pr_result) { ?>
                <div class="els-result-count col-lg-6 col-md-6 col-sm-6 col-xs-12">
                	<p class="woocommerce-result-count">
	                  <?php 
	                  $paged    = max( 1, $elsey_products->get( 'paged' ) );
	                  $per_page = $elsey_products->get( 'posts_per_page' );
	                  $total    = $elsey_products->found_posts;
	                  $first    = ( $per_page * $paged ) - $per_page + 1;
	                  $last     = min( $total, $elsey_products->get( 'posts_per_page' ) * $paged );

	                  if ( $total <= $per_page || -1 === $per_page ) {
	                    printf( _n( 'Showing the single result', 'Showing all %d results', $total, 'elsey-core' ), $total );
	                  } else {
	                    printf( _nx( 'Showing the single result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'elsey-core' ), $first, $last, $total );
	                  } ?>
	                </p>
                </div>
              <?php } if ($pr_sort_by) { ?>  
                <div class="els-order-filter col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <?php 
                  if ( 1 === (int) $elsey_products->found_posts ) { return; }
                  $orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
                  $show_default_orderby = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
                  $catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
                    'menu_order' => __( 'Default sorting', 'elsey-core' ),
                    'popularity' => __( 'Sort by popularity', 'elsey-core' ),
                    'rating'     => __( 'Sort by average rating', 'elsey-core' ),
                    'date'       => __( 'Sort by newness', 'elsey-core' ),
                    'price'      => __( 'Sort by price: low to high', 'elsey-core' ),
                    'price-desc' => __( 'Sort by price: high to low', 'elsey-core' ),
                  ) );
                  if ( ! $show_default_orderby ) {
                    unset( $catalog_orderby_options['menu_order'] );
                  }
                  if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
                    unset( $catalog_orderby_options['rating'] );
                  }
                  wc_get_template( 'loop/orderby.php', array( 'catalog_orderby_options' => $catalog_orderby_options, 'orderby' => $orderby, 'show_default_orderby' => $show_default_orderby ) ); ?>
                </div>
              <?php } ?>
            </div>
          <?php } ?>
          
          <?php 
          if ($pr_title || $pr_view_icon) { 
            echo '<div class="els-prsc-heading row">';
              if ($pr_title) {
                echo '<div class="'.esc_attr($pr_title_class).'"><h4 class="els-prsc-title">'.esc_attr($pr_title).'</h4></div>';
              }
              if ($pr_view_icon) {
                $view_image_url = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/view-all.png';
                echo '<div class="'.esc_attr($pr_icon_class).' els-prsc-view-all">';
                echo '<a href="'.esc_url($pr_shop_page_url).'">'; ?>
                <span class="els-parent-dots">
                  <span class="els-dots-row">
                   <span class="els-child-dots"></span>
                   <span class="els-child-dots"></span>
                   <span class="els-child-dots"></span>
                  </span>
                  <span class="els-dots-row">
                   <span class="els-child-dots"></span>
                   <span class="els-child-dots"></span>
                   <span class="els-child-dots"></span>
                  </span>
                  <span class="els-dots-row">
                   <span class="els-child-dots"></span>
                   <span class="els-child-dots"></span>
                   <span class="els-child-dots"></span>
                  </span>
                </span>
                <?php
                echo esc_html__('VIEW ALL PRODUCTS', 'elsey-core').'</a></div>';
              }
            echo '</div>';
          } ?>

          <div class="els-shop-load-anim">
            <div class="line-scale-pulse-out">
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
            </div>
          </div>

          <div class="els-products-full-wrap <?php echo esc_attr($pr_style_class); ?>" data-shopurl="<?php echo esc_url($pr_shop_page_url); ?>">
            <ul class="products" <?php echo esc_attr($data_attr_value); ?>>   

            <?php if ($pr_style === 'shop-masonry') { echo '<li class="els-pr-masonry-sizer"></li>'; }

              if ($elsey_products->have_posts()) : while ($elsey_products->have_posts()) : $elsey_products->the_post();   
                global $product; 
                $product_hover_image  = (cs_get_option('woo_hover_image')) ? true : false;
                $product_meta_hover_image = (isset($product_meta_options['product_hover_image_change'])) ? $product_meta_options['product_hover_image_change'] : true;
                $product_meta_options = get_post_meta( $product->get_id(), 'product_options', true );
                $product_bg_color     = (!empty($product_meta_options['product_single_bg'])) ? 'style="background:'.$product_meta_options['product_single_bg'].'"' : '';

                if ($pr_style === 'shop-masonry') { 
                  $product_masonry_size = (isset($product_meta_options['product_masonry_size'])) ? $product_meta_options['product_masonry_size'] : 'pd-wh';
                  $product_masonry_image = (isset($product_meta_options['product_masonry_image'])) ? $product_meta_options['product_masonry_image'] : '';
                  $product_masonry_hover_image = (isset($product_meta_options['product_masonry_hover_image'])) ? $product_meta_options['product_masonry_hover_image'] : ''; 
                  $product_thumbnail_id = get_post_thumbnail_id(); ?> 

                  <li <?php post_class($product_masonry_size . ' els-pr-masonry-item'); ?>>

                    <div class="els-product-image" <?php echo $product_bg_color; ?>>
                      <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="woocommerce-LoopProduct-link"></a>
                      <div class="els-product-info">
    	                  <div class="els-product-title">
    	                    <h3><a href="<?php echo get_the_permalink($product->get_id()); ?>"><?php echo esc_attr(get_the_title($product->get_id())); ?></a></h3>
    	                  </div>
    	                  <div class="els-product-price price"><?php echo $product->get_price_html(); ?></div>
    	                  <div class="els-product-atc">
    	                    <?php woocommerce_template_loop_add_to_cart( $elsey_products->post, $product ); ?>
    	                  </div>
                    	</div>

                      <?php $html = '';

    									if ( !$product->is_in_stock() ) {
    									  echo '<span class="els-product-sold">' . esc_html__( 'Sold', 'elsey-core' ) . '</span>';
    									} else if ( $product->is_on_sale() ) {
    									  echo '<span class="els-product-onsale">' . esc_html__( 'Sale!', 'elsey-core' ) . '</span>'; 
    									}

                      if ( $product_hover_image && $product_meta_hover_image ) {
                        if( isset($product_masonry_image) && isset($product_masonry_hover_image) ) {  
                          $product_thumbnail_alt_src = wp_get_attachment_image_src( $product_masonry_hover_image, 'full' );
                          $hover_parent_class = ($product_thumbnail_alt_src) ? 'els-product-has-hover' : '';
                        }
                        if ( empty($product_masonry_image) ) {
                          $product_gallery_thumbnail_ids = $product->get_gallery_image_ids();
                          $product_thumbnail_alt_id      = ($product_gallery_thumbnail_ids) ? reset($product_gallery_thumbnail_ids) : null;  
                          if ( $product_thumbnail_alt_id ) {
                            $product_thumbnail_alt_title = get_the_title($product_thumbnail_alt_id);
                            $product_thumbnail_alt_src   = wp_get_attachment_image_src( $product_thumbnail_alt_id, 'shop_catalog' );
                            $hover_parent_class = ($product_thumbnail_alt_src) ? 'els-product-has-hover' : '';
                          } else {
                            $hover_parent_class = '';
                          }   
                        }  
                      } else {
                        $hover_parent_class = '';
                      }

                      $html .= '<div class="els-product-featured-image '.esc_attr($hover_parent_class).'">';

                      if( $product_masonry_image ) {
                        $product_thumbnail_title = get_the_title($product_masonry_image);
                        $product_thumbnail_full  = wp_get_attachment_image_src($product_masonry_image, 'full');
                        $html .= '<img src="'.esc_url($product_thumbnail_full[0]).'" alt="'.esc_attr($product_thumbnail_title).'" class="attachment-shop_catalog size-shop_catalog wp-post-image" />';
                      } else if (has_post_thumbnail($product->get_id())) {
                        $product_thumbnail_id    = get_post_thumbnail_id();
                        $product_thumbnail_title = get_the_title($product_thumbnail_id);
                        $product_thumbnail_clog  = wp_get_attachment_image_src($product_thumbnail_id, 'shop_catalog');
                        $product_thumbnail_full  = wp_get_attachment_image_src($product_thumbnail_id, 'full');  
                                            
                        $html .= '<img width="'.esc_attr($product_thumbnail_clog[1]).'" height="'.esc_attr($product_thumbnail_clog[2]).'" src="'.esc_url($product_thumbnail_clog[0]).'" alt="'.esc_attr($product_thumbnail_title).'" data-src-main="'.esc_url($product_thumbnail_full[0]).'" class="attachment-shop_catalog size-shop_catalog wp-post-image" />';                    
                      } else {
                        $image_url = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/shop-sample.jpg';
                        $html .= '<img src="'.esc_url($image_url).'" class="attachment-shop-catalog size-shop_catalog" alt=""/>';
                      }

                      if ($product_hover_image && $product_meta_hover_image) {    
                        if( isset($product_masonry_image) && isset($product_masonry_hover_image) ) {  
                          $product_thumbnail_alt_title = get_the_title($product_masonry_hover_image);
                          $product_masonry_hover_image = wp_get_attachment_image_src($product_masonry_hover_image, 'full');
                          if ($product_masonry_hover_image) {
                            $html .= '<div class="els-product-hover-image"><img width="'.esc_attr($product_masonry_hover_image[1]).'" height="'.esc_attr($product_masonry_hover_image[2]).'" src="'.esc_url($product_masonry_hover_image[0]).'" alt="'.esc_attr($product_thumbnail_alt_title).'" data-src-main="'.esc_url($product_masonry_hover_image[0]).'" class="attachment-shop_catalog size-shop_catalog els-pr-hover-image" /></div>';
                          }
                        } 
                        if ( empty($product_masonry_image) ) { 
                          $product_gallery_thumbnail_ids = $product->get_gallery_image_ids();
                          $product_thumbnail_alt_id      = ($product_gallery_thumbnail_ids) ? reset($product_gallery_thumbnail_ids) : null;
                          if ($product_thumbnail_alt_id) {
                            $product_thumbnail_alt_title = get_the_title($product_thumbnail_alt_id);
                            $product_thumbnail_alt_src   = wp_get_attachment_image_src($product_thumbnail_alt_id, 'shop_catalog');
                            $product_thumbnail_alt_full  = wp_get_attachment_image_src($product_thumbnail_alt_id, 'full');
                            if ($product_thumbnail_alt_src) {
                              $html .= '<div class="els-product-hover-image"><img width="'.esc_attr($product_thumbnail_alt_src[1]).'" height="'.esc_attr($product_thumbnail_alt_src[2]).'" src="'.esc_url($product_thumbnail_alt_src[0]).'" alt="'.esc_attr($product_thumbnail_alt_title).'" data-src-main="'.esc_url($product_thumbnail_alt_full[0]).'" class="attachment-shop_catalog size-shop_catalog els-pr-hover-image" /></div>';
                            }
                          }
                        }
                      }
                    $html .= '</div>';  
                    echo $html; ?>
                  </div>    
                </li>

              <?php } else if ($pr_style === 'shop-fullgrid') { 
                $product_fullgrid_image = (isset($product_meta_options['product_fullgrid_image'])) ? $product_meta_options['product_fullgrid_image'] : ''; ?>
                <li <?php post_class(); ?>>

                	<div class="els-fullgrid-title-rating">
	                  <div class="els-product-title">
	                    <h2><a href="<?php echo get_the_permalink($product->get_id()); ?>"><?php echo esc_attr(get_the_title($product->get_id())); ?></a></h2>
	                  </div>
                  	<?php echo get_the_term_list($product->get_id(), 'product_cat', '<div class="els-product-cats">', ' & ', '</div>'); ?>        
	                  <div class="els-star-rating woocommerce-product-rating">
	                    <?php if ($average = $product->get_average_rating()) : ?>
	                      <?php echo '<div class="star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'elsey-core' ), $average).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'elsey-core' ).'</span></div>'; ?>
	                    <?php endif; ?>
	                  </div>  
	                </div>

	                <div class="els-product-image" <?php echo $product_bg_color; ?>>        
                    <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="woocommerce-LoopProduct-link"></a>
                    <?php 
                    $html = '';
                    $html .= '<div class="els-product-featured-image">';

                    if( $product_fullgrid_image ) {
                      $product_thumbnail_title = get_the_title($product_fullgrid_image);
                      $product_thumbnail_full  = wp_get_attachment_image_src($product_fullgrid_image, 'full');
                      $html .= '<img src="'.esc_url($product_thumbnail_full[0]).'" alt="'.esc_attr($product_thumbnail_title).'" class="attachment-shop_catalog size-shop_catalog wp-post-image" />';
                    } else if (has_post_thumbnail($product->get_id())) {
                      $lazy_load_image_url     = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/lazy-load.jpg';
                      $product_thumbnail_id    = get_post_thumbnail_id();
                      $product_thumbnail_title = get_the_title($product_thumbnail_id);
                      $product_thumbnail_clog  = wp_get_attachment_image_src($product_thumbnail_id, 'shop_catalog');
                      $product_thumbnail_full  = wp_get_attachment_image_src($product_thumbnail_id, 'full');

                      if ( $woo_lazy_load === 'els-dload-full' ) {  
                        $html .= '<img width="'.esc_attr($product_thumbnail_clog[1]).'" height="'.esc_attr($product_thumbnail_clog[2]).'" src="'.esc_url($product_thumbnail_clog[0]).'" alt="'.esc_attr($product_thumbnail_title).'" class="attachment-shop_catalog size-shop_catalog wp-post-image" />';
                      } else {
                        $html .= '<img width="'.esc_attr($product_thumbnail_clog[1]).'" height="'.esc_attr($product_thumbnail_clog[2]).'" data-src="'.esc_url($product_thumbnail_clog[0]).'" src="'.esc_url($lazy_load_image_url).'" alt="'.esc_attr($product_thumbnail_title).'" data-src-main="'.esc_url($product_thumbnail_full[0]).'" class="attachment-shop_catalog size-shop_catalog wp-post-image els-unveil-image" />';
                        $html .= '<div class="els-product-unveil-loader ball-beat"><div></div></div>';
                      }
                    } else {
                      $image_url = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/shop-sample.jpg';
                      $html .= '<img src="'.esc_url($image_url).'" class="attachment-shop-catalog size-shop_catalog" alt=""/>';
                    }

                    $html .= '</div>';  

                    echo $html; ?>
                  </div>

                  <div class="els-fullgrid-price-atc">
	                	<div class="els-product-price price"><?php echo $product->get_price_html(); ?></div>
	                	<div class="els-product-atc">
	                  	<?php woocommerce_template_loop_add_to_cart( $elsey_products->post, $product ); ?>
	                	</div>
                	</div>

                </li>

              <?php } else { ?>

                <li <?php post_class(); ?>>
                
                  <div class="els-product-image" <?php echo $product_bg_color; ?>>        
	                  <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="woocommerce-LoopProduct-link"></a>
	                  
	                  <?php if ( !$product->is_in_stock() ) {
	                    echo '<span class="els-product-sold">' . esc_html__( 'Sold', 'elsey-core' ) . '</span>';
	                  } else if ( $product->is_on_sale() ) {
	                    echo '<span class="els-product-onsale">' . esc_html__( 'Sale!', 'elsey-core' ) . '</span>'; 
	                  }
	  
                    $html = '';

	                  if ( $product_hover_image && $product_meta_hover_image ) {                      
	                    $product_gallery_thumbnail_ids = $product->get_gallery_image_ids();
	                    $product_thumbnail_alt_id      = ($product_gallery_thumbnail_ids) ? reset($product_gallery_thumbnail_ids) : null;
	                    
	                    if ($product_thumbnail_alt_id) {
	                      $product_thumbnail_alt_title = get_the_title($product_thumbnail_alt_id);
	                      $product_thumbnail_alt_src   = wp_get_attachment_image_src( $product_thumbnail_alt_id, 'shop_catalog' );
	                      $hover_parent_class = ($product_thumbnail_alt_src) ? 'els-product-has-hover' : '';
	                    } else {
	                      $hover_parent_class = '';
	                    }                    
	                  } else {
	                    $hover_parent_class = '';
	                  }

                  	$html .= '<div class="els-product-featured-image '.esc_attr($hover_parent_class).'">';

	                  if (has_post_thumbnail($product->get_id())) {
	                    $lazy_load_image_url     = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/lazy-load.jpg';
	                    $product_thumbnail_id    = get_post_thumbnail_id();
	                    $product_thumbnail_title = get_the_title($product_thumbnail_id);
	                    $product_thumbnail_clog  = wp_get_attachment_image_src($product_thumbnail_id, 'shop_catalog');
	                    $product_thumbnail_full  = wp_get_attachment_image_src($product_thumbnail_id, 'full');

	                    if ( $woo_lazy_load === 'els-dload-full' ) { 
	                      $html .= '<img width="'.esc_attr($product_thumbnail_clog[1]).'" height="'.esc_attr($product_thumbnail_clog[2]).'" src="'.esc_url($product_thumbnail_clog[0]).'" alt="'.esc_attr($product_thumbnail_title).'" class="attachment-shop_catalog size-shop_catalog wp-post-image" />';
	                    } else {
	                      $html .= '<img width="'.esc_attr($product_thumbnail_clog[1]).'" height="'.esc_attr($product_thumbnail_clog[2]).'" data-src="'.esc_url($product_thumbnail_clog[0]).'" src="'.esc_url($lazy_load_image_url).'" alt="'.esc_attr($product_thumbnail_title).'" data-src-main="'.esc_url($product_thumbnail_full[0]).'" class="attachment-shop_catalog size-shop_catalog wp-post-image els-unveil-image" />';
	                      $html .= '<div class="els-product-unveil-loader ball-beat"><div></div></div>';
	                    }                     
	                  } else {
	                    $image_url = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/shop-sample.jpg';
	                    $html .= '<img src="'.esc_url($image_url).'" class="attachment-shop-catalog size-shop_catalog" alt=""/>';
	                  }

	                  if ($product_hover_image && $product_meta_hover_image) {    
	                    $product_gallery_thumbnail_ids = $product->get_gallery_image_ids();
	                    $product_thumbnail_alt_id = ($product_gallery_thumbnail_ids) ? reset($product_gallery_thumbnail_ids) : null;

	                    if ($product_thumbnail_alt_id) {
	                      $product_thumbnail_alt_title = get_the_title($product_thumbnail_alt_id);
	                      $product_thumbnail_alt_src   = wp_get_attachment_image_src($product_thumbnail_alt_id, 'shop_catalog');
	                      $product_thumbnail_alt_full  = wp_get_attachment_image_src($product_thumbnail_alt_id, 'full');

	                      if ($product_thumbnail_alt_src) {
	                        $html .= '<div class="els-product-hover-image"><img width="'.esc_attr($product_thumbnail_alt_src[1]).'" height="'.esc_attr($product_thumbnail_alt_src[2]).'" src="'.esc_url($product_thumbnail_alt_src[0]).'" alt="'.esc_attr($product_thumbnail_alt_title).'" data-src-main="'.esc_url($product_thumbnail_alt_full[0]).'" class="attachment-shop_catalog size-shop_catalog els-pr-hover-image" /></div>';
	                      }
	                    }
	                  }

                  	$html .= '</div>';
                  	echo $html;  ?>

	                  <div class="els-product-atc">
	                    <?php woocommerce_template_loop_add_to_cart( $elsey_products->post, $product ); ?>
	                  </div>
                	</div>

	                <div class="els-product-info">
	                  <div class="els-product-title">
	                    <h3><a href="<?php echo get_the_permalink($product->get_id()); ?>"><?php echo esc_attr(get_the_title($product->get_id())); ?></a></h3>
	                    <div class="els-product-wishlist">
	                      <?php echo do_shortcode('[yith_wcwl_add_to_wishlist icon="fa-heart-o"]'); ?>
	                    </div>
	                  </div>
	                  <?php echo get_the_term_list($product->get_id(), 'product_cat', '<div class="els-product-cats">', ', ', '</div>'); ?>   
	                  <div class="els-product-price price"><?php echo $product->get_price_html(); ?></div>
	                </div>

              	</li>
            	<?php } endwhile; endif; wp_reset_postdata(); ?>
          	</ul>
        	</div>

	        <?php
	        if ($pr_nav) {
	          $lmore_shop_text  = cs_get_option('lmore_shop_text');
	          $older_shop_text  = cs_get_option('older_shop_text');
	          $newer_shop_text  = cs_get_option('newer_shop_text');

	          if ($pr_nav_style === 'els-pagination-three') { 
	            $lmore_shop_text = $lmore_shop_text ? $lmore_shop_text : esc_html__( 'Load More', 'els-core' ); ?>
	            <div class="els-load-more-box els-shop-load-more-box">
	              <div class="els-load-more-link els-shop-load-more-link">
	                <?php next_posts_link( '&nbsp;', $elsey_products->max_num_pages ); ?>
	              </div>
	              <div class="els-load-more-controls els-shop-load-more-controls els-btn-mode">
	                <div class="line-scale-pulse-out">
	                  <div></div>
	                  <div></div>
	                  <div></div>
	                  <div></div>
	                  <div></div>
	                </div>
	                <a href="javascript:void(0);" id="els-shop-load-more-btn" class="els-btn"><?php echo esc_attr($lmore_shop_text); ?></a>
	                <a href="javascript:void(0);" id="els-loaded" class="els-btn"><?php echo esc_html__( 'All Loaded', 'els-core' ); ?></a>
	              </div>
	            </div>
	          <?php  
	          } elseif ($pr_nav_style === 'els-pagination-two') {
	            $older_shop_text = $older_shop_text ? $older_shop_text : esc_html__( 'Older Products', 'els-core' );
	            $newer_shop_text = $newer_shop_text ? $newer_shop_text : esc_html__( 'Newer Products', 'els-core' ); ?>
	            <div class="els-prev-next-pagination els-shop-pagination">
	              <div class="row">     
	                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 older">
	                  <?php next_posts_link( '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt=""> '. $older_shop_text, $elsey_products->max_num_pages ); ?>
	                </div>
	                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 newer">
	                  <?php previous_posts_link( $newer_shop_text . ' <img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">', $elsey_products->max_num_pages ); ?>
	                </div>
	              </div>
	            </div><?php
	          } else {
	            if (function_exists('wp_pagenavi')) {
	              wp_pagenavi(array( 'query' => $elsey_products ) );
	            } else {
	              $older_shop_text = $older_shop_text ? $older_shop_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt="">';
	              $newer_shop_text = $newer_shop_text ? $newer_shop_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">';
	              $big = 999999999;
	              echo '<div class="wp-pagenavi">';
	              echo paginate_links( array(
	                'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
	                'format'    => '?paged=%#%',
	                'total'     => $elsey_products->max_num_pages,
	                'show_all'  => false,
	                'current'   => max( 1, $my_page ),
	                'prev_text' => $older_shop_text,
	                'next_text' => $newer_shop_text,
	                'mid_size'  => 1,
	                'type'      => 'list'
	              ) );
	              echo '</div>';
	            }
	          }
	        } ?>
        </div>
    	</div>

	    <?php
	    // Return outbut buffer
	    return ob_get_clean();

    }
  }
  
  add_shortcode( 'elsey_product', 'elsey_product_function' );
}
