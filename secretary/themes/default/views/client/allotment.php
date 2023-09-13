<div class="header_between_all_section">
<section class="panel">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="modal_allotment" class="">
				<section id="wAllotment">
				<?php $attrib = array('class' => 'form-horizontal allotment_form', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'allotment_form');
								echo form_open_multipart("masterclient/save_allotment", $attrib);
											/*$cr[""] = [];
											foreach ($currency as $cs) {
												$cr[$cs->id] = $cs->currency;
											}
											$bl[""] = [];
											foreach ($sharetype as $share) {
												$bl[$share->id] = $share->sharetype;
											}*/
							?>	
				<div class="panel-body">
					
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="wizard-steps" id="allotment_tab">
							
							<li class="active">
								<a href="#alloment_number_shares" data-toggle="tab"><span>1</span>Number Shares</a>
							</li>
							<li>
								<a href="#allotment_member" data-toggle="tab"><span>2</span>Members</a>
							</li>

							<li>
								<a href="#allotment_confirm" data-toggle="tab"><span>3</span>Certificate</a>
							</li>
						</ul>
					</div>
					
						<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
						<div class="hidden"><input type="text" class="form-control" name="transaction_type" value="Allotment"/></div>
						<div class="tab-content">
							<div id="alloment_number_shares" class="tab-pane active">
								<div class="hidden"><input type="text" class="form-control" name="client_member_share_capital_id" id="client_member_share_capital_id" value="<?=$allotment[0]->client_member_share_capital_id?>"/></div>
								<!-- <div class="hidden"><input type="text" class="form-control" name="previous_new_cert" id="previous_new_cert" value="<?=$allotment[0]->new_certificate_no?>"/></div>
								<div class="hidden"><input type="text" class="form-control" name="previous_cert" id="previous_cert" value="<?=$allotment[0]->certificate_no?>"/></div> -->
								<div class="form-group">
									<label class="col-sm-5 control-label">Transaction Date</label>
									<!-- <div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_registration" name="date_registration[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div> -->
									<div class="col-sm-3">
										<div class="input-group" id="transaction_datepicker" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control" id="transaction_date" name="date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="<?php echo $allotment[0]->transaction_date; ?>">
											<!-- <?php if($allotment[0]->transaction_date != null){echo $allotment[0]->transaction_date;}else{$now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];}?> -->
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="allotment_class">Class</label>
									<div class="col-sm-3" name="share_type">
										<select class="form-control" style="text-align:right;width: 100%;" name="class" id="class">
											<option value="0">Select Class</option>
										</select>
										<div id="form_class"></div>
										<!-- <?php
											// echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control input-sm  input-sm select" style="width:100%;" ');
											echo form_dropdown('sharetype_allotment', $bl, '', 'id="slsales"  class="form-control" style="width:100%;" ');
										?> -->
									</div>
								</div>
								<!-- <div class="form-group" id="other_class" hidden>
									<label class="col-sm-5 control-label" for="allotment_others">Others</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" name="others" id="others" value="<?=$allotment[0]->other_class?>" readonly/>
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-5 control-label" for="allotment_currency">Currency</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" name="currency" id="currency" value="<?=$allotment[0]->currency?>" readonly/>
										<!-- <select class="form-control" style="text-align:right;width: 100%;" name="currency" id="currency" disabled="disabled">
											<option value="0" >Select Currency</option>
										</select>
										<div id="form_currency"></div> -->
										<!-- <?php
										echo form_dropdown('currency', $cr, '', 'id="currency"  class="form-control" style="width:100%;"');
									
										?> -->
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="col-sm-5 control-label" for="Allotment_Share">No of Share</label>
									<div class="col-sm-3">
										<input type="text" class="form-control number text-right" name="Allotment_Share" id="Allotment_Share" value="1,000" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label" for="Allotment_Share_amount">Amount</label>
									<div class="col-sm-3">
										<input type="text" class="form-control number text-right" name="Allotment_Share_amount" id="Allotment_Share_amount" value="1,000.00" required>
									</div>
								</div> -->
							</div>
							<div id="allotment_member" class="tab-pane" align="center">
								<!-- <div class="col-md-12">
									<div class="col-md-8">
										<select class="input-sm" style="float:left;" id="tipepencarian">
											<option value="gid">ID</option>
											<option value="nama">Name</option>
										</select>
										<div class="col-md-6 input-group" style="float:left;margin-left:5px;">
											<input type="text" class="form-control input-sm" id="katacari" name="katacari" placeholder="Search">
											<span class="input-group-btn">
												<a class="btn btn-primary" id="cari_orang" style="height:30px;"><i class="fa fa-search"></i></a>
											</span>
										</div>
									</div>
									<div class="col-md-6" style="margin-top:5px;">
										<table class="table table-bordered table-striped table-condensed mb-none" >
											<thead>
												<tr>
													<th>ID</th>
													<th>Name</th>
													<th width=20px></th>
												</tr>
											</thead>
											<tbody id="tbody_cari_orang">
												
											</tbody>
										</table>
										<br/>
									</div>
								</div> -->

								<table  class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<div class="tr">
											<!-- <div class="th" style="border-bottom:none;" valign=middle>No</div> -->
											<div class="th" valign=middle>ID</div>
											<div class="th">Class</div>
											<div class="th">Number of Shares Issued</div>
											<div class="th rowspan">Number of Shares Paid Up</div>
											<!-- <div class="th" style="border-bottom:none;">Certificate No.</div> -->
											<a href="javascript: void(0);" class="th" rowspan =2 style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="allotment_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Allotment" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Allotment</span></a>

											<!-- <div class="th" style="font-size:25px;border-bottom:none;width: 50px;"><span id="allotment_member_Add"><i class="fa fa-plus-circle"></i></span></div> -->
										</div>
										<div class="tr">
											<!-- <div class="th empty"></div> -->
											<div class="th">Name</div>
											<div class="th">Currency</div>
											<div class="th">Amount of Shares Issued</div>
											<div class="th">Amount of Shares Paid Up</div>
											<!-- <div class="th empty"></div> -->
											<div class="th empty" style="width: 0px !important;"></div>
										</div class="tr">
									</thead>
									<!-- <tr>
										<th>No.</th>
										<th>ID</th>
										<th>Name</th>
										<th>Share</th>
										<th>Amount</th>
										<th>Share Paid</th>
										<th>Amount Paid</th>
										<th>Certificate No.</th>
									</tr> -->
									<div class="tbody" id="allotment_add">
															

									</div>
									<!-- <tbody id="allotment_add"> -->
									<!--tr>
										<td>1</td>
										<td>
											<div>
												<input type="text" class="form-control" value="S8484841Z" required>
											</div>
										</td>
										<td>
											<div>
												<input type="text" class="form-control" value="Dart" required>
											</div>
										</td>
										<td>
											<div>
												<input type="text" class="form-control number text-right" value="100" required>
											</div>
										</td>
										<td>
											<div>
												<input type="text" class="form-control number text-right" value="1,000.00" required>
											</div>
										</td>
										<td>
											<div>
												<input type="text" class="form-control number text-right" value="100" required>
											</div>
										</td>
										<td>
											<div>
												<input type="text" class="form-control number text-right" value="1,000.00" required>
											</div>
										</td>
										<td>
											<div>
												<input type="text" class="form-control" value="CRT1199191" required>
											</div>
										</td>
										<td>
											<a href="#"><i class="fa fa-trash"></i></a>
										</td>
									</tr-->
									<!-- </tbody> -->
								</table>
							</div>
							
							<div id="allotment_confirm" class="tab-pane">
								<div style="margin-bottom: 20px;">
									<span class="help-block cert_remind_tag"></span>
								</div>
								<div class="form-group">
									<label class="col-sm-3 col-md-3">Transaction Date:</label>
									<div class="col-sm-3 col-md-3" id="confirm_date"></div>
									<label class="col-sm-3 col-md-3">Total Number of Shares Issued:</label>
									<div class="col-sm-3 col-md-3" id="confirm_total_number_of_shares"></div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 col-md-3">Class:</label>
									<div class="col-sm-3 col-md-3" id="confirm_class"></div>
									<label class="col-sm-3 col-md-3">Total Amount of Shares Issued:</label>
									<div class="col-sm-3 col-md-3" id="confirm_total_amount_shares"></div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 col-md-3">Currency:</label>
									<div class="col-sm-3 col-md-3" id="confirm_currency"></div>
									<label class="col-sm-3 col-md-3">Total Number of Shares Paid Up:</label>
									<div class="col-sm-3 col-md-3" id="confirm_total_no_of_share_paid"></div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 col-md-3"></label>
									<div class="col-sm-3 col-md-3"></div>
									<label class="col-sm-3 col-md-3">Total Amount of Shares Paid Up:</label>
									<div class="col-sm-3 col-md-3" id="confirm_total_amount_paid"></div>
								</div>
								<table style="border:1px solid black" class="allotment_table" id="confirm_allotment_add">
												
									<tr> 
										<th rowspan="2" style="width:50px !important;">No</th>
										<th>ID</th> 
										<th>Class</th> 
										<th>Number of Shares Issued</th> 
										<th>Number of Shares Paid Up</th> 
									</tr> 
									<tr> 
										<th>Name</th> 
										<th>Currency</th> 
										<th>Amount of Shares Issued</th> 
										<th>Amount of Shares Paid Up</th> 
									</tr> 
									
									<!-- <div class="tbody" id="confirm_transfer_add_row">
										
									</div> -->
								</table>
								<!-- <table  class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<div class="tr">
											<div class="th" valign=middle>ID</div>
											<div class="th">Class</div>
											<div class="th">Number of Shares</div>
											<div class="th rowspan">No of Share Paid</div>
											<div class="th" style="border-bottom:none;">Certificate No.</div>
										</div>
										<div class="tr">
											<div class="th">Name</div>
											<div class="th">Currency</div>
											<div class="th">Amount Share</div>
											<div class="th">Amount Paid</div>
											<div class="th empty"></div>
										</div class="tr">
									</thead>
									<div class="tbody" id="confirm_allotment_add">
															

									</div>
								</table>
 -->

								<!-- <div >
									<table class="table table-bordered table-condensed">
										<tr>
										<th>No.</th>
										<th>ID</th>
										<th>Name</th>
										<th>Share</th>
										<th>Amount</th>
										<th>Share Paid</th>
										<th>Amount Paid</th>
										<th>Certificate No.</th>
									</tr>
										<tbody id="tbody_allotment_confirm">
										</tbody>
									</table>
								</div>
								<div class="form-group" style="padding-left:25px;">
									<div class="form-group">
										<label>Certificate:</label>
									</div>
									<div class="form-group">
										<label><input type="radio" name="certificate" class="unmanual">&nbsp;&nbsp;&nbsp;Cancel all existing and replace with new certificate</label>
									</div>
									<div class="form-group">
										<label><input type="radio" name="certificate" class="unmanual">&nbsp;&nbsp;&nbsp;New certificate number for shares alloted</label>
									</div>
									<div class="form-group">
										<label><input type="radio" name="certificate"  id="manual">&nbsp;&nbsp;&nbsp;Manual Changes</label>
										<table class="table table-striped" id="A" style="display:none" >
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

				
					<!-- </form> -->
						
				</div>
				
				<div class="panel-footer">
					<!-- Previous/Next buttons -->
			        <ul class="pager wizard">
			            <li class="previous hidden"><a href="javascript: void(0);">Go To Previous Page</a></li>

			            <li class="next" style="float: right"><a href="javascript: void(0);">Go To Next Page</a></li>

			            <li class="other_next" style="float: right"><a href="<?= base_url();?>masterclient/view_allotment/<?=$company_code?>">Done</a></li>

			            <li class="cancel" style="float: right; margin-right: 10px;"><a href="<?= base_url();?>masterclient/view_allotment/<?=$company_code?>" id="cancel">Cancel Allotment</a></li>
			            
			        </ul>
					<!-- <ul class="pager">
						<li class="previous hidden">
							<a><i class="fa fa-angle-left"></i> Previous</a>
						</li>
						<li class="finish hidden pull-right">
							<a id="save_form">Save</a>
						</li>
						<li class="next">
							<a>Next <i class="fa fa-angle-right"></i></a>
						</li>
					</ul> -->
				</div>	
				<?= form_close(); ?>
				<div class="loading" id='loadingAllotment'>Loading&#8230;</div>

				</section>
			</div>
		</div>
	</div>
	
