var set_value = [];

$(document).ready(function ()
{

$("#only_partner").select2();
$("#only_reviewer").select2();

$('#partner_filter').multiselect({
  allSelectedText: 'All',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  maxHeight: 200,
  buttonWidth: '200px',
  includeSelectAllOption: true
});
// $("#partner_filter").multiselect('selectAll', false);
// $("#partner_filter").multiselect('updateButtonText');

$('#reviewer_filter').multiselect({
  allSelectedText: 'All',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  maxHeight: 200,
  buttonWidth: '200px',
  includeSelectAllOption: true
});
// $("#reviewer_filter").multiselect('selectAll', false);
// $("#reviewer_filter").multiselect('updateButtonText');

$('#TypeOfJob_input').multiselect({
  allSelectedText: 'All',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  maxHeight: 200,
  buttonWidth: '200px',
  includeSelectAllOption: true
});
// $("#TypeOfJob_input").multiselect('selectAll', false);
// $("#TypeOfJob_input").multiselect('updateButtonText');

$('#portfolio_table').DataTable({
    "order": [],
    'rowsGroup': [0,1,3,4],
    'pageLength': 100,
    "autoWidth" : false
});

});

$(document).on('click',".assign",function(){
    $("#loadingmessage").show();
    $.ajax({
        type: "POST",
        url: "portfolio/update_client_to_portfolio",
        success: function(data)
        {    
            $("#loadingmessage").hide();
            // filter();
        }
    });
});

function set(elements)
{
    if($(elements).is(":checked"))
    {
        set_value.push($(elements).val()+'='+$(elements).parent().next().text());
    }
    else
    {
        index = set_value.indexOf($(elements).val()+'='+$(elements).parent().next().text());
        set_value.splice(index,1);
    }
}

function Set_portfolio()
{
    if(set_value.length)
    {
        $("#loadingmessage").show();

        var partner  = $('.only_partner').val();
        var reviewer = $('.only_reviewer').val();
        var job      = $('#TypeOfJob_input').val();

        Set_or_Unset(partner,reviewer,job,set_value);
    }
    else
    {
        toastr.error('Please Select Any Client to Assign', 'Errors');
    }
}

function filter()
{
    $("#loadingmessage").show();

    var partner  = $("#partner_filter").val();
    var reviewer = $("#reviewer_filter").val();
    var job      = $("#TypeOfJob_input").val();

    // fix maximum call stack for rowsgroup
    $(document).on( 'destroy.dt', function ( e, settings ) {
        var api = new $.fn.dataTable.Api( settings );
        api.off('order.dt');
        api.off('preDraw.dt');
        api.off('column-visibility.dt');
        api.off('search.dt');
        api.off('page.dt');
        api.off('length.dt');
        api.off('xhr.dt');
    });
    $('#portfolio_table').DataTable().clear().draw();
    $('#portfolio_table').DataTable().clear().draw();
    $("#portfolio_table").DataTable().destroy();
    $("#portfolio_table").DataTable().destroy();

    // SELECTED ALL
    if($("#partner_filter :not(:selected)").length == 0 && $("#reviewer_filter :not(:selected)").length == 0 && $("#TypeOfJob_input :not(:selected)").length == 0)
    {
        $.ajax({
            type: "POST",
            url: "portfolio/get_all",
            success: function(data)
            {    
                var result  = JSON.parse(data);
                var htmlRow = "";
                
                for(var a = 0; a < result.length; a++)
                {
                    if(result[a]['partner'] == null)
                    {
                        result[a]['partner'] = "";
                    }

                    if(result[a]['reviewer'] == null)
                    {
                        result[a]['reviewer'] = "";
                    }

                    htmlRow += "<tr><td class='text-left' style='text-align:center;'><input type='checkbox' value='"+result[a]['company_code']+"' onclick='set(this);'></td><td style='width: 45%'>"+result[a]['company_name']+"</td><td style='width: 20%'>"+result[a]['jobName']+"</td><td style='width: 15%;text-align:center;'>"+result[a]['partner']+"</td><td style='width: 15%;text-align:center;'>"+result[a]['reviewer']+"</td></tr>";
                }

                $("#portfolio_table_body").append(htmlRow);

                $('#portfolio_table').DataTable({
                    "order": [],
                    'rowsGroup': [0,1,3,4],
                    'pageLength': 100,
                    "autoWidth" : false
                });

                $("#loadingmessage").hide();
            }
        });
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "portfolio/filter",
            data: {'partner':partner, 'reviewer':reviewer, 'job': job},
            success: function(data)
            {    
                var result  = JSON.parse(data);
                var htmlRow = "";
                
                for(var a = 0; a < result.length; a++)
                {
                    if(result[a]['partner'] == null)
                    {
                        result[a]['partner'] = "";
                    }

                    if(result[a]['reviewer'] == null)
                    {
                        result[a]['reviewer'] = "";
                    }

                    htmlRow += "<tr><td class='text-left' style='text-align:center;'><input type='checkbox' value='"+result[a]['company_code']+"' onclick='set(this);'></td><td style='width: 45%'>"+result[a]['company_name']+"</td><td style='width: 20%'>"+result[a]['jobName']+"</td><td style='width: 15%;text-align:center;'>"+result[a]['partner']+"</td><td style='width: 15%;text-align:center;'>"+result[a]['reviewer']+"</td></tr>";
                }

                $("#portfolio_table_body").append(htmlRow);

                $('#portfolio_table').DataTable({
                    "order": [],
                    'rowsGroup': [0,1,3,4],
                    'pageLength': 100,
                    "autoWidth" : false
                });

                $("#loadingmessage").hide();
            }
        });
    }
}

