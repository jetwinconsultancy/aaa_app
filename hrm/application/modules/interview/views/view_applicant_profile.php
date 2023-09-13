<section class="panel" style="margin-top: 30px;">
	<!-- <header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_purple" href="interview/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Create Interview</a>
		</div>
		<h2></h2>
	</header> -->
	<div class="panel-body">
		<div id="applicant_profile" class="col-md-12"></div>
		
	</div>
</section>

<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var applicant_id = <?php echo $applicant_id; ?>;

	get_applicant_profile(applicant_id);

	function get_applicant_profile(applicant_id){
		$.post(base_url + "applicant/applicant_profile/" + applicant_id, function(data, status){
			$('#applicant_profile').append(data);
        });
	}

</script>
