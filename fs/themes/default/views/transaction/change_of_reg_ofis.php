<div id="w2-change_reg_ofis_form" class="panel">
	<h3>Change of Registration Officer</h3>
	<form id="change_of_reg_ofis_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control hidden_old_registration_address" id="hidden_old_registration_address" name="old_registration_address" value=""/>
		<input type="hidden" class="form-control transaction_change_regis_ofis_address_id" id="transaction_change_regis_ofis_address_id" name="transaction_change_regis_ofis_address_id" value=""/>
		<h4>Current Registration Office Address</h4>
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
		<h4>New Registration Office Address</h4>
		<div id="change_of_reg_ofis_interface">
			<div class="form-group" style="margin-top: 20px">
				<label class="col-xs-2" for="w2-username">Use Our Registered Address: </label>
				<div class="col-xs-8">
					<div class="col-xs-1">
						<input type="checkbox" class="" id="use_registered_address" name="use_registered_address" onclick="fillRegisteredAddressInput(this);">
					</div>
					<div class="col-xs-4 service_reg_off_area" style="display: none;">
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
			<!-- <div class="form-group" style="margin-top: 20px">
				<label class="col-xs-2" for="w2-username">Effective Date: </label>
				<div class="col-xs-8">
					<input type="text" class="form-control valid" id="date_todolist" name="effective_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY" style="width: 200px;">
				</div>
			</div> -->
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitChangeRegOfisInfo" id="submitChangeRegOfisInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script type="text/javascript">
	$('#change_of_reg_ofis_interface #postal_code').keyup(function(){
    console.log($(this).val());
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
</script>