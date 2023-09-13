<div class="header_between_all_section">
        <section class="panel">
            <?php echo $breadcrumbs;?>
            <div class="panel-body">
                <div class="col-md-12">
                    <div id="modalLG" class="modal-block modal-block-lg" style="max-width: 100% ;margin: 0px auto;">
                        <section class="panel" style="margin-bottom: 0px;">
                            <div class="panel-body">
                                <div class="modal-wrapper">
                                    <div class="modal-text">
                                        <div class="tabs">
                                            
                                            <ul class="nav nav-tabs nav-justify" id="myTab">
                                                <li class="active our_firm_check_stat" id="li-ourFirm" data-information="ourFirm">
                                                    <a href="#w2-ourFirm" data-toggle="tab" class="text-center">
                                                        <span class="badge hidden-xs">1</span>
                                                        Our Firm
                                                    </a>
                                                </li>
                                                <li class="our_firm_check_stat" id="li-bankInfo" data-information="bankInfo" >
                                                    <a href="#w2-bankInfo" data-toggle="tab" class="text-center ">
                                                        <span class="badge hidden-xs">2</span>
                                                        Bank Info
                                                    </a>
                                                </li>
                                                <!-- <li class="our_firm_check_stat" id="li-ourService" data-information="ourService" >
                                                    <a href="#w2-ourService" data-toggle="tab" class="text-center ">
                                                        <span class="badge hidden-xs">3</span>
                                                        Our Service
                                                    </a>
                                                </li> -->
                                            </ul>
                                            <div class="tab-content">
                                                <div id="w2-ourFirm" class="tab-pane active">
                                                    <div class="box-content">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <?php echo form_open_multipart('', array('id' => 'firm_form', 'enctype' => "multipart/form-data")); ?>
                                                                <input type="hidden" class="form-control" id="firm_id" name="firm_id" value="<?=$firm[0]->id?>"/>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="col-md-12">

                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Registration No :</label>
                                                                                    </div>
                                                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                                        <div class="input-group" style="width: 20%;" >
                                                                                        <input type="text" class="form-control" id="registration_no" name="registration_no" value="<?=$firm[0]->registration_no?>" style="width: 400px;"/>
                                                                                        
                                                                                        </div>

                                                                                        <div id="form_registration_no"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- <div class="form-group">
                                                                                <label class="col-sm-2">Registration No :</label>
                                                                                <div class="col-sm-5">
                                                                                    <input type="text" class="form-control" id="registration_no" name="registration_no" value="<?=$firm[0]->registration_no?>"/>
                                                                                    <div id="form_registration_no"></div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label class="col-sm-2">Company Name :</label>
                                                                                <div class="col-sm-5">
                                                                                    <input type="text" class="form-control" id="name" name="name" value="<?=$firm[0]->name?>"/>
                                                                                    <div id="form_name"></div>
                                                                                </div>
                                                                            </div> -->
                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Company Name :</label>
                                                                                    </div>
                                                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                                        <div class="input-group" style="width: 20%;" >
                                                                                        <input type="text" class="form-control" id="name" name="name" value="<?=$firm[0]->name?>" style="width: 400px;"/>
                                                                                        
                                                                                        </div>

                                                                                        <div id="form_name"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- <div class="form-group">
                                                                                
                                                                                    <label class="col-sm-2">Telephone :</label>
                                                                                
                                                                                    <div class="col-sm-8">
                                                                                        <div class="input-group fieldGroup_telephone">
                                                                                            <input type="tel" class="form-control check_empty_telephone main_telephone hp" id="telephone" name="telephone[]" value="<?=$client_contact_info[0]->telephone?>"/>

                                                                                            <input type="hidden" class="form-control input-xs hidden_telephone main_hidden_telephone" id="hidden_telephone" name="hidden_telephone[]" value=""/>

                                                                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="telephone_primary main_telephone_primary" name="telephone_primary" value="1" checked> Primary</label> 

                                                                                             
                                                                                            <input class="btn btn-primary button_increment_telephone addMore_telephone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>

                                                                                            <button type="button" class="btn btn-default btn-sm show_telephone" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
                                                                                                  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
                                                                                            </button>
                                                    
                                                                                        </div>

                                                                                        <div class="telephone_toggle">
                                                                                        </div>

                                                                                        <div class="input-group fieldGroupCopy_telephone telephone_disabled" style="display: none;">
                                                                                            <input type="tel" class="form-control check_empty_telephone second_telephone second_hp" id="telephone" name="telephone[]" value=""/>

                                                                                            <input type="hidden" class="form-control input-xs hidden_telephone" id="hidden_telephone" name="hidden_telephone[]" value=""/>

                                                                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="telephone_primary" name="telephone_primary" value="1"> Primary</label>
                                                                                        </div>

                                                                                        <div id="form_telephone"></div>
                                                                                    </div>
                                                                            </div> -->


                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Telephone :</label>
                                                                                    </div>
                                                                                    <div style="width: 65%;float:left;margin-bottom:5px;">

                                                                                        <div class="input-group fieldGroup_telephone">
                                                                                            <input type="tel" class="form-control check_empty_telephone main_telephone hp" id="telephone" name="telephone[]" value="<?=$client_contact_info[0]->telephone?>"/>

                                                                                            <input type="hidden" class="form-control input-xs hidden_telephone main_hidden_telephone" id="hidden_telephone" name="hidden_telephone[]" value=""/>

                                                                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="telephone_primary main_telephone_primary" name="telephone_primary" value="1" checked> Primary</label>

                                                                                            <input class="btn btn-primary button_increment_telephone addMore_telephone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>

                                                                                            <button type="button" class="btn btn-default btn-sm show_telephone" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
                                                                                                  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
                                                                                            </button>
                                                    
                                                                                        </div>

                                                                                        <div class="telephone_toggle">
                                                                                        </div>

                                                                                        <div class="input-group fieldGroupCopy_telephone telephone_disabled" style="display: none;">
                                                                                            <input type="tel" class="form-control check_empty_telephone second_telephone second_hp" id="telephone" name="telephone[]" value=""/>

                                                                                            <input type="hidden" class="form-control input-xs hidden_telephone" id="hidden_telephone" name="hidden_telephone[]" value=""/>

                                                                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="telephone_primary" name="telephone_primary" value="1"> Primary</label>
                                                                                        </div>

                                                                                        <div id="form_telephone"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Fax :</label>
                                                                                    </div>
                                                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                                        <!-- <div class="input-group" style="width: 20%;" >
                                                                                            <input type="text" class="form-control" id="fax" name="fax" value="<?=$firm[0]->fax?>" style="width: 400px;"/>
                                                                                        <?php echo form_error('fax','<span class="help-block">','</span>'); ?>
                                                                                        </div>
                                                                                        <div id="form_fax"></div> -->
                                                                                        <div class="input-group fieldGroup_fax">
                                                                                            <input type="tel" class="form-control check_empty_fax main_fax hp" id="fax" name="fax[]" value="<?=$client_contact_info[0]->fax?>"/>

                                                                                            <input type="hidden" class="form-control input-xs hidden_fax main_hidden_fax" id="hidden_fax" name="hidden_fax[]" value=""/>

                                                                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="fax_primary main_fax_primary" name="fax_primary" value="1" checked> Primary</label>

                                                                                            <!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
                                                                                                <input class="btn btn-primary button_increment_fax addMore_fax" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>
                                                                                            <!-- </span> -->

                                                                                            <button type="button" class="btn btn-default btn-sm show_fax" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
                                                                                                  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
                                                                                            </button>
                                                    
                                                                                        </div>

                                                                                        <div class="fax_toggle">
                                                                                        </div>

                                                                                        <div class="input-group fieldGroupCopy_fax fax_disabled" style="display: none;">
                                                                                            <input type="tel" class="form-control check_empty_fax second_fax second_hp" id="fax" name="fax[]" value=""/>

                                                                                            <input type="hidden" class="form-control input-xs hidden_fax" id="hidden_fax" name="hidden_fax[]" value=""/>

                                                                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="fax_primary" name="fax_primary" value="1"> Primary</label>

                                                                                            <!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
                                                                                                <input class="btn btn-primary button_decrease_fax remove_fax" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/>
                                                                                            <!-- </span> -->
                                                                                        </div>

                                                                                        <div id="form_fax"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Email :</label>
                                                                                    </div>
                                                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                                        <!-- <div class="input-group" style="width: 20%;" >
                                                                                            <input type="text" class="form-control" id="email" name="email" value="<?=$firm[0]->email?>" style="width: 400px;"/>
                                                                                        </div>
                                                                                        <div id="form_email"></div> -->
                                                                                        <!-- style="display: block !important;" -->
                                                                                        <div class="input-group fieldGroup_email" style="display: block !important;">
                                                                                            <input type="text" class="form-control input-xs check_empty_email main_email" id="email" name="email[]" value="<?=$client_contact_info[0]->email?>" style="width:400px;"/>

                                                                                            <label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="email_primary main_email_primary" name="email_primary" value="1" checked> Primary</label>
                                                                                            
                                                                                            <!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
                                                                                                <input class="btn btn-primary button_increment_email addMore_email" type="button" id="create_button" value="+" style="margin-left: 10px; border-radius: 3px;visibility: hidden; width: 35px;"/>
                                                                                            <!-- </span> -->

                                                                                            <button type="button" class="btn btn-default btn-sm show_email" style="margin-left: 10px; display: none;">
                                                                                                  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
                                                                                            </button>
                                                    
                                                                                        </div>

                                                                                        <div class="email_toggle">
                                                                                        </div>

                                                                                        <div class="input-group fieldGroupCopy_email email_disabled" style="display: none;">
                                                                                            <input type="text" class="form-control input-xs check_empty_email second_email" id="email" name="email[]" value="" style="width:400px;"/>
                                                                                            <label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="email_primary" name="email_primary" value="1"> Primary</label>
                                                                                            
                                                                                            <!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
                                                                                                <input class="btn btn-primary button_decrease_email remove_email" type="button" id="create_button" value="-" style="margin-left: 10px; border-radius: 3px; width: 35px;"/>
                                                                                            <!-- </span> -->
                                                                                        </div>

                                                                                        <div id="form_email"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Website :</label>
                                                                                    </div>
                                                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                                        <div class="input-group" style="width: 20%;" >
                                                                                            <input type="text" class="form-control" id="url" name="url" value="<?=$firm[0]->url?>" style="width: 400px;"/>
                                                                                            <?php echo form_error('url','<span class="help-block">','</span>'); ?>
                                                                                        </div>
                                                                                        <div id="form_url"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Address: </label>
                                                                                    </div>
                                                                                <div style="width: 65%;float:left;margin-bottom:5px;">

                                                                                    <div style="width: 100%;">
                                                                                        <label><input type="radio" id="local_edit" name="address_type" <?php print $firm[0]->local_status; ?> value="Local"/>&nbsp;&nbsp;Singapore Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" id="foreign_edit" name="address_type" <?php print $firm[0]->foreign_status; ?> value="Foreign"/>&nbsp;&nbsp;Malaysia Address</label>
                                                                                    </div>
                                                                                    <div id="tr_local_edit" <?php print $firm[0]->address_type=="Local"?'style=""':'style="display:none;"'; ?>>
                                                                                    <div style="margin-bottom:5px;">
                                                                                            <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                                <label>Postal Code :</label>
                                                                                            </div>
                                                                                            <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                                                <div class="input-group" style="width: 20%;" >
                                                                                                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?=$firm[0]->postal_code?>" maxlength="6">
                                                                                                </div>
                                                                                                <div id="form_postal_code"></div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div style="margin-bottom:5px;">
                                                                                            <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                                <label>Street Name :</label>
                                                                                            </div>
                                                                                            <div style="width: 71%;float:left;margin-bottom:5px;">
                                                                                                <div class="input-group" style="width: 100%;" >
                                                                                                    <input type="text" class="form-control" id="street_name" name="street_name" value="<?=$firm[0]->street_name?>">
                                                                                                </div>
                                                                                                <div id="form_street_name"></div>
                                                                                               
                                                                                            </div>
                                                                                                    
                                                                                        
                                                                                        </div>
                                                                                        <div style="margin-bottom:5px;">
                                                                                            <label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
                                                                                            <div class="input-group" style="width: 71%;" >
                                                                                                <input style="width: 100%;" type="text" class="form-control" id="building_name" name="building_name" value="<?=$firm[0]->building_name?>">
                                                                                                <?php echo form_error('building_name','<span class="help-block">*','</span>'); ?>
                                                                                            </div>
                                                                                        
                                                                                        </div>
                                                                                        <div style="margin-bottom:2px;">
                                                                                            <div style="width: 25%;">
                                                                                            <label style="width: 100%;float:left;margin-right: 20px;">Unit No :</label>
                                                                                        </div>
                                                                                            <div style="width: 75%;" > 
                                                                                            <div class="input-group" style="width: 10%;display: inline-block">
                                                                                                        <input style="width: 100%; margin-right: 10px;" maxlength="3" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=$firm[0]->unit_no1?>">
                                                                                                        <?php echo form_error('unit_no1','<span class="help-block">*','</span>'); ?>
                                                                                                    </div>
                                                                                                    <div class="input-group" style="width: 20%;display: inline-block" >
                                                                                                        <input style="width: 100%;" maxlength="10" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=$firm[0]->unit_no2?>">
                                                                                                        
                                                                                                        <?php echo form_error('unit_no2','<span class="help-block">*','</span>'); ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                                
                                                                                        
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="tr_foreign_edit" <?php print $firm[0]->address_type=="Foreign"?'style=""':'style="display:none;"'; ?>>
                                        <div style="width: 100%;">
                                            <div style="width: 95%;float:left;margin-bottom:5px;">
                                                <div class="add-input-group" style="width: 95%;" >
                                                    <input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="foreign_address1" name="foreign_address1" value="<?=$firm[0]->foreign_address1?>"> 
                                                </div>

                                                <div id="form_foreign_address1"></div>
                                            </div>
                                        </div>
                                        <div style="width: 100%;">
                                            <div style="width: 95%;float:left;margin-bottom:5px;">
                                                <div class="add-input-group" style="width: 95%;" >
                                                    <input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="foreign_address2" name="foreign_address2" value="<?=$firm[0]->foreign_address2?>">
                                                    
                                                    
                                                </div>

                                                <div id="form_foreign_address2"></div>
                                            </div>
                                        </div>
                                        <div style="width: 100%;">
                                            <div style="width: 95%;float:left;margin-bottom:5px;">
                                                <div class="add-input-group" style="width: 95%;" >
                                                    <input type="text" class="form-control input-xs" id="foreign_address3" name="foreign_address3" value="<?=$firm[0]->foreign_address3?>">
                                                    
                                                    
                                                </div>

                                                <div id="form_foreign_address3"></div>
                                            </div>
                                        </div>
                                </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>GST (%) :</label>
                                                                                    </div>
                                                                                    <div style="width: 70%;float:left;margin-bottom:5px;padding-left: 0px !important;" class="col-sm-10">
                                                                                        <div class="input-group" style="margin-bottom: 5px;" >

                                                                                            <input type="checkbox" name="hidden_gst_checkbox" <?=$firm[0]->gst_checkbox?'checked':'';?>/>
                                                                                            <input type="hidden" name="gst_checkbox" value=""/>

                                                                                                <div style="float: right;display: none;" id="div_no_gst">
                                                                                                    <label style="margin-left: 105px;margin-right: 37px;">Since :</label>
                                                                                                    <div class="input-group" id="billing_date" style="width:227px;float: right;">
                                                                                                        <span class="input-group-addon">
                                                                                                            <i class="far fa-calendar-alt"></i>
                                                                                                        </span>
                                                                                                        <input type="text" class="billing_date form-control" id="no_gst_date" name="no_gst_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="<?=$firm[0]->no_gst_date?>" disabled>
                                                                                                    </div>
                                                                                                    
                                                                                                </div>
                                                                                                <div style="margin-left: 250px; padding-right: 0px;" id="form_no_gst_date"></div>
                                                                                        </div>
                                                                                        
                                                                                        <div>
                                                                                            <label style="width: 15%;float:left;margin-right: 2px;">
                                                                                                <input style="margin-right: 10px;" type="text" class="form-control" id="gst_value" name="gst_value" value="<?=$firm[0]->gst?>" maxlength="5">
                                                                                                <div id="form_gst_value"></div>
                                                                                            </label>
                                                                                            <div style="width: 5%; float: left;margin-right: 20px;">%</div>
                                                                                            <div style="width: 10%; float: left;">Since :</div>
                                                                                            
                                                                                            <div style="width: 30%;float: left;" id="">
                                                                                                <div class="input-group" id="billing_date">
                                                                                                    <span class="input-group-addon">
                                                                                                        <i class="far fa-calendar-alt"></i>
                                                                                                    </span>
                                                                                                    <input type="text" class="billing_date form-control" id="gst_date" name="gst_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="<?=$firm[0]->gst_date?>">
                                                                                                </div>
                                                                                                <div id="form_gst_date"></div>
                                                                                            </div>
                                                                                            
                                                                                        </div>
                                                                                        <br><br>
                                                                                        <div id="previous_gst_info" style="display: none;">
                                                                                            <label style="width: 15%;float:left;margin-right: 2px;"><input style="margin-right: 10px;" type="text" class="form-control" id="previous_gst_value" name="previous_gst_value" value="<?=$firm[0]->previous_gst?>" disabled></label>
                                                                                            <div style="width: 5%; float: left;margin-right: 20px;">%</div>
                                                                                            <div style="width: 10%; float: left;">Since :</div>
                                                                                            
                                                                                            <div style="width: 30%;float: left;" id=""><div class="input-group" id="billing_date">
                                                                                                <span class="input-group-addon">
                                                                                                    <i class="far fa-calendar-alt"></i>
                                                                                                </span>
                                                                                                <input type="text" class="billing_date form-control" name="previous_gst_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="<?=$firm[0]->previous_gst_date?>" disabled>
                                                                                            </div></div>
                                                                                            
                                                                                        </div>
                                                                                    
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div style="width: 100%;">
                                                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                                                        <label>Logo (1200 X 1200 px) - gif,jpg,jpeg,png,ico,icon:</label>

                                                                                    </div>
                                                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                                        <div class="input-group" style="width: 100%;" >
                                                                                            <div class="file-loading">
                                                                                                <input type="file" id="logo" class="file" name="uploadlogo" data-min-file-count="0" accept="image/*">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 text-right" style="margin-top: 10px;">
                                                                    <?php echo form_submit('save_firm', lang('Save'), 'class="btn btn-primary"'); ?>
                                                                    <a href="<?= base_url();?>our_firm/" class="btn btn-default">Cancel</a>
                                                                </div>
                                                                <?php echo form_close(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="w2-bankInfo" class="tab-pane">
                                            <div>
                                                <table class="table table-bordered table-striped table-condensed mb-none" id="bank_info_table">
                                                    <thead>
                                                        <div class="tr">
                                                            <div class="th" id="bank_id" style="text-align: center;width:150px">Bank ID</div>
                                                            <div class="th" id="banker" style="text-align: center;width:170px">Banker</div>
                                                            <div class="th" style="text-align: center;width:170px" id="acc_number">Account Number</div>
                                                            <div class="th" id="bank_code" style="text-align: center;width:160px">Bank Code</div>
                                                            <div class="th" id="swift_code" style="text-align: center;width:150px">Swift Code</div>
                                                            <div class="th" id="currency" style="text-align: center;width:150px">Currency</div>
                                                            <a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:170px; outline: none !important;text-decoration: none;"><span id="bank_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Bank Info" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Bank</span></a>
                                                            <div class="th" id="in_use" style="text-align: center;width:150px">In Use</div>
                                                        </div>
                                                        
                                                    </thead>
                                                    

                                                    <div class="tbody" id="body_bank_info">
                                                        

                                                    </div>
                                                    
                                                </table>
                                            </div>
                                        </div>
                                        <!-- <div id="w2-ourService" class="tab-pane">
                                            <table id="our_service_datatable" style="width:100%" class="table table-bordered table-striped mb-none">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;"><span id="our_service_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Our Service" style="font-size:14px;"><i class="fas fa-plus" id="addRow"></i></span></th>
                                                        <th>Service Type</th>
                                                        <th>Service Name</th>
                                                        <th>Amount</th>

                                                    </tr>

                                                </thead>
                                                <tfoot>
                                                    <td style="text-align: center;"><i class="fas fa-search"></i></td>
                                                    <td style="padding: 3px;">Service Type</td>
                                                    <td style="padding: 3px;">Service Name</td>
                                                    <td style="padding: 3px;">Amount</td>
                                                </tfoot>
                                            </table>
                                        </div> -->
                                        <!-- <?php if ($template_module != 'none') { ?> 
                                        <div id="w2-ourService" class="tab-pane">
                                            <form id="form_template">
                                            <table class="table table-bordered table-striped mb-none">
                                                <thead>
                                                    <div class="tr"> 
                                                        
                                                        <div class="th" valign=middle style="width:150px;text-align: center">Service ID</div>
                                                        <div class="th" valign=middle style="width:200px;text-align: center">Service Type</div>
                                                        <div class="th" valign=middle style="width:200px;text-align: center">Service Name</div>
                                                        <div class="th" valign=middle style="width:250px;text-align: center">Invoice Description</div>
                                                        <div class="th" style="width:180px;text-align: center">Amount</div>
                                                        <a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:130px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Service</span></a>
                                                    </div>
                                                    
                                                </thead>
                                                <div class="tbody" id="body_template_info">
                                                    

                                                </div>
                                            
                                            </table>
                                            </form>
                                        </div>
                                        <?php
                                            }
                                        ?> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 number text-right" id="billing_footer_button">
                    <button type="submit" class="btn btn-primary" name="save_template" id="save_template">Save</button>
                    <a href="<?= base_url();?>masterclient/" class="btn btn-default">Close</a>
                </div>
            </div>
        </footer> -->
    </section>
    <div class="loading" id='loadingOurFirm'>Loading&#8230;</div>
</div>




<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script type="text/javascript" charset="utf-8">
    var firm = <?php echo json_encode($firm);?>;
    var company_code = <?php echo json_encode($company_code);?>;
    var edit_firm_telephone = <?php echo json_encode($firm[0]->firm_telephone);?>;
    var edit_firm_fax = <?php echo json_encode($firm[0]->firm_fax);?>;
    var edit_firm_email = <?php echo json_encode($firm[0]->firm_email);?>;
    var initialPreviewArray = []; 
    var initialPreviewConfigArray = [];
    var base_url = '<?php echo base_url() ?>';
    var files = <?php echo json_encode($firm[0]->file_name);?>;
    // var template = <?php echo json_encode($template);?>;
    var bank_info = <?php echo json_encode($bank_info);?>;
    var firm_id = <?php echo json_encode($firm_id);?>;
    var tab = <?php echo json_encode($tab);?>;

    //console.log(template);
</script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/bank_info.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<!-- <script src="themes/default/assets/js/our_service.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script> -->
<script type="text/javascript" charset="utf-8">
// var firm = <?php echo json_encode($firm);?>;
// var edit_firm_telephone = <?php echo json_encode($firm[0]->firm_telephone);?>;
// var edit_firm_fax = <?php echo json_encode($firm[0]->firm_fax);?>;
// var edit_firm_email = <?php echo json_encode($firm[0]->firm_email);?>;
// var initialPreviewArray = []; 
// var initialPreviewConfigArray = [];
// var base_url = '<?php echo base_url() ?>';
// var files = <?php echo json_encode($firm[0]->file_name);?>;
// var template = <?php echo json_encode($template);?>;

$("#header_our_firm").addClass("header_disabled");
$("#header_manage_user").removeClass("header_disabled");
$("#header_access_right").removeClass("header_disabled");
$("#header_user_profile").removeClass("header_disabled");
$("#header_setting").removeClass("header_disabled");
$("#header_dashboard").removeClass("header_disabled");
$("#header_client").removeClass("header_disabled");
$("#header_person").removeClass("header_disabled");
$("#header_document").removeClass("header_disabled");
$("#header_report").removeClass("header_disabled");
$("#header_billings").removeClass("header_disabled");

$(document).ready(function() {
    $('#loadingOurFirm').hide();
});

$(function() {
  $('#gst_value').on('input', function() {
    this.value = this.value.match(/\d{0,2}(\.\d{0,4})?/)[0];
  });
});

if(tab == "service")
{
    $('#myTab #li-ourFirm').removeClass("active");
    $('.tab-content #w2-ourFirm').removeClass("active");

    $('#myTab #li-ourService').addClass("active");
    $('.tab-content #w2-ourService').addClass("active");
    //$tab_aktif = "billing";
}

$('#postal_code').keyup(function(){
    if($(this).val().length == 6){
        var zip = $(this).val();
        //var address = "068914";
        $.ajax({
          url:    'https://gothere.sg/maps/geo',
          dataType: 'jsonp',
          data:   {
            'output'  : 'json',
            'q'     : zip,
            'client'  : '',
            'sensor'  : false
          },
          type: 'GET',
          success: function(data) {
            //console.log(data);
            //var field = $("textarea");
            var myString = "";
            
            var status = data.Status;
            /*myString += "Status.code: " + status.code + "\n";
            myString += "Status.request: " + status.request + "\n";
            myString += "Status.name: " + status.name + "\n";*/
            
            if (status.code == 200) {         
              for (var i = 0; i < data.Placemark.length; i++) {
                var placemark = data.Placemark[i];
                var status = data.Status[i];

                $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                if(placemark.AddressDetails.Country.AddressLine == "undefined")
                {
                    $("#building_name").val("");
                }
                else
                {
                    $("#building_name").val(placemark.AddressDetails.Country.AddressLine);
                }
                
              }
              $( '#form_postal_code' ).html('');
              $( '#form_street_name' ).html('');
              //field.val(myString);
            } else if (status.code == 603) {
                $( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
              //field.val("No Record Found");
            }

          },
          statusCode: {
            404: function() {
              alert('Page not found');
            }
          },
        });
    }
    else
    {
        $("#street_name").val("");
        $("#building_name").val("");

        /*$("#street_name").attr("readonly", false);
        $("#building_name").attr("readonly", false);*/
    }
});

$('.show_telephone').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".telephone_toggle").toggle();
    console.log($(this).parent().parent());
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        $(this).find(".toggle_word").text('Show more');
    }
});

