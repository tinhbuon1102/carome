jQuery(document).ready(function($) {
	AOS.init();
	$('.flw_slider').slick({
		arrows: false,
		dots: false,
		infinite: true,
		autoplay: true,
		speed: 300,
		slidesToShow: 1
	});
	$('a.disable_link').click(function(){
    return false;
	});
	$(window).on("load resize scroll",function(e){
		var $win = $(window),
			//$winH = $win.height(),
			winpos = $win.scrollTop(),
			$objH = $('.buy_now_flw').height();
			if($objH > winpos) {
				$('.buy_now_flw').css('bottom', '-' + $objH + 'px');
			} else {
				$('.buy_now_flw').css('bottom', '0px');
			}
	});
	$(window).load(function() {
		$("body").removeClass("preload");
		$('.loader-bg').addClass('loaded');
		$('.anim-fadein-top').addClass('fade-in-top');
		$('.ani-fadein-right').addClass('animated fadeInRight');
		$('.eyeliner_item_name').addClass('blink-1');
	});
	$(window).on('load resize',function(){
		var windowW = $(window).width();
		var windowH = $(window).height();
		var headerH = $('header.els-header').outerHeight();
		var headlineH = $('.news-headline').outerHeight();
		if (windowW > 768 && windowH < 700) {
			$('section.main_visual').css('height', '840px');
		} else if (windowW > 768 && windowH > 700) {
			$('section.main_visual').css('height', (windowH - headerH - headlineH) + 'px');
		} else {
			$('section.main_visual').css('height', (windowH - headerH - headlineH) + 'px');
			console.log('els-header' + headerH + 'px');
			console.log('news-headline' + headlineH + 'px');
			console.log('windowH' + windowH + 'px');
			console.log('windowH - totalhead' + windowH + '-' + headerH + '+' + headlineH + 'px');
		}
		//var eyeItemH = $('.eye-item-inner').innerHeight();
		var eyeDetH = $('.eye-detail-row').outerHeight();
		if (windowW < 768) {
			$('.eye-item-inner').css('height', (windowH - headerH - headlineH - eyeDetH) + 'px');
		} else {
			$('.eye-item-inner').css('height', 'auto');
		}
		var feImg01H = $('.point01_image').innerHeight();
		
		var feImg02H = $('.point02_image_01').innerHeight();
		var feImg02H02 = $('.point02_image_01').innerHeight();
		
		if (windowW > 768) {
			$('.feature_point01 .point_content').css('height', feImg01H + 'px');
			$('.feature_point02 .point_content').css('height', feImg02H + 'px');
		} else {
			$('.feature_point01 .point_content').css('height', 'auto');
			$('.feature_point02 .point_content').css('height', (feImg02H + feImg02H02) + 'px');
		}
		var f01ImageH = $('.point_image.point01_image').height();
		var f03ImageH = $('.point_image.point03_image').height();
		if (windowW < 768) {
			$('.feature_point01 .point_text_content').css('margin-top', (f01ImageH * 0.8) + 'px');
			$('.feature_point03 .point_text_content').css('margin-top', (f03ImageH * 0.8) + 'px');
			$('.feature_point02 .point_text_content').css('margin-top', (feImg02H * 0.75) + 'px');
		}
	});
	
	// #で始まるアンカーをクリックした場合に処理
   $('a[href^=#]').click(function() {
      // スクロールの速度
      var speed = 400; // ミリ秒
      // アンカーの値取得
      var href= $(this).attr("href");
      // 移動先を取得
      var target = $(href == "#" || href == "" ? 'html' : href);
      // 移動先を数値で取得
      var position = target.offset().top;
      // スムーススクロール
      $('body,html').animate({scrollTop:position}, speed, 'swing');
      return false;
   });
	var $img = $('.obj-fit-image');
  
  // Handle responsive images with srcset
  $img.each(function(){
    // Polyfill srcset and sizes 
    picturefill({ elements: this });
    
    // Polyfill object-fit property
    objectFitImages(this);
  });
});