<?php

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_width_desktop'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Width (Desktop)', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '300',
		'max'  => '600',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '400',
	'transport'=>'postMessage',
	'output' => array(
		array(
			'element'  => array('.wooqv-slider-wrapper', '.wooqv-slider li img'),
			'property' => 'width',
			'value_pattern' => '$px',
			'media_query' => '@media (min-width: 1123px)',
		)
	),
	'js_vars' => array(
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-desktop-slider-width'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_height_desktop'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Height (Desktop)', 'woo-quick-view' ),
	'type'     => 'slider',
	'choices'  => array(
		'min'  => '300',
		'max'  => '600',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '400',
	'transport'=>'postMessage',
	'output' => array(
		array(
			'element'  => array('.wooqv-slider-wrapper', '.wooqv-item-info'),
			'property' => 'height',
			'value_pattern' => '$px',
			'media_query' => '@media (min-width: 1123px)',
		)
	),
	'js_vars' => array(
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-desktop-slider-height'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_width_mobile'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Width (Tablet / Mobile)', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '200',
		'max'  => '500',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '430',
	'transport'=>'postMessage',
	'output' => array(
		array(
			'element'  => array('.woo-quick-view', '.wooqv-slider-wrapper'),
			'property' => 'width',
			'value_pattern' => '$px',
			'media_query' => '@media (max-width: 1022px)',
		)
	),
	'js_vars' => array(
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-mobile-slider-width'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_height_mobile'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Height (Tablet / Mobile)', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '200',
		'max'  => '500',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '340',
	'transport'=>'postMessage',
	'output' => array(
		array(
			'element'  => array('.wooqv-slider-wrapper'),
			'property' => 'height',
			'value_pattern' => '$px',
			'media_query' => '@media (max-width: 1022px)',
		)
	),
	'js_vars' => array(
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-mobile-slider-height'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_animation'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Animation', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'slide' 	=> esc_attr__( 'Slide', 'woo-quick-view' ),
		'fade' 	=> esc_attr__( 'Fade', 'woo-quick-view' ),
	),
	'default'     => 'slide',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_autoplay'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Auto Play', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'0' 	=> esc_attr__( 'Disabled', 'woo-quick-view' ),
		'1' 	=> esc_attr__( 'Enabled', 'woo-quick-view' ),
	),
	'default'     => '1',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('wqv_'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Grayscale Transition', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'0' 	=> esc_attr__( 'Disable', 'woo-quick-view' ),
		'1' 	=> esc_attr__( 'Enable', 'woo-quick-view' ),
	),
	'default'     => '1',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_lightbox_enabled'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider LightBox', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'0' 	=> esc_attr__( 'Disable', 'woo-quick-view' ),
		'1' 	=> esc_attr__( 'Enable', 'woo-quick-view' ),
	),
	'default'     => '0',
	'priority'    => 10
));


Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_arrows_enabled'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Arrows', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'0' 	=> esc_attr__( 'Disable', 'woo-quick-view' ),
		'1' 	=> esc_attr__( 'Enable', 'woo-quick-view' ),
	),
	'default'     => '1',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_arrow'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Arrows Icon', 'woo-quick-view' ),
	'type'     => 'wooqvicons',
	'choices'  => array('types' => array('arrow')),
	'priority' => 10,
	'default'  => 'wooqvicon-arrows-18',
	'transport'=>'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.wooqv-arrow-icon',
			'function' =>'class'
		),
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-slider-arrow'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_arrows_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_arrow_size'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Arrows Size', 'woo-quick-view' ),
	'type'     => 'slider',
	'choices'     => array(
		'min'  => '0',
		'max'  => '80',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '40',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-slider-wrapper .lSAction .lSPrev','.wooqv-slider-wrapper .lSAction .lSNext'),
			'property' => 'font-size',
			'value_pattern' => '$px'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_arrows_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_arrow_color'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Arrows Icon Color', 'woo-quick-view' ),
	'type'     => 'color-alpha',
	'priority' => 10,
	'default'     => 'rgba(255,255,255,0.7)',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-slider-wrapper .lSAction .lSPrev','.wooqv-slider-wrapper .lSAction .lSNext'),
			'property' => 'color',
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_arrows_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_arrow_hover_color'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Arrows Icon Hover Color', 'woo-quick-view' ),
	'type'     => 'color-alpha',
	'priority' => 10,
	'default'  => 'rgba(255,255,255,1)',
	'transport'=>'auto',
	'output' => array(

		array(
			'element'  => array('.wooqv-no-touchevents .wooqv-slider-wrapper .lSAction .lSPrev:hover','.wooqv-no-touchevents .wooqv-slider-wrapper .lSAction .lSNext:hover'),
			'property' => 'color',
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_arrows_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_thumb_gallery_enabled'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Thumb Gallery', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'0' 	=> esc_attr__( 'Disable', 'woo-quick-view' ),
		'1' 	=> esc_attr__( 'Enable', 'woo-quick-view' ),
	),
	'default'     => '1',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_thumb_gallery_visible_hover'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Thumb Gallery Visible on Hover only', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'0' 	=> esc_attr__( 'No', 'woo-quick-view' ),
		'1' 	=> esc_attr__( 'Yes', 'woo-quick-view' ),
	),
	'default'     => '1',
	'priority'    => 10,
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_thumb_gallery_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_thumb_gallery_visible'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Gallery Visible Thumbs', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '3',
		'max'  => '15',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '6',
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_thumb_gallery_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_thumb_gallery_active_border_width'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Gallery Active Thumb Border Width', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '1',
		'max'  => '100',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '5',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.wooqv-slider-wrapper .lSGallery li:after',
			'property' => 'border-width',
			'value_pattern' => '$px'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_thumb_gallery_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_slider_thumb_gallery_active_border_color'),
	'section'  => 'modal-product-slider',
	'label'    => esc_html__( 'Slider Gallery Active Thumb Border Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => 'rgba(164,100,151,0.8)',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.wooqv-slider-wrapper .lSGallery li:after',
			'property' => 'border-color'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_slider_thumb_gallery_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));


