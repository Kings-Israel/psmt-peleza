<?php require_once('../Connections/connect.php'); ?><?php
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
	
  $logoutGoTo = "../login/";
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

$MM_restrictGoTo = "../";
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
else
{
?>
  <meta http-equiv="Refresh" content="900; url=../login/">
  
<?php

}
?><?php
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
$colname_getcredit = "-1";
if (isset($_SESSION['MM_client_id'])) {
  $colname_getcredit = $_SESSION['MM_client_id'];
}

$query_getcredit = sprintf("SELECT client_credits FROM pel_client WHERE client_id = %s", GetSQLValueString($colname_getcredit, "int"));
$getcredit = mysqli_query($connect,$query_getcredit) or die(mysqli_error());
$row_getcredit = mysqli_fetch_assoc($getcredit);
$totalRows_getcredit = mysqli_num_rows($getcredit);

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$colname_getpaymentsall = "-1";

if (isset($_GET['pesapal_transaction_tracking_id']) && isset($_GET['pesapal_merchant_reference']) ) {
 $source_ref = $_GET['pesapal_transaction_tracking_id'];
 $payment_ref = $_GET['pesapal_merchant_reference'];
 $clientcredits =  $row_getcredit['client_credits'];


$query_getmypayments2 = "SELECT * FROM pel_payments WHERE payment_ref = '$payment_ref'";
$getmypayments2 = mysqli_query($connect,$query_getmypayments2) or die(mysqli_error());
$row_getmypayments2 = mysqli_fetch_assoc($getmypayments2);
$totalRows_getmypayments2 = mysqli_num_rows($getmypayments2);
  
$updateSQL = sprintf("UPDATE pel_payments SET source_ref=%s, status='11',payment_status = '11' WHERE payment_ref=%s",
                       GetSQLValueString($source_ref, "text"),
                       GetSQLValueString($payment_ref, "text"));

  
  $Result1 = mysqli_query($connect,$updateSQL) or die(mysqli_error());
  

$currentcredits = $row_getmypayments2['plan_volume'] + $clientcredits;
    $amountcredits = number_format($currentcredits, 2);
	
  $updateSQL2 = sprintf("UPDATE pel_client SET client_credits=%s WHERE client_id=%s",
                       GetSQLValueString($amountcredits, "text"),
					   GetSQLValueString($colname_getcredit, "int"));

  
  $Result2 = mysqli_query($connect,$updateSQL2) or die(mysqli_error());
  

  
  /*  $updateGoTo = "mypayments.php";
 if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));*/
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "verifyform")) {

  $updateSQL = sprintf("UPDATE pel_payments SET source_ref=%s, status='11' WHERE client_id=%s and payment_id=%s",
                       GetSQLValueString($_POST['source_ref'], "text"),
                       GetSQLValueString($_POST['client_id'], "int"),
					   GetSQLValueString($_POST['payment_id'], "int"));

  
  $Result1 = mysqli_query($connect,$updateSQL) or die(mysqli_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "postform")) {

  $updateSQL = sprintf("UPDATE pel_payments SET payment_status='11' WHERE client_id=%s and payment_id=%s",
                       GetSQLValueString($_POST['client_id'], "int"),
					   GetSQLValueString($_POST['payment_id'], "int"));

  
  $Result1 = mysqli_query($connect,$updateSQL) or die(mysqli_error());
  
  $currentcredits = $row_getcredit['client_credits'] + $_POST['client_credits'];
  
  $updateSQL2 = sprintf("UPDATE pel_client SET client_credits=%s WHERE client_id=%s",
                       GetSQLValueString($currentcredits, "text"),
					   GetSQLValueString($_POST['client_id'], "int"));

  
  $Result2 = mysqli_query($connect,$updateSQL2) or die(mysqli_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}





?><!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="View your EdCheck Payments and Invoices">
	<meta name="author" content="EdCheck Africa">
	<title>EdCheck Africa - My Edcheck Payments</title>

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
   
    <!-- SPECIFIC CSS -->
    <link href="../css/date_picker.css" rel="stylesheet">
    
	<!-- YOUR CUSTOM CSS -->
	<link href="../css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">

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
						<a href="../" title="EdCheck Africa Logo"><img src="../img/logo.png" data-retina="true" alt="" width="163" height="36"></a>					</div>
				</div>
				<div class="col-lg-9 col-6">
					<ul id="top_access">
							<li><a href="<?php echo $logoutAction ?>" class="btn_1 small2"><i class="icon-logout" style="font-size:15px"></i><span id="mybuttontext">SIGN OUT</span></a></li>
					</ul>
					<nav id="menu" class="main-menu">
						<ul>
							<li>
								<span><a  class="active" href="../">Home</a></span>							</li>
                            <li>
								<span><a href="../index.php#howitworks">How it works</a></span>							</li>
                            <li>
								<span><a href="../whycheck.html">Why</a></span>							</li>
                            <li><span><a href="../pricing.html">Pricing</a></span></li>
                            <li><span><a href="../faq.html">FAQ</a></span></li>
                            <li><span><a href="../contacts.html">Contact</a></span></li>
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
      
<div id="results" class="filters_listing" style="background-image:url(../img/banner1.jpg); background-position:center; background-repeat:no-repeat;">
		<div class="container">
<div class="col-lg-12">
			<ul class="clearfix">
				<li>
					<h1 style="color:#FFFFFF"><strong> Hello, <?php echo 	$_SESSION['MM_first_name']; ?> !</strong></h1>
				</li>
            <li>
					<h1 align="center" style="color:#cbd332; font-weight:bolder"><strong> <?php echo $row_getcredit['client_credits']; ?></strong><strong> CREDITS</strong></h1>
              
                    <!--    <h3 align="center" style="color:#fff"><strong> AVAILABLE</strong></h3>-->
			  </li>
			</ul>
            </div>
  </div>
		<!-- /container -->
	</div>
	<!-- /filters -->
	   
	<div class="container margin_60_35">
		<div class="row">
       	  <aside class="col-lg-3" id="sidebar">
			
                  
                  
                  <div class="icon2-bar">
  <a href="../dashboard"><img height="30px" width="30px" src="../img/homeicon.png"></a>
  <a href="../search"><img  width="30px" src="../img/search-icon.png"></a>
  <a   href="../payment"><img  height="30px" width="30px"  src="../img/payment-icon.png"></a>
  <a href="../mysearches"><img  width="30px" src="../img/my-searches-icon.png"></a>
  <a class="active" style="background-color:#DDFF4B; color:#153F56;" href="../mypayments"><img  width="30px" src="../img/my-payments.png"></a>
  <a href="../myaccount"><img  width="30px" src="../img/my-account.png"></a></div>
                  
                <div class="icon-bar2">
  <a href="../dashboard">Dashboard</a>
  <a href="../search">Search</a>
  <a  href="../payment">Payment Plans</a>
  <a  href="../mysearches">My Searches</a>
  <a class="active" style="background-color:#153F56; color:#DDFF4B" href="../mypayments">My Payments</a>
  <a href="../myaccount">Account</a></div>
</aside>
			<!-- /aside -->
        
     
				<div class="col-xl-9 col-lg-9">
				<div class="box_general_3 cart">
                
						<h4>My Payments</h4>
			
                
				
         	<div class="tabs_styled_2">
						<ul class="nav nav-tabs" role="tablist"> <li class="nav-item">
								<a class="nav-link active" id="posted-tab" data-toggle="tab" href="#posted" role="tab" aria-controls="posted" aria-expanded="true">POSTED CREDITS</a>
							</li> 
                            <li class="nav-item">
								<a class="nav-link" id="nonposted-tab" data-toggle="tab" href="#nonposted" role="tab" aria-controls="nonposted">NON POSTED</a>
							</li>
						
						<li class="nav-item">
								<a class="nav-link" id="notcomplete-tab" data-toggle="tab" href="#notcomplete" role="tab" aria-controls="notcomplete">NOT COMPLETE</a>
							</li>
						</ul>
						<!--/nav-tabs -->

						<div class="tab-content">
                        
                            
							<div class="tab-pane fade  show active" id="posted" role="tabpanel" aria-labelledby="posted-tab">
                            <div class="row">
                 <?php


$client_id = $_SESSION['MM_client_id'];
$maxRows_getmypayments = 20;
$pageNum_getmypayments = 0;
if (isset($_GET['pageNum_getmypayments'])) {
  $pageNum_getmypayments = $_GET['pageNum_getmypayments'];
}
$startRow_getmypayments = $pageNum_getmypayments * $maxRows_getmypayments;


$query_getmypayments = "SELECT * FROM pel_payments WHERE client_id = '$client_id' and status='11' and payment_status='11' ORDER BY payment_date DESC ";
$query_limit_getmypayments = sprintf("%s LIMIT %d, %d", $query_getmypayments, $startRow_getmypayments, $maxRows_getmypayments);
$getmypayments = mysqli_query($connect,$query_limit_getmypayments) or die(mysqli_error());
$row_getmypayments = mysqli_fetch_assoc($getmypayments);

if (isset($_GET['totalRows_getmypayments'])) {
  $totalRows_getmypayments = $_GET['totalRows_getmypayments'];
} else {
  $all_getmypayments = mysqli_query($connect,$query_getmypayments);
  $totalRows_getmypayments = mysqli_num_rows($all_getmypayments);
}
$totalPages_getmypayments = ceil($totalRows_getmypayments/$maxRows_getmypayments)-1;



$queryString_getmypayments = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getmypayments") == false && 
        stristr($param, "totalRows_getmypayments") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getmypayments = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getmypayments = sprintf("&totalRows_getmypayments=%d%s", $totalRows_getmypayments, $queryString_getmypayments);

				 do { 
				 
               
?>					<div class="col-md-6">
						<div class="box_list wow fadeIn" style="border-color:#FF0000">
							<div class="wrapper">								
								<h6 style="color:#153F56"><?php echo $row_getmypayments['pay_source']; ?></h6>
                                <medium>DATE:<strong> <?php echo $row_getmypayments['payment_date']; ?></strong></medium>
								<p>PAYMENT REF: <strong><?php echo $row_getmypayments['payment_ref']; ?></strong><br/>ACCOUNT: <strong><?php echo $row_getmypayments['payment_account']; ?></strong><br/>AMOUNT: <strong><?php echo $row_getmypayments['amount']; ?></strong><br/>CREDITS: <strong><?php echo $row_getmypayments['plan_volume']; ?></strong></p>
								<span class="rating"><strong>STATUS:</strong><?php 
														
														if($row_getmypayments['status']=='11' and $row_getmypayments['payment_status']=='11')
														{
														?>
                                                        
                                                        <span style="color:#006600">SUCCESS  <i class="icon_star voted"></i></span>	 <a  target="_blank" href="invoice.php"><i class="ti-printer"></i></a> 
                                                        <?php
														}
														?></span>
							</div>
											</div>
					</div>
					<!-- /box_list -->
  <?php } while ($row_getmypayments = mysqli_fetch_assoc($getmypayments)); ?>
  
    <nav aria-label="" class="add_top_20">
					<ul class="pagination pagination-sm">
                    <li class="page-item">
               <?php if ($pageNum_getmypayments > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_getmypayments=%d%s", $currentPage, 0, $queryString_getmypayments); ?>" class="page-link">First</a>
					
                          <?php } // Show if not first page ?>	
                          </li>     <li class="page-item">
                   <?php if ($pageNum_getmypayments > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_getmypayments=%d%s", $currentPage, max(0, $pageNum_getmypayments - 1), $queryString_getmypayments); ?>" class="page-link">Previous</a>
                          <?php } // Show if not first page ?> </li>     <li class="page-item">
                 <?php if ($pageNum_getmypayments < $totalPages_getmypayments) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_getmypayments=%d%s", $currentPage, min($totalPages_getmypayments, $pageNum_getmypayments + 1), $queryString_getmypayments); ?>" class="page-link">Next</a>
                          <?php } // Show if not last page ?> </li>     <li class="page-item">
                  <?php if ($pageNum_getmypayments < $totalPages_getmypayments) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_getmypayments=%d%s", $currentPage, $totalPages_getmypayments, $queryString_getmypayments); ?>" class="page-link">Last</a>
                          <?php } // Show if not last page ?>      </li>    	</ul>
				</nav>
          </div>        
    </div>
                            <div class="tab-pane fade" id="nonposted" role="tabpanel" aria-labelledby="nonposted-tab">
                     <div class="row">    
                     
                       <?php