$('.show_fax').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".fax_toggle").toggle();
    console.log($(this).parent().parent());
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        $(this).find(".toggle_word").text('Show more');
    }
});

$('.show_email').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".email_toggle").toggle();
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        $(this).find(".toggle_word").text('Show more');
    }
});

$('.hp').intlTelInput({
    preferredCountries: [ "sg", "my"],
    initialCountry: "auto",
    formatOnDisplay: false,
    nationalMode: true,
    geoIpLookup: function(callback) {
        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
        });
    },
    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
      return "" ;
    },
    utilsScript: "../themes/default/js/utils.js"
});

//edit
if(edit_firm_telephone != null)
{
    for (var h = 0; h < edit_firm_telephone.length; h++) 
    {
        var firmTelephoneArray = edit_firm_telephone[h].split(',');

        if(firmTelephoneArray[2] == 1)
        {
            $(".fieldGroup_telephone").find('.main_telephone').intlTelInput("setNumber", firmTelephoneArray[1]);
            $(".fieldGroup_telephone").find('.main_hidden_telephone').attr("value", firmTelephoneArray[1]);
            $(".fieldGroup_telephone").find('.main_telephone_primary').attr("value", firmTelephoneArray[1]);
            $(".fieldGroup_telephone").find(".button_increment_telephone").css({"visibility": "visible"});
        }
        else
        {
            
            $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", firmTelephoneArray[1]);
            $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", firmTelephoneArray[1]);


            var fieldHTML = '<div class="input-group fieldGroup_telephone" style="margin-top:10px;">'+$(".fieldGroupCopy_telephone").html()+'</div>';

            //$('body').find('.fieldGroup_telephone:first').after(fieldHTML);
            $( fieldHTML).prependTo(".telephone_toggle");

            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').intlTelInput({
                preferredCountries: [ "sg", "my"],
                formatOnDisplay: false,
                nationalMode: true,
                geoIpLookup: function(callback) {
                    jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                  return "" ;
                },
                utilsScript: "../themes/default/js/utils.js"
            });

            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').intlTelInput("setNumber", firmTelephoneArray[1]);

            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').on({
              keydown: function(e) {
                if (e.which === 32)
                  return false;
              },
              change: function() {
                this.value = this.value.replace(/\s/g, "");
              }
            });

            $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", "");
            $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", "");
        }
    }
}
else
{
    $(".fieldGroup_telephone").find('.main_telephone').intlTelInput("setNumber", "");
}

