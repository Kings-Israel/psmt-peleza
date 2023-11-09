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
$status_search = '';
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
    <div class="app-container body-tabs-shadow fixed-sidebar">
        <?php include '../partials/header.php'; ?>

        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <div class="header__pane ml-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="fa-stack"><i class="icon-menu-3 fa-stack-2x" ></i></span>
                            </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box"><span class="hamburger-inner"></span></span>
                        </button>
                    </div>
                </div>
                <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm">
                        <span class="btn-icon-wrapper"><i class="fa fa-ellipsis-v fa-w-6"></i></span>
                    </button>
                </span>
                </div>

                <!--Sidebar Scroll-->
                <div class="scrollbar-sidebar sidebar-color">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu ">
                            <li class="app-sidebar__heading text-light" >Main</li>
                            <li class="text-light">
                                <a href="../dashboard/index.php">
                                    <span class="fa-stack"><i class="icon-database fa-stack-1x"></i></span>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#0">
                                    <span class="fa-stack"><i class="icon-mail-6 fa-stack-1x"></i></span>
                                    Make Request
                                    <span class="fa-stack"><i class="icon-angle-right fa-stack-1x"></i></span>
                                </a>
                                <ul>
                                    <?php
                                    $query_getpackagenames = sprintf("SELECT package_id, package_name, client_id FROM pel_client_package where client_id = %s ", GetSQLValueString($client_id_get_psmt_requests, "int"));
                                    $getpackagenames = mysqli_query($connect,$query_getpackagenames) or die(mysqli_error());
                                    $row_getpackagenames = mysqli_fetch_assoc($getpackagenames);
                                    $totalRows_getpackagenames = mysqli_num_rows($getpackagenames);
                                    $queryitem = "(";
                                    if($totalRows_getpackagenames > '0') {
                                        $x = 1;
                                        do {
                                            $queryitem .= "'".$row_getpackagenames['package_name']."',";

                                            ?>
                                            <li><a href="../request.php?package_id=<?php echo $row_getpackagenames['package_id']; ?>"><i class="metismenu-icon"></i> <?php echo $row_getpackagenames['package_name']; ?> </a></li>

                                            <?php
                                            $x++;
                                        } while ($row_getpackagenames = mysqli_fetch_assoc($getpackagenames));
                                    } else {
                                        $queryitem .= "";
                                        $x = 1;

                                        do {
                                            $queryitem .= "'".$row_getpackagegeneral['package_name']."',";
                                            ?>
                                            <li><a href="../request.php?package_id=<?php echo $row_getpackagegeneral['package_id']; ?>"><i class="metismenu-icon"></i> <?php echo $row_getpackagegeneral['package_name']; ?> </a></li>

                                            <?php
                                        } while ($row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral));
                                    }

                                    $queryitem .= "'')";

                                    ?>
                                </ul>
                            </li>
                            <li>
                                <a href="./" class="mm-active">
                                    <span class="fa-stack"><i class="icon-newspaper-1 fa-stack-1x"></i></span>
                                    Reports
                                </a>
                            </li>
                            <li>
                                <a href="../cart/cart.php">
                                    <span class="fa-stack"><i class="icon-cart fa-stack-1x"></i></span>
                                    Cart
                                </a>
                            </li>
                            <li>
                                <a href="../payments.php">
                                    <span class="fa-stack"><i class="icon-dollar-1 fa-stack-1x"></i></span>
                                    Payment
                                </a>
                            </li>
                            <li>
                                <a href="../faq.php">
                                    <span class="fa-stack"><i class="icon-question fa-stack-1x"></i></span>
                                    FAQs
                                </a>
                            </li>
                            <li>
                                <a href="../testapi.php">
                                    <span class="fa-stack"><i class="icon-code-3 fa-stack-1x"></i></span>
                                    APIs
                                </a>
                            </li>
                            <li class="app-sidebar__heading text-light">YOUR STUFF</li>
                            <li>
                                <a href="../profile.php" >
                                    <span class="fa-stack"><i class="icon-user fa-stack-1x"></i></span>
                                    Profile
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="app-main__outer">
                <?php include "../partials/app_inner.php"; ?>

                <!--Body-->
                <div class="filters_listing">
                    <div class="container">
                        <ul class="clearfix">
                            <li>
                                <h6>Sort by Status</h6>
                                <form name="formc" action="<?php echo $editFormAction; ?>" method="POST" >
                                    <select name="status_search" class="selectbox" required>
                                        <?php
                                        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formc")) {
                                            $status_search = filter_var($_POST['status_search'], FILTER_SANITIZE_STRING);
                                            ?>
                                            <option value="<?php if ($status_search == '00') { echo "New Request"; }
                                            if ($status_search == '44') { echo "In Progress"; }
                                            if ($status_search == '33') { echo "Interim"; }
                                            if ($status_search == '11') { echo "Final"; }
                                            if ($status_search == '55') { echo "Awaiting Quotation"; }
                                            if ($status_search == '66') { echo "Awaiting Payment"; }

                                            ?>"><?php if ($status_search == '00') { echo "New Request"; }
                                                if ($status_search == '44') { echo "In Progress"; }
                                                if ($status_search == '33') { echo "Interim"; }
                                                if ($status_search == '11') { echo "Final"; }
                                                if ($status_search == '55') { echo "Awaiting Quotation"; }
                                                if ($status_search == '66') { echo "Awaiting Payment"; }

                                                ?></option>
                                            <?php
                                        }
                                        ?>
                                        <option value="">Select Status</option>
                                        <option value="00">New Request</option>
                                        <option value="44">In Progress</option>
                                        <option value="33">Interim</option>
                                        <option value="11">Final</option>
                                        <option value="55">Awaiting Quotation</option>
                                        <option value="66">Awaiting Payment</option>
                                    </select>

                                    <input type="hidden" name="MM_insert" value="formc">
                                    <input type="submit" class="btn_1" value="Click to Filter" id="submit-register">
                                </form>
                            </li>
                            <li>
                                <h6>Sort by Request Type</h6>
                                <form name="formd" action="<?php echo $editFormAction; ?>" method="POST" >
                                    <select name="datatype_search" class="selectbox" onChange="this.form.submit()">
                                        <?php
                                        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formd")) {
                                            $datatype_search = filter_var($_POST['datatype_search'], FILTER_SANITIZE_STRING);

                                            ?>
                                            <option value="<?php if ($datatype_search == 'INDIVIDUAL') { echo "INDIVIDUAL"; }
                                            if ($datatype_search == 'COMPANY') { echo "COMPANY"; }
                                            ?>"><?php if ($datatype_search == 'INDIVIDUAL') { echo "INDIVIDUAL"; }
                                                if ($datatype_search == 'COMPANY') { echo "COMPANY"; }
                                                ?>
                                            </option>
                                        <?php } ?>
                                        <option value="ALL">ALL</option>
                                        <option value="INDIVIDUAL">INDIVIDUAL</option>
                                        <option value="COMPANY">COMPANY</option>
                                    </select>
                                    <input type="hidden" name="MM_insert" value="formd">
                                </form>
                            </li>

                            <?php
                            $query_getpackagenames = sprintf("SELECT package_id, package_name, client_id FROM pel_client_package where client_id = %s ", GetSQLValueString($client_id_get_psmt_requests, "int"));
                            $getpackagenames = mysqli_query($connect,$query_getpackagenames) or die(mysqli_error());
                            $row_getpackagenames = mysqli_fetch_assoc($getpackagenames);
                            $totalRows_getpackagenames = mysqli_num_rows($getpackagenames);
                            $queryitem = "(";
                            if($totalRows_getpackagenames > '0') {
                                do {
                                    $queryitem .= "'".$row_getpackagenames['package_name']."',";
                                } while ($row_getpackagenames = mysqli_fetch_assoc($getpackagenames));
                            } else {
                                $query_getpackagegeneral = sprintf("SELECT package_id, package_name FROM pel_package where package_general = '11'");
                                $getpackagegeneral = mysqli_query($connect,$query_getpackagegeneral) or die(mysqli_error());
                                $row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral);
                                $totalRows_getpackagegeneral = mysqli_num_rows($getpackagegeneral);

                                $queryitem .= "";
                                do {
                                    $queryitem .= "'".$row_getpackagegeneral['package_name']."',";
                                } while ($row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral));
                            }
                            $queryitem .= "'')";
                            ?>
                        </ul>
                    </div>
                    <!-- /container -->
                </div>
                <!-- /filters -->

                <div class="container margin_60_35">
                    <div class="row">
                        <?php
                        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formsearch")) {
                            if($_POST['searchparameter'] != "") {
                                $searchparameter = filter_var($_POST['searchparameter'], FILTER_SANITIZE_STRING);
                                $maxRows_get_psmt_requests = 1000;
                                $pageNum_get_psmt_requests = 0;
                                if (isset($_GET['pageNum_get_psmt_requests'])) {
                                    $pageNum_get_psmt_requests = filter_var($_GET['pageNum_get_psmt_requests'], FILTER_SANITIZE_STRING);
                                }
                                $startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;
                                //$query_get_psmt_requests = "SELECT * FROM pel_psmt_request WHERE client_id  = '$client_id_get_psmt_requests' and client_login_id = '$client_login_id_get_psmt_requests' and (bg_dataset_name LIKE '%$searchparameter%' OR request_ref_number LIKE '$searchparameter') ORDER BY request_date DESC";

                                $query_get_psmt_requests = "SELECT * FROM pel_psmt_request WHERE client_login_id = '$client_login_id_get_psmt_requests' and (bg_dataset_name LIKE '%$searchparameter%' OR request_ref_number LIKE '$searchparameter')   AND request_plan IN ".$queryitem."   ORDER BY request_date DESC";
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
                                        if (stristr($param, "pageNum_get_psmt_requests") == false && stristr($param, "totalRows_get_psmt_requests") == false) {
                                            array_push($newParams, $param);
                                        }
                                    }
                                    if (count($newParams) != 0) {
                                        $queryString_get_psmt_requests = "&" . htmlentities(implode("&", $newParams));
                                    }
                                }
                                $queryString_get_psmt_requests = sprintf("&totalRows_get_psmt_requests=%d%s", $totalRows_get_psmt_requests, $queryString_get_psmt_requests);

                                if($totalRows_get_psmt_requests > '0'){
                                    do { ?>
                                        <div class="col-lg-6">
                                            <div class="strip_list wow fadeIn">
                                                <?php
                                                if($row_get_psmt_requests['dataset_photo'] == '') {
                                                    ?>
                                                    <figure><a href="#"><img src="../img/nophoto.png" alt=""></a></figure>
                                                <?php } else { ?>
                                                    <figure><a href="#"><img src="img/clientphotos/<?php echo $row_get_psmt_requests['dataset_photo']; ?>" alt=""  class="img-fluid"></a></figure>
                                                <?php } ?>

                                                <h3><?php echo $row_get_psmt_requests['bg_dataset_name']; ?></h3>
                                                <table width="100%" border="0" cellspacing="0">
                                                    <tr>
                                                        <td width="35%"><b>Ref Number:</b></td>
                                                        <td><?php echo $row_get_psmt_requests['request_ref_number']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Package:</b></td>
                                                        <td><?php echo $row_get_psmt_requests['request_plan']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Request Date:</b></td>
                                                        <td><?php echo $row_get_psmt_requests['request_date']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Dataset Name:</b></td>
                                                        <td><?php echo $row_get_psmt_requests['dataset_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Status:</b> </td>
                                                        <td> <?php
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
                                                                <a href="#" class="btn_1 small_status_55"><span id="mybuttontext">Awaiting Quotation</span></a>
                                                                <?php
                                                            }
                                                            if($row_get_psmt_requests['status']=='66')
                                                            {
                                                                ?>
                                                                <a href="#" class="btn_1 small_status_66"><span id="mybuttontext">Awaiting Payment</span></a>
                                                                <?php
                                                            }

                                                            ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Progress:</b></td>
                                                        <td>
                                                            <b><span class="rating">
                                                        <?php
                                                        $refnumber = $row_get_psmt_requests['request_ref_number'];
                                                        $query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
                                                        $getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
                                                        $row_getprogress2 = mysqli_fetch_assoc($getprogress2);
                                                        $totalRows_getprogress2 = mysqli_num_rows($getprogress2);
                                                        $complete=0;
                                                        $all=0;
                                                        do
                                                        {
                                                            if($row_getprogress2['statuscheck']=='11') {
                                                                $complete++;
                                                                ?>
                                                                <i class="icon_star voted"></i>
                                                                <?php
                                                            }
                                                            if($row_getprogress2['statuscheck']=='00') {
                                                                ?>
                                                                <i class="icon_star"></i>
                                                                <?php
                                                            }
                                                            $all++;
                                                        } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));

                                                        ?>
                                                        <small>(<?php echo round(($complete/$all)*100); ?>%)</small>
                                                    </span></b>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <ul>
                                                    <li></li>
                                                    <li></li>
                                                    <li><a href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php
                                    } while ($row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests)); ?>

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
                                } else { ?>
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-8">
                                                <div id="confirm">
                                                    <div class="icon icon--order-success svg add_bottom_15">
                                                        <img src="../img/warningsign.png" alt="noresultssign">
                                                    </div>
                                                    <h2>Their is no requests that are under the Search Parameter!</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /container -->
                                    <?php
                                }  mysqli_free_result($get_psmt_requests);
                            }
                        }
                        else if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formc")){
                            $status_search =	filter_var($_POST['status_search'], FILTER_SANITIZE_STRING);
                            $maxRows_get_psmt_requests = 1000;
                            $pageNum_get_psmt_requests = 0;
                            if (isset($_GET['pageNum_get_psmt_requests'])) {
                                $pageNum_get_psmt_requests = $_GET['pageNum_get_psmt_requests'];
                            }
                            $startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;
                            $query_get_psmt_requests = sprintf("SELECT * FROM pel_psmt_request WHERE client_login_id = %s and status= %s  AND request_plan IN ".$queryitem."  ORDER BY request_date DESC", GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_search, "text"));
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
                            if($totalRows_get_psmt_requests > '0') {
                                do { ?>
                                    <div class="col-lg-6">
                                        <div class="strip_list wow fadeIn">
                                            <?php if($row_get_psmt_requests['dataset_photo'] == '') { ?>
                                                <figure><a href="#"><img src="../img/nophoto.png" alt=""></a></figure>
                                            <?php } else { ?>
                                                <figure><a href="#"><img src="img/clientphotos/<?php echo $row_get_psmt_requests['dataset_photo']; ?>" alt=""  class="img-fluid"></a></figure>
                                            <?php } ?>
                                            <h3><?php echo $row_get_psmt_requests['bg_dataset_name']; ?></h3>

                                            <table width="100%" border="0" cellspacing="0">
                                                <tr>
                                                    <td width="35%"><b>Ref Number:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_ref_number']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Package:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_plan']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Request Date:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_date']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Dataset Name:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['dataset_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Status:</b> </td>
                                                    <td> <?php
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
                                                            <a href="#" class="btn_1 small_status_55"><span id="mybuttontext">Awaiting Quotation</span></a>
                                                            <?php
                                                        }
                                                        if($row_get_psmt_requests['status']=='66')
                                                        {
                                                            ?>
                                                            <a href="#" class="btn_1 small_status_66"><span id="mybuttontext">Awaiting Payment</span></a>
                                                            <?php
                                                        }

                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Progress:</b></td>
                                                    <td><b><span class="rating">
                                                   <?php
                                                   $refnumber = $row_get_psmt_requests['request_ref_number'];
                                                   $query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
                                                   $getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
                                                   $row_getprogress2 = mysqli_fetch_assoc($getprogress2);
                                                   $totalRows_getprogress2 = mysqli_num_rows($getprogress2);
                                                   $complete=0;
                                                   $all=0;
                                                   do {
                                                       if($row_getprogress2['statuscheck']=='11') {
                                                           $complete++;
                                                           ?>
                                                           <i class="icon_star voted"></i>
                                                           <?php
                                                       }
                                                       if($row_getprogress2['statuscheck']=='00') {
                                                           ?>
                                                           <i class="icon_star"></i>
                                                       <?php }
                                                       $all++;
                                                   } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));
                                                   ?>
                                                   <small>(<?php echo round(($complete/$all)*100); ?>%)</small>
                                               </span>
                                                        </b>
                                                    </td>
                                                </tr>
                                            </table>

                                            <ul>
                                                <li></li>
                                                <li></li>
                                                <li><a href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } while ($row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests)); ?>
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
                            <?php } else { ?>
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div id="confirm">
                                                <div class="icon icon--order-success svg add_bottom_15">
                                                    <img src="../img/warningsign.png" alt="noresultssign">
                                                </div>
                                                <h2>Their is no requests that are under the chosen Request Status!</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /row -->
                                </div>
                                <!-- /container -->
                                <?php
                            }
                            mysqli_free_result($get_psmt_requests);
                        }
                        else if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formd")){
                            $datatype_search = filter_var($_POST['datatype_search'], FILTER_SANITIZE_STRING);
                            $maxRows_get_psmt_requests = 1000;
                            $pageNum_get_psmt_requests = 0;
                            if (isset($_GET['pageNum_get_psmt_requests'])) {
                                $pageNum_get_psmt_requests = $_GET['pageNum_get_psmt_requests'];
                            }
                            $startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;
                            $query_get_psmt_requests = sprintf("SELECT * FROM pel_psmt_request WHERE client_login_id = %s and request_type = %s  AND request_plan IN ".$queryitem."  ORDER BY request_date DESC", GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($datatype_search, "text"));
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
                            if($totalRows_get_psmt_requests > '0') {
                                do { ?>

                                    <div class="col-lg-6">
                                        <div class="strip_list wow fadeIn">
                                            <?php
                                            if($row_get_psmt_requests['dataset_photo'] == '') {
                                                ?>
                                                <figure><a href="#"><img src="../img/nophoto.png" alt=""></a></figure>
                                                <?php
                                            } else {
                                                ?>
                                                <figure><a href="#"><img src="img/clientphotos/<?php echo $row_get_psmt_requests['dataset_photo']; ?>" alt=""  class="img-fluid"></a></figure>
                                                <?php
                                            }
                                            ?>

                                            <h3><?php echo $row_get_psmt_requests['bg_dataset_name']; ?></h3>

                                            <table width="100%" border="0" cellspacing="0">
                                                <tr>
                                                    <td width="35%"><b>Ref Number:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_ref_number']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Package:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_plan']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Request Date:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_date']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Dataset Name:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['dataset_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Status:</b> </td>
                                                    <td> <?php
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
                                                            <a href="#" class="btn_1 small_status_55"><span id="mybuttontext">Awaiting Quotation</span></a>
                                                            <?php
                                                        }
                                                        if($row_get_psmt_requests['status']=='66')
                                                        {
                                                            ?>
                                                            <a href="#" class="btn_1 small_status_66"><span id="mybuttontext">Awaiting Payment</span></a>
                                                            <?php
                                                        }

                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Progress:</b></td>
                                                    <td><b><span class="rating">
                                                    <?php
                                                    $refnumber = $row_get_psmt_requests['request_ref_number'];
                                                    $query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
                                                    $getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
                                                    $row_getprogress2 = mysqli_fetch_assoc($getprogress2);
                                                    $totalRows_getprogress2 = mysqli_num_rows($getprogress2);
                                                    $complete=0;
                                                    $all=0;

                                                    do {
                                                        if($row_getprogress2['statuscheck']=='11') {
                                                            $complete++;
                                                            ?>
                                                            <i class="icon_star voted"></i>
                                                            <?php
                                                        }
                                                        if($row_getprogress2['statuscheck']=='00') {
                                                            ?>
                                                            <i class="icon_star"></i>
                                                            <?php
                                                        }
                                                        $all++;
                                                    } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));
                                                    ?>

                                                    <small>(<?php echo round(($complete/$all)*100); ?>%)</small>
                                                </span></b>
                                                    </td>
                                                </tr>
                                            </table>
                                            <ul>
                                                <li></li>
                                                <li></li>
                                                <li><a href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></li>
                                            </ul>
                                        </div>
                                        <!-- /strip_list -->
                                    </div>
                                    <?php
                                } while ($row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests)); ?>
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
                            } else {
                                ?>
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div id="confirm">
                                                <div class="icon icon--order-success svg add_bottom_15">
                                                    <img src="../img/warningsign.png" alt="noresultssign">
                                                </div>
                                                <h2>Their is no requests that are under the chosen Dataset Type!</h2>
                                                <!--<p>You'll receive a confirmation email at mail@example.com</p>-->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /row -->
                                </div>
                                <!-- /container -->
                                <?php
                            }
                            mysqli_free_result($get_psmt_requests);
                        }
                        else  {
                            if ((isset($_GET["status_search"]))) {
                                $status_search = $_GET['status_search'];
                            } else {
                                $status_search = '00';
                            }

                            $maxRows_get_psmt_requests = 1000;
                            $pageNum_get_psmt_requests = 0;

                            if (isset($_GET['pageNum_get_psmt_requests'])) {
                                $pageNum_get_psmt_requests = $_GET['pageNum_get_psmt_requests'];
                            }
                            $startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;
                            $query_get_psmt_requests = sprintf("SELECT * FROM pel_psmt_request WHERE client_login_id = %s and status= %s  AND request_plan IN ".$queryitem."  ORDER BY request_date DESC", GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_search, "text"));
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

                            if($totalRows_get_psmt_requests > '0') {
                                do { ?>

                                    <div class="col-lg-6">
                                        <div class="strip_list wow fadeIn">
                                            <?php
                                            if($row_get_psmt_requests['dataset_photo'] == '') {
                                                ?>
                                                <figure><a href="#"><img src="../img/nophoto.png" alt=""></a></figure>
                                                <?php
                                            } else {
                                                ?>
                                                <figure><a href="#"><img src="img/clientphotos/<?php echo $row_get_psmt_requests['dataset_photo']; ?>" alt=""  class="img-fluid"></a></figure>
                                                <?php
                                            }
                                            ?>

                                            <h3><?php echo $row_get_psmt_requests['bg_dataset_name']; ?></h3>
                                            <table width="100%" border="0" cellspacing="0">
                                                <tr>
                                                    <td width="35%"><b>Ref Number:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_ref_number']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Package:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_plan']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Request Date:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['request_date']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Dataset Name:</b></td>
                                                    <td><?php echo $row_get_psmt_requests['dataset_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Status:</b> </td>
                                                    <td> <?php
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
                                                            <a href="#" class="btn_1 small_status_55"><span id="mybuttontext">Awaiting Quotation</span></a>
                                                            <?php
                                                        }
                                                        if($row_get_psmt_requests['status']=='66')
                                                        {
                                                            ?>
                                                            <a href="#" class="btn_1 small_status_66"><span id="mybuttontext">Awaiting Payment</span></a>
                                                            <?php
                                                        }

                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Progress:</b></td>
                                                    <td><b><span class="rating">
                                                   <?php
                                                   $refnumber = $row_get_psmt_requests['request_ref_number'];
                                                   $query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
                                                   $getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
                                                   $row_getprogress2 = mysqli_fetch_assoc($getprogress2);
                                                   $totalRows_getprogress2 = mysqli_num_rows($getprogress2);
                                                   $complete=0;
                                                   $all=0;
                                                   do {
                                                       if($row_getprogress2['statuscheck']=='11') {
                                                           $complete++;
                                                           ?>
                                                           <i class="icon_star voted"></i>
                                                           <?php
                                                       }
                                                       if($row_getprogress2['statuscheck']=='00') {
                                                           ?>
                                                           <i class="icon_star"></i>
                                                           <?php
                                                       }
                                                       $all++;
                                                   } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));
                                                   ?>
                                                   <small>(<?php echo round(($complete/$all)*100); ?>%)</small></span></b>
                                                    </td>
                                                </tr>
                                            </table>
                                            <ul>
                                                <li></li>
                                                <li></li>
                                                <li><a href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></li>
                                            </ul>
                                        </div>
                                        <!-- /strip_list -->
                                    </div>
                                <?php } while ($row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests)); ?>

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
                            } else {
                                ?>
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div id="confirm">
                                                <div class="icon icon--order-success svg add_bottom_15">
                                                    <img src="../img/warningsign.png" alt="noresultssign">
                                                </div>
                                                <h2>Their is no requests that are under the chosen Request Status!</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /row -->
                                </div>
                                <!-- /container -->
                                <?php
                            }
                            mysqli_free_result($get_psmt_requests);
                        }

                        ?>
                    </div>
                    <!-- /row -->
                </div>

                <!--Footer-->
                <?php include '../partials/footer.php'; ?>
            </div>
            <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        </div>
    </div>
    <script type="text/javascript" src="../assets/scripts/main.js"></script>

    <!-- COMMON SCRIPTS -->
    <script src="../js/jquery-2.2.4.min.js"></script>
    <script src="../js/common_scripts.min.js"></script>
    <script src="../js/functions.js"></script>

    <!-- SPECIFIC SCRIPTS -->
    <script src="../js/map_listing.js"></script>
    <script src="../js/infobox.js"></script>
    </body>
    </html>
<?php
mysqli_free_result($getpackagenames);



?>