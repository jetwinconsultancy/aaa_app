var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

$pv_index_tab_aktif = "vendor_info";
var bank_acc_info;

if(active_tab != null)
{  
    if(active_tab != "vendor_info")
    {
        $pv_index_tab_aktif = active_tab;

        $('li[data-information="'+active_tab+'"]').addClass("active");
        $('#w2-'+active_tab+'').addClass("active");
        $('li[data-information="vendor_info"]').removeClass("active");
        $('#w2-vendor_info').removeClass("active");
        $("#exportExcel").hide();

        if(active_tab == "payment_voucher")
        {
            $(".create_vendor").hide();
            $(".create_payment_voucher").show();
            $(".create_claim").hide();
            $(".create_pv_receipt").hide();
            $("#exportExcel").show();
        }
        else if(active_tab == "claim")
        {
            $(".create_vendor").hide();
            $(".create_payment_voucher").hide();
            $(".create_claim").show();
            $(".create_pv_receipt").hide();
            $("#exportExcel").show();
        }
        else if(active_tab == "pv_receipt")
        {
            $(".create_vendor").hide();
            $(".create_payment_voucher").hide();
            $(".create_claim").hide();
            $(".create_pv_receipt").show();
            $("#exportExcel").show();
        }
    }
}

if(pv_check_state)
{
    if(pv_check_state != "vendor_info")
    {
        $pv_index_tab_aktif = pv_check_state;
        
        $('li[data-information="'+pv_check_state+'"]').addClass("active");
        $('#w2-'+pv_check_state+'').addClass("active");
        $('li[data-information="vendor_info"]').removeClass("active");
        $('#w2-vendor_info').removeClass("active");
        $("#exportExcel").hide();

        if(pv_check_state == "payment_voucher")
        {
            $(".create_vendor").hide();
            $(".create_payment_voucher").show();
            $(".create_claim").hide();
            $("#exportExcel").show();
        }
        else if(pv_check_state == "claim")
        {
            $(".create_vendor").hide();
            $(".create_payment_voucher").hide();
            $(".create_claim").show();
            $("#exportExcel").show();
        }
        else if(pv_check_state == "pv_receipt")
        {
            $(".create_vendor").hide();
            $(".create_payment_voucher").hide();
            $(".create_claim").hide();
            $(".create_pv_receipt").show();
            $("#exportExcel").show();
        }
    }
}

$('#form_claim_cheque').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },

    fields: {
        cheque_number: {
        	row: '.cheque-number-input-group',
            validators: {
		        notEmpty: {
		            message: 'The Cheque Number field is required.'
		        }
		    }
        },
        bank_account: {
        	row: '.input-group',
            validators: {
                callback: {
                    message: 'The Bank Account field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('bank_account').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },

    }
});

$('#form_payment_cheque').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },

    fields: {
        cheque_number: {
        	row: '.cheque-number-input-group',
            validators: {
		        notEmpty: {
		            message: 'The Cheque Number field is required.'
		        }
		    }
        },
        bank_account: {
        	row: '.input-group',
            validators: {
                callback: {
                    message: 'The Bank Account field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('bank_account').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },

    }
});

$('#form_receipt_cheque').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },

    fields: {
        cheque_number: {
            row: '.cheque-number-input-group',
            validators: {
                notEmpty: {
                    message: 'The Cheque Number field is required.'
                }
            }
        },
        bank_account: {
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Bank Account field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('bank_account').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },

    }
});

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
            //console.log(e);
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

function Claim_index() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getBankAcc = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getBankAcc';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.bank_account').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.bank_account').find("option:eq(0)").html("Select Bank Account");
            //console.log(data);
            if(data.tp == 1){
            	$(".bank_account option").remove();
            	var first_option = $('<option />');
                 	first_option.attr('value', "0").text("Select Bank Account");

                $('.bank_account').append(first_option);

                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_bank_acc != null && key == data.selected_bank_acc)
                    {
                        option.attr('selected', 'selected');
                        //$('.currency').attr('disabled', true);
                    }
                    
                    $('.bank_account').append(option);
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
    bank_acc_info = new Claim_index();
});


