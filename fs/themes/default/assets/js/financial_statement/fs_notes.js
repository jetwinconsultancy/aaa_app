// var deleted_fs_note_details_ids = [];
var deleted_fs_note_templates_master_id = [];

function add_edit_note_no(note_no, fs_note_templates_master_id)
{
  $('#insert_note_num').modal("show");

  $('#target_note_no').val(note_no);
  $('#target_fs_note_templates_master_id').val(fs_note_templates_master_id);

}

// function insert_note_num()  // manual key in and insert
// {
//   var fs_statement_doc_type_id = $('#opening_fs_statement_doc_type').val();  // get statement document type id.
//   var note_num = $('#target_note_no').val();
//   var fs_note_templates_master_id = $('#target_fs_note_templates_master_id').val();
//   var input_note_num = $('#input_note_num').val();

//   if(fs_statement_doc_type_id == '1')
//   {
//     $('.note_' + note_num).find('.fs_note_templates_master_id').val(fs_note_templates_master_id);
//     $('.note_' + note_num).find('.fs_note_num_displayed').val(input_note_num);
//     $('.note_' + note_num).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + input_note_num + '</i>');
//     $('.note_' + note_num).parent().find('a.edit_note').addClass("edit_note_no");
//     $('.note_' + note_num).parent().find('a.edit_note_no').show();
//   }
//   else if(fs_statement_doc_type_id == '2')
//   {
//     $('.fp_note_' + note_num).find('.fs_note_templates_master_id').val(fs_note_templates_master_id);
//     $('.fp_note_' + note_num).find('.fs_note_num_displayed').val(input_note_num);
//     $('.fp_note_' + note_num).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + input_note_num + '</i>');
//     $('.fp_note_' + note_num).parent().find('a.edit_note').addClass("edit_note_no");
//     $('.fp_note_' + note_num).parent().find('a.edit_note_no').show();
//   }

//   $('#insert_note_num').modal("hide");
// };

function add_note(element, note_no)
{
  var this_selected_fs_notes_templates_master_id = $(element).parent().find('.fs_note_templates_master_id').val();
  var class_name_fca = '';

  if($('#opening_fs_statement_doc_type').val() == 1)
  {
    var fs_note_template_master_list = $('#form_state_comp_income .fs_note_templates_master_id');
    class_name_fca = '.SCI_C_sub_fs_categorized_account_id';
  }
  else if($('#opening_fs_statement_doc_type').val() == 2)
  {
    var fs_note_template_master_list = $('#form_financial_position .fs_note_templates_master_id');
    // var fs_fca_ids                   = $('#form_state_comp_income .FP_sub_fs_categorized_account_id');

    class_name_fca = '.SFP_sub_fs_categorized_account_id';
  }
  else if($('#opening_fs_statement_doc_type').val() == 3)
  {
    var fs_note_template_master_list = $('#form_state_cash_flows .fs_note_templates_master_id');
    var fs_fca_ids                   = [];

    // console.log(fs_note_template_master_list);
  }

  /* ----- get values from list ----- */
  var fs_fcaro_id_list           = [];
  var current_selected_note_list = [];
  var fs_state_comp_id_list      = [];

  for (i = 0; i < fs_note_template_master_list.length; i++) 
  {
    current_selected_note_list.push($(fs_note_template_master_list[i]).val());

    if($('#opening_fs_statement_doc_type').val() == 1 || $('#opening_fs_statement_doc_type').val() == 2)
    {
      if($(fs_note_template_master_list[i]).parent().parent().find(class_name_fca).val())
      {
        fs_fcaro_id_list.push($(fs_note_template_master_list[i]).parent().parent().find(class_name_fca).val());
      }
      else
      {
        fs_fcaro_id_list.push(0);
      }

      // collect fs_state_comp_id
      if($(fs_note_template_master_list[i]).parent().parent().find('.fs_state_comp_id').val())
      {
        fs_state_comp_id_list.push($(fs_note_template_master_list[i]).parent().parent().find('.fs_state_comp_id').val());
      }
      else
      {
        fs_state_comp_id_list.push(0);
      }
    }
  }

  /* ----- END OF get values from list ----- */

  // console.log(fs_fcaro_id_list);
  // console.log(current_selected_note_list);

  $.ajax({ //Upload common input
      url: "fs_notes/partial_note_list",
      type: "POST",
      data: {
            fs_company_info_id        : $('#fs_company_info_id').val(), 
            fs_statement_doc_type_id  : $('#opening_fs_statement_doc_type').val(), 
            // fs_state_comp_income_id : fs_state_comp_income_id, 
            note_no : note_no, 
            // is_selected: is_selected,
            fs_state_comp_id_list : fs_state_comp_id_list,
            fs_fcaro_id_list : fs_fcaro_id_list,
            current_selected_note_list                  : current_selected_note_list,
            this_selected_fs_notes_templates_master_id  : this_selected_fs_notes_templates_master_id,  // this selected row with selected note template
            deleted_fs_note_templates_master_id         : deleted_fs_note_templates_master_id
          },
      dataType: 'html',
      success: function (response,data) {
        $('#note_list .modal-body').html(response);
        
        $('#note_list').modal("show");
      }
  });
}

