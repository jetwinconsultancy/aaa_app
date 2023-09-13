// var id, splitid, index, options;

// if(transaction_incorporation_subsidiary)
// {
// 	$count_corp_rep = transaction_incorporation_subsidiary.length;
// }
// else
// {
// 	$count_corp_rep = 0;
// }
// $(document).on('click',"#corp_rep_Add",function() {

// 	$count_corp_rep++;
//  	$a = ""; 
// 	$a += '<tr class="row_corp_rep">';
// 	$a += '<td><input type="text" style="text-transform:uppercase;" name="subsidiary_name[]" id="subsidiary_name_'+$count_corp_rep+'" class="form-control subsidiary_name" value=""/></td>';
// 	$a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_name[]" id="corp_rep_name" class="form-control" value=""/></td>';
// 	$a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_identity_number[]" class="form-control corp_rep_identity_number" value="" id="corp_rep_identity_number" maxlength="15"/></td>';
// 	$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
// 	$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
// 	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_officer(this);">Delete</button></div></td>';
// 	$a += '</tr>';
// 	$("#body_corp_rep").prepend($a); 

// 	$('#corp_rep_date_of_appointment').datepicker({ 
//     	dateFormat:'dd/mm/yyyy',
// 	});

// 	$('#corp_rep_date_of_cessation').datepicker({ 
//     	dateFormat:'dd/mm/yyyy',
// 	});

// });

// function delete_officer(element)
// {
// 	var tr = jQuery(element).parent().parent().parent();
// 	tr.remove();
// }

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
// 		/*$("client_id").val(element.id);*/
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




$(document).on('click',"#submitIncorpSubsidiaryInfo",function(e){
    $('#loadingmessage').show();

    $.ajax({ //Upload common input
      url: "transaction/add_incorp_subsidiary",
      type: "POST",
      data: $('form#incorp_subsidiary_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            // $("#body_appoint_new_director .row_appoint_new_director").remove();
            //console.log($("#transaction_trans #transaction_master_id"));
            //$(".transaction_change_regis_ofis_address_id").val(response.transaction_change_regis_ofis_address_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
          }
        }
    });
});