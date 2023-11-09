<?php


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

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
    $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}
$colname_getpackagecost = "-1";

if (isset($_GET['package_id'])) {

    $colname_getpackagecost = $_GET['package_id'];
}

$client_id_get_psmt_requests = "-1";
if (isset($_SESSION['MM_client_id'])) {
    $client_id_get_psmt_requests = $_SESSION['MM_client_id'];
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


    <!--Peleza-->
    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <link href="css/vendors.css" rel="stylesheet">
    <link href="css/icon_fonts/css/all_icons_min.css" rel="stylesheet">
    <link href="assets/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">

    <style>
        .box{

        }
        .box-radio{

        }
        hr.divider {
            margin: 0em;
        }
        h5{
            margin: 0;
            padding: 0;
        }
        .spacer{
            margin-top: 2em!important;
        }
        .bg-green{
            background-color: #d8e95d
        }
        .btn-outline-primary{
            color: #2C6261!important;
            border-color: #2C6261!important;
        }
        .btn-primary{
            background-color: #2C6261;!important;
            border-color: #2C6261!important;
        }

    </style>

</head>
<body>
<div id="preloader" class="Fixed">
    <div data-loader="circle-side"></div>
</div>
<!-- /Preload-->

<span id="login-id" style="display: none"><?= $client_login_id_get_psmt_requests ?></span>
<span id="client-id" style="display: none"><?= $client_id_get_psmt_requests ?></span>
<span id="filter-status" style="display: none"><?= $filter_status ?></span>
<span id="package-id" style="display: none"><?= $colname_getpackagecost ?></span>
<span id="client-company-id" style="display: none"><?= $_SESSION['MM_client_company_id'] ?></span>
<span id="staff_id" style="display: none"><?= $_SESSION['MM_Username'] ?></span>
<span id="uploaded_by" style="display: none"><?= $_SESSION['MM_full_names'] ?></span>


<div class="app-container body-tabs-shadow fixed-sidebar">
    <?php include 'partials/header.php'; ?>

    <div class="app-main">

        <?php include 'partials/sidebar.php'; ?>

        <div class="app-main__outer" id="request-vm">

            <?php include 'partials/top-header.php'; ?>

            <!--Body-->
            <div v-if="request_id == -1" class="container margin_60">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="box_general_3 cart">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <div id="confirm">
                                            <h2>Kindly select a Package or Choose an Individual/Company to conduct a backgorund request</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>
                            <!-- /container -->
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>

                <div class="container margin_60">
                    <div class="row">
                        <div class="single box">
                            <!-- /filters -->
                            <div class="col-sm-12">
                                <div class="box_general_3 cart bg-green">
                                    <div class="container">

                                        <div style="background-color: darkolivegreen; color: white!important; padding: 8px; margin: 0px 0px 20px 0px !important; width: 100%">
                                            <span v-text="package.package_name"></span>
                                        </div>

                                        <form class="form-horizontal m-t-40 " id="requestform" name="requestform" ENCTYPE='multipart/form-data' action='requestgeneral.php' method='post'>

                                            <div class="col-12 box_general_3 cart bg-green">

                                                <input type="hidden" id="package_id" name="package_id" class="form-control" v-bind:value="package.package_id">
                                                <div class="main_title_4">
                                                    <h3>Select the Background Checks you wish to conduct</h3>
                                                </div>

                                                <ul class="treatments clearfix">

                                                    <li v-for="(item,x) in package.modules">
                                                        <div class="checkbox">
                                                            <input type="checkbox" class="css-checkbox selected-modules ckbx user-input" v-bind:id="item.id" v-model="module_id" v-bind:value="item.module_id" v-on:change="setModuleID">
                                                            <label v-bind:for="item.id" class="css-label">
                                                                <span v-text="item.module_name"></span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-12 box_general_3 cart bg-green">

                                                <div class="col-12">
                                                    <div class="row spacer" style="margin: 5px 15px;"><h5>Dataset Details</h5></div>
                                                    <hr class="divider">
                                                    <div class="row spacer">
                                                        <div class="col-xl-4 col-lg-4">
                                                            <label>Request Reference No</label>
                                                            <input type="text" id="request_ref_number" v-model="request_ref_number" class="form-control  txbx user-input" readonly/>
                                                            <input type="hidden" id="colname_getpackagecost" name="colname_getpackagecost" placeholder="Text Field" class="col-xs-10 col-sm-5"  v-bind:value="package.package_cost" />
                                                        </div>

                                                        <div class="col-xl-4 col-lg-4">
                                                            <label>Name of Candidate</label>
                                                            <input type="text" id="bg_dataset_name" v-model="bg_dataset_name" placeholder="&#xf007;" class="iconified empty form-control mb-2">
                                                        </div>

                                                        <div class="col-xl-4 col-lg-4">

                                                            <label>Select Citizenship</label>
                                                            <select class="custom-select form-control stbx user-input" id="dataset_citizenship" v-model="dataset_citizenship">
                                                                <option v-for="country in countries" v-bind:value="country.country_nationality" v-text="country.country_nationality"></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="row spacer" style="margin: 5px 15px;"><h5>Consent Form</h5></div>
                                                    <hr class="divider">
                                                    <div class="row spacer">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <div  class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="consentform" name="consentform">
                                                                    <label class="custom-file-label" for="consentform">
                                                                        Consent Form
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="row spacer" style="margin: 5px 15px;"><h5>Required Documents</h5></div>
                                                    <hr class="divider">

                                                    <div class="row">
                                                        <div v-for="doc in required_documents" class="col-3" v-if="doc.data_type == 'text'" style="margin: 10px 0px;">

                                                            <div class="form-group">
                                                                <label v-bind:for="doc.id">
                                                                    <span v-text="doc.document_name"></span>
                                                                </label>
                                                                <input type="text" class="form-control" v-bind:id="doc.id" v-bind:placeholder="doc.document_name" v-bind:id="doc.id" v-bind:class="doc.class" v-bind:class="getClassType(doc.data_type)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div v-for="doc in required_documents" class="col-3" v-if="doc.data_type == 'file'" style="margin: 10px 0px;">

                                                            <div  class="custom-file">
                                                                <input type="file" class="custom-file-input" v-bind:id="doc.id">
                                                                <label class="custom-file-label" v-bind:for="doc.id">
                                                                    <span v-text="doc.document_name"  style="font-size: small"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!--
                                                <div class="col-12" v-for="document in documents">
                                                    <div class="row spacer" style="margin: 5px 15px;"><h5 v-text="document.module_name"></h5></div>
                                                    <hr class="divider">
                                                    <div class="row spacer">
                                                        <div class="col-md-6 col-sm-6" v-for="doc in document.documents">
                                                            <div class="form-group">
                                                                <label v-text="doc.document_name"></label>
                                                                <input v-bind:type="doc.data_type" v-bind:placeholder="doc.document_name" class="user-input" v-bind:id="doc.id" v-bind:class="doc.class" v-bind:class="getClassType(doc.data_type)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                -->

                                                <div class="col-12">
                                                    <div class="row spacer" style="margin: 5px 15px;"><h5>TERMS AND CONDITIONS</h5></div>
                                                    <hr class="divider">
                                                    <div class="row spacer">
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="form-group">
                                                                <input type="checkbox" name="terms" id="terms" class="ckbx user-input" v-model="terms"/>
                                                                I accept terms and conditions and general policy of Background Screening Request. I also accept that the data set above mentioned has given consent for us to conduct background screening
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                            <!--Submit buttons-->
                            <div class="col-12">
                                <div class="box_general_3 cart bg-green">
                                    <div class="row">
                                        <div class="col-5">
                                            <button class="btn btn-outline-primary mb-2" id="addCandidate" form="Validate">Add Candidate</button>
                                        </div>
                                        <div class="col-5">
                                            <input class="btn btn-outline-primary mb-2" type="button" value="Save & Continue Late">
                                        </div>
                                        <div class="col-2 pull-right">
                                            <button class="btn btn-primary" v-on:click="postForm">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Footer-->
            <?php include 'partials/footer.php'; ?>
        </div>

    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">REQUEST RESPONSE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="statusMsg"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="./assets/scripts/main.js"></script>

<!-- Submit form -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="v1/js/vue.js" type="text/javascript"></script>
<script src="v1/js/axios.min.js" type="text/javascript"></script>
<script src="js/bootstrap-notify.js" type="text/javascript"></script>
<script src="assets/sweetalert/sweetalert.min.js"></script>
<script src="v1/js/dashboard-stats.js?<?= rand(0,1000) ?>" type="text/javascript"></script>

<script>

    setInterval(function () {

        $('input[type="file"]').change(function(e){

            console.log('GOT HERE ON CHANGE');
            var fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').html(fileName);

        });

    },1000);

</script>

<!--<script> document.cookie = "myJavascriptVar = " + javascript_array </script>-->

<script src="js/common_scripts.min.js"></script>
<script src="js/functions.js"></script>

<script>
    $(document).ready( function () {

        $(document).ready(function() {

            var request_id = document.getElementById('package-id').innerHTML;

        });


    });
</script>

</body>
</html>