<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<section role="main" class="content_section">

    <div class="panel-body">
        <div class="col-md-12">
            <div style="padding-top: 10px;padding-left: 15px; display: inline-block;">
                <table class="allotment_table" style="width:30%;float:left;" >
                    <tr>
                        <td class="text-left"><strong>Assignment No : </strong></td>
                        <td class="text-left"><?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><strong>Client Name : </strong></td>
                        <td class="text-left"><?php echo isset($assignment_details)?$assignment_details[0]->client_name:'' ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><strong>Budget Hours : </strong></td>
                        <td class="text-left"><?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?> hour(s)</td>
                    </tr>
                    <tr>
                        <td class="text-left"><strong>Actual Hours : </strong></td>
                        <td class="text-left">
                            <table style="width:100%;">
                            <?php 
                                        echo '<tr>';
                                        echo '<td style="width:65%;">';
                                        echo json_decode($assignment_details[0]->PIC)->leader;
                                        echo '</td>';
                                        echo '<td style="width:35%;"><span class="leader"></span>  hour(s)</td>';
                                        echo '</tr>';

                                        $assistant_key = 0;

                                foreach (json_decode($assignment_details[0]->PIC)->assistant as $key => $assistant)
                                {
                                    if(json_decode($assignment_details[0]->PIC)->leader != $assistant)
                                    {
                                        echo '<tr>';
                                        echo '<td style="width:65%;">';
                                        echo $assistant;
                                        echo '</td>';
                                        echo '<td style="width:35%;"><span class="assistant'.$assistant_key.'"></span>  hour(s)</td>';
                                        echo '</td>';
                                        echo '</tr>';

                                        $assistant_key ++ ;
                                    }
                                }

                            ?>
                            </table>  
                        </td>
                    </tr>
                </table>
                <div style="padding-top: 15px;float:right;padding-right: 15px;">
                    <button id="view_priorYear" class="btn btn_purple hidden" onclick="view_priorYear()">View Prior Year Data</button>
                    <button class="btn btn_purple" onclick="exportDocumentPDF()">View Report</button>
                </div>
    
            </div>

            <hr>

            <div style="padding-bottom: 25px">
                <label for="reportType">Report Type :</label>
                <select id="reportType" class="reportType">
                    <option value="audit">Audit</option>
                    <option value="accounting">Accounting</option>
                    <option value="payroll">Payroll</option>
                    <option value="tax">Tax</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <div class="tabs">
                <ul class="nav nav-tabs nav-justify">
                    <li class="check_state active" data-information="in_progress" style="width: 25%">
                        <a href="#w2-budget" data-toggle="tab" class="text-center">
                            <b>Budget</b>
                        </a>
                    </li>
                    <li class="check_state" data-information="completed" style="width: 25%">
                        <a href="#w2-actual" data-toggle="tab" class="text-center">
                            <b>Actual</b>
                        </a>
                    </li>
                    <li class="check_state" data-information="completed" style="width: 25%">
                        <a href="#w2-others" data-toggle="tab" class="text-center">
                            <b>Others</b>
                        </a>
                    </li>
                </ul>

                <div class="tab-content clearfix">
                    <div id="w2-budget" class="tab-pane active">
                        <table class="allotment_table budget_table" style="width:100%;">
                            <tbody id="tablebody_budget"></tbody>
                        </table>

                        <div style="padding-top: 10px;text-align: right;">
                            <button id="submit_budget_hour" class="btn btn_purple">Save</button>
                            <?php 
                                echo '<a href="'.base_url().'budget_hours/index" class="btn btn_cancel">Cancel</a>';
                            ?>
                        </div>
                    </div>

                    <div id="w2-actual" class="tab-pane">
                        <table class="allotment_table actual_table" id="" style="width:100%;" >
                            <tbody id="tablebody_acutal"></tbody>
                        </table>

                        <div style="padding-top: 10px;text-align: right;">
                            <button id="submit_actual_hour" class="btn btn_purple">Save</button>
                            <?php 
                                echo '<a href="'.base_url().'budget_hours/index" class="btn btn_cancel">Cancel</a>';
                            ?>
                        </div>
                    </div>

                    <div id="w2-others" class="tab-pane">
                        <table class="allotment_table others_table" id="" style="width:100%;" >
                            <tr>
                                <td class="text-left" style="height:46px;"></td>
                                <td class="text-left" style="height:46px;text-align: center;"><b>Budget Hours</b></td>
                                <td class="text-left" style="height:46px;text-align: center;"><b>Actual Hours</b></td>
                                <td class="text-left" style="height:46px;text-align: center;"><b>Rate S$</b></td>
                            </tr>
                            <tr>
                                <td class="text-left" style="width:20%;height:46px;">Review and Supervision</td>
                                <td class="text-left" style=";text-align: center;">
                                    <!-- <input type="number" id="rns_Bhrs" class="form-control budgetCheck" min="1" style="text-align: center;" onchange="budget_hours_check(this)"/> -->
                                    <input type="number" id="rns_Bhrs" class="form-control" min="1" style="text-align: center;" onchange="budget_hours_check(this)"/>
                                </td>
                                <td class="text-left" style=";text-align: center;">
                                    <input type="number" id="rns_Ahrs" class="form-control" min="1" style="text-align: center;"/>
                                </td>
                                <td class="text-left" style=";text-align: center;">
                                    <input type="number" id="rns_rate" class="form-control" min="1" style="text-align: center;"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="width:20%;height:46px;">Partner Review</td>
                                <td class="text-left" style=";text-align: center;">
                                    <!-- <input type="number" id="pr_Bhrs" class="form-control budgetCheck" min="1" style="text-align: center;" onchange="budget_hours_check(this)"/> -->
                                    <input type="number" id="pr_Bhrs" class="form-control" min="1" style="text-align: center;" onchange="budget_hours_check(this)"/>
                                </td>
                                <td class="text-left" style=";text-align: center;">
                                    <input type="number" id="pr_Ahrs" class="form-control" min="1" style="text-align: center;"/>
                                </td>
                                <td class="text-left" style=";text-align: center;">
                                    <input type="number" id="pr_rate" class="form-control" min="1" style="text-align: center;"/>
                                </td>
                            </tr>
                             <tr>
                                <td class="text-left" style=";text-align: center;height:46px;background:#fafafa;" colspan="4"></td>
                            </tr>
                            <tr>
                                <td class="text-left" style=";text-align: center;height:46px;"></td>
                                <td class="text-left" style="height:46px;text-align: center;"><b>Budget Cost S$</b></td>
                                <td class="text-left" style="height:46px;text-align: center;"><b>Actual Cost S$</b></td>
                                <td class="text-left" style="height:46px;text-align: center;"></td>
                            </tr>
                            <tr>
                                <td class="text-left" style="width:20%;height:46px;">Fees Raised</td>
                                <td class="text-left" style="text-align: center;">
                                    <input type="number" id="Bfees_raised" class="form-control" min="1" style="text-align: center;"/>
                                </td>
                                <td class="text-left" style="text-align: center;">
                                    <input type="number" id="Afees_raised" class="form-control" min="1" style="text-align: center;"/>
                                </td>
                                <td class="text-left" style="text-align: center;"></td>
                            </tr>
                            <tr>
                                <td class="text-left" style=";text-align: center;height:46px;background:#fafafa;" colspan="4"></td>
                            </tr>
                            <tr>
                                <td class="text-left" style="width:20%;height:46px;">Explanations for Variance</td>
                                <td class="text-left" style=";text-align: center;height:46px;" colspan="2">
                                    <textarea type="text" id="explanations_for_variance" class="form-control"/></textarea>
                                </td>
                                <td class="text-left" style="height:46px;text-align: center;"></td>
                            </tr>
                        </table>

                        <div style="padding-top: 10px;text-align: right;">
                            <button id="submit_others" class="btn btn_purple">Save</button>
                            <?php 
                                echo '<a href="'.base_url().'budget_hours/index" class="btn btn_cancel">Cancel</a>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div id="PYA" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
    <div class="modal-dialog" style="width: 1000px !important;">
        <div class="modal-content">
            <header class="panel-heading">
                <h2 class="panel-title">Prior Year Actual</h2>
            </header>
            <div class="panel-body">
                <div class="col-md-12">
                    <table class="allotment_table PYA" id="" style="width:100%;" >
                        <tbody id="tablebody_PYA"></tbody>
                    </table>

                    <table class="allotment_table prior_others_table" id="" style="width:100%;" >
                        <tr>
                            <td class="text-left" style="height:46px;"></td>
                            <td class="text-left" style="height:46px;text-align: center;"><b>Actual Hours</b></td>
                            <td class="text-left" style="height:46px;text-align: center;"><b>Rate S$</b></td>
                        </tr>
                        <tr>
                            <td class="text-left" style="width:20%;height:46px;">Review and Supervision</td>
                            <td class="text-left" style=";text-align: center;">
                            <input type="number" id="prior_rns_Ahrs" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                            <td class="text-left" style=";text-align: center;">
                                <input type="number" id="prior_rns_rate" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" style="width:20%;height:46px;">Partner Review</td>
                            <td class="text-left" style=";text-align: center;">
                            <input type="number" id="prior_pr_Ahrs" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                            <td class="text-left" style=";text-align: center;">
                                <input type="number" id="prior_pr_rate" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                        </tr>
                         <tr>
                            <td class="text-left" style=";text-align: center;height:46px;background:#fafafa;" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="text-left" style=";text-align: center;height:46px;"></td>
                            <td class="text-left" style="height:46px;text-align: center;"><b>Actual Cost S$</b></td>
                            <td class="text-left" style="height:46px;text-align: center;"></td>
                        </tr>
                        <tr>
                            <td class="text-left" style="width:20%;height:46px;">Fees Raised</td>
                            <td class="text-left" style="text-align: center;">
                                <input type="number" id="prior_Afees_raised" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                            <td class="text-left" style="text-align: center;"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id ="submit_PYA" class="btn btn_purple" >Save</button>
                <input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
            </div>
        </div>
    </div>
