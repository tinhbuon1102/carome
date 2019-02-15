<?php
/**
 * VictorTheme Custom Changes - Added new elements, div and class
 */

/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
global $product;
global $post;

/*if ( ! $post->post_excerpt ) {
	return;
}*/

#$swatch_data = get_post_meta(get_the_ID(), 'zoo_cw_product_swatch_data', true);
#echo '<pre>';
#print_r($swatch_data);
#echo '</pre>';

$tabs = apply_filters( 'woocommerce_product_tabs', array() );

//if ( ! empty( $tabs ) ) : 

?>
<div class="o-row o-column o-extra-small--12">
	<div class="c-product-details js-product-details">
<div data-add-class="accordion" class="o-accordion js-accordion">
	<!--actual tabs-->
			<?php foreach ( $tabs as $key => $tab ) : ?>
		<div id="tab-<?php echo esc_attr( $key ); ?>" class="o-accordion__tabset" data-add-class="js_collapsible js_collapsed">
				<div data-toggle="#tab-<?php echo esc_attr( $key ); ?>">
					<div class="o-row">
						<div class="o-column o-extra-small--12">
							<a class="o-accordion__tab" href="">
								<h3 class="o-accordion__label"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></h3><span class="o-accordion__control c-product-details__collapse"></span>
							</a>
						</div>
					</div>
				</div>
				<div class="o-accordion__panel">
					<div class="o-row">
						<div class="o-column o-extra-small--12">
							<div class="u-global-p"><?php call_user_func( $tab['callback'], $key, $tab ); ?></div>
						</div>
					</div>
				</div>
			</div><!--/o-accordion__tabset-->
			<?php endforeach; ?>
	<!--manual tabs content-->
	
	<!--ACF size tab-->
	<?php
	$table = get_field( 'product_size' );
	if (! is_array($table)) {
		$table = json_decode($table, true);
		if (! isset($table['header']) && isset($table['h'])){
			$table['header'] = $table['h'];
			unset($table['h']);
		}
		if (! isset($table['body']) && isset($table['b'])){
			$table['body'] = $table['b'];
			unset($table['b']);
		}
	}
	#cho '<pre>';
	#rint_r($table);
	#cho '</pre>';
	#print_r(json_decode($table));
	if ( $table ) {
	?>
	<div id="product_size__panel" class="o-accordion__tabset" data-add-class="js_collapsible js_collapsed">
				<div data-toggle="#product_size__panel">
				<div class="o-row">
					<div class="o-column o-extra-small--12">
						<a class="o-accordion__tab" href=""><h3 class="o-accordion__label"><?php esc_html_e( 'サイズ情報', 'elsey' ); ?></h3><span class="o-accordion__control c-product-details__collapse"></span></a>
					</div>
				</div>
				</div>
				<div class="o-accordion__panel">
					<div class="o-row">
						<div class="o-column o-extra-small--12">
							
							<?php
								echo '<table class="c-table" border="0">';
								if ( $table['header'] ) {
									echo '<thead class="c-thead">';
									echo '<tr>';
									foreach ( $table['header'] as $th ) {
										echo '<th class="c-thead__th">';
										echo $th['c'];
										echo '</th>';
									}
									echo '</tr>';
									echo '</thead>';
								}
								echo '<tbody>';
								foreach ( $table['body'] as $tr ) {
									echo '<tr class="c-table__tr">';
									foreach ( $tr as $td ) {
										echo '<td class="c-table__td">';
										echo $td['c'];
										echo '</td>';
									}
									echo '</tr>';
								}
								echo '</tbody>';
								echo '</table>';
								?>
						</div>
					</div>
				</div>
			</div>
	<?php } ?>
	
	<!--description tab-->
			<div id="product_description__panel" class="o-accordion__tabset" data-add-class="js_collapsible">
				<div data-toggle="#product_description__panel">
				<div class="o-row">
					<div class="o-column o-extra-small--12">
						<a class="o-accordion__tab" href=""><h3 class="o-accordion__label"><?php esc_html_e( 'Item details', 'elsey' ); ?></h3><span class="o-accordion__control c-product-details__collapse"></span></a>
					</div>
				</div>
				</div>
				<div class="o-accordion__panel">
					<div class="o-row">
						<div class="o-column o-extra-small--12">
							<div class="u-global-p">
								<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ); ?>
								<!--素材-->
								<?php $material = get_field('product_material'); if( $material ): ?>
								<p><strong><?php esc_html_e( 'Material', 'elsey' ); ?></strong><br/><?php echo $material; ?></p>
								<?php endif; ?>
								
								<p><strong><?php esc_html_e( 'Notice', 'elsey' ); ?></strong><br/>
									<?php echo elsey_is_ja_lang() ? get_option('msg_threshold_notice') : get_option('msg_threshold_notice_en')?><?php esc_html_e( '商品画像はサンプルのため、色味やサイズ等の仕様に変更がある場合がございますので、予めご了承ください。', 'elsey' ); ?></p>
								
								<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<p><span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span></p>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<p><span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span></p>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span></p>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
	
	<!--shipping info tab-->
			<div id="product_notice__panel" class="o-accordion__tabset" data-add-class="js_collapsible js_collapsed">
				<div data-toggle="#product_notice__panel">
				<div class="o-row">
					<div class="o-column o-extra-small--12">
						<a class="o-accordion__tab" href=""><h3 class="o-accordion__label"><?php esc_html_e( 'Shipping + Returns', 'elsey' ); ?></h3><span class="o-accordion__control c-product-details__collapse"></span></a>
					</div>
				</div>
				</div>
				<div class="o-accordion__panel">
					<div class="o-row">
						<div class="o-column o-extra-small--12">
							<div class="u-global-p">
								<p><?php echo get_option('msg_threshold')?></p>
							</div>
							<table class="c-table" border="0" cellpadding="2" cellspacing="2">
								<thead class="c-thead">
									<tr class="c-thead__tr">
										<th class="c-thead__th"><?php esc_html_e( 'Method', 'elsey' ); ?></th>
										<th class="c-thead__th"><?php esc_html_e( 'Shipping Time', 'elsey' ); ?></th>
										<th class="c-thead__th"><?php esc_html_e( 'Cost', 'elsey' ); ?></th>
									</tr>
								</thead>
								<tbody>
								<?php 
									$settings = get_option( 'woocommerce_woocommerce_flatrate_percountry_settings', null );
									$count=(isset($settings['per_state_count']) ? intval($settings['per_state_count']) : 0);
									
									?>
									<tr class="c-table__tr">
										<td class="c-table__td"><?php echo $settings['world_rulename']?></td>
										<td class="c-table__td"><?php echo $settings['world_free_estimate']?></td>
										<td class="c-table__td"><?php echo $settings['fee_world']?></td>
									</tr>
									<?php 
									for($counter = 1; $count >= $counter; $counter++) {
									?>
									<!--repeat shipping method-->
									<tr class="c-table__tr">
										<td class="c-table__td"><?php echo $settings['per_state_'.$counter.'_txt']?></td>
										<td class="c-table__td"><?php echo $settings['per_state_'.$counter.'_fr_estimation_time']?></td>
										<td class="c-table__td"><?php echo $settings['per_state_'.$counter.'_fee']?></td>
									</tr>
									<!--/repeat shipping method-->
									<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	


</div>
</div>
</div>
<?php //endif; ?>
