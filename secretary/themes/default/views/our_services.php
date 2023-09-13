<div class="header_between_all_section">
	<!-- <div style="height: 300px;">
		<select id="acc-selector" class="acc-selector"></select>
	</div> -->

	<section class="panel">
        <div class="panel-body">
            <div class="col-md-12">
				<div id="w2-ourService" class="tab-pane">
			        <table id="our_service_datatable" style="width:100%" class="table table-bordered table-striped mb-none">
			            <thead>
			                <tr>
			                    <th style="text-align: center;"><span id="our_service_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Our Service" style="font-size:14px;"><i class="fas fa-plus" id="addRow"></i></span></th>
			                    <th>Service Type</th>
			                    <th>Service Name</th>
			                    <th>Amount</th>
			                    <th>QuickBooks</th>
			                    <?php if ($Admin && !$Individual) {?>
			                    	<th>Approved</th>
								<?php } ?>
			                </tr>
			            </thead>
			            <tfoot>
			                <td style="text-align: center;"><i class="fas fa-search"></i></td>
			                <td style="padding: 3px;">Service Type</td>
			                <td style="padding: 3px;">Service Name</td>
			                <td style="padding: 3px;">Amount</td>
			                <td style="padding: 3px;">QuickBooks</td>
			                <?php if ($Admin && !$Individual) {?>
			                	<td style="padding: 3px;">Approved</td>
			                <?php } ?>
			            </tfoot>
			        </table>
			    </div>
			</div>
		</div>
	</section>
</div>
<div id="modal_import_services_to_qb" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Import to Quickbook Online</h2>
			</header>
			<form id="form_import_services_to_qb">
				<div class="panel-body">
					<div style="height: 300px;">
						<h5>Please choose one Income Account before import this data to Quickbook Online.</h5>
						<div class="div_qb_accSelector"><!-- <select id="acc-selector" class="acc-selector"></select> --></div>
					</div>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" name="saveImportServicesToQB" id="saveImportServicesToQB">Import</button>
				<input type="button" class="btn btn-default" data-dismiss="modal" name="cancelImportServicesToQB" value="Cancel">
			</div>
		</div>
	</div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	var template = <?php echo json_encode($template);?>;
	var user_admin_code_id = <?php echo json_encode($user_admin_code_id);?>;
	var is_admin = <?php echo json_encode($Admin);?>;
	var is_individual = <?php echo json_encode($Individual);?>;
	var qb_company_id = <?php echo json_encode($qb_company_id);?>;
	
	$("#header_our_firm").removeClass("header_disabled");
	$("#header_our_services").addClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");
</script>
<script src="themes/default/assets/js/our_service.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<style>
    tfoot {
        display: table-header-group;
    }

    #our_service_datatable_filter {
        display: none;
    }

    .sorting_1 {
        text-align: center;
    }
</style>