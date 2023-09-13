<!-- DATEPICKER -->
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<!-- SELECT2 -->
<link href="<?= base_url() ?>node_modules/select2/dist/css/select2.min.css" rel="stylesheet" />
<script src="<?= base_url() ?>node_modules/select2/dist/js/select2.min.js"></script>

<div class="experience_detail panel">
    <div class="card-header panel-heading title_bg">
        <span class="mb-0">
            <?php 
                $displayNum = $count+1;

                echo '<a class="btn btn-link interview_title" data-toggle="collapse" data-target="#collapse_exp'.$displayNum.'" aria-expanded="true" aria-controls="collapseOne">Experience '.$displayNum.'</a>'
            ?>
            <div class="pull-right" style="padding: 1.5%;">
                <span class="glyphicon glyphicon-trash deleteIcon" onclick="cancel_experience(this, <?=isset($content['id'])?$content['id']:''?>)"></span>
            </div>
        </span>
    </div>

    <input type="hidden" name="exp_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">

    <?php echo '<div id="collapse_exp'.$displayNum.'" class="exp_collapse show">' ?>
        <div class="card-body panel-body">
            <div style="padding: 5%;">

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Date :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-daterange input-group datepicker">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <input type="text" class="form-control" id="exp_from[<?=$count?>]" name="exp_from[<?=$count?>]" value="<?=(isset($content['exp_from'])?$content['exp_from']:'' !=null)?date('d F Y', strtotime($content['exp_from'])):''?>" placeholder="From">
                                <span class="input-group-addon">to</span>
                                <input type="text" class="form-control" id="exp_to[<?=$count?>]" name="exp_to[<?=$count?>]" value="<?=(isset($content['exp_to'])?$content['exp_to']:'' !=null)?date('d F Y', strtotime($content['exp_to'])):''?>" placeholder="To">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Company Name :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="exp_company[<?=$count?>]" name="exp_company[<?=$count?>]" value="<?=isset($content['exp_company'])?$content['exp_company']:''?>" style="width: 500%;"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Position Held :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="exp_position[<?=$count?>]" name="exp_position[<?=$count?>]" value="<?=isset($content['exp_position'])?$content['exp_position']:''?>" style="width: 500%;"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Nature of Duties :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <textarea class="form-control" rows="5" id="exp_duties[<?=$count?>]" name="exp_duties[<?=$count?>]" style="width:500%" "form-control"><?php echo isset($content['exp_duties'])?$content['exp_duties']:''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Reason for Leaving :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <textarea class="form-control" rows="5" id="exp_leaving_reason[<?=$count?>]" name="exp_leaving_reason[<?=$count?>]" style="width:500%" "form-control"><?php echo isset($content['exp_leaving_reason'])?$content['exp_leaving_reason']:''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
               <!--  <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Position Title :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="exp_position[<?=$count?>]" name="exp_position[<?=$count?>]" value="<?=isset($content['position'])?$content['position']:''?>" style="width: 500%;"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Company Name :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="exp_company[<?=$count?>]" name="exp_company[<?=$count?>]" value="<?=isset($content['company_name'])?$content['company_name']:''?>" style="width: 500%;" style="width: 500%;"/>
                            </div>

                            <div id="form_name"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Joined Duration :</label>
                        </div>
                        <div style="float:left;margin-bottom:5px;" class="inline_block">
                            <div>
                                <?php
                                    echo form_dropdown('exp_joined_month['.$count.']', $months, isset($content['join_month'])?$content['join_month']:'', 'style="width:100%;"');
                                ?>
                            </div>
                            <div>
                                <?php echo '<select name="exp_joined_year['.$count.']" class="yearpicker" style="width: 100%"></select>' ?>
                                <script>
                                    $('.yearpicker').append('<option>Year</option>');

                                    for (i = new Date().getFullYear() + 5; i > 1957; i--)
                                    {
                                        $('.yearpicker').append($('<option />').val(i).html(i));
                                    }
                                    $("select[name='exp_joined_year[<?= $count ?>]']").val(<?=isset($content['join_year'])?$content['join_year']:''?>);
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Specialization :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 100%;">
                                <input type="text" name="exp_specialization[<?= $count ?>]" class="form-control" value="<?=isset($content['specialization'])?$content['specialization']:''?>">
                            </div>

                            <div id="form_name"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Role :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 100%;">
                                <input type="text" name="exp_role[<?= $count ?>]" class="form-control" value="<?=isset($content['role'])?$content['role']:''?>">
                            </div>

                            <div id="form_name"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Country :</label>
                        </div>
                        <div>
                            <?php
                                echo form_dropdown('exp_country['.$count.']', $country, isset($content['country'])?$content['country']:'', 'class="exp_country-select style="width:100%;"');
                            ?>
                            <script>
                                $(".exp_country-select").chosen({no_results_text: "Oops, nothing found!"});
                            </script>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Industry :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="exp_industry[<?=$count?>]" name="exp_industry[<?=$count?>]" value="<?=isset($content['industry'])?$content['industry']:''?>" style="width: 500%;"/>
                            </div>

                            <div id="form_name"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Position Level :</label>
                        </div>
                        <div>
                            <?php
                                echo form_dropdown('exp_position_level['.$count.']', $position_level, isset($content['position_level'])?$content['position_level']:'', 'class="exp_position_level-select style="width:100%;"');
                            ?>
                            <script>
                                $(".exp_position_level-select").chosen({no_results_text: "Oops, nothing found!"});
                            </script>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Monthly Salary :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div style="float:left;margin-bottom:5px;" class="inline_block">
                                <div>
                                    <?php
                                        echo form_dropdown('exp_currency['.$count.']', $currency, isset($content['monthly_salary_currency'])?$content['monthly_salary_currency']:'');
                                    ?>
                                </div>
                                <div>
                                    <input type="text" name="exp_salary[<?=$count?>]" class="form-control" value="<?=isset($content['monthly_salary_amount'])?$content['monthly_salary_amount']:''?>" onchange="numberWithCommas(this)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Experience Description :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <textarea class="form-control" rows="5" id="exp_description[<?=$count?>]" name="exp_description[<?=$count?>]" style="width:100%"><?=isset($content['experience_description'])?$content['experience_description']:'' ?></textarea>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>


<!-- <div class="row experience_detail">
    <div class="col-md-12" style="border-style: solid; border-width: thin; padding:7%">
        <div class="col-md-12">
        	
            
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Upload Certificate Awards :</label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 100%;" >
                            <div class="file-loading">
                                <?php echo '<input type="file" id="exp_cert['.$count.']" class="file" name="exp_cert['.$count.']"data-min-file-count="0" accept="image/*">'?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <a class="btn btn_purple">Save</a>
                <a class="btn btn_cancel" onclick="cancel_experience(this)">Cancel</a>
            </div>
        </div>
    </div>
</div> -->

<!-- Custom CSS here -->
<style>
    .inline_block div {
        display: inline-block;
    }
</style>

<script>
    $('.exp_collapse').collapse();

    // Thousand Separator
    function numberWithCommas(x)
    {
        var val = $(x).val();
        val = val.replace(',','');
        console.log(val);
        val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $(x).val(val);
    }

    $('.datepicker').datepicker({format: 'dd MM yyyy',});
</script>
