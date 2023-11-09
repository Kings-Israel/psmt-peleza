<?php require_once('Connections/process.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
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
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
        $MM_referrer .= "?" . $QUERY_STRING;
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(dbconnect(), $theValue) : mysqli_escape_string(dbconnect(), $theValue);

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
$colname_getrequestdetails = "-1";
if (isset($_GET['requestid'])) {
    $colname_getrequestdetails = $_GET['requestid'];
}

$query_getrequestdetails = sprintf("SELECT * FROM pel_psmt_request WHERE request_id = %s", GetSQLValueString($colname_getrequestdetails, "int"));
$getrequestdetails = mysqli_query($connect, $query_getrequestdetails) or die(mysqli_error());
$row_getrequestdetails = mysqli_fetch_assoc($getrequestdetails);
$totalRows_getrequestdetails = mysqli_num_rows($getrequestdetails);

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
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114"
          href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144"
          href="img/apple-touch-icon-144x144-precomposed.png">

    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
            integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
            crossorigin="anonymous"></script>

    <!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">-->

    <!--Peleza-->
    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <link href="css/vendors.css" rel="stylesheet">
    <link href="css/icon_fonts/css/all_icons_min.css" rel="stylesheet">

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="v1/css/style.css?=<?= rand(0,999999) ?>?<?= rand(0, 1000) ?>">
    <link rel="stylesheet" media="print" href="v1/css/print.css?<?= rand(0, 1000) ?>">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css?<?= rand(0, 1000) ?>" rel="stylesheet">
    <style>
        .fa-stack {
            position: relative;
            /* Adjust these values accordingly */
            bottom: 4px;
            padding: 10px;
        }
    </style>

    <!-- SPECIFIC CSS -->
    <link href="css/date_picker.css" rel="stylesheet">

</head>
<body>

<span id="request-id" style="display: none;"><?= $colname_getrequestdetails ?></span>
<span id="client-id" style="display: none;"><?= $client_id_get_psmt_requests ?></span>

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

                        <div class="tabs_styled_2">
                            <ul class="nav nav-tabs" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link active" id="book-tab" data-toggle="tab" href="#book" role="tab"
                                       aria-controls="book">BG Screening Report</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="general-tab" data-toggle="tab" href="#general" role="tab"
                                       aria-controls="general" aria-expanded="true">Request Details</a>
                                </li>

<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab"-->
<!--                                       aria-controls="reviews">Notes</a>-->
<!--                                </li>-->

                            </ul>
                            <!--/nav-tabs -->

                            <div class="tab-content">

                                <div class="tab-pane fade  show active" id="book" role="tabpanel"
                                     aria-labelledby="book-tab">

                                    <div id="main">

                                        <!-- Page 1 -->
                                        <div id="cover" v-if="isset(report.pel_individual_id)" class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="row">
                                                <div class="col-sm-12" style="padding-top: 10px">
                                                    <img src="img/report-header.png" width="100%">
                                                </div>
                                            </div>

                                            <div class="peleza">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="main">
                                                            <span class="main1">Candidate's Name</span>
                                                            <span class="main2"
                                                                  v-text="report.pel_psmt_request.bg_dataset_name"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">SOW NO.</span>
                                                            <span class="main2" v-text="request_id"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">Screening Package</span>
                                                            <span class="main2"
                                                                  v-text="report.pel_psmt_request.request_plan"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="main">
                                                            <span class="main1">Report Status</span>
                                                            <span class="main2"
                                                                  v-text="getStatus(report.pel_psmt_request.status)"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">Position Hired</span>
                                                            <span class="main2"
                                                                  v-text="report.pel_psmt_request.company_name"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">Reference NO.</span>
                                                            <span class="main2"
                                                                  v-text="report.pel_psmt_request.request_ref_number"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="center " style="max-width: 440px;text-align: inherit;">
                                                        <div class="row no-gutters">

                                                            <div class="col-md-5  secondary">
                                                                <div class="card-body ">
                                                                    <p class="card-text "><span
                                                                                v-text="report.pel_psmt_request.bg_dataset_name"></span>'S
                                                                        PICTURE</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-7">
                                                                <img src="img/nophoto.png"
                                                                     style="border: 5px solid #b2c3cb;" alt="..."
                                                                     height="200px">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="center">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col">DESCRIPTION</th>
                                                            <th scope="col">DETAILS VERIFIED</th>
                                                            <th scope="col">MATCH</th>
                                                            <th scope="col">NO MATCH</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td scope="row" class="primary">Names</td>
<!--                                                            <td v-text="report.pel_psmt_request.bg_dataset_name"></td>-->
                                                            <td>
                                                                <span v-text="id.identity_name"></span>
                                                            </td>

                                                            <td>
                                                                <span v-if="id.match_status === 'MATCH'">
<!--                                                                    <i class="material-icons">check</i>-->
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span v-if="id.match_status === 'DOESNT MATCH'">
<!--                                                                    <i class="material-icons">check</i>-->
                                                                </span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td scope="row" class="primary">ID No.</td>
<!--                                                            <td v-text="report.pel_psmt_request.bg_dataset_idnumber"></td>-->
                                                            <td v-text="id.identity_number"></td>

                                                            <td>
                                                                <span v-if="id.identity_number === report.pel_psmt_request.bg_dataset_idnumber">
<!--                                                                    <i class="material-icons">check</i>-->
                                                                </span>
                                                            </td>

                                                            <td>
                                                                <span v-if="id.identity_number !== report.pel_psmt_request.bg_dataset_idnumber">
<!--                                                                    <i class="material-icons">check</i>-->
                                                                </span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td scope="row" class="primary">Date of Birth</td>
<!--                                                            <td></td>-->
                                                            <td>
                                                                <span v-text="id.date_of_birth"></span>
                                                            </td>
                                                            <td>
<!--                                                                <i class="material-icons">check</i>-->
                                                            </td>
                                                            <td></td>

                                                        </tr>
                                                        <tr>
                                                            <td scope="row" class="primary">Gender</td>
<!--                                                            <td>Male</td>-->
                                                            <td>
                                                                <span v-text="id.gender"></span>
                                                            </td>
                                                            <td>
<!--                                                                <i class="material-icons">check</i>-->
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="row" v-if="id.data_notes">
                                                    <div class="center">
                                                        <br>
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="id.data_notes">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="id.data_notes"></p>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 1a -->
                                        <div id="cover" v-if="isset(report.pel_company_registration)" class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="row">
                                                <div class="col-sm-12" style="padding-top: 10px">
                                                    <img src="https://psmt.pidva.africa/img/report-header.png" width="100%">
                                                </div>
                                            </div>

                                            <div class="peleza">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="main">
                                                            <span class="main1">Clients's Name</span>
                                                            <span class="main2"
                                                                  v-text="report.pel_psmt_request.bg_dataset_name"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">Company’s Name</span>
                                                            <span class="main2" v-text="report.pel_company_registration.company.company_name"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">Screening Package</span>
                                                            <span class="main2">COMPANY DUE DILIGENCE</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="main">
                                                            <span class="main1">Report Status</span>
                                                            <span class="main2"
                                                                  v-text="getStatus(report.pel_psmt_request.status)"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">Report Dated</span>
                                                            <span class="main2"
                                                                  v-text="report.pel_company_registration.company.verified_date"></span>
                                                        </div>
                                                        <div class="main">
                                                            <span class="main1">Reference NO.</span>
                                                            <span class="main2"
                                                                  v-text="report.pel_psmt_request.request_ref_number"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="center">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th colspan="2" scope="col"><span v-text="report.pel_company_registration.type"></span> REGISTRATION DETAILS</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr class="d-flex">
                                                            <td scope="row" class="primary col-4"><span v-text="report.pel_company_registration.type"></span> NAME</td>
                                                            <td class=" col-8">
                                                                <span v-text="report.pel_company_registration.company.company_name"></span>
                                                            </td>
                                                        </tr>
                                                        <tr class="d-flex">
                                                            <td scope="row" class="primary col-4"><span v-text="report.pel_company_registration.type"></span> Registration Number</td>
                                                            <td class=" col-8">
                                                                <span v-text="report.pel_company_registration.company.registration_number"></span>
                                                            </td>
                                                        </tr>
                                                        <tr class="d-flex">
                                                            <td scope="row" class="primary col-4">Date of Incorporation</td>
                                                            <td class=" col-8">
                                                                <span v-text="report.pel_company_registration.company.registration_date"></span>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="center" style="margin-top: 10px">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th colspan="2" scope="col">REGISTERED OFFICE ADDRESSES</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr class="d-flex">
                                                            <td scope="row" class="primary col-4">Registered Office Location</td>
                                                            <td class=" col-8">
                                                                <span v-text="report.pel_company_registration.company.office"></span>
                                                            </td>
                                                        </tr>
                                                        <tr class="d-flex">
                                                            <td scope="row" class="primary col-4">Registered Address</td>
                                                            <td class=" col-8">
                                                                <span v-text="report.pel_company_registration.company.address"></span>
                                                            </td>
                                                        </tr>
                                                        <tr class="d-flex">
                                                            <td scope="row" class="primary col-4">Registered Telephone & Email</td>
                                                            <td class=" col-8">
                                                                <span v-text="report.pel_company_registration.company.email_address"></span>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div v-if="report.pel_company_registration.shareholding.length > 0" class="center" style="margin-top: 10px">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th colspan="6" scope="col">SHAREHOLDING AND DIRECTORSHIP DETAILS</th>
                                                        </tr>
                                                        <tr class="dark-header">
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Description</th>
                                                            <th scope="col">Citizenship</th>
                                                            <th scope="col">Shares</th>
                                                            <th scope="col">Shares Percentage</th>
                                                            <th scope="col">Share Value</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="shares in report.pel_company_registration.shareholding">
                                                            <td scope="row" v-text="shares.name"></td>
                                                            <td scope="row" v-text="shares.description"></td>
                                                            <td scope="row" v-text="shares.citizenship"></td>
                                                            <td scope="row" v-text="shares.shares"></td>
                                                            <td scope="row">
                                                                <span v-text="shares.share_percentage"></span>%
                                                            </td>
                                                            <td scope="row">
                                                                KES <span v-text="shares.share_value"></span>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div  v-if="report.pel_company_registration.encumbrances.length > 0" class="center" style="margin-top: 10px">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th colspan="3" scope="col">ENCUMBRANCES</th>
                                                        </tr>
                                                        <tr class="dark-header">
                                                            <th scope="col">Description</th>
                                                            <th scope="col">Date of Instrument</th>
                                                            <th scope="col">Amount Secured</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="shares in report.pel_company_registration.encumbrances">
                                                            <td scope="row" v-text="shares.description"></td>
                                                            <td scope="row" v-text="shares.date"></td>
                                                            <td scope="row">
                                                                <div class="row">
                                                                    <div v-for="a in shares.amount" class="col-md-3">
                                                                        <span v-text="a.currency"></span> <span v-text="a.amount"></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div v-if="report.pel_company_registration.business_ownership.length > 0" class="center" style="margin-top: 10px">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th colspan="6" scope="col">OWNERSHIP DETAILS</th>
                                                        </tr>
                                                        <tr class="dark-header">
                                                            <th scope="col">Name</th>
                                                            <th scope="col">ID Number</th>
                                                            <th scope="col">Citizenship</th>
                                                            <th scope="col">Type</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="shares in report.pel_company_registration.business_ownership">
                                                            <td scope="row" v-text="shares.name"></td>
                                                            <td scope="row" v-text="shares.idnumber"></td>
                                                            <td scope="row" v-text="shares.citizenship"></td>
                                                            <td scope="row" v-text="shares.description"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="row" v-if="report.pel_company_registration.company.data_notes">
                                                    <div class="center">
                                                        <br>
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="report.pel_company_registration.company.data_notes">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="report.pel_company_registration.company.data_notes"></p>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 2 -->
                                        <div id="pel_individual_fprint_data" v-if="isset(report.pel_individual_fprint_data)" class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="peleza">
                                                <div v-for="rp in report.pel_individual_fprint_data">
                                                    <div class="center">
                                                        <p class="dark-text">FINGERPRINT ANALYSIS</p>
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                            <tr class="dark-header">
                                                                <th scope="col" style="width: 17%">NAME</th>
                                                                <th scope="col" style="width: 32%">ID IMAGE OF
                                                                    FINGERPRINT
                                                                </th>
                                                                <th scope="col">ID IMAGE OF FINGERPRINT TAKEN</th>
                                                                <th scope="col" style="width: 13%">MATCH</th>
                                                                <th scope="col" style="width: 13%">NO MATCH</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr style="width: 120px; height: 150px;">
                                                                <th scope="row" class="primary align-middle">
                                                                    <span v-text="rp.first_name"></span>
                                                                </th>
                                                                <td>
                                                                    <img v-bind:src="rp.finger_print_pel"
                                                                         height="200px">
                                                                </td>
                                                                <td>
                                                                    <img v-bind:src="rp.finger_print_src"
                                                                         height="200px">
                                                                </td>
                                                                <td class="align-middle">
                            <span v-if="rp.match_status == 'MATCH'">
                                <i class="material-icons">check</i>
                            </span>
                                                                </td>
                                                                <td>
                            <span v-if="rp.match_status !== 'MATCH'">
                                <i class="material-icons">check</i>
                            </span>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="row">
                                                        <div class="center">
                                                            <p class="dark-text">COMMENTS</p>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="box">
                                                            <p class="clearfix remove-font"
                                                               v-html="rp.data_notes"></p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>

                                        <!-- Page 3 -->
                                        <div id="pel_individual_dl_data" v-if="isset(report.pel_individual_dl_data)" class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="peleza">

                                                <div v-for="r in report.pel_individual_dl_data">

                                                    <div class="center">
                                                        <p class="dark-text">DRIVING LICENCE</p>
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                            <tr class="dark-header">
                                                                <th scope="col">NAME</th>
                                                                <th scope="col">IDENTITY NUMBER</th>
                                                                <th scope="col">EXPIRY DATE</th>
                                                                <th scope="col">CLASS</th>
                                                                <th scope="col">LICENCE NUMBER</th>
                                                                <th scope="col">STATUS</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>

                                                                <th scope="row" class="primary">
                                                                    <span v-text="r.first_name"></span>
                                                                    <span v-text="r.second_name"></span>
                                                                    <span v-text="r.third_name"></span>
                                                                </th>

                                                                <td>
                                                                    <span v-text="r.identity_number"></span>
                                                                </td>

                                                                <td>
                                                                    <span v-text="r.expiry_date"></span>
                                                                </td>

                                                                <td>
                                                                    <span v-text="r.class"></span>
                                                                </td>

                                                                <td>
                                                                    <span v-text="r.license_number"></span>
                                                                </td>

                                                                <td class="secondaryLight">
                                                                    <span v-text="r.dl_status"></span>
                                                                </td>

                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="row">
                                                        <div class="center">
                                                            <p class="dark-text">COMMENTS</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="box">
                                                            <p class="clearfix remove-font"
                                                               v-html="r.data_notes"></p>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 4 -->
                                        <div v-if="isset(report.pel_psmt_edu_data) " class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px "
                                             v-for="r in report.pel_psmt_edu_data"
                                             v-bind:id="getUniqueID('pel_psmt_edu_data')"
                                             v-show="parseInt(r.verification_status) === 1 ">

                                            <div class="peleza">

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">EDUCATION</p>
                                                    </div>
                                                </div>

                                                <div class="center">

                                                    <table class="table table-bordered table-sm">

                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col" style="width: 17%"colspan="2">DESCRIPTION</th>
                                                            <th scope="col" style="width: 22%">DETAILS PROVIDED</th>
                                                            <th scope="col" style="width: 22%">DETAILS VERIFIED</th>
                                                            <th scope="col" style="width: 16%">MATCH</th>
                                                            <th scope="col" style="width: 16%">NO MATCH</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>

                                                        <tr>
                                                            <th scope="row" class="primary align-middle" rowspan="4">
                                                                Highest Education
                                                            </th>
                                                            <th scope="row" class="primary align-middle">
                                                                Institution
                                                            </td>
                                                            <td>
                                                                <span v-text="r.institution_provided"></span>
                                                            </td>
                                                            <td>
                                                                <span v-text="r.edu_institution"></span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_insititution == 'MATCH'">MATCH</span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_insititution == 'DOESNT MATCH'">DOESNT MATCH</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row" class="primary align-middle">
                                                                Years
                                                            </th>
                                                            <td>
                                                                <span v-text="r.year_provided"></span>
                                                            </td>
                                                            <td>
                                                                <span v-text="r.edu_graduation_year"></span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_year == 'MATCH'">MATCH</span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_year == 'DOESNT MATCH'">DOESNT MATCH</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row" class="primary align-middle">
                                                                Course
                                                            </th>
                                                            <td>
                                                                <span v-text="r.course_provided"></span>
                                                            </td>
                                                            <td>
                                                                <span v-text="r.edu_course"></span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_course == 'MATCH'">MATCH</span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_course == 'DOESNT MATCH'">DOESNT MATCH</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row" class="primary align-middle">
                                                                Award
                                                            </th>
                                                            <td>
                                                                <span v-text="r.award_provided"></span>
                                                            </td>
                                                            <td>
                                                                <span v-text="r.edu_award"></span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_award == 'MATCH'">MATCH</span>
                                                            </td>
                                                            <td>
                                                                <span v-show="r.match_status_award == 'DOESNT MATCH'">DOESNT MATCH</span>
                                                            </td>
                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                </div>

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="r.data_notes"></p>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <!-- Page 4a -->
                                        <div v-if="isset(report.pel_psmt_edu_data) " class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px "
                                             v-for="r in report.pel_psmt_edu_data"
                                             v-bind:id="getUniqueID('pel_psmt_edu_negative_data')"
                                             v-show="parseInt(r.verification_status) === -1 ">

                                            <div class="peleza">

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">EDUCATION</p>
                                                    </div>
                                                </div>

                                                <div class="center">

                                                    <table class="table table-bordered table-sm">

                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col" style="width: 20%">DESCRIPTION</th>
                                                            <th scope="col" style="width: 40%" colspan="2">DETAILS PROVIDED</th>
                                                            <th scope="col" style="width: 40%">MATCH STATUS</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>

                                                        <tr>
                                                            <td scope="row" class="primary align-middle" rowspan="4">
                                                                Highest Education
                                                            </td>

                                                            <td class="light">Institution Name</td>

                                                            <td>
                                                                <span v-text="r.institution_provided"></span>
                                                            </td>

                                                            <td rowspan="4" colspan="3" class="secondaryLight">
                                                                <span v-text="r.verification_status_comments"></span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="light">Years</td>
                                                            <td>
                                                                <span v-text="r.year_provided"></span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="light">Course</td>
                                                            <td>
                                                                <span v-text="r.course_provided"></span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="light">Awards</td>
                                                            <td>
                                                                <span v-text="r.award_provided"></span>
                                                            </td>
                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                </div>

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="r.data_notes"></p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Page 5 -->
                                        <div id="pel_data_proff_membership" v-if="isset(report.pel_data_proff_membership) " class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="peleza">

                                                <div class="center">
                                                    <p class="dark-text">PROFESSIONAL QUALIFICATION</p>
                                                </div>

                                                <div v-for="r in report.pel_data_proff_membership">
                                                    <div class="center">
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                            <tr class="dark-header">
                                                                <th scope="col">DESCRIPTION</th>
                                                                <th scope="col">BODY</th>
                                                                <th scope="col">REGISTRATION DATE</th>
                                                                <th scope="col">MEMBERSHIP NUMBER</th>
                                                                <th scope="col">STATUS</th>
                                                                <th scope="col">CERTIFICATE</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>

                                                                <th scope="row" class="primary align-middle">
                                                                    Professional Membership
                                                                </th>

                                                                <td class="light">
                                                                    <span v-text="r.membership_body"></span>
                                                                </td>

                                                                <td>
                                                                    <span v-text="r.registration_date"></span>
                                                                </td>

                                                                <td class="light">
                                                                    <span v-text="r.membership_number"></span>
                                                                </td>

                                                                <td>
                                                                    <span v-text="r.membership_status"></span>
                                                                </td>
                                                                <td>
                                                                    <img v-if="r.membership_certificate"
                                                                         v-bind:src="membership_certificate"
                                                                         height="200px">
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row">
                                                        <div class="center">
                                                            <p class="dark-text">COMMENTS</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="box">
                                                            <p class="clearfix remove-font"
                                                               v-html="r.data_notes"></p>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 6 -->
                                        <div v-if="isset(report.pel_psmt_employ_data)" class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px"
                                             v-for="r in report.pel_psmt_employ_data"
                                             v-bind:id="getUniqueID('pel_psmt_employ_data')" >

                                            <div class="peleza">

                                                <div class="center">
                                                    <p class="dark-text">EMPLOYMENT</p>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col">DESCRIPTION</th>
                                                            <th scope="col" colspan="2">DETAILS PROVIDED</th>
                                                            <th scope="col" colspan="2">DETAILS VERIFIED</th>
                                                            <th scope="col">MATCH</th>
                                                            <th scope="col">NO MATCH</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr>

                                                            <th scope="row" class="primary align-middle" rowspan="4">
                                                                <span v-text="r.verified_organisation"></span>
                                                            </th>

                                                            <td class="light">Organization</td>
                                                            <td>
                                                                <span v-text="r.organisation_provided"></span>
                                                            </td>

                                                            <td class="light">Organization</td>
                                                            <td>
                                                                <span v-text="r.verified_organisation"></span>
                                                            </td>

                                                            <td>
                                                                <i v-if="r.match_status_organisation == 'MATCH' "
                                                                   class="material-icons">check</i>

                                                            </td>
                                                            <td>
                                                                <i v-if="r.match_status_organisation != 'MATCH' "
                                                                   class="material-icons">check</i>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="light">Years</td>
                                                            <td>
                                                                <span v-text="r.period_provided"></span>
                                                            </td>

                                                            <td class="light">Years</td>
                                                            <td>
                                                                <span v-text="r.verified_date"></span>
                                                            </td>

                                                            <td>
                                                                <i v-if="r.match_status_period == 'MATCH' "
                                                                   class="material-icons">check</i>

                                                            </td>
                                                            <td>
                                                                <i v-if="r.match_status_period != 'MATCH' "
                                                                   class="material-icons">check</i>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="light">Position</td>
                                                            <td>
                                                                <span v-text="r.position_provided"></span>
                                                            </td>

                                                            <td class="light">Position</td>
                                                            <td>
                                                                <span v-text="r.verified_position"></span>
                                                            </td>

                                                            <td>
                                                                <i v-if="r.match_status_position == 'MATCH' "
                                                                   class="material-icons">check</i>

                                                            </td>
                                                            <td>
                                                                <i v-if="r.match_status_position != 'MATCH' "
                                                                   class="material-icons">check</i>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="light">Reason for Leaving</td>
                                                            <td>
                                                                <span v-text="r.leaving_reason_provided"></span>
                                                            </td>

                                                            <td class="light">Reason for Leaving</td>
                                                            <td>
                                                                <span v-text="r.verified_leaving_reason"></span>
                                                            </td>

                                                            <td>
                                                                <i v-if="r.match_status_leaving_reason == 'MATCH' "
                                                                   class="material-icons">check</i>

                                                            </td>
                                                            <td>
                                                                <i v-if="r.match_status_leaving_reason != 'MATCH' "
                                                                   class="material-icons">check</i>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="r.data_notes"></p>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 7 -->
                                        <div id="pel_psmt_employ_data_tenure" v-if="isset(report.pel_psmt_employ_data)" class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="peleza">

                                                <div class="center">

                                                    <p class="dark-text">EMPLOYEMENT TENURE</p>

                                                    <table class="table table-sm table-bordered peleza-striped">

                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col" colspan="4">EMPLOYEMENT TENURE</th>
                                                        </tr>
                                                        <tr class="primary">
                                                            <td>Company</td>
                                                            <td>Position</td>
                                                            <td>Period</td>
                                                            <td>Reason for Leaving</td>
                                                        </tr>

                                                        </thead>

                                                        <tbody>
                                                        <tr v-for="r in report.pel_psmt_employ_data">
                                                            <td>
                                                                <span v-text="r.verified_organisation"></span>
                                                            </td>
                                                            <td>
                                                                <span v-text="r.verified_position"></span>
                                                            </td>
                                                            <td>
                                                                <span v-text="r.verified_period"></span>
                                                            </td>
                                                            <td>
                                                                <span v-text="r.verified_leaving_reason"></span>
                                                            </td>
                                                        </tr>
                                                        </tbody>

                                                    </table>

                                                </div>

                                                <!--
                                                <div class="box">
                                                    <div>
                                                        <div class="center">
                                                            <table class="table table-sm table-bordered peleza-striped">
                                                                <thead>
                                                                <tr class="primary">
                                                                    <th scope="col">Trustworthiness</th>
                                                                    <th scope="col">Trustworthy</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>Dependability/ reliability</td>
                                                                    <td>Reliable</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Taking Initiative</td>
                                                                    <td>Takes Initiative</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Communication skills (Verbal/ written)</td>
                                                                    <td>Well-Spoken & written</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Time management</td>
                                                                    <td>High time management culture</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Ability to make sound and timely decisions</td>
                                                                    <td>In Charge even if faced with difficulties</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Adaptability to change</td>
                                                                    <td>Adaptive</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Professionalism</td>
                                                                    <td>She is professional</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                -->

                                            </div>


                                        </div>

                                        <!-- Page 8 -->
                                        <div id="pel_individual_gap_data" v-if="isset(report.pel_individual_gap_data) " class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="peleza">

                                                <div class="center">
                                                    <br>
                                                    <p class="dark-text">GAP IDENTIFICATION AND ANALYSIS</p>
                                                    <table class="table table-sm table-bordered peleza-striped">
                                                        <thead class="darkred" style="color: white">
                                                        <th style="width: 30%">Name</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th style="width: 35%">Comments</th>
                                                        </thead>
                                                        <tbody>

                                                        <tr v-for="r in report.pel_individual_gap_data">
                                                            <td class="">
                                                                <span v-text="r.gap_name"></span>
                                                            </td>
                                                            <td class="">
                                                                <span v-text="r.from_date"></span>
                                                            </td>
                                                            <td class="">
                                                                <span v-text="r.to_date"></span>
                                                            </td>
                                                            <td>
                                                                <span class="remove-font" v-html="r.data_notes"></span>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                        </div>

                                        <!-- Page 9 -->
                                        <div v-if="isset(report.pel_data_residence) "
                                             v-for="r in report.pel_data_residence " class="page a4" size="A4"
                                             v-bind:id="getUniqueID('pel_data_residence')"
                                            style="page-break-after: always; margin-top: 30px ">

                                            <div class="peleza">
                                                <!--table-->
                                                <div class="center">
                                                    <p class="dark-text">RESIDENTIAL CHECK ADDRESS</p>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col">DESCRIPTION</th>
                                                            <th scope="col" style="width: 25%">DETAILS VERIFIED</th>
                                                            <th scope="col">MATCH</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr>
                                                            <td class="primary">Physical Address</td>
                                                            <td>
                                                                <span v-text="r.physical_address"></span>
                                                            </td>
                                                            <td>
                                                                <i class="material-icons">check</i>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="primary">Street/Road</td>
                                                            <td>
                                                                <span v-text="r.street"></span>
                                                            </td>
                                                            <td>
                                                                <i class="material-icons">check</i>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="primary">House</td>
                                                            <td>
                                                                <span v-text="r.house_number"></span>
                                                            </td>
                                                            <td>
                                                                <i class="material-icons">check</i>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--Comments-->
                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="r.data_notes">
                                                        </p>
                                                    </div>
                                                </div>

                                                <br>
                                                <!--Images-->
                                                <div class="row">
                                                    <div class="col-sm-12"><img v-bind:src="r.building_photo" width="100%"></div>
                                                </div>
                                            </div>


                                        </div>

                                        <!--    Page 10-->
                                        <div id="pel_individual_credit_data" v-if="isset(report.pel_individual_credit_data)" class="page a4" size="A4"
                                             style="page-break-after: always; margin-top: 30px ">

                                            <div class="peleza">

                                                <div class="center">
                                                    <br>
                                                    <p class="dark-text">CREDIT INFORMATION CHECK</p>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col" colspan="2">DESCRIPTION</th>
                                                            <th scope="col" colspan="4">COMMENTS</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr>
                                                            <td colspan="2">Credit Report</td>
                                                            <td colspan="4"><span v-text="open"></span> open loan
                                                                accounts <br><span v-text="closed"></span> closed loan
                                                                accounts.
                                                            </td>
                                                        </tr>

                                                        <tr v-show="open > 0 " class="color-1">
                                                            <td colspan="6"><b>Open loan accounts</b></td>
                                                        </tr>
                                                        <tr v-show="open > 0"  class="color-2">
                                                            <td><b><em>Institution</em></b></td>
                                                            <td colspan="2">Type of Loan</td>
                                                            <td><em><b>Total Amount</b></em></td>
                                                            <td><em><b>Balance Amount</b></em></td>
                                                            <td><em><b>Amount & Days</b></em></td>
                                                        </tr>
                                                        <tr v-show="open > 0 " v-for="r in report.pel_individual_credit_data" v-if="r.balance > 0 ">
                                                            <td>
                                                                <span v-text="r.subscriber"></span>
                                                            </td>

                                                            <td colspan="2">
                                                                <span v-text="r.loan_type"></span>
                                                            </td>

                                                            <td>
                                                                KES. <span v-text="financial(r.total_amount)"></span>
                                                            </td>
                                                            <td>
                                                                KES <span v-text="financial(r.balance)"></span>
                                                            </td>
                                                            <td class="secondaryLight">
                                                                KES <span v-text="financial(r.past_due)"></span>
                                                            </td>
                                                        </tr>

                                                        <tr v-show="closed > 0 " class="color-1">
                                                            <td colspan="6"><b>Closed loan accounts</b></td>
                                                        </tr>
                                                        <tr v-show="closed > 0"  class="color-2">
                                                            <td><b><em>Institution</em></b></td>
                                                            <td colspan="2">Type of Loan</td>
                                                            <td><em><b>Total Amount</b></em></td>
                                                            <td><em><b>Balance Amount</b></em></td>
                                                            <td><em><b>Amount & Days</b></em></td>
                                                        </tr>
                                                        <tr v-show="closed > 0 " v-for="r in report.pel_individual_credit_data" v-if="r.balance == 0 ">
                                                            <td>
                                                                <span v-text="r.subscriber"></span>
                                                            </td>

                                                            <td colspan="2">
                                                                <span v-text="r.loan_type"></span>
                                                            </td>

                                                            <td>
                                                                KES. <span v-text="financial(r.total_amount)"></span>
                                                            </td>
                                                            <td>
                                                                KES <span v-text="financial(r.balance)"></span>
                                                            </td>
                                                            <td class="secondaryLight">
                                                                KES <span v-text="financial(r.past_due)"></span>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font"
                                                           v-html="report.pel_credit_data_comments[0].data_notes">
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 11 -->
                                        <!--
                                        <div class="page a4" size="A4">
                                            <div class="peleza">
                                                <div class="center">
                                                    <table class="table table-bordered table-sm">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="3"><b>Total Amount</b></td>
                                                            <td><b>KES 3,033,000</b></td>
                                                            <td>KES 2,004,346</td>
                                                            <td>KES 2,300</td>
                                                        </tr>
                                                        <tr class="color-1">
                                                            <td colspan="6"><b>Closed loan accounts</b></td>
                                                        </tr>
                                                        <tr class="color-2">
                                                            <td><b><em>Institution</em></b></td>
                                                            <td colspan="2">Type of Loan</td>
                                                            <td><em><b>Total Amount</b></em></td>
                                                            <td><em><b>Balance Amount</b></em></td>
                                                            <td><em><b>Closed Status</b></em></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tala</td>
                                                            <td colspan="2">Mobile Banking Loan</td>
                                                            <td>KES 23,000</td>
                                                            <td>KES 20,000</td>
                                                            <td>Written Off</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Branch</td>
                                                            <td colspan="2">Mobile Banking Loan</td>
                                                            <td>KES 57,000</td>
                                                            <td>KES 0</td>
                                                            <td>Paid in full</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><b>Total Amount</b></td>
                                                            <td><b>KES 80,000</b></td>
                                                            <td>KES 0</td>
                                                            <td>KES 0</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6"><b>Closed loan accounts</b></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                        <div class="box">
                                                            <p class="clearfix">John Doe Smith has two mobile banking loan, Mshwari loan with a balance of KES 33,000, which he is currently
                                                                servicing hence the account is still open. He also has an active Personal loan with Kenya Commercial
                                                                Bank balance amounting to KES 2,000,000; this loan is 201 days in arrears.
                                                                The candidate also has 2 closed loan accounts, which are mobile banking loans, Tala and Branch amounting to
                                                                Ksh.80, 000 which he has fully settled (paid); therefore the loan accounts have been closed.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        -->

                                        <!-- page 12 -->
                                        <div v-if="isset(report.pel_individual_criminal_data) "
                                             v-for="r in report.pel_individual_criminal_data"
                                             v-bind:id="getUniqueID('pel_individual_criminal_data')" class="page a4" size="A4">

                                            <div class="peleza">

                                                <div class="center">
                                                    <br>
                                                    <p class="dark-text">NATIONAL CRIMINAL DATABASE SEARCH</p>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col" style="width: 20%">NAME</th>
                                                            <th scope="col">IDENTITY NUMBER</th>
                                                            <th scope="col">POLICE CLEARANCE REFERENCE NUMBER</th>
                                                            <th scope="col" style="width: 25%">STATUS</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td v-text="r.first_name"></td>
                                                            <td v-text="r.identity_number"></td>
                                                            <td v-text="r.clearence_ref_number"></td>
                                                            <td v-text="r.criminal_offence_status"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--Comments-->

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="r.data_notes">
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 14 -->
                                        <div v-if="isset(report.pel_individual_watchlist_data) "
                                             v-for="r in report.pel_individual_watchlist_data"
                                             v-bind:id="getUniqueID('pel_individual_watchlist_data')" class="page a4"
                                             size="A4">

                                            <div class="peleza">

                                                <div class="center">
                                                    <p class="dark-text">GLOBAL WATCHLIST DATABASE SCREENING</p>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col" style="width: 20%">NAME</th>
                                                            <th scope="col" style="width: 15%">DATE OF BIRTH</th>
                                                            <th scope="col" style="width: 20%">FATHER'S NAME</th>
                                                            <th scope="col" style="width: 20%">STATUS</th>
                                                            <th scope="col">COMMENT</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td v-text="r.first_name"></td>
                                                            <td v-text="r.date_of_birth"></td>
                                                            <td v-text="r.father_name"></td>
                                                            <td v-text="r.watchlist_status"></td>
                                                            <td v-text="r.review_notes"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <br>
                                                </div>

                                                <!--Comments-->
                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text">COMMENTS</p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="r.data_notes">
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- Page 15 -->
                                        <div v-if="hasPhoto(report.pel_individual_watchlist_data)"
                                             v-for="r in report.pel_individual_watchlist_data"
                                             v-bind:id="getUniqueID('pel_individual_watchlist_data')" class="page a4"
                                             size="A4">
                                            <div class="peleza">
                                                <!--photo-->
                                                <div class="row" v-if="r.photo">
                                                    <div class="center">
                                                        <p class="dark-text">Search Photo</p>
                                                        <div class="box">
                                                            <img v-bind:src="r.photo" width="100%"/>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Page 16 -->
                                        <div id="pel_data_social_media" v-if="isset(report.pel_data_social_media)" class="page a4" size="A4">

                                            <div class="peleza">
                                                <div class="center">
                                                    <br>
                                                    <p class="dark-text">SOCIAL MEDIA</p>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                        <tr class="dark-header">
                                                            <th scope="col" style="width: 22%">DESCRIPTION</th>
                                                            <th scope="col" style="width: 22%">ADVERSE STATUS</th>
                                                            <th scope="col" style="width: 22%">SOCIAL MEDIA HANDLE</th>
                                                            <th scope="col" style="width: 34%">COMMENTS</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="r in report.pel_data_social_media">
                                                            <td class="primary" v-text="r.website"></td>
                                                            <td v-text="r.adverse_status"></td>
                                                            <td v-text="r.social_media_handle"></td>
                                                            <td v-text="r.review_notes"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Page 16 -->
                                        <div v-if="isset(report.pel_data_social_media)" v-for="r in report.pel_data_social_media" v-bind:id="getUniqueID('pel_data_social_media')" class="page a4" size="A4">

                                            <div class="peleza">

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text"><span v-text="r.website"></span>
                                                            Photographic Evidence</p>
                                                        <div class="">
                                                            <img v-bind:src="r.photo" width="500px"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="center">
                                                        <p class="dark-text"><span v-text="r.website"></span> Comments</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="box">
                                                        <p class="clearfix remove-font" v-html="r.data_notes">
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>


                                        <div class="fixed-action-btn float" style="padding-bottom: 50px;">

                                            <a id="to-pdf" class="btn-large waves-effect waves-light pdf"
                                               @click="printer" style="color:white">

                                                <button class="btn btn-danger" type="button">
                                                    <i class="material-icons">print</i>
                                                    <span v-show="!printing">Print</span>
                                                    <span  v-show="printing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    <span v-show="printing">Printing...</span>
                                                </button>
                                            </a>
                                        </div>

                                    </div>

                                </div>

                                <!-- /tab_1 -->

                                <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">

                                    <div class="card">

                                        <div class="row no-gutters">

                                            <div class="col-auto">

                                                <?php
                                                if ($row_getrequestdetails['dataset_photo'] == ''): ?>

                                                    <img src="img/nophoto.png" alt="" class="img-fluid">

                                                <?php else: ?>

                                                    <img src="img/clientphotos/<?php echo $row_getrequestdetails['dataset_photo']; ?>"
                                                         alt=""
                                                         class="img-fluid">

                                                <?php endif; ?>

                                            </div>

                                            <div class="col">

                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo $row_getrequestdetails['bg_dataset_name']; ?></h5>
                                                </div>

                                                <ul class="list-group list-group-flush">

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Citizenship:</b>
                                                            </div>
                                                            <div class="col-8">
                                                                <span><?php echo $row_getrequestdetails['dataset_citizenship']; ?></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Email Address:</b>
                                                            </div>
                                                            <div class="col-8">
                                                                <span><?php echo $row_getrequestdetails['bg_dataset_email']; ?></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Mobile Number:</b>
                                                            </div>
                                                            <div class="col-8">
                                                                <span><?php echo $row_getrequestdetails['bg_dataset_mobile']; ?></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Reference Number:</b>
                                                            </div>
                                                            <div class="col-8">
                                                                <span><?php echo $row_getrequestdetails['request_ref_number']; ?></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Package:</b>
                                                            </div>
                                                            <div class="col-8">
                                                                <span><?php echo $row_getrequestdetails['request_plan']; ?></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Request Date:</b>
                                                            </div>
                                                            <div class="col-8">
                                                                <span><?php echo $row_getrequestdetails['request_date']; ?></span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Request Status:</b>
                                                            </div>
                                                            <div class="col-8">
                                                                <?php
                                                                if ($row_getrequestdetails['status'] == '00') {
                                                                    ?>
                                                                    <a href="#" class="btn_1 small_status_00"><span
                                                                                id="mybuttontext">New Request</span></a>
                                                                    <?php

                                                                }
                                                                if ($row_getrequestdetails['status'] == '11') {
                                                                    ?>
                                                                    <a href="#" class="btn_1 small_status_11"><span
                                                                                id="mybuttontext">Final Report</span></a>
                                                                    <?php
                                                                }
                                                                if ($row_getrequestdetails['status'] == '33') {
                                                                    ?>
                                                                    <a href="#" class="btn_1 small_status_33"><span
                                                                                id="mybuttontext">Interim</span></a>
                                                                    <?php
                                                                }
                                                                if ($row_getrequestdetails['status'] == '44') {
                                                                    ?>
                                                                    <a href="#" class="btn_1 small_status_44"><span
                                                                                id="mybuttontext">In Progress</span></a>
                                                                    <?php
                                                                }
                                                                if ($row_getrequestdetails['status'] == '55') {
                                                                    ?>
                                                                    <a href="#" class="btn_1 small_status_55"><span
                                                                                id="mybuttontext">Awaiting Quotation</span></a>
                                                                    <?php
                                                                }
                                                                if ($row_getrequestdetails['status'] == '66') {
                                                                    ?>
                                                                    <a href="#" class="btn_1 small_status_66"><span
                                                                                id="mybuttontext">Awaiting Payment</span></a>
                                                                <?php } ?>

                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row no-gutters">
                                                            <div class="col-4">
                                                                <b>Progress:</b>
                                                            </div>
                                                            <div class="col-8">

                                                                <?php

                                                                    $refnumber = $row_getrequestdetails['request_ref_number'];
                                                                    //
                                                                    $query_getprogress2 = sprintf("SELECT status AS statuscheck, module_name FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
                                                                    $getprogress2 = mysqli_query($connect, $query_getprogress2) or die(mysqli_error());
                                                                    $row_getprogress2 = mysqli_fetch_assoc($getprogress2);
                                                                    $totalRows_getprogress2 = mysqli_num_rows($getprogress2);

                                                                    $complete = 0;
                                                                    $all = 0;
                                                                ?>

                                                                <span class="rating">
                                                                        <?php

                                                                        if ($totalRows_getprogress2 > 0) {
                                                                            do {
                                                                                if ($row_getprogress2['statuscheck'] == '11') {
                                                                                    $complete++;
                                                                                    ?><i class="icon_star voted"></i>
                                                                                    <?php
                                                                                } else if ($row_getprogress2['statuscheck'] == '00') {
                                                                                    ?>
                                                                                    <i class="icon_star"></i>
                                                                                    <?php
                                                                                } else {

                                                                                    $complete = 9999;
                                                                                    ?>

                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <?php
                                                                                $all++;
                                                                            } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));


                                                                        }
                                                                        ?>

                          <small><?php
                              if ($complete == 9999 || $complete == 0) {
                                  echo "(100%)";
                              } else {
                                  echo "(" . round(($complete / $all) * 100) . "%)";
                              } ?></small></span>

                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="card">

                                        <div class="card-body">

                                            <div class="indent_title_in card-title">
                                                <i class="pe-7s-safe"></i>
                                                <h3>Background Check Conducted Progress</h3>
                                            </div>

                                        </div>

                                        <?php

                                            $query_getprogress3 = sprintf("SELECT status AS statuscheck, module_name FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
                                            $getprogress3 = mysqli_query($connect, $query_getprogress3) or die(mysqli_error());
                                            $row_getprogress3 = mysqli_fetch_assoc($getprogress3);
                                            $totalRows_getprogress3 = mysqli_num_rows($getprogress3);

                                        ?>

                                        <ul class="list-group list-group-flush">

                                            <?php

                                                if ($totalRows_getprogress3 > 0) {
                                                    do {

                                                ?>
                                                        <li class="list-group-item">
                                                            <div class="row no-gutters">
                                                                <div class="col-4">
                                                                    <b><?php echo ucfirst(strtolower($row_getprogress3['module_name'])); ?></b>
                                                                </div>
                                                                <div class="col-8">
                                                                    <span class="rating">   <?php
                                                                        if ($row_getprogress3['statuscheck'] == '11') {

                                                                            ?><i class="icon_star voted"></i> Complete
                                                                            <?php
                                                                        }
                                                                        if ($row_getprogress3['statuscheck'] == '00') {
                                                                            ?>
                                                                            <i class="icon_star"></i> Incomplete
                                              <?php
                                                                        }

                                                                        ?></span>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <?php

                                                        } while ($row_getprogress3 = mysqli_fetch_assoc($getprogress3));
                                                    }
                                            ?>

                                        </ul>

                                    </div>

                                    <div class="card">

                                        <div class="card-body">

                                            <div class="indent_title_in card-title">
                                                <i class="pe-7s-user"></i>
                                                <h3>Submitted By</h3>
                                            </div>

                                        </div>

                                        <ul class="list-group list-group-flush">

                                            <li class="list-group-item">
                                                <div class="row no-gutters">
                                                    <div class="col-4">
                                                        <b>Staff Name:</b>
                                                    </div>
                                                    <div class="col-8">
                                                        <?php echo $row_getrequestdetails['client_name']; ?>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="list-group-item">
                                                <div class="row no-gutters">
                                                    <div class="col-4">
                                                        <b>Company Name:</b>
                                                    </div>
                                                    <div class="col-8">
                                                        <?php echo $row_getrequestdetails['company_name']; ?>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="list-group-item">
                                                <div class="row no-gutters">
                                                    <div class="col-4">
                                                        <b>Company ID:</b>
                                                    </div>
                                                    <div class="col-8">
                                                        <?php echo $client_login_id_get_psmt_requests; ?>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>

                                    </div>

                                </div>
                                <!-- /tab_2 -->

                                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                    <div class="reviews-container">
                                        COMING SOON

                                    </div>
                                    <!-- End review-container -->
                                </div>
                                <!-- /tab_3 -->
                            </div>
                            <!-- /tab-content -->
                        </div>
                        <!-- /tabs_styled -->
                    </div>
                    <!-- /col -->
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->

        </div>
        <!-- /main -->
    </div>

    <!--Footer-->
    <?php include 'partials/footer.php'; ?>

</div>

</div>
</div>

<!-- Submit form -->
<script src="js/jquery-2.2.4.min.js"></script>
<!--<script src="//cdn.jsdelivr.net/npm/bluebird@3.7.2/js/browser/bluebird.min.js"></script>-->

<script src="v1/js/vue.js" type="text/javascript"></script>
<script src="v1/js/axios.min.js" type="text/javascript"></script>
<script src="js/bootstrap-notify.js" type="text/javascript"></script>
<script src="assets/sweetalert/sweetalert.min.js"></script>
<script src="v1/js/dashboard-stats.js?<?= rand(0, 1000) ?>" type="text/javascript"></script>
<script src="/js/logo.js?<?= rand(0, 1000) ?>" type="text/javascript"></script>
<script type="text/javascript" src="./assets/scripts/main.js"></script>
<script type="text/javascript" src="v1/js/html2canvas1.js"></script>
<script src="v1/js/main.js?<?= rand(0, 1000) ?>" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"
        integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/"
        crossorigin="anonymous"></script>

<!-- COMMON SCRIPTS -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/common_scripts.min.js"></script>
<script src="js/functions.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>

    $(document).ready(function () {

        $('.fixed-action-btn').floatingActionButton();

        $('#to-pdf').click(function () {

            var dt = $('#main').html();

        });
    });

</script>


</body>
</html>