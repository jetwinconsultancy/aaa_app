<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script> -->

<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>

<section class="body">
	<div class="inner-wrapper">
		<section role="main" class="content_section" style="margin-left:0;">
			<div class="box" style="margin-bottom: 30px; margin-top: 30px;">
				<div class="box-content">
				    <div class="row">
				        <div class="col-lg-12">
				            <form id="set_bonus" method="POST">
				            	<input type="hidden" name="selected_month" id="selected_month" value="<?=isset($selected_month)?$selected_month:''?>">
			                    <table class="table">
								  	<thead>
								    	<tr>
								      		<th scope="col">Employee Name</th>
								      		<th scope="col">Bonus</th>
								      		<th scope="col">Commission</th>
								      		<th scope="col">Other Allowance</th>
								      		<th scope="col">
								      			<a onclick="add_bonus_tr()"><span class="glyphicon glyphicon-plus-sign"></span>
								      		</a>
								      	</th>
								    	</tr>
								  	</thead>
								  	<tbody id="set_bonus_tbody"></tbody>
								</table>

								<div class="form-group row">
									<div class="col-sm-7">
								    	<?php echo '<a href="'.base_url().'payslip/index_admin" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

								    		if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null))
								    		{
								    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Submit</button>';
								    		}
								    		else
								    		{
								    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
								    		}
								    	?>
								    </div>
			                    </div>
						    </form>
					    </div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>


<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	    	<div class="modal-header">
	      		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		  	    </button>
		  	    <?php 
		  	    	if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<h4 class="modal-title">Success apply</h4>';
		  	    	} else {
		  	    		echo '<h4 class="modal-title">Success update</h4>';
		  	    	}
		  	    ?>
	      		
	      	</div>
	      	<div class="modal-body">
	      		<?php 
	      			if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<p>Your MC has been successfully applied.</p>';
		  	    	} else {
		  	    		echo '<p>MC has been successfully updated.</p>';
		  	    	}
	      		?>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default cancelBtn" data-dismiss="modal">Close</button>
	      	</div>
    	</div>
  	</div>
</div> -->
<!-- <script src="<?= base_url() ?>application/views/modal/modal_template.php"></script> -->

<script type="text/javascript">
	var count_bonus = 0;
	var base_url = "<?php echo base_url(); ?>";
	var payslip_list = <?php echo json_encode($payslip_list); ?>;

	// console.log(payslip_list);

	if(payslip_list.length < 1){
		add_bonus_tr('');
	}else{
		payslip_list.forEach(function(obj){
			add_bonus_tr(obj);
		});
	}

	function add_bonus_tr(obj){
		// console.log("count_bonus", count_bonus);

		$.ajax({
		  	type: "POST",
		  	url: base_url + 'payslip/set_bonus_tr_partial',
		  	data: { 'count': count_bonus, 'bonus_details': obj, 'selected_month': $('#selected_month').val() },
		  	async:false,
		  	success: function(data){
				//   console.log(data);
				
				var this_row = $('#set_bonus_tbody').prepend(data);
				var this_dropdown = this_row.find('.employee-select');

				this_dropdown.select2();
				count_bonus++;
		  	}
		});
		// $.post(base_url + 'payslip/set_bonus_tr_partial', { 'count': count_bonus, 'bonus_details': obj }, function(data, status){
		// 	$('#set_bonus_tbody').prepend(data);

  //           count_bonus++;
	 //    });
	}

    $('form#set_bonus').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('payslip/submit_bonus'); ?>",
	        data: form.serialize(),
	        dataType: "html",
	        success: function(data){
	        	var return_data = JSON.parse(data);

	        	// if(return_data.result){
	        	// 	return_data.data.forEach(function(data, index){
	        	// 		$('input[name="payslip_id['+ index +']"]').val(data.id);
	        	// 	});
	        	// }
	        	
	        	if(return_data.result){
	        		alert("Successfully set this month bonus for employee(s).");

	        		window.location = base_url + "payslip";
	        	}else{
	        		alert("Something went wrong. Please try again later.");
	        	}
	        }
	        // error: function() { alert("Error posting feed."); }
	   });
	});


</script>  