<div id="w2-officer" class="panel">
	<h3>Resign of Director</h3>
	<form id="resign_of_director_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1010px">
			<thead>
				<tr>
					<th id="id_position" style="text-align: center;width:170px">Position</th>
					<th id="id_header" style="text-align: center;width:170px">ID</th>
					<th style="text-align: center;width:170px" id="id_name">Name</th>
					<th style="text-align: center;width:170px">Date Of Appointment</div>
					<th style="text-align: center;width:170px">Date Of Cessation</div>
					<th style="text-align: center;width:220px">Reason</div>
					<th><!-- <a href="javascript: void(0);" style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="resign_director_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a> --></th>
				</tr>
				
			</thead>
			<tbody id="body_resign_director">
			</tbody>
			
		</table>
		<div id="appoint_new_director" style="display:none">
			<h3>Appointment of Director</h3>
			<div>
				<span class="help-block">
					* Must have one director in this company.
				</span>
			</div>
			<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
				<thead>
					<tr>
						<th id="id_position" style="text-align: center;width:170px">Position</th>
						<th id="id_header" style="text-align: center;width:270px">ID</th>
						<th style="text-align: center;width:270px" id="id_name">Name</th>
						<!-- <th style="text-align: center;width:170px">Date Of Appointment</div> -->
						<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="appoint_new_director_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a></th>
					</tr>
					
				</thead>
				<tbody id="body_appoint_new_director">
				</tbody>
				
			</table>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitResignDirectorInfo" id="submitResignDirectorInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>