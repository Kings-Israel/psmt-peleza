
<div class="app-header header-shadow">
    <div class="app-header__logo">
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                        data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
                <span>
                    <button type="button"
                            class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="icon-ellipsis-vert"></i>
                        </span>
                    </button>
                </span>
    </div>
    <div class="app-header__content">
        <div class="app-header-left colorText">
            <ul class="header-menu nav">
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <span class="fa-stack"><i class="icon-calendar fa-stack-2x"></i></span>
                        <?php echo date('l d, F Y') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="app-header-right">
            <div class="header-btn-lg pr-3">
                <div class="widget-content p-3">
                    <div class="widget-content-wrapper">
                        <div>
                            <img width="42" class="rounded-circle" src="https://via.placeholder.com/40" alt="">
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                <?php echo $_SESSION['MM_first_name']; ?>
                            </div>
                        </div>
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <i class="icon-angle-down"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                     class="dropdown-menu dropdown-menu-right">
                                    <button type="button" tabindex="0" class="dropdown-item">User Account</button>
                                    <button type="button" tabindex="0" class="dropdown-item">Settings</button>
                                    <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                    <button type="button" tabindex="0" class="dropdown-item">Actions</button>
                                    <div tabindex="-1" class="dropdown-divider"></div>
                                    <button type="button" tabindex="0" class="dropdown-item">Dividers</button>
                                </div>
                            </div>
                        </div>
                        <div class="vertical-line"></div>
                    </div>
                </div>
            </div>

            <div class="header-btn-lg pr-3">
                <div class="search-wrapper" style="display: none">
                    <div class="input-holder">
                        <input type="text" class="search-input" placeholder="Type to search">
                        <button class="search-icon"><span></span></button>
                    </div>
                    <button class="close"></button>
                </div>
            </div>

            <div class="vertical-line"></div>
            <!--
            <div class="header-btn-lg pr-0">
                <div class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <span class="fa-stack"><i class="icon-cart fa-stack-2x"></i></span>
                    </a>
                </div>
            </div>
            <div class="header-btn-lg pr-3">
                <div class="nav-item">
                            <span class="fa-stack">
                                <i class="icon-tags fa-stack-2x"></i>
                                <strong class="fa-stack-1x" style="color: white">3</strong>
                            </span>
                </div>
            </div>
            -->
            <div class="vertical-line"></div>
            <div class="header-btn-lg pr-1">
                <div class="nav-item">
                    <a href="/logout.php" class="btn_1 small2"><i class="icon-logout"></i><span>SIGN OUT</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
