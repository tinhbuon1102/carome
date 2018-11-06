<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Woo_Quick_View_License {
	
	protected $url = "http://license.xplodedthemes.com/process.php";
	protected $repo_url = "http://repo.xplodedthemes.com/{market}.php";
	protected $product = null;
	protected $is_fake = false;
	protected $option_key = null;
	protected $option_key_check = null;

	public function __construct ( $product, $refreshLicense = false ) {
		
		$this->repo_url = str_replace('{market}', WOOQV_MARKET, $this->repo_url);
		
		$this->product = $this->get_market_product($product);
		
		if(empty($this->product->id)) {
			$this->is_fake = true;
		}

		$this->option_key = 'xt-license-'.$this->product->id;
		$this->option_key_check = 'xt-license-check-'.$this->product->id;
		
		add_action( 'wp_ajax_wooqv_license_activation_'.$this->product->id, array($this, 'ajax_activate'));
		add_action( 'wp_ajax_wooqv_license_revoke_'.$this->product->id, array($this, 'ajax_revoke'));

		if(!empty($_REQUEST['xt-'.WOOQV_MARKET.'-refresh'])) {
		 	
		 	$this->deleteLocalLicense();
		 	
		 	$license = $this->getLocalLicense();
	 	}
	 			
		if($refreshLicense || $this->refreshNeeded()) {
			$license = $this->getLocalLicense();
	
			if(!empty($license)) {
				$this->activate(
					$license->license->purchase_code,
					$license->license->domain,
					$license->license->installation_url
				);
			}
		}
	}	
	
	public function product() {
		
		return $this->product;
	}
 	
 	public function ajax_activate() {
	 	
	 	$nonce = $_POST['wpnonce'];
	    if ( ! wp_verify_nonce( $nonce, 'wooqv_license_activation' ) ) {
		    
	       	die( 'invalid nonce' );
	       	
	    } else {
		    
		 	$domain = !empty($_POST['domain']) ? $_POST['domain'] : '';
		 	$installation_url = !empty($_POST['installation_url']) ? $_POST['installation_url'] : '';
		 	$purchase_code = !empty($_POST['purchase_code']) ? $_POST['purchase_code'] : '';
		 
		 	$response = $this->activate($purchase_code, $domain, $installation_url);

		 	die(json_encode($response));
	    }
 	}
 
  	public function ajax_revoke() {
	 	
	 	$nonce = $_POST['wpnonce'];
	    if ( ! wp_verify_nonce( $nonce, 'wooqv_license_revoke' ) ) {
		    
	       	die( 'invalid nonce' );
	       	
	    } else {
		    
		 	$local = !empty($_POST['local']) ? true : false;
		 	$purchase_code = !empty($_POST['purchase_code']) ? $_POST['purchase_code'] : '';
		 	$domain = !empty($_POST['domain']) ? $_POST['domain'] : '';
		 	$response = $this->revoke($purchase_code, $domain, $local);

		 	die(json_encode($response));
	    }
 	}

 	
 	public function activate($purchase_code, $domain, $installation_url) {

	 	$response = $this->process(array( 
		 	'product_id' => $this->product->id,
			'purchase_code' => $purchase_code, 
			'domain' => $domain,
			'installation_url' => $installation_url,
			'action' => 'activate',
			'market' => WOOQV_MARKET
		));

		if(!empty($response) && $response->code == "valid") {
			
			$this->saveLocalLicense($response);
				
		}else{
			
			$this->deleteLocalLicense();
		}
	
		return $response;
 	}	
 		 	
 	public function revoke($purchase_code, $domain = null, $local = false) {
	
		$response = $this->process(array( 
			'purchase_code' => $purchase_code, 
			'domain' => $domain, 
			'action' => 'revoke'
		)); 	

		if($local) {
			$license = $this->getLocalLicense();
			$this->deleteLocalLicense($license->license->product_id);
		}	
					
		return $response;
 	}	
		
 	public function process($data) {

 		$data['t'] = time();
 		$url = add_query_arg($data, $this->url);
 		
 		$response = $this->remote_get($url);
 		
 		if(!empty($response)) {
 			return json_decode($response);
 		}	
		return false;
 	}
 	
 	public function getLocalLicenseInfo($type) {
	 
	 	$license = $this->getLocalLicense();
	 	
	 	if(!empty($license->license->$type)) {
	 		return $license->license->$type;
	 	}
	 	return "";
 	}
 	
 	public function getLocalLicenseSummary() {
	 	
	 	$license = $this->getLocalLicense();
	 	
	 	return $license->license_summary;
 	}
 	
 	public function getLocalLicense() {

	 	return $this->get_option($this->option_key);
 	}
 	
 	public function refreshNeeded() {

	 	return $this->get_transient($this->option_key_check) === false;
 	}
 	
 	public function saveLocalLicense($license) {
	 			
	 	$this->add_option($this->option_key, $license);
	 	$this->set_transient($this->option_key_check, time(), DAY_IN_SECONDS);
 	}
 	
 	public function deleteLocalLicense($product_id = null) {
	 	
	 	if(!empty($product_id)) {
		 	
	 		$option_key = 'xt-license-'.$product_id;
	 		$option_key_check = 'xt-license-check-'.$product_id;
	 			 
	 		$this->delete_option($option_key);
	 		$this->delete_transient($option_key_check);
	 		
		}
	 	
	 	$this->delete_option($this->option_key);
	 	$this->delete_transient($this->option_key_check);
 	}
 	
 	public function isActivated() {
		
		$license = $this->getLocalLicense();
		
		if(!empty($license)) {
			return true;
		}
		
		return false;
 	}
 	
 	public function form() {

 		$domain = "";
	 	if(is_multisite() && function_exists('get_current_site')) {
	 		$domain = get_current_site()->domain;
	 	}
	 		 	
	 	$isActivated = $this->isActivated();
	 	?>
	 	<div id="wooqv-license-activation-<?php echo esc_attr($this->product->id);?>" data-id="<?php echo esc_attr($this->product->id);?>" data-ajaxurl="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-homeurl="<?php echo esc_url(network_site_url('/'));?>" data-domain="<?php echo $domain;?>" class="wooqv-license-activation" style="width: calc(100% - 33px);">

		 	<div id="wooqv-license-activation-form" class="wooqv-license-form" style="<?php if($isActivated):?>display:none<?php endif;?>">
			 	<p class="wooqv-license-status">
				    <span class="wooqv-license-msg">
					    <?php 
						echo apply_filters(
							'wooqv_license_msg_activate_'.$this->product->id, 
							sprintf(
						    	esc_html__( 'Your license of %1$s is %2$s.', 'woo-quick-view'), 
								"<strong>".$this->product->name."</strong>", 
								"<span class='wooqv-license-status wooqv-license-invalid'><strong>".esc_html__('Not Activated', 'woo-quick-view')."</strong></span>"
							), 
							$this->product
						);
						?>
				    </span>
					<span class="wooqv-license-submsg">
						<?php 
						echo apply_filters(
							'wooqv_license_submsg_activate_'.$this->product->id, 
							sprintf(
								esc_html('Validate your purchase code to activate %1$s and enable automated updates', 'woo-quick-view'),  
								"<strong>".$this->product->name."</strong>"
							), 
							$this->product
						);
						?>
					</span>
					<span class="wooqv-license-submsg wooqv-license-revoke-info"></span>
				</p>
			 	<input type="hidden" name="action" value="wooqv_license_activation_<?php echo $this->product->id;?>">
				<input type="hidden" name="wpnonce" value="<?php echo wp_create_nonce('wooqv_license_activation');?>">
				<input type="hidden" name="domain" value="">
				<input type="hidden" name="installation_url" value="">
				<input type="hidden" name="product_id" value="<?php echo esc_attr($this->product->id);?>">
			 	<input class="regular-text" placeholder="<?php echo esc_html__('Purchase Code...', 'woo-quick-view' );?>" name="purchase_code" value="">
			 	<input type="submit" class="button button-primary" value="<?php echo esc_html__('Validate', 'woo-quick-view'); ?>">
		 	</div>	
		 		
		 	<div id="wooqv-license-revoke-form" class="wooqv-license-form" style="display:none">
			 	
			 	<p class="wooqv-license-status">
				    <span class="wooqv-license-msg wooqv-license-invalid"><?php echo apply_filters('wooqv_license_msg_active_invalid_'.$this->product->id, esc_html__( 'This purchase code is activated somewhere else.', 'woo-quick-view'), $this->product);?></span>
					<span class="wooqv-license-submsg"><?php echo apply_filters('wooqv_license_submsg_active_invalid_'.$this->product->id, esc_html('You can either revoke the below license then re-activate it here or buy a new license.', 'woo-quick-view'), $this->product);?></span>
				</p>
				
			 	<input type="hidden" name="action" value="wooqv_license_revoke_<?php echo $this->product->id;?>">
				<input type="hidden" name="wpnonce" value="<?php echo wp_create_nonce('wooqv_license_revoke');?>">
				<input type="hidden" name="purchase_code" value="">
			 	<input type="hidden" name="domain" value="">
			 	<input type="button" class="button button wooqv-license-revoke-cancel" value="<?php echo esc_html__('Cancel', 'woo-quick-view'); ?>">
			 	
			 	<a href="<?php echo $this->product->buy_url;?>" class="button" target="_blank"><?php echo esc_html__('Buy License', 'woo-quick-view'); ?></a>
			 	<input type="submit" class="button button-primary" value="<?php echo esc_html__('Revoke', 'woo-quick-view'); ?>">
		 	</div>	
		 	
		 	<div id="wooqv-license-invalid" style="display:none">
			 	<p class="wooqv-license-status">
				    <span class="wooqv-license-msg">
				    <?php 
					echo apply_filters(
						'wooqv_license_msg_invalid_'.$this->product->id, 
						sprintf(
					    	esc_html__( 'This purchase code is %1$s.', 'woo-quick-view'), 
							"<span class='wooqv-license-status wooqv-license-invalid'><strong>".esc_html__('Invalid', 'woo-quick-view')."</strong></span>"
						),
						$this->product
					);
					?>
				    </span>
				    <span class="wooqv-license-timer"></span>
				</p>
		 	</div>
		 	
		 	<div id="wooqv-license-activated" style="<?php echo ($isActivated ? 'display:block' : 'display:none'); ?>">
			 	<p class="wooqv-license-status">
				    <span class="wooqv-license-msg">
				    <?php 
					echo apply_filters(
						'wooqv_license_msg_activated_'.$this->product->id, 
						sprintf(
					    	esc_html__( 'Your license of %1$s is %2$s.', 'woo-quick-view'), 
							"<strong>".$this->product->name."</strong>", 
							"<span class='wooqv-license-status wooqv-license-valid'><strong>".esc_html__('Activated', 'woo-quick-view')."</strong></span>"
						), 
						$this->product
					);
					?>
				    </span>
					<span class="wooqv-license-submsg">
				    <?php 
					echo apply_filters(
						'wooqv_license_submsg_activated', 
						sprintf(
					    	esc_html__( 'Automated updates are now %1$s', 'woo-quick-view'), 
							"<strong>".esc_html__('Enabled', 'woo-quick-view')."</strong>"
						), 
						$this->product
					);
					?>
				    </span>
				</p>
		 	</div>
		 	
		 	<?php if($isActivated):?>
		 	<div class="wooqv-license-info">
			 	<?php echo $this->getLocalLicenseSummary();?>
		 	</div>
		 	<?php else: ?>
		 	<div class="wooqv-license-info"></div>
		 	<?php endif;?>

		 	<div id="wooqv-license-local-revoke-form" class="wooqv-license-form" style="<?php if(!$isActivated):?>display:none<?php endif;?>">

			 	<input type="hidden" name="action" value="wooqv_license_revoke_<?php echo $this->product->id;?>">
				<input type="hidden" name="wpnonce" value="<?php echo wp_create_nonce('wooqv_license_revoke');?>">
				<input type="hidden" name="purchase_code" value="<?php echo $this->getLocalLicenseInfo('purchase_code'); ?>">
				<input type="hidden" name="domain" value="<?php echo $this->getLocalLicenseInfo('domain'); ?>">
				<input type="hidden" name="local" value="1">
			 	<a href="<?php echo $this->product->buy_url;?>" class="button" target="_blank"><?php echo esc_html__('Buy License', 'woo-quick-view'); ?></a>
			 	<input type="submit" class="button button-primary" value="<?php echo esc_html__('Revoke License', 'woo-quick-view'); ?>">
		 	</div>	
		</div>

	 	<?php
 	}
 	
 	public function get_market_product($product) {
		 	
	 	$default = array(
		 	'id' => 0,
		 	'name' => '',
		 	'url' => '#',
		 	'buy_url' => '#'
	 	);
	 	 	
	 	if(is_int($product)) {
		 	
		 	$default['id'] = $product;
		 	return (object)$default;
		 	
		}else if(is_array($product)) {
			
			return (object)$product;
		}
		 
	 	$cache_key = 'xt_'.WOOQV_MARKET.'_'.$product;
	 	
	 	if(!empty($_REQUEST['xt-'.WOOQV_MARKET.'-refresh'])) {
		 	
		 	$this->delete_transient($cache_key);
	 	}

	 	if ( false === ( $data = $this->get_transient( $cache_key ) ) ) {
		 	
		 	$url = $this->repo_url.'?id='.$product;
		 	$data = $this->remote_get($url);
						
			if(!empty($data) && is_array($data) && !empty($data['id'])) {
			
				// Store remote HTML file in transient, expire after 24 hours
				$this->set_transient( $cache_key, $data, 24 * WEEK_IN_SECONDS );
			}
		}	
		
		if(empty($data)) {
			$data = $default;
		}
		
		return (object)$data;
 	}

 	public function remote_get($url) {
	 	
	 	$data = null;
	 	
 		// First, we try to use wp_remote_get
		$response = wp_remote_get( $url, array(
            'timeout' => 120,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0'
        ));
	
		if( is_wp_error( $response ) || (!empty($response["response"]["code"]) && $response["response"]["code"] === 403 )) {
			
            if(function_exists('curl_init')) {
	
				// And if that doesn't work, then we'll try curl
				$curl = curl_init( $url );
			
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $curl, CURLOPT_HEADER, 0 );
				curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0' );
				curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
			
				$response = curl_exec( $curl );
				if( 0 !== curl_errno( $curl ) || 200 !== curl_getinfo( $curl, CURLINFO_HTTP_CODE ) ) {
					
					// If that doesn't work, then we'll try file_get_contents
			        $response = @file_get_contents( $url );
			
				} // end if
				curl_close( $curl );

			}else{
			
			    // If curl is not availaible try file_get_contents
			    $response = @file_get_contents( $url );
			        
			}// end if
					
			if( null == $response ) {
				$response = null;
			}	
	
			if(!empty($response)) {
				$data = maybe_unserialize($response);
			}
	
		}else{

	        // Parse remote HTML file
			$data = wp_remote_retrieve_body( $response );
	
	        // Check for error
			if ( !is_wp_error( $data ) ) {
				$data = maybe_unserialize($data);
			}
		}
		
		return $data;
 	} 	
  	
 	function set_transient($key, $value, $expiration = 0) {
	 	
	 	if(is_multisite()) {
		 	set_site_transient($key, $value, $expiration);
	 	}else{
		 	set_transient($key, $value, $expiration);
	 	}
 	}
 	
 	function get_transient($key) {
	 	
	 	if(is_multisite()) {
		 	return get_site_transient($key);
	 	}else{
		 	return get_transient($key);
	 	}
 	}
 	
 	function delete_transient($key) {
	 	
	 	if(is_multisite()) {
		 	delete_site_transient($key);
	 	}else{
		 	delete_transient($key);
	 	}
 	}	
 	
 	function add_option($key, $value) {
	 	
	 	if(is_multisite()) {
		 	add_site_option($key, $value, '', 'no');
	 	}else{
		 	add_option($key, $value, '', 'no');
	 	}
 	}
 	
 	function get_option($key) {
	 	
	 	$option = $this->get_transient($key);
	 	
	 	if($option === false) {
		 	
		 	if(is_multisite()) {
			 	return get_site_option($key);
		 	}else{
			 	return get_option($key);
		 	}
	 	}
	 	
	 	return $option;
 	}
 	
 	function delete_option($key) {
	 	
	 	$this->delete_transient($key);
	 	
	 	if(is_multisite()) {
		 	delete_site_option($key);
	 	}else{
		 	delete_option($key);
	 	}
 	}	 	
}

if(!empty($_REQUEST['xt-license-revoke']) && !empty($_REQUEST['id'])) {

	$product = intval($_REQUEST['id']);

	$license = new Woo_Quick_View_License($product);
	
	$purchase_code = $license->getLocalLicenseInfo('purchase_code');
	$domain = $license->getLocalLicenseInfo('domain');
	
	if(!empty($purchase_code)) {
		$license->revoke($purchase_code, $domain);
	}
	$license->deleteLocalLicense();
	die();
}