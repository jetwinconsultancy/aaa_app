var index = 0;
var director_index = 0;
var arr_deleted_company = [];
var arr_deleted_directors = [];

$("[name='hidden_director_interest_checkbox']").bootstrapSwitch({
    // state: state_checkbox,
    size: 'small',
    onColor: 'primary',
    onText: 'YES',
    offText: 'NO',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '45px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'
});

// Triggered on switch state change.
$("[name='hidden_director_interest_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    var hidden_val = $(event.target).parent().parent().parent().find("[name='director_interest_checkbox']");
    var director_interest_sec = $(".director_interest_sec");

    if(state)
    {
        hidden_val.val(1);
        director_interest_sec.show();
    }
    else
    {
        hidden_val.val(0);
        director_interest_sec.hide();
    }
})

// $( document ).ready(function() {
	// console.log(fs_dir_state_company);
	retrieve_data(fs_dir_state_company);

	$('.fs_dropdown').select2();
// });

function retrieve_data(list)
{
	for (i = 0; i < list.length; i++) 
	{
		add_row(list[i]['fs_company_type_id'], list[i]);
		// .then(
		// 	function(){
		// 		add_dir_row_init(list[i]['id']);
		// 	});

		// console.log('--- newline ---');
	}
}

function add_row(fs_company_type_id, data)
{
	var fs_dir_state_company = "fs_dir_state_company_id_" + data['id'];
	var next_tr = "";	// insert before tr except "others" will be insert at last row.
	var placeholder= "";
	var input_name = "";
	var input_country_id = "";
	var input_dir_begin_no_share = "";
	var input_dir_end_no_share = "";
	var input_deem_begin_no_share = "";
	var input_deem_end_no_share = "";
	var input_dir_state_id = "";

	var input_director = "";

	// console.log(fs_company_type_id == 1);

	if(data != '')
	{
		var dir_state_id 				  = data['id'];
		var company_name_val 			  = data['company_name'];
		var input_dir_begin_no_share_val  = data['dir_begin_fy_no_of_share'];
		var input_dir_end_no_share_val 	  = data['dir_end_fy_no_of_share'];
		var input_deem_begin_no_share_val = data['deem_begin_fy_no_of_share'];
		var input_deem_end_no_share_val   = data['deem_end_fy_no_of_share'];
	}

	input_director = "company_" + index;

	// console.log(index);

	if(fs_company_type_id == 1)
	{	
		// fs_dir_state_company_id = 'ultimate';
		// tr = $('#ultimate_input');
		next_tr = $('#intermediate_input');
		placeholder = "Ultimate Holding Company Name";
		input_dir_state_id = 'ultimate_id['+ index + ']';
		input_name = 'ultimate_company['+ index +']';
		input_country_id = 'ultimate_country_id['+ index +']';
		input_dir_begin_no_share = 'ultimate_input_dir_begin_no_share['+ index +']';
		input_dir_end_no_share = 'ultimate_input_dir_end_no_share['+ index +']';
		input_deem_begin_no_share = 'ultimate_input_deem_begin_no_share['+ index +']';
		input_deem_end_no_share = 'ultimate_input_deem_end_no_share['+ index +']';
	}
	else if(fs_company_type_id == 2)
	{
		// company_type = "intermediate";
		// tr = $('#intermediate_input');
		next_tr = $('#immediate_input');
		placeholder = "Intermediate Holding Company Name";
		input_dir_state_id = 'intermediate_id['+ index +']';
		input_name = 'intermediate_company['+ index +']';
		input_country_id = 'intermediate_country_id['+ index +']';
		input_dir_begin_no_share = 'intermediate_input_dir_begin_no_share['+ index +']';
		input_dir_end_no_share = 'intermediate_input_dir_end_no_share['+ index +']';
		input_deem_begin_no_share = 'intermediate_input_deem_begin_no_share['+ index +']';
		input_deem_end_no_share = 'intermediate_input_deem_end_no_share['+ index +']';
	}
	else if(fs_company_type_id == 3)
	{
		// company_type = "immediate";
		// tr = $('#immediate_input');
		next_tr = $('#corporate_input');
		placeholder = "Immediate Holding Company Name";
		input_dir_state_id = 'immediate_id['+ index +']';
		input_name = 'immediate_company['+ index +']';
		input_country_id = 'immediate_country_id['+ index +']';
		input_dir_begin_no_share = 'immediate_input_dir_begin_no_share['+ index +']';
		input_dir_end_no_share = 'immediate_input_dir_end_no_share['+ index +']';
		input_deem_begin_no_share = 'immediate_input_deem_begin_no_share['+ index +']';
		input_deem_end_no_share = 'immediate_input_deem_end_no_share['+ index +']';
	}
	else if(fs_company_type_id == 4)
	{
		// company_type = "corporate";
		// tr = $('#corporate_input');
		next_tr = $('#others_input');
		placeholder = "Corporate Shareholders Name";
		input_dir_state_id = 'corporate_id['+ index +']';
		input_name = 'corporate_company['+ index +']';
		input_country_id = 'corporate_country_id['+ index +']';
		input_dir_begin_no_share = 'corporate_input_dir_begin_no_share['+ index +']';
		input_dir_end_no_share = 'corporate_input_dir_end_no_share['+ index +']';
		input_deem_begin_no_share = 'corporate_input_deem_begin_no_share['+ index +']';
		input_deem_end_no_share = 'corporate_input_deem_end_no_share['+ index +']';
	}
	else if(fs_company_type_id == 5)
	{
		// company_type = "others";
		// tr = $('#others_input');
		placeholder = "Other Company Name";
		input_dir_state_id = 'others_id['+ index +']';
		input_name = 'others_company['+ index +']';
		input_country_id = 'others_country_id['+ index +']';
		input_dir_begin_no_share = 'others_input_dir_begin_no_share['+ index +']';
		input_dir_end_no_share = 'others_input_dir_end_no_share['+ index +']';
		input_deem_begin_no_share = 'others_input_deem_begin_no_share['+ index +']';
		input_deem_end_no_share = 'others_input_deem_end_no_share['+ index +']';
	}

	var director_data_template = '';

	if(data != '') // if got data retreived from database
	{
		var template = '<tr class="'+ fs_dir_state_company +'">' + 
							'<td style="width: 5%; text-align: center;">' + 
								'<input type="hidden" class="company_index" value="'+ index +'">' + 
								'<a class="delete_company" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold; cursor: pointer; color:#c61156;" onclick="delete_company(this)">' + 
									'<i class="fa fa-minus-circle" style="font-size:16px;"></i>' + 
								'</a>' + 
							'</td>' + 
							'<td style="width: 25%;" colspan="2"><input type="hidden" class="company_id" name="'+ input_dir_state_id +'" value="'+ dir_state_id +'"><input type="text" class="form-control" placeholder="'+ placeholder +'" name="'+ input_name +'" value="'+ company_name_val +'"/></td>' + 
							'<td style="width: 15%;"><select style="width:100%" name="' + input_country_id + '" class="fs_dropdown company_country_id_' + index + '"></select></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_dir_begin_no_share +'" value="'+ input_dir_begin_no_share_val +'"/></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_dir_end_no_share +'" value="'+ input_dir_end_no_share_val +'"/></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_deem_begin_no_share +'" value="'+ input_deem_begin_no_share_val +'"/></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_deem_end_no_share +'" value="'+ input_deem_end_no_share_val +'"/></td>' + 
						'</tr>';
		var director_template = '<tr class="'+ input_director +'">' + 
									'<td></td>' + 
									'<td bgcolor="#CD658C" colspan="2">' + 
										'<a class="add_director amber" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold; cursor: pointer;" onclick="add_dir_row(this, ' + fs_company_type_id + ')">' + 
											'<i class="fa fa-plus-circle" style="color:white; font-size:16px;"></i>' + 
										'</a><strong style="color:white;">     Add Director</strong></td></tr>';
		

		if(data['id'] != '')
		{
			for(j = 0; j < fs_dir_statement_director.length; j++)
			{
				if(fs_dir_statement_director[j]['fs_dir_statement_company_id'] == data['id'])
				{
					director_data_template = director_data_template + 
											'<tr>' + 
												'<td>' + 
													'<input type="hidden" class="fs_director_id" name="fs_director_id['+ director_index +']" value="'+ fs_dir_statement_director[j]['id'] +'">' + 
													'<input type="hidden" class="company_director" name="company_director['+ director_index +']" value="'+ index +'">' + 
												'</td>' + 
												'<td style="text-align: center;">' + 
													'<a data-toggle="tooltip" data-trigger="hover" style="color:#CD658C; font-weight:bold; cursor: pointer;" onclick="delete_director(this)"><i class="fa fa-minus-circle" style="font-size:16px;"></i></a>' + 
												'</td>' + 
												'<td><input type="text" class="form-control" name="fs_director_name['+ director_index +']" placeholder="Director name" value="'+ fs_dir_statement_director[j]['director_name'] +'"></td>' + 
												'<td></td>' + 
												'<td><input type="text" class="form-control" style="text-align:right;" name="fs_dir_begin_fy_nos['+ director_index +']" value="' + fs_dir_statement_director[j]['dir_begin_fy_no_of_share'] + '" /></td>' + 
												'<td><input type="text" class="form-control" style="text-align:right;" name="fs_dir_end_fy_nos['+ director_index +']" value="' + fs_dir_statement_director[j]['dir_end_fy_no_of_share'] + '" /></td>' + 
												'<td><input type="text" class="form-control" style="text-align:right;" name="fs_deem_begin_fy_nos['+ director_index +']" value="' + fs_dir_statement_director[j]['deem_begin_fy_no_of_share'] + '" /></td>' + 
												'<td><input type="text" class="form-control" style="text-align:right;" name="fs_deem_end_fy_nos['+ director_index +']" value="' + fs_dir_statement_director[j]['deem_end_fy_no_of_share'] + '" /></td>' + 
											'</tr>';
				}

				director_index++;
			}
		}
	}
	else{
		var template = '<tr class="'+ fs_dir_state_company +'">' + 
							'<td style="width: 5%; text-align: center;"><input type="hidden" class="company_index" value="'+ index +'">' + 
								'<a class="delete_company" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold; cursor: pointer; color:#c61156;" onclick="delete_company(this)">' + 
									'<i class="fa fa-minus-circle" style="font-size:16px;"></i>' + 
								'</a>' + 
							'</td>' + 
							'<td style="width: 25%;" colspan="2"><input type="hidden" class="company_id" name="'+ input_dir_state_id +'" value=""><input type="text" class="form-control" placeholder="'+ placeholder +'" name="'+ input_name +'" /></td>' + 
							'<td style="width: 15%;"><select style="width:100%" name="' + input_country_id + '" class="fs_dropdown company_country_id_' + index + '"></select></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_dir_begin_no_share +'"/></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_dir_end_no_share +'"/></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_deem_begin_no_share +'"/></td>' + 
							// '<td style="width: 12.5%; text-align: center;"><input type="text" class="form-control" placeholder="No. of Shares" style="text-align: right;" name="'+ input_deem_end_no_share +'"/>' + 
							'</td>' + 
						'</tr>';
		var director_template = '<tr class="'+ input_director +'">' + 
									'<td></td>' + 
									'<td bgcolor="#CD658C" colspan="2">' + 
										'<a class="add_director amber" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold; cursor: pointer;" onclick="add_dir_row(this, ' + fs_company_type_id + ')">' + 
											'<i class="fa fa-plus-circle" style="color:white; font-size:16px;"></i>' + 
										'</a> <strong style="color:white;">     Add Director</strong>' + 
									'</td>' + 
								'</tr>';

		// $(template + director_template + director_data_template).insertAfter(tr);
	}

	// $(template + director_template + director_data_template).insertAfter(next_tr);
	if(fs_company_type_id != 5)
	{
		next_tr.before(template + director_template + director_data_template);
	}
	else
	{
		$(template + director_template + director_data_template).insertAfter($('#fs_director_statement_div .table tr:last'));
	}

	if(data['id'] != '')
	{
		add_country_list_dropdown(data['country_id'], $('.company_country_id_' + index));
	}
	else
	{
		add_country_list_dropdown('', $('.company_country_id_' + index));
	}

	index++;

	// console.log($('.company_' + index));
}

