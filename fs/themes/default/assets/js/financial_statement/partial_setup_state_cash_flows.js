$(document).ready(function() {
    table.api().columns.adjust().draw();
});

function initDataTable() 
{
  table = $('#tbl_setup_cfs').dataTable( {
              scrollY       : '50vh',
              scrollX       : true,
              scrollCollapse: true,
              scroller      : true,
              paging        : false,
              ordering      : false,
              searching     : false, 
              bInfo         : false,
              // autoWidth     : false,
              fixedHeader: {
                header: true,
                headerOffset: 45,
              },
              fixedColumns  :   {
                  leftColumns: 4,
                  rightColumns: 1
              },
              bJQueryUI     : true,
              sDom          : 'l<"H"Rf>t<"F"ip>',
              // rowReorder: true
              // createdRow: function(row, data, index)
              // {
              //   console.log(data['DT_RowId']);
              //   console.log(row);
              // }
          } );
}

$('#form_fs_setup_cfs').contextmenu({
  delegate: ".dataTable td",
  menu: [
    {title: "Edit", cmd: "editCell", uiIcon: "ui-icon-pencil"},
    {title: "Set adjustment value", cmd: "setAdjVal", uiIcon: "ui-icon-pin-s"},
    {title: "Remove adjustment value", cmd: "removeAdjVal", uiIcon: "ui-icon-pin-w"}
    // {title: "Filter", cmd: "filter", uiIcon: "ui-icon-volume-off ui-icon-filter"},
    // {title: "Remove filter", cmd: "nofilter", uiIcon: "ui-icon-volume-off ui-icon-filter"}
  ],
  select: function(event, ui) {
    var td = ui.target;
    var tr = td.closest('tr');
    var col_index = td.parent().children().index(td);
    var row_index = td.parent().parent().children().index(td.parent());

    switch(ui.cmd){
      // edit cell
      case "editCell":
        EditCellValue(td, col_index, row_index);
        break;

      // set adjustment values  
      case "setAdjVal":
        td.css("background-color", "lightgrey");
        td.find('.set_adj_val').val(1);
        td.addClass('set_adj_val_td'); // use it to know if the column has a adjustment value. 
        insert_adj_val(td, col_index, row_index);
        break;

      case "removeAdjVal":
        td.css("background-color", "");
        td.find('.set_adj_val').val(0);
        td.removeClass('set_adj_val_td'); // use it to know if the column has a adjustment value.
        break;
    }
  },
  beforeOpen: function(event, ui) {
    var td = ui.target;
    var tr = td.closest('tr');
    var col_index = td.parent().children().index(td);
    var row_index = td.parent().parent().children().index(td.parent());

    var pinned_set_adj_val   = false;
    var col_contains_adj_val = false;

    $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (col_index+1) +')').each(function(index, element)
    {
      if($(element).hasClass('set_adj_val_td'))
      {
        col_contains_adj_val = true;
      }
    });

    if(col_index > 3 && !tr.hasClass('no_edit'))
    {
      var $menu = ui.menu,
      $target = ui.target,
      extraData = ui.extraData;

      if(td.hasClass('set_adj_val_td'))
      {
        pinned_set_adj_val = true;
      }

      // disable edit if td is set as adjustment value
      if(td.hasClass('set_adj_val_td'))
      {
        $('#form_fs_setup_cfs').contextmenu("showEntry", "editCell", false);
      }
      else
      {
        $('#form_fs_setup_cfs').contextmenu("showEntry", "editCell", true);
      }

      // enable 'Set Adjustment value' / 'Remove Adjustment value'
      for (var i = 0; i < $menu[0].childNodes.length; i++) 
      {
        if(!col_contains_adj_val) // if all cells in this column does not contains 'Adjustment value'
        {
          if(!pinned_set_adj_val) // if the cell is set as 'Adjustment value'
          {
            if($menu[0].childNodes[i].dataset.command == 'removeAdjVal')
            {
              // remove 'Remove adjustment value' option
              $('#form_fs_setup_cfs').contextmenu("showEntry", "removeAdjVal", false);
              $('#form_fs_setup_cfs').contextmenu("showEntry", "setAdjVal", true);
            }
          }
          else
          {
            if($menu[0].childNodes[i].dataset.command == 'setAdjVal')
            {
              // remove 'Set adjustment value' option
              $('#form_fs_setup_cfs').contextmenu("showEntry", "setAdjVal", false);
              $('#form_fs_setup_cfs').contextmenu("showEntry", "removeAdjVal", true);
            }
          }
        }
        else
        {
          if(pinned_set_adj_val) // if the selected cell is set as 'Adjustment value'
          {
            // remove 'Set adjustment value' option
              $('#form_fs_setup_cfs').contextmenu("showEntry", "setAdjVal", false);
              $('#form_fs_setup_cfs').contextmenu("showEntry", "removeAdjVal", true);
          }
          else
          {
            $('#form_fs_setup_cfs').contextmenu("showEntry", "removeAdjVal", false);
            $('#form_fs_setup_cfs').contextmenu("showEntry", "setAdjVal", false);
          }
        }
      }

      $menu.zIndex(9999);
    }
    else
    {
      return false;
    }
  }
});

