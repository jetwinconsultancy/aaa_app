<div class="header_between_all_section">
<section class="panel">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div id="modal_buyback" class="">
			<section class="panel" id="wBuyback">
				<?php $attrib = array('class' => 'form-horizontal transfer_form', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'buyback_form');
								echo form_open_multipart("masterclient/save_buyback", $attrib);
							?>	
				<div class="panel-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="wizard-steps" id="buyback_tab">
							<li class="active">
								<a href="#buyback_number_shares" data-toggle="tab"><span>1</span>Number Shares</a>
							</li>
							<li>
								<a href="#buyback_member" data-toggle="tab"><span>2</span>Member</a>
							</li>
							<li>
								<a href="#buyback_confirm" data-toggle="tab"><span>3</span>Certificate</a>
							</li>
						</ul>
					</div>
	
					
					<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'myForm');
						echo form_open_multipart("masterclient/save_buyback", $attrib);
									$cr[""] = [];
									$cr[0] = '--Select--';
									foreach ($currency as $cs) {
										$cr[$cs->id] = $cs->currency;
									}
									$bl[""] = [];
									
									foreach ($sharetype as $share) {
										$bl[$share->id] = $share->sharetype;
									}
					?> -->
						<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
						<div class="hidden"><input type="text" class="form-control" name="transaction_type" value="Buyback"/></div>
						<div class="tab-content">
							<div id="buyback_number_shares" class="tab-pane active">
								<div class="hidden"><input type="text" class="form-control" name="client_member_share_capital_id" id="client_member_share_capital_id" value="<?=$buyback[0]->client_member_share_capital_id?>"/></div>
								<div class="form-group">
									<label class="col-sm-5 control-label">Transaction Date</label>
									<div class="col-sm-3">
										<div class="input-group" id="buyback_datepicker" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control" name="date" id="transaction_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="<?php echo $buyback[0]->transaction_date; ?>">
										</div>
										<!-- <input type="text" class="form-control" data-date-format="dd/mm/yyyy" data-plugin-datepicker required name="tgl" value="<?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?>"> -->
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="allotment_sharetype">Class</label>
									<div class="col-sm-3">			
										<select class="form-control" style="text-align:right;width: 100%;" name="buyback_class" id="buyback_class">
											<option value="0">Select Class</option>
										</select>
									</div>
									<!-- <div class="col-sm-3">
										<?php
											// echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control input-sm  input-sm select" style="width:100%;" ');
											echo form_dropdown('sharetype_buyback', $bl, '', 'id="sharetype_buyback"  class="form-control" style="width:100%;" ');
										?>
									</div> -->
								</div>
								<!-- <div class="form-group" id="buyback_other_class" hidden>
									<label class="col-sm-5 control-label" for="buyback_others">Others</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" name="buyback_others" id="buyback_others" value="<?=$buyback[0]->other_class?>" readonly/>
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-5 control-label" for="allotment_sharetype">Currency</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" name="buyback_currency" id="buyback_currency" value="<?=$buyback[0]->currency?>" readonly/>
									</div>
									<!-- <div class="col-sm-3">
										<?php
										echo form_dropdown('currency', $cr, '', 'id="currency_buyback"  class="form-control" style="width:100%;" required');
									
										?>
									</div> -->
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="buyback_Share">Buyback Share (%)</label>
									<div class="col-sm-3">
										<input type="text" class="form-control text-right buyback_share" name="buyback_share" id="buyback_share" value=""  pattern="[0-9.]">
										<!-- <input type="number" class="form-control text-right" max-value="15" name="buyback_Share" id="buyback_share" value="15"  required> -->
									</div>
									<div class="col-sm-3">
										<!-- <button name="redistribute" class="btn btn-primary" tabindex="-1">Redistribute</button> -->

										<a href="javascript: void(0);" class="btn btn-primary redistribute" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to redistribute number of share to be bought back based on this percentage." onclick="redistribute()">Redistribute</a>
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="col-sm-5 control-label" for="Allotment_Share_amount">Share Amount</label>
									<div class="col-sm-3">
										<input type="text" class="form-control text-right" name="Allotment_Share_amount" id="Allotment_Share_amount"  value="10,000.00" required>
									</div>
								</div> -->
							</div>
							<div id="buyback_member" class="tab-pane" align="center">
								<h3 align="left">Total Share Buyback : <span id="t_s_bb"> </span></h3>
								<table  class="table table-bordered table-striped table-condensed mb-none">
									<tr>
										<th>No.</th>
										<th>Members</th>
										<th>Current Number of Shares</th>
										<th>Share Buyback</th>
										<th>New Number of Shares</th>
										<!-- <th>Certificate No.</th> -->
									</tr>
									<tbody id="tbody_buyback">
									
									</tbody>
									<tr>
										<td colspan=2>Total</td>
										<td id="total_number_of_share" style="text-align:right;"></td>
										<td id="total_share_buyback" style="text-align:right;"></td>
										<td id="total_new_number_of_share" style="text-align:right;"></td>
										<!-- <td id=""></td> -->
									</tr>
								</table>
							</div>

							<div id="buyback_confirm" class="tab-pane" align="center">
								<table style="border:1px solid black" class="transfer_table" id="confirm_buyback_add">
												
									<tr> 
										<th rowspan="2" style="text-align: center">No</th> 
										<th rowspan="2" style="text-align: center">Members</th> 
										<th colspan="3" style="text-align: center">Number of Shares</th> 
										<th rowspan="2" style="text-align: center">Certificate No.</th> 
									</tr> 
									<tr> 
										<th style="text-align: center">Current Number of Shares</th> 
										<th style="text-align: center">Share Buyback</th> 
										<th style="text-align: center">New Number of Shares</th> 
										
									</tr> 
									
									<!-- <div class="tbody" id="confirm_transfer_add_row">
										
									</div> -->
								</table>
								<!-- <table class="table table-bordered">
									<tr>
										<td>No</td>
										<td>Name</td>
										<td>ID</td>
										<td>Share</td>
										<td>Amount</td>
										<td>Share Buyback</td>
										<td>Amount Buyback</td>
										<td>Total Share</td>
										<td>Total Amount</td>
										<td>Ceritificate</td>
									</tr>
									<tbody id="buyback_confirm">
									</tbody>
								</table>
								
								<div class="form-group" style="padding-left:25px;">
									<div class="form-group">
										<label>Certificate:</label>
									</div>
									<div class="form-group">
										<label><input type="radio" name="certificate" class="unmanual" checked>&nbsp;&nbsp;&nbsp;Cancel all existing and replace with new certificate</label>
									</div>
									<div class="form-group">
										<label><input type="radio" name="certificate" class="unmanual">&nbsp;&nbsp;&nbsp;New certificate number for shares alloted</label>
									</div>
									<div class="form-group">
										<label><input type="radio" name="certificate"  id="manual">&nbsp;&nbsp;&nbsp;Manual Changes</label>
										<table class="table table-striped" id="A" style="display:none;width:70%" >
											<tr>
												<td>No</td>
												<td>Date</td>
												<td>Members</td>
												<td>Share</td>
												<td>Certificate</td>
											</tr>
											<tbody id="body_manual_certificate">
											</tbody>
										</table>
									</div>
								</div> -->
							</div>
						</div>
							
							

				</div>
				<div class="panel-footer">
					<!-- Previous/Next buttons -->
			        <ul class="pager wizard">
			           <!--  <li class="previous hidden"><a href="javascript: void(0);">Previous</a></li>
			            <li class="next" style="float: right"><a href="javascript: void(0);">Next</a></li>
			            <li class="cancel_buyback" style="float: right; margin-right: 10px;"><a href="javascript: void(0);" id="cancel_buyback">Cancel</a></li> -->

			            <li class="previous hidden"><a href="javascript: void(0);">Go To Previous Page</a></li>
			            <li class="next" style="float: right"><a href="javascript: void(0);">Go To Next Page</a></li>

			            <li class="other_next" style="float: right"><a href="<?= base_url();?>masterclient/view_buyback/<?=$company_code?>">Done</a></li>

			            <li class="cancel_buyback" style="float: right; margin-right: 10px;"><a href="<?= base_url();?>masterclient/view_buyback/<?=$company_code?>" id="cancel_buyback">Cancel Buyback</a></li>
			        </ul>
					<!-- <ul class="pager">
						<li class="previous disabled">
							<a><i class="fa fa-angle-left"></i> Previous</a>
						</li>
						<li class="finish  modal-dismiss hidden pull-right">
							<a id="save_form"d>Save</a>
						</li>
						<li class="next">
							<a>Next <i class="fa fa-angle-right"></i></a>
						</li>
					</ul> -->
				</div>
				<?= form_close(); ?>
				<div class="loading" id='loadingBuyback'>Loading&#8230;</div>
			</section>
		</div>
					
	</div>
	
