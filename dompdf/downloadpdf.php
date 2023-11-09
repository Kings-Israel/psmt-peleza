<?php
/*namespace Dompdf;
require_once 'dompdf/autoload.inc.php';
ob_start();*/
?>
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
$colname_getrequestdetails = "-1";
if (isset($_GET['requestid'])) {
  $colname_getrequestdetails = $_GET['requestid'];
}

$query_getrequestdetails = sprintf("SELECT * FROM pel_psmt_request WHERE request_id = %s", GetSQLValueString($colname_getrequestdetails, "int"));
$getrequestdetails = mysqli_query($connect,$query_getrequestdetails) or die(mysqli_error());
$row_getrequestdetails = mysqli_fetch_assoc($getrequestdetails);
$totalRows_getrequestdetails = mysqli_num_rows($getrequestdetails);

?><!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Peleza Screening and Monitoring Tool Kit">
	<meta name="author" content="Peleza">
	<title>PSMT-Peleza Screening Management Toolkit</title>

		<!-- BASE CSS -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
    	<link href="../css/icon_fonts/css/all_icons_min.css" rel="stylesheet">

</head>

<body>

 <div class="container margin_60">
			
	    <div class="box_general_3 cart">
                                <table width="100%" border="0">
  <tr>
    <td width="50%" bgcolor="#C2D348" style="padding-top:10px; padding-left:20px; padding-bottom:10px; border-bottom-right-radius:20px;" >      <h5><strong>CONFIDENTIAL BACKGROUND</strong></h5>      <h5><strong>SCREENING REPORT</strong></h5></td>
    <td><img align="right" src="../img/Peleza_Logo_We_Get_It.png" width="240" height="70" alt="PELEZALOGO"></td>
  </tr></table>	
               <br/>    
											
									   <table width="100%" border="0">
  <tr>
    <td width="25%" bgcolor="#E7EDB6" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >      <h6><strong>Name Of Individual</strong></h6></td>
    <td width="25%" bgcolor="#9DB3BC" bordercolor="#0A4157" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 2px solid;"><h6><strong><?php echo $row_getrequestdetails['bg_dataset_name']; ?></strong></h6></td>
  <td width="25%" bgcolor="#E7EDB6" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >      <h6><strong>Report Status</strong></h6></td>
    <td width="25%" bgcolor="#9DB3BC" bordercolor="#0A4157" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 2px solid;"> <?php
								  if($row_getrequestdetails['status']=='00')
								  {
								  ?>
                                  <a href="#" class="btn_1 small_status_00"><span id="mybuttontext">New Request</span></a>
                                  <?php
								
								  }
								    if($row_getrequestdetails['status']=='11')
								  {
								  ?>
                                  <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">Final Report</span></a>
                                  <?php
								  }
								    if($row_getrequestdetails['status']=='33')
								  {
								  ?>
                                  <a href="#" class="btn_1 small_status_33"><span id="mybuttontext">Interim</span></a>
                                  <?php
								  }
								    if($row_getrequestdetails['status']=='44')
								  {
								 ?>
                                  <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">In Progress</span></a>
                                  <?php
								  }
								      if($row_getrequestdetails['status']=='55')
								  {
								 ?>
                                  <a href="#" class="btn_1 small_status_55"><span id="mybuttontext">Awaiting Quotation</span></a>
                                  <?php
								  }
								      if($row_getrequestdetails['status']=='66')
								  {
								 ?>
                                  <a href="#" class="btn_1 small_status_66"><span id="mybuttontext">Awaiting Payment</span></a>
                                  <?php
								  }
								  
								  ?></td>
  </tr>
   <tr>
      <td colspan="4" bgcolor="#FFFFFF" style="padding-top:1px; padding-bottom:1px;" >&nbsp;</td>
      </tr>
        <tr>
    <td width="25%" bgcolor="#E7EDB6" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >      <h6><strong>Screening Package</strong></h6></td>
    <td width="25%" bgcolor="#9DB3BC" bordercolor="#0A4157" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 2px solid;"><h6><strong> <?php echo $row_getrequestdetails['request_plan']; ?></strong></h6></td>
  <td width="25%" bgcolor="#E7EDB6" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >      <h6><strong>Date Requested</strong></h6></td>
    <td width="25%" bgcolor="#9DB3BC" bordercolor="#0A4157" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 2px solid;"><h6><strong> <?php echo $row_getrequestdetails['request_date']; ?></strong></h6></td>
  </tr>
    <tr>
      <td colspan="4" bgcolor="#FFFFFF" style="padding-top:1px; padding-bottom:1px;" >&nbsp;</td>
      </tr>
        <tr> <td width="25%" bgcolor="#E7EDB6" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >      <h6><strong>Track Number</strong></h6></td>
    <td width="25%" bgcolor="#9DB3BC" bordercolor="#0A4157" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 2px solid;"><h6><strong> <?php echo $row_getrequestdetails['request_ref_number']; ?></strong></h6></td>
   
  <td width="25%" bgcolor="#E7EDB6" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >      <h6><strong>Data Set Type</strong></h6></td>
    <td width="25%" bgcolor="#9DB3BC" bordercolor="#0A4157" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 2px solid;"><h6><strong> <?php echo $row_getrequestdetails['request_type']; ?></strong></h6></td>
  </tr>
  
  <tr>
      <td colspan="4" bgcolor="#FFFFFF" style="padding-top:1px; padding-bottom:1px;" >&nbsp;</td>
      </tr>
  
     <tr> <td  colspan="2" width="25%" bgcolor="#E7EDB6" style="padding-top:8px; padding-left:5px; padding-bottom:5px;" >      <h6><strong>Adverse Status:</strong></h6></td>
   <td   colspan="2"  width="25%" bgcolor="#9DB3BC" bordercolor="#0A4157" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 2px solid;"><h6><strong> <?php echo $row_getrequestdetails['adverse_status']; ?></strong></h6></td>
  </tr>
  </table>
								<?php
		$search_ref = $row_getrequestdetails['request_ref_number'];						
			
if( $row_getrequestdetails['request_type'] == 'COMPANY' && $row_getrequestdetails['report_file'] == '00' )
{


?>
<?php

$query_getdetailsreg = "SELECT * FROM pel_company_registration WHERE search_id = '".$search_ref."'";
$getdetailsreg  = mysqli_query($connect,$query_getdetailsreg ) or die(mysqli_error());
$row_getdetailsreg  = mysqli_fetch_assoc($getdetailsreg );
$totalRows_getdetailsreg  = mysqli_num_rows($getdetailsreg );	
if($totalRows_getdetailsreg>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-doc-text-inv"></i>
									<h3>REGISTRATION DETAILS</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                              <tr>
										
													  <th><strong>Company Name:</strong></th>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsreg['company_name']; ?></a>																								</td></tr>
                                                     <tr>
										
													  <th><strong>Company Type:</strong></th>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsreg['business_type']; ?></a>																								</td></tr>
                                                   <tr>
                                                      
                                                   
														<th><strong>Company Registration Number:</strong></th>
                                                     <td><?php echo $row_getdetailsreg['registration_number']; ?></td>
                                      </tr><tr>
                                                        <th><strong>Date of Incorporation:</strong></th>
                                                          <td><?php echo $row_getdetailsreg['registration_date']; ?></td>
                                                        </tr>
                                                        <tr>
                                                         <th><strong>Registered Address:</strong></th>
                                                     <td><?php echo $row_getdetailsreg['address']; ?></td>
                                                     </tr>
                                                       <tr>
                                                         <th><strong>Registered Location:</strong></th>
                                                     <td><?php echo $row_getdetailsreg['office']; ?></td>
                                                     </tr>
                                                       <tr>
                                                         <th><strong>Industry:</strong></th>
                                                     <td><?php echo $row_getdetailsreg['industry']; ?></td>
                                                     </tr>
                                                      <tr>
                                                         <th><strong>Country:</strong></th>
                                                     <td><?php echo $row_getdetailsreg['country']; ?></td>
                                                     </tr>
                             <tr>
                                  <td><b>Operation Status:</b></td>
                                  <td><?php echo $row_getdetailsreg['operation_status']; ?></a></td>
                                </tr>
                  
                                   <tr>
                                                        <th><strong>Data Source:</strong></th>
		<td> <?php echo $row_getdetailsreg['data_source']; ?></td>
									  </tr>
                             </table>	
				 <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsreg['data_notes']; ?></p></td></tr></table>
	<?php
	}
	?>			

<?php

$query_getdetailslicense = "SELECT * FROM pel_company_license WHERE search_id = '".$search_ref."'";
$getdetailslicense  = mysqli_query($connect,$query_getdetailslicense ) or die(mysqli_error());
$row_getdetailslicense  = mysqli_fetch_assoc($getdetailslicense );
$totalRows_getdetailslicense  = mysqli_num_rows($getdetailslicense );	
if($totalRows_getdetailslicense>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-doc-text-inv"></i>
									<h3>LICENSE CHECK DETAILS</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<?php
								do
								{
								
								?>
                                
                                		<h5>LICENSE TYPE: <?php echo $row_getdetailslicense['license_type']; ?></h5>
                                
                                    <table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                              <tr>
										
													  <th width="35%"><strong>Company Name:</strong></th>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailslicense['company_name']; ?></a>																								</td></tr>
                                                   <tr>
                                                      
                                                   
														<th><strong>Company License Number:</strong></th>
                                                     <td><?php echo $row_getdetailslicense['license_number']; ?></td>
                                      </tr><tr>
                                                        <th><strong>Issuing Date:</strong></th>
                                                          <td><?php echo $row_getdetailslicense['registration_date']; ?></td>
                                                        </tr>
                                                    
                                                    <!--   <tr>
                                                         <th><strong>License Location:</strong></th>
                                                     <td><?php echo $row_getdetailslicense['office']; ?></td>
                                                     </tr>-->
                                                       <tr>
                                                         <th><strong>License Type:</strong></th>
                                                     <td><?php echo $row_getdetailslicense['license_type']; ?></td>
                                                     </tr>
                                                     
                                                       <tr>
                                                         <th><strong>License Year:</strong></th>
                                                     <td><?php echo $row_getdetailslicense['license_year']; ?></td>
                                                     </tr>
                                                        <tr>
                                                         <th><strong>License Year:</strong></th><td><?php 
														
														if($row_getdetailslicense['operation_status']=='EXPIRED')
														{
														?>
                                                        
                                            <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">EXPIRED</span></a>
                                                        <?php
														}
														if($row_getdetailslicense['operation_status']=='VALID')
														{
														?>
                                                <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">VALID</span></a>	
                                                         <?php
														}
														?> </td>
                                                        </tr>
                                                 
                                   <tr>
                                                        <th><strong>Data Source:</strong></th>
										 <td><?php echo $row_getdetailslicense['data_source']; ?></td>
									  </tr>
                             </table>	<hr>      
                                           <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailslicense['data_notes']; ?></p></td></tr></table>
							
                                    <hr>
							<?php
							 } while ($row_getdetailslicense = mysqli_fetch_assoc($getdetailslicense));
							
							?>
	<?php
	}
	?>	
    
   
    	<?php

$query_getdetailsshareholders = "SELECT * FROM pel_company_shares_data WHERE search_id = '".$search_ref."'";
$getdetailsshareholders  = mysqli_query($connect,$query_getdetailsshareholders ) or die(mysqli_error());
$row_getdetailsshareholders  = mysqli_fetch_assoc($getdetailsshareholders );
$totalRows_getdetailsshareholders  = mysqli_num_rows($getdetailsshareholders );	
if($totalRows_getdetailsshareholders>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-users-1"></i>
									<h3>COMPANY SHAREHOLDING DETAILS</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								   
                              <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	<table id="simple-table" class="table  table-striped  table-bordered table-hover">
										<thead bgcolor="#0A4157">
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">SHAREHOLDER NAME:</font></th>
                                                   	  <th><font color="#FFFFFF">SHARE HOLDER TYPE:</font></th>
                                                   	  <th><font color="#FFFFFF">ADDRESS:</font></th>
                                                 	  <th><font color="#FFFFFF">NATIONALITY:</font></th>
                                                	  <th><font color="#FFFFFF">SHARES:</font></th>
                                                  </tr>
                                  </thead>
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                   	<tr>			     
                                           
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailsshareholders['first_name']; ?> <?php echo $row_getdetailsshareholders['second_name']; ?> <?php echo $row_getdetailsshareholders['third_name']; ?></a>																								</td>
                                              
                                                          <td><?php echo $row_getdetailsshareholders['share_type']; ?></td>
                                                      
                                                          <td><?php echo $row_getdetailsshareholders['address']; ?></td>
                                                     
                                                     <td><?php echo $row_getdetailsshareholders['citizenship']; ?></td>
                                                   
                                                     <td><?php echo $row_getdetailsshareholders['shares_number']; ?></td>
                                                                                         
                                                   </tr>
                                                     <?php } while ($row_getdetailsshareholders = mysqli_fetch_assoc($getdetailsshareholders)); ?>
                                                   
                                                   
								  </table>
                                            
                           <?php
						   
						   $query_getdetailsshareholders_comments = "SELECT * FROM pel_company_shares_data_comm WHERE search_id = '".$search_ref."'";
$getdetailsshareholders_comments  = mysqli_query($connect,$query_getdetailsshareholders_comments ) or die(mysqli_error());
$row_getdetailsshareholders_comments  = mysqli_fetch_assoc($getdetailsshareholders_comments );
$totalRows_getdetailsshareholders_comments  = mysqli_num_rows($getdetailsshareholders_comments );	
if($totalRows_getdetailsshareholders_comments>0)
{

?>                 
                                            
								
									
                                         <hr>      
                                           <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsshareholders_comments['data_notes']; ?></p></td></tr></table>
					<?php
					}
					?>
	<?php
	}
	?>	
    	
        
        	<?php

