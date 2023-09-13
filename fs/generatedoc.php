<?php
	// include "header.php";
	session_start();
	$servername = "localhost";
	$username_s="root";
	// $username_s="ster1432_khusus";
	$password_s='';
	// $password_s='Sp3ci4l';
	$dbname='dot';
	// $dbname='ster1432_dot';
	 
	function SQLex($perintah)
	{
		// Create connection
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username_s'], $GLOBALS['password_s'], $GLOBALS['dbname']);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$hasil = $conn->query($perintah);
		mysqli_close($conn);
		// echo "A";
		return ($hasil);
	}
	$now = getDate();
	$bulan_romawi[1] = "I";
	$bulan_romawi[2] = "II";
	$bulan_romawi[3] = "III";
	$bulan_romawi[4] = "IV";
	$bulan_romawi[5] = "V";
	$bulan_romawi[6] = "VI";
	$bulan_romawi[7] = "VII";
	$bulan_romawi[8] = "VIII";
	$bulan_romawi[9] = "IX";
	$bulan_romawi[10] = "X";
	$bulan_romawi[11] = "XI";
	$bulan_romawi[12] = "XII";
	function convert_3digit($nilai1){
		if ($nilai1 < 10)
		{
			return "00".$nilai1;
		} else if($nilai1 <100)
		{
			return "0".$nilai1;
		}else
		{
			return $nilai1;
		}
	}
	
		require_once 'PHPWord-m/PhpWord.php';
		$PHPWord = new PHPWord();
		$document ="";
		// $document = $PHPWord->loadTemplate('templates.docx');
		// print_r($_GET['content']);
			// $b = str_replace('}',"","A");
			// $b = explode('${',$b);
		// print_r($b);
		if (isset($_GET['id']))
		{
			// echo "select * from  waitingdocument where id=".$_GET['id'];
			$a = SQLex("select A.*,B.field1,B.field2,B.field3,B.field4,B.field5,B.field6 from  waitingdocument A,service B where A.id=".$_GET['id']." and A.id_service = B.id");
			// echo $a;
			$c = mysqli_fetch_assoc($a);
			// echo "<pre>";
			// print_r($c);
			// print_r(json_decode($c['content']));
			$konten = (array) json_decode($c['content']);
			// print_r($konten['konten']);
			// echo "</pre>";
			if(isset($c['field1']) && $c['field1'] != '')
			{
				$document = $PHPWord->loadTemplate('uploads/'.$c['field1']);
				foreach($konten['konten'] as $key=>$value)
				{
					$document->setValue($key, $value);
				}
				// $document->setValue('myVariabel', 'Sun');
				$filename1 = 'dot'.$now[0].mt_rand(1,10000).'.docx';
				
				$document->save($filename1);
				// echo "update swaitingdocument set uploadfile='".$filename1."' where id=".$_GET['id'];
				SQLex("update waitingdocument set upload_file='".$filename1."' where id=".$_GET['id']);
			}
			if(isset($c['field2']) && $c['field2'] != '')
			{
				$document = $PHPWord->loadTemplate($c['field2']);
				foreach($konten['konten'] as $key=>$value)
				{
						$document->setValue($key, $value);
				}
				// $document->setValue('myVariabel', 'Sun');
				$filename2 = 'dot'.$now[0].mt_rand(1,10000).'.docx';
				
				$document->save($filename2);
				SQLex("update waitingdocument set uploadfile2='".$filename2."' where id=".$_GET['id']);
			}
			if(isset($c['field3']) && $c['field3'] != '')
			{
				$document = $PHPWord->loadTemplate($c['field3']);
				foreach($konten['konten'] as $key=>$value)
				{
						$document->setValue($key, $value);
				}
				// $document->setValue('myVariabel', 'Sun');
				$filename3 = 'dot'.$now[0].mt_rand(1,10000).'.docx';
				
				$document->save($filename3);
				SQLex("update waitingdocument set uploadfile3='".$filename3."' where id=".$_GET['id']);
			}
			if(isset($c['field4']) && $c['field4'] != '')
			{
				$document = $PHPWord->loadTemplate($c['field4']);
				foreach($konten['konten'] as $key=>$value)
				{
						$document->setValue($key, $value);
				}
				// $document->setValue('myVariabel', 'Sun');
				$filename4= 'dot'.$now[0].mt_rand(1,10000).'.docx';
				
				$document->save($filename4);
				SQLex("update waitingdocument set uploadfile4='".$filename4."' where id=".$_GET['id']);
			}
			if(isset($c['field5']) && $c['field5'] != '')
			{
				$document = $PHPWord->loadTemplate($c['field5']);
				foreach($konten['konten'] as $key=>$value)
				{
						$document->setValue($key, $value);
				}
				// $document->setValue('myVariabel', 'Sun');
				$filename5 = 'dot'.$now[0].mt_rand(1,10000).'.docx';
				
				$document->save($filename5);
				SQLex("update waitingdocument set uploadfile5='".$filename5."' where id=".$_GET['id']);
			}
			if(isset($c['field6']) && $c['field6'] != '')
			{
				$document = $PHPWord->loadTemplate($c['field6']);
				foreach($konten['konten'] as $key=>$value)
				{
						$document->setValue($key, $value);
				}
				// $document->setValue('myVariabel', 'Sun');
				$filename6 = 'dot'.$now[0].mt_rand(1,10000).'.docx';
				
				$document->save($filename6);
				SQLex("update waitingdocument set uploadfile6='".$filename6."' where id=".$_GET['id']);
			}
		} else {
			$document = $PHPWord->loadTemplate('templates.docx');
			if(isset($_SESSION['field']))
			{
				foreach($_SESSION['field'] as $a)
				{
					$document->setValue($a[0], $a[1]);
				}
			}
			$document->setValue('myVariabel', 'Sun');
			$filename = 'dot'.$now[0].mt_rand(1,10000).'.docx';
			
			$document->save($filename);
		}
	// }
	$assets = 'themes/default/assets';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>

    <!-- Vendor CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
        <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
        <link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

    <!-- Theme CSS -->
        <link rel="stylesheet" href="assets/stylesheets/theme.css" />

    <!-- Skin CSS -->
        <link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

        <!-- Head Libs -->
        <script src="assets/vendor/modernizr/modernizr.js"></script>

    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/respond.min.js"></script>
    <![endif]-->

