<?php
require 'config.php';

$error = '';
$success = '';

// remove hardcoded credentials; will validate against database

if (isset($_GET['expired'])) {
    $error = 'Session expired. Please login again.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // lookup user in staff table
    $sql = "SELECT id, username, password, role FROM staff WHERE username = '$username' AND status = 'active' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // handle hashed or plain passwords
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();
            // update last login
            $update_sql = "UPDATE staff SET last_login = NOW() WHERE id = " . $user['id'];
            $conn->query($update_sql);
            header("Location: dashboard.php");
            exit();
        } else {
            $error = 'Invalid password.';
        }
    } else {
        $error = 'Username not found or inactive.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bobony Family - Staff Login</title>
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

        /* NAVBAR */
        nav {
            position: fixed;
            width: 100%;
            padding: 20px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,0,0,0.3);
            z-index: 1000;
        }

        .logo {
            font-weight: 800;
            font-size: 22px;
            color: red;
            letter-spacing: 2px;
        }

        nav ul {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        nav ul li a {
            text-decoration: none;
            color: #ccc;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: red;
        }

        /* hide login nav items on login page if needed? keep for consistency */


        .login-container {
            background: #111;
            border: 1px solid rgba(255,0,0,0.3);
            border-radius: 15px;
            padding: 50px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 40px rgba(255,0,0,0.2);
            margin-top: 100px;
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

        .btn-login {
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

        .btn-login:hover {
            background: #cc0000;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255,0,0,0.4);
        }

        .btn-login:active {
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
            nav {
                padding: 15px 30px;
            }

            .login-container {
                margin: 80px 20px 0;
                padding: 35px 25px;
            }

            .login-container h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<!-- <nav>
    <div class="logo">Bobony Family</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="discord.php">Discord</a></li>
        <li><a href="team.php">Team</a></li>
        <li><a href="bans.php">Bans</a></li>
        <li><a href="REGLEMENTS.php">REGLEMENTS</a></li>
    </ul>
</nav> -->

<div class="login-container">
    <h1>Staff Login</h1>
    <p>Secure access for staff members only</p>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn-login" style="margin-bottom: 15px;">Login</button>
        <a href="forgot_password.php" style="display: block; text-align: center; color: red; text-decoration: none; font-size: 14px; margin-bottom: 20px;">Forgot Password?</a>
    </form>

    <div class="back-link">
        <a href="index.php">← Back to Home</a>
    </div>

    <div class="footer-text">
        Staff login system • Bobony Family 2026
    </div>
</div>

</body>
</html>
