<div id="w2-controller" class="panel">
	<div class="form-group">
		<label class="col-sm-2" for="w2-username">Registration No: </label>
		<div class="col-sm-8">
			<div style="width: 100%;">
				<div style="width: 75%;float:left;margin-right: 20px;">
					<label id="registration_no"></label>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2" for="w2-username">Company Name: </label>
		<div class="col-sm-8">
			<div style="width: 100%;">
				<div style="width: 75%;float:left;margin-right: 20px;">
					<label id="company_name"></label>
				</div>
			</div>
		</div>
	</div>
	<button type="button" class="controller-collapsible"><span style="font-size: 2.4rem;">Current Register of Controller</span></button>
	<div id="controller_info" class="controller-content">
		<!-- <div style="padding-top: 18px; height: 65px;">
			<div style="font-size: 1.4rem;float:left;padding-top:5px; padding-right: 5px; height: 30px;"><span>Filter: </span></div>
			<select class="form-control" id="search_filter_categoryposition" name="search_filter_categoryposition" style="float:left; width: 100px;">
				<option value="all" <?=isset($_POST['search_filter_categoryposition'])=='all'?'selected':'';?>>All</option>
				<option value="individual" <?=isset($_POST['search_filter_categoryposition'])=='individual'?'selected':'';?>>Individual</option>
				<option value="corporate" <?=isset($_POST['search_filter_categoryposition'])=='corporate'?'selected':'';?>>Corporate</option>
			</select> -->

			<!-- <a href="javascript: void(0);" class="btn btn-primary" id="create_controller" class="create_controller" style="float: right;">Create</a> -->
			<!-- <a href="javascript: void(0);" class="btn btn-primary" id="refresh_controller" class="refresh_controller" style="float: right; margin-right: 10px;">Refresh</a> -->
		<!-- </div> -->
		<div style="padding-top: 18px; padding-bottom: 18px;">
			<table style="width: 100%;" class="table table-bordered table-striped mb-none" id="current_controller_table">
	            <thead>
	                <tr>
		                <th style="text-align: center">Notice sent</th>
		                <th style="text-align: center">Confirmation Received</th>
		                <th style="text-align: center">Date of entry/update</th>
		                <th style="text-align: center">Controller Particulars</th>
		                <th style="text-align: center">Supporting Docs</th>
		                <th></th>
	                </tr>
	            </thead>
	            <tbody id="table_body_current_controller">
				</tbody>
	        </table>
		</div>
	</div>
	<button type="button" class="controller-collapsible"><span style="font-size: 2.4rem;">Latest Register of Controller</span></button>
	<div id="controller_info" class="controller-content">
		<div style="padding-top: 18px; height: 65px;">
			<!-- <div style="font-size: 1.4rem;float:left;padding-top:5px; padding-right: 5px; height: 30px;"><span>Filter: </span></div>
			<select class="form-control" id="search_filter_categoryposition" name="search_filter_categoryposition" style="float:left; width: 100px;">
				<option value="all" <?=isset($_POST['search_filter_categoryposition'])=='all'?'selected':'';?>>All</option>
				<option value="individual" <?=isset($_POST['search_filter_categoryposition'])=='individual'?'selected':'';?>>Individual</option>
				<option value="corporate" <?=isset($_POST['search_filter_categoryposition'])=='corporate'?'selected':'';?>>Corporate</option>
			</select> -->

			<a href="javascript: void(0);" class="btn btn-primary" id="create_controller" class="create_controller" style="float: right;">Create</a>
			<!-- <a href="javascript: void(0);" class="btn btn-primary" id="refresh_controller" class="refresh_controller" style="float: right; margin-right: 10px;">Refresh</a> -->
		</div>
		<div style="padding-bottom: 18px;">
			<table style="width: 100%;" class="table table-bordered table-striped mb-none" id="latest_controller_table">
	            <thead>
	                <tr>
		                <th style="text-align: center">Notice sent</th>
		                <!-- <th style="text-align: center">Confirmation Received</th> -->
		                <!-- <th style="text-align: center">Date of entry/update</th> -->
		                <th style="text-align: center">Controller Particulars</th>
		                <th style="text-align: center">Supporting Docs</th>
		                <th></th>
	                </tr>
	            </thead>
	            <tbody id="table_body_latest_controller">
				</tbody>
	        </table>
		</div>
	</div>
