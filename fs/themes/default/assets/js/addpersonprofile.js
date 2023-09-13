var id, splitid, index, options;

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

	//$('#subsidiary_name_'+$count_corp_rep).easyAutocomplete(options);

});

function delete_officer(element)
{
	var tr = jQuery(element).parent().parent().parent();
	//console.log(tr);
	tr.remove();
}

// $(document).on("focus", ".subsidiary_name", function () {
// 	id = this.id;
// 	splitid = id.split('_');
//   	index = splitid[2];
// 	console.log(id);
// 	console.log($('#'+id).val());
// });

// options = {

// 	url: function(phrase) {
// 		return "documents/clientSearch";
// 	},

// 	getValue: function(element) {
// 		//console.log(element);
// 		return element.name;
// 	},

// 	ajaxSettings: {
// 		dataType: "json",
// 		method: "POST",
// 		data: {
// 		  dataType: "json"
// 		}
// 	},

// 	preparePostData: function(data) {
// 		data.phrase = $('#'+id).val();
// 		//console.log(data)
// 		return data;
// 	},

// 	list: {
// 		onSelectItemEvent: function() {
// 			var client_id = $('#subsidiary_name_'+index).getSelectedItemData().id;
// 			console.log(client_id);
// 			$('#subsidiary_id_'+index).val(client_id).trigger("change");
// 		}	
// 	},

// 	requestDelay: 400
// };

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

		//$('#subsidiary_name_'+i).easyAutocomplete(options);
	}
}
