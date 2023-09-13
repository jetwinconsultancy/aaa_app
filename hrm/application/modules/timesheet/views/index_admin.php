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
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>

<section class="panel" style="margin-top: 30px;">

 	<div class="panel-body">
		<div class="col-md-12">
			<div class="row datatables-header form-inline">

        		<div>
        			<table>
        				<tr>
        					<td style="padding-right: 25px">
        						Offices :
					    		<?php 
					    			echo form_dropdown('office', $office, isset($office)?$office[0]:'', 'class="timesheet_office_filter"');
					    		?>	
	        				</td>

        					<td style="padding-right: 25px">
        						Departments :
				        		<?php 
				        			echo form_dropdown('department', $department, isset($department)?$department[0]:'', 'class="timesheet_department_filter" ');
				        		?>
        					</td>

        					<td style="padding-right: 25px">
                    <label class="control-label" style="display: block;">Employee :</label>
    								<?php 
    									echo form_dropdown('employee', $employee, isset($employee)?$employee:'', 'class="timesheet_employee_filter" multiple="multiple"');
    								?>
        					</td>

                  <td style="padding-right: 10px">
                    Month :
                    <?php 
                      echo form_dropdown('month', $month, isset($month)?date('m'):'', 'class="timesheet_month_filter" ');
                    ?>
                  </td>

                  <td style="padding-right: 25px">
                    Year :
                    <?php 
                      echo form_dropdown('years', $years, isset($years)?date('Y'):'', 'class="timesheet_year_filter" ');
                    ?>
                  </td>

        					<td>
        						Status :
  					    		<?php 
  					    			echo form_dropdown('status', $status, isset($status)?$status[0]:'', 'class="timesheet_status_filter" ');
  					    		?>
        					</td>

                  <td>
                    <div style="float: left;padding-top:18.5px;padding-left:25px;">
                      <button class="btn btn_purple" onclick="generate_PDF()">Generate PDF</button>
                    </div>
                  </td>
        				</tr>
	        		</table>
        		</div>

	        	<hr>

	        	<div style="text-align: left;">
					<label style="display:block;" class="control-label"><strong>Records :</strong></label>
		        	<input style="display:block;" type="checkbox" id="record_filter" name="record_filter" style="width: 100%;"/>
		        </div>

				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Date</th>
								<th class="text-left">Employee</th>
								<th class="text-left">Status</th>
							</tr>
						</thead>
						<tbody class="timesheet_table">
							<?php 
								foreach($timesheet_list as $timesheet)
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
	<div style="height: 75%; overflow: hidden;">
	<div id="timesheet_excel" hidden="true"></div>
	<!-- <div id="timesheet_excel"></div> -->
	</div>
</section>

<script>
var header = '<?php echo json_encode($header); ?>';
var array_header_col_readonly = '<?php echo json_encode($array_header_col_readonly); ?>';
var container = document.getElementById('timesheet_excel');
var holiday = <?php echo json_encode(isset($holiday)?$holiday:"") ?>;
var third_working_date = '<?php echo $third_working_date; ?>';
var this_month_leave = '<?php echo json_encode(isset($this_month_leave)?$this_month_leave:"") ?>';
var date = new Date();

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
  localStorage.setItem("office", $('.timesheet_office_filter').val());
  localStorage.setItem("department", $('.timesheet_department_filter').val());
  localStorage.setItem("employee", $('.timesheet_employee_filter').val());
  localStorage.setItem("month", $('.timesheet_month_filter').val());
  localStorage.setItem("year", $('.timesheet_year_filter').val());
  localStorage.setItem("status", $('.timesheet_status_filter').val());
}

window.onload = function() {
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
}

// HANDSONTABLE INITIAL
var hot = new Handsontable(container, {
	data: get_data(),
	fixedColumnsLeft: 3,
	minRows: 5,
	minCols: (JSON.parse(header)).length,
	rowHeaders: true,
	colHeaders: true,
	filters: true,
	contextMenu: true,
	dropdownMenu: true,
	colWidths: [300,200,100],
	rowHeights: 30,
	manualColumnResize: false,
	manualRowResize: false,
	colHeaders: JSON.parse(header),
	columns: JSON.parse(array_header_col_readonly),
	columnSummary: [
		{
		  destinationRow: 0,
		  destinationColumn: 0,
		  reversedRowCoords: true,
		  type: 'sum',
		  forceNumeric: true
		}
	]
});

