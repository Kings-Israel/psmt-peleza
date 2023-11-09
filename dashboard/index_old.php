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
	<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" type="image/x-icon" href="../img/apple-touch-icon-57x57-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="../img/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="../img/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="../img/apple-touch-icon-144x144-precomposed.png">

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


	<!-- /Preload-->
	
	<div id="page">		
	<header class="static">	
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
						<a href="index.php" title="PSMT"><img src="../img/Peleza_Logo_We_Get_It.png" data-retina="true" alt="" width="163" height="36"></a>
				  </div>
				</div>
				<div class="col-lg-9 col-6">
					<ul id="top_access">
                        <li><a href="<?php echo $logoutAction ?>" class="btn_1 small2"><i class="icon-logout" style="font-size:15px"></i><span id="mybuttontext">SIGN OUT</span></a></li>
				  </ul>
					<nav id="menu" class="main-menu">
						<ul>
                            <li><span><a href="index.php"  class="active">Dashboard</a></span></li>
							
                            <li>
								<span><a href="../request.php">Make a Request</a></span>
								<ul>
				           <?php


$query_getpackagenames = sprintf("SELECT package_id, package_name, client_id FROM pel_client_package where client_id = %s ", GetSQLValueString($client_id_get_psmt_requests, "int"));
$getpackagenames = mysqli_query($connect,$query_getpackagenames) or die(mysqli_error());
$row_getpackagenames = mysqli_fetch_assoc($getpackagenames);
$totalRows_getpackagenames = mysqli_num_rows($getpackagenames);
$queryitem = "(";
if($totalRows_getpackagenames > '0')
{

					?>
<?php
								$x = 1;
								do {

					//$queryitem .= " request_plan = '".$row_getpackagenames['package_name']."'";

					$queryitem .= "'".$row_getpackagenames['package_name']."',";
								?>	<li><a href="../request.php?package_id=<?php echo $row_getpackagenames['package_id']; ?>"> <?php echo $row_getpackagenames['package_name']; ?> </a></li>
						  <?php

						  $x++;
						  } while ($row_getpackagenames = mysqli_fetch_assoc($getpackagenames));


						  ?>

   <?php
   }
   else

  {

$query_getpackagegeneral = sprintf("SELECT package_id, package_name FROM pel_package where package_general = '11'");
$getpackagegeneral = mysqli_query($connect,$query_getpackagegeneral) or die(mysqli_error($connect));
$row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral);
$totalRows_getpackagegeneral = mysqli_num_rows($getpackagegeneral);

$queryitem .= "";
	$x = 1;
								do {
								$queryitem .= "'".$row_getpackagegeneral['package_name']."',";
								?>	<li><a href="../request.php?package_id=<?php echo $row_getpackagegeneral['package_id']; ?>"> <?php echo $row_getpackagegeneral['package_name']; ?> </a></li>
						<?php
						  } while ($row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral));


						  ?>

<?php
 }
 $queryitem .= "'')";
   ?>

								</ul>
							</li>
                            
                            
                            
							<li><span><a href="../reports/index.php">Reports</a></span></li>
							<li><span><a href="../cart/cart.php">My Cart</a></span></li>
<li><span><a href="../payments.php">Payments</a></span></li>
							<li><span><a href="../downloads.php">Downloads</a></span></li>                            
                            <li><span><a href="../faq.php">FAQs</a></span></li>
                            <li><span><a href="../testapi.php">APIs</a></span></li>
                            <li><span><a href="../profile.php">My Profile</a></span></li>
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
   
   <div id="results">
       <div class="container">
           <div class="row">
               <div class="col-md-8"> <?php
			   
			   				   
				   
$query_getcompany = sprintf("SELECT company_logo FROM pel_client_co where company_code = %s ", GetSQLValueString($client_login_id_get_psmt_requests, "text"));
$getcompany = mysqli_query($connect,$query_getcompany) or die(mysqli_error());
$row_getcompany = mysqli_fetch_assoc($getcompany);
$totalRows_getcompany = mysqli_num_rows($getcompany);	
			   ?>	
                 <table class="logotable"><tr><td bgcolor="#FFFFFF" style="-webkit-border-radius: 5%;
  -moz-border-radius: 5%;
  -ms-border-radius: 5%;
  border-radius: 5%; padding:3px;" >
                   <?php