(function( $ ) {

	'use strict';

	function sortNumbersIgnoreText(a, b, high) {

	    var reg = /[+-]?((\d+(\.\d*)?)|\.\d+)([eE][+-]?[0-9]+)?/;    
        a = a.replace(/(<a[^>]+>|<a>|<\/a>)/g, '');   
        a = a.substr(-1);  
        a = a !== null ? parseInt(a) : high;
        b = b.replace(/(<a[^>]+>|<a>|<\/a>)/g, '');
        b = b.substr(-1);    
        b = b !== null ? parseInt(b) : high;

        return ((a < b) ? -1 : ((a > b) ? 1 : 0));        
	}

	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "sort-numbers-ignore-text-asc": function (a, b) {
            return sortNumbersIgnoreText(a, b, Number.POSITIVE_INFINITY);
        },
        "sort-numbers-ignore-text-desc": function (a, b) {
            return sortNumbersIgnoreText(a, b, Number.NEGATIVE_INFINITY) * -1;
        }
	});

	var datatableInit = function() {

		var table1 = $('#datatable-vendor_info').DataTable({
			"columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            'type': 'num', 
	            'targets': 0
	        }],
            "order": [[ 1, 'asc' ]]

		});
		table1.on( 'order.dt search.dt', function () {
            table1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

		var table2 = $('#datatable-payment_voucher').DataTable({
			"columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            'type': 'num', 
	            "targets": 0
	        } ],
	        "order": [[ 3, 'desc' ]]
		});
		table2.on( 'order.dt search.dt', function () {
            table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        var table3 = $('#datatable-claim').DataTable({
//             dom: "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-12't>>" +
// "<'row'<'col-sm-5'i><'col-sm-7'p>>",
//             buttons: [
//                 {
//                     extend: 'excel',
//                     exportOptions: {
//                         columns: [ 0, 1, 2, 3, 4, 5, 6],
//                         format: {
//                              body: function (data, row, column, node) {
//                                //console.log(data);
//                                 if(column === 2){
//                                     data = data.split('<span')[0];

//                                 }

//                                 if(column === 6){
//                                     data = data.replace(/&amp;/g, "&");

//                                 }

//                                 return column === 3 ?
//                                       data.replace(/<.*?>/ig, ""): data;

//                            }
//                         }
//                     }
//                 },
//               ],
			"columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            'type': 'num', 
	            "targets": 0
	        } ],
	        "order": [[ 3, 'desc' ]]
            
		});

		table3.on( 'order.dt search.dt', function () {
            table3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        var table4 = $('#datatable-pv_receipt').DataTable({
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                'type': 'num', 
                "targets": 0
            } ],
            "order": [[ 3, 'desc' ]]
        });
        table4.on( 'order.dt search.dt', function () {
            table4.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

	};

	$(function() {
		datatableInit();
	});

}).apply( this, [ jQuery ]);

$(document).on('click','.tonggle_readmore',function (){
	$id = $(this).data('id');
	$("#"+$id).toggle();
});

function approveClaim($pv_id){
	bootbox.confirm({
        message: "Do you want to approve this selected info?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
        	console.log($pv_index_tab_aktif);
        	if(result == true)
        	{
        		if($pv_index_tab_aktif == "claim")
				{
					$.ajax({
						type: "POST",
						url: "payment_voucher/approve_claim",
						data: {"claim_id":$pv_id, "tab": $pv_index_tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
							//console.log(response);
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
						}				
					});
				}
        	}

        }
    });
}

function approvePV($pv_id){
	bootbox.confirm({
        message: "Do you want to approve this selected info?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
        	if(result == true)
        	{
        		if($pv_index_tab_aktif == "payment_voucher")
				{
					$.ajax({
						type: "POST",
						url: "payment_voucher/approve_pv",
						data: {"payment_voucher_id":$pv_id, "tab": $pv_index_tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
						}				
					});
				}
        	}

        }
    });
}

