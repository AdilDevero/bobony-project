<?php
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'] ?? '';

// ONLY owners can access the team panel
if ($role !== 'owner') {
    die("<div style='background:#070707;color:red;height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:sans-serif;'><h2>Access Denied</h2><p>You must be an Owner to manage the team.</p><br><a href='dashboard.php' style='color:#ccc;text-decoration:none;border:1px solid #333;padding:10px 20px;border-radius:5px;'>Back to Dashboard</a></div>");
}

$error = '';
$editing = false;
$editData = ['id' => '', 'name' => '', 'role' => 'Admin RP', 'image' => '', 'link1' => '', 'link2' => ''];

$upload_dir = 'uploads/team/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

/* ===========================
   DELETE TEAM MEMBER
=========================== */
if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];

    $getImg = $conn->prepare("SELECT image FROM team_members WHERE id=?");
    $getImg->bind_param("i", $id);
    $getImg->execute();
    $resImg = $getImg->get_result();
    $imgRow = $resImg->fetch_assoc();
    $getImg->close();

    $stmt = $conn->prepare("DELETE FROM team_members WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if (!empty($imgRow['image']) && file_exists($imgRow['image'])) {
            unlink($imgRow['image']);
        }
        $stmt->close();
        header("Location: team_panel.php?msg=deleted");
        exit();
    } else {
        $error = "Delete failed: " . $stmt->error;
    }
}

/* ===========================
   LOAD EDIT DATA
=========================== */
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM team_members WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $editing = true;
        $editData = $row;
    }
    $stmt->close();
}

