<?php
/**
 * Common function
 *
 * @author YITH
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */

if( ! function_exists( 'yith_wccl_update_db_check' ) ) {
	function yith_wccl_update_db_check() {
		if ( get_option( 'yith_wccl_db_version' ) != YITH_WCCL_DB_VERSION ) {

			if ( ! function_exists( 'yith_wccl_activation' ) ) {
				require_once( YITH_WAPO_DIR . 'includes/functions/yith-wccl-activation.php' );
			}

			yith_wccl_activation();
		}
	}
}


if( ! function_exists( 'ywccl_get_term_meta' ) ) {
	/**
	 * Get term meta. If WooCommerce version is >= 2.6 use get_term_meta else use get_woocommerce_term_meta
	 *
	 * @param $term_id
	 * @param $key
	 * @param bool $single
	 *
	 * @return mixed
	 * @author Francesco Licandro
	 */
	function ywccl_get_term_meta( $term_id, $key, $single = true ) {
		if ( ywccl_check_wc_version( '2.6', '>=' ) ) {
			return function_exists( 'get_term_meta' ) ? get_term_meta( $term_id, $key, $single ) : get_metadata( 'woocommerce_term', $term_id, $key, $single );
		} else {
			return get_woocommerce_term_meta( $term_id, $key, $single );
		}
	}
}

if( ! function_exists( 'ywccl_update_term_meta' ) ) {
	/**
	 * Get term meta. If WooCommerce version is >= 2.6 use update_term_meta else use update_woocommerce_term_meta
	 *
	 * @param string|int $term_id
	 * @param string $meta_key
	 * @param mixed $meta_value
	 * @param mixed $prev_value
	 *
	 * @return bool
	 * @author Francesco Licandro
	 */
	function ywccl_update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
		if ( ywccl_check_wc_version( '2.6', '>=' ) ) {
			return function_exists( 'update_term_meta' ) ? update_term_meta( $term_id, $meta_key, $meta_value, $prev_value ) : update_metadata( 'woocommerce_term', $term_id, $meta_key, $meta_value, $prev_value );
		} else {
			return update_woocommerce_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' );
		}
	}
}

if( ! function_exists( 'ywccl_get_custom_tax_types' ) ) {
	/**
	 * Return custom product's attributes type
	 *
	 * @author Francesco Licandro
	 * @since 1.2.0
	 * @return mixed|void
	 */
	function ywccl_get_custom_tax_types() {
		return apply_filters( 'yith_wccl_get_custom_tax_types', array(
			'colorpicker' => __( 'Colorpicker', 'yith-woocommerce-color-label-variations' ),
			'image'       => __( 'Image', 'yith-woocommerce-color-label-variations' ),
			'label'       => __( 'Label', 'yith-woocommerce-color-label-variations' )
		) );
	}
}

if( ! function_exists( 'ywccl_check_wc_version' ) ) {
	/**
	 * Check installed WooCommerce version
	 *
	 * @since 1.3.0
	 * @author Francesco Licandro
	 * @param string $version
	 * @param string $operator
	 * @return boolean
	 */
	function ywccl_check_wc_version( $version, $operator ) {
		return version_compare( WC()->version, $version, $operator );
	}
}

if( ! function_exists( 'yith_wccl_hide_add_to_cart' ) ) {
	/**
	 * Check if catalog mode is active or if RAQ option "Hide add to cart" as enabled
	 *
	 * @since 1.6.0
	 * @author Francesco Licandro
	 * @return boolean
	 */
	function yith_wccl_hide_add_to_cart(){
		$catalog_mode = defined( 'YWCTM_PREMIUM' ) && YWCTM_PREMIUM && get_option( 'ywctm_enable_plugin ' ) == 'yes';
		$raq          = defined( 'YITH_YWRAQ_PREMIUM' ) && YITH_YWRAQ_PREMIUM && get_option( 'ywraq_hide_add_to_cart' ) == 'yes';
		
		return $catalog_mode || $raq;
	}
}

if( ! function_exists( 'yith_wccl_get_variation_gallery' ) ) {
    /**
     * Get gallery images for given variation
     *
     * @since 1.8.0
     * @author Francesco Licandro
     * @param \WP_Post|\WC_Product_Variation $variation
     * @return array
     */
    function yith_wccl_get_variation_gallery( $variation ) {
        $variation instanceof WC_Product || $variation = wc_get_product( $variation->ID );
        if( ! $variation ) {
            return array();
        }

        return $variation->get_meta( '_yith_wccl_gallery', true );
    }
}