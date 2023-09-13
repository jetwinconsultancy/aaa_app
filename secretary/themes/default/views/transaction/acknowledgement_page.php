<div class="col-sm-6 col-sm-offset-3" style="margin-top: 30px;">
	<section class="panel">
		<div class="panel-body">
			<div class="col-md-12">
				<h5>Acknowledgement page for <?=isset($client_name) ? $client_name : ''?>:</h5>
				<input type="hidden" id="transaction_master_id_for_acknowledgement" name="transaction_master_id_for_acknowledgement" value="<?=isset($transaction_master_id) ? $transaction_master_id : ''?>">
				<!-- <input type="hidden" id="common_seal_and_stamp" name="common_seal_and_stamp" value=""> -->
				<table style="width:100%">
					<tr style="height: 50px">
						<td>1.</td>
						<td>The record is successfully created.</td>
						<td></td>
					</tr>
					<tr style="height: 50px">
						<td>2.</td>
						<td>Would you like to order the following from</td>
						<td><select id="common_seal_vendor" class="form-control common_seal_vendor" id="common_seal_vendor" style="width:100%;" name="common_seal_vendor">
		                </select></td>
					</tr>
					<tr>
						<td></td>
						<td>a.	Purchase a common seal</td>
						<td><input type="checkbox" id="common_seal" name="common_seal_and_stamp" value="Common Seal"></td>
					</tr>
					<tr>
						<td></td>
						<td>b.	Purchase a self-inking stamp</td>
						<td><input type="checkbox" id="stamp" name="common_seal_and_stamp" value="Self-inking Stamp"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><button type="button" class="btn btn-primary send_common_seal_email" name="send_common_seal_email" id="send_common_seal_email" style="margin-right: 10px;">Send</button></td>
					</tr>
					<tr style="height: 50px">
						<td>3.</td>
						<td>Incorporation Services billing</td>
						<td><a href="./billings" class="btn btn-primary" target="_blank">Click here</a></td>
					</tr>
					<tr style="height: 50px">
						<td>4.</td>
						<td>Setup a recurring billing</td>
						<td><a href="./billings" class="btn btn-primary" target="_blank">Click here</a></td>
					</tr>
					<tr style="height: 50px">
						<td>5.</td>
						<td>Setting up bank account with our prefer banker</td>
						<td></td>
					</tr>
					<tr style="height: 50px">
						<td>6.</td>
						<td>Notifying client on the incorporation document</td>
						<td></td>
					</tr>
				</table>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	var transaction_common_seal_vendor_list = <?php echo json_encode(isset($transaction_common_seal_vendor_list) ? $transaction_common_seal_vendor_list : '');?>;

	window.onbeforeunload = function (e) {
		// Your logic to prepare for 'Stay on this Page' goes here 
	    return "Are you sure you want to leave this site?";
	};
</script>
<script src="themes/default/assets/js/acknowledgement_page.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