function Set_or_Unset(partner,reviewer,job,list)
{
    var partner_filter  = $("#partner_filter").val();
    var reviewer_filter = $("#reviewer_filter").val();

    $(document).on( 'destroy.dt', function ( e, settings ) {
        var api = new $.fn.dataTable.Api( settings );
        api.off('order.dt');
        api.off('preDraw.dt');
        api.off('column-visibility.dt');
        api.off('search.dt');
        api.off('page.dt');
        api.off('length.dt');
        api.off('xhr.dt');
    });
    $('#portfolio_table').DataTable().clear().draw();
    $('#portfolio_table').DataTable().clear().draw();
    $("#portfolio_table").DataTable().destroy();
    $("#portfolio_table").DataTable().destroy();

    $.ajax({
        type: "POST",
        url: "portfolio/set_or_unset_client_list",
        data: {'partner':partner, 'reviewer':reviewer, 'job': job, 'set_value': list, 'partner_filter': partner_filter, 'reviewer_filter': reviewer_filter},
        success: function(data)
        {  
            $('.only_partner').val('').trigger('change');
            $('.only_reviewer').val('').trigger('change');

            var result  = JSON.parse(data);
            var htmlRow = "";
            
            for(var a = 0; a < result.length; a++)
            {
                if(result[a]['partner'] == null)
                {
                    result[a]['partner'] = "";
                }

                if(result[a]['reviewer'] == null)
                {
                    result[a]['reviewer'] = "";
                }

                htmlRow += "<tr><td class='text-left' style='text-align:center;'><input type='checkbox' value='"+result[a]['company_code']+"' onclick='set(this);'></td><td style='width: 45%'>"+result[a]['company_name']+"</td><td style='width: 20%'>"+result[a]['jobName']+"</td><td style='width: 15%;text-align:center;'>"+result[a]['partner']+"</td><td style='width: 15%;text-align:center;'>"+result[a]['reviewer']+"</td></tr>";
            }

            $("#portfolio_table_body").append(htmlRow);

            $('#portfolio_table').DataTable({
                "order": [],
                'rowsGroup': [0,1,3,4],
                'pageLength': 100,
                "autoWidth" : false
            });

            set_value = [];

            $("#loadingmessage").hide();
        }
    });
}