$( document ).ready(function() {
  // $('[data-toggle]').bootstrapToggle();
    // get opinion data attribute
    // $.ajax({
    //   url: "financial_statement/get_fs_doc_template_master",
    //   type: "POST",
    //   // data: $('#form_fs_company_info').serialize() + '&company_code=' + $company_code,
    //   dataType: 'json',
    //   success: function (response,data) {

    //     console.log(response);

    //     for (i = 0; i < response.length; i++) { 
    //         if(response[i]["section"] === "Opinion Details")
    //         {
    //             // console.log();
    //             $("label.lbl_opinion_details").data(response[i]["value"], response[i]["content"]);
    //         }
    //     }

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

    // console.log($(fs_company_info_id).val(), $(company_code).val());

    $.ajax({ //Upload common input
      url: "financial_statement/partial_company_particular",
      type: "POST",
      data: {fs_company_info_id: $(fs_company_info_id).val(), company_code:$(company_code).val()},
      dataType: 'html',
      success: function (response,data) 
      {
        $("#FS_setting").html(response);
      }
    });
});

var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';

// var tcm1 = new Chairman();
function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          //console.log(e);
          alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
        };
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });

    }

}

$(function() { 
    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
        // console.log(localStorage.getItem('lastTab'));
    });
});

// $(document).ready(function() {

//     $('[href="' + '#FS_director_statement' + '"]').tab('show');
// });


loadLastTab();

function loadLastTab()
{
  var fs_company_info_id = $("#fs_company_info_id").val();

    $(document).ready(function() {
      if(fs_company_info_id == 0)
      {
        $('[href="#FS_corporate_information"]').tab('show');
      }
      else
      {
        // go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('lastTab');

        console.log(lastTab);
        
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }
      }
        
        //localStorage.clear();
        $('#loadingTransaction').hide();
    });
}

$('#loadingTransaction').hide();

function loadFirstTab()
{
    $(document).ready(function() {
        $('#loadingTransaction').hide();
    });
}

function deletePDF()
{
    $.ajax({ //Upload common input
      url: "transaction_document/delete_document",
      async: false,
      type: "POST",
      //data: {"path":link},
      dataType: 'json',
      success: function (response,data) {
        console.log(response);
      }
    })
}

function update_fs_report_template()
{
  $('#update_fs_report_template_modal').modal("show");
}

$(".fs_firm #firm_dp").change(function(){
    $('#loadingWizardMessage').show();

    var company_code = $('#firm_dp').val();

    $.ajax({ //Upload common input
      url: "financial_statement/get_firm_info",
      type: "POST",
      data: {"company_code":company_code},
      dataType: 'json',
      success: function (response,data) {
        $('#loadingWizardMessage').hide();
        
        if(response != null) {   
            $("#FS_reg_no").text(response[0].registration_no); 
        }
        else {  $("#FS_reg_no").text('');   }

        $("#company_code").val(response[0].company_code);
      }
    })

    $('#loadingWizardMessage').hide();
});

// calculate sub total for statements
function find_sub_total_lye(parent_fs_categorized_account_id, statement_type)
{
  // update subtotal
  var values = $('.' + statement_type + '_values_under_' + parent_fs_categorized_account_id);
  var subtotal = 0.00;

  for(i = 0; i < values.length; i ++)
  {
    if($(values[i]).val() != '')
    {
      subtotal += parseFloat(convert_back_bracket_to_negative($(values[i]).val()));
    }
  }

  $('#' + statement_type + '_subtotal_' + parent_fs_categorized_account_id).text(subtotal); 

  return subtotal.toFixed(2);
}

// calculate sub total
function find_sub_total_by_classname(className)
{
  var values = $('.' + className);
  var subtotal = 0.00;

  for(i = 0; i < values.length; i ++)
  {
    if($(values[i]).val() != '')
    {
      subtotal += parseFloat(convert_back_bracket_to_negative($(values[i]).val()));
    }
  }

  // $('#' + statement_type + '_subtotal_' + parent_fs_categorized_account_id).text(subtotal); 

  // console.log(className);

  return negative_bracket_thousand_separator(subtotal);
}

function get_overall_total(statement_type)
{
    // update overall total
    var all_values = $('.'+ statement_type + '_all_values');
    var overall_total = 0.00;

    for(i = 0; i < all_values.length; i ++)
    {
        if($(all_values[i]).val() != '')
        {
            overall_total += parseFloat(convert_back_bracket_to_negative($(all_values[i]).val()));
        }
    }

    $('#' + statement_type + '_lye_overall_total').text(overall_total);

    return negative_bracket_thousand_separator(overall_total);
}

