<div class="header_between_all_section">
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
			                    <!-- <th>Column 4</th>
			                    <th>Column 5</th> -->
			                </tr>

			            </thead>
			            <tfoot>
			                <!-- <th></th> -->
			                <td style="text-align: center;"><i class="fas fa-search"></i></td>
			                <td style="padding: 3px;">Service Type</td>
			                <td style="padding: 3px;">Service Name</td>
			                <td style="padding: 3px;">Amount</td>
			            </tfoot>

			            
			            <!-- <tfoot>
			                <tr>
			                    <th>Column 1</th>
			                    <th>Column 2</th>
			                    <th>Column 3</th>
			                    <th>Column 4</th>
			                    <th>Column 5</th>
			                </tr>
			            </tfoot> -->
			        </table>
			    </div>
			</div>
		</div>
	</section>
</div>
<script>
	var template = <?php echo json_encode($template);?>;
	var user_admin_code_id = <?php echo json_encode($user_admin_code_id);?>;

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