<div id="w2-purchase_common_seal_form" class="panel">
	<form id="purchase_common_seal_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control transaction_purchase_common_seal_page_id" id="transaction_purchase_common_seal_page_id" name="transaction_purchase_common_seal_page_id" value=""/>

		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Purchase of Common Seal & Self inking stamp</span></button>
		<div id="company_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px;padding-bottom: 18px;">
				<label class="col-xs-2" for="w2-DS1">Date:</label>
				<div class="col-sm-8">
					<div class="input-group" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid tab_2_purchase_date" id="date_todolist" name="purchase_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
			</div>
			<div id="change_of_company_name_interface">
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-2" for="w2-vendor">Vendor: </label>
					<div class="col-xs-8">
						<select class="form-control common_seal_vendor" style="text-align:right;width: 300px;" name="common_seal_vendor" id="common_seal_vendor">
							<option value="0">Select Vendor</option>
						</select>
					</div>
				</div>
			</div>
			<div style="margin-top: 20px;padding-bottom: 18px;">
				<table class="table table-bordered table-striped table-condensed mb-none" id="purchase_common_seal_table" style="width: 1000px" style="margin-top: 20px;">
					<thead>
						<tr>
							<th id="id_position" style="text-align: center;">Company Name</th>
							<th id="id_header" style="text-align: center;width:270px">UEN</th>
							<th style="text-align: center;width:270px" id="id_name">Order for</th>
							<th style="width: 130px;"><a href="javascript: void(0);" owspan=2 style="color: #D9A200; outline: none !important;text-decoration: none;"><span id="purchase_common_seal_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Add Order" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Order</span></a></th>
						</tr>
					</thead>
					<tbody id="body_purchase_common_seal">
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitPurchaseCommonSealInfo" id="submitPurchaseCommonSealInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
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