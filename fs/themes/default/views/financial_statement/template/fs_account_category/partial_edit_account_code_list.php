<style type="text/css">
#tbl_edit_sub_account_list .not_used_account.selected, #tbl_edit_sub_account_list .used_account.selected {
  background-color: #c61156;
  color: #FFF;
}

/*#tbl_edit_sub_account_list tbody tr.selected:hover, #tbl_edit_sub_account_list tbody tr.selected:hover { 
  background-color: #c61156; color: #f5f5f5; 
}*/

#tbl_edit_sub_account_list tbody tr.selected { 
  background-color: #c61156; color: #f5f5f5; 
}

#tbl_edit_sub_account_list .used_account { 
  background-color: #f5f5f5; 
}

</style>

<div id="partial_edit_sub_account_list">
  <div id="psal_edit_sub_account_list" class="form-group" style="height: 300px; overflow-y: auto;">

    <div class="form-group">
      <div class="col-xs-12">
        <input id="search_sub_acc" class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" type="search" style="float:right;" onkeyup="filter_account()">
      </div>
    </div>
    <div class="form-group">
      <div class="col-xs-12">
        <table id="tbl_edit_sub_account_list" class="table">
          <thead>
            <tr>
              <th>Reference ID</th>
              <th>Description</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php 
            foreach($sub_account_list as $key => $value) 
            { 
              if($selected_acc_code == $value['account_code'])
              {
                if($value['is_used'] == 1)
                {
                  echo '<tr class="selected used_account" ondblclick="edit_account_code(\'' . $value["account_code"] . '\', \'' . $value["fs_default_acc_category_id"] . '\')" style="cursor: pointer;">';
                }
                else
                {
                  echo '<tr class="selected" ondblclick="edit_account_code(\'' . $value["account_code"] . '\', \'' . $value["fs_default_acc_category_id"] . '\')" style="cursor: pointer;">';
                }
              }
              elseif($value['is_used'] == 1) 
              {
                echo '<tr class="used_account init" ondblclick="edit_account_code(\'' . $value["account_code"] . '\', \'' . $value["fs_default_acc_category_id"] . '\')" style="cursor: pointer;">';
              } 
              else { 
            ?>
              <tr class="not_used_account" ondblclick="edit_account_code('<?php echo $value['account_code']?>', '<?php echo $value['fs_default_acc_category_id']?>')" style="cursor: pointer;">
            <?php } ?>
                <input type="hidden" class="fs_default_acc_category_id" value="<?php echo $value['fs_default_acc_category_id'];?>"/>
                <td class="account_code"><?php echo $value['account_code']; ?></td>
                <td><?php echo $value['description']; ?></td>

              <?php
                if($selected_acc_code == $value['account_code'])
                {
                  echo '<td><button class="btn btn-danger" onclick="edit_account_code(\'' . "" . '\', \'' . "". '\')">Remove</button></td>';
                }
                else
                {
                  echo '<td></td>';
                }
              ?>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $("#tbl_edit_sub_account_list tbody tr").click(function()
  {
    $(this).addClass('selected').siblings().removeClass('selected');
    var value=$(this).find('td:first').html();
  });

  function filter_account()
  {
    var input, filter, table, tr, td, i, txtValue, txtValue1;
    input = document.getElementById("search_sub_acc");
    filter = input.value.toUpperCase();
    table = document.getElementById("tbl_edit_sub_account_list");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      td1 = tr[i].getElementsByTagName("td")[1];

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