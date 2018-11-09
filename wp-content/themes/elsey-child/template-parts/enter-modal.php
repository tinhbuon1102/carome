<?php
$elsey_brand_logo_default = cs_get_option('brand_logo_default');
?>
<div class="remodal event-enter-modal" data-remodal-id="eventaccess" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
  <!--<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>-->
  <div class="event-modal-content custom-modal-content">
	  <div class="custom-modal-head">
		  <?php echo '<div class="modal-logo"><img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_default ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="default-logo"></div>'; ?>
		  <div class="modal-hero-title">Welcome to CAROME. event!</div>
	  </div>
	  <div class="custom-modal-body">
		  <div class="custom-modal-body-inner">
		  <h2 id="modal1Title" class="modal_smallhead">イベント購入ページへの<br class="xs-show">アクセス方法</h2>
		  <div class="modal-content">
			  <div class="instruction-browser">
				  <?php
				  echo('<p>');
				  // 判定するのに小文字にする
$browser = strtolower($_SERVER['HTTP_USER_AGENT']);
if(stripos($browser,'Android') !== false){
        //Androidからのアクセス
	echo('Androidです。');
    } elseif (stripos($user_agent,'iPhone') !== false) {
	//iPhoneからのアクセス
	echo('iPhoneです。');
}else{
        echo "スマートフォンからのアクセスではありません。";
    }
				  echo('</p>');
// ユーザーエージェントの情報を基に判定
				  echo('<p>');
if (strstr($browser , 'edge')) {
    echo('ご使用のブラウザはEdgeです。');
} elseif (strstr($browser , 'trident') || strstr($browser , 'msie')) {
    echo('ご使用のブラウザはInternet Explorerです。');
} elseif (strstr($browser , 'chrome')) {
    echo('ご使用のブラウザはGoogle Chromeです。');
} elseif (strstr($browser , 'firefox')) {
    echo('ご使用のブラウザはFirefoxです。');
} elseif (strstr($browser , 'safari')) {
    echo('ご使用のブラウザはSafariです。');
} elseif (strstr($browser , 'opera')) {
    echo('ご使用のブラウザはOperaです。');
} else {
    echo('知らん。');
}
				  echo('</p>');
				  ?>
			  </div>
			  <div class="instruction-event">
				<p>イベント購入ページへアクセスするには、以下の手順に従い、現在地情報を許可してください。<?php //esc_html_e( 'イベント購入ページへアクセスするには、以下の手順に従い、現在地情報を許可してください。', 'elsey' ); ?></p>
				  <ol class="instruction-enter count-num">
					  <li><div class="inst-txt">一番下に表示されている<strong>「現在地情報を確認」ボタンをクリック</strong>してください。</div></li>
					  <li>
						  <div class="inst-txt">ご使用のブラウザから<strong>現在の位置情報を利用する許可の確認</strong>がでますので、<strong>OK(または許可)をクリック</strong>してください。</div>
						  <div class="inst-img"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/event/sample_geo-alert.jpg" alt="geoscreen" /></div>
					  </li>
					  <li><div class="inst-txt">イベント購入ページへアクセスが成功すると<strong>アクセスポイントのマッチに成功したメッセージが表示</strong>されます。</div></li>
				  </ol>
			  </div>
			  
		  </div>
		</div><!--/custom-modal-body-inner-->
	  </div><!--/custom-modal-body-->
	  <div class="custom-modal-foot">
		  <a href="#" class="button check-location-button button-before-icon"><i class="evg-icon evg-icon-pin-user"></i><span class="icon-nexttext"><?php esc_html_e( '現在地情報を確認', 'elsey' ); ?></span></a>
	  </div>
	  
  </div>
</div>