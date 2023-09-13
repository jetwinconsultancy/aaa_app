<style type="text/css">
.not_used_account.selected {
  background-color: #c61156;
  color: #FFF;
}

#tbl_fs_note_list tbody tr.selected:hover { 
  background-color: #c61156; color: #f5f5f5; 
}

/*#tbl_fs_note_list tbody tr.used_account:hover { 
  background-color:#f5f5f5; color: lightgrey; 
}*/

/*#tbl_fs_note_list tbody tr:hover { 
  background-color:#f5f5f5; color: black; 
}*/

#tbl_fs_note_list .used_account
{
  /*background-color:#f5f5f5; color: lightgrey;*/
  background-color:#f5f5f5;
  color: black;
}
</style>

<div style="height: 300px; overflow-y: auto;">
  <div class="form-group">
    <div class="col-xs-12">
      <input id="search_note" class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" type="search" style="float:right;" onkeyup="filter_note()">
    </div>
  </div>

  <table id="tbl_fs_note_list" class="table table-hover">
    <thead>
      <tr>
        <th style="width: 15%;">Note no.</th>
        <th style="width: 30%;">Note Section</th>
        <th>Selected in</th>
        <!-- <th style="width: 10%; text-align: center;">Inserted no.</th> -->
        <th style="text-align: center;">Action</th>
      </tr>
    </thead>
    <tbody>
     <!--  <tr>
        <td>Investment in Subsidiary</td>
        <td><button class="btn btn-primary">Select</button></td>
      </tr> -->
      
      <?php 
        // print_r($fs_ntfs_layout_template_list);
        // $note_no = 3;

        foreach($fs_note_list as $key => $value)
        {
          $matched = false;
          $list_matched = false;
          $all_selected_note_list_key = '';
          $note_no = '-';

          if($fs_statement_doc_type_id == 3)
          {
            $fntm_fnd_obj_name = 'fs_note_details_id';
          }
          else
          {
            $fntm_fnd_obj_name = 'fs_note_templates_master_id';
          }

          // if this is the current selected note, highlight the row.
          if($value[$fntm_fnd_obj_name] == $this_selected_fs_notes_templates_master_id)
          {
            $matched = true;
          }

          // if this note fs_note_templates_master_id is in the selected_note_list, grey out the row.
          if(in_array($value[$fntm_fnd_obj_name], $all_selected_note_list))
          {
            $all_selected_note_list_key = array_search($value[$fntm_fnd_obj_name], $all_selected_note_list, true);
            $list_matched = true;
          }

          /* ------ get note no. ------ */
          if(in_array($value['fs_ntfs_layout_template_default_id'], array_column($fs_ntfs_layout_template_list, 'fs_ntfs_layout_template_default_id')) && !in_array($value[$fntm_fnd_obj_name], $deleted_fs_note_templates_master_id))
          {
            $lyt_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_ntfs_layout_template_list, 'fs_ntfs_layout_template_default_id'), true);

            if(!empty($fs_ntfs_layout_template_list[$lyt_key]['note_no']))
            {
              $note_no = $fs_ntfs_layout_template_list[$lyt_key]['note_no'];
            }
          }

          /* ------ END OF get note no. ------ */

          if($matched)  // this selected note for the category
          {
            $fs_note_details_id = 0;

            if(!empty($fs_note_details_ids[$key]))
            {
              $fs_note_details_id = $fs_note_details_ids[$key];
            }

            $selected_item_using_note = [];

            foreach ($all_selected_note_list_data as $asnld_key => $asnld_value) 
            {
              if($value['fs_note_templates_master_id'] == $asnld_value['fs_note_templates_master_id'])
              {
                array_push($selected_item_using_note, $asnld_value);
              }
            }
            
            echo 
            '<tr>' .
              '<td bgcolor="#ffd2d1" style="color:black;" align="center">' . $note_no . '</td>' . 
              '<td bgcolor="#ffd2d1" style="color:black;">' . $value['default_name'] . '</td>' . 
              '<td bgcolor="#ffd2d1">
                <table class="table">
                  <thead>
                    <tr>
                      <th style="width: 30%;">Document</th>
                      <th style="width: 70%;">Category/item</th>
                    </tr>
                  </thead>
                  <tbody>';
                    foreach ($selected_item_using_note as $siun_key => $siun_value) 
                    {
                      echo '<tr>
                              <td>' . $siun_value['document_name'] . '</td>
                              <td>' . $siun_value['description'] . '</td>
                            </tr>';
                    }
            echo '</tbody>
                </table>
              </td>' .
              // '<td bgcolor="#ffd2d1"></td>' .
              '<td bgcolor="#ffd2d1" style="text-align:center; color:black;">
                <button class="btn btn-danger" onclick="remove_note(this, ' . $this_note_no . ')">Remove</button>
              </td>' . 
            '</tr>';

            // $note_no++;
          }
          elseif($list_matched) 
          {
            $items_using_note = [];

            $note_list_key = array_search($value['fs_note_templates_master_id'], array_column($all_selected_note_list_data, 'fs_note_templates_master_id'));

            // print_r(array($value['fs_note_templates_master_id']));

            foreach ($all_selected_note_list_data as $asnld_key => $asnld_value) 
            {
              if($value['fs_note_templates_master_id'] == $asnld_value['fs_note_templates_master_id'])
              {
                array_push($items_using_note, $asnld_value);
              }
            }

            if(!empty($items_using_note))
            {
              echo '<tr class="used_account">';
            }

            echo
              '<td align="center">' . $note_no . '</td>' .
              '<td>' . $value['default_name'] . '</td>' . 
              // '<td class="used_account" style="text-align:center;"><button class="btn btn-primary" onclick="show_note_layout(' . $value['fs_note_templates_master_id'] . ', ' . $fs_categorized_account_id . ')" disabled>Insert</button></td>' .

              '<td>';
                if(!empty($items_using_note))
                {
                  echo '<table class="table">
                          <thead>
                            <tr>
                              <th style="width: 30%;">Document</th>
                              <th style="width: 70%;">Category/item</th>
                            </tr>
                          </thead>
                          <tbody>';
                            foreach ($items_using_note as $iun_key => $iun_value) 
                            {
                              echo '<tr>
                                      <td>' . $iun_value['document_name'] . '</td>
                                      <td>' . $iun_value['description'] . '</td>
                                    </tr>';
                            }
                            
                  echo    '</tbody>
                        </table>';
                }
              '</td>';

              echo 
              // '<td style="text-align:center;">' . $all_selected_note_list_data[$note_list_key]['note_num_displayed'] . '</td>' .
              '<td style="text-align:center;">';

              if(isset($value['fs_note_details_id']))
              {
                echo '<input type="hidden" class="fs_note_details_id" value="' . $value['fs_note_details_id'] . '" />';
              }
              else
              {
                echo '<input type="hidden" class="fs_note_details_id" value="" />';
              }

              echo 
                '<input type="hidden" class="fs_ntfs_layout_template_default_id" value="' . $value['fs_ntfs_layout_template_default_id'] . '" />
                <input type="hidden" class="fs_note_templates_master_id" value="' . $value['fs_note_templates_master_id'] . '" />
                <button class="btn btn-primary" onclick="insert_note_number(this, ' . $this_note_no . ')">Insert</button>
              </td>' .  
            '</tr>';

            // $note_no++;
          }
          else
          {
            if($fs_statement_doc_type_id == 3)
            {
              $items_using_note = [];

              $note_list_key = array_search($value['fs_note_templates_master_id'], array_column($all_selected_note_list_data, 'fs_note_templates_master_id'));

              foreach ($all_selected_note_list_data as $asnld_key => $asnld_value) 
              {
                if($value['fs_note_templates_master_id'] == $asnld_value['fs_note_templates_master_id'])
                {
                  array_push($items_using_note, $asnld_value);
                }
              }

              echo 
                '<tr>' .
                  '<td align="center">' . $note_no . '</td>' .
                  '<td>' . $value['default_name'] . '</td>' . 
                  // '<td class="used_account" style="text-align:center;"><button class="btn btn-primary" onclick="show_note_layout(' . $value['fs_note_templates_master_id'] . ', ' . $fs_categorized_account_id . ')" disabled>Insert</button></td>' .

                  '<td>';
                    if(!empty($items_using_note))
                    {
                      echo '<table class="table">
                              <thead>
                                <tr>
                                  <th style="width: 30%;">Document</th>
                                  <th style="width: 70%;">Category/item</th>
                                </tr>
                              </thead>
                              <tbody>';
                                foreach ($items_using_note as $iun_key => $iun_value) 
                                {
                                  echo '<tr>
                                          <td>' . $iun_value['document_name'] . '</td>
                                          <td>' . $iun_value['description'] . '</td>
                                        </tr>';
                                }
                                
                      echo    '</tbody>
                            </table>';
                    }
                  '</td>';

                  echo 
                  // '<td style="text-align:center;">' . $all_selected_note_list_data[$note_list_key]['note_num_displayed'] . '</td>' .
                  '<td style="text-align:center;">';

                  if(isset($value['fs_note_details_id']))
                  {
                    echo '<input type="hidden" class="fs_note_details_id" value="' . $value['fs_note_details_id'] . '" />';
                  }
                  else
                  {
                    echo '<input type="hidden" class="fs_note_details_id" value="" />';
                  }

                  echo 
                    '<input type="hidden" class="fs_ntfs_layout_template_default_id" value="' . $value['fs_ntfs_layout_template_default_id'] . '" />
                    <input type="hidden" class="fs_note_templates_master_id" value="' . $value['fs_note_templates_master_id'] . '" />
                    <button class="btn btn-primary" onclick="insert_note_number(this, ' . $this_note_no . ')">Insert</button>
                  </td>' .  
                '</tr>';

                // $note_no++;
            }
            else
            {
              echo 
                '<tr>' .
                  '<td align="center">' . $note_no . '</td>' .
                  '<td>' . $value['default_name'] . '</td>' . 
                  // '<td style="text-align:center;"><button class="btn btn-primary" onclick="show_note_layout(' . $value['fs_note_templates_master_id'] . ', ' . $fs_categorized_account_id . ')">Insert</button></td>' . 
                  '<td></td>' .
                  // '<td></td>' .
                  '<td style="text-align:center;">';
                    if(isset($value['fs_note_details_id']))
                    {
                      echo '<input type="hidden" class="fs_note_details_id" value="' . $value['fs_note_details_id'] . '" />';
                    }
                    else
                    {
                      echo '<input type="hidden" class="fs_note_details_id" value="" />';
                    }
                    
                  echo 
                    '<input type="hidden" class="fs_ntfs_layout_template_default_id" value="' . $value['fs_ntfs_layout_template_default_id'] . '" />
                    <input type="hidden" class="fs_note_templates_master_id" value="' . $value['fs_note_templates_master_id'] . '" />
                    <button class="btn btn-primary" onclick="insert_note_number(this, ' . $this_note_no . ')">Insert</button>
                  </td>' . 
                '</tr>';
            }
          }
        }
      ?>

    </tbody>
  </table>
