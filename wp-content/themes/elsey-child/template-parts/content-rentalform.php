<?php
$current_user = wp_get_current_user();
$is_user_online = false;
$first_name = $last_name = $company = $address_1 = $address_2 = $city = $post_code = $state = $phone = $email = '';
$states = WC()->countries->get_states('JP');
$aTimes = getRetailYearMonthDay();

if ( $current_user && $current_user->ID )
{
	$first_name = get_user_meta($current_user->ID, 'billing_first_name', true);
	$last_name = get_user_meta($current_user->ID, 'billing_last_name', true);
	$first_name_kana = get_user_meta($current_user->ID, 'billing_first_name_kana', true);
	$last_name_kana = get_user_meta($current_user->ID, 'billing_last_name_kana', true);
	$company = get_user_meta($current_user->ID, 'billing_company', true);
	$address_1 = get_user_meta($current_user->ID, 'billing_address_1', true);
	$address_2 = get_user_meta($current_user->ID, 'billing_address_2', true);
	$city = get_user_meta($current_user->ID, 'billing_city', true);
	$post_code = get_user_meta($current_user->ID, 'billing_postcode', true);
	$state = get_user_meta($current_user->ID, 'billing_state', true);
	$phone = get_user_meta($current_user->ID, 'billing_phone', true);
	$email = get_user_meta($current_user->ID, 'billing_email', true);
}

?>
<script type="text/javascript">
	var min_year = <?php echo (int)$aTimes['min_year']?>;
	var min_month = <?php echo (int)$aTimes['min_month']?>;
	var min_day = <?php echo (int)$aTimes['min_day']?>;
</script>
<div class="remtal_kimono_content">
<div class="row">
	<div class="els-product-image-col col-md-6 col-xs-12 images">
		<div class="product__images">
			<ul class="product__slider list--unstyled">
				<li class="product__slider-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/kimono/kimono_01.jpg" /></li>
				<li class="product__slider-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/kimono/kimono_02.jpg" /></li>
				<li class="product__slider-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/kimono/kimono_03.jpg" /></li>
			</ul>
		</div>
	</div>
	<div class="els-product-summary-col col-md-6 col-xs-12">
		<div class="summary entry-summary stick-content">
			<div class="item_headline">
				<p class="pdp__name product_title_en">Kimono Set</p>
				<h1 class="product_title entry-title ja-product-name">着物セット</h1>
				<div class="els-single-product-price price">
					<div class="els-pr-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">¥</span>50,000</span><span class="ja tax">(税抜)</span></div>
				</div>
			</div>
			<div class="els-product-stock-status"></div>
			<div class="pdp__actions product-add-to-cart">
				<div class="input-list">
					<a data-remodal-target="rental_kimono_openform" class="input-list--item single_add_to_cart_button button--primary button">レンタル申し込み</a>
				</div>
			</div>
			<div class="item_summary">
			<div class="panel_item">
				<h3>レンタル期間</h3>
				<div class="u-global-p">
					<p>要相談</p>
				</div>
			</div>
			<div class="panel_item">
				<h3>セット内容</h3>
				<div class="u-global-p">
					<p>振袖/帯/長襦袢/ショール/帯揚げ/帯締め/ひよく衿/草履/バックセット/小物セット/足袋/かんざし</p>
				</div>
			</div>
			<div class="panel_item">
				<h3>サイズ</h3>
				<table class="size-chart">
					<tbody>
						<tr>
							<th>身長</th>
							<th>身丈</th>
							<th>裄</th>
							<th>袖丈</th>
							<th>ヒップ</th>
							<th>前幅</th>
							<th>後幅</th>
						</tr>
						<tr>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="panel_item">
				<h3>素材</h3>
				<div class="u-global-p">
					<p>正絹</p>
				</div>
			</div>
		</div>
		</div>
		
		
	</div>
