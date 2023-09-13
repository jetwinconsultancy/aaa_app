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

<form id="form_state_cash_flows">
	<?php
		if($show_data_content)
		{
	?>
	<input type="hidden" id="statement_doc_type_id" class="statement_doc_type" name="statement_doc_type" value="3">

	<table class="table table-hover table-borderless" style="width: 100%; border-collapse: collapse; color:black;" border="0">
		<thead>
			<tr>
				<th style="width: 1%;" rowspan="3">&nbsp;</th>
				<th style="width: 1%;" rowspan="3">&nbsp;</th>
				<th style="width: 42%;" rowspan="3">&nbsp;</th>
				<th style="text-align: center; width: 5%;" rowspan="2">&nbsp;</th>
				<th style="width: 0.5%;" rowspan="2">&nbsp;</th>
				<?php
					if($is_group)
					{
						if(!empty($last_fye_end))
						{
							echo '<th style="width: 25%; text-align: center; border-bottom:1px solid black;" colspan="2">Group</th>';
						}
						else
						{
							echo '<th style="width: 25%; text-align: center; border-bottom:1px solid black;" colspan="1">Group</th>';
						}

						echo '<th style="width: 1%;">&nbsp;</th>';
					}

					if(!empty($last_fye_end))
					{
						echo '<th style="width: 25%; text-align: center; border-bottom:1px solid black;" colspan="2">Company</th>';
					}
					else
					{
						echo '<th style="width: 25%; text-align: center; border-bottom:1px solid black;" colspan="1">Company</th>';
					}
				?>
			</tr>
			<tr>
				<?php
					if($is_group)
					{
						echo '<th style="width: 12.5%; text-align: center;">' . $current_fye_end . '</th>';

						if(!empty($last_fye_end))
						{
							echo '<th style="width: 12.5%; text-align: center;">' . $last_fye_end . '</th>';
						}

						echo '<th style="width: 1%;">&nbsp;</th>';
					}
				?>

				<?php
					echo '<th style="width: 12.5%; text-align: center;">' .$current_fye_end . '</th>';

					if(!empty($last_fye_end))
					{
						echo '<th style="width: 12.5%; text-align: center;">' . $last_fye_end . '</th>';
					}
				?>
			</tr>
			<tr>
				<th style="text-align: center; width: 5%;">
					<div class="input-group">
		                <!-- <input type="checkbox" name="on_auto_rearrange" checked/> -->
		               <!--  <input type="hidden" name="auto_rearrange_value" value="<?php echo $auto_rearrange_value; ?>"/> -->
		            </div>
					Note
				</th>
				<th style="width: 0.5%;">&nbsp;</th>

				<?php
					if($is_group)
					{
						echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>';

						if(!empty($last_fye_end))
						{
							echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>';
						}
							  
						echo '<th style="width: 1%;">&nbsp;</th>';
					}
				?>

				<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>

				<?php 
					if(!empty($last_fye_end))
					{
						echo '<th style="width: 12.5%; text-align: center; border-bottom:1px solid black;">$</th>';
					}
				?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="10">&nbsp;</td>
			</tr>

			<!-- Operating activities -->
			<tr>
				<td style="width: 1%;">
					<input type="hidden"  name="show_operating_act" id="check_operating_act" value="<?php echo $check_operating_act[0]['status']; ?>" <?php echo ($check_operating_act[0]['status'])?"checked":"" ?> />
				</td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">
					<b><i>Operating activities</i></b>
				</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}

						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td style="width: 12.5%;">&nbsp;</td>

				<?php
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>
			</tr>

			<?php
				if(count($pl_be4_tax_values_from_sci) > 0)
				{
					echo 
					'<tr class="op_act_group pl_be4_tax">' .
						'<td style="width: 1%;">&nbsp;</td>' .
						'<td style="width: 1%;">&nbsp;</td>' . 
						'<td style="width: 42%;">' . $pl_be4_tax_values_from_sci[0]['description'] . '</td>' . 
						'<td style="width: 5%;">&nbsp;</td>' . 
						'<td style="width: 0.5%;">&nbsp;</td>';

						if($is_group)
						{
							echo '<td class="group_ye" style="width: 12.5%; text-align:right;">' . negative_bracket($pl_be4_tax_values_from_sci[0]['value_group_ye']) . '</td>';

							if(!empty($last_fye_end))
							{
								echo '<td class="group_lye" style="width: 12.5%; text-align:right;">' . negative_bracket($pl_be4_tax_values_from_sci[0]['value_group_lye_end']) . '</td>';
							}
								  
							echo '<td style="width: 1%;">&nbsp;</td>';
						}

						echo '<td class="company_ye" style="width: 12.5%; text-align:right;">' . negative_bracket($pl_be4_tax_values_from_sci[0]['value_company_ye']) . '</td>';
						if(!empty($last_fye_end))
						{
							echo '<td class="company_lye" style="width: 12.5%; text-align:right;">' . negative_bracket($pl_be4_tax_values_from_sci[0]['value_company_lye_end']) . '</td>';	
						}

					echo '</tr>';
				}
			?>

			<!-- <tr class="op_act_group">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[profit_before_tax]"  value="1"></td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">Profit before tax</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;"><input type="number" style="text-align:right;" name="group_ye[profit_before_tax]" value="'.$fs_state_cash_flows_fixed['profit_before_tax']['group_ye'].'" class="form-control" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;"><input type="number" style="text-align:right;" name="group_lye_end[profit_before_tax]" value="'.$fs_state_cash_flows_fixed['profit_before_tax']['group_lye_end'].'" class="form-control" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td style="width: 12.5%;"><input type="number" style="text-align:right;" name="company_ye[profit_before_tax]" <?php echo 'value="'.$fs_state_cash_flows_fixed['profit_before_tax']['company_ye'].'"'?> class="form-control" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>
				
				<?php
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;"><input type="number" style="text-align:right;" name="company_lye_end[profit_before_tax]" value="'.$fs_state_cash_flows_fixed['profit_before_tax']['company_lye_end'].'" class="form-control" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"></td>';
					}
				?>
			</tr> -->

			<!-- New Line -->
			<tr><td colspan="10">&nbsp;</td></tr>
			<!-- New Line -->

			<tr class="op_act_group no_edit" id="adjustment">
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 1%;">
					<!-- <a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="add_row('', 1, '#adjustment')"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a> -->
				</td>
				<td style="width: 42%;">
					<u>Adjustments for:-</u>
				</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
						
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>

				<td style="width: 12.5%;">&nbsp;</td>

				<?php
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>
			</tr>


			<tr class="op_act_group no_edit" id="changes">
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 1%;">
					<!-- <a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="add_row('', 1, '#changes')"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a> -->
				</td>
				<td style="width: 42%;">
					<u>Changes in working capital</u>
				</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>

				<?php

					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>

				<td style="width: 12.5%;">&nbsp;</td>
			</tr>

			<tr class="op_act_group" id="net_cash">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[net_cash_operation]"  value="1"></td>
				<td style="width: 1%;">
					<!-- <a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="add_row('', 1, '#net_cash')"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a> -->
				</td>
				<td style="width: 42%;">
					<u>Net cash from operations</u>
				</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td class="group_ye" style="width: 12.5%; border-top: 1px solid black; text-align: right;">' .
								// '<input type="hidden" style="text-align:right;" value="'.$fs_state_cash_flows_fixed['net_cash_operation']['group_ye'].'" name="group_ye[net_cash_operation]" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
								'<span class="display_text"></span>' .
							'</td>';
						
						if(!empty($last_fye_end))
						{
							echo '<td class="group_lye" style="width: 12.5%; border-top: 1px solid black; text-align: right;">' .
									// '<input type="hidden" style="text-align:right;" value="'.$fs_state_cash_flows_fixed['net_cash_operation']['group_lye_end'].'" name="group_lye_end[net_cash_operation]" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
									'<span class="display_text"></span>' .
								'</td>';
						}

						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td class="company_ye" style="width: 12.5%; border-top: 1px solid black; text-align: right;">
					<!-- <input type="hidden" style="text-align:right;" name="company_ye[net_cash_operation]" <?php echo 'value="'.$fs_state_cash_flows_fixed['net_cash_operation']['company_ye'].'"'?> class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"> -->
					<span class="display_text"></span>
				</td>

				<?php
					if(!empty($last_fye_end))
					{
						echo '<td class="company_lye" style="width: 12.5%; border-top: 1px solid black; text-align: right;">' .
								// '<input type="hidden" style="text-align:right;" name="company_lye_end[net_cash_operation]" value="'.$fs_state_cash_flows_fixed['net_cash_operation']['company_lye_end'].'" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
								'<span class="display_text"></span>' .
							'</td>';
					}
				?>
				
			</tr> 

			<tr class="op_act_group net_cash_opt">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[net_cash_movement_op]"  value="1"></td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">Net cash movement in operating activities</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo 
						'<td class="group_ye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">' . 
							// '<input type="hidden" name="group_ye[net_cash_movement_op]" style="text-align:right;" value="'.$fs_state_cash_flows_fixed['net_cash_movement_op']['group_ye'].'" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'<span class="display_text"></span>' .
						'</td>';

						if(!empty($last_fye_end))
						{
							echo 
							'<td class="group_lye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">' . 
								// '<input type="hidden" name="group_lye_end[net_cash_movement_op]" style="text-align:right;" value="'.$fs_state_cash_flows_fixed['net_cash_movement_op']['group_lye_end'].'" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'<span class="display_text"></span>' .
							'</td>';
						}

						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td class="company_ye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">
					<!-- <input type="hidden" style="text-align:right;" name="company_ye[net_cash_movement_op]" <?php echo 'value="'.$fs_state_cash_flows_fixed['net_cash_movement_op']['company_ye'].'"'?> class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"> -->
					<span class="display_text"></span>
				</td>

				<?php 
					if(!empty($last_fye_end))
					{
						echo 
						'<td class="company_lye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">' . 
							// '<input type="hidden" style="text-align:right;" name="company_lye_end[net_cash_movement_op]" value="'.$fs_state_cash_flows_fixed['net_cash_movement_op']['company_lye_end'].'" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'<span class="display_text"></span>' .
						'</td>';
					}
				?>
			</tr> 
			<!-- End of Operating activities -->

			<tr>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">&nbsp;</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td style="width: 12.5%;">&nbsp;</td>

				<?php 
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>
			</tr> 

			<!-- Start of investing activities -->
			<tr id="investing">
				<td style="width: 1%;">
					<input type="hidden"  name="show_investing_act" id="check_investing_act" value="<?php echo $check_investing_act[0]['status']; ?>" <?php echo ($check_investing_act[0]['status'])?"checked":"" ?> />
				</td>
				<td style="width: 1%;">
					<!-- <a class="inv_act_group" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="add_row('', 2, '#investing')"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a> -->
				</td>
				<td style="width: 42%;">
					<b><i>Investing activities</i></b>
				</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td style="width: 12.5%;">&nbsp;</td>
				
				<?php 
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>
			</tr>

			<tr class="inv_act_group net_cash_inv">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[net_cash_movement_inv]"  value="2"></td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">Net cash movement in investing activities</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo 
						'<td class="group_ye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align:right;">' . 
							// '<input type="hidden" style="text-align:right;" name="group_ye[net_cash_movement_inv]" value="'.$fs_state_cash_flows_fixed['net_cash_movement_inv']['group_ye'].'" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'<span class="display_text"></span>' .
						'</td>';

						if(!empty($last_fye_end))
						{
							echo 
							'<td class="group_lye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align:right;">' . 
								// '<input type="hidden" style="text-align:right;" name="group_lye_end[net_cash_movement_inv]" value="'.$fs_state_cash_flows_fixed['net_cash_movement_inv']['group_lye_end'].'" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'<span class="display_text"></span>' .
							'</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td class="company_ye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align:right;">
					<!-- <input type="hidden" style="text-align:right;" name="company_ye[net_cash_movement_inv]" <?php echo 'value="'.$fs_state_cash_flows_fixed['net_cash_movement_inv']['company_ye'].'"'?> class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"> -->
					<span class="display_text"></span>
				</td>

				<?php
					if(!empty($last_fye_end))
					{
						echo 
						'<td class="company_lye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align:right;">' . 
							// '<input type="hidden" style="text-align:right;" name="company_lye_end[net_cash_movement_inv]" value="'.$fs_state_cash_flows_fixed['net_cash_movement_inv']['company_lye_end'].'" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'<span class="display_text"></span>' .
						'</td>';
					}
				?>
				
			</tr> 
			<!-- End of investing activities -->

			<tr>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">&nbsp;</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td style="width: 12.5%;">&nbsp;</td>
				
				<?php 
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>
			</tr> 

			<!-- Start of financing activities -->
			<tr id="financing">
				<td style="width: 1%;">
					<input type="hidden"  name="show_financing_act" id="check_financing_act" value="<?php echo $check_financing_act[0]['status']; ?>" <?php echo ($check_financing_act[0]['status'])?"checked":"" ?> />
				</td>
				<td style="width: 1%;">
					<!-- <a class="fin_act_group" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="add_row('', 3, '#financing')"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a> -->
				</td>
				<td style="width: 42%;">
					<b><i>Financing activities</i></b>
				</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td style="width: 12.5%;">&nbsp;</td>
				
				<?php 
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>
			</tr>

			<tr class="fin_act_group net_cash_fin">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[net_cash_movement_fin]"  value="3"></td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">Net cash movement in financing activities</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo 
						'<td class="group_ye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">' . 
							// '<input type="hidden" style="text-align:right;" name="group_ye[net_cash_movement_fin]" value="'.$fs_state_cash_flows_fixed['net_cash_movement_fin']['group_ye'].'" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'<span class="display_text"></span>' .
						'</td>';

						if(!empty($last_fye_end))
						{
							echo 
							'<td class="group_lye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">' . 
								// '<input type="hidden" style="text-align:right;" name="group_lye_end[net_cash_movement_fin]" value="'.$fs_state_cash_flows_fixed['net_cash_movement_fin']['group_lye_end'].'" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
								'<span class="display_text"></span>' . 
							'</td>';
						}

						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td class="company_ye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">
					<!-- <input type="hidden" style="text-align:right;" name="company_ye[net_cash_movement_fin]" <?php echo 'value="'.$fs_state_cash_flows_fixed['net_cash_movement_fin']['company_ye'].'"'?> class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"> -->
					<span class="display_text"></span>
				</td>

				<?php 
					if(!empty($last_fye_end))
					{
						echo 
						'<td class="company_lye" style="width: 12.5%; border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">' . 
							// '<input type="hidden" style="text-align:right;" name="company_lye_end[net_cash_movement_fin]" value="'.$fs_state_cash_flows_fixed['net_cash_movement_fin']['company_lye_end'].'" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
							'<span class="display_text"></span>' .
						'</td>';
					}
				?>
				
			</tr> 
			<!-- End of financing activities -->

			<tr>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">&nbsp;</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';

						if(!empty($last_fye_end))
						{
							echo '<td style="width: 12.5%;">&nbsp;</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td style="width: 12.5%;">&nbsp;</td>

				<?php
					if(!empty($last_fye_end))
					{
						echo '<td style="width: 12.5%;">&nbsp;</td>';
					}
				?>
			</tr> 


			<tr class="changes_in_cash_n_cash_eq">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[changes_cash_equivalent]"  value="4"></td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">Changes in cash and cash equivalents</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo 
						'<td class="group_ye" style="width: 12.5%; text-align:right;">' . 
							// '<input type="number" style="text-align:right;" name="group_ye[changes_cash_equivalent]"  value="'.$fs_state_cash_flows_fixed['changes_cash_equivalent']['group_ye'].'" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' . 
							'<span class="display_text"></span>' . 
						'</td>';

						if(!empty($last_fye_end))
						{
							echo 
							'<td class="group_lye" style="width: 12.5%; text-align:right;">' . 
								// '<input type="number" style="text-align:right;" name="group_lye_end[changes_cash_equivalent]" value="'.$fs_state_cash_flows_fixed['changes_cash_equivalent']['group_lye_end'].'" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' . 
								'<span class="display_text"></span>' . 
							'</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td class="company_ye" style="width: 12.5%; text-align:right;">
					<!-- <input type="number" style="text-align:right;" name="company_ye[changes_cash_equivalent]" <?php echo 'value="'.$fs_state_cash_flows_fixed['changes_cash_equivalent']['company_ye'].'"'?> class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1"> -->
					<span class="display_text"></span>
				</td>

				<?php
					if(!empty($last_fye_end))
					{
						echo 
						'<td class="company_lye" style="width: 12.5%; text-align:right;">' . 
							// '<input type="number" style="text-align:right;" name="company_lye_end[changes_cash_equivalent]" value="'.$fs_state_cash_flows_fixed['changes_cash_equivalent']['company_lye_end'].'" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' .
							'<span class="display_text"></span>' . 
						'</td>';
					}
				?>
			</tr> 

			<tr class="cash_n_eq_beg">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[cash_equivalent_begin]" value="5"></td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;">Cash and equivalent at beginning of the year</td>
				<td style="width: 5%;">&nbsp;</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td class="group_ye" style="width: 12.5%; text-align:right;"><input type="number" style="text-align:right;" name="group_ye[cash_equivalent_begin]" value="'.$fs_state_cash_flows_fixed['cash_equivalent_begin']['group_ye'].'" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1"></td>';

						if(!empty($last_fye_end))
						{
							echo '<td class="group_lye" style="width: 12.5%; text-align:right;"><input type="number" style="text-align:right;" name="group_lye_end[cash_equivalent_begin]" value="'.$fs_state_cash_flows_fixed['cash_equivalent_begin']['group_lye_end'].'" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1"></td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td class="company_ye" style="width: 12.5%; text-align:right;"><input type="number" style="text-align:right;" name="company_ye[cash_equivalent_begin]" <?php echo 'value="'.$fs_state_cash_flows_fixed['cash_equivalent_begin']['company_ye'].'"'?> class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1"></td>

				<?php
					if(!empty($last_fye_end))
					{
						echo '<td class="company_lye" style="width: 12.5%; text-align:right;"><input type="number" style="text-align:right;" name="company_lye_end[cash_equivalent_begin]" value="'.$fs_state_cash_flows_fixed['cash_equivalent_begin']['company_lye_end'].'" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1"></td>';
					}
				?>
			</tr> 

			<tr class="cash_n_eq_end">
				<td style="width: 1%;">&nbsp;<input type="hidden" name="fixed_category_id[cash_equivalent_end]"  value="6"></td>
				<td style="width: 1%;">&nbsp;</td>
				<td style="width: 42%;" class="description_name">Cash and equivalent at end of the year</td>
				<td class="cf_note_0" style="width: 5%; text-align:center;vertical-align: middle;">
					<a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer; " onclick="add_note(this, 0)">
						<?php 
							if($fs_state_cash_flows_fixed['cash_equivalent_end']['note_display_num'])
							{
								// print_r($fs_state_cash_flows_fixed['cash_equivalent_end']['note_display_num']);
								echo '<i class="inserted_note_no" style="font-size:14px;">' . $fs_state_cash_flows_fixed['cash_equivalent_end']['note_display_num'][0]['note_num_displayed'].'</i>';
							}
							else
							{
								echo '<i class="fa fa-plus-circle" style="font-size:14px;"></i>';
							}

						?>
						
					</a>
					<input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="input_note_0" <?php echo 'value="'.$fs_state_cash_flows_fixed['cash_equivalent_end']['fs_note_details_id'].'"'?>>
				</td>
				<td style="width: 0.5%;">&nbsp;</td>

				<?php
					if($is_group)
					{
						echo '<td class="group_ye" style="width: 12.5%; text-align:right; border-top: 1px solid black; border-bottom: 3px double black;">' . 
								// '<input type="hidden" style="text-align:right;" name="group_ye[cash_equivalent_end]" value="'.$fs_state_cash_flows_fixed['cash_equivalent_end']['group_ye'].'" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'<input type="hidden" style="text-align:right;" name="group_ye[cash_equivalent_end]" value="" class="form-control val_group_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
								'<span class="display_text"></span>' .
							'</td>';

						if(!empty($last_fye_end))
						{
							echo '<td class="group_lye" style="width: 12.5%; text-align:right; border-top: 1px solid black; border-bottom: 3px double black;">' . 
									// '<input type="hidden" style="text-align:right;" name="group_lye_end[cash_equivalent_end]" value="'.$fs_state_cash_flows_fixed['cash_equivalent_end']['group_lye_end'].'" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
									'<input type="hidden" style="text-align:right;" name="group_lye_end[cash_equivalent_end]" value="" class="form-control val_group_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
									'<span class="display_text"></span>' .
								'</td>';
						}
							  
						echo '<td style="width: 1%;">&nbsp;</td>';
					}
				?>
				<td class="company_ye" style="width: 12.5%; text-align:right; border-top: 1px solid black; border-bottom: 3px double black;">
					<!-- <input type="hidden" style="text-align:right;" name="company_ye[cash_equivalent_end]" <?php echo 'value="'.$fs_state_cash_flows_fixed['cash_equivalent_end']['company_ye'].'"'?> class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1"> -->
					<input type="hidden" style="text-align:right;" name="company_ye[cash_equivalent_end]" value="" class="form-control val_company_ye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">
					<span class="display_text"></span>
				</td>

				<?php
					if(!empty($last_fye_end))
					{
						echo 
						'<td class="company_lye" style="width: 12.5%; text-align:right; border-top: 1px solid black; border-bottom: 3px double black;">' . 
								// '<input type="hidden" style="text-align:right;" name="company_lye_end[cash_equivalent_end]" value="'.$fs_state_cash_flows_fixed['cash_equivalent_end']['company_lye_end'].'" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
							'<input type="hidden" style="text-align:right;" name="company_lye_end[cash_equivalent_end]" value="" class="form-control val_company_lye" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
								'<span class="display_text"></span>' .
						'</td>';
					}
				?>
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
	var is_group = <?php echo json_encode(isset($is_group)?$is_group:"") ?>;
	var fs_state_cash_flows = <?php echo json_encode(isset($fs_state_cash_flows)?$fs_state_cash_flows:"")?>;	
	var arr_deleted_row = [];
	var index = 1;
	var section_flag = {};
	var last_fye_end = '<?php echo $last_fye_end ?>';

	// console.log(last_fye_end);

	// console.log(fs_state_cash_flows);
	retrieve_data(fs_state_cash_flows);
	autoCalculateTable();
	get_initial_checkbox();

	function retrieve_data(list)
	{
		for (i = 0; i < list.length; i++) 
		{
			add_row(list[i], list[i]['category_id'], list[i]['parent_id']);
			// .then(
			// 	function(){
			// 		add_dir_row_init(list[i]['id']);
			// 	});
			// console.log(list[i]);
			// console.log('--- newline ---');
		}
	}


	$('#check_operating_act').change(function(){
		if(!this.checked)
		{
			$(".op_act_group").hide();
			section_flag[1] = 0;

		}
		else
		{
			$(".op_act_group").show();
			section_flag[1] = 1;

		}
	});

	$('#check_investing_act').change(function(){
		if(!this.checked)
		{
			$(".inv_act_group").hide();
			section_flag[2] = 0;
	
		}
		else
		{
			$(".inv_act_group").show();
			section_flag[2] = 1;

		}
	});

	$('#check_financing_act').change(function(){
		if(!this.checked)
		{
			$(".fin_act_group").hide();
			section_flag[3] = 0;

		}
		else
		{
			$(".fin_act_group").show();
			section_flag[3] = 1;

		}
	});

	function get_initial_checkbox(){
		var chkBox1 = document.getElementById('check_operating_act');
		var chkBox2 = document.getElementById('check_investing_act');
		var chkBox3 = document.getElementById('check_financing_act');

		if (!chkBox1.checked)
	    {
			$(".op_act_group").hide();
			section_flag[1] = 0;
		}
		else
		{
			$(".op_act_group").show();
			section_flag[1] = 1;
		}

	    if (!chkBox2.checked)
	    {
			$(".inv_act_group").hide();
			section_flag[2] = 0;
		}
		else
		{
			$(".inv_act_group").show();
			section_flag[2] = 1;
		}

	    if (!chkBox3.checked)
	    {
			$(".fin_act_group").hide();
			section_flag[3] = 0;
		}
		else
		{
			$(".fin_act_group").show();
			section_flag[3] = 1;
		}
	}

	function autoCalculateTable()
	{
		var total_g_ye = {
	                      	net_cash_frm_opt: 0,
	                        net_cash_opt: 0,
	                        net_cash_inv: 0,
	                        net_cash_fin: 0,
	                        changes_in_cash_n_cash_eq: 0,
	                        cash_n_eq_end: 0
						},
			total_g_lye = {
	                      	net_cash_frm_opt: 0,
	                        net_cash_opt: 0,
	                        net_cash_inv: 0,
	                        net_cash_fin: 0,
	                        changes_in_cash_n_cash_eq: 0,
	                        cash_n_eq_end: 0
						},
			total_c_ye = {
	                      	net_cash_frm_opt: 0,
	                        net_cash_opt: 0,
	                        net_cash_inv: 0,
	                        net_cash_fin: 0,
	                        changes_in_cash_n_cash_eq: 0,
	                        cash_n_eq_end: 0
						},
			total_c_lye = {
	                      	net_cash_frm_opt: 0,
	                        net_cash_opt: 0,
	                        net_cash_inv: 0,
	                        net_cash_fin: 0,
	                        changes_in_cash_n_cash_eq: 0,
	                        cash_n_eq_end: 0
						};

		$('#form_state_cash_flows table tbody tr').each(function(index, element)
		{
			if($(element).hasClass('op_act_group') && !($(element).hasClass('no_edit')))
			{
				if($(element)[0].id == 'net_cash')
				{
					total_c_ye['net_cash_frm_opt'] 	= total_c_ye['net_cash_opt'];
					total_c_lye['net_cash_frm_opt'] = total_c_lye['net_cash_opt'];
					total_g_ye['net_cash_frm_opt'] 	= total_g_ye['net_cash_opt'];
					total_g_lye['net_cash_frm_opt'] = total_g_lye['net_cash_opt'];

					if(is_group)
					{
						$(element).find('.group_ye .display_text').text(negative_bracket_js(total_g_ye['net_cash_frm_opt']));
						$(element).find('td.group_ye .val_group_ye').val(total_g_ye['net_cash_frm_opt']); // update hidden values
						
						if(last_fye_end !== '')
						{
							$(element).find('.group_lye .display_text').text(negative_bracket_js(total_g_lye['net_cash_frm_opt']));
							$(element).find('td.group_lye .val_group_lye').val(total_g_lye['net_cash_frm_opt']);
						}
					}

					$(element).find('.company_ye .display_text').text(negative_bracket_js(total_c_ye['net_cash_frm_opt']));
					$(element).find('td.company_ye .val_company_ye').val(total_c_ye['net_cash_frm_opt']); // update hidden values
					
					if(last_fye_end !== '')
					{
						$(element).find('.company_lye .display_text').text(negative_bracket_js(total_c_lye['net_cash_frm_opt']));
						$(element).find('td.company_lye .val_company_lye').val(total_c_lye['net_cash_frm_opt']);
					}
				}
				else if($(element).hasClass('net_cash_opt'))
				{
					if(is_group)
					{
						$(element).find('.group_ye .display_text').text(negative_bracket_js(total_g_ye['net_cash_opt']));
						$(element).find('td.group_ye .val_group_ye').val(total_g_ye['net_cash_opt']); // update hidden values
						
						if(last_fye_end !== '')
						{
							$(element).find('.group_lye .display_text').text(negative_bracket_js(total_g_lye['net_cash_opt']));
							$(element).find('td.group_lye .val_group_lye').val(total_g_lye['net_cash_opt']);
						}
					}

					$(element).find('.company_ye .display_text').text(negative_bracket_js(total_c_ye['net_cash_opt']));
					$(element).find('td.company_ye .val_company_ye').val(total_c_ye['net_cash_opt']);	// update hidden values
					
					if(last_fye_end !== '')
					{
						$(element).find('.company_lye .display_text').text(negative_bracket_js(total_c_lye['net_cash_opt']));
						$(element).find('td.company_lye .val_company_lye').val(total_c_lye['net_cash_opt']);
					}
				}
				else
				{
					if($(element).hasClass('pl_be4_tax')) // exception for profit before tax (values take from text)
					{
						if(is_group)
						{
							total_g_ye['net_cash_opt']  += negative_bracket_to_number($(element).find('td.group_ye').text());

							if(last_fye_end !== '')
							{
								total_g_lye['net_cash_opt'] += negative_bracket_to_number($(element).find('td.group_lye').text());
							}
						}

						total_c_ye['net_cash_opt']  += negative_bracket_to_number($(element).find('td.company_ye').text());

						if(last_fye_end !== '')
						{
							total_c_lye['net_cash_opt'] += negative_bracket_to_number($(element).find('td.company_lye').text());
						}
					}
					else
					{
						if(is_group)
						{
							total_g_ye['net_cash_opt']  += negative_bracket_to_number($(element).find('td.group_ye .val_group_ye').val());

							if(last_fye_end !== '')
							{
								total_g_lye['net_cash_opt'] += negative_bracket_to_number($(element).find('td.group_lye .val_group_lye').val());
							}
						}
						
						total_c_ye['net_cash_opt']  += negative_bracket_to_number($(element).find('td.company_ye').text());

						if(last_fye_end !== '')
						{
							total_c_lye['net_cash_opt'] += negative_bracket_to_number($(element).find('td.company_lye .val_company_lye').val());
						}
					}
				}
			}
			else if($(element).hasClass('inv_act_group'))
			{
				if($(element).hasClass('net_cash_inv'))
				{
					if(is_group)
					{
						$(element).find('.group_ye .display_text').text(negative_bracket_js(total_g_ye['net_cash_inv']));
						$(element).find('td.group_ye .val_group_ye').val(total_g_ye['net_cash_inv']);	// update hidden values

						if(last_fye_end !== '')
						{
							$(element).find('.group_lye .display_text').text(negative_bracket_js(total_g_lye['net_cash_inv']));
							$(element).find('td.group_lye .val_group_lye').val(total_g_lye['net_cash_inv']);
						}
					}
					
					$(element).find('.company_ye .display_text').text(negative_bracket_js(total_c_ye['net_cash_inv']));
					$(element).find('td.company_ye .val_company_ye').val(total_c_ye['net_cash_inv']);	// update hidden values
					
					if(last_fye_end !== '')
					{
						$(element).find('.company_lye .display_text').text(negative_bracket_js(total_c_lye['net_cash_inv']));
						$(element).find('td.company_lye .val_company_lye').val(total_c_lye['net_cash_inv']);
					}
				}
				else
				{
					if(is_group)
					{
						total_g_ye['net_cash_inv'] += negative_bracket_to_number($(element).find('td.group_ye .val_group_ye').val());

						if(last_fye_end !== '')
						{
							total_g_lye['net_cash_inv'] += negative_bracket_to_number($(element).find('td.group_lye .val_group_lye').val());
						}
					}
					
					total_c_ye['net_cash_inv'] += negative_bracket_to_number($(element).find('td.company_ye').text());

					if(last_fye_end !== '')
					{
						total_c_lye['net_cash_inv'] += negative_bracket_to_number($(element).find('td.company_lye .val_company_lye').val());
					}
				}
			}
			else if($(element).hasClass('fin_act_group'))
			{
				if($(element).hasClass('net_cash_fin'))
				{
					if(is_group)
					{
						$(element).find('.group_ye .display_text').text(negative_bracket_js(total_g_ye['net_cash_fin']));
						$(element).find('td.group_ye .val_group_ye').val(total_g_ye['net_cash_fin']);
						
						if(last_fye_end !== '')
						{
							$(element).find('.group_lye .display_text').text(negative_bracket_js(total_g_lye['net_cash_fin']));
							$(element).find('td.group_lye .val_group_lye').val(total_g_lye['net_cash_fin']);
						}
					}
					
					$(element).find('.company_ye .display_text').text(negative_bracket_js(total_c_ye['net_cash_fin']));
					$(element).find('td.company_ye .val_company_ye').val(total_c_ye['net_cash_fin']);	// update hidden values
					
					if(last_fye_end !== '')
					{
						$(element).find('.company_lye .display_text').text(negative_bracket_js(total_c_lye['net_cash_fin']));
						$(element).find('td.company_lye .val_company_lye').val(total_c_lye['net_cash_fin']);
					}
				}
				else
				{
					if(is_group)
					{
						total_g_ye['net_cash_fin'] += negative_bracket_to_number($(element).find('td.group_ye .val_group_ye').val());

						if(last_fye_end !== '')
						{
							total_g_lye['net_cash_fin'] += negative_bracket_to_number($(element).find('td.group_lye .val_group_lye').val());
						}
					}
					
					total_c_ye['net_cash_fin'] += negative_bracket_to_number($(element).find('td.company_ye').text());

					if(last_fye_end !== '')
					{
						total_c_lye['net_cash_fin'] += negative_bracket_to_number($(element).find('td.company_lye .val_company_lye').val());
					}
				}
			}
			else if($(element).hasClass('changes_in_cash_n_cash_eq'))
			{
				if($('#check_operating_act').val() == 0)
				{
					total_g_ye['net_cash_opt']  = 0;
					total_g_lye['net_cash_opt'] = 0;
					total_c_ye['net_cash_opt']  = 0;
					total_c_lye['net_cash_opt'] = 0;
				}

				if($('#check_investing_act').val() == 0)
				{
					total_g_ye['net_cash_inv']  = 0;
					total_g_lye['net_cash_inv'] = 0;
					total_c_ye['net_cash_inv']  = 0;
					total_c_lye['net_cash_inv'] = 0;
				}

				if($('#check_financing_act').val() == 0)
				{
					total_g_ye['net_cash_fin']  = 0;
					total_g_lye['net_cash_fin'] = 0;
					total_c_ye['net_cash_fin']  = 0;
					total_c_lye['net_cash_fin'] = 0;
				}

				total_g_ye['changes_in_cash_n_cash_eq']  = total_g_ye['net_cash_opt'] + total_g_ye['net_cash_inv'] + total_g_ye['net_cash_fin'];
				total_g_lye['changes_in_cash_n_cash_eq'] = total_g_lye['net_cash_opt'] + total_g_lye['net_cash_inv'] + total_g_lye['net_cash_fin'];
				total_c_ye['changes_in_cash_n_cash_eq']  = total_c_ye['net_cash_opt'] + total_c_ye['net_cash_inv'] + total_c_ye['net_cash_fin'];
				total_c_lye['changes_in_cash_n_cash_eq'] = total_c_lye['net_cash_opt'] + total_c_lye['net_cash_inv'] + total_c_lye['net_cash_fin'];

				if(is_group)
				{
					$(element).find('.group_ye .display_text').text(negative_bracket_js(total_g_ye['changes_in_cash_n_cash_eq']));

					if(last_fye_end !== '')
					{
						$(element).find('.group_lye .display_text').text(negative_bracket_js(total_g_lye['changes_in_cash_n_cash_eq']));
					}
				}

				$(element).find('.company_ye .display_text').text(negative_bracket_js(total_c_ye['changes_in_cash_n_cash_eq']));

				if(last_fye_end !== '')
				{
					$(element).find('.company_lye .display_text').text(negative_bracket_js(total_c_lye['changes_in_cash_n_cash_eq']));
				}
			}
			else if($(element).hasClass('cash_n_eq_beg'))
			{
				if(is_group)
				{
					total_g_ye['cash_n_eq_end'] += negative_bracket_to_number($(element).find('td.group_ye .val_group_ye').val());

					if(last_fye_end !== '')
					{
						total_g_lye['cash_n_eq_end'] += negative_bracket_to_number($(element).find('td.group_lye .val_group_lye').val());
					}
				}
				
				total_c_ye['cash_n_eq_end'] += negative_bracket_to_number($(element).find('td.company_ye .val_company_ye').val());

				if(last_fye_end !== '')
				{
					total_c_lye['cash_n_eq_end'] += negative_bracket_to_number($(element).find('td.company_lye .val_company_lye').val());
				}
			}
			else if($(element).hasClass('cash_n_eq_end'))
			{
				total_g_ye['cash_n_eq_end']  = total_g_ye['changes_in_cash_n_cash_eq'] + total_g_ye['cash_n_eq_end'];
				total_g_lye['cash_n_eq_end'] = total_g_lye['changes_in_cash_n_cash_eq'] + total_g_lye['cash_n_eq_end'];
				total_c_ye['cash_n_eq_end']  = total_c_ye['changes_in_cash_n_cash_eq'] + total_c_ye['cash_n_eq_end'];
				total_c_lye['cash_n_eq_end'] = total_c_lye['changes_in_cash_n_cash_eq'] + total_c_lye['cash_n_eq_end'];

				if(is_group)
				{
					$(element).find('td.group_ye .display_text').text(negative_bracket_js(total_g_ye['cash_n_eq_end']));

					if(last_fye_end !== '')
					{
						$(element).find('td.group_lye .display_text').text(negative_bracket_js(total_g_lye['cash_n_eq_end']));
					}
				}

				$(element).find('td.company_ye .display_text').text(negative_bracket_js(total_c_ye['cash_n_eq_end']));

				if(last_fye_end !== '')
				{
					$(element).find('td.company_lye .display_text').text(negative_bracket_js(total_c_lye['cash_n_eq_end']));
				}
			}
		});
	}

	function add_row(data, category, parent){
		
		// console.log(data);

		var tr = $(parent);
		// console.log(tr);
		var template = "";
		var tr_class = "";

		var input_id = "";
		var input_desc = "";
		var input_note_id = "";
		var input_value_company_ye = "";
		var input_value_company_lye_end = "";
		var input_value_group_ye = "";
		var input_value_group_lye_end = "";
		var input_parent = "";
		var input_category = "";

		// console.log(data);

		if(data != '')
		{
			var id_value_hidden 			  = data['id'];
			var note_id_value_hidden 		  = data['fs_note_details_id'];
			var note_num_displayed 			  = data['note_num_displayed'];
			var description_value  			  = data['description'];
			var value_group_ye   			  = data['value_group_ye'];
			var value_group_lye_end	 		  = data['value_group_lye_end']
			var value_company_ye 	  		  = data['value_company_ye'];
			var value_company_lye_end 	  	  = data['value_company_lye_end'];
		}

		if(category == 1)
		{
			tr_class = "op_act_group";
		}
		else if(category == 2)
		{
			tr_class = "inv_act_group";
		}
		else if(category == 3)
		{
			tr_class = "fin_act_group";
		}


		if(note_num_displayed != null)
		{
			var add_note_btn = '<i class="inserted_note_no" style="font-size:14px;">' + note_num_displayed + '</i>';
		}
		else
		{
			var add_note_btn = '<i class="fa fa-plus-circle" style="font-size:14px;"></i>';
		}


		input_id = 'input_id['+ index + ']';
		input_desc = 'input_desc['+ index +']';
		input_note_id = 'input_note_id['+ index +']';
		input_value_company_ye = 'input_value_company_ye['+ index +']';
		input_value_company_lye_end = 'input_value_company_lye_end['+ index +']';
		input_value_group_ye = 'input_value_group_ye['+ index +']';
		input_value_group_lye_end = 'input_value_group_lye_end['+ index +']';
		input_parent = 'input_parent['+ index +']';
		input_category = 'input_category['+ index +']';

		// console.log(input_value_group_lye_end);


		// var index = tr.attr('class').replace("company_", "");
		if (data != ""){
			if(is_group){

				if(last_fye_end !== '')
				{
					template = '<tr class="'+ tr_class +'">' + 
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"  value="'+id_value_hidden+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'" value="'+description_value+'">' + description_value + '</td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')">'+
								add_note_btn+
								'</a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'" value="'+note_id_value_hidden+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td class="group_ye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_group_ye" name="'+input_value_group_ye+'" value="'+value_group_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
								'<td class="group_lye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_group_lye" name="'+input_value_group_lye_end+'" value="'+value_group_lye_end+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
								'<td style="width: 1%;">&nbsp;</td>'+
								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" value="'+value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
									negative_bracket_js(value_company_ye) + 
								'</td>'+
								'<td class="company_lye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_company_lye" name="'+input_value_company_lye_end+'" value="'+value_company_lye_end+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
								
							'</tr>';
				}
				else
				{
					template = '<tr class="'+ tr_class +'">' + 
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"  value="'+id_value_hidden+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'" value="'+description_value+'">' + description_value + '</td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')">'+
								add_note_btn+
								'</a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'" value="'+note_id_value_hidden+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td class="group_ye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_group_ye" name="'+input_value_group_ye+'" value="'+value_group_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1" onkeyup="autoCalculateTable()">' + 
								'</td>'+
								
								'<td style="width: 1%;">&nbsp;</td>'+
								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" value="'+value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
									negative_bracket_js(value_company_ye) + 
								'</td>'+
								
							'</tr>';
				}		
			}
			else{

				if(last_fye_end !== '')
				{
					template = '<tr class="'+ tr_class +'">' +
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"  value="'+id_value_hidden+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'" value="'+description_value+'">' + description_value + '</td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')">'+add_note_btn+'</a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'" value="'+note_id_value_hidden+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" value="'+value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' +
									negative_bracket_js(value_company_ye) +  
								'</td>'+
								'<td class="company_lye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_company_lye" name="'+input_value_company_lye_end+'" value="'+value_company_lye_end+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
							'</tr>';
				}
				else
				{
					template = '<tr class="'+ tr_class +'">' +
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"  value="'+id_value_hidden+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'" value="'+description_value+'">' + description_value + '</td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')">'+add_note_btn+'</a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'" value="'+note_id_value_hidden+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" value="'+value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
									negative_bracket_js(value_company_ye) + 
								'</td>'+
							'</tr>';
				}
				
							
			}
		}
		else{
			if(is_group){

				if(last_fye_end !== '')
				{
					template = '<tr class="'+ tr_class +'">' + 
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'"></td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')"><i class="fa fa-plus-circle" style="font-size:14px;"></i></a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_group_ye" name="'+input_value_group_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
								'<td style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_group_lye" name="'+input_value_group_lye_end+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
								'<td style="width: 1%;">&nbsp;</td>'+
								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
									negative_bracket_js(0) + 
								'</td>'+
								'<td class="company_lye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_company_lye" name="'+input_value_company_lye_end+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
							'</tr>';
				}
				else
				{
					template = '<tr class="'+ tr_class +'">' + 
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'"></td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')"><i class="fa fa-plus-circle" style="font-size:14px;"></i></a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td class="group_ye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_group_ye" name="'+input_value_group_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
								'<td style="width: 1%;">&nbsp;</td>'+
								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
									negative_bracket_js(0) + 
								'</td>'+
								
							'</tr>';
				}

			}
			else{

				if(last_fye_end !== '')
				{
					template = '<tr class="'+ tr_class +'">' +
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'"></td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')"><i class="fa fa-plus-circle" style="font-size:14px;"></i></a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
									negative_bracket_js(0) + 
								'</td>'+
								'<td class="company_lye" style="width: 12.5%; text-align:right;">' + 
									'<input type="number" style="text-align:right;" class="form-control val_company_lye" name="'+input_value_company_lye_end+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
								'</td>'+
							'</tr>';
				}
				else
				{
					template = '<tr class="'+ tr_class +'">' +
								'<td style="width: 1%;">&nbsp;<input type="hidden" style="text-align:left;" class="input_id" name="'+input_id+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_parent+'" value="'+parent+'"><input type="hidden" style="text-align:left;" class="form-control" name="'+input_category+'" value="'+category+'"></td>'+
								'<td style="width: 1%;">' + 
									// '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>' + 
								'</td>'+
								'<td style="width: 42%;"><input type="hidden" style="text-align:left;" class="form-control description_name" name="'+input_desc+'"></td>'+
								'<td class="cf_note_'+index+'" style="width: 5%; text-align:center;vertical-align: middle;"><a class="add_note" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Add Note" style="font-weight:bold; cursor: pointer;" onclick="add_note(this, '+index+')"><i class="fa fa-plus-circle" style="font-size:14px;"></i></a><input type="hidden" style="text-align:left;" class="form-control input_note_id fs_note_templates_master_id" name="'+input_note_id+'"></td>'+
								'<td style="width: 0.5%;">&nbsp;</td>'+

								'<td class="company_ye" style="width: 12.5%; text-align:right;">' + 
									// '<input type="hidden" style="text-align:right;" class="form-control val_company_ye" name="'+input_value_company_ye+'" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" onkeyup="autoCalculateTable()" step="1">' + 
									negative_bracket_js(0) + 
								'</td>'+
							'</tr>';
				}		
			}
		}
		
		// console.log(template);
		$(template).insertAfter(tr);

		index++;
	}



	// var auto_rearrange_value = <?php echo $auto_rearrange_value; ?>;

	// $('.edit_note').hide();

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


</script>