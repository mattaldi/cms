<?php
require_once 'lib/common.php';



$requestPath = isset($_GET['path']) ? trim($_GET['path'], '/') : '';

try {
    $table      = 'posts';
    $columns    = ['category', 'title', 'content','banner'];
    $where      = ['path' => $requestPath, 'is_active' => '1']; 
    $result     = $db->select($table, $columns, $where);

    if ($row = $db->fetchArray($result)) {
        $banner         = $row['banner'];
        $category       = $row['category'];
        $pageTitle      = $row['title'];
        $pageContent    = $row['content'];
        $filePath       = "module_".$category . '.php';

        include 'layout-header.php';
        include $filePath;
    } else {
        include '404.php';
    }
} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage());
}
?>