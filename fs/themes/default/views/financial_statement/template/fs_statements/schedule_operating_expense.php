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

<form id="form_schedule_operating_expense">
	<?php
		if($show_data_content)
		{
	?>
	<table id="tbl_schedule_operating_expense" class="table table-hover table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%;">
		<thead>
			<!-- <tr>
				<th style="width: 65%;"></th>
				<th colspan="2" style="text-align: center; width: 15%"> Company </th>
			</tr> -->
			<tr>
				<th rowspan="0" style="width: 65%;"></th>
				<?php 
					if(!empty($current_fye_end))
					{
						echo '<th style="border-top:1px solid black; border-bottom:1px solid black; text-align:center; width: 15%">';
						
						if($display_restated)
						{
							echo '<br/>';
						}

						echo $current_fye_end . '<br/>' .
							'$' .
						'</th>';
					}

					if(!empty($last_fye_end))
					{
						echo '<th style="border-top:1px solid black; border-bottom:1px solid black; text-align:center; width: 15%">';

						if($display_restated)
						{
							echo 'Restated <br/>';
						}
						
						echo $last_fye_end . ' <br/>' .
								'$' .
							'</th>';
					}

				?>
			</tr>
			<!-- <tr>
				<?php 
					if(!empty($current_fye_end))
					{
						echo '<th style="text-align: center; border-bottom:1px solid black !important;">$</th>';
					}
					
					if(!empty($last_fye_end))
					{
						echo '<th style="text-align: center; border-bottom:1px solid black;">$</th>';
					}
				?>
			</tr> -->
		</thead>
		<tbody>
			<?php
				$total = [];
				$total_ly = [];
				$index = 0;
				// echo json_encode($schedule_operating_expense_data);

				// print_r($schedule_operating_expense_data[0]['child_array']);

				foreach($schedule_operating_expense_data[0]['child_array'] as $key => $value)
				{
					// print_r($value);

					$temp_total 	= 0.00;
					$temp_total_ly 	= 0.00;

					if(count($value['parent_array']) > 0)
					{
						echo 
							'<tr>' . 
								'<td><i>' . $value['parent_array'][0]['description'] . '</i></td>' .	// Description in Level 2
								'<td colspan="2"></td>' .
							'</tr>';

						foreach ($value['child_array'] as $key2 => $details_data) 
						{
							// print_r($details_data);

							if(count($details_data['parent_array']) > 0)
							{
								$temp_total 	+= convert_string_to_number($details_data['parent_array'][0]['total_c']);
								$temp_total_ly 	+= convert_string_to_number($details_data['parent_array'][0]['total_c_lye']);

								// echo 
								// 	'<tr>' . 
								// 		'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
								// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c']) . '</td>';

								echo 
									'<tr>' . 
										'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
										'<td style="text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c']) . '</td>';

								if(!empty($last_fye_end))
								{
									// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c_lye']) . '</td>';

									echo '<td style="text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c_lye']) . '</td>';
								}

								echo	
									'</tr>';
							}
							else
							{
								$temp_total 	+= convert_string_to_number($details_data['child_array']['value']);
								$temp_total_ly 	+= convert_string_to_number($details_data['child_array']['company_end_prev_ye_value']);

								// echo 
								// 	'<tr>' . 
								// 		'<td>' . $details_data['child_array']['description'] . '</td>' .	// Description in level 3 (no sub)
								// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['child_array']['value']) . '</td>';

								echo 
									'<tr>' . 
										'<td>' . $details_data['child_array']['description'] . '</td>' .	// Description in level 3 (no sub)
										'<td style="text-align: right;">' . negative_bracket($details_data['child_array']['value']) . '</td>';

								if(!empty($last_fye_end))
								{
									// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['child_array']['company_end_prev_ye_value']) . '</td>';

									echo '<td style="text-align: right;">' . negative_bracket($details_data['child_array']['company_end_prev_ye_value']) . '</td>';
								}

								echo	
									'</tr>';
							}

							// echo 
							// 	'<tr>' . 
							// 		'<td>' . $details_data['description'] . '</td>' .
							// 		'<td style="text-align:right; ">'. negative_bracket($details_data['value']) .'</td>';

							// if(!empty($last_fye_end))
							// {
							// 	echo '<td style="text-align:right;">' . negative_bracket($details_data['company_end_prev_ye_value']) . '</td>';

							// 	$index++;	// for item in sub category.
							// }

							// echo '</tr>';
						}

						/* ---------------------- show total for the category ---------------------- */
						// if(count($details['data']) > 0)
						// {
							echo 
								'<tr>' .
									'<td></td>' .
									'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
										// '<input type="hidden" name="current_fye_end['. $key .']" value="'. $current_fye_end .'">' .
										'<input type="hidden" name="category_total['. $key .']" value="'. $temp_total .'">' .
										'<input type="hidden" class="parent_fs_categorized_account" name="categorized_account_id['. $key .']" value="'. $details['fs_categorized_account_id'] .'">' .
										'<span>' . negative_bracket($temp_total) . '</span>' .
									'</td>';

							if(!empty($last_fye_end))
							{
								echo '<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' . 
										'<span id="SOE_subtotal_'. $details['fs_categorized_account_id'] .'">' . negative_bracket($temp_total_ly) . '</span>' . 
									'</td>';
							}

							echo '</tr>';
						// }
						

						array_push($total, $temp_total);
						array_push($total_ly, $temp_total_ly);
						/* ---------------------- END OF show total for the category ---------------------- */
					}
					else 
					{
						$temp_total 	+= convert_string_to_number($value['child_array']['value']);
						$temp_total_ly 	+= convert_string_to_number($value['child_array']['company_end_prev_ye_value']);

						echo '<tr>' . 
								'<td colspan="5">&nbsp;</td>' . 
							'</tr>';

						// echo 
						// 	'<tr>' . 
						// 		'<td>' . $value['child_array']['description'] . '</td>' .	// Description in Level 2 (without child)
						// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($value['child_array']['value']) . '</td>';

						echo 
							'<tr>' . 
								'<td>' . $value['child_array']['description'] . '</td>' .	// Description in Level 2 (without child)
								'<td style="text-align: right;">' . negative_bracket($value['child_array']['value']) . '</td>';

						if(!empty($last_fye_end))
						{
							// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($value['child_array']['company_end_prev_ye_value']) . '</td>';

							echo '<td style="text-align: right;">' . negative_bracket($value['child_array']['company_end_prev_ye_value']) . '</td>';
						}

						echo	
							'</tr>';

						array_push($total, $temp_total);
						array_push($total_ly, $temp_total_ly);
					}

					


					// foreach($value as $key1 => $details)
					// {
					// 	$temp_total 	= 0.00;
					// 	$temp_total_ly 	= 0.00;

					// 	if(count($details['data']) > 0)
					// 	{
					// 		echo 
					// 			'<tr>' . 
					// 				'<td><i>' . $details['category_name'] . '</i></td>' .
					// 				'<td colspan="2"></td>' .
					// 				// '<td></td>' .
					// 			'</tr>';

					// 		foreach ($details['data'] as $key2 => $details_data) {
					// 			$temp_total += $details_data['value'];
					// 			$temp_total_ly += $details_data['company_end_prev_ye_value'];

					// 			echo 
					// 				'<tr>' . 
					// 					'<td>' . $details_data['description'] . '</td>' .
					// 					'<td style="text-align:right; ">'. negative_bracket($details_data['value']) .'</td>';

					// 			if(!empty($last_fye_end))
					// 			{
					// 				// echo '<td style="text-align="right;">' . 
					// 				// 		'<input type="hidden" name="SOE_sub_fs_categorized_account_id['. $index .']" value="'. $details_data['id'] .'">' .
					// 				// 		'<input type="text" name="SOE_company_end_prev_year_value[' . $index . ']" class="form-control SOE_all_values SOE_values_under_' . $details['fs_categorized_account_id'] . '" style="text-align: right;" value="' . $details_data['company_end_prev_ye_value'] . '" onchange="calculation_SOE('. $details['fs_categorized_account_id'] .')">' .
					// 				// 	'</td>';

					// 				echo '<td style="text-align:right;">' . negative_bracket($details_data['company_end_prev_ye_value']) . '</td>';

					// 				$index++;	// for item in sub category.
					// 			}

					// 			echo '</tr>';
					// 		}
					// 	}
					// 	else
					// 	{
					// 		echo 
					// 			'<tr>' . 
					// 				'<td>' . $details['category_name'] . '</td>' .
					// 				'<td style="border-bottom:1px solid black; text-align: right;"> - </td>';

					// 		if(!empty($last_fye_end))
					// 		{
					// 			echo '<td style="border-bottom:1px solid black; text-align: right;"> - </td>';
					// 		}

					// 		echo	
					// 			'</tr>';
					// 	}

					// 	// // show total for the category
					// 	// if(count($details['data']) > 0)
					// 	// {
					// 	// 	echo 
					// 	// 		'<tr>' .
					// 	// 			'<td></td>' .
					// 	// 			'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
					// 	// 				// '<input type="hidden" name="current_fye_end['. $key .']" value="'. $current_fye_end .'">' .
					// 	// 				'<input type="hidden" name="category_total['. $key .']" value="'. $temp_total .'">' .
					// 	// 				'<input type="hidden" class="parent_fs_categorized_account" name="categorized_account_id['. $key .']" value="'. $details['fs_categorized_account_id'] .'">' .
					// 	// 				'<span>' . negative_bracket($temp_total) . '</span>' .
					// 	// 			'</td>';

					// 	// 	if(!empty($last_fye_end))
					// 	// 	{
					// 	// 		echo '<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' . 
					// 	// 				'<span id="SOE_subtotal_'. $details['fs_categorized_account_id'] .'">' . negative_bracket($temp_total_ly) . '</span>' . 
					// 	// 			'</td>';
					// 	// 	}

					// 	// 	echo '</tr>';
					// 	// }
						

					// 	// array_push($total, $temp_total);
					// 	// array_push($total_ly, $temp_total_ly);
					// }
				}

				// calculate overall total
				$overall_total = 0.00;
				$overall_total_ly = 0.00;
				
				foreach($total as $counter => $each_num)
				{
					$overall_total += $each_num;
				}

				foreach($total_ly as $counter => $each_num)
				{
					$overall_total_ly += $each_num;
				}

				echo '<tr>' .
						'<td>Total operating expenses</td>' .
						'<td style="text-align:right; border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">' . 
							'<input type="hidden" name="overall_operating_expenses" value="'. $overall_total .'">' .
							'<span>' . negative_bracket($overall_total) . '</span>'.
						'</td>';

						if(!empty($last_fye_end))
						{
							echo 
							'<td style="text-align:right; border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">' . 
								'<input type="hidden" name="overall_operating_expenses_ly" value="' . $overall_total_ly . '">' .
								'<span id="SOE_lye_overall_total">' . negative_bracket($overall_total_ly) . '</span>'.
							'</td>';
						}
					'</tr>';
			?>
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

<script src="themes/default/assets/js/financial_statement/schedule_operating_expense.js" charset="utf-8"></script>

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

	function convert_string_to_number($number)
	{
		if($number == "-")
      	{
          	return 0;
      	}
      	elseif($number == "")
      	{
          	return 0;
      	}
      	else
      	{
          	return str_replace('(', "", str_replace(')', "", str_replace(',', "", $number)));;
      	}
	}
?>

<script type="text/javascript">
	
</script>