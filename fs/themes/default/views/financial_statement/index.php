<div class="header_between_all_section">
	<section class="panel">
		<header class="panel-heading">
			<h2></h2>
		</header>
		<div class="panel-body">
			<div class="col-md-12">
				<div class="row datatables-header form-inline">
					<div class="col-sm-12 col-md-12">
						<div class="dataTables_filter" id="datatable-default_filter">

							<?php 
								$attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
								echo form_open_multipart("financial_statement", $attrib);
							?>
							<!-- <select id="service_category" class="form-control service_category" style="width:200px;" name="service_category">
								<option value="0" <?=$_POST['service_category']=='0'?'selected':'';?>>All</option>
								<option value="1" <?=$_POST['service_category']=='1'?'selected':'';?>>Statutory Audit</option>
								<option value="2" <?=$_POST['service_category']=='2'?'selected':'';?>>Accounting</option>
								<option value="3" <?=$_POST['service_category']=='3'?'selected':'';?>>Tax</option>
								<option value="4" <?=$_POST['service_category']=='4'?'selected':'';?>>Secretary</option>
								<option value="5" <?=$_POST['service_category']=='5'?'selected':'';?>>Administrative</option>
								<option value="6" <?=$_POST['service_category']=='6'?'selected':'';?>>Human Resource</option>
								<option value="7" <?=$_POST['service_category']=='7'?'selected':'';?>>Registered Office Address</option>
								<option value="8" <?=$_POST['service_category']=='8'?'selected':'';?>>Others</option>
								<option value="9" <?=$_POST['service_category']=='9'?'selected':'';?>>I.T.</option>
								<option value="10" <?=$_POST['service_category']=='10'?'selected':'';?>>Other Assurance</option>
							</select> -->
							<select class="form-control" name="tipe" style="display: none;">
								<option value="All" <?=$_POST['tipe']=='All'?'selected':'';?>>All</option>
								<option value="company_name" <?=$_POST['tipe']=='company_name'?'selected':'';?>>Company Name</option>
								<option value="former_name" <?=$_POST['tipe']=='former_name'?'selected':'';?>>Former Name</option>
								<option value="registration_no" <?=$_POST['tipe']=='registration_no'?'selected':'';?>>Registration No</option>
							</select>

							<input class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" type="search">
							<div class="search_group_button" style="display: inline;">
								<input type="submit" class="btn btn-primary" value="Search"/>
								<a href="financial_statement" class="btn btn-primary">Show All Clients</a>
							</div>
							<?= form_close();?>
						</div>
					</div>
				</div>

				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-center">Registration No</th>
								<th class="text-center">Company</th>
								<th class="text-center">Status</th>
								<th class="text-center">Current FYE</th>
								<th class="text-center">Last FYE</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i=1;

								foreach($client as $key=>$c)
								{
							?>
									<tr>
										<td>
											<div style="width: 100px; word-break:break-all;" >
												<?=ucwords(substr($c["registration_no"],0,11))?><span id="p<?=$i?>" style="display:none;"><?=substr($c["registration_no"],11,strlen($c["registration_no"]))?></span>
												<?php
													if(strlen($c['registration_no']) > 11)
													{
														echo '<a class="tonggle_readmore" data-id=p'.$i.'>...</a>';
													}
												?>
											</div>
										</td>
										<td>
											<div style="width: 350px; word-break:break-all;" >
												<a class="" href="<?=site_url('financial_statement/partial_fs_report_list/'.$c["id"]);?>" data-name="<?=$c["company_name"]?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="View FS report details"><?=ucwords(substr($c["company_name"],0,50))?><span id="f<?=$i?>" style="display:none;cursor:pointer"><?=substr($c["company_name"],50,strlen($c["company_name"]))?></span></a>
												<?php
													if(strlen($c['company_name']) > 50)
													{
														echo '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
													}
												?>
											</div>
										</td> 
										<td>
											<?php 
												echo '<input type="hidden" class="fs_company_info" name="fs_company_info_id[' . $i . ']" value="' . $c["fs_company_info_id"] . '">';

												if($c['current_fye_end'] == '-' && $c['last_fye_end'] == '-')
												{
													echo '<span>Not commenced</span>';
												}
												else
												{
													echo form_dropdown('fs_status[' . $key . ']', $fs_list_report_status, isset($c['fs_list_report_status_id'])?$c['fs_list_report_status_id']: '', 'class="fs_status" style="width: 100%;" id="fs_status" onchange="change_fs_status(this)"');

													// echo 
													// 	'<select class="fs_status" style="width: 100%;" id="fs_status" onchange="change_fs_status(this)">
													// 		<option value="1">Not commenced</option>
													// 		<option value="2">In progress</option>
													// 		<option value="3">Completed</option>
													// 	</select>';
												}
											?>
										</td>
										<td align="right">
											<?php
												echo $c['current_fye_end'];
											?>
										</td>
										<td align="right">
											<?php
												echo $c['last_fye_end'];
											?>
										</td>
									</tr>
							<?php 
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

<script type="text/javascript">
	$(".fs_status").select2({ minimumResultsForSearch: -1 });

	toastr.options = { "positionClass": "toast-bottom-right" };

	$("#header_client").addClass("header_disabled");

	$(document).on('click','.tonggle_readmore',function ()
	{
		$id = $(this).data('id');
		$("#"+$id).toggle();
	});

	function change_fs_status(element)
	{
		bootbox.confirm("Are you sure to change status of report?", function (result) 
		{
            if (result) 
            {
            	var fs_company_info_id = $(element).parent().find('.fs_company_info').val();
            	var fs_list_report_status_id = $(element).val();

            	var data = {
            		fs_company_info_id: fs_company_info_id,
            		fs_list_report_status_id: fs_list_report_status_id
            	};

            	$.ajax({ //Upload common input
				   	url: "financial_statement/update_fs_report_status",
				   	type: "POST",
				   	data: data,
					dataType: 'json',
					success: function (response,data) 
					{
						if(response['result'])
						{
							toastr.success("Information Updated", "Success");
						}
						else
						{
							toastr.error("Something went wrong. Please try again later.", "Error");
						}
					}
			    });
            }
            else
            {
            	return false;
            }
        });
	}

	// $(document).on('onchange','.fs_status',function (element)
	// {
	// 	bootbox.confirm("Are you sure to change status of report?", function (result) 
	// 	{
 //            if (result) 
 //            {
 //            	console.log(element);
 //            }
 //            else
 //            {
 //            	return false;
 //            }
 //        });
	// });

</script>

<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>