( function( $ ) {
    "use strict";

    $(document).ready(function(){
        $(document).on('click',".zoom-button",function(t){
            //$(".product-gallery-slider").find(".is-selected a").click();
            t.preventDefault()
        });

        $( document.body ).bind( 'openswatch_update_images',function(event,data){
            //start your custom code here
            // data.html is html string return by filter: openswatch_image_swatch_html ,
            // use add_filter('openswatch_image_swatch_html','your function') to change it.

            // this is for storefont theme
            var html = data.html;
            var productId = data.productId;
            if(html.length > 5 )
            {
                //$('.woocommerce-product-gallery').replaceWith( html );
                var $form = $('.variations_form');
                //var $product          = $form.closest( '.product' );
                //var $product_gallery  = $product.find( '.images' );
                $( html).wc_product_gallery();

                console.log(html);
                var lga = $(html).find('.product-gallery-slider').html();
                var images_options = $('.product-gallery-slider').data('flickity-options');

                var thumbnails_options = $('.product-thumbnails').data('flickity-options');
                var thumbnails_js_flickity = $('.product-thumbnails').flickity(thumbnails_options);

                var images_js_flickity = $('.product-gallery-slider').flickity(images_options);
                images_js_flickity.flickity('destroy');
                thumbnails_js_flickity.flickity('destroy');
                $('.product-gallery').html(html);

                images_js_flickity = $('.product-gallery-slider').flickity(images_options);
                thumbnails_js_flickity = $('.product-thumbnails').flickity(thumbnails_options);

                jQuery(".has-lightbox .product-gallery-slider").each(function() {
                    jQuery(this).magnificPopup({
                        delegate: "a",
                        type: "image",
                        tLoading: '<div class="loading-spin centered dark"></div>',
                        closeBtnInside: !1,
                        gallery: {
                            enabled: !0,
                            navigateByImgClick: !0,
                            preload: [0, 1],
                            arrowMarkup: '<button class="mfp-arrow mfp-arrow-%dir%" title="%title%"><i class="icon-angle-%dir%"></i></button>'
                        },
                        image: {
                            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                            verticalFit: !1
                        }
                    })
                })

                jQuery(".has-image-zoom .slide").easyZoom({loadingNotice:"",preventClicks:!1});
                $( '.woocommerce-product-gallery' ).each( function() {
                    // $( this ).wc_product_gallery();
                } );
            }
            //end your custom code here
        });

        jQuery(document).on('click',".zoom-button",function(t) {
            jQuery(".product-gallery-slider").find(".is-selected a").click(), t.preventDefault()
        });
        
    });
} )( jQuery );