// for checkbox part
change_cbx_status($('#tbl_setup_cfs .cbx_operating_act')[0], 'opt', 0);
change_cbx_status($('#tbl_setup_cfs .cbx_investing_act')[0], 'inv', 0);
change_cbx_status($('#tbl_setup_cfs .cbx_financing_act')[0], 'fin', 0);

if (!$.fn.dataTable.isDataTable('#tbl_setup_cfs') ) {
  initDataTable();
  autoCalculateTable();
}

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust()
        .fixedColumns().relayout();
});

$('#form_fs_setup_cfs tbody').on('dblclick', 'td', function () {
  var tr = $(this).closest('tr');
  var row_index = tr.index();
  var col_index = $(this).parent().children().index($(this));

  if(col_index > 1 && row_index > 2 && !tr.hasClass("no_edit") && col_index != 3)
  {
    if(!(tr.hasClass("setup_pl_b4_tax") && col_index < 4))
    {
      EditCellValue($(this), col_index, row_index);
    }
  }
  else
  {
    return false;
  }
});

function autoCalculateTable()
{
  var check_val_col = [];

  /* ------------ Load data by row (Calculate by row) ------------ */
  $('#form_fs_setup_cfs #tbl_setup_cfs tbody tr').each(function(row_index, element)
  {
    var actual_amount = 0;
    var total_by_row = 0;

    if(!$(element).hasClass('no_edit'))
    {
      if(row_index > 2)
      {
        // load td (by column)
        $(element).find('td').each(function(col_index, element1)
        {
          if(col_index == 3)
          {
            actual_amount = negative_bracket_to_number($(element1).text());
          }
          else if(!$(element1).hasClass('check_by_row'))
          {
            total_by_row += negative_bracket_to_number($(element1).text());
          }
        });
      }
    }

    if(!($(element).hasClass('no_edit') && !$(element).hasClass('setup_pl_b4_tax')))
    {
      // write value in last column (check value by row)
      var diff_vals_by_row =  total_by_row - actual_amount;
      $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').text(negative_bracket_js(diff_vals_by_row));
      $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').text(negative_bracket_js(diff_vals_by_row));

      if(diff_vals_by_row != 0)
      {
        $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "red");
        $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "black");

        // clone td (fixed right column 1)
        $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "red");
        $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "black");
      }
      else
      {
        $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "");
        $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "");

        // clone td (fixed right column 1)
        $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "");
        $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "");
      }
    }
  });
  /* ------------ END OF Load data by row (Calculate by row) ------------ */

  /* ------------ Load data by row (Calculate by column) ------------ */
  var column_length = $('#tbl_setup_cfs tr:nth-child(1) td').length;

  for (var i = 4; i < column_length; i++) 
  {
    var total_by_column  = 0;
    var diff_vals        = 0;
    var start_taking_val = 0;

    $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (i+1) +')').each(function(index, element)
    {
      if($(element).closest('tr').hasClass('diff_vals'))
      {
        start_taking_val = 1;
      }

      if(start_taking_val && index !== $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (i+1) +')').length - 1 && !$(element).hasClass('no_edit'))
      {
        if(start_taking_val)
        {
          if($(element).closest('tr').hasClass('diff_vals'))
          {
            diff_vals = negative_bracket_to_number($(element).text());
          }
          else
          {
            total_by_column += negative_bracket_to_number($(element).text());
          }
        }
      }
    });

    // write calculated checking value in last row
    $('#tbl_setup_cfs .check_val_col td:nth-child('+ (i+1) +')').text(negative_bracket_js(diff_vals + total_by_column));

    if((diff_vals + total_by_column) != 0)
    {
      $('#tbl_setup_cfs .check_val_col td:nth-child('+ (i+1) +')').css("background-color", "red");
      $('#tbl_setup_cfs .check_val_col td:nth-child('+ (i+1) +')').css("color", "black");
    }
    else
    {
      $('#tbl_setup_cfs .check_val_col td:nth-child('+ (i+1) +')').css("background-color", "");
      $('#tbl_setup_cfs .check_val_col td:nth-child('+ (i+1) +')').css("color", "");
    }
  }
  /* ------------ END OF Load data by row (Calculate by column) ------------ */

  autoCalculateByPart(); // Calculate total of net cash
}

