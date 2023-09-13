$('[data-toggle="tooltip"]').tooltip();

$tabs = $('#fs_master_currency_modal #form_fs_setup_master_currency #fs_setup_master_currency_tbl');

$("#fs_master_currency_modal #form_fs_setup_master_currency #fs_setup_master_currency_tbl tbody#fs_setup_master_currency_tbody")
.sortable({
    connectWith: "#fs_setup_master_currency_tbody",
    items: "> tr:not(:first)",
    appendTo: $tabs,
    helper:"clone",
    zIndex: 999990,
    start: function(){ $tabs.addClass("dragging") },
    stop: function(){ 
    	$tabs.removeClass("dragging");
    	update_arrangement_currency();
	}
}).disableSelection();

function update_arrangement_currency()
{
	var currency_ids = [];

	$('#fs_master_currency_modal #form_fs_setup_master_currency #fs_setup_master_currency_tbl .currency_id').each(function()
	{
		currency_ids.push($(this).val());
	});

	$.ajax({ //Upload common input
	  	url: "fs_notes/update_fs_ntfs_master_currency",
	  	type: "POST",
	  	data: {fs_company_info_id: $('#fs_company_info_id').val(), currency_ids: currency_ids},
	    dataType: 'html',
	    success: function (response,data) {
	    	// $("#comprehensive_income").html(response);
	    	// insert_label_opinion(1, 'acb');
		}
	});
}

$("#account_category").modal({
    show: false,
    backdrop: 'static'
});
    
$("#account_category_btn").click(function() {
 	$("#account_category").modal("show");    

 	load_layout_tree_structure();         
});

$("#schedule_operating_expenses_btn").click(function() {
 	$("#schedule_operating_expenses_modal").modal("show");

 	load_schedule_operating_expenses();
});

$("#state_detailed_pro_loss_btn").click(function() {
 	$("#state_detailed_pro_loss_modal").modal("show");

 	load_state_detailed_pro_loss();           
});

$("#state_comp_income_btn").click(function() {
 	$("#state_comp_income_modal").modal("show");

 	load_state_comp_income();   

 	$('#opening_fs_statement_doc_type').val(1);        
});

$("#state_changes_in_equity_group_btn").click(function() {
 	$("#state_changes_in_equity_modal").modal("show");

 	load_state_changes_in_equity('group');   

 	// $('#opening_fs_statement_doc_type').val(1);        
});


$("#state_changes_in_equity_company_btn").click(function() {
 	$("#state_changes_in_equity_modal").modal("show");

 	load_state_changes_in_equity('company');   

 	// $('#opening_fs_statement_doc_type').val(1);        
});

$("#state_cash_flows_btn").click(function() {
 	$("#state_cash_flows_modal").modal("show");

 	load_state_cash_flows();   

 	$('#opening_fs_statement_doc_type').val(3);        
});


$("#state_financial_statement_group_btn").click(function() {
 	$("#state_financial_position_modal").modal("show");

 	load_financial_statement('group');      

 	$('#opening_fs_statement_doc_type').val(2);      
});

$("#state_financial_statement_company_btn").click(function() {
 	$("#state_financial_position_modal").modal("show");

 	load_financial_statement('company');  

 	$('#opening_fs_statement_doc_type').val(2);          
});

$("#nta_btn").click(function() 
{
	$('#loadingmessage').show();

 	$("#nta_modal").modal("show");

 	load_nta();
});

$('.collapse').collapse();



// handle modal when closing
$('#state_comp_income_modal').on('hidden.bs.modal', function() 
{
	var fs_note_templates_master_id = $('.fs_note_templates_master_id');

	$('input[name^="fs_note_details_id"]').each(function(key, value) // when fs_note_details_id is 0, we change fs_note_templates_master_id to 0 for "note details" purpose
	{
		if($(this).val() == 0)
		{
			$(fs_note_templates_master_id[key]).val(0);
		}
	});

	// var fs_note_templates_master_id = $('.fs_note_templates_master_id');

	// for (var i = fs_note_templates_master_id.length - 1; i >= 0; i--) 
	// {
	// 	// console.log(fs_note_templates_master_id[i]);
	// 	$(fs_note_templates_master_id[i]).val(0);
	// }
})

// load_partial();
// load_sub_account_list();

// function load_partial()
// {
// 	$.ajax({ //Upload common input
// 	  	url: "financial_statement/partial_state_comp_income",
// 	  	type: "POST",
// 	  	data: {fs_company_info_id: $(fs_company_info_id).val()},
// 	    dataType: 'html',
// 	    success: function (response,data) {
// 	    	$("#comprehensive_income").html(response);
// 	    	// insert_label_opinion(1, 'acb');
// 		}
// 	});
// }

function load_layout_tree_structure()
{
	$.ajax({ //Upload common input
	  	url: "fs_account_category/layout_tree_structure",
	  	type: "POST",
	  	// data: {fs_company_info_id: $(fs_company_info_id).val()},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#account_category .modal-body').html(response);
	    	// insert_label_opinion(1, 'acb');
		}
	});
}

