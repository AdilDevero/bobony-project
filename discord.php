<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bobony RP - Discord</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

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

        @media(max-width:768px){
            header{
                flex-direction: column;
            }
            nav{
                margin-top: 15px;
            }
        }
    </style>
</head>

<body>

<nav>
    <div class="logo">Bobony Family</div>
    <ul>
        <li><a href="home.html">Home</a></li>
        <li><a href="discord.html">Discord</a></li>
        <li><a href="team.html">Team</a></li>
        <li><a href="bans.php">Bans</a></li>
        <li><a href="REGLEMENTS.html">REGLEMENTS</a></li>
        <li><a href="login.php" style="color: red;">Staff Login</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Join Our Discord Community</h2>
    <p>Stay connected with Bobony Roleplay. Get announcements, support, and exclusive updates.</p>

    <div class="discord-grid">

        <div class="discord-card">
            <img src="https://cdn-icons-png.flaticon.com/512/2111/2111370.png" alt="Discord Logo">
            <h3>Main Discord Server</h3>
            <p>Official Bobony Roleplay Discord server.</p>
            <a href="https://discord.gg/YOURINVITELINK" target="_blank">Join Now</a>
        </div>

        <div class="discord-card">
            <img src="https://cdn-icons-png.flaticon.com/512/2111/2111370.png" alt="Support">
            <h3>Support & Tickets</h3>
            <p>Open tickets and contact administration team.</p>
            <a href="https://discord.gg/YOURSUPPORTLINK" target="_blank">Open Ticket</a>
        </div>

        <div class="discord-card">
            <img src="https://cdn-icons-png.flaticon.com/512/2111/2111370.png" alt="Announcements">
            <h3>Announcements</h3>
            <p>Stay updated with events and updates.</p>
            <a href="https://discord.gg/YOURANNOUNCELINK" target="_blank">View Updates</a>
        </div>

    </div>
</div>

<footer>
    © 2026 Bobony Roleplay - All Rights Reserved
</footer>

</body>
</html>