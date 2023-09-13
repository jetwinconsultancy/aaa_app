<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<header class="panel-heading">
				<div class="panel-actions">
					<?php if(count($staff_list)<=0){ ?>
						<a class="create_client themeColor_purple" href="employee/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Employee" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Create Employee</a>
					<?php } ?>

					<!-- <a class="create_client themeColor_purple" href="employee/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Employee" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Create Employee</a> -->
				</div>
			<h2></h2>
		</header>
		<div class="panel-body">
			<div class="col-md-12">
				<?php if($Admin || $Manager){ ?>
					<div class="tabs">
						<ul class="nav nav-tabs nav-justify">
							<li class="check_state active" data-information="current" style="width: 25%">
								<a href="#w2-current" data-toggle="tab" class="text-center">
									<b>Current Employee</b>
								</a>
							</li>
							<li class="check_state" data-information="past" style="width: 25%">
								<a href="#w2-past" data-toggle="tab" class="text-center">
									<b>Past Employee</b>
								</a>
							</li>
						</ul>
						<div class="tab-content clearfix">
							<div id="w2-current" class="tab-pane active">
								<div class="col-sm-12 col-md-12">
									<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
										<table class="table table-bordered table-striped mb-none" id="datatable-employee_info" style="width:100%">
											<thead>
												<tr style="background-color:white;">
													<th class="text-left">Name</th>
													<th class="text-center">Phone no.</th>
													<th class="text-left">Designation</th>
													<th class="text-left">Office</th>
													<th class="text-left">Department</th>
													<th class="text-center">Workpass</th>
													<th class="text-center">Account</th>
													<th class="text-center hidden">Offer Letter</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($staff_list as $row)
										  			{
										  				echo '<tr>';
										  				echo '<td><a href="employee/edit/'.$row->id.'">'.$row->name.'</td>';
										  				// echo '<td>'.$row->phoneno.'</td>';
										  				echo '<td>'.$row->telephone.'</td>';
										  				echo '<td>'.$row->designation.'</td>';
										  				echo '<td>'.$row->office_name.'</td>';
										  				echo '<td>'.$row->department_name.'</td>';
										  				echo '<td>'.$row->workpass.'</td>';

										  				if($row->user_id == null){
										  					echo '<td align="center"><a href="'. base_url() .'employee/create_user/'. $row->id .'" class="btn btn-success">Create</a></td>';
										  				}else{
										  					echo '<td>'.$row->user_email.'</td>';
										  				}
										  				
										  				echo '<td class ="hidden" style="text-align:center;"><button type="button" class="btn btn_purple" onclick=confirmationOL("'. $row->id .'")>Offer Letter</button></td>';
										  				echo '</tr>';
										  			}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<div id="w2-past" class="tab-pane">
								<div class="col-sm-12 col-md-12">
									<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
										<table class="table table-bordered table-striped mb-none" id="datatable-past_employee_info" style="width:100%">
											<thead>
												<tr style="background-color:white;">
													<th class="text-left">Name</th>
													<th class="text-center">Phone no.</th>
													<th class="text-left">Designation</th>
													<th class="text-left">Office</th>
													<th class="text-left">Department</th>
													<th class="text-center">Workpass</th>
													<th class="text-center">Account</th>
													<th class="text-center hidden">Offer Letter</th>
													<th class="text-center">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($past_staff_list as $row)
										  			{
										  				echo '<tr>';
										  				echo '<td><a href="employee/edit/'.$row->id.'">'.$row->name.'</td>';
										  				// echo '<td>'.$row->phoneno.'</td>';
										  				echo '<td>'.$row->telephone.'</td>';
										  				echo '<td>'.$row->designation.'</td>';
										  				echo '<td>'.$row->office_name.'</td>';
										  				echo '<td>'.$row->department_name.'</td>';
										  				echo '<td>'.$row->workpass.'</td>';

										  				if($row->user_id == null){
										  					echo '<td align="center"><a href="'. base_url() .'employee/create_user/'. $row->id .'" class="btn btn-success">Create</a></td>';
										  				}else{
										  					echo '<td>'.$row->user_email.'</td>';
										  				}
										  				
										  				echo '<td class ="hidden" style="text-align:center;"><button type="button" class="btn btn_purple" onclick=confirmationOL("'. $row->id .'")>Offer Letter</button></td>';
										  				echo '<td align="center"><button type="button" class="btn btn_purple" onclick=reemployed("'. $row->id .'")>Re-employed</button></td>';
										  				echo '</tr>';

										  			}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php }else{ ?>
					<div class="row datatables-header form-inline">
						<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
							<table class="table table-bordered table-striped mb-none" id="datatable-employee_info" style="width:100%">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">Name</th>
										<th class="text-center">Phone no.</th>
										<th class="text-left">Designation</th>
										<th class="text-left">Office</th>
										<th class="text-left">Department</th>
										<th class="text-center">Workpass</th>
										<th class="text-center">Account</th>
										<th class="text-center hidden">Offer Letter</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach($staff_list as $row)
							  			{
							  				echo '<tr>';
							  				echo '<td><a href="employee/edit/'.$row->id.'">'.$row->name.'</td>';
							  				// echo '<td>'.$row->phoneno.'</td>';
							  				echo '<td>'.$row->telephone.'</td>';
							  				echo '<td>'.$row->designation.'</td>';
							  				echo '<td>'.$row->office_name.'</td>';
							  				echo '<td>'.$row->department_name.'</td>';
							  				echo '<td>'.$row->workpass.'</td>';

							  				if($row->user_id == null){
							  					echo '<td align="center"><a href="'. base_url() .'employee/create_user/'. $row->id .'" class="btn btn-success">Create</a></td>';
							  				}else{
							  					echo '<td>'.$row->user_email.'</td>';
							  				}
							  				
							  				echo '<td class ="hidden" style="text-align:center;"><button type="button" class="btn btn_purple" onclick=confirmationOL("'. $row->id .'")>Offer Letter</button></td>';
							  				echo '</tr>';
							  			}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
</section>

<!-- Modal -->
<div class="modal fade" id="offer_letter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form id="offer_letter" method="POST">
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
	        <button type="submit" class="btn btn_purple">Save</button>
	      </div>
  		</form>
    </div>
  </div>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>';

	function confirmationOL(id){
		$.post(base_url + "offer_letter/sendOL_ExistingEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#offer_letter_modal').modal('show');
            // $('#exampleModal').show();
        });
	}

	function preview_offer_letter(employee_id){
        // console.log(payslip_id);

        $.post(base_url + "employee/view_offer_letter", { 'employee_id': employee_id }, function(data, status){
            // console.log(data);
            var response = JSON.parse(data);

            window.open(
                response.pdf_link,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
    }

	$(document).ready(function () {
		$("#datatable-past_employee_info").DataTable();
		$("#datatable-employee_info").DataTable()
	});


	
	function reemployed(id) 
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
		    url: base_url + "employee/reemployed",
		    type: "POST",
		    data: {"id": id},
		    dataType: 'json',
		    success: function (response) {
		        $('#loadingmessage').hide();
		        if(response.Status == 1)
		        {
		            toastr.success("Updated Information.", "Updated");
		            setTimeout(window.location.reload(),3000)
		        }
		    }
		});
	}
</script>
