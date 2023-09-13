<style>
#account_category .modal,
#schedule_operating_expenses_modal .modal,
#state_detailed_pro_loss_modal .modal,
#state_comp_income_modal .modal,
#state_financial_position_modal .modal,
#state_cash_flows_modal .modal,
#state_changes_in_equity_modal .modal,
#nta_modal .modal,
#fs_setup_cfs_modal .modal
{
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  overflow: hidden;
}

#account_category .modal-dialog,
#schedule_operating_expenses_modal .modal-dialog,
#state_detailed_pro_loss_modal .modal-dialog, 
#state_comp_income_modal .modal-dialog,
#state_financial_position_modal .modal-dialog,
#state_cash_flows_modal .modal-dialog,
#state_changes_in_equity_modal .modal-dialog,
#nta_modal .modal-dialog,
#fs_setup_cfs_modal .modal-dialog
{
  position: fixed;
  margin: 0;
  width: 100%;
  height: 100%;
  padding: 0;
}

#account_category .modal-content, 
#schedule_operating_expenses_modal .modal-content, 
#state_detailed_pro_loss_modal .modal-content, 
#state_comp_income_modal .modal-content,
#state_financial_position_modal .modal-content ,
#state_cash_flows_modal .modal-content,
#state_changes_in_equity_modal .modal-content,
#nta_modal .modal-content,
#fs_setup_cfs_modal .modal-content
{
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  border-radius: 0;
  box-shadow: none;
}

#account_category .modal-title,
#schedule_operating_expenses_modal .modal-title,
#state_detailed_pro_loss_modal .modal-title, 
#state_comp_income_modal .modal-title,
#state_financial_position_modal .modal-title,
#state_cash_flows_modal .modal-title,
#state_changes_in_equity_modal .modal-title,
#nta_modal .modal-title,
#fs_setup_cfs_modal .modal-title
{
  font-weight: 300;
  font-size: 2em;
  line-height: 30px;
}

#account_category .modal-body, 
#schedule_operating_expenses_modal .modal-body, 
#state_detailed_pro_loss_modal .modal-body, 
#state_comp_income_modal .modal-body,
#state_financial_position_modal .modal-body,
#state_cash_flows_modal .modal-body,
#state_changes_in_equity_modal .modal-body,
#nta_modal .modal-body,
#fs_setup_cfs_modal .modal-body
{
  position: absolute;
  top: 60px;
  bottom: 60px;
  width: 100%;
  font-weight: 300;
  overflow: auto;
}

#account_category .modal-footer, 
#schedule_operating_expenses_modal .modal-footer,
#state_detailed_pro_loss_modal .modal-footer, 
#state_comp_income_modal .modal-footer,
#state_financial_position_modal .modal-footer ,
#state_cash_flows_modal .modal-footer,
#state_changes_in_equity_modal .modal-footer,
#nta_modal .modal-footer,
#fs_setup_cfs_modal .modal-footer
{
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  height: 60px;
  padding: 10px;
}

.fileUpload {
  position: relative;
  overflow: hidden;
  margin-top: 0;
}

.fileUpload input.upload_TB,  .fileUpload input.upload_LY_TB{
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  padding: 0;
  font-size: 20px;
  cursor: pointer;
  opacity: 0;
  filter: alpha(opacity=0);
  width: 100%;
}

/*Chrome fix*/
input::-webkit-file-upload-button {
  cursor: pointer !important;
  width: 100%;
}
</style>

<input type="hidden" id="opening_fs_statement_doc_type" value="">

<!-- <div class="form-group">
    <label class="col-xs-1">
      <strong>Step 1:</strong>
    </label>

    <?php
      if($show_ly_TB_btn == 1)
      {
        echo 
        '<div class="col-xs-11">
          <div class="fileUpload btn btn-primary">
            <span>Last Year Trial Balance</span>
            <input type="file" class="upload_LY_TB" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onclick="this.value=null"/>
          </div>
        </div>';

        echo '<div class="col-xs-12"><br/></div>';
        echo '<label class="col-xs-1"><br/></label>';
      }
    ?>
    
    <div class="col-xs-11">
      <div class="fileUpload btn btn-primary">
        <span>Trial Balance</span>
        <input type="file" class="upload_TB" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onclick="this.value=null"/>
      </div>
      <i id="trial_balance_info_btn" class="fa fa-info-circle" aria-hidden="true" style="font-size: 12pt; margin: 10px; cursor:pointer;" data-name="trial_balance_info" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to see or download trial balance format" onclick="show_trial_b_info()"></i>
    </div>
</div>

<hr/> -->