if(edit_firm_fax != null)
{
    for (var h = 0; h < edit_firm_fax.length; h++) 
    {
        var firmFaxArray = edit_firm_fax[h].split(',');

        if(firmFaxArray[2] == 1)
        {
            $(".fieldGroup_fax").find('.main_fax').intlTelInput("setNumber", firmFaxArray[1]);
            $(".fieldGroup_fax").find('.main_hidden_fax').attr("value", firmFaxArray[1]);
            $(".fieldGroup_fax").find('.main_fax_primary').attr("value", firmFaxArray[1]);
            $(".fieldGroup_fax").find(".button_increment_fax").css({"visibility": "visible"});
        }
        else
        {
            
            $(".fieldGroupCopy_fax").find('.hidden_fax').attr("value", firmFaxArray[1]);
            $(".fieldGroupCopy_fax").find('.fax_primary').attr("value", firmFaxArray[1]);


            var fieldHTML = '<div class="input-group fieldGroup_fax" style="margin-top:10px;">'+$(".fieldGroupCopy_fax").html()+'</div>';

            //$('body').find('.fieldGroup_fax:first').after(fieldHTML);
            $( fieldHTML).prependTo(".fax_toggle");

            $('.fax_toggle .fieldGroup_fax').eq(0).find('.second_hp').intlTelInput({
                preferredCountries: [ "sg", "my"],
                formatOnDisplay: false,
                nationalMode: true,
                geoIpLookup: function(callback) {
                    jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                  return "" ;
                },
                utilsScript: "../themes/default/js/utils.js"
            });

            $('.fax_toggle .fieldGroup_fax').eq(0).find('.second_hp').intlTelInput("setNumber", firmFaxArray[1]);

            $('.fax_toggle .fieldGroup_fax').eq(0).find('.second_hp').on({
              keydown: function(e) {
                if (e.which === 32)
                  return false;
              },
              change: function() {
                this.value = this.value.replace(/\s/g, "");
              }
            });

            $(".fieldGroupCopy_fax").find('.hidden_fax').attr("value", "");
            $(".fieldGroupCopy_fax").find('.fax_primary').attr("value", "");
        }
    }
}
else
{
    $(".fieldGroup_fax").find('.main_fax').intlTelInput("setNumber", "");
}

