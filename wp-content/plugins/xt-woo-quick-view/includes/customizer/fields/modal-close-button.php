<?php

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_close_enabled'),
	'section'  => 'modal-close-button',
	'label'    => esc_html__( 'Slider Close Button', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'0' 	=> esc_attr__( 'Disable', 'woo-quick-view' ),
		'1' 	=> esc_attr__( 'Enable', 'woo-quick-view' ),
	),
	'default'     => '1',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_close_icon'),
	'section'  => 'modal-close-button',
	'label'    => esc_html__( 'Slider Close Icon', 'woo-quick-view' ),
	'type'     => 'wooqvicons',
	'choices'  => array('types' => array('close')),
	'priority' => 10,
	'default'  => 'wooqvicon-cancel-4',
	'transport'=>'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.wooqv-close-icon',
			'function' =>'class'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_close_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_close_size'),
	'section'  => 'modal-close-button',
	'label'    => esc_html__( 'Slider Close Icon Size', 'woo-quick-view' ),
	'type'     => 'slider',
	'choices'     => array(
		'min'  => '0',
		'max'  => '80',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '25',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-close-icon'),
			'property' => 'font-size',
			'value_pattern' => '$px'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_close_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_close_color'),
	'section'  => 'modal-close-button',
	'label'    => esc_html__( 'Slider Close Icon Color', 'woo-quick-view' ),
	'type'     => 'color-alpha',
	'priority' => 10,
	'default'     => '#111111',
	'transport'=>'auto',
	'output' => array(
		array(
			'property' => 'color',
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_close_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_close_hover_color'),
	'section'  => 'modal-close-button',
	'label'    => esc_html__( 'Slider Close Icon Hover Color', 'woo-quick-view' ),
	'type'     => 'color-alpha',
	'priority' => 10,
	'default'     => '#111111',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-no-touchevents .wooqv-close-icon:hover'),
			'property' => 'color',
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('modal_close_enabled'),
			'operator' => '==',
			'value'    => '1',
		),
	)
));