$query_getdetailscredit = "SELECT * FROM pel_company_credit_data WHERE search_id = '".$search_ref."' ORDER BY loan_status ASC";
$getdetailscredit  = mysqli_query($connect,$query_getdetailscredit ) or die(mysqli_error());
$row_getdetailscredit  = mysqli_fetch_assoc($getdetailscredit );
$totalRows_getdetailscredit  = mysqli_num_rows($getdetailscredit );	

$query_getdetailscredit2 = "SELECT * FROM pel_company_credit_data WHERE search_id = '".$search_ref."' AND loan_status = 'OPEN' ORDER BY loan_status ASC";
$getdetailscredit2  = mysqli_query($connect,$query_getdetailscredit2) or die(mysqli_error());
$row_getdetailscredit2  = mysqli_fetch_assoc($getdetailscredit2);
$totalRows_getdetailscredit2  = mysqli_num_rows($getdetailscredit2);


if($totalRows_getdetailscredit>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="pe-7s-cash"></i>
									<h3>CREDIT CHECK DETAILS</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                                <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead  bgcolor="#0A4157">
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">SUBSCRIBER:</font></th>
                                                       <th><font color="#FFFFFF">LOAN TYPE:</font></th>
                                                     <th><font color="#FFFFFF">TOTAL AMOUNT:</font></th>
                                                     <th><font color="#FFFFFF">BALANCE:</font></th>
                                                    <th><font color="#FFFFFF">PAST DUE:</font></th>
                                                     <th><font color="#FFFFFF">LOAN STATUS:</font></th>
                                                   <th><font color="#FFFFFF">SOURCE:</font></th>
                                                   
                                          </tr>
                                  </thead>
                                  <tr><td colspan="7"><h5 align="left" class="smaller lighter blue"><strong>CLOSED LOANS: </strong> 
</h5></td></tr>
                                                   
                                                   <?php
												 
												  $total_closed = 0;
												  $total_balance_closed = 0;
												  $x=1;												  
												  do { 
	   if($row_getdetailscredit['loan_status']=='CLOSED')
{ ?>
                                                   	<tr>			     
                                           
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailscredit['subscriber']; ?> </a>																								</td>
                                               <td><?php echo $row_getdetailscredit['loan_type']; ?></td> 
                                                       <td><?php echo number_format($row_getdetailscredit['total_amount']); ?></td>
                                                          <td><?php echo number_format($row_getdetailscredit['balance']); ?></td>  
                                                           <td><?php echo $row_getdetailscredit['past_due']; ?></td>  
                                                           <td><?php 
														
														if($row_getdetailscredit['loan_status']=='CLOSED')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">CLOSED</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailscredit['loan_status']=='OPEN')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">OPEN</span></a>
                                                         <?php
														}
														?> </td>
                                                            
                                                             <td><?php echo $row_getdetailscredit['data_source']; ?></td>
                                                                                                       
                                                     
                                                   </tr>
                                                     <?php 
													  $total_closed= $total_closed + $row_getdetailscredit['total_amount'];
													 $total_balance_closed= $total_balance_closed + $row_getdetailscredit['balance'];
}
													 } while ($row_getdetailscredit = mysqli_fetch_assoc($getdetailscredit)); ?>
                                    <tr>
                                                      <td colspan="2" align="right"><strong>TOTAL:</strong> </td>
                                                      <td colspan="1"><strong><?php echo number_format($total_closed); ?></strong> </td>
                                                      <td colspan="1"><strong><?php echo number_format($total_balance_closed); ?></strong> </td>
                                                      <td colspan="5"></td></tr>
                                            
                                            <tr><td colspan="7"><h5 align="left" class=" smaller lighter blue"><strong>OPEN LOANS: </strong> 
</h5></td></tr>     


                                                               <?php
												  $total_open = 0;
												   $total_balance_open = 0;
												 
												  $x=1;												  
												  do { 
	   if($row_getdetailscredit2['loan_status']=='OPEN')
{
	   ?>    
                                                   	<tr>			     
                                           
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailscredit2['subscriber']; ?> </a>																								</td>
                                               <td><?php echo $row_getdetailscredit2['loan_type']; ?></td> 
                                                       <td><?php echo number_format($row_getdetailscredit2['total_amount']); ?></td>
                                                          <td><?php echo number_format($row_getdetailscredit2['balance']); ?></td>  
                                                           <td><?php echo $row_getdetailscredit2['past_due']; ?></td>  
                                                            <td><?php 
														
														if($row_getdetailscredit2['loan_status']=='CLOSED')
														{
														?>
                                                        
                                            <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">OPEN</span></a>
                                                        <?php
														}
														if($row_getdetailscredit2['loan_status']=='OPEN')
														{
														?>
                                                <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">OPEN</span></a>	
                                                         <?php
														}
														?> </td>
                                                            
                                                             <td><?php echo $row_getdetailscredit2['data_source']; ?></td>
                                                                                                       
                                                        </tr>
                                                     <?php
													 $total_balance_open= $total_balance_open + $row_getdetailscredit2['balance'];
													 $total_open= $total_open + $row_getdetailscredit2['total_amount'];
													  }
													 ?>
                                                  
                                                     
                                                     <?php
													 } while ($row_getdetailscredit2 = mysqli_fetch_assoc($getdetailscredit2)); ?>
                                                      <tr>
                                                      <td colspan="2" align="right"><strong>TOTAL:</strong> </td>
                                                        <td colspan="1"><strong><?php echo number_format($total_open,2); ?></strong> </td>
                                                      <td colspan="1"><strong><?php echo number_format($total_balance_open,2); ?></strong> </td>
                                                      <td colspan="4"></td></tr>



            
                                                   
								  </table>
                                            
                           <?php
						   
						   $query_getdetailscredit_comments = "SELECT * FROM pel_credit_data_comments WHERE search_id = '".$search_ref."'";
$getdetailscredit_comments  = mysqli_query($connect,$query_getdetailscredit_comments ) or die(mysqli_error());
$row_getdetailscredit_comments  = mysqli_fetch_assoc($getdetailscredit_comments );
$totalRows_getdetailscredit_comments  = mysqli_num_rows($getdetailscredit_comments );	
if($totalRows_getdetailscredit_comments>0)
{

?>                 
                                            
					 <h6>COMMENTS:</h6>
					
 <table width="100%" bgcolor="#FFFFFF">
  <tr>

    <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailscredit_comments['data_notes']; ?></p></td>
  </tr></table>			
							
					<?php
					}
					?>			
								
                           
	<?php
	}
	?>	
    
   
    
    <?php

