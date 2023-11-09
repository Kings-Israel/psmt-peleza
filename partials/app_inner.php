<div class="app-main__inner">
    <div class="app-page-title primaryColor">
        <div class="page-title-wrapper colorText">
            <div class="page-title-heading">
                <div>
                    <h4><strong>Welcome back, </strong><?php echo $_SESSION['MM_first_name']; ?> </h4>
                    <div class="page-title-subheading">You have <b><?php echo $_SESSION['MM_client_credits']; ?></b> credits</div>
                </div>
            </div>
        </div>
        <div class="col-sm-offset-2">
            <p>
                <span class="fa-stack"><i class="icon-home fa-stack-2x" ></i></span>
                <span><a href="dashboard/index.php">Home</a></span>   /
                <span class="active">Dashboard</span>
            </p>
        </div>
    </div>
</div>
