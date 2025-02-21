<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
	<link rel="stylesheet" href="theme.css">
</head>
<body class="font-mali vh-100 d-flex justify-content-center align-items-center">
	<div class="card mb-3">
		<div class="card-header bg-primary text-white">
			<h4>Register</h4>
		</div>
		<div class="card-body">

			<form action="saveRegister.php" class="mb-3" method="POST">
                <?php
                    if (isset($_GET['msg'])) {
                        if ($_GET['msg'] == "email_exist") {
                            echo "<h5 class='my-3 text-danger'>Email is already use</h5>";
                        }if ($_GET['msg'] == "invalid_email_format") {
                            echo "<h5 class='my-3 text-danger'>Email format is incorrect. Please try again.</h5>";
                            echo "<h5 class='my-3 text-danger'>Example: yourname@domain.com</h5>";
                        }
                    }
                ?>
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" name="name" id="name" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="email" title="Example: yourname@domain.com">Email</label>
					<input type="email" name="email" id="email" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" required>
				</div>
				<button type="submit" class="btn btn-primary">Register</button>
			</form>
			<a href="login.php">Login</a>
            <?php
                if (session_status() == PHP_SESSION_NONE) {
					session_start();
				}
                if (! isset($_SESSION['user_id'])) {
                    echo "or <a href='index.php'>Use as Guest</a><br>";
                    echo "<a href='resetPassword.php'>Forgot password?</a>";
                } else {
                    header("Location: index.php");
                }
            ?>
		</div>
	</div>
</body>
</html>