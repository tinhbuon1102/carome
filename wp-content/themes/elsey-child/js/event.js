jQuery(document).ready(
function($) {
	if (!user_agree_to_check_location) {
		var inst = $.remodal.lookup[$('[data-remodal-id=beforeenter]')
				.data('remodal')];
		// открыть модальное окно
		inst.open();
	}

	$(document).on('click', '.check-location-button', function() {
		$(this).addClass('clicked');
		getMyPlace(true);
	});
	$('.openNext').click(function() {
    $('#instOp').toggle('slow');
	});
	// tab
	$('ul.tabs').parent().addClass('tab-box');

	$('.event-modal-content').each(function() {
		var tab_box = $(this).find('.tab-box');
		tab_box.find('ul.tabs li').each(function(){
			var content_tab_id = $(this).find('a').attr('href');
			if ($(this).hasClass('active'))
			{
				$(content_tab_id).show();
			}
			else {
				$(content_tab_id).hide();
			}
		});
		
		
		$('.tabs li').click(function(event) {
			var tab_box = $(this).closest('.tab-box');
			tab_box.find('li').removeClass('active');
			// Set active current li
			$(this).addClass('active');
			
			tab_box.find('ul.tabs li').each(function(){
				var content_tab_id = $(this).find('a').attr('href');
				$(content_tab_id).hide();
				if ($(this).hasClass('active'))
				{
					$(content_tab_id).fadeIn();
				}
				else {
					$(content_tab_id).hide();
				}
			});
		});
	});

});