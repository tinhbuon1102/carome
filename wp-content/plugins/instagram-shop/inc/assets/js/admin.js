jQuery(function($){
	function initTypeHead() {
		$.typeahead({
			input : '[name="item_sku"]',
			minLength : 2,
			maxItem : 300,
			order : "asc",
			accent: true,
			highlight: 'any',
			dropdownFilter : false,
			dynamic: true,
			templateValue: '{{name}}',
			emptyTemplate: message_no_result,
		    correlativeTemplate: true,
		    maxItemPerGroup: 300,
			group : {
				key : "group_name",
				template : function(item) {
					var group = item.group_name;
					group = group.toUpperCase();
					return group;
				}
			},

			source : {
				product : {
					display: 'name',
					//href: '{{url}}',
					template: '<span class="searching-results">' +
						'<span class="col-md-12 result-column"><span class="image">{{image}}</span><span class="name">{{name}}</span></span>',
					ajax : function(query){
						$('input[name="item_sku"]').val(query);
						$('#product_id').val('');
						
						// Get excludes id
						var excludes = '';
					    $('#related_items_list li').each(function(){
						  excludes += '&exclude[]=' + $(this).attr('data-id'); 
					    });
					    
						return {
							type: 'GET',
							data: $('#insta_content_form').serialize(),
							url : object_data.ajax_url + "?term="+ query +"&action=get_search_product" + excludes,
							path : "data.product"
						}
					}
				}
			},
			callback : {
				onClickAfter: function (node, a, item, event) {
		            event.preventDefault();
		            $('#product_id').val(item.id);
		        }
			},
			debug : false
		});
	}
	
	function get_ajax_list(max_id){
		var params = {count: LIMIT_INSTA_IMAGE, path: "/v1/users/" + INSTA_ID + "/media/recent/"};
		if (max_id)
		{
			params.max_id = max_id;
		}
		jQuery('#instagram_list_admin').LoadingOverlay('show');
		$.ajax({
			  dataType: "json",
			  url: MY_SITE_URL + '/wp-content/plugins/instagram-shop/api/',
			  data: params,
			  success: function(response){
				  if (response.data && response.data.length > 0)
				  {
					  var insta_list = '';
					  $.each(response.data, function(index, item){
						  if (item) {
							  var related_number = (insta_related_products[item.code] && insta_related_products[item.code]['products']) ? Object.keys(insta_related_products[item.code]['products']).length : 0;
							  var hide_post = insta_related_products[item.code] && insta_related_products[item.code]['hide'] && insta_related_products[item.code]['hide'] == 1 ? true : false;
							  insta_list += '<li class="'+ (hide_post ? "hide-post" : "") +'">';
							  insta_list += '<img src="'+ item.images.__original.url +'"/>';
							  
							  insta_list += '<div>';
							  insta_list += '<a class="edit_insta" href="javascript:void(0)" data-insta-id="'+ item.code +'">Edit</a>';
							  insta_list += '<a class="hide_insta" href="javascript:void(0)" data-insta-id="'+ item.code +'">'+ (hide_post ? "Un-hide" : "Hide") +'</a>';
							  insta_list += '</div>';
							  
							  insta_list += '<span class="related_number '+ (related_number ? 'has_related' : '') +'" data-insta-id="'+ item.code +'">'+ related_number +'</span>';
							  insta_list += '</li>';
						  }
					  });
					  if (max_id)
					  {
						  $('#instagram_list_admin').append(insta_list);
					  }
					  else {
						  $('#instagram_list_admin').html(insta_list);
					  }
					  
					  jQuery('#instagram_list_admin').LoadingOverlay('hide');
					  
					  // Loadmore
					  if (response.pagination && response.pagination.next_url)
					  {
						  $('#instagram_list_more').show();
						  $('#instagram_list_more').attr('next_max_id', response.pagination.next_max_id);
					  }
					  else {
						  $('#instagram_list_more').hide();
						  jQuery('#instagram_list_more').attr('next_max_id', 0);
					  }
				  }
			  },
			  error: function(response){jQuery('#instagram_list_admin').LoadingOverlay('hide');}
		});
	}
	
	$('body').on('click', '#instagram_list_more', function(e){
		get_ajax_list($(this).attr('next_max_id'));
	});
	
	$('body').on('click', '#instagram_list_admin a.edit_insta', function(e){
		  e.preventDefault();
		  var inst = $('[data-remodal-id=modal_instagram]').remodal(); 
		  var image = $(this).closest('li').find('img').clone();
		  
		  $('#modal_insta_content').hide();
		  $('body').LoadingOverlay('show');
		  $('#item_sku').val('');
		  $('#insta_id').val($(this).attr('data-insta-id'));
		  
		  params = {action: 'get_insta_form', insta_id: $(this).attr('data-insta-id')};
		  $.ajax({
			  dataType: "json",
			  type: 'post',
			  url: object_data.ajax_url,
			  data: params,
			  success: function(response){
				  $('#modal_insta_content .insta-left').html(image);
				  $('#modal_insta_content .insta-right #related_items_list').html(response.html);
				  jQuery('body').LoadingOverlay('hide');
				  $('#modal_insta_content').show();
				  inst.open();
			  },
			  error: function(response){jQuery('body').LoadingOverlay('hide')}
		});
	});
	
	$('body').on('click', '#instagram_list_admin a.hide_insta', function(e){
		  var eleClick = $(this);
		  e.preventDefault();
		  $('body').LoadingOverlay('show');
		  
		  params = {action: 'hide_insta_post', insta_id: $(this).attr('data-insta-id')};
		  $.ajax({
			  dataType: "json",
			  type: 'post',
			  url: object_data.ajax_url,
			  data: params,
			  success: function(response){
				  if (response.hide)
				  {
					  eleClick.text('Un-hide');
					  eleClick.closest('li').addClass('hide-post');
				  }
				  else {
					  eleClick.text('Hide');
					  eleClick.closest('li').removeClass('hide-post');
				  }
				  jQuery('body').LoadingOverlay('hide');
			  },
			  error: function(response){jQuery('body').LoadingOverlay('hide')}
		});
	});
	
	$('body').on('click', '#btn_add_related_product', function(e){
		  e.preventDefault();
		  
		  var product_id = $('#product_id').val();
		  var insta_id = $('#insta_id').val();
		  
		  if (!product_id) return ;
		  
		  $('body').LoadingOverlay('show');
		  $('#item_sku').val('');
		  
		  params = {action: 'add_related_product', product_id: product_id, insta_id: insta_id};
		  $.ajax({
			  dataType: "json",
			  type: 'post',
			  url: object_data.ajax_url,
			  data: params,
			  success: function(response){
				  $('#modal_insta_content .insta-right #related_items_list').html(response.html);
				  
				  var related_number_element = $('.related_number[data-insta-id="'+ insta_id +'"]');
				  var related_number = parseInt(related_number_element.text());
				  related_number_element.addClass('has_related');
				  related_number_element.text(related_number + 1);
				  
				  jQuery('body').LoadingOverlay('hide');
				  $('#product_id').val('');
			  },
			  error: function(response){jQuery('body').LoadingOverlay('hide');}
		});
	});
	
	
	$('body').on('click', '#modal_insta_content .remove_related', function(e){
		  e.preventDefault();
		  
		  $('body').LoadingOverlay('show');
		  
		  var product_id = $(this).closest('li').attr('data-id');
		  var insta_id = $('#insta_id').val();
		  
		  params = {action: 'remove_related_product', product_id: product_id, insta_id: insta_id};
		  $.ajax({
			  dataType: "json",
			  type: 'post',
			  url: object_data.ajax_url,
			  data: params,
			  success: function(response){
				  $('#modal_insta_content .insta-right #related_items_list').html(response.html);
				  var related_number_element = $('.related_number[data-insta-id="'+ insta_id +'"]');
				  var related_number = parseInt(related_number_element.text());
				  related_number_element.text(related_number - 1);
				  related_number = parseInt(related_number_element.text());
				  if (related_number > 0)
				  {
					  related_number_element.addClass('has_related');
				  }
				  else {
					  related_number_element.removeClass('has_related');
				  }
				  
				  jQuery('body').LoadingOverlay('hide');
			  },
			  error: function(response){jQuery('body').LoadingOverlay('hide');}
		});
	});
	
	
	get_ajax_list();
	initTypeHead();
});