<!-- end: page -->
</section>
</div>
<script>
	var company_class = <?php echo json_encode($company_class);?>;
	var buyback = <?php echo json_encode($buyback);?>;
	var company_code = "<?php echo ($company_code);?>";
	var access_right_member_module = <?php echo json_encode($member_module);?>;
	var client_status = <?php echo json_encode($client_status);?>;
	var first_time_calculation = false;
	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	if(access_right_member_module == "read" || client_status != "1")
	{
		$("select#buyback_class").attr("disabled", true);
		$("#transaction_date").attr("disabled", true);
		$("#buyback_share").attr("disabled", true);
	}

	function redistribute()
	{
		first_time_calculation = true;

		toastr.success("Information Updated", "Updated");
	}

	$(document).on("change",".buyback_share",function(){
		//console.log(parseFloat($(this).val()).toFixed(2));
	    $(this).val(parseFloat($(this).val()).toFixed(2));
	});

</script>
<script src="themes/default/assets/js/buyback.js?v=30eee4fc8d1b59e4584b0d39edfa2082"></script>
<script>
	$(document).ready(function() {
	    $('#loadingBuyback').hide();
	});
	$(document).on('click','#manual',function(){
		$("#A").show();
	});
	$(document).on('click','.unmanual',function(){
		$("#A").hide();
	});
	$(document).on('blur','#Allotment_Share',function(){
		if ($(this).val() >100)
		{
			$(this).val(100);
		} else if ($(this).val() <0)
		{
			$(this).val(0);
		}
	});
	/*$("#buyback_share").on('change',function(){
		if ($(this).val() > 15)
		{
			$(this).val(15);
			alert("Buyback cant Bigger than 15%");
		}
	});*/
	$("#currency_buyback").on('change',function(){
		// alert("A");
		$("#tbody_buyback").load("masterclient/read_buyback/<?=$unique_code?>/"+$("#sharetype_buyback option:selected").val()+"/"+$(this).val());
	});
	$("#currency_buyback").on('change',function(){
		// alert("A");
		$("#tbody_buyback").load("masterclient/read_buyback/<?=$unique_code?>/"+$("#sharetype_buyback option:selected").val()+"/"+$(this).val());
		$("#buyback_confirm").load("masterclient/read_buybackplain/<?=$unique_code?>/"+$("#sharetype_buyback option:selected").val()+"/"+$(this).val());
	});
	$(document).on("change",".share_bb",function(){
		$("#share_bb"+$(this).data('id')).html($(this).val());
		// $selisih = parseFloat($(this).data('shareori'))-parseFloat($(this).val());
		$seharusnya = (parseFloat($(this).data('shareori'))*15/100);
		//console.log($seharusnya);
		if (parseFloat($(this).val().replace(',','')) > $seharusnya)
		{
			alert("Over 15% from share, maximum share can be buyback is "+$seharusnya);
			$(this).val($seharusnya);
		}
			$selisih = parseFloat($(this).data('shareori'))-parseFloat($(this).val());
		// if ($selisih/parseFloat($(this).data('shareori'))
		$("#total_share_left"+$(this).data('id')).html($selisih);
	});
	$(document).on("change",".amount_bb",function(){
		$("#amount_bb"+$(this).data('id')).html($(this).val());
		$selisih =parseFloat($(this).data('amountori'))-parseFloat($(this).val());
		// console.log($selisih);
		if (parseFloat($(this).val()) < 0)
		{
			alert("Amount Minus Please Check Again");
		}
		$("#total_amount_left"+$(this).data('id')).html($selisih);
	});
	$(document).on("change",".certificate_bb",function(){
		$("#certificate"+$(this).data('id')).html($(this).val());
	});
	$("#save_form").on('click',function(){
		$("#myForm").submit();
	});
	
	$("#manual").on('click',function(){
		$("#body_manual_certificate").load("masterclient/get_certificate/<?=$unique_code?>");
	});
</script>