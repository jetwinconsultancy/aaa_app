if(salary_cap_list)
{

    salary_cap_list.forEach(function (key)
    {
        var content = jQuery('#clone_salary_cap_model tr'),
        element = null,    
        element = content.clone();
        
        element.find("[name='salary_cap_id[]']").attr("value",key.id);
        element.find("[name='cap_start_date[]']").attr("value",moment(key.cap_start_date).format("DD/MM/YYYY"));
        var currency = element.find("[name='cap_currency[]']");
        if(key.cap_end_date)
        {
            element.find("[name='cap_end_date[]']").val(moment(key.cap_end_date).format("DD/MM/YYYY"));
        }
        element.find("[name='cap_currency[]']").val(key.currency);
        element.find("[name='monthly_cap_value[]']").val(key.monthly_cap_value);
        element.find("[name='annual_cap_value[]']").val(key.annual_cap_value);

        element.appendTo('#body_salary_cap');

	    // currency.select2();

        $('.effective_date').datepicker({ 
            dateFormat:'dd/mm/yyyy',
            autoclose: true,
        });
    });

    if(salary_cap_list[0].cap_end_date !== null)
    {
        var content = jQuery('#clone_salary_cap_model tr'),
        element = null,    
        element = content.clone(); 

        element.find("[name='cap_start_date[]']").attr("value",moment(salary_cap_list[0].cap_end_date).add(1, 'days').format("DD/MM/YYYY"));

        $('#body_salary_cap').prepend(element);

    }
}
else{
    
    var content = jQuery('#clone_salary_cap_model tr'),
    element = null,    
    element = content.clone(); 

    $('#body_salary_cap').prepend(element);

    
}

$(document).on('click',".save_salary",function(){
    var tr = jQuery(this).parent().parent().parent().parent();
    var data = [
        tr.find("[name='salary_cap_id[]']").val(),
        tr.find("[name='cap_start_date[]']").val(),
        tr.find("[name='cap_end_date[]']").val(),
        tr.find("[name='cap_currency[]']").val(),
        tr.find("[name='monthly_cap_value[]']").val(),
        tr.find("[name='annual_cap_value[]']").val(),
    ];
    
    if(tr.find("[name='cap_start_date[]']").val() == "")
    {
        toastr.error('Cap start date cannot be empty', 'Error');
    }
    else if(tr.find("[name='cap_currency[]']").val() == "")
    {
        toastr.error('Please Select The Currency', 'Error');
    }
    else if(tr.find("[name='monthly_cap_value[]']").val() == "")
    {
        toastr.error('Please Enter Monthly Cap Value', 'Error');
    }
    else if(tr.find("[name='annual_cap_value[]']").val() == "")
    {
        toastr.error('Please Enter Anually Cap Value', 'Error');
    }
    else
    {
        $('#loadingmessage').show();
        tr.find(".save_salary").disabled = "true";
        $.ajax({
            type: "POST",
            url: add_salary_cap_link,
            data: {"data":data}, // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
                if(response){
                    toastr.success('Information Updated', 'Updated');
                    location.reload();
                }
                else
                {
                    toastr.error('Something went wrong', 'Error');
                }
            }
        });

    }

});


if(age_group_period_list)
{

    age_group_period_list.forEach(function (key)
    {
        var content = jQuery('#clone_age_group_period_model tr'),
        element = null,    
        element = content.clone();
        
        element.find("[name='age_group_period_id[]']").attr("value",key.id);
        element.find("[name='period_start_date[]']").attr("value",moment(key.period_start_date).format("DD/MM/YYYY"));
        if(key.period_end_date)
        {
            element.find("[name='period_end_date[]']").val(moment(key.period_end_date).format("DD/MM/YYYY"));
        }
   
        element.appendTo('#body_age_group_period');

	    // currency.select2();

        $('.effective_date').datepicker({ 
            dateFormat:'dd/mm/yyyy',
            autoclose: true,
        });
    });

    // console.log(age_group_period_list);
    if(age_group_period_list[0].period_end_date != null && age_group_period_list[0].period_end_date != "")
    {
        var content = jQuery('#clone_age_group_period_model tr'),
        element = null,    
        element = content.clone(); 

        element.find("[name='period_start_date[]']").attr("value",moment(age_group_period_list[0].period_end_date).add(1, 'days').format("DD/MM/YYYY"));

        $('#body_age_group_period').prepend(element);

    }
}
else{
    
    var content = jQuery('#clone_age_group_period_model tr'),
    element = null,    
    element = content.clone(); 

    $('#body_age_group_period').prepend(element);

    
}

