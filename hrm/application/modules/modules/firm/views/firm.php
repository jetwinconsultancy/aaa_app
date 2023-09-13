<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>

<div class="header_between_all_section">
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions" style="height:80px">
										
				<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
				<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
				<?php if($Admin || $Manager) { ?>
					<a class="edit_firm amber" href="<?= base_url();?>firm/add" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Firm" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Firm</a>
				<?php } ?>
			</div>
			<h2></h2>
				
		</header>
		<div class="panel-body">
			<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
				echo form_open_multipart("firm", $attrib);
				
			?>
					<div class="col-md-12">
						<!-- <div class="col-md-2">
							<select class="form-control input-sm" name="type">
								<option value="all" <?=$type //== "all"?'selected':'';?>>All</option>									
								<option value="individual" <?=$type //== "individual"?'selected':'';?>>Individual</option>
								<option value="company" <?=$type //== "company"?'selected':'';?>>Company</option>
								
							</select>
						</div> -->
						<div class="col-md-5">
								<input type="text" class="form-control input-sm" id="w2-username" name="search" placeholder="Search" value="<?php isset($_POST['search'])?$_POST['search']:'' ?>">
						</div>
						<div class="col-md-4 ">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<input type="submit" class="btn btn_purple" value="Show All Firm"/>
						</div>

							<div class="col-md-12" id="div_firm" style="margin-top:10px;">
								<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default" style="width: 100%">
									<thead>
										<tr>
											<th style="text-align: center;width: 350px">Name</th>
											<th style="text-align: center;width: 200px">Telephone</th>
											<th style="text-align: center;width: 200px">Fax</th>
											<th style="text-align: center;width: 250px">Email</th>
											<th style="text-align: center;width: 100px">Default Company</th>
											<th style="text-align: center;width: 100px">In Use</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$i = 1;
											foreach($all_firm as $p)
											{
												//echo '';
												echo '<tr>';
												if($Admin || $Manager)
												{
													echo '<td style="word-break:break-all;">';
													echo "<a href='firm/edit/".$p->id."' data-name='".$p->name."' style='cursor:pointer;'>".ucwords(substr($p->name,0,37))."<span id='b".$i."' style='display:none;cursor:pointer'>".substr($p->name,37,strlen($p->name))."</span></a>";
													echo '</td>';
												}
												else
												{
													echo '<td style="word-break:break-all;">';
													echo "".ucwords(substr($p->name,0,37))."<span id='b".$i."' style='display:none;cursor:pointer'>".substr($p->name,37,strlen($p->name))."</span>";
													echo '</td>';
												}
												if(strlen($p->name) > 37)
                                                {
                                                    echo '<a class="tonggle_readmore_firm" data-id="b'.$i.'">...</a>';
                                                }
												echo '<td>'.$p->telephone.'</td>';
												echo '<td>'.$p->fax.'</td>';
												echo '<td>'.$p->email.'</td>';
												echo '<td><label class="verify_switch"><input name="firm_switch" class="firm_switch" type="checkbox" '.(($p->default_company)?"checked":"").'><span class="slider round"></span></label><input type="hidden" id="firm_id" name="firm_id" value="'.$p->id.'"><input type="hidden" id="user_id" name="user_id" value="'.$p->user_id.'"></td>';
												echo '<td><label class="verify_switch"><input name="in_use_switch" class="in_use_switch" type="checkbox" '.(($p->in_use)?"checked":"").'><span class="slider round"></span></label></td>';
												echo '</tr>';

												$i++;
											}
										?>

									</tbody>
								</table>
								<br/>
							</div>												
					</div>
			<?= form_close();?>
		</div>


	<!-- end: page -->
	
</section>
</div>
<style>
	#div_firm .datatables-header {
		display:none;
	}
