
<div class="box" style="margin-bottom: 30px;margin-top: 30px;">
    <!-- <div class="box-header" style="height:54px;">
        <h2 class="blue"><i class="fa-fw fa fa-user"></i><?= lang('Our Firm'); ?></h2>
    </div> -->
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <div style="margin-bottom: 20px;">
                    <span class="help-block">
                        * Would you like to delete all transaction subsequent to this transaction you are about to delete? <a href="javascript:void(0)" class="confirm_delete" id="confirm_delete">YES</a> / <a href="javascript:void(0)" class="cancel_delete" id="cancel_delete">NO</a>
                    </span>
                </div>
                <div id="register_table">
                                                                    
                </div>
            </div>
        </div>   
    </div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
    var member = <?php echo json_encode($member);?>;
    var certificate_no = <?php echo json_encode($certificate_no);?>;
    var company_code = <?php echo json_encode($company_code);?>;
    var client_member_share_capital_id = <?php echo json_encode($client_member_share_capital_id);?>;
    var officer_id = <?php echo json_encode($officer_id);?>;
    var field_type = <?php echo json_encode($field_type);?>;
    var transaction_type = <?php echo json_encode($transaction_type);?>;
    var each_transfer = <?php echo json_encode($each_transfer);?>;
    //var cert_info = <?php echo json_encode($cert_info);?>;
    var trans_date = null;
    //console.log(cert_info);
    if(member)
    {
        var member_info = member;
    }
    else
    {
        var member_info = '';
    }

    toastr.options = {
      "positionClass": "toast-bottom-right"
    }

    $('.confirm_delete').on("click",function(e){
        e.preventDefault();
        bootbox.confirm("Are you sure you want to delete all transaction subsequent to this?", function (result) {
            if (result) {
               $('#loadingmessage').show();
                $.ajax({ //Upload common input
                        url: "masterclient/delete_subsequent_allotment",
                        type: "POST",
                        data: {"certificate_no": certificate_no, "company_code": company_code, "client_member_share_capital_id": client_member_share_capital_id, "officer_id": officer_id, "field_type": field_type, "transaction_type": transaction_type, "each_transfer": each_transfer},
                        dataType: 'json',
                        success: function (response) {
                            $('#loadingmessage').hide();
                            //console.log(response);
                            toastr.success(response.message, response.title);
                            //window.close();
                            window.opener.location.reload();
                            window.close();
                        }
                    });
            }
            /*else
            {
                window.close();
            }*/
        });
    })

    $('.cancel_delete').on("click",function(e){
        e.preventDefault();
        window.close();
    })


    function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    $a = "";
    $a += '<div id="register_member" style="margin-top:20px;">';
    $a += '<h3>Member</h3>';
    $a += '<table class="table table-bordered table-striped mb-none register_member_table" id="register_table_member" style="width:100%;">';
    //$a += '<table style="border:1px solid black" class="allotment_table" id="register_filing_table">';
    $a += '<thead><tr>'; 
    $a += '<th style="text-align:center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No</th>';
    $a += '<th style="word-break:break-all;text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">ID</th>'; 
    $a += '<th style="word-break:break-all;text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Name</th>'; 
    $a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Date</th>';
    $a += '<th style="text-align: center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">Transaction Type</th>';
    $a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Class</th>';
    $a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>';
    $a += '<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Movement in Number of Shares</th>'; 
    $a += '<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Balance No of Shares</th>';
    /*$a += '<th style="text-align: center">Amount of Shares Issued</th>'; 
    $a += '<th style="text-align: center">Number of Shares Paid Up</th>';
    $a += '<th style="text-align: center">Amount of Shares Paid Up</th>';*/
    $a += '<th style="text-align: center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">Certificate</th>';
    $a += '</tr></thead>';
    $a += '</table>';
    $a += '</div>';
    $("#register_table").append($a);

    if(member_info.length > 0)
    {
        var id = null, balance = 0;
        for(var i = 0; i < member_info.length; i++)
        {
            if(certificate_no == member_info[i]["certificate_no"])
            {
                //console.log(member_info[i]["trans_date"]);
                trans_date = member_info[i]["trans_date"];
            }

            if(member_info[i]["sharetype"] == "Others")
            {
                var sharetype = "(" +member_info[i]["other_class"]+ ")" ;
            }
            else
            {
                var sharetype = "";
            }

            if(id == null)
            {
                if(member_info[i]["identification_no"] != null)
                {
                    id = member_info[i]["identification_no"];
                    balance = parseInt(member_info[i]["number_of_share"]);
                }
                else
                {
                    id = member_info[i]["register_no"];
                    balance = parseInt(member_info[i]["number_of_share"]);
                }
            }
            else
            {
                /*console.log(member_info[i]["identification_no"]);
                console.log(id);*/
                if(member_info[i]["identification_no"] == id)
                {
                    balance += parseInt(member_info[i]["number_of_share"]);
                }
                else if(member_info[i]["register_no"] == id)
                {
                    balance += parseInt(member_info[i]["number_of_share"]);
                }
                else
                {
                    if(member_info[i]["identification_no"] != null)
                    {
                        id = member_info[i]["identification_no"];
                        balance = parseInt(member_info[i]["number_of_share"]);
                    }
                    else
                    {
                        id = member_info[i]["register_no"];
                        balance = parseInt(member_info[i]["number_of_share"]);
                    }
                }
            }

            var parts =member_info[i]["transaction_date"].split('/');
            var date = parts[2]+"/"+parts[1]+"/"+parts[0];
            var mydate1 = $.datepicker.formatDate('dd M yy',new Date(date)); 


            $b=""; 
            if(member_info[i]["trans_date"] > trans_date && trans_date != null)
            {
                $b += '<tr class="member_info_for_each_company" style="color:red">';
            }
            else
            {
                $b += '<tr class="member_info_for_each_company">';
            }

            $b += '<td style="text-align: right;width:10px">'+(i+1)+'</td>';

            $b += '<td>'+((member_info[i]["identification_no"] != null)?member_info[i]["identification_no"] : member_info[i]["register_no"])+'</td>';
            $b += '<td>'+((member_info[i]["name"] != null)?member_info[i]["name"] : member_info[i]["company_name"])+'</td>';
            
            $b += '<td>'+mydate1+'</td>';
            $b += '<td>'+member_info[i]["transaction_type"]+'</td>';
            $b += '<td>'+(member_info[i]["sharetype"] + " " + sharetype)+'</td>';
            $b += '<td>'+member_info[i]["currency"]+'</td>';
            $b += '<td style="text-align:right">'+addCommas(member_info[i]["number_of_share"])+'</td>';
            $b += '<td style="text-align:right">'+addCommas(balance)+'</td>';
            // $b += '<td style="text-align:right">'+addCommas(member_info[i]["amount_share"])+'</td>';
            // $b += '<td style="text-align:right">'+addCommas(member_info[i]["no_of_share_paid"])+'</td>';
            // $b += '<td style="text-align:right">'+addCommas(member_info[i]["amount_paid"])+'</td>';
            $b += '<td>'+member_info[i]["certificate_no"]+'</td>';


            $b += '</tr>';

            $(".register_member_table").append($b);
        }
        /*$('#register_table_member').DataTable({"paging": false,});
        $('#register_member .datatables-header').hide();
        $('#register_member .datatables-footer').hide();*/
    }
    else
    {
        $b=""; 
        $b += '<tr class="member_info_for_each_company">';
        $b += '<td colspan="10" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
        $b += '</tr>';

        $(".register_member_table").append($b);
    }
</script>
