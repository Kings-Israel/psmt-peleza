<html>
  <head>
    <title>Google recapcha demo - Codeforgeek</title>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
  </head>
  <body>
    <h1>Google reCAPTHA Demo</h1>
    <form id="comment_form" action="form.php" method="post">
      <input type="email" placeholder="Type your email" size="40"><br><br>
      <textarea name="comment" rows="8" cols="39"></textarea><br><br>
      <input type="submit" name="submit" value="Post comment"><br><br>
      <div class="g-recaptcha" data-sitekey="=== Your site key ==="></div>
    </form>
  </body>
</html>

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
<div id="preloader" class="Fixed">
    <div data-loader="circle-side"></div>
</div>
<!-- /Preload-->

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
                                <span class="fa-stack"><i class="icon-comment-inv-alt2 fa-stack-1x"></i></span>
                                Make Request
                                <span class="fa-stack"><i class="icon-angle-right fa-stack-1x"></i></span>
                            </a>
                        </li>
                        <li>
                            <a href="#0" class="mm-active">
                                <span class="fa-stack"><i class="icon-mail-6 fa-stack-1x"></i></span>
                                Bulk Request
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
                            <a href="testapi.php">
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
            <?php include 'partials/app_inner.php'; ?>

            <!--Body-->
            <div class="container margin_60">
                <div class="row">
                    <?php
                    $response = array(
                        'status' => 0,
                        'message' => 'Form submission failed, please try again.'
                    );
                    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "requestform")) {

                        $colname_getpackagecost = mysqli_real_escape_string($connect, $_POST['colname_getpackagecost']);

                        $STAFF_ID = $_SESSION['MM_Username'];
                        date_default_timezone_set('Africa/Nairobi');
                        $date_insert = date('Y-m-d h:i:s');

                        $uploadedby = mysqli_escape_string($connect,$_SESSION['MM_full_names']);

                        $MM_client_id = $_SESSION['MM_client_id'];

                        $request_plan =mysqli_real_escape_string($connect, $_POST['request_plan']);
                        $request_ref_number = mysqli_real_escape_string($connect, $_POST['request_ref_number']);
                        $document_numbers = mysqli_real_escape_string($connect, $_POST['document_numbers']);

                        $bg_dataset_name = mysqli_real_escape_string($connect, strtoupper($_POST['bg_dataset_name']));

                        $bg_dataset_mobile = mysqli_real_escape_string($connect, $_POST['bg_dataset_mobile']);
                        $bg_dataset_email = mysqli_real_escape_string($connect, $_POST['bg_dataset_email']);
                        $dataset_citizenship = mysqli_real_escape_string($connect, $_POST['dataset_citizenship']);

                        $MM_client_login_id =   $_SESSION['MM_client_login_id'];
                        $MM_client_parent_company = $_SESSION['MM_client_parent_company'];

                        $date_insert2 = date('dmYhis');
                        $BLOCKCHAIN = "TRACK".$date_insert2."".$STAFF_ID."".$request_ref_number;

                        //get package id
                        $query_getpackagename2 = "SELECT pel_package.package_id, pel_dataset.dataset_name, pel_dataset.dataset_type 
                                                    FROM pel_package Inner Join pel_dataset ON pel_dataset.dataset_id = pel_package.dataset_id 
                                                    WHERE pel_package.package_name = '$request_plan'";
                        $getpackagename2 = mysqli_query($connect,$query_getpackagename2) or die(mysqli_error($connect));
                        $row_getpackagename2 = mysqli_fetch_assoc($getpackagename2);
                        $totalRows_getpackagename2 = mysqli_num_rows($getpackagename2);

                        $package_id= $row_getpackagename2['package_id'];
                        $dataset_name = $row_getpackagename2['dataset_name'];
                        $dataset_type = $row_getpackagename2['dataset_type'];


                        $sql_insert="INSERT INTO pel_psmt_request (request_plan,bg_dataset_name,request_ref_number,client_id,request_date,
                                        dataset_citizenship,request_dataset_cat,company_name, client_name, bg_dataset_email, bg_dataset_mobile, file_tracker, 
                                        request_package, dataset_name, request_type, client_login_id)
                                        VALUES ('$request_plan','$bg_dataset_name','$request_ref_number','$MM_client_id','$date_insert','$dataset_citizenship',
                                        '$request_plan','$MM_client_parent_company','$uploadedby','$bg_dataset_email','$bg_dataset_mobile','$BLOCKCHAIN',
                                        '$package_id', '$dataset_name','$dataset_type','$MM_client_login_id')";


                        //	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());

                        $result = mysqli_query($connect,$sql_insert) or die('Query failed: ' . mysqli_error($connect));

                        if(isset($_POST['modules']))
                        {
                            foreach($_POST['modules'] as $selected){

                                $query_getmodules = sprintf("SELECT module_name, module_id FROM pel_packages_module WHERE package_id = %s and module_id= %s ", GetSQLValueString($package_id, "int"), GetSQLValueString($selected, "int"));
                                $getmodules = mysqli_query($connect,$query_getmodules) or die(mysqli_error());
                                $row_getmodules = mysqli_fetch_assoc($getmodules);
                                $totalRows_getmodules = mysqli_num_rows($getmodules);

                                $module_name= $row_getmodules['module_name'];
                                $module_id= $row_getmodules['module_id'];

                                $sql_insert2="INSERT INTO pel_psmt_request_modules (request_ref_number,status,client_id,package_id,package_name, module_name, request_type, module_id) 
                                                VALUES ('$request_ref_number','00','$MM_client_id','$package_id','$request_plan','$module_name','$dataset_type','$module_id')";

                                $result2 = mysqli_query($connect,$sql_insert2) or die('Query failed: ' . mysqli_error($connect));


                            }
                        }
                        else
                        {
                            //get modules
                            $query_getmodules = sprintf("SELECT module_name, module_id FROM pel_packages_module WHERE package_id = %s", GetSQLValueString($package_id, "int"));
                            $getmodules = mysqli_query($connect,$query_getmodules) or die(mysqli_error($connect));
                            $row_getmodules = mysqli_fetch_assoc($getmodules);
                            $totalRows_getmodules = mysqli_num_rows($getmodules);
                            do
                            {
                                $module_name= $row_getmodules['module_name'];
                                $module_id= $row_getmodules['module_id'];

                                $sql_insert3="INSERT INTO pel_psmt_request_modules (request_ref_number,status,client_id,package_id,package_name, module_name, request_type, module_id) VALUES ('$request_ref_number','00','$MM_client_id','$package_id','$request_plan','$module_name','$dataset_type','$module_id')";
                                //	$result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());

                                $result3 = mysqli_query($connect,$sql_insert3) or die('Query failed: ' . mysqli_error($connect));

                            }while ($row_getmodules = mysqli_fetch_assoc($getmodules));

                        }

                        $togetmoduledetails = $_FILES['consentform']['name'];
                        $tmpFilePath = $_FILES['consentform']['tmp_name'];

                        //$consentform = strtolower(end(explode('.', $togetmoduledetails)));
                        $aconsentform = $STAFF_ID."_".$date_insert2;
                        "Upload: ".$aconsentform."_". $togetmoduledetails;
                        $rawnameconsentform = $togetmoduledetails;
                        $fileconsentform="uploads/".$aconsentform."_". $togetmoduledetails;
                        move_uploaded_file($tmpFilePath, "uploads/".$aconsentform."_". $togetmoduledetails);

                        $filenameuploadedconsentform = $aconsentform."_".$togetmoduledetails;

                        $sql_insert4="INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type)
                                        VALUES ('$filenameuploadedconsentform','Consent Form','$BLOCKCHAIN','$BLOCKCHAIN','$MM_client_id','file')";

                        $result4 = mysqli_query($connect,$sql_insert4) or die('Query failed: ' . mysqli_error($connect));




                        $query_getmoduledocs = sprintf("SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id
                                                FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id 
                                                WHERE package_id = %s ORDER BY pel_module_documents.module_doc_id ASC", GetSQLValueString($package_id, "int"));
                        $getmoduledocs = mysqli_query($connect,$query_getmoduledocs) or die(mysqli_error($connect));
                        $row_getmoduledocs = mysqli_fetch_assoc($getmoduledocs);
                        $totalRows_getmoduledocs = mysqli_num_rows($getmoduledocs);

                        do
                        {
                            $document_name = $row_getmoduledocs['document_name'];
                            $data_type = $row_getmoduledocs['data_type'];
                            $datafile = "datafile_".$row_getmoduledocs['module_doc_id'];




                            // Count total files
                            $countfiles = count($_FILES['files']['name']);
                            // Upload directory
                            $upload_location = "uploads/";
                            // To store uploaded files path
                            $files_arr = array();
                            // Loop all files
                            for($index = 0;$index < $countfiles;$index++){

                                // File name
                                $filename = $_FILES['files']['name'][$index];

                                // Get extension
                                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                                // Valid image extension
                                $valid_ext = array("png","jpeg","jpg", "pdf");

                                // Check extension
                                if(in_array($ext, $valid_ext)){

                                    // File path
                                    $path = $upload_location.$filename;

                                    // Upload file
                                    if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path)){
                                        $files_arr[] = $path;
                                    }
                                }

                            }

                            echo json_encode($files_arr);






                            if(isset($_FILES[$datafile]['tmp_name']) && $data_type == 'file')
                            {

                                $tmpFilePath = $_FILES[$datafile]['tmp_name'];

                                if ($tmpFilePath != ""){

                                    $togetmoduledetails = $_FILES[$datafile]['name'];

                                    //$consentform = strtolower(end(explode('.', $togetmoduledetails)));
                                    $aconsentform = $STAFF_ID."_".$date_insert2;
                                    "Upload: ".$aconsentform."_". $togetmoduledetails;
                                    $rawnameconsentform = $togetmoduledetails;
                                    $fileconsentform="uploads/".$aconsentform."_". $togetmoduledetails;
                                    move_uploaded_file($tmpFilePath,

                                        "uploads/".$aconsentform."_". $togetmoduledetails);

                                    $filenameuploadedconsentform = $aconsentform."_".$togetmoduledetails;

                                    $sql_insert5="INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type)
                                                    VALUES ('$filenameuploadedconsentform','$document_name','$BLOCKCHAIN','$BLOCKCHAIN','$MM_client_id','file')";

                                    $result5 = mysqli_query($connect,$sql_insert5) or die('Query failed: ' . mysqli_error($connect));
                                }
                            }


                            if(isset($_POST[$datafile]) && $data_type == 'text')
                            {

                                $tmpFilePath = $_POST[$datafile];

                                if ($tmpFilePath != ""){



                                    $sql_insert6="INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type) 
                                                    VALUES ('$tmpFilePath','$document_name','$BLOCKCHAIN','$BLOCKCHAIN','$MM_client_id','text')";

                                    $result6 = mysqli_query($connect,$sql_insert6) or die('Query failed: ' . mysqli_error($connect));
                                }



                            }
                        }
                        while ($row_getmoduledocs = mysqli_fetch_assoc($getmoduledocs));



                        $toemail= "verify@peleza.com";

                        require ("PHPMailer/PHPMailer.php");

                        require("PHPMailer/SMTP.php");
                        require("PHPMailer/Exception.php");

                        $mail = new PHPMailer\PHPMailer\PHPMailer();

                        $mail->isSMTP();
                        $mail->Host = 'eleven.deepafrica.com';             // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                     // Enable SMTP authentication
                        $mail->Username = 'admin@pidva.africa';          // SMTP username
                        $mail->Password = 'Gu!Z@JMu*ann'; // SMTP password
                        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 587;                           // TCP port to connect to

                        $mail->setFrom('admin@pidva.africa', 'Background Checks>> Peleza International');

                        $mail->addAddress($toemail);
