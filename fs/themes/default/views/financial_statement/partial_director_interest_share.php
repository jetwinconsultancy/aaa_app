<div id="fs_director_statement_div"><form id="form_fs_director_statement" method="POST">

    <div class="form-group">
        <label class="col-xs-4">Directors' interest: </label>
        <div class="col-xs-8">
            <div class="input-group" style="width: 200px;" >
                <input type="checkbox" name="hidden_director_interest_checkbox" <?=$fs_company_info[0]['has_director_interest']?'checked':'';?> />
                <input type="hidden" name="director_interest_checkbox" value="<?=$fs_company_info[0]['has_director_interest']?>"/>
            </div>
        </div>
    </div>

    <div class="table-responsive director_interest_sec" <?=$fs_company_info[0]['has_director_interest'] == 1?'':'style="display:none;"';?>>
        <table class="table" style="border-collapse: collapse; width: 100%;" border="0">
            <tbody>
                <tr>
                    <td style="width: 5%"></td>
                    <td style="width: 5%"></td>
                    <td style="width: 25%;">&nbsp;</td>
                    <td style="width: 15%;">&nbsp;</td>
                    <td style="width: 25%; text-align: center;" colspan="2"><span style="text-decoration: underline;"><strong>Direct interest</strong></span></td>
                    <td style="width: 25%; text-align: center;" colspan="2"><span style="text-decoration: underline;"><strong>Deemed interest</strong></span></td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 5%"></td>
                    <td style="width: 25%;"><strong>Name of directors</strong></td>
                    <td style="width: 15%; text-align: center;"><span style="text-decoration: underline;"><strong>Country</strong></span></td>
                    <td style="width: 12.5%; text-align: center;"><span style="text-decoration: underline;"><strong>Beginning of financial year</strong></span></td>
                    <td style="width: 12.5%; text-align: center;"><span style="text-decoration: underline;"><strong>End of financial year</strong></span></td>
                    <td style="width: 12.5%; text-align: center;"><span style="text-decoration: underline;"><strong>Beginning of financial year</strong></span></td>
                    <td style="width: 12.5%; text-align: center;"><span style="text-decoration: underline;"><strong>End of financial year</strong></span></td>
                </tr>

                <?php
                    // echo '<tr><td colspan="8">&nbsp;</td></tr>';

                    foreach ($director_interest_list as $key => $value) 
                    {
                        echo 
                            '<tr>' .
                                '<td style="width: 5%;"></td>' .
                                '<td style="width: 5%;"></td>' .
                                '<td style="width: 25%;">' . $value['director_name'] . '</td>' .
                                '<td style="width: 15%;"></td>' .
                                '<td style="width: 12.5%; text-align: right;">' . $value['beg_direct_interest'] . '</td>' .
                                '<td style="width: 12.5%; text-align: right;">' . $value['end_direct_interest'] . '</td>' .
                                '<td style="width: 12.5%; text-align: right;">' . $value['beg_deemed_interest'] . '</td>' .
                                '<td style="width: 12.5%; text-align: right;">' . $value['end_deemed_interest'] . '</td>' .
                            '</tr>';
                    }

                    echo '<tr><td colspan="8">&nbsp;</td></tr>';
                ?>

                <!-- <tr>
                    <td colspan="8">&nbsp;</td>
                </tr> -->
                <tr class="bg_red_darken" id="ultimate_input">
                    <td colspan="8"><a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:white; font-weight:bold; cursor: pointer;" onclick="add_row(1, '')"><i class="fa fa-plus-circle" style="font-size:16px;"></i></a><strong style="color:white;">     Ultimate Holding Company</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>
                <tr class="bg_red_darken" id="intermediate_input">
                    <td colspan="8"><a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:white; font-weight:bold; cursor: pointer;" onclick="add_row(2, '')"><i class="fa fa-plus-circle" style="font-size:16px;"></i></a><strong style="color:white;">     Intermediate Holding Company</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>
                <tr class="bg_red_darken" id="immediate_input">
                    <td colspan="8"><a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:white; font-weight:bold; cursor: pointer;" onclick="add_row(3, '')"><i class="fa fa-plus-circle" style="font-size:16px;"></i></a><strong style="color:white;">     Immediate Holding Company</strong>
                    </td>
                </tr>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>
                <tr class="bg_red_darken" id="corporate_input">
                    <td colspan="8"><a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:white; font-weight:bold; cursor: pointer;" onclick="add_row(4, '')"><i class="fa fa-plus-circle" style="font-size:16px;"></i></a><strong style="color:white;">     Corporate Shareholders</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style="height:40px;"></td>
                </tr>

                <tr class="bg_red_darken" id="others_input">
                    <td colspan="8"><a class="add_company" data-toggle="tooltip" data-trigger="hover" style="color:white; font-weight:bold; cursor: pointer;" onclick="add_row(5, '')"><i class="fa fa-plus-circle" style="font-size:16px;"></i></a><strong style="color:white;">     Others</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- <div class="form-group">
        <div class="col-sm-12">
            <input type="button" class="btn btn-primary submit_fs_director_statement" id="submit_fs_director_statement" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
        </div>
    </div> -->
</form>

<div class="loading" id="loadingDirectorInterestShare" style="display: none;">Loading&#8230;</div>

<script type="text/javascript">
    var fs_dir_state_company = <?php echo json_encode($fs_dir_state_company); ?>;
    var fs_dir_statement_director = <?php echo json_encode($fs_dir_statement_director); ?>;
    var country_list = <?php echo json_encode($country_list); ?>;
</script>

<script src="themes/default/assets/js/financial_statement/partial_director_interest_share.js" charset="utf-8"></script>
</div>