/* ===========================
   ADD / UPDATE TEAM MEMBER
=========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    $name = trim($_POST['name'] ?? '');
    $team_role = trim($_POST['role'] ?? '');
    $link1 = trim($_POST['link1'] ?? '');
    $link2 = trim($_POST['link2'] ?? '');
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $image = '';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $newname = uniqid('team_') . '.' . $ext;
            $path = $upload_dir . $newname;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
                $image = $path;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format.";
        }
    }

    if ($name === '' || $team_role === '') {
        $error = "Name and Role are required.";
    } else {
        if ($id > 0) {
            if ($image === '') {
                $image = $editData['image'];
            }
            $stmt = $conn->prepare("UPDATE team_members SET name=?, role=?, image=?, link1=?, link2=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $team_role, $image, $link1, $link2, $id);

            if ($stmt->execute()) {
                header("Location: team_panel.php?msg=updated");
                exit();
            } else {
                $error = $stmt->error;
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO team_members (name, role, image, link1, link2) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $name, $team_role, $image, $link1, $link2);

            if ($stmt->execute()) {
                header("Location: team_panel.php?msg=added");
                exit();
            } else {
                $error = $stmt->error;
            }
            $stmt->close();
        }
    }
}

/* ===========================
   FETCH ALL MEMBERS
=========================== */
$members = $conn->query("SELECT * FROM team_members ORDER BY FIELD(role, 'Owner & Developer', 'Owner', 'Admin RP', 'Helper RP'), created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
    <title>Bobony Family - Team Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #070707; color: white; }
        .container { max-width: 1200px; margin: 80px auto; padding: 20px; }
        .card { background: #111; border: 1px solid rgba(255, 0, 0, 0.2); padding: 25px; border-radius: 12px; margin-bottom: 25px; }
        h1 { color: red; margin-bottom: 20px; text-align: center; font-weight: 800; font-size: 32px; }
        h2 { color: white; margin-bottom: 20px; border-bottom: 1px solid rgba(255,0,0,0.3); padding-bottom: 10px; }
        
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        label { display: block; margin-bottom: 5px; color: #ccc; font-size: 14px; }
        input, select { width: 100%; padding: 12px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #333; background: #222; color: #fff; }
        input:focus, select:focus { outline: none; border-color: red; }
        
        .btn { display: inline-block; padding: 12px 20px; background: red; color: #fff; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .btn:hover { background: #ff2a2a; box-shadow: 0 0 10px red; }
        .btn.gray { background: transparent; border: 1px solid #555; color: #ccc; }
        .btn.gray:hover { background: #333; border-color: #fff; color: white; box-shadow: none; }
        .btn.small { padding: 6px 12px; font-size: 13px; }
        .btn i { margin-right: 6px; }

        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
        
        .member-card { text-align: center; position: relative; }
        .member-card img { width: 100px; height: 100px; border-radius: 20px; object-fit: cover; border: 2px solid rgba(255,0,0,0.3); margin-bottom: 15px; }
        .member-card h3 { margin-bottom: 5px; font-size: 18px; }
        .member-card .role { color: red; font-size: 13px; margin-bottom: 15px; font-weight: 600; text-transform: uppercase; }
        
        .actions-box { background: rgba(0,0,0,0.3); padding: 10px; border-radius: 8px; margin-top: 15px; display: flex; justify-content: center; gap: 10px; }
        
        @media(max-width: 600px) {
            .header-actions { flex-direction: column; gap: 15px; }
            .header-actions .btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-actions">
            <h1><i class="fas fa-users-cog"></i> Team Management</h1>
            <a href="dashboard.php" class="btn gray"><i class="fas fa-home"></i> Back to Dashboard</a>
        </div>

        <?php if ($error): ?>
            <div style="background:rgba(255,0,0,0.1); border-left:4px solid red; color:#ff8080; padding:15px; border-radius:6px; margin-bottom:25px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['msg'])): ?>
            <div style="background:rgba(0,255,0,0.1); border-left:4px solid #00ff00; color:#80ff80; padding:15px; border-radius:6px; margin-bottom:25px;">
                <i class="fas fa-check-circle"></i> Action completed successfully.
            </div>
        <?php endif; ?>

        <div class="card">
            <h2><i class="fas <?php echo $editing ? 'fa-edit' : 'fa-plus-circle'; ?>"></i> <?php echo $editing ? 'Edit Team Member' : 'Add New Team Member'; ?></h2>

            <form method="POST" enctype="multipart/form-data">
                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?php echo (int) $editData['id']; ?>">
                <?php endif; ?>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Name</label>
                        <input name="name" placeholder="Member Name" required value="<?php echo htmlspecialchars($editData['name']); ?>">
                    </div>
                    <div>
                        <label>Role</label>
                        <select name="role" required>
                            <?php
                            $roles = ['Owner & Developer', 'Owner', 'Admin RP', 'Helper RP'];
                            foreach($roles as $r) {
                                $sel = ($editData['role'] === $r) ? 'selected' : '';
                                echo "<option value='$r' $sel>$r</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <label>Profile Picture</label>
                <div style="display:flex; align-items:center; gap:15px; margin-bottom:15px;">
                    <input type="file" name="image" accept="image/*" style="margin-bottom:0;">
                    <?php if($editing && $editData['image']): ?>
                        <img src="<?php echo htmlspecialchars($editData['image']); ?>" style="width:50px;height:50px;border-radius:10px;object-fit:cover;">
                    <?php endif; ?>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Social Link 1 (Kick, Twitch, etc.)</label>
                        <input name="link1" placeholder="https://..." value="<?php echo htmlspecialchars($editData['link1']); ?>">
                    </div>
                    <div>
                        <label>Social Link 2 (Instagram, X, etc.)</label>
                        <input name="link2" placeholder="https://..." value="<?php echo htmlspecialchars($editData['link2']); ?>">
                    </div>
                </div>

                <div style="margin-top: 10px; display:flex; gap: 10px;">
                    <button class="btn" type="submit">
                        <i class="fas fa-save"></i> <?php echo $editing ? 'Update Member' : 'Add Member'; ?>
                    </button>
                    <?php if($editing): ?>
                        <a href="team_panel.php" class="btn gray">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <h2 style="margin-top: 40px; margin-bottom: 20px; color:red; border-bottom:1px solid rgba(255,0,0,0.2); padding-bottom:10px;">Current Team Members</h2>
        
        <div class="grid">
            <?php if($members && $members->num_rows > 0): ?>
                <?php while ($row = $members->fetch_assoc()): ?>
                    <div class="card member-card">
                        <?php if ($row['image']): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>">
                        <?php else: ?>
                            <div style="width:100px; height:100px; border-radius:20px; background:#222; margin:0 auto 15px; display:flex; align-items:center; justify-content:center; color:#555;">No Image</div>
                        <?php endif; ?>

                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <div class="role"><?php echo htmlspecialchars($row['role']); ?></div>

                        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:10px;">
                            <?php if ($row['link1']): ?>
                                <a href="<?php echo htmlspecialchars($row['link1']); ?>" target="_blank" class="btn small gray" title="Link 1"><i class="fas fa-link" style="margin:0;"></i></a>
                            <?php endif; ?>
                            <?php if ($row['link2']): ?>
                                <a href="<?php echo htmlspecialchars($row['link2']); ?>" target="_blank" class="btn small gray" title="Link 2"><i class="fas fa-link" style="margin:0;"></i></a>
                            <?php endif; ?>
                        </div>

                        <div class="actions-box">
                            <a href="team_panel.php?action=edit&id=<?php echo (int) $row['id']; ?>" class="btn small" style="background:#00a8ff;"><i class="fas fa-edit"></i> Edit</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?php echo (int) $row['id']; ?>">
                                <button class="btn small" style="background:transparent; border:1px solid red; color:red;" onclick="return confirm('Delete this team member?');"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align:center; padding: 40px; color:#555;">
                    <i class="fas fa-users" style="font-size: 40px; margin-bottom: 15px;"></i>
                    <p>No team members found.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
