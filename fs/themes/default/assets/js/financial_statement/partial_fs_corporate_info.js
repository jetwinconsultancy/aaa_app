var fs_company_info_id = $("#fs_company_info_id").val();
var company_code 	   = $("#company_code").val();

$("#fs_company_info_btn").click(function() 
{
 	$("#fs_company_info_modal").modal("show");

 	load_fs_company_info();             
});

$("#fs_director_interest_btn").click(function() 
{
 	$("#fs_director_interest_modal").modal("show");    

 	load_fs_director_interest();         
});

$("#fs_firm_report_btn").click(function() 
{
 	$("#fs_firm_report_modal").modal("show");   

 	load_firm_report();          
});

function load_fs_company_info()
{
	$.ajax({ //Upload common input
	  	url: "financial_statement/partial_company_particular",
	  	type: "POST",
	  	data: {fs_company_info_id: fs_company_info_id, company_code:company_code},
	    dataType: 'html',
	    success: function (response,data) 
	    {
	    	$("#fs_company_info_modal .modal-body").html(response);
		}
    });
}

function load_fs_director_interest()
{
	$.ajax({ //Upload common input
	  	url: "financial_statement/partial_director_interest_share",
	  	type: "POST",
	  	data: {fs_company_info_id: fs_company_info_id},
	    dataType: 'html',
	    success: function (response,data) {
	    	$("#fs_director_interest_modal .modal-body").html(response);
	    	// insert_label_opinion(1, 'acb');
		}
    });
}

function load_firm_report()
{
	$.ajax({ //Upload common input
	  	url: "financial_statement/partial_auditor_report",
	  	type: "POST",
	  	data: {fs_company_info_id: fs_company_info_id},
	    dataType: 'html',
	    success: function (response,data) {
	    	$("#fs_firm_report_modal .modal-body").html(response);
	    	// insert_label_opinion(1, 'acb');
		}
    });
}

