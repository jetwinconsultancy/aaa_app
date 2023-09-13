<script src="<?= base_url() ?>node_modules/handsontable/dist/handsontable.full.min.js"></script>
<link href="<?= base_url() ?>node_modules/handsontable/dist/handsontable.full.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>

<style>
	.highlight{
		color: black;
	}
	.handsontable .htCore .htDimmed {
	    background-color: #F2F2F2;
	    font-style: italic;
	}
	.handsontable .htCore .WeekEnd {
	    background-color: #d2322d2e;
	    font-style: italic;
	}
	.handsontable .htCore .tt{
	    font-weight: bold;
	}
	.handsontable .htCore .act{
	    white-space: nowrap;
  		overflow: hidden;
  		text-overflow: ellipsis;
	}
	footer{
		margin-top:35px !important;
	}
</style>

<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-1">
					<span>Staff: </span>
				</div>
				<div class="col-md-10">
					<span><?php echo $timesheet[0]->employee_name ?></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1">
					<span>Month: </span>
				</div>
				<div class="col-md-10">
					<span><?php echo date('M Y', strtotime($timesheet[0]->month)) ?></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1">
					<span>Status: </span>
				</div>
				<div class="col-md-5">
					<span><?php echo $timesheet_status_name ?></span>
				</div>
				<div class="col-md-6" style="text-align: right;">
					<?php if($timesheet[0]->status_id == 1){?>
						<button class="btn btn_purple" onclick="insert_row()">Insert Row</button>
					<?php }?>
					<button class="btn btn_purple" onclick="generate_PDF()">Generate PDF</button>
				</div>
			</div>
			<div class="row">
				<label></label>
			</div>
			
			<div class="form-group row">
				<div class="col-md-5">
					<?php 
						if($timesheet[0]->status_id == 1){
							echo '<button class="btn btn_purple" onclick="submit_timesheet()">Submit</button>';
							// echo '<button class="btn btn_purple">Submit</button>';
						}
						// if($timesheet[0]->status_id == 1 && (!($this->data['Admin'] || $this->data['Manager']) || ($this->user_id == 91 || $this->user_id == 84 || $this->user_id == 107))){
						// 	echo '<button class="btn btn_purple" onclick="submit_timesheet()">Submit</button>';
						// 	// echo '<button class="btn btn_purple">Submit</button>';
						// }
						if($timesheet[0]->status_id == 2){
							echo '<button class="btn btn_purple" onclick="approve_timesheet()">Approve</button>';
							// echo '<button class="btn btn_purple">Approve</button>';
						} 
						// if($timesheet[0]->status_id == 2 && ($this->data['Admin'] || $this->data['Manager'])){
						// 	echo '<button class="btn btn_purple" onclick="approve_timesheet()">Approve</button>';
						// 	// echo '<button class="btn btn_purple">Approve</button>';
						// } 
					?>
				</div>
				<div class="col-md-7" style="text-align: right">
			    	<?php 
			    		echo '<a href="'.base_url().'timesheet/index" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

			    		if($timesheet[0]->status_id == 1){
			    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%" onclick="save_timesheet()">Save</button>';
			    			// echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
			    		}
			    		// if($timesheet[0]->status_id == 1 && (!($this->data['Admin'] || $this->data['Manager']) || ($this->user_id == 91 || $this->user_id == 84 || $this->user_id == 107))){
			    		// 	echo '<button class="btn btn_purple pull-right" style="margin:0.5%" onclick="save_timesheet()">Save</button>';
			    		// 	// echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
			    		// }
			    	?>
			    </div>
            </div>

			<div style="height:525px; width: 100%; overflow-x: hidden; overflow-y: hidden;" >
				<div id="timesheet_excel" ></div>
			</div>

			<div class="form-group row">
				<span style="font-size:12px; margin-left: 1%">This timesheet status is <?php echo strtolower($timesheet_status_name) ?>.</span>
			</div>
			
		</div>
	</div>
</section>