function approveReceipt($pv_id){
    bootbox.confirm({
        message: "Do you want to approve this selected info?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            //console.log(result);
            if(result == true)
            {
                if($pv_index_tab_aktif == "pv_receipt")
                {
                    $.ajax({
                        type: "POST",
                        url: "payment_voucher/approve_pv_receipt",
                        data: {"payment_receipt_id":$pv_id, "tab": $pv_index_tab_aktif}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        success: function(response){
                            //console.log(response);
                            if(response.Status == 1)
                            {
                                toastr.success(response.message, response.title);
                                location.reload();
                            }
                        }               
                    });
                }
            }

        }
    });
}

function reasonPV($cancel_reason){
	//console.log($cancel_reason)
	bootbox.alert($cancel_reason);
}

function reasonClaim($cancel_reason){
	//console.log($cancel_reason)
	bootbox.alert($cancel_reason);
}

function cancelClaim($pv_id){
    bootbox.prompt({
        title: "Reason",
        inputType: 'textarea',
        closeButton: false,
        callback: function (result) {
            console.log($pv_index_tab_aktif);
            if(result != null)
            {
                //console.log(result);
                if(result == "")
                {
                    toastr.error("Please enter a reason.", "Error");
                    return false; 
                }
                else
                {
                    if($pv_index_tab_aktif == "claim")
					{
						$.ajax({
							type: "POST",
							url: "payment_voucher/cancel_claim",
							data: {"claim_id":$pv_id, "tab": $pv_index_tab_aktif, "cancel_reason": result}, // <--- THIS IS THE CHANGE
							dataType: "json",
							success: function(response){
								//console.log(response);
								if(response.Status == 1)
								{
									toastr.success(response.message, response.title);
									location.reload();
								}
							}				
						});
					}
                }
            }
            
        }
    });
}

function cancelReceipt($pv_id){
    bootbox.prompt({
        title: "Reason",
        inputType: 'textarea',
        closeButton: false,
        callback: function (result) {
            
            if(result != null)
            {
                //console.log(result);
                if(result == "")
                {
                    toastr.error("Please enter the a reason.", "Error");
                    return false; 
                }
                else
                {
                    if($pv_index_tab_aktif == "pv_receipt")
                    {
                        $.ajax({
                            type: "POST",
                            url: "payment_voucher/cancel_pv_receipt",
                            data: {"payment_receipt_id":$pv_id, "tab": $pv_index_tab_aktif, "cancel_reason": result}, // <--- THIS IS THE CHANGE
                            dataType: "json",
                            success: function(response){
                                //console.log(response);
                                if(response.Status == 1)
                                {
                                    toastr.success(response.message, response.title);
                                    location.reload();
                                }
                            }               
                        });
                    }
                }
            }
            
        }
    });
}

function cancelPV($pv_id){
    bootbox.prompt({
        title: "Reason",
        inputType: 'textarea',
        closeButton: false,
        callback: function (result) {
            
            if(result != null)
            {
                //console.log(result);
                if(result == "")
                {
                    toastr.error("Please enter the a reason.", "Error");
                    return false; 
                }
                else
                {
                    if($pv_index_tab_aktif == "payment_voucher")
					{
						$.ajax({
							type: "POST",
							url: "payment_voucher/cancel_pv",
							data: {"payment_voucher_id":$pv_id, "tab": $pv_index_tab_aktif, "cancel_reason": result}, // <--- THIS IS THE CHANGE
							dataType: "json",
							success: function(response){
								//console.log(response);
								if(response.Status == 1)
								{
									toastr.success(response.message, response.title);
									location.reload();
								}
							}				
						});
					}
                }
            }
            
        }
    });
}

