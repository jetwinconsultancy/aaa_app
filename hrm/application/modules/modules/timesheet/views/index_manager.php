<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/handsontable/dist/handsontable.full.min.js"></script>
<link href="<?= base_url() ?>node_modules/handsontable/dist/handsontable.full.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>
<link href="<?= base_url() ?>node_modules/icheck-bootstrap/icheck-bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>

<style>
.timesheet_office span.select2.select2-container.select2-container--default{
    width: 112px !important;  
}
.timesheet_department span.select2.select2-container.select2-container--default{
    width: 145px !important;  
}
.timesheet_employee span.select2.select2-container.select2-container--default{
    width: 196px !important;  
}
.timesheet_month span.select2.select2-container.select2-container--default{
    width: 89px !important;  
}
.timesheet_year span.select2.select2-container.select2-container--default{
    width: 81px !important;  
}
.timesheet_status span.select2.select2-container.select2-container--default{
    width: 156px !important;  
}
</style>

<section class="panel" style="margin-top: 30px;">

 	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs" >
				<ul class="nav nav-tabs nav-justify">
                    <li class="check_state active" data-information="own" style="width: 25%">
                        <a href="#w2-own" data-toggle="tab" class="text-center">
                            <b>Own</b>
                        </a>
                    </li>
                    <li class="check_state" data-information="others" style="width: 25%">
                        <a href="#w2-others" data-toggle="tab" class="text-center">
                            <b>Others</b>
                        </a>
                    </li>
                </ul>
                <div class="tab-content clearfix">

                    <div id="w2-own" class="tab-pane active">
						<div class="panel-body">
							<div class="col-md-12">

								<div class="row datatables-header form-inline">
									<div style="text-align: right;">
					        			<a class="create_client themeColor_purple" href="timesheet/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Create Timesheet</a>
					        		</div>

						        	<hr>

						        	<div>
										<label style="display:block;" class="control-label"><strong>Records :</strong></label>
							        	<input style="display:block;" type="checkbox" name="record_filter1" style="width: 100%;"/>
							        </div>

									<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
										<table class="table" id="datatable-default1" style="width:100%">
											<thead>
												<tr style="background-color:white;">
													<th class="text-left">Timesheet no. </th>
													<th class="text-left">Month</th>
													<th class="text-left">Status</th>
												</tr>
											</thead>
											<tbody class="timesheet_table2">
												<?php 
													foreach($timesheet_list1 as $row)
										  			{
										  				echo '<tr class="timesheet_tr2">';
										  				// echo '<td><a href="timesheet/edit/'. $row->id .'">'. $row->timesheet_no .'</td>';
										  				echo '<td><a onclick="selectAssignment('.$row->id.');" style="cursor:pointer;">'. $row->timesheet_no .'</a></td>';
										  				echo '<td>'.date('F Y', strtotime($row->month)).'</td>';
										  				echo '<td id="status'.$row->id.'">'.$row->status_id.'</td>';
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

                    <div id="w2-others" class="tab-pane">
                    	<div class="panel-body">
							<div class="col-md-12">
		                    	<div class="row datatables-header form-inline">

					        		<div>
					        			<table>
					        				<tr>
					        					<td class="timesheet_office" style="padding-right: 25px">
					        						Offices :
										    		<?php 
										    			echo form_dropdown('office', $office, isset($office)?$office[0]:'', 'class="timesheet_office_filter"');
										    		?>	
						        				</td>

					        					<td class="timesheet_department" style="padding-right: 25px">
					        						Departments :
									        		<?php 
									        			echo form_dropdown('department', $department, isset($department)?$department[0]:'', 'class="timesheet_department_filter" ');
									        		?>
					        					</td>

					        					<td class="timesheet_employee" style="padding-right: 25px">
					        						<label class="control-label" style="display: block;">Employee :</label>
					    								<?php 
					    									echo form_dropdown('employee', $employee, isset($employee)?$employee:'', 'class="timesheet_employee_filter" multiple="multiple"');
					    								?>
					        					</td>

								                <td class="timesheet_month" style="padding-right: 10px">
									                Month :
									                <?php 
									                    echo form_dropdown('month', $month, isset($month)?date('m'):'', 'class="timesheet_month_filter" ');
									                ?>
								                </td>

								                <td class="timesheet_year" style="padding-right: 25px">
									                Year :
									                <?php 
									                	echo form_dropdown('years', $years, isset($years)?date('Y'):'', 'class="timesheet_year_filter" ');
									                ?>
								                </td>

					        					<td class="timesheet_status">
					        						Status :
					  					    		<?php 
					  					    			echo form_dropdown('status', $status, isset($status)?$status[0]:'', 'class="timesheet_status_filter" ');
					  					    		?>
					        					</td>
					        				</tr>
						        		</table>
					        		</div>

						        	<hr>

						        	<div>
										<label style="display:block;" class="control-label"><strong>Records :</strong></label>
							        	<input style="display:block;" type="checkbox" name="record_filter2" style="width: 100%;"/>
							        </div>

									<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
										<table class="table" id="datatable-default2" style="width:100%">
											<thead>
												<tr style="background-color:white;">
													<th class="text-left">Date</th>
													<th class="text-left">Employee</th>
													<th class="text-left">Status</th>
												</tr>
											</thead>
											<tbody class="timesheet_table">
												<?php 
													foreach($timesheet_list2 as $timesheet)
										  			{
										  				echo '<tr class="timesheet_tr" >';
										  				echo '<td><a href="'. base_url() .'timesheet/edit/'. $timesheet->id .'">'.date('F Y', strtotime($timesheet->month)).'</a></td>';
										  				echo '<td>'.$timesheet->employee_name.'</td>';
										  				echo '<td>'.$timesheet->status_id.'</td>';
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
                </div>
			</div>
		</div>
	</div>
	<div style="height: 75%; overflow: hidden;">
	<div id="timesheet_excel" hidden="true"></div>
	<!-- <div id="timesheet_excel"></div> -->
	</div>
</section>

<div id="select_assignment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"> Assignment Selection</h2>
			</header>
			<!-- <form id="proceed_edit_timesheet_form" method=POST > -->
				<body>
					<div style="display:block;padding-top:10px;padding-bottom: 10px;padding-left: 10px;padding-right: 10px;table-layout: fixed;">
						<table class="table table-bordered table-striped mb-none" id="datatable-selection" style="width:100%;">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left"></th>
									<th class="text-left">Client</th>
									<th class="text-left">Job Type</th>
									<th class="text-left">FYE</th>
								</tr>
							</thead>
							<tbody class="selection_table">
								<tr class="selection" ></tr>
							</tbody>
						</table>
					</div>
				</body>
				<div class="modal-footer">
					<div class='checkbox icheck-wisteria' style="float: left;text-align: left;"><input type='checkbox' name='select_all' id='select_all' onclick='All_Assignment_selection()'/><label for='select_all'><strong>Select All<strong></label></div>
					<button type="submit" id="proceed_btn" class="btn btn_purple">Proceed</button>
					<input type="button" id="cancel_btn" class="btn btn-default" data-dismiss="modal" value="Cancel">
				</div>
			<!-- </form> -->
		</div>
	</div>
</div>

<script>

var assignment_list;
var selected = [];
var timesheet_id;
var timesheet_assignment_list = [];

function selectAssignment(item){

	timesheet_id = item;
	var status = $('#status'+item).text();
	var timesheet_list = [];

	$.ajax({
		'async':false,
		type: "POST",
		url:  "timesheet/get_timesheet",
		data: { timesheet_id: timesheet_id },
		dataType: "json",
		success: function(data)
		{
			if(data[0]['content'] != '')
			{
				if(JSON.parse(data[0]['content'])!=null || JSON.parse(data[0]['content'])!="")
				{
					timesheet_list = JSON.parse(data[0]['content']);
				}

				if(timesheet_list != [])
				{
					for(var a = 0 ; a < timesheet_list.length ; a++)
					{
						var temp = [];

						if(timesheet_list[a][0].includes('*'))
						{
							temp.push(timesheet_list[a][0]);
							temp.push(timesheet_list[a][1]);
							temp.push(timesheet_list[a][2]);
							timesheet_assignment_list.push(temp);
						}
					}
				}
			}
		}
	});

	if(status == 'Work on Progress')
	{
		$("#select_assignment").modal("show");

		$.ajax({
			'async':false,
			type: "POST",
			url:  "timesheet/select_assignment",
			data: '&timesheet_id=' + timesheet_id ,
			success: function(data)
			{
				assignment_list = JSON.parse(data);

				if(JSON.parse(data)==null || JSON.parse(data)==""){
           			var table  = $("#datatable-selection").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
           		}

				if(JSON.parse(data)!=null || JSON.parse(data)!="")
				{
					var object = (JSON.parse(data));

					$(".selection").remove();

					for(var i=0; i<object.length; i++){

						var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
		  				if(object[i]['FYE'] != null){

		  					var date = new Date(object[i]['FYE']);
							var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
		  				}
		  				else{
		  					var date_format = "";
		  				}

						var rowHtml = "<tr class='selection'><td style='text-align:center;'><div class='checkbox icheck-wisteria'><input type='checkbox' class='checkbox' name="+object[i]['assignment_id']+" id='"+object[i]['assignment_id']+"' onclick='Assignment_selection("+JSON.stringify(object[i]['assignment_id'])+");'/><label for='"+object[i]['assignment_id']+"'></label></div></td><td>"+object[i]['client_name']+"</td><td>"+object[i]['job']+"</td><td>"+date_format+"</td></tr>";

						$(".selection_table").append(rowHtml);

						if(timesheet_assignment_list != []){

							for(var b = 0 ; b < timesheet_assignment_list.length ; b++)
							{
								var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
				  				if(object[i]['FYE'] != null){

				  					var date = new Date(object[i]['FYE']);
									var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
				  				}
				  				else{
				  					var date_format = "";
				  				}
								if(timesheet_assignment_list[b][0] == object[i]['client_name'] && timesheet_assignment_list[b][1] == object[i]['job'] && timesheet_assignment_list[b][2] == date_format)
								{
									$('input[name^="'+object[i]['assignment_id']+'"]').each(function() {
							            this.checked = true;                        
							        });

							        Assignment_selection(object[i]['assignment_id']);
								}
							}
						}
					}

					if(timesheet_assignment_list.length === object.length)
					{
						$('input[name^="select_all"]').each(function() {
				            this.checked = true;                        
				        });
					}

					$('#datatable-selection').DataTable( {
						"order": [],
						"autoWidth" : false
					});
				}
			}
		});
	}
	else
	{
		window.location.assign("timesheet/edit/"+timesheet_id);
	}
}

$("#cancel_btn").click(function(){
	location.reload();
});

function All_Assignment_selection(item){

	selected = [];

	$("#datatable-selection").DataTable().destroy();

	$('input[name^="select_all"]').each(function()
	{
        if($(this).prop("checked") == true)
        {
        	for($a=0 ; $a<assignment_list.length ; $a++)
			{
	           $('input[name^="'+assignment_list[$a]['assignment_id']+'"]').each(function() {
		            this.checked = true;                        
		        });

	           Assignment_selection(assignment_list[$a]['assignment_id']);
	       	}
        }
        else
        {
	        for($a=0 ; $a<assignment_list.length ; $a++)
			{
	           $('input[name^="'+assignment_list[$a]['assignment_id']+'"]').each(function() {
		            this.checked = false;                        
		        });

	           Assignment_selection(assignment_list[$a]['assignment_id']);
	       	}
        }
    });

    $('#datatable-selection').DataTable( {
		"order": [],
		"autoWidth" : false
	});
}

function Assignment_selection(item){

	$('input[name^="'+item+'"]').each(function()
	{
        if($(this).prop("checked") == true)
        {
            selected.push(item);
        }
        else
        {
        	for($i=0;$i<selected.length;$i++)
			{
				if(selected[$i] == item)
				{
					selected.splice($i,1);
				}
			}
        }
    });
}

$("#proceed_btn").click(function(){

	$.ajax({
		'async':false,
		type: "POST",
		url:  "timesheet/edit1",
		data: { timesheet_id: timesheet_id , assignment: JSON.stringify(selected) },
		dataType: "json",
		success: function(data)
		{
			window.location.assign("timesheet/edit/"+timesheet_id);
		}
	});

});

// HANDSONTABLE INITIAL
// var hot = new Handsontable(container, {
// 	data: get_data(),
// 	fixedColumnsLeft: 3,
// 	minRows: 5,
// 	minCols: (JSON.parse(header)).length,
// 	rowHeaders: true,
// 	colHeaders: true,
// 	filters: true,
// 	contextMenu: true,
// 	dropdownMenu: true,
// 	colWidths: [300,200,100],
// 	rowHeights: 30,
// 	manualColumnResize: false,
// 	manualRowResize: false,
// 	colHeaders: JSON.parse(header),
// 	columns: JSON.parse(array_header_col_readonly),
// 	columnSummary: [
// 		{
// 		  destinationRow: 0,
// 		  destinationColumn: 0,
// 		  reversedRowCoords: true,
// 		  type: 'sum',
// 		  forceNumeric: true
// 		}
// 	]
// });

var active_tab = "own";
var date = new Date();

$(document).on('click',".check_state",function(){
	var index_tab_aktif = $(this).data("information");
	active_tab = index_tab_aktif;
});

$('.timesheet_employee_filter').multiselect({
  allSelectedText: 'All',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  maxHeight: 200,
  includeSelectAllOption: true
});

$(".timesheet_employee_filter").multiselect('selectAll', false);
$(".timesheet_employee_filter").multiselect('updateButtonText');

window.onbeforeunload = function() {
	localStorage.setItem("record_filter1", $("[name='record_filter1']").is(":checked"));

	localStorage.setItem("office", $('.timesheet_office_filter').val());
	localStorage.setItem("department", $('.timesheet_department_filter').val());
	localStorage.setItem("employee", $('.timesheet_employee_filter').val());
	localStorage.setItem("month", $('.timesheet_month_filter').val());
	localStorage.setItem("year", $('.timesheet_year_filter').val());
	localStorage.setItem("status", $('.timesheet_status_filter').val());

	localStorage.setItem("active_tab", active_tab);
}

window.onload = function() {

	$record_filter1 = localStorage.getItem("record_filter1");

	if($record_filter1 === "true"){
		$("[name='record_filter1']").bootstrapSwitch('state',1);
	} else {
		$("[name='record_filter1']").bootstrapSwitch('state',0);
	}

	$office        = localStorage.getItem("office");
	$department    = localStorage.getItem("department");
	$employee      = localStorage.getItem("employee");
	$month         = localStorage.getItem("month");
	$year          = localStorage.getItem("year");
	$status        = localStorage.getItem("status");

	if( $office == null || $office == 'null' || $office == 'undefined' ){
		$('.timesheet_office_filter').val(0);
	} else if($office != null || $office != "") {
		$('.timesheet_office_filter').val($office);
	}

	if( $department == null || $department == 'null' || $department == 'undefined' ){
		$('.timesheet_department_filter').val(0);
	} else if($department != null || $department != "") {
		$('.timesheet_department_filter').val($department);
	}

	if( $employee == null || $employee == 'null' || $employee == 'undefined' ){
	    $('.timesheet_employee_filter').val('multiselect-all');
	    $(".timesheet_employee_filter").multiselect("refresh");
	} else if($employee != null || $employee != "") {
		console.log($employee)
	    $employee_arr = $employee.split(',');  
	    $('.timesheet_employee_filter').val($employee_arr);
	    $(".timesheet_employee_filter").multiselect("refresh");
	}

	if( $month == null || $month == 'null' || $month == 'undefined' ){
		$('.timesheet_month_filter').val(date.getMonth()+1);
	} else if($month != null || $month != "") {
		$('.timesheet_month_filter').val($month);
	}

	if( $year == null || $year == 'null' || $year == 'undefined' ){
		$('.timesheet_year_filter').val(date.getFullYear());
	} else if($year != null || $year != "") {
		$('.timesheet_year_filter').val($year);
	}

	if( $status == null || $status == 'null' || $status == 'undefined' ){
		$('.timesheet_status_filter').val(0);
	} else if($status != null || $status != "") {
		$('.timesheet_status_filter').val($status);
	}

	$('.timesheet_office_filter').select2();
	$('.timesheet_department_filter').select2();
	$('.timesheet_month_filter').select2();
	$('.timesheet_year_filter').select2();
	$('.timesheet_status_filter').select2();
	$(".timesheet_status_filter").trigger('change');

	$active_tab = localStorage.getItem("active_tab");

	if($active_tab == null || $active_tab == 'null' || $active_tab == 'undefined' ){
		$active_tab = "own";
	} 
	if($active_tab != null || $active_tab != "") {
		$(document).ready(function () {
			if($active_tab == "own") {  
		        $('li[data-information="'+$active_tab+'"]').addClass("active");
		        $('#w2-'+$active_tab+'').addClass("active");
		        $('li[data-information="others"]').removeClass("active");
		        $('#w2-others').removeClass("active");
			} else {
				$('li[data-information="'+$active_tab+'"]').addClass("active");
		        $('#w2-'+$active_tab+'').addClass("active");
		        $('li[data-information="own"]').removeClass("active");
		        $('#w2-own').removeClass("active");
			}
		});
	}

	active_tab = $active_tab
}

$("[name='record_filter1']").bootstrapSwitch({
    // state: <?php // echo isset($staff[0]->aws_given)? $staff[0]->aws_given : 0 ?>,
    size: 'normal',
    onColor: 'purple',
    onText: 'Old',
    offText: 'New',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '75px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'
});

$("[name='record_filter2']").bootstrapSwitch({
    // state: <?php // echo isset($staff[0]->aws_given)? $staff[0]->aws_given : 0 ?>,
    size: 'normal',
    onColor: 'purple',
    onText: 'Old',
    offText: 'New',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '75px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'
});

function get_data(){
	return [];
}

// DATATABLE INITIAL
$(document).ready( function () {
	$('#datatable-default1').DataTable( {
		"order": [],
		"autoWidth" : false,
		"bStateSave": true,
	} );
})

$(document).ready( function () {
	$('#datatable-default2').DataTable( {
		"order": [],
		"autoWidth" : false,
		"bStateSave": true,
	} );
})

// FILTER ------------------------------------------------------------------------------------------------------------------------------------------//
// OFFICES FILTER
$(document).on('change',".timesheet_office_filter",function(){
	var office     = $(".timesheet_office_filter").val();
	var department = $(".timesheet_department_filter").val();
	var employee   = $(".timesheet_employee_filter").val();
	var month      = $(".timesheet_month_filter").val();
	var year       = $(".timesheet_year_filter").val();
	var status     = $(".timesheet_status_filter").val();

	$.ajax({
       type: "POST",
       url:  "timesheet/timesheet_filter",
       data: '&office=' + office + '&department=' + department + '&employee=' + employee + '&month=' + month + '&year=' + year + '&status=' + status,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default2").DataTable().destroy();
       			var table  = $("#datatable-default2").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default2").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default2').DataTable( {
				"order": [],
				"autoWidth" : false,
				"bStateSave": true,
			} );
       }
   	});
});
// DEPARTMENTS FILTER
$(document).on('change',".timesheet_department_filter",function(){
	var office     = $(".timesheet_office_filter").val();
	var department = $(".timesheet_department_filter").val();
	var employee   = $(".timesheet_employee_filter").val();
	var month      = $(".timesheet_month_filter").val();
	var year       = $(".timesheet_year_filter").val();
	var status     = $(".timesheet_status_filter").val();

	$.ajax({
       type: "POST",
       url:  "timesheet/timesheet_filter",
       data: '&office=' + office + '&department=' + department + '&employee=' + employee + '&month=' + month + '&year=' + year + '&status=' + status,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default2").DataTable().destroy();
       			var table  = $("#datatable-default2").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default2").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default2').DataTable( {
				"order": [],
				"autoWidth" : false,
				"bStateSave": true,
			} );
       }
   	});
});
// EMPLOYEE FILTER
$(document).on('change',".timesheet_employee_filter",function(){
	var office     = $(".timesheet_office_filter").val();
	var department = $(".timesheet_department_filter").val();
	var employee  = $(".timesheet_employee_filter").val();
	var month     = $(".timesheet_month_filter").val();
	var year      = $(".timesheet_year_filter").val();
	var status    = $(".timesheet_status_filter").val();

	$.ajax({
       type: "POST",
       url:  "timesheet/timesheet_filter",
       data: '&office=' + office + '&department=' + department + '&employee=' + employee + '&month=' + month + '&year=' + year + '&status=' + status,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default2").DataTable().destroy();
       			var table  = $("#datatable-default2").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default2").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default2').DataTable( {
				"order": [],
				"autoWidth" : false,
				"bStateSave": true,
			} );
       }
   	});
});
// MONTH FILTER
$(document).on('change',".timesheet_month_filter",function(){
	var office     = $(".timesheet_office_filter").val();
	var department = $(".timesheet_department_filter").val();
	var employee  = $(".timesheet_employee_filter").val();
	var month     = $(".timesheet_month_filter").val();
	var year      = $(".timesheet_year_filter").val();
	var status    = $(".timesheet_status_filter").val();

	$.ajax({
       type: "POST",
       url:  "timesheet/timesheet_filter",
       data: '&office=' + office + '&department=' + department + '&employee=' + employee + '&month=' + month + '&year=' + year + '&status=' + status,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default2").DataTable().destroy();
       			var table  = $("#datatable-default2").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default2").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default2').DataTable( {
				"order": [],
				"autoWidth" : false,
				"bStateSave": true,
			} );
       }
   	});
});
// YEAR FILTER
$(document).on('change',".timesheet_year_filter",function(){
	var office     = $(".timesheet_office_filter").val();
	var department = $(".timesheet_department_filter").val();
	var employee  = $(".timesheet_employee_filter").val();
	var month     = $(".timesheet_month_filter").val();
	var year      = $(".timesheet_year_filter").val();
	var status    = $(".timesheet_status_filter").val();

	$.ajax({
       type: "POST",
       url:  "timesheet/timesheet_filter",
       data: '&office=' + office + '&department=' + department + '&employee=' + employee + '&month=' + month + '&year=' + year + '&status=' + status,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default2").DataTable().destroy();
       			var table  = $("#datatable-default2").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default2").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default2').DataTable( {
				"order": [],
				"autoWidth" : false,
				"bStateSave": true,
			} );
       }
   	});
});
// STATUS FILTER
$(document).on('change',".timesheet_status_filter",function(){
	var office     = $(".timesheet_office_filter").val();
	var department = $(".timesheet_department_filter").val();
	var employee  = $(".timesheet_employee_filter").val();
	var month     = $(".timesheet_month_filter").val();
	var year      = $(".timesheet_year_filter").val();
	var status    = $(".timesheet_status_filter").val();

	$.ajax({
       type: "POST",
       url:  "timesheet/timesheet_filter",
       data: '&office=' + office + '&department=' + department + '&employee=' + employee + '&month=' + month + '&year=' + year + '&status=' + status,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default2").DataTable().destroy();
       			var table  = $("#datatable-default2").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default2").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default2').DataTable( {
				"order": [],
				"autoWidth" : false,
				"bStateSave": true,
			} );
       }
   	});
});

