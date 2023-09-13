var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';
$(".nd_add_office_person_link").attr("href", url + 'personprofile/add/1');
$(".nomi_add_office_person_link").attr("href", url + 'personprofile/add/1');

// $( document ).ready(function() {

function revalidateDatepicker()
{
    // $('#form_register_of_nominee_director #nd_date_entry').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    //     autoclose: true,
    // }).on('changeDate', function (selected) {
    //     $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_date_entry');
    // });

    $('#form_register_of_nominee_director #date_become_nominator').datepicker({ 
        dateFormat:'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        $('#form_register_of_nominee_director').formValidation('revalidateField', 'date_become_nominator');
    });

    $('#form_register_of_nominee_director #date_ceased_nominator').datepicker({ 
        dateFormat:'dd/mm/yyyy',
        autoclose: true,
    });
}

//-----------------open modal -------------------------
function open_nominee_director()
{
    $('.register_of_nominee_director input[name="nd_identification_no"]').prop("readonly", false);
    $('.register_of_nominee_director input[name="nomi_identification_no"]').prop("readonly", false);
    $('.register_of_nominee_director input').val("");
    $(".register_of_nominee_director .nd_file_name").html("");
    $(".register_of_nominee_director .nd_hidden_supporting_document").val("");

    $('.register_of_nominee_director .nd_add_office_person_link').hide();
    $('.register_of_nominee_director .nd_a_refresh_controller').hide();
    $('.register_of_nominee_director .nd_refresh_controller').hide();
    $('.register_of_nominee_director .nd_view_edit_person').hide();

    $('.register_of_nominee_director .nomi_add_office_person_link').hide();
    $('.register_of_nominee_director .nomi_a_refresh_controller').hide();
    $('.register_of_nominee_director .nomi_refresh_controller').hide();
    $('.register_of_nominee_director .nomi_view_edit_person').hide();

    $('#form_register_of_nominee_director').formValidation('addField', 'nd_identification_no', nd_identification_no);
    $('#form_register_of_nominee_director').formValidation('addField', 'nd_name', nd_name);
    //$('#form_register_of_nominee_director').formValidation('addField', 'nd_date_entry', nd_date_entry);

    $('#form_register_of_nominee_director').formValidation('addField', 'nomi_identification_no', nomi_identification_no);
    $('#form_register_of_nominee_director').formValidation('addField', 'nomi_name', nomi_name);
    $('#form_register_of_nominee_director').formValidation('addField', 'date_become_nominator', date_become_nominator);

    revalidateDatepicker();
    $("#modal_nominee_director").modal("show");
}

