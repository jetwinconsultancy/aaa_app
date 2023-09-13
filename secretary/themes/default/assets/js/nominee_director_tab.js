var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';
$(".nd_add_office_person_link").attr("href", url + 'personprofile/add/1');
$(".nomi_add_office_person_link").attr("href", url + 'personprofile/add/1');

//Nominee Director
$('#form_register_of_nominee_director').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        nd_identification_no: {
            row: '.div_nd_id',
            validators: {
                notEmpty: {
                    message: 'The Identification No. field is required.'
                }
            }
        },
        nd_name: {
            row: '.div_nd_name',
            validators: {
                notEmpty: {
                    message: 'The Name field is required.'
                }
            }
        },
        nd_date_entry: {
            row: '.div_nd_date_entry',
            validators: {
                notEmpty: {
                    message: 'The Date of entry/update field is required.'
                }
            }
        },
        nomi_identification_no: {
            row: '.div_nomi_id',
            validators: {
                notEmpty: {
                    message: 'The Identification No. field is required.'
                }
            }
        },
        nomi_name: {
            row: '.div_nomi_name',
            validators: {
                notEmpty: {
                    message: 'The Name field is required.'
                }
            }
        },
        date_become_nominator: {
            row: '.div_date_become_nominator',
            validators: {
                notEmpty: {
                    message: 'The Date on which the person becomes a nominator field is required.'
                }
            }
        },
    }
});

$('#form_register_of_nominee_director #nd_date_entry').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_date_entry');
});

$('#form_register_of_nominee_director #date_become_nominator').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#form_register_of_nominee_director').formValidation('revalidateField', 'date_become_nominator');
});

//-----------------open modal -------------------------
function open_nominee_director()
{
    $('.register_of_nominee_director input').val('');
    $(".register_of_nominee_director .file_name").html("");
    $(".register_of_nominee_director .hidden_supporting_document").val("");

    $('.register_of_nominee_director .nd_view_edit_person').hide();
    $('.register_of_nominee_director .nomi_view_edit_person').hide();

    $("#modal_nominee_director").modal("show");
}

$("#create_nominee_director").click(function()
{
    open_nominee_director();
});
//-----------------------------------------------------

function nd_refresh_controller()
{
    var officer_frm = $("#nd_gid_add_controller_officer");
    nd_get_info(officer_frm);
}

//Search Officer
$("#nd_gid_add_controller_officer").live('change',function(){
	var officer_frm = $(this);
    nd_get_info(officer_frm);
});

function nd_get_info(officer_frm)
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
            //console.log(response);
            if(response)
            {
                //--------------------new-----------------------
				if(response['field_type'] == "individual")
                {
                    $('.register_of_nominee_director #nomi_company_code').val(company_code);
                    $('.register_of_nominee_director #nd_name').val(response['name']);
                    $('.register_of_nominee_director input[name="nd_officer_id"]').val(response['id']);
                    $('.register_of_nominee_director input[name="nd_officer_field_type"]').val(response['field_type']);
                    $('.register_of_nominee_director .nd_view_edit_person').show();
                    $('.register_of_nominee_director .nd_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(response['encrypt_identification_no'])));
                    
                    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_name');
                }
                else
                {
                    $('.register_of_nominee_director input[name="nd_name"]').val("");
                    toastr.error("Please insert the correct Identification No.", "Error");
                }
                //------------------------------------------
                
                //---------------------new---------------------
                $('.nd_add_office_person_link').attr('hidden',"true");
                $('.nd_refresh_controller').attr('hidden',"true");
                $('.nd_a_refresh_controller').attr('hidden',"true");
                //---------------------------------------------
            }
            else
            {
                //---------------------new---------------------
                $('.register_of_nominee_director .nd_add_office_person_link').removeAttr('hidden');
                $('.register_of_nominee_director #nd_name').val("");
                $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_name');
                $('.register_of_nominee_director .nd_view_edit_person').hide();
                
                //---------------------------------------------
            }
        }               
    });
}

function nomi_refresh_controller()
{
    var officer_frm = $("#nomi_gid_add_controller_officer");
    nd_get_info(officer_frm);
}

//Search Officer
$("#nomi_gid_add_controller_officer").live('change',function(){
	var officer_frm = $(this);
    nomi_get_info(officer_frm);
});