$("[name='record_filter1']").on('switchChange.bootstrapSwitch', function(event, state) {

		$.ajax({
	       type: "POST",
	       url:  "timesheet/record_filter",
	       data: '&result=' + state,
	       success: function(data)
	       {

	       		if(JSON.parse(data)==null || JSON.parse(data)==""){
	       			$("#datatable-default1").DataTable().destroy();
	       			var table  = $("#datatable-default1").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
	       		}

	       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
	       			$("#datatable-default1").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".timesheet_tr2").remove();
	           		var http = '<?php echo base_url() ?>';
	           		// console.log(object);

	           		for(var i=0; i<object.length; i++){
	           			var rowHtml = "<tr class='timesheet_tr2' ><td><a onclick='selectAssignment("+ object[i]['id'] +");' style='cursor:pointer;'>"+ object[i]['timesheet_no'] +"</a></td>><td>"+moment(object[i]['month']).format('MMMM YYYY')+"</td><td id='status"+ object[i]['id'] +"'>"+ object[i]['status_id'] +"</td>/tr>";

	           			$(".timesheet_table2").append(rowHtml);
	           		}

	       		}

	       		$('#datatable-default1').DataTable( {
					"order": [],
					"autoWidth" : false,
					"bStateSave": true,
				} );
	       }
	   	});

	});

$("[name='record_filter2']").on('switchChange.bootstrapSwitch', function(event, state) {

	$.ajax({
       type: "POST",
       url:  "timesheet/record_filter_admin",
       data: '&result=' + state,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default2").DataTable().destroy();
       			var table  = $("#datatable-default2").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default2").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';
           		// console.log(object);

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['employee_name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default2').DataTable( {
				"order": [],
				"autoWidth" : false,
				"bStateSave": true,
			} );
       }
   	});

});
// FILTER ------------------------------------------------------------------------------------------------------------------------------------------//

// TIMESHEET SUBMITTION NOTIFICATION
// $(document).ready( function (){
// 	$.post("timesheet/Submition_Notification", {  }, function(data, status){

//     });
// });
// TIMESHEET SUBMITTION NOTIFICATION ---------------------------------------------------------------------------------------------------------------//

</script>