<!-- <div class="form-group">
    <label class="col-xs-1">
      <strong>Step 2:</strong>
    </label>
    <div class="col-xs-8">
        <button id="account_category_btn" class="btn btn-primary account_category_btn">Account Category</button>
    </div>
</div> -->

<hr/>

<div class="form-group">
  <label class="col-xs-1">
    <strong>Step 1:</strong>
  </label>
  <div class="col-xs-8">
      <?php 
        if($group_type != 1)
        {
          echo '<button id="state_financial_statement_group_btn" class="btn btn-primary">Statement of financial position (Group)</button>';
        }
      ?>
      
      <button id="state_financial_statement_company_btn" class="btn btn-primary">Statement of financial position (Company)</button>
  </div>
</div>

<hr/>

<div class="form-group">
  <label class="col-xs-1"><strong>Step 2:</strong></label>
  <div class="col-xs-8">
      <button id="state_comp_income_btn" class="btn btn-primary">Statement of comprehensive income</button>
  </div>
</div>

<hr/>

<div class="form-group">
  <label class="col-xs-1"><strong>Step 3:</strong></label>
  <div class="col-xs-8">
      <?php 
        if($group_type != 1)
        {
          echo '<button id="state_changes_in_equity_group_btn" class="btn btn-primary">Statement of changes in equity (Group)</button>';
        }
      ?>
      
      <button id="state_changes_in_equity_company_btn" class="btn btn-primary">Statement of changes in equity (Company)</button>
  </div>
</div>

<hr/>

<div class="form-group">
  <label class="col-xs-1"><strong>Step 4:</strong></label>
  <div class="col-xs-8">
      <button id="state_cash_flows_btn" class="btn btn-primary">Statement of cash flows</button>
      <!-- <i id="fs_cfs_setup_btn" class="glyphicon glyphicon-cog" aria-hidden="true" style="font-size: 12pt; margin: 10px; cursor:pointer;" data-name="cash_flows" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to setup Statement of Cash Flows" onclick="fs_setup_cfs()"></i> -->
      <a href="<?=site_url('fs_statements/setup_cfs/'.$fs_company_info[0]['id'].'/' . $client_id);?>" id="fs_cfs_setup_btn" class="glyphicon glyphicon-cog" aria-hidden="true" style="font-size: 12pt; margin: 10px; cursor:pointer;" data-name="cash_flows" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to setup Statement of Cash Flows" style="text-decoration: none;"></a>
  </div>
</div>

<hr/>

<div class="form-group">
  <label class="col-xs-1"><strong>Step 5:</strong></label>
  <div class="col-xs-8">
        <button id="state_detailed_pro_loss_btn" class="btn btn-primary">Statement of detailed profit or loss</button>
    </div>
</div>

<hr/>

<div class="form-group">
  <label class="col-xs-1"><strong>Step 6:</strong></label>
  <div class="col-xs-8">
        <button id="schedule_operating_expenses_btn" class="btn btn-primary">Schedule of operating expenses</button>
    </div>
</div>

<hr/>

<div class="form-group">
  <label class="col-xs-1"><strong>Step 7:</strong></label>
  <div class="col-xs-8">
      <button id="nta_btn" class="btn btn-primary">NTA</button>
      <i id="fs_master_currency_btn" class="glyphicon glyphicon-cog" aria-hidden="true" style="font-size: 12pt; margin: 10px; cursor:pointer;" data-name="master_currency" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to setup master currency" onclick="fs_setup_master_currency()"></i>
  </div>
</div>

<!-- <div id="accordion">
  <div class="card">
    <div class="card-header bg_red_darken" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Statement of comprehensive income
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div id="comprehensive_income" class="card-body"></div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg_red_darken" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Statement of financial position
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div id="financial_position" class="card-body"></div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg_red_darken" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Statement of changes in equity
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div id="changes_in_equity" class="card-body"></div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg_red_darken" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Statement of cash flows
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div id="cash_flows" class="card-body"></div>
    </div>
  </div>
</div> -->

<!-- Modal to display trial balance error message -->
<div class="modal fade" id="trial_b_error_msg_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel">Trial balance template info</h4>
      </div>
      <div class="modal-body">
        <h5>Set 3 columns same as the image below or download from <a style="cursor:pointer;" onclick="download_trial_b_template()">here</a>.</h5>
        <br/>
        <img src="img/Trial Balance Sample.PNG" alt="Trial balance - template" height="75%" width="75%">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">OK</button>
        <!-- <button id="save_trial_b_error_msg" type="button" class="btn btn-primary">Save</button> -->
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display trial balance error message -->