function autoCalculateByPart()
{
  /* Calculate total of net cash */
  var start_taking_val = 0;
  var total = { 
              'net_cash_frm_opt': 0,
              'opt_net_cash' : 0, 
              'inv_net_cash' : 0,
              'fin_net_cash' : 0
            };

  $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child(4)').each(function(index, element)
  {
    if($(element).closest('tr').hasClass('diff_vals'))
    {
      start_taking_val = 1;
    }

    if(start_taking_val && index !== $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child(4)').length - 1 && !$(element).hasClass('no_edit'))
    {
      if($(element).closest('tr').hasClass("opt_act_group") || $(element).closest('tr').hasClass("setup_pl_b4_tax"))
      {
        if($(element).closest('tr').hasClass('net_cash_frm_opt')) // Net Cash from Operation
        {
          total['net_cash_frm_opt'] = total['opt_net_cash'];
        }
        else
        {
          total['opt_net_cash'] +=  negative_bracket_to_number($(element).text());
        }
      }
      else if($(element).closest('tr').hasClass("inv_act_group"))
      {
        total['inv_net_cash'] +=  negative_bracket_to_number($(element).text());
      }
      else if($(element).closest('tr').hasClass("fin_act_group"))
      {
        total['fin_net_cash'] +=  negative_bracket_to_number($(element).text());
      }
    }
  });

  // write the values in all tables 
  $('#form_fs_setup_cfs table tbody td:nth-child(4)').each(function(index, element)
  {
    if($(element).closest('tr').hasClass("opt_net_cash"))
    {
      $(element).text(negative_bracket_js(total['opt_net_cash']));
    }
    else if($(element).closest('tr').hasClass("inv_net_cash"))
    {
      $(element).text(negative_bracket_js(total['inv_net_cash']));
    }
    else if($(element).closest('tr').hasClass("fin_net_cash"))
    {
      $(element).text(negative_bracket_js(total['fin_net_cash']));
    }
    else if($(element).closest('tr').hasClass('net_cash_frm_opt'))
    {
      $(element).text(negative_bracket_js(total['net_cash_frm_opt']));
    }
  });
  /* END OF Calculate total of net cash */
}

function insert_adj_val(td, col_index, row_index)
{
  // get this diff value 
  var total_by_column = 0;
  var diff_vals = $('#tbl_setup_cfs .diff_vals td:nth-child('+ (col_index+1) +')').text();
  diff_vals = negative_bracket_to_number(diff_vals);

  $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (col_index+1) +')').each(function(index, element)
  {
    if(index > 2 && index !== $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (col_index+1) +')').length - 1 && index != row_index)
    {
      total_by_column += negative_bracket_to_number($(element).text());
    }
  });

  var set_adj_val = (diff_vals + total_by_column) * (-1);

  td.html('<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="1"><input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' + set_adj_val + '">' + negative_bracket_js(set_adj_val));
  table.api().cell(td).data(td.html()).draw();

  if(col_index > 2)
  {
    autoCalculateByRowCol(td, col_index, row_index);
    autoCalculateByPart(); // Calculate total of net cash
  }
}

