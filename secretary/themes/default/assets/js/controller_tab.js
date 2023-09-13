var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';

$(".add_office_person_link").attr("href", url + 'personprofile/add/1');

$('#form_individual_register_of_controller').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        identification_no: {
            row: '.div_identification_no',
            validators: {
                notEmpty: {
                    message: 'The Identification No. field is required.'
                }
            }
        },
        individual_controller_name: {
            row: '.div_controller_name',
            validators: {
                notEmpty: {
                    message: 'The Name field is required.'
                }
            }
        },
        date_appointed: {
            row: '.div_date_appoint',
            validators: {
                notEmpty: {
                    message: 'The Date Appointed field is required.'
                }
            }
        },
        individual_confirm_registrable_controller: {
            row: '.div_radio_confirm_controller',
            validators: {
                notEmpty: {
                    message: 'The Confirmation by Registrable Controller field is required.'
                }
            }
        },
    }
});

$('#form_company_register_of_controller').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        controller_uen: {
            row: '.div_controller_uen',
            validators: {
                notEmpty: {
                    message: 'The UEN field is required.'
                }
            }
        },
        entity_name: {
            row: '.div_entity_name',
            validators: {
                notEmpty: {
                    message: 'The Entity Name field is required.'
                }
            }
        },
        date_appointed: {
            row: '.div_date_appoint',
            validators: {
                notEmpty: {
                    message: 'The Date Appointed field is required.'
                }
            }
        },
        corp_confirm_registrable_controller: {
            row: '.div_radio_corp_confirm_registrable_controller',
            validators: {
                notEmpty: {
                    message: 'The Confirmation by Registrable Controller field is required.'
                }
            }
        },
    }
});

//Controller
$('#form_individual_register_of_controller #date_appointed').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#form_individual_register_of_controller').formValidation('revalidateField', 'date_appointed');
});

$('#form_company_register_of_controller #date_appointed').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#form_company_register_of_controller').formValidation('revalidateField', 'date_appointed');
});

function open_controller()
{
    $(".individual_register_controller").show();
    $(".company_register_controller").hide();
    $('input[name="field_type"]').prop("disabled", false);
    $("#reg_cont_individual_edit").prop("checked", true);
    $('.individual_register_controller input[name="identification_no"]').prop("readonly", false);
    $('.individual_register_controller input').val('');
    $(".individual_register_controller .file_name").html("");
    $(".individual_register_controller .hidden_supporting_document").val("");

    //$('input[name="individual_confirm_registrable_controller"]').attr('checked', false);
    $('.individual_register_controller .individual_div_date_confirmation').hide();
    $('.company_register_controller .corp_view_edit_person').hide();
    $('.individual_register_controller .indi_view_edit_person').hide();

    $('.company_register_controller input').val('');
    $(".company_register_controller .file_name").html("");
    $(".company_register_controller .hidden_supporting_document").val("");
    $('.company_register_controller .corp_div_date_confirmation').hide();
    
    $('#form_individual_register_of_controller').formValidation('revalidateField', 'identification_no');
    $('#form_individual_register_of_controller').formValidation('revalidateField', 'individual_controller_name');
    $('#form_individual_register_of_controller').formValidation('revalidateField', 'date_appointed');

    $("#modal_controller").modal("show");
}

$("#create_controller").click(function()
{
    open_controller();
});

//category
$("#reg_cont_individual_edit").click(function() {
    $(".individual_register_controller").show();
    $(".company_register_controller").hide();
    $('.company_register_controller input[name="controller_uen"]').prop("readonly", false);
    $('.company_register_controller input').val('');
    $(".company_register_controller .corp_file_name").html("");
    $(".company_register_controller .corp_hidden_supporting_document").val("");
    $('input[name="corp_confirm_registrable_controller"]').attr('checked', false);
    $('.company_register_controller .corp_div_date_confirmation').hide();
});

$("#reg_cont_company_edit").click(function() {
    $(".company_register_controller").show();
    $(".individual_register_controller").hide();
    $('.individual_register_controller input[name="identification_no"]').prop("readonly", false);
    $('.individual_register_controller input').val('');
    $(".individual_register_controller .file_name").html("");
    $(".individual_register_controller .hidden_supporting_document").val("");
    $('input[name="individual_confirm_registrable_controller"]').attr('checked', false);
    $('.individual_register_controller .individual_div_date_confirmation').hide();
});

