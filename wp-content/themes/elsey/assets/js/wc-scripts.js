(function($) {

	'use strict';

	var $window_size = $(window).width();

	var $filter_open_page_url    = [];
	var $filter_next_page_url    = [];

	var $shop_next_page_url      = [];
	var $shop_default_container  = [];
	var $shop_masonry_container  = [];
	var $shop_fullgrid_container = [];

	////*************************************** Shop Filter General Functions ********************************////

	function checkUrlParam(url, param) {
		var results = new RegExp('[\?&]' + param + '=([^&#]*)').exec(url);
		if (results == null) {
			return 0;
		} else {
			return results[1] || 0;
		}
	}

	function replaceUrlParam(url, paramName, paramValue) {
		if (paramValue == null) paramValue = '';
		var pattern = new RegExp('\\b(' + paramName + '=).*?(&|$)')
		if (url.search(pattern) >= 0) {
			return url.replace(pattern, '$1' + paramValue + '$2');
		}
		return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
	}

	function removeUrlParam(url, paramName) {
		var rtn = url.split("?")[0],
			param, params_arr = [],
			queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";
		if (queryString !== "") {
			params_arr = queryString.split("&");
			for (var i = params_arr.length - 1; i >= 0; i -= 1) {
				param = params_arr[i].split("=")[0];
				if (param === paramName) {
					params_arr.splice(i, 1);
				}
			}
			if (params_arr.length > 0) {
				rtn = rtn + "?" + params_arr.join("&");
			}
		}
		return rtn;
	}

	function changeUrl(url) {
		var page = document.title;
		if (typeof(history.pushState) != "undefined") {
			var obj = {
				Page: page,
				Url: url
			};
			history.pushState(obj, obj.Page, obj.Url);
		} else {
			alert("Browser does not support HTML5.");
		}
	}

	function elseyShopLoadImages(indexNum) {

		var $dload   = indexNum.data('dload');
		var $sload   = indexNum.data('sload');
		var $lazySrc = indexNum.data('ldurl');

		if ( ( typeof $dload !== 'undefined' ) && ( $dload === 'els-dload-small' ) && ( typeof $sload !== 'undefined') ) {

			if ( $window_size < $sload ) {

				indexNum.find('li .els-product-featured-image .els-product-unveil-loader').hide();

				indexNum.children('li:not(.els-image-loaded)').each(function(index) {
					var $this = $(this).find('.els-product-featured-image .wp-post-image');
					var $realSrc = $this.attr('data-src');
					$this.attr('src', $realSrc);
				});

			} else {

				$(window).off('scroll.unveil resize.unveil lookup.unveil');
				indexNum.find('li .els-product-featured-image .els-product-unveil-loader').show();

				indexNum.children('li:not(.els-image-loaded)').each(function(index) {
					var $this = $(this).find('.els-product-featured-image .wp-post-image');
					$this.attr('src', $lazySrc);
				});

				var $imagesLoaded = indexNum.find('li:not(.els-image-loaded) .els-product-featured-image .els-unveil-image');

			    if ($imagesLoaded.length) {
					var scrollTolerance = 1;
					$imagesLoaded.unveil(scrollTolerance, function() {
					    $(this).load(function() {
					        $(this).parents('li').first().addClass('els-image-loaded');
					    });
					});
				}

				$(window).scrollTop($(window).scrollTop()+1);
			}

		} else {

			$(window).off('scroll.unveil resize.unveil lookup.unveil');
			var $images = indexNum.find('li:not(.els-image-loaded) .els-product-featured-image .els-unveil-image');

			if ($images.length) {
				var scrollTolerance = 1;
				$images.unveil(scrollTolerance, function() {
				    $(this).load(function() {
				        $(this).parents('li').first().addClass('els-image-loaded');
				    });
				});
			}

			$(window).scrollTop($(window).scrollTop()+1);
		}
	}

	////*************************************** Document Ready Functions ********************************////

	$(document).ready(function() {

		elseyShopLoadImages($('ul.products'));

		// Currency Select
		$('.els-currency-switcher select[name="woocommerce-currency-switcher"]').niceSelect();

		// OnClick Focus Search Modal
		$("#els-search-modal").on("shown.bs.modal", function() {
			$("#els-prs").focus();
		})

		// OnClick Cart Popup Open
		$('#els-cart-trigger').on("click", function() {
			$('#els-shopping-cart-content .widget_shopping_cart_content').toggleClass('els-cart-popup-open');
		});

		// Remove Class button from product catalog
		$('ul.products .els-product-image .els-product-atc a').removeClass('button');

		// Filled Wishlist Icon Change
		$('body').on( "added_to_wishlist", function (e) {
			if (!$('.els-wishlist-icon').hasClass('els-wishlist-filled')) {
				$('.els-wishlist-icon').removeClass('els-wishlist-empty').addClass('els-wishlist-filled');
			}
		});

		$('body').on( "removed_from_wishlist", function (e) {
			if ($('.wishlist_table tbody tr td').hasClass('wishlist-empty')) {
				if ($('.els-wishlist-icon').hasClass('els-wishlist-filled')) {
					$('.els-wishlist-icon').removeClass('els-wishlist-filled').addClass('els-wishlist-empty');
				}
			}
		});

		////****************************************** Product ShortCode Scripts Starts ***************************************////

		$(".els-shop-wrapper").each(function(index) {
			$(this).attr('data-scnumber', index);
			$filter_open_page_url[index] = $(location).attr('href');
			$filter_next_page_url[index] = $(this).find('.els-load-more-link a').attr('href');
			$shop_next_page_url[index]   = $(this).find('.els-load-more-link a').attr('href');
		});

		$(".els-shop-wrapper .els-shop-default ul.products").each(function(index) {
			$shop_default_container[index] = $(this);
			$shop_default_container[index].imagesLoaded(function() {
		    	$shop_default_container[index].children('li').matchHeight({
		        	byRow: false
		    	});
	    	});
		});

		$(".els-shop-wrapper .els-shop-masonry ul.products").each(function(index) {
			$shop_masonry_container[index] = $(this);
			$shop_masonry_container[index].imagesLoaded(function() {
				$shop_masonry_container[index].isotope({
					layoutMode: 'packery',
					packery: {
						columnWidth: '.els-pr-masonry-sizer'
					},
					itemSelector: '.els-pr-masonry-item',
				});
			});
		});

		$(".els-shop-wrapper .els-shop-fullgrid ul.products").each(function(index) {
			$shop_fullgrid_container[index] = $(this);
			$shop_fullgrid_container[index].imagesLoaded(function() {
		    	$shop_fullgrid_container[index].children('li').matchHeight({
		        	byRow: false
		    	});
	    	});
		});

		////************************************* Shop All Filter Variable Declaration ********************************////

		var $shopPageUrl  = $('.els-shop-wrapper .els-products-full-wrap').attr('data-shopurl');
		var $initSortBy   = $('.els-prsc-shop-filter .els-order-filter .woocommerce-ordering select[name="orderby"] option:selected').val();
		var $initMinPrice = $('#els-price-filter .price_slider_amount #min_price').val();
		var $initMaxPrice = $('#els-price-filter .price_slider_amount #max_price').val();

        if ( typeof woocommerce_price_slider_params === 'undefined' ) {
		    var $initMinHtml = '';
            var $initMaxHtml = '';
    	} else {
    	    if ( woocommerce_price_slider_params.currency_pos === 'left' ) {
                var $initMinHtml = woocommerce_price_slider_params.currency_symbol + $initMinPrice;
                var $initMaxHtml = woocommerce_price_slider_params.currency_symbol + $initMaxPrice;
            } else if ( woocommerce_price_slider_params.currency_pos === 'left_space' ) {
                var $initMinHtml = woocommerce_price_slider_params.currency_symbol + ' ' + $initMinPrice;
                var $initMaxHtml = woocommerce_price_slider_params.currency_symbol + ' ' + $initMaxPrice;
            } else if ( woocommerce_price_slider_params.currency_pos === 'right' ) {
                var $initMinHtml = min + woocommerce_price_slider_params.currency_symbol;
                var $initMaxHtml = max + woocommerce_price_slider_params.currency_symbol;
            } else if ( woocommerce_price_slider_params.currency_pos === 'right_space' ) {
                var $initMinHtml = min + ' ' + woocommerce_price_slider_params.currency_symbol;
                var $initMaxHtml = max + ' ' + woocommerce_price_slider_params.currency_symbol;
            }
    	}

		////************************************* Shop Ajax Filter Scripts Starts ********************************////

		function doProductAjaxFilter(urlFilter, getScIndex) {

			var minPrice = decodeURIComponent(checkUrlParam(urlFilter, 'min_price'));
			var maxPrice = decodeURIComponent(checkUrlParam(urlFilter, 'max_price'));
			var orderBy  = decodeURIComponent(checkUrlParam(urlFilter, 'orderby'));

			if (orderBy != 0) {
				if (orderBy == $initSortBy) {
					$filterPageUrl[getScIndex] = removeUrlParam($filterPageUrl[getScIndex], 'orderby');
				}
			}

			if ((minPrice != 0) && (maxPrice != 0)) {
				if ((minPrice == $initMinPrice) && (maxPrice == $initMaxPrice)) {
					var minChangedUrl = removeUrlParam($filter_open_page_url[getScIndex], 'min_price');
					$filter_open_page_url[getScIndex] = removeUrlParam(minChangedUrl, 'max_price');
				}
			}

			var queryStringPart = ($filter_open_page_url[getScIndex].indexOf("?") !== -1) ? $filter_open_page_url[getScIndex].split("?")[1] : 0;
			var $finalFilterPageUrl;

			if (queryStringPart != 0) {
				$finalFilterPageUrl = $shopPageUrl + "?" + queryStringPart;
			} else {
				$finalFilterPageUrl = $filter_open_page_url[getScIndex];
			}

			//alert($finalFilterPageUrl);

			if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").hasClass('els-no-ajax')) {

				window.location.href = $finalFilterPageUrl;

			} else if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").hasClass('els-ajax')) {

				//changeUrl($filter_open_page_url[getScIndex]);

				if ($finalFilterPageUrl) {

					if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").hasClass('els-all-loaded')) {
						$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").removeClass('els-all-loaded');
					}

					if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").hasClass('els-hide-btn')) {
						$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").removeClass('els-hide-btn');
					}

					$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-shop-load-more-controls #els-loaded').addClass('els-link-present');
					$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-shop-load-anim').addClass('els-loader');
					$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-products-full-wrap').fadeOut(500);
					$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-shop-load-more-box').fadeOut(500);

					$.ajax({
						method: 'POST',
						url: $finalFilterPageUrl,
						dataType: 'html',
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							console.log('AJAX error - ' + errorThrown);
						},
						complete: function() {
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-shop-load-anim').removeClass('els-loader');
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-products-full-wrap').fadeIn(500);
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-shop-load-more-box').fadeIn(500);
						},
						success: function(response) {
							var $filteredProducts = '';
							var $filteredProductsMasonry = '';
							var $checkShopNumber = 0;

							$(".els-shop-wrapper", $.parseHTML(response)).each(function(index) {
								$checkShopNumber = $checkShopNumber + index;
							});

							if ($checkShopNumber == 0) {
								$filteredProducts = $($.parseHTML(response)).find('.els-shop-wrapper .els-products-full-wrap ul.products').children('li.type-product');
								$filteredProductsMasonry = $($.parseHTML(response)).find('.els-shop-wrapper .els-products-full-wrap ul.products').children('li.type-product').addClass('els-pr-masonry-item');
							} else {
								$(".els-shop-wrapper", $.parseHTML(response)).each(function(index) {
									var $this = $(this);
									if (parseInt(index) == parseInt(getScIndex)) {
										$filteredProducts = $this.find('.els-products-full-wrap ul.products').children('li.type-product');
										$filteredProductsMasonry = $this.find('.els-products-full-wrap ul.products').children('li.type-product').addClass('els-pr-masonry-item');
									}
								});
							}

							if ($filteredProducts.length) {

								if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").hasClass('els-prsc-shop-masonry')) {

									var $deleteProductsMasonry = $(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap ul.products").children('li.type-product');
									$shop_masonry_container[getScIndex].isotope('remove', $deleteProductsMasonry).isotope('layout');

									$.each($filteredProductsMasonry, function( key, el ) {
										var elseyProductText  = $(el).find('.els-product-text').html();
										$(el).find('.els-product-cnt').prepend('<div class="els-product-text top">' + elseyProductText + '</div>');
										if ($(el).hasClass('pd-2wh')) {
											$(el).find('.els-loop-thumb img.wp-post-image').removeAttr('width');
											$(el).find('.els-loop-thumb img.wp-post-image').removeAttr('height');
											var elseyProductMainImage = $(el).find('.els-loop-thumb img.wp-post-image').attr('data-src-main');
											$(el).find('.els-loop-thumb img.wp-post-image').attr('src', elseyProductMainImage);
											$(el).find('.els-loop-thumb img.wp-post-image').attr('data-src', elseyProductMainImage);
										}
										if ($(el).find('.els-loop-thumb').hasClass('els-loop-thumb-has-hover')) {
											$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').removeAttr('width');
											$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').removeAttr('height');
											var elseyProductHoverImage = $(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').attr('data-src-main');
											$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').attr('src', elseyProductHoverImage);
											$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').attr('data-src', elseyProductHoverImage);
										}
									});

									$shop_masonry_container[getScIndex].append($filteredProductsMasonry).imagesLoaded(function() {
										$shop_masonry_container[getScIndex].isotope('appended', $filteredProductsMasonry).isotope('layout');
									});

									elseyShopLoadImages($shop_masonry_container[getScIndex]);

								} else if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").hasClass('els-shop-fullgrid')) {

                                    $.each($filteredProducts, function( key, el ) {
										var elseyProductText = $(el).find('.els-product-text').html();
										$(el).find('.els-product-cnt').prepend('<div class="els-product-text top">' + elseyProductText + '</div>');
									});

                                   	$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find("ul.products").empty();
									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find("ul.products").append($filteredProducts);

									elseyShopLoadImages($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products'));

								} else if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").hasClass('els-prsc-shop-default')) {

									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find("ul.products").empty();
									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find("ul.products").append($filteredProducts);

                                    $shop_default_container[getScIndex].children('li').matchHeight({
                                        byRow: false
                                    });

                                    elseyShopLoadImages($shop_default_container[getScIndex]);

								} else {

									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find("ul.products").empty();
									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find("ul.products").append($filteredProducts);

                                    elseyShopLoadImages($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find("ul.products"));

								}

								$filter_next_page_url[getScIndex] = $($.parseHTML(response)).find('.els-shop-load-more-link').children('a').attr('href');

								if ($filter_next_page_url[getScIndex]) {
									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-load-more-link").find('a').attr('href', $filter_next_page_url[getScIndex]);
									$shop_next_page_url[getScIndex] = $(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-load-more-link").find('a').attr('href');
								} else {
									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").addClass('els-all-loaded');
									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-load-more-link").find('a').removeAttr('href');
									$(".els-shop-wrapper[data-scnumber='" + getScIndex + "']").find('.els-shop-load-more-controls #els-loaded').removeClass('els-link-present');
								}

							} else {
								$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products').empty();
								$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").addClass('els-hide-btn');
								$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products').html('No Data for this Combinaion.');
							}
						}
					});
				}
			}
		}

		////************************************* Shop Sort Filter Scripts Starts ********************************////

		$('.els-prsc-shop-filter .els-order-filter .woocommerce-ordering select[name="orderby"]').change(function(e) {
			var sortData   = $(this).val();
			var getScIndex = $(this).closest(".els-shop-wrapper").attr('data-scnumber');

			e.stopPropagation();
			e.preventDefault();

			if (getScIndex == undefined) {
				getScIndex = $(".els-shop-wrapper").attr('data-scnumber');
			}

			var orderBy = decodeURIComponent(checkUrlParam($filter_open_page_url[getScIndex], 'orderby'));

			if (orderBy != 0) {
				$filter_open_page_url[getScIndex] = replaceUrlParam($filter_open_page_url[getScIndex], 'orderby', sortData);
			} else {
				if ($filter_open_page_url[getScIndex].indexOf('?') > 0) {
					$filter_open_page_url[getScIndex] += '&';
				} else {
					$filter_open_page_url[getScIndex] += '?';
				}
				$filter_open_page_url[getScIndex] += 'orderby=' + sortData;
			}

			doProductAjaxFilter($filter_open_page_url[getScIndex], getScIndex);
		});

		////************************************* Shop Price Filter Scripts Starts ********************************////

		$('#els-price-filter .price_slider_amount #els-price-filter-submit').on('click', function(e) {
			e.preventDefault();
			var $this = $(this);
			var getScIndex = $this.parent('.price_slider_amount').closest(".els-shop-wrapper").attr('data-scnumber');

			if (getScIndex == undefined) {
				getScIndex = $(".els-shop-wrapper").attr('data-scnumber');
			}

			var minPrice = decodeURIComponent(checkUrlParam($filter_open_page_url[getScIndex], 'min_price'));
			var maxPrice = decodeURIComponent(checkUrlParam($filter_open_page_url[getScIndex], 'max_price'));
			var minValue = $this.parent('.price_slider_amount').find('#min_price').val();
			var maxValue = $this.parent('.price_slider_amount').find('#max_price').val();

			if ((minPrice == 0) && (maxPrice == 0)) {
				if ($filter_open_page_url[getScIndex].indexOf('?') > 0) {
					$filter_open_page_url[getScIndex] += '&';
				} else {
					$filter_open_page_url[getScIndex] += '?';
				}
				$filter_open_page_url[getScIndex] += 'min_price=' + minValue + '&max_price=' + maxValue;
			} else {
				var minChangedUrl = replaceUrlParam($filter_open_page_url[getScIndex], 'min_price', minValue);
				$filter_open_page_url[getScIndex] = replaceUrlParam(minChangedUrl, 'max_price', maxValue);
			}

			doProductAjaxFilter($filter_open_page_url[getScIndex], getScIndex);
		});

		////*************************************** Shop Attribute Filter Scripts Starts ********************************////

		$('.els-attribute-filter li a').on('click', function(e) {
			e.preventDefault();
			var $this = $(this);
			var $thisLi = $this.parent('li');
			var attributeData = $this.attr('data-attribute');
			var attrnameData = $this.attr('data-attrname');
			var filterAttribute = 'filter_' + attrnameData;
			var getScIndex = $thisLi.parent('ul').closest(".els-shop-wrapper").attr('data-scnumber');

			if (getScIndex == undefined) {
				getScIndex = $(".els-shop-wrapper").attr('data-scnumber');
			}

			var filterName = decodeURIComponent(checkUrlParam($filter_open_page_url[getScIndex], filterAttribute));

			if ($thisLi.hasClass('active')) {
				if (filterName != 0) {
					$filter_open_page_url[getScIndex] = removeUrlParam($filter_open_page_url[getScIndex], filterAttribute);
				}
				$thisLi.parent('ul').children('.active').removeClass('active');
			} else {
				$thisLi.parent('ul').children('.active').removeClass('active');
				$thisLi.addClass('active');

				if (filterName != 0) {
					$filter_open_page_url[getScIndex] = replaceUrlParam($filter_open_page_url[getScIndex], filterAttribute, attributeData);
				} else {
					if ($filter_open_page_url[getScIndex].indexOf('?') > 0) {
						$filter_open_page_url[getScIndex] += '&';
					} else {
						$filter_open_page_url[getScIndex] += '?';
					}
					$filter_open_page_url[getScIndex] += filterAttribute + '=' + attributeData;
				}
			}

			doProductAjaxFilter($filter_open_page_url[getScIndex], getScIndex);
		});

		////************************************* Shop Load More Pagination Scripts Starts ********************************////

		$.each($shop_next_page_url, function(getScIndex, value) {
			if ($shop_next_page_url[getScIndex]) {
				$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls #els-loaded").addClass('els-link-present');
			} else {
				$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").addClass('els-hide-btn');
			}
		});

		$('.els-shop-wrapper #els-shop-load-more-btn').on('click', function(e) {

			$('body').LoadingOverlay('show');
			e.preventDefault();

			var $this = $(this);
			var getScIndex = $this.parent('.els-load-more-controls').closest(".els-shop-wrapper").attr('data-scnumber');

			if ($shop_next_page_url[getScIndex]) {

				if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").hasClass('els-hide-btn')) {
					$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").removeClass('els-hide-btn');
				}

				$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").addClass('els-loader');

				$.ajax({
					url: $shop_next_page_url[getScIndex],
					dataType: 'html',
					method: 'GET',
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						console.log('AJAX error - ' + errorThrown);
					},
					complete: function() {
						$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").removeClass('els-loader');
						$('body').LoadingOverlay('hide');
					},
					success: function(response) {
						var $newElements = '';
						var $newElementsMasonry = '';
						var $checkShopNumber = 0;

						$(".els-shop-wrapper", $.parseHTML(response)).each(function(index) {
							$checkShopNumber = $checkShopNumber + index;
						});

						if ($checkShopNumber == 0) {
							$newElements = $($.parseHTML(response)).find('.els-shop-wrapper .els-products-full-wrap ul.products').children('li.type-product');
							$newElementsMasonry = $($.parseHTML(response)).find('.els-shop-wrapper .els-products-full-wrap ul.products').children('li.type-product').addClass('els-pr-masonry-item');
						} else {
							$(".els-shop-wrapper", $.parseHTML(response)).each(function(index) {
								var $this = $(this);
								if (parseInt(index) == parseInt(getScIndex)) {
									$newElements = $this.find('.els-products-full-wrap ul.products').children('li.type-product');
									$newElementsMasonry = $this.find('.els-products-full-wrap ul.products').children('li.type-product').addClass('els-pr-masonry-item');
								}
							});
						}

						if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").hasClass('els-prsc-shop-masonry')) {

							$.each( $newElementsMasonry, function( key, el ) {
								var elseyProductText = $(el).find('.els-product-text').html();
								$(el).find('.els-product-cnt').prepend('<div class="els-product-text top">' + elseyProductText + '</div>');
								if ($(el).hasClass('pd-2wh')) {
									$(el).find('.els-loop-thumb img.wp-post-image').removeAttr('width');
									$(el).find('.els-loop-thumb img.wp-post-image').removeAttr('height');
									var elseyProductMainImage = $(el).find('.els-loop-thumb img.wp-post-image').attr('data-src-main');
									$(el).find('.els-loop-thumb img.wp-post-image').attr('src', elseyProductMainImage);
									$(el).find('.els-loop-thumb img.wp-post-image').attr('data-src', elseyProductMainImage);
								}
								if ($(el).find('.els-loop-thumb').hasClass('els-loop-thumb-has-hover')) {
									$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').removeAttr('width');
									$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').removeAttr('height');
									var elseyProductHoverImage = $(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').attr('data-src-main');
									$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').attr('src', elseyProductHoverImage);
									$(el).find('.els-loop-thumb .els-hover-image-wrap img.els-pr-hover-image').attr('data-src', elseyProductHoverImage);
								}
							});

                            $shop_masonry_container[getScIndex].append($newElementsMasonry).imagesLoaded(function() {
								$shop_masonry_container[getScIndex].isotope('appended', $newElementsMasonry).isotope('layout');
							});

							elseyShopLoadImages($shop_masonry_container[getScIndex]);

						} else if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").hasClass('els-shop-fullgrid')) {

							$.each($newElements, function( key, el ) {
								var elseyProductText = $(el).find('.els-product-text').html();
								$(el).find('.els-product-cnt').prepend('<div class="els-product-text top">' + elseyProductText + '</div>');
							});

							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products').append($newElements);

							elseyShopLoadImages($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products'));

					    } else if ($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").hasClass('els-prsc-shop-default')) {

                            $(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products').append($newElements);

                            $shop_default_container[getScIndex].children('li').matchHeight({
                                remove: true
                            });

                            $shop_default_container[getScIndex].children('li').matchHeight({
                                byRow: false
                            });

                            elseyShopLoadImages($shop_default_container[getScIndex]);

						} else {
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products').append($newElements);
							elseyShopLoadImages($(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-products-full-wrap").find('ul.products'));
						}

						$shop_next_page_url[getScIndex] = $($.parseHTML(response)).find('.els-load-more-link').children('a').attr('href');

						if ($shop_next_page_url[getScIndex]) {
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-load-more-link").find('a').attr('href', $shop_next_page_url[getScIndex]);
						} else {
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").addClass('els-all-loaded');
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-load-more-link").find('a').removeAttr('href');
							$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls #els-loaded").removeClass('els-link-present');
						}
						$('body').LoadingOverlay('hide');
					}
				});
			} else {
				$(".els-shop-wrapper[data-scnumber='" + getScIndex + "'] .els-shop-load-more-controls").addClass('els-hide-btn');
			}
		});

		////************************************* Product Single Page Script Starts ********************************////

		$(document).on('init_slider', '.els-product-image-col', function(){
		var $productImageSlider = $('#els-product-featured-image-slider');
		var $productThumbSlider = $('#els-product-thumbnails-slider');
		var $productImages = $productImageSlider.children('div');
		var $productThumbs = $productThumbSlider.children('div');
		var $productActiveThumb = $productThumbs.first();
		var productNumThumbs = $productThumbs.length;
		var productAnimSpeed = 300;
		var isThumbClick = false;

		$productImageSlider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
			if (!isThumbClick) {
				$productThumbSlider.find('.slick-slide').eq(nextSlide).trigger('click');
			}
			isThumbClick = false;
			$productImageSlider.addClass('animating');
		});

		$productImageSlider.on('afterChange', function() {
			$productImageSlider.removeClass('animating');
		});

		$productImageSlider.slick({
			adaptiveHeight: false,
			draggable: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			dots: false,
			fade: true,
			infinite: false,
			cssEase: 'linear',
			speed: productAnimSpeed
		});

		$productThumbSlider.on('init', function() {
			$productThumbs.on('click', function() {
				var $this = $(this);
				if ($productImageSlider.hasClass('animating') || $this.hasClass('current')) {
					return;
				}

				isThumbClick = true;
				$productActiveThumb.removeClass('current');
				$this.addClass('current');
				$productActiveThumb = $this;

				if (!$this.next().hasClass('slick-active')) {
					$productThumbSlider.slick('slickNext');
				} else if (!$this.prev().hasClass('slick-active')) {
					$productThumbSlider.slick('slickPrev');
				}

				$productImageSlider.slick('slickGoTo', $this.index(), false);
			});
		});
//edited 20180605
		$productThumbSlider.slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			arrows: false,
			infinite: false,
			vertical: true,
			focusOnSelect: false,
			draggable: false,
			swipe: false,
			touchMove: false,
			speed: productAnimSpeed,
			 responsive: [
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 6,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
		});

			// Magnific Popup Gallery
		$productImageSlider.magnificPopup({
				delegate: 'a',
				type: 'image',
				closeOnContentClick: false,
				closeBtnInside: false,
				mainClass: 'mfp-with-zoom',
				image: {
					verticalFit: true,
				},
				gallery: {
					enabled: true,
				},
				zoom: {
					enabled: true,
					duration: 300,
					opener: function(element) {
						return element.find("img");
					}
				}
			});

			// Magnific Popup Single Image
			$(".els-img-popup").magnificPopup({
				type: 'image',
				closeOnContentClick: false,
				closeOnBgClick: true,
				closeBtnInside: false,
				mainClass: 'mfp-with-zoom',
				image: {
					verticalFit: true
				},
				zoom: {
					enabled: true,
					duration: 300,
					opener: function(element) {
						return element.find("img");
					}
				}
			});
		});
		
		
		$('.els-product-image-col').trigger('init_slider');

		// Tab Activation Class
		$('.els-wc-tabs-details .woocommerce-Tabs-panel').removeClass('els-current-tab');
		$('.wc-tabs-wrapper #tab-description').addClass('els-current-tab');
		$('.wc-tabs-wrapper ul.wc-tabs li a').on('click', function(e) {
			var $this = $(this);
			var $thisHref = $this.attr('href');
			$('.els-wc-tabs-details .woocommerce-Tabs-panel').removeClass('els-current-tab');
			$($thisHref).addClass('els-current-tab');
		});

		// Related Product Carousel
		var $relatedProCarousel = $('.els-related-product-slider');

		$relatedProCarousel.owlCarousel({
		    loop: $relatedProCarousel.data("loop"),
            margin: 20,
            mouseDrag: true,
            touchDrag: true,
            items: $relatedProCarousel.data("items"),
			nav: $relatedProCarousel.data("nav"),
			dots: $relatedProCarousel.data("dots"),
			autoplay: $relatedProCarousel.data("autoplay"),
			navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
			responsive: {
				0: {
					items: 2
				},
				480: {
					items: 2
				},
				767: {
					items: 3
				},
				991: {
					items: $relatedProCarousel.data("items")
				}
			}
		});

		// Gallery Update image (in case a variation image is used)
		var $variationsForm = $('.variations_form');
		var $variationsWrap = $variationsForm.children('.variations');

		$.fn.wc_set_variation_attr = function( attr, value ) {
			if ( undefined === this.attr( 'data-o_' + attr ) ) {
				this.attr( 'data-o_' + attr, ( ! this.attr( attr ) ) ? '' : this.attr( attr ) );
			}
			if ( false === value ) {
				this.removeAttr( attr );
			} else {
				this.attr( attr, value );
			}
		};

		$.fn.wc_reset_variation_attr = function( attr ) {
			if ( undefined !== this.attr( 'data-o_' + attr ) ) {
				this.attr( attr, this.attr( 'data-o_' + attr ) );
			}
		};

		function elseyResetVaritaion() {
			var $product      = $variationsForm.closest( '.product' ),
			$product_gallery  = $product.find( '#els-product-thumbnails-slider' ),
			$product_img_wrap = $product_gallery.find( '.woocommerce-product-gallery__image' ).eq( 0 ),
			$product_img      = $product_img_wrap.find( '.wp-post-image' );

			$product_img.wc_reset_variation_attr( 'src' );
			$product_img.wc_reset_variation_attr( 'width' );
			$product_img.wc_reset_variation_attr( 'height' );
			$product_img.wc_reset_variation_attr( 'srcset' );
			$product_img.wc_reset_variation_attr( 'sizes' );
			$product_img.wc_reset_variation_attr( 'title' );
			$product_img.wc_reset_variation_attr( 'alt' );
			$product_img.wc_reset_variation_attr( 'data-src' );
			$product_img.wc_reset_variation_attr( 'data-large_image' );
			$product_img.wc_reset_variation_attr( 'data-large_image_width' );
			$product_img.wc_reset_variation_attr( 'data-large_image_height' );
			$product_img_wrap.wc_reset_variation_attr( 'data-thumb' );
		}

		$( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
			var $product      = $variationsForm.closest( '.product' ),
			$product_gallery  = $product.find( '#els-product-thumbnails-slider' ),
			$product_img_wrap = $product_gallery.find( '.woocommerce-product-gallery__image, woocommerce-product-gallery__image--placeholder' ).eq( 0 ),
			$product_img      = $product_img_wrap.find( '.wp-post-image' );

			if ( variation && variation.image && variation.image.src && variation.image.src.length > 1 ) {
				$product_img.wc_set_variation_attr( 'src', variation.image.src );
				$product_img.wc_set_variation_attr( 'height', variation.image.src_h );
				$product_img.wc_set_variation_attr( 'width', variation.image.src_w );
				$product_img.wc_set_variation_attr( 'srcset', variation.image.srcset );
				$product_img.wc_set_variation_attr( 'sizes', variation.image.sizes );
				$product_img.wc_set_variation_attr( 'title', variation.image.title );
				$product_img.wc_set_variation_attr( 'alt', variation.image.alt );
				$product_img.wc_set_variation_attr( 'data-src', variation.image.full_src );
				$product_img.wc_set_variation_attr( 'data-large_image', variation.image.full_src );
				$product_img.wc_set_variation_attr( 'data-large_image_width', variation.image.full_src_w );
				$product_img.wc_set_variation_attr( 'data-large_image_height', variation.image.full_src_h );
				$product_img_wrap.wc_set_variation_attr( 'data-thumb', variation.image.src );
			} else {
				$product_img.wc_reset_variation_attr( 'src' );
				$product_img.wc_reset_variation_attr( 'width' );
				$product_img.wc_reset_variation_attr( 'height' );
				$product_img.wc_reset_variation_attr( 'srcset' );
				$product_img.wc_reset_variation_attr( 'sizes' );
				$product_img.wc_reset_variation_attr( 'title' );
				$product_img.wc_reset_variation_attr( 'alt' );
				$product_img.wc_reset_variation_attr( 'data-src' );
				$product_img.wc_reset_variation_attr( 'data-large_image' );
				$product_img.wc_reset_variation_attr( 'data-large_image_width' );
				$product_img.wc_reset_variation_attr( 'data-large_image_height' );
				$product_img_wrap.wc_reset_variation_attr( 'data-thumb' );
			}
		});

		$variationsForm.on( 'click', '.reset_variations', function( event ) {
			elseyResetVaritaion();
		});

		$( ".single_variation_wrap" ).on( "hide_variation", function ( event, variation ) {
      		elseyResetVaritaion();
		});

		// Sticky Info Product JS
		var $stickyTopMargin;
		if ($('#els-wrap').hasClass('els-fixed-menubar ')) {
			$stickyTopMargin = 120;
		} else {
			$stickyTopMargin = 30;
		}

		$('els-product-images-sticky, .els-product-summary-sticky').theiaStickySidebar({
		    additionalMarginTop: $stickyTopMargin
		});

		////************************************* Others Script Starts ********************************////

		$('div.quantity.buttons_added .plus, td.quantity.buttons_added .plus').attr("value", $.parseHTML("&#xf106;")[0].data);
		$('div.quantity.buttons_added .minus, td.quantity.buttons_added .minus').attr("value", $.parseHTML("&#xf107;")[0].data);

	});

})(jQuery);