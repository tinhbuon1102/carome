<?php
$sku = 'CSMEL01'; // SKU example to be replaced by the real SKU of the product
$product_id = wc_get_product_id_by_sku( $sku );
$link = get_permalink( $product_id );
$tickets = new WC_Product_Variable( $product_id);
$variables = $tickets->get_available_variations();
$black_stock = '';
$brown_stock = '';
$burgundy_stock = '';
foreach ($variables as $variation)  {
    if($variation['attributes']['attribute_pa_color']=='black'){
    	$black_stock = $variation['max_qty'];
    }
    if($variation['attributes']['attribute_pa_color']=='brown'){
    	$brown_stock = $variation['max_qty'];
    }
    if($variation['attributes']['attribute_pa_color']=='burgundy'){
    	$burgundy_stock = $variation['max_qty'];
    }
}
?>
<div class="buy_now_flw">
	<ul id="slider" class="flw_slider">
		<li class="color_blk">
			<div class="row">
				<div class="buy-item col-flw">
					<img src="https://test.carome.net/wp-content/uploads/2019/03/CSMEL01_0005_BLK-01.jpg">
				</div>
				<div class="buy-meta col-flw">
					<div class="price-item">¥1,400</div>
					<div class="meta-item">
						<span class="att_t">Color</span>
						<span class="att_v">Black</span>
					</div>
				</div>
				<div class="buy-btn col-flw">
					<a href="<?php echo $link ?>" class="btn main-buynow-btn flw_btn <?php if($black_stock>0){ ?>active_link<?php } else { ?>disable_link<?php } ?>"><?php if($black_stock>0){ ?>BUY NOW<?php } else { ?>SOLD OUT<?php } ?></a>
				</div>
			</div>
		</li>
		<li class="color_brn">
			<div class="row">
				<div class="buy-item col-flw">
					<img src="https://test.carome.net/wp-content/uploads/2019/03/CSMEL01_0003_BRN-01.jpg">
				</div>
				<div class="buy-meta col-flw">
					<div class="price-item">¥1,400</div>
					<div class="meta-item">
						<span class="att_t">Color</span>
						<span class="att_v">Brown</span>
					</div>
				</div>
				<div class="buy-btn col-flw">
					<a href="<?php echo $link ?>" class="btn main-buynow-btn flw_btn <?php if($brown_stock>0){ ?>active_link<?php } else { ?>disable_link<?php } ?>"><?php if($brown_stock>0){ ?>BUY NOW<?php } else { ?>SOLD OUT<?php } ?></a>
				</div>
			</div>
		</li>
		<li class="color_bgd">
			<div class="row">
				<div class="buy-item col-flw">
					<img src="https://test.carome.net/wp-content/uploads/2019/03/CSMEL01_0001_BGD-01.jpg">
				</div>
				<div class="buy-meta col-flw">
					<div class="price-item">¥1,400</div>
					<div class="meta-item">
						<span class="att_t">Color</span>
						<span class="att_v">Burgundy</span>
					</div>
				</div>
				<div class="buy-btn col-flw">
					<a href="<?php echo $link ?>" class="btn main-buynow-btn flw_btn <?php if($burgundy_stock>0){ ?>active_link<?php } else { ?>disable_link<?php } ?>"><?php if($burgundy_stock>0){ ?>BUY NOW<?php } else { ?>SOLD OUT<?php } ?></a>
				</div>
			</div>
		</li>
	</ul>
</div>