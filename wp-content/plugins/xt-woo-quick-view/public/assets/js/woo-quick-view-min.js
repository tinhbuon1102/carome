!function(To){"use strict";
/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	*/To(function(){function r(){Y=t("wooqv-close-on-added",0,!0),ao=t("wooqv-lightbox",0,!0),E=t("wooqv-mobile-slider-width",350,!0),B=t("wooqv-mobile-slider-height",350,!0),G=t("wooqv-desktop-slider-width",400,!0),K=t("wooqv-desktop-slider-height",400,!0),oo=t("wooqv-slider-animation","slide"),to=t("wooqv-slider-autoplay",0,!0),eo=t("wooqv-slider-gallery",1,!0),io=t("wooqv-slider-gallery-thumbs",6,!0),ro=t("wooqv-slider-arrows-enabled",0,!0),no=t("wooqv-slider-arrow",""),so=t("wooqv-box-shadow-blur",30),lo=t("wooqv-box-shadow-spread",0),co=t("wooqv-box-shadow-color","rgba(0,0,0,0.3)"),A.css({"box-shadow":"0 0 "+so+"px "+lo+"px "+co})}function q(){xo=To(window).width(),ko=To(window).height(),_o=xo<=W.L,Co=xo<=W.M,Z=_o?parseInt(E):parseInt(G),$=_o?parseInt(B):parseInt(K)}function o(){if(X){for(var t,o=To(document.body).data("events").click,e=0;e<o.length;e++)if("preview"===o[e].namespace){t=o[e].handler;break}t&&(To(document.body).off("click.preview","a"),To(document.body).on("click.preview","a",function(o){To(o.target).hasClass("wooqv-trigger")||To(o.target).hasClass("wooqv-trigger-icon")||t(o)})),void 0!==wp.customize&&void 0!==wp.customize.preview&&A.attrchange({trackValues:!0,
/* Default to false, if set to true the event object is 
					                updated with old and new value.*/
callback:function(o){
//event               - event object
//event.attributeName - Name of the attribute modified
//event.oldValue      - Previous value of the modified attribute
//event.newValue      - New value of the modified attribute
//Triggered when the selected elements attribute is added/updated/removed
-1!==o.attributeName.search("wooqv-")&&(r(),setTimeout(function(){y(),y()},1))}})}
//open / close the quick view panel
if(To("body").on("click",function(o){if(To(o.target).is(".wooqv-shortcode-trigger")){o.preventDefault();var t=To(o.target).attr("target");To("#"+t).find(".wooqv-trigger").trigger("click")}else if(To(o.target).is(".wooqv-product-overlay"))o.preventDefault(),To(o.target).next().trigger("click");else if(To(o.target).is(".wooqv-trigger")||To(o.target).is(".wooqv-trigger-icon")){o.preventDefault();var e=To(o.target).closest(".product").find("img.attachment-shop_catalog");if(0===e.length&&0===(e=To(o.target).closest(".product").find(".woocommerce-LoopProduct-link > img")).length&&0===(e=To(o.target).closest(".product").find(".woocommerce-LoopProduct-link img").first()).length&&0===(e=To(o.target).closest(".product").find(".attachment-woocommerce_thumbnail").first()).length&&0===(e=To(o.target).closest(".product").find(".woocommerce-LoopProduct-link").first()).length&&(e=To(o.target).closest(".product").find(".wp-post-image").first()),0===e.length)return!1;var i=e.attr("src");To("html").addClass("wooqv-active"),f(e,vo,uo,"open")}else To(o.target).is(".wooqv-close-icon")||To(o.target).is("html.wooqv-active")||To(o.target).is(".wooqv-overlay")?u(vo,uo):To(o.target).is(".wooqv-prev")||To(o.target).closest(".wooqv-prev").length?T():(To(o.target).is(".wooqv-next")||To(o.target).closest(".wooqv-next").length)&&P()}),To(document).keyup(function(o){
//check if user has pressed 'Esc'
"27"===o.which&&u(vo,uo)}),
//center quick-view on window resize
To(window).on("resize",function(){window.requestAnimationFrame(y),window.requestAnimationFrame(y)}),To(document.body).on("wooqv-animation-end",function(){m(),y(),x(),To("html").addClass("wooqv-ready"),qo=!0}),
// Woo Floating Cart Integration
F||
//single add product to cart
To(document).on("click",".woo-quick-view form .single_add_to_cart_button",function(o){var t=To(this);if(a(t)||t.hasClass("disabled"))return!1;o.preventDefault(),o.stopPropagation(),s(t)&&n(t)}),Y){var i=function(){mo&&u(vo,uo)};To(document.body).on("woofc_added_to_cart",i),To(document.body).on("wooqv_added_to_cart",i)}}function n(o){if(Q&&clearInterval(Q),o.data("loading"))return!1;o.removeClass("added");var t=o.closest("form"),e=t.serializeJSON();"string"==typeof e&&(e=To.parseJSON(e)),"object"==typeof e&&(e["add-to-cart"]=t.find('[name="add-to-cart"]').val()),o.data("loading",!0),o.addClass("loading"),
//update cart product list
i(e,function(){o.removeClass("loading").addClass("added"),o.removeData("loading"),Q=setTimeout(function(){o.removeClass("added")},3e3),setTimeout(function(){To(document.body).trigger("wc_fragment_refresh"),To(document.body).trigger("wooqv_added_to_cart")},200)})}function i(o,t){To("html").addClass("wooqv-loading");var e,i={action:"wooqv_update_cart",type:"single-add"};i=To.extend(i,o),To.ajax({url:location.href,data:i,type:"post",success:function(){To("html").removeClass("wooqv-loading"),void 0!==t&&t()}})}function a(o){return!(!o.hasClass("gform_button")&&!o.closest(".wc-product-table").length)}function s(o){
// validate required options from:
// https://woocommerce.com/products/product-add-ons/
// https://codecanyon.net/item/woocommerce-extra-product-options/7908619
var t=o.closest("form"),e=0;if(t.find(".required-product-addon, .tm-has-required + div + .tm-extra-product-options-container").each(function(){var o=To(this),t=To(this).find("input");1<t.length?(o.removeClass("woofc-error"),t.is(":checked")||(e++,o.addClass("woofc-error"))):(o.removeClass("woofc-error"),""===t.val()&&(e++,o.addClass("woofc-error")))}),0<e){var i=t.find(".required-product-addon.woofc-error").first();To("html,body").animate({scrollTop:i.offset().top-100},500)}return 0===e}function t(o,t,e){var i;return e=e||!1,i=A.attr(o)?A.attr(o):t,e&&(i=parseInt(i)),i}function d(){_o?(To(".wooqv-slider li img").css("width",""),To(".wooqv-item-info").css("height",""),To(".woo-quick-view, .wooqv-slider-wrapper").css("width",E+"px"),To(".wooqv-slider-wrapper").css("height",B+"px")):(To(".woo-quick-view").css("width",""),To(".wooqv-slider-wrapper, .wooqv-slider li img").css("width",G+"px"),To(".wooqv-slider-wrapper, .wooqv-item-info").css("height",K+"px"))}function y(){q(),X&&d(),
//SET VARS FOR MOBILE
uo=xo<=Z?vo=xo:(vo=Z,R),fo=ko<=$?wo=ko:(wo=$,U),
// SET OVERFLOW
!b(E)||!1!==ho&&null!==ho?b(E)||!0!==ho&&null!==ho||c():l(),!C(A.height())||!1!==go&&null!==go?C(A.height())||!0!==go&&null!==go||w():v();var o=(xo-A.width())/2,t=(ko-A.height())/2,e=.8*xo<uo?.8*xo:uo,i="",r=parseInt(e-G);_o&&(o=(xo-A.width())/2,t=(ko-A.height())/2,e=E,i=.8*ko<fo?.8*ko:fo,r="100%"),A.css({top:t,left:o,width:e}),A.find(".wooqv-item-info").css("width",r)}function b(o){return xo<=o+So}function C(o){return ko<=o+So}function l(){To("html").addClass("wooqv-width-overflow"),ho=!0}function c(){To("html").removeClass("wooqv-width-overflow"),ho=!1}function v(){To("html").addClass("wooqv-height-overflow"),go=!0}function w(){To("html").removeClass("wooqv-height-overflow"),go=!1}function u(o,t,e,i){e=void 0!==e&&e;var r,n=To(".wooqv-close-icon").siblings(".wooqv-slider-wrapper").find(".selected img").attr("src"),a=To(".empty-box").find("img");
//update the image in the gallery
e||A.hasClass("velocity-animating")||!A.hasClass("wooqv-add-content")?p(a,o,t,i):(a.attr("src",n),f(a,o,t,"close",i))}function f(o,t,e,i,r){y();var n=(
//store some image data (width, top position, ...)
//store window data to calculate quick view panel position
o=o.length?o:To(".empty-box")).closest(".product"),a=n.find(".wooqv-trigger").data("id"),s=o.offset().top-To(window).scrollTop(),d=o.offset().left,l=o.width(),c=o.height(),v=t*c/l,w=(xo-t)/2,u=(ko-wo)/2,f=.8*xo<e?.8*xo:e,p=v,h=(xo-f)/2,g=u,m=parseInt(f-G);_o&&(u=(ko-(v=t*c/l))/2,f=t,h=w=(xo-vo)/2,g=(ko-(p=.8*ko<fo?.8*ko:fo))/2,m="100%"),To("html").removeClass("wooqv-ready"),qo=!1,"open"===i?_(a,null,function(o){if(!o)return!1;q(),y(),b(t)&&(w=Io),
//hide the image in the gallery
n.addClass("empty-box"),
//place the quick view over the image gallery and give it the dimension of the gallery image
A.css({top:s,left:d,width:l}).velocity({
//animate the quick view: animate its width and center it in the viewport
//during this animation, only the slider image is visible
top:u+"px",left:w+"px",width:t+"px",scaleX:"1",scaleY:"1",opacity:1},800,[400,20],function(){_o&&(p=A.find(".wooqv-item-info").outerHeight(!0)+A.find(".wooqv-slider-wrapper").outerHeight(!0),g=(ko-p)/2),b(f)&&(h=Io),C(p)&&(g=Io),
//animate the quick view: animate its width to the final value
A.addClass("wooqv-animate-width").velocity({top:g+"px",left:h+"px",width:f+"px"},300,"ease",function(){
//show quick view content
A.find(".wooqv-item-info").css("width",m),A.addClass("wooqv-add-content"),A.addClass("wooqv-preview-gallery"),setTimeout(function(){A.removeClass("wooqv-preview-gallery"),A.addClass("wooqv-no-transitions")},2e3),M(),y(),To(document.body).trigger("wooqv-animation-end"),void 0!==r&&r()})}).addClass("wooqv-is-visible"),mo=!0}):(
//close the quick view reverting the animation
A.removeClass("wooqv-add-content").velocity({top:u+"px",left:w+"px",width:t+"px"},300,"ease",function(){To("html").removeClass("wooqv-active"),A.removeClass("wooqv-animate-width").velocity({top:s,left:d,width:l,scaleX:"0.5",scaleY:"0.5",opacity:0},500,"ease",function(){mo=!1,A.removeClass("wooqv-no-transitions"),A.removeClass("wooqv-is-visible"),n.removeClass("empty-box"),To(document.body).trigger("wooqv-animation-end"),void 0!==r&&r()})}),mo=!1,bo=yo=null)}function p(o,t,e,i){var r=(o=o.length?o:To(".empty-box")).closest(".product"),n=o.offset().top-To(window).scrollTop(),a=o.offset().left,s=o.width();
//close the quick view reverting the animation
To("html").removeClass("wooqv-active"),r.removeClass("empty-box"),A.velocity("stop").removeClass("wooqv-add-content wooqv-no-transitions wooqv-animate-width wooqv-is-visible").css({top:n,left:a,width:s}),mo=!1,y(),void 0!==i&&i()}function _(t,e,i){var o,r;if(e=e||0,yo=yo||0,bo=bo||0,parseInt(t)+parseInt(e)===parseInt(yo)+parseInt(bo))return void 0!==i&&i(!1),!1;qo?e||mo?A.find(".wooqv-slider-wrapper").block({message:null,overlayCSS:{background:"#fff",opacity:.4}}):A.block({message:null,overlayCSS:{background:"#fff",opacity:1}}):To("html").addClass("wooqv-loading");var n={action:"wooqv_quick_view",id:t,variation_id:e,slider_only:e||mo?1:0};y(),To.ajax({url:WOOQV.ajaxurl,data:n,type:"post",success:function(o){0<A.height()&&A.css("height",A.height()+"px"),e||mo?A.find(".wooqv-slider-wrapper").replaceWith(To(o.fragment)):A.find(".wooqv-product").replaceWith(To(o.fragment)),h(t,e,o,i)}})}function h(o,t,e,i){yo=o,bo=t,X&&(bo=yo=null,d()),g(),S(e,i),m(),To(document.body).trigger("wooqv-product-loaded"),To("html").removeClass("wooqv-loading"),setTimeout(function(){A.css("height",""),A.find(".wooqv-slider-wrapper").unblock(),A.unblock()},100)}function g(){if(!ao)return A.find(".wooqv-slider-wrapper .wooqv-slider li a").on("click",function(o){o.preventDefault()}),!1;
// Lightbox
A.find(".wooqv-slider-wrapper a[data-rel^='prettyPhoto']").prettyPhoto({hook:"data-rel",social_tools:!1,theme:"pp_woocommerce",horizontal_padding:20,opacity:.8,deeplinking:!1})}function m(){z()?To("html").addClass("wooqv-first-product"):To("html").removeClass("wooqv-first-product"),L()?To("html").addClass("wooqv-last-product"):To("html").removeClass("wooqv-last-product")}function x(){"undefined"!=typeof wc_add_to_cart_variation_params&&A.find(".variations_form").each(function(){To(this).wc_variation_form(),To(this).off("found_variation",e),To(this).find(".reset_variations").off("click",k),To(this).on("found_variation",e),To(this).find(".reset_variations").on("click",k)})}function e(o,t){I(t)}function k(o){I()}function I(o){var t,e,i;_(D().find(".wooqv-trigger").data("id"),o?o.variation_id:0,function(o){o&&(M(),y(),V&&V.goToSlide(1))})}function S(o,t){var e;if(parseInt(A.find(".wooqv-slider-wrapper").attr("data-attachments"))<=1)return void 0!==t&&t(o),!1;V=A.find(".wooqv-slider").lightSlider({gallery:1===eo,mode:oo,auto:1===to,pauseOnHover:!0,item:1,loop:!0,thumbItem:io,thumbMargin:0,slideMargin:0,currentPagerPosition:"left",controls:1===ro,prevHtml:'<span class="wooqv-arrow-icon '+no+'"></span>',nextHtml:'<span class="wooqv-arrow-icon '+no+'"></span>',onSliderLoad:function(){void 0!==t&&t(o)}})}function T(){if(!mo||!yo)return!1;var o;O(D().prev())}function P(){if(!mo||!yo)return!1;var o;O(D().next())}function z(){return!(!mo||!yo)&&yo===j()}function L(){return!(!mo||!yo)&&yo===H()}function D(){return To(".wooqv-trigger[data-id="+yo+"]").closest(".product")}function j(){return To(".product:first-child .wooqv-trigger").data("id")}function H(){return To(".product:last-child .wooqv-trigger").data("id")}function O(o){if(o.find(".wooqv-trigger").length){var t=o.find(".wooqv-trigger").data("id");mo=!1,_(t,bo=yo=null,function(o){o&&(M(),y()),mo=!0})}}function M(){!J&&V&&V.refresh()}function N(){return"ontouchstart"in document.documentElement?(To("html").addClass("wooqv-touchevents"),!0):(To("html").addClass("wooqv-no-touchevents"),!1)}
//final width --> this is the quick view image slider width
//maxQuickWidth --> this is the max-width of the quick-view panel
var W={S:400,M:768,L:1023,XL:1123},X=void 0!==wp.customize,A=To(".woo-quick-view"),F=0<To(".woofc").length,J=N(),Q,V,Y=0,E=350,B=350,G=400,K=400,R=900,U=755,Z,$,oo,to=0,eo=1,io=6,ro=0,no="",ao=!1,so="",lo="",co="",vo=Z,wo=$,uo=R,fo=U,po=!0,ho=null,go=null,mo=!1,qo=!1,yo=null,bo=null,Co=!1,_o=!1,xo,ko,Io=50,So=2*Io;To(document).on("ready",function(){r(),q(),o(),y()}),window.wooqv_resize=y,window.wooqv_previous=T,window.wooqv_is_first=z,window.wooqv_is_last=L,window.wooqv_next=P})}(jQuery);