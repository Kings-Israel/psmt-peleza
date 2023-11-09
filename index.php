<?php require_once('Connections/connect.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
    session_start();
}
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
    $_SESSION['PrevUrl'] = $_GET['accesscheck'];
} ?><?php
$errorcode = "";

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "loginpsmt")) {

    //honey pot field
    $captcha = $_POST['g-recaptcha-response'];
    if (empty($captcha)) {
        //   if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
//			{				
//		$captcha=$_POST['g-recaptcha-response'];
//$secretKey = '6Lehb78UAAAAADJUHm2rO9-gaVo0JfsfRzuGADLp';				
//$ip = $_SERVER['REMOTE_ADDR'];
//        // post request to server
//        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
//        $response = file_get_contents($url);
//        $responseKeys = json_decode($response,true);
//        // should return JSON with success as true
//        if($responseKeys["success"]) {
//     


// Given password
        $password = $_POST['password'];
// Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
//$specialChars = preg_match('@[^\w]@', $password);|| !$specialChars

        //if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {

        //    $errorcode = " <p class='text-center link_bright'><strong>Password > = 8, atleast 1 upper case letter, 1 number.</strong></a></p>";

        //} else {


            $loginUsername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = $_POST['password']; //md5($_POST['password']);
            $client_company_id = filter_var($_POST['client_company_id'], FILTER_SANITIZE_STRING);
            $MM_fldUserAuthorization = "";
            $MM_redirectLoginSuccess = "dashboard/";
            $MM_redirectLoginFailed = "index.php";
            $MM_redirectChangePassword = "changepassword/";
            $MM_redirecttoReferrer = false;
            // mysqli_select_db($connect,$database_connect);

//$LoginRS__query=sprintf("SELECT * FROM pel_client WHERE client_login_username=%s AND client_password=%s AND client_company_id=%s and status = '11' AND client_counter IN ('0','1','2','3')", GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"), GetSQLValueString($client_company_id, "text")); 

            $LoginRS__query = sprintf("SELECT pcc.company_logo,pc.* FROM pel_client pc LEFT JOIN pel_client_co pcc ON pc.client_company_id = pcc.company_code WHERE client_login_username=%s AND client_password=MD5(%s) AND client_company_id=%s ",
                GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"), GetSQLValueString($client_company_id, "text"));

            $LoginRS__query1 = sprintf("SELECT pc.* FROM pel_client pc LEFT JOIN pel_client_co pcc ON pc.client_company_id = pcc.company_code WHERE client_login_username=%s AND client_company_id=%s ",
                GetSQLValueString($loginUsername, "text"), GetSQLValueString($client_company_id, "text"));

            error_log("QUERY $LoginRS__query");

            $LoginRS = mysqli_query($connect, $LoginRS__query) or die(mysqli_error());
            $loginFoundUser = mysqli_num_rows($LoginRS);

            if ($loginFoundUser) {

                $loginStrGroup = "";

                while ($row = mysqli_fetch_array($LoginRS)) {

                    //declare two session variables and assign them
                    $_SESSION['MM_Username'] = $loginUsername;
                    $_SESSION['MM_UserGroup'] = $loginStrGroup;
                    $_SESSION['MM_client_email_address'] = $row['client_email_address'];
                    $_SESSION['MM_client_mobile_number'] = $row['client_mobile_number'];
                    $_SESSION['MM_client_status'] = $row['status'];
                    $_SESSION['MM_first_name'] = $row['client_first_name'];
                    $_SESSION['MM_last_name'] = $row['client_last_name'];
                    $_SESSION['MM_full_names'] = $row['client_first_name'] . " " . $row['client_last_name'];
                    $_SESSION['MM_client_id'] = $row['client_id'];
                    $_SESSION['MM_client_credits'] = $row['client_credits'];
                    $_SESSION['MM_client_company_id'] = $row['client_company_id'];
                    $_SESSION['MM_added_date'] = $row['added_date'];
                    $_SESSION['MM_client_country'] = $row['client_country'];
                    $_SESSION['MM_client_parent_company'] = $row['client_parent_company'];
                    $_SESSION['MM_client_industry'] = $row['client_industry'];
                    $_SESSION['MM_added_by'] = $row['added_by'];
                    $_SESSION['MM_verified_date'] = $row['verified_date'];
                    $_SESSION['MM_verified_by'] = $row['verified_by'];
                    $_SESSION['MM_client_login_id'] = $row['client_company_id'];
                    $_SESSION['MM_client_postal_address'] = $row['client_postal_address'];
                    $_SESSION['MM_client_city'] = $row['client_city'];
                    $_SESSION['MM_client_postal_code'] = $row['client_postal_code'];
                    $_SESSION['MM_client_currency'] = $row['client_currency'];
                    $_SESSION['company_logo'] = $row['company_logo'];

                    $client_counter = $row['client_counter'];
                    $client_status = $row['status'];

                }

//check if client is active and continue
                if ($client_status == '11') {

                    if ($client_counter == '0' || $client_counter == '1' || $client_counter == '2' || $client_counter == '3') {

                        if (isset($_SESSION['PrevUrl']) && false) {
                            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
                        }
                        header("Location: " . $MM_redirectLoginSuccess);
                    } else {
                        $errorcode = " <p class='text-center link_bright'><strong>HERE :  Account Blocked: Talk to Administrator</strong></a></p>";
                    }
                }
                if ($client_status == '33') {
                    if (isset($_SESSION['PrevUrl']) && false) {
                        $MM_redirectChangePassword = $_SESSION['PrevUrl'];
                    }
                    header("Location: " . $MM_redirectChangePassword);
                }


            } else {

                $LoginRS__query = sprintf("SELECT * FROM pel_client WHERE client_login_username=%s AND client_company_id=%s",
                    GetSQLValueString($loginUsername, "text"), GetSQLValueString($client_company_id, "text"));

                $LoginRS = mysqli_query($connect, $LoginRS__query) or die(mysqli_error());
                $loginFoundUser = mysqli_num_rows($LoginRS);
                if ($loginFoundUser) {
                    $loginStrGroup = "";

                    while ($row = mysqli_fetch_array($LoginRS)) {

                        $client_counter = $row['client_counter'];

                    }
                    $client_counter = $client_counter + 1;

                    if ($client_counter >= '4') {
                        $errorcode = " <p class='text-center link_bright'><strong>APA: Account Blocked: Talk to Administrator</strong></a></p>";
                    } else {
                        $updateSQL = sprintf("UPDATE pel_client SET client_counter=%s WHERE client_login_username=%s AND client_company_id=%s",
                            GetSQLValueString($client_counter, "text"),
                            GetSQLValueString($loginUsername, "text"),
                            GetSQLValueString($client_company_id, "text"));


                        mysqli_query($connect, $updateSQL);
                        $errorcode = " <p class='text-center link_bright'><strong>Please Enter Correct Details</strong></a></p>";
                    }
                } else {

                    $errorcode = " <p class='text-center link_bright'><strong>Please Enter Correct Details</strong></a></p>";

                }
            }
        //}
    } else {
        $errorcode = " <p class='text-center link_bright'><strong>Error!!! on Page</strong></a></p>";

    }

// }
// 
//  else {
//
//  $errorcode = " <p class='text-center link_bright'><strong>Please check the Checkbox</strong></a></p>";
////    header("Location: ". $MM_redirectLoginFailed );
//  }

}
?>
<!DOCTYPE html>
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

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <link href="css/vendors.css" rel="stylesheet">
    <link href="css/icon_fonts/css/all_icons_min.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css?=<?= rand(0,999999) ?>" rel="stylesheet">
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">

    <style>
        .hide-robot {
            display: none;
        }
    </style>

</head>

<body>

<div id="preloader" class="Fixed">
    <div data-loader="circle-side"></div>
</div>
<!-- /Preload-->

<div id="page">
    <header class="header_sticky">
        <a href="#menu" class="btn_mobile">
            <div class="hamburger hamburger--spin" id="hamburger">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
        </a>
        <!-- /btn_mobile-->
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div id="logo">
                        <a href="#" title="PSMT"><img src="img/Peleza_Logo_We_Get_It.png?1" data-retina="true" alt=""
                                                      width="163" height="36"></a>
                    </div>
                </div>
                <div class="col-lg-9 col-6">
                    <ul id="top_access">

                        <li><a href="contacts.html" class="btn_1 small"><i class="icon-mobile"
                                                                           style="font-size:15px"></i><span
                                        id="mybuttontext">Contact Admin</span></a></li>
                    </ul>
                    <nav id="menu" class="main-menu">
                        <ul>

                        </ul>
                    </nav>
                    <!-- /main-menu -->
                </div>
            </div>
        </div>
        <!-- /container -->
    </header>
    <!-- /header -->

    <main>
        <div id="hero_register">
            <div class="container margin_120_95_login">
                <div class="row">
                    <div class="col-lg-6">
                        <h1>Redefining Background Screening</h1>
                        <p class="lead">We at Peleza are redefining how you conduct background screening by offering you
                            a self service platform.</p>
                        <div class="box_feat_2">
                            <i class="pe-7s-note2"></i>
                            <h3>Consent!</h3>
                            <p>Always get a consent from the individual/organisation before you conduct background
                                checks.</p>
                        </div>
                        <div class="box_feat_2">
                            <i class="pe-7s-note"></i>
                            <h3>Collect data</h3>
                            <p>Collect data for the individual/organisation and submit to us easily for Backgorund
                                screening.</p>
                        </div>
                        <div class="box_feat_2">
                            <i class="pe-7s-display1"></i>
                            <h3>Reports</h3>
                            <p>Track progress, and receive digital reports which you can print from the comfort of your
                                desk.</p>
                        </div>
                    </div>
                    <!-- /col -->
                    <div class="col-lg-5 ml-auto" style="margin-top:50px;">
                        <div class="box_form">
                            <div id="message-register">      <?php

                                echo $errorcode;

                                ?></div>
                            <form name="loginuser" action="<?php echo $loginFormAction; ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="form-group"><span id="sprytextfield1">
                                        <input type="text" class="form-control" placeholder="Client Login ID"
                                               name="client_company_id" id="client_company_id" AUTOCOMPLETE="off"
                                               required>
                                        <span class="textfieldRequiredMsg">*.</span><span class="textfieldMinCharsMsg">*.</span></span>
                                        </div>
                                    </div>

                                </div>
                                <!-- /row -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group"><span id="sprytextfield2">
                                        <input type="email" class="form-control" placeholder="Username" name="username"
                                               id="username" AUTOCOMPLETE="off" required>
                                        <span class="textfieldRequiredMsg">*.</span><span
                                                        class="textfieldInvalidFormatMsg">*.</span><span
                                                        class="textfieldMinCharsMsg">*.</span></span></div>
                                    </div>
                                </div>
                                <!-- /row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"><span id="sprytextfield3">
                                        <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password" AUTOCOMPLETE="off" required>
                                        <span class="textfieldRequiredMsg">*.</span><span class="textfieldMinCharsMsg">*.</span></span>
                                        </div>
                                    </div>

                                </div>
                                <!-- /row -->
                                <!--	<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                 <div class="g-recaptcha" data-sitekey="6Lehb78UAAAAAPfBjsgBynvKz1Ncpm-O6jtcR96D"></div>
                            </div>
                        </div>
                    </div>-->
                                <!-- /row -->
                                <input type="hidden" class="form-control" name="g-recaptcha-response"
                                       id="g-recaptcha-response">
                                <div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="submit" class="btn_1" value="Submit" id="submit-register">
                                            </div>
                                        </div>
                                    </div>


                                    <input type="hidden" name="MM_insert" value="loginpsmt">
                            </form>
                        </div>
                        <!-- /box_form -->
                    </div>
                    <!-- /col -->
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /hero_register -->
    </main>
    <!-- /main -->

    <footer>
        <div class="container margin_60_35">

            <!--/row-->
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <ul id="additional_links">
                        <li><a href="#0">Terms and conditions</a></li>
                        <li><a href="#0">Privacy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div id="copy">© 2018 PSMT</div>
                </div>
            </div>
        </div>
    </footer>
    <!--/footer-->
</div>
<!-- page -->

<div id="toTop"></div>
<!-- Back to top button -->

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<!-- COMMON SCRIPTS -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/common_scripts.min.js"></script>
<script src="js/functions.js"></script>

<!-- SPECIFIC SCRIPTS -->
<script src="assets/validate.js"></script>
<script type="text/javascript">
    var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn: ["change"]});
    var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email", {
        validateOn: ["change"],
        minChars: 5
    });
    var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {minChars: 4});
</script>
</body>
</html>