function add_country_list_dropdown(country_id, element)
{
	// insert country
	for(z = 0; z < country_list.length; z++)
	{
		if(country_list[z]['id'] == country_id)
		{
			element.append('<option value="' + country_list[z]['id'] + '" selected>' + country_list[z]['name'] + '</option>');
		}
		else
		{
			if(country_list[z]['id'] == 192) // singapore as default
			{
				element.append('<option value="' + country_list[z]['id'] + '" selected>' + country_list[z]['name'] + '</option>');
			}
			else
			{
				element.append('<option value="' + country_list[z]['id'] + '">' + country_list[z]['name'] + '</option>');
			}
			
		}
	}
}

function add_dir_row_init(fs_dir_statement_company_id)
{
	for(j = 0; j < fs_dir_statement_director.length; j++)
	{
		if(fs_dir_statement_director[j]['fs_dir_statement_company_id'] == fs_dir_statement_company_id)
		{
			add_dir_row_init(fs_dir_statement_director[j]);
		}
	}
}

function add_dir_row(element, fs_company_type_id)
{
	var tr = $(element).parent().parent();

	var index = tr.attr('class').replace("company_", "");
	var template = '<tr>' + 
						'<td></td>' + 
						'<td style="text-align: center; width:5%;"><a data-toggle="tooltip" data-trigger="hover" style="color: #CD658C; font-weight:bold; cursor: pointer;" onclick="delete_director(this)"><i class="fa fa-minus-circle" style="font-size:16px;"></i></a></td>' + 
						'<td><input type="hidden" class="fs_director_id" name="fs_director_id['+ director_index +']" value=""><input type="hidden" class="company_director" name="company_director['+ director_index +']" value="'+ index +'"><input type="text" class="form-control" name="fs_director_name['+ director_index +']" placeholder="Director name"></td>' + 
						'<td></td>' + 
						'<td><input type="text" class="form-control" style="text-align:right;" name="fs_dir_begin_fy_nos['+ director_index +']" value="0" /></td>' + 
						'<td><input type="text" class="form-control" style="text-align:right;" name="fs_dir_end_fy_nos['+ director_index +']" value="0" /></td>' + 
						'<td><input type="text" class="form-control" style="text-align:right;" name="fs_deem_begin_fy_nos['+ director_index +']" value="0" /></td>' + 
						'<td><input type="text" class="form-control" style="text-align:right;" name="fs_deem_end_fy_nos['+ director_index +']" value="0" /></td>' + 
					'</tr>';

	// $(template).insertAfter(tr);

	// console.log(tr);
	if(!tr.is(":last-child"))	// if this tr is not last tr
	{
		var targeted_tr = get_next_tr(tr);
		var hasClass_below_tr = false;	// to rrcord whether is has class in next tr or not until all tr loop to the end.

		// console.log(targeted_tr.not('[class]').length);

		if(!targeted_tr.not('[class]').length)	// if not no have class (mean has class atrribute) for the next row of current tr
		{
			hasClass_below_tr = true;
		}
		else
		{
			while (targeted_tr.not('[class]').length && !targeted_tr.is(":last-child"))	// find if the element don't have class, then move to next tr until the next tr has class then stop
			{
				targeted_tr = get_next_tr(targeted_tr);

				if(!targeted_tr.not('[class]').length && !targeted_tr.is(":last-child"))
				{
					hasClass_below_tr = true;
				}
			};
		}
		

		console.log(targeted_tr);

		if(hasClass_below_tr)
		{
			console.log('1');
			targeted_tr.before(template);	// put before the next tr that has class.
		}
		else
		{
			console.log('2');
			$(template).insertAfter(targeted_tr);	// put in after last tr
		}
	}
	else
	{
		console.log('3');
		$(template).insertAfter(tr);	// put in after last tr
	}

	director_index++;
}

