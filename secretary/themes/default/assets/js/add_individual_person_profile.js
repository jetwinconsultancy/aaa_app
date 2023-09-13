$('.show_local_fix_line').click(function(e){
    e.preventDefault();
    $(this).closest('td').find(".local_fix_line_toggle").toggle();
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

$('.show_local_mobile').click(function(e){
    e.preventDefault();
    $(this).closest('td').find(".local_mobile_toggle").toggle();
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

$('.show_email').click(function(e){
    e.preventDefault();
    $(this).closest('td').find(".local_email_toggle").toggle();
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
if(officer_fixed_line_no != null)
{
	for (var h = 0; h < officer_fixed_line_no.length; h++) 
	{
	  	var officerFixLineNoArray = officer_fixed_line_no[h].split(',');

	  	if(officerFixLineNoArray[2] == 1)
	  	{
	  		$(".fieldGroup_local_fix_line").find('.main_local_fix_line').intlTelInput("setNumber", officerFixLineNoArray[1]);
	  		$(".fieldGroup_local_fix_line").find('.main_hidden_local_fix_line').attr("value", officerFixLineNoArray[1]);
	  		$(".fieldGroup_local_fix_line").find('.main_fixed_line_no_primary').attr("value", officerFixLineNoArray[1]);
	  		$(".fieldGroup_local_fix_line").find(".button_increment_local_fix_line").css({"visibility": "visible"});
	  	}
	  	else
	  	{
	  		
        	$(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", officerFixLineNoArray[1]);
        	$(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", officerFixLineNoArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_local_fix_line" style="margin-top:10px;">'+$(".fieldGroupCopy_local_fix_line").html()+'</div>';

            $( fieldHTML).prependTo(".local_fix_line_toggle");
            
            $('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').intlTelInput({
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

			$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').intlTelInput("setNumber", officerFixLineNoArray[1]);

			$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(1).find('.second_hp').on({
			  keydown: function(e) {
			    if (e.which === 32)
			      return false;
			  },
			  change: function() {
			    this.value = this.value.replace(/\s/g, "");
			  }
			});

            $(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", "");
            $(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", "");
	  	}
	}
}
else
{
	$(".fieldGroup_local_fix_line").find('.main_local_fix_line').intlTelInput("setNumber", "");
}

if(officer_mobile_no != null)
{
	for (var h = 0; h < officer_mobile_no.length; h++) 
	{
	  	var officerMobileNoArray = officer_mobile_no[h].split(',');

	  	if(officerMobileNoArray[2] == 1)
	  	{
	  		$(".fieldGroup_local_mobile").find('.main_local_mobile').intlTelInput("setNumber", officerMobileNoArray[1]);
	  		$(".fieldGroup_local_mobile").find('.main_hidden_local_mobile').attr("value", officerMobileNoArray[1]);
	  		$(".fieldGroup_local_mobile").find('.main_local_mobile_primary').attr("value", officerMobileNoArray[1]);
	  		$(".fieldGroup_local_mobile").find(".button_increment_local_mobile").css({"visibility": "visible"});
	  	}
	  	else
	  	{
	  		
        	$(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", officerMobileNoArray[1]);
        	$(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", officerMobileNoArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_local_mobile" style="margin-top:10px;">'+$(".fieldGroupCopy_local_mobile").html()+'</div>';

            //$('body').find('.fieldGroup_local_mobile:first').after(fieldHTML);
            $( fieldHTML).prependTo(".local_mobile_toggle");

            $('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').intlTelInput({
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

			$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').intlTelInput("setNumber", officerMobileNoArray[1]);

			$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').on({
			  keydown: function(e) {
			    if (e.which === 32)
			      return false;
			  },
			  change: function() {
			    this.value = this.value.replace(/\s/g, "");
			  }
			});

            $(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", "");
            $(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", "");
	  	}
	}
}

if(officer_email != null)
{
	for (var h = 0; h < officer_email.length; h++) 
	{
	  	var officerEmailArray = officer_email[h].split(',');

	  	if(officerEmailArray[2] == 1)
	  	{
	  		$(".fieldGroup_email").find('.main_email').attr("value", officerEmailArray[1]);
	  		$(".fieldGroup_email").find('.main_email_primary').attr("value", officerEmailArray[1]);

	  		$(".fieldGroup_email").find(".button_increment_email").css({"visibility": "visible"});
	  	}
	  	else
	  	{
	  		$(".fieldGroupCopy_email").find('.second_email').attr("value", officerEmailArray[1]);

        	$(".fieldGroupCopy_email").find('.email_primary').attr("value", officerEmailArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_email" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_email").html()+'</div>';

            //$('body').find('.fieldGroup_email:first').after(fieldHTML);
            $( fieldHTML).prependTo(".local_email_toggle");

			$(".fieldGroupCopy_email").find('.second_email').attr("value", "");
            $(".fieldGroupCopy_email").find('.email_primary').attr("value", "");
	  	}
	}
}

//put to hidden and radio button value when finish typing
//individual
$(document).on('blur', '.check_empty_local_fix_line', function(){
    $(this).parent().parent().find(".hidden_local_fix_line").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".fixed_line_no_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).on('blur', '.check_empty_local_mobile', function(){
	//console.log($(this).val());
	$(this).parent().parent().find(".hidden_local_mobile").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".local_mobile_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).on('blur', '.check_empty_email', function(){

    $(this).parent().find(".email_primary").attr("value", $(this).val());
});


$(document).ready(function() {

	$(document).on('click', '.fixed_line_no_primary', function(event){
		event.preventDefault();
		var fixed_line_no_primary_radio_button = $(this);
    	bootbox.confirm("Are you comfirm set as primary for this Fixed Line No?", function (result) {
            if (result) {
            	fixed_line_no_primary_radio_button.prop( "checked", true );
            }
        });
	});
	
	$(document).on('click', '.local_mobile_primary', function(event){
		event.preventDefault();
		var local_mobile_primary_radio_button = $(this);
    	bootbox.confirm("Are you comfirm set as primary for this Mobile No?", function (result) {
            if (result) {
            	local_mobile_primary_radio_button.prop( "checked", true );
            }
        });
	    
	});

	$(document).on('click', '.email_primary', function(event){	
		event.preventDefault();
		var email_primary_radio_button = $(this);
    	bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
            if (result) {
            	email_primary_radio_button.prop( "checked", true );
            }
        });
	});

	$(".check_empty_local_fix_line").on({
	  keydown: function(e) {
	    if (e.which === 32)
	      return false;
	  },
	  change: function() {
	    this.value = this.value.replace(/\s/g, "");
	  }
	});

	$(".check_empty_local_mobile").on({
	  keydown: function(e) {
	    if (e.which === 32)
	      return false;
	  },
	  change: function() {
	    this.value = this.value.replace(/\s/g, "");
	  }
	});

	//individual_local_fix_line
	$(".addMore_local_fix_line").click(function(){
    	var number = $(".main_local_fix_line").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

    	var countryData = $(".main_local_fix_line").intlTelInput("getSelectedCountryData");

    	$(".local_fix_line_toggle").show();
    	$(".show_local_fix_line").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
    	$(".show_local_fix_line").find(".toggle_word").text('Show less');
    	

    	$(".fieldGroupCopy_local_fix_line").find('.second_local_fix_line').attr("value", $(".main_local_fix_line").val());

    	$(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", number);
    	$(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", number);

        var fieldHTML = '<div class="input-group fieldGroup_local_fix_line" style="margin-top:10px;">'+$(".fieldGroupCopy_local_fix_line").html()+'</div>';

       	$( fieldHTML).prependTo(".local_fix_line_toggle");
       	
        $('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').intlTelInput({
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

		$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').on({
		  keydown: function(e) {
		    if (e.which === 32)
		      return false;
		  },
		  change: function() {
		    this.value = this.value.replace(/\s/g, "");
		  }
		});

		if ($(".main_fixed_line_no_primary").is(":checked")) 
		{
			$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.fixed_line_no_primary').prop( "checked", true );
		}

        $(".button_increment_local_fix_line").css({"visibility": "hidden"});

        if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
		{
			$(".show_local_fix_line").css({"visibility": "visible"});

       	}
       	else {
       		$(".show_local_fix_line").css({"visibility": "hidden"});
       		
       	}
       
        $(".main_local_fix_line").val("");
        $(".main_local_fix_line").parent().parent().find(".hidden_local_fix_line").val("");
        $(".main_local_fix_line").parent().parent().find(".fixed_line_no_primary").val("");
        $(".fieldGroupCopy_local_fix_line").find('.second_local_fix_line').attr("value", "");
        $(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", "");
        $(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", "");
    });

    $("body").on("click",".remove_local_fix_line",function(){ 
    	var remove_local_fix_line_button = $(this);
    	bootbox.confirm("Are you comfirm delete this Fixed Line No?", function (result) {
            if (result) {
            	
            	remove_local_fix_line_button.parents(".fieldGroup_local_fix_line").remove();

            	if (remove_local_fix_line_button.parent().find(".fixed_line_no_primary").is(":checked")) 
				{
					if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
					{
						$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.fixed_line_no_primary').prop( "checked", true );
					}
					else
					{
						$(".main_fixed_line_no_primary").prop( "checked", true );
					}
				}

            	if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
				{
					$(".show_local_fix_line").css({"visibility": "visible"});

		       	}
		       	else {
		       		$(".show_local_fix_line").css({"visibility": "hidden"});
		       		
		       	}
            }
        });
        
    });

	$('.main_local_fix_line').keyup(function(){

		if ($(this).val()) {
			$(".button_increment_local_fix_line").css({"visibility": "visible"});

       	}
       	else {
       		$(".button_increment_local_fix_line").css({"visibility": "hidden"});
       	}
	});

	//individual_mobile_no
	$(".addMore_local_mobile").click(function(){
    	var number = $(".main_local_mobile").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

    	var countryData = $(".main_local_mobile").intlTelInput("getSelectedCountryData");

    	$(".local_mobile_toggle").show();
    	$(".show_local_mobile").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
    	$(".show_local_mobile").find(".toggle_word").text('Show less');

    	$(".fieldGroupCopy_local_mobile").find('.second_local_mobile').attr("value", $(".main_local_mobile").val());
    	$(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", number);
    	$(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", number);

        var fieldHTML = '<div class="input-group fieldGroup_local_mobile" style="margin-top:10px;">'+$(".fieldGroupCopy_local_mobile").html()+'</div>';

        $( fieldHTML).prependTo(".local_mobile_toggle");

        $('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').intlTelInput({
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

		$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').on({
		  keydown: function(e) {
		    if (e.which === 32)
		      return false;
		  },
		  change: function() {
		    this.value = this.value.replace(/\s/g, "");
		  }
		});

		if ($(".main_local_mobile_primary").is(":checked")) 
		{
			$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.local_mobile_primary').prop( "checked", true );
		}

        $(".button_increment_local_mobile").css({"visibility": "hidden"});

        if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
		{
			$(".show_local_mobile").css({"visibility": "visible"});

       	}
       	else {
       		$(".show_local_mobile").css({"visibility": "hidden"});
       		
       	}

        $(".main_local_mobile").val("");
        $(".main_local_mobile").parent().parent().find(".hidden_local_mobile").val("");
        $(".main_local_mobile").parent().parent().find(".local_mobile_primary").val("");
        $(".fieldGroupCopy_local_mobile").find('.second_local_mobile').attr("value", "");
        $(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", "");
        $(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", "");
        $(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').prop( "checked", false );

    });

    $("body").on("click",".remove_local_mobile",function(){ 
        var remove_local_mobile_button = $(this);
    	bootbox.confirm("Are you comfirm delete this Mobile No?", function (result) {
            if (result) {
            	remove_local_mobile_button.parents(".fieldGroup_local_mobile").remove();

            	if (remove_local_mobile_button.parent().find(".local_mobile_primary").is(":checked")) 
				{
					if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
					{
						$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.local_mobile_primary').prop( "checked", true );
					}
					else
					{
						$(".main_local_mobile_primary").prop( "checked", true );
					}
				}

            	if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
				{
					$(".show_local_mobile").css({"visibility": "visible"});

		       	}
		       	else {
		       		$(".show_local_mobile").css({"visibility": "hidden"});
		       		
		       	}
		       	$( '#form_local_mobile' ).html("");
            }
        });
    });

	$('.main_local_mobile').keyup(function(){

		if ($(this).val()) {
			$(".button_increment_local_mobile").css({"visibility": "visible"});

       	}
       	else {
       		$(".button_increment_local_mobile").css({"visibility": "hidden"});
       	}
	});

	//individual_email
	$(".addMore_email").click(function(){

		$(".local_email_toggle").show();
    	$(".show_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
    	$(".show_email").find(".toggle_word").text('Show less');

    	$(".fieldGroupCopy_email").find('.second_email').attr("value", $(".main_email").val());

    	$(".fieldGroupCopy_email").find('.email_primary').attr("value", $(".main_email").val());

        var fieldHTML = '<div class="input-group fieldGroup_email" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_email").html()+'</div>';

        $( fieldHTML).prependTo(".local_email_toggle");

        if ($(".main_email_primary").is(":checked")) 
		{
			$(".local_email_toggle .fieldGroup_email").eq(0).find('.email_primary').prop( "checked", true );
		}

        $(".button_increment_email").css({"visibility": "hidden"});

        if ($(".local_email_toggle").find(".second_email").length > 0) 
		{
			$(".show_email").css({"visibility": "visible"});

       	}
       	else {
       		$(".show_email").css({"visibility": "hidden"});
       		
       	}
       
        $(".main_email").val("");
        $(".main_email").parent().find(".email_primary").val("");
        $(".fieldGroupCopy_email").find('.second_email').attr("value", "");
        $(".fieldGroupCopy_email").find('.email_primary').attr("value", "");

    });

    $("body").on("click",".remove_email",function(){ 
        var remove_email = $(this);
    	bootbox.confirm("Are you comfirm delete this Email?", function (result) {
            if (result) {

            	remove_email.parents(".fieldGroup_email").remove();

            	if (remove_email.parent().find(".email_primary").is(":checked")) 
				{
					if ($(".local_email_toggle").find(".second_email").length > 0) 
					{
						$(".local_email_toggle .fieldGroup_email").eq(0).find('.email_primary').prop( "checked", true );
					}
					else
					{
						$(".main_email_primary").prop( "checked", true );
					}
				}

            	if ($(".local_email_toggle").find(".second_email").length > 0) 
				{
					$(".show_email").css({"visibility": "visible"});

		       	}
		       	else {
		       		$(".show_email").css({"visibility": "hidden"});
		       		
		       	}
		       	
				$( '#form_email' ).html("");
            }
        });
    });

	$('.main_email').keyup(function(){

		if ($(this).val()) {
			$(".button_increment_email").css({"visibility": "visible"});

       	}
       	else {
       		$(".button_increment_email").css({"visibility": "hidden"});
       	}
	});

	if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
	{
		$(".show_local_fix_line").css({"visibility": "visible"});
		$(".local_fix_line_toggle").hide();

   	}
   	else {
   		$(".show_local_fix_line").css({"visibility": "hidden"});
   		$(".local_fix_line_toggle").hide();
   	}

   	if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
	{
		$(".show_local_mobile").css({"visibility": "visible"});
		$(".local_mobile_toggle").hide();

   	}
   	else {
   		$(".show_local_mobile").css({"visibility": "hidden"});
   		$(".local_mobile_toggle").hide();
   	}

   	if ($(".local_email_toggle").find(".second_email").length > 0) 
	{
		$(".show_email").css({"visibility": "visible"});
		$(".local_email_toggle").hide();

   	}
   	else {
   		$(".show_email").css({"visibility": "hidden"});
   		$(".local_email_toggle").hide();
   	}
});

$('#date_of_birth').datepicker({ 
    endDate: date
});

$("#identification_type").live('change',function(){
	$( '#form_identification_type' ).html("");

	if($("#identification_type").val() == "NRIC (Singapore citizen)")
	{
		var identification_no = $("#identification_no").val();
		var first_char = identification_no.charAt(0);
		var small_first_char = first_char.toLowerCase();
		if(small_first_char != "s" && small_first_char != "t")
		{
			toastr.error("Please enter correct identification no for SINGAPORE CITIZEN.", "Error");
		}
	}
});
$("#identification_no").live('change',function(){
	$( '#form_identification_no' ).html("");

	if($("#identification_type").val() == "NRIC (Singapore citizen)")
	{
		var identification_no = $(this).val();
		var first_char = identification_no.charAt(0);
		var small_first_char = first_char.toLowerCase();
		if(small_first_char != "s" && small_first_char != "t")
		{
			toastr.error("Please enter correct identification no for SINGAPORE CITIZEN.", "Error");
		}
	}
});

$("#id_expiry_date").live('change',function(){
	$( '#form_id_expiry_date' ).html("");
});
$("#name").live('change',function(){
	$( '#form_name' ).html("");
});
$("#date_of_birth").live('change',function(){
	$( '#form_date_of_birth' ).html("");
});
$("#postal_code1").live('change',function(){
	$( '#form_postal_code1' ).html("");
});
$("#street_name1").live('change',function(){
	$( '#form_street_name1' ).html("");
});
$("#postal_code2").live('change',function(){
	$( '#form_postal_code2' ).html("");
});
$("#street_name2").live('change',function(){
	$( '#form_street_name2' ).html("");
});
$("#foreign_address1").live('change',function(){
	$( '#form_foreign_address1' ).html("");
});
$("#foreign_address2").live('change',function(){
	$( '#form_foreign_address2' ).html("");
});
$(".nationality").live('change',function(){
	$( '#form_nationality' ).html("");
});
$("#local_fix_line").live('change',function(){
	$( '#form_local_fix_line' ).html("");
	$( '#form_local_mobile' ).html("");
});
$("#local_mobile").live('change',function(){
	$( '#form_local_fix_line' ).html("");
	$( '#form_local_mobile' ).html("");
});
$(".main_email").live('change',function(){
	$( '#form_email' ).html("");
});

var boolNationality = "false";
$(document).on('submit', '#upload', function (e) {
    e.preventDefault();
    var form = $('#upload');

    if(document.getElementById("nationalityId").disabled == true && boolNationality == "false")
    {
    	document.getElementById("nationalityId").disabled=false;
    	boolNationality = "true";
    }
    
    $('#loadingmessage').show();
    
	$(".local_fix_line_disabled .check_empty_local_fix_line").attr("disabled", "disabled");
	$(".local_fix_line_disabled .hidden_local_fix_line").attr("disabled", "disabled");
	$(".local_mobile_disabled .check_empty_local_mobile").attr("disabled", "disabled");
	$(".local_mobile_disabled .hidden_local_mobile").attr("disabled", "disabled");
	$(".email_disabled .check_empty_email").attr("disabled", "disabled");
    $.ajax({ //Upload common input
        url: "personprofile/update",
        type: "POST",
        data: form.serialize(),
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	$(".local_fix_line_disabled .check_empty_local_fix_line").removeAttr("disabled");
        	$(".local_fix_line_disabled .hidden_local_fix_line").removeAttr("disabled");
			$(".local_mobile_disabled .check_empty_local_mobile").removeAttr("disabled");
			$(".local_mobile_disabled .hidden_local_mobile").removeAttr("disabled");
			$(".email_disabled .check_empty_email").removeAttr("disabled");
        	//console.log(response);
            if (response.Status === 1) {
            	$('#upload #officer_id').val(response.officer_id);
            	$('#upload #old_identification_no').val(response.old_identification_no);
            	$('#kycScreeningIndividual-form #individual_officer_id').val(response.officer_id);
            	individual_reload_link = response.reload_link;
			    $('#multiple_file').fileinput('upload');
            }
            else if(response.Status === 2)
            {
            	toastr.error("This Identification No already in the system.", "Error");
            }
            else if(response.Status === 3)
            {
            	toastr.error("Local address and alternate address cannot be the same", "Error");
            }
            else
            {
            	//console.log("fail");
            	toastr.error("Please complete all required field", "Error");
            	//console.log(response.error["nationality"]);
            	if (response.error["identification_no"] != "")
            	{
            		var errorsIdentificationNo = '<span class="help-block">*' + response.error["identification_no"] + '</span>';
            		$( '#form_identification_no' ).html( errorsIdentificationNo );

            	}
            	else
            	{
            		var errorsIdentificationNo = '';
            		$( '#form_identification_no' ).html( errorsIdentificationNo );
            	}
            	if (response.error["id_expiry_date"] != "")
            	{
            		var errorsIdExpiryDate = '<span class="help-block">*' + response.error["id_expiry_date"] + '</span>';
            		$( '#form_id_expiry_date' ).html( errorsIdExpiryDate );

            	}
            	else
            	{
            		var errorsIdExpiryDate = '';
            		$( '#form_id_expiry_date' ).html( errorsIdExpiryDate );
            	}
            	if (response.error["name"] != "")
            	{
            		var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
            		$( '#form_name' ).html( errorsName );

            	}
            	else
            	{
            		var errorsName = '';
            		$( '#form_name' ).html( errorsName );
            	}
            	if (response.error["date_of_birth"] != "")
            	{
            		var errorsDateOfBirth = '<span class="help-block">*' + response.error["date_of_birth"] + '</span>';
            		$( '#form_date_of_birth' ).html( errorsDateOfBirth );

            	}
            	else
            	{
            		var errorsDateOfBirth = '';
            		$( '#form_date_of_birth' ).html( errorsDateOfBirth );
            	}
            	if (response.error["postal_code1"] != "")
            	{
            		var errorsPostalCode1 = '<span class="help-block">*' + response.error["postal_code1"] + '</span>';
            		$( '#form_postal_code1' ).html( errorsPostalCode1 );

            	}
            	else
            	{
            		var errorsPostalCode1 = '';
            		$( '#form_postal_code1' ).html( errorsPostalCode1 );
            	}
            	if (response.error["street_name1"] != "")
            	{
            		var errorsStreetName1 = '<span class="help-block">*' + response.error["street_name1"] + '</span>';
            		$( '#form_street_name1' ).html( errorsStreetName1 );

            	}
            	else
            	{
            		var errorsStreetName1 = '';
            		$( '#form_street_name1' ).html( errorsStreetName1 );
            	}
            	if (response.error["postal_code2"] != "")
            	{
            		var errorsPostalCode2 = '<span class="help-block">*' + response.error["postal_code2"] + '</span>';
            		$( '#form_postal_code2' ).html( errorsPostalCode2 );

            	}
            	else
            	{
            		var errorsPostalCode2 = '';
            		$( '#form_postal_code2' ).html( errorsPostalCode2 );
            	}
            	if (response.error["street_name2"] != "")
            	{
            		var errorsStreetName2 = '<span class="help-block">*' + response.error["street_name2"] + '</span>';
            		$( '#form_street_name2' ).html( errorsStreetName2 );

            	}
            	else
            	{
            		var errorsStreetName2 = '';
            		$( '#form_street_name2' ).html( errorsStreetName2 );
            	}
            	if (response.error["nationality"] != "")
            	{
            		var errorsNationality = '<span class="help-block">*' + response.error["nationality"] + '</span>';
            		$( '#form_nationality' ).html( errorsNationality );

            	}
            	else
            	{
            		var errorsNationality = '';
            		$( '#form_nationality' ).html( errorsNationality );
            	}
            	if (response.error["local_fix_line"] != "")
            	{
            		var errorsLocalFixLine = '<span class="help-block">*' + response.error["local_fix_line"] + '</span>';
            		$( '#form_local_fix_line' ).html( errorsLocalFixLine );

            	}
            	else
            	{
            		var errorsLocalFixLine = '';
            		$( '#form_local_fix_line' ).html( errorsLocalFixLine );
            	}
            	if (response.error["local_mobile"] != "")
            	{
            		var errorsLocalMobile = '<span class="help-block">*' + response.error["local_mobile"] + '</span>';
            		$( '#form_local_mobile' ).html( errorsLocalMobile );

            	}
            	else
            	{
            		var errorsLocalMobile = '';
            		$( '#form_local_mobile' ).html( errorsLocalMobile );
            	}
            	if (response.error["foreign_address1"] != "")
            	{
            		var errorsForeignAddress1 = '<span class="help-block">*' + response.error["foreign_address1"] + '</span>';
            		$( '#form_foreign_address1' ).html( errorsForeignAddress1 );

            	}
            	else
            	{
            		var errorsForeignAddress1 = '';
            		$( '#form_foreign_address1' ).html( errorsForeignAddress1 );
            	}
            	/*if (response.error["foreign_address2"] != "")
            	{
            		var errorsForeignAddress2 = '<span class="help-block">*' + response.error["foreign_address2"] + '</span>';
            		$( '#form_foreign_address2' ).html( errorsForeignAddress2 );

            	}
            	else
            	{
            		var errorsForeignAddress2 = '';
            		$( '#form_foreign_address2' ).html( errorsForeignAddress2 );
            	}*/
            	if (response.error["email"] != "")
            	{
            		var errorsEmail = '<span class="help-block">*' + response.error["email"] + '</span>';
            		$( '#form_email' ).html( errorsEmail );

            	}
            	else
            	{
            		var errorsEmail = '';
            		$( '#form_email' ).html( errorsEmail );
            	}
            }
        }
    });
	//document.getElementById("nationalityId").disabled=true;
	//console.log(boolNationality);
	if(document.getElementById("nationalityId").disabled == false && boolNationality == "true")
    {
    	document.getElementById("nationalityId").disabled=true;
    	boolNationality = "false";
    }
});

$('#postal_code1').keyup(function(){
	if($(this).val().length == 6)
	{
  		var zip = $(this).val();
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

			        $("#street_name1").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

			        if(placemark.AddressDetails.Country.AddressLine == "undefined")
			        {
			        	$("#building_name1").val("");
			        }
			        else
			        {
			        	$("#building_name1").val(placemark.AddressDetails.Country.AddressLine);
			        }
		      	}
				$( '#form_postal_code1' ).html('');
				$( '#form_street_name1' ).html('');
		    } else if (status.code == 603) {
		    	$( '#form_postal_code1' ).html('<span class="help-block">*No Record Found</span>');
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
		$("#street_name1").val("");
		$("#building_name1").val("");
	}
});

$('#unit_no3').keyup(function(){
	if($("#postal_code1").val() == $("#postal_code2").val() && $("#unit_no1").val() == $("#unit_no3").val() && $("#unit_no2").val() == $("#unit_no4").val())
	{
		toastr.error("Local address and alternate address cannot be the same", "Error");
		$("#postal_code2").val("");
		$("#street_name2").val("");
		$("#building_name2").val("");
		$("#unit_no3").val("");
		$("#unit_no4").val("");
	}
});

$('#unit_no4').keyup(function(){
	if($("#postal_code1").val() == $("#postal_code2").val() && $("#unit_no1").val() == $("#unit_no3").val() && $("#unit_no2").val() == $("#unit_no4").val())
	{
		toastr.error("Local address and alternate address cannot be the same", "Error");
		$("#postal_code2").val("");
		$("#street_name2").val("");
		$("#building_name2").val("");
		$("#unit_no3").val("");
		$("#unit_no4").val("");
	}
});

$('#postal_code2').keyup(function(){
	if($(this).val().length == 6)
	{
  		var zip = $(this).val();
		//var address = "068914";
		if($("#postal_code1").val() == zip && $("#unit_no1").val() == $("#unit_no3").val() && $("#unit_no2").val() == $("#unit_no4").val())
		{
			toastr.error("Local address and alternate address cannot be the same", "Error");
		}
		else
		{
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
			    //console.log(data);
			    //var field = $("textarea");
			    var myString = "";
			    
			    var status = data.Status;
			    
			    if (status.code == 200) {         
			      for (var i = 0; i < data.Placemark.length; i++) {
			        var placemark = data.Placemark[i];
			        var status = data.Status[i];

			        $("#street_name2").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

			        if(placemark.AddressDetails.Country.AddressLine == "undefined")
			        {
			        	$("#building_name2").val("");
			        }
			        else
			        {
			        	$("#building_name2").val(placemark.AddressDetails.Country.AddressLine);
			        }
			      }
			      $( '#form_postal_code2' ).html('');
			    } else if (status.code == 603) {
			    	$( '#form_postal_code2' ).html('<span class="help-block">*No Record Found</span>');
			    }

			  },
			  statusCode: {
			    404: function() {
			      alert('Page not found');
			    }
			  },
		    });
		}
	}
	else
	{
		$("#street_name2").val("");
		$("#building_name2").val("");
	}
});

if(person['foreign_address1'] != "" || person['foreign_address2'] != "" || person['foreign_address3'] != "")
{
	if (undefined !== person['foreign_address1'] && person['foreign_address1'].length || undefined !== person['foreign_address2'] && person['foreign_address2'].length) 
	{
		$('input[name="foreign_address1"]').removeAttr('disabled');
		$('input[name="foreign_address2"]').removeAttr('disabled');
		$('input[name="foreign_address3"]').removeAttr('disabled');
	}
	else
	{
		$('input[name="foreign_address1"]').attr('disabled', 'true');
		$('input[name="foreign_address2"]').attr('disabled', 'true');
		$('input[name="foreign_address3"]').attr('disabled', 'true');
	}
}
else if(person['foreign_address1'] == "" || person['foreign_address2'] == "" || person['foreign_address3'] == "")
{
	$('input[name="foreign_address1"]').attr('disabled', 'true');
	$('input[name="foreign_address2"]').attr('disabled', 'true');
	$('input[name="foreign_address3"]').attr('disabled', 'true');
}
else
{
	$('input[name="foreign_address1"]').attr('disabled', 'true');
	$('input[name="foreign_address2"]').attr('disabled', 'true');
	$('input[name="foreign_address3"]').attr('disabled', 'true');
}

if(person['postal_code1'] != "")
{

	$('input[name="postal_code1"]').removeAttr('disabled');

}
else
{
	$('input[name="postal_code1"]').attr('disabled', 'true');
}

if(person['street_name1'] != "")
{

	$('input[name="street_name1"]').removeAttr('disabled');

}
else
{
	$('input[name="street_name1"]').attr('disabled', 'true');
}

var alternate_address = document.getElementById('alternate_address');
if(alternate_address.checked)
{
	if(person['postal_code2'] != "")
	{

		$('input[name="postal_code2"]').removeAttr('disabled');

	}
	else
	{
		$('input[name="postal_code2"]').attr('disabled', 'true');
	}

	if(person['street_name2'] != "")
	{

		$('input[name="street_name2"]').removeAttr('disabled');

	}
	else
	{
		$('input[name="street_name2"]').attr('disabled', 'true');
	}
}
else
{
	$('input[name="postal_code2"]').attr('disabled', 'true');
	$('input[name="street_name2"]').attr('disabled', 'true');
}


if(files != null)
{
	for (var i = 0; i < files.length; i++) 
	{
		var url = base_url + "uploads/images_or_pdf/";
		var fileArray = files[i].split(',');
		initialPreviewArray.push( url + fileArray[1] );
		var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
	  	if(file_type == ".pdf" || file_type == ".PDF")
	  	{
		  initialPreviewConfigArray.push({
			  type: "pdf",
		      caption: fileArray[1],
		      url: "/secretary/personprofile/deleteFile/" + fileArray[0],
		      width: "120px",
		      key: i+1
		  });
		}
		else
		{
			initialPreviewConfigArray.push({
		      caption: fileArray[1],
		      url: "/secretary/personprofile/deleteFile/" + fileArray[0],
		      width: "120px",
		      key: i+1
		  });
		}
	}
}

$("#multiple_file").fileinput({
    theme: 'fa',
    uploadUrl: '/secretary/personprofile/uploadFile', // you must set a valid URL here else you will get an error
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
    initialPreviewDownloadUrl: base_url + 'uploads/images_or_pdf/{filename}',
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
		if(individual_reload_link != false)
		{
			window.location.href = individual_reload_link;
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
		if(individual_reload_link != false)
		{
			window.location.href = individual_reload_link;
		}
		toastr.success("Information Updated", "Success");
	}
	//toastr.error("Error", "Error");
});

$("#local_add").click(function() {
	$("#tr_foreign_add").hide();
	$("#tr_local_add").show();
});
$("#foreign_add").click(function() {
	$("#tr_foreign_add").show();
	$("#tr_local_add").hide();
});
$("#local_edit").click(function() {
	$("#tr_foreign_edit").hide();
	$("#tr_local_edit").show();

	var alternate_address = document.getElementById('alternate_address');

	var foreign_address1 = document.getElementById('foreign_address1');
	var foreign_address2 = document.getElementById('foreign_address2');
	var foreign_address3 = document.getElementById('foreign_address3');

	$('input[name="postal_code1"]').removeAttr('disabled');
	$('input[name="street_name1"]').removeAttr('disabled');

	 if(alternate_address.checked == false)
    {
    	$('input[name="postal_code2"]').attr('disabled', 'true');
    	$('input[name="street_name2"]').attr('disabled', 'true');
    }

	$('input[name="foreign_address1"]').attr('disabled', 'true');
	$('input[name="foreign_address2"]').attr('disabled', 'true');
	$('input[name="foreign_address3"]').attr('disabled', 'true');

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

	var alternate_address = document.getElementById('alternate_address');

    $('input[name="postal_code1"]').attr('disabled', 'true');
    $('input[name="street_name1"]').attr('disabled', 'true');

    if(alternate_address.checked == false)
    {
    	$('input[name="postal_code2"]').attr('disabled', 'true');
    	$('input[name="street_name2"]').attr('disabled', 'true');
    }
    
    $('input[name="foreign_address1"]').removeAttr('disabled');
	$('input[name="foreign_address2"]').removeAttr('disabled');
	$('input[name="foreign_address3"]').removeAttr('disabled');

	for (var i = 1; i < 2; i++) {
		window['postal_code'+i] = document.getElementById('postal_code'+i);
		window['street_name'+i] = document.getElementById('street_name'+i);
		window['building_name'+i] = document.getElementById('building_name'+i);

		switch (window['postal_code'+i].type) {
            case 'text':
                window['postal_code'+i].value = '';
                break;
        }
        switch (window['street_name'+i].type) {
            case 'text':
                window['street_name'+i].value = '';
                break;
        }
        switch (window['building_name'+i].type) {
            case 'text':
                window['building_name'+i].value = '';
                break;
        }
	}
	for (var i = 1; i < 3; i++) {
		window['unit_no'+i] = document.getElementById('unit_no'+i);
		switch (window['unit_no'+i].type) {
            case 'text':
                window['unit_no'+i].value = '';
                break;
        }
	}
});

$("#individual_edit").click(function() {
	$("#tr_individual_edit").show();
	$("#tr_company_edit").hide();

	var company_name = document.getElementById('company_name');
	var register_no = document.getElementById('register_no');
	var date_of_incorporation = document.getElementById('date_of_incorporation');
	var country_of_incorporation = document.getElementById('country_of_incorporation');
	var company_postal_code = document.getElementById('company_postal_code');
	var company_street_name = document.getElementById('company_street_name');
	var company_building_name = document.getElementById('company_building_name');
	var company_unit_no1 = document.getElementById('company_unit_no1');
	var company_unit_no2 = document.getElementById('company_unit_no2');
	var company_register_no = document.getElementById('company_register_no');

	company_register_no.value = register_no.value;

    company_name.value = '';
    register_no.value = '';
    date_of_incorporation.value = '';
    country_of_incorporation.value = '';
    company_postal_code.value = '';
    company_street_name.value = '';
    company_building_name.value = '';
    company_unit_no1.value = '';
    company_unit_no2.value = '';

    var company_local_edit = document.getElementById('company_local_edit');
    company_local_edit.checked = "checked"; 

    var company_foreign_edit = document.getElementById('company_foreign_edit');
    company_foreign_edit.checked = false; 

    $("#tr_company_foreign_edit").hide();
	$("#tr_company_local_edit").show();

	$('input[name="company_postal_code"]').removeAttr('disabled');
	$('input[name="company_street_name"]').removeAttr('disabled');
	$('input[name="foreign_address1"]').attr('disabled', 'true');
	$('input[name="foreign_address2"]').attr('disabled', 'true');
	$('input[name="foreign_address3"]').attr('disabled', 'true');

	$('input[name="company_foreign_address1"]').attr('disabled', 'true');
	$('input[name="company_foreign_address2"]').attr('disabled', 'true');
	$('input[name="company_foreign_address3"]').attr('disabled', 'true');

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
	//}

	for (var i = 1; i < 3; i++) {
		window['company_unit_no'+i] = document.getElementById('company_unit_no'+i);
		switch (window['company_unit_no'+i].type) {
            case 'text':
                window['company_unit_no'+i].value = '';
                break;
        }
	}

	var company_foreign_address1 = document.getElementById('company_foreign_address1');
	var company_foreign_address2 = document.getElementById('company_foreign_address2');
	var company_foreign_address3 = document.getElementById('company_foreign_address3');

    company_foreign_address1.value = '';
    company_foreign_address2.value = '';
    company_foreign_address3.value = '';
});
$("#alternate_label_edit").click(function() {
	$("#alternate_text_edit").toggle();
	var alternate_address = document.getElementById('alternate_address');
	if(alternate_address.checked)
	{
		$('input[name="postal_code2"]').removeAttr('disabled');
		$('input[name="street_name2"]').removeAttr('disabled');
	}
	else
	{
		$('input[name="postal_code2"]').attr('disabled', 'true');
		$('input[name="street_name2"]').attr('disabled', 'true');
	}
	$("#postal_code2").val("");
	$("#street_name2").val("");
	$("#building_name2").val("");
	$("#unit_no3").val("");
	$("#unit_no4").val("");
});
$("#alternate_label_add").click(function() {
	$("#alternate_text_add").toggle();
});