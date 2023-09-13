<div id="w2-share_transfer_form" class="panel">
	<h3>Share Transfer</h3>
	<form id="share_transfer_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control transaction_share_transfer_id" id="transaction_share_transfer_id" name="transaction_share_transfer_id" value=""/>
		<input type="hidden" class="form-control" name="client_member_share_capital_id" id="client_member_share_capital_id" value=""/>
		<div class="hidden"><input type="text" class="form-control" name="transaction_type" value="Transfer"/></div>
		<div class="form-group">
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
						<select class="form-control" style="text-align:right;width: 200px;" name="class" id="class">
							<option value="0">Select Class</option>
						</select>
						
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Currency: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<input type="text" style="width: 200px;" class="form-control" name="currency" id="currency" value="" readonly/>
						
					</div>
				</div>
			</div>
		</div>

		<div id="transfer_member" align="center">
			<h3 align="left">From</h3>	
			<table class="table table-bordered table-striped table-condensed mb-none" style="width: 800px">
				<thead>
					<tr>
						<th style="width:150px; text-align: center">ID</th>
						<th style="width:150px; text-align: center">Name</th>
						<th style="width:180px; text-align: center">Current Number of Shares</th>
						<th style="width:200px; text-align: center">Number of Shares to Transfer</th>
						<th>Consideration</th>
						<th>Certificate</th>
						<th><a href="javascript: void(0);" style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transfer</span></a></th>
					</tr>
				</thead>
				<tbody id="transfer_add">
										

				</tbody>
				<tr id="total_share">
					<th>Total</th>
					<th style="border-right: none;"></th>
					<th style="border-left: none;"></th>
					<th style="text-align: right;" id="total_from">0</th>
					<th></th>
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
						<th><a href="javascript: void(0);" style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_to_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transfer</span></a></th>
					</tr>
				</thead>
				<tbody id="transfer_to_add">
										

				</tbody>
				<tr id="total_share_transfer_to">
					<th>Total</th>
					<th style=""></th>
					<th style="text-align: right;" id="total_to">0</th>
					<th></th>
					<th></th>
				</tr>
			</table>
		</div>

		

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitShareTransferInfo" id="submitShareTransferInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
	<div class="register_member_table" id="register_table_member" style="width:100%;" align="center">
		<table style="border:1px solid black; width: 1000px;" class="allotment_table" id="register_filing_table">
			<thead>
				<tr>
					<th style="text-align:center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
					<th style="word-break:break-all;text-align: center;width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferor ID</th>
					<th style="word-break:break-all;text-align: center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">Transferor Name</th>
					<th style="word-break:break-all;text-align: center;width:60px !important;padding-right:2px !important;padding-left:2px !important;">Transferee ID</th>
					<th style="word-break:break-all;text-align: center; width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee Name</th>
					<th style="text-align: center; width:70px !important;padding-right:2px !important;padding-left:2px !important;">Class</th>
					<th style="text-align: center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">Share Certificate No.</th>
					<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>
					<!-- 1 -->
					<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No. of Shares Transferred</th>
					<th style="width:20px !important;"></th>
					<!-- <th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Balance No of Shares</th> -->
								
				</tr>
			</thead>
			<tbody id="transfer_info_add">
										

			</tbody>
		</table>
	</div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>