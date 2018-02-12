<?php
/*
 * All WooCommerce Related Functions
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

if ( class_exists( 'WooCommerce' ) ) {

	/**
	 * Remove each style one by one
	 * https://docs.woothemes.com/document/disable-the-default-stylesheet/
	 */
	add_filter( 'woocommerce_enqueue_styles', 'elsey_dequeue_styles' );
  if ( ! function_exists('elsey_dequeue_styles') ) {
    function elsey_dequeue_styles( $enqueue_styles ) {
      unset( $enqueue_styles['woocommerce-general'] ); // Remove the gloss
      unset( $enqueue_styles['woocommerce-layout'] );  // Remove the layout
      return $enqueue_styles;
    }
	}

  /**
	* Product Listing Page Modification
	*/

	// Remove Shop Page Title
	add_filter( 'woocommerce_show_page_title' , 'elsey_hide_page_title' );
  if ( ! function_exists('elsey_hide_page_title') ) {
    function elsey_hide_page_title() {
      return false;
    }
	}

	// Add Product Category Content Changes
	remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
	remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

  add_action( 'woocommerce_before_subcategory', 'els_product_cat_image_open', 9 );
  add_action( 'woocommerce_before_subcategory', 'els_product_cat_link_open', 10 );
  add_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_close', 10, 2 );

  if (!function_exists('els_product_cat_image_open')) {
		function els_product_cat_image_open() {
			echo '<div class="els-product-image">';
		}
	}

	if (!function_exists('els_product_cat_link_open')) {
		function els_product_cat_link_open( $category ) {
			echo '<a href="'. esc_url(get_term_link( $category, 'product_cat' )) . '" class="woocommerce-LoopProduct-link">';
		}
	}

	remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
  add_action( 'woocommerce_before_subcategory_title', 'els_product_cat_thumbnail', 10 );

  if (!function_exists('els_product_cat_thumbnail')) {
		function els_product_cat_thumbnail( $category ) {
			$html  = '<div class="els-product-featured-image">';

			$small_thumbnail_size  	= apply_filters( 'subcategory_archive_thumbnail_size', 'shop_catalog' );
			$dimensions    			= wc_get_image_size( $small_thumbnail_size );
			$thumbnail_id  			= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image        = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
				$image        = $image[0];
				$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, $small_thumbnail_size ) : false;
				$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, $small_thumbnail_size ) : false;
			} else {
				$image        = wc_placeholder_img_src();
				$image_srcset = $image_sizes = false;
			}

			if ( $image ) {
				$image = str_replace( ' ', '%20', $image );
				if ( $image_srcset && $image_sizes ) {
					$html .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" />';
				} else {
					$html .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
				}
			}

			$html .= '</div>';
			echo $html;
		}
	}

  add_action( 'woocommerce_before_subcategory_title', 'els_product_cat_title_open', 10, 2);
  if (!function_exists('els_product_cat_title_open')) {
		function els_product_cat_title_open() {
			echo '</div><div class="els-product-info"><div class="els-product-title">';
		}
	}

	remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
	add_action( 'woocommerce_shop_loop_subcategory_title', 'els_product_cat_title', 10 );
  if (!function_exists('els_product_cat_title')) {
		function els_product_cat_title( $category ) {
			$html  = '<h3><a href="'.get_term_link( $category, 'product_cat' ).'">';
			$html .= esc_attr($category->name);
			if ( $category->count > 0 )
			  $html .= apply_filters( 'woocommerce_subcategory_count_html', ' ('.$category->count.') ', $category );
		  $html .= '</a></h3>';
		  echo $html;
		}
	}

	add_action( 'woocommerce_after_subcategory_title', 'els_product_cat_title_close' );
  if (!function_exists('els_product_cat_title_close')) {
		function els_product_cat_title_close() {
			echo '</div></div>';
		}
	}

  // Remove Breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

  // Remove Default Sidebar Hook
  remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	// Remove Product Ordering, Count
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

	// Product Listing Pages - Remove Template Loop Open Link
  remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

  // Product Listing Pages - Remove Sale Flash
  remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

  // Product Listing Pages - Remove Product Title
  remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

  // Product Listing Pages - Remove Product Rating
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

  // Product Listing Pages - Remove Template Loop Close Link
  remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

  // Product Listing Pages - Remove Add To Cart
  remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Product Listing Pages - Add Product Badge
  add_action( 'woocommerce_before_shop_loop_item_title', 'elsey_show_product_badge', 10);
  if ( ! function_exists('elsey_show_product_badge') ) {
	  function elsey_show_product_badge() {
	  	global $product;
  	  if ( !$product->is_in_stock() ) {
				echo '<span class="els-product-sold">' . esc_html__( 'Sold', 'elsey' ) . '</span>';
			} else if ( $product->is_on_sale() ) {
				echo '<span class="els-product-onsale">' . esc_html__( 'Sale!', 'elsey' ) . '</span>';
			}
	  }
	}

  // Product Listing Pages - Function to return new placeholder image URL
	add_filter( 'woocommerce_placeholder_img_src', 'elsey_custom_shop_placeholder', 10 );
	function elsey_custom_shop_placeholder( $image_url ) {
	  $image_url = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/shop-sample.jpg';
	  return $image_url;
	}

  // Product Listing Pages - Add Image Parent div Class and Catalog image + Hover image
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
	add_action( 'woocommerce_before_shop_loop_item_title', 'elsey_loop_product_thumbnail', 10);
	if ( ! function_exists('elsey_loop_product_thumbnail') ) {
	  function elsey_loop_product_thumbnail() {
	    global $product;

	    $html = '';
	    $hover_image         = (cs_get_option('woo_hover_image')) ? true : false;
	    $product_options     = get_post_meta($product->get_id(), 'product_options', true );
			$product_hover_image = isset($product_options['product_hover_image_change']) ? $product_options['product_hover_image_change'] : true;

	    if ($hover_image && $product_hover_image) {
	      $product_gallery_thumbnail_ids = $product->get_gallery_image_ids();
	      $product_thumbnail_alt_id      = ($product_gallery_thumbnail_ids) ? reset($product_gallery_thumbnail_ids) : null;
	      if ($product_thumbnail_alt_id) {
	      	$product_thumbnail_alt_title = get_the_title($product_thumbnail_alt_id);
	        $product_thumbnail_alt_src   = wp_get_attachment_image_src( $product_thumbnail_alt_id, 'shop_catalog' );
	        if ($product_thumbnail_alt_src) {
	          $hover_parent_class = 'els-product-has-hover';
	        } else {
	          $hover_parent_class = '';
	        }
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

				$html .= '<img width="'.esc_attr($product_thumbnail_clog[1]).'" height="'.esc_attr($product_thumbnail_clog[2]).'" data-src="'.esc_url($product_thumbnail_clog[0]).'" src="'.esc_url($lazy_load_image_url).'" alt="'.esc_attr($product_thumbnail_title).'" data-src-main="'.esc_url($product_thumbnail_full[0]).'" class="attachment-shop_catalog size-shop_catalog wp-post-image els-unveil-image" />';

				$html .= '<div class="els-product-unveil-loader ball-beat"><div></div></div>';

	    } else {
	    	$image_url = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/shop-sample.jpg';
	  		$html .= '<img src="'.esc_url($image_url).'" class="attachment-shop-catalog size-shop_catalog" alt=""/>';
	    }

	    if ($hover_image && $product_hover_image) {
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
	    echo $html;
	  }
	}

  // Product Listing Pages - Add Add To Cart wrap div open
	add_action( 'woocommerce_before_shop_loop_item_title', 'elsey_atc_wrap_open', 10, 2);
	if (!function_exists('elsey_atc_wrap_open')) {
	  function elsey_atc_wrap_open() {
	      echo '<div class="els-product-atc">';
	  }
	}

	// Product Listing Pages - Add Add To Cart
  add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10, 2);

  // Product Listing Pages - Add image within this wrap
  add_action( 'woocommerce_before_shop_loop_item_title', 'elsey_product_thumb_wrap_open', 9, 2);
  if (!function_exists('elsey_product_thumb_wrap_open')) {
		function elsey_product_thumb_wrap_open() {
			$product_options  = get_post_meta(get_the_ID(), 'product_options', true );
			$product_bg_color = (isset($product_options['product_single_bg'])) ? 'style="background:'.$product_options['product_single_bg'].'"' : '';
			$html = '<div class="els-product-image" '.$product_bg_color.'>';
			$html.= '<a href="'.esc_url(get_permalink(get_the_ID())).'" class="woocommerce-LoopProduct-link"></a>';
			echo $html;
		}
	}

	// Product Listing Pages - Add Add To Cart wrap div and image div close
  add_action( 'woocommerce_before_shop_loop_item_title', 'elsey_product_thumb_wrap_close', 14, 2);
	if (!function_exists('elsey_product_thumb_wrap_close')) {
		function elsey_product_thumb_wrap_close() {
      echo '</div></div><div class="els-product-info">';
		}
	}

  // Product Listing Pages - Add Product Title
  add_action( 'woocommerce_shop_loop_item_title', 'elsey_product_title', 10);
  if (!function_exists('elsey_product_title')) {
		function elsey_product_title() {
			global $product;
			$html = '<div class="els-product-title"><h3><a href="'.get_the_permalink($product->get_id()).'">'. get_the_title() .'</a></h3>';
			// Add Wishlist Plugin Button
		  if ( defined( 'YITH_WCWL' ) ) {
		  	$html .= '<div class="els-product-wishlist">';
			  $html .= do_shortcode('[yith_wcwl_add_to_wishlist icon="fa-heart-o"]');
			  $html .= '</div>';
		  }
		  $html .= '</div>';
		  echo $html;
		}
	}

  // Product Listing Pages - Add category list
	add_action( 'woocommerce_after_shop_loop_item_title', 'elsey_meta_category_list', 5);
	if (!function_exists('elsey_meta_category_list')) {
	  function elsey_meta_category_list() {
	    global $product;
	    //echo get_the_term_list($product->get_id(), 'product_cat', '<div class="els-product-cats">', ', ', '</div>');
		}
	}

  // Product Listing Pages - Add info close
	add_action( 'woocommerce_after_shop_loop_item', 'elsey_meta_wrap_close' );
	if (!function_exists('elsey_meta_wrap_close')) {
	  function elsey_meta_wrap_close() {
	    echo '</div>';
		}
	}

	// Product Listing Pages - Add all products within this wrap.
  add_action( 'woocommerce_before_shop_loop', 'elsey_all_products_open', 30, 2);
	if (!function_exists('elsey_all_products_open')) {
		function elsey_all_products_open() {
			$shop_page_url = get_permalink(wc_get_page_id('shop'));
			$html = '<div class="els-shop-load-anim"><div class="line-scale-pulse-out"><div></div><div></div><div></div><div></div><div></div></div></div>';
			$html.= '<div class="els-products-full-wrap els-shop-default" data-shopurl="'.esc_url($shop_page_url).'">';
			echo $html;
		}
	}
  add_action( 'woocommerce_after_shop_loop', 'elsey_all_products_close', 9);
	if (!function_exists('elsey_all_products_close')) {
		function elsey_all_products_close() {
			echo '</div>';
		}
	}

 	// WooCommerce Products Per Page Limit
	add_filter( 'loop_shop_per_page', 'elsey_product_limit', 20);
	if ( ! function_exists('elsey_product_limit') ) {
		function elsey_product_limit() {
			$woo_limit = cs_get_option('theme_woo_limit');
			$woo_limit = $woo_limit ? $woo_limit : '8';
			return $woo_limit;
		}
	}

	// Product Column Limit - Shop Page
	add_filter('loop_shop_columns', 'elsey_loop_columns');
	if ( ! function_exists('elsey_loop_columns') ) {
		function elsey_loop_columns() {
			return cs_get_option('woo_product_columns', '4');
		}
	}

	// Remove WooCommerce Default Pagination & Add our Own Pagination
	remove_action('woocommerce_pagination', 'woocommerce_pagination', 10);
	function woocommerce_pagination() {
		elsey_shop_paging_nav();
	}
	add_action( 'woocommerce_pagination', 'woocommerce_pagination', 10);

	// Display only min price for variable products.
	add_filter( 'woocommerce_variable_sale_price_html', 'elsey_custom_variable_price', 10, 2 );
	add_filter( 'woocommerce_variable_price_html', 'elsey_custom_variable_price', 10, 2 );
	function elsey_custom_variable_price( $price, $product ) {
	  if ( !is_product() ) {
	    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
	    /* translators: 1: price */
	    $price = $prices[0] !== $prices[1] ? sprintf( __( '<i class="fa fa-tags" aria-hidden="true"></i> %1$s', 'elsey' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

	    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
	    sort( $prices );
	     /* translators: 1: price */
	    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( '<i class="fa fa-tags" aria-hidden="true"></i> %1$s', 'elsey' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

	    $price = isset($price) ? $price : $saleprice;
	  }
	  return $price;
	}

	// Display only min price for grouped products.
	add_filter( 'woocommerce_grouped_price_html', 'elsey_grouped_price_html', 10, 2 );
	function elsey_grouped_price_html( $price, $product ) {
	  if ( !is_product() ) {
	    $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
		  $child_prices     = array();

			foreach ( $product->get_children() as $child_id ) {
				$child = wc_get_product( $child_id );
				if ( '' !== get_post_meta( $child_id, '_price', true ) ) {
					$child_prices[] = 'incl' === $tax_display_mode ? wc_get_price_including_tax( $child ) : wc_get_price_excluding_tax( $child );
				}
			}

			if ( ! empty( $child_prices ) ) {
				$min_price = min( $child_prices );
				$max_price = max( $child_prices );
			} else {
				$min_price = '';
				$max_price = '';
			}

	    if ( $min_price == $max_price ) {
	      $price = wc_price( $min_price );
	  	} else {
	  		$from  = wc_price( $min_price );
	  		/* translators: 1: price */
	  		$price = sprintf( __( '<i class="fa fa-tags" aria-hidden="true"></i> %1$s', 'elsey' ), $from );
	  	}
	  }
	  return $price;
	}

	/**
	 * Product Single Page Modification
	 */

	// Single Product Page - Function to return new placeholder for large image.
	add_filter( 'woocommerce_single_product_image_thumbnail_html', 'elsey_single_product_image_html', 10, 2 );
	function elsey_single_product_image_html( $html, $post_id ) {
	  if ( !has_post_thumbnail() ) {
      $html = '<div><img src="'.ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/single-product-sample.jpg" alt="'.esc_html__('Placeholder', 'elsey').'" /></div>';
      return $html;
	  } else {
	    return $html;
	  }
	}

	// Single Product Page - Change order of RRP and sale price.
	function elsey_rrp_price_html( $price, $product ) {
	  return '<div class="els-pr-price">'.preg_replace('@(<del>.*?</del>).*?(<ins>.*?</ins>)@misx', '$2 $1', $price).'</div>';
	}
	add_filter( 'woocommerce_get_price_html', 'elsey_rrp_price_html', 100, 2 );

	// Single Product Page - Add class on "product description" text.
	add_filter( 'woocommerce_single_product_summary', 'elsey_product_description_open', 10, 2 );
	add_filter( 'woocommerce_single_product_summary', 'elsey_product_description_close', 20, 2 );
	function elsey_product_description_open($availability) {
		global $product;
    $quantity     = $product->get_stock_quantity();
    $availability = $product->get_availability();

    if( isset($quantity) || $availability['class'] ) {
    	$output = '<div class="els-product-stock-status">';
	    if( isset($quantity) ) {
		    $output .= '<div class="els-product-qty">';
		    $output .= '<img src="'.ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/stock-icon.png" alt="" />';
				$output .= esc_html__( ' Only ', 'elsey' ) . '<label>' . $quantity . ' ' . esc_html__( 'left', 'elsey' ) . '</label>';
		    $output .= '</div>';
		  }

    	if( $availability['class'] ) {
	    	$output .= '<div class="els-'.esc_attr($availability['class']).' els-avl">';
	    	$output .= ( isset($quantity) ) ? esc_html__( ' | ', 'elsey' ) : '';
	    	$output .= '<label>'.esc_html__( 'Availability:', 'elsey' ).'</label>';
		    if( $availability['class'] === 'in-stock' ) {
		    	$output .= '<span>' . esc_html__( ' In Stock', 'elsey' ) . '</span>';
		    } else if ( $availability['class'] === 'out-of-stock' ) {
		    	$output .= '<span>' . esc_html__( ' Out of Stock', 'elsey' ) . '</span>';
		    }
				$output .= '</div>';
			}
			$output .= '</div>';
		}

    $output .= '<div class="els-single-product-excerpt">';
	  echo $output;
	}
	function elsey_product_description_close() {
	  echo '</div>';
	}

	// Single Product Page - Moving the single share section before the description tabs
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

	add_action('woocommerce_after_single_product_summary', 'woocommerce_template_single_sharing', 10);
  add_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10, 2);

	$woo_single_share = cs_get_option('woo_single_share');
	if (!$woo_single_share) {
	  remove_action('woocommerce_after_single_product_summary', 'woocommerce_template_single_sharing', 10);
	}

	// Single Product Page - Share code into the woocommerce_template_single_sharing
	add_action( 'woocommerce_share', 'elsey_wp_single_product_share_option' );

	// Single Product Page - Change "product description" text
	add_filter( 'woocommerce_product_description_heading', 'elsey_product_description_heading' );
	function elsey_product_description_heading() {
		$pro_des = esc_html__('Product Description', 'elsey');
	  return $pro_des;
	}

  // Single Product Page - Remove "additional information" text
	add_filter( 'woocommerce_product_additional_information_heading', 'elsey_additional_information_heading' );
	function elsey_additional_information_heading() {
	  return '';
	}

  // Single Product Page - Change the gravator image size in review authors - Use Same function name of : woocommerce_review_display_gravatar
	if ( ! function_exists( 'woocommerce_review_display_gravatar' ) ) {
		function woocommerce_review_display_gravatar( $comment ) {
			echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '95' ), '' );
		}
	}

	// Single Product Page - Moving the review star after review meta
	remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);
	remove_action('woocommerce_review_meta', 'woocommerce_review_display_meta', 10);

	add_action('woocommerce_review_meta', 'woocommerce_review_display_rating', 10);
  add_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_meta', 10, 2);

  // Single Product Page - Upsells Products Limit
	add_filter( 'woocommerce_upsell_display_args', 'elsey_upsell_products_args' );
	function elsey_upsell_products_args( $args ) {
		$woo_upsell_limit   = cs_get_option('woo_upsell_limit');
		$woo_upsell_columns = cs_get_option('woo_upsell_columns');

		$args['posts_per_page'] = (int)$woo_upsell_limit;
		$args['columns'] = (int)$woo_upsell_columns;

		return $args;
	}

	// Single Product Page - Remove Upsells Products From Theme Option
	$woo_single_upsell = cs_get_option('woo_single_upsell');
	if (!$woo_single_upsell) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
	}

	// Single Product Page - Related Products Limit
	add_filter( 'woocommerce_output_related_products_args', 'elsey_related_products_args' );
	function elsey_related_products_args( $args ) {
		$woo_related_limit   = cs_get_option('woo_related_limit');
		$woo_related_columns = cs_get_option('woo_related_columns');

		$args['posts_per_page'] = (int)$woo_related_limit;
		$args['columns'] = (int)$woo_related_columns;

		return $args;
	}

	// Single Product Page - Remove Related Products From Theme Option
	$woo_single_related = cs_get_option('woo_single_related');
	if (!$woo_single_related) {
	  remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

	/**
	* Cart Page Modification
	*/
	// Remove Cross Sells => "You may be interested in" from Cart Page
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

  /**
	* General Modification
	*/

	// Change parentheses of product count of wc category widget
	if ( ! function_exists('elsey_categories_postcount_filter') ) {
		function elsey_categories_postcount_filter($variable) {
		   $variable = str_replace('(', '<sup class="count">[', $variable);
		   $variable = str_replace(')', ']</sup>', $variable);
		   return $variable;
		}
		add_filter('wp_list_categories','elsey_categories_postcount_filter');
	}

	if ( ! function_exists('elsey_archive_postcount_filter') ) {
		function elsey_archive_postcount_filter($variable) {
		   $variable = str_replace('(', '<sup class="count">[', $variable);
		   $variable = str_replace(')', ']</sup>', $variable);
		   return $variable;
		}
		add_filter('get_archives_link', 'elsey_archive_postcount_filter');
	}

	// Define image sizes
	function elsey_woo_image_dimensions() {
		global $pagenow;

		if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
			return;
		}
	  $catalog = array(
		'width' 	=> '480',
		'height'	=> '600',
		'crop'		=> 1
		);
		$single = array(
		'width' 	=> '525',
		'height'	=> '700',
		'crop'		=> 1
		);
		$thumbnail = array(
		'width' 	=> '90',
		'height'	=> '120',
		'crop'		=> 1
		);
		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); // Product category thumbs
		update_option( 'shop_single_image_size', $single ); // Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); // Image gallery thumbs
	}
	add_action( 'after_switch_theme', 'elsey_woo_image_dimensions', 1 );


	/** Custom Feilds For Woocommerce Category */
	function elsey_taxonomy_add_new_meta_field() { ?>
		<div class="form-field term-masonry-image-wrap">
      <label for="term_meta[masonry_image]"><?php esc_html_e('Masonry Layout Image', 'elsey'); ?></label>
	    <div class="cs-field-image els-cs-image">
	    	<div class="cs-image-preview hidden">
	    		<div class="cs-preview"><i class="fa fa-times cs-remove"></i><img src="" alt="preview"></div>
	    	</div>
	    	<a href="javascript:void(0);" class="button button-primary cs-add">
	    	  <?php echo esc_html__('Add Image', 'elsey'); ?>
	    	</a>
        <input type="text" id="term_meta[masonry_image]" class="hidden" name="term_meta[masonry_image]" value="" />
      </div>
	  </div>
	  <div class="form-field term-masonry-size-wrap">
	  	<label for="term_meta[masonry_size]"><?php esc_html_e('Grid Size', 'elsey'); ?></label>
			<select name="term_meta[masonry_size]" id="term_meta[masonry_size]">
        <?php $size_list = array(
          'd-wh'  => esc_html__('Default', 'elsey'),
          'd-h2w' => esc_html__('Default Height & Double Width', 'elsey'),
        	'd-2wh' => esc_html__('Double Width & Double Height', 'elsey'),
        );
        foreach($size_list as $key => $list) { echo '<option value="'. esc_attr($key) .'">'. esc_attr($list) .'</option>'; } ?>
    	</select>
      <p class="description"><?php esc_html_e('Select grid size for masonary layout', 'elsey'); ?></p>
	  </div>
	<?php }
	add_action('product_cat_add_form_fields', 'elsey_taxonomy_add_new_meta_field', 10, 2);

	function elsey_taxonomy_edit_meta_field( $term ) {
	  $term_id        = $term->term_id;
	  $term_meta      = get_option("taxonomy_" . $term_id);
	  $term_meta_size = ($term_meta['masonry_size']) ? esc_attr($term_meta['masonry_size']) : '';
	  $term_meta_img  = ($term_meta['masonry_image']) ? esc_attr( $term_meta['masonry_image'] ) : '';
	  $preview = ''; $ahidden = '';
    $hidden  = ( empty( $term_meta_img ) ) ? ' hidden' : '';
    if( ! empty( $term_meta_img ) ) {
      $attachment = wp_get_attachment_image_src( $term_meta_img, 'thumbnail' );
      $preview    = $attachment[0];
      $ahidden    = ' hidden';
    } ?>
	  <tr class="form-field term-masonry-image-wrap">
      <th scope="row" valign="top"><label for="term_meta[masonry_image]"><?php esc_html_e('Masonry Layout Image', 'elsey'); ?></label></th>
      <td>
		    <div class="cs-field-image els-cs-image">
		    	<div class="cs-image-preview <?php echo esc_attr($hidden); ?>">
		    		<div class="cs-preview"><i class="fa fa-times cs-remove"></i><img src="<?php echo esc_url($preview); ?>" alt="preview"></div>
		    	</div>
		    	<a href="javascript:void(0);" class="button button-primary cs-add">
		    	  <?php echo esc_html__('Add Image', 'elsey'); ?>
		    	</a>
	        <input type="text" id="term_meta[masonry_image]" class="hidden" name="term_meta[masonry_image]" value="<?php echo esc_attr($term_meta_img); ?>" />
	      </div>
      </td>
	  </tr>
	  <tr class="form-field term-masonry-size-wrap">
      <th scope="row" valign="top"><label for="term_meta[masonry_size]"><?php esc_html_e('Grid Size', 'elsey'); ?></label></th>
      <td>
        <select name="term_meta[masonry_size]" id="term_meta[masonry_size]">
          <?php $size_list = array(
            'd-wh'  => esc_html__('Default', 'elsey'),
            'd-h2w' => esc_html__('Default Height & Double Width', 'elsey'),
          	'd-2wh' => esc_html__('Double Width & Double Height', 'elsey'),
          );
          foreach($size_list as $key => $list) {
            $sel = ($term_meta_size == $key) ? 'selected="true"' : '';
            echo '<option value="'. esc_attr($key) .'" '.$sel.'>'. esc_attr($list) .'</option>';
          } ?>
				</select>
				<p class="description"><?php esc_html_e('Select grid size for masonary layout', 'elsey'); ?></p>
      </td>
	  </tr>
	<?php
	}
	add_action('product_cat_edit_form_fields', 'elsey_taxonomy_edit_meta_field', 10, 2);

	function save_taxonomy_custom_meta($term_id) {
	  if (isset($_POST['term_meta'])) {
      $term_meta = get_option("taxonomy_" . $term_id);
      $cat_keys = array_keys($_POST['term_meta']);
      foreach ($cat_keys as $key) {
        if (isset($_POST['term_meta'][$key])) {
            $term_meta[$key] = $_POST['term_meta'][$key];
        }
      }
      update_option("taxonomy_" . $term_id, $term_meta);
	  }
	}
	add_action('edited_product_cat', 'save_taxonomy_custom_meta', 10, 2);
	add_action('create_product_cat', 'save_taxonomy_custom_meta', 10, 2);

	// Cart Count Number Ajax Update
	if ( defined('WOOCOMMERCE_VERSION') && version_compare( WOOCOMMERCE_VERSION, '2.7', '>=' ) ) {
		add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_ajax_fragments' );
	} else {
		add_filter( 'add_to_cart_fragments', 'woocommerce_ajax_fragments' );
	}

	function woocommerce_ajax_fragments( $fragments ) {
		global $woocommerce;
		ob_start();
		if ( $woocommerce->cart->get_cart_contents_count() == '0' ) { ?>
		  <span class="els-cart-count els-cart-zero"><?php echo esc_attr($woocommerce->cart->get_cart_contents_count()); ?></span>
		<?php } else { ?>
			<span class="els-cart-count"><?php echo esc_attr($woocommerce->cart->get_cart_contents_count()); ?></span>
	  <?php }
		$fragments['span.els-cart-count'] = ob_get_clean();
		return $fragments;
  }

	/**
	 * Add Class Field For Wordpress
	 */
	if ( ! function_exists( 'elsey_attachment_field_class' ) ) {
	  function elsey_attachment_field_class( $form_fields, $post ) {
	  	$form_fields['image-media-link'] = array(
		    'label' => esc_html__('Popup Image URL', 'elsey'),
		    'input' => 'text',
		    'value' => get_post_meta( $post->ID, '_image_media_link', true ),
		    'helps' => esc_html__('Add large image URL', 'elsey'),
		  );

	    $form_fields['msclass'] = array(
	      'label' => esc_html__( 'Grid Size', 'elsey' ),
	      'input' => 'html',
	      'helps' => 'Select lookbook masonry layout grid size.',
	    );

	    $current_msclass = get_post_meta($post->ID, 'msclass', true);
	    if ( !isset($current_msclass) ) {
	        $current_msclass = 'lb-df';
	    }

	    $form_fields['msclass']['html']  = '<select name="attachments['.$post->ID.'][msclass]" id="attachments['.$post->ID.'][msclass]" class="els-media-msclass">';
	    $form_fields['msclass']['html'] .= '<option value="lb-df" '.selected('lb-df', $current_msclass, false).'>Default</option>';
	    $form_fields['msclass']['html'] .= '<option value="lb-dfh-dbw" '.selected('lb-dfh-dbw', $current_msclass, false).'>Default Height Double Width</option>';
	    $form_fields['msclass']['html'] .= '<option value="lb-dfw-dbh" '.selected('lb-dfw-dbh', $current_msclass, false).'>Default Width Double Height</option>';
	    $form_fields['msclass']['html'] .= '<option value="lb-dbwh" '.selected('lb-dbwh', $current_msclass, false).'>Double Width & Height</option>';
	    $form_fields['msclass']['html'] .= '</select>';

	    return $form_fields;
	  }
	  add_filter( 'attachment_fields_to_edit', 'elsey_attachment_field_class', 10, 2 );
	}

	if ( ! function_exists( 'elsey_attachment_field_class_save' ) ) {
	  function elsey_attachment_field_class_save(  $post, $attachment ) {
      if( isset( $attachment['image-media-link'] ) )
    		update_post_meta( $post['ID'], '_image_media_link', $attachment['image-media-link'] );
      if (isset( $attachment['msclass'] ) )
        update_post_meta( $post['ID'], 'msclass', $attachment['msclass'] );
      return $post;
	  }
	  add_filter( 'attachment_fields_to_save', 'elsey_attachment_field_class_save', 10, 2 );
	}

} // class_exists => WooCommerce

?>