function formatDateROCFunc(date) {
    //console.log(date);
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function changeROCDateFormat(date)
{
    $array = date.split("/");
    $tmp = $array[0];
    $array[0] = $array[1];
    $array[1] = $tmp;
    $latest_date_format = $array[0]+"/"+$array[1]+"/"+$array[2];

    return $latest_date_format;
}

append_controller_table(client_controller);

function append_controller_table(client_controller)
{
    if(client_controller)
    {
        for(var i = 0; i < client_controller.length; i++)
        {
            // $b = '';
            // $b += '<tr class="tr_controller_table">';
            // $b += '<td style="text-align: center">'+ (client_controller[i]["identification_no"]!=null ? client_controller[i]["identification_no"] : client_controller[i]["register_no"] != null ? client_controller[i]["register_no"] : client_controller[i]["registration_no"]) +'</td>';
            // $b += '<td style="text-align: center">'+client_controller[i]["date_of_birth"]+'</td>';
            // $b += '<td rowspan="2" style="text-align: center">'+client_controller[i]["address"]+'</td>';
            // $b += '<td style="text-align: center">'+client_controller[i]["date_of_registration"]+'</td>';
            // $b += '<td style="text-align: center">'+client_controller[i]["confirmation_received_date"]+'</td>'; 
            // $b += '<td rowspan="2" style="text-align: center"'+client_controller[i]["date_of_cessation"]+'</td>';
            // $b += '<td rowspan="2"></td>';
            // $b += '</tr>';
            // $b += '<tr>';
            // $b += '<td style="text-align: center">'+ (client_controller[i]["company_name"]!=null ? client_controller[i]["company_name"] : client_controller[i]["name"] != null ? client_controller[i]["name"] : client_controller[i]["client_company_name"]) +'</td>';
            // $b += '<td style="text-align: center">'+client_controller[i]["nationality_name"]+'</td>';
            // $b += '<td style="text-align: center">'+client_controller[i]["date_of_notice"]+'</td>'; 
            // $b += '<td style="text-align: center">'+client_controller[i]["date_of_entry"]+'</td>';
            // $b += '</tr>';
            var search_filter_categoryposition = $("#search_filter_categoryposition").val();

            if(client_controller[i]["client_controller_field_type"] == "individual" && search_filter_categoryposition == "individual")
            {
                var selected_controller = client_controller[i];
            }
            else if((client_controller[i]["client_controller_field_type"] == "client" || client_controller[i]["client_controller_field_type"] == "company") && search_filter_categoryposition == "corporate")
            {
                var selected_controller = client_controller[i];
            }
            else if(search_filter_categoryposition == "all")
            {
                var selected_controller = client_controller[i];
            }
            else
            {
                var selected_controller = false;
            }

            if(selected_controller)
            {
                var full_address;

                if(selected_controller["supporting_document"] != "")
                {
                    var file_result = JSON.parse(selected_controller["supporting_document"]);
                }

                if(selected_controller["client_controller_field_type"] == "individual")
                {
                    // if(selected_controller["alternate_address"] == 1)
                    // {
                    //     full_address = address_format (selected_controller["postal_code2"], selected_controller["street_name2"], selected_controller["building_name2"], selected_controller["unit_no3"], selected_controller["unit_no4"]);
                    // }
                    // else
                    // {
                        full_address = address_format (selected_controller["postal_code1"], selected_controller["street_name1"], selected_controller["building_name1"], selected_controller["officer_unit_no1"], selected_controller["officer_unit_no2"], selected_controller["foreign_address1"], selected_controller["foreign_address2"], selected_controller["foreign_address3"]);
                    //}
                }
                else if(selected_controller["client_controller_field_type"] == "company")
                {
                    full_address = address_format (selected_controller["company_postal_code"], selected_controller["company_street_name"], selected_controller["company_building_name"], selected_controller["company_unit_no1"], selected_controller["company_unit_no2"], selected_controller["company_foreign_address1"], selected_controller["company_foreign_address2"], selected_controller["company_foreign_address3"]);
                }
                else if(selected_controller["client_controller_field_type"] == "client")
                {
                    full_address = address_format (selected_controller["postal_code"], selected_controller["street_name"], selected_controller["building_name"], selected_controller["client_unit_no1"], selected_controller["client_unit_no2"], selected_controller["foreign_add_1"], selected_controller["foreign_add_2"], selected_controller["foreign_add_3"]);
                }

                if(selected_controller["date_of_notice"] != "")
                {
                    var date_of_notice = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_notice"])));
                }
                else
                {
                    var date_of_notice = "";
                }

                if(selected_controller["confirmation_received_date"] != "")
                {
                    var confirmation_received_date = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["confirmation_received_date"])));
                }
                else
                {
                    var confirmation_received_date = "";
                }

                if(selected_controller["date_of_entry"] != "")
                {
                    var date_of_entry = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_entry"])));
                }
                else
                {
                    var date_of_entry = "";
                }

                $b = '';
                $b += '<tr class="tr_controller_table">';
                $b += '<td style="text-align: center">'+date_of_notice+'</td>';
                $b += '<td style="text-align: center">'+confirmation_received_date+'</td>'; 
                $b += '<td style="text-align: center">'+date_of_entry+'</td>';
                if(selected_controller["client_controller_field_type"] == "individual")
                {
                    var date_of_birth = formatDateROCFunc(new Date(selected_controller["date_of_birth"]));
                    var date_of_registration = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_registration"])));
                    if(selected_controller["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }

                    $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_controller["client_controller_id"]+'" class="open_edit_controller pointer">'+ selected_controller["name"] +'</a><br/><b>Alias:</b> '+selected_controller["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality: </b> '+selected_controller["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_controller["identification_no"] +'<br/><b>Date of birth: </b> '+date_of_birth +'<br/><b>Date of becoming a controller: </b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                else
                {
                    //var date_of_birth = formatDateFunc(new Date(selected_controller["date_of_birth"]));
                    var date_of_incorporation = (selected_controller["date_of_incorporation"] != null ? selected_controller["date_of_incorporation"] : (selected_controller["incorporation_date"] != null)?changeROCDateFormat(selected_controller["incorporation_date"]) : "");
                    if(date_of_incorporation != "")
                    {
                        var old_date_of_incorporation = new Date(date_of_incorporation);
                        var date_of_incorporation = formatDateROCFunc(old_date_of_incorporation);
                    }
                    var date_of_registration = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_registration"])));
                    if(selected_controller["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Name:</b> <a data-toggle="modal" data-id="'+selected_controller["client_controller_id"]+'" class="open_edit_controller pointer">'+ (selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"]) + '</a><br/><b>Unique entity number issued by the Registrar: </b>'+ (selected_controller["entity_issued_by_registrar"]!=null?selected_controller["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office: </b> '+full_address+ '<br/><b>Legal form: </b>'+ (selected_controller["legal_form_entity"]!=null?selected_controller["legal_form_entity"]:selected_controller["client_company_type"]!=null?selected_controller["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated: </b> '+(selected_controller["country_of_incorporation"]!=null?selected_controller["country_of_incorporation"]:selected_controller["client_country_of_incorporation"]!=null?selected_controller["client_country_of_incorporation"]:"") + (selected_controller["statutes_of"] != null ? ', ' + selected_controller["statutes_of"] : selected_controller["client_statutes_of"] != null ? ', ' + selected_controller["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated: </b>' + (selected_controller["coporate_entity_name"]!=null?selected_controller["coporate_entity_name"]:selected_controller["client_coporate_entity_name"]!=null?selected_controller["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated: </b> '+ (selected_controller["register_no"] != null ? selected_controller["register_no"] : selected_controller["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                if(selected_controller["supporting_document"] != "" && selected_controller["supporting_document"] != "[]")
                {
                    $b += '<td><a href="'+base_url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                }
                else
                {
                    $b += '<td></td>';
                }
                $b += '<td><input type="hidden" id="client_controller_id" value="'+selected_controller["client_controller_id"]+'" name="client_controller_id"/><input type="hidden" id="client_controller_name" value="'+((selected_controller["name"]!=null && selected_controller["name"]!=false) ? selected_controller["name"] : (selected_controller["officer_company_company_name"]!=null && selected_controller["officer_company_company_name"]!=false) ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"])+'" name="client_controller_name"/><input type="hidden" id="company_code" value="'+selected_controller["client_controller_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></td>';
                $b += '</tr>';

                $("#table_body_controller").append($b);
            }
        }
    }
    $( document ).ready(function() {
        $('#controller_table').DataTable();
    });
}

$( "#form_individual_register_of_controller, #form_company_register_of_controller" ).submit(function( e ) {
    e.preventDefault();
    var radioButtonValue = $('input[name="field_type"]:checked').val();
    var $form = $(e.target);

    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    if(valid_setup)
    {
        $('#loadingControllerMessage').show();

    // var $form = $(e.target);

    // var fv = $form.data('formValidation');
    // // Get the first invalid field
    // var $invalidFields = fv.getInvalidFields().eq(0);
    // // Get the tab that contains the first invalid field
    // var $tabPane     = $invalidFields.parents();
    // var valid_setup = fv.isValidContainer($tabPane);

    // if(valid_setup)
    // {
        // if($('#create_claim_service').css('display') != 'none')
        // {
            if(radioButtonValue == "Individual")
            {
                //console.log("individual");
                $('.individual_register_controller #identification_type').attr('disabled', false);

                var formData = new FormData($('#form_individual_register_of_controller')[0]);
                //formData.append('radio_individual_confirm_registrable_controller', $('input[name="individual_confirm_registrable_controller"]:checked').val());
            }
            else
            {
                //console.log("company");
                var formData = new FormData($('#form_company_register_of_controller')[0]);
                //formData.append('radio_corp_confirm_registrable_controller', $('input[name="corp_confirm_registrable_controller"]:checked').val());
            }
            
            //formData.append('user_name_text', $(".user_name option:selected").text());
            //$('#loadingPaymentVoucher').show();
            $.ajax({
                type: 'POST', //$form.serialize()
                url: "masterclient/add_controller",
                data: formData,
                dataType: 'json',
                // Tell jQuery not to process data or worry about content-type
                // You *must* include these options!
                // + '&user_name_text=' + $(".user_name option:selected").text()
                cache: false,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.individual_register_controller #identification_type').attr('disabled', true);
                    $('#loadingControllerMessage').hide();
                    if (response.Status === 1) 
                    {
                        var client_controller = response.list_of_controller;
                        $(".tr_controller_table").remove();
                        $('#controller_table').DataTable().clear();
                        $('#controller_table').DataTable().destroy();
                        append_controller_table(client_controller);
                        // if(client_controller.length > 0)
                        // {
                        //     for(var i = 0; i < client_controller.length; i++)
                        //     {
                        //         //var latest_effective_date_format = changeDateFormat(list_of_controller[i]["effective_date"]);

                        //         // $b=""; 
                        //         // $b += '<tr class="tr_letter_confirmation_to_auditor">';
                        //         // $b += '<td style="text-align: right">'+(i+1)+'</td>';
                        //         // $b += '<td><span style="display: none">'+ formatDateFunc(new Date(latest_effective_date_format)) + '</span>'+list_of_controller[i]["effective_date"]+'</td>';
                        //         // $b += '<td>'+list_of_controller[i]["transaction_task"]+'</td>';
                        //         // $b += '</tr>';

                        //         // $b = '';
                        //         // $b += '<tr class="tr_controller_table">';
                        //         // $b += '<td style="text-align: center">'+ (list_of_controller[i]["identification_no"]!=null ? list_of_controller[i]["identification_no"] : list_of_controller[i]["register_no"] != null ? list_of_controller[i]["register_no"] : list_of_controller[i]["registration_no"]) +'</td>';
                        //         // $b += '<td style="text-align: center">'+list_of_controller[i]["date_of_birth"]+'</td>';
                        //         // $b += '<td rowspan="2" style="text-align: center">'+list_of_controller[i]["address"]+'</td>';
                        //         // $b += '<td style="text-align: center">'+list_of_controller[i]["date_of_registration"]+'</td>';
                        //         // $b += '<td style="text-align: center">'+list_of_controller[i]["confirmation_received_date"]+'</td>'; 
                        //         // $b += '<td rowspan="2" style="text-align: center"'+list_of_controller[i]["date_of_cessation"]+'</td>';
                        //         // $b += '<td rowspan="2"></td>';
                        //         // $b += '</tr>';
                        //         // $b += '<tr>';
                        //         // $b += '<td style="text-align: center">'+ (list_of_controller[i]["company_name"]!=null ? list_of_controller[i]["company_name"] : list_of_controller[i]["name"] != null ? list_of_controller[i]["name"] : list_of_controller[i]["client_company_name"]) +'</td>';
                        //         // $b += '<td style="text-align: center">'+list_of_controller[i]["nationality_name"]+'</td>';
                        //         // $b += '<td style="text-align: center">'+list_of_controller[i]["date_of_notice"]+'</td>'; 
                        //         // $b += '<td style="text-align: center">'+list_of_controller[i]["date_of_entry"]+'</td>';
                        //         // $b += '</tr>';
                        //         var full_address;

                        //         if(client_controller[i]["supporting_document"] != "")
                        //         {
                        //             var file_result = JSON.parse(client_controller[i]["supporting_document"]);
                        //         }

                        //         if(client_controller[i]["client_controller_field_type"] == "individual")
                        //         {
                        //             if(client_controller[i]["alternate_address"] == 1)
                        //             {
                        //                 full_address = address_format (client_controller[i]["postal_code2"], client_controller[i]["street_name2"], client_controller[i]["building_name2"], client_controller[i]["unit_no3"], client_controller[i]["unit_no4"]);
                        //             }
                        //             else
                        //             {
                        //                 full_address = address_format (client_controller[i]["postal_code1"], client_controller[i]["street_name1"], client_controller[i]["building_name1"], client_controller[i]["officer_unit_no1"], client_controller[i]["officer_unit_no2"], client_controller[i]["foreign_address1"], client_controller[i]["foreign_address2"], client_controller[i]["foreign_address3"]);
                        //             }
                        //         }
                        //         else if(client_controller[i]["client_controller_field_type"] == "company")
                        //         {
                        //             full_address = address_format (client_controller[i]["company_postal_code"], client_controller[i]["company_street_name"], client_controller[i]["company_building_name"], client_controller[i]["company_unit_no1"], client_controller[i]["company_unit_no2"], client_controller[i]["company_foreign_address1"], client_controller[i]["company_foreign_address2"], client_controller[i]["company_foreign_address3"]);
                        //         }
                        //         else if(client_controller[i]["client_controller_field_type"] == "client")
                        //         {
                        //             full_address = address_format (client_controller[i]["postal_code"], client_controller[i]["street_name"], client_controller[i]["building_name"], client_controller[i]["client_unit_no1"], client_controller[i]["client_unit_no2"], client_controller[i]["foreign_add_1"], client_controller[i]["foreign_add_2"], client_controller[i]["foreign_add_3"]);
                        //         }

                        //         $b = '';
                        //         $b += '<tr class="tr_controller_table">';
                        //         $b += '<td style="text-align: center">'+client_controller[i]["date_of_notice"]+'</td>';
                        //         $b += '<td style="text-align: center">'+client_controller[i]["confirmation_received_date"]+'</td>'; 
                        //         $b += '<td style="text-align: center">'+client_controller[i]["date_of_entry"]+'</td>';
                        //         if(client_controller[i]["client_controller_field_type"] == "individual")
                        //         {
                        //             $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+client_controller[i]["client_controller_id"]+'" class="open_edit_controller pointer">'+ client_controller[i]["name"] +'</a><br/><b>Alias:</b> '+client_controller[i]["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+client_controller[i]["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ client_controller[i]["identification_no"] +'<br/><b>Date of birth:</b> '+client_controller[i]["date_of_birth"] +'<br/><b>Date of becoming a controller:</b> '+client_controller[i]["date_of_registration"]+'<br/><b>Date of cessation:</b>'+client_controller[i]["date_of_cessation"]+'</td>';
                        //         }
                        //         else
                        //         {
                        //             $b += '<td><b>Name:</b> <a data-toggle="modal" data-id="'+client_controller[i]["client_controller_id"]+'" class="open_edit_controller pointer">'+ (client_controller[i]["officer_company_company_name"]!=null ? client_controller[i]["officer_company_company_name"] : client_controller[i]["client_company_name"]) + '</a><br/><b>Unique entity number issued by the Registrar:</b>'+ client_controller[i]["entity_issued_by_registrar"]+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b>'+ client_controller[i]["legal_form_entity"] +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+client_controller[i]["nationality_name"] + (client_controller[i]["statutes_of"] != null ? ' ,' + client_controller[i]["statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + client_controller[i]["coporate_entity_name"] +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (client_controller[i]["register_no"] != null ? client_controller[i]["register_no"] : client_controller[i]["registration_no"]) +'<br/><b>Date of Incorpodation:</b> '+(client_controller[i]["date_of_incorporation"] != null ? client_controller[i]["date_of_incorporation"] : client_controller[i]["incorporation_date"]) +'<br/><b>Date of becoming a controller:</b> '+client_controller[i]["date_of_registration"]+'<br/><b>Date of cessation:</b>'+client_controller[i]["date_of_cessation"]+'</td>';
                        //         }
                        //         if(client_controller[i]["supporting_document"] != "")
                        //         {
                        //             $b += '<td><a href="'+base_url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                        //         }
                        //         else
                        //         {
                        //             $b += '<td></td>';
                        //         }
                        //         $b += '<td><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></td>';
                        //         $b += '</tr>';

                        //         $("#table_body_controller").append($b);
                        //     }
                        // }

                        $( document ).ready(function() {
                            $('#controller_table').DataTable();
                        });
                        $('.individual_register_controller input[name="identification_no"]').prop("readonly", false);
                        $('.company_register_controller input[name="controller_uen"]').prop("readonly", false);
                        $('#modal_controller').modal('toggle');
                    }
                    //console.log(response.Status);
                    // $('#loadingPaymentVoucher').hide();
                    // if (response.Status === 1) 
                    // {
                    //     toastr.success(response.message, response.title);
                    //     var getUrl = window.location;
                    //     var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/payment_voucher";
                    //     window.location.href = baseUrl;
                    // }
                }
            });
        //}
        // else
        // {
        //     toastr.error("Please set service engagement in this Vendor.", "error");
        // }
    //}
    }
});

// fix maximum call stack for rowsgroup
// $(document).on( 'destroy.dt', function ( e, settings ) {
//     var api = new $.fn.dataTable.Api( settings );
//     api.off('order.dt');
//     api.off('preDraw.dt');
//     api.off('column-visibility.dt');
//     api.off('search.dt');
//     api.off('page.dt');
//     api.off('length.dt');
//     api.off('xhr.dt');
// });

$(document).on('click',"#saveRegController",function(e){
    var radioButtonValue = $('input[name="field_type"]:checked').val();

    if(radioButtonValue == "Individual")
    {
        //console.log("individual");
        $("#form_individual_register_of_controller").submit();
    }
    else
    {
        //console.log("company");
        $("#form_company_register_of_controller").submit();
    }
});

$(document).on('change','#supporting_document',function(){
    var filename = "";
    //console.log(this.files[0]);
    for(var i = 0; i < this.files.length; i++)
    {
        if(i == 0)
        {
            filename = this.files[i].name;
        }
        else
        {
            filename = filename + ", " + this.files[i].name;
        }
    }
    $(this).parent().find(".file_name").html(filename);
    $(this).parent().find(".hidden_supporting_document").val("");
});

$(document).on('change','#corp_supporting_document',function(){
    var filename = "";
    //console.log(this.files[0]);
    for(var i = 0; i < this.files.length; i++)
    {
        if(i == 0)
        {
            filename = this.files[i].name;
        }
        else
        {
            filename = filename + ", " + this.files[i].name;
        }
    }
    $(this).parent().find(".corp_file_name").html(filename);
    $(this).parent().find(".corp_hidden_supporting_document").val("");
});

function open_edit_controller(controller_id) {
    $('#loadingControllerMessage').show();
    $.ajax({
        type: "POST",
        url: "masterclient/get_controller_info",
        data: {"controller_id":controller_id}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingControllerMessage').hide();
            if(response.status == 1)
            {
                $("#modal_controller input[name='field_type']").prop("disabled", true);
                var controller_info = response.list_of_controller;
                if(controller_info[0]['client_controller_field_type'] == "company")
                {
                    $(".company_register_controller").show();
                    $(".individual_register_controller").hide();
                    $("#reg_cont_company_edit").prop("checked", true);
                    $('.company_register_controller #company_code').val(controller_info[0]['client_controller_company_code']);
                    $('.company_register_controller #client_controller_id').val(controller_info[0]['client_controller_id']);
                    $('.company_register_controller input[name="controller_uen"]').val(controller_info[0]['register_no']);
                    $('.company_register_controller input[name="controller_uen"]').prop("readonly", true);
                    $('.company_register_controller input[name="entity_name"]').val(controller_info[0]['officer_company_company_name']);
                    $('.company_register_controller input[name="officer_id"]').val(controller_info[0]['client_controller_officer_id']);
                    $('.company_register_controller input[name="officer_field_type"]').val(controller_info[0]['client_controller_field_type']);
                    $('.company_register_controller .corp_view_edit_person').show();
                    $('.company_register_controller .corp_view_edit_person').attr("href", "personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(controller_info[0]['encrypt_register_no'])));

                    $('.company_register_controller input[name="date_appointed"]').val(controller_info[0]['date_of_registration']);
                    $('.company_register_controller input[name="date_ceased"]').val(controller_info[0]['date_of_cessation']);
                    $('.company_register_controller input[name="date_of_notice"]').val(controller_info[0]['date_of_notice']);
                    $('.company_register_controller input[name="date_of_entry"]').val(controller_info[0]['date_of_entry']);
                    if(controller_info[0]['is_confirm_by_reg_controller'] == "yes")
                    {
                        $("#corp_is_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').show();
                        $("#radio_corp_confirm_registrable_controller").val('yes');
                    }
                    else
                    {
                        $("#corp_not_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').hide();
                        $("#radio_corp_confirm_registrable_controller").val('no');
                    }
                    $('.company_register_controller input[name="date_confirmation"]').val(controller_info[0]['confirmation_received_date']);
                    if(controller_info[0]["supporting_document"] != "" && controller_info[0]["supporting_document"] != "[]")
                    {
                        var edit_file_result = JSON.parse(controller_info[0]["supporting_document"]);
                        var editfilename = "";

                        editfilename = '<a href="'+base_url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                        $(".company_register_controller .corp_file_name").html(editfilename);
                        $(".company_register_controller .corp_hidden_supporting_document").val(controller_info[0]["supporting_document"]);
                    }
                    else
                    {
                        $(".company_register_controller .corp_file_name").html("");
                        $(".company_register_controller .corp_hidden_supporting_document").val("");
                    }
                }
                else if(controller_info[0]['client_controller_field_type'] == "individual")
                {
                    $(".individual_register_controller").show();
                    $(".company_register_controller").hide();
                    $("#reg_cont_individual_edit").prop("checked", true);
                    $('.individual_register_controller #company_code').val(controller_info[0]['client_controller_company_code']);
                    $('.individual_register_controller #client_controller_id').val(controller_info[0]['client_controller_id']);
                    $('.individual_register_controller input[name="identification_no"]').val(controller_info[0]['identification_no']);
                    $('.individual_register_controller input[name="identification_no"]').prop("readonly", true);
                    $('.individual_register_controller #individual_controller_name').val(controller_info[0]['name']);
                    $('.individual_register_controller input[name="officer_id"]').val(controller_info[0]['client_controller_officer_id']);
                    $('.individual_register_controller input[name="officer_field_type"]').val(controller_info[0]['client_controller_field_type']);
                    $('.individual_register_controller .indi_view_edit_person').show();
                    $('.individual_register_controller .indi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(controller_info[0]['encrypt_identification_no'])));

                    $('.individual_register_controller input[name="date_appointed"]').val(controller_info[0]['date_of_registration']);
                    $('.individual_register_controller input[name="date_ceased"]').val(controller_info[0]['date_of_cessation']);
                    $('.individual_register_controller input[name="date_of_notice"]').val(controller_info[0]['date_of_notice']);
                    $('.individual_register_controller input[name="date_of_entry"]').val(controller_info[0]['date_of_entry']);
                    if(controller_info[0]['is_confirm_by_reg_controller'] == "yes")
                    {
                        $("#individual_is_confirm_registrable_controller").prop("checked", true);
                        $('.individual_register_controller .individual_div_date_confirmation').show();
                        $("#radio_individual_confirm_registrable_controller").val("yes");
                    }
                    else
                    {
                        $("#individual_not_confirm_registrable_controller").prop("checked", true);
                        $('.individual_register_controller .individual_div_date_confirmation').hide();
                        $("#radio_individual_confirm_registrable_controller").val("no");
                    }
                    $('.individual_register_controller input[name="date_confirmation"]').val(controller_info[0]['confirmation_received_date']);

                    if(controller_info[0]["supporting_document"] != "" && controller_info[0]["supporting_document"] != "[]")
                    {
                        var edit_file_result = JSON.parse(controller_info[0]["supporting_document"]);
                        var editfilename = "";
                        editfilename = '<a href="'+base_url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                        $(".individual_register_controller .file_name").html(editfilename);
                        $(".individual_register_controller .hidden_supporting_document").val(controller_info[0]["supporting_document"]);
                    }
                    else
                    {
                        $(".individual_register_controller .file_name").html("");
                        $(".individual_register_controller .hidden_supporting_document").val("");
                    }
                }
                else
                {
                    $(".company_register_controller").show();
                    $(".individual_register_controller").hide();
                    $("#reg_cont_company_edit").prop("checked", true);
                    $('.company_register_controller #company_code').val(controller_info[0]['client_controller_company_code']);
                    $('.company_register_controller #client_controller_id').val(controller_info[0]['client_controller_id']);
                    $('.company_register_controller input[name="controller_uen"]').val(controller_info[0]['registration_no']);
                    $('.company_register_controller input[name="controller_uen"]').prop("readonly", true);
                    $('.company_register_controller input[name="entity_name"]').val(controller_info[0]['client_company_name']);
                    $('.company_register_controller input[name="officer_id"]').val(controller_info[0]['client_controller_officer_id']);
                    $('.company_register_controller input[name="officer_field_type"]').val("client");
                    $('.company_register_controller .corp_view_edit_person').show();
                    $('.company_register_controller .corp_view_edit_person').attr("href", "masterclient/edit/"+controller_info[0]['client_id']);

                    $('.company_register_controller input[name="date_appointed"]').val(controller_info[0]['date_of_registration']);
                    $('.company_register_controller input[name="date_ceased"]').val(controller_info[0]['date_of_cessation']);
                    $('.company_register_controller input[name="date_of_notice"]').val(controller_info[0]['date_of_notice']);
                    $('.company_register_controller input[name="date_of_entry"]').val(controller_info[0]['date_of_entry']);
                    if(controller_info[0]['is_confirm_by_reg_controller'] == "yes")
                    {
                        $("#corp_is_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').show();
                        $("#radio_corp_confirm_registrable_controller").val('yes');
                    }
                    else
                    {
                        $("#corp_not_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').hide();
                        $("#radio_corp_confirm_registrable_controller").val('no');
                    }
                    $('.company_register_controller input[name="date_confirmation"]').val(controller_info[0]['confirmation_received_date']);
                    if(controller_info[0]["supporting_document"] != "" && controller_info[0]["supporting_document"] != "[]")
                    {
                        var edit_file_result = JSON.parse(controller_info[0]["supporting_document"]);
                        var editfilename = "";
                        editfilename = '<a href="'+base_url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                        $(".company_register_controller .corp_file_name").html(editfilename);
                        $(".company_register_controller .corp_hidden_supporting_document").val(controller_info[0]["supporting_document"]);
                    }
                    else
                    {
                        $(".company_register_controller .corp_file_name").html("");
                        $(".company_register_controller .corp_hidden_supporting_document").val("");
                    }
                }

                $("#modal_controller").modal("show");
            }
        }

    });
}

$(document).on("click", ".open_edit_controller", function () {
    var controller_id = $(this).data('id');
    console.log(controller_id);
    open_edit_controller(controller_id);
});


//Confirmation by Registrable Controller
$("#individual_is_confirm_registrable_controller").click(function() {
    $(".individual_div_date_confirmation").show();
    $('.individual_register_controller input[name="date_confirmation"]').val("");
    //console.log($(this).data('information'));
    $("#radio_individual_confirm_registrable_controller").val($(this).data('information'));
});

$("#individual_not_confirm_registrable_controller").click(function() {
    $(".individual_div_date_confirmation").hide();
    $('.individual_register_controller input[name="date_confirmation"]').val("");
    //console.log($(this).data('information'));
    $("#radio_individual_confirm_registrable_controller").val($(this).data('information'));
});

$("#corp_is_confirm_registrable_controller").click(function() {
    $(".corp_div_date_confirmation").show();
    $('.company_register_controller input[name="date_confirmation"]').val("");
    $("#radio_corp_confirm_registrable_controller").val($(this).data('information'));
});

$("#corp_not_confirm_registrable_controller").click(function() {
    $(".corp_div_date_confirmation").hide();
    $('.company_register_controller input[name="date_confirmation"]').val("");
    $("#radio_corp_confirm_registrable_controller").val($(this).data('information'));
});

//-----------------------------------------------old-------------------------------------------------------------

$("#gid_add_controller_officer").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_identification_register_no").html( "" );
});
$("#name").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_name").html( "" );
});
$("#date_of_birth").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_date_of_birth").html( "" );
});
$("#nationality").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_nationality").html( "" );
});
$("#controller_address").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
});
$("#date_of_registration").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_date_of_registration").html( "" );

    if($(this).parent().parent().parent('form').find('input[name="identification_register_no[]"]').val()!="")
    {
        if($(this).val() == "")
        {
            toastr.error("Date of registration must bigger or equal than date of inforporation.", "Error");
        }
    }
});
$("#date_of_notice").live('change',function(){
    $(this).parent().parent().parent('form').find("DIV#form_date_of_notice").html( "" );
});
$("#confirmation_received_date").live('change',function(){
    $(this).parent().parent().parent('form').find("DIV#form_confirmation_received_date").html( "" );
});
$("#date_of_entry").live('change',function(){
    $(this).parent().parent().parent('form').find("DIV#form_date_of_entry").html( "" );
});
$("#date_of_cessation").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_date_of_cessation").html( "" );
	$(this).parent().parent().parent('form').find("DIV#form_compare_date_of_cessation").html( "" );
});
// $("#add_office_person_link").click(function(){
//     console.log("in");
// });

