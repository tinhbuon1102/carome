<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_Payment_Status_Rules{

	function get_countries_list()
	{
		$countries = WC()->countries->countries;
		asort( $countries );
		return $countries;
	}

	function get_filed_values($keys = array())
  {
    $values = array();
    if (!empty($keys)) {
      foreach ($keys as $key) {
        $values[$key] = __( $key, 'woocommerce-payment-status' );
      }
    }
    return $values;
  }
	function get_products_list()
		{
	    $values = array();
	    $args   = array( 'post_type' => 'product', 'posts_per_page' => -1 );
	    $loop = new WP_Query( $args );
	    if($loop->have_posts()){
	      foreach ($loop->posts as $post) {
	        $values[$post->ID] = $post->post_title;
	      }
	    }
		return $values;
	}

	function get_products_cats_list()
	{
		$taxonomies = array( 'product_cat' );

		$args = array(
		    'orderby'           => 'name', 
		    'order'             => 'ASC',
		    'hide_empty'        => false, 
		    'fields'            => 'all',
		    'hierarchical'      => true
		); 

		$terms = get_terms($taxonomies, $args);
	    $values = array();

	    if($terms){
	      foreach ($terms as $term) {
	        $values[$term->term_id] = $term->name;
	      }
	    }
		return $values;
	}
	function get_shipping_methods()
	{
		$methods = array();
	    $ss = WC()->shipping->load_shipping_methods();
	    if (!empty($ss)) {
	      foreach ($ss as $code => $s) {
	        $methods[$code] = $s->method_title;
	      }
	    }
	    global $wpdb;
	    $t           = "{$wpdb->prefix}woocommerce_shipping_table_rates";
		if( $wpdb->get_var("SHOW TABLES LIKE '$t'") == $t ){
		    $shipping_table_rates = $wpdb->get_results("SELECT  rate.rate_id, rate.rate_label, rate.shipping_method_id, methods.shipping_method_type, zone.zone_id, zone.zone_name
		    	FROM {$wpdb->prefix}woocommerce_shipping_table_rates as rate
		    	LEFT JOIN {$wpdb->prefix}woocommerce_shipping_zone_shipping_methods methods ON(rate.shipping_method_id = methods.shipping_method_id)
		    	LEFT JOIN {$wpdb->prefix}woocommerce_shipping_zones zone ON(zone.zone_id = methods.zone_id)
		    	GROUP BY rate.rate_label, rate.shipping_method_id
		    	" );
		    if($shipping_table_rates){
		    	foreach ($shipping_table_rates as $rate) {
		    		$code = $rate->shipping_method_type . '-' . $rate->shipping_method_id . ' : ' . $rate->rate_id;
		    		if($rate->zone_id == 0)
		    			$methods[$code] = $rate->rate_label;
		    		else
		    			$methods[$code] = $rate->zone_name . ' - ' . $rate->rate_label;
		    	}
		    }
		}
		return $methods;
	}
	public function get_payment_methods()
	{
		$methods = array();
	    $gateways = WC()->payment_gateways;
	    foreach ( $gateways->payment_gateways as $gateway ) {
	        $methods[ $gateway->id ] = $gateway->title;
	    }
		return $methods;
	}
	public function get_conditions_fields($field = '')
	{
		$conditions_fields = array(
			'order_number' => array(
					'name' => __( 'Order Number', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not', 'is greater than', 'is less than'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'number'
							)
					)
				),
			'order_total' => array(
					'name' => __( 'Order Total', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not', 'is greater than', 'is less than'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'number'
							)
					)
				),
			'purchased_items' => array(
					'name' => __( 'Purchased Items', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not', 'is greater than', 'is less than'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'number'
							)
					)
				),
			'billing_country' => array(
					'name' => __( 'Billing Country', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'multi_select',
								'values' => $this->get_countries_list()
							)
					)
				),
			'shipping_country' => array(
					'name' => __( 'Shipping Country', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'multi_select',
								'values' => $this->get_countries_list()
							)
					)
				),
      'billing_first_name' => array(
          'name' => __( 'Billing First Name', 'woocommerce-payment-status' ),
          'first_col' => array(
              array(
                'type'   => 'select',
                'values' => $this->get_filed_values(array('contains', 'does not contain', 'begins with', 'ends with', 'is', 'is not'))
              )
          ),
          'second_col' => array(
              array(
                'type'   => 'text'
              )
          )
        ),
      'billing_last_name' => array(
          'name' => __( 'Billing Last Name', 'woocommerce-payment-status' ),
          'first_col' => array(
              array(
                'type'   => 'select',
                'values' => $this->get_filed_values(array('contains', 'does not contain', 'begins with', 'ends with', 'is', 'is not'))
              )
          ),
          'second_col' => array(
              array(
                'type'   => 'text'
              )
          )
        ),
			'shipping_first_name' => array(
					'name' => __( 'Shipping First Name ', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('contains', 'does not contain', 'begins with', 'ends with', 'is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'text'
							)
					)
				),
			'shipping_last_name' => array(
					'name' => __( 'Shipping Last Name', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('contains', 'does not contain', 'begins with', 'ends with', 'is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'text'
							)
					)
				),
			'customer_email' => array(
					'name' => __( 'Customer Email', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('contains', 'does not contain', 'begins with', 'ends with', 'is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'text'
							)
					)
				),
			'customer_email' => array(
					'name' => __( 'Customer Email', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('contains', 'does not contain', 'begins with', 'ends with', 'is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'text'
							)
					)
				),
			'telephone_number' => array(
					'name' => __( 'Telephone Number', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('contains', 'does not contain', 'begins with', 'ends with', 'is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'text'
							)
					)
				),
			'shipping' => array(
					'name' => __( 'Shipping Method', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_shipping_methods()
							)
					)
				),
			'order_notes' => array(
					'name' => __( 'Order Notes', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is true', 'is false'))
							)
					)
				),
			'payment_method' => array(
					'name' => __( 'Payment Method', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_payment_methods()
							)
					)
				),
			'order_date' => array(
					'name' => __( 'Order Date', 'woocommerce-payment-status' ),
					'first_col' => array(
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('is', 'is not', 'is after', 'is before', 'in the last', 'not in the last'))
							)
					),
					'second_col' => array(
							array(
								'type'   => 'date'
							),
							array(
								'type'   => 'number'
							),
							array(
								'type'   => 'select',
								'values' => $this->get_filed_values(array('days', 'weeks', 'months'))
							)
					)
				),
	      'products' => array(
	          'name' => __( 'Product', 'woocommerce-payment-status' ),
	          'first_col' => array(
	              array(
	                'type'   => 'select',
	                'values' => $this->get_filed_values(array('is', 'is not'))
	              )
	          ),
	          'second_col' => array(
	              array(
	                'type'   => 'search_product',
	              )
	          )
	        ),
	      	'product_categories' => array(
	          'name' => __( 'Product category', 'woocommerce-payment-status' ),
	          'first_col' => array(
	              array(
	                'type'   => 'select',
	                'values' => $this->get_filed_values(array('is', 'is not'))
	              )
	          ),
	          'second_col' => array(
	              array(
	                'type'   => 'multi_select',
	                'values' => $this->get_products_cats_list()
	              )
	          )
	        ),
		);
    $conditions_fields = apply_filters('wc_payment_status_rules_conditions_fields', $conditions_fields, $this);
    
		if (!empty($field)) {
			return $conditions_fields[$field];
		}
		return $conditions_fields;
	}
	
	function display_edit_form()
	{
		$status = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : '';
		$data = array();
		if( !empty($status) ){
			$data = get_option('wc_payment_status_rules_'.$status);
		}
		if( !$data || empty($data) ){
			$data = array(
				'match_rules'        => 'any',
				'payment_status'     => 'paid',
				'conditions'         => array(array('order_var'=>'order_number', 'rule'=>'', 'value'=>'') )
			);
		}
		
		$conditions_data    = isset($data['conditions']) ? $data['conditions'] : array(array('order_var'=>'order_number', 'rule'=>'', 'value'=>'') );
		$conditions_fields  = $this->get_conditions_fields();
		
		?>
		<tr class="form-field form-required">
			<th>
				<label><?php _e('Conditions', 'woocommerce-payment-status'); ?></label>
			</th>
             <td>
              <div id="match_rules_wrap">
                    Match <select name="rules[match_rules]" id="match_rules"> 
                    <?php echo '<option value="all" '.($data['match_rules'] == 'all' ? 'selected="selected"' : '').'>All</option>'; ?>
                    <?php echo '<option value="any" '.($data['match_rules'] == 'any' ? 'selected="selected"' : '').'>Any</option>'; ?>
                  </select>
                  of the following rules:
              </div>
                <table class="conditions_table payment_status_conditions_table widefat conditions_table_edit">
                  <thead>
                    <tr>
                      <th class="manage-column column-cb check-column"><input type="checkbox"></th>
                      <th class="variable-column"><?php _e('Variable', 'woocommerce-payment-status'); ?></th>
          					  <th class="rule-column"><?php _e('Rule', 'woocommerce-payment-status'); ?></th>
          					  <th class="value-column"><?php _e('Value', 'woocommerce-payment-status'); ?></th>
                    </tr>
                  </thead>
                  <tbody id="conditions">
                  <?php if(!empty($conditions_data)) { ?>
                    <?php $c_key = -1; foreach ($conditions_data as $condition) { $c_key++;  ?>
                    <tr data-index="<?php echo $c_key; ?>">
                      <th class="check-column"><input type="checkbox" value="<?php echo $c_key; ?>"></th>
                      <td class="order_var">
                        <select name="rules[conditions][<?php echo $c_key; ?>][order_var]">
                          <?php foreach ($conditions_fields as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php echo ($key == $condition['order_var'] ? 'selected="selected"' : ''); ?> ><?php echo $value['name']; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <td class="order_rule">
                        <?php 
                          if(isset($conditions_fields[$condition['order_var']]['first_col']) ){
                            $i = 0;
                            foreach ($conditions_fields[$condition['order_var']]['first_col'] as $field_id => $field) {
                        		if( !isset($condition['rule']) ){
                            		$condition['rule'] = '';
                            	}
                                switch ($field['type']) {
                                    case 'text':
                                        echo '<input type="text" class="'.$key.'_inp"  name="rules[conditions]['.$c_key.'][rule]" value="'.$condition['rule'].'">';
                                        break;
                                    case 'number':
                                        echo '<input type="number" step="any" min="0" class="'.$key.'_inp" name="rules[conditions]['.$c_key.'][rule]" value="'.$condition['rule'].'">';
                                        break;
                                    case 'date':
                                        echo '<input type="text" class="'.$key.'_inp ui_date" name="rules[conditions]['.$c_key.'][rule]" value="'.$condition['rule'].'">';
                                        break;
                                    case 'select':
                                        echo '<select class="'.$key.'_inp __chosen_select" name="rules[conditions]['.$c_key.'][rule]">';
                                            foreach ($field['values'] as $val => $name) {
                                                echo '<option value="'.$val.'" '. ($val == $condition['rule'] ? 'selected="selected"' : '') .' >'.$name.'</option>';
                                            }
                                        echo '</select>';
                                        break;
                                    case 'multi_select':
                                    	if( !is_array($condition['rule']) ){
                                    		$condition['rule'] = array();
                                    	}
                                        echo '<select multiple="multiple" class="'.$key.'_inp chosen_multi_select" name="rules[conditions]['.$c_key.'][rule][]">';
                                            foreach ($field['values'] as $val => $name) {
                                                echo '<option value="'.$val.'" '. ($val == $condition['rule'] ? 'selected="selected"' : '') .'>'.$name.'</option>';
                                            }
                                        echo '</select>';
                                        break;
                                }
                                $i++;
                            }
                          }
                        ?>
                      </td>
                      <td class="order_value">
                        <?php 
                          if(isset($conditions_fields[$condition['order_var']]['second_col']) ){
                            $i = 0;
                            foreach ($conditions_fields[$condition['order_var']]['second_col'] as $field_id => $field) {
                            	if( !isset($condition['value'][$i]) ){
                            		$condition['value'][$i] = '';
                            	}
                                switch ($field['type']) {
                                    case 'search_product':
                                    	$product_ids = array_filter( array_map( 'absint', explode( ',', $condition['value'][$i] ) ) );
										$json_ids    = array();

										foreach ( $product_ids as $product_id ) {
											$product = wc_get_product( $product_id );
											if ( is_object( $product ) ) {
												$json_ids[ $product_id ] = wp_kses_post( $product->get_formatted_name() );
											}
										}
										$val = implode( ',', array_keys( $json_ids ) );
                                          echo '<input type="text" class="'.$key.'_inp wc-product-search"  name="rules[conditions]['.$c_key.'][value]['.$i.']"  data-selected="'.esc_attr( json_encode( $json_ids ) ).'"  value="'.$val.'" data-placeholder="'.__('Search product', 'woocommerce-payment-status' ).'"  data-multiple="true" style="width: 100%;" data-action="woocommerce_json_search_products">';
                                        break;
                                    case 'text':
                                          echo '<input type="text" class="'.$key.'_inp"  name="rules[conditions]['.$c_key.'][value]['.$i.']" value="'.$condition['value'][$i].'">';
                                        break;
                                    case 'number':
                                          echo '<input type="number" step="any" min="0" class="'.$key.'_inp" name="rules[conditions]['.$c_key.'][value]['.$i.']" value="'.$condition['value'][$i].'">';                                      
                                        break;
                                    case 'date':
                                          echo '<input type="text" class="'.$key.'_inp ui_date" name="rules[conditions]['.$c_key.'][value]['.$i.']" value="'.$condition['value'][$i].'">';
                                        break;
                                    case 'select':
                                          echo '<select class="'.$key.'_inp __chosen_select" name="rules[conditions]['.$c_key.'][value]['.$i.']">';
                                            foreach ($field['values'] as $val => $name) {
                                                echo '<option value="'.$val.'" '. ($val == $condition['value'][$i] ? 'selected="selected"' : '') .'>'.$name.'</option>';
                                            }
                                          echo '</select>';
                                        break;
                                    case 'multi_select':
                                    	if( !is_array($condition['value'][$i]) ){
                                    		$condition['value'][$i] = array();
                                    	}
                                        $c = $condition['value'][$i];
                                        echo '<select multiple="multiple" class="'.$key.'_inp chosen_multi_select" name="rules[conditions]['.$c_key.'][value]['.$i.'][]">';
                                        	foreach ($field['values'] as $val => $name) {
                                            	echo '<option value="'.$val.'" '. ( in_array($val, $c) ? 'selected="selected"' : '') .'>'.$name.'</option>';
                                        	}
                                      	echo '</select>';
                                        
                                        break;
                                }
                                $i++;
                            }
                          }
                        ?>
                      </td>
                      
                    </tr>
                    <?php }
                  } ?>
                    
                  </tbody>                    
                  <tfoot>
                  <tr>
                    <th colspan="4" style="padding: 20px;">
                      <a class="button plus insert" id="insert_condition_row" href="#"><?php _e( 'Insert Row', 'woocommerce-payment-status' ); ?></a>
                      <a class="button minus remove_conditions" href="#"><?php _e( 'Remove Selected Row(s)', 'woocommerce-payment-status' ); ?></a>
                    </th>
                  </tr>
                </tfoot>
                </table>
                <p class="description"><?php _e( 'Add and configure conditions which need to be met in order for this rule to be activated.', 'woocommerce-payment-status' ); ?></p>
              </td>
            </tr>
            <tr class="form-field form-required">
              <th><label for="payment_status"><?php _e('Payment Status', 'woocommerce-payment-status'); ?></label></th>
              <td>
              	<select name="rules[payment_status]" id="match_rules" class="wc-enhanced-select">
              		<?php 
              		$p_st = $this->get_filed_values(array('Paid', 'Not Paid', 'Partially Paid'));
              		if($p_st){
              			foreach ($p_st as $st => $st_name) {
              				echo '<option value="' . $st . '" '.selected($data['payment_status'], $st, false).'>' . $st_name . '</option>';		
              			}
              		}
              		?>
                  </select> 
                  <p class="description"><?php _e('Select what the payment status should be when this rule is matched.', 'woocommerce-payment-status'); ?></p>           
        <?php 
        $default_f = array();
        foreach ($conditions_fields as $key => $value) {
            if(!isset($value['first_col']) ) continue;
          	if( !isset( $default_f[$key.'_inp_first'] )){
          		$default_f[$key.'_inp_first'] = '';
          	}
            $i = 0;
            foreach ($value['first_col'] as $field_id => $field) {
                switch ($field['type']) {
                    case 'text':
                        $default_f[$key.'_inp_first'] .= '<input type="text" class="'.$key.'_inp_first"  name="rules[conditions][__replace__][rule]">';
                        break;
                    case 'number':
                        $default_f[$key.'_inp_first'] .= '<input type="number" step="any"  min="0" class="'.$key.'_inp_first" name="rules[conditions][__replace__][rule]">';
                        break;
                    case 'date':
                        $default_f[$key.'_inp_first'] .= '<input type="text" class="'.$key.'_inp_first ui_date" name="rules[conditions][__replace__][rule]">';
                        break;
                    case 'select':
                        $default_f[$key.'_inp_first'] .= '<select class="'.$key.'_inp_first __chosen_select" name="rules[conditions][__replace__][rule]">';
                            foreach ($field['values'] as $val => $name) {
                                $default_f[$key.'_inp_first'] .=  '<option value="'.$val.'">'.$name.'</option>';
                            }
                        $default_f[$key.'_inp_first'] .= '</select>';
                        break;
                    case 'multi_select':
                        $default_f[$key.'_inp_first'] .= '<select multiple="multiple" class="'.$key.'_inp_first chosen_multi_select" name="rules[conditions][__replace__][rule][]">';
                            foreach ($field['values'] as $val => $name) {
                                $default_f[$key.'_inp_first'] .= '<option value="'.$val.'">'.$name.'</option>';
                            }
                        $default_f[$key.'_inp_first'] .= '</select>';
                        break;
                }
                $i++;
            }
          }
        
        foreach ($conditions_fields as $key => $value) {
            if(!isset($value['second_col']) ) continue;
            if( !isset( $default_f[$key.'_inp_second'] )){
          		$default_f[$key.'_inp_second'] = '';
          	}
            $i = 0;
            foreach ($value['second_col'] as $field_id => $field) {
                switch ($field['type']) {
                	case 'search_product':
                        $default_f[$key.'_inp_second'] .=  '<input type="text" class="'.$key.'_inp wc-product-search" name="rules[conditions][__replace__][value]['.$i.']" data-placeholder="'.__('Search product', 'woocommerce-payment-status' ).'"  data-multiple="true" style="width: 100%;" data-action="woocommerce_json_search_products">';
                        break;
                    case 'text':
                        $default_f[$key.'_inp_second'] .= '<input type="text" class="'.$key.'_inp_second"  name="rules[conditions][__replace__][value]['.$i.']">';
                        break;
                    case 'number':
                        $default_f[$key.'_inp_second'] .= '<input type="number" step="any"  min="0" class="'.$key.'_inp_second" name="rules[conditions][__replace__][value]['.$i.']">';
                        break;
                    case 'date':
                        $default_f[$key.'_inp_second'] .= '<input type="text" class="'.$key.'_inp_second ui_date" name="rules[conditions][__replace__][value]['.$i.']">';
                        break;
                    case 'select':
                        $default_f[$key.'_inp_second'] .= '<select class="'.$key.'_inp_second __chosen_select" name="rules[conditions][__replace__][value]['.$i.']">';
                            foreach ($field['values'] as $val => $name) {
                                $default_f[$key.'_inp_second'] .= '<option value="'.$val.'">'.$name.'</option>';
                            }
                        $default_f[$key.'_inp_second'] .= '</select>';
                        break;
                    case 'multi_select':
                        $default_f[$key.'_inp_second'] .= '<select multiple="multiple" class="'.$key.'_inp_second chosen_multi_select" name="rules[conditions][__replace__][value]['.$i.'][]">';
                            foreach ($field['values'] as $val => $name) {
                                $default_f[$key.'_inp_second'] .= '<option value="'.$val.'">'.$name.'</option>';
                            }
                        $default_f[$key.'_inp_second'] .= '</select>';
                        break;
                }
                $i++;
            }
        }
        ?>
        <script type="text/javascript" >
        	var default_f = <?php echo json_encode($default_f); ?>;
        </script>
          		</td>
            </tr>
        <?php

	}

	function check_order($post_id = 0, $status_rules = array()){
        if($post_id == 0) return false;
        if(empty($status_rules)) return true;
        $check = false;
        
        $order = new WC_Order($post_id);
        $conditions =  $status_rules['conditions'];
        $i = 0;
        foreach ($conditions as $condition) {
            switch ($condition['order_var']) {
                case 'order_status':
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $order->post_status, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'order_number':
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $post_id, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'order_total':
                
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $order->get_total(), $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'purchased_items':
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $order->get_item_count(), $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'billing_country':
                    $billing_country =  $order->billing_country;
                    if($condition['rule'] == 'is'){
                    	$condition_eval = in_array($billing_country, $condition['value'][0]);
                    }else{
                    	$condition_eval = !in_array($billing_country, $condition['value'][0]);
                    }
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'shipping_country':
                    $shipping_country =  $order->shipping_country;
                    if($condition['rule'] == 'is'){
                    	$condition_eval = in_array($shipping_country, $condition['value'][0]);
                    }else{
                    	$condition_eval = !in_array($shipping_country, $condition['value'][0]);
                    }
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'billing_first_name':
                    $billing_first_name =  $order->billing_first_name;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $billing_first_name, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'billing_last_name':
                    $billing_last_name =  $order->billing_last_name;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $billing_last_name, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'shipping_first_name':
                    $shipping_first_name =  $order->shipping_first_name;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $shipping_first_name, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'shipping_last_name':
                    $shipping_last_name =  $order->shipping_last_name;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $shipping_last_name, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'customer_email':
                    $customer_email =  $order->billing_email;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $customer_email, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'telephone_number':
                    $telephone_number =  $order->billing_phone;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $telephone_number, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'shipping':
                    $shipping =  $order->get_shipping_methods();
                    $condition_eval = false;
                    if( class_exists('WC_Shipping_Zones')){
                        $zones = $this->get_shipping_zones($condition['value'][0]);
                        $condition['value'] =  array_merge($zones, array($condition['value'][0]));
                    }
                    if($condition['rule'] == 'is'){
                        foreach ($shipping as $si) {
                            if( in_array($si['method_id'], $condition['value']) ) $condition_eval = true;
                        }
                    }else if($condition['rule'] == 'is not'){
                        foreach ($shipping as $si) {
                            if( !in_array($si['method_id'], $condition['value']) ) $condition_eval = true;
                        }
                    }
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'payment_method':
                    $payment_method =  $order->payment_method;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $payment_method, $condition['value'][0]);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'order_notes':
                    $order_notes =  $order->customer_note;
                    $condition_eval = $this->condition_rule_symbol($condition['rule'], $order_notes, '');
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;

                case 'products':
                    $items     =  $order->get_items();
                    $items_ids = array();
                    foreach ($items as $item) {
                        $items_ids[] = $item['product_id'];
                    }
                    $v = array_filter( array_map( 'absint', explode( ',', $condition['value'][0] ) ) );
                    $condition_eval = false;                            
                    if($condition['rule'] == 'is'){
                        if ( sizeof( array_intersect( $items_ids, $v ) ) > 0 )
                            $condition_eval = true;
                    }else if($condition['rule'] == 'is not'){
                        if ( sizeof( array_intersect( $items_ids, $v ) ) == 0 )
                            $condition_eval = true;
                    }
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                case 'product_categories':
                    $items     =  $order->get_items();
                    $terms     = array();
                    $args      = array('fields' => 'ids');
                    foreach ($items as $item) {
                        $pr_id  = $item['product_id'];
                    	$_terms = wp_get_post_terms( $pr_id, 'product_cat', $args );
                    	$terms  =  array_merge($terms, $_terms);
                    }                    
                    $v = $condition['value'][0];
                    $condition_eval = false;                            
                    if($condition['rule'] == 'is'){
                        if ( sizeof( array_intersect( $terms, $v ) ) > 0 )
                            $condition_eval = true;
                    }else if($condition['rule'] == 'is not'){
                        if ( sizeof( array_intersect( $terms, $v ) ) == 0 )
                            $condition_eval = true;
                    }
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;

                case 'order_date':
                    if($condition['rule'] == 'is after'){
                        $order_date =  strtotime($order->order_date);
                        $date = strtotime(date( 'm/d/Y', $order_date ) );
                        $rule_date  =  strtotime($condition['value'][0]);
                        $condition_eval = $this->condition_rule_symbol('is greater than', $date, $rule_date);
                    }else if($condition['rule'] == 'is before'){
                        $order_date =  strtotime($order->order_date);
                        $date = strtotime(date( 'm/d/Y', $order_date ) );
                        $rule_date  =  strtotime($condition['value'][0]);
                        $condition_eval = $this->condition_rule_symbol('is less than', $date, $rule_date);
                    }else if($condition['rule'] == 'in the last'){
                        
                        $rule_date = strtotime( '-'.$condition['value'][1].' '.$condition['value'][2] );

                        $order_date =  strtotime($order->order_date);
                        $date = strtotime(date( 'm/d/Y', $order_date ) );

                        $condition_eval = $this->condition_rule_symbol('is greater than', $date, $rule_date);
                    }else if($condition['rule'] == 'not in the last'){
                        $rule_date = strtotime( '-'.$condition['value'][1].' '.$condition['value'][2] );

                        $order_date =  strtotime($order->order_date);
                        $date = strtotime(date( 'm/d/Y', $order_date ) );

                        $condition_eval = $this->condition_rule_symbol('is less than', $date, $rule_date);
                    }else{                                
                        $order_date =  strtotime($order->order_date);
                        $date = date( 'm/d/Y', $order_date );
                        $condition_eval = $this->condition_rule_symbol($condition['rule'], $date, $condition['value'][0]);
                    }
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    break;
                default:
                    $condition_eval = apply_filters('wc_payment_status_rule_order_change_status', false, $condition['order_var'], $condition['rule'], $condition['value'], $post_id, $this);
                    if( $condition_eval ){
                        $check = true;
                        $i++;
                    }
                    
                    break;
            }
        }
        
        if($status_rules['match_rules'] == 'all' && count($conditions) != $i){
            $check = false;
        }elseif($status_rules['match_rules'] != 'all' && $i == 0){
			$check = false;
        }
        return $check;
    }
    function condition_rule_symbol($rule = '', $f, $s){
        if(empty($rule)) return false;
        switch ($rule) {
            case 'is':
                if($f == $s) return true;
                else return false;
                break;
            case 'is not':
                if($f != $s) return true;
                else return false;
                break;
            case 'contains':
                if(strpos($f,$s) !== false) return true;
                else return false;
                break;
            case 'does not contain':
                if(strpos($f,$s) === false) return true;
                else return false;
                break;
            case 'begins with':
                $l = strlen($s);
                if(substr($f,0, $l) == $s) return true;
                else return false;
                break;
            case 'ends with':
                $l = strlen($s);
                if(substr($f,-$l) == $s) return true;
                else return false;
                break; 
            case 'is greater than':
                if($f > $s) return true;
                else return false;
                break;  
            case 'is less than':
                if($f < $s) return true;
                else return false;
                break;
            case 'is true':
                if($f) return true;
                else return false;
                break;
            case 'is false':
                if(!$f) return true;
                else return false;
                break;
            default:
                return false;
                break;
        }
    }
}