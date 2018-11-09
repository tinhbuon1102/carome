<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>


<div class="auth__container set-division max-width--med-tab">
<div class="login-registe__row" id="customer_login"> <!-- custom - row class added -->
	<ul class="tabs">
	 <li><a href="#login-tab"><?php esc_html_e( 'Login', 'elsey' ); ?></a></li>
	 <li><a href="#reg-tab"><?php esc_html_e( 'New Registration', 'elsey' ); ?></a></li>
	</ul>
	<div class="tab_container custom_tab_container">
		<div id="login-tab" class="tab_content">
			<div class="col-xs-12 auth__section login_col">
		<h2 class="heading-ja heading heading--small"><?php esc_html_e( 'Login', 'elsey' ); ?></h2>
		<p class="form__description p4">会員登録がお済みの方は以下よりログイン下さい。</p>

		<form class="woocomerce-form woocommerce-form-login login auth__form" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
				<label for="username" class="control-label"><?php esc_html_e( 'User Name or Email', 'elsey' ); ?> <span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" required />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
				<label for="password" class="control-label"><?php esc_html_e( 'Password', 'elsey' ); ?> <span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text form-control" type="password" name="password" id="password" required />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<!-- <div class="els-login-lost-pass"> custom - div added -->
				<p class="form-row label-inline login-rememberme label-inline form-indent">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> 
						<label for="rememberme"><?php esc_html_e( 'Remember me', 'elsey' ); ?></label>
					</label>
				</p>
			<div class="form-row form-row-button">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<input type="submit" class="woocommerce-Button button button--full" name="login" value="<?php esc_attr_e( 'Login', 'elsey' ); ?>" />
			</div>
				<div class="align--center">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="cta"><?php esc_html_e( 'Lost your password?', 'elsey' ); ?></a>
				</div>
			<!--</div>  custom - div end -->

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>



	</div><!--/auth__section-->
		</div><!--/tab_content-->
		<div id="reg-tab" class="tab_content">
			<div class="col-xs-12 auth__section reg_col">
		<div id="toggleReg" class="register-container">
		<h2 class="heading-ja heading heading--small"><?php esc_html_e( 'New Registration', 'elsey' ); ?></h2>
		<p class="form__description p4">本サイトでのご注文、ご利用には会員登録(無料)が必要です。お届け先がご登録できるほか、ご注文履歴や配送情報が確認できます。</p>

		<form method="post" class="register auth__form">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
					<label for="reg_username" class="control-label"><?php esc_html_e( 'User Name', 'elsey' ); ?> <span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" required />
				</p>

			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
				<label for="reg_email" class="control-label"><?php esc_html_e( 'Email Address', 'elsey' ); ?> <span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" required />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
					<label for="reg_password" class="control-label"><?php esc_html_e( 'Password', 'elsey' ); ?> <span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="password" id="reg_password" required />
				</p>

			<?php endif; ?>

			<!-- Spam Trap -->
			<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php esc_html_e( 'Anti-spam', 'elsey' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" autocomplete="off" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<div class="form-row form-row-button">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<input type="submit" class="woocommerce-Button button button--full" name="register" value="<?php esc_attr_e( 'Register', 'elsey' ); ?>" />
			</div>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>
</div><!--/register-container-->
	</div><!--/auth__section-->
		</div><!--/tab_content-->
	</div><!--/custom_tab_container--->
	<div class="col-xs-12 enter-guest">
		<a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>" class="button button-primary button-goshop"><?php esc_html_e( 'ショッピングページへ移動', 'elsey' ); ?></a>
	</div>
</div><!--/login-registe__row-->
</div><!--/auth__container-->


<?php do_action( 'woocommerce_after_customer_login_form' ); ?>