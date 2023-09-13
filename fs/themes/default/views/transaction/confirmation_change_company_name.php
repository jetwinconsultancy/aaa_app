<div id="transaction_confirmation_change_company_name">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">New Company Name</span><a href="javascript:void(0)" class="btn btn-primary edit_change_company_name" id="edit_change_company_name"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="change_of_company_name_interface">
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Company Name: </label>
			<div class="col-xs-8">
				<div class="new_company_name" id="new_company_name"></div>
			</div>
		</div>
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
<script src="themes/default/assets/js/confirmation_change_company_name.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>