$query_getdetailstaxcompliance = "SELECT * FROM pel_company_tax_data WHERE search_id = '".$search_ref."'";
$getdetailstaxcompliance  = mysqli_query($connect,$query_getdetailstaxcompliance ) or die(mysqli_error());
$row_getdetailstaxcompliance  = mysqli_fetch_assoc($getdetailstaxcompliance );
$totalRows_getdetailstaxcompliance  = mysqli_num_rows($getdetailstaxcompliance );	
if($totalRows_getdetailstaxcompliance>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-check"></i>
									<h3>TAX COMPLIANCE CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
							
                                
                              <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	<table id="simple-table" class="table  table-bordered table-hover">
										<thead> 
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Company Name:</font></th>
                                                   <th><font color="#FFFFFF">Registration Number:</font></th>
                                                    <th><font color="#FFFFFF">Tax Organisation:</font></th>
                                                <th><font color="#FFFFFF">Tax Number:</font></th>
                                                  <th><font color="#FFFFFF">Country:</font></th>
                                                  <th><font color="#FFFFFF">Data Source:</font></th>                                                   <th><font color="#FFFFFF">Expiry Date:</font></th>
                                                    <th><font color="#FFFFFF">Compliance Status:</font></th>
                                          </tr>
                                  </thead>
                                                         <tr>			     
                                           	<tr>
                                                    	<td>
                                                   <a href="#"><?php echo $row_getdetailstaxcompliance['company_name']; ?> </a>																								</td>
                                                   <td><?php echo $row_getdetailstaxcompliance['registration_number']; ?></td> 
                                                   <td><?php echo $row_getdetailstaxcompliance['tax_organisation']; ?></td>
                                                   <td><?php echo $row_getdetailstaxcompliance['tax_number']; ?></td>  
                                                   <td><?php echo $row_getdetailstaxcompliance['country']; ?></td>
                                                   <td><?php echo $row_getdetailstaxcompliance['data_source']; ?></td>
                                                    <td><?php echo $row_getdetailstaxcompliance['expiry_date']; ?></td>  
                                                   <td><?php 
														
														if($row_getdetailstaxcompliance['tax_status']=='VALID')
														{
														?>
                                                        
                                                        <span class="label label-sm label-success">VALID</span>	
                                                        <?php
														}
														if($row_getdetailstaxcompliance['tax_status']=='EXPIRED')
														{
														?>
                                                        <span class="label label-sm label-danger">EXPIRED</span>	
                                                         <?php
														}
														?> </td>
                                                        </tr></table>
                    <?php
					
					if($row_getdetailstaxcompliance['tax_photo'] == '')
					{
					}
					else
					{
					?>                        
            <h6>Tax Compliance Certificate Photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/company/taxcompliance/<?php echo $row_getdetailstaxcompliance['tax_photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/company/taxcompliance/<?php echo $row_getdetailstaxcompliance['tax_photo']; ?>" width="500px" height="400px" alt="Tax Compliance Cetficate Photo"></a> 
                      <?php
					}
					?>
								       <hr>
                                        <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailstaxcompliance['data_notes']; ?></p></td></tr></table>                           
                                            

	<?php
	}
	?>	
  
  
  <!--Proffessional Membership-->
  <?php

$query_getdetailsproffmembership = "SELECT * FROM pel_data_proff_membership WHERE search_id = '".$search_ref."' ";
$getdetailsproffmembership  = mysqli_query($connect,$query_getdetailsproffmembership ) or die(mysqli_error());
$row_getdetailsproffmembership = mysqli_fetch_assoc($getdetailsproffmembership );
$totalRows_getdetailsproffmembership  = mysqli_num_rows($getdetailsproffmembership );	
if($totalRows_getdetailsproffmembership>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-doc-text-inv"></i>
									<h3>PROFFESSIONAL MEMBERSHIP CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                  
                                                  <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Membership Body:</font></th>
                                               <th><font color="#FFFFFF">Registration Date:</font></th>
                                             <th><font color="#FFFFFF">Data Source:</font></th>
                                  <th><font color="#FFFFFF">Status:</font></th>
                                             </tr>
                                                    </thead>
                                                   	<tr>			     
                                              	<td>
                                                   <a href="#"><?php echo $row_getdetailsproffmembership['membership_body']; ?> </a>																								</td>
                                               <td><?php echo $row_getdetailsproffmembership['registration_date']; ?></td> 
                                                       <td><?php echo $row_getdetailsproffmembership['data_source']; ?></td>
                                                   <td><?php 
														
														if($row_getdetailsproffmembership['membership_status']=='ACTIVE')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">ACTIVE</span></a>
                                                              <?php
														}
														if($row_getdetailsproffmembership['membership_status']=='NON ACTIVE')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">NON ACTIVE</span></a>
                                                         <?php
														}
														?> </td>
                                                    </tr>   
                                                   </table>
                                        
                                               <hr>                          
                             <?php
					
					if($row_getdetailsproffmembership['membership_certificate'] == '')
					{
					}
					else
					{
					?> <h6>Certificate Scan Photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/company/membershipcertificate/<?php echo $row_getdetailsproffmembership['membership_certificate']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/company/membershipcertificate/<?php echo $row_getdetailsproffmembership['membership_certificate']; ?>" width="500px" height="400px" alt="Certificate Photo"></a> 
                                         <?php
					}
					?>  
                             <hr>      
                                           <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsproffmembership['data_notes']; ?></p></td></tr></table>
                                                     
                                                   
                                               <hr>                    
                                                   
                                                     <?php } while ($row_getdetailsproffmembership = mysqli_fetch_assoc($getdetailsproffmembership)); ?>
	<?php
	}
	?>	
  
    
    
    	<?php

$query_getdetailscustomerreference = "SELECT * FROM pel_company_customer_ref WHERE search_id = '".$search_ref."'";
$getdetailscustomerreference  = mysqli_query($connect,$query_getdetailscustomerreference ) or die(mysqli_error());
$row_getdetailscustomerreference  = mysqli_fetch_assoc($getdetailscustomerreference );
$totalRows_getdetailscustomerreference  = mysqli_num_rows($getdetailscustomerreference );	
if($totalRows_getdetailscustomerreference>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-hammer"></i>
									<h3>CUSTOMER REFERENCE CHECK:</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<?php
												  
												  $x=1;												  
												  do { ?>	
                                     
                      	<table width="100%" id="simple-table" class="table table-bordered table-hover">  
                            <thead bgcolor="#0A4157">
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Company Name</font></th>
                                                   	  <th><font color="#FFFFFF">Contact Person Name</font></th>
                                                      	  <th><font color="#FFFFFF">Respondent Contact</font></th>
                                                            <th><font color="#FFFFFF">Respondent Title</font></th>
                                                              <th><font color="#FFFFFF">Data Source</font></th>
                                                              </tr>
                                                              </thead>
                                                                         
							<tr>  
                            <td><?php echo $row_getdetailscustomerreference['customer_name']; ?></td>
                            <td><?php echo $row_getdetailscustomerreference['contact_person_name']; ?></td>
                               <td><?php echo $row_getdetailscustomerreference['reference_contact']; ?></td>
                                  <td><?php echo $row_getdetailscustomerreference['contact_person_title']; ?></td>
                                     <td><?php echo $row_getdetailscustomerreference['data_source']; ?></td>
                          </tr>
                                 </table>       
                                  <hr>      
                                           <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailscustomerreference['data_notes']; ?></p></td></tr></table>   <hr>     
                                 <?php 
							
								 } while ($row_getdetailscustomerreference = mysqli_fetch_assoc($getdetailscustomerreference)); ?>
	<?php
	}
	?>	
    
    
        
<!--  Residence Data check-->
        
    	<?php

$query_getdetailsresidency = "SELECT * FROM pel_data_residence WHERE search_id = '".$search_ref."'";
$getdetailsresidency  = mysqli_query($connect,$query_getdetailsresidency ) or die(mysqli_error());
$row_getdetailsresidency  = mysqli_fetch_assoc($getdetailsresidency );
$totalRows_getdetailsresidency  = mysqli_num_rows($getdetailsresidency );	
if($totalRows_getdetailsresidency>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-location-1"></i>
									<h3>SITEVISIT DETAILS:</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                            			<tr>
										
													  <th   bgcolor="#0A4157">Building Name:</th>
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailsresidency['building_name']; ?></a>																								</td></tr>
                                                   <tr>
                                                      
                                                   
														<th>Physical Address</th>
                                                          <td><?php echo $row_getdetailsresidency['physical_address']; ?></td>
                                      </tr><tr>
                                                        <th>Street</th>
                                                          <td><?php echo $row_getdetailsresidency['street']; ?></td>
                                                        </tr>
                                                        <tr>
                                                         <th>House Number</th>
                                                     <td><?php echo $row_getdetailsresidency['house_number']; ?></td>
                                                     </tr>
                                                   
                                                      <tr>
                                                         <th>Country</th>
                                                     <td><?php echo $row_getdetailsresidency['country']; ?></td>
                                                     </tr>                                                
                                                    
                                                      <tr>
                                                        <th class="hidden-480">Data Source</th>
										
                                                     
                                                      <td class="hidden-480">
                                                        
                                                      <?php echo $row_getdetailsresidency['data_source']; ?></td>
													</tr>
							<tr bgcolor="#0A4157">									
													  <th colspan="2"  bgcolor="#0A4157"><font color="#FFFFFF"><strong>Plot Image:</strong></font></th>
										
                                      </tr>
                                               <tr>      
                                                      <td colspan="2" class="hidden-480"><img src="http://46.101.16.235/pidva/html/company/sitevisit/<?php echo $row_getdetailsresidency['building_photo']; ?>" alt="Location Photo" class="img-fluid"></td>
									  </tr>
                                    
								  </table>
							<h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsresidency['data_notes']; ?></p></td></tr></table>
	<?php
	}
	?>	
    
    
    
  <!--  Company Social Media Data-->
    
     <?php

