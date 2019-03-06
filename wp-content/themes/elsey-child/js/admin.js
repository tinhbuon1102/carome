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
});