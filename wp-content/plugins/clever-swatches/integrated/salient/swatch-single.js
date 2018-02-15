
jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    var imagesDiv = jQuery('#product-' + response.product_id).find('.single-product-main-image');
    if (jQuery(imagesDiv).length) {
        imagesDiv.html(response.content);
    }
    setTimeout(function () {
        imagesLoaded(jQuery('.product-slider'),function(instance) {
            jQuery('.iosSlider.product-slider').iosSlider({
                snapToChildren: true,
                desktopClickDrag: true,
                elasticPullResistance: 0.6,
                snapFrictionCoefficient: 0.8,
                infiniteSlider: true,
                navPrevSelector: jQuery('.product-slider .prev_slide'),
                navNextSelector: jQuery('.product-slider .next_slide'),
                navSlideSelector: '.product-thumbs .thumb',
                onSlideChange: iosSliderChange,
                onSliderLoaded: iosSliderResize,
                onSliderResize: iosSliderResize
            });


            jQuery('.iosSlider.product-thumbs').iosSlider({
                desktopClickDrag: true,
                snapToChildren: true,
                navPrevSelector: jQuery('.product-thumbs .prev_slide'),
                navNextSelector: jQuery('.product-thumbs .next_slide')
            });
            jQuery('.iosSlider').css('opacity','1');
        })
    },300);
    function iosSliderResize(obj){
        setTimeout(function() {

            var slideHeight = jQuery(obj.currentSlideObject).find('img').first().outerHeight();

            jQuery(obj.sliderContainerObject).css('min-height',slideHeight);
            jQuery(obj.currentSlideObject).css('height',slideHeight);
            jQuery(obj.sliderContainerObject).css('height','auto');

        }, 30);
    }
    var slideNum = jQuery('.iosSlider.product-thumbs').find('.thumb').length;
    function iosSliderChange(obj) {

        jQuery('.product-thumbs .thumb').removeClass('active');
        jQuery('.product-thumbs .thumb:eq(' + (obj.currentSlideNumber - 1) + ')').addClass('active');

        if(slideNum > 4){
            jQuery('.product-thumbs').iosSlider('goToSlide', obj.currentSlideNumber);
        }
    }
});