if(edit_firm_email != null)
{
    for (var h = 0; h < edit_firm_email.length; h++) 
    {
        var firmEmailArray = edit_firm_email[h].split(',');

        if(firmEmailArray[2] == 1)
        {
            $(".fieldGroup_email").find('.main_email').attr("value", firmEmailArray[1]);
            $(".fieldGroup_email").find('.main_email_primary').attr("value", firmEmailArray[1]);

            $(".fieldGroup_email").find(".button_increment_email").css({"visibility": "visible"});
        }
        else
        {
            $(".fieldGroupCopy_email").find('.second_email').attr("value", firmEmailArray[1]);

            $(".fieldGroupCopy_email").find('.email_primary').attr("value", firmEmailArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_email").html()+'</div>';

            //$('body').find('.fieldGroup_email:first').after(fieldHTML);
            $( fieldHTML).prependTo(".email_toggle");

            $(".fieldGroupCopy_email").find('.second_email').attr("value", "");
            $(".fieldGroupCopy_email").find('.email_primary').attr("value", "");
        }
    }
}
//

$(document).on('blur', '.check_empty_telephone', function(){
    $(this).parent().parent().find(".hidden_telephone").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".telephone_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).on('blur', '.check_empty_fax', function(){
    $(this).parent().parent().find(".hidden_fax").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".fax_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).on('blur', '.check_empty_email', function(){
    $(this).parent().find(".email_primary").attr("value", $(this).val());
});

$(document).ready(function() {

    $(document).on('click', '.telephone_primary', function(event){   
        event.preventDefault();
        var telephone_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Telephone?", function (result) {
            if (result) {
                telephone_primary_radio_button.prop( "checked", true );
                $( '#form_telephone' ).html("");
            }
        });
    });

    $(document).on('click', '.fax_primary', function(event){   
        event.preventDefault();
        var fax_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Fax?", function (result) {
            if (result) {
                fax_primary_radio_button.prop( "checked", true );
                $( '#form_fax' ).html("");
            }
        });
    });

    $(document).on('click', '.email_primary', function(event){  
        event.preventDefault();
        var email_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
            if (result) {
                email_primary_radio_button.prop( "checked", true );
                $( '#form_email' ).html("");
            }
        });
    });

    $(".check_empty_telephone").on({
      keydown: function(e) {
        if (e.which === 32)
          return false;
      },
      change: function() {
        this.value = this.value.replace(/\s/g, "");
      }
    });

    $(".check_empty_fax").on({
      keydown: function(e) {
        if (e.which === 32)
          return false;
      },
      change: function() {
        this.value = this.value.replace(/\s/g, "");
      }
    });

    $(".addMore_telephone").click(function(){
        var number = $(".main_telephone").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        var countryData = $(".main_telephone").intlTelInput("getSelectedCountryData");

        $(".telephone_toggle").show();
        $(".show_telephone").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_telephone").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_telephone").find('.second_telephone').attr("value", $(".main_telephone").val());
        $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", number);
        $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", number);
        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        //$(".fieldGroupCopy_telephone").find('.second_telephone').intlTelInput("setCountry", countryData.iso2);

        var fieldHTML = '<div class="input-group fieldGroup_telephone" style="margin-top:10px;">'+$(".fieldGroupCopy_telephone").html()+'</div>';

        //$('body').find('.fieldGroup_telephone:first').after(fieldHTML);
        $( fieldHTML).prependTo(".telephone_toggle");

        $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').intlTelInput({
            preferredCountries: [ "sg", "my"],
            formatOnDisplay: false,
            nationalMode: true,
            initialCountry: countryData.iso2,
            geoIpLookup: function(callback) {
                jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
              return "" ;
            },
            utilsScript: "../themes/default/js/utils.js"
        });

        $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').on({
          keydown: function(e) {
            if (e.which === 32)
              return false;
          },
          change: function() {
            this.value = this.value.replace(/\s/g, "");
          }
        });

        if ($(".main_telephone_primary").is(":checked")) 
        {
            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.telephone_primary').prop( "checked", true );
        }


        $(".button_increment_telephone").css({"visibility": "hidden"});

        if ($(".telephone_toggle").find(".second_telephone").length > 0) 
        {
            $(".show_telephone").css({"visibility": "visible"});

        }
        else {
            $(".show_telephone").css({"visibility": "hidden"});
            
        }
       
        $(".main_telephone").val("");
        $(".main_telephone").parent().parent().find(".hidden_telephone").val("");
        $(".main_telephone").parent().parent().find(".telephone_primary").val("");
        $(".fieldGroupCopy_telephone").find('.second_telephone').attr("value", "");
        $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", "");
        $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", "");

    });

    $("body").on("click",".remove_telephone",function(){ 
        var remove_telephone_button = $(this);
        bootbox.confirm("Are you comfirm delete this Telephone?", function (result) {
            if (result) {

                remove_telephone_button.parents(".fieldGroup_telephone").remove();

                if (remove_telephone_button.parent().find(".telephone_primary").is(":checked")) 
                {
                    if ($(".telephone_toggle").find(".second_telephone").length > 0) 
                    {
                        $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.telephone_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_telephone_primary").prop( "checked", true );
                    }
                    
                }

                if ($(".telephone_toggle").find(".second_telephone").length > 0) 
                {
                    $(".show_telephone").css({"visibility": "visible"});

                }
                else {
                    $(".show_telephone").css({"visibility": "hidden"});
                    
                }
            }
        });
    });

    $('.main_telephone').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_telephone").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_telephone").css({"visibility": "hidden"});
        }
    });

    $(".addMore_fax").click(function(){
        var number = $(".main_fax").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        var countryData = $(".main_fax").intlTelInput("getSelectedCountryData");

        $(".fax_toggle").show();
        $(".show_fax").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_fax").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_fax").find('.second_fax').attr("value", $(".main_fax").val());
        $(".fieldGroupCopy_fax").find('.hidden_fax').attr("value", number);
        $(".fieldGroupCopy_fax").find('.fax_primary').attr("value", number);
        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        //$(".fieldGroupCopy_fax").find('.second_fax').intlTelInput("setCountry", countryData.iso2);

        var fieldHTML = '<div class="input-group fieldGroup_fax" style="margin-top:10px;">'+$(".fieldGroupCopy_fax").html()+'</div>';

        //$('body').find('.fieldGroup_fax:first').after(fieldHTML);
        $( fieldHTML).prependTo(".fax_toggle");

        $('.fax_toggle .fieldGroup_fax').eq(0).find('.second_hp').intlTelInput({
            preferredCountries: [ "sg", "my"],
            formatOnDisplay: false,
            nationalMode: true,
            initialCountry: countryData.iso2,
            geoIpLookup: function(callback) {
                jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
              return "" ;
            },
            utilsScript: "../themes/default/js/utils.js"
        });

        $('.fax_toggle .fieldGroup_fax').eq(0).find('.second_hp').on({
          keydown: function(e) {
            if (e.which === 32)
              return false;
          },
          change: function() {
            this.value = this.value.replace(/\s/g, "");
          }
        });

        if ($(".main_fax_primary").is(":checked")) 
        {
            $('.fax_toggle .fieldGroup_fax').eq(0).find('.fax_primary').prop( "checked", true );
        }


        $(".button_increment_fax").css({"visibility": "hidden"});

        if ($(".fax_toggle").find(".second_fax").length > 0) 
        {
            $(".show_fax").css({"visibility": "visible"});

        }
        else {
            $(".show_fax").css({"visibility": "hidden"});
            
        }
       
        $(".main_fax").val("");
        $(".main_fax").parent().parent().find(".hidden_fax").val("");
        $(".main_fax").parent().parent().find(".fax_primary").val("");
        $(".fieldGroupCopy_fax").find('.second_fax').attr("value", "");
        $(".fieldGroupCopy_fax").find('.hidden_fax').attr("value", "");
        $(".fieldGroupCopy_fax").find('.fax_primary').attr("value", "");

    });

    $("body").on("click",".remove_fax",function(){ 
        var remove_fax_button = $(this);
        bootbox.confirm("Are you comfirm delete this Fax?", function (result) {
            if (result) {

                remove_fax_button.parents(".fieldGroup_fax").remove();

                if (remove_fax_button.parent().find(".fax_primary").is(":checked")) 
                {
                    if ($(".fax_toggle").find(".second_fax").length > 0) 
                    {
                        $('.fax_toggle .fieldGroup_fax').eq(0).find('.fax_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_fax_primary").prop( "checked", true );
                    }
                    
                }

                if ($(".fax_toggle").find(".second_fax").length > 0) 
                {
                    $(".show_fax").css({"visibility": "visible"});

                }
                else {
                    $(".show_fax").css({"visibility": "hidden"});
                    
                }
            }
        });
    });

    $('.main_fax').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_fax").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_fax").css({"visibility": "hidden"});
        }
    });

    $(".addMore_email").click(function(){
        $(".email_toggle").show();
        $(".show_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_email").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_email").find('.second_email').attr("value", $(".main_email").val());
        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        //$(".fieldGroupCopy_email").find('.second_email').intlTelInput("setCountry", countryData.iso2);
        $(".fieldGroupCopy_email").find('.email_primary').attr("value", $(".main_email").val());

        var fieldHTML = '<div class="input-group fieldGroup_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_email").html()+'</div>';

        //$('body').find('.fieldGroup_email:first').after(fieldHTML);
        $( fieldHTML).prependTo(".email_toggle");

        if ($(".main_email_primary").is(":checked")) 
        {
            $(".email_toggle .fieldGroup_email").eq(0).find('.email_primary').prop( "checked", true );
        }
        
        $(".button_increment_email").css({"visibility": "hidden"});
       
       if ($(".email_toggle").find(".second_email").length > 0) 
        {
            $(".show_email").show();

        }
        else {
            $(".show_email").hide();
            
        }

        $(".main_email").val("");
        $(".main_email").parent().find(".main_email_primary").val("");
        $(".fieldGroupCopy_email").find('.second_email').attr("value", "");
        $(".fieldGroupCopy_email").find('.email_primary').attr("value", "");

    });

    $("body").on("click",".remove_email",function(){ 
        var remove_email_button = $(this);
        bootbox.confirm("Are you comfirm delete this Email?", function (result) {
            if (result) {

                remove_email_button.parents(".fieldGroup_email").remove();

                if (remove_email_button.parent().find(".email_primary").is(":checked")) 
                {
                    if ($(".email_toggle").find(".second_email").length > 0) 
                    {
                        $(".email_toggle .fieldGroup_email").eq(0).find('.email_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_email_primary").prop( "checked", true );
                    }
                }

                if ($(".email_toggle").find(".second_email").length > 0) 
                {
                    $(".show_email").show();

                }
                else {
                    $(".show_email").hide();
                    
                }
            }
        });
    });

    $('.main_email').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_email").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_email").css({"visibility": "hidden"});
        }
    });

    if ($(".telephone_toggle").find(".second_telephone").length > 0) 
    {
        $(".show_telephone").css({"visibility": "visible"});
        $(".telephone_toggle").hide();

    }
    else {
        $(".show_telephone").css({"visibility": "hidden"});
        $(".telephone_toggle").hide();
    }

    if ($(".fax_toggle").find(".second_fax").length > 0) 
    {
        $(".show_fax").css({"visibility": "visible"});
        $(".fax_toggle").hide();

    }
    else {
        $(".show_fax").css({"visibility": "hidden"});
        $(".fax_toggle").hide();
    }

    if ($(".email_toggle").find(".second_email").length > 0) 
    {
        $(".show_email").show();
        $(".email_toggle").hide();

    }
    else {
        $(".show_email").hide();
        $(".email_toggle").hide();
    }

});

