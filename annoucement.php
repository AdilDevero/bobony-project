<?php
require 'config.php';

// Fetch announcements
$announcements = [];
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bobony Family | Announcements</title>

<link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#070707;
color:white;
overflow-x:hidden;
}

/* GRID BACKGROUND */

body::before{
content:"";
position:fixed;
width:100%;
height:100%;
background-image:
linear-gradient(rgba(255,0,0,0.06) 1px, transparent 1px),
linear-gradient(90deg, rgba(255,0,0,0.06) 1px, transparent 1px);
background-size:40px 40px;
z-index:-2;
}

body::after{
content:"";
position:fixed;
width:100%;
height:100%;
background:radial-gradient(circle at 50% 20%, rgba(255,0,0,0.15), transparent 70%);
z-index:-1;
}

/* NAVBAR */

nav{
position:fixed;
width:100%;
padding:20px 80px;
display:flex;
justify-content:space-between;
align-items:center;
background:rgba(0,0,0,0.8);
backdrop-filter:blur(10px);
border-bottom:1px solid rgba(255,0,0,0.3);
z-index:1000;
}

.logo{
font-weight:800;
font-size:22px;
color:red;
letter-spacing:2px;
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

/* HEADER */

.header{
padding:160px 20px 60px;
text-align:center;
}

.header h1{
font-size:45px;
font-weight:800;
}

.header span{
color:red;
}

.header p{
margin-top:20px;
color:#aaa;
max-width:700px;
margin-left:auto;
margin-right:auto;
}

/* ANNOUNCEMENTS */

.announcements-container{
padding:0 80px 100px;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
gap:40px;
}

.announcement-card{
background:#111;
padding:30px;
border-radius:15px;
border:1px solid rgba(255,0,0,0.2);
transition:0.4s;
position:relative;
overflow:hidden;
}

.announcement-card::before{
content:"";
position:absolute;
top:0;
left:0;
right:0;
height:4px;
background:linear-gradient(90deg,red,transparent);
}

.announcement-card:hover{
transform:translateY(-10px);
border-color:red;
box-shadow:0 0 30px rgba(255,0,0,0.4);
}

.announcement-title{
font-size:20px;
font-weight:700;
margin-bottom:10px;
color:white;
}

.announcement-date{
font-size:12px;
color:#888;
margin-bottom:15px;
}

.announcement-details{
font-size:14px;
color:#ccc;
line-height:1.6;
}

.empty{
grid-column:1/-1;
text-align:center;
padding:80px;
color:#777;
}

.empty i{
font-size:50px;
margin-bottom:20px;
color:#444;
}

/* FOOTER */

.footer{
text-align:center;
padding:40px 20px;
color:#666;
border-top:1px solid rgba(255,0,0,0.2);
margin-top:80px;
}

@media(max-width:900px){

nav{
padding:20px;
}

.announcements-container{
padding:20px;
}

.header h1{
font-size:32px;
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
<li><a href="annoucement.php">Announcements</a></li>
<li><a href="REGLEMENTS.php">Reglements</a></li>
<li><a href="login.php" style="color:red;">Staff Login</a></li>
</ul>
</nav>

<section class="header">
<h1>Server <span>Announcements</span></h1>

<p>
Stay updated with the latest news, server updates, events and important
information from the Bobony Roleplay staff team.
</p>
</section>

<section class="announcements-container">

<?php if(count($announcements) > 0): ?>

<?php foreach($announcements as $a): ?>

<div class="announcement-card">

<div class="announcement-title">
<i class="fas fa-bullhorn" style="color:red;margin-right:8px;"></i>
<?php echo htmlspecialchars($a['subject']); ?>
</div>

<div class="announcement-date">
<i class="fas fa-calendar"></i>
<?php echo date("M d, Y H:i", strtotime($a['created_at'])); ?>
</div>

<div class="announcement-details">
<?php echo nl2br(htmlspecialchars($a['details'])); ?>
</div>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="empty">
<i class="fas fa-inbox"></i>
<h3>No announcements yet</h3>
<p>Server announcements will appear here.</p>
</div>

<?php endif; ?>

</section>

<div class="footer">
© 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</div>

</body>
</html>