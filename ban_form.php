<?php
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Ban reasons extracted from REGLEMENTS
$ban_reasons = [
    "TOXIC RP: 2 to 4 Days Ban",
    "GIRLS RP: Disrespecting female players - PERMA BAN",
    "Fight Cooldown Violation: No Respect CD - 2 Days",
    "Metagaming: Using Discord/Stream info in RP - 2 Days Min",
    "Carkill Intentional - 4 Days Min",
    "Freekill - 2 to 4 Days",
    "NVL (No Value of Life) - 4 Days Min",
    "/Me Misuse - 2 Days",
    "Salary Abuse - 7 Days",
    "Acting as Admin in RP - 2 Days Min",
    "ALT to ALT Transfer - PERMA BAN",
    "Out of Character Abuse - 2 Days Min",
    "Win RP - 2 Days",
    "Refuse RP with Police/Medic - 2 Days",
    "Mass RP limit - 48h Ban",
    "ALT+F4 during scene - 7 Days",
    "2 Jobs - 7 Days",
    "Cancel Animation Abuse - 7 Days",
    "Destructive Speech (racism, politics) - 2 Weeks to PERMA",
    "Rob Police Forbidden - 2 Days",
    "Hostage Issues - 48h Ban",
    "Escorting Dead Players - 2 Days",
    "Streamer Rules Violation - 48h to PERMA",
    "Other"
];

$edit_id = isset($_GET['edit_id']) ? $conn->real_escape_string($_GET['edit_id']) : null;
$ban = null;