$query_getdetailssocial = "SELECT * FROM pel_data_social_media WHERE search_id = '".$search_ref."'";
$getdetailssocial  = mysqli_query($connect,$query_getdetailssocial ) or die(mysqli_error());
$row_getdetailssocial  = mysqli_fetch_assoc($getdetailssocial );
$totalRows_getdetailssocial  = mysqli_num_rows($getdetailssocial );	
if($totalRows_getdetailssocial>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-network"></i>
									<h3>SOCIAL MEDIA CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                        
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                    <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF"><strong>Source Name:</strong></font></th>
                                                   <th><font color="#FFFFFF"><strong>Adverse Mentions Status:</strong></font></th>
                                                    <th><font color="#FFFFFF"><strong>Social Media Handle:</strong></font></th>
                                                       <th><font color="#FFFFFF"><strong>Data Source:</strong></font></th>
                                                                                                         
                                                      
                                          </tr>
                                  </thead>
                                                   	<tr>			     
                                             	<td>
                                                   <a href="#"><?php echo $row_getdetailssocial['website']; ?></a>																								</td>
                                              
                                                          <td><?php echo $row_getdetailssocial['adverse_status']; ?></td>
                                                      
                                                          <td><?php echo $row_getdetailssocial['social_media_handle']; ?></td>
                                                                      <td><?php echo $row_getdetailssocial['data_source']; ?></td>
                                                 </tr> 
                                      <thead>     	<tr bgcolor="#0A4157">
                                                        <th colspan="4" class="hidden-480"><font color="#FFFFFF"><strong>Adverse Mention Caption:</strong></font></th>
										
                                      </tr></thead> 
                                                     
                                        	<tr>              <td  colspan="4"><a href="http://46.101.16.235/pidva/html/company/socialmediaphotos/<?php echo $row_getdetailssocial['photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/company/socialmediaphotos/<?php echo $row_getdetailssocial['photo']; ?>" width="100%" alt="Social Media Caption"></a>  </td> </tr>  </table>
                                    <hr>               
                 <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailssocial['data_notes']; ?></p></td></tr></table>
  <hr>
                                                     <?php } while ($row_getdetailssocial = mysqli_fetch_assoc($getdetailssocial)); ?>
	<?php
	}
	?>	
    	
    
        <?php
$query_getdetailswatchlist = "SELECT * FROM pel_company_watchlist_data WHERE search_id = '".$search_ref."'";
$getdetailswatchlist  = mysqli_query($connect,$query_getdetailswatchlist ) or die(mysqli_error());
$row_getdetailswatchlist  = mysqli_fetch_assoc($getdetailswatchlist );
$totalRows_getdetailswatchlist  = mysqli_num_rows($getdetailswatchlist );	
if($totalRows_getdetailswatchlist>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-globe"></i>
									<h3>GLOBAL WATCHLIST</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                  <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
												<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF"><strong>Company Name:</strong></font></th>
                                                    <th><font color="#FFFFFF"><strong>Address:</strong></font></th>
                                                   <th><font color="#FFFFFF"><strong>Country Of Operation:</strong></font></th>
                                                <th><font color="#FFFFFF"><strong>Data Source:</strong></font></th>
                                             <th><font color="#FFFFFF"><strong>Watchlist Status:</strong></font></th>
                                                
                                          </tr>
                                                    </thead>
                                                   	<tr>			     
                                                  	<td>
                                                   <a href="#"><?php echo $row_getdetailswatchlist['company_name']; ?></a>																								</td>
                                               <td><?php echo $row_getdetailswatchlist['address']; ?></td>
                                               
                                                  <td><?php echo $row_getdetailswatchlist['country']; ?></td>
                                                           <td><?php echo $row_getdetailswatchlist['data_source']; ?></td>
                                                    
                                                          <td><?php echo $row_getdetailswatchlist['watchlist_status']; ?></td>
                                                 </tr>	
                                                 
                                  <thead>	<tr bgcolor="#0A4157">
                                                        <th colspan="5" class="hidden-480"><font color="#FFFFFF"><strong>Glaboal Watchlist Caption:</strong></font></th>
										
                                      </tr>  </thead>              
                                                 
                                                 
                                                 <tr>
                                                   
                                                     <td colspan="5"><a href="http://46.101.16.235/pidva/html/company/watchlistphotos/<?php echo $row_getdetailswatchlist['photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/company/watchlistphotos/<?php echo $row_getdetailswatchlist['photo']; ?>"  alt="Global Watchlist Caption"  class="img-fluid"></a> </td>      
                                                  
                                                    </tr>	
                                            </table>
                                              
                                        <hr>               
                 <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailswatchlist['data_notes']; ?></p></td></tr></table>
  <hr>  
                                                   
                                                   
                                                     <?php } while ($row_getdetailswatchlist = mysqli_fetch_assoc($getdetailswatchlist)); ?>
	<?php
	}
	?>	
 
  <?php
}
else if( $row_getrequestdetails['request_type'] == 'INDIVIDUAL' && $row_getrequestdetails['report_file'] == '00' )
{

?>
<?php

$query_getdetailsid = "SELECT * FROM pel_individual_id WHERE search_id = '".$search_ref."' and module_name='identitycheck'";
$getdetailsid  = mysqli_query($connect,$query_getdetailsid ) or die(mysqli_error());
$row_getdetailsid  = mysqli_fetch_assoc($getdetailsid );
$totalRows_getdetailsid  = mysqli_num_rows($getdetailsid );	
if($totalRows_getdetailsid>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="pe-7s-id"></i>
									<h3>IDENTITY DETAILS</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<div class="profile">
								<div class="row">
									
                                        <?php
										if($row_getdetailsid['identity_holder_photo'] == '')
										{										
										?>		<div class="col-lg-12 col-md-12">	
                                        <!--  <img src="img/nophoto.png" alt="" class="img-fluid">-->
                                        <?php
										}
										else
										{
										?><div class="col-lg-4 col-md-4">
										<figure>
										  <img src="http://localhost/pilotadmin/html/individual/individualphotos/<?php echo $row_getdetailsid['identity_holder_photo']; ?>" alt="" class="img-fluid">        	</figure>
									</div>    <div class="col-lg-8 col-md-8">	                 <?php
										  }
										?>
                                   	
									
                                 <table id="simple-table" class="table  table-striped  table-bordered table-hover">
                                      
                                 <tr>
                                  <td width="35%"><b>IDENTITY HOLDERS NAME:</b></td>
                                  <td>  <h1> <?php echo $row_getdetailsid['identity_name']; ?></h1></td>
                                </tr>
                                   <tr>
                                  <td width="35%"><b>Identity Type:</b></td>
                                  <td><?php echo $row_getdetailsid['identity_type']; ?></td>
                                </tr>
                                     <tr>
                                  <td width="35%"><b>Identity Number:</b></td>
                                  <td><?php echo $row_getdetailsid['identity_number']; ?></td>
                                </tr>
                                   <tr>
                                  <td width="35%"><b>Identity Country:</b></td>
                                  <td><?php echo $row_getdetailsid['identity_country']; ?></td>
                                </tr>
                                  <tr>
                                  <td width="35%"><b>Date of Birth:</b></td>
                                  <td><?php echo $row_getdetailsid['date_of_birth']; ?></td>
                                </tr>
                                  <tr>
                                  <td width="35%"><b>Citizenship:</b></td>
                                  <td><?php echo $row_getdetailsid['citizenship']; ?></td>
                                </tr>
                                 <tr>
                                  <td width="35%"><b>Gender:</b></td>
                                  <td><?php echo $row_getdetailsid['gender']; ?></td>
                                </tr>
                                   <tr>
                                  <td width="35%"><b>Data Source:</b></td>
                                  <td><?php echo $row_getdetailsid['data_source']; ?></td>
                                </tr>
                            <!--     <tr>
                                  <td width="35%"><b>Date Added:</b></td>
                                  <td><?php echo $row_getdetailsid['date_added']; ?></td>
                                </tr>
                                
                          
                                <tr>
                                  <td><b>Match Status:</b> </td>
                                  <td> <?php
							 if($row_getdetailsid['match_status']=='MATCH')
								  {
								  ?>
                                  <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                  <?php
								  }
								   if($row_getdetailsid['match_status']=='UN MATCHED')
								  {
								 ?>
                                  <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">UN MATCHED</span></a>
                                  <?php
								  }
								  
								  ?></td>
                                </tr>-->
                             </table>	
                                   
									</div>
								</div>		</div>
                                
               
                            <h6>COMMENTS:</h6>
					
 <table width="100%" bgcolor="#FFFFFF">
  <tr>

    <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
    <p><?php echo $row_getdetailsid['data_notes']; ?></p></td>
  </tr></table>
	<?php
	}
	?>	
    
    
    <?php

$query_getdetailspassport = "SELECT * FROM pel_individual_id WHERE search_id = '".$search_ref."' and module_name='passportcheck'";
$getdetailspassport  = mysqli_query($connect,$query_getdetailspassport ) or die(mysqli_error());
$row_getdetailspassport  = mysqli_fetch_assoc($getdetailspassport );
$totalRows_getdetailspassport  = mysqli_num_rows($getdetailspassport );	
if($totalRows_getdetailspassport>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="pe-7s-id"></i>
									<h3>PASSPORT IDENTITY DETAILS</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<div class="profile">
								<div class="row">
									
                                        <?php
										if($row_getdetailspassport['identity_holder_photo'] == '')
										{										
										?>		<div class="col-lg-12 col-md-12">	
                                        <!--  <img src="img/nophoto.png" alt="" class="img-fluid">-->
                                        <?php
										}
										else
										{
										?><div class="col-lg-4 col-md-4">
										<figure>
										  <img src="http://46.101.16.235/pidva/html/individual/individualpassportphotos/<?php echo $row_getdetailspassport['identity_holder_photo']; ?>" alt="" class="img-fluid">        	</figure>
									</div>    <div class="col-lg-8 col-md-8">	                 <?php
										  }
										?>
                                   	
									
                                 <table id="simple-table" class="table  table-striped  table-bordered table-hover">
                                      
                                 <tr>
                                  <td width="35%"><b>PASSPORT HOLDERS NAME:</b></td>
                                  <td>  <h1> <?php echo $row_getdetailspassport['identity_name']; ?></h1></td>
                                </tr>
                                   <tr>
                                  <td width="35%"><b>Identity Type:</b></td>
                                  <td><?php echo $row_getdetailspassport['identity_type']; ?></td>
                                </tr>
                                     <tr>
                                  <td width="35%"><b>Passport Number:</b></td>
                                  <td><?php echo $row_getdetailspassport['identity_number']; ?></td>
                                </tr>
                                   <tr>
                                  <td width="35%"><b>Passport Country:</b></td>
                                  <td><?php echo $row_getdetailspassport['identity_country']; ?></td>
                                </tr>
                                  <tr>
                                  <td width="35%"><b>Date of Birth:</b></td>
                                  <td><?php echo $row_getdetailspassport['date_of_birth']; ?></td>
                                </tr>
                                  <tr>
                                  <td width="35%"><b>Citizenship:</b></td>
                                  <td><?php echo $row_getdetailspassport['citizenship']; ?></td>
                                </tr>
                                 <tr>
                                  <td width="35%"><b>Gender:</b></td>
                                  <td><?php echo $row_getdetailspassport['gender']; ?></td>
                                </tr>
                                 <tr>
                                  <td width="35%"><b>Expiry Date:</b></td>
                                  <td><?php echo $row_getdetailspassport['expiry_date']; ?></td>
                                </tr>
                                   <tr>
                                  <td width="35%"><b>Data Source:</b></td>
                                  <td><?php echo $row_getdetailspassport['data_source']; ?></td>
                                </tr>
                            <!--     <tr>
                                  <td width="35%"><b>Date Added:</b></td>
                                  <td><?php echo $row_getdetailsid['date_added']; ?></td>
                                </tr>
                                
                          
                                <tr>
                                  <td><b>Match Status:</b> </td>
                                  <td> <?php
							 if($row_getdetailspassport['match_status']=='MATCH')
								  {
								  ?>
                                  <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                  <?php
								  }
								   if($row_getdetailspassport['match_status']=='UN MATCHED')
								  {
								 ?>
                                  <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">UN MATCHED</span></a>
                                  <?php
								  }
								  
								  ?></td>
                                </tr>-->
                             </table>	
                                   
									</div>
								</div>		</div>
                                
                    
                            <h6>COMMENTS:</h6>
					
 <table width="100%" bgcolor="#FFFFFF">
  <tr>

    <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailspassport['data_notes']; ?></p></td>
  </tr></table>
	<?php
	}
	?>	
    	<?php

$query_getdetailscredit = "SELECT * FROM pel_individual_credit_data WHERE search_id = '".$search_ref."' ORDER BY loan_status ASC";
$getdetailscredit  = mysqli_query($connect,$query_getdetailscredit ) or die(mysqli_error());
$row_getdetailscredit  = mysqli_fetch_assoc($getdetailscredit );
$totalRows_getdetailscredit  = mysqli_num_rows($getdetailscredit );	

$query_getdetailscredit2 = "SELECT * FROM pel_individual_credit_data WHERE search_id = '".$search_ref."' AND loan_status = 'OPEN' ORDER BY loan_status ASC";
$getdetailscredit2  = mysqli_query($connect,$query_getdetailscredit2) or die(mysqli_error());
$row_getdetailscredit2  = mysqli_fetch_assoc($getdetailscredit2);
$totalRows_getdetailscredit2  = mysqli_num_rows($getdetailscredit2);


if($totalRows_getdetailscredit>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="pe-7s-cash"></i>
									<h3>CREDIT CHECK DETAILS</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                                <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead  bgcolor="#0A4157">
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">SUBSCRIBER:</font></th>
                                                       <th><font color="#FFFFFF">LOAN TYPE:</font></th>
                                                     <th><font color="#FFFFFF">TOTAL AMOUNT:</font></th>
                                                     <th><font color="#FFFFFF">BALANCE:</font></th>
                                                    <th><font color="#FFFFFF">PAST DUE:</font></th>
                                                     <th><font color="#FFFFFF">LOAN STATUS:</font></th>
                                                   <th><font color="#FFFFFF">SOURCE:</font></th>
                                                   
                                          </tr>
                                  </thead>
                                  <tr><td colspan="7"><h5 align="left" class="smaller lighter blue"><strong>CLOSED LOANS: </strong> 
</h5></td></tr>
                                                   
                                                   <?php
												 
												  $total_closed = 0;
												  $total_balance_closed = 0;
												  $x=1;												  
												  do { 
	   if($row_getdetailscredit['loan_status']=='CLOSED')
{ ?>
                                                   	<tr>			     
                                           
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailscredit['subscriber']; ?> </a>																								</td>
                                               <td><?php echo $row_getdetailscredit['loan_type']; ?></td> 
                                                       <td><?php echo number_format($row_getdetailscredit['total_amount']); ?></td>
                                                          <td><?php echo number_format($row_getdetailscredit['balance']); ?></td>  
                                                           <td><?php echo $row_getdetailscredit['past_due']; ?></td>  
                                                           <td><?php 
														
														if($row_getdetailscredit['loan_status']=='CLOSED')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">CLOSED</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailscredit['loan_status']=='OPEN')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">OPEN</span></a>
                                                         <?php
														}
														?> </td>
                                                            
                                                             <td><?php echo $row_getdetailscredit['data_source']; ?></td>
                                                                                                       
                                                     
                                                   </tr>
                                                     <?php 
													  $total_closed= $total_closed + $row_getdetailscredit['total_amount'];
													 $total_balance_closed= $total_balance_closed + $row_getdetailscredit['balance'];
}
													 } while ($row_getdetailscredit = mysqli_fetch_assoc($getdetailscredit)); ?>
                                    <tr>
                                                      <td colspan="2" align="right"><strong>TOTAL:</strong> </td>
                                                      <td colspan="1"><strong><?php echo number_format($total_closed); ?></strong> </td>
                                                      <td colspan="1"><strong><?php echo number_format($total_balance_closed); ?></strong> </td>
                                                      <td colspan="5"></td></tr>
                                            
                                            <tr><td colspan="7"><h5 align="left" class=" smaller lighter blue"><strong>OPEN LOANS: </strong> 
</h5></td></tr>     


                                                               <?php
												  $total_open = 0;
												   $total_balance_open = 0;
												 
												  $x=1;												  
												  do { 
	   if($row_getdetailscredit2['loan_status']=='OPEN')
{
	   ?>    
                                                   	<tr>			     
                                           
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailscredit2['subscriber']; ?> </a>																								</td>
                                               <td><?php echo $row_getdetailscredit2['loan_type']; ?></td> 
                                                       <td><?php echo number_format($row_getdetailscredit2['total_amount']); ?></td>
                                                          <td><?php echo number_format($row_getdetailscredit2['balance']); ?></td>  
                                                           <td><?php echo $row_getdetailscredit2['past_due']; ?></td>  
                                                            <td><?php 
														
														if($row_getdetailscredit2['loan_status']=='CLOSED')
														{
														?>
                                                        
                                            <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">OPEN</span></a>
                                                        <?php
														}
														if($row_getdetailscredit2['loan_status']=='OPEN')
														{
														?>
                                                <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">OPEN</span></a>	
                                                         <?php
														}
														?> </td>
                                                            
                                                             <td><?php echo $row_getdetailscredit2['data_source']; ?></td>
                                                                                                       
                                                        </tr>
                                                     <?php
													 $total_balance_open= $total_balance_open + $row_getdetailscredit2['balance'];
													 $total_open= $total_open + $row_getdetailscredit2['total_amount'];
													  }
													 ?>
                                                  
                                                     
                                                     <?php
													 } while ($row_getdetailscredit2 = mysqli_fetch_assoc($getdetailscredit2)); ?>
                                                      <tr>
                                                      <td colspan="2" align="right"><strong>TOTAL:</strong> </td>
                                                        <td colspan="1"><strong><?php echo number_format($total_open,2); ?></strong> </td>
                                                      <td colspan="1"><strong><?php echo number_format($total_balance_open,2); ?></strong> </td>
                                                      <td colspan="4"></td></tr>



            
                                                   
								  </table>
                                            
                           <?php
						   
						   $query_getdetailscredit_comments = "SELECT * FROM pel_credit_data_comments WHERE search_id = '".$search_ref."'";
$getdetailscredit_comments  = mysqli_query($connect,$query_getdetailscredit_comments ) or die(mysqli_error());
$row_getdetailscredit_comments  = mysqli_fetch_assoc($getdetailscredit_comments );
$totalRows_getdetailscredit_comments  = mysqli_num_rows($getdetailscredit_comments );	
if($totalRows_getdetailscredit_comments>0)
{

?>                 
                                            
					 <h6>COMMENTS:</h6>
					
 <table width="100%" bgcolor="#FFFFFF">
  <tr>

    <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailscredit_comments['data_notes']; ?></p></td>
  </tr></table>			
							
					<?php
					}
					?>
	<?php
	}
	?>	
    
    
    
    
    	<?php

