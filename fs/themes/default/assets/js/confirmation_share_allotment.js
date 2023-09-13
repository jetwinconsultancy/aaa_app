var follow_up_history_data;

$('.edit_share_allotment_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#transaction_confirmation_member_table").offset().top-180
    }, 2000);
});

check_incorp_date();

$('.date_of_follow_up').datepicker({ 
     dateFormat:'dd/mm/yyyy',
 }).datepicker('setStartDate', "01/01/1920")
.datepicker("setDate", new Date());


$('#datetimepicker5').datetimepicker({
    format: 'LT'
});

$('.next_follow_up_date').datepicker({ 
     dateFormat:'dd/mm/yyyy',
 }).datepicker('setStartDate', "01/01/1920")
.datepicker("setDate", new Date());


$('#datetimepicker6').datetimepicker({
    format: 'LT'
});

$('.tran_status').on("click",function(e){
    e.preventDefault();
    if($(".tran_status").val() == "3")
    {
        $(".reason_cancellation_textfield").show();
    }
    else
    {
        $(".reason_cancellation_textfield").hide();
        $(".cancellation_reason").val("");
    }
});

$('.create_follow_up').on("click",function(e){
    e.preventDefault();
    $(".follow_up_form_section").show();
    $(".create_follow_up_form_section").hide();
});

$('.follow_up_outcome').on("click",function(e){
    e.preventDefault();
    if($(this).val() == "1")
    {
        $(".action_part").hide();
        $(".follow_up_action").val("0");
        $(".next_follow_up_date").datepicker("setDate", new Date());
        $(".next_follow_up_time").val("");
        $(".follow_up_action").prop("disabled", true);
        $(".next_follow_up_date").prop("disabled", true);
        $(".next_follow_up_time").prop("disabled", true);
    }
    else if($(this).val() == "2")
    {
        $(".action_part").show();
        $(".follow_up_action").prop("disabled", false);
        $(".next_follow_up_date").prop("disabled", false);
        $(".next_follow_up_time").prop("disabled", false);
    }
    
});

$('#save_follow_up').click(function(e){
    e.preventDefault();
    $('#loadingWizardMessage').show();
    //console.log($('#follow_up_form').serialize());
    $.ajax({ //Upload common input
        url: "transaction/add_follow_up_info",
        type: "POST",
        data: $('#follow_up_form').serialize() + '&company_code=' + $('#company_code').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
        dataType: 'json',
        success: function (response) {
            $('#loadingWizardMessage').hide();
            //console.log(response.Status);
            if (response.Status === 1) {
                toastr.success(response.message, response.title);
                follow_up_history_data = response.follow_up_history;
                follow_up_history_table(response.follow_up_history);
                $('.follow_up_history_id').val("");
                $('.date_of_follow_up').datepicker("setDate", new Date());
                $('.time_of_follow_up').val("");
                $('.follow_up_remark').val("");
                $('.follow_up_outcome').val("0");

                $(".action_part").hide();
                $(".follow_up_action").val("0");
                $(".next_follow_up_date").datepicker("setDate", new Date());
                $(".next_follow_up_time").val("");
                $(".follow_up_action").prop("disabled", true);
                $(".next_follow_up_date").prop("disabled", true);
                $(".next_follow_up_time").prop("disabled", true);

                $(".follow_up_form_section").hide();
                $(".create_follow_up_form_section").show();
            }
        }
    })
});

function follow_up_history_table(follow_up_history)
{   
    if(follow_up_history)
    {
        console.log(follow_up_history);
        $(".each_follow_up_history").remove();

        for(var i = 0; i < follow_up_history.length; i++)
        {
            $b=""; 
            $b += '<tr class="each_follow_up_history">';
            $b += '<td class="hidden"><input type="text" class="form-control" name="each_follow_up_history_id" id="each_follow_up_history_id" value="'+follow_up_history[i]["id"]+'"/></td>';


            $b += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_follow_up_history" data-id="'+follow_up_history[i]["id"]+'" data-follow_up_info="'+follow_up_history[i]+'" id="edit_follow_up_history">'+follow_up_history[i]["follow_up_id"]+'</a></td>';

            
            $b += '<td style="text-align: center">' + follow_up_history[i]["date_of_follow_up"] +" "+ follow_up_history[i]["time_of_follow_up"] +'</td>';
            $b += '<td style="text-align: center">' + follow_up_history[i]["next_follow_up_date"] +" "+ follow_up_history[i]["next_follow_up_time"] +'</td>';
            $b += '<td>'+follow_up_history[i]["first_name"]+'</td>';
            $b += '<td><button type="button" class="btn btn-primary delete_follow_up_history_button" onclick="delete_follow_up_history(this)">Delete</button></td>';
            $b += '</tr>';

            $("#follow_up_table").append($b);
        }
    }
}

$('.edit_follow_up_history').live("click",function(){
    
    var follow_up_history_id =  $(this).data("id");follow_up_history_data

    for(var i = 0; i < follow_up_history_data.length; i++)
    {
        if(follow_up_history_data[i]["id"] == follow_up_history_id)
        {
            $(".follow_up_form_section").show();
            $(".create_follow_up_form_section").hide();

            $(".follow_up_history_id").val(follow_up_history_data[i]["id"]);
            $(".date_of_follow_up").val(follow_up_history_data[i]["date_of_follow_up"]);
            $(".time_of_follow_up").val(follow_up_history_data[i]["time_of_follow_up"]);
            $(".follow_up_remark").val(follow_up_history_data[i]["follow_up_remark"]);
            $(".follow_up_outcome").val(follow_up_history_data[i]["follow_up_outcome_id"]);
            if(follow_up_history_data[i]["follow_up_outcome_id"] == "2")
            {
                $(".action_part").show();
                $(".follow_up_action").prop("disabled", false);
                $(".next_follow_up_date").prop("disabled", false);
                $(".next_follow_up_time").prop("disabled", false);

                $(".follow_up_action").val(follow_up_history_data[i]["follow_up_action_id"]);
                $(".next_follow_up_date").val(follow_up_history_data[i]["next_follow_up_date"]);
                $(".next_follow_up_time").val(follow_up_history_data[i]["next_follow_up_time"]);
            }
            else
            {
                $(".action_part").hide();
                $(".follow_up_action").val("0");
                $(".next_follow_up_date").datepicker("setDate", new Date());
                $(".next_follow_up_time").val("");
                $(".follow_up_action").prop("disabled", true);
                $(".next_follow_up_date").prop("disabled", true);
                $(".next_follow_up_time").prop("disabled", true);
            }
            
        }
    }
});

function delete_follow_up_history(element){
    var tr = jQuery(element).parent().parent();
    var each_follow_up_history_id = tr.find('input[name="each_follow_up_history_id"]').val();
    console.log(each_follow_up_history_id);
    if(each_follow_up_history_id != undefined)
    {
        $('#loadingWizardMessage').show();
        $.ajax({ //Upload common input
            url: "transaction/delete_follow_up_history",
            type: "POST",
            data: {"follow_up_history_id": each_follow_up_history_id, "company_code": $('#company_code').val(), 'transaction_master_id': $("#transaction_trans #transaction_master_id").val()},
            dataType: 'json',
            success: function (response) {
                //console.log(response);
                $('#loadingWizardMessage').hide();
                toastr.success(response.message, response.title);
                follow_up_history_data = response.follow_up_history;
                follow_up_history_table(response.follow_up_history);
            }
        });
    }
    tr.remove();
}