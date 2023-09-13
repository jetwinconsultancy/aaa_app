$('.show_company_email').click(function(e){
    e.preventDefault();
    $(this).closest('td').find(".company_email_toggle").toggle();
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

$('.show_company_phone_number').click(function(e){
    e.preventDefault();
    $(this).closest('td').find(".company_phone_number_toggle").toggle();
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

if(officer_company_email != null)
{
	for (var h = 0; h < officer_company_email.length; h++) 
	{
	  	var officerCompanyEmailArray = officer_company_email[h].split(',');

	  	if(officerCompanyEmailArray[2] == 1)
	  	{
	  		$(".fieldGroup_company_email").find('.main_company_email').attr("value", officerCompanyEmailArray[1]);
	  		$(".fieldGroup_company_email").find('.main_company_email_primary').attr("value", officerCompanyEmailArray[1]);

	  		$(".fieldGroup_company_email").find(".button_increment_company_email").css({"visibility": "visible"});
	  	}
	  	else
	  	{
	  		$(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", officerCompanyEmailArray[1]);

        	$(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", officerCompanyEmailArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_company_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_company_email").html()+'</div>';

            //$('body').find('.fieldGroup_company_email:first').after(fieldHTML);
            $( fieldHTML).prependTo(".company_email_toggle");

			$(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", "");
            $(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", "");
	  	}
	}
}


if(officer_company_phone_number != null)
{
	for (var h = 0; h < officer_company_phone_number.length; h++) 
	{
	  	var officerCompanyPhoneNumberArray = officer_company_phone_number[h].split(',');



	  	if(officerCompanyPhoneNumberArray[2] == 1)
	  	{
	  		$(".fieldGroup_company_phone_number").find('.main_company_phone_number').intlTelInput("setNumber", officerCompanyPhoneNumberArray[1]);
	  		$(".fieldGroup_company_phone_number").find('.main_hidden_company_phone_number').attr("value", officerCompanyPhoneNumberArray[1]);
	  		$(".fieldGroup_company_phone_number").find('.main_company_phone_number_primary').attr("value", officerCompanyPhoneNumberArray[1]);
	  		$(".fieldGroup_company_phone_number").find(".button_increment_company_phone_number").css({"visibility": "visible"});
	  	}
	  	else
	  	{
	  		
        	$(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", officerCompanyPhoneNumberArray[1]);
        	$(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", officerCompanyPhoneNumberArray[1]);


            var fieldHTML = '<div class="input-group fieldGroup_company_phone_number" style="margin-top:10px;">'+$(".fieldGroupCopy_company_phone_number").html()+'</div>';

            //$('body').find('.fieldGroup_company_phone_number:first').after(fieldHTML);
            $( fieldHTML).prependTo(".company_phone_number_toggle");

            $('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').intlTelInput({
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

			$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').intlTelInput("setNumber", officerCompanyPhoneNumberArray[1]);

			$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').on({
			  keydown: function(e) {
			    if (e.which === 32)
			      return false;
			  },
			  change: function() {
			    this.value = this.value.replace(/\s/g, "");
			  }
			});

            $(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", "");
            $(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", "");
	  	}
	}
}
else
{
	$(".fieldGroup_company_phone_number").find('.main_company_phone_number').intlTelInput("setNumber", "");
}

//put to hidden and radio button value when finish typing
//company
$(document).on('blur', '.check_empty_company_email', function(){

    $(this).parent().find(".company_email_primary").attr("value", $(this).val());
});

$(document).on('blur', '.check_empty_company_phone_number', function(){

    $(this).parent().parent().find(".hidden_company_phone_number").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".company_phone_number_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).ready(function() {
	$(document).on('click', '.company_email_primary', function(event){	
		event.preventDefault();
		var company_email_primary_radio_button = $(this);
    	bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
            if (result) {
            	company_email_primary_radio_button.prop( "checked", true );
            }
        });
	});

	$(document).on('click', '.company_phone_number_primary', function(event){	
		event.preventDefault();
		var company_phone_number_primary_radio_button = $(this);
    	bootbox.confirm("Are you comfirm set as primary for this Phone Number?", function (result) {
            if (result) {
            	company_phone_number_primary_radio_button.prop( "checked", true );
            }
        });
	});

	$(".check_empty_company_phone_number").on({
	  keydown: function(e) {
	    if (e.which === 32)
	      return false;
	  },
	  change: function() {
	    this.value = this.value.replace(/\s/g, "");
	  }
	});

	//company_phone_number
	$(".addMore_company_phone_number").click(function(){

    	var number = $(".main_company_phone_number").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

    	var countryData = $(".main_company_phone_number").intlTelInput("getSelectedCountryData");

    	$(".company_phone_number_toggle").show();
    	$(".show_company_phone_number").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
    	$(".show_company_phone_number").find(".toggle_word").text('Show less');

    	$(".fieldGroupCopy_company_phone_number").find('.second_company_phone_number').attr("value", $(".main_company_phone_number").val());
    	$(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", number);
    	$(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", number);

        var fieldHTML = '<div class="input-group fieldGroup_company_phone_number" style="margin-top:10px;">'+$(".fieldGroupCopy_company_phone_number").html()+'</div>';

        $( fieldHTML).prependTo(".company_phone_number_toggle");

        $('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').intlTelInput({
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

		$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').on({
		  keydown: function(e) {
		    if (e.which === 32)
		      return false;
		  },
		  change: function() {
		    this.value = this.value.replace(/\s/g, "");
		  }
		});

		if ($(".main_company_phone_number_primary").is(":checked")) 
		{
			$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.company_phone_number_primary').prop( "checked", true );
		}


        $(".button_increment_company_phone_number").css({"visibility": "hidden"});

        if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
		{
			$(".show_company_phone_number").css({"visibility": "visible"});

       	}
       	else {
       		$(".show_company_phone_number").css({"visibility": "hidden"});
       		
       	}
       
        $(".main_company_phone_number").val("");
        $(".main_company_phone_number").parent().parent().find(".hidden_company_phone_number").val("");
        $(".main_company_phone_number").parent().parent().find(".company_phone_number_primary").val("");
        $(".fieldGroupCopy_company_phone_number").find('.second_company_phone_number').attr("value", "");
        $(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", "");
        $(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", "");

    });

    $("body").on("click",".remove_company_phone_number",function(){ 
        var remove_company_phone_number_button = $(this);
    	bootbox.confirm("Are you comfirm delete this Phone Number?", function (result) {
            if (result) {

            	remove_company_phone_number_button.parents(".fieldGroup_company_phone_number").remove();

            	if (remove_company_phone_number_button.parent().find(".company_phone_number_primary").is(":checked")) 
				{
					if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
					{
						$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.company_phone_number_primary').prop( "checked", true );
					}
					else
					{
						$(".main_company_phone_number_primary").prop( "checked", true );
					}
					
				}

            	if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
				{
					$(".show_company_phone_number").css({"visibility": "visible"});

		       	}
		       	else {
		       		$(".show_company_phone_number").css({"visibility": "hidden"});
		       		
		       	}
            }
        });
    });

	$('.main_company_phone_number').keyup(function(){

		if ($(this).val()) {
			$(".button_increment_company_phone_number").css({"visibility": "visible"});

       	}
       	else {
       		$(".button_increment_company_phone_number").css({"visibility": "hidden"});
       	}
	});

	//company_email
	$(".addMore_company_email").click(function(){

		$(".company_email_toggle").show();
    	$(".show_company_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
    	$(".show_company_email").find(".toggle_word").text('Show less');

    	$(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", $(".main_company_email").val());

    	$(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", $(".main_company_email").val());

        var fieldHTML = '<div class="input-group fieldGroup_company_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_company_email").html()+'</div>';

        $( fieldHTML).prependTo(".company_email_toggle");

        if ($(".main_company_email_primary").is(":checked")) 
		{
			$(".company_email_toggle .fieldGroup_company_email").eq(0).find('.company_email_primary').prop( "checked", true );
		}
		
        $(".button_increment_company_email").css({"visibility": "hidden"});
       
       if ($(".company_email_toggle").find(".second_company_email").length > 0) 
		{
			$(".show_company_email").css({"visibility": "visible"});

       	}
       	else {
       		$(".show_company_email").css({"visibility": "hidden"});
       		
       	}

        $(".main_company_email").val("");
        $(".main_company_email").parent().find(".main_company_email_primary").val("");
        $(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", "");
        $(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", "");

    });

    $("body").on("click",".remove_company_email",function(){ 
        var remove_company_email_button = $(this);
    	bootbox.confirm("Are you comfirm delete this Email?", function (result) {
            if (result) {

            	remove_company_email_button.parents(".fieldGroup_company_email").remove();

            	if (remove_company_email_button.parent().find(".company_email_primary").is(":checked")) 
				{
					if ($(".company_email_toggle").find(".second_company_email").length > 0) 
					{
						$(".company_email_toggle .fieldGroup_company_email").eq(0).find('.company_email_primary').prop( "checked", true );
					}
					else
					{
						$(".main_company_email_primary").prop( "checked", true );
					}
				}

            	if ($(".company_email_toggle").find(".second_company_email").length > 0) 
				{
					$(".show_company_email").css({"visibility": "visible"});

		       	}
		       	else {
		       		$(".show_company_email").css({"visibility": "hidden"});
		       		
		       	}
            }
        });
    });

	$('.main_company_email').keyup(function(){

		if ($(this).val()) {
			$(".button_increment_company_email").css({"visibility": "visible"});

       	}
       	else {
       		$(".button_increment_company_email").css({"visibility": "hidden"});
       	}
	});

   	if ($(".company_email_toggle").find(".second_company_email").length > 0) 
	{
		$(".show_company_email").css({"visibility": "visible"});
		$(".company_email_toggle").hide();

   	}
   	else {
   		$(".show_company_email").css({"visibility": "hidden"});
   		$(".company_email_toggle").hide();
   	}

   	if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
	{
		$(".show_company_phone_number").css({"visibility": "visible"});
		$(".company_phone_number_toggle").hide();

   	}
   	else {
   		$(".show_company_phone_number").css({"visibility": "hidden"});
   		$(".company_phone_number_toggle").hide();
   	}
});

$("#company_name").live('change',function(){
	$( '#form_company_name' ).html("");
});
$("#register_no").live('change',function(){
	$( '#form_register_no' ).html("");
});
$("#date_of_incorporation").live('change',function(){
	$( '#form_date_of_incorporation' ).html("");
});
$("#country_of_incorporation").live('change',function(){
	$( '#form_country_of_incorporation' ).html("");
});
$("#company_postal_code").live('change',function(){
	$( '#form_company_postal_code' ).html("");
});
$("#company_street_name").live('change',function(){
	$( '#form_company_street_name' ).html("");
});
$("#company_foreign_address1").live('change',function(){
	$( '#form_company_foreign_address1' ).html("");
});
$("#company_foreign_address2").live('change',function(){
	$( '#form_company_foreign_address2' ).html("");
});
$("#company_email").live('change',function(){
	$( '#form_company_email' ).html("");
});
$("#company_phone_number").live('change',function(){
	$( '#form_company_phone_number' ).html("");
});
$("#company_corporate_representative").live('change',function(){
	$( '#form_company_corporate_representative' ).html("");
});


$(document).on('submit', '#submit_company', function (e) {
    e.preventDefault();
    var form = $('#submit_company');
    $('#loadingmessage').show();
    $(".company_phone_number_disabled .check_empty_company_phone_number").attr("disabled", "disabled");
	$(".company_phone_number_disabled .hidden_company_phone_number").attr("disabled", "disabled");
	$(".company_email_disabled .check_empty_company_email").attr("disabled", "disabled");
    $.ajax({ //Upload common input
        url: "personprofile/updateCompany",
        type: "POST",
        data: form.serialize(),
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	$(".company_phone_number_disabled .check_empty_company_phone_number").removeAttr("disabled");
			$(".company_phone_number_disabled .hidden_company_phone_number").removeAttr("disabled");
			$(".company_email_disabled .check_empty_company_email").removeAttr("disabled");
        	//console.log(response.Status);
            if (response.Status === 1) {
            	$('#submit_company #officer_company_id').val(response.officer_company_id);
            	$('#submit_company #old_register_no').val(response.old_register_no);
            	$('#kycScreeningIndividual-form #coporate_officer_id').val(response.officer_company_id);
            	corporate_reload_link = response.corporate_reload_link;
			    $('#multiple_company_file').fileinput('upload');
            }
            else if(response.Status === 2)
            {
            	toastr.error("This Register No already in the system.", "Error");
            }
            else
            {
            	toastr.error("Please complete all required field", "Error");
            	if (response.error["register_no"] != "")
            	{
            		var errorsRegisterNo = '<span class="help-block">*' + response.error["register_no"] + '</span>';
            		$( '#form_register_no' ).html( errorsRegisterNo );

            	}
            	else
            	{
            		var errorsRegisterNo = '';
            		$( '#form_register_no' ).html( errorsRegisterNo );
            	}

            	if (response.error["company_name"] != "")
            	{
            		var errorsCompanyName = '<span class="help-block">*' + response.error["company_name"] + '</span>';
            		$( '#form_company_name' ).html( errorsCompanyName );

            	}
            	else
            	{
            		var errorsCompanyName = '';
            		$( '#form_company_name' ).html( errorsCompanyName );
            	}

				/*if (response.error["date_of_incorporation"] != "")
            	{
            		var errorsDateOfIncorporation = '<span class="help-block">*' + response.error["date_of_incorporation"] + '</span>';
            		$( '#form_date_of_incorporation' ).html( errorsDateOfIncorporation );

            	}
            	else
            	{
            		var errorsDateOfIncorporation = '';
            		$( '#form_date_of_incorporation' ).html( errorsDateOfIncorporation );
            	}

            	if (response.error["country_of_incorporation"] != "")
            	{
            		var errorsCountryOfIncorporation = '<span class="help-block">*' + response.error["country_of_incorporation"] + '</span>';
            		$( '#form_country_of_incorporation' ).html( errorsCountryOfIncorporation );

            	}
            	else
            	{
            		var errorsCountryOfIncorporation = '';
            		$( '#form_country_of_incorporation' ).html( errorsCountryOfIncorporation );
            	}*/

            	if (response.error["company_postal_code"] != "")
            	{
            		var errorsCompanyPostalCode = '<span class="help-block">*' + response.error["company_postal_code"] + '</span>';
            		$( '#form_company_postal_code' ).html( errorsCompanyPostalCode );

            	}
            	else
            	{
            		var errorsCompanyPostalCode = '';
            		$( '#form_company_postal_code' ).html( errorsCompanyPostalCode );
            	}

            	if (response.error["company_street_name"] != "")
            	{
            		var errorsCompanyStreetName = '<span class="help-block">*' + response.error["company_street_name"] + '</span>';
            		$( '#form_company_street_name' ).html( errorsCompanyStreetName );

            	}
            	else
            	{
            		var errorsCompanyStreetName = '';
            		$( '#form_company_street_name' ).html( errorsCompanyStreetName );
            	}

            	if (response.error["company_foreign_address1"] != "")
            	{
            		var errorsComapanyForeignAdd1 = '<span class="help-block">*' + response.error["company_foreign_address1"] + '</span>';
            		$( '#form_company_foreign_address1' ).html( errorsComapanyForeignAdd1 );

            	}
            	else
            	{
            		var errorsComapanyForeignAdd1 = '';
            		$( '#form_company_foreign_address1' ).html( errorsComapanyForeignAdd1 );
            	}

            	/*if (response.error["company_foreign_address2"] != "")
            	{
            		var errorsComapanyForeignAdd2 = '<span class="help-block">*' + response.error["company_foreign_address2"] + '</span>';
            		$( '#form_company_foreign_address2' ).html( errorsComapanyForeignAdd2 );

            	}
            	else
            	{
            		var errorsComapanyForeignAdd2 = '';
            		$( '#form_company_foreign_address2' ).html( errorsComapanyForeignAdd2 );
            	}*/

            	if (response.error["company_email"] != "")
            	{
            		var errorsComapanyEmail = '<span class="help-block">*' + response.error["company_email"] + '</span>';
            		$( '#form_company_email' ).html( errorsComapanyEmail );

            	}
            	else
            	{
            		var errorsComapanyEmail = '';
            		$( '#form_company_email' ).html( errorsComapanyEmail );
            	}

            	if (response.error["company_phone_number"] != "")
            	{
            		var errorsComapanyPhoneNumber = '<span class="help-block">*' + response.error["company_phone_number"] + '</span>';
            		$( '#form_company_phone_number' ).html( errorsComapanyPhoneNumber );

            	}
            	else
            	{
            		var errorsComapanyPhoneNumber = '';
            		$( '#form_company_phone_number' ).html( errorsComapanyPhoneNumber );
            	}

            	/*if (response.error["company_corporate_representative"] != "")
            	{
            		var errorsComapanyCorporateRepresentative = '<span class="help-block">*' + response.error["company_corporate_representative"] + '</span>';
            		$( '#form_company_corporate_representative' ).html( errorsComapanyCorporateRepresentative );

            	}
            	else
            	{
            		var errorsComapanyCorporateRepresentative = '';
            		$( '#form_company_corporate_representative' ).html( errorsComapanyCorporateRepresentative );
            	}*/
            }
        }
	})
});

$('#company_postal_code').keyup(function(){
	if($(this).val().length == 6){
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

		        $("#company_street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

		        if(placemark.AddressDetails.Country.AddressLine == "undefined")
		        {
		        	$("#company_building_name").val("");
		        }
		        else
		        {
		        	$("#company_building_name").val(placemark.AddressDetails.Country.AddressLine);
		        }
		      }
		      $( '#form_company_postal_code' ).html('');
		      $( '#form_company_street_name' ).html('');
		    } else if (status.code == 603) {
		    	$( '#form_company_postal_code' ).html('<span class="help-block">*No Record Found</span>');
		    }

		  },
		  statusCode: {
		    404: function() {
		      alert('Page not found');
		    }
		  },
	    });
	}
	else
	{
		$("#company_street_name").val("");
		$("#company_building_name").val("");
	}
});

if(person['company_foreign_address1'] != "" || person['company_foreign_address2'] != "" || person['company_foreign_address3'] != "")
{
	$('input[name="company_foreign_address1"]').removeAttr('disabled');
	$('input[name="company_foreign_address2"]').removeAttr('disabled');
	$('input[name="company_foreign_address3"]').removeAttr('disabled');
}
else if(person['company_foreign_address1'] == "" || person['company_foreign_address2'] == "" || person['company_foreign_address3'] == "")
{
	$('input[name="company_foreign_address1"]').attr('disabled', 'true');
	$('input[name="company_foreign_address2"]').attr('disabled', 'true');
	$('input[name="company_foreign_address3"]').attr('disabled', 'true');
}
else
{
	$('input[name="company_foreign_address1"]').attr('disabled', 'true');
	$('input[name="company_foreign_address2"]').attr('disabled', 'true');
	$('input[name="company_foreign_address3"]').attr('disabled', 'true');
}

if(person['company_postal_code'] != "")
{
	$('input[name="company_postal_code"]').removeAttr('disabled');
}
else
{
	$('input[name="company_postal_code"]').attr('disabled', 'true');
}

if(person['company_street_name'] != "")
{
	$('input[name="company_street_name"]').removeAttr('disabled');
}
else
{
	$('input[name="company_street_name"]').attr('disabled', 'true');
}

if(company_files != null)
{
	for (var i = 0; i < company_files.length; i++) {
		
	  var url = base_url + "uploads/company_images_or_pdf/";
	  var fileArray = company_files[i].split(',');
	  //console.log(fileArray[0]);
	  initialPreviewArray.push( url + fileArray[1] );
	  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
	  //console.log(file_type);
	  	if(file_type == ".pdf" || file_type == ".PDF")
	  	{
		  initialPreviewConfigArray.push({
			  type: "pdf",
		      caption: fileArray[1],
		      url: "/secretary/personprofile/deleteCompanyFile/" + fileArray[0],
		      width: "120px",
		      key: i+1
		  });
		}
		else
		{
			initialPreviewConfigArray.push({
		      caption: fileArray[1],
		      url: "/secretary/personprofile/deleteCompanyFile/" + fileArray[0],
		      width: "120px",
		      key: i+1
		  });
		}
	}
}

$("#multiple_company_file").fileinput({
    theme: 'fa',
    uploadUrl: '/secretary/personprofile/uploadCompanyFile', // you must set a valid URL here else you will get an error
    uploadAsync: false,
    browseClass: "btn btn-primary",
    fileType: "any",
    showCaption: false,
    showUpload: false,
    showRemove: false,
    fileActionSettings: {
                    showRemove: true,
                    showUpload: false,
                    showZoom: true,
                    showDrag: true,
                },
    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
    overwriteInitial: false,
    initialPreviewAsData: true,
    initialPreviewDownloadUrl: base_url + 'uploads/company_images_or_pdf/{filename}',
    initialPreview: initialPreviewArray,
 	initialPreviewConfig: initialPreviewConfigArray,
 	//deleteUrl: "/dot/personprofile/deleteFile",
 	/*maxFileSize: 20000048,
 	maxImageWidth: 1000,
    maxImageHeight: 1500,
    resizePreference: 'height',
    resizeImage: true,*/
 	purifyHtml: true // this by default purifies HTML data for preview
    /*uploadExtraData: { 
    	officer_id: $('input[name="officer_id"]').val() 
    }*/
    /*width:auto;height:auto;max-width:100%;max-height:100%;*/

}).on('filesorted', function(e, params) {
    //console.log('File sorted params', params);
}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
	if($("#close_page").val() == 1)
	{
		window.close();
	}
	else
	{
		//window.location.href = base_url + "personprofile";
		if(corporate_reload_link != false)
		{
			window.location.href = corporate_reload_link;
		}
		toastr.success("Information Updated", "Success");
	}
	
    //console.log(data);
}).on('fileuploaderror', function(event, data, msg) {
	if($("#close_page").val() == 1)
	{
		window.close();
	}
	else
	{
		//window.location.href = base_url + "personprofile";
		if(corporate_reload_link != false)
		{
			window.location.href = corporate_reload_link;
		}
		toastr.success("Information Updated", "Success");
	}
	//toastr.error("Error", "Error");
});

$("#company_local_edit").click(function() {
	$("#tr_company_foreign_edit").hide();
	$("#tr_company_local_edit").show();

	var company_foreign_address1 = document.getElementById('company_foreign_address1');
	var company_foreign_address2 = document.getElementById('company_foreign_address2');
	var company_foreign_address3 = document.getElementById('company_foreign_address3');

	$('input[name="company_postal_code"]').removeAttr('disabled');
	$('input[name="company_street_name"]').removeAttr('disabled');

	$('input[name="company_foreign_address1"]').attr('disabled', 'true');
	$('input[name="company_foreign_address2"]').attr('disabled', 'true');
	$('input[name="company_foreign_address3"]').attr('disabled', 'true');

	$("#company_street_name").attr("readonly", false);
	$("#company_building_name").attr("readonly", false);

    switch (company_foreign_address1.type) {
        case 'text':
            company_foreign_address1.value = '';
            break;
    }

    switch (company_foreign_address2.type) {
        case 'hidden':
        case 'text':
            company_foreign_address2.value = '';
            break;
        case 'radio':
        case 'checkbox': 
    }
	switch (company_foreign_address3.type) {
        case 'hidden':
        case 'text':
            company_foreign_address3.value = '';
            break;
        case 'radio':
        case 'checkbox': 
    }
});

$("#company_foreign_edit").click(function() {
	$("#tr_company_foreign_edit").show();
	$("#tr_company_local_edit").hide();

    $('input[name="company_postal_code"]').attr('disabled', 'true');
    $('input[name="company_street_name"]').attr('disabled', 'true');
    $('input[name="company_foreign_address1"]').removeAttr('disabled');
	$('input[name="company_foreign_address2"]').removeAttr('disabled');
	$('input[name="company_foreign_address3"]').removeAttr('disabled');

	$("#company_street_name").attr("readonly", false);
	$("#company_building_name").attr("readonly", false);

	window['company_postal_code'] = document.getElementById('company_postal_code');
	window['company_street_name'+i] = document.getElementById('company_street_name');
	window['company_building_name'+i] = document.getElementById('company_building_name');

	switch (window['company_postal_code'].type) {
        case 'text':
            window['company_postal_code'].value = '';
            break;
    }
    switch (window['company_street_name'].type) {
        case 'text':
            window['company_street_name'].value = '';
            break;
    }
    switch (window['company_building_name'].type) {
        case 'text':
            window['company_building_name'].value = '';
            break;
    }

	for (var i = 1; i < 3; i++) {
		window['company_unit_no'+i] = document.getElementById('company_unit_no'+i);
		switch (window['company_unit_no'+i].type) {
            case 'text':
                window['company_unit_no'+i].value = '';
                break;
        }
	}
});

$("#company_edit").click(function() {
	$("#tr_individual_edit").hide();
	$("#tr_company_edit").show();

	var identification_type = document.getElementById('identification_type');
	identification_type.selectedIndex = 0;

	var individual_identification_no = document.getElementById('individual_identification_no');
	var identification_no = document.getElementById('identification_no');
	individual_identification_no.value = identification_no.value;
    identification_no.value = '';

    var name = document.getElementById('name');
    name.value = '';
    var date_of_birth = document.getElementById('date_of_birth');
    //console.log(date_of_birth);
    date_of_birth.value = '';

	var alternate_address = document.getElementById('alternate_address');
	if(alternate_address.checked){
        $("#alternate_text_edit").toggle();
    }
    alternate_address.checked = false; 

    var local_edit = document.getElementById('local_edit');
    local_edit.checked = "checked"; 

    var foreign_edit = document.getElementById('foreign_edit');
    foreign_edit.checked = false; 

    $("#tr_foreign_edit").hide();
	$("#tr_local_edit").show();

	$('input[name="postal_code1"]').removeAttr('disabled');
	$('input[name="street_name1"]').removeAttr('disabled');
	$('input[name="postal_code2"]').removeAttr('disabled');
	$('input[name="street_name2"]').removeAttr('disabled');

	$('input[name="foreign_address1"]').attr('disabled', 'true');
	$('input[name="foreign_address2"]').attr('disabled', 'true');
	$('input[name="foreign_address3"]').attr('disabled', 'true');

	$('input[name="company_foreign_address1"]').attr('disabled', 'true');
	$('input[name="company_foreign_address2"]').attr('disabled', 'true');
	$('input[name="company_foreign_address3"]').attr('disabled', 'true');

	$("#company_street_name").attr("readonly", false);
	$("#company_building_name").attr("readonly", false);

    for (var i = 1; i < 3; i++) {
		window['postal_code'+i] = document.getElementById('postal_code'+i);
		window['street_name'+i] = document.getElementById('street_name'+i);
		window['building_name'+i] = document.getElementById('building_name'+i);

        window['postal_code'+i].value = '';
        window['street_name'+i].value = '';
        window['building_name'+i].value = '';
	}
	for (var i = 1; i < 5; i++) {
		window['unit_no'+i] = document.getElementById('unit_no'+i);
        window['unit_no'+i].value = '';
	}

	// var local_fix_line = document.getElementById('local_fix_line');
	// var local_mobile = document.getElementById('local_mobile');
	// var email = document.getElementById('email');

	// local_fix_line.value = '';
 //    local_mobile.value = '';
 //    email.value = '';
});