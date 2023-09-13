<link href="<?= base_url() ?>node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="<?= base_url() ?>node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>

<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_purple" href="mc_claim/apply_mc" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Apply MC</a>
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
							<a href="Interview" class="btn btn_purple">Show All Interview</a>
						<?= form_close();?>
					</div> -->
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
				<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
					<thead>
						<tr style="background-color:white;">
							<th class="text-left">MC no.</th>
							<th class="text-left">Start Date</th>
							<th class="text-left">End Date</th>
							<th class="text-center">MC Status</th>
							<th class="text-center">Claim no.</th>
							<th class="text-center">Receipt</th>
							<th class="text-center">Claim Status</th>
						</tr>
					</thead>
					<tbody>
						<!-- <tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td align="center"><button class="btn btn_purple">Submit</button></td>
							<td></td>
						</tr> -->
						<!-- <?php echo json_encode($mc_claim_list); ?> -->
						<?php 
							foreach($mc_claim_list as $row)
				  			{
				  				echo '<tr>';
				  				echo '<td><a href="mc_claim/mc_edit/'.$row->apply_id.'">'.$row->mc_no.'</a></td>';
				  				echo '<td>'.date('d F Y', strtotime($row->start_date)).'</td>';
				  				echo '<td>'.date('d F Y', strtotime($row->end_date)).'</td>';
				  				echo '<td>'.$row->mc_status.'</td>';

				  				if($row->mc_status == 'Approved'){
				  					if(!empty($row->claim_id)){
				  						echo '<td align="center"><a href="mc_claim/edit_claim/'.$row->claim_id.'">'.$row->claim_no.'</a></td>';
				  					}else{
				  						echo '<td align="center"><a href="mc_claim/claim_apply/'. $row->apply_id .'" class="btn btn_purple">Apply</a></td>';
				  					}
				  				}else{
				  					echo '<td align="center"> - </td>';
				  				}
				  				
				  				echo '<td>'.$row->receipt_img.'</td>';
				  				echo '<td>'.$row->claim_status.'</td>';
				  				echo '</tr>';
				  			}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">Send Offer Letter</h4>
        </div>
      <!-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Offer Letter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn_purple">Send</button>
      </div>
    </div>
  </div>
</div>

<script>
	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    } );
	} );

	function confirmationOL(id){
		$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#exampleModal').modal('show');
            // $('#exampleModal').show();
        });
	}
</script>