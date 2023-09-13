<div id="transaction_confirmation_purchase_common_seal">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Purchase of Common Seal & Self inking stamp</span><a href="javascript:void(0)" class="btn btn-primary edit_purchase_common_seal" id="edit_purchase_common_seal" style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="purchase_common_seal_interface">
		<div class="form-group">
			<label class="col-sm-2" for="confirmation_purchase_date">Date:</label>
			<div class="col-sm-8">
				<span class="confirmation_purchase_date" id="confirmation_purchase_date" name="confirmation_purchase_date"></span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="confirmation_common_seal_vendor">Vendor:</label>
			<div class="col-sm-8">
				<span class="confirmation_common_seal_vendor" id="confirmation_common_seal_vendor" name="confirmation_common_seal_vendor"></span>
			</div>
		</div>
		<div style="margin-top: 20px;padding-bottom: 18px;">
			<table class="table table-bordered table-striped table-condensed mb-none" id="confirmation_purchase_common_seal_table" style="width: 1000px" style="margin-top: 20px;">
				<thead>
					<tr>
						<th id="id_position" style="text-align: center;">Company Name</th>
						<th id="id_header" style="text-align: center;width:270px">UEN</th>
						<th style="text-align: center;width:270px" id="id_name">Order for</th>
					</tr>
				</thead>
				<tbody id="confirmation_body_purchase_common_seal">
				</tbody>
			</table>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="button" class="btn btn-primary sendEmailForCommonSeal" id="sendEmailForCommonSeal" value="Send Email" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</div>
</div>
<script src="themes/default/assets/js/confirmation_purchase_common_seal.js?v=44eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>