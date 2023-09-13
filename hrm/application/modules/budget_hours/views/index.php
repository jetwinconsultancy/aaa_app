<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<section role="main" class="content_section">

	<header class="panel-heading">
		<!-- <div class="panel-actions">
			
		</div>
		<h2></h2> -->
	</header>

	<div class="panel-body">
		<div class="col-md-12">
			<div class="row datatables-header form-inline">
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table" id="datatable-default" style="table-layout: fixed;width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Assingment No.</th>
								<th class="text-left">Client Name</th>
								<th class="text-left">Budget Hours</th>
							</tr>
						</thead>
						<tbody>
							<?PHP
								foreach ($assignment_list as $result) {
									echo '<tr>';
					  				echo '<td><a href="budget_hours/edit/'. $result->assignment_id .'">'. $result->assignment_id .'</td>';
					  				echo '<td>'. $result->client_name .'</td>';
					  				echo '<td>'. $result->budget_hour .'</td>';
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

<script>
	//DATATABLE INITIAL
	$(document).ready( function (){
	    $('#datatable-default').DataTable( {
	    	"order": []
	    } );
	});
</script>