$client_id = $_SESSION['MM_client_id'];
$maxRows_getmypaymentsnonposted = 20;
$pageNum_getmypaymentsnonposted = 0;
if (isset($_GET['pageNum_getmypaymentsnonposted'])) {
  $pageNum_getmypaymentsnonposted = $_GET['pageNum_getmypaymentsnonposted'];
}
$startRow_getmypaymentsnonposted = $pageNum_getmypaymentsnonposted * $maxRows_getmypaymentsnonposted;


$query_getmypaymentsnonposted = "SELECT * FROM pel_payments WHERE client_id = '$client_id' and status='11' and payment_status='00' ORDER BY payment_date DESC ";
$query_limit_getmypaymentsnonposted = sprintf("%s LIMIT %d, %d", $query_getmypaymentsnonposted, $startRow_getmypaymentsnonposted, $maxRows_getmypaymentsnonposted);
$getmypaymentsnonposted = mysqli_query($connect,$query_limit_getmypaymentsnonposted) or die(mysqli_error());
$row_getmypaymentsnonposted = mysqli_fetch_assoc($getmypaymentsnonposted);

if (isset($_GET['totalRows_getmypaymentsnonposted'])) {
  $totalRows_getmypaymentsnonposted = $_GET['totalRows_getmypaymentsnonposted'];
} else {
  $all_getmypaymentsnonposted = mysqli_query($connect,$query_getmypaymentsnonposted);
  $totalRows_getmypaymentsnonposted = mysqli_num_rows($all_getmypaymentsnonposted);
}
$totalPages_getmypaymentsnonposted = ceil($totalRows_getmypaymentsnonposted/$maxRows_getmypaymentsnonposted)-1;



