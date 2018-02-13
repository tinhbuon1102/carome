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
                $('.avada-single-product-gallery-wrapper').html( html );
                var $form = $('.variations_form');

                $( '.woocommerce-product-gallery' ).wc_product_gallery();
                $form.trigger('reset_image');
                $('.avada-product-gallery .flex-control-thumbs').css('opacity',1);
            }
            //end your custom code here
        });
    });
} )( jQuery );