function display_on_collapse(layouts_data)
{
	console.log(layouts_data);

	var layout = "";

	var counter = 0;
	var main_index 	= 0;
	var sub_index 	= 0;
	var roman_index = 0;

	var data_parent_id = "accordion";
	var href_collapse_id = "collapse";
	var panel_body_content = "panel_body_content";

	var sub_parent_section_no = '';

	layouts_data.forEach(function(data)
	{
		var parent 	   = data['parent'];
		var section_no = data['section_no'];

		// console.log(data['is_shown']);

		if(data['is_shown'] == 1)
		{
			if(parent == 0)
			{
				main_index++;
				roman_index = 0;
				sub_index = 0;
				sub_parent_section_no = '';

				/* create layout and show with numbering and title first */
				var layout_template = create_collapse_layout(data, counter, main_index + '.', main_index, '<strong>' + data['section_name'] + '</strong>', 1, 0);
				$('.layouts').append(layout_template);

				// retrieve and display layout
				$.ajax({
				  	url: "fs_notes/retrieve_ntfs_layout",
				  	type: "POST",
				  	data: {fs_ntfs_layout_template_default_id: data['id'], fs_company_info_id: $("#fs_company_info_id").val()},
				  	async: false,
				    dataType: 'html',
				    success: function (response,data) {

				    	// var layout_content = JSON.parse(response);
						// $('#content' + main_index).append(layout_content[0]['layout_content']);
						$('#content' + main_index).append(response);
					}
			    });
				// END OF create layout and show with numbering and title first 
			}
			else	// display sub 
			{
				// console.log(data);
				if(data['is_roman_section'] != '1')
				{
					if(sub_parent_section_no != data['section_no'])
					{
						sub_index++;
						roman_index = 0;

						var sub_layout_template = create_collapse_layout(data, counter, main_index + '.' + sub_index, main_index + '_' + sub_index, '<strong>' + data['section_name'] + '</strong>', 0, 1);
						$('#panel_body_content' + main_index).append(sub_layout_template);

						$.ajax({ // Upload common input
						  	url: "fs_notes/retrieve_ntfs_layout",
						  	type: "POST",
						  	data: {fs_ntfs_layout_template_default_id: data['id'], fs_company_info_id: $("#fs_company_info_id").val()},
						  	async: false,
						    dataType: 'html',
						    success: function (response_child, data) 
						    {
						    	// var layout_content = JSON.parse(response_child);

								// $('#content'+ main_index + '_' + sub_index).append(layout_content[0]['layout_content']);
								$('#content'+ main_index + '_' + sub_index).append(response_child);
							}
					    });

					    sub_parent_section_no = data['section_no'];
					}
					else
					{
						// var sub_layout_template = create_collapse_layout(data, main_index + '.' + sub_index, main_index + '_' + sub_index, '<strong>' + data['section_name'] + '</strong>');
						// $('#panel_body_content' + main_index).append(sub_layout_template);

						$.ajax({ //Upload common input
						  	url: "fs_notes/retrieve_ntfs_layout",
						  	type: "POST",
						  	data: {fs_ntfs_layout_template_default_id: data['id'], fs_company_info_id: $("#fs_company_info_id").val()},
						  	async: false,
						    dataType: 'html',
						    success: function (response_child, data) {
						    	// var layout_content = JSON.parse(response_child);

						    	// $('#content'+ main_index + '_' + sub_index + '_' + roman_index).after(layout_content[0]['layout_content']);
								// $('#content'+ main_index + '_' + sub_index).append(layout_content[0]['layout_content']);

								$('#accordion'+ main_index + '_' + sub_index + '_' + roman_index).after(response_child);

								// console.log($('#accordion'+ main_index + '_' + sub_index + '_' + roman_index).after(layout_content[0]['layout_content']));
							}
					    });
					}
				}
				else	// display sub of sub (roman numbering)
				{
					// console.log(data['id'] + '\n');

					// if(data['id'] > 58)
					// {
					// 	console.log(data);
					// 	console.log(parseFloat(data['parent']));
					// }

					roman_index++;

					if(parseFloat(data['parent']) > 3)	// if note after no.3 got sub
					{
						var sub_layout_template = create_collapse_layout(data, counter, '(' + romanize(roman_index).toLowerCase() + ')', main_index + '_' + roman_index, data['section_name'], 0, 0);
		    			$('#panel_body_content' + main_index).append(sub_layout_template);
		    			// console.log('#panel_body_content' + main_index + '_' + sub_index);

		    			$.ajax({ //Upload common input
						  	url: "fs_notes/retrieve_ntfs_layout",
						  	type: "POST",
						  	data: {fs_ntfs_layout_template_default_id: data['id'], fs_company_info_id: $("#fs_company_info_id").val()},
						  	async: false,
						    dataType: 'html',
						    success: function (response_roman,data) {
						    	// var layout_content = JSON.parse(response_roman);

								$('#content'+ main_index + '_' + roman_index).append(response_roman);
							}
					    });
					}
					else
					{
						var sub_layout_template = create_collapse_layout(data, counter, '<em>(' + romanize(roman_index).toLowerCase() + ')</em>', main_index + '_' + sub_index + '_' + roman_index, '<em>' + data['section_name'] + '</em>', 0, 0);
		    			$('#panel_body_content' + main_index + '_' + sub_index).append(sub_layout_template);
		    			// console.log('#panel_body_content' + main_index + '_' + sub_index);

		    			$.ajax({ //Upload common input
						  	url: "fs_notes/retrieve_ntfs_layout",
						  	type: "POST",
						  	data: {fs_ntfs_layout_template_default_id: data['id'], fs_company_info_id: $("#fs_company_info_id").val()},
						  	async: false,
						    dataType: 'html',
						    success: function (response_roman,data) {
						    	// var layout_content = JSON.parse(response_roman);

								$('#content'+ main_index + '_' + sub_index + '_' + roman_index).append(response_roman);
							}
					    });
					}
					
				}
				
			}

			counter++;
		}
	});

    return { 'result' : true, 'main_index' : main_index, 'sub_index': sub_index, 'roman_index': roman_index };
}