function load_schedule_operating_expenses()
{
	$.ajax({ //Upload common input
	  	url: "fs_statements/schedule_operating_expense",
	  	type: "POST",
	  	data: {fs_company_info_id: $('#fs_company_info_id').val()},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#schedule_operating_expenses_modal .modal-body').html(response);
	    	// insert_label_opinion(1, 'acb');
		}
	});
}

function load_state_detailed_pro_loss()
{
	$.ajax({ //Upload common input
	  	url: "fs_statements/state_detailed_pro_loss",
	  	type: "POST",
	  	data: {fs_company_info_id: $('#fs_company_info_id').val()},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#state_detailed_pro_loss_modal .modal-body').html(response);
	    	// insert_label_opinion(1, 'acb');
		}
	});
}

function load_state_comp_income()
{	
	$.ajax({ //Upload common input
	  	url: "fs_statements/partial_state_comp_income",
	  	type: "POST",
	  	data: {fs_company_info_id: $("#fs_company_info_id").val()},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#state_comp_income_modal .modal-body').html(response);
	    	// insert_label_opinion(1, 'acb');
		}
	});
}

function load_state_changes_in_equity(group_company)
{
	$.ajax({ //Upload common input
	  	url: "fs_statements/partial_state_changes_in_equity",
	  	type: "POST",
	  	data: {fs_company_info_id: $('#fs_company_info_id').val(), group_company: group_company},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#state_changes_in_equity_modal .modal-body').html(response);
	    	// insert_label_opinion(1, 'acb');
	    	
	    	// console.log("group_company = " + group_company);
	    	if(group_company != "group")
	    	{
	    		$('#save_state_changes_in_equity_company').show();
	    		$('#save_state_changes_in_equity_group').hide();
	    	}
	    	else
	    	{
	    		$('#save_state_changes_in_equity_group').show();
	    		$('#save_state_changes_in_equity_company').hide();
	    	}
		}
	});
}

function load_setup_state_cash_flows()
{
	$.ajax({ //Upload common input
	  	url: "fs_statements/partial_setup_state_cash_flows",
	  	type: "POST",
	  	data: {fs_company_info_id: $("#fs_company_info_id").val()},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#fs_setup_cfs_modal .modal-body').html(response);
	    	// insert_label_opinion(1, 'acb');
		}
	});
}

function load_state_cash_flows()
{
	// console.log($(fs_company_info_id).val());
	
	$.ajax({ //Upload common input
	  	url: "fs_statements/partial_state_cash_flows",
	  	type: "POST",
	  	data: {fs_company_info_id: $("#fs_company_info_id").val()},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#state_cash_flows_modal .modal-body').html(response);
	    	// insert_label_opinion(1, 'acb');


		}
	});
}

function load_financial_statement(group_company)
{
	$.ajax({ //Upload common input
	  	url: "fs_statements/partial_financial_position",
	  	type: "POST",
	  	data: {fs_company_info_id: $('#fs_company_info_id').val(), group_company: group_company},
	    dataType: 'html',
	    success: function (response,data) {
	    	$('#state_financial_position_modal .modal-body').html(response);

	    	if(group_company != "group")
	    	{
	    		$('#save_state_financial_statement_company').show();
	    		$('#save_state_financial_statement_group').hide();
	    	}
	    	else
	    	{
	    		$('#save_state_financial_statement_group').show();
	    		$('#save_state_financial_statement_company').hide();
	    	}
		}
	});
}

function load_nta()
{
	$.ajax({ //Upload common input
		url: "financial_statement/partial_ntfs_layout",
		type: "POST",
		data: {fs_company_info_id: $('#fs_company_info_id').val()},
		dataType: 'html',
		success: function (response,data) 
		{
			$('#loadingmessage').hide();
			$("#nta_modal .modal-body").html(response);
		}
	});
}

$(".upload_TB").change(function() 
{
    var filename = readURL(this, 1);
    $(this).parent().children('span').html(filename);
});

$(".upload_LY_TB").change(function() 
{
    var filename = readURL(this, 0);
    $(this).parent().children('span').html(filename);
});



// function upload_TB(element)
// {
// 	console.log(element);

//     var filename = readURL(element);
//     $(this).parent().children('span').html(filename);
// }

