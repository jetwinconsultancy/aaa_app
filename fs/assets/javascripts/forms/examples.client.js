(function( $ ) {

	'use strict';

    function removeCommas(str) {
	    while (str.search(",") >= 0) {
	        str = (str + "").replace(',', '');
	    }
	    return str;
	};

	$('#wFS_document').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onTabClick: function( tab, navigation, index) 
		{
			// alert('on tab click disabled');
			// return true;
			if($('#fs_company_info_id').val() == 0 && tab != 0)
			{
				return false;
			}
		},
		onNext: function( tab, navigation, index, newindex ) 
		{
			// console.log(tab);
			// var validated = $('#w5 form').valid();
			// if( !validated ) {
			// 	$w5validator.focusInvalid();
			// 	return false;
			// }
		},
		onTabChange: function( tab, navigation, index, newindex ) 
		{
			// console.log($('#wFS_document .active > a').attr('href'));
			var fs_company_info_id = $("#fs_company_info_id").val();
			var company_code 	   = $("#company_code").val();
			var client_id 		   = $('#client_id').val();

			if($('#wFS_document .active > a').attr('href') == "#FS_corporate_information")
			{
				$.ajax({ //Upload common input
				  	url: "financial_statement/partial_corporate_information",
				  	type: "POST",
				  	data: {fs_company_info_id: fs_company_info_id, company_code:company_code},
				    dataType: 'html',
				    success: function (response,data) 
				    {
				    	$("#FS_corporate_information").html(response);
					}
			    });
			}
			else if($('#wFS_document .active > a').attr('href') == "#FS_financial_statement")
			{
				$.ajax({ //Upload common input
				  	url: "financial_statement/partial_document_setup",
				  	type: "POST",
				  	data: {fs_company_info_id: fs_company_info_id, firm_id: firm_id, client_id: client_id},
				    dataType: 'html',
				    success: function (response,data) {
				    	$("#FS_financial_statement").html(response);
				    	// insert_label_opinion(1, 'acb');
					}
			    });
			}
			else if($('#wFS_document .active > a').attr('href') == "#FS_generate_report")
			{
				$.ajax({ //Upload common input
				  	url: "financial_statement/document_checklist",
				  	type: "POST",
				  	data: {fs_company_info_id: fs_company_info_id},
				    dataType: 'html',
				    success: function (response,data) {
				    	// console.log(JSON.parse(response));
				    	var result = JSON.parse(response);

				    	$("#fs_doc_checklist_list").html(result['tr_template']);

				    	if(result['generate_report_approved'])
				    	{
				    		$('#fs_doc_checklist').hide();
				    		$('#fs_generate_report_link').show();
				    	}
				    	else
				    	{
				    		$('#fs_doc_checklist').show();
				    		$('#fs_generate_report_link').hide();

				    	}
				    	// insert_label_opinion(1, 'acb');
					}
			    });

				// console.log($('#fs_statement_checklist'));

			}

			// if($('#wFS_document .active > a').attr('href') == "#FS_setting")
			// {
			// 	$('#fs_setting_div').remove();

			// 	$.ajax({ //Upload common input
			// 	  	url: "financial_statement/partial_company_particular",
			// 	  	type: "POST",
			// 	  	data: {fs_company_info_id: fs_company_info_id, company_code:company_code},
			// 	    dataType: 'html',
			// 	    success: function (response,data) 
			// 	    {
			// 	    	$("#FS_setting").html(response);
			// 		}
			//     });
			// }
			// else if($('#wFS_document .active > a').attr('href') == "#FS_director_statement")
			// {
			// 	// console.log($('#fs_director_statement_div'));
			// 	$('#FS_director_statement').empty();
			// 	// console.log($("script[src='themes/default/assets/js/financial_statement/partial_director_interest_share.js']"));
			// 	$("script[src='themes/default/assets/js/financial_statement/partial_director_interest_share.js']").remove();

			// 	$.ajax({ //Upload common input
			// 	  	url: "financial_statement/partial_director_interest_share",
			// 	  	type: "POST",
			// 	  	data: {fs_company_info_id: fs_company_info_id},
			// 	    dataType: 'html',
			// 	    success: function (response,data) {
			// 	    	$("#FS_director_statement").html(response);
			// 	    	// insert_label_opinion(1, 'acb');
			// 		}
			//     });
			// }
			// else if($('#wFS_document .active > a').attr('href') == "#FS_indepent_aud_report")
			// {
			// 	$.ajax({ //Upload common input
			// 	  	url: "financial_statement/partial_auditor_report",
			// 	  	type: "POST",
			// 	  	data: {fs_company_info_id: fs_company_info_id},
			// 	    dataType: 'html',
			// 	    success: function (response,data) {
			// 	    	$("#FS_indepent_aud_report").html(response);
			// 	    	// insert_label_opinion(1, 'acb');
			// 		}
			//     });
			// }
			// else if($('#wFS_document .active > a').attr('href') == "#FS_doc_setup")
			// {
			// 	$.ajax({ //Upload common input
			// 	  	url: "financial_statement/partial_document_setup",
			// 	  	type: "POST",
			// 	  	data: {fs_company_info_id: fs_company_info_id},
			// 	    dataType: 'html',
			// 	    success: function (response,data) {
			// 	    	$("#FS_doc_setup").html(response);
			// 	    	// insert_label_opinion(1, 'acb');
			// 		}
			//     });
			// }
			// else if($('#wFS_document .active > a').attr('href') == "#FS_ntfs")
			// {
			// 	$.ajax({ //Upload common input
			// 	  	url: "financial_statement/partial_ntfs_layout",
			// 	  	type: "POST",
			// 	  	data: {fs_company_info_id: fs_company_info_id},
			// 	    dataType: 'html',
			// 	    success: function (response,data) {
			// 	    	$("#FS_ntfs").html(response);
			// 		}
			//     });
			// }
		},
		onTabShow: function( tab, navigation, index ) 
		{
			// console.log(index);

			// $('#wFS_document').bootstrapWizard('1');

			// if(index == 2)
			// {
			// 	$('#wFS_document').find("a[href*='FS_corporate_information']").trigger('click');
			// }
			// var $total = navigation.find('li').length;
			// var $current = index + 1;
			// var $percent = ( $current / $total ) * 100;
			// $('#w5').find('.progress-bar').css({ 'width': $percent + '%' });
		}
	});
}).apply( this, [ jQuery ]);
