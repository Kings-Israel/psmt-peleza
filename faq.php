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
                    <aside class="col-lg-2" id="sidebar">
                        <div class="box_style_cat" id="faq_box">
                            <ul id="cat_nav">
                                <li><a href="#payment" class="active"><i class="icon_document_alt"></i>Payments</a></li>
                                <li><a href="#tips"><i class="icon_document_alt"></i>Conducting BG Check</a></li>
                                <li><a href="#reccomendations"><i class="icon_document_alt"></i>Reccomendations</a></li>
                                <li><a href="#terms"><i class="icon_document_alt"></i>Terms&amp;conditons</a></li>
                                <li><a href="#booking"><i class="icon_document_alt"></i>Screening Reports</a></li>
                            </ul>
                        </div>
                        <!--/sticky -->
                    </aside>
                    <!--/aside -->

                    <div class="col-lg-10" id="faq">
                        <h4 class="nomargin_top">Payments</h4>
                        <div role="tablist" class="add_bottom_45 accordion" id="payment">
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne_payment" aria-expanded="true"><i class="indicator icon_minus_alt2"></i>FAQ 1</a>
                                    </h5>
                                </div>

                                <div id="collapseOne_payment" class="collapse show" role="tabpanel" data-parent="#payment">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo_payment" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 2
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseTwo_payment" class="collapse" role="tabpanel" data-parent="#payment">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree_payment" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 3
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseThree_payment" class="collapse" role="tabpanel" data-parent="#payment">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /accordion payment -->

                        <h4 class="nomargin_top">Conducting BG Check</h4>
                        <div role="tablist" class="add_bottom_45 accordion" id="tips">
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne_tips" aria-expanded="true"><i class="indicator icon_plus_alt2"></i>FAQ 1</a>
                                    </h5>
                                </div>

                                <div id="collapseOne_tips" class="collapse" role="tabpanel" data-parent="#tips">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo_tips" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 2
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseTwo_tips" class="collapse" role="tabpanel" data-parent="#tips">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree_tips" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 3
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseThree_tips" class="collapse" role="tabpanel" data-parent="#tips">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /accordion Conducting BG Check -->

                        <h4 class="nomargin_top">Reccomendations</h4>
                        <div role="tablist" class="add_bottom_45 accordion" id="reccomendations">
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne_reccomendations" aria-expanded="true"><i class="indicator icon_plus_alt2"></i>FAQ 1</a>
                                    </h5>
                                </div>

                                <div id="collapseOne_reccomendations" class="collapse" role="tabpanel" data-parent="#reccomendations">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo_reccomendations" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 2
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseTwo_reccomendations" class="collapse" role="tabpanel" data-parent="#reccomendations">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree_reccomendations" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 3
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseThree_reccomendations" class="collapse" role="tabpanel" data-parent="#reccomendations">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /accordion Reccomendations -->

                        <h4 class="nomargin_top">Terms&amp;conditons</h4>
                        <div role="tablist" class="add_bottom_45 accordion" id="terms">
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne_terms" aria-expanded="true"><i class="indicator icon_plus_alt2"></i>FAQ 1</a>
                                    </h5>
                                </div>

                                <div id="collapseOne_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo_terms" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 2
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseTwo_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree_terms" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 3
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseThree_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /accordion Terms -->

                        <h4 class="nomargin_top">Screening Reports</h4>
                        <div role="tablist" class="add_bottom_45 accordion" id="booking">
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne_booking" aria-expanded="true"><i class="indicator icon_plus_alt2"></i>FAQ 1</a>
                                    </h5>
                                </div>

                                <div id="collapseOne_booking" class="collapse" role="tabpanel" data-parent="#booking">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo_booking" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 2
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseTwo_booking" class="collapse" role="tabpanel" data-parent="#booking">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree_booking" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>FAQ 3
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseThree_booking" class="collapse" role="tabpanel" data-parent="#booking">
                                    <div class="card-body">
                                        <p>Faq Content Here</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /accordion Booking -->

                    </div>
                    <!-- /col -->
                </div>
                <!-- /row -->
            </div>

            <!--Footer-->
            <?php include 'partials/footer.php'; ?>
        </div>
    </div>
</div>

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