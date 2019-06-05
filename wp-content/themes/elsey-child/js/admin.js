jQuery(function($){
	var aWidget = [
		'#product_report_dashboard_widget',
		'#user_age_report_dashboard_widget',
		'#pre_order_report_dashboard_widget',
		'#pre_variation_order_report_dashboard_widget',
		
	];
	
	function getAjaxLoadTableList(element, widget)
	{
		var protocol = location.protocol;
		var slashes = protocol.concat("//");
		var host = slashes.concat(window.location.hostname);
		var gl_siteUrl = host + '/wp-admin/admin-ajax.php'
		$(widget).LoadingOverlay('show');
		$.ajax({
	        type: "post",
	        url: gl_siteUrl,
	        data: {action: 'load_table_list_widget_dashboard', url: element.attr('href'), wg: widget},
	        crossDomain: false,
	        dataType : "html",
	        scriptCharset: 'utf-8'
	    }).done(function(data){
	    	$(widget).find('> .inside').html(data);
	    	$(widget).LoadingOverlay('hide');
	    });		
	}
	
	$.each(aWidget, function(index, element){
		
		$('body').on('click', element + ' .pagination-links a', function(e){
			e.preventDefault();
			getAjaxLoadTableList($(this), element)
		})
	});
	
	if ($('#load_member_age_btn').length)
	{
		var current_load_age_btn = '';
		function load_member_age_by_order(offset, final)
		{
			var protocol = location.protocol;
			var slashes = protocol.concat("//");
			var host = slashes.concat(window.location.hostname);
			var gl_siteUrl = host + '/wp-admin/admin-ajax.php'
			$.ajax({
		        type: "post",
		        url: gl_siteUrl,
		        data: {action: 'load_member_age_by_order', offset: offset, final: final},
		        crossDomain: false,
		        dataType : "json",
		        scriptCharset: 'utf-8'
		    }).done(function(data){
		    	if (!offset) $('#load_member_age_content').html('');
		    	
		    	if (data.end) {
		    		var current_btn = $('.load_member_age_btn.clicked');
		    		$(current_btn.attr('attr-trigger')).append(data.content)
		    		$('#member_age_date').html(data.date)
		    		$('#user_age_report_dashboard_widget').LoadingOverlay('hide');
		    		
		    		if ($('.load_member_age_btn:eq('+ (current_load_age_btn + 1) +')').length)
		    		{
		    			$('.load_member_age_btn:eq('+ (current_load_age_btn + 1) +')').trigger('click');
		    		}
		    	}
		    	else if (data.final) {
		    		load_member_age_by_order(data.offset, data.final)
		    	}
		    	else {
		    		load_member_age_by_order(data.offset)
		    	}
		    });
		}
		$('body').on('click', '.load_member_age_btn', function(){
			$('.load_member_age_btn').removeClass('clicked');
			$(this).addClass('clicked');
			
			current_load_age_btn = $(this).index();
			$('#user_age_report_dashboard_widget').LoadingOverlay('show');
			load_member_age_by_order(0)
		});
		
		$('body').on('click', '#load_member_age_btn', function(){
			$('.load_member_age_btn:eq(0)').trigger('click');
		});
	}
});