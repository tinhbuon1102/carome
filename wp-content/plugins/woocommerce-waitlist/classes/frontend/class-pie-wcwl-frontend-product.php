<?php
/**
 * Exit if accesses directly
 */
defined( 'ABSPATH' ) or exit;
if ( ! class_exists( 'Pie_WCWL_Frontend_Product' ) ) {
	/**
	 * Abstract class for all frontend classes to load from
	 *
	 * @package  WooCommerce Waitlist
	 */
	abstract class Pie_WCWL_Frontend_Product {

		/**
		 * Current product object
		 *
		 * @var WC_Product
		 */
		protected $product;
		/**
		 * Child products of current parent product
		 *
		 * @var array
		 */
		protected $children = array();
		/**
		 * Current user object
		 *
		 * @var object
		 */
		protected $user;
		/**
		 * Has the user just requested to update the waitlist
		 *
		 * @var bool
		 */
		protected $user_modified_waitlist = false;
		/**
		 * Is WPML installed and active?
		 *
		 * @var bool
		 */
		public $has_wpml = false;

		/**
		 * Pie_WCWL_Frontend_Product constructor.
		 *
		 * Notices are cleared on shutdown to ensure waitlist notices don't persist to cart
		 */
		public function __construct() {
			$this->user                   = is_user_logged_in() ? wp_get_current_user() : false;
			$this->user_modified_waitlist = $this->user_has_altered_waitlist();
			$this->has_wpml               = function_exists('icl_object_id');
			$this->setup_waitlist();
			$this->setup_text_strings();
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue_scripts' ) );
			add_filter( 'woocommerce_add_to_cart_url', array( $this, 'remove_waitlist_parameters_from_query_string' ) );
		}

		/**
		 * Enqueue scripts and styles for the frontend if user is on a product page
		 *
		 * @access public
		 * @return void
		 * @since  1.3
		 */
		public function frontend_enqueue_scripts() {
			wp_enqueue_script( 'wcwl_frontend', Pie_WCWL_Compatibility::plugin_url() . '/includes/js/wcwl_frontend.js', array(), '1.0.0', true );
			wp_enqueue_style( 'wcwl_frontend', Pie_WCWL_Compatibility::plugin_url() . '/includes/css/wcwl_frontend.css' );
		}

		/**
		 * Setup required variables for the frontend UI
		 *
		 * @access public
		 * @return void
		 * @since  1.3
		 */
		private function setup_waitlist() {
			global $post;
			if ( isset( $_REQUEST['wc-ajax'] ) && $_REQUEST['wc-ajax'] == 'get_variation' ) {
				if ( $this->has_wpml ) {
					$product_id = $this->get_main_product_id( $_REQUEST['product_id'] );
				} else {
					$product_id = $_REQUEST['product_id'];
				}
				$this->product = wc_get_product( $product_id );
			} else {
				if ( $this->has_wpml ) {
					$product_id = $this->get_main_product_id( $post->ID );
				} else {
					$product_id = $post->ID;
				}
				$this->product = wc_get_product( $product_id );
			}
			if ( $this->product ) {
				if ( $this->product->get_children() ) {
					$this->setup_child_waitlists();
				} else {
					$this->product->waitlist = $this->get_waitlist( $this->product );
				}
			}
		}

		/**
		 * Setup child product waitlists for parent product
		 */
		private function setup_child_waitlists() {
			$children = array();
			foreach ( $this->product->get_children() as $child_id ) {
				$child                 = wc_get_product( $child_id );
				$child->waitlist       = $this->get_waitlist( $child );
				$children[ $child_id ] = $child;
			}
			$this->children = $children;
		}

		/**
		 * Return the waitlist for the given product
		 *
		 * @param $product
		 *
		 * @return mixed
		 */
		protected function get_waitlist( $product ) {
			if ( isset( $this->children[ Pie_WCWL_Compatibility::get_product_id( $product ) ]->waitlist ) ) {
				return $this->children[ Pie_WCWL_Compatibility::get_product_id( $product ) ]->waitlist;
			}

			return new Pie_WCWL_Waitlist( $product );
		}

		/**
		 * Return the waitlist for the given child product
		 *
		 * @param $child
		 *
		 * @return mixed
		 */
		protected function get_child_waitlist( $child ) {
			$child_id = Pie_WCWL_Compatibility::get_product_id( $child );

			return isset($this->children[ $child_id ]) ? $this->children[ $child_id ]->waitlist : null;
		}

		/**
		 * Checks to see if request to adjust waitlist is valid for user
		 *
		 * @access private
		 * @return boolean true if valid, false if not
		 * @since  1.3
		 */
		private function user_has_altered_waitlist() {
			if ( isset( $_REQUEST[ WCWL_SLUG ] ) && is_numeric( $_REQUEST[ WCWL_SLUG ] ) && ! isset( $_REQUEST['added-to-cart'] ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Check if current user is able to use the waitlist functionality and output error if not
		 *
		 * @return bool
		 */
		protected function check_if_new_user_can_use_waitlist() {
			if ( 'yes' == get_option( 'woocommerce_waitlist_registration_needed' ) ) {
				Pie_WCWL_Compatibility::add_notice( apply_filters( 'wcwl_join_waitlist_user_requires_registration_message_text', $this->users_must_register_and_login_message_text ), 'error' );

				return false;
			} elseif ( ! isset( $_REQUEST['wcwl_email'] ) || ! is_email( $_REQUEST['wcwl_email'] ) ) {
				Pie_WCWL_Compatibility::add_notice( apply_filters( 'wcwl_join_waitlist_invalid_email_message_text', $this->join_waitlist_invalid_email_message_text ), 'error' );

				return false;
			} elseif ( $this->product->is_type( 'grouped' ) && empty( $_REQUEST['wcwl_join'] ) ) {
				Pie_WCWL_Compatibility::add_notice( apply_filters( 'wcwl_toggle_waitlist_no_product_message_text', $this->toggle_waitlist_no_product_message_text ), 'error' );

				return false;
			} else {
				$this->setup_new_user( $_REQUEST['wcwl_email'] );

				return true;
			}
		}

		/**
		 * Find existing user by email given or create a new user if required
		 *
		 * @param $email
		 */
		private function setup_new_user( $email ) {
			if ( email_exists( $email ) ) {
				$this->user = get_user_by( 'email', $email );
			} else {
				$this->user = get_user_by( 'id', WooCommerce_Waitlist_Plugin::create_new_customer_from_email( $email ) );
			}
		}

		/**
		 * Handle functionality for logged out user joining/leaving a waitlist
		 *
		 * Reset user to false as creating new users can cause issues
		 *
		 * @param $waitlist object Waitlist object that needs updating
		 */
		protected function handle_waitlist_when_new_user( $waitlist ) {
			if ( $this->check_if_new_user_can_use_waitlist() ) {
				if ( ! $waitlist->register_user( $this->user ) ) {
					Pie_WCWL_Compatibility::add_notice( apply_filters( 'wcwl_leave_waitlist_message_text', $this->leave_waitlist_message_text ) );
				} else {
					Pie_WCWL_Compatibility::add_notice( apply_filters( 'wcwl_join_waitlist_success_message_text', $this->join_waitlist_success_message_text ) );
				}
			}
			$this->user = false;
		}

		/**
		 * Process the waitlist request
		 *
		 * @param $waitlist object Waitlist object that needs updating
		 */
		protected function toggle_waitlist_action( $waitlist ) {
			if ( isset( $_POST['add-to-cart'] ) ) {
				return;
			}
			if ( $_GET[ WCWL_SLUG . '_action' ] == 'leave' && $waitlist->user_is_registered( $this->user ) && $waitlist->unregister_user( $this->user ) ) {
				Pie_WCWL_Compatibility::add_notice( apply_filters( 'wcwl_leave_waitlist_success_message_text', $this->leave_waitlist_success_message_text ) );
			}
			if ( $_GET[ WCWL_SLUG . '_action' ] == 'join' && ! $waitlist->user_is_registered( $this->user ) && $waitlist->register_user( $this->user ) ) {
				Pie_WCWL_Compatibility::add_notice( apply_filters( 'wcwl_join_waitlist_success_message_text', $this->join_waitlist_success_message_text ) );
			}
		}

		/**
		 * Return the main product for the given translated product ID
		 *
		 * @param $product_id
		 *
		 * @return int|NULL
		 */
		public function get_main_product_id( $product_id ) {
			return wpml_object_id_filter( $product_id, 'product', true, wpml_get_default_language() );
		}

		/**
		 * Get HTML for waitlist email
		 *
		 * @access public
		 * @return  string
		 * @since  1.3
		 */
		protected function get_waitlist_email_field() {
			return '<div class="wcwl_email_field">
					<label for="wcwl_email">' . $this->email_field_placeholder_text . '</label>
					<input type="email" name="wcwl_email" id="wcwl_email" required />
				</div>';
		}

		/**
		 * Removes waitlist parameters from query string
		 *
		 * @access public
		 *
		 * @param  string $query_string current query
		 *
		 * @return string               updated query
		 */
		public function remove_waitlist_parameters_from_query_string( $query_string ) {
			return esc_url( remove_query_arg( array(
				'woocommerce_waitlist',
				'woocommerce_waitlist_nonce',
				'wcwl_email',
				'wcwl_join',
				'wcwl_leave',
			), $query_string ) );
		}

		/**
		 * Sets up the text strings used by the plugin in the front end
		 *
		 * @hooked action plugins_loaded
		 * @access private
		 * @return void
		 * @since  1.0
		 */
		private function setup_text_strings() {
			$this->join_waitlist_button_text                    = __( 'Join waitlist', 'woocommerce-waitlist' );
			$this->dummy_waitlist_button_text                   = __( 'Join waitlist', 'woocommerce-waitlist' );
			$this->leave_waitlist_button_text                   = __( 'Leave waitlist', 'woocommerce-waitlist' );
			$this->update_waitlist_button_text                  = __( 'Update waitlist', 'woocommerce-waitlist' );
			$this->join_waitlist_message_text                   = __( "Join the waitlist to be emailed when this product becomes available", 'woocommerce-waitlist' );
			$this->leave_waitlist_message_text                  = __( 'You are on the waitlist for this product', 'woocommerce-waitlist' );
			$this->leave_waitlist_success_message_text          = __( 'You have been removed from the waitlist for this product', 'woocommerce-waitlist' );
			$this->join_waitlist_success_message_text           = __( 'You have been added to the waitlist for this product', 'woocommerce-waitlist' );
			$this->update_waitlist_success_message_text         = __( 'You have updated your waitlist for these products', 'woocommerce-waitlist' );
			$this->toggle_waitlist_no_product_message_text      = __( 'You must select at least one product for which to update the waitlist', 'woocommerce-waitlist' );
			$this->toggle_waitlist_ambiguous_error_message_text = __( 'Something seems to have gone awry. Are you trying to mess with the fabric of the universe?', 'woocommerce-waitlist' );
			$this->join_waitlist_invalid_email_message_text     = __( 'You must provide a valid email address to join the waitlist for this product', 'woocommerce-waitlist' );
			$this->users_must_register_and_login_message_text   = sprintf( __( 'You must register to use the waitlist feature. Please %slogin or create an account%s', 'woocommerce-waitlist' ), '<a href="' . wc_get_page_permalink( 'myaccount' ) . '">', '</a>' );
			$this->grouped_product_message_text                 = __( "Check the box alongside any Out of Stock products and update the waitlist to be emailed when those products become available", 'woocommerce-waitlist' );
			$this->no_user_grouped_product_message_text         = __( "Check the box alongside any Out of Stock products, enter your email address and join the waitlist to be notified when those products become available", 'woocommerce-waitlist' );
			$this->grouped_product_joined_message_text          = __( 'You have updated the selected waitlist/s', 'woocommerce-waitlist' );
			$this->email_field_placeholder_text                 = __( "Email address", 'woocommerce-waitlist' );
		}
	}
}