</div>
<div class="sticky-stopper"></div>
</div>
<!--open form--> 
<div class="remodal" data-remodal-id="rental_kimono_openform" id="rental_kimono_openform" aria-labelledby="modalTitle" aria-describedby="modalDesc"   data-remodal-options="hashTracking: false, closeOnOutsideClick: false">
	<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	<div id="retal_kimono_form_content_wraper">
		<h4 class="contact_popup_title">着物レンタル申し込み</h4>
		<form class="retal_kimono_form" method="POST">
		<div id="retal_kimono_form_content">
			<div class="form-theme">
				<div class="form-box">
			<div class="form-row row required date-wraper">
				<div class="col-xs-12">
					<label class="form-row__label required"><?php echo __('ご利用日第1希望', 'elsey')?></label>
					<div class="row">
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[year1]" class="validate[required] date-field select-year">
								<?php foreach ($aTimes['years'] as $year_index => $year) {?>
								<option value="<?php echo $year_index?>"><?php echo $year?></option>
								<?php }?>
							</select>
							</span>
						</div>
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[month1]" class="validate[required] date-field select-month">
								<?php foreach ($aTimes['months'] as $month_index => $month) {?>
								<option value="<?php echo $month_index?>"><?php echo $month?></option>
								<?php }?>
							</select>
							</span>
						</div>
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[date1]" class="validate[required] date-field select-day">
								<?php foreach ($aTimes['days'] as $day_index => $day) {?>
								<option value="<?php echo $day_index?>"><?php echo $day?></option>
								<?php }?>
							</select>
							</span>
						</div>
					</div>
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required date-wraper">
				<div class="col-xs-12">
					<label class="form-row__label required"><?php echo __('ご利用日第2希望', 'elsey')?></label>
					<div class="row">
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[year2]" class="validate[required] date-field select-year">
								<?php foreach ($aTimes['years'] as $year_index => $year) {?>
								<option value="<?php echo $year_index?>"><?php echo $year?></option>
								<?php }?>
							</select>
							</span>
						</div>
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[month2]" class="validate[required] date-field select-month">
								<?php foreach ($aTimes['months'] as $month_index => $month) {?>
								<option value="<?php echo $month_index?>"><?php echo $month?></option>
								<?php }?>
							</select>
							</span>
						</div>
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[date2]" class="validate[required] date-field select-day">
								<?php foreach ($aTimes['days'] as $day_index => $day) {?>
								<option value="<?php echo $day_index?>"><?php echo $day?></option>
								<?php }?>
							</select>
							</span>
						</div>
					</div>
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required date-wraper">
				<div class="col-xs-12">
					<label class="form-row__label required"><?php echo __('ご利用日第3希望', 'elsey')?></label>
					<div class="row">
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[year3]" class="validate[required] date-field select-year">
								<?php foreach ($aTimes['years'] as $year_index => $year) {?>
								<option value="<?php echo $year_index?>"><?php echo $year?></option>
								<?php }?>
							</select>
							</span>
						</div>
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[month3]" class="validate[required] date-field select-month">
								<?php foreach ($aTimes['months'] as $month_index => $month) {?>
								<option value="<?php echo $month_index?>"><?php echo $month?></option>
								<?php }?>
							</select>
							</span>
						</div>
						<div class="col-sm-4">
							<span class="dropdown">
								<select name="contact[date3]" class="validate[required] date-field select-day">
								<?php foreach ($aTimes['days'] as $day_index => $day) {?>
								<option value="<?php echo $day_index?>"><?php echo $day?></option>
								<?php }?>
							</select>
							</span>
						</div>
					</div>
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required">
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('Last Name', 'elsey')?></label>
					<input type="text" name="contact[last_name]" value="<?php echo $last_name?>" class="validate[required]">
				</div>
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('First Name', 'elsey')?></label>
					<input type="text" name="contact[first_name]" value="<?php echo $first_name?>" class="validate[required]">
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required">
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('姓(ふりがな)', 'elsey')?></label>
					<input type="text" name="contact[last_name_kana]" value="<?php echo $last_name_kana?>" class="validate[required]">
				</div>
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('名(ふりがな)', 'elsey')?></label>
					<input type="text" name="contact[first_name_kana]" value="<?php echo $first_name_kana?>" class="validate[required]">
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required">
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('Phone', 'woocommerce')?></label>
					<input type="tel" name="contact[tel]" value="<?php echo $phone?>" class="validate[required], custom[phone]">
				</div>
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('Email', 'elsey')?></label>
					<input type="email" name="contact[email]" value="<?php echo $email?>" class="validate[required], custom[email]">
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required">
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('ZIP', 'woocommerce')?></label>
					<input type="text" name="contact[postcode]" id="billing_postcode" value="<?php echo $post_code?>" class="validate[required]">
				</div>
				<div class="col-sm-6">
					<label class="form-row__label required"><?php echo __('Prefecture', 'woocommerce')?></label>
					<span class="dropdown">
						<select name="contact[state]" id="billing_state" class="validate[required]">
					<?php foreach ($states as $jp_state_code => $jp_state) {?>
					<option value="<?php echo $jp_state_code?>" <?php echo $state == $jp_state_code ? 'selected' : ''?>><?php echo $jp_state?></option>
					<?php }?>
				</select>
					</span>
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required">
				<div class="col-xs-12">
					<label class="form-row__label required"><?php echo __('City', 'woocommerce')?></label>
					<input type="text" name="contact[city]" value="<?php echo $city?>" id="billing_city" class="validate[required]">
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row required">
				<div class="col-xs-12">
					<label class="form-row__label required"><?php echo __('町名・番地', 'woocommerce')?></label>
					<input type="text" name="contact[address1]" value="<?php echo $address_1?>" id="billing_address_1" class="validate[required]">
				</div>
			</div>
			<!--/form-row-->
			<div class="form-row row">
				<div class="col-xs-12">
					<label class="form-row__label"><?php echo __('マンション・建物名', 'woocommerce')?></label>
					<input type="text" name="contact[address2]" value="<?php echo $address_2?>">
				</div>
			</div>
			<!--/form-row-->
		</div>
			</div>
			<div class="modal_button_wraper">
				<button type="button" class="button--primary button" id="retal_confirm_btn">Confirm</button>
				<button data-remodal-action="cancel" class="button remodal-cancel">Cancel</button>
			</div>
		</div>
		</form>
	</div>
</div>
<!--confirm-->
<div class="remodal" data-remodal-id="retal_kimono_popup" id="retal_kimono_popup" aria-labelledby="modalTitle" aria-describedby="modalDesc"   data-remodal-options="hashTracking: false, closeOnOutsideClick: false">
	<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	<div id="retal_kimono_popup_content_wraper">
		<h4 class="contact_popup_title">Confirm</h4>
		<div id="retal_kimono_popup_content">
			<div class="form-theme">
				
			</div>
			<div class="modal_button_wraper">
				  <button data-remodal-action="cancel" class="remodal-cancel">編集</button>
				  <button class="remodal-confirm submit_confirm">送信</button>
			</div>
		</div>
	</div>
</div>
<style>
.loadingoverlay {z-index: 9999999999;}
</style>