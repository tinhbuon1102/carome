<?php
/*
 * The template for displaying all single team.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

if ($elsey_meta) {
  $elsey_team_spacings       = $elsey_meta['content_spacings'];
  $elsey_team_top_spacing    = $elsey_meta['content_top_spacings'];
  $elsey_team_bottom_spacing = $elsey_meta['content_btm_spacings'];
} else {
  $elsey_team_spacings       = '';
}

if (empty($elsey_team_spacings) || ($elsey_team_spacings === 'els-padding-none')) {
  $elsey_team_spacings       = cs_get_option('team_spacings');
  $elsey_team_top_spacing    = cs_get_option('team_top_spacing');
  $elsey_team_bottom_spacing = cs_get_option('team_bottom_spacing');
}

if ($elsey_team_spacings && $elsey_team_spacings !== 'els-padding-none') {
  if ($elsey_team_spacings === 'els-padding-custom') {
	$elsey_team_top_spacing    = $elsey_team_top_spacing ? 'padding-top:'. elsey_check_px($elsey_team_top_spacing) .' !important;' : '';
	$elsey_team_bottom_spacing = $elsey_team_bottom_spacing ? 'padding-bottom:'. elsey_check_px($elsey_team_bottom_spacing) .' !important;' : '';
	$elsey_custom_padding = $elsey_team_top_spacing . $elsey_team_bottom_spacing;
  } else {
	$elsey_custom_padding = '';
  }
} else {
  $elsey_custom_padding = '';
}

$elsey_team_page_layout  = cs_get_option('team_page_layout');
$elsey_team_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';

if ($elsey_team_page_layout === 'full-width') {
  $elsey_parent_class = 'els-full-width';
  $elsey_layout_class = 'container';
} else {
  $elsey_parent_class = 'els-less-width';
  $elsey_layout_class = 'container els-reduced';
}

get_header();
?>

<!-- Container Start -->
<div class="els-container-wrap <?php echo esc_attr($elsey_parent_class . ' ' . $elsey_team_spacings); ?>" style="<?php echo esc_attr($elsey_custom_padding);?>">
  <div class="<?php echo esc_attr($elsey_layout_class); ?>">
    <div class="row">

      <!-- Content Column Start -->
      <div class="<?php echo esc_attr($elsey_team_column_class); ?> els-content-col">
        <div class="row els-content-area">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="els-single-team">

              <?php
              if (have_posts()) : while (have_posts()) : the_post();

                $elsey_team_large_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
                $elsey_team_large_image = $elsey_team_large_image[0];
                $elsey_team_options     = get_post_meta( get_the_ID(), 'team_options', true );
                $elsey_team_member_link = ($elsey_team_options['team_member_custom_link']) ? $elsey_team_options['team_member_custom_link'] : get_the_permalink();
                $elsey_team_member_job  = ($elsey_team_options['team_member_job_position']) ? $elsey_team_options['team_member_job_position'] : '';
                $elsey_team_member_fcb  = ($elsey_team_options['team_member_facebook_link']) ? $elsey_team_options['team_member_facebook_link'] : '';
                $elsey_team_member_twr  = ($elsey_team_options['team_member_twitter_link']) ? $elsey_team_options['team_member_twitter_link'] : '';
                $elsey_team_member_ins  = ($elsey_team_options['team_member_instagram_link']) ? $elsey_team_options['team_member_instagram_link'] : '';

                if ($elsey_team_large_image) { ?>
                  <div class="els-team-single-img">
                    <img src="<?php echo esc_url($elsey_team_large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/>
                  </div>
                <?php 
                } else {
                  echo '';
                } ?>

                <div class="els-team-member-name">
                  <?php the_title(); ?>
                </div>

                <?php if ($elsey_team_member_job) { ?>
                  <div class="els-team-member-job">
                    <?php echo esc_attr($elsey_team_member_job); ?>
                  </div>
                <?php } ?>

                <div class="els-team-content">
                  <?php the_content(); ?>
                </div>

                <?php if($elsey_team_member_fcb || $elsey_team_member_twr || $elsey_team_member_ins) { ?>  
                  <div class="els-team-member-details">   
                    <?php 
                    if(isset($elsey_team_member_fcb)) {
                      echo '<a href="'.esc_url($elsey_team_member_fcb).'" target="_blank">'.esc_html__('Facebook', 'elsey').'</a>';
                    } 
                    if(isset($elsey_team_member_twr)) {
                      echo ($elsey_team_member_fcb) ? esc_html__('/', 'elsey') : '';
                      echo '<a href="'.esc_url($elsey_team_member_twr).'" target="_blank">'.esc_html__('Twitter', 'elsey').'</a>';
                    } 
                    if(isset($elsey_team_member_ins)) {
                      echo ($elsey_team_member_fcb || $elsey_team_member_twr) ? esc_html__('/', 'elsey') : '';
                      echo '<a href="'.esc_url($elsey_team_member_ins).'" target="_blank">'.esc_html__('Instagram', 'elsey').'</a>';
                    } 
                    ?>
                  </div>
                <?php } 
   
              endwhile; endif;
              wp_reset_postdata();  // avoid errors further down the page
		          ?>

            </div>
      		</div>
        </div>
      </div>
      <!-- Content Column End -->
      
    </div>
  </div>
</div>
<!-- Container End -->

<?php get_footer();