function autoCalculateByRowCol(td, col_index, row_index)
{
  auto_deduct_adj_val(td, col_index, row_index); // update adjustment value first

  var total = 0;

  // check by row
  td.closest('tr').find('td').each(function(index, element)
  {
    if(index > 3 && !$(element).hasClass('check_by_row'))
    {
      total += negative_bracket_to_number($(element).text());
    }
  });

  $('#form_fs_setup_cfs table tbody').each(function(index, element)
  {
    var found_tr_by_index = $(element).find('tr:eq(' + row_index + ')');
    var found_td          = found_tr_by_index.find('td:nth-child(4)');

    if(!found_tr_by_index.hasClass("setup_pl_b4_tax"))
    {
      // write total in 4th column
      found_td.html('<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="' + total + '">' + negative_bracket_js(total));
    }
  });

  // write calculated checking value in last column
  var actual_amount = 0;
  var total_by_row = 0;

  $('#form_fs_setup_cfs #tbl_setup_cfs tr:eq(' + (row_index+1) + ') td').each(function(index, element)
  {
    var temp_value = negative_bracket_to_number($(element).text());

    if(index == 3)
    {
      actual_amount = temp_value;
    }
    else if(index !==  $('#form_fs_setup_cfs #tbl_setup_cfs tr:eq(' + (row_index+1) + ') td').length-1)
    {
      total_by_row += temp_value;
    }
  });

  var diff_vals_by_row =  total_by_row - actual_amount;
  $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').text(negative_bracket_js(diff_vals_by_row));
  $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').text(negative_bracket_js(diff_vals_by_row));

  if(diff_vals_by_row != 0)
  {
    $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "red");
    $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "black");

    // clone td (fixed right column 1)
    $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "red");
    $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "black");
  }
  else
  {
    $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "");
    $('#form_fs_setup_cfs table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "");

    // clone td (fixed right column 1)
    $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("background-color", "");
    $('#form_fs_setup_cfs .DTFC_RightWrapper table tbody tr:eq(' + (row_index) + ') td:last-child').css("color", "");
  }

  // check by column
  var diff_vals = $('#tbl_setup_cfs .diff_vals td:nth-child('+ (col_index+1) +')').text();
  diff_vals = negative_bracket_to_number(diff_vals);

  var total_by_column = 0;

  $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (col_index+1) +')').each(function(index, element)
  {
    if(index > 2 && index !== $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (col_index+1) +')').length - 1)
    {
      total_by_column += negative_bracket_to_number($(element).text());
    }
  });

  // write calculated checking value in last row
  $('#tbl_setup_cfs .check_val_col td:nth-child('+ (col_index+1) +')').text(negative_bracket_js(diff_vals + total_by_column));

  if((diff_vals + total_by_column) != 0)
  {
    $('#tbl_setup_cfs .check_val_col td:nth-child('+ (col_index+1) +')').css("background-color", "red");
    $('#tbl_setup_cfs .check_val_col td:nth-child('+ (col_index+1) +')').css("color", "black");
  }
  else
  {
    $('#tbl_setup_cfs .check_val_col td:nth-child('+ (col_index+1) +')').css("background-color", "");
    $('#tbl_setup_cfs .check_val_col td:nth-child('+ (col_index+1) +')').css("color", "");
  }
}

function auto_deduct_adj_val(td, col_index, row_index)
{
  var diff_vals = '';
  var total_by_column = 0;
  var adj_val_info = { value: '', col_index: '', row_index: '' }; // to update adjustment value

  $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (col_index+1) +')').each(function(index, element)
  {
    if(($(element).parent()).hasClass('diff_vals'))
    {
      diff_vals = negative_bracket_to_number($(element).text());
    }
    else
    {
      if(index > 2 && index !== $('#form_fs_setup_cfs #tbl_setup_cfs tbody td:nth-child('+ (col_index+1) +')').length - 1)
      {
        if($(element).hasClass('set_adj_val_td'))
        {
          adj_val_info['value'] = negative_bracket_to_number($(element).text());
          adj_val_info['col_index'] = col_index + 1;
          adj_val_info['row_index'] = index;
        }
        else
        {
          total_by_column += negative_bracket_to_number($(element).text());
        }
      }
    }
  });

  // calculate updated adjustment value
  var updated_adj_val = (diff_vals*-1) - total_by_column;

  if(!(adj_val_info['col_index'] == '' && adj_val_info['row_index'] == ''))
  {
    // update adjustment value
    $('#form_fs_setup_cfs table tbody td:nth-child('+ (adj_val_info['col_index']) +')').each(function(index, element)
    {
      if(index == adj_val_info['row_index'])
      {
        $(element).html('<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="1"><input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="' + updated_adj_val + '">' + negative_bracket_js(updated_adj_val));
      }
    });

    // update total in column 4
    var total_adj_row_col_4 = 0;

    // calculate total by row
    $('#tbl_setup_cfs tbody tr:nth-child(' + (adj_val_info['row_index']+1) + ') td').each(function(index, element)
    {
      if(index > 3)
      {
        total_adj_row_col_4 += negative_bracket_to_number($(element).text());
      }
    });

    // write new value
    $('#form_fs_setup_cfs table tbody tr:nth-child(' + (adj_val_info['row_index']+1) + ') td:nth-child(4)').each(function(index, element)
    {
      $(element).html('<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="'+ total_adj_row_col_4 +'">' + negative_bracket_js(total_adj_row_col_4));
    });
  }
}

