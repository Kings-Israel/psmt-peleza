<?php

$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
$host = $_SERVER['SERVER_NAME'];
$baseURL = $protocol . "$host/";

?>

<span id="login-id" style="display: none"><?= $client_login_id_get_psmt_requests ?></span>
<span id="client-id" style="display: none"><?= $client_id_get_psmt_requests ?></span>
<span id="filter-status" style="display: none"><?= $filter_status ?></span>
<span id="package-id" style="display: none"><?= $colname_getpackagecost ?></span>
<span id="client-company-id" style="display: none"><?= $_SESSION['MM_client_company_id'] ?></span>
<span id="staff_id" style="display: none"><?= $_SESSION['MM_Username'] ?></span>
<span id="uploaded_by" style="display: none"><?= $_SESSION['MM_full_names'] ?></span>

<div class="app-sidebar sidebar-shadow" id="sidebar-vm">

    <div class="app-header__logo">
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="fa-stack"><i class="icon-menu-3 fa-stack-2x" style="color: white"></i></span>
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

        <div class="profile">

            <div class="username">
                <span><?php echo $_SESSION['MM_first_name']; ?></span>
            </div>

            <div class="useremail">
                <span><?php echo $_SESSION['MM_client_email_address']; ?></span>
            </div>
        </div>

        <div class="client-logo">
            <img src="<?= $_SESSION['company_logo'] ?>" height="80px" width="80px" class="client-logo-img"/>
            <div class="edit">

                <input type="file" name="file" id="logo-img" class="inputfile" onchange="uploadLogo()" />
                <label for="file" onclick="document.getElementById('logo-img').click();"><i class="icon_1 icon-pencil"></i> Edit</label>

            </div>
        </div>

        <div class="app-sidebar__inner">

            <ul class="vertical-nav-menu ">
                <li class="app-sidebar__heading text-light">Main</li>
                <li class="text-light">
                    <a href="<?= $baseURL . "dashboard" ?>" class="mm-active">
                        <span class="fa-stack"><i class="icon-database fa-stack-1x"></i></span>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="#0">
                        <span class="fa-stack"><i class="icon_1 icon-mail-6 fa-stack-1x"></i></span>
                        Make Request
                        <span class="fa-stack"><i class="icon_1 icon-angle-right fa-stack-1x"></i></span>
                    </a>
                    <ul>

                        <li>
                            <a href="/request1.php">
                                <i class="metismenu-icon"></i>
                                <span class="sub-menu">Individual Request</span>
                            </a>
                        </li>

                        <li v-show="packages.length > 0 ">
                            <a href="/bulk.php">
                                <i class="metismenu-icon"></i>
                                <span class="sub-menu">Company Request</span>
                            </a>
                        </li>

                        <!--
                        <li v-for="item in packages">
                            <a v-bind:href=getPackageURL(item)><i class="metismenu-icon"></i> <span v-text="item.package_name"></span></a>
                        </li>
                        -->
                    </ul>
                </li>

                <li>
                    <a href="../reports/index1.php">
                        <span class="fa-stack"><i class="icon-newspaper-1 fa-stack-1x"></i></span>
                        Reports
                    </a>
                </li>


                <li>
                    <a href="<?= $baseURL . "cart/cart.php" ?>">
                        <span class="fa-stack"><i class="icon-cart fa-stack-1x"></i></span>
                        Cart
                    </a>
                </li>

                <li>
                    <a href="<?= $baseURL . "payments.php" ?>">
                        <span class="fa-stack"><i class="icon-dollar-1 fa-stack-1x"></i></span>
                        Payment
                    </a>
                </li>

                <li>
                    <a href="<?= $baseURL . "faq.php" ?>">
                        <span class="fa-stack"><i class="icon-question fa-stack-1x"></i></span>
                        FAQs
                    </a>
                </li>
                <li>
                    <a href="https://swagger.psmt.pidva.africa/" target="_blank">
                        <span class="fa-stack"><i class="icon-code-3 fa-stack-1x"></i></span>
                        APIs
                    </a>
                </li>
                <li class="app-sidebar__heading text-light">My Account</li>
                <li>
                    <a href="<?= $baseURL . "profile.php" ?>" >
                        <span class="fa-stack"><i class="icon-user fa-stack-1x"></i></span>
                        Profile
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>