if($row_getcompany ['company_logo']!= '')
{
	
		
				   ?>
							<a href="#"><img src="https://psmt.pidva.africa/clients/logoimages/<?php echo $row_getcompany['company_logo']; ?>" width="50px" height="50px" alt=""  class="img-fluid"></a>
                  <?php
}  
else
{
	?><img src="../img/nologo.png"  width="50px" height="50px" alt=""  class="img-fluid">

<?php
}
?></td><td style="padding-left:20px;"><h4>  
<strong>Welcome, </strong> <?php echo 	$_SESSION['MM_first_name']; ?> ! <strong><br/>CLIENT ID: </strong><?php echo 	$_SESSION['MM_client_company_id']; ?> </h4>
          </td></tr></table></div>
              	<div class="col-md-4">
						<div class="search_bar_list">
                         <form name="formsearch" action="<?php echo $editFormAction; ?>" method="POST" >
							<input id="searchparameter" type="text" class="form-control" placeholder="Ex. Name, Ref Number ...." name="searchparameter" required>
							<input type="submit" value="Search">
                                  <input type="hidden" name="MM_insert" value="formsearch">
                            </form>
						</div>
					</div>			
           </div>
           <!-- /row -->
       </div>
       <!-- /container -->
   </div>
   <!-- /results -->
   
   <div class="filters_listing">
		<div class="container">
			<ul class="clearfix">
            
            <li>
                 
					<h6>Sort by</h6>
                  
                    
                    <form name="formc" action="<?php echo $editFormAction; ?>" method="POST" >
					<select name="status_search" class="selectbox" required>	
                  <?php  
        if ((isset($_POST["MM_insert"])) && (filter_var($_POST["MM_insert"], FILTER_SANITIZE_STRING) == "formc")) {
					
				echo	$status_search = filter_var($_POST['status_search'], FILTER_SANITIZE_STRING);	
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
					<h6>Type</h6>
                    <?php


$query_getpackagenames = sprintf("SELECT package_id, package_name, client_id FROM pel_client_package where client_id = %s ", GetSQLValueString($client_id_get_psmt_requests, "int"));
$getpackagenames = mysqli_query($connect,$query_getpackagenames) or die(mysqli_error());
$row_getpackagenames = mysqli_fetch_assoc($getpackagenames);
$totalRows_getpackagenames = mysqli_num_rows($getpackagenames);	
$queryitem = "(";
if($totalRows_getpackagenames > '0')
{			
					
					?>
<div class="switch-field">
						<input type="radio" id="all" name="type_patient" value="all" checked>
						<label for="all">CHOOSE PACKAGE:</label>
                        
                        		<?php 
								$x = 1;
								do { 
															
					//$queryitem .= " request_plan = '".$row_getpackagenames['package_name']."'";
					
					$queryitem .= "'".$row_getpackagenames['package_name']."',";
								?>	<a href="../request.php?package_id=<?php echo $row_getpackagenames['package_id']; ?>">	
		
						<label for="<?php echo $row_getpackagenames['package_name']; ?>"><?php echo $row_getpackagenames['package_name']; ?></label>		</a>
						  <?php 
						  if($x%4==0)
						  {
						  ?>
                          <br/>
                          <?php
						  }
						
						  $x++;
						  } while ($row_getpackagenames = mysqli_fetch_assoc($getpackagenames)); 
						  
					
						  ?>
				  </div>
   <?php
   }
   else
   
  {
  
$query_getpackagegeneral = sprintf("SELECT package_id, package_name FROM pel_package where package_general = '11'");
$getpackagegeneral = mysqli_query($connect,$query_getpackagegeneral) or die(mysqli_error());
$row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral);
$totalRows_getpackagegeneral = mysqli_num_rows($getpackagegeneral);	

$queryitem .= "";
  ?>

					<div class="switch-field">
						<input type="radio" id="all" name="type_patient" value="all" checked>
						<label for="all">NEW REQUEST:</label>
						<?php 
								$x = 1;
								do { 
								$queryitem .= "'".$row_getpackagegeneral['package_name']."',";
								?>	<a href="../request.php?package_id=<?php echo $row_getpackagegeneral['package_id']; ?>">	
		
						<label for="<?php echo $row_getpackagegeneral['package_name']; ?>"><?php echo $row_getpackagegeneral['package_name']; ?></label>		</a>
						<?php
						  } while ($row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral)); 
						  

						  ?>
					</div>
