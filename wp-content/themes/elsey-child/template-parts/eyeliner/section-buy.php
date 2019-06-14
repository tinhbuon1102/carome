<?php
$sku = 'CSMEL01'; // SKU example to be replaced by the real SKU of the product
$product_id = wc_get_product_id_by_sku( $sku );
$link = get_permalink( $product_id );
$tickets = new WC_Product_Variable( $product_id);
$variables = $tickets->get_available_variations();
$var_data = '';
foreach ($variables as $variation)  {
	$var_data += ($variation['max_qty'] ? $variation['max_qty'] : 0);
}
?>
<section class="buy bg_black">
	<div class="eye_container">
		<div class="buynow-content">
			<h3 class="sec-title kaku_gothic_font bold">メイクを楽しむ<br class="xs-show">全ての女性へ</h3>
			<p class="buy-catch tsukushi_font bold"><span class="quote_start">“</span>1日中魅力的な瞳へ<br/>ウォータプルーフ <br class="xs-show">リキッドアイライナー<span class="quote_end">”</span></p>
			<div class="buy-image"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/eyeliner/buy-item.png" /></div>
			<a href="<?php echo $link ?>" class="buy_now_btn <?php if($var_data>0){ ?>active_link<?php } else { ?>disable_link<?php } ?>"><?php if($var_data>0){ ?>BUY NOW<?php } else { ?>SOLD OUT<?php } ?></a>
		</div>
	</div>
</section>