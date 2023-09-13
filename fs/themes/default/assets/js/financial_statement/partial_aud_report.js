$( document ).ready(function() {
    $('.datepicker_input').datepicker({
        dateFormat:'dd MM yyyy',
    });

    // get opinion data attribute
    // console.log(report_opinion_template_fixed_1.length);
    for (i = 0; i < report_opinion_template_fixed_1.length; i++) {
        // console.log(report_opinion_template_fixed_1[i]["content"]);
        $("label.lbl_opinion_details").data(report_opinion_template_fixed_1[i]["order_by"], report_opinion_template_fixed_1[i]["content"]);

    }

    for (i = 0; i < report_opinion_template_fixed_2.length; i++) {
        $("label.lbl_opinion_details_2").data(report_opinion_template_fixed_2[i]["fs_opinion_type_id"], report_opinion_template_fixed_2[i]["content"]);
    }

    // get basic opinion template
    for (i = 0; i < report_basic_opinion_template_input.length; i++) {
        $(".section_basic_opinion_input").data(report_basic_opinion_template_input[i]["fs_opinion_type_id"], report_basic_opinion_template_input[i]["content"]);
    }
    for (i = 0; i < report_basic_opinion_template_fixed.length; i++) {
        $("label.lbl_basic_for_opinion_fixed").data(report_basic_opinion_template_fixed[i]["fs_opinion_type_id"], report_basic_opinion_template_fixed[i]["content"]);
    }

    for (i = 0; i < key_aud_matter_template.length; i++) {
        $("label.lbl_key_aud_matter").data(key_aud_matter_template[i]["fs_opinion_type_id"], key_aud_matter_template[i]["content"]);
    }

    for (i = 0; i < other_matter_template.length; i++) {
        $("label.lbl_other_matter").data(other_matter_template[i]["order_by"], other_matter_template[i]["content"]);  // let order_by as the id
    }

    for (i = 0; i < disclaimer_of_opinion_template.length; i++) {
        $("textarea.disclaimer_input").data(disclaimer_of_opinion_template[i]["order_by"], disclaimer_of_opinion_template[i]["content"]);  // let order_by as the id
    }
    
    insert_label_opinion($('#fs_aud_report_opinion'), true);
    
    // $('.section_basic_opinion_input').html('<textarea class="form-control tarea_basic_for_opinion" style="width:100%; height: 200px; text-align: justify;"><i>' + $(".section_basic_opinion_input").data(selected) + '</i></textarea>');

    $('textarea.emphasis_input').val(!(this_independ_aud_report[0]["emphasis_of_matter"] === "")?this_independ_aud_report[0]["emphasis_of_matter"]:emphasis_of_matter_template[0]["content"]);
    $("textarea.disclaimer_input").val(!(this_independ_aud_report[0]["disclaimer_of_opinion"] === "")?this_independ_aud_report[0]["disclaimer_of_opinion"]:disclaimer_of_opinion_template[0]["content"]);
});

$("[name='hidden_emphasis_of_matter_checkbox']").bootstrapSwitch({
    state: !(this_independ_aud_report[0]["emphasis_of_matter"] === "")? 1: 0,
    size: 'small',
    onColor: 'primary',
    onText: 'ON',
    offText: 'OFF',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '45px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'
});

$("[name='hidden_other_matter_checkbox']").bootstrapSwitch({
    state: !(this_independ_aud_report[0]["other_matters_checkbox"] === "")? parseInt(this_independ_aud_report[0]["other_matters_checkbox"]): 0,
    size: 'small',
    onColor: 'primary',
    onText: 'ON',
    offText: 'OFF',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '45px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'
});