function add_controller_person(elem)
{
    //------------new--------------------
    $(".refresh_controller").show();
    $(".refresh_a_controller").show();
    
    //--------------------------------
    jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]').val("");
    jQuery(elem).attr('hidden',"true");
}

function delete_controller(element)
{
    var tr = jQuery(element).parent().parent();

    var client_controller_id = tr.find('#client_controller_id').val();
    var delete_company_code = tr.find('#company_code').val();
    var client_controller_name = tr.find('#client_controller_name').val();
    // console.log("client_officer_id==="+client_controller_id);
    // console.log("delete_company_code==="+delete_company_code);
    bootbox.confirm("Are you confirm delete this record?", function (result) {
        if (result) 
        {    
            $('#loadingmessage').show();
            if(client_controller_id != undefined)
            {
                $(".tr_controller_table").remove();
                $('#controller_table').DataTable().clear();
                $('#controller_table').DataTable().destroy();
                $.ajax({ //Upload common input
                    url: "masterclient/delete_controller",
                    type: "POST",
                    data: {"client_controller_id": client_controller_id, "delete_company_code": delete_company_code, "client_controller_name": client_controller_name},
                    dataType: 'json',
                    success: function (response) {
                        //console.log(response.Status);
                        $('#loadingmessage').hide();
                        append_controller_table(response.list_of_controller);
                    }
                });
            }

            toastr.success("Updated Information.", "Updated");
        }
    });
}