function deletePV($pv_id){
	bootbox.confirm({
        message: "Do you want to delete this selected info?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
        	//console.log(result);
        	if(result == true)
        	{
        		if($pv_index_tab_aktif == "vendor_info")
				{
					$.ajax({
						type: "POST",
						url: "payment_voucher/delete_vendor",
						data: {"vendor_id":$pv_id, "tab": $pv_index_tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
							//console.log(response);
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
						}				
					});
				}
				else if($pv_index_tab_aktif == "payment_voucher")
				{
					$.ajax({
						type: "POST",
						url: "payment_voucher/delete_vendor",
						data: {"payment_voucher_id":$pv_id, "tab": $pv_index_tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
							//console.log(response);
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
						}				
					});
				}
				else if($pv_index_tab_aktif == "claim")
				{
					$.ajax({
						type: "POST",
						url: "payment_voucher/delete_vendor",
						data: {"claim_id":$pv_id, "tab": $pv_index_tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
							//console.log(response);
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
								//pv_check_state = $pv_index_tab_aktif;
							}
						}				
					});
				}
                else if($pv_index_tab_aktif == "pv_receipt")
                {
                    $.ajax({
                        type: "POST",
                        url: "payment_voucher/delete_vendor",
                        data: {"payment_receipt_id":$pv_id, "tab": $pv_index_tab_aktif}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        success: function(response){
                            //console.log(response);
                            if(response.Status == 1)
                            {
                                toastr.success(response.message, response.title);
                                location.reload();
                                //pv_check_state = $pv_index_tab_aktif;
                            }
                        }               
                    });
                }
        	}

        }
    });
}

$(document).on('click',".pv_check_state",function() 
{
	$pv_index_tab_aktif = $(this).data("information");
	$(".submit_pv_check_state").val($pv_index_tab_aktif);

	if($pv_index_tab_aktif == "vendor_info")
	{
		//console.log($("#billing_footer_button"));
		$(".create_vendor").show();
		$(".create_payment_voucher").hide();
		$(".create_claim").hide();
        $(".create_pv_receipt").hide();
        $("#exportExcel").hide();
	}
	else if($pv_index_tab_aktif == "payment_voucher")
	{
		$(".create_vendor").hide();
		$(".create_payment_voucher").show();
		$(".create_claim").hide();
        $(".create_pv_receipt").hide();
        $("#exportExcel").show();
	}
	else if($pv_index_tab_aktif == "claim")
	{
		$(".create_vendor").hide();
		$(".create_payment_voucher").hide();
		$(".create_claim").show();
        $(".create_pv_receipt").hide();
        $("#exportExcel").show();
	}
    else if($pv_index_tab_aktif == "pv_receipt")
    {
        $(".create_vendor").hide();
        $(".create_payment_voucher").hide();
        $(".create_claim").hide();
        $(".create_pv_receipt").show();
        $("#exportExcel").show();
    }
});

$(document).on('click',"#searchResult",function(e){
    $("#form_search_payment_voucher").submit();
});

function updatePaymentChequeNum($claim_id)
{
	$("#payment_cheque_id").val($claim_id);
	bank_acc_info.getBankAcc();
	$("#modal_payment_cheque_list").modal("show");
}

function updateReceiptChequeNum($pv_receipt_id)
{
    $("#receipt_cheque_id").val($pv_receipt_id);
    bank_acc_info.getBankAcc();
    $("#modal_receipt_cheque_list").modal("show");
}

$(document).on("submit", "#form_payment_cheque", function(e){
	
	e.preventDefault();
	var $form = $(e.target);
	
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    //console.log(fv);
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    fv.disableSubmitButtons(false);

    //console.log(valid_setup);
    if(valid_setup)
    {
    	//$("#form_claim_cheque").formValidation('destroy');
    	$.ajax({
	        type: 'POST',
	        url: "payment_voucher/save_payment_cheque",
	        data: $form.serialize(),
	        dataType: 'json',
	        success: function(response){
	            if (response.Status === 1) 
	            {
	            	toastr.success(response.message, response.title);
	            	$('#modal_payment_cheque_list').modal('toggle');

	            	$("#form_payment_cheque #bank_account").val("0");
	            	$("#form_payment_cheque #cheque_number").val("");

	            	$('#form_payment_cheque').formValidation('revalidateField', 'bank_account');
	            	$('#form_payment_cheque').formValidation('revalidateField', 'cheque_number');
	            	location.reload();
	            }
	        }
		});
    }
});

$(document).on('click',"#savePaymentChequeList",function(e){
	$("#form_payment_cheque").submit();
    //$("#modal_claim_cheque_list").modal("hide");
});

