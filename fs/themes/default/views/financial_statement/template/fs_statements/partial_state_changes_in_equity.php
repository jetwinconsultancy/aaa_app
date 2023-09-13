<style type="text/css">

.table-borderless > tbody > tr > td,
.table-borderless > tbody > tr > th,
.table-borderless > tfoot > tr > td,
.table-borderless > tfoot > tr > th,
.table-borderless > thead > tr > td,
.table-borderless > thead > tr > th 
{
    border: none;
}
</style>

<form id="form_state_changes_in_equity">
	<?php
		if($show_data_content)
		{
	?>
	<input type="hidden" id="statement_doc_type_id" class="statement_doc_type" name="statement_doc_type" value="4">

	<table class="table table-hover table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
		<thead>
			<tr>
				<th style="width: 2%;" >&nbsp;</th>
				<th style="width: 2%;" >&nbsp;</th>
				<th style="width: 30.5%;"></th>
				<th style="width: 0.5%;"></th>
				<th id="group_company_header" colspan="10" style="text-align: center; border-bottom: 1px solid #000000;"><?php echo $group_company?></th>
			</tr>
			<tr class='header_title'>
				<th style="width: 2%;">&nbsp;</th>
				<th style="width: 2%;">&nbsp;</th>
				<th style="width: 30.5%;">&nbsp;</th>
				<th style="width: 0.5%; vertical-align: bottom !important;">
					<a data-toggle="tooltip" data-trigger="hover" style="color:#c61156; font-weight:bold; cursor: pointer;" title="Add Column" onclick="add_column(this)"><i class="fa fa-plus-circle" style="font-size:14px;"></i></a>
				</th>
				<?php
					$column = 0;

					foreach ($fs_state_changes_in_equity_header as $key => $value) 
					{
						if($column < 3)
						{
							echo '<th style="text-align: center;"></br><input class="form-control" type="text" name="header['.$column.']" value="'.$value.'"></br></th>';
						}
						else
						{
							echo '<th style="text-align: center;"><a class="remove-col" data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a><input type="text" class="form-control" value="'.$value.'" name="header['.$column.']"></th>';
						}
						

						$column++;
					}

					if(!$fs_state_changes_in_equity_header){
						echo '<th style="text-align: center;"></br><input class="form-control" type="text" name="header[0]" value="Share capital"></br></th>'.
							'<th style="text-align: center;"></br><input class="form-control" type="text" name="header[1]" value="Revenue reserves"></br></th>'.
							'<th style="text-align: center;"></br><input class="form-control" type="text" name="header[2]" value="Total"></br></th>';	
							// '<th style="text-align: center;"></br><input class="form-control" type="text" name="header[2]" value="Fair value reserves"></br></th>';	
					}
				?>
			</tr>
			<tr class='dollar_sign'>
				<th style="width: 2%;">&nbsp;</th>
				<th style="width: 2%;">&nbsp;</th>
				<th style="width: 30.5%;"></th>
				<th style="width: 0.5%; vertical-align: middle !important;">
				<?php
					foreach ($fs_state_changes_in_equity_header as $key => $value) 
					{
						echo '<th style="text-align: center; border-bottom: 1px solid #000;">S$</th>';
					}

					if(!$fs_state_changes_in_equity_header){
						echo 	'<th style="text-align: center; border-bottom: 1px solid #000;">S$</th>'.
								'<th style="text-align: center; border-bottom: 1px solid #000;">S$</th>'.
								'<th style="text-align: center; border-bottom: 1px solid #000;">S$</th>';
					}
				?>
			</tr>
		</thead>
		<!-- BODY -->
		<tbody>
			<tr class="prior_group blank_tr">
				<td style="width: 4%; text-align: center;" colspan="2">
					<a class="curr_year_group" data-toggle="tooltip" data-trigger="hover" style="color:#c61156; font-weight:bold; cursor: pointer;" title="Add Row" onclick="add_row(this, 'prior', '')"><i class="fa fa-plus-circle" style="font-size:14px;"></i></a>
				</td>
				<td style="width: 30.5%;"><b>Prior year</b></td>
				<td style="width: 0.5%;">&nbsp;</td>
				<td style="text-align: center;"></td>
				<td style="text-align: center;"></td>
				<td style="text-align: center;"></td>	
			</tr>

			<tr class="prior_group input_tr"  data-row-number-prior="0">
				<td style="width: 2%;">
					<input type="hidden" name="prior_yr_row[0][0]" <?php echo 'value="'.$fs_state_changes_in_equity_prior_group[0]['id'].'"' ?>>
					<input type="checkbox" class="cbx" name="prior_yr_row[0][1]" value="1">
				</td>
				<td style="width: 2%;">
					<!-- <a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a> -->
				</td>
				<td style="width: 30.5%;"><input class="form-control" type="text" name="prior_yr_row[0][2]" <?php echo 'value="'.$fs_state_changes_in_equity_prior_group[0]['description'].'"' ?>></td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					$column = 3;

					foreach ($fs_state_changes_in_equity_prior_group[0]['row_item'] as $key => $value) 
					{
						echo '<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" name="prior_yr_row[0]['.$column.']" value="'.$value.'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';

						$column++;
					}

					if(!$fs_state_changes_in_equity_prior_group[0]['row_item'])
					{
						echo '<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" name="prior_yr_row[0][3]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
							'<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" name="prior_yr_row[0][4]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
							'<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" name="prior_yr_row[0][5]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';	
					}
				?>	
			</tr>

			<tr class="prior_group double_line_tr_prior" id="prior_last_rw">
				<td style="width: 2%;">&nbsp;</td>
				<td style="width: 2%;">&nbsp;</td>
				<td style="width: 30.5%;"><input class="form-control" type="text" name="footer[prior][0]" <?php echo 'value="'.$fs_state_changes_in_equity_footer[0]['description'].'"' ?>></td>
				<td style="width: 0.5%;">&nbsp;</td>
				<?php
					$column = 1;

					foreach ($fs_state_changes_in_equity_footer[0]['footer_item'] as $key => $value) 
					{
						echo '<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[prior]['.$column.']" value="'.$value.'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';

						$column++;
					}

					if(!$fs_state_changes_in_equity_footer[0]){
						echo 	'<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[prior][1]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
								'<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[prior][2]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
								'<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[prior][3]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';
					}
					
				?>
			</tr>

			<tr class="blank_tr">
				<td style="width: 4%; text-align: center;" colspan="2">
					<a class="curr_year_group" data-toggle="tooltip" data-trigger="hover" style="color:#c61156; font-weight:bold; cursor: pointer;" title="Add Row" onclick="add_row(this, 'current', '')"><i class="fa fa-plus-circle" style="font-size:14px;"></i></a>
				</td>
				<td style="width: 30.5%;"><b>Current year</b></td>
				<td style="width: 0.5%;">&nbsp;</td>
				<td style="text-align: center;"></td>
				<td style="text-align: center;"></td>
				<td style="text-align: center;"></td>	
			</tr>

			<tr id="clone_model" class="input_tr" data-row-number-current="0">
				<td style="width: 2%;">
					<input type="hidden" class="input_id" name="curr_yr_row[0][0]" <?php echo 'value="'.$fs_state_changes_in_equity_current_group[0]['id'].'"' ?>>
					<input type="checkbox" class="cbx" name="curr_yr_row[0][1]" value="1" <?php echo ($fs_state_changes_in_equity_current_group[0]['is_subtotal'])?"checked":"" ?>/>
					<!-- <input type="hidden" class="hidden_cbx_value" name="curr_yr_row[0][1]" value="1"> -->
				</td>
				<td style="width: 2%;">
					<!-- <a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a> -->
				</td>
				<td style="width: 30.5%;"><input class="form-control" type="text" name="curr_yr_row[0][2]" <?php echo 'value="'.$fs_state_changes_in_equity_current_group[0]['description'].'"' ?>></td>
				<td style="width: 0.5%;">&nbsp;</td>
				<?php
					$column = 3;

					foreach ($fs_state_changes_in_equity_current_group[0]['row_item'] as $key => $value) 
					{
						echo '<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;"  name="curr_yr_row[0]['.$column.']" value="'.$value.'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';

						$column++;
					}

					if(!$fs_state_changes_in_equity_current_group[0]['row_item']){
						echo '<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" name="curr_yr_row[0][3]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
							'<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" name="curr_yr_row[0][4]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
							'<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" name="curr_yr_row[0][5]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';	
					}
					
				?>
			</tr>

			<tr id="current_last_rw" class="double_line_tr_current">
				<td style="width: 2%;">&nbsp;</td>
				<td style="width: 2%;">&nbsp;</td>
				<td style="width: 30.5%;"><input class="form-control" type="text" name="footer[current][0]"  <?php echo 'value="'.$fs_state_changes_in_equity_footer[1]['description'].'"' ?>></td>
				<td style="width: 0.5%;">&nbsp;</td>
				<?php
			
					$column = 1;
					foreach ($fs_state_changes_in_equity_footer[1]['footer_item'] as $key => $value) 
					{
						echo '<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[current]['.$column.']" value="'.$value.'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';

						$column++;
					}

					if(!$fs_state_changes_in_equity_footer[1]){
						echo 	'<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[current][1]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
								'<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[current][2]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>'.
								'<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="footer[current][3]" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';
					}
					
				?>
			</tr>

			<tr class="prior_group blank_tr">
				<td style="width: 4%; text-align: center;" colspan="2">&nbsp;</td>
				<td style="width: 30.5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>
				<td style="text-align: center;">&nbsp;</td>
				<td style="text-align: center;">&nbsp;</td>
				<td style="text-align: center;">&nbsp;</td>	
			</tr>
		</tbody>
	</table>
	<?php
		}
		else
		{
			echo '<p style="text-align:center; font-weight: bold; font-size: 20px; margin-top:50px;">Account Category is not managed!</p>' .
				 '<p style="text-align:center; font-weight: bold; font-size: 14px; margin-top:10px;">Please complete Account Category before preview this document!</p>';
		}
	?>
