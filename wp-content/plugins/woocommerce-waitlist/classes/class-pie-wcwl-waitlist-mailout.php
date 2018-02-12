<?php
/**
 * Exit if accesses directly
 */
defined( 'ABSPATH' ) or exit;
if ( ! class_exists( 'Pie_WCWL_Waitlist_Mailout' ) ) {
	/**
	 * Waitlist Mailout
	 *
	 * An email sent to the admin when a new order is received/paid for.
	 *
	 * @class    Pie_WCWL_Waitlist_Mailout
	 * @extends  WC_Email
	 */
	class Pie_WCWL_Waitlist_Mailout extends WC_Email {

		/**
		 * Language code to send the email out in
		 *
		 * @var string
		 */
		public $language = '';

		/**
		 * Hooks up the functions for Waitlist Mailout
		 *
		 * @access public
		 */
		public function __construct() {
			// Init
			$this->wcwl_setup_mailout();
			// Triggers for this email
			add_action( 'wcwl_mailout_send_email', array( $this, 'trigger' ), 10, 2 );
			// Call parent constructor
			parent::__construct();
		}

		/**
		 * Setup required variables for mailout class
		 *
		 * @access public
		 * @return void
		 */
		public function wcwl_setup_mailout() {
			$this->id             = WCWL_SLUG . '_mailout';
			$this->title          = __( 'Waitlist Mailout', 'woocommerce-waitlist' );
			$this->description    = __( 'When a product changes from being Out-of-Stock to being In-Stock, this email is sent to all users registered on the waitlist for that product.', 'woocommerce-waitlist' );
			$this->template_base  = WooCommerce_Waitlist_Plugin::$path . 'templates/emails/';
			$this->template_html  = 'waitlist-mailout.php';
			$this->template_plain = 'plain/waitlist-mailout.php';
			if ( function_exists( 'icl_object_id' ) ) {
				$this->language = wpml_get_default_language();
			}
		}

		/**
		 * Get email subject.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_subject() {
			return __( 'A product you are waiting for is back in stock', 'woocommerce-waitlist' );
		}

		/**
		 * Get email heading.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_heading() {
			return __( '{product_title} is now back in stock at {blogname}', 'woocommerce-waitlist' );
		}

		/**
		 * Trigger function for the mailout class
		 *
		 * @param int $user_id    ID of user to send the mail to
		 * @param int $product_id ID of product that email refers to
		 *
		 * @access public
		 * @return void
		 */
		public function trigger( $user_id, $product_id ) {
			$user      = get_user_by( 'id', $user_id );
			$languages = get_user_meta( $user_id, 'wcwl_languages', true );
			global $woocommerce_wpml;
			if ( is_array( $languages ) && $woocommerce_wpml ) {
				$this->language = $languages[ $product_id ];
				$product        = wc_get_product( wpml_object_id_filter( $product_id, 'product', true, $languages[ $product_id ] ) );
			} else {
				$product = wc_get_product( $product_id );
			}
			$this->setup_required_data( $product, $user );
			$this->setup_wpml_email( $languages[ $product_id ] );
			if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
				return;
			}
			$this->send_email( $product_id, $user->ID );
		}

		/**
		 * Setup all required translations
		 *
		 * @param $wcml_emails
		 * @param $language
		 */
		private function setup_wpml_email( $language ) {
			global $woocommerce_wpml, $sitepress, $woocommerce;
			if ( $language && $woocommerce_wpml && $sitepress ) {
				$wcml_emails = new WCML_Emails( $woocommerce_wpml, $sitepress, $woocommerce );
				$wcml_emails->change_email_language( $language );
			}
		}

		/**
		 * Load generic data into the email class
		 *
		 * @param $product
		 * @param $user
		 */
		private function setup_required_data( $product, $user ) {
			$this->object                          = $product;
			$this->placeholders['{product_title}'] = $product->get_title();
			$this->placeholders['{blogname}']      = $this->get_blogname();
			$this->recipient                       = $user->user_email;
		}

		/**
		 * Send the email and store the record in the archive if required
		 *
		 * @param $product_id
		 * @param $user_id
		 */
		private function send_email( $product_id, $user_id ) {
			$result = $this->send( $this->get_recipient(), $this->format_string( $this->get_translated_string( $this->get_default_subject(), $this->language ) ), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			if ( $result && 'yes' == get_option( 'woocommerce_waitlist_archive_on' ) ) {
				$this->add_user_to_archive( $product_id, $user_id );
			}
		}

		/**
		 * Translate the given string to the given language if a translation exists
		 *
		 * @param $string
		 * @param $language_code
		 *
		 * @return false|string
		 */
		private function get_translated_string( $string, $language_code ) {
			if ( $language_code ) {
				$string_id   = icl_get_string_id( $string, 'woocommerce-waitlist' );
				$translation = icl_get_string_by_id( $string_id, $language_code );
				if ( $translation ) {
					return $translation;
				} else {
					return $string;
				}
			}
			return $string;
		}

		/**
		 * Add a user to the archive for the current product
		 * This occurs when the user has been emailed and appends their ID to the list of users emailed today
		 *
		 * @param $product_id
		 * @param $user_id
		 */
		public function add_user_to_archive( $product_id, $user_id ) {
			$existing_archives = get_post_meta( $product_id, 'wcwl_waitlist_archive', true );
			if ( ! is_array( $existing_archives ) ) {
				$existing_archives = array();
			}
			$today = strtotime( date( "Ymd" ) );
			if ( ! isset( $existing_archives[ $today ] ) ) {
				$existing_archives[ $today ] = array();
			}
			$existing_archives[ $today ][] = $user_id;
			update_post_meta( $product_id, 'wcwl_waitlist_archive', $existing_archives );
		}

		/**
		 * Returns the html string needed to create an email to send out to user
		 *
		 * @access public
		 * @return string
		 */
		public function get_content_html() {
			ob_start();
			$product_id = Pie_WCWL_Compatibility::get_product_id( $this->object );
			Pie_WCWL_Compatibility::get_template( $this->template_html, array(
				'product_title' => get_the_title( $product_id ),
				'product_link'  => get_permalink( $product_id ),
				'email_heading' => $this->format_string( $this->get_translated_string( $this->get_default_heading(), $this->language ) ),
				'product_id'    => $product_id,
			), false, $this->template_base );

			return ob_get_clean();
		}

		/**
		 * Returns the plain text needed to create an email to send out to user
		 *
		 * @access public
		 * @return string
		 */
		public function get_content_plain() {
			ob_start();
			$product_id = Pie_WCWL_Compatibility::get_product_id( $this->object );
			Pie_WCWL_Compatibility::get_template( $this->template_plain, array(
				'product_title' => get_the_title( $product_id ),
				'product_link'  => get_permalink( $product_id ),
				'email_heading' => $this->format_string( $this->get_translated_string( $this->get_default_heading(), $this->language ) ),
				'product_id'    => $product_id,
			), false, $this->template_base );

			return ob_get_clean();
		}
	}
}
return new Pie_WCWL_Waitlist_Mailout();