function nomi_get_info(officer_frm)
{
    $('#loadingControllerMessage').show();
    $.ajax({
        type: "POST",
        url: "masterclient/get_guarantee_officer",
        data: {"identification_register_no":officer_frm.val()}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingControllerMessage').hide();
            $(".nomi_refresh_controller").hide();
            $(".nomi_a_refresh_controller").hide();
            //console.log(response);
            if(response)
            {
                //--------------------new-----------------------
				if(response['field_type'] == "company")
                {
                    $('.register_of_nominee_director #nomi_company_code').val(company_code);
                    $('.register_of_nominee_director #nomi_name').val(response['company_name']);
                    $('.register_of_nominee_director input[name="nomi_officer_id"]').val(response['id']);
                    $('.register_of_nominee_director input[name="nomi_officer_field_type"]').val(response['field_type']);
                    $('.register_of_nominee_director .nomi_view_edit_person').show();
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(response['encrypt_register_no'])));

                    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
                }
                else if(response['field_type'] == "individual")
                {
                    $('.register_of_nominee_director #nomi_company_code').val(company_code);
                    $('.register_of_nominee_director #nomi_name').val(response['name']);
                    $('.register_of_nominee_director input[name="nomi_officer_id"]').val(response['id']);
                    $('.register_of_nominee_director input[name="nomi_officer_field_type"]').val(response['field_type']);
                    $('.register_of_nominee_director .nomi_view_edit_person').show();
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(response['encrypt_identification_no'])));
                    
                    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
                }
                else
                {
                    $('.register_of_nominee_director #nomi_company_code').val(company_code);
                    $('.register_of_nominee_director #nomi_name').val(response['company_name']);
                    $('.register_of_nominee_director input[name="nomi_officer_id"]').val(response['id']);
                    $('.register_of_nominee_director input[name="nomi_officer_field_type"]').val("client");
                    $('.register_of_nominee_director .nomi_view_edit_person').show();
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "masterclient/edit/"+response['client_id']);
                    
                    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
                }
                //------------------------------------------
                
                //---------------------new---------------------
                $('.nomi_add_office_person_link').attr('hidden',"true");
                $('.nomi_refresh_controller').attr('hidden',"true");
                $('.nomi_a_refresh_controller').attr('hidden',"true");
                //---------------------------------------------
            }
            else
            {
                //---------------------new---------------------
                $('.register_of_nominee_director .nomi_add_office_person_link').removeAttr('hidden');
                $('.register_of_nominee_director #nomi_name').val("");
                $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
                $('.register_of_nominee_director .nomi_view_edit_person').hide();
                
                //---------------------------------------------
            }
        }               
    });
}

$( "#form_register_of_nominee_director" ).submit(function( e ) {
    e.preventDefault();
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

        var formData = new FormData($('#form_register_of_nominee_director')[0]);
        //formData.append('radio_individual_confirm_registrable_controller', $('input[name="individual_confirm_registrable_controller"]:checked').val());
        
        $.ajax({
            type: 'POST', //$form.serialize()
            url: "masterclient/add_nominee_director",
            data: formData,
            dataType: 'json',
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            // + '&user_name_text=' + $(".user_name option:selected").text()
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){

                $('#loadingControllerMessage').hide();
                if (response.Status === 1) 
                {
                    var list_of_nominee_director = response.list_of_nominee_director;
                    $(".tr_nominee_director_table").remove();
                    $('#nominee_director_table').DataTable().clear();
                    $('#nominee_director_table').DataTable().destroy();
                    append_nominee_director_table(list_of_nominee_director);

                    $('.register_of_nominee_director input[name="nd_identification_no"]').prop("readonly", false);
                    $('.register_of_nominee_director input[name="nomi_identification_no"]').prop("readonly", false);
                    $('#modal_nominee_director').modal('toggle');
                }
            }
        });
    }
});

$(document).on('click',"#saveRegNomineeDirector",function(e){
    $("#form_register_of_nominee_director").submit();
});

$(document).on('change','#nd_supporting_document',function(){
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
    $(this).parent().find(".nd_file_name").html(filename);
    $(this).parent().find(".nd_hidden_supporting_document").val("");
});