<!-- end: page -->
</section>
</div>
<script>
	/*var base_url = <?php  echo (base_url());?>;*/
	var company_class = <?php echo json_encode($company_class);?>;
	var allotment = <?php echo json_encode($allotment);?>;
	var company_code = "<?php echo ($company_code);?>";
	var access_right_member_module = <?php echo json_encode($member_module);?>;
	var client_status = <?php echo json_encode($client_status);?>;
/*	console.log(company_class);
	console.log(allotment);*/
</script>
<script src="themes/default/assets/js/allotment.js?v=30eee4fc8d1b59e4584b0d39edfa2082"></script>

<script>
	$(document).ready(function() {
	    $('#loadingAllotment').hide();
	});
	if(access_right_member_module == "read" || client_status != "1" )
	{
		$("select#class").attr("disabled", true);
		$("#transaction_date").attr("disabled", true);
		$(".id").attr("disabled", true);
		$(".number_of_share").attr("disabled", true);
		$(".amount_share").attr("disabled", true);
		$(".amount_paid").attr("disabled", true);
		$("#allotment_member_Add").hide();
	}
	$(document).on('click','#manual',function(){
		$("#A").show();
	});
	$(document).on('click','.unmanual',function(){
		$("#A").hide();
	});
	$("#cari_orang").on('click', function(){
		// console.log($("#tipepencarian option:selected").val());
		// console.log($("#katacari").val());
		
		$("#tbody_cari_orang").load("masterclient/search_member/" + $("#tipepencarian option:selected").val() +"/" + $("#katacari").val());
	});
	$("#katacari").on('keypress',function(event) {
		if (event.keyCode == 13)
		{
			$("#tbody_cari_orang").load("masterclient/search_member/" + $("#tipepencarian option:selected").val() +"/" + $("#katacari").val());
			
			return event.keyCode != 13;
		}
		
	});
	$byk_allotment = 1;
	$(document).on('click','.add_director',function(){
		$html = '<tr>';
		$html += '	<td class="no_allotment">'+$byk_allotment+'</td>';
		$html += '	<td>';
		$html += '			<input type="text" name="gid[]" class="form-control gid_allotment" data-id="'+$byk_allotment+'" value="'+$(this).data('gid')+'" required>';
		$html += '	</td>';
		$html += '	<td>';
		$html += '			<input type="text" name="nama[]" class="form-control nama_allotment" data-id="'+$byk_allotment+'" value="'+$(this).data('nama')+'" required>';
		$html += '	</td>';
		$html += '	<td>';
		$html += '			<input type="text" name="share_allotment[]" class="form-control number text-right share_allotment" data-id="'+$byk_allotment+'" value="" required>';
		$html += '	</td>';
		$html += '	<td>';
		$html += '			<input type="text" name="amount_allotment[]" class="form-control number text-right amount_allotment" data-id="'+$byk_allotment+'" value="" required>';
		$html += '	</td>';
		$html += '	<td>';
		$html += '			<input type="text"  name="sharepaid_allotment[]" class="form-control number text-right sharepaid_allotment" data-id="'+$byk_allotment+'" value="" required>';
		$html += '	</td>';
		$html += '	<td>';
		$html += '			<input type="text"  name="amountpaid_allotment[]" class="form-control number text-right amountpaid_allotment" data-id="'+$byk_allotment+'" value="" required>';
		$html += '	</td>';
		$html += '	<td>';
		$html += '			<input type="text" name ="certificate_allotment" class="form-control certificate_allotment" data-id="'+$byk_allotment+'" value="" required>';
		$html += '	</td>';
		$html += '	<td>';
		$html += '		<a class="delete_row pointer" data-element="no_allotment"><i class="fa fa-trash"></i></a>';
		$html += '	</td>';
		$html += '</tr>';
		$("#person_added").append($html);
		$html = '<tr>';
		$html += '	<td class="no_allotment">'+$byk_allotment+'</td>';
		$html += '	<td id="gid'+$byk_allotment+'">'+$(this).data('gid')+'</td>';
		$html += '	<td id="nama'+$byk_allotment+'">'+$(this).data('nama')+'</td>';
		$html += '	<td id="share'+$byk_allotment+'"></td>';
		$html += '	<td id="amount'+$byk_allotment+'"></td>';
		$html += '	<td id="sharepaid'+$byk_allotment+'"></td>';
		$html += '	<td id="amountpaid'+$byk_allotment+'"></td>';
		$html += '	<td id="certificate'+$byk_allotment+'"></td>';
		// $html += '	<td></td>';
		$html += '</tr>';
		$("#tbody_allotment_confirm").append($html);
		$byk_allotment++;
	});
	
	$(document).on("change",".gid_allotment",function(){
		$("#gid"+$(this).data('id')).html($(this).val());
	});
	$(document).on("change",".nama_allotment",function(){
		$("#nama"+$(this).data('id')).html($(this).val());
	});
	$(document).on("change",".share_allotment",function(){
		$("#share"+$(this).data('id')).html($(this).val());
	});
	$(document).on("change",".amount_allotment",function(){
		$("#amount"+$(this).data('id')).html($(this).val());
	});
	$(document).on("change",".sharepaid_allotment",function(){
		$("#sharepaid"+$(this).data('id')).html($(this).val());
	});
	$(document).on("change",".amountpaid_allotment",function(){
		$("#amountpaid"+$(this).data('id')).html($(this).val());
	});
	$(document).on("change",".certificate_allotment",function(){
		$("#certificate"+$(this).data('id')).html($(this).val());
	});
	$("#save_form").on('click',function(e){
		//console.log($("#allotment_form").submit());
		e.preventDefault();
		$("#allotment_form").submit();
	});
	$("#manual").on('click',function(){
		$("#body_manual_certificate").load("masterclient/get_certificate/<?=$unique_code?>");
	});
</script>