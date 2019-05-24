jQuery( document ).ready( function ( $ ) {
	function init_events() {
		if ( script_data.update_price_with_qty ) {
			jQuery( '[name="quantity"]' ).on( 'change', function () {
				var $qty = jQuery( this ).val();
				var $product_id = 0;
				var $price_place = "";

				if ( jQuery( 'button[name="add-to-cart"]' ).length ) {
					$product_id = jQuery( 'button[name="add-to-cart"]' ).val();
					$price_place = jQuery( 'div.product p.price' );
				} else if ( jQuery( 'input[name="variation_id"]' ).length ) {
					$product_id = jQuery( 'input[name="variation_id"]' ).val();

					$price_place = jQuery( 'div.product .woocommerce-variation-price' );
					$price_place.html( "" );
					$price_place.append( "<div class='price'></div>" );
					$price_place = jQuery( 'div.product .woocommerce-variation-price .price' );
				}

				if ( ! $product_id || ! $price_place ) {
					return;
				}

				var data = {
					action: 'get_price_product_with_bulk_table',
					product_id: $product_id,
					qty: $qty,
				};

				jQuery.ajax( {
					url: script_data.ajaxurl,
					data: data,
					dataType: 'json',
					type: 'POST',
					success: function ( response ) {
						if ( response.success ) {
							$price_place.html( response.data.price_html )
						}
					},
					error: function ( response ) {

					}
				} );

			} );
		}

	}

	if ( script_data.js_init_trigger ) {
		$( document ).on( script_data.js_init_trigger, function () {
			init_events();
		} );
	}

	init_events();
} );