<style>
#fs_company_info_modal .modal,
#fs_director_interest_modal .modal,
#fs_firm_report_modal .modal
{
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  overflow: hidden;
}

#fs_company_info_modal .modal-dialog,
#fs_director_interest_modal .modal-dialog,
#fs_firm_report_modal .modal-dialog
{
  position: fixed;
  margin: 0;
  width: 100%;
  height: 100%;
  padding: 0;
}

#fs_company_info_modal .modal-content, 
#fs_director_interest_modal .modal-content, 
#fs_firm_report_modal .modal-content
{
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  border-radius: 0;
  box-shadow: none;
}

#fs_company_info_modal .modal-title,
#fs_director_interest_modal .modal-title,
#fs_firm_report_modal .modal-title
{
  font-weight: 300;
  font-size: 2em;
  line-height: 30px;
}

#fs_company_info_modal .modal-body, 
#fs_director_interest_modal .modal-body, 
#fs_firm_report_modal .modal-body
{
  position: absolute;
  top: 60px;
  bottom: 60px;
  width: 100%;
  font-weight: 300;
  overflow: auto;
}

#fs_company_info_modal .modal-footer, 
#fs_director_interest_modal .modal-footer,
#fs_firm_report_modal .modal-footer
{
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  height: 60px;
  padding: 10px;
}
</style>

<div class="form-group">
    <label class="col-xs-1">
      <strong>Step 1:</strong>
    </label>
    <div class="col-xs-8">
        <button id="fs_company_info_btn" class="btn btn-primary">Information</button>
    </div>
</div>

<hr/>

<div class="form-group">
    <label class="col-xs-1">
      <strong>Step 2:</strong>
    </label>
    <div class="col-xs-8">
        <button id="fs_director_interest_btn" class="btn btn-primary">Director Share Holding</button>
    </div>
</div>

<hr/>

<div class="form-group">
    <label class="col-xs-1">
      <strong>Step 3:</strong>
    </label>
    <div class="col-xs-8">
        <button id="fs_firm_report_btn" class="btn btn-primary">Our Report</button>
    </div>
</div>

<!-- DISPLAY/HIDE MODAL HERE -->

<div class="modal fade" id="fs_company_info_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">Information</h4>
      </div>
      <div class="modal-body" style="padding:50px;"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="save_fs_company_info" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="fs_director_interest_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">Director Share Holding</h4>
      </div>
      <div class="modal-body" style="padding:50px;"></div>
      <div class="modal-footer">
        <!-- <button id="check_account_code" type="button" class="btn btn-default pull-left">Check Account code</button> -->
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="save_fs_director_interest" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="fs_firm_report_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel" style="text-align:center;">Firm's Report</h4>
      </div>
      <div class="modal-body" style="padding:50px;"></div>
      <div class="modal-footer">
        <!-- <button id="check_account_code" type="button" class="btn btn-default pull-left">Check Account code</button> -->
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <?php
          if(!$is_small_frs_not_audited)
          {
            echo '<button id="save_fs_firm_report" type="button" class="btn btn-primary">Save</button>';
          }
        ?>
      </div>
    </div>
  </div>
</div>

<script src="themes/default/assets/js/financial_statement/partial_fs_corporate_info.js" charset="utf-8"></script>