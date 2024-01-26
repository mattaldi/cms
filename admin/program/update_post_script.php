<?php
include('../main_resource.php');


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['id'])) {
    $id = sanitize_input($_POST['id']);
    $title = sanitize_input($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $path = sanitize_input($_POST['path'] ?? '');
    $meta_title = sanitize_input($_POST['meta_title'] ?? '');
    $meta_description = sanitize_input($_POST['meta_description'] ?? '');
    $meta_tags = sanitize_input($_POST['meta_tags'] ?? '');
    $meta_keywords = sanitize_input($_POST['meta_keywords'] ?? '');
    $is_active = sanitize_input($_POST['is_active'] ?? '');
    $created_by = $_SESSION['username']; 

    // Initialize paths for banner and thumbnail
    $bannerPath = '../../files/images/'; 
    $thumbnailPath = '../../files/images/';

    // Process banner image upload
    $banner = '';
    if (isset($_FILES['banner']['name']) && $_FILES['banner']['error'] == UPLOAD_ERR_OK) {
        $bannerFileName = uniqid() . '_' . basename($_FILES['banner']['name']);
        $bannerDest = $bannerPath . $bannerFileName;
        if (move_uploaded_file($_FILES['banner']['tmp_name'], $bannerDest)) {
            $banner = $bannerDest;

            $query = "UPDATE posts SET banner=? WHERE id=?";
            $db->query($query, $banner, $id);

        } else {
            echo json_encode(["status" => "error", "message" => "Banner upload failed"]);
            exit;
        }
    }

    // Process thumbnail image upload
    $thumbnail = '';
    if (isset($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $thumbnailFileName = uniqid() . '_' . basename($_FILES['thumbnail']['name']);
        $thumbnailDest = $thumbnailPath . $thumbnailFileName;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailDest)) {
            $thumbnail = $thumbnailDest;

            $query = "UPDATE posts SET thumbnail=? WHERE id=?";
            $db->query($query, $thumbnail, $id);

        } else {
            echo json_encode(["status" => "error", "message" => "Thumbnail upload failed"]);
            exit;
        }
    }

    
    $query = "UPDATE posts SET title=?, is_active=?, content=?, path=?, meta_title=?, meta_description=?, meta_tags=?, meta_keywords=?, updated_by=?, updated_date=? WHERE id=?";
    $db->query($query, $title, $is_active, $content, $path, $meta_title, $meta_description, $meta_tags, $meta_keywords, $created_by, date("Y-m-d H:i:s"), $id);

    if ($db->affectedRows() > 0) {
        // echo json_encode(["status" => "success", "message" => "Post updated successfully"]);
    } else {
        // echo json_encode(["status" => "error", "message" => "Error updating post"]);
    }

    $db->close();
} else {
    // echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
echo("<script>window.location.href = '../program/edit.php?id=".$id."'; </script>");
?>