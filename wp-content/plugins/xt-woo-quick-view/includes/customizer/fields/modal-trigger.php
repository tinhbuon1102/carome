<?php
Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('trigger_position'),
	'section'     => 'modal-trigger',
	'label'    	  => esc_html__( 'Quick View Button Position', 'woo-quick-view' ),
	'type'        => 'radio',
	'priority'    => 10,
	'transport'		=>'auto',
	'choices'     => array(
		'before' => esc_html__( 'Before Add to cart button', 'woo-quick-view' ),
		'above' => esc_html__( 'Above add to cart button', 'woo-quick-view' ),		
		'after'  => esc_html__( 'After add to cart button', 'woo-quick-view' ),
		'below'  => esc_html__( 'Below add to cart button', 'woo-quick-view' ),
		'over-product'  => esc_html__( 'Over product container on Hover', 'woo-quick-view' ),
		'over-image'  => esc_html__( 'Over product image on Hover', 'woo-quick-view' ),
		'shortcode'  => esc_html__( 'Via shortcode only', 'woo-quick-view' )
	),
	'default'     => 'before',
));

Kirki::add_field( self::$config_id, array(
	'settings'    	=> self::field_id('trigger_note'),	
	'section'     	=> 'modal-trigger',	
	'type'        	=> 'custom',
	'label'       	=> __( 'Note', 'woo-quick-view' ),
	'default'     	=> '<div style="padding: 10px;background-color: #ffffcc;">If this trigger position does not work on your theme, try <strong>Over Product Container</strong> instead</div>',
	'priority'    	=> 10,
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => '==',
			'value'    => 'over-image',
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    	=> self::field_id('trigger_shortcode'),	
	'section'     	=> 'modal-trigger',	
	'type'        	=> 'custom',
	'label'       	=> __( 'Shortcode', 'woo-quick-view' ),
	'default'     	=> '<div style="padding: 10px;background-color: #ffffcc;">' . esc_html__( '[wooqv_trigger id="PRODUCT_ID"]', 'woo-quick-view' ) . '</div>',
	'priority'    	=> 10,
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => '==',
			'value'    => 'shortcode',
		)
	)
));


