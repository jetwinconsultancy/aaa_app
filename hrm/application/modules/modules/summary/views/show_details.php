<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<style>
	.test{
	    white-space: nowrap;
  		overflow: hidden;
  		text-overflow: ellipsis;
	}
</style>

<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>

	<?php if($request == 'JUC') { $this->session->set_userdata("tab_active", "no_job_completed_updated"); ?>

		<div class="col-lg-100 col-xs-100 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
			
					<h2 class="panel-title">Status Updated</h2>
				</header>
				<div class="panel-body" style="height:288px;overflow:auto;">	
					<div class="table-responsive">
						<table class="table table-striped mb-none">
							<thead>
								<tr>
									<th>ID</th>
									<th>Client</th>
									<th>From Status</th>
									<th>To Status</th>
									<th>Update On</th>
								</tr>
							</thead>
							<tbody >
								<?php
									foreach($status_updated_list as $status_updated){
										echo '<tr>';
										echo '<td>'.$status_updated['id'].'</td>';
										echo '<td>'.$status_updated['client'].'</td>';
										echo '<td>'.$status_updated['from_status'].'</td>';
										echo '<td>'.$status_updated['to_status'].'</td>';
										echo '<td>'.date('d F Y', strtotime($status_updated['date'])).'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>

		<div class="col-lg-100 col-xs-100 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
			
					<h2 class="panel-title">Remark Updated</h2>
				</header>
				<div class="panel-body" style="height:288px;overflow:auto;">	
					<div class="table-responsive">
						<table class="table table-striped mb-none">
							<thead>
								<tr>
									<th>ID</th>
									<th>Client</th>
									<th>From Remark</th>
									<th>To Remark</th>
									<th>Updated On</th>
								</tr>
							</thead>
							<tbody >
								<?php
									foreach($remark_updated_list as $remark_updated){
										echo '<tr>';
										echo '<td>'.$remark_updated['id'].'</td>';
										echo '<td>'.$remark_updated['client'].'</td>';
										echo '<td>'.$remark_updated['from_remark'].'</td>';
										echo '<td>'.$remark_updated['to_remark'].'</td>';
										echo '<td>'.date('d F Y', strtotime($remark_updated['date'])).'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>

		<div class="col-lg-100 col-xs-100 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
			
					<h2 class="panel-title">Completed</h2>
				</header>
				<div class="panel-body" style="height:288px;overflow:auto;">	
					<div class="table-responsive">
						<table class="table table-striped mb-none">
							<thead>
								<tr>
									<th>ID</th>
									<th>Client</th>
									<th>FYE</th>
									<th>Job Type</th>
									<th>Completed On</th>
								</tr>
							</thead>
							<tbody >
								<?php
									foreach($completed_list as $completed){
										echo '<tr>';
										echo '<td>'.$completed['id'].'</td>';
										echo '<td>'.$completed['client'].'</td>';
										echo '<td>'.$completed['fye'].'</td>';
										echo '<td>'.$completed['job'].'</td>';
										echo '<td>'.date('d F Y', strtotime($completed['date'])).'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	<?php } ?>

	<?php if($request == 'JR') { $this->session->set_userdata("tab_active", "no_job_remain"); ?>
		<div class="panel-body">
			<div class="col-md-12">
				<table class="table table-bordered table-striped mb-none datatable-job_remain_details" id="" style="width:100%">
					<thead>
						<tr style="background-color:white;">
							<th class="text-left">ID</th>
							<th class="text-left">Client</th>
							<th class="text-left">FYE</th>
							<th class="text-left">Job Type</th>
							<th class="text-left">Status</th>
							<th class="text-left" style="width: 30%">Remark</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach($emp_job_remain_list as $emp_job_remain)
				  			{
				  				echo '<tr>';
				  				echo '<td>'.$emp_job_remain['id'].'</td>';
								echo '<td>'.$emp_job_remain['client'].'</td>';
								echo '<td>'.$emp_job_remain['fye'].'</td>';
								echo '<td>'.$emp_job_remain['job'].'</td>';
								echo '<td>'.$emp_job_remain['status'].'</td>';
								echo '<td>'.$emp_job_remain['remark'].'</td>';
				  				echo '</tr>';
				  			}
						?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>

	<div class="form-group row">
		<div class="col-sm-12">
	    	<?php 
	    		echo '<a href="'.base_url().'summary" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Back</a>';
	    	?>
	    </div>
    </div>

</section>

<script>
var request = <?php echo json_encode(isset($request)?$request:"") ?>;

console.log(request);

$(document).ready(function ()
{
	if(request == 'JR'){
		$(".datatable-job_remain_details").DataTable({
	    "order": [],
	  });
	}
});

// Panels
(function( $ ) {

	$(function() {
		$('.panel')
			.on( 'panel:toggle', function() {
				var $this,
					direction;

				$this = $(this);
				direction = $this.hasClass( 'panel-collapsed' ) ? 'Down' : 'Up';

				$this.find('.panel-body, .panel-footer')[ 'slide' + direction ]( 200, function() {
					$this[ (direction === 'Up' ? 'add' : 'remove') + 'Class' ]( 'panel-collapsed' )
				});
			})
			.on( 'panel:dismiss', function() {
				var $this = $(this);

				if ( !!( $this.parent('div').attr('class') || '' ).match( /col-(xs|sm|md|lg)/g ) && $this.siblings().length === 0 ) {
					$row = $this.closest('.row');
					$this.parent('div').remove();
					if ( $row.children().length === 0 ) {
						$row.remove();
					}
				} else {
					$this.remove();
				}
			})
			.on( 'click', '[data-panel-toggle]', function( e ) {
				e.preventDefault();
				$(this).closest('.panel').trigger( 'panel:toggle' );
			})
			.on( 'click', '[data-panel-dismiss]', function( e ) {
				e.preventDefault();
				$(this).closest('.panel').trigger( 'panel:dismiss' );
			})
			/* Deprecated */
			.on( 'click', '.panel-actions a.fa-caret-up', function( e ) {
				e.preventDefault();
				var $this = $( this );

				$this
					.removeClass( 'fa-caret-up' )
					.addClass( 'fa-caret-down' );

				$this.closest('.panel').trigger( 'panel:toggle' );
			})
			.on( 'click', '.panel-actions a.fa-caret-down', function( e ) {
				e.preventDefault();
				var $this = $( this );

				$this
					.removeClass( 'fa-caret-down' )
					.addClass( 'fa-caret-up' );

				$this.closest('.panel').trigger( 'panel:toggle' );
			})
			.on( 'click', '.panel-actions a.fa-times', function( e ) {
				e.preventDefault();
				var $this = $( this );

				$this.closest('.panel').trigger( 'panel:dismiss' );
			});
	});

})( jQuery );
</script>