function create_collapse_layout(data, counter, display_index, inner_index, title, is_main_category, is_sub_category)
{
	var add_dnd_class  = "";	// for dnd use
	var portlet 	   = "";	// for dnd use
	var portlet_header = "";	// for dnd use
	var sub_category   = "";
	var input_checkbox = "";
	var is_main	   	   = 0;

	var show_hidden_input_main = "";
	var parent_index = "";
	var is_roman_hidden = "";
	var category = " romanji_section";

	var is_checked = "";	// use to display "checked" in element

	var account_code_display = data['account_code'];

	if(data['account_code'] == null)
	{
		account_code_display = '-';
	}
	else
	{
		title = '<strong>' + data['description'] + '</strong>';
	}

	if(is_main_category)
	{
		add_dnd_class  = " dnd";	// to enable dnd inside this div
		portlet_header = " main_portlet-header";
		category   	   = " main_category";

		parent_index   = ' <span class="parent_index" style="display:none;">' +  inner_index + '</span>';

		if(data['is_checked'] == '1')
		{
			is_checked = "checked";
		}
		
		is_main = '<span class="is_main" style="display:none;">1</span>';
		input_checkbox = '<input type="hidden" class="checkbox_lyt_id" name="checkbox_lyt_id[' + counter + ']" value="' + data['id'] + '">' + 
						'<input type="checkbox" class="show_sub_category" name="lyt_checkbox[' + counter + ']" onclick="disable_click_note(this)" ' + is_checked + '>';
	}

	if(is_sub_category)
	{
		portlet 	   = " portlet";			
		portlet_header = " portlet-header";		
		category   	   = " sub_category";

		if(data['is_checked'] == '1')
		{
			is_checked = "checked";
		}

		if(data['is_roman_section'] !== '')
		{
			is_roman_hidden = '<span class="is_roman" style="display:none;">0</span>';
		}

		is_main = '<span class="is_main" style="display:none;">0</span>';
		input_checkbox = '<input type="hidden" class="checkbox_lyt_id" name="checkbox_lyt_id[' + counter + ']" value="' + data['id'] + '">' + 
						'<input type="checkbox" class="show_sub_category" name="lyt_checkbox[' + counter + ']" onclick="disable_click_note(this)" ' + is_checked + '>';
	}

	if(data['is_roman_section'] == '1')
	{
		if(data['is_checked'] == '1')
		{
			is_checked = "checked";
		}

		is_main = '<span class="is_main" style="display:none;">0</span>';
		input_checkbox = '<input type="hidden" class="checkbox_lyt_id" name="checkbox_lyt_id[' + counter + ']" value="' + data['id'] + '">' + 
						'<input type="checkbox" class="show_sub_category" name="lyt_checkbox[' + counter + ']" onclick="disable_click_note(this)" ' + is_checked + '>';
	}

	// console.log(data['section_no']);

	return '<div class="panel-group' + category + '" id="accordion' + inner_index + '">' + 
				'<div class="panel panel-default' + portlet + '">' + 
					'<div class="panel-heading' + portlet_header + '">' + 
						
						show_hidden_input_main + 

						'<h4 class="panel-title">' + 
							'<div class="checkbox_show_sub_category" style="width: 3%; display: inline-block; text-align:center;">' + 
								input_checkbox + 
								is_main +
							'</div>' + 
							'<a class="note_section_content" style="width:97%; display:inline-block" data-toggle="collapse" data-parent="#accordion' + inner_index + '" href="#collapse' + inner_index + '" onclick="load_content(this)">' + 
								'<input type="hidden" name="lyt_id[' + counter + ']" value="' + data['id'] + '">' + 
								is_roman_hidden +
								'<input type="hidden" class="set_section_no" name="lyt_section_no[' + counter + ']" value="' + data['section_no'] + '">' + 
				                '<div style="width: 3%; display: inline-block; text-align: center;" class="display_index">' + display_index + '</div>' + 
				                '<div style="width: 5%; display: inline-block; text-align: center;" class="">' + account_code_display + '</div>' +
				                '<div style="display: inline-block;">' + title + '</div>' + 
					   		'</a>' + 
				        '</h4>' + 

			    	'</div>' + 
			        '<div id="collapse' + inner_index + '" class="panel-collapse collapse">' + 
				        '<div id="panel_body_content' + inner_index + '" class="panel-body' + add_dnd_class + '">' + 
				        	parent_index +
				        	// '<div style="width: 5%; display: inline-block;"></div>' + 
				            '<div style="width: 11%; display: inline-block;"></div>' + 
			                '<div id="content' + inner_index + '" class="NTA_content" style="width: 86%; display: inline-block; text-align:justify;"></div>' + 
		                '</div>' + 
		            '</div>' + 
		        '</div>' + 
		    '</div>';
}

