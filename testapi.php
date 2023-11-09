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
    <style>
        .fa-stack {
            position: relative;
            /* Adjust these values accordingly */
            bottom: 4px;
            padding: 10px;
        }
    </style>

</head>
<body>
<div class="app-container body-tabs-shadow fixed-sidebar">
    <?php include 'partials/header.php'; ?>

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
                            <a href="dashboard/index.php">
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
                                        <li><a href="request.php?package_id=<?php echo $row_getpackagenames['package_id']; ?>"><i class="metismenu-icon"></i> <?php echo $row_getpackagenames['package_name']; ?> </a></li>

                                        <?php
                                        $x++;
                                    } while ($row_getpackagenames = mysqli_fetch_assoc($getpackagenames));
                                } else {
                                    $queryitem .= "";
                                    $x = 1;

                                    do {
                                        $queryitem .= "'".$row_getpackagegeneral['package_name']."',";
                                        ?>
                                        <li><a href="request.php?package_id=<?php echo $row_getpackagegeneral['package_id']; ?>"><i class="metismenu-icon"></i> <?php echo $row_getpackagegeneral['package_name']; ?> </a></li>

                                        <?php
                                    } while ($row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral));
                                }

                                $queryitem .= "'')";

                                ?>
                            </ul>
                        </li>
                        <li>
                            <a href="reports/index.php">
                                <span class="fa-stack"><i class="icon-newspaper-1 fa-stack-1x"></i></span>
                                Reports
                            </a>
                        </li>
                        <li>
                            <a href="cart/cart.php">
                                <span class="fa-stack"><i class="icon-cart fa-stack-1x"></i></span>
                                Cart
                            </a>
                        </li>
                        <li>
                            <a href="payments.php">
                                <span class="fa-stack"><i class="icon-dollar-1 fa-stack-1x"></i></span>
                                Payment
                            </a>
                        </li>
                        <li>
                            <a href="faq.php">
                                <span class="fa-stack"><i class="icon-question fa-stack-1x"></i></span>
                                FAQs
                            </a>
                        </li>
                        <li>
                            <a href="testapi.php" class="mm-active">
                                <span class="fa-stack"><i class="icon-code-3 fa-stack-1x"></i></span>
                                APIs
                            </a>
                        </li>
                        <li class="app-sidebar__heading text-light">YOUR STUFF</li>
                        <li>
                            <a href="profile.php">
                                <span class="fa-stack"><i class="icon-user fa-stack-1x"></i></span>
                                Profile
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="app-main__outer">
            <?php include "partials/app_inner.php"; ?>

            <!--Body-->
            <div class="container margin_60">
                <div class="row">
                    <aside class="col-lg-2" id="sidebar">
                        <div class="box_style_cat" id="faq_box">
                            <ul id="cat_nav">
                                <li><a href="#registration" class="active"><i class="icon_document_alt"></i>Registration</a></li>
                                <li><a href="#apidocumentation"><i class="icon_document_alt"></i>API Documentation</a></li>

                            </ul>
                        </div>
                        <!--/sticky -->
                    </aside>
                    <!--/aside -->

                    <div class="col-lg-10" id="faq">


                        <h3>TEST ENVIRONMENT API SETUP</h3>

                        <h4 class="nomargin_top">Registration</h4>
                        <div role="tablist" class="add_bottom_45 accordion" id="registration">
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne_payment" aria-expanded="true"><i class="indicator icon_minus_alt2"></i>Signing Up for Test Integration</a>
                                    </h5>
                                </div>

                                <div id="collapseOne_payment" class="collapse show" role="tabpanel" data-parent="#registration">
                                    <div class="card-body">
                                        <p> Open a Client Account with Peleza International Limited you can contact the Peleza team using the email  <strong>admin@peleza.com</strong>.To start using the API you need to have been assigned a <strong>CLIENT LOGIN ID</strong> and then you will use it to generate an <strong>API Key</strong> and <strong>Secret Password </strong>that will be available on your account for access.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo_payment" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>Creating your Test API account</a></h5>
                                </div>

                                <div id="collapseTwo_payment" class="collapse" role="tabpanel" data-parent="#registration">
                                    <div class="card-body">
                                        <p>
                                            1. Peleza Sandbox URL is a test environment for you to use in testing your Integrations before going to production.</p>
                                        <p>2. First time you generate the credentails you will receive the Sandbox URLS for Testing the Different APIs,<br>
                                        <ul>
                                            <li>a. Go through the detailed documentation <a href="#apidocumentation"><i class="icon_document_alt"></i>Here</a></li>
                                            <li>b. Go to <a href="profile.php">My Profile</a> on the API tab Click Generate Test API APP tab</li>
                                        </ul>
                                        </p>
                                        <p>3. Create a Test API APP by adding Test Name and select the services you want to Integrate:<br>


                                        <table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                                            <tr><td>API Servicee</td><td>Description</td></tr>
                                            <tr><td>National Identity Check Kenya</td><td>For checking National Identity status and verification of validity </td></tr>
                                            <tr><td>Passport Check Kenya</td><td>For Checking Kenyan Passport holders passport verirification  </td></tr>
                                        </table>
                                        </p>
                                        <p>4. Your Test Integration app will be created and approved by our Adminsitration.</p>
                                        <p>5. Your Will then receive an email with a notification to access the TEST API credentials and you can Proceed.</p>
                                        <p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree_payment" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>API Credentials and Secret Password
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseThree_payment" class="collapse" role="tabpanel" data-parent="#registration">
                                    <div class="card-body">
                                        <p>1. <strong>An API Key</strong> will be generated and Published to your Account</p>
                                        <p>2. The username to Use is your <strong>CLIENT LOGIN ID</strong> assigned by Peleza</p>
                                        <p>3. The <strong>Secret Password </strong> will be published to your account</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /accordion payment -->

                        <h4 class="nomargin_top">APIs Documentation</h4>
                        <div role="tablist" class="add_bottom_45 accordion" id="apidocumentation">
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne_tips" aria-expanded="true"><i class="indicator icon_plus_alt2"></i>Generating Access Token</a>
                                    </h5>
                                </div>

                                <div id="collapseOne_tips" class="collapse" role="tabpanel" data-parent="#apidocumentation">
                                    <div class="card-body">
                                        <div id="collapse1">
                                            <div>
                                                <p>This API generates the tokens for authenticating your API calls. This   is the first API you will engage with in the set of APIs available   because all the other APIs require authentication information from this   API to work.</p>
                                                <p>You can access your <strong>API key and API Secret</strong> under the <strong>MY TEST API</strong> tab from <strong>My Account</strong> <a href="profile.php">here</a></p>
                                                <p><strong>The API works as detailed below:</strong></p>
                                                <div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">  <p> <strong>Authentication:</strong> Basic U1BMd0xkMnVBM29ub1BSWENKRjZiV3FXR3hOdkE4Qlo6NldPZ2hNQUdUdUVZS2pYMw==</p>
                                                    <pre><strong>GET</strong> /pelauth/vgenerate/generatetok?gettype=tokengeneration HTTP/1.1
