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

function CompanyTypeInfo() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getCompanyType = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getCompanytype';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.company_type').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.company_type').find("option:eq(0)").html("Select Company Type");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_company_type != null && key == data.selected_company_type)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.company_type').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getAcquriedBy = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getAcquriedBy';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.acquried_by').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.acquried_by').find("option:eq(0)").html("Client Status:");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_acquried_by != null && key == data.selected_acquried_by)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.acquried_by').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.status = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getStatus';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.status').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.status').find("option:eq(0)").html("Select Status");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_status != null && key == data.selected_status)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.status').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

}

$(function() {
    var loc = new CompanyTypeInfo();
    loc.getCompanyType();

    loc.getAcquriedBy();

    loc.status();

    /*$("#identification_type").change(function(ev) {
        var identification_type = $(this).val();
        console.log(identification_type);
        if(identification_type == 'NRIC (Singapore citizen)'){
            
            document.getElementById("nationalityId").value = "166";
            document.getElementById("nationalityId").disabled=true;
            
        }
        else
        {
           document.getElementById("nationalityId").value = "0";
           document.getElementById("nationalityId").disabled=false;
        }
       
    });*/
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