</form>

<?php
  function negative_bracket($number)
  {
      if($number == 0)
      {
          return "-";
      }
      elseif($number < 0)
      {
          return "(" . number_format(abs($number)) . ")";
      }
      else
      {
          return number_format($number);
      }
  }
?>

<script type="text/javascript">
	var curr_yr_row_index = 1;
	var prior_yr_row_index = 1;
	var header_index = <?php echo json_encode(isset($fs_state_changes_in_equity_header)?count($fs_state_changes_in_equity_header):"")?>;
	var fs_state_changes_in_equity_current_group = <?php echo json_encode(isset($fs_state_changes_in_equity_current_group)?$fs_state_changes_in_equity_current_group:"")?>;
	var fs_state_changes_in_equity_prior_group = <?php echo json_encode(isset($fs_state_changes_in_equity_prior_group)?$fs_state_changes_in_equity_prior_group:"")?>;
	var is_first_set = <?php echo json_encode(isset($first_set)?$first_set:"") ?>;
	var arr_deleted_row = [];

	//hide prior year if first set
	check_prior();
	retrieve_data(fs_state_changes_in_equity_current_group);
	retrieve_data(fs_state_changes_in_equity_prior_group);
	check_subtotal_line();

	$('body').on('click', '.remove-col', function ( event ) {
		// var col_value = $("#group_company_header").attr('colspan');
		// col_value = parseInt(col_value);
		// col_value -= 1;

		// $("#group_company_header").attr('colspan', col_value);
	    // Get index of parent TD among its siblings (add one for nth-child)
	    // console.log($(this).parent());
	    var ndx = $(this).parent().index()+1;
	    console.log(ndx);
	    console.log($('th'));
	    console.log($('td'));
	    // Find all TD elements with the same index
	    $('th').remove(':nth-child(' + ndx + ')');
	    $('td').remove(':nth-child(' + ndx + ')');
	});

	$('.cbx').change(function(){
	    if($(this).is(':checked')){
	        var td_counter = 0;

			var subtotal_tr = ($(this).parent().parent());
			if(!subtotal_tr.attr("class").includes("prior_group")){
				subtotal_tr.attr("class", "input_tr");
			}
			else
			{
				subtotal_tr.attr("class", "prior_group input_tr");
			}
			// subtotal_tr.attr("class", "subtotal_tr");

			// console.log(subtotal_tr);
			subtotal_tr.find('td').each(function()
			{
				// console.log($(this));
				if(td_counter >= 4)
				{
					$(this).attr("style", "text-align:right; border-top: 1px solid black;");
				}
				td_counter++;
			});
	    } else {
	        var td_counter = 0;

			var subtotal_tr = ($(this).parent().parent());
			if(!subtotal_tr.attr("class").includes("prior_group")){
				subtotal_tr.attr("class", "input_tr");
			}
			else
			{
				subtotal_tr.attr("class", "prior_group input_tr");
			}
			// subtotal_tr.attr("class", "input_tr");

			// console.log(subtotal_tr);
			subtotal_tr.find('td').each(function(){
				// console.log($(this));
				if(td_counter >= 4)
				{
					$(this).attr("style", "text-align:right;");
				}
				td_counter++;
			});
	    }
	});

	function retrieve_data(list)
	{
		for (i = 0; i < list.length; i++) 
		{
			if(i == 0) continue;
			
			add_row("", list[i]['current_prior'], list[i]);
			// .then(
			// 	function(){
			// 		add_dir_row_init(list[i]['id']);
			// 	});
			// console.log(list[i]);
			// console.log('--- newline ---');
		}
	}

	function check_prior(){
		if(is_first_set == 1)
		{
			$(".prior_group").hide();
		}
		else
		{
			$(".prior_group").show();
		}
	}

	function check_subtotal_line()
	{
		$('#form_state_changes_in_equity input[type=checkbox]').each(function () {
			if(this.checked)
			{
				var td_counter = 0;

				var subtotal_tr = ($(this).parent().parent());
				// console.log((subtotal_tr.attr("class")).includes("prior_group"));

				if(!(subtotal_tr.attr("class")).includes("prior_group"))
				{
					subtotal_tr.attr("class", "subtotal_tr");
				}
				else
				{
					subtotal_tr.attr("class", "prior_group subtotal_tr");
				}

				subtotal_tr.find('td').each(function(){
					// console.log($(this));
					if(td_counter >= 4)
					{
						$(this).attr("style", "text-align:right; border-top: 1px solid black;");
					}
					td_counter++;
				});

			}
		   
		});
	}

	function add_row(element, group_type, data){  
		console.log(data);
		// $('input:text').each(function() {
		//     $(this).attr('value', $(this).val());
		// });

		if(data != '')
		{
			var id_value_hidden 		  = data['id'];
			var subtotal_checkbox 	 	  = data['is_subtotal'];
			// var note_num_displayed 		  = data['note_num_displayed'];
			var description_value  		  = data['description'];
			var row_item_value 		 	  = data['row_item'];
			// var value_group_lye_end	 	  = data['value_group_lye_end']
			// var value_company_ye 	  	  = data['value_company_ye'];
			// var value_company_lye_end 	  = data['value_company_lye_end'];
	

		}


		if(group_type == "current"){
			var tr = $('#current_last_rw');
		}
		else if(group_type == "prior")
		{
			var tr = $('#prior_last_rw');
		}

		// for ()

		var clone_tr = $('#clone_model');
		var clone = clone_tr.clone(true).removeAttr("id");




		// console.log(clone.find('td input.form-control').attr("name", 'curr_yr_row['+curr_yr_row_index+']['+clone.find('td input.form-control').index+']'));
		if(!data == '')
		{
			if(group_type == "current")
			{
				var loop_counter = 0;
				var row_item_counter = 0;
				clone.attr("data-row-number-current", curr_yr_row_index);
				clone.find('td input').each(function(){

				// console.log($(this).attr("value", "ffffffffffffffffffffffffffffffff"));
				if(loop_counter == 0){
					$(this).attr("value", id_value_hidden);
				}
				else if(loop_counter == 1){
					if(subtotal_checkbox == 1)
					{
						$(this).prop("checked", true);
					}
					else
					{
						$(this).prop("checked", false);
					}
				}
				else if(loop_counter == 2){
					$(this).attr("value", description_value);
				}
				else{
					$(this).attr("value", row_item_value[row_item_counter]);
					row_item_counter++;
				}
				$(this).attr("name", 'curr_yr_row['+curr_yr_row_index+']['+clone.find('td input').index(this)+']');
				$(this).attr('value', $(this).val());
		
					
					loop_counter++;
				});

				curr_yr_row_index++;	
			}
			else if(group_type == "prior")
			{
				var prior_loop_counter = 0;
				var prior_row_item_counter = 0;
				clone.attr("data-row-number-prior", prior_yr_row_index);
				clone.find('td input').each(function(){
					if(prior_loop_counter == 0){
						$(this).attr("value", id_value_hidden);
					}
					else if(prior_loop_counter == 1){
						if(subtotal_checkbox == 1)
						{
							$(this).prop("checked", true);
						}
						else
						{
							$(this).prop("checked", false);
						}
					}
					else if(prior_loop_counter == 2){
						$(this).attr("value", description_value);
					}
					else{
						$(this).attr("value", row_item_value[prior_row_item_counter]);
						prior_row_item_counter++;
					}
					$(this).attr("name", 'prior_yr_row['+prior_yr_row_index+']['+clone.find('td input').index(this)+']');
					$(this).attr('value', $(this).val());
		
					
					prior_loop_counter++;


				});

				prior_yr_row_index++;
			}

		}
		else
		{
			if(group_type == "current")
			{
				clone.attr("data-row-number-current", curr_yr_row_index);
				clone.find('td input').each(function(){
					// console.log($(this));
					$(this).attr("name", 'curr_yr_row['+curr_yr_row_index+']['+clone.find('td input').index(this)+']');
				});

				curr_yr_row_index++;	
			}
			else if(group_type == "prior")
			{
				clone.attr("data-row-number-prior", prior_yr_row_index);
				clone.find('td input').each(function(){
					// console.log($(this));
					$(this).attr("name", 'prior_yr_row['+prior_yr_row_index+']['+clone.find('td input').index(this)+']');
				});

				prior_yr_row_index++;
			}

			clone.find(".input_id").val('');
			clone.find(':text, input[type=number]').val('');
		}
		// if(group_type == "current")
		// {
		// 	clone.attr("data-row-number-current", curr_yr_row_index);
		// 	clone.find('td input').each(function(){
		// 		// console.log($(this));
		// 		$(this).attr("name", 'curr_yr_row['+curr_yr_row_index+']['+clone.find('td input').index(this)+']');
		// 	});

		// 	curr_yr_row_index++;	
		// }
		// else if(group_type == "prior")
		// {
		// 	clone.attr("data-row-number-prior", prior_yr_row_index);
		// 	clone.find('td input').each(function(){
		// 		// console.log($(this));
		// 		$(this).attr("name", 'prior_yr_row['+prior_yr_row_index+']['+clone.find('td input').index(this)+']');
		// 	});

		// 	prior_yr_row_index++;
		// }
		
		
		// clone.find(':number').val('');
		clone.find("td:nth-child(2)").append('<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>');

		if(group_type == "prior"){
			clone.attr('class', 'prior_group input_tr');
		}
		else
		{
	    	clone.attr('class', 'input_tr');
		}

		tr.before(clone);
	    
	}

	function add_column(element)
	{
		var col_value = $("#group_company_header").attr('colspan');
		col_value = parseInt(col_value);
		col_value += 1;

		$("#group_company_header").attr('colspan', col_value);


		// console.log($(element).parent().parent().parent().parent());
		$(element).parent().parent().parent().parent().parent().find('.table thead .header_title').append('<th style="text-align: center;"><a class="remove-col" data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a><input type="text" class="form-control" value="" name="'+'header['+header_index+']'+'"></th>');
		$(element).parent().parent().parent().parent().parent().find('.table thead .dollar_sign').append('<th style="text-align: center; border-bottom: 1px solid black;">S$</th>');
		$(element).parent().parent().parent().parent().parent().find('.table tbody .blank_tr').append('<td style="text-align: center;">&nbsp;</td>');
    	$(element).parent().parent().parent().parent().parent().find('.table tbody .input_tr').append('<td style="text-align: center;"><input type="number" class="form-control" style="text-align:right;" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>');
    	$(element).parent().parent().parent().parent().parent().find('.table tbody .subtotal_tr').append('<td style="text-align: center; border-top:1px solid black;"><input type="number" class="form-control" style="text-align:right;" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>');
    	$(element).parent().parent().parent().parent().parent().find('.table tbody .double_line_tr_current').append('<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="'+'footer[current]['+(header_index+1)+']'+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>');
    	$(element).parent().parent().parent().parent().parent().find('.table tbody .double_line_tr_prior').append('<td style="text-align: center; border-bottom: 3px double #000; border-top: 1px solid #000;"><input type="number" class="form-control" style="text-align:right;" name="'+'footer[prior]['+(header_index+1)+']'+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>');

    	$(element).parent().parent().parent().parent().parent().find('.table tbody .input_tr').each(function(){
    		// console.log($(this));
    		if(($(this).attr("class")).includes("prior_group"))
    		{

    			$(this).find('td input:last').attr('name', 'prior_yr_row['+$(this).attr("data-row-number-prior")+']['+$(this).find('td input').index($(this).find('td input:last'))+']');
    		}
    		else
    		{
    			// console.log($("tr").index($(this)));
    			$(this).find('td input:last').attr('name', 'curr_yr_row['+$(this).attr("data-row-number-current")+']['+$(this).find('td input').index($(this).find('td input:last'))+']');
    		}
    		// console.log($("tr").index($(this)));
    		// console.log($(this).find('td input'));
    		// console.log($(this).find('td input').index($(this).find('td input:last')));

    	});

    	$(element).parent().parent().parent().parent().parent().find('.table tbody .subtotal_tr').each(function(){
    		// console.log($(this));
    		if(($(this).attr("class")).includes("prior_group"))
    		{

    			$(this).find('td input:last').attr('name', 'prior_yr_row['+$(this).attr("data-row-number-prior")+']['+$(this).find('td input').index($(this).find('td input:last'))+']');
    		}
    		else
    		{
    			// console.log($("tr").index($(this)));
    			$(this).find('td input:last').attr('name', 'curr_yr_row['+$(this).attr("data-row-number-current")+']['+$(this).find('td input').index($(this).find('td input:last'))+']');
    		}
    		// console.log($("tr").index($(this)));
    		// console.log($(this).find('td input'));
    		// console.log($(this).find('td input').index($(this).find('td input:last')));

    	});

    	header_index++;
    }

	// function delete_column(element, event)
	// {
	//   var ndx = $(element).parent().index() + 1;
 //      // Find all TD elements with the same index
 //      $('th', event.delegateTarget).remove(':nth-child(' + ndx + ')');
 //      $('td', event.delegateTarget).remove(':nth-child(' + ndx + ')');
	// }




</script>