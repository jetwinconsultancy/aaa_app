var id, splitid, index, options;

toastr.options = {
  "positionClass": "toast-bottom-right"
}

if(access_right_person_module == "read")
{
	$('input').attr('disabled', true);
	$('select').attr('disabled', true);
}

$('#date_of_birth').datepicker({ 
    dateFormat:'dd/mm/yyyy',
}).datepicker('setStartDate', '1800-01-01');

if(non_verify == 1)
{
	var state_non_verify_checkbox = false;
}
else
{
	var state_non_verify_checkbox = true;
}
$("[name='non_verify_checkbox']").bootstrapSwitch({
    state: state_non_verify_checkbox,
    size: 'normal',
    onColor: 'primary',
    onText: 'YES',
    offText: 'NOT',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '75px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'


});

$("[name='non_verify_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    if(state == true)
    {
        $("[name='hidden_non_verify_checkbox']").val(0);
    }
    else
    {
        $("[name='hidden_non_verify_checkbox']").val(1);
    }
});

if(tab_aktif == '')
{
	tab_aktif = "individual";
}

$(document).on('click',".check_stat",function() {
	tab_aktif = $(this).data("information");
});
$(document).on('click',"#save",function(){
	$("form #tr_"+tab_aktif+"_edit").submit();
});

if(access_right_person_module == "read")
{
	$('input').attr('disabled', true);
}

if(corp_rep_data)
{
	$count_corp_rep = corp_rep_data.length;
}
else
{
	$count_corp_rep = 0;
}
$(document).on('click',"#corp_rep_Add",function() {

	$count_corp_rep++;
 	$a = ""; 
	$a += '<tr class="row_corp_rep">';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="subsidiary_name[]" id="subsidiary_name_'+$count_corp_rep+'" class="form-control subsidiary_name" value=""/></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_name[]" id="corp_rep_name" class="form-control" value=""/></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_identity_number[]" class="form-control corp_rep_identity_number" value="" id="corp_rep_identity_number" maxlength="15"/></td>';
	$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
	$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_officer(this);">Delete</button></div></td>';
	$a += '</tr>';
	//<input type="hidden" name="subsidiary_id[]" class="form-control subsidiary_id" id="subsidiary_id_'+$count_corp_rep+'" value=""/>
	$("#body_corp_rep").prepend($a); 

	$('#corp_rep_date_of_appointment').datepicker({ 
    	dateFormat:'dd/mm/yyyy',
	});

	$('#corp_rep_date_of_cessation').datepicker({ 
    	dateFormat:'dd/mm/yyyy',
	});
});

function delete_officer(element)
{
	var tr = jQuery(element).parent().parent().parent();
	//console.log(tr);
	tr.remove();
}

if(corp_rep_data)
{
	for(var i = 0; i < corp_rep_data.length; i++)
	{
		$a = ""; 
		$a += '<tr class="row_corp_rep">';
		$a += '<td><input type="text" style="text-transform:uppercase;" name="subsidiary_name[]" id="subsidiary_name_'+i+'" class="form-control subsidiary_name" value="'+corp_rep_data[i]["subsidiary_name"]+'"/><input type="hidden" name="subsidiary_id[]" class="form-control subsidiary_id" id="subsidiary_id_'+i+'" value="'+corp_rep_data[i]["client_id"]+'"/></td>';
		$a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_name[]" id="corp_rep_name" class="form-control" value="'+corp_rep_data[i]["name_of_corp_rep"]+'"/></td>';
		$a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_identity_number[]" class="form-control corp_rep_identity_number" value="'+corp_rep_data[i]["identity_number"]+'" id="corp_rep_identity_number" maxlength="15"/></td>';
		$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+corp_rep_data[i]["effective_date"]+'" placeholder="DD/MM/YYYY"></div></td>';
		$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+corp_rep_data[i]["cessation_date"]+'" placeholder="DD/MM/YYYY"></div></td>';
		$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_officer(this);">Delete</button></div></td>';
		$a += '</tr>';
		
		$("#body_corp_rep").prepend($a); 

		$('#corp_rep_date_of_appointment').datepicker({ 
	    	dateFormat:'dd/mm/yyyy',
		});

		$('#corp_rep_date_of_cessation').datepicker({ 
	    	dateFormat:'dd/mm/yyyy',
		});
	}
}

// (function( $ ) {

// 	'use strict';

// 	var datatableTransactionInit = function() {

// 		$('#datatable-screening').dataTable({
// 	        //"pagingType": "full_numbers"
// 	    });

// 	};

// 	$(function() {
// 		datatableTransactionInit();
// 	});

// }).apply( this, [ jQuery ]);
