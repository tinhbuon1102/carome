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
	// tab
	$('ul.tabs').parent().addClass('tab-box');

	$('.tab-box').each(function() {
		$(this).find('.tab_content').hide();
		var tabCon = $(this).find('.tabs li');
		if ($(tabCon).hasClass('active')) {
			var selectedTab = $(tabCon).find('a').attr("href");
			$(selectedTab).show();
		} else {
			/*
			 * $('.tabs li:first').addClass('active');
			 * $('.tab_content:first').show();
			 */
		}
		$('.tabs li').click(function(event) {
			$('.tabs li').removeClass('active');
			$(this).addClass('active');
			$('.tab_content').hide();

			var selectTab = $(this).find('a').attr("href");

			$(selectTab).fadeIn();
		});
	});

});