function EditCellValue(td, col_index, row_index) 
{
    if(td.hasClass('set_adj_val_td')){return false;}

    var value   = td.html();
    var match_class = [...value.matchAll(/<input.*?>/g)];

    var input_class = 'dyn_val';
    var input_name = 'setup_cfs_dyn_val[]';

    // get input type
    if(match_class !== null)
    {
      if(match_class.length == 1)
      {
        input_class = 'description';
        input_name  = 'setup_cfs_description[]';

        value = match_class[0][0];
      }
      else if(match_class.length == 2)
      {
        value = match_class[1][0];
      }
    }

    // get input value
    var match_value = value.match(/value="(.*?)"/);

    if(match_value !== null)
    {
      if(match_value.length > 1)
      {
        value = match_value[1];
      }
    }

    var text_align  = '';
    var newcont     = '';
    var nb_newcont  = '';
    var set_adj_val = 0;

    if(col_index > 2)
    {
      text_align = 'text-align:right;';

      value = negative_bracket_to_number(value);
      set_adj_val = td.find('.set_adj_val').val();

      // create temporary input field
      $(td).html('<input id="newcont" style="width:100%; border-width:0px; border:none; ' + text_align + '" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1" value="' + value + '">');
    }
    else
    {
      // create temporary input field
      $(td).html('<input id="newcont" style="width:100%; border-width:0px; border:none; ' + text_align + '" value="' + value + '">');
    }

    $("#newcont").val(value);
    $("#newcont").focus();
    
    $("#newcont").focus(function() {
        // console.log('in');
    }).blur(function() {
        newcont    = $("#newcont").val();
        nb_newcont = newcont;

        if(col_index > 2)
        {
          nb_newcont = negative_bracket_js(newcont);
          newcont    = negative_bracket_to_number(newcont);

          // for dynamic input
          td.html('<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="' + set_adj_val + '"><input type="hidden" class="' + input_class + '" name="' + input_name + '" value="' + newcont + '">' + nb_newcont);
        }
        else
        {
          // for description part
          td.html('<input type="hidden" class="' + input_class + '" name="' + input_name + '" value="' + newcont + '">' + nb_newcont);
        }
        
        table.api().cell(td).data(td.html()).draw();

        if(col_index > 2)
        {
          autoCalculateByRowCol(td, col_index, row_index);
          autoCalculateByPart(); // Calculate total of net cash
        }
    });
}

