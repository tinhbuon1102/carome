// JavaScript Document
jQuery(document).ready(function($){
	/*Tabs Jquery*/
	$('[data-add-class]').each(function(){
    var class_to_add = $(this).attr('data-add-class');
    // Then take the value from this attribute and add it into the class list.
    $(this).addClass(class_to_add);
  });
  
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
					$('.els-product-stock-status').fadeOut();
					$('.els-product-stock-status .els-avl').removeClass('els-out-of-stock');
					$('.els-product-stock-status .els-avl').addClass('els-in-stock');
					$('.els-product-stock-status span').text('IN STOCK');
					$('.woocommerce-variation-add-to-cart').removeClass('soldout_disabled');
				}
				else {
					$('.els-product-stock-status').fadeIn();
					$('.els-product-stock-status .els-avl').removeClass('els-in-stock');
					$('.els-product-stock-status .els-avl').addClass('els-out-of-stock');
					$('.els-product-stock-status').html('<span class="soldout_text">SOLD OUT</span>');
					$('.woocommerce-variation-add-to-cart').addClass('soldout_disabled');
				}
			}
		})
	});
  
  function addWaitListButton(){
	  var form = $('form.variations_form.cart');
	  if (!form.length) return ;
	  
	  var attrOutStock = {};
		variations = jQuery.parseJSON(form.attr('data-product_variations'));
		
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
			}
		});
		
		$('#waitlist_remodal_content').html($('.variations_form .pdp__attribute--list.variations').clone());
		var variation_html = $('#waitlist_remodal_content .pdp__attribute--list.variations');
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
		
		var joinBtn = $('.woocommerce-variation-availability .woocommerce_waitlist.join');
		
		if ($('#form_waitlist_modal').smkValidate())
		{
			if ($(joinBtn).length)
			{
				$(joinBtn).trigger('click');
				location.href = $(joinBtn).attr('href');
			}
			else {
				$('#waitlist_remodal .waitlist_error').html(already_in_waitlist);
				$('#waitlist_remodal .waitlist_error').fadeIn();
			}
		}
  });
  
  setTimeout(function(){
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
});