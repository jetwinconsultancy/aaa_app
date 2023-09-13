<div id="w2-change_reg_ofis_form" class="panel">
	<h3>Change of Registration Office Address</h3>
	<form id="change_of_reg_ofis_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control hidden_old_registration_address" id="hidden_old_registration_address" name="old_registration_address" value=""/>
		<input type="hidden" class="form-control transaction_change_regis_ofis_address_id" id="transaction_change_regis_ofis_address_id" name="transaction_change_regis_ofis_address_id" value=""/>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Current Registration Office Address</span></button>
		<div id="company_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-2">Registration No: </label>
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
			<div class="form-group">
				<label class="col-sm-2" for="w2-username">Registered Office Address: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="old_registration_address"></label>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">New Registration Office Address</span></button>
		<div id="company_info" class="incorp_content">
			<div id="change_of_reg_ofis_interface">
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-2" for="w2-username">Use Our Registered Address: </label>
					<div class="col-xs-8">
						<div class="col-xs-1">
							<input type="checkbox" class="" id="use_registered_address" name="use_registered_address" onclick="fillRegisteredAddressInput(this);">
						</div>
						<div class="col-xs-4 service_reg_off_area" style="display: none;">
							<select id="trans_service_reg_off" class="form-control service_reg_off" style="text-align:right;" name="service_reg_off">
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
				<div class="form-group">
					<label class="col-sm-2" for="w2-DS1">Effective Date:</label>
					<div class="col-sm-8">
						<div class="input-group" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid tab_2_effective_date" id="date_todolist" name="effective_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Service Engagement</span></button>
	<div id="company_info" class="incorp_content">
		<form id="billing_form" style="margin-top: 20px;">
		<!-- <div id="billing_form" style="margin-top: 20px;"> -->
			<div class="hidden"><input type="text" class="form-control" name="company_code" id="company_code" value="<?= isset($transaction_company_code) ? $transaction_company_code : ''?>"/></div>
			<table class="table table-bordered table-striped table-condensed" id="billing_table"style="margin-top: 20px;width: 1000px">
				<thead>
					<tr> 
						<th valign=middle style="width:210px;text-align: center">Service</th>
						<th valign=middle style="width:200px;text-align: center">Invoice Description</th>
						<th style="width:200px;text-align: center">Fee</th>
						<!-- <th style="width:180px;text-align: center">Amount</th> -->
						<th style="width:180px;text-align: center">Unit Pricing</th>
						<th style="width:180px;text-align: center">Servicing Firm</th>
						<th style="width:80px;"><a href="javascript: void(0);" style="color: #D9A200;width:230px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add</span></a></th>
					</tr>
				</thead>
				<tbody id="body_billing_info">
				</tbody>
			</table>
		</form>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="button" class="btn btn-primary submitChangeRegOfisInfo" id="submitChangeRegOfisInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script type="text/javascript">
	$('#change_of_reg_ofis_interface #postal_code').keyup(function(){
	    if($(this).val().length == 6)
	    {
	        var zip = $(this).val();
	        //var address = "068914";
	        $('#loadingmessage').show();
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
	            $('#loadingmessage').hide();
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