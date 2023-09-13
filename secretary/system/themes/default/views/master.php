<?php
	// $field = $this->sma->field_service();
?>
<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs">		
				<ul class="nav nav-tabs nav-justify">
					<li class="">
						<a href="#w2-sharetype" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">1</span>
							Share Type
						</a>
					</li>
					<li class="">
						<a href="#w2-currency" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">2</span>
							Currency
						</a>
					</li>
					<li class="">
						<a href="#w2-citizen" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">3</span>
							Citizen
						</a>
					</li>
					<li class="check_stat">
						<a href="#w2-typeofdoc" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">4</span>
							TypeOfDoc
						</a>
					</li>
					<li class="check_stat">
						<a href="#w2-doccategory" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">5</span>
							DocCategory
						</a>
					</li>
					<li class="check_stat">
						<a href="#w2-service" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">6</span>
							Service
						</a>
					</li>
					<li class="check_stat">
						<a href="#w2-logbook" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">7</span>
							Logbook
						</a>
					</li>
					<li class="active">
						<a href="#w2-field" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">8</span>
							Field
						</a>
					</li>
				</ul>
				
					<div class="tab-content" style="overflow:show;display: block;">
						<div id="w2-currency" class="tab-pane">
							<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
								<thead>
									<tr>
										<th>No</th>
										<th>Currency</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="service_body">
									<?php
									$i = 1;
										foreach($currency as $a)
										{
											// print_r($a);
											echo '<tr>
													<td>'.$i.'</td>
													<td><span href="" style="height:45px;font-weight:bold;" class="edit_currency  pointer amber" data-id="'.$a->id.'" data-info="'.$a->currency.'">'.$a->currency.'</span></td>
													<td>
														<a href="master/remove_currency/'.$a->id.'" class="fa fa-trash pointer amber"></span>
													</td>
												</tr>';
												// <span href="" class="fa fa-pencil edit_share pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'"></span>
														
											$i++;
										}
									?>
									
								</tbody>
							</table>
							<div class="col-md-8 col-md-offset-2">
								<?php 
									$attrib = array('data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("master/save_currency", $attrib); ?>
									<div class="col-md-12">
										<label>Currency</label>
											<input type="hidden" name="id" id="currency_id" class="form-control input-sm" value="">
										<div class="input-group">
											<input type="text" name="currency" id="currency" class="form-control input-sm">
											<span class="input-group-btn">
												<input class="btn btn-primary" type="submit" style="height:30px;padding-top:4px;" value="Save"/>
												<input class="btn btn-default" type="reset" style="height:30px;padding-top:4px;width:80px;" value="Cancel"/>
											</span>
											
										</div>
									</div>
								<?php echo form_close(); ?>
							
							</div>
							&nbsp;<br/>
							&nbsp;<br/>
							&nbsp;<br/>
							&nbsp;<br/>
						</div>
						<div id="w2-typeofdoc" class="tab-pane">
							<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
								<thead>
									<tr>
										<th>No</th>
										<th>TypeOfDoc</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="service_body">
									<?php
									$i = 1;
										foreach($typeofdoc as $a)
										{
											// print_r($a);
											echo '<tr>
													<td>'.$i.'</td>
													<td><span href="" style="height:45px;font-weight:bold;" class="edit_typeofdoc  pointer amber" data-id="'.$a->id.'" data-info="'.$a->typeofdoc.'">'.$a->typeofdoc.'</span></td>
													<td>
														<a href="master/remove_typeofdoc/'.$a->id.'" class="fa fa-trash pointer amber"></span>
													</td>
												</tr>';
												// <span href="" class="fa fa-pencil edit_share pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'"></span>
														
											$i++;
										}
									?>
									
								</tbody>
							</table>
							<div class="col-md-8 col-md-offset-2">
								<?php 
									$attrib = array('data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("master/save_typeofdoc", $attrib); ?>
									<div class="col-md-12">
										<label>TypeOfDoc</label>
											<input type="hidden" name="id" id="typeofdoc_id" class="form-control input-sm" value="">
										<div class="input-group">
											<input type="text" name="typeofdoc" id="typeofdoc" class="form-control input-sm">
											<span class="input-group-btn">
												<input class="btn btn-primary" type="submit" style="height:30px;padding-top:4px;" value="Save"/>
												<input class="btn btn-default" type="reset" style="height:30px;padding-top:4px;width:80px;" value="Cancel"/>
											</span>
											
										</div>
									</div>
								<?php echo form_close(); ?>
							</div>
							&nbsp;<br/>
							&nbsp;<br/>
							&nbsp;<br/>
						</div>
						<div id="w2-doccategory" class="tab-pane">
							<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
								<thead>
									<tr>
										<th>No</th>
										<th>DocCategory</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="service_body">
									<?php
									$i = 1;
										foreach($doccategory as $a)
										{
											// print_r($a);
											echo '<tr>
													<td>'.$i.'</td>
													<td><span href="" style="height:45px;font-weight:bold;" class="edit_typeofdoc  pointer amber" data-id="'.$a->id.'" data-info="'.$a->doccategory.'">'.$a->doccategory.'</span></td>
													<td>
														<a href="master/remove_doccategory/'.$a->id.'" class="fa fa-trash pointer amber"></span>
													</td>
												</tr>';
												// <span href="" class="fa fa-pencil edit_share pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'"></span>
														
											$i++;
										}
									?>
									
								</tbody>
							</table>
							<div class="col-md-8 col-md-offset-2">
								<?php 
									$attrib = array('data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("master/save_doccategory", $attrib); ?>
									<div class="col-md-12">
										<label>DocCategory</label>
											<input type="hidden" name="id" id="doccategory_id" class="form-control input-sm" value="">
										<div class="input-group">
											<input type="text" name="doccategory" id="doccategory" class="form-control input-sm">
											<span class="input-group-btn">
												<input class="btn btn-primary" type="submit" style="height:30px;padding-top:4px;" value="Save"/>
												<input class="btn btn-default" type="reset" style="height:30px;padding-top:4px;width:80px;" value="Cancel"/>
											</span>
											
										</div>
									</div>
								<?php echo form_close(); ?>
							</div>
						</div>
						<div id="w2-citizen" class="tab-pane">
							<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
								<thead>
									<tr>
										<th>No</th>
										<th>Citizen</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="service_body">
									<?php
									$i = 1;
										foreach($citizen as $a)
										{
											echo '<tr>
													<td>'.$i.'</td>
													<td><span href="" style="height:45px;font-weight:bold;" class="edit_citizen pointer amber" data-id="'.$a->id.'" data-info="'.$a->citizen.'">'.$a->citizen.'</span></td>
													<td>
														<a href="master/remove_citizen/'.$a->id.'" class="fa fa-trash pointer amber"></span>
													</td>
												</tr>';	
											$i++;
										}
									?>
									
								</tbody>
							</table>
							<div class="col-md-8 col-md-offset-2">
								<?php 
									$attrib = array('data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("master/save_citizen", $attrib); ?>
									<div class="col-md-12">
										<label>Citizen</label>
											<input type="hidden" name="id" id="citizen_id" class="form-control input-sm" value="">
										<div class="input-group">
											<input type="text" name="citizen" id="citizen" class="form-control input-sm">
											<span class="input-group-btn">
												<input class="btn btn-primary" type="submit" style="height:30px;padding-top:4px;" value="Save"/>
												<input class="btn btn-default" type="reset" style="height:30px;padding-top:4px;width:80px;" value="Cancel"/>
											</span>
											
										</div>
									</div>
								<?php echo form_close(); ?>
							</div>
							&nbsp;<br/>
							&nbsp;<br/>
							&nbsp;<br/>
						</div>
						<div id="w2-sharetype" class="tab-pane">
							<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
								<thead>
									<tr>
										<th>No</th>
										<th>Share Type</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="service_body">
									<?php
									$i = 1;
										foreach($sharetype as $a)
										{
											// print_r($a);
											echo '<tr>
													<td>'.$i.'</td>
													<td><span href="" style="height:45px;font-weight:bold;" class="edit_share  pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'">'.$a->sharetype.'</span></td>
													<td>
														<a href="master/remove_sharetype/'.$a->id.'" class="fa fa-trash pointer amber"></span>
													</td>
												</tr>';
												// <span href="" class="fa fa-pencil edit_share pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'"></span>
														
											$i++;
										}
									?>
									
								</tbody>
							</table>
							<div class="col-md-8 col-md-offset-2">
								<?php 
									$attrib = array('data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("master/save_share", $attrib); ?>
									<div class="col-md-12">
										<label>Share Type</label>
											<input type="hidden" name="id" id="share_id" class="form-control input-sm" value="">
										<div class="input-group">
											<input type="text" name="sharetype" id="sharetype" class="form-control input-sm">
											<span class="input-group-btn">
												<input class="btn btn-primary" type="submit" style="height:30px;padding-top:4px;" value="Save"/>
												<input class="btn btn-default" type="reset" style="height:30px;padding-top:4px;width:80px;" value="Cancel"/>
											</span>
											
										</div>
									</div>
								<?php echo form_close(); ?>
							</div>
						
							&nbsp;<br/>
							&nbsp;<br/>
							&nbsp;<br/>
						</div>
						<div id="w2-service" class="tab-pane">
							<header class=" text-right">
									<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
									<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
									
								<span class="edit_client amber pointer" data-target="#modal_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Add Service" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i>Add Service</span>
									
							</header>
							<div class="bordered">
								<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
									<thead>
										<tr>
											<th>Service</th>
											<th>Price</th>
											<th>Link to Field</th>
											<th>Link to Document</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="service_body">
										<tr>
											<td><a href="#" data-target="#modal_edit_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Edit Service">Service 1</a></td>
											<td class="text-right">$ 1.000,00</td>
											<td class="text-right">
												Company Info -> Field 1 <br/>
												Company Info -> Field 2 <br/>
												Officer -> Name<br/>
												Officer -> Birthday <br/>
											</td>
											<td class="text-right">
												Document 1 <br/>
												Document 2 <br/>
												Document 3 <br/>
											</td>
											<td>
												<a href="#" class="fa fa-trash"></a>
											</td>
										</tr>
										<tr>
											<td><a href="#" data-target="#modal_edit_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Edit Service">Service 2</a></td>
											<td class="text-right">$ 1.500,00</td>
											<td class="text-right">
												Company Info -> Field 3 <br/>
												Company Info -> Field 4 <br/>
												Officer -> Nationality<br/>
												Officer -> Certificate <br/>
											</td>
											<td class="text-right">
												Document 4 <br/>
												Document 5 <br/>
											</td>
											<td>
												</a>
												<a href="#" class="fa fa-trash"></a>
											</td>
										</tr>
									</tbody>
								</table>
							
							</div>&nbsp;<br/>
						</div>
						<div id="w2-postcode" class="tab-pane">
							<div class="bordered">
								<span class="btn btn-primary" data-toggle="modal" data-target="#modal_postcode">Add Post Code</span>
								<table class="table table-bordered table-striped table-condensed mb-none">
										<tr>
											<th>Post Code</th>
											<th>Area</th>
										</tr>
										<tbody id="post_body">
											<tr>
												<td>14400</td>
												<td class="text-right">Jakarta Barat</td>
											</tr>
											<tr>
												<td>14500</td>
												<td class="text-right">Jakarta Utara</td>
											</tr>
										</tbody>
									</table>
							</div>
						</div>
						<div id="w2-logbook" class="tab-pane">
							<div class="bordered">
								<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
									<thead>
										<tr>
											<th>Date</th>
											<th>User</th>
											<th>Action</th>
											<th>Changes</th>
										</tr>
									</thead>
									<tbody id="post_body">
										<tr>
											<td>04/10/2017 21:00:50</td>
											<td>User 1</td>
											<td>Edit Company</td>
											<td>Value 1 -> Value 2</td>
										</tr>
										<tr>
											<td>04/10/2017 20:00:50</td>
											<td>User 2</td>
											<td>ADD Company</td>
											<td>Company : bestCOMP</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div id="w2-backup" class="tab-pane">
							<div class="bordered">
								<div class="col-md-6">
									<button class="btn btn-primary modal-dismiss">Backup</button>
									<table class="table table-bordered table-striped table-condensed mb-none">
										<tr>
											<th>Date</th>
											<th>File</th>
										</tr>
										<tbody id="post_body">
											<tr>
												<td>04/10/2017</td>
												<td class="text-right"><a href="#">backup_04_10_2017.zip</a></td>
											</tr>
											<tr>
												<td>03/01/2017</td>
												<td class="text-right"><a href="#">backup_03_01_2017.zip</a></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-6">
									<input type="file" class="form-control">
									<button class="btn btn-primary">Restore</button>
								</div>
								
							</div>
						</div>
						<div id="w2-field" class="tab-pane active">
							<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
								<thead>
									<tr>
										<th>No</th>
										<th>title</th>
										<th>kode</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="service_body">
									<?php
									$i = 1;
										foreach($kolom as $a)
										{
											// print_r($a);
											echo '<tr>
													<td>'.$i.'</td>
													<td><span href="" style="height:45px;font-weight:bold;" class="edit_kolom  pointer amber" data-id="'.$a->id.'" data-info="'.$a->kode.'">'.$a->title.'</span></td>
													<td>'.$a->kode.'</span></td>
													<td>
														<a href="master/remove_kolom/'.$a->id.'" class="fa fa-trash pointer amber"></span>
													</td>
												</tr>';
												// <span href="" class="fa fa-pencil edit_share pointer amber" data-id="'.$a->id.'" data-info="'.$a->sharetype.'"></span>
														
											$i++;
										}
									?>
									
								</tbody>
							</table>
							<div class="col-md-8 col-md-offset-2">
								<?php 
									$attrib = array('data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("master/save_kolom", $attrib); ?>
									<div class="col-md-12">
										<label>Field</label>
											<input type="hidden" name="id" id="kolom_id" class="form-control input-sm" value="">
										<div class="input-group">
											<label>title</label>
											<input type="text" name="title_kolom" id="title_kolom" class="form-control input-sm">
											<label>Code</label>
											<input type="text" name="kode_kolom" id="kode_kolom" class="form-control input-sm">
											<span class="input-group-btn">
												<input class="btn btn-primary" type="submit" style="height:30px;padding-top:4px;" value="Save"/>
												<input class="btn btn-default" type="reset" style="height:30px;padding-top:4px;width:80px;" value="Cancel"/>
											</span>
											
										</div>
									</div>
								<?php echo form_close(); ?>
							
							</div>
							&nbsp;<br/>
							&nbsp;<br/>
							&nbsp;<br/>
							&nbsp;<br/>
						</div>
						
					</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-primary modal-dismiss">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			<div>							
													
		</div>
	</div>
	
</section>
</div>
	<div id="modal_service_setting" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<header class="panel-heading">
					<h2 class="panel-title">Service Setting</h2>
				</header>
				<?php 
					$attrib = array('data-toggle' => 'validator', 'role' => 'form');
					echo form_open_multipart("master/save_service", $attrib); ?>
				<div class="modal-body panel-body" >
					<div class="col-md-12">
						<label>Service</label>
						<select data-plugin-selectTwo id="select_edit_p" name="service_type" class="form-control populate">
							<optgroup label="Type">
								<option value="auditor">Auditor</option>
								<option value="company">Company</option>
								<option value="individual">Individual</option>
							</optgroup>
						</select>
					</div>
					<div class="col-md-6">
						<label>Service Name</label>
						<input type="text" name="service_name" class="form-control ">
					</div>
					<div class="col-md-6">
						<label>Price</label>
						<input type="text" name="service_price" class="form-control number">
					</div>
					<div class="col-md-6" id="div_field">
						<h1>Field </h1>
								<?php
								
									foreach($kolom as $a)
									{
										// echo '<label><input type="checkbox" value="'.$a->kode.'">'.$a->title.'</label>';
									}
								?>
						<select class="form-control populate" multiple="multiple" size="15" name="field_service[]" id="field_service">
										<optgroup label="Type">
								<?php
								foreach($kolom as $a)
									{
										
										echo '<option value="'.$a->kode.'">'.$a->title.'</option>';
										// echo '<label><input type="checkbox" value="'.$a->kode.'">'.$a->title.'</label>';
									}
										echo '</optgroup>';
									// foreach($field as $key=>$value)
									// {
										// echo '<optgroup label="key">';
										// echo '<option value="'.$key.'">'.$value.'</option>';
										// echo '</optgroup>';
									// }
								?>
						</select>
					</div>
					<div class="col-md-6" id="div_document">
						<p>Word document Uploaded must contain Simbol (type not copy paste) or you can see <a href="phpdocx.php?content=" id="link_download">Sample Document here</a></p>
						<p id="fild_you_can_used">
							Field You can used :<br/>
							${Unique identifier}<br/>
							${Name}<br/>
							${Client_Company}<br/>
						</p>
						<h3>Default for Dot Exe,LTE PTE :</h3>
						<p>
							Field You can used :<br/>
							${comp_name}<br/>
							${comp_street}<br/>
							${comp_block}<br/>
							${comp_city}<br/>
							${comp_country}<br/>
							${comp_phone}<br/>
							${comp_fax}<br/>
							${comp_mail}<br/>
						
						</p>
					</div>
					
				<h3>Upload Document</h3>
					<div class="col-md-12">
						<input type="file" name="files1" class="form-control"></br>
						<input type="file" name="files2" class="form-control"></br>
						<input type="file" name="files3" class="form-control"></br>
						<input type="file" name="files4" class="form-control"></br>
						<input type="file" name="files5" class="form-control"></br>
						<input type="file" name="files6" class="form-control"></br>
					</div>
					
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-primary">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</div>
						<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<div id="modal_edit_service_setting" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<header class="panel-heading">
					<h2 class="panel-title">Service Setting</h2>
				</header>
			  </div>
				<div class="modal-body panel-body" >
					<div class="col-md-12">
						<label>Service</label>
						<select data-plugin-selectTwo id="select_edit_p" class="form-control populate">
							<optgroup label="Type">
								<option value="aud">Auditor</option>
								<option value="comp">Company</option>
								<option value="indv">Individual</option>
							</optgroup>
						</select>
					</div>
					<div class="col-md-12">
						<label>Service Name</label>
						<input type="text" class="form-control ">
					</div>
					<div class="col-md-6" id="div_field">
						<h1>Field <span id="add_field" class="amber"><i class="fa fa-plus"></i></span></h1>
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Field 1</option>
								<option value="HI">Field 2</option>
								<option value="FIN">Field 3</option>
								<option value="Pas">Field 4</option>
							</optgroup>
						</select>
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Field 1</option>
								<option value="HI">Field 2</option>
								<option value="FIN">Field 3</option>
								<option value="Pas">Field 4</option>
							</optgroup>
						</select>
					</div>
					<div class="col-md-6" id="div_document">
						<p>Word document Uploaded must contain Mail Merge Field or you can <a href="#">Download Sample Document here</a></p>
						<p>
							Field You can used :<br/>
							${Unique identifier}<br/>
							${Name}<br/>
							${Client_Company}<br/>
						</p>
						<h3>Default for Dot Exe,LTE PTE :</h3>
						<p>
							Field You can used :<br/>
							${comp_name}<br/>
							${comp_street}<br/>
							${comp_block}<br/>
							${comp_city}<br/>
							${comp_country}<br/>
							${comp_phone}<br/>
							${comp_fax}<br/>
							${comp_mail}<br/>
						
						</p>
					</div>
					<table class="table">
						<tr>
							<td><a href="#">Document 1</a></td>
							<td><input type="file" class="form-control"></td>
							<td><a href="#" class="btn btn-primary">Replace</a></td>
						</tr>
						<tr>
							<td><a href="#">Document 2</a></td>
							<td><input type="file" class="form-control"></td>
							<td><a href="#" class="btn btn-primary">Replace</a></td>
						</tr>
						<tr>
							<td><a href="#">Document 3</a></td>
							<td><input type="file" class="form-control"></td>
							<td><a href="#" class="btn btn-primary">Replace</a></td>
						</tr>
					</table>
					<h3>Upload Document</h3>
					<div class="col-md-12">
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
					</div>
					
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-primary modal-dismiss">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<script>
	$(".edit_share").on('click',function(){
		$("#share_id").val($(this).data('id'));
		$("#sharetype").val($(this).data('info'));
	});
	$(".edit_currency").on('click',function(){
		$("#currency_id").val($(this).data('id'));
		$("#currency").val($(this).data('info'));
	});
	$(".edit_typeofdoc").on('click',function(){
		$("#typeofdoc_id").val($(this).data('id'));
		$("#typeofdoc").val($(this).data('info'));
	});
	$(".edit_doccategory").on('click',function(){
		$("#doccategory_id").val($(this).data('id'));
		$("#doccategory").val($(this).data('info'));
	});
	$(".edit_citizen").on('click',function(){
		$("#citizen_id").val($(this).data('id'));
		$("#citizen").val($(this).data('info'));
	});
	$("#field_service").on('click',function(){
		
		$value="";
		$val="";
		$.each($(this).val(),function(k,value){
			console.log(value);
			$val +="${"+value+"}";
			$value +="${"+value+"}<br/>";
		});
		$("#fild_you_can_used").html("Field You can used :<br/>" +$value);
		// $value.replace("<br/>","");
		$("#link_download").attr('href','phpdocx.php?content='+$val);
	});
	$("#add_field").on('click',function(){
		$html = '<select data-plugin-selectTwo class="form-control populate">' +
							'<optgroup label="Type">' +
								'<option value="AK">Field 1</option>' +
								'<option value="HI">Field 2</option>' +
								'<option value="FIN">Field 3</option>' +
								'<option value="Pas">Field 4</option>' +
							'</optgroup>' +
						'</select>';
		$("#div_field").append($html);
	});
	$("#add_documet").on('click',function(){
		$html = '<select data-plugin-selectTwo class="form-control populate">' +
							'<optgroup label="Type">' +
								'<option value="AK">Document1</option>' +
								'<option value="HI">Document2</option>' +
								'<option value="FIN">Document3</option>' +
								'<option value="Pas">Document4</option>' +
							'</optgroup>' +
						'</select>';
		$("#div_document").append($html);
	});
</script>
<style>
	.select2-container {
		display:block;
	}
	.dataTables_filter {
		display:none;
	}
	.dataTables_length label{
		width:100%;
	}
</style>