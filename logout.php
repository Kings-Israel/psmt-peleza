<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 22/05/20
 * Time: 17:27
 */
//to fully log out a visitor we need to clear the session varialbles
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
$_SESSION['PrevUrl'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
unset($_SESSION['PrevUrl']);
session_destroy();


$logoutGoTo = "index.php";

header("Location: $logoutGoTo");
