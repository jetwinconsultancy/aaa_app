<div id="transaction_confirmation_controller" class="panel">
	<div class="form-group">
		<label class="col-sm-2" for="w2-username">Registration No: </label>
		<div class="col-sm-8">
			<div style="width: 100%;">
				<div style="width: 75%;float:left;margin-right: 20px;">
					<label id="confirmation_registration_no"></label>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2" for="w2-username">Company Name: </label>
		<div class="col-sm-8">
			<div style="width: 100%;">
				<div style="width: 75%;float:left;margin-right: 20px;">
					<label id="confirmation_company_name"></label>
				</div>
			</div>
		</div>
	</div>
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Current Register of Controller</span><a href="javascript:void(0)" class="btn btn-primary edit_current_controller" id="edit_current_controller" style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div style="padding-top: 18px; padding-bottom: 18px;">
		<table style="width: 100%;" class="table table-bordered table-striped mb-none" id="confirmation_current_controller_table">
            <thead>
                <tr>
	                <th style="text-align: center">Notice sent</th>
	                <th style="text-align: center">Confirmation Received</th>
	                <th style="text-align: center">Date of entry/update</th>
	                <th style="text-align: center">Controller Particulars</th>
	                <th style="text-align: center">Supporting Docs</th>
	                <!-- <th></th> -->
                </tr>
            </thead>
            <tbody id="confirmation_table_body_current_controller">
			</tbody>
        </table>
	</div>
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Latest Register of Controller</span><a href="javascript:void(0)" class="btn btn-primary edit_latest_controller" id="edit_latest_controller" style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div style="padding-bottom: 18px;">
		<table style="width: 100%;" class="table table-bordered table-striped mb-none" id="confirmation_latest_controller_table">
            <thead>
                <tr>
	                <th style="text-align: center">Notice sent</th>
	                <!-- <th style="text-align: center">Confirmation Received</th> -->
	                <!-- <th style="text-align: center">Date of entry/update</th> -->
	                <th style="text-align: center">Controller Particulars</th>
	                <th style="text-align: center">Supporting Docs</th>
	                <!-- <th></th> -->
                </tr>
            </thead>
            <tbody id="confirmation_table_body_latest_controller">
			</tbody>
        </table>
	</div>
</div>
<script src="themes/default/assets/js/confirmation_register_controller.js?v=44eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
