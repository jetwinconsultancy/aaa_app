//-------------------start jurisdiction list----------------------------
$count_jurisdiction_info = 0;
$(document).on('click',"#jurisdiction_Add",function() {

	$count_jurisdiction_info++;
 	$a=""; 
	$a += '<form class="tr jurisdiction_editing sort_id" method="post" name="form'+$count_jurisdiction_info+'" id="form'+$count_jurisdiction_info+'">';
	//$a += '<div class="hidden"><input type="text" class="form-control" name="firm_id" id="firm_id" value="'+firm_id+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="jurisdiction_info_id" id="jurisdiction_info_id" value=""/></div>';
	$a += '<div class="td"><input type="text" name="code" id="code" class="form-control" value=""/><div id="form_code"></div></div>';
	$a += '<div class="td"><input type="text" name="jurisdiction" class="form-control" value="" id="jurisdiction"/><div id="form_jurisdiction"></div></div>';
	$a += '<div class="td jurisdiction_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_jurisdiction_info_button" onclick="edit_jurisdiction(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_jurisdiction_info(this);">Delete</button></div></div>';
	$a += '</form>';
	
	$("#body_jurisdiction_info").prepend($a); 
});

function edit_jurisdiction(element)
{
	var tr = jQuery(element).parent().parent().parent();
	if(!tr.hasClass("jurisdiction_editing")) 
	{
		tr.addClass("jurisdiction_editing");
		tr.find("DIV.td").each(function()
		{
			if(!jQuery(this).hasClass("jurisdiction_action"))
			{
				jQuery(this).find('input[name="code"]').attr('readonly', true);
				jQuery(this).find('input[name="jurisdiction"]').attr('disabled', false);
			} 
			else 
			{
				jQuery(this).find(".submit_jurisdiction_info_button").text("Save");
			}
		});
	} 
	else 
	{
		var frm = $(element).closest('form');

		var frm_serialized = frm.serialize();

		jurisdiction_info_submit(frm_serialized, tr);
	}
}

function jurisdiction_info_submit(frm_serialized, tr)
{
	$('#loadingmessage').show();
	$.ajax({ //Upload common input
        url: "admin_setting/add_jurisdiction_info",
        type: "POST",
        data: frm_serialized,
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	if (response.Status === 1) {
            	//var errorsDateOfCessation = ' ';
            	toastr.success(response.message, response.title);
            	if(response.insert_jurisdiction_info_id != null)
            	{
            		tr.find('input[name="jurisdiction_info_id"]').attr('value', response.insert_jurisdiction_info_id);
            	}
            	tr.removeClass("jurisdiction_editing");

				tr.find("DIV.td").each(function(){
					if(!jQuery(this).hasClass("jurisdiction_action"))
					{
						jQuery(this).find('input[name="code"]').attr('readonly', true);
						jQuery(this).find('input[name="jurisdiction"]').attr('disabled', true);
					} 
					else 
					{
						jQuery(this).find(".submit_jurisdiction_info_button").text("Edit");
					}
				});
			    
            }
            else if (response.Status === 2)
            {
            	toastr.error(response.message, response.title);
            }
        }
    });
}

if(jurisdiction_info)
{
	$count_jurisdiction_info = jurisdiction_info.length;

	for(var i = 0; i < jurisdiction_info.length; i++)
	{
		$a=""; 
		$a += '<form class="tr sort_id" method="post" name="form'+i+'" id="form'+i+'">';
		//$a += '<div class="hidden"><input type="text" class="form-control" name="firm_id" id="firm_id" value="'+firm_id+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="jurisdiction_info_id" id="jurisdiction_info_id" value="'+jurisdiction_info[i]["id"]+'"/></div>';
		$a += '<div class="td"><input type="text" name="code" id="code" class="form-control" value="'+jurisdiction_info[i]["code"]+'" readonly="true"/><div id="form_code"></div></div>';
		$a += '<div class="td"><input type="text" name="jurisdiction" class="form-control" value="'+jurisdiction_info[i]["jurisdiction"]+'" id="jurisdiction" disabled="disabled"/><div id="form_jurisdiction"></div></div>';
		$a += '<div class="td jurisdiction_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_jurisdiction_info_button" onclick="edit_jurisdiction(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_jurisdiction_info(this);">Delete</button></div></div>';
		$a += '</form>';
		
		$("#body_jurisdiction_info").prepend($a); 
	}
}