function updateClaimChequeNum($claim_id)
{
	$("#claim_cheque_id").val($claim_id);
	bank_acc_info.getBankAcc();
	$("#modal_claim_cheque_list").modal("show");
}

$(document).on("submit", "#form_claim_cheque", function(e){
	
	e.preventDefault();
	var $form = $(e.target);
	
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    //console.log(fv);
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    fv.disableSubmitButtons(false);

    //console.log(valid_setup);
    if(valid_setup)
    {
    	//$("#form_claim_cheque").formValidation('destroy');
    	$.ajax({
	        type: 'POST',
	        url: "payment_voucher/save_claim_cheque",
	        data: $form.serialize(),
	        dataType: 'json',
	        success: function(response){
	            if (response.Status === 1) 
	            {
	            	toastr.success(response.message, response.title);
	            	$('#modal_claim_cheque_list').modal('toggle');

	            	$("#form_claim_cheque #bank_account").val("0");
	            	$("#form_claim_cheque #cheque_number").val("");

	            	$('#form_claim_cheque').formValidation('revalidateField', 'bank_account');
	            	$('#form_claim_cheque').formValidation('revalidateField', 'cheque_number');
	            	location.reload();
	            }
	        }
		});
    }
});

$(document).on('click',"#saveClaimChequeList",function(e){
	$("#form_claim_cheque").submit();
    //$("#modal_claim_cheque_list").modal("hide");
});

$(document).on("submit", "#form_receipt_cheque", function(e){
    
    e.preventDefault();
    var $form = $(e.target);
    
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    //console.log(fv);
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    fv.disableSubmitButtons(false);

    //console.log(valid_setup);
    if(valid_setup)
    {
        //$("#form_claim_cheque").formValidation('destroy');
        $.ajax({
            type: 'POST',
            url: "payment_voucher/save_receipt_cheque",
            data: $form.serialize(),
            dataType: 'json',
            success: function(response){
                if (response.Status === 1) 
                {
                    toastr.success(response.message, response.title);
                    $('#modal_receipt_cheque_list').modal('toggle');

                    $("#form_receipt_cheque #bank_account").val("0");
                    $("#form_receipt_cheque #cheque_number").val("");

                    $('#form_receipt_cheque').formValidation('revalidateField', 'bank_account');
                    $('#form_receipt_cheque').formValidation('revalidateField', 'cheque_number');
                    location.reload();
                }
            }
        });
    }
});

$(document).on('click',"#saveReceiptChequeList",function(e){
    $("#form_receipt_cheque").submit();
    //$("#modal_claim_cheque_list").modal("hide");
});

$(document).on('click',"#exportExcel",function(e){
    $('#loadingmessage').show();
    $.ajax({
        type: "POST",
        url: "payment_voucher/export_excel",
        data: {"tab": $pv_index_tab_aktif, "search_name": $(".search").val(), "start_date": $(".start").val(), "end_date": $(".end").val()}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            //console.log(response.link);
            //console.log(window.URL);
            // for(var b = 0; b < response.link.length; b++) 
            // {
                //console.log(response);
                //window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
                $('#loadingmessage').hide();
                if(response.status != "fail")
                {
                    window.open(
                          response.link,
                          '_blank' // <- This is what makes it open in a new window.
                        );
                }
                else
                {
                    toastr.error("The excel cannot be generated.", "Unsuccessful");
                }

                
            //}
            
            //setTimeout(function(){ deleteExcelPayment(); }, 50000);
        }               
    });
});

// function deleteExcelPayment()
// {
//     $.ajax({ //Upload common input
//       url: "payment_voucher/delete_pv_excel",
//       async: false,
//       type: "POST",
//       //data: {"path":link},
//       dataType: 'json',
//       success: function (response,data) {
//         //console.log(response);
//       }
//     })
// }

