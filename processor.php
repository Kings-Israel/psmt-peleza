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


?>
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
$colname_getcredit = "-1";

$query_getcredits = sprintf("SELECT pel_client.client_credits FROM pel_client WHERE client_id='$client_id_get_psmt_requests'");
$getcredits = mysqli_query($connect,$query_getcredits) or die(mysqli_error());
$row_getcredits = mysqli_fetch_assoc($getcredits);
if (isset($_SESSION['MM_client_credits'])) {
  $_SESSION['MM_client_credits'] = $row_getcredits['client_credits']; 
   $colname_getcredit = $_SESSION['MM_client_credits'];
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
						  <li><span><a href="cart/cart.php">Cart</a></span></li>
							<li><span><a href="payments.php"  class="active">Payments</a></span></li>                        
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
               <div class="col-md-6">
                   <h4><strong>Welcome, </strong> <?php echo 	$_SESSION['MM_first_name']; ?> ! <strong><br/>CLIENT ID: </strong><?php echo 	$_SESSION['MM_client_company_id']; ?> </h4>
               </div>
               <div class="col-md-6">
                    <div class="search_bar_list">
                  
                    <input type="submit" value="You Have <?php echo 	$_SESSION['MM_client_credits']; ?> Credits">
                </div>
               </div>
           </div>
           <!-- /row -->
       </div>
       <!-- /container -->
   </div>
  
  
  
  
   <div class="filters_listing">
		<div class="container">
			<ul class="clearfix">
            
            <li>
                 
					<h6>Sort by Status</h6>
                 
                      <div class="switch-field">
                    <input type="radio" id="all" name="type_patient" value="all" checked>
					
						<label for="all">PAYMENTS:</label>
			
						<a href="payments.php?set=checkout"><label for="New Request">CHECK OUT</label></a>
                        
						<a href="payments.php?set=mypayments"><label for="New Request">MY PAYMENTS</label></a>
                        
                        <a href="buycredits.php?set=buycredits"><label for="New Request">BUY CREDITS</label></a>
                      
                        <a href="cart/cart.php?status_search=77"><label for="New Request">PAYMENT CART</label></a>
					</div>
			  </li>
           <!--     
              <li>
                 
					<h6>Sort by Payment Type</h6>
                    
                    <form name="formc" action="<?php echo $editFormAction; ?>" method="POST" >
					<select name="datatype_search" class="selectbox" onChange="this.form.submit()">
                    <option value="ALL">ALL</option>
                    <option value="LPO">LPO</option>
                    <option value="PAYPAL">PAYPAL</option>
					<option value="MPESA">MPESA</option>
					<option value="CARD PAYMENT">CARD PAYMENT</option></select>
<input type="hidden" name="MM_insert" value="formd">
                </form>
			  </li>-->
			
				
			</ul>
		</div>
		<!-- /container -->
	</div>
	<!-- /filters -->
 <div class="container margin_60">
			<div class="row">	
							<div class="col-xl-12 col-lg-12">
				<div class="box_general_3 cart">
					<div class="message">
						<p>PAYMENT PROCESSING</p>
					</div>
   <?php
   
   
   
  if (isset($_GET['pesapal_transaction_tracking_id']) && isset($_GET['pesapal_merchant_reference']) ) {
 $source_ref = $_GET['pesapal_transaction_tracking_id'];
 $payment_ref = $_GET['pesapal_merchant_reference'];
 $clientid=  $_SESSION['MM_client_id'];
 $client_login_id=  $_SESSION['MM_client_login_id'];

	function GeraHash3($qtd){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
$QuantidadeCaracteres = strlen($Caracteres);
$QuantidadeCaracteres--;

$Hash=NULL;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }

return $Hash;
}
$datenow = date('dmyhis');
$payment_invoice_ref =  "PEL-".$client_login_id."-INV-".GeraHash3(4)."-".$datenow;


$query_getmypayments2 = "SELECT * FROM pel_payments WHERE payment_ref = '$payment_ref'";
$getmypayments2 = mysqli_query($connect,$query_getmypayments2) or die(mysqli_error());
$row_getmypayments2 = mysqli_fetch_assoc($getmypayments2);
$totalRows_getmypayments2 = mysqli_num_rows($getmypayments2);
  
$updateSQL = sprintf("UPDATE pel_payments SET source_ref=%s, status='11',payment_status = '11', invoice_number=%s WHERE payment_ref=%s",
                       GetSQLValueString($source_ref, "text"),
					   GetSQLValueString($payment_invoice_ref, "text"),
                       GetSQLValueString($payment_ref, "text"));

  
  $Result1 = mysqli_query($connect,$updateSQL) or die(mysqli_error());
  
  $sql_insert="UPDATE pel_psmt_request SET status='44', request_payment_ref='$payment_ref' WHERE status='77' and client_id='$clientid'";
			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());
				
    $result = mysqli_query($connect,$sql_insert) or die('Query failed: ' . mysqli_error());		

 if (mysqli_error()) {
   // echo 'Error:' . curl_error($ch);
	?>
    
    <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Your CREDITS Card payment request has failed</h2>
                 <hr>
						<div style="position:relative;">	<a href="payments.php"><input type="button" class="btn_1 medium" value="Try Again"></a></div>			
                       
					<!--<p>You'll receive a confirmation email at mail@example.com</p>-->
					</div>
				</div>
    
	
      <?php
	
	
}else{
  
	
	
	?>
      <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
								<g fill="none" stroke="#8EC343" stroke-width="2">
									<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
									<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
								</g>
							</svg>
						</div>
                                  
                        
              <h3 style="color:#153f56"><img src="img/creditsicon.png" alt="lpo"></h3>
                                 
					<h2>Your CREDITS CARD Payment Request has been submitted successfully and your requests are in Progress status.</h2>	<hr>
					<a href="payments.php?set=mypayments"><input type="button" class="btn_1 medium" value="View Status"></a>
					</div>
				</div>
    
    <?php
	}
 
} 
   
   
   
   
  if (isset($_POST['payment_type']) && isset($_POST['payment_ref']) && isset($_POST['amount_paypal'])) {
$amount = $_POST['amount_paypal'];
$desc = "PSMT CREDITS PURCHASE";
$type = $_POST['payment_type'];
$payment_ref = $_POST['payment_ref'];
$client_login_id = $_SESSION['MM_client_company_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['account_email'];
$phonenumber = $_POST['account_mobile'];
$filenameuploadedconsentform="";
 $source_ref ="";
if($type=='CREDITS')
{
$desc = "PSMT CREDITS PAYMENT";
}
if($type=='LPO')
{
 $payment_account = $_POST['payment_account'];
 
 $source_ref = $_POST['payment_account'];
 
 $STAFF_ID = $_SESSION['MM_Username'];  
  date_default_timezone_set('Africa/Nairobi');
$date_insert = date('Y-m-d h:i:s');

$uploadedby = mysqli_escape_string($_SESSION['MM_full_names']);

   $MM_client_id = $_SESSION['MM_client_id']; 
    $date_insert2 = date('dmYhis');
 $BLOCKCHAIN = "TRACK".$date_insert2."".$STAFF_ID."".$_POST['payment_ref'];
 
 
 $togetmoduledetails = $_FILES['payment_file']['name']; 
		$tmpFilePath = $_FILES['payment_file']['tmp_name']; 
			
			 $consentform = strtolower(end(explode('.', $togetmoduledetails)));
 $aconsentform = $STAFF_ID."_".$date_insert2;
 	  "Upload: ".$aconsentform."_". $togetmoduledetails;
	  $rawnameconsentform = $togetmoduledetails;
 $fileconsentform="payment_files/".$aconsentform."_". $togetmoduledetails;
	  move_uploaded_file($tmpFilePath,

      "payment_files/".$aconsentform."_". $togetmoduledetails);  
	  
$filenameuploadedconsentform = $aconsentform."_".$togetmoduledetails;	
 
}
else
{
 $payment_account = $_POST['account_mobile'];
}
$plan_currency=$_POST['plan_currency'];

$address_postal_code = $_POST['address_postal_code'];
$address_postal = $_POST['address_postal'];
$address_city = $_POST['address_city'];
$address_country = $_POST['address_country'];



if($_POST['credits'] == 'purchase')
{
	


$query_getcreditcurrency2 = sprintf("SELECT pel_credits.credit_cost, pel_credits.credit_volume, pel_currency.currency_name, pel_currency.currency_code, pel_currency.currency_id FROM pel_credits Inner Join pel_currency ON pel_credits.credit_currency = pel_currency.currency_id WHERE  pel_currency.currency_code ='$plan_currency'");
$getcreditcurrency2 = mysqli_query($connect,$query_getcreditcurrency2) or die(mysqli_error());
$row_getcreditcurrency2 = mysqli_fetch_assoc($getcreditcurrency2);
$totalRows_getcreditcurrency2 = mysqli_num_rows($getcreditcurrency2);

$cost_convertor2 = number_format($row_getcreditcurrency2['credit_cost'],2);
$plan_volume = ($amount/$cost_convertor2);
	$payment_plan = "OPEN PURCHASE";
}
else
{ 
     $plan_volume = $_POST['credits'];
	$payment_plan = "PAYMENT_PLAN";
	
}

$insertSQL = sprintf("INSERT INTO pel_payments (payment_plan, payment_ref, plan_id, pay_source, payment_date, client_id, module_id, currency, client_name, plan_amount, amount, payment_account,plan_volume, address_postal_code,address_postal,address_city,address_country, payment_file, source_ref, client_login_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($payment_plan, "text"),
					   GetSQLValueString($_POST['payment_ref'], "text"),
					   GetSQLValueString($payment_plan, "text"),
					  // GetSQLValueString(isset($_POST['pay_source'] ? "true" : "", "defined","'MPESA'","'N'"),
					   GetSQLValueString($type, "text"),
					   GetSQLValueString(date('d-m-Y H:m:s'), "text"),
					   GetSQLValueString($_SESSION['MM_client_id'], "text"),
					   GetSQLValueString("PSMT", "text"),
					   GetSQLValueString($plan_currency, "text"),
					   GetSQLValueString($_SESSION['MM_full_names'], "text"),
					   GetSQLValueString("0", "text"),
					   GetSQLValueString(number_format($amount, 2), "text"),
					   GetSQLValueString($payment_account, "text"),
					   GetSQLValueString($plan_volume, "text"),
					   GetSQLValueString($address_postal_code, "text"),
					   GetSQLValueString($address_postal, "text"),
					   GetSQLValueString($address_city, "text"),
					   GetSQLValueString($address_country, "text"),
					   GetSQLValueString($filenameuploadedconsentform, "text"),
					   GetSQLValueString($source_ref, "text"),
					   GetSQLValueString($client_login_id, "text"));

   
  $Result1 = mysqli_query($connect,$insertSQL) or die(mysqli_error());
  
  
$query_getpayid = "SELECT * FROM pel_payments where payment_ref='$payment_ref'";
$getpayid = mysqli_query($connect,$query_getpayid) or die(mysqli_error());
$row_getpayid = mysqli_fetch_assoc($getpayid);
$totalRows_getpayid = mysqli_num_rows($getpayid);
/*echo $_POST['amount'];
echo "<br/>".$row_getplans3['plan_cost'];*/
$payment_id = $row_getpayid['payment_id'];
$payment_ref2 = $row_getpayid['payment_ref'];

if($type=='CREDITS')
{
  
  if (mysqli_error()) {
   // echo 'Error:' . curl_error($ch);
	?>
    
    <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Your CREDITS payment request has failed</h2>
                 <hr>
						<div style="position:relative;">	<a href="payments.php"><input type="button" class="btn_1 medium" value="Try Again"></a></div>			
                       
					<!--<p>You'll receive a confirmation email at mail@example.com</p>-->
					</div>
				</div>
    
	
      <?php
	
	
}else{
  
	
	$clientid = $_SESSION['MM_client_id'];
	$clientcredits = $_SESSION['MM_client_credits']-$plan_volume;
	
		$client_login_id = $_SESSION['MM_client_login_id'];
		function GeraHash3($qtd){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
$QuantidadeCaracteres = strlen($Caracteres);
$QuantidadeCaracteres--;

$Hash=NULL;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }

return $Hash;
}
$datenow = date('dmyhis');
$payment_invoice_ref =  "PEL-".$client_login_id."-INV-".GeraHash3(4)."-".$datenow;
	

	$sql_insert="UPDATE pel_psmt_request SET status='44', request_payment_ref='$payment_ref' WHERE status='77' and client_id='$clientid'";
			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());
				
    $result = mysqli_query($connect,$sql_insert) or die('Query failed: ' . mysqli_error());		
	
	$sql_insert2="UPDATE pel_client SET client_credits='$clientcredits' WHERE client_id='$clientid'";
			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());
				
    $result2 = mysqli_query($connect,$sql_insert2) or die('Query failed: ' . mysqli_error());	
	
	$sql_insert3="UPDATE pel_payments SET status='11', invoice_number = '$payment_invoice_ref' WHERE payment_ref='$payment_ref2'";
			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());
				
    $result3 = mysqli_query($connect,$sql_insert3) or die('Query failed: ' . mysqli_error());	
	
	?>
      <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
								<g fill="none" stroke="#8EC343" stroke-width="2">
									<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
									<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
								</g>
							</svg>
						</div>
                                  
                        
              <h3 style="color:#153f56"><img src="img/creditsicon.png" alt="lpo"></h3>
                                 
					<h2>Your CREDITS upload Payment Request has been submitted successfully and your requests are in Progress status.</h2>	<hr>
					<a href="payments.php?set=mypayments"><input type="button" class="btn_1 medium" value="View Status"></a>
					</div>
				</div>
    
    <?php
	}
  }