$("[name='record_filter']").bootstrapSwitch({
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
	$('#datatable-default').DataTable( {
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
       			$("#datatable-default").DataTable().destroy();
       			var table  = $("#datatable-default").DataTable();
           	var object = (JSON.parse(data));
				    table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default').DataTable( {
    				"order": [],
    				"autoWidth" : false,
            "bStateSave": true,
    			});
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
       			$("#datatable-default").DataTable().destroy();
       			var table  = $("#datatable-default").DataTable();
           	var object = (JSON.parse(data));
				    table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default').DataTable( {
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
       			$("#datatable-default").DataTable().destroy();
       			var table  = $("#datatable-default").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default').DataTable( {
    				"order": [],
    				"autoWidth" : false,
            "bStateSave": true,
    			});
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
       			$("#datatable-default").DataTable().destroy();
       			var table  = $("#datatable-default").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default').DataTable( {
    				"order": [],
    				"autoWidth" : false,
            "bStateSave": true,
    			});
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
       			$("#datatable-default").DataTable().destroy();
       			var table  = $("#datatable-default").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default').DataTable( {
    				"order": [],
    				"autoWidth" : false,
            "bStateSave": true,
    			});
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
       			$("#datatable-default").DataTable().destroy();
       			var table  = $("#datatable-default").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default').DataTable( {
    				"order": [],
    				"autoWidth" : false,
            "bStateSave": true,
    			});
       }
   	});
});

$("[name='record_filter']").on('switchChange.bootstrapSwitch', function(event, state) {

	$.ajax({
       type: "POST",
       url:  "timesheet/record_filter_admin",
       data: '&result=' + state,
       success: function(data)
       {

       		if(JSON.parse(data)==null || JSON.parse(data)==""){
       			$("#datatable-default").DataTable().destroy();
       			var table  = $("#datatable-default").DataTable();
           		var object = (JSON.parse(data));
				table.clear().draw();
       		}

       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
       			$("#datatable-default").DataTable().destroy();
           		var object = (JSON.parse(data));
           		$(".timesheet_tr").remove();
           		var http = '<?php echo base_url() ?>';

           		for(var i=0; i<object.length; i++){
           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ moment(object[i]['month']).format('MMMM YYYY') +"</a></td>><td>"+object[i]['employee_name']+"</td><td>"+ object[i]['status_id'] +"</td>";

           			$(".timesheet_table").append(rowHtml);
           		}

       		}

       		$('#datatable-default').DataTable( {
    				"order": [],
    				"autoWidth" : false,
            "bStateSave": true,
    			});
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

function set_string(number){
	if(number == 0){
		return '0.0';
	}
	else{
		if(!isNaN((number.toFixed(1)).toString())){
			return (number.toFixed(1)).toString();
		}
		else{
			return '';
		}
	}
}

function generate_PDF()
{
  $("#loadingmessage").show();
  var office     = $(".timesheet_office_filter").val();
  var department = $(".timesheet_department_filter").val();
  var employee   = $(".timesheet_employee_filter").val();
  var month      = $(".timesheet_month_filter").val();
  var year       = $(".timesheet_year_filter").val();
  var status     = $(".timesheet_status_filter").val();

  $.ajax({
    type: "POST",
    async: false,
    url:  "timesheet/generate_multiple_PDF",
    data: '&office=' + office + '&department=' + department + '&employee=' + employee + '&month=' + month + '&year=' + year + '&status=' + status,
    dataType: "json",
    'async':false,
    success: function(response)
    {
        window.open(response.link,'_blank');
        filename = response.filename;

        $("#loadingmessage").hide();
    }  
  });
}

</script>

<!-- <script src="<?= base_url()?>application/modules/timesheet/js/timesheet_submition_check.js" charset="utf-8"></script> -->