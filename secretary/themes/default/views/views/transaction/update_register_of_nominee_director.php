<div id="w2-nominee_director" class="panel">
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
	<button type="button" class="controller-collapsible"><span style="font-size: 2.4rem;">Current Register of Nominee Director</span></button>
	<div id="controller_info" class="controller-content">
		<div style="padding-top: 18px; padding-bottom: 18px;">
			<table style="width: 100%;" class="table table-bordered table-striped mb-none current_nominee_director_table display nowrap" id="current_nominee_director_table">
                <thead>
                	<tr>
		                <th style="text-align: center; width: 150px !important;">Date of entry/update</th>
		                <th style="text-align: center; width: 200px !important;">Name of Nominee Director</th>
		                <th style="text-align: center">Particulars of nominator</th>
		                <th style="text-align: center; width: 200px !important;">Supporting Docs</th>
		                <th style="width: 100px !important;"></th>
	                </tr>
	            </thead>
	            <tbody id="table_body_current_nominee_director">
				</tbody>
            </table>
        </div>
	</div>
	<button type="button" class="controller-collapsible"><span style="font-size: 2.4rem;">Latest Register of Nominee Director</span></button>
	<div id="controller_info" class="controller-content">
		<div style="padding-top: 18px; height: 65px;">
			<a href="javascript: void(0);" class="btn btn-primary" id="create_nominee_director" class="create_nominee_director" style="float: right;">Create</a>
		</div>
		<div style="padding-bottom: 18px;">
			<table style="width: 100%;" class="table table-bordered table-striped mb-none latest_nominee_director_table display nowrap" id="latest_nominee_director_table">
                <thead>
                	<tr>
		                <!-- <th style="text-align: center; width: 150px !important;">Date of entry/update</th> -->
		                <th style="text-align: center; width: 200px !important;">Name of Nominee Director</th>
		                <th style="text-align: center">Particulars of nominator</th>
		                <th style="text-align: center; width: 200px !important;">Supporting Docs</th>
		                <th style="width: 100px !important;"></th>
	                </tr>
	            </thead>
	            <tbody id="table_body_latest_nominee_director">
				</tbody>
            </table>
        </div>
	</div>
</div>
<div class="loading" id='loadingControllerMessage' style='display:none; z-index: 9999 !important;'>Loading&#8230;</div>
<!-- update_register_of_nominee_director -->
<script src="themes/default/assets/js/update_register_of_nominee_director.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<div id="modal_nominee_director" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Update Register of Nominee Director</h2>
			</header>
			<form id="form_register_of_nominee_director" autocomplete="off" enctype="multipart/form-data">
				<div class="panel-body register_of_nominee_director">
					<div class="col-md-12">
						<span style="font-size: 1.8rem;"><u>Nominee Director</u></span>
					</div>
					<input type="hidden" id="client_nominee_director_id" value="" name="client_nominee_director_id"/>
					<input type="hidden" id="nomi_company_code" value="" name="nomi_company_code"/>
					<div class="col-md-6">
						<div class="div_nd_id">
                        	<label class="font_14" for="nd_gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nd_gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="nd_identification_no" required="required"/>
                            <a class="nd_add_office_person_link" href="" style="cursor:pointer;" id="nd_add_office_person_link" target="_blank" hidden onclick="nd_add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
                            <a class="nd_a_refresh_controller" href="javascript:void(0)" style="cursor:pointer;" id="nd_refresh_controller" onclick="nd_refresh_controller()"><div style="cursor:pointer;"><span class="nd_refresh_controller" style="font-weight:bold" hidden>Refresh</span></div></a>
                            <a class="nd_view_edit_person" href="" style="cursor:pointer;" id="nd_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
                        </div>
					</div>

					<div class="col-md-6">
						<div class="div_nd_name">
                        	<label class="font_14" for="nd_name">Name<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nd_name" class="form-control" placeholder="Name" value="" name="nd_name" required readonly="true" />
                            <!-- <input type="hidden" id="nd_controller_id" value="" name="nd_client_controller_id"/> -->
                            <!-- <input type="hidden" id="nd_company_code" value="" name="nd_company_code"/> -->
                            <input type="hidden" id="nd_officer_id" value="" name="nd_officer_id"/>
                            <input type="hidden" id="nd_officer_field_type" value="" name="nd_officer_field_type"/>
                            <input type="hidden" id="nd_date_entry" name="nd_date_entry" value="">
                        </div>
					</div>
					<!-- <div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
						<div class="col-md-6">
							<div class="div_nd_date_entry">
	                        	<label class="font_14" for="nd_date_entry">Date of entry/update<span class="color_red">*</span></label>
	                            <div class="input-group" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="nd_date_entry" name="nd_date_entry" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
					</div> -->
					<div class="col-md-12" style="margin-top: 15px;">
						<span style="font-size: 1.8rem;"><u>Particulars of Nominator</u></span>
					</div>

                    <div class="col-md-6">
						<div class="div_nomi_id">
                        	<label class="font_14" for="nomi_gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nomi_gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="nomi_identification_no" required="required"/>
                            <a class="nomi_add_office_person_link" href="" style="cursor:pointer;" id="nomi_add_office_person_link" target="_blank" hidden onclick="nomi_add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
                            <a class="nomi_a_refresh_controller" href="javascript:void(0)" style="cursor:pointer;" id="nomi_refresh_controller" onclick="nomi_refresh_controller()"><div style="cursor:pointer;"><span class="nomi_refresh_controller" style="font-weight:bold" hidden>Refresh</span></div></a>
                            <a class="nomi_view_edit_person" href="" style="cursor:pointer;" id="nomi_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
                        </div>
					</div>
					
					<div class="col-md-6">
						<div class="div_nomi_name">
                        	<label class="font_14" for="nomi_name">Name<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nomi_name" class="form-control" placeholder="Name" value="" name="nomi_name" required="required" readonly="true" />
                            <!-- <input type="hidden" id="nomi_controller_id" value="" name="nomi_client_controller_id"/> -->
                            <!-- <input type="hidden" id="nomi_company_code" value="" name="nomi_company_code"/> -->
                            <input type="hidden" id="nomi_officer_id" value="" name="nomi_officer_id"/>
                            <input type="hidden" id="nomi_officer_field_type" value="" name="nomi_officer_field_type"/>
                        </div>
					</div>
					<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
						<div class="col-md-6">
							<div class="div_date_become_nominator">
	                        	<label class="font_14" for="date_become_nominator">Date which becomes a nominator<span class="color_red">*</span></label>
	                            <div class="input-group" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="date_become_nominator" name="date_become_nominator" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
						<div class="col-md-6">
							<div>
	                        	<label class="font_14" for="date_ceased_nominator">Date ceased as nominator</label>
	                            <div class="input-group" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="date_ceased_nominator" name="date_ceased_nominator" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
					</div>
					<div class="col-md-12">
                    	<label class="font_14">Supporting Document (if any)</label>
                    	<div class="input-group">
                    		<input type="file" style="display:none" id="nd_supporting_document" class="nd_supporting_document" name="nd_supporting_document">
                    		<label for="nd_supporting_document" class="btn btn-primary">Attachment</label><br/>
                    		<span class="nd_file_name"></span>
                    		<input type="hidden" class="nd_hidden_supporting_document" name="nd_hidden_supporting_document" value=""/>
                    	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" name="saveRegNomineeDirector" id="saveRegNomineeDirector">Save</button>
					<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelRegController" value="Cancel">
				</div>
			</form>
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