function insert_note_number(element, note_no) // fs_note_templates_master_id
{
  var fs_note_templates_master_id = $(element).parent().find('.fs_note_templates_master_id').val();
  var fs_note_details_id          = $(element).parent().find('.fs_note_details_id').val();
  var fs_ntfs_layout_template_default_id = $(element).parent().find('.fs_ntfs_layout_template_default_id').val();

  // get document type & rearrange note_no & update fs_note_templates_master_id, fs_note_num_displayed
  var fs_statement_doc_type_id = $('#opening_fs_statement_doc_type').val();  // get statement document type id.
  var display_no_start = 0;
  var previous_fs_ntfs_layout_template_default_id = 0;

  var add_note_td = '';
  var selected_td = '';
  var note_class_name = '';

  var list_selected_fs_ntfs_layout_template_id = [];

  if(fs_statement_doc_type_id == 1)
  {
    // add_note_td = $('.add_note_td');
    selected_td = $('.note_' + note_no);
    note_class_name = 'note_';
    note_class_name_dot = '.note_';

    previous_fs_ntfs_layout_template_default_id = $('.note_' + note_no).find('.fs_ntfs_layout_template_default_id').val();
    // console.log($('.note_' + note_no).find('.fs_ntfs_layout_template_default_id').val());
  }
  else if(fs_statement_doc_type_id == 2)
  {
    // add_note_td = $('.fp_add_note_td');
    selected_td = $('.fp_note_' + note_no);
    note_class_name = 'fp_note_';
    note_class_name_dot = '.fp_note_';

    previous_fs_ntfs_layout_template_default_id = $('.fp_note_' + note_no).find('.fs_ntfs_layout_template_default_id').val();
  }
  else if(fs_statement_doc_type_id == 3)
  {
    selected_td = $('.cf_note' + note_no);
    note_class_name = 'cf_note';
    note_class_name_dot = '.cf_note';

    previous_fs_ntfs_layout_template_default_id = $('.cf_note' + note_no).find('.fs_ntfs_layout_template_default_id').val();
  }

  // collect all fs_ntfs_layout_template_default_id
  // for (i = 0; i < add_note_td.length; i++) 
  // {
  //   temp_fs_ntfs_layout_template_default_id = $($(add_note_td[i])[0]).find('.fs_ntfs_layout_template_default_id').val();
  //   list_selected_fs_ntfs_layout_template_id.push(temp_fs_ntfs_layout_template_default_id);
  // }

  

  // console.log(fs_ntfs_layout_template_list);

  /* DO NOT DELETE THIS */
  // var auto_rearrange_value = $("[name='auto_rearrange_value']").val();
  /* END OF DO NOT DELETE THIS */

  // console.log($("[name='auto_rearrange_value']").val());

  // console.log($('#opening_fs_statement_doc_type'));

  /* ------- DO NOT DELETE THIS (Auto rearrange note no) ------- */
  // if(fs_statement_doc_type_id == 1)
  // {
  //   var add_note_td = $('.add_note_td');
  //   var selected_td = $('.note_' + note_no);

  //   display_no_start = 3;

  //   if(auto_rearrange_value == '1')
  //   {
  //     for (i = 0; i < add_note_td.length; i++) 
  //     {
  //       var td = $(add_note_td[i]);

  //       if(td.hasClass('note_' + note_no))
  //       {
  //         $('.note_' + note_no).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + display_no_start + '</i>');
  //         $('.note_' + note_no).parent().find('a.edit_note').addClass("edit_note_no");

  //         $(td[0]).find('.fs_note_templates_master_id').val(fs_note_templates_master_id);
  //         $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

  //         var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

  //         $(i_inserted_note_no[0]).text(display_no_start);

  //         display_no_start ++;
  //       }
  //       else
  //       {
  //         if($(td[0]).find('i.inserted_note_no').length !== 0)
  //         {
  //           $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

  //           var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

  //           $(i_inserted_note_no[0]).text(display_no_start);

  //           display_no_start ++;
  //         }
  //       }
  //     }
  //   }
  //   else
  //   {
  //     $('#target_note_no').val(note_no);
  //     $('#target_fs_note_templates_master_id').val(fs_note_templates_master_id);
  //     $('#insert_note_num').modal("show");
  //   }
    
  // }
  // else if(fs_statement_doc_type_id == 2)
  // {
  //   var add_note_td = $('.fp_add_note_td');
  //   var selected_td = $('.fp_note_' + note_no);

  //   // retrieve first number to start for statement
  //   $.ajax({ //Upload common input
  //     url: "fs_notes/get_starting_note_no",
  //     type: "POST",
  //     data: {
  //           fs_company_info_id        : $(fs_company_info_id).val(), 
  //           fs_statement_doc_type_id  : 2
  //         },
  //     dataType: 'html',
  //     success: function (response,data) {
  //       console.log(response);

  //       display_no_start = JSON.parse(response)[0]['biggest_note_no'];

  //       if(display_no_start == 0)
  //       {
  //         display_no_start = 3;
  //       }
  //       else
  //       {
  //         display_no_start = parseInt(display_no_start) + 1;
  //       }

  //       // if auto rearrange is ON, rearrange the numbering
  //       if(auto_rearrange_value == '1')
  //       {
  //         for (i = 0; i < add_note_td.length; i++) 
  //         {
  //           var td = $(add_note_td[i]);

  //           if(td.hasClass('fp_note_' + note_no))
  //           {
  //             $('.fp_note_' + note_no).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + display_no_start + '</i>');
  //             $('.fp_note_' + note_no).parent().find('a.edit_note').addClass("edit_note_no");

  //             $(td[0]).find('.fs_note_templates_master_id').val(fs_note_templates_master_id);
  //             $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

  //             var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

  //             $(i_inserted_note_no[0]).text(display_no_start);

  //             display_no_start ++;
  //           }
  //           else
  //           {
  //             if($(td[0]).find('i.inserted_note_no').length !== 0)
  //             {
  //               $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

  //               var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

  //               $(i_inserted_note_no[0]).text(display_no_start);

  //               display_no_start ++;
  //             }
  //           }
  //         }
  //       }
  //       else
  //       {
  //         $('#target_note_no').val(note_no);
  //         $('#target_fs_note_templates_master_id').val(fs_note_templates_master_id);
  //         $('#insert_note_num').modal("show");
  //       }
  //     }
  //   });
  // }
  // if(fs_statement_doc_type_id == 1)
  // {
  //   var add_note_td = $('.add_note_td');
  //   var selected_td = $('.note_' + note_no);

  //   display_no_start = 3;

  //   if(auto_rearrange_value == '1')
  //   {
  //     for (i = 0; i < add_note_td.length; i++) 
  //     {
  //       var td = $(add_note_td[i]);

  //       if(td.hasClass('note_' + note_no))
  //       {
  //         $('.note_' + note_no).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + display_no_start + '</i>');
  //         $('.note_' + note_no).parent().find('a.edit_note').addClass("edit_note_no");

  //         $(td[0]).find('.fs_note_templates_master_id').val(fs_note_templates_master_id);
  //         $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

  //         var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

  //         $(i_inserted_note_no[0]).text(display_no_start);

  //         display_no_start ++;
  //       }
  //       else
  //       {
  //         if($(td[0]).find('i.inserted_note_no').length !== 0)
  //         {
  //           $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

  //           var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

  //           $(i_inserted_note_no[0]).text(display_no_start);

  //           display_no_start ++;
  //         }
  //       }
  //     }
  //   }
  //   else
  //   {
  //     $('#target_note_no').val(note_no);
  //     $('#target_fs_note_templates_master_id').val(fs_note_templates_master_id);
  //     $('#insert_note_num').modal("show");
  //   }
    
  // }

  /* ------- END OF DO NOT DELETE THIS (Auto rearrange note no) ------- */

  if(fs_statement_doc_type_id == 1 || fs_statement_doc_type_id == 2)
  {
    // var add_note_td = $('.add_note_td');
    // var selected_td = $('.note_' + note_no);

    // display_no_start = 3;

    // if(auto_rearrange_value == '1')
    // {
      // for (i = 0; i < add_note_td.length; i++) 
      // {
      //   var td = $(add_note_td[i]);

      //   if(td.hasClass(note_class_name + note_no))
      //   {
      //     // previous_fs_ntfs_layout_template_default_id = $(td[0]).find('.fs_ntfs_layout_template_default_id').val(); // get previous fs_ntfs_layout_template_default_id

      //     /* ------ find note no using fs_ntfs_layout_template_default_id ------ */
      //     fs_ntfs_layout_template_list.forEach(function(item, index) 
      //     {
      //       if(item['id'] === fs_ntfs_layout_template_default_id)
      //       {
      //         display_no_start = item['note_no'];
      //       }
      //     });
      //     /* ------ END OF find note no using fs_ntfs_layout_template_default_id ------ */

      //     $(note_class_name_dot + note_no).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + display_no_start + '</i>');
      //     $(note_class_name_dot + note_no).parent().find('a.edit_note').addClass("edit_note_no");

      //     $(td[0]).find('.fs_note_templates_master_id').val(fs_note_templates_master_id);
      //     $(td[0]).find('.fs_ntfs_layout_template_default_id').val(fs_ntfs_layout_template_default_id);
      //     $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

      //     var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

      //     $(i_inserted_note_no[0]).text(display_no_start);
      //   }
      //   else
      //   {
      //     /* ------ find note no using fs_ntfs_layout_template_default_id ------ */
      //     var temp_fs_ntfs_layout_template_default_id = $(td[0]).find('.fs_ntfs_layout_template_default_id').val();

      //     // console.log(temp_fs_ntfs_layout_template_default_id);

      //     fs_ntfs_layout_template_list.forEach(function(item, index) 
      //     {
      //       if(item['id'] === temp_fs_ntfs_layout_template_default_id)
      //       {
      //         display_no_start = item['note_no'];
      //       }
      //     });
      //     /* ------ END OF find note no using fs_ntfs_layout_template_default_id ------ */

      //     if($(td[0]).find('i.inserted_note_no').length !== 0)
      //     {
      //       $(td[0]).find('.fs_note_num_displayed').val(display_no_start);

      //       var i_inserted_note_no = $(td[0]).find('i.inserted_note_no');

      //       $(i_inserted_note_no[0]).text(display_no_start);
      //     }
      //   }
      // }
    // }
    // else
    // {
    //   $('#target_note_no').val(note_no);
    //   $('#target_fs_note_templates_master_id').val(fs_note_templates_master_id);
    //   $('#insert_note_num').modal("show");
    // }

    var in_deleted_list_key = deleted_fs_note_templates_master_id.indexOf(fs_note_templates_master_id);

    if(in_deleted_list_key > -1)
    {
      deleted_fs_note_templates_master_id.splice(in_deleted_list_key, 1);
    }

    list_selected_fs_ntfs_layout_template_id = get_list_selected_fs_ntfs_layout_template_id(fs_statement_doc_type_id, note_no);

    console.log(fs_ntfs_layout_template_default_id);
    console.log("before:");
    console.log(fs_ntfs_layout_template_list);

    fs_ntfs_layout_template_list = rewrite_fs_ntfs_layout_template(fs_ntfs_layout_template_list, list_selected_fs_ntfs_layout_template_id, previous_fs_ntfs_layout_template_default_id, fs_ntfs_layout_template_default_id, 0); // rewrite the list

    console.log("after:");
    console.log(fs_ntfs_layout_template_list);

    $(selected_td).find('.fs_note_templates_master_id').val(fs_note_templates_master_id);

    // console.log($(selected_td).find('.fs_note_templates_master_id'));

    $(selected_td).find('.fs_ntfs_layout_template_default_id').val(fs_ntfs_layout_template_default_id);

    var selected_fs_ntfs_layout_template_default_id = $(selected_td).find('.fs_ntfs_layout_template_default_id').val();

    $(selected_td).find('.fs_ntfs_layout_template_default_id').parent().append('<input type="hidden" class="adding_note" value="1">');  // use hidden input to mark up this deleted note

    update_all_notes_in_state_doc();
  }
  else if(fs_statement_doc_type_id == 3)
  {
    $('.cf_note_' + note_no).find('.input_note_id').val(fs_note_details_id);

    $.ajax({ //Upload common input
      url: "fs_notes/get_input_note_num",
      type: "POST",
      data: {
          fs_company_info_id : $('#fs_company_info_id').val(), 
          fs_note_details_id : fs_note_details_id
      },
      dataType: 'html',
      success: function (input_note_num) 
      {
        $('.cf_note_' + note_no).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + JSON.parse(input_note_num)[0]['note_num_displayed'] + '</i>');
      }
    });
    // $('.cf_note_' + note_no).parent().find('a.edit_note').addClass("edit_note_no");
    // $('.cf_note_' + note_no).parent().find('a.edit_note_no').show();
  }

  $('#note_list').modal("hide");
}

