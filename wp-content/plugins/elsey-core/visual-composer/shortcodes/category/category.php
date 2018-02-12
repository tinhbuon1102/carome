<?php
/* ==========================================================
  Product Categories
=========================================================== */

if ( class_exists('WooCommerce') ) {

  if ( !function_exists('elsey_product_categories_function') ) {

    function elsey_product_categories_function( $atts, $content = NULL ) {

	    extract(shortcode_atts(array(
	      // Layout
	      'cat_style'       => 'cats_default',
	      'cat_limit'       => '',
	      'cat_columns'     => 'col_3',
	      'cat_space'       => 'style_one',
	      'cat_height'      => 'cats_auto_height',
	      'cat_min_height'  => '',
	      // Listing
	      'cat_pr_count'    => '',
	      'cat_des'         => '',
	      'cat_hide_empty'  => '',
	      'cat_parent'      => '',
	      // Color
	      'cat_bg_color'    => '',
	      'cat_title_color' => '',
	      'cat_desc_color'  => '',
	      'cat_order'       => 'ASC',
	      'cat_orderby'     => 'none',
	      'cat_ids'         => '',
	      'class'           => '',
	    ), $atts));
  
	    $cat_hide_empty = ($cat_hide_empty) ? 1 : 0;

	    $args = array();
	    $args['order'] = $cat_order;
	    $args['orderby'] = $cat_orderby;
	    $args['hide_empty'] = $cat_hide_empty;

	    if( $cat_parent ) { $args['parent'] = 0; }
	    if( $cat_limit !== '' ) { $args['number'] = $cat_limit; }
	    if( $cat_ids !== '' ) { $args['include'] = $cat_ids; }
	   
	    $pro_cats = get_terms('product_cat', $args);

	    // Inline Style 
	    $inline_style = '';
	    $e_uniqid     = uniqid();
	    $styled_class = ($cat_style === 'cats_masonry') ? 'els-cat-masonry-'. $e_uniqid : 'els-cat-default-'. $e_uniqid;

	    if ( $cat_title_color ) {
	      $inline_style .= '.'.$styled_class.' .els-catsc-text .els-catsc-name {';
	      $inline_style .= 'color: '.$cat_title_color.';';
	      $inline_style .= '}';
	    }

	    if ( $cat_desc_color ) {
	      $inline_style .= '.'.$styled_class.' .els-catsc-text .els-catsc-desc {';
	      $inline_style .= 'color: '.$cat_desc_color.';';
	      $inline_style .= '}';
	    }

	    // Turn output buffer on
	    ob_start();

		  if ( $cat_style === 'cats_masonry' ) {

		  	$inline_style .= '.'.$styled_class.' .els-cat-masonry-box {';
				$inline_style .= ( $cat_space === 'style_one' ) ? 'padding: 0 10px 20px;' : 'padding: 0 5px 10px;';
				$inline_style .= '}';

	      if( isset($cat_bg_color) ) {
	        $inline_style .= '.'.$styled_class.' .els-cat-masonry-img {';
	        $inline_style .= ($cat_bg_color) ? 'background-color: '.$cat_bg_color.'; background-image:none;' : 'background-color: #C5C5C5;';
	        $inline_style .= '}';
	      }

	      add_inline_style( $inline_style );

	      $output_masonry  = '';
	      $output_masonry .= '<div class="els-cat-masonry els-cat-wrap '.esc_attr($styled_class.' '.$class).'">';
	      $output_masonry .= '<div class="els-cat-masonry-sizer"></div>';

	      foreach ($pro_cats as $pro_cat) {
 					$term_meta     = get_option("taxonomy_" . $pro_cat->term_id);
 					$cat_msn_id    = ($term_meta['masonry_image']) ? $term_meta['masonry_image'] : '';
 					$cat_thumb_id  = get_woocommerce_term_meta( $pro_cat->term_id, 'thumbnail_id', true );
 					$cat_thumb_id  = !empty($cat_thumb_id) ? $cat_thumb_id : '';
 					$thumbnail_id  = !empty($cat_msn_id) ? $cat_msn_id : $cat_thumb_id;
 					$cat_image 		 = wp_get_attachment_image_src( $thumbnail_id, 'full' ); 
 					$cat_image     = $cat_image[0];
	        $cat_image     = !empty($cat_image) ? $cat_image : '';
	        $catUrl        = get_term_link($pro_cat->term_id);
	        $grid_size     = ($term_meta['masonry_size']) ? esc_attr($term_meta['masonry_size']) : 'd-wh';

	        $output_masonry .= '<div class="els-cat-masonry-box '.esc_attr($grid_size).'"><a href="'.esc_url($catUrl).'"></a>';	        
	        $output_masonry .= isset($cat_image) ? '<div class="els-cat-masonry-img"><img src="'.esc_url($cat_image).'" alt="'.esc_attr($pro_cat->name).'"></div>' : '<div class="els-cat-masonry-img"></div>';
	        $output_masonry .= '<div class="els-cat-masonry-text els-catsc-text"><div class="els-cat-info-box">';

	        if( $pro_cat->name ) {
	          $output_masonry .= '<div class="els-cat-masonry-name els-catsc-name">'.esc_attr($pro_cat->name);
	          $output_masonry .= ($cat_pr_count) ? ' <span>['.esc_attr($pro_cat->count).']</span>' : '';
	          $output_masonry .= '</div>';
	        }

	        $output_masonry .= ($cat_des && $pro_cat->description) ? '<div class="els-cat-masonry-desc els-catsc-desc">'.esc_attr($pro_cat->description).'</div>' : '';
	        $output_masonry .= '</div></div></div>';
	      }

	      $output_masonry .= '</div>';

	      echo $output_masonry; ?>

	      <script type="text/javascript">
	        jQuery(document).ready(function($) {
	          var $catGrid = $('.<?php echo esc_js($styled_class); ?>');
	          $catGrid.imagesLoaded(function() {
		          $catGrid.isotope({
		            itemSelector: '.els-cat-masonry-box',
		            masonry: {
	                columnWidth: '.els-cat-masonry-sizer',
	              }
		          });
		        });
	        });
	      </script>

	    <?php
	    } else {

	 			if( $cat_columns === 'col_5' ) {
		      $col_number   = 5;
		      $column_class = 'col-nm-5';
		    } else if( $cat_columns === 'col_4' ) {
		      $col_number   = 4;
		      $column_class = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
		    } else {
		      $col_number   = 3;
		      $column_class = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
		    }

		    $cat_min_height = ($cat_min_height) ? $cat_min_height : '';
	      $cat_height = ($cat_height === 'cats_min_height') ? elsey_check_px($cat_min_height) : 'auto';

	      if( isset($cat_height) || isset($cat_bg_color) ) {
	        $inline_style .= '.'.$styled_class.' .els-catdt-box {';
	        $inline_style .= 'height:'.$cat_height.';';
	        $inline_style .= ($cat_bg_color) ? 'background-color: '.$cat_bg_color.'; background-image:none;' : 'background-color: #C5C5C5;';
	        $inline_style .= '}';
	      }

	      add_inline_style( $inline_style );

	      $count = 0;
	      $count_all_cat   = count($pro_cats);
	      $output_default  = '';
	      $output_default .= '<div class="els-cat-default els-cat-wrap '.esc_attr( $styled_class.' '.$class ).'">';

	      foreach ($pro_cats as $pro_cat) {
	        $count++;

	        if( $count === 1 ) {
	          $output_default .= '<div class="row">';
	        } 

	        $output_default .= '<div class="'. esc_attr($column_class) .'">';

	        $thumbnail_id = get_woocommerce_term_meta( $pro_cat->term_id, 'thumbnail_id', true );
	        $cat_image    = wp_get_attachment_image_src( $thumbnail_id, 'fullsize', false, '' );
	        $cat_image    = $cat_image[0];
	        $cat_image    = isset($cat_image) ? $cat_image : '';
	        $cat_height   = '';	         
	        $catUrl       = get_term_link($pro_cat->term_id);

	        $output_default .= '<div class="masonry-grid__item portrait"><a href="'.esc_url($catUrl).'"><span class="overlay"></span>';
	        $output_default .= ($cat_image) ? '<img src="'.esc_url($cat_image).'" alt="'.esc_attr($pro_cat->name).'"></a>' : '<div class="els-catdt-img"></div>'; 
	        $output_default .= '<div class="masonry-grid__item__copy">';

	        if( $pro_cat->name ) {
	          $output_default .= '<h3 class="masonry-grid__item__title"><a class="link--border-bottom" href="'.esc_url($catUrl).'">'.esc_attr($pro_cat->name);
	          $output_default .= ($cat_pr_count) ? ' ['.esc_attr($pro_cat->count).']' : '';
	          $output_default .= '</a></h3>';
			  $output_default .= '<a href="'.esc_url($catUrl).'". class="cta icon--angle-right icon--outside">Shop '.esc_attr($pro_cat->name).'</a>';
	        }

	        if( $cat_des && $pro_cat->description ) {
	          $output_default .= '<div class="els-catdt-desc els-catsc-desc">'.esc_attr($pro_cat->description).'</div>';
	        }

	        $output_default .= '</div></div></div>';

		      if( $count === $count_all_cat ) {
	          $output_default .= '</div>';
	        }
	      }

	      $output_default .= '</div>';

	      echo $output_default; ?>

	      <script type="text/javascript">
	        jQuery(document).ready(function($) {
	          var $catGridDefault = $('.<?php echo esc_js($styled_class); ?> .els-catdt-box');
	      		$catGridDefault.imagesLoaded(function() {
				    	$catGridDefault.matchHeight({
				        	byRow: false
				    	});
	    			});
		    	});
	      </script>

	    <?php
	    }

      // Return outbut buffer
      return ob_get_clean();

	  }
  }

  add_shortcode( 'elsey_product_categories', 'elsey_product_categories_function' );

}