function setup_cfs_add_row(data, category, parent, element)
{
  
  var tr = $(element).closest('tr');
  var tr_index = tr.index();
  var arr_data = [];

  arr_data.push('<input type="hidden" class="body_id" name="setup_cfs_id[]" value=""><input type="hidden" name="setup_cfs_parent_id[]" value="' + parent + '"><input type="hidden" name="setup_cfs_category_id[]" value="' + category + '">');
  arr_data.push('<input type="hidden" name="setup_cfs_is_checked[]" value="1"><a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="setup_cfs_delete_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>');

  if(data == '')
  {
    arr_data.push('<input type="hidden" class="description" name="setup_cfs_description[]" value="">');
    arr_data.push('<input type="hidden" class="main_val" name="setup_cfs_main_val[]" value="0">-');

    for (var i = 0; i < cfs_header_count; i++) 
    {
      arr_data.push('<input type="hidden" class="set_adj_val" name="setup_cfs_is_adj_val[]" value="0"><input type="hidden" class="input_values" name="setup_cfs_dyn_val[]" value="0">-');
    }
  }

  // // $('#tbl_setup_cfs').dataTable().fnAddData(arr_data, 12);
  // var currentPage = table.page();

  // table.row.add(arr_data).draw();

  // //move added row to desired index (here the row we clicked on)
  // var index = table.row(element).index(),
  //     rowCount = table.data().length-1,
  //     insertedRow = table.row(rowCount).data(),
  //     tempRow;

  // for (var i=rowCount;i>index+1;i--) 
  // {
  //     tempRow = table.row(i-1).data();

  //     // swap with previous row
  //     table.row(i).data(tempRow);
  //     table.row(i-1).data(insertedRow);

  //     // var tr_i = $('#tbl_setup_cfs').find('tr').eq(i);
  //     // var tr_added = $('#tbl_setup_cfs').find('tr').eq(i-1);
  // }

  // //refresh the current page
  // table.page(currentPage).draw(false);

  // -----------------------------------------------------------

  // setup class name for hide row later.
  var className = '';

  if(category == 1)
  {
    className = 'opt_act_group';
  }
  else if(category == 2)
  {
    className = 'inv_act_group';
  }
  else if(category == 3)
  {
    className = 'fin_act_group';
  }

  var newRow = '<tr class="' + className + '">';

  for (var i = 0; i < arr_data.length ; i++) 
  {
    var right_align = 'style="text-align:right;"';

    if(i < 3)
    {
      right_align = '';
    }

    newRow = newRow + '<td ' + right_align + '>' + arr_data[i] + '</td>';
  }

  newRow += '<td class="check_by_row" style="text-align:right;">-</td>';

  newRow = newRow + '</tr>';

  table.fnDestroy();
   $("#tbl_setup_cfs tbody tr").eq(tr_index).after(newRow);

  initDataTable();
  setup_checked_current_cbx(); // set current changes values on checkbox for the 3 main activities

  // table.api().row(tr_index).scrollTo();
}

function setup_checked_current_cbx() // checked back checkbox values for the 3 main activities (operating, investing, financing)
{
  var checked_opt = false, 
      checked_inv = false, 
      checked_fin = false;

  // console.log($('#form_fs_setup_cfs .cbx_opt_act_changes'));

  // console.log(opt_act_changes_cbx);

  // operating activities
  if(check_operating_act == 1)
  {
    checked_opt = true;
  }
  $('#form_fs_setup_cfs .cbx_operating_act').prop("checked", checked_opt);

  // investing activities
  if(check_investing_act == 1)
  {
    checked_inv = true;
  }
  $('#form_fs_setup_cfs .cbx_investing_act').prop("checked", checked_inv);

  // financing activities
  if(check_financing_act == 1)
  {
    checked_fin = true;
  }
  $('#form_fs_setup_cfs .cbx_financing_act').prop("checked", checked_fin);

  // for changes in working capital
  var count_changes = 0;

  $('#form_fs_setup_cfs .cbx_opt_act_changes').each(function(index, element)
  { 
    $(element).val(opt_act_changes_cbx[count_changes]['is_checked']);

    var checkbox_opt_act_changes = $(element).closest('tr').find('.checkbox_opt_act_changes');

    if(opt_act_changes_cbx[count_changes]['is_checked'] == "1")
    {
      checkbox_opt_act_changes.prop("checked", true);
    }
    else
    {
      checkbox_opt_act_changes.prop("checked", false);
    }

    // for counter
    if(count_changes < opt_act_changes_cbx.length - 1)
    {
      count_changes++;
    }
    else
    {
      count_changes = 0;
    }
  });
}

function setup_cfs_delete_row(element)
{
  table.api().row($(element).parents('tr')).remove().draw();

  setup_checked_current_cbx();
}

