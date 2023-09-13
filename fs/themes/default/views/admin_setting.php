<div class="header_between_all_section">
<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="form-group">
                        <div class="col-sm-2">
                            <label>Expiration Date :</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span style="display: inline;">
                                    <input class="setting_input" type="text" style="width: 200px;margin-right: 32px" value="<?= $user_info[0]->date_of_expiry ?>" disabled>
                                    <button type="button" class="btn btn-primary">RENEW</button>
                                </span>
                            </div>
                        </div>

                        
                    
               
                <!-- <div style="width: 100%;">
                    <div style="width: 20%;float:left;margin-right: 80px;">
                        <label>Expiration Date :</label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 60%;" >
                        	<span style="display: inline;">
		                        <input class="setting_input" type="text" style="width: 200px;margin-right: 32px" value="<?= $user_info[0]->date_of_expiry ?>" disabled>
		                        <button type="button" class="btn btn-primary">RENEW</button>
		                    </span>
                        </div>

                        <div id="form_name"></div>
                    </div>
                </div> -->
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <label>No of Users :</label>
                </div>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span style="display: inline;">
                            
                            <input class="setting_input" type="text" style="width: 70px;margin-right: 10px" value="<?= $user_info[0]->no_of_user ?>" disabled>
                            <span>/</span>
                            <input class="setting_input" type="text" style="width: 70px;margin-left: 10px;margin-right: 60px" value="<?= $user_info[0]->total_no_of_user ?>" disabled>
                            <button type="button" class="btn btn-primary">RENEW</button>
                        </span>
                        
                    </div>
                </div>
            </div>
            <!-- <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 20%;float:left;margin-right: 80px;">
                        <label>No of Users :</label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 60%;" >
                        	<span style="display: inline;">
                        		
		                        <input class="setting_input" type="text" style="width: 70px;margin-right: 10px" value="<?= $user_info[0]->no_of_user ?>" disabled>
		                    	<span>/</span>
	                        	<input class="setting_input" type="text" style="width: 70px;margin-left: 10px;margin-right: 60px" value="<?= $user_info[0]->total_no_of_user ?>" disabled>
		                        <button type="button" class="btn btn-primary">RENEW</button>
		                    </span>
	                        
                    	</div>
                    <div id="form_telephone"></div>
                    </div>
                </div>
            </div> -->
            <div class="form-group">
                <div class="col-sm-2">
                    <label>No of Clients :</label>
                </div>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span style="display: inline;">
                            
                            <input class="setting_input" type="text" style="width: 70px;margin-right: 10px" value="<?= $user_info[0]->no_of_client ?>" disabled>
                            <span>/</span>
                            <input class="setting_input" type="text" style="width: 70px;margin-left: 10px;margin-right: 60px" value="<?= $user_info[0]->total_no_of_client ?>" disabled>
                            <button type="button" class="btn btn-primary">RENEW</button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 20%;float:left;margin-right: 80px;">
                        <label>No of Clients :</label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 60%;" >
                        	<span style="display: inline;">
                        		
		                        <input class="setting_input" type="text" style="width: 70px;margin-right: 10px" value="<?= $user_info[0]->no_of_client ?>" disabled>
		                    	<span>/</span>
	                        	<input class="setting_input" type="text" style="width: 70px;margin-left: 10px;margin-right: 60px" value="<?= $user_info[0]->total_no_of_client ?>" disabled>
		                        <button type="button" class="btn btn-primary">RENEW</button>
		                    </span>
	                        
                    	</div>
                    <div id="form_telephone"></div>
                    </div>
                </div>
            </div> -->

            <div class="form-group">
                
                    <div class="col-sm-2">
                        <label>Storage :</label>
                    </div>
                    <div class="col-sm-8" style="float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 200px;text-align: center; " >
                        	<span style="display: inline; font-size: 20px;">
                        		&infin;
		                        
		                    </span>
	                        
                    	</div>

                    </div>
                
            </div>

            <!-- <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 20%;float:left;margin-right: 80px;">
                        <label>Storage :</label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 60%;" >
                            <span style="display: inline; font-size: 20px;">
                                &infin;
                                
                            </span>
                            
                        </div>
                    <div id="form_telephone"></div>
                    </div>
                </div>
            </div> -->

            <div class="form-group">
                <div class="col-sm-2">
                    <label>No of Firms :</label>
                </div>
                <div class="col-sm-8">
                    <div class="input-group">
                    	<span style="display: inline;">
	                        <input class="setting_input" type="text" style="width: 200px;margin-right: 32px" value="<?= $user_info[0]->no_of_firm ?>" disabled>
	                        <button type="button" class="btn btn-primary">ADD</button>
	                    </span>
                    </div>
                </div>
                
            </div>

		</div>
	</div>
		
		
</section>
</div>
<script>
$("#header_our_firm").removeClass("header_disabled");
$("#header_manage_user").removeClass("header_disabled");
$("#header_access_right").removeClass("header_disabled");
$("#header_user_profile").removeClass("header_disabled");
$("#header_setting").addClass("header_disabled");
$("#header_dashboard").removeClass("header_disabled");
$("#header_client").removeClass("header_disabled");
$("#header_person").removeClass("header_disabled");
$("#header_document").removeClass("header_disabled");
$("#header_report").removeClass("header_disabled");
$("#header_billings").removeClass("header_disabled");

</script>
<style>

</style>