function edit_controller(element)
{
	 //element.preventDefault();
	var tr = jQuery(element).parent().parent().parent();
	if(!tr.hasClass("editing")) 
	{
		tr.addClass("editing");
		tr.find("DIV.td").each(function()
		{
			if(!jQuery(this).hasClass("action"))
			{
				/*if(jQuery(this).find('input[name="name[]"]').val()=="")
				{
					jQuery(this).find("input").attr('disabled', false);
				}
				else
				{
					jQuery(this).find("input").attr('disabled', false);
				}*/
				//console.log(jQuery(this).find('input[name="guarantee[]"]'));
				jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', false);

				jQuery(this).find('input[name="date_of_birth[]"]').attr('disabled', false);
				
				
				jQuery(this).find('input[name="date_of_registration[]"]').attr('disabled', false);
                jQuery(this).find('input[name="date_of_notice[]"]').attr('disabled', false);
                jQuery(this).find('input[name="confirmation_received_date[]"]').attr('disabled', false);
                jQuery(this).find('input[name="date_of_entry[]"]').attr('disabled', false);
				jQuery(this).find('input[name="date_of_cessation[]"]').attr('disabled', false);
				jQuery(this).find("select").attr('disabled', false);
				
				//jQuery(this).find(".datepicker").datepicker('disable');
				//jQuery(this).text("");
				//jQuery(this).append('<input type="text" value="'+value+'" />');
			} 
			else 
			{
				jQuery(this).find(".submit_controller").text("Save");
			}
		});
	} 
	else 
	{
/*			var form_id = $(element).closest('form').attr('id');*/

		//console.log(tr.find('input[name="name[]"]').val()=="");

		// if(tr.find('textarea[name="address[]"]').val()=="" && tr.find('input[name="identification_register_no[]"]').val()=="" && tr.find('input[name="name[]"]').val()=="" && tr.find('input[name="nationality[]"]').val()=="" && tr.find('input[name="date_of_birth[]"]').val()=="" && tr.find('input[name="date_of_registration[]"]').val()=="" && tr.find('input[name="date_of_cessation[]"]').val()=="")
		// {
		// 	//console.log(jQuery(this).find('input[name="client_officer_id[]"]'));
		// 	var client_controller_id = tr.find('input[name="client_controller_id[]"]').val();
		// 	//console.log("client_officer_id==="+client_officer_id);
		// 	if(client_controller_id != undefined)
		// 	{
		// 		$.ajax({ //Upload common input
	 //                url: "masterclient/delete_controller",
	 //                type: "POST",
	 //                data: {"client_controller_id": client_controller_id},
	 //                dataType: 'json',
	 //                success: function (response) {
	 //                	console.log(response.Status);
	 //                }
	 //            });
		// 	}
		// 	tr.remove();
		// }
		// else
		// {
            tr.find('#controller_address').removeAttr("disabled");
			var frm = $(element).closest('form');

			var frm_serialized = frm.serialize();

            $.ajax({
                type: "POST",
                url: "masterclient/check_controller_data",
                data: frm_serialized, // <--- THIS IS THE CHANGE
                dataType: "json",
                async: false,
                success: function(response)
                {
                    if(response)
                    {
                        if (confirm('Do you want to submit?')) 
                        {
                            controller_submit(frm_serialized, tr);
                        } 
                        else 
                        {
                           return false;
                        }
                    }
                    else
                    {
                        controller_submit(frm_serialized, tr);
                    }
                }
            });

			//console.log(frm_serialized);
			
		//}
	}
}

