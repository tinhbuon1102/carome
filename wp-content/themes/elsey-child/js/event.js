jQuery(document).ready(function($){
	if (!user_agree_to_check_location)
	{
		var inst = $.remodal.lookup[$('[data-remodal-id=eventaccess]').data('remodal')];
		// открыть модальное окно
		inst.open();
	}
    
    $(document).on('click', '.check-location-button', function(){
    	$(this).addClass('clicked');
    	getMyPlace(true);
    });
	//tab
	  $('.tab_content').hide();
  $('.tab_content:first').show();
  $('.tabs li:first').addClass('active');
  $('.tabs li').click(function(event) {
    $('.tabs li').removeClass('active');
    $(this).addClass('active');
    $('.tab_content').hide();

    var selectTab = $(this).find('a').attr("href");

    $(selectTab).fadeIn();
  });
});