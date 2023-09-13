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


<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_purple" href="timesheet/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Create Timesheet</a>
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div>
			<label style="display:block;" class="control-label"><strong>Records :</strong></label>
        	<input style="display:block;" type="checkbox" name="record_filter" style="width: 100%;"/>
        </div>
		<div class="col-md-12">
			<div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12" >
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Timesheet no. </th>
								<th class="text-left">Month</th>
								<th class="text-left">Status</th>
							</tr>
						</thead>
						<tbody class="timesheet_table">
							<?php 
								foreach($timesheet_list as $row)
					  			{
					  				echo '<tr class="timesheet_tr" >';
					  				echo '<td><a href="timesheet/edit/'. $row->id .'">'. $row->timesheet_no .'</td>';
					  				echo '<td>'.date('F Y', strtotime($row->month)).'</td>';
					  				echo '<td>'.$row->status_id.'</td>';
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
	// var holiday = <?php echo json_encode(isset($holiday)?$holiday:"") ?>;
	var third_working_date = '<?php echo $third_working_date; ?>';
	var this_month_leave = '<?php echo json_encode(isset($this_month_leave)?$this_month_leave:"") ?>';
	var emp_id = <?php echo json_encode(isset($emp_id)?$emp_id:"") ?>;

	window.onbeforeunload = function() {
	  localStorage.setItem("record_filter", $("[name='record_filter']").is(":checked"));
	}

	window.onload = function() {
	  $record_filter = localStorage.getItem("record_filter");

	  if($record_filter === "true"){
	    $("[name='record_filter']").bootstrapSwitch('state',1);
	  } else {
	    $("[name='record_filter']").bootstrapSwitch('state',0);
	  }
	}

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

	$(document).ready( function (){
		$.post("timesheet/Check_Timesheet", { 'emp_id' : emp_id }, function(data, status){
			if(data){
				location.reload();
			}
    	});
	});

	$(document).ready( function (){
	    $('#datatable-default').DataTable( {
	    	"order": [],
	    	"autoWidth" : false,
	    	"bStateSave": true,
	    } );
	});

	// TIMESHEET SUBMITTION NOTIFICATION
	// $(document).ready( function (){
	// $.post("timesheet/Submition_Notification", {  }, function(data, status){
    // });
	// });

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

	$("[name='record_filter']").on('switchChange.bootstrapSwitch', function(event, state) {

		$.ajax({
	       type: "POST",
	       url:  "timesheet/record_filter",
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
	           		console.log(object);

	           		for(var i=0; i<object.length; i++){
	           			var rowHtml = "<tr class='timesheet_tr' ><td><a href="+ http +'timesheet/edit/'+ object[i]['id'] +">"+ object[i]['timesheet_no'] +"</a></td>><td>"+moment(object[i]['month']).format('MMMM YYYY')+"</td><td>"+ object[i]['status_id'] +"</td>/tr>";

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

</script>

<!-- <script src="<?= base_url()?>application/modules/timesheet/js/timesheet_submition_check.js" charset="utf-8"></script> -->