<?php

return array(

	array(
		'version' => '1.1.2',
		'date' =>'27.10.2018',
		'changes' => array(
			'support' => array(
				'Better theme support for trigger position "Over Image & Over Container"'	
			),
			'fix' => array(
				'Fixed issue with some customizer color fields not showing',
				'Fixed multiple ajax requests issue with variable products',
				'Minor cart refresh fixes'
			)			
		)
	),
	
	array(
		'version' => '1.1.1',
		'date' =>'18.09.2018',
		'changes' => array(
			
			'fix' => array(
				'Fix javascript error when selecting a variation'
			)			
		)
	),
	
	array(
		'version' => '1.1.0',
		'date' =>'11.09.2018',
		'changes' => array(
			
			'fix' => array(
				'Remove / Replace deprecated woocommerce functions',
				'Prevent variable product from being added to cart if no option has been selected',
				'Minor Customizer Fixes'
			)			
		)
	),
	
	array(
		'version' => '1.0.9',
		'date' =>'01.07.2018',
		'changes' => array(
			
			'enhance' => array(
				'More usable on ipads',
			),
			'support' => array(
				'Added new javascript event "wooqv-product-loaded" on "document.body" triggered once the quick view is opened and product info loaded. Can be used by themes or plugins to perform custom actions',
			)			
		)
	),
	
	array(
		'version' => '1.0.8',
		'date' =>'24.04.2018',
		'changes' => array(
			
			'new' => array(
				'Added Previous / Next Navigation Arrows to quickly preview products on the page',
				'Added options to select navigation arrow icon / size and color'
			),
			'fix' => array(
				'Removed greyscale animation if only one image is found within the slider'
			),
			'support' => array(
				'Better variations support',
				'Switch to variation image whenever a variation is selected',
				'Support the <strong><a target="_blank" href="https://woocommerce.com/products/woocommerce-additional-variation-images/">WooCommerce Additional Variation Images Plugin</a></strong>'
			)			
		)
	),
	
	array(
		'version' => '1.0.7',
		'date' =>'23.03.2018',
		'changes' => array(
			
			'new' => array(
				'Added an optional Trigger Overlay Color if the trigger position is set to Over Product or Over Image'
			),
			'fix' => array(
				'Fixed some trigger options where not appearing within the customizer'	
			),
			'support' => array(
				'Better compatibility with Flatsome Theme',
				'Better theme compatibility'
			)			
		)
	),
	
	array(
		'version' => '1.0.6',
		'date' =>'15.01.2018',
		'changes' => array(

			'support' => array(
				'Woo Variations Table Plugin'
			)			
		)
	),
	
	array(
		'version' => '1.0.5',
		'date' =>'25.11.2017',
		'changes' => array(

			'support' => array(
				'Wordpress v4.9 Customizer Support'
			)			
		)
	),
	
	array(
		'version' => '1.0.4',
		'date' =>'24.10.2017',
		'changes' => array(
			
			'new' => array(
				'Added trigger shortcode [wooqv_trigger id="PRODUCT_ID"] that can be inserted within post content editor or anywhere within a theme template',
				'Added new trigger position "Over Product Container" better theme compatibility compared to "Over Product Image"'
			),
			'fix' => array(
				'Fix compatibility issue with the X Theme'
			),
			'support' => array(
				'Better theme compatibility'
			)				
		)
	),
	
	array(
		'version' => '1.0.3.1',
		'date' =>'07.07.2017',
		'changes' => array(

			'fix' => array(
				'Fix multiple domain license check bug',
			)				
		)
	),
	
	array(
		'version' => '1.0.3',
		'date' =>'29.05.2017',
		'changes' => array(
			'fix' => array(
				'Fix issue with the More Info button on FireFox'
			)
		)
	),
	array(
		'version' => '1.0.2',
		'date' =>'19.05.2017',
		'changes' => array(
			'fix' => array(
				'Fix issues with Flatsome WordPress Theme'
			),
			'support' => array(
				'Better theme compatibility'
			)
		)
	),	
	array(
		'version' => '1.0.1',
		'date' =>'24.04.2017',
		'changes' => array(
			'fix' => array(
				'Support WooCommerce 3.0.x+',
				'Minor CSS Fixes'
			)
		)
	),	
	array(
		'version' => '1.0.0',
		'date' =>'10.03.2017',
		'changes' => array(
			'initial' => 'Initial Version'
		)
	)
);