function romanize (num) 
{
	if (!+num)
		return false;
	var	digits = String(+num).split(""),
		key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
		       "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
		       "","I","II","III","IV","V","VI","VII","VIII","IX"],
		roman = "",
		i = 3;
	while (i--)
		roman = (key[+digits.pop() + (i * 10)] || "") + roman;
	return Array(+digits.join("") + 1).join("M") + roman;
}

function check_initial_checkbox()
{
    var checkbox_element_lists = $('.show_sub_category');

    for(i = 0; i < checkbox_element_lists.length; i++)
    {
    	var element = checkbox_element_lists[i];

    	disable_click_note(element);
    }

    // console.log(checkbox_element_lists);
}

function disable_click_note(element)	// disable div and re-arrange index
{
	var selector = $(element).parent().parent().find('.note_section_content');
	var collapse = $(element).parent().parent().parent().parent().find(".collapse");

	var sub_category_list = $(element).parent().parent().parent().parent().parent().parent().find('.sub_category');
	var main_category_list = $(element).parent().parent().parent().parent().parent().parent().find('.main_category');

	if(!$(element).is(":checked"))
	{
		selector.css({"color": "lightgrey", "pointer-events": "none" });

		if(collapse.hasClass('in'))
		{
		   collapse.collapse("hide");
		}
	}
	else
	{
		selector.css({"color": "", "pointer-events": "" });
	}

	var is_main = $(element).parent().find('.is_main').text();

	if(is_main === '1')
	{
		get_sub_list_rearrange_index(main_category_list, 0);
	}
	else
	{
		get_sub_list_rearrange_index(sub_category_list, 1);
	}
	
}

