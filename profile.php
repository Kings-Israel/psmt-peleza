<?php require_once('Connections/process.php');

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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "adressform")) {
  $updateSQL = sprintf("UPDATE pel_client SET client_postal_address=%s, client_city=%s, client_postal_code=%s WHERE client_id=%s",
                       GetSQLValueString(filter_var($_POST['client_postal_address'], FILTER_SANITIZE_STRING), "text"),
                       GetSQLValueString(filter_var($_POST['client_city'], FILTER_SANITIZE_STRING), "text"),
                       GetSQLValueString(filter_var($_POST['client_postal_code'], FILTER_SANITIZE_STRING), "text"),
                       GetSQLValueString(filter_var($_POST['client_id'], FILTER_SANITIZE_INT), "int"));

  
  $Result1 = mysqli_query($connect,$updateSQL) or die(mysqli_error());

  $updateGoTo = "profile.php";
  if (isset($_SERVER['QUERY_STRING'])) {

    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];

  }

  header(sprintf("Location: %s", $updateGoTo));

}
$errorcode = "";
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "changepwdform")) {

if($_POST['client_password']==$_POST['client_password2'])
{
  $updateSQL = sprintf("UPDATE pel_client SET client_password=%s WHERE client_id=%s",
                       GetSQLValueString(md5($_POST['client_password']), "text"),
                       GetSQLValueString(filter_var($_POST['client_id'], FILTER_SANITIZE_INT), "int"));

  
  $Result1 = mysqli_query($connect,$updateSQL) or die(mysqli_error());

  $updateGoTo = "profile.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  }
  else
  {
$errorcode = "<div class='error_message'><p style='color:#990000; font-size:24px;'>Passwords Do Not Match</p></div>";
  }
}
$client_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_id'])) {
  $client_id_get_psmt_requests = $_SESSION['MM_client_id'];
}
$client_login_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_login_id'])) {
  $client_login_id_get_psmt_requests = $_SESSION['MM_client_login_id'];
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
            <div class="container margin_60">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="box_general_3 cart">
                            <div class="message"><p>Your Created Profile: <a href="#0"></a></p></div>
                            <?php echo $errorcode;?>
                            <div class="form_title">
                                <h3><strong>1</strong>Personal Details:</h3>
                                <p>To edit details kindly contact Peleza PSMT administrator.</p>
                            </div>
                            <div class="step">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>First name</label>
                                            <input type="text" class="form-control" id="client_first_name" name="client_first_name" placeholder="Jhon" value="<?php echo $_SESSION['MM_first_name'];?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Other names</label>
                                            <input type="text" class="form-control" id="client_last_name" name="client_last_name" placeholder="Doe" value="<?php echo $_SESSION['MM_last_name'];?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <input type="text" id="added_date" name="added_date" class="form-control" placeholder="00 44 678 94329" value="<?php echo $_SESSION['MM_client_country'];?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Parent Company</label>
                                            <input type="text" id="added_date" name="added_date" class="form-control" placeholder="00 44 678 94329" value="<?php echo $_SESSION['MM_client_parent_company'];?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Industry</label>
                                            <input type="text" id="added_date" name="added_date" class="form-control" placeholder="00 44 678 94329" value="<?php echo $_SESSION['MM_client_industry'];?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form_title">
                                <h3><strong>2</strong>Contact and Billing Address Details:</h3>
                                <p>To edit (Email and Mobile Phone) details kindly contact Peleza PSMT administrator.</p>
                            </div>
                            <div class="step">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" id="client_email_address" name="client_email_address" class="form-control" placeholder="jhon@doe.com" value="<?php echo $_SESSION['MM_client_email_address'];?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Mobile Phone</label>
                                            <input type="text" id="client_mobile_number" name="client_mobile_number" class="form-control" placeholder="00 44 678 94329" value="<?php echo $_SESSION['MM_client_mobile_number'];?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <form class="form-horizontal m-t-40" id="addressform" name="adressform" action="<?php echo $editFormAction; ?>" method='POST'>
                                    <input type="hidden" class="form-control" id="client_id" name="client_id" placeholder="Jhon" value="<?php echo $_SESSION['MM_client_id'];?>">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label>Postal Address</label>
                                                <input type="text" class="form-control" id="client_postal_address" name="client_postal_address" placeholder="Jhon" value="<?php echo $_SESSION['MM_client_postal_address'];?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label>Postal Code</label>
                                                <input type="text" class="form-control" id="client_postal_code" name="client_postal_code" placeholder="Doe" value="<?php echo $_SESSION['MM_client_postal_code'];?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label>Postal City</label>
                                                <input type="text" class="form-control" id="client_city" name="client_city" placeholder="Doe" value="<?php echo $_SESSION['MM_client_city'];?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="submit" class="btn_1 small2" value="Change Address" id="submit-register">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="MM_update" value="adressform">
                                </form>
                            </div>
                            <hr>
                            <!--End step -->

                            <div class="form_title">
                                <h3><strong>3</strong>Credentials</h3>
                                <p>You can Change and Edit Password.</p>
                            </div>
                            <div class="step">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Login Username</label>
                                            <input type="text" id="card_number" name="card_number" class="form-control" placeholder="xxxx - xxxx - xxxx - xxxx" value="<?php echo $_SESSION['MM_Username'];?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <form class="form-horizontal m-t-40" id="changepwdform" name="changepwdform" action="<?php echo $editFormAction; ?>" method='POST'>
                                            <input type="hidden" class="form-control" id="client_id" name="client_id" placeholder="Jhon" value="<?php echo $_SESSION['MM_client_id'];?>">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <input type="password" id="client_password" name="client_password" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Repeat Password</label>
                                                        <input type="password" id="client_password2" name="client_password2" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="submit" class="btn_1 small2" value="Change Password" id="submit-register">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--End row -->
                                            <input type="hidden" name="MM_update" value="changepwdform">
                                        </form>
                                    </div>
                                    <hr>
                                    <!--End step -->
                                </div>
                            </div>
                            <!-- /col -->
                        </div>
                        <!-- /row -->
                    </div>
                </div>
            </div>

            <!--Footer-->
            <?php include 'partials/footer.php'; ?>
        </div>
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="v1/js/vue.js" type="text/javascript"></script>
        <script src="v1/js/axios.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-notify.js" type="text/javascript"></script>
        <script src="assets/sweetalert/sweetalert.min.js"></script>
        <script src="v1/js/dashboard-stats.js?<?= rand(0,1000) ?>" type="text/javascript"></script>
        <script src="/js/logo.js?<?= rand(0,1000) ?>" type="text/javascript"></script>
    </div>
</div>
<script type="text/javascript" src="./assets/scripts/main.js"></script>
</body>
</html>
