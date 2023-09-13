<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>node_modules/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>


<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div style="text-align:center;">
			<div style="display:inline-block;" class="pull-left">
				<a id="prevBtn" style="cursor: pointer;" onclick="getMonthAndRender('prev')">
		          <span class="glyphicon glyphicon-menu-left"></span>
		        </a>
			</div>
			<div style="display:inline-block;">
				<h3 class="payslip_month" style="margin: 0px;" id="display_month"></h3>
				<input id="hidden_display_month" type="hidden" value=""/>
			</div>
			<div style="display:inline-block;" class="pull-right">
				<a id="nextBtn" style="cursor: pointer;" onclick="getMonthAndRender('next')">
		          <span class="glyphicon glyphicon-menu-right"></span>
		        </a>
			</div>
		</div>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="action_section" style="text-align: center;"></div>
			<div style="float: left; width: 200px;">
				<label class="control-label">Departments</label>
				<?php
					echo form_dropdown('department', $department, isset($department)?$department[0]:'', ' id="A_department_filter" class="department A_department_filter" style="width:85%;"');
				?>
			</div>
			<div style="padding-top:24px;">
				<button type="submit" id ="generate_Excel" class="btn btn_purple" >Generate Excel</button>
			</div>
			<div class="row datatables-header form-inline col-sm-12 col-md-12">
				<div class="col-sm-12 col-md-12">
					<!-- <div class="dataTables_filter" id="datatable-default_filter">
						<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<a href="Employee" class="btn btn_purple">Show All Employee</a>
						<?= form_close();?>
					</div> -->
				</div>
				
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left" width="30%">Employee Name</th>
								<th class="text-left" width="22%">Department</th>
								<th class="text-left" width="12%">Salary</th>
								<th class="text-left" width="12%">Bonus</th>
								<th class="text-center" width="12%">Bond Allowance</th>
								<th class="text-center" width="12%">Payslip</th>
							</tr>
						</thead>
						<tbody id="employee_payslip_list"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<div id="payslip_confirmation" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 900px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">PAYSLIP CONFIRMATION</h2>
			</header>
			<div class="modal-body">
				<form id="payslip_form">
					<?php if($Admin) { ?>
					<div style="float: left; width: 200px;">
						<label class="control-label">Departments</label>
						<?php
							echo form_dropdown('confirmation_department', $department, isset($confirmation_department)?$confirmation_department[0]:'', ' id="B_department_filter" class="confirmation_department B_department_filter" style="width:85%;"');
						?>
					</div>
					<?php } ?>

					<table class="payslip_summary_tbl table table-bordered table-striped mb-none" style="width: 100%;">
						<thead>
							<input name="selected_month" type="hidden" value=""/>
							<tr style="background-color:white;">
								<th class="text-left" width="28%">Employee Name</th>
								<th class="text-left th-salary" width="24%">Salary</th>
								<th class="text-left th-salary" width="24%">Bond Allowance</th>
								<th class="text-left th-salary" width="24%">Bonus</th>
								<th class="text-left th-cpf" width="14%" style="display:none;">OW Payable (CPF)</th>
								<th class="text-left th-cpf" width="14%" style="display:none;">AW Payable (CPF)</th>
								<th class="text-left th-cpf" width="7%" style="display:none;">CPF Employee (%)</th>
								<th class="text-left th-cpf" width="7%" style="display:none;">CPF Employer (%)</th>
								<th class="text-left th-cpf" width="13%" style="display:none;">CPF Employee</th>
								<th class="text-left th-cpf" width="13%" style="display:none;">CPF Employer</th>
							</tr>
						</thead>
						<tbody id="payslip_summary_list">
							
						</tbody>
					</table>
				
				</form>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn_purple next_btn" onclick="submit_calculate_cpf()" name="" value="Next">
				<input type="button" id="confirm_btn" class="btn btn_purple confirm_btn" onclick="submit_generate_payslip()" name="" data-dismiss="modal" style="display:none;" value="Confirm">

				<input type="button" class="btn btn-default cancel next_btn" data-dismiss="modal" name="" value="Cancel">
				<input type="button" class="btn btn-default cancel confirm_btn" onclick="cancel_generate()" name="" value="Cancel">

			</div>
		</div>
	</div>
