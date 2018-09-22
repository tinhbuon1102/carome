function execTrade ( response ) {
	if( response.resultCode != 000 ){
	window.alert( '購入処理中にエラーが発生しました' )
	}else{
	//予め購入フォームに用意したtokenフィールドに、値を設定
	document.getElementById('token_cc').value = response.tokenObject.token;
	document.getElementById('maskedCardNo').value = response.tokenObject.maskedCardNo;
	//スクリプトからフォームをsubmit
	jQuery('form.woocommerce-checkout').submit() 
	}
}
jQuery(function($){
	if ($('form.woocommerce-checkout').length)
	{
		$('body').on('click', '#place_order', function(e){
			if (jQuery('input[name="payment_method"]:checked').val() == 'epsilon' && jQuery('#epsilon-use-stored-payment-info-no').is(':checked'))
			{
				e.preventDefault();
				var cardObj = {};
				cardObj.cardno = document.getElementById('card_number').value;
				cardObj.expire = document.getElementById('expire_y').value + document.getElementById('expire_m').value;
				cardObj.securitycode = document.getElementById('cvv').value;
				cardObj.holdername = document.getElementById('holdername').value;
				EpsilonToken.init(document.getElementById("contract_code").value);
				EpsilonToken.getToken( cardObj , execTrade );
			}
		});
	}
});
