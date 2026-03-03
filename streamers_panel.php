<?php
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'] ?? '';
$error = '';
$success = '';
$editing = false;
$editData = ['id'=>'', 'name'=>'', 'profile'=>'', 'link1'=>'', 'link2'=>''];

// handle delete action via GET
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if ($role === 'owner' || $role === 'admin') {
        $id = (int)$_GET['id'];
        $del = $conn->prepare("DELETE FROM streamers WHERE id = ?");
        if ($del) {
            $del->bind_param('i', $id);
            if ($del->execute()) {
                $success = 'Streamer removed.';
            } else {
                $error = 'Database error: ' . $del->error;
            }
            $del->close();
        } else {
            $error = 'Database error: ' . $conn->error;
        }
    } else {
        $error = 'You do not have permission to delete streamers.';
    }
}

// if edit requested, load data for form
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $res = $conn->query("SELECT * FROM streamers WHERE id = $id LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $editing = true;
        $editData = $row;
    }
}

// Handle form submission for add/update streamer (allowed for owner/admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($role !== 'owner' && $role !== 'admin') {
        $error = 'You do not have permission to manage streamers.';
    } else {
        $name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
        $profile = $conn->real_escape_string(trim($_POST['profile'] ?? ''));
        $link1 = $conn->real_escape_string(trim($_POST['link1'] ?? ''));
        $link2 = $conn->real_escape_string(trim($_POST['link2'] ?? ''));
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($name === '') {
            $error = 'Streamer name is required.';
        } else {
            if ($id > 0) {
                // update existing
                $stmt = $conn->prepare("UPDATE streamers SET name=?, profile=?, link1=?, link2=? WHERE id=?");
                if ($stmt) {
                    $stmt->bind_param('ssssi', $name, $profile, $link1, $link2, $id);
                    if ($stmt->execute()) {
                        $success = 'Streamer updated.';
                    } else {
                        $error = 'Database error: ' . $stmt->error;
                    }
                    $stmt->close();
                    // clear editing state
                    $editing = false;
                    $editData = ['id'=>'', 'name'=>'', 'profile'=>'', 'link1'=>'', 'link2'=>''];
                } else {
                    $error = 'Database error: ' . $conn->error;
                }
            } else {
                // insert new
                $stmt = $conn->prepare("INSERT INTO streamers (name, profile, link1, link2) VALUES (?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param('ssss', $name, $profile, $link1, $link2);
                    if ($stmt->execute()) {
                        $success = 'Streamer added.';
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
}

// Fetch existing streamers
$streamers = $conn->query("SELECT * FROM streamers ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Streamers — Bobony Family</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        *{box-sizing:border-box;margin:0;padding:0;font-family:'Poppins',sans-serif}
        body{background:#070707;color:white;overflow-x:hidden}
        .container{max-width:1000px;margin:120px auto;padding:20px}
        .card{background:#111;border:1px solid rgba(255,0,0,0.2);padding:20px;border-radius:12px;margin-bottom:20px}
        h1{color:red;margin-bottom:20px}
        form .form-group{margin-bottom:15px}
        label{display:block;margin-bottom:6px;color:#ccc;font-weight:600}
        input,textarea{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,0,0,0.15);background:rgba(255,255,255,0.03);color:#fff}
        .btn{display:inline-block;padding:10px 16px;background:red;color:#fff;border-radius:8px;border:none;cursor:pointer;margin-top:10px}
        .link-buttons a{display:inline-block;margin-right:10px;padding:8px 12px;background:#222;color:#fff;border-radius:6px;text-decoration:none;transition:0.2s}
        .link-buttons a:hover{background:red}
        .message{padding:12px;border-radius:8px;margin-bottom:12px}
        .error{background:rgba(255,0,0,0.08);color:#ff7b7b;border-left:4px solid #ff0000}
        .success{background:rgba(0,255,0,0.04);color:#7bff9a;border-left:4px solid #00ff00}
        .streamers-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px}
    </style>
</head>
<body>
<div class="container">
    <h1>Streamers</h1>
    <?php if ($error): ?><div class="message error"><?php echo $error; ?></div><?php endif; ?>
    <?php if ($success): ?><div class="message success"><?php echo $success; ?></div><?php endif; ?>

    <?php if ($role === 'owner' || $role === 'admin'): ?>
    <div class="card">
        <h2><?php echo $editing ? 'Edit Streamer' : 'Add New Streamer'; ?></h2>
        <form method="POST">
            <?php if ($editing): ?>
                <input type="hidden" name="id" value="<?php echo (int)$editData['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" required value="<?php echo htmlspecialchars($editData['name']); ?>">
            </div>
            <div class="form-group">
                <label for="profile">Profile (text/description)</label>
                <textarea id="profile" name="profile" rows="3"><?php echo htmlspecialchars($editData['profile']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="link1">Link 1</label>
                <input id="link1" name="link1" placeholder="https://" value="<?php echo htmlspecialchars($editData['link1']); ?>">
            </div>
            <div class="form-group">
                <label for="link2">Link 2</label>
                <input id="link2" name="link2" placeholder="https://" value="<?php echo htmlspecialchars($editData['link2']); ?>">
            </div>
            <button class="btn" type="submit"><?php echo $editing ? 'Update Streamer' : 'Add Streamer'; ?></button>
            <?php if ($editing): ?>
                <a href="streamers.php" class="btn" style="background:#555;margin-left:10px;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>

    <div class="streamers-grid">
        <?php while ($row = $streamers->fetch_assoc()): ?>
        <div class="card">
            <h2><?php echo htmlspecialchars($row['name']); ?></h2>
            <?php if ($row['profile']): ?><p><?php echo nl2br(htmlspecialchars($row['profile'])); ?></p><?php endif; ?>
            <div class="link-buttons">
                <?php if ($row['link1']): ?><a href="<?php echo htmlspecialchars($row['link1']); ?>" target="_blank">Link 1</a><?php endif; ?>
                <?php if ($row['link2']): ?><a href="<?php echo htmlspecialchars($row['link2']); ?>" target="_blank">Link 2</a><?php endif; ?>
            </div>
            <?php if ($role === 'owner' || $role === 'admin'): ?>
            <div style="margin-top:10px;">
                <a href="streamers.php?action=edit&id=<?php echo (int)$row['id']; ?>" class="btn" style="background:#555;">Edit</a>
                <a href="streamers.php?action=delete&id=<?php echo (int)$row['id']; ?>" class="btn" style="background:#888;" onclick="return confirm('Are you sure you want to delete this streamer?');">Delete</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>