<?php
include "config.php";

if(isset($_POST['submit'])){

$subject = $_POST['subject'];
$details = $_POST['details'];

$sql = "INSERT INTO announcements (subject,details) VALUES ('$subject','$details')";
$conn->query($sql);

header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Bobony Family annoucements</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
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
linear-gradient(rgba(255,0,0,0.07) 1px, transparent 1px),
linear-gradient(90deg, rgba(255,0,0,0.07) 1px, transparent 1px);
background-size:40px 40px;
z-index:-2;
}

body::after{
content:"";
position:fixed;
width:100%;
height:100%;
background:radial-gradient(circle at 50% 30%, rgba(255,0,0,0.15), transparent 70%);
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

/* DASHBOARD */

.dashboard{
padding:140px 80px;
display:grid;
grid-template-columns:1fr 1fr;
gap:60px;
}

/* FORM */

.form-box{
background:#111;
padding:40px;
border-radius:12px;
box-shadow:0 0 30px rgba(255,0,0,0.2);
}

.form-box h2{
color:red;
margin-bottom:20px;
}

input,textarea{
width:100%;
padding:12px;
margin-bottom:15px;
background:#1a1a1a;
border:1px solid rgba(255,0,0,0.4);
color:white;
border-radius:6px;
}

button{
background:red;
border:none;
padding:12px 25px;
color:white;
font-weight:600;
cursor:pointer;
border-radius:6px;
transition:0.3s;
}

button:hover{
background:#ff2a2a;
box-shadow:0 0 10px red;
}

/* ANNOUNCEMENT LIST */

.announcements{
background:#111;
padding:40px;
border-radius:12px;
box-shadow:0 0 30px rgba(255,0,0,0.2);
}

.announcements h2{
color:red;
margin-bottom:30px;
}

.timeline{
border-left:2px solid red;
padding-left:20px;
}

.timeline-item{
margin-bottom:35px;
position:relative;
}

.timeline-item::before{
content:"";
position:absolute;
left:-11px;
top:5px;
width:15px;
height:15px;
background:red;
border-radius:50%;
box-shadow:0 0 10px red;
}

.timeline-item span{
font-size:13px;
color:#888;
}

.timeline-item h3{
margin:5px 0;
}

.timeline-item p{
color:#bbb;
font-size:14px;
}

/* FOOTER */

footer{
text-align:center;
padding:25px;
background:#111;
border-top:2px solid red;
margin-top:60px;
}

/* MOBILE */

@media(max-width:1000px){

.dashboard{
grid-template-columns:1fr;
}

nav{
padding:20px 40px;
}

}

</style>
</head>

<body>

<nav>

<div class="logo">BOBONY FAMILY</div>

<ul>
<!-- <li><a href="index.php">Home</a></li>
<li><a href="rules.html">Rules</a></li>
<li><a href="discord.html">Discord</a></li> -->
<li><a href="dashboard.php">Dashboard</a></li>
</ul>

</nav>

<div class="dashboard">

<!-- FORM -->

<div class="form-box">

<h2>Create Announcement</h2>

<form method="POST">

<input type="text" name="subject" placeholder="Announcement Subject" required>

<textarea name="details" rows="6" placeholder="Announcement Details" required></textarea>

<button type="submit" name="submit">Publish Announcement</button>

</form>

</div>

<!-- ANNOUNCEMENTS -->

<div class="announcements">

<h2>Latest Announcements</h2>

<div class="timeline">

<?php

$result = $conn->query("SELECT * FROM announcements ORDER BY id DESC");

while($row = $result->fetch_assoc()){

echo "<div class='timeline-item'>";
echo "<span>".$row['created_at']."</span>";
echo "<h3>".$row['subject']."</h3>";
echo "<p>".$row['details']."</p>";
echo "</div>";

}

?>

</div>

</div>

</div>

<footer>

© 2026 Bobony Family Roleplay

</footer>

</body>
</html>