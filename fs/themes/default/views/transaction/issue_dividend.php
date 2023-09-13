<div id="w2-issue_dividend_form" class="panel">
	<h3>Issue Dividend</h3>
	<form id="issue_dividend_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control transaction_issue_dividend_id" id="transaction_issue_dividend_id" name="transaction_issue_dividend_id" value=""/>
		<input type="hidden" class="form-control devidend_per_share" id="devidend_per_share" name="devidend_per_share" value=""/>
		<div class="form-group">
			<label class="col-sm-2" for="w2-currency">Currency: </label>
			<div class="col-sm-8">
				<select class="form-control currency" style="width: 200px;" name="currency" id="currency""><option value="0">Select Currency</option></select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-total_dividend_amount">Total Dividend Amount: </label>
			<div class="col-sm-8">
				<input class="form-control numberdes total_dividend_amount" style="text-align: right; width: 200px;" name="total_dividend_amount" id="total_dividend_amount" pattern="^[0-9,]+$" onkeypress="return validateFloatKeyPress(this,event);">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-declare_of_fye">Declare of FYE: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid declare_of_fye" id="date_todolist" name="declare_of_fye" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-devidend_of_cut_off_date">Dividend of Cut Off Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid devidend_of_cut_off_date" id="date_todolist" name="devidend_of_cut_off_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-devidend_payment_date">Dividend Payment Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid devidend_payment_date" id="date_todolist" name="devidend_payment_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-nature">Nature: </label>
			<div class="col-sm-8">
				<select class="form-control nature" style="width: 200px;" name="nature" id="nature""><option value="0">Select Nature</option></select>
			</div>
		</div>
		<h4>Member</h4>
		<table class="table table-bordered table-striped table-condensed mb-none" id="latest_director_table" style="width: 750px">
			<thead>
				<tr>
					<!-- <th id="id_position" style="text-align: center;width:170px">Position</th> -->
					<!-- <th id="id_header" style="text-align: center;width:270px">ID</th> -->
					<th style="text-align: center;width:270px" id="id_name">Shareholder Name</th>
					<th style="text-align: center;width:270px">Number of Share</th>
					<th style="text-align: center;width:270px">Dividend Paid</div>
					<!-- <th style="text-align: center;width:270px">Fee</div> -->
				</tr>
				
			</thead>
			<tbody id="body_issue_dividend">
			</tbody>
			
		</table>

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitIssueDividendInfo" id="submitIssueDividendInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript">
	function validateFloatKeyPress(el, evt) {
	    var charCode = (evt.which) ? evt.which : event.keyCode;
	    var number = el.value.split('.');
	    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    //just one dot
	    if(number.length>1 && charCode == 46){
	         return false;
	    }
	    //get the carat position
	    var caratPos = getSelectionStart(el);
	    var dotPos = el.value.indexOf(".");
	    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
	        return false;
	    }
	    return true;
	}

	//thanks: http://javascript.nwbox.com/cursor_position/
	function getSelectionStart(o) {
		if (o.createTextRange) {
			var r = document.selection.createRange().duplicate()
			r.moveEnd('character', o.value.length)
			if (r.text == '') return o.value.length
			return o.value.lastIndexOf(r.text)
		} else return o.selectionStart
	}
</script>