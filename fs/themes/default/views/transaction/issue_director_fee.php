<div id="w2-issue_director_fee_form" class="panel">
	<h3>Issue Director Fee</h3>
	<form id="issue_director_fee_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control transaction_issue_director_fee_id" id="transaction_issue_director_fee_id" name="transaction_issue_director_fee_id" value=""/>
		<div class="form-group">
			<label class="col-sm-2" for="w2-declare_of_fye">Declare of FYE: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid declare_of_fye" id="date_todolist" name="declare_of_fye" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-resolution_date">Resolution Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid resolution_date" id="date_todolist" name="resolution_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-meeting_date">Meeting Date: </label>
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
			<label class="col-sm-2" for="w2-notice_date">Notice Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid notice_date" id="date_todolist" name="notice_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id="latest_director_table" style="width: 750px">
			<thead>
				<tr>
					<!-- <th id="id_position" style="text-align: center;width:170px">Position</th> -->
					<th id="id_header" style="text-align: center;width:270px">ID</th>
					<th style="text-align: center;width:270px" id="id_name">Director Name</th>
					<th style="text-align: center;width:270px" id="id_name">Date of Appointment</th>
					<th style="text-align: center;width:270px">Currency</div>
					<th style="text-align: center;width:270px">Fee</div>
				</tr>
				
			</thead>
			<tbody id="body_issue_director_fee">
			</tbody>
			
		</table>

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitIssueDirectorFeeInfo" id="submitIssueDirectorFeeInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>