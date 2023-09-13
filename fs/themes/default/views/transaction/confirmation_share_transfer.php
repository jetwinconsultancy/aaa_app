<div id="transaction_share_transfer">
	<div id="transaction_confirmation_member_table">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Share Transfer</span><a href="javascript:void(0)" class="btn btn-primary edit_share_transfer_info" id="edit_share_transfer_info" style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<!-- <div class="form-group">
			<label class="col-sm-2" for="w2-username">Company Name: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="company_name"></label>
						
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Class: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="class_name"></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Currency: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="currency"></label>
						
					</div>
				</div>
			</div>
		</div> -->
		
			<table style="border:1px solid black; width: 1070px;" class="allotment_table" id="register_filing_table">
				<thead>
					<tr>
						<th style="text-align:center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
						<th style="word-break:break-all;text-align: center;width:60px !important;padding-right:2px !important;padding-left:2px !important;">Transferor ID</th>
						<th style="text-align: center; width:150px !important;padding-right:2px !important;padding-left:2px !important;">Transferor Name</th>
						<th style="word-break:break-all;text-align: center;width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee ID</th>
						<th style="word-break:break-all;text-align: center; width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee Name</th>
						<th style="text-align: center; width:70px !important;padding-right:2px !important;padding-left:2px !important;">Class</th>
						<th style="text-align: center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">Share Certificate No.</th>
						<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>
						<!-- 1 -->
						<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No. of Shares Transferred</th>
						<!-- <th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Balance No of Shares</th> -->
									
					</tr>
				</thead>
				<tbody id="confirm_transfer_info_add">
											

				</tbody>
			</table>
		
		<!-- <div id="transfer_member" class="tab-pane" align="center">
			<h3 align="left">From</h3>	
			<table class="table table-bordered table-striped table-condensed mb-none" style="width: 1100px">
				<thead>
					<tr>
						<th style="width:200px;">ID</th>
						<th style="width:200px;">Name</th>
						<th style="width:200px;">Current Number of Shares</th>
						<th style="width:220px;">Number of Shares to Transfer</th>
						<th>Consideration</th>
						<th>Certificate</th>
					</tr>
				</thead>
				<tbody id="confirmation_transfer_add">
										

				</tbody>
				<tr id="total_share">
					<th>Total</th>
					<th style="border-right: none;"></th>
					<th style="border-left: none;"></th>
					<th style="text-align: right;" id="total_from">0</th>
					<th></th>
					<th></th>
				</tr>
			</table>


			<h3 class="to" align="left">To</h3>	
			<table class="table table-bordered table-striped table-condensed mb-none" style="width: 800px">
				<thead>
					<tr id="table_transfer_to">
						<th style="width:220px;">ID</th>
						<th style="width:220px;">Name</th>
						<th style="width:190px;">Number of Shares</th>
						<th>Certificate</th>
					</tr>
				</thead>
				<tbody id="confirmation_transfer_to_add">
										

				</tbody>
				<tr id="total_share_transfer_to">
					<th>Total</th>
					<th style=""></th>
					<th style="text-align: right;" id="total_to">0</th>
					<th></th>
				</tr>
			</table>
		</div> -->
	</div>
	

	<div id = "document">
		<h3>Compilation</h3>
		<table class="table table-bordered table-striped mb-none" id="datatable-pending" style="width:100%;">
		<thead>
			<tr>
				<th style="text-align: center;width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
				<!-- <th style="text-align: center;width:250px !important;padding-right:2px !important;padding-left:2px !important;">Client</th> -->
				<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Document Name</th>
				<!-- <th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created On</th> -->
				<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
			</tr>
		</thead>
		<tbody id="pending_doc_body">
			
		</tbody>
	</table>

	</div>

	<div>
		<h3>Logdement Info</h3>

		<div class="form-group">
			<label class="col-sm-3" for="w2-DS2">Effective Date:</label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid lodgement_date" id="date_todolist" name="lodgement_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
	</div>

</div>

<script src="themes/default/assets/js/confirmation_share_transfer.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