</div>

<table id="clone_payslip_form" style="display: none;" >
	<tr class="">
		<td valign=middle>
			<p class="employee_name"></p> 
		</td>
		<td valign=middle>
			<input type='hidden' name="employee_id[]" value="" /> 
			<input type='number' class="form-control general" name="payslip_salary[]" value="" style="width: 80%; display:inline-block !important;" required/> 
		</td>
		<td valign=middle>
			<input type='number' class="form-control general" name="payslip_bond_allowance[]" value="" style="width: 80%; display:inline-block !important;" required/> 
		</td>
		<td valign=middle>
			<input type='number' class="form-control general" name="payslip_bonus[]" value="" style="width: 80%; display:inline-block !important;" required/> 
		</td>		
	</tr>
</table>

<table id="clone_payslip_cpf_form" style="display: none;" >
	<tr class="">
		<td valign=middle>
			<p class="employee_name"></p> 
		</td>
		<td valign=middle>
			<input type='number' class="form-control general salary_cpf_payable" name="salary_cpf_payable[]" value="" style="width: 100%; display:inline-block !important;" required/> 
		</td>
		<td valign=middle>
			<input type='number' class="form-control general bonus_cpf_payable" name="bonus_cpf_payable[]" value="" style="width: 100%; display:inline-block !important;" required/> 
		</td>
		<td valign=middle>
			<p class="employee_percentage text-right"></p> 
		</td>
		<td valign=middle>
			<p class="employer_percentage text-right"></p> 
		</td>
		<td valign=middle>
			<input type='hidden' name="employee_id[]" value="" /> 
			<input type='hidden' name="payslip_id[]" value="" /> 
			<input type='number' class="form-control general disable" name="cpf_employee[]" readonly value="" style="width: 100%; display:inline-block !important;" required/> 
		</td>
		<td valign=middle>
			<input type='number' class="form-control general disable" name="cpf_employer[]" readonly value="" style="width: 100%; display:inline-block !important;" required/> 
		</td>	
	</tr>
</table>

