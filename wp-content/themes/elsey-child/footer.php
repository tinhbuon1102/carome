<?php
/*
 * The template for displaying the footer.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

global $post;
$elsey_id    = ( isset( $post ) ) ? $post->ID : false;
$elsey_id    = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id    = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
$elsey_meta  = get_post_meta( $elsey_id, 'page_type_metabox', true );

if ($elsey_meta) {
  $elsey_hide_footer     = $elsey_meta['hide_footer'];
  $elsey_menubar_options = $elsey_meta['menubar_options'];

  if ($elsey_menubar_options === 'hide') {
    $elsey_menubar_rightmenu = false;
  } elseif ($elsey_menubar_options === 'custom') {
    $elsey_menubar_rightmenu = $elsey_meta['menubar_rightmenu'];
  } else {
    $elsey_menubar_rightmenu = cs_get_option('menubar_rightmenu');
  }
} else {
  $elsey_hide_footer  = false;
  $elsey_menubar_rightmenu  = cs_get_option('menubar_rightmenu');
} ?>
<?php if ( !is_product() ){ echo '</div>'; } ?>
<!-- Content Background End -->
<?php if ( !is_product() ){ ?>
<?php } ?>
</div>
<!-- max-width--site End -->
</div>
<!-- Wrapper End -->

<?php
$elsey_footer_widget  = cs_get_option('footer_widget_block');
$elsey_need_copyright = cs_get_option('need_copyright');

if (!$elsey_hide_footer) {
  if ($elsey_footer_widget || $elsey_need_copyright) { ?>
    <!-- Footer Start -->
    <footer class="els-footer">
		<div class="footer__container max-width--site">
      <?php if (isset($elsey_footer_widget)) {
        // Footer Widget Block
        get_template_part( 'layouts/footer/footer', 'widgets' );
      }
      if (isset($elsey_need_copyright)) {
        // Copyright Block
        get_template_part( 'layouts/footer/footer', 'copyright' );
      } ?>
		</div>
    </footer>
    <!-- Footer End-->
<?php
  }
} ?>
<div class="focus-overlay focus-overlay--body"></div>
</div><!-- Wrap End -->

<?php
if ($elsey_menubar_rightmenu) {
  echo '<a href="javascript:void(0)" id="els-sidebar-menu-footer-close" class="els-sidebar-menu-footer-close"><i class="fa fa-times" aria-hidden="true"></i></a>';
}

if (function_exists('elsey_preloader_option')) { echo elsey_preloader_option(); } else { echo ''; }
wp_footer(); ?>
		<?php if (is_product()) { ?><div class="remodal remodalSource" data-remodal-id="waitlistmodal"></div><?php } ?>

<!-- If User Not Logged In Then Signup Popup-->		
<div id="sisfySignPopup" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
		<p><?=_e("You need to login if u will use favorite list.","elsey");?></p>
        <p><a class="slogin" href="<?php echo home_url()."/my-account/favorite-list/"; ?>"><?=_e("Login","elsey");?></a><a class="sclose" data-dismiss="modal"><?=_e("Close");?></a></p>
      </div>
    </div>
  </div>
</div>

<div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
  <div>
    <h2 id="modal1Title" class="modal_smallhead">年末年始期間の営業及び配送に関しましてのご案内</h2>
    <p id="modal1Desc">
      いつもCAROME. ONLINEをご利用いただき、誠にありがとうございます。<br/><br/>
 
年末年始期間中の営業につきまして、下記の通りとさせていただきます。<br/>
お客様にはご不便、ご迷惑をおかけいたしますが、何卒ご理解いただきます様よろしくお願いいたします。<br/><br/>
 
【カスタマーセンター休業日】<br/>
休業日は2018年12月28日(金)〜2019年1月6日(日)となっております。<br/>
カスタマーセンターでは年末年始期間中もお問合せフォーム及びメールでのお問い合せを受け付けておりますが、通常よりご返信までにお時間をいただく場合がございます。1月7日(月)より順に順次ご返答させていただきますので、あらかじめご了承下さい。<br/><br/>
 
【配送について】<br/>
2018年12月27日午前11時以降にご注文いただきました商品より、2019年1月7日以降、順にご発送の方をさせていただきます。<br/>
ご注文から商品到着まで、通常よりお時間をいただく事、あらかじめご了承ください。
    </p>
  </div>
  <br>
  <button data-remodal-action="cancel" class="remodal-cancel close_jabtn">閉じる</button>
</div>
<?php if ( is_page('enter') ) {
	get_template_part( 'template-parts/beforeenter', 'modal' );
	get_template_part( 'template-parts/beforeenter02', 'modal' );
	get_template_part( 'template-parts/enter', 'modal' );
} ?>
<script>
//Custom Js

jQuery(document).ready(function(){
	//Cart
	jQuery(".els-icon li").on("click",".woocommerce-mini-cart .remove",function(){
		jQuery('#els-shopping-cart-content-sticky .widget_shopping_cart_content').toggleClass('els-cart-popup-open');
		jQuery('#els-shopping-cart-content').toggleClass('toggle--active');
		jQuery('#els-shopping-cart-content-sticky').toggleClass('toggle--active');
		setTimeout(function(){ jQuery("#els-cart-trigger").click();jQuery('.focus-overlay').toggleClass('set--active'); }, 900);
	});
	//Favourite List
	<?php if (!is_user_logged_in()): ?>
		jQuery(".product-template-default .els-product-summary-col").on("click",".yith-wcwl-add-to-wishlist .button",function(){
			jQuery("#sisfySignPopup").modal();
			if(jQuery('.modal-backdrop').length > 0) {
				jQuery('.modal-backdrop').addClass('sisfy-modal-backdrop');
			}
			return false;
		});
		jQuery("#sisfySignPopup").on("hidden.bs.modal", function () {
			jQuery('.modal-backdrop').removeClass('sisfy-modal-backdrop');
		});
	<?php endif; ?>
	<?php
	if(is_product()){
		global $product;
		if($product->get_stock_status() == "outofstock"){
		?>	
				//Nothing
		<?php
		}else{
			?>
				if(jQuery(".woocommerce-variation-add-to-cart-disabled.soldout_disabled").length == 0){
					jQuery("#woocommerce_waitlist_wraper a.woocommerce_waitlist_new").text("<?=_e("Need notification for out of stock product ?","elsey");?>").addClass("cta link_waitlist_cta");
				}
				jQuery(".els-product-summary-col").on("change",".variations select",function () {
					if(jQuery(".woocommerce-variation-add-to-cart-disabled.soldout_disabled").length == 0){
						jQuery("#woocommerce_waitlist_wraper a.woocommerce_waitlist_new").text("<?=_e("Need notification for out of stock product ?","elsey");?>").addClass("cta link_waitlist_cta");
					}else{
						jQuery("#woocommerce_waitlist_wraper a.woocommerce_waitlist_new").text("<?=_e("Join waitlist","elsey");?>").removeClass("cta link_waitlist_cta");
					}
				});
			<?php
		}
	}
	?>
});
</script>
<div class="woofc">
	<?php echo checkGeoLocationNearStore();?>
</div>
</body>
</html>