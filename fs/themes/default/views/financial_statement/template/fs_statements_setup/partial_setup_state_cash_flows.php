<!-- <form id="form_fs_setup_cfs">
	<div style="height:60vh; width: 100%; overflow-x: hidden; overflow-y: hidden;" >
		<div id="setup_cfs" ></div>
	</div>
</form> -->


<style type="text/css">

/*table.dataTable,
table.dataTable th,
table.dataTable td {
  -webkit-box-sizing: content-box;
  -moz-box-sizing: content-box;
  box-sizing: content-box;
}*/


.ui-state-focus {
  background: none !important;
  background-color: lightgrey !important;
  border: none !important;
  color: black !important;
}

.ui-menu-item
{
  background-color: white;
}

th, td { white-space: nowrap; }
div.dataTables_wrapper {
    width: 90vw;
    margin: 0 auto;
}
</style>

<style>
  #buttonclick .datatables-header {
    display:none;
  }
</style>

<!-- Retrieve from online -->

<!-- <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/south-street/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="//cdn.datatables.net/plug-ins/725b2a2115b/integration/jqueryui/dataTables.jqueryui.css" rel="stylesheet" type="text/css" />

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.3.2/css/fixedColumns.dataTables.min.css" />

<script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery.ui-contextmenu/1.7.0/jquery.ui-contextmenu.min.js"></script> -->

<!-- END OF Retrieve from online -->

<link href="assets/vendor/jquery-ui-themes-1.11.1/themes/south-street/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="assets/vendor/DataTables/~External/datatable-plug-ins/725b2a2115b/integration/jqueryui/dataTables.jqueryui.css" rel="stylesheet" type="text/css" />

<script src="assets/vendor/jquery-ui/1.11.1/jquery-ui.min.js"></script>

<link rel="stylesheet" href="assets/vendor/DataTables/FixedColumns-3.3.2/css/fixedColumns.dataTables.min.css" />

<script src="assets/vendor/DataTables/FixedColumns-3.3.2/js/dataTables.fixedColumns.min.js"></script>
<script src="assets/vendor/DataTables/~External/jquery.ui-contextmenu/1.7.0/jquery.ui-contextmenu.min.js"></script>


