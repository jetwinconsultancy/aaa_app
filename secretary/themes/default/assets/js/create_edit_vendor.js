$tab_aktif = "vendorInfo";
var base_url = '<?php echo base_url() ?>';

$('.nav li').not('.active').addClass('disabled');

if(vendor_info)
{
    $('.nav li').removeClass('disabled');
}
else
{
    $('.disabled').click(function (e) {
        e.preventDefault();

        if($(this).hasClass("disabled"))
        {
            return false;
        }
        else
        {
            return true;
        }
        
    });
}

$(document).on('click',".check_stat",function() {
	$tab_aktif = $(this).data("information");
});

if(tab == "vendorSetup")
{
    $('#myTab #li-vendorInfo').removeClass("active");
    $('.tab-content #w2-vendorInfo').removeClass("active");

    $('#myTab #li-vendorSetup').addClass("active");
    $('.tab-content #w2-vendorSetup').addClass("active");
    $tab_aktif = "vendorSetup";
}

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$('#vendor_postal_code').keyup(function(){
	if($(this).val().length == 6)
	{
		var zip = $(this).val();
		//var address = "068914";
		$.ajax({
		  url:    'https://gothere.sg/maps/geo',
		  dataType: 'jsonp',
		  data:   {
		    'output'  : 'json',
		    'q'     : zip,
		    'client'  : '',
		    'sensor'  : false
		  },
		  type: 'GET',
		  success: function(data) {
		    var myString = "";
		    
		    var status = data.Status;
		    
		    if (status.code == 200) {         
		      for (var i = 0; i < data.Placemark.length; i++) {
		        var placemark = data.Placemark[i];
		        var status = data.Status[i];
		        //console.log(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);
		        $("#vendor_street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

		        if(placemark.AddressDetails.Country.AddressLine == "undefined")
		        {
		        	$("#vendor_building_name").val("");
		        }
		        else
		        {
		        	$("#vendor_building_name").val(placemark.AddressDetails.Country.AddressLine);
		        }
		       
		      }
		      $( '#form_vendor_postal_code' ).html('');
		      $( '#form_vendor_street_name' ).html('');
		      //field.val(myString);
		    } else if (status.code == 603) {
		    	$( '#form_vendor_postal_code' ).html('<span class="help-block">*No Record Found</span>');
		      //field.val("No Record Found");
		    }

		  },
		  statusCode: {
		    404: function() {
		      alert('Page not found');
		    }
		  },
	    });
	}
});

$("#vendor_code").live('change',function(){
	$( '#form_vendor_code' ).html("");
});
// $("#vendor_registration_no").live('change',function(){
// 	$( '#form_vendor_registration_no' ).html("");
// });
$("#vendor_company_name").live('change',function(){
	$( '#form_vendor_company_name' ).html("");
});
$("#vendor_postal_code").live('change',function(){
	$( '#form_vendor_postal_code' ).html("");
});
$("#vendor_street_name").live('change',function(){
	$( '#form_vendor_street_name' ).html("");
});

$(document).on('click',"#save_vendor",function(e){
	$("#w2-"+$tab_aktif+" form").submit();
});

$(document).on('submit', function (e) {
    e.preventDefault();

    var link;
    if($tab_aktif == "vendorInfo")
	{
		link = "payment_voucher/save_vendor";
	}
	else if($tab_aktif == "vendorSetup") 
	{
		link = "payment_voucher/add_vendor_setup_info";
	}

	if($tab_aktif == "vendorInfo" || $tab_aktif == "vendorSetup")
	{
		var form = $("#w2-"+$tab_aktif+" form");

		var form_data = form.serialize();
		
		$('#loadingmessage').show();
        $.ajax({ //Upload common input
            url: link,
            type: "POST",
            data: form_data,
            dataType: 'json',
            success: function (response,data) {
            	$('#loadingmessage').hide();
            	//console.log(response);
                if (response.Status === 1) 
                {
                	toastr.success(response.message, response.title);

				    if($tab_aktif == "vendorInfo")
                	{
					    var errors = '';
                		$( '#form_vendor_code' ).html( errors );
                		//$( '#form_vendor_registration_no' ).html( errors );
                		$( '#form_vendor_company_name' ).html( errors );
                		$( '#form_vendor_former_name' ).html( errors );
                		$( '#form_vendor_postal_code' ).html( errors );
                        $( '#form_vendor_foreign_address1' ).html( errors );
                		$('.nav li').removeClass('disabled');
                        //console.log(response.change_company_name);
                		if(response.change_company_name)
                		{
                			location.reload();
                		}
                	}
                }
                else
                {
                	toastr.error(response.message, response.title);

                	if($tab_aktif == "vendorInfo")
                	{
                    	if (response.error["vendor_code"] != "")
                    	{
                    		var errorsVendorCode = '<span class="help-block">*' + response.error["vendor_code"] + '</span>';
                    		$( '#form_vendor_code' ).html( errorsVendorCode );

                    	}
                    	else
                    	{
                    		var errorsVendorCode = '';
                    		$( '#form_vendor_code' ).html( errorsVendorCode );
                    	}
                    	
                    	// if (response.error["vendor_registration_no"] != "")
                    	// {
                    	// 	var errorsVendorRegistrationNo = '<span class="help-block">*' + response.error["vendor_registration_no"] + '</span>';
                    	// 	$( '#form_vendor_registration_no' ).html( errorsVendorRegistrationNo );

                    	// }
                    	// else
                    	// {
                    	// 	var errorsVendorRegistrationNo = '';
                    	// 	$( '#form_vendor_registration_no' ).html( errorsVendorRegistrationNo );
                    	// }

                    	if (response.error["vendor_company_name"] != "")
                    	{
                    		var errorsVendorCompanyName = '<span class="help-block">*' + response.error["vendor_company_name"] + '</span>';
                    		$( '#form_vendor_company_name' ).html( errorsVendorCompanyName );

                    	}
                    	else
                    	{
                    		var errorsVendorCompanyName = '';
                    		$( '#form_vendor_company_name' ).html( errorsVendorCompanyName );
                    	}

                    	if (response.error["vendor_postal_code"] != "")
                    	{
                    		var errorsVendorPostalCode = '<span class="help-block">*' + response.error["vendor_postal_code"] + '</span>';
                    		$( '#form_vendor_postal_code' ).html( errorsVendorPostalCode );

                    	}
                    	else
                    	{
                    		var errorsVendorPostalCode = '';
                    		$( '#form_vendor_postal_code' ).html( errorsVendorPostalCode );
                    	}

                    	if (response.error["vendor_street_name"] != "")
                    	{
                    		var errorsVendorStreetName = '<span class="help-block">*' + response.error["vendor_street_name"] + '</span>';
                    		$( '#form_vendor_street_name' ).html( errorsVendorStreetName );

                    	}
                    	else
                    	{
                    		var errorsVendorStreetName = '';
                    		$( '#form_vendor_street_name' ).html( errorsVendorStreetName );
                    	}

                        if (response.error["vendor_foreign_address1"] != "")
                        {
                            var errorsVendorForeignAdd1 = '<span class="help-block">*' + response.error["vendor_foreign_address1"] + '</span>';
                            $( '#form_vendor_foreign_address1' ).html( errorsVendorForeignAdd1 );

                        }
                        else
                        {
                            var errorsVendorForeignAdd1 = '';
                            $( '#form_vendor_foreign_address1' ).html( errorsVendorForeignAdd1 );
                        }
                    }
                    else if($tab_aktif == "setup")
                    {

                    	if (response.error["contact_phone"] != "")
                    	{
                    		var errorsContactPhone = '<span class="help-block">*' + response.error["contact_phone"] + '</span>';
                    		$( '#form_contact_phone' ).html( errorsContactPhone );

                    	}
                    	else
                    	{
                    		var errorsContactPhone = '';
                    		$( '#form_contact_phone' ).html( errorsContactPhone );
                    	}

                    	if (response.error["contact_email"] != "")
                    	{
                    		var errorsContactEmail = '<span class="help-block">*' + response.error["contact_email"] + '</span>';
                    		$( '#form_contact_email' ).html( errorsContactEmail );

                    	}
                    	else
                    	{
                    		var errorsContactEmail = '';
                    		$( '#form_contact_email' ).html( errorsContactEmail );
                    	}

                    }
                }
            }
        });
	}
});

$('.show_contact_phone').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".contact_phone_toggle").toggle();
    //console.log($(this).parent().parent());
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
if(vendor_contact_info_phone != null)
{
    for (var h = 0; h < vendor_contact_info_phone.length; h++) 
    {
        var clientContactInfoPhoneArray = vendor_contact_info_phone[h].split(',');

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

            //$('body').find('.fieldGroup_contact_phone:first').after(fieldHTML);
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

if(vendor_contact_info_email != null)
{
    for (var h = 0; h < vendor_contact_info_email.length; h++) 
    {
        var clientContactInfoEmailArray = vendor_contact_info_email[h].split(',');

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

            //$('body').find('.fieldGroup_contact_email:first').after(fieldHTML);
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
        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        //$(".fieldGroupCopy_contact_phone").find('.second_contact_phone').intlTelInput("setCountry", countryData.iso2);

        var fieldHTML = '<div class="input-group fieldGroup_contact_phone" style="margin-top:10px;">'+$(".fieldGroupCopy_contact_phone").html()+'</div>';

        //$('body').find('.fieldGroup_contact_phone:first').after(fieldHTML);
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
        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        //$(".fieldGroupCopy_email").find('.second_email').intlTelInput("setCountry", countryData.iso2);
        $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", $(".main_contact_email").val());

        var fieldHTML = '<div class="input-group fieldGroup_contact_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_contact_email").html()+'</div>';

        //$('body').find('.fieldGroup_contact_email:first').after(fieldHTML);
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

if(vendor_info['foreign_address1'] != "" || vendor_info['foreign_address2'] != "" || vendor_info['foreign_address3'] != "")
{
    if (undefined !== vendor_info['foreign_address1'] && vendor_info['foreign_address1'].length || undefined !== vendor_info['foreign_address2'] && vendor_info['foreign_address2'].length) 
    {
        $('input[name="vendor_foreign_address1"]').removeAttr('disabled');
        $('input[name="vendor_foreign_address2"]').removeAttr('disabled');
        $('input[name="vendor_foreign_address3"]').removeAttr('disabled');
    }
    else
    {
        $('input[name="vendor_foreign_address1"]').attr('disabled', 'true');
        $('input[name="vendor_foreign_address2"]').attr('disabled', 'true');
        $('input[name="vendor_foreign_address3"]').attr('disabled', 'true');
    }
    /*$('input[name="foreign_address1"]').removeAttr('disabled');
    $('input[name="foreign_address2"]').removeAttr('disabled');*/
    
}
else if(vendor_info['foreign_address1'] == "" || vendor_info['foreign_address2'] == "" || vendor_info['foreign_address3'] == "")
{
    $('input[name="vendor_foreign_address1"]').attr('disabled', 'true');
    $('input[name="vendor_foreign_address2"]').attr('disabled', 'true');
    $('input[name="vendor_foreign_address3"]').attr('disabled', 'true');
}
else
{
    $('input[name="vendor_foreign_address1"]').attr('disabled', 'true');
    $('input[name="vendor_foreign_address2"]').attr('disabled', 'true');
    $('input[name="vendor_foreign_address3"]').attr('disabled', 'true');
}

if(vendor_info['postal_code'] != "")
{

    $('input[name="vendor_postal_code"]').removeAttr('disabled');

}
else
{
    $('input[name="vendor_postal_code"]').attr('disabled', 'true');
}

if(vendor_info['street_name'] != "")
{

    $('input[name="vendor_street_name"]').removeAttr('disabled');

}
else
{
    $('input[name="vendor_street_name"]').attr('disabled', 'true');
}

$("#local_edit").click(function() {
    $("#tr_foreign_edit").hide();
    $("#tr_local_edit").show();

    var foreign_address1 = document.getElementById('vendor_foreign_address1');
    var foreign_address2 = document.getElementById('vendor_foreign_address2');
    var foreign_address3 = document.getElementById('vendor_foreign_address3');

    $('input[name="vendor_postal_code"]').removeAttr('disabled');
    $('input[name="vendor_street_name"]').removeAttr('disabled');

    $('input[name="vendor_foreign_address1"]').attr('disabled', 'true');
    $('input[name="vendor_foreign_address2"]').attr('disabled', 'true');
    $('input[name="vendor_foreign_address3"]').attr('disabled', 'true');

    switch (foreign_address1.type) {
        case 'text':
            foreign_address1.value = '';
            break;
    }

    switch (foreign_address2.type) {
        case 'hidden':
        case 'text':
            foreign_address2.value = '';
            break;
        case 'radio':
        case 'checkbox': 
    }

    switch (foreign_address3.type) {
        case 'hidden':
        case 'text':
            foreign_address3.value = '';
            break;
        case 'radio':
        case 'checkbox': 
    }
});

$("#foreign_edit").click(function() {
    $("#tr_foreign_edit").show();
    $("#tr_local_edit").hide();

    $('input[name="vendor_postal_code"]').attr('disabled', 'true');
    $('input[name="vendor_street_name"]').attr('disabled', 'true');
    
    $('input[name="vendor_foreign_address1"]').removeAttr('disabled');
    $('input[name="vendor_foreign_address2"]').removeAttr('disabled');
    $('input[name="vendor_foreign_address3"]').removeAttr('disabled');

    window['postal_code'] = document.getElementById('vendor_postal_code');
    window['street_name'] = document.getElementById('vendor_street_name');
    window['building_name'] = document.getElementById('vendor_building_name');

    switch (window['postal_code'].type) {
        case 'text':
            window['postal_code'].value = '';
            break;
    }
    switch (window['street_name'].type) {
        case 'text':
            window['street_name'].value = '';
            break;
    }
    switch (window['building_name'].type) {
        case 'text':
            window['building_name'].value = '';
            break;
    }
    
    for (var i = 1; i < 3; i++) {
        window['unit_no'+i] = document.getElementById('vendor_unit_no'+i);
        switch (window['unit_no'+i].type) {
            case 'text':
                window['unit_no'+i].value = '';
                break;
        }
    }

});