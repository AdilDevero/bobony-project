<?php
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Get staff member details
$sql = "SELECT * FROM staff WHERE id = $user_id LIMIT 1";
$result = $conn->query($sql);
$staff = $result->fetch_assoc();

// Get total staff count
$staff_count = $conn->query("SELECT COUNT(*) as count FROM staff WHERE status = 'active'")->fetch_assoc()['count'];

// Get total admins count
$admin_count = $conn->query("SELECT COUNT(*) as count FROM staff WHERE role = 'admin' AND status = 'active'")->fetch_assoc()['count'];

// Get recent logins
$recent_logins = $conn->query("SELECT username, last_login FROM staff WHERE last_login IS NOT NULL AND status = 'active' ORDER BY last_login DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bobony Family - Staff Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-badge {
            background: rgba(255,0,0,0.2);
            border: 1px solid red;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .logout-btn {
            background: rgba(255,0,0,0.8);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 600;
        }

        .logout-btn:hover {
            background: red;
            transform: translateY(-2px);
        }

        /* MAIN CONTAINER */
        .container {
            /* reduced top padding to bring content closer to navbar */
            padding: 120px 80px 80px;
            max-width: 1200px;
            margin: 0 auto;
            margin-left: 260px; /* space for sidebar */
        }

        .header {
            margin-bottom: 50px;
        }

        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .header h1 span {
            color: red;
        }

        .header p {
            color: #999;
            font-size: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        /* MANAGEMENT MENU */
        .management-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }

        .menu-card {
            background: linear-gradient(135deg, #1a1a1a, #111);
            border: 1px solid rgba(255,0,0,0.3);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .menu-card:hover {
            border-color: red;
            transform: translateY(-5px);
            box-shadow: 0 0 25px rgba(255,0,0,0.3);
            background: linear-gradient(135deg, #1f1f1f, #0a0a0a);
        }

        .menu-icon {
            font-size: 32px;
            color: red;
        }

        .menu-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: #fff;
        }

        .menu-card p {
            font-size: 12px;
            color: #888;
            margin: 0;
        }

        .stat-card {
            background: #111;
            border: 1px solid rgba(255,0,0,0.2);
            padding: 30px;
            border-radius: 12px;
            transition: 0.3s;
        }

        .stat-card:hover {
            border-color: red;
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(255,0,0,0.2);
        }

        .stat-card h3 {
            color: #999;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 800;
            color: red;
        }

        /* CONTENT GRID */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 50px;
        }

        .card {
            background: #111;
            border: 1px solid rgba(255,0,0,0.2);
            border-radius: 12px;
            padding: 30px;
            transition: 0.3s;
        }

        .card:hover {
            border-color: red;
            box-shadow: 0 0 20px rgba(255,0,0,0.2);
        }

        .card h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: red;
            border-bottom: 2px solid rgba(255,0,0,0.3);
            padding-bottom: 15px;
        }

        .card table {
            width: 100%;
        }

        .card table tr {
            border-bottom: 1px solid rgba(255,0,0,0.1);
        }

        .card table tr:last-child {
            border-bottom: none;
        }

        .card table td {
            padding: 12px 0;
            color: #ccc;
        }

        .card table td:first-child {
            color: #fff;
            font-weight: 500;
        }

        .card table td:last-child {
            text-align: right;
            color: #999;
            font-size: 13px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,0,0,0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #999;
            font-size: 14px;
        }

        .info-value {
            color: red;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            padding: 25px;
            background: #111;
            border-top: 1px solid rgba(255,0,0,0.3);
            margin-top: 50px;
            border-radius: 12px;
        }

        /* SIDEBAR */
        .sidebar{
            position:fixed;left:0;top:0;width:240px;height:100%;background:#0f0f0f;border-right:1px solid rgba(255,0,0,0.08);padding:20px 16px;z-index:900;display:flex;flex-direction:column;gap:18px}
        .sidebar .slogo{font-weight:800;color:red;font-size:20px;letter-spacing:1px}
        .sidebar .greet{color:#ccc;font-size:13px}
        .side-btn{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;text-decoration:none;color:#fff;background:transparent;border:1px solid rgba(255,255,255,0.02)}
        .side-btn i{width:26px;text-align:center;color:red}
        .side-btn span{font-weight:600}
        .side-btn:hover{background:rgba(255,0,0,0.08);border-color:rgba(255,0,0,0.18);transform:translateY(-2px)}
        .side-btn.primary{background:linear-gradient(90deg,rgba(255,0,0,0.12),transparent);border-color:rgba(255,0,0,0.18)}
        .sidebar.collapsed{transform:translateX(-260px)}
        .container{transition:margin-left .25s ease}
        .sidebar.collapsed ~ .container{margin-left:0}

        .sidebar-toggle{
            width:44px;height:44px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.03);color:#fff;cursor:pointer;margin-right:12px
        }
        .sidebar-toggle:hover{background:rgba(255,0,0,0.06);border-color:rgba(255,0,0,0.12)}

        @media(max-width: 1000px) {
            .container {
                padding: 150px 40px 80px;
                margin-left: 0;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 768px) {
            nav {
                padding: 15px 30px;
                flex-direction: column;
                gap: 15px;
            }

            .container {
                padding: 140px 20px 80px;
                margin-left: 0;
            }

            .header h1 {
                font-size: 28px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .nav-right {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="slogo">Bobony Family</div>
    <div class="greet">Hello, <?php echo htmlspecialchars($username); ?></div>
    <a href="ban_management.php" class="side-btn primary"><i class="fas fa-ban"></i><span>Ban Management</span></a>
    <a href="streamers_panel.php" class="side-btn"><i class="fas fa-video"></i><span>Manage Streamers</span></a>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
        <a href="create_user.php" class="side-btn"><i class="fas fa-user-plus"></i><span>Create User</span></a>
    <?php endif; ?>
    <div style="flex:1"></div>
    <a href="dashboard.php" class="side-btn"><i class="fas fa-home"></i><span>Dashboard</span></a>
</aside>

<nav>
    <div style="display:flex;align-items:center;gap:12px">
        <button id="sidebarToggle" class="sidebar-toggle" title="Toggle menu"><i id="sidebarToggleIcon" class="fas fa-angle-left"></i></button>
        <div class="logo">Bobony Family</div>
    </div>
    <div class="nav-right">
        <div class="user-info">
            <span><?php echo htmlspecialchars($username); ?></span>
            <div class="user-badge"><?php echo htmlspecialchars($role); ?></div>
        </div>
        <form method="POST" action="logout.php" style="margin: 0;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</nav>

<div class="container">

    <div class="header">
        <h1>Welcome to <span>Staff Dashboard</span></h1>
        <p>Manage staff and server information</p>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Staff</h3>
            <div class="number"><?php echo $staff_count; ?></div>
        </div>
        <div class="stat-card">
            <h3>Administrators</h3>
            <div class="number"><?php echo $admin_count; ?></div>
        </div>
        <div class="stat-card">
            <h3>Your Role</h3>
            <div class="number" style="font-size: 20px; text-transform: uppercase;"><?php echo htmlspecialchars($role); ?></div>
        </div>
    </div>

    
    <div class="management-menu">
        <a href="ban_management.php" class="menu-card">
            <div class="menu-icon"><i class="fas fa-ban"></i></div>
            <h3>Ban Management</h3>
            <p>View & manage bans</p>
        </a>
        <a href="streamers_panel.php" class="menu-card">
            <div class="menu-icon"><i class="fas fa-video"></i></div>
            <h3>Manage Streamers</h3>
            <p>Add, edit or remove streamers</p>
        </a>
    </div>
    
    <div class="content-grid">
        <div class="card">
            <h2>Your Profile</h2>
            <div class="profile-info">
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <span class="info-value"><?php echo htmlspecialchars($staff['username']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Role:</span>
                    <span class="info-value"><?php echo htmlspecialchars($staff['role']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value"><?php echo htmlspecialchars($staff['status']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Joined:</span>
                    <span class="info-value"><?php echo date('M d, Y', strtotime($staff['created_at'])); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last Login:</span>
                    <span class="info-value"><?php echo $staff['last_login'] ? date('M d, Y H:i', strtotime($staff['last_login'])) : 'First login'; ?></span>
                </div>
            </div>
        </div>

        <!-- RECENT LOGINS -->
        <div class="card">
            <h2>Recent Logins</h2>
            <table>
                <tbody>
                    <?php while ($login = $recent_logins->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($login['username']); ?></td>
                        <td><?php echo date('M d, H:i', strtotime($login['last_login'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($recent_logins->num_rows == 0): ?>
                    <tr>
                        <td colspan="2" style="text-align: center; color: #666;">No recent logins</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    </div>

</div>

<div class="footer">
    © 2026 Bobony Roleplay - All Rights Reserved
</div>

</body>
</html>
<script>
    // sidebar small-screen toggle: click on logo to toggle
    (function(){
        var sidebar = document.getElementById('sidebar');
        var logo = document.querySelector('nav .logo');
        if(sidebar && logo){
            logo.style.cursor = 'pointer';
            logo.addEventListener('click', function(){
                if(window.innerWidth < 1000){
                    if(sidebar.style.transform && sidebar.style.transform !== 'translateX(0px)'){
                        sidebar.style.transform = 'translateX(0)';
                    } else {
                        sidebar.style.transform = sidebar.style.transform === 'translateX(0px)' ? 'translateX(-260px)' : 'translateX(0)';
                    }
                }
            });
        }
    })();
    // sidebar toggle button (desktop + mobile) with persistence
    (function(){
        var sidebar = document.getElementById('sidebar');
        var btn = document.getElementById('sidebarToggle');
        var icon = document.getElementById('sidebarToggleIcon');
        if(!sidebar || !btn) return;

        // restore state
        var collapsed = localStorage.getItem('sidebarCollapsed') === '1';
        if(collapsed){
            sidebar.classList.add('collapsed');
            if(icon) icon.className = 'fas fa-angle-right';
        }

        btn.addEventListener('click', function(e){
            e.preventDefault();
            sidebar.classList.toggle('collapsed');
            var nowCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', nowCollapsed ? '1' : '0');
            if(icon) icon.className = nowCollapsed ? 'fas fa-angle-right' : 'fas fa-angle-left';
        });
    })();
</script>
