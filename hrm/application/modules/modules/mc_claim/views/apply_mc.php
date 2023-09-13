<link rel="stylesheet" href="<?= base_url() ?>plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

<section class="body">
	<div class="inner-wrapper">
		<section role="main" class="content_section" style="margin-left:0;">
			<div class="box" style="margin-bottom: 30px; margin-top: 30px;">
				<div class="box-content">
				    <div class="row">
				        <div class="col-lg-12">
				            <form id="apply_mc" method="POST">
				            	<input name="mc_id" class="form-control" size="16" type="hidden" value="<?=isset($mc_data[0]->id)?$mc_data[0]->id:''?>">
				            	<input name="mc_no" class="form-control" size="16" type="hidden" value="<?=isset($mc_data[0]->id)?$mc_data[0]->mc_no:''?>">
				            	<input name="mc_employee_id" class="form-control" size="16" type="hidden" value="<?=isset($mc_data[0]->id)?$mc_data[0]->employee_id: $employee_id ?>">
				            	<input name="mc_status" class="form-control" size="16" type="hidden" value="<?=isset($mc_data[0]->mc_status)?$mc_data[0]->mc_status:''?>">

				            	<div class="form-group row">
								    <label for="mc_start_date" class="col-sm-2 col-form-label">Start Date: </label>
								    <div class="col-sm-10">
								      	<div class="input-group date form_datetime col-md-5"  data-date-format="dd MM yyyy - hh:ii:ss" data-link-field="dtp_input1">
						                    <input id="mc_start_date" name="mc_start_date" class="form-control" size="16" type="text" value="<?=isset($mc_data[0]->start_date)?$mc_data[0]->start_date:''?>">
						                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
											<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
						                </div>
						                <div class="input-group style="width: 30%">
                                        	<label style="color:red;" id="err_mc_start_date"></label>
                                        </div>
								    </div>
								</div>
								<div class="form-group row">
								    <label for="mc_end_date" class="col-sm-2 col-form-label">End Date: </label>
								    <div class="col-sm-10">
								      	<div class="input-group date form_datetime col-md-5"  data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
						                    <input name="mc_end_date" class="form-control" size="16" type="text" value="<?=isset($mc_data[0]->end_date)?$mc_data[0]->end_date:''?>">
						                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
											<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
						                </div>
						                <div class="input-group style="width: 30%">
                                        	<label style="color:red;" id="err_mc_end_date"></label>
                                        </div>
								    </div>
								</div>
								<div class="form-group row">
								    <label for="mc_reason" class="col-sm-2 col-form-label">Reason: </label>
								    <div class="col-sm-5">
								    	<!-- <div class="input-group col-sm-7"> -->
								    	<textarea class="form-control" rows="5" id="mc_claim_reason" name="mc_reason" placeholder="Eg. Sick"><?php echo isset($mc_data[0]->id)?$mc_data[0]->reason:''; ?></textarea>
									      	<!-- <?php 
				                                echo '<textarea class="form-control" rows="5" id="mc_claim_reason" name="mc_reason">'.$applicant[0]->about.'</textarea>';
				                            ?> -->
								    	<!-- </div> -->
								    	<div class="input-group style="width: 30%">
                                        	<label style="color:red;" id="err_mc_reason"></label>
                                        </div>
								    </div>
								</div>
								<div class="form-group row">
									<div class="col-sm-7">
								    	<?php echo '<a href="'.base_url().'mc_claim" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

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
						    </form>
					    </div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>


<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	    	<div class="modal-header">
	      		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		  	    </button>
		  	    <?php 
		  	    	if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<h4 class="modal-title">Success apply</h4>';
		  	    	} else {
		  	    		echo '<h4 class="modal-title">Success update</h4>';
		  	    	}
		  	    ?>
	      		
	      	</div>
	      	<div class="modal-body">
	      		<?php 
	      			if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<p>Your MC has been successfully applied.</p>';
		  	    	} else {
		  	    		echo '<p>MC has been successfully updated.</p>';
		  	    	}
	      		?>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default cancelBtn" data-dismiss="modal">Close</button>
	      	</div>
    	</div>
  	</div>
</div> -->
<!-- <script src="<?= base_url() ?>application/views/modal/modal_template.php"></script> -->

<script type="text/javascript">
	// $(document).ready( function () {
	//    // $('.modal_section').append($('#exampleModal'));
	// } );

	var base_url = '<?php echo base_url(); ?>';
	console.log(base_url);

    $('.form_datetime').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1,
        format: 'yyyy-mm-dd hh:ii'    
    });

    $('form#apply_mc').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('mc_claim/submit_mc'); ?>",
	        data: form.serialize(),
	        dataType: "html",
	        success: function(data){
	        	// console.log(data);
	        	var error = JSON.parse(data);
	        	// console.log(error);

	        	if(error.result){
	        		$('#err_mc_start_date').text(error.mc_start_date);
		        	$('#err_mc_end_date').text(error.mc_end_date);
		        	$('#err_mc_reason').text(error.mc_reason);
	        	}
	        	else
	        	{
	        		// $('#exampleModal').modal('show');
	        		window.location.href = base_url + 'mc_claim';
	        	}
	            // $('input[name=mc_id]').val(return_data.id);
	            // $('input[name=mc_no]').val(return_data.mc_no);

	            // $('#exampleModal').modal('show');

	            // window.location.href = base_url + 'interview';
	            // console.log(data);
	            // $('.modal-body').empty();
	            // $('.modal-body').prepend(data);
	            // $('#exampleModal').modal('show');
	        },
	        // error: function() { alert("Error posting feed."); }
	   });
	});


</script>  