</div>
<div class="loading" id='loadingControllerMessage' style='display:none; z-index: 9999 !important;'>Loading&#8230;</div>
<!-- update_register_of_controller -->
<script src="themes/default/assets/js/transaction_update_register_of_controller.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<div id="modal_controller" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Update Register of Controller</h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<label class="font_14">Category:&nbsp;&nbsp;</label>
						<label><input type="radio" id="reg_cont_individual_edit" name="field_type" value="Individual" class="check_stat" data-information="individual" checked="checked"/>&nbsp;&nbsp;Individual</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="radio" id="reg_cont_company_edit" name="field_type" value="company" class="check_stat" data-information="company"/>&nbsp;&nbsp;Corporate</label>
					</div>
					<form id="form_individual_register_of_controller" autocomplete="off" enctype="multipart/form-data">
						<div class="individual_register_controller">
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important; margin-bottom: 15px;">
								<div class="col-md-6">
									<div class="div_identification_no">
			                        	<label class="font_14" for="gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="identification_no" required="required"/>
			                            <a class="add_office_person_link" href="" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
			                            <a class="refresh_a_controller" href="javascript:void(0)" style="cursor:pointer;" id="refresh_controller" hidden onclick="refresh_person()"><div style="cursor:pointer;"><span class="refresh_controller" style="font-weight:bold">Refresh</span></div></a>
			                            <a class="indi_view_edit_person" href="" style="cursor:pointer;" id="indi_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>

			                        </div>
								</div>
								<div class="col-md-6">
									<div class="div_controller_name">
			                        	<label class="font_14" for="individual_controller_name">Name<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="individual_controller_name" class="form-control" placeholder="Name" value="" name="individual_controller_name" required="required" readonly="true"/>
			                            <input type="hidden" id="transaction_client_controller_id" value="" name="transaction_client_controller_id"/>
			                            <input type="hidden" id="company_code" value="" name="company_code"/>
			                            <input type="hidden" id="officer_id" value="" name="officer_id"/>
			                            <input type="hidden" id="officer_field_type" value="" name="officer_field_type"/>
			                            <!-- <input type="hidden" id="date_of_birth" value="" name="date_of_birth"/>
			                            <input type="hidden" id="nationality" value="" name="nationality"/>
			                            <input type="hidden" id="controller_address" value="" name="controller_address"/> -->
			                        </div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="div_date_appoint">
		                        	<label class="font_14" for="aliases">Date appointed as registrable controller<span class="color_red">*</span></label>
		                            <div class="input-group" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_appointed" name="date_appointed" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div>
							</div>
							<div class="col-md-6">
								<div>
		                        	<label class="font_14" for="aliases">Date ceased as registrable controller</label>
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_ceased" name="date_ceased" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div>
							</div>
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
								<div class="col-md-6">
									<div>
			                        	<label class="font_14" for="aliases">Date of notice</span></label>
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_of_notice" name="date_of_notice" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div>
								</div>
								<div class="col-md-6">
									<!-- <div>
			                        	<label class="font_14" for="aliases">Date of entry/update</span></label>
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_of_entry" name="date_of_entry" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div> -->
			                        <input type="hidden" id="date_of_entry" name="date_of_entry" value="">
			                        <input type="hidden" id="radio_individual_confirm_registrable_controller" value="" name="radio_individual_confirm_registrable_controller"/>
			                        <input type="hidden" id="date_confirmation" name="date_confirmation" value="">
			                        <!-- <div style="margin-bottom: 0px !important;">
										<div>
				                    		<label class="font_14" for="aliases">Confirmation by Registrable Controller<span class="color_red">*</span></label>
				                    	</div>
				                    	<div class="div_radio_confirm_controller">
				                        	<label><input type="radio" id="individual_is_confirm_registrable_controller" name="individual_confirm_registrable_controller" value="yes" class="check_is_confirm_registrable_controller" data-information="yes"/>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<label><input type="radio" id="individual_not_confirm_registrable_controller" name="individual_confirm_registrable_controller" value="no" class="check_is_confirm_registrable_controller" data-information="no"/>&nbsp;&nbsp;No</label>
											<input type="hidden" id="radio_individual_confirm_registrable_controller" value="" name="radio_individual_confirm_registrable_controller"/>
										</div>
										<div class="individual_div_date_confirmation div_date_confirmation" style="display: none;">
				                            <div class="input-group mb-md" style="width: 100%;">
												<span class="input-group-addon">
													<i class="far fa-calendar-alt"></i>
												</span>
												<input type="text" class="form-control input-xs" id="date_confirmation" name="date_confirmation" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
											</div>
										</div>
				                    </div> -->
								</div>
							</div>
							
							<div class="col-md-12">
								<div>
		                        	<label class="font_14" for="aliases">Supporting Document (if any)</label>
		                        	<div class="input-group">
		                        		<input type="file" style="display:none" id="supporting_document" class="supporting_document" name="supporting_document">
		                        		<label for="supporting_document" class="btn btn-primary" class="supporting_document">Attachment</label><br/>
		                        		<span class="file_name"></span>
		                        		<input type="hidden" class="hidden_supporting_document" name="hidden_supporting_document" value=""/>
		                        	</div>
		                        </div>
							</div>
						</div>
					</form>
					<form id="form_company_register_of_controller" autocomplete="off" enctype="multipart/form-data">
						<div class="company_register_controller" style="display: none;">
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important; margin-bottom: 15px;">
								<div class="col-md-6">
									<div class="div_controller_uen">
			                        	<label class="font_14" for="gid_add_controller_officer">UEN<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="gid_add_controller_officer" class="form-control" placeholder="UEN" value="" name="controller_uen" required="required"/>
			                            <a class="add_office_person_link" href="" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
			                            <a class="refresh_a_controller" href="javascript:void(0)" style="cursor:pointer;" id="refresh_a_controller" hidden onclick="refresh_person()"><div style="cursor:pointer;"><span class="refresh_controller" style="font-weight:bold">Refresh</span></div></a>
			                            <a class="corp_view_edit_person" href="" style="cursor:pointer;" id="corp_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="div_entity_name">
			                        	<label class="font_14" for="entity_name">Entity Name<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="entity_name" class="form-control" placeholder="Entity Name" value="" name="entity_name" required="required" readonly="true"/>
			                            <input type="hidden" id="transaction_client_controller_id" value="" name="transaction_client_controller_id"/>
			                            <input type="hidden" id="company_code" value="" name="company_code"/>
			                            <input type="hidden" id="officer_id" value="" name="officer_id"/>
			                            <input type="hidden" id="officer_field_type" value="" name="officer_field_type"/>
			                            <!-- <input type="hidden" id="date_of_birth" value="" name="date_of_birth"/>
			                            <input type="hidden" id="nationality" value="" name="nationality"/>
			                            <input type="hidden" id="controller_address" value="" name="controller_address"/> -->
			                        </div>
								</div>
							</div>
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
								<div class="col-md-6">
									<div class="div_date_appoint">
			                        	<label class="font_14" for="date_appointed">Date appointed as registrable controller<span class="color_red">*</span></label>
			                            <div class="input-group" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_appointed" name="date_appointed" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div>
			                        	<label class="font_14" for="date_ceased">Date ceased as registrable controller</label>
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_ceased" name="date_ceased" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div>
								</div>
							</div>
							<div class="col-md-6">
								<div>
		                        	<label class="font_14" for="aliases">Date of notice</span></label>
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_of_notice" name="date_of_notice" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div>
							</div>
							<div class="col-md-6">
								<input type="hidden" id="date_of_entry" name="date_of_entry" value="">
		                        <input type="hidden" id="radio_corp_confirm_registrable_controller" value="" name="radio_corp_confirm_registrable_controller"/>
		                        <input type="hidden" id="date_confirmation" name="date_confirmation" value="">
								<!-- <div>
		                        	<label class="font_14" for="aliases">Date of entry/update</span></label>
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_of_entry" name="date_of_entry" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div> -->
		                        <!-- <div style="margin-bottom: 0px !important;">
									<div>
			                    		<label class="font_14">Confirmation by Registrable Controller<span class="color_red">*</span></label>
			                    	</div>
			                    	<div class="div_radio_corp_confirm_registrable_controller">
			                        	<label><input type="radio" id="corp_is_confirm_registrable_controller" name="corp_confirm_registrable_controller" value="yes" class="check_is_confirm_registrable_controller" data-information="yes"/>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label><input type="radio" id="corp_not_confirm_registrable_controller" name="corp_confirm_registrable_controller" value="no" class="check_is_confirm_registrable_controller" data-information="no"/>&nbsp;&nbsp;No</label>
										<input type="hidden" id="radio_corp_confirm_registrable_controller" value="" name="radio_corp_confirm_registrable_controller"/>
									</div>
									<div class="corp_div_date_confirmation" style="display: none;">
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_confirmation" name="date_confirmation" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
									</div>
			                    </div> -->
							</div>
							<div class="col-md-12">
								<div>
		                        	<label class="font_14">Supporting Document (if any)</label>
		                        	<div class="input-group">
		                        		<input type="file" style="display:none" id="corp_supporting_document" class="supporting_document" name="supporting_document">
		                        		<label for="corp_supporting_document" class="btn btn-primary" class="supporting_document">Attachment</label><br/>
		                        		<span class="corp_file_name"></span>
		                        		<input type="hidden" class="corp_hidden_supporting_document" name="hidden_supporting_document" value=""/>
		                        	</div>
		                        </div>
							</div>
						</div>
					</form>
				</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" name="saveRegController" id="saveRegController">Save</button>
				<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelRegController" value="Cancel">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		var coll = document.getElementsByClassName("collapsible");
		for (var g = 0; g < coll.length; g++) {
		    coll[g].classList.toggle("incorp_active");
		    coll[g].nextElementSibling.style.maxHeight = "100%";
		}
		for (var i = 0; i < coll.length; i++) {
		  coll[i].addEventListener("click", function() {
		    this.classList.toggle("incorp_active");
		    var content = this.nextElementSibling;
		    if (content.style.maxHeight){
		      content.style.maxHeight = null;
		      //content.style.border = "0px";
		    } else {
		      content.style.maxHeight = "100%";
		      //content.style.border = "1px solid #FEE8A6";
		    } 
		  });
		}
	});
</script>