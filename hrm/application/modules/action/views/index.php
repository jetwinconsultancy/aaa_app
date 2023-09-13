<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="row datatables-header form-inline">
					<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
						<table class="table table-bordered table-striped mb-none" id="datatable-employee_info" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left">Name</th>
									<th class="text-left">Office</th>
									<th class="text-left">Department</th>
									<th class="text-left">Designation</th>
									<th class="text-center">Account</th>
									<th class="text-center">Phone no.</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($staff_list as $row)
						  			{
						  				echo '<tr>';
						  				echo '<td><a href="action/edit/'.$row->id.'">'.$row->name.'</td>';
						  				echo '<td>'.$row->office_name.'</td>';
						  				echo '<td>'.$row->department_name.'</td>';
						  				echo '<td>'.$row->designation.'</td>';
						  				// if($row->user_id == null){
						  				// 	echo '<td align="center"><a href="'. base_url() .'employee/create_user/'. $row->id .'" class="btn btn-success">Create</a></td>';
						  				// }else{
						  				// 	echo '<td>'.$row->user_email.'</td>';
						  				// }
						  				echo '<td>'.$row->user_email.'</td>';
						  				echo '<td>'.$row->telephone.'</td>';
						  				echo '</tr>';
						  			}
								?>
							</tbody>
						</table>
					</div>
				</div>
		</div>
	</div>
	</section>
</section>

<script>

	$(document).ready(function ()
	{
		$("#datatable-employee_info").DataTable()
	});

</script>