// when user click on the checkbox
function change_cbx_status(element, part, arr_index)
{
  // console.log(part);

  var tr = $(element).closest('tr');
  var checked_val = 0;
  var row_index = $(element).closest('tr').index();
  var col_index = $(element).closest('tr').children().index($(element).closest('td'));
  var innerHTML = '';

  if(part == "opt_act_changes")
  {
    if($(element).prop("checked") == false)
    {
      $(element).prop("checked", false);
      tr.find('.cbx_opt_act_changes').val(0);

      opt_act_changes_cbx[arr_index]['is_checked'] = '0';
    }
    else
    {
      $(element).prop("checked", true);
      tr.find('.cbx_opt_act_changes').val(1);

      opt_act_changes_cbx[arr_index]['is_checked'] = '1';
    }
  }
  else
  {
    if($(element).prop("checked") == false)
    {
      $('#form_fs_setup_cfs .' + part + '_act_group').hide();
      $('#form_fs_setup_cfs .' + part + '_net_cash').hide();

      $(element).prop("checked", false);
      $('#form_fs_setup_cfs .cbx_status_' + part).val(0);

      checked_val = 0;
    }
    else
    {
      $('#form_fs_setup_cfs .' + part + '_act_group').show();
      $('#form_fs_setup_cfs .' + part + '_net_cash').show();
      
      $(element).prop("checked", true);
      $('#form_fs_setup_cfs .cbx_status_' + part).val(1);

      checked_val = 1;
    }

    if(part == 'opt')
    {
      check_operating_act = checked_val;
    }
    else if(part == 'inv')
    {
      check_investing_act = checked_val;
    }
    else if(part == 'fin')
    {
      check_financing_act = checked_val;
    }
  }
}

$(document).on('click',"#save_setup_cfs",function(e) 
{
  // var net_cash_val = {
  //                       net_cash_frm_opt: 0,
  //                       net_cash_opt: 0,
  //                       net_cash_inv: 0,
  //                       net_cash_fin: 0
  //                     };

  // net_cash_val['net_cash_frm_opt'] = negative_bracket_to_number($('#tbl_setup_cfs tbody tr.net_cash_frm_opt td:nth-child(4)').text());
  // net_cash_val['net_cash_opt'] = negative_bracket_to_number($('#tbl_setup_cfs tbody tr.opt_net_cash td:nth-child(4)').text());
  // net_cash_val['net_cash_inv'] = negative_bracket_to_number($('#tbl_setup_cfs tbody tr.inv_net_cash td:nth-child(4)').text());
  // net_cash_val['net_cash_fin'] = negative_bracket_to_number($('#tbl_setup_cfs tbody tr.fin_net_cash td:nth-child(4)').text());

  // console.log(net_cash_val);

  $.ajax({ //Upload common input
        url: "fs_statements/save_setup_state_cash_flows",
        type: "POST",
        data: $('form#form_fs_setup_cfs').serialize(),
              // '&net_cash_frm_opt=' + net_cash_val['net_cash_frm_opt'] + 
              // '&net_cash_opt='     + net_cash_val['net_cash_opt'] + 
              // '&net_cash_inv='     + net_cash_val['net_cash_inv'] +
              // '&net_cash_fin='     + net_cash_val['net_cash_fin'],
        dataType: 'json',
        success: function (response,data) 
        {
          var body_id_index     = 0;
          var checkbox_id_index = 0;

          // Due to fixed column bootstrap plugin problem, we write id for twice
          $('#form_fs_setup_cfs #tbl_fs_setup_cfs .header_id').each(function(index, element)
          {
            $(element).val(response['header_id']);
          });

          $('#form_fs_setup_cfs table tbody .body_id').each(function(index, element)
          {
            // reset to 0 for another hidden table
            if(parseInt(body_id_index) > (response['body_id'].length-1))
            {
              body_id_index = 0;
            }

            $(element).val(response['body_id'][body_id_index]);

            body_id_index++;
          });

          $('#form_fs_setup_cfs #tbl_fs_setup_cfs .checkbox_id').each(function(index, element)
          {
            // reset to 0 for another hidden table
            if(parseInt(checkbox_id_index) > (response['checkbox_id'].length-1))
            {
              checkbox_id_index = 0;
            }

            $(element).val(response['checkbox_id'][checkbox_id_index]);

            checkbox_id_index++;
          });


          if(response['status'])
          {
              toastr.success("The data is saved to database.", "Successfully saved");
              // $('#fs_firm_report_modal').modal('hide');
          }
          else
          {
              toastr.error("Something went wrong. Please try again later.", "");
          }

          $('#loadingAudReport').hide();
        }
    });
});