$(document).on('click',".save_age_group_period",function(){   
    var tr = jQuery(this).parent().parent().parent().parent();
    console.log(tr);
    var data = [
        tr.find("[name='age_group_period_id[]']").val(),
        tr.find("[name='period_start_date[]']").val(),
        tr.find("[name='period_end_date[]']").val(),
    ];
    

    if(tr.find("[name='cap_start_date[]']").val() == "")
    {
        toastr.error('Cap start date cannot be empty', 'Error');
    }
    else
    {
        $('#loadingmessage').show();
        tr.find(".save_age_group_period").disabled = "true";

        // console.log(data);

        $.ajax({
            type: "POST",
            url: add_age_group_period_link,
            data: {"data":data}, // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
                if(response){
                    toastr.success('Information Updated', 'Updated');
                    location.reload();
                }
                else
                {
                    toastr.error('Something went wrong', 'Error');
                }
            }
        });

    }
});

$(document).on('click',".edit_age_group_period",function(){   
    var tr = jQuery(this).parent().parent().parent().parent();
    var period_id =  tr.find("[name='age_group_period_id[]']").val();
    console.log(period_id);
    if(period_id == "")
    {
        var data = [
            tr.find("[name='age_group_period_id[]']").val(),
            tr.find("[name='period_start_date[]']").val(),
            tr.find("[name='period_end_date[]']").val(),
        ];
        
    
        if(tr.find("[name='period_start_date[]']").val() == "")
        {
            toastr.error('Period start date cannot be empty', 'Error');
        }
        else
        {
            $('#loadingmessage').show();
            tr.find(".save_age_group_period").disabled = "true";
    
            // console.log(data);
    
            $.ajax({
                type: "POST",
                url: add_age_group_period_link,
                data: {"data":data}, // <--- THIS IS THE CHANGE
                dataType: "json",
                'async':false,
                success: function(response)
                {
                    period_id = response;
                    tr.find("[name='age_group_period_id[]']").val(response);  
                }
            });
    
        }
    }
    $('#age_group_modal').modal('show');
    $('#age_group_modal').find("[name='age_group_period_id']").val(period_id);
    $('#body_add_age_group').empty();
    $.ajax({
        type: "POST",
        url: get_age_group_link,
        data: {"id":period_id}, // <--- THIS IS THE CHANGE
        dataType: "json",
        'async':false,
        success: function(response)
        {
            // console.log(response);
       

            if(response)
            {

                response.forEach(function (key)
                {
                    var content = jQuery('#clone_age_group_model tr'),
                    element = null,    
                    element = content.clone();
                    
                    element.find("[name='age_group_id[]']").attr("value",key.id);
                    element.find("[name='age_years[]']").val(key.age_years);
                    element.find("[name='age_months[]']").val(key.age_months);
                    element.find("[name='employer_percent[]']").val(key.employer_percent);
                    element.find("[name='employee_percent[]']").val(key.employee_percent);

            
                    element.appendTo('#body_add_age_group');

                    // currency.select2();

                    // $('.effective_date').datepicker({ 
                    //     dateFormat:'dd/mm/yyyy',
                    //     autoclose: true,
                    // });
                });
            }
            
        }
    });
    
    
});

$(document).on('click',"#age_group_add",function(){ 
    var content = jQuery('#clone_age_group_model tr'),
    element = null,    
    element = content.clone();
    element.appendTo('#body_add_age_group');

});

$(document).on('click',"#save_age_group",function(){   
    let allAreFilled = true;
    document.getElementById("age_group_form").querySelectorAll("[required]").forEach(function(i) {
        if (!allAreFilled) return;
        if (!i.value) allAreFilled = false;
        if (i.type === "radio") {
        let radioValueCheck = false;
        document.getElementById("age_group_form").querySelectorAll(`[name=${i.name}]`).forEach(function(r) {
            if (r.checked) radioValueCheck = true;
        })
        allAreFilled = radioValueCheck;
        }
    })
    if (!allAreFilled) {
        alert('Fill all the fields');
    }
    else
    {
        $.ajax({
            type: "POST",
            url: save_age_group_link,
            data: $("#age_group_form").serialize(), // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
                console.log(response);                
                if(response){
                    $('#age_group_modal').modal('hide');

                    toastr.success('Information Updated', 'Updated');
                    // location.reload();
                }
                else
                {
                    toastr.error('Something went wrong', 'Error');
                }
            }
        });
    }

});


