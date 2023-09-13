<div class="referral_detail panel">
    <div class="card-header panel-heading title_bg">
        <span class="mb-0">
            <?php 
                $displayNum = $count+1;

                echo '<a class="btn btn-link interview_title" data-toggle="collapse" data-target="#collapse_ref'.$displayNum.'" aria-expanded="true" aria-controls="collapseOne">Referral '.$displayNum.'</a>'
            ?>
            <div class="pull-right" style="padding: 1.5%;">
                <span class="glyphicon glyphicon-trash deleteIcon" onclick="cancel_referral(this, <?=isset($content['id'])?$content['id']:''?>)"></span>
            </div>
        </span>
    </div>

    <input type="hidden" name="ref_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">

    <!-- <?php echo '' ?> -->
    <div id="collapse_ref<?=$displayNum?>" class="ref_collapse show">
        <div class="card-body panel-body">
            <div style="padding: 5%;">
            	<div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Name :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                            	<input type="text" class="form-control" id="ref_name[<?=$count?>]" name="ref_name[<?=$count?>]" style="width: 500%;" value="<?=isset($content['name'])?$content['name']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Phone Number :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="tel" class="form-control" id="ref_phoneno[<?=$count?>]" name="ref_phoneno[<?=$count?>]" style="width: 500%;" value="<?=isset($content['phoneno'])?$content['phoneno']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Email Address :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="email" class="form-control" id="ref_email[<?=$count?>]" name="ref_email[<?=$count?>]"style="width: 500%;" value="<?=isset($content['email'])?$content['email']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Address :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <textarea class="form-control" rows="5" id="ref_address[<?=$count?>]" name="ref_address[<?=$count?>]" style="width:500%" "form-control"><?php echo isset($content['address'])?$content['address']:''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Profession :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="ref_profession[<?=$count?>]" name="ref_profession[<?=$count?>]" style="width: 500%;" value="<?=isset($content['profession'])?$content['profession']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>No of years known :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="number" class="form-control" id="ref_years_known[<?=$count?>]" name="ref_years_known[<?=$count?>]" style="width: 500%;" value="<?=isset($content['no_of_years_known'])?$content['no_of_years_known']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS here -->
<style>
    .inline_block div {
        display: inline-block;
    }
</style>

<script>
    $('.ref_collapse').collapse();
</script>
