<script src="<?= base_url() ?>application/js/fileinput.js" type="text/javascript"></script>
<!-- DATEPICKER -->
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />


<div class="professional_detail panel">
    <div class="card-header panel-heading title_bg">
        <span class="mb-0">
            <?php 
                $displayNum = $count+1;

                echo '<a class="btn btn-link interview_title" data-toggle="collapse" data-target="#collapse_pro'.$displayNum.'" aria-expanded="true" aria-controls="collapseOne">Professional Membership '.$displayNum.'</a>';
            ?>
        </span>
        <div class="pull-right" style="padding: 1.5%;">
            <span class="glyphicon glyphicon-trash deleteIcon" onclick="cancel_professional(this, <?=isset($content['id'])?$content['id']:''?>)"></span>
        </div>
    </div>

    <input type="hidden" name="pro_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">

    <?php echo '<div id="collapse_pro'.$displayNum.'" class="pro_collapse show">' ?>
        <div class="card-body panel-body">
            <div style="padding: 5%;">

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 30%;float:left;margin-right: 20px;">
                            <label>Date :</label>
                        </div>
                        <div style="width: 60%;float:left;margin-bottom:5px;">
                            <div class="input-daterange input-group datepicker">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <input type="text" class="form-control" id="pro_from[<?=$count?>]" name="pro_from[<?=$count?>]" value="<?=(isset($content['pro_from'])?$content['pro_from']:'' !=null)?date('d F Y', strtotime($content['pro_from'])):''?>" placeholder="From">
                                <span class="input-group-addon">to</span>
                                <input type="text" class="form-control" id="pro_to[<?=$count?>]" name="pro_to[<?=$count?>]" value="<?=(isset($content['pro_to'])?$content['pro_to']:'' !=null)?date('d F Y', strtotime($content['pro_to'])):''?>" placeholder="To">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 30%;float:left;margin-right: 20px;">
                            <label>Qualifications / Awards obtained :</label>
                        </div>
                        <div style="width: 60%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="qualifications_awards[<?=$count?>]" name="qualifications_awards[<?=$count?>]" style="width: 500%;" value="<?=isset($content['qualifications_awards'])?$content['qualifications_awards']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 30%;float:left;margin-right: 20px;">
                            <label>Awarding Institution :</label>
                        </div>
                        <div style="width: 60%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="institution[<?=$count?>]" name="institution[<?=$count?>]" style="width: 500%;" value="<?=isset($content['institution'])?$content['institution']:''?>"/>
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

    $('.pro_collapse').collapse();

    $('.datepicker').datepicker({format: 'dd MM yyyy',});

</script>