function delete_age_group(element)
{
    var tr = jQuery(element).parent().parent();

    // console.log(tr);

    var age_group_id = tr.find('input[name="age_group_id[]"]').val();

    bootbox.confirm({
        message: "Do you wanna delete this selected info?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn_purple'
            },
            cancel: {
                label: 'No',
                className: 'btn_cancel'
            }
        },
        callback: function (result) {
            if (result) 
            {
                $('#loadingmessage').show();
                if(age_group_id != undefined)
                {
                    $.ajax({ //Upload common input
                        url: delete_age_group_link,
                        type: "POST",
                        data: {"age_group_id": age_group_id},
                        dataType: 'json',
                        success: function (response) {
                            $('#loadingmessage').hide();
                            if(response.Status == 1)
                            {
                                tr.remove();
                                toastr.success("Updated Information.", "Updated");

                            }
                        }
                    });
                }
            }
        }
    })
}

if(nationality_period_list)
{

    nationality_period_list.forEach(function (key)
    {
        var content = jQuery('#clone_nationality_period_model tr'),
        element = null,    
        element = content.clone();
        
        element.find("[name='nationality_period_id[]']").attr("value",key.id);
        element.find("[name='period_start_date[]']").attr("value",moment(key.period_start_date).format("DD/MM/YYYY"));
        // element.find("[name='period_end_date[]']").val(moment(key.period_end_date).format("DD/MM/YYYY"));
        if(key.period_end_date)
        {
            element.find("[name='period_end_date[]']").val(moment(key.period_end_date).format("DD/MM/YYYY"));
        }
   
   
        element.appendTo('#body_nationality_period');

	    // currency.select2();

        // $('.effective_date').datepicker({ 
        //     dateFormat:'dd/mm/yyyy',
        //     autoclose: true,
        // });
    });

    if(nationality_period_list[0].period_end_date != null && nationality_period_list[0].period_end_date != "")
    {
        var content = jQuery('#clone_nationality_period_model tr'),
        element = null,    
        element = content.clone(); 

        element.find("[name='period_start_date[]']").attr("value",moment(nationality_period_list[0].period_end_date).add(1, 'days').format("DD/MM/YYYY"));

        $('#body_nationality_period').prepend(element);

    }
}
else{
    
    var content = jQuery('#clone_nationality_period_model tr'),
    element = null,    
    element = content.clone(); 

    $('#body_nationality_period').prepend(element);

    
}

$(document).on('click',".save_nationality_period",function(){   
    var tr = jQuery(this).parent().parent().parent().parent();
    // console.log(tr);
    var data = [
        tr.find("[name='nationality_period_id[]']").val(),
        tr.find("[name='period_start_date[]']").val(),
        tr.find("[name='period_end_date[]']").val(),
    ];
    

    if(tr.find("[name='period_start_date[]']").val() == "")
    {
        toastr.error('Period start date cannot be empty', 'Error');
    }
    else
    {
        $('#loadingmessage').show();
        tr.find(".save_nationality_period").disabled = "true";

        // console.log(data);

        $.ajax({
            type: "POST",
            url: add_nationality_period_link,
            data: {"data":data}, // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
                if(response){
                    toastr.success('Information Updated', 'Updated');
                    location.reload();
                }
                else
                {
                    toastr.error('Something went wrong', 'Error');
                }
            }
        });

    }
});

