// JS required for the front end
jQuery( document ).ready( function( $ ){

    //Support for WooCommerce Quick View (check quick view elements exist and we are on the shop page)
    if ( $( '.post-type-archive' ).length && $( '.quick-view-button' ).length ) {
        $( window ).ajaxComplete( function() {
            console.log( 'waitlist loaded' );
            load_waitlist();
        });
    } else if ( $( '.single-product' ).length ) {
        $( window ).on('load', function() {
            load_waitlist();
        });
    }

    var load_waitlist = function() {
        /* SUBSCRIPTIONS */
        // Hack to add the email input for simple subscriptions to the page.
        // WCS has the filter we require wrapped with wp_kses which removes out input field
        if( $( 'div.product-type-subscription' ).length && ! $( '#wcwl_email' ).length ) {
            $( 'div.wcwl_email_field' ).append( '<input type="email" name="wcwl_email" id="wcwl_email" />' );
        }

        /* GROUPED */
        if( $( '.product-type-grouped' ).length || $( '.woocommerce_waitlist_label' ).length ) {
            //Grab href of join waitlist button
            var href = $( "a.woocommerce_waitlist" ).attr( "href" );
            var product_id = $( '.wcwl_control a' ).data( 'id' );
            // Modify the buttons href attribute to include the updated array of checkboxes and user email
            $( '#wcwl-product-' + product_id ).on( 'click', function( e ) {
                $( "a.woocommerce_waitlist" ).prop( "href", href + "&wcwl_leave=" + leave_array + "&wcwl_join=" + join_array );
            } );
            // Create two arrays, showing products user wishes to join/leave the waitlist for
            var join_array = $( "input:checkbox:checked.wcwl_checkbox" ).map( function() {
                return $( this ).attr( "id" ).replace( 'wcwl_checked_', '' );
            } ).get();
            if( ! $( '#wcwl_email' ).length > 0 ) {
                var leave_array = $( "input:checkbox:not(:checked).wcwl_checkbox" ).map( function() {
                    return $( this ).attr( "id" ).replace( 'wcwl_checked_', '' );
                } ).get();
            } else {
                var leave_array = [];
            }
            // When a checkbox is clicked, add/remove the product ID to/from the appropriate arrays
            $( ".wcwl_checkbox" ).change( function() {
                // If user is logged in create a join and leave array otherwise we only want a join array
                var changed = $( this ).attr( "id" ).replace( 'wcwl_checked_', '' );
                if( this.checked ) {
                    if( ! $( '#wcwl_email' ).length > 0 ) {
                        leave_array.splice( $.inArray( changed, leave_array ), 1 );
                    }
                    join_array.push( changed );
                }
                if( ! this.checked ) {
                    join_array.splice( $.inArray( changed, join_array ), 1 );
                    if( ! $( '#wcwl_email' ).length > 0 ) {
                        leave_array.push( changed );
                    }
                }
            } );
        }

        /* GENERIC */
        // When email input is changed update the buttons href attribute to include the email
        // Load on page load
        update_waitlist_link_with_email();
        // Load on variation change - only required for variations
        $( "form.variations_form" ).change( function() {
            update_waitlist_link_with_email();
        } );
        function update_waitlist_link_with_email() {
            // Only load if user is logged out
            if( $( '#wcwl_email' ).length > 0 ) {
                $( '.wcwl_control' ).on( 'click', 'a.woocommerce_waitlist', function( event ) {
                    event.preventDefault();
                    window.location.href = $( this ).attr( 'href' ) + '&wcwl_email=' + $( '#wcwl_email' ).val();
                } );
            }
        }
    }
});
