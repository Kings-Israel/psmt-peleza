<?php require_once('Connections/connect.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$currentPage = $_SERVER["PHP_SELF"];

$client_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_id'])) {
  $client_id_get_psmt_requests = $_SESSION['MM_client_id'];
}
$client_login_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_login_id'])) {
$client_login_id_get_psmt_requests = $_SESSION['MM_client_login_id'];
}

?><!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Peleza Screening and Monitoring Tool Kit">
	<meta name="author" content="Peleza">
	<title>PSMT-Peleza Screening Management Toolkit</title>

	<!-- Favicons-->
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">

	<!-- BASE CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
	<link href="css/menu.css" rel="stylesheet">
	<link href="css/vendors.css" rel="stylesheet">
	<link href="css/icon_fonts/css/all_icons_min.css" rel="stylesheet">
    
	<!-- YOUR CUSTOM CSS -->
	<link href="css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">

</head>

<body>

	<div id="preloader" class="Fixed">
		<div data-loader="circle-side"></div>
	</div>
	<!-- /Preload-->
	
	<div id="page">		
	<header class="static">	
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
						<a href="dashboard/index.php" title="PSMT"><img src="img/Peleza_Logo_We_Get_It.png" data-retina="true" alt="" width="163" height="36"></a>
				  </div>
				</div>
				<div class="col-lg-9 col-6">
					<ul id="top_access">
                        <li><a href="<?php echo $logoutAction ?>" class="btn_1 small2"><i class="icon-logout" style="font-size:15px"></i><span id="mybuttontext">SIGN OUT</span></a></li>
				  </ul>
					<nav id="menu" class="main-menu">
						<ul>
                            <li><span><a href="dashboard/index.php">Dashboard</a></span></li>
							<li>
								<span><a href="#0">Make a Request</a></span>
								<ul>
				           <?php


$query_getpackagenames = sprintf("SELECT package_id, package_name, client_id FROM pel_client_package where client_id = %s ", GetSQLValueString($client_id_get_psmt_requests, "int"));
$getpackagenames = mysqli_query($connect,$query_getpackagenames) or die(mysqli_error());
$row_getpackagenames = mysqli_fetch_assoc($getpackagenames);
$totalRows_getpackagenames = mysqli_num_rows($getpackagenames);	
$queryitem = "(";
if($totalRows_getpackagenames > '0')
{			
					
					?>
<?php 
								$x = 1;
								do { 
															
					//$queryitem .= " request_plan = '".$row_getpackagenames['package_name']."'";
					
					$queryitem .= "'".$row_getpackagenames['package_name']."',";
								?>	<li><a href="request.php?package_id=<?php echo $row_getpackagenames['package_id']; ?>"> <?php echo $row_getpackagenames['package_name']; ?> </a></li>
						  <?php 
						
						  $x++;
						  } while ($row_getpackagenames = mysqli_fetch_assoc($getpackagenames)); 
						  
					
						  ?>
		
   <?php
   }
   else
   
  {
  
$query_getpackagegeneral = sprintf("SELECT package_id, package_name FROM pel_package where package_general = '11'");
$getpackagegeneral = mysqli_query($connect,$query_getpackagegeneral) or die(mysqli_error());
$row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral);
$totalRows_getpackagegeneral = mysqli_num_rows($getpackagegeneral);	

$queryitem .= "";
	$x = 1;
								do { 
								$queryitem .= "'".$row_getpackagegeneral['package_name']."',";
								?>	<li><a href="request.php?package_id=<?php echo $row_getpackagegeneral['package_id']; ?>"> <?php echo $row_getpackagegeneral['package_name']; ?> </a></li>
						<?php
						  } while ($row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral)); 
						  

						  ?>
				
<?php
 }
 $queryitem .= "'')";
   ?>              
								
								</ul>
							</li><li><span><a href="reports/index.php">Reports</a></span></li>
							<li><span><a href="cart/cart.php">My Cart</a></span></li>
<li><span><a href="payments.php">Payments</a></span></li>
							<li><span><a href="downloads.php"  class="active">Downloads</a></span></li>                            
                            <li><span><a href="faq.php">FAQs</a></span></li>
                            <li><span><a href="testapi.php">APIs</a></span></li>
                            <li><span><a href="profile.php">My Profile</a></span></li>
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
		 <div id="results">
       <div class="container">
           <div class="row">
               <div class="col-md-8">
                   <h4><strong>Welcome, </strong> <?php echo 	$_SESSION['MM_first_name']; ?> ! <strong><br/>CLIENT ID: </strong><?php echo 	$_SESSION['MM_client_company_id']; ?> </h4>
               </div>
              	<div class="col-md-4">
						<div class="search_bar_list">
							<input type="text" class="form-control" placeholder="Ex. Name, Ref Number ....">
							<input type="submit" value="Search">
						</div>
					</div>			
           </div>
           <!-- /row -->
       </div>
       <!-- /container -->
   </div>
   <!-- /results -->
<div class="container margin_60_35">
			<div class="main_title">
				<h2>BACKGROUND SCREENING DOWNLOAD FILES</h2>
				<p>All documents are subject to copyright and is sole propoerty of Peleza International Limited. Documents shall not be sent and shared with third party wihtout prior consent of Peleza International Limited.</p>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<a href="#" class="box_cat_home">
						<i class="icon-info-4"></i>
					<!--	<img src="img/icon_cat_1.svg" width="60" height="60" alt="">
                        <i class="pe-7s-note"></i>-->
                        
						<h3>CONSENT FORM</h3>
						<ul class="clearfix">
							<li><strong>PDF</strong></li>
							<li><strong>VIEW</strong></li>
						</ul>
					</a>
				</div>
			
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->

		
	</main>
	<!-- /main content -->
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

	<!-- COMMON SCRIPTS -->
	<script src="js/jquery-2.2.4.min.js"></script>
	<script src="js/common_scripts.min.js"></script>
	<script src="js/functions.js"></script>

</body>

</html>