function get_next_tr(tr)
{
	var target = tr.closest('tr').next('tr');
	// console.log($(element).closest('tr').next('tr'));

	return target;
}

function delete_company(element)
{
	var tr = $(element).parent().parent();
	var deleted_company_index = tr.find('.company_index').val();
	var deleted_company_id = tr.attr('class').replace("fs_dir_state_company_id_", "");
	// console.log(deleted_company_id != "undefined");

	if(deleted_company_id != "undefined")
	{
		arr_deleted_company.push(deleted_company_id);
	}

	tr.remove();

	// delete + Add Director under the company
	$('.company_' + deleted_company_index).remove();

	// remove director under the company
	$('.company_director').each(function(i, element){

		if($(element).val() == deleted_company_index)
		{
			var tr_director_under_this_company = $(element).parent().parent();

			tr_director_under_this_company.remove();
		}
	});

	// console.log(arr_deleted_company);
}

function delete_director(element)
{
	var tr = $(element).parent().parent();
	var deleted_director_id = tr.find('.fs_director_id').val();

	arr_deleted_directors.push(deleted_director_id);
	tr.remove();

	// console.log(arr_deleted_directors);
}

// $(document).on('click',"#submit_fs_director_statement",function(e){
//     $('#loadingmessage').show();

//     var fs_company_info_id = $('input[name=fs_company_info_id]').val();

//     $.ajax({ //Upload common input
//         url: "financial_statement/submit_director_statement",
//         type: "POST",
//         data: $('form#form_fs_director_statement').serialize() + '&fs_company_info_id=' + fs_company_info_id + '&arr_deleted_company=' + arr_deleted_company + '&arr_deleted_directors=' + arr_deleted_directors,
//         dataType: 'json',
//         success: function (response,data) {
//         	$('#loadingmessage').show();

//         	if(response['status'] === true)
//         	{
//         		window.location.href = "financial_statement/create/" + fs_company_info_id;
// 			    toastr.success("The data is saved to database.", "Successfully saved");
//         	}
//         	else
//         	{
//         		toastr.error("Something went wrong. Please try again later.", "");
//         	}

//             $('#loadingmessage').hide();
//         }
//     });
// });
