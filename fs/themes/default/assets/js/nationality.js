var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

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

function locationInfo() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getNationality = function(isSingCitizen = false) {
        var url = base_url+"/"+folder+"/"+'nationality/getNationality';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.nationality').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.nationality').find("option:eq(0)").html("Select Nationality");
            $('.nationality option').remove();
            $('.nationality').append($('<option>', {value:0, text:'Select Nationality'}));
            //console.log(data);
            if(data.tp == 1){
                //console.log($("#identification_type").val());
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    if(isSingCitizen)
                    {
                        if (key == 165)
                        {   
                            //console.log(key);
                            option.attr('selected', 'selected');
                            document.getElementById("nationalityId").disabled = true;
                        }
                    }
                    else if(!isSingCitizen)
                    {
                        if(data.selected_nationality != null && key == data.selected_nationality)
                        {
                            option.attr('selected', 'selected');
                            if (data.selected_nationality == 165)
                            {
                                //console.log("selected_nationality=166");
                                document.getElementById("nationalityId").disabled = true;
                            }
                        }
                    }
                    
                    $('.nationality').append(option);
                });
                //$(".nationality").prop("disabled",false);
                if($("#identification_type").val() != "NRIC (Singapore citizen)")
                {
                    $(".nationality option[value='165']").remove();
                }
            }
            else{
                alert(data.msg);
            }
        }); 
    };

}

$(function() {
    var loc = new locationInfo();
    loc.getNationality();

    $("#identification_type").change(function(ev) {
        var identification_type = $(this).val();
        //console.log(identification_type);
        if(identification_type == 'NRIC (Singapore citizen)'){
            //Get select object
            /*var objSelect = document.getElementById("nationalityId");

            //Set selected
            setSelectedValue(objSelect, "166");

            function setSelectedIndex(s, v) {
                for ( var i = 0; i < s.options.length; i++ ) {
                    if ( s.options[i].value == v ) {
                        s.options[i].selected = true;
                        return;
                    }
                }
            }*/
            var isSingCitizen = true;
            loc.getNationality(isSingCitizen);
            /*document.getElementById("nationalityId").value = "166";
            document.getElementById("nationalityId").disabled=true;*/
            
        }
        else
        {
           //document.getElementById("nationalityId").value = "0";
           document.getElementById("nationalityId").disabled=false;
           $(".nationality option[value='165']").remove();

        }
        /*else{
            $(".states option:gt(0)").remove();
        }*/
    });
    /*console.log(window.location.pathname);
    if(window.location.pathname == '/iasia/account_details' || window.location.pathname == '/iasia/account_details/edit_account' || window.location.pathname == '/iasia/payment')
    {
    }*/
    /*var $nationality = $(".nationality");
    console.log($nationality.val());
    
    $nationality.data("value", $nationality.val());

    setTimeout(function() {
    var data = $nationality.data("value"),
        nationalityId = $nationality.val();

        if (data !== nationalityId) 
        {
            $nationality.data("value", nationalityId);
        }
        
    }, 200);*/

});
