<div class="header_between_all_section">
<div class="row" style="margin-bottom: 35px;">
    <div class="col-sm-12" style="margin-top: 20px;">
	<div class="tabs">
											
		<ul class="nav nav-tabs nav-justify">
			<li class="active check_stat" id="#li-account" data-information="account">
				<a href="#w2-account" data-toggle="tab" class="text-center">
					Profile
				</a>
			</li>
			<li class="check_stat" id="#li-change_password" data-information="change_password">
				<a href="#w2-change_password" data-toggle="tab" class="text-center">
					Password
				</a>
			</li>
			<?php 
				if($user->group_id != "4"){
	                $firm_id = $this->session->userdata("firm_id");
	                if($firm_id == 6 || $firm_id == 7 || $firm_id == 8 || $firm_id == 9 || $firm_id == 15 || $firm_id == 16 || $firm_id == 17 || $firm_id == 18 || $firm_id == 21 || $firm_id == 22 || $firm_id == 23 || $firm_id == 24 || $firm_id == 25 || $firm_id == 26) { 
            ?>
				<li class="check_stat" id="#li-rules" data-information="rules">
					<a href="#w2-rules" data-toggle="tab" class="text-center">
						Rules
					</a>
				</li>
			<?php } } ?>
		</ul>
		<div class="tab-content">
			<div id="w2-account" class="tab-pane active">
				<div class="row">
					<div class="col-lg-12">

						<?php $attrib = array('id' => 'update_profile','class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
						echo form_open('', $attrib);
						?>
						<h4 class="text-primary" style="margin-bottom: 30px;"><?= $user->email; ?></h4>
						<div class="row">
							<div class="col-md-12">

								<div>

									<div class="form-group">
										<div class="col-sm-2">
											<label for="first_name" class="profile_label"><?php echo lang('first_name', 'first_name'); ?>:</label>
										</div>
										<div class="col-sm-5">
											<?php echo form_input('first_name', 'First Name', $user->first_name, 'class="form-control" id="first_name" required="required" style="width:300px;" maxlength="50"'); ?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-2">
											<label for="first_name" class="profile_label"><?php echo lang('last_name', 'last_name'); ?>:</label>
										</div>
										<div class="col-sm-5">
											<?php echo form_input('last_name', 'Last Name', $user->last_name, 'class="form-control" id="last_name" style="width:300px;" maxlength="50"'); ?>
										</div>
									</div>

									<?php if($part == "access_client"){ ?>
										<input type="hidden" id="role" name="role" class="form-control" value="4" />

										<div class="form-group" style="overflow: visible; height: 100px">
											<div class="col-sm-2">
			                                	<label for="client" class="profile_label">Client:</label>
			                                </div>
			                                <div class="col-sm-5">
			                                    <select class="form-control" id="selected_client" multiple="multiple" name="selected_client[]">
			                                    </select>
			                                </div>
			                                
			                            </div>
									<?php } ?>

									<?php if (($Admin && $Admin != null && $id != $this->session->userdata('user_id') && $part != "access_client") || ($Manager && $Manager != null && $id != $this->session->userdata('user_id') && $part != "access_client")) { ?>
										<div class="form-group">
											<div class="col-sm-2">
			                                	<label for="email" class="profile_label">Role:</label>
			                                </div>
			                                <div class="col-sm-5">
			                                        <select class="form-control" style="text-align:right;width: 300px;" name="role" id="role">
			                                            <option value="0" >Select Role</option>
			                                        </select>
			                                    
			                                </div>
			                            </div>
			                            <div class="form_group has-success manager_in_charge_div">
                            			</div>
			                        <?php } ?>
			                        <?php if (($Admin && $Admin != null && $id != $this->session->userdata('user_id') && $part != "access_client") || ($Manager && $Manager != null && $id != $this->session->userdata('user_id') && $part != "access_client")) { ?>
			                        	<div class="form-group">
											<div class="col-sm-2">
			                                	<label for="department" class="profile_label">Department:</label>
			                                </div>
			                                <div class="col-sm-5">
			                                        <select class="form-control" style="text-align:right;width: 300px;" name="department" id="department">
			                                            <option value="0" >Select Department</option>
			                                        </select>
			                                    
			                                </div>
			                            </div>
		                            <?php } ?>
									<?php if (($Admin && $Admin != null && $id != $this->session->userdata('user_id') && $part != "access_client") || ($Manager && $Manager != null && $id != $this->session->userdata('user_id') && $part != "access_client")) { ?>
										<div class="form-group" style="overflow: visible; height: 100px">
											<div class="col-sm-2">
			                                	<label for="firm" class="profile_label">Firm: </label>
			                                </div>
			                                <div class="col-sm-5">
			                                    <select id="selected_firm" multiple="multiple" name="selected_firm[]">
			                                    </select>
			                                </div>
			                            </div>
		                            <?php } ?>
									<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
										<div class="form-group">
											<?php echo lang('username', 'username'); ?>
											<input type="text" name="username" class="form-control"
												   id="username" value="<?= $user->username ?>"
												   required="required"/>
										</div>
										<div class="form-group">
											<?php echo lang('email', 'email'); ?>

											<input type="email" name="email" class="form-control" id="email"
												   value="<?= $user->email ?>" required="required"/>
										</div>

									<?php } ?>

								</div>
								<div class="col-md-5 col-md-offset-1">
									<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
										<div class="row">
											<div class="panel panel-warning">
												<div
													class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
												<div class="panel-body" style="padding: 5px;">
													<div class="col-md-12">
														<div class="col-md-12">
															<div class="form-group">
																<?php echo lang('password', 'password'); ?>
																<?php echo form_input($password); ?>
															</div>

															<div class="form-group">
																<?php echo lang('confirm_password', 'password_confirm'); ?>
																<?php echo form_input($password_confirm); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php } ?>
									<?php echo form_hidden('id', $id); ?>
									<?php echo form_hidden($csrf); ?>
								</div>
							</div>
						</div>
						<div class="col-md-12 text-right" style="margin-top: 10px;">
		                    <input type="button" value="Update" id="update" class="btn btn-primary ">
		                    <?php if ($id != $this->session->userdata('user_id') && $part != "access_client") { ?>
		                    	<a href="<?= base_url();?>auth/users/" class="btn btn-default">Cancel</a>
		                    <?php } ?>
		                    <?php if ($id == $this->session->userdata('user_id') && $part != "access_client") { ?>
		                    	<a href="<?= base_url();?>welcome/" class="btn btn-default">Cancel</a>
		                    <?php } ?>
		                    <?php if($part == "access_client"){ ?>
		                    	<a href="<?= base_url();?>auth/client/" class="btn btn-default">Cancel</a>
		                    <?php } ?>
		                </div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div id="w2-change_password" class="tab-pane">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
                            <div class="col-lg-12">
                                <?php $attrib = array('id' => 'change-password-form','class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
								echo form_open('', $attrib);
								?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div>
                                            <div class="form-group">
                                            	<div class="col-sm-2">
                                                	<label for="old_password" class="profile_label"><?php echo lang('old_password', 'curr_password'); ?> :</label>
                                                </div>
                                                <div class="col-sm-5">
                                                	<?php echo form_password('old_password', 'Old Password', '', 'class="form-control" id="curr_password" style="width:300px" required="required" '); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                            	<div class="col-sm-2">
													<label for="new_password" class="profile_label"><?php echo sprintf(lang('new_password'), $min_password_length); ?> :</label>
												</div>
												<div class="col-sm-8">
	                                                <?php echo form_password('new_password', 'New Password', '', 'class="form-control" id="new_password" style="width:300px" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>

	                                                <span class="help-block"><?= lang('pasword_hint') ?></span>
	                                            </div>

                                            </div>

                                            <div class="form-group">
                                            	<div class="col-sm-2">
                                                	<label for="confirm_password" class="profile_label"><?php echo lang('confirm_password', 'new_password_confirm'); ?> :</label>
                                                </div>
                                                <div class="col-sm-5">
	                                                <?php echo form_password('new_password_confirm', 'Confirm New Password', '', 'class="form-control" id="new_password_confirm" style="width:300px" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
	                                            </div>

                                            </div>
                                            <?php echo form_input($user_id); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right" style="margin-top: 10px;">
				                    <input type="button" value="Change Password" id="change_password_button" class="btn btn-primary ">
				                    <?php if ($id != $this->session->userdata('user_id') && $part != "access_client") { ?>
				                    	<a href="<?= base_url();?>auth/users/" class="btn btn-default">Cancel</a>
				                    <?php } ?>
				                    <?php if ($id == $this->session->userdata('user_id') && $part != "access_client") { ?>
				                    	<a href="<?= base_url();?>welcome/" class="btn btn-default">Cancel</a>
				                    <?php } ?>
				                    <?php if($part == "access_client"){ ?>
				                    	<a href="<?= base_url();?>auth/client/" class="btn btn-default">Cancel</a>
				                    <?php } ?>
				                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    
					</div>
				</div>
			</div>
			<?php if($user->group_id != "4"){ ?>
				<div id="w2-rules" class="tab-pane">
					<div class="aaa-grid">
							<div class="scrollspy grid-link">
									<div id="rules_nav" data-spy="affix" style="height: 100%;">
										<div class="top-left-panel">
							                <div class="sbff-main-title">
											    <span>RULES</span>
										        <a href="../secretary/assets/uploads/file/Rules.pdf" title="Download PDF (439KB)" target="_blank" aria-label="Download PDF (439KB)" data-size=" (439KB)" class="file-download">
										            <span class="far fa-file-pdf" style="color: red;"></span>
										        </a>
											</div>

											<div class="status-row">
											    <div class="status-caption">
											        Status:<br>&nbsp;
											    </div>
											    <div class="status-value">
											        Current version<br>as at 29 April 2020
											    </div>
											</div>
										</div>
										<div class="sbf-panel" style="" id="sbfPanel">
											<div class="sbf-title" id="sbfTitle">
						                        Table of Contents
						                    </div>
											<ul id="myRulesDIV" class="nav hidden-xs hidden-sm rules_nav scrollable-panel sbf" style="width:100%; height: 100%; max-height: 524px;">
												<!-- onscroll="myFunction()"scrollRulesContent -->
												<li><a href="#organization_conduct"><span><b>PART I - ORGANIZATION CONDUCT</b></span></a></li>
												<li><a href="#citation_and_commencement"><span><i>Citation and commencement</i></span></a>
													<ul class="nav">
														<li><a href="#commencement_date"></span>1 Commencement date</a></li>
													</ul>
												</li>
												<li><a href="#application_rule_definition"><span><i>Application of rules and definition</i></span></a>
													<ul class="nav">
														<li><a href="#applicability_and_definitions"></span>2 Applicability and Definitions</a></li>
													</ul>
												</li>
												<li><a href="#general_principles"><span><i>General principles</i></span></a>
													<ul class="nav">
														<li><a href="#principles_of_rules"></span>3 Principles of the rules</a></li>
													</ul>
												</li>
												<li><a href="#working_environment"><span><i>Working environment</i></span></a>
													<ul class="nav">
														<li><a href="#discrimination"></span>4 Discrimination</a></li>
														<li><a href="#drugs_unlawful_substances"></span>5 Drugs and unlawful substance</a></li>
														<li><a href="#counselling"></span>6 Counselling</a></li>
													</ul>
												</li>
												<li><a href="#probation"><span><i>Probation</i></span></a>
													<ul class="nav">
														<li><a href="#probationary"></span>7 Probationary</a></li>
													</ul>
												</li>
												<li><a href="#leave"><span><i>Leave</i></span></a>
													<ul class="nav">
														<li><a href="#public_holiday"></span>8 Public holiday</a></li>
														<li><a href="#leave_entitlement"></span>9 Leave entitlement</a></li>
														<li><a href="#vacation_leave"></span>10 Vacation leave</a></li>
														<li><a href="#medical_leave"></span>11 Medical leave</a></li>
														<li><a href="#hospitalization_leave"></span>12 Hospitalization leave</a></li>
														<li><a href="#maternity_leave"></span>13 Maternity leave</a></li>
														<li><a href="#paternity_leave"></span>14 Paternity leave</a></li>
														<li><a href="#childcare_leave"></span>15 Childcare leave</a></li>
														<li><a href="#study_leave"></span>16 Study leave</a></li>
														<li><a href="#time_off"></span>17 Time off</a></li>
														<li><a href="#urgent_leave"></span>18 Urgent leave</a></li>
													</ul>
												</li>
												<li><a href="#working_arrangement"><span><i>Working arrangement</i></span></a>
													<ul class="nav">
														<li><a href="#working_hours"></span>19 Working hours</a></li>
														<li><a href="#place_of_work"></span>20 Place of work</a></li>
														<li><a href="#residence"></span>21 Residence</a></li>
														<li><a href="#attire_code"></span>22 Attire code</a></li>
													</ul>
												</li>
												<li><a href="#remuneration_and_benefits"><span><i>Remuneration and benefits</i></span></a>
													<ul class="nav">
														<li><a href="#fixed_and_variables_payment"></span>23 Fixed and variables payment</a></li>
														<li><a href="#salaries_calculation"></span>24 Salaries calculation</a></li>
														<li><a href="#variable_scheme"></span>25 Variable scheme</a></li>
														<li><a href="#training"></span>26 Training</a></li>
														<li><a href="#support_on_courses"></span>27 Support on courses</a></li>
														<li><a href="#meal_and_transport_claims"></span>28 Meal and transport claims</a></li>
													</ul>
												</li>
												<li><a href="#conduct_and_expectation"><span><i>Conduct and expectation</i></span></a>
													<ul class="nav">
														<li><a href="#timesheet"></span>29 Timesheet</a></li>
														<li><a href="#professional_conduct"></span>30 Professional conduct</a></li>
														<li><a href="#health_and_safety"></span>31 Health and safety</a></li>
													</ul>
												</li>
												<li><a href="#performace_assessment"><span><i>Performance assessment</i></span></a>
													<ul class="nav">
														<li><a href="#appraisal"></span>32 Appraisal</a></li>
													</ul>
												</li>
												<li><a href="#disciplinary_ground"><span><i>Disciplinary ground</i></span></a>
													<ul class="nav">
														<li><a href="#displinary_actions"></span>33 Disciplinary actions</a></li>
													</ul>
												</li>
												<li><a href="#termination"><span><i>Termination</i></span></a>
													<ul class="nav">
														<li><a href="#termination_with_notice"></span>34 Termination with notice</a></li>
														<li><a href="#termination_without_notice"></span>35 Termination without notice</a></li>
														<li><a href="#misconduct"></span>36 Misconduct</a></li>
													</ul>
												</li>
												<li><a href="#property_or_organization"><span><i>Property of organization</i></span></a>
													<ul class="nav">
														<li><a href="#property_and_usage"></span>37 Property and usage</a></li>
													</ul>
												</li>
												<li><a href="#intellectual_property"><span><i>Intellectual property</i></span></a>
													<ul class="nav">
														<li><a href="#ownership_of_intellectual_property"></span>38 Ownership of Intellectual property</a></li>
													</ul>
												</li>
												<li><a href="#privacy"><span><i>Privacy</i></span></a>
													<ul class="nav">
														<li><a href="#privacy_in_the_organization"></span>39 Privacy in the organization</a></li>
													</ul>
												</li>
												<li><a href="#confidentiality"><span><i>Confidentiality</i></span></a>
													<ul class="nav">
														<li><a href="#managing_confidentiality"></span>40 Managing confidentiality</a></li>
													</ul>
												</li>
												<li><a href="#conflict_of_interest"><span><i>Conflict of interest</i></span></a>
													<ul class="nav">
														<li><a href="#managing_conflict_of_interest"></span>41 Managing conflict of interest</a></li>
													</ul>
												</li>
												<li><a href="#solicitation"><span><i>Solicitation</i></span></a>
													<ul class="nav">
														<li><a href="#restriction_on_solicitation"></span>42 Restriction on solicitation</a></li>
													</ul>
												</li>
												<li><a href="#public_appearance"><span><i>Public appearance</i></span></a>
													<ul class="nav">
														<li><a href="#restriction_on_public_appearance"></span>43 Restriction on public appearance</a></li>
													</ul>
												</li>
												<li><a href="#personal_data_protection"><span><i>Personal data protection</i></span></a>
													<ul class="nav">
														<li><a href="#safeguarding_data_collected"></span>44 Safeguarding data collected</a></li>
													</ul>
												</li>
												<li><a href="#quality_management"><span><b>PART II - QUALITY MANAGEMENT</b></span></a></li>
												<li><a href="#objective"><span><i>Objectives</i></span></a>
													<ul class="nav">
														<li><a href="#objective_of_quality_control"></span>45 Objective of quality control</a></li>
														<li><a href="#conformity_of_standard_on_quality_control"></span>46 Conformity of standard on quality control</a></li>
													</ul>
												</li>
												<li><a href="#leadership"><span><i>Leadership</i></span></a>
													<ul class="nav">
														<li><a href="#leadership_in_organization"></span>47 Leadership in organization</a></li>
													</ul>
												</li>
												<li><a href="#ethical_requirement"><span><i>Ethical requirement</i></span></a>
													<ul class="nav">
														<li><a href="#adherence_to_ethical_requirements"></span>48 Adherence to ethical requirements</a></li>
													</ul>
												</li>
												<li><a href="#acceptance_and_continuance"><span><i>Acceptance and continuance</i></span></a>
													<ul class="nav">
														<li><a href="#acceptance_and_continuance_practice"></span>49 Acceptance and continuance practice</a></li>
													</ul>
												</li>
												<li><a href="#human_resources"><span><i>Human resources</i></span></a>
													<ul class="nav">
														<li><a href="#allocation_of_human_resources"></span>50 Allocation of human resources</a></li>
													</ul>
												</li>
												<li><a href="#engagement_performance"><span><i>Engagement performance</i></span></a>
													<ul class="nav">
														<li><a href="#professional_standards_performance"></span>51 Professional standards performance</a></li>
														<li><a href="#monitoring"></span>52 Monitoring</a></li>
													</ul>
												</li>
												<li><a href="#documentation"><span><i>Documentation</i></span></a>
													<ul class="nav">
														<li><a href="#adequacy_of_documentation"></span>53 Adequacy of documentation</a></li>
													</ul>
												</li>
												<li><a href="#independence"><span><b>PART III - INDEPENDENCE</b></span></a></li>
												<li><a href="#threat_to_independence"><span><i>Threat to independence</i></span></a>
													<ul class="nav">
														<li><a href="#types_of_threat"></span>54 Types of threat</a></li>
													</ul>
												</li>
												<li><a href="#self-interest_threat"><span><i>Self-interest threat</i></span></a>
													<ul class="nav">
														<li><a href="#self-interest_threat_safeguard"></span>55 Self-interest threat safeguard</a></li>
													</ul>
												</li>
												<li><a href="#self-review_threat"><span><i>Self-review threat</i></span></a>
													<ul class="nav">
														<li><a href="#self-review_threat_safeguard"></span>56 Self-review threat safeguard</a></li>
													</ul>
												</li>
												<li><a href="#advocacy_threat"><span><i>Advocacy threat</i></span></a>
													<ul class="nav">
														<li><a href="#advocacy_threat_safeguard"></span>57 Advocacy threat safeguard</a></li>
													</ul>
												</li>
												<li><a href="#familiarity_threat"><span><i>Familiarity threat</i></span></a>
													<ul class="nav">
														<li><a href="#familiarity_threat_safeguard"></span>58 Familiarity threat safeguard</a></li>
													</ul>
												</li>
												<li><a href="#intimidation_threat"><span><i>Intimidation threat</i></span></a>
													<ul class="nav">
														<li><a href="#intimidation_threat_safeguard"></span>59 Intimidation threat safeguard</a></li>
													</ul>
												</li>
												<li><a href="#safeguards"><span><i>Safeguards</i></span></a>
													<ul class="nav">
														<li><a href="#safeguards_to_independence"></span>60 Safeguards to independence</a></li>
													</ul>
												</li>
												<li><a href="#breach_of_independence"><span><i>Breach of independence</i></span></a>
													<ul class="nav">
														<li><a href="#identification_of_breach"></span>61 Identification of breach</a></li>
													</ul>
												</li>
												<li><a href="#anti_money_laudering_counting_financing"><span><b>PART IV - ANTI MONEY LAUNDERING AND COUNTERING FINANCING OF TERRORISM</b></span></a></li>
												<li><a href="#anti_money_laudering_general"><span><i>General</i></span></a>
													<ul class="nav">
														<li><a href="#money_laundering_and_financing_of_terrorism"></span>62 Money laundering and financing of terrorism</a></li>
													</ul>
												</li>
												<li><a href="#customer_due_diligence"><span><i>Customer due diligence</i></span></a>
													<ul class="nav">
														<li><a href="#kyc_process"></span>63 KYC process</a></li>
													</ul>
												</li>
												<li><a href="#record_keeping"><span><i>Record keeping</i></span></a>
													<ul class="nav">
														<li><a href="#period_and_types_of_record_to_be_kept"></span>64 Period and types of record to be kept</a></li>
													</ul>
												</li>
												<li><a href="#hiring_and_training"><span><i>Hiring and training</i></span></a>
													<ul class="nav">
														<li><a href="#training_process"></span>65 Training process</a></li>
													</ul>
												</li>
												<li><a href="#compliance_management"><span><i>Compliance management</i></span></a>
													<ul class="nav">
														<li><a href="#compliance_officer_and_audit_function"></span>66 Compliance officer and audit function</a></li>
													</ul>
												</li>
												<li><a href="#suspicious_transactions_reporting"><span><i>Suspicious transactions reporting</i></span></a>
													<ul class="nav">
														<li><a href="#reporting_procedures"></span>67 Reporting procedures</a></li>
													</ul>
													<ul class="nav">
														<li><a href="#indicators_of_suspicious_transactions"></span>68 Indicators of suspicious transactions</a></li>
													</ul>
												</li>
									        </ul>
									    </div>
								    </div>
							    </div>
								<div class="grid-content" id="scrollRulesContent"> 
									<!-- onscroll="scrollRulesContent()" -->
									<section id="organization_conduct" class="rulesContentTitle">
										<span>PART I – ORGANIZATION CONDUCT</span>
									</section>
									<section id="citation_and_commencement" class="rulesContentTitle">
										<span>Citation and commencement</span>
									</section>
									<section id="commencement_date" class="rulesContent">
										<span><strong>1.</strong>– These Rules may be cited as the Organization Rules 2020 and shall come into operation on 1st day of May 2020.</span>
									</section>
									<section id="application_rule_definition" class="rulesContentTitle">
										<span>Application of Rules and definition</span>
									</section>
									<section id="applicability_and_definitions" class="rulesContent">
										<p><strong>2.</strong>–(1) These Rules shall apply to every employee who work full time with the organization.</p>
										<p>(2) Employee for the purpose of these rules refers to a person employed under contract of service with the organization.</p>
										<p>(3) Organization for the purpose of these rules include:</p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Alpha Advisory Pte. Ltd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Alpha Advisory Sdn. Bhd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Alpha Taxation Sdn. Bhd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Assurance</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Associates LLP</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Bizcorp Pte. Ltd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Genesis Pte. Ltd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Novelty Pte. Ltd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Alpha Corporate Services Pte. Ltd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Simpex Consultings (s) Pte. Ltd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">SYA PAC</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Acumen Novelty Pte. Ltd.</span></p>
										<p><span class="rulesNo1">•</span><span class="pRulesTxt1">Venture Corporate Services Pte. Ltd.</span></p>
										<p>(4) For the purpose of these rules, singular and plural may be interchanged but carries the same meaning.</p>
										<p>(5) These rules are not all inclusive or intended to provide strict interpretations of the policies; rather, it offers an overview of the work environment.</p>
										<p>(6) These rules are not a contract, expressed or implied, guarantying employment for any length of time and is not intended to induce an employee to accept employment with the organization.</p>
										<p>(7) The management has the right to unilaterally revise, suspend, revoke, terminate or change any of its rules, in whole or in part, whether described within these rules or elsewhere, in its sole discretion.</p>
										<p>(8) If any discrepancy between these rules and current policy arises, conform to current policy.</p>
									</section>
									<section id="general_principles" class="rulesContentTitle">
										<span>General principles</span>
									</section>
									<section id="principles_of_rules" class="rulesContent">
										<p><strong>3.</strong>–(1) An employee shall exercise due diligence and care when dealing with any assignment, task or work in discharging their duties.</p>
										<p>(2) An employee shall assist and cooperate with the representative of the organization who are carrying out investigation of misconduct and other breaches in the organization.</p>
									</section>
									<section id="working_environment" class="rulesContentTitle">
										<span>Working environment</span>
									</section>
									<section id="discrimination" class="rulesContent">
										<p><strong>4.</strong>–(1) The organization does not tolerate discrimination against any individual or permit discrimination by any member of its community against any individual on the basis of race, color, religion, national origin, sex, parental status, marital status, age, disability, citizenship, veteran or status in matters of admissions, employment, housing, or services or in the educational programs or activities it operates.</p>
										<p>(2) Harassment on the basis of race, color, religion, creed, sex, national origin, age, disability, marital status, veteran status or any other status protected by applicable law will be considered as misconduct in accordance with the provision of these Rules.</p>
										<p>(3) Employee being harassed or discriminated against or has witnessed or become aware of discrimination or harassment in violation of these rules, may bring the matter to the immediate attention of the management.</p>
										<p>(4) The management upon receiving report from the employee shall take prompt investigation on allegations of discrimination and harassment and action as appropriate based on the outcome of the investigation.</p>
										<p>(5) An investigation and its results will be treated as confidential.</p>
										<p>(6) No employee will be retaliated against for making a complaint in good faith regarding a violation of these rules, or for participating in good faith in an investigation pursuant to these rules.</p>
									</section>
									<section id="drugs_unlawful_substances" class="rulesContent">
										<p><strong>5.</strong>–(1) Employee is prohibited from unlawfully consuming, distributing, possessing, selling, or using controlled substances while on duty.</p>
										<p>(2) Employee may not be under the influence of any controlled substance, such as drugs, while at work, on premises of the organization or engaged in the business of the organization.</p>
										<p>(3) Prescription drugs or over-the counter medications, taken as prescribed by medical professional, are an exception to these Rules.</p>
									</section>
									<section id="counselling" class="rulesContent">
										<p><strong>6.</strong>–(1) Employee is encouraged to bring any workplace concerns or problems they might have or know about to the management.</p>
										<p>(2) Employee is invited to consult the management with regards to any matters that may affect their performance of work, such as training, technical advice or unresolved issues with clients and career progression.</p>
									</section>
									<section id="probation" class="rulesContentTitle">
										<span>Probation</span>
									</section>
									<section id="probationary" class="rulesContent">
										<p><strong>7.</strong>–(1) The probation period is the initial number months of service in the position agreed by employee and the organization in the employment contract.</p>
										<p>(2) New employee under probation can expect to receive work and a training schedule and feedback meetings.</p>
										<p>(3) Written or oral performance expectations or objectives can be expected, as well as meetings to review progress and performance during the probation period.</p>
										<p>(4) New employee under probation who is not performing adequately in a position may be required for extension of the review period or for dismissal from that position at any time before the end of the probation period.</p>
										<p>(5) New employee under probation is not eligible to privileges given by the organization, save privileges granted by applicable law.</p>
									</section>
									<section id="leave" class="rulesContentTitle">
										<span>Leave</span>
									</section>
									<section id="public_holiday" class="rulesContent">
										<p><strong>8.</strong>–(1) Employee is entitled to paid public holidays published by government of applicable state or country of which the employee is assigned to work at.</p>
										<p>(2) Organization has the right to either substitute a public holiday or other rest day for any other day.</p>
									</section>
									<section id="leave_entitlement" class="rulesContent">
										<p><strong>9.</strong>–(1) Upon completion of first three months of service or otherwise communicated by the organization in writing at an earlier date than three months, employee is entitled to various leaves and medical reimbursement..</p>
										<p>(2) All types of leave entitlement will be calculated on the basis of a calendar year, that is from 1 January through 31 December.</p>
										<p>(3) All types of leave shall be pro-rated if the employee has completed less than twelve months of service in a calendar year.</p>
										<p>(4) For the purpose of these rules, any types of leave save vacation leave utilized for lesser than one day will be considered as one day.</p>
										<p>(5) A request for leave may be denied, in particular, if the leave interferes with the operations of the organization, or when the request is too close to the date of leave which leaves the organization insufficient time to prepare for proper arrangement.</p>
										<p>(6) Management has the right to request for documentation from an employee for requests for leave prior to the leave or at the time of returning from the leave.</p>
										<p>(7) Employee who does not report to work at the expiration of a leave or does not request an extension of the leave at least fourteen days before the expiration expresses to the organization that he or she is terminating the employment contract with the organization.</p>
										<p>(8) Employee who is taking any type of leave without prior approval from the management will be considered as absent without notice.</p>
										<p>(9) The organization take absenteeism very seriously and employee who is absent without prior approval for two days will be considered as breaching and terminating their employment contract with immediate effect without notice.</p>
									</section>
									<section id="vacation_leave" class="rulesContent">
										<p><strong>10.</strong>–(1) Pursuant rule 9(1), employee is entitled to days of vacation leave per calendar year according to the employment contract.</p>
										<p>(2) Employee claiming vacation must seek approval from the department manager via HRM system no shorter than fourteen days prior to the date of the leave.</p>
										<p>(3) The manager is to consider the workload of the employee and the organizations together with other consideration prior to her decision in the approval or rejection of the vacation leave applied for.</p>
										<p>(4) Subsequent to approval of vacation leave, employee is allowed to withdraw their application for vacation leave up to one day prior to the actual vacation day.</p>
										<p>(5) All unclaimed vacation leave as of 31st December of that year may be carried forward and utilize not later than 31st March of the next calendar year.</p>
										<p>(6) All unused vacation leave brought forward from previous year will be forfeited subsequent to 31st March unless management issued another temporary directive in writing revising it to another date.</p>
									</section>
									<section id="medical_leave" class="rulesContent">
										<p><strong>11.</strong>–(1) Employee must inform the manager via the HRM system no later than twenty-four hours and inform manager via text within first four hours of the day the employee is unfit to report for duty for medical claim together with medical leave.</p>
										<p>(2) Medical certificate must be presented for medical leave.</p>
										<p>(3) Failure to inform the manager in accordance with rule 11(1) will result in the absenteeism without permission or reasonable excuse.</p>
										<p>(4) The organization must not question the truthfulness of the medical leave taken by the employee unless the frequency of taking medical leaves affect the performance of the employee.</p>
										<p>(5) Where performance is deteriorated because of the medical leave, manager must counsel the employee.</p>
										<p>(6) Entitlement of medical reimbursement pursuant rule 9(1) is capped at $300 per calendar year which be pro-rated if the employee has completed less than twelve months of service in a calendar year.</p>
										<p>(7) The maximum amount per claim for employee is $50.</p>
										<p>(8) Medical reimbursement pursuant rule 9(1) shall cover only fee for consultation and medication prescribed by doctors practicing in the city of which the employee is assigned to work. For avoidance of doubt, dental care and traditional Chinese medication are not covered by medical claim.</p>
										<p>(9) Employee must submit medical claim via HRM system with the proof of payment from the clinic or hospital.</p>
										<p>(10) Manager must assess and make the reimbursement accordingly and promptly.</p>
										<p>(11) Unused medical leave and reimbursement benefits cannot be exchanged for other benefits and cannot be carried forward.</p>
									</section>
									<section id="hospitalization_leave" class="rulesContent">
										<p><strong>12.</strong>–(1) Employee must inform the manager via call or text no later than one week of the day the employee is unfit to report for duty for hospitalization claim together with hospitalization leave.</p>
										<p>(2) Claim and hospitalization leave must be applied via HRM system accompanied with certificate from doctors and bills from hospitals no later than one day from the day the employee resumes work.</p>
										<p>(3) The manager shall understand the condition of the hospitalized employee prior to approval or rejection of the hospitalization leave.</p>
										<p>(4) The number of paid hospitalization leave is capped at sixty days which is inclusive of the medical leave of 14 days. If medical leave has been utilized, the remaining entitlement of hospitalization leave is forty-six days (60 days – 14 days).</p>
										<p>(5) Unused hospitalization claims and leave cannot be exchange for other benefits and cannot be carried forward to the following year.</p>
									</section>
									<section id="maternity_leave" class="rulesContent">
										<p><strong>13.</strong>–(1) Female employee is entitled to maternity leave pursuant rule 9(1) where the employee also:</p>
										<p><span class="rulesNo1">a.</span><span class="pRulesTxt1">is legally married; and</span></p>
										<p><span class="rulesNo1">b.</span><span class="pRulesTxt1">has fewer than two children (excluding the new-born) who has the legal status of a citizen of the country employee is assigned to work in.</span></p>
										<p>(2) A female employee who gives birth to more than one child will also be treated in the same way as an employee who gives birth to a single child.</p>
										<p>(3) Female employee who fulfils the requirements set out in rule 13(1) will be entitled to a total of sixteen weeks of maternity leave.</p>
										<p>(4) Female employee may utilize such leave of up to four weeks before the anticipated date of delivery.</p>
										<p>(5) Last four weeks of maternity leave may be taken flexibly over a twelve months period following the childbirth if a mutual agreement with the organization is arranged.</p>
										<p>(6) On premature delivery, female employee can commence the sixteen weeks of maternity leave from the date of confinement.</p>
										<p>(7) Employee must inform the manager via HRM system at least one week in advance before taking maternity leave.</p>
										<p>(8) Employee is required to inform the manager about her delivery date as soon as practicable.</p>
										<p>(9) Failure to inform the manager pursuant rule 13(7) will result in a halved entitlement to the payment of salary during the maternity leave.</p>
										<p>(10) Any maternity leave that has lapsed pursuant rule 13(4) and (5) cannot be utilized.</p>
										<p>(11) A female employee with stillbirth will also be entitled to twelve weeks of maternity leave.</p>
										<p>(12) Maternity leave pursuant rule 13(1) does not cover abortions or miscarriages.</p>
										<p>(13) Medical expenses incurred in connection with the delivery of the child is not claimable from the organization.</p>
									</section>
									<section id="paternity_leave" class="rulesContent">
										<p><strong>14.</strong>–(1) Male employee is entitled to paternity leave pursuant rule 9(1) where the employee also:</p>
										<p><span class="rulesNo1">a.</span><span class="pRulesTxt1">is legally married; and</span></p>
										<p><span class="rulesNo1">b.</span><span class="pRulesTxt1">the new-born has the legal status as citizen of the countries where employee is assigned to work in.</span></p>
										<p>(2) Male employee who fulfils the requirements set out in rule 14(1) will be entitled to a total of one week of paternity leave.</p>
										<p>(3) Paternity leave can be utilized sixteen weeks after the birth of the child flexibly within twelve months after the birth of the child, if there is mutual agreement with the organization.</p>
										<p>(4) Employee must inform the manager via HRM system at least one week in advance before taking paternity leave.</p>
										<p>(5) Any unused paternity leave will lapse and cannot be utilized.</p>
									</section>
									<section id="childcare_leave" class="rulesContent">
										<p><strong>15.</strong>–(1) Employee is entitled to childcare leave pursuant rule 9(1) where the employee has:</p>
										<p><span class="rulesNo1">a.</span><span class="pRulesTxt1">child below seven years of age; and</span></p>
										<p><span class="rulesNo1">b.</span><span class="pRulesTxt1">the child with legal status of the citizen of the country employee is assigned to work in.</span></p>
										<p>(2) Employee is entitled to six days of childcare every calendar year leave pursuant rule 15(1) until the year where the child turns seven years old.</p>
										<p>(3) Employee is entitled to two days of extended childcare leave every calendar year pursuant rule 9(1) if the youngest child of the employee is a Singapore Citizen aged between seven to twelve.</p>
										<p>(4) Employee must inform the manager via HRM system at least one day in advance before taking childcare leave.</p>
										<p>(5) Unutilized childcare leave cannot be exchange for other benefit and after lapsing it cannot be utilized.</p>
									</section>
									<section id="study_leave" class="rulesContent">
										<p><strong>16.</strong>–(1) Employee is entitled to study leave pursuant rule 9(1) of two days per subject for the purpose of preparation for and the examination of approved institution or programme listed in rule 16(2).</p>
										<p>(2) For the purpose of rule 16(1), the approved institutions are:</p>
										<p><span class="rulesNo1">a.</span><span class="pRulesTxt1">Institute of Singapore Chartered Accountants (ISCA);</span></p>
										<p><span class="rulesNo1">b.</span><span class="pRulesTxt1">Institute of Chartered Accountants of England and Wales (ICAEW);</span></p>
										<p><span class="rulesNo1">c.</span><span class="pRulesTxt1">Association of Chartered Certified Accountants (ACCA);</span></p>
										<p><span class="rulesNo1">d.</span><span class="pRulesTxt1">Malaysia Institute of Accountants (MIA);</span></p>
										<p><span class="rulesNo1">e.</span><span class="pRulesTxt1">Certified Public Accountants of Australia (CPA Australia); and</span></p>
										<p><span class="rulesNo1">f.</span><span class="pRulesTxt1"> Chartered Accountants of Australia and New Zealand (CA ANZ).</span></p>
										<p>(3) Employee must inform the manager at least one month in advance prior to the date of the leave.</p>
										<p>(4) Manager considers the workload and the resources of the organization prior to the approval or rejection of the application.</p>
										<p>(5) Study leave cannot be exchange for other benefits and lapsed study leave cannot be utilized.</p>
									</section>
									<section id="time_off" class="rulesContent">
										<p><strong>17.</strong>–(1) Employee may receive paid time off from work pursuant rule 9(1) of three days to attend a funeral or make funeral arrangements for immediate family members defined in rule 17(2); and one day close relatives defined in rule 17(3).</p>
										<p>(2) For the purpose of rule 17(1), immediate family refers to parent, parent-in-law, spouse, siblings, brothers and sisters-in-law and children, including legally adopted children.</p>
										<p>(3) For the purpose of rule 17(1), close relatives refer to grandparent, uncle, aunt, grandchildren, cousin, nephew and niece.</p>
										<p>(4) Employee must inform the manager via HRM system to take the bereavement leave.</p>
										<p>(5) Employee should provide the manager with evidence of the death and proof of relationship.</p>
										<p>(6) Failure to comply with rule 17(4) and (5) will result forfeiture of bereavement leave, which the leave applied and utilized will be considered as vacation leave.</p>
									</section>
									<section id="urgent_leave" class="rulesContent">
										<p><strong>18.</strong>–(1) The organization does not recognize any urgent leave and will reject all such application by default.</p>
										<p>(2) Application of urgent leave must be accompanied with sufficient justification of the leave being no recourse action and such justification has to be reviewed and approved by the manager in writing.</p>
										<p>(3) The management upon consideration may reject with or without providing reason of which such decision will be final.</p>
										<p>(4) Upon rejection of urgent leave application, employee must report back to work as usual, failure of which will be considered as a breach of employment contract pursuant rule 9(8) and (9).</p>
									</section>
									<section id="working_arrangement" class="rulesContentTitle">
										<span>Working arrangements</span>
									</section>
									<section id="working_hours" class="rulesContent">
										<p><strong>19.</strong>–(1) The scheduled work hour is forty hours per week.</p>
										<p>(2) Each department and office may work on different working time.</p>
										<p>(3) The management has the authority to change the working time that will make up the forty hours week which will be communicated in writing where employee is required to oblige.</p>
										<p>(4) Employee is to take one hour of lunch for working time longer than four hours or one hour of lunch and one hour of dinner if the working hour is beyond eight hours a day.</p>
										<p>(5) Lunch and/or dinner time is not considered as part of the forty hours week working hours.</p>
										<p>(6) Employee is not allowed to shorten or eliminate lunch and/or dinner periods to alter the beginning or ending of a workday.</p>
										<p>(7) Employee may retire early for the day for feeling unwell after permission is granted by the management either verbal or in writing.</p>
										<p>(8) Employee may be required to work beyond the normal working hours to discharge duties of the employee at the sole discretion of the management.</p>
										<p>(9) Leave maybe taken in lieu of overtime worked on the discretion of the management, of which overtime worked must be verified and justified.</p>
										<p>(10) Where overtime is claimed, it must be reflected in timesheet, which forms the basis for the organization in performance review of why jobs require extra time to complete.</p>
										<p>(11) When visiting client for engagement, employee must reach client’s premise according to the working hour.</p>
										<p>(12) For avoidance of doubt of rule 19(11), employee is not to leave office or any place in the beginning of working hour unless such is the request from the client or the management.</p>
									</section>
									<section id="place_of_work" class="rulesContent">
										<p><strong>20.</strong>–(1) The main place of work is stated in the employment contract.</p>
										<p>(2) The place of work may be subject to change, according to the commercial needs of the business.</p>
										<p>(3) Employee is expected to make herself available at any work location arranged by the organization which may be different from the employment contract.</p>
										<p>(4) Nature of the work may require employee to travel domestically and internationally where employee is expected to be available on such arrangement.</p>
									</section>
									<section id="residence" class="rulesContent">
										<p><strong>21.</strong>–(1) Employee is expected during the working days, to reside in the country where the main place of work is, pursuant rule 20(1).</p>
										<p>(2) Exception is deemed to be granted to rule 21(1) where the employee is required to travel to other cities by the management.</p>
									</section>
									<section id="attire_code" class="rulesContent">
										<p><strong>22.</strong>–(1) Employee is required to follow professional dress attire from Monday to Thursday.</p>
										<p>(2) For the purpose of rule 22(1) men are expected to wear formal long sleeve shirt with tie whereas women are expected to put on formal shirt and pantsuit or skirt.</p>
										<p>(3) On Friday, employee may wear business casual attire subject to rule 22(4) and (5).</p>
										<p>(4) When employee is visiting or meeting client, employee is expected to dress in a professional manner on any day.</p>
										<p>(5) Slippers are strictly prohibited in the workplace from Monday to Friday.</p>
									</section>
									<section id="remuneration_and_benefits" class="rulesContentTitle">
										<span>Remuneration and benefits</span>
									</section>
									<section id="fixed_and_variables_payment" class="rulesContent">
										<p><strong>23.</strong>–(1) Employee is entitled to fixed and variable payment stated in employment contract minus compulsory deductions required by the applicable law in the country employee is assigned to work on.</p>
										<p>(2) Employee is entitled to obtain pay slip that shows fixed and variable pay.</p>
										<p>(3) Payment of fixed and variable remuneration are made on the twenty-seventh day of the same month is known as pay day.</p>
										<p>(4) Further to rule 23(3), if the pay day falls on rest day, payment will be made on the first working day subsequent to the pay day.</p>
										<p>(5) Salaries and other variable payment will be paid to employee by cheque or electronic transfer directly to the bank account of the employee.</p>
										<p>(6) Employee must notify the manager if the payment of salaries appears to be inaccurate or if it has been misplaced.</p>
										<p>(7) The manager may charge a replacement fee for any lost pay cheque.</p>
										<p>(8) The management has the power to alter the method or intervals of payment within the context of applicable law.</p>
										<p>(9) Personal tax of the employee is the obligation of the employee and the organization is not obligated to pay any personal tax of employee.</p>
										<p>(10) Information about salaries of each employee is confidential and should not be shared with other employee within the organization.</p>
									</section>
									<section id="salaries_calculation" class="rulesContent">
										<p><strong>24.</strong>–(1) For the purpose of calculating salary in rule 23(1), a ‘month’ or ‘complete month’ refers to any one of the months in the calendar year.</p>
										<p>(2) An incomplete month of work is one where an employee starts work after the first day of the month; or leaves employment before the last day of the month; or takes no-pay leave of one day or more during the month; or is on reservist training during the month.</p>
										<p>(3) Salary payable on a monthly basis is calculated using the monthly gross of pay pro-rated by the total number of days the employee actually worked in that month.</p>
										<p>(4) The monthly gross pay includes allowances payable to an employee, excluding bonus payments, any sum paid to you for any reimbursement of special expenses incurred, productivity incentive payments and travelling, food or housing allowances.</p>
										<p>(5) Final salary payment may be made by cheque on last working day and subject to the return of any property owned by the organization.</p>
										<p>(6) The organization has the right to make deductions from the salary for any monies owed to the organization and in the event of termination, all monies will become immediately payable to the organization.</p>
										<p>(7) The organization has the right to make a deduction from final payment of salary for any sums that are due to the organization at the time employee is leaving.</p>
										<p>(8) The following deductions may be made:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Deductions for absence from work;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Deductions for damage to or loss of goods expressly entrusted to you for custody or for loss of money for which you are required to account, where the damage or loss is directly attributable to your neglect or default;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Deductions for the actual cost of meals supplied by the organization on your request;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Deductions for any housing accommodation supplied by the organization;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Deductions for such amenities and services supplied by the organization;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Deductions for the recovery of advances or loans or for the adjustment of over-payments of salary;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">Deductions of contributions payable by the organization on behalf of the employee;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">h.</td>
													<td class="tableRulesRow">Deductions made with the written consent of the employee and paid by the organization to any cooperative society registered under any written law for the time being in force in respect of subscriptions, entrance fees, instalments of loans, interest and other dues payable by the employee to such society;
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="variable_scheme" class="rulesContent">
										<p><strong>25.</strong>–(1) The organization may operate a discretionary (non-contractual) variable scheme.</p>
										<p>(2) Payments from any variable schemes in the organization are based on the organization achieving its targeted profits and employee achieving any personal targets or objectives as set by the management.</p>
										<p>(3) All schemes are subject to change or withdrawal by the organization without notice or compensation.</p>
										<p>(4) In order to receive a payment under the variable scheme defined in rule 25(1), employee must be employed by the organization and employee has not tendered resignation on the date on which bonus payments are made.</p>
									</section>
									<section id="training" class="rulesContent">
										<p><strong>26.</strong>–(1) The organization offers a wide range of professional development opportunities through which employee can enhance their workplace skills and knowledge.</p>
										<p>(2) Educational development is encouraged and supported across the organization and offers workshops and coaching throughout the calendar year at the discretion of the management.</p>
										<p>(3) Training and workshops conducted by approved institutions listen in rule 16(1) are supported by the organization capped at the amount stated in the employment contract.</p>
										<p>(4) Employee must obtain approval from the manager in writing prior to enrolling to any training or workshops.</p>
										<p>(5) Manager has the right to reject the selection of trainings or workshops by employee without the need to give reason for rejection.</p>
										<p>(6) Employee who attends training or workshops approved by the manager will be considered as working for the day and no leave will be deducted from the employee.</p>
										<p>(7) Training fee cannot be exchanged for other benefit and cannot be carried forward.</p>
									</section>
									<section id="support_on_courses" class="rulesContent">
										<p><strong>27.</strong>–(1) The organization can support course and examination fee for business related courses on a condition that employee agrees to stay employed with the organization for a period of six months for each subject that supported by the organization.</p>
										<p>(2) For the purpose of rule 27(1), the six months commence on the first day of the following month after the completion of the course that employee attends (thereafter “bond period”).</p>
										<p>(3) Employee must compensate an amount equivalent to salary over the remaining bond period shall the employee terminates employment with the organization during the bond period.</p>
										<p>(4) Prior to registration of courses, employee is required to seek approval from the management where a separate bond agreement will be executed with the employee.</p>
									</section>
									<section id="meal_and_transport_claims" class="rulesContent">
										<p><strong>28.</strong>–(1) In discharging duties of employee under rule 20(4), the organization provides daily meal allowance to employee of variable amount depending on the cities to be visited.</p>
										<p>(2) For the purpose of rule 28(1), the allowance is given if the employee works under rule 20(4) for minimal of six hours a day.</p>
										<p>(3) Employee claiming such allowance must submit the claim via secretary system within one week after returning to main place of work.</p>
										<p>(4) Travelling intra cities claim are to be approved by manager prior to purchase of the ticket and accommodation.</p>
										<p>(5) Failure to obtain prior approval may result in claim being rejected.</p>
										<p>(6) In visiting client’s premise for audit, every employee must meet in nearest train station and take private hire or cab together from the station if client’s premise is not reachable by public transport.</p>
										<p>(7) Other than first and last day of visit to client’s premise where private hire or grab can be used, public transport is to be used.</p>
										<p>(8) When manager is assessing approval for transport claim, manager shall consider approve only ONE private hire or cab claim of the same client on the same day each way from and to the station.</p>
										<p>(9) Employee may claim cab fare from office to home after 10pm if the employee is working on an urgent job that needs to meet deadline other than due to employee’s lack of productivity in completing the job.</p>
										<p>(10) In claiming the fare in rule 28(9), employee must first seek prior approval from manager.</p>
									</section>
									<section id="conduct_and_expectation" class="rulesContentTitle">
										<span>Conduct and expectation</span>
									</section>
									<section id="timesheet" class="rulesContent">
										<p><strong>29.</strong>–(1) Employee is required to report accurately all work hours in the Timesheet.</p>
										<p>(2) Employee must complete number of hours spent on assigned cases and tasks performed on the workday in the timesheet and submit the timesheet to the manager on monthly basis.</p>
										<p>(3) Submission of timesheet should be made via HRM system no later than the third working day of the following month.</p>
										<p>(4) Failure to submit on the time stipulated in rule 29(3) will result in auto submission of the timesheet where number of hours that do not match the working days will be considered as idle, which will be taken into consideration on performance review.</p>
									</section>
									<section id="professional_conduct" class="rulesContent">
										<p><strong>30.</strong>–(1) The organization expects every employee to act in a professional manner.</p>
										<p>(2) Employee should attempt to achieve their job objectives, and act with diligence in accordance with applicable technical and professional standards at all times.</p>
										<p>(3) Good time management is essential for completing jobs assigned which is expected from employee.</p>
										<p>(4) For the purpose of rule 30 (3), if employee requires additional time to complete given tasks, employee shall inform the manager of the situation as soon as it became apparent that jobs assigned cannot be completed on time.</p>
										<p>(5) Every employee is expected to be attentive, in particular when conversing with the management and/or client.</p>
										<p>(6) Forgetfulness is not a privilege for omission of tasks and necessary remedial action must be taken to overcome such shortcomings by taking note among others.</p>
										<p>(7) Employee shall consult the manager, seniors or previous year’s person in charge to understand about the assigned jobs or other technical matters to avoid confusion.</p>
										<p>(8) Every employee is expected to read and listen information conveyed by any party in full and not in part to ensure there is no omission in the understanding of the subject being read.</p>
										<p>(9) Employee is not allowed to perform any duty apart from those assigned by the organization or those that relates to the business of the organization during working hour.</p>
									</section>
									<section id="health_and_safety" class="rulesContent">
										<p><strong>31.</strong>– During employment with the organization, employee must follow the safe working procedures and principles introduced:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Employee must not engage in any unsafe act that may endanger the employee or others working around the employee; and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Employee must not engage in any negligent acts that may endanger any person.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="performace_assessment" class="rulesContentTitle">
										<span>Performance assessment</span>
									</section>
									<section id="appraisal" class="rulesContent">
										<p><strong>32.</strong>–(1) The Employee will be provided with a performance appraisal at least once per year which will be reviewed at which time all aspects of the assessment can be fully discussed.</p>
										<p>(2) All performance reviews are based on merit, achievement and other factors which include but are not limited to:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Quality of work;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Attitude;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Knowledge of work;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Attendance and punctuality;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Compliance with company policy;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Improvement;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">Acceptance of responsibility;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">h.</td>
													<td class="tableRulesRow">Productivity and time management; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">i.</td>
													<td class="tableRulesRow">Constructive feedback.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(3) Pay review usually follows subsequent to performance review in accordance with provision of rule 32(1) at discretion of the management.</p>
										<p>(4) Employee should note that a performance review does not guarantee a pay increase or promotion.</p>
									</section>
									<section id="disciplinary_ground" class="rulesContentTitle">
										<span>Disciplinary ground</span>
									</section>
									<section id="displinary_actions" class="rulesContent">
										<p><strong>33.</strong>–(1) Disciplinary ground is a ground for disciplinary action to be taken against employee.</p>
										<p>(2) For the purpose of rule 33(1), the following actions are unacceptable and considered grounds for disciplinary action:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Possessing, distributing or being under the influence of illicit controlled substances;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Being under the influence of a controlled substance at work, on premises, or while engaged in business of the organization;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Unauthorized use of organization’s property, equipment, devices or assets;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Damage, destruction or theft of property, equipment, devices or assets of the organization;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Removing property belongs to the organization without prior authorization;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Disseminating company information without authorization;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">Falsification, misrepresentation or omission of information, documents or records;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">h.</td>
													<td class="tableRulesRow">Lying;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">i.</td>
													<td class="tableRulesRow">Insubordination or refusal to comply with directives;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">j.</td>
													<td class="tableRulesRow">Failing to adequately perform job responsibilities;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">k.</td>
													<td class="tableRulesRow">Excessive or unexcused absenteeism or tardiness;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">l.</td>
													<td class="tableRulesRow">Disclosing confidential or proprietary company information without permission;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">m.</td>
													<td class="tableRulesRow">Illegal or violent activity;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">n.</td>
													<td class="tableRulesRow">Falsifying injury reports or reasons for leave;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">o.</td>
													<td class="tableRulesRow">Possessing unauthorized weapons on premises;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">p.</td>
													<td class="tableRulesRow">Disregard for safety and security procedures;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">q.</td>
													<td class="tableRulesRow">Disparaging or disrespecting management and/or co-workers; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">r.</td>
													<td class="tableRulesRow">Any other action or conduct that is inconsistent with these rules, or expectations;
													</td>
												</tr>
											</tbody>
										</table>
										<p>(3) Manager must perform investigation to every cause of disciplinary ground and counsel the employee of the severity of the disciplinary ground and plan for remedial action.</p>
										<p>(4) Counseling under rule 33(3) shall be documented by the manager in personnel file.</p>
										<p>(5) Disciplinary action as result of rule 33(2) may take the form of oral warnings, written warnings, probation, suspension, demotion, discharge, removal or some other disciplinary action, in no particular order.</p>
										<p>(6) The course of action will be determined by the manager as it deems appropriate.</p>
										<p>(7) Written warning pursuant rule 33(5) will be given to employee if severe mistake in the action or non-action by the employee.</p>
										<p>(8) Three written warning pursuant rule 33(5) will amount to immediate termination of employment with sufficient cause.</p>
									</section>
									<section id="termination" class="rulesContentTitle">
										<span>Termination</span>
									</section>
									<section id="termination_with_notice" class="rulesContent">
										<p><strong>34.</strong>–(1) Both the organization and employee may terminate the employment contract at any time during the employment by giving the other party sufficient notice period.</p>
										<p>(2) During the probationary period, either party may terminate the contract by giving two week's written notice or the equivalent of two week's salary in lieu of notice.</p>
										<p>(3) Subsequent to confirmation of employment, the either party may terminate the employment without the requirement to show sufficient cause, by giving at least one month written notice during off peak season or two months during peak season or payment in lieu of notice.</p>
										<p>(4) For the purpose of rule 34(3), peak season runs right after Lunar New Year through August, whereas off peak season starts on September and ends on Lunar New Year.</p>
										<p>(5) For the purpose of rule 34(2) and (3), payment in lieu is not subject to provident fund contribution.</p>
										<p>(6) Any unconsumed annual leave cannot be used to offset the notice period for the termination of this contract and employee would only be paid till the last day of work and the annual leave would not be available for encashment.</p>
										<p>(7) Employee is not allowed to work with another employer before the date of termination while serving notice period, unless there is written permission from the management allowing you to do so.</p>
										<p>(8) A deduction will not exceed the amount of the damages or loss caused to the organization by neglect or default of an employee, and except with the permission of the director shall in no case exceed of one month’s wages.</p>
									</section>
									<section id="termination_without_notice" class="rulesContent">
										<p><strong>35.</strong>– The organization may terminate the employment without giving notice, and employee must pay the organization salary-in-lieu of notice if the employment is terminated due to:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Absence from work continuously for more than two working days without approval or good excuse;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Absence for work continuously for more than two working days without informing or attempting to inform the organization of the reason for absence.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="misconduct" class="rulesContent">
										<p><strong>36.</strong>– The organization may terminate the employment without giving notice, and employee must pay the organization salary-in-lieu of notice if the employment is terminated due to misconduct listed in rule 33(2) and absence from work listed in rule 35.</p>
									</section>
									<section id="property_or_organization" class="rulesContentTitle">
										<span>Property of organization</span>
									</section>
									<section id="property_and_usage" class="rulesContent">
										<p><strong>37.</strong>–(1) Property of the organization includes the equipment, telephones, computers, and software, data, files, information among others must be used for business purpose and strictly not for personal use.</p>
										<p>(2) Property of the Company are to be used for business purposes and/or for communicating between employee, clients and other third parties on professional basis.</p>
										<p>(3) Employee may access the Internet and email systems to send or receive small number of personal mails, but should be done, as far as possible, outside normal business hours.</p>
										<p>(4) The use of email or the internet for accessing, receiving or distributing material that is sexist, pornographic, culturally insensitive, racist or otherwise offensive is not permissible.</p>
										<p>(5) Any employee breaching requirement of rule 37(4) or misuse the email system (for example, by tampering or introducing viruses), may result in disciplinary action, including removal of access to e-mail or internet access rights, or termination of employment or getting court order to indemnify loss caused to the organization as result of such action.</p>
										<p>(6) The management has the right to monitor, filter and block the content of your emails and your use of the internet, including personal emails to ensure that employee does not breach the provision of rule37(4).</p>
										<p>(7) Files or programs stored on computers and/or other storage of the organization may not be copied for personal use.</p>
										<p>(8) Employee is not permitted to download any “pirated” software, files or programs and must receive permission from the manager before installing any new software on a property of organization.</p>
										<p>(9) The management has the right to monitor the data stored in equipment supplied to employee does not breach rule 37(7) and (8).</p>
										<p>(10) Phones are provided for business use, that employee should not receive personal calls while on duty unless it is urgent, which such personal calls should be kept to a minimum and conversations brief.</p>
										<p>(11) For the purpose of rule 37(10), personal long-distance calls are not permitted.</p>
										<p>(12) Hardcopy files are the property of the organization, which is made available to the employee for the benefit of references and is expected to be handled with care for the benefit of the next users.</p>
										<p>(13) All paper files must be indexed with the client code and arranged in the cabinet according to the sequence of the client code.</p>
										<p>(14) All hardcopy files must be returned to the cabinet at the end of every day before going off to allow others to use when the employee has retired for the day and not to leave the files in any other places save the application of rule 37(15).</p>
										<p>(15) Exception is made to rule 37(14) in emergency situation where employee is working outside office location where the hardcopy files are stored and where employee is visiting the premise of client for work purpose.</p>
										<p>(16) Every employee is expected to keep their table clean and tidy before going off from the office every day.</p>
										<p>(17) Office premise other than meeting room is off limit to any person other than the employee and the management of the organization.</p>
										<p>(18) Other person accessing office premise must be accompanied by the management or prior written approval has been sought from the management.</p>
										<p>(19) Any employee breaching rule 37(18) will be penalized of $400 per entry.</p>
										<p>(20) Any employee that does not notify management of the breach committed by other employee under rule 37(19) will be equally guilty of the breach and a penalty of $400 will be sanctioned.</p>
										<p>(21) Upon the termination of the employment employee shall return to the organization all documents, records, items, materials and equipment in the possession or custody of employee belonging to the organization or its clients and employee shall not retain any copies (including electronic or soft copies) thereof.</p>
									</section>
									<section id="intellectual_property" class="rulesContentTitle">
										<span>Intellectual property</span>
									</section>
									<section id="ownership_of_intellectual_property" class="rulesContent">
										<p><strong>38.</strong>–(1) All intellectual property, software, systems, structures and processes being used or designed by the employee during the course of employment with the organization in relation to the projects and applications, and all patents, designs, copyright and other artistic, commercial or intellectual property rights covering the same, are the absolute property of the organization or its clients.</p>
										<p>(2) At the expense of the organization, employee will do all things necessary to ensure intellectual properties set out in rule 38(1) and any inventions remain the property of the organization.</p>
									</section>
									<section id="privacy" class="rulesContentTitle">
										<span>Privacy</span>
									</section>
									<section id="privacy_in_the_organization" class="rulesContent">
										<p><strong>39.</strong>–(1) The management has the right to access all property belonging to the organization including computers, desks, file cabinets, storage facilities, and files and folders, electronic or otherwise, at any time.</p>
										<p>(2) All documents, files, voicemails and electronic information, including e-mails and other communications, created, received or maintained on or through organization property are deemed to be the property of the organization and employee should have no expectation of privacy over those files or documents.</p>
									</section>
									<section id="confidentiality" class="rulesContentTitle">
										<span>Confidentiality</span>
									</section>
									<section id="managing_confidentiality" class="rulesContent">
										<p><strong>40.</strong>–(1) Examples of confidential information include information relating to existing and prospective clients, client information on past and future film releases, client artwork and imagery, profit margins, security arrangements for the office, and contact details for clients, brands and anyone else associated with the business. This list is not exhaustive.</p>
										<p>(2) To protect the business of the organization and its clients, employee is expressly forbidden, either during or after the employment, to disclose any confidential information relating to the organization or its clients either verbally or in writing to any person or company, or make use of any such information, without the prior written consent of a Director of the organization.</p>
										<p>(3) Employee shall not without prior written consent of the organization destroy, make copies, duplicate or reproduce in any form the confidential information of the organization.</p>
										<p>(4) Rule 40(2) and (3) shall not affect the common law rights of the organization and the organization may seek adequate compensation and an injunction if this obligation is not fulfilled.</p>
									</section>
									<section id="conflict_of_interest" class="rulesContentTitle">
										<span>Conflict of interest</span>
									</section>
									<section id="managing_conflict_of_interest" class="rulesContent">
										<p><strong>41.</strong>–(1) During the employment with the organization, employee shall not be concerned or interested directly or indirectly, whether solely or with others in any trade, business or occupation, which competes with the interests of the organization or has the potential of causing a conflict of interest, without the prior written permission of the management.</p>
										<p>(2) Notwithstanding rule 40(1), the organization does not prohibit the employee the right to hold shares, securities or debentures in any other company as a bona fide investor.</p>
										<p>(3) Before engaging in any other employment outside of the organization when the employee is still in the employment with the organization, employee should obtain written permission from the management.</p>
										<p>(4) Permission is given pursuant rule 41(3) on the sole discretion of the management.</p>
										<p>(5) If permission pursuant rule 41(4) is given for employee to engage in other employment, and the total amount of hours worked (by combining all paid working hours) shall not exceed 48 hours per week.</p>
										<p>(6) Permission pursuant rule 41(4) may be withdrawn if the ‘other’ employment interferes or affects, in any way, the ability to effectively carrying out duties, or causes a conflict of interest.</p>
										<p>(7) The organization will not permit any employee under any circumstances, to undertake private work for clients of the organization.</p>
										<p>(8) Anyone found to be in breach of rule 41(7) will be dealt with through the disciplinary action set out in rule 33(5).</p>
										<p>(9) Employee is required to immediately advise the management if the employee is approached by a client or prospective client in respect to private work.</p>
										<p>(10) Employee is not permitted to accept any financial payments or payments in kind from clients or suppliers.</p>
										<p>(11) Any employee receiving a gift from a client or supplier, these must not be received at your home address, must be declared to the management and acknowledged on organization letterhead.</p>
										<p>(12) Employee found in breach of rule 41(10) and (11) will be investigated by the management and may result in disciplinary action taken against the employee.</p>
									</section>
									<section id="solicitation" class="rulesContentTitle">
										<span>Solicitation</span>
									</section>
									<section id="restriction_on_solicitation" class="rulesContent">
										<p><strong>42.</strong>– Employee is bound during the employment and for a period of twenty-four months following termination:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Not to approach, solicit or entice away (or endeavor to do so) either directly or indirectly any clients or brand partners of the organization with whom employee is actively concerned or were actively concerned during the twenty-four months prior to the termination of the employment, whether by the employee or with or on behalf of any person, firm or company, or by acting through others;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Not to solicit or entice away (or endeavor to do so) any employee of the organization who holds a management, sales, account management or technical position, whether by the employee or with or on behalf of any person, firm or company, or by acting through others;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Not to prevent or seek to prevent any person or company who is or was a supplier to the organization from supplying goods or services to the organization.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="public_appearance" class="rulesContentTitle">
										<span>Public appearance</span>
									</section>
									<section id="restriction_on_public_appearance" class="rulesContent">
										<p><strong>43.</strong>–(1) Employee expressly forbidden, either during or after the employment:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">to directly or indirectly publish any opinion, fact or material on any matter connected with or relating to the business of the organization or client of the organization without the prior written approval of the management;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">To make any public appearances or comments to the press on any matter connected with or relating to the business of the organization or client of the organization without the prior written approval of the management.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(2) Any requests for comments, opinions or public appearances should be referred to the management.</p>
									</section>
									<section id="personal_data_protection" class="rulesContentTitle">
										<span>Personal data protection</span>
									</section>
									<section id="safeguarding_data_collected" class="rulesContent">
										<p><strong>44.</strong>–(1) The organization will make reasonable security arrangements to protect your personal data by preventing unauthorized access, collection, use, disclosure or similar risks over the following:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Full name and residential address</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">National Registration Identification Card (NRIC) number or Foreign Identification Number (FIN)
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Passport details (including passport number, photograph or video image of an individual)
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Mobile telephone number
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Personal email address
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Thumbprint
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">DNA profile
													</td>
												</tr>
											</tbody>
										</table>
										<p>(2) The organization will not provide an individual access to an employee’s personal data if the provision of the data or other information could reasonably be expected to:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">cause immediate or grave harm to the individual’s safety or physical or mental health;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">threatens the safety or physical or mental health of another individual;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">reveals personal data about another individual;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">reveals the identity of another individual who has provided the personal data, and the individual has not consented to the disclosure of his or her identity; or
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">be contrary to national interest.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(3) Provision of rule 44(1) and (2) will be exempted if there is a court order or instruction from applicable law in releasing such information.</p>
										<p>(4) The management shall design appropriate safeguards and ensure the safety of personal information set out in rule 44(1) and (2).</p>
									</section>
									<section id="quality_management" class="rulesContentTitle">
										<span>PART II - QUALITY MANAGEMENT</span>
									</section>
									<section id="objective" class="rulesContentTitle">
										<span>Objectives</span>
									</section>
									<section id="objective_of_quality_control" class="rulesContent">
										<p><span><strong>45.</strong>–(1) The main objective is to establish, implement, maintain, monitor, and enforce a quality control system that meets, as a minimum, the requirements of the International Standard on Quality Control (ISQC) 1.</span></p>
										<p>(2) The quality control system is intended to provide the organization with reasonable assurance that the organization complies with professional standards and applicable legal and regulatory requirements, and that engagement reports issued are appropriate in the circumstances.</p>
									</section>
									<section id="conformity_of_standard_on_quality_control" class="rulesContent">
										<p><strong>46.</strong>– Every person in the organization, including the management and the employee is required to conform to the following guidelines:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Treating ethical behavior and quality of service as priority;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Identifying and managing threats to independence;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Complying with continuing professional development;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Remaining abreast of current developments in the profession;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Providing assistance when needed, to learn through shared knowledge;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Keeping time records to track and identify time spent on engagement;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">Keeping organization and client information secure and confidential;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">h.</td>
													<td class="tableRulesRow">Informing a partner or manager of observations of significant breaches in organization QC;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">i.</td>
													<td class="tableRulesRow">Documenting and maintaining appropriate records of all significant client contacts when professional advice is given or requested;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">j.</td>
													<td class="tableRulesRow">Following organization’s standard practices for work hours, attendance, administration, meeting deadlines, and quality control.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="leadership" class="rulesContentTitle">
										<span>Leadership</span>
									</section>
									<section id="leadership_in_organization" class="rulesContent">
										<p><span><strong>47.</strong>–(1) The management is given the authority to decide on all key matters regarding the organization and its professional practice and shall be responsible for leading and promoting quality assurance with necessary practical aids and guidance to support engagement quality.</span></p>
										<p>(2) The management will designate qualified employee with sufficient and appropriate experience and ability to be responsible for the elements of the quality control system.</p>
										<p>(3) Any individuals who take on responsibilities and duties in rule 47(2) shall be given necessary authority to carry out their responsibilities.</p>
									</section>
									<section id="ethical_requirement" class="rulesContentTitle">
										<span>Ethical requirements</span>
									</section>
									<section id="adherence_to_ethical_requirements" class="rulesContent">
										<p><span><strong>48.</strong>–(1) Management and employee must be independent both of mind and in appearance of their assurance clients.</span></p>
										<p>(2) If threats to independence cannot be eliminated or reduced to an acceptable level, the relationship that is creating the threat must be terminated; or reject the continuance of the engagement.</p>
										<p>(3) The management must investigate and assess all factors that give rise to condition set out in rule 48(2) prior to making its decision.</p>
										<p>(4) The management is responsible for the implementation and enforcement of these rules designed to assist all employee in identifying and managing independence threats.</p>
										<p>(5) Each engagement partner shall provide the organization with relevant information about their client engagements, including the scope of services, to enable the organization to evaluate the overall impact on independence requirements.</p>
										<p>(6) Employee assigned to an assurance engagement shall confirm to the engagement partner of his/her independence.</p>
										<p>(7) The engagement partner shall take reasonable actions that are necessary and possible to eliminate or reduce any independence threat to an acceptable level through the application of appropriate safeguards.</p>
										<p>(8) For the purpose of rule 48(7), these actions include:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Replacing a member of the engagement team;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Ceasing work or services performed in an engagement; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Ceasing or altering nature of relationships with clients.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="acceptance_and_continuance" class="rulesContentTitle">
										<span>Acceptance and continuance</span>
									</section>
									<section id="acceptance_and_continuance_practice" class="rulesContent">
										<p><span><strong>49.</strong>–(1) The organization shall only accept new engagements or continue existing engagements where it has the capabilities to do so and is able to conclude that both organization and the clients can comply with ethical requirements.</span></p>
										<p>(2) The management must evaluate the clients prior to acceptance or continuance of engagement pursuant rule 49(1).</p>
										<p>(3) The evaluation process below shall be performed for new clients:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Assessment of the risks associated with the client; and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Inquiry of appropriate personnel and third parties.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(4) When accepting a new client, the organization shall obtain clearance letter from the former auditors unless the client has not existing auditors.</p>
										<p>(5) In finalizing the acceptance of new client, the manager shall prepare an engagement letter setting out the terms of engagement which is to be acknowledged by the new client.</p>
										<p>(6) For each ongoing engagement, a documented client continuance review will be required from the management to determine whether it is appropriate to continue providing the client with services.</p>
										<p>(7) Only the board of directors of the organization has the authority to withdraw from the engagement having considered all the evidence presented to the board by the engagement partner and/or the manager.</p>
										<p>(8) If withdrawal is consequently considered appropriate, the management will document the significant matters which led to the withdrawal.</p>
										<p>(9) The management must consider whether it has a professional, regulatory or legal obligation to report the withdrawal of the engagement pursuant rule 49(8) to any relevant authorities.</p>
									</section>
									<section id="human_resources" class="rulesContentTitle">
										<span>Human resources</span>
									</section>
									<section id="allocation_of_human_resources" class="rulesContent">
										<p><span><strong>50.</strong>–(1) The manager shall consider the academic and professional credentials and check references for credit and criminal records when organization is seeking candidates for employment.</span></p>
										<p>(2) All employee must meet the minimum continuing professional development requirements.</p>
										<p>(3) The management shall ensure that the required training has been undertaken by employee and appropriate actions to address any shortfalls.</p>
										<p>(4) The management shall ensure the assignment of appropriate partners and employee to each engagement.</p>
										<p>(5) Engagement partner will allocate enough time to assume overall responsibility for performing the engagement according to professional standards and applicable regulatory and legal requirements.</p>
									</section>
									<section id="engagement_performance" class="rulesContentTitle">
										<span>Engagement performance</span>
									</section>
									<section id="professional_standards_performance" class="rulesContent">
										<p><span><strong>51.</strong>–(1) Every engagement must be performed according to professional standards and applicable regulatory and legal requirements.</span></p>
										<p>(2) The organization provides sample working paper templates for documenting the engagement process for clients, which are frequently updated to reflect any changes in professional standards.</p>
										<p>(3) For the purpose of rule 51(2), the manager is responsible for updating the template.</p>
										<p>(4) Supervision and review responsibilities shall be determined by the management on the basis that the work of less experienced team members is reviewed by more experienced engagement team members.</p>
										<p>(5) The engagement partner, being leader of the engagement team is responsible for signing the engagement report and:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Forming a conclusion on compliance with requirements, identifying threats, taking action to reduce or eliminate such threats;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Ensuring that the engagement team has the appropriate competence to perform the engagement;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Communicating to key members of the client’s management and those charged with governance;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Ensure that sufficient appropriate evidence has been obtained to support the conclusions reached and for the engagement report to be issued; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Undertaking appropriate consultation on difficult or contentious matters.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(6) Consultation among the engagement team is encouraged to promote the quality of engagement performance.</p>
										<p>(7) For contentious issue identified during planning or throughout the engagement, the engagement partner shall ensure consultation takes place.</p>
										<p>(8) All employee must be willing to assist each other in dealing with and reaching conclusions on problems encountered during engagement.</p>
									</section>
									<section id="monitoring" class="rulesContent">
										<p><span><strong>52.</strong>–(1) The management shall designate responsibility for the monitoring process to a person with sufficient experience and authority (“the monitor”).</span></p>
										<p>(2) The purpose of the monitoring program is to assist the organization in obtaining reasonable assurance that its rules relating to the system of quality control are relevant, adequate and operating effectively.</p>
										<p>(3) Monitoring of quality control system shall be completed on a periodic basis.</p>
										<p>(4) As part of the monitoring program, the management may inspect a selection of individual engagements without prior notification to the engagement team.</p>
										<p>(5) The management may instruct the monitor to prepare an evaluation of whether our organization has appropriately applied quality control rules and regulations; adherence to professional standards; evaluation of whether the engagement report is appropriate; identification of deficiencies and summary of results and conclusion reached.</p>
										<p>(6) Findings report must include a detailed description of the procedures performed and the conclusions drawn from the review.</p>
										<p>(7) From the finding reports in rule 52(6), monitor shall provide recommendations to address reported deficiencies that focuses on addressing the underlying reasons for those deficiencies.</p>
										<p>(8) If it appears that the organization has issued an inappropriate engagement report, the management shall determine what further actions are appropriate in order to comply with professional standards and regulatory and legal requirements.</p>
										<p>(9) Non-compliance with quality control system is a serious matter, particularly if a partner or employee have deliberately refused to comply with these rules.</p>
										<p>(10) Deliberate non-compliance will be addressed by instituting a plan to improve performance; performance reviews and reconsideration of opportunities for promotion and increased compensation; and ultimately termination of employment.</p>
										<p>(11) Any complaint received from a client or other third party must be responded by the manager to at the earliest practical moment, with an acknowledgement that the matter is being attended to, and that a response will be forthcoming after it has been appropriately investigated.</p>
										<p>(12) If the investigation carried out in accordance with rule 52(11) reveals deficiencies in the design or operation of quality control rules and regulations or non-compliance with system of quality control by one or more individuals, the manager shall take appropriate remedial action.</p>
									</section>
									<section id="documentation" class="rulesContentTitle">
										<span>Documentation</span>
									</section>
									<section id="adequacy_of_documentation" class="rulesContent">
										<p><span><strong>53.</strong>–(1) Engagement documentation shall include:</span></p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Engagement planning checklist or memorandum;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Issues with respect to ethics requirements;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Compliance with independence;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Conclusions reached with respect to acceptance and continuance of client relationship;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Procedures performed to assess the risk of material misstatement;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Nature, timing, and extent of procedures performed in response to assessed risks;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">Conclusion that sufficient, appropriate audit evidence has been accumulated and evaluated, and supports the report to be issued; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">h.</td>
													<td class="tableRulesRow">File closing, including appropriate sign-off.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(2) Engagement documentation of any kind must be retained for a period of no less than five years to allow those performing monitoring.</p>
										<p>(3) For the purpose of rule 53(2), five years period commence from the date of the:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">financial statements signed for assurance and compilation engagement for such financial year end;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">final notice of assessment for tax engagement of that year of assessment;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">date of AGM where accounts for that financial year is being tabled; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">date of termination of service for other documents.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(4) The manager shall design a system to protect all working papers, reports, and other documents prepared, including client-prepared worksheets, from unauthorized access.</p>
										<p>(5) Our working papers and other documents are not available for review to any party unless prior written approval is given by the director or it is required by the applicable law.</p>
										<p>(6) An authorization letter must be obtained when there is a request to review files from any party.</p>
										<p>(7) An accessible record of all files stored off-site shall be maintained, and each storage needs to be appropriately labeled for easy identification and retrieval.</p>
									</section>
									<section id="independence" class="rulesContentTitle">
										<span>PART III – INDEPENDENCE</span>
									</section>
									<section id="threat_to_independence" class="rulesContentTitle">
										<span>Threats to independence</span>
									</section>
									<section id="types_of_threat" class="rulesContent">
										<p><span><strong>54.</strong>–(1) Every employee is expected to comply with these rules, which include Code of Ethics for Professional Accountants on Independence.</span></p>
										<p>(2) The followings are types of threat to independence:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Self-interest threat;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Self-review threat;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Advocacy threat;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Familiarity threat; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Intimidation threat
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="self-interest_threat" class="rulesContentTitle">
										<span>Self-interest threat</span>
									</section>
									<section id="self-interest_threat_safeguard" class="rulesContent">
										<p><span><strong>55.</strong>–(1) Self-interest threat occurs when the organization or the employee could benefit from a financial interest in or other self-interest conflict with any client.</span></p>
										<p>(2) Employee is expected to avoid any activities that would result in self-interest threat.</p>
										<p>(3) Employee is not to:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">have direct financial interest or material indirect financial interest in any of the client;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">extend or obtain loan or guarantee to or from a client or any of its officers;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">have a close business relationship with a client;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">have a potential employment with a client; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">accept any gift with value more than S$200 singularly or collectively by one client in a year.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(4) To observe such threat, the organization will ensure that:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">it will avoid undue dependence by ensuring that no fee collected from a single client will make 20% of total revenue of the organization;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">it will not place itself in a situation where it will be concerned about the possibility of losing the engagement;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">it will not provide contingent fees relating to assurance engagements.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="self-review_threat" class="rulesContentTitle">
										<span>Self-review threat</span>
									</section>
									<section id="self-review_threat_safeguard" class="rulesContent">
										<p><span><strong>56.</strong>–(1) Self-review threat occurs when the organization, or an individual audit team member is put in a position of reviewing subject matter for which the organization or individual was previously responsible, and which is significant in the context of the audit engagement.</span></p>
										<p>(2) Employee is expected not to:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">be or having recently been, a director, officer or other employee of the client in a position to exert direct and significant influence over the subject matter of the engagement;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">perform services for any client that directly affect the subject matter of the current, or a subsequent, engagement; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">preparation of original data used to generate financial statements or preparation of other records that are the subject matter of the engagement.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="advocacy_threat" class="rulesContentTitle">
										<span>Advocacy threat</span>
									</section>
									<section id="advocacy_threat_safeguard" class="rulesContent">
										<p><span><strong>57.</strong>–(1) Advocacy threat occurs when the organization or the employee, promotes, or may be perceived to promote, any client’s position or opinion to the point where objectivity may be compromised.</span></p>
										<p>(2) Employee is expected not to:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">deal in or promoting shares or other securities in any client; and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">act as an advocate on behalf of any client in litigation or in resolving disputes with third parties.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="familiarity_threat" class="rulesContentTitle">
										<span>Familiarity threat</span>
									</section>
									<section id="familiarity_threat_safeguard" class="rulesContent">
										<p><span><strong>58.</strong>–(1) Familiarity threat occurs when, by virtue of a close relationship with a client, its directors, officers or employee, the organization or employee becomes too sympathetic to the interests of the client.</span></p>
										<p>(2) Employee is expected not to:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">be a close family member of a director, officer or other employee of the client;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">be a former partner of the organization being a director, officer or other employee of the client;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">long association of a senior member of the audit team with the client; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">acceptance of gifts or hospitality, unless the value is clearly insignificant, from the client, its directors, officers or employee.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="intimidation_threat" class="rulesContentTitle">
										<span>Intimidation threat</span>
									</section>
									<section id="intimidation_threat_safeguard" class="rulesContent">
										<p><span><strong>59.</strong>–(1) Intimidation threat occurs when a member of the audit team may be deterred from acting objectively and exercising professional skepticism by threats, actual or perceived, from the directors, officers or employee of a client.</span></p>
										<p>(2) Examples of circumstances that may create this threat include:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">threat of replacement over a disagreement with the application of an accounting principle;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">pressure to reduce inappropriately the extent of work performed in order to reduce fees; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">dominant personality in a senior position at the client, controlling dealings with the employee of the organization.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(3) In relation to any engagement undertaken, employee should be in a position to articulate which of the above threats to independence apply.</p>
										<p>(4) The threats and their magnitude will depend on the circumstances, and therefore a considered assessment will require the application of judgment by the management.</p>
									</section>
									<section id="safeguards" class="rulesContentTitle">
										<span>Safeguards</span>
									</section>
									<section id="safeguards_to_independence" class="rulesContent">
										<p><span><strong>60.</strong>–(1) The organization recognized that independence can never be absolute because the auditor is appointed and paid by the client or its shareholders. Acknowledging this, the purpose of safeguards is to reduce the impact of threats to independence to a level that does not impair the auditor’s opinion-forming process in fact, or in the perception of a reasonable and informed observer.</span></p>
										<p>(2) The management must ensure that:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">the identification of threats to independence through interests or relationships, reliance on revenues from one client, and the provision of any services to audit clients;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">prohibition of individuals who are not members of the engagement team from influencing the outcome of the engagement;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">designation of a member of senior management as responsible for overseeing the adequate functioning of the safeguarding system;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">arrangements to ensure physical and virtual separation of employee involved in conflicting transactions;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">operation of disciplinary mechanism to promote compliance with these rules;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">employee is able to communicate to senior levels within the organization any issue of independence and objectivity that concerns them;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">involvement an additional accountant to review the work done or otherwise advise as necessary.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">h.</td>
													<td class="tableRulesRow">rotation of employee in charge of engagement;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">i.</td>
													<td class="tableRulesRow">disclosing to the audit committee the nature of services provided and extent of fees charged and discussing with it independence issues;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">j.</td>
													<td class="tableRulesRow">rules and regulations are in place to ensure members of the team do not make, or assume responsibility for, management decisions for the client;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">k.</td>
													<td class="tableRulesRow">involvement another firm to perform or re-perform part of the engagement or to re-perform the any service to the extent necessary to enable it to take responsibility for that service; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">l.</td>
													<td class="tableRulesRow">removal an individual from the team, when that individual’s financial interests or relationships create a threat to independence.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="breach_of_independence" class="rulesContentTitle">
										<span>Breach of independence</span>
									</section>
									<section id="identification_of_breach" class="rulesContent">
										<p><span><strong>61.</strong>– Where independence breach is identified by any of engagement team, member of engagement team shall:</span></p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">promptly notify manager of independence breach; and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">promptly communicate to engagement partner of such breach.
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="anti_money_laudering_counting_financing" class="rulesContentTitle">
										<span>PART IV – ANTI MONEY LAUNDERING AND COUNTERING FINANCING OF TERRORISM</span>
									</section>
									<section id="anti_money_laudering_general" class="rulesContentTitle">
										<span>General</span>
									</section>
									<section id="money_laundering_and_financing_of_terrorism" class="rulesContent">
										<p><span><strong>62.</strong>–(1) Money laundering is the funneling of cash or other funds generated from illegal activities through financial institutions and businesses to conceal or disguise the true ownership and source of the funds.</span></p>
										<p>The activities and methods of money laundering have become increasingly complex and ingenious, its “operations” tend to consist of three basic stages or processes — placement, layering and integration.</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Placement is the process of disposing the proceeds from drug trafficking or criminal conduct, for example by transferring the illegal funds into the financial system in a way that financial institutions and government authorities are not able to detect. Money launderers pay careful attention to national laws, regulations, governance structures, trends and law enforcement strategies and techniques to keep their proceeds concealed, their methods secret and their identities and professional resources anonymous.</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Layering is the process of generating a series or layers of transactions to distance the proceeds from their illegal source and to obscure the audit trail. Common layering techniques include outbound electronic funds transfers, usually directly or subsequently into a “bank secrecy haven” or a jurisdiction with lax record-keeping and reporting requirements, and withdrawals of already-placed deposits in the form of highly liquid monetary instruments, such as money orders or travelers’ checks.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Integration, the final money-laundering stage, is the unnoticed reinsertion of successfully laundered, untraceable funds into an economy. This is accomplished by spending, investing and lending, along with cross-border, legitimate-appearing transactions.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(2) Terrorist financing refers to the direct or indirect act of providing or collecting property for terrorist acts, providing property and services for terrorist purposes, using or possessing property for terrorist purposes, and dealing with property of terrorists. Properties refer to assets of every kind, whether tangible or intangible, movable or immovable, including bank credits, travelers’ cheques, bank cheques, money orders, shares, securities, bonds, drafts and letters of credit.</p>
										<p>(3) A terrorist refers to any person who commits or attempts to commit any terrorist act or participates in or facilitates the commission of any terrorist act. A terrorist act includes, among others, actions that involve violence against a person, serious damage to property, endangering a person’s life, creating a serious risk to the health or the safety of the public, the use of firearms or explosives, and releasing into the environment dangerous, hazardous, radioactive or harmful substance.</p>
										<p>(4) Knowledge or reasonable grounds to suspect is likely to include:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Actual knowledge;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Shutting one’s mind to the obvious;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Deliberately refraining from making inquiries, the results of which one might not care to have;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Deliberately deterring a person from making disclosures, the content of which one might not care to have;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Knowledge of circumstances which would indicate the facts to an honest and reasonable person; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Knowledge of circumstances which would put an honest and reasonable person on inquiry and failing to make the reasonable inquiries which such a person would have made.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(5) Suspicion is a subjective concept which may be caused by a transaction or transactions or set of circumstances which to the professional accountants appear unusual or out of context. It can arise from a single transaction or from on-going activity over a period of time.</p>
										<p>(6) Beneficial owner refers to natural person(s) who ultimately owns or controls a client and/or the natural person on whose behalf a transaction is being conducted. It also includes those persons who exercise ultimate effective control over a legal person or arrangement.</p>
										<p>(7) Branch refers to an extension of the parent and is not a legal entity separate from the parent.</p>
										<p>(8) Group refers to a parent and its subsidiaries and/or branches.</p>
										<p>(9) Legal arrangement – Express trusts or other similar arrangements.</p>
										<p>(10) Legal person refers to any entities other than natural persons that can establish a permanent client relationship with a professional firm or otherwise own property. This can include companies, bodies corporate, foundations, partnerships, or associations and other relevantly similar entities.</p>
										<p>(11) Politically exposed person (PEP) – A foreign PEP, domestic PEP or international organization PEP.</p>
										<p>(12) Foreign PEPs are individuals who are or have been entrusted with prominent public functions by a foreign country, for example Heads of State or of government, senior politicians, government ministers, senior civil or public servants, judicial or military officials, senior executives of state owned corporations, senior political party officials and members of the legislature.</p>
										<p>(13) Domestic PEPs are individuals who are or have been entrusted domestically with prominent public functions, for example Heads of State or of government, senior politicians, government ministers (including Second Minister and Minister of State), senior civil or public servants, judicial or military officials, senior executives of state owned</p>
										<p>(14) International organization PEPs are persons who are or have been entrusted with a prominent function by an international organization such as members of senior management, i.e. directors, deputy directors and members of the board or equivalent functions. International organization means an entity established by formal political agreements between member countries that have the status of international treaties, whose existence is recognized by law in member countries and who is not treated as a resident institutional unit of the country in which it is located.</p>
										<p>(15) The definition of PEPs is not intended to cover middle ranking or more junior individuals in the foregoing categories.</p>
										<p>(16) Family member of a PEP refers to an individual who is related to a PEP either directly (consanguinity) or through marriage or similar (civil) forms of partnership.</p>
										<p>(17) Close associate of a PEP refers to an individual who is closely connected to a PEP, either socially or professionally.</p>
									</section>
									<section id="customer_due_diligence" class="rulesContentTitle">
										<span>Customer due diligence</span>
									</section>
									<section id="kyc_process" class="rulesContent">
										<p><span><strong>63.</strong>–(1) Employee is required to perform Customer Due Diligence (“CDD”) on each engagement.</span></p>
										<p>(2) The objective of CDD is in developing a thorough understanding, through appropriate due diligence, of the true beneficial parties to transactions, the source and intended use of funds and the appropriateness and reasonableness of the business activity and pattern of transactions in the context of the business.</p>
										<p>(3) The following CDD measures shall be taken:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Identifying the client;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Identifying the beneficial owner;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Verifying that client’s identity using reliable, independent source documents, data or information, and taking reasonable measures to verify the identity of the beneficial owner, such that the organization is satisfied that it knows who the beneficial owner is. For legal persons and arrangements, this shall include the organization understanding the ownership and control structure of the client; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Understanding and, as appropriate, obtaining information on the purpose and intended nature of the business relationship.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(4) Management or employee shall undertake CDD measures when:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Establishing business relations;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Carrying out occasional transactions;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">There is a suspicion of money laundering or terrorist financing; or
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Where there are doubts about the veracity or adequacy of previously obtained client identification data.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(5) Management or employee shall verify the identity of the client and beneficial owner before or during the course of establishing a business relationship or carrying out occasional transactions.</p>
										<p>(6) For the purpose of rule 53(5), the type of information that would normally be needed to perform this function would be:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Name, legal form and proof of existence – verification could be obtained, for example, through a certificate of incorporation, a certificate of good standing, a partnership agreement, a deed of trust, or other documentation from a reliable independent source providing the name, form and current existence of the client;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">The powers that regulate and bind the legal person or arrangement (e.g. the constitution of a company), as well as the names of the relevant persons having a senior management position in the legal person or arrangement (e.g. senior managing directors in a company, trustee(s) of a trust); and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">The address of the registered office, and, if different, a principal place of business.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(7) Employee shall identify the beneficial owners of a client and take reasonable measures to verify the identity of such legal persons, through the following information:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">The identity of the natural persons (if any – as ownership interests can be so diversified that there are no natural persons (whether acting alone or together) exercising control of the legal person or arrangement through ownership) who ultimately have a controlling ownership interest in a legal person; and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">To the extent that there is doubt under rule 63(7)(a) as to whether the person(s) with the controlling ownership interest are the beneficial owner(s) or where no natural person exerts control through ownership interests, the identity of the natural persons (if any) exercising control of the legal person or arrangement through other means.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Where no natural person is identified under rule 63(7)(a) and (b), employee shall identify and take reasonable measures to verify the identity of the relevant natural person who holds the position of senior managing official.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(8) Employee shall identify the beneficial owners of a client and take reasonable measures to verify the identity of such legal arrangements, through the following information:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Trusts – the identity of the settlor, the trustee(s), the protector (if any), the beneficiaries or class of beneficiaries, and any other natural person exercising ultimate effective control over the trust (including through a chain of control/ownership).</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Other types of legal arrangements – the identity of persons in equivalent or similar positions.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(9) Where a person purports to act on behalf of a client, the employee shall identify and verify the identity of the person and shall verify that the person is so authorized, by obtaining appropriate documentary evidence that the client has appointed the person to act on its behalf, and the specimen signatures of the person appointed.</p>
										<p>(10) It is not necessary to identify and verify the identity of any shareholder or beneficial owner if the client is:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">A Singapore Government entity;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">A foreign government entity;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">An entity listed on the Singapore Exchange;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">An entity listed on a stock exchange outside of Singapore that is subject to regulatory disclosure requirements;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">A majority-owned subsidiary of a company in rule 63(10)(c) and (d);
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">A financial institution that is licensed, approved, registered or regulated by the Monetary Authority of Singapore (“MAS”) but does not include:
														<table class="pTable" style="font-size:13pt" width="100%">
															<tbody>
																<tr>
																	<td class="tableRulesNo">(i)</td>
																	<td class="tableRulesRow">Holders of stored value facilities; and</td>
																</tr>
																<tr>
																	<td class="tableRulesNo">(ii)</td>
																	<td class="tableRulesRow">A person who is exempted from licensing, approval or regulation by the MAS under any Act administered by the MAS, including a private trust company exempted from licensing;
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">A person exempted under Financial Advisers Act (Cap. 110);
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">h.</td>
													<td class="tableRulesRow">A person exempted under section 99(1)(h) of the Securities and Futures Act (Cap. 289) read with paragraph 7(1)(b) of the Second Schedule to the Securities and Futures (Licensing and Conduct of Business) Regulations;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">i.</td>
													<td class="tableRulesRow">A financial institution incorporated or established outside Singapore that is subject to and supervised for compliance with AML/CFT requirements consistent with standards set by the FATF; or
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">j.</td>
													<td class="tableRulesRow">An investment vehicle where the managers are financial institutions
													</td>
												</tr>
											</tbody>
										</table>
										<p>(11) Employee must retain copies of all reference source documents, data or information used to verify the identity of the client.</p>
										<p>(12) Where the client is unable to produce original documents, the employee may consider accepting documents that are certified to be true copies by an independent, qualified person, such as a notary public, or an external law firm.</p>
										<p>(13) In cases of foreign Politically Exposed Persons (“PEPs”) or high risk business relationship with domestic PEPs, international organization PEPs, or PEPs who have stepped down from their prominent public functions, taking into consideration the level of influence such persons may continue to exercise after stepping down from their prominent public functions (whether as client or beneficial owner), employee shall, in addition to performing normal CDD measures under rule 63(3), perform enhanced CDD measures.</p>
										<p>(14) For the purpose of rule 63(13), enhanced CDD measures include:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Obtaining senior management approval for establishing (or continuing, for existing clients) such business relationships;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Taking reasonable measures to establish the source of wealth and source of funds; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Conducting enhanced ongoing monitoring of the business relationship.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(15) The enhanced CDD requirements for a PEP under rule 63(13) shall also apply to family members and close associates of PEP.</p>
										<p>(16) In cases of lower risk business relationship with domestic PEPs, international organization PEPs or PEPs who have stepped down from their prominent functions, their family members and close associates, the management need to determine the extent of enhanced CDD to perform.</p>
										<p>(17) When the management is considering whether to establish or continue a business relationship with a PEP, his/her family members and close associates, the focus shall be on the level of money laundering and terrorist financing risk, and whether the organization has adequate controls in place to mitigate the risk so as to avoid the organization from being abused for illicit purposes.</p>
										<p>(18) Existing clients may have become PEPs after they enter a business relationship, so it is essential that the compliance officer periodically monitor their existing client base for a change in the PEP status and update client information. Such ongoing monitoring shall be based on the level of risk.</p>
										<p>(19) Compliance officer shall examine, as far as reasonably possible, the background and purpose of all complex, unusual large transactions, and all unusual patterns of transactions, which have no apparent economic or lawful purpose.</p>
										<p>(20) Where the risks of money laundering or terrorist financing are higher, employee shall conduct enhanced CDD.</p>
										<p>(21) For the purpose of rule 63(20) enhanced CDD measures that could be applied for higher risk business relationships include:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Obtaining additional information on the client (e.g. occupation, volume of assets, information available through public databases, internet, etc.), and updating more regularly the identification data of client and beneficial owner.</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Obtaining additional information on the intended nature of the business relationship.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Obtaining information on the source of funds or source of wealth of the client.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Obtaining information on the reasons for intended or performed transactions.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Obtaining the approval of senior management to commence or continue the business relationship.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Conduct enhanced monitoring of the business relationship, by increasing the number and timing of controls applied, and selecting patterns of transactions that need further examination.
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">Requiring the first payment to be carried out through an account in the client’s name with a bank subject to similar CDD standards.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(22) Employee when performing CDD under rule 63(3) shall also check the names of clients or potential clients against:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">The list of persons subject to prescribed restrictions in the regulations issued by the MAS enacted to give effect to the list of individuals or entities identified by the Security Council of the United Nations; and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">The list of terrorist names under the First Schedule of the Terrorism (Suppression of Funds) Act (“TSFA”).
													</td>
												</tr>
											</tbody>
										</table>
									</section>
									<section id="record_keeping" class="rulesContentTitle">
										<span>Record keeping</span>
									</section>
									<section id="period_and_types_of_record_to_be_kept" class="rulesContent">
										<p><span><strong>64.</strong>–(1) The compliance officer shall prepare, maintain and retain documentation on all its business relations with, and transactions for, its clients such that:</span></p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">All requirements imposed by law are met;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Any individual transaction can be reconstructed so as to provide, if necessary, evidence for prosecution of criminal activity;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">The relevant authorities are able to review the organization’s business relations, transactions, records and CDD information and assess the level of compliance with relevant laws and compliance with Part IV of these rules; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">The organization can satisfy, within a reasonable time or any more specific time period imposed by law or by any requesting authority, any enquiry or order from the relevant authorities for information.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(2) The compliance officer shall ensure compliance with the following document retention periods:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Of at least five years following the termination of business relations for all information obtained through CDD measures (e.g. copies or records of official identification documents like passports, identity cards, driving licenses or similar documents), account files and business correspondence, including the results of any analysis undertaken (e.g. inquiries to establish the background and purpose of complex, unusual large transactions); and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Of at least five years following the completion of the transaction for records relating to a transaction, including any information needed to explain and reconstruct the transaction.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(3) Organization may retain documents, data and information as originals or copies, in paper or electronic form or on microfilm, provided that they are admissible as evidence in the court of law.</p>
										<p>(4) Organization shall maintain a complete file of all internal suspicious transactions reports filed by employee to Compliance Officer, whether or not these were subsequently reported by the Compliance Officer to the STRO, together with all internal findings and analysis done in relation to them.</p>
									</section>
									<section id="record_keeping" class="rulesContentTitle">
										<span>Record keeping</span>
									</section>
									<section id="period_and_types_of_record_to_be_kept" class="rulesContent">
										<p><span><strong>64.</strong>–(1) The compliance officer shall prepare, maintain and retain documentation on all its business relations with, and transactions for, its clients such that:</span></p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">All requirements imposed by law are met;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Any individual transaction can be reconstructed so as to provide, if necessary, evidence for prosecution of criminal activity;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">The relevant authorities are able to review the organization’s business relations, transactions, records and CDD information and assess the level of compliance with relevant laws and compliance with Part IV of these rules; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">The organization can satisfy, within a reasonable time or any more specific time period imposed by law or by any requesting authority, any enquiry or order from the relevant authorities for information.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(2) The compliance officer shall ensure compliance with the following document retention periods:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Of at least five years following the termination of business relations for all information obtained through CDD measures (e.g. copies or records of official identification documents like passports, identity cards, driving licenses or similar documents), account files and business correspondence, including the results of any analysis undertaken (e.g. inquiries to establish the background and purpose of complex, unusual large transactions); and</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Of at least five years following the completion of the transaction for records relating to a transaction, including any information needed to explain and reconstruct the transaction.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(3) Organization may retain documents, data and information as originals or copies, in paper or electronic form or on microfilm, provided that they are admissible as evidence in the court of law.</p>
										<p>(4) Organization shall maintain a complete file of all internal suspicious transactions reports filed by employee to Compliance Officer, whether or not these were subsequently reported by the Compliance Officer to the STRO, together with all internal findings and analysis done in relation to them.</p>
									</section>
									<section id="hiring_and_training" class="rulesContentTitle">
										<span>Hiring and Training</span>
									</section>
									<section id="training_process" class="rulesContent">
										<p><span><strong>65.</strong>–(1) Management should conduct adequate screening procedures in place to ensure high standards when hiring employees.</span></p>
										<p>(2) Employee should be reminded of their responsibilities and kept informed of new developments through refresher training, or through other forms of internal communication, at regular intervals.</p>
										<p>(3) Refresher training should be held at least once every two years, or more regularly where there have been significant developments such as new regulatory requirements or changes to key internal processes.</p>
										<p>(4) For the purpose of rule 65(2) training would as a minimum be expected to emphasize the following:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Requirements of the AML and CFT legislations;</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Prevailing techniques, methods and trends in money laundering and terrorist financing;
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Indications of money laundering and terrorist financing; and
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">the need to obtain legal advice in situations where there is doubt about the legal framework and requirements.
													</td>
												</tr>
											</tbody>
										</table>
										<p>(5) Evidence of assessment of training needs and steps taken to meet such needs are retained and that such records be kept for at least five years.</p>
										<p>(6) Compliance officer should monitor attendance at such training and take appropriate follow-up action in relation to employee who absent without reasonable cause which will be dealt in as absent under rule 9(8) and (9).</p>
									</section>
									<section id="compliance_management" class="rulesContentTitle">
										<span>Compliance management</span>
									</section>
									<section id="compliance_officer_and_audit_function" class="rulesContent">
										<p><span><strong>66.</strong>–(1) Organization shall appoint a compliance officer at the management level who would report to senior management on compliance and address any identified deficiencies.</span></p>
										<p>(2) The compliance officer, as well as any other persons appointed to assist her, is empowered by the organization with adequate resources and timely access to all client records and other relevant information which they require to discharge their functions.</p>
										<p>(3) Roles and responsibilities of compliance officers:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">Conduct due diligence investigations, closing false alerts and escalating to management suspicious payments for further investigation</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">Reviewing and assessing alerts for money laundering risk through the organization’s transaction monitoring system and liaising with business areas as necessary to investigate the alerts
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">c.</td>
													<td class="tableRulesRow">Escalating suspicious alerts for further review and investigation
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">d.</td>
													<td class="tableRulesRow">Contribute to suggestions to enhance systems, methodologies and process simplification
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">e.</td>
													<td class="tableRulesRow">Ensuring management is made aware of all pressing issues in a timely fashion
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">f.</td>
													<td class="tableRulesRow">Operating to the very highest levels of integrity at all times
													</td>
												</tr>
												<tr>
													<td class="tableRulesNo">g.</td>
													<td class="tableRulesRow">Maintaining an appropriate audit trail and documentation in all instances, to evidence/support the review and resolution of each payment alert
													</td>
												</tr>
											</tbody>
										</table>
										<p>(4) Compliance officer will ensure there is an audit function that is adequately resourced and independent to regularly assess the effectiveness of its rules and its compliance with AML/CFT requirements.</p>
									</section>
									<section id="suspicious_transactions_reporting" class="rulesContentTitle">
										<span>Suspicious transactions reporting</span>
									</section>
									<section id="reporting_procedures" class="rulesContent">
										<p><span><strong>67.</strong>–(1) The organization shall ensure that they are sufficiently aware of the main provisions of the AML and CFT legislations.</span></p>
										<p>(2) Employees shall be familiar with and apply the requirements in these Rules.</p>
										<p>(3) Where the employee discovers information, which could indicate to the employee that money laundering or terrorist financing specified in rule 68 is occurring or has occurred, the employee shall report to compliance officer.</p>
										<p>(4) Compliance officer shall complete their assessment of whether there are reasonable grounds to suspect money laundering or terrorist financing is occurring or has occurred and, if appropriate, to report to the Suspicious Transaction Reporting Officer (“STRO”) manually.</p>
										<p>(5) For the purpose of rule 67(4), compliance officer shall consider it to be suspicious if:</p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="tableRulesNo">a.</td>
													<td class="tableRulesRow">The employee is for any reason unable to complete the CDD measures; or</td>
												</tr>
												<tr>
													<td class="tableRulesNo">b.</td>
													<td class="tableRulesRow">The client is reluctant, unable or unwilling to provide any information requested by the employee, decides to withdraw from establishing business relations or a pending engagement, or to terminate existing business relations.</td>
												</tr>
											</tbody>
										</table>
										<p>(6) Such considerations under rule 67(5) and conclusions shall be documented.</p>
										<p>(7) When employee becomes aware of a possible breach of law or regulations, the employee shall discuss the matter with compliance officer and appropriate members of management.</p>
										<p>(8) Where the entity or its customers, suppliers or other business associates are suspected of being involved in the criminal activity, compliance officer undertake their assessment of the circumstances with care so as not to alert the entity's management or anyone else to these suspicions in case tipping-off occurs.</p>
										<p>(9) Statutory immunity is granted from any legal action, criminal or civil, for breach of confidence arising from having reported suspicions of money laundering and terrorist financing to the STRO, provided the report is made in good faith.</p>
									</section>
									<section id="indicators_of_suspicious_transactions" class="rulesContent">
										<p><span><strong>68.</strong>– Money launderers use many different and sophisticated types of schemes, techniques and transactions to accomplish their ends and is difficult to describe all money laundering methodologies, the following are the more frequently observed signs of suspicions:</span></p>
										<table class="pTable" style="font-size:13pt" width="100%">
											<tbody>
												<tr>
													<td class="lastTDwidth">(1)</td>
													<td class="tableRulesRow">Frequent address changes.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(2)</td>
													<td class="tableRulesRow">Client does not want correspondence sent to home address.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(3)</td>
													<td class="tableRulesRow">Client repeatedly uses an address but frequently changes the names involved.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(4)</td>
													<td class="tableRulesRow">Client uses a post office box or general delivery address, or other type of mail drop address, instead of a street address when this is not the norm for that area.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(5)</td>
													<td class="tableRulesRow">Client’s home or business telephone number has been disconnected or there is no such number when an attempt is made to contact client shortly after he/she has opened an account.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(6)</td>
													<td class="tableRulesRow">Client is accompanied and watched.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(7)</td>
													<td class="tableRulesRow">Client shows uncommon curiosity about internal systems, controls, policies and reporting; client has unusual knowledge of the law in relation to suspicious transaction reporting.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(8)</td>
													<td class="tableRulesRow">Client has only vague knowledge of the amount of a deposit.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(9)</td>
													<td class="tableRulesRow">Client gives unrealistic, confusing or inconsistent explanation for transaction or account activity.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(10)</td>
													<td class="tableRulesRow">Defensive stance to questioning or over-justification of the transaction.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(11)</td>
													<td class="tableRulesRow">Client is secretive and reluctant to meet in person.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(12)</td>
													<td class="tableRulesRow">Unusual nervousness of the person conducting the transaction.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(13)</td>
													<td class="tableRulesRow">Client is involved in transactions that are suspicious but seems blind to being involved in money laundering activities.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(14)</td>
													<td class="tableRulesRow">Client insists on a transaction being done quickly.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(15)</td>
													<td class="tableRulesRow">Client appears to have recently established a series of new relationships with different financial entities.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(16)</td>
													<td class="tableRulesRow">Client attempts to develop close rapport with employee.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(17)</td>
													<td class="tableRulesRow">Client offers money, gratuities or unusual favors for the provision of services that may appear unusual or suspicious.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(18)</td>
													<td class="tableRulesRow">Client attempts to convince employee not to complete any documentation required for the transaction.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(19)</td>
													<td class="tableRulesRow">Large contracts or transactions with apparently unrelated third parties, particularly from abroad.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(20)</td>
													<td class="tableRulesRow">Large lump-sum payments to or from abroad, particularly with countries known or suspected to facilitate money laundering activities.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(21)</td>
													<td class="tableRulesRow">Client is quick to volunteer that funds are “clean” or “not being laundered”.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(22)</td>
													<td class="tableRulesRow">Client’s lack of business knowledge atypical of trade practitioners.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(23)</td>
													<td class="tableRulesRow">Forming companies or trusts with no apparent business purpose.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(24)</td>
													<td class="tableRulesRow">Unusual transference of negotiable instruments.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(25)</td>
													<td class="tableRulesRow">Uncharacteristically premature redemption of investment vehicles, particularly with requests to remit proceeds to apparently unrelated third parties or with little regard to tax or other cancellation charges.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(26)</td>
													<td class="tableRulesRow">Large or unusual currency settlements for investments or payment for investments made from an account that is not the clients..</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(27)</td>
													<td class="tableRulesRow">Clients seeking investment management services where the source of funds is difficult to pinpoint or appears inconsistent with the client’s means or expected behavior.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(28)</td>
													<td class="tableRulesRow">Purchase of large cash value investments soon followed by heavy borrowing against them.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(29)</td>
													<td class="tableRulesRow">Buying or selling investments for no apparent reason, or in circumstances that appear unusual, e.g. losing money without the principals seeming concerned.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(30)</td>
													<td class="tableRulesRow">Forming overseas subsidiaries or branches that do not seem necessary to the business and manipulating transfer prices with them.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(31)</td>
													<td class="tableRulesRow">Extensive and unnecessary foreign travel.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(32)</td>
													<td class="tableRulesRow">Purchasing at prices significantly below or above market.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(33)</td>
													<td class="tableRulesRow">Excessive or unusual sales commissions or agents’ fees; large payments for unspecified services or loans to consultants, related parties, employees or government employees.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(34)</td>
													<td class="tableRulesRow">Client frequently exchanges small bills for large ones.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(35)</td>
													<td class="tableRulesRow">Deposit of bank notes with a suspect appearance (very old notes, notes covered in powder, etc).</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(36)</td>
													<td class="tableRulesRow">Use of unusually large amounts in traveler’s checks.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(37)</td>
													<td class="tableRulesRow">Frequent domestic and international ATM activity.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(38)</td>
													<td class="tableRulesRow">Client asks to hold or transmit large sums of money or other assets when this type activity is unusual for the client.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(39)</td>
													<td class="tableRulesRow">Purchase or sale of gold, diamonds or other precious metals or stones in cash.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(40)</td>
													<td class="tableRulesRow">Shared address for individuals involved in cash transactions, particularly when the address is also for a business location or does not seem to correspond to the stated occupation (for example, student, unemployed, self-employed, etc.).</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(41)</td>
													<td class="tableRulesRow">Apparent use of personal account for business purposes.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(42)</td>
													<td class="tableRulesRow">Opening accounts when the client’s address is outside the local service area.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(43)</td>
													<td class="tableRulesRow">Opening accounts with names very similar to other established business entities.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(44)</td>
													<td class="tableRulesRow">Opening an account that is credited exclusively with cash deposits in foreign currencies.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(45)</td>
													<td class="tableRulesRow">Use of nominees who act as holders of, or who hold power of attorney over, bank accounts.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(46)</td>
													<td class="tableRulesRow">Account with a large number of small cash deposits and a small number of large cash withdrawals.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(47)</td>
													<td class="tableRulesRow">Funds being deposited into several accounts, consolidated into one and transferred outside the country.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(48)</td>
													<td class="tableRulesRow">Use of wire transfers and the Internet to move funds to/from high-risk countries and geographic locations.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(49)</td>
													<td class="tableRulesRow">Accounts receiving frequent deposits of bearer instruments (e.g. bearer cheques, money orders, bearer bonds) followed by wire transactions.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(50)</td>
													<td class="tableRulesRow">Deposit at a variety of locations and times for no logical reason.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(51)</td>
													<td class="tableRulesRow">Multiple transactions are carried out on the same day at the same branch but with an apparent attempt to use different tellers.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(52)</td>
													<td class="tableRulesRow">Establishment of multiple accounts, some of which appear to remain dormant for extended periods.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(53)</td>
													<td class="tableRulesRow">Account that was reactivated from inactive or dormant status suddenly sees significant activity.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(54)</td>
													<td class="tableRulesRow">Cash advances from credit card accounts to purchase cashier’s checks or to wire funds to foreign destinations.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(55)</td>
													<td class="tableRulesRow">Large cash payments on small or zero-balance credit card accounts followed by credit balance refund checks” sent to account holders.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(56)</td>
													<td class="tableRulesRow">Attempting to open accounts for the sole purpose of obtaining online banking capabilities.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(57)</td>
													<td class="tableRulesRow">Loans secured by obligations from offshore banks.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(58)</td>
													<td class="tableRulesRow">Loans to or from offshore companies.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(59)</td>
													<td class="tableRulesRow">Offers of multimillion-dollar deposits from a confidential source to be sent from an offshore bank or somehow guaranteed by an offshore bank.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(60)</td>
													<td class="tableRulesRow">Transactions involving an offshore “shell” bank whose name may be very similar to the name of a major legitimate institution.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(61)</td>
													<td class="tableRulesRow">Client repeatedly uses an address but frequently changes the names involved.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(61)</td>
													<td class="tableRulesRow">Client receives unusual payments from unlikely sources which is inconsistent with sales.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(63)</td>
													<td class="tableRulesRow">Client has a history of changing bookkeepers or accountants yearly.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(64)</td>
													<td class="tableRulesRow">Client is uncertain about location of company records.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(65)</td>
													<td class="tableRulesRow">Company records consistently reflect sales at less than cost, thus putting the company into a loss position, but the company continues without reasonable explanation of the continued loss.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(66)</td>
													<td class="tableRulesRow">Company shareholder loans are not consistent with business activity.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(67)</td>
													<td class="tableRulesRow">Company makes large payments to subsidiaries or other entities within the group that do not appear within normal course of business.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(68)</td>
													<td class="tableRulesRow">Company is invoiced by organizations located in a country that does not have adequate money laundering laws and is known as a highly secretive banking and corporate tax haven.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(69)</td>
													<td class="tableRulesRow">Client appears to be living beyond his or her means.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(70)</td>
													<td class="tableRulesRow">Client has no or low income compared to normal cost of living.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(71)</td>
													<td class="tableRulesRow">Client has unusual rise in net worth arising from gambling and lottery gains.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(72)</td>
													<td class="tableRulesRow">Client has unusual rise in net worth arising from inheritance from a criminal family member.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(73)</td>
													<td class="tableRulesRow">Client owns assets located abroad, not declared in the tax return.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(74)</td>
													<td class="tableRulesRow">Client obtains loan from unidentified parties.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(75)</td>
													<td class="tableRulesRow">Client obtains mortgage on a relatively low income.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(76)</td>
													<td class="tableRulesRow">Complex corporate structure where complexity does not seem to be warranted.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(77)</td>
													<td class="tableRulesRow">Complex or unusual transactions, possibly with related parties.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(78)</td>
													<td class="tableRulesRow">Transactions with little commercial logic taking place in the normal course of business.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(79)</td>
													<td class="tableRulesRow">Transactions not in the normal course of business.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(80)</td>
													<td class="tableRulesRow">Transactions where there is a lack of information or explanations, or where explanations are unsatisfactory.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(81)</td>
													<td class="tableRulesRow">Transactions at an undervalue.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(82)</td>
													<td class="tableRulesRow">Transactions with companies whose identity is difficult to establish as they are registered in countries known for their commercial secrecy.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(83)</td>
													<td class="tableRulesRow">Extensive or unusual related party transactions.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(84)</td>
													<td class="tableRulesRow">Many large cash transactions when not expected.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(85)</td>
													<td class="tableRulesRow">Payments for unspecified services, or payments for services that appear excessive in relation to the services provided.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(86)</td>
													<td class="tableRulesRow">The forming of companies or trusts with no apparent commercial or other purpose.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(87)</td>
													<td class="tableRulesRow">Long delays in the production of company or trust accounts.</td>
												</tr>
												<tr>
													<td class="lastTDwidth">(88)</td>
													<td class="tableRulesRow">Foreign travel which is apparently unnecessary and extensive.</td>
												</tr>
											</tbody>
										</table>
									</section>
								</div> 
							</div><!--end of .row-->
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
    </div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	var in_use_user_id = <?php echo json_encode($id);?>;
	var user_id = <?php echo json_encode($this->session->userdata('user_id'));?>;
	var field_name;
    var manager_in_charge_value = <?php echo json_encode($user->manager_in_charge);?>;
    var part = <?php echo json_encode($part);?>;

	$("#header_our_firm").removeClass("header_disabled");
    
    $("#header_access_right").removeClass("header_disabled");
    if(in_use_user_id == user_id)
    {
    	$("#header_manage_user").removeClass("header_disabled");
    	$("#header_user_profile").addClass("header_disabled");
    }
    else if(in_use_user_id != user_id)
    {
    	$("#header_manage_user").addClass("header_disabled");
    	$("#header_user_profile").removeClass("header_disabled");
    }
    $("#header_setting").removeClass("header_disabled");
    $("#header_dashboard").removeClass("header_disabled");
    $("#header_client").removeClass("header_disabled");
    $("#header_person").removeClass("header_disabled");
    $("#header_document").removeClass("header_disabled");
    $("#header_report").removeClass("header_disabled");
    $("#header_billings").removeClass("header_disabled");

    $('#rules_nav').affix({
	    offset: {     
	      top: 100,//$('#nav').offset().top //100
	      bottom: ($('footer').outerHeight(true) + $('.application').outerHeight(true)) + 40
	    }
	});

    $('body').scrollspy({
	    target: '.scrollspy',
	    offset: 160
	});

	$("#rules_nav li a").on('click', function(e) {
	   	// prevent default anchor click behavior
	   	e.preventDefault();
		var target = $(this.hash);
		target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
		
		if (target.length) {
			$('html,body').animate({
			  scrollTop: target.offset().top - 125
			}, 1000);
			return false;
		}
	});

	//Disable window scroll when scrolling an overflowed element
	$('ul#myRulesDIV').mouseenter(function(event) {
	    $('body').css('overflow', 'hidden');
	}).mouseleave(function(event) {
	    $('body').css('overflow', '');
	});

	$(window).on("scroll", function() {
		var scrollHeight = $(document).height();
		var scrollPosition = $(window).height() + $(window).scrollTop();
		//console.log(scrollPosition);
		if(scrollPosition >= 0 && scrollPosition < 2971)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 0;
		}
		else if(scrollPosition >= 2971 && scrollPosition < 7932)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 324;
		}
		else if(scrollPosition >= 7932 && scrollPosition < 12812)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 824;
		}
		else if(scrollPosition >= 12812 && scrollPosition < 16386)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 1236;
		}
		else if(scrollPosition >= 16386 && scrollPosition < 19376)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 1746;
		}
		else if(scrollPosition >= 19376 && scrollPosition < 23164)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 2250;
		}
		else if(scrollPosition >= 23164 && scrollPosition < 29729)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 2754;
		}
		else if(scrollPosition >= 29729)
		{
			var elmnt = document.getElementById("myRulesDIV");
			elmnt.scrollTop = 2989;
		}
	});

	// function myFunction() {
	//   var elmnt = document.getElementById("myRulesDIV");
	//   //console.log(elmnt);
	//   var x = elmnt.scrollLeft;
	//   var y = elmnt.scrollTop;
	//   console.log(y);
	//   //document.getElementById ("demo").innerHTML = "Horizontally: " + x + "px<br>Vertically: " + y + "px";
	// }

 //    $(document).on('click',".check_stat",function() 
	// {
	// 	$profile_index_tab_aktif = $(this).data("information");

	// 	if($profile_index_tab_aktif == "rules")
	// 	{
	// 		$(".list_of_rules").remove();
	// 		var depart_id = <?php echo json_encode($user->department_id) ?>;

	// 		$.ajax({
	// 	        type: "POST",
	// 	        url: "auth/get_rules",
	// 	        data: '&department_id=' + depart_id,
	// 	        dataType: "json",
	// 	        async: false,
	// 	        success: function(data){
	// 	            if(data)
	// 	            {
	// 	                for(var f = 0; f < data.length; f++)
	// 	                {
	// 			            $b=""; 
	// 				        $b += '<tr class="list_of_rules">';
	// 				        $b += '<td>'+(data[f]["type"])+'</td>';
	// 				        $b += '<td>'+(data[f]["description"])+'</td>';
	// 				        $b += '</tr>';

	// 				        $(".rules_table").append($b);

	// 	                }
	// 	            }
	// 	            else
	// 	            {
	// 	                $b=""; 
	// 			        $b += '<tr class="list_of_rules">';
	// 			        $b += '<td colspan="2" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
	// 			        $b += '</tr>';

	// 			        $(".rules_table").append($b);
	// 	            }
	// 	        }               
	// 	    });

	// 	}
	// });

	var base_url = '<?php echo base_url() ?>';
    $(document).ready(function () {

        $('#change-password-form').bootstrapValidator({
            //message: 'Please enter/select a value',
            submitButtons: 'input[type="submit"]',
            fields: {
	            old_password: {
	                validators: {
	                    notEmpty: {
	                        message: 'The Old Password is required.'
	                    }/*,
	                    regexp: {
                          regexp: '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                          message: 'The value is not a valid password.'
                        }*/
	                }
	            }
	        }
        });

        if(part == "access_client")
        {
        	field_name = "selected_client[]";
        }
        else
        {
        	field_name = "selected_firm[]";
        }
        //console.log(field_name);
        $('#update_profile')
        	.find('[name="'+field_name+'"]')
            .multiselect({
                buttonWidth: '300px',
                maxHeight: 200,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                filterPlaceholder: 'Search ...',
                nonSelectedText: 'Check an option!',
                numberDisplayed: 1,
                buttonText: function(options, select) {
                    if (options.length === 0) {
                    	if(part == "access_client")
        				{
                        	return 'Select the client';
                        }
                        else
                        {
                        	return 'Select the Firm';
                        }
                    }
                    else if (options.length > 1) {
                    	if(part == "access_client")
        				{
                        	return 'More than 1 client selected!';
                        }
                        else
                        {
                        	return 'More than 1 Firm selected!';
                        }
                    }
                    else {
                         var labels = [];
                         options.each(function() {
                             if ($(this).attr('label') !== undefined) {
                                 labels.push($(this).attr('label'));
                             }
                             else {
                                 labels.push($(this).html());
                             }
                         });
                         return labels.join(', ') + '';
                    }
                },
                // Re-validate the multiselect field when it is changed
                onChange: function(element, checked) {
                    //console.log($('#create_user_form').bootstrapValidator('revalidateField', 'selected_firm'));
                    //console.log(field_name);
                    if(part == "access_client")
        			{
                    	$('#update_profile').bootstrapValidator('revalidateField', "selected_client[]");
                    }
                    else
                    {
                    	$('#update_profile').bootstrapValidator('revalidateField', 'selected_firm[]');
                    }
                }
            })
            .end()
        	.bootstrapValidator({
            submitButtons: 'input[id="update"]',
            excluded: ':disabled',
            fields: {
	            first_name: {
	                validators: {
	                    notEmpty: {
	                        message: 'The first name is required'
	                    }
	                }
	            },
	            last_name: {
	                validators: {
	                    notEmpty: {
	                        message: 'The last name is required'
	                    }
	                }
	            },
	            role: {
                    validators: {
                        callback: {
                            message: 'The role is required',
                            callback: function(value, validator, $field) {
                                //var num = jQuery($field).parent().parent().parent().attr("num");
                                var options = validator.getFieldElements('role').val();
                                return (options != null && options != "0");
                            }
                        }
                    }
                },
                department: {
                    validators: {
                        callback: {
                            message: 'The department is required',
                            callback: function(value, validator, $field) {
                                //var num = jQuery($field).parent().parent().parent().attr("num");
                                var options = validator.getFieldElements('department').val();
                                return (options != null && options != "0");
                            }
                        }
                    }
                },
	            field_name: {
                    validators: {
                        callback: {
                            message: 'Please choose at least one firm.',
                            callback: function(value, validator, $field) {
                                var options = validator.getFieldElements(''+field_name+'').val();
                                //console.log(options);
                                return (options != null
                                    && options.length >= 1);
                            }
                        }
                    }
                }
	        }
        });
    });
	
	var validate_role = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.form-group',
        validators: {
            callback: {
                message: 'The role is required.',
                callback: function(value, validator, $field) {
                    var options = validator.getFieldElements('role').val();
                    return (options != null && options != "0");
                }
            }
        }
    },
    validate_department = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.form-group',
        validators: {
            callback: {
                message: 'The department is required.',
                callback: function(value, validator, $field) {
                    var options = validator.getFieldElements('department').val();
                    return (options != null && options != "0");
                }
            }
        }
    },
    selected_firm = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.form-group',
        validators: {
            callback: {
                message: 'Please choose at least one firm.',
                callback: function(value, validator, $field) {
                    var options = validator.getFieldElements("selected_firm[]").val();
                    return (options != null
                        && options.length >= 1);
                }
            }
        }
    };

    $.ajax({
        type: "GET",
        url: "masterclient/get_department",
        dataType: "json",
        async: false,
        success: function(data){
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    var str = <?php echo json_encode($user->department_id) ?>;
					var temp = new Array();
					if(str)
					{
						// this will return an array with strings "1", "2", etc.
						temp = str.split(",");
						
						for($k = 0; $k < temp.length; $k++)
						{
							if(key == temp[$k])
	                    	{
	                    		option.attr('selected', 'selected');
	                    	}
						}
					}
                    $('#department').append(option);
                });
                $('#update_profile').bootstrapValidator('addField', 'department', validate_department);
            }
            else{
                alert(data.msg);
            }
        }               
    });

    $.ajax({
        type: "GET",
        url: "auth/get_group",
        dataType: "json",
        async: false,
        success: function(data){
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    var str = <?php echo json_encode($user->group_id) ?>;
					var temp = new Array();
					if(str)
					{
						// this will return an array with strings "1", "2", etc.
						temp = str.split(",");
						
						for($k = 0; $k < temp.length; $k++)
						{
							if(key == temp[$k])
	                    	{
	                    		option.attr('selected', 'selected');
	                    	}
						}
					}
                    $('#role').append(option);
                });
                $('#update_profile').bootstrapValidator('addField', 'role', validate_role);
            }
            else{
                alert(data.msg);
            }
        }               
    });

    $.ajax({
        type: "GET",
        url: "auth/get_manager_name",
        async: false,
        success: function(response){
            response = JSON.parse(response);
            if(response.result.length != 0)
            {
                if($('#role').val() == 3 || $('#role').val() == 6)
                {
                    $(".manager_in_charge_div_chill").remove(); 
                    $(".manager_in_charge_div").removeAttr( 'style' );

                    $a = "";
                    $a = '<div class="col-sm-2 manager_in_charge_div_chill" style="margin-left: -15px; margin-right: 5px;"><label for="manager_in_charge" class="profile_label">Manager In Charge:</label></div><div class="col-sm-10 manager_in_charge_div_chill"><select class="form-control" style="text-align:right;width: 300px;" name="manager_in_charge" id="manager_in_charge"></select></div>';

                    $(".manager_in_charge_div").append($a); 
                    $(".manager_in_charge_div").attr("style","margin-bottom: 65px;");

                    $.each(response.result, function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        console.log($('#role').val());
                        if(manager_in_charge_value != null && key == manager_in_charge_value)
                        {
                            option.attr('selected', 'selected');
                        }
                        
                        $("#manager_in_charge").append(option);
                    });
                }
                else
                {
                    $(".manager_in_charge_div_chill").remove(); 
                    $(".manager_in_charge_div").removeAttr( 'style' );
                }
            }
        }
    });

    $(document).on('change',"#role",function() {
        $role = $("#role option:selected").text();
        $role_value = $("#role option:selected").val();
        $.ajax({
            type: "GET",
            url: "auth/get_manager_name",
            success: function(response){
                response = JSON.parse(response)
                //console.log(response.result);
                if(response.result.length != 0)
                {
                    if($role_value == 3 || $role_value == 6)
                    {
                        $(".manager_in_charge_div_chill").remove(); 
                        $(".manager_in_charge_div").removeAttr( 'style' );

                        $a = "";
                        $a = '<div class="col-sm-2 manager_in_charge_div_chill" style="margin-left: -15px; margin-right: 5px;"><label for="manager_in_charge" class="profile_label">Manager In Charge:</label></div><div class="col-sm-10 manager_in_charge_div_chill"><select class="form-control" style="text-align:right;width: 300px;" name="manager_in_charge" id="manager_in_charge"></select></div>';

                        $(".manager_in_charge_div").append($a); 
                        $(".manager_in_charge_div").attr("style","margin-bottom: 65px;");

                        $.each(response.result, function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);

                            // if(claim_below_info[t]["type_id"] != null && key == claim_below_info[t]["type_id"])
                            // {
                            //     option.attr('selected', 'selected');
                            // }
                            
                            $("#manager_in_charge").append(option);
                        });
                    }
                    else
                    {
                        $(".manager_in_charge_div_chill").remove(); 
                        $(".manager_in_charge_div").removeAttr( 'style' );
                    }
                }
            }
        });
    });
	

	if(part == "access_client")
    {
    	$.ajax({
	        type: "GET",
	        url: "auth/get_client",
	        dataType: "json",
	        async: false,
	        success: function(data){
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    var str = <?php echo json_encode($user) ?>;
						var temp = new Array();
						if(str)
						{
							// this will return an array with strings "1", "2", etc.
							temp = str["client_id"].split(",");
							
							for($k = 0; $k < temp.length; $k++)
							{
								if(key == temp[$k])
		                    	{
		                    		option.attr('selected', 'selected');
		                    	}
							}
						}
	                    $('#selected_client').append(option);
	                });
	            }
	            else{
	                alert(data.msg);
	            }
	        }               
	    });
    }
    else
    {
    	$.ajax({
	        type: "GET",
	        url: "auth/get_firm",
	        dataType: "json",
	        async: false,
	        //data: {"currency": client_charges[i]["currency"]},
	        success: function(data){
	            //$("#form"+$count_charges+" #currency"+$count_charges+"").find("option:eq(0)").html("Select Currency");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);


	                    var str = <?php echo json_encode($user->firm_id) ?>;
						var temp = new Array();
						if(str)
						{
							// this will return an array with strings "1", "2", etc.
							temp = str.split(",");
							
							for($k = 0; $k < temp.length; $k++)
							{
								if(key == temp[$k])
		                    	{
		                    		option.attr('selected', 'selected');
		                    	}
							}
						}
	                    $('#selected_firm').append(option);
	                });
	                $('#update_profile').bootstrapValidator('addField', "selected_firm[]", selected_firm);
	            }
	            else{
	                alert(data.msg);
	            }
	        }               
	    });
    }


    toastr.options = {

	  "positionClass": "toast-bottom-right"

	}

    
    $(document).on("submit",function(e){
        e.preventDefault();

        var $form = $(e.target);
    
        // and the FormValidation instance
        var fv = $form.data('formValidation');
        //console.log(fv);
        // Get the first invalid field
        var $invalidFields = fv.getInvalidFields().eq(0);
        // Get the tab that contains the first invalid field
        var $tabPane     = $invalidFields.parents();
        var valid_setup = fv.isValidContainer($tabPane);

        if(valid_setup)
        {
	        if(part == "access_client")
            {
	        	var link = 'auth/edit_user/'+ <?php echo json_encode($user->id);?> + "/access_client";
	        }
	        else
	        {
	        	var link = 'auth/edit_user/'+ <?php echo json_encode($user->id);?>;
	        }

	        $('#loadingmessage').show();
	        //console.log(link);
	        $.ajax({ //Upload common input
                type: "POST",
                url: link,
                data: $("#update_profile").serialize(),
                dataType: 'json',
                success: function (response) {
                	console.log(response);
                	$('#loadingmessage').hide();
                	//console.log(response.client_billing["client_billing_data"]);
                    //if (response.Status === 1) {
                    	toastr.success(response.message, response.title);

                    	if(response.direct == "homepage")
                    	{
                    		window.location.href = base_url;
                    	}
                    	else if(response.direct == "user_page")
                    	{
                    		window.location.href = base_url + "auth/users/";
                    	}
                    	else if(response.direct == "user_client_page")
                    	{
                    		window.location.href = base_url + "auth/client/";
                    	}
                    	
                    //}
                }
            });
	    }
    });

	$(document).on('click',"#update",function(e){
	    $("#update_profile").submit();
	});

    $(document).on('click',"#change_password_button",function(e){
        e.preventDefault();
        
        if(part == "access_client")
        {
        	var link = 'auth/profile_change_password/access_client';
        }
        else
        {
        	var link = 'auth/profile_change_password';
        }
        $('#loadingmessage').show();
        $.ajax({ //Upload common input
            url: link,
            type: "POST",
            data: $("#change-password-form").serialize(),
            dataType: 'json',
            success: function (response) {
            	$('#loadingmessage').hide();
            	//console.log(response);
                //if (response.Status === 1) {
                	
                	if(response != null)
                	{
                    	if(response.Status == 2)
                    	{
                    		toastr.success(response.message, response.title);
                    		window.location.href = base_url;
                    		//window.location.href = base_url + "auth/profile/" +response.user_id+ "/#cpassword";
                    	}
                    	else if(response.Status == 1)
                    	{
                    		toastr.success(response.message, response.title);
                    		window.location.href = base_url + "auth/users/";
                    	}
                    	else if(response.Status == 3)
                    	{
                    		toastr.error(response.message, response.title);
                    	}
                    	else if(response.Status == 4)
                    	{
                    		toastr.success(response.message, response.title);
                    		window.location.href = base_url + "auth/client/";
                    	}
                    }
                	
                //}
            }
        });
    });
</script>