function exportClaimPDF($claim_id)
{

	bootbox.confirm({
        message: "Do you want to print Pre-printed Letterhead document?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
        	//console.log($pv_index_tab_aktif);
            if($pv_index_tab_aktif == "claim")
			{
				// var billingCheckboxes = new Array();
				// $('input[name="billing_checkbox"]:checked').each(function() {
				//    billingCheckboxes.push($(this).val());
				// });
				// var billingCheckboxes = new Array();
				// billingCheckboxes.push($billing_id);
				//console.log($tab_aktif);
				$('#loadingmessage').show();
				$.ajax({
					type: "POST",
					url: "createclaimpdf/create_claim_pdf",
					data: {"pv_id":$claim_id, "tab": $pv_index_tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						//console.log(response.link);
						//console.log(window.URL);
						for(var b = 0; b < response.link.length; b++) 
						{
							//console.log(response);
							//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
							$('#loadingmessage').hide();
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);

							
						}
						//setTimeout(function(){ deleteClaimPDF(); }, 5000);
					}				
				});
			}
        }
    });
}

// function deleteClaimPDF()
// {
//     $.ajax({ //Upload common input
//       url: "createclaimpdf/delete_claim_pdf",
//       async: false,
//       type: "POST",
//       //data: {"path":link},
//       dataType: 'json',
//       success: function (response,data) {
//         //console.log(response);
//       }
//     })
// }

// function deleteReceiptPDF()
// {
//     $.ajax({ //Upload common input
//       url: "createpvreceiptpdf/delete_receipt_pdf",
//       async: false,
//       type: "POST",
//       //data: {"path":link},
//       dataType: 'json',
//       success: function (response,data) {
//         //console.log(response);
//       }
//     })
// }

function exportReceiptPDF($pv_id)
{
    bootbox.confirm({
        message: "Do you want to print Pre-printed Letterhead document?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if($pv_index_tab_aktif == "pv_receipt")
            {
                // var billingCheckboxes = new Array();
                // $('input[name="billing_checkbox"]:checked').each(function() {
                //    billingCheckboxes.push($(this).val());
                // });
                // var billingCheckboxes = new Array();
                // billingCheckboxes.push($billing_id);
                //console.log($tab_aktif);
                $('#loadingmessage').show();
                $.ajax({
                    type: "POST",
                    url: "createpvreceiptpdf/create_pv_receipt_pdf",
                    data: {"pv_id":$pv_id, "tab": $pv_index_tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
                    dataType: "json",
                    success: function(response){
                        //console.log(response.link);
                        //console.log(window.URL);
                        for(var b = 0; b < response.link.length; b++) 
                        {
                            //console.log(response);
                            //window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
                            $('#loadingmessage').hide();
                            window.open(
                                  response.link[b],
                                  '_blank' // <- This is what makes it open in a new window.
                                );

                            
                        }
                        //setTimeout(function(){ deleteReceiptPDF(); }, 5000);
                    }               
                });
            }
        }
    });
}

function exportPVPDF($pv_id){

	bootbox.confirm({
        message: "Do you want to print Pre-printed Letterhead document?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if($pv_index_tab_aktif == "payment_voucher")
			{
				// var billingCheckboxes = new Array();
				// $('input[name="billing_checkbox"]:checked').each(function() {
				//    billingCheckboxes.push($(this).val());
				// });
				// var billingCheckboxes = new Array();
				// billingCheckboxes.push($billing_id);
				//console.log($tab_aktif);
				$('#loadingmessage').show();
				$.ajax({
					type: "POST",
					url: "createpvpdf/create_pv_pdf",
					data: {"pv_id":$pv_id, "tab": $pv_index_tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						//console.log(response.link);
						//console.log(window.URL);
						for(var b = 0; b < response.link.length; b++) 
						{
							//console.log(response);
							//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
							$('#loadingmessage').hide();
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);

							
						}
						//setTimeout(function(){ deletePVPDF(); }, 5000);
					}				
				});
			}
        }
    });
}

// function deletePVPDF()
// {
//     $.ajax({ //Upload common input
//       url: "createpvpdf/delete_pv_pdf",
//       async: false,
//       type: "POST",
//       //data: {"path":link},
//       dataType: 'json',
//       success: function (response,data) {
//         //console.log(response);
//       }
//     })
// }
