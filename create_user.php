<?php
require 'config.php';

// Only allow users with role 'owner' to access this page
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'owner') {
    header('HTTP/1.1 403 Forbidden');
    echo '<h2 style="color:#fff;background:#111;padding:30px;border-radius:8px;margin:40px;">403 Forbidden — You do not have permission to access this page.</h2>';
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';
    $submitted_role = trim($_POST['role'] ?? 'staff');
    $email = trim($_POST['email'] ?? '');

    $allowed_roles = ['staff'];
    $column_default = 'staff';
    $schema_sql = "SELECT COLUMN_TYPE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'staff' AND COLUMN_NAME = 'role' LIMIT 1";
    if ($schema_res = $conn->query($schema_sql)) {
        if ($schema_row = $schema_res->fetch_assoc()) {
            $column_type = $schema_row['COLUMN_TYPE'];
            $column_default = $schema_row['COLUMN_DEFAULT'] ?? $column_default;
            if (preg_match_all("/'([^']+)'/", $column_type, $matches)) {
                $allowed_roles = $matches[1];
            }
        }
        $schema_res->free();
    }

    if (!in_array($submitted_role, $allowed_roles, true)) {
        $role = $column_default ?: ($allowed_roles[0] ?? 'staff');
    } else {
        $role = $submitted_role;
    }

    if ($username === '' || $password === '' || $email === '') {
        $error = 'Username, password, and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check username uniqueness
        $check_sql = "SELECT id FROM staff WHERE username = '$username' LIMIT 1";
        $res = $conn->query($check_sql);
        if ($res && $res->num_rows > 0) {
            $error = 'Username is already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO staff (username, password, role, status, email, created_at) VALUES (?, ?, ?, 'active', ?, NOW())");
            if ($stmt) {
                $email_param = $email ?: '';
                $stmt->bind_param('ssss', $username, $hash, $role, $email_param);
                if ($stmt->execute()) {
                    $success = 'User created successfully.';
                } else {
                    $error = 'Database error: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = 'Database error: ' . $conn->error;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Staff User — Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif
        }

        body {
            background: #070707;
            color: white;
            overflow-x: hidden
        }

        /* GRID BACKGROUND */
        body::before {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(255, 0, 0, 0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 0, 0, 0.07) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -2;
        }

        body::after {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 30%, rgba(255, 0, 0, 0.15), transparent 70%);
            z-index: -1;
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

        nav {
            position: fixed;
            width: 100%;
            padding: 20px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 0, 0, 0.3);
            z-index: 1000
        }

        .logo {
            font-weight: 800;
            font-size: 22px;
            color: red;
            letter-spacing: 2px
        }

        nav ul {
            display: flex;
            gap: 30px;
            list-style: none
        }

        nav ul li a {
            text-decoration: none;
            color: #ccc;
            transition: 0.3s
        }

        nav ul li a:hover,
        .active {
            color: red
        }

        .nav-right {
            margin-left: auto;
            color: #ccc
        }

        .container {
            max-width: 800px;
            margin: 140px auto;
            padding: 20px
        }

        .card {
            background: #111;
            border: 1px solid rgba(255, 0, 0, 0.2);
            padding: 30px;
            border-radius: 12px
        }

        .card h2 {
            color: red;
            margin-bottom: 15px
        }

        .form-group {
            margin-bottom: 15px
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #ccc;
            font-weight: 600
        }

        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1px solid #333;
            background: #111;
            color: #fff;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: red;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 14px;
            padding-right: 40px;
            cursor: pointer;
        }

        select option {
            background: #fff;
            color: #000;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #ff0000;
            color: #fff;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #cc0000;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px
        }

        .error {
            background: rgba(255, 0, 0, 0.08);
            color: #ff7b7b;
            border-left: 4px solid #ff0000
        }

        .success {
            background: rgba(0, 255, 0, 0.04);
            color: #7bff9a;
            border-left: 4px solid #00ff00
        }

        /* sidebar removed for this page to match site layout */
        @media(max-width:800px) {
            nav {
                padding: 15px 20px
            }

            .container {
                margin-top: 100px;
                padding: 12px
            }
        }
    </style>
</head>

<body>
    <!-- <nav>
    <div style="display:flex;align-items:center;gap:12px">
        <div class="logo">Bobony Family</div>
    </div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="ban_management.php">Bans</a></li>
    </ul>
    <div class="nav-right"><?php echo htmlspecialchars($_SESSION['username']); ?> - <a href="logout.php" style="color:#ccc;margin-left:10px">Logout</a></div>
</nav> -->



    <div class="container">
        <div class="card">
            <h2>Create Staff User</h2>
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div><?php endif; ?>
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div><?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="owner">Owner</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Helper</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email (required)</label>
                    <input id="email" name="email" type="email" required>
                </div>
                <button class="btn" type="submit">Create User</button>
                <div class="back-link">
                    <a href="dashboard.php">← Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>