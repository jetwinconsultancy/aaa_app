<link rel="stylesheet" href="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/fileinput.css" />
<script src="<?= base_url() ?>application/js/fileinput.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<style>
	.file-preview button span {
		display:none;
	}
</style>

<script type="text/javascript" src="<?= base_url()?>application/js/custom/time_format.js"></script>

<section class="panel" style="margin-top: 30px;">
<div class="panel-body">
	<div class="box-content">
	    <div class="row">
	        <div class="col-lg-12">
	            <form id="apply_reimbursement" enctype="multipart/form-data" method="post" accept-charset="utf-8"> 
	            	<input type="hidden" name="employee_id" value="<?=isset($employee_id)?$employee_id:''?>">

	            	<table class="table" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Date</th>
								<th class="text-left">Client</th>
								<th class="text-left">Description</th>
								<th class="text-center">Firm</th>
								<th class="text-center">Amount</th>
								<th class="text-center">Receipt</th>
								<th class="text-center">Invoice no.</th>
								<th><a onclick="add_reimbursement()"><span class="glyphicon glyphicon-plus-sign"></span></th>
							</tr>
						</thead>
						<tbody id="reimbursement_input"></tbody>
					</table>

					<!-- <div class="form-group row">
					    <label for="reimbursement_date" class="col-sm-2 col-form-label">Date : </label>
					    <div class="col-sm-5">
					      	<div class='input-group date' class='date'>
							    <input type='text' class="form-control" name="reimbursement_date" />
							    <span class="input-group-addon">
							        <span class="glyphicon glyphicon-th"></span>
							    </span>
							</div>
					    </div>
					</div>
					<div class="form-group row">
					    <label for="reimbursement_client_name" class="col-sm-2 col-form-label">Client : </label>
					    <div class="col-sm-5">
					      	<input type='text' class="form-control" name="reimbursement_client_name" value="<?=isset($reimbursement->client_name)?$reimbursement->client_name:''; ?>" />
					    </div>
					</div>
					<div class="form-group row">
					    <label for="reimbursement_description" class="col-sm-2 col-form-label">Description : </label>
					    <div class="col-sm-5">
					      	<input type='text' class="form-control" name="reimbursement_description" value="<?=isset($reimbursement->description)?$reimbursement->description:''; ?>" />
					    </div>
					</div>
					<div class="form-group row">
					    <label for="reimbursement_firm" class="col-sm-2 col-form-label">Firm : </label>
					    <div class="col-sm-5">
					      	<input type='text' class="form-control" name="reimbursement_firm" value="<?=isset($reimbursement->firm_name)?$reimbursement->firm_name:''; ?>" />
					    </div>
					</div>
					<div class="form-group row">
					    <label for="reimbursement_amount" class="col-sm-2 col-form-label">Amount : </label>
					    <div class="col-sm-5">
					    	<input type='number' class="form-control" name="reimbursement_amount" value="<?=isset($reimbursement->amount)?$reimbursement->amount:''; ?>" />
					    </div>
					</div>
					<div class="form-group row">
					    <label for="reimbursement_receipt" class="col-sm-2 col-form-label">Receipt : </label>
					    <div class="col-sm-5">
					    	<div class="input-group" style="width: 100%;" >
                                <div class="file-loading">
                                    <input type="file" id="reimbursement_receipt" class="file" name="receipt_img" data-min-file-count="0" accept="image/*">
                                </div>
                            </div>
					    </div>
					</div>
					<div class="form-group row">
					    <label for="reimbursement_invoice_no" class="col-sm-2 col-form-label">Invoice no. : </label>
					    <div class="col-sm-5">
					      	<input type='text' class="form-control" name="reimbursement_invoice_no" value="<?=isset($reimbursement->invoice_no)?$reimbursement->invoice_no:''; ?>" />
					    </div>
					</div> -->
					<div class="form-group row">
						<div class="col-sm-7">
					    	<?php echo '<a href="'.base_url().'reimbursement/index" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

					    		echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
					    	?>
					    </div>
                    </div>
	            </form>
	        </div>
	    </div>
	</div>
</div>
</section>

<div class="modal fade" id="offer_letter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<form id="offer_letter" method="POST">
		    	<div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			    	</button>
		          	<h4 class="modal-title">Upload Photo</h4>
		        </div>
		      <div class="modal-body"></div>
		      <div class="modal-footer">
		      	<button type="submit" class="btn btn_purple">Upload</button>
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  		</form>
	    </div>
	  </div>
	</div>

