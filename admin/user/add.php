<?php
include('../main_resource.php');
$sql = "SELECT * FROM posts WHERE id='".addslashes($_GET['id'])."'";
$currdata = $db->query($sql)->fetchArray();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Campus</title>
	<!-- Add this to your <head> section -->
<script src="../../lib/ckeditor/ckeditor.js"></script>

</head>
<body>

<?php include('../main_navbar.php'); ?>

<div class="page-content">
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg shadow-sm">
        <div class="sidebar-content">
            <?php include('../user_menu.php'); ?>
            <?php include('../main_navigation.php'); ?>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="content-inner">
            <?php include('../page_header.php'); ?>

            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
						<div class="card-header">

						Add

							<div class="header-elements" style="display: flex; justify-content: flex-end; align-items: center; width: 100%; margin-top: 0px; margin-bottom: 0px; padding-top: 0px; padding-bottom: 0px; ">
								<!-- back button -->
								<a href="../news/index.php" class="btn btn-primary" style="margin-top: 0px; margin-bottom: 0px; padding-top: 0px; padding-bottom: 0px; ">Back</a>
							</div>
						</div>


                            <div class="card-body">



							<form action="add_post_script.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?=$_GET['id']?>">                                   
									 <input type="hidden" name="e" value="<?=$_GET['e']?>">                                     
									 <input type="hidden" name="action" value="add_subject">         
									 <input type="hidden" name="classid" value="<?=$_GET['id']?>">                                     
									 



									 <div class="row">
										<!-- Title -->
										<div class="col-md-6">
											<div class="form-group">
												<label>Title:</label>
												<input type="text" name="title" class="form-control form-control-sm" placeholder="Title" value="<?=$currdata['title'];?>" required>
											</div>
										</div>

										<!-- Path -->
										<div class="col-md-6">
											<div class="form-group">
												<label>Path:</label>
												<input type="text" name="path" class="form-control form-control-sm" placeholder="Path" value="<?=$currdata['path'];?>" required>
											</div>
										</div>
									</div>

									<div class="row">
										<!-- Content -->
										<div class="col-md-12">
											<div class="form-group">
												<label>Content:</label>
												<textarea name="content" id="editor" class="form-control" rows="20"><?=$currdata['content'];?></textarea>

												
											</div>
										</div>
									</div>

									<div class="row">
										<!-- Meta Information -->
										<div class="col-md-3">
											<div class="form-group">
												<label>Meta Title:</label>
												<input type="text" name="meta_title" class="form-control form-control-sm" placeholder="Meta Title" value="<?=$currdata['meta_title'];?>">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Meta Description:</label>
												<input type="text" name="meta_description" class="form-control form-control-sm" placeholder="Meta Description" value="<?=$currdata['meta_description'];?>">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Meta Tags:</label>
												<input type="text" name="meta_tags" class="form-control form-control-sm" placeholder="Meta Tags" value="<?=$currdata['meta_tags'];?>">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Meta Keywords:</label>
												<input type="text" name="meta_keywords" class="form-control form-control-sm" placeholder="Meta Keywords" value="<?=$currdata['meta_keywords'];?>">
											</div>
										</div>
									</div>

									<div class="row">
										<!-- File Uploads -->
										<div class="col-md-6">
											<div class="form-group">
												<label>Banner:</label>
												<input type="file" name="banner" id="bannerInput" class="form-control form-control-sm" onchange="previewImage('bannerInput', 'bannerPreview')">
												<!-- Container for Banner Preview -->
												<img src="../../files/images/<?=$currdata['banner'];?>"  id="bannerPreview" src="#" alt="Banner Preview" style="max-width: 100%; height: auto;">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Thumbnail:</label>
												<input type="file" name="thumbnail" id="thumbnailInput" class="form-control form-control-sm" onchange="previewImage('thumbnailInput', 'thumbnailPreview')">
												<!-- Container for Thumbnail Preview -->
												<img  src="../../files/images/<?=$currdata['thumbnail'];?>"  id="thumbnailPreview" src="#" alt="Thumbnail Preview" style="max-width: 100%; height: auto;">
											</div>
										</div>

									</div>
									
									 
 

	 
  
  
                                        <div class="text-right">
											<button type="submit" name="button"  class="badge ml-2" style="background: #2980b9; color: #fff">Submit</button>
										</div>
										 


									</form>
						 








                                 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('../main_footer.php'); ?>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace('editor', {
        height: 300
    });

    function previewImage(inputId, previewId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = "";
            preview.style.display = 'none';
        }
    }
</script>

</body>
</html>
