<!DOCTYPE html>







<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bobony RP - Discord</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">

    <!-- Google Font -->
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
            background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
            color: white;
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

        .container {
            padding: 150px 10% 80px;
            text-align: center;
        }

        .container h2 {
            font-size: 32px;
            margin-bottom: 15px;
            color: #ff0000;
        }

        .container p {
            margin-bottom: 50px;
            opacity: 0.8;
        }

        .discord-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .discord-card {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 15px;
            transition: 0.3s;
            border: 1px solid #ff0000;
        }

        .discord-card:hover {
            transform: translateY(-5px);
            background: #252525;
            box-shadow: 0 0 20px rgba(255,0,0,0.3);
        }

        .discord-card img {
            width: 80px;
            margin-bottom: 20px;
        }

        .discord-card h3 {
            margin-bottom: 15px;
        }

        .discord-card a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 25px;
            background: #ff0000;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: 0.3s;
            font-weight: 600;
        }

        .discord-card a:hover {
            background: white;
            color: #ff0000;
        }

        footer {
            text-align: center;
            padding: 25px;
            background: #111;
            border-top: 2px solid #ff0000;
            margin-top: 80px;
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

            .container {
                padding: 120px 20px 60px;
            }
        }
        
        @media(max-width:600px){
            .logo{
                font-size:18px;
            }
            .container h2 {
                font-size: 26px;
            }
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
        <li><a href="login.php" style="color: red;">Staff Login</a></li>
    </ul>
</nav>

<div class="overlay" onclick="toggleMenu()"></div>

<div class="container">
    <h2>Join Our Discord Community</h2>
    <p>Stay connected with Bobony Roleplay. Get announcements, support, and exclusive updates.</p>

    <div class="discord-grid">

        <div class="discord-card">
            <img src="https://cdn-icons-png.flaticon.com/512/2111/2111370.png" alt="Discord Logo">
            <h3>Bobony Family</h3>
            <p>Official Bobony Family Discord server.</p>
            <a href="https://discord.gg/sPX9zQKXC9" target="_blank">Join Now</a>
        </div>

        <div class="discord-card">
            <img src="https://cdn-icons-png.flaticon.com/512/2111/2111370.png" alt="Support">
            <h3>Bobony RolePlay</h3>
            <p>Official Bobony Roleplay Discord server.</p>
            <a href="https://discord.gg/bobony-roleplay-1167631174588448790" target="_blank">Join Now</a>
        </div>

        

    </div>
</div>

<footer>
    © 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</footer>
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