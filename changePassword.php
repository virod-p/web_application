<?php
    if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
    if(isset($_SESSION['otpCheck'])){
        if($_SESSION['otpCheck'] == true){
            $url_safe_email = $_GET['email'];
            $decoded_email = urldecode($url_safe_email); 
            $email = base64_decode($decoded_email);
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Change Password</title>
	<link rel="stylesheet" href="theme.css">
</head>
<body class="font-mali vh-100 d-flex justify-content-center align-items-center">
	<div class="card mb-3">
		<div class="card-header bg-primary text-white">
			<h4>Change password</h4>
		</div>
		<div class="card-body">

			<form action="saveChangePassword.php?email=<?php echo $url_safe_email; ?>" class="mb-3" method="POST">
                <div class="form-group">
                    <p class="form-control"><?php echo $email; ?></p>
                </div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" required>
				</div>
				<button type="submit" class="btn btn-primary">Change</button>
			</form>

			<a href="login.php">Login</a>
		</div>
	</div>
</body>
</html>