<?php require_once('../Connections/process.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
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

$filter_status = isset($_GET['status']) ? $_GET['status'] : -1;

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
        <link href="../css/all.css" rel="stylesheet">

        <link href="../assets/css/main.css" rel="stylesheet">
        <link href="../assets/css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

            <!--Peleza-->
        <!-- BASE CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
        <link href="../css/menu.css" rel="stylesheet">
        <link href="../css/vendors.css" rel="stylesheet">
        <link href="../css/icon_fonts/css/all_icons_min.css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body>
    <style>

        .icon {
            position: relative;
            /* Adjust these values accordingly */
            top: 7px;
            padding: 10px;
        }


        a.btn_p,
        .btn_p {
            border: none;
            color: #fff;
            background: #153f56;
            cursor: pointer;
            padding: 7px 20px;
            display: inline-block;
            outline: none;
            font-size: 14px;
            font-size: 0.875rem;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            -webkit-transition: all 0.3s ease-in-out;
            -ms-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            -webkit-border-radius: 25px;
            -moz-border-radius: 25px;
            -ms-border-radius: 25px;
            border-radius: 5px;
            font-weight: 500;
        }
        a.btn_p.status_22,
        .btn_p.status_22 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #0058a2;
            color: #ffffff;
        }
        a.btn_p.status_00,
        .btn_p.status_00 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #33007f;
            color: #ffffff;
        }
        a.btn_p.status_33,
        .btn_p.status_33 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #00967b;
            color: #ffffff;
        }
        a.btn_p.status_11,
        .btn_p.status_11 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #d14e00;
            color: #ffffff;
        }
        a.btn_p.status_44,
        .btn_p.status_44 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #960040;
            color: #ffffff;
        }
    </style>

    <span id="login-id" style="display: none"><?= $client_login_id_get_psmt_requests ?></span>
    <span id="client-id" style="display: none"><?= $client_id_get_psmt_requests ?></span>
    <span id="filter-status" style="display: none"><?= $filter_status ?></span>

    <div class="app-container body-tabs-shadow fixed-sidebar">

        <?php include '../partials/header.php'; ?>

        <div class="app-main">

            <?php include '../partials/sidebar.php'; ?>

            <div class="app-main__outer">

                <?php include '../partials/top-header.php'; ?>

                <!--Body-->
                <main id="report-stats">

                    <div class="filters_listing">
                        <div class="container">
                            <ul class="clearfix">
                                <li>
                                    <form class="form-inline">

                                        <div class="form-group" style="margin-right: 10px">
                                            <label for="status-filter" class="">Sort by Status</label>
                                        </div>

                                        <div class="form-group">
                                            <select id="status-filter" name="status_search" class="selectbox" v-on:change="displayRequests" v-model="sortByStatus">
                                                <option value="">Select Status</option>
                                                <option value="00">New Request</option>
                                                <option value="44">In Progress</option>
                                                <option value="33">Interim</option>
                                                <option value="11">Final</option>
                                                <option value="55">Awaiting Quotation</option>
                                                <option value="66">Awaiting Payment</option>
                                            </select>
                                        </div>
                                    </form>
                                </li>
                                <li>
                                    <form class="form-inline">

                                        <div class="form-group" style="margin-right: 10px">
                                            <label for="status-filter" class="">Sort by Request Type</label>
                                        </div>
                                        <div class="form-group">
                                            <select name="datatype_search" class="selectbox">
                                                <option value="ALL">ALL</option>
                                                <option value="INDIVIDUAL">INDIVIDUAL</option>
                                                <option value="COMPANY">COMPANY</option>
                                            </select>
                                        </div>
                                    </form>
                                </li>

                            </ul>
                        </div>
                        <!-- /container -->
                    </div>

                    <div class="container margin_60">

                        <div v-for="r in requestRows" class="card-group" style="margin: 10px">

                            <div v-for="rec in r.request" class="card" style="margin: 10px 10px 10px 0px ">

                                <img v-show="!rec.dataset_photo" src="../img/nophoto.png" class="card-img-top" alt="Photo" style="height: 200px;object-fit: scale-down;">
                                <img v-show="rec.dataset_photo" v-bind:src="rec.dataset_photo" class="card-img-top" alt="Photo" style="height: 200px;object-fit: cover;">

                                <div class="card-body">

                                    <h5 class="card-title" v-text="rec.bg_dataset_name"></h5>

                                </div>

                                <ul class="list-group list-group-flush">

                                    <li class="list-group-item">
                                        <div class="row no-gutters">
                                            <div class="col-4">
                                                <b>Ref:</b>
                                            </div>
                                            <div class="col-8">
                                                <span v-text="rec.request_ref_number"></span>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row no-gutters">
                                            <div class="col-md-4">
                                                <b>Package:</b>
                                            </div>
                                            <div class="col-md-8">
                                                <span v-text="rec.request_plan"></span>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row no-gutters">
                                            <div class="col-md-4">
                                                <b>Date:</b>
                                            </div>
                                            <div class="col-md-8">
                                                <span v-text="createdAt(rec.request_date)"></span>
                                            </div>
                                        </div>
                                    </li>

