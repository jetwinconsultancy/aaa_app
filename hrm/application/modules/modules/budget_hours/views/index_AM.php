<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<section role="main" class="content_section">

	<header class="panel-heading">
		<div class="form-group">

			<div class="col-sm-6 form-inline" style="width: 170px">
				Departments :
                <?php 
					echo form_dropdown('report_department', $department, isset($department)?$department[0]:'', 'class="report_department"');
				?>
			</div>
			<div class="col-sm-6 form-inline" style="width: 400px">
				Date Range:
				<div class="input-daterange input-group report_dateRange" data-plugin-datepicker data-date-format="dd/mm/yyyy">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" style="text-align: center;" class="form-control report_dateFrom" name="from" value="" placeholder="From" autocomplete="off">
					<span class="input-group-addon">to</span>
					<input type="text" style="text-align: center;" class="form-control report_dateTo" name="to" value="" placeholder="To" autocomplete="off">
				</div>
			</div>
			<div class="col-sm-6 form-inline" style="width: 385px">
				Status:
                <?php 
					echo form_dropdown('report_status', $assignment_status, isset($assignment_status)?$assignment_status[0]:'', 'class="report_status"');
				?>
			</div>
			<div style="padding-top:17.5px">
				<button class="btn btn_purple generate_report">Generate Excel</button>
			</div>
		</div>
	</header>

	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs">
				<ul class="nav nav-tabs nav-justify">
					<li class="check_state active" data-information="in_progress" style="width: 25%">
						<a href="#w2-in_progress" data-toggle="tab" class="text-center">
							<b>In Progress</b>
						</a>
					</li>
					<li class="check_state" data-information="completed" style="width: 25%">
						<a href="#w2-completed" data-toggle="tab" class="text-center">
							<b>Completed</b>
						</a>
					</li>
					<li class="check_state" data-information="BvsA" style="width: 25%">
						<a href="#w2-BvsA" data-toggle="tab" class="text-center">
							<b>Budget VS Actual</b>
						</a>
					</li>
				</ul>
				<div class="tab-content clearfix">
					<div id="w2-in_progress" class="tab-pane active">
						<div class="col-sm-12 col-md-12">
							<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
								<table class="table budget_table" id="datatable-budget_table" style="table-layout: fixed;width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">Assingment No.</th>
											<th class="text-left">Client Name</th>
											<th class="text-left">Budget Hours</th>
											<th class="text-left">Log</th>
										</tr>
									</thead>
									<tbody>
										<?PHP
											foreach ($assignment_list as $result){
												echo '<tr>';
								  				echo '<td><a href="budget_hours/edit/'. $result->assignment_id .'">'. $result->assignment_id .'</td>';
								  				echo '<td>'. $result->client_name .'</td>';
								  				echo '<td>'. $result->budget_hour .'</td>';
								  				echo '<td><button  class="btn btn_purple" title="Log" data-id="'.$result->assignment_id.'" id="show_log"><i class="fas fa-search"></i></button></td>';
								  				echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="w2-completed" class="tab-pane">
						<div class="col-sm-12 col-md-12">
							<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
								<table class="table complete_budget_table" id="datatable-complete_budget_table" style="table-layout: fixed;width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">Assingment No.</th>
											<th class="text-left">Client Name</th>
											<th class="text-left">Budget Hours</th>
											<th class="text-left">Log</th>
										</tr>
									</thead>
									<tbody>
										<?PHP
											foreach ($completed_assignment_list as $result) {
												echo '<tr>';
								  				echo '<td><a href="budget_hours/edit/'. $result->assignment_id .'">'. $result->assignment_id .'</td>';
								  				echo '<td>'. $result->client_name .'</td>';
								  				echo '<td>'. $result->budget_hour .'</td>';
								  				echo '<td><button  class="btn btn_purple" title="Log" data-id="'.$result->assignment_id.'" id="show_log"><i class="fas fa-search"></i></button></td>';
								  				echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="w2-BvsA" class="tab-pane">
						<div class="col-sm-12 col-md-12">
							<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
								<div class="form-group">
									<label class="col-xs-3" for="w2-show_all">Employee Name: </label>
									<div class="col-sm-6 form-inline" style="width: 300px">
										<div class="">
	                                        <?php 
		    									echo form_dropdown('employee', $employee, isset($employee)?$employee[0]:'', 'class="bvsa_employee" ');
		    								?>
	                                    </div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-3" for="w2-show_all">Completed Date Range: </label>
									<div class="col-sm-6 form-inline" style="width: 550px">
										<div class="input-daterange input-group bvsa_date" data-plugin-datepicker data-date-format="dd/mm/yyyy">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" style="text-align: center;" class="form-control bvsa_date_from" name="from" value="" placeholder="From">
											<span class="input-group-addon">to</span>
											<input type="text" style="text-align: center;" class="form-control bvsa_date_to" name="to" value="" placeholder="To">
										</div>
									</div>
								</div>
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple submit_bvsa">Check</button>
					                        	<a class="btn pull-right btn_cancel cancel_bvsa">Clear</a>
					                        </div>
					                    </div>
					                </div>
					            </div>

								<hr>
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple generateExcel_bvsa">Generate Excel</button>
					                        </div>
					                    </div>
					                </div>
					            </div>
								<table class="table bvsa" id="bvsa" style="table-layout: fixed;width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">Assignment ID</th>
											<th class="text-left">Client Name</th>
											<th class="text-left">Complete Date</th>
											<th class="text-left">Actual Hours</th>
											<th class="text-left">Budget Hours</th>
										</tr>
									</thead>
									<tbody class="bvsa_tbody"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div id="log" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Budget Hours Log</h2>
			</header>
			<form id="log">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden" name="budget_hours_log_id" class="budget_hours_log_id" value="">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left" style="width: 10%">ID</th>
									<th class="text-left" style="width: 15%">Date</th>
									<th class="text-left">Log</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<!-- <button type="submit" id ="generate_log_Excel" class="btn btn_purple" >Generate Excel</button> -->
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>

	$('.bvsa_employee').select2();
	$('.report_status').select2();
	$('.report_department').select2();

	//DATATABLE INITIAL
	$(document).ready( function (){
	    $('.budget_table').DataTable( {
	    	"order": []
	    } );
	});

	$(document).ready( function (){
	    $('.complete_budget_table').DataTable( {
	    	"order": []
	    } );
	});

	$(document).ready( function (){
	    $('.bvsa').DataTable( {
	    	"order": []
	    } );
	});

	$(".cancel").click(function(){
		var tableRef = document.getElementById('log').getElementsByTagName('tbody')[0];
		tableRef.remove();
	});

	var userTarget = "";
	var exit = false;
	$('.input-daterange').datepicker({
	  format: "dd MM yyyy",
	  weekStart: 1,
	  language: "en",
	  startDate: "01/01/1957",
	  orientation: "bottom auto",
	  autoclose: true,
	  showOnFocus: true,
	  keepEmptyValues: true,
	});
	$('.input-daterange').focusin(function(e) {
	  userTarget = e.target.name;
	});
	$('.input-daterange').on('changeDate', function(e) {
	  if (exit) return;
	  if (e.target.name != userTarget) {
	    exit = true;
	    $(e.target).datepicker('clearDates');
	  }
	  exit = false;
	});

	//Show Log
	$(document).on('click',"#show_log",function(e){
		var assignment_id =  $(this).data("id");
		$("#log").modal("show");

		$.ajax({
	       type: "POST",
	       url: "budget_hours/show_log",
	       data: '&assignment_id=' + assignment_id,
	       success: function(data)
	       {
	       		$('.table').append('<tbody></tbody>');

	       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
	           		var object = (JSON.parse(data));
	           		for(var i=0; i<object.length; i++){
	           			$('.assignment_log_id').val(object[i]["assignment_id"]);

	           			var tableRef = document.getElementById('log').getElementsByTagName('tbody')[0];

						// Insert a row in the table at the last row
						var newRow   = tableRef.insertRow();

						// Insert a cell in the row at index 0
						var newCell  = newRow.insertCell(0);
						var newCell2 = newRow.insertCell(1);
						var newCell3 = newRow.insertCell(2);

						// Append a text node to the cell
						var newText  = document.createTextNode(object[i]["assignment_id"]);
						newCell.appendChild(newText);
						var newText2  = document.createTextNode(object[i]["date"]);
						newCell2.appendChild(newText2);
						var newText3  = document.createTextNode(object[i]["budget_log"]);
						newCell3.appendChild(newText3);

	           		}
	       		}
	       }
	   	});
	});

	$(document).on('click',".submit_bvsa",function(e){

		$("#loadingmessage").show();

		$.ajax({
	       type: "POST",
	       url: "budget_hours/get_employee_bvsa_data",
	       data: '&employee_id=' + $(".bvsa_employee").val() + '&from=' + $(".bvsa_date_from").val() + '&to=' + $(".bvsa_date_to").val(),
	       success: function(data)
	       {
	       		$("#loadingmessage").hide();

	       		if(data == "" || data == "[]")
	       		{
           			$(".bvsa").DataTable().destroy();
	       			var table  = $(".bvsa").DataTable();
					table.clear().draw();
           		}

				if(data != "" && data != "[]")
				{
					var table = $(".bvsa").DataTable();
	           		var object = (JSON.parse(data));
					table.clear();

	    			for(var i=0; i<object.length; i++)
	    			{
	           			var rowHtml = "<tr><td>"+object[i]['assignment_id']+"</td><td>"+object[i]['client_name']+"</td><td>"+moment(object[i]["complete_date"]).format('DD MMMM YYYY')+"</td><td>"+object[i]['actual_hour']+"</td><td>"+object[i]['budget_hour']+"</td></tr>";
	           			table.row.add($(rowHtml)).draw();
	           		}
				}
	       }
	   	});
	});

	$(document).on('click',".cancel_bvsa",function(e){
		$('.bvsa_employee').val(0).trigger('change');
		$('.bvsa_date_from').val('');
		$('.bvsa_date_to').val('');

		$(".bvsa").DataTable().destroy();
		var table  = $(".bvsa").DataTable();
		table.clear().draw();
	});

	$(document).on('click',".generateExcel_bvsa",function(e){

		$.ajax({
	       type: "POST",
	       url: "budget_hours/generate_excel_bvsa",
	       data: '&employee_id=' + $(".bvsa_employee").val() + '&from=' + $(".bvsa_date_from").val() + '&to=' + $(".bvsa_date_to").val(),
	       success: function(response, data)
	       {
	       		if(response != "")
	       		{
	       			toastr.success('Excel File Generated', 'Successful');
           			window.open(response,'_blank');
	       		}
	       }
	   	});

	});

	$(document).on('click',".generate_report",function(e){
		$("#loadingmessage").show();
		$.ajax({
	       type: "POST",
	       url: "budget_hours/generate_report",
	       data: '&status=' + $(".report_status").val() + '&report_dateFrom=' + $(".report_dateFrom").val() + '&report_dateTo=' + $(".report_dateTo").val() + '&report_department=' + $(".report_department").val(),
	       success: function(response, data)
	       {
	       		if(response != "")
	       		{
	       			$("#loadingmessage").hide();
	       			toastr.success('Excel File Generated', 'Successful');
           			window.open(response,'_blank');
	       		}
	       }
	   	});
	});

</script>