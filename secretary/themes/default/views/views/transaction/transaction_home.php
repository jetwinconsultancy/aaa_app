<div class="header_between_all_section">
<section class="panel">
	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
			<div class="panel-actions">
				<a class="create_transaction amber" href="<?= base_url();?>transaction/add" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Services" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Services</a>
			</div>
			<h2></h2>
		<?php } ?>
	</header>
	<div class="panel-body">
		<div class="col-md-5">
			<span>Filter Status:</span>
			<select id="filter_transaction" class="form-control filter_transaction" style="width:200px; margin-bottom: 20px;" name="filter_transaction">
				<option value="">All</option>
            	<option value="Pending Signature">Pending Signature</option>
            	<option value="Completed">Completed</option>
            	<option value="Lodged to ACRA">Lodged to ACRA</option>
            	<option value="Cancelled by System">Cancelled by System</option>
            	<option value="Cancelled by User">Cancelled by User</option>
            	<option value="Engagement Letter prepared">Engagement Letter prepared</option>
			</select>
		</div>
		<div class="col-md-12">
			<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-transaction">
				<thead>
					<tr>
						<th>No</th>
						<th>Company Name</th>
						<th>Create Date</th>
						<th>Effective Date</th>
						<th>Lodgement Date</th>
						<th>Transaction ID</th>
						<th>Transaction Type</th>
						<th>Remarks</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody id="service_body">
					<?php
						$i = 1;
						if($transaction)
						{
							foreach($transaction as $a)
							{
								if($a->transaction_task_id != 36)
								{
									$td_home = '<td><span style="display:none">'.(($a->lodgement_date != "")?date("Ymd",strtotime($a->effective_date)):"").'</span>'.($a->lodgement_date != ""?($a->effective_date != "")?$a->effective_date:"<p style='text-align:center;'>-</p>":"").'</td>
										<td><span style="display:none">'.date("Ymd",strtotime($a->lodgement_date)).'</span>'.$a->lodgement_date.'</td>';
								}
								else
								{
									$td_home = '<td><span style="display:none">'.(($a->effective_date != "")?date("Ymd",strtotime($a->effective_date)):"").'</span>'.($a->effective_date != ""?($a->effective_date != "")?$a->effective_date:"<p style='text-align:center;'>-</p>":"").'</td>
										<td><span style="display:none"></span><p style="text-align:center;">-</p></td>';
								}

								echo '<tr>
										<td style="text-align: right"></td>
										<td>'.($a->client_name != NULL ? $a->client_name : (isset($a->company_name)?$a->company_name:'')).'</td>
										<td><span style="display:none">'.date("Ymd",strtotime($a->created_at)).'</span>'.date("d F Y",strtotime($a->created_at)).'</td>
										'.$td_home.'
										<td>'.$a->transaction_code.'</td>
										<td>
										<a class="'.(($a->status == 4 || $a->status == 5)?'link_disabled':'').'" href="'.site_url("transaction/edit/".$a->id).'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Transaction">'.$a->transaction_task.'</a>
										</td>
										<td>'.$a->remarks.'</td>
										<td>'.$a->transaction_status.'</td>
										
									</tr>';
								$i++;
							}
						}
					?>
					
				</tbody>
			</table>
		</div>
	</div>
</section>
</div>	
	
<script>
	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_transaction").addClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");

	localStorage.removeItem('lastTab');
	$(document).ready(function () {
        $('.create_transaction').click(function (e) {
            e.preventDefault();
            window.location.href = '<?= base_url();?>transaction/add';

        });
    });
	$(".edit_share").on('click',function(){
		$("#share_id").val($(this).data('id'));
		$("#sharetype").val($(this).data('info'));
	});
	$(".edit_currency").on('click',function(){
		$("#currency_id").val($(this).data('id'));
		$("#currency").val($(this).data('info'));
	});
	$(".edit_typeofdoc").on('click',function(){
		$("#typeofdoc_id").val($(this).data('id'));
		$("#typeofdoc").val($(this).data('info'));
	});
	$(".edit_doccategory").on('click',function(){
		$("#doccategory_id").val($(this).data('id'));
		$("#doccategory").val($(this).data('info'));
	});
	$(".edit_citizen").on('click',function(){
		$("#citizen_id").val($(this).data('id'));
		$("#citizen").val($(this).data('info'));
	});
	$("#field_service").on('click',function(){
		
		$value="";
		$val="";
		$.each($(this).val(),function(k,value){
			//console.log(value);
			$val +="${"+value+"}";
			$value +="${"+value+"}<br/>";
		});
		$("#fild_you_can_used").html("Field You can used :<br/>" +$value);
		// $value.replace("<br/>","");
		$("#link_download").attr('href','phpdocx.php?content='+$val);
	});
	$("#add_field").on('click',function(){
		$html = '<select data-plugin-selectTwo class="form-control populate">' +
							'<optgroup label="Type">' +
								'<option value="AK">Field 1</option>' +
								'<option value="HI">Field 2</option>' +
								'<option value="FIN">Field 3</option>' +
								'<option value="Pas">Field 4</option>' +
							'</optgroup>' +
						'</select>';
		$("#div_field").append($html);
	});
	$("#add_documet").on('click',function(){
		$html = '<select data-plugin-selectTwo class="form-control populate">' +
							'<optgroup label="Type">' +
								'<option value="AK">Document1</option>' +
								'<option value="HI">Document2</option>' +
								'<option value="FIN">Document3</option>' +
								'<option value="Pas">Document4</option>' +
							'</optgroup>' +
						'</select>';
		$("#div_document").append($html);
	});
</script>
