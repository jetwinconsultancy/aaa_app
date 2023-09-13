<div class="header_between_all_section">
<section class="panel">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="create_transaction amber" href="<?= base_url();?>transaction/add" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Services" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Services</a>
									
			<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
			<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="w2-currency" class="tab-pane">
				<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-transaction">
					<thead>
						<tr>
							<th>No</th>
							<th>Company Name</th>
							<th>Create Date</th>
							<th>Effective Date</th>
							<th>Transaction ID</th>
							<th>Transaction Type</th>
							<th>Remarks</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="service_body">
						<?php
						$i = 1;
							foreach($transaction as $a)
							{
								//print_r($a);
								echo '<tr>
										<td>'.$i.'</td>
										<td>'.($a->client_name != NULL ? $a->client_name : $a->company_name).'</td>
										<td><span style="display:none">'.date("Ymd",strtotime($a->created_at)).'</span>'.date("d F Y",strtotime($a->created_at)).'</td>
										<td><span style="display:none">'.date("Ymd",strtotime($a->effective_date)).'</span>'.$a->effective_date.'</td>
										<td>'.$a->transaction_code.'</td>
										<td>
										<a class="'.(($a->status == 4 || $a->status == 5)?'link_disabled':'').'" href="'.site_url("transaction/edit/".$a->id).'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Transaction">'.$a->transaction_task.'</a>
										</td>
										<td>'.$a->remarks.'</td>
										<td>'.$a->transaction_status.'</td>
										
									</tr>';
									// <span href="" class="fa fa-pencil edit_share pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'"></span>
									// <td><span href="" style="height:45px;font-weight:bold;" class="edit_currency  pointer amber" data-id="'.$a->id.'" data-info="'.$a->currency.'">'.$a->currency.'</span></td>
									// 	<td>
									// 		<a href="master/remove_currency/'.$a->id.'" class="fa fa-trash pointer amber"></span>
									// 	</td>	
								$i++;
							}
						?>
						
					</tbody>
				</table>
			</div>
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
