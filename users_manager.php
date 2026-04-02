<?php
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? '';

// Ensure only owners can access this page
if ($role !== 'owner') {
    header('HTTP/1.1 403 Forbidden');
    echo '<h2 style="color:#fff;background:#111;padding:30px;border-radius:8px;margin:40px;font-family:sans-serif;">403 Forbidden — You do not have permission to access this page.</h2>';
    exit();
}

$success_msg = '';
$error_msg = '';

// Handle Delete User
if (isset($_GET['delete_id'])) {
    if ($role === 'owner') { // Usually only owner can delete other staff
        $delete_id = (int)$_GET['delete_id'];
        if ($delete_id != $user_id) { // Prevent self-deletion
            $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $delete_id);
                if ($stmt->execute()) {
                    $success_msg = "User deleted successfully!";
                } else {
                    $error_msg = "Error deleting user: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $error_msg = "You cannot delete yourself.";
        }
    } else {
        $error_msg = "Only owners can delete users.";
    }
}

// Handle Toggle Status
if (isset($_GET['toggle_id'])) {
    $toggle_id = (int)$_GET['toggle_id'];
    if ($toggle_id != $user_id) { // Prevent toggling own status
        $stmt = $conn->prepare("SELECT status FROM staff WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $toggle_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $new_status = ($row['status'] === 'active') ? 'inactive' : 'active';
                $update_stmt = $conn->prepare("UPDATE staff SET status = ? WHERE id = ?");
                if ($update_stmt) {
                    $update_stmt->bind_param("si", $new_status, $toggle_id);
                    if ($update_stmt->execute()) {
                        $success_msg = "User status updated to " . ucfirst($new_status) . "!";
                    } else {
                        $error_msg = "Error updating user status: " . $update_stmt->error;
                    }
                    $update_stmt->close();
                }
            }
            $stmt->close();
        }
    } else {
        $error_msg = "You cannot change your own status.";
    }
}

// Handle Update Role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role_id'])) {
    if ($role === 'owner') {
        $update_id = (int)$_POST['update_role_id'];
        $new_role = $_POST['new_role'] ?? '';
        
        if ($update_id != $user_id) { // Prevent modifying own role here
            $valid_roles = ['owner', 'admin', 'staff'];
            if (in_array($new_role, $valid_roles)) {
                $stmt = $conn->prepare("UPDATE staff SET role = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $new_role, $update_id);
                    if ($stmt->execute()) {
                        $success_msg = "User role updated successfully!";
                    } else {
                        $error_msg = "Error updating user role: " . $stmt->error;
                    }
                    $stmt->close();
                }
            } else {
                $error_msg = "Invalid role selected.";
            }
        } else {
             $error_msg = "You cannot modify your own role.";
        }
    }
}

// Check if we are editing a user's role
$edit_user = null;
if (isset($_GET['edit_role_id']) && $role === 'owner') {
    $edit_id = (int)$_GET['edit_role_id'];
    $stmt = $conn->prepare("SELECT id, username, role FROM staff WHERE id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $edit_user = $row;
        }
        $stmt->close();
    }
}

// Fetch all staff users
$users = [];
$users_sql = "SELECT id, username, email, role, status, created_at, last_login FROM staff ORDER BY created_at DESC";
$users_result = $conn->query($users_sql);
if ($users_result && $users_result->num_rows > 0) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}

