
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('../main_resource.php'); ?>
<?php

if(isset($_POST["action"]) && $_POST["action"]=="submit"){

    // Prepared statement for secure querying
    $sql = "SELECT * FROM user_acc WHERE staff_id='".addslashes($_POST['username'])."' AND '".md5($_POST['password'])."'";    
    $result = $db->query($sql);
	$user = $db->fetchArray($result);

	


    if($user){

        // Prepared statement for updating user's last login

        $_SESSION['is_login'] = 1;
		$_SESSION['user_log'] = 'login';
        $_SESSION['username'] = $user['staff_id'];
        $_SESSION['userrole'] = $user['role_id'];
        $_SESSION['timestamp'] = time();

	
       echo("<script>window.location.href = '../home/'; </script>");
    }	
}
?>

</head>

<body>

	<div class="navbar navbar-expand-lg navbar-dark navbar-static shadow-sm">
		<div class="navbar-brand ml-2 ml-lg-0">
			<a href="index.html" class="d-inline-block">
				<img src="global_assets/images/logo_light.png" alt="">
			</a>
		</div>

		<div class="d-flex justify-content-end align-items-center ml-auto">
			<ul class="navbar-nav flex-row">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link">
						<i class="icon-user-lock"></i>
						<span class="d-none d-lg-inline-block ml-2">Login</span>
					</a>
				</li>
			</ul>
		</div>
	</div>


	<div class="page-content">

		<div class="content-wrapper">

			<div class="content-inner">

				<div class="content d-flex justify-content-center align-items-center ">

					<form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<input type="hidden" name="action" value="submit">
						<div class="card mb-0">
							<div class="card-body  shadow">
								<div class="text-center mb-3">
									<img src="../config/image/logo.png" style="width: 200px">
									<h6 class="mb-0">Campus Website</h6>
									<span class="d-block text-muted">Please Login</span>
								</div>

								<div class="form-group form-group-feedback form-group-feedback-left shadow-sm">
									<input type="text" name="username" class="form-control" placeholder="Username">
									<div class="form-control-feedback">
										<i class="icon-user text-muted"></i>
									</div>
								</div>

								<div class="form-group form-group-feedback form-group-feedback-left shadow-sm">
									<input type="password" name="password" class="form-control" placeholder="Password">
									<div class="form-control-feedback">
										<i class="icon-lock2 text-muted"></i>
									</div>
								</div>
								<div class="form-group shadow-sm">
									<button type="submit" class="btn btn-primary btn-block" style="background: #4785ef; border: none">Login</button>
								</div>

								<div class="text-center">
									<a href="../forgot_password">Reset Password</a>
								</div>
							</div>
						</div>
					</form>

				</div>


				<?php include('../main_footer.php');?>

			</div>

		</div>

	</div>

</body>
</html>
