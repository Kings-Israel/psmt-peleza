<?php

$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
$host = $_SERVER['SERVER_NAME'];
$baseURL = $protocol . "$host/";

$client_id = "-1";
if (isset($_GET['client_id'])) {

    $client_id = $_GET['client_id'];
}

$reportURL = $baseURL . "v1/main.php?client_id=" . $client_id . "&request_id=";
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

//require '../../../config/config.php';

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
$status = isset($_GET['status']) ? $_GET['status'] : -1;
$client_login_id_get_psmt_requests = isset($_GET['id']) ? $_GET['id'] : -1;

$configs = parse_ini_file("../../Connections/config.ini", true);
$name = $configs['db']['name'];
$configs = $configs[$name];

$hostname_connect = $configs['host'];
$database_connect = $configs['dbname'];
$username_connect = $configs['username'];
$password_connect = $configs['password'];
$port = $configs['port'];

// DB table to use
$table = 'pel_psmt_request';

// Table's primary key
$primaryKey = 'request_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
    array('db' => "1", 'dt' => 0, 'field' => 'request_id'),
    array('db' => "`a`.`request_plan`", 'dt' => 1, 'field' => 'request_plan'),
    array('db' => '`a`.`bg_dataset_name`', 'dt' => 2, 'field' => 'bg_dataset_name', 'formatter' => function ($d, $row) {

        $r = intval($row['request_id']);

        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
        $host = $_SERVER['SERVER_NAME'];
        $baseURL = $protocol . "$host/";
        $client_id = "-1";
        if (isset($_GET['client_id'])) {

            $client_id = $_GET['client_id'];
        }

        $reportURL = $baseURL . "v1/main.php?client_id=" . $row['client_id'] . "&request_id=$r";

        return "<a href='$reportURL' target='_blank'>$d</a>";
    }),
    array('db' => '`a`.`request_ref_number`', 'dt' => 3, 'field' => 'request_ref_number', 'formatter' => function ($d, $row) {

        $r = intval($row['request_id']);

        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
        $host = $_SERVER['SERVER_NAME'];
        $baseURL = $protocol . "$host/";
        $client_id = "-1";
        if (isset($_GET['client_id'])) {

            $client_id = $_GET['client_id'];
        }

        $reportURL = $baseURL . "v1/main.php?client_id=" . $row['client_id'] . "&request_id=$r";

        return "<a href='$reportURL' target='_blank'>$d</a>";
    }),
    array('db' => "`a`.`request_date`", 'dt' => 4, 'field' => 'request_date'),
    array('db' => '`a`.client_id', 'dt' => 5, 'field' => 'client_id', 'formatter' => function ($d, $row) {

        $r = $row['request_ref_number'];

        return
            '<div class="progress" style="height: 25px; background-color: #c6c6c6;">
                <div class="progress-bar progress-bar-striped bg-success" id="rid-' . $r . '" style="width:0%">
                    <span class="rid-' . $r . '" style="font-weight: bold;padding-left: 5px;"></span>
                </div>
            </div>';
    }),
    array('db' => '`a`.`status`', 'dt' => 6, 'field' => 'status', 'formatter' => function ($d, $row) {

        switch ($d) {

            case "44":
                return '<a href="#" class="btn_p status_44"><span id="mybuttontext">In Progress</span></a>';
                break;

            case "00":
                return '<a href="#" class="btn_p status_00"><span id="mybuttontext">New Request</span></a>';
                break;

            case "11":
                return '<a href="#" class="btn_p status_11"><span id="mybuttontext">Final Report</span></a>';
                break;

            case "33":
                return '<a href="#" class="btn_p status_33"><span id="mybuttontext">Interim</span></a>';
                break;

            case "22":
                return '<span class="label label-sm label-danger">NO DATA</span>';
                break;

            case "55":
                return '<a href="#" class="btn_p status_55"><span id="mybuttontext">Awaiting Quotation</span></a>';
                break;

            case "66":
                return '<a href="#" class="btn_p status_66"><span id="mybuttontext">Awaiting Payment</span></a>';
                break;
        }

        return $d;
    }),
    array('db' => '`a`.request_id', 'dt' => 7, 'field' => 'request_id', 'formatter' => function ($d, $row) {

        $r = intval($row['request_id']);

        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
        $host = $_SERVER['SERVER_NAME'];
        $baseURL = $protocol . "$host/";

        $client_id = "-1";
        if (isset($_GET['client_id'])) {

            $client_id = $_GET['client_id'];
        }

        $reportURL = $baseURL . "v1/main.php?client_id=" . $row['client_id'] . "&request_id=$r";

        //        return "<a href='$reportURL' target='_blank'>$d</a>";
        return "<a href='$reportURL' target='_blank'>View Report</a>";

        $r = intval($row['request_id']);

        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
        $host = $_SERVER['SERVER_NAME'];
        $baseURL = $protocol . "$host/";
        $reportURL = $baseURL . "viewrequest.php?client_id=" . $row['client_id'] . "&request_id=$r&requestid=$r";

        return "<a href='$reportURL' target='_blank'>View Report</a>";
    }),
);


//SELECT * FROM pel_individual_id WHERE status IN ('00','33','44','22','11') and identity_id > 3500 ORDER BY identity_id DESC

// SQL server connection information
// SQL server connection information
$sql_details = array(
    'user' => $username_connect,
    'pass' => $password_connect,
    'db' => $database_connect,
    'host' => $hostname_connect
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php');

//$joinQuery = "FROM `attempt` AS `a` JOIN `profile` AS `p` ON (`a`.`profile_id` = `p`.`profile_id`) JOIN `question` AS `q` ON (`a`.`question_id` = `q`.`question_id` )";
$joinQuery = "FROM $table a ";
$extraWhere = "`client_login_id` = '$client_login_id_get_psmt_requests' ";

if (intval($status) != -1) {


    $extraWhere = "`client_login_id` = '$client_login_id_get_psmt_requests' AND `status` = '$status' ";

    if ($status == "pending") {

        $extraWhere = "`client_login_id` = '$client_login_id_get_psmt_requests' AND `status` IN ('22','55','66') ";
    }
}

echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere));
