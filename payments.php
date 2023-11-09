<?php require_once('Connections/process.php'); ?><?php
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

$query_getcredits = sprintf("SELECT pel_client.client_credits FROM pel_client WHERE client_id='$client_id_get_psmt_requests'");
$getcredits = mysqli_query($connect,$query_getcredits) or die(mysqli_error());
$row_getcredits = mysqli_fetch_assoc($getcredits);
if (isset($_SESSION['MM_client_credits'])) {
  $_SESSION['MM_client_credits'] = $row_getcredits['client_credits'];
}

$client_login_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_login_id'])) {
  $client_login_id_get_psmt_requests = $_SESSION['MM_client_login_id'];
}

$set = "-1";
if (isset($_GET['set'])) {
$set = $_GET['set'];
}


?>
    <!doctype html>
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

        <link href="assets/css/main.css" rel="stylesheet">
        <link href="assets/css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>

        <!--Peleza-->
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
    <div class="app-container body-tabs-shadow fixed-sidebar">

        <?php include 'partials/header.php'; ?>

        <div class="app-main">

            <?php include 'partials/sidebar.php'; ?>

            <div class="app-main__outer">

                <?php include 'partials/top-header.php'; ?>

                <!--Body-->
                <div class="filters_listing">
                    <div class="container">
                        <ul class="clearfix">

                            <li>

                                <h5>Sort by Status</h5>

                                <div class="switch-field">

                                    <label for="" style="background-color:#929e36">PAYMENTS:</label>
                                    <a href="payments.php?set=checkout"><?php

                                        if($set=='-1' || $set=='checkout')
                                        {
                                            ?>
                                            <input type="radio" id="all" name="type_patient" value="all" checked>  <?php
                                        }

                                        ?><label for="New Request">CHECKOUT</label></a>


                                    <a href="payments.php?set=mypayments"><?php

                                        if($set=='mypayments')
                                        {
                                            ?>
                                            <input type="radio" id="all" name="type_patient" value="all" checked>  <?php
                                        }

                                        ?><label for="New Request">MY PAYMENTS</label></a>
                                    <a href="buycredits.php?set=buycredits">          <?php

                                        if($set=='buycredits')
                                        {
                                            ?>
                                            <input type="radio" id="all" name="type_patient" value="all" checked>  <?php
                                        }

                                        ?><label for="New Request">BUY CREDITS</label></a>


                                    <a href="cart/cart.php?status_search=77"><label for="New Request">PAYMENT CART</label></a>
                                </div>
                            </li>
                            <li>

                                <h5>Default Currency</h5>
                                <?php

                                $currency_search = $_SESSION['MM_client_currency'];
                                if(isset($_POST['currency_search']))
                                {
                                    $currency_search = $_POST['currency_search'];

                                }


                                $query_getcreditcurrency = sprintf("SELECT pel_credits.credit_cost, pel_credits.credit_volume, pel_currency.currency_name, pel_currency.currency_code, pel_currency.currency_id FROM pel_credits Inner Join pel_currency ON pel_credits.credit_currency = pel_currency.currency_id ORDER BY pel_currency.currency_name ASC");
                                $getcreditcurrency = mysqli_query($connect,$query_getcreditcurrency) or die(mysqli_error());
                                $row_getcreditcurrency = mysqli_fetch_assoc($getcreditcurrency);
                                $totalRows_getcreditcurrency = mysqli_num_rows($getcreditcurrency);
                                ?>
                                <form name="formc" action="<?php echo $editFormAction; ?>" method="POST" >
                                    <select name="currency_search" class="selectbox" onChange="this.form.submit()">

                                        <?php
                                        do
                                        {

                                            if($row_getcreditcurrency['currency_code'] == $currency_search)
                                            {
                                                ?>
                                                <option selected="selected" value="<?php echo $row_getcreditcurrency['currency_code']; ?>"><?php echo $row_getcreditcurrency['currency_name']; ?></option>
                                                <?php
                                            }
                                            else {
                                                ?>
                                                <option value="<?php echo $row_getcreditcurrency['currency_code']; ?>"><?php echo $row_getcreditcurrency['currency_name']; ?></option>
                                                <?php
                                            }
                                        } while ($row_getcreditcurrency = mysqli_fetch_assoc($getcreditcurrency));
                                        ?>
                                    </select>
                                    <input type="hidden" name="MM_insert" value="formd">
                                </form>
                            </li>
                            <!--<li>
					<h6>Sort By Package</h6>
                     <form name="formd" action="<?php echo $editFormAction; ?>" method="POST" >
					<select name="package_search" class="selectbox" style="width: 500px;" onChange="this.form.submit()">
                    <?php


                            $query_getpackagenames = sprintf("SELECT package_id, package_name, client_id FROM pel_client_package where client_id = %s ", GetSQLValueString($client_id_get_psmt_requests, "int"));
                            $getpackagenames = mysqli_query($connect,$query_getpackagenames) or die(mysqli_error());
                            $row_getpackagenames = mysqli_fetch_assoc($getpackagenames);
                            $totalRows_getpackagenames = mysqli_num_rows($getpackagenames);

                            if($totalRows_getpackagenames > '0')
                            {

                                ?>

				<?php

                                do {
                                    ?>	<option value="<?php echo $row_getpackagenames['package_id']; ?>"><?php echo $row_getpackagenames['package_name']; ?></option>
					<?php
                                } while ($row_getpackagenames = mysqli_fetch_assoc($getpackagenames)); ?>



   <?php
                            }

                            ?> <option value="INDIVIDUAL">INDIVIDUAL</option>
					<option value="COMPANY">COMPANY</option></select>
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
                        <?php

                        $query_getcreditcurrency2 = sprintf("SELECT pel_credits.credit_cost, pel_credits.credit_volume, pel_currency.currency_name, pel_currency.currency_code, pel_currency.currency_id FROM pel_credits Inner Join pel_currency ON pel_credits.credit_currency = pel_currency.currency_id WHERE  pel_currency.currency_code ='$currency_search'");
                        $getcreditcurrency2 = mysqli_query($connect,$query_getcreditcurrency2) or die(mysqli_error());
                        $row_getcreditcurrency2 = mysqli_fetch_assoc($getcreditcurrency2);
                        $totalRows_getcreditcurrency2 = mysqli_num_rows($getcreditcurrency2);

                        $cost_convertor2 = $row_getcreditcurrency2['credit_cost'];


                        $query_getcart = "SELECT