<!--                                    <li class="list-group-item">-->
<!--                                        <div class="row no-gutters">-->
<!--                                            <div class="col-md-4">-->
<!--                                                <b>Dataset Name:</b>-->
<!--                                            </div>-->
<!--                                            <div class="col-md-8">-->
<!--                                                <span v-text="rec.dataset_name"></span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </li>-->

                                    <li class="list-group-item">
                                        <div class="row no-gutters">
                                            <div class="col-md-4">
                                                <b>Status:</b>
                                            </div>
                                            <div class="col-md-8">
                                                <a href="#" class="btn_1 small_status_00" v-bind:class="getStatusClass(rec.status)">
                                                    <span id="mybuttontext" v-text="getStatusName(rec.status)"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row no-gutters">
                                            <div class="col-md-4">
                                                <b>Progress:</b>
                                            </div>
                                            <div class="col-md-8">
                                                <b>
                                                    <span class="rating">
                                                        <i v-for="p in rec.progress.icons" v-bind:class="p"></i>
                                                    </span>
                                                </b>
                                                <small>(<span v-text="rec.progress.percentage"></span>)</small>
                                            </div>
                                        </div>
                                    </li>

                                </ul>

                                <div class="card-body">
                                    <div class="row no-gutters">

                                        <div class="col-md-8 progress-container">
                                            <div class="progress" style="height: 30px; background-color: #c6c6c6;">
                                                <div class="progress-bar progress-bar-striped" v-bind:class="getProgressColor(rec.progress.percentage)" v-bind:id="rec.id" v-bind:percentage="rec.progress.percentage" style="width:0%">
                                                    <span v-text="getProgressText(rec.progress.percentage)" style="font-weight: bold;padding-left: 5px;"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 strip_list">
                                            <ul>
                                                <li></li>
                                                <li></li>
                                                <li><a v-bind:href="getReportURL(rec)" target="_blank">View</a></li>
                                            </ul>
                                        </div>

                                    </div>


                                </div>

                            </div>

                        </div>

                    </div>

                </main>

            </div>
        </div>
    <?php include '../partials/footer.php'; ?>
    <!--Footer-->


    <!--DataTables-->
    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='../js/jquery.js'>" + "<" + "/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='../js/jquery1x.js'>" + "<" + "/script>");
    </script>

    <script src="../js/jquery.js"></script>

    <![endif]-->
    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement) document.write("<script src='../js/jquery.mobile.custom.js'>" + "<" + "/script>");
    </script>
    <script src="../js/bootstrap.js"></script>

    <script type="text/javascript" src="../assets/scripts/main.js"></script>
    <script src="../v1/js/vue.js" type="text/javascript"></script>
        <script src="../v1/js/axios.min.js" type="text/javascript"></script>
        <script src="/js/moment.js" type="text/javascript"></script>
        <script src="/js/logo.js?<?= rand(0,1000) ?>" type="text/javascript"></script>
        <script src="../v1/js/dashboard-stats.js?<?= rand(0,1000) ?>" type="text/javascript"></script>

    </body>
    </html>
