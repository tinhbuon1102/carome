jQuery( document ).ready( function($) {
	$('select[name=order_status]').change(function(){
		if( $('select[name=order_status]').val() != $('select[input=order_status_changed]').data('original') ){
			$('input[name=order_status_changed]').val('1');
		} else {
			$('input[name=order_status_changed]').val('0');
		}
	});


	if($('table.payment_status_conditions_table').length > 0){
		if($('#conditions tr').length > 1){
			$('#match_rules_wrap').show();
		}else{
			$('#match_rules_wrap').hide();
		}
	    var tmp_row = $('table.payment_status_conditions_table tbody#conditions tr').first().clone();
	    ruleConditions($('table.payment_status_conditions_table tbody#conditions tr').first().find('td.order_var select'));
	    $('.remove_conditions').click(function () {
	      var rows = $('#mainform table.payment_status_conditions_table tbody .check-column input:checked');
	      if(rows.length > 0){
	        var removed_rows = $('#removed_rows').val();
	        rows.each(function () {
            	$(this).parents('tr:not(.form-field)').remove();
	        });
	      }
	      if($('.payment_status_conditions_table #conditions tr').length < 2){
	        $('#match_rules_wrap').hide();
	      }
	      return false;
	    });
	    $('#mainform').submit(function(){
	      $('#mainform .form-invalid').removeClass('form-invalid');
	      if($('#rule_name').val() == ''){
	        $('#rule_name').parents('.form-field').addClass('form-invalid');
	      }
	      $('.payment_status_conditions_table tbody tr').each(function(){
	        var tr = $(this);
	        tr.find('select:not(:disabled), input:not(:disabled)').each(function(){
	        if( $().select2 ){
	          if($(this).val() == '' && $(this).parents('.select2-container').length == 0){
	            tr.addClass('form-invalid');
	          }
	        }else{
	          if($(this).val() == '' && $(this).parents('.chosen-container').length == 0){
	            tr.addClass('form-invalid');
	          }          
	        }
	        });
	      });
	      if($('#mainform .form-invalid').length > 0 ) return false;
	    });
	    $('#insert_condition_row').click(function () {
	      
	      var data_index =  0;
	      var new_rows = $('table.payment_status_conditions_table tbody#conditions tr');      
	      new_rows.each(function(){
	        var index = $(this).attr('data-index');
	        index = parseInt(index);
	        if(index > data_index) data_index = index;
	      });
	      data_index++;
	      //data_index = 'new_'+data_index;

	      var $new_row = tmp_row;

	      var new_row_str = $new_row.prop('outerHTML');
	      var old_data_index = $new_row.attr('data-index');

	      var $order_variable = $new_row.find('.order_var select');
	      var order_variable_str = $( $order_variable.prop('outerHTML') );
	          order_variable_str.attr('name', 'rules[conditions]['+data_index+'][order_var]');
	          order_variable_str = order_variable_str.prop('outerHTML');

	      var v_html = order_variable_str.replace('selected="selected"', '');

	      new_row_str = new_row_str.replace('data-index="'+old_data_index+'"', 'class="new_row" data-index="'+data_index+'"');


	      new_row_str = new_row_str.replace('value="'+old_data_index+'"', 'value="'+data_index+'"');

	      var order_var   = 'order_number';
	      console.log(order_var+'_inp_first');
	      var $first_col  = $(default_f[order_var+'_inp_first']); //$('#default_f .'+order_var+'_inp_first');
	      var $second_col = $(default_f[order_var+'_inp_second']); //$('#default_f .'+order_var+'_inp_second');
	      var f_html = '';
	      var s_html = '';
	      
	      $first_col.each(function(){
	        var str = $(this).prop('outerHTML');
	        f_html += str.replace('__replace__', data_index);
	      });
	      $second_col.each(function(){
	        var str = $(this).prop('outerHTML');
	        s_html += str.replace('__replace__', data_index);
	      });

	      $('table.payment_status_conditions_table tbody#conditions').append(new_row_str);
	      $('table.payment_status_conditions_table tbody#conditions .new_row .order_var').html(v_html);
	      $('table.payment_status_conditions_table tbody#conditions .new_row .order_rule').html(f_html).find('select').show();

	      $('table.payment_status_conditions_table tbody#conditions .new_row .order_value').html(s_html);

	      ruleConditions($('table.payment_status_conditions_table tbody#conditions tr').last().find('td.order_var select'));
	      $('tr.new_row select:visible').not('.products_inp_second').select2();
	      select2_products_inp_second ();
	      
	      $('tr.new_row .order_rule select').change();
	      $('tr.new_row input.ui_date').datepicker();
	      $('tr.new_row').removeAttr('class');

	      if($('.payment_status_conditions_table #conditions tr').length > 1){
	        $('#match_rules_wrap').show();
	      }
	      return false;
	    });

	    $('body').on('change', '.order_var select', function(){
	      var el = $(this);
	      var order_var    = el.val();
	      var data_index   = el.parents('tr:not(.form-field)').attr('data-index');
	      var $first_col   = $(default_f[order_var+'_inp_first']); //$('#default_f .'+order_var+'_inp_first');
	      var $second_col  = $(default_f[order_var+'_inp_second']); //$('#default_f .'+order_var+'_inp_second');
	      var f_html = '';
	      var s_html = '';
	      $first_col.each(function(){
	        var str = $(this).prop('outerHTML');
	        f_html += str.replace('__replace__', data_index);
	      });
	      $second_col.each(function(){
	        var str = $(this).prop('outerHTML');
	        s_html += str.replace('__replace__', data_index);
	      });
	      el.parents('tr:not(.form-field)').addClass('new_row');
	      el.parents('tr:not(.form-field)').find('.order_rule').html(f_html).find('select').show();
	      el.parents('tr:not(.form-field)').find('.order_value').html(s_html);
	      
	      ruleConditions($(this));
	      $('tr.new_row select:visible').not('.products_inp_second').select2();
	      select2_products_inp_second ();
	      
	      $('tr.new_row input.ui_date').datepicker();
	      $('tr.new_row .order_rule select').change();
	      $('tr.new_row').removeAttr('class');
	    });


	    $('body').on('change', '.order_rule select', function(){
	      var el  = $(this).parents('tr:not(.form-field)').find('.order_var').find('select');
	        $(this).parents('tr:not(.form-field)').find('.order_value select.enhanced').select2('destroy'); 
	        $(this).parents('tr:not(.form-field)').find(".order_value select.enhanced").removeClass("enhanced").css('display', 'inline').data('select2', null);
	        ruleConditions(el);
	        $(this).parents('tr:not(.form-field)').find('.order_value select:visible').not('.products_inp_second').addClass('enhanced').select2().change();
	        select2_products_inp_second ();
	    });
		$('table.payment_status_conditions_table tbody#conditions tr').each(function(){
	      ruleConditions($(this).find('td.order_var select'));  
	    });
	      $('table.payment_status_conditions_table select:visible').not('.products_inp_second').select2();
	      select2_products_inp_second ();
	    $('table.payment_status_conditions_table .order_rule select').change();
	}

	function ruleConditions(el){
	    var o_var  = el.val();
	    var o_rule = el.parents('tr:not(.form-field)').find('.order_rule').find('select').val();
	    var o_val = el.parents('tr:not(.form-field)').find('.order_value');
	    if(o_var == 'order_status'){
	      if(o_rule == 'is' || o_rule == 'is not'){
	        console.log(o_rule);
	        o_val.find('input').hide().attr('disabled', 'disabled');
	        o_val.find('select').show().removeAttr('disabled');
	      }else{
	        o_val.find('input').show().removeAttr('disabled');
	        o_val.find('select').hide().attr('disabled', 'disabled');
	      }
	    }else if( o_var == 'order_date'){
	      if(o_rule == 'is' || o_rule == 'is not' || o_rule == 'is after' || o_rule == 'is before'){
	        o_val.find('.__chosen_select, input[type="number"]').hide().attr('disabled', 'disabled');
	        o_val.find('input.ui_date').show().removeAttr('disabled');
	      }else{
	        o_val.find('.__chosen_select, input[type="number"]').css('width', '48%').show().removeAttr('disabled');
	        o_val.find('input.ui_date').hide().attr('disabled', 'disabled');
	      }
	    }
	  }
	function select2_products_inp_second () {
		$( document.body ).trigger('wc-enhanced-select-init');
			
	}
});