function controller_submit(frm_serialized, tr)
{
    $('#loadingmessage').show();

        $.ajax({ //Upload common input
            url: "masterclient/add_controller",
            type: "POST",
            data: frm_serialized,
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();
                //console.log(response.Status);
                if (response.Status === 1) {
                    //var errorsDateOfCessation = ' ';
                    toastr.success(response.message, response.title);
                    //tr.find("DIV#form_date_of_cessation").html(" ");
                    if(response.insert_client_controller_id != null)
                    {
                        tr.find('input[name="client_controller_id[]"]').attr('value', response.insert_client_controller_id);
                    }
                    tr.removeClass("editing");

                    
                    tr.attr("data-registe_no",tr.find('input[name="identification_register_no[]"]').val());
                    tr.attr("data-name",tr.find('input[name="position[]"]').val());
                    tr.attr("data-date_of_birth",tr.find('input[name="date_of_birth[]"]').text());
                    tr.attr("data-nationality",tr.find('.nationality option:selected').text());
                    tr.attr("data-address",tr.find('textarea[name="address[]"]').val());
                    tr.attr("data-date_of_registration",tr.find('input[name="date_of_registration[]"]').val());
                    tr.attr("data-date_of_cessation",tr.find('input[name="date_of_cessation[]"]').val());
                    
                    /*tr.data('registe_no',tr.find('input[name="identification_register_no[]"]').val());
                    tr.data('name',tr.find('input[name="identification_register_no[]"]').val());*/
                    tr.find("DIV.td").each(function(){
                        if(!jQuery(this).hasClass("action")){

                            jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_birth[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_registration[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_notice[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="confirmation_received_date[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_entry[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_cessation[]"]').attr('disabled', true);
                            jQuery(this).find("textarea").attr('disabled', true);
                            jQuery(this).find("select").attr('disabled', true);

                            

                            
                        } else {
                            jQuery(this).find(".submit_controller").text("Edit");
                        }
                    });
                    tr.find("DIV#form_date_of_cessation").html("");
                    
                }
                else if (response.Status === 2)
                {
                    //console.log(response.data);
                    toastr.error(response.message, response.title);
                }
                else
                {
                    //console.log(tr.find("DIV#form_date_of_cessation"));
                    toastr.error(response.message, response.title);

                    if (response.error["identification_register_no"] != "")
                    {
                        var errorsIdentificationRegisterNo = '<span class="help-block">*' + response.error["identification_register_no"] + '</span>';
                        tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );

                    }
                    else
                    {
                        var errorsIdentificationRegisterNo = ' ';
                        tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );
                    }

                    if (response.error["name"] != "")
                    {
                        var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
                        tr.find("DIV#form_name").html( errorsName );

                    }
                    else
                    {
                        var errorsName = ' ';
                        tr.find("DIV#form_name").html( errorsName );
                    }

                    /*if (response.error["date_of_birth"] != "")
                    {
                        var errorsDateOfBirth = '<span class="help-block">*' + response.error["date_of_birth"] + '</span>';
                        tr.find("DIV#form_date_of_birth").html( errorsDateOfBirth );

                    }
                    else
                    {
                        var errorsDateOfBirth = ' ';
                        tr.find("DIV#form_date_of_birth").html( errorsDateOfBirth );
                    }*/

                    /*if (response.error["nationality"] != "")
                    {
                        var errorsNationality = '<span class="help-block">' + response.error["nationality"] + '</span>';
                        tr.find("DIV#form_nationality").html( errorsNationality );

                    }
                    else
                    {
                        var errorsNationality = ' ';
                        tr.find("DIV#form_nationality").html( errorsNationality );
                    }*/

                    if (response.error["address"] != "")
                    {
                        var errorsAddress = '<span class="help-block">*' + response.error["address"] + '</span>';
                        tr.find("DIV#form_controller_address").html( errorsAddress );

                    }
                    else
                    {
                        var errorsAddress = ' ';
                        tr.find("DIV#form_controller_address").html( errorsAddress );
                    }

                    if (response.error["date_of_registration"] != "")
                    {
                        var errorsDateOfRegistration = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_registration"] + '</span>';
                        tr.find("DIV#form_date_of_registration").html( errorsDateOfRegistration );

                    }
                    else
                    {
                        var errorsDateOfRegistration = ' ';
                        tr.find("DIV#form_date_of_registration").html( errorsDateOfRegistration );
                    }

                    if (response.error["date_of_notice"] != "")
                    {
                        var errorsDateOfNotice = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_notice"] + '</span>';
                        tr.find("DIV#form_date_of_notice").html( errorsDateOfNotice );

                    }
                    else
                    {
                        var errorsDateOfNotice = ' ';
                        tr.find("DIV#form_date_of_notice").html( errorsDateOfNotice );
                    }

                    if (response.error["confirmation_received_date"] != "")
                    {
                        var errorsConfirmationReceivedDate = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["confirmation_received_date"] + '</span>';
                        tr.find("DIV#form_confirmation_received_date").html( errorsConfirmationReceivedDate );

                    }
                    else
                    {
                        var errorsConfirmationReceivedDate = ' ';
                        tr.find("DIV#form_confirmation_received_date").html( errorsConfirmationReceivedDate );
                    }

                    if (response.error["date_of_entry"] != "")
                    {
                        var errorsDateOfEntry = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_entry"] + '</span>';
                        tr.find("DIV#form_date_of_entry").html( errorsDateOfEntry );

                    }
                    else
                    {
                        var errorsDateOfEntry = ' ';
                        tr.find("DIV#form_date_of_entry").html( errorsDateOfEntry );
                    }

                    if (response.error["date_of_cessation"] != "")
                    {
                        var errorsDateOfCessation = '<span class="help-block">*' + response.error["date_of_cessation"] + '</span>';
                        tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
                        tr.find("#date_of_cessation").val("");
                    }
                    else
                    {
                        var errorsDateOfCessation = ' ';
                        tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
                    }

                    /*if (response.error["compare_date_of_cessation"] != "")
                    {
                        var errorsCompareDateOfCessation = '<span class="help-block">*' + response.error["compare_date_of_cessation"] + '</span>';
                        tr.find("DIV#form_compare_date_of_cessation").html( errorsCompareDateOfCessation );

                    }
                    else
                    {
                        var errorsCompareDateOfCessation = ' ';
                        tr.find("DIV#form_compare_date_of_cessation").html( errorsCompareDateOfCessation );
                    }*/

                    
                }
            }
        });
}
$(document).ready(function(){
    $('#id_controller_header')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('registe_no').toString().toLowerCase()) < ($(a).data('registe_no').toString().toLowerCase()) ? 1 : -1;
				    }));
				});

				asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('registe_no').toString().toLowerCase()) < ($(a).data('registe_no').toString().toLowerCase()) ? -1 : 1;
				    }));
				});

				asc = true;
            }
            $('#guarantee_start_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_controller_name')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(name_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('name').toString().toLowerCase()) < ($(a).data('name').toString().toLowerCase()) ? 1 : -1;
				    }));
				});

				name_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('name').toString().toLowerCase()) < ($(a).data('name').toString().toLowerCase()) ? -1 : 1;
				    }));
				});

				name_asc = true;
            }
            $('#guarantee_start_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_address')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(address_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('address').toLowerCase()) < ($(a).data('address').toLowerCase()) ? 1 : -1;
				    }));
				});

				address_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('address').toLowerCase()) < ($(a).data('address').toLowerCase()) ? -1 : 1;
				    }));
				});

				address_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_date_of_birth')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(date_of_birth_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_birth')) < ($(a).data('date_of_birth')) ? 1 : -1;
				    }));
				});

				date_of_birth_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_birth')) < ($(a).data('date_of_birth')) ? -1 : 1;
				    }));
				});

				date_of_birth_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_nationality')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(nationality_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('nationality').toLowerCase()) < ($(a).data('nationality').toLowerCase()) ? 1 : -1;
				    }));
				});

				nationality_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('nationality').toLowerCase()) < ($(a).data('nationality').toLowerCase()) ? -1 : 1;
				    }));
				});

				nationality_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_date_of_registration')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(date_of_registration_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_registration').toLowerCase()) < ($(a).data('date_of_registration').toLowerCase()) ? 1 : -1;
				    }));
				});

				date_of_registration_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_registration').toLowerCase()) < ($(a).data('date_of_registration').toLowerCase()) ? -1 : 1;
				    }));
				});

				date_of_registration_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_date_of_cessation')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(date_of_cessation_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_cessation').toLowerCase()) < ($(a).data('date_of_cessation').toLowerCase()) ? 1 : -1;
				    }));
				});

				date_of_cessation_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_cessation').toLowerCase()) < ($(a).data('date_of_cessation').toLowerCase()) ? -1 : 1;
				    }));
				});

				date_of_cessation_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

});

