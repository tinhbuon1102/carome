<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Woo_Quick_View_Update {

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;
	
	protected $update_url = "http://repo.xplodedthemes.com/updates.php?action=get_metadata&slug={slug}&market={market}";

	public function __construct ( $parent ) {
		
		$this->parent = &$parent;

		$this->update_url = str_replace(
			array(
				'{slug}',
				'{market}'
			),
			array(
				$this->parent->plugin_slug(),	
				WOOQV_MARKET, 
			),
			$this->update_url
		);
		
		$updateChecker = Woo_Quick_View_Update_Factory::buildUpdateChecker(
		    $this->update_url,
		    $this->parent->plugin_file()
		);
		
		$updateChecker->addQueryArgFilter(array($this, 'filter_update_checker'));
	}

	public function filter_update_checker($queryArgs) {

	    $license = $this->parent->license();
	
	    if ( !empty($license) && $license->getLocalLicense() !== false) {
		    
		    $product = $license->getLocalLicense()->license;
		     
	        $queryArgs['purchase_code'] = $product->purchase_code;
	        $queryArgs['product_id'] = $product->product_id;
	        $queryArgs['domain'] = $product->domain;
	        
	    }else{
		    
		    $queryArgs['purchase_code'] = '';
	        $queryArgs['product_id'] = '';
	        $queryArgs['domain'] = '';
	    }
	    
	    return $queryArgs;
	}

}
