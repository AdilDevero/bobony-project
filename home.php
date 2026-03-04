<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bobony Family Studios</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">
<link rel="apple-touch-icon" href="favicon.png">
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

/* HERO LAYOUT */

.hero{
    padding:150px 80px 80px;
    display:grid;
    grid-template-columns:1.3fr 1fr;
    gap:80px;
}

/* VIDEO SECTION */

.video-box{
    background:#111;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 0 40px rgba(255,0,0,0.3);
    transition:0.4s;
}

.video-box:hover{
    transform:scale(1.02);
    box-shadow:0 0 60px rgba(255,0,0,0.5);
}

.video-box iframe{
    width:100%;
    height:450px;
}

/* JOURNEY SECTION */

.journey h2{
    font-size:28px;
    margin-bottom:40px;
    color:red;
}

.timeline{
    border-left:2px solid red;
    padding-left:20px;
}

.timeline-item{
    margin-bottom:40px;
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
    box-shadow:0 0 15px red;
}

.timeline-item span{
    font-size:14px;
    color:#999;
}

.timeline-item h3{
    margin:5px 0;
    font-size:18px;
}

.timeline-item p{
    font-size:14px;
    color:#bbb;
}
footer {
            text-align: center;
            padding: 25px;
            background: #111;
            border-top: 2px solid #ff0000;
            margin-top: 80px;
        }

/* RESPONSIVE */

@media(max-width:1000px){
    .hero{
        grid-template-columns:1fr;
    }
    .video-box iframe{
        height:300px;
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
        <li><a href="streamers.php">Streamers</a></li>
        <li><a href="login.php" style="color: red;">Staff Login</a></li>
        <!-- <li><a href="#">Careers</a></li>
        <li><a href="#">Store</a></li>
        <li><a href="#">Forum</a></li>
        <li><a href="#">Docs</a></li> -->
    </ul>
</nav>

<section class="hero">

    <!-- LEFT SIDE VIDEO -->
    <div class="video-box">
        <iframe src="https://www.youtube.com/embed/MhmrK_a-zms"
        frameborder="0"
        allowfullscreen></iframe>
    </div>

    <!-- RIGHT SIDE JOURNEY -->
    <div class="journey">
        <h2>THE JOURNEY</h2>

        <div class="timeline">

            <div class="timeline-item">
                <span> 2019</span>
                <h3>Bobony Family 1.0</h3>
                <p>The foundation of our immersive roleplay community began.</p>
            </div>

            <div class="timeline-item">
                <span> 2024</span>
                <h3>Bobony Family 2.0</h3>
                <p>Advanced scripts, improved economy and new systems introduced.</p>
            </div>

            <div class="timeline-item">
                <span> 2025</span>
                <h3>Bobony Family 3.0</h3>
                <p>Major expansion with high-quality RP mechanics and active content creators.</p>
            </div>

            <div class="timeline-item">
                <span> 2026 - Today</span>
                <h3>Bobony Family V</h3>
                <p>Next generation roleplay experience built for serious players.</p>
            </div>

        </div>
    </div>

</section>
<footer>
    © 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</footer>
</body>
</html>