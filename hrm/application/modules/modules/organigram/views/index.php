<link href="<?= base_url() ?>application/css/org_chart.css" rel="stylesheet" type="text/css">

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 5px;">
		<div class="panel-body">
			<div class="centered">
				<ul class="orgchart">
					<li class="root">
						<div class="nodecontent" style="width: 200px" onclick="show_orgChart_details(this)">CORE PARTNER</div>
						<ul class="ulheard">

						</ul>
					</li>
				</ul>
			</div>
		</div>
	</section>
</section>

<div id="org_chart_detail" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 900px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title" id="department_title"></h2>
			</header>
			<div class="modal-body">
				<u><strong><p id="designation_title"></p></strong></u>
				<table class="position_table" style="width: 100%">

				</table>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
			</div>
		</div>
	</div>
</div>

<script>

	var department_list = <?php echo json_encode(isset($department_list)?$department_list:"") ?>;

	for(var a = 0; a < department_list.length; a++)
	{
		var position = 0;

		var departmentbox = '<li><div class="nodecontent depart" style="width: 96%;background-color: #52307c; ">'+department_list[a]['department_name']+' DEPT</div><ul class="departclass'+department_list[a]['id']+''+position+'"></ul></li>';

		$(".ulheard").append(departmentbox);

		$.ajax({
			'async':false,
	       	type: "POST",
	       	url: "<?php echo site_url('organigram/get_designation'); ?>",
	       	data: "&department="+ department_list[a]['id'],
	       	success: function(data)
	       	{
	       		var result = JSON.parse(data);
	       		var designation_count = result.length;

	       		for(var b = 0; b < result.length; b++)
				{
					if(designation_count == (b+1))
					{
						var designationbox = '<li class="leaf"><div class="nodecontent" style="width: 96%;" onclick="show_orgChart_details(this,'+department_list[a]['id']+')">'+result[b]['designation']+'</div><ul class="departclass'+department_list[a]['id']+''+(b+1)+'"></ul></li>';
					}
					else
					{
						var designationbox = '<li><div class="nodecontent" style="width: 96%;" onclick="show_orgChart_details(this,'+department_list[a]['id']+')">'+result[b]['designation']+'</div><ul class="departclass'+department_list[a]['id']+''+(b+1)+'"></ul></li>';
					}

					$(".departclass"+department_list[a]['id']+""+b+"").append(designationbox);

					position++;
				}
	       	}
	   	});
	}

	$(".show_orgChart_details").click(function()
	{
    	$("#org_chart_detail").modal("show"); 

	});

	function show_orgChart_details(element,depart_id)
	{	
		var image = '<img src="<?php echo base_url('assets/logo/no_image.png');?>" width=100px style="margin:5px" height=100px alt="" />';

		$(".position_table").empty();

		for(var a = 0; a < department_list.length; a++)
		{
			if(department_list[a]['id'] == depart_id)
			{
				var department  = department_list[a]['department_name'];
			}
		}
		
		var designation = $(element).text();

		if(designation == 'CORE PARTNER')
		{
			$('#department_title').text(designation);
			$('#designation_title').text('');

			var woelly = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/woelly.png');?>" width=100px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>WOELLY WILLIAM</p><p><strong>Email : </strong>woellywilliam@aaa-global.com</p><p><strong>Phone : </strong>+6581518810</p></td></tr>';

			$(".position_table").append(woelly);


			var looi = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/looi.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>LOOI YONG KEAN</p><p><strong>Email : </strong>looi@acumenbizcorp.com.sg</p><p><strong>Phone : </strong>+6596637546</p></td></tr>';

			$(".position_table").append(looi);


			var george = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/george.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>GEORGE YEO</p><p><strong>Email : </strong>george@aaa-global.com</p><p><strong>Phone : </strong>+6590052645</p></td></tr>';

			$(".position_table").append(george);


			var ray = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/ray.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>RAY KONG</p><p><strong>Email : </strong>raykong@acumenbizcorp.com.sg</p><p><strong>Phone : </strong>+6581866239</p></td></tr>';

			$(".position_table").append(ray);


			var thomas = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/thomas.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>THOMAS YEAP</p><p><strong>Email : </strong>thomasyeap@acumenbizcorp.com.sg</p><p><strong>Phone : </strong>+6582687192</p></td></tr>';

			$(".position_table").append(thomas);
		}
		else if(designation == 'PARTNER')
		{
			$('#department_title').text(department + ' DEPARTMENT');
			$('#designation_title').text(designation);

			if(department == 'AUDIT' || department == 'I.T.' || department == 'CONSULTANCY')
			{
				var woelly = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/woelly.png');?>" width=100px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>WOELLY WILLIAM</p><p><strong>Email : </strong>woellywilliam@aaa-global.com</p><p><strong>Phone : </strong>+6581518810</p><p><strong>Office : </strong>SBF (SINGAPORE)</p></td></tr>';

				$(".position_table").append(woelly);
			}


			if(department == 'TAX' || department == 'ACCOUNT' || department == 'SECRETARY')
			{
				var looi = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/looi.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>LOOI YONG KEAN</p><p><strong>Email : </strong>looi@acumenbizcorp.com.sg</p><p><strong>Phone : </strong>+6596637546</p><p><strong>Office : </strong>NOVELTY (SINGAPORE)</p></td></tr>';

				$(".position_table").append(looi);
			}


			// if(department == 'SECRETARY')
			// {
			// 	var george = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/george.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>George YEO</p><p><strong>Email : </strong>george@aaa-global.com</p><p><strong>Phone : </strong>+6590052645</p></td></tr>';

			// 	$(".position_table").append(george);
			// }


			if(department == 'TAX' || department == 'ACCOUNT' || department == 'SECRETARY' || department == 'CONSULTANCY')
			{
				var ray = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/ray.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>RAY KONG</p><p><strong>Email : </strong>raykong@acumenbizcorp.com.sg</p><p><strong>Phone : </strong>+6581866239</p><p><strong>Office : </strong>UOA (MALAYSIA)</p></td></tr>';

				$(".position_table").append(ray);
			}

			$.ajax({
				'async':false,
		       	type: "POST",
		       	url: "<?php echo site_url('organigram/get_position_staff'); ?>",
		       	data: {'department':depart_id,'designation':designation},
		       	success: function(data)
		       	{
		       		if(data != '[]')
		       		{
		       			var result = JSON.parse(data);

		       			for(var b = 0; b < result.length; b++)
						{	
							if(result[b]['pic'] != "")
							{
								image = '<img src="'+result[b]['pic']+'" width=100px style="margin:5px" height=100px alt="" />';
							}
							else
							{
								image = '<img src="<?php echo base_url('assets/logo/no_image.png');?>" width=100px style="margin:5px" height=100px alt="" />';
							}

							var personal_details = '<tr><td style="width:30%;text-align: center;">'+image+'</td><td><p><strong>Name : </strong>'+result[b]['name']+'</p><p><strong>Email : </strong>'+result[b]['email']+'</p><p><strong>Phone : </strong>'+result[b]['telephone']+'</p><p><strong>Office : </strong>'+result[b]['office_name']+' ('+result[b]['office_country']+')</p></td></tr>';
							$(".position_table").append(personal_details);
						}
		       		}
		       	}
		   	});

		}
		else if(designation == 'PRINCIPAL')
		{
			$('#department_title').text(department + ' DEPARTMENT');
			$('#designation_title').text(designation);

			if(department == 'AUDIT')
			{
				var ray = '<tr><td style="width:30%;text-align: center;"><img src="<?php echo base_url('assets/core_partner/ray.jpg');?>" width=125px style="margin:5px" height=100px alt="" /></td><td><p><strong>Name : </strong>RAY KONG</p><p><strong>Email : </strong>raykong@acumenbizcorp.com.sg</p><p><strong>Phone : </strong>+6581866239</p><p><strong>Office : </strong>UOA (MALAYSIA)</p></td></tr>';

				$(".position_table").append(ray);
			}

			$.ajax({
				'async':false,
		       	type: "POST",
		       	url: "<?php echo site_url('organigram/get_position_staff'); ?>",
		       	data: {'department':depart_id,'designation':designation},
		       	success: function(data)
		       	{
		       		if(data != '[]')
		       		{
		       			var result = JSON.parse(data);

		       			for(var b = 0; b < result.length; b++)
						{	
							if(result[b]['pic'] != "")
							{
								image = '<img src="'+result[b]['pic']+'" width=100px style="margin:5px" height=100px alt="" />';
							}
							else
							{
								image = '<img src="<?php echo base_url('assets/logo/no_image.png');?>" width=100px style="margin:5px" height=100px alt="" />';
							}

							var personal_details = '<tr><td style="width:30%;text-align: center;">'+image+'</td><td><p><strong>Name : </strong>'+result[b]['name']+'</p><p><strong>Email : </strong>'+result[b]['email']+'</p><p><strong>Phone : </strong>'+result[b]['telephone']+'</p><p><strong>Office : </strong>'+result[b]['office_name']+' ('+result[b]['office_country']+')</p></td></tr>';
							$(".position_table").append(personal_details);
						}
		       		}
		       		else
		       		{
		       			if(department != 'AUDIT')
						{
			       			var personal_details = '<tr><td style="width:30%;text-align: center;">'+image+'</td><td><p><strong>Name : </strong>N/A</p><p><strong>Email : </strong>N/A</p><p><strong>Phone : </strong>N/A</p></td></tr>';
								$(".position_table").append(personal_details);
						}
		       		}
		       	}
		   	});
		}
		else
		{
			$('#department_title').text(department + ' DEPARTMENT');
			$('#designation_title').text(designation);

			$.ajax({
				'async':false,
		       	type: "POST",
		       	url: "<?php echo site_url('organigram/get_position_staff'); ?>",
		       	data: {'department':depart_id,'designation':designation},
		       	success: function(data)
		       	{
		       		if(data != '[]')
		       		{
		       			var result = JSON.parse(data);

		       			for(var b = 0; b < result.length; b++)
						{	
							if(result[b]['pic'] != "")
							{
								image = '<img src="'+result[b]['pic']+'" width=100px style="margin:5px" height=100px alt="" />';
							}
							else
							{
								image = '<img src="<?php echo base_url('assets/logo/no_image.png');?>" width=100px style="margin:5px" height=100px alt="" />';
							}

							var personal_details = '<tr><td style="width:30%;text-align: center;">'+image+'</td><td><p><strong>Name : </strong>'+result[b]['name']+'</p><p><strong>Email : </strong>'+result[b]['email']+'</p><p><strong>Phone : </strong>'+result[b]['telephone']+'</p><p><strong>Office : </strong>'+result[b]['office_name']+' ('+result[b]['office_country']+')</p></td></tr>';
							$(".position_table").append(personal_details);
						}
		       		}
		       		else
		       		{
		       			var personal_details = '<tr><td style="width:30%;text-align: center;">'+image+'</td><td><p><strong>Name : </strong>N/A</p><p><strong>Email : </strong>N/A</p><p><strong>Phone : </strong>N/A</p></td></tr>';
							$(".position_table").append(personal_details);
		       		}
		       	}
		   	});
		}

		$("#org_chart_detail").modal("show");
	}

</script>

<style type="text/css">

.depart:hover
{
    color: white;
    cursor: default;
}

.position_table {
  border-collapse: collapse;
}

.position_table ,td {
  border: 1px solid black;
  padding: 10px
}

.depart {
	color: white;
    border-radius: 0px;
    border-style: none;
}

</style>