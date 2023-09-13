
$('#postal_code').keyup(function(){
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
		    //console.log(data);
		    //var field = $("textarea");
		    var myString = "";
		    
		    var status = data.Status;
		    /*myString += "Status.code: " + status.code + "\n";
		    myString += "Status.request: " + status.request + "\n";
		    myString += "Status.name: " + status.name + "\n";*/
		    
		    if (status.code == 200) {         
		      for (var i = 0; i < data.Placemark.length; i++) {
		        var placemark = data.Placemark[i];
		        var status = data.Status[i];
		        //console.log(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);
		        $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

		        if(placemark.AddressDetails.Country.AddressLine == "undefined")
		        {
		        	$("#building_name").val("");
		        }
		        else
		        {
		        	$("#building_name").val(placemark.AddressDetails.Country.AddressLine);
		        }

		      }
		      $( '#form_postal_code' ).html('');
		      $( '#form_street_name' ).html('');
		      //field.val(myString);
		    } else if (status.code == 603) {
		    	$( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
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