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
							<th style="width: 150px;">Subject</th>
							<th style="width: 150px;">Message</th>
							<th style="width: 60px;">Attachment</th>
							<th>From</th>
							<th>To</th>
							<th>Status</th>
							<th>Created At</th>
						</tr>
					</thead>
					<tbody id="email_history_body">
						<?php
						$i = 1;
						//echo json_encode($email_history);
							foreach($email_history as $a)
							{
								 //print_r($a);
								echo '<tr>
										<td>'.$i.'</td>
										<td>'.$a->subject.'</td>
										<td>'.
										ucwords(substr(strip_tags($a->message),0,11))
										.'<span id="p'.$i.'" style="display:none;">'.substr(strip_tags($a->message),11,strlen(strip_tags($a->message))).'</span>'.
										((strlen(strip_tags($a->message)) > 11)?'<a class="tonggle_readmore" data-id=p'.$i.'>...</a>': '')
										.'
										</td>
										<td>'.$a->attachment.'</td>
										<td>'.$a->email_from.'</td>
										<td>'.$a->email_to.'</td>
										<td>'.$a->status.'</td>
										<td>'.$a->created_at.'</td>
								
									</tr>';
									// <span href="" class="fa fa-pencil edit_share pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'"></span>
									// <td><span href="" style="height:45px;font-weight:bold;" class="edit_currency  pointer amber" data-id="'.$a->id.'" data-info="'.$a->currency.'">'.$a->currency.'</span></td>
									// 	<td>
									// 		<a href="master/remove_currency/'.$a->id.'" class="fa fa-trash pointer amber"></span>
									// 	</td>	
								$i++;
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
	$(document).on('click','.tonggle_readmore',function (){
		$id = $(this).data('id');
		$("#"+$id).toggle();
	});
</script>
