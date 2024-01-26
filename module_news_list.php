<body class="bg-light">
    <?php include('layout-nav.php'); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?php echo $pageTitle; ?></h1>
                <div class="row"> <!-- Ensure this is inside a "row" to use Bootstrap's grid system -->
                    <?php
                            $table = 'posts';
                            $columns = ['*']; 
                            $where = ['category' => 'news_detail', 'is_active' => '1'];  
                            $options = ['orderBy' => 'id DESC']; 

                            $result = $db->select($table, $columns, $where, $options);

                            if ($result) {
                                while ($data_content = $db->fetchArray($result)) {
                            ?>

                            <!-- Start Single Tile -->
                            <div class="col-md-2 col-sm-4 col-6"> <!-- Adjust classes to fit 5 items in a row -->
                                <div class="tile tile--modern">
                                    <div class="tile-thumb">
                                        <a href="<?= ($data_content['path']) ?>">
                                            <img class="img-fluid" src="../<?= ('/files/images/' . ($data_content['thumbnail'] ?: $data_content['banner'] ?: 'logo-white.png')) ?>" alt="Image" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="tile-content text-center">
                                        <h6 class="heading">
                                            <a  style="text-decoration: none;" href="<?= ($data_content['path']) ?>"><?= ($data_content['title']) ?></a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Tile -->

                            <?php
                        }
                    } else {
                        echo "<p>No content found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include('layout-footer.php'); ?>
</body>
