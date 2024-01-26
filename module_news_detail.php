<body class="bg-light">

<?php include('layout-nav.php'); ?>

<?php if($banner != ''): ?>
    <!-- center the image -->
    <div class="text-center" >
<img style="height: 400px" src="files/images/<?=$banner; ?>">
</div>
<?php endif; ?>

<nav aria-label="breadcrumb" style="background-color: #f8f8f7;">
    <div class="container-fluid">
        <ol class="breadcrumb py-2 my-2" style="background-color: transparent;">
            <li class="breadcrumb-item">
                <a href="#" style="text-decoration: none; color: inherit;">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="../news" style="text-decoration: none; color: inherit;">News</a>
            </li>            
            <li class="breadcrumb-item active" aria-current="page" style="color: #6c757d;">
                <span style="font-size: 16px;"><?php echo $pageTitle; ?></span>
            </li>
        </ol>
    </div>
</nav>

<div class="bg-white">
<div class="container">
        <div class="row">
            <div class="col-md-12" >
            <h1 class="mt-4"  style="color:#c01122; font-size: 1.5rem"><b><?php echo $pageTitle; ?></b></h1>
            </div>
        </div>
    </div>

<hr>
        <div class="container">
        <div class="row">
            <div class="col-md-12"  style="margin-bottom: 60px">
                <div class="mt-4 text-gray-600" style="font-size: 16px;">
                    <?php echo $pageContent; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('layout-footer.php'); ?>

</body>
