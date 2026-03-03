<?php
require 'config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'] ?? '';
$error = '';
$success = '';
$editing = false;
$editData = ['id'=>'', 'name'=>'', 'image'=>'', 'link1'=>'', 'link2'=>''];

$upload_dir = 'uploads/streamers/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

/* ===========================
   DELETE (Redirect to Dashboard)
=========================== */
if (isset($_POST['delete_id'])) {
    if ($role === 'owner' || $role === 'admin') {

        $id = (int)$_POST['delete_id'];

        // get image path to delete file too
        $getImg = $conn->prepare("SELECT image FROM streamers WHERE id=?");
        $getImg->bind_param("i", $id);
        $getImg->execute();
        $resImg = $getImg->get_result();
        $imgRow = $resImg->fetch_assoc();
        $getImg->close();

        $stmt = $conn->prepare("DELETE FROM streamers WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {

                // delete image file from server
                if (!empty($imgRow['image']) && file_exists($imgRow['image'])) {
                    unlink($imgRow['image']);
                }

                $stmt->close();
                header("Location: dashboard.php?msg=streamer_deleted");
                exit();
            } else {
                $error = "Delete failed: " . $stmt->error;
            }
            $stmt->close();
        }

    } else {
        $error = "No permission to delete.";
    }
}

/* ===========================
   LOAD EDIT DATA
=========================== */
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM streamers WHERE id=? LIMIT 1");
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
   ADD / UPDATE STREAMER
=========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {

    if ($role !== 'owner' && $role !== 'admin') {
        $error = "No permission.";
    } else {

        $name = trim($_POST['name'] ?? '');
        $link1 = trim($_POST['link1'] ?? '');
        $link2 = trim($_POST['link2'] ?? '');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        $image = '';

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, ['jpg','jpeg','png','gif'])) {

                $newname = uniqid('str_') . '.' . $ext;
                $path = $upload_dir . $newname;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
                    $image = $path;
                }

            } else {
                $error = "Invalid image format.";
            }
        }

        if ($name === '') {
            $error = "Streamer name required.";
        } else {

            if ($id > 0) {

                if ($image === '') {
                    $image = $editData['image'];
                }

                $stmt = $conn->prepare("UPDATE streamers SET name=?, image=?, link1=?, link2=? WHERE id=?");
                $stmt->bind_param("ssssi", $name, $image, $link1, $link2, $id);

                if ($stmt->execute()) {
                    header("Location: dashboard.php?msg=streamer_updated");
                    exit();
                } else {
                    $error = $stmt->error;
                }

                $stmt->close();

            } else {

                $stmt = $conn->prepare("INSERT INTO streamers (name, image, link1, link2) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss", $name, $image, $link1, $link2);

                if ($stmt->execute()) {
                    header("Location: dashboard.php?msg=streamer_added");
                    exit();
                } else {
                    $error = $stmt->error;
                }

                $stmt->close();
            }
        }
    }
}

/* ===========================
   FETCH ALL STREAMERS
=========================== */
$streamers = $conn->query("SELECT * FROM streamers ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Streamers Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:'Poppins',sans-serif}
body{background:#070707;color:white}
.container{max-width:1000px;margin:120px auto;padding:20px}
.card{background:#111;border:1px solid rgba(255,0,0,0.2);padding:20px;border-radius:12px;margin-bottom:20px}
h1{color:red;margin-bottom:20px}
input{width:100%;padding:10px;border-radius:8px;margin-bottom:10px;border:1px solid #333;background:#222;color:#fff}
.btn{display:inline-block;padding:10px 15px;background:red;color:#fff;border:none;border-radius:8px;cursor:pointer;text-decoration:none}
.btn.gray{background:#333}
.btn.small{padding:6px 10px;font-size:14px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px}
img{max-width:100%;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>

<div class="container">

<h1>Manage Streamers</h1>

<?php if ($error): ?>
<div style="background:#330000;color:#ff8080;padding:10px;border-radius:6px;margin-bottom:15px;">
<?php echo $error; ?>
</div>
<?php endif; ?>

<div class="card">
<h2><?php echo $editing ? 'Edit Streamer' : 'Add Streamer'; ?></h2>

<form method="POST" enctype="multipart/form-data">

<?php if ($editing): ?>
<input type="hidden" name="id" value="<?php echo (int)$editData['id']; ?>">
<?php endif; ?>

<input name="name" placeholder="Streamer Name" required value="<?php echo htmlspecialchars($editData['name']); ?>">
<input type="file" name="image" accept="image/*">
<input name="link1" placeholder="Link 1" value="<?php echo htmlspecialchars($editData['link1']); ?>">
<input name="link2" placeholder="Link 2" value="<?php echo htmlspecialchars($editData['link2']); ?>">

<button class="btn" type="submit">
<?php echo $editing ? 'Update Streamer' : 'Add Streamer'; ?>
</button>

<a href="dashboard.php" class="btn gray">Back to Dashboard</a>

</form>
</div>

<div class="grid">
<?php while ($row = $streamers->fetch_assoc()): ?>
<div class="card">

<?php if ($row['image']): ?>
<img src="<?php echo htmlspecialchars($row['image']); ?>">
<?php endif; ?>

<h3><?php echo htmlspecialchars($row['name']); ?></h3>

<?php if ($row['link1']): ?>
<a href="<?php echo htmlspecialchars($row['link1']); ?>" target="_blank" class="btn small gray">Link 1</a>
<?php endif; ?>

<?php if ($row['link2']): ?>
<a href="<?php echo htmlspecialchars($row['link2']); ?>" target="_blank" class="btn small gray">Link 2</a>
<?php endif; ?>

<?php if ($role === 'owner' || $role === 'admin'): ?>
<div style="margin-top:10px;">
<a href="streamers.php?action=edit&id=<?php echo (int)$row['id']; ?>" class="btn small gray">Edit</a>

<form method="POST" style="display:inline;">
<input type="hidden" name="delete_id" value="<?php echo (int)$row['id']; ?>">
<button class="btn small" onclick="return confirm('Delete this streamer?');">Delete</button>
</form>
</div>
<?php endif; ?>

</div>
<?php endwhile; ?>
</div>

</div>
</body>
</html>