</div>

<div id="PYA_RATE" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
    <div class="modal-dialog" style="width: 1000px !important;">
        <div class="modal-content">
            <header class="panel-heading">
                <h2 class="panel-title">Prior Year Actual</h2>
            </header>
            <div class="panel-body">
                <div class="col-md-12">
                    <table class="allotment_table PYA_RATE" id="" style="width:100%;" >
                        <tbody>
                            <tr>
                                <td class="text-left" style="width:20%;">
                                    <strong style="color: #d50000">Rate S$</strong>
                                </td>
                                <td class="text-left" style="text-align: center;width:20%;">
                                    <input type="number" id="PYA_RATE_rate" class="form-control" min="1" style="text-align: center;" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id ="submit_PYA_RATE" class="btn btn_purple" >Save</button>
                <input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
            </div>
        </div>
    </div>
</div>

<div id="PYA2" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
    <div class="modal-dialog" style="width: 1000px !important;">
        <div class="modal-content">
            <header class="panel-heading">
                <h2 class="panel-title">Prior Year Actual</h2>
            </header>
            <div class="panel-body">
                <div class="col-md-12">
                    <table class="allotment_table PYA2" id="" style="width:100%;" >
                        <tbody id="tablebody_PYA2"></tbody>
                    </table>

                    <table class="allotment_table prior_others_table" id="" style="width:100%;" >
                        <tr>
                            <td class="text-left" style="height:46px;"></td>
                            <td class="text-left" style="height:46px;text-align: center;"><b>Actual Hours</b></td>
                            <td class="text-left" style="height:46px;text-align: center;"><b>Rate S$</b></td>
                        </tr>
                        <tr>
                            <td class="text-left" style="width:20%;height:46px;">Review and Supervision</td>
                            <td class="text-left" style=";text-align: center;">
                            <input type="number" id="prior_rns_Ahrs2" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                            <td class="text-left" style=";text-align: center;">
                                <input type="number" id="prior_rns_rate2" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" style="width:20%;height:46px;">Partner Review</td>
                            <td class="text-left" style=";text-align: center;">
                            <input type="number" id="prior_pr_Ahrs2" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                            <td class="text-left" style=";text-align: center;">
                                <input type="number" id="prior_pr_rate2" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                        </tr>
                         <tr>
                            <td class="text-left" style=";text-align: center;height:46px;background:#fafafa;" colspan="4"></td>
                        </tr>
                        <tr>
                            <td class="text-left" style=";text-align: center;height:46px;"></td>
                            <td class="text-left" style="height:46px;text-align: center;"><b>Actual Cost S$</b></td>
                            <td class="text-left" style="height:46px;text-align: center;"></td>
                        </tr>
                        <tr>
                            <td class="text-left" style="width:20%;height:46px;">Fees Raised</td>
                            <td class="text-left" style="text-align: center;">
                                <input type="number" id="prior_Afees_raised2" class="form-control" min="1" style="text-align: center;"/>
                            </td>
                            <td class="text-left" style="text-align: center;"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id ="submit_PYA2" class="btn btn_purple" >Save</button>
                <input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
            </div>
        </div>
    </div>
</div>

<div id="PYA_RATE2" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
    <div class="modal-dialog" style="width: 1000px !important;">
        <div class="modal-content">
            <header class="panel-heading">
                <h2 class="panel-title">Prior Year Actual</h2>
            </header>
            <div class="panel-body">
                <div class="col-md-12">
                    <table class="allotment_table PYA_RATE2" id="" style="width:100%;" >
                        <tbody>
                            <tr>
                                <td class="text-left" style="width:20%;">
                                    <strong style="color: #d50000">Rate S$</strong>
                                </td>
                                <td class="text-left" style="text-align: center;width:20%;">
                                    <input type="number" id="PYA_RATE_rate2" class="form-control" min="1" style="text-align: center;" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id ="submit_PYA_RATE2" class="btn btn_purple" >Save</button>
                <input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
            </div>
        </div>
    </div>
