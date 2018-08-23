<div id="sliderhome">
	<?php
	while ( have_posts() ) : the_post();
	the_content();
	endwhile;
	?>
</div>
<?php if ( date_i18n('YmdHi') >= "201808231200" ) { ?>
<div class="sub_banner xs-hide">
<a href="<?php echo home_url('/product-category/finalsummersale'); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner/SummerFinalSaleTopBanner-Desktop.jpg" alt="FINAL SUMMER SALE" /></a>
</div>
	  <div class="sub_banner xs-show">
		  <a href="<?php echo home_url('/product-category/finalsummersale'); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner/SummerFinalSale_TopBanner-Mobile.jpg" alt="FINAL SUMMER SALE" /></a>
	  </div>
	  <?php } else { ?><!--time set end-->
<div class="sub_banner xs-hide">
	<a href="<?php echo home_url('/product-category/thespringsale18'); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner/theSpringSaleTopBanner-Desktop.jpg" alt="springfair" /></a>
</div>
	  <div class="sub_banner xs-show">
		  <a href="<?php echo home_url('/product-category/thespringsale18'); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner/theSpringSale_TopBanner-Mobile01.jpg" alt="springfair" /></a>
	  </div>
<?php } ?>
<div class="max-width--site gutter-padding--full">
	<section id="justarrived" class="vc_section section_home_first">
		<h3 class="heading heading--main upper">Just Arrived</h3>
		<?php echo do_shortcode('[products limit="4" columns="4" orderby="date" order="DESC" visibility="visible"]'); ?>
		<div class="view_more"><a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="minmal_link">View more</a></div>
	</section>
	<section id="mobilecat" class="full_section xs-show">
		<?php
		$taxonomy     = 'product_cat';
		$orderby      = 'name';
		$show_count   = 0;      // 1 for yes, 0 for no
		$pad_counts   = 0;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no
		$title        = '';
		$empty        = 1;
		
		$args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
	     'exclude'    => '77,92,153,152,154,157,169',
         'hide_empty'   => $empty
		);
		$all_categories = get_categories( $args );
		?>
		<ul class="spcat_links">
			<?php
			 foreach ($all_categories as $cat) {
    if($cat->category_parent == 0) {
        $category_id = $cat->term_id;       
        echo '<li><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a></li>';

        $args2 = array(
                'taxonomy'     => $taxonomy,
                'child_of'     => 0,
                'parent'       => $category_id,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
			    'exclude'    => '157,161,158,159,163,145,146,147,148,151,150,149',//live from 145
                'hide_empty'   => $empty
        );
        $sub_cats = get_categories( $args2 );
        if($sub_cats) {
            foreach($sub_cats as $sub_category) {
                echo  '<li><a href="'. get_term_link($sub_category->slug, 'product_cat') .'">'. $sub_category->name .'</a></li>' ;
            }   
        }
    }       
}
			echo '<li class="viewall"><a href="'.get_permalink( wc_get_page_id( 'shop' ) ).'">View All</a></li>';
			?>
		</ul>
		
	</section>
	<section id="front-magazine" class="vc_section">
		<div class="magazine-article__section">
			<div class="vc_row wpb_row vc_inner vc_row-fluid align-bottom">
				<div class="magazine-article__col-content vertical--align-center mag_media_content_wrap wpb_column vc_column_container vc_col-sm-8">
					<div class="vc_column-inner">
						<div class="wpb_wrapper">
							<div class="row">
<div class="col-lg-4 headline">
<div class="headline_inner">
<div class="headline_inner_abs">
<p>it's time to</p>
<h3>CAROME. <br class="sp-none">SPRING</h3>
<div class="mag-text">
<ul class="mag-tags">
<li>#CAROME</li>
<li>#MUSTBUY</li>
<li>#SPRING</li>
<li>#FLOWERS</li>
</ul>
</div>
</div>
</div>
</div>
<div class="col-lg-8 mag-media">
<div class="magazine-article__media creditable-content">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/mag/mag18ss.jpg" />
</div>
</div>
</div>
						</div>
					</div>
				</div>
				<div class="magazine-article__col-content vertical--align-center mag_items wpb_column vc_column_container vc_col-sm-4">
					<div class="vc_column-inner">
						<div class="wpb_wrapper">
							<?php echo do_shortcode('[products limit="4" columns="2" orderby="date" order="DESC" ids="8166,8152,8946,8953," visibility="visible"]'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section id="cat-links-home" class="vc_section section_home_last show-pc">
		<div class="els-cat-default els-cat-wrap">
			<div class="row">
				<?php
				$prod_categories = get_terms( 'product_cat', array(
					'orderby'    => 'name',
					'order'      => 'ASC',
					'exclude'    => '69, 77,157,161,158,159,163,162,145,146,147,148,151,150,149,153,152,154,169',
					'number'     => 8,
					'hide_empty' => 1
				));
				foreach( $prod_categories as $prod_cat ) :
				$cat_thumb_id = get_woocommerce_term_meta( $prod_cat->term_id, 'thumbnail_id', true );
				$cat_thumb_url = wp_get_attachment_image_src( $cat_thumb_id, 'full' );
				?>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<div class="masonry-grid__item portrait">
						<a href="<?php echo get_category_link( $prod_cat->term_id ); ?>">
							<span class="overlay"></span>
							<img src="<?php echo $cat_thumb_url[0]; ?>" alt="<?php echo $prod_cat->name; ?>" />
						</a>
						<div class="masonry-grid__item__copy">
							<h3 class="masonry-grid__item__title"><a class="link--border-bottom" href="<?php echo get_category_link( $prod_cat->term_id ); ?>"><?php echo $prod_cat->name; ?></a></h3>
							<a class="cta icon--angle-right icon--outside" href="<?php echo get_category_link( $prod_cat->term_id ); ?>">Shop <?php echo $prod_cat->name; ?></a>
						</div>
					</div>
				</div>
				<?php endforeach; wp_reset_query(); ?>
			</div>
		</div>
	</section>