<?php global $woocommerce; ?>
<form id="wc-epsilon-pro-setting-form" method="post" action=""  enctype="multipart/form-data">
<?php wp_nonce_field( 'my-nonce-key','wc-epsilon-pro-setting');?>
<h3><?php echo __( 'Epsilon Initial Setting', 'wc4jp-epsilon' );?></h3>
<table class="form-table">
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_company"><?php echo __( 'Connect ID', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="text" name="epsilon_pro_cid" value="<?php echo get_option('wc-epsilon-pro-cid');?>" >
    <p class="description"><?php echo __( 'Please input User ID from Epsilon documents', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_company"><?php echo __( 'Connect Password', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="text" name="epsilon_pro_cpass" value="<?php echo get_option('wc-epsilon-pro-cpass');?>" >
    <p class="description"><?php echo __( 'Please input User Password from Epsilon documents', 'wc4jp-epsilon' );?></p></td>
</tr>
</table>
<h3><?php echo __( 'Epsilon Payment Method', 'wc4jp-epsilon' );?></h3>
<table class="form-table">
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_cc"><?php echo __( 'Credit Card', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_cc" value="1" <?php $options['wc-epsilon-pro-cc'] =get_option('wc-epsilon-pro-cc') ;checked( $options['wc-epsilon-pro-cc'], 1 ); ?>><?php echo __( 'Credit Card', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Credit Card', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_cs"><?php echo __( 'Convenience store', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_cs" value="1" <?php $options['wc-epsilon-pro-cs'] =get_option('wc-epsilon-pro-cs') ;checked( $options['wc-epsilon-pro-cs'], 1 ); ?>><?php echo __( 'Convenience store', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Convenience store', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_nb"><?php echo __( 'Net Bank', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_nb" value="1" <?php $options['wc-epsilon-pro-nb'] =get_option('wc-epsilon-pro-nb') ;checked( $options['wc-epsilon-pro-nb'], 1 ); ?>><?php echo __( 'Net Bank', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Net Bank', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_wm"><?php echo __( 'Web Money', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_wm" value="1" <?php $options['wc-epsilon-pro-wm'] =get_option('wc-epsilon-pro-wm') ;checked( $options['wc-epsilon-pro-wm'], 1 ); ?>><?php echo __( 'Web Money', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Web Money', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_bc"><?php echo __( 'BitCash', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_bc" value="1" <?php $options['wc-epsilon-pro-bc'] =get_option('wc-epsilon-pro-bc') ;checked( $options['wc-epsilon-pro-bc'], 1 ); ?>><?php echo __( 'BitCash', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of BitCash', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_em"><?php echo __( 'Electric Money', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_em" value="1" <?php $options['wc-epsilon-pro-em'] =get_option('wc-epsilon-pro-em') ;checked( $options['wc-epsilon-pro-em'], 1 ); ?>><?php echo __( 'Electric Money', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Electric Money', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_pe"><?php echo __( 'Pay-easy', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_pe" value="1" <?php $options['wc-epsilon-pro-pe'] =get_option('wc-epsilon-pro-pe') ;checked( $options['wc-epsilon-pro-pe'], 1 ); ?>><?php echo __( 'Pay-easy', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Pay-easy', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_pp"><?php echo __( 'Pay Pal', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_pp" value="1" <?php $options['wc-epsilon-pro-pp'] =get_option('wc-epsilon-pro-pp') ;checked( $options['wc-epsilon-pro-pp'], 1 ); ?>><?php echo __( 'Pay Pal', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Pay Pal', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_yw"><?php echo __( 'Yahoo! Walet', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_yw" value="1" <?php $options['wc-epsilon-pro-yw'] =get_option('wc-epsilon-pro-yw') ;checked( $options['wc-epsilon-pro-yw'], 1 ); ?>><?php echo __( 'Yahoo! Walet', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Yahoo! Walet', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_sc"><?php echo __( 'SmartPhone Carrier', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_sc" value="1" <?php $options['wc-epsilon-pro-sc'] =get_option('wc-epsilon-pro-sc') ;checked( $options['wc-epsilon-pro-sc'], 1 ); ?>><?php echo __( 'SmartPhone Carrier', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of SmartPhone Carrier', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_sn"><?php echo __( 'SBI Net Bank', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_sn" value="1" <?php $options['wc-epsilon-pro-sn'] =get_option('wc-epsilon-pro-sn') ;checked( $options['wc-epsilon-pro-sn'], 1 ); ?>><?php echo __( 'SBI Net Bank', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of SBI Net Bank', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_gp"><?php echo __( 'GMO Postpay Payment', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_gp" value="1" <?php $options['wc-epsilon-pro-gp'] =get_option('wc-epsilon-pro-gp') ;checked( $options['wc-epsilon-pro-gp'], 1 ); ?>><?php echo __( 'GMO Postpay Payment', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of GMO Postpay Payment', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_mccc"><?php echo __( 'Multi-currency Credit Card', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_mccc" value="1" <?php $options['wc-epsilon-pro-mccc'] =get_option('wc-epsilon-pro-mccc') ;checked( $options['wc-epsilon-pro-mccc'], 1 ); ?>><?php echo __( 'Multi-currency Credit Card', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of Multi-currency Credit Card', 'wc4jp-epsilon' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_jp"><?php echo __( 'JCB PREMO', 'wc4jp-epsilon' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="epsilon_pro_jp" value="1" <?php $options['wc-epsilon-pro-jp'] =get_option('wc-epsilon-pro-jp') ;checked( $options['wc-epsilon-pro-jp'], 1 ); ?>><?php echo __( 'JCB PREMO', 'wc4jp-epsilon' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use the payment method of JCB PREMO', 'wc4jp-epsilon' );?></p></td>
</tr>
</table>
<p class="submit">
   <input name="save" class="button-primary" type="submit" value="<?php echo __( 'Save changes', 'wc4jp-epsilon' );?>">
</p>
</form>