// Read File and return value  
function readURL(input, current_year_tb) 
{
	var url = input.value;
	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();

	if (input.files && input.files[0] && (ext == "xlsx" || ext == "xls" || ext == "CSV")) 
	{
	  	var path = $(input).val();
	  	var filename = path.replace(/^.*\\/, "");

	  	// console.log(input.files[0]);
	  	var data = new FormData();
		data.append('excel_file', input.files[0]);
		data.append('fs_company_info_id', $("#fs_company_info_id").val());
		data.append('current_year_tb', current_year_tb);

		$.ajax({
			url: "fs_account_category/read_extract_excel",
			type: 'POST',
		  	processData: false, // important
		  	contentType: false, // important
		  	dataType : 'json',
		  	data: data,
		  	success: function (response, data) 
		  	{
		  		// console.log(response);

				alert(response.message);
				// $('#trial_b_error_msg_modal .modal-body').html(response.message);
				// $('#trial_b_error_msg_modal').modal("show");

		  		if(!response.status)
		  		{
		  			$(".upload_TB").parent().children('span').html("Upload Trial Balance");
		  		}
		  		else
		  		{
		  			$('#Categoried_Treeview').jstree(true).refresh();
		  			$('#Uncategoried_Treeview').jstree(true).refresh();
		  		}
		  	},
		  	error: function (error)
		  	{
		  		// console.log(error);
		  		alert("Error. Something went wrong. Please try again later.");

		  		$(".upload_TB").val("");
		  		$(".upload_TB").parent().children('span').html("Upload Trial Balance");
		  	}
		})
		return "Uploaded file : " + filename;
		// console.log(url);
	} else {
	  	$(input).val("");
	  	return "Only excel format are allowed!";
	}
}

function show_trial_b_info()
{
	$('#trial_b_error_msg_modal').modal("show");
}

function fs_setup_cfs() // open setup cash flows modal
{
	load_setup_state_cash_flows();
	$('#fs_setup_cfs_modal').modal("show");
}

function fs_setup_master_currency()
{
	$('#fs_master_currency_modal').modal("show");
}

function delete_fs_mc_row(element)
{
	var tr = $(element).parent().parent();
	var deleted_row_id = tr.find('.input_id').val();    

	$.ajax({
		url: "fs_notes/delete_fs_ntfs_master_currency",
		type: 'POST',
	  	dataType : 'json',
	  	data: {fs_ntfs_master_currency_id: deleted_row_id, fs_company_info_id: $("#fs_company_info_id").val()},
	  	success: function (response, data) 
	  	{
	  		if(response['result'])
	  		{
	  			tr.remove();
	  		}
	  	}
	});
}

function insert_tr_master_currency(element, data)
{
	var clone = $('#fs_setup_master_currency_tbl .tr_template_row').clone().show();
	var tr    = $('#fs_setup_master_currency_tbl').find('.add_tr_fs_master_currency');

	clone.attr("class", "");

	if(data == '')
	{
		data = {
				'id'				 : '',
				'currency_id' 		 : '',
				'currency_short_form': '',
				'currency_name' 	 : ''
			};
	}

	var loop_counter = 0;

	clone.find('td input').each(function(index)
	{
		if(loop_counter == 0)
		{
			$(this).attr("value", data['id']);
			$(this).attr("class", "input_id");
			$(this).attr("name", 'fs_ntfs_master_currency_id[]');
		}
		else if(loop_counter == 1)
		{
			$(this).attr("value", data['currency_id']);
			$(this).attr("class", "currency_id");
			$(this).attr("name", 'currency_id[]');
		}

		loop_counter++;
	});

	clone.find('td.currency_name').text(data['currency_name']);

	// for dropdown
	var dp = clone.find('td select');

	dp.attr("name", 'fs_master_currency_dp[]');
	dp.attr("value", data['currency_id']);

	dp.select2({
		allowClear: true,
		minimumInputLength: 2
	});

	tr.before(clone);
}

function update_fs_master_currency_info(element)
{
	var tr = $(element).parent().parent();
	var currency_id = $(element).val();
	var fs_ntfs_master_currency_id = tr.find("input[name='fs_ntfs_master_currency_id[]']").val();

	// console.log(fs_ntfs_master_currency_id);

	$.ajax({
		url: "fs_notes/save_get_currency_details",
		type: 'POST',
	  	dataType : 'json',
	  	data: $('#form_fs_setup_master_currency').serialize() + 
      			'&fs_company_info_id=' + $("#fs_company_info_id").val() +
      			'&currency_id=' + currency_id,
	  	success: function (response, data) 
	  	{
	  		var index = 0;

	  		tr.find('.currency_name').text(response['data'][0]['name']);

	  		$('#form_fs_setup_master_currency input[name="fs_ntfs_master_currency_id[]"]').each(function(index, element_1) 
			{
				$(element_1).val(response['fs_ntfs_mc_ids'][index]);
				index++;
			});

	  		tr.find('input[name="currency_id[]"]').val(currency_id);
	  	}
	});
}

$("#sub_account_list").on('hide.bs.modal', function()
{
	$('#input_new_description').val("");
});

$("#state_financial_position_modal").on('hide.bs.modal', function()
{
	$('#form_financial_position #account_balance_msg').text("");
	$('#tbl_financial_position tbody').html('');
});

$("#nta_modal").on('hide.bs.modal', function()
{
	$('#loadingmessage').show();
	window.location.href = "financial_statement/create/" + $('#client_id').val() + "/" + $('#fs_company_info_id').val();
});