<!-- Modal Setup for Statement of Cash Flows -->
<div class="modal fade" id="fs_setup_cfs_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #c61156; color:white;">
        <p class="modal-title" id="myModalLabel" style="text-align:center;">Setup Statement of Cash Flows</p>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <!-- <button type="button" data-dismiss="modal" class="btn">OK</button> -->
        <!-- <button type="button" data-dismiss="modal" class="btn">Cancel</button> -->
        <!-- <button id="save_setup_cfs" type="button" class="btn btn-primary">Save</button> -->
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal Setup for Statement of Cash Flows -->

<!-- Modal Setup for master currency -->
<div class="modal fade" id="fs_master_currency_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel">Setup master currency</h4>
      </div>
      <div class="modal-body">
        <p>Add in currency for NTA use</p>
        <form id="form_fs_setup_master_currency">
          <table id="fs_setup_master_currency_tbl" class="table table-hover table-borderless">
            <thead>
              <tr>
              <th style="width: 5%;">&nbsp;</th>
              <th style="width: 31.3877%;">Currency (Short form)</th>
              <th style="width: 31.4796%;">Currency Name</th>
              </tr>
            </thead>
            <tbody id="fs_setup_master_currency_tbody">
              <tr class="tr_template_row" style="display:none;">
              <td style="text-align: right; padding-left: 10px;">
                <a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_fs_mc_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>
              </td>
              <td class="currency_short_form" style="padding-left: 10px;">
                <input type="hidden">
                <input type="hidden">
                <?php
                    echo form_dropdown('', $dp_country_list, '', 'onchange="update_fs_master_currency_info(this)"');
                ?>
              </td>
              <td class="currency_name" style="padding-left: 10px;">&nbsp;</td>
              </tr>
              <tr class="add_tr_fs_master_currency">
                <td style="width: 5%;">&nbsp;</td>
                <td colspan="3">
                  <a class="add_fs_master_currency amber" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold; cursor: pointer;" onclick="insert_tr_master_currency(this, '')">
                    <i class="fa fa-plus-circle" style="font-size:14px;"> <span style="font-family: 'Open Sans', Arial, sans-serif; font-weight: 700;">Add currency</span></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">OK</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal Setup for master currency -->

<div class="modal fade" id="account_category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">Account Category</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <!-- <button id="check_account_code" type="button" class="btn btn-default pull-left">Check Account code</button> -->
        <button type="button" data-dismiss="modal" class="btn">Back</button>
        <button id="SaveAllAccountDetail" type="button" class="btn btn-primary">Save All</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for create account form for uncategorised account -->
<div class="modal fade" id="create_account_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>CREATE ACCOUNT</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body">
        <input id="uncategorised_new_account" class="form-control" type="text" placeholder="New account name">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_create_account" type="button" class="btn btn-primary" onclick="create_uncategorized_account()">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal for create account form for uncategorised account -->

<!-- Modal to display main list so that user can choose -->
<!-- <div class="modal fade" id="main_account_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>MAIN ACCOUNT LIST</strong>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_select_main" type="button" class="btn btn-primary">Select</button>
      </div>
    </div>
  </div>
</div> -->
<!-- END OF Modal to display main list so that user can choose -->

<!-- Modal to display sub list so that user can choose -->
<div class="modal fade" id="sub_account_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>SUB ACCOUNT LIST</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_insert_sub" type="button" class="btn btn-primary">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display sub list so that user can choose -->

<!-- Modal to display edit/change sub list WITHOUT INPUT DESCRIPTION NAME so that user can choose -->
<div class="modal fade" id="edit_account_code_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>ACCOUNT CODE LIST</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_edit_sub" type="button" class="btn btn-primary">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display edit/change sub list WITHOUT INPUT DESCRIPTION NAME so that user can choose -->

<!-- Modal to display Statement of Comprehensive Income -->
<div class="modal fade" id="state_comp_income_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">STATEMENT OF COMPREHENSIVE INCOME</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <!-- <button id="check_account_code" type="button" class="btn btn-default pull-left">Check Account code</button> -->
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="save_state_comp_income" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Statement of Comprehensive Income  -->

<!-- Modal to display Statement of financial position -->
<div class="modal fade" id="state_financial_position_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">STATEMENT OF FINANCIAL POSITION</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="save_state_financial_statement_group" type="button" class="btn btn-primary" style="display:none;">Save</button>
        <button id="save_state_financial_statement_company" type="button" class="btn btn-primary" style="display:none;">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Statement of Comprehensive Income  -->

