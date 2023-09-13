$("#AP_job_filter").select2();


$('#AP_year_filter').datepicker({
  autoclose: true,
  minViewMode: 2,
  format: 'yyyy'
});


$('#AP_partner_filter').multiselect({
  allSelectedText: 'All',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  maxHeight: 200,
  includeSelectAllOption: true
});
$("#AP_partner_filter").multiselect('selectAll', false);
$("#AP_partner_filter").multiselect('updateButtonText');


$(document).ready(function ()
{
  for(var a=0; a<annually_list.length; a++){

    var rowHtml = "<tr>";
    rowHtml += "<td>"+annually_list[a]['client_name']+"</td>";
    rowHtml += "<td class='text-left A"+a+"J1'></td>";
    rowHtml += "<td class='text-left A"+a+"J2'></td>";
    rowHtml += "<td class='text-left A"+a+"J3'></td>";
    rowHtml += "<td class='text-left A"+a+"J4'></td>";
    rowHtml += "<td class='text-left A"+a+"J5'></td>";
    rowHtml += "<td class='text-left A"+a+"J6'></td>";
    rowHtml += "<td class='text-left A"+a+"J7'></td>";
    rowHtml += "<td class='text-left A"+a+"J8'></td>";
    rowHtml += "<td class='text-left A"+a+"J10'></td>";
    rowHtml += "<td class='text-left A"+a+"J11'></td>";
    rowHtml += "<td class='text-left A"+a+"J12'></td>";
    rowHtml += "<td class='text-left A"+a+"J13'></td>";
    rowHtml += "<td class='text-left A"+a+"J9'></td>";
    rowHtml += "</tr>";

    $(".annually_portfolio_table").append(rowHtml);

    for(var b=0; b<annually_list[a]['job_list'].length; b++)
    {
      if(annually_list[a]['job_list'][b][1] != null)
      {
        if($('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html() == "")
        {
          $('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html(moment(annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ annually_list[a]['job_list'][b][2]);
        }
        else
        {
          $('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ annually_list[a]['job_list'][b][2]);
        }
      }
    }
  }

  $(".datatable-annually_portfolio").DataTable({
    "order": [],
    "scrollY": "600px",
    "scrollX": true,
    "scrollCollapse": true,
    "pageLength": 50,
    "lengthChange": false,
    fixedColumns: {leftColumns: 1}
  });
});


$(document).on('click',".annually",function(){
  $("#loadingmessage").show();
  $("#datatable-annually_portfolio").DataTable().destroy();
  $(".datatable-annually_portfolio").DataTable({
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


$("#generate_annually_portfolio_Excel").click(function(e) {
  $("#loadingmessage").show();

  var table = $('#datatable-annually_portfolio').DataTable();
  table = table.data();

  var tableData = [];

  for(var a = 0; a<table.length; a++)
  {
    tableData.push(table[a]);
  }

  $.ajax({
     type: "POST",
     url: "portfolio/generateAnnuallyPortfolioExcel",
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


$("#AP_partner_filter").change(function(e)
{
  $("#loadingmessage").show();
  annually_portfolio_filtering();
  e.preventDefault(); // avoid to execute the actual submit of the form.
});
$("#AP_job_filter").change(function(e)
{
  $("#loadingmessage").show();
  annually_portfolio_filtering();
  e.preventDefault(); // avoid to execute the actual submit of the form.
});
$("#AP_year_filter").change(function(e)
{
  $("#loadingmessage").show();
  annually_portfolio_filtering();
  e.preventDefault(); // avoid to execute the actual submit of the form.
});


function annually_portfolio_filtering()
{
	$("#datatable-annually_portfolio").DataTable().destroy();
	$('.annually_portfolio_table').empty();

	var partner = $("#AP_partner_filter").val();
	var job = $("#AP_job_filter").val();
	var jobName = $("#AP_job_filter option:selected").text();
	var year = $("#AP_year_filter").val();
	var list_string = JSON.stringify(annually_list);

	if((partner == '' || $("#AP_partner_filter :not(:selected)").length == 0) && job == '0' && year == '')
	{
		for(var a = 0; a<annually_list.length; a++)
    	{
    		var rowHtml = "<tr>";
   			    rowHtml += "<td>"+annually_list[a]['client_name']+"</td>";
   			    rowHtml += "<td class='text-left A"+a+"J1'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J2'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J3'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J4'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J5'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J6'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J7'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J8'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J10'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J11'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J12'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J13'></td>";
   			    rowHtml += "<td class='text-left A"+a+"J9'></td>";
   			    rowHtml += "</tr>";

			$(".annually_portfolio_table").append(rowHtml);

    		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
    		{
    			if($('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html() == "")
					{
						$('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html(moment(annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ annually_list[a]['job_list'][b][2]);
					}
					else
					{
						$('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ annually_list[a]['job_list'][b][2]);
					}
    		}
    	}
	}
	else if((partner != '' || $("#AP_partner_filter :not(:selected)").length != 0) && job == '0' && year == '')
	{
		var temp_annually_list = [];
		for(var a = 0; a<annually_list.length; a++)
	  	{   
	  		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(annually_list[a]['job_list'][b][1]);

		        for(var c=0; c<partner.length; c++)
		        {
		    		if((annually_list[a]['job_list'][b][3] == partner[c] || annually_list[a]['job_list'][b][5] == partner[c]))
		    		{
		    			temp_annually_list.push(annually_list[a]);
		    			break;
		    		}
		        }
	    	}
	  	}

        var uniqueList = [];
        $.each(temp_annually_list, function(i, el){
            if($.inArray(el, uniqueList) === -1) uniqueList.push(el);
        });
        temp_annually_list = uniqueList;

    	for(var a = 0; a<temp_annually_list.length; a++)
      	{
            // for(var c=0; c<partner.length; c++)
            // {
        		// if(JSON.stringify(temp_annually_list[a]['job_list']).includes(partner[c]))
        		// {
        			var rowHtml = "<tr>";
           			   rowHtml += "<td>"+temp_annually_list[a]['client_name']+"</td>";
           			   rowHtml += "<td class='text-left A"+a+"J1'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J2'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J3'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J4'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J5'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J6'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J7'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J8'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J10'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J11'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J12'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J13'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J9'></td>";
           			   rowHtml += "</tr>";

          			$(".annually_portfolio_table").append(rowHtml);

              		for(var b = 0; b<temp_annually_list[a]['job_list'].length; b++)
              		{
              			// if((temp_annually_list[a]['job_list'][b][3] == partner[c] || temp_annually_list[a]['job_list'][b][5] == partner[c]))
                        if((partner.includes(temp_annually_list[a]['job_list'][b][3]) || partner.includes(temp_annually_list[a]['job_list'][b][5])))
              			{
              				if($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() == "")
         					{
         						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html(moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
         					}
         					else
         					{
         						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
         					}
              			}
              		}
        		// }
            // }
      	}
	}
	else if((partner == '' || $("#AP_partner_filter :not(:selected)").length == 0) && job != '0' && year == '')
	{
		var temp_annually_list = [];
		for(var a = 0; a<annually_list.length; a++)
    	{
    		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(annually_list[a]['job_list'][b][1]);

	    		if(annually_list[a]['job_list'][b][0] == job)
	    		{
	    			temp_annually_list.push(annually_list[a]);
	    			break;
	    		}
	    	}
    	}

        var uniqueList = [];
        $.each(temp_annually_list, function(i, el){
            if($.inArray(el, uniqueList) === -1) uniqueList.push(el);
        });
        temp_annually_list = uniqueList;

    	for(var a = 0; a<temp_annually_list.length; a++)
    	{
    		if(JSON.stringify(temp_annually_list[a]['job_list']).includes(job) && JSON.stringify(temp_annually_list[a]['job_list']).includes(jobName))
    		{
    			var rowHtml = "<tr>";
       			   rowHtml += "<td>"+temp_annually_list[a]['client_name']+"</td>";
       			   rowHtml += "<td class='text-left A"+a+"J1'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J2'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J3'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J4'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J5'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J6'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J7'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J8'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J10'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J11'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J12'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J13'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J9'></td>";
       			   rowHtml += "</tr>";

				$(".annually_portfolio_table").append(rowHtml);

	    		for(var b = 0; b<temp_annually_list[a]['job_list'].length; b++)
	    		{
	    			if(temp_annually_list[a]['job_list'][b][0] == job)
	    			{
	    				if($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() == "")
	   					{
	   						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html(moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
	   					}
	   					else
	   					{
	   						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
	   					}
	    			}
	    		}
	    	}
    	}
	}
	else if((partner != '' || $("#AP_partner_filter :not(:selected)").length != 0) && job != '0' && year == '')
	{
		var temp_annually_list = [];
		for(var a = 0; a<annually_list.length; a++)
      	{
      		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
        	{
                for(var c=0; c<partner.length; c++)
                { 
                    if((annually_list[a]['job_list'][b][3] == partner[c] || annually_list[a]['job_list'][b][5] == partner[c]) && annually_list[a]['job_list'][b][0] == job)
                    {
                        temp_annually_list.push(annually_list[a]);
                        break;
                    }
                }
        	}
      	}

        var uniqueList = [];
        $.each(temp_annually_list, function(i, el){
            if($.inArray(el, uniqueList) === -1) uniqueList.push(el);
        });
        temp_annually_list = uniqueList;

      	for(var a = 0; a<temp_annually_list.length; a++)
      	{
            // for(var c=0; c<partner.length; c++)
            // {
        		// if(JSON.stringify(temp_annually_list[a]['job_list']).includes(partner[c]) && JSON.stringify(temp_annually_list[a]['job_list']).includes(job) && JSON.stringify(temp_annually_list[a]['job_list']).includes(jobName))
                if(JSON.stringify(temp_annually_list[a]['job_list']).includes(job) && JSON.stringify(temp_annually_list[a]['job_list']).includes(jobName))
        		{
        			var rowHtml = "<tr>";
           			   rowHtml += "<td>"+temp_annually_list[a]['client_name']+"</td>";
           			   rowHtml += "<td class='text-left A"+a+"J1'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J2'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J3'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J4'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J5'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J6'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J7'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J8'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J10'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J11'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J12'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J13'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J9'></td>";
           			   rowHtml += "</tr>";

          			$(".annually_portfolio_table").append(rowHtml);

              		for(var b = 0; b<temp_annually_list[a]['job_list'].length; b++)
              		{
              			// if((temp_annually_list[a]['job_list'][b][3] == partner[c] || temp_annually_list[a]['job_list'][b][5] == partner[c]) && temp_annually_list[a]['job_list'][b][0] == job)
                        if((partner.includes(temp_annually_list[a]['job_list'][b][3]) || partner.includes(temp_annually_list[a]['job_list'][b][5])) && temp_annually_list[a]['job_list'][b][0] == job)
              			{
              				if($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() == "")
         					{
         						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html(moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
         					}
         					else
         					{
         						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
         					}
              			}
              		}
              	}
            // }
      	}
	}
	else if((partner == '' || $("#AP_partner_filter :not(:selected)").length == 0) && job == '0' && year != '')
	{
		var temp_annually_list = [];
		for(var a = 0; a<annually_list.length; a++)
    	{
    		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(annually_list[a]['job_list'][b][1]);

	    		if(date.getFullYear() == year)
	    		{
	    			temp_annually_list.push(annually_list[a]);
	    			break;
	    		}
	    	}
    	}

        var uniqueList = [];
        $.each(temp_annually_list, function(i, el){
            if($.inArray(el, uniqueList) === -1) uniqueList.push(el);
        });
        temp_annually_list = uniqueList;
    	
		for(var a = 0; a<temp_annually_list.length; a++)
    	{
    		var rowHtml = "<tr>";
   			   rowHtml += "<td>"+temp_annually_list[a]['client_name']+"</td>";
   			   rowHtml += "<td class='text-left A"+a+"J1'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J2'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J3'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J4'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J5'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J6'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J7'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J8'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J10'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J11'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J12'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J13'></td>";
   			   rowHtml += "<td class='text-left A"+a+"J9'></td>";
   			   rowHtml += "</tr>";

			$(".annually_portfolio_table").append(rowHtml);

    		for(var b = 0; b<temp_annually_list[a]['job_list'].length; b++)
    		{
    			var date = new Date(temp_annually_list[a]['job_list'][b][1]);

    			if(date.getFullYear() == year)
    			{
    				if($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() == "")
   					{
   						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html(moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
   					}
   					else
   					{
   						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
   					}
    			}
    		}
    	}
	}
	else if((partner != '' || $("#AP_partner_filter :not(:selected)").length != 0) && job == '0' && year != '')
	{
		var temp_annually_list = [];
		for(var a = 0; a<annually_list.length; a++)
      	{
      		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
        	{
        		var date = new Date(annually_list[a]['job_list'][b][1]);

                for(var c=0; c<partner.length; c++)
                {
    	    		if((annually_list[a]['job_list'][b][3] == partner[c] || annually_list[a]['job_list'][b][5] == partner[c]) && date.getFullYear() == year)
    	    		{
    	    			temp_annually_list.push(annually_list[a]);
    	    			break;
    	    		}
                }
        	}
      	}

        var uniqueList = [];
        $.each(temp_annually_list, function(i, el){
            if($.inArray(el, uniqueList) === -1) uniqueList.push(el);
        });
        temp_annually_list = uniqueList;

		for(var a = 0; a<temp_annually_list.length; a++)
      	{
            // for(var c=0; c<partner.length; c++)
            // {
        		// if(JSON.stringify(temp_annually_list[a]['job_list']).includes(partner[c]))
        		// {
        			var rowHtml = "<tr>";
           			   rowHtml += "<td>"+temp_annually_list[a]['client_name']+"</td>";
           			   rowHtml += "<td class='text-left A"+a+"J1'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J2'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J3'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J4'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J5'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J6'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J7'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J8'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J10'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J11'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J12'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J13'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J9'></td>";
           			   rowHtml += "</tr>";

          			$(".annually_portfolio_table").append(rowHtml);

              		for(var b = 0; b<temp_annually_list[a]['job_list'].length; b++)
              		{
              			var date = new Date(temp_annually_list[a]['job_list'][b][1]);

              			// if((temp_annually_list[a]['job_list'][b][3] == partner[c] || temp_annually_list[a]['job_list'][b][5] == partner[c]) && date.getFullYear() == year)
                        if((partner.includes(temp_annually_list[a]['job_list'][b][3]) || partner.includes(temp_annually_list[a]['job_list'][b][5])) && date.getFullYear() == year)
              			{
              				if($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() == "")
             					{
             						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html(moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
             					}
             					else
             					{
             						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
             					}
              			}
              		}
        		// }
            // }
      	}
	}
	else if((partner == '' || $("#AP_partner_filter :not(:selected)").length == 0) && job != '0' && year != '')
	{
		var temp_annually_list = [];
		for(var a = 0; a<annually_list.length; a++)
    	{
    		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(annually_list[a]['job_list'][b][1]);

	    		if(annually_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
	    		{
	    			temp_annually_list.push(annually_list[a]);
	    			break;
	    		}
	    	}
    	}

        var uniqueList = [];
        $.each(temp_annually_list, function(i, el){
            if($.inArray(el, uniqueList) === -1) uniqueList.push(el);
        });
        temp_annually_list = uniqueList;

    	for(var a = 0; a<temp_annually_list.length; a++)
    	{
    		if(JSON.stringify(temp_annually_list[a]['job_list']).includes(job) && JSON.stringify(temp_annually_list[a]['job_list']).includes(jobName))
    		{
    			var rowHtml = "<tr>";
       			   rowHtml += "<td>"+temp_annually_list[a]['client_name']+"</td>";
       			   rowHtml += "<td class='text-left A"+a+"J1'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J2'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J3'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J4'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J5'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J6'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J7'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J8'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J10'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J11'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J12'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J13'></td>";
       			   rowHtml += "<td class='text-left A"+a+"J9'></td>";
       			   rowHtml += "</tr>";

				$(".annually_portfolio_table").append(rowHtml);

	    		for(var b = 0; b<temp_annually_list[a]['job_list'].length; b++)
	    		{
	    			var date = new Date(temp_annually_list[a]['job_list'][b][1]);

	    			if(temp_annually_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
	    			{
	    				if($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() == "")
	   					{
	   						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html(moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
	   					}
	   					else
	   					{
	   						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
	   					}
	    			}
	    		}
	    	}
    	}
	}
	else if((partner != '' || $("#AP_partner_filter :not(:selected)").length != 0) && job != '0' && year != '')
	{
		var temp_annually_list = [];
		for(var a = 0; a<annually_list.length; a++)
	  	{
	  		for(var b = 0; b<annually_list[a]['job_list'].length; b++)
	    	{
	    		var date = new Date(annually_list[a]['job_list'][b][1]);

		        for(var c=0; c<partner.length; c++)
		        {
		      		if((annually_list[a]['job_list'][b][3] == partner[c] || annually_list[a]['job_list'][b][5] == partner[c]) && annually_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
		      		{
		      			temp_annually_list.push(annually_list[a]);
		      			break;
		      		}
		        }
	    	}
	  	}

        var uniqueList = [];
        $.each(temp_annually_list, function(i, el){
            if($.inArray(el, uniqueList) === -1) uniqueList.push(el);
        });
        temp_annually_list = uniqueList;

      	for(var a = 0; a<temp_annually_list.length; a++)
      	{
            // for(var c=0; c<partner.length; c++)
            // {
        		if(JSON.stringify(temp_annually_list[a]['job_list']).includes(job) && JSON.stringify(temp_annually_list[a]['job_list']).includes(jobName))
        		{
        			var rowHtml = "<tr>";
           			   rowHtml += "<td>"+temp_annually_list[a]['client_name']+"</td>";
           			   rowHtml += "<td class='text-left A"+a+"J1'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J2'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J3'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J4'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J5'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J6'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J7'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J8'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J10'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J11'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J12'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J13'></td>";
           			   rowHtml += "<td class='text-left A"+a+"J9'></td>";
           			   rowHtml += "</tr>";

          			$(".annually_portfolio_table").append(rowHtml);

              		for(var b = 0; b<temp_annually_list[a]['job_list'].length; b++)
              		{
              			var date = new Date(temp_annually_list[a]['job_list'][b][1]);

              			// if((temp_annually_list[a]['job_list'][b][3] == partner[c] || temp_annually_list[a]['job_list'][b][5] == partner[c]) && temp_annually_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
                        if((partner.includes(temp_annually_list[a]['job_list'][b][3]) || partner.includes(temp_annually_list[a]['job_list'][b][5])) && temp_annually_list[a]['job_list'][b][0] == job && date.getFullYear() == year)
              			{
              				if($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() == "")
             					{
             						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html(moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
             					}
             					else
             					{
             						$('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html($('.A'+a+'J'+temp_annually_list[a]['job_list'][b][0]).html() +'<br>'+ moment(temp_annually_list[a]['job_list'][b][1]).format('DD MMM YYYY') +' - '+ temp_annually_list[a]['job_list'][b][2]);
             					}
              			}
              		}
                }
            // }
      	}
	}

	$(".datatable-annually_portfolio").DataTable({
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