// !(this_independ_aud_report[0]["last_year_not_audited"] === "")? this_independ_aud_report[0]["last_year_not_audited"]: 1
// console.log(this_independ_aud_report[0]["last_year_not_audited"]);
$("[name='hidden_ly_not_aud_checkbox']").bootstrapSwitch({
    state: !(this_independ_aud_report[0]["last_year_not_audited"] === "")?parseInt(this_independ_aud_report[0]["last_year_not_audited"]): 1 ,
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



// var last_year_audited_by_other_company_state = !(this_independ_aud_report[0]["last_year_audited_by_other_company"] === "")? this_independ_aud_report[0]["last_year_audited_by_other_company"]: 0;
// console.log(last_year_audited_by_other_company_state);
// !(this_independ_aud_report[0]["last_year_audited_by_other_company"] === "")? this_independ_aud_report[0]["last_year_audited_by_other_company"]: 0
$("[name='hidden_ly_other_aud_checkbox']").bootstrapSwitch({
    state: !(this_independ_aud_report[0]["last_year_audited_by_other_company"] === "")? parseInt(this_independ_aud_report[0]["last_year_audited_by_other_company"]): 0,
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

$("[name='hidden_disclaimer_checkbox']").bootstrapSwitch({
    state: !(this_independ_aud_report[0]["disclaimer_of_opinion"] === "")?1 : 0,
    size: 'small',
    onColor: 'primary',
    onText: 'ON',
    offText: 'OFF',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '45px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'
});

// console.log(this_independ_aud_report[0]["key_audit_matter"] === '', this_independ_aud_report[0]["key_audit_matter_input"] === undefined);
// console.log(this_independ_aud_report[0]["key_audit_matter_input"] );

$("[name='hidden_key_audit_matter_checkbox']").bootstrapSwitch({
    state: (!(this_independ_aud_report[0]["key_audit_matter"] === '' || this_independ_aud_report[0]["key_audit_matter"] === undefined) || !(this_independ_aud_report[0]["key_audit_matter_input"] === '' || this_independ_aud_report[0]["key_audit_matter_input"] === undefined))? 1: 0,
    size: 'small',
    onColor: 'primary',
    onText: 'ON',
    offText: 'OFF',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '45px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'
});

if($("[name='hidden_emphasis_of_matter_checkbox']").bootstrapSwitch('state') == 0){
    var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
    var emphasis_input = $(".emphasis_details");

    hidden_val.val(0);
    emphasis_input.hide();
}
else
{
    var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
    var emphasis_input = $(".emphasis_details");

    hidden_val.val(1);
    emphasis_input.show();
}

if($("[name='hidden_key_audit_matter_checkbox']").bootstrapSwitch('state') == 0)
{
    var hidden_val = $(event.target).parent().parent().parent().find("[name='key_audit_matter_checkbox']");

    hidden_val.val(0);
    $('.section_key_audit_matter').hide();
    $('.section_key_audit_matter_input').hide();
}
else
{
    var hidden_val = $(event.target).parent().parent().parent().find("[name='key_audit_matter_checkbox']");
    var selected = $('#form_audit_report #fs_aud_report_opinion').val();

    hidden_val.val(1);

    if(selected != '3')
    {
        $('.section_key_audit_matter_input').show();
    }

    $('.section_key_audit_matter').show();
    // $('.section_key_audit_matter_input').show();
}

/* -------------------------------------------------------------------------------------------------------- */
// Triggered on switch state change.
$("[name='hidden_emphasis_of_matter_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
    var emphasis_input = $(".emphasis_details");

    if(state){
        hidden_val.val(1);
        emphasis_input.show();
    }
    else{
        hidden_val.val(0);
        emphasis_input.hide();
    }
    // console.log(hidden_val.val());
})

// Triggered on switch state change.
$("[name='hidden_other_matter_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    var hidden_val = $(event.target).parent().parent().parent().find("[name='other_matter_checkbox']");
    var other_matter_1_input = $(".other_matter_1");
    var other_matter_2_input = $(".other_matter_2");
    var other_matter_details = $('.other_matter_details');
    var other_matter_input   = $('.other_matter_input');

    var last_year_not_aud_checkbox = $("input[name=ly_not_aud_checkbox]").val();
    // console.log('last_year_not_aud_checkbox = ' + last_year_not_aud_checkbox);

    if(last_year_not_aud_checkbox == 1)
    {
        other_matter_details.find('.lbl_other_matter').html(other_matter_details.find('.lbl_other_matter').data('1'));
    }
    else
    {
        other_matter_details.find('.lbl_other_matter').html(other_matter_details.find('.lbl_other_matter').data('2'));
    }

    if(state){
        hidden_val.val(1);
        other_matter_1_input.show();
        other_matter_2_input.show();
        other_matter_details.show();

        if($('input[name=ly_other_aud_checkbox]').val() == 1)
        {
            other_matter_input.show();
        }
    }
    else{
        hidden_val.val(0);
        other_matter_1_input.hide();
        other_matter_2_input.hide();
        other_matter_details.hide();

        other_matter_input.hide();

        // console.log($('input[name=ly_other_aud_checkbox]').val());
        // if($('input[name=ly_other_aud_checkbox]').val())
        // {
            
        // }
    }
})

// Triggered on switch state change.
$("[name='hidden_ly_not_aud_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    var hidden_val = $(event.target).parent().parent().parent().find("[name='ly_not_aud_checkbox']");
    var other_aud_checkbox = $("[name='hidden_ly_other_aud_checkbox']");
    var other_matter_details = $('.other_matter_details').find('.lbl_other_matter');

    if(state){
        hidden_val.val(1);
        other_aud_checkbox.bootstrapSwitch('state', false);
        other_matter_details.html(other_matter_details.data('1'));
    }
    else{
        hidden_val.val(0);
        other_aud_checkbox.bootstrapSwitch('state', true);
        other_matter_details.html(other_matter_details.data('2'));
    }
})

// Triggered on switch state change.
$("[name='hidden_ly_other_aud_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    var hidden_val = $(event.target).parent().parent().parent().find("[name='ly_other_aud_checkbox']");
    var not_aud_checkbox = $("[name='hidden_ly_not_aud_checkbox']");
    var other_matter_details = $('.other_matter_details').find('.lbl_other_matter');
    var other_matter_input = $('.other_matter_input');

    if(state){
        hidden_val.val(1);
        not_aud_checkbox.bootstrapSwitch('state', false);

        update_other_matter_display_content();
        other_matter_input.show();
    }
    else{
        hidden_val.val(0);
        not_aud_checkbox.bootstrapSwitch('state', true);

        // console.log(replace_content_other_matters(other_matter_details.data('1')));

        other_matter_details.html(other_matter_details.data('1'));
        other_matter_input.hide();
    }
})