</div>

<script type="text/javascript">
  // to disable scrolling pop up on lower layer.
  $('#note_list').on("hidden.bs.modal", function (e) { 
      if ($('.modal:visible').length) { 
          $('body').addClass('modal-open');
      }
  });

  function filter_note()
  {
    var input, filter, table, tr, td, i, txtValue, txtValue1;
    input = document.getElementById("search_note");
    filter = input.value.toUpperCase();
    table = document.getElementById("tbl_fs_note_list");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      td1 = tr[i].getElementsByTagName("td")[1];

      // console.log(tr[i].getElementsByTagName("td"));

      if (td || td1) {
        txtValue = td.textContent || td.innerText;
        txtValue1 = td1.textContent || td1.innerText;

        if ((txtValue.toUpperCase().indexOf(filter) > -1) || (txtValue1.toUpperCase().indexOf(filter) > -1)) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
  }
</script>

<!-- <script src="themes/default/assets/js/financial_statement/fs_notes.js" charset="utf-8"></script> -->

<!-- <script type="text/javascript">
  $("#tbl_sub_account_list tbody tr").click(function(){
    
    if($(this).hasClass("not_used_account"))
    {
      $(this).addClass('selected').siblings().removeClass('selected');    
      var value=$(this).find('td:first').html();
    }
  });

  // $('#sub_account_list').on('show.bs.modal', function () {
  //   $('.modal .modal-body').css('overflow-y', 'auto'); 
  //   $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
  // });
</script> -->