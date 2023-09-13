var date = new Date();
var months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];


$('#MP_partner_filter').multiselect({
  allSelectedText: 'All',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  maxHeight: 200,
  includeSelectAllOption: true
});
$("#MP_partner_filter").multiselect('selectAll', false);
$("#MP_partner_filter").multiselect('updateButtonText');


$(document).ready(function ()
{
  for(var a=0; a<monthly_list.length; a++)
  {
    var rowHtml = "<tr>";
    rowHtml += "<td>"+monthly_list[a]['client_name']+"</td>";
    rowHtml += "<td>"+months[monthly_list[a]['month']]+" "+date.getFullYear()+"</td>";
    rowHtml += "<td class='text-left M"+a+"7'></td>";
    rowHtml += "<td class='text-left M"+a+"10'></td>";
    rowHtml += "<td class='text-left M"+a+"9'></td>";
    rowHtml += "</tr>";

    $(".monthly_portfolio_table").append(rowHtml);

    for(var b=0; b<monthly_list[a]['job_list'].length; b++)
    {
      if(monthly_list[a]['assign_list'][b] != 0)
      {
      $('.M'+a+monthly_list[a]['job_list'][b]).html('Assigned');
      }
      else
      {
        $('.M'+a+monthly_list[a]['job_list'][b]).html('Not Assign');
      }
    }
  }

  $(".datatable-monthly_portfolio").DataTable({
    "order": [],
    "autoWidth" : false,
    "columnDefs": [{ "targets": [0], "searchable": true },{ "targets": "_all", "searchable": false }],
  });
});


$(document).on('click',".monthly",function(){
  $("#datatable-monthly_portfolio").DataTable().destroy();
  $(".datatable-monthly_portfolio").DataTable({
    "order": [],
    "autoWidth" : false,
    "columnDefs": [{ "targets": [0], "searchable": true },{ "targets": "_all", "searchable": false }],
  });
});


$("#generate_monthly_portfolio_Excel").click(function(e) {
  $("#loadingmessage").show();

  var table = $('#datatable-monthly_portfolio').DataTable();
  table = table.data();

  var tableData = [];

  for(var a = 0; a<table.length; a++)
  {
    tableData.push(table[a]);
  }

  $.ajax({
    type: "POST",
    url: "portfolio/generateMonthlyPortfolioExcel",
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


$("#MP_partner_filter").change(function(e)
{
  $("#loadingmessage").show();
  monthly_portfolio_filtering();
  e.preventDefault(); // avoid to execute the actual submit of the form.
});


function monthly_portfolio_filtering()
{
	$("#datatable-monthly_portfolio").DataTable().destroy();
	$('.monthly_portfolio_table').empty();

	var partner = $("#MP_partner_filter").val();
	var list_string = JSON.stringify(annually_list);

	if(partner == '' || $("#MP_partner_filter :not(:selected)").length == 0)
	{
		for(var a=0; a<monthly_list.length; a++)
    {
      var rowHtml = "<tr>";
         rowHtml += "<td>"+monthly_list[a]['client_name']+"</td>";
         rowHtml += "<td>"+months[monthly_list[a]['month']]+" "+date.getFullYear()+"</td>";
         rowHtml += "<td class='text-left M"+a+"7'></td>";
         rowHtml += "<td class='text-left M"+a+"10'></td>";
         rowHtml += "<td class='text-left M"+a+"9'></td>";
         rowHtml += "</tr>";

      $(".monthly_portfolio_table").append(rowHtml);

      for(var b=0; b<monthly_list[a]['job_list'].length; b++)
      {
        if(monthly_list[a]['assign_list'][b] != 0)
        {
          $('.M'+a+monthly_list[a]['job_list'][b]).html('Assigned');
        }
        else
        {
          $('.M'+a+monthly_list[a]['job_list'][b]).html('Not Assign');
        }
      }
    }
	}
	else
	{
		for(var a=0; a<monthly_list.length; a++)
    {
      for(var c=0; c<partner.length; c++)
      {
        if(monthly_list[a]['partner'] == partner[c] || monthly_list[a]['manager'] == partner[c])
        {
          var rowHtml = "<tr>";
             rowHtml += "<td>"+monthly_list[a]['client_name']+"</td>";
             rowHtml += "<td>"+months[monthly_list[a]['month']]+" "+date.getFullYear()+"</td>";
             rowHtml += "<td class='text-left M"+a+"7'></td>";
             rowHtml += "<td class='text-left M"+a+"10'></td>";
             rowHtml += "<td class='text-left M"+a+"9'></td>";
             rowHtml += "</tr>";

          $(".monthly_portfolio_table").append(rowHtml);

          for(var b=0; b<monthly_list[a]['job_list'].length; b++)
          {
            if(monthly_list[a]['assign_list'][b] != 0)
            {
              $('.M'+a+monthly_list[a]['job_list'][b]).html('Assigned');
            }
            else
            {
              $('.M'+a+monthly_list[a]['job_list'][b]).html('Not Assign');
            }
          }
        }
      }
    }
	}

  $(".datatable-monthly_portfolio").DataTable({
    "order": [],
    "autoWidth" : false,
    "columnDefs": [{ "targets": [0], "searchable": true },{ "targets": "_all", "searchable": false }],
  });

	$("#loadingmessage").hide();
}