function get_sub_list_rearrange_index(category_list, is_sub_category)	// rewrite index numbering for subcategory 
{
	if(is_sub_category)
	{
		var temp_main_index = 0;
		var main_index = 0;
		

		var index = 1;
		var counter = 1;

		for (y = 0; y < category_list.length; y++) 
	    {	
	    	main_index = $(category_list[y]).parent().find('.parent_index').text();

	    	if(temp_main_index != main_index)
	    	{
	    		index = 1;
	    		counter = 1;

	    		temp_main_index = main_index;
	    	}

	        var displayed_sub_index 	   = $(category_list[y]).find('.portlet > .portlet-header > .panel-title > .note_section_content > .display_index').text();
	        var checkbox_show_sub_category = $(category_list[y]).find('.portlet > .portlet-header > .panel-title > .checkbox_show_sub_category > .show_sub_category').is(":checked");
	        var is_roman = $(category_list[y]).find('.portlet > .portlet-header > .panel-title > .note_section_content > .is_roman').text();

	        if(checkbox_show_sub_category)	// display and rewrite index numbering.
	        {
	        	$(category_list[y]).find('.portlet > .portlet-header > .panel-title > .note_section_content > .display_index').text(main_index + '.' + index);
	        	$(category_list[y]).find('.portlet > .portlet-header > .panel-title > .note_section_content > .set_section_no').val(main_index + '.' + counter);

	        	index++;
	        	counter++;
	        }
	        else	// display '-' if checkbox is unticked.
	        {
	        	$(category_list[y]).find('.portlet > .portlet-header > .panel-title > .note_section_content > .display_index').text('-');
	        	$(category_list[y]).find('.portlet > .portlet-header > .panel-title > .note_section_content > .set_section_no').val(main_index + '.' + counter);

	        	if(is_roman == '0')
	        	{
	        		counter++;
	        	}
	        }
	        
	    }
	}
	else // for main category
	{
		var main_index = 1;
		var main_counter = 1;

		for (x = 0; x < category_list.length; x++) 
	    {
	    	// console.log($(category_list[x]).find('.panel-default > .main_portlet-header > .panel-title > .note_section_content > .display_index'));

	    	var checkbox_show_sub_category = $(category_list[x]).find('.panel-default > .main_portlet-header > .panel-title > .checkbox_show_sub_category > .show_sub_category').is(":checked");

	    	// console.log($(category_list[x]).find('.panel-default > .main_portlet-header > .panel-title > .checkbox_show_sub_category > .show_sub_category'));
	    	// console.log(' ');

			if(checkbox_show_sub_category)	// display and rewrite index numbering.
			{
				$(category_list[x]).find('.panel-default > .main_portlet-header > .panel-title > .note_section_content > .display_index').text(main_index + '.');
		    	$(category_list[x]).find('.panel-default > .main_portlet-header > .panel-title > .note_section_content > .set_section_no').val(main_counter + '.0');

		    	$(category_list[x]).find('.panel-default > .panel-collapse > .panel-body > .parent_index').text(main_index);	// update parent index so that child can get updated parent index

		    	// update numbering in sub
		    	get_sub_list_rearrange_index($(category_list[x]).find('.panel-default > .collapse > .dnd > .sub_category'), 1);

		    	main_index++;
			}
			else	// display '-' if checkbox is unticked.
			{
				// console.log(checkbox_show_sub_category);
				$(category_list[x]).find('.panel-default > .main_portlet-header > .panel-title > .note_section_content > .display_index').text('-');
	        	$(category_list[x]).find('.panel-default > .main_portlet-header > .panel-title > .note_section_content > .set_section_no').val(main_counter + '.0');

	        	// main_index++;
			}
	    	
			main_counter++;
	    	
	    }
	}
}

/* ------------------------------------------------------------ Submit part -------------------------------------------------------------------------- */
// $(document).on('click',"#submit_ntfs_layout",function(e)
$(document).on('click',"#save_nta",function(e)
{
    $('#loadingNTA').show();

    var is_checked = [];

    for(i = 0; i < $('.show_sub_category').length; i++)
    {
    	var element = $('.show_sub_category')[i];

    	if($(element).is(":checked"))
    	{
    		is_checked.push(1);
    	}
	    else
	    {
	    	is_checked.push(0);
	    }
    }

    // console.log(is_checked);

    $.ajax({ //Upload common input
      url: "fs_notes/save_ntfs_layout_template",
      type: "POST",
      data: $('#form_ntfs_layout').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&is_checked=' + is_checked,
      dataType: 'json',
      success: function (response,data) {

        console.log(response);

        $('#loadingNTA').hide();

        if(response['result'])
        {
          alert("NTA updated!");
        }
        else
        {
          alert("Something went wrong! Please try again later.");
        }
      }
    });
});

$(document).on('click',"#submit_group_not_consolidated",function(e)
{
	var snc_fs_investment_in_subsidiaries_id = $('#snc_fs_investment_in_subsidiaries_id').val();
	// console.log($('#snc_fs_investment_in_subsidiaries_id').val());

	$.ajax({
      url: "fs_notes/save_group_not_consolidated",
      type: "POST",
      data: $('#form_group_not_consolidated').serialize() + 
      		'&fs_company_info_id=' + $("#fs_company_info_id").val() + 
      		'&snc_fs_investment_in_subsidiaries_id=' + snc_fs_investment_in_subsidiaries_id,
      dataType: 'json',
      success: function (response,data) {

        console.log(response);
      }
    });
});

$(document).on('click',"#submit_ntfs_employee_benefits",function(e)
{
	// console.log($("textarea[name='eb_para_1']").val());
	// console.log($("textarea[name='eb_para_2']").val());


	$.ajax({
      url: "fs_notes/save_ntfs_employee_benefits",
      type: "POST",
      // data: $('#form_ntfs_employee_benefits').serialize() + 
      // 		'&fs_company_info_id=' + $("#fs_company_info_id").val(),
      data: 'fs_company_info_id=' + $("#fs_company_info_id").val() + 
      		'&eb_para=' + $("textarea[name='eb_para']").val(),
      dataType: 'json',
      success: function (response,data) {

        console.log(response);
      }
    });
});

// Group accounting
function update_wording_hc(element)
{
	var holding_company_name = $(element).val();

	$('.holding_company_name').val(holding_company_name);
}

function load_content(element)
{	
	// $(element).parent().parent().parent().find('.panel-collapse .panel-body .NTA_content').html('<span>Load content here.</span>');
	console.log("Expand / Collapse");
}