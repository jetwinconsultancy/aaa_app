<style type="text/css">
.not_used_account.selected, .used_account.selected {
  background-color: #c61156;
  color: #FFF;
}

#tbl_sub_account_list tbody tr.selected:hover, #tbl_sub_account_list tbody tr.selected:hover { 
  background-color: #c61156; color: #f5f5f5; 
  /*background-color:#c61156; color: black;*/
}

#tbl_sub_account_list .used_account { 
  background-color: #f5f5f5; 
}

/*#tbl_sub_account_list tbody tr.used_account:hover { */
  /*background-color:#f5f5f5; color: lightgrey; */
  /*background-color:#f5f5f5; color: black;*/
/*}*/

/*#tbl_sub_account_list tbody tr:hover { 
  background-color:#f5f5f5; color: black; 
}*/

/*#tbl_sub_account_list .init
{
  background-color:#f5f5f5; color: black;
}*/
</style>

<div id="partial_sub_account_list">
  <div class="form-group">
    <div class="col-xs-9">
      <!-- <input type="text" class="form-control" id="input_new_description" name="new_description" placeholder="New Description Name" onkeypress="change_result_msg()"> -->
      <input type="text" class="form-control" id="input_new_description" name="new_description" placeholder="New Description Name">
    </div>
      <!-- <button class="btn btn-primary" onclick="createNewCategory()">Create</button> -->
  </div>
  <!-- <p class="result_msg"></p> -->
  <!-- <p class="result_msg">Input account name to insert new account.</p> -->

  <!-- <br/> -->

  <!-- <p>Select an account to insert to sub category.</p> -->

  <div class="form-group">
    <label class="col-xs-3">Link with Reference ID</label>
    <div class="col-xs-9">
        <div class="input-group" style="width: 200px;" >
            <input type="checkbox" name="checkbox_link_with_ref_id"/>
            <input type="hidden" id="link_with_ref_id_val" value="0"/>
        </div>
    </div>
  </div>

  <div id="psal_sub_account_list" class="form-group" style="height: 300px; overflow-y: auto; display: none;">
    <hr/>

    <div class="form-group">
      <div class="col-xs-12">
        <input id="search_sub_acc" class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" type="search" style="float:right;" onkeyup="filter_account()">
      </div>
    </div>
    <div class="form-group">
      <div class="col-xs-12">
        <table id="tbl_sub_account_list" class="table">
          <thead>
            <tr>
              <th>Reference ID</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($sub_account_list as $key => $value) { 
              if($value['is_used']) {
                // echo '<tr class="used_account" style="cursor: not-allowed;">';
                echo '<tr class="used_account init" ondblclick="select_sub(' . $value["account_code"] . ', ' . $value["description"] . ')" style="cursor: pointer;">';
              } else { 
            ?>
              <tr class="not_used_account" ondblclick="select_sub('<?php echo $value['account_code']?>', '<?php echo $value['description']?>')" style="cursor: pointer;">
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
</div>

<script type="text/javascript">
  $('#sub_account_list .modal-body #partial_sub_account_list #input_new_description').focus();

  $("#tbl_sub_account_list tbody tr").click(function(){
    
    // console.log($(this).addClass('selected'));

    // if($(this).hasClass("not_used_account") )
    // {
      $(this).addClass('selected').siblings().removeClass('selected');
      var value=$(this).find('td:first').html();
    // }
  });

  // $('#sub_account_list').on('show.bs.modal', function () {
  //   $('.modal .modal-body').css('overflow-y', 'auto'); 
  //   $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
  // });

  $("[name='checkbox_link_with_ref_id']").bootstrapSwitch({
      state: 0,
      size: 'small',
      onColor: 'primary',
      onText: 'YES',
      offText: 'NO',
      // Text of the center handle of the switch
      labelText: '&nbsp',
      // Width of the left and right sides in pixels
      handleWidth: '45px',
      // Width of the center handle in pixels
      labelWidth: 'auto',
      baseClass: 'bootstrap-switch',
      wrapperClass: 'wrapper'
  });

  // Triggered on switch state change.
  $("[name='checkbox_link_with_ref_id']").on('switchChange.bootstrapSwitch', function(event, state) {
      // var hidden_val = $(event.target).parent().parent().parent().find("[name='change_com_name_checkbox']");
      var link_with_ref_id_val = $("#link_with_ref_id_val");
      var psal_sub_account_list_section = $("#psal_sub_account_list");

      if(state)
      {
          link_with_ref_id_val.val(1);
          psal_sub_account_list_section.show();
      }
      else
      {
          link_with_ref_id_val.val(0);
          psal_sub_account_list_section.hide();
      }
  });

  $('#partial_sub_account_list #input_new_description').keypress(function (e) 
  {
      var key = e.which;

      if(key == 13)  // the enter key code
      {
          select_sub('', '');
      }
  });

  function filter_account()
  {
    var input, filter, table, tr, td, i, txtValue, txtValue1;
    input = document.getElementById("search_sub_acc");
    filter = input.value.toUpperCase();
    table = document.getElementById("tbl_sub_account_list");
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