/*$("#gst_value").*/
/*$('#gst_value').on('focusin', function(){
    console.log("Saving value " + $(this).val());
    $(this).data('val', $(this).val());
});*/

/*$('#gst_value').on('input', function() {
    //console.log("inin");
    var prev = $(this).data('val');
    var current = $(this).val();

    console.log("Prev value " + prev);
    console.log("New value " + current);
});*/
$("#registration_no").live('change',function(){
    $(this).parent().parent().find("DIV#form_registration_no").html( "" );
});

$("#name").live('change',function(){
    $(this).parent().parent().find("DIV#form_name").html( "" );
});

$("#telephone").live('change',function(){
    //console.log($(this).parent().parent().find("DIV#form_telephone"));
    $("#form_telephone").html( "" );
});

$("#fax").live('change',function(){
    $("DIV#form_fax").html( "" );
});

$("#email").live('change',function(){
    $("DIV#form_email").html( "" );
});

$("#postal_code").live('change',function(){
    $(this).parent().parent().find("DIV#form_postal_code").html( "" );
});

$("#street_name").live('change',function(){
    $(this).parent().parent().find("DIV#form_street_name").html( "" );
});

$("#no_gst_date").live('change',function(){
    $(this).parent().parent().parent().find("DIV#form_no_gst_date").html( "" );
});

