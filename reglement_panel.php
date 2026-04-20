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

// Add Reglement
if(isset($_POST['submit'])){
    $category = $conn->real_escape_string($_POST['category']);
    $rule_text = $conn->real_escape_string($_POST['rule_text']);
    $ban_time = $conn->real_escape_string($_POST['ban_time']);
    
    $sql = "INSERT INTO reglements (category, rule_text, ban_time) VALUES ('$category','$rule_text', '$ban_time')";
    $conn->query($sql);
    
    header("Location: reglement_panel.php");
    exit();
}

// Delete Reglement
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM reglements WHERE id = $id");
    header("Location: reglement_panel.php");
    exit();
}

// Update Reglement
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $category = $conn->real_escape_string($_POST['category']);
    $rule_text = $conn->real_escape_string($_POST['rule_text']);
    $ban_time = $conn->real_escape_string($_POST['ban_time']);
    
    $conn->query("UPDATE reglements SET category='$category', rule_text='$rule_text', ban_time='$ban_time' WHERE id=$id");
    header("Location: reglement_panel.php");
    exit();
}

// Handle edit view
$edit_mode = false;
$edit_data = null;
if(isset($_GET['edit'])){
    $edit_mode = true;
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM reglements WHERE id = $id");
    if($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    } else {
        $edit_mode = false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bobony Family - Manage Reglements</title>
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

        /* SIDEBAR */
        .sidebar {
            position: fixed; left: 0; top: 0; width: 240px; height: 100%;
            background: #0f0f0f; border-right: 1px solid rgba(255,0,0,0.08);
            padding: 20px 16px; z-index: 900; display: flex; flex-direction: column; gap: 18px;
        }
        .sidebar .slogo { font-weight: 800; color: red; font-size: 20px; letter-spacing: 1px; }
        .sidebar .greet { color: #ccc; font-size: 13px; }
        .side-btn { 
            display: flex; align-items: center; gap: 12px; padding: 12px 14px; 
            border-radius: 10px; text-decoration: none; color: #fff; background: transparent; 
            border: 1px solid rgba(255,255,255,0.02);
            transition: 0.3s;
        }
        .side-btn i { width: 26px; text-align: center; color: red; }
        .side-btn span { font-weight: 600; }
        .side-btn:hover, .side-btn.active {
            background: rgba(255,0,0,0.08);
            border-color: rgba(255,0,0,0.18);
            transform: translateY(-2px);
        }
        .side-btn.primary { background: linear-gradient(90deg, rgba(255,0,0,0.12), transparent); border-color: rgba(255,0,0,0.18); }
        .sidebar.collapsed { transform: translateX(-260px); }

        .sidebar-toggle {
            width: 44px; height: 44px; border-radius: 8px; display: flex; align-items: center;
            justify-content: center; background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.03); color: #fff; cursor: pointer; margin-right: 12px;
        }
        .sidebar-toggle:hover { background: rgba(255,0,0,0.06); border-color: rgba(255,0,0,0.12); }
        
        .container {
            padding: 120px 40px 80px;
            margin-left: 260px;
            transition: margin-left .25s ease;
            max-width: 1400px;
        }
        .sidebar.collapsed ~ .container { margin-left: 0; }

        .dashboard {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
        }

        /* FORM */
        .form-box, .list-box {
            background: #111;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(255,0,0,0.2);
            border: 1px solid rgba(255,0,0,0.2);
        }

        .form-box h2, .list-box h2 {
            color: red;
            margin-bottom: 20px;
            border-bottom: 2px solid rgba(255,0,0,0.3);
            padding-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #ccc;
            font-size: 14px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            background: #1a1a1a;
            border: 1px solid rgba(255,0,0,0.4);
            color: white;
            border-radius: 6px;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: red;
        }

        button.action-btn {
            background: red;
            border: none;
            padding: 12px 25px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border-radius: 6px;
            transition: 0.3s;
            width: 100%;
        }

        button.action-btn:hover {
            background: #ff2a2a;
            box-shadow: 0 0 10px red;
        }

        .cancel-btn {
            display: inline-block;
            text-align: center;
            background: transparent;
            border: 1px solid #555;
            padding: 12px 25px;
            color: #ccc;
            font-weight: 600;
            cursor: pointer;
            border-radius: 6px;
            transition: 0.3s;
            text-decoration: none;
            width: 100%;
            margin-top: 10px;
        }

        .cancel-btn:hover {
            border-color: white;
            color: white;
            background: #333;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid rgba(255,0,0,0.1);
        }

        th {
            color: red;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }

        td {
            color: #ccc;
            font-size: 14px;
        }

        tr:hover td {
            background: rgba(255,0,0,0.05);
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.3s;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-edit {
            background: transparent;
            border: 1px solid #00a8ff;
            color: #00a8ff;
        }
        .btn-edit:hover {
            background: #00a8ff;
            color: white;
            box-shadow: 0 0 10px #00a8ff;
        }

        .btn-delete {
            background: transparent;
            border: 1px solid red;
            color: red;
        }
        .btn-delete:hover {
            background: red;
            color: white;
            box-shadow: 0 0 10px red;
        }

        .category-badge {
            background: rgba(255,255,255,0.1);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .ban-badge {
            background: rgba(255,0,0,0.15);
            color: #ff6b6b;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid rgba(255,0,0,0.3);
            font-size: 12px;
            font-weight: bold;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 25px;
            background: #111;
            border-top: 2px solid red;
            margin-top: 60px;
        }

        /* MOBILE */
        @media(max-width: 1000px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            nav { padding: 20px 40px; }
            .container { padding: 140px 20px 80px; margin-left: 0; }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <br><br><br> 
    <a href="ban_management.php" class="side-btn"><i class="fas fa-ban"></i><span>Ban Management</span></a>
    <a href="streamers_panel.php" class="side-btn"><i class="fas fa-video"></i><span>Manage Streamers</span></a>
    <a href="announcement_panel.php" class="side-btn"><i class="fas fa-bullhorn"></i><span>Manage Announcements</span></a>
    <a href="reglement_panel.php" class="side-btn active"><i class="fas fa-user-plus"></i><span>Reglement</span></a>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
        <a href="users_manager.php" class="side-btn"><i class="fas fa-users"></i><span>Users Manager</span></a>
        <a href="create_user.php" class="side-btn"><i class="fas fa-user-plus"></i><span>Create User</span></a>
    <?php endif; ?>
    <div style="flex:1"></div>
    <a href="dashboard.php" class="side-btn"><i class="fas fa-home"></i><span>Dashboard</span></a>
</aside>

<nav>
    <div style="display:flex;align-items:center;gap:12px">
        <button id="sidebarToggle" class="sidebar-toggle" title="Toggle menu"><i id="sidebarToggleIcon" class="fas fa-angle-left"></i></button>
        <div class="logo">BOBONY FAMILY</div>
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
    <div class="dashboard">

        <!-- FORM -->
        <div class="form-box">
            <h2><?php echo $edit_mode ? 'Edit Reglement' : 'Add New Reglement'; ?></h2>
            
            <form method="POST">
                <?php if($edit_mode): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <label>Category</label>
                <select name="category" required>
                    <?php 
                    $categories = [
                        'General Rules', 
                        'Illegal Rules', 
                        'Streamer Rules', 
                        'Heist / Braquages Rules', 
                        'Blacklisted Words',
                        'New Rules'
                    ];
                    $current_cat = $edit_mode ? $edit_data['category'] : '';
                    foreach($categories as $cat) {
                        $selected = ($current_cat == $cat) ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>$cat</option>";
                    }
                    ?>
                </select>

                <label>Rule Description / Details</label>
                <textarea name="rule_text" rows="5" placeholder="E.g., TOXIC RP: Any toxic behavior affecting the player..." required><?php echo $edit_mode ? htmlspecialchars($edit_data['rule_text']) : ''; ?></textarea>

                <label>Ban Time / Penalty</label>
                <input type="text" name="ban_time" placeholder="E.g., 2 to 4 Days, PERMA BAN, Warning..." value="<?php echo $edit_mode ? htmlspecialchars($edit_data['ban_time']) : ''; ?>" required>

                <?php if($edit_mode): ?>
                    <button type="submit" name="update" class="action-btn">Update Rule</button>
                    <a href="reglement_panel.php" class="cancel-btn">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="submit" class="action-btn">Add Rule</button>
                <?php endif; ?>
            </form>
        </div>

        <!-- REGLEMENT LIST -->
        <div class="list-box">
            <h2>Current Rules</h2>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Rule Details</th>
                            <th>Ban Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM reglements ORDER BY category, id DESC");
                        if($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td><span class='category-badge'>".htmlspecialchars($row['category'])."</span></td>";
                                echo "<td>".htmlspecialchars($row['rule_text'])."</td>";
                                echo "<td><span class='ban-badge'>".htmlspecialchars($row['ban_time'])."</span></td>";
                                echo "<td style='white-space: nowrap;'>";
                                echo "<a href='reglement_panel.php?edit=".$row['id']."' class='btn btn-edit'><i class='fas fa-edit'></i></a>";
                                echo "<a href='reglement_panel.php?delete=".$row['id']."' class='btn btn-delete' onclick=\"return confirm('Are you sure you want to delete this rule?');\"><i class='fas fa-trash'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center;'>No rules found. Add one on the left.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<footer>
    © 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</footer>

<script>
    // sidebar small-screen toggle
    (function(){
        var sidebar = document.getElementById('sidebar');
        var logo = document.querySelector('nav .logo');
        if(sidebar && logo){
            logo.style.cursor = 'pointer';
            logo.addEventListener('click', function(){
                if(window.innerWidth < 1000){
                    sidebar.style.transform = sidebar.style.transform === 'translateX(0px)' ? 'translateX(-260px)' : 'translateX(0)';
                }
            });
        }
    })();

    // sidebar toggle button
    (function(){
        var sidebar = document.getElementById('sidebar');
        var btn = document.getElementById('sidebarToggle');
        var icon = document.getElementById('sidebarToggleIcon');
        if(!sidebar || !btn) return;
        
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

</body>
</html>
