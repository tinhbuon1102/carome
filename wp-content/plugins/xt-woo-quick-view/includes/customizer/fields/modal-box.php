<?php

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('close_modal_on_added'),
	'section'  	  => 'modal-box',
	'label'       => esc_html__( 'Close modal when product added to cart', 'woo-quick-view' ),
	'type'        => 'toggle',
	'default'     => '0',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_box_bg_color'),
	'section'  	  => 'modal-box',
	'label'    => esc_html__( 'Modal Box Background Color', 'woo-quick-view' ),
	'type'     => 'color',
	'priority' => 10,
	'default'  => '#ffffff',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.woo-quick-view.wooqv-animate-width',
			'property' => 'background-color',
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_box_shadow_color'),
	'section'  => 'modal-box',
	'label'    => esc_html__( 'Modal Box Shadow Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => 'rgba(0,0,0,0.3)',
	'transport'=>'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-box-shadow-color'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_box_radius'),
	'section'  => 'modal-box',
	'label'    => esc_html__( 'Modal Box Radius', 'woo-quick-view' ),
	'type'     => 'slider',
	'choices'     => array(
		'min'  => '0',
		'max'  => '30',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '0',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.woo-quick-view',
			'property' => 'border-radius',
			'value_pattern' => '$px'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_box_shadow_blur'),
	'section'  => 'modal-box',
	'label'    => esc_html__( 'Modal Box Shadow Blur', 'woo-quick-view' ),
	'type'     => 'slider',
	'choices'     => array(
		'min'  => '0',
		'max'  => '100',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '30',
	'transport'=>'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-box-shadow-blur'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_box_shadow_spread'),
	'section'  => 'modal-box',
	'label'    => esc_html__( 'Modal Box Shadow Spread', 'woo-quick-view' ),
	'type'     => 'slider',
	'choices'     => array(
		'min'  => '0',
		'max'  => '100',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '0',
	'transport'=>'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.woo-quick-view',
			'function' => 'html',
			'attr' => 'wooqv-box-shadow-spread'
		)
	)
));