<!-- Modal to display Statement of Comprehensive Income -->
<div class="modal fade" id="state_changes_in_equity_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">STATEMENT OF CHANGES IN EQUITY</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <!-- <button id="check_account_code" type="button" class="btn btn-default pull-left">Check Account code</button> -->
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="save_state_changes_in_equity_group" type="button" class="btn btn-primary">Save</button>
        <button id="save_state_changes_in_equity_company" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Statement of Comprehensive Income  -->

<!-- Modal to display Statement of cash flows -->
<div class="modal fade" id="state_cash_flows_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">STATEMENT OF CASH FLOWS</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <!-- <button id="check_account_code" type="button" class="btn btn-default pull-left">Check Account code</button> -->
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="save_state_cash_flows" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Statement of cash flows  -->

<!-- Modal to display Shedule Operating Expenses -->
<div class="modal fade" id="schedule_operating_expenses_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">SCHEDULE OPERATING EXPENSES</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Shedule Operating Expenses -->

<!-- Modal to display Statement of Detailed Profit or Loss -->
<div class="modal fade" id="state_detailed_pro_loss_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">STATEMENT OF DETAILED PROFIT OR LOSS</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Statement of Detailed Profit or Loss -->

<!-- Modal to display Statement of cash flows -->
<div class="modal fade" id="nta_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">NTA</h4>
      </div>
      <div class="modal-body" style="padding:50px;"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="save_nta" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Statement of cash flows  -->

<!-- Modal to display Expenses list (NTA - Note Profit before tax) so that user can choose -->
<div class="modal fade" id="add_income_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>Add Income</strong>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Expenses list (NTA - Note Profit before tax) so that user can choose -->

<!-- Modal to display Expenses list (NTA - Note Profit before tax) so that user can choose -->
<div class="modal fade" id="add_expense_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>Add Expense</strong>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display Expenses list (NTA - Note Profit before tax) so that user can choose -->

  <!-- Modal to display add note -->
  <div class="modal fade" id="note_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <strong>Add Note</strong>
          <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
          <!-- <button type="button" data-dismiss="modal" class="btn">Close</button>
          <button id="btn_select_sub" type="button" class="btn btn-primary">Select</button> -->
        </div>
      </div>
    </div>
  </div>
  <!-- END OF Modal to display add note -->

  <!-- Modal to display add note -->
  <div class="modal fade" id="insert_note_num" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <strong>Insert Note Number</strong>
          <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
        </div>
        <div class="modal-body">
          <form id="form_insert_note_num" class="form-inline">
            <div class="form-group">
              <input type="hidden" id="target_note_no" value="">
              <input type="hidden" id="target_fs_note_templates_master_id" value="">
              <input type="text" class="form-control" id="input_note_num" name="input_note_num" value="" placeholder="Input number. Eg. 3" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"> 
              <button id="btn_ok_insert_num" type="button" class="btn btn-primary" onclick="insert_note_num()">OK</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  <!-- END OF Modal to display add note -->

  <!-- Modal to display note layout -->
  <div class="modal fade" id="note_layout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <input type="hidden" id="fs_categorized_account_id_to_link_note" value="">
          <input type="hidden" id="fs_note_templates_default_id_selected" value="">
          <input type="hidden" id="fs_note_templates_master_id" value="">
          <strong></strong>
          <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <!-- <button type="button" data-dismiss="modal" class="btn">Close</button> -->
          <button type="button" data-dismiss="modal" class="btn">Cancel</button>
          <button id="btn_save_note" type="button" class="btn btn-primary">Save and Insert Note</button>
        </div>
      </div>
    </div>
  </div>
  <!-- END OF Modal to display add note -->

<script src="themes/default/assets/js/financial_statement/partial_fs_doc_setup.js" charset="utf-8"></script>


<?php
  function negative_bracket($number)
  {
      if($number == 0)
      {
          return "-";
      }
      elseif($number < 0)
      {
          return "(" . number_format(abs($number), 2) . ")";
      }
      else
      {
          return number_format($number, 2);
      }
  }
?>

<script type="text/javascript">
var country_list    = <?php echo json_encode($country_list); ?>;
var fs_ntfs_mc_data = <?php echo json_encode($fs_ntfs_mc_data); ?>;

retrieve_fs_ntfs_mc_data(fs_ntfs_mc_data);

$('.fs_master_currency_dp').select2();

// fix scrolling problem
$('#insert_note_num').on("hidden.bs.modal", function (e) { 
  if ($('.modal:visible').length) { 
      $('body').addClass('modal-open');
  }
});

function retrieve_fs_ntfs_mc_data(list)
{
  for (i = 0; i < list.length; i++)   // exclude last row
  {
    insert_tr_master_currency('', list[i]);
  }
}
</script>