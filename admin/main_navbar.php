<!-- Main navbar -->
<div class="navbar navbar-expand-lg navbar-dark navbar-static shadow-sm" style="color: #000">
    <div class="d-flex flex-1 d-lg-none">
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3" style="color: #1e272e"></i>
        </button>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-gift"></i>
        </button>
    </div>

    <div class="navbar-brand text-center text-lg-left" style="padding-top: 1rem; padding-bottom: 1rem; ">
        <a href="/" class="d-inline-block">
            <img src="../logo_light2.png" class="d-none d-sm-block" alt="" style="height: 2rem;">
            <img src="../../global_assets/images/logo_icon_light.png" class="d-sm-none" alt="">
        </a>
    
    </div>

    <div class="collapse navbar-collapse order-2 order-lg-1" id="navbar-mobile">
        <ul class="navbar-nav">
    
        </ul>
        <span class="my-3 my-lg-0 ml-lg-3" style="background: #fff"><span id="total_online"></span>&nbsp;</span>
    </div>

 

    <ul class="navbar-nav flex-row order-1 order-lg-2 flex-1 flex-lg-0 justify-content-end align-items-center">
       


        <li class="nav-item nav-item-dropdown-lg dropdown dropdown-user h-100">
            <a href="#" class="navbar-nav-link navbar-nav-link-toggler dropdown-toggle d-inline-flex align-items-center h-100" data-toggle="dropdown">
            <i class="icon-user"></i>
                <img src="../../global_assets/images/placeholders/placeholder.jpg" class="rounded-pill mr-lg-2" height="34" alt="">
                <span class="d-none d-lg-inline-block"><?=$_SESSION['username']?></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">

																
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="logout">
                    <input type="hidden" name="id" value="<?=$menu['id']?>">
                    <button type="submit" class="dropdown-item"><i class="icon-switch2"></i> Logout</button>
                </form>
                
            </div>
        </li>
    </ul>
</div>
<!-- /main navbar -->