$queryString_getmypaymentsnonposted = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getmypaymentsnonposted") == false && 
        stristr($param, "totalRows_getmypaymentsnonposted") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getmypaymentsnonposted = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getmypaymentsnonposted = sprintf("&totalRows_getmypaymentsnonposted=%d%s", $totalRows_getmypaymentsnonposted, $queryString_getmypaymentsnonposted);

				 do { 
				 
               
?>					<div class="col-md-6">
						<div class="box_list wow fadeIn" style="border-color:#FF0000">
							<div class="wrapper">								
								<h6 style="color:#153F56"><?php echo $row_getmypaymentsnonposted['pay_source']; ?></h6>
                                <medium>DATE:<strong> <?php echo $row_getmypaymentsnonposted['payment_date']; ?></strong></medium>
								<p>PAYMENT REF: <strong><?php echo $row_getmypaymentsnonposted['payment_ref']; ?></strong><br/>ACCOUNT: <strong><?php echo $row_getmypaymentsnonposted['payment_account']; ?></strong><br/>AMOUNT: <strong><?php echo $row_getmypaymentsnonposted['amount']; ?></strong><br/>CREDITS: <strong><?php echo $row_getmypaymentsnonposted['plan_volume']; ?></strong></p>
								<span class="rating"><strong>STATUS:</strong><?php 
														
														if($row_getmypaymentsnonposted['status']=='11' and $row_getmypaymentsnonposted['payment_status']=='00')
														{
														?>
                                                        
                                                        <span style="color:#006600">AWAITING POSTING</span>	 <a  target="_blank" href="invoice.php"><i class="ti-printer"></i></a> 
                                                        <?php
														}
														?></span>
							</div>
											</div>
					</div>
					<!-- /box_list -->
  <?php } while ($row_getmypaymentsnonposted = mysqli_fetch_assoc($getmypaymentsnonposted)); ?>
  
    <nav aria-label="" class="add_top_20">
					<ul class="pagination pagination-sm">
                    <li class="page-item">
               <?php if ($pageNum_getmypaymentsnonposted > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnonposted=%d%s", $currentPage, 0, $queryString_getmypaymentsnonposted); ?>" class="page-link">First</a>
					
                          <?php } // Show if not first page ?>	
                          </li>     <li class="page-item">
                   <?php if ($pageNum_getmypaymentsnonposted > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnonposted=%d%s", $currentPage, max(0, $pageNum_getmypaymentsnonposted - 1), $queryString_getmypaymentsnonposted); ?>" class="page-link">Previous</a>
                          <?php } // Show if not first page ?> </li>     <li class="page-item">
                 <?php if ($pageNum_getmypaymentsnonposted < $totalPages_getmypaymentsnonposted) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnonposted=%d%s", $currentPage, min($totalPages_getmypaymentsnonposted, $pageNum_getmypaymentsnonposted + 1), $queryString_getmypaymentsnonposted); ?>" class="page-link">Next</a>
                          <?php } // Show if not last page ?> </li>     <li class="page-item">
                  <?php if ($pageNum_getmypaymentsnonposted < $totalPages_getmypaymentsnonposted) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnonposted=%d%s", $currentPage, $totalPages_getmypaymentsnonposted, $queryString_getmypaymentsnonposted); ?>" class="page-link">Last</a>
                          <?php } // Show if not last page ?>      </li>    	</ul>
				</nav>
          </div>           </div>
                            <div class="tab-pane fade" id="notcomplete" role="tabpanel" aria-labelledby="notcomplete-tab">
                              <div class="row">    
                              
                              <?php