$(document).on('click',".edit_nationality_period",function(){   
    var tr = jQuery(this).parent().parent().parent().parent();
    var period_id =  tr.find("[name='nationality_period_id[]']").val();
    console.log(period_id);
    if(period_id == "")
    {
        var data = [
            tr.find("[name='nationality_period_id[]']").val(),
            tr.find("[name='period_start_date[]']").val(),
            tr.find("[name='period_end_date[]']").val(),
        ];
        
    
        if(tr.find("[name='period_start_date[]']").val() == "")
        {
            toastr.error('Period start date cannot be empty', 'Error');
        }
        else
        {
            $('#loadingmessage').show();
            tr.find(".save_nationality_period").disabled = "true";
    
            // console.log(data);
    
            $.ajax({
                type: "POST",
                url: add_nationality_period_link,
                data: {"data":data}, // <--- THIS IS THE CHANGE
                dataType: "json",
                'async':false,
                success: function(response)
                {
                    period_id = response;
                    tr.find("[name='nationality_period_id[]']").val(response);  
                }
            });
    
        }
    }
    $('#nationality_modal').modal('show');
    $('#nationality_modal').find("[name='nationality_period_id']").val(period_id);
    $('#body_add_nationality').empty();
    $.ajax({
        type: "POST",
        url: get_nationality_link,
        data: {"id":period_id}, // <--- THIS IS THE CHANGE
        dataType: "json",
        'async':false,
        success: function(response)
        {
            if(response !== "false")
            {
                if(response)
                {
                    response.forEach(function (key)
                    {
                        var content = jQuery('#clone_nationality_model tr'),
                        element = null,    
                        element = content.clone();
                        
                        element.find("[name='nationality_id[]']").attr("value",key.id);
                        element.find("[name='nationality_type[]']").val(key.nationality_type);
                        element.find("[name='employer_percent[]']").val(key.employer_percent);
                        element.find("[name='employee_percent[]']").val(key.employee_percent);

                        if(key.nationality_type == 3)
                        {
                            element.find("[name='employer_percent[]']").val("");
                            element.find("[name='employee_percent[]']").val("");
                            element.find("[name='employer_percent[]']").attr("disabled", true);
                            element.find("[name='employee_percent[]']").attr("disabled", true);
                            element.find("[name='employer_percent[]']").removeAttr('required');
                            element.find("[name='employee_percent[]']").removeAttr('required');
                            element.find(".nationality_message").show();

                        }
                
                        element.appendTo('#body_add_nationality');

                        // currency.select2();

                        // $('.effective_date').datepicker({ 
                        //     dateFormat:'dd/mm/yyyy',
                        //     autoclose: true,
                        // });
                    });
                }
                else
                {
                    for (let i = 3; i > 0; i--) {
                        var content = jQuery('#clone_nationality_model tr'),
                        element = null,    
                        element = content.clone();
                        
                        element.find("[name='nationality_type[]']").val(i);
                        element.find("[name='employer_percent[]']").val(0);
                        element.find("[name='employee_percent[]']").val(0);

                        if(i == 3)
                        {
                            element.find("[name='employer_percent[]']").val("");
                            element.find("[name='employee_percent[]']").val("");
                            element.find("[name='employer_percent[]']").attr("disabled", true);
                            element.find("[name='employee_percent[]']").attr("disabled", true);
                            element.find("[name='employer_percent[]']").removeAttr('required');
                            element.find("[name='employee_percent[]']").removeAttr('required');
                            element.find(".nationality_message").show();

                        }
                
                        element.appendTo('#body_add_nationality');
                    }
                }
            }
            
        }
    });
    
    
});

$(document).on('click',"#save_nationality",function(){   
    let allAreFilled = true;
    document.getElementById("nationality_form").querySelectorAll("[required]").forEach(function(i) {
        if (!allAreFilled) return;
        if (!i.value) allAreFilled = false;
        if (i.type === "radio") {
            let radioValueCheck = false;
            document.getElementById("nationality_form").querySelectorAll(`[name=${i.name}]`).forEach(function(r) {
                if (r.checked) radioValueCheck = true;
            })
            allAreFilled = radioValueCheck;
        }
    })
    if (!allAreFilled) {
        alert('Fill all the fields');
    }
    else
    {
        $(".nationality_type_field").attr("disabled", false);
        $(".disable").attr("disabled", false);
        $.ajax({
            type: "POST",
            url: save_nationality_link,
            data: $("#nationality_form").serialize(), // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
                console.log(response);                
                if(response){
                    $('#nationality_modal').modal('hide');

                    toastr.success('Information Updated', 'Updated');
                    // location.reload();
                }
                else
                {
                    toastr.error('Something went wrong', 'Error');
                }
            }
        });
    }

});