function controller_change_date(latest_incorporation_date)
{
    $('#date_of_registration').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', latest_incorporation_date);

}

$('#search_filter_categoryposition').change(function(){
    refresh_controller();
});

$("#refresh_controller").click(function(){
    refresh_controller();
});

function refresh_controller()
{
    //$(".controller_sort_id").remove();
    $(".tr_controller_table").remove();
    $('#controller_table').DataTable().clear();
    $('#controller_table').DataTable().destroy();

    $.ajax({
        type: "POST",
        url: "masterclient/refresh_controller",
        data: {"company_code": company_code},
        asycn: false,
        dataType: "json",
        success: function(response){
            client_controller = response.info["client_controller"];
            append_controller_table(client_controller);
        }
    });
}

get_client_controller();

function get_client_controller()
{
	if(client_controller)
	{
		//console.log(client_controller);
		//console.log(client_officers[0]['name']);
		for(var i = 0; i < client_controller.length; i++)
		{
			$a="";
			$a += '<form class="tr controller_sort_id" method="post" name="form'+i+'" id="form'+i+'" data-date_of_cessation="'+client_controller[i]["date_of_cessation"]+'" data-date_of_registration="'+client_controller[i]["date_of_registration"]+'" data-nationality="'+client_controller[i]["nationality_name"]+'" data-date_of_birth="'+client_controller[i]["date_of_birth"]+'" data-address="'+client_controller[i]["address"]+'" data-registe_no="'+ (client_controller[i]["identification_no"]!=null ? client_controller[i]["identification_no"] : client_controller[i]["register_no"]) +'" data-name="'+ (client_controller[i]["company_name"]!=null ? client_controller[i]["company_name"] : client_controller[i]["name"]) +'">';
			$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+client_controller[i]["company_code"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value="'+client_controller[i]["id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value="'+client_controller[i]["officer_id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value="'+client_controller[i]["field_type"]+'"/></div>';
			$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (client_controller[i]["identification_no"]!=null ? client_controller[i]["identification_no"] : client_controller[i]["register_no"] != null ? client_controller[i]["register_no"] : client_controller[i]["registration_no"]) +'" id="gid_add_controller_officer" disabled="disabled"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this"><div style="cursor:pointer;height:62px;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (client_controller[i]["company_name"]!=null ? client_controller[i]["company_name"] : client_controller[i]["name"] != null ? client_controller[i]["name"] : client_controller[i]["client_company_name"]) +'" readonly/><div id="form_name"></div></div></div>';
			$a += '<div class="td"><div class="mb-md" style="width: 140px;"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control"  value="'+client_controller[i]["date_of_birth"]+'" readonly/></div><div class="mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="'+client_controller[i]["nationality_name"]+'" readonly/></div></div>';
			$a += '<div class="td"><div class="input-group" style="width: 170px;"><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" disabled="disabled">'+client_controller[i]["address"]+'</textarea></div><div id="form_controller_address"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_registration" name="date_of_registration[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_registration"]+'" disabled="disabled"></div><div id="form_date_of_registration"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_notice"]+'" disabled="disabled"></div><div id="form_date_of_notice"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="confirmation_received_date" name="confirmation_received_date[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["confirmation_received_date"]+'" disabled="disabled"></div><div id="form_confirmation_received_date"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_entry"]+'" disabled="disabled"></div><div id="form_date_of_entry"></div></div>';

			/*$a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_registration" name="date_of_registration[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_registration"]+'" disabled="disabled"></div><div id="form_date_of_registration"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["form_date_of_notice"]+'" disabled="disabled"></div><div id="form_date_of_notice"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="confirmation_received_date" name="confirmation_received_date[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["confirmation_received_date"]+'" disabled="disabled"></div><div id="form_confirmation_received_date"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_entry"]+'" disabled="disabled"></div><div id="form_date_of_entry"></div></div>';*/
			$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_cessation"]+'" disabled="disabled"></div><div id="form_date_of_cessation"></div><div id="form_compare_date_of_cessation"></div></div>';
			/*$a += '<div class="td">';
			$a += '<div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_end_date" name="guarantee_end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_guarantee_end_date"></div>';
			$a += '</div>';*/
            $a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_controller" onclick="edit_controller(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></div>';
			//$a += '<div class="td action" style="width: 140px;"><button type="button" class="btn btn-primary" onclick="edit_controller(this);">Edit</button><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div>';
			$a += '</form>';
				
				/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
			$("#body_controller").prepend($a);
			$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
			!function (i) {
				$.ajax({
					type: "POST",
					url: "masterclient/check_incorporation_date",
					data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
					dataType: "json",
					async: false,
					success: function(response)
					{
						//console.log("incorporation_date==="+response[0]["incorporation_date"]);
						$array = response[0]["incorporation_date"].split("/");
						$tmp = $array[0];
						$array[0] = $array[1];
						$array[1] = $tmp;
						//unset($tmp);
						$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
						//console.log(new Date($date_2));

						$latest_incorporation_date = new Date($date_2);
						$('#date_of_registration').datepicker({ 
						    dateFormat:'dd/mm/yyyy',
						}).datepicker('setStartDate', $latest_incorporation_date);
					}
				});
			} (i);

			/*!function (i) {
				$.ajax({
					type: "POST",
					url: "masterclient/get_nationality",
					data: {"nationality": client_controller[i]["nationality_id"]},
					dataType: "json",
					success: function(data){
			            $("#form"+i+" #nationality"+i+"").find("option:eq(0)").html("Select Nationality");
			            if(data.tp == 1){
			                $.each(data['result'], function(key, val) {
			                    var option = $('<option />');
			                    option.attr('value', key).text(val);
			                    if(data.selected_nationality != null && key == data.selected_nationality)
			                    {
			                        option.attr('selected', 'selected');
			                        
			                    }

			                    $("#form"+i+" #nationality"+i+"").append(option);
			                    
			                });
			            }
			            else{
			                alert(data.msg);
			            }
					}				
				});
			} (i);*/

			
		}
	}
}