</div>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script>
    // VALUE INITIAL -----------------------------------------------------------------------------------------------------------------------------------------------
    var PIC                 = <?php echo json_encode(isset($assignment_details)?$assignment_details[0]->PIC:'') ?>;
    var saved_budget        = <?php echo json_encode(isset($budget[0]->budget)?$budget[0]->budget:'') ?>;
    var saved_actual_budget = <?php echo json_encode(isset($actual_budget[0]->actual)?$actual_budget[0]->actual:'') ?>;
    var saved_rns           = <?php echo json_encode(isset($others[0]->review_and_supervision)?$others[0]->review_and_supervision:'') ?>;
    var saved_pr            = <?php echo json_encode(isset($others[0]->partner_review)?$others[0]->partner_review:'') ?>;
    var saved_fees_raised   = <?php echo json_encode(isset($others[0]->fees_raised)?$others[0]->fees_raised:'') ?>;
    var saved_variance      = <?php echo json_encode(isset($others[0]->variance)?$others[0]->variance:'') ?>;
    var report_type         = <?php echo json_encode(isset($report_type[0]->report_type)?$report_type[0]->report_type:'') ?>;
    var assistant           = JSON.parse(PIC)['assistant'];
    var leader              = JSON.parse(PIC)['leader'];
    // var budget_tr           = $(".budget_table tr");
    // var actual_tr           = $(".actual_table tr");
    var pic_num;
    var log_1 = true;
    var log_2 = true;
    var log_3 = true;

    var audit_activities = ["Final Completion","Audit Completion","Audit Planing","General Audit Procedures","Revenue","Cost of Sales","Other income and expense","Inventories","Trade And Other Receivables","Cash And Bank Balances","Propert, Plant And Equipment","Investment Properties","Intangible Assets","Equity Investments","Share Capital And Reserves","Borrowings And Finance Lease","Trade And Other Payables","Current And Deferred Tax","Goods And Services Tax","Construction Contracts","Provision And Contingent Liabilities","Leases And Capital Commitment","Related Parties Transactions","Group Audit","Interest Rate & Forex Risk","Cashflows","Search for unrecorded liabilities","Draft Report","Clear review points","Stock take"];

    var accounting_activities = ['Book Keeping','Draft Management Accounts','Draft Compilation Report','XBRL Preparation'];

    var payroll_activities =['CPF Application','CPF Submission','Work Pass Application & Cancellation','Tax Clearance'];

    var tax_activities =['GST Application','GST Submission','Estimated Tax Computation','Final Tax Computation','Tax Submission','Tax Queries','Withholding Tax Submission'];

    var others_activities =['AUP Verification','Draft Report','Corppass Creation','Letter Collection','Other Administrative Matters'];

    var data_from = '';

    $("#reportType").select2();

    $(document).ready(function () {
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';

        $.ajax({
            type: "POST",
            'async' : false,
            url: "<?php echo site_url('budget_hours/priorYear_Data_Check'); ?>",
            data: '&client_id=' + client_id + '&assignment_id=' + assignment_id + '&fye=' + fye + '&type_of_job=' + type_of_job,
            dataType: "json",
            success: function(data)
            {
                if(data != null)
                {
                    data_from = data;
                    $("#view_priorYear").removeClass("hidden");
                }
            }               
        });
    });

    $(document).ready(function () {
        $("#reportType").change(function(e){

            var budget_table = $(".budget_table");
            var actual_table = $(".actual_table");
            var type         = $( "#reportType" ).val();
            pic_num          = 1;

            if(type == 'audit')
            {
                $('#tablebody_budget').remove();
                $('#tablebody_acutal').remove();

                budget_table.append('<tbody id="tablebody_budget"><tr><td class="text-left" style="height:46px;"> </td></tr>');
                actual_table.append('<tbody id="tablebody_acutal"><tr><td class="text-left" style="height:46px;"> </td></tr>');

                for(var a=0 ; a<audit_activities.length ; a++)
                {
                    budget_table.append('<tr><td class="text-left" style="width:20%;">'+audit_activities[a]+'</td></tr>');
                    actual_table.append('<tr><td class="text-left" style="width:20%;">'+audit_activities[a]+'</td></tr>');
                }

                budget_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
                actual_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
            }
            else if(type == 'accounting')
            {
                $('#tablebody_budget').remove();
                $('#tablebody_acutal').remove();

                budget_table.append('<tbody id="tablebody_budget"><tr><td class="text-left" style="height:46px;"> </td></tr>');
                actual_table.append('<tbody id="tablebody_acutal"><tr><td class="text-left" style="height:46px;"> </td></tr>');

                for(var a=0 ; a<accounting_activities.length ; a++)
                {
                    budget_table.append('<tr><td class="text-left" style="width:20%;">'+accounting_activities[a]+'</td></tr>');
                    actual_table.append('<tr><td class="text-left" style="width:20%;">'+accounting_activities[a]+'</td></tr>');
                }

                budget_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
                actual_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
            }
            else if(type == 'payroll')
            {
                $('#tablebody_budget').remove();
                $('#tablebody_acutal').remove();

                budget_table.append('<tbody id="tablebody_budget"><tr><td class="text-left" style="height:46px;"> </td></tr>');
                actual_table.append('<tbody id="tablebody_acutal"><tr><td class="text-left" style="height:46px;"> </td></tr>');

                for(var a=0 ; a<payroll_activities.length ; a++)
                {
                    budget_table.append('<tr><td class="text-left" style="width:20%;">'+payroll_activities[a]+'</td></tr>');
                    actual_table.append('<tr><td class="text-left" style="width:20%;">'+payroll_activities[a]+'</td></tr>');
                }

                budget_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
                actual_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
            }
            else if(type == 'tax')
            {
                $('#tablebody_budget').remove();
                $('#tablebody_acutal').remove();

                budget_table.append('<tbody id="tablebody_budget"><tr><td class="text-left" style="height:46px;"> </td></tr>');
                actual_table.append('<tbody id="tablebody_acutal"><tr><td class="text-left" style="height:46px;"> </td></tr>');

                for(var a=0 ; a<tax_activities.length ; a++)
                {
                    budget_table.append('<tr><td class="text-left" style="width:20%;">'+tax_activities[a]+'</td></tr>');
                    actual_table.append('<tr><td class="text-left" style="width:20%;">'+tax_activities[a]+'</td></tr>');
                }

                budget_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
                actual_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
            }
            else if(type == 'others')
            {
                $('#tablebody_budget').remove();
                $('#tablebody_acutal').remove();

                budget_table.append('<tbody id="tablebody_budget"><tr><td class="text-left" style="height:46px;"> </td></tr>');
                actual_table.append('<tbody id="tablebody_acutal"><tr><td class="text-left" style="height:46px;"> </td></tr>');

                for(var a=0 ; a<others_activities.length ; a++)
                {
                    budget_table.append('<tr><td class="text-left" style="width:20%;">'+others_activities[a]+'</td></tr>');
                    actual_table.append('<tr><td class="text-left" style="width:20%;">'+others_activities[a]+'</td></tr>');
                }

                budget_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
                actual_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td></tr></tbody>');
            }


            var budget_tr = $(".budget_table tr");
            var actual_tr = $(".actual_table tr");

            var budget_length = budget_tr.length - 1;
            var actual_length = actual_tr.length - 1;

            // BUDGET TAB ------------------------------------------------------------------------------------------------------------------------------------------
            // LEADER COL
            budget_tr.each(function(index) 
            {
                if(index == '0'){
                    var td = '<td class="text-left" style="width:auto;text-align: center;"><b>'+leader+'</b></td>';
                    $(this).append(td);
                }
                else if(index == budget_length){
                    var td = '<td class="text-left" style=";text-align: center;height:46px;"><label id="colTotal">0</label></td>';
                    $(this).append(td);
                }
                else{
                    var td = '<td class="text-left" style=";text-align: center;"><input type="number" id="cell" class="form-control budgetCheck" min="1" style="text-align: center;" onchange="budget_hours_check(this); input(this);" /></td>';
                    $(this).append(td);
                }
            });

            // ASSISTANT COL
            for($i=0 ; $i<assistant.length ; $i++){

                if(assistant[$i] != leader){

                    pic_num ++;

                    budget_tr.each(function(index) {

                        if(index == '0'){
                            var td = '<td class="text-left" style="width:auto;text-align: center;"><b>'+assistant[$i]+'</b></td>';
                            $(this).append(td);
                        }
                        else if(index == budget_length){
                            var td = '<td class="text-left" style=";text-align: center;height:46px;"><label id="colTotal">0</label></td>';
                            $(this).append(td);
                        }
                        else{
                            var td = '<td class="text-left" style="text-align: center;"><input type="number" id="cell" class="form-control budgetCheck" min="1" style="text-align: center;" onchange="budget_hours_check(this); input(this);"/></td>';
                            $(this).append(td);
                        }
                    });
                }
            }

            // Actual TAB ------------------------------------------------------------------------------------------------------------------------------------------
            // LEADER COL
            actual_tr.each(function(index) {

                if(index == '0'){
                    var td = '<td class="text-left" style="width:auto;text-align: center;"><b>'+leader+'</b></td>';
                    $(this).append(td);
                }
                else if(index == actual_length){
                    var td = '<td class="text-left" style=";text-align: center;height:46px;"><label id="colTotal">0</label></td>';
                    $(this).append(td);
                }
                else{
                    var td = '<td class="text-left" style="text-align: center;"><input type="number" id="cell" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td>';
                    $(this).append(td);
                }
            });

            // ASSISTANT COL
            for($i=0 ; $i<assistant.length ; $i++){

                if(assistant[$i] != leader){

                    actual_tr.each(function(index) {

                        if(index == '0'){
                            var td = '<td class="text-left" style="width:auto;text-align: center;"><b>'+assistant[$i]+'</b></td>';
                            $(this).append(td);
                        }
                        else if(index == actual_length){
                            var td = '<td class="text-left" style=";text-align: center;height:46px;"><label id="colTotal">0</label></td>';
                            $(this).append(td);
                        }
                        else{
                            var td = '<td class="text-left" style=";text-align: center;"><input type="number" id="cell" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td>';
                            $(this).append(td);
                        }
                    });
                }
            }

            // Total Col -------------------------------------------------------------------------------------------------------------------------------------------
            pic_num ++;
            budget_tr.each(function(index) 
            {
                if(index == '0'){
                    var td = '<td class="text-left" style="width:auto%;text-align: center;"><b>Total</b></td>';
                    $(this).append(td);
                }
                else if(index == budget_length){
                    var td = '<td class="text-left" style=";text-align: center;"></td>';
                    $(this).append(td);
                }
                else{
                    var td = '<td id="'+index+'" class="text-left" style="text-align: center;"><label id="rowTotal">0</label></td>';
                    $(this).append(td);
                }
            });

            actual_tr.each(function(index) 
            {
                if(index == '0'){
                    var td = '<td class="text-left" style="width:auto;text-align: center;"><b>Total</b></td>';
                    $(this).append(td);
                }
                else if(index == actual_length){
                    var td = '<td class="text-left" style=";text-align: center;"></td>';
                    $(this).append(td);
                }
                else{
                    var td = '<td id="'+index+'" class="text-left" style="text-align: center;"><label id="rowTotal">0</label></td>';
                    $(this).append(td);
                }
            });

        });

    if(report_type == "")
    {
        $(".reportType").val('audit').trigger('change');
    }
    else
    {
        $(".reportType").val(report_type).trigger('change');
        document.getElementById('reportType').disabled = "true";
    }

    });

    $(document).ready(function () {

        // LOAD SAVED DATA (BUDGET TAB) ----------------------------------------------------------------------------------------------------------------------------
        if(saved_budget != "")
        {
            var data = JSON.parse(saved_budget);
            var table = $(".budget_table");

            for($i=0;$i<data.length-1;$i++)
            {
                if(data[$i].length == 31) {
                    data[$i].splice(30, 0, "");
                }

                for($n=0;$n<data[$i].length;$n++)
                {
                    if(data[$i][$n] != "")
                    {
                        if($n != 0 || $n != (data[$i].length -1))
                        {
                            $a = $i + 1;
                            table.find('tr:eq('+$n+')').find('td:eq('+$a+')').children().val(data[$i][$n]);
                            input(table.find('tr:eq('+$n+')').find('td:eq('+$a+')').children());
                        }
                    }
                }
            }
        }

        // LOAD SAVED DATA (ACTUAL BUDGET TAB) ---------------------------------------------------------------------------------------------------------------------
        if(saved_actual_budget != "")
        {
            var data = JSON.parse(saved_actual_budget);
            var table = $(".actual_table");

            for($i=0;$i<data.length-1;$i++)
            {
                if(data[$i].length = 31) {
                    data[$i].splice(30, 0, "");
                }

                for($n=0;$n<data[$i].length;$n++)
                {
                    if(data[$i][$n] != "")
                    {
                        if($n != 0 || $n != (data[$i].length -1))
                        {
                            $a = $i + 1;
                            table.find('tr:eq('+$n+')').find('td:eq('+$a+')').children().val(data[$i][$n]);
                            input(table.find('tr:eq('+$n+')').find('td:eq('+$a+')').children());
                        }
                    }
                }
            }
        }

        // LOAD SAVED DATA (REVIEW AND SUPERVISION) ----------------------------------------------------------------------------------------------------------------
        if(saved_rns != "")
        {
            result = JSON.parse(saved_rns);

            $('#rns_Bhrs').val(result[0]);
            $('#rns_Ahrs').val(result[1]);
            $('#rns_rate').val(result[2]);
        }

        // LOAD SAVED DATA (PARTNER REVIEW) ------------------------------------------------------------------------------------------------------------------------
        if(saved_pr != "")
        {
            result = JSON.parse(saved_pr);

            $('#pr_Bhrs').val(result[0]);
            $('#pr_Ahrs').val(result[1]);
            $('#pr_rate').val(result[2]);
        }

        // LOAD SAVED DATA (FEES RAISED) ---------------------------------------------------------------------------------------------------------------------------
        if(saved_fees_raised != "")
        {
            result = JSON.parse(saved_fees_raised);

            $('#Bfees_raised').val(result[0]);
            $('#Afees_raised').val(result[1]);
        }

        // LOAD SAVED DATA (EXPLANATIONS FOR VARIANCE) -------------------------------------------------------------------------------------------------------------
        if(saved_variance != "")
        {
            $('#explanations_for_variance').val(saved_variance);
        }
        
    });

    // ROW & COL TOTAL CALCULATION ---------------------------------------------------------------------------------------------------------------------------------
    function input(cell)
    {
        var cell_jquery = $(cell);
        var allRow      = cell_jquery.parent().parent().parent().find('tr');
        var row         = cell_jquery.parent().parent().find('td').children();
        var colIndex    = cell_jquery.parent().index();
        var rowTotal    = 0;
        var colTotal    = 0;

        var lenght = allRow.length - 1;

        // ROW TOTAL CALCULATION
        row.each(function(index, element)
        {
            var input = $(element).val();

            if(input == ""){
                input = "0";
            }

            input = parseFloat(input);
            rowTotal += input;

            if($(element).attr('id') == 'rowTotal'){
                $(element).text(rowTotal);
            }
        });

        // COL TOTAL CALCULATION
        allRow.each(function(index, element)
        {
            var eachRow = $(element).find('td').children();

            if(index != '0' && index != lenght)
            {
                eachRow.each(function(index, element)
                {
                    if((colIndex-1) == index)
                    {
                        var input = $(element).val();

                        if(input == ""){
                            input = "0";
                        }

                        input = parseFloat(input);
                        colTotal += input;
                    }

                });
            }

            if(index == lenght)
            {
                eachRow.each(function(index, element)
                {
                    if((colIndex-1) == index)
                    {
                        if($(element).attr('id') == 'colTotal'){
                            $(element).text(colTotal);
                        }
                    }

                });
            }

        });
    }

    // BUDGET HOURS CHECKING ---------------------------------------------------------------------------------------------------------------------------------------
    function budget_hours_check(cell)
    {
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';
        var totalBudget = 0;
        var inputs = $(".budgetCheck");

        for(var i = 0; i < inputs.length; i++)
        {
            if($(inputs[i]).val() != '')
            {
                totalBudget = totalBudget + parseFloat($(inputs[i]).val());
            }
        }

        if(totalBudget>budget_hour)
        {
            toastr.error("Total Hours Over Budget Hours Setted", 'Notices');
            $(cell).val('');
        }
    }

    // SUBMIT BUDGET HOUR ------------------------------------------------------------------------------------------------------------------------------------------
    $("#submit_budget_hour").click(function(){
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';
        var table         = $(".budget_table tr");
        var budget_data   = [];

        var lenght = table.length - 1;

        for($i=1 ; $i<=pic_num ; $i++)
        {
            var data2 = [];

            table.each(function(row, tr){

                var data;

                if((row == 0 || row == lenght) && $i!=pic_num){
                    data = $(tr).find('td:eq('+$i+')').text();
                }
                else if($i!=pic_num)
                {
                     data = $(tr).find('td:eq('+$i+')').children().val();
                }

                if($i==pic_num)
                {
                    if(row == lenght){
                        data = $(tr).find('td:eq('+$i+')').text();
                    }
                    else
                    {
                         data = $(tr).find('td:eq('+$i+')').children().text();
                    }
                }

                data2.push(data);
            });

            budget_data.push(data2);
        }

        $.ajax({
           type: "POST",
           url:  " <?php echo site_url('budget_hours/save_budget'); ?>",
           // data: '&assignment_id=' + assignment_id + '&client_id=' + client_id +  '&client_name=' + client_name + '&fye=' + fye + '&type_of_job=' + type_of_job + '&budget_hour=' + budget_hour + '&budget_data=' + JSON.stringify(budget_data) + '&report_type=' + $('#reportType').val(),

           data: {'assignment_id': assignment_id,'client_id': client_id,'client_name': client_name,'fye': fye,'type_of_job': type_of_job,'budget_hour': budget_hour,'budget_data': JSON.stringify(budget_data),'report_type': $('#reportType').val()},
           success: function(data)
           {    
                if(data){
                    toastr.success('Budget Hours Saved Successfully', 'Saved');
                    document.getElementById('reportType').disabled = "true";
                }
           }
        });

        if(log_1)
        {
            $.ajax({
               type: "POST",
               url:  " <?php echo site_url('budget_hours/save_budget_log'); ?>",
               data: '&assignment_id=' + assignment_id
            });
        }

    });

    // SUBMIT ACTUAL HOUR ------------------------------------------------------------------------------------------------------------------------------------------
    $("#submit_actual_hour").click(function(){
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';
        var table         = $(".actual_table tr");
        var actual_data   = [];

        var lenght = table.length - 1;

        for($i=1 ; $i<=pic_num ; $i++)
        {
            var data2 = [];

            table.each(function(row, tr){

                var data;

                if((row == 0 || row == lenght) && $i!=pic_num){
                    data = $(tr).find('td:eq('+$i+')').text();
                }
                else if($i!=pic_num)
                {
                     data = $(tr).find('td:eq('+$i+')').children().val();
                }

                if($i==pic_num)
                {
                    if(row == lenght){
                        data = $(tr).find('td:eq('+$i+')').text();
                    }
                    else
                    {
                         data = $(tr).find('td:eq('+$i+')').children().text();
                    }
                }

                data2.push(data);
            });

            actual_data.push(data2);
        }

        $.ajax({
           type: "POST",
           url:  " <?php echo site_url('budget_hours/save_actual_budget'); ?>",
           // data: '&assignment_id=' + assignment_id + '&client_id=' + client_id +  '&client_name=' + client_name + '&fye=' + fye + '&type_of_job=' + type_of_job + '&budget_hour=' + budget_hour + '&actual_data=' + JSON.stringify(actual_data) + '&report_type=' + $('#reportType').val(),

           data: {'assignment_id': assignment_id,'client_id': client_id,'client_name': client_name,'fye': fye,'type_of_job': type_of_job,'budget_hour': budget_hour,'actual_data': JSON.stringify(actual_data),'report_type': $('#reportType').val()},
           success: function(data)
           {    
                if(data){
                    toastr.success('Actual Budget Hours Saved Successfully', 'Saved');
                    document.getElementById('reportType').disabled = "true";
                }
           }
        });

        if(log_2)
        {
            $.ajax({
               type: "POST",
               url:  " <?php echo site_url('budget_hours/save_actual_budget_log'); ?>",
               data: '&assignment_id=' + assignment_id
            });
        }
    });

    // SUBMIT OTHERS -----------------------------------------------------------------------------------------------------------------------------------------------
    $("#submit_others").click(function(){
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';

        var review_and_supervision = [];
        review_and_supervision.push($('#rns_Bhrs').val());
        review_and_supervision.push($('#rns_Ahrs').val());
        review_and_supervision.push($('#rns_rate').val());

        var partner_review = [];
        partner_review.push($('#pr_Bhrs').val());
        partner_review.push($('#pr_Ahrs').val());
        partner_review.push($('#pr_rate').val());

        var fees_raised = [];
        fees_raised.push($('#Bfees_raised').val());
        fees_raised.push($('#Afees_raised').val());

        var variance      = $('#explanations_for_variance').val();
        
        $.ajax({
           type: "POST",
           url:  " <?php echo site_url('budget_hours/save_others'); ?>",
           // data: '&assignment_id=' + assignment_id + '&client_id=' + client_id +  '&client_name=' + client_name + '&fye=' + fye + '&type_of_job=' + type_of_job + '&budget_hour=' + budget_hour + '&review_and_supervision=' + JSON.stringify(review_and_supervision) + '&partner_review=' + JSON.stringify(partner_review) + '&fees_raised=' + JSON.stringify(fees_raised) + '&variance=' + variance + '&report_type=' + $('#reportType').val(),

           data: {'assignment_id': assignment_id,'client_id': client_id,'client_name': client_name,'fye': fye,'type_of_job': type_of_job,'budget_hour': budget_hour,'review_and_supervision': JSON.stringify(review_and_supervision),'partner_review': JSON.stringify(partner_review),'fees_raised': JSON.stringify(fees_raised),'variance': variance,'report_type': $('#reportType').val()},
           success: function(data)
           {    
                if(data){
                    toastr.success('Others Setting Saved Successfully', 'Saved');
                    document.getElementById('reportType').disabled = "true";
                }
           }
        });

        if(log_3)
        {
            $.ajax({
               type: "POST",
               url:  " <?php echo site_url('budget_hours/save_others_log'); ?>",
               data: '&assignment_id=' + assignment_id
            });
        }
    });

    // REPORT ------------------------------------------------------------------------------------------------------------------------------------------------------
    var canView     = false;
    var canView2    = true;
    var canView3    = true;
    var actualHrs   = [];

    // PRIOR YEAR ACTUAL CHECKING ----------------------------------------------------------------------------------------------------------------------------------
    function priorYear_Actual_Check()
    {
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';

        // var prior_actual;
        // var prior_rns;
        // var prior_pr;
        // var prior_fr;

        $.ajax({
            type: "POST",
            'async' : false,
            url: "<?php echo site_url('budget_hours/priorYear_Actual_Check'); ?>",
            data: '&client_id=' + client_id + '&fye=' + fye +  '&type_of_job=' + type_of_job, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(data)
            {
                if(data)
                {
                    $.ajax({
                        type: "POST",
                        'async' : false,
                        url: "<?php echo site_url('budget_hours/priorYear_Rate_Check'); ?>",
                        data: '&client_id=' + client_id + '&assignment_id=' + assignment_id + '&fye=' + fye +  '&type_of_job=' + type_of_job,
                        dataType: "json",
                        success: function(data)
                        {
                            if(data == true)
                            {
                                canView = true;
                            }
                            else
                            {
                                $('#PYA_RATE').modal('show');
                            }
                        }               
                    });
                }
                else
                {
                    var pya_table = $(".PYA #tablebody_PYA");
                    var type      = $( "#reportType" ).val();

                    $('#tablebody_PYA').children().remove();
                   
                    pya_table.append(' <tr><td class="text-left" style="width:20%;"><strong style="color: #d50000">Rate S$</strong></td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" /></td></tr>');

                    if(type == 'audit')
                    {
                        for(var a=0 ; a<audit_activities.length ; a++)
                        {
                            pya_table.append('<tr><td class="text-left" style="width:20%;">'+audit_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                        }
                    }
                    else if(type == 'accounting')
                    {
                        for(var a=0 ; a<accounting_activities.length ; a++)
                        {
                            pya_table.append('<tr><td class="text-left" style="width:20%;">'+accounting_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                        }
                    }
                    else if(type == 'payroll')
                    {
                        for(var a=0 ; a<payroll_activities.length ; a++)
                        {
                            pya_table.append('<tr><td class="text-left" style="width:20%;">'+payroll_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                        }
                    }
                    else if(type == 'tax')
                    {
                        for(var a=0 ; a<tax_activities.length ; a++)
                        {
                            pya_table.append('<tr><td class="text-left" style="width:20%;">'+tax_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                        }
                    }
                    else if(type == 'others')
                    {
                        for(var a=0 ; a<others_activities.length ; a++)
                        {
                            pya_table.append('<tr><td class="text-left" style="width:20%;">'+others_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                        }
                    }

                    pya_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td><td class="text-left" style=";text-align: center;height:46px;"><label id="colTotal">0</label></td></tr>');

                    $('#PYA').modal('show');
                }
            }               
        });

    }


    // VIEW PRIOR YEAR ----------------------------------------------------------------------------------------------------------------------------------------------
    function view_priorYear()
    {
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';

        if(data_from == 'user_key')
        {
            $data = '';

            $.ajax({
                type: "POST",
                'async' : false,
                url: "<?php echo site_url('budget_hours/get_prior_year_actual'); ?>",
                data: '&client_id=' + client_id + '&assignment_id=' + assignment_id + '&fye=' + fye +  '&type_of_job=' + type_of_job,
                dataType: "json",
                success: function(data)
                {
                    $data = data;
                }               
            });

            var pya_table = $(".PYA2 #tablebody_PYA2");
            var type      = $( "#reportType" ).val();

            $('#tablebody_PYA2').children().remove();

            $prior_actual = JSON.parse($data[0]);

            if($prior_actual[0].length == 31) {
                $prior_actual[0].splice(30, 0, 0);
            }
           
            pya_table.append(' <tr><td class="text-left" style="width:20%;"><strong style="color: #d50000">Rate S$</strong></td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" value="'+$prior_actual[0][0]+'"/></td></tr>');

            if(type == 'audit')
            {
                for(var a=0 ; a<audit_activities.length ; a++)
                {
                    pya_table.append('<tr><td class="text-left" style="width:20%;">'+audit_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)" value="'+$prior_actual[0][a+1]+'" /></td></tr>');
                }
            }
            else if(type == 'accounting')
            {
                for(var a=0 ; a<accounting_activities.length ; a++)
                {
                    pya_table.append('<tr><td class="text-left" style="width:20%;">'+accounting_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                }
            }
            else if(type == 'payroll')
            {
                for(var a=0 ; a<payroll_activities.length ; a++)
                {
                    pya_table.append('<tr><td class="text-left" style="width:20%;">'+payroll_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                }
            }
            else if(type == 'tax')
            {
                for(var a=0 ; a<tax_activities.length ; a++)
                {
                    pya_table.append('<tr><td class="text-left" style="width:20%;">'+tax_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                }
            }
            else if(type == 'others')
            {
                for(var a=0 ; a<others_activities.length ; a++)
                {
                    pya_table.append('<tr><td class="text-left" style="width:20%;">'+others_activities[a]+'</td><td class="text-left" style="text-align: center;width:20%;"><input type="number" id="" class="form-control" min="1" style="text-align: center;" onchange="input(this)"/></td></tr>');
                }
            }

            pya_table.append('<tr><td class="text-left" style="width:20%;">Subtotal</td><td class="text-left" style=";text-align: center;height:46px;"><label id="colTotal">'+$prior_actual[0][$prior_actual[0].length-1]+'</label></td></tr>');

            $prior_rns   = JSON.parse($data[1]);
            $prior_pr    = JSON.parse($data[2]);
            $prior_Afees = JSON.parse($data[3]);

            $('#prior_rns_Ahrs2').val($prior_rns[1]);
            $('#prior_rns_rate2').val($prior_rns[2]);
            $('#prior_pr_Ahrs2').val($prior_rns[1]);
            $('#prior_pr_rate2').val($prior_rns[2]);
            $('#prior_Afees_raised2').val($prior_Afees[1]);

            $('#PYA2').modal('show');
        }
        else if(data_from == 'system_data')
        {
            $.ajax({
                type: "POST",
                'async' : false,
                url: "<?php echo site_url('budget_hours/get_prior_year_rate'); ?>",
                data: '&client_id=' + client_id + '&assignment_id=' + assignment_id + '&fye=' + fye +  '&type_of_job=' + type_of_job,
                dataType: "json",
                success: function(data)
                {
                    $('#PYA_RATE_rate2').val(data);
                }               
            });

            $('#PYA_RATE2').modal('show');
        }
    }


    // SUBMIT PYA --------------------------------------------------------------------------------------------------------------------------------------------------
    $("#submit_PYA").click(function(){
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';
        
        var prior_table            = $(".PYA tr");
        var prior_actual_data      = [];
        var temp_prior_actual_data = [];

        var length = prior_table.length - 1;

        prior_table.each(function(row, tr){

            if(row == length){
                temp_prior_actual_data.push($(tr).find('td:eq(1)').text());
            }
            else
            {
                temp_prior_actual_data.push($(tr).find('td:eq(1)').children().val());
            }
        });

        for($i=0;$i<temp_prior_actual_data.length;$i++)
        {
            if(temp_prior_actual_data[$i] == "")
            {
                temp_prior_actual_data[$i] = "0";
            }
        }

        prior_actual_data.push(temp_prior_actual_data);

        if($('#prior_rns_Ahrs').val() == '')
        {
            $('#prior_rns_Ahrs').val(0);
        }

        if($('#prior_rns_rate').val() == '')
        {
            $('#prior_rns_rate').val(0);
        }

        if($('#prior_pr_Ahrs').val() == '')
        {
            $('#prior_pr_Ahrs').val(0);
        }

        if($('#prior_pr_rate').val() == '')
        {
            $('#prior_pr_rate').val(0);
        }
        
        if($('#prior_Afees_raised').val() == '')
        {
            $('#prior_Afees_raised').val(0);
        }

        var review_and_supervision = [];
        review_and_supervision.push('0');
        review_and_supervision.push($('#prior_rns_Ahrs').val());
        review_and_supervision.push($('#prior_rns_rate').val());

        var partner_review = [];
        partner_review.push('0');
        partner_review.push($('#prior_pr_Ahrs').val());
        partner_review.push($('#prior_pr_rate').val());

        var fees_raised = [];
        fees_raised.push('0');
        fees_raised.push($('#prior_Afees_raised').val());

        $.ajax({
           type: "POST",
           'async' : false,
           url:  " <?php echo site_url('budget_hours/save_PYA'); ?>",
           // data: '&assignment_id=' + assignment_id + '&client_id=' + client_id +  '&client_name=' + client_name + '&fye=' + fye + '&type_of_job=' + type_of_job + '&budget_hour=' + budget_hour + '&review_and_supervision=' + JSON.stringify(review_and_supervision) + '&partner_review=' + JSON.stringify(partner_review) + '&fees_raised=' + JSON.stringify(fees_raised) + '&prior_actual_data=' + JSON.stringify(prior_actual_data),

           data: {'assignment_id': assignment_id,'client_id': client_id,'client_name': client_name,'fye': fye,'type_of_job': type_of_job,'budget_hour': budget_hour,'review_and_supervision': JSON.stringify(review_and_supervision),'partner_review': JSON.stringify(partner_review),'fees_raised': JSON.stringify(fees_raised),'prior_actual_data': JSON.stringify(prior_actual_data)},
           success: function(data)
           {    
                if(data){
                    toastr.success('Prior Year Actual Saved Successfully', 'Saved');

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('CreateDocumentPdf/create_document_pdf'); ?>",
                        data: {"document_id":assignment_id}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        success: function(response)
                        {
                            window.open(
                                response.link,
                                '_blank' // <- This is what makes it open in a new window.
                            );

                            $('#PYA').modal('hide');
                            location.reload();
                        }               
                    });

                }
           }
        });
    });
    

    // SUBMIT PYA RATE ----------------------------------------------------------------------------------------------------------------------------------------------
    $("#submit_PYA_RATE").click(function(){
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';
        var rate          = $("#PYA_RATE_rate").val();

        $.ajax({
           type: "POST",
           'async' : false,
           url:  " <?php echo site_url('budget_hours/save_PYA_RATE'); ?>",
           data: {'assignment_id': assignment_id,'client_id': client_id,'client_name': client_name,'fye': fye,'type_of_job': type_of_job,'budget_hour': budget_hour,'rate': rate },
           success: function(data)
           {    
                if(data){
                    toastr.success('Prior Year Rate Saved Successfully', 'Saved');

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('CreateDocumentPdf/create_document_pdf'); ?>",
                        data: {"document_id":assignment_id}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        success: function(response)
                        {
                            window.open(
                                response.link,
                                '_blank' // <- This is what makes it open in a new window.
                            );

                            $('#PYA_RATE').modal('hide');
                            location.reload();
                        }               
                    });

                }
           }
        });
    });


    // SUBMIT PYA 2 -------------------------------------------------------------------------------------------------------------------------------------------------
    $("#submit_PYA2").click(function(){
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';
        
        var prior_table            = $(".PYA2 tr");
        var prior_actual_data      = [];
        var temp_prior_actual_data = [];

        var length = prior_table.length - 1;

        prior_table.each(function(row, tr){

            if(row == length){
                temp_prior_actual_data.push($(tr).find('td:eq(1)').text());
            }
            else
            {
                temp_prior_actual_data.push($(tr).find('td:eq(1)').children().val());
            }
        });

        for($i=0;$i<temp_prior_actual_data.length;$i++)
        {
            if(temp_prior_actual_data[$i] == "")
            {
                temp_prior_actual_data[$i] = "0";
            }
        }

        prior_actual_data.push(temp_prior_actual_data);

        if($('#prior_rns_Ahrs2').val() == '')
        {
            $('#prior_rns_Ahrs2').val(0);
        }

        if($('#prior_rns_rate2').val() == '')
        {
            $('#prior_rns_rate2').val(0);
        }

        if($('#prior_pr_Ahrs2').val() == '')
        {
            $('#prior_pr_Ahrs2').val(0);
        }

        if($('#prior_pr_rate2').val() == '')
        {
            $('#prior_pr_rate2').val(0);
        }
        
        if($('#prior_Afees_raised2').val() == '')
        {
            $('#prior_Afees_raised2').val(0);
        }

        var review_and_supervision = [];
        review_and_supervision.push('0');
        review_and_supervision.push($('#prior_rns_Ahrs2').val());
        review_and_supervision.push($('#prior_rns_rate2').val());

        var partner_review = [];
        partner_review.push('0');
        partner_review.push($('#prior_pr_Ahrs2').val());
        partner_review.push($('#prior_pr_rate2').val());

        var fees_raised = [];
        fees_raised.push('0');
        fees_raised.push($('#prior_Afees_raised2').val());

        $.ajax({
           type: "POST",
           'async' : false,
           url:  " <?php echo site_url('budget_hours/save_PYA'); ?>",
           data: {'assignment_id': assignment_id,'client_id': client_id,'client_name': client_name,'fye': fye,'type_of_job': type_of_job,'budget_hour': budget_hour,'review_and_supervision': JSON.stringify(review_and_supervision),'partner_review': JSON.stringify(partner_review),'fees_raised': JSON.stringify(fees_raised),'prior_actual_data': JSON.stringify(prior_actual_data)},
           success: function(data)
           {    
                if(data)
                {
                    toastr.success('Prior Year Actual Saved Successfully', 'Saved');
                    $('#PYA2').modal('hide');
                    // location.reload();
                }
           }
        });
    });
    

    // SUBMIT PYA RATE 2 --------------------------------------------------------------------------------------------------------------------------------------------
    $("#submit_PYA_RATE2").click(function(){
        var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';
        var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
        var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
        var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
        var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';
        var rate          = $("#PYA_RATE_rate2").val();

        $.ajax({
           type: "POST",
           'async' : false,
           url:  " <?php echo site_url('budget_hours/save_PYA_RATE'); ?>",
           data: {'assignment_id': assignment_id,'client_id': client_id,'client_name': client_name,'fye': fye,'type_of_job': type_of_job,'budget_hour': budget_hour,'rate': rate },
           success: function(data)
           {    
                if(data){
                    toastr.success('Prior Year Rate Saved Successfully', 'Saved');
                    $('#PYA_RATE2').modal('hide');
                    // location.reload();
                }
           }
        });
    });


    // ACTUAL HOURS CHECKING ---------------------------------------------------------------------------------------------------------------------------------------
    var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
    var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
    var type_of_job   = '<?php echo isset($assignment_details)?$assignment_details[0]->type_of_job:'' ?>';
    var months        = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    var result_people = [];
    var people        = [];
    var total_acutal  = 0;

    client_name = '*'+ client_name;

    if(fye != null){

        var date = new Date(fye);
        fye = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();
    }
    else{
        fye = "";
    }

    $.ajax({
        type: "POST",
        'async' : false,
        url: "<?php echo site_url('budget_hours/get_type_of_job'); ?>",
        data: '&type_of_job=' + type_of_job , // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(data)
        {
            type_of_job = data;
        }               
    });

    people.push(leader);

    for($i=0 ; $i<assistant.length ; $i++)
    {
        if(assistant[$i] != leader)
        {
            people.push(assistant[$i]);
        }
    }

    var assignment_complete_date = '<?php echo isset($assignment_details)?$assignment_details[0]->complete_date:'' ?>';

    for($i=0 ; $i<people.length ; $i++)
    {
        $.ajax({
            type: "POST",
            'async' : false,
            url: "<?php echo site_url('budget_hours/actual_hours_check'); ?>",
            data: '&people=' + people[$i] + '&complete_date=' + assignment_complete_date ,// <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(data)
            {
                result_people.push(data);
            }               
        });
    }

    for($i=0 ; $i<result_people.length ; $i++)
    {
        if(result_people[$i] != '')
        {
            var object = JSON.parse(result_people[$i]);

            for($o=0 ; $o<object.length ; $o++)
            {
                if(object[$o][0] === client_name && object[$o][1] === type_of_job && object[$o][2] === fye)
                {
                    var value;
                    if(object[$o][object[$o].length-1] == "") {
                        value = "0.0"
                    } else {
                        value = object[$o][object[$o].length-1]
                    }
                    actualHrs.push(value);
                    break;
                }

                if($o == object.length-1)
                {
                    if(actualHrs == '')
                    {
                        for($u=0 ; $u<result_people.length ; $u++)
                        {
                            actualHrs.push('0.0');
                            break;
                        }
                    }
                }
            }
        }
        else
        {
            actualHrs.push('0.0');
            // break;
        }
    }

    // CLEAR REVIEW HOURS
    clearReviewHrs = [];
    for($i=0 ; $i<result_people.length ; $i++)
    {
        if(result_people[$i] != '')
        {   
            var object = JSON.parse(result_people[$i]);

            for($o=0 ; $o<object.length ; $o++)
            {
                var flag = true;
                if(object[$o].includes(client_name) && object[$o].includes("STATUTORY AUDIT - CLEAR REVIEW") && object[$o].includes(fye)) {
                    var value;
                    if(object[$o][object[$o].length-1] == "") {
                        value = "0.0"
                    } else {
                        value = object[$o][object[$o].length-1]
                    }
                    clearReviewHrs.push(value);
                    flag = false;
                    break;
                }

                if($o === (object.length - 1)) {
                    if(flag) {
                         clearReviewHrs.push('0.0');
                    }
                }
            }
        }
        else
        {
            clearReviewHrs.push('0.0');
            // break;
        }
    }

    $(document).ready(function () {
        if(clearReviewHrs.length)
        {
            var table = $(".actual_table");
            for($i=0;$i<clearReviewHrs.length;$i++) {
                for($n=0;$n<31;$n++)
                {
                    if($n == (31 -2))
                    {
                        $a = $i + 1;
                        if(clearReviewHrs[$i] !== "0.0") {
                            table.find('tr:eq('+$n+')').find('td:eq('+$a+')').children().val(clearReviewHrs[$i]);
                            input(table.find('tr:eq('+$n+')').find('td:eq('+$a+')').children());
                        }
                    }
                }
            }
        }
    });

    // STOCK TAKE HOURS
    var client_id     = '<?php echo isset($assignment_details)?$assignment_details[0]->client_id:'' ?>';
    var fye           = '<?php echo isset($assignment_details)?$assignment_details[0]->FYE:'' ?>';
    var client_name   = <?php echo json_encode( isset($assignment_details)?$assignment_details[0]->client_name:'') ?>;
    var stock_take_list;
    $.ajax({
        type: "POST",
        'async' : false,
        url: "<?php echo site_url('budget_hours/get_stock_take_hours'); ?>",
        data: {"client_id":client_id, "fye":fye, "people":people},
        dataType: "json",
        success: function(data)
        {
            stock_take_list = data;
        }               
    });
    
    var stock_take_hrs = [];
    for($a=0;$a<stock_take_list.length;$a++) {
        var client_name_with_star = "*"+client_name;
        var name = stock_take_list[$a]['name'];
        var content = JSON.parse(stock_take_list[$a]['content']);
        for($b=0;$b<content.length;$b++) {
            if(content[$b].includes(client_name_with_star)) {
                if(content[$b][1] === "STATUTORY AUDIT - STOCK TAKE") {
                    var last_value = content[$b].length - 1;
                    if(content[$b][last_value] == "") {
                        var hrs = "0.0";
                    } else {
                        var hrs = content[$b][last_value];
                    }
                    stock_take_hrs.push({'name':name, 'hrs':hrs});
                    break;
                }
            }
        }
    }

    $(document).ready(function () {
        for($a=0;$a<stock_take_hrs.length;$a++) {
            var table = $(".actual_table");
            var td_index;
            table.find('tr:eq(0)').find('td').each(function(i){
                if($(this).text() === stock_take_hrs[$a]["name"]){
                    td_index = i;
                }
            });

            if(stock_take_hrs[$a]["hrs"] !== "0.0") {
                table.find('tr:eq(30)').find('td:eq('+td_index+')').children().val(stock_take_hrs[$a]["hrs"]);
                input(table.find('tr:eq(30)').find('td:eq('+td_index+')').children());
            }

        }
    });

    // EACH MEMBER ACTUAL HOURS
    for($a=0 ; $a<actualHrs.length ; $a++)
    {
        if(actualHrs[$a] == '' || actualHrs[$a] == undefined)
        {
            actualHrs[$a] = '0.0';
        }

        if(clearReviewHrs[$a] == '' || clearReviewHrs[$a] == undefined)
        {
            clearReviewHrs[$a] = '0.0';
        }

        actualHrs[$a] = parseFloat(actualHrs[$a]) + parseFloat(clearReviewHrs[$a]);

        if($a==0)
        {   
            for($b=0;$b<stock_take_hrs.length;$b++) {
                if($('.leader').parent().parent().find('td:eq(0)').text() === stock_take_hrs[$b]['name']) {
                    actualHrs[$a] = parseFloat(actualHrs[$a]) + parseFloat(stock_take_hrs[$b]['hrs']);
                }
            }
            $('.leader').text(parseFloat(actualHrs[$a]));
        }
        else
        {
            for($b=0;$b<stock_take_hrs.length;$b++) {
                if($('.assistant'+ ($a-1) ).parent().parent().find('td:eq(0)').text() === stock_take_hrs[$b]['name']) {
                    actualHrs[$a] = parseFloat(actualHrs[$a]) + parseFloat(stock_take_hrs[$b]['hrs']);
                }
            }
            $('.assistant'+ ($a-1) ).text(parseFloat(actualHrs[$a]));
        }

        total_acutal = total_acutal + parseFloat(actualHrs[$a]);
    }

    $(document).ready(function () {
        Actual_Check();
    });

    function Actual_Check()
    {
        var actualTable  = $(".actual_table tr");
        var actualData   = [];
        var actualPerson = [];

        var length = actualTable.length - 1;

        for($i=1 ; $i<=pic_num-1 ; $i++)
        {
            actualTable.each(function(row, tr){

                var data;

                if(row == length && $i!=pic_num)
                {
                    data = $(tr).find('td:eq('+$i+')').text();
                    actualData.push(data);
                }
                else if(row == 0)
                {
                    data = $(tr).find('td:eq('+$i+')').text();
                    actualPerson.push(data);
                }
            });
        }

        var check = [];

        console.log(actualData, actualHrs)

        for($i=0 ; $i<actualData.length ; $i++)
        {
            if(parseFloat(actualData[$i]) != actualHrs[$i])
            {
                toastr.error("Actual Hours Not Equivalent With Timesheet : " + actualPerson[$i], 'Notices');
                check.push(false);
            }
            else
            {
                check.push(true);
            }
        }

        if(check.includes(false))
        {
            canView2 = false;
        }
        else
        {
            canView2 = true;
        }

    }

    function exportDocumentPDF(){

        var budget_hour   = '<?php echo isset($assignment_details)?$assignment_details[0]->budget_hour:'' ?>';

        log_1 = false;
        log_2 = false;
        log_3 = false;

        canView3 = true;

        $("#submit_budget_hour").trigger( "click" );
        $("#submit_actual_hour").trigger( "click" );
        $("#submit_others").trigger( "click" );

        // priorYear_Actual_Check();
        Actual_Check();

        if(total_acutal > budget_hour)
        {
            var explanation = $('#explanations_for_variance').val();

            if(explanation == '')
            {
                bootbox.confirm({
                    message: "<strong>Actual hours is more than Budget hours. Please provide explanation!</strong>",
                    closeButton: false,
                    buttons: {
                        confirm: {
                            label: 'OK',
                            className: 'btn_purple'
                        },
                        cancel: {
                            label: 'No',
                            className: 'hidden'
                        }
                    },
                    callback: function (result){}
                })
                canView3 = false;
            }
            else
            {
                priorYear_Actual_Check();
            }
        }
        else
        {
            priorYear_Actual_Check();
        }

        if(canView && canView2 && canView3 ){

            var assignment_id = '<?php echo isset($assignment_details)?$assignment_details[0]->assignment_id:'' ?>';

            $("#loadingmessage").show();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('CreateDocumentPdf/create_document_pdf'); ?>",
                data: {"document_id":assignment_id}, // <--- THIS IS THE CHANGE
                dataType: "json",
                success: function(response)
                {
                    window.open(
                        response.link,
                        '_blank' // <- This is what makes it open in a new window.
                    );

                    log_1 = true;
                    log_2 = true;
                    log_3 = true;

                    $("#loadingmessage").hide();
                }               
            });

        }
    }

</script>