// Load existing ban for editing
if ($edit_id) {
    $edit_sql = "SELECT * FROM bans WHERE id = '$edit_id'";
    $edit_result = $conn->query($edit_sql);
    if ($edit_result && $edit_result->num_rows > 0) {
        $ban = $edit_result->fetch_assoc();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $discord_username = $conn->real_escape_string($_POST['discord_username']);
    $discord_id = $conn->real_escape_string($_POST['discord_id']);
    $ban_reason = $conn->real_escape_string($_POST['ban_reason']);
    $ban_duration = intval($_POST['ban_duration']);
    $ban_count = intval($_POST['ban_count']);
    $is_permanent = isset($_POST['is_permanent']) ? 1 : 0;
    $is_blacklisted = isset($_POST['is_blacklisted']) ? 1 : 0;
    
    // Handle notes - properly format NULL for SQL
    if (isset($_POST['notes']) && !empty($_POST['notes'])) {
        $notes = "'" . $conn->real_escape_string($_POST['notes']) . "'";
    } else {
        $notes = "NULL";
    }

    // If permanent, set duration to 0
    if ($is_permanent) {
        $ban_duration = 0;
    }

    if ($edit_id) {
        // Update existing ban
        $update_sql = "UPDATE bans SET 
                       discord_username = '$discord_username',
                       discord_id = '$discord_id',
                       ban_reason = '$ban_reason',
                       ban_duration = $ban_duration,
                       ban_count = $ban_count,
                       is_permanent = $is_permanent,
                       is_blacklisted = $is_blacklisted,
                       notes = $notes
                       WHERE id = '$edit_id'";
        
        if ($conn->query($update_sql)) {
            $success_msg = "Ban updated successfully!";
            header("Refresh:2; url=ban_management.php");
        } else {
            $error_msg = "Error updating ban: " . $conn->error;
        }
    } else {
        // Create new ban
        if ($ban_duration > 0) {
            $insert_sql = "INSERT INTO bans (discord_username, discord_id, ban_reason, ban_duration, ban_count, is_permanent, is_blacklisted, banned_by, unban_date, notes) 
                          VALUES ('$discord_username', '$discord_id', '$ban_reason', $ban_duration, $ban_count, $is_permanent, $is_blacklisted, $user_id, DATE_ADD(NOW(), INTERVAL $ban_duration HOUR), $notes)";
        } else {
            $insert_sql = "INSERT INTO bans (discord_username, discord_id, ban_reason, ban_duration, ban_count, is_permanent, is_blacklisted, banned_by, notes) 
                          VALUES ('$discord_username', '$discord_id', '$ban_reason', 0, $ban_count, $is_permanent, $is_blacklisted, $user_id, $notes)";
        }
        
        if ($conn->query($insert_sql)) {
            $success_msg = "Ban added successfully!";
            header("Refresh:2; url=ban_management.php");
        } else {
            $error_msg = "Error adding ban: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edit_id ? 'Edit' : 'Add'; ?> Ban - Bobony Family</title>
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
            max-width: 800px;
            margin: 120px auto 40px;
            padding: 40px;
            background: #1a1a1a;
            border: 1px solid rgba(255,0,0,0.3);
            border-radius: 15px;
        }

        .page-title {
            font-size: 28px;
            color: red;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #ccc;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255,0,0,0.3);
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            color: white;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: red;
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 15px rgba(255,0,0,0.2);
        }

        .form-group select option {
            background: #1a1a1a;
            color: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .checkbox-group {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-item input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }

        .checkbox-item label {
            margin: 0;
            cursor: pointer;
            color: #ccc;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }

        .btn {
            flex: 1;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit {
            background: red;
            color: white;
        }

        .btn-submit:hover {
            background: #cc0000;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: rgba(255,255,255,0.1);
            color: #ccc;
        }

        .btn-cancel:hover {
            background: rgba(255,0,0,0.2);
            color: red;
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

        .info-text {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        @media(max-width: 768px) {
            .container {
                margin: 100px 20px 40px;
                padding: 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            nav {
                padding: 15px 20px;
            }
        }
    </style>
</head>

<body>

<nav>
    <div class="logo">Bobony Family</div>
    <div class="nav-right">
        <span><?php echo htmlspecialchars($username); ?></span>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="page-title">
        <i class="fas fa-<?php echo $edit_id ? 'edit' : 'plus'; ?>"></i>
        <?php echo $edit_id ? 'Edit Ban' : 'Add New Ban'; ?>
    </div>

    <?php if (isset($success_msg)): ?>
        <div class="message success-message"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <?php if (isset($error_msg)): ?>
        <div class="message error-message"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="discord_username">Discord Username</label>
                <input type="text" id="discord_username" name="discord_username" placeholder="e.g., JohnDoe#1234" required value="<?php echo $ban ? htmlspecialchars($ban['discord_username']) : ''; ?>">
                <div class="info-text">Enter the player's full Discord username</div>
            </div>

            <div class="form-group">
                <label for="discord_id">Discord ID</label>
                <input type="text" id="discord_id" name="discord_id" placeholder="e.g., 123456789" required value="<?php echo $ban ? htmlspecialchars($ban['discord_id']) : ''; ?>">
                <div class="info-text">Unique Discord ID number</div>
            </div>
        </div>

        <div class="form-group">
            <label for="ban_reason">Ban Reason</label>
            <select id="ban_reason" name="ban_reason" required>
                <option value="">Select a reason...</option>
                <?php foreach ($ban_reasons as $reason): ?>
                    <option value="<?php echo htmlspecialchars($reason); ?>" <?php echo ($ban && $ban['ban_reason'] == $reason) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($reason); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="ban_duration">Ban Duration (Hours)</label>
                <input type="number" id="ban_duration" name="ban_duration" min="0" placeholder="e.g., 48" value="<?php echo $ban && !$ban['is_permanent'] ? $ban['ban_duration'] : '48'; ?>">
                <div class="info-text">Leave as 0 or check "Permanent" for permanent bans</div>
            </div>

            <div class="form-group">
                <label for="ban_count">Ban Count</label>
                <input type="number" id="ban_count" name="ban_count" min="1" placeholder="1" value="<?php echo $ban ? $ban['ban_count'] : '1'; ?>" required>
                <div class="info-text">How many times this player has been banned</div>
            </div>
        </div>

        <div class="form-group">
            <label>Ban Type Options</label>
            <div class="checkbox-group">
                <div class="checkbox-item">
                    <input type="checkbox" id="is_permanent" name="is_permanent" <?php echo ($ban && $ban['is_permanent']) ? 'checked' : ''; ?>>
                    <label for="is_permanent">Permanent Ban</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="is_blacklisted" name="is_blacklisted" <?php echo ($ban && $ban['is_blacklisted']) ? 'checked' : ''; ?>>
                    <label for="is_blacklisted">Blacklist Player</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Additional Notes</label>
            <textarea id="notes" name="notes" placeholder="Add any additional details about the ban..."></textarea>
            <?php if ($ban && $ban['notes']): ?>
                <script>
                    document.getElementById('notes').value = <?php echo json_encode($ban['notes']); ?>;
                </script>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-check"></i> <?php echo $edit_id ? 'Update Ban' : 'Add Ban'; ?>
            </button>
            <a href="ban_management.php" class="btn btn-cancel">
                <i class="fas fa-times"></i> Cancel
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="discord.php">Discord</a></li>
                <li><a href="team.php">Team</a></li>
                <li><a href="bans.php">Bans</a></li>
                <li><a href="REGLEMENTS.php">REGLEMENTS</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
                    <li><a href="create_user.php" style="color: red;">Create User</a></li>
                <?php endif; ?>
            </ul>
