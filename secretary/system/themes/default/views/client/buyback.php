<section class="panel">
	<div class="panel-body">
		<div id="modal_buyback" class="">
			<section class="panel" id="wBuyback">
				<div class="panel-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="wizard-steps">
							<li class="active">
								<a href="#buyback_number_shares" data-toggle="tab"><span>1</span>Shares</a>
							</li>
							<li>
								<a href="#buyback_member" data-toggle="tab" id="calculate_buyback"><span>2</span>Member</a>
							</li>
							<li>
								<a href="#allotment_confirm" data-toggle="tab"><span>3</span>Confirmation</a>
							</li>
						</ul>
					</div>
	
					
					<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'myForm');
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
					?>
						<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/><input type="text" class="form-control" name="id" value="<?=$id?>"/></div>
						<div class="tab-content">
							<div id="buyback_number_shares" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-5 control-label">Date</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" data-date-format="dd/mm/yyyy" data-plugin-datepicker required name="tgl" value="<?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="allotment_sharetype">Share Type</label>
									<div class="col-sm-3">
										<?php
											// echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control input-sm  input-sm select" style="width:100%;" ');
											echo form_dropdown('sharetype_buyback', $bl, '', 'id="sharetype_buyback"  class="form-control" style="width:100%;" ');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="allotment_sharetype">Currency</label>
									<div class="col-sm-3">
										<?php
										echo form_dropdown('currency', $cr, '', 'id="currency_buyback"  class="form-control" style="width:100%;" required');
									
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="Allotment_Share">Buyback Share (%)</label>
									<div class="col-sm-3">
										<input type="number" class="form-control text-right" max-value="15" name="Allotment_Share" id="buyback_share" value="15"  required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="Allotment_Share_amount">Share Amount</label>
									<div class="col-sm-3">
										<input type="text" class="form-control text-right" name="Allotment_Share_amount" id="Allotment_Share_amount"  value="10,000.00" required>
									</div>
								</div>
							</div>
							<div id="buyback_member" class="tab-pane">
								<h1>Total Share Buyback : <span id="t_s_bb"> </span></h1>
								<table  class="table table-bordered table-striped table-condensed mb-none">
									<tr>
										<th>No.</th>
										<th>Name</th>
										<th>ID</th>
										<th>Share</th>
										<th>Amount</th>
										<th>Share Buyback</th>
										<th>Amount Paid</th>
										<th>Certificate No.</th>
									</tr>
									<tbody id="tbody_buyback">
									
									</tbody>
									<tr>
										<td colspan=3>Total</td>
										<td id="total_Share"></td>
										<td id="total_amount"></td>
										<td id="total_shareBB"></td>
										<td id="total_amountBB"></td>
										<td id=""></td>
									</tr>
								</table>
							</div>
							<div id="allotment_confirm" class="tab-pane">
								<table class="table table-bordered">
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
								</div>
							</div>
						</div>
					</form>
							
							

				</div>
				<div class="">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fa fa-angle-left"></i> Previous</a>
						</li>
						<li class="finish  modal-dismiss hidden pull-right">
							<a id="save_form"d>Save</a>
						</li>
						<li class="next">
							<a>Next <i class="fa fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
		</div>
					
	</div>
	
<!-- end: page -->
</section>

<script>
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
	$("#buyback_share").on('change',function(){
		if ($(this).val() > 15)
		{
			$(this).val(15);
			alert("Buyback cant Bigger than 15%");
		}
	});
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
		console.log($seharusnya);
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