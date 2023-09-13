$("#QP_job_filter").select2();


$('#QP_year_filter').datepicker({
    autoclose: true,
    minViewMode: 2,
    format: 'yyyy'
});


$('#QP_partner_filter').multiselect({
	allSelectedText: 'All',
	enableFiltering: true,
	enableCaseInsensitiveFiltering: true,
	maxHeight: 200,
	includeSelectAllOption: true
});
$("#QP_partner_filter").multiselect('selectAll', false);
$("#QP_partner_filter").multiselect('updateButtonText');


$(document).ready(function ()
{
	for(var a=0; a<quarterly_list.length; a++)
	{
		var rowHtml = "<tr>";
		rowHtml += "<td>"+quarterly_list[a]['client_name']+"</td>";
		rowHtml += "<td class='text-left Q"+a+"J1'></td>";
		rowHtml += "<td class='text-left Q"+a+"J2'></td>";
		rowHtml += "<td class='text-left Q"+a+"J3'></td>";
		rowHtml += "<td class='text-left Q"+a+"J4'></td>";
		rowHtml += "<td class='text-left Q"+a+"J5'></td>";
		rowHtml += "<td class='text-left Q"+a+"J6'></td>";
		rowHtml += "<td class='text-left Q"+a+"J7'></td>";
		rowHtml += "<td class='text-left Q"+a+"J8'></td>";
		rowHtml += "<td class='text-left Q"+a+"J10'></td>";
		rowHtml += "<td class='text-left Q"+a+"J11'></td>";
		rowHtml += "<td class='text-left Q"+a+"J12'></td>";
		rowHtml += "<td class='text-left Q"+a+"J13'></td>";
		rowHtml += "<td class='text-left Q"+a+"J9'></td>";
		rowHtml += "</tr>";

		$(".quarterly_portfolio_table").append(rowHtml);

		for(var b=0; b<quarterly_list[a]['job_list'].length; b++)
		{
			if($('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html() == "")
			{
				$('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html(moment(quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ quarterly_list[a]['job_list'][b][4]+ ') ');
			}
			else
			{
				$('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ quarterly_list[a]['job_list'][b][4]+ ') ');
			}
	   	}
	}

	$(".datatable-quarterly_portfolio").DataTable({
		"order": [],
		"scrollY": "600px",
		"scrollX": true,
		"scrollCollapse": true,
		"pageLength": 50,
		"lengthChange": false,
		fixedColumns: {leftColumns: 1}
	});
});


$(document).on('click',".quarterly",function(){
	$("#loadingmessage").show();
	$("#datatable-quarterly_portfolio").DataTable().destroy();
	$(".datatable-quarterly_portfolio").DataTable({
		"order": [],
		"scrollY": "600px",
		"scrollX": true,
		"scrollCollapse": true,
		"pageLength": 50,
		"lengthChange": false,
		fixedColumns: {leftColumns: 1}
	});
	$("#loadingmessage").hide();
});


$("#generate_quarterly_portfolio_Excel").click(function(e) {
	$("#loadingmessage").show();

   	var table = $('#datatable-quarterly_portfolio').DataTable();
    table = table.data();

    var tableData = [];

    for(var a = 0; a<table.length; a++)
    {
    	tableData.push(table[a]);
    }

    $.ajax({
       type: "POST",
       url: "portfolio/generateQuarterlyPortfolioExcel",
       data: {'tableData' : tableData},
       success: function(response,data)
       {	
       		$("#loadingmessage").hide();
       		toastr.success('Excel File Generated', 'Successful');
       		window.open(
              response,
              '_blank' // <- This is what makes it open in a new window.
            );
       }
   	});

	e.preventDefault(); // avoid to execute the actual submit of the form.
});


$("#QP_partner_filter").change(function(e)
{
	$("#loadingmessage").show();
	quarterly_portfolio_filtering();
	e.preventDefault(); // avoid to execute the actual submit of the form.
});
$("#QP_job_filter").change(function(e)
{
	$("#loadingmessage").show();
	quarterly_portfolio_filtering();
	e.preventDefault(); // avoid to execute the actual submit of the form.
});
$("#QP_year_filter").change(function(e)
{
	$("#loadingmessage").show();
	quarterly_portfolio_filtering();
	e.preventDefault(); // avoid to execute the actual submit of the form.
});


function quarterly_portfolio_filtering()
{
	$("#datatable-quarterly_portfolio").DataTable().destroy();
	$('.quarterly_portfolio_table').empty();

	var partner = $("#QP_partner_filter").val();
	var job = $("#QP_job_filter").val();
	var jobName = $("#QP_job_filter option:selected").text();
	var year = $("#QP_year_filter").val();
	var list_string = JSON.stringify(quarterly_list);

	if((partner == '' || $("#QP_partner_filter :not(:selected)").length == 0) && job == '0' && year == '')
	{
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		var rowHtml = "<tr>";
   			   rowHtml += "<td>"+quarterly_list[a]['client_name']+"</td>";
   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
   			   rowHtml += "</tr>";

			$(".quarterly_portfolio_table").append(rowHtml);

    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
    		{
    			if($('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html() == "")
				{
					$('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html(moment(quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ quarterly_list[a]['job_list'][b][4]+ ') ');
				}
				else
				{
					$('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ quarterly_list[a]['job_list'][b][4]+ ') ');
				}
    		}
    	}
	}
	else if((partner != '' || $("#QP_partner_filter :not(:selected)").length != 0) && job == '0' && year == '')
	{
		var temp_quarterly_list = [];
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(quarterly_list[a]['job_list'][b][1]);

	    		for(var c=0; c<partner.length; c++)
        		{
		    		if((quarterly_list[a]['job_list'][b][5] == partner[c] || quarterly_list[a]['job_list'][b][7] == partner[c]))
		    		{
		    			temp_quarterly_list.push(quarterly_list[a]);
		    			break;
		    		}
		    	}
	    	}
    	}

		for(var a = 0; a<temp_quarterly_list.length; a++)
    	{
    		for(var c=0; c<partner.length; c++)
        	{
	    		if(JSON.stringify(temp_quarterly_list[a]['job_list']).includes(partner[c]))
	    		{
	    			var rowHtml = "<tr>";
		   			   rowHtml += "<td>"+temp_quarterly_list[a]['client_name']+"</td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
		   			   rowHtml += "</tr>";

					$(".quarterly_portfolio_table").append(rowHtml);

		    		for(var b = 0; b<temp_quarterly_list[a]['job_list'].length; b++)
		    		{
		    			if((temp_quarterly_list[a]['job_list'][b][5] == partner[c] || temp_quarterly_list[a]['job_list'][b][7] == partner[c]))
		    			{
		    				if($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() == "")
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html(moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
							else
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
		    			}
		    		}
	    		}
	    	}
    	}
	}
	else if((partner == '' || $("#QP_partner_filter :not(:selected)").length == 0) && job != '0' && year == '')
	{
		var temp_quarterly_list = [];
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(quarterly_list[a]['job_list'][b][1]);

	    		if(quarterly_list[a]['job_list'][b][0] == job)
	    		{
	    			temp_quarterly_list.push(quarterly_list[a]);
	    			break;
	    		}
	    	}
    	}

    	for(var a = 0; a<temp_quarterly_list.length; a++)
    	{
    		if(JSON.stringify(temp_quarterly_list[a]['job_list']).includes(job) && JSON.stringify(temp_quarterly_list[a]['job_list']).includes(jobName))
    		{
    			var rowHtml = "<tr>";
	   			   rowHtml += "<td>"+temp_quarterly_list[a]['client_name']+"</td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
	   			   rowHtml += "</tr>";

				$(".quarterly_portfolio_table").append(rowHtml);

	    		for(var b = 0; b<temp_quarterly_list[a]['job_list'].length; b++)
	    		{
	    			if(temp_quarterly_list[a]['job_list'][b][0] == job)
	    			{
	    				if($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() == "")
						{
							$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html(moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
						}
						else
						{
							$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
						}
	    			}
	    		}
	    	}
    	}
	}
	else if((partner != '' || $("#QP_partner_filter :not(:selected)").length != 0) && job != '0' && year == '')
	{
		var temp_quarterly_list = [];
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
	    	{
	    		for(var c=0; c<partner.length; c++)
	        	{
		    		if((quarterly_list[a]['job_list'][b][5] == partner[c] || quarterly_list[a]['job_list'][b][7] == partner[c]) && quarterly_list[a]['job_list'][b][0] == job)
		    		{
		    			temp_quarterly_list.push(quarterly_list[a]);
		    			break;
		    		}
		    	}
	    	}
    	}

    	for(var a = 0; a<temp_quarterly_list.length; a++)
    	{
    		for(var c=0; c<partner.length; c++)
	        {
	    		if(JSON.stringify(temp_quarterly_list[a]['job_list']).includes(partner[c]) && JSON.stringify(temp_quarterly_list[a]['job_list']).includes(job) && JSON.stringify(temp_quarterly_list[a]['job_list']).includes(jobName))
	    		{
	    			var rowHtml = "<tr>";
		   			   rowHtml += "<td>"+temp_quarterly_list[a]['client_name']+"</td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
		   			   rowHtml += "</tr>";

					$(".quarterly_portfolio_table").append(rowHtml);

		    		for(var b = 0; b<temp_quarterly_list[a]['job_list'].length; b++)
		    		{
		    			if((temp_quarterly_list[a]['job_list'][b][5] == partner[c] || temp_quarterly_list[a]['job_list'][b][7] == partner[c]) && temp_quarterly_list[a]['job_list'][b][0] == job)
		    			{
		    				if($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() == "")
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html(moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
							else
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
		    			}
		    		}
		    	}
		    }
    	}
	}
	else if((partner == '' || $("#QP_partner_filter :not(:selected)").length == 0) && job == '0' && year != '')
	{
		var temp_quarterly_list = [];
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(quarterly_list[a]['job_list'][b][1]);

	    		if(date.getFullYear() == year)
	    		{
	    			temp_quarterly_list.push(quarterly_list[a]);
	    			break;
	    		}
	    	}
    	}

		for(var a = 0; a<temp_quarterly_list.length; a++)
    	{
    		var rowHtml = "<tr>";
   			   rowHtml += "<td>"+temp_quarterly_list[a]['client_name']+"</td>";
   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
   			   rowHtml += "</tr>";

			$(".quarterly_portfolio_table").append(rowHtml);

    		for(var b = 0; b<temp_quarterly_list[a]['job_list'].length; b++)
    		{
    			var date = new Date(temp_quarterly_list[a]['job_list'][b][1]);

    			if(date.getFullYear() == year)
    			{
	    			if($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() == "")
					{
						$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html(moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
					}
					else
					{
						$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
					}
				}
    		}
    	}
	}
	else if((partner != '' || $("#QP_partner_filter :not(:selected)").length != 0) && job == '0' && year != '')
	{
		var temp_quarterly_list = [];
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(quarterly_list[a]['job_list'][b][1]);

	    		for(var c=0; c<partner.length; c++)
		        {
		    		if((quarterly_list[a]['job_list'][b][5] == partner[c] || quarterly_list[a]['job_list'][b][7] == partner[c]) && date.getFullYear() == year)
		    		{
		    			temp_quarterly_list.push(quarterly_list[a]);
		    			break;
		    		}
		    	}	
	    	}
    	}

		for(var a = 0; a<temp_quarterly_list.length; a++)
    	{
    		for(var c=0; c<partner.length; c++)
		    {
	    		if(JSON.stringify(temp_quarterly_list[a]['job_list']).includes(partner[c]))
	    		{
	    			var rowHtml = "<tr>";
		   			   rowHtml += "<td>"+temp_quarterly_list[a]['client_name']+"</td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
		   			   rowHtml += "</tr>";

					$(".quarterly_portfolio_table").append(rowHtml);

		    		for(var b = 0; b<temp_quarterly_list[a]['job_list'].length; b++)
		    		{
		    			var date = new Date(temp_quarterly_list[a]['job_list'][b][1]);

		    			if((temp_quarterly_list[a]['job_list'][b][5] == partner[c] || temp_quarterly_list[a]['job_list'][b][7] == partner[c]) && date.getFullYear() == year)
		    			{
		    				if($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() == "")
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html(moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
							else
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
		    			}
		    		}
	    		}
	    	}
    	}
	}
	else if((partner == '' || $("#QP_partner_filter :not(:selected)").length == 0) && job != '0' && year != '')
	{
		var temp_quarterly_list = [];
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(quarterly_list[a]['job_list'][b][1]);

	    		if(quarterly_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
	    		{
	    			temp_quarterly_list.push(quarterly_list[a]);
	    			break;
	    		}
	    	}
    	}

    	for(var a = 0; a<temp_quarterly_list.length; a++)
    	{
    		if(JSON.stringify(temp_quarterly_list[a]['job_list']).includes(job) && JSON.stringify(temp_quarterly_list[a]['job_list']).includes(jobName))
    		{
    			var rowHtml = "<tr>";
	   			   rowHtml += "<td>"+temp_quarterly_list[a]['client_name']+"</td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
	   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
	   			   rowHtml += "</tr>";

				$(".quarterly_portfolio_table").append(rowHtml);

	    		for(var b = 0; b<temp_quarterly_list[a]['job_list'].length; b++)
	    		{
	    			var date = new Date(temp_quarterly_list[a]['job_list'][b][1]);

	    			if(temp_quarterly_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
	    			{
	    				if($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() == "")
						{
							$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html(moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
						}
						else
						{
							$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
						}
	    			}
	    		}
	    	}
    	}
	}
	else if((partner != '' || $("#QP_partner_filter :not(:selected)").length != 0) && job != '0' && year != '')
	{
		var temp_quarterly_list = [];
		for(var a = 0; a<quarterly_list.length; a++)
    	{
    		for(var b = 0; b<quarterly_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(quarterly_list[a]['job_list'][b][1]);

	    		for(var c=0; c<partner.length; c++)
			    {
		    		if((quarterly_list[a]['job_list'][b][5] == partner[c] || quarterly_list[a]['job_list'][b][7] == partner[c]) && quarterly_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
		    		{
		    			temp_quarterly_list.push(quarterly_list[a]);
		    			break;
		    		}
		    	}
	    	}
    	}

    	for(var a = 0; a<temp_quarterly_list.length; a++)
    	{
    		for(var c=0; c<partner.length; c++)
			{
	    		if(JSON.stringify(temp_quarterly_list[a]['job_list']).includes(partner[c]) && JSON.stringify(temp_quarterly_list[a]['job_list']).includes(job) && JSON.stringify(temp_quarterly_list[a]['job_list']).includes(jobName))
	    		{
	    			var rowHtml = "<tr>";
		   			   rowHtml += "<td>"+temp_quarterly_list[a]['client_name']+"</td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J1'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J2'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J3'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J4'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J5'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J6'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J7'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J8'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J10'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J11'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J12'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J13'></td>";
		   			   rowHtml += "<td class='text-left Q"+a+"J9'></td>";
		   			   rowHtml += "</tr>";

					$(".quarterly_portfolio_table").append(rowHtml);

		    		for(var b = 0; b<temp_quarterly_list[a]['job_list'].length; b++)
		    		{
		    			var date = new Date(temp_quarterly_list[a]['job_list'][b][1]);

		    			if((temp_quarterly_list[a]['job_list'][b][5] == partner[c] || temp_quarterly_list[a]['job_list'][b][7] == partner[c]) && temp_quarterly_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
		    			{
		    				if($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() == "")
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html(moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
							else
							{
								$('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html($('.Q'+a+'J'+temp_quarterly_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_quarterly_list[a]['job_list'][b][2]).format('DD MMM YYYY') +' ~ '+ moment(temp_quarterly_list[a]['job_list'][b][3]).format('DD MMM YYYY') + ' ('+ temp_quarterly_list[a]['job_list'][b][4]+ ') ');
							}
		    			}
		    		}
		    	}
		    }
    	}
	}

	$(".datatable-quarterly_portfolio").DataTable({
		"order": [],
		"scrollY": "600px",
		"scrollX": true,
		"scrollCollapse": true,
		"pageLength": 50,
		"lengthChange": false,
		fixedColumns: {leftColumns: 1}
	});

	$("#loadingmessage").hide();
}