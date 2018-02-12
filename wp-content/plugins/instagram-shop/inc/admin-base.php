<?php
class Instashop_Admin_Base {
	private static $instance;

	private static $text_domain;

	const MENU_ID = 'instashop-admin-menu';
	const FONT_THEME = 'instashop_fonttheme';
	const MESSAGE_KEY = 'instashop-admin-errors';
	const MENU_FONTTHEME = 'instashop-admin-fonttheme';
	const MENU_FONTGEN = 'instashop-admin-fontgen';
	const FONT_THEME_MAX = 10;
	private function __construct(){}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	public function get_auth_params() {
		$auth	= Instashop_Auth::get_instance();
		$param = $auth->get_auth_params();
		return $param;
	}

	public function set_error_messages( $wp_error ) {
		$msg[] = $wp_error->get_error_message();
		set_transient( self::MESSAGE_KEY , $msg, 10 );
	}

	public function instashop_admin_notices() {
		$messages = get_transient( self::MESSAGE_KEY );
		if ( ! $messages ) {
			return;
		}
	?>
		<div class="error">
			<ul>
				<?php foreach ( $messages as $message ) : ?>
					<li><?php echo esc_html( $message );?></li>
				<?php endforeach;?>
			</ul>
		</div>
	<?php
	}
}
