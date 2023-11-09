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


$query_getplans = "SELECT * FROM pel_plans where plan_status='11' and module_id='EDUCATION' ORDER BY plan_name DESC";
$getplans = mysqli_query($connect,$query_getplans) or die(mysqli_error());
$row_getplans = mysqli_fetch_assoc($getplans);
$totalRows_getplans = mysqli_num_rows($getplans);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_getcredit = "-1";
if (isset($_SESSION['MM_client_id'])) {
  $colname_getcredit = $_SESSION['MM_client_id'];
}

$query_getcredit = sprintf("SELECT client_credits FROM pel_client WHERE client_id = %s", GetSQLValueString($colname_getcredit, "int"));
$getcredit = mysqli_query($connect,$query_getcredit) or die(mysqli_error());
$row_getcredit = mysqli_fetch_assoc($getcredit);
$totalRows_getcredit = mysqli_num_rows($getcredit);

?>
            
            <?php
				
				
include_once('OAuth.php');

//pesapal params
$token = $params = NULL;

/*
PesaPal Sandbox is at https://demo.pesapal.com. Use this to test your developement and 
when you are ready to go live change to https://www.pesapal.com.
*/
$consumer_key = 'heIrf24S5IW7iUSIzSBPXeJT1u3WJOQN';
//$consumer_key = 'u0NYknF2bc25+lYTv0Lcrh37qCiU7G69';
//Register a merchant account on
                   //demo.pesapal.com and use the merchant key for testing.
                   //When you are ready to go live make sure you change the key to the live account
                   //registered on www.pesapal.com!
$consumer_secret = 'qcWEvTGfyOfwJqwUTuU3yNOi95Y=';
//$consumer_secret = 'Q3tDM67OXSPadX5OwVfRECS/dWQ=';
// Use the secret from your test
                   //account on demo.pesapal.com. When you are ready to go live make sure you 
                   //change the secret to the live account registered on www.pesapal.com!
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
//$iframelink = 'https://demo.pesapal.com/api/PostPesapalDirectOrderV4';//change to      
$iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4'; //when you are ready to go live!

//get form details

$amount = $_POST['amount_paypal'];
$amount = number_format($amount, 2);//format amount to 2 decimal places
$desc = "PSMT CREDITS PURCHASE";
$type = "MERCHANT"; //default value = MERCHANT
$reference = $_POST['payment_ref'];

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['account_email'];
$phonenumber = $_POST['account_mobile'];
$payment_account = $_POST['account_mobile'];
$plan_currency=$_POST['plan_currency'];


$address_postal_code = $_POST['address_postal_code'];
$address_postal = $_POST['address_postal'];
$address_city = $_POST['address_city'];
$address_country = $_POST['address_country'];

if($_POST['plan_currency']=='KES')
{
$plan_volume = ($_POST['amount_paypal']);
}

if($_POST['plan_currency']=='USD')
{
$plan_volume = ($_POST['amount_paypal']*100);
}

$insertSQL = sprintf("INSERT INTO pel_payments (payment_plan, payment_ref, plan_id, pay_source, payment_date, client_id, module_id, currency, client_name, plan_amount, amount, payment_account,plan_volume, address_postal_code,address_postal,address_city,address_country) VALUES (%s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString("PAYMENT_PLAN", "text"),
					   GetSQLValueString($_POST['payment_ref'], "text"),
					   GetSQLValueString("PAYMENT_PLAN", "text"),
					  // GetSQLValueString(isset($_POST['pay_source'] ? "true" : "", "defined","'MPESA'","'N'"),
					   GetSQLValueString("CARD", "text"),
					   GetSQLValueString(date('d-m-Y H:m:s'), "text"),
					   GetSQLValueString($_SESSION['MM_client_id'], "text"),
					   GetSQLValueString("PSMT", "text"),
					   GetSQLValueString($plan_currency, "text"),
					   GetSQLValueString($_SESSION['MM_full_names'], "text"),
					   GetSQLValueString("0", "text"),
					   GetSQLValueString($amount, "text"),
					   GetSQLValueString($payment_account, "text"),
					   GetSQLValueString(number_format($plan_volume, 2), "text"),
					   GetSQLValueString($address_postal_code, "text"),
					   GetSQLValueString($address_postal, "text"),
					   GetSQLValueString($address_city, "text"),
					   GetSQLValueString($address_country, "text"));


   
  $Result1 = mysqli_query($connect,$insertSQL) or die(mysqli_error());


$callback_url = 'https://psmt.pidva.africa/mypayments.php';

$post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchemainstance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"".$amount."\" Description=\"".$desc."\" Type=\"".$type."\" Reference=\"".$reference."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
$post_xml = htmlentities($post_xml);

$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

//post transaction to pesapal
$iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
$iframe_src->set_parameter("oauth_callback", $callback_url);
$iframe_src->set_parameter("pesapal_request_data", $post_xml);
$iframe_src->sign_request($signature_method, $consumer, $token);

//display pesapal - iframe and pass iframe_src
?>
<iframe src="<?php echo $iframe_src;?>" width="100%"   scrolling="no" frameBorder="0">
	<p>Browser unable to load iFrame</p>
</iframe>
            
            
         