function get_list_selected_fs_ntfs_layout_template_id(fs_statement_doc_type_id, note_no)
{
  var list_selected_fs_ntfs_layout_template_id = [];

  if(fs_statement_doc_type_id == 1)
  {
    add_note_td = $('.add_note_td');
    selected_td = $('.note_' + note_no);
    note_class_name = 'note_';
    note_class_name_dot = '.note_';

    previous_fs_ntfs_layout_template_default_id = $('.note_' + note_no).find('.fs_ntfs_layout_template_default_id').val();
    // console.log($('.note_' + note_no).find('.fs_ntfs_layout_template_default_id').val());
  }
  else if(fs_statement_doc_type_id == 2)
  {
    add_note_td = $('.fp_add_note_td');
    selected_td = $('.fp_note_' + note_no);
    note_class_name = 'fp_note_';
    note_class_name_dot = '.fp_note_';

    previous_fs_ntfs_layout_template_default_id = $('.fp_note_' + note_no).find('.fs_ntfs_layout_template_default_id').val();
  }

  // collect all fs_ntfs_layout_template_default_id
  for (i = 0; i < add_note_td.length; i++) 
  {
    temp_fs_ntfs_layout_template_default_id = $($(add_note_td[i])[0]).find('.fs_ntfs_layout_template_default_id').val();
    list_selected_fs_ntfs_layout_template_id.push(temp_fs_ntfs_layout_template_default_id);
  }

  return list_selected_fs_ntfs_layout_template_id;
}

