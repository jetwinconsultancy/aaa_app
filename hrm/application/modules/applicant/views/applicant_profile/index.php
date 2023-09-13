<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>application/css/modules/applicant/applicant_profile/purple.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>application/css/modules/applicant/applicant_profile/print.css" media="print"/>
<!--[if IE 7]>
<link href="css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 6]>
<link href="css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src="<?=base_url()?>application/js/custom/applicant_profile/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>application/js/custom/applicant_profile/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?=base_url()?>application/js/custom/applicant_profile/cufon.yui.js"></script>
<script type="text/javascript" src="<?=base_url()?>application/js/custom/applicant_profile/scrollTo.js"></script>
<script type="text/javascript" src="<?=base_url()?>application/js/custom/applicant_profile/myriad.js"></script>
<script type="text/javascript" src="<?=base_url()?>application/js/custom/applicant_profile/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>application/js/custom/applicant_profile/custom.js"></script>
<script type="text/javascript">
		Cufon.replace('h1,h2');
</script>
<!-- Begin Wrapper -->
<!-- <?php echo json_encode($applicant_profile); ?> -->
<div id="wrapper">
  <div class="wrapper-top"></div>
  <div class="wrapper-mid">
    <!-- Begin Paper -->
    <div id="paper">
      <div class="paper-top"></div>
      <div id="paper-mid">
        <div class="entry">
          <!-- Begin Image -->
          <?php 
            if(empty($applicant_profile->pic)){
              echo '<img class="portrait" src="'. base_url() .'assets/custom/applicant_profile/image.jpg" />';
            }else{
              echo '<img class="portrait" src="'. $applicant_profile->pic .'" />';
            }
          ?>
          <!-- <img class="portrait" src="<?=base_url()?>uploads/claim/Most-Beautiful-People-Emma-Watson.jpg" /> -->
          
          <!-- End Image -->
          <!-- Begin Personal Information -->
          <div class="self">
            <h1 class="name"><?php echo $applicant_profile->name; ?><br />
              <!-- <span>Interactive Designer</span> -->
            </h1>
            <ul>
              <li class="ad" style="height:200%">
                <!-- <?php echo $applicant_profile->unit_no_floor . $applicant_profile->unit_no . " ". $applicant_profile->building_name .", ". $applicant_profile->street_name . "," . $applicant_profile->postal_code ?> -->
                <?php echo $applicant_profile->address ?>
              </li>
              <li class="mail"><?php echo $applicant_profile->email; ?></li>
              <li class="tel"><?php echo $applicant_profile->phoneno; ?></li>
            </ul>
          </div>
          <!-- End Personal Information -->
          <!-- Begin Social -->
          <div class="social">
            <ul>
              <!-- <li><a class='north' href="#" title="Download .pdf"><img src="<?=base_url()?>assets/custom/applicant_profile/icn-save.jpg" alt="Download the pdf version" /></a></li> -->
              <li><a class='north' href="javascript:window.print()" title="Print"><img src="<?=base_url()?>assets/custom/applicant_profile/icn-print.jpg" alt="" /></a></li>
              <!-- <li><a class='north' id="contact" href="contact/index.html" title="Contact Me"><img src="<?=base_url()?>assets/custom/applicant_profile/icn-contact.jpg" alt="" /></a></li> -->
              <!-- <li><a class='north' href="#" title="Follow me on Twitter"><img src="<?=base_url()?>assets/custom/applicant_profile/icn-twitter.jpg" alt="" /></a></li> -->
            <!--   <li><a class='north' href="#" title="My Facebook Profile"><img src="<?=base_url()?>assets/custom/applicant_profile/icn-facebook.jpg" alt="" /></a></li> -->
            </ul>
          </div>
          <!-- End Social -->
        </div>
        <!-- Begin 1st Row -->
        <!-- <div class="entry">
          <h2>Expected Salary</h2>
          <p><?php echo $applicant_profile->expected_salary; ?></p>
        </div> -->
        <div class="entry">
          <h2>ABOUT ME</h2>
          <p><?php echo $applicant_profile->about; ?></p>
        </div>
        <!-- End 1st Row -->
        <!-- Begin 2nd Row -->
        <div class="entry">
          <h2>EDUCATION</h2>
          <?php
            if(count($education) > 0){
                // echo json_encode($education);
              foreach($education as $row){
                echo '<div class="content">
                        <h3>'. $row->graduate_month .' '. $row->graduate_year .'</h3>
                        <p>'. $row->uni_name .', '. $row->uni_country .' <br />
                          <em>'. $row->qualification .' in '. $row->uni_fieldOfStudy .'</em>
                          <em> Major : '. $row->major .'</em>';

                          if($row->grade == "CGPA/Percentage"){
                            echo '<em> CGPA : '. $row->score .'/'. $row->total_score .'</em>';
                          }else{
                            echo '<em> Grade: '. $row->grade .'</em>';
                          }
                  echo    '<em style="margin-top:20px;">'. $row->additional_info .'</em>
                        </p>
                      </div>';
              }
            }else{
              echo '<h3></h3><p>No education background available.</p>';
            }
          ?>
        </div>
        <!-- End 2nd Row -->
        <!-- Begin 3rd Row -->
        <div class="entry">
          <h2>EXPERIENCE</h2>
          <?php 
            if(count($experience) > 0){
              // '<h3>May 2009 - Feb 2010</h3>'
              foreach($experience as $row)
              echo '<div class="content">
                      <h3>'. $row->join_month .' '. $row->join_year .'</h3>
                      <p>'. $row->company_name .', '. $row->country .' <br />
                        <em>Industry: '. $row->industry .'</em>
                        <em>Specialization: '. $row->specialization .'</em>
                        <em>Role: '. $row->role .'</em>
                        <em>Position Level: '. $row->position_level .'</em>
                        <em>Monthly Salary: '. $row->monthly_salary_currency .' '. $row->monthly_salary_amount .'</em>
                        <em style="margin-top:20px;">'. $row->experience_description .'</em>
                      </p>
                    </div>';
            }else{
              echo '<h3></h3><p>No experience background available.</p>';
            }
          ?>
        </div>
        
        <div class="entry">
          <h2>LANGUAGE</h2>
          <div class="content" style="padding-left: 11%;">
            <?php 
              if(count($language) > 0){
                // echo json_encode($language);
                echo '<table class="table">
                        <thead>
                          <th>Languages</th>
                          <th>Spoken</th>
                          <th>Written</th>
                        </thead>
                        <tbody>';
                          foreach($language as $row){
                            echo '<tr>
                                    <td>'. $row->name .'</td>
                                    <td>'. $row->spoken .'</td>
                                    <td>'. $row->written .'</td>
                                 </tr>';
                          }
                echo    '</tbody>
                      </table>';
              }else{
                echo '<h3></h3><p>No language background available.</p>';
              }
            ?>
          </div>
        </div>

        <div class="entry">
          <h2>PROFESSIONAL</h2>
          <?php 
            // echo json_encode($professional);
            if(count($professional) > 0){
              foreach($professional as $row){
                echo 
                '<div class="content">
                  <h3 style="padding-left: 30px;">'. $row->professional_body .' </h3>
                  <p>
                    <em>Membership no: '. $row->membership_no .'</em>
                    <em>Type: '. $row->membership_type .'</em>
                    <em>Awarded on: '. $row->membership_awarded .'</em>
                  </p>
                </div>';
              }
              
              // echo '';
            //   // '<h3>May 2009 - Feb 2010</h3>'
            //   foreach($experience as $row)
            //   echo '<h3>'. $row->join_month .' '. $row->join_year .'</h3>
            //         <p>'. $row->company_name .', '. $row->country .' <br />
            //           <em>Industry: '. $row->industry .'</em>
            //           <em>Specialization: '. $row->specialization .'</em>
            //           <em>Role: '. $row->role .'</em>
            //           <em>Position Level: '. $row->position_level .'</em>
            //           <em>Monthly Salary: '. $row->monthly_salary_currency .' '. $row->monthly_salary_amount .'</em>
            //           <em style="margin-top:20px;">'. $row->experience_description .'</em>
            //         </p>';
            }else{
              echo '<h3></h3><p>No professional background available.</p>';
            }
          ?>
        </div>

        <div class="entry">
          <h2>REFERRAL</h2>
          <?php 
            // echo json_encode($referral);

            if(count($referral) > 0){
              foreach($referral as $row){
                echo '<div class="content">
                        <h3 style="padding-left: 30px;">'. $row->name .'</h3>
                        <p>
                          <em> Company: '. $row->company .'</em>
                          <em> Position: '. $row->job_title .'</em>
                          <em> Phone no. :'. $row->phoneno .'</em>
                          <em> Email: '. $row->email .'</em>
                        </p>
                      </div>';
              }
            }else{
              echo '<h3></h3><p>No referral background available.</p>';
            }
          ?>
        </div>
        <!-- End 3rd Row -->
        <!-- Begin 4th Row -->
        <!-- <div class="entry">
          <h2>SKILLS</h2>
          <div class="content">
            <h3>Software Knowledge</h3>
            <ul class="skills">
              <li>Photoshop</li>
              <li>Illustrator</li>
              <li>InDesign</li>
              <li>Flash</li>
              <li>Fireworks</li>
              <li>Dreamweaver</li>
              <li>After Effects</li>
              <li>Cinema 4D</li>
              <li>Maya</li>
            </ul>
          </div>
          <div class="content">
            <h3>Languages</h3>
            <ul class="skills">
              <li>CSS/XHTML</li>
              <li>PHP</li>
              <li>JavaScript</li>
              <li>Ruby on Rails</li>
              <li>ActionScript</li>
              <li>C++</li>
            </ul>
          </div>
        </div> -->
        <!-- End 4th Row -->
         <!-- Begin 5th Row -->
        <!-- <div class="entry">
        <h2>WORKS</h2>
        	<ul class="works">
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/1.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/2.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/3.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/1.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/2.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/3.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/1.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        		<li><a href="<?=base_url()?>assets/custom/applicant_profile/1.jpg" rel="gallery" title="Lorem ipsum dolor sit amet."><img src="<?=base_url()?>assets/custom/applicant_profile/image.jpg" alt="" /></a></li>
        	</ul>
        </div> -->
        <!-- Begin 5th Row -->
      </div>
      <div class="clear"></div>
      <div class="paper-bottom"></div>
    </div>
    <!-- End Paper -->
  </div>
  <div class="wrapper-bottom"></div>

  <div style="text-align: center">
        <?php 
            echo '<a href="'.base_url().'interview" class="btn pull-left btn_cancel" style="margin:0.5%; cursor: pointer;">Back to previous page</a>';
        ?>
    </div>

</div>
<!-- <div id="message"><a href="#top" id="top-link">Go to Top</a></div> -->
<!-- End Wrapper -->

<div class="form-group row"></div>
