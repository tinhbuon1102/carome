<?php
/**
 * Banner Slider - Shortcode Options
 */

if (class_exists('WooCommerce')) {

  add_action( 'init', 'elsey_banner_slider_vc_map' );

  if ( ! function_exists( 'elsey_banner_slider_vc_map' ) ) {

	  function elsey_banner_slider_vc_map() {

	    vc_map( array(
	      'name'                 => esc_html__('Banner Slider', 'elsey-core'),
	      'base'                 => 'elsey_banner_slider',
	      'description'          => esc_html__('Banner Slider Styles', 'elsey-core'),
	      'icon'                 => 'fa fa-picture-o color-olive',
	      'category'             => ElseyLib::elsey_cat_name(),
	      'params'               => array(

	        // Slider Settings
	        array(
	          'type'             => 'notice',
	          'heading'          => esc_html__('Slider Settings', 'elsey-core'),
	          'param_name'       => 'slst_opt',
	          'class'            => 'cs-info',
	          'value'            => '',
	        ),
					array(
	          'type' 			 			 => 'switcher',
	          'heading' 		 		 => esc_html__('Adaptive Height', 'elsey-core'),
	          'param_name' 	     => 'adaptive_height',
	          'description'	     => esc_html__('Enable adaptive height for each slide.', 'elsey-core'),
	          'value'            => '',
	          'std'              => false,
	          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
					),
        	array(
				  	'type' 			 			 => 'switcher',
					  'heading' 		 		 => esc_html__('Infinite Loop', 'elsey-core'),
					  'param_name' 	     => 'infinite_loop',
					  'description'	     => esc_html__('Infinite loop sliding.', 'elsey-core'),
	          'value'            => '',
	          'std'              => true,
	          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
					),
					array(
			      'type' 			 		   => 'switcher',
					  'heading' 		     => esc_html__('Arrows', 'elsey-core'),
					  'param_name' 	     => 'arrows',
					  'description'	     => esc_html__('Show "prev" and "next" arrows.', 'elsey-core'),
	          'value'            => '',
	          'std'              => true,
	          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
					),
        	array(
					  'type' 			 		   => 'switcher',
					  'heading' 		 	   => esc_html__('Autoplay', 'elsey-core'),
					  'param_name' 	     => 'autoplay',
					  'description'	     => esc_html__('Enable autoplay.', 'elsey-core'),
					  'value'            => '',
	          'std'              => false,
	          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
					),
	        array(
					  'type' 			 			 => 'switcher',
					  'heading' 		 		 => esc_html__('Pause On Hover', 'elsey-core'),
					  'param_name' 	     => 'pause_hover',
					  'description'	     => esc_html__('Enable pause on hover.', 'elsey-core'),
					  'value'            => '',
	          'std'              => true,
	          'dependency'       => array(
	            'element'        => 'autoplay',
	            'value'          => 'true',
	          ),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
					),
        	array(
					  'type' 			       => 'textfield',
					  'heading' 		     => esc_html__('Autoplay Speed', 'elsey-core'),
					  'param_name' 	     => 'autoplay_speed',
					  'description'	     => esc_html__('Enter autoplay interval in milliseconds.', 'elsey-core'),
			          'dependency'   => array(
			            'element'    => 'autoplay',
			            'value'      => 'true',
			          ),
			      'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
					),

	        // Slider Styling
	        array(
	          'type'             => 'notice',
	          'heading'          => esc_html__('Slider Styling', 'elsey-core'),
	          'param_name'       => 'slslng_opt',
	          'class'            => 'cs-info',
	          'value'            => '',
	        ),
       		array(
					  'type' 			 		   => 'colorpicker',
					  'heading' 		 		 => esc_html__('Background Color', 'elsey-core'),
					  'param_name' 	     => 'background_color',
					  'description'	     => esc_html__('Set a background color.', 'elsey-core'),
			      'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
					),
        	array(
					  'type' 			 			 => 'dropdown',
					  'heading' 		 		 => esc_html__('Animation Type', 'elsey-core'),
					  'param_name' 	     => 'animation',
					  'description'	     => esc_html__('Select animation type.', 'elsey-core'),
				      'value' 		     => array(
					    esc_html__('Fade', 'elsey-core')   => 'fade',
					    esc_html__('Slide', 'elsey-core')  => 'slide'
					  ),
					  'std' 			 			 => 'slide',
	          'admin_label'      => true,
	          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
					),
					array(
					  'type' 			       => 'textfield',
					  'heading' 		     => esc_html__('Animation Speed', 'elsey-core'),
					  'param_name' 	     => 'anim_speed',
					  'description'	     => esc_html__('Enter animation speed in milliseconds.', 'elsey-core'),
			      'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
					),

          // Banner Listing
	        array(
					  'type'             => 'notice',
					  'heading'          => esc_html__('Banner Listing', 'elsey-core'),
					  'param_name'       => 'pr_lsng_opt',
					  'class'            => 'cs-info',
					  'value'            => '',
					),
	        array(
	          'type'             => 'param_group',
	          'value'            => '',
	          'heading'          => esc_html__('Banners', 'elsey-core'),
	          'param_name'       => 'prlists',
	          'params'           => array(
		          array(
		            'type'             => 'attach_image',
		            'value'            => '',
		            'heading'          => esc_html__( 'Banner Image', 'elsey-core' ),
		            'param_name'       => 'pr_img',
		            'description'      => esc_html__( 'Upload banner image.', 'elsey-core'),
		          ),
		          array(
		            'type'             => 'textfield',
		            'value'            => '',
		            'heading'          => esc_html__( 'Top Offer Text', 'elsey-core' ),
		            'param_name'       => 'pr_top_offer_text',
		            'description'	     => esc_html__( 'Enter banner top offer text.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'textfield',
		            'value'            => '',
		            'heading'          => esc_html__( 'Main Title Text One', 'elsey-core' ),
		            'param_name'       => 'pr_title_text_one',
		            'description'	     => esc_html__( 'Enter banner title one.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'textfield',
		            'value'            => '',
		            'heading'          => esc_html__( 'Main Title Text Two', 'elsey-core' ),
		            'param_name'       => 'pr_title_text_two',
		            'description'	     => esc_html__( 'Enter banner title two.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'textfield',
		            'value'            => '',
		            'heading'          => esc_html__( 'Main Title Link', 'elsey-core' ),
		            'param_name'       => 'pr_title_link',
		            'description'	     => esc_html__( 'Enter a valid title link.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'textfield',
		            'value'            => '',
		            'heading'          => esc_html__( 'Sub Title Text', 'elsey-core' ),
		            'param_name'       => 'pr_sub_title_text',
		            'description'	     => esc_html__( 'Enter banner sub title.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'textarea',
		            'value'            => '',
		            'heading'          => esc_html__( 'Banner Details One', 'elsey-core' ),
		            'param_name'       => 'pr_details',
		            'description'	     => esc_html__( 'Enter banner details.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'textarea',
		            'value'            => '',
		            'heading'          => esc_html__( 'Banner Details Two', 'elsey-core' ),
		            'param_name'       => 'pr_details_two',
		            'description'	     => esc_html__( 'Enter banner details.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
		          ),
		          array(
			    		  'type' 			 			 => 'textfield',
			    		  'heading' 		 		 => esc_html__( 'Button One Text', 'elsey-core' ),
			    		  'param_name' 	     => 'shop_now_title',
		            'description'	     => esc_html__( 'Enter a button one title.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		    		  ),
			    		array(
			    		  'type' 			 			 => 'textfield',
			    		  'heading' 		 		 => esc_html__( 'Button One Link', 'elsey-core' ),
			    		  'param_name' 	     => 'shop_now_link',
		            'description'	     => esc_html__( 'Enter a valid button one link.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
			    		),
			    		array(
			    		  'type' 			 			 => 'textfield',
			    		  'heading' 		 		 => esc_html__( 'Button Two Text', 'elsey-core' ),
			    		  'param_name' 	     => 'view_now_title',
		            'description'	     => esc_html__( 'Enter a button two title.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		    		  ),
			    		array(
			    		  'type' 			 			 => 'textfield',
			    		  'heading' 		 		 => esc_html__( 'Button Two Link', 'elsey-core' ),
			    		  'param_name' 	     => 'view_now_link',
		            'description'	     => esc_html__( 'Enter a valid button two link.', 'elsey-core' ),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
			    		),
		          array(
		    		  	'type' 			       => 'dropdown',
		            'heading' 		     => esc_html__('Text Position', 'elsey-core' ),
		            'param_name' 	     => 'text_position',
		            'value' 		       => array(			           
				           	esc_html__('Center Left', 'elsey-core')   => 'h_left-v_center',
				            esc_html__('Center Center', 'elsey-core') => 'h_center-v_center',
				           	esc_html__('Center Right', 'elsey-core')  => 'h_right-v_center',			           
		            ),
		  		  		'std' 			       => 'h_left-v_center',
		            'description'	     => esc_html__('Select text position.', 'elsey-core'),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		    			),
		          array(
			    		  'type' 			       => 'dropdown',
			    		  'heading' 		     => esc_html__('Text Alignment', 'elsey-core'),
			          'param_name' 	     => 'text_alignment',
			    		  'description'	     => esc_html__('Select text alignment.', 'elsey-core'),
			    		  'value' 		       => array(
			    		    esc_html__('Left', 'elsey-core')   => 'align_left',
			    		    esc_html__('Center', 'elsey-core') => 'align_center',
			    		    esc_html__('Right', 'elsey-core')  => 'align_right'
			    		  ),
			    		  'std' 			       => 'align_left',
			          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
			    		),
			    		array(
			    		  'type' 			       => 'textfield',
			    		  'heading' 		     => esc_html__('Text Padding', 'elsey-core'),
			    		  'param_name' 	     => 'text_padding',
			    		  'description'	     => esc_html__('Enter text padding (value is in percent (%), enter numbers only).', 'elsey-core'),
			          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
			        ),
			    		array(
			    		  'type' 			       => 'dropdown',
			    		  'heading' 		     => esc_html__( 'Text Animation', 'elsey-core' ),
			          'param_name' 	     => 'text_animation',
			    		  'description'	     => esc_html__( 'Select slider text animation.', 'elsey-core' ),
			    		  'value' 		       => array(
			    		    esc_html__('Flash', 'elsey-core')              => 'flash',
			    		    esc_html__('Bounce', 'elsey-core')             => 'bounce',
			    		    esc_html__('Shake', 'elsey-core')              => 'shake',
			    		    esc_html__('Tada', 'elsey-core')               => 'tada',
			    		    esc_html__('Swing', 'elsey-core')              => 'swing',
			    		    esc_html__('Wobble', 'elsey-core')             => 'wobble',
			    		    esc_html__('Pulse', 'elsey-core')              => 'pulse',
			    		    esc_html__('Flip', 'elsey-core')               => 'flip',
			    		    esc_html__('FlipInX', 'elsey-core')            => 'flipInX',
			    		    esc_html__('FlipInY', 'elsey-core')            => 'flipInY',
			    		    esc_html__('FadeIn', 'elsey-core')             => 'fadeIn',
			    		    esc_html__('FadeInUp', 'elsey-core')           => 'fadeInUp',
			    		    esc_html__('FadeInDown', 'elsey-core')         => 'fadeInDown',
			    		    esc_html__('FadeInLeft', 'elsey-core')         => 'fadeInLeft',
			    		    esc_html__('FadeInRight', 'elsey-core')        => 'fadeInRight',
			    		    esc_html__('FadeInUpBig', 'elsey-core')        => 'fadeInUpBig',
			    		    esc_html__('FadeInDownBig', 'elsey-core')      => 'fadeInDownBig',
			    		    esc_html__('FadeInLeftBig', 'elsey-core')      => 'fadeInLeftBig',
			    		    esc_html__('FadeInRightBig', 'elsey-core')     => 'fadeInRightBig',
			    		    esc_html__('BounceIn', 'elsey-core')           => 'bounceIn',
			    		    esc_html__('BounceInDown', 'elsey-core')       => 'bounceInDown',
			    		    esc_html__('BounceInUp', 'elsey-core')         => 'bounceInUp',
			    		    esc_html__('BounceInLeft', 'elsey-core')       => 'bounceInLeft',
			    		    esc_html__('BounceInRight', 'elsey-core')      => 'bounceInRight',
			    		    esc_html__('RotateIn', 'elsey-core')           => 'rotateIn',
			    		    esc_html__('RotateInDownLeft', 'elsey-core')   => 'rotateInDownLeft',
			    		    esc_html__('RotateInDownRight', 'elsey-core')  => 'rotateInDownRight',
			    		    esc_html__('RotateInUpLeft', 'elsey-core')     => 'rotateInUpLeft',
			    		    esc_html__('RotateInUpRight', 'elsey-core')    => 'rotateInUpRight',
			    		    esc_html__('RollIn', 'elsey-core')             => 'rollIn',
			    		  ),
			    		  'std' 			       => 'align_left',
			          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
			    		),
			    		array(
		            'type'             => 'colorpicker',
		            'heading'          => esc_html__( 'Top Offer Text Color', 'elsey-core' ),
		            'param_name'       => 'offer_text_color',
		            'value'            => '',
		            'description'      => esc_html__( 'Select top offer title text color.', 'elsey-core'),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
			    		array(
		            'type'             => 'colorpicker',
		            'heading'          => esc_html__( 'Title Text Color', 'elsey-core' ),
		            'param_name'       => 'title_text_color',
		            'value'            => '',
		            'description'      => esc_html__( 'Select title text color.', 'elsey-core'),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'colorpicker',
		            'heading'          => esc_html__( 'Sub Title Text Color', 'elsey-core' ),
		            'param_name'       => 'subtitle_text_color',
		            'value'            => '',
		            'description'      => esc_html__( 'Select sub title text color.', 'elsey-core'),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'colorpicker',
		            'heading'          => esc_html__( 'Banner Details Text Color', 'elsey-core' ),
		            'param_name'       => 'details_text_color',
		            'value'            => '',
		            'description'      => esc_html__( 'Select banner details text color.', 'elsey-core'),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'colorpicker',
		            'heading'          => esc_html__( 'Button One Text Color', 'elsey-core' ),
		            'param_name'       => 'shopnow_text_color',
		            'value'            => '',
		            'description'      => esc_html__( 'Select button one text color.', 'elsey-core'),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
		          array(
		            'type'             => 'colorpicker',
		            'heading'          => esc_html__( 'Button Two Text Color', 'elsey-core' ),
		            'param_name'       => 'viewnow_text_color',
		            'value'            => '',
		            'description'      => esc_html__( 'Select button two text color.', 'elsey-core'),
		            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		          ),
        		),
        	),

          ElseyLib::elsey_class_option(),

        )
      ) );
		}
  }
}
