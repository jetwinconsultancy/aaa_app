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

<form id="form_state_comp_income">
	<?php 
		if($show_data_content)
		{
	?>
	<input type="hidden" id="statement_doc_type_id" class="statement_doc_type" name="statement_doc_type" value="1">

	<table class="table table-hover table-borderless" style="width: 100%; border-collapse: collapse; color:black;" border="0">
		<thead>
			<tr>
				<th style="width: 1%;">&nbsp;</th>
				<th style="width: 44%;" rowspan="3">&nbsp;</th>
				<!-- <th style="width: 44%;" rowspan="3">&nbsp;</th> -->
				<th style="text-align: center; width: 5%;" rowspan="2">&nbsp;</th>
				<th style="width: 0.5%;" rowspan="2">&nbsp;</th>
				<?php
					if($is_group)
					{
						echo '<th style="width: 25%; text-align: center; border-bottom:1px solid black;" colspan="2">Group</th>
							  <th style="width: 1%;">&nbsp;</th>';
					}
				?>
				
				<th style="width: 25%; text-align: center; border-bottom:1px solid black;" colspan="2">Company</th>
			</tr>
			<tr>
				<th style="width: 1%;">&nbsp;</th>
				<?php
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo '<th style="width: 12.5%; text-align: center;">' . $current_fye_end . '</th>';
							echo '<th style="width: 12.5%; text-align: center;">' . $last_fye_end . '</th>';
						}
						else
						{
							echo '<th style="width: 12.5%; text-align: center;" colspan="2">' . $current_fye_end . '</th>';
						}
							  
						echo '<th style="width: 1%;">&nbsp;</th>';
					}
				?>

				<?php
					if(!empty($last_fye_end))
					{
						echo '<th style="width: 12.5%; text-align: center;">' .$current_fye_end . '</th>';
						echo '<th style="width: 12.5%; text-align: center;">' . $last_fye_end . '</th>';
					}
					else
					{
						echo '<th style="width: 12.5%; text-align: center;" colspan="2">' .$current_fye_end . '</th>';
					}
				?>
			</tr>
			<tr>
				<th style="width: 1%;">&nbsp;</th>
				<th style="text-align: center; width: 5%;">
					<!-- DO NOT DELETE THIS -->
					<!-- <div class="input-group">
		                <input type="checkbox" name="on_auto_rearrange" checked/>
		                <input type="hidden" name="auto_rearrange_value" value="<?php echo $auto_rearrange_value; ?>"/>
		            </div> -->
					Note
				</th>
				<th style="width: 0.5%;">&nbsp;</th>

				<?php
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>';
							echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>';
						}
						else
						{
							echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;" colspan="2">$</th>';
						}

						echo '<th style="width: 1%;">&nbsp;</th>';
					}

					if(!empty($last_fye_end))
					{
						echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>';
						echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>';
					}
					else
					{
						echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;" colspan="2">$</th>';
					}
				?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 44%;">&nbsp;</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
						else
						{
							echo '<td style="width: 25%;" colspan="2">&nbsp;</td>';
						}

						echo '<td style="width: 1%;">&nbsp;</td>';
					}

					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
					else
					{
						echo '<td style="width: 25%;" colspan="2">&nbsp;</td>';
					}
				?>
			</tr>

			<!-- main category -->
			<?php
			
				$index = 0;

				// $total_mc_company_ye = 0.00;
				// $total_mc_company_lye = 0.00;
				// $total_mc_group_ye = 0.00;
				// $total_mc_group_lye = 0.00;

				$note_no = 1;
				$index_o = 0;
				$index_category = 0;
				$index_sci = 0;

				foreach($income_list[0] as $key => $value)
				{
					if(!empty($value[0]['parent'][0]['company_end_prev_ye_value']))
					{
						$c_lye_value = $value[0]['parent'][0]['company_end_prev_ye_value'];
					}
					else
					{
						$c_lye_value = $value[0]['parent_array'][0]['total_c_lye'];
					}

					// $total_mc_company_ye  += $value[0]['parent_array'][0]['total_c'];
					// $total_mc_company_lye += $c_lye_value;

					// $total_mc_group_ye 	+= $value[0]['parent_array'][0]['group_end_this_ye_value'];
					// $total_mc_group_lye += $value[0]['parent_array'][0]['group_end_prev_ye_value'];
					
					echo 
					'<tr>'.
						'<td style="width: 1%;">&nbsp;</td>' .
						// input hidden
						'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id[' . $index_category . ']" class="SCI_C_sub_fs_categorized_account_id" value="' . $value[0]['parent_array'][0]['id'] . '">' .
						'<td>'. $value[0]['parent_array'][0]['description'] . '</td>';	// display description 

						if(in_array($value[0]['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
						{
							$key = array_search($value[0]['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

							// show numbering
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
									'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
									'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
									'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '">' .
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
										'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '</i>' . 
									'</a>' . 
								'</td>';
						}
						else
						{
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' . 
									'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
									'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
									'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
										'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
									'</a>' . 
								'</td>';
						}

						echo '<td style="width: 0.5%;">&nbsp;</td>';

					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo  
							'<td align="right">' . 
								'<input type="number" class="form-control main_category_group_ye" name="SCI_C_value_group_ye[' . $index_category . ']" style="text-align:right;" value="' . $value[0]['parent_array'][0]['group_end_this_ye_value'] . '" onchange="SCI_calculation(\'main_category_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';

							echo 
							'<td align="right">' . 
								'<input type="number" class="form-control main_category_group_lye" name="SCI_C_value_group_lye_end[' . $index_category . ']" style="text-align:right;" value="' . $value[0]['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="SCI_calculation(\'main_category_group_lye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						else
						{
							echo  
							'<td align="right" colspan="2">' . 
								'<input type="number" class="form-control main_category_group_ye" name="SCI_C_value_group_ye[' . $index_category . ']" style="text-align:right;" value="' . $value[0]['parent_array'][0]['group_end_this_ye_value'] . '" onchange="SCI_calculation(\'main_category_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}

						echo '<td></td>';
					}
					
					if(!empty($last_fye_end))
					{
						echo '<td align="right">' . 
							negative_bracket($value[0]['parent_array'][0]['total_c']) . 
						'</td>';

						echo '<td align="right">' . 
							negative_bracket($c_lye_value) . 
						'</td>';
					}
					else
					{
						echo '<td align="right" colspan="2">' . 
							negative_bracket($value[0]['parent_array'][0]['total_c']) . 
						'</td>';
					}
					'</tr>';

					$index ++;
					$note_no++;
					$index_o ++;
					$index_category ++;
				}

				// // display total for main category
				// echo 
				// '<tr>' . 
				// 		'<td colspan="2"></td>' . 
				// 		'<td></td>' .
				// 		'<td style="width: 0.5%;">&nbsp;</td>';

				// if($is_group)
				// {
				// 	if(!empty($last_fye_end))
				// 	{
				// 		echo 
				// 		'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' .
				// 			'<span id="main_category_group_ye_subtotal">' . negative_bracket($total_mc_group_ye) . '</span>' . 
				// 		'</td>';

				// 		echo 
				// 		'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' . 
				// 			'<span id="main_category_group_lye_subtotal">' . negative_bracket($total_mc_group_lye) . '</span>' . 
				// 		'</td>';
				// 	}
				// 	else
				// 	{
				// 		echo 
				// 		'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;" colspan="2">' .
				// 			'<span id="main_category_group_ye_subtotal">' . negative_bracket($total_mc_group_ye) . '</span>' . 
				// 		'</td>';
				// 	}
						
				// 	echo '<td></td>';
				// }

				// if(!empty($last_fye_end))
				// {
				// 	echo 	
				// 	'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' . 
				// 		'<span id="main_category_company_ye_subtotal">' . negative_bracket($total_mc_company_ye) . '</span>' . 
				// 	'</td>' . 
				// 	'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' . 
				// 		'<span id="main_category_company_lye_subtotal">' . negative_bracket($total_mc_company_lye) . '</span>' . 
				// 	'</td>';
				// }
				// else
				// {
				// 	echo 	
				// 	'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;" colspan="2">' . 
				// 		'<span id="main_category_company_ye_subtotal">' . negative_bracket($total_mc_company_ye) . '</span>' . 
				// 	'</td>';
				// }
						
				
				// echo '</tr>';

				if(count($income_list[0]) > 0)
				{
					echo '<tr><td colspan="9">&nbsp;</td></tr>';
				}
			?>
			<!-- END OF main category -->

			<!-- Other income -->
			<?php
			
				// $index = 0;

				$total_oi_company_ye = 0.00;
				$total_oi_company_lye = 0.00;
				$total_oi_group_ye = 0.00;
				$total_oi_group_lye = 0.00;

				// $note_no = 1;
				// $index_o = 0;
				// $index_category = 0;
				// $index_sci = 0;

				// print_r($other_income_list[0]);

				foreach($other_income_list as $key => $value)
				{
					if(!empty($value[0]['parent'][0]['company_end_prev_ye_value']))
					{
						$c_lye_value = $value[0]['parent'][0]['company_end_prev_ye_value'];
					}
					else
					{
						$c_lye_value = $value[0]['parent_array'][0]['total_c_lye'];
					}

					$total_oi_company_ye  += $value[0]['parent_array'][0]['total_c'];
					$total_oi_company_lye += $c_lye_value;

					$total_oi_group_ye 	+= $value[0]['parent_array'][0]['group_end_this_ye_value'];
					$total_oi_group_lye += $value[0]['parent_array'][0]['group_end_prev_ye_value'];

					echo 
					'<tr>'.
						'<td style="width: 1%;">&nbsp;</td>' .
						// input hidden
						'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id[' . $index_category . ']" class="SCI_C_sub_fs_categorized_account_id" value="' . $value[0]['parent_array'][0]['id'] . '">' .
						'<td>'. $value[0]['parent_array'][0]['description'] . '</td>';	// display description 

						if(in_array($value[0]['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
						{
							$key = array_search($value[0]['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

							// show numbering
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
									'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
									'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
									'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '">' .
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
										'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '</i>' . 
									'</a>' . 
								'</td>';
						}
						else
						{
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' . 
									'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
									'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
									'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
										'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
									'</a>' . 
								'</td>';
						}

						echo '<td style="width: 0.5%;">&nbsp;</td>';

					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo  
							'<td align="right">' . 
								'<input type="number" class="form-control expenses_group_ye" name="SCI_C_value_group_ye[' . $index_category . ']" style="text-align:right;" value="' . $value[0]['parent_array'][0]['group_end_this_ye_value'] . '" onchange="SCI_calculation(\'expenses_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';

							echo 
							'<td align="right">' . 
								'<input type="number" class="form-control expenses_group_lye" name="SCI_C_value_group_lye_end[' . $index_category . ']" style="text-align:right;" value="' . $value[0]['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="SCI_calculation(\'expenses_group_lye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						else
						{
							echo  
							'<td align="right" colspan="2">' . 
								'<input type="number" class="form-control expenses_group_ye" name="SCI_C_value_group_ye[' . $index_category . ']" style="text-align:right;" value="' . $value[0]['parent_array'][0]['group_end_this_ye_value'] . '" onchange="SCI_calculation(\'expenses_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}

						echo '<td></td>';
					}
					
					if(!empty($last_fye_end))
					{
						echo '<td align="right">' . 
							negative_bracket($value[0]['parent_array'][0]['total_c']) . 
						'</td>';

						echo '<td align="right">' . 
							negative_bracket($c_lye_value) . 
						'</td>';
					}
					else
					{
						echo '<td align="right" colspan="2">' . 
							negative_bracket($value[0]['parent_array'][0]['total_c']) . 
						'</td>';
					}
					'</tr>';

					$index ++;
					$note_no++;
					$index_o ++;
					$index_category ++;
				}
			?>
			<!-- END OF Other income -->

			<!-- Changes in inventories -->
			<?php
				if(count($changes_in_inventories) > 0)
				{
					echo 
					'<tr>'. 
						'<td style="width: 1%;">&nbsp;</td>' .
						'<td>' .
							// input hidden
							'<input type="hidden" class="fs_state_comp_id" name="fs_state_comp_id[' . $index_sci . ']" value="' . $changes_in_inventories['id'] . '">' . 
							'<input type="hidden" name="fs_list_state_comp_income_section_id[' . $index_sci . ']" value="1">' .
							'<input type="hidden" name="SCI_description[' . $index_sci . ']" value="' . $changes_in_inventories['description'] . '">' . 
							$changes_in_inventories['description'] . 
						'</td>' . 
						'<td></td>' . 
						'<td style="width: 0.5%;">&nbsp;</td>';

						if($is_group)
						{
							if(!empty($last_fye_end))
							{
								echo 
								'<td align="right">' . 
									'<input type="number" class="form-control expenses_group_ye" name="SCI_value_group_ye[' . $index_sci . ']" style="text-align:right;" value="' . $changes_in_inventories['value_group_ye'] . '" onchange="SCI_calculation(\'expenses_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'</td>' . 
								'<td align="right">' . 
									'<input type="number" class="form-control expenses_group_lye" name="SCI_value_group_lye_end[' . $index_sci . ']" style="text-align:right;" value="' . $changes_in_inventories['value_group_lye_end'] . '" onchange="SCI_calculation(\'expenses_group_lye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
								'</td>';
							}
							else
							{
								echo 
								'<td align="right" colspan="2">' . 
									'<input type="number" class="form-control expenses_group_ye" name="SCI_value_group_ye[' . $index_sci . ']" style="text-align:right;" value="' . $changes_in_inventories['value_group_ye'] . '" onchange="SCI_calculation(\'expenses_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'</td>';;
							}
							
							echo '<td></td>';
						}
					
					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right">' .
							negative_bracket($changes_in_inventories['value_company_ye']) . 
						'</td>' . 
						'<td align="right">' .
							negative_bracket($changes_in_inventories['value_company_lye_end']) . 
						'</td>';
					}
					else
					{
						echo
						'<td align="right" colspan="2">' .
							negative_bracket($changes_in_inventories['value_company_ye']) . 
						'</td>';
					}

					echo '</tr>';

					$index_o ++;
					$index++;
					$index_sci ++;
				}
			?>
			<!-- END OF Changes in inventories -->

			<!-- Purchases -->
			<?php
				if(count($purchases) > 0)
				{
					echo 
					'<tr>'.
						'<td style="width: 1%;">&nbsp;</td>' . 
						// hidden fields
						'<input type="hidden" class="fs_state_comp_id" name="fs_state_comp_id[' . $index_sci . ']" value="' . $purchases['id'] . '">' . 
						'<input type="hidden" name="fs_list_state_comp_income_section_id[' . $index_sci . ']" value="2">' . 
						'<input type="hidden" name="SCI_description[' . $index_sci . ']" value="' . $purchases['description'] . '">' . 
						'<td>'. $purchases['description'] . '</td>';

					if(in_array($purchases['id'], array_column($fs_notes_details, 'fs_state_comp_income_id')))
					{
						$key = array_search($purchases['id'], array_column($fs_notes_details, 'fs_state_comp_income_id'));

						// show numbering
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
								'<input type="hidden" name="SCI_fs_note_details_id[' . $index_sci . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
								'<input type="hidden" name="SCI_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
								'<input type="hidden" name="SCI_fs_note_num_displayed[' . $index_sci . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '">' .
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
									'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '</i>' . 
								'</a>' . 
							'</td>';
					}
					else
					{
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
								'<input type="hidden" name="SCI_fs_note_details_id[' . $index_sci . ']" value="0">' . 
								'<input type="hidden" name="SCI_fs_note_templates_master_id[' . $index_sci . ']" class="fs_note_templates_master_id" value="0">' . 
								'<input type="hidden" name="SCI_fs_note_num_displayed[' . $index_sci . ']" class="fs_note_num_displayed" value="">' . 
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
									'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
								'</a>' . 
							'</td>';
					}

					$note_no++;

					echo '<td style="width: 0.5%;">&nbsp;</td>';
					
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo 
							'<td align="right">' . 
								'<input type="number" class="form-control expenses_group_ye" style="text-align:right;" name="SCI_value_group_ye[' . $index_sci . ']" value="' . $purchases['value_group_ye'] . '" onchange="SCI_calculation(\'expenses_group_ye\',\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
							echo
							'<td align="right">' . 
								'<input type="number" class="form-control expenses_group_lye" style="text-align:right;" name="SCI_value_group_lye_end[' . $index_sci . ']" value="' . $purchases['value_group_lye_end'] . '" onchange="SCI_calculation(\'expenses_group_lye\',\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						else
						{
							echo '<td align="right" colspan="2">' . 
								'<input type="number" class="form-control expenses_group_ye" style="text-align:right;" name="SCI_value_group_ye[' . $index_sci . ']" value="' . $purchases['value_group_ye'] . '" onchange="SCI_calculation(\'expenses_group_ye\',\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						 
						echo '<td></td>';
					}	 
					
					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right" class="taxation_company_ye">' . 
							negative_bracket($purchases['value_company_ye']) . 
						'</td>' . 
						'<td align="right">' . 
							negative_bracket($purchases['value_company_lye_end']) .
						'</td>';
					}
					else
					{
						echo 
						'<td align="right" class="taxation_company_ye" colspan="2">' . 
							negative_bracket($purchases['value_company_ye']) . 
						'</td>';
					}
					
					'</tr>';

					$total_purchases_group_ye 	 += $purchases['value_group_ye'];
					$total_purchases_group_lye 	 += $purchases['value_group_lye_end'];
					$total_purchases_company_ye  += $purchases['value_company_ye'];
					$total_purchases_company_lye += $purchases['value_company_lye_end'];

					$index ++;
					$index_o++;
					$index_sci ++;
				}
			?>
			<!-- END OF Purchases -->

			<!-- expenses category -->
			<?php

				$total_expenses_group_ye 	= 0.00;
				$total_expenses_group_lye 	= 0.00;
				$total_expenses_company_ye 	= 0.00;
				$total_expenses_company_lye = 0.00;
				
				foreach($expense_list as $key => $value)
				{
					echo 
					'<tr>'.
						'<td style="width: 1%;">&nbsp;</td>' .
						'<td>'. 
							// input hidden
							'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id[' . $index_category . ']" class="SCI_C_sub_fs_categorized_account_id" value="' . $value[0]['parent_array'][0]['id'] . '">' .
							$value[0]['parent_array'][0]['description'] . 
						'</td>';

					if(in_array($value[0]['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
					{
						$key = array_search($value[0]['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

						// show numbering
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
								'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
								'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
								'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '">' .
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
										'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '</i>' . 
								'</a>' . 
							'</td>';
					}
					else
					{
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
								'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
								'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
								'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
									'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
								'</a>' . 
							'</td>';
					}

					echo '<td style="width: 0.5%;">&nbsp;</td>';
					
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo 
							'<td align="right">' . 
								'<input type="number" class="form-control expenses_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $value[0]['parent_array'][0]['group_end_this_ye_value'] . '" onchange="SCI_calculation(\'expenses_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>' . 
							'<td align="right">' . 
								'<input type="number" class="form-control expenses_group_lye" style="text-align:right;" name="SCI_C_value_group_lye_end[' . $index_category . ']" value="' . $value[0]['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="SCI_calculation(\'expenses_group_lye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						else
						{
							echo 
							'<td align="right" colspan="2">' . 
								'<input type="number" class="form-control expenses_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $value[0]['parent_array'][0]['group_end_this_ye_value'] . '" onchange="SCI_calculation(\'expenses_group_ye\', \'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						
						echo '<td></td>';
					}	 
					
					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right">' . 
							negative_bracket($value[0]['parent_array'][0]['total_c']) . 
						'</td>' . 
						'<td align="right">' . 
							negative_bracket($value[0]['parent_array'][0]['total_c_lye']) . 
						'</td>';
					}
					else
					{
						echo 
						'<td align="right" colspan="2">' . 
							negative_bracket($value[0]['parent_array'][0]['total_c']) . 
						'</td>';
					}
					
					echo '</tr>';

					$total_expenses_group_ye 	+= $value[0]['parent_array'][0]['group_end_this_ye_value'];
					$total_expenses_group_lye 	+= $value[0]['parent_array'][0]['group_end_prev_ye_value'];
					$total_expenses_company_ye 	+= $value[0]['parent_array'][0]['total_c'];
					$total_expenses_company_lye += $value[0]['parent_array'][0]['total_c_lye'];

					$index++;
					$index_o ++;
					$note_no ++;
					$index_category ++;
				}

				// display total
				echo '<tr>'.
						'<td colspan="2"></td>' . 
						'<td></td>' . 
						'<td style="width: 0.5%;">&nbsp;</td>';

				if($is_group)
				{
					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' . 
							'<span id="expenses_group_ye_subtotal">' . negative_bracket($total_oi_group_ye + $total_expenses_group_ye + $changes_in_inventories['value_group_ye'] + $total_purchases_group_ye) . '</span>' .
						'</td>' . 
						'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' . 
							'<span id="expenses_group_lye_subtotal">' . negative_bracket($total_oi_group_lye + $total_expenses_group_lye + $changes_in_inventories['value_group_lye_end'] + $total_purchases_group_lye) . '</span>' .
						'</td>';
					}
					else
					{
						echo 
						'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;" colspan="2">' . 
							'<span id="expenses_group_ye_subtotal">' . negative_bracket($total_oi_group_ye + $total_expenses_group_ye + $changes_in_inventories['value_group_ye'] + $total_purchases_group_ye) . '</span>' .
						'</td>';
					}
					
					echo '<td></td>';
				}
				
				if(!empty($last_fye_end))
				{
					echo 	
					'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' . 
						'<span id="expenses_company_ye_subtotal">' . negative_bracket($total_oi_company_ye + $total_expenses_company_ye + $changes_in_inventories['value_company_ye'] + $total_purchases_company_ye) . '</span>' .
					'</td>' . 
					'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">' . 
						'<span id="expenses_company_lye_subtotal">' . negative_bracket($total_oi_company_lye + $total_expenses_company_lye  + $changes_in_inventories['value_company_lye_end'] + $total_purchases_company_lye) . '</span>' .
					'</td>';
				}
				else
				{
					echo 	
					'<td align="right" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;" colspan="2">' . 
						'<span id="expenses_company_ye_subtotal">' . negative_bracket($total_oi_company_ye + $total_expenses_company_ye + $changes_in_inventories['value_company_ye'] + $total_purchases_company_ye) . '</span>' .
					'</td>';
				}
				
				echo '</tr>';

				echo '<tr><td colspan="9">&nbsp;</td></tr>';
			?>
			<!-- END OF expenses category -->

			<!-- Profit/Loss before tax -->
			<?php
				$a_g_ye  = $total_mc_group_ye;
				$a_g_lye = $total_mc_group_lye;
				$a_c_ye  = $total_mc_company_ye;
				$a_c_lye = $total_mc_company_lye;

				$b_g_ye  = $total_expenses_group_ye    + $changes_in_inventories['value_group_ye'];
				$b_g_lye = $total_expenses_group_lye   + $changes_in_inventories['value_group_lye_end'];
				$b_c_ye  = $total_expenses_company_ye  + $changes_in_inventories['value_company_ye'];
				$b_c_lye = $total_expenses_company_lye + $changes_in_inventories['value_company_lye_end'];

				if(count($pl_be4_tax) > 0)
				{
					echo 
					'<tr>' . 
						'<td style="width: 1%;">' . 
						'<td>';

							echo '<input type="hidden" class="fs_state_comp_id" name="fs_state_comp_id[' . $index_sci . ']" value="' . $pl_be4_tax['id'] . '">';
							echo '<input type="hidden" name="fs_list_state_comp_income_section_id[' . $index_sci . ']" value="3">';
						// if(isset($pl_be4_tax['description']))
						// {
							echo '<input class="profit_loss_display_b4_tax_input" type="hidden" name="SCI_description[' . $index_sci . ']" value="' . $pl_be4_tax['description'] . '">';
							echo '<span class="profit_loss_display_b4_tax">' . $pl_be4_tax['description'] . '</span>';
						// }
						echo '</td>';

						if(in_array($pl_be4_tax['id'], array_column($fs_notes_details, 'fs_state_comp_income_id')))
						{
							// print_r($fs_notes_details);

							// print_r(array($pl_be4_tax['id'], array_column($fs_notes_details, 'fs_state_comp_income_id')));

							$key = array_search($pl_be4_tax['id'], array_column($fs_notes_details, 'fs_state_comp_income_id'));

							// print_r($fs_notes_details[$key]);

							// show numbering
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
									'<input type="hidden" name="SCI_fs_note_details_id[' . $index_sci . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
									'<input type="hidden" name="SCI_fs_note_templates_master_id[' . $index_sci . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
									'<input type="hidden" name="SCI_fs_note_num_displayed[' . $index_sci . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '">' .
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
										'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '</i>' . 
									'</a>' . 
								'</td>';
						}
						else
						{
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
									'<input type="hidden" name="SCI_fs_note_details_id[' . $index_sci . ']" value="0">' . 
									'<input type="hidden" name="SCI_fs_note_templates_master_id[' . $index_sci . ']" class="fs_note_templates_master_id" value="0">' . 
									'<input type="hidden" name="SCI_fs_note_num_displayed[' . $index_sci . ']" class="fs_note_num_displayed" value="">' . 
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
										'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
									'</a>' . 
								'</td>';
						}

						$note_no++;

						$edit_note_no_class = '';

						if(!is_null($involved_note_list[$index_o]['id']) && $involved_note_list[$index_o]['id'] != 0)
						{
							$edit_note_no_class = "edit_note_no";
						}

						echo '<td style="width: 0.5%;">' .
								'<a class="edit_note ' . $edit_note_no_class . '" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Edit Note Number" style="font-weight:bold; cursor: pointer; color:green;" onclick="add_edit_note_no(' . $note_no .', ' . $fs_notes_details[$key]['fs_note_templates_master_id'] . ')">' . 
									'<i class="fa fa-edit" style="font-size:16px;"></i>' .
								'</a>' .
							'</td>';

					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo 
							'<td align="right" class="pl_be4_tax_double_line">';

							if(isset($pl_be4_tax['value_group_ye']))
							{
								echo '<span id="profit_loss_be4_group_ye">' . negative_bracket($pl_be4_tax['value_group_ye']) . '</span>' .
								'<input id="profit_loss_be4_group_ye_input" type="hidden" name="SCI_value_group_ye[' . $index_sci . ']" value="' . $pl_be4_tax['value_group_ye'] . '">';
							}
								
							echo 
							'</td>' .
							'<td align="right" class="pl_be4_tax_double_line">';

							if(isset($pl_be4_tax['value_group_lye_end']))
							{
								echo '<span id="profit_loss_be4_group_lye">' . negative_bracket($pl_be4_tax['value_group_lye_end']) . '</span>' .
								'<input id="profit_loss_be4_group_lye_input" type="hidden" name="SCI_value_group_lye_end[' . $index_sci . ']" value="' . $pl_be4_tax['value_group_lye_end'] . '">';
							}
							
							echo 
							'</td>';
						}
						else
						{
							echo 
							'<td align="right" colspan="2" class="pl_be4_tax_double_line">';

							if(isset($pl_be4_tax['value_group_ye']))
							{
								echo '<span id="profit_loss_be4_group_ye">' . negative_bracket($pl_be4_tax['value_group_ye']) . '</span>' .
								'<input id="profit_loss_be4_group_ye_input" type="hidden" name="SCI_value_group_ye[' . $index_sci . ']" value="' . $pl_be4_tax['value_group_ye'] . '">';
							}
								
							echo 
							'</td>';
						}
						
						echo '<td></td>';
					}

					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right" class="pl_be4_tax_double_line">';

						if(isset($pl_be4_tax['value_company_ye']))
						{
							echo '<span id="profit_loss_be4_company_ye">' . negative_bracket($pl_be4_tax['value_company_ye']) . '</span>';
						}

						echo 	
						'</td>' .
						'<td align="right" class="pl_be4_tax_double_line">';

						if(isset($pl_be4_tax['value_company_lye_end']))
						{
							echo '<span id="profit_loss_be4_company_lye">' . negative_bracket($pl_be4_tax['value_company_lye_end']) . '</span>';
						}

						echo
						'</td>';
					}
					else
					{
						echo 
						'<td align="right" colspan="2" class="pl_be4_tax_double_line">';

						if(isset($pl_be4_tax['value_company_ye']))
						{
							echo '<span id="profit_loss_be4_company_ye">' . negative_bracket($pl_be4_tax['value_company_ye']) . '</span>';
						}

						echo 	
						'</td>';
					}
						
					echo '</tr>';

					$note_no++;
					$index_o++;
					$index_sci ++;
				}
			?>
			<!-- END OF Profit/Loss before tax  -->

			<!-- TAXATION -->
			<?php 
				$total_additional_group_ye 		= 0.00;
				$total_additional_group_lye 	= 0.00;
				$total_additional_company_ye 	= 0.00;
				$total_additional_company_lye 	= 0.00;

				foreach($additional_list[0] as $key => $value)
				{
					if(count($value['child_array']) > 0)
					{
						echo 
						'<tr>'.
							'<td style="width: 1%;">' . 
							// hidden fields
							'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id['. $index_category .']" class="SCI_C_sub_fs_categorized_account_id" value="'. $value['parent_array'][0]['id'] .'">' .

							'<td>'. $value['parent_array'][0]['description'] . '</td>';

						// print_r(array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));
						// print_r(array($value['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')));

						if(in_array($value['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
						{
							$tax_note_key = array_search($value['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

							// show numbering
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$tax_note_key]['fs_ntfs_layout_template_default_id'] . '" />' .
									'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$tax_note_key]['id'] . '">' .
									'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$tax_note_key]['fs_note_templates_master_id'] . '">' .
									'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$tax_note_key]['note_no']) . '">' .
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
											'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$tax_note_key]['note_no']) . '</i>' . 
									'</a>' . 
								'</td>';
						}
						else
						{
							echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
									'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
									'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
									'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
									'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
									'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
										'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
									'</a>' . 
								'</td>';
						}

						echo '<td style="width: 0.5%;">&nbsp;</td>';
						
						if($is_group)
						{
							if(!empty($last_fye_end))
							{
								echo 
								'<td align="right">' . 
									'<input type="number" class="form-control taxation_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_this_ye_value'] . '" onchange="calculate_profit_after_tax(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'</td>';

								echo 
								'<td align="right">' . 
									'<input type="number" class="form-control taxation_group_lye" style="text-align:right;" name="SCI_C_value_group_lye_end[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="calculate_profit_after_tax(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'</td>';
								
							}
							else
							{
								echo 
								'<td align="right" colspan="2">' . 
									'<input type="number" class="form-control taxation_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_this_ye_value'] . '" onchange="calculate_profit_after_tax(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'</td>';
							}

							echo '<td></td>';
						}	 
						
						if(!empty($last_fye_end))
						{
							echo 
							// '<td align="right" class="taxation_company_ye">' . 
							'<td align="right">' . 
								negative_bracket($value['parent_array'][0]['total_c']) . 
							'</td>' . 
							'<td align="right">' . 
								negative_bracket($value['parent_array'][0]['total_c_lye']) .
							'</td>';
						}
						else
						{
							echo 
							// '<td align="right" class="taxation_company_ye">' . 
							'<td align="right" colspan="2">' . 
								negative_bracket($value['parent_array'][0]['total_c']) . 
							'</td>';
						}

						echo '</tr>';

						$total_additional_group_ye 		+= $value['parent_array'][0]['group_end_this_ye_value'];
						$total_additional_group_lye 	+= $value['parent_array'][0]['group_end_prev_ye_value'];
						$total_additional_company_ye 	+= $value['parent_array'][0]['total_c'];
						$total_additional_company_lye 	+= $value['parent_array'][0]['total_c_lye'];

						$index ++;
						$index_o++;
						$note_no++;
						$index_category ++;
					}
				}
			?>
			<!-- END OF TAXATION -->

			<!-- SHARE OF ASSOCIATES PROFIT OR LOSS -->
			<?php 
				$total_soa_pl_group_ye 		= 0.00;
				$total_soa_pl_group_lye 	= 0.00;
				$total_soa_pl_company_ye 	= 0.00;
				$total_soa_pl_company_lye 	= 0.00;

				foreach($soa_pl_list[0] as $key => $value)
				{
					echo 
					'<tr>'.
						'<td style="width: 1%;">' . 
						// hidden fields
						'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id['. $index_category .']" class="SCI_C_sub_fs_categorized_account_id" value="'. $value['parent_array'][0]['id'] .'">' .

						'<td>'. $value['parent_array'][0]['description'] . '</td>';

					if(in_array($value['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
					{
						$key = array_search($value['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

						// show numbering
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
								'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
								'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
								'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '">' .
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
										'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '</i>' . 
								'</a>' . 
							'</td>';
					}
					else
					{
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
								'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
								'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
								'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
									'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
								'</a>' . 
							'</td>';
					}

					echo '<td style="width: 0.5%;">&nbsp;</td>';
					
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo 
							'<td align="right">' . 
								'<input type="number" class="form-control soa_pl_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_this_ye_value'] . '" onchange="calculate_profit_after_tax(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';

							echo 
							'<td align="right">' . 
								'<input type="number" class="form-control soa_pl_group_lye" style="text-align:right;" name="SCI_C_value_group_lye_end[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="calculate_profit_after_tax(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						else
						{
							echo 
							'<td align="right" colspan="2">' . 
								'<input type="number" class="form-control soa_pl_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_this_ye_value'] . '" onchange="calculate_profit_after_tax(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						
						echo '<td></td>';
					}	 
					
					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right">' . 
							negative_bracket($value['parent_array'][0]['total_c']) . 
						'</td>' . 
						'<td align="right">' . 
							negative_bracket($value['parent_array'][0]['total_c_lye']) .
						'</td>';
					}
					else
					{
						echo 
						'<td align="right" colspan="2">' . 
							negative_bracket($value['parent_array'][0]['total_c']) . 
						'</td>';
					}

					
					echo '</tr>';

					$total_soa_pl_group_ye 		+= $value['parent_array'][0]['group_end_this_ye_value'];
					$total_soa_pl_group_lye 	+= $value['parent_array'][0]['group_end_prev_ye_value'];
					$total_soa_pl_company_ye 	+= $value['parent_array'][0]['total_c'];
					$total_soa_pl_company_lye 	+= $value['parent_array'][0]['total_c_lye'];

					$index ++;
					$index_o++;
					$note_no++;
					$index_category ++;
				}
			?>
			<!-- END OF SHARE OF ASSOCIATES PROFIT OR LOSS -->

			<!-- Profit/Loss after tax -->
			<?php
				// $pl_after_tax_g_ye  = $a_g_ye  - $b_g_ye  - $total_additional_group_ye;
				// $pl_after_tax_g_lye = $a_g_lye - $b_g_lye - $total_additional_group_lye;
				// $pl_after_tax_c_ye  = $a_c_ye  - $b_c_ye  - $total_additional_company_ye;
				// $pl_after_tax_c_lye = $a_c_lye - $b_c_lye - $total_additional_company_lye;
				if(count($pl_after_tax) > 0)
				{
					echo 
					'<tr>' . 
						'<td style="width: 1%;">' . 
						'<td>';

						echo '<input type="hidden" class="fs_state_comp_id" name="fs_state_comp_id[' . $index_sci . ']" value="' . $pl_after_tax['id'] . '">';
						echo '<input type="hidden" name="fs_list_state_comp_income_section_id[' . $index_sci . ']" value="4">';

						echo '<input class="profit_loss_display_after_tax_input" type="hidden" name="SCI_description[' . $index_sci . ']" value="' . $pl_after_tax['description'] . '">';
						echo '<span class="profit_loss_display_after_tax">' . $pl_after_tax['description'] . '</span>';

					if(in_array($pl_after_tax['id'], array_column($fs_notes_details, 'fs_state_comp_income_id')))
					{
						$key = array_search($pl_after_tax['id'], array_column($fs_notes_details, 'fs_state_comp_income_id'));

						// show numbering
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
								'<input type="hidden" name="SCI_fs_note_details_id[' . $index_sci . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
								'<input type="hidden" name="SCI_fs_note_templates_master_id[' . $index_sci . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
								'<input type="hidden" name="SCI_fs_note_num_displayed[' . $index_sci . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '">' .
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
										'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_no']) . '</i>' . 
								'</a>' . 
							'</td>';
					}
					else
					{
						echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
								'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
								'<input type="hidden" name="SCI_fs_note_details_id[' . $index_sci . ']" value="0">' . 
								'<input type="hidden" name="SCI_fs_note_templates_master_id[' . $index_sci . ']" class="fs_note_templates_master_id" value="0">' . 
								'<input type="hidden" name="SCI_fs_note_num_displayed[' . $index_sci . ']" class="fs_note_num_displayed" value="">' . 
								'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
									'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
								'</a>' . 
							'</td>';
					}

					$note_no++;

					$edit_note_no_class = '';

					if(!is_null($involved_note_list[$index_o]['id']) && $involved_note_list[$index_o]['id'] != 0)
					{
						$edit_note_no_class = "edit_note_no";
					}

					echo '<td style="width: 0.5%;">' .
							'<a class="edit_note ' . $edit_note_no_class . '" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Edit Note Number" style="font-weight:bold; cursor: pointer; color:green;" onclick="add_edit_note_no(' . $note_no .', ' . $fs_notes_details[$key]['fs_note_templates_master_id'] . ')">' . 
								'<i class="fa fa-edit" style="font-size:16px;"></i>' .
							'</a>' .
						'</td>';

					if ($is_group) 
					{
						if(!empty($last_fye_end))
						{
							echo 
							'<td align="right" style="border-top: 1px solid #000000;" class="pl_after_tax_double_line">';

							if(isset($pl_after_tax['value_group_ye']))
							{
								echo '<span id="pl_after_tax_g_ye">' . negative_bracket($pl_after_tax['value_group_ye']) . '</span>' .
								'<input id="pl_after_tax_g_ye_input" type="hidden" name="SCI_value_group_ye[' . $index_sci . ']" value="' . $pl_after_tax['value_group_ye'] . '">';
							}
								
							echo 
							'</td>';

							echo '<td align="right" style="border-top: 1px solid #000000;" class="pl_after_tax_double_line">';

							if(isset($pl_after_tax['value_group_lye_end']))
							{
								echo '<span id="pl_after_tax_g_lye">' . negative_bracket($pl_after_tax['value_group_lye_end']) . '</span>' .
								'<input id="pl_after_tax_g_lye_input" type="hidden" name="SCI_value_group_lye_end[' . $index_sci . ']" value="' . $pl_after_tax['value_group_lye_end'] . '">';
							}
							
							echo 
							'</td>';
						}
						else
						{
							echo 
							'<td align="right" style="border-top: 1px solid #000000;" colspan="2" class="pl_after_tax_double_line">';

							if(isset($pl_after_tax['value_group_ye']))
							{
								echo '<span id="pl_after_tax_g_ye">' . negative_bracket($pl_after_tax['value_group_ye']) . '</span>' .
								'<input id="pl_after_tax_g_ye_input" type="hidden" name="SCI_value_group_ye[' . $index_sci . ']" value="' . $pl_after_tax['value_group_ye'] . '">';
							}
								
							echo 
							'</td>';
						}
						
						echo '<td></td>';
					}

					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right" style="border-top: 1px solid #000000;" class="pl_after_tax_double_line">';

						if(isset($pl_after_tax['value_company_ye']))
						{
							echo '<span id="pl_after_tax_c_ye">' . negative_bracket($pl_after_tax['value_company_ye']) . '</span>';
						}
						// else
						// {
						// 	echo '<span id="pl_after_tax_c_ye">' . negative_bracket($pl_after_tax_c_ye) . '</span>';
						// }

						echo 	
						'</td>';

						
						echo '<td align="right" style="border-top: 1px solid #000000;" class="pl_after_tax_double_line">';

						if(isset($pl_after_tax['value_company_lye_end']))
						{
							echo '<span id="pl_after_tax_c_lye">' . negative_bracket($pl_after_tax['value_company_lye_end']) . '</span>';
						}
						// else
						// {
						// 	echo '<span id="pl_after_tax_c_lye">' . negative_bracket($pl_after_tax_c_lye) . '</span>';
						// }

						echo '</td>';
					}
					else
					{
						echo 
						'<td align="right" style="border-top: 1px solid #000000;" colspan="2" class="pl_after_tax_double_line">';

						if(isset($pl_after_tax['value_company_ye']))
						{
							echo '<span id="pl_after_tax_c_ye">' . negative_bracket($pl_after_tax['value_company_ye']) . '</span>';
						}

						echo 	
						'</td>';
					}
						
					echo '</tr>';

					$note_no++;
					$index_o++;
					$index_sci ++;
				}
			?>
			<!-- END OF Profit/Loss after tax  -->

			<!-- OTHER COMPREHENSIVE INCOME; NET OF TAX -->
			<?php
				$total_other_g_ye  = 0.00;
				$total_other_g_lye = 0.00;
				$total_other_c_ye  = 0.00;
				$total_other_c_lye = 0.00;

				/* ------ Default dynamic add ------ */
				echo 
				'<tr>' . 
					'<td style="width: 1%;">' . 
						'<a class="sci_new_row" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="add_state_comp_row(this, \'\')"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a>' . 
					'</td>' . 
					'<td>' . 
						'<input type="hidden" name="sci_other_id[]" class="sci_other_id">' . 
						'<input type="text" name="sci_other_description[]" class="form-control sci_other_description" placeholder="Eg. Other comprehensive Income; Net of tax" onchange="show_hide_total_comprehensive_income()">' . 
					'</td>' . 
					'<td></td>' . 
					'<td style="width: 0.5%;">&nbsp;</td>';

					if($is_group) 
					{
						if(!empty($last_fye_end))
						{
							echo 
							'<td>' . '<input type="number" name="sci_other_g_ye[]" class="form-control sci_other_g_ye" style="text-align:right;" min="0" pattern="[0-9]" onchange="process_total_comprehensive_income(\'group\')" onkeypress="return !(event.charCode == 46)" step="1">' . '</td>' . 
							'<td>' . '<input type="number" name="sci_other_g_lye[]" class="form-control sci_other_g_lye" style="text-align:right;" min="0" pattern="[0-9]" onchange="process_total_comprehensive_income(\'group\')" onkeypress="return !(event.charCode == 46)" step="1">' . '</td>';
						}
						else
						{
							echo 
							'<td colspan="2">' . 
								'<input type="number" name="sci_other_g_ye[]" class="form-control sci_other_g_ye" style="text-align:right;" min="0" pattern="[0-9]" onchange="process_total_comprehensive_income(\'group\')" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'</td>';
						}
						
						echo '<td style="width: 0.5%;">&nbsp;</td>';
					}

					if(!empty($last_fye_end))
					{
						echo
						'<td>' . '<input type="number" name="sci_other_c_ye[]" class="form-control sci_other_c_ye" style="text-align:right;" min="0" pattern="[0-9]" onchange="process_total_comprehensive_income(\'company\')" onkeypress="return !(event.charCode == 46)" step="1">' . '</td>' . 
						'<td>' . '<input type="number" name="sci_other_c_lye[]" class="form-control sci_other_c_lye" style="text-align:right;" min="0" pattern="[0-9]" onchange="process_total_comprehensive_income(\'company\')" onkeypress="return !(event.charCode == 46)" step="1">' . '</td>';
					}
					else
					{
						echo
						'<td colspan="2">' . 
							'<input type="number" name="sci_other_c_ye[]" class="form-control sci_other_c_ye" style="text-align:right;" min="0" pattern="[0-9]" onchange="process_total_comprehensive_income(\'company\')" onkeypress="return !(event.charCode == 46)" step="1">' . 
						'</td>';
					}

					
				echo '</tr>';

				foreach ($other_list as $ol_key => $ol_value) 
				{
					$total_other_g_ye  += $ol_value['value_group_ye'];
					$total_other_g_lye += $ol_value['value_group_lye_end'];
					$total_other_c_ye  += $ol_value['value_company_ye'];
					$total_other_c_lye += $ol_value['value_company_lye_end'];
				}

				// print_r(array($total_other_g_ye));
				// print_r(array($total_other_g_lye));
				// print_r(array($total_other_c_ye));
				// print_r(array($total_other_c_lye));


				// /* ------ END OF Default dynamic add ------ */
				// foreach($other_list[0] as $key => $value)
				// {
				// 	// if(count($value['child_array']) == 0)
				// 	// {
				// 		echo 
				// 		'<tr>'.
				// 			'<td style="width: 1%;">' . 
				// 			'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id['. $index_category .']" value="'. $value['parent_array'][0]['id'] .'">' .
				// 			'<td>'. $value['parent_array'][0]['description'] . '</td>';

				// 			if(in_array($value['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
				// 			{
				// 				$key = array_search($value['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

				// 				// show numbering
				// 				echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
				// 						'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
				// 						'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
				// 						'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
				// 						'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . str_replace('.0', '', $fs_notes_details[$key]['note_num_displayed']) . '">' .
				// 						'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
				// 							'<i class="inserted_note_no" style="font-size:16px;">' . str_replace('.0', '', $fs_notes_details[$key]['note_num_displayed']) . '</i>' . 
				// 						'</a>' . 
				// 					'</td>';
				// 			}
				// 			else
				// 			{
				// 				echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
				// 						'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
				// 						'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
				// 						'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
				// 						'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
				// 						'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
				// 							'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
				// 						'</a>' . 
				// 					'</td>';
				// 			}

				// 			echo '<td style="width: 0.5%;">&nbsp;</td>';
							
				// 			if($is_group)
				// 			{
				// 				echo 
				// 				'<td align="right">' . 
				// 					'<input type="number" class="form-control others_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_this_ye_value'] . '" onchange="calculate_total_comprehensive(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
				// 				'</td>' . 
				// 				'<td align="right">' . 
				// 					'<input type="number" class="form-control others_group_lye" style="text-align:right;" name="SCI_C_value_group_lye_end[' . $index_category . ']" value="' . $value['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="calculate_total_comprehensive(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
				// 				'</td>' . 
				// 				'<td></td>';
				// 			}

				// 			echo 
				// 			'<td align="right">' . 
				// 				negative_bracket($value['parent_array'][0]['total_c']) . 
				// 			'</td>' . 
				// 			'<td align="right">' . 
				// 				$value['parent_array'][0]['total_c_lye'] . 
				// 			'</td>' . 
				// 		'</tr>';

				// 			$total_other_g_ye  += $value['parent_array'][0]['group_end_this_ye_value'];
				// 			$total_other_g_lye += $value['parent_array'][0]['group_end_prev_ye_value'];
				// 			$total_other_c_ye  += $value['parent_array'][0]['total_c'];
				// 			$total_other_c_lye += $value['parent_array'][0]['total_c_lye'];

				// 		$index_o ++;
				// 		$index_category ++;
				// 	// }
				// 	// else
				// 	// {
				// 	// 	echo 
				// 	// 	'<tr>' . 
				// 	// 		// hidden fields
				// 	// 		// '<input type="hidden" name="SCI_C_sub_fs_categorized_account_id['. $index_category .']" value="'. $value['parent_array'][0]['id'] .'">' .
				// 	// 		'<td>'. $value['parent_array'][0]['description'] . '</td>' .

				// 	// 		// '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
				// 	// 			// '<input type="hidden" name="fs_note_details_id[' . $index_o . ']" value="0">' .
				// 	// 			// '<input type="hidden" name="SCI_fs_note_templates_master_id[' . $index_o . ']" class="fs_note_templates_master_id" value="0">' .
				// 	// 			// '<input type="hidden" name="SCI_fs_note_num_displayed[' . $index_o . ']" class="fs_note_num_displayed" value="">' .
				// 	// 		'<td style="text-align:center;">' . '</td>' .
				// 	// 		'<td colspan="8"></td>' .
				// 	// 	'</tr>';

				// 	// 	$index_o++;
				// 	// 	// $index_category ++;

				// 	// 	foreach($value['child_array'] as $key => $sub_other_comprehensive)
				// 	// 	{
				// 	// 		if(!is_null($sub_other_comprehensive['parent_array']))	// child that is a parent has child under it (got sub)
				// 	// 		{
				// 	// 			echo 
				// 	// 			'<tr>' . 
				// 	// 				// hidden fields
				// 	// 				'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id['. $index_category .']" value="'. $sub_other_comprehensive['parent_array'][0]['id'] .'">' .
				// 	// 				'<td>'. $sub_other_comprehensive['parent_array'][0]['description'] . '</td>';

				// 	// 				if(in_array($sub_other_comprehensive['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
				// 	// 				{
				// 	// 					$key = array_search($sub_other_comprehensive['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

				// 	// 					// show numbering
				// 	// 					echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . $fs_notes_details[$key]['note_num_displayed'] . '">' .
				// 	// 							'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
				// 	// 									'<i class="inserted_note_no" style="font-size:16px;">' . $fs_notes_details[$key]['note_num_displayed'] . '</i>' . 
				// 	// 							'</a>' . 
				// 	// 						'</td>';
				// 	// 				}
				// 	// 				else
				// 	// 				{
				// 	// 					echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
				// 	// 							'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
				// 	// 								'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
				// 	// 							'</a>' . 
				// 	// 						'</td>';
				// 	// 				}

				// 	// 				echo '<td style="width: 0.5%;">&nbsp;</td>';

				// 	// 				if($is_group)
				// 	// 				{
				// 	// 					echo 
				// 	// 					'<td align="right">' . 
				// 	// 						'<input type="number" class="form-control others_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $sub_other_comprehensive['parent_array'][0]['group_end_this_ye_value'] . '" onchange="calculate_total_comprehensive(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
				// 	// 					'</td>' . 
				// 	// 					'<td align="right">' . 
				// 	// 						'<input type="number" class="form-control others_group_lye" style="text-align:right;" name="SCI_C_value_group_lye_end[' . $index_category . ']" value="' . $sub_other_comprehensive['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="calculate_total_comprehensive(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
				// 	// 					'</td>' . 
				// 	// 					'<td></td>';
				// 	// 				}

				// 	// 				echo 
				// 	// 				'<td align="right">' . 
				// 	// 					negative_bracket($sub_other_comprehensive['parent_array']['total_c']) . 
				// 	// 				'</td>' . 
				// 	// 				'<td align="right">' . 
				// 	// 					negative_bracket($sub_other_comprehensive['parent_array'][0]['total_c_lye']) .
				// 	// 				'</td>' .
				// 	// 			'</tr>';

				// 	// 			$index++;
				// 	// 		}
				// 	// 		else // child is not a parent of other category (no sub)
				// 	// 		{
				// 	// 			echo 
				// 	// 			'<tr>' . 
				// 	// 				// hidden fields
				// 	// 				'<input type="hidden" name="SCI_C_sub_fs_categorized_account_id['. $index_category .']" value="'. $sub_other_comprehensive['child_array']['id'] .'">' .
				// 	// 				'<td>'. $sub_other_comprehensive['child_array']['description'] . '</td>';

				// 	// 				if(in_array($sub_other_comprehensive['child_array']['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
				// 	// 				{
				// 	// 					$key = array_search($sub_other_comprehensive['child_array']['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

				// 	// 					// show numbering
				// 	// 					echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="' . $fs_notes_details[$key]['note_num_displayed'] . '">' .
				// 	// 							'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
				// 	// 									'<i class="inserted_note_no" style="font-size:16px;">' . $fs_notes_details[$key]['note_num_displayed'] . '</i>' . 
				// 	// 							'</a>' . 
				// 	// 						'</td>';
				// 	// 				}
				// 	// 				else
				// 	// 				{
				// 	// 					echo '<td class="add_note_td note_' . $note_no . '" style="text-align:center;">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_details_id[' . $index_category . ']" value="0">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_templates_master_id[' . $index_category . ']" class="fs_note_templates_master_id" value="0">' . 
				// 	// 							'<input type="hidden" name="SCI_C_fs_note_num_displayed[' . $index_category . ']" class="fs_note_num_displayed" value="">' . 
				// 	// 							'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, ' . $note_no . ')">' . 
				// 	// 								'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
				// 	// 							'</a>' . 
				// 	// 						'</td>';
				// 	// 				}

				// 	// 				echo '<td style="width: 0.5%;">&nbsp;</td>';

				// 	// 				if($is_group)
				// 	// 				{
				// 	// 					echo 
				// 	// 					'<td align="right">' . 
				// 	// 						'<input type="number" class="form-control others_group_ye" style="text-align:right;" name="SCI_C_value_group_ye[' . $index_category . ']" value="' . $sub_other_comprehensive['child_array']['group_end_this_ye_value'] . '" onchange="calculate_total_comprehensive(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
				// 	// 					'</td>' . 
				// 	// 					'<td align="right">' . 
				// 	// 						'<input type="number" class="form-control others_group_lye" style="text-align:right;" name="SCI_C_value_group_lye_end[' . $index_category . ']" value="' . $sub_other_comprehensive['child_array']['group_end_prev_ye_value'] . '" onchange="calculate_total_comprehensive(\'group\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
				// 	// 					'</td>' . 
				// 	// 					'<td></td>';
				// 	// 				}

				// 	// 				echo 
				// 	// 				'<td align="right">' . 
				// 	// 					negative_bracket($sub_other_comprehensive['child_array']['value']) . 
				// 	// 				'</td>' . 
				// 	// 				'<td align="right">' . 
				// 	// 					negative_bracket($sub_other_comprehensive['child_array']['company_end_prev_ye_value']) . 
				// 	// 				'</td>' .
				// 	// 			'</tr>';

				// 	// 			$index++;
				// 	// 		}

				// 	// 		$total_other_g_ye  += $sub_other_comprehensive['child_array']['group_end_this_ye_value'];
				// 	// 		$total_other_g_lye += $sub_other_comprehensive['child_array']['group_end_prev_ye_value'];
				// 	// 		$total_other_c_ye  += $sub_other_comprehensive['child_array']['value'];
				// 	// 		$total_other_c_lye += $sub_other_comprehensive['child_array']['company_end_prev_ye_value'];
							
				// 	// 		$index_o++;
				// 	// 		$index_category ++;
				// 	// 	}
				// 	// }
				// }
			// }
			?>
			<!-- END OF OTHER COMPREHENSIVE INCOME; NET OF TAX -->

			<!-- DISPLAY TOTAL COMPREHENSIVE INCOME FOR THE YEAR -->
			<?php
				$show_total_comprehensive = false;

				if(count($other_list) > 0)
				{
					$show_total_comprehensive = true;
				}
			?>
			<tr class="sci_total_comprehensive_income_tr" <?php if(!$show_total_comprehensive){ echo 'style="display:none;"'; } ?>>
				<td style="width: 1%;">
				<td>Total comprehensive income for the year</td>
				<td></td>
				<td></td>

				<?php
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo 
							'<td align="right" style="border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">
								<span id="total_comprehensive_group_ye">' . negative_bracket($pl_after_tax['value_group_ye'] + $total_other_g_ye) . '</span>
							</td>
							<td align="right" style="border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">
								<span id="total_comprehensive_group_lye">' . negative_bracket($pl_after_tax['value_group_lye_end'] + $total_other_g_lye) . '</span>
							</td>';
						}
						else
						{
							echo 
							'<td align="right" style="border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;" colspan="2">
								<span id="total_comprehensive_group_ye">' . negative_bracket($pl_after_tax['value_group_ye'] + $total_other_g_ye) . '</span>
							</td>';
						}
						echo '<td></td>';
					} 

					if(!empty($last_fye_end))
					{
						echo 
						'<td align="right" style="border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">' .
							'<span id="total_comprehensive_company_ye">' .
								negative_bracket($pl_after_tax["value_company_ye"] + $total_other_c_ye) .
							'</span>' .
						'</td>' .
						'<td align="right" style="border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">' .
							'<span id="total_comprehensive_company_lye">' .
								negative_bracket($pl_after_tax["value_company_lye_end"] + $total_other_c_lye) .
							'</span>' .
						'</td>';
					}
					else
					{
						echo 
						'<td align="right" style="border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;" colspan="2">' .
							'<span id="total_comprehensive_company_ye">' .
								negative_bracket($pl_after_tax["value_company_ye"] + $total_other_c_ye);
							'</span>' .
						'</td>';
					}
				?>
			</tr>
			<!-- END OF DISPLAY TOTAL COMPREHENSIVE INCOME FOR THE YEAR -->
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

<div class="loading" id="loadingStateCompIncome" style="display: none;">Loading&#8230;</div>

<!-- <script src="themes/default/assets/js/financial_statement/partial_state_comp_income.js" charset="utf-8"></script>
<script src="themes/default/assets/js/financial_statement/fs_notes.js" charset="utf-8"></script> -->

<?php
  function negative_bracket($number)
  {
      if($number == 0 || $number == '')
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
	var is_group 					 = <?php echo json_encode($is_group); ?>;
	var fs_ntfs_layout_template_list = <?php echo json_encode($fs_ntfs_layout_template_list); ?>;
	
	var other_list 					 = <?php echo json_encode($other_list); ?>;
	var fs_notes_details_state_2 	 = <?php echo json_encode($fs_notes_details_state_2); ?>;
	var deleted_dynamic_ids 		 = [];

	var sci_additional_list = <?php echo json_encode($additional_list); ?>;	// taxation
	var sci_soa_pl_list = <?php echo json_encode($soa_pl_list); ?>;	// share of associates

	if(sci_additional_list[0][0]['child_array'].length == 0 && sci_soa_pl_list.length == 0)
	{
		// $('.pl_be4_tax_double_line').css("border-top", "1px solid black");
		if(other_list.length == 0)
		{
			$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-style", "double");
			$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-width", "5px");
		}
	}
	else
	{
		if(other_list.length == 0)
		{
			$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-style", "double");
			$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-width", "5px");
		}
	}

	// console.log(fs_ntfs_layout_template_list);
	// console.log(other_list);

	/* Loop */
	other_list.forEach(function(data, index)
	{
		add_state_comp_row($('.sci_new_row'), data);
	});
	/* END OF Loop */

	$('.edit_note').hide();

	/* ------------------ DO NOT DELETE THIS ------------------ */
	// var auto_rearrange_value = <?php echo $auto_rearrange_value; ?>;

	// if(!auto_rearrange_value)
	// {
	// 	$('.edit_note').hide();
	// 	$('.edit_note_no').show();
	// }

	// $("[name='on_auto_rearrange']").bootstrapSwitch({
	//     state: <?php echo $auto_rearrange_value; ?>,
	//     size: 'small',
	//     onColor: 'primary',
	//     onText: 'ON',
	//     offText: 'OFF',
	//     // Text of the center handle of the switch
	//     labelText: '&nbsp',
	//     // Width of the left and right sides in pixels
	//     handleWidth: '45px',
	//     // Width of the center handle in pixels
	//     labelWidth: 'auto',
	//     baseClass: 'bootstrap-switch',
	//     wrapperClass: 'wrapper'
	// });

	// // Triggered on switch state change.
	// $("[name='on_auto_rearrange']").on('switchChange.bootstrapSwitch', function(event, state) 
	// {
	// 	var msg = '';
	// 	var value = 0;

	// 	if(state)
	// 	{
	// 		msg = "Turn ON auto rearrange note number?";
	// 	}
	// 	else
	// 	{
	// 		msg = "Turn OFF auto rearrange note number?";
	// 	}

	//     bootbox.confirm(msg, function(result)
	//     {
	//     	if(result)
	//     	{
	//     		if(state)
	//     		{
	//     			value = 1;
	//     		}
	//     		else
	//     		{
	//     			value = 0;
	//     		}

	//     		$("[name='on_auto_rearrange']").bootstrapSwitch('state', state, true);
	//     		$("[name='auto_rearrange_value']").val(value);
	//     	}
	//     	else
	//     	{
	//     		if(!state)
	//     		{
	//     			value = 1;
	//     		}
	//     		else
	//     		{
	//     			value = 0;
	//     		}

	//     		$("[name='on_auto_rearrange']").bootstrapSwitch('state', !state, true);
	//     		$("[name='auto_rearrange_value']").val(value);
	//     	}

	//     	if(state)
	//     	{
	//     		$('.edit_note_no').hide();
	//     	}
	//     	else
	//     	{
	//     		$('.edit_note_no').show();
	//     	}
	//     })
	// })
	/* ------------------ END OF DO NOT DELETE THIS ------------------ */
</script>