//$mail->addAddress('omintolbert@gmail.com');   // Add a recipient
//$mail->addCC('omintolbert@gmail.co');
//$mail->addBCC('omintolbert@gmail.com');

                        $mail->isHTML(true);  // Set email format to HTML

                        $bodyContent = '<p><img src="https://psmt.pidva.africa/img/Peleza_Logo_We_Get_It.png" width="166" height="60" /></p>
                                        <p><strong>Hi Verification Team,</strong></p>
                                        <p>New Request has been Submitted from PSMT.</p><br/><br/>
                                        <p>NAME:  '.$bg_dataset_name.'</p><br/><br/>
                                        <p>COMPANY:   '.$MM_client_parent_company.'</p><br/><br/>
                                         
                                        <p>  - The Peleza Team<br />
                                          Support: +254 796 111 020 or +254  Email:&nbsp;<a href="mailto:verify@peleza.com">verify@peleza.com</a>&nbsp;<br />
                                          ® Peleza Int, 2018. All rights reserved. </p>';
                        //$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

                        $mail->Subject = 'Confidential Background Check Request';
                        $mail->Body    = $bodyContent;

                        $mail->send();

                        $response['status'] = 200;
                        $response['message'] = 'Form data submitted successfully!';


                        //	}while ($row_getrecord = mysqli_fetch_assoc($getrecord));

                    }

                    // Return response
                    echo json_encode($response);

                    ?>

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

<!-- Submit form -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


<!-- COMMON SCRIPTS -->


<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/common_scripts.min.js"></script>
<script src="js/functions.js"></script>
</body>
</html>

<script>
    $(document).ready(function(){

        $('#submit').click(function(){

            var form_data = new FormData();

            // Read selected files
            var totalfiles = document.getElementById('files').files.length;
            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            // AJAX request
            $.ajax({
                url: 'requestpost',
                type: 'post',
                data: form_data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (response) {

                    for(var index = 0; index < response.length; index++) {
                        var src = response[index];

                        // Add img element in <div id='preview'>
                        $('#preview').append('<img src="'+src+'" width="200px;" height="200px">');
                    }

                }
            });

        });

    });

</script>