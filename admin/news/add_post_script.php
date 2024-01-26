<?php
include('../main_resource.php');

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Assuming the POST request will always be for inserting a new record
$title = sanitize_input($_POST['title'] ?? '');
$content = $_POST['content'] ?? '';
$path = sanitize_input($_POST['path'] ?? '');
$meta_title = sanitize_input($_POST['meta_title'] ?? '');
$meta_description = sanitize_input($_POST['meta_description'] ?? '');
$meta_tags = sanitize_input($_POST['meta_tags'] ?? '');
$meta_keywords = sanitize_input($_POST['meta_keywords'] ?? '');
$created_by = $_SESSION['username'];  // Assuming the session is already started

// Initialize paths for banner and thumbnail
$bannerPath = '../../files/images/'; 
$thumbnailPath = '../../files/images/';

// Process banner image upload
$banner = '';
if (isset($_FILES['banner'])) {
    if ($_FILES['banner']['error'] == UPLOAD_ERR_OK) {
        $bannerFileName = uniqid() . '_' . basename($_FILES['banner']['name']);
        $bannerDest = $bannerPath . $bannerFileName;
        if (move_uploaded_file($_FILES['banner']['tmp_name'], $bannerDest)) {
            $banner = $bannerDest;
        } else {
            echo json_encode(["status" => "error", "message" => "Banner move failed"]);
            exit;
        }
    }
}


// Process thumbnail image upload
$thumbnail = '';
if (isset($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
    $thumbnailFileName = uniqid() . '_' . basename($_FILES['thumbnail']['name']);
    $thumbnailDest = $thumbnailPath . $thumbnailFileName;
    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailDest)) {
        $thumbnail = $thumbnailDest;
    } else {
        echo json_encode(["status" => "error", "message" => "Thumbnail move failed"]);
        exit;
    }
}

$query = "INSERT INTO posts (title, category, content, path, meta_title, meta_description, meta_tags, meta_keywords, banner, thumbnail, created_by, created_date) 
VALUES ('$title', 'news_detail', '$content', '$path', '$meta_title', '$meta_description', '$meta_tags', '$meta_keywords', '$banner', '$thumbnail', '$created_by', NOW())";

$db->query($query);

if ($db->affectedRows() > 0) {
    // echo json_encode(["status" => "success", "message" => "Post inserted successfully"]);
} else {
    // echo json_encode(["status" => "error", "message" => "Error inserting post"]);
}


$db->close();
echo("<script>window.location.href = '../news/'; </script>");
?>