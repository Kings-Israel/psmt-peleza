<?php
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

<div class="app-main__inner">
    <div class="app-page-title primaryColor">
        <div class="page-title-wrapper">

            <div class="page-title-heading">
                <div style="color: white !important;">
                    <h4 style="color: white !important;"><strong style="color: white !important;">Welcome back, </strong><?php echo $_SESSION['MM_first_name']; ?> </h4>
                    <div class="page-title-subheading" style="color: white !important;">You have <b><?php echo $_SESSION['MM_client_credits']; ?></b> credits</div>
                </div>
            </div>
        </div>
    </div>
</div>