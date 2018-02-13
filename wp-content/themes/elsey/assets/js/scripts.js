/*
 * All Scripts Used in this Elsey Theme
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

 (function($){

	'use strict';

	$(window).load(function() {

		// $('[id*="els-plxsec"]').parallax("50%", 0.1);
		// Preloader Option
		$('.els-preloader-mask').delay(200).fadeOut();
        $('#preloader').delay(350).fadeOut('slow');
        $('body').delay(350).css({'overflow':'visible'});

       	// OWL Carousel For Single Post Slider Images
		$('.els-blog-single .els-feature-img-carousel').owlCarousel({
			items: 1,
			loop: true,
			nav: true,
			dots: false,
			autoplay: false,
			autoHeight: true,
			navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 1
				}
			}
		});

		// Toggle Sidebar Right
	    $('#els-right-menu').click(function(e) {
			$('#els-sidebar-menu').toggleClass('els-sidebar-menu-active');
			$('#els-wrap').toggleClass('els-sidebar-canvas');
			$('#els-sidebar-menu-footer-close').addClass("els-sidebar-menu-open");
		});

		$('#els-sidebar-menu-close').click(function(e) {
			$('#els-sidebar-menu').removeClass('els-sidebar-menu-active');
			$('#els-wrap').removeClass('els-sidebar-canvas');
			$('#els-sidebar-menu-footer-close').removeClass("els-sidebar-menu-open");
		});

		$('#els-sidebar-menu-footer-close').click(function(e) {
			$('#els-sidebar-menu').removeClass('els-sidebar-menu-active');
			$('#els-wrap').removeClass('els-sidebar-canvas');
			$('#els-sidebar-menu-footer-close').removeClass("els-sidebar-menu-open");
		});

	});

	$(document).ready(function() {

		$.stellar({
			horizontalScrolling: false,
		});

		// IE Class
		var isIE = document.body.style.msTouchAction !== undefined;
		if(isIE) {
			$('html').addClass('ie10');
		} else {
			$('html').addClass('els-browser');
		}

		// Col Remove From Sidebar and Footer Main Menu
        $('.els-sidebar .main-navigation li.els-megamenu ul.sub-menu li.els-megamenu-show-title').removeClass();
        $('.els-sidebar .main-navigation li.els-megamenu ul.sub-menu li.els-megamenu-show-title').addClass( 'menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children');
        $('.els-footer .main-navigation li.els-megamenu ul.sub-menu li.els-megamenu-show-title').removeClass();
        $('.els-footer .main-navigation li.els-megamenu ul.sub-menu li.els-megamenu-show-title').addClass( 'menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children');

		// Slicknav Mobile Menu
		$("#els-menu").slicknav({
			label: 'Menu',
			duplicate: true,
			nestedParentLinks: true,
			closedSymbol: '',
    	openedSymbol: '',
			duration: 200,
			allowParentLinks: false,
			prependTo: "#els-mobile-menu"
		});

		$('#els-menu li.els-dropdown-menu ul.sub-menu').removeClass('row row-eq-height');
		$('#els-menu li.els-dropdown-menu ul.sub-menu li').removeClass('els-megamenu-show-title');
		$('#els-mobile-menu li.els-dropdown-menu ul.sub-menu').removeClass('row row-eq-height');
		$('#els-mobile-menu li.els-dropdown-menu ul.sub-menu li').removeClass('els-megamenu-show-title');
		$('#els-menu li.els-megamenu ul.row').wrap('<div class="els-megamenu-wrap"><div class="container"></div></div>');
		$('#els-mobile-menu .slicknav_menu ul.slicknav_nav').wrap('<div class="els-slicknav-mobile-inner"></div>');

		$('#els-mobile-menu .slicknav_menu .slicknav_btn').click(function(e) {
			$('#els-mobile-menu .slicknav_menu .els-slicknav-mobile-inner').toggleClass('open');
		});

		// Scrolling Header
		$(window).scroll(function() {
			var scroll = $(window).scrollTop();
			if (scroll >= 20) {
				if ( $('body').hasClass('admin-bar') ) {
					$(".els-fixed-menubar .els-header .els-menubar").sticky({topSpacing:32});
				} else {
					$(".els-fixed-menubar .els-header .els-menubar").sticky({topSpacing:0});
				}
			} else {
				$(".els-fixed-menubar .els-header .els-menubar").unstick();
			}

			if ( ( $(window).scrollTop() + $(window).height() ) < ( $(document).height() - ( $(".els-footer").height() ) ) ) {
	        	$('#els-plx-nav').css({ 'opacity' : 1 }).css({ 'visibility' : 'visible' });
			}
	    	if ( ( $(window).scrollTop() + $(window).height() ) > ( $(document).height() - ( $(".els-footer").height() ) ) ) {
      			$('#els-plx-nav').css({ 'opacity' : 0 }).css({ 'visibility' : 'hidden' });
	 		}
		});

		// FitJs Video
		$(".els-content-area").fitVids();

		// Blog Masonary Call
		var $blog_masonry_container;
		if ($('.els-blog-wrapper').hasClass('els-blog-masonry')) {
			$blog_masonry_container = $('.els-blog-masonry-wrap');
			$blog_masonry_container.imagesLoaded(function() {
				$blog_masonry_container.masonry({
					itemSelector: '.els-blog-masonry-item',
					columnWidth: '.els-blog-masonry-sizer',
				});
			});
		}

		// OWL Carousel Blog Feature Image
		$('.els-blog-post .els-feature-img-carousel').owlCarousel({
			items: 1,
			loop: true,
			nav: true,
			dots: false,
			autoplay: false,
			autoHeight: false,
			navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 1
				}
			}
		});

		// Blog Post Ajax Load
		var $nextPageLinkBlog = $('.els-blog-load-more-link').find('a');
		var $loadMoreControlsBlog = $('.els-blog-load-more-controls');
		var $nextPageUrlBlog = $nextPageLinkBlog.attr('href');

		if ($nextPageUrlBlog) {
			$('.els-blog-load-more-controls #els-loaded').addClass('els-link-present');
		} else {
			$loadMoreControlsBlog.addClass('els-hide-btn');
		}

		$('.els-blog-wrapper #els-blog-load-more-btn').on('click', function(e) {
			e.preventDefault();

			if ($nextPageUrlBlog) {

				if ($loadMoreControlsBlog.hasClass('els-hide-btn')) {
					$loadMoreControlsBlog.removeClass('els-hide-btn');
				}

				$loadMoreControlsBlog.addClass('els-loader');

				$.ajax({
					url: $nextPageUrlBlog,
					dataType: 'html',
					method: 'GET',
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						console.log('ELSEY: AJAX error - ' + errorThrown);
					},
					complete: function() {
						$loadMoreControlsBlog.removeClass('els-loader');
					},
					success: function(response) {
						if ($('.els-blog-wrapper').hasClass('els-blog-masonry')) {
							var $newElements = $($.parseHTML(response)).find('.els-blog-inner').children('.els-blog-masonry-item');
							$blog_masonry_container.append($newElements).imagesLoaded(function() {
								$blog_masonry_container.masonry('appended', $newElements);
							});
						} else {
							var $newElements = $($.parseHTML(response)).find('.els-blog-inner').children('.row');
							$('.els-blog-wrapper').find('.els-blog-inner').append($newElements);
						}

						$('.els-blog-post .els-feature-img-carousel').owlCarousel({
							items: 1,
							loop: true,
							nav: true,
							dots: false,
							autoplay: false,
							autoHeight: false,
							navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
							responsive: {
								0: {
									items: 1
								},
								600: {
									items: 1
								}
							}
						});

	                    $(".els-content-area").fitVids();

						$nextPageUrlBlog = $($.parseHTML(response)).find('.els-blog-load-more-link').children('a').attr('href');

						if ($nextPageUrlBlog) {
							$nextPageLinkBlog.attr('href', $nextPageUrlBlog);
						} else {
							$loadMoreControlsBlog.addClass('els-all-loaded');
							$nextPageLinkBlog.removeAttr('href');
							$('.els-blog-load-more-controls #els-loaded').removeClass('els-link-present');
						}
					}
				});
			} else {
				$loadMoreControlsBlog.addClass('els-hide-btn');
			}
		});

	  //Parallax scroll nav script
	  $('.sidebar-nav ul li a').on('click', function() {
	    var scrollAnchor = $(this).attr('data-scroll'),
	    scrollPoint = $('.data-scroll[data-anchor="' + scrollAnchor + '"]').offset().top -1;
	    $('body,html').animate({
	      scrollTop: scrollPoint
	    }, 500);
	    return false;
	  });

	  $(window).scroll(function() {
	    var windscroll = $(window).scrollTop();
	    if (windscroll >= 620) {
	      $('.data-scroll').each(function(i) {
	        if ($(this).position().top <= windscroll -1) {
	          $('.sidebar-nav ul li a.active').removeClass('active');
	          $('.sidebar-nav ul li a').eq(i).addClass('active');
	        }
	      });
	    }
	    else {
	      $('.sidebar-nav ul li .active').removeClass('active');
	      $('.sidebar-nav ul li a:first').addClass('active');
	    }
	  }).scroll();

  	//on scroll fixed sidebarnav script
  	function sticky_relocate() {
			var window_top = $(window).scrollTop();
			var footer_top = $('.els-footer-widget-area').offset().top;
			if ( $('.elsy-secondary').length == 0 ) {
				var div_top = 0;
			} else {
				var div_top = $('.elsy-secondary').offset().top;
			}
			var div_height = $('.sidebar-nav').height();
			var padding = 20;  // tweak here or get from margins etc

			if (window_top + div_height > footer_top - padding)
				$('.sidebar-nav').css({top: (window_top + div_height - footer_top + padding) * -1})
			else if (window_top > div_top) {
				$('.sidebar-nav').addClass('fixed');
				$('.sidebar-nav').css({top: 100})
			} else {
				$('.sidebar-nav').removeClass('fixed');
			}
    }

  	$(function () {
	    $(window).scroll(sticky_relocate);
	    sticky_relocate();
  	});

	(function() {

		'use strict';

		// click events
		document.body.addEventListener('click', copy, true);

		// event handler
		function copy(e) {
			// find target element
			var
			t = e.target,
			c = t.dataset.copytarget,
			inp = (c ? document.querySelector(c) : null);

			// is element selectable?
			if (inp && inp.select) {
			// select text
			inp.select();
			try {
				// copy text
				document.execCommand('copy');
				inp.blur();

				// copied animation
				t.classList.add('copied');
					setTimeout(function() { t.classList.remove('copied'); }, 9999999999);
				}
				catch (err) {
					alert('please press Ctrl/Cmd+C to copy');
				}

			}

		}

	})();

		$('.elsy-coupon-section').each( function() {
			var $this = $(this);
		 // if ($('.elsy-cpd').hasClass('copied')) {
		 $this.find('.elsy-cpd').click( function() {
	    $this.find('.coupon-input').addClass('border-change');
	  // }
	});
	});

	//Elsey Counter Script
	$('.elsy-counter').counterUp ({
		delay: 1,
		time: 4000,
		beginAt: 10,
	});

	$('p').each(function() {
    var $this = $(this);

    if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
        $this.remove();
	});

});


// Mobil App tab Event
(function () {

	$('.elsey-event-tab  ul li:first').addClass('active');
	$('.event-tabs .tab-pane:first').addClass('active');

	'use strict';

	var sideTabsPaneHeight = function() {
		var navHeight = $('.nav-tabs.nav-tabs-left').outerHeight() || $('.nav-tabs.nav-tabs-right').outerHeight() || 0;

		var paneHeight = 0;

		$('.tab-content.side-tabs .tab-pane').each(function(idx) {
		  paneHeight = $(this).outerHeight() > paneHeight ? $(this).outerHeight() : paneHeight;
		});


		$('.tab-content.side-tabs .tab-pane').each(function(idx) {
		  $(this).css('min-height', navHeight + 'px');
		});
  	};

	$(function() {
		sideTabsPaneHeight();

		$( window ).resize(function() {
		  	sideTabsPaneHeight();
		});

		$( '.nav-tabs.nav-tabs-left' ).resize(function() {
		  	sideTabsPaneHeight();
		});
	});

}());

})(jQuery);