/* Compensation tab */
if(salary)
{
    salary.forEach(function (key)
    {
        var content = jQuery('#clone_salary_model tr'),
        element = null,    
        element = content.clone();
        
        element.find("[name='effective_start_date[]']").attr("value",moment(key.effective_start_date).format("DD MMMM YYYY"));
        var currency = element.find("[name='salary_currency[]']");
        element.find("[name='salary_info_id[]']").val(key.salary_info_id);
        element.find("[name='salary_currency[]']").val(key.id);
        element.find("[name='staff_salary[]']").val(key.salary);

        element.find("[name='effective_start_date[]']").attr("disabled", true);
        currency.attr("disabled", true);
        element.find("[name='staff_salary[]']").attr("disabled", true);
        // element.find(".submit_salary_info_button").hide();

        // info_id.val(key.id);
        // console.log(info_id[0]);
        // info_id.val(key.id);

        element.appendTo('#body_add_salary');

	    currency.select2();

        $('.effective_date').datepicker({ 
            dateFormat:'dd/mm/yyyy',
            autoclose: true,
        });
    });
}

if(bond)
{
    bond.forEach(function (key)
    {
        var content = jQuery('#clone_bond_model tr'),
        element = null,    
        element = content.clone();

        var today = new Date();
    
        if(key.bond_start_date != '')
        {
           
            var content = jQuery('#clone_bond_model tr'),
            element = null,    
            element = content.clone();
            
            element.find("[name='bond_start_date[]']").attr("value",moment(key.bond_start_date).format("DD MMMM YYYY"));
            var currency = element.find("[name='bond_currency[]']");
            element.find("[name='bond_info_id[]']").val(key.bond_info_id);
            element.find("[name='bond_currency[]']").val(key.currency_id);
            element.find("[name='bond_period[]']").val(key.bond_period);
            element.find("[name='bond_allowance[]']").val(key.bond_allowance);
            var startDate = new Date(key.bond_start_date);
            var today 	  = new Date();
		    var endDate = moment(startDate).add(24, 'months').subtract(1, 'days');
            element.find("[name='bond_end_date[]']").attr("value",endDate.format("DD MMMM YYYY"));

            var completed_month = monthDiff(startDate, today, key.bond_period);
            element.find("[name='bond_completed[]']").val(completed_month);
            element.find("[name='total_bond_allowance[]']").val(completed_month*key.bond_allowance);

            element.appendTo('#body_add_bond');

            // element.prepend('#body_add_salary');

            currency.select2();

            // $("#bond_completed").val(month);
            // element.find("[name='stocktake_date[]']").attr("value", key.stocktake_date);
            // $("#total_bond_allowance").val(month*($('#bond_allowance').val()));
        }
        
        // element.find("[name='stocktake_date[]']").attr("value", key.stocktake_date);
        // var currency = element.find("[name='salary_currency[]']").attr("value", key.currency);
        // element.find("[name='stocktake_address[]']").val(key.stocktake_address);

        // info_id.val(key.id);
        // console.log(info_id[0]);
        // info_id.val(key.id);

        element.appendTo('#body_add_bond');

	    // currency.select2();
        // $('.stocktake_date').datepicker({ 
        //     dateFormat:'dd/mm/yyyy',
        //     autoclose: true,
        // });



    });
}

$(document).on('click',"#salary_add",function(){
    $.ajax({
        type: "POST",
        url: get_statement_content_link,
        data: {"id":employee_id}, // <--- THIS IS THE CHANGE
        'async':false,
        success: function(data)
        {
            if(data)
            {
                $('#principal_statement .modal-body').empty();
                data = $(data);
                var currency = data.find("[name='salary_form_currency']");

                $('#principal_statement .modal-body').prepend(data);
                currency.select2();

                $('#principal_statement').modal('show');

                // console.log(currency);
                
                // $("#EC_today_date").val($('#form'+element).find(".eventDate").val());
            }
        }               
    });
});



function delete_salary_info(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var salary_info_id = tr.find('input[name="salary_info_id[]"]').val();

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
                if(salary_info_id != undefined)
                {
                    $.ajax({ //Upload common input
                        url: delete_salary_link,
                        type: "POST",
                        data: {"salary_info_id": salary_info_id},
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



$(document).on('click',"#bond_add",function(){
    $.ajax({
        type: "POST",
        url: get_bond_statement_content_link,
        data: {"id":employee_id}, // <--- THIS IS THE CHANGE
        'async':false,
        success: function(data)
        {
            if(data)
            {
                $('#bond_statement .modal-body').empty();
                data = $(data);
                var currency = data.find("[name='bond_form_currency']");

                $('#bond_statement .modal-body').prepend(data);
                $('#bond_statement').modal('show');

                currency.select2();
                // $("#EC_today_date").val($('#form'+element).find(".eventDate").val());
            }
        }               
    });
});

function delete_bond_info(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var bond_info_id = tr.find('input[name="bond_info_id[]"]').val();

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
                if(bond_info_id != undefined)
                {
                    $.ajax({ //Upload common input
                        url: delete_bond_link,
                        type: "POST",
                        data: {"bond_info_id": bond_info_id},
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

