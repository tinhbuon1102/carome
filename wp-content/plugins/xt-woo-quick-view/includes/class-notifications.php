<?php
if (!class_exists('XT_Notifications')) {
	
	class XT_Notifications {
		
		
		protected $service_url = 'https://xplodedthemes.com/notifications/';
		protected $notifications = [];
		
		function __construct() {
			
			add_action( 'admin_notices', array($this, 'show_notification') );
			add_action( 'wp_ajax_xt_notification_dismiss', array($this, 'dismiss_notification') );

		}
	
		public function load_notifications() {
			
			$cache_key = 'xt_notifications';
	 	
		 	if(!empty($_REQUEST['nocache'])) {
			 	
			 	delete_transient($cache_key);
		 	}
		 	
		 	if(!empty($_REQUEST['xtnotif_flush_dismissed'])) {
			 	
			 	$id = !empty($_REQUEST['xtnotif_id']) ? intval($_REQUEST['xtnotif_id']) : null;
			 	$this->remove_dismissed($id);
		 	}


			if ( false === ( $this->notifications = get_transient( $cache_key ) ) ) {
		 
			 	$this->notifications = $this->remote_get($this->service_url);
							
				if(!empty($this->notifications) && is_array($this->notifications)) {
				
					set_transient( $cache_key, $this->notifications, DAY_IN_SECONDS );
				}
			}
			
		}

		function show_notification() {
				
			$this->load_notifications();
			$notification = $this->pop_notification();
			
			if(empty($notification)) {
				return false;
			}
			
		    ?>
		    <div class="xt-notification notice notice-<?php echo $notification['type'];?> is-dismissible" data-id="<?php echo $notification['id'] ?>" style="max-width:<?php echo $notification['style']['width'];?>">
		        <p style="font-size:<?php echo $notification['style']['font-size'];?>"><?php echo $notification['message'] ?></p>
		    </div>
		    <script>
			jQuery(document).on( 'click', '.xt-notification .notice-dismiss', function() {

				var id = jQuery(this).parent().data('id');
				
			    jQuery.ajax({
			        url: ajaxurl,
			        method: 'POST',
			        data: {
			            action: 'xt_notification_dismiss',
			            xtnotif_id: id
			        }
			    })
			
			});    
			</script>
		    <?php
		}
		
				
		protected function pop_notification() {

			if(empty($this->notifications) || !is_array($this->notifications)) {
				return null;
			}

			foreach($this->notifications as $notif) {
				
				$id = $notif['id'];
				$condition = $notif['condition'];
			
				if($this->is_dismissed($id)) {
					continue;
				}
				
				if(!empty($condition)) {
					
					if($condition['if'] === 'active_plugin') {
									
						if(is_plugin_active($condition['value'])) {
							
							return $notif;
						}
					}
					
				}else{
					
					return $notif;
				}
			}
			
			return null;
			
		}
		
		function is_dismissed($id) {
			
			$dismissed = $this->get_dismissed();
			
			return in_array($id, $dismissed);
		}
		
		function get_dismissed() {
			
			$cache_key = 'xt_dismissed_notifications';
			
			if ( false === ( $dismissed = wp_cache_get( $cache_key ) ) ) {
				$dismissed = get_option('xt_dismissed_notifications', []);
				wp_cache_set($cache_key, $dismissed);
			}
			
			return $dismissed;
		}
		
		function update_dismissed($dismissed) {
			
			return update_option('xt_dismissed_notifications', $dismissed, true);
		}
		
		function remove_dismissed($id = null) {
			
			if(empty($id)) {
				
				delete_option('xt_dismissed_notifications');
				
			}else{
				
				$dismissed = $this->get_dismissed();
				
				foreach($dismissed as $key => $value) {
					if ($value === $id) {
						unset($dismissed[$key]);
						break;
					}
				}
			}
		}
		
		function dismiss_notification() {
		
			$id = intval( $_POST['xtnotif_id'] );
			
			if(!$this->is_dismissed($id)) {
				$dismissed = $this->get_dismissed();
				$dismissed[] = $id;
				$this->update_dismissed($dismissed);
			}

			wp_die();
		}

	 	protected function remote_get($url) {
		 	
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
	 		
	}
	
	new XT_Notifications;
}	