$client_id = $_SESSION['MM_client_id'];
$maxRows_getmypaymentsnotcomplete = 20;
$pageNum_getmypaymentsnotcomplete = 0;
if (isset($_GET['pageNum_getmypaymentsnotcomplete'])) {
  $pageNum_getmypaymentsnotcomplete = $_GET['pageNum_getmypaymentsnotcomplete'];
}
$startRow_getmypaymentsnotcomplete = $pageNum_getmypaymentsnotcomplete * $maxRows_getmypaymentsnotcomplete;


$query_getmypaymentsnotcomplete = "SELECT * FROM pel_payments WHERE client_id = '$client_id' and status='22' ORDER BY payment_date DESC ";
$query_limit_getmypaymentsnotcomplete = sprintf("%s LIMIT %d, %d", $query_getmypaymentsnotcomplete, $startRow_getmypaymentsnotcomplete, $maxRows_getmypaymentsnotcomplete);
$getmypaymentsnotcomplete = mysqli_query($connect,$query_limit_getmypaymentsnotcomplete) or die(mysqli_error());
$row_getmypaymentsnotcomplete = mysqli_fetch_assoc($getmypaymentsnotcomplete);

if (isset($_GET['totalRows_getmypaymentsnotcomplete'])) {
  $totalRows_getmypaymentsnotcomplete = $_GET['totalRows_getmypaymentsnotcomplete'];
} else {
  $all_getmypaymentsnotcomplete = mysqli_query($connect,$query_getmypaymentsnotcomplete);
  $totalRows_getmypaymentsnotcomplete = mysqli_num_rows($all_getmypaymentsnotcomplete);
}
$totalPages_getmypaymentsnotcomplete = ceil($totalRows_getmypaymentsnotcomplete/$maxRows_getmypaymentsnotcomplete)-1;



