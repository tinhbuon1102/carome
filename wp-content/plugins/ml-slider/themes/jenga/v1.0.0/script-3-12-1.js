!function(e){e(document).on("metaslider/initialized",function(s,i){if(e(i).closest(".metaslider.ms-theme-jenga").length){var a=e(i),l=e(i).closest(".metaslider.ms-theme-jenga"),t=a.find(".caption");t.length&&l.addClass("ms-has-caption"),l.addClass("ms-loaded")}e(".metaslider.has-dots-nav.ms-theme-jenga:not(.has-carousel-mode) .flexslider:not(.filmstrip) > .flex-control-paging, .metaslider.has-dots-nav.ms-theme-jenga:not(.has-carousel-mode) .flexslider:not(.filmstrip) > .flex-direction-nav").wrapAll("<div class='slide-control'></div>"),e(".metaslider.ms-theme-jenga.has-carousel-mode .flexslider > .flex-control-paging").wrap("<div class='slide-control'></div>"),e(".metaslider.ms-theme-jenga.has-filmstrip-nav .flexslider:not(.filmstrip) > .flex-direction-nav").wrap("<div class='slide-control'></div>"),e(".metaslider.ms-theme-jenga:not(.has-carousel-mode).metaslider-responsive > div > .rslides_nav, .metaslider.ms-theme-jenga:not(.has-carousel-mode).metaslider-responsive > div > .rslides_tabs").wrapAll("<div class='slide-control'></div>"),e(".metaslider.ms-theme-jenga:not(.has-carousel-mode).metaslider-responsive .slide-control > .rslides_nav").wrapAll("<div class='rslides_arrows'></div>");var o=e(".metaslider.ms-theme-jenga:not(.has-carousel-mode).metaslider-responsive .rslides_arrows");o.next().insertBefore(o),e(window).trigger("resize")}),e(window).on("resize",function(s){e(function(){e(".metaslider.ms-theme-jenga .slide-control").css({position:"absolute",top:"39%",height:"140px","margin-top":"-50px"})})})}(jQuery);