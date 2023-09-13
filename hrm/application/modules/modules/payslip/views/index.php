<!-- <link href="node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>
 -->
<section class="panel" style="margin-top: 30px;">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="form-group">
        		<!-- <input type="hidden" class="form-control" id="staff_id" name="staff_id" value="<?=$staff[0]->id ?>" style="width: 400px;"/> -->
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Select Month :</label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<?php
								echo form_dropdown('selected_month', $payslip_months, '', 'class="form-control" style="width:150%;"');
							?>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
        		<!-- <input type="hidden" class="form-control" id="staff_id" name="staff_id" value="<?=$staff[0]->id ?>" style="width: 400px;"/> -->
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label></label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<button class="btn btn_purple" onclick="generate_payslip()">Generate</button>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</section>

<script type="text/javascript">
    function generate_payslip(){
        // console.log('hello');
        var payslip_id = $("select[name=selected_month]").val();

        if(payslip_id == ''){
            alert('Please select a month to view a payslip.');
        }else{
            view_payslip(payslip_id);
        }
    }

    function view_payslip(payslip_id){
        // console.log(payslip_id);

        $.post('<?php echo base_url(); ?>' + "payslip/view_payslip", { payslip_id: payslip_id }, function(data, status){
            // console.log(data);
            var response = JSON.parse(data);

            window.open(
                response.pdf_link,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
    }
</script>