$query_getdetailscriminal = "SELECT * FROM pel_individual_criminal_data WHERE search_id = '".$search_ref."'";
$getdetailscriminal  = mysqli_query($connect,$query_getdetailscriminal) or die(mysqli_error());
$row_getdetailscriminal  = mysqli_fetch_assoc($getdetailscriminal );
$totalRows_getdetailscriminal  = mysqli_num_rows($getdetailscriminal );	
if($totalRows_getdetailscriminal>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-hammer"></i>
									<h3>CRIMINAL CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	<table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Name:</font></th>
                                                     <th><font color="#FFFFFF">Identity Number:</font></th>
                                                    <th><font color="#FFFFFF">PCC clearance Number:</font></th>
                                                     <th><font color="#FFFFFF">Data Source:</font></th>
                                               <th><font color="#FFFFFF">Status:</font></th>
                                                   
                                          </tr>
                                  </thead>

                                             
                                                   	<tr>			     
                                           
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailscriminal['first_name']; ?>  <?php echo $row_getdetailscriminal['second_name']; ?></a>																								</td>
                                               <td><?php echo $row_getdetailscriminal['identity_number']; ?></td> 
                                                       <td><?php echo $row_getdetailscriminal['clearance_ref_number']; ?></td>
                                                          <td><?php echo $row_getdetailscriminal['data_source']; ?></td>  
                                                           <td><?php echo $row_getdetailscriminal['criminal_offence_status']; ?></td>  
                                  </tr>
                                  </table>
                                             <hr>
                                             <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Finger Print Taken:</font></th>
                                               <th><font color="#FFFFFF">Finger Print From Source</font></th>
                                          </tr>
                                               </thead>
                                             
                                                   	<tr>			     
                                           
                                                      
                                                         <td><a href="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_pel']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_pel']; ?>" width="100px" height="100px" alt="Finger Print Thumb Right"></a> </td>
                                                         
                                                             <td><a href="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_src']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_src']; ?>" width="100px" height="100px" alt="Finger Print Thumb Right"></a> </td>
                                               </tr>
                                             </table>
                      <h6>COMMENTS:</h6>
					
 <table width="100%" bgcolor="#FFFFFF">
  <tr>

    <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailscriminal['data_notes']; ?></p></td></tr></table>
	<?php
	}
	?>	
    
  
    <?php