$("#gst_date").live('change',function(){
    $(this).parent().parent().parent().find("DIV#form_gst_date").html( "" );
});

$("#gst_value").live('change',function(){
    $(this).parent().parent().parent().find("DIV#form_gst_value").html( "" );
});

$('.nav li').not('.active').addClass('disabled');

$("#foreign_address1").live('change',function(){
    $( '#form_foreign_address1' ).html("");
});

if(firm)
{
    $('.nav li').removeClass('disabled');
}
else
{
    $('.disabled').click(function (e) {
        e.preventDefault();
        if($(this).hasClass("disabled"))
        {
            return false;
        }
        else
        {
            return true;
        }
        
    });
}


if(firm)
{
    if(firm[0]["previous_gst"] != 0)
    {
        $("#previous_gst_info").show();
    }
    else
    {
        $("#previous_gst_info").hide();
    }
}

$(document).on('submit', '#firm_form', function (e) {
    e.preventDefault();
    $("#loadingmessage").show();
    /*$("#gst_value").prop("disabled",false);
    $("#gst_date").prop("disabled",false);*/
    $(".telephone_disabled .check_empty_telephone").attr("disabled", "disabled");
    $(".telephone_disabled .hidden_telephone").attr("disabled", "disabled");
    $(".fax_disabled .check_empty_fax").attr("disabled", "disabled");
    $(".fax_disabled .hidden_fax").attr("disabled", "disabled");
    $(".email_disabled .check_empty_email").attr("disabled", "disabled");

    var form = $('#firm_form');
    //console.log(document.getElementById("logo").value && files);
    $.ajax({ //Upload common input
            url: "our_firm/add_firm",
            type: "POST",
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                //console.log(response.Status);
                $(".telephone_disabled .check_empty_telephone").removeAttr("disabled");
                $(".telephone_disabled .hidden_telephone").removeAttr("disabled");
                $(".fax_disabled .check_empty_fax").removeAttr("disabled");
                $(".fax_disabled .hidden_fax").removeAttr("disabled");
                $(".email_disabled .check_empty_email").removeAttr("disabled");

                if (response.Status === 1) {
                    firm_id = response.firm_id;
                    $("#firm_id").val(response.firm_id);
                    $('.nav li').removeClass('disabled');
                    $('#logo').fileinput('upload');
                }
                else
                {
                    /*console.log("fail");
                    console.log(response.error["nationality"]);*/
                    /*$("#gst_value").prop("disabled",true);
                    $("#gst_date").prop("disabled",true);*/
                    $("#loadingmessage").hide();
                    toastr.error('Please complete all required field', 'Error');
                    if (response.error["registration_no"] != "")
                    {
                        var errorsRegistrationNo = '<span class="help-block">*' + response.error["registration_no"] + '</span>';
                        $( '#form_registration_no' ).html( errorsRegistrationNo );

                    }
                    else
                    {
                        var errorsRegistrationNo = '';
                        $( '#form_registration_no' ).html( errorsRegistrationNo );
                    }

                    if (response.error["name"] != "")
                    {
                        var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
                        $( '#form_name' ).html( errorsName );

                    }
                    else
                    {
                        var errorsName = '';
                        $( '#form_name' ).html( errorsName );
                    }

                    if (response.error["telephone"] != "")
                    {
                        var errorsTelephone = '<span class="help-block">*' + response.error["telephone"] + '</span>';
                        $( '#form_telephone' ).html( errorsTelephone );

                    }
                    else
                    {
                        var errorsTelephone = '';
                        $( '#form_telephone' ).html( errorsTelephone );
                    }

                    if (response.error["fax"] != "")
                    {
                        var errorsFax = '<span class="help-block">*' + response.error["fax"] + '</span>';
                        $( '#form_fax' ).html( errorsFax );

                    }
                    else
                    {
                        var errorsFax = '';
                        $( '#form_fax' ).html( errorsFax );
                    }

                    if (response.error["email"] != "")
                    {
                        var errorsEmail = '<span class="help-block">*' + response.error["email"] + '</span>';
                        $( '#form_email' ).html( errorsEmail );

                    }
                    else
                    {
                        var errorsEmail = '';
                        $( '#form_email' ).html( errorsEmail );
                    }

                    /*if (response.error["url"] != "")
                    {
                        var errorsUrl = '<span class="help-block">*' + response.error["url"] + '</span>';
                        $( '#form_url' ).html( errorsUrl );

                    }
                    else
                    {
                        var errorsUrl = '';
                        $( '#form_url' ).html( errorsUrl );
                    }*/

                    if (response.error["postal_code"] != "")
                    {
                        var errorsPostalCode = '<span class="help-block">*' + response.error["postal_code"] + '</span>';
                        $( '#form_postal_code' ).html( errorsPostalCode );

                    }
                    else
                    {
                        var errorsPostalCode = '';
                        $( '#form_postal_code' ).html( errorsPostalCode );
                    }

                    if (response.error["street_name"] != "")
                    {
                        var errorsStreetName = '<span class="help-block">*' + response.error["street_name"] + '</span>';
                        $( '#form_street_name' ).html( errorsStreetName );

                    }
                    else
                    {
                        var errorsStreetName = '';
                        $( '#form_street_name' ).html( errorsStreetName );
                    }

                    if (response.error["gst_value"] != "")
                    {
                        var errorsGstValue = '<span class="help-block">*' + response.error["gst_value"] + '</span>';
                        $( '#form_gst_value' ).html( errorsGstValue );

                    }
                    else
                    {
                        var errorsGstValue = '';
                        $( '#form_gst_value' ).html( errorsGstValue );
                    }

                    if (response.error["gst_date"] != "")
                    {
                        var errorsGstDate = '<span class="help-block">*' + response.error["gst_date"] + '</span>';
                        $( '#form_gst_date' ).html( errorsGstDate );

                    }
                    else
                    {
                        var errorsGstDate = '';
                        $( '#form_gst_date' ).html( errorsGstDate );
                    }

                    if (response.error["no_gst_date"] != "")
                    {
                        var errorsNoGstDate = '<span class="help-block">*' + response.error["no_gst_date"] + '</span>';
                        $( '#form_no_gst_date' ).html( errorsNoGstDate );

                    }
                    else
                    {
                        var errorsNoGstDate = '';
                        $( '#form_no_gst_date' ).html( errorsNoGstDate );
                    }

                    if (response.error["foreign_address1"] != "")
                    {
                        var errorsForeignAddress1 = '<span class="help-block">*' + response.error["foreign_address1"] + '</span>';
                        $( '#form_foreign_address1' ).html( errorsForeignAddress1 );

                    }
                    else
                    {
                        var errorsForeignAddress1 = '';
                        $( '#form_foreign_address1' ).html( errorsForeignAddress1 );
                    }
                }
            }
        });
});

