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
$colname_getpackagecost = filter_var($_GET['package_id'], FILTER_SANITIZE_INT);
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
                            <a href="#0" class="mm-active">
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

                ul.tri {
                    margin: 0.75em 0;
                    padding: 0 1em;
                    list-style: none!important;
                }
                li.tri:before {
                    content: "";
                    border-color: transparent #255468;
                    border-style: solid;
                    border-width: 0.35em 0 0.35em 0.45em;
                    display: block;
                    height: 0;
                    width: 0;
                    left: -1em;
                    top: 0.9em;
                    position: relative;
                }

            </style>
            <div class="container margin_60">
                <div class="row">
                    <?php
                    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "requestform")) {
                        $colname_getpackagecost = $_POST['package_id'];
                        $query_getpackagecost = sprintf("SELECT pel_package.package_id, pel_package.package_name, pel_package.package_cost, pel_package.package_status, pel_package.package_added_by, pel_package.package_added_date, pel_package.package_data, pel_package.package_verified_by, pel_package.package_verified_date, pel_package.dataset_id, pel_package.package_currency, pel_package.package_min, pel_package.package_max, pel_package.package_credits, pel_package.package_general, pel_dataset.dataset_name, pel_dataset.dataset_type FROM pel_package Inner Join pel_dataset ON pel_dataset.dataset_id = pel_package.dataset_id WHERE pel_package.package_id = %s", GetSQLValueString($colname_getpackagecost, "int"));
                        $getpackagecost = mysqli_query($connect,$query_getpackagecost) or die(mysqli_error());
                        $row_getpackagecost = mysqli_fetch_assoc($getpackagecost);
                        $totalRows_getpackagecost = mysqli_num_rows($getpackagecost);
                        mysqli_free_result($getpackagecost);

                        if(!empty($_POST['visit'])){
                            // Loop to store and display values of individual checked checkbox.

                            $total_cost = 0;

                            foreach($_POST['visit'] as $selected){
                                $togetmoduledetails=$selected;
                                $query_getpackagemodules = sprintf("SELECT pel_module.module_name, pel_packages_module.cost_currency,
                        pel_packages_module.module_cost, pel_packages_module.module_id FROM pel_packages_module Inner Join pel_module ON  pel_packages_module.module_id = pel_module.module_id WHERE pel_packages_module.package_id =%s and pel_module.module_id =%s ORDER BY pel_module.module_name ASC ", GetSQLValueString($colname_getpackagecost, "int"),GetSQLValueString($togetmoduledetails, "text"));
                                $getpackagemodules = mysqli_query($connect,$query_getpackagemodules) or die(mysqli_error());
                                $row_getpackagemodules = mysqli_fetch_assoc($getpackagemodules);
                                $totalRows_getpackagemodules = mysqli_num_rows($getpackagemodules);

                                $total_cost= $total_cost + $row_getpackagemodules['module_cost'];

                            }
                            ?>

                            <div class="">
                                <div class="col-sm-12">
                                    <div class="box_general_3 cart bg-green">
                                        <div><h5><span class="fa-stack"><i class="icon-comment fa-stack-1x"></i></span><b>&nbsp;Bulk Request</b></h5></div>
                                        <div class="col-12" style="background-color: darkolivegreen; color: white!important; padding: 8px; margin: 0!important;"><strong><?php echo $row_getpackagecost['package_name']; ?></strong></div>

                                        <div class="container">
                                            <form class="form-horizontal m-t-40" id="blog" name="requestform" ENCTYPE='multipart/form-data'>
                                                <input type="hidden" id="package_id" name="package_id" class="form-control" value="<?php echo $row_getpackagecost['package_id']; ?>">
                                                <div style="color: #255468!important;" class="col-12">
                                                    <div class="box_general_3 cart bg-green" >

                                                        <div class="row"><h5>SELECTED PACKAGES</h5></div>
                                                        <hr class="divider">
                                                        <ul class="tri">
                                                            <?php
                                                            $x=1;

                                                            foreach($_POST['visit'] as $selected){
                                                                $togetmoduledetails=$selected;

                                                                $query_getpackagemodules = sprintf("SELECT pel_module.module_name FROM pel_packages_module Inner Join pel_module ON  pel_packages_module.module_id = pel_module.module_id WHERE pel_packages_module.module_id = %s ORDER BY pel_module.module_name ASC ", GetSQLValueString($togetmoduledetails, "int"));
                                                                $getpackagemodules = mysqli_query($connect,$query_getpackagemodules) or die(mysqli_error($connect));
                                                                $row_getpackagemodules = mysqli_fetch_assoc($getpackagemodules);
                                                                $totalRows_getpackagemodules = mysqli_num_rows($getpackagemodules);

                                                                ?>
                                                                <li class="tri"><strong><?php echo $x++;?></strong> <?php echo $row_getpackagemodules['module_name']; ?></li>
                                                            <?php }  ?>
                                                        </ul>
                                                        <div class="row spacer"><h5>DATASET DETAILS</h5></div>
                                                        <hr class="divider">
                                                        <div class="row spacer">
                                                            <div class="col-xl-4 col-lg-4">
                                                                <label>Request Reference No</label>
                                                                <input value="<?php echo $_SESSION['MM_client_company_id']."-RQ-".GeraHash2(4)."-".date('dmyhis'); ?>" type="text" id="request_ref_number" name="request_ref_number" class="form-control" readonly/>
                                                                <input type="hidden" id="request_plan" name="request_plan" placeholder="&#xF002;" class="iconified empty col-xs-10 col-sm-5"  value="<?php echo $row_getpackagecost['package_name']; ?>" />
                                                                <input type="hidden" id="colname_getpackagecost" name="colname_getpackagecost" placeholder="Text Field" class="col-xs-10 col-sm-5"  value="<?php echo $colname_getpackagecost; ?>" />
                                                            </div>
                                                            <div class="col-xl-4 col-lg-4">
                                                                <?php  if($row_getpackagecost['dataset_type'] =='COMPANY') { ?>
                                                                    <label>Name of Company</label>
                                                                <?php } else { ?>
                                                                    <label>Name of Candidate</label>
                                                                <?php } ?>

                                                                <input type="text" id="bg_dataset_name" name="bg_dataset_name" placeholder="&#xf007;" class="iconified empty form-control mb-2">
                                                            </div>
                                                            <div class="col-xl-4 col-lg-4">
                                                                <label>Email</label>
                                                                <input type="email" id="bg_dataset_email" name="bg_dataset_email" placeholder="&#xf003;" class="iconified empty form-control mb-2">
                                                            </div>
                                                            <div class="col-xl-4 col-lg-4">
                                                                <label>Telephone</label>
                                                                <input type="text" id="bg_dataset_mobile" name="bg_dataset_mobile" placeholder="&#xf095;" class="iconified empty form-control mb-2">
                                                            </div>
                                                            <div class="col-xl-4 col-lg-4">
                                                                <?php  if($row_getpackagecost['dataset_type'] =='COMPANY') { ?>
                                                                    <label>Country of Registration</label>
                                                                    <select class="custom-select form-control required" id="dataset_citizenship" name="dataset_citizenship"  required>
                                                                        <option value="KENYA">KENYA</option>
                                                                        <?php do { ?>
                                                                            <option value="<?php echo $row_citizenship['country_name']; ?>"><?php echo $row_citizenship['country_name']; ?></option>
                                                                        <?php } while ($row_citizenship = mysqli_fetch_assoc($citizenship)); ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <label>Select Citizenship</label>
                                                                    <select class="custom-select form-control required" id="dataset_citizenship" name="dataset_citizenship"  required>
                                                                        <option value="KENYAN">KENYAN</option>
                                                                        <?php do { ?>
                                                                            <option value="<?php echo $row_citizenship['country_nationality']; ?>"><?php echo $row_citizenship['country_nationality']; ?></option>
                                                                        <?php } while ($row_citizenship = mysqli_fetch_assoc($citizenship)); ?>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="row spacer"><h5>DOCUMENT AND DATA UPLOAD</h5></div>
                                                        <hr class="divider">
                                                        <div class="row spacer" id="">
                                                            <div class="col-md-4 col-sm-12">
                                                                <div class="form-group">
                                                                    <label>1. Consent Form </label>
                                                                    <input accept=".gif,.jpg,.jpeg,.png,.doc,.docx,.pdf" type="file" placeholder="&#xf093;" class="iconified empty form-control required" id="consentform" name="consentform">
                                                                </div>
                                                            </div>

                                                            <?php
                                                            $x=2;
                                                            foreach($_POST['visit']  as $selected){
                                                                $togetmoduledetails=$selected;
                                                                ?>
                                                                <input type="hidden" id="modules[]" name="modules[]" class="form-control" value="<?php echo $togetmoduledetails; ?>">
                                                                <?php
                                                                $query_getmoduledocs = sprintf("SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id
                                                                                        FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id 
                                                                                        WHERE pel_packages_module.module_id = %s", GetSQLValueString($togetmoduledetails, "int"));
                                                                $getmoduledocs = mysqli_query($connect,$query_getmoduledocs) or die(mysqli_error());
                                                                $row_getmoduledocs = mysqli_fetch_assoc($getmoduledocs);
                                                                $totalRows_getmoduledocs = mysqli_num_rows($getmoduledocs);
                                                                if(	$totalRows_getmoduledocs>0) {
                                                                    ?>
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label><?php echo $x;?>. <?php echo $row_getmoduledocs['document_name']; ?> </label>
                                                                            <input <?php if($row_getmoduledocs['data_type']=='file'){?>accept=".gif,.jpg,.jpeg,.png,.doc,.docx,.pdf"<?php } ?> type="<?php echo $row_getmoduledocs['data_type']; ?>" placeholder="&#xf093;" class="iconified empty file_allowed form-control required" id="datafile_<?php echo $row_getmoduledocs['module_doc_id']; ?>" name="datafile_<?php echo $row_getmoduledocs['module_doc_id']; ?>" <?php if($row_getmoduledocs['mandatory_status']=='11'){?>required<?php }?>>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $y = $x++;
                                                                }
                                                            }
                                                            ?>

                                                            <input type="hidden" id="document_numbers" name="document_numbers" class="form-control" value="<?php echo $y;?>">
                                                        </div>
                                                        <input type="hidden" id="document_numbers" name="document_numbers" class="form-control" value="<?php echo $y;?>">
                                                        <div class="row spacer"><h5>TERMS AND CONDITIONS</h5></div>
                                                        <hr class="divider">
                                                        <div class="row spacer">
                                                            <div class="col-12">
                                                                <label><input type="checkbox" name="terms" id="terms"> I accept terms and conditions and general policy of Background Screening Request. I also accept that the data set above mentioned has given consent for us to conduct background screening.</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end of 1st col-12 -->

                                                <!--Submit buttons-->
                                                <div class="col-12">
                                                    <div class="box_general_3 cart bg-green">
                                                        <div class="row">
                                                            <input type="hidden" name="MM_insert" value="requestform">
                                                            <button style="color: #d8e95d!important;" type="submit" class="btn btn-primary btn-lg float-right submitBtn">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Box -->
                            </div>
                            <!-- /col -->
                        <?php } else { ?>

                            <div class="col-xl-12 col-lg-12">
                                <div class="box_general_3 cart">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-12">
                                                <div id="confirm">
                                                    <div class="icon icon--order-success svg add_bottom_15">
                                                        <img src="img/warningsign.png" alt="noresultssign">
                                                    </div>
                                                    <h2>Please Select atleast one Background Screening Check to make a successfull request</h2>
                                                    <hr>
                                                    <div style="position:relative;"><a href="request.php?package_id=<?php echo $colname_getpackagecost;?>"><input type="submit" class="btn_1 full-medium" value="TRY AGAIN" id="submit-booking"></a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /row -->
                                    </div>
                                    <!-- /container -->
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->

            <!--Footer-->
            <?php include 'partials/footer.php'; ?>
        </div>
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
<!-- Back to top button -->

<script src="assets/socket.js"></script>


<!-- COMMON SCRIPTS -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/common_scripts.min.js"></script>
<script src="js/functions.js"></script>

<!-- Submit form -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function(e){
        // Submit form data via Ajax
        $("#blog").on('submit', function(e){
            e.preventDefault();

            $('#exampleModalCenter').modal('show');
            $('.statusMsg').html("<b>Loading response...</b>");

            $.ajax({
                type: 'POST',
                url: 'requestpost.php',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('.submitBtn').attr("disabled","disabled");
                    $('#blog').css("opacity",".5");
                },
                success: function(response){ //console.log(response);
                    $('.statusMsg').html('');
                    if(response.status == 200){
                        $('.statusMsg').html('<div>'+response.message+'</div>');
                    }else{
                        $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');
                    }
                    $('#blog')[0].reset();
                    $('#blog').css("opacity", ".2");
                    $('#checks').css("opacity", ".2");
                    $("#newRequest").attr("style", "display:block")
                }
            });
        });

        // File type validation
        $(".file_allowed").change(function() {
            var file = this.files[0];
            var fileType = file.type;
            var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
            if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))){
                alert('Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.');
                $(".file_allowed").val('');
                return false;
            }
        });
    });

</script>


</body>
</html>