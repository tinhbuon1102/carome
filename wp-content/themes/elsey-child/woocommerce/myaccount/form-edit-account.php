<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
	<fieldset class="form-row-wrap">
		<div class="form-row"><h3 class="form__copy heading heading--xsmall"><?php _e( '基本情報', 'woocommerce' ); ?></h3></div>
	<div class="woocommerce-form-row woocommerce-form-row--last form-row form-row-first">
		<label class="form-row__label light-copy" for="account_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</div>
	<div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-last">
		<label class="form-row__label light-copy" for="account_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</div>
	
	<div class="woocommerce-form-row woocommerce-form-row--last form-row form-row-first">
		<label class="form-row__label light-copy" for="account_last_name_kana"><?php _e( 'Last Name Kana', 'elsey' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name_kana" id="account_last_name_kana" value="<?php echo get_user_meta($user->ID, 'last_name_kana', true); ?>" />
	</div>
	<div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-last">
		<label class="form-row__label light-copy" for="account_first_name_kana"><?php _e( 'First Name Kana', 'elsey' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name_kana" id="account_first_name_kana" value="<?php echo get_user_meta($user->ID, 'first_name_kana', true); ?>" />
	</div>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label class="form-row__label light-copy" for="account_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</div>
	
	<?php 
	$aTimes = getArrayYearMonthDay();
	$user_birth_year = get_user_meta($user->ID, 'birth_year', true);
	$user_birth_month = get_user_meta($user->ID, 'birth_month', true);
	$user_birth_day = get_user_meta($user->ID, 'birth_day', true);
	?>
	<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for=birth_year class="control-label"><?php esc_html_e( 'Your Birth', 'elsey' ); ?> <span class="required">*</span></label>
		
		<div class="birth_wraper birth_year_wraper form-row-first-3 form-group">
			<select name="birth_year" id="birth_year" class="woocommerce-Select form-control" required>
			<?php foreach ($aTimes['years'] as $timeKey => $timeValue) {?>
				<option value="<?php echo $timeKey?>" <?php echo $user_birth_year == $timeKey ? 'selected' : ''?> ><?php echo $timeValue?></option>
			<?php }?>
			</select>
		</div>
		
		<div class="birth_wraper birth_month_wraper form-row-middle-3 form-group">
			<select name="birth_month" id="birth_month" class="woocommerce-Select form-control" required>
			<?php foreach ($aTimes['months'] as $timeKey => $timeValue) {?>
				<option value="<?php echo $timeKey?>" <?php echo $user_birth_month == $timeKey ? 'selected' : ''?> ><?php echo $timeValue?></option>
			<?php }?>
			</select>
		</div>
		
		<div class="birth_wraper birth_day_wraper form-row-last-3 form-group">
			<select name="birth_day" id="birth_day" class="woocommerce-Select form-control"  required>
			<?php foreach ($aTimes['days'] as $timeKey => $timeValue) {?>
				<option value="<?php echo $timeKey?>" <?php echo $user_birth_day == $timeKey ? 'selected' : ''?> ><?php echo $timeValue?></option>
			<?php }?>
			</select>
		</div>
	</div>
	</fieldset>

	<fieldset class="form-row-wrap">
		<div class="form-row"><h3 class="form__copy heading heading--xsmall"><?php _e( 'Password change', 'woocommerce' ); ?></h3></div>

		<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label class="form-row__label light-copy" for="password_current"><?php _e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" />
		</div>
		<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label class="form-row__label light-copy" for="password_1"><?php _e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" />
		</div>
		<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label class="form-row__label light-copy" for="password_2"><?php _e( 'Confirm new password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" />
		</div>
	</fieldset>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<div class="form-row">
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="submit" class="button button--primary" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" />
		<input type="hidden" name="action" value="save_account_details" />
	</div>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