Kirki::add_field( self::$config_id, array(
	'settings'    	=> self::field_id('trigger_radius'),
	'section'     	=> 'modal-trigger',
	'label'    	  => esc_html__( 'Quick View Button Radius', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '0',
		'max'  => '300',
		'step' => '1',
	),
	'priority'    	=> 10,
	'default'	  	=> '20',
	'transport'		=>'auto',
	'output' 	=> array(
		array(
			'element'  => '.wooqv-trigger:not(.wooqv-shortcode-trigger).wooqv-over-image',
			'property' =>'border-radius',
			'value_pattern' => '$px!important'	
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => 'in',
			'value'    => array('over-image','over-product'),
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('trigger_overlay'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Add overlay behind the trigger', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'1' => esc_attr__( 'Yes', 'woo-quick-view' ),
		'0' 	=> esc_attr__( 'No', 'woo-quick-view' )
	),
	'default'     => '0',
	'priority'    => 10,
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => 'in',
			'value'    => array('over-image','over-product'),
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('trigger-overlay'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Overlay color behind the trigger', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => 'rgba(10,10,10,0.2)',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.wooqv-product-overlay',
			'property' => 'background-color',
			'value_pattern' => '$!important'
		)
	),
	'js_vars' => array(
		array(
			'element'  => '.wooqv-product-overlay',
			'property' => 'background-color',
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => 'in',
			'value'    => array('over-image','over-product'),
		),
		array(
			'setting'  => self::field_id('trigger_overlay'),
			'operator' => '!=',
			'value'    => '0',
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('add_to_cart_fullwidth'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Force the Add To Cart button to be Fullwidth as well?', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'xt_woovs-fullwidth' => esc_attr__( 'Yes', 'woo-quick-view' ),
		'xt_woovs-inline' 	=> esc_attr__( 'No', 'woo-quick-view' )
	),
	'default'     => 'xt_woovs-fullwidth',
	'priority'    => 10,
	'transport'	  => 'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.product .single_add_to_cart_button',
			'function' =>'class'
		),
		array(
			'element'  => '.product .add_to_cart_button',
			'function' =>'class'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => 'in',
			'value'    => array('above', 'below'),
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('trigger_center_text'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Quick View Button Text align', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'left' => esc_attr__( 'Left', 'woo-quick-view' ),
		'center' 	=> esc_attr__( 'Center', 'woo-quick-view' ),
		'right' 	=> esc_attr__( 'Right', 'woo-quick-view' )
	),
	'default'     => 'left',
	'priority'    => 10,
	'transport'		=>'auto',
	'output' 	=> array(
		array(
			'element'  => '.wooqv-trigger',
			'property' =>'text-align',
			'value_pattern' => '$!important'	
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => 'in',
			'value'    => array('above', 'below'),
		)
	)
));


Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('trigger_padding'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Override Quick View Button Padding', 'woo-quick-view' ),
	'type'        => 'text',
	'default'     => '',
	'priority'    => 10,
	'output' 	=> array(
		array(
			'element'  => '.wooqv-trigger:not(.wooqv-shortcode-trigger)',
			'property' =>'padding-top',
			'value_pattern' => '$!important'	
		),
		array(
			'element'  => '.wooqv-trigger:not(.wooqv-shortcode-trigger)',
			'property' =>'padding-bottom',
			'value_pattern' => '$!important'	
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => 'in',
			'value'    => array('before','above','after','below','shortcode'),
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('add_to_cart_center_text'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Add To Cart Button Text align', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'left' => esc_attr__( 'Yes', 'woo-quick-view' ),
		'center' 	=> esc_attr__( 'Center', 'woo-quick-view' ),
		'right' 	=> esc_attr__( 'Right', 'woo-quick-view' )
	),
	'default'     => 'left',
	'priority'    => 10,
	'transport'		=>'auto',
	'output' 	=> array(
		array(
			'element'  => array('.product .add_to_cart_button', '.product .single_add_to_cart_button'),
			'property' =>'text-align',
			'value_pattern' => '$!important'	
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_position'),
			'operator' => 'in',
			'value'    => array('above', 'below'),
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('add_to_cart_padding'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Override Add To Cart Button Padding', 'woo-quick-view' ),
	'type'        => 'text',
	'default'     => '',
	'priority'    => 10,
	'output' 	=> array(
		array(
			'element'  => array('.product .add_to_cart_button', '.product .single_add_to_cart_button'),
			'property' =>'padding-top',
			'value_pattern' => '$!important'	
		),
		array(
			'element'  => array('.product .add_to_cart_button', '.product .single_add_to_cart_button'),
			'property' =>'padding-bottom',
			'value_pattern' => '$!important'	
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('trigger_icon_type'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Quick View Button Icon Type', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'' 	=> esc_attr__( 'No Icon', 'woo-quick-view' ),
		'font' 	=> esc_attr__( 'Font Icon', 'woo-quick-view' ),
		'image' => esc_attr__( 'Image / SVG', 'woo-quick-view' )
	),
	'default'     => 'font',
	'priority'    => 10
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('trigger_icon_font'),
	'section'  => 'modal-trigger',
	'label'    => esc_html__( 'Quick View Button Icon', 'woo-quick-view' ),
	'type'     => 'wooqvicons',
	'choices'  => array('types' => array('trigger')),
	'priority' => 10,
	'default'  => 'wooqvicon-eye-close-up',
	'transport'=>'postMessage',
	'js_vars' => array(
		array(
			'element'  => '.wooqv-trigger-icon',
			'function' =>'class'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_icon_type'),
			'operator' => '==',
			'value'    => 'font',
		),
	)
));


Kirki::add_field( self::$config_id, array(
	'settings' 		=> self::field_id('trigger_icon_image'),
	'section'     	=> 'modal-trigger',
	'label'    		=> esc_html__( 'Quick View Button Icon Image', 'woo-quick-view' ),
	'type'        	=> 'image',
	'default'  		=> '',
	'priority'    	=> 10,
	'transport'	 	=>'auto',
	'output' 		=> array(
		array(
			'element'  => '.wooqv-trigger-icon::before',
			'property' => 'background-image',
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_icon_type'),
			'operator' => '==',
			'value'    => 'image',
		),
	)
));

Kirki::add_field( self::$config_id, array(
	'settings'    => self::field_id('trigger_icon_only'),
	'section'     => 'modal-trigger',
	'label'       => esc_html__( 'Quick View Button Show Icon Only', 'woo-quick-view' ),
	'type'        => 'radio-buttonset',
	'choices'     => array(
		'1' 	=> esc_attr__( 'Yes', 'woo-quick-view' ),
		'0' 	=> esc_attr__( 'No', 'woo-quick-view' )
	),
	'default'     => '0',
	'priority'    => 10,
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_icon_type'),
			'operator' => 'in',
			'value'    => array('font','image'),
		),
	)
));


Kirki::add_field( self::$config_id, array(
	'settings' 		=> self::field_id('trigger_icon_size'),
	'section'     	=> 'modal-trigger',
	'label'    		=> esc_html__( 'Quick View Button Icon Size', 'woo-quick-view' ),
	'type'        	=> 'slider',
	'choices'     => array(
		'min'  => '12',
		'max'  => '150',
		'step' => '1',
	),
	'default'  		=> '16',
	'priority'    	=> 10,
	'transport'	 	=>'auto',
	'output' 		=> array(
		array(
			'element'  => array('.wooqv-trigger.wooqv-icontype-font .wooqv-trigger-icon'),
			'property' => 'font-size',
			'value_pattern' => '$px'
		),
		array(
			'element'  => array('.wooqv-trigger.wooqv-icontype-image .wooqv-trigger-icon'),
			'property' => 'width',
			'value_pattern' => '$px'
		),
		array(
			'element'  => array('.wooqv-trigger.wooqv-icontype-image .wooqv-trigger-icon'),
			'property' => 'height',
			'value_pattern' => '$px'
		)
	),
	'active_callback'    => array(
		array(
			'setting'  => self::field_id('trigger_icon_type'),
			'operator' => 'in',
			'value'    => array('font','image'),
		),
		array(
			'setting'  => self::field_id('trigger_icon_only'),
			'operator' => '==',
			'value'    => '1',
		)
	)
));


Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('trigger_bg_color'),
	'section'  => 'modal-trigger',
	'label'    => esc_html__( 'Quick View Button Bg Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => '#a46497',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.wooqv-trigger',
			'property' => 'background-color',
			'value_pattern' => '$!important'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('trigger_text_color'),
	'section'  => 'modal-trigger',
	'label'    => esc_html__( 'Quick View Button Text Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => '#ffffff',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => '.wooqv-trigger',
			'property' => 'color',
			'value_pattern' => '$!important'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('trigger_hover_bg_color'),
	'section'  => 'modal-trigger',
	'label'    => esc_html__( 'Quick View Button Hover Bg Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => '#935386',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-no-touchevents .wooqv-trigger:hover', '.wooqv-touchevents .wooqv-trigger:focus'),
			'property' => 'background',
			'value_pattern' => '$!important'
		)
	)
));

Kirki::add_field( self::$config_id, array(
	'settings' => self::field_id('trigger_hover_text_color'),
	'section'  => 'modal-trigger',
	'label'    => esc_html__( 'Quick View Button Hover Text Color', 'woo-quick-view' ),
	'type'     => 'color',
	'choices'     => array(
		'alpha' => true,
	),
	'priority' => 10,
	'default'  => '#ffffff',
	'transport'=>'auto',
	'output' => array(
		array(
			'element'  => array('.wooqv-no-touchevents .wooqv-trigger:hover', '.wooqv-touchevents .wooqv-trigger:focus'),
			'property' => 'color',
			'value_pattern' => '$!important'
		)
	)
));