<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<div class="header_between_all_section">
  <section class="panel">
    <?php echo $breadcrumbs;?>
    <div class="panel-body">
      <div class="col-md-12">
        <form id="form_fs_setup_cfs">
          <input type="hidden" id="fs_company_info_id" name="fs_company_info_id" value="<?=$fs_company_info[0]['id']?>">
          <table id="tbl_setup_cfs" class="table table-bordered" style="width:100%">
            <thead>
              <th>
                <p style="min-width: 10px;">&nbsp;</p>
              </th>
              <th>
                <p style="min-width: 10px;">&nbsp;</p>
              </th>
              <th>
                <p style="min-width: 200px;">&nbsp;</p>
              </th>
              <th data-align="right">
                <p style="min-width: 70px;">&nbsp;</p>
                  <input type="hidden" class="header_id" name="header_id" value="<?=$cfs_header['header_id']?>">
              </th>
              <?php
                  foreach ($cfs_header['items'] as $key => $value) 
                  {
                      echo 
                      '<th data-editable="true" data-align="right">' .
                          '<input type="hidden" name="header_items_fcaro_id[]" value="' . $value['fcaro_id'] . '">' .
                          $value['description'] . 
                      '</th>';
                  }
              ?>
              <th>
                <p style="min-width: 70px;">
                  Check
                </p>
              </th>
            </thead>
            <tbody id="tbody_fs_setup_cfs">
              <?php
                // display FYE (current year)
                echo 
                '<tr class="no_edit">' . 
                    '<td>&nbsp;</td>' .
                    '<td>&nbsp;</td>' .
                    '<td>' . 
                        '<b>FYE ' . substr($fs_company_info[0]['current_fye_end'], 3) . '</b>' .
                    '</td>' . 
                    '<td style="text-align:center;">S$</td>';

                    foreach ($cfs_header['items'] as $key => $value) 
                    {
                        echo 
                        '<td style="text-align:right;">' .
                            negative_bracket((int)$value['total_c']) . 
                        '</td>';
                    }

                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';

                if(!$fs_company_info[0]['first_set'])
                {
                  // display FYE (last year)
                  echo 
                  '<tr class="no_edit">' . 
                      '<td>&nbsp;</td>' . 
                      '<td>&nbsp;</td>' . 
                      '<td>' . 
                          '<b>FYE ' . substr($fs_company_info[0]['last_fye_end'], 3)  . '</b>' . 
                      '</td>' . 
                      '<td style="text-align:center;">S$</td>';

                      foreach ($cfs_header['items'] as $key => $value) 
                      {
                          echo 
                          '<td style="text-align:right;">' . 
                              negative_bracket((int)$value['total_c_lye']) . 
                          '</td>';
                      }

                  echo
                    '<td class="check_by_row" style="text-align:right;"></td>' . 
                  '</tr>';
                }

                /* -------------- Display Different between 2 years -------------- */
                echo 
                '<tr class="diff_vals no_edit">' . 
                  '<td>&nbsp;</td>' . 
                  '<td>&nbsp;</td>' . 
                  '<td>' . 
                    '<i>Diff</i>' . 
                  '</td>' . 
                  '<td></td>'; 

                  foreach ($cfs_header['items'] as $key => $value) 
                  {
                    $diff = (int)$value['total_c'];

                    // minus last year 
                    if(!$fs_company_info[0]['first_set'])
                    {
                      $diff = (int)$value['total_c_lye'] - $diff;
                    }

                    echo 
                    '<td style="text-align:right;">' .
                      negative_bracket($diff) .
                    '</td>';
                  }
                echo 
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* -------------- END OF Display Different between 2 years -------------- */

                /* -------- New line -------- */
                echo 
                '<tr class="no_edit">' .
                  '<td>&nbsp;</td>' . 
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' . 
                  '<td>&nbsp;</td>';

                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* -------- END OF New line -------- */

                /* ------------- Display Profit before tax values -------------- */
                foreach ($cfs_body_data['pl_be4_tax'] as $key => $value) 
                {
                  echo 
                  '<tr class="setup_pl_b4_tax">' .
                    '<td>' . 
                      '<input type="hidden" class="body_id" name="setup_cfs_id[]" value="' . $value['id'] . '">' .
                      '<input type="hidden" name="setup_cfs_parent_id[]" value="' . $value['parent_id'] . '">' .
                      '<input type="hidden" name="setup_cfs_category_id[]" value="' . $value['category_id'] . '">' .
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" name="setup_cfs_is_checked[]" value="' . $value['is_checked'] . '">' . 
                    '</td>' .
                    '<td style="font-weight: bold;">' .
                      '<input type="hidden" class="description" name="setup_cfs_description[]" value="' . $value['description'] . '">' .
                      $value['description'] . 
                    '</td>' .
                    '<td style="text-align:right;">' .
                      '<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="' . $value['main_value'] . '">'.
                      negative_bracket($value['main_value']) . 
                    '</td>';

                    foreach ($value['row_item'] as $key2 => $value2)
                    {
                      // set background to grey color if value is an adjustment value
                      $background_color = '';
                      $class_adj_val_td = '';
                        
                      if($value['is_adjustment_values'][$key2])
                      {
                        $background_color = 'background-color: lightgrey;';
                        $class_adj_val_td = 'set_adj_val_td';
                      }

                      echo 
                      '<td class="' . $class_adj_val_td . '" style="text-align:right; ' . $background_color . '">' .
                        '<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="' . $value['is_adjustment_values'][$key2] . '">' . 
                        '<input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' . $value2 . '">' . negative_bracket($value2) .
                      '</td>';
                    }
                  echo 
                    '<td class="check_by_row" style="text-align:right;"></td>' . 
                  '</tr>';
                }
                /* ------------- END OF Display Profit before tax values -------------- */

                /* -------- New line -------- */
                echo 
                '<tr class="no_edit">' . 
                  '<td>&nbsp;</td>' . 
                  '<td>&nbsp;</td>' . 
                  '<td>&nbsp;</td>' . 
                  '<td>&nbsp;</td>';

                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* -------- END OF New line -------- */

                /* ------------ Operating activities ------------ */
                $checked = 'checked';

                if(!$check_operating_act[0]['status'])
                {
                  $checked = '';
                }

                echo
                '<tr class="no_edit">' .
                  '<td>' .
                    '<input type="hidden" class="checkbox_id" name="hide_show_id_opt" value="' . $check_operating_act[0]['id'] . '" />' .
                    '<input type="checkbox" class="cbx_operating_act"' . $checked . ' onclick="change_cbx_status(this, \'opt\', 0)" />' . 
                    '<input type="hidden"  name="check_operating_act" class="cbx_status_opt" value="' . $check_operating_act[0]['status'] . '"/>' . 
                    '<input type="hidden" name="check_operating_section_id" value="1" />' . 
                  '</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>' .
                    '<b><i>Operating activities</i></b>' .
                  '</td>' .
                  '<td>&nbsp;</td>';

                foreach ($cfs_header['items'] as $key => $value) 
                {
                  echo '<td style="text-align:right;"></td>';
                }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';

                echo
                '<tr class="opt_act_group no_edit" id="setup_adjustment">' .
                  '<td>&nbsp;</td>' .
                  '<td>' .
                    '<a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="setup_cfs_add_row(\'\', 1, \'#adjustment\', this)"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a>' .
                  '</td>' .
                  '<td><u>Adjustments for:-</u></td>' .
                  '<td></td>';
                  
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';

                /* Adjustment for: (list of dynamic added data) */
                foreach ($cfs_body_data['adjustment'] as $key => $value) 
                {
                  echo 
                  '<tr class="opt_act_group">' .
                    '<td>' . 
                      '<input type="hidden" class="body_id" name="setup_cfs_id[]" value="' . $value['id'] . '">' .
                      '<input type="hidden" name="setup_cfs_parent_id[]" value="' . $value['parent_id'] . '">' .
                      '<input type="hidden" name="setup_cfs_category_id[]" value="' . $value['category_id'] . '">' .
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" name="setup_cfs_is_checked[]" value="' . $value['is_checked'] . '">' . 
                      '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="setup_cfs_delete_row(this)">' . 
                        '<i class="fa fa-minus-circle" style="font-size:12px;"></i>' .
                      '</a>' . 
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" class="description" name="setup_cfs_description[]" value="' . $value['description'] . '">' .
                      $value['description'] . 
                    '</td>' .
                    '<td style="text-align:right;">' .
                      '<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="' . $value['main_value'] . '">'.
                      negative_bracket($value['main_value']) . 
                    '</td>';

                    foreach ($value['row_item'] as $key2 => $value2)
                    {
                      // set background to grey color if value is an adjustment value
                      $background_color = '';
                      $class_adj_val_td = '';
                        
                      if($value['is_adjustment_values'][$key2])
                      {
                        $background_color = 'background-color: lightgrey;';
                        $class_adj_val_td = 'set_adj_val_td';
                      }

                      echo 
                      '<td class="' . $class_adj_val_td . '" style="text-align:right; ' . $background_color . '">' .
                        '<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="' . $value['is_adjustment_values'][$key2] . '">' . 
                        '<input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' . $value2 . '">' . negative_bracket($value2) .
                      '</td>';
                    }
                  echo 
                    '<td class="check_by_row" style="text-align:right;"></td>' . 
                  '</tr>';
                }

                /* New line */
                echo 
                '<tr class="opt_act_group no_edit">' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* END OF New line */

                /* Changes in working capital */
                echo
                '<tr class="opt_act_group no_edit" id="setup_changes">'.
                  '<td>&nbsp;</td>'.
                  '<td></td>'.
                  '<td><u>Changes in working capital</u></td>'.
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';

                /* Changes in working capital (list of dynamic added data) */
                foreach ($cfs_body_data['changes'] as $key => $value) 
                {
                  $checked = '';

                  if($value['is_checked'] == 1)
                  {
                    $checked = 'checked';
                  }

                  echo 
                  '<tr class="opt_act_group">' .
                    '<td>' . 
                      '<input type="hidden" class="body_id" name="setup_cfs_id[]" value="' . $value['id'] . '">' .
                      '<input type="hidden" name="setup_cfs_parent_id[]" value="' . $value['parent_id'] . '">' .
                      '<input type="hidden" name="setup_cfs_category_id[]" value="' . $value['category_id'] . '">' .
                    '</td>' .
                    '<td>' .
                      '<input type="checkbox" class="checkbox_opt_act_changes" value="' . $value['is_checked'] . '" ' . $checked . ' onclick="change_cbx_status(this, \'opt_act_changes\', ' . $key . ')">' . 
                      '<input type="hidden" class="cbx_opt_act_changes" name="setup_cfs_is_checked[]" value="' . $value['is_checked'] . '">' .
                     '</td>' .
                    '<td>' .
                      '<input type="hidden" class="description" name="setup_cfs_description[]" value="' . $value['description'] . '">' .
                      $value['description'] .
                    '</td>' .
                    '<td style="text-align:right;">' .
                      '<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="' . $value['main_value'] . '">'.
                      negative_bracket($value['main_value']) . 
                    '</td>';

                    foreach ($value['row_item'] as $key2 => $value2)
                    {
                      // set background to grey color if value is an adjustment value
                      $background_color = '';
                      $class_adj_val_td = '';
                        
                      if($value['is_adjustment_values'][$key2])
                      {
                        $background_color = 'background-color: lightgrey;';
                        $class_adj_val_td = 'set_adj_val_td';
                      }

                      echo 
                      '<td class="' . $class_adj_val_td . '" style="text-align:right; ' . $background_color . '">' .
                        '<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="' . $value['is_adjustment_values'][$key2] . '">' . 
                        '<input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' . $value2 . '">' . negative_bracket($value2) .
                      '</td>';
                    }
                  echo 
                    '<td class="check_by_row" style="text-align:right;"></td>' . 
                  '</tr>';
                }

                /* Net cash from operations */
                echo
                '<tr class="net_cash_frm_opt opt_act_group no_edit">'.
                  '<td>&nbsp;</td>'.
                  '<td></td>'.
                  '<td><u>Net cash from operations</u></td>'.
                  '<td style="border-top: 1px solid black; text-align:right;"></td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';

                /* Net cash from operations (list of dynamic added data) */
                foreach ($cfs_body_data['net_cash'] as $key => $value) 
                {
                  echo 
                  '<tr class="opt_act_group">' .
                    '<td>' . 
                      '<input type="hidden" class="body_id" name="setup_cfs_id[]" value="' . $value['id'] . '">' .
                      '<input type="hidden" name="setup_cfs_parent_id[]" value="' . $value['parent_id'] . '">' .
                      '<input type="hidden" name="setup_cfs_category_id[]" value="' . $value['category_id'] . '">' .
                    '</td>' .
                    '<td>' . 
                      '<input type="hidden" name="setup_cfs_is_checked[]" value="1">' .
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" class="description" name="setup_cfs_description[]" value="' . $value['description'] . '">' .
                      $value['description'] .
                    '</td>' .
                    '<td style="text-align:right;">' .
                      '<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="' . $value['main_value'] . '">'.
                      negative_bracket($value['main_value']) . 
                    '</td>';

                    foreach ($value['row_item'] as $key2 => $value2)
                    {
                      // set background to grey color if value is an adjustment value
                      $background_color = '';
                      $class_adj_val_td = '';
                        
                      if($value['is_adjustment_values'][$key2])
                      {
                        $background_color = 'background-color: lightgrey;';
                        $class_adj_val_td = 'set_adj_val_td';
                      }

                      echo 
                      '<td class="' . $class_adj_val_td . '" style="text-align:right; ' . $background_color . '">' .
                        '<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="' . $value['is_adjustment_values'][$key2] . '">' . 
                        '<input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' . $value2 . '">' . negative_bracket($value2) .
                      '</td>';
                    }
                  echo 
                    '<td class="check_by_row" style="text-align:right;"></td>' . 
                  '</tr>';
                }
                
                /* New line */
                echo 
                '<tr class="opt_act_group no_edit">' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* END OF New line */

                /* Net cash from operations */
                echo
                '<tr class="opt_net_cash no_edit">'.
                  '<td>&nbsp;</td>'.
                  '<td></td>'.
                  '<td>Net cash movement in operating activities</td>'.
                  '<td style="text-align:right; border-top: 1px solid black; border-bottom: 1px solid black;">-</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* ------------ END OF Operating activities ------------ */

                /* New line */
                echo 
                '<tr class="no_edit">' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* END OF New line */

                /* ------------ Investing activities ------------ */
                $checked = '';

                if($check_investing_act[0]['status'] == 1)
                {
                  $checked = 'checked';
                }

                echo 
                '<tr class="no_edit" id="setup_investing">' .
                  '<td style="width: 1%;">' . 
                    '<input type="hidden" class="checkbox_id" name="hide_show_id_inv" value="' . $check_investing_act[0]['id'] . '" />' .
                    '<input type="checkbox" class="cbx_investing_act" ' . $checked . ' onclick="change_cbx_status(this, \'inv\', 0)"/>' .
                    '<input type="hidden"  name="check_investing_act" class="cbx_status_inv" value="' . $check_investing_act[0]['status'] . '" />' .
                    '<input type="hidden" name="check_investing_section_id" value="2" />' .
                  '</td>' . 
                  '<td style="width: 1%;">' . 
                    '<a class="inv_act_group" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="setup_cfs_add_row(\'\', 2, \'#investing\', this)"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a>' .
                  '</td>' .
                  '<td>' . 
                    '<b><i>Investing activities</i></b>' .
                  '</td>' .
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';

                foreach ($cfs_body_data['investing'] as $key => $value) 
                {
                  echo 
                  '<tr class="inv_act_group">' .
                    '<td>' . 
                      '<input type="hidden" class="body_id" name="setup_cfs_id[]" value="' . $value['id'] . '">' .
                      '<input type="hidden" name="setup_cfs_parent_id[]" value="' . $value['parent_id'] . '">' .
                      '<input type="hidden" name="setup_cfs_category_id[]" value="' . $value['category_id'] . '">' .
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" name="setup_cfs_is_checked[]" value="1">' . 
                      '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="setup_cfs_delete_row(this)">' . 
                        '<i class="fa fa-minus-circle" style="font-size:12px;"></i>' .
                      '</a>' . 
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" class="description" name="setup_cfs_description[]" value="' . $value['description'] . '">' .
                      $value['description'] .
                    '</td>' .
                    '<td style="text-align:right;">' .
                      '<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="' . $value['main_value'] . '">'.
                      negative_bracket($value['main_value']) . 
                    '</td>';

                    foreach ($value['row_item'] as $key2 => $value2)
                    {
                      // set background to grey color if value is an adjustment value
                      $background_color = '';
                      $class_adj_val_td = '';
                        
                      if($value['is_adjustment_values'][$key2])
                      {
                        $background_color = 'background-color: lightgrey;';
                        $class_adj_val_td = 'set_adj_val_td';
                      }

                      echo 
                      '<td class="' . $class_adj_val_td . '" style="text-align:right; ' . $background_color . '">' .
                        '<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="' . $value['is_adjustment_values'][$key2] . '">' . 
                        '<input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' . $value2 . '">' . negative_bracket($value2) .
                      '</td>';
                    }
                  echo 
                    '<td class="check_by_row" style="text-align:right;"></td>' . 
                  '</tr>';
                }

                /* Net cash from operations */
                echo
                '<tr class="inv_net_cash no_edit">'.
                  '<td>&nbsp;</td>'.
                  '<td></td>'.
                  '<td>Net cash movement in investing activities</td>'.
                  '<td style="text-align:right; border-top: 1px solid black; border-bottom: 1px solid black;">-</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* ------------ END OF Investing activities ------------ */
                /* New line */
                echo 
                '<tr class="no_edit">' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' . 
                '</tr>';
                /* END OF New line */

                /* ------------ Financial activities ------------ */
                $checked = '';

                if($check_financing_act[0]['status'] == 1)
                {
                  $checked = 'checked';
                }

                echo 
                '<tr class="no_edit" id="setup_financing">' .
                  '<td style="width: 1%;">' . 
                    '<input type="hidden" class="checkbox_id" name="hide_show_id_fin" value="' . $check_financing_act[0]['id'] . '" />' .
                    '<input type="checkbox" class="cbx_financing_act" ' . $checked . ' onclick="change_cbx_status(this, \'fin\', 0)"/>' .
                    '<input type="hidden"  name="check_financing_act" class="cbx_status_fin" value="' . $check_financing_act[0]['status'] . '" />' .
                    '<input type="hidden" name="check_financing_section_id" value="3" />' .
                  '</td>' . 
                  '<td style="width: 1%;">' . 
                    '<a class="fin_act_group" data-toggle="tooltip" data-trigger="hover" style="color:black; font-weight:bold; cursor: pointer;" onclick="setup_cfs_add_row(\'\', 3, \'#financing\', this)"><i class="fa fa-plus-circle" style="font-size:12px;"></i></a>' .
                  '</td>' .
                  '<td>' . 
                    '<b><i>Financing activities</i></b>' .
                  '</td>' .
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' .
                '</tr>';

                foreach ($cfs_body_data['financing'] as $key => $value) 
                {
                  echo 
                  '<tr class="fin_act_group">' .
                    '<td>' . 
                      '<input type="hidden" class="body_id" name="setup_cfs_id[]" value="' . $value['id'] . '">' .
                      '<input type="hidden" name="setup_cfs_parent_id[]" value="' . $value['parent_id'] . '">' .
                      '<input type="hidden" name="setup_cfs_category_id[]" value="' . $value['category_id'] . '">' .
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" name="setup_cfs_is_checked[]" value="1">' . 
                      '<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="setup_cfs_delete_row(this)">' . 
                        '<i class="fa fa-minus-circle" style="font-size:12px;"></i>' .
                      '</a>' . 
                    '</td>' .
                    '<td>' .
                      '<input type="hidden" class="description" name="setup_cfs_description[]" value="' . $value['description'] . '">' .
                      $value['description'] .
                    '</td>' .
                    '<td style="text-align:right;">' .
                      '<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="' . $value['main_value'] . '">'.
                      negative_bracket($value['main_value']) . 
                    '</td>';

                    foreach ($value['row_item'] as $key2 => $value2)
                    {
                      $background_color = '';
                      $class_adj_val_td = '';

                      if($value['is_adjustment_values'][$key2])
                      {
                        $background_color = 'background-color: lightgrey;';
                        $class_adj_val_td = 'set_adj_val_td';
                      }

                      echo 
                      '<td class="' . $class_adj_val_td . '" style="text-align:right; ' . $background_color . '">' .
                        '<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="' . $value['is_adjustment_values'][$key2] . '">' . 
                        '<input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' . $value2 . '">' . negative_bracket($value2) .
                      '</td>';
                    }
                  echo 
                    '<td class="check_by_row" style="text-align:right;"></td>' .
                  '</tr>';
                }

                /* Net cash from operations */
                echo
                '<tr class="fin_net_cash no_edit">'.
                  '<td>&nbsp;</td>'.
                  '<td></td>'.
                  '<td>Net cash movement in financing activities</td>'.
                  '<td style="text-align:right; border-top: 1px solid black; border-bottom: 1px solid black;">-</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' .
                '</tr>';
                /* ------------ END OF Financial activities ------------ */

                /* New line */
                echo 
                '<tr class="no_edit">' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>' .
                  '<td>&nbsp;</td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' .
                '</tr>';
                /* END OF New line */

                /* Check value */
                echo 
                '<tr class="check_val_col no_edit">' .
                  '<td></td>' .
                  '<td></td>' .
                  '<td style="color:blue; font-weight: bold;">Check</td>' .
                  '<td></td>';
                  foreach ($cfs_header['items'] as $key => $value)
                  {
                    echo '<td style="text-align:right;"></td>';
                  }
                echo
                  '<td class="check_by_row" style="text-align:right;"></td>' .
                '</tr>';
                /* END OF Check value */
            ?>
            </tbody>
          </table>

          <div class="col-md-12" style="margin: 1%; text-align: right;">
            <!-- <button type="button" data-dismiss="modal" class="btn">Cancel</button> -->
            <button id="save_setup_cfs" type="button" class="btn btn-primary">Save</button>
          </div>
          </form>
      </div>
    </div>
  </section>
</div>

<!-- for thousand separator -->
<script src="composer_plugin/node_modules/numeral/numeral.js"></script>
<script src="themes/default/assets/js/financial_statement/functions.js" charset="utf-8"></script>

<script src="themes/default/assets/js/financial_statement/partial_setup_state_cash_flows.js" charset="utf-8"></script>

<script type="text/javascript">
// var cfs_header_desc = '<?php echo json_encode($cfs_header['desc']); ?>';
// cfs_header_desc = ['', '', '', ''].concat(JSON.parse(cfs_header_desc));

var cfs_header_count = <?php echo count($cfs_header['items']); ?>;
var table;

var check_operating_act = <?php echo $check_operating_act[0]['status']; ?>;
var check_investing_act = <?php echo $check_investing_act[0]['status']; ?>;
var check_financing_act = <?php echo $check_financing_act[0]['status']; ?>;

var opt_act_changes_cbx = <?php echo json_encode($cfs_body_data['changes']); ?>;

</script>

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