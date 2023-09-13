<?php
    if(!$is_small_frs_not_audited)
    {
?>

<form id="form_audit_report" method="POST">
	<label><strong>Report on the Audit of the Financial Statements</strong></label>
	<hr/>
	<div class="form-group mb-md">
        <label class="col-xs-2">Opinion</label>
        <div class="col-xs-2">
        	<div>
        		<?php
					echo form_dropdown('fs_aud_report_opinion', $dp_opinion, $this_independ_aud_report[0]['fs_opinion_type_id'], 'id="fs_aud_report_opinion" class="form-control fs_aud_report_opinion fs_aud_dropdown" style="width:150%;" style="width:200px;" onchange="insert_label_opinion(this, false)"');
				?>
        		<!-- <select class="form-control fs_aud_report_opinion" id="fs_aud_report_opinion" style="width:200px;" name="fs_aud_report_opinion"></select> -->
            </div>
        </div>
    </div>
    <div class="form-group mb-md">
        <label class="col-xs-2"></label>
        <div class="col-xs-9 aud_report_opinion">
        	<!-- <div>
        		<label><strong class="lbl_opinion">Basic for Opinion</strong></label>
            </div> -->
            <div>
            	<label class="lbl_opinion_details" style="width:100%; text-align: justify;"></label>	<!-- content will change as the dropdown value changed --> 
            </div>
        </div>
    </div>

    <div class="form-group tarea_opinion_details">
        <label class="col-xs-2"></label>
        <div class="col-xs-9">
            <!-- <div>
                <label id="lbl_basic_for_opinion"></label>
            </div> -->
            <div>
                <label class="lbl_opinion_details_2" style="width:100%; text-align: justify;"></label>
            </div>
        </div>
    </div>

   	<div class="form-group basis_for_opinion" style="display:none;">
        <label class="col-xs-2"></label>
        <div class="col-xs-9">
        	<div>
        		<label id="lbl_basic_for_opinion"></label>
        	</div>
        	<div class="section_basic_opinion_input">
        		<textarea class="form-control tarea_basic_for_opinion" style="width:100%; height: 200px; text-align: justify;"></textarea>
            </div>
        </div>
    </div>

    <div class="form-group tarea_opinion_details">
        <label class="col-xs-2"></label>
        <div class="col-xs-9">
            <div>
                <label class="lbl_basic_for_opinion_fixed" style="width:100%; text-align: justify;"></label>
            </div>
        </div>
    </div>

    <div class="form-group section_emphasis_of_matter">
        <label class="col-xs-2">Emphasis of matter: </label>
        <div class="col-xs-8">
            <div class="input-group" style="width: 200px;">
                <input type="checkbox" name="hidden_emphasis_of_matter_checkbox" <?=!empty($this_independ_aud_report[0]['emphasis_of_matter'])?'checked':'';?>/>
                <input type="hidden" name="emphasis_of_matter_checkbox" value="<?=!empty($this_independ_aud_report[0]['emphasis_of_matter'])?1:0;?>"/>
            </div>
        </div>
    </div>
    <div class="form-group emphasis_details section_emphasis_of_matter" <?=empty($this_independ_aud_report[0]['emphasis_of_matter'])?'style="display:none;"':'';?> >
        <label class="col-xs-2"></label>
        <div class="col-xs-9">
        	<div>
        		<label>Details:</label>
            </div>
            <div>
            	<textarea class="form-control emphasis_input" style="width:100%; height: 200px;"><?php !empty($this_independ_aud_report[0]['emphasis_of_matter'])?$this_independ_aud_report[0]['emphasis_of_matter']: ''; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group section_other_matters">
        <label class="col-xs-2">Other matters: </label>
        <div class="col-xs-8">
            <div class="input-group" style="width: 200px;" >
                <input type="checkbox" name="hidden_other_matter_checkbox" <?=!empty($this_independ_aud_report[0]['other_matters_checkbox'])?($this_independ_aud_report[0]['other_matters_checkbox']) == 1?'checked':'':'';?>/>
                <input type="hidden" name="other_matter_checkbox" value="<?=!empty($this_independ_aud_report[0]['other_matters_checkbox'])?$this_independ_aud_report[0]['other_matters_checkbox']:0; ?>"/>
            </div>
        </div>
    </div>
    <div class="form-group other_matter_1" <?=!empty($this_independ_aud_report[0]['other_matters_checkbox'])?($this_independ_aud_report[0]['other_matters_checkbox'] == 1)?'':'style="display:none;"':'style="display:none;"';?>>
        <label class="col-xs-2"></label>
        <div class="col-xs-6 mb-md">
        	<div class="col-xs-5">
            	<label>Last year is not audited:</label>
            </div>
        	<div class="col-xs-6">
                <input type="checkbox" name="hidden_ly_not_aud_checkbox" <?=!empty($this_independ_aud_report[0]['last_year_not_audited'])?($this_independ_aud_report[0]['last_year_not_audited']==1)?'checked':'':'checked';?>/>
                <!-- <?php echo $this_independ_aud_report[0]['last_year_not_audited']; ?> -->
                <input type="hidden" name="ly_not_aud_checkbox" value="<?=isset($this_independ_aud_report[0]['last_year_not_audited'])?$this_independ_aud_report[0]['last_year_not_audited']:1;?>"/>
            </div>
        </div>
    </div>
    <div class="form-group other_matter_2" <?=!empty($this_independ_aud_report[0]['other_matters_checkbox'])?($this_independ_aud_report[0]['other_matters_checkbox'] == 1)?'':'style="display:none;"':'style="display:none;"';?>>
        <label class="col-xs-2"></label>
        <div class="col-xs-6">
        	<div class="col-xs-5">
            	<label>Last year is audited by other auditors:</label>
            </div>
        	<div class="col-xs-6">
                <input type="checkbox" name="hidden_ly_other_aud_checkbox" <?=!empty($this_independ_aud_report[0]['last_year_audited_by_other_company'])?($this_independ_aud_report[0]['last_year_audited_by_other_company']==1)?'checked':'':'';?>/>
                <input type="hidden" name="ly_other_aud_checkbox" value="<?=isset($this_independ_aud_report[0]['last_year_audited_by_other_company'])?$this_independ_aud_report[0]['last_year_audited_by_other_company']:0;?>"/>
            </div>
        </div>
    </div>

    <div class="form-group other_matter_input" <?=!empty($this_independ_aud_report[0]['last_year_audited_by_other_company'])?($this_independ_aud_report[0]['last_year_audited_by_other_company'] == 1)?'':'style="display:none;"':'style="display:none;"';?>>
        <label class="col-xs-2"></label>
        <div class="col-xs-6">
            <div class="col-xs-5">
                <label>Last year Auditor Report Type:</label>
            </div>
            <div class="col-xs-3">
                <?php
                    echo form_dropdown('fs_ly_report_opinion', $dp_opinion, $this_independ_aud_report[0]['last_year_audit_report_opinion_type'], 'id="fs_ly_report_opinion" class="form-control fs_ly_report_opinion fs_aud_dropdown" style="width:150%;" style="width:100px;" onchange="update_other_matter_display_content()"');
                ?>
            </div>
        </div>
    </div>
    <div class="form-group other_matter_input" <?=!empty($this_independ_aud_report[0]['last_year_audited_by_other_company'])?($this_independ_aud_report[0]['last_year_audited_by_other_company'] == 1)?'':'style="display:none;"':'style="display:none;"';?>>
        <label class="col-xs-2"></label>
        <div class="col-xs-6">
            <div class="col-xs-5">
                <label>Date of Auditor's Report:</label>
            </div>
            <div class="col-xs-6">
                <div class="input-group mb-md" style="width: 200px;">
                    <span class="input-group-addon">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                    <input type="text" class="form-control valid datepicker_input" id="date_of_auditors_report" name="date_of_auditors_report" data-date-format="dd MM yyyy" data-plugin-datepicker="" value="<?=$this_independ_aud_report[0]['date_of_auditors_report'];?>" placeholder="DD MM YYYY" onchange="update_other_matter_display_content()" />
                </div>
            </div>
        </div>
    </div>

    <div class="form-group other_matter_details" <?=!empty($this_independ_aud_report[0]['other_matters_checkbox'])?($this_independ_aud_report[0]['other_matters_checkbox'] == 1)?'':'style="display:none;"':'style="display:none;"';?>>
    	<label class="col-xs-2"></label>
        <div class="col-xs-9">
        	<div>
            	<label>
            		<strong>Other matters</strong>
            	</label>
            </div>
            <div>
            	<label class="lbl_other_matter"><?=!empty($this_independ_aud_report[0]['other_matters'])?$this_independ_aud_report[0]['other_matters']:'';?></label>
            </div>
        </div>
    </div>
    <div class="form-group section_key_audit_matter_checkbox">
        <label class="col-xs-2">Key Audit Matter </label>
        <div class="col-xs-8">
            <div class="input-group" style="width: 200px;">
                <input type="checkbox" name="hidden_key_audit_matter_checkbox" <?=((!empty($this_independ_aud_report[0]['key_audit_matter'])) || (!empty($this_independ_aud_report[0]['key_audit_matter_input'])))?'checked':'';?>/>
                <input type="hidden" name="key_audit_matter_checkbox" value="<?=((!empty($this_independ_aud_report[0]['key_audit_matter'])) || (!empty($this_independ_aud_report[0]['key_audit_matter_input'])))?1:0;?>"/>
            </div>
        </div>
    </div>

    <div class="form-group mb-md section_key_audit_matter">
        <label class="col-xs-2"></label>
        <div class="col-xs-9">
        	<div>
        		<label class="lbl_key_aud_matter" style="text-align: justify;"></label>
        		<!-- <label id="lbl_key_aud_matter_type">Report is </label> -->
        		<!-- <?php
					echo form_dropdown('fs_key_audit_matter', $dp_key_aud_matter, '', 'id="fs_key_audit_matter" class="form-control fs_key_audit_matter" style="width:150%;" style="width:200px;" onchange="key_audit_matter()"');
				?> -->
            </div>
        </div>
    </div>
    <div class="form-group key_audit section_key_audit_matter_input">
        <label class="col-xs-2"></label>
        <div class="col-xs-9">
            <div>
                <!-- <label><strong>Key audit matter</strong></label> -->
            </div>
            <div>
                <textarea class="form-control tarea_desc_key_audit" style="width:100%; height: 200px; text-align: justify;"><?=!empty($this_independ_aud_report[0]['key_audit_matter_input'])?$this_independ_aud_report[0]['key_audit_matter_input']: ''; ?></textarea>
            </div>
        </div>
    </div>
    <!-- <div class="form-group key_audit_matter_details">
        <label class="col-xs-2"></label>
        <div class="col-xs-9">
        	<div>
        		<label><strong>Key audit matter</strong></label>
        	</div>
        	<div>
        		<label class="lbl_key_aud_matter" style="display:justify;"></label>
            </div>
        </div>
    </div> -->

    <!-- <div class="form-group">
		<div class="col-sm-12">
			<input type="button" class="btn btn-primary submit_aud_report" id="submit_aud_report" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</div> -->
</form>

<?php
    }
    else
    {
        echo $accountant_compilation_report;
    }