function rewrite_fs_ntfs_layout_template(fs_ntfs_layout_template_list, list_selected_fs_ntfs_layout_template_id, previous_fs_ntfs_layout_template_default_id, this_fs_ntfs_layout_template_id, deleted)
{
  var counter = 3;

  // get another list of note no from db, then check if note is appeared in the list then dont unchecked the note - 25/3/2020

  fs_ntfs_layout_template_list.forEach(function(item, index) 
  {
    // if(item['id'] === previous_fs_ntfs_layout_template_default_id && previous_fs_ntfs_layout_template_default_id !== this_fs_ntfs_layout_template_id)  // unchecked previous fs_ntfs_layout_template_default_id item
    // {
    //   var matched_times = 0;

    //   for (i = 0; i < list_selected_fs_ntfs_layout_template_id.length; i++) 
    //   {
    //     if(list_selected_fs_ntfs_layout_template_id[i] == item['id'])
    //     {
    //       matched_times++;
    //     }
    //   }

    //   if(matched_times == 1)  // unchecked note if this selected note is selected once only. 
    //   {
    //     item['is_checked'] = 0;
    //   }
    // }
    // else 
    if(item['id'] === this_fs_ntfs_layout_template_id)
    {
      if(!deleted)
      {
        item['is_checked'] = 1;
      }
      else
      {
        var matched_times = 0;

        for (i = 0; i < list_selected_fs_ntfs_layout_template_id.length; i++)   // for current selected fs_ntfs_layout_template
        {
          if(list_selected_fs_ntfs_layout_template_id[i] == item['id'])
          {
            matched_times++;
          }
        }

        for (i = 0; i < fs_notes_details_state_2.length; i++)   // for another statement linked note
        {
          if(fs_notes_details_state_2[i]['fs_ntfs_layout_template_default_id'] == item['id'])
          {
            matched_times++;
          }
        }

        if(item['default_checked'] !== 1)
        {
          if(matched_times == 1)  // unchecked note if this selected note is selected once only. 
          {
            item['is_checked'] = 0;
            deleted_fs_note_templates_master_id.push(item['fs_note_templates_master_id']);
          }
        }
      }
    }

    if(item['is_checked'] == 1)
    {
      item['note_no'] = counter;
      counter++;
    }
    else
    {
      item['note_no'] = '-';
    }
  });

  return fs_ntfs_layout_template_list;
}

