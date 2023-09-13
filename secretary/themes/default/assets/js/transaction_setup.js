var cm1 = new Chairman();
var showCM, showDS1, showDS2;
var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
            if(e.status != 200)
            {
                alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
            }
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

function Chairman() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getDirectorSignature2 = function(director_signature_1_id) {
        var url = base_url+ '/' + folder + '/'+'companytype/getTransactionDirectorSignature2';
        var method = "post";
        var data = {"company_code": transaction_company_code, "director_signature_1_id": director_signature_1_id};
        $('.director_signature_2').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.director_signature_2').find("option:eq(0)").html("Select Director Signature 2");
            if(data.tp == 1){
                if(data['result'].length == 0)
                {
                    $(".director_signature_2").attr("disabled", "disabled");
                    $(".director_signature_2 option:gt(0)").remove();
                }
                else
                {
                    $(".director_signature_2 option:gt(0)").remove(); 
                    
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(data.selected_all_director2 != null && key == data.selected_all_director2)
                        {
                            option.attr('selected', 'selected');
                        }
                        $('.director_signature_2').append(option);
                    });
                }
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getTodayDirectorSignature2 = function(director_signature_1_id) {
        var url = base_url+"/"+folder+"/"+'companytype/getTodayTransactionDirectorSignature2';
        var method = "post";
        var data = {"company_code": transaction_company_code, "director_signature_1_id": director_signature_1_id};
        $('.director_signature_2').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.director_signature_2').find("option:eq(0)").html("Select Director Signature 2");
            if(data.tp == 1){
                if(data['result'].length == 0)
                {
                    $(".director_signature_2").attr("disabled", "disabled");
                    $(".director_signature_2 option:gt(0)").remove();
                }
                else
                {
                    $(".director_signature_2 option:gt(0)").remove(); 
                    $(".director_signature_2").attr("disabled", false);
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(data.selected_all_director2 != null && key == data.selected_all_director2)
                        {
                            option.attr('selected', 'selected');
                        }
                        $('.director_signature_2').append(option);
                    });
                }
                
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getDirectorSignature1 = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getTransactionDirectorSignature1';
        var method = "post";
        var data = {"company_code": transaction_company_code};
        $('.director_signature_1').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.director_signature_1').find("option:eq(0)").html("Select Director Signature 1");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_all_director1 != null && key == data.selected_all_director1)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.director_signature_1').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getTodayDirectorSignature1 = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getTodayTransactionDirectorSignature1';
        var method = "post";
        var data = {"company_code": transaction_company_code};
        $('.director_signature_1').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.director_signature_1').find("option:eq(0)").html("Select Director Signature 1");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_all_director1 != null && key == data.selected_all_director1)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.director_signature_1').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getChairman = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getTransactionChairman';
        var method = "post";
        var data = {"company_code": transaction_company_code};
        $('.chairman').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.chairman').find("option:eq(0)").html("Select Chairman");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_chairman != null && key == data.selected_chairman)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.chairman').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getAllChairman = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getAllTransactionChairman';
        var method = "post";
        var data = {"company_code": transaction_company_code};
        $('.chairman').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.chairman').find("option:eq(0)").html("Select Chairman");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_all_chairman != null && key == data.selected_all_chairman)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.chairman').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }); 
    };

}

$(function() {
    var cm = new Chairman();
    if(!transaction_client_signing_info)
    {
        cm.getChairman();
        cm.getTodayDirectorSignature1();

    }
    else
    {
        cm.getAllChairman();
        cm.getDirectorSignature1();
    }
});

if(transaction_client_signing_info)
{
    if(transaction_client_signing_info[0]['show_all'])
    {
        cm1.getAllChairman();
    }

    if(transaction_client_signing_info[0]['director_signature_2'])
    {
        cm1.getDirectorSignature2(transaction_client_signing_info[0]['director_signature_1']);
        $(".director_signature_2").removeAttr("disabled");
    }
}


if(transaction_client_signing_info)
{
    showCM = false;
    showDS1 = false;
    $(".btnShowAllChairman").prop('value', 'Show Today');
    $(".btnShowAllDirectorSig1").prop('value', 'Show Today');
    if(transaction_client_signing_info[0]["director_signature_2"] != "0")
    {
        showDS2 = false;
        $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
    }
    else
    {
        showDS2 = true;
        $(".btnShowAllDirectorSig2").prop('value', 'Show All');
        $(".director_signature_2").attr("disabled", "disabled");
    }
    
}
else
{
    showCM = true;
    showDS1 = true;
    showDS2 = true;
    $(".btnShowAllChairman").prop('value', 'Show All');
    $(".btnShowAllDirectorSig1").prop('value', 'Show All');
    $(".btnShowAllDirectorSig2").prop('value', 'Show All');
}

