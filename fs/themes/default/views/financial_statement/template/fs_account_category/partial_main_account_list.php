<style type="text/css">
.not_used_account.selected {
  background-color: #c61156;
  color: #FFF;
}

#tbl_main_account_list tbody tr.selected:hover { 
  background-color: #c61156; color: #f5f5f5; 
}

#tbl_main_account_list tbody tr.used_account:hover { 
  background-color:#f5f5f5; color: lightgrey; 
}

#tbl_main_account_list tbody tr:hover { 
  background-color:#f5f5f5; color: black; 
}

.used_account
{
  background-color:#f5f5f5; color: lightgrey;
}
</style>

<div style="height: 300px; overflow-y: auto;">
  <div class="form-group">
    <div class="col-xs-12">
      <p>Select a main account to insert.</p>
      <input id="search_main_acc" class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" type="search" style="float:right;" onkeyup="filter_main_account()">
    </div>
  </div>

  <div class="form-group">
    <div class="col-xs-12">
      <table id="tbl_main_account_list" class="table">
        <thead>
          <tr>
            <th>Reference ID</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($main_account_list as $key => $value) { 
            if($value['is_used']) {
              echo '<tr class="used_account" style="cursor: not-allowed;">';
            } else { 
          ?>
            <tr class="not_used_account" ondblclick="select_main('<?php echo $value['account_code']?>', '<?php echo $value['description']?>')" style="cursor: pointer;">
          <?php } ?>
              <input type="hidden" class="fs_default_acc_category_id" value="<?php echo $value['fs_default_acc_category_id'];?>"/>
              <td><?php echo $value['account_code']; ?></td>
              <td><?php echo $value['description']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">
  $("#tbl_main_account_list tbody tr").click(function(){
    // console.log($(this).hasClass("not_used_account"));

    if($(this).hasClass("not_used_account"))
    {
      $(this).addClass('selected').siblings().removeClass('selected');    
      var value=$(this).find('td:first').html();
    }
  });

  function filter_main_account()
  {
    var input, filter, table, tr, td, i, txtValue, txtValue1;
    input = document.getElementById("search_main_acc");
    filter = input.value.toUpperCase();
    table = document.getElementById("tbl_main_account_list");
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