COUNT(request_id) AS COUNT_REQUEST,
pel_psmt_request.request_plan,
SUM(pel_psmt_request.package_cost * '$cost_convertor2'/pel_credits.credit_cost) AS PACKAGE_COST,
SUM(pel_psmt_request.package_cost/pel_credits.credit_cost) AS PACKAGE_VOLUME
FROM
pel_psmt_request
Inner Join pel_currency ON pel_currency.currency_code = pel_psmt_request.package_cost_currency
Inner Join pel_credits ON pel_credits.credit_currency = pel_currency.currency_id
WHERE pel_psmt_request.status='77' AND pel_psmt_request.client_id = '$client_id_get_psmt_requests'
GROUP BY pel_psmt_request.request_plan
ORDER BY pel_psmt_request.request_plan ASC";
                        $getcart = mysqli_query($connect,$query_getcart) or die(mysqli_error());
                        $row_getcart = mysqli_fetch_assoc($getcart);
                        $totalRows_getcart = mysqli_num_rows($getcart);

                        if($totalRows_getcart>0  && ($set=='-1' || $set=='checkout'))
                        {
                            $listcart="";
                            $total_price=0;
                            $total_volume=0;
                            do {

                                $listcart2= "<li>".$row_getcart['request_plan']."- (".$row_getcart['COUNT_REQUEST'].") <strong class='float-right'>".$currency_search." ".round($row_getcart['PACKAGE_COST'], 2) ."</strong></li>";
                                $listcart.= $listcart2;
                                $total_price= $total_price + $row_getcart['PACKAGE_COST'];
                                $total_volume= $total_volume + $row_getcart['PACKAGE_VOLUME'];

                            } while ($row_getcart = mysqli_fetch_assoc($getcart)); ?>
                            <div class="col-xl-8 col-lg-8">
                                <div class="box_general_3 cart">
                                    <?php
                                    if(round($total_volume,2) > $_SESSION['MM_client_credits'])
                                    {

                                        ?>

                                        <div class="message">
                                            <h4>Kindly Purchase <strong><?php echo round($total_volume-$_SESSION['MM_client_credits'],2);?></strong> CREDITS worth <strong><?php echo $currency_search." ".round(($total_volume-$_SESSION['MM_client_credits'])*$cost_convertor2,2);?></strong> to fully pay.</h4>
                                        </div>


                                        <?php
                                    }
                                    if(round($total_volume,2) <= $_SESSION['MM_client_credits'])
                                    {

                                        ?>
                                        <div class="message">
                                            <h4>You have Enough <strong><?php echo $_SESSION['MM_client_credits'];?></strong> CREDITS worth <strong><?php echo $currency_search." ".round(($_SESSION['MM_client_credits'])*$cost_convertor2,2);?></strong> to fully pay <strong><?php echo $currency_search." ".round(($total_volume)*$cost_convertor2,2);?></strong> .</h4>
                                        </div>

                                        <?php
                                    }
                                    ?>

                                    <?php

                                    function GeraHash2($qtd){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
                                        $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
                                        $QuantidadeCaracteres = strlen($Caracteres);

                                        $Hash=NULL;
                                        for($x=1;$x<=$qtd;$x++){
                                            $Posicao = rand(0,$QuantidadeCaracteres);
                                            $Hash .= substr($Caracteres,$Posicao,1);
                                        }

                                        return $Hash;
                                    }


                                    $ref1 = "".$_SESSION['MM_client_id']."".GeraHash2(4)."".date('dmyhms');

                                    if(round($total_volume,2) > $_SESSION['MM_client_credits'])
                                    {

                                        ?>
                                        <div class="indent_title_in">
                                            <i class="pe-7s-wallet"></i>
                                            <h3 style="color:#153f56">Select Payment Method</h3>
                                            <p> <strong>Click to select and fill in the details</strong></p>
                                        </div>

                                        <div class="tabs_styled_2">
                                            <ul class="nav nav-tabs" role="tablist">

                                                <li class="nav-item">
                                                    <a class="nav-link active" id="lpo-tab" data-toggle="tab" href="#lpo" role="tab" aria-controls="lpo"><img src="img/lpoicon.png" alt="lpo"></a>
                                                </li>
                                                <?php

                                                if($currency_search == 'KES')
                                                {
                                                    ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="mpesa-tab" data-toggle="tab" href="#mpesa" role="tab" aria-controls="mpesa"><img src="img/mpesapayment.png" alt="mpesa"></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="card-tab" data-toggle="tab" href="#card" role="tab" aria-controls="card" aria-expanded="true"><img src="img/cardicon.png" alt="cardpayments"></a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link" id="paypal-tab" data-toggle="tab" href="#paypal" role="tab" aria-controls="paypal"><img src="img/paypalicon.png" alt="paypal"></a>
                                                </li>
                                            </ul>
                                            <!--/nav-tabs -->

                                            <div class="tab-content">
                                                <div class="tab-pane fade  show active" id="lpo" role="tabpanel" aria-labelledby="lpo-tab">
                                                    <form name="processlpo" action="processor.php" method="POST" ENCTYPE='multipart/form-data'>
                                                        <?php

                                                        $payreflpo = "PSMT-LPO-".$ref1;

                                                        ?>
                                                        <input type="hidden" id="payment_ref" name="payment_ref" value="<?php echo $payreflpo; ?>" readonly/>

                                                        <input type="hidden" id="payment_type" name="payment_type" value="LPO" readonly/>

                                                        <input type="hidden" class="form-control" id="search-text-inputmobile" name="first_name" value="<?php echo $_SESSION['MM_first_name'];?>">

                                                        <input type="hidden" id="credits" name="credits" value="<?php echo round($total_volume-$_SESSION['MM_client_credits'],2);?>" readonly/>
                                                        <input type="hidden" class="form-control" id="search-text-inputmobile" name="last_name" value="<?php echo $_SESSION['MM_last_name'];?>">

                                                        <div class="form_title">
                                                            <h3><strong>1</strong>Payment Reference Number</h3>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <h5><strong>Payment Ref Number:</strong> <font color="#006600"><?php echo $payreflpo;?></font></h5>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                        <div class="form_title">
                                                            <h3><strong>2</strong>Billing Address</h3>
                                                            <p>Enter new Address or Leave to Use registered Address.</p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <label>Country</label>
                                                                    <div class="form-group">
                                                                        <?php


                                                                        $query_getcitizenship = "SELECT * FROM pel_countries ORDER BY country_nationality ASC";
                                                                        $getcitizenship = mysqli_query($connect,$query_getcitizenship) or die(mysqli_error());
                                                                        $row_getcitizenship = mysqli_fetch_assoc($getcitizenship);
                                                                        $totalRows_getcitizenship = mysqli_num_rows($getcitizenship);
                                                                        ?>
                                                                        <select class="custom-select form-control required" id="address_country" name="address_country"  required>
                                                                            <option value="<?php echo $_SESSION['MM_client_country'];?>"><?php echo $_SESSION['MM_client_country'];?></option>
                                                                            <?php do { ?>
                                                                                <option value="<?php echo $row_getcitizenship['country_name']; ?>"><?php echo $row_getcitizenship['country_name']; ?></option>
                                                                            <?php } while ($row_getcitizenship = mysqli_fetch_assoc($getcitizenship)); ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>City</label>
                                                                        <input type="text" id="address_city" name="address_city" class="form-control" placeholder="City" value="<?php echo $_SESSION['MM_client_city'];?>" required>
                                                                    </div>	</div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Postal Address</label>
                                                                        <input type="text" id="address_postal" name="address_postal" class="form-control" placeholder="Postal Address" value="<?php echo $_SESSION['MM_client_postal_address'];?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Postal Code</label>
                                                                        <input type="text" id="address_postal_code" name="address_postal_code" class="form-control" placeholder="Postal Code" value="<?php echo $_SESSION['MM_client_postal_code'];?>" required>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <!--End row -->
                                                        </div>
                                                        <hr>
                                                        <!--End step -->
                                                        <div class="form_title">
                                                            <h3><strong>3</strong>Payment Details</h3>
                                                            <p>Enter LPO Number.</p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Enter Amount</label>
                                                                        <input style="background-color:#cdd53a;" type="text" id="search-text-inputamount" name="amount_paypal" class="form-control inputamount" placeholder="Amount to Pay" value="<?php echo round(($total_volume-$_SESSION['MM_client_credits'])*$cost_convertor2,2);?>"  readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Mobile Number:</label>
                                                                        <input type="text" style="background-color:#cdd53a;"  class="form-control inputmobile" id="search-text-inputmobile" name="account_mobile" placeholder="Your Mobile Number" value="<?php echo $_SESSION['MM_client_mobile_number'];?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Email Address</label>
                                                                        <input type="text" style="background-color:#cdd53a;" class="form-control inputemail" id="search-text-inputemail" name="account_email" placeholder="Your Email Address" value="<?php echo $_SESSION['MM_client_email_address'];?>" readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Currency</label>
                                                                        <select name="plan_currency"  style="background-color:#cdd53a;" id="search-text-inputamount"  class="custom-select form-control" required>
                                                                            <option value="<?php echo $currency_search?>"><?php echo $currency_search?></option>

                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Upload LPO pdf File</label>
                                                                        <input accept=".pdf" style="background-color:#cdd53a;" type="file" class="form-control required" id="payment_file" name="payment_file" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>LPO Number:</label>
                                                                        <input type="text" style="background-color:#cdd53a;"  class="form-control inputmobile" id="search-text-inputmobile" name="payment_account" placeholder="LPO Number" required>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <!--End row -->
                                                        <div class="form_title">
                                                            <h3><strong>4</strong>Accept Terms and Condition</h3>
                                                            <p><a href="#">Click to Read Terms</a></p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12">
                                                                    <label class="container_checkbox"><input type="checkbox"  id="policy_terms" name="policy_terms" value="YES" required><p>I accept terms and conditions and general policy.</p>
                                                                        <span class="checkmark"></span>
                                                                    </label>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <input type="submit" class="btn_1 medium" value="Submit">
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="tab-pane fade" id="mpesa" role="tabpanel" aria-labelledby="mpesa-tab">
                                                    <form name="processmpesa" action="processor.php" method="POST">
                                                        <?php

                                                        $payrefmpesa = "PSMT-MPESA-".$ref1;

                                                        ?>
                                                        <input type="hidden" id="payment_type" name="payment_type" value="MPESA" readonly/>
                                                        <input type="hidden" id="payment_ref" name="payment_ref" value="<?php echo $payrefmpesa;

                                                        ?>" readonly/>
                                                        <input type="hidden" id="credits" name="credits" value="<?php echo round($total_volume-$_SESSION['MM_client_credits'],2);?>" readonly/>
                                                        <input type="hidden" class="form-control" id="search-text-inputmobile" name="first_name" value="<?php echo $_SESSION['MM_first_name'];?>">

                                                        <input type="hidden" class="form-control" id="search-text-inputmobile" name="last_name" value="<?php echo $_SESSION['MM_last_name'];?>">

                                                        <div class="form_title">
                                                            <h3><strong>1</strong>Payment Reference Number</h3>

                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <h5><strong>Payment Ref Number:</strong> <font color="#006600"><?php echo $payrefmpesa;?></font></h5>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                        <div class="form_title">
                                                            <h3><strong>2</strong>Billing Address</h3>
                                                            <p>Enter new Address or Leave to Use registered Address.</p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <label>Country</label>
                                                                    <div class="form-group">
                                                                        <?php


                                                                        $query_getcitizenship = "SELECT * FROM pel_countries ORDER BY country_nationality ASC";
                                                                        $getcitizenship = mysqli_query($connect,$query_getcitizenship) or die(mysqli_error());
                                                                        $row_getcitizenship = mysqli_fetch_assoc($getcitizenship);
                                                                        $totalRows_getcitizenship = mysqli_num_rows($getcitizenship);
                                                                        ?>
                                                                        <select class="custom-select form-control required" id="address_country" name="address_country"  required>
                                                                            <option value="<?php echo $_SESSION['MM_client_country'];?>"><?php echo $_SESSION['MM_client_country'];?></option>
                                                                            <?php do { ?>
                                                                                <option value="<?php echo $row_getcitizenship['country_name']; ?>"><?php echo $row_getcitizenship['country_name']; ?></option>
                                                                            <?php } while ($row_getcitizenship = mysqli_fetch_assoc($getcitizenship)); ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>City</label>
                                                                        <input type="text" id="address_city" name="address_city" class="form-control" placeholder="City" value="<?php echo $_SESSION['MM_client_city'];?>" required>
                                                                    </div>	</div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Postal Address</label>
                                                                        <input type="text" id="address_postal" name="address_postal" class="form-control" placeholder="Postal Address" value="<?php echo $_SESSION['MM_client_postal_address'];?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Postal Code</label>
                                                                        <input type="text" id="address_postal_code" name="address_postal_code" class="form-control" placeholder="Postal Code" value="<?php echo $_SESSION['MM_client_postal_code'];?>" required>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <!--End row -->
                                                        </div>
                                                        <hr>
                                                        <!--End step -->
                                                        <div class="form_title">
                                                            <h3><strong>3</strong>Payment Details</h3>
                                                            <p>Enter MPESA number.</p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Enter Amount</label>
                                                                        <input style="background-color:#cdd53a;" type="text" id="search-text-inputamount" name="amount_paypal" class="form-control inputamount" placeholder="Amount to Pay" value="<?php echo round(($total_volume-$_SESSION['MM_client_credits'])*$cost_convertor2);?>"  readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>MPESA Number</label>
                                                                        <input type="text" style="background-color:#cdd53a;"  class="form-control inputmobile" id="search-text-inputmobile" name="account_mobile" placeholder="Your Mobile Number" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Email Address</label>
                                                                        <input type="text" style="background-color:#cdd53a;" class="form-control inputemail" id="search-text-inputemail" name="account_email" placeholder="Your Email Address" value="<?php echo $_SESSION['MM_client_email_address'];?>" readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Currency</label>
                                                                        <select name="plan_currency"  style="background-color:#cdd53a;" id="search-text-inputamount"  class="custom-select form-control" required>
                                                                            <option value="KES">KES</option>
                                                                            <!--         <option value="USD">USD</option>-->
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>   </div>
                                                        <!--End row -->
                                                        <div class="form_title">
                                                            <h3><strong>4</strong>Accept Terms and Condition</h3>
                                                            <p><a href="#">Click to Read Terms</a></p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12">
                                                                    <label class="container_checkbox"><input type="checkbox"  id="policy_terms" name="policy_terms" value="YES" required><p>I accept terms and conditions and general policy.</p>
                                                                        <span class="checkmark"></span>
                                                                    </label>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <input type="submit" class="btn_1 medium" value="Submit">
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>        </div>


                                                <div class="tab-pane fade" id="card" role="tabpanel" aria-labelledby="card-tab">
                                                    <form name="searchstudent" action="processor.php" method="POST" >

                                                        <input type="hidden" id="payment_type" name="payment_type" value="CARD" readonly/>
                                                        <input type="hidden" id="payment_ref" name="payment_ref" value="<?php

                                                        echo $payrefcard = "PSMT-CARD-".$ref1;

                                                        ?>" readonly/>
                                                        <input type="hidden" id="credits" name="credits" value="<?php echo round($total_volume-$_SESSION['MM_client_credits'],2);?>" readonly/>
                                                        <input type="hidden" class="form-control" id="search-text-inputmobile" name="first_name" value="<?php echo $_SESSION['MM_first_name'];?>">

                                                        <input type="hidden" class="form-control" id="search-text-inputmobile" name="last_name" value="<?php echo $_SESSION['MM_last_name'];?>">
                                                        <div class="form_title">
                                                            <h3><strong>1</strong>Payment Reference Number</h3>

                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <h5><strong>Payment Ref Number:</strong> <font color="#006600"><?php echo $payrefcard;?></font></h5>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                        <div class="form_title">
                                                            <h3><strong>2</strong>Billing Address</h3>
                                                            <p>Enter new Address or Leave to Use registered Address.</p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <label>Country</label>
                                                                    <div class="form-group">
                                                                        <?php


                                                                        $query_getcitizenship = "SELECT * FROM pel_countries ORDER BY country_nationality ASC";
                                                                        $getcitizenship = mysqli_query($connect,$query_getcitizenship) or die(mysqli_error());
                                                                        $row_getcitizenship = mysqli_fetch_assoc($getcitizenship);
                                                                        $totalRows_getcitizenship = mysqli_num_rows($getcitizenship);
                                                                        ?>
                                                                        <select class="custom-select form-control required" id="address_country" name="address_country"  required>
                                                                            <option value="<?php echo $_SESSION['MM_client_country'];?>"><?php echo $_SESSION['MM_client_country'];?></option>
                                                                            <?php do { ?>
                                                                                <option value="<?php echo $row_getcitizenship['country_name']; ?>"><?php echo $row_getcitizenship['country_name']; ?></option>
                                                                            <?php } while ($row_getcitizenship = mysqli_fetch_assoc($getcitizenship)); ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>City</label>
                                                                        <input type="text" id="address_city" name="address_city" class="form-control" placeholder="City" value="<?php echo $_SESSION['MM_client_city'];?>" required>
                                                                    </div>	</div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Postal Address</label>
                                                                        <input type="text" id="address_postal" name="address_postal" class="form-control" placeholder="Postal Address" value="<?php echo $_SESSION['MM_client_postal_address'];?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Postal Code</label>
                                                                        <input type="text" id="address_postal_code" name="address_postal_code" class="form-control" placeholder="Postal Code" value="<?php echo $_SESSION['MM_client_postal_code'];?>" required>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <!--End row -->
                                                        </div>
                                                        <hr>
                                                        <!--End step -->
                                                        <div class="form_title">
                                                            <h3><strong>3</strong>Payment Details</h3>
                                                            <p>Enter Contact Details.</p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Enter Amount</label>
                                                                        <input style="background-color:#cdd53a;" type="text" id="search-text-inputamount" name="amount_paypal" class="form-control inputamount" placeholder="Amount to Pay" value="<?php echo round(($total_volume-$_SESSION['MM_client_credits'])*$cost_convertor2,2);?>"  readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Mobile Number</label>
                                                                        <input type="text" style="background-color:#cdd53a;"  class="form-control inputmobile" id="search-text-inputmobile" name="account_mobile" placeholder="Your Mobile Number" value="<?php echo $_SESSION['MM_client_mobile_number'];?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Email Address</label>
                                                                        <input type="text" style="background-color:#cdd53a;" class="form-control inputemail" id="search-text-inputemail" name="account_email" placeholder="Your Email Address" value="<?php echo $_SESSION['MM_client_email_address'];?>" readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Currency</label>
                                                                        <select name="plan_currency"  style="background-color:#cdd53a;" id="search-text-inputamount"  class="custom-select form-control" required>
                                                                            <option value="<?php echo $currency_search?>"><?php echo $currency_search?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>   </div>
                                                        <!--End row -->
                                                        <div class="form_title">
                                                            <h3><strong>4</strong>Accept Terms and Condition</h3>
                                                            <p><a href="#">Click to Read Terms</a></p>
                                                        </div>
                                                        <div class="step">
                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12">
                                                                    <label class="container_checkbox"><input type="checkbox"  id="policy_terms" name="policy_terms" value="YES" required><p>I accept terms and conditions and general policy.</p>
                                                                        <span class="checkmark"></span>
                                                                    </label>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-6">
                                                                    <input type="submit" class="btn_1 medium" value="Submit">
                                                                </div>
                                                                <div class="col-md-6 col-sm-6">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>



                                                </div>
                                                <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
                                                    COMING SOON</div>

                                            </div>
                                        </div>

                                        <hr>
                                        <!--End step -->


                                        <?php
                                    }
                                    if(round($total_volume,2) <= $_SESSION['MM_client_credits'])
                                    {

                                        ?>
                                        <form name="processcredits" action="processor.php" method="POST">
                                            <?php

                                            $payrefcredits = "PSMT-CREDITS-".$ref1;

                                            ?>
                                            <input type="hidden" id="payment_type" name="payment_type" value="CREDITS" readonly/>
                                            <input type="hidden" id="payment_ref" name="payment_ref" value="<?php echo $payrefcredits;

                                            ?>" readonly/>

                                            <input type="hidden" id="credits" name="credits" value="<?php echo round($total_volume);?>" readonly/>

                                            <input type="hidden" class="form-control" id="search-text-inputmobile" name="first_name" value="<?php echo $_SESSION['MM_first_name'];?>">

                                            <input type="hidden" class="form-control" id="search-text-inputmobile" name="last_name" value="<?php echo $_SESSION['MM_last_name'];?>">

                                            <div class="form_title">
                                                <h3><strong>1</strong>Payment Reference Number</h3>

                                            </div>
                                            <div class="step">
                                                <div class="row">
                                                    <h5><strong>Payment Ref Number:</strong> <font color="#006600"><?php echo $payrefcredits;?></font></h5>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form_title">
                                                <h3><strong>2</strong>Billing Address</h3>
                                                <p>Enter new Address or Leave to Use registered Address.</p>
                                            </div>
                                            <div class="step">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <label>Country</label>
                                                        <div class="form-group">
                                                            <?php


                                                            $query_getcitizenship = "SELECT * FROM pel_countries ORDER BY country_nationality ASC";
                                                            $getcitizenship = mysqli_query($connect,$query_getcitizenship) or die(mysqli_error());
                                                            $row_getcitizenship = mysqli_fetch_assoc($getcitizenship);
                                                            $totalRows_getcitizenship = mysqli_num_rows($getcitizenship);
                                                            ?>
                                                            <select class="custom-select form-control required" id="address_country" name="address_country"  required>
                                                                <option value="<?php echo $_SESSION['MM_client_country'];?>"><?php echo $_SESSION['MM_client_country'];?></option>
                                                                <?php do { ?>
                                                                    <option value="<?php echo $row_getcitizenship['country_name']; ?>"><?php echo $row_getcitizenship['country_name']; ?></option>
                                                                <?php } while ($row_getcitizenship = mysqli_fetch_assoc($getcitizenship)); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label>City</label>
                                                            <input type="text" id="address_city" name="address_city" class="form-control" placeholder="City" value="<?php echo $_SESSION['MM_client_city'];?>" required>
                                                        </div>	</div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Postal Address</label>
                                                            <input type="text" id="address_postal" name="address_postal" class="form-control" placeholder="Postal Address" value="<?php echo $_SESSION['MM_client_postal_address'];?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Postal Code</label>
                                                            <input type="text" id="address_postal_code" name="address_postal_code" class="form-control" placeholder="Postal Code" value="<?php echo $_SESSION['MM_client_postal_code'];?>" required>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!--End row -->
                                            </div>
                                            <hr>
                                            <!--End step -->
                                            <div class="form_title">
                                                <h3><strong>3</strong>Payment Details</h3>
                                            </div>
                                            <div class="step">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Enter Amount</label>
                                                            <input style="background-color:#cdd53a;" type="text" id="search-text-inputamount" name="amount_paypal" class="form-control inputamount" placeholder="Amount to Pay" value="<?php echo round(($total_volume)*$cost_convertor2,2);?>"  readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Mobile Number</label>
                                                            <input type="text" style="background-color:#cdd53a;"  class="form-control inputmobile" id="search-text-inputmobile" name="account_mobile" placeholder="Your Mobile Number" value="<?php echo $_SESSION['MM_client_mobile_number'];?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email Address</label>
                                                            <input type="text" style="background-color:#cdd53a;" class="form-control inputemail" id="search-text-inputemail" name="account_email" placeholder="Your Email Address" value="<?php echo $_SESSION['MM_client_email_address'];?>" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Currency</label>
                                                            <select name="plan_currency"  style="background-color:#cdd53a;" id="search-text-inputamount"  class="custom-select form-control" required>
                                                                <option value="<?php echo $currency_search;?>"><?php echo $currency_search;?></option>
                                                                <!--         <option value="USD">USD</option>-->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>   </div>
                                            <!--End row -->
                                            <div class="form_title">
                                                <h3><strong>4</strong>Accept Terms and Condition</h3>
                                                <p><a href="#">Click to Read Terms</a></p>
                                            </div>
                                            <div class="step">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <label class="container_checkbox"><input type="checkbox"  id="policy_terms" name="policy_terms" value="YES" required><p>I accept terms and conditions and general policy.</p>
                                                            <span class="checkmark"></span>
                                                        </label>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="submit" class="btn_1 medium" value="Submit">
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">

                                                    </div>
                                                </div>
                                            </div>
                                        </form>


                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- /col -->
                            <aside class="col-xl-4 col-lg-4" id="sidebar">
                                <div class="box_general_3 booking">

                                    <div class="title">
                                        <h3>PAYMENT CART</h3>
                                    </div>
                                    <div class="summary">
                                        <ul>
                                            <li>PAYMENT DATE: <strong class="float-right"><?php echo date('d-m-Y h:m:s'); ?></strong></li>

                                        </ul>
                                        <hr/>
                                    </div>
                                    <ul class="treatments checkout clearfix">

                                        <?php echo $listcart;?>

                                        <li class="total">
                                            CART TOTAL <strong class="float-right"> <?php echo $currency_search." ".round($total_price,2);?></strong>
                                        </li>

                                        <li class="total">
                                            <font color="#006600"><strong>CREDITS AVAILABLE</strong> <strong class="float-right"> <?php echo $currency_search." ".round(($_SESSION['MM_client_credits'])*$cost_convertor2,2);?></strong></font>
                                        </li>
                                        <?php
                                        if(round($total_volume,2) > $_SESSION['MM_client_credits'])
                                        {
                                            ?>
                                            <li class="total">
                                                TOTAL TO BUY <strong class="float-right"> <?php echo $currency_search." ".round(($total_volume-$_SESSION['MM_client_credits'])*$cost_convertor2,2);?></strong>
                                            </li>
                                            <?php
                                        }

                                        ?>
                                    </ul>
                                    <hr>


                                </div>
                                <!-- /box_general -->
                            </aside>
                            <!-- /asdide -->

                            <?php
                        }
                        if($totalRows_getcart==0 && ($set=='-1' || $set=='checkout'))
                        {
                            ?>
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <div id="confirm">
                                            <div class="icon icon--order-success svg add_bottom_15">
                                                <img src="img/warningsign.png" alt="noresultssign">
                                            </div>
                                            <h2>CHECK OUT</h2>
                                            <h2>There are no items in the payment cart for you to proceed with checkout!</h2>
                                            <p>Please go to cart and add Items to Payment cart to proceed</strong></p>

                                            <p><a href="cart/cart.php?status_search=66"><input type="submit" class="btn_1 medium" value="Add Items" id="submit-register"></a></p>
                                            <!--<p>You'll receive a confirmation email at mail@example.com</p>-->
                                        </div>
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>
                            <!-- /container -->

                            <?php
                        }


                        $maxRows_get_payments = 30;
                        $pageNum_get_payments = 0;
                        if (isset($_GET['pageNum_getpayments'])) {
                            $pageNum_get_payments = $_GET['pageNum_get_payments'];
                        }
                        $startRow_get_payments = $pageNum_get_payments * $maxRows_get_payments;

                        $query_get_payments = sprintf("SELECT* FROM pel_payments WHERE module_id='PSMT' AND client_id  = %s ORDER BY payment_date DESC", GetSQLValueString($client_id_get_psmt_requests, "text"));
                        $query_limit_get_payments = sprintf("%s LIMIT %d, %d", $query_get_payments, $startRow_get_payments, $maxRows_get_payments);
                        $get_payments = mysqli_query($connect,$query_limit_get_payments) or die(mysqli_error());
                        $row_get_payments = mysqli_fetch_assoc($get_payments);

                        if (isset($_GET['totalRows_get_payments'])) {
                            $totalRows_get_payments = $_GET['totalRows_get_payments'];
                        } else {
                            $all_get_payments = mysqli_query($connect,$query_get_payments);
                            $totalRows_get_payments = mysqli_num_rows($all_get_payments);
                        }
                        $totalPages_get_payments = ceil($totalRows_get_payments/$maxRows_get_payments)-1;

                        $queryString_get_payments = "";
                        if (!empty($_SERVER['QUERY_STRING'])) {
                            $params = explode("&", $_SERVER['QUERY_STRING']);
                            $newParams = array();
                            foreach ($params as $param) {
                                if (stristr($param, "pageNum_get_payments") == false &&
                                    stristr($param, "totalRows_get_payments") == false) {
                                    array_push($newParams, $param);
                                }
                            }
                            if (count($newParams) != 0) {
                                $queryString_get_payments = "&" . htmlentities(implode("&", $newParams));
                            }
                        }
                        $queryString_get_payments = sprintf("&totalRows_get_payments=%d%s", $totalRows_get_payments, $queryString_get_payments);

                        if($set=='mypayments' && $totalRows_get_payments>0)
                        {
                            ?>
                            <div class="col-xl-12 col-lg-12">

                                <div class="box_general_3 cart">
                                    <form class="form-horizontal m-t-40" id="requestform" name="requestform" action="<?php echo $editFormAction; ?>" method='post'>


                                        <div class="filters_listing">

                                            <ul class="clearfix">

                                                <li>

                                                    <h6>Sort by Payment Source</h6>

                                                    <form name="formc" action="<?php echo $editFormAction; ?>" method="POST" >
                                                        <select name="payment_source" class="selectbox" onChange="this.form.submit()">
                                                            <option value="ALL">ALL</option>
                                                            <option value="LPO">LPO</option>
                                                            <option value="MPESA">MPESA</option>
                                                            <option value="CARD">CARD</option>
                                                        </select>
                                                        <input type="hidden" name="MM_insert" value="formd">
                                                    </form>
                                                </li>


                                                <li>

                                                    <h6>Sort by Status</h6>

                                                    <form name="formc" action="<?php echo $editFormAction; ?>" method="POST" >
                                                        <select name="datatype_search" class="selectbox" onChange="this.form.submit()">
                                                            <option value="ALL">ALL</option>
                                                            <option value="00">Not Complete</option>
                                                            <option value="11">Success</option>
                                                            <option value="22">Unverified</option>
                                                            <option value="33">Rejected</option></select>

                                                        <input type="hidden" name="MM_insert" value="formd">
                                                    </form>
                                                </li>
                                            </ul>

                                        </div>
                                        <table id="simple-table" width="100%" class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr bgcolor="#0A4157">
                                                <th></th>

                                                <th><font color="#FFFFFF"><strong>Payment Ref:</strong></font></th>
                                                <th><font color="#FFFFFF"><strong>Payment Source:</strong></font></th>

                                                <th><font color="#FFFFFF"><strong>Payment Date:</strong></font></th>

                                                <th><font color="#FFFFFF"><strong>Amount:</strong></font></th>
                                                <th><font color="#FFFFFF"><strong>Status:</strong></font></th>
                                                <th><font color="#FFFFFF"><strong>Invoice:</strong></font></th>


                                            </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                            $x=1;
                                            $z=1;
                                            do { ?>  <tr>

                                                <td>
                                                    <?php echo $z++; ?></td>
                                                <td>
                                                    <strong>   <?php echo $row_get_payments['payment_ref']; ?></strong></td>
                                                <td><?php echo $row_get_payments['pay_source']; ?></td>
                                                <td><?php echo $row_get_payments['payment_date']; ?></td>
                                                <td><?php echo $row_get_payments['currency']; ?> <?php echo $row_get_payments['amount']; ?></td>

                                                <td><?php
                                                    if($row_get_payments['status']=='00')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_00"><span id="mybuttontext">Not Completed</span></a>
                                                        <?php

                                                    }
                                                    if($row_get_payments['status']=='11')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">Success</span></a>
                                                        <?php
                                                    }
                                                    if($row_get_payments['status']=='33')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_33"><span id="mybuttontext">Rejected</span></a>
                                                        <?php
                                                    }
                                                    if($row_get_payments['status']=='22')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">Unverified</span></a>
                                                        <?php
                                                    }
                                                    ?></td>

                                                <?php
                                                if($row_get_payments['status'] == "11")
                                                {	?>
                                                    <td><a href="dompdf/downloadpdfinvoice.php?payment_id=<?php echo $row_get_payments['payment_id'];?>&payment_ref=<?php echo $row_get_payments['payment_ref'];?>"  target="_new"><img width="30px" height="30px"  align="center" src="img/pdficon.png"></a></td>
                                                    <?php
                                                }
                                                else
                                                {	?>
                                                    <td>-</td>
                                                    <?php
                                                }
                                                ?>
                                                <!--   <td><a href="viewpayment.php?paymentid=<?php echo $row_get_payments['payment_id']; ?>"><input class="btn_1 small" value="View" type="button"></a></td>-->
                                            </tr>



                                                <?php
                                                $x++;
                                            } while ($row_get_payments = mysqli_fetch_assoc($get_payments)); ?>

                                            </tbody>
                                        </table>
                                        <nav aria-label="" class="add_top_20">
                                            <ul class="pagination pagination-sm">
                                                <li class="page-item">
                                                    <?php if ($pageNum_get_payments > 0) { // Show if not first page ?>
                                                        <a class="page-link" href="<?php printf("%s?pageNum_get_payments=%d%s", $currentPage, 0, $queryString_get_payments); ?>">First</a>
                                                    <?php } // Show if not first page ?>

                                                </li>
                                                <li class="page-item">
                                                    <?php if ($pageNum_get_payments > 0) { // Show if not first page ?>
                                                        <a  class="page-link" href="<?php printf("%s?pageNum_get_payments=%d%s", $currentPage, max(0, $pageNum_get_payments - 1), $queryString_get_payments); ?>">Previous</a>
                                                    <?php } // Show if not first page ?>

                                                </li>
                                                <li class="page-item">
                                                    <?php if ($pageNum_get_payments < $totalPages_get_payments) { // Show if not last page ?>
                                                        <a class="page-link" href="<?php printf("%s?pageNum_get_payments=%d%s", $currentPage, min($totalPages_get_payments, $pageNum_get_payments + 1), $queryString_get_payments); ?>">Next</a>
                                                    <?php } // Show if not last page ?>
                                                </li>
                                                <li class="page-item">
                                                    <?php if ($pageNum_get_payments < $totalPages_get_payments) { // Show if not last page ?>
                                                        <a class="page-link" href="<?php printf("%s?pageNum_get_psmt_requests=%d%s", $currentPage, $totalPages_get_payments, $queryString_get_payments); ?>">Last</a>
                                                    <?php } // Show if not last page ?>
                                                </li>

                                            </ul>
                                        </nav>

                                        <input type="hidden" name="MM_insert" value="requestform">
                                    </form>    </div>

                            </div>
                            </p>

                            <?php
                        }
                        ?>




                    </div>
                    <!-- /row -->
                </div>
                <!-- /container -->

                <!--Footer-->
                <?php include 'partials/footer.php'; ?>
            </div>

        </div>

    </div>

    <!-- Submit form -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="v1/js/vue.js" type="text/javascript"></script>
    <script src="v1/js/axios.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-notify.js" type="text/javascript"></script>
    <script src="assets/sweetalert/sweetalert.min.js"></script>
    <script src="v1/js/dashboard-stats.js?<?= rand(0,1000) ?>" type="text/javascript"></script>
    <script src="/js/logo.js?<?= rand(0,1000) ?>" type="text/javascript"></script>

    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    </body>
    </html>
<?php
mysqli_free_result($getcart);

?>