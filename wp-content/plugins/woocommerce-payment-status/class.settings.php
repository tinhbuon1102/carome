<?php
/**
 * WooCommerce Payment Status Settings
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Settings_Payment_Status' ) ) :

/**
 * WC_Settings_Accounts
 */
class WC_Settings_Payment_Status extends WC_Settings_Page {

	/**
	 * Default WooCommerce's order statuses
	 * @var array
	 */
	private $default_statuses = array(
				'pending',
				'failed',
				'on-hold',
				'processing',
				'completed',
				'refunded',
				'cancelled'
			);
  private $table_headers;

	/**
	 * Constructor.
	 */
	public function __construct() {
    $this->table_headers = array(
        'status'     => __( 'Order Status', 'woocommerce-payment-status' ),
        'default'    => __( 'Not Affected', 'woocommerce-payment-status' ),
        'paid'       => __( 'Paid', 'woocommerce-payment-status' ),
        'partial'	   => __( 'Partially Paid', 'woocommerce-payment-status'),
        'unpaid'     => __( 'Not Paid', 'woocommerce-payment-status' ),
        'pay_button' => __( 'Pay Button', 'woocommerce-payment-status' ),
        'rules'      => __( 'Rules', 'woocommerce-payment-status' )
      );

    if(!function_exists('wc_get_order_statuses')){
      $this->default_statuses = array_combine($this->default_statuses, $this->default_statuses);
    }else{
      $statuses = wc_get_order_statuses();
      if(!empty($statuses)){
        $this->default_statuses = array();
        foreach ($statuses as $key => $value) {
          if(strpos('wc-', $key) == 0){
            $key = substr($key, 3, strlen($key));
          }
          $this->default_statuses[$key] = $value;
        }
      }      
    }
		$this->id    = 'payment_status';
		$this->label = __( 'Payment Status', 'woocommerce' );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
    add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    add_action( 'woocommerce_admin_field_table', array( $this, 'output_fields_table' ) );
		add_action( 'woocommerce_admin_field_conditions_table', array( $this, 'output_fields_conditions_table' ) );
    //add_action( 'woocommerce_update_option_table' , array( $this, 'update_option_table' ));
    add_filter( 'woocommerce_admin_settings_sanitize_option' , array( $this, 'update_option_table' ), 50, 3);
    add_filter( 'woocommerce_admin_settings_sanitize_option' , array( $this, 'update_option_conditions_table' ), 51, 3);
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
    if( isset($_GET['status']) && isset($this->default_statuses[$_GET['status']]) ){
      return $this->get_rules_settings();
    }else{
      return $this->get_general_settings();
    }
      
	}
  public function get_rules_settings() {
    $status = $_GET['status'];
    return array(
      array(  'title' => __( 'Payment Status Rules', 'woocommerce-payment-status' ) . ' - ' . $this->default_statuses[$status] , 'type' => 'title','desc' => '', 'id' => 'payment_status_rules' ),

      array(
          'title'     => __( 'Conditions', 'woocommerce-payment-status' ),
          'type'      => 'conditions_table',
          'id'        => 'wc_payment_status_rules',
      ),

      array( 'type' => 'sectionend', 'id' => 'payment_status_rules'),
    );
  }

  public function get_general_settings() {

    $settings = array( array(  'title' => __( 'Payment Statuses Options', 'woocommerce-payment-status' ), 'type' => 'title','desc' => '', 'id' => 'payment_status_options' ));

    $settings[] = array(
                        'type'      => 'table',
                        'id'        => 'woocommerce_payment_status_default_',
                        'desc_tip'  =>  __( 'This will effect the default WooCommerce\'s statuses (e.g. Processing, Completed, etc.).', 'woocommerce-payment-status' ),
                        'rows'      => $this->default_statuses,
                        'cols'      => $this->table_headers,
                    );
    $settings[] = array(
                        'title' => __( 'Action Button', 'woocommerce-payment-status' ),
                        'type'      => 'checkbox',
                        'desc' => __( 'Tick this box to enable the action button for orders which are not paid.', 'woocommerce-payment-status' ),
                        'id'        => 'woocommerce_payment_status_action_icon_trigger'
                     );
    if ( class_exists( 'WC_Xero' ) ){
      $settings[] = array(
                        'title' => __( 'Xero', 'woocommerce-payment-status' ),
                        'default'   => 'mark_as_paid',
                        'type'      => 'select',
                        'options'   => array(
                          'mark_as_paid' => __('Mark order as paid when payment is sent to Xero', 'woocommerce-payment-status'),
                          'not_allow'    => __('Do not allow not paid orders to be sent to Xero', 'woocommerce-payment-status'),
                          ),
                        'id'        => 'woocommerce_payment_status_xero_payments'
                     );
    }
    $settings[] = array( 'type' => 'sectionend', 'id' => 'payment_status_options');

    return apply_filters( 'woocommerce_' . $this->id . '_settings', $settings);
  }