function remove_note(element, note_no)
{
  var fs_statement_doc_type_id = $('#opening_fs_statement_doc_type').val();
  var display_no_start = 2;
  var note_class_name = '';
  // var auto_rearrange_value = $("[name='auto_rearrange_value']").val();

  if(fs_statement_doc_type_id == 1)
  {
    note_class_name = '.note_' + note_no;
  }
  else if(fs_statement_doc_type_id == 2)
  {
    note_class_name = '.fp_note_' + note_no;
  }

  if(fs_statement_doc_type_id == 1 || fs_statement_doc_type_id == 2)
  {
    // display_no_start = 3;
    var selected_fs_ntfs_layout_template_default_id = $(note_class_name).find('.fs_ntfs_layout_template_default_id').val();

    $(note_class_name).find('.fs_ntfs_layout_template_default_id').parent().append('<input type="hidden" class="deleted_note" value="1">');  // use hidden input to mark up this deleted note

    // $('.note_' + note_no).find('.fs_ntfs_layout_template_default_id').val(0);
    // $('.note_' + note_no).find('.fs_note_templates_master_id').val(0);
    // $('.note_' + note_no).find('.fs_note_num_displayed').val('');
    // $('.note_' + note_no).find('.add_note').empty().append('<i class="fa fa-plus-circle" style="font-size:16px;"></i>');  // remove number display text and change to + icon
    // $('.note_' + note_no).parent().find('a.edit_note').removeClass("edit_note_no");
    // $('.note_' + note_no).parent().find('a.edit_note').hide();

    var list_selected_fs_ntfs_layout_template_id = get_list_selected_fs_ntfs_layout_template_id(fs_statement_doc_type_id, note_no);

    rewrite_fs_ntfs_layout_template(fs_ntfs_layout_template_list, list_selected_fs_ntfs_layout_template_id, 0, selected_fs_ntfs_layout_template_default_id, 1);
    update_all_notes_in_state_doc();

    // if(auto_rearrange_value == '1')
    // {
    //   // rearrange the numbering
    //   $('input[name^="SCI_fs_note_num_displayed"]').each(function(key, value)
    //   { 
    //     if($(this).val() !== '')
    //     {
    //       $(this).val(display_no_start);
    //       $(this).parent().find('.add_note').find('.inserted_note_no').text(display_no_start);

    //       display_no_start++;
    //     }
    //   });
    // }
  }
  // else if(fs_statement_doc_type_id == 2)
  // {
  //   var previous_fs_ntfs_layout_template_default_id = $('.note_' + note_no).find('.fs_ntfs_layout_template_default_id').val();

  //   $('.fp_note_' + note_no).find('.fs_note_templates_master_id').val(0);
  //   $('.fp_note_' + note_no).find('.fs_note_num_displayed').val('');
  //   $('.fp_note_' + note_no).find('.add_note').empty().append('<i class="fa fa-plus-circle" style="font-size:16px;"></i>');
  //   $('.fp_note_' + note_no).parent().find('a.edit_note').removeClass("edit_note_no");
  //   $('.fp_note_' + note_no).parent().find('a.edit_note').hide();

  //   var list_selected_fs_ntfs_layout_template_id = get_list_selected_fs_ntfs_layout_template_id(fs_statement_doc_type_id, note_no);

  //   rewrite_fs_ntfs_layout_template(fs_ntfs_layout_template_list, list_selected_fs_ntfs_layout_template_id, previous_fs_ntfs_layout_template_default_id, '0', 1);

  //   // retrieve first number to start for statement
  //   $.ajax({ //Upload common input
  //     url: "fs_notes/get_starting_note_no",
  //     type: "POST",
  //     data: {
  //           fs_company_info_id        : $(fs_company_info_id).val(), 
  //           fs_statement_doc_type_id  : $('#opening_fs_statement_doc_type').val()
  //         },
  //     dataType: 'html',
  //     success: function (response,data) 
  //     {
  //       display_no_start = JSON.parse(response)[0]['biggest_note_no'];


  //       if(display_no_start == 0)
  //       {
  //         display_no_start = 3;
  //       }
  //       else
  //       {
  //         display_no_start = parseInt(display_no_start) + 1;
  //       }

  //       // if(auto_rearrange_value == '1')
  //       // {
  //       //   // rearrange the numbering
  //       //   $('input[name^="FP_fs_note_num_displayed"]').each(function(key, value)
  //       //   { 
  //       //     if($(this).val() !== '')
  //       //     {
  //       //       $(this).val(display_no_start);
  //       //       $(this).parent().find('.add_note').find('.inserted_note_no').text(display_no_start);

  //       //       display_no_start++;
  //       //     }
  //       //   });
  //       // }
  //     }
  //   });
  // }
  else if(fs_statement_doc_type_id == 3)
  {
    $('.cf_note_' + note_no).find('.input_note_id').val(0);
    $('.cf_note_' + note_no).find('.add_note').empty().append('<i class="fa fa-plus-circle" style="font-size:14px;"></i>');
    $('.cf_note_' + note_no).parent().find('a.edit_note').removeClass("edit_note_no");
    $('.cf_note_' + note_no).parent().find('a.edit_note').hide();
  }

  $('#note_list').modal("hide");
}