<?php
 }
 $queryitem .= "'')";
   ?>               
				</li>
				<!--<li>
					<h6>Layout</h6>
					<div class="layout_view">
						<a href="#0" class="active"><i class="icon-th"></i></a>
						<a href="dashboardlist.php"><i class="icon-th-list"></i></a>
					
					</div>
				</li>-->
				
			</ul>
		</div>
		<!-- /container -->
	</div>
	<!-- /filters -->
	   
	<div class="container margin_60">
   <?php
		 
					        if ((isset($_POST["MM_insert"])) && (filter_var($_POST["MM_insert"], FILTER_SANITIZE_STRING) == "formsearch")) {
							if(filter_var($_POST['searchparameter'], FILTER_SANITIZE_STRING) != "")
							{
					$searchparameter = filter_var($_POST['searchparameter'], FILTER_SANITIZE_STRING);	
					$maxRows_get_psmt_requests = 1000;
$pageNum_get_psmt_requests = 0;
if (isset($_GET['pageNum_get_psmt_requests'])) {
  $pageNum_get_psmt_requests = filter_var($_GET['pageNum_get_psmt_requests'], FILTER_SANITIZE_STRING);
}
$startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;
						
$query_get_psmt_requests = "SELECT * FROM pel_psmt_request WHERE client_login_id = '$client_login_id_get_psmt_requests' and (bg_dataset_name LIKE '%$searchparameter%' OR request_ref_number LIKE '$searchparameter') AND request_plan IN ".$queryitem."  ORDER BY request_date DESC";
$query_limit_get_psmt_requests = sprintf("%s LIMIT %d, %d", $query_get_psmt_requests, $startRow_get_psmt_requests, $maxRows_get_psmt_requests);
$get_psmt_requests = mysqli_query($connect,$query_limit_get_psmt_requests) or die(mysqli_error());
$row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests);

if (isset($_GET['totalRows_get_psmt_requests'])) {
  $totalRows_get_psmt_requests = filter_var($_GET['totalRows_get_psmt_requests'], FILTER_SANITIZE_STRING);
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

					?>

              <?php
   if($totalRows_get_psmt_requests > '0')
{	             
  ?>     	<div class="col-lg-12" style="background-color:#FFF; padding:10px">

			<table id="simple-table" width="100%" class="table table-striped table-bordered table-hover">
										<thead>
												<tr bgcolor="#0A4157">		
                        <th></th>							
					<!--	<th><font color="#FFFFFF"><strong>Photo:</strong></font></th>-->
                         <th><font color="#FFFFFF"><strong>Name:</strong></font></th>
                          <th><font color="#FFFFFF"><strong>Ref Number:</strong></font></th>
                   <!--      <th><font color="#FFFFFF"><strong>Request Plan:</strong></font></th>-->
                         <th><font color="#FFFFFF"><strong>Request Date:</strong></font></th>
              <!--           <th><font color="#FFFFFF"><strong>Dataset Name:</strong></font></th>-->
                         <th><font color="#FFFFFF"><strong>Status:</strong></font></th>
                         <th><font color="#FFFFFF"><strong>Progress:</strong></font></th>
                              <th><font color="#FFFFFF"><strong>Action:</strong></font></th>
                                          
                                                
                                          </tr>
                                                    </thead>  
					<?php
					$x = 1;
					do { ?>  
                    
                       <tr>
                                  <td><b><?php echo $x++; ?></b></td>
                  <!--   <td>
                     <?php
										if($row_get_psmt_requests['dataset_photo'] == '')
										{										
										?>
                                     <img src="img/nophoto.png"  width="50px" height="50px" alt=""  class="img-fluid">
                                        <?php
										}
										else
										{
										?>
									<a href="#"><img src="img/clientphotos/<?php echo $row_get_psmt_requests['dataset_photo']; ?>" width="50px" height="50px" alt=""  class="img-fluid"></a>
                          <?php
										  }
										?>
					</td>-->
				
					        <td> <a href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>"> <strong><?php echo $row_get_psmt_requests['bg_dataset_name']; ?></strong></a></td>
                     <td><?php echo $row_get_psmt_requests['request_ref_number']; ?></td>
                   <!--  <td><?php echo $row_get_psmt_requests['request_plan']; ?></td>-->
                      <td><?php echo $row_get_psmt_requests['request_date']; ?></td>
                  <!--    <td><?php echo $row_get_psmt_requests['dataset_name']; ?></td>-->
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
                       <td><b><span class="rating">   <?php
								  $refnumber = $row_get_psmt_requests['request_ref_number'];
								   
$query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
$getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
$row_getprogress2 = mysqli_fetch_assoc($getprogress2);
$totalRows_getprogress2 = mysqli_num_rows($getprogress2);	
$complete=0;
$all=0;
do
{
							if($row_getprogress2['statuscheck']=='11')
							{
						$complete++;
								  ?><i class="icon_star voted"></i>
                                  <?php
								  }
								if($row_getprogress2['statuscheck']=='00')
							{
								  ?>
                                 <i class="icon_star"></i>
                                  <?php
								  }
								  ?>
                             <?php
							 	$all++;
							  } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));
							 ?>     
                                  
                                   <small>(<?php echo round(($complete/$all)*100); ?>%)</small></span></b></td>
                               
                            <td><a class="btn_1 small_status_11" href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></td>    
                               
                               
                                </tr>
       
       <!-- <div class="col-lg-6">

					<div class="strip_list wow fadeIn">                   
                      <?php
										if($row_get_psmt_requests['dataset_photo'] == '')
										{										
										?>
                                        <figure><a href="#"><img src="img/nophoto.png" alt=""></a></figure>
                                        <?php
										}
										else
										{
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
                                   <td><b><span class="rating">   <?php
								  $refnumber = $row_get_psmt_requests['request_ref_number'];
								   
$query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
$getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
$row_getprogress2 = mysqli_fetch_assoc($getprogress2);
$totalRows_getprogress2 = mysqli_num_rows($getprogress2);	
$complete=0;
$all=0;
do
{
							if($row_getprogress2['statuscheck']=='11')
							{
						$complete++;
								  ?><i class="icon_star voted"></i>
                                  <?php
								  }
								if($row_getprogress2['statuscheck']=='00')
							{
								  ?>
                                 <i class="icon_star"></i>
                                  <?php
								  }
								  ?>
                             <?php
							 	$all++;
							  } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));
							 ?>     
                                  
                                   <small>(<?php echo round(($complete/$all)*100); ?>%)</small></span></b></td>
                                </tr>
                              </table>	
				
                        
                        	<ul>
							<li></li>
							<li></li>
							  <li><a href="viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></li>
						</ul>
					</div></div>-->
        
          <?php } while ($row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests)); ?>
               
           </table>
                
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
            
            
                <!--	<nav aria-label="" class="add_top_20">
					<ul class="pagination pagination-sm">
						<li class="page-item disabled">
							<a class="page-link" href="#" tabindex="-1">Previous</a>
						</li>
						<li class="page-item active"><a class="page-link" href="#">1</a></li>
						<li class="page-item"><a class="page-link" href="#">2</a></li>
						<li class="page-item"><a class="page-link" href="#">3</a></li>
						<li class="page-item">
							<a class="page-link" href="#">Next</a>
						</li>
					</ul>
				</nav>-->
				<!-- /pagination -->
			</div>
			<!-- /col -->
			
		<!--	<aside class="col-lg-4" id="sidebar">
				<div id="map_listing" class="normal_list">
				</div>
			</aside>-->
			<!-- /aside -->
	    <?php
  }
  else
  {
?>
	<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="../img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Their is no requests that are under the Search Parameter!</h2>
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
  else
  {          
  ?>  
<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="../img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Their is no requests that are under the Search Parameter!</h2>
					<!--<p>You'll receive a confirmation email at mail@example.com</p>-->
					</div>
				</div>
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
  
  <?php
  }

							
 }
     else   if ((isset($_POST["MM_insert"])) && (filter_var($_POST["MM_insert"], FILTER_SANITIZE_STRING) == "formc")) {
							
					$status_search = filter_var($_POST['status_search'], FILTER_SANITIZE_STRING);	
					$maxRows_get_psmt_requests = 100;
$pageNum_get_psmt_requests = 0;
if (isset($_GET['pageNum_get_psmt_requests'])) {
  $pageNum_get_psmt_requests = filter_var($_GET['pageNum_get_psmt_requests'], FILTER_SANITIZE_STRING);
}
$startRow_get_psmt_requests = $pageNum_get_psmt_requests * $maxRows_get_psmt_requests;
						
$query_get_psmt_requests = sprintf("SELECT * FROM pel_psmt_request WHERE client_login_id = %s and status= %s  AND request_plan IN ".$queryitem."  ORDER BY request_date DESC", GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_search, "text"));
$query_limit_get_psmt_requests = sprintf("%s LIMIT %d, %d", $query_get_psmt_requests, $startRow_get_psmt_requests, $maxRows_get_psmt_requests);
$get_psmt_requests = mysqli_query($connect,$query_limit_get_psmt_requests) or die(mysqli_error());
$row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests);

