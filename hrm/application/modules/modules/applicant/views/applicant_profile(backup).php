<!-- <?php echo json_encode($applicant_profile); ?> -->

<div class="row">
    <div class="col-md-12">
        <div class="col-md-12">

        	<!-- <input type="hidden" name="interview_id" value="<?=isset($interview_detail['id']) ? $interview_detail['id'] : '' ?>">
        	<input type="hidden" name="applicant_id" value="<?=isset($interview_detail['applicant_id']) ? $interview_detail['applicant_id'] : '' ?>"> -->

        	<div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Name: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->name; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Email: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->email; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Phone No: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->phoneno; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>IC/Passport No.: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->ic_passport_no; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Gender: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->gender; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Skills: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->skills; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Expected Salary: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->expected_salary; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Last Drawn Salary: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->last_drawn_salary; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Uploaded Resume: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->uploaded_resume; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="width: 25%;float:left;margin-right: 20px;">
                        <label>About: </label>
                    </div>
                    <div style="width: 65%;float:left;margin-bottom:5px;">
                        <div class="input-group" style="width: 20%;">
                        	<label><?php echo $applicant_profile->about; ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