function convert_back_bracket_to_negative(number)
{
  // console.log(number);
  if(number == '-' || number == '' || number === undefined)
  {
    return 0;
  }
  else
  {
    return ((number.replace('(', '-')).replace(')', '')).replace(/,/g, '');
  }
}

function negative_bracket_thousand_separator(number)  // convert number to thousand separator and with negative brackets
{
  number = number.toString();

  if(number === '0' || number === 0)
  { 
    return 0;
  }
  else if(number > 0)
  {
    number = numberWithCommas(number);

    return number.toString();
  }
  else
  {
    number = number.toString().replace(/-/g, "");
    number = numberWithCommas(number);

    return '(' + number.toString() + ')';
  }
}

function numberWithCommas(x)  // thousand separator
{
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


/* -------- Save data -------- */
$(document).on('click',"#save_fs_company_info",function(e)
{
    $('#loadingCompanyParticular').show();

    var client_id          = $('#client_id').val();
    var company_code       = $("#company_code").val();
    var reason_of_changing_fc = $('#reason_of_changing_fc').val();

    $.ajax({ //Upload common input
      url: "financial_statement/submit_company_particular",
      type: "POST",
      data: $('#form_fs_company_info').serialize() + '&fs_company_info_id=' + $('#fs_company_info_id').val() + '&company_code=' + company_code + '&firm_id=' + firm_id + '&reason_of_changing_fc=' + reason_of_changing_fc,
      dataType: 'json',
      success: function (response,data) 
      {
        $('input[name=fs_company_info_id]').val(response['id']);

        if(response['result'])
        {
          // console.log(response);
          if(!(response['fs_company_info']['id'] === '') && !(response['fs_company_info']['id'] === 'undefined'))
          {
              toastr.success("The data will be saved to database.", "Success saved!");
              window.location.href = "financial_statement/create/" + client_id + "/" + response['fs_company_info']['id'];
          }
          else
          {
              toastr.error("Something went wrong. Please try again later.", "Error");
          }
        }
        else
        {   
            if(response['popup_msg'] !== '')
            {
              alert(response['popup_msg']);

              toastr.error("Data will not be saved.", "Unsuccesful");
            }
            else
            {
              toastr.error(response['errormsg'], "");
            }
        }

        $('#loadingCompanyParticular').hide();
      }
    });
});

$(document).on('click',"#save_fs_director_interest",function(e)
{
    $('#loadingDirectorInterestShare').show();

    $.ajax({ //Upload common input
        url: "financial_statement/submit_director_statement",
        type: "POST",
        data: $('form#form_fs_director_statement').serialize() + '&fs_company_info_id=' + $('#fs_company_info_id').val() + '&arr_deleted_company=' + arr_deleted_company + '&arr_deleted_directors=' + arr_deleted_directors,
        dataType: 'json',
        success: function (response,data) 
        {
          if(response['status'] === true)
          {
            toastr.success("The data is saved to database.", "Successfully saved");

            $('#fs_director_interest_modal').modal('hide');
          }
          else
          {
            toastr.error("Something went wrong. Please try again later.", "");
          }

          $('#loadingDirectorInterestShare').hide();
        }
    });
});

$(document).on('click',"#save_fs_firm_report",function(e)
{
    $('#loadingAudReport').show();

    // console.log($("#form_audit_report input[name=key_audit_matter_checkbox]").val());
    var key_audit_matter_checkbox = $("#form_audit_report input[name=key_audit_matter_checkbox]").val();

    var fs_company_info_id = $('input[name=fs_company_info_id]').val();
    var opinion_fixed      = $('.lbl_opinion_details').text();
    var opinion_fixed_2    = $('.lbl_opinion_details_2').html();

    var basic_for_opinion       = !($('#fs_aud_report_opinion').val() === '1')? $('.tarea_basic_for_opinion').val(): '';
    var basic_for_opinion_fixed = $('.lbl_basic_for_opinion_fixed').html();

    if(key_audit_matter_checkbox == '1')
    {
      var key_audit_matter       = $('.lbl_key_aud_matter').text();
      var key_audit_matter_input = ($('#fs_aud_report_opinion').val() === '1' || $('#fs_aud_report_opinion').val() === '2')? $('.tarea_desc_key_audit').val(): '';
    }
    else
    {
      var key_audit_matter       = '';
      var key_audit_matter_input = '';
    }

    var emphasis_of_matter = ($("input[name=emphasis_of_matter_checkbox]").val() === '1')? $('.emphasis_input').val(): '';
    var other_matters      = ($("input[name=other_matter_checkbox]").val() === '1')? $('.lbl_other_matter').text(): '';
    var disclaimer_of_opinion = ($("input[name=disclaimer_checkbox]").val() === '1')? $('.disclaimer_input').val(): '';

    // console.log($("[name='other_matter_checkbox']").val());
    if($("[name='other_matter_checkbox']").val() == 0)
    {
      $('input[name=ly_other_aud_checkbox]').val(0);
    }
    
    $.ajax({ //Upload common input
        url: "financial_statement/submit_aud_report",
        type: "POST",
        data: $('form#form_audit_report').serialize() 
                + '&fs_company_info_id='      + fs_company_info_id 
                + '&opinion_fixed='           + opinion_fixed 
                + '&opinion_fixed_2='         + opinion_fixed_2 
                + '&basic_for_opinion='       + basic_for_opinion 
                + '&basic_for_opinion_fixed=' + basic_for_opinion_fixed 
                + '&key_audit_matter='        + key_audit_matter 
                + '&key_audit_matter_input='  + key_audit_matter_input
                + '&emphasis_of_matter='      + emphasis_of_matter 
                + '&other_matters='           + other_matters 
                + '&disclaimer_of_opinion='   + disclaimer_of_opinion,
        dataType: 'json',
        success: function (response,data) 
        {
            if((response['status'] === "success"))
            {
                toastr.success("The data is saved to database.", "Successfully saved");
                $('#fs_firm_report_modal').modal('hide');
            }
            else
            {
                toastr.error("Something went wrong. Please try again later.", "");
            }

            $('#loadingAudReport').hide();
        }
    });
});

$(document).on('click',"#SaveAllAccountDetail",function(e) 
{
  $('#loadingSaveTree').show();

    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));

    CategoriedTree = rearrange_cu_accounts_arr(CategoriedTree);
    // console.log(CategoriedTree);

    var u = $('#Uncategoried_Treeview').jstree(true).get_json('#', { flat: true });
    var UncategoriedTree = JSON.parse(JSON.stringify(u));

    UncategoriedTree = rearrange_cu_accounts_arr(UncategoriedTree);

    $.ajax({
        type: 'post',
        url: "fs_account_category/save_categorized_uncategorized_account",
        dataType: 'json',
        data: { fs_company_info_id: $('#fs_company_info_id').val(), CategoriedTree: CategoriedTree, UncategoriedTree: UncategoriedTree },
        success: function (response) 
        {
            alert(response.message);

            if(response.result)
            {
              $('#Categoried_Treeview').jstree(true).refresh();
              $('#Uncategoried_Treeview').jstree(true).refresh();
            }

            $('#loadingSaveTree').hide();
        }
    });
});