$queryString_getmypaymentsnotcomplete = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getmypaymentsnotcomplete") == false && 
        stristr($param, "totalRows_getmypaymentsnotcomplete") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getmypaymentsnotcomplete = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getmypaymentsnotcomplete = sprintf("&totalRows_getmypaymentsnotcomplete=%d%s", $totalRows_getmypaymentsnotcomplete, $queryString_getmypaymentsnotcomplete);

				 do { 
				 
               
?>					<div class="col-md-6">
						<div class="box_list wow fadeIn" style="border-color:#FF0000">
							<div class="wrapper">								
								<h6 style="color:#153F56"><?php echo $row_getmypaymentsnotcomplete['pay_source']; ?></h6>
                                <medium>DATE:<strong> <?php echo $row_getmypaymentsnotcomplete['payment_date']; ?></strong></medium>
								<p>PAYMENT REF: <strong><?php echo $row_getmypaymentsnotcomplete['payment_ref']; ?></strong><br/>ACCOUNT: <strong><?php echo $row_getmypaymentsnotcomplete['payment_account']; ?></strong><br/>AMOUNT: <strong><?php echo $row_getmypaymentsnotcomplete['amount']; ?></strong><br/>CREDITS: <strong><?php echo $row_getmypaymentsnotcomplete['plan_volume']; ?></strong></p>
								<span class="rating"><strong>STATUS:</strong><?php 
														
														if($row_getmypaymentsnotcomplete['status']=='22')
														{
														?>
                                                        
                                                        <span style="color:#FF0000">NOT COMPLETE</span>	 <?php
														}
														?></span>
							</div>
											</div>
					</div>
					<!-- /box_list -->
  <?php } while ($row_getmypaymentsnotcomplete = mysqli_fetch_assoc($getmypaymentsnotcomplete)); ?>
  
    <nav aria-label="" class="add_top_20">
					<ul class="pagination pagination-sm">
                    <li class="page-item">
               <?php if ($pageNum_getmypaymentsnotcomplete > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnotcomplete=%d%s", $currentPage, 0, $queryString_getmypaymentsnotcomplete); ?>" class="page-link">First</a>
					
                          <?php } // Show if not first page ?>	
                          </li>     <li class="page-item">
                   <?php if ($pageNum_getmypaymentsnotcomplete > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnotcomplete=%d%s", $currentPage, max(0, $pageNum_getmypaymentsnotcomplete - 1), $queryString_getmypaymentsnotcomplete); ?>" class="page-link">Previous</a>
                          <?php } // Show if not first page ?> </li>     <li class="page-item">
                 <?php if ($pageNum_getmypaymentsnotcomplete < $totalPages_getmypaymentsnotcomplete) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnotcomplete=%d%s", $currentPage, min($totalPages_getmypaymentsnotcomplete, $pageNum_getmypaymentsnotcomplete + 1), $queryString_getmypaymentsnotcomplete); ?>" class="page-link">Next</a>
                          <?php } // Show if not last page ?> </li>     <li class="page-item">
                  <?php if ($pageNum_getmypaymentsnotcomplete < $totalPages_getmypaymentsnotcomplete) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_getmypaymentsnotcomplete=%d%s", $currentPage, $totalPages_getmypaymentsnotcomplete, $queryString_getmypaymentsnotcomplete); ?>" class="page-link">Last</a>
                          <?php } // Show if not last page ?>      </li>    	</ul>
				</nav>  
                              
                              
                              
                              
                              
          </div>       
                            </div>
                    </div>
                    </div>
		
			
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</main>
	<!-- /main -->
	
	<footer>
		<div class="container2" style="background-color:#002e3f; ">	
        <div class="container">		
			<div class="row">
				<div  align="center" class="col-md-12" style="height:50px; padding:1%">
					<a href="../terms.html" style="color:#FFFFFF">Terms and conditions</a>  |  <font color="#FFFFFF">All Rights reserved &copy; 2018 - Designed & Developed by Peleza International Limited</font>				</div>
			
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
	<script src="../js/jquery-2.2.4.min.js"></script>
	<script src="../js/common_scripts.min.js"></script>
	<script src="../js/functions.js"></script>
    <script src="../js/choosen.js"></script>
	
	<!-- SPECIFIC SCRIPTS -->
	<script src="http://maps.googleapis.com/maps/api/js"></script>
    <script src="../js/map_listing.js"></script>
    <script src="../js/infobox.js"></script>
<script type="text/javascript">
$(".chosen").chosen();
</script>

</body>
</html><?php
mysqli_free_result($getmypayments);
mysqli_free_result($getmypaymentsnotcomplete);
mysqli_free_result($getmypaymentsnonposted);
mysqli_free_result($getcredit);
?>