$(".director_signature_1").change(function(ev) {
    var director_signature_1_id = $(this).val();
    if(director_signature_1_id != '' && director_signature_1_id != 0){
        cm1.getDirectorSignature2(director_signature_1_id);
        showDS2 = false;
        $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
        $(".director_signature_2").removeAttr("disabled");
    }
    else{
        $(".director_signature_2").attr("disabled", "disabled");
        $(".director_signature_2 option:gt(0)").remove();
    }
});

function showAllChairman(chairmanbox) {
    var tr = jQuery(chairmanbox).parent().parent();
    if (showCM) 
    {
        tr.find('select[name="chairman"]').html(""); 
        tr.find('select[name="chairman"]').append($('<option>', {
            value: '0',
            text: 'Select Chairman'
        }));
        cm1.getAllChairman();

        showCM = false;
        $(".btnShowAllChairman").prop('value', 'Show Today');
    }
    else
    {
        tr.find('select[name="chairman"]').html(""); 
        tr.find('select[name="chairman"]').append($('<option>', {
            value: '0',
            text: 'Select Chairman'
        }));
        cm1.getChairman();

        showCM = true;
        $(".btnShowAllChairman").prop('value', 'Show All');
    }
}

function showAllDirectorSig1(directorsig1box) {
    var tr = jQuery(directorsig1box).parent().parent();
    if (showDS1) 
    {
        tr.find('select[name="director_signature_1"]').html(""); 
        tr.find('select[name="director_signature_1"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 1'
        }));
        cm1.getDirectorSignature1();

        showDS1 = false;
        $(".btnShowAllDirectorSig1").prop('value', 'Show Today');
    }
    else
    {
        tr.find('select[name="director_signature_1"]').html(""); 
        tr.find('select[name="director_signature_1"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 1'
        }));
        cm1.getTodayDirectorSignature1();

        showDS1 = true;
        $(".btnShowAllDirectorSig1").prop('value', 'Show All');
    }
}

function showAllDirectorSig2(directorsig2box) {
    var tr = jQuery(directorsig2box).parent().parent();
    var ds_1_id = $('select[name="director_signature_1"]').val();
    $(".director_signature_2").attr("disabled", false);
    if (showDS2) 
    {
        tr.find('select[name="director_signature_2"]').html(""); 
        tr.find('select[name="director_signature_2"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 2'
        }));
        cm1.getDirectorSignature2(ds_1_id);

        showDS2 = false;
        $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
    }
    else
    {
        tr.find('select[name="director_signature_2"]').html(""); 
        tr.find('select[name="director_signature_2"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 2'
        }));
        cm1.getTodayDirectorSignature2(ds_1_id);

        showDS2 = true;
        $(".btnShowAllDirectorSig2").prop('value', 'Show All');
    }
}

$('.show_contact_phone').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".contact_phone_toggle").toggle();
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        $(this).find(".toggle_word").text('Show more');
    }
});

$('.show_contact_email').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".contact_email_toggle").toggle();
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        $(this).find(".toggle_word").text('Show more');
    }
});

$('.hp').intlTelInput({
    preferredCountries: [ "sg", "my"],
    initialCountry: "auto",
    formatOnDisplay: false,
    nationalMode: true,
    geoIpLookup: function(callback) {
        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
        });
    },
    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
      return "" ;
    },
    utilsScript: "../themes/default/js/utils.js"
});

//edit
if(client_contact_info_phone != null)
{
    for (var h = 0; h < client_contact_info_phone.length; h++) 
    {
        var clientContactInfoPhoneArray = client_contact_info_phone[h].split(',');

        if(clientContactInfoPhoneArray[2] == 1)
        {
            $(".fieldGroup_contact_phone").find('.main_contact_phone').intlTelInput("setNumber", clientContactInfoPhoneArray[1]);
            $(".fieldGroup_contact_phone").find('.main_hidden_contact_phone').attr("value", clientContactInfoPhoneArray[1]);
            $(".fieldGroup_contact_phone").find('.main_contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);
            $(".fieldGroup_contact_phone").find(".button_increment_contact_phone").css({"visibility": "visible"});
        }
        else
        {
            
            $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", clientContactInfoPhoneArray[1]);
            $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_contact_phone" style="margin-top:10px;">'+$(".fieldGroupCopy_contact_phone").html()+'</div>';

            $( fieldHTML).prependTo(".contact_phone_toggle");

            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput({
                preferredCountries: [ "sg", "my"],
                formatOnDisplay: false,
                nationalMode: true,
                geoIpLookup: function(callback) {
                    jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                  return "" ;
                },
                utilsScript: "../themes/default/js/utils.js"
            });

            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput("setNumber", clientContactInfoPhoneArray[1]);

            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').on({
              keydown: function(e) {
                if (e.which === 32)
                  return false;
              },
              change: function() {
                this.value = this.value.replace(/\s/g, "");
              }
            });

            $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", "");
            $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", "");
        }
    }
}
else
{
    $(".fieldGroup_contact_phone").find('.main_contact_phone').intlTelInput("setNumber", "");
}

