<div class="container els-footer-widget-area">
	<div class="row">
		<div class="col-sm-5 footer_menu_links">
			<div class="row">
				<?php echo elsey_vt_footer_widgets(); ?>
			</div>
		</div>
		<div class="col-sm-3 footer_menu_connect">
			<div class="social_widget els-widget els-footer-3-widget els-text-widget">
						<h2 class="widget-title"><span>Connect us</span></h2>
						<?php echo do_shortcode( '[vt_socials][vt_social social_link="https://www.facebook.com" social_icon="fa fa-facebook" target_tab="1"][vt_social social_link="https://www.instagram.com/carome_official/" social_icon="fa fa-instagram" target_tab="1"][/vt_socials]' ); ?>
					</div>
		</div>
		<div class="col-sm-3 footer_menu_newsletter">
		<h2 class="widget-title"><span>Newsletter</span></h2>
		<?php echo do_shortcode( '[yikes-mailchimp form="1"]' ); ?>
		</div>
	</div>
</div>