function rearrange_cu_accounts_arr(tree)
{
  var output = [];

  for (var i = 0; i < tree.length; i++) 
  {
    var temp = {
                'id':     tree[i]['id'],
                'parent': tree[i]['parent'],
                'type':   tree[i]['type'],
                'text':   tree[i]['text'],
                'data':   tree[i]['data']
              };

    output.push(temp);
  }

  return output;
}

/* -------- END OF Save data -------- */

$(".upload_signing_report").change(function() 
{
  var filename = readURL_SR(this);
  $(this).parent().children('span').html(filename);
});

$(".upload_template").change(function() 
{
  var data = new FormData();

  data.append('rt_input_file', this.files[0]);
  data.append('fs_company_info_id', $("#fs_company_info_id").val());

  $.ajax({
    url: "Fs_generate_doc_word/upload_report_template",
    type: 'POST',
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      data: data,
      success: function (result, data) 
      {
        console.log(result);

        if(result['result'])
        {
          toastr.success("Success", result['alert_msg']);
          window.location.href = "financial_statement/create/" + $('#client_id').val() + "/" + $("#fs_company_info_id").val();
        }
        else
        {
          toastr.error("Error", result['alert_msg']);
        }

        

        $('#update_fs_report_template_modal').hide();
      },
      error: function (error)
      {
        toastr.error("Something went wrong when uploading the file. Please try again later.", "");

        $('#loadingmessage').hide();
      }
  });
});