if(client_contact_info_email != null)
{
    for (var h = 0; h < client_contact_info_email.length; h++) 
    {
        var clientContactInfoEmailArray = client_contact_info_email[h].split(',');

        if(clientContactInfoEmailArray[2] == 1)
        {
            $(".fieldGroup_contact_email").find('.main_contact_email').attr("value", clientContactInfoEmailArray[1]);
            $(".fieldGroup_contact_email").find('.main_contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

            $(".fieldGroup_contact_email").find(".button_increment_contact_email").css({"visibility": "visible"});
        }
        else
        {
            $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", clientContactInfoEmailArray[1]);

            $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_contact_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_contact_email").html()+'</div>';

            $( fieldHTML).prependTo(".contact_email_toggle");

            $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", "");
            $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", "");
        }
    }
}
//

$(document).on('blur', '.check_empty_contact_phone', function(){
    $(this).parent().parent().find(".hidden_contact_phone").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".contact_phone_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).on('blur', '.check_empty_contact_email', function(){
    $(this).parent().find(".contact_email_primary").attr("value", $(this).val());
});

$(document).ready(function() {

    $(document).on('click', '.contact_phone_primary', function(event){   
        event.preventDefault();
        var contact_phone_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Phone Number?", function (result) {
            if (result) {
                contact_phone_primary_radio_button.prop( "checked", true );
                $( '#form_contact_phone' ).html("");
            }
        });
    });

    $(document).on('click', '.contact_email_primary', function(event){  
        event.preventDefault();
        var contact_email_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
            if (result) {
                contact_email_primary_radio_button.prop( "checked", true );
                $( '#form_contact_email' ).html("");
            }
        });
    });

    $(".check_empty_contact_phone").on({
      keydown: function(e) {
        if (e.which === 32)
          return false;
      },
      change: function() {
        this.value = this.value.replace(/\s/g, "");
      }
    });

    $(".addMore_contact_phone").click(function(){
        var number = $(".main_contact_phone").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        var countryData = $(".main_contact_phone").intlTelInput("getSelectedCountryData");

        $(".contact_phone_toggle").show();
        $(".show_contact_phone").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_contact_phone").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_contact_phone").find('.second_contact_phone').attr("value", $(".main_contact_phone").val());
        $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", number);
        $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", number);
        
        var fieldHTML = '<div class="input-group fieldGroup_contact_phone" style="margin-top:10px;">'+$(".fieldGroupCopy_contact_phone").html()+'</div>';
        $( fieldHTML).prependTo(".contact_phone_toggle");

        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput({
            preferredCountries: [ "sg", "my"],
            formatOnDisplay: false,
            nationalMode: true,
            initialCountry: countryData.iso2,
            geoIpLookup: function(callback) {
                jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
              return "" ;
            },
            utilsScript: "../themes/default/js/utils.js"
        });

        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').on({
          keydown: function(e) {
            if (e.which === 32)
              return false;
          },
          change: function() {
            this.value = this.value.replace(/\s/g, "");
          }
        });

        if ($(".main_contact_phone_primary").is(":checked")) 
        {
            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.contact_phone_primary').prop( "checked", true );
        }


        $(".button_increment_contact_phone").css({"visibility": "hidden"});

        if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
        {
            $(".show_contact_phone").css({"visibility": "visible"});

        }
        else {
            $(".show_contact_phone").css({"visibility": "hidden"});
            
        }
       
        $(".main_contact_phone").val("");
        $(".main_contact_phone").parent().parent().find(".hidden_contact_phone").val("");
        $(".main_contact_phone").parent().parent().find(".contact_phone_primary").val("");
        $(".fieldGroupCopy_contact_phone").find('.second_contact_phone').attr("value", "");
        $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", "");
        $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", "");

    });

    $("body").on("click",".remove_contact_phone",function(){ 
        var remove_contact_phone_button = $(this);
        bootbox.confirm("Are you comfirm delete this Phone Number?", function (result) {
            if (result) {

                remove_contact_phone_button.parents(".fieldGroup_contact_phone").remove();

                if (remove_contact_phone_button.parent().find(".contact_phone_primary").is(":checked")) 
                {
                    if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
                    {
                        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.contact_phone_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_contact_phone_primary").prop( "checked", true );
                    }
                    
                }

                if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
                {
                    $(".show_contact_phone").css({"visibility": "visible"});

                }
                else {
                    $(".show_contact_phone").css({"visibility": "hidden"});
                    
                }
            }
        });
    });

    $('.main_contact_phone').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_contact_phone").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_contact_phone").css({"visibility": "hidden"});
        }
    });

    $(".addMore_contact_email").click(function(){
        $(".contact_email_toggle").show();
        $(".show_contact_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_contact_email").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", $(".main_contact_email").val());
        $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", $(".main_contact_email").val());

        var fieldHTML = '<div class="input-group fieldGroup_contact_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_contact_email").html()+'</div>';

        $( fieldHTML).prependTo(".contact_email_toggle");

        if ($(".main_contact_email_primary").is(":checked")) 
        {
            $(".contact_email_toggle .fieldGroup_contact_email").eq(0).find('.contact_email_primary').prop( "checked", true );
        }
        
        $(".button_increment_contact_email").css({"visibility": "hidden"});
       
       if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
        {
            $(".show_contact_email").css({"visibility": "visible"});

        }
        else {
            $(".show_contact_email").css({"visibility": "hidden"});
            
        }

        $(".main_contact_email").val("");
        $(".main_contact_email").parent().find(".main_contact_email_primary").val("");
        $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", "");
        $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", "");

    });

    $("body").on("click",".remove_contact_email",function(){ 
        var remove_contact_email_button = $(this);
        bootbox.confirm("Are you comfirm delete this Email?", function (result) {
            if (result) {

                remove_contact_email_button.parents(".fieldGroup_contact_email").remove();

                if (remove_contact_email_button.parent().find(".contact_email_primary").is(":checked")) 
                {
                    if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
                    {
                        $(".contact_email_toggle .fieldGroup_contact_email").eq(0).find('.contact_email_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_contact_email_primary").prop( "checked", true );
                    }
                }

                if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
                {
                    $(".show_contact_email").css({"visibility": "visible"});

                }
                else {
                    $(".show_contact_email").css({"visibility": "hidden"});
                    
                }
            }
        });
    });

    $('.main_contact_email').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_contact_email").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_contact_email").css({"visibility": "hidden"});
        }
    });

    if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
    {
        $(".show_contact_phone").css({"visibility": "visible"});
        $(".contact_phone_toggle").hide();

    }
    else {
        $(".show_contact_phone").css({"visibility": "hidden"});
        $(".contact_phone_toggle").hide();
    }

    if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
    {
        $(".show_contact_email").css({"visibility": "visible"});
        $(".contact_email_toggle").hide();

    }
    else {
        $(".show_contact_email").css({"visibility": "hidden"});
        $(".contact_email_toggle").hide();
    }

});



