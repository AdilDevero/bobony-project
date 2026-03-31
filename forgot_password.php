<?php
require 'config.php';
require 'vendor/autoload.php';

// Explicitly require the files to fix your IDE's "Unknown Class" warnings
require_once 'vendor/phpmailer/phpmailer/src/Exception.php';
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    }
    else {
        // Query database to see if email exists
        $sql = "SELECT id FROM staff WHERE email = '$email' AND status = 'active' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $staff_id = $row['id'];

            // Generate a unique token
            $token = bin2hex(random_bytes(16));
            $token_hash = hash('sha256', $token);

            // Save hashed token to database
            // By using DATE_ADD(NOW(), INTERVAL 1 HOUR), we prevent timezone desync 
            // between PHP and MySQL which often causes "expired link" errors locally.
            $update_sql = "UPDATE staff SET reset_token_hash = '$token_hash', reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = $staff_id";
            if ($conn->query($update_sql)) {
                // Prepare PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // IMPORTANT: To actually send emails from localhost/Laragon, 
                    // you MUST use a real SMTP server. We will set this up for Gmail.
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    // CHANGE THIS TO YOUR GMAIL ADDRESS
                    $mail->Username = 'your.email.@gmail.com';
                    // CHANGE THIS TO YOUR GMAIL APP PASSWORD (NOT your normal password)
                    $mail->Password = 'your-app-password'; // The 16-letter App Password here
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // The 'From' address will show up as the sender
                    $mail->setFrom('your.email.@gmail.com', 'Bobony Family Staff');
                    $mail->addAddress($email);

                    // Content
                    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=$token";

                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request - Bobony Family';
                    $mail->Body = "Hello,<br><br>We received a request to reset your staff password.<br>
                                      Click the link below to set a new password:<br><br>
                                      <a href='$reset_link'>$reset_link</a><br><br>
                                      If you did not request this, please ignore this email.<br>
                                      This link will expire in 1 hour.";
                    $mail->AltBody = "Hello,\n\nWe received a request to reset your staff password.\nCopy and paste the link below to set a new password:\n\n$reset_link\n\nIf you did not request this, please ignore this email.";

                    $mail->send();
                    $success = 'Password reset instructions have been sent to your email.';
                }
                catch (Exception $e) {
                    $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
            else {
                $error = 'Failed to generate reset token. Please try again.';
            }
        }
        else {
            // For security, it's often better to show the same success message 
            // even if the email wasn't found.
            $success = 'If an account with that email exists, a password reset link has been sent.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bobony Family - Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #070707;
            color: white;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* GRID BACKGROUND */
        body::before {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(255,0,0,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,0,0,0.07) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -2;
        }

        body::after {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 30%, rgba(255,0,0,0.15), transparent 70%);
            z-index: -1;
        }

        .login-container {
            background: #111;
            border: 1px solid rgba(255,0,0,0.3);
            border-radius: 15px;
            padding: 50px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 40px rgba(255,0,0,0.2);
            margin-top: 0px;
        }

        .login-container h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
            color: red;
        }

        .login-container p {
            text-align: center;
            color: #999;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #ccc;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255,0,0,0.3);
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            color: white;
            font-size: 14px;
            transition: 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: red;
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 15px rgba(255,0,0,0.2);
        }

        .form-group input::placeholder {
            color: #666;
        }

        .error-message {
            background: rgba(255,0,0,0.1);
            border-left: 3px solid red;
            color: #ff6b6b;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .success-message {
            background: rgba(0,255,0,0.1);
            border-left: 3px solid #00ff00;
            color: #6bff6b;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .btn-reset {
            width: 100%;
            padding: 12px;
            background: red;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-reset:hover {
            background: #cc0000;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255,0,0,0.4);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 13px;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: red;
            text-decoration: none;
            transition: 0.3s;
            font-size: 14px;
        }

        .back-link a:hover {
            color: white;
        }

        @media(max-width: 768px) {
            .login-container {
                margin: 20px;
                padding: 35px 25px;
            }
        }
    </style>
</head>

<body>

<div class="login-container">
    <h1>Forgot Password</h1>
    <p>Enter your email to receive a reset link</p>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php
endif; ?>

    <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php
endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your staff email" required>
        </div>

        <button type="submit" class="btn-reset">Send Reset Link</button>
    </form>

    <div class="back-link">
        <a href="login.php">← Back to Login</a>
    </div>

    <div class="footer-text">
        Staff login system • Bobony Family 2026
    </div>
</div>

<script src="animations.js"></script>
</body>
</html>
