// JavaScript Document
jQuery(document).ready(function($){
	$(window).on('load resize click',function(){
		var windowW = $(window).width();
		var windowH = $(window).height();
		if (windowW < 768) {
			$('.header__secondary.toggle--active').css('height', (windowH - 62) + 'px');
		} else {
			$('.header__secondary.toggle--active').css('height', 'auto');
		}
	});
	/*Tabs Jquery*/
	$('[data-add-class]').each(function(){
    var class_to_add = $(this).attr('data-add-class');
    // Then take the value from this attribute and add it into the class list.
    $(this).addClass(class_to_add);
  });
  $.simpleTicker($("#ticker"),{'effectType':'fade'});
  // Scour the elements in the DOM for the existence of a 'data-toggle' attribute.
  $('[data-toggle]').on('click', function(e){
    // The target is the attribute value, a CSS selector
    var target = $(this).attr('data-toggle');
    // ...and toggle its collapsed class.
		$(target).toggleClass('js_collapsed');
    // Prevent default behaviour (if required)
		e.preventDefault();
	});
	
  	$('#customer_login form input[type="submit"]').click(function(e) {
  	  e.preventDefault();
  	  var form = $(this).closest('form');
  	  form.append('<input type="hidden" name="'+ ($(this).attr('name')) +'" value="'+ $(this).attr('value') +'" />');
	  if( form.smkValidate() ){
		  form.submit();
	  }
	});
  	
  	if ($('#billing_birth_year').length)
  	{
  		$('#billing_birth_year').select2();
  		$('#billing_birth_month').select2();
  		$('#billing_birth_day').select2();
  	}
  	
  	if ($('#birth_year').length)
  	{
  		$('#birth_year').select2();
  		$('#birth_month').select2();
  		$('#birth_day').select2();
  	}

  	if ($('form.woocommerce-checkout').length)
  	{
  		jQuery('form.woocommerce-checkout').on( 'checkout_place_order', function(){
  			$('form.woocommerce-checkout .validate-required').each(function(){
  	  			$(this).addClass('form-group');
  	  			$(this).find('input:visible').attr('required', true);
  	  			$(this).find('select:visible').attr('required', true);
  	  			$(this).find('textarea:visible').attr('required', true);
  	  			
  	  			$(this).find('input:hidden').attr('required', false);
	  			$(this).find('select:hidden').attr('required', false);
	  			$(this).find('textarea:hidden').attr('required', false);
  	  		});
  			
  			if (!jQuery('form.woocommerce-checkout').smkValidate())
  			{
  				return false;
  			}
  			else {
  				return true;
  			}
  		});
  		
  	}
  
	$('h2.icon--plus').on("click", function() {
		$('.order--checkout__col--summary').toggleClass('toggle--active');
		$(this).toggleClass('toggle--active');
		$('.order--checkout__summary').toggleClass('toggle--active');
		$('.account__sidebar').toggleClass('toggle--active');
		$('.account__nav').toggleClass('toggle--active');
	});
	
	$('#els-cart-trigger-sticky').on("click", function() {
		$('#els-shopping-cart-content .widget_shopping_cart_content').toggleClass('els-cart-popup-open');
		$('#els-shopping-cart-content-sticky .widget_shopping_cart_content').toggleClass('els-cart-popup-open');
		$('#els-shopping-cart-content-sticky').toggleClass('toggle--active');
		$('#els-shopping-cart-content').toggleClass('toggle--active');
		$('.focus-overlay').toggleClass('set--active');
	});
	
	$('#els-cart-trigger').on("click", function() {
		$('#els-shopping-cart-content-sticky .widget_shopping_cart_content').toggleClass('els-cart-popup-open');
		$('#els-shopping-cart-content').toggleClass('toggle--active');
		$('#els-shopping-cart-content-sticky').toggleClass('toggle--active');
		$('.focus-overlay').toggleClass('set--active');
	});
	$(".focus-overlay").unbind().click(function() {
		$('#els-shopping-cart-content .widget_shopping_cart_content').toggleClass('els-cart-popup-open');
		$('#els-shopping-cart-content-sticky .widget_shopping_cart_content').toggleClass('els-cart-popup-open');
		$('#els-shopping-cart-content').toggleClass('toggle--active');
		$('#els-shopping-cart-content-sticky').toggleClass('toggle--active');
		$('.focus-overlay').toggleClass('set--active');
	});
	$('.header__mobile-trigger').on("click", function() {
		$(this).toggleClass('toggle--active');
		$('.header__primary').toggleClass('toggle--active');
		$('.header__secondary').toggleClass('toggle--active');
	});
	$('footer > .footer__container > .container > .row > div:not(:last-child)').on("click", function(e) {
			$(this).toggleClass('toggle--active');
			$(this).find('.main-navigation').toggleClass('toggle--active');
			$(this).find('.widget-title').toggleClass('toggle--active');
	});
	
	$('body').on('click', '.waitlist_remove_product', function(e){
		e.preventDefault();
		var element = $(this);
		$.ajax({
	        type: "post",
	        url: gl_siteUrl + element.attr('href'),
	        crossDomain: false,
	        dataType : "html",
	        scriptCharset: 'utf-8'
	    }).done(function(data){
	    	element.closest('.wishlist__item').fadeOut(function(){
	    		$(this).remove();
	    	});
	    });
	})


  	
  $('body').on('click', 'p.link .ico_modal', function(e){
	  e.preventDefault();
	  var inst = $('[data-remodal-id=chart_size_modal]').remodal(); 
	  if ($('#tab-custom_tab h2#modal1Title').length)
	  {
		  $('#chart_popup_content').html($('#tab-custom_tab h2#modal1Title'));
		  $('#chart_popup_content').append($('#tab-custom_tab .chart-1-image'));
		  $('#chart_popup_content #modal1Title').show();
		  $('#chart_popup_content .chart-1-image').show();
	  }
	  inst.open();

  });
  
  $('form.variations_form.cart').on('show_variation hide_variation', function(){
		var form = $('form.variations_form.cart');
		variations = jQuery.parseJSON(form.attr('data-product_variations'));
		$.each(variations, function(index, variation){
			if (variation.variation_id == $('.input-list .variation_id').val())
			{
				if (variation.is_in_stock == true)
				{
					$('.els-product-stock-status').hide();
					$('.els-product-stock-status .els-avl').removeClass('els-out-of-stock');
					$('.els-product-stock-status .els-avl').addClass('els-in-stock');
					$('.els-product-stock-status span').text('IN STOCK');
					$('.woocommerce-variation-add-to-cart').removeClass('soldout_disabled');
				}
				else {
					//addOutStockText();
				}
			}
		})
	});
  
  function addOutStockText()
  {
	  $('.els-product-stock-status').fadeIn();
		$('.els-product-stock-status .els-avl').removeClass('els-in-stock');
		$('.els-product-stock-status .els-avl').addClass('els-out-of-stock');
		$('.els-product-stock-status').html('<span class="soldout_text">SOLD OUT</span>');
		$('.woocommerce-variation-add-to-cart').addClass('soldout_disabled');
  }
  
  function addWaitListButton(){
	  var form = $('form.variations_form.cart');
	  if (!form.length) return ;
	  
	  var attrOutStock = {};
		variations = jQuery.parseJSON(form.attr('data-product_variations'));
		
		var allOutStock = true;
		$.each(variations, function(index, variation){
			if (variation.is_in_stock == false)
			{
				$('#woocommerce_waitlist_wraper').show();
				
				$.each(variation.attributes, function(att_name, att_value){
					if (att_value)
					{
						attrOutStock[att_name] = attrOutStock[att_name] ? attrOutStock[att_name] : []
						if (attrOutStock[att_name].indexOf(att_value) == -1)
						{
							attrOutStock[att_name].push(att_value);
						}
					}
				});
				
			}
			else {
				allOutStock = false;
			}
		});
		
		if (allOutStock)
		{
			addOutStockText();
		}
		
		$('#waitlist_remodal_content').html($('.variations_form .pdp__attribute--list.variations').clone());
		var variation_html = $('#waitlist_remodal_content .pdp__attribute--list.variations');
		variation_html.find('select.zoo-cw-attribute-select').remove();
		variation_html.find('.zoo-cw-attribute-option').remove();
		variation_html.find('.variations__attribute__value').attr('class', 'value variations__attribute__value dropdown');
		
		variation_html.find('select').each(function(index_select, select){
			var select_name = $(select).attr('name');
			if (!attrOutStock[select_name])
			{
				$(select).closest('.variations__attribute').remove();
			}
			else {
				$(select).find('option').each(function(index_option, option){
					var option_value = $(option).attr('value');
					if (option_value && attrOutStock[select_name].indexOf(option_value) == -1)
					{
						$(option).remove();
					}
				});
			}
			$(select).off('change');
			$(select).on('change', function(){
				$('#waitlist_remodal .waitlist_error').fadeOut();
				$('.variations_form.cart select[name="'+ $(this).attr('name') +'"]').val($(this).val()).trigger('change');
			});
		});
  }
  
  $('body').on('click', '#woocommerce_waitlist_wraper a.woocommerce_waitlist_new.join', function(e){
	  e.preventDefault();
	  //get variation dropdowns
	  $('#waitlist_remodal .waitlist_error').hide();
	  var inst = $('[data-remodal-id=waitlist_remodal]').remodal();
	  inst.open();
  });
  
  $('body').on('click', '#submit_add_waitlist', function(){
	  var variation_html = $('#waitlist_remodal_content .pdp__attribute--list.variations');
	  var iSelectedCount = 0;
		variation_html.find('select').each(function(index_select, select){
			
			$(select).attr('required', true);
			$(select).addClass('form-control');
			$(select).closest('.variations__attribute__value').addClass('form-group');
			$(select).trigger('change');
			
			if ($(select).val())
			{
				iSelectedCount++;
			}
		});
		
		// trigger the original waitlist fields
		$('#wcwl_email').val($('#wcwl_email_new').val());
		
		var joinBtn = $('.woocommerce_waitlist.join');
		
		if ($('#form_waitlist_modal').smkValidate())
		{
			if ($(joinBtn).length)
			{
				$(joinBtn).trigger('click');
				
				if( $( '#wcwl_email' ).length > 0 ) {
                    window.location.href = $(joinBtn).attr( 'href' ) + '&wcwl_email=' + $( '#wcwl_email' ).val();
	            }
				else {
					window.location.href = $(joinBtn).attr( 'href' );
				}
			}
			else {
				$('#waitlist_remodal .waitlist_error').html(already_in_waitlist);
				$('#waitlist_remodal .waitlist_error').fadeIn();
			}
		}
  });
  
  function disableVariationOption()
  {
	  $( '.variations select option' ).each( function( index, el ) {
	      var sold_out = 'SOLD OUT';
	      var re = new RegExp( ' - ' + sold_out + '$' );
	      el = $( el );

	      if ( el.is( ':disabled' ) ) {
	        if ( ! el.html().match( re ) ) el.html( el.html() + ' - ' + sold_out );
	      } else {
	        if ( el.html().match( re ) ) el.html( el.html().replace( re,'' ) );
	      }
	    } );

  }
  
  setTimeout(function(){
	$( document ).bind( 'woocommerce_update_variation_values', function() {
//		disableVariationOption();
	});
	  
	if ($('.els-out-of-stock').length)
	{
		  $('.woocommerce-variation-add-to-cart').addClass('soldout_disabled');
	}
	
	if ($('#calc_shipping_country_field').length)
	{
	  if ($('#calc_shipping_country option').length <= 2) {
		  $('#calc_shipping_country').val('JP').val();
	  }
	  else {
		  $('#calc_shipping_country_field').show();
	  }
	  
	}
	
	if ($('.woocommerce-MyAccount-content').length)
	  {
		  var select = $('.select2-hidden-accessible');
		  if (!select.val())
		  {
			  var emptyText = select.find('option:eq(0)').text();
			  select.closest('.address-field').find('.select2-selection__placeholder').text(emptyText);
		  }
	  }
	
	addWaitListButton();
  }, 400);
  
  $(this).on('click', '.qty-input .plus', function(e) {
      $input = $(this).closest('.qty-input').find('input[type="text"]');
      var val = parseInt($input.val());
      var step = $input.attr('step');
      step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
      $input.val( val + step ).change();
  });
  
  $.fn.autoKana('#billing_first_name', '#billing_first_name_kana');
  $.fn.autoKana('#billing_last_name', '#billing_last_name_kana');
  
  $.fn.autoKana('#shipping_first_name', '#shipping_first_name_kana');
  $.fn.autoKana('#shipping_last_name', '#shipping_last_name_kana');
  
  $.fn.autoKana('#account_first_name', '#account_first_name_kana');
  $.fn.autoKana('#account_last_name', '#account_last_name_kana');
  
  $('body').on('change', '#deliver_postcode, #billing_postcode, #shipping_postcode', function(){
		var zip1 = $.trim($(this).val());
	    var zipcode = zip1;
	    var elementChange = $(this);
	    
	    // Remove error message about postcode
	    $('.postcode_fail').remove();

	    $.ajax({
	        type: "post",
	        url: gl_siteUrl + "/dataAddress/api.php",
	        data: JSON.stringify(zipcode),
	        crossDomain: false,
	        dataType : "jsonp",
	        scriptCharset: 'utf-8'
	    }).done(function(data){
	    	var address = [
	    		{postcode : '#deliver_postcode', state : '#deliver_state', city: '#deliver_city', address1: '#deliver_addr1'},
	    		{postcode : '#billing_postcode', state : '#billing_state', city: '#billing_city', address1: '#billing_address_1'},
	    		{postcode : '#shipping_postcode', state : '#shipping_state', city: '#shipping_city', address1: '#shipping_address_1'},
	    	]
	    	
	        if(false && (data[0] == "" || gl_stateAllowed.indexOf(data[0]) == -1)){
	        	if (data[0] != "" && gl_stateAllowed.indexOf(data[0]) == -1)
	        	{
	        		var alertElement = '<span style="display: block" class="woocommerce-error postcode_fail clear">'+ gl_alertStateNotAllowed +'</span>';
	        		elementChange.parent().append(alertElement);
	        	}
	        	$.each(address, function(index, addressItem){
	        		$(addressItem['postcode']).val('');
	        		$(addressItem['state']).val('');
	        		$(addressItem['city']).val('');
	        		$(addressItem['address1']).val('');
	        	});
	        	
	        } else {
	    		$.each(address, function(index, addressItem){
	        		if ($(addressItem['postcode']).length && ('#'+elementChange.attr('id') == addressItem['postcode']))
	        		{
	        			$(addressItem['state'] + ' option').each(function(){
	                		if($(this).text() == data[0])
	                		{
	                			$(addressItem['state']).val($(this).attr('value'));
	                			$(addressItem['state']).change();
	                		}
	                	});
	                	
	                    $(addressItem['city']).val(data[1] + data[2]);
//	                    var address1 = $(addressItem['address1']).val();
//	                    address1 = address1.replace(data[2], '');
//	                    $(addressItem['address1']).val(data[2] + address1);
	        		}
	        	});
	        }
	    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
	    });
	});
  
  $(this).on('click', '.qty-input .minus', function(e) {
	  $input = $(this).closest('.qty-input').find('input[type="text"]');
      var val = parseInt($input.val());
      var step = $input.attr('step');
      step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
      if (val > 1) {
          $input.val( val - step ).change();
      } 
  });
  
  $('body').on('click', '#remove_cc_card', function(e){
	  e.preventDefault();
	  if(confirm($(this).attr('data-message'))) {
		  $.ajax({
			  type: "post",
			  url: woocommerce_params.ajax_url,
			  crossDomain: false,
			  dataType : "json",
			  data: {action: "removed_epsilon_method"}
		  }).done(function(data){
			  location.reload();
		  });
	  }
   });
  
  if ($('.retal_kimono_form').length)
  {
	  $(document).on('click', '#retal_confirm_btn', function(){
		  $("form.retal_kimono_form").validationEngine({promptPosition: 'inline', addFailureCssClassToField: "inputError", bindMethod:"live"});
			var isFormValid = $("form.retal_kimono_form").validationEngine('validate');
			if (isFormValid)
			{
				var form = $(this).closest('form');
				var row_html = '';
				var aLabels = [];
				form.find('.form-row').each(function(){
					var row = $(this);
					var label = ''
					var label_row = row.closest('.form-row').find('label:eq(0)').text().replace('*', '');
					var aTextVal = [];
					row.find('input[type="checkbox"]:checked, input[type="text"], input[type="date"], input[type="email"], input[type="tel"], select, textarea').each(function(){
						var label_col = $(this).closest('div').find('label:eq(0)').text().replace('*', '');
						var text_val = $(this).val().replace(/(?:\r\n|\r|\n)/g, '<br />');
						label = label_col ? label_col : label_row;
						text_val = text_val.replace(/<br \/>/g, '<br />');
						aTextVal.push(text_val.replace(/<br \/>/g, '<br />'));
						if (!row.hasClass('date-wraper'))
						{
							row_html += '<div class="form-list"><label>'+ label +' : </label><span class="wpcf7-form-control-wrap">'+ text_val +'</span></div>'
						}
					});
					if (row.hasClass('date-wraper'))
					{
						row_html += '<div class="form-list"><label>'+ label +' : </label><span class="wpcf7-form-control-wrap">'+ aTextVal.join('-') +'</span></div>'
					}
				});
				
				$('#retal_kimono_popup_content .form-theme').html(row_html);
				
				var inst = $('[data-remodal-id=retal_kimono_popup]').remodal();
				inst.open();
			}
		});
	  
	  $(document).on('click', '.submit_confirm', function(){
		  $('body').LoadingOverlay('show');
		  $.ajax({
		        type: "post",
		        url: gl_siteUrl + woocommerce_params.ajax_url + '?action=retal_submition',
		        data: $('form.retal_kimono_form').serialize(),
		        crossDomain: false,
		        dataType : "json",
		        scriptCharset: 'utf-8'
		    }).done(function(response){
		    		$('body').LoadingOverlay('hide');
		    		if (response.success)
		    		{
		    			location.href = response.redirect;
		    		}
		    		else {
		    			alert('Email server got issue, please try again later or contact Site admin!');
		    		}
		    }); 
	  });
	  
	  $(document).on('change', '.select-month', function(){
		  var date_row = $(this).closest('.date-wraper');
		  var year_field = date_row.find('.select-year');
		  var month_field = $(this);
		  var day_field = date_row.find('.select-day');
		  if (parseInt(year_field.val()) == min_year && parseInt(month_field.val()) == min_month)
		  {
			  // Set min day for day selector
			  day_field.find('option').each(function(){
				 var day_val = parseInt($(this).val());
				  if (day_val < min_day) 
				  {
					  $(this).hide();
				  }
			  });
		  }
		  else {
			  day_field.find('option').show();
		  }
	  });
  }
  // Contact form 
  if ($('.wpcf7 select[name="contact-type"]').length)
  {
	  $(document).on('change', '.wpcf7 select[name="contact-type"]', function(){
		 if ($(this).val() == '不良品の返品・交換について') 
		 {
			 $('.contact_file_wraper').fadeIn("slow", "linear");
			 $('#contact_file').addClass('wpcf7-validates-as-required');
			 $('#contact_file').attr('aria-required', 'true');
		 }
		 else {
			 $('.contact_file_wraper').fadeOut("slow", "linear");
			 $('#contact_file').removeClass('wpcf7-validates-as-required');
			 $('#contact_file').attr('aria-required', 'false');
		 }
	  });
  }
  
  document.addEventListener( 'wpcf7mailsent', function( event ) {
	  $('.contact_file_wraper').fadeOut("slow", "linear");
	}, false );
	
	$(document).on('opening', '.remodal', function () {
    console.log('opening');
  });

  $(document).on('opened', '.remodal', function () {
    console.log('opened');
  });

  $(document).on('closing', '.remodal', function (e) {
    console.log('closing' + (e.reason ? ', reason: ' + e.reason : ''));
  });

  $(document).on('closed', '.remodal', function (e) {
    console.log('closed' + (e.reason ? ', reason: ' + e.reason : ''));
  });
});