if (isset($_GET['totalRows_get_psmt_requests'])) {
  $totalRows_get_psmt_requests = filter_var($_GET['totalRows_get_psmt_requests'], FILTER_SANITIZE_STRING);
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

					?>

              <?php
   if($totalRows_get_psmt_requests > '0')
{	             
  ?>          <div class="col-lg-12" style="background-color:#FFF; padding:10px">

			<table id="simple-table" width="100%" class="table table-striped table-bordered table-hover">
										<thead>
												<tr bgcolor="#0A4157">		
                        <th></th>							
					<!--	<th><font color="#FFFFFF"><strong>Photo:</strong></font></th>-->
                         <th><font color="#FFFFFF"><strong>Name:</strong></font></th>
                          <th><font color="#FFFFFF"><strong>Ref Number:</strong></font></th>
                   <!--      <th><font color="#FFFFFF"><strong>Request Plan:</strong></font></th>-->
                         <th><font color="#FFFFFF"><strong>Request Date:</strong></font></th>
              <!--           <th><font color="#FFFFFF"><strong>Dataset Name:</strong></font></th>-->
                         <th><font color="#FFFFFF"><strong>Status:</strong></font></th>
                         <th><font color="#FFFFFF"><strong>Progress:</strong></font></th>
                              <th><font color="#FFFFFF"><strong>Action:</strong></font></th>
                                          
                                                
                                          </tr>
                                                    </thead>  
					<?php
					$x = 1;
					do { ?>  
                    
                       <tr>
                                  <td><b><?php echo $x++; ?></b></td>
                  <!--   <td>
                     <?php
										if($row_get_psmt_requests['dataset_photo'] == '')
										{										
										?>
                                     <img src="img/nophoto.png"  width="50px" height="50px" alt=""  class="img-fluid">
                                        <?php
										}
										else
										{
										?>
									<a href="#"><img src="img/clientphotos/<?php echo $row_get_psmt_requests['dataset_photo']; ?>" width="50px" height="50px" alt=""  class="img-fluid"></a>
                          <?php
										  }
										?>
					</td>-->
				
					        <td> <a href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>"> <strong><?php echo $row_get_psmt_requests['bg_dataset_name']; ?></strong></a></td>
                     <td><?php echo $row_get_psmt_requests['request_ref_number']; ?></td>
                   <!--  <td><?php echo $row_get_psmt_requests['request_plan']; ?></td>-->
                      <td><?php echo $row_get_psmt_requests['request_date']; ?></td>
                  <!--    <td><?php echo $row_get_psmt_requests['dataset_name']; ?></td>-->
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
                       <td><b><span class="rating">   <?php
								  $refnumber = $row_get_psmt_requests['request_ref_number'];
								   
$query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
$getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
$row_getprogress2 = mysqli_fetch_assoc($getprogress2);
$totalRows_getprogress2 = mysqli_num_rows($getprogress2);	
$complete=0;
$all=0;
do
{
							if($row_getprogress2['statuscheck']=='11')
							{
						$complete++;
								  ?><i class="icon_star voted"></i>
                                  <?php
								  }
								if($row_getprogress2['statuscheck']=='00')
							{
								  ?>
                                 <i class="icon_star"></i>
                                  <?php
								  }
								  ?>
                             <?php
							 	$all++;
							  } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));
							 ?>     
                                  
                                   <small>(<?php echo round(($complete/$all)*100); ?>%)</small></span></b></td>
                               
                            <td><a class="btn_1 small_status_11" href="../viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></td>    
                               
                               
                                </tr>
       
       <!-- <div class="col-lg-6">

					<div class="strip_list wow fadeIn">                   
                      <?php
										if($row_get_psmt_requests['dataset_photo'] == '')
										{										
										?>
                                        <figure><a href="#"><img src="img/nophoto.png" alt=""></a></figure>
                                        <?php
										}
										else
										{
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
                                   <td><b><span class="rating">   <?php
								  $refnumber = $row_get_psmt_requests['request_ref_number'];
								   
$query_getprogress2= sprintf("SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = %s ORDER BY status DESC", GetSQLValueString($refnumber, "text"));
$getprogress2 = mysqli_query($connect,$query_getprogress2) or die(mysqli_error());
$row_getprogress2 = mysqli_fetch_assoc($getprogress2);
$totalRows_getprogress2 = mysqli_num_rows($getprogress2);	
$complete=0;
$all=0;
do
{
							if($row_getprogress2['statuscheck']=='11')
							{
						$complete++;
								  ?><i class="icon_star voted"></i>
                                  <?php
								  }
								if($row_getprogress2['statuscheck']=='00')
							{
								  ?>
                                 <i class="icon_star"></i>
                                  <?php
								  }
								  ?>
                             <?php
							 	$all++;
							  } while ($row_getprogress2 = mysqli_fetch_assoc($getprogress2));
							 ?>     
                                  
                                   <small>(<?php echo round(($complete/$all)*100); ?>%)</small></span></b></td>
                                </tr>
                              </table>	
				
                        
                        	<ul>
							<li></li>
							<li></li>
							  <li><a href="viewrequest.php?requestid=<?php echo $row_get_psmt_requests['request_id']; ?>">View</a></li>
						</ul>
					</div></div>-->
        
          <?php } while ($row_get_psmt_requests = mysqli_fetch_assoc($get_psmt_requests)); ?>
               
           </table>
               
                
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
            
            
                <!--	<nav aria-label="" class="add_top_20">
					<ul class="pagination pagination-sm">
						<li class="page-item disabled">
							<a class="page-link" href="#" tabindex="-1">Previous</a>
						</li>
						<li class="page-item active"><a class="page-link" href="#">1</a></li>
						<li class="page-item"><a class="page-link" href="#">2</a></li>
						<li class="page-item"><a class="page-link" href="#">3</a></li>
						<li class="page-item">
							<a class="page-link" href="#">Next</a>
						</li>
					</ul>
				</nav>-->
				<!-- /pagination -->
			</div>
			<!-- /col -->
			
		<!--	<aside class="col-lg-4" id="sidebar">
				<div id="map_listing" class="normal_list">
				</div>
			</aside>-->
			<!-- /aside -->
	    <?php
  }
  else
  {          
  ?>  
<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div id="confirm">
						<div class="icon icon--order-success svg add_bottom_15">
							<img src="../img/warningsign.png" alt="noresultssign">
						</div>
					<h2>Their is no requests that are under the chosen Request Status!</h2>
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
 
 else
 {
  ?>  
   
		<div class="row">
      <div class="col-lg-3">
      <a href="../cart/cart.php?status_search=55">        <div class="strip_list wow fadeIn">
        <?php
		$status_searchaq = '55';
			 
$query_getaq = sprintf("SELECT COUNT(request_id) as COUNT_AQ FROM pel_psmt_request WHERE client_login_id = %s and status= %s ORDER BY request_date DESC", GetSQLValueString($client_id_get_psmt_requests, "text"),GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_searchaq, "text"));
$getaq = mysqli_query($connect,$query_getaq) or die(mysqli_error());
$row_getaq = mysqli_fetch_assoc($getaq);
$totalRows_getaq = mysqli_num_rows($getaq);	
		
		?>
											<figure style="background-image:url(https://psmt.pidva.africa/img/bg_count2.png)">
		         <h1><?php echo $row_getaq['COUNT_AQ'];?> </h1>
		        					</figure>
						<small>Requests</small>
						<h3>Awaiting <br/> Quotation</h3>
					<!--	<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cuodo....</p>-->
					</div>
					<!-- /strip_list -->
                     </a>	 
        </div>
        
        <div class="col-lg-3">
       <a href="../cart/cart.php?status_search=66">    <div class="strip_list wow fadeIn">
										 <?php
		$status_searchap = '66';
			 
$query_getap = sprintf("SELECT COUNT(request_id) as COUNT_AP FROM pel_psmt_request WHERE client_login_id = %s and status= %s ORDER BY request_date DESC", GetSQLValueString($client_id_get_psmt_requests, "text"),GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_searchap, "text"));
$getap = mysqli_query($connect,$query_getap) or die(mysqli_error());
$row_getap = mysqli_fetch_assoc($getap);
$totalRows_getap = mysqli_num_rows($getap);	
		
		?>
											<figure style="background-image:url(https://psmt.pidva.africa/img/bg_count2.png)">
		      <h1><?php echo $row_getap['COUNT_AP'];?> </h1>
		      					</figure>
						<small>Requests</small>
						<h3>Awaiting <br/> Payment</h3>
					<!--	<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cuodo....</p>-->
					</div>
					<!-- /strip_list -->    </a>	
                    
        </div>
        
        <div class="col-lg-3">
       <a href="../reports/index.php?status_search=44">  
          <div class="strip_list wow fadeIn">
										 <?php
		$status_searchip = '44';
			 
