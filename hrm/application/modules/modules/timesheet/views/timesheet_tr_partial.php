<tr>
	<?php echo '<td><a href="'. base_url() .'timesheet/edit/'. $timesheet['id'] .'">'. $timesheet['timesheet_no'] .'</a></td>'; ?>
	<td><?php echo $timesheet['employee_name'] ?></td>
	<td><?php echo $timesheet['status_id'] ?></td>
</tr>