$query_getdetailstaxcompliance = "SELECT * FROM pel_individual_tax_data WHERE search_id = '".$search_ref."'";
$getdetailstaxcompliance  = mysqli_query($connect,$query_getdetailstaxcompliance ) or die(mysqli_error());
$row_getdetailstaxcompliance  = mysqli_fetch_assoc($getdetailstaxcompliance );
$totalRows_getdetailstaxcompliance  = mysqli_num_rows($getdetailstaxcompliance );	
if($totalRows_getdetailstaxcompliance>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-check"></i>
									<h3>TAX COMPLIANCE CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	<table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead> 
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Holders Name:</font></th>
                                                   <th><font color="#FFFFFF">Identity Name:</font></th>
                                                    <th><font color="#FFFFFF">Tax Organisation:</font></th>
                                                <th><font color="#FFFFFF">Tax Number:</font></th>
                                                  <th><font color="#FFFFFF">Country:</font></th>
                                                  <th><font color="#FFFFFF">Data Source:</font></th>                                                   <th><font color="#FFFFFF">Expiry Date:</font></th>
                                                    <th><font color="#FFFFFF">Compliance Status:</font></th>
                                          </tr>
                                  </thead>
                                                         <tr>			     
                                           	<tr>
                                                    	<td>
                                                   <a href="#"><?php echo $row_getdetailstaxcompliance['first_name']; ?> </a>																								</td>
                                                   <td><?php echo $row_getdetailstaxcompliance['identity_number']; ?></td> 
                                                   <td><?php echo $row_getdetailstaxcompliance['tax_organisation']; ?></td>
                                                   <td><?php echo $row_getdetailstaxcompliance['tax_number']; ?></td>  
                                                   <td><?php echo $row_getdetailstaxcompliance['country']; ?></td>
                                                   <td><?php echo $row_getdetailstaxcompliance['data_source']; ?></td>
                                                    <td><?php echo $row_getdetailstaxcompliance['expiry_date']; ?></td>  
                                                   <td><?php 
														
														if($row_getdetailstaxcompliance['tax_status']=='VALID')
														{
														?>
                                                        
                                                        <span class="label label-sm label-success">VALID</span>	
                                                        <?php
														}
														if($row_getdetailstaxcompliance['tax_status']=='EXPIRED')
														{
														?>
                                                        <span class="label label-sm label-danger">EXPIRED</span>	
                                                         <?php
														}
														?> </td>
                                                        </tr></table>
                    <?php
					
					if($row_getdetailstaxcompliance['tax_photo'] == '')
					{
					}
					else
					{
					?>                        
            <h6>Tax Compliance Certificate Photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/individual/taxcompliance/<?php echo $row_getdetailstaxcompliance['tax_photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/taxcompliance/<?php echo $row_getdetailstaxcompliance['tax_photo']; ?>" width="500px" height="400px" alt="Tax Compliance Cetficate Photo"></a> 
                      <?php
					}
					?>
								       <hr>
                                        <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailstaxcompliance['data_notes']; ?></p></td></tr></table>
	<?php
	}
	?>	
  
  
  
      <?php

$query_getdetailsdl = "SELECT * FROM pel_individual_dl_data WHERE search_id = '".$search_ref."'";
$getdetailsdl  = mysqli_query($connect,$query_getdetailsdl ) or die(mysqli_error());
$row_getdetailsdl  = mysqli_fetch_assoc($getdetailsdl );
$totalRows_getdetailsdl  = mysqli_num_rows($getdetailsdl );	
if($totalRows_getdetailsdl>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-globe"></i>
									<h3>DRIVING LICENSE CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	<table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Holders Name:</font></th>
                                                   <th><font color="#FFFFFF">Identity Name:</font></th>
                                                    <th><font color="#FFFFFF">License Number:</font></th>
                                                <th><font color="#FFFFFF">Class:</font></th>
                                                  <th><font color="#FFFFFF">Data Source:</font></th>                                                   <th><font color="#FFFFFF">Expiry Date:</font></th>
                                                    <th><font color="#FFFFFF">Status:</font></th>
                                          </tr>
                                  </thead>
                                                         <tr>			     
                                           <td>
                                                   <a href="#"><?php echo $row_getdetailsdl['first_name']; ?> <?php echo $row_getdetailsdl['second_name']; ?></a>																								</td>
                                               <td><?php echo $row_getdetailsdl['identity_number']; ?></td>
                                                          <td><?php echo $row_getdetailsdl['license_number']; ?></td>
                                                      <td><?php echo $row_getdetailsdl['class']; ?></td>
                                                                 <td><?php echo $row_getdetailsdl['data_source']; ?></td>
                                                                 
                                                                       <td><?php echo $row_getdetailsdl['expiry_date']; ?></td>
                                                                                                  <td><?php echo $row_getdetailsdl['dl_status']; ?></td></tr></table>
                    <?php
					
					if($row_getdetailsdl['dl_photo'] == '')
					{
					}
					else
					{
					?>                        
            <h6>DL photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/individual/dlphotos/<?php echo $row_getdetailsdl['dl_photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/dlphotos/<?php echo $row_getdetailsdl['dl_photo']; ?>" width="500px" height="400px" alt="DL Photo"></a> 
                      <?php
					}
					?>
								       <hr>
                                        <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsdl['data_notes']; ?></p></td></tr></table>
	<?php
	}
	?>	
     <?php

$query_getdetailspsv = "SELECT * FROM pel_individual_psv_data WHERE search_id = '".$search_ref."'";
$getdetailspsv  = mysqli_query($connect,$query_getdetailspsv ) or die(mysqli_error());
$row_getdetailspsv  = mysqli_fetch_assoc($getdetailspsv );
$totalRows_getdetailspsv  = mysqli_num_rows($getdetailspsv);	
if($totalRows_getdetailspsv>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-globe"></i>
									<h3>PSV LICENSE CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	<table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
												<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Holders Name:</font></th>
                                                     <th><font color="#FFFFFF">Identity Name:</font></th>
                                                    <th><font color="#FFFFFF">License Number:</font></th>
                                                    <th><font color="#FFFFFF">Operator License:</font></th>
                                                   <th><font color="#FFFFFF">Data Source:</font></th>                                                  <th><font color="#FFFFFF">Expiry Date:</font></th>
                                                   <th><font color="#FFFFFF">Status:</font></th>
                                          </tr>
                                  </thead>
                                                         <tr>			     
                                           <td>
                                                   <a href="#"><?php echo $row_getdetailspsv['first_name']; ?></a>																								</td>
                                               <td><?php echo $row_getdetailspsv['identity_number']; ?></td>
                                                          <td><?php echo $row_getdetailspsv['license_number']; ?></td>
                                                      <td><?php echo $row_getdetailspsv['operator_license']; ?></td>
                                                                 <td><?php echo $row_getdetailspsv['data_source']; ?></td>
                                                                 
                                                                       <td><?php echo $row_getdetailspsv['expiry_date']; ?></td>
                                                                                                  <td><?php echo $row_getdetailspsv['psv_status']; ?></td></tr></table>
                                            
             <?php
					
					if($row_getdetailspsv['psv_photo'] == '')
					{
					}
					else
					{
					?> <h6>PSV photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/individual/psvphotos/<?php echo $row_getdetailspsv['psv_photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/psvphotos/<?php echo $row_getdetailspsv['psv_photo']; ?>" width="500px" height="400px" alt="PSV Photo"></a> 
                                         <?php
					}
					?>
							   <hr>
                                        <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailspsv['data_notes']; ?></p>     </td></tr></table>
	<?php
	}
	?>	
    	
    
    
<!--  Finger Print Data  -->  
    
    
    	<?php

$query_getdetailsfprint = "SELECT * FROM pel_individual_fprint_data WHERE search_id = '".$search_ref."'";
$getdetailsfprint  = mysqli_query($connect,$query_getdetailsfprint) or die(mysqli_error());
$row_getdetailsfprint  = mysqli_fetch_assoc($getdetailsfprint );
$totalRows_getdetailsfprint  = mysqli_num_rows($getdetailsfprint );	
if($totalRows_getdetailsfprint>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-th-thumb"></i>
									<h3>FINGER PRINT ANALYSIS CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	
                                             <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
														<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Finger Print Taken:</font></th>
                                                        <th><font color="#FFFFFF">Identity Card Finger Print</font></th>
                                                      <th><font color="#FFFFFF">Match Status</font></th>
                                          </tr>
                                                                                                            
                                               </thead>
                                             
                                                   	<tr>			     
                                           
                                                      
                                                         <td><a href="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_pel']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_pel']; ?>" width="100px" height="100px" alt="Finger Print Thumb Right"></a> </td>
                                                         
                                                             <td><a href="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_src']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_src']; ?>" width="100px" height="100px" alt="Finger Print Thumb Right"></a> </td>
                                                               <td><?php echo $row_getdetailsfprint['match_status']; ?></td>
                                               </tr>
                                             </table>
                     <hr>
                                        <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsfprint['data_notes']; ?></p></td></tr></table>
	<?php
	}
	?>	
    
    <!--Proffessional Membership-->
  <?php

$query_getdetailsproffmembership = "SELECT * FROM pel_data_proff_membership WHERE search_id = '".$search_ref."' ";
$getdetailsproffmembership  = mysqli_query($connect,$query_getdetailsproffmembership ) or die(mysqli_error());
$row_getdetailsproffmembership = mysqli_fetch_assoc($getdetailsproffmembership );
$totalRows_getdetailsproffmembership  = mysqli_num_rows($getdetailsproffmembership );	
if($totalRows_getdetailsproffmembership>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-doc-text-inv"></i>
									<h3>PROFFESSIONAL MEMBERSHIP CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                  
                                                  <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">Membership Body:</font></th>
                                               <th><font color="#FFFFFF">Registration Date:</font></th>
                                             <th><font color="#FFFFFF">Data Source:</font></th>
                                  <th><font color="#FFFFFF">Status:</font></th>
                                             </tr>
                                                    </thead>
                                                   	<tr>			     
                                              	<td>
                                                   <a href="#"><?php echo $row_getdetailsproffmembership['membership_body']; ?> </a>																								</td>
                                               <td><?php echo $row_getdetailsproffmembership['registration_date']; ?></td> 
                                                       <td><?php echo $row_getdetailsproffmembership['data_source']; ?></td>
                                                   <td><?php 
														
														if($row_getdetailsproffmembership['membership_status']=='ACTIVE')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">ACTIVE</span></a>
                                                              <?php
														}
														if($row_getdetailsproffmembership['membership_status']=='NON ACTIVE')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">NON ACTIVE</span></a>
                                                         <?php
														}
														?> </td>
                                                    </tr>   
                                                   </table>
                                        
                                               <hr>                          
                             <?php
					
					if($row_getdetailsproffmembership['membership_certificate'] == '')
					{
					}
					else
					{
					?> <h6>Certificate Scan Photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/individual/membershipcertificate/<?php echo $row_getdetailsproffmembership['membership_certificate']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/membershipcertificate/<?php echo $row_getdetailsproffmembership['membership_certificate']; ?>" width="500px" height="400px" alt="Certificate Photo"></a> 
                                         <?php
					}
					?>  
                             <hr>      
                                           <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsproffmembership['data_notes']; ?></p></td></tr></table>
                                                     
                                                   
                                               <hr>                    
                                                   
                                                     <?php } while ($row_getdetailsproffmembership = mysqli_fetch_assoc($getdetailsproffmembership)); ?>
	<?php
	}
	?>	
  
