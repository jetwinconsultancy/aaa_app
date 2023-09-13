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

<!-- <?php echo json_encode($financial_position_data); ?> -->
<form id="form_financial_position">
	<?php
		if($show_data_content)
		{
	?>
	<input type="hidden" class="statement_doc_type" name="statement_doc_type" value="2">

	<h5 id="account_balance_msg" style="color:red"></h5>

	<table id="tbl_financial_position" class="table table-hover table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
		<thead>
			<tr>
				<th style="width: <?=$width['account_desc'] ?>%;"></th>
				<th style="width: <?=$width['note'] ?>%;"></th>
				<th style="width: 0.5%;">&nbsp;</th>
				<th colspan="3" style="width: <?=$width['value'] ?>%; text-align: center; border-bottom: 1px solid #000000;"><?php echo $group_company?></th>
			</tr>
			<tr>
				<th style="width: <?=$width['account_desc'] ?>%;">&nbsp;</th>
				<th style="width: <?=$width['note'] ?>%;">&nbsp;</th>
				<th style="width: 0.5%;">&nbsp;</th>
				<th style="width: <?=$width['value'] ?>%; text-align: center;">
					<?php
						if($show_third_col)
						{
							echo '</br>';
						} 
						echo $current_fye_end; 
					?>	
				</th>

				<?php 
					if(!empty($last_fye_end))
					{
						echo 
						'<th style="width: ' . $width['value'] . '%; text-align: center;">';
							if($show_third_col)
							{
								echo 'Restated</br>';
							}
							echo $last_fye_end; 
						echo '</th>';

						if($show_third_col)
						{
							echo '<th style="width: ' . $width['value'] . '%; text-align: center;">Restated</br>' . $last_fye_beg . '</th>';
						}
					}
				?>
			</tr>
			<tr>
				<th style="width: <?=$width['account_desc'] ?>%;"></th>
				<th style="width: <?=$width['note'] ?>%; text-align: center;">
					<!-- <div class="input-group">
		                <input type="checkbox" name="on_auto_rearrange" checked/>
		                <input type="hidden" name="auto_rearrange_value" value="<?php echo $auto_rearrange_value; ?>"/>
		            </div> -->
					Note
				</th>
				<th style="width: 0.5%;">&nbsp;</th>
				<th style="width: <?=$width['value'] ?>%; text-align: center; border-bottom: 1px solid #000;">$</th>

				<?php 
					if(!empty($last_fye_end))
					{
						echo '<th style="width: 15%; text-align: center; border-bottom: 1px solid #000;">$</th>';

						if($show_third_col)
						{
							echo '<th style="width: 15%; text-align: center; border-bottom: 1px solid #000;">$</th>';
						}
					}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
				$index = 0;
				$note_no = 0;

				if($is_group)
				{
					$total_assets_g 		 = 0.00;
					$total_assets_g_end		 = 0.00;
					$total_assets_g_beg		 = 0.00;

					$total_equity_g 		 = 0.00;
					$total_equity_g_end		 = 0.00;
					$total_equity_g_beg		 = 0.00;

					$total_liabilities_g 	 = 0.00;
					$total_liabilities_g_end = 0.00;
					$total_liabilities_g_beg = 0.00;

					$display_class_ye 	   = "FP_group_ye"; 
					$display_class_lye_end = "FP_group_lye_end"; 
					$display_class_lye_beg = "FP_group_lye_beg"; 

					// $display_class_subtotal_ye 		= "FP_subtotal_ye"; 
					// $display_class_subtotal_lye_end = "FP_subtotal_lye_end"; 
					// $display_class_subtotal_lye_beg = "FP_subtotal_lye_beg"; 
				}
				else
				{
					$total_assets_c 		 = 0.00;
					$total_assets_c_end 	 = 0.00;
					$total_assets_c_beg 	 = 0.00;

					$total_equity_c 		 = 0.00;
					$total_equity_c_end 	 = 0.00;
					$total_equity_c_beg 	 = 0.00;

					$total_liabilities_c 	 = 0.00;
					$total_liabilities_c_end = 0.00;
					$total_liabilities_c_beg = 0.00;

					$display_class_lye_end = "FP_company_lye_end";
					$display_class_lye_beg = "FP_company_lye_beg";

					// $display_class_subtotal_ye 	   = "FP_subtotal_ye"; 
					// $display_class_subtotal_lye_end = "FP_subtotal_lye_end"; 
					// $display_class_subtotal_lye_beg = "FP_subtotal_lye_beg"; 
				}

				if($is_group)
				{
					// display name of input
					$FP_ye_end_value_name  = "FP_group_ye_end_value";
					$FP_lye_end_value_name = "FP_group_lye_end_value";
					$FP_lye_beg_value_name = "FP_group_lye_beg_value";
				}
				else
				{
					// display name of input
					$FP_ye_end_value_name  = "FP_company_ye_end_value";
					$FP_lye_end_value_name = "FP_company_lye_end_value";
					$FP_lye_beg_value_name = "FP_company_lye_beg_value";
				}

				$displayed_eq_liabi_title = false;

				foreach ($data as $level_1_key => $level_1) 
				{
					$hide_main_title = false;
					$level_1_description = "";

					$fs_ntfs_list_key = array_search($level_1['parent_array'][0]['account_code'], array_column($fs_ntfs_list, "account_code"));	// get key

					if(!empty($fs_ntfs_list_key) || (string)$fs_ntfs_list_key == 0)
		            {
		            	$level_1_description = $fs_ntfs_list[$fs_ntfs_list_key]['description'];	// get description from fs_ntfs_list json from document name "Statement of financial position"
		            }

		            /* ---------------------------- Display main category (Level 1). eg. Assets, Equity and Liabilities ---------------------------- */
		            if(count($level_1['child_array']) == 1)
		            {
		            	foreach ($level_1['child_array'] as $key => $value) 
		            	{
		            		if(empty($value['parent_array']))
		            		{
		            			$hide_main_title = true;
		            		}
		            	}
		            }

		            /* Set description title for "Assets" */
					if($level_1_description == "Assets" && (count($level_1['child_array']) > 0) && !$hide_main_title)
					{
						echo 
						'<tr>'.
							'<td><strong>' . ucfirst(strtolower($level_1['parent_array'][0]['description'])) . '</strong></td>' .
							'<td align="center"></td>' .
							'<td style="width: 0.5%;">&nbsp;</td>' .
							'<td colspan="3"></td>' .
						'</tr>';
					}
					/* END OF Set description title for "Assets" */

					/* Set description title for "Equity" / "Liabilities" */
					if(($level_1_description == "Equity" || $level_1_description == "Liabilities"))
					{
						$empty_inner_item = false;

						if($level_1_description == "Equity")
						{
							$level_1['child_array'] = array($level_1); // move equity's level 1 to level 2 template
						}
						elseif($level_1_description == "Liabilities")
						{
							if($level_1['child_array'][0]['parent_array'] == null)
							{
								// $level_1['child_array'] = array(array('child_array' => $level_1['child_array']));
								$level_1['child_array'] = [];
								$empty_inner_item = true;
							}
						}

						if(!$displayed_eq_liabi_title && !$empty_inner_item && !empty($e_l_title))
						{
							echo 
							'<tr>'.
								'<td><strong>' . $e_l_title . '</strong></td>' .
								'<td align="center"></td>' .
								'<td style="width: 0.5%;">&nbsp;</td>' .
								'<td colspan="3"></td>' .
							'</tr>';

							$displayed_eq_liabi_title = true;
						}
					}
					/* END OF Set description title for "Equity" / "Liabilities" */

					/* ---------------------------- END OF Display main category (Level 1). eg. Assets, Equity and Liabilities ---------------------------- */

					if(!empty($level_1['parent_array']))
					{
						foreach ($level_1['child_array'] as $level_2_key => $level_2) 
						{
							// print_r($level_2);
							if($is_group)
							{
								$temp_total_g 	  = 0.00;
								$temp_total_g_end = 0.00;
								$temp_total_g_beg = 0.00;
							}
							else
							{
								$temp_total_c 	  = 0.00;
								$temp_total_c_end = 0.00;
								$temp_total_c_beg = 0.00;
							}

							/* DISPLAY 1 LINE ONLY IF NO CHILD UNDER LEVEL 2 */
							if(count($level_2['child_array']) > 0)
							{
								if(!empty($level_2['parent_array']))
								{
									// Display title of "Current Asset" / "Non-current Asset" || "Current Liabilities" / "Non-current Liabilities"
									echo 
									'<tr>'.
										'<td><strong><em>' . ucfirst(strtolower($level_2['parent_array'][0]['description'])) . '</em></strong></td>' .
										'<td align="center"></td>' .
										'<td style="width: 0.5%;">&nbsp;</td>' .
										'<td colspan="3"></td>' .
									'</tr>';

									foreach ($level_2['child_array'] as $level_3_key => $level_3)
									{
										// echo '</br></br>';

										/* DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */
										if(!empty($level_3['parent_array']))
										{
											// echo json_encode($level_3['fs_note_details']);
											echo 
											'<tr>' .
												'<td>' .
													// hidden fields
													'<input type="hidden" name="FP_sub_fs_categorized_account_id[]" class="SFP_sub_fs_categorized_account_id" value="'. $level_3['parent_array'][0]['id'] .'">' .
													$level_3['parent_array'][0]['description'] . 
												'</td>';

												// print_r(array(array_column($fs_notes_details, 'fs_categorized_account_round_off_id'), $level_3['parent_array'][0]['id']));

												if(in_array($level_3['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
												{
													$key = array_search($level_3['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

													// print_r(array($key, $level_3['parent_array'][0]['description'], $fs_notes_details[$key]));

													// echo '<td class="add_note" style="text-align:center;"><a style="font-weight:bold; cursor: pointer;" onclick="show_note_layout('. $level_3['fs_note_details']['fs_note_templates_master_id'] .', ' . $level_3['parent_array'][0]['id'] . ')">' . $fs_notes_details[$key]['note_num_displayed'] . '</a></td>';

													// show numbering
													echo 
														'<td class="fp_add_note_td fp_note_' . $note_no . '" style="text-align:center;">' . 
															'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' . 
															'<input type="hidden" name="fs_note_details_id[' . $index . ']" value="' . $fs_notes_details[$key]['id'] . '">' . 
															'<input type="hidden" name="FP_fs_note_templates_master_id[' . $index . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' . 
															'<input type="hidden" name="FP_fs_note_num_displayed[' . $index . ']" class="fs_note_num_displayed" value="' . $fs_notes_details[$key]['note_num_displayed'] . '">' . 
															'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
																'<i class="inserted_note_no" style="font-size:16px;">' . $fs_notes_details[$key]['note_num_displayed'] . '</i>' . 
															'</a>' . 
														'</td>';

														$note_no++;
												}
												else
												{
													echo 
														'<td class="fp_add_note_td fp_note_' . $note_no . '" style="text-align:center;">' . 
															'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
															'<input type="hidden" name="fs_note_details_id[' . $index . ']" value="0">' .
															'<input type="hidden" name="FP_fs_note_templates_master_id[' . $index . ']" class="fs_note_templates_master_id" value="0">' .
															'<input type="hidden" name="FP_fs_note_num_displayed[' . $index . ']" class="fs_note_num_displayed" value="">' .
															'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no .')"">' .
																'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
															'</a>' . 
														'</td>';

													// '<td class="add_note" style="text-align:center;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $index .')"">' . 
													// 	'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
													// '</a></td>';

													$note_no++;
												}

												$edit_note_no_class = '';

												if(in_array($level_3['parent_array'][0]['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
												{
													$edit_note_no_class = "edit_note_no";
												}

												echo '<td style="width: 0.5%;">' .
														'<a class="edit_note ' . $edit_note_no_class . '" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Edit Note Number" style="font-weight:bold; cursor: pointer; color:green;" onclick="add_edit_note_no(' . $note_no .', ' . $level_3['parent_array'][0]['id'] . ')">' . 
															'<i class="fa fa-edit" style="font-size:16px;"></i>' .
														'</a>' .
													'</td>';

												if($is_group)
												{
													echo 
													'<td align="right">' . 
														'<input type="number" class="form-control ' . $display_class_ye . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_ye .  '_account_code_' . $level_1_description . '" name="' . $FP_ye_end_value_name . '[' . $index . ']" style="text-align:right" value="' . $level_3['parent_array'][0]['group_end_this_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_ye . '\', \'' . $level_1_description . '\', \'' . "group" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
													'</td>';
													
													if(!empty($last_fye_end))
													{
														echo 
														'<td align="right">' . 
															'<input type="number" name="' . $FP_lye_end_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_end . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_end .  '_account_code_' . $level_1_description . '" style="text-align:right;" value="' . $level_3['parent_array'][0]['group_end_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_end. '\', \'' . $level_1_description . '\', \'' . "group" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
														'</td>';

													 	if($show_third_col)
														{
															echo 
															'<td align="right">' . 
																'<input type="number" name="' . $FP_lye_beg_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_beg .  '_account_code_' . $level_1_description . '" style="text-align:right;" value="' . $level_3['parent_array'][0]['group_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_beg . '\', \'' . $level_1_description . '\', \'' . "group" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
															'</td>';
														}
													}

													$temp_total_g 	  += $level_3['parent_array'][0]['group_end_this_ye_value'];
													$temp_total_g_end += $level_3['parent_array'][0]['group_end_prev_ye_value'];
													$temp_total_g_beg += $level_3['parent_array'][0]['group_beg_prev_ye_value'];
												}
												else
												{
													// if($level_3['parent_array'][0]['account_code'] == 'Q103')
													// {
													// 	// print_r($level_3['parent_array'][0]);
													// }

													echo '<td align="right">' . negative_bracket($level_3['parent_array'][0]['total_c']) . '</td>';
													
													if(!empty($last_fye_end))
													{
														// echo 
														// '<td align="right">' . 
														// 	'<input type="number" name="' . $FP_lye_end_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_end . '_values_under_' . $level_2['parent_array']['id'] . ' ' . $display_class_lye_end .  '_account_code_' . $level_2['parent_array']['account_code'] . '" style="text-align:right;" value="' . $level_3['parent_array'][0]['company_end_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array']['id'] .', \'' . $display_class_lye_end . '\', \'' . $level_2['parent_array']['account_code'] . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
														// '</td>';

														echo 
														'<td align="right">' . negative_bracket($level_3['parent_array'][0]['total_c_lye']) . '</td>';
													
													 	if($show_third_col)
														{
															echo 
															'<td align="right">' . 
																'<input type="number" name="' . $FP_lye_beg_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_beg .  '_account_code_' . $level_1_description . '" style="text-align:right;" value="' . $level_3['parent_array'][0]['company_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_beg . '\', \'' . $level_1_description . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
															'</td>';
														}
													}

													$temp_total_c 	  += $level_3['parent_array'][0]['total_c'];
													$temp_total_c_end += $level_3['parent_array'][0]['total_c_lye'];
													$temp_total_c_beg += $level_3['parent_array'][0]['company_beg_prev_ye_value'];
												}
											echo '</tr>';

											$index++;
										}
										/* END OF DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */

										/* DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
										elseif($level_1_description == "Liabilities" || $level_1_description == "Assets")
										{
											echo 
											'<tr>'.
												'<td>' . 
													// hidden fields
													'<input type="hidden" name="FP_sub_fs_categorized_account_id[]" class="SFP_sub_fs_categorized_account_id" value="'. $level_3['child_array']['id'] .'">' .

													$level_3['child_array']['description'] . 
												'</td>';

												if(in_array($level_3['child_array']['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
												{
													$key = array_search($level_3['child_array']['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id'));

													// echo '<td class="add_note" style="text-align:center;"><a style="font-weight:bold; cursor: pointer;" onclick="show_note_layout('. $level_3['fs_note_details']['fs_note_templates_master_id'] .', ' . $level_3['parent_array'][0]['id'] . ')">' . $fs_notes_details[$key]['note_num_displayed'] . '</a></td>';

													// show numbering
													echo 
														'<td class="fp_add_note_td fp_note_' . $note_no . '" style="text-align:center;">' . 
															'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="'. $fs_notes_details[$key]['fs_ntfs_layout_template_default_id'] . '" />' .
															'<input type="hidden" name="fs_note_details_id[' . $index . ']" value="' . $fs_notes_details[$key]['id'] . '">' .
															'<input type="hidden" name="FP_fs_note_templates_master_id[' . $index . ']" class="fs_note_templates_master_id" value="' . $fs_notes_details[$key]['fs_note_templates_master_id'] . '">' .
															'<input type="hidden" name="FP_fs_note_num_displayed[' . $index . ']" class="fs_note_num_displayed" value="' . $fs_notes_details[$key]['note_num_displayed'] . '">' .
															'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no . ')">' . 
																'<i class="inserted_note_no" style="font-size:16px;">' . $fs_notes_details[$key]['note_num_displayed'] . '</i>' . 
															'</a>' . 
														'</td>';

														$note_no++;
												}
												else
												{
													echo 
														'<td class="fp_add_note_td fp_note_' . $note_no . '" style="text-align:center;">' . 
															'<input type="hidden" class="fs_ntfs_layout_template_default_id" value="0" />' .
															'<input type="hidden" name="fs_note_details_id[' . $index . ']" value="0">' .
															'<input type="hidden" name="FP_fs_note_templates_master_id[' . $index . ']" class="fs_note_templates_master_id" value="0">' .
															'<input type="hidden" name="FP_fs_note_num_displayed[' . $index . ']" class="fs_note_num_displayed" value="">' .
															'<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $note_no .')"">' .
																'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
															'</a>' . 
														'</td>';

													// '<td class="add_note" style="text-align:center;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $index .')"">' . 
													// 	'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
													// '</a></td>';

													$note_no++;
												}

												$edit_note_no_class = '';

												if(in_array($level_3['child_array']['id'], array_column($fs_notes_details, 'fs_categorized_account_round_off_id')))
												{
													$edit_note_no_class = "edit_note_no";
												}

												echo '<td style="width: 0.5%;">' .
														'<a class="edit_note ' . $edit_note_no_class . '" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Edit Note Number" style="font-weight:bold; cursor: pointer; color:green;" onclick="add_edit_note_no(' . $note_no .', ' . $level_3['child_array']['id'] . ')">' . 
															'<i class="fa fa-edit" style="font-size:16px;"></i>' .
														'</a>' .
													'</td>';

												// if(!empty($level_3['child_array']['fs_note_details']['fs_note_details_id']))
												// {
												// 	echo '<td class="add_note" style="text-align:center;"><a style="font-weight:bold; cursor: pointer;" onclick="show_note_layout('. $level_3['child_array']['fs_note_details']['fs_note_templates_master_id'] .', ' . $level_3['child_array']['id'] . ')">Note number</a></td>';
												// }
												// else
												// {
												// 	echo '<td class="add_note" style="text-align:center;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '. $level_3['child_array']['id'] .')"">' . 
												// 		'<i class="fa fa-plus-circle" style="font-size:16px;"></i>' . 
												// 	'</a></td>';
												// }

												if($is_group)
												{
													echo 
													'<td align="right">' . 
														'<input type="number" class="form-control ' . $display_class_ye . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_ye . '_account_code_' . $level_1_description . '" name="' . $FP_ye_end_value_name . '[' . $index . ']" style="text-align:right;" value="' . $level_3['child_array']['group_end_this_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_ye . '\', \'' . $level_1_description . '\', \'' . "group" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
														// negative_bracket($level_3[0]['group_end_this_ye_value']) . 
													'</td>';

													if(!empty($last_fye_end))
													{
														echo 
														'<td align="right">' . 
															'<input type="number" class="form-control ' . $display_class_lye_end . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_end . '_account_code_' . $level_1_description . '" name="' . $FP_lye_end_value_name . '[' . $index . ']" style="text-align:right;" value="' . $level_3['child_array']['group_end_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_end . '\', \'' . $level_1_description . '\', \'' . "group" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
														'</td>';

													 	if($show_third_col)
														{
															echo 
															'<td align="right">' . 
																'<input type="number" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_beg . '_account_code_' . $level_1_description . '" name="' . $FP_lye_beg_value_name . '[' . $index . ']" style="text-align:right;" value="' . $level_3['child_array']['group_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_beg . '\', \'' . $level_1_description . '\', \'' . "group" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
															'</td>';
														}
													}
													
													$temp_total_g 	  += $level_3['child_array']['group_end_this_ye_value'];
													$temp_total_g_end += $level_3['child_array']['group_end_prev_ye_value'];
													$temp_total_g_beg += $level_3['child_array']['group_beg_prev_ye_value'];
												}
												else
												{
													echo 
													'<td align="right">' .  negative_bracket($level_3['child_array']['value']) . '</td>';

													if(!empty($last_fye_end))
													{
													 	// echo 
													 	// '<td align="right">' . 
													 	// 	'<input type="number" class="form-control ' . $display_class_lye_end . '_values_under_' . $level_2['parent_array']['id'] . ' ' . $display_class_lye_end . '_account_code_' . $level_2['parent_array']['account_code'] . '" name="' . $FP_lye_end_value_name . '[' . $index . ']" style="text-align:right;" value="' . $level_3['child_array']['company_end_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array']['id'] .', \'' . $display_class_lye_end. '\', \'' . $level_2['parent_array']['account_code'] . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
													 	// '</td>';

													 	echo
													 		'<td align="right">' . negative_bracket($level_3['child_array']['company_end_prev_ye_value']) . '</td>';

													 	if($show_third_col)
														{
															echo 
															'<td align="right">' . 
																'<input type="number" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_beg . '_account_code_' . $level_1_description . '" name="' . $FP_lye_beg_value_name . '[' . $index . ']" style="text-align:right;" value="' . $level_3['child_array']['company_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_beg. '\', \'' . $level_1_description . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
															'</td>';
														}
													}

													$temp_total_c 	  += $level_3['child_array']['value'];
													$temp_total_c_end += $level_3['child_array']['company_end_prev_ye_value'];
													$temp_total_c_beg += $level_3['child_array']['company_beg_prev_ye_value'];
												}
											echo '</tr>';

											$index++;
										}
										/* END OF DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
									}
									
									/* DISPLAY TOTAL FOR EACH CATEGORY	*/
									if($is_group)	// FOR GROUP
									{
										$total 		= $temp_total_g;
										$total_end 	= $temp_total_g_end;
										$total_beg 	= $temp_total_g_beg;

										/* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - GROUP */
										if($level_1_description == "Assets")	// NON-CURRENT ASSETS || CURRENT ASSETS
										{
											$total_assets_g 	+= $total;
											$total_assets_g_end += $total_end;
											$total_assets_g_beg += $total_beg;
										}
										elseif($level_1_description == "Equity") // EQUITY
										{
											$total_equity_g 	+= $total;
											$total_equity_g_end += $total_end;
											$total_equity_g_beg += $total_beg;
										}
										elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
										{
											$total_liabilities_g 	 += $total;
											$total_liabilities_g_end += $total_end;
											$total_liabilities_g_beg += $total_beg;
										}
										/* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */
									}
									else 	// FOR COMPANY
									{
										$total 		= $temp_total_c;
										$total_end 	= $temp_total_c_end;
										$total_beg 	= $temp_total_c_beg;

										/* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - COMPANY */
										if($level_1_description == "Assets")	// NON-CURRENT ASSETS || CURRENT ASSETS
										{
											$total_assets_c 	+= $total;
											$total_assets_c_end += $total_end;
											$total_assets_c_beg += $total_beg;
										}
										elseif($level_1_description == "Equity") // EQUITY
										{
											$total_equity_c 	+= $total;
											$total_equity_c_end += $total_end;
											$total_equity_c_beg += $total_beg;
										}
										elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
										{
											$total_liabilities_c 	 += $total;
											$total_liabilities_c_end += $total_end;
											$total_liabilities_c_beg += $total_beg;
										}
										/* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */
									}

									echo 
										'<tr>' . 
											'<td colspan="2"></td>' . 
											'<td style="width: 0.5%;">&nbsp;</td>' .
											'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
												'<span id="' . $display_class_ye . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
													negative_bracket($total) . 
												'</span>' . 
											'</td>';

											if(!empty($last_fye_end))
											{
												echo 
												'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
													'<span id="' . $display_class_lye_end . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
														negative_bracket($total_end) . 
													'</span>' . 
												'</td>';

											 	if($show_third_col)
												{
													echo 
													'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
														'<span id="' . $display_class_lye_beg . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
															negative_bracket($total_beg) . 
														'</span>' . 
													'</td>';
												}
											}
										'</tr>';
										/* END OF DISPLAY TOTAL FOR EACH CATEGORY */
								}
							}

							/* DISPLAY TOTAL FOR EACH CATEGORY	*/
							// if(count($level_2['child_array']) > 0)
							// {
								// if($is_group)	// FOR GROUP
								// {
								// 	$total 		= $temp_total_g;
								// 	$total_end 	= $temp_total_g_end;
								// 	$total_beg 	= $temp_total_g_beg;

								// 	/* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - GROUP */
								// 	if($level_1_description == "Assets")	// NON-CURRENT ASSETS || CURRENT ASSETS
								// 	{
								// 		$total_assets_g 	+= $total;
								// 		$total_assets_g_end += $total_end;
								// 		$total_assets_g_beg += $total_beg;
								// 	}
								// 	elseif($level_1_description == "Equity") // EQUITY
								// 	{
								// 		$total_equity_g 	+= $total;
								// 		$total_equity_g_end += $total_end;
								// 		$total_equity_g_beg += $total_beg;
								// 	}
								// 	elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
								// 	{
								// 		$total_liabilities_g 	 += $total;
								// 		$total_liabilities_g_end += $total_end;
								// 		$total_liabilities_g_beg += $total_beg;
								// 	}
								// 	/* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */
								// }
								// else 	// FOR COMPANY
								// {
								// 	$total 		= $temp_total_c;
								// 	$total_end 	= $temp_total_c_end;
								// 	$total_beg 	= $temp_total_c_beg;

								// 	/* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - COMPANY */
								// 	if($level_1_description == "Assets")	// NON-CURRENT ASSETS || CURRENT ASSETS
								// 	{
								// 		$total_assets_c 	+= $total;
								// 		$total_assets_c_end += $total_end;
								// 		$total_assets_c_beg += $total_beg;
								// 	}
								// 	elseif($level_1_description == "Equity") // EQUITY
								// 	{
								// 		$total_equity_c 	+= $total;
								// 		$total_equity_c_end += $total_end;
								// 		$total_equity_c_beg += $total_beg;
								// 	}
								// 	elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
								// 	{
								// 		$total_liabilities_c 	 += $total;
								// 		$total_liabilities_c_end += $total_end;
								// 		$total_liabilities_c_beg += $total_beg;
								// 	}
								// 	/* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */
								// }

								// echo 
								// 	'<tr>' . 
								// 		'<td colspan="2"></td>' . 
								// 		'<td style="width: 0.5%;">&nbsp;</td>' .
								// 		'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
								// 			'<span id="' . $display_class_ye . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
								// 				negative_bracket($total) . 
								// 			'</span>' . 
								// 		'</td>';

								// 		if(!empty($last_fye_end))
								// 		{
								// 			echo 
								// 			'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
								// 				'<span id="' . $display_class_lye_end . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
								// 					negative_bracket($total_end) . 
								// 				'</span>' . 
								// 			'</td>';

								// 		 	if($show_third_col)
								// 			{
								// 				echo 
								// 				'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
								// 					'<span id="' . $display_class_lye_beg . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
								// 						negative_bracket($total_beg) . 
								// 					'</span>' . 
								// 				'</td>';
								// 			}
								// 		}
								// 	'</tr>';
								// 	/* END OF DISPLAY TOTAL FOR EACH CATEGORY */
							// }
						}
					}


					
					/* DISPLAY TOTAL ASSETS */
					if($level_1_description == "Assets" && (count($level_1['child_array']) > 0) && !$hide_main_title)
					{
						$total_assets 	  = 0.00;
						$total_assets_end = 0.00;
						$total_assets_beg = 0.00;

						if($is_group)
						{
							$total_assets 	  = $total_assets_g;
							$total_assets_end = $total_assets_g_end;
							$total_assets_beg = $total_assets_g_beg;
						}
						else
						{
							$total_assets 	  = $total_assets_c;
							$total_assets_end = $total_assets_c_end;
							$total_assets_beg = $total_assets_c_beg;
						}

						echo 
						'<tr class="total_assets">' . 
							'<td><strong>Total assets</strong></td>' . 
							'<td></td>' . 
							'<td style="width: 0.5%;">&nbsp;</td>' .
							'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
								'<span id="'. $display_class_ye .'_total_assets" class="total_assets">' . negative_bracket($total_assets) . '</span>' .
							'</td>';

							if(!empty($last_fye_end))
							{
								echo 
								'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
									'<span id="' . $display_class_lye_end . '_total_assets" class="total_assets_end">' . negative_bracket($total_assets_end) . '</span>' . 
								'</td>';

							 	if($show_third_col)
								{
									echo 
									'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
										'<span id="' . $display_class_lye_beg . '_total_assets" class="total_assets_beg">' . negative_bracket($total_assets_beg) . '</span>' . 
									'</td>';
								}
							}
						echo '</tr>';
						
					}
					/* END OF DISPLAY TOTAL ASSETS */

					/* DISPLAY TOTAL LIABILITES */
					if($level_1_description == "Liabilities" && (count($level_1['child_array']) > 0) && !$hide_main_title)
					{
						if(in_array("Liabilities", $eq_liabi_title_list))
						{
							$total_liabilities 	   = 0.00;
							$total_liabilities_end = 0.00;
							$total_liabilities_beg = 0.00;

							if($is_group)
							{
								$total_liabilities 	   = $total_liabilities_g;
								$total_liabilities_end = $total_liabilities_g_end;
								$total_liabilities_beg = $total_liabilities_g_beg;
							}
							else
							{
								$total_liabilities 	   = $total_liabilities_c;
								$total_liabilities_end = $total_liabilities_c_end;
								$total_liabilities_beg = $total_liabilities_c_beg;
							}

							echo 
							'<tr>' .  
								'<td><strong>Total liabilities</strong></td>' . 
								'<td></td>' . 
								'<td style="width: 0.5%;">&nbsp;</td>' .
								'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
									'<span id="'. $display_class_ye .'_total_liabilities">' . negative_bracket($total_liabilities) . '</span>' .
								'</td>';

								if(!empty($last_fye_end))
								{
									echo 
									'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
										'<span id="'. $display_class_lye_end .'_total_liabilities">' . negative_bracket($total_liabilities_end) . '</span>' .
									'</td>';

								 	if($show_third_col)
									{
										echo 
										'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
											'<span id="'. $display_class_lye_beg .'_total_liabilities">' . negative_bracket($total_liabilities_beg) . '</span>' . 
										'</td>';
									}
								}
							echo '</tr>';

							echo 
							'<tr>' . 
								'<td colspan="5">&nbsp;</td>' . 
							'</tr>';
						}
					}
					/* END OF DISPLAY TOTAL LIABILITES */

					/* DISPLAY TOTAL EQUITY & LIABILITIES */
					elseif($level_1_key == count($data) - 1)
					{
						if(count($eq_liabi_title_list) > 1)
						{
							$total_equity_liabilities 	  = 0.00;
							$total_equity_liabilities_end = 0.00;
							$total_equity_liabilities_beg = 0.00;

							if($is_group)
							{
								$total_equity_liabilities 	  = $total_equity_g 	+ $total_liabilities_g;
								$total_equity_liabilities_end = $total_equity_g_end + $total_liabilities_g_end;
								$total_equity_liabilities_beg = $total_equity_g_beg + $total_liabilities_g_beg;
							}
							else
							{
								$total_equity_liabilities 	  = $total_equity_c 	+ $total_liabilities_c;
								$total_equity_liabilities_end = $total_equity_c_end + $total_liabilities_c_end;
								$total_equity_liabilities_beg = $total_equity_c_beg + $total_liabilities_c_beg;
							}

							echo 
							'<tr class="total_equity_liabilities">' . 
								'<td><strong>Total equity and liabilities</strong></td>' . 
								'<td></td>' . 
								'<td style="width: 0.5%;">&nbsp;</td>' .
								'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
									'<span id="'. $display_class_ye .'_total_equity_liabilities" class="total_equity_liabilities">' . negative_bracket($total_equity_liabilities) . '</span>' . 
								'</td>';
								if(!empty($last_fye_end))
								{
									echo 
									'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
										'<span id="'. $display_class_lye_end .'_total_equity_liabilities" class="total_equity_liabilities_end">' . negative_bracket($total_equity_liabilities_end) . '</span>' . 
									'</td>';

								 	if($show_third_col)
									{
										echo 
										'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
											'<span id="'. $display_class_lye_beg .'_total_equity_liabilities" class="total_equity_liabilities_beg">' . negative_bracket($total_equity_liabilities_beg) . '</span>' .
										'</td>';
									}
								}
							echo '</tr>';
						}

						
					}
					/* END OF DISPLAY TOTAL LIABILITES, TOTAL EQUITY & LIABILITIES */

					/* BREAK LINE */
					if($level_1_key < (count($data[0]) - 1) && (count($level_1['child_array']) > 0) && !$hide_main_title)
					{
						echo 
						'<tr>' . 
							'<td colspan="5">&nbsp;</td>' . 
						'</tr>';
					}
					/* END OF BREAK LINE */
				}
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
	var fs_ntfs_layout_template_list = <?php echo json_encode($fs_ntfs_layout_template_list); ?>;
	var fs_notes_details_state_2 	 = <?php echo json_encode($fs_notes_details_state_2); ?>;

	$('.edit_note').hide();

	var last_fye_end = '<?php echo $last_fye_end; ?>';
	var is_group 	 = '<?php echo $is_group; ?>';
	// var show_third_col = '<?php echo $show_third_col?>';

	show_FP_unbalanced_msg(true);

	/* ------------------ DO NOT DELETE THIS ------------------ */
	// Manual auto rearrange note section (toggle)
	// var auto_rearrange_value = <?php echo $auto_rearrange_value; ?>;

	// if(!auto_rearrange_value)
	// {
	// 	$('.edit_note').hide();
	// 	$('.edit_note_no').show();
	// }

	// $("[name='on_auto_rearrange']").bootstrapSwitch({
	//     state: auto_rearrange_value,
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
	//     })

	//     if(state)
 //    	{
 //    		$('.edit_note_no').hide();
 //    	}
 //    	else
 //    	{
 //    		$('.edit_note_no').show();
 //    	}
	// })
	/* ------------------ END OF DO NOT DELETE THIS ------------------ */
</script>