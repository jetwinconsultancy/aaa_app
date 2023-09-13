<link rel="stylesheet" href="<?= base_url() ?>plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<link href="<?= base_url() ?>node_modules/select2/dist/css/select2.min.css ?>" rel="stylesheet" />
<script src="<?= base_url() ?>node_modules/select2/dist/js/select2.min.js"></script>

<section class="body">
<div class="inner-wrapper">
<section role="main" class="content_section" style="margin-left:0;">
<div class="box" style="margin-bottom: 5px; margin-top: 5px;">
	<div class="box-content">
	    <div class="row">
	        <div class="col-lg-12">
	            <form id="interview" method="POST" autocomplete="off"> 
		            <div class="row">
		                <div class="col-md-12">
		                    <div class="col-md-12">
		                    	<input type="hidden" name="interview_id" value="<?=isset($interview_detail['id']) ? $interview_detail['id'] : '' ?>">
		                    	<input type="hidden" name="applicant_id" value="<?=isset($interview_detail['applicant_id']) ? $interview_detail['applicant_id'] : '' ?>">

	                            <div class="form-group">
			                    	<div style="width: 100%;">
				                    	<div style="width: 25%;float:left;margin-right: 20px;">
				                            <label>Interview No :</label>
				                        </div>
				                    	<div style="width: 30%;float: left;">
				                    		<b>
					                    		<span class="interview_no"></span>
					                    	</b>
				                    		<input type="hidden" class="form-control" id="interview_no" name="interview_no" >
				                        </div>
				                    </div>
			                    </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Firm :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 20%;">
	                                        	<?php
    												echo form_dropdown('interview_company_name', $firm_list, isset($interview_detail['firm'])?$interview_detail['firm']: '', 'class="firm-select form-control" style="width:150%;"');
    											?>
	                                        	<!-- <input type="text" class="form-control" id="staff_nric_finno" name="staff_nric_finno" value="<?=isset($staff[0]->nric_fin_no)?$staff[0]->nric_fin_no:'' ?>" style="width: 400px;" required/> -->
	                                        </div>
	                                        <div class="input-group" style="width: 30%">
	                                        	<label style="color:red;" id="err_interview_company_name"></label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
		                    	<div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Name :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="text" class="form-control" id="applicant_name" name="applicant_name" value="<?=isset($interview_detail['applicant_name'])?$interview_detail['applicant_name']: '' ?>" style="width: 400px;" onkeyup="this.value = this.value.toUpperCase();"/>
	                                        </div>
	                                        <div class="input-group" style="width: 30%">
	                                        	<label style="color:red;" id="err_applicant_name"></label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Email :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                            <input type="email" class="form-control" id="applicant_email" name="applicant_email" style="width: 400px;" value="<?=isset($interview_detail['applicant_email'])?$interview_detail['applicant_email']: '' ?>" placeholder="eg. examples@gmail.com"/>
	                                        </div>
	                                        <div class="input-group" style="width: 30%">
	                                        	<label style="color:red;" id="err_applicant_email"></label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
			                    	<div style="width: 100%;">
				                    	<div style="width: 25%;float:left;margin-right: 20px;">
				                            <label>Time and date of interview :</label>
				                        </div>
				                        <div style="width: 30%;float: left;">
							                <div class="input-group date form_datetime col-md-5" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
							                	<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
							                	<input autocomplete="off" name="interview_datetime" class="form-control" type="text" value="<?=isset($interview_detail['interview_time'])?$interview_detail['interview_time']: '' ?>" style="width:250px;">
							                </div>
							                <div class="input-group" style="width: 80%">
	                                        	<label style="color:red;" id="err_interview_datetime"></label>
	                                        </div>
				                        </div>
				                    </div>
			                    </div>
			                    <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Venue :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="text" class="form-control" id="venue" name="venue" value="<?=isset($interview_detail['venue'])?$interview_detail['venue']: '160 Robinson Rd, #2603 SBF Center, Singapore 068914' ?>" style="width: 400px;"/>
	                                        </div>
	                                        <div class="input-group" style="width: 30%">
	                                        	<label style="color:red;" id="err_venue"></label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
			                    	<div style="width: 100%;">
				                    	<div style="width: 25%;float:left;margin-right: 20px;">
				                            <label>Interview number valid until :</label>
				                        </div>
				                    	<div style="width: 30%;float: left;">
							                <div class="input-group date form_datetime col-md-5" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
							                	<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
							                    <input autocomplete="off" name="interview_valid_datetime" class="form-control" size="16" type="text" value="<?=isset($interview_detail['interview_num_valid_until'])?$interview_detail['interview_num_valid_until']: '' ?>" style="width:250px;">
							                </div>
							                <div class="input-group" style="width: 80%">
	                                        	<label style="color:red;" id="err_interview_valid_datetime"></label>
	                                        </div>
				                        </div>
				                    </div>
			                    </div>
			                    <div class="form-group">
			                    	<div style="width: 100%;">
				                    	<div style="width: 25%;float:left;margin-right: 20px;">
				                    		<label></label>
				                    	</div>
				                    	<div style="width: 30%;float: left;">
				                    		<label></label>
				                    	</div>
				                    	<!-- <hr> -->
				                    </div>
			                    </div>
			                    <div class="form-group">
			                        <div style="width: 100%;float:left;margin-bottom:5px;">
			                        	<?php 
			                        		echo '<a href="'.base_url().'interview" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

			                        		if(isset($interview_detail)){
			                        			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
			                        		}else{
			                        			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Create</button>';
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
</section>
</div>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript">
	 $('.firm-select').select2();
	 var base_url = '<?php echo base_url(); ?>';

	var interview_no = '<?php echo isset($interview_detail['interview_no'])?$interview_detail['interview_no']: ''?>';	// retrieve if there is any interview no.

	// if interview no is empty, generate new interview no.
	if(interview_no == '')
	{
		interview_no = Math.random().toString(36).replace('0.', '');
	}

	$('.interview_no').text(interview_no);
	$('input[name=interview_no]').val(interview_no);

	// $(".firm-select").chosen({no_results_text: "Oops, nothing found!"});

	$(document).ready( function () {
	    $('.form_datetime').datetimepicker({
	        weekStart: 1,
	        todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
	        showMeridian: 1
	    });

	    // $('.form_datetime').datetimepicker({
	    //     format: "dd MM yyyy - hh:ii"
	    // });
	});

    $('form#interview').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();

	    $('#loadingmessage').show();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('interview/create_applicant'); ?>",
	        data: form.serialize(),
	        dataType: "html",
	        success: function(data){
	        	$('#loadingmessage').hide();

	        	var error = JSON.parse(data);

	        	if(error.result)
	        	{
	        		$('#err_interview_company_name').text(error.interview_company_name);
		        	$('#err_applicant_email').text(error.applicant_email);
		        	$('#err_applicant_name').text(error.applicant_name);
		        	$('#err_interview_datetime').text(error.interview_datetime);
		        	$('#err_interview_datetime').text(error.interview_datetime);
		        	$('#err_interview_datetime').text(error.interview_datetime);
	        	}
	        	else
	        	{
	        		window.location.href = base_url + 'interview';
	        	}

	            // $('#feed-container').prepend(data);
	            // $('input[name=interview_code]').val(data);
	            // $('input[name=interview_no]').val(data);
	            // console.log(data);
	        },
	        // error: function() { alert("Error posting feed."); }
	   	});

	});
</script>  