function formatDateRONDFunc(date) {
    //console.log(date);
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = date.getDate();
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function changeRONDDateFormat(date)
{
    $array = date.split("/");
    $tmp = $array[0];
    $array[0] = $array[1];
    $array[1] = $tmp;
    $latest_date_format = $array[0]+"/"+$array[1]+"/"+$array[2];

    return $latest_date_format;
}

append_nominee_director_table(client_nominee_director);

function append_nominee_director_table(client_nominee_director)
{
    if(client_nominee_director)
    {
        for(var i = 0; i < client_nominee_director.length; i++)
        {
            var selected_nominee_director = client_nominee_director[i];

            if(selected_nominee_director)
            {
                var full_address;

                if(selected_nominee_director["supporting_document"] != "")
                {
                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
                }

                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    // if(selected_nominee_director["alternate_address"] == 1)
                    // {
                    //     full_address = address_format (selected_nominee_director["postal_code2"], selected_nominee_director["street_name2"], selected_nominee_director["building_name2"], selected_nominee_director["unit_no3"], selected_nominee_director["unit_no4"]);
                    // }
                    // else
                    // {
                        full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
                    //}
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
                {
                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
                {
                   full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
                }

                if(selected_nominee_director["nd_date_entry"] != "")
                {
                    var nd_date_entry = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["nd_date_entry"])));
                }
                else
                {
                    var nd_date_entry = "";
                }

                $b = '';
                $b += '<tr class="tr_nominee_director_table">';
                $b += '<td style="text-align: center">'+nd_date_entry+'</td>';
                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
                
                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    var date_of_birth = formatDateRONDFunc(new Date(selected_nominee_director["date_of_birth"]));
                    var date_become_nominator = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a><br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                else
                {
                    var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDDateFormat(selected_nominee_director["incorporation_date"]) : "");
                    if(date_of_incorporation != "")
                    {
                        var old_date_of_incorporation = new Date(date_of_incorporation);
                        var date_of_incorporation = formatDateRONDFunc(old_date_of_incorporation);
                    }
                    var date_become_nominator = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" class="open_edit_nominee_director pointer">'+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '</a><br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b>'+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }

                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
                {
                    $b += '<td><a href="'+base_url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                }
                else
                {
                    $b += '<td></td>';
                }
                $b += '<td><input type="hidden" id="client_nominee_director_id" value="'+selected_nominee_director["client_nominee_director_id"]+'" name="client_nominee_director_id"/><input type="hidden" id="client_nominee_director_name" value="'+selected_nominee_director["nd_officer_name"]+'" name="client_nominee_director_name"/><input type="hidden" id="company_code" value="'+selected_nominee_director["client_nominee_director_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_nominee_director(this);">Delete</button></td>';
                $b += '</tr>';

                $("#table_body_nominee_director").append($b);
            }
        }
    }
    $( document ).ready(function() {
        $('#nominee_director_table').DataTable();
    });
}

function open_edit_nominee_director(nominee_director_id) {
    $('#loadingControllerMessage').show();
    $.ajax({
        type: "POST",
        url: "masterclient/get_nominee_director_info",
        data: {"nominee_director_id":nominee_director_id}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingControllerMessage').hide();
            if(response.status == 1)
            {
                var nominee_director_info = response.list_of_nominee_director;

                $('.register_of_nominee_director #nomi_company_code').val(nominee_director_info[0]['client_nominee_director_company_code']);
                $('.register_of_nominee_director #client_nominee_director_id').val(nominee_director_info[0]['client_nominee_director_id']);
                $('.register_of_nominee_director input[name="nd_identification_no"]').val(nominee_director_info[0]['nd_officer_identification_no']);
                $('.register_of_nominee_director input[name="nd_identification_no"]').prop("readonly", true);
                $('.register_of_nominee_director #nd_name').val(nominee_director_info[0]['nd_officer_name']);
                $('.register_of_nominee_director input[name="nd_officer_id"]').val(nominee_director_info[0]['nd_officer_id']);
                $('.register_of_nominee_director input[name="nd_officer_field_type"]').val(nominee_director_info[0]['nd_officer_field_type']);
                $('.register_of_nominee_director .nd_view_edit_person').show();
                $('.register_of_nominee_director .nd_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(nominee_director_info[0]['encrypt_nd_identification_no'])));
                $('.register_of_nominee_director input[name="nd_date_entry"]').val(nominee_director_info[0]['nd_date_entry']);
                

                if(nominee_director_info[0]['nomi_officer_field_type'] == "company")
                {
                    $('.register_of_nominee_director input[name="nomi_identification_no"]').val(nominee_director_info[0]['register_no']);
                    $('.register_of_nominee_director #nomi_name').val(nominee_director_info[0]['officer_company_company_name']);
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(nominee_director_info[0]['encrypt_register_no'])));
                }
                else if(nominee_director_info[0]['nomi_officer_field_type'] == "individual")
                {
                    $('.register_of_nominee_director input[name="nomi_identification_no"]').val(nominee_director_info[0]['identification_no']);
                    $('.register_of_nominee_director #nomi_name').val(nominee_director_info[0]['name']);
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(nominee_director_info[0]['encrypt_identification_no'])));
                }
                else
                {
                    $('.register_of_nominee_director input[name="nomi_identification_no"]').val(nominee_director_info[0]['registration_no']);
                    $('.register_of_nominee_director #nomi_name').val(nominee_director_info[0]['client_company_name']);
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "masterclient/edit/"+response['client_id']);
                }
                
                $('.register_of_nominee_director input[name="nomi_identification_no"]').prop("readonly", true);
                $('.register_of_nominee_director input[name="nomi_officer_id"]').val(nominee_director_info[0]['nomi_officer_id']);
                $('.register_of_nominee_director input[name="nomi_officer_field_type"]').val(nominee_director_info[0]['nomi_officer_field_type']);
                $('.register_of_nominee_director .nomi_view_edit_person').show();
                $('.register_of_nominee_director input[name="date_become_nominator"]').val(nominee_director_info[0]['date_become_nominator']);
                $('.register_of_nominee_director input[name="date_ceased_nominator"]').val(nominee_director_info[0]['date_of_cessation']);

                if(nominee_director_info[0]["supporting_document"] != "" && nominee_director_info[0]["supporting_document"] != "[]")
                {
                    var edit_file_result = JSON.parse(nominee_director_info[0]["supporting_document"]);
                    var editfilename = "";
                    editfilename = '<a href="'+base_url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                    $(".register_of_nominee_director .nd_file_name").html(editfilename);
                    $(".register_of_nominee_director .nd_hidden_supporting_document").val(nominee_director_info[0]["supporting_document"]);
                }
                else
                {
                    $(".register_of_nominee_director .nd_file_name").html("");
                    $(".register_of_nominee_director .nd_hidden_supporting_document").val("");
                }
                
                $("#modal_nominee_director").modal("show");
            }
        }

    });
}

