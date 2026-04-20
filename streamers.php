<?php
require 'config.php';

// public-facing streamer list
$streamers = $conn->query("SELECT * FROM streamers ORDER BY created_at DESC");

/* ✅ FIXED: function declared OUTSIDE the loop */
if (!function_exists('icon_for_url')) {
    function icon_for_url($url) {
        if (strpos($url, 'kick.com') !== false) return 'fa-brands fa-kickstarter';
        if (strpos($url, 'instagram.com') !== false) return 'fa-brands fa-instagram';
        if (strpos($url, 'twitch.tv') !== false) return 'fa-brands fa-twitch';
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) return 'fa-brands fa-youtube';
        return 'fa-solid fa-link';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bobony Family | Streamers</title>
<link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
nav ul li a:hover,
.active{
    color:red;
}

.menu-toggle {
    display: none;
    font-size: 24px;
    cursor: pointer;
    color: white;
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}
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
.team-container{
    padding:0 80px 100px;
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(250px,1fr));
    gap:40px;
}
.card{
    background:#111;
    padding:30px;
    border-radius:15px;
    text-align:center;
    border:1px solid rgba(255,0,0,0.2);
    transition:0.4s;
}
.card:hover{
    transform:translateY(-10px);
    border-color:red;
    box-shadow:0 0 30px rgba(255,0,0,0.5);
}
.card img{
    width:100px;
    height:100px;
    border-radius:20px;
    margin-bottom:20px;
    object-fit:cover;
}
.card h3{
    margin-bottom:5px;
}
.role{
    color:red;
    font-size:14px;
    margin-bottom:20px;
}
.socials{
    display:flex;
    justify-content:center;
    gap:15px;
}
.socials a{
    width:35px;
    height:35px;
    border-radius:50%;
    border:1px solid red;
    display:flex;
    justify-content:center;
    align-items:center;
    text-decoration:none;
    color:red;
    transition:0.3s;
    font-size:14px;
}
.socials a:hover{
    background:red;
    color:black;
}
@media(max-width:900px){
    nav{
        padding: 15px 20px;
    }

    .menu-toggle {
        display: block;
        z-index: 1002;
    }

    nav ul{
        position: fixed;
        top: 0;
        left: -100%;
        width: 300px;
        max-width: 85%;
        height: 100vh;
        background: rgba(10, 10, 10, 0.95);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 30px;
        padding: 90px 30px 40px;
        transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-right: 1px solid rgba(255,0,0,0.2);
        box-shadow: 5px 0 25px rgba(0,0,0,0.9);
        z-index: 1001;
    }

    nav ul.active {
        left: 0;
    }

    nav ul li a {
        font-size: 18px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .overlay {
        transition: opacity 0.4s ease;
        opacity: 0;
        pointer-events: none;
    }

    .overlay.active {
        display: block;
        opacity: 1;
        pointer-events: all;
    }

    .team-container{padding:40px 20px 100px; gap: 20px;}
    .header h1{font-size:30px;}
}

@media(max-width:600px){
    .logo{font-size:18px;}
    .header{padding:120px 20px 40px;}
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

.footer{
    text-align:center;
    padding:40px 20px;
    color:#666;
    border-top:1px solid rgba(255,0,0,0.2);
    margin-top:80px;
}
</style>
</head>

<body>

<nav>
    <div class="logo">Bobony Family</div>
    <div class="menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </div>
    <ul id="menu">
<li><a href="index.php">Home</a></li>
<li><a href="discord.php">Discord</a></li>
<li><a href="team.php">Team</a></li>
<li><a href="bans.php">Bans</a></li>
<li><a href="annoucement.php">Announcements</a></li>
<li><a href="REGLEMENTS.php">Reglements</a></li>
<li><a href="streamers.php">Streamers</a></li>
<li><a href="login.php" style="color:red;">Staff Login</a></li>
    </ul>
</nav>

<div class="overlay" onclick="toggleMenu()"></div>

<section class="header">
    <h1>Our <span>Streamers</span></h1>
    <p>Check out the streamers supported by Bobony Family. Click the links to visit their channels.</p>
</section>

<section class="team-container">
<?php if ($streamers && $streamers->num_rows > 0): ?>
    <?php while ($row = $streamers->fetch_assoc()): ?>
        <div class="card">
            <?php if (!empty($row['image'])): ?>
                <img src="<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
            <?php endif; ?>

            <h3><?= htmlspecialchars($row['name']); ?></h3>
            <div class="role">Streamer</div>

            <div class="socials">
                <?php if (!empty($row['link1'])): ?>
                    <a href="<?= htmlspecialchars($row['link1']); ?>" target="_blank">
                        <i class="<?= icon_for_url($row['link1']); ?>"></i>
                    </a>
                <?php endif; ?>

                <?php if (!empty($row['link2'])): ?>
                    <a href="<?= htmlspecialchars($row['link2']); ?>" target="_blank">
                        <i class="<?= icon_for_url($row['link2']); ?>"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="empty">
        <i class="fas fa-inbox"></i>
        <h3>No streamers yet</h3>
        <p>Our streamers list will appear here.</p>
    </div>
<?php endif; ?>
</section>

<div class="footer">
© 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</div>
<script src="animations.js"></script>
<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        const overlay = document.querySelector(".overlay");
        const icon = document.querySelector(".menu-toggle i");
        
        menu.classList.toggle("active");
        overlay.classList.toggle("active");

        if (menu.classList.contains("active")) {
            icon.classList.remove("fa-bars");
            icon.classList.add("fa-xmark");
        } else {
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars");
        }
    }
</script>
</body>
</html>