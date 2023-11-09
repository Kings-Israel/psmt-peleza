<?php require_once('../Connections/process.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
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


$company_name = "Peleza International";

if (isset($_SESSION['MM_client_parent_company'])) {

    $company_name = $_SESSION['MM_client_parent_company'];

}

$filter_status = isset($_GET['status']) ? $_GET['status'] : -1;

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

        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

            <!--Peleza-->
        <!-- BASE CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css?=<?= rand(0,999999) ?>" rel="stylesheet">
        <link href="../css/menu.css" rel="stylesheet">
        <link href="../css/vendors.css" rel="stylesheet">
        <link href="../css/icon_fonts/css/all_icons_min.css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body>
    <style>

        .icon {
            position: relative;
            /* Adjust these values accordingly */
            top: 7px;
            padding: 10px;
        }


        a.btn_p,
        .btn_p {
            border: none;
            color: #fff;
            background: #153f56;
            cursor: pointer;
            padding: 7px 20px;
            display: inline-block;
            outline: none;
            font-size: 14px;
            font-size: 0.875rem;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            -webkit-transition: all 0.3s ease-in-out;
            -ms-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            -webkit-border-radius: 25px;
            -moz-border-radius: 25px;
            -ms-border-radius: 25px;
            border-radius: 5px;
            font-weight: 500;
        }
        a.btn_p.status_22,
        .btn_p.status_22 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #0058a2;
            color: #ffffff;
        }
        a.btn_p.status_00,
        .btn_p.status_00 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #33007f;
            color: #ffffff;
        }
        a.btn_p.status_33,
        .btn_p.status_33 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #00967b;
            color: #ffffff;
        }
        a.btn_p.status_11,
        .btn_p.status_11 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #d14e00;
            color: #ffffff;
        }
        a.btn_p.status_44,
        .btn_p.status_44 {
            padding:5px 5px;
            font-size: 7px;
            font-size: 0.8rem;
            background: #960040;
            color: #ffffff;
        }
    </style>

    <span id="login-id" style="display: none"><?= $client_login_id_get_psmt_requests ?></span>
    <span id="company-name" style="display: none"><?= $company_name ?></span>
    <span id="client-id" style="display: none"><?= $client_id_get_psmt_requests ?></span>
    <span id="filter-status" style="display: none"><?= $filter_status ?></span>

    <div class="app-container body-tabs-shadow fixed-sidebar">
        <?php include '../partials/header.php'; ?>

        <div class="app-main">

            <?php include '../partials/sidebar.php'; ?>

            <div class="app-main__outer">

                <?php include '../partials/top-header.php'; ?>

                <!--Body-->
                <main id="dashboard-stats">

                    <div class="container margin_60">

                        <div class="card-group" style="margin: 10px">

                            <div class="card">
                                <div class="card-body">
                                    <div class="main-card mb-3 card text-white" style="background-color: #330076">
                                        <div class="card-body text-center">
                                            <span style="font-size: 3em"><span v-text="ifnull(stats.new_request,0)"></span></span>
                                            <div class="widget-subheading">New Request</div>
                                        </div>
                                        <div>
                                            <hr style="color: white!important; background-color: white; margin: 4px">
                                            <p class="" style="text-align: center">
                                                <a href="index.php?status=00" style="color: white !important;">View All</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card">
                                <div class="card-body">
                                    <div class="main-card mb-3 card text-white" style="background-color: orange">
                                        <div class="card-body text-center">
                                            <span style="font-size: 3em"><span v-text="ifnull(stats.interim,0)"></span></span>
                                            <div class="widget-subheading">Interim</div>
                                        </div>
                                        <div>
                                            <hr style="color: white!important; background-color: white; margin: 4px">
                                            <p class="" style="text-align: center">
                                                <a href="index.php?status=33" style="color: white !important;">View All</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="main-card mb-3 card text-white" style="background-color: blue">
                                        <div class="card-body text-center">
                                            <span style="font-size: 3em"><span v-text="ifnull(stats.progress,0)"></span></span>
                                            <div class="widget-subheading">In Progress</div>
                                        </div>
                                        <div>
                                            <hr style="color: white!important; background-color: white; margin: 4px">
                                            <p class="" style="text-align: center">
                                                <a href="index.php?status=44" style="color: white !important;">View All</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card" style="display: none">
                                <div class="card-body">
                                    <div class="main-card mb-3 card text-white" style="background-color: #980042">
                                        <div class="card-body text-center">
                                            <span style="font-size: 3em"><span v-text="ifnull(stats.pending,0)"></span> </span>
                                            <div class="widget-subheading">Pending</div>
                                        </div>
                                        <div>
                                            <hr style="color: white!important; background-color: white; margin: 4px">
                                            <p class="" style="text-align: center">
                                                <a href="index.php?status=pending" style="color: white !important;">View All</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="main-card mb-3 card text-white" style="background-color: green">
                                        <div class="card-body text-center">
                                            <span style="font-size: 3em"><span v-text="ifnull(stats.final_report,0)"></span></span>
                                            <div class="widget-subheading">Completed</div>
                                        </div>
                                        <div>
                                            <hr style="color: white!important; background-color: white; margin: 4px">
                                            <p class="" style="text-align: center">
                                                <a href="index.php?status=11" style="color: white !important;">View All</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="main-card mb-3 card text-white" style="background-color: red">
                                        <div class="card-body text-center">
                                            <span style="font-size: 3em"><span>0</span></span>
                                            <div class="widget-subheading">Overdue</div>
                                        </div>
                                        <div>
                                            <hr style="color: white!important; background-color: white; margin: 4px">
                                            <p class="" style="text-align: center">
                                                <a href="#" style="color: white !important;">View All</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card" style="margin: 10px">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="icon material-icons">bar_chart</i> <span id="report-name"></span><span>Reports</span>
                                </h5>
                                <table id="idrequests-table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th><i class="icon material-icons">business_center</i>Package</th>
                                        <th><i class="icon material-icons">contacts</i>Name</th>
                                        <th><i class="icon material-icons">filter_1</i>Ref</th>
                                        <th><i class="icon material-icons">event</i>Date</th>
                                        <th><i class="icon material-icons">transfer_within_a_station</i>Progress</th>
                                        <th>Status</th>
                                        <th>More</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 20px">
                        <div class="row content">
                            <div class="col-sm-6">
                                <div class="card" style="background-color: #f5c0ba">
                                    <div class="card-body" style="margin-bottom: 10px">
                                        <h5 class="card-title"><i class="material-icons md-48" style="font-size: 38px; position: relative; top: 10px; padding-right: 10px">system_update_alt</i>Download Consent Form</h5>
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <img src="../img/pdf.png" class="img-fluid" alt="">
                                            </div>
                                            <div class="col">
                                                <div class="card-block px-2">
                                                    <p class="card-text">All Documents are subject to copyright and sole property of Peleza International Limited.
                                                        Documents shall not be sent and shared with third party without prior consent of Peleza International Limited</p>
                                                    <button class="btn btn-outline-danger" @click="printer">Download Now</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card" style="background-color: rgb(183, 199, 75); color: white">
                                    <div class="card-body" style="margin-bottom: 10px">
                                        <h5 class="card-title"><i class="material-icons md-48" style="font-size: 30px; position: relative; top: 10px; padding-right: 10px">system_update_alt</i>Make a Request</h5>
                                        <div class="card-text">
                                            <div>
                                                <b>COMPANY PACKAGE: </b>Select the background checks you wish to conduct<br>
                                                <a href="/bulk.php" class="btn btn-outline-danger">Make Request</a><br><br>
                                            </div>
                                            <div>
                                                <b>INDIVIDUAL PACKAGE: </b>Select the background checks you wish to conduct<br>
                                                <a href="/request1.php" class="btn btn-outline-danger">Make Request</a><br><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </main>

            </div>
        </div>
    <?php include '../partials/footer.php'; ?>
    <!--Footer-->


    <!--DataTables-->
    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='../js/jquery.js'>" + "<" + "/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='../js/jquery1x.js'>" + "<" + "/script>");
    </script>

    <script src="../js/jquery.js"></script>

    <![endif]-->
    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement) document.write("<script src='../js/jquery.mobile.custom.js'>" + "<" + "/script>");
    </script>
    <script src="../js/bootstrap.js"></script>

    <!-- page specific plugin scripts -->
    <script src="../js/dataTables/jquery.dataTables.js"></script>
    <script src="../js/dataTables/jquery.dataTables.bootstrap.js"></script>
    <script src="../js/dataTables/jquery.dataTables.js"></script>
    <script src="../js/dataTables/jquery.dataTables.bootstrap.js"></script>
    <script src="../js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
    <script src="../js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <script type="text/javascript" src="../assets/scripts/main.js"></script>
    <script src="../v1/js/vue.js" type="text/javascript"></script>
    <script src="../v1/js/axios.min.js" type="text/javascript"></script>
    <script src="../v1/js/dashboard-stats.js?<?= rand(0,1000) ?>" type="text/javascript"></script>
        <script src="/js/logo.js?<?= rand(0,1000) ?>" type="text/javascript"></script>

    <script>
        $(document).ready( function () {

            //$('.datatable').DataTable();
            $(document).ready(function() {

                // $('input[name="reportrange"]').daterangepicker();
                var status = document.getElementById('filter-status').innerHTML;
                var login_id = document.getElementById('login-id').innerHTML;
                var client_id = document.getElementById('client-id').innerHTML;

                var url = location.origin+"/dataTable/php/idrequests.php?id="+login_id+"&status="+status+"&client_id="+client_id;
                var baseURL = window.location.protocol + '//' + window.location.hostname + "/";

                var tableAllEntries = $('#idrequests-table')
                    .DataTable({
                        'paging': true,
                        'lengthChange': true,
                        'searching': true,
                        'ordering': true,
                        'info': true,
                        'autoWidth': false,
                        "serverSide": true,
                        "processing": true,
                        "pageLength": 10,
                        'dom': 'Bfrtip',
                        'buttons': [
                            'pdf','print','csv'
                        ],
                        "ajax": url,
                        "order": [[ 4, "desc" ]],
                        "drawCallback": function( settings ) {

                            loadProgress();
                        }
                    }).draw();

                tableAllEntries.on( 'order.dt search.dt', function () {

                    tableAllEntries.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );

                } ).draw();

                var report_name = "All";

                switch (status) {

                    case '44':
                        report_name = "In Progress";
                        break;

                    case '00':
                        report_name = "New Request";
                        break;

                    case '11':
                        report_name = "Final";
                        break;

                    case '33':
                        report_name = "Interim";
                        break;

                    case '22':
                    case '55':
                    case '66':
                        report_name = "Pending";
                        break;

                    default:
                        report_name = "All";
                        break;

                }

                document.getElementById('report-name').innerText = report_name+" ";

                function loadProgress() {

                    console.log('GOT HERE loadProgress ');

                    $.each($('[id^="rid-"]'),function(k,v){

                        var id = v.getAttribute('id').split('rid-').join('');

                        var data = {
                            type: 'progress',
                            reference_number: id
                        };

                        var ur = baseURL + 'v1/api/Dashboard.php';

                        axios.post(ur, data)
                            .then(function (response) {

                                var p = response.data.percentage;

                                $(".rid-"+id).text(p+' Completed ');

                                $("#rid-"+id).animate({

                                    width: p

                                }, 1000 );


                            })
                            .catch(function (error) {

                                console.log(error);

                            });

                    })
                }

            });


        });
    </script>
    </body>
    </html>
