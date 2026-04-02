<?php
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Handle delete ban
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM bans WHERE id = '$delete_id'";
    if ($conn->query($delete_sql)) {
        $success_msg = "Ban removed successfully!";
    } else {
        $error_msg = "Error removing ban: " . $conn->error;
    }
}

// Fetch all bans
$bans = [];
$bans_sql = "SELECT b.*, s.username as banned_by_name FROM bans b 
             LEFT JOIN staff s ON b.banned_by = s.id 
             ORDER BY b.banned_date DESC";
$bans_result = $conn->query($bans_sql);
if ($bans_result && $bans_result->num_rows > 0) {
    while ($row = $bans_result->fetch_assoc()) {
        $bans[] = $row;
    }
}

// Count statistics
$total_bans = count($bans);
$active_bans = count(array_filter($bans, function($b) { return $b['status'] == 'active'; }));
$permanent_bans = count(array_filter($bans, function($b) { return $b['is_permanent']; }));
$blacklisted = count(array_filter($bans, function($b) { return $b['is_blacklisted']; }));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
    <title>Ban Management — Admin Panel - Bobony Family</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
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

        nav ul{
            display:flex;
            gap:30px;
            list-style:none;
        }

        nav ul li a{
            text-decoration:none;
            color:#ccc;
            transition:0.3s;
        }

        nav ul li a:hover{
            color:red;
        }

        .nav-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-right a {
            color: #ccc;
            text-decoration: none;
            transition: 0.3s;
        }

        .nav-right a:hover {
            color: red;
        }

        .container {
            /* tighten space under navbar */
            max-width: 1400px;
            margin: 100px auto 40px;
            padding: 0 40px;
        }

        .page-title {
            font-size: 32px;
            color: red;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #1a1a1a;
            border: 1px solid rgba(255,0,0,0.3);
            border-radius: 10px;
            padding: 25px;
            text-align: center;
        }

        .stat-card h3 {
            color: #ccc;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .stat-number {
            font-size: 36px;
            color: red;
            font-weight: 800;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add {
            background: red;
            color: white;
        }

        .btn-add:hover {
            background: #cc0000;
            transform: translateY(-2px);
        }

        .btn-back {
            background: rgba(255,255,255,0.1);
            color: #ccc;
        }

        .btn-back:hover {
            background: rgba(255,0,0,0.2);
            color: red;
        }

        .bans-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,0,0,0.3);
            margin-bottom: 40px;
        }

        .bans-table thead {
            background: #111;
            border-bottom: 2px solid rgba(255,0,0,0.5);
        }

        .bans-table th {
            padding: 15px;
            text-align: left;
            color: red;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }

        .bans-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255,0,0,0.2);
            font-size: 14px;
        }

        .bans-table tr:hover {
            background: rgba(255,0,0,0.05);
        }

        .status-active {
            color: #ff6b6b;
            font-weight: 600;
        }

        .status-inactive {
            color: #888;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 5px;
        }

        .badge-perm {
            background: rgba(255,0,0,0.2);
            color: #ff6b6b;
        }

        .badge-blacklist {
            background: rgba(0,0,0,0.5);
            color: #999;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-small {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-edit {
            background: rgba(100,150,255,0.3);
            color: #64a8ff;
            border: 1px solid #64a8ff;
        }

        .btn-edit:hover {
            background: rgba(100,150,255,0.5);
        }

        .btn-delete {
            background: rgba(255,100,100,0.3);
            color: #ff6464;
            border: 1px solid #ff6464;
        }

        .btn-delete:hover {
            background: rgba(255,100,100,0.5);
        }

        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid;
        }

        .success-message {
            background: rgba(0,255,0,0.1);
            color: #6bff6b;
            border-left-color: #00ff00;
        }

        .error-message {
            background: rgba(255,0,0,0.1);
            color: #ff6b6b;
            border-left-color: red;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state i {
            font-size: 48px;
            color: #444;
            margin-bottom: 20px;
        }

        @media(max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .bans-table {
                font-size: 12px;
            }

            .bans-table th,
            .bans-table td {
                padding: 10px;
            }

            nav {
                padding: 15px 20px;
            }

            .container {
                padding: 0 20px;
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
        <li><a href="login.php" style="color: red;">Staff Login</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
            <li><a href="create_user.php" style="color: red;">Create User</a></li>
        <?php endif; ?>
    </ul>
    <div class="nav-right">
        <span><?php echo htmlspecialchars($username); ?></span>
        <a href="logout.php">Logout</a>
    </div>
</nav> -->

<div class="container">
    <div class="page-title">
        <i class="fas fa-ban"></i>
        Ban Management — Admin Panel
    </div>

    <?php if (isset($success_msg)): ?>
        <div class="message success-message"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <?php if (isset($error_msg)): ?>
        <div class="message error-message"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Bans</h3>
            <div class="stat-number"><?php echo $total_bans; ?></div>
        </div>
        <div class="stat-card">
            <h3>Active Bans</h3>
            <div class="stat-number"><?php echo $active_bans; ?></div>
        </div>
        <div class="stat-card">
            <h3>Permanent Bans</h3>
            <div class="stat-number"><?php echo $permanent_bans; ?></div>
        </div>
        <div class="stat-card">
            <h3>Blacklisted</h3>
            <div class="stat-number"><?php echo $blacklisted; ?></div>
        </div>
    </div>

    <div class="button-group">
        <a href="ban_form.php" class="btn btn-add">
            <i class="fas fa-plus"></i> Add Ban
        </a>
        <a href="dashboard.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <?php if (count($bans) > 0): ?>
        <table class="bans-table">
            <thead>
                <tr>
                    <th>Discord Username</th>
                    <th>Discord ID</th>
                    <th>Reason</th>
                    <th>Ban Count</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Banned By</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bans as $ban): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ban['discord_username']); ?></td>
                        <td><?php echo htmlspecialchars($ban['discord_id']); ?></td>
                        <td><?php echo htmlspecialchars($ban['ban_reason']); ?></td>
                        <td><?php echo $ban['ban_count']; ?></td>
                        <td>
                            <?php if ($ban['is_permanent']): ?>
                                <span class="badge badge-perm">PERMANENT</span>
                            <?php else: ?>
                                <?php echo $ban['ban_duration']; ?> hours
                            <?php endif; ?>
                            <?php if ($ban['is_blacklisted']): ?>
                                <span class="badge badge-blacklist">BLACKLISTED</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-<?php echo $ban['status'] == 'active' ? 'active' : 'inactive'; ?>">
                                <?php echo ucfirst($ban['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($ban['banned_by_name']); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($ban['banned_date'])); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="ban_form.php?edit_id=<?php echo $ban['id']; ?>" class="btn-small btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="ban_management.php?delete_id=<?php echo $ban['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure?');">
                                    <i class="fas fa-trash"></i> Remove
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No bans yet</h3>
            <p>Start adding bans to manage your server</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
