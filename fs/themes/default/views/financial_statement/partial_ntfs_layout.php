<style type="text/css">
    .panel-group .panel {
        border-radius: 0;
        box-shadow: none;
        border-color: #EEEEEE;
    }

    .panel-default > .panel-heading {
        padding: 0;
        border-radius: 0;
        color: #212121;
        background-color: #FAFAFA;
        border-color: #EEEEEE;
    }

    .panel-title {
        font-size: 14px;
    }

    .panel-title > a {
        display: block;
        padding: 15px;
        text-decoration: none;
    }

    .more-less {
        float: right;
        color: #212121;
    }

    .panel-default > .panel-heading + .panel-collapse > .panel-body {
        border-top-color: #EEEEEE;
    }
</style>

<label><em style='font-size: 14pt;'>Notes to financial statements</em></label>

<p><br/>These notes from an integral part of and should be read in conjunction with the accompanying financial statements.</p>

<form id="form_ntfs_layout">
    <div class="layouts"></div>

    <!-- <div class="form-group">
        <div class="col-sm-12">
            <input type="button" class="btn btn-primary submit_ntfs_layout" id="submit_ntfs_layout" value="Save Number Arrangement" style="float: right; margin-bottom: 20px; margin-top: 20px;">
        </div>
    </div> -->
</form>

<div class="loading" id="loadingNTA" style="display: none;">Loading&#8230;</div>

<!-- <script src="themes/default/assets/js/financial_statement/partial_ntfs_layout.js" charset="utf-8"></script> -->

<script type="text/javascript">
    var layouts = '<?php echo json_encode($layouts); ?>';
    // var main_index = 1;
    // var sub_index = 1;
    // var roman_index = 1;

    // console.log(JSON.parse(layouts));
    // console.log(layouts);

    var return_data = display_on_collapse(JSON.parse(layouts));

    if(return_data['result'])
    {
        $('#loadingNTA').hide();
    }

    check_initial_checkbox();   // disable section if checkbox is unticked

    // JSON.parse(layouts).forEach(function(data) {
    //     console.log(data['parent_array']['parent']);
    //     if(data['parent_array']['parent'] == 0)
    //     {
    //         var return_data = display_on_collapse(data, main_index, sub_index, roman_index);

    //     //     main_index = return_data['main_index'];
    //     //     sub_index = return_data['sub_index'];
    //     //     roman_index = return_data['roman_index'];
    //     //     // console.log(display_on_collapse(data, main_index, sub_index, roman_index));
    //     }
    // });

    function toggleIcon(e) {
        // console.log(e);
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);


    /* ------------------- DO NOT DELETE THIS PART ------------------- */
    // // allow drag and drop, then later re-arrange the index
    // $(".dnd").sortable({
    //     connectWith: ".dnd",
    //     handle: ".portlet-header",
    //     cancel: ".portlet-toggle",
    //     placeholder: "drop-placeholder",
    //     stop: function(event, ui){

    //         $(ui.item).find('portlet-header').click();
    //         var sortorder='';

    //         $('.dnd').each(function(){
    //             var itemorder = $(this).sortable('toArray');
    //             var sub_category_list = $(this).find('.sub_category');

    //             if(sub_category_list[0] !== "undefined")    // re-arrange sub index number
    //             {
    //                 get_sub_list_rearrange_index(sub_category_list, 1);
    //             }
    //         });
    //     }
    // });

    // // dnd for main category eg. 1. 2. 3. ...
    // $(".layouts").sortable({
    //     connectWith: ".layouts",
    //     handle: ".main_portlet-header",
    //     // cancel: ".main_portlet-toggle",
    //     placeholder: "placeholder",
    //     stop: function(event, ui){

    //         $(ui.item).find('main_portlet-header').click();
    //         var sortorder='';

    //         // console.log("drag and drop main");

    //         $('.layouts').each(function(){
    //             var itemorder = $(this).sortable('toArray');
    //             var main_category_list = $(this).find('.main_category');

    //             // console.log(main_category_list);
    //             if(main_category_list[0] !== "undefined")    // re-arrange sub index number
    //             {
    //                 get_sub_list_rearrange_index(main_category_list, 0);
    //             }
    //         });
    //     }
    // });
    /* ------------------- END OF DO NOT DELETE THIS PART ------------------- */
</script>
