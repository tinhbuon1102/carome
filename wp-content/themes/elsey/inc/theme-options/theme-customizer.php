<?php
/*
 * All customizer related options for elsey theme.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

if( ! function_exists( 'elsey_vt_customizer' ) ) {

  function elsey_vt_customizer( $options ) {

		$options        = array(); // remove old options

		// Primary Color
		$options[]      = array(
		  'name'        => 'elemets_color_section',
		  'title'       => esc_html__('Primary Color', 'elsey'),
		  'settings'    => array(
		    // Fields Start
				array(
					'name'      		=> 'all_element_colors',
					'default'   		=> '#ff7645',
					'control'   		=> array(
						'type'  		=> 'color',
						'label' 		=> esc_html__('Elements Color', 'elsey'),
						'description'  		=> esc_html__('This is theme primary color, means it\'ll affect all elements that have default color of our theme primary color.', 'elsey'),
					),
				),
		    // Fields End
	  	)
		);
		// Primary Color

		// Preloader Color
		$options[]      = array(
		  'name'        => 'preloader_color_section',
		  'title'       => esc_html__('01. Preloader Colors', 'elsey'),
		  'settings'    => array(
				// Fields Start
				array(
					'name'      	=> 'preloader_color',
					'control'   	=> array(
						'type'  	=> 'color',
						'label' 	=> esc_html__('Preloader Color', 'elsey'),
					),
				),
				array(
					'name'      	=> 'preloader_bg_color',
					'control'   	=> array(
						'type'  	=> 'color',
						'label' 	=> esc_html__('Preloader Background Color', 'elsey'),
					),
				),
			  // Fields End
		  )
		);
		// Preloader Color

		// Topbar Color
		$options[]      = array(
		  'name'        => 'topbar_color_section',
		  'title'       => esc_html__('02. Top Bar Colors', 'elsey'),
		  'settings'    => array(

		    // Fields Start
		    array(
					'name'          => 'topbar_bg_heading',
					'control'       => array(
						'type'        => 'cs_field',
						'options'     => array(
							'type'      => 'notice',
							'class'     => 'info',
							'content'   => esc_html__('Top Bar Color', 'elsey'),
						),
					),
				),
				array(
					'name'      		=> 'topbar_bg_color',
					'control'   		=> array(
						'type'  		=> 'color',
						'label' 		=> esc_html__('Background Color', 'elsey'),
					),
				),
				array(
					'name'      		=> 'topbar_border_color',
					'control'   		=> array(
						'type'  		=> 'color',
						'label' 		=> esc_html__('Border Color', 'elsey'),
					),
				),
				array(
					'name'          => 'topbar_text_heading',
					'control'       => array(
						'type'        => 'cs_field',
						'options'     => array(
							'type'      => 'notice',
							'class'     => 'info',
							'content'   => esc_html__('Content Color', 'elsey'),
						),
					),
				),
				array(
					'name'      		=> 'topbar_text_color',
					'control'   		=> array(
						'type'  		=> 'color',
						'label' 		=> esc_html__('Text Color', 'elsey'),
					),
				),
				array(
					'name'      		=> 'topbar_link_color',
					'control'   		=> array(
						'type'  		=> 'color',
						'label' 		=> esc_html__('Link Color', 'elsey'),
					),
				),
				array(
					'name'      		=> 'topbar_link_hover_color',
					'control'   		=> array(
						'type'  		=> 'color',
						'label' 		=> esc_html__('Link Hover Color', 'elsey'),
					),
				),
		    // Fields End
		  )
		);
		// Topbar Color

		// Header Color
		$options[]      = array(
		  'name'        => 'menubar_color_section',
		  'title'       => esc_html__('03. Menu Bar Colors', 'elsey'),
		  'sections'    => array(

				// Footer Widgets Block
		  	array(
		      'name'          => 'main_menubar_section',
		      'title'         => esc_html__('Default Menu', 'elsey'),
		      'settings'      => array(	      	
				    // Fields Start	    
				    array(
							'name'          => 'menubar_bg_heading',
							'control'       => array(
								'type'        => 'cs_field',
								'options'     => array(
									'type'      => 'notice',
									'class'     => 'info',
									'content'   => esc_html__('Menu Bar Color', 'elsey'),
								),
							),
						),
						array(
							'name'          => 'menubar_bg_color',
							'control'       => array(
								'type'      => 'color',
								'label'     => esc_html__('Background Color', 'elsey'),
							),
						),
						array(
							'name'          => 'menubar_mainmenu_heading',
							'control'       => array(
								'type'        => 'cs_field',
								'options'     => array(
									'type'      => 'notice',
									'class'     => 'info',
									'content'   => esc_html__('Main Menu Colors', 'elsey'),
								),
							),
						),
						array(
							'name'          => 'menubar_mainmenu_link_color',
							'control'       => array(
								'type'      => 'color',
								'label'     => esc_html__('Link Color', 'elsey'),
							),
						),
						array(
							'name'          => 'menubar_mainmenu_link_hover_color',
							'control'       => array(
								'type'      => 'color',
								'label'     => esc_html__('Link Hover Color', 'elsey'),
							),
						),

						// Sub Menu Color
						array(
							'name'          => 'menubar_submenu_heading',
							'control'       => array(
								'type'        => 'cs_field',
								'options'     => array(
									'type'      => 'notice',
									'class'     => 'info',
									'content'   => esc_html__('Sub-Menu Colors', 'elsey'),
								),
							),
						),
						array(
							'name'          => 'menubar_submenu_bg_color',
							'control'       => array(
								'type'      => 'color',
								'label'     => esc_html__('Background Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'menubar_submenu_link_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Link Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'menubar_submenu_link_hover_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Link Hover Color', 'elsey'),
							),
						),
				    // Fields End

					)
				),
				// Footer Widgets Block

				// Footer Copyright Block
		  	array(
		      'name'          => 'trans_menubar_section',
		      'title'         => esc_html__('Transparent Menu', 'elsey'),
		      'settings'      => array(

						// Fields Start
						array(
							'name'          => 'trans_menubar_mainmenu_heading',
							'control'       => array(
								'type'        => 'cs_field',
								'options'     => array(
									'type'      => 'notice',
									'class'     => 'info',
									'content'   => esc_html__('Main Menu Colors', 'elsey'),
								),
							),
						),
						array(
							'name'          => 'trans_menubar_mainmenu_link_color',
							'control'       => array(
								'type'      => 'color',
								'label'     => esc_html__('Link Color', 'elsey'),
							),
						),
						array(
							'name'          => 'trans_menubar_mainmenu_link_hover_color',
							'control'       => array(
								'type'      => 'color',
								'label'     => esc_html__('Link Hover Color', 'elsey'),
							),
						),

						// Sub Menu Color
						array(
							'name'          => 'trans_menubar_submenu_heading',
							'control'       => array(
								'type'        => 'cs_field',
								'options'     => array(
									'type'      => 'notice',
									'class'     => 'info',
									'content'   => esc_html__('Sub-Menu Colors', 'elsey'),
								),
							),
						),
						array(
							'name'          => 'trans_menubar_submenu_bg_color',
							'control'       => array(
								'type'      => 'color',
								'label'     => esc_html__('Background Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'trans_menubar_submenu_link_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Link Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'trans_menubar_submenu_link_hover_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Link Hover Color', 'elsey'),
							),
						),
				    // Fields End

				  )
				),

		  )
		);
		// Header Color

		// Title Bar Color
		$options[]      = array(
		  'name'        => 'titlebar_section',
		  'title'       => esc_html__('04. Title Bar Colors', 'elsey'),
	    'settings'    => array(
	    	// Fields Start
	    	array(
					'name'          => 'titlebar_colors_heading',
					'control'       => array(
						'type'        => 'cs_field',
						'options'     => array(
							'type'      => 'notice',
							'class'     => 'info',
							'content'   => esc_html__('This is common settings, if this settings not affect in your page. Please check your page metabox. You may set default settings there.', 'elsey'),
						),
					),
				),
				array(
					'name'          => 'titlebar_bg_heading',
					'control'       => array(
						'type'        => 'cs_field',
						'options'     => array(
							'type'      => 'notice',
							'class'     => 'info',
							'content'   => esc_html__('Title Bar Color', 'elsey'),
						),
					),
				),
				array(
					'name'          => 'titlebar_bg_color',
					'control'       => array(
						'type'      => 'color',
						'label'     => esc_html__('Background Color', 'elsey'),
					),
				),
				array(
					'name'          => 'titlebar_common_heading',
					'control'       => array(
						'type'        => 'cs_field',
						'options'     => array(
							'type'      => 'notice',
							'class'     => 'info',
							'content'   => esc_html__('Content Colors', 'elsey'),
						),
					),
				),
				array(
					'name'      		=> 'titlebar_title_color',
					'control'   		=> array(
						'type'  		=> 'color',
						'label'		 	=> esc_html__('Title Text Color', 'elsey'),
					),
				),
	    	array(
					'name'     		  => 'titlebar_breadcrumbs_color',
					'control'   		=> array(
						'type'  		=> 'color',
						'label'     => esc_html__('Breadcrumbs Text Color', 'elsey'),
					),
				),
		    // Fields End
		  )
		);
		// Title Bar Color

		// Content Color
		$options[]      = array(
		  'name'        => 'content_section',
		  'title'       => esc_html__('05. Content Colors', 'elsey'),
		  'description' => esc_html__('This is all about content area text and heading colors.', 'elsey'),
		  'sections'    => array(
		  	array(
		      'name'          => 'content_text_section',
		      'title'         => esc_html__('Content Text', 'elsey'),
		      'settings'      => array(
				    // Fields Start
				    array(
							'name'      => 'body_color',
							'control'   => array(
								'type'  => 'color',
								'label' => esc_html__('Body & Content Color', 'elsey'),
							),
						),
						array(
							'name'      => 'body_links_color',
							'control'   => array(
								'type'  => 'color',
								'label' => esc_html__('Body Links Color', 'elsey'),
							),
						),
						array(
							'name'      => 'body_link_hover_color',
							'control'   => array(
								'type'  => 'color',
								'label' => esc_html__('Body Links Hover Color', 'elsey'),
							),
						),
						array(
							'name'      => 'sidebar_content_color',
							'control'   => array(
								'type'  => 'color',
								'label' => esc_html__('Sidebar Content Color', 'elsey'),
							),
						),
				    // Fields End
				  )
				),
				// Text Colors Section
				array(
		      'name'          => 'content_heading_section',
		      'title'         => esc_html__('Headings', 'elsey'),
		      'settings'      => array(

		      	// Fields Start
						array(
							'name'      => 'content_heading_color',
							'control'   => array(
								'type'  => 'color',
								'label' => esc_html__('Content Heading Color', 'elsey'),
							),
						),
		      	array(
							'name'      => 'sidebar_heading_color',
							'control'   => array(
								'type'  => 'color',
								'label' => esc_html__('Sidebar Heading Color', 'elsey'),
							),
						),
				    // Fields End
	      	)
	      ),
		  )
		);
		// Content Color

		// Footer Color
		$options[]      = array(
		  'name'        => 'footer_section',
		  'title'       => esc_html__('06. Footer Colors', 'elsey'),
		  'description' => esc_html__('This is all about footer settings. Make sure you\'ve enabled your needed section at : <strong>Elsey > Theme Options > Footer</strong> ', 'elsey'),
		  'sections'    => array(

				// Footer Widgets Block
		  	array(
		      'name'          => 'footer_widget_section',
		      'title'         => esc_html__('Widget Block', 'elsey'),
		      'settings'      => array(

				    // Fields Start
						array(
				      'name'          => 'footer_widget_color_notice',
				      'control'       => array(
				        'type'        => 'cs_field',
				        'options'     => array(
				          'type'      => 'notice',
				          'class'     => 'info',
				          'content'   => esc_html__('Content Colors', 'elsey'),
				        ),
				      ),
				    ),
						array(
							'name'      		=> 'footer_heading_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Widget Heading Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'footer_text_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Widget Text Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'footer_link_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Widget Link Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'footer_link_hover_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Widget Link Hover Color', 'elsey'),
							),
						),
				    // Fields End
				  )
				),
				// Footer Widgets Block

				// Footer Copyright Block
		  	array(
		      'name'          => 'footer_copyright_section',
		      'title'         => esc_html__('Copyright Block', 'elsey'),
		      'settings'      => array(
				    // Fields Start
				    array(
				      'name'          => 'footer_copyright_active',
				      'control'       => array(
				        'type'        => 'cs_field',
				        'options'     => array(
				          'type'      => 'notice',
				          'class'     => 'info',
				          'content'   => __('Make sure you\'ve enabled copyright block in : <br /> <strong>Elsey > Theme Options > Footer > Copyright Bar : Enable Copyright Block</strong>', 'elsey'),
				        ),
				      ),
				    ),
				    array(
							'name'      		=> 'copyright_bg_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Background Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'copyright_text_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Text Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'copyright_link_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Link Color', 'elsey'),
							),
						),
						array(
							'name'      		=> 'copyright_link_hover_color',
							'control'   		=> array(
								'type'  		=> 'color',
								'label' 		=> esc_html__('Link Hover Color', 'elsey'),
							),
						),
						// Fields End
				  )
				),
				// Footer Copyright Block
		  )
		);
		// Footer Color

		return $options;

  }
  add_filter( 'cs_customize_options', 'elsey_vt_customizer' );
}