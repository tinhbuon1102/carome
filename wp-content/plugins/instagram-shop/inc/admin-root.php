<?php
class Instashop_Admin_Root extends Instashop_Admin_Base {
	private static $instance;
	private static $text_domain;
	private function __construct(){}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}


	public function instashop_admin_menu() {
		$param = $this->get_auth_params();
		$option_name = 'instashop_auth';
		$nonce_key = Instashop::OPTION_NAME;
		$insta_related_products = get_option('insta_related_products');
		$insta_related_products = $insta_related_products ? $insta_related_products : array();
		
		echo "<div class='wrap'>";
		echo '<script type="text/javascript">
			var LIMIT_INSTA_IMAGE = '. LIMIT_INSTA_IMAGE .';
			var INSTA_ID = "'. INSTA_ID .'";
			var MY_SITE_URL = "'. site_url() .'";
			var message_no_result = "'. __( 'No items found' , self::$text_domain ) .'";
			var insta_related_products = '. json_encode($insta_related_products) .';
		</script>';
		echo '<h2>'. __( 'Instashop Settings' , self::$text_domain ). '</h2>';
		echo '<div>Display in Frontend Use Shortcode : [instagram_shop count=33]</div>';
		echo '</div>';
		do_action( 'instashop_add_setting_before' );
		echo $this->get_instagram_list_settings();
		do_action( 'instashop_add_setting_after' );
	}

	public function get_instagram_list_settings() {
	?>
		<hr />
		<form method="post" id="form_setting_insta_shop">
			<div class="input_wraper">
				<label><?php echo __('Instagram Name', self::$text_domain)?></label>
				<input id="insta_shop_id" name="insta_shop_id" value="<?php echo get_option('insta_shop_id')?>" placeholder="<?php echo __( 'Input your instagram shop ID' , self::$text_domain )?>"/>
			</div>
			<div class="button_wraper">
				<button type="submit"><?php echo __('Save', self::$text_domain)?></button>
			</div>
		</form>
		
		<hr />
		
		<ul id="instagram_list_admin"></ul>
		<button id="instagram_list_more" style="display: none;"><?php echo __( 'Load More' , self::$text_domain )?></button>
	
		<div class="remodal" id="modal_instagram" data-remodal-id="modal_instagram" role="dialog" data-remodal-options='{ "hashTracking": false }' aria-labelledby="modal1Title" aria-describedby="modal1Desc">
			<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
			<div id="modal_insta_content_wraper">
				<h1><?php echo __( 'Edit Related Items' , self::$text_domain )?></h1>
				<div id="modal_insta_content">
					<div class="insta-left"></div>
					<div class="insta-right">
						<form id="insta_content_form" method="post">
							<input type="hidden" name="insta_code" value=""/>
							<label><?php echo __( 'Related Items' , self::$text_domain )?></label>
							<div class="typeahead__container"> 
								<div class="typeahead__field"> 
									<span class="typeahead__query">
										<input type="text" autocomplete="off" type="search" class="form-control Typeahead-input" placeholder="<?php echo __( 'Add items\'s Sku here' , self::$text_domain )?>" name="item_sku" id="item_sku"/>
										<input type="hidden" name="product_id" id="product_id" value=""/>
										<input type="hidden" name="insta_id" id="insta_id" value=""/>
									</span>
								</div>
							</div>
							<div class="button_wraper">
								<button type="button" id="btn_add_related_product" class="remodal-confirm"><?php echo __( 'Add' , self::$text_domain )?></button>
							</div>
						</form>
						<div id="related_items_label"><?php echo __( 'Current Related Items' , self::$text_domain )?></div>
						<ul id="related_items_list"></ul>
					</div>
				</div>
			</div>
		</div>
<?php
	}
}
