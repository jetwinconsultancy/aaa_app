<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/fileinput.css" />
<script src="<?= base_url() ?>application/js/fileinput.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<script src="<?= base_url() ?>plugin/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js"></script>

<style>
	.file-preview button span {
		display:none;
	}
</style>

<section class="body">
	<div class="inner-wrapper">
		<section role="main" class="content_section" style="margin-left:0;">
			<div class="box" style="margin-bottom: 30px; margin-top: 30px;">
				<div class="box-content">
				    <div class="row">
				        <div class="col-lg-12">
				        	<!-- <?php echo form_open_multipart('', array('id'=> 'apply_claim', 'ectype' => "multipart/form-data"));?> -->
				            <!-- <form id="apply_claim" enctype="multipart/form-data" method="POST" accept-charset="utf-8"> -->
				          	<!-- <?php echo form_open_multipart('mc_claim/submit_claim', array('id' => 'apply_claim', 'enctype' => "multipart/form-data")); ?> -->

				          	<?php echo form_open_multipart('', array('id' => 'apply_claim', 'enctype' => "multipart/form-data")); ?>
				          	
				          	<!-- <form id="apply_claim" enctype="multipart/form-data" method="POST" accept-charset="utf-8"> -->
				            	<input id="mc_id" name="mc_id" class="form-control" size="16" type="hidden" value="<?=isset($claim_data[0]->mc_id)?$claim_data[0]->mc_id:$mc_id?>">
				            	<input name="claim_id" class="form-control" size="16" type="hidden" value="<?=isset($claim_data[0]->id)?$claim_data[0]->id:''?>">
				            	<input name="claim_no" class="form-control" size="16" type="hidden" value="<?=isset($claim_data[0]->claim_no)?$claim_data[0]->claim_no:''?>">
				            	<input name="claim_status" class="form-control" size="16" type="hidden" value="<?=isset($claim_data[0]->status)?$claim_data[0]->status:''?>">

				            	<div class="form-group row">
								    <label for="mc_start_date" class="col-sm-2 col-form-label">Invoice no. : </label>
								    <div class="col-sm-5">
								    	<input type="text" class="form-control" name="claim_invoice_no" value="<?=isset($claim_data[0]->invoice_no)?$claim_data[0]->invoice_no:''?>">
								    	<div class="input-group style="width: 30%">
                                        	<label style="color:red;" id="err_claim_invoice_no"></label>
                                        </div>
								    </div>
								</div>
								<div class="form-group row">
								    <label for="mc_end_date" class="col-sm-2 col-form-label">Amount: </label>
								    <div class="col-sm-5">
								    	<input type="text" class="form-control input_num_val" name="claim_amount" placeholder="0.00" value="<?=isset($claim_data[0]->amount)?$claim_data[0]->amount:''?>" style="text-align: right;">
								    	<div class="input-group style="width: 30%">
                                        	<label style="color:red;" id="err_claim_amount"></label>
                                        </div>
								    </div>
								</div>
								<div class="form-group row">
								    <label for="mc_reason" class="col-sm-2 col-form-label">Receipt: </label>
								    <div class="col-sm-5">
								    	<div class="input-group" style="width: 100%;" >
                                            <div class="file-loading">
                                                <input type="file" id="receipt" class="file" name="receipt_img" data-min-file-count="0" accept="image/*">
                                            </div>
                                        </div>
								    </div>
								</div>
								<div class="form-group row">
									<div class="col-sm-7">
								    	<?php echo '<a href="'.base_url().'mc_claim" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

								    		if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null))
								    		{
								    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Apply</button>';
								    		}
								    		else
								    		{
								    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
								    		}
								    	?>
			                      		
								    </div>
			                    </div>
						    <!-- </form> -->
						    <?php echo form_close(); ?>
					    </div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	    	<div class="modal-header">
	      		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		  	    </button>
	      		<h4 class="modal-title">Success apply</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>Your claim has been successfully applied.</p>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default cancelBtn" data-dismiss="modal">Close</button>
	      	</div>
    	</div>
  	</div>
</div> -->
<!-- <script src="<?= base_url() ?>application/views/modal/modal_template.php"></script> -->