if(client_controller)
{
	$count_controller = client_controller.length;
}
else
{
	$count_controller = 0;
}
$(document).on('click',"#controller_Add",function() {

	$count_controller++;
 	$a=""; 
 	/*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
	$a += '<form class="tr editing controller_sort_id" method="post" name="form'+$count_controller+'" id="form'+$count_controller+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value=""/></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="" id="gid_add_controller_officer"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;height:62px;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div></div></div>';
	$a += '<div class="td"><div class="mb-md" style="width: 140px;"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control" value="" readonly/></div><div class="mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="" readonly/></div></div>';
	$a += '<div class="td"><div class="input-group" style="width: 170px;"><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" readonly></textarea></div><div id="form_controller_address"></div></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_registration" name="date_of_registration[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_registration"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_notice"></div></div>';
    //$a += '<div class="td"><div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_notice"></div></div>';
    $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="confirmation_received_date" name="confirmation_received_date[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_confirmation_received_date"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_entry"></div></div>';
    //$a += '<div class="td"><div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_entry"></div></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_cessation"></div><div id="form_compare_date_of_cessation"></div></div>';
	/*$a += '<div class="td">';
	$a += '<div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_end_date" name="guarantee_end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_guarantee_end_date"></div>';
	$a += '</div>';*/
    $a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_controller" onclick="edit_controller(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></div>';
	//$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_controller(this);">Save</button><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div>';
	$a += '</form>';
	
	/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
	$("#body_controller").prepend($a); 
	
	//console.log(latest_incorporation_date);
    $('.datepicker').datepicker({ dateFormat:'dd/mm/yyyy'});

	$('#date_of_registration').datepicker({ 
	    dateFormat:'dd/mm/yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	/*!function ($count_controller) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_nationality",
			dataType: "json",
			success: function(data){
	            $("#form"+$count_controller+" #nationality"+$count_controller+"").find("option:eq(0)").html("Select Nationality");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    
	                    $("#form"+$count_controller+" #nationality"+$count_controller+"").append(option);
	                    
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($count_controller);*/

});
function refresh_person()
{
    var officer_frm = $("#gid_add_controller_officer");
    get_info(officer_frm);
}

//Search Officer
$("#gid_add_controller_officer").live('change',function(){
	var officer_frm = $(this);
    get_info(officer_frm);
});

function get_info(officer_frm)
{
    $('#loadingControllerMessage').show();
    $.ajax({
        type: "POST",
        url: "masterclient/get_guarantee_officer",
        data: {"identification_register_no":officer_frm.val()}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingControllerMessage').hide();
            $(".refresh_controller").hide();
            $(".refresh_a_controller").hide();
            //console.log(response);
            var controllerCategory = $('input[name="field_type"]:checked').val();
            if(response)
            {
                //--------------------new-----------------------
                if(response['field_type'] == "company")
                {
                    if(controllerCategory == "company")
                    {
                        $('.company_register_controller #company_code').val(company_code);
                        $('.company_register_controller input[name="entity_name"]').val(response['company_name']);
                        $('.company_register_controller input[name="officer_id"]').val(response['id']);
                        $('.company_register_controller input[name="officer_field_type"]').val(response['field_type']);
                        $('.company_register_controller .corp_view_edit_person').show();
                        $('.company_register_controller .corp_view_edit_person').attr("href", "personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(response['encrypt_register_no'])));
                        
                        if(response['date_of_incorporation'] != null)
                        {
                            var date_of_incorporation = (response['date_of_incorporation']).split('-');
                            response['date_of_incorporation'] = date_of_incorporation[2] + '/' + date_of_incorporation[1] + '/' + date_of_incorporation[0];
                        }
                        else
                        {
                            response['date_of_incorporation'] = "";
                        }
                        $('.company_register_controller input[name="date_of_birth"]').val(response['date_of_incorporation']);
                        $('.company_register_controller #nationality').val(response['country_of_incorporation']);

                        $('.company_register_controller #controller_address').val(address);
                    }
                    else
                    {
                        $('.company_register_controller input[name="entity_name"]').val("");
                        toastr.error("Please insert the correct UEN.", "Error");
                    }
                    $('#form_company_register_of_controller').formValidation('revalidateField', 'entity_name');
                }
                else if(response['field_type'] == "individual")
                {
                    if(controllerCategory == "Individual")
                    {
                        $('.individual_register_controller #company_code').val(company_code);
                        //$('.individual_register_controller #identification_type').val(response['identification_type']);
                        $('.individual_register_controller #individual_controller_name').val(response['name']);
                        //$('.individual_register_controller #aliases').val(response['alias']);
                        $('.individual_register_controller input[name="officer_id"]').val(response['id']);
                        $('.individual_register_controller input[name="officer_field_type"]').val(response['field_type']);
                        $('.individual_register_controller .indi_view_edit_person').show();
                        $('.individual_register_controller .indi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(response['encrypt_identification_no'])));

                        var date_birth = (response['date_of_birth']).split('-');
                        response['date_of_birth'] = date_birth[2] + '/' + date_birth[1] + '/' + date_birth[0];

                        $('.individual_register_controller input[name="date_of_birth"]').val(response['date_of_birth']);
                        $('.individual_register_controller #nationality').val(response['nationality_name']);

                        if(response["postal_code1"] != "" && response["street_name1"] != "")
                        {
                            var units = "", buildingName = "";

                            if(response["unit_no1"] != "" || response["unit_no2"] != "")
                            {
                                units = ', #'+response["unit_no1"] + " - " + response["unit_no2"];
                            }
                            else if(response["unit_no1"] != "")
                            {
                                units = response["unit_no1"]+' ';
                            }
                            else if(response["unit_no2"] != "")
                            {
                                units = response["unit_no2"]+' ';
                            }
                            else
                            {
                                units = '';
                            }

                            if(response["building_name1"] != "")
                            {
                                if(units == '')
                                {
                                    buildingName = ', ' + response["building_name1"];
                                }
                                else
                                {
                                    buildingName = ' ' + response["building_name1"];
                                }
                            }
                            else
                            {
                                buildingName = '';
                            }
                            var address = response["street_name1"]+units+buildingName+', SINGAPORE '+response["postal_code1"];
                        }
                        else if(response["foreign_address1"] != "" )
                        {
                            var address = response["foreign_address1"];

                            if(response["foreign_address2"] != "")
                            {
                                address = address + ', ' + response["foreign_address2"];
                            }

                            if(response["foreign_address3"] != "")
                            {
                                address = address + ', ' + response["foreign_address3"];
                            }
                        }
                        $('.individual_register_controller #controller_address').val(address);
                    }
                    else
                    {
                        $('.individual_register_controller #individual_controller_name').val("");
                        toastr.error("Please insert the correct Identification No.", "Error");
                    }
                    $('#form_individual_register_of_controller').formValidation('revalidateField', 'individual_controller_name');
                }
                else
                {
                    if(controllerCategory == "company")
                    {
                        $('.company_register_controller #company_code').val(company_code);
                        $('.company_register_controller input[name="entity_name"]').val(response['company_name']);
                        $('.company_register_controller input[name="officer_id"]').val(response['id']);
                        $('.company_register_controller input[name="officer_field_type"]').val("client");
                        $('.company_register_controller .corp_view_edit_person').show();
                        $('.company_register_controller .corp_view_edit_person').attr("href", "masterclient/edit/"+response['client_id']);
                    }
                    else
                    {
                        $('.company_register_controller input[name="entity_name"]').val("");
                        toastr.error("Please insert the correct UEN.", "Error");
                    }
                    $('#form_company_register_of_controller').formValidation('revalidateField', 'entity_name');
                }
                //------------------------------------------
                if(response['field_type'] == "company")
                {
                    //console.log(data['field_type'] == "company");
                    officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_id"]').val(response['id']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);

                    if(response['date_of_incorporation'] != null)
                    {
                        var date_of_incorporation = (response['date_of_incorporation']).split('-');
                        response['date_of_incorporation'] = date_of_incorporation[2] + '/' + date_of_incorporation[1] + '/' + date_of_incorporation[0];
                    }
                    else
                    {
                        response['date_of_incorporation'] = "";
                    }

                    officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val(response['date_of_incorporation']);
                    officer_frm.parent().parent().parent('form').find('.nationality').val(response['country_of_incorporation']);

                    if(response["company_postal_code"] != "" && response["company_street_name"] != "")
                    {
                        // if(response["company_unit_no1"] != "" || response["company_unit_no2"] != "")
                        // {
                        //  var unit = ' #'+response["company_unit_no1"] +' - '+response["company_unit_no2"];
                        // }
                        // else
                        // {
                        //  var unit = "";
                        // }

                        var units = "", buildingName = "";

                        if(response["company_unit_no1"] != "" || response["company_unit_no2"] != "")
                        {
                            units = ', #'+response["company_unit_no1"] + " - " + response["company_unit_no2"];
                        }
                        else if(response["company_unit_no1"] != "")
                        {
                            units = response["company_unit_no1"]+' ';
                        }
                        else if(response["company_unit_no2"] != "")
                        {
                            units = response["company_unit_no2"]+' ';
                        }
                        else
                        {
                            units = '';
                        }
                        if(response["company_building_name"] != "")
                        {
                            if(units == '')
                            {
                                buildingName = ', ' + response["company_building_name"];
                            }
                            else
                            {
                                buildingName = ' ' + response["company_building_name"];
                            }
                        }
                        else
                        {
                            buildingName = '';
                        }
                        var address = response["company_street_name"]+units+buildingName+', SINGAPORE '+response["company_postal_code"];
                        //var address = response["company_street_name"]+ unit +' '+response["company_building_name"]+' Singapore '+response["company_postal_code"];
                        officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
                    }
                    else if(response["company_foreign_address1"] != null )
                    {
                        var address = response["company_foreign_address1"];

                        if(response["company_foreign_address2"] != "")
                        {
                            address = address + ', ' + response["company_foreign_address2"];
                        }

                        if(response["company_foreign_address3"] != "")
                        {
                            address = address + ', ' + response["company_foreign_address3"];
                        }
                        officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
                    }
                    officer_frm.parent().parent().parent('form').find('#controller_address').val(address);

                    if(response['company_name'] != undefined)
                    {
                        //officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
                        officer_frm.parent().parent().parent('form').find("DIV#form_name").html( "" );
                        //officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
                    }
                }
                else if(response['field_type'] == "individual")
                {
                    //console.log(officer_frm.parent().parent().parent('form'));
                    officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val(response['name']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_id"]').val(response['id']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);

                    var date_birth = (response['date_of_birth']).split('-');
                    response['date_of_birth'] = date_birth[2] + '/' + date_birth[1] + '/' + date_birth[0];

                    officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val(response['date_of_birth']);
                    officer_frm.parent().parent().parent('form').find('.nationality').val(response['nationality_name']);

                    if(response["postal_code1"] != "" && response["street_name1"] != "")
                    {
                        // if(response["unit_no1"] != "" || response["unit_no2"] != "")
                        // {
                        //  var unit = ' #'+response["unit_no1"] +' - '+response["unit_no2"];
                        // }
                        // else
                        // {
                        //  var unit = "";
                        // }

                        var units = "", buildingName = "";

                        if(response["unit_no1"] != "" || response["unit_no2"] != "")
                        {
                            units = ', #'+response["unit_no1"] + " - " + response["unit_no2"];
                        }
                        else if(response["unit_no1"] != "")
                        {
                            units = response["unit_no1"]+' ';
                        }
                        else if(response["unit_no2"] != "")
                        {
                            units = response["unit_no2"]+' ';
                        }
                        else
                        {
                            units = '';
                        }

                        if(response["building_name1"] != "")
                        {
                            if(units == '')
                            {
                                buildingName = ', ' + response["building_name1"];
                            }
                            else
                            {
                                buildingName = ' ' + response["building_name1"];
                            }
                        }
                        else
                        {
                            buildingName = '';
                        }
                        var address = response["street_name1"]+units+buildingName+', SINGAPORE '+response["postal_code1"];
                        //var address = response["street_name1"]+ unit +' '+response["building_name1"]+' Singapore '+response["postal_code1"];
                        officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
                    }
                    else if(response["foreign_address1"] != "" )
                    {
                        var address = response["foreign_address1"];

                        if(response["foreign_address2"] != "")
                        {
                            address = address + ', ' + response["foreign_address2"];
                        }

                        if(response["foreign_address3"] != "")
                        {
                            address = address + ', ' + response["foreign_address3"];
                        }

                        officer_frm.parent().parent().parent().find("DIV#form_controller_address").html( "" );
                    }
                    officer_frm.parent().parent().parent('form').find('#controller_address').val(address);

                    if(response['name'] != undefined)
                    {
                        //officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
                        officer_frm.parent().parent().parent('form').find("DIV#form_name").html( "" );
                    }
                }
                else
                {
                    officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_id"]').val(response['id']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_field_type"]').val("client");
                    //console.log(response);
                    officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val(response['incorporation_date']);
                    officer_frm.parent().parent().parent('form').find('.nationality').val("");

                    if(response["postal_code"] != "" && response["street_name"] != "")
                    {
                        // if(response["unit_no1"] != "" || response["unit_no2"] != "")
                        // {
                        //     var unit = ' #'+response["unit_no1"] +' - '+response["unit_no2"];
                        // }
                        // else
                        // {
                        //     var unit = "";
                        // }
                        // var address = response["street_name"]+ unit +' '+response["building_name"]+' Singapore '+response["postal_code"];
                        
                        var units = "", buildingName = "";

                        if(response["unit_no1"] != "" || response["unit_no2"] != "")
                        {
                            units = ', #'+response["unit_no1"] + " - " + response["unit_no2"];
                        }
                        else if(response["unit_no1"] != "")
                        {
                            units = response["unit_no1"]+' ';
                        }
                        else if(response["unit_no2"] != "")
                        {
                            units = response["unit_no2"]+' ';
                        }
                        else
                        {
                            units = '';
                        }

                        if(response["building_name"] != "")
                        {
                            if(units == '')
                            {
                                buildingName = ', ' + response["building_name"];
                            }
                            else
                            {
                                buildingName = ' ' + response["building_name"];
                            }
                        }
                        else
                        {
                            buildingName = '';
                        }
                        var address = response["street_name"]+units+buildingName+', SINGAPORE '+response["postal_code"];

                        officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
                    }
                    officer_frm.parent().parent().parent('form').find('#controller_address').val(address);

                    if(response['company_name'] != undefined)
                    {
                        //officer_frm.parent().parent().find('input[name="name[]"]').attr('readOnly', true);
                        officer_frm.parent().parent().parent('form').find("DIV#form_name").html( "" );
                    }
                }
                //---------------------new---------------------
                $('.add_office_person_link').attr('hidden',"true");
                //---------------------------------------------
                officer_frm.parent().parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
            }
            else
            {
                //---------------------new---------------------
                
                if(controllerCategory == "company")
                {
                    $('.company_register_controller .add_office_person_link').removeAttr('hidden');
                    $('.company_register_controller input[name="entity_name"]').val("");
                    $('#form_company_register_of_controller').formValidation('revalidateField', 'entity_name');
                    $('.company_register_controller .corp_view_edit_person').hide();
                }
                else if(controllerCategory == "Individual")
                {
                    $('.individual_register_controller .add_office_person_link').removeAttr('hidden');
                    $('.individual_register_controller #individual_controller_name').val("");
                    $('#form_individual_register_of_controller').formValidation('revalidateField', 'individual_controller_name');
                    $('.individual_register_controller .indi_view_edit_person').hide();
                }
                //---------------------------------------------
                officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val("");
                //officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', false);
                officer_frm.parent().parent().parent('form').find('input[name="officer_id"]').val("");
                officer_frm.parent().parent().parent('form').find('input[name="officer_field_type"]').val("");
                officer_frm.parent().parent().parent('form').find('a#add_office_person_link').removeAttr('hidden');

                officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val("");
                officer_frm.parent().parent().parent('form').find('.nationality').val("");
                officer_frm.parent().parent().parent('form').find('#controller_address').val("");
            }
        }               
    });
}


function address_format (postal_code, street_name, building_name, unit_no1, unit_no2, foreign_add_1 = null, foreign_add_2 = null, foreign_add_3 = null)
{
    if(postal_code != "" && street_name != "")
    {
        var units = "", buildingName = "";

        if(unit_no1 != "" || unit_no2 != "")
        {
            units = ', #'+unit_no1 + " - " + unit_no2;
        }
        else if(unit_no1 != "")
        {
            units = unit_no1+' ';
        }
        else if(unit_no2 != "")
        {
            units = unit_no2+' ';
        }
        else
        {
            units = '';
        }
        if(building_name != "")
        {
            if(units == '')
            {
                buildingName = ', ' + building_name;
            }
            else
            {
                buildingName = ' ' + building_name;
            }
        }
        else
        {
            buildingName = '';
        }
        var address = street_name+units+buildingName+', SINGAPORE '+postal_code;
    }
    else if(foreign_add_1 != null )
    {
        var address = foreign_add_1;

        if(foreign_add_2 != "")
        {
            address = address + ', ' + foreign_add_2;
        }

        if(foreign_add_3 != "")
        {
            address = address + ', ' + foreign_add_3;
        }
    }

    return address;
}

