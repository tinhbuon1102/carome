<?php
$elsey_brand_logo_default = cs_get_option('brand_logo_default');
?>
<div class="remodal event-enter-modal" data-remodal-id="beforeenter02" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
  <!--<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>-->
  <div class="event-modal-content custom-modal-content">
	  <div class="custom-modal-head">
		  <?php echo '<div class="modal-logo"><img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_default ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="default-logo"></div>'; ?>
		  <div class="modal-hero-title">Welcome to CAROME. event!</div>
	  </div>
	  <div class="custom-modal-body">
		  <div class="custom-modal-body-inner">
		  <h2 id="modal1Title" class="modal_smallhead">位置情報許可のご確認</h2>
		  <div class="modal-content">
			  <div class="instruction-browser">
				  <h2 class="set-title"><i class="evg-icon evg-icon-globe"></i> ブラウザでの位置情報設定</h2>
				  <p>ブラウザの位置情報を許可していただく必要がございます。</p>
				  <?php
				  $browser = strtolower($_SERVER['HTTP_USER_AGENT']);
				  // ユーザーエージェントの情報を基に判定
				  if(strstr($browser , 'chrome')) {
					  //Chromeからのアクセス
					  $tabSafariClass = '';
					  $tabChromeClass = 'active';
					  echo('<p><i class="evg-icon evg-icon-browser-chrome"></i> Chromeです</p>');
				  } elseif (strstr($browser , 'firefox')) {
					  //Firefoxからのアクセス
					  $tabSafariClass = '';
					  $tabChromeClass = '';
					  $tabFirefoxClass = 'active';
					  $tabOtherClass = '';
					  echo('<p>Firefoxです</p>');
					  
				  } elseif (strstr($browser , 'safari')) {
					  //Safariからのアクセス
					  $tabSafariClass = 'active';
					  $tabChromeClass = '';
					  echo('<p><i class="evg-icon evg-icon-browser-safari"></i> Safariです</p>');
				  } else {
					  //その他からのアクセス
					  $tabSafariClass = '';
					  $tabChromeClass = '';
					  $tabFirefoxClass = '';
					  $tabOtherClass = 'active';
					  echo('<p>その他ブラウザです</p>');
					  
				  }
				  echo('<div class="browser-inst">');
				  echo ('<ul class="tabs">');
	 echo ('<li class="'.$tabSafariClass.'"><a href="#safariUser"><i class="evg-icon evg-icon-browser-safari"></i> Safariの方</a></li>');
	 echo ('<li class="'.$tabChromeClass.'"><a href="#chromeUser"><i class="evg-icon evg-icon-browser-chrome"></i> Chromeの方</a></li>');
	 if(!strstr($browser , 'safari') || !strstr($browser , 'chrome')) {
		 echo ('<li class="'.$tabFirefoxClass.'"><a href="#firefoxUser">Firefoxの方</a></li>');
		 echo ('<li class="'.$tabOtherClass.'"><a href="#otherUser">その他の方</a></li>');
	 }
	 
				  
				  echo ('</ul>');
				  echo ('<div class="tab_container custom_tab_container">');
				  //Safari手順
				  echo ('<div id="safariUser" class="tab_content">');
	get_template_part( 'template-parts/instbrowser', 'safari' );
				  echo ('</div>');//end of tab_content
				  //Chrome手順
				  echo ('<div id="chromeUser" class="tab_content">');
	get_template_part( 'template-parts/instbrowser', 'chrome' );
				  echo ('</div>');//end of tab_content
				  if(!strstr($browser , 'safari') || !strstr($browser , 'chrome')) {
				  //Firefox手順
				  echo ('<div id="firefoxUser" class="tab_content">');
				  echo ('準備中');
	//get_template_part( 'template-parts/instbrowser', 'firefox' );
				  echo ('</div>');//end of tab_content
				  
				  //その他手順
				  echo ('<div id="otherUser" class="tab_content">');
				  echo ('準備中');
	//get_template_part( 'template-parts/instbrowser', 'other' );
				  echo ('</div>');//end of tab_content
					  }//if chrome nor safari

				  echo('</div>');//end of browser-inst

				  ?>
			  </div><!--/.instruction-browser-->
			  
		  </div>
		</div><!--/custom-modal-body-inner-->
	  </div><!--/custom-modal-body-->
	  <div class="custom-modal-foot">
		  <button data-remodal-target="eventaccess" class="button move-check-location-button button-before-icon"><i class="evg-icon evg-icon-globe"></i><span class="icon-nexttext"><?php esc_html_e( '上記の設定が完了', 'elsey' ); ?></span></button>
	  </div>
	  
  </div>
</div>