if($type=='MPESA')
{
  
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://46.101.16.235:8383/v1/mpesa_request");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
/*curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"amount\":100,\"contact\":\"254723594312\",\"reference\":\"EDCheckAfrica\",\"paymentid\":\"Pay89302\",\"paymentref\":\"MASUU823893JKW\"}");
*/
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"amount\":$amount,\"contact\":\"$payment_account\",\"reference\":\"EDCheckAfrica\",\"paymentid\":\"$payment_id\",\"paymentref\":\"$payment_ref2\"}");
/*echo "{\"amount\":".$amount.",\"contact\":".$payment_account.",\"reference\":\"EDCheckAfrica\",\"paymentid\":".$payment_id.",\"paymentref\":".$payment_ref2."}";
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"amount\":".$amount.",\"contact\":".$payment_account.",\"reference\":\"EDCheckAfrica\",\"paymentid\":".$payment_id.",\"paymentref\":".$payment_ref2."}");*/
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Content-Type: application/json;charset=UTF-8";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
   // echo 'Error:' . curl_error($ch);
	?>
    
         <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Your MPESA payment request has failed</h2>
                 <hr>
						<div style="position:relative;">	<a href="payments.php"><input type="button" class="btn_1 medium" value="Try Again"></a></div>			
                       
					<!--<p>You'll receive a confirmation email at mail@example.com</p>-->
					</div>
				</div>
      <?php
	
	
}else{
   "<br/><br/>".$result."<br/><br/>";
    $array = json_decode($result, true);
    $status =$array['statuscode'];
   /* echo "<br/>$status<br/>";*/
	
	if ($status == '0')
	{
	
	$clientid = $_SESSION['MM_client_id'];
	$sql_insert="UPDATE pel_psmt_request SET request_payment_ref='$payment_ref2' WHERE status='77' and client_id='$clientid'";
			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());
				
    $result = mysqli_query($connect,$sql_insert) or die('Query failed: ' . mysqli_error());		
	
	
	
	?>
     <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
								<g fill="none" stroke="#8EC343" stroke-width="2">
									<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
									<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
								</g>
							</svg>
						</div>
                                  
                        
              <h3 style="color:#153f56"><img src="img/mpesapayment.png" alt="mpesa"> </h3>
                                 
					<h2>Your MPESA Payment Request has been submitted successfully go and enter you Mpesa PIN to complete your payment</h2>	<hr>
					<a href="payments.php?set=mypayments"><input type="button" class="btn_1 medium" value="View Status"></a>
					</div>
				</div>
    
    <?php
	}
	else
	{
	?>
        <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Your MPESA payment request has failed</h2>
                 <hr>
						<div style="position:relative;">	<a href="payments.php"><input type="button" class="btn_1 medium" value="Try Again"></a></div>			
                       
					<!--<p>You'll receive a confirmation email at mail@example.com</p>-->
					</div>
				</div>
    <?php
	}
}
curl_close ($ch);

}

 if($type=='LPO')
 {
 
 
if (mysqli_error()) {
   // echo 'Error:' . curl_error($ch);
	?>
    
    <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Your LPO upload payment request has failed</h2>
                 <hr>
						<div style="position:relative;">	<a href="payments.php"><input type="button" class="btn_1 medium" value="Try Again"></a></div>			
                       
					<!--<p>You'll receive a confirmation email at mail@example.com</p>-->
					</div>
				</div>
    
	
      <?php
	
	
}else{
  
	
	$clientid = $_SESSION['MM_client_id'];
	$sql_insert="UPDATE pel_psmt_request SET request_payment_ref='$payment_ref' WHERE status='77' and client_id='$clientid'";
//			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());
//				
    $result = mysqli_query($connect,$sql_insert) or die('Query failed: ' . mysqli_error());		
	
	
	
	?>
      <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
								<g fill="none" stroke="#8EC343" stroke-width="2">
									<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
									<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
								</g>
							</svg>
						</div>
                                  
                        
              <h3 style="color:#153f56"><img src="img/lpoicon.png" alt="lpo"></h3>
                                 
					<h2>Your LPO upload Payment Request has been submitted successfully go kindly await approval of your LPO payment.</h2>	<hr>
					<a href="payments.php?set=mypayments"><input type="button" class="btn_1 medium" value="View Status"></a>
					</div>
				</div>
    
    <?php
	}
 
 } 
   
   if($type=='CARD')
   {
   
include_once('OAuth.php');

//pesapal params
$token = $params = NULL;

/*
PesaPal Sandbox is at https://demo.pesapal.com. Use this to test your developement and 
when you are ready to go live change to https://www.pesapal.com.
*/
//$consumer_key = 'heIrf24S5IW7iUSIzSBPXeJT1u3WJOQN';
$consumer_key = 'u0NYknF2bc25+lYTv0Lcrh37qCiU7G69';
//Register a merchant account on
                   //demo.pesapal.com and use the merchant key for testing.
                   //When you are ready to go live make sure you change the key to the live account
                   //registered on www.pesapal.com!
//$consumer_secret = 'qcWEvTGfyOfwJqwUTuU3yNOi95Y=';
$consumer_secret = 'Q3tDM67OXSPadX5OwVfRECS/dWQ=';
// Use the secret from your test
                   //account on demo.pesapal.com. When you are ready to go live make sure you 
                   //change the secret to the live account registered on www.pesapal.com!
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
//$iframelink = 'https://demo.pesapal.com/api/PostPesapalDirectOrderV4';//change to      
$iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4'; //when you are ready to go live!

$callback_url = 'https://psmt.pidva.africa/processor.php';
$type_pesapal = 'MERCHANT';
$post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchemainstance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"".$amount."\" Description=\"".$desc."\" Type=\"".$type_pesapal."\" Reference=\"".$payment_ref2."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
$post_xml = htmlentities($post_xml);

$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

//post transaction to pesapal
$iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
$iframe_src->set_parameter("oauth_callback", $callback_url);
$iframe_src->set_parameter("pesapal_request_data", $post_xml);
$iframe_src->sign_request($signature_method, $consumer, $token);

//display pesapal - iframe and pass iframe_src
?>
<iframe src="<?php echo $iframe_src;?>" style='height: 100%; width: 100%;'  scrolling="yes" frameBorder="0">
	<p>Browser unable to load iFrame</p>
</iframe>
<?php
   
   
   }
   
   }
  
   ?>	
   
   <?php
   
   if (isset($_GET['pesapal_transaction_tracking_id']) && isset($_GET['pesapal_merchant_reference']) ) {
 $source_ref = $_GET['pesapal_transaction_tracking_id'];
 $payment_ref = $_GET['pesapal_merchant_reference'];

 

$query_getmypayments2 = "SELECT * FROM pel_payments WHERE payment_ref = '$payment_ref'";
$getmypayments2 = mysqli_query($connect,$query_getmypayments2) or die(mysqli_error());
$row_getmypayments2 = mysqli_fetch_assoc($getmypayments2);
$totalRows_getmypayments2 = mysqli_num_rows($getmypayments2);

	$client_login_id = $_SESSION['MM_client_login_id'];
		function GeraHash3($qtd){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
$QuantidadeCaracteres = strlen($Caracteres);
$QuantidadeCaracteres--;

$Hash=NULL;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }

return $Hash;
}
$datenow = date('dmyhis');
$payment_invoice_ref =  "PEL-".$client_login_id."-INV-".GeraHash3(4)."-".$datenow;
  
