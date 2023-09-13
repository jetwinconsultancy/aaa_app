<div class="header_between_all_section">
<section class="panel">
	<header class="panel-heading">
		
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="w2-currency" class="tab-pane">
				<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-transaction">
					<thead>
						<tr>
							<th>No</th>
							<th>Username</th>
							<th>Modules</th>
							<th>Events</th>
							<th>Actions</th>
							<th>Created At</th>
						</tr>
					</thead>
					<tbody id="audit_trail_body">
						<?php
							$i = 1;
							//echo json_encode($email_history);
							if($audit_trail)
							{
								foreach($audit_trail as $a)
								{
									//print_r($a);
									echo '<tr>
											<td>'.$i.'</td>
											<td>'.$a->first_name . ' '.$a->last_name.'</td>
											<td>'.$a->modules.'</td>
											<td>'.$a->events.'</td>
											<td>'.$a->actions.'</td>
											<td>'.$a->created_at.'</td>
										</tr>';
									$i++;
								}
							}
						?>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
</div>	
	
<script>
	$("#header_audit_trail").addClass("header_disabled");
</script>
