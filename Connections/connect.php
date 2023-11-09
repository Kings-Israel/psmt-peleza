<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Africa/Nairobi');

//header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
//header('Cache-Control: no-store, no-cache, must-revalidate');
//header('Cache-Control: post-check=0, pre-check=0', FALSE);
//header('Pragma: no-cache');
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

$configs = parse_ini_file("config.ini", true);
$name = $configs['db']['name'];
$configs = $configs[$name];

$hostname_connect = $configs['host'];
$database_connect = $configs['dbname'];
$username_connect = $configs['username'];
$password_connect = $configs['password'];
$port = $configs['port'];


if ($mysqli = mysqli_connect($hostname_connect, $username_connect, $password_connect, $database_connect)) {

    $connect = mysqli_connect($hostname_connect, $username_connect, $password_connect, $database_connect);
} else {

    header("Location: 503.html");
}


function dbconnect()
{

    $configs = parse_ini_file("config.ini", true);
    $name = $configs['db']['name'];
    $configs = $configs[$name];

    $hostname_connect = $configs['host'];
    $database_connect = $configs['dbname'];
    $username_connect = $configs['username'];
    $password_connect = $configs['password'];
    $port = $configs['port'];

    if ($mysqli = mysqli_connect($hostname_connect, $username_connect, $password_connect, $database_connect)) {

        $connect = mysqli_connect($hostname_connect, $username_connect, $password_connect, $database_connect);
    } else {

        header("Location: 503.html");
    }


    return $connect;
}


$con = mysqli_connect($hostname_connect, $username_connect, $password_connect, $database_connect);

if (!$con) {

    http_response_code(500);
    $res = new stdClass();
    $res->ResultCode = 1;
    $res->ResultDesc = mysqli_connect_error();
    echo json_encode($res);

    die("Connection failed: " . mysqli_connect_error());
}
