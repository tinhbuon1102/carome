<div class="instagram row" ></div>
<button id="instagram_list_more" style="display: none;"><?php echo __( 'Load More' , 'instagram-shop' )?></button>
<script>
			var ig = {};
			var args = {};
			args.container = jQuery('.instagram');
			args.feedurl = '<?php echo site_url()?>/wp-content/plugins/instagram-shop/api/';
			args.html = '';
			
			ig.init = function() {
				jQuery('.instagram').each(function(i) {
					// PASS ARGS TO QUERY
					ig.query(args);
				});
			}

			ig.query = function(args) {
				var params = {count: <?php echo $insta_count ?>, path: "/v1/users/<?php echo INSTA_ID ?>/media/recent/"};
				if (args.max_id)
				{
					params.max_id = args.max_id;
				}
				jQuery.ajax({
					  dataType: "json",
					  url: args.feedurl,
					  data: params,
					  success: function(data){
						  ig.build(data, args);
					  },
					  error: function(response){}
				});
			}


			ig.build = function(data, args) {

				jQuery.each(data.data, function(i, item) {
					if (item.user) var username = item.user.username;
					var uppic = item.user.profile_picture;
					if (item.likes) var likes = item.likes.count;
					var thumb = item.images.__original.url;
					var img = item.images.__original.url;
					var instaID = item.link;
					var hires = img;
					args.html += '<div class="c-insta-tile o-column o-extra-small--6 o-medium--3 o-large--2 image-outer-wraper"><div class="image-outer" ><a originalpath="'+instaID+'" data-img="' + hires + '" class="image"><img class="c-ugc-tile__image" src="'+ thumb +'" >';
					args.html += '<div class="c-insta-tile__details caption"><div class="c-insta-tile__inner">';
					if (username) args.html += '<p class="user-info"><img src="' + uppic + '" />@' + username + '</p>';
					if (likes) args.html += '<p class="likes"><span class="count">' + likes + '</span><span class="hearts">&#10084;</span></p>';
					args.html += '</div></div>';
					args.html += '</a></div></div>';
					// PASS TO OUTPUT
					ig.output(args);
				});

				// Loadmore
				  if (data.pagination && data.pagination.next_url)
				  {
					  jQuery('#instagram_list_more').show();
					  jQuery('#instagram_list_more').attr('next_max_id', data.pagination.next_max_id);
				  }
				  else {
					  jQuery('#instagram_list_more').hide();
					  jQuery('#instagram_list_more').attr('next_max_id', 0);
				  }
			}

			ig.output = function(args) {
				args.container.html(args.html);
			}

			ig.view = {
				viewer: jQuery('.igviewer'),
				image: jQuery('.igviewer img'),
				open: function(img) {
					ig.view.viewer.removeClass('hidden');
					ig.view.image.attr('src', img);
				},
				close: function() {
					ig.view.viewer.addClass('hidden');
					ig.view.image.attr('src', '');
				}
			}

			ig.init();

			jQuery('body').on('click', '#instagram_list_more', function(e){
				args.max_id = jQuery(this).attr('next_max_id');
				ig.query(args);
			});
			
			//Listeners
			var originalpath = jQuery(this).find("a.image").attr("originalpath");
			jQuery('.instagram').on('click', '.image-outer-wraper', function() {
				var indeximg = parseInt(jQuery(this).index()) + 1;
				var data_image = jQuery(this).find("a.image").attr("data-img");
				var caption_html = jQuery(this).find('div.caption').html();
				originalpath = jQuery(this).find("a.image").attr("originalpath");
				
				jQuery(".product-box").remove();
				jQuery(".instagram .image-outer-wraper").css("pointer-events","initial");
				jQuery(this).css("pointer-events","none");
				
				if (jQuery(window).width() > 999) {
					var elindex = Math.ceil(indeximg / 4) * 4;
				} else if (jQuery(window).width() > 640 && jQuery(window).width() < 999) {
					var elindex = Math.ceil(indeximg / 3) * 3;
				} else if (jQuery(window).width() > 480 && jQuery(window).width() < 640) {
					var elindex = Math.ceil(indeximg / 2) * 2;
				} else if (jQuery(window).width() < 480) {
					var elindex = Math.ceil(indeximg / 2) * 2;
				}
				
				if (jQuery(".instagram .product-box").length == 0) {
					if(jQuery(".instagram .image-outer-wraper:nth-child(" + elindex + ")").length){
						jQuery(".instagram .image-outer-wraper:nth-child(" + elindex + ")").after('<div class="product-box"><span class="close" title="Close">&nbsp;</span><div class="col1"><img class="main" src="'+data_image+'"/><div class="caption">'+caption_html+'</div></div><div class="col2"><div class="insta_heading"><h2>In This Look</h2></div><div class="products"></div></div></div>');
					}else if(jQuery(".instagram .image-outer-wraper:nth-child(" +(elindex - 1)+ ")").length){
						jQuery(".instagram .image-outer-wraper:nth-child(" +(elindex - 1)+ ")").after('<div class="product-box"><span class="close" title="Close">&nbsp;</span><div class="col1"><img class="main" src="'+data_image+'"/><div class="caption">'+caption_html+'</div></div><div class="col2"><div class="insta_heading"><h2>In This Look</h2></div><div class="products"></div></div></div>');
					}else if(jQuery(".instagram .image-outer-wraper:nth-child(" +(elindex - 2)+ ")").length){
						jQuery(".instagram .image-outer-wraper:nth-child(" +(elindex - 2)+ ")").after('<div class="product-box"><span class="close" title="Close">&nbsp;</span><div class="col1"><img class="main" src="'+data_image+'"/><div class="caption">'+caption_html+'</div></div><div class="col2"><div class="insta_heading"><h2>In This Look</h2></div><div class="products"></div></div></div>');
					}

					if (jQuery(".product-box").length) {
						jQuery('html, body').animate({
							scrollTop: jQuery(".product-box").prev().position().top + 150
						}, 600);
					}
					jQuery('.instagram .product-box span.close').on('click', function() {
						jQuery(this).parent().remove();
						jQuery(".instagram .image-outer-wraper").css("pointer-events","initial");
					});
				}
				//Load Related Products
				jQuery.post(
					'<?php echo admin_url('admin-ajax.php'); ?>', 
					{
						'action': 'instagram_related_products',
						'data':   originalpath
					}, 
					function(response){
						var objInsta = JSON.parse(response);
						if(objInsta.length > 0){
							var html_product_slides = '<div id="slick_insta">';
								for(i=0;i<objInsta.length;i++){
									html_product_slides += '<div class="insta_product"><div class="product-tile">';
									html_product_slides += '<div class="insta-item">';
									html_product_slides += '<a href="'+objInsta[i].link+'" target="_blank"><img src="'+objInsta[i].post_image+'" /></a>';
									html_product_slides += '<div class="o-row o-column o-large--12 o-extra-small--3 c-product-tile-controls">';
									html_product_slides += '<a href="'+objInsta[i].link+'" target="_blank" class="c-product-tile-controls__quickshop-link js-product-tile-control-quickshop">Shop Now</a>';
									html_product_slides += '</div>';
									html_product_slides += '</div>';
									html_product_slides += '<div class="c-product-tile-details c-product-tile-details--title_and_price"><a href="'+objInsta[i].link+'" target="_blank" class="c-product-tile__title-link">';
									html_product_slides += '<h3 class="c-product-tile__h3 c-product-tile__h3--title_and_price">'+objInsta[i].post_title+'</h3>';
									html_product_slides += '</a>';
									html_product_slides += ''+objInsta[i].price+'';
									html_product_slides += '</div>';
									html_product_slides += '</div></div>';
								}
								html_product_slides += '</div>';
							jQuery('.instagram .product-box .col2 .products').html(html_product_slides);
							jQuery('#slick_insta').slick({
							  slidesToShow: 4,
							  slidesToScroll: 4,
							  autoplay: false,
							  autoplaySpeed: 2000,
							  responsive: [
								{
								  breakpoint: 999,
								  settings: {
									slidesToShow: 3,
									slidesToScroll: 3
								  }
								},
								{
								  breakpoint: 640,
								  settings: {
									slidesToShow: 2,
									slidesToScroll: 2
								  }
								},
								{
								  breakpoint: 480,
								  settings: {
									slidesToShow: 2,
									slidesToScroll: 2
								  }
								}
							  ]
							});
							return false;
						}else{
							var html_product_slides = '<div id="slick_insta">';
							html_product_slides += '<div class="insta_product ask_product"><div class="product-tile">';
							html_product_slides += '<div class="insta-item ask-item"><span class="ask-text">Coming Soon</span></div>';
							html_product_slides += '<p>Coming Soon...</p>';
							html_product_slides += '</div></div>'
							html_product_slides += '</div>';
							jQuery('.instagram .product-box .col2 .products').html(html_product_slides);
							jQuery('.instagram .product-box .col2 .products').html(html_product_slides);
							jQuery('#slick_insta').slick({
							  slidesToShow: 4,
							  slidesToScroll: 4,
							  autoplay: false,
							  autoplaySpeed: 2000,
							  responsive: [
								{
								  breakpoint: 999,
								  settings: {
									slidesToShow: 3,
									slidesToScroll: 3
								  }
								},
								{
								  breakpoint: 640,
								  settings: {
									slidesToShow: 2,
									slidesToScroll: 2
								  }
								},
								{
								  breakpoint: 480,
								  settings: {
									slidesToShow: 2,
									slidesToScroll: 2
								  }
								}
							  ]
							});
							return false;
						}
					}
				);

			});
			jQuery('.igviewer').on('click', function() {
				ig.view.close();
			});
			var s_width = jQuery(this).width();
			jQuery(window).resize(function() {
			  if(s_width != jQuery(this).width()){
				jQuery(".product-box").remove();
			  }
			});
		</script>
