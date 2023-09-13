<div class="col-lg-12 col-xs-12" style="min-height:350px;margin-top: 30px;">		
	<section class="panel">
		<div class="panel-body">
			<div class="col-md-12" id="div_admin_page" style="margin-top:10px;">
				<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-admin-page" >
					<thead>
						<tr>
							<th style="text-align: center;width: 300px">Main User</th>
							<th style="text-align: center;width: 150px">No of Users</th>
							<th style="text-align: center;width: 300px">Type of user</th>
							<th style="text-align: center;width: 150px">Date of Registration</th>
							<th style="text-align: center;width: 200px">Date of Expiry</th>
							<th style="text-align: center;width: 150px">No of Clients</th>
							<th style="text-align: center;width: 200px">Storage</th>
							<th style="text-align: center;width: 150px">No of Firms</th>
							<th style="text-align: center;width: 200px">Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							foreach($user_info as $p)
							{
								echo '<tr>';
								echo '<td>'.$p["email"].'</td>';
								echo '<td style="text-align: center;">'.$p["no_of_user"]. ' / '. $p["total_no_of_user"] .'</td>';
								echo '<td>'.$p["type_of_user"].'</td>';
								echo '<td style="text-align: center;">'.$p["date_of_registration"].'</td>';
								echo '<td style="text-align: center;">'.date('d/m/Y',strtotime($p["date_of_expiry"])).'</td>';
								echo '<td style="text-align: center;">'.$p["no_of_client"]. ' / '. $p["total_no_of_client"].'</td>';
								/*echo '<td style="text-align: center;">'.$p["storage"]. ' / '. $p["total_storage"].'</td>';*/
								/*echo '<td style="text-align: center;">'.$p["no_of_firm"]. ' / '. $p["total_no_of_firm"].'</td>';*/
								echo '<td style="text-align: center; font-size: 20px;">&infin;</td>';
								echo '<td style="text-align: center;">'.$p["no_of_firm"]. '</td>';
								echo '<td style="text-align: right;">'.$p["amount"].'</td>';
								echo '</tr>';

								$i++;
							}
						?>
					</tbody>
				</table>
				<br/>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	    $('#datatable-admin-page').DataTable( {
	        dom: 'Bfrtip',
	        buttons: [
	            /*'copyHtml5',*/
	            'excelHtml5',
	            /*'csvHtml5',*/
	            'pdfHtml5'
	        ]
	    } );
	} );
</script>
<style>
.dt-buttons {
	position: relative;
    float: left;
    
}
.dt-button{
	color: #ffffff !important;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    background-color: #D9A200 !important;
    border-color: #D9A200 !important;
    touch-action: manipulation;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 0;
    font-weight: normal;
}
.dataTables_filter {
	float: right;
    text-align: right;
    width: 500px;
}

.dataTables_wrapper .dataTables_info {
    clear: both;
    float: left;
    padding-top: 0.755em;
}

.dataTables_wrapper .dataTables_paginate {
    /*float: right;*/
    text-align: center;
    padding-top: 0.25em;
}


</style>