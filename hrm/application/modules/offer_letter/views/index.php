<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_purple" href="employee/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Employee" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Create Employee</a>
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12">
					<!-- <div class="dataTables_filter" id="datatable-default_filter">
						<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<a href="Employee" class="btn btn_purple">Show All Employee</a>
						<?= form_close();?>
					</div> -->
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
				<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
					<thead>
						<tr style="background-color:white;">
							<th class="text-left">Name</th>
							<th class="text-center">Phone no.</th>
							<th class="text-left">Designation</th>
							<th class="text-left">Department</th>
							<th class="text-center">Workpass</th>
						</tr>
					</thead>
					<tbody>
						<!-- <?php 
							foreach($staff_list as $row)
				  			{
				  				echo '<tr>';
				  				echo '<td><a href="employee/edit/'.$row->id.'">'.$row->name.'</td>';
				  				echo '<td>'.$row->phoneno.'</td>';
				  				echo '<td>'.$row->designation.'</td>';
				  				echo '<td>'.$row->department.'</td>';
				  				echo '<td>'.$row->workpass.'</td>';
				  				echo '</tr>';
				  			}
						?> -->
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<!-- <script>
	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    } );
	} );
</script> -->