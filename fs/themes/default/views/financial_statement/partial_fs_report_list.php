<div class="header_between_all_section">
	<section class="panel">
		<?php echo $breadcrumbs;?>

		<header class="panel-heading">
			<div class="panel-actions">
				<!-- <a class="create_fs_report amber" href="financial_statement/create/<?=$client_id?>/0" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create FS Report" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create</a> -->
			</div>
			<h2></h2>
		</header>
		<div class="panel-body">
			<div class="col-md-12">
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:60%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-center" style="width:1%">No.</th>
								<th class="text-center">Final Year End</th>
							</tr>
						</thead>
						<tbody>
						<?php
							foreach ($fs_company_info_list as $key => $value) 
							{
								echo 
									'<tr>' . 
										'<td>' . ($key+1) . '</td>' . 
										'<td align="center"><a style="cursor:pointer;" onclick="load_report(' . $client_id . ',' . $value['id']. ')">' . $value['current_fye_end'] . '</a></td>' . 
									'</tr>';
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
function load_report(client_id, fs_company_info_id)
{
	localStorage.setItem('lastTab', '#FS_corporate_information');

	window.location.href = "financial_statement/create/" + client_id + '/' + fs_company_info_id;

}
</script>

<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>