<tr>
	<td>
		<div class='input-group date' class='date'>
            <input type='text' class="form-control" name=<?php echo 'reimbursement_date['.$count.']' ?> required />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </span>
        </div>
	</td>
	<td><input type='text' class="form-control" name=<?php echo 'reimbursement_client_name['.$count.']' ?> required /></td>
	<td><input type='text' class="form-control" name=<?php echo 'reimbursement_description['.$count.']' ?> required /></td>
	<td><input type='text' class="form-control" name=<?php echo 'reimbursement_firm['.$count.']' ?> required /></td>
	<td><input type='number' class="form-control" name=<?php echo 'reimbursement_amount['.$count.']' ?> required /></td>
	<td>
		<div style="text-align: center;">
			<!-- <a class="btn btn_purple" style="cursor: pointer" onclick="upload_Receipt()">Upload Receipt</a> -->
			<div class="row">
				<label class="btn btn_purple">
					<input type='file' class="receipt" name=<?php echo 'reimbursement_receipt['.$count.']' ?> style="width: 150%;"/>
					Upload Receipt
				</label>
			</div>
			<div class="row">
				<span class="filename" style="font-size: 12px;"></span>
				<a style="cursor: pointer; font-size: 12px;" onclick="clear_file(this)">Clear</a>
			</div>
		</div>
	</td>
	<td><input type='text' class="form-control" name=<?php echo 'reimbursement_invoice_no['.$count.']' ?> required /></td>
	<td>
		<span class="glyphicon glyphicon-trash" onclick="delete_imbursement(this)" style="cursor: pointer;"></span>
	</td>
</tr>

<style>
	input[type="file"] {
	    display: none;
	}
</style>

<link rel="stylesheet" href="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="<?= base_url()?>application/js/custom/time_format.js"></script>

<script>
	$('.date').datetimepicker({
	    format: 'DD/MM/YYYY',
        useCurrent: false
	});

	// function upload_Receipt(){
	// 	console.log($('#offer_letter_modal'));
	// 	$('#offer_letter_modal').modal('show');
	// }

	$('.receipt').change(function() {
		console.log($(this)[0].files[0] == undefined);
		var label 	 = $(this).parent().parent();
		var filename_label = $(this).parent().parent().parent().find(".filename");

		var filename = '';

		if($(this)[0].files[0] != undefined){
			filename = $(this)[0].files[0].name;
		}
		
		// console.log(filename_label);
		filename_label.text(filename);

		// var i = $(this).prev('label').clone();
		// var file = $('.receipt')[0].files[0].name;
		// $(this).prev('label').text(file);
	});

	function clear_file(element){
		var input_file = $(element).parent().parent().find('.receipt');
		var filename_label = $(element).parent().find('.filename');

		input_file.replaceWith(input_file.val('').clone(true));
		filename_label.text('');
	}
</script>