// Read File and return value 
function readURL_SR(input) 
{
  $('#loadingmessage').show();

  var url      = input.value;
  var path     = $(input).val();

  console.log(path);

  var filename = path.replace(/^.*\\/, "");

  var data = new FormData();

  data.append('sr_input_file', input.files[0]);
  data.append('fs_company_info_id', $("#fs_company_info_id").val());
  data.append('fs_signing_report_id', $('#fs_signing_report_id').val());

  $.ajax({
    url: "financial_statement/upload_signing_report",
    type: 'POST',
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      data: data,
      success: function (result, data) 
      {
        console.log(result);

        window.location.href = "financial_statement/create/" + $('#client_id').val() + "/" + $("#fs_company_info_id").val();

        if(result)
        {
          toastr.success("Successfully uploaded file", "Upload success");
        }
        else
        {
          toastr.error("Something went wrong. Please try again later.", "");
        }

        $('#loadingmessage').hide();
      },
      error: function (error)
      {
        // console.log(error);
        // alert("Error. Please ensure that excel file contains the correct format.");

        // $(".uploadlogo").val("");
        // $(".uploadlogo").parent().children('span').html("Upload Trial Balance");

        toastr.error("Something went wrong when uploading the file. Please try again later.", "");

        $('#loadingmessage').hide();
      }
  });

  // return "Uploaded file : " + filename;

  // var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  // // if (input.files && input.files[0] && (ext == "xlsx" || ext == "xls" || ext == "CSV")) {
  //   var path = $(input).val();
  //   var filename = path.replace(/^.*\\/, "");

  //   var data = new FormData();

  //   data.append('excel_file', input.files[0]);
  //   data.append('fs_company_info_id', $("#fs_company_info_id").val());

  //   $.ajax({
  //     url: "fs_account_category/read_extract_excel",
  //     type: 'POST',
  //       processData: false, // important
  //       contentType: false, // important
  //       dataType : 'json',
  //       data: data,
  //       success: function (response, data) 
  //       {

  //       },
  //       error: function (error)
  //       {
  //         console.log(error);
  //         alert("Error. Please ensure that excel file contains the correct format.");

  //         $(".uploadlogo").val("");
  //         $(".uploadlogo").parent().children('span').html("Upload Trial Balance");
  //       }
  //   })

  //   return "Uploaded file : " + filename;
    // console.log(url);
  // } else {
  //     $(input).val("");
  //     return "Only excel format are allowed!";
  // }
}

function download_trial_b_template()
{ 
  $.ajax({
    url: "Fs_generate_doc_word/get_trial_balance_template_excel",
    type: 'POST',
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      success: function (response) 
      {
        console.log(response);

        for(var b = 0; b < response.link.length; b++) 
        {
          console.log(response);
          
          // window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
          $('#loadingmessage').hide();
          window.open(
              response.link[b],
              '_blank' // <- This is what makes it open in a new window.
          );
        }
      }
  });
}

function remove_report_template(rt_id)
{
  $('#loadingmessage').show();

  bootbox.confirm("Are you sure to delete the current report template?", function(result)
  {
    if(result)
    {
      $.ajax({
        url: "Fs_generate_doc_word/remove_report_template",
        type: 'POST',
          dataType : 'json',
          data: { rt_id: rt_id },
          success: function (response) 
          {
            $('#loadingmessage').hide();

            console.log(response);

            if(response['result'])
            {
              toastr.success("Success", response['alert_msg']);
            }
            else
            {
              toastr.error("Error", response['alert_msg']);
            }

            $('#update_fs_report_template_modal').hide();

            window.location.href = "financial_statement/create/" + $('#client_id').val() + "/" + $("#fs_company_info_id").val();
          }
      });
    }
    else
    {
      $('#loadingmessage').hide();
    }
  });
}

function download_doc_template($fs_company_info_id)
{
  $.ajax({
    url: "fs_generate_doc_word/download_doc_template",
    type: 'POST',
      dataType : 'json',
      data: { fs_company_info_id: fs_company_info_id },
      success: function (response) 
      {
        console.log(response);

        if(response['status'] == '1')
        {
          for(var b = 0; b < response.link.length; b++) 
          {
            $('#loadingmessage').hide();
            window.open(
                response.link[b],
                '_blank' // <- This is what makes it open in a new window.
            );
          }
        }
        else
        {
          alert(response['msg']);
        }
        
      }
  });
}