<?php
/**
 * Exit if accesses directly
*/
defined( 'ABSPATH' ) or exit;
if ( ! class_exists( 'Pie_WCWL_Frontend_Variable' ) ) {
	/**
	 * Loads up the waitlist for variable products
	 *
	 * @package  WooCommerce Waitlist
	 */
	class Pie_WCWL_Frontend_Variable extends Pie_WCWL_Frontend_Product {

		/**
		 * Pie_WCWL_Frontend_Variable constructor.
		 */
		public function __construct() {
			parent::__construct();
			$this->init();
		}

		/**
		 * Load up hooks if product is out of stock
		 */
		private function init() {
			if ( ! isset( $_POST['add-to-cart'] ) && $this->user_modified_waitlist ) {
				$this->process_waitlist_update();
			}
			$this->output_waitlist_elements();
		}

		/**
		 * If the user has attempted to modify the waitlist process the request
		 */
		private function process_waitlist_update() {
			$waitlist = $this->get_child_waitlist( wc_get_product( $_REQUEST[ WCWL_SLUG ] ) );
			if ( ! $this->user ) {
				$this->handle_waitlist_when_new_user( $waitlist );
			} else {
				$this->toggle_waitlist_action( $waitlist );
			}
		}

		/**
		 * Check version of WC and output waitlist elements on appropriate hooks
		 */
		private function output_waitlist_elements() {
			add_filter( 'woocommerce_get_availability', array( $this, 'append_waitlist_message' ), 20, 2 );
			add_action( 'woocommerce_get_availability', array( $this, 'append_waitlist_control', ), 21, 2 );
			if ( ! $this->user ) {
				if ( Pie_WCWL_Compatibility::wc_is_at_least_3_0() ) {
					add_filter( 'woocommerce_get_stock_html', array( $this, 'append_waitlist_control_if_user_unknown' ), 20 );
				} else {
					add_filter( 'woocommerce_stock_html', array( $this, 'append_waitlist_control_if_user_unknown' ), 20 );
				}
			}
		}

		/**
		 * Appends the waitlist button HTML to text string
		 *
		 * @hooked   filter woocommerce_stock_html
		 *
		 * @param $array
		 * @param $product
		 *
		 * @return string HTML with waitlist button appended if product is out of stock
		 *
		 * @access   public
		 * @since    1.0
		 */
		public function append_waitlist_control( $array, $product ) {
			if ( $this->has_wpml ) {
				$product = wc_get_product( $this->get_main_product_id( $product->get_id() ) );
			}
			if ( ! $product->is_in_stock() ) {
				$waitlist = $this->get_child_waitlist( $product );
				if ( isset( $waitlist ) && ! $waitlist->user_is_registered( $this->user ) ) {
					$array['availability'] .= '<div>' . $this->get_waitlist_control( 'join', $product ) . '</div>';
				} else {
					$array['availability'] .= '<div>' . $this->get_waitlist_control( 'leave', $product ) . '</div>';
				}
			}

			return $array;
		}

		/**
		 * This function modifies the string in place of the 'add to cart' option, adding in an email field when the user
		 * is not logged in.
		 *
		 * @param string $string current waitlist string
		 *
		 * @access public
		 * @return string $string modified string
		 *
		 * @since  1.3
		 */
		public function append_waitlist_control_if_user_unknown( $string ) {
			if ( ! WooCommerce_Waitlist_Plugin::users_must_be_logged_in_to_join_waitlist() ) {
				$string = str_replace( '<div>', '</p>' . $this->get_waitlist_email_field(), $string );
			}

			return $string;
		}

		/**
		 * Get HTML for waitlist elements depending on product type
		 *
		 * @param string $context the context in which the button should be generated (join|leave)
		 * @param        $product
		 *
		 * @return string HTML for join waitlist button
		 * @access public
		 * @since  1.0
		 */
		private function get_waitlist_control( $context, $child ) {
			$child_id    = Pie_WCWL_Compatibility::get_product_id( $child );
			$text_string = $context . '_waitlist_button_text';
			$classes     = implode( ' ', apply_filters( 'wcwl_' . $context . '_waitlist_button_classes', array( 'button', 'alt', WCWL_SLUG, $context, ) ) );
			$text        = apply_filters( 'wcwl_' . $context . '_waitlist_button_text', $this->$text_string );
			$url         = $this->create_button_url( $context, $child_id );

			return apply_filters( 'wcwl_' . $context . '_waitlist_button_html', '<div class="wcwl_control"><a href="' . esc_url( $url ) . '" class="' . esc_attr( $classes ) . '" data-id="' . $child_id . '" id="wcwl-product-' . esc_attr( $child_id ) . '">' . esc_html( $text ) . '</a></div>' );
		}

		/**
		 * Get URL to toggle waitlist status
		 *
		 * @param string $action
		 *
		 * @return string
		 * @access private
		 */
		private function create_button_url( $action = '', $child_id ) {
			$url = add_query_arg( WCWL_SLUG, $child_id, get_permalink( $child_id ) );
			$url = add_query_arg( WCWL_SLUG . '_action', $action, $url );
			$url = add_query_arg( WCWL_SLUG . '_nonce', wp_create_nonce( __FILE__ ), $url );

			return apply_filters( 'wcwl_toggle_waitlist_url', $url );
		}

		/**
		 * Checks whether product is in stock and if not, appends the waitlist message of 'join/leave waitlist' to the 'out
		 * of stock' message
		 *
		 * @param array  $array   stock details
		 * @param object $product the current product
		 *
		 * @access public
		 * @return array
		 * @since  1.4.12
		 */
		public function append_waitlist_message( $array, $child ) {
			if ( $this->has_wpml ) {
				$child    = wc_get_product( $this->get_main_product_id( $child->get_id() ) );
			}
			if ( ! $child->is_in_stock() ) {
				$waitlist = $this->get_child_waitlist( $child );
				if ( $waitlist ) {
					if ( ! $this->user || ! $waitlist->user_is_registered( $this->user ) ) {
						$array['availability'] .= apply_filters( 'wcwl_join_waitlist_message_text', ' - ' . $this->join_waitlist_message_text );
					} else {
						$array['availability'] .= apply_filters( 'wcwl_leave_waitlist_message_text', ' - ' . $this->leave_waitlist_message_text );
					}
				}
			}

			return $array;
		}
	}
}