$updateSQL = sprintf("UPDATE pel_payments SET source_ref=%s, status='11',payment_status = '11', invoice_number=%s WHERE payment_ref=%s",
                       GetSQLValueString($source_ref, "text"),
                        GetSQLValueString($payment_invoice_ref, "text"),
						GetSQLValueString($payment_ref, "text"));

  
  $Result1 = mysqli_query($connect,$updateSQL) or die(mysqli_error());
  
/*
$currentcredits = $row_getmypayments2['plan_volume'] + $clientcredits;
    $amountcredits = number_format($currentcredits, 2);
	
  $updateSQL2 = sprintf("UPDATE pel_client SET client_credits=%s WHERE client_id=%s",
                       GetSQLValueString($amountcredits, "text"),
					   GetSQLValueString($colname_getcredit, "int"));

  
  $Result2 = mysqli_query($connect,$updateSQL2) or die(mysqli_error());*/
  
$sql_insert="UPDATE pel_psmt_request SET status='44', request_payment_ref='$payment_ref' WHERE status='77' and client_id='$clientid'";
			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());
				
    $result = mysqli_query($connect,$sql_insert) or die('Query failed: ' . mysqli_error());	
	
	?>
    
    <div class="col-lg-12">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
								<g fill="none" stroke="#8EC343" stroke-width="2">
									<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
									<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
								</g>
							</svg>
						</div>
                                  
                        
              <h3 style="color:#153f56"><img src="img/lpoicon.png" alt="lpo"></h3>
                                 
					<h2>Your CARD Payment Request has been submitted successfully.</h2>	<hr>
					<a href="payments.php?set=mypayments"><input type="button" class="btn_1 medium" value="View Status"></a>
					</div>
				</div>
    
    
    <?php
  
  /*  $updateGoTo = "mypayments.php";
 if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));*/
}
   
   
   
   
   
   ?>
   
   
   
   
   </div>
				</div>
		</div>
			<!-- /row -->
		</div>
		<!-- /container -->
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

	<!-- COMMON SCRIPTS -->
	<script src="js/jquery-2.2.4.min.js"></script>
	<script src="js/common_scripts.min.js"></script>
	<script src="js/functions.js"></script>
     
</body>
</html>