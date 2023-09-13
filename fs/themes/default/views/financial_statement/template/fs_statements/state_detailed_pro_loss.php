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

<form id="form_state_detailed_profit_loss">
	<?php
		if($show_data_content)
		{
	?>
	<table id="tbl_state_detailed_profit_loss" class="table table-hover table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
		<thead>
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
		</thead>
		<tbody>
			<?php 
				$total = [];
				$total_revenue_current 			= 0.00; // (+) ** M1003
				$total_revenue_current_ly 		= 0.00; // (+) ** M1003
				$total_cost_of_sale_current 	= 0.00; // (-) ** M1004
				$total_cost_of_sale_current_ly 	= 0.00; // (-) ** M1004
				$total_other_income				= 0.00; // 	   ** M1005
				$total_other_income_ly			= 0.00; // 	   ** M1005

				$total_ly = [];

				$revenue_exist			= false;
				$cost_of_sales_exist	= false;

				$counter = 0;

				$closing_inventories_ac = "";
				$closing_inventories_key = array_search("Closing inventories", array_column($fs_ntfs_list, "description"));	// get key

				if(!empty($closing_inventories_key) || (string)$closing_inventories_key == 0)
	            {
	            	$closing_inventories_ac = $fs_ntfs_list[$closing_inventories_key]['account_code'];	// get description from fs_ntfs_list json from document name "Statement of detailed profit or loss"
	            }

				foreach($state_detailed_pro_loss_data as $key => $main_category)
		        {
		        	$add_less_display = "";
		        	$main_account_description = "";
		            $main_account_code = $main_category[0]['parent_array'][0]['account_code'];
		            
		            /* Settings */
		            // check using this account code > take the default description > do checking using description (list take from json)
		            $fs_ntfs_list_key = array_search($main_account_code, array_column($fs_ntfs_list, "account_code"));	// get key

		            if(!empty($fs_ntfs_list_key) || (string)$fs_ntfs_list_key == 0)
		            {
		            	$main_account_description = $fs_ntfs_list[$fs_ntfs_list_key]['description'];	// get description from fs_ntfs_list json from document name "Statement of detailed profit or loss"
		            }

		            if($main_account_description == "Revenue")	// revenue
		            {
		            	$revenue_exist = true;
		            }
		            elseif($main_account_description == "Cost of Sales")	// cost of sale
		            {
		            	$cost_of_sales_exist = true;
		            	$add_less_display 	 = "Less: ";
		            }
		            elseif($main_account_description == "Income")	// other income
		            {
		            	$add_less_display = "Add: ";
		            }
		            /* END OF Settings */

		            /* ------------------- If NO SUB under main category ------------------- */
	            	if(count($main_category[0]['child_array']) == 0)
	            	{
	            		$c_lye_value = 0.00;

	            		if(!empty($main_category[0]['parent_array'][0]['company_end_prev_ye_value']))
	            		{
	            			if($main_account_description == "Revenue" || $main_account_description == "Income")
            				{
            					$c_lye_value = $main_category[0]['parent_array'][0]['company_end_prev_ye_value'] * (-1);
            				}
            				else
            				{
            					$c_lye_value = $main_category[0]['parent_array'][0]['company_end_prev_ye_value'];
            				}
	            		}

	            		echo '<tr>' . 
								'<td><em>' . $add_less_display . ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) . '</em></td>' . 
								'<td style="text-align:right; border-bottom:1px solid black;"> - </td>';

								if(!empty($last_fye_end))
								{
									echo '<td align="right" style="border-bottom:1px solid black;">' . 
											// '<input type="hidden" name="SDPL_parent_fs_categorized_account_id['. $counter .']" value="'. $main_category[0]['parent_array'][0]['account_code'] .'">' .
											// '<input type="hidden" name="SDPL_sub_fs_categorized_account_id['. $counter .']" value="'. $main_category[0]['parent_array'][0]['id'] .'">' .
											// '<input type="text" name="SDPL_company_end_prev_year_value[' . $counter . ']" class="form-control SDPL_all_values SDPL_values_under_' . $main_category['parent_array'][0]['id'] . '" style="text-align: right;" value="' . $c_lye_value . '" onchange="calculation('. $main_category['parent_array'][0]['id'] .')">' .
											negative_bracket($c_lye_value) . 
										'</td>';
								}
						echo '</tr>';

						// total up 
						$temp_total    = 0.00;
						$temp_total_ly = $c_lye_value;
						$counter ++;
	            	}
	            	/* ------------------- END OF If NO SUB under main category ------------------- */

	            	/* If there are subs under main category */
	            	else
	            	{
	            		$temp_total = 0.00;

	            		echo '<tr>' . 
								'<td><em>' . $add_less_display . ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) . '</em></td>' .
								'<td></td>';
								if(!empty($last_fye_end))
								{
									echo '<td></td>';
								}
						echo '</tr>';

	            		foreach ($main_category[0]['child_array'] as $key2 => $child_array) 
	            		{
	            			if($child_array['parent_array'] != null && $child_array['child_array'] != null)
	            			{
	            				if($main_account_description == "Revenue" || $main_account_description == "Income")
	            				{
	            					$temp_value 	= $child_array['parent_array'][0]['total_c'] * (-1);
	            					$temp_value_ly 	= $child_array['parent_array'][0]['total_c_lye'] * (-1);
	            				}
	            				else
	            				{
	            					$temp_value 	= $child_array['parent_array'][0]['total_c'];
	            					$temp_value_ly 	= $child_array['parent_array'][0]['total_c_lye'];
	            				}

	            				/* ------ Add in Display "Less:" for Closing inventories ------ */
	            				if($child_array['parent_array'][0]['account_code'] == $closing_inventories_ac)
	            				{
	            					$child_array['parent_array'][0]['description'] = $add_less_display . $child_array['parent_array'][0]['description'];
	            				}
	            				/* ------ END OF Add in Display "Less:" for Closing inventories ------ */

	            				// Display child with subcategory. 
	            				echo '<tr>' . 
										'<td>' . $child_array['parent_array'][0]['description'] . '</td>' .
										'<td style="text-align:right;">'. negative_bracket($temp_value) .'</td>';
										if(!empty($last_fye_end))
										{
											echo '<td style="text-align:right;">' . negative_bracket($temp_value_ly);
										}

								echo '</td></tr>';

								// total up 
								$temp_total    += $temp_value;
								$temp_total_ly += $temp_value_ly;

								$counter++;
	            			}
	            			elseif($child_array['child_array'] != null)
	            			{
	            				if($main_account_description == "Revenue" || $main_account_description == "Income")
	            				{
	            					$temp_value = $child_array['child_array']['value'] * (-1);
	            				}
	            				else
	            				{
	            					$temp_value = $child_array['child_array']['value'];
	            				}

	            				// Display child without subcategory. 

	            				echo '<tr>' . 
										'<td>' . $child_array['child_array']['description'] . '</td>' .
										'<td style="text-align:right;">'. negative_bracket($temp_value) .'</td>';

										if(!empty($last_fye_end))
										{
											echo '<td style="text-align:right;">' . 
												// '<input type="hidden" name="SDPL_parent_fs_categorized_account_id['. $counter .']" value="'. $main_category['parent_array'][0]['account_code'] .'">' .
												// '<input type="hidden" name="SDPL_sub_fs_categorized_account_id['. $counter .']" value="'. $child_array['child_array']['id'] .'">' . 
												// '<input type="text" name="SDPL_company_end_prev_year_value[' . $counter . ']" class="form-control SDPL_all_values SDPL_values_under_' . $main_category['parent_array'][0]['id'] . '" style="text-align: right;" value="' . $child_array[0]['company_end_prev_ye_value'] . '" onchange="calculation('. $main_category['parent_array'][0]['id'] .')">';
												negative_bracket($child_array['child_array']['company_end_prev_ye_value']);
										}
										
								echo '</td></tr>';

								// total up 
								$temp_total    += $temp_value;
								$temp_total_ly += $child_array[0]['company_end_prev_ye_value'];

								$counter ++;
	            			}
		            	}

		            	// show total for the category
						echo '<tr>' .
								'<td></td>' .
								'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
									// '<input type="hidden" name="current_fye_end['. $key .']" value="'. $current_fye_end .'">' .
									// '<input type="hidden" name="category_total['. $key .']" value="'. $temp_total .'">' .
									// '<input type="hidden" name="categorized_account_id['. $key .']" value="'. $main_category['parent_array'][0]['id'] .'">' .
									'<span>' . negative_bracket($temp_total) . '</span>' .
								'</td>';

								if(!empty($last_fye_end))
								{
									echo '<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' . 
											// '<span id="SDPL_subtotal_'. $main_category['parent_array'][0]['id'] .'">' . negative_bracket($temp_total_ly) . '</span>' . 
											'<span>' . negative_bracket($temp_total_ly) . '</span>' . 
										'</td>';
								}
						echo '</tr>';
	            	}
	            	/* END OF If there are subs under main category */

					if($main_account_description == "Income" || (count($main_category['child_array']) == 0))
		            {
						echo '<tr><td colspan="3">&nbsp;</td></tr>';
		            }

		            if($main_account_description == "Revenue")
		            {
		            	$total_revenue_current 	  = $temp_total;
						$total_revenue_current_ly = $temp_total_ly;
		            }
		            elseif($main_account_description == "Cost of Sales")
		            {
		            	$total_cost_of_sale_current    = $temp_total;
						$total_cost_of_sale_current_ly = $temp_total_ly;
		            }
		            elseif($main_account_description == "Income")
		            {
		            	$total_other_income    = $temp_total;
						$total_other_income_ly = $temp_total_ly;
		            }

		            /* CALCULATE AND DISPLAY GROSS PROFIT */
		            if($main_account_description == "Cost of Sales" && $revenue_exist && $cost_of_sales_exist)
		            {
		            	$gross_profit 	 = $total_revenue_current - $total_cost_of_sale_current;
						$gross_profit_ly = $total_revenue_current_ly - $total_cost_of_sale_current_ly;

		            	echo '<tr>'.
		            			'<td><em>Gross Profit</em></td>' . 
		            			'<td style="text-align:right; border-bottom:1px solid black;">'. 
										// '<input type="hidden" name="gross_profit" value="' . $gross_profit . '" >' .
										negative_bracket($gross_profit) .
									'</td>';
									if(!empty($last_fye_end))
									{
										echo '<td style="text-align:right; border-bottom:1px solid black;">'. 
												// '<input type="hidden" name="gross_profit_ly" value="' . $gross_profit_ly . '" >' .
												'<span class="gross_profit_ly">' . negative_bracket($gross_profit_ly) . '</span>' .
											'</td>';
									}
		            		'</tr>';

		            	echo '<tr>' . 
								'<td>&nbsp;</td>'.
								'<td>&nbsp;</td>';
								if(!empty($last_fye_end))
								{
									echo '<td>&nbsp;</td>';
								}
						echo '</tr>';
		            }
		            /* END OF CALCULATE AND DISPLAY GROSS PROFIT */
		        }

		        /* DISPLAY OPERATING EXPENSES */
		        echo '<tr>' .
						'<td style="text-align:left;"><em>Less: Operating expenses (As per schedule)</em></td>' . 
						'<td style="text-align:right; border-bottom:1px solid black;" class="total_operating_expenses">' . negative_bracket($total_operating_expenses_current['total_operating_expenses']) . '</td>';
						if(!empty($last_fye_end))
						{
							echo '<td style="text-align:right; border-bottom:1px solid black;">' . 
									'<span class="total_operating_expenses_ly">' . negative_bracket($total_operating_expenses_current['total_operating_expenses_ly']) . '</span>' . 
								'</td>';
						}
				echo '</tr>';
				/* END OF DISPLAY OPERATING EXPENSES */

				/* CALCULATE AND DISPLAY PROFIT OF THE YEAR */
				$profit_of_the_year = ($total_revenue_current - $total_cost_of_sale_current) + $total_other_income - $total_operating_expenses_current['total_operating_expenses'];
				$profit_of_the_year_ly = ($total_revenue_current_ly - $total_cost_of_sale_current_ly) + $total_other_income_ly - $total_operating_expenses_current['total_operating_expenses_ly'];

				echo 
				'<tr>' .
					'<td style="text-align:left;">Profit of the year</td>' . 
					'<td style="text-align:right; border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">' . 
						// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
						negative_bracket($profit_of_the_year) . 
					'</td>';
					if(!empty($last_fye_end))
					{
						echo '<td style="text-align:right; border-top:1px solid black; border-bottom-style: double; border-bottom-width: 5px;">' . 
								// '<input type="hidden" name="profit_of_the_year_ly" value="'. $profit_of_the_year_ly .'">' .
								'<span class="profit_of_the_year_ly">' . negative_bracket($profit_of_the_year_ly) . '</span>' .  
							'</td>';
					}
				echo '</tr>';

				/* END OF CALCULATE AND DISPLAY PROFIT OF THE YEAR */
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