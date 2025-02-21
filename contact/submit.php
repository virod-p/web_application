<?php
require '../connection.php';
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/functions.php';
require_once __DIR__.'/config.php';
echo "1<br>";
session_start();
echo "2<br>";
// Basic check to make sure the form was submitted.
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirectWithError("The form must be submitted with POST data.");
        
}
$email = mysqli_real_escape_string($conn, $_POST["email"]);
        
// ค้นหาข้อมูลของผู้ใช้จากฐานข้อมูล
$query = "SELECT * FROM `users` WHERE `user_email` = '$email'"; //หา email ของผู้ใช้ใน database
$result = mysqli_query($conn, $query); // เก็บ email ไว้ใน $result
        
if(mysqli_num_rows($result) != 1) {
    // ไม่พบผู้ใช้งานในระบบ
    header("Location: ../resetPassword.php?msg=email_not_found");
    exit();
}
echo "2.5<br>";
$row = mysqli_fetch_assoc($result);
echo "3<br>";
// Do some validation, check to make sure the name, email and message are valid.
// if (empty($_POST['g-recaptcha-response'])) {
//     redirectWithError("Please complete the CAPTCHA.");
// }

// $recaptcha = new \ReCaptcha\ReCaptcha(CONTACTFORM_RECAPTCHA_SECRET_KEY);
// $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_REQUEST['REMOTE_ADDR']);

// if (!$resp->isSuccess()) {
//     $errors = $resp->getErrorCodes();
//     $error = $errors[0];

//     $recaptchaErrorMapping = [
//         'missing-input-secret' => 'No reCAPTCHA secret key was submitted.',
//         'invalid-input-secret' => 'The submitted reCAPTCHA secret key was invalid.',
//         'missing-input-response' => 'No reCAPTCHA response was submitted.',
//         'invalid-input-response' => 'The submitted reCAPTCHA response was invalid.',
//         'bad-request' => 'An unknown error occurred while trying to validate your response.',
//         'timeout-or-duplicate' => 'The request is no longer valid. Please try again.',
//     ];

//     $errorMessage = $recaptchaErrorMapping[$error];
//     redirectWithError("Please retry the CAPTCHA: ".$errorMessage);
// }

// if (empty($_POST['name'])) {
//     redirectWithError("Please enter your name in the form.");
// }

if (empty($_POST['email'])) {
    redirectWithError("Please enter your email address in the form.");
}
echo "4<br>";
// if (empty($_POST['subject'])) {
//     redirectWithError("Please enter your message in the form.");
// }

// if (empty($_POST['message'])) {
//     redirectWithError("Please enter your message in the form.");
// }

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    redirectWithError("Please enter a valid email address.");
}

echo "5<br>";
// if (strlen($_POST['message']) < 10) {
//     redirectWithError("Please enter at least 10 characters in the message field.");
// }

// Everything seems OK, time to send the email.

$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
$otp = rand(100000,999999); 
$otpString = strval($otp);
echo "6<br>";
$sql = "UPDATE users SET otp = '{$otpString}' WHERE user_email = '{$_POST['email']}'";
echo "7<br>";
mysqli_query($conn, $sql);
echo "8<br>";
try {
    // Server settings
    $mail->setLanguage(CONTACTFORM_LANGUAGE);
    $mail->SMTPDebug = CONTACTFORM_PHPMAILER_DEBUG_LEVEL;
    $mail->isSMTP();
    $mail->Host = CONTACTFORM_SMTP_HOSTNAME;
    $mail->SMTPAuth = true;
    $mail->Username = CONTACTFORM_SMTP_USERNAME;
    $mail->Password = CONTACTFORM_SMTP_PASSWORD;
    $mail->SMTPSecure = CONTACTFORM_SMTP_ENCRYPTION;
    $mail->Port = CONTACTFORM_SMTP_PORT;
    $mail->CharSet = CONTACTFORM_MAIL_CHARSET;
    $mail->Encoding = CONTACTFORM_MAIL_ENCODING;
    echo "9<br>";
    // Recipients
    $mail->setFrom(CONTACTFORM_FROM_ADDRESS, CONTACTFORM_FROM_NAME);
    $mail->addAddress($_POST['email'], $row['user_name']);
    $mail->addReplyTo("yourelementalpowerisnoodles@gmail.com", "yourelementalpowerisnoodles.kesug.com");
    echo "9.5<br>";
    // Content
    $mail->Subject = "[OTP] yourelementalpowerisnoodles.kesug.com";

    $mail->Body    = <<<EOT
Your Email: {$_POST['email']}
OTP: $otpString
EOT;
    
    // Send
    $mail->send();
    echo "10<br>";
    $email = $_POST['email'];
    $encoded_email = base64_encode($email);
    $url_safe_email = urlencode($encoded_email);

    header("location: ../otpConfirm.php?email={$url_safe_email}");
    echo "sendsuccess11<br>";
} catch (Exception $e) {
    header("location: ../resetPassword.php?msg=error_to_send_otp");
}
echo "12<br>";
?>