$query_getip = sprintf("SELECT COUNT(request_id) as COUNT_IP FROM pel_psmt_request WHERE client_login_id = %s and status= %s ORDER BY request_date DESC", GetSQLValueString($client_id_get_psmt_requests, "text"),GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_searchip, "text"));
$getip = mysqli_query($connect,$query_getip) or die(mysqli_error());
$row_getip = mysqli_fetch_assoc($getip);
$totalRows_getip = mysqli_num_rows($getip);	
		
		?>
											<figure style="background-image:url(https://psmt.pidva.africa/img/bg_count2.png)">
		       <h1><?php echo $row_getip['COUNT_IP'];?> </h1>
		        					</figure>
						<small>Requests</small>
						<h3>In<br/> Progress</h3>
				<!--		<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cuodo....</p>-->
					</div>  </a>	
					<!-- /strip_list -->
                    
        </div>
        
        <div class="col-lg-3">
        <a href="../reports/index.php?status_search=33">   <div class="strip_list wow fadeIn">
												 <?php
		$status_searchiu = '33';
			 
$query_getiu = sprintf("SELECT COUNT(request_id) as COUNT_IU FROM pel_psmt_request WHERE client_login_id = %s and status= %s ORDER BY request_date DESC", GetSQLValueString($client_id_get_psmt_requests, "text"),GetSQLValueString($client_login_id_get_psmt_requests, "text"),GetSQLValueString($status_searchiu, "text"));
$getiu = mysqli_query($connect,$query_getiu) or die(mysqli_error());
$row_getiu = mysqli_fetch_assoc($getiu);
$totalRows_getiu = mysqli_num_rows($getiu);	
		
		?>
											<figure style="background-image:url(../img/bg_count2.png)">
		    <h1><?php echo $row_getiu['COUNT_IU'];?> </h1>
		         						</figure>
						<small>Requests</small>
						<h3>Interim <br/> Uploaded</h3>
					<!--	<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cuodo....</p>-->
					</div> </a>
					<!-- /strip_list -->
                    
        </div>
        <?php
 }
 ?>
      		
		</div>
		<!-- /row -->
	</div>

    
	<!-- /container -->
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

	<!-- COMMON SCRIPTS -->
	<script src="../js/jquery-2.2.4.min.js"></script>
	<script src="../js/common_scripts.min.js"></script>
	<script src="../js/functions.js"></script>
	
	<!-- SPECIFIC SCRIPTS -->
	<script src="../js/googlemaps.js"></script>
    <script src="../js/map_listing.js"></script>
    <script src="../js/infobox.js"></script>


</body>
</html>
<?php

mysqli_free_result($getpackagenames);



?>