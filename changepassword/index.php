<?php require_once('../Connections/connect.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(dbconnect(),$theValue) : mysqli_escape_string(dbconnect(),$theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}?><?php
 $errorcode = "";
				
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "loginpsmt")) {				
		  
		  //honey pot field
	$captcha = $_POST['g-recaptcha-response'];
	if (empty($captcha) ){
		 //   if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
//			{				
//		$captcha=$_POST['g-recaptcha-response'];
//$secretKey = '6Lehb78UAAAAADJUHm2rO9-gaVo0JfsfRzuGADLp';				
//$ip = $_SERVER['REMOTE_ADDR'];
//        // post request to server
//        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
//        $response = file_get_contents($url);
//        $responseKeys = json_decode($response,true);
//        // should return JSON with success as true
//        if($responseKeys["success"]) {
//     


// Given password
 $oldpassword=$_POST['oldpassword'];

$newpassword=$_POST['newpassword'];

$repeatpassword=$_POST['repeatpassword'];

// Validate password strength
$uppercase = preg_match('@[A-Z]@', $newpassword);
$lowercase = preg_match('@[a-z]@', $newpassword);
$number    = preg_match('@[0-9]@', $newpassword);
//$specialChars = preg_match('@[^\w]@', $password);|| !$specialChars

// Validate password strength
$uppercase2 = preg_match('@[A-Z]@', $repeatpassword);
$lowercase2 = preg_match('@[a-z]@', $repeatpassword);
$number2    = preg_match('@[0-9]@', $repeatpassword);

if(!$uppercase || !$lowercase || !$number  || strlen($newpassword) < 8 || !$uppercase2 || !$lowercase2 || !$number2  || strlen($repeatpassword) < 8) {
   $errorcode = " <p class='text-center link_bright'><strong>Password > = 8, atleast 1 upper case letter, 1 number.</strong></a></p>"; 
}
else if($newpassword !=$repeatpassword )
{
	 $errorcode = " <p class='text-center link_bright'><strong> New Password and Confirm New Password Do Not Match.</strong></a></p>"; 
}
else{
	
	 $loginUsername = $_SESSION['MM_Username'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "../";
  $MM_redirectLoginFailed = "changepassword/";
  $MM_redirectChangePassword = "changepassword/";
  $MM_redirecttoReferrer = false;
 // mysqli_select_db($connect,$database_connect);
  
//$LoginRS__query=sprintf("SELECT * FROM pel_client WHERE client_login_username=%s AND client_password=%s AND client_company_id=%s and status = '11' AND client_counter IN ('0','1','2','3')", GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"), GetSQLValueString($client_company_id, "text")); 
   
$LoginRS__query=sprintf("SELECT * FROM pel_client WHERE client_login_username=%s AND client_password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString(md5($oldpassword), "text")); 

  $LoginRS = mysqli_query($connect,$LoginRS__query) or die(mysqli_error());
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
   
echo $updateSQL = sprintf("UPDATE pel_client SET status = %s, client_password=%s WHERE client_email_address=%s",
                        GetSQLValueString("11", "text"),
						GetSQLValueString(md5($newpassword), "text"),
						GetSQLValueString($loginUsername, "text"));
					mysqli_query($connect,$updateSQL);
header("Location: ". $MM_redirectLoginSuccess);
  
  
} else {
	
	$errorcode = " <p class='text-center link_bright'><strong> Old Password Not Correct.</strong></a></p>"; 
}
}
 } else {
     $errorcode = " <p class='text-center link_bright'><strong>Error!!! on Page</strong></a></p>";
        }	
	
// }
// 
//  else {
//
//  $errorcode = " <p class='text-center link_bright'><strong>Please check the Checkbox</strong></a></p>";
////    header("Location: ". $MM_redirectLoginFailed );
//  }

			}					
						?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Peleza Screening and Monitoring Tool Kit">
	<meta name="author" content="Peleza">
	<title>PSMT-Peleza Screening Management Toolkit</title>

	<!-- Favicons-->
	<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" type="image/x-icon" href="../img/apple-touch-icon-57x57-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="../img/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="../img/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="../img/apple-touch-icon-144x144-precomposed.png">

	<!-- BASE CSS -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
	<link href="../css/menu.css" rel="stylesheet">
	<link href="../css/vendors.css" rel="stylesheet">
	<link href="../css/icon_fonts/css/all_icons_min.css" rel="stylesheet">
    
	<!-- YOUR CUSTOM CSS -->
	<link href="../css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">
	<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
    
    <style>
		.hide-robot{
			display:none;
		}
		</style>

</head>

<body>

	<div id="preloader" class="Fixed">
		<div data-loader="circle-side"></div>
	</div>
	<!-- /Preload-->
	
	<div id="page">		
	<header class="header_sticky">	
		<a href="#menu" class="btn_mobile">
			<div class="hamburger hamburger--spin" id="hamburger">
				<div class="hamburger-box">
					<div class="hamburger-inner"></div>
				</div>
			</div>
		</a>
		<!-- /btn_mobile-->
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-6">
					<div id="logo">
						<a href="#" title="PSMT"><img src="../img/Peleza_Logo_We_Get_It.png" data-retina="true" alt="" width="163" height="36"></a>
					</div>
				</div>
				<div class="col-lg-9 col-6">
				  <ul id="top_access">
					
					<li><a href="../contacts.html" class="btn_1 small"><i class="icon-mobile" style="font-size:15px"></i><span id="mybuttontext">Contact Admin</span></a></li>
					</ul>
					<nav id="menu" class="main-menu">
						<ul>
							
						</ul>
					</nav>
					<!-- /main-menu -->
				</div>
			</div>
		</div>
		<!-- /container -->
	</header>
	<!-- /header -->
	
	<main>
		<div id="hero_register">
			<div class="container margin_120_95_login">			
				<div class="row">
					<div class="col-lg-6">
						<h1>Redefining Background Screening!</h1>
						<p class="lead">We at Peleza are redefining how you conduct background screening by offering you a self service platform.</p>
						<div class="box_feat_2">
							<i class="pe-7s-note2"></i>
							<h3>Consent!</h3>
							<p>Always get a consent from the individual/organisation before you conduct background checks.</p>
						</div>
						<div class="box_feat_2">
							<i class="pe-7s-note"></i>
							<h3>Collect data</h3>
							<p>Collect data for the individual/organisation and submit to us easily for Backgorund screening.</p>
						</div>
						<div class="box_feat_2">
							<i class="pe-7s-display1"></i>
							<h3>Reports</h3>
							<p>Track progress, and receive digital reports which you can print from the comfort of your desk.</p>
						</div>
					</div>
					<!-- /col -->
					<div class="col-lg-5 ml-auto" style="margin-top:50px;">
						<div class="box_form">
                        <h2 class='text-center link_bright'><strong> CHANGE PASSWORD</strong></a></h2>
							<div id="message-register">      <?php  
	
echo $errorcode;
	
	?></div>
						<form name="loginuser" action="<?php echo $loginFormAction; ?>" method="POST" >
								<div class="row">
									<div class="col-md-12 ">
										<div class="form-group"><span id="sprytextfield1">
                                         <input type="password" class="form-control" placeholder="Enter Current Password" name="oldpassword" id="oldpassword" AUTOCOMPLETE="off" required>
                                        <span class="textfieldRequiredMsg">*.</span><span class="textfieldMinCharsMsg">*.</span></span></div>
									</div>
								
								</div>
								<!-- /row -->
								<div class="row">
									<div class="col-lg-12">
                                    <p class='text-center link_bright'><strong> password >8 Characters, Atleast 1 Capital Letter, Atleast 1 Lowercase Letter</strong></a></p>
										<div class="form-group"><span id="sprytextfield2">
                                        <input type="password" class="form-control" placeholder="Enter New Password" name="newpassword" id="newpassword" AUTOCOMPLETE="off" required>
                                        <span class="textfieldRequiredMsg">*.</span><span class="textfieldMinCharsMsg">*.</span></span></div>
									</div>
								</div>
								<!-- /row -->
								<div class="row">
									<div class="col-md-12">
										<div class="form-group"><span id="sprytextfield3">
                                        <input type="password" class="form-control" placeholder="Repeat New Password" name="repeatpassword" id="repeatpassword" AUTOCOMPLETE="off" required>
                                        <span class="textfieldRequiredMsg">*.</span><span class="textfieldMinCharsMsg">*.</span></span></div>
									</div>
								
								</div>
								<!-- /row -->
											<!--	<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											 <div class="g-recaptcha" data-sitekey="6Lehb78UAAAAAPfBjsgBynvKz1Ncpm-O6jtcR96D"></div>
										</div>
									</div>
								</div>-->
								<!-- /row -->
                                <input type="hidden" class="form-control" name="g-recaptcha-response" id="g-recaptcha-response">
							  <div>
                              
                              <div class="row">
									<div class="col-md-12">
										<div class="form-group">
											 <input type="submit" class="btn_1" value="Change Password" id="submit-register">
										</div>
									</div>
								</div>
                           
                              
                              	  <input type="hidden" name="MM_insert" value="loginpsmt">
							</form>
					  </div>
						<!-- /box_form -->
					</div>
					<!-- /col -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /hero_register -->
	</main>
	<!-- /main -->
	
	<footer>
		<div class="container margin_60_35">
			
<!--/row-->
			<hr>
			<div class="row">
				<div class="col-md-8">
					<ul id="additional_links">
						<li><a href="#0">Terms and conditions</a></li>
					  <li><a href="#0">Privacy</a></li>
				  </ul>
				</div>
				<div class="col-md-4">
					<div id="copy">© 2018 PSMT</div>
				</div>
			</div>
		</div>
	</footer>
	<!--/footer-->
	</div>
	<!-- page -->

	<div id="toTop"></div>
	<!-- Back to top button -->
      
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	<!-- COMMON SCRIPTS -->
	<script src="../js/jquery-2.2.4.min.js"></script>
	<script src="../js/common_scripts.min.js"></script>
	<script src="../js/functions.js"></script>
	
	<!-- SPECIFIC SCRIPTS -->
	<script src="../assets/validate.js"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"],validateOn:["change"], minChars:8});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"], minChars:8});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["change"], minChars:8});
</script>
</body>
</html>