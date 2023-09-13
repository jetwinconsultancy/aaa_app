<script src="<?= base_url() ?>application/js/fileinput.js" type="text/javascript"></script>
<!-- DATEPICKER -->
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />


<div class="family_detail panel">
    <div class="card-header panel-heading title_bg">
        <span class="mb-0">
            <?php 
                $displayNum = $count+1;

                echo '<a class="btn btn-link interview_title" data-toggle="collapse" data-target="#collapse_family'.$displayNum.'" aria-expanded="true" aria-controls="collapseOne">Family Member '.$displayNum.'</a>';
            ?>
        </span>
        <div class="pull-right" style="padding: 1.5%;">
            <span class="glyphicon glyphicon-trash deleteIcon" onclick="cancel_family(this, <?=isset($content['id'])?$content['id']:''?>)"></span>
        </div>
    </div>

    <input type="hidden" name="family_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">

    <?php echo '<div id="collapse_family'.$displayNum.'" class="family_collapse show">' ?>
        <div class="card-body panel-body">
            <div style="padding: 5%;">

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 30%;float:left;margin-right: 20px;">
                            <label>Name :</label>
                        </div>
                        <div style="width: 60%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="family_name[<?=$count?>]" name="family_name[<?=$count?>]" style="width: 500%;" value="<?=isset($content['family_name'])?$content['family_name']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 30%;float:left;margin-right: 20px;">
                            <label>Relationship :</label>
                        </div>
                        <div style="width: 60%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="family_relationship[<?=$count?>]" name="family_relationship[<?=$count?>]" style="width: 500%;" value="<?=isset($content['family_relationship'])?$content['family_relationship']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 30%;float:left;margin-right: 20px;">
                            <label>Age :</label>
                        </div>
                        <div style="width: 60%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                               <input type="number" class="form-control" id="family_age[<?=$count?>]" name="family_age[<?=$count?>]" value="<?=isset($content['family_age'])?$content['family_age']:''?>" style="width: 500%;"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 30%;float:left;margin-right: 20px;">
                            <label>Occupation :</label>
                        </div>
                        <div style="width: 60%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="family_occupation[<?=$count?>]" name="family_occupation[<?=$count?>]" style="width: 500%;" value="<?=isset($content['family_occupation'])?$content['family_occupation']:''?>"/>
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

    $('.family_collapse').collapse();

    $('.datepicker').datepicker({format: 'dd MM yyyy',});

</script>