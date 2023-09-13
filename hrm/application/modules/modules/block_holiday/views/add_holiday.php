<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px; overflow: auto;">
		<div class="panel-body">
			<form id="block_holiday_submit">
				<div class="col-md-12">
					<div class="form-group">
		            	<div style="width: 100%;">
		                	<div style="width: 25%;float:left;margin-right: 20px;">
		                        <label>Select Date:</label>
		                    </div>
		                	<div style="width: 30%;float: left;">
		                        <div class="input-group date datepicker">
		                        	<div class="input-group-addon">
								        <span class="far fa-calendar-alt"></span>
								    </div>
								    <input type="text" class="form-control" id="block_holiday" name="block_holiday" required>
								</div>
		                    </div>
		                </div>
		            </div>
					<div class="form-group">
		                <div style="width: 100%;">
		                    <div style="width: 25%;float:left;margin-right: 20px;">
		                        <label>Holiday Description: </label>
		                    </div>
		                    <div style="width: 30%;float: left;">
		                        <div style="width:100%">
		                        	<input type="text" class="form-control" name="holiday_description">
		                        </div>
		                    </div>
		                </div>
		            </div>
					<div class="form-group">
		                <div style="width: 100%;">
		                    <div style="width: 25%;float:left;margin-right: 20px;">
		                        <label></label>
		                    </div>
		                    <div style="float:right;margin-bottom:5px;">
		                        <div class="input-group">
		                        	<button class="btn btn_purple" type="submit">Submit</button>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <hr>
		            <div class="form-group">
		                <div style="width: 100%;">
		                    <div style="width: 25%;float:left;margin-right: 20px;">
		                        <label>Holiday list in this year</label>
		                    </div>
		                </div>
		            </div>
		            <div id="holiday_list">
			            <?php 
			            	foreach($holiday_list as $holiday){
			            		echo '<div class="form-group">
						                <div style="width: 100%;">
						                	<input type="hidden" class="holiday_id" value="'. $holiday->id .'" />
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <span>'. date('d F Y', strtotime($holiday->holiday_date)) .'</span>
						                    </div>
						                    <div style="width: 25%;float: left;">
						                        <div class="input-group" style="width:100%">
						                        	<span>'. $holiday->description .'</span>
						                        </div>
						                    </div>

						                    <div style="width: 15%;float: left;">
						                        <div class="input-group" style="width:100%">
						                        	<a style="cursor:pointer" onclick="delete_holiday(this)">Delete</a>
						                        </div>
						                    </div>
						                </div>
						            </div>';
			            	}
			            ?>
			        </div>
				</div>
			</form>
		</div>
	</section>
</section>

<script type="text/javascript">

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
	});

	$("#block_holiday_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "block_holiday/submit_holiday",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			location.reload(true);
           		}
               // alert(data); // show response from the php script.
               // $('#applicant_resume').fileinput('upload');
           }
       	});

    	e.preventDefault(); // avoid to execute the actual submit of the form.
    })

    function delete_holiday(element){
    	var div 		= $(element).parent().parent().parent();
    	var holiday_id 	= div.find('.holiday_id').val();

    	if(confirm("Confirm to delete this holiday?")){
    		$.post("block_holiday/delete_holiday", { 'holiday_id': holiday_id }, function(data, status){
	    	 	if(data){
	    	 		div.remove();
	    	 	}
	    	});
    	}
    }

    // function generate_payslip(){
    //     // console.log('hello');
    //     var payslip_id = $("select[name=selected_month]").val();

    //     if(payslip_id == ''){
    //         alert('Please select a month to view a payslip.');
    //     }else{
    //         view_payslip(payslip_id);
    //     }
    // }

    // function view_payslip(payslip_id){
    //     // console.log(payslip_id);

    //     $.post("payslip/view_payslip", { 'payslip_id': payslip_id }, function(data, status){
    //         // console.log(data);
    //         var response = JSON.parse(data);

    //         window.open(
    //             response.pdf_link,
    //             '_blank' // <- This is what makes it open in a new window.
    //         );
    //     });
    // }
</script>