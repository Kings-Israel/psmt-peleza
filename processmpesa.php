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
	require_once('Connections/connect.php');		
			$amount = $_POST['amount_paypal'];
$amount = number_format($amount, 2);//format amount to 2 decimal places
$desc = "PSMT CREDITS PURCHASE";
$type = "MPESA"; //default value = MERCHANT
$payment_ref = $_POST['payment_ref'];

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
$plan_volume = $_POST['amount_paypal'];
}

$insertSQL = sprintf("INSERT INTO pel_payments (payment_plan, payment_ref, plan_id, pay_source, payment_date, client_id, module_id, currency, client_name, plan_amount, amount, payment_account,plan_volume, address_postal_code,address_postal,address_city,address_country) VALUES (%s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString("PAYMENT_PLAN", "text"),
					   GetSQLValueString($_POST['payment_ref'], "text"),
					   GetSQLValueString("PAYMENT_PLAN", "text"),
					  // GetSQLValueString(isset($_POST['pay_source'] ? "true" : "", "defined","'MPESA'","'N'"),
					   GetSQLValueString("MPESA", "text"),
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
  
  
$query_getpayid = "SELECT * FROM pel_payments where payment_ref='$payment_ref'";
$getpayid = mysqli_query($connect,$query_getpayid) or die(mysqli_error());
$row_getpayid = mysqli_fetch_assoc($getpayid);
$totalRows_getpayid = mysqli_num_rows($getpayid);
/*echo $_POST['amount'];
echo "<br/>".$row_getplans3['plan_cost'];*/
$payment_id = $row_getpayid['payment_id'];
$payment_ref2 = $row_getpayid['payment_ref'];
  
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
					<h2 style="color:#993300">Your MPESA payment request has failed</h2>	<hr>
					<a href="../payment"><input type="button" class="btn_1 medium" value="Try Again"></a>
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
	$sql_insert="UPDATE pel_psmt_request SET status='44', request_payment_ref='$payment_ref2' WHERE status='77' and client_id='$clientid'";
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
					<h2>Your MPESA Payment Request has been submitted successfully go and enter you Mpesa PIN to complete your payment</h2>	<hr>
					<a href="../mypayments"><input type="button" class="btn_1 medium" value="Check Status"></a>
					</div>
				</div>
    
    <?php
	}
	else
	{
	?>
       <div class="col-lg-12">
						<div id="confirm">
						<h2 style="color:#993300">Your MPESA payment request has failed</h2>	<hr>
					<a href="../payment"><input type="button" class="btn_1 medium" value="Try Again"></a>
					</div>
				</div>
    
    <?php
	}
}
curl_close ($ch);

			
			
			?>
            
            
         