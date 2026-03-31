<?php
require 'config.php';

$error = '';
$success = '';
$valid_token = false;
$staff_id = null;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $token_hash = hash('sha256', $token);
    
    // Check if token is valid and not expired
    $sql = "SELECT id FROM staff WHERE reset_token_hash = '$token_hash' AND reset_token_expires > NOW() LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $valid_token = true;
        $row = $result->fetch_assoc();
        $staff_id = $row['id'];
    } else {
        $error = 'Invalid or expired password reset link.';
    }
} else {
    $error = 'No reset token provided.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $valid_token) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($password) || strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear token
        $update_sql = "UPDATE staff SET password = '$hashed_password', reset_token_hash = NULL, reset_token_expires = NULL WHERE id = $staff_id";
        
        if ($conn->query($update_sql)) {
            $success = 'Your password has been reset successfully. You can now login.';
            $valid_token = false; // Hide form on success
        } else {
            $error = 'Failed to reset password. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bobony Family - Reset staff Password</title>
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
    <h1>Reset Password</h1>
    <p>Set a new password for your staff account</p>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if ($valid_token): ?>
    <form method="POST">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" placeholder="Enter new password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn-reset">Reset Password</button>
    </form>
    <?php endif; ?>

    <div class="back-link">
        <a href="login.php">← Back to Login</a>
    </div>
</div>

<script src="animations.js"></script>
</body>
</html>