</head>

<body class="login-page" style="background-color: #E6E6FA;">
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>
<div class="page-back">
    
    <div id="login">
         <section class="body-sign">
             <div class="center-sign">
            
             <div class="panel panel-sign">
                   <!--  <div class="panel-title-sign mt-xl text-right">
                        <h2 class="title text-uppercase text-weight-bold m-none" style="font-size:14px; font-weight:normal;" ><i class="fa fa-user mr-xs"></i> Sign In</h2>
                    </div> -->
           
            <div class="panel-body" style="background-color: #F5F5F5; border-radius: 10px;" >
                <div class="text-center"></div>
            <!-- <div class="login-form-div"> -->
                <!-- <div class="login-content"> -->
                    <?php 
if(isset($filename1)) echo 'Your Document has generated <a href="'.$filename1.'">Click Here to Download Your Document</a>';
if(isset($filename2)) echo 'Your Document has generated <a href="'.$filename2.'">Click Here to Download Your Document</a>';
if(isset($filename3)) echo 'Your Document has generated <a href="'.$filename3.'">Click Here to Download Your Document</a>';
if(isset($filename4)) echo 'Your Document has generated <a href="'.$filename4.'">Click Here to Download Your Document</a>';
if(isset($filename5)) echo 'Your Document has generated <a href="'.$filename5.'">Click Here to Download Your Document</a>';
if(isset($filename6)) echo 'Your Document has generated <a href="'.$filename6.'">Click Here to Download Your Document</a>';
?>
                        
            <!-- </div> -->
            <!-- </div> -->
            </div>
        </div>
    </div>

</div>

<script src="<?= $assets ?>js/jquery.js"></script>
<!-- <script src="<?= $assets ?>js/bootstrap.min.js"></script> -->
<script src="<?= $assets ?>js/jquery.cookie.js"></script>
<script src="<?= $assets ?>js/login.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var hash = window.location.hash;
        if (hash && hash != '') {
            $("#login").hide();
            $(hash).show();
        }
    });
</script>
   
    <!-- Vendor -->
        <!-- <script src="assets/vendor/jquery/jquery.js"></script> -->
        <script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
        <script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
        <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
        <script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

    <!-- Theme Custom -->
        <script src="assets/javascripts/theme.custom.js"></script>

    <!-- Theme Base, Components and Settings -->
        <script src="assets/javascripts/theme.js"></script>

    <!-- Theme Initialization Files -->
        <script src="assets/javascripts/theme.init.js"></script>    

</body>
</html>