// Triggered on switch state change.
$("[name='hidden_disclaimer_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    var hidden_val = $(event.target).parent().parent().parent().find("[name='disclaimer_checkbox']");
    var disclaimer_details = $('.disclaimer_details');

    if(state){
        hidden_val.val(1);
        disclaimer_details.show();
    }
    else{
        hidden_val.val(0);
        disclaimer_details.hide();
    }
    // console.log(hidden_val.val());
})

// Triggered on switch state change.
$("[name='hidden_key_audit_matter_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) 
{
    var hidden_val = $(event.target).parent().parent().parent().find("[name='key_audit_matter_checkbox']");
    var selected = $('#form_audit_report #fs_aud_report_opinion').val();

    // console.log($('#fs_aud_report_opinion').val());

    if(state){
        hidden_val.val(1);

        $('.section_key_audit_matter').show();

        if(selected != '3')
        {
            $('.section_key_audit_matter_input').show();
        }
        else
        {
            $('.section_key_audit_matter_input').hide();
        }
    }
    else{
        hidden_val.val(0);
        $('.section_key_audit_matter').hide();
        $('.section_key_audit_matter_input').hide();
        // emphasis_input.hide();
    }
    // console.log(hidden_val.val());
})

/* ------------------ submit form ------------------ */
// $(document).on('click',"#submit_aud_report",function(e){
//     $('#loadingmessage').show();

//     var fs_company_info_id = $('input[name=fs_company_info_id]').val();
//     var opinion_fixed      = $('.lbl_opinion_details').text();
//     var opinion_fixed_2    = $('.lbl_opinion_details_2').html();

//     var basic_for_opinion       = !($('#fs_aud_report_opinion').val() === '1')? $('.tarea_basic_for_opinion').val(): '';
//     var basic_for_opinion_fixed = $('.lbl_basic_for_opinion_fixed').html();

//     var key_audit_matter       = $('.lbl_key_aud_matter').text();
//     var key_audit_matter_input = ($('#fs_aud_report_opinion').val() === '1' || $('#fs_aud_report_opinion').val() === '2')? $('.tarea_desc_key_audit').val(): '';

//     var emphasis_of_matter = ($("input[name=emphasis_of_matter_checkbox]").val() === '1')? $('.emphasis_input').val(): '';
//     var other_matters      = ($("input[name=other_matter_checkbox]").val() === '1')? $('.lbl_other_matter').text(): '';
//     var disclaimer_of_opinion = ($("input[name=disclaimer_checkbox]").val() === '1')? $('.disclaimer_input').val(): '';

