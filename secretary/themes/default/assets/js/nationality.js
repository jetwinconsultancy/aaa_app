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

function locationInfo() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getNationality = function(isSingCitizen = false) {
        var url = base_url+"/"+folder+"/"+'nationality/getNationality';
        var method = "get";
        var data = {};
        $('.nationality').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.nationality').find("option:eq(0)").html("Select Nationality");
            $('.nationality option').remove();
            $('.nationality').append($('<option>', {value:0, text:'Select Nationality'}));

            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    if(isSingCitizen)
                    {
                        if (key == 165)
                        {   
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
                                document.getElementById("nationalityId").disabled = true;
                            }
                        }
                    }
                    
                    $('.nationality').append(option);
                });
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

    this.getCompanyNationality = function() {
        var url = base_url+"/"+folder+"/"+'nationality/getCompanyNationality';
        var method = "get";
        var data = {};
        $('.country_of_incorporation').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.country_of_incorporation').find("option:eq(0)").html("Select Country of Incorporation");
            $('.country_of_incorporation option').remove();
            $('.country_of_incorporation').append($('<option>', {value:0, text:'Select Country of Incorporation'}));

            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.select_company_nationality != null && key == data.select_company_nationality)
                    {
                        option.attr('selected', 'selected');
                    }
                    
                    $('.country_of_incorporation').append(option);
                });
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
    loc.getCompanyNationality();

    $("#identification_type").change(function(ev) {
        var identification_type = $(this).val();
        if(identification_type == 'NRIC (Singapore citizen)'){
            var isSingCitizen = true;
            loc.getNationality(isSingCitizen);
        }
        else
        {
           document.getElementById("nationalityId").disabled=false;
           $(".nationality option[value='165']").remove();

        }
    });
});
