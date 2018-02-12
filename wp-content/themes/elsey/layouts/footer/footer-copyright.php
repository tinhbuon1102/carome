<?php
// Theme Options
$need_copyright = cs_get_option('need_copyright');
$footer_copyright_layout = cs_get_option('footer_copyright_layout');

if ($footer_copyright_layout === 'copyright-2') {
  $copyright_layout_class    = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 els-align-left';
  $copyright_seclayout_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 els-align-right';
} elseif ($footer_copyright_layout === 'copyright-3') {
  $copyright_layout_class    = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 els-align-right';
  $copyright_seclayout_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 els-align-left';
} else {
  $copyright_layout_class    = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-align-center';
  $copyright_seclayout_class = '';
}

$copyright_text = cs_get_option('copyright_text');
$secondary_text = cs_get_option('secondary_text');

if (isset($need_copyright)) { ?>

	<!-- Copyright Bar Start -->
	<div class="els-copyright-bar">
	  <div class="container">
	    <div class="row">

		    <?php if ($footer_copyright_layout === 'copyright-3') { ?>
		      <div class="<?php echo esc_attr($copyright_seclayout_class); ?>"><?php echo do_shortcode($secondary_text); ?></div>
		    <?php } ?>

		    <div class="<?php echo esc_attr($copyright_layout_class); ?>">
		    	<?php
		        if (isset($copyright_text)) {
		          echo do_shortcode($copyright_text);
		        } else {
		          echo '&copy; <a href="https://victorthemes.com/" target="_blank">'.esc_html__('Victorthemes', 'elsey').'</a> '.esc_html__('- Elite ThemeForest Author.', 'elsey');
		        } ?>
		    </div>

		    <?php if ($footer_copyright_layout === 'copyright-2') { ?>
		      <div class="<?php echo esc_attr($copyright_seclayout_class); ?>"><?php echo do_shortcode($secondary_text); ?></div>
		    <?php } ?>

		  </div>
	  </div>
	</div>
	<!-- Copyright Bar End -->

<?php } ?>