//     // console.log($("[name='other_matter_checkbox']").val());
//     if($("[name='other_matter_checkbox']").val() == 0)
//     {
//         $('input[name=ly_other_aud_checkbox]').val(0);
//     }
    
//     $.ajax({ //Upload common input
//         url: "financial_statement/submit_aud_report",
//         type: "POST",
//         data: $('form#form_audit_report').serialize() 
//                 + '&fs_company_info_id='      + fs_company_info_id 
//                 + '&opinion_fixed='           + opinion_fixed 
//                 + '&opinion_fixed_2='         + opinion_fixed_2 
//                 + '&basic_for_opinion='       + basic_for_opinion 
//                 + '&basic_for_opinion_fixed=' + basic_for_opinion_fixed 
//                 + '&key_audit_matter='        + key_audit_matter 
//                 + '&key_audit_matter_input='  + key_audit_matter_input
//                 + '&emphasis_of_matter='      + emphasis_of_matter 
//                 + '&other_matters='           + other_matters 
//                 + '&disclaimer_of_opinion='   + disclaimer_of_opinion,
//         dataType: 'json',
//         success: function (response,data) {
//             // console.log('hello');
//             // console.log(response);
//             // console.log(response["fs_company_info_id"]);

//             if((response['status'] === "success"))
//             {
//                 toastr.success("The data is saved to database.", "Successfully saved");
//             }
//             else
//             {
//                 toastr.error("Something went wrong. Please try again later.", "");
//             }

//             $('#loadingmessage').hide();
//         }
//     });
// });

/* ------------------ functions ------------------ */
function insert_label_opinion(element, isFirstLoad)
{
    var selected = $(element).val();
    var selected_text = $(element).children("option").filter(":selected").text();

    if(selected === 0 || selected === '')
    {
        selected = 1;
    }

    // $("label.lbl_opinion_details").html($("label.lbl_opinion_details").data(selected));
    $.ajax({
        type: "POST",
        url: "financial_statement/get_fs_company_info",
        data: { fs_company_info_id: $('input[name=fs_company_info_id]').val() },
        success: function(data){
            var data = JSON.parse(data);
            var group_type = data[0]["group_type"];
            var chosen_template = "";

            if(group_type != 1)
            {
                chosen_template = $("label.lbl_opinion_details").data('2');
            }
            else
            {
                chosen_template = $("label.lbl_opinion_details").data('1');
            }

            $.ajax({
                type: "POST",
                url: "financial_statement/replace_dynamic_content",
                async: false,
                data: { 
                    template: chosen_template,
                    choice: selected
                },
                success: function(output){
                    $("label.lbl_opinion_details").html(output);
                    $("label.lbl_opinion_details_2").html($("label.lbl_opinion_details_2").data(selected));
                    
                    if(isFirstLoad)
                    {
                        $('textarea.tarea_basic_for_opinion').val(this_independ_aud_report[0]["basic_for_opinion"]);
                    }
                    else
                    {
                       $('textarea.tarea_basic_for_opinion').val($(".section_basic_opinion_input").data(selected)); 
                    }
                    
                    $("label.lbl_basic_for_opinion_fixed").html($("label.lbl_basic_for_opinion_fixed").data(selected));
                }
            });
        }
    });

    if(selected !== '1')
    {
        $("div.basis_for_opinion").show();
        $("#lbl_basic_for_opinion").html("<strong>Basic for " + selected_text + " Opinion</strong>");
        $(".section_basic_opinion_input").show();

        $(".section_emphasis_of_matter").hide();
        $(".section_other_matters").hide();
        // $(".other_matter_1").hide();
        // $(".other_matter_2").hide();

        // if($("[name='hidden_emphasis_of_matter_checkbox']").bootstrapSwitch('state') == 0){
        //     // var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
        //     var emphasis_input = $(".emphasis_details");

        //     // hidden_val.val(0);
        //     emphasis_input.hide();
        // }
        // else
        // {
        //     // var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
        var emphasis_input = $(".emphasis_details");

        //     // hidden_val.val(1);
        //     emphasis_input.show();
        // }
      
        if($('[name="emphasis_of_matter_checkbox"]').val() == 0)
        {
            emphasis_input.hide();
        }
        else
        {
            emphasis_input.show();
        }
    }
    else
    {
        $("div.basis_for_opinion").show();
        $("#lbl_basic_for_opinion").html("<strong>Basic for Opinion</strong>");
        $(".section_basic_opinion_input").hide();

        $(".section_emphasis_of_matter").show();

        if(fs_company_info[0]['first_set'] == 0)
        {
            $(".section_other_matters").show();
        }

        var emphasis_input = $(".emphasis_details");

        //     // hidden_val.val(1);
        //     emphasis_input.show();
        // }
        if($('[name="emphasis_of_matter_checkbox"]').val() == 0)
        {

            emphasis_input.hide();
        }
        else
        {
            emphasis_input.show();
        }
        // $(".other_matter_1").show();
        // $(".other_matter_2").show();

        // if($("[name='hidden_emphasis_of_matter_checkbox']").bootstrapSwitch('state') == 0){
        //     // var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
        //     var emphasis_input = $(".emphasis_details");

        //     // hidden_val.val(0);
        //     emphasis_input.hide();
        // }
        // else
        // {
        //     // var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
        //     var emphasis_input = $(".emphasis_details");

        //     // hidden_val.val(1);
        //     emphasis_input.show();
        // }


    }

    var key_audit_matter_checkbox = $('[name="key_audit_matter_checkbox"]').val();

    // show/hide key audit matter
    if(selected == 4)
    {
        $('.section_key_audit_matter_checkbox').hide();
        $('.section_key_audit_matter').hide();  // "Disclaimer of opinion" does not have "key audit matter" section
        $('.section_key_audit_matter_input').hide();
    }
    else
    {
        $('.section_key_audit_matter_checkbox').show();
        $('.section_key_audit_matter').show();
    }

    console.log(key_audit_matter_checkbox);

    // show/hide key audit matter input
    if((selected == 1 || selected == 2) && !(key_audit_matter_checkbox == '0'))
    {
        $('.section_key_audit_matter').show();
        $('.section_key_audit_matter_input').show();
    }
    else if((selected == 3) && !(key_audit_matter_checkbox == '0'))
    {
        $('.section_key_audit_matter').show();
        $('.section_key_audit_matter_input').hide();
    }
    else
    {
        $('.section_key_audit_matter').hide();
        $('.section_key_audit_matter_input').hide();
    }

    insert_label_key_aud_matter(selected);
}

