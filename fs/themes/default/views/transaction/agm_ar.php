<div id="w2-agm_ar_form" class="panel">
	<h3>AGM & AR</h3>
	<form id="agm_ar_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control transaction_agm_ar_id" id="transaction_agm_ar_id" name="transaction_agm_ar_id" value=""/>
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
			<label class="col-sm-2" for="w2-username">First AGM/ Annual Return: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="first_agm" id="first_agm">
							<option value="0">Select First AGM</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Year End: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<div class="input-group mb-md" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid fye_date" id="date_todolist" name="fye_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">AGM: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 200px;float:left;margin-right: 20px;">
						<div class="input-group mb-md agm_date_input" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid agm_date_info" id="date_todolist agm_date" name="agm_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
						</div>
						
					</div>
					<div class="input-bar-item input-bar-item-btn">
				      <button type="button" class="btn btn-primary dispense_agm_button" onclick="dispense_agm(this)" >Dispense AGM</button>
				    </div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Resolution Date: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<div class="input-group mb-md reso_date_input" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid reso_date" id="date_todolist" name="reso_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<h4>Agenda</h4>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Activity Status: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="activity_status" id="activity_status">
							<option value="0">Select Activity Status</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Solvency Status: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="solvency_status" id="solvency_status">
							<option value="0">Select Solvency Status</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">EPC Status: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control epc_status" style="text-align:right;width: 200px;" name="epc_status" id="epc_status">
							<option value="0">Select EPC Status</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Small Company: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control small_company" style="text-align:right;width: 200px;" name="small_company" id="small_company">
							<option value="0">Select Small Company</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Financial Statements Audited: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="audited_fs" id="audited_fs">
							<option value="0">Select Financial Statements Audited</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Share Transfer: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="share_transfer" id="agm_share_transfer">
							<option value="0">Select Share Transfer</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Consent For Shorter Notice: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="shorter_notice" id="shorter_notice">
							<option value="0">Select Consent For Shorter Notice</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Chairman: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="chairman" id="transaction_agm_chairman">
							<option value="0">Select Chairman</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div id="bottom_interface">
			<div class="form-group">
				<label class="col-sm-5" for="w2-username">
					<input type="checkbox" class="director_fee" id="director_fee" name="director_fee" onclick="directorFee(this);">  Director Fee
				</label>
				
			</div>
			<div class="form-group director_fee_div" style="display: none;">
				<div class="col-sm-8">
					<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
						<thead>
							<tr>
								<th style="width:250px; text-align: center">Director Name</th>
								<th style="width:150px; text-align: center">Fee</th>
							</tr>
						</thead>
						<tbody id="director_fee_add">
												
						</tbody>
					</table>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5" for="w2-username">
					<input type="checkbox" class="dividend" id="dividend" name="dividend" onclick="dividendCheckBox(this);">  Dividend
				</label>
			</div>
			<div class="form-group dividend_div" style="display: none;">
				<div class="col-sm-8">
					<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
						<thead>
							<tr>
								<th style="text-align:center;">
									Total Dividend Declared
								</th>
								<th>
									<input type="text" style="text-transform:uppercase;" name="total_dividend" id="total_dividend" class="form-control numberdes" value=""/>
								</th>
							</tr>
							<tr>
								<th style="width:250px; text-align: center">Member Name</th>
								<th style="width:150px; text-align: center">Dividend</th>
							</tr>
						</thead>
						<tbody id="dividend_add">
												
						</tbody>
						
					</table>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5" for="w2-username">
					<input type="checkbox" class="amount_due_from_director" id="amount_due_from_director" name="amount_due_from_director" onclick="amountDueFromDirector(this);">  Amount Due From Director
				</label>
			</div>
			<div class="form-group amount_due_from_director_div" style="display: none;">
				<div class="col-sm-8">
					<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
						<thead>
							<tr>
								<th style="width:250px; text-align: center">Director Name</th>
								<th style="width:150px; text-align: center">Amount</th>
							</tr>
						</thead>
						<tbody id="amount_due_from_director_add">
												
						</tbody>
					</table>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5" for="w2-username">
					<input type="checkbox" class="director_retirement" id="director_retirement" name="director_retirement" onclick="directorRetirement(this);">  Director Retirement
				</label>
			</div>
			<div class="form-group director_retirement_div" style="display: none;">
				<div class="col-sm-8">
					<table class="table table-bordered table-striped table-condensed mb-none" style="width: 700px">
						<thead>
							<tr>
								<th style="width:50px; text-align: center">No</th>
								<th style="width:250px; text-align: center">NRIC</th>
								<th style="width:250px; text-align: center">Director Name</th>
								<th style="width:150px; text-align: center">Retiring</th>
							</tr>
						</thead>
						<tbody id="director_retirement_add">
												
						</tbody>
					</table>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5" for="w2-username">
					<input type="checkbox" class="reappointment_auditor" id="reappointment_auditor" name="reappointment_auditor" onclick="reappointmentAuditor(this);">  Reappointment of Auditor
				</label>
			</div>

			<div class="form-group reappointment_auditor_div" style="display: none;">
				<div class="col-sm-8">
					<table class="table table-bordered table-striped table-condensed mb-none" style="width: 250px">
						<thead>
							<tr>
								<th style="width:250px; text-align: center">Auditor Name</th>
								
							</tr>
						</thead>
						<tbody id="reappointment_auditor_add">
												
						</tbody>
					</table>
				</div>
			</div>

		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitAgmArInfo" id="submitAgmArInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	$("#total_dividend").change(function(){
		var total_dividend = $(this).val();
		$('.row_dividend').each(function(){
			var dividend = 0;
			// console.log($(this).attr('data-numberOfShare'));
			 console.log(total_dividend);
			// console.log(total_number_of_share);
			dividend = (parseInt($(this).attr('data-numberOfShare')) * parseInt(removeCommas(total_dividend))) / parseInt(total_number_of_share);
			//* parseInt($(this).val())
			// console.log(dividend);
			// console.log(total_number_of_share);
			$(this).find("#dividend_fee").val(addCommas(dividend.toFixed(2)));
		});
	});

	

	$(".small_company").change(function(){
            console.log($(this).val());
            if($(this).val() == 2) //No
            {
                $("#audited_fs").val("1");
                $("#audited_fs").prop("disabled", true);
                $('#reappointment_auditor').prop('checked', true);
                $("#loadingmessage").show();
                $(".row_reappointment_auditor").remove();
				$.post("transaction/get_resign_auditor_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
			        $("#loadingmessage").hide();
			        console.log(JSON.parse(data));
			        array_for_auditor_info = JSON.parse(data);

			        for(var i = 0; i < array_for_auditor_info.length; i++)
				    {
				        $a="";
			            $a += '<tr class="row_reappointment_auditor">';
			            $a += '<td><input type="text" style="text-transform:uppercase;" name="reappointment_auditor_name[]" id="name" class="form-control" value="'+ (array_for_auditor_info[i]["company_name"]!=null ? array_for_auditor_info[i]["company_name"] : array_for_auditor_info[i]["name"]) +'" readonly/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="hidden_resign_identification_register_no" value="'+ (array_for_auditor_info[i]["identification_no"]!=null ? array_for_auditor_info[i]["identification_no"] : array_for_auditor_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value="'+array_for_auditor_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value="'+array_for_auditor_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value="'+array_for_auditor_info[i]["field_type"]+'"/></div></td>';
			            $a += '</tr>';

			            $("#reappointment_auditor_add").append($a);
			        }
			    });
				$(".reappointment_auditor_div").show();
            }
            else if($(this).val() == 1)
            {
            	$("#audited_fs").prop("disabled", false);
            }
        });

	$("#audited_fs").change(function(){
		if($(this).val() == 2) //No
        {
        	$('#reappointment_auditor').prop('checked', false);
        	$(".reappointment_auditor_div").hide();
			$(".row_reappointment_auditor").remove();
        }
	});
</script>