<script type="text/javascript" src="<?php echo base_url() . 'application/js/number_input_format.js' ?>"></script>
<script type="text/javascript">
	var initialPreviewArray 	  = []; 
	var initialPreviewConfigArray = [];
	var base_url 				  = '<?php echo base_url() ?>';
	var files 					  = '<?php echo isset($claim_data[0]->receipt_img)?$claim_data[0]->receipt_img:''; ?>';

  //   $('.form_datetime').datetimepicker({
  //       weekStart: 1,
  //       todayBtn:  1,
		// autoclose: 1,
		// todayHighlight: 1,
		// startView: 2,
		// forceParse: 0,
  //       showMeridian: 1,
  //       format: 'yyyy-mm-dd hh:ii'    
  //   });

  	if(files != '')
	{
		var url = base_url + "uploads/claim/";

		initialPreviewArray.push( url + files );
        initialPreviewConfigArray.push({
	        caption: files,
	        url: "/payroll/mc_claim/delete_receipt/" + '<?php echo isset($claim_data[0]->id)?$claim_data[0]->id:''; ?>' ,
	        width: "120px",
	        key: 1
	  	});
	}
 	
  	$(document).on('submit', '#apply_claim', function (e) {
    // $('form#apply_claim').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('mc_claim/submit_claim'); ?>",
	        // data: form.serialize()+'&userfile='+new FormData(document.getElementById("userfile")),
	        data: form.serialize(),
	        dataType: "json",
	        success: function(error){ // because dataType is json so no need to convert to object.

	        	if(error.result){
	        		// console.log(error);
	        		$('#err_claim_invoice_no').text(error.claim_invoice_no);
		        	$('#err_claim_amount').text(error.claim_amount);
	        	}
	        	else
	        	{
	        		$('#receipt').fileinput('upload');
	        		window.location.href = base_url + 'mc_claim';
	        	}
	        	
	            // $('#exampleModal').modal('show');
	        },
	   });
	});

	$("#receipt").fileinput({
	    theme: 'fa',
	    uploadUrl: base_url + 'mc_claim/uploadFile', // you must set a valid URL here else you will get an error
	    uploadAsync: false,
	    browseClass: "btn btn_purple",
	    fileType: "any",
	    required: false,
	    showCaption: false,
	    showUpload: false,
	    showRemove: false,
	    //showClose: false,
	    autoReplace: true,
	    overwriteInitial: true,
	    maxFileCount: 1,
	    fileActionSettings: {
	                    showRemove: false,
	                    showUpload: false,
	                    showZoom: true,
	                    showDrag: false
	                },
	    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
	    initialPreviewShowDelete: false,
	    initialPreviewAsData: true,
	    initialPreviewDownloadUrl: base_url + 'uploads/applicant_resume/{filename}',
	    initialPreview: initialPreviewArray,
	    initialPreviewConfig: initialPreviewConfigArray,
	    allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "ico", "Icon"],
	    //deleteUrl: "/dot/personprofile/deleteFile",
	    /*maxFileSize: 20000048,
	    maxImageWidth: 1000,
	    maxImageHeight: 1500,
	    resizePreference: 'height',
	    resizeImage: true,*/
	    purifyHtml: true // this by default purifies HTML data for preview
	    /*uploadExtraData: { 
	        officer_id: $('input[name="officer_id"]').val() 
	    }*/
	    /*width:auto;height:auto;max-width:100%;max-height:100%;*/

	}).on('filesorted', function(e, params) {
	    console.log('File sorted params', params);
	}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
	    $("#loadingmessage").hide();
	    // $('#exampleModal').modal('show');
	    alert("Successfully updated!");
	    window.location.href = base_url + "mc_claim";
	    toastr.success('Information Updated', 'Updated');
	    // console.log(data);

	    // $('#exampleModal').modal('show');

	}).on('fileuploaderror', function(event, data, msg) {
	    $("#loadingmessage").hide();
	    // $('#exampleModal').modal('show');
	    alert("Successfully updated!");
	    window.location.href = base_url + "mc_claim";
	    toastr.success('Information Updated', 'Updated');
	    // console.log("Error");
	    // alert(msg);
	});

</script>  