$total_users = count($users);
$active_users = count(array_filter($users, function($u) { return $u['status'] == 'active'; }));
$admin_users = count(array_filter($users, function($u) { return $u['role'] == 'admin' || $u['role'] == 'owner'; }));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
    <title>Users Management — Admin Panel</title>
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

        .container {
            max-width: 1400px;
            margin: 60px auto 40px;
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
            grid-template-columns: repeat(3, 1fr);
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
            text-decoration: none;
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

        .users-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,0,0,0.3);
            margin-bottom: 40px;
        }

        .users-table thead {
            background: #111;
            border-bottom: 2px solid rgba(255,0,0,0.5);
        }

        .users-table th {
            padding: 15px;
            text-align: left;
            color: red;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }

        .users-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255,0,0,0.2);
            font-size: 14px;
        }

        .users-table tr:hover {
            background: rgba(255,0,0,0.05);
        }

        .status-active {
            color: #6bff6b;
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
            text-transform: uppercase;
        }

        .badge-owner {
            background: rgba(255, 215, 0, 0.2);
            color: gold;
            border: 1px solid gold;
        }

        .badge-admin {
            background: rgba(255,0,0,0.2);
            color: #ff6b6b;
            border: 1px solid #ff6b6b;
        }

        .badge-staff {
            background: rgba(100,150,255,0.2);
            color: #64a8ff;
            border: 1px solid #64a8ff;
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
            text-decoration: none;
        }

        .btn-toggle {
            background: rgba(100,255,100,0.2);
            color: #6bff6b;
            border: 1px solid #6bff6b;
        }

        .btn-toggle:hover {
            background: rgba(100,255,100,0.4);
        }

        .btn-toggle-inactive {
            background: rgba(255,165,0,0.2);
            color: orange;
            border: 1px solid orange;
        }

        .btn-toggle-inactive:hover {
            background: rgba(255,165,0,0.4);
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
            font-weight: 600;
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
                grid-template-columns: 1fr;
            }
            .users-table {
                font-size: 12px;
                display: block;
                overflow-x: auto;
            }
            .container {
                padding: 0 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="page-title">
        <i class="fas fa-users"></i>
        Users Management
    </div>

    <?php if ($success_msg): ?>
        <div class="message success-message"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="message error-message"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="stat-number"><?php echo $total_users; ?></div>
        </div>
        <div class="stat-card">
            <h3>Active Users</h3>
            <div class="stat-number"><?php echo $active_users; ?></div>
        </div>
        <div class="stat-card">
            <h3>Admins & Owners</h3>
            <div class="stat-number"><?php echo $admin_users; ?></div>
        </div>
    </div>

    <?php if ($edit_user): ?>
        <div style="background:#111; border:1px solid rgba(255,100,100,0.3); padding:20px; border-radius:10px; margin-bottom:30px;">
            <h3 style="color:red; margin-bottom:15px; font-size:18px;">Edit Role: <?php echo htmlspecialchars($edit_user['username']); ?></h3>
            <form method="POST" action="users_manager.php" style="display:flex; gap:15px; align-items:center;">
                <input type="hidden" name="update_role_id" value="<?php echo $edit_user['id']; ?>">
                <select name="new_role" style="padding:10px; border-radius:6px; border:1px solid #333; background:#222; color:#fff; min-width:150px;">
                    <option value="owner" <?php echo $edit_user['role'] === 'owner' ? 'selected' : ''; ?>>Owner</option>
                    <option value="admin" <?php echo $edit_user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="staff" <?php echo $edit_user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                </select>
                <button type="submit" class="btn btn-add">Update Role</button>
                <a href="users_manager.php" class="btn btn-back">Cancel</a>
            </form>
        </div>
    <?php endif; ?>

    <div class="button-group">
        <?php if ($role === 'owner'): ?>
        <a href="create_user.php" class="btn btn-add">
            <i class="fas fa-user-plus"></i> Create User
        </a>
        <?php endif; ?>
        <a href="dashboard.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <?php if (count($users) > 0): ?>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?php echo $user['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php if ($user['role'] === 'owner'): ?>
                                <span class="badge badge-owner">Owner</span>
                            <?php elseif ($user['role'] === 'admin'): ?>
                                <span class="badge badge-admin">Admin</span>
                            <?php else: ?>
                                <span class="badge badge-staff">Staff</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-<?php echo $user['status'] == 'active' ? 'active' : 'inactive'; ?>">
                                <i class="fas fa-circle" style="font-size: 10px; margin-right: 5px;"></i>
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                        <td><?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                        <td>
                            <div class="action-buttons">
                                <?php if ($user['id'] != $user_id): // Don't show actions for own account ?>
                                    
                                    <?php if ($role === 'owner'): ?>
                                    <a href="users_manager.php?edit_role_id=<?php echo $user['id']; ?>" 
                                       class="btn-small" style="background:rgba(100,150,255,0.2); color:#64a8ff; border:1px solid #64a8ff;"
                                       title="Edit Role">
                                        <i class="fas fa-edit"></i> Edit Role
                                    </a>
                                    <?php endif; ?>

                                    <a href="users_manager.php?toggle_id=<?php echo $user['id']; ?>" 
                                       class="btn-small <?php echo $user['status'] === 'active' ? 'btn-toggle-inactive' : 'btn-toggle'; ?>"
                                       title="Toggle Status">
                                        <i class="fas <?php echo $user['status'] === 'active' ? 'fa-ban' : 'fa-check'; ?>"></i> 
                                        <?php echo $user['status'] === 'active' ? 'Disable' : 'Activate'; ?>
                                    </a>

                                    <?php if ($role === 'owner'): ?>
                                    <a href="users_manager.php?delete_id=<?php echo $user['id']; ?>" 
                                       class="btn-small btn-delete" 
                                       onclick="return confirm('Are you sure you want to completely DELETE this user? This cannot be undone.');"
                                       title="Delete User">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <span style="color: #666; font-size: 12px; font-style: italic;">Your Account</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h3>No users found</h3>
            <p>There are currently no staff members in the system.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
