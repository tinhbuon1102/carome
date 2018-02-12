<?php
/* ==========================================================
  Blog
=========================================================== */
if (!function_exists('elsey_blog_function')) {

  function elsey_blog_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
   	  'blog_title'             => '',
      'blog_style'             => 'els-blog-one',
      'blog_columns'           => '',
      'blog_limit'             => '',
      // Enable & Disable
      'blog_category'          => '',
      'blog_date'              => '',
      'blog_author'            => '',
      'blog_popup'             => '',
      'blog_excerpt'           => '',
      'blog_read_more'         => '',
      'blog_share'             => '',
      'blog_pagination'        => '',
      'blog_pagination_style'  => '',
      'blog_pagination_align'  => 'els-pagenavi-center',
      'blog_excerpt_length'    => '',
      'blog_slider_loop'       => '',
      'blog_slider_nav'        => '',
      'blog_slider_dots'       => '',
      'blog_slider_autoplay'   => '',
      'blog_slider_nav_speed'  => '',
      'blog_slider_dots_speed' => '',
      'blog_slider_ap_speed' 	 => '',
      // Translation Text
      'blog_read_more_text'    => '',
      // Listing
      'blog_order'             => 'DESC',
      'blog_orderby'           => 'date',
      'blog_category_slugs'    => '',
      // Custom Class
      'class'                  => '',
    ), $atts));

    $e_uniqid = uniqid();
    $blog_styled_class = 'els-blog-'. $e_uniqid;

    // Blog Slider Style Values
    $blog_slider_loop       = $blog_slider_loop ? 'true' : 'false';
    $blog_slider_nav        = $blog_slider_nav ? 'true' : 'false';
    $blog_slider_dots       = $blog_slider_dots ? 'true' : 'false';
    $blog_slider_autoplay   = $blog_slider_autoplay ? 'true' : 'false';
    $blog_slider_nav_speed  = $blog_slider_nav_speed ? (int)$blog_slider_nav_speed : '500';
    $blog_slider_dots_speed = $blog_slider_dots_speed ? (int)$blog_slider_dots_speed : '500';    
    $blog_slider_ap_speed   = $blog_slider_ap_speed ? (int)$blog_slider_ap_speed : '500';
   
    // Blog Style
    if ( $blog_style === 'els-blog-one' ) {
      $blog_style_class  = 'els-blog-standard';
      $blog_parent_class = '';
      $blog_item_class   = '';
    } else if ( $blog_style === 'els-blog-two' ) {
      $blog_style_class  = 'els-blog-masonry';
      $blog_parent_class = 'els-blog-masonry-wrap';
      $blog_item_class   = 'els-blog-masonry-item';
    } else {
      $blog_style_class  = 'els-blog-slider els-top-arrow';
      $blog_parent_class = 'els-blog-slider-wrap owl-carousel';
      $blog_item_class   = 'els-blog-slider-item row';
    }

    // Column Style
    if ($blog_columns === 'els-blog-col-2') {
      $blog_grid_number  = 2;
      $blog_column_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
      $blog_readmore_col_class = 'col-lg-5 col-md-5 col-sm-5 col-xs-12';
      $blog_share_col_class    = 'col-lg-7 col-md-7 col-sm-7 col-xs-12';
    } else if ($blog_columns === 'els-blog-col-3') {
      $blog_grid_number  = 3;
      $blog_column_class = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
      if ($blog_style === 'els-blog-two') {
        $blog_readmore_col_class = 'col-lg-5 col-md-12 col-sm-12 col-xs-12';
        $blog_share_col_class    = 'col-lg-7 col-md-12 col-sm-12 col-xs-12';
      } else {
        $blog_readmore_col_class = 'col-lg-5 col-md-12 col-sm-12 col-xs-12';
        $blog_share_col_class    = 'col-lg-7 col-md-12 col-sm-12 col-xs-12';
      }
    } else {
      $blog_grid_number  = 1;
      $blog_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
      $blog_readmore_col_class = 'col-lg-5 col-md-5 col-sm-5 col-xs-12';
      $blog_share_col_class    = 'col-lg-7 col-md-7 col-sm-7 col-xs-12';
    }

    // Translation Text
    if (elsey_framework_active()) {
      $read_more_to = cs_get_option('blog_read_more_text');
      $excerpt_length = cs_get_option('blog_excerpt_length');

      if ($blog_read_more_text) {
        $blog_read_more_text = $blog_read_more_text;
      } elseif ($read_more_to) {
        $blog_read_more_text = $read_more_to;
      } else {
        $blog_read_more_text = esc_html__( 'Continue Reading', 'elsey-core' );
      }  

      if ($blog_excerpt_length) {
        $blog_excerpt_length = $blog_excerpt_length;
      } elseif ($excerpt_length) {
        $blog_excerpt_length = $excerpt_length;
      } else {
        $blog_excerpt_length = '55';
      }
    } else {
      $blog_excerpt_length = $blog_excerpt_length ? $blog_excerpt_length : '55';
      $blog_read_more_text = $blog_read_more_text ? $blog_read_more_text : esc_html__( 'Continue Reading', 'elsey-core' );
    }

    // Turn output buffer on
    ob_start();

    // Pagination
    global $paged;
    if (get_query_var('paged'))
      $my_page = get_query_var( 'paged' );
    else {
      $my_page = (get_query_var('page')) ? get_query_var('page') : 1;
      set_query_var('paged', $my_page);
      $paged = $my_page;
    }

    // Other query params here
    $args = array(
      'paged'          => $my_page,
      'post_type'      => 'post',
      'posts_per_page' => (int)$blog_limit,
      'category_name'  => esc_attr($blog_category_slugs),
      'orderby'        => $blog_orderby,
      'order'          => $blog_order
    );

    $elsey_post = new WP_Query( $args ); ?>

    <!-- Blog Start -->
    <div class="els-blog-wrapper <?php echo esc_attr($blog_style_class.' '.$blog_styled_class.' '.$class); ?>">

      <?php if ($blog_title) { echo '<h4 class="els-blog-title">'.esc_attr($blog_title).'</h4>'; } ?>

      <div class="els-blog-inner <?php echo esc_attr($blog_parent_class); ?>">

        <?php 
        if ($elsey_post->have_posts()) :
          $count_all_post = $elsey_post->post_count;
          $count = 0;

          while ($elsey_post->have_posts()) : $elsey_post->the_post();
            $count++;    

            if ( ( $blog_style === 'els-blog-one' ) || ( $blog_style === 'els-blog-two' ) ) {

	            if ( $blog_style === 'els-blog-one' ) {
	              if ( $blog_grid_number === 1 ) {
	                echo '<div class="row">';
	              } else {
	                if ($count === 1) {
	                  echo '<div class="row">';
	                } else if( ( $count % $blog_grid_number ) === 1 ) {
	                  echo '<div class="row">';
	                }
	              }
	            } else if ($blog_style === 'els-blog-two') {
	              if ($count === 1) {
	                echo '<div class="els-blog-masonry-gutter"></div>';
	                echo '<div class="'.esc_attr($blog_column_class).' els-blog-masonry-sizer"></div>';
	              }
	            } 

	            $large_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'fullsize', false, '' );
	            $large_image = $large_image[0];

	            $post_type = get_post_meta( get_the_ID(), 'post_type_metabox', true );
              $post_ferd = get_post_meta( get_the_ID(), 'post_featured_options', true );

              if ($blog_style === 'els-blog-two') {
                if (!empty($post_ferd['masonry_featured_image'])) {    
                  $img_id = $post_ferd['masonry_featured_image'];
                  $attachment = wp_get_attachment_image_src( $img_id, 'full' ); 
                  $large_image = $attachment[0];
                } else {
                  $large_image = $large_image;
                }
              } 

	            $sticky_class = (is_sticky(get_the_ID())) ? 'sticky' : ''; ?>

            	<div class="<?php echo esc_attr($blog_column_class.' '.$blog_item_class); ?>">           
              	<div class="els-recent-blog">
                	<div id="post-<?php the_ID(); ?>" <?php post_class('els-blog-post '.$sticky_class); ?>>

                  	<!-- Blog Introduction Start -->
                  	<div class="els-blog-intro">

	                    <h3 class="els-blog-heading">
	                      <a href="<?php echo esc_url(get_permalink()); ?>">
	                        <?php echo esc_attr(get_the_title()); ?>
	                      </a>
	                    </h3>  

                    	<?php if ($blog_author || $blog_category) { // Meta's Hide ?>
	                      <div class="els-blog-cat-author">
	                        <ul>
	                        <?php if ($blog_category) { // Category Hide  ?>
	                          <li class="els-blog-cat">       
	                            <label><?php esc_html_e('Category: ', 'elsey-core'); ?></label>                                 
	                            <?php                            
	                            $categories = get_the_category();
	                            if ($categories) {
	                              the_category('<span>&nbsp;&amp;&nbsp;</span>');
	                            } ?>
	                          </li>
	                        <?php } if ($blog_author) { // Author Hide 
	                          if ($blog_category) { ?>
	                          	<li><span>/</span></li>
	                          <?php } ?>
	                          <li><span>by</span></li>
	                          <li>
	                          	<?php printf('<a href="%1$s" rel="author">%2$s</a>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author()); ?>
	                          </li>
	                        <?php } if( ('audio' == get_post_format()) || ('video' == get_post_format()) || (!('gallery' == get_post_format()) && !$large_image) ) {
	                          if ($blog_author || $blog_category) { ?>
	                            <li><span>/</span></li>
	                          <?php } ?>
	                          <li>
	                             <?php echo '<div class="els-blog-single-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>'; ?>
	                          </li>
	                        <?php } ?>
	                        </ul>
	                      </div>
                    	<?php } ?>

                  	</div>
                  	<!-- Blog Introduction End -->

                  	<!-- Blog Publish Start -->
                  	<div class="els-blog-publish">    
                    
                    	<?php if ( ('gallery' == get_post_format()) && !empty($post_type['gallery_post_format']) ) {                                             
                     
                        $images = []; 
                        $ids = explode( ',', $post_type['gallery_post_format'] );     

	                      foreach ( $ids as $id ) {
	                        $attachment = wp_get_attachment_image_src( $id, 'full' );
	                        if ( isset($attachment[0]) ) {
	                          array_push($images, $attachment[0]);
	                        }
	                      }

                      	if ( count($images) > 0 ) { 
                      
	                        if ($blog_date) { 
	                          echo '<div class="els-blog-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>';
	                        } 

                        	echo '<div class="els-blog-slider-box"><ul class="owl-carousel els-feature-img-carousel">';
                       
	                        foreach ( $ids as $id ) {
	                          $attachment = wp_get_attachment_image_src($id, 'full');
	                          $alt        = get_post_meta($id, '_wp_attachment_image_alt', true);
	                          $alt        = $alt ? esc_attr($alt) : esc_attr(get_the_title());
	                          $g_img      = $attachment[0]; 

	                          if($blog_style === 'els-blog-one') {
	                            if($blog_columns === 'els-blog-col-3') {
	                              if($g_img) {
	                                if(class_exists('Aq_Resize')) {
	                                  $featured_img = aq_resize( $g_img, '370', '235', true );
	                                  $post_img = ($featured_img) ? $featured_img : ELSEY_PLUGIN_ASTS . '/images/370x235.jpg';
	                                } else {
	                                  $post_img = $g_img;
	                                }
	                              } else {
	                                $post_img = '';
	                              }
	                            } else if($blog_columns === 'els-blog-col-2') {
	                              if($g_img){
	                                if(class_exists('Aq_Resize')) {
	                                  $featured_img = aq_resize( $g_img, '570', '355', true );
	                                  $post_img = ($featured_img) ? $featured_img : ELSEY_PLUGIN_ASTS . '/images/570x355.jpg';
	                                } else {
	                                  $post_img = $g_img;
	                                }
	                              } else {
	                                $post_img = '';
	                              }
	                            } else {
	                              if($g_img){
	                                $post_img = $g_img;
	                              } else {
	                                $post_img = '';
	                              }
	                            }
	                          } else if($blog_style === 'els-blog-two') {
	                            $post_img = ($g_img) ? $g_img : '';
	                          } 

	                          if ($blog_popup) {
	                            $popup_class = 'els-img-popup';
	                            $link_to = ($g_img) ? $g_img : get_the_permalink();
	                          } else {
	                            $popup_class = '';
	                            $link_to = get_the_permalink();
	                          }

	                          if ($post_img) {
	                            echo '<li><a href='.esc_url($link_to).' class="'.esc_attr($popup_class).'"><img src="'.esc_url($post_img).'" alt="'.$alt.'" /></a></li>';
	                          } else {
	                            echo '';
	                          }                          
                        	}
                       	  echo '</ul></div>';
	                      } else {
	                        echo '';
	                      }

                   	  } elseif (('audio' == get_post_format()) && ! empty( $post_type['audio_post_format'])) { 

                      	echo '<div class="els-music">'. $post_type['audio_post_format'] .'</div>';

                    	} elseif (('video' == get_post_format()) && ! empty( $post_type['video_post_format'])) {

                      	echo '<div class="els-video">'. $post_type['video_post_format'] .'</div>'; 

                    	} elseif ($large_image) {

	                      if ($blog_date) { 
	                        echo '<div class="els-blog-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>';
	                      } 

		                    if ($blog_style === 'els-blog-two') {
		                      $post_img = ($large_image) ? $large_image : ELSEY_PLUGIN_ASTS . '/images/1170x705.jpg';
		                    } else {
		                      if ($blog_columns === 'els-blog-col-3') {
		                        if (class_exists('Aq_Resize')) {
		                          $post_img = aq_resize( $large_image, '370', '235', true );
		                          $post_img = ($post_img) ? $post_img : ELSEY_PLUGIN_ASTS . '/images/370x235.jpg';
		                        } else {
		                          $post_img = $large_image;
		                        }
		                      } else if ($blog_columns === 'els-blog-col-2') {
		                        if (class_exists('Aq_Resize')) {
		                          $post_img = aq_resize( $large_image, '570', '355', true );
		                          $post_img = ($post_img) ? $post_img : ELSEY_PLUGIN_ASTS . '/images/570x355.jpg';
		                        } else {
		                          $post_img = $large_image;
		                        }
		                      } else {
		                        $post_img = $large_image;
		                      }
		                    }  

		                    if ($blog_popup) {
		                      $popup_class = 'els-img-popup';
		                      $link_to = $large_image;
		                    } else {
		                      $popup_class = '';
		                      $link_to = get_the_permalink();
		                    } ?>

		                    <div class="els-blog-feature-img">
		                      <a href="<?php echo esc_url($link_to); ?>" class="<?php echo esc_attr($popup_class); ?>"><img src="<?php echo esc_url($post_img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/></a>
		                    </div>
	                  
                    	<?php } else { echo ''; } ?>

										</div>
                  	<!-- Blog Publish End -->
             
		                <?php if($blog_excerpt) { ?>     
	                  <!-- Blog Excerpt Start -->
	                    <div class="els-blog-excerpt">
	                      <?php  
	                      if ( function_exists( 'elsey_excerpt' ) ) { 
	                        elsey_excerpt($blog_excerpt_length); 
	                      }
	                      if ( function_exists( 'elsey_wp_link_pages' ) ) { 
	                        echo elsey_wp_link_pages(); 
	                      }
		                    ?>
	                    </div>
	                  <!-- Blog Excerpt End -->
	                  <?php } ?>
                  
	         
	                  <?php if ($blog_read_more || $blog_share) { ?>
	                  <!-- Blog More Start -->
		                  <div class="els-blog-more row">
		                  	<?php if($blog_read_more) { ?>
		                      <div class="els-blog-readmore <?php echo esc_attr($blog_readmore_col_class); ?>">
		                        <a href="<?php echo esc_url(get_permalink()); ?>">
		                          <?php echo esc_attr($blog_read_more_text); ?>
		                        </a>
		                      </div>
		                    <?php } if($blog_share) { ?>
		                      <div class="els-blog-share <?php echo esc_attr($blog_share_col_class); ?>">
		                        <?php if ( function_exists( 'elsey_wp_share_option' ) ) { echo elsey_wp_share_option(); } ?>
		                      </div>
		                    <?php } ?>
		                  </div>
	                  <!-- Blog More End -->
		                <?php } ?>
	                   
                	</div>
              	</div>

	            <?php
	            if ($blog_style === 'els-blog-two') {
	              echo '</div>';
	            } else {
	              if ( $blog_grid_number === 1 ) {
	                echo '</div></div>';
	              } else {
	                echo '</div>';
	                if((($count % $blog_grid_number) === 0) || ($count === ($count_all_post))) {
	                  echo '</div>';
	                }
	              }
	            }

	          } else if ( $blog_style === 'els-blog-three' ) { 

          		$large_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'fullsize', false, '' );
	            $large_image = $large_image[0]; 

              if ($large_image) {
                $details_col_class = 'col-lg-7 col-md-7 col-sm-12 col-xs-12';
              } else {
                $details_col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
              }

              echo '<div class="'.esc_attr($blog_item_class).'">';

	            if ($large_image) {
                echo '<div class="els-blog-slider-image col-lg-5 col-md-5 col-sm-12 col-xs-12">';
  	            	if (class_exists('Aq_Resize')) {
                    $post_img = aq_resize( $large_image, '235', '235', true );
                    $post_img = ($post_img) ? $post_img : ELSEY_PLUGIN_ASTS . '/images/235x235.jpg';
                  } else {
                    $post_img = $large_image;
                  }
                  echo '<img src="'.esc_url($post_img).'" alt="'.esc_attr(get_the_title()).'"/>';
                echo '</div>';
	            } else {
	            	echo '';
	            }     	

	          	echo '<div class="els-blog-slider-details '.esc_attr($details_col_class).'">'; 
						
							if ( $blog_date || $blog_category ) { 
								echo '<ul>';
								if ($blog_category) {
									echo '<li class="els-blog-slider-cat">';
									$categories = get_the_category();
									if ($categories) { the_category('<span>&nbsp;&amp;&nbsp;</span>'); }
                  echo '</li>';
                } if ($blog_date) {
                	if ($blog_category) { echo '<li><span>/</span></li>'; }
                	echo '<li class="els-blog-slider-date">'.get_the_time('d M Y').'</li>';
          		  } 
          		  echo '</ul>';
          		} 

          		echo '<h4 class="els-blog-slider-title">'.esc_attr(get_the_title()).'</h4>';  

          	  if( $blog_excerpt ) {      
                echo '<div class="els-blog-slider-excerpt">';
                if ( function_exists( 'elsey_excerpt' ) ) { elsey_excerpt($blog_excerpt_length); }
                if ( function_exists( 'elsey_wp_link_pages' ) ) { echo elsey_wp_link_pages(); }
	              echo '</div>';
              }

              if ( $blog_read_more ) { 
              	echo '<div class="els-blog-readmore"><a href="'.esc_url(get_permalink()).'">'.esc_html__('Read More', 'elsey-core').'</a></div>';
              }

	          	echo '</div></div>'; ?>

	          <?php
	          }

          endwhile;
        endif; ?>

      </div>

      <?php
      if ($blog_pagination) {
        $lmore_post_text = cs_get_option('lmore_post_text');
        $older_post_text = cs_get_option('older_post_text');
        $newer_post_text = cs_get_option('newer_post_text');

        if ( $blog_pagination_style === 'els-pagination-three') {
          $lmore_post_text = $lmore_post_text ? $lmore_post_text : esc_html__( 'Load More', 'elsey-core' ); ?>
          <div class="els-load-more-box els-blog-load-more-box">
            <div class="els-load-more-link els-blog-load-more-link">
              <?php next_posts_link( '&nbsp;', $elsey_post->max_num_pages ); ?>
            </div>
            <div class="els-load-more-controls els-blog-load-more-controls els-btn-mode">
              <div class="line-scale-pulse-out">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
              </div>
              <a href="javascript:void(0);" id="els-blog-load-more-btn" class="els-btn"><?php echo $lmore_post_text; ?></a>
              <a href="javascript:void(0);" id="els-loaded" class="els-btn"><?php echo esc_html__( 'All Loaded', 'elsey-core' ); ?></a>
            </div>
          </div>
        <?php } elseif ($blog_pagination_style === 'els-pagination-two') {
          $older_post_text = $older_post_text ? $older_post_text : esc_html__( 'Older Posts', 'elsey-core' );
          $newer_post_text = $newer_post_text ? $newer_post_text : esc_html__( 'Newer Posts', 'elsey-core' ); ?>
          <div class="els-prev-next-pagination els-blog-single-pagination">
            <div class="row">     
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 older">
                <?php next_posts_link( '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt=""> ' . $older_post_text, $elsey_post->max_num_pages ); ?>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 newer">
                <?php previous_posts_link( $newer_post_text . ' <img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">', $elsey_post->max_num_pages ); ?>
              </div>
            </div>
          </div>
        <?php } else {
          if ( function_exists('wp_pageblog_slider_navi') ) {
            echo '<div class="'.esc_attr($blog_pagination_align).'">';
              wp_pageblog_slider_navi(array('query' => $elsey_post));
            echo '</div>';
          } else {
            $older_post_text = $older_post_text ? $older_post_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt="">';
            $newer_post_text = $newer_post_text ? $newer_post_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">';
            $big = 999999999;
            if( $elsey_post->max_num_pages == '1' ) {} else { echo ''; } 
            echo '<div class="'.esc_attr($blog_pagination_align).'">';
            echo '<div class="wp-pagenavi">';
            echo paginate_links( array(
              'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
              'format'    => '?paged=%#%',
              'total'     => $elsey_post->max_num_pages,
              'current'   => max( 1, $my_page ),
              'show_all'  => false,
              'end_size'  => 1,
              'mid_size'  => 2,
              'prev_next' => true,
              'prev_text' => $older_post_text,
              'next_text' => $newer_post_text,
              'type'      => 'list'
            ) );
            echo '</div></div>';
            if( $elsey_post->max_num_pages == '1' ) {} else {echo '';}
          }
        }
      }
      wp_reset_postdata();  // avoid errors further down the page ?>

    </div>
    <!-- Blog End -->

    <?php if ( $blog_style === 'els-blog-three' ) { ?>
	    <script type="text/javascript">
	      jQuery(document).ready(function($) {       
	        var $owl = $('.<?php echo esc_js($blog_styled_class); ?> .els-blog-slider-wrap');   
	        $owl.owlCarousel({
	          items:         2,
	          margin:        29,
	          loop:          <?php echo esc_js($blog_slider_loop); ?>,
	          nav:           <?php echo esc_js($blog_slider_nav); ?>,
	          dots:          <?php echo esc_js($blog_slider_dots); ?>,
	          autoplay:      <?php echo esc_js($blog_slider_autoplay); ?>,           
	          navSpeed:      <?php echo esc_js($blog_slider_nav_speed); ?>, 
	          dotsSpeed:     <?php echo esc_js($blog_slider_dots_speed); ?>, 
	          autoplaySpeed: <?php echo esc_js($blog_slider_ap_speed); ?>, 
	          autoHeight:    false,
	          navText:       ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
	          responsive:    {
	                            0: {
	                                items: 1
	                            },
	                            480: {
	                                items: 2
	                            },
	                         },
	        });
	      });      
	    </script>
	  <?php }

    // Return outbut buffer
    return ob_get_clean();
  }
}

add_shortcode( 'elsey_blog', 'elsey_blog_function' );