function delete_jurisdiction_info(element)
{
    bootbox.confirm({
        message: "Do you want to delete this selected info?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result == true)
            {
            	var tr = jQuery(element).parent().parent().parent();
            	var jurisdiction_info_id = tr.find('input[name="jurisdiction_info_id"]').val();
            	$('#loadingmessage').show();
            	if(jurisdiction_info_id != undefined)
            	{
            		$.ajax({ //Upload common input
                        url: "admin_setting/delete_jurisdiction_info",
                        type: "POST",
                        data: {"jurisdiction_info_id": jurisdiction_info_id},
                        dataType: 'json',
                        success: function (response) {
                        	//console.log(response.Status);
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

//-------------------end jurisdiction list----------------------------

// $('#form_category').formValidation({
//     framework: 'bootstrap',
//     fields: {
//         category: {
//             row: '.category_div',
//             validators: {
//                 notEmpty: {
//                     message: 'The Category field is required.'
//                 }
//             }
//         },
//     }
// });

var category = {
        row: '.category_div',
        validators: {
            notEmpty: {
                message: 'The Category field is required.'
            }
        }
    },
    jurisdiction = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.juris-input-group',
        validators: {
            callback: {
                message: 'The Jurisdiction field is required.',
                callback: function(value, validator, $field) {
                    //var num = jQuery($field).parent().parent().parent().attr("num");
                    var options = validator.getFieldElements('jurisdiction[]').val();
                    return (options != null && options != "0");
                }
            }
        }
    },
    start_date = {
        row: '.date-input-group',
        validators: {
            notEmpty: {
                message: 'The Start Date field is required.'
            }
        }
    },
	rate = {
        row: '.input-group',
        validators: {
            notEmpty: {
                message: 'The Rate field is required.'
            }
        }
    };

// var payment_voucher_type_validation = {
//         row: '.payment_voucher_type_div',
//         validators: {
//             notEmpty: {
//                 message: 'The Payment Voucher Type field is required.'
//             }
//         }
//     };

//Open Category
$(".create_category").click(function()
{
	$("#add_category_form #form_category").remove();
	var jurisdiction_elem = document.querySelector('.hidden_form_category');
	// Get HTML content
	var html = jurisdiction_elem.innerHTML;

	$("#add_category_form").append(html);
	$('#add_category_form .origin_form_category').attr("id", "form_category");
	//$("#add_category_form #form_category").show();

	// $(".tr_additional_juris").remove();
	// $("#category").val("");
	// $(".juris-input-group #jurisdiction0 option").remove();
	// $("#start_date").val("");
	// $("#end_date").val("");
	// $("#rate").val("");
	$('#form_category').formValidation('addField', 'category', category);
	$('#form_category').formValidation('addField', 'jurisdiction[]', jurisdiction);
    $('#form_category').formValidation('addField', 'start_date[]', start_date);
    $('#form_category').formValidation('addField', 'rate[]', rate);

    $('#loadingmessage').show();
    $.ajax({ //Upload common input
        url: "admin_setting/get_dropdown_jurisdiction_info",
        type: "GET",
        dataType: 'json',
        success: function (response) {
        	//console.log(response.Status);
        	$('#loadingmessage').hide();
        	if(response.Status == 1)
        	{
        		dropdown_jurisdiction_info = response[0]["dropdown_jurisdiction_info"];
        		//$("#form_category .juris-input-group #jurisdiction0").append("<option value='0'>Select Jurisdiction</option>");
        		$.each(dropdown_jurisdiction_info, function(key, val) {
			        var option = $('<option />');
			        option.attr('value', val['id']).text(val['jurisdiction']);
			        $("#form_category .juris-input-group #jurisdiction0").append(option);
			    });
        	}
        }
    });

    $('.start_date').datepicker({}).on('changeDate', function (selected) {
	    $('#form_category').formValidation('revalidateField', 'start_date[]');
	});
	$('.end_date').datepicker({});
    checkTableRow();

    $("#modal_category").modal("show");
});

function addPVTValidate()
{
    var bootstrapValidator = $("#form_payment_voucher_type").data('bootstrapValidator');

    if (bootstrapValidator != undefined)
        bootstrapValidator.destroy();

    $('#form_payment_voucher_type').bootstrapValidator({
        excluded: ':disabled',
        submitButtons: 'input[class="savePaymentVoucherType"]',
        fields: {
            payment_voucher_type: {
                validators: {
                    notEmpty: {
                        message: 'The Payment Voucher Type field is required.'
                    }
                }
            },
        }
    });
}

$(".create_payment_type").click(function(){
    //$('.form_payment_voucher_type').formValidation('addField', 'payment_voucher_type', payment_voucher_type_validation);
    addPVTValidate();
    $("#payment_voucher_type").val("");
    $("#modal_payment_voucher_type").modal("show");
});

$(document).on('click',"#rate_Add",function() {
    if($('.delete_rate').css('display') == 'none')
    {
        $('.delete_rate').css('display','inline-block');
    }

	$a = "";
	$a += '<tr class="tr_juris">';
	$a += '<td><input type="hidden" name="gst_category_info_id[]" value=""/><div class="juris-input-group"><select class="form-control" id="jurisdiction'+number_juris+'" name="jurisdiction[]"><option value="0">Select Jurisdiction</option></select></div></td>';
	$a += '<td><div class="date-input-group"><div class="input-group"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="start_date form-control" id="start_date'+number_juris+'" name="start_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></td>';
	$a += '<td><div class="input-group"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="end_date form-control" id="end_date'+number_juris+'" name="end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></td>';
	$a += '<td><div class="input-group"><input type="text" name="rate[]" class="form-control" value="" id="rate'+number_juris+'"/></div></td>';
	$a += '<td><input class="btn btn-primary delete_rate" type="button" id="delete_rate" value="Delete"/></td>';
	$a += '</tr>';
	
	$("#body_category_info").prepend($a);
	
	$.each(dropdown_jurisdiction_info, function(key, val) {
        //console.log(val['unit_pricing_name']);
        var option = $('<option />');
        option.attr('value', val['id']).text(val['jurisdiction']);
        $(".juris-input-group #jurisdiction" + number_juris).append(option);
    });

	$('#form_category').formValidation('addField', 'jurisdiction[]', jurisdiction);
    $('#form_category').formValidation('addField', 'start_date[]', start_date);
    $('#form_category').formValidation('addField', 'rate[]', rate);

    $('.start_date').datepicker({}).on('changeDate', function (selected) {
	    $('#form_category').formValidation('revalidateField', 'start_date[]');
	});
    $('.end_date').datepicker({});

    checkTableRow();

	number_juris++;
});

function checkTableRow()
{
    if(($("#body_category_info .tr_juris").length - 1) == 1)
    {
        //console.log($('.delete_rate').css('display'));
        if($('.delete_rate').css('display') == 'inline-block')
        {
            $('.delete_rate').css('display','none');
        }
    }
}

// $(document).on("submit","#form_payment_voucher_type",function(e){
//     e.preventDefault();
//     var $form = $(e.target);
//     var fv = $form.data('formValidation');
//     // Get the first invalid field
//     var $invalidFields = fv.getInvalidFields().eq(0);
//     // Get the tab that contains the first invalid field
//     var $tabPane     = $invalidFields.parents();
//     var valid_setup = fv.isValidContainer($tabPane);
//     console.log($form);
//     if(valid_setup)
//     {   
//         $('#loadingmessage').show();
//         $.ajax({
//             type: 'POST',
//             url: "admin_setting/save_payment_voucher_type",
//             data: $form.serialize(),
//             //async: false,
//             dataType: 'json',
//             success: function(response){
//                 if (response.Status === 1) 
//                 {
//                 }
//             }
//         });
//     }
// });

function submit_payment_voucher_info()
{
    var bootstrapValidator = $("#form_payment_voucher_type").data('bootstrapValidator');
    bootstrapValidator.validate();
    if(bootstrapValidator.isValid())
    {
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "admin_setting/save_payment_voucher_type",
            data: $("#form_payment_voucher_type").serialize(),
            //async: false,
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                if (response.Status === 1) 
                {
                    toastr.success(response.message, response.title);
                    $('#modal_payment_voucher_type').modal('toggle');

                    var payment_voucher_type = response[0]['payment_voucher_type'];

                    if ($.fn.DataTable.isDataTable('#datatable-payment-type-setting')) {
                        $('#datatable-payment-type-setting').DataTable().destroy();
                    }

                    $("#tbody_payment_type_list").empty();

                    //var table1 = $('#datatable-gst-setting').DataTable();
                    //table1.clear().draw();

                    masterPaymentVoucherTable(payment_voucher_type, true);
                }
            }
        });
    }
}

$(document).on('click',"#savePaymentVoucherType",function(e){
    e.preventDefault();
    submit_payment_voucher_info();
});

$(document).on("submit","#form_category",function(e){
    e.preventDefault();
    var $form = $(e.target);
    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    if(valid_setup)
    {   
    	$('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "admin_setting/save_category_list",
            data: $form.serialize() + '&delete_category_info=' + JSON.stringify(deleteCategoryTr),
            //async: false,
            dataType: 'json',
            success: function(response){
                if (response.Status === 1) 
                {
                    $('#loadingmessage').hide();
                    toastr.success(response.message, response.title);
                    $('#modal_category').modal('toggle');

                    var category_info = response[0]['category_info'];

                    if ($.fn.DataTable.isDataTable('#datatable-gst-setting')) {
                        $('#datatable-gst-setting').DataTable().destroy();
                    }

                    $("#tbody_category_list").empty();

                    //var table1 = $('#datatable-gst-setting').DataTable();
                    //table1.clear().draw();

                    masterCategoryTable(category_info, true);
                }
            }
        });
    }
});

$(document).on('click',"#saveCategory",function(e){
    e.preventDefault();
    $("#form_category").submit();
});

function changeDateFormatWithDash(date)
{
    if(date != null)
    {
        var change_date_parts = date.split('-');
        var change_date = change_date_parts[2]+"/"+change_date_parts[1]+"/"+change_date_parts[0];
    }
    else
    {
        change_date = "";
    }

    return change_date;
}

function formatDateFunc(date) {
    //console.log(date);
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return year + monthNames[monthIndex] + day;
}

if(category_info)
{
    masterCategoryTable(category_info);
}
else
{
    $(document).ready(function() {
        t_sa = $('#datatable-gst-setting').DataTable({
            'rowsGroup': [0]
        });
    });
}

function masterCategoryTable(category_info)
{   
    for(var t = 0; t < category_info.length; t++)
    {
        $b = "";
        $b += "<tr>";
        $b += "<td><a href='javascript:void(0)' onclick='editCategory("+category_info[t]["gst_category_id"]+")' class='pointer mb-sm mt-sm mr-sm'>"+category_info[t]["category"]+"</td>";
        $b += "<td>"+category_info[t]["jurisdiction"]+"</td>";
        $b += "<td><span style='display: none'>"+formatDateFunc(new Date(category_info[t]["start_date"]))+"</span>"+changeDateFormatWithDash(category_info[t]["start_date"])+"</td>";
        $b += "<td><span style='display: none'>"+((category_info[t]["end_date"] != null)?formatDateFunc(new Date(category_info[t]["end_date"])):"")+"</span>"+changeDateFormatWithDash(category_info[t]["end_date"])+"</td>";
        $b += "<td>"+category_info[t]["rate"]+"</td>";
        $b += "</tr>";

        $("#tbody_category_list").prepend($b);
    }

    $(document).ready(function() {
        t_sa = $('#datatable-gst-setting').DataTable({
            //destroy: true,
            'rowsGroup': [0]
        });
    });
}

if(payment_voucher_type)
{
    masterPaymentVoucherTable(payment_voucher_type);
}
else
{
    $(document).ready(function() {
        t_ps = $('#datatable-payment-type-setting').DataTable({
            'rowsGroup': [1]
        });
    });
}

function masterPaymentVoucherTable(payment_voucher_type)
{   
    for(var t = 0; t < payment_voucher_type.length; t++)
    {
        $b = "";
        $b += "<tr>";
        $b += "<td>"+(t+1)+"</td>";
        $b += "<td><a href='javascript:void(0)' onclick='editPaymentVoucher("+payment_voucher_type[t]["id"]+")' class='pointer mb-sm mt-sm mr-sm'>"+payment_voucher_type[t]["type_name"]+"</td>";
        $b += "</tr>";

        $("#tbody_payment_type_list").prepend($b);
    }

    $(document).ready(function() {
        t_ps = $('#datatable-payment-type-setting').DataTable({
            //destroy: true,
            'rowsGroup': [1]
        });

        t_ps.on( 'order.dt search.dt', function () {
            t_ps.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
}

//------------For datatable rowGroup destroy----------------------
$(document).on( 'destroy.dt', function ( e, settings ) {
    var api = new $.fn.dataTable.Api( settings );
    api.off('order.dt');
    api.off('preDraw.dt');
    api.off('column-visibility.dt');
    api.off('search.dt');
    api.off('page.dt');
    api.off('length.dt');
    api.off('xhr.dt');
});
//----------------------------------------------------------------

function editCategory(gst_category_id)
{
    //console.log(gst_category_id);
    $('#loadingmessage').show();
    $.ajax({
        type: 'POST',
        url: "admin_setting/get_edit_category",
        data: {"gst_category_id": gst_category_id},
        dataType: 'json',
        success: function(response){
            if (response.Status === 1) 
            {
                if($('.delete_rate').css('display') == 'none')
                {
                    $('.delete_rate').css('display','inline-block');
                }

                var edit_category_info = response[0]["edit_category_info"];
                deleteCategoryTr = new Array();
                //console.log(deleteCategoryTr);

                $("#add_category_form #form_category").remove();
                var jurisdiction_elem = document.querySelector('.hidden_form_category');
                // Get HTML content
                var html = jurisdiction_elem.innerHTML;

                $("#add_category_form").append(html);
                $('#add_category_form .origin_form_category').attr("id", "form_category");

                $("#category_id").val(edit_category_info[0]["gst_category_id"]);
                $("#category").val(edit_category_info[0]["category"]);

                for(var g = 0; g < edit_category_info.length; g++)
                {
                    if(g == 0)
                    {
                        $("#gst_category_info_id0").val(edit_category_info[0]["id"]);
                        $("#start_date0").val(changeDateFormatWithDash(edit_category_info[0]["start_date"]));
                        $("#end_date0").val(changeDateFormatWithDash(edit_category_info[0]["end_date"]));
                        $("#rate0").val(edit_category_info[0]["rate"]);

                        $.ajax({
                            url: "admin_setting/get_dropdown_jurisdiction_info",
                            type: "GET",
                            async: false,
                            dataType: 'json',
                            success: function (response) {
                                if(response.Status == 1)
                                {
                                    dropdown_jurisdiction_info = response[0]["dropdown_jurisdiction_info"];
                                    $.each(dropdown_jurisdiction_info, function(key, val) {
                                        var option = $('<option />');
                                        option.attr('value', val['id']).text(val['jurisdiction']);
                                        if(val['id'] == edit_category_info[0]["jurisdiction_id"])
                                        {
                                            option.attr('selected', 'selected');
                                        }
                                        $("#form_category .juris-input-group #jurisdiction0").append(option);
                                    });
                                }
                            }
                        });
                    }
                    else
                    {
                        $a = "";
                        $a += '<tr class="tr_juris">';
                        $a += '<td><input type="hidden" name="gst_category_info_id[]" id="gst_category_info_id'+number_juris+'" value=""/><div class="juris-input-group"><select class="form-control" id="jurisdiction'+number_juris+'" name="jurisdiction[]"><option value="0">Select Jurisdiction</option></select></div></td>';
                        $a += '<td><div class="date-input-group"><div class="input-group"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="start_date form-control" id="start_date'+number_juris+'" name="start_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></td>';
                        $a += '<td><div class="input-group"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="end_date form-control" id="end_date'+number_juris+'" name="end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></td>';
                        $a += '<td><div class="input-group"><input type="text" name="rate[]" class="form-control" value="" id="rate'+number_juris+'"/></div></td>';
                        $a += '<td><input class="btn btn-primary delete_rate" type="button" id="delete_rate" value="Delete"/></td>';
                        $a += '</tr>';
                        
                        $("#body_category_info").append($a);

                        $("#gst_category_info_id"+number_juris).val(edit_category_info[g]["id"]);
                        $("#start_date"+number_juris).val(changeDateFormatWithDash(edit_category_info[g]["start_date"]));
                        $("#end_date"+number_juris).val(changeDateFormatWithDash(edit_category_info[g]["end_date"]));
                        $("#rate"+number_juris).val(edit_category_info[g]["rate"]);

                        $.each(dropdown_jurisdiction_info, function(key, val) {
                            var option = $('<option />');
                            option.attr('value', val['id']).text(val['jurisdiction']);
                            if(val['id'] == edit_category_info[g]["jurisdiction_id"])
                            {
                                option.attr('selected', 'selected');
                            }
                            $("#form_category .juris-input-group #jurisdiction"+number_juris).append(option);
                        });

                        number_juris++;
                    }
                }

                $('#form_category').formValidation('addField', 'category', category);
                $('#form_category').formValidation('addField', 'jurisdiction[]', jurisdiction);
                $('#form_category').formValidation('addField', 'start_date[]', start_date);
                $('#form_category').formValidation('addField', 'rate[]', rate);

                $('.start_date').datepicker({}).on('changeDate', function (selected) {
                    $('#form_category').formValidation('revalidateField', 'start_date[]');
                });
                $('.end_date').datepicker({});

                $('#loadingmessage').hide();
                checkTableRow();
                $('#modal_category').modal('toggle');
            }
        }
    });
}

function editPaymentVoucher(payment_voucher_type_id)
{
    $('#loadingmessage').show();
    $.ajax({
        type: 'POST',
        url: "admin_setting/get_edit_payment_voucher_type",
        data: {"payment_voucher_type_id": payment_voucher_type_id},
        dataType: 'json',
        success: function(response){
            if (response.Status === 1) 
            {
                $('#loadingmessage').hide();
                var payment_voucher_type_info = response[0]["payment_voucher_type_info"];
                $("#payment_voucher_type_id").val(payment_voucher_type_info[0]["id"]);
                $("#payment_voucher_type").val(payment_voucher_type_info[0]["type_name"]);
                
                addPVTValidate();
                $("#modal_payment_voucher_type").modal("show");
            }
        }
    });
}

$(document).on('click',"#delete_rate",function(e){
    e.preventDefault();
    console.log($(this).parent().parent().find('input[name="gst_category_info_id[]"]').val());
    var tr = $(this).parent().parent(),
        category_info_id = tr.find('input[name="gst_category_info_id[]"]').val();

    tr.closest("tr").remove();

    deleteCategoryTr.push(category_info_id);
    console.log(deleteCategoryTr);
    checkTableRow();
    //$("#form_category").submit();
});