</style>
<script>

	$(document).ready(function () {
	    $('#datatable-default').DataTable( {
	    	"order": []
	    } );
	});

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$("#header_our_firm").addClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");

	$("[name='firm_switch']").change(function() {
		/*console.log(this.checked);
		console.log($(this).parent().parent().parent().find("#firm_id").val());*/
		var checkbox = $(this);
		//var checkbox_checked = $('input[name="firm_switch"]:checked');
		var checked = this.checked;
		var firm_id = $(this).parent().parent().parent().find("#firm_id").val();
		var user_id = $(this).parent().parent().parent().find("#user_id").val();

		$.ajax({
			type: "POST",
			url: "firm/check_default_company",
			data: {"checked":checked, "firm_id": firm_id, "user_id": user_id}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				if(response.Status == 1)
				{
					checkbox.prop('checked', true);
					toastr.error(response.message, response.title);
				}
				else
				{
					//if(confirm('Do you wanna change the default company?')) {
					bootbox.confirm({
	        			message: "Do you wanna change the default company?",
	        			closeButton: false,
	        			buttons: {
	            			confirm: {
	                			label: 'Yes',
	                			className: 'btn_purple'
	            			},
	            			cancel: {
	                			label: 'No',
	                			className: 'btn_cancel'
	            			}
	        			},
	        			callback: function (result) {
	        				if(result == true){
	        					$.ajax({
								type: "POST",
								url: "firm/change_default_company",
								data: {"checked":checked, "firm_id": firm_id, "user_id": user_id}, // <--- THIS IS THE CHANGE
								dataType: "json",
								success: function(response){
									if(response.Status == 1)
									{
										/*if($('input[name="firm_switch"]:checked'))
										{*/
										//if(this.checked){
											 $('input[name="firm_switch"]:checked').not(checkbox).prop('checked', false);
										//}
										//console.log($('input[name="firm_switch"]:checked').not(this));
										//}

										toastr.success(response.message, response.title);
									}
								}
							});
	        				}
	        				else{
								if(checked){
									//console.log(checkbox);
									checkbox.prop('checked', false);
								}
								else{
									checkbox.prop('checked', true);
								}
								//return false;
							}
	        			}
	    			})
	    			
					// else
					// {
					// 	if(checked)
					// 	{
					// 		//console.log(checkbox);
					// 		checkbox.prop('checked', false);
					// 	}
					// 	else
					// 	{
					// 		checkbox.prop('checked', true);
					// 	}
					// 	return false;
					// }

				}

			}
		});
	});

	$("[name='in_use_switch']").change(function() {
		/*console.log(this.checked);
		console.log($(this).parent().parent().parent().find("#firm_id").val());*/
		var checkbox = $(this);
		//var checkbox_checked = $('input[name="firm_switch"]:checked');
		var checked = this.checked;
		var firm_id = $(this).parent().parent().parent().find("#firm_id").val();
		var user_id = $(this).parent().parent().parent().find("#user_id").val();

		$.ajax({
			type: "POST",
			url: "firm/check_in_use_company",
			data: {"checked":checked, "firm_id": firm_id, "user_id": user_id}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				if(response.Status == 1)
				{
					checkbox.prop('checked', true);
					toastr.error(response.message, response.title);
				}
				else
				{						
					//if(confirm('Do you wanna change the in use company?')) {
					bootbox.confirm({
	        			message: "Do you wanna change the in use company?",
	        			closeButton: false,
	        			buttons: {
	            			confirm: {
	                			label: 'Yes',
	                			className: 'btn_purple'
	            			},
	            			cancel: {
	                			label: 'No',
	                			className: 'btn_cancel'
	            			}
	        		},
	        			callback: function (result) {
	        				if(result == true){
	        					$.ajax({
								type: "POST",
								url: "firm/change_in_use_company",
								data: {"checked":checked, "firm_id": firm_id, "user_id": user_id}, // <--- THIS IS THE CHANGE
								dataType: "json",
								success: function(response){
									if(response.Status == 1){
										/*if($('input[name="firm_switch"]:checked'))
										{*/
										//if(this.checked){
											 $('input[name="in_use_switch"]:checked').not(checkbox).prop('checked', false);
										//}
										//console.log($('input[name="in_use_switch"]:checked').not(this));
										//}

										toastr.success(response.message, response.title);
										location.reload();
									}
								}
								});
	        				}
	        				else{
								if(checked){
									//console.log(checkbox);
									checkbox.prop('checked', false);
								}
								else{
									checkbox.prop('checked', true);
								}
							//return false;
							}
	        			}
	    			})

					// else
					// {
					// 	if(checked)
					// 	{
					// 		//console.log(checkbox);
					// 		checkbox.prop('checked', false);
					// 	}
					// 	else
					// 	{
					// 		checkbox.prop('checked', true);
					// 	}
					// 	return false;
					// }

				}

			}
		});
	});

	$(document).on('click','.tonggle_readmore_firm',function (event){
		event.preventDefault();
		$id = $(this).data('id');
		

		if($("#"+$id).css('display') == 'none')
		{
			$("#"+$id).show();
		}
		else
		{
			$("#"+$id).hide();
		}
		//console.log($("#"+$id).show());
	});
</script>