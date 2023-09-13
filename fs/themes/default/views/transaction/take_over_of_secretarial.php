<?php
	$now = getDate();
	if ($this->session->userdata('transaction_company_code') && $this->session->userdata('transaction_company_code') != '')
	{
		$transaction_company_code = $this->session->userdata('transaction_company_code');
		//echo json_encode($company_code);
	} 
	else 
	{
		$transaction_company_code = 'company_'.$now[0];
		$this->session->set_userdata('transaction_company_code', $transaction_company_code);
	}
	
	
?>
<section class="panel" style="margin-bottom: 0px;">
	<div class="panel-body">
		<div class="modal-wrapper">
			<div class="modal-text">

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Company Information</span></button>
				<div id="w2-companyInfo" class="incorp_content">
				  <?php echo form_open_multipart('', array('id' => 'upload_company_info', 'enctype' => "multipart/form-data")); ?>

						<div class="hidden"><input type="text" class="form-control transaction_company_code" name="transaction_company_code" value="<?=$transaction_company_code?>"/></div>
						
						<!-- <div class="form-group" style="padding: 0 18px; margin-top: 20px;">
							<span style="font-size: 2.4rem;">Company Profile</span>
						</div> -->
						<div class="form-group" style="margin-top: 20px;">
							<label class="col-sm-2" for="w2-username">Client Code: </label>
							<div class="col-sm-4">
								<input type="text" style="text-transform:uppercase" class="form-control" maxlength="20" id="client_code" name="client_code" value="<?=$transaction_client->client_code?>" >
								<div id="form_client_code"></div>
								
							</div>
							<!-- <div class="col-sm-4">
								
									<div style="float: right;">
										<select id="acquried_by" class="form-control acquried_by" style="text-align:right; text-transform:uppercase;" name="acquried_by">
						                    <option value="0">Acquried by:</option>
						                </select>
						                <div id="form_acquried_by"></div>
									</div>
								
							</div> -->
						</div>
						
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Company Name: </label>
							<div class="col-sm-8">
								<input type="text" style="text-transform:uppercase" class="form-control" id="edit_company_name" name="company_name"  value="<?=$transaction_client->company_name?>">
								<div id="form_company_name"></div>
								
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label" for="w2-username">Former Name (if any): </label>
							<div class="col-sm-8">
								<textarea class="form-control" style="text-transform:uppercase" id="former_name" name="former_name" /><?=$client->former_name?></textarea>
								<div id="form_former_name"></div>
								
							</div>
						</div> -->
						
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label" for="w2-username">Incorporation Date: </label>
							<div class="col-sm-8">
								<div class="input-group mb-md" style="width: 200px;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control valid" id="date_todolist" name="incorporation_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=$client->incorporation_date?>" placeholder="DD/MM/YYYY">
									

									
								</div>
								<div id="form_incorporation_date"></div>
								
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Company Type: </label>
							<div class="col-sm-8">
								<select id="company_type" class="form-control company_type" style="text-align:right; width: 400px;" name="company_type">
				                    <option value="0">Select Company Type</option>
				                </select>
				                <div id="form_company_type"></div>
				             

				                

								
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label" for="w2-username">Status: </label>
							<div class="col-sm-8">
								
								<select id="status" class="form-control status" style="text-align:right;" name="status">
				                    <option value="0">Select Status</option>
				                </select>
				                <div id="form_status"></div>
									
								
							</div>
						</div> -->
						<!-- <span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Principal Activities</span> -->
						<div class="form-group" style="margin-top: 20px">
							<label class="col-sm-2" for="w2-username">Principal Activity 1: </label>
							<div class="col-sm-8">
								<input type="text" style="text-transform:uppercase" class="form-control" id="activity1" name="activity1" value="<?=$transaction_client->activity1?>" >
								<div id="form_activity1"></div>
								
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Principal Activity 2: </label>
							<div class="col-sm-8">
								<input type="text" style="text-transform:uppercase" class="form-control" id="activity2" name="activity2" value="<?=$transaction_client->activity2?>" >
								<div id="form_activity2"></div>
								
							</div>
							
						</div>
						
						<!-- <span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Registered Office Address</span> -->
						<div class="form-group" style="margin-top: 20px">
							<label class="col-xs-2" for="w2-username">Use Our Registered Address: </label>
							<div class="col-xs-8">
								<div class="col-xs-1">
									<input type="checkbox" class="" id="use_registered_address" name="use_registered_address" onclick="fillRegisteredAddressInput(this);">
								</div>
								<div class="col-xs-6 service_reg_off_area" style="display: none;">
									<select id="service_reg_off" class="form-control service_reg_off" style="text-align:right;" name="service_reg_off">
					                    <option value="0">Select Service Name</option>
					                </select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Registered Office Address: </label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Postal Code :</label>
									</div>
									<div style="width: 65%;float:left;margin-bottom:5px;">
										<div class="" style="width: 20%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="postal_code" name="postal_code" value="" maxlength="6">
										</div>
										<div id="form_postal_code"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Street Name :</label>
									</div>
									<div style="width: 71%;float:left;margin-bottom:5px;">
										<div class="" style="width: 100%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="street_name" name="street_name" value="<?=$transaction_client->street_name?>">
										</div>
										<div id="form_street_name"></div>
					
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Building Name :</label>
									</div>
									<div style="width: 71%;float:left;margin-bottom:5px;">
										<div class="" style="width: 100%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="<?=$transaction_client->building_name?>">
										</div>
										<div id="form_street_name"></div>
					
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
								<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=$transaction_client->unit_no1?>" maxlength="3">
								<label style="float: left; margin-right: 10px;" >-</label>
								<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=$transaction_client->unit_no2?>" maxlength="10">
							</div>
						</div>
						<div class="form-group hidden">
							<label class="col-sm-2" for="w2-username">Listed Company: </label>
							<div class="col-sm-8">
								<input type="checkbox" class="" id="listedcompany" name="listedcompany" <?=$transaction_client->listed_company?'checked':'';?> />
							</div>
						</div>
					<?= form_close(); ?>
					<div class="form-group">
						<div class="col-sm-12">
							<input type="button" class="btn btn-primary submitCompanyInfo" id="submitCompanyInfo" value="Save" style="float: right; margin-bottom: 20px;">
						</div>
					</div>
				</div>
				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Officers</span></button>
				<div id="w2-officer" class="incorp_content">
					<form id="officer_form" style="margin-top: 20px;">
						<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>
						<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
							<thead>
								<tr>
									<th id="id_position" style="text-align: center;width:270px">Position</th>
									<th id="id_header" style="text-align: center;width:270px">ID</th>
									<th style="text-align: center;width:270px" id="id_name">Name</th>
									
									<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="officers_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a></th>
								</tr>
								
							</thead>
							<tbody id="body_officer">
							</tbody>
							
						</table>

						<div class="form-group">
							<div class="col-sm-12">
								<input type="button" class="btn btn-primary submitOfficerInfo" id="submitOfficerInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
							</div>
						</div>
					</form>
				</div>

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Filing</span></button>
				<div id="w2-filing" class="incorp_content">
				  <?php echo form_open_multipart('', array('id' => 'filing_form', 'enctype' => "multipart/form-data")); ?>

					<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>

					<div class="hidden"><input type="text" class="form-control filing_id" name="filing_id" value="<?=$filing->id?>"/></div>
					<!-- <div style="margin-bottom: 23px; margin-top: 20px;">
						<h3>Filing</h3>
					</div> -->
					<div class="form-group" style="margin-top: 20px;">
						<label class="col-xs-3" for="w2-show_all">Year End: </label>
						<div class="col-sm-9 form-inline">
							<div class="input-bar">
							    <div class="input-bar-item">
							     
							         <div class="year_end_group">
							            <div class="input-group" style="width: 200px;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control year_end_date" id="year_end_date" name="year_end" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
										</div>
										<div id="validate_year_end_date"></div>
							        </div>
							      
							    </div>

							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3" for="w2-DS1">Financial Year Cycle:</label>
							<div class="col-sm-3">
								<select id="financial_year_period" class="form-control financial_year_period" id="financial_year_period" style="width:200px;" name="financial_year_period">
				                  
				                </select>
							</div>

					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<input type="button" class="btn btn-primary submitFilingInfo" id="submitFilingInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
						</div>
					</div>
					<?= form_close(); ?>
				</div>

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Service Engagement</span></button>
				<div id="w2-billing" class="incorp_content">

					<!-- <div class="form-group" style="margin-bottom: 23px; margin-top: 20px;">
						<div class="col-sm-4">
							<h3>Billing Information</h3>
						</div>

					</div> -->
					<form id="billing_form" style="margin-top: 20px;">
						<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>
						<table class="table table-bordered table-striped table-condensed mb-none" id="billing_table"style="width: 1000px">
							<thead>
								<tr> 
									<th valign=middle style="width:210px;text-align: center">Service</th>
									<th valign=middle style="width:250px;text-align: center">Invoice Description</th>
									<th style="width:180px;text-align: center">Currency</th>
									<th style="width:180px;text-align: center">Amount</th>
									<th style="width:180px;text-align: center">Unit Pricing</th>
									<th style="width:80px;"><a href="javascript: void(0);" style="color: #D9A200;width:230px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add</span></a></th>
								</tr>
							</thead>
							<tbody id="body_billing_info">
							</tbody>
						</table>

						<div class="form-group">
							<div class="col-sm-12">
								<input type="button" class="btn btn-primary submitBillingInfo" id="submitBillingInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
							</div>
						</div>
					</form>
				</div>
				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Previous Secretarial Information</span></button>
				<div id="w2-previous_secretarial_info" class="incorp_content">
				  <?php echo form_open_multipart('', array('id' => 'previous_secretarial_form', 'enctype' => "multipart/form-data")); ?>

					<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>

					<div class="hidden"><input type="text" class="form-control previous_secretarial_info_id" name="previous_secretarial_info_id" value="<?=$previous_secretarial_info->id?>"/></div>
					<!-- <div style="margin-bottom: 23px; margin-top: 20px;">
						<h3>Filing</h3>
					</div> -->
					
					<div class="form-group" style="margin-top: 20px;">
						<label class="col-sm-2" for="w2-username">Company_name: </label>
						<div class="col-sm-8">
							<input type="text" style="text-transform:uppercase" class="form-control" maxlength="20" id="previous_secretarial_company_name" name="previous_secretarial_company_name">							
						</div>
					</div>
					<div class="form-group">
							<label class="col-sm-2" for="w2-username">Address: </label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Postal Code :</label>
									</div>
									<div style="width: 65%;float:left;margin-bottom:5px;">
										<div class="" style="width: 20%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="previous_secretarial_postal_code" name="previous_secretarial_postal_code" value="" maxlength="6">
										</div>
										<div id="form_previous_secretarial_postal_code"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Street Name :</label>
									</div>
									<div style="width: 71%;float:left;margin-bottom:5px;">
										<div class="" style="width: 100%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="previous_secretarial_street_name" name="previous_secretarial_street_name" value="">
										</div>
										<div id="form_previous_secretarial_street_name"></div>
					
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Building Name :</label>
									</div>
									<div style="width: 71%;float:left;margin-bottom:5px;">
										<div class="" style="width: 100%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="previous_secretarial_building_name" name="previous_secretarial_building_name" value="">
										</div>
										<div id="form_previous_secretarial_street_name"></div>
					
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
								<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="previous_secretarial_unit_no1" name="previous_secretarial_unit_no1" value="<?=$transaction_client->previous_secretarial_unit_no1?>" maxlength="3">
								<label style="float: left; margin-right: 10px;" >-</label>
								<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="previous_secretarial_unit_no2" name="previous_secretarial_unit_no2" value="" maxlength="10">
							</div>
						</div>
					<div class="form-group">
						<div class="col-sm-12">
							<input type="button" class="btn btn-primary submitPreviousSecretarialInfo" id="submitPreviousSecretarialInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
						</div>
					</div>
					<?= form_close(); ?>
				</div>

	<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
