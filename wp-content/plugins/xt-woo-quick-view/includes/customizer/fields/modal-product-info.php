<?php
	
$default_font = 'Open Sans';	

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_title'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Title Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(
		'font-family'    => $default_font,
		'variant'        => '700',
		'font-size'      => '30px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'capitalize',
		'color' => '#111111'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .product_title',
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		),
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .product_title',
			'media_query' => '@media (max-width: 479px)',
			'value_pattern' => array(
				'font-size' => 'calc($ * 0.75)'
			)	
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('product_rating_stars_size'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Rating Stars Size', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '12',
		'max'  => '40',
		'step' => '1',
	),
	'priority' => 10,
	'default'  => '14',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .woocommerce-product-rating .star-rating',
			'property' => 'font-size',
			'value_pattern' => '$px'
		)
	)
));
Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('product_rating_stars_color'),
	'section'  => 'modal-product-info',
	'label'       => esc_attr__( 'Product Rating Stars Color', 'woo-quick-view' ),
	'type'     => 'color',
	'priority' => 10,
	'default'  => '#A46497',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .woocommerce-product-rating .star-rating',
			'property' => 'color',
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_price'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Price Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(

		'font-family'    => $default_font,
		'variant'        => '400',
		'font-size'      => '18px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'none',
		'color' => '#77a464'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => array('.woocommerce div.product .wooqv-item-info p.price', '.woocommerce div.product .wooqv-item-info span.price'),
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		),
		array(
			'element'  => array('.woocommerce div.product .wooqv-item-info p.price', '.woocommerce div.product .wooqv-item-info span.price'),
			'media_query' => '@media (max-width: 479px)',
			'value_pattern' => array(
				'font-size' => 'calc($ * 0.85)'
			)	
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_disabled_price'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Old Price Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(

		'font-family'    => $default_font,
		'variant'        => '400',
		'font-size'      => '18px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'none',
		'color' => '#333'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => array('.woocommerce div.product .wooqv-item-info p.price del', '.woocommerce div.product .wooqv-item-info span.price del'),
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		),
		array(
			'element'  => array('.woocommerce div.product .wooqv-item-info p.price del', '.woocommerce div.product .wooqv-item-info span.price del'),
			'media_query' => '@media (max-width: 479px)',
			'value_pattern' => array(
				'font-size' => 'calc($ * 0.85)'
			)	
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_description'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Description Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(

		'font-family'    => $default_font,
		'variant'        => '400',
		'font-size'      => '14px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'none',
		'color' => '#333333'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.wooqv-item-info p',
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		),
		array(
			'element'  => '.wooqv-item-info p',
			'media_query' => '@media (max-width: 479px)',
			'value_pattern' => array(
				'font-size' => 'calc($ * 0.85)'
			)	
		),
	)
));


Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_meta_labels'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Meta Labels Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(

		'font-family'    => $default_font,
		'variant'        => '400',
		'font-size'      => '14px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'none',
		'color' => '#333333'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.wooqv-item-info .product_meta',
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		),
		array(
			'element'  => '.wooqv-item-info .product_meta',
			'media_query' => '@media (max-width: 479px)',
			'value_pattern' => array(
				'font-size' => 'calc($ * 0.85)'
			)	
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_meta_links'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Meta Links Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(

		'font-family'    => $default_font,
		'variant'        => '400',
		'font-size'      => '14px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'none',
		'color' => '#a46497'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.wooqv-item-info .product_meta a',
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		),
		array(
			'element'  => '.wooqv-item-info .product_meta a',
			'media_query' => '@media (max-width: 479px)',
			'value_pattern' => array(
				'font-size' => 'calc($ * 0.85)'
			)	
		),
	)
));


Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('typo_product_add_to_cart_button_bg'),
	'section'  => 'modal-product-info',
	'label'       => esc_attr__( 'Product Add to Cart Button Background', 'woo-quick-view' ),
	'type'     => 'color',
	'priority' => 10,
	'default'  => '#a46497',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .single_add_to_cart_button',
			'property' => 'background-color',
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_add_to_cart_button'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product Add to Cart Button Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(

		'font-family'    => $default_font,
		'variant'        => '700',
		'font-size'      => '14px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'none',
		'color' => '#ffffff'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .single_add_to_cart_button',
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('typo_product_more_info_button_bg'),
	'section'  => 'modal-product-info',
	'label'       => esc_attr__( 'Product More Info Button Background', 'woo-quick-view' ),
	'type'     => 'color',
	'priority' => 10,
	'default'  => '#ebe9eb',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .wooqv-more-info',
			'property' => 'background-color',
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('typo_product_more_info_button'),
	'section'     => 'modal-product-info',
	'label'       => esc_attr__( 'Product More Info Button Typography', 'woo-quick-view' ),
	'type'        => 'typography',
	'default'     => array(

		'font-family'    => $default_font,
		'variant'        => '700',
		'font-size'      => '14px',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'none',
		'color' => '#515151'
	),
	'priority'    => 10,
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element'  => '.woocommerce div.product .wooqv-item-info .wooqv-more-info',
			'media_query' => '@media (min-width: 480px)',
			'value_pattern' => array(
				'font-size' => '$'
			)
		)
	)
));