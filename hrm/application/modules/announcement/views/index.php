<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>node_modules/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<header class="panel-heading">
			<div class="panel-actions" style="height:80px">
				<?php if($Admin || $User == '79' || $User == '97') {?>
					<a href="javascript:void(0)" data-toggle="modal" class="add_new_announcement amber" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Announcement" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> New Announcement </a>
				<?php } ?>
			</div>
			<h2></h2>
		</header>
		<div style="padding-left: 15px;padding-top: 5px;">
			<label style="display:block;" class="control-label"><strong>Records :</strong></label>
        	<input style="display:block;" type="checkbox" name="record_filter" style="width: 100%;"/>
        </div>
		<div class="panel-body">
			<table class="table table-bordered table-striped mb-none datatable-announcement" id="datatable-announcement">
				<thead>
					<tr style="background-color:white;">
						<th class="text-left" style="width:20%">Date</th>
						<th class="text-left" style="width:60%">Announcement</th>
						<th class="text-left" style="width:20%">Department</th>
					</tr>
				</thead>
				<tbody class="announcement_table">
					<?php 
						foreach($announcement_list as $announcement)
						{
							echo '<tr class="announcement_tr" >';
							echo '<td>'.date('d F Y', strtotime($announcement['date'])).'</td>';
							echo '<td><a href="javascript:void(0)" data-id="'.$announcement['id'].'" class="edit_announcement">'.$announcement['title'].'</a></td>';
							echo '<td>'.$announcement['department_list'].'</td>';
							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</section>
</section>

<div id="announcement" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 900px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Announcement</strong></h2>
			</header>
			<form id="new_announcement">
				<div class="panel-body">
					<div class="col-md-12">

						<input type="hidden" class="form-control" name="id" id="id"/>

		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Department :</label>
		                        </div>
		                        <div style="width: 70%;float:left;margin-bottom:5px;">
		                            <div style="width: 50%;">
		                            	<input type="hidden" class="form-control" name="department" id="department"/>
		                            	<?php 
                                    		// echo form_dropdown('', $department_list, '', 'class="form-control" id="department_dropdown"');

                                    		echo form_dropdown('', $department_list,'', 'id="department_dropdown" class="department_dropdown" style="width:100% !important;" multiple="multiple" required');
                                    	?>
		                            </div>
		                        </div>
		                    </div>
		                </div>

						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Title :</label>
		                        </div>
		                        <div style="width: 70%;float:left;margin-bottom:5px;">
		                            <div style="width: 100%;">
		                            	<input type="text" class="form-control" id="announce_title" name="announce_title" style="width: 100%;"/>
		                            </div>
		                        </div>
		                    </div>
		                </div>

		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Announcement :</label>
		                        </div>
		                        <div style="width: 70%;float:left;margin-bottom:5px;">
		                            <div style="width: 100%;">
		                            	<textarea class="form-control" id="announce_info" name="announce_info" style="width: 100%;height: 250px" required="true"></textarea>
		                            </div>
		                        </div>
		                    </div>
		                </div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id ="announce_btn" class="btn btn_purple">Announce</button>
					<button type="button" id ="update_btn" class="btn btn_purple" style="display: none">Update</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script>
	<?php if(!$Admin && $User != '79') {?>
		// document.getElementById('announce_title').readOnly = true;
		// document.getElementById('announce_info').readOnly  = true;
		// document.getElementById('announce_btn').style.visibility  = 'hidden';
	<?php } ?>

	var announcement_list = <?php echo json_encode(isset($announcement_list)?$announcement_list:"") ?>;

	$('#department_dropdown').multiselect({
	    allSelectedText: 'All',
	    enableFiltering: true,
	    numberDisplayed: 6,
	    enableCaseInsensitiveFiltering: true,
	    maxHeight: 200,
	    includeSelectAllOption: true
	});

	$("[name='record_filter']").bootstrapSwitch({
	    // state: <?php // echo isset($staff[0]->aws_given)? $staff[0]->aws_given : 0 ?>,
	    size: 'normal',
	    onColor: 'purple',
	    onText: 'Old',
	    offText: 'New',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '75px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'
	});

	$(document).ready(function ()
	{
		// $("#department_dropdown").select2();

		$(".datatable-announcement").DataTable({
			"order": [],
			"autoWidth" : false
		});
	});

	$(".cancel").click(function()
	{
		location.reload();
	});

	$(".add_new_announcement").click(function()
	{
    	$("#announcement").modal("show"); 
	});

	// $(".edit_announcement").click(function()
	$(document).on('click',".edit_announcement",function()
	{
		console.log($(this).data("id"));

		<?php if(!$Admin && $User != '79' && $User != '97') {?>
			document.getElementById('announce_title').readOnly = true;
			document.getElementById('announce_info').readOnly  = true;
			document.getElementById('announce_btn').style.visibility  = 'hidden';
		<?php } else { ?>
			$("#update_btn").show();
			document.getElementById('announce_title').readOnly = true;
			document.getElementById('announce_info').readOnly  = true;
			$('#announce_btn').hide();
		<?php }?>

		$("#announce_btn").css("display","inline-block !important");
	    // $("#edit_client").css("display","none");

    	$("#announcement").modal("show"); 

    	for(var i = 0; i < announcement_list.length; i++)
    	{
			if(announcement_list[i]["id"] == $(this).data("id"))
			{
				$("#id").val(announcement_list[i]['id']);

				$("#department").val(announcement_list[i]['department']);

				$depart_arr = announcement_list[i]['department'].split(',');  
		    	$('#department_dropdown').val($depart_arr);
		    	$("#department_dropdown").multiselect("refresh");
		    	$("#department_dropdown").multiselect("disable");

				$("#announce_title").val(announcement_list[i]['title']);
				$("#announce_info").val(announcement_list[i]['announcement']);
			}
		}

	});

	$("#new_announcement").submit(function(e)
	{
		document.getElementById('announce_btn').disabled = 'disabled';
		$("#announcement").modal("hide"); 
		$('#loadingmessage').show();

		e.preventDefault();

		var form = $(this);

		$("#department").val($("#department_dropdown").val());

		$.ajax({
           type: "POST",
           url: "announcement/new_announcement",
           data: form.serialize(),
           success: function(data)
           {	
           		if(data){
  					$('#loadingmessage').hide();
           			toastr.success('Announcement Updated', 'Successfully');
           			setTimeout(function(){location.reload();}, 500);
           		}
           }
       	});

	});

	$("#update_btn").click(function()
	{
		document.getElementById('announce_title').readOnly = false;
		document.getElementById('announce_info').readOnly  = false;

		$("#announce_btn").show();
		$('#update_btn').hide();
	});

	$("[name='record_filter']").on('switchChange.bootstrapSwitch', function(event, state) {

		$.ajax({
	       type: "POST",
	       url:  "announcement/record_filter",
	       data: '&result=' + state,
	       success: function(data)
	       {

	       		if(JSON.parse(data)==null || JSON.parse(data)==""){
	       			$(".datatable-announcement").DataTable().destroy();
	       			var table  = $(".datatable-announcement").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
	       		}

	       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
	       			$(".datatable-announcement").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".announcement_tr").remove();

	           		for(var i=0; i<object.length; i++){
	           			var rowHtml = "";
	           			rowHtml += "<tr class='announcement_tr' >";
	           			rowHtml += "<td>"+moment(object[i]['date']).format('DD MMMM YYYY')+"</td>";
	           			rowHtml += "<td><a href='javascript:void(0)'' data-id='"+object[i]['id']+"' class='edit_announcement'>"+object[i]['title']+"</a></td>";
	           			rowHtml += "<td>"+object[i]['department_list']+"</td>";
	           			rowHtml += "</tr>";

	           			$(".announcement_table").append(rowHtml);
	           		}

	           		announcement_list = object;
	       		}

	       		$('.datatable-announcement').DataTable( {
					"order": [],
					"autoWidth" : false
				} );
	       }
	   	});

	});
</script>