<script type="text/javascript">
var base_url = '<?php echo base_url(); ?>';
var count_reimbursement = 0;
// var reimbursement_id = 0;

var initialPreviewArray 	  = []; 
var initialPreviewConfigArray = [];
var base_url 				  = '<?php echo base_url() ?>';
var files 					  = '<?php echo isset($reimbursement->receipt_img_filename)?$reimbursement->receipt_img_filename:''; ?>';

var initial_date = '<?php echo isset($reimbursement->date)?$reimbursement->date: ''; ?>';

if(files != '')
{
	var url = base_url + "uploads/reimbursement/";

	initialPreviewArray.push( url + files );
    initialPreviewConfigArray.push({
        caption: files,
        url: "/payroll/reimbursement/delete_receipt/" + '<?php echo isset($reimbursement->id)?$reimbursement->id:''; ?>' ,
        width: "120px",
        key: 0
  	});
}

add_reimbursement();

$('.date').datetimepicker({
    format: 'DD/MM/YYYY',
    date: new Date(initial_date),
    useCurrent: false
});

function add_reimbursement(){
	$.ajax({
	  	type: "POST",
	  	url: base_url + 'reimbursement/apply_reimbursement_tr_partial',
	  	data: { 'count': count_reimbursement },
	  	// async:false,
	  	success: function(data){

			$('#reimbursement_input').prepend(data);
			count_reimbursement++;
	  	}
	});
}

function delete_imbursement(element){
	var tr = $(element).parent().parent();
	// var tbody = tr.parent();

	if(confirm("Confirm to delete this claim?")){
		// console.log(tbody);

		tr.remove();
		// count_reimbursement--;
	}
}

$('form#apply_reimbursement').submit(function(e) {
    var form = $(this);

    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "<?php echo site_url('reimbursement/submit_reimbursement'); ?>",
        data: new FormData(this),
        processData:false,
     	contentType:false,
        success: function(data){
        	var result = data;
        	console.log(result);

        	// $('#reimbursement_receipt').fileinput('upload');

        	if(result){
        		window.location = base_url + "reimbursement/index";
        	}
        }
   });
});

// $("#reimbursement_receipt").fileinput({
//     theme: 'fa',
//     uploadUrl: '/payroll/reimbursement/uploadFile', // you must set a valid URL here else you will get an error
//     uploadAsync: false,
//     browseClass: "btn btn_purple",
//     fileType: "any",
//     required: true,
//     showCaption: false,
//     showUpload: false,
//     showRemove: false,
//     //showClose: false,
//     autoReplace: true,
//     overwriteInitial: true,
//     maxFileCount: 1,
//     fileActionSettings: {
//                     showRemove: true,
//                     showUpload: false,
//                     showZoom: true,
//                     showDrag: false,
//                 },
//     previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
//     initialPreviewShowDelete: false,
//     initialPreviewAsData: true,
//     initialPreviewDownloadUrl: base_url + 'uploads/reimbursement/{filename}',
//     initialPreview: initialPreviewArray,
//     initialPreviewConfig: initialPreviewConfigArray,
//     allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "ico", "Icon"],
//     //deleteUrl: "/dot/personprofile/deleteFile",
//     /*maxFileSize: 20000048,
//     maxImageWidth: 1000,
//     maxImageHeight: 1500,
//     resizePreference: 'height',
//     resizeImage: true,*/
//     purifyHtml: true // this by default purifies HTML data for preview
//     /*uploadExtraData: { 
//         officer_id: $('input[name="officer_id"]').val() 
//     }*/
//     /*width:auto;height:auto;max-width:100%;max-height:100%;*/

// }).on('filesorted', function(e, params) {
//     console.log('File sorted params', params);
// }).on('filebatchuploadsuccess', function(event, data, previewId, index) {
//     $("#loadingmessage").hide();
//     // window.location.href = base_url + "reimbursement";
//     toastr.success('Information Updated', 'Updated');
//     console.log(data);
// }).on('fileuploaderror', function(event, data, msg) {
//     $("#loadingmessage").hide();
//     // window.location.href = base_url + "reimbursement";
//     toastr.success('Information Updated', 'Updated');
//     console.log(data);
// });

</script>  