function update_all_notes_in_state_doc()
{
  var fs_statement_doc_type_id = $('#opening_fs_statement_doc_type').val();
  
  var add_note_td   = '';
  var lytd_ids_list = fs_ntfs_layout_template_list.map(function(value, index){ return value['fs_ntfs_layout_template_default_id']});  // array_column for "fs_ntfs_layout_template_default_id"
  var deleted_note = 0;
  var added_note = 0; 

  console.log(fs_ntfs_layout_template_list);
  console.log(lytd_ids_list);

  if(fs_statement_doc_type_id == 1)
  {
    add_note_td = $('.add_note_td');
  }
  else if(fs_statement_doc_type_id == 2)
  {
    add_note_td = $('.fp_add_note_td');
  }

  add_note_td.each(function(index, item_td)
  {
    added_note     = 0;
    deleted_note   = 0;
    this_lytd_id   = $(item_td).find('.fs_ntfs_layout_template_default_id').val();
    lytd_arr_index = lytd_ids_list.indexOf(this_lytd_id);

    // for added note
    if($(item_td).find('.adding_note').length > 0)
    {
      added_note = $(item_td).find('.adding_note').val();
    }

    // for deleted note
    if($(item_td).find('.deleted_note').length > 0)
    {
      deleted_note = $(item_td).find('.deleted_note').val();
    }
  
    if(lytd_arr_index > -1)   // if id found in the list
    {
      if(!fs_ntfs_layout_template_list[lytd_arr_index]['is_checked'] || deleted_note) 
      {
        $(item_td).find(".fs_ntfs_layout_template_default_id").val(0);
        $(item_td).find(".fs_note_templates_master_id").val(0);
        $(item_td).find(".fs_note_num_displayed").val(0);

        if($(item_td).find('a.add_note > i').hasClass("inserted_note_no") && deleted_note)
        {
          $(item_td).find('a.add_note > i').empty().append('<i class="fa fa-plus-circle" style="font-size:16px;"></i>');  // remove number display text and change to + icon
          $(item_td).find('.deleted_note').remove();
        }
      }
      else if(fs_ntfs_layout_template_list[lytd_arr_index]['is_checked'] || added_note) // if checked, add note
      {
        var deleted_fntm_ids_key = deleted_fs_note_templates_master_id.indexOf(fs_ntfs_layout_template_list[lytd_arr_index]['fs_note_templates_master_id']);

        $(item_td).find(".fs_ntfs_layout_template_default_id").val(fs_ntfs_layout_template_list[lytd_arr_index]['fs_ntfs_layout_template_default_id']);

        console.log(fs_ntfs_layout_template_list);

        $(item_td).find(".fs_note_templates_master_id").val(fs_ntfs_layout_template_list[lytd_arr_index]['fs_note_templates_master_id']);
        $(item_td).find(".fs_note_num_displayed").val(fs_ntfs_layout_template_list[lytd_arr_index]['note_no']);

        if(fs_ntfs_layout_template_list[lytd_arr_index]['note_no'] == 0)
        {
          fs_ntfs_layout_template_list[lytd_arr_index]['note_no'] = '';
        }

        $(item_td).find('a.add_note > i.inserted_note_no').text(fs_ntfs_layout_template_list[lytd_arr_index]['note_no']);

        if(added_note) // need to add note, insert note
        {
          $(item_td).find('.add_note').empty().append('<i class="inserted_note_no" style="font-size:16px;">' + fs_ntfs_layout_template_list[lytd_arr_index]['note_no'] + '</i>');
          $(item_td).find('.adding_note').remove();
        }
      }
    }
    else  // if id is not found in the list, check if have edit note, remove it if found / remove hidden input "deleted"
    {
      $(item_td).find(".fs_ntfs_layout_template_default_id").val(0);
      $(item_td).find(".fs_note_templates_master_id").val(0);
      $(item_td).find(".fs_note_num_displayed").val(0);

      if($(item_td).hasClass(".inserted_note_no") && deleted_note)
      {
        $(item_td).find('.add_note > i').empty().append('<i class="fa fa-plus-circle" style="font-size:16px;"></i>');  // remove number display text and change to + icon
        $(item_td).find('.deleted_note').remove();
      }
    }

    deleted_note = 0;
  });
}

