<?php require_once('../Connections/process.php'); ?><?php
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
	
  $logoutGoTo = "../index.php";
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

$MM_restrictGoTo = "../index.php";
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

?>
<?php
 $errorcode = "";
					//	if (isset($_POST['username'])) {
$currency_search = $_SESSION['MM_client_currency'];	
			
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "requestform")) {	
			
			  if(!empty($_POST['requestlist'])){
// Loop to store and display values of individual checked checkbox.

$total_cost = 0;
$status_search_post = $_POST['status_search_post'];

 if(isset($_POST['currency_search'])) 
 {
$currency_search = $_POST['currency_search'];
}

foreach($_POST['requestlist'] as $selected){
$togetrequestlist=$selected;

if(isset($_POST['currency_search'])) 
 {
$currency_search = $_POST['currency_search'];
//$sql_insert="UPDATE pel_psmt_request SET status='$status_search_post', package_cost_currency='$currency_search' WHERE request_id='$togetrequestlist'";
 $updateSQL = sprintf("UPDATE pel_psmt_request SET status=%s, package_cost_currency=%s WHERE request_id=%s",
                       GetSQLValueString($status_search_post, "text"),
                       GetSQLValueString($currency_search, "text"),
					   GetSQLValueString($togetrequestlist, "text"));

  
  mysqli_query($connect,$updateSQL);


  if (mysqli_error())
  {
$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												ERROR!!!!!
											</strong>

											 Error on Details Havent been updated
											<br />
										</div>';

}
else
{

  $updateGoTo = "cart.php?status_search=$status_search_post";
 /* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
  }

}
else
{
//$sql_insert="UPDATE pel_psmt_request SET status='$status_search_post' WHERE request_id='$togetrequestlist'";

$updateSQL = sprintf("UPDATE pel_psmt_request SET status=%s WHERE request_id=%s",
                       GetSQLValueString($status_search_post, "text"),
					   GetSQLValueString($togetrequestlist, "text"));

  
  mysqli_query($connect,$updateSQL);


  if (mysqli_error())
  {
$errorcode = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												ERROR!!!!!
											</strong>

											 Error on Details Havent been updated
											<br />
										</div>';

}
else
{

  $updateGoTo = "cart.php?status_search=$status_search_post";
 /* if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
  }
}
	
			//	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());			
//    $result = mysqli_query($connect,$sql_insert) or die('Query failed: ' . mysqli_error());		

}
}
else
{
$errorcode = "<div class='error_message'><p>Kindly Select atleast one request to submit</p></div>";

}
			
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
        <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" type="image/x-icon" href="../img/apple-touch-icon-57x57-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="../img/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="../img/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="../img/apple-touch-icon-144x144-precomposed.png">

        <link href="../assets/css/main.css" rel="stylesheet">
        <link href="../assets/css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>

        <!--Peleza-->
        <!-- BASE CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
        <link href="../css/menu.css" rel="stylesheet">
        <link href="../css/vendors.css" rel="stylesheet">
        <link href="../css/icon_fonts/css/all_icons_min.css" rel="stylesheet">

        <!-- YOUR CUSTOM CSS -->
        <link href="../css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">

    </head>
    <body>

    <div class="app-container body-tabs-shadow fixed-sidebar">
        <?php include '../partials/header.php'; ?>

        <div class="app-main">

            <?php include '../partials/sidebar.php'; ?>

            <div class="app-main__outer">

                <?php include '../partials/top-header.php'; ?>

                <!--Body-->
                <div class="filters_listing">
                    <div class="container">
                        <ul class="clearfix">

                            <li>



                                <div class="switch-field">

                                    <?php
                                    if ((isset($_GET["status_search"]))) {
                                        $status_search2 = $_GET['status_search'];
                                    }
                                    else
                                    {
                                        $status_search2 = "00";
                                    }
                                    ?>

                                    <label for="" style="background-color:#929e36">CHOOSE STATUS:</label>
                                    <a href="cart.php?status_search=00"> <?php

                                        if( $status_search2 == "00")
                                        {
                                            ?>
                                            <input type="radio" id="all" name="type_patient" value="all" checked>  <?php
                                        }

                                        ?><label for="New Request">New Request</label></a>


                                    <a href="cart.php?status_search=55"><?php

                                        if( $status_search2 == "55")
                                        {
                                            ?>
                                            <input type="radio" id="all" name="type_patient" value="all" checked>  <?php
                                        }

                                        ?><label for="New Request">AWAITING QUOTATION</label></a>

                                    <a href="cart.php?status_search=66"><?php

                                        if( $status_search2 == "66")
                                        {
                                            ?>
                                            <input type="radio" id="all" name="type_patient" value="all" checked>  <?php
                                        }

                                        ?><label for="New Request">QUOTATION GENERATED</label></a>
                                    <a href="cart.php?status_search=77"><?php

                                        if( $status_search2 == "77")
                                        {
                                            ?>
                                            <input type="radio" id="all" name="type_patient" value="all" checked>  <?php
                                        }

                                        ?><label for="New Request">PAYMENT CART</label></a>
                                </div>
                            </li>
                            <?php

                            if($status_search2=='00' || $status_search2=='77')
                            {
                                ?>
                                <li> <?php


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
                                <?php
                            }

                            ?>
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

                <div class="container margin_30_35">
                    <div class="row">

                        <?php

                        if ((isset($_GET["status_search"]))) {

                            $status_search = $_GET['status_search'];
                            $maxRows_get_psmt_requests = 20;
                            $pageNum_get_psmt_requests = 0;
                            if (isset($_GET['pageNum_get_psmt_requests'])) {
                                $pageNum_get_psmt_requests = $_GET['pageNum_get_psmt_requests'];
                            }
                            $startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;

                            $query_get_psmt_requests = sprintf("SELECT * FROM pel_psmt_request WHERE client_id  = %s and client_login_id = %s and status= %s ORDER BY request_date DESC", GetSQLValueString($client_id_get_psmt_requests, "text"),GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_search, "text"));
                            $query_limit_get_psmt_requests = sprintf("%s LIMIT %d, %d", $query_get_psmt_requests, $startRow_get_psmt_requests, $maxRows_get_psmt_requests);
                            $get_psmt_requests = mysqli_query($connect,$query_limit_get_psmt_requests) or die(mysqli_error());
                            $row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests);

                            if (isset($_GET['totalRows_get_psmt_requests'])) {
                                $totalRows_get_psmt_requests = $_GET['totalRows_get_psmt_requests'];
                            } else {
                                $all_get_psmt_requests = mysqli_query($connect,$query_get_psmt_requests);
                                $totalRows_get_psmt_requests = mysqli_num_rows($all_get_psmt_requests);
                            }
                            $totalPages_get_psmt_requests = ceil($totalRows_get_psmt_requests/$maxRows_get_psmt_requests)-1;

                            $queryString_get_psmt_requests = "";
                            if (!empty($_SERVER['QUERY_STRING'])) {
                                $params = explode("&", $_SERVER['QUERY_STRING']);
                                $newParams = array();
                                foreach ($params as $param) {
                                    if (stristr($param, "pageNum_get_psmt_requests") == false &&
                                        stristr($param, "totalRows_get_psmt_requests") == false) {
                                        array_push($newParams, $param);
                                    }
                                }
                                if (count($newParams) != 0) {
                                    $queryString_get_psmt_requests = "&" . htmlentities(implode("&", $newParams));
                                }
                            }
                            $queryString_get_psmt_requests = sprintf("&totalRows_get_psmt_requests=%d%s", $totalRows_get_psmt_requests, $queryString_get_psmt_requests);



                            ?>

                            <?php
                        }
                        else {
                            $status_search = "00";
                            $maxRows_get_psmt_requests = 20;
                            $pageNum_get_psmt_requests = 0;
                            if (isset($_GET['pageNum_get_psmt_requests'])) {
                                $pageNum_get_psmt_requests = $_GET['pageNum_get_psmt_requests'];
                            }
                            $startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;

                            $query_get_psmt_requests = sprintf("SELECT * FROM pel_psmt_request WHERE client_id  = %s and client_login_id = %s and status= %s ORDER BY request_date DESC", GetSQLValueString($client_id_get_psmt_requests, "text"),GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_search, "text"));
                            $query_limit_get_psmt_requests = sprintf("%s LIMIT %d, %d", $query_get_psmt_requests, $startRow_get_psmt_requests, $maxRows_get_psmt_requests);
                            $get_psmt_requests = mysqli_query($connect,$query_limit_get_psmt_requests) or die(mysqli_error());
                            $row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests);

                            if (isset($_GET['totalRows_get_psmt_requests'])) {
                                $totalRows_get_psmt_requests = $_GET['totalRows_get_psmt_requests'];
                            } else {
                                $all_get_psmt_requests = mysqli_query($connect,$query_get_psmt_requests);
                                $totalRows_get_psmt_requests = mysqli_num_rows($all_get_psmt_requests);
                            }
                            $totalPages_get_psmt_requests = ceil($totalRows_get_psmt_requests/$maxRows_get_psmt_requests)-1;



                            $queryString_get_psmt_requests = "";
                            if (!empty($_SERVER['QUERY_STRING'])) {
                                $params = explode("&", $_SERVER['QUERY_STRING']);
                                $newParams = array();
                                foreach ($params as $param) {
                                    if (stristr($param, "pageNum_get_psmt_requests") == false &&
                                        stristr($param, "totalRows_get_psmt_requests") == false) {
                                        array_push($newParams, $param);
                                    }
                                }
                                if (count($newParams) != 0) {
                                    $queryString_get_psmt_requests = "&" . htmlentities(implode("&", $newParams));
                                }
                            }
                            $queryString_get_psmt_requests = sprintf("&totalRows_get_psmt_requests=%d%s", $totalRows_get_psmt_requests, $queryString_get_psmt_requests);

                        }
                        ?>

                        <?php
                        if($totalRows_get_psmt_requests > '0')
                        {
                        ?>

                        <div class="col-xl-12 col-lg-12">

                            <div class="box_general_3 cart">
                                <form class="form-horizontal m-t-40" id="requestform" name="requestform" action="<?php echo $editFormAction; ?>" method='post'>
                                    <?php

                                    if($status_search2=='00' || $status_search2=='77')
                                    {
                                        ?>

                                        <input value="<?php echo $currency_search;?>" type="hidden" id="currency_search" name="currency_search" class="form-control" readonly/>
                                        <?php
                                    }
                                    ?>
                                    <div class="main_title_4">
                                        <h3><i class="icon_circle-slelected"></i><?php
                                            if($status_search == "00")
                                            {
                                                ?>Select the checkbox to Request for Quotation
                                                <input value="55" type="hidden" id="status_search_post" name="status_search_post" class="form-control" readonly/>
                                                <?php
                                            }
                                            if($status_search == "55")
                                            {
                                                ?>
                                                Requests Awaiting Quotation from Account Manager
                                                <?php
                                            }
                                            if($status_search == "66")
                                            {
                                                ?>
                                                Select the checkbox to Mark for Payment and add to Payment CART.
                                                <input value="77" type="hidden" id="status_search_post" name="status_search_post" class="form-control" readonly/>
                                                <?php
                                            }
                                            if($status_search == "77")
                                            {
                                                ?>
                                                Select the checkbox to remove from Payment Cart OR   <a href="../payments.php"><input type="button" class="btn_1 medium2" value="Proceed to Payment" id="submit-register"></a>
                                                <input value="66" type="hidden" id="status_search_post" name="status_search_post" class="form-control" readonly/>
                                                <?php
                                            }
                                            ?>
                                        </h3>
                                    </div>
                                    <?php echo $errorcode; ?>
                                    <table  id="simple-table" width="100%" class="table  table-striped  table-bordered table-hover">

                                        <thead>
                                        <tr>

                                            <?php
                                            if($status_search == "00" || $status_search == "66" || $status_search == "77")
                                            {
                                                ?>  <td><b>Select:</b></td>
                                                <?php
                                            }
                                            if($status_search == "55")
                                            {	?>
                                                <td></td>
                                                <?php
                                            }
                                            ?>
                                            <td><b>Dataset Name:</b></td>
                                            <td><b>Ref Number:</b></td>
                                            <td><b>Package:</b></td>
                                            <td><b>Request Date:</b></td>
                                            <?php
                                            if($status_search == "55")
                                            {	?>
                                                <td><b>Date Sent:</b></td>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if($status_search == "66" || $status_search == "77")
                                            {	?>

                                                <td><b>Package Cost:</b></td>

                                                <?php
                                            }
                                            ?>
                                            <td><b>Status:</b> </td>
                                            <?php
                                            if($status_search == "66" || $status_search == "77")
                                            {	?>


                                                <td><b>Quote:</b></td>
                                                <?php
                                            }
                                            ?>
                                            <td><b>Action:</b> </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $x=1;
                                        $z=1;
                                        $total_cost=0;
                                        do { ?>



                                            <tr>
                                                <?php
                                                if($status_search == "00" || $status_search == "66" || $status_search == "77")
                                                {
                                                    ?>
                                                    <td><label class="container_checkbox"><input type="checkbox"  id="requestlist<?php echo $x;?>" name="requestlist[]" value="<?php echo $row_get_psmt_requests['request_id']; ?>">
                                                            <span class="checkmark"></span>
                                                        </label></td>

                                                    <?php
                                                }
                                                if($status_search == "55")
                                                {	?>
                                                    <td>
                                                        <?php echo $z++; ?></td>
                                                    <?php
                                                }
                                                ?>
                                                <td>
                                                    <?php echo $row_get_psmt_requests['bg_dataset_name']; ?></td>
                                                <td><?php echo $row_get_psmt_requests['request_ref_number']; ?></td>
                                                <td><?php echo $row_get_psmt_requests['request_plan']; ?></td>
                                                <td><?php echo $row_get_psmt_requests['request_date']; ?></td>
                                                <?php
                                                if($status_search == "55")
                                                {	?>
                                                    <td><?php echo $row_get_psmt_requests['status_date']; ?></td>
                                                    <?php
                                                }
                                                ?>
                                                <?php


                                                if($status_search == "77")
                                                {

                                                    $query_getcreditcurrency2 = sprintf("SELECT pel_credits.credit_cost, pel_credits.credit_volume, pel_currency.currency_name, pel_currency.currency_code, pel_currency.currency_id FROM pel_credits Inner Join pel_currency ON pel_credits.credit_currency = pel_currency.currency_id WHERE  pel_currency.currency_code ='$currency_search'");
                                                    $getcreditcurrency2 = mysqli_query($connect,$query_getcreditcurrency2) or die(mysqli_error());
                                                    $row_getcreditcurrency2 = mysqli_fetch_assoc($getcreditcurrency2);
                                                    $totalRows_getcreditcurrency2 = mysqli_num_rows($getcreditcurrency2);

                                                    $currency_search3 = $row_get_psmt_requests['package_cost_currency'];

                                                    $query_getcreditcurrency3 = sprintf("SELECT pel_credits.credit_cost, pel_credits.credit_volume, pel_currency.currency_name, pel_currency.currency_code, pel_currency.currency_id FROM pel_credits Inner Join pel_currency ON pel_credits.credit_currency = pel_currency.currency_id WHERE  pel_currency.currency_code ='$currency_search3'");
                                                    $getcreditcurrency3 = mysqli_query($connect,$query_getcreditcurrency3) or die(mysqli_error());
                                                    $row_getcreditcurrency3 = mysqli_fetch_assoc($getcreditcurrency3);
                                                    $totalRows_getcreditcurrency3 = mysqli_num_rows($getcreditcurrency3);

                                                    $cost_convertor2 = $row_getcreditcurrency2['credit_cost'];

                                                    $cost_convertor3 = $row_getcreditcurrency3['credit_cost'];

                                                    $cost_cuurency = $row_get_psmt_requests['package_cost'] * $cost_convertor2/$cost_convertor3;
                                                    $total_cost=$total_cost+$cost_cuurency;
                                                    ?>
                                                    <td><?php

                                                        echo $currency_search; ?><b> <?php echo round($cost_cuurency,2); ?></b></td>
                                                    <?php
                                                }
                                                if($status_search == "66")
                                                {

                                                    ?>
                                                    <td><?php

                                                        echo $row_get_psmt_requests['package_cost_currency']; ?><b> <?php echo $row_get_psmt_requests['package_cost']; ?></b></td>
                                                    <?php
                                                }
                                                ?>
                                                <td><?php
                                                    if($row_get_psmt_requests['status']=='00')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_00"><span id="mybuttontext">New Request</span></a>
                                                        <?php

                                                    }
                                                    if($row_get_psmt_requests['status']=='11')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">Final Report</span></a>
                                                        <?php
                                                    }
                                                    if($row_get_psmt_requests['status']=='33')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_33"><span id="mybuttontext">Interim</span></a>
                                                        <?php
                                                    }
                                                    if($row_get_psmt_requests['status']=='44')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">In Progress</span></a>
                                                        <?php
                                                    }
                                                    if($row_get_psmt_requests['status']=='55')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_33"><span id="mybuttontext">Awaiting Quotation</span></a>
                                                        <?php
                                                    }
                                                    if($row_get_psmt_requests['status']=='66')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">Awaiting Payment</span></a>
                                                        <?php
                                                    }
                                                    if($row_get_psmt_requests['status']=='77')
                                                    {
                                                        ?>
                                                        <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">Awaiting Payment</span></a>
                                                        <?php
                                                    }
                                                    ?></td>

                                                <?php
                                                if($status_search == "66" || $status_search == "77")
                                                {	?>
                                                    <td> <a href="../dompdf/downloadpdfquote.php?requestid=<?php echo $row_get_psmt_requests['request_id'];?>&request_ref_number=<?php echo $row_get_psmt_requests['request_ref_number'];?>"  target="_new"><img width="30px" height="30px"  align="center" src="../img/pdficon.png"></a></td>
                                                    <?php
                                                }
                                                ?>
                                                <td><a href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>"><input class="btn_1 small" value="View" type="button"></a></td>
                                            </tr>
                                            <?php
                                            $x++;
                                        } while ($row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests)); ?>
                                        <?php
                                        if($status_search == "77")
                                        {	?>
                                            <tr><td colspan="5" align="right">TOTAL:</td><td><?php echo $currency_search;?> <b> <?php echo round($total_cost,2);?></b></td></tr>
                                            <?php
                                        }
                                        ?>


                                        </tbody>
                                    </table>
                                    <nav aria-label="" class="add_top_20">
                                        <ul class="pagination pagination-sm">
                                            <li class="page-item">
                                                <?php if ($pageNum_get_psmt_requests > 0) { // Show if not first page ?>
                                                    <a class="page-link" href="<?php printf("%s?pageNum_get_psmt_requests=%d%s", $currentPage, 0, $queryString_get_psmt_requests); ?>">First</a>
                                                <?php } // Show if not first page ?>

                                            </li>
                                            <li class="page-item">
                                                <?php if ($pageNum_get_psmt_requests > 0) { // Show if not first page ?>
                                                    <a  class="page-link" href="<?php printf("%s?pageNum_get_psmt_requests=%d%s", $currentPage, max(0, $pageNum_get_psmt_requests - 1), $queryString_get_psmt_requests); ?>">Previous</a>
                                                <?php } // Show if not first page ?>

                                            </li>
                                            <li class="page-item">
                                                <?php if ($pageNum_get_psmt_requests < $totalPages_get_psmt_requests) { // Show if not last page ?>
                                                    <a class="page-link" href="<?php printf("%s?pageNum_get_psmt_requests=%d%s", $currentPage, min($totalPages_get_psmt_requests, $pageNum_get_psmt_requests + 1), $queryString_get_psmt_requests); ?>">Next</a>
                                                <?php } // Show if not last page ?>
                                            </li>
                                            <li class="page-item">
                                                <?php if ($pageNum_get_psmt_requests < $totalPages_get_psmt_requests) { // Show if not last page ?>
                                                    <a class="page-link" href="<?php printf("%s?pageNum_get_psmt_requests=%d%s", $currentPage, $totalPages_get_psmt_requests, $queryString_get_psmt_requests); ?>">Last</a>
                                                <?php } // Show if not last page ?>
                                            </li>

                                        </ul>
                                    </nav>
                                    <?php
                                    if($status_search == "00" || $status_search == "66")
                                    {	?>
                                        <div class="col-md-6"><input type="submit" class="btn_1 medium" value="Submit" id="submit-register"></div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($status_search == "77")
                                    {	?>
                                        <input type="submit" class="btn_1 medium" value="Remove From Cart" id="submit-register">

                                        <?php
                                    }
                                    ?>




                                    <input type="hidden" name="MM_insert" value="requestform">
                                </form>
                                <a href="../dompdf/downloadpdfpi.php?client_id=<?php echo $client_id_get_psmt_requests;?>" target="_blank"> OR  <div class="col-md-6"><input type="submit" class="btn_1 medium" value="Download Proforma Invoice" id="submit-register"></a></div>
                            </div>

                        </div>
                    </div>
                    <?php
                    }
                    else
                    {
                        ?>
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div id="confirm">
                                        <div class="icon icon--order-success svg add_bottom_15">
                                            <img src="../img/warningsign.png" alt="noresultssign">
                                        </div>
                                        <h2>Their is no requests that are under the chosen Request Status!</h2>
                                        <!--<p>You'll receive a confirmation email at mail@example.com</p>-->
                                    </div>
                                </div>
                            </div>
                            <!-- /row -->
                        </div>
                        <!-- /container -->

                        <?php
                    }
                    ?>
                </div>
                <!-- /row -->

                <!--Footer-->
                <?php include '../partials/footer.php'; ?>

            </div>
         </div>
    </div>
    <script type="text/javascript" src="../assets/scripts/main.js"></script>

    <script src="../v1/js/vue.js" type="text/javascript"></script>
    <script src="../v1/js/axios.min.js" type="text/javascript"></script>
    <script src="../v1/js/dashboard-stats.js?<?= rand(0,1000) ?>" type="text/javascript"></script>
    <script src="/js/logo.js?<?= rand(0,1000) ?>" type="text/javascript"></script>

    </body>
    </html>
<?php
mysqli_free_result($get_psmt_requests);

mysqli_free_result($getpackagenames);



?>