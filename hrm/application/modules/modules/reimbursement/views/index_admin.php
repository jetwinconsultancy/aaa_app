<link href="<?= base_url() ?>node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="<?= base_url() ?>node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>

<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<!-- <a class="create_client themeColor_purple" href="reimbursement/apply_reimbursement" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Apply Reimbursement</a> -->
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<button class="btn btn_purple pull-right" onclick="view_history()">History</button>

			<div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12">
					<!-- <div class="dataTables_filter" id="datatable-default_filter">
						<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<a href="Interview" class="btn btn_purple">Show All Interview</a>
						<?= form_close();?>
					</div> -->
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<!-- <th class="text-left">Reimbursement no. </th> -->
								<!-- <th></th> -->
								<th class="text-left">Employee</th>
								<th class="text-left">Client</th>
								<th class="text-left">Firm</th>
								<th class="text-left">Status</th>
								<th class="text-center">Total Amount Pending</th>
								<th class="text-center">Date of latest Pending Claim</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach($reimbursement_list as $row)
					  			{
					  				echo '<tr>';
					  				// echo '<td><input type="checkbox" name="vehicle" value="Bike"></td>';
					  				// echo '<td><a href="reimbursement/edit/'. $row->id .'">'. $row->reimbursement_no .'</td>';
					  				// echo '<td>'. date('d F Y', strtotime($row->date)) .'</td>';
					  				echo '<td>'. $row->employee_name .'</td>';
					  				echo '<td>'. $row->client_name .'</td>';
					  				echo '<td>'. $row->firm_name .'</td>';
					  				echo '<td><a href="reimbursement/view_content/'. $row->employee_id . '/' . $row->client_name . '/' . $row->firm_name . '" style="cursor:pointer;">'. $row->status_count .' pending(s)</a></td>';
					  				// echo '<td align="center";><img src='. base_url() . 'uploads/reimbursement/' . $row->receipt_img_filename .' style=" width:30%; height:30%;"></td>';
					  				echo '<td>'. $row->total_amount .'</td>';
					  				echo '<td>'. date('d F Y', strtotime($row->max_date)) .'</td>';
					  				// echo '<td>'. $row->status_id .'</td>';
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

<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">Send Offer Letter</h4>
        </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn_purple">Send</button>
      </div>
    </div>
  </div>
</div> -->

<script>
	var base_url = '<?php echo base_url(); ?>';
	
	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    } );
	} );

	function view_history(){
		window.location = base_url + "reimbursement/history_admin";
	}
	// function confirmationOL(id){
	// 	$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
	// 		$('.modal-body').empty();
 //            $('.modal-body').prepend(data);
 //            $('#exampleModal').modal('show');
 //            // $('#exampleModal').show();
 //        });
	// }
</script>