<!--  Education Data  -->
    
	<?php

$query_getdetailsedu = "SELECT * FROM pel_psmt_edu_data WHERE search_id = '".$search_ref."'";
$getdetailsedu  = mysqli_query($connect,$query_getdetailsedu ) or die(mysqli_error());
$row_getdetailsedu  = mysqli_fetch_assoc($getdetailsedu );
$totalRows_getdetailsedu  = mysqli_num_rows($getdetailsedu );	
if($totalRows_getdetailsedu>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="pe-7s-study"></i>
									<h3>EDUCATION CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<?php
												  
												  $x=1;												  
												  do { ?>
                                <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF"><strong>Data Set:</strong></font></th>
													  <th><font color="#FFFFFF"><strong>Details Provided:</strong></font></th>                                                    <th><font color="#FFFFFF"><strong>Details Verified:</strong></font></th>
                                                    <th><font color="#FFFFFF"><strong>Match Status:</strong></font></th>
                                      </tr>
                                      </thead>
                              <tr><td><strong>Student Name:</strong></td>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['name_provided']; ?></a>																								</td>
                                                     <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['edu_name']; ?></a>																								</td>
                                                     <td><?php 
														
														if($row_getdetailsedu['match_status_name']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsedu['match_status_name']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                      </tr>
                                                   
                                                     <tr><td><strong>Institution Name:</strong></td>
                                                           <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['institution_provided']; ?></a>																								</td>
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['edu_institution']; ?></a>																								</td>
                                                
                                                     <td><?php 
														
														if($row_getdetailsedu['match_status_insititution']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsedu['match_status_insititution']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                                  
                                                  
                                                         <tr><td><strong>Course Name:</strong></td>
                                                          <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['course_provided']; ?></a>																								</td>
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['edu_course']; ?></a>																								</td>
                                                 
                                                     <td><?php 
														
														if($row_getdetailsedu['match_status_course']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsedu['match_status_course']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                                   
                                                   
                                                         <tr><td><strong>Award:</strong></td>
                                                      
                                                      <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['award_provided']; ?></a>																								</td>	  <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['edu_award']; ?></a>																								</td>
                                                  
                                                     <td><?php 
														
														if($row_getdetailsedu['match_status_award']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsedu['match_status_award']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                                   
                                                         <tr><td><strong>Year:</strong></td>
                                                       <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['year_provided']; ?></a>																								</td>
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsedu['edu_graduation_year']; ?></a>																								</td>
                                                    
                                                     <td><?php 
														
														if($row_getdetailsedu['match_status_year']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsedu['match_status_year']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                         
                             </table>	
								      <hr>                          
                             <?php
					
					if($row_getdetailsedu['certificate_photo'] == '')
					{
					}
					else
					{
					?> <h6>Certificate Scan Photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/individual/educationcertificates/<?php echo $row_getdetailsedu['certificate_photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/educationcertificates/<?php echo $row_getdetailsedu['certificate_photo']; ?>" width="500px" height="400px" alt="Certificate Photo"></a> 
                                         <?php
					}
					?>  	<hr>            
						    <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsedu['data_notes']; ?></p></td></tr></table>       	<hr>
							    <?php } while ($row_getdetailsedu = mysqli_fetch_assoc($getdetailsedu)); ?>
	<?php
	}
	?>	
    
    
    <!--  Employment Details  -->
    
	<?php

$query_getdetailsemployment = "SELECT * FROM pel_psmt_employ_data WHERE search_id = '".$search_ref."'";
$getdetailsemployment  = mysqli_query($connect,$query_getdetailsemployment ) or die(mysqli_error());
$row_getdetailsemployment  = mysqli_fetch_assoc($getdetailsemployment );
$totalRows_getdetailsemployment  = mysqli_num_rows($getdetailsemployment );	
if($totalRows_getdetailsemployment>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-briefcase"></i>
									<h3>EMPLOYMENT CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<?php
												  
												  $x=1;												  
												  do { ?>
                                  <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF"><strong>Data Set:</strong></font></th>
													    <th><font color="#FFFFFF"><strong>Details Provided:</strong></font></th>                                                       <th><font color="#FFFFFF"><strong>Details Verified:</strong></font></th>
                                                      <th><font color="#FFFFFF"><strong>Match Status:</strong></font></th>
                                      </tr></thead>
                              <tr><td><strong>Individual Name:</strong></td>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['name_provided']; ?></a>																								</td>
                                                     <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['name_provided']; ?></a>																								</td>
                                                     <td>-</td>  
                                      </tr>
                                                   
                                                     <tr><td><strong>Organization Name:</strong></td>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['organisation_provided']; ?></a>																								</td>
                                                     <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['verified_organisation']; ?></a>																								</td>
                                                     <td><?php 
														
														if($row_getdetailsemployment['match_status_organisation']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsemployment['match_status_organisation']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                                  
                                                  
                                                         <tr><td><strong>Position:</strong></td>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['position_provided']; ?></a>																								</td>
                                                     <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['verified_position']; ?></a>																								</td>
                                                     <td><?php 
														
														if($row_getdetailsemployment['match_status_position']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsemployment['match_status_position']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                                   
                                                   
                                                         <tr><td><strong>Leaving Reason:</strong></td>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['leaving_reason_provided']; ?></a>																								</td>
                                                     <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['verified_leaving_reason']; ?></a>																								</td>
                                                     <td><?php 
														
														if($row_getdetailsemployment['match_status_leaving_reason']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsemployment['match_status_leaving_reason']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                                   
                                                         <tr><td><strong>Year:</strong></td>
                                                      
                                                   	  <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['period_provided']; ?></a>																								</td>
                                                     <td>
                                                   <a href="#"><?php echo $row_getdetailsemployment['verified_period']; ?></a>																								</td>
                                                     <td><?php 
														
														if($row_getdetailsemployment['match_status_period']=='MATCH')
														{
														?>
                                                          <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                                  
                                                        <?php
														}
														if($row_getdetailsemployment['match_status_period']=='DOESNT MATCH')
														{
														?>
                                                 <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                                                         <?php
														}
														?> </td>  
                                                   </tr>
                                         
                             </table>
                               <hr>                          
                             <?php
					
					if($row_getdetailsemployment['employment_reference_photo'] == '')
					{
					}
					else
					{
					?> <h6>Reference Letter Scan Photo:</h6>	
                                         <a href="http://46.101.16.235/pidva/html/individual/employementreference/<?php echo $row_getdetailsemployment['employment_reference_photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/employementreference/<?php echo $row_getdetailsemployment['employment_reference_photo']; ?>" width="500px" height="400px" alt="Reference Letter Photo"></a> 
                                         <?php
					}
					?> 	<hr>             	
							  <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsemployment['data_notes']; ?></p></td></tr></table>
  <hr>
							    <?php } while ($row_getdetailsemployment = mysqli_fetch_assoc($getdetailsemployment)); ?>
	<?php
	}
	?>	
    
    
      <!--Gap ANalysis-->
  <?php

$query_getdetailsgapanalysis = "SELECT * FROM pel_individual_gap_data WHERE search_id = '".$search_ref."' ";
$getdetailsgapanalysis  = mysqli_query($connect,$query_getdetailsgapanalysis ) or die(mysqli_error());
$row_getdetailsgapanalysis = mysqli_fetch_assoc($getdetailsgapanalysis );
$totalRows_getdetailsgapanalysis  = mysqli_num_rows($getdetailsgapanalysis );	
if($totalRows_getdetailsgapanalysis>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-calendar-5"></i>
									<h3>GAP ANALYSIS CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                  
                                                  <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>									
													
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF">GAP:</font></th>
                                               <th><font color="#FFFFFF">From:</font></th>
                                             <th><font color="#FFFFFF">To:</font></th>
                                  <th><font color="#FFFFFF">Data Source:</font></th>
                                             </tr>
                                                    </thead>
                                                   	<tr>			     
                                              	<td>
                                                   <a href="#"><?php echo $row_getdetailsgapanalysis['gap_name']; ?> </a>																								</td>
                                                    <td><?php echo $row_getdetailsgapanalysis['from_date']; ?></td>
                                                    <td><?php echo $row_getdetailsgapanalysis['to_date']; ?></td> 
                                                  
                                                   <td><?php echo $row_getdetailsgapanalysis['data_source']; ?></td>
                                                    </tr>   
                                                   </table>
                                        
                                               <hr>                          
                          <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsgapanalysis['data_notes']; ?></p></td></tr></table>
                                                     
                                                   
                                               <hr>                    
                                                   
                                                     <?php } while ($row_getdetailsgapanalysis = mysqli_fetch_assoc($getdetailsgapanalysis)); ?>
	<?php
	}
	?>	
  
    
    
<!--  Residence Data check-->
        
    	<?php

$query_getdetailsresidency = "SELECT * FROM pel_data_residence WHERE search_id = '".$search_ref."'";
$getdetailsresidency  = mysqli_query($connect,$query_getdetailsresidency ) or die(mysqli_error());
$row_getdetailsresidency  = mysqli_fetch_assoc($getdetailsresidency );
$totalRows_getdetailsresidency  = mysqli_num_rows($getdetailsresidency );	
if($totalRows_getdetailsresidency>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-location-1"></i>
									<h3>RESIDENCY DETAILS:</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<table  id="simple-table" class="table  table-striped  table-bordered table-hover">
                            			<tr>
										
													  <th>Building Name:</th>
                                                      
                                                       	<td>
                                                   <a href="#"><?php echo $row_getdetailsresidency['building_name']; ?></a>																								</td></tr>
                                                   <tr>
                                                      
                                                   
														<th>Physical Address</th>
                                                          <td><?php echo $row_getdetailsresidency['physical_address']; ?></td>
                                      </tr><tr>
                                                        <th>Street</th>
                                                          <td><?php echo $row_getdetailsresidency['street']; ?></td>
                                                        </tr>
                                                        <tr>
                                                         <th>House Number</th>
                                                     <td><?php echo $row_getdetailsresidency['house_number']; ?></td>
                                                     </tr>
                                                   
                                                      <tr>
                                                         <th>Country</th>
                                                     <td><?php echo $row_getdetailsresidency['country']; ?></td>
                                                     </tr>                                                
                                                    
                                                      <tr>
                                                        <th class="hidden-480">Data Source</th>
										
                                                     
                                                      <td class="hidden-480">
                                                        
                                                      <?php echo $row_getdetailsresidency['data_source']; ?></td>
													</tr>
							<tr bgcolor="#0A4157">									
													  <th colspan="2"  bgcolor="#0A4157"><font color="#FFFFFF"><strong>Plot Image:</strong></font></th>
										
                                      </tr>
                                               <tr>      
                                                      <td colspan="2" class="hidden-480"><img src="http://46.101.16.235/pidva/html/individual/residencephotos/<?php echo $row_getdetailsresidency['building_photo']; ?>" alt="Location Photo" class="img-fluid"></td>
									  </tr>
                                    
								  </table>
							<h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailsresidency['data_notes']; ?></p></td></tr></table>
	<?php
	}
	?>	
    
    
    
    <?php

$query_getdetailssocial = "SELECT * FROM pel_data_social_media WHERE search_id = '".$search_ref."'";
$getdetailssocial  = mysqli_query($connect,$query_getdetailssocial ) or die(mysqli_error());
$row_getdetailssocial  = mysqli_fetch_assoc($getdetailssocial );
$totalRows_getdetailssocial  = mysqli_num_rows($getdetailssocial );	
if($totalRows_getdetailssocial>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-network"></i>
									<h3>SOCIAL MEDIA CHECK</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                        
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                    <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
													<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF"><strong>Source Name:</strong></font></th>
                                                   <th><font color="#FFFFFF"><strong>Adverse Mentions Status:</strong></font></th>
                                                    <th><font color="#FFFFFF"><strong>Social Media Handle:</strong></font></th>
                                                       <th><font color="#FFFFFF"><strong>Data Source:</strong></font></th>
                                                                                                         
                                                      
                                          </tr>
                                  </thead>
                                                   	<tr>			     
                                             	<td>
                                                   <a href="#"><?php echo $row_getdetailssocial['website']; ?></a>																								</td>
                                              
                                                          <td><?php echo $row_getdetailssocial['adverse_status']; ?></td>
                                                      
                                                          <td><?php echo $row_getdetailssocial['social_media_handle']; ?></td>
                                                                      <td><?php echo $row_getdetailssocial['data_source']; ?></td>
                                                 </tr> 
                                      <thead>     	<tr bgcolor="#0A4157">
                                                        <th colspan="4" class="hidden-480"><font color="#FFFFFF"><strong>Adverse Mention Caption:</strong></font></th>
										
                                      </tr></thead> 
                                                     
                                        	<tr>              <td  colspan="4"><a href="http://46.101.16.235/pidva/html/individual/socialmediaphotos/<?php echo $row_getdetailssocial['photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/socialmediaphotos/<?php echo $row_getdetailssocial['photo']; ?>" width="100%" alt="Social Media Caption"></a>  </td> </tr>  </table>
                                    <hr>               
                 <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailssocial['data_notes']; ?></p></td></tr></table>
  <hr>
                                                     <?php } while ($row_getdetailssocial = mysqli_fetch_assoc($getdetailssocial)); ?>
	<?php
	}
	?>	
<!--    watchlist global data	-->
    
        <?php

$query_getdetailswatchlist = "SELECT * FROM pel_individual_watchlist_data WHERE search_id = '".$search_ref."'";
$getdetailswatchlist  = mysqli_query($connect,$query_getdetailswatchlist ) or die(mysqli_error());
$row_getdetailswatchlist  = mysqli_fetch_assoc($getdetailswatchlist );
$totalRows_getdetailswatchlist  = mysqli_num_rows($getdetailswatchlist );	
if($totalRows_getdetailswatchlist>0)
{
?>							
						
	<hr>                                    
                         								
   <div class="indent_title_in">
									<i class="icon-globe"></i>
									<h3>GLOBAL WATCHLIST</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
                             	
                                                   
                                                   <?php
												  
												  $x=1;												  
												  do { ?>
                                                  <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
												<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF"><strong>Name:</strong></font></th>
                                                    <th><font color="#FFFFFF"><strong>Fathers Name:</strong></font></th>
                                                   <th><font color="#FFFFFF"><strong>Date of Birth:</strong></font></th>
                                                <th><font color="#FFFFFF"><strong>Data Source:</strong></font></th>
                                             <th><font color="#FFFFFF"><strong>Watchlist Status:</strong></font></th>
                                                
                                          </tr>
                                                    </thead>
                                                   	<tr>			     
                                                  	<td>
                                                   <a href="#"><?php echo $row_getdetailswatchlist['first_name']; ?> <?php echo $row_getdetailswatchlist['second_name']; ?></a>																								</td>
                                               <td><?php echo $row_getdetailswatchlist['father_name']; ?></td>
                                               
                                                  <td><?php echo $row_getdetailswatchlist['date_of_birth']; ?></td>
                                                           <td><?php echo $row_getdetailswatchlist['data_source']; ?></td>
                                                    
                                                          <td><?php echo $row_getdetailswatchlist['watchlist_status']; ?></td>
                                                 </tr>	
                                                 
                                  <thead>	<tr bgcolor="#0A4157">
                                                        <th colspan="5" class="hidden-480"><font color="#FFFFFF"><strong>Glaboal Watchlist Caption:</strong></font></th>
										
                                      </tr>  </thead>              
                                                 
                                                 
                                                 <tr>
                                                   
                                                     <td colspan="5"><a href="http://46.101.16.235/pidva/html/individual/watchlistphotos/<?php echo $row_getdetailswatchlist['photo']; ?>" target="_blank"><img src="http://46.101.16.235/pidva/html/individual/watchlistphotos/<?php echo $row_getdetailswatchlist['photo']; ?>"  alt="Global Watchlist Caption"  class="img-fluid"></a> </td>      
                                                  
                                                    </tr>	
                                            </table>
                                              
                                        <hr>               
                 <h6>COMMENTS:</h6>	
                          <table width="100%" bgcolor="#FFFFFF">
  <tr> <td bordercolor="#BEE8F8" style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;"><p><?php echo $row_getdetailswatchlist['data_notes']; ?></p></td></tr></table>
  <hr>  
                                                   
                                                   
                                                     <?php } while ($row_getdetailswatchlist = mysqli_fetch_assoc($getdetailswatchlist)); ?>
	<?php
	}
	?>	
    

<?php

}
else if ($row_getrequestdetails['report_file'] != '00')
{

?><hr>    <table id="simple-table" class="table  table-striped table-bordered table-hover">
										<thead>
												<tr bgcolor="#0A4157">									
													  <th><font color="#FFFFFF"><strong>Date Uploaded:</strong></font></th>
                                               <th><font color="#FFFFFF"><strong>File Name:</strong></font></th>
                                              <th><font color="#FFFFFF"><strong>Download:</strong></font></th>
                                          </tr>
                                  </thead>
                                             	<tr>	<td>
                                                   <a href="#"><?php echo $row_getrequestdetails['notify_date']; ?></a>		</td>																						
                                                     <td><?php echo $row_getrequestdetails['report_file']; ?></td>
  <td><a href="http://46.101.16.235/pidva/html/searches/reportfiles/<?php echo $row_getrequestdetails['report_file']; ?>" target="_blank"><img align="right" src="../img/pdficon.png"></a></td>
 </tr></table>
   
<?php	
}
?>
  <hr>
    	<div class="indent_title_in">
								
									<h3>DISCLAIMER</h3>
						<!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
				  </div>
								<p>The records contained in this reports are compiled from various databases that may only be updated infrequently, and therefore, may not have the most current information. This report is not intended to serve as recommendation of whether to hire the candidate investigated.</p>
<p>  This report is submitted in strict confidence and except where required by law, no information provided in our reports may be revealed directly or indirectly to any person except to those whose official  duties require them to pass this report on in relation to which the report was requested by the client.</p>
<p>  Peleza International Limited neither warrants, vouches for, or authenticates the reliability of the information contained herein that the records are accurately reported as they were found at the source as of  the date and time of this report, whether on a computer information system, retrieved by manual search, or telephonic interviews. </p>
<p>The information provided herein shall not be construed to constitute a  legal opinion; rather it is a compilation of public records and/or data for your review. Peleza International Limited shall not be liable for any losses or injuries now or in the future resulting from or relating  to the information provided herein.<br />
  </p>
<p>The recommended searches provided on our website should not serve as legal advice for your background investigation. You should always seek legal advice from your attorney. The recommended  searches are provided to help orient you to searches you may want to consider for a particular job classification. We will work with you to create a background investigation specific to your industry needs.</p>
<p>  Take note that for unqualified verification of a candidate's criminal record, fingerprints are required to be analyzed against the criminals database held by the Directorate of Criminal investigations, a  passport number for the case of foreigners or an identity number for the case of Kenyan nationals, by itself is not entirely sufficient.</p>
                          
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->





</body>
</html>
<?php

?>						
<?php
/*$html = ob_get_clean();
$dompdf = new DOMPDF();
$dompdf->setPaper('A4', 'landscape');
$dompdf->load_html($html);
$dompdf->render();

$name = $search_ref."-".$row_getrequestdetails['bg_dataset_name'];
//For view
//$dompdf->stream("",array("Attachment" => false));
 //for download
$dompdf->stream($name);*/
?>