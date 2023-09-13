<div id="w2-officer" class="panel appoint_auditor">
	<h3>Appointment of Auditor</h3>
	<form id="apointment_of_auditor_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Meeting Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid meeting_date" id="date_todolist" name="meeting_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Notice Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid notice_date" id="date_todolist" name="notice_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
			<thead>
				<tr>
					<th id="id_position" style="text-align: center;width:170px">Position</th>
					<th id="id_header" style="text-align: center;width:270px">ID</th>
					<th style="text-align: center;width:270px" id="id_name">Name</th>
					<!-- <th style="text-align: center;width:170px">Date Of Appointment</div> -->
					<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="appoint_new_auditor_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a></th>
				</tr>
				
			</thead>
			<tbody id="body_appoint_new_auditor">
			</tbody>
		</table>
		<h3>Resignation of Auditor</h3>
		<div id="resign_auditor_table">
			<table class="table table-bordered table-striped table-condensed mb-none resign_auditor" id="officer_table" style="width: 1010px">
				<thead>
					<tr>
						<th id="id_position" style="text-align: center;width:170px">Position</th>
						<th id="id_header" style="text-align: center;width:170px">ID</th>
						<th style="text-align: center;width:170px" id="id_name">Name</th>
						<th style="text-align: center;width:170px">Date Of Appointment</th>
						<th style="text-align: center;width:170px">Date Of Cessation</th>
						<th style="text-align: center;width:220px">Reason</th>
						<th></th>
					</tr>
					
				</thead>
				<tbody id="body_resign_auditor">
				</tbody>
				
			</table>
		</div>
		<div id="no_auditor_resign">
			<div>
				<span class="help-block">
					* No auditor can resign.
				</span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitAuditorInfo" id="submitAuditorInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>