<script type="text/javascript">
	var month_list = <?php echo json_encode($month_list); ?>;
	var this_month_date = generate_date('','now');

	if(!(month_list.length > 0)){
		month_list.push(this_month_date);
	}

	$(document).ready( function () {
		
	    getNewIndexAndRender("right");
		$(".department").select2();
		$(".confirmation_department").select2();
		


	} );

	var resultBox = document.getElementById('display_month');
	var months 	  = month_list;
	var length 	  = months.length;
	var idx 	  = length - 1; // idx is undefined, so getNextIdx will take 0 as default
	var this_month_beg = new Date(this_month_date);

	

	$('.payslip_month').datepicker({
	    autoclose: true,
	    minViewMode: 1,
	    format: 'MM yyyy'
	}).on("changeDate", function(event) {
		var selectedMonth = moment(event.date).format('MMMM Y');
		// var new_date_string = moment(new_date, 'Y-M-D').format('MMMM Y');
		resultBox.innerHTML = selectedMonth;
		var temp = moment(event.date).format('Y-M-D');
		refresh_table_display(temp);

	});

	// // get the month where it is same month with this month.
	// month_list.forEach(function(element, index){
	// 	var temp_date = new Date(element);
	// 	// console.log(temp_date, this_month_beg);
	// 	if(temp_date.getTime() == this_month_beg.getTime()){
	// 		idx = index;
	// 	}
	// });
    $(".department").change(function (e){
		refresh_table_display($('#hidden_display_month').val());
		
	});

	$(".confirmation_department").change(function (e){
		generate_payslip($('#hidden_display_month').val());
		
	});

	var getNextIdx = (idx = 0, length, direction) => {

	   switch (direction) {
	     case 'next': {
	     	// return (idx + 1) % length;
	     	if(months[idx + 1] == undefined){
				var selected_month_temp_date = generate_date(month_list[idx], "next");

				months.push(selected_month_temp_date);
	     	}

	     	return idx + 1;
	     };
	     case 'prev': {
	     	// return (idx == 0) && length - 1 || idx - 1;
	     	if(months[idx - 1] == undefined){
	     		var selected_month_temp = new Date(month_list[idx]);
				selected_month_temp.setMonth(selected_month_temp.getMonth() - 1);

				var selected_month_temp_date = generate_date(month_list[idx], "prev");

				months.unshift(selected_month_temp_date);

				return idx;
	     	}
	     	else{
	     		return idx - 1;
	     	}
	     };
	     default: return idx;
	   }
	}

	const getMonthAndRender = (direction) => {
		var current_month = document.getElementById("display_month").innerText;
		var temp = moment(current_month, 'MMMM Y').format('Y-M-D');
		var selected_month_temp = new Date(temp);
		var new_date;
		switch (direction) {
			case 'next': {
				
				var new_selected_month = selected_month_temp.getMonth()==11?1:selected_month_temp.getMonth()+2;
				var new_selected_year;

				if(selected_month_temp.getMonth()==11)
				{
					new_selected_year = selected_month_temp.getFullYear() + 1;
				}
				else
				{
					new_selected_year = selected_month_temp.getFullYear();
				}
			
				new_date = new_selected_year + '-' + new_selected_month + '-01';

				var new_date_string = moment(new_date, 'Y-M-D').format('MMMM Y');
				resultBox.innerHTML = new_date_string;
				break;

				
			};
			case 'prev': {

				var new_selected_month = selected_month_temp.getMonth()==0?12:selected_month_temp.getMonth();
				var new_selected_year;

				if(selected_month_temp.getMonth()==0)
				{
					new_selected_year = selected_month_temp.getFullYear() - 1;
				}
				else
				{
					new_selected_year = selected_month_temp.getFullYear();
				}
			
				new_date = new_selected_year + '-' + new_selected_month + '-01';

				var new_date_string = moment(new_date, 'Y-M-D').format('MMMM Y');
				resultBox.innerHTML = new_date_string;
				break;

			};

			
		}

		$('#hidden_display_month').val(new_date);
		$("[name='selected_month']").val(new_date);
		refresh_table_display(new_date);

	}

	const getNewIndexAndRender = (direction) => {
	    idx = getNextIdx(idx, length, direction);

		// console.log(months);

	    $('#hidden_display_month').val(months[idx]);
		$("[name='selected_month']").val(months[idx]);
	    resultBox.innerHTML = moment(months[idx], 'Y-M-D').format('MMMM Y');


	    refresh_table_display(months[idx]);
	}

	function refresh_table_display(selected_month){

		$.post("payslip/getThisMonthPayslipList", { 'date': selected_month, 'department': $('.department').val() }, function(data, status){
	    	var info = JSON.parse(data);

	    	// var info = [];
			// console.log(info);
			$("#datatable-default").DataTable().destroy();
			$("#employee_payslip_list").empty();
			$("#action_section").empty();

			info.payslip.forEach(function(element){
				// console.log(element);
				$('#employee_payslip_list').append(
					'<tr>' +
						'<td width="30%">'+ element.name +'</td>'+
						'<td width="22%">'+ element.department_name +'</td>'+
						'<td width="12%">'+ element.basic_salary +'</td>'+
						'<td width="12%">'+ element.bonus +'</td>'+
						'<td width="12%">'+ element.bond_allowance +'</td>'+
						'<td align="center" width="12%"><button class="btn btn_purple" onclick="view_payslip('+ element.id +')">View</button></td>'+
					'</tr>'
				);
				// $('#datatable-default').DataTable().ajax.reload();
			});

			$('#action_section').append(
				'<div style="margin: 0.1%; text-align: center; display: none;">' +
					'<button  onclick="location.href=\'payslip/set_bonus/'+selected_month+'\'"  class="btn btn_purple">Set Bonus</button>' +
				'</div>' +
				'<div style="margin: 0.1%; text-align: center; display: inline-block;">' +
					'<button class="btn btn_purple" onclick="generate_payslip(\'' + selected_month + '\')">Generate Payslip</button>' +
				'</div>' 
			);

			$('#datatable-default').DataTable();


			if(info.payslip.length == 0 || info.payslip.length == undefined){
				// $('#datatable-default').DataTable().ajax.reload();
				// $('#datatable-default').DataTable().draw();
			}
        });
	}

	function view_payslip(payslip_id){
		// console.log(payslip_id);

		$.post("payslip/view_payslip", { 'payslip_id': payslip_id }, function(data, status){
			// console.log(data);
			var response = JSON.parse(data);

			window.open(
              	response.pdf_link,
              	'_blank' // <- This is what makes it open in a new window.
            );
		});
	}

	function generate_payslip(selected_month){
    	$("#payslip_confirmation").modal("show"); 
		$.post("payslip/getThisMonthPayslipConfirmation", { 'selected_month': selected_month, 'department' : $('.confirmation_department').val() }, function(data, status){
	    	var info = JSON.parse(data);

			$("#payslip_summary_list").empty();

			$(".th-salary").show();
			$(".th-cpf").hide();

			$(".next_btn").show();
			$(".confirm_btn").hide();

			info.forEach(function(each){
				var basic_salary = !isNaN(parseFloat(each.basic_salary))?parseFloat(each.basic_salary):0;
				var bond_allowance = !isNaN(parseFloat(each.bond_allowance))?parseFloat(each.bond_allowance):0;

				// $('#payslip_summary_list').append(
				// 	'<tr>' +
				// 		'<td width="65%">'+ element.employee_name +'</td>'+
				// 		'<td width="35%">'+ (basic_salary+bond_allowance) +'</td>'+
				// 	'</tr>'
				// );

				var content = jQuery('#clone_payslip_form tr'),
				element = null,    
				element = content.clone();

				// console.log(each);
				
				$(element.find(".employee_name")).text(each.employee_name);
				element.find("[name='payslip_salary[]']").attr("value",basic_salary);
				element.find("[name='payslip_bond_allowance[]']").attr("value",bond_allowance);
				element.find("[name='payslip_bonus[]']").attr("value",0);
				element.find("[name='employee_id[]']").attr("value",each.employee_id);

		
				element.appendTo('#payslip_summary_list');
				// $('#datatable-default').DataTable().ajax.reload();
			});
        });

		// $.post("payslip/generate_payslip", { 'selected_month': selected_month }, function(data, status){
        //     var selected_month = $('#hidden_display_month').val();

        //     if(data){
        //     	refresh_table_display(selected_month);
        //     }
        // });
	}

	function submit_calculate_cpf(){
		var selected_month = $('#hidden_display_month').val();

		// $.post("payslip/calculate_cpf", { 'data': data }, function(data, status){
        //     // var selected_month = $('#hidden_display_month').val();

        //     // if(data){
        //     // 	refresh_table_display(selected_month);
        //     // }
        // });
		$.ajax({
            type: "POST",
            url: "payslip/calculate_cpf",
            data: $("#payslip_form").serialize(), // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
                // console.log(response);   

				$("#payslip_summary_list").empty();

				$(".th-salary").hide();
				$(".th-cpf").show();

				$(".next_btn").hide();
				$(".confirm_btn").show();
	
	
				response.forEach(function(each){
					var cpf_employee = !isNaN(parseFloat(each.cpf_employee))?parseFloat(each.cpf_employee):0;
					var cpf_employer = !isNaN(parseFloat(each.cpf_employer))?parseFloat(each.cpf_employer):0;

					// $('#payslip_summary_list').append(
					// 	'<tr>' +
					// 		'<td width="65%">'+ element.employee_name +'</td>'+
					// 		'<td width="35%">'+ (basic_salary+bond_allowance) +'</td>'+
					// 	'</tr>'
					// );

					var content = jQuery('#clone_payslip_cpf_form tr'),
					element = null,    
					element = content.clone();

					// console.log(each);
					$(element.find(".employee_name")).text(each.employee_name);
					$(element.find(".employee_percentage")).text(each.employee_percent);
					$(element.find(".employer_percentage")).text(each.employer_percent);
					element.find("[name='salary_cpf_payable[]']").attr("value",each.salary_cpf_payable);
					element.find("[name='bonus_cpf_payable[]']").attr("value",each.bonus_cpf_payable);
					element.find("[name='cpf_employer[]']").attr("value",cpf_employer);
					element.find("[name='cpf_employee[]']").attr("value",cpf_employee);
					element.find("[name='employee_id[]']").attr("value",each.employee_id);
					element.find("[name='payslip_id[]']").attr("value",each.id);
			
					element.appendTo('#payslip_summary_list');
					// $('#datatable-default').DataTable().ajax.reload();
				});    

            }
        });
	}


	function submit_generate_payslip(){
		var selected_month = $('#hidden_display_month').val();

		// $.post("payslip/calculate_cpf", { 'data': data }, function(data, status){
        //     // var selected_month = $('#hidden_display_month').val();

        //     // if(data){
        //     // 	refresh_table_display(selected_month);
        //     // }
        // });
		$.ajax({
            type: "POST",
            url: "payslip/submit_payslip",
            data: $("#payslip_form").serialize(), // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
				refresh_table_display(selected_month);
            }
        });
	}

	function cancel_generate(){
		if($("#confirm_btn").is(":visible")){
			// alert("This action will delete existing payslips of selected employees. Continue?");
			if (confirm('This action will delete existing payslips of selected employees. Continue?')) {
				// Save it!
				// console.log('Thing was saved to the database.');
				$.ajax({
					type: "POST",
					url: "payslip/cancel_generate",
					data: $("#payslip_form").serialize(), // <--- THIS IS THE CHANGE
					dataType: "json",
					'async':false,
					success: function(response)
					{
						refresh_table_display(selected_month);
					}
				});
			} 
			// else {
			// 	// Do nothing!
			// 	console.log('Thing was not saved to the database.');
			// }
		}
	}

	function generate_date(input_date, prev_next){
		var date = new Date(input_date);

		if(input_date == ''){
			date = new Date();
		}

		if(prev_next == "prev"){
			date.setMonth(date.getMonth() - 1);
		}
		else if(prev_next == "next"){
			date.setMonth(date.getMonth() + 1);
		}

		var output_date = date.getFullYear() + '-' + parseInt(date.getMonth() + 1) + '-' + '01';

		return output_date;
	}

	function recalculate_cpf(element)
	{
		var tr = $(element).parent().parent();
		
		var salary_cpf_payable = tr.find("[name='salary_cpf_payable[]']").val();
		var bonus_cpf_payable = tr.find("[name='bonus_cpf_payable[]']").val();
		var employee_percentage = $(tr.find(".employee_percentage")).text();
		var employer_percentage = $(tr.find(".employer_percentage")).text();

		var cpf_employer = (Number(salary_cpf_payable) + Number(bonus_cpf_payable)) * (Number(employer_percentage)/100);
		var cpf_employee = (Number(salary_cpf_payable)+Number(bonus_cpf_payable)) * (Number(employee_percentage)/100);


		tr.find("[name='cpf_employer[]']").attr("value",cpf_employer);
		tr.find("[name='cpf_employee[]']").attr("value",cpf_employee);

	}

	$(document).on('change keyup','.salary_cpf_payable',function(){
		recalculate_cpf(this);

	});

	$(document).on('change keyup','.bonus_cpf_payable',function(){
		recalculate_cpf(this);

	});


	// $(".salary_cpf_payable").on('change',function(){
	// 	var tr = $(this).parent().parent();
	// 	console.log(tr);
	// 	console.log("HI");
	// });

	$("#generate_Excel").click(function(e) {
		var selected_month = $('#hidden_display_month').val();
        $.ajax({
			type: "POST",
			url: "payslip/generateExcel",
			data: '&selected_month=' + selected_month,
			success: function(response,data)
			{	
				toastr.success('Excel File Generated', 'Successful');
				window.open(
					response,
					'_blank' // <- This is what makes it open in a new window.
				);
			}
		});

    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

</script>