$(document).on("click", ".open_edit_nominee_director", function () {
    var nominee_director_id = $(this).data('id');
    console.log(nominee_director_id);
    open_edit_nominee_director(nominee_director_id);
});

$("#refresh_nominee_director").click(function(){
    refresh_nominee_director();
});

function refresh_nominee_director()
{
    //$(".controller_sort_id").remove();
    $(".tr_nominee_director_table").remove();
    $('#nominee_director_table').DataTable().clear();
    $('#nominee_director_table').DataTable().destroy();

    $.ajax({
        type: "POST",
        url: "masterclient/refresh_nominee_director",
        data: {"company_code": company_code},
        asycn: false,
        dataType: "json",
        success: function(response){
            client_nominee_director = response.info["client_nominee_director"];
            append_nominee_director_table(client_nominee_director);
        }
    });
}

function nd_add_controller_person(elem)
{
    //------------new--------------------
    $(".nd_refresh_controller").show();
    $(".nd_a_refresh_controller").show();
    
    jQuery(elem).attr('hidden',"true");
    //--------------------------------
}

function nomi_add_controller_person(elem)
{
    //------------new--------------------
    $(".nomi_refresh_controller").show();
    $(".nomi_a_refresh_controller").show();
    
    jQuery(elem).attr('hidden',"true");
    //--------------------------------
}

function delete_nominee_director(element)
{
    var tr = jQuery(element).parent().parent();

    var client_nominee_director_id = tr.find('#client_nominee_director_id').val();
    var delete_company_code = tr.find('#company_code').val();
    var client_nominee_director_name = tr.find('#client_nominee_director_name').val();
    // console.log("client_officer_id==="+client_controller_id);
    // console.log("delete_company_code==="+delete_company_code);
    bootbox.confirm("Are you confirm delete this record?", function (result) {
        if (result) 
        {    
            $('#loadingmessage').show();
            if(client_nominee_director_id != undefined)
            {
                $(".tr_nominee_director_table").remove();
                $('#nominee_director_table').DataTable().clear();
                $('#nominee_director_table').DataTable().destroy();
                $.ajax({ //Upload common input
                    url: "masterclient/delete_nominee_director",
                    type: "POST",
                    data: {"client_nominee_director_id": client_nominee_director_id, "delete_company_code": delete_company_code, "client_nominee_director_name": client_nominee_director_name},
                    dataType: 'json',
                    success: function (response) {
                        //console.log(response.Status);
                        $('#loadingmessage').hide();
                        append_nominee_director_table(response.list_of_nominee_director);
                    }
                });
            }

            toastr.success("Updated Information.", "Updated");
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