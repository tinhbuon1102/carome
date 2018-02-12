<?php
/**
 * Initialise waitlist on the frontend product pages and load shortcode.
 */
/**
 * Exit if accesses directly
 */
defined( 'ABSPATH' ) or exit;
if ( ! class_exists( 'Pie_WCWL_Frontend_Init' ) ) {
	class Pie_WCWL_Frontend_Init {

		/**
		 * Hooks up the frontend initialisation and any functions that need to run before the 'init' hook
		 *
		 * @todo   hook user waitlist into my account tabs once it is more efficient
		 *
		 * @access public
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'frontend_init' ) );
			add_action( 'wc_quick_view_before_single_product', array( $this, 'quickview_init' ) );
			add_shortcode( 'woocommerce_my_waitlist', array( $this, 'display_users_waitlists' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue_scripts' ) );
		}

		/**
		 * Enqueue scripts and styles for the frontend if user is on a product page
		 *
		 * @access public
		 * @return void
		 * @since  1.3
		 */
		public function frontend_enqueue_scripts() {
			if ( is_shop() ) {
				wp_enqueue_script( 'wcwl_frontend', Pie_WCWL_Compatibility::plugin_url() . '/includes/js/wcwl_frontend.js', array(), '1.0.0', true );
			}
		}

		/**
		 * Check requirements and run initialise if required
		 */
		public function frontend_init() {
			if ( isset( $_REQUEST['wc-ajax'] ) && $_REQUEST['wc-ajax'] == 'get_variation' ) {
				$product = wc_get_product( $_REQUEST['product_id'] );
				$this->load_class( $product );
			} elseif ( is_product() ) {
				global $post;
				$product = wc_get_product( $post );
				if ( array_key_exists( $product->get_type(), WooCommerce_Waitlist_Plugin::$product_types ) ) {
					$this->load_class( $product );
				}
			}
		}

		/**
		 * Check requirements and run initialise if required
		 */
		public function quickview_init() {
			global $post;
			$product = wc_get_product( $post );
			if ( array_key_exists( $product->get_type(), WooCommerce_Waitlist_Plugin::$product_types ) ) {
				$this->load_class( $product );
			}
		}

		/**
		 * Load required class for product type
		 */
		private function load_class( $product ) {
			require_once 'class-pie-wcwl-frontend-product.php';
			$class = WooCommerce_Waitlist_Plugin::$product_types[ $product->get_type() ];
			require_once $class['filepath'];
			new $class['class'];
		}

		/**
		 * Output the HTML to display a list of products the current user is on the waitlist for
		 *
		 * @todo make more efficient, need user meta added to avoid the huge overhead on this. WILL NOT SCALE!
		 *
		 * @return string
		 */
		public function display_users_waitlists() {
			if ( ! is_user_logged_in() ) {
				return '';
			}
			$waitlist_products = $this->get_waitlist_products_by_user_id();
			ob_start();
			include( locate_template( 'waitlist-user.php' ) );
			$content .= ob_get_contents();
			ob_end_clean();
			wp_reset_postdata();

			return $content;
		}

		/**
		 * Wrapper for get_posts, returning all products for which the user is on the waitlist. This is currently a
		 * patchfix function to enable a user waitlist summary in the frontend. It really should be factored out in
		 * the future. Possibly change the way we store waitlists? add usermeta?
		 *
		 * @static
		 *
		 * @access public
		 * @return array    array of post objects
		 * @since  1.1.3
		 */
		private function get_waitlist_products_by_user_id() {
			$args = array(
				'type'  => array( 'simple', 'variation', 'subscription', 'subscription_variation' ),
				'limit' => '-1'
			);

			return array_filter( wc_get_products( $args ), array( __CLASS__, 'current_user_is_on_waitlist_for_product', ) );
		}

		/**
		 * Patch fix removing closure from function above
		 *
		 * @static
		 * @access public
		 *
		 * @param $product WC_Product
		 *
		 * @return bool
		 * @since  1.1.4
		 */
		public static function current_user_is_on_waitlist_for_product( $product ) {
			if ( get_post_meta( $product->get_id(), WCWL_SLUG . '_has_dates', true ) ) {
				return array_key_exists( get_current_user_id(), get_post_meta( $product->get_id(), WCWL_SLUG, true ) );
			} else {
				$userList = get_post_meta( $product->get_id(), WCWL_SLUG, true );
				$userList = $userList ? $userList : array();
				return in_array( get_current_user_id(), $userList);
			}
		}

		/**
		 * Returns the HTML for the required product in a table row ready for display on frontend
		 *
		 * @param  WC_Product $product
		 * @param  string     $content current HTML string
		 *
		 * @access public
		 * @return string          updated HTML string
		 */
		private function return_html_for_each_product( $product, $content ) {
			$content .= '<tr><td>';
			if ( has_post_thumbnail( $product->get_id() ) ) {
				$content .= apply_filters( 'wcwl_shortcode_thumbnail', get_the_post_thumbnail( $product->get_id(), 'shop_thumbnail' ), $product->get_id() );
			} else {
				$parent = $product->parent_id;
				if ( WooCommerce_Waitlist_Plugin::is_variation( $product ) && has_post_thumbnail( $parent ) ) {
					$content .= apply_filters( 'wcwl_shortcode_thumbnail', get_the_post_thumbnail( $parent, 'shop_thumbnail' ), $parent, $product );
				}
			}
			$title = apply_filters( 'wcwl_shortcode_product_title', esc_html( get_the_title( $product->get_id() ) ), $product->get_id() );
			$content .= '</td><td><a href="' . get_permalink( $product->get_id() ) . '"  >' . $title . '</a></td></tr>';

			return $content;
		}
	}
}