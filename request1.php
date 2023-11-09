<?php
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

// Form Action :: Navigate to the Same Page
$editFormAction = $_SERVER['PHP_SELF'];
// if (isset($_SERVER['QUERY_STRING'])) {
//     $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
// }

// Send Mail
// If form is submitted.
// require('./requestmail.php');
// if (isset($_POST['MM_insert'])){
//     $auth_mailer->send_mail();
// } else {
//     echo "Form not submitted successfully!!";
// }

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


    <!--Peleza-->
    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <link href="css/vendors.css" rel="stylesheet">
    <link href="css/icon_fonts/css/all_icons_min.css" rel="stylesheet">
    <link href="assets/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">

    <style>
        .box {

        }

        .box-radio {

        }

        hr.divider {
            margin: 0em;
        }

        h5 {
            margin: 0;
            padding: 0;
        }

        .spacer {
            margin-top: 2em !important;
        }

        .bg-green {
            background-color: #d8e95d
        }

        .btn-outline-primary {
            color: #2C6261 !important;
            border-color: #2C6261 !important;
        }

        .btn-primary {
            background-color: #2C6261;
        !important;
            border-color: #2C6261 !important;
        }
        .form-group.required .control-label:after {
            content:"*";
            color:red;
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
<span id="package-id" style="display: none"><?= 7 ?></span>
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
            <div class="container">
                <div class="row">
<!--                    <form action="requestpost.php" method="POST" name="requestform"> -->
                        <div class="">
                            <!-- /filters -->
                            <div class="cart olive-bg row" style="border-radius: 5px;">

                                <div class="col-12" style="padding: 10px 10px 10px 20px;">
                                    <h6 class="dark-navy-blue"><span><i class="fa fa-comments fa-2x"></i></span> <span>Individual Request</span>
                                    </h6>
                                </div>

                                <div class="green-shade-1-bg col-12" style="padding: 8px;">
                                    <h6 style="color: white"><span v-text="package.package_name"></span></h6>
                                </div>
                            </div>

                            <div class="cart olive-bg row" style="border-radius: 5px 5px 0px 0px;">

                                <div class="col-12" style="max-width: 100%;padding: 15px;">

                                    <h5 style="text-transform: uppercase">Select the Background Checks you wish to conduct</h5>
                                    <hr class="dark-divider">

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

                                    <h5 style="margin-top: 15px; text-transform: uppercase">Dataset Details</h5>
                                    <hr class="dark-divider">

                                    <div class="col-12" style="padding: 0px">
                                        <div class="row" style="padding: 0">
                                            <div class="col-xl-4 col-lg-4 form-group required">
                                                <label class="control-label">Request Reference No</label>
                                                <input type="text" id="request_ref_number" v-model="request_ref_number" class="form-control  txbx user-input" readonly/>
                                                <input type="hidden" id="colname_getpackagecost" name="colname_getpackagecost" placeholder="Text Field" class="col-xs-10 col-sm-5"  v-bind:value="package.package_cost" />
                                            </div>

                                            <div class="col-xl-4 col-lg-4 form-group required">
                                                <label class="control-label">Name of Candidate</label>
                                                <input type="text" id="bg_dataset_name" v-model="bg_dataset_name" placeholder="&#xf007;" class="iconified empty form-control mb-2" required="required" name="bg_dataset_name">
                                            </div>

                                            <div class="col-xl-4 col-lg-4 form-group required">

                                                <label class="control-label">Select Citizenship</label>
                                                <select class="custom-select form-control stbx user-input" id="dataset_citizenship" v-model="dataset_citizenship" required="required">
                                                    <option v-for="country in countries" v-bind:value="country.country_nationality" v-text="country.country_nationality"></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 style="margin-top: 15px; text-transform: uppercase">Consent Form</h5>
                                    <hr class="dark-divider">

                                    <div class="col-12" style="padding: 0px">
                                        <div class="row" style="padding: 0">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div  class="custom-file required">
                                                        <input type="file" class="custom-file-input" id="consentform" name="consentform" required>
                                                        <label class="custom-file-label control-label" for="consentform">
                                                            Consent Form
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 style="margin-top: 15px; text-transform: uppercase">Document Upload</h5>
                                    <hr class="dark-divider">

                                    <div class="col-12" style="padding: 0px">

                                        <div class="row">
                                            <div v-for="doc in required_documents" class="col-3" v-if="doc.data_type != 'file'" style="margin: 10px 0px;">
                                                <div class="form-group required">
                                                    <label v-bind:for="doc.id" class="control-label">
                                                        <span v-text="doc.document_name"></span>
                                                    </label>
                                                    <input v-bind:type="doc.data_type" class="form-control" v-bind:id="doc.id" v-bind:placeholder="doc.document_name" v-bind:id="doc.id" v-bind:class="doc.class" v-bind:class="getClassType(doc.data_type)" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div v-for="doc in required_documents" class="col-3" v-if="doc.data_type == 'file'" style="margin: 10px 0px;">

                                                <div  class="custom-file form-group required">
                                                    <input type="file" class="custom-file-input control-label" v-bind:id="doc.id" required>
                                                    <label class="custom-file-label" v-bind:for="doc.id">
                                                        <span v-text="doc.document_name"  style="font-size: small"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <h5 style="margin-top: 15px; text-transform: uppercase">Terms and Conditions</h5>
                                    <hr class="dark-divider">

                                    <div class="col-12" style="padding: 0px">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group required">
                                                    <input type="checkbox" name="terms" id="terms" class="ckbx user-input control-label" v-model="terms"/>
                                                    I accept terms and conditions and general policy of Background Screening
                                                    Request. I also accept that the data set above mentioned has given
                                                    consent for us to conduct background screening
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12" style="padding: 0">
                                    <hr class="closing">
                                </div>
                            </div>

                            <div class="cart olive-bg row" style="border-radius: 0px 0px 5px 5px; padding: 10px; margin-bottom: 15px">

                                <div class="col-12">

                                    <button input type="submit" value="submit" class="btn btn-primary pull-right" v-on:click="postForm">
                                        Submit
                                    </button>

                                    <!-- Calling Page to submit the Request and Trigger an Email Prompt. -->
<!--                                     <button input type="submit" value="submit" class="btn btn-primary pull-right">
                                        Submit
                                    </button> -->
                                </div>
                            </div>

                        </div>
                        <!--Submit buttons-->
                        <input type="hidden" name="MM_insert" value="requestform">
                    <!-- </form> -->
                </div>

            </div>

            <!-- Modal -->
            <div class="modal fade" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Uploading file <span v-text="percentage"></span>% </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="progress-area">
                        <!-- PHP Code to Send Mail  -->
                        <?php 
                            // Get Details From The Session
                            $STAFF_ID = $_SESSION['MM_Username'];
                            date_default_timezone_set('Africa/Nairobi');
                            $date_insert = date('Y-m-d h:i:s');
                            $uploadedby =  $_SESSION['MM_full_names'];
                            $MM_client_id = $_SESSION['MM_client_id'];
                            $MM_client_login_id =   $_SESSION['MM_client_login_id'];
                            $MM_client_parent_company = $_SESSION['MM_client_parent_company'];

                            // collect value of input field
                            $candidate_name = $_REQUEST['bg_dataset_name'];
 
                            // Send Email
                            require ("PHPMailer/PHPMailer.php");

                            require("PHPMailer/SMTP.php");
                            require("PHPMailer/Exception.php");

                            $toemail= "verify@peleza.com";

                            $mail = new PHPMailer\PHPMailer\PHPMailer();

                            $mail->isSMTP();
                            $mail->Host = 'smtppro.zoho.com';             // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true;                     // Enable SMTP authentication
                            $mail->Username = 'supportadmin@peleza.com';          // SMTP username
                            $mail->Password = 'LTQmZbfxzaStb8xN@'; // SMTP password
                            $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = 465;                           // TCP port to connect to

                            $mail->setFrom('supportadmin@peleza.com', 'Background Checks>> Peleza International');

                            $mail->addAddress($toemail);
                            // $mail->addAddress('joseph.mbuku@peleza.com');   // Add a recipient
                            $mail->addCC('joseph.mbuku@peleza.com');
                            // $mail->addBCC('omintolbert@gmail.com');

                            $mail->isHTML(true);  // Set email format to HTML

                            $bodyContent = '<p><img src="https://psmt.pidva.africa/img/Peleza_Logo_We_Get_It.png" width="166" height="60" /></p>
                                                        <p><strong>Hi Verification Team,</strong></p>
                                                        <p>New Request has been Submitted from PSMT.</p><br/><br/>
                                                        <p>Uploaded By:  '.$uploadedby.'</p><br/><br/>
                                                        <p>COMPANY:   '.$MM_client_parent_company.'</p><br/><br/>
                                                         
                                                        <p>  - The Peleza Team<br />
                                                          Support: +254 796 111 020 or +254  Email:&nbsp;<a href="mailto:verify@peleza.com">verify@peleza.com</a>&nbsp;<br />
                                                          ® Peleza Int, 2018. All rights reserved. </p>';
                            //$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

                            $mail->Subject = 'Confidential Background Check Request';
                            $mail->Body    = $bodyContent;

                            // $mail->send();
                            if(!$mail->Send()) {
                              echo "Error while sending Email.";
                              var_dump($mail);
                            } else {
                              echo "Email sent successfully";
                            }
                        ?>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--Footer-->
            <?php include 'partials/footer.php'; ?>

        </div>

    </div>

</div>

</div>

<!-- Modal -->


<script type="text/javascript" src="./assets/scripts/main.js"></script>

<!-- Submit form -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="v1/js/vue.js" type="text/javascript"></script>
<script src="v1/js/axios.min.js" type="text/javascript"></script>
<script src="js/bootstrap-notify.js" type="text/javascript"></script>
<script src="assets/sweetalert/sweetalert.min.js"></script>
<script src="js/progress-js/progress.js"></script>

<script src="v1/js/dashboard-stats.js?<?= rand(0, 1000) ?>" type="text/javascript"></script>

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

    $(document).ready(function () {

        $(document).ready(function () {

            var request_id = document.getElementById('package-id').innerHTML;

        });

        $('.custom-file-input').on('change',function(){
            //get the file name
            //var fileName = $(this).val();
            //replace the "Choose a file" label
            //$(this).next('.custom-file-label').html(fileName);
        });

    });
</script>

</body>
</html>