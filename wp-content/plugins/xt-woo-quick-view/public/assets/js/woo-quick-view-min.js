(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	*/
	
	$(function() {
		
		//final width --> this is the quick view image slider width
		//maxQuickWidth --> this is the max-width of the quick-view panel
		var layouts = {
			'S': 400,  
			'M': 768,  
			'L': 1023,   
			'XL':1123 
		},
		customizer = (typeof(wp.customize) !== 'undefined'),
		quickView = $('.woo-quick-view'),
		woofcEnabled = ($('.woofc').length > 0),
		isTouchDevice = touchSupport(),
		addTimeoutId,
		currentSlider,
		closeModalOnAdded = 0,
		mobileSliderWidth = 350,
		mobileSliderHeight = 350,
		desktopSliderWidth = 400,
		desktopSliderHeight = 400,
		defaultMaxQuickWidth = 900,
		defaultMaxQuickHeight = 755,
		defaultSliderWidth,
		defaultSliderHeight,
		sliderAnimation,
		sliderAutoPlay = 0,
		sliderGallery = 1,
		sliderGalleryThumbs = 6,
		sliderArrowsEnabled = 0,
		sliderArrow = '',
		lightBoxEnabled = false,
		
		boxShadowBlur = '',
		boxShadowSpread = '',
		boxShadowColor = '',
		
		sliderFinalWidth = defaultSliderWidth,
		sliderFinalHeight = defaultSliderHeight,
		maxQuickWidth = defaultMaxQuickWidth,
		maxQuickHeight = defaultMaxQuickHeight,
		closeOnOverlayClick = true,
		widthOverflowSet = null,
		heightOverflowSet = null,
		isVisible = false,
		animationComplete = false,
		recentProduct = null,
		recentVariation = null,
		mobileScreen = false,
		tabletScreen = false,
		winWidth,
		winHeight,
		modalMargin = 50,
		modalOffset = (modalMargin * 2);

		function initVars() {
		
			closeModalOnAdded = getOption('wooqv-close-on-added', 0, true);
			lightBoxEnabled = getOption('wooqv-lightbox', 0, true);
			mobileSliderWidth = getOption('wooqv-mobile-slider-width', 350, true);
			mobileSliderHeight = getOption('wooqv-mobile-slider-height', 350, true);
			desktopSliderWidth = getOption('wooqv-desktop-slider-width', 400, true);
			desktopSliderHeight = getOption('wooqv-desktop-slider-height', 400, true);
			sliderAnimation = getOption('wooqv-slider-animation', 'slide');
			sliderAutoPlay = getOption('wooqv-slider-autoplay', 0, true);
			sliderGallery = getOption('wooqv-slider-gallery', 1, true);
			sliderGalleryThumbs = getOption('wooqv-slider-gallery-thumbs', 6, true);
			sliderArrowsEnabled = getOption('wooqv-slider-arrows-enabled', 0, true);
			sliderArrow = getOption('wooqv-slider-arrow', '');
			
			boxShadowBlur = getOption('wooqv-box-shadow-blur', 30);
			boxShadowSpread = getOption('wooqv-box-shadow-spread', 0);
			boxShadowColor = getOption('wooqv-box-shadow-color', 'rgba(0,0,0,0.3)');
			
			quickView.css({'box-shadow': '0 0 '+boxShadowBlur+'px '+boxShadowSpread+'px '+boxShadowColor+''});
			
		}
		
		function updateResponsiveVars() {
			
			winWidth = $(window).width(),
			winHeight = $(window).height(),
			tabletScreen = winWidth <= layouts.L,
			mobileScreen = winWidth <= layouts.M,
			defaultSliderWidth = tabletScreen ? parseInt(mobileSliderWidth) : parseInt(desktopSliderWidth);
			defaultSliderHeight = tabletScreen ? parseInt(mobileSliderHeight) : parseInt(desktopSliderHeight);		
		}
		
		function initEvents() {
		
			if(customizer) {
				
				var handler;
				var bodyClickEvents = $( document.body ).data('events').click;
				
				for(var i = 0 ; i < bodyClickEvents.length ; i++) {
				
					if(bodyClickEvents[i].namespace === 'preview') {
						handler = bodyClickEvents[i].handler;
						break;
					}	
				}					
				
				if(handler) {
					$( document.body ).off( 'click.preview', 'a');
					$( document.body ).on( 'click.preview', 'a', function(e) {
	
						if(!$(e.target).hasClass('wooqv-trigger') && !$(e.target).hasClass('wooqv-trigger-icon')) {
							handler(e);
						}
	
					});
				}
				
				if(typeof(wp.customize) !== 'undefined' && typeof(wp.customize.preview) !== 'undefined') {
					
					quickView.attrchange({
					    trackValues: true, /* Default to false, if set to true the event object is 
					                updated with old and new value.*/
					    callback: function (e) { 
					        //event               - event object
					        //event.attributeName - Name of the attribute modified
					        //event.oldValue      - Previous value of the modified attribute
					        //event.newValue      - New value of the modified attribute
					        //Triggered when the selected elements attribute is added/updated/removed
					  
					        if(e.attributeName.search('wooqv-') !== -1) {
						        
					        	initVars();
		
						        setTimeout(function() {
	
							        resizeQuickView();
							        resizeQuickView();
							        
							    },1); 
								
					        }
					    }        
					});
				
				}
			}

			//open / close the quick view panel
			$('body').on('click', function(evt){
				
				if( $(evt.target).is('.wooqv-shortcode-trigger') ) {
					
					evt.preventDefault();		
					var target = $(evt.target).attr('target');
					$('#'+target).find('.wooqv-trigger').trigger('click');
				
				}else if( $(evt.target).is('.wooqv-product-overlay') ) {
					
					evt.preventDefault();		
					$(evt.target).next().trigger('click');
						
				}else if( $(evt.target).is('.wooqv-trigger') || $(evt.target).is('.wooqv-trigger-icon')) {
					
					evt.preventDefault();
					
					var selectedImage = $(evt.target).closest('.product').find('img.attachment-shop_catalog');
					if(selectedImage.length === 0) {
						
						selectedImage = $(evt.target).closest('.product').find('.woocommerce-LoopProduct-link > img');
						
						if(selectedImage.length === 0) {
							selectedImage = $(evt.target).closest('.product').find('.woocommerce-LoopProduct-link img').first();
						
							if(selectedImage.length === 0) {
								selectedImage = $(evt.target).closest('.product').find('.attachment-woocommerce_thumbnail').first();
							
								if(selectedImage.length === 0) {
									selectedImage = $(evt.target).closest('.product').find('.woocommerce-LoopProduct-link').first();
								
									if(selectedImage.length === 0) {
										selectedImage = $(evt.target).closest('.product').find('.wp-post-image').first();
									}
								}
							}
						}
					}
					
					if(selectedImage.length === 0) {
						return false;	
					}
					
					var selectedImageUrl = selectedImage.attr('src');
		
					$('html').addClass('wooqv-active');
					animateQuickView(selectedImage, sliderFinalWidth, maxQuickWidth, 'open');

					
				}else if( 
					$(evt.target).is('.wooqv-close-icon') || 
					$(evt.target).is('html.wooqv-active') ||
					($(evt.target).is('.wooqv-overlay') && closeOnOverlayClick)
				) {
					closeQuickView( sliderFinalWidth, maxQuickWidth);
					
				}else if($(evt.target).is('.wooqv-prev') || $(evt.target).closest('.wooqv-prev').length) {
					
					previousProduct();
					
				}else if($(evt.target).is('.wooqv-next') || $(evt.target).closest('.wooqv-next').length) {
					
					nextProduct();
				}
			});
			
			$(document).keyup(function(evt){
				//check if user has pressed 'Esc'
		    	if(evt.which==='27'){
					closeQuickView( sliderFinalWidth, maxQuickWidth);
				}
			});

			//center quick-view on window resize
			$(window).on('resize', function(){

				window.requestAnimationFrame(resizeQuickView);
				window.requestAnimationFrame(resizeQuickView);
				
			});
			
			$(document.body).on('wooqv-animation-end', function() {
				
				checkNavigation();
				resizeQuickView();
				initVariationsEvents();

				$('html').addClass('wooqv-ready');
				animationComplete = true;

			});
			
			
			// Woo Floating Cart Integration
			if(!woofcEnabled) {
				
				//single add product to cart
				$(document).on('click', '.woo-quick-view form .single_add_to_cart_button', function(evt){
					
					var btn = $(this);
								
					if(skipAddToCart(btn) || btn.hasClass('disabled')) {
						return false;
					}
					
					evt.preventDefault();
					evt.stopPropagation();
					
					if(validateAddToCart(btn)) {
						addToCart(btn);
					}

				});
			
			}

			 
			if(closeModalOnAdded) { 
				
				var closeModal = function() {
					
					if(isVisible) {
						closeQuickView( sliderFinalWidth, maxQuickWidth);
					}
				};
			
				$( document.body ).on( 'woofc_added_to_cart', closeModal);
				$( document.body ).on( 'wooqv_added_to_cart', closeModal);
			}
		}	
		
		
		function addToCart(trigger) {
			
			if(addTimeoutId){
				clearInterval(addTimeoutId);
			}
			
			if(trigger.data('loading')) {
				return false;
			}
			
			trigger.removeClass('added');
			
			var form = trigger.closest('form');
			var args = form.serializeJSON();
			
			if(typeof args === 'string') {
				args = $.parseJSON(args);
			}
			
			if(typeof args === 'object') {
				args['add-to-cart'] = form.find('[name="add-to-cart"]').val();
			}
			
			trigger.data('loading', true);
			trigger.addClass('loading');
			
			//update cart product list
			request(args, function() {
				
				trigger.removeClass('loading').addClass('added');
				trigger.removeData('loading');
				
				addTimeoutId = setTimeout(function() {
					trigger.removeClass('added');
				}, 3000);
				
				setTimeout(function() {
					
					$( document.body ).trigger( 'wc_fragment_refresh' );
					$( document.body ).trigger( 'wooqv_added_to_cart' );
					
				},200);	
			});

		}
		
		
		function request(args, callback) {
			
			$('html').addClass('wooqv-loading');
			
			var type = 'single-add';

			var params = {
				action: 'wooqv_update_cart',
				type: type
			};

			params = $.extend(params, args);
			
			$.ajax({
				url: location.href,
				data: params,
				type: 'post', 
				success: function() {
					
					$('html').removeClass('wooqv-loading');
					
					if(typeof(callback) !== 'undefined') {
						callback();
					}
				}
			});	
		}	
		
		function skipAddToCart(btn) {

			if(btn.hasClass('gform_button') || btn.closest('.wc-product-table').length) {
				
				return true;
			} 
			
			return false;
		}
		
		function validateAddToCart(btn) {

			// validate required options from:
			// https://woocommerce.com/products/product-add-ons/
			// https://codecanyon.net/item/woocommerce-extra-product-options/7908619
			
			var form = btn.closest('form');
			var errors = 0;
			form.find('.required-product-addon, .tm-has-required + div + .tm-extra-product-options-container').each(function() {
				
		    	var addon = $(this);
				var input = $(this).find('input');
		    	if(input.length > 1) {
			    	addon.removeClass('woofc-error');
		    		if(!input.is(':checked')) {
						errors++;
						addon.addClass('woofc-error');
					}
		    	}else{
			    	addon.removeClass('woofc-error');
					if(input.val() === '') {
						errors++;
						addon.addClass('woofc-error');
		        	}
		    	}
			});
	
			
			if(errors > 0) {
				var firstError = form.find('.required-product-addon.woofc-error').first();
				$('html,body').animate({scrollTop: firstError.offset().top - 100}, 500);
			}
			
		    return (errors === 0);
		}

		function getOption(key, defaultVal, isInt) {
			
			var val;
			isInt = isInt ? isInt : false;
			
			if(quickView.attr(key)) {
				
				val = quickView.attr(key);
				
			}else{
			
				val = defaultVal;
			}	
			
			if(isInt) {
				val = parseInt(val);
			}
			
			return val;
		}

		function customizerValuesChanged() {

        	if(!tabletScreen) {
        		
        		$('.woo-quick-view').css('width', '' );
        		
        		$('.wooqv-slider-wrapper, .wooqv-slider li img').css('width', desktopSliderWidth+'px' );
        		$('.wooqv-slider-wrapper, .wooqv-item-info').css('height', desktopSliderHeight+'px');
        	
        	}else{
	        	
	        	$('.wooqv-slider li img').css('width', '' );
				$('.wooqv-item-info').css('height', '' );
				
	        	$('.woo-quick-view, .wooqv-slider-wrapper').css('width', mobileSliderWidth+'px' );
	        	$('.wooqv-slider-wrapper').css('height', mobileSliderHeight+'px' );
	        	
        	}

		}

		function resizeQuickView() {
			
			updateResponsiveVars();
			
			if(customizer) {
				customizerValuesChanged();
			}	
			
			//SET VARS FOR MOBILE
			
			if(winWidth <= defaultSliderWidth) {
				
				sliderFinalWidth = winWidth;
				maxQuickWidth = sliderFinalWidth;
								
			}else{
					
				sliderFinalWidth = defaultSliderWidth;
				maxQuickWidth = defaultMaxQuickWidth;			
			}
			
			if(winHeight <= defaultSliderHeight) {
				
				sliderFinalHeight = winHeight;
				maxQuickHeight = sliderFinalHeight;
								
			}else{
					
				sliderFinalHeight = defaultSliderHeight;
				maxQuickHeight = defaultMaxQuickHeight;		
			}
			
			
			// SET OVERFLOW
			
			if(widthWillOverflow(mobileSliderWidth) && (widthOverflowSet === false || widthOverflowSet === null)) {
				
				enableWidthOverflow();
				
			}else if(!widthWillOverflow(mobileSliderWidth) && (widthOverflowSet === true || widthOverflowSet === null)){

				disableWidthOverflow();
			}	
			
			if(heightWillOverflow(quickView.height()) && (heightOverflowSet === false || heightOverflowSet === null)) {
								
				enableHeightOverflow();
				
			}else if(!heightWillOverflow(quickView.height()) && (heightOverflowSet === true || heightOverflowSet === null)){
				
				disableHeightOverflow();
			}
			
			var quickViewLeft = (winWidth - quickView.width())/2,
				quickViewTop = (winHeight - quickView.height())/2,
				quickViewWidth = ( winWidth * 0.8 < maxQuickWidth ) ? winWidth * 0.8 : maxQuickWidth,
				quickViewHeight = '',
				quickViewInfoWidth = parseInt(quickViewWidth - desktopSliderWidth);

        
			if(tabletScreen) {	
	
				quickViewLeft =  (winWidth - quickView.width())/2;
				quickViewTop = (winHeight - quickView.height())/2;		
				quickViewWidth = mobileSliderWidth;
				quickViewHeight = ( winHeight * 0.8 < maxQuickHeight ) ? winHeight * 0.8 : maxQuickHeight;
				quickViewInfoWidth = '100%';
			}
		
			quickView.css({
			    "top": quickViewTop,
			    "left": quickViewLeft,
			    "width": quickViewWidth
			});
			
			quickView.find('.wooqv-item-info').css('width', quickViewInfoWidth);
			
		} 
		
		function widthWillOverflow(width) {
			
			return (winWidth <= (width + modalOffset))
		}
		
		function heightWillOverflow(height) {
			
			return (winHeight <= (height + modalOffset))
		}
		
		function enableWidthOverflow() {
			$('html').addClass('wooqv-width-overflow');
			widthOverflowSet = true;
		}
		
		function disableWidthOverflow() {
			$('html').removeClass('wooqv-width-overflow');
			widthOverflowSet = false;
		}
		
		function enableHeightOverflow() {
			$('html').addClass('wooqv-height-overflow');
			heightOverflowSet = true;
		}
		
		function disableHeightOverflow() {
			
			$('html').removeClass('wooqv-height-overflow');
			heightOverflowSet = false;
		}
	
		function closeQuickView(finalWidth, maxQuickWidth, noAnimation, callback) {
			
			noAnimation = typeof(noAnimation) !== 'undefined' ? noAnimation : false;
			
			var close = $('.wooqv-close-icon'),
				activeSliderUrl = close.siblings('.wooqv-slider-wrapper').find('.selected img').attr('src'),
				selectedImage = $('.empty-box').find('img');
			//update the image in the gallery
			if(!noAnimation && !quickView.hasClass('velocity-animating') && quickView.hasClass('wooqv-add-content')) {
				selectedImage.attr('src', activeSliderUrl);
				animateQuickView(selectedImage, finalWidth, maxQuickWidth, 'close', callback);
			} else {
				closeNoAnimation(selectedImage, finalWidth, maxQuickWidth, callback);
			}
		}
	
		function animateQuickView(image, finalWidth, maxQuickWidth, animationType, callback) {
			
			resizeQuickView();

			//store some image data (width, top position, ...)
			//store window data to calculate quick view panel position
			image = image.length ? image : $('.empty-box');

			var parentListItem = image.closest('.product'),
			    productId = parentListItem.find('.wooqv-trigger').data('id'),
				topSelected = image.offset().top - $(window).scrollTop(),
				leftSelected = image.offset().left,
				widthSelected = image.width(),
				heightSelected = image.height(),
				
				finalHeight = finalWidth * heightSelected/widthSelected,
				finalLeft = (winWidth - finalWidth)/2,
				finalTop = (winHeight - sliderFinalHeight)/2,
				quickViewWidth = ( winWidth * 0.8 < maxQuickWidth ) ? winWidth * 0.8 : maxQuickWidth,
				quickViewHeight = finalHeight,
				quickViewLeft = (winWidth - quickViewWidth)/2,
				quickViewTop = finalTop,
				quickViewInfoWidth = parseInt(quickViewWidth - desktopSliderWidth);

			if(tabletScreen) {		
			
				finalHeight = finalWidth * heightSelected/widthSelected;
				finalLeft = (winWidth - sliderFinalWidth)/2;
				finalTop = (winHeight - finalHeight)/2;	
				quickViewWidth = finalWidth;
				quickViewHeight = ( winHeight * 0.8 < maxQuickHeight ) ? winHeight * 0.8 : maxQuickHeight;
				quickViewLeft = finalLeft;
				quickViewTop = (winHeight - quickViewHeight)/2;
				quickViewInfoWidth = '100%';
			}
			
	
			$('html').removeClass('wooqv-ready');
			animationComplete = false;
			
			if( animationType === 'open') {

				loadProductInfo(productId, null, function(data) {
					
					if(!data) {
						return false;
					}
					
					updateResponsiveVars();
					resizeQuickView();
					
					if(widthWillOverflow(finalWidth)) {			
						
						finalLeft = modalMargin
					}
		
			
					//hide the image in the gallery
					parentListItem.addClass('empty-box');
					//place the quick view over the image gallery and give it the dimension of the gallery image
					quickView.css({
					    "top": topSelected,
					    "left": leftSelected,
					    "width": widthSelected,
					}).velocity({
						//animate the quick view: animate its width and center it in the viewport
						//during this animation, only the slider image is visible
					    'top': finalTop+ 'px',
					    'left': finalLeft+'px',
					    'width': finalWidth+'px',
					    "scaleX": '1',
					    "scaleY": '1',
					    "opacity": 1
					}, 800, [ 400, 20 ], function(){
		
						if(tabletScreen) {		
							
							quickViewHeight = quickView.find('.wooqv-item-info').outerHeight(true) + quickView.find('.wooqv-slider-wrapper').outerHeight(true);
							quickViewTop = (winHeight - quickViewHeight)/2;
						}
						
						if(widthWillOverflow(quickViewWidth)) {		

							quickViewLeft = modalMargin
						}
						if(heightWillOverflow(quickViewHeight)) {		

							quickViewTop = modalMargin
						}

						//animate the quick view: animate its width to the final value
						quickView.addClass('wooqv-animate-width').velocity({
							'top': quickViewTop+'px',
							'left': quickViewLeft+'px',
					    	'width': quickViewWidth+'px'
						}, 300, 'ease' ,function(){
							//show quick view content
							
							quickView.find('.wooqv-item-info').css('width', quickViewInfoWidth);
							
							quickView.addClass('wooqv-add-content');
							quickView.addClass('wooqv-preview-gallery');
							setTimeout(function() {
								quickView.removeClass('wooqv-preview-gallery');
								quickView.addClass('wooqv-no-transitions');
							},2000);
							
							refreshLightSlider();
							resizeQuickView();
							
							$(document.body).trigger('wooqv-animation-end');
							
							if(typeof(callback) !== 'undefined') {
								callback()
							}
							
						});
						
					}).addClass('wooqv-is-visible');
					
					isVisible = true;
				
				});
				
			} else {
				//close the quick view reverting the animation
				quickView.removeClass('wooqv-add-content').velocity({
				    'top': finalTop+ 'px',
				    'left': finalLeft+'px',
				    'width': finalWidth+'px',
				}, 300, 'ease', function(){
					$('html').removeClass('wooqv-active');
					quickView.removeClass('wooqv-animate-width').velocity({
						"top": topSelected,
					    "left": leftSelected,
					    "width": widthSelected,
					    "scaleX": '0.5',
					    "scaleY": '0.5', 
					    "opacity": 0
					}, 500, 'ease', function(){
						
						isVisible = false;
						quickView.removeClass('wooqv-no-transitions');	
						quickView.removeClass('wooqv-is-visible');
						parentListItem.removeClass('empty-box');
						
						$(document.body).trigger('wooqv-animation-end');
						
						if(typeof(callback) !== 'undefined') {
							callback()
						}
							
					});
				});
				
				isVisible = false;
				recentProduct = null;
				recentVariation = null;
			}
		}
		
		function closeNoAnimation(image, finalWidth, maxQuickWidth, callback) {
			
			image = image.length ? image : $('.empty-box');
			
			var parentListItem = image.closest('.product'),
				topSelected = image.offset().top - $(window).scrollTop(),
				leftSelected = image.offset().left,
				widthSelected = image.width();
	
			//close the quick view reverting the animation
			$('html').removeClass('wooqv-active');
			parentListItem.removeClass('empty-box');
			quickView.velocity("stop").removeClass('wooqv-add-content wooqv-no-transitions wooqv-animate-width wooqv-is-visible').css({
				"top": topSelected,
			    "left": leftSelected,
			    "width": widthSelected,
			});
			isVisible = false;
			resizeQuickView();
			
			if(typeof(callback) !== 'undefined') {
				callback()
			}
		}

		function loadProductInfo(id, variation_id, callback) {
			
			variation_id = variation_id ? variation_id : 0;
			
			recentProduct = recentProduct ? recentProduct : 0;
			recentVariation = recentVariation ? recentVariation : 0;
			
			var hash = parseInt(id) + parseInt(variation_id);
			var recent_hash = parseInt(recentProduct) + parseInt(recentVariation);
			
			if(hash === recent_hash) {
	
				if(typeof(callback) !== 'undefined') {
					callback(false);
				}
				return false;			
			}

			if(animationComplete) {
				
				if(variation_id || isVisible) {
					
					quickView.find('.wooqv-slider-wrapper').block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.4
						}
					});
					
				}else{
					
					quickView.block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 1
						}
					});
				}
				
			}else{
				
				$('html').addClass('wooqv-loading');
			}

			var params = {
				action: 'wooqv_quick_view',
				id: id,
				variation_id: variation_id,
				slider_only: (variation_id || isVisible) ? 1 : 0
			};
			
			resizeQuickView();
			
			$.ajax({
				url: WOOQV.ajaxurl,
				data: params,
				type: 'post', 
				success: function(data) {
					
					if(quickView.height() > 0) {
						quickView.css('height', quickView.height()+'px');
					}
						
					if(variation_id || isVisible) {
						
						quickView.find('.wooqv-slider-wrapper').replaceWith($(data.fragment));
						
					}else{
						
						quickView.find('.wooqv-product').replaceWith($(data.fragment));
					}
					
					onProductLoaded(id, variation_id, data, callback);

				}
			});
			
		}
		
		function onProductLoaded(id, variation_id, data, callback) {
				
			recentProduct = id;
			recentVariation = variation_id;
		
			if(customizer) {
				recentProduct = null;
				recentVariation = null;
				customizerValuesChanged();
			}
			
			initPrettyPhoto();
			initLightSlider(data, callback);
			checkNavigation();
			
			$(document.body).trigger('wooqv-product-loaded');
			
		    $('html').removeClass('wooqv-loading');
		    
			setTimeout(function() {
				quickView.css('height', '');
				quickView.find('.wooqv-slider-wrapper').unblock();
				quickView.unblock();
				
			}, 100);
			
		}

		
		function initPrettyPhoto() {
			
			if(!lightBoxEnabled) {
				quickView.find(".wooqv-slider-wrapper .wooqv-slider li a").on('click', function(e) {
					e.preventDefault();
				});
				return false;
			}
			
			// Lightbox
			quickView.find(".wooqv-slider-wrapper a[data-rel^='prettyPhoto']").prettyPhoto({
			    hook: 'data-rel',
			    social_tools: false,
			    theme: 'pp_woocommerce',
			    horizontal_padding: 20,
			    opacity: 0.8,
			    deeplinking: false
			});

		}
		
		function checkNavigation() {
			
			if(isFirstProduct()) {
				$('html').addClass('wooqv-first-product');
			}else{
				$('html').removeClass('wooqv-first-product');
			}
		
			if(isLastProduct()) {
				$('html').addClass('wooqv-last-product');
			}else{
				$('html').removeClass('wooqv-last-product');
			}
		}

		function initVariationsEvents() {
			
			if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
				quickView.find( '.variations_form' ).each( function() {
					
					$( this ).wc_variation_form();
					$( this ).off('found_variation', onFoundVariation );
					$( this ).find('.reset_variations').off('click', onResetVariation );
					$( this ).on('found_variation', onFoundVariation );
					$( this ).find('.reset_variations').on('click', onResetVariation );
				});
			}
		}
		
		function onFoundVariation ( event, variation ) {

			loadVariation(variation);
		}
		
		function onResetVariation(event) {
			
			loadVariation();
		}
		
		function loadVariation(variation) {
			
			var product = getRecentProduct();
			var id = product.find('.wooqv-trigger').data('id')
			var variation_id = variation ? variation.variation_id : 0;
	
			loadProductInfo(id, variation_id, function(data) {
				
				if(data) {
					refreshLightSlider();
					resizeQuickView();
					
					if(currentSlider) {
						currentSlider.goToSlide(1);
					}
				}
				
			});
		}
		
		function initLightSlider(data, callback) {
			
			var attachments = parseInt(quickView.find(".wooqv-slider-wrapper").attr('data-attachments'));
			if(attachments <= 1) {
				if(typeof(callback) !== 'undefined') {
					callback(data);
				}
				return false;	
			}
			
			currentSlider = quickView.find('.wooqv-slider').lightSlider({
		        gallery: (sliderGallery === 1),
		        mode: sliderAnimation,
		        auto: (sliderAutoPlay === 1),
		        pauseOnHover: true,
		        item:1,
		        loop:true,
		        thumbItem: sliderGalleryThumbs,
		        thumbMargin: 0,
		        slideMargin:0,
		        currentPagerPosition:'left',
		        controls: (sliderArrowsEnabled === 1),
		        prevHtml: '<span class="wooqv-arrow-icon '+sliderArrow+'"></span>',
		        nextHtml: '<span class="wooqv-arrow-icon '+sliderArrow+'"></span>',
		        onSliderLoad: function() {
			        			
					if(typeof(callback) !== 'undefined') {
						callback(data);
					}
		        }  
		    });
		}
		
		function previousProduct() {
			
			if(!isVisible || !recentProduct) {
				return false
			}
			
			var product = getRecentProduct().prev();
			
			triggerProductQuickView(product);
		}
		
		function nextProduct() {
			
			if(!isVisible || !recentProduct) {
				return false
			}
			
			var product = getRecentProduct().next();
			
			triggerProductQuickView(product);
		}
		
		function isFirstProduct() {
			
			if(!isVisible || !recentProduct) {
				return false
			}
			
			return recentProduct === getFirstProductId();
		}
		
		function isLastProduct() {
			
			if(!isVisible || !recentProduct) {
				return false
			}
			
			return recentProduct === getLastProductId();
		}
		
		function getRecentProduct() {
			
			return $('.wooqv-trigger[data-id='+recentProduct+']').closest('.product');
		}
		
		function getFirstProductId() {
			
			return $('.product:first-child .wooqv-trigger').data('id');
		}
		
		function getLastProductId() {
			
			return $('.product:last-child .wooqv-trigger').data('id');
		}
		
		function triggerProductQuickView(product) {
		
			if(product.find('.wooqv-trigger').length) {
				
				var id = product.find('.wooqv-trigger').data('id');
					
				isVisible = false;
				recentProduct = null;
				recentVariation = null;
				loadProductInfo(id, null, function(data) {
					if(data) {
						refreshLightSlider();
						resizeQuickView();
					}
					isVisible = true;
				});
			}	
		}
		
		function refreshLightSlider() {
			
			if(!isTouchDevice && currentSlider) {
				currentSlider.refresh();
			}
		}
		
		function touchSupport() {
			
			if ("ontouchstart" in document.documentElement) {
				$('html').addClass('wooqv-touchevents');
				return true;
			}
			
			$('html').addClass('wooqv-no-touchevents');
			
			return false;
		}	
		
		$(document).on('ready', function() {
					
			initVars();
			updateResponsiveVars();
			initEvents();
			resizeQuickView();
	
		});
		
		window.wooqv_resize = resizeQuickView;
		window.wooqv_previous = previousProduct;
		window.wooqv_is_first = isFirstProduct;
		window.wooqv_is_last = isLastProduct;
		window.wooqv_next = nextProduct;
	});


})( jQuery );
