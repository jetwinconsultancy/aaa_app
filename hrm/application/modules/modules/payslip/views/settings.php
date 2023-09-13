<section class="panel" style="margin-top: 30px;">
<div class="panel-body">
	<div class="box-content">
	    <div class="row">
	        <div class="col-lg-12">
	            <form id="payslip_setting" method="POST"> 
		            <div class="row">
		                <div class="col-md-12">
		                    <div class="col-md-12">

		                    	<input type="hidden" name="payslip_setting_id" value="<?= isset($payslip_settings->id)?$payslip_settings->id:'' ?>">

		                    	<div class="form-group row">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>CDAC/MENDAKI/SINDA :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 20%;">
	                                        	<input type="number" class="form-control" id="payslip_setting_cdac" name="payslip_setting_cdac" value="<?= isset($payslip_settings->cdac)?$payslip_settings->cdac:'' ?>" style="width: 400px;" required/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group row">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Skill Development Levy :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 20%;">
	                                        	<input type="number" class="form-control" id="payslip_setting_sdl" name="payslip_setting_sdl" value="<?= isset($payslip_settings->sdl)?$payslip_settings->sdl:'' ?>" step="any" style="width: 400px;" required/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group row">
									<div class="col-sm-7">
								    	<?php echo '<a href="'.base_url().'payslip/index_admin" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

								    		if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null))
								    		{
								    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Submit</button>';
								    		}
								    		else
								    		{
								    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
								    		}
								    	?>
								    </div>
			                    </div>
	                 		</div>
	                 	</div>
	                 </div>
	             </form>
	         </div>
	     </div>
	 </div>
</div>

<script type="text/javascript">

    $('form#payslip_setting').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('payslip/submit_settings'); ?>",
	        data: form.serialize(),
	        dataType: "html",
	        success: function(data){
	        	console.log(data);
	        	// var return_data = JSON.parse(data);

	         //    $('input[name=mc_id]').val(return_data.id);
	         //    $('input[name=mc_no]').val(return_data.mc_no);

	         //    $('#exampleModal').modal('show');
	         //    // console.log(data);
	            // $('.modal-body').empty();
	            // $('.modal-body').prepend(data);
	            // $('#exampleModal').modal('show');
	        }
	        // error: function() { alert("Error posting feed."); }
	   });
	});

	function delete_bonus(element){
		var tr = $(element).parent().parent();

		tr.remove();
	}

    $(".payslip_employee_aws").bootstrapSwitch({
        state: true,
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
        state: true,
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
