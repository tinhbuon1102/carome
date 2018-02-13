( function( $ ) {
    "use strict";

    $(document).ready(function(){

        $( document.body ).bind( 'openswatch_update_images',function(event,data){
            //start your custom code here
            // data.html is html string return by filter: openswatch_image_swatch_html ,
            // use add_filter('openswatch_image_swatch_html','your function') to change it.

            // this is for storefont theme
            var html = data.html;
            var productId = data.productId;
            if(html.length > 5)
            {
                console.log(html);
                $('.p-single-images').html( html );
                var $form = $('.variations_form');
                var $product          = $form.closest( '.product' );
                var $product_gallery  = $product.find( '.images' );
                $( '.woocommerce-product-gallery' ).wc_product_gallery();
                $form.trigger('reset_image');

                if ($(".flex-control-thumbs").length > 0) {
                    var aa = $(".flex-control-thumbs li").length;
                    if (aa > 5) {
                        E(".woocommerce-product-gallery__wrapper").WR_ImagesLoaded(function() {
                            setTimeout(function() {
                                $(".flex-control-thumbs").scrollbar()
                            }, 50)
                        })
                    }
                }
                if ($(".woocommerce-product-gallery--with-nav").length > 0) {
                    $(".woocommerce-product-gallery--with-nav").flexslider({
                        animation: "slide",
                        controlNav: false,
                        animationLoop: false,
                        slideshow: false,
                        itemWidth: 90,
                        itemMargin: 10,
                        asNavFor: ".woocommerce-product-gallery--with-images"
                    })
                }

                $('.single-product .woocommerce-product-gallery').attr('style','opacity: 1; transition: opacity 0.25s ease-in-out;display: block !important');;
            }
            //end your custom code here
        });
    });
} )( jQuery );