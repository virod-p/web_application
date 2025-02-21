<?php
    $url_safe_email = $_GET['email'];
    $decoded_email  = urldecode($url_safe_email);
    $email          = base64_decode($decoded_email);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>OTP Confirm</title>
	<link rel="stylesheet" href="theme.css">
</head>
<body class="font-mali vh-100 d-flex justify-content-center align-items-center">
	<div class="card mb-3">
		<div class="card-header bg-primary text-white">
			<h4>OTP Confirm</h4>
		</div>
		<div class="card-body">

			<form action="otpCheck.php?email=<?php echo $url_safe_email; ?>" class="mb-3" method="POST">
                <?php
                    if (isset($_GET['msg'])) {
                        if ($_GET['msg'] == "email_not_found") {
                            echo "<h5 class='my-3 text-danger'>This email was not found. Please try again.</h5>";
                        }
                        if ($_GET['msg'] == "otp_error") {
                            echo "<h5 class='my-3 text-danger'>Wrong OTP. Please try again.</h5>";
                        }
                    }
                ?>
				<div class="form-group">
					<label for="text">OTP</label>
					<input type="text" name="otp" id="otp" class="form-control" required>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>

			<a href="login.php">Login</a>
		</div>
	</div>
</body>
</html>