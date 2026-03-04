<?php
require 'config.php';

// Fetch all active bans from database
$bans = [];
$bans_sql = "SELECT * FROM bans WHERE status = 'active' ORDER BY banned_date DESC";
$bans_result = $conn->query($bans_sql);

if ($bans_result && $bans_result->num_rows > 0) {
    while ($row = $bans_result->fetch_assoc()) {
        $bans[] = $row;
    }
}

// Separate bans by type
$permanent_bans = array_filter($bans, function($b) { return $b['is_permanent']; });
$temporary_bans = array_filter($bans, function($b) { return !$b['is_permanent']; });
$blacklisted = array_filter($bans, function($b) { return $b['is_blacklisted']; });

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bobony Family | Banned Players</title>
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
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

        /* GRID BACKGROUND */
        body::before {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(255,0,0,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,0,0,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -2;
        }

        body::after {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 20%, rgba(255,0,0,0.15), transparent 70%);
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

        nav ul li a:hover,
        .active {
            color: red;
        }

        /* HEADER SECTION */
        .header {
            padding: 160px 20px 60px;
            text-align: center;
        }

        .header h1 {
            font-size: 45px;
            font-weight: 800;
        }

        .header span {
            color: red;
        }

        .header p {
            margin-top: 20px;
            color: #aaa;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* CATEGORY HEADERS */
        .category-title {
            padding: 40px 80px 20px;
            font-size: 28px;
            font-weight: 700;
            color: red;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .category-icon {
            font-size: 32px;
        }

        .category-count {
            font-size: 18px;
            color: #888;
            font-weight: 400;
            margin-left: auto;
        }

        /* BANS GRID */
        .bans-container {
            padding: 0 80px 100px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .ban-card {
            background: #111;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid rgba(255,0,0,0.2);
            transition: 0.4s;
            position: relative;
            overflow: hidden;
        }

        .ban-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, red, transparent);
        }

        .ban-card:hover {
            transform: translateY(-10px);
            border-color: red;
            box-shadow: 0 0 30px rgba(255,0,0,0.5);
        }

        .ban-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .ban-avatar {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: linear-gradient(135deg, red, #ff6b6b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .ban-info {
            flex: 1;
        }

        .ban-username {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .discord-id {
            font-size: 12px;
            color: #888;
            font-family: monospace;
        }

        .ban-details {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,0,0,0.1);
            font-size: 14px;
        }

        .detail-label {
            color: #888;
            font-weight: 600;
        }

        .detail-value {
            color: #fff;
            font-weight: 600;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .ban-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }

        .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-permanent {
            background: rgba(255,0,0,0.3);
            color: #ff6b6b;
            border: 1px solid #ff6b6b;
        }

        .badge-blacklist {
            background: rgba(0,0,0,0.5);
            color: #999;
            border: 1px solid #555;
        }

        .badge-active {
            background: rgba(100,255,100,0.2);
            color: #6bff6b;
            border: 1px solid #6bff6b;
        }

        .ban-reason {
            background: rgba(255,0,0,0.1);
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid red;
            font-size: 13px;
            line-height: 1.5;
            margin: 15px 0;
        }

        .ban-reason-label {
            color: #888;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state i {
            font-size: 48px;
            color: #444;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            border-top: 1px solid rgba(255,0,0,0.2);
            margin-top: 80px;
        }

        @media(max-width: 900px) {
            nav {
                padding: 20px;
            }

            nav ul {
                gap: 15px;
            }

            .category-title {
                padding: 30px 20px 15px;
                font-size: 22px;
            }

            .category-count {
                display: none;
            }

            .bans-container {
                padding: 20px;
                gap: 20px;
            }

            .header h1 {
                font-size: 32px;
            }
        }

        @media(max-width: 600px) {
            nav ul {
                display: none;
            }

            .bans-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<nav>
    <div class="logo">Bobony Family</div>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="discord.php">Discord</a></li>
        <li><a href="team.php">Team</a></li>
        <li><a href="bans.php">Bans</a></li>
        <li><a href="REGLEMENTS.php">REGLEMENTS</a></li>
        <li><a href="login.php" style="color: red;">Staff Login</a></li>
        <!-- <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
            <li><a href="create_user.php" style="color: red;">Create User</a></li>
        <?php endif; ?> -->
    </ul>
</nav>

<section class="header">
    <h1>Banned <span>Players</span></h1>
    <p>
        Below is a list of all players who have been banned from Bobony Family Roleplay.
        These bans are enforced across all servers and appeals can be submitted through our Discord.
    </p>
</section>

<?php if (count($permanent_bans) > 0): ?>
    <div class="category-title">
        <span class="category-icon"><i class="fas fa-gavel"></i></span>
        Permanent Bans
        <span class="category-count"><?php echo count($permanent_bans); ?></span>
    </div>
    <section class="bans-container">
        <?php foreach ($permanent_bans as $ban): ?>
            <div class="ban-card">
                <div class="ban-header">
                    <div class="ban-avatar">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="ban-info">
                        <div class="ban-username"><?php echo htmlspecialchars($ban['discord_username']); ?></div>
                        <div class="discord-id"><?php echo htmlspecialchars($ban['discord_id']); ?></div>
                    </div>
                </div>

                <div class="ban-badges">
                    <span class="badge badge-permanent"><i class="fas fa-infinity"></i> Permanent</span>
                    <?php if ($ban['is_blacklisted']): ?>
                        <span class="badge badge-blacklist"><i class="fas fa-list"></i> Blacklisted</span>
                    <?php endif; ?>
                    <span class="badge badge-active"><i class="fas fa-circle-dot"></i> Active</span>
                </div>

                <div class="ban-reason">
                    <div class="ban-reason-label">Reason</div>
                    <?php echo htmlspecialchars($ban['ban_reason']); ?>
                </div>

                <div class="ban-details">
                    <div class="detail-row">
                        <span class="detail-label">Ban Count</span>
                        <span class="detail-value"><?php echo $ban['ban_count']; ?>x</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Banned Date</span>
                        <span class="detail-value"><?php echo date('M d, Y', strtotime($ban['banned_date'])); ?></span>
                    </div>
                    <?php if ($ban['notes']): ?>
                        <div style="padding: 10px 0; border-bottom: 1px solid rgba(255,0,0,0.1);">
                            <span class="detail-label" style="display: block; margin-bottom: 8px;">Notes</span>
                            <div style="font-size: 13px; color: #aaa;">
                                <?php echo htmlspecialchars($ban['notes']); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php if (count($temporary_bans) > 0): ?>
    <div class="category-title">
        <span class="category-icon"><i class="fas fa-hourglass-end"></i></span>
        Temporary Bans
        <span class="category-count"><?php echo count($temporary_bans); ?></span>
    </div>
    <section class="bans-container">
        <?php foreach ($temporary_bans as $ban): ?>
            <div class="ban-card">
                <div class="ban-header">
                    <div class="ban-avatar">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="ban-info">
                        <div class="ban-username"><?php echo htmlspecialchars($ban['discord_username']); ?></div>
                        <div class="discord-id"><?php echo htmlspecialchars($ban['discord_id']); ?></div>
                    </div>
                </div>

                <div class="ban-badges">
                    <span class="badge badge-permanent"><?php echo $ban['ban_duration']; ?> Hours</span>
                    <?php if ($ban['is_blacklisted']): ?>
                        <span class="badge badge-blacklist"><i class="fas fa-list"></i> Blacklisted</span>
                    <?php endif; ?>
                    <span class="badge badge-active"><i class="fas fa-circle-dot"></i> Active</span>
                </div>

                <div class="ban-reason">
                    <div class="ban-reason-label">Reason</div>
                    <?php echo htmlspecialchars($ban['ban_reason']); ?>
                </div>

                <div class="ban-details">
                    <div class="detail-row">
                        <span class="detail-label">Ban Count</span>
                        <span class="detail-value"><?php echo $ban['ban_count']; ?>x</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Banned Date</span>
                        <span class="detail-value"><?php echo date('M d, Y', strtotime($ban['banned_date'])); ?></span>
                    </div>
                    <?php if ($ban['unban_date']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Unban Date</span>
                            <span class="detail-value"><?php echo date('M d, Y H:i', strtotime($ban['unban_date'])); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($ban['notes']): ?>
                        <div style="padding: 10px 0; border-bottom: 1px solid rgba(255,0,0,0.1);">
                            <span class="detail-label" style="display: block; margin-bottom: 8px;">Notes</span>
                            <div style="font-size: 13px; color: #aaa;">
                                <?php echo htmlspecialchars($ban['notes']); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php if (count($bans) == 0): ?>
    <section class="bans-container">
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Active Bans</h3>
            <p>Great news! There are currently no active bans.</p>
        </div>
    </section>
<?php endif; ?>

<div class="footer">
    © 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</div>

</body>
</html>