preview_image(files);
function preview_image(files)
{
    if(files != null)
    {
        /*for (var i = 0; i < files.length; i++) {*/
            
          var url = base_url + "uploads/logo/";
          //var fileArray = files[i].split(',');
          console.log(files);
          
          //console.log(files.substring(files.lastIndexOf('.') + 1));
          var file_type = files.substring(files.lastIndexOf('.') + 1);
          //var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
          //console.log(file_type);
            /*if(file_type == ".pdf")
            {
              initialPreviewConfigArray.push({
                  type: "pdf",
                  caption: fileArray[1],
                  url: "/dot/our_firm/deleteFile/" + fileArray[0],
                  width: "120px",
                  key: i+1
              });
            }
            else
            {*/
                if(files != '')
                {
                    initialPreviewArray.push( url + files );

                    initialPreviewConfigArray.push({
                        //type: "image",
                        caption: files,
                        url: "/secretary/our_firm/deleteFile/" + firm[0]["id"],
                        width: "120px",
                        key: 1
                  });
                }
                
            //}
        //}
    }
}



$("#logo").fileinput({
    theme: 'fa',
    uploadUrl: '/secretary/our_firm/uploadFile', // you must set a valid URL here else you will get an error
    uploadAsync: false,
    browseClass: "btn btn-primary",
    fileType: "any",
    showCaption: false,
    showUpload: false,
    showRemove: false,
    //showClose: false,
    
    fileActionSettings: {
                    showRemove: true,
                    showUpload: false,
                    showZoom: true,
                    showDrag: false,
                },
    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
    initialPreviewShowDelete: false,
    initialPreviewAsData: true,
    initialPreviewDownloadUrl: base_url + 'uploads/logo/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,
    allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "ico", "Icon", "JPEG"],
    autoReplace: true,
    overwriteInitial: true,
    maxFileCount: 1,
    //deleteUrl: "/dot/personprofile/deleteFile",
    /*maxFileSize: 20000048,
    maxImageWidth: 1000,
    maxImageHeight: 1500,
    resizePreference: 'height',
    resizeImage: true,*/
    purifyHtml: true // this by default purifies HTML data for preview
    /*uploadExtraData: { 
        officer_id: $('input[name="officer_id"]').val() 
    }*/
    /*width:auto;height:auto;max-width:100%;max-height:100%;*/

}).on('filesorted', function(e, params) {
    console.log('File sorted params', params);
}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
    $("#loadingmessage").hide();
    //window.location.href = base_url + "our_firm";
    //location.reload(true);
    //console.log(data.response);
    preview_image(data.response);
    $('.nav li').removeClass('disabled');
    toastr.success('Information Updated', 'Updated');
    //console.log(data);
}).on('fileuploaderror', function(event, data, msg) {
    $("#loadingmessage").hide();
    //window.location.href = base_url + "our_firm";
    //location.reload(true);
    toastr.success("Information Updated", "Success");
    //console.log(data);
    //toastr.error('Please upload correct type of image.', 'Error');
     //toastr.error(msg, 'Error');
});

toastr.options = {
  "positionClass": "toast-bottom-right"
}



var state_checkbox = false;
$("[name='gst_checkbox']").val(1);

if(firm)
{
    if(firm[0]['gst_checkbox'] == 0)
    {
        state_checkbox = false;
        $("[name='gst_checkbox']").val(firm[0]['gst_checkbox']);
        $("[name='gst_value']").attr("disabled",true);
        $("#gst_date").prop("disabled",true);

        $("#div_no_gst").show();
        $("#no_gst_date").prop("disabled",false);
    }
    else if(firm[0]['gst_checkbox'] == 1)
    {
        state_checkbox = true;
        $("[name='gst_checkbox']").val(firm[0]['gst_checkbox']);
        $("[name='gst_value']").attr("disabled",false);
        $("#gst_date").prop("disabled",false);

        $("#div_no_gst").hide();
        $("#no_gst_date").prop("disabled",true);
        $("#no_gst_date").val("");
    }
}

$("[name='hidden_gst_checkbox']").bootstrapSwitch({
    state: state_checkbox,
    size: 'small',
    onColor: 'primary',
    onText: 'YES',
    offText: 'NO',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '45px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'


});

$("#gst_value").live('change',function(){
    $( '#form_gst_value' ).html( "" );
});

$("#gst_date").live('change',function(){
    $( '#form_gst_date' ).html( "" );
});

var gst_value;
var gst_date;
var previous_gst_value;
var previous_gst_date;
// Triggered on switch state change.
$("[name='hidden_gst_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    //console.log(this); // DOM element
    //console.log(event); // jQuery event
    //console.log(state); // true | false
    
    //console.log($("[name='gst_value']").val());
    $( '#form_gst_value' ).html( "" );
    $( '#form_gst_date' ).html( "" );
    if(state == true)
    {
        //$("#gst_value").val("");
        $("#div_no_gst").hide();
        $("[name='no_gst_date']").attr("disabled",true);
        $("#no_gst_date").val("");
        $("[name='gst_value']").attr("disabled",false);
        $("[name='gst_value']").val(gst_value);
        $("[name='gst_date']").val(gst_date);
        $("#gst_date").prop("disabled",false);
        $("[name='gst_checkbox']").val(1);
        // /$("[name='gst_value']").attr("value", "");
        if(parseInt(previous_gst_value) > 0)
        {
            $("#previous_gst_info").show();
            $("[name='previous_gst_value']").val(previous_gst_value);
            $("[name='previous_gst_date']").val(previous_gst_date);
        }
        else
        {
            $("#previous_gst_info").hide();
        }

    }
    else
    {
        //$('#gst_value').attr("value", "");
        //$("#gst_date").val("");
        $("#div_no_gst").show();
        $("[name='no_gst_date']").attr("disabled",false);
        gst_value = $("[name='gst_value']").val();
        gst_date = $("[name='gst_date']").val();
        $("[name='gst_value']").attr("disabled",true);
        $("[name='gst_value']").val("");
        $("#gst_date").prop("disabled",true);
        $("[name='gst_date']").val("");
        $("[name='gst_checkbox']").val(0);
        
        previous_gst_value = $("[name='previous_gst_value']").val();
        previous_gst_date = $("[name='previous_gst_date']").val();
        $("[name='previous_gst_value']").val(0);
        $("[name='previous_gst_date']").val("");
        $("#previous_gst_info").hide();
    }
});