</section>
<script src="themes/default/assets/js/companyType.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>	
<script src="themes/default/assets/js/transaction_setup.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script type="text/javascript">
$('#postal_code').keyup(function(){
    if($(this).val().length == 6)
    {
        var zip = $(this).val();
        //var address = "068914";
        $.ajax({
          url:    'https://gothere.sg/maps/geo',
          dataType: 'jsonp',
          data:   {
            'output'  : 'json',
            'q'     : zip,
            'client'  : '',
            'sensor'  : false
          },
          type: 'GET',
          success: function(data) {

            var myString = "";
            
            var status = data.Status;

            if (status.code == 200) {         
              for (var i = 0; i < data.Placemark.length; i++) {
                var placemark = data.Placemark[i];
                var status = data.Status[i];
                $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                if(placemark.AddressDetails.Country.AddressLine == "undefined")
                {
                    $("#building_name").val("");
                }
                else
                {
                    $("#building_name").val(placemark.AddressDetails.Country.AddressLine);
                }

              }
              $( '#form_postal_code' ).html('');
              $( '#form_street_name' ).html('');

            } else if (status.code == 603) {
                $( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');

            }

          },
          statusCode: {
            404: function() {
              alert('Page not found');
            }
          },
        });
    }
});

$('#previous_secretarial_postal_code').keyup(function(){
    if($(this).val().length == 6)
    {
        var zip = $(this).val();
        //var address = "068914";
        $.ajax({
          url:    'https://gothere.sg/maps/geo',
          dataType: 'jsonp',
          data:   {
            'output'  : 'json',
            'q'     : zip,
            'client'  : '',
            'sensor'  : false
          },
          type: 'GET',
          success: function(data) {

            var myString = "";
            
            var status = data.Status;

            if (status.code == 200) {         
              for (var i = 0; i < data.Placemark.length; i++) {
                var placemark = data.Placemark[i];
                var status = data.Status[i];
                $("#previous_secretarial_street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                if(placemark.AddressDetails.Country.AddressLine == "undefined")
                {
                    $("#previous_secretarial_building_name").val("");
                }
                else
                {
                    $("#previous_secretarial_building_name").val(placemark.AddressDetails.Country.AddressLine);
                }

              }
              $( '#form_previous_secretarial_postal_code' ).html('');
              $( '#form_previous_secretarial_street_name' ).html('');

            } else if (status.code == 603) {
                $( '#form_previous_secretarial_postal_code' ).html('<span class="help-block">*No Record Found</span>');

            }

          },
          statusCode: {
            404: function() {
              alert('Page not found');
            }
          },
        });
    }
});
</script>
