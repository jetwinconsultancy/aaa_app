<tr class="bonus_tr">
    <input type="hidden" class="payslip_id" name=<?php echo 'payslip_id['.$count.']' ?> value=<?php echo isset($bonus_details['id'])?$bonus_details['id']:0 ?> />
    <td>
    	<?php
			echo form_dropdown('payslip_employee_name['.$count.']', $employee_name, isset($bonus_details['employee_id'])?$bonus_details['employee_id']:'', 'class="employee-select" style="width:100%;"');
		?>
    </td>
    <td>
        <input class="form-control" type="number" name=<?php echo 'payslip_employee_bonus['.$count.']' ?> value=<?php echo isset($bonus_details['bonus'])?$bonus_details['bonus']:'0.00' ?> />
    </td>
    <td>
        <input class="form-control" type="number" name=<?php echo 'payslip_employee_commission['.$count.']' ?> value=<?php echo isset($bonus_details['commission'])?$bonus_details['commission']:'0.00' ?> />
    </td>
    <td>
        <input class="form-control" type="number" name=<?php echo 'payslip_employee_other_allowance['.$count.']' ?> value=<?php echo isset($bonus_details['other_allowance'])?$bonus_details['other_allowance']:'0.00' ?> />
    </td>
    <td>
    	<span class="glyphicon glyphicon-trash" onclick="delete_bonus(this)" style="cursor: pointer;"></span>
    </td>
</tr>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";

	function delete_bonus(element){
		var tr = $(element).parent().parent();
        var payslip_id = tr.find('.payslip_id').val();

        if(payslip_id > 0){
            if (confirm("Remove bonus from this employee?")) {
                $.post(base_url + "payslip/remove_bonus", { 'payslip_id': payslip_id }, function(result, status){
                    if(result){
                        tr.remove();
                    }
                });
            }
        }else{
            tr.remove();
        }
	}

    $(".payslip_employee_aws").bootstrapSwitch({
        state: <?php echo isset($bonus_details['aws'])?$bonus_details['aws']:0 ?>,
        size: 'normal',
        onColor: 'purple',
        onText: 'Yes',
        offText: 'No',
        // Text of the center handle of the switch
        labelText: '&nbsp',
        // Width of the left and right sides in pixels
        handleWidth: '75px',
        // Width of the center handle in pixels
        labelWidth: 'auto',
        baseClass: 'bootstrap-switch',
        wrapperClass: 'wrapper'
    });

    $(".payslip_employee_aws").on('switchChange.bootstrapSwitch', function(event, state) {
        var payslip_employee_aws = $(this).parent().parent().parent().find(".hidden_payslip_employee_aws");
        
        if(state == true)
        {
            payslip_employee_aws.val(1);
        }
        else
        {
            payslip_employee_aws.val(0);
        }
    })

    $(".payslip_employee_health_incentive").bootstrapSwitch({
        state: <?php echo isset($bonus_details['health_incentive'])?$bonus_details['health_incentive']:0 ?>,
        size: 'normal',
        onColor: 'purple',
        onText: 'Yes',
        offText: 'No',
        // Text of the center handle of the switch
        labelText: '&nbsp',
        // Width of the left and right sides in pixels
        handleWidth: '75px',
        // Width of the center handle in pixels
        labelWidth: 'auto',
        baseClass: 'bootstrap-switch',
        wrapperClass: 'wrapper'
    });

    $(".payslip_employee_health_incentive").on('switchChange.bootstrapSwitch', function(event, state) {
        var payslip_employee_health_incentive = $(this).parent().parent().parent().find(".hidden_payslip_employee_health_incentive");
        
        if(state == true)
        {
            payslip_employee_health_incentive.val(1);
        }
        else
        {
            payslip_employee_health_incentive.val(0);
        }
    })
</script>