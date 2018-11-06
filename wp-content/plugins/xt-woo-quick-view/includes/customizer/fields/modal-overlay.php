<?php

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_overlay_color'),
	'section'  => 'modal-overlay',
	'label'    => esc_html__( 'Modal Overlay Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => 'rgba(71,55,78,0.8)',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => 'body .wooqv-overlay',
			'property' => 'background',
			'value_pattern' => '$!important'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('modal_overlay_spinner'),
	'section'  => 'modal-overlay',
	'label'    => esc_html__( 'Modal Overlay Spinner', 'woo-quick-view' ),
	'type'        => 'radio',
	'priority'    => 10,
	'choices'     => array(
		'0' => esc_html__('No Spinner', 'woo-quick-view'),
		'1-rotating-plane' => esc_html__('Rotating Plane', 'woo-quick-view'),
		'2-double-bounce' => esc_html__('Double Bounce', 'woo-quick-view'),
		'3-wave' => esc_html__('Wave', 'woo-quick-view'),
		'4-wandering-cubes' => esc_html__('Wandering Cubes', 'woo-quick-view'),
		'5-pulse' => esc_html__('Pulse', 'woo-quick-view'),
		'6-chasing-dots' => esc_html__('Chasing Dots', 'woo-quick-view'),
		'7-three-bounce' => esc_html__('Three Bounce', 'woo-quick-view'),
		'8-circle' => esc_html__('Circle', 'woo-quick-view'),
		'9-cube-grid' => esc_html__('Cube Grid', 'woo-quick-view'),
		'10-fading-circle' => esc_html__('Fading Circle', 'woo-quick-view'),
		'11-folding-cube' => esc_html__('Folding Cube', 'woo-quick-view'),
		'loading-text' => esc_html__('Boring Loading Text', 'woo-quick-view')
	),
	'default'     => '7-three-bounce',
	'partial_refresh' => array(
		'cart_spinner' => array(
			'selector'        => '.wooqv-spinner-wrap',
			'render_callback' => function() {
				return wooqv_spinner_html(true, false);
			},
		)
	),
));


Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('modal_overlay_spinner_color'),
	'section'  => 'modal-overlay',
	'label'    => esc_html__( 'Modal Overlay Spinner Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => 'rgba(255,255,255,0.6)',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array(
				'.wooqv-spinner-rotating-plane',
				'.wooqv-spinner-double-bounce .wooqv-spinner-child',
				'.wooqv-spinner-wave .wooqv-spinner-rect',
				'.wooqv-spinner-wandering-cubes .wooqv-spinner-cube',
				'.wooqv-spinner-spinner-pulse',
				'.wooqv-spinner-chasing-dots .wooqv-spinner-child',
				'.wooqv-spinner-three-bounce .wooqv-spinner-child',
				'.wooqv-spinner-circle .wooqv-spinner-child:before',
				'.wooqv-spinner-cube-grid .wooqv-spinner-cube',
				'.wooqv-spinner-fading-circle .wooqv-spinner-circle:before',
				'.wooqv-spinner-folding-cube .wooqv-spinner-cube:before',
			),	
			'property' => 'background-color',
		),
		array(
			'element' => '.wooqv-spinner-loading-text',
			'property' => 'color',
		)
	)
));
