<?php if ( ! defined( 'ABSPATH' ) ) { return; } /*#!-- Do not allow this file to be loaded unless in WP context*/
/**
 * This is the plugin's default page
 */
global $aebaseapi;
$purchase_codes = get_option(AEBaseApi::PURCHASE_CODES_OPTION_KEY, array());
$products = $aebaseapi->get_products();


function ae_updater_validate_codes($purchase_codes)
{
	foreach ($purchase_codes as $slug => $code) {
		$url = 'http://actualityextensions.com/updates/server/?action=validate_code&slug=' . $slug . '&purchase_code=' . $code;

		$transient = 'ae_code' . md5($url);
		$resultEnvato  = get_transient( $transient );
		
		if( !$resultEnvato ){

			$options = array(
				'timeout' => 10, //seconds
				'headers' => array(
					'Accept' => 'application/json'
				),
			);
			$options = apply_filters('puc_request_info_options-'.$slug, $options);
			
			$result = wp_remote_get(
				$url,
				$options
			);

			if ( is_wp_error($result) ) { /** @var WP_Error $result */
				?>
				<div class="error below-h2">
					<p><?php echo 'WP HTTP Error: ' . $result->get_error_message(); ?></p>
				</div>
				<?php
			}

			if ( !isset($result['response']['code']) ) {
				?>
				<div class="error below-h2">
					<p><?php echo 'wp_remote_get() returned an unexpected result.'; ?></p>
				</div>
				<?php
			}

			if ( $result['response']['code'] !== 200 ) {
				?>
				<div class="error below-h2">
					<p><?php echo 'HTTP response code is ' . $result['response']['code'] . ' (expected: 200)'; ?></p>
				</div>
				<?php
			}

			if ( empty($result['body']) ) {
				?>
				<div class="error below-h2">
					<p><?php echo 'The metadata file appears to be empty.'; ?></p>
				</div>
				<?php
			}
			
			$resultEnvato = json_decode($result['body']);
		}
		set_transient( $transient, $resultEnvato, 12 * HOUR_IN_SECONDS );

	    if( isset($resultEnvato->error) && !empty($resultEnvato->error)){
	    	?>
			<div class="error below-h2">
				<p><?php echo $resultEnvato->error; ?></p>
			</div>
			<?php
	    }
	}
}
?>
<style>
    div.ae-license:before {
		content: "\00e6";
		float: right;
		background: #8f1e20;
		width: 140px;
		height: 140px;
		font-size: 110px;
		color: white;
		text-align: center;
		font-weight: 300;
		line-height: 120px;
		-webkit-box-shadow: 0 1px 3px rgba(0,0,0,.2);
		box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
</style>
<div class="wrap about-wrap ae-license">
	<h1><?php _e( 'Actuality Extensions', 'wc_point_of_sale' ); ?></h1>
	<p class="about-text"><?php _e( 'Thank you for purchasing this plugin. To ensure you get the latest updates, please enter the purchase codes in the table below.', 'wc_point_of_sale' ); ?></p>
	<br>
	<br>
	<hr>
	<h2><?php _e( 'Installed Products', 'wc_point_of_sale' ); ?></h2>
</div>
<div class="wrap" style="margin: 25px 40px 0 20px;">
<?php
	$rm = strtoupper($_SERVER['REQUEST_METHOD']);
	if('POST' == $rm)
	{
		if (! isset( $_POST['ae_save_credentials'] )|| ! wp_verify_nonce( $_POST['ae_save_credentials'], 'ae_save_credentials_action' )) { ?>
			<div class="error below-h2">
				<p><?php _e('Invalid request.', 'envato-update-plugins');?></p>
			</div>
		<?php }
		else if(isset($_POST['envato-update-plugins_purchase_code']) ){
			$purchase_codes = array_map('trim', $_POST['envato-update-plugins_purchase_code']);
			ae_updater_validate_codes($purchase_codes);
			update_option(AEBaseApi::PURCHASE_CODES_OPTION_KEY, $purchase_codes);
			?>
			<div class="updated below-h2">
				<p><?php _e('Settings saved.', 'envato-update-plugins');?></p>
			</div>
			<?php
		}
	}else{
		ae_updater_validate_codes($purchase_codes);
	}
?>
</div>
<div class="wrap about-wrap">
	<form id="envato-update-plugins-form" method="post" style="margin: 2em 0;">
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php _e( 'Product', 'wc_point_of_sale' ); ?></th>
					<th><?php _e( 'Purchase Code', 'wc_point_of_sale' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			foreach ($products as $file ) {
				$plugin_slug = basename($file, '.php');
				$pluginData = get_plugin_data($file);
				$purchase_code = isset($purchase_codes[$plugin_slug]) ? $purchase_codes[$plugin_slug] : '';
				if( $pluginData ){
					?>
					<tr>
						<th scope="row"><?php echo $pluginData['Name']; ?></th>
						<td><input type="text" placeholder="<?php _e( 'Place your purchase code here', 'wc_point_of_sale' ); ?>" class="regular-text" name="envato-update-plugins_purchase_code[<?php echo $plugin_slug;?>]"
						           value="<?php echo $purchase_code;?>" /></td>
					</tr>
					<?php					
				}
			}
			?>
			</tbody>
		</table>
		<p style="color: #666;"><?php _e( 'Your purchase code will have been sent by email when you purchased the plugin. Alternatively, you can ', 'wc_point_of_sale' ); ?><a href="http://codecanyon.net" target="_blank"><?php _e( 'log into CodeCanyon.net', 'wc_point_of_sale' ); ?></a><?php _e( ', then go to Downloads > Plugin Name > Download > License certificate & purchase code. ', 'wc_point_of_sale' ); ?><?php _e( 'Click ', 'wc_point_of_sale' ); ?><a href="http://actualityextensions.com/updates/purchase-code-example.gif" target="_blank"><?php _e( 'here', 'wc_point_of_sale' ); ?></a><?php _e( ' for an example of this.', 'wc_point_of_sale' ); ?>
		<p class="submit">
			<input type="submit"class="button-primary" id="envato-update-plugins_submit"
			       value="<?php _e( 'Save Settings', 'envato-update-plugins');?>" />
		</p>
		<?php wp_nonce_field( 'ae_save_credentials_action', 'ae_save_credentials');?>
	</form>
</div>