function insert_label_key_aud_matter(selected_id)
{
    $('.lbl_key_aud_matter').html($('.lbl_key_aud_matter').data(selected_id));
}

function update_other_matter_display_content()
{
    var ly_other_aud_checkbox = $("input[name=ly_other_aud_checkbox]").val();
    var ls_auditor_report_type = $('#fs_ly_report_opinion :selected').text();
    var date_of_auditors_report = $('#date_of_auditors_report').val();
    var content = $('.other_matter_details').find('.lbl_other_matter').data('2');

    if(ly_other_aud_checkbox === '1')
    {
        var vowelRegex = '^[aieouAIEOU].*';
        var matched = ls_auditor_report_type.match(vowelRegex);

        if(matched)
        {
            ls_auditor_report_type = 'an ' + ls_auditor_report_type.toLowerCase();
        }
        else
        {
            ls_auditor_report_type = 'a ' + ls_auditor_report_type.toLowerCase();
        }

        console.log(ls_auditor_report_type);

        if(ls_auditor_report_type != '')
        {
            if(ls_auditor_report_type === 'a disclaimer')
            {
                content = content.replace("{{Last Year Report Type}}", ls_auditor_report_type + " of");
            }
            else
            {
                content = content.replace("{{Last Year Report Type}}", ls_auditor_report_type);
            }
        }

        if(date_of_auditors_report != '')
        {
            content = content.replace("{{Date of Auditors Report Date}}", date_of_auditors_report);
        }

        $('.lbl_other_matter').html(content);
    }
}

/* -------- hide other matters checkbox -------- */
if(fs_company_info[0]['first_set'])
{
    $('.section_other_matters').hide();
    $('.other_matter_1').hide();
    $('.other_matter_2').hide();
    $('.other_matter_input').hide();
    $('.other_matter_input').hide();     
    $('.other_matter_details').hide();  

    $("#form_audit_report [name='hidden_other_matter_checkbox']").bootstrapSwitch('state', false);
    $('#form_audit_report input[name="other_matter_checkbox"]').val(0);
}
/* -------- END OF hide other matters checkbox -------- */
