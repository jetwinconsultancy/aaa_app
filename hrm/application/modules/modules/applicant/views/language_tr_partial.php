
<tr class="lang_tr">
    <td style="padding-right: 100px">
        <input type="hidden" name="lang_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">
        <input class="form-control" name="lang_name[<?= $count ?>]" value="<?=isset($content['name'])?$content['name']:''?>">
    </td>
    <td>
        <select class="one_to_ten<?= $count ?>" name="lang_spoken[<?= $count ?>]" value="<?=isset($content['spoken'])?$content['spoken']:''?> "></select>
    </td>
    <td>
        <select class="one_to_ten<?= $count ?>" name="lang_written[<?= $count ?>]" value="<?=isset($content['written'])?$content['written']:''?> "></select>
    </td>
    <td>
        <select class="one_to_ten<?= $count ?>" name="lang_reading[<?= $count ?>]" value="<?=isset($content['reading'])?$content['reading']:''?> "></select>
    </td>
    <td><span class="glyphicon glyphicon-trash" onclick="cancel_lang(this, <?=isset($content['id'])?$content['id']:''?>)" style="cursor: pointer;"></span></td>
</tr>

<script>
    var $select = $(".one_to_ten<?= $count ?>");

    // $select.empty();

    for (i=1;i<11;i++){
        $select.prepend($('<option></option>').val(i).html(i));
    }

    $("select[name='lang_spoken[<?= $count ?>]']").val(<?=isset($content['spoken'])?$content['spoken']:''?>);
    $("select[name='lang_written[<?= $count ?>]']").val(<?=isset($content['written'])?$content['written']:''?>);
    $("select[name='lang_reading[<?= $count ?>]']").val(<?=isset($content['reading'])?$content['reading']:''?>);

</script>