//$("#create_nominee_director").click(function()
$(document).on('click','#create_nominee_director',function(){
    open_nominee_director();
    //console.log("in");
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
                    $('.register_of_nominee_director #nomi_company_code').val(transaction_company_code);
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
                    $('.register_of_nominee_director #nomi_company_code').val(transaction_company_code);
                    $('.register_of_nominee_director #nomi_name').val(response['company_name']);
                    $('.register_of_nominee_director input[name="nomi_officer_id"]').val(response['id']);
                    $('.register_of_nominee_director input[name="nomi_officer_field_type"]').val(response['field_type']);
                    $('.register_of_nominee_director .nomi_view_edit_person').show();
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(response['encrypt_register_no'])));

                    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
                }
                else if(response['field_type'] == "individual")
                {
                    $('.register_of_nominee_director #nomi_company_code').val(transaction_company_code);
                    $('.register_of_nominee_director #nomi_name').val(response['name']);
                    $('.register_of_nominee_director input[name="nomi_officer_id"]').val(response['id']);
                    $('.register_of_nominee_director input[name="nomi_officer_field_type"]').val(response['field_type']);
                    $('.register_of_nominee_director .nomi_view_edit_person').show();
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(response['encrypt_identification_no'])));
                    
                    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
                }
                else
                {
                    $('.register_of_nominee_director #nomi_company_code').val(transaction_company_code);
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

function open_edit_nominee_director(nominee_director_id, transaction_id, nomioffice_id, nomifield_type) {
    $('#loadingControllerMessage').show();
    $.ajax({
        type: "POST",
        url: "transaction/get_nominee_director_info",
        data: {"nominee_director_id":nominee_director_id, "transaction_id":transaction_id, "nomioffice_id":nomioffice_id, "nomifield_type":nomifield_type}, // <--- THIS IS THE CHANGE
        dataType: "json",
        //async: false,
        success: function(response){
            $('#loadingControllerMessage').hide();
            //var validateValue = false;
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
                
                // $('.register_of_nominee_director input[name="nomi_identification_no"]').val(nominee_director_info[0]['identification_no']);
                // $('.register_of_nominee_director input[name="nomi_identification_no"]').prop("readonly", true);
                // $('.register_of_nominee_director #nomi_name').val(nominee_director_info[0]['name']);
                // $('.register_of_nominee_director input[name="nomi_officer_id"]').val(nominee_director_info[0]['nomi_officer_id']);
                // $('.register_of_nominee_director input[name="nomi_officer_field_type"]').val(nominee_director_info[0]['nomi_officer_field_type']);
                // $('.register_of_nominee_director .nomi_view_edit_person').show();
                // $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(nominee_director_info[0]['encrypt_identification_no'])));
                // $('.register_of_nominee_director input[name="date_become_nominator"]').val(nominee_director_info[0]['date_become_nominator']);
                // $('.register_of_nominee_director input[name="date_ceased_nominator"]').val(nominee_director_info[0]['date_of_cessation']);

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
                    $('.register_of_nominee_director .nomi_view_edit_person').attr("href", "masterclient/edit/"+nominee_director_info[0]['client_id']);
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
                    editfilename = '<a href="'+url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                    $(".register_of_nominee_director .nd_file_name").html(editfilename);
                    $(".register_of_nominee_director .nd_hidden_supporting_document").val(nominee_director_info[0]["supporting_document"]);
                }
                else
                {
                    $(".register_of_nominee_director .nd_file_name").html("");
                    $(".register_of_nominee_director .nd_hidden_supporting_document").val("");
                }
                //validateValue = true;
                //$('#form_register_of_nominee_director').formValidation('updateStatus', 'nd_identification_no', 'NOT_VALIDATED').formValidation('validateField', 'nd_identification_no');
                //revalidatefield();
                revalidateDatepicker();

                $("#modal_nominee_director").modal("show");

            }

            // $( document ).ready(function() {
            // if(validateValue)
            // {   
            //     if($('.register_of_nominee_director input[name="nd_identification_no"]').val() != "")
            //     {
            //         console.log($('.register_of_nominee_director input[name="nd_identification_no"]').val());
            //         $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_identification_no');
            //     }
            //     $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_name');
            //     $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_date_entry');

            //     $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_identification_no');
            //     $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
            //     $('#form_register_of_nominee_director').formValidation('revalidateField', 'date_become_nominator');
            //     $('#form_receipt').formValidation('revalidateField', 'total_amount_received');
            // }
            // });
        }

    });
    
}

$(document).on("click", ".open_edit_nominee_director", function () {
    var nominee_director_id = $(this).data('id');
    var transaction_id = $(this).data('transaction_id');
    var nomioffice_id = $(this).data('nomiofficeid');
    var nomifield_type = $(this).data('nomifieldtype');
    //console.log(nominee_director_id);
    open_edit_nominee_director(nominee_director_id, transaction_id, nomioffice_id, nomifield_type);
});
// });

function delete_nominee_director(element)
{
    var tr = jQuery(element).parent().parent();

    var client_nominee_director_id = tr.find('#client_nominee_director_id').val();
    var delete_nomi_officer_id = tr.find('#delete_nomi_officer_id').val();
    var delete_nomi_officer_field_type = tr.find('#delete_nomi_officer_field_type').val();
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
                $(".table_body_current_nominee_director").remove();
                $('#current_nominee_director_table').DataTable().clear();
                $('#current_nominee_director_table').DataTable().destroy();

                $(".table_body_latest_nominee_director").remove();
                $('#latest_nominee_director_table').DataTable().clear();
                $('#latest_nominee_director_table').DataTable().destroy();
                
                $.ajax({ //Upload common input
                    url: "transaction/delete_nominee_director",
                    type: "POST",
                    data: {"client_nominee_director_id": client_nominee_director_id, "delete_nomi_officer_id": delete_nomi_officer_id, "delete_nomi_officer_field_type": delete_nomi_officer_field_type, "delete_company_code": delete_company_code, "client_nominee_director_name": client_nominee_director_name, "transaction_master_id": $('#transaction_trans #transaction_master_id').val()},
                    dataType: 'json',
                    success: function (response) {
                        //console.log(response.Status);
                        $('#loadingmessage').hide();
                        registerNomineeDirectorInterface(response.current_client_nominee_director_data);
                        // registerLatestNomineeDirectorInterface(response.latest_client_nominee_director_data);

                        if($('.transaction_task option:selected').val() == 33)
                        {
                            appointResignNomineeDirectorInterface(response.latest_client_nominee_director_data);
                        }
                        else
                        {
                            registerLatestNomineeDirectorInterface(response.latest_client_nominee_director_data);
                        }
                    }
                });
            }

            toastr.success("Updated Information.", "Updated");
        }
    });
}