$.ajax({
    type: "GET",
    url: "masterclient/get_reminder",
    dataType: "json",
    async: false,
    success: function(data){
        if(data.tp == 1){
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                var str = client_selected_reminder;
                if(str)
                {
                    for($k = 0; $k < str.length; $k++)
                    {
                        if(key == str[$k]['selected_reminder'])
                        {
                            option.attr('selected', 'selected');
                        }
                    }
                }

                $('#select_reminder').append(option);
            });
        }
        else{
            alert(data.msg);
        }
    }               
});

$(document).ready(function () {
    $('#setup_form').find('[name="select_reminder[]"]')
    .multiselect({
        buttonWidth: '300px',
        maxHeight: 100,
        buttonText: function(options, select) {
            if (options.length === 0) {
                return 'Select the Reminder';
            }
            else if (options.length > 1) {
                return 'More than 1 reminder selected!';
            }
            else {
                 var labels = [];
                 options.each(function() {
                     if ($(this).attr('label') !== undefined) {
                         labels.push($(this).attr('label'));
                     }
                     else {
                         labels.push($(this).html());
                     }
                 });
                 return labels.join(', ') + '';
            }
        }

    });
});

$(document).on('click',"#submitSetupInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/add_setup_info",
      type: "POST",
      data: $('form#setup_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
            $('#loadingmessage').hide();
            if (response.Status === 1) 
            {
                toastr.success(response.message, response.title);
            }
            else if(response.Status == 2)
            {
                toastr.warning(response.message, response.title);
            }
            else if(response.Status == 3)
            {
                toastr.error(response.message, response.title);
            }
        }
    })

});