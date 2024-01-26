

<body class="bg-light"> 

<?php include('layout-nav.php'); ?>


<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php
        $first = true;
        $imageDir = 'banner/';
        $images = glob($imageDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        foreach ($images as $index => $image) {
            echo "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='{$index}'" . ($first ? " class='active' aria-current='true'" : "") . " aria-label='Slide " . ($index + 1) . "'></button>";
            $first = false;
        }
        ?>
    </div>
    <div class="carousel-inner">
        <?php
        $first = true;
        foreach ($images as $image) {
            $fileName = basename($image);
            echo "<div class='carousel-item" . ($first ? " active" : "") . "'>";
            echo "<img src='{$imageDir}{$fileName}' class='d-block w-100' alt='Slide'>";
            echo "</div>";
            $first = false;
        }
        ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>



<!-- Start Call To Action -->
<div class="bg-danger py-3 text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="py-2">
                    <h4 class="text-white font-weight-light text-uppercase">
                        <strong>Transforming Lives, Enriching Future</strong>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Call To Action -->



<!-- Search Action Section (Light Grey Background) -->
<!-- course finder -->
<div class="card card-body" style="margin-bottom: 0px; padding-bottom: 0px; background: #ecf0f1">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="container">
              
                    <div class="input-group mb-3">
                        <input type="text" name="id_programme" id="country" class="form-control form-control-lg" placeholder="Search..." aria-label="Search..." aria-describedby="button-search">

                        <button type="submit" class="btn btn-danger btn-lg" id="button-search">Search</button>
                    </div>
             
                <div id="content-list"></div>
            </div>
        </div>
    </div>
</div>
<!-- /course finder -->


<!-- Quick Links Section -->
<div class="container mt-4">
  <div class="row justify-content-center">

    <!-- Programmes -->
    <div class="col-lg-2 col-md-3 col-6" style="padding: 0; margin-bottom: 15px;">
        <a href="../program" class="text-decoration-none">
            <div class="card text-center" style='height: 100%;'>
                <img loading="lazy" src="/images/programmes2.png" class="card-img-top" alt="Programmes" >
                <div class="card-body p-0">
                  <span style="color:#c0392b; font-size: 14px; font-family: montserrat;">Programmes</span>
                </div>
            </div>
        </a> 
    </div>

    <!-- Open Days -->
    <div class="col-lg-2 col-md-3 col-6" style="padding: 0; margin-bottom: 15px;">
        <a href="https://pmb.campus.com/" target='_blank' class="text-decoration-none">
            <div class="card text-center" style='height: 100%;'>
                <img loading="lazy" src="/images/open-days2.png" class="card-img-top" alt="Open Days">
                <div class="card-body p-0">
                  <span style="color:#c0392b; font-size: 14px; font-family: montserrat;">Open Days</span>
                </div>
            </div>
        </a>
    </div>

    <!-- International -->
    <div class="col-lg-2 col-md-3 col-6" style="padding: 0; margin-bottom: 15px;">
        <a href="../international-students" class="text-decoration-none">
            <div class="card text-center" style='height: 100%;'>
                <img loading="lazy" src="/images/international2.png" class="card-img-top" alt="International">
                <div class="card-body p-0">
                  <span style="color:#c0392b; font-size: 14px; font-family: montserrat;">International</span>
                </div>
            </div>
        </a> 
    </div>

    <!-- Online Payment -->
    <div class="col-lg-2 col-md-3 col-6" style="padding: 0; margin-bottom: 15px;">
        <a href="https://campus.com/online-payment" target="_blank" class="text-decoration-none">
            <div class="card text-center" style='height: 100%;'>
                <img loading="lazy" src="/images/online-payment.png" class="card-img-top" alt="Online Payment">
                <div class="card-body p-0">
                  <span style="color:#c0392b; font-size: 14px; font-family: montserrat;">Online Payment</span>
                </div>
            </div>
        </a>
    </div>

  </div>
</div>


 

<!-- Video Profile Section -->
<div class="bg-light py-3 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="font-weight-bold mb-4">Campus Video Profile</h3>
                <video width="100%" height="315" controls>
                  <source src="videos/video.mp4" type="video/mp4">
                 </video>
            </div>
        </div>
    </div>
</div>
<!-- Latest News Section -->
<div class="bg-light py-5">
    <div class="container">
        <!-- Section Title -->
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="col-md-12">
                  <h3 class="font-weight-bold mb-4">Latest News</h3>
                </div>
            </div>
        </div>

        <!-- News Items -->
        <div class="row">
        <?php

            try {
           
                $table          = 'posts';
                $columns        = ['title', 'content', 'path', 'banner'];
                $where          = ['category' => 'news_detail', 'is_active' => '1']; 
                $options        = ['orderBy' => '`id` DESC', 'limit' => 3];
                $result         = $db->select($table, $columns, $where, $options);
                $newsArticles   = $db->fetchAll($result);

                if (count($newsArticles) > 0) {
                    // Iterate over each news article
                    foreach ($newsArticles as $row) {
                        $newsTitle      = $row['title'];
                        $newsContent    = substr($row['content'], 0, 200);
                        $newsPath       = $row['path'];
                        $thumbnailPath  = $row['banner'];

                        echo "<div class='col-md-4 mb-4'>";
                        echo "<div class='card h-100'>";
                        if (!empty($thumbnailPath)) {
                            echo "<img src='/files/images/$thumbnailPath' class='card-img-top' alt='...'>";
                        }
                        echo "<div class='card-body'>";
                        echo "<h4 class='card-title font-weight-bold'>$newsTitle</h4>";
                        echo "<p class='card-text'>$newsContent...</p>";  
                        echo "<a href='$newsPath' class='btn btn-primary btn-sm'>Read More</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    // Message if no news articles are found
                    echo "<div class='col-md-12'><p>No news articles found.</p></div>";
                }
            } catch (Exception $e) {
                // Handle any exceptions here
                echo "An error occurred: " . $e->getMessage();
            }

            ?>

        </div>

        <!-- More News Button -->
        <div class="row">
            <div class="col-md-12 text-center mt-4">
                <a href="/news" class="btn btn-primary btn-sm">More News</a>
            </div>
        </div>
    </div>
</div>


<?php include('layout-footer.php'); ?>