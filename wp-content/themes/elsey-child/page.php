<?php
/*
 * The template for displaying all pages.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */
global $wp;
$request = explode( '/', $wp->request );
// Metabox
global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

$elsey_content_padding = ($elsey_meta) ? $elsey_meta['content_spacings'] : '';

if ($elsey_content_padding && $elsey_content_padding !== 'els-padding-none') {
  $elsey_content_top_spacings = $elsey_meta['content_top_spacings'];
  $elsey_content_btm_spacings = $elsey_meta['content_btm_spacings'];
  if ($elsey_content_padding === 'els-padding-custom') {
    $elsey_content_top_spacings = $elsey_content_top_spacings ? 'padding-top:'. elsey_check_px($elsey_content_top_spacings) .' !important;' : '';
    $elsey_content_btm_spacings = $elsey_content_btm_spacings ? 'padding-bottom:'. elsey_check_px($elsey_content_btm_spacings) .' !important;' : '';
    $elsey_custom_padding = $elsey_content_top_spacings . $elsey_content_btm_spacings;
  } else {
    $elsey_custom_padding = '';
  }
} else {
  $elsey_custom_padding = '';
}

if ($elsey_meta) {
  $elsey_titlebar_options = ($elsey_meta['titlebar_options']) ? $elsey_meta['titlebar_options'] : '';
  if ($elsey_titlebar_options === 'hide') {
  	$elsey_title_bar_show = false;
  } elseif ($elsey_titlebar_options === 'custom') {
  	$elsey_title_bar_show = true;
  } else {
    $elsey_title_bar_show = cs_get_option('need_titlebar');
  }
} else {
  $elsey_title_bar_show = cs_get_option('need_titlebar');
}

// Page Layout Options
$elsey_page_layout_options = get_post_meta( get_the_ID(), 'page_layout_options', true );

if ($elsey_page_layout_options) {
  $elsey_page_layout           = $elsey_page_layout_options['page_layout'];
  $elsey_page_show_sidebar     = $elsey_page_layout_options['page_show_sidebar'];
  $elsey_page_sidebar_position = $elsey_page_layout_options['page_sidebar_position'];

  if ($elsey_page_layout === 'full-width') {
    $elsey_parent_class = 'els-full-width';
    $elsey_layout_class = 'container';
  } else if ($elsey_page_layout === 'strech-width') {
    $elsey_parent_class = 'els-strech-width';
    $elsey_layout_class = 'container-fluid';
  } else {
    $elsey_parent_class = 'els-less-width';
    $elsey_layout_class = 'container els-reduced max-width--large';
  }

  if ($elsey_page_show_sidebar) {

    if ($elsey_page_sidebar_position === 'sidebar-left') {
      $elsey_column_class = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-left-col';
      $elsey_sidebar_position = $elsey_page_sidebar_position;
    } else {
      $elsey_column_class = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-right-col';
      $elsey_sidebar_position = $elsey_page_sidebar_position;
    }

    if (!$elsey_title_bar_show) {
      $elsey_layout_class .= ' els-top-space';
    }

  } else {
    $elsey_column_class     = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
    $elsey_sidebar_position = 'sidebar-hide';
  }
} else {
  $elsey_page_show_sidebar = false;
  $elsey_sidebar_position  = 'sidebar-hide';
  $elsey_parent_class = 'els-less-width';
  $elsey_layout_class = 'container els-reduced';
  $elsey_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
}

get_header(); ?>
<!-- Container Start -->
<div class="els-container-wrap <?php echo esc_attr($elsey_parent_class.' '.$elsey_content_padding); ?>" style="<?php echo esc_attr($elsey_custom_padding);?>">
	<?php 
	if (current_time('mysql') >= "2019-04-01 12:00") { ?>
	<?php if(is_page('contact')) { ?>
	<div class="temp_contact">
		<div class="temp_inner">
			<strong>■CAROME.ECサイトに掲載するアイライナーの商品に関する問い合わせ先</strong>
			<p>アイライナー商品に関するお問い合わせに関しましては、<span class="bold_font red">問い合わせフォームにての連絡は承っておりません</span>ので、以下の連絡先にてお問い合わせをお願い致します。<br/></p>
			<p>I-neカスタマーセンター<br/>
電話番号 <a href="tel:0120-333-476" class="tel_link">0120-333-476</a><br/>
受付時間 9:00-18:00<br/>
休日 土日祝（年末年始・夏季休暇）</p>
		</div>
	</div>
	<?php } ?>
<?php } else { ?>
	
<?php } ?>
	
	
	<?php if ($elsey_page_show_sidebar == false) { ?>
	<?php if( ! ( end($request) == 'my-account' && is_account_page() ) ){ ?>
	<?php
            // echo json_encode( get_option('_cs_options') ); // BrixeyWP - JSON File, json, Json.
	        if(is_page('kimono-rental')){
			get_template_part( 'template-parts/content', 'rentalform' );
	        //else if(is_page('kimono-rental')):
			}elseif( is_front_page() ){
				get_template_part( 'template-parts/content', 'home' );
			}elseif( is_page('enter') ){
				get_template_part( 'template-parts/content', 'event' );
			}else{
            while ( have_posts() ) : the_post();
              the_content();
            endwhile;
			}
            ?>
	<?php } else { ?>
	<div class="max-width--large">
		<div class="els-content-col">
			<div class="els-content-area">
				<?php
            // echo json_encode( get_option('_cs_options') ); // BrixeyWP - JSON File, json, Json.
            while ( have_posts() ) : the_post();
              the_content();
            endwhile;
            ?>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<?php } else { ?>
	<div class="<?php echo esc_attr($elsey_layout_class); ?> max-width--large">
    <div class="row">
      <?php if( ($elsey_page_show_sidebar == true) && ($elsey_sidebar_position === 'sidebar-left') ) { get_sidebar(); } ?>

      <!-- Content Col Start -->
      <div class="<?php echo esc_attr($elsey_column_class); ?> els-content-col">
        <div class="row els-content-area">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php
            // echo json_encode( get_option('_cs_options') ); // BrixeyWP - JSON File, json, Json.
            while ( have_posts() ) : the_post();
              the_content();
            endwhile;
            ?>
          </div>
        </div>
      </div>
      <!-- Content Col End -->

      <?php if( ($elsey_page_show_sidebar == true) && ($elsey_sidebar_position === 'sidebar-right') ) { get_sidebar(); } ?>

	  </div>
  </div>
	<?php } ?>
</div>
<!-- Container End -->

<?php get_footer();