<script type="text/javascript">
	
	var testdata = <?php echo json_encode($this->data) ?>;
	console.log(testdata);

	var timesheet_id = <?php echo $timesheet[0]->id ?>;
	var emp_id = '<?php echo $timesheet[0]->employee_id ?>';
	var header = '<?php echo json_encode($header); ?>';
	var array_header_col_readonly = '<?php echo json_encode($array_header_col_readonly); ?>';
	var timesheet_content = '<?php echo urlencode($timesheet[0]->content) ; ?>';
	var bf_timesheet_content = <?php echo json_encode(isset($bf_timesheet[0]->content)?$bf_timesheet[0]->content:"") ?>;
	var timesheet_status = '<?php echo $timesheet[0]->status_id ?>';
	var leave_details = <?php echo json_encode(isset($leave_details)?$leave_details:'') ?>;
	var timesheet_date  = '<?php echo $timesheet[0]->month ?>' ;
	// var holiday = <?php echo json_encode(isset($holiday)?$holiday:"") ?>;
	var data = [];
	
	var container = document.getElementById('timesheet_excel');

	// FOR MANAGER TO SELECT THE ASSIGNMENT HE/SHE WANT TO FILL IN
	var assignment_DB = <?php echo json_encode($assignment) ?>;
	var assignment_user_selected = <?php echo json_encode($user_selected_assignment) ?>;
	// var assignment = [];
	var assignment = new Array()
	var review_assignment_list;

	for($b=0 ; $b<assignment_user_selected.length ; $b++)
	{
		for($a=0 ; $a<assignment_DB.length ; $a++)
		{
			if(assignment_DB[$a]['assignment_id'] == assignment_user_selected[$b])
			{
				assignment.push(assignment_DB[$a]);
			}
		}
	}

	// FOR MANAGER TO SELECT THE ASSIGNMENT HE/SHE WANT TO FILL IN
	if(assignment.length == 0)
	{
		review_assignment_list = undefined;
	}
	else
	{
		$.ajax({
			'async':false,
			type: "POST",
			url:  "<?php echo site_url('timesheet/check_assignment_status'); ?>",
			data: { 'assignment_list': assignment },
			dataType: "json",
			success: function(data)
			{
				review_assignment_list = data;
			}
		});
	}

	function get_data(){
		return [];
	}

	// SET ASC ORDER FOR TIMESHEET ACTIVITIES
	function compare( a, b) {

		if ( a.Activities == ""){
			return 1;
		}
		if ( b.Activities == ""){
			return -1;
		}
		if ( a.Activities < b.Activities ){
			return -1;
		}
		if ( a.Activities > b.Activities ){
			return 1;
		}
		return 0;
	}

	// DISABLE * KEY-IN
	$('#timesheet_excel').keypress(function(e){
		if(e.which == 42 )
		{
		  return false;
		}
	});

	var hot = new Handsontable(container, {
	  data: get_data(),
	  // fixedRowsTop: 0,
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
	  copyPaste: false,
	  contextMenu: false,
	  columnSummary: [
	    {
	      destinationRow: 0,
	      destinationColumn: 0,
	      reversedRowCoords: true,
	      type: 'sum',
	      forceNumeric: true
	    }
	  ],
	afterChange: function(changes, src){
	  	temp_all_data = this.getData();
	  	data = [];

  	 	if(!changes){
  	 		return;
  	 	}

  	 	// $.each(changes, function(index, element) {
  	 		if(changes[0][2] == null)
  	 		{
  	 			var change_value = '';
  	 		}
  	 		else
  	 		{
  	 			var change_value = changes[0][2];
  	 		}

  	 		if(change_value.includes('*') || change_value == "On Leave" || change_value == "Total" || change_value == "Idle"){

  	 			var row = changes[0][0];
	          	var col = changes[0][1];

	          	temp_all_data.forEach(function(row, index){
	          		var obj = [];

	          		row.forEach(function(item, index){
	          			obj[hot.getColHeader()[index]] = item;
	          		});

	          		data.push(obj);
	      		});

  	 			data[row][col] = change_value;
  	 			hot.loadData(data);
  	 			toastr.error("Please Do Not Edit", 'Notices');
  	 		}
  	 		else if(change_value.includes('-')){

  	 			var row = changes[0][0];
	          	var col = changes[0][1];

	          	temp_all_data.forEach(function(row, index){
	          		var obj = [];

	          		row.forEach(function(item, index){
	          			obj[hot.getColHeader()[index]] = item;
	          		});

	          		data.push(obj);
	      		});

  	 			data[row][col] = change_value;
  	 			hot.loadData(data);
  	 			toastr.error("Day Blocked", 'Notices');
  	 		}
  	 		else
  	 		{
  	 			var row = changes[0][0];
	          	var col = changes[0][1];

	          	var get_this_row_data = hot.getDataAtRow(row);
	          	var total_current = 0;
	          	rowValue = 0;

	          	var current_col_index = GetColFromName('current');	// get index of column named "current" 
	          	var bf_col_index   	  = GetColFromName('b/f');	// get index of column named "b/f" 
	          	var total_col_index   = GetColFromName('total');	// get index of column named "total" 

	          	// calculate total number for "current" cell
	          	get_this_row_data.forEach(function(item, index){
	          		// for no include the first 3 col in calculation
	          		if(index!=0 && index!=1 && index!=2)
	          		{
	          			if(index < current_col_index)
	          			{
	          				if(item == '*' || item == null)
			  				{
			  					item = "";
			  				}
			  				else if(item.match(/[*][0-8][.][0-9]/s)) 
			  				{
			  					item = item.replace('*','');
			  				}

		              		var float_num = parseFloat(item);

		              		if(!isNaN(float_num))
		              		{
		              			total_current += float_num;
		              		}
		          		}
	          		}
	          	});

	      		temp_all_data[row][current_col_index] = set_string(total_current);	// set new value for "current" cell

	      		// Calculate total number for "total" cell
	            if(!isNaN(parseFloat(get_this_row_data[bf_col_index]))){
	                temp_all_data[row][total_col_index] = set_string(total_current + parseFloat(get_this_row_data[bf_col_index]));
	            }else{
	                temp_all_data[row][total_col_index] = set_string(total_current);
	            }

	          	temp_all_data.forEach(function(row, index){
	          		var obj = [];

	          		row.forEach(function(item, index){
	          			obj[hot.getColHeader()[index]] = item;
	          		});

	          		data.push(obj);
	      		});

	  			// Calculate total of all current
	      		var overall_total_current = 0;

	      		for($i=0; $i<data.length - 1; $i++){
	      			var float_num = parseFloat(data[$i]["current"]);
	      			if(!isNaN(float_num)){
	          			overall_total_current += float_num;
	          		}
	      		}

	      		data[data.length - 1]["current"] = set_string(parseFloat(overall_total_current));

	          	// Calculate total by column
	          	if(col != 'Activities' && col!= 'Type of Job' && col!= 'FYE'){
	          		var get_this_col_data = [];

	          		for($i=0; $i<data.length; $i++){
	          			get_this_col_data.push(data[$i][col]);
	          		}

	              	// var get_this_col_data = hot.getDataAtCol(col);
	              	var col_length 		  = get_this_col_data.length;
	              	var total_col_value   = 0;

	              	get_this_col_data.forEach(function(item, index){
	              		if(index < col_length - 1){
	              			if(item != null && item.includes('*'))
		      				{
			      				// item = item.replace('*','');
			      				if(item == '*')
		      					{
		      						item = item.replace('*','0.0');
		      					}
		      					else
		      					{
		      						item = item.replace('*','');
		      					}
			      			}

	              			var float_num = parseFloat(item);

	                  		if(!isNaN(float_num)){
	                  			total_col_value += float_num;
	                  		}
	              		}
	              	})
	              	
	              	data[data.length - 1]["Activities"] = "Total";	// Set "Total"

	              	if(col!= 'b/f' && col!= 'Type of Job' && col!= 'FYE'){
	              		data[col_length-1][col] = set_string(total_col_value);	// set values in last row
	              		data[row][col] = set_string(parseFloat(data[row][col]));	// change number to 0.0 format
	              		data[col_length-1][get_this_row_data.length-1] = 0;
	              	}
	              	else
	              	{
	              		var b_f_col_num = hot.getDataAtCol(bf_col_index).length;	// get col length

	              		data[row][col] = set_string(parseFloat(temp_all_data[row][bf_col_index]));	// change and set number to 0.0 format for 'b/f' column

	              		data[b_f_col_num - 1]['b/f'] = '-';	// let last row of 'b/f' column to be empty
	              		data[b_f_col_num-1]['total'] = '-';	// let last row of 'total' column to be empty
	              	}

	          		// Set empty cell to null
	              	if(data[row][col] == ""){
	          			data[row][col] = null;
	          		}

	          		if(get_this_row_data[bf_col_index] == ""){
	          			get_this_row_data[bf_col_index] = null;
	          		}

	          		// Valitation for 8 hours per day
	          		if(col !='b/f'){
	          			if(total_col_value > 8)
		              	{
		              		temp_value = data[row][col];
		              		data[row][col] = null;
		              		data[col_length-1][col] = set_string(total_col_value - temp_value);
		              		data[data.length - 1]["current"] = set_string(parseFloat(overall_total_current - temp_value));
		              		total_current = parseFloat(total_current - temp_value);
		              		toastr.error('More Than 8 hours', 'Over Working Hours');
		              	}
	          		}

	          		// Row Calculation
	              	for($i = 0 ; $i<data[row].length ; $i++)
	              	{
	              		var item = data[row][$i];

	              		if(item == ""){
	              			item = null;
	              		}

	              		if(item != undefined || item != null ){
	              			if(item != '-'){
	              				if(item == '*')
		      					{
		      						item = item.replace('*','0.0');
		      					}
		      					else if(item.includes('*'))
		      					{
		      						item = item.replace('*','');
		      					}
	              				rowValue = parseFloat(item) + parseFloat(rowValue);
	              			}
	              		}
	              	}

	                data[row]["current"] = set_string(rowValue);
	              	data[row]["total"]   = set_string(rowValue);

	          		// add total if b/f is not empty
	                if(get_this_row_data[bf_col_index] != null){

	                    data[row]["total"] = set_string(total_current + parseFloat(get_this_row_data[bf_col_index]));
	                }

	            }
	          	hot.loadData(data);
  	 		}
  	 	// });
	}
	});

	if(timesheet_content != ""){
		// Insert existing data from database
		var data1 = JSON.stringify(<?php echo $timesheet[0]->content ; ?>);
		change_array_format(JSON.parse(data1));
	}
	else{
		// Insert assignment
		insert_assignment();
	}

	// HWEE XIN [UID=91 & EID=37], ALAN [UID=84 & EID=19], felicia [UID=107 & EID=20]
	if(timesheet_status == '1'){
		if('<?php echo $this->data['Admin'] || $this->data['Manager']?>'){
			if('<?php echo $this->user_id != 91 && $this->user_id != 84 && $this->user_id != 107 ?>'){
				hot.updateSettings({
				    readOnly: false, // make table cells read-only
				    contextMenu: false, // disable context menu to change things
				    disableVisualSelection: true, // prevent user from visually selecting
				    manualColumnResize: false, // prevent dragging to resize columns
				    manualRowResize: false, // prevent dragging to resize rows
				    comments: false // prevent editing of comments
				});
			}
			else if('<?php echo $timesheet[0]->employee_id != 37 && $timesheet[0]->employee_id != 19 && $timesheet[0]->employee_id != 20 ?>')
			{
				hot.updateSettings({
				    readOnly: false, // make table cells read-only
				    contextMenu: false, // disable context menu to change things
				    disableVisualSelection: true, // prevent user from visually selecting
				    manualColumnResize: false, // prevent dragging to resize columns
				    manualRowResize: false, // prevent dragging to resize rows
				    comments: false // prevent editing of comments
				});
			}
		}
	}

	if(timesheet_status == '2'){
		// if(!'<?php echo $this->data['Admin'] || $this->data['Manager']?>'){
				hot.updateSettings({
				    readOnly: false, // make table cells read-only
				    contextMenu: false, // disable context menu to change things
				    disableVisualSelection: true, // prevent user from visually selecting
				    manualColumnResize: false, // prevent dragging to resize columns
				    manualRowResize: false, // prevent dragging to resize rows
				    comments: false // prevent editing of comments
				});
		// }
	}

	if(timesheet_status == '3'){
		hot.updateSettings({
		    readOnly: true, // make table cells read-only
		    contextMenu: false, // disable context menu to change things
		    disableVisualSelection: true, // prevent user from visually selecting
		    manualColumnResize: false, // prevent dragging to resize columns
		    manualRowResize: false, // prevent dragging to resize rows
		    comments: false // prevent editing of comments
		});
	}

	// get index of column by name. Eg. find the header column with name "current" and get the column index
	function GetColFromName(name){
	    var n_cols  =   hot.countCols();
	    var i       =   1;

	    for (i=1; i<=n_cols; i++)
	    {
	        if (name.toLowerCase() == (hot.getColHeader(i)).toLowerCase()) {
	            return i;
	        }
	    }
	    return -1; //return -1 if nothing can be found
	}

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

	function save_timesheet(){
		var temp_all_data = hot.getData();

		for($i=0 ; $i<temp_all_data.length ; $i++){
			if(temp_all_data[$i][0] == 'On Leave'){
				temp_all_data.splice($i,1);
			}
			if(temp_all_data[$i][0] == 'Total'){
				temp_all_data.splice($i,1);
			}
		}

		bootbox.confirm({
	        message: "<p><strong>Do You Wanna To Save This Timesheet ?</strong></p>Reminder : <p> - Please Do Not Save The Timesheet When There Is Any Issue (e.g. Assignment Missing)</p>",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Save',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'Cancel',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) {
	        	if(result == true)
	        	{
			    	$.post("<?php echo site_url('timesheet/save_timesheet'); ?>", { timesheet_id: timesheet_id , data: temp_all_data }, function(result, status){
						if(result){
							toastr.success('Successfully saved timesheet.', 'Saved');
						}
					});
				}
	        }
	    })
	}

	function insert_assignment(){
		$("#loadingmessage").show();
  		if(assignment!='[]')
  		{
  			table_row = 5;
		  	// Insert new row for total row
  			if(table_row <= assignment.length)
  			{
  				hot.alter('insert_row', hot.countRows() - 1, 1);
  			}

  			for($i=0 ; $i<assignment.length ; $i++){

  				if(table_row<assignment.length){
  					hot.alter('insert_row', hot.countRows() - 1, 1);
  					table_row ++ ;
  				}
  			}
		  	// Insert Assignment client name to table
		  	var data = hot.getData();
  			for($i=0 ; $i < assignment.length ; $i++){
  				var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
  				if(assignment[$i]['FYE'] != null){

  					var date = new Date(assignment[$i]['FYE']);
					var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
  				}
  				else{
  					var date_format = "";
  				}
  				data[$i]['Activities']  = assignment[$i]['client_name'];
  				data[$i]['Type of Job'] = assignment[$i]['job'];
  				data[$i]['FYE']         = date_format;
  			}
  			hot.loadData(data);
  		}

  		if(bf_timesheet_content != '')
		{
			var bf_content = JSON.parse(bf_timesheet_content);
			var bf_total_col = bf_content[0].length-1;
			var bf_col = data[0].length-2;

			for($i=0;$i<bf_content.length;$i++)
			{
				if(!(bf_content[$i][0].includes('*')))
				{
					bf_content.splice($i);
				}
			}

			for($i=0 ; $i<assignment.length ; $i++){

				for($j=0 ; $j<bf_content.length ; $j++){

					var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

					if(assignment[$i]['FYE'] != null){

						var date = new Date(assignment[$i]['FYE']);
						var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
					}
					else{
						var date_format = "";
					}

					if (assignment[$i]['client_name'] === bf_content[$j][0] && assignment[$i]['job'] === bf_content[$j][1] && date_format === bf_content[$j][2]){
				    	data[$i]['b/f'] = bf_content[$j][bf_total_col];
				    	data[$i]['total'] = bf_content[$j][bf_total_col];
				    }
				}
			}

			// B/F & FILTER CLEAR REVIEW & STOCK TAKE
			var bf_clearReview_stockTake = [];
			var bf_clearReview = [];
			var bf_stockTake = [];
			var stocktake_assignment_list;
			var clearReview_assignment_list = [];
	     	$.ajax({
		        type: "POST",
		        'async' : false,
		        url:  "<?php echo site_url('timesheet/stocktake_assignment_list'); ?>",
		        data: { timesheet_id: timesheet_id },
		        dataType: "json",
		        success: function(data){
		        	stocktake_assignment_list = data;
		        }
		   	});
		   	for($a=0; $a<review_assignment_list.length ; $a++) {
      			var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
					
				if(review_assignment_list[$a][0]['FYE'] != null) {
					var date = new Date(review_assignment_list[$a][0]['FYE']);
					review_assignment_list[$a][0]['FYE'] = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
				} else {
					review_assignment_list[$a][0]['FYE'] = "";
				}

				clearReview_assignment_list.push({
					name:review_assignment_list[$a][0]["client_name"],
					job:review_assignment_list[$a][0]["type_of_job"],
					FYE:review_assignment_list[$a][0]['FYE']
				});
      		}
      		for($a=0; $a<stocktake_assignment_list.length ; $a++) {
      			var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
					
				if(stocktake_assignment_list[$a]['fye_date'] != null) {
					var date = new Date(stocktake_assignment_list[$a]['fye_date']);
					stocktake_assignment_list[$a]['fye_date'] = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
				} else {
					stocktake_assignment_list[$a]['fye_date'] = "";
				}
      		}
			for($a=0 ; $a<bf_content.length ; $a++){
				var table = hot.getData();
				let empty_row = [];

				table.forEach(function(row, index) {
	          		var obj  = [];
	          		row.forEach(function(item, index) {
	          			empty_row[hot.getColHeader()[index]]  = '';
	          		});
	      		});

				var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

				if(bf_content[$a][2] != null){

					var date = new Date(bf_content[$a][2]);
					var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
				}
				else {
					var date_format = "";
				}

			 	if (bf_content[$a][1] === "STATUTORY AUDIT - CLEAR REVIEW"){
			 		empty_row["Activities"] = bf_content[$a][0];
			 		empty_row["Type of Job"] = bf_content[$a][1];
			    	empty_row["FYE"] = bf_content[$a][2];
			    	empty_row["b/f"] = bf_content[$a][bf_total_col];
			    	empty_row["total"] = bf_content[$a][bf_total_col];
			    	bf_clearReview.push(empty_row);
			    }

			    if (bf_content[$a][1] === "STATUTORY AUDIT - STOCK TAKE"){
			 		empty_row["Activities"] = bf_content[$a][0];
			 		empty_row["Type of Job"] = bf_content[$a][1];
			    	empty_row["FYE"] = bf_content[$a][2];
			    	empty_row["b/f"] = bf_content[$a][bf_total_col];
			    	empty_row["total"] = bf_content[$a][bf_total_col];
			    	bf_stockTake.push(empty_row);
			    }
			}

			bf_clearReview = bf_clearReview.filter(item => clearReview_assignment_list.some(other => other.job == "STATUTORY AUDIT" && item.Activities == other.name && item.FYE == other.FYE));
			for($a=0; $a<bf_clearReview.length ; $a++) {
				data.push(bf_clearReview[$a]);
      		}

			bf_stockTake = bf_stockTake.filter(item => stocktake_assignment_list.some(other => item.Activities == other.company_name && item.FYE == other.fye_date));
			for($a=0; $a<bf_stockTake.length ; $a++) {
				data.push(bf_stockTake[$a]);
      		}

			hot.loadData(data);					
		}

		var timesheet_date  = '<?php echo $timesheet[0]->month ?>' ;
		if(leave_details != '')
		{
			// var leave_list = JSON.parse(leave_details);
			var leave_list = leave_details;
			// hot.alter('insert_row', hot.countRows() - 1, 1);
			var table = hot.getData();
			var result = [];
			var empty_row_leave = [];

			table.forEach(function(row, index){
          		var obj  = [];

          		row.forEach(function(item, index){
          			obj[hot.getColHeader()[index]]  = item;
          			empty_row_leave[hot.getColHeader()[index]]  = '';
          		});

          		result.push(obj);
      		});

      		empty_row_leave['Activities'] = 'On Leave';
      		empty_row_leave['Type of Job'] = '-';
  			empty_row_leave['FYE']         = '-';

      		var total_leave_hours = 0;

			for($i=0 ; $i<leave_list.length ; $i++) {
				var start = new Date(leave_list[$i]['start_date']);
					start = new Date(start.getFullYear()+'-'+(start.getMonth()+1)+'-'+start.getDate());
				var start_date = start.getDate();
				var start_time = leave_list[$i]['start_time'];

				var end = new Date(leave_list[$i]['end_date']);
					end = new Date(end.getFullYear()+'-'+(end.getMonth()+1)+'-'+end.getDate());
				var end_date = end.getDate();
				var end_time = leave_list[$i]['end_time'];

				empty_row_leave.forEach(function(item, index){
					var date     = new Date(timesheet_date);
		    		var date_day = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+index);

					if(date_day >= start && date_day <= end){

						var leave_hours;

						if(index == start_date)
						{
							if(start_time == "1:00 PM"){
								// leave_hours = set_string(4);
								leave_hours = '*4.0';
							}
							else if(end_time == "1:00 PM"){
								// leave_hours = set_string(4);
								leave_hours = '*4.0';
							}
							else{
								// leave_hours = set_string(8);
								leave_hours = '*8.0';
							}
							empty_row_leave[index] = leave_hours;
						}
						else if(index == end_date)
						{
							if(start_time == "1:00 PM"){
								// leave_hours = set_string(4);
								leave_hours = '*4.0';
							}
							else if(end_time == "1:00 PM"){
								// leave_hours = set_string(4);
								leave_hours = '*4.0';
							}
							else{
								// leave_hours = set_string(8);
								leave_hours = '*8.0';
							}
							empty_row_leave[index] = leave_hours;
						}
						else
						{
							// leave_hours = set_string(8);
							leave_hours = '*8.0';
							empty_row_leave[index] = leave_hours;
						}

						leave_hours = leave_hours.replace('*','');
						total_leave_hours = set_string(parseFloat(total_leave_hours) + parseFloat(leave_hours));
					}
				});
			}
			empty_row_leave['current'] = total_leave_hours;
			empty_row_leave['b/f'] = '-';
			empty_row_leave['total'] = total_leave_hours;


			empty_row_leave.forEach(function(item, index){

				if(item == ""){
					empty_row_leave[index] = '-';
				}

			});

			result.push(empty_row_leave);
	      	hot.loadData(result);
		}

		if(assignment!='[]')
  		{
  			var table = hot.getData();
  			var result = [];

			table.forEach(function(row, index){
          		var obj  = [];

          		row.forEach(function(item, index){
          			obj[hot.getColHeader()[index]]  = item;
          		});

          		result.push(obj);
      		});

      		for($i=0 ; $i<assignment.length ; $i++){

	  			if(result[$i]['Activities'] == assignment[$i]['client_name']){

	  				var create_on = new Date(assignment[$i]['create_on']);
	  				var create_date =  create_on.getDate();
	  				var create_month =  (create_on.getMonth()+1);

	  				result[$i].forEach(function(item, index){

	  					var date1 = new Date(timesheet_date);
	  					date1 = (date1.getMonth()+1);

	  					if(create_month == date1){
	  						if(index < create_date){
	  							result[$i][index] = "-";
	  						}
	  					}
			  		});

			  		if(assignment[$i]['complete_date'] != null){

			  			var complete_on = new Date(assignment[$i]['complete_date']);
		  				var complete_date =  complete_on.getDate();
		  				var complete_month =  (complete_on.getMonth()+1);

		  				result[$i].forEach(function(item, index){

		  					var date1 = new Date(timesheet_date);
		  					date1 = (date1.getMonth()+1);

		  					if(complete_month == date1){
		  						if(index > complete_date){
		  							result[$i][index] = "-";
		  						}
		  					}
				  		});
			  		}
	  			}
	  			hot.loadData(result);
	  		}
  		}
  		total_calculation();
  		
  		var temp_all_data = hot.getData();
		for($i=0 ; $i<temp_all_data.length ; $i++){
			if(temp_all_data[$i][0] == 'On Leave'){
				temp_all_data.splice($i,1);
			}
			if(temp_all_data[$i][0] == 'Total'){
				temp_all_data.splice($i,1);
			}
		}
		$.post("<?php echo site_url('timesheet/save_timesheet'); ?>", { timesheet_id: timesheet_id , data: temp_all_data }, function(result, status){
			if(result){
				$("#loadingmessage").hide();
				location.reload();
			}
		});
	}

	function change_array_format(temp_all_data){

		var data = [];
		var data_length = 0;
		var empty_row = [];

		temp_all_data.forEach(function(row, index){
	  		var obj = [];

	  		row.forEach(function(item, index){
	  			// AaA

      			if(item.includes('*'))
  				{
  					// console.log(item);
  					if(item == '*')
	  				{
	  					item = "";
	  				}
	  				else if(item.match(/[*][0-8][.][0-9]/s)) 
	  				{
	  					item = item.replace('*','');
	  				}
  				}
  				else if(item == '-')
  				{
  					item = "";
  				}

	  			obj[hot.getColHeader()[index]] = item;
	  			empty_row[hot.getColHeader()[index]]  = '';

	  		});

	  		if(timesheet_status == '1'){
	  			if(obj['Activities'] != "")
		  		{
		  			data.push(obj);
		  		}
	  		}
	  		else{
	  			data.push(obj);
	  		}
  		});

		// get assignment length inside timesheet ------------------------------------------------------------------------------------------------------------------
  		for($i=0 ; $i<data.length ; $i++)
  		{
  			if(data[$i]['Activities'] != "")
  			{
  				data_length++;
  			}

  			if(review_assignment_list != undefined)
  			{
	  			for($o=0 ; $o<review_assignment_list.length ; $o++)
	  			{
	  				var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
						
					if(review_assignment_list[$o][0]['FYE'] != null){

						var date = new Date(review_assignment_list[$o][0]['FYE']);
						var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
					}
					else{
						var date_format = "";
					}

	  				if(data[$i]['Activities'] == review_assignment_list[$o][0]['client_name'] && data[$i]['Type of Job'] == review_assignment_list[$o][0]['type_of_job'] && data[$i]['FYE'] == date_format)
	  				{
	  					var block_date       = new Date(review_assignment_list[$o][0]['date']);
						var block_date_day   = block_date.getDate()
						var block_date_month = block_date.getMonth() + 1;
						var block_date_year  = block_date.getFullYear();

						var today       = new Date(timesheet_date);
						var today_day   = ("0" + today.getDate()).slice(-2);
						var today_month = today.getMonth() + 1;
						var today_year  = today.getFullYear();

						if(block_date_month==today_month && block_date_year==today_year)
						{
	  						for($u=1 ;  $u<block_date_day ; $u++)
	  						{
	  							data[$i][$u] = '*' + data[$i][$u];
	  						}
						}
						else if(block_date_month>=today_month && block_date_year==today_year)
						{
							for($u=1 ;  $u<data[$i].length ; $u++)
	  						{
	  							data[$i][$u] = '*' + data[$i][$u];
	  						}
						}	
	  				}
	  			}
	  		}
  		}


  		// INSERT / DELETE ASSIGNMENT INTO TIMESHEET ---------------------------------------------------------------------------------------------------------------
		// if((!'<?php echo $this->data['Admin'] || $this->data['Manager']?>') && timesheet_status == '1'){
		if(timesheet_status == '1'){
			var data_client = [];
			var assignment_client = [];
			var data_job = [];
			var assignment_placehold = [];
			var data2 = [];

			// push timesheet activities into an array
			for($i=0;$i<data_length;$i++){
				data_client.push({name:data[$i]['Activities'],job:data[$i]['Type of Job'],FYE:data[$i]['FYE']});
				data_job.push(data[$i]['Type of Job']);
			}

			// push assignment client name into an array
			for($i=0;$i<assignment.length;$i++){

				var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
					
				if(assignment[$i]['FYE'] != null){

					var date = new Date(assignment[$i]['FYE']);
					var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
				}
				else{
					var date_format = "";
				}

				assignment_client.push({name:assignment[$i]['client_name'],job:assignment[$i]['job'],FYE:date_format});
			}

			for($i=0;$i<data_length;$i++)
			{
				for($j=0;$j<assignment_client.length;$j++)
				{
					if(data[$i]['Activities'] == assignment_client[$j]['name'] && data[$i]['Type of Job'] == assignment_client[$j]['job'] && data[$i]['FYE'] == assignment_client[$j]['FYE'])
					{
						data2.push({name:data[$i]['Activities'],job:data[$i]['Type of Job'],FYE:data[$i]['FYE']});
					}
				}
			}
			
			var new_assignment = assignment_client.filter(item => !data2.some(other => item.job == other.job && item.name == other.name && item.FYE == other.FYE));

			for($i=0;$i<new_assignment.length;$i++)
			{
				data_client.push(new_assignment[$i]);
			}

			var delete_assignment = data_client.filter(item => !assignment_client.some(other => item.job == other.job && item.name == other.name && item.FYE == other.FYE));

			for($i=0;$i<delete_assignment.length;$i++)
			{
				if(!(delete_assignment[$i]['name'].includes('*')) || delete_assignment[$i]['job'] == "STATUTORY AUDIT - CLEAR REVIEW" || delete_assignment[$i]['job'] == "STATUTORY AUDIT - STOCK TAKE")
				{
					delete_assignment.splice($i);
				}
			}

			for($i=0;$i<assignment.length;$i++){

				var result = [];
				var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
				if(assignment[$i]['FYE'] != null){

					var date = new Date(assignment[$i]['FYE']);
					var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
				}
				else{
					var date_format = "";
				}

				result.push(assignment[$i]['client_name']);
				result.push(assignment[$i]['job']);
				result.push(date_format);

				assignment_placehold.push(result);
			}

			for($i=0;$i<data.length;$i++)
			{
				for($j=0;$j<delete_assignment.length;$j++)
				{
					if(data[$i]['Activities'] == delete_assignment[$j]['name'] && data[$i]['Type of Job'] == delete_assignment[$j]['job'] && data[$i]['FYE'] == delete_assignment[$j]['FYE'])
					{
						data.splice($i,1);
					}
				}

			}

			for($i=0;$i<new_assignment.length;$i++){
				var empty = [];

				temp_all_data.forEach(function(row, index)
				{
					row.forEach(function(item, index)
			  		{
			  			empty[hot.getColHeader()[index]]  = '';
			  		});
		  		});

				for($j=0;$j<assignment_placehold.length;$j++)
				{
					if(assignment_placehold[$j][0] == new_assignment[$i]['name'] && assignment_placehold[$j][1] == new_assignment[$i]['job'] && assignment_placehold[$j][2] == new_assignment[$i]['FYE']) 
					{
						empty['Activities'] = assignment_placehold[$j][0];
						empty['Type of Job'] = assignment_placehold[$j][1];
						empty['FYE'] = assignment_placehold[$j][2];
					}
				}
				data.push(empty);
			}

			data.sort(compare);
  		}

  		hot.loadData(data);
  		// END -----------------------------------------------------------------------------------------------------------------------------------------------------

  // 		if(timesheet_status == '1')
		// {
	 //  		if(bf_timesheet_content != '')
		// 	{
		// 		var bf_content = JSON.parse(bf_timesheet_content);
		// 		var bf_total_col = bf_content[0].length-1;
		// 		var bf_col = data[0].length-2;

		// 		for($i=0;$i<bf_content.length;$i++)
		// 		{
		// 			if(!(bf_content[$i][0].includes('*')))
		// 			{
		// 				bf_content.splice($i);
		// 			}
		// 		}

		// 		for($i=0 ; $i<assignment.length ; $i++){

		// 			for($j=0 ; $j<bf_content.length ; $j++){

		// 				var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

		// 				if(assignment[$i]['FYE'] != null){

		// 					var date = new Date(assignment[$i]['FYE']);
		// 					var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
		// 				}
		// 				else{
		// 					var date_format = "";
		// 				}
		// 				if (assignment[$i]['client_name'] === bf_content[$j][0] && assignment[$i]['job'] === bf_content[$j][1] && date_format === bf_content[$j][2])
		// 				{
		// 					if(data[$i] != undefined)
		// 					{	
		// 						if(data[$i]['current'] == '' || data[$i]['current'] == '0.0')
		// 						{
		// 							data[$i]['b/f'] = bf_content[$j][bf_total_col];
		// 							data[$i]['total'] = bf_content[$j][bf_total_col];
		// 						}
		// 						else
		// 						{
		// 							data[$i]['b/f'] = bf_content[$j][bf_total_col];

		// 							if(bf_content[$j][bf_total_col] == '')
		// 							{
		// 								bf_content[$j][bf_total_col] = 0;
		// 							}

		// 							data[$i]['total'] = set_string(parseFloat(bf_content[$j][bf_total_col]) + parseFloat(data[$i]['current']));
		// 						}
		// 					}
		// 			    }
		// 			}
		// 		}

		// 		hot.loadData(data);
		// 	}
		// }

		if(timesheet_status == '1')
		{
			var table = hot.getData();
			var result = [];
			var empty_row_leave = [];

			table.forEach(function(row, index){
          		var obj  = [];

          		row.forEach(function(item, index){

          			obj[hot.getColHeader()[index]]  = item;
          			empty_row_leave[hot.getColHeader()[index]]  = '';
          		});

          		result.push(obj);
      		});

	  		if(leave_details != '')
			{
				// var leave_list = JSON.parse(leave_details);
				var leave_list = leave_details;
				var total_leave_hours = 0;

	      		empty_row_leave['Activities'] = 'On Leave';
	      		empty_row_leave['Type of Job'] = '-';
  				empty_row_leave['FYE']         = '-';
	      		// hot.loadData(result);

				for($i=0 ; $i<leave_list.length ; $i++){
					var start = new Date(leave_list[$i]['start_date']);
						start = new Date(start.getFullYear()+'-'+(start.getMonth()+1)+'-'+start.getDate());
					var start_date = start.getDate();
					var start_time = leave_list[$i]['start_time'];

					var end = new Date(leave_list[$i]['end_date']);
						end = new Date(end.getFullYear()+'-'+(end.getMonth()+1)+'-'+end.getDate());
					var end_date = end.getDate();
					var end_time = leave_list[$i]['end_time'];

					empty_row_leave.forEach(function(item, index){
						var date     = new Date(timesheet_date);
			    		var date_day = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+index);

						if(date_day >= start && date_day <= end){

							var leave_hours;

							if(index == start_date)
							{
								if(start_time == "1:00 PM"){
									// leave_hours = set_string(4);
									leave_hours = '*4.0';
								}
								else if(end_time == "1:00 PM"){
									// leave_hours = set_string(4);
									leave_hours = '*4.0';
								}
								else{
									// leave_hours = set_string(8);
									leave_hours = '*8.0';
								}
								empty_row_leave[index] = leave_hours;
							}
							else if(index == end_date)
							{
								if(start_time == "1:00 PM"){
									// leave_hours = set_string(4);
									leave_hours = '*4.0';
								}
								else if(end_time == "1:00 PM"){
									// leave_hours = set_string(4);
									leave_hours = '*4.0';
								}
								else{
									// leave_hours = set_string(8);
									leave_hours = '*8.0';
								}
								empty_row_leave[index] = leave_hours;
							}
							else
							{
								// leave_hours = set_string(8);
								leave_hours = '*8.0';
								empty_row_leave[index] = leave_hours;
							}
							leave_hours = leave_hours.replace('*','');
							total_leave_hours = set_string(parseFloat(total_leave_hours) + parseFloat(leave_hours));
						}
					});

				}

				empty_row_leave['current'] = total_leave_hours;
				empty_row_leave['b/f'] = '-';
				empty_row_leave['total'] = total_leave_hours;

				empty_row_leave.forEach(function(item, index){

					if(item == ""){
						empty_row_leave[index] = '-';
					}

				});

				result.push(empty_row_leave);

		      	hot.loadData(result);
			}

			// block before create & after complete date -----------------------------------------------------------------------------------------------------------
			for($i=0 ; $i<assignment.length ; $i++)
			{
				for($n=0 ; $n<result.length ; $n++)
				{
					var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

					if(assignment[$i]['FYE'] != null){

						var date = new Date(assignment[$i]['FYE']);
						var date_format = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
					}
					else{
						var date_format = "";
					}

		  			if(result[$n]['Activities'] == assignment[$i]['client_name'] && result[$n]['Type of Job'] == assignment[$i]['job'] && result[$n]['FYE'] == date_format)
		  			{
		  				var create_on = new Date(assignment[$i]['create_on']);
		  				var create_date =  create_on.getDate();
		  				var create_month =  (create_on.getMonth()+1);

		  				result[$n].forEach(function(item, index){

		  					var date1 = new Date(timesheet_date);
		  					date1 = (date1.getMonth()+1);

		  					if(create_month == date1){
		  						if(index < create_date){
		  							result[$n][index] = "-";
		  						}
		  					}
				  		});

				  		if(assignment[$i]['complete_date'] != null){

				  			var complete_on = new Date(assignment[$i]['complete_date']);
			  				var complete_date =  complete_on.getDate();
			  				var complete_month =  (complete_on.getMonth()+1);

			  				result[$n].forEach(function(item, index){

			  					var date1 = new Date(timesheet_date);
			  					date1 = (date1.getMonth()+1);

			  					if(complete_month == date1){
			  						if(index > complete_date){
			  							result[$n][index] = "-";
			  						}
			  					}
					  		});
				  		}
		  			}
		  		}
		  		hot.loadData(result);
	  		}


	  		// clear review ---------------------------------------------------------------------------------------------------------------
	  		if(review_assignment_list != undefined) {
	  			var table = hot.getData();
				var result = [];

				table.forEach(function(row, index) {
	          		var obj  = [];
	          		row.forEach(function(item, index) {
	          			obj[hot.getColHeader()[index]]  = item;
	          		});
	          		result.push(obj);
	      		});

				var clear_review_assignment = [];
				var existing_assignment = [];

	      		for($a=0; $a<review_assignment_list.length ; $a++) {
	      			var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
						
					if(review_assignment_list[$a][0]['FYE'] != null) {
						var date = new Date(review_assignment_list[$a][0]['FYE']);
						review_assignment_list[$a][0]['FYE'] = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
					} else {
						review_assignment_list[$a][0]['FYE'] = "";
					}

	      			for($b=0; $b<result.length; $b++) {
	      				if(review_assignment_list[$a][0]["client_name"] == result[$b]["Activities"] && review_assignment_list[$a][0]["FYE"] == result[$b]["FYE"] && review_assignment_list[$a][0]["type_of_job"] == result[$b]["Type of Job"] && result[$b]["Type of Job"] == "STATUTORY AUDIT") {

	      					clear_review_assignment.push({
	      						name:result[$b]["Activities"],
	      						FYE:result[$b]['FYE']
	      					});
	      				}
	      			}
	      		}

	      		for($a=0;$a<result.length;$a++) {

					var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
						
					if(result[$a]['FYE'] != null){

						var date = new Date(result[$a]['FYE']);
						var dateFormat = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
					}
					else{
						var dateFormat = "";
					}

					existing_assignment.push({name:result[$a]['Activities'],job:result[$a]['Type of Job'],FYE:dateFormat});
				}

	      		var existing_clear_review_assignment = existing_assignment.filter(item => clear_review_assignment.some(other => item.job == "STATUTORY AUDIT - CLEAR REVIEW" && item.name == other.name && item.FYE == other.FYE));

	      		if(existing_clear_review_assignment.length === 0) {
					for($a=0; $a<clear_review_assignment.length; $a++) {
						var new_clear_review = [];
						var table = hot.getData();
						table.forEach(function(row, index){
			          		row.forEach(function(item, index){
			          			new_clear_review[hot.getColHeader()[index]]  = '';
			          		});
			      		});

						new_clear_review["Activities"] = clear_review_assignment[$a]['name'];
						new_clear_review["Type of Job"] = "STATUTORY AUDIT - CLEAR REVIEW";
						new_clear_review["FYE"] = clear_review_assignment[$a]['FYE'];
						result.push(new_clear_review);
					}
					result.sort(compare);
	      			hot.loadData(result);						
	      		} else {
	      			var new_clear_review_assignment = clear_review_assignment.filter(item => !existing_clear_review_assignment.some(other => other.job == "STATUTORY AUDIT - CLEAR REVIEW" && item.name == other.name && item.FYE == other.FYE));

	      			for($a=0; $a<new_clear_review_assignment.length; $a++) {
		      			var new_clear_review = [];
						var table = hot.getData();
						table.forEach(function(row, index){
			          		row.forEach(function(item, index){
			          			new_clear_review[hot.getColHeader()[index]]  = '';
			          		});
			      		});

						new_clear_review["Activities"] = new_clear_review_assignment[$a]['name'];
						new_clear_review["Type of Job"] = "STATUTORY AUDIT - CLEAR REVIEW";
						new_clear_review["FYE"] = new_clear_review_assignment[$a]['FYE'];
						result.push(new_clear_review);
		      		}

		      		result.sort(compare);
	      			hot.loadData(result);
	      		}
	  		}

	  		// stocktake ----------------------------------------------------------------------------------------------------------------------------------------
	  		var stocktake_assignment_list;
	     	$.ajax({
		        type: "POST",
		        'async' : false,
		        url:  "<?php echo site_url('timesheet/stocktake_assignment_list'); ?>",
		        data: { timesheet_id: timesheet_id },
		        dataType: "json",
		        success: function(data){
		        	stocktake_assignment_list = data;
		        }
		   	});
			if(stocktake_assignment_list.length) {
				var stocktake_assignment = [];
	      		for($a=0;$a<stocktake_assignment_list.length;$a++) {
					var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
					if(stocktake_assignment_list[$a]['fye_date'] != null) {
						var date = new Date(stocktake_assignment_list[$a]['fye_date']);
						var dateFormat = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
					} else {
						var dateFormat = "";
					}

					stocktake_assignment.push({name:stocktake_assignment_list[$a]['company_name'],job:"STATUTORY AUDIT",FYE:dateFormat});
				}

				var table = hot.getData();
				var result = [];
				table.forEach(function(row, index) {
	          		var obj  = [];
	          		row.forEach(function(item, index) {
	          			obj[hot.getColHeader()[index]]  = item;
	          		});
	          		result.push(obj);
	      		});
				var existing_assignment = [];
	      		for($a=0;$a<result.length;$a++) {
					var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
					if(result[$a]['FYE'] != null) {
						var date = new Date(result[$a]['FYE']);
						var dateFormat = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
					} else {
						var dateFormat = "";
					}

					existing_assignment.push({name:result[$a]['Activities'],job:result[$a]['Type of Job'],FYE:dateFormat});
				}

				var existing_stocktake_assignment = existing_assignment.filter(item => stocktake_assignment.some(other => item.job == "STATUTORY AUDIT - STOCK TAKE" && item.name == other.name && item.FYE == other.FYE));

				if(existing_stocktake_assignment.length === 0) {
					for($a=0; $a<stocktake_assignment.length; $a++) {
						var new_stocktake = [];
						var table = hot.getData();
						table.forEach(function(row, index){
			          		row.forEach(function(item, index){
			          			new_stocktake[hot.getColHeader()[index]]  = '';
			          		});
			      		});

						new_stocktake["Activities"] = stocktake_assignment[$a]['name'];
						new_stocktake["Type of Job"] = "STATUTORY AUDIT - STOCK TAKE";
						new_stocktake["FYE"] = stocktake_assignment[$a]['FYE'];
						result.push(new_stocktake);
					}
					result.sort(compare);
	      			hot.loadData(result);						
	      		} else {
	      			var new_stocktake_assignment = stocktake_assignment.filter(item => !existing_stocktake_assignment.some(other => other.job == "STATUTORY AUDIT - STOCK TAKE" && item.name == other.name && item.FYE == other.FYE));

	      			for($a=0; $a<new_stocktake_assignment.length; $a++) {
		      			var new_stocktake = [];
						var table = hot.getData();
						table.forEach(function(row, index){
			          		row.forEach(function(item, index){
			          			new_stocktake[hot.getColHeader()[index]]  = '';
			          		});
			      		});

						new_stocktake["Activities"] = new_stocktake_assignment[$a]['name'];
						new_stocktake["Type of Job"] = "STATUTORY AUDIT - STOCK TAKE";
						new_stocktake["FYE"] = new_stocktake_assignment[$a]['FYE'];
						result.push(new_stocktake);
		      		}

		      		result.sort(compare);
	      			hot.loadData(result);
	      		}

			}

	  		total_calculation();
		}
	}

	function total_calculation(){
		var table1 = hot.getData();
		var result1 = [];
		var empty_row = [];

		table1.forEach(function(row, index){
      		var obj  = [];

      		row.forEach(function(item, index){
      			obj[hot.getColHeader()[index]]  = item;
      			empty_row[hot.getColHeader()[index]]  = '';
      		});

      		result1.push(obj);
  		});

  		var reverse_result = [];
  		var tt_array = [];
  		var array = [];

  		for (var col = 1; col < result1[0].length; col++) {
  			tt_array = [];
	        for (var row = 0; row < result1.length; row++) {

	        	array = result1[row][col];
	        	tt_array.push(array);
	        }
	        reverse_result.push(tt_array);
        }
        var current_total = '0.0';
        reverse_result.forEach(function(row, index){
        	var total = '0.0';
      		row.forEach(function(item, index){
      			if(item!="" && item!=null && item!="-")
      			{
      				if(item.includes('*'))
      				{
      					if(item == '*')
      					{
      						item = item.replace('*','0.0');
      					}
      					else
      					{
      						item = item.replace('*','');
      					}
	      			}

      				total = set_string(parseFloat(total) + parseFloat(item));
      			}
      		});

      		empty_row[index+1] = total;
      		current_total = set_string(parseFloat(current_total) + parseFloat(total));
  		});
        empty_row['current'] = current_total;
        empty_row['Activities'] = 'Total';
        empty_row['Type of Job'] = '-';
        empty_row['FYE'] = '-';
        empty_row['b/f'] = '-';
        result1.push(empty_row);
        hot.loadData(result1);
	}

	function submit_timesheet(){

		var date           = new Date();
		var timesheet_date = new Date('<?php echo $timesheet[0]->month ?>');
		var weekday = getWeekdaysInMonth(timesheet_date.getMonth(), timesheet_date.getFullYear());
		var today          = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
		today = new Date(today);
		var lastDay        = new Date(timesheet_date.getFullYear(), timesheet_date.getMonth() + 1, 0);
		lastDay = new Date(lastDay);

		if(lastDay.getDay() == 6){
			lastDay = new Date(timesheet_date.getFullYear(), timesheet_date.getMonth() + 1, -1);
			lastDay = new Date(lastDay);
		}
		else if(lastDay.getDay() == 0){
			lastDay = new Date(timesheet_date.getFullYear(), timesheet_date.getMonth() + 1, -2);
			lastDay = new Date(lastDay);
		}

		if(today >= lastDay){
			var temp_all_data = hot.getData();
			var total_working_hours_perMonth = weekday * 8;
			var empty_row_idle =[];
			var empty_row =[];
			var reverse_result = [];
			var tt_array = [];
			var result = [];
			var array = [];
			var holiday;

			for($i=0 ; $i<temp_all_data.length ; $i++){

				if(temp_all_data[$i][0] == 'Total'){
					temp_all_data.splice($i,1);
				}
			}

			temp_all_data.forEach(function(row, index){
          		var obj  = [];

          		row.forEach(function(item, index){
          			if(item == null){
          				item = "";
          			}
          			obj[hot.getColHeader()[index]]  = item;
          			empty_row_idle[hot.getColHeader()[index]] ="";
          			empty_row[hot.getColHeader()[index]] ="";
          		});
          		if(obj['Activities'] != "")
          		{
          			result.push(obj);
          		}
      		});

      		if(result.length == '0')
      		{
      			toastr.error('Please Fill Up Your Timesheet', 'Submission Failed');
      		}
      		else
      		{
      			for (var col = 1; col < result[0].length; col++) {
		  			tt_array = [];
			        for (var row = 0; row < result.length; row++) {
			        	array = result[row][col];
			        	tt_array.push(array);
			        }
			        reverse_result.push(tt_array);
		        }

		        reverse_result.forEach(function(row, index){

		        	var total = '8.0';
		        	var is_blocked = true;

		      		row.forEach(function(item, index){
		      			if(item!="" && item!=null && item!='-')
		      			{
		      				if(item.includes('*'))
		      				{
			      				if(item == '*')
		      					{
		      						item = item.replace('*','0.0');
		      					}
		      					else
		      					{
		      						item = item.replace('*','');
		      					}
			      			}

		      				total = set_string(parseFloat(total) - parseFloat(item));
		      				is_blocked = false;
		      			}
		      			else if(item==""){
		      				is_blocked = false;
		      			}
		      		});

		      		if(is_blocked)
		      		{
		      			empty_row_idle[index+1] = '0.0';
		      		}
		      		else
		      		{
		      			empty_row_idle[index+1] = total;
		      		}
		  		});

		  		var idle_current_total = '0.0';

		  		var date2 = new Date(timesheet_date);
		  		timesheetDate = date2.getFullYear()+'-'+(date2.getMonth()+1)+'-'+date2.getDate();

		  		$.ajax({
			        type: "POST",
			        'async' : false,
			        url:  "<?php echo site_url('timesheet/get_holiday2'); ?>",
			        data: '&emp_id='+emp_id+'&month='+timesheetDate,
			        dataType: "json",
			        success: function(data){
			        	holiday = data;
			        }
			   	});

		        empty_row_idle.forEach(function(item, index){
		        	var date     = new Date(timesheet_date);
					var date_day = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+index);
					var date_day2 = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+index;

					if(date_day.getDay() == 0 || date_day.getDay() == 6){
						empty_row_idle[index] = '0.0';
					}

					if(holiday != []){
						for(var h = 0 ; h < holiday.length ; h ++){
							var holiday_date = new Date(holiday[h]['holiday_date']);
							holiday_date2 = holiday_date.getFullYear()+'-'+(holiday_date.getMonth()+1)+'-'+holiday_date.getDate();

							if(holiday_date2 == date_day2){
								empty_row_idle[index] = '0.0';
								total_working_hours_perMonth = parseFloat(total_working_hours_perMonth) - 8.0;
							}
						}
					}

					idle_current_total = set_string(parseFloat(idle_current_total) + parseFloat(empty_row_idle[index]));
					
		        });

		        empty_row_idle['Activities'] = 'Idle';
		        empty_row_idle['Type of Job'] = '-';
        		empty_row_idle['FYE'] = '-';
        		empty_row_idle['b/f'] = '-';
		        empty_row_idle['current'] = idle_current_total;
				empty_row_idle['total'] = idle_current_total;

				// CALCULATE THE TOTAL WORKING HOURS OF A MONTH
				temp = result;
				var total_working_hours = '0.0';

				for(var a = 0;a<temp.length;a++){

					if(temp[a]['current']==''){
						temp[a]['current'] = '0.0';
					}

					total_working_hours = set_string(parseFloat(total_working_hours) + parseFloat(temp[a]['current']));
				}

				// IF TOTAL WORKING HOURS < 160, SHOW IDEL. 
				if(parseFloat(total_working_hours) < parseFloat(total_working_hours_perMonth))
				{
					result.push(empty_row_idle);
				}

				timesheet_result = result;

				// IF TIMESHEET ROW < 5, ADD EMPTY ROW (CAUSE HONDSONTABLE SET MINROWS : 5)
				if(timesheet_result.length < 5){
					var position = 0; 
					var empty_row_needed = 5 - timesheet_result.length;

					for($i=0;$i<timesheet_result.length;$i++){

						if(result[$i]['Activities'] == 'On Leave' || result[$i]['Activities'] == 'Idle'){
							var position = $i;
							break;
						}
					}

					if(position != 0)
					{
						for($i=0;$i<empty_row_needed;$i++){
							timesheet_result.splice(position,0,empty_row);
						}
					}
				}

				bootbox.confirm({
			        message: "Do you wanna to submit this timesheet ?",
			        closeButton: false,
			        buttons: {
			            confirm: {
			                label: 'Submit',
			                className: 'btn_purple'
			            },
			            cancel: {
			                label: 'No',
			                className: 'btn_cancel'
			            }
			        },
			        callback: function (result){
			        	if(result == true){
			        		$.post("<?php echo site_url('timesheet/submit_timesheet'); ?>", { timesheet_id: timesheet_id }, function(result, status){
								if(result){
									toastr.success('Successfully submited timesheet.', 'Saved');

									hot.loadData(timesheet_result);
									total_calculation();
									temp_all_data = hot.getData();

									$.post("<?php echo site_url('timesheet/save_timesheet'); ?>", { timesheet_id: timesheet_id , data: temp_all_data }, function(result, status){
										if(result){
											toastr.success('Successfully saved timesheet.', 'Saved');
											location.reload();
										}
									});

								}
							});
			        	}
			        }
			    })
      		}
		}
		else
		{
			toastr.error('Ensure the timesheet submit only in the end of the month.', 'Unsuccessful Submit');
		}
	}

	function approve_timesheet(){

		bootbox.confirm({
	        message: "Do you wanna to approve this timesheet ?",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Approve',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result){
	        	if(result == true){
	        		$.post("<?php echo site_url('timesheet/approve_timesheet'); ?>", { timesheet_id: timesheet_id }, function(result, status){
						if(result){
							toastr.success('Successfully approve timesheet.', 'Approved');
							location.reload();
						}
					});
	        	}
	        }
	    })
	}

	function insert_row(){

		var table = hot.getData();
		var row = 1;

		if(table[table.length-2][0] == 'On Leave'){
			row = 2;
		}
		
		hot.alter('insert_row', hot.countRows() - row, 1);
	}

	function generate_PDF()
	{
		$("#loadingmessage").show();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('timesheet/generate_PDF'); ?>",
	        data: {"timesheet_id":timesheet_id},
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

	function getWeekdaysInMonth(month, year) {
		var days = daysInMonth(month, year);
		var weekdays = 0;
		for(var i=0; i< days; i++) {
			if (isWeekday(year, month, i+1)) weekdays++;
		}
		return weekdays;
	}

	function isWeekday(year, month, day) {
		var day = new Date(year, month, day).getDay();
		return day !=0 && day !=6;
	}

	function daysInMonth(iMonth, iYear)
    {
    	return 32 - new Date(iYear, iMonth, 32).getDate();
    }
</script>