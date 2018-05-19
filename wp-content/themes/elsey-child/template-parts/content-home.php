<div id="sliderhome">
	<div class="xs-hide"><?php echo do_shortcode('[metaslider id="5379"]'); ?></div>
	<div class="xs-show"><?php echo do_shortcode('[metaslider id="5383"]'); ?></div>
</div>
<div class="max-width--site gutter-padding--full">
	<section id="justarrived" class="vc_section section_home_first">
		<h3 class="heading heading--main upper">Just Arrived</h3>
		<?php echo do_shortcode('[products limit="4" columns="4" orderby="date" order="DESC" visibility="visible"]'); ?>
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
				<div class="magazine-article__col-content vertical--align-center wpb_column vc_column_container vc_col-sm-4">
					<div class="vc_column-inner">
						<div class="wpb_wrapper">
							<?php echo do_shortcode('[products limit="4" columns="2" orderby="date" order="DESC" ids="8166,8152,8946,8953," visibility="visible"]'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section id="cat-links-home" class="vc_section section_home_last">
		<div class="els-cat-default els-cat-wrap">
			<div class="row">
				<?php
				$prod_categories = get_terms( 'product_cat', array(
					'orderby'    => 'name',
					'order'      => 'ASC',
					'exclude'    => '69, 77',
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