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
    $role = $conn->real_escape_string($_POST['role'] ?? 'staff');
    $email = $conn->real_escape_string(trim($_POST['email'] ?? null));

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        // Check username uniqueness
        $check_sql = "SELECT id FROM staff WHERE username = '$username' LIMIT 1";
        $res = $conn->query($check_sql);
        if ($res && $res->num_rows > 0) {
            $error = 'Username is already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO staff (username, password, role, status, email, created_at) VALUES ('" . $username . "', '" . $hash . "', '" . $role . "', 'active', '" . ($email ?: '') . "', NOW())";
            if ($conn->query($insert_sql)) {
                $success = 'User created successfully.';
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
    <style>
        *{box-sizing:border-box;margin:0;padding:0;font-family:'Poppins',sans-serif}
        body{background:#070707;color:white;overflow-x:hidden}

        /* GRID BACKGROUND */
        body::before{
            content:"";
            position:fixed;
            width:100%;
            height:100%;
            background-image:
                linear-gradient(rgba(255,0,0,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,0,0,0.07) 1px, transparent 1px);
            background-size:40px 40px;
            z-index:-2;
        }

        body::after{
            content:"";
            position:fixed;
            width:100%;
            height:100%;
            background:radial-gradient(circle at 50% 30%, rgba(255,0,0,0.15), transparent 70%);
            z-index:-1;
        }

        nav{position:fixed;width:100%;padding:20px 80px;display:flex;justify-content:space-between;align-items:center;background:rgba(0,0,0,0.8);backdrop-filter:blur(10px);border-bottom:1px solid rgba(255,0,0,0.3);z-index:1000}
        .logo{font-weight:800;font-size:22px;color:red;letter-spacing:2px}
        nav ul{display:flex;gap:30px;list-style:none}
        nav ul li a{text-decoration:none;color:#ccc;transition:0.3s}
        nav ul li a:hover,.active{color:red}
        .nav-right{margin-left:auto;color:#ccc}
        .container{max-width:800px;margin:140px auto;padding:20px}
        .card{background:#111;border:1px solid rgba(255,0,0,0.2);padding:30px;border-radius:12px}
        .card h2{color:red;margin-bottom:15px}
        .form-group{margin-bottom:15px}
        label{display:block;margin-bottom:6px;color:#ccc;font-weight:600}
        input,select{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,0,0,0.15);background:rgba(255,255,255,0.03);color:#fff}
        .btn{display:inline-block;padding:10px 16px;background:red;color:#fff;border-radius:8px;border:none;cursor:pointer;margin-top:10px}
        .message{padding:12px;border-radius:8px;margin-bottom:12px}
        .error{background:rgba(255,0,0,0.08);color:#ff7b7b;border-left:4px solid #ff0000}
        .success{background:rgba(0,255,0,0.04);color:#7bff9a;border-left:4px solid #00ff00}
        /* sidebar removed for this page to match site layout */
        @media(max-width:800px){nav{padding:15px 20px}.container{margin-top:100px;padding:12px}}
    </style>
</head>
<body>
<!-- <nav>
    <div style="display:flex;align-items:center;gap:12px">
        <div class="logo">Bobony Family</div>
    </div>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="ban_management.php">Bans</a></li>
    </ul>
    <div class="nav-right"><?php echo htmlspecialchars($_SESSION['username']); ?> - <a href="logout.php" style="color:#ccc;margin-left:10px">Logout</a></div>
</nav> -->



<div class="container">
    <div class="card">
        <h2>Create Staff User</h2>
        <?php if ($error): ?><div class="message error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="message success"><?php echo $success; ?></div><?php endif; ?>

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
                <label for="email">Email (optional)</label>
                <input id="email" name="email" type="email">
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
