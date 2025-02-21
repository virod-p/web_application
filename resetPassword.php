<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reset Password</title>
	<link rel="stylesheet" href="theme.css">
</head>
<body class="font-mali vh-100 d-flex justify-content-center align-items-center">
	<div class="card mb-3">
		<div class="card-header bg-primary text-white">
			<h4>Reset Password</h4>
		</div>
		<div class="card-body">

			<form action="contact/submit.php" class="mb-3" method="POST">
                <?php
                    if (isset($_GET['msg'])) {
                        if ($_GET['msg'] == "email_not_found") {
                            echo "<h5 class='my-3 text-danger'>This email was not found. Please try again.</h5>";
                        }
                    }
                ?>
				<div class="form-group">
					<label for="email" title="Example: yourname@domain.com">Email</label>
					<input type="email" name="email" id="email" class="form-control" required>
				</div>
				<button type="submit" class="btn btn-primary">Send OTP</button>
			</form>

			<a href="login.php">Login</a>
		</div>
	</div>
</body>
</html>