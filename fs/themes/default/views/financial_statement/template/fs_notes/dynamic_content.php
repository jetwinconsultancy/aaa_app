<form id="form_gc_ntfs">
<?php
$default_template_1 = 'The financial statements of the Company have been prepared on a going concern basis notwithstanding the negative revenue reserve exceeds its share capital by approximately $1,490,024 (2017: $1,746,227) and current liabilities exceeding current assets by $1,490,024 (2017: $1,747,393). These factors indicate the existence of a material uncertainty which may cast significant doubt over the Company’s ability to continue as a going concern.

The ability of the Company to continue as a going concern is dependent on the undertaking of its holding company, to provide continuing financial support to enable the Company to meet its liabilities as and when they fall due.

If the Company were unable to continue in operational existence for the foreseeable future, the Company may be unable to discharge its liabilities in the normal course of business and adjustments may have to be made to reflect the situation that the assets may need to be realized other than in the normal course of business and at amounts which could differ significantly from the amounts at which they are currently recorded in the statement of financial position. In addition, the Company may have to reclassify non-current assets and liabilities as current assets and liabilities. No such adjustments have been made to these financial statements.';
$text_content = isset($gc_info[0]['content'])?$gc_info[0]['content']:$default_template_1;

echo '<input type="hidden" id="gc_info_id" name="gc_info_id" value="' . $gc_info[0]['id'] . '">';
echo '<textarea class="form-control" style="margin-top:1%; height: 300px;" id="gc_info_content" name="gc_info_content">' . $text_content . '</textarea>';
?>
<div class="form-group">
<div class="col-sm-12">
<input type="button" class="btn btn-primary" id="submit_gc_ntfs" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
</div>
</div>
</form>

<script>
$(document).on('click',"#submit_gc_ntfs",function(e)
{
	$.ajax({
      url: "fs_notes/save_going_concern_ntfs",
      type: "POST",
      data: $('#form_gc_ntfs').serialize() + 
      		'&fs_company_info_id=' + $("#fs_company_info_id").val(),
      dataType: 'json',
      success: function (response,data) {
		if(response['result'])
		{
			$('#gc_info_id').val(response['gc_info_id']);
			toastr.success("Data is successfully saved.", "Success (Note - GOING CONCERN)");
		}
		else
		{
			toastr.error("Data is failed saved.", "Error (Under Note - GOING CONCERN)");
		}
      }
    });
});
</script>