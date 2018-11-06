<?php

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_nav_icon'),
	'section'  => 'modal-navigation',
	'label'    => esc_html__( 'Navigation Arrow Icon', 'woo-quick-view' ),
	'type'     => 'wooqvicons',
	'choices'  => array('types' => array('arrow')),
	'priority' => 10,
	'default'  => 'wooqvicon-arrows-22',
	'transport'=>'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.wooqv-nav-icon',
			'function' =>'class'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_nav_icon_size'),
	'section'  => 'modal-navigation',
	'label'    => esc_html__( 'Navigation Arrow Icon Size', 'woo-quick-view' ),
	'type'     => 'slider',
	'choices'     => array(
		'min'  => '0',
		'max'  => '80',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '30',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-nav-icon'),
			'property' => 'font-size',
			'value_pattern' => '$px'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_nav_color'),
	'section'  => 'modal-navigation',
	'label'    => esc_html__( 'Navigation Arrow Icon Color', 'woo-quick-view' ),
	'type'     => 'color-alpha',
	'priority' => 10,
	'default'  => '#ffffff',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-nav-icon'),
			'property' => 'color',
		)
	)
));
Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_nav_hover_color'),
	'section'  => 'modal-navigation',
	'label'    => esc_html__( 'Navigation Arrow Icon Hover Color', 'woo-quick-view' ),
	'type'     => 'color-alpha',
	'priority' => 10,
	'default'  => '#ffffff',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-no-touchevents .wooqv-nav-icon:hover'),
			'property' => 'color',
		)
	)
));