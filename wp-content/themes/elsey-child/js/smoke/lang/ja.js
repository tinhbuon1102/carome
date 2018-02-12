(function($){
  $.fn.smkValidate.Languaje = {
    // 入力が必須の項目
    textEmpty        : '入力が必須の項目です',
    // emailの形式チェック
    textEmail        : '不正なメールアドレスです',
    // アルファベットか数値のみ許可
    textAlphanumeric : '半角英数で入力してください',
    // 半角数字のみ入力可能
    textNumber       : '半角数字のみで入力してください',
    // 数値が範囲外の場合
    textNumberRange  : '数値は<b> {@} </b>以上<b> {@} </b>以下で入力してください',
    // 10進数の数値のみ有効
    textDecimal      : '10進数の数値で入力してください',
    // 通貨として不正な場合
    textCurrency     : '正しい金額を入力してください',
    // selectで選択がされていない場合
    textSelect       : '選択してください',
    // チェックボックス/ラジオボタンの選択がされていない
    textCheckbox     : '選択してください',
    // テキストの長さが指定された文字数でない場合
    textLength       : '文字数は<b> {@} </b>文字で入力してください',
    // 文字数の範囲
    textRange        : '文字は<b> {@} </b>文字以上<b> {@} </b>以下で入力してください',
    // 最低4文字以上入力
    textSPassDefault : '4文字以上入力してください',
    // 最低6文字以上入力
    textSPassWeak    : '6文字以上入力してください',
    // 数値と文字で6文字以上入力
    textSPassMedium  : '6文字以上で少なくとも数値を1文字以上入力してください',
    // 6文字以上かつ1つ以上の数値と、1つは英大文字
    textSPassStrong  : '6文字以上で、少なくとも数値を1文字以上、英大文字を1文字以上入力してください',
    
	textUrl          : 'Please enter a valid url',
    textTel          : 'は電話番号として正しくありません',
    textColor        : 'Please enter a valid hex color',
    textDate         : 'Please enter a valid date',
    textDatetime     : 'Please enter a valid date and time',
    textMonth        : 'Please enter a valid month',
    textWeek         : 'Please enter a valid week',
    textTime         : 'Please enter a valid time',
    textPattern      : 'Enter a valid string'	
  };

  $.smkEqualPass.Languaje = {
    // パスワードが一致しない場合
    textEqualPass    : 'パスワードが一致しません'
  };

  $.smkDate.Languaje = {
    shortMonthNames : ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
    monthNames : ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"]
  };

}(jQuery));