if(firm[0]['gst_checkbox'] == null)
{
    if(state_checkbox == false)
    {
        $("#div_no_gst").show();
        $("[name='no_gst_date']").attr("disabled",false);
        gst_value = $("[name='gst_value']").val();
        gst_date = $("[name='gst_date']").val();
        $("[name='gst_value']").attr("disabled",true);
        $("[name='gst_value']").val("");
        $("#gst_date").prop("disabled",true);
        $("[name='gst_date']").val("");
        $("[name='gst_checkbox']").val(0);
        
        previous_gst_value = $("[name='previous_gst_value']").val();
        previous_gst_date = $("[name='previous_gst_date']").val();
        $("[name='previous_gst_value']").val(0);
        $("[name='previous_gst_date']").val("");
        $("#previous_gst_info").hide();
    }
}

    $(document).ready(function () {
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'biller');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'warehouse');
            } else {
                $('.no').slideDown();
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'biller');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'warehouse');
            }
        });
    });

    function handleFileSelect(event) {
        var input = this;

        var ext = input.files[0].type.substring(input.files[0].type.lastIndexOf('/'));
        var str_image_application = input.files[0].type.replace(ext, "");

        //console.log(ext);

        if (str_image_application == "image")
        {
            if (input.files && input.files.length) {
                
                for (var i = 0; i < input.files.length; i++) {
                    var f = input.files[i];
                    //console.log(f);
                    var reader = new FileReader();
                    this.enabled = false
                    reader.onload = (function (e) {
                    //console.log($("#preview").removeAttr("display"));
                    $("#preview").removeAttr("style");
                    $("#preview").attr("style","width: 300px; height:100%;");
                    
                        $("#preview").html(['<div class="image"><div class="img"><img src="', e.target.result, '" title="', input.files[0].name, '" style="max-width:100%;max-height:100%;"/><span class="icon-remove blue delete">x</span></div></div>'].join(''))
                    });
                    reader.readAsDataURL(f);
                }
            }
        }
        else if(str_image_application == "application")
        {
            if (input.files && input.files.length) {
                var reader = new FileReader();
                this.enabled = false
                reader.onload = (function (e) {
                    //console.log(input.files);
                    $("#preview").removeAttr("style");
                    
                    $("#preview").html(['<div class="image"><div class="img"><a href="', e.target.result, '" download>', input.files[0].name, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><span class="icon-remove blue delete">x</span></div></div>'].join(''))
                    
                });
                //console.log(reader.readAsDataURL(input.files[0]));
                reader.readAsDataURL(input.files[0]);
            }
        }
    }
    $('#file').change(handleFileSelect);
    $('#preview').on('click', '.delete', function () {
        $("#preview").removeAttr("style");
        $("#preview").empty()
        $("#file").val("");
    });

if(firm[0]['foreign_address1'] != "" || firm[0]['foreign_address2'] != "" || firm[0]['foreign_address3'] != "")
    {
        if (undefined !== firm[0]['foreign_address1'] && firm[0]['foreign_address1'].length || undefined !== firm[0]['foreign_address2'] && firm[0]['foreign_address2'].length) 
        {
            $('input[name="foreign_address1"]').removeAttr('disabled');
            $('input[name="foreign_address2"]').removeAttr('disabled');
            $('input[name="foreign_address3"]').removeAttr('disabled');
        }
        else
        {
            $('input[name="foreign_address1"]').attr('disabled', 'true');
            $('input[name="foreign_address2"]').attr('disabled', 'true');
            $('input[name="foreign_address3"]').attr('disabled', 'true');
        }
        /*$('input[name="foreign_address1"]').removeAttr('disabled');
        $('input[name="foreign_address2"]').removeAttr('disabled');*/
        
    }
    else if(firm[0]['foreign_address1'] == "" || firm[0]['foreign_address2'] == "" || firm[0]['foreign_address3'] == "")
    {
        $('input[name="foreign_address1"]').attr('disabled', 'true');
        $('input[name="foreign_address2"]').attr('disabled', 'true');
        $('input[name="foreign_address3"]').attr('disabled', 'true');
    }
    else
    {
        $('input[name="foreign_address1"]').attr('disabled', 'true');
        $('input[name="foreign_address2"]').attr('disabled', 'true');
        $('input[name="foreign_address3"]').attr('disabled', 'true');
    }
    
    if(firm[0]['postal_code'] != "")
    {

        $('input[name="postal_code"]').removeAttr('disabled');

    }
    else
    {
        $('input[name="postal_code"]').attr('disabled', 'true');
    }

    if(firm[0]['street_name'] != "")
    {

        $('input[name="street_name"]').removeAttr('disabled');

    }
    else
    {
        $('input[name="street_name"]').attr('disabled', 'true');
    }


$("#local_edit").click(function() {
    $("#tr_foreign_edit").hide();
    $("#tr_local_edit").show();

    //var alternate_address = document.getElementById('alternate_address');

    var foreign_address1 = document.getElementById('foreign_address1');
    var foreign_address2 = document.getElementById('foreign_address2');
    var foreign_address3 = document.getElementById('foreign_address3');

    $('input[name="postal_code"]').removeAttr('disabled');
    $('input[name="street_name"]').removeAttr('disabled');

    // if(alternate_address.checked == false)
    // {
    //     $('input[name="postal_code2"]').attr('disabled', 'true');
    //     $('input[name="street_name2"]').attr('disabled', 'true');
    // }

    /*$('input[name="postal_code2"]').removeAttr('disabled');
    $('input[name="street_name2"]').removeAttr('disabled');*/

    $('input[name="foreign_address1"]').attr('disabled', 'true');
    $('input[name="foreign_address2"]').attr('disabled', 'true');
    $('input[name="foreign_address3"]').attr('disabled', 'true');

    //console.log(foreign_address1);
    /*for (var i = 0; i<foreign_address1.value.length; i++) {*/
        switch (foreign_address1.type) {
            case 'text':
                foreign_address1.value = '';
                break;
        }
    //}
    /*for (var i = 0; i<foreign_address2.value.length; i++) {*/
        switch (foreign_address2.type) {
            case 'hidden':
            case 'text':
                foreign_address2.value = '';
                break;
            case 'radio':
            case 'checkbox': 
        }
    //}
        switch (foreign_address3.type) {
            case 'hidden':
            case 'text':
                foreign_address3.value = '';
                break;
            case 'radio':
            case 'checkbox': 
        }
});

$("#foreign_edit").click(function() {
    $("#tr_foreign_edit").show();
    $("#tr_local_edit").hide();

    //var alternate_address = document.getElementById('alternate_address');
    /*if(alternate_address.checked){
        $("#alternate_text_edit").toggle();
    }

    switch (alternate_address.type) {
        case 'checkbox':
            alternate_address.checked = false; 
            break;
    }*/

    $('input[name="postal_code"]').attr('disabled', 'true');
    $('input[name="street_name"]').attr('disabled', 'true');

    // if(alternate_address.checked == false)
    // {
    //     $('input[name="postal_code2"]').attr('disabled', 'true');
    //     $('input[name="street_name2"]').attr('disabled', 'true');
    // }
    
    $('input[name="foreign_address1"]').removeAttr('disabled');
    $('input[name="foreign_address2"]').removeAttr('disabled');
    $('input[name="foreign_address3"]').removeAttr('disabled');

    /*$("#street_name1").attr("readonly", false);
    $("#building_name1").attr("readonly", false);

    $("#street_name2").attr("readonly", false);
    $("#building_name2").attr("readonly", false);*/


    //for (var i = 1; i < 2; i++) {
        window['postal_code'] = document.getElementById('postal_code');
        window['street_name'] = document.getElementById('street_name');
        window['building_name'] = document.getElementById('building_name');

        switch (window['postal_code'].type) {
            case 'text':
                window['postal_code'].value = '';
                break;
        }
        switch (window['street_name'].type) {
            case 'text':
                window['street_name'].value = '';
                break;
        }
        switch (window['building_name'].type) {
            case 'text':
                window['building_name'].value = '';
                break;
        }
    //}
    for (var i = 1; i < 3; i++) {
        window['unit_no'+i] = document.getElementById('unit_no'+i);
        switch (window['unit_no'+i].type) {
            case 'text':
                window['unit_no'+i].value = '';
                break;
        }
    }

});
</script>

