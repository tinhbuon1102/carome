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
<section class="eyeproducts bg_black">
	<div class="eye_container full_eye_container">
		<div class="row eye-item-row">
			<!---Black--->
			<div class="col-md-4 col-xs-12 eye-black">
				<div class="eye-item-inner" data-aos="fade-up" data-aos-delay="200" data-aos-duration="350">
					<div class="eye-item">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/images/eyeliner/eyeliner_blk.png" />
					</div>
					<div class="item-info">
					<h4 class="color_name">Black</h4>
					<p>くっきり引き締まるブラック</p>
					</div>
				</div>
				<div class="grid-row eye-detail-row">
					<div class="col-view-img">
						<div class="fit-image"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/eyeliner/eyeview_black_new.jpg" class="obj-fit-image" /></div>
					</div>
					<div class="col-price-buy">
						<div class="grid-row price-buy-row no-pad-row">
							<div class="col-price">
								<div class="col-price-inner">
									<div class="eye-price">&yen;1,400</div>
									<div class="eye-color">カラー/<span class="color-ja">ブラック</span></div>
								</div>
							</div>
							<div class="col-buy">
								<a href="<?php echo $link ?>" class="eye-buy-button <?php if($black_stock>0){ ?>active_link<?php } else { ?>disable_link<?php } ?>"><span class="buy-btn-center"><?php if($black_stock>0){ ?>Buy<br/>Now<?php } else { ?>Sold<br/>Out<?php } ?></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!---Brown--->
			<div class="col-md-4 col-xs-12 eye-brown">
				<div class="eye-item-inner" data-aos="fade-up" data-aos-delay="300" data-aos-duration="450">
					<div class="eye-item">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/images/eyeliner/eyeliner_brn.png" />
					</div>
					<div class="item-info">
					<h4 class="color_name">Brown</h4>
					<p>目元に深みを与えるブラウン</p>
					</div>
				</div>
				<div class="grid-row eye-detail-row">
					<div class="col-view-img">
						<div class="fit-image"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/eyeliner/eyeview_brown_new.jpg" class="obj-fit-image" /></div>
					</div>
					<div class="col-price-buy">
						<div class="grid-row price-buy-row no-pad-row">
							<div class="col-price">
								<div class="col-price-inner">
									<div class="eye-price">&yen;1,400</div>
									<div class="eye-color">カラー/<span class="color-ja">ブラウン</span></div>
								</div>
							</div>
							<div class="col-buy">
								<a href="<?php echo $link ?>" class="eye-buy-button <?php if($brown_stock>0){ ?>active_link<?php } else { ?>disable_link<?php } ?>"><span class="buy-btn-center"><?php if($brown_stock>0){ ?>Buy<br/>Now<?php } else { ?>Sold<br/>Out<?php } ?></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!---Burgundy--->
			<div class="col-md-4 col-xs-12 eye-burgundy">
				<div class="eye-item-inner" data-aos="fade-up" data-aos-delay="400" data-aos-duration="550">
					<div class="eye-item">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/images/eyeliner/eyeliner_brg.png" />
					</div>
					<div class="item-info">
					<h4 class="color_name">Burgundy</h4>
					<p>上品で印象的なピンクバーガンディ</p>
					</div>
				</div>
				<div class="grid-row eye-detail-row">
					<div class="col-view-img">
						<div class="fit-image"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/eyeliner/eyeview_burgundy_new.jpg" class="obj-fit-image" /></div>
					</div>
					<div class="col-price-buy">
						<div class="grid-row price-buy-row no-pad-row">
							<div class="col-price">
								<div class="col-price-inner">
									<div class="eye-price">&yen;1,400</div>
									<div class="eye-color">カラー/<span class="color-ja letterspace-narrow">バーガンディ</span></div>
								</div>
							</div>
							<div class="col-buy">
								<a href="<?php echo $link ?>" class="eye-buy-button <?php if($burgundy_stock>0){ ?>active_link<?php } else { ?>disable_link<?php } ?>"><span class="buy-btn-center"><?php if($burgundy_stock>0){ ?>Buy<br/>Now<?php } else { ?>Sold<br/>Out<?php } ?></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>