// function show_note_layout(fs_note_templates_master_id, fs_categorized_account_id)
// {
  // console.log($('.statement_doc_type').val());

	// $('#fs_note_templates_master_id').val(fs_note_templates_master_id);
	// $('#fs_categorized_account_id_to_link_note').val(fs_categorized_account_id);

	// $.ajax({ //Upload common input
	//   	url: "fs_notes/partial_note_layout",
	//   	type: "POST",
	//   	data: {
	//   		fs_company_info_id: $(fs_company_info_id).val(), 
	//   		fs_note_templates_master_id: fs_note_templates_master_id, 
	//   		fs_categorized_account_id: fs_categorized_account_id 
	//   	},
	//     dataType: 'json',
	//     success: function (response,data) {
 //        // response = JSON.parse(response);
	//     	console.log(response);

 //        $('#note_list').modal("hide");

        /* ---------------- DO NOT DELETE THIS! For note's layout purpose. ---------------- */
	    	// $('#fs_note_templates_default_id_selected').val(response['fs_note_templates_default_id']);

	    	// $('#note_layout .modal-header strong').text(response['fs_note_name']);
	    	// $('#note_layout .modal-body').html(response['layout_template'][0]['layout_template']);
	    	
	    	// $('#note_list').modal("hide");
	    	// $('#note_layout').modal("show");

	    	// $('.group_col').hide();
	    	// $('.lye_value').hide();

        /* ---------------- END OF DO NOT DELETE THIS! For note's layout purpose. ---------------- */
	// 	}
	// });

  // var fs_note_templates_default_id = $('#fs_note_templates_default_id_selected').val();
  // var fs_statement_doc_type_id = $('.statement_doc_type').val();

  /* GENERATE AND REARRANGE NOTE NO. BEFORE SAVE! */


  /* Save not to database */
  // $.ajax({ //Upload common input
  //     url: "fs_notes/save_note",
  //     type: "POST",
  //     data:
  //         '&fs_company_info_id=' + $("#fs_company_info_id").val() + 
  //         '&fs_statement_doc_type_id=' + $('.statement_doc_type').val() + 
  //         '&fs_categorized_account_id_to_link_note=' + fs_categorized_account_id + 
  //         '&fs_note_templates_master_id=' + fs_note_templates_master_id,
  //     dataType: 'json',
  //     success: function (response,data) {

  //       console.log(response);

  //       // alert("Added Note!");

  //       if(response['result'])
  //       {
  //         alert("Successfully updated!");
  //         $('#note_list').modal("hide");
  //       }
  //       else
  //       {
  //         alert("Something went wrong! Please try again later.");
  //       }

  //       // toastr.success(response.message, response.title);

  //       // $('#loadingmessage').hide();

  //         // if (response.Status === 1) 
  //         // {
  //         //   toastr.success(response.message, response.title);
  //         //   // $("#body_appoint_new_director .row_appoint_new_director").remove();
  //         //   //console.log($("#transaction_trans #transaction_master_id"));
  //         //   //$(".transaction_change_regis_ofis_address_id").val(response.transaction_change_regis_ofis_address_id);
  //         //   $("#transaction_trans #transaction_code").val(response.transaction_code);
  //         //   $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
  //         //   //$("#strike_off_form #transaction_strike_off_id").val(response.transaction_strike_off_id);
  //         //   //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
  //         // }
  //       }
  // });

  /* END OF Save not to database */