?>

<div class="loading" id="loadingAudReport" style="display: none;">Loading&#8230;</div>

<script type="text/javascript">
    var fs_company_info = <?php echo json_encode($fs_company_info); ?>;

    $(".fs_aud_dropdown").select2({ minimumResultsForSearch: -1 });

    // console.log('<?php echo !empty($this_independ_aud_report[0]['key_audit_matter_input'])?$this_independ_aud_report[0]['key_audit_matter_input']: ''; ?>');
	var report_opinion_template_fixed_1 = <?php echo json_encode($report_opinion_template_fixed_1); ?>;
    var report_opinion_template_fixed_2 = <?php echo json_encode($report_opinion_template_fixed_2); ?>;

    var report_basic_opinion_template_input = <?php echo json_encode($report_basic_opinion_template_input); ?>;
    var report_basic_opinion_template_fixed = <?php echo json_encode($report_basic_opinion_template_fixed); ?>;

	var key_aud_matter_template = <?php echo json_encode($key_aud_matter_template); ?>;
	var emphasis_of_matter_template = <?php echo json_encode($emphasis_of_matter_template); ?>;
	var other_matter_template = <?php echo json_encode($other_matter_template); ?>;
	var disclaimer_of_opinion_template = <?php echo json_encode($disclaimer_of_opinion_template); ?>;

	var this_independ_aud_report = <?php echo json_encode($this_independ_aud_report); ?>;
    console.log(this_independ_aud_report);

	if(this_independ_aud_report.length === 0)
	{
		var empty_obj = {
			basic_for_opinion: '',
			disclaimer_of_opinion: '',
			emphasis_of_matter : '',
			fs_company_info_id : '',
			fs_opinion_type_id : '',
			id : '',
			key_audit_matter : '',
			last_year_audited_by_other_company : '',
            last_year_audit_report_opinion_type : '',
            date_of_auditors_report : '',
			last_year_not_audited : '',
			opinion : '',
			other_matters : '',
			other_matters_checkbox : ''
		};
		this_independ_aud_report.push(empty_obj);
	}
</script>

<script src="themes/default/assets/js/financial_statement/partial_aud_report.js" charset="utf-8"></script>

