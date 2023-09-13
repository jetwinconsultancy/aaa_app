<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script> -->

<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/fileinput.css" />
<script src="<?= base_url() ?>application/js/fileinput.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<style>
	.file-preview button span {
		display:none;
	}
</style>

<script type="text/javascript" src="<?= base_url()?>application/js/custom/time_format.js"></script>

<section class="panel" style="margin-top: 30px;">
<div class="panel-body">
	<div class="box-content">
	    <div class="row">
	        <div class="col-lg-12">
	            <form id="create_timesheet" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off"> 
	            	<input type="hidden" name="employee_id" value="<?=isset($employee_id)?$employee_id:''?>">

	            	<div class="form-group row">
					    <label class="col-sm-2 col-form-label">Month:</label>
					    <div class="col-sm-5">
					    	<input type="text" class="form-control form-control-1 input-sm from" placeholder="Select a month" name="timesheet_month" required>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-sm-7">
					    	<?php echo '<a href="'.base_url().'timesheet/index" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

					    		echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
					    	?>
					    </div>
                    </div>
           		</form>
				<hr>

				<div>
					<h3>Created Timesheet List</h3>
				</div>

            	<table class="table" id="datatable-default" style="width:100%">
					<thead>
						<tr style="background-color:white;">
							<th class="text-left">Timesheet no</th>
							<th class="text-left">Month</th>
							<!-- <th class="text-left"></th> -->
						</tr>
					</thead>
					<tbody id="timesheet_listt">
						<?php 
							foreach($timesheet_list as $row)
				  			{
				  				echo '<tr>';
				  				echo '<input type="hidden" class="timesheet_id" value="'. $row->id .'">';
				  				echo '<td>'. $row->timesheet_no .'</td>';
				  				echo '<td>'. date('F Y', strtotime($row->month)) .'</td>';
				  				// echo '<td><a style="cursor:pointer;" onclick="delete_timesheet(this)">Delete</a></td>';
				  				echo '</tr>';
				  			}
				  		?>
					</tbody>
				</table>
	        </div>
	    </div>
	</div>
</div>
</section>

<script type="text/javascript">
var base_url = '<?php echo base_url(); ?>';
var timesheet_list = <?php echo json_encode($timesheet_list) ?>;
var previous_timesheet_submited_flag = true;

var this_month = new Date();
this_month = this_month.getMonth() + 1;

for(var a=0 ; a<timesheet_list.length ; a++)
{
	var this_timesheet_month = new Date(timesheet_list[a]['month']);
	this_timesheet_month = this_timesheet_month.getMonth() + 1;

	if(this_timesheet_month != this_month)
	{
		if(timesheet_list[a]['status_id'] == 1)
		{
			previous_timesheet_submited_flag = false;
		}
	}
}

$('.from').datepicker({
    autoclose: true,
    minViewMode: 1,
    endDate: '+0m',
    format: 'M yyyy'
})

$('form#create_timesheet').submit(function(e) {
    var form = $(this);

    e.preventDefault();

    if(previous_timesheet_submited_flag)
    {
    	$.ajax({
	        type: "POST",
	        url: "<?php echo site_url('timesheet/create_timesheet'); ?>",
	        data: new FormData(this),
	        processData:false,
	     	contentType:false,
	        success: function(result)
	        {
	        	if(result){
	        		window.location = base_url + "timesheet/create";
	        	}
	        	else
	        	{
	        		toastr.error("Timesheet Already Exist", 'Create Failed');
	        	}
	        }
	   	});
    }
    else
    {
    	toastr.error("Please Make Sure Previous Month Timesheet Is Submitted", 'Create Failed');
    }

});

</script>  