// }

$(document).on('click',"#btn_save_note",function(e){
    $('#loadingmessage').show();

    var fs_note_templates_default_id = $('#fs_note_templates_default_id_selected').val();
    var fs_categorized_account_id_to_link_note = $('#fs_categorized_account_id_to_link_note').val();
    var fs_note_templates_master_id = $('#fs_note_templates_master_id').val();

    // console.log(fs_note_templates_default_id);

  	// if(fs_note_templates_default_id == 1)
  	// {
  	// 	var formData = $('#note_employee_benefits_expense').serialize();
  	// }
  	// else if(fs_note_templates_default_id == 2)
  	// {
      
  	// }

    // $.ajax({ //Upload common input
    //   url: "fs_notes/save_note",
    //   type: "POST",
    //   data:  
    //       formData + 
    //   		'&fs_company_info_id=' + $("#fs_company_info_id").val() + 
    //   		'&fs_categorized_account_id_to_link_note=' + fs_categorized_account_id_to_link_note + 
    //   		'&fs_note_templates_master_id=' + fs_note_templates_master_id,
    //   dataType: 'json',
    //   success: function (response,data) {

    //     console.log(response);

    //     // alert("Added Note!");

    //     if(response['result'])
    //     {
    //       alert("Successfully updated!");
    //       $('#note_layout').modal("hide");
    //     }
    //     else
    //     {
    //       alert("Something went wrong! Please try again later.");
    //     }

    //     // // toastr.success(response.message, response.title);

    //     // $('#loadingmessage').hide();

    //       // if (response.Status === 1) 
    //       // {
    //       //   toastr.success(response.message, response.title);
    //       //   // $("#body_appoint_new_director .row_appoint_new_director").remove();
    //       //   //console.log($("#transaction_trans #transaction_master_id"));
    //       //   //$(".transaction_change_regis_ofis_address_id").val(response.transaction_change_regis_ofis_address_id);
    //       //   $("#transaction_trans #transaction_code").val(response.transaction_code);
    //       //   $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
    //       //   //$("#strike_off_form #transaction_strike_off_id").val(response.transaction_strike_off_id);
    //       //   //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
    //       // }
    //     }
    // });
});