  public function output_fields_conditions_table($settings)
  {
    ?>
    <tbody>
    <?php 
    $rules = new WC_Payment_Status_Rules;
    $rules->display_edit_form();
    ?>
    </tbody>
    <?php
  }

   /**
   * Output the fields
   */
  public function output_fields_table($settings) {

    $rows = $settings['rows'];
    $cols = $settings['cols'];

    ?>
    <tbody>
    <tr>
      <td colspan="2">
        <table class="wp-list-table widefat fixed pages payment_settings">
          <thead>
            <tr>
              <?php
                foreach ($cols as $key => $value) {
                    if($key == 'status')
                      echo "<th class='column-".$key."'>".$value."</th>";
                    else
                      echo "<th class='column-".$key."'><center>".$value."</center></th>";
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
              $i = 1;
              $ff ='';
              foreach ($rows as $key => $value) {
                  $name = $settings['id'].$key;
                 if ($i%2 == 0) echo "<tr class='alt'>";
                  else  echo "<tr>";

                    $option_value = WC_Admin_Settings::get_option( $name, 'default' );
                   foreach ($cols as $kk => $vv) {
                    if($kk == 'status'){
                      echo "<td>".$value."</td>";
                    }
                    else if($kk == 'pay_button'){
                      $name =  'woocommerce_payment_status_action_pay_button_controller';
                      $option_value = get_option( $name, array() );
                      if(!is_array($option_value))
                        $option_value = array();
                      ?>
                      <td>
                        <center>
                        <input type="checkbox" <?php checked( in_array($key, $option_value),  true); ?> value="<?php echo $key; ?>" name="<?php echo $name; ?>[]">
                        </center>
                      </td>
                      <?php
                    }
                    else if($kk == 'rules'){
                      ?>
                      <td>
                        <center>
                        <a href="admin.php?page=wc-settings&tab=payment_status&status=<?php echo $key; ?>" class="button"><?php _e('Manage rules', 'woocommerce-payment-status'); ?></a>
                        </center>
                      </td>
                      <?php
                    }
                    else{
                      ?>
                      <td>
                        <center>
                        <input type="radio" <?php checked( $kk, $option_value ); ?> class="" style="" value="<?php echo $kk; ?>" name="<?php echo $name; ?>">
                        </center>
                      </td>
                      <?php
                    }
                  }
                echo "</tr>";
                $i++;
                }
              ?>
          </tbody>
        </table>
      </td>
    </tr>
    </tbody>
  <?php
  }
     /**
   * Save the fields
   */

  public function update_option_conditions_table($value, $options, $raw_value)
  {

    if($options['id'] != 'wc_payment_status_rules'){
      return $value;
    }
    $status = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : '';
    if(!empty($status) && isset($_POST['rules']) ){
      $name = $options['id'] . '_' .$status;
      update_option( $name, $_POST['rules'] );
    }
    return null;
  }
  public function update_option_table($value, $options, $raw_value) {
    if($options['id'] != 'woocommerce_payment_status_default_'){
      return $value;
    }
    $rows = $options['rows'];
    $cols = $options['cols'];
    $id   = $options['id'];

    foreach ($rows as $key => $value) {
      if ( isset( $_POST[ $id.$key ] ) ) {
        $update_options[$id.$key]  = $_POST[ $id.$key ];
      } else {
          $update_options[ $id.$key ]  = 'default';
      }
    }
    
    $pay_button =  'woocommerce_payment_status_action_pay_button_controller';
    if ( isset( $_POST[ $pay_button ] ) ) {
        $update_options[$pay_button]  = $_POST[ $pay_button ];
      } else {
        $update_options[ $pay_button ]  = array();
      }
    
    foreach( $update_options as $name => $val )
        update_option( $name, $val );

    return null;
  }


}

endif;

return new WC_Settings_Payment_Status();