<strong>Host:</strong> sandbox.peleza.com
<strong>Authorization:</strong> Basic VTFCTWQweGtNblZCTTI5dWIxQlNXRU5LUmpaaVYzRlhSM2hPZGtFNFFsbzZObGRQWjJoTlFVZFVkVVZaUzJwWU13PT0=
<strong>Content-Type:</strong>application/json </pre></div>
                                                <pre>
Response:
<div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">{    &quot;access_token&quot;: &quot;hsHoclSD53UC3657NAD3d0qBE8cA&quot;,    &quot;expires_in&quot;: &quot;4000&quot;  }</div>
</pre>
                                                <p>Get the Base-64 encoding of <strong>API Key + &quot;:&quot; + API Secret</strong> (note the full colon in the encoding)</p>
                                                <p>Create a GET request and set an   Authentication header with the value as Basic + encoded value from above   step e.g. using the Test Peleza Credentials above, the header will be</p>
                                                <p>Send the request to the endpoint <strong>https://sandbox.peleza.com/pelauth/vgenerate/generatetok?gettype</strong>. The raw request will look similar to the following :</p>
                                                <p>You will get a response in the sample format shown below. This shows your token and how long it will take before it expires.</p>
                                                <p>
                                                <p> Once generated, you somehow need to keep track of the timeout period so it does not expire. But know that when you get the <strong>&quot;Invalid Access Token&quot;</strong>error,   your Auth token has expired or is not set. Get a new one. </p>
                                                <p><strong>ERROR RESPONSES.</strong></p>
                                                <table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                                                    <tr><td> Error Code</td><td>Description</td></tr>
                                                    <tr><td>pel_100_001</td><td>Invalid get type passed </td></tr>
                                                    <tr><td>pel_100_002</td><td>Invalid Authentication passed  </td></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /card -->
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo_tips" aria-expanded="false">
                                            <i class="indicator icon_plus_alt2"></i>National Identity Verification / Passport Check Verification
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseTwo_tips" class="collapse" role="tabpanel" data-parent="#apidocumentation">
                                    <div class="card-body">
                                        <p>This API is used for verification of National Identity/Passport Check Verification. You can use the following National Id Numbers /Passport Numbers to Send Sample Requests and Get Responses.
                                        </p>
                                        <p><strong>NOTE: </strong> The following National Identity/ Passport Numbers have been provided for test purposes only. Data Protection and Policy applies for rights of use.
                                        </p>
                                        <p><strong>SAMPLE DATA TO USE:</strong></p>
                                        <table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                                            <tr><td width="50%">NATIONAL IDENTITY</td><td>PASSPORT</td></tr>
                                            <tr><td>23680415</td><td>A1998653</td></tr>
                                            <tr><td>25219798</td><td></td></tr>
                                            <tr><td>28353847</td><td></td></tr>
                                            <tr><td></td><td></td></tr>
                                            <tr><td></td><td></td></tr>
                                            <tr><td></td><td></td></tr>
                                        </table>
                                        <p>The basic National Identity/ Passport Verification request looks like the sample below:</p>

                                        <div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">
                                            <p>// URL [POST] https://sandbox.peleza.com/verification/identity/simulate<br />
                                                // HEADERS<br />
                                                Host: sandbox.peleza.com<br />
                                                Authorization: Bearer [access token]<br />
                                                Content-Type: application/json<br />
                                                // BODY<br />
                                                {<br />
                                                &quot;ClientID&quot;: &quot;CLIENTID&quot;,<br />
                                                &quot;VeririfcationID&quot;: &quot;VerifyIdentity&quot;,<br />
                                                &quot;TransactionType&quot;: &quot;NationalIdentityVerification/PassportVerification&quot;,<br />
                                                &quot;Country&quot;: &quot;KENYA&quot;,<br />
                                                &quot;VerificationIdNumber&quot;: &quot;National Id Number/ Passport Number&quot;,<br />
                                                &quot;VerificationIdSerial&quot;: &quot;National Id Serial Number/ Passport Serial Number&quot;,<br />
                                                &quot;TransTime&quot;: &quot;YYYY-MM-dd HH:mm:ss&quot;,<br />
                                                &quot;TransactionId&quot;: &quot;UniqueGeneratedIdentifier&quot;<br />
                                                &quot;CallBackUrl&quot;: &quot;https://yourdomain/verificationresults&quot;<br />
                                                }</p></div>

                                        <h5><strong>ClientID</strong></h5>
                                        <p>This is the Unique Assigned Client Ideneitifier Issued to the Client by PELEZA Administrator.</p>
                                        <h5><strong>VeririfcationID</strong></h5>
                                        <p>Idenitfy shwcasing the API type allowed verification should always be <strong>VerifyIdentity</strong> if you are verifying identity details.</p>
                                        <h5><strong>TransactionType</strong></h5>
                                        <p>The type of verification being conducted <strong>NationalIdentityVerification</strong> for National Identity & <strong>PassportVerification</strong> for Passport.</p>
                                        <h5><strong>Country</strong></h5>
                                        <p>The country of orgigin of the identification document. Should be full name of country in UPPERCASE.</p>
                                        <h5><strong>VerificationIdNumber</strong></h5>
                                        <p>The National Identity/Passport Number being verified.</p>
                                        <h5><strong>VerificationIdSerial</strong></h5>
                                        <p>The National Identity Serial Number/Passport Serial Number being verified.</p>
                                        <p><strong>NOTE: </strong>Atleast one of the following values should be sent and given. <strong>VerificationIdNumber/VerificationIdSerial </strong>
                                            Otherwise you will receive and error pel_200_003: Verification Data Missing</p>
                                        <h5><strong>TransTime</strong></h5>
                                        <p>Simply the time the transaction was sent to our system format YYYY-MM-dd HH:mm:ss.</p>
                                        <h5><strong>TransactionId</strong></h5>
                                        <p>A unique Generated Transaction Reference Number to track the transaction.</p>

                                        <h5><strong>CallBackUrl</strong></h5>
                                        <p>A URL that receives a callback from our system to notify you of the results of the Identity/Passport Verification.</p>

                                        <p><strong>After posting the request Succesful response will be the following:</strong></p>

                                        <div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">
                                            <p>
                                                // BODY<br />
                                                {<br />
                                                &quot;ClientID&quot;: &quot;CLIENTID&quot;,<br />
                                                &quot;VeririfcationID&quot;: &quot;VerifyIdentity&quot;,<br />
                                                &quot;ResultCode&quot;: &quot;000&quot;,<br />
                                                &quot;ResultDescription&quot;: &quot;SUCCESS&quot;,<br />
                                                &quot;TransactionId&quot;: &quot;UniqueGeneratedIdentifier&quot;<br />
                                                &quot;SystemTrackNumber&quot;: &quot;PEL-IDCHK-MZASD-YYYYMMddHHmmss&quot;<br />
                                                }</p></div>

                                        <p><strong>While Unsuccesful response will be the following:</strong></p>

                                        <div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">
                                            <p>
                                                // BODY<br />
                                                {<br />
                                                &quot;ClientID&quot;: &quot;CLIENTID&quot;,<br />
                                                &quot;VeririfcationID&quot;: &quot;VerifyIdentity&quot;,<br />
                                                &quot;ResultCode&quot;: &quot;errorcode&quot;,<br />
                                                &quot;ResultDescription&quot;: &quot;description&quot;,<br />
                                                &quot;TransactionId&quot;: &quot;UniqueGeneratedIdentifier&quot;<br />
                                                &quot;SystemTrackNumber&quot;: &quot;&quot;<br />
                                                }</p></div>
                                        <br/>
                                        <p><strong>ERROR RESPONSES.</strong></p>
                                        <table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                                            <tr><td> Error Code</td><td>Description</td></tr>
                                            <tr><td>pel_200_001</td><td>Invalid Verification Id passed </td></tr>
                                            <tr><td>pel_200_002</td><td>Invalid Token passed  </td></tr>
                                            <tr><td>pel_200_003</td><td>Verification Data Missing</td></tr>
                                            <tr><td>pel_200_004</td><td>One of the Mandatory Fields Missing Data</td></tr>
                                            <tr><td>pel_200_005</td><td>Invalid Callback URL</td></tr>
                                        </table>


                                        <p>The basic Call Back URL request should have the following parameters:</p>

                                        <div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">
                                            <p>// URL [POST] https://yourdomain/verificationresults<br />

                                                {<br />
                                                &quot;ClientID&quot;: &quot;CLIENTID&quot;,<br />
                                                &quot;VeririfcationID&quot;: &quot;VerifyIdentity&quot;,<br />
                                                &quot;TransactionType&quot;: &quot;NationalIdentityVerification/PassportVerification&quot;,<br />
                                                &quot;Country&quot;: &quot;KENYA&quot;,<br />
                                                &quot;VerificationIdNumber&quot;: &quot;National Id Number/ Passport Number&quot;,<br />
                                                &quot;VerificationIdSerial&quot;: &quot;National Id Serial Number/ Passport Serial Number&quot;,<br />
                                                &quot;TransTime&quot;: &quot;YYYY-MM-dd HH:mm:ss&quot;,<br />
                                                &quot;TransactionId&quot;: &quot;UniqueGeneratedIdentifier&quot;<br />
                                                &quot;SystemTrackNumber&quot;: &quot;PEL-IDCHK-MZASD-YYYYMMddHHmmss&quot;<br />
                                                &quot;DateOfBirth&quot;: &quot;01-01-1990&quot;<br />
                                                &quot;Gender&quot;: &quot;MALE/FEMALE&quot;<br />
                                                &quot;HoldersName&quot;: &quot;All Names&quot;<br />
                                                &quot;VerificationStatus&quot;: &quot;VALID/INVALID&quot;<br />
                                                }</p></div>

                                        <h5><strong>VerificationStatus</strong></h5>
                                        <p>For the Identity that exist in the Database is VALID and for the Identity that doesnt exist is INVALID.</p>

                                        <p><strong>After posting the request Succesful response will be the following:</strong></p>

                                        <div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">
                                            <p>
                                                // BODY<br />
                                                {<br />
                                                &quot;ResultCode&quot;: &quot;000&quot;,<br />
                                                &quot;ResultDescription&quot;: &quot;SUCCESS&quot;,<br />
                                                }</p></div>

                                        <p><strong>While Unsuccesful response will be the following:</strong></p>

                                        <div style=" border:#030 solid 1px;background-color:#cbd332; border-radius: 15px; padding:10px;">
                                            <p>
                                                // BODY<br />
                                                {<br />
                                                &quot;ResultCode&quot;: &quot;errorcode&quot;,<br />
                                                &quot;ResultDescription&quot;: &quot;description&quot;,<br />
                                                }</p></div>
                                        <br/>

                                    </div>
                                </div>
                            </div>
                            <!-- /card -->

                        </div>
                        <!-- /accordion Conducting BG Check -->




                    </div>
                    <!-- /col -->
                </div>
                <!-- /row -->
            </div>

            <!--Footer-->
            <?php include 'partials/footer.php'; ?>
        </div>
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    </div>
</div>
<script type="text/javascript" src="./assets/scripts/main.js"></script>
</body>
</html>