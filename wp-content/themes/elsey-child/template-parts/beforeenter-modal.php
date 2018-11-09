<?php
$elsey_brand_logo_default = cs_get_option('brand_logo_default');
?>
<div class="remodal event-enter-modal" data-remodal-id="beforeenter" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
  <!--<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>-->
  <div class="event-modal-content custom-modal-content">
	  <div class="custom-modal-head">
		  <?php echo '<div class="modal-logo"><img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_default ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="default-logo"></div>'; ?>
		  <div class="modal-hero-title">Welcome to CAROME. event!</div>
	  </div>
	  <div class="custom-modal-body">
		  <div class="custom-modal-body-inner">
		  <h2 id="modal1Title" class="modal_smallhead"><i class="evg-icon evg-icon-speaker"></i>位置情報許可のご確認</h2>
		  <div class="modal-content">
			  <div class="instruction-browser device-inst">
				  <h2 class="set-title">端末での位置情報設定</h2>
				  <div id="error_message_access"></div>
				  <p>端末の位置情報をオンしていただく必要がございます。</p>
				  <?php
				  $browser = strtolower($_SERVER['HTTP_USER_AGENT']);
				  if(stripos($user_agent,'iPhone') !== false) {
					  //iPhoneからのアクセス
					  $tabIphoneClass = 'active';
					  $tabAndroidClass = '';
				  } elseif (stripos($browser,'Android') !== false) {
					  //Androidからのアクセス
					  $tabIphoneClass = '';
					  $tabAndroidClass = 'active';
				  } else {
					  //その他からのアクセス
					  $tabIphoneClass = '';
					  $tabAndroidClass = 'active';
				  }
				  echo('<div class="browser-inst">');
				  echo ('<ul class="tabs">');
	 echo ('<li class="'.$tabIphoneClass.'"><a href="#iphoneUser">iPhoneの方</a></li>');
	 echo ('<li class="'.$tabAndroidClass.'"><a href="#androidUser">Androidの方</a></li>');
				  echo ('</ul>');
				  echo ('<div class="tab_container custom_tab_container">');
				  //iPhone手順
				  echo ('<div id="iphoneUser" class="tab_content">');
	get_template_part( 'template-parts/instruction', 'iphone' );
				  echo ('</div>');//end of tab_content
				  //Androidかその他手順
				  echo ('<div id="androidUser" class="tab_content">');
	get_template_part( 'template-parts/instruction', 'android' );
				  echo ('</div>');//end of tab_content

				  echo('</div>');//end of browser-inst
				  ?>
			  </div><!--/.instruction-browser-->
			  
		  </div>
		</div><!--/custom-modal-body-inner-->
	  </div><!--/custom-modal-body-->
	  <div class="custom-modal-foot">
		  <button data-remodal-target="beforeenter02" class="button move-check-location-button button-before-icon"><i class="evg-icon evg-icon-pin-check"></i><span class="icon-nexttext"><?php esc_html_e( '端末の設定をしました', 'elsey' ); ?></span></button>
	  </div>
	  
  </div>
</div>