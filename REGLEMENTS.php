<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bobony RP - Server Rules</title>
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
            padding: 150px 10% 60px;
        }

        .section {
            margin-bottom: 60px;
        }

        .section h2 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #ff0000;
            border-left: 5px solid #ff0000;
            padding-left: 15px;
        }

        .rule-box {
            background: #1e1e1e;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 4px solid #ff0000;
            transition: 0.3s;
        }

        .rule-box:hover {
            transform: translateY(-3px);
            background: #252525;
        }

        footer {
            text-align: center;
            padding: 25px;
            background: #111;
            border-top: 2px solid #ff0000;
            margin-top: 50px;
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
<li><a href="home.php">Home</a></li>
<li><a href="discord.php">Discord</a></li>
<li><a href="team.php">Team</a></li>
<li><a href="bans.php">Bans</a></li>
<li><a href="annoucement.php">Announcements</a></li>
<li><a href="REGLEMENTS.php">Reglements</a></li>
<li><a href="login.php" style="color:red;">Staff Login</a></li>
    </ul>
</nav>

<div class="container">

    <!-- GENERAL RULES -->
    <div class="section">
        <h2>General Rules</h2>

        <div class="rule-box">• TOXIC RP: Any toxic behavior affecting the player behind the character (especially TREBRIB) → 2 to 4 Days Ban.</div>
        <div class="rule-box">• GIRLS RP: Disrespecting female players → PERMA BAN.</div>
        <div class="rule-box">• Need Admin? Use /report or open a ticket with full clip (voice required).</div>
        <div class="rule-box">• Report after 1 week = Cancelled.</div>
        <div class="rule-box">• Fight Cooldown 30min – No Respect CD → 2 Days.</div>
        <div class="rule-box">• Metagaming (Using Discord/Stream info in RP) → 2 Days Min.</div>
        <div class="rule-box">• Powergaming allowed (Semi-Strict RP).</div>
        <div class="rule-box">• Carkill intentional → 4 Days Min.</div>
        <div class="rule-box">• Freekill → 2 to 4 Days.</div>
        <div class="rule-box">• NVL (No Value of Life) → 4 Days Min.</div>
        <div class="rule-box">• /Me misuse → 2 Days.</div>
        <div class="rule-box">• Salary Abuse → 7 Days.</div>
        <div class="rule-box">• Acting as Admin in RP → 2 Days Min.</div>
        <div class="rule-box">• ALT to ALT Transfer → PERMA BAN.</div>
        <div class="rule-box">• Out of Character abuse → 2 Days Min.</div>
        <div class="rule-box">• Win RP → 2 Days.</div>
        <div class="rule-box">• Refuse RP with Police/Medic → 2 Days.</div>
        <div class="rule-box">• Mass RP limit → 48h Ban.</div>
        <div class="rule-box">• Cohérence RP violation → Whitelist Revoked.</div>
        <div class="rule-box">• ALT+F4 during scene → 7 Days.</div>
        <div class="rule-box">• 2 Jobs → 7 Days.</div>
        <div class="rule-box">• Cancel Animation abuse → 7 Days.</div>
        <div class="rule-box">• Destructive Speech (racism, politics, discrimination) → 2 Weeks to PERMA.</div>
    </div>

    <!-- ILLEGAL RULES -->
    <div class="section">
        <h2>Illegal Rules</h2>

        <div class="rule-box">• Mort RP → Must submit Mort RP file.</div>
        <div class="rule-box">• Street Fight: No cooldown in own street, attackers max 20min.</div>
        <div class="rule-box">• Rob Police forbidden (except escaping situation) → 2 Days.</div>
        <div class="rule-box">• Hostage only for robbery (No police kidnapping to free friends) → 48h Ban.</div>
        <div class="rule-box">• Escorting dead players forbidden → 2 Days.</div>
        <div class="rule-box">• No Memory Voice – Cannot recognize someone only by voice.</div>

    </div>

    <!-- STREAMER RULES -->
    <div class="section">
        <h2>Streamer Rules</h2>

        <div class="rule-box">• Maintain Positive Image – No negative speech about Bobony RP.</div>
        <div class="rule-box">• Must follow all server rules while streaming.</div>
        <div class="rule-box">• OOC Insults strictly forbidden (no real-life insults, slurs, mocking).</div>
        <div class="rule-box">• Saying “it’s jokes” or “stream content” is NOT an excuse.</div>
        <div class="rule-box">• Violations → 48h Ban to PERMA.</div>

    </div>

    <!-- HEIST / BRAQUAGES RULES -->
    <div class="section">
        <h2>Heist Rules</h2>

        <div class="rule-box">
            <strong>Shop Robbery</strong>
            <ul>
                <li>Criminals: Minimum: 1 vehicle + 2 criminals. Maximum: 1 vehicle + 4 criminals.</li>
                <li>Once the police pursuit begins, you cannot stop to use repair kits or refuel. You are fully responsible for your vehicle and decisions.</li>
                <li>At least one criminal must always stay with the hostage. If the hostage is left alone, police are allowed to intervene, and arrest everyone involved.</li>
                <li>Getting arrested more than once in the same day will increase your fine: 2nd arrest: x2 fine; 3rd arrest: x3 fine (and so on).</li>
                <li>You must have a weapon (Melee or Pistol) and a real hostage to initiate the robbery.</li>
                <li>Escape using motorcycles is strictly prohibited (cars only).</li>
            </ul>
        </div>

        <div class="rule-box">
            <strong>Laundromat Heist</strong>
            <ul>
                <li>Criminals: Minimum: 1 vehicle + 2 criminals + 1 hostage. Maximum: 1 vehicle + 4 criminals + 2 hostages.</li>
                <li>Police: Minimum: 2 vehicles + 4 officers. Maximum: 4 vehicles + 8 officers.</li>
            </ul>
        </div>

        <div class="rule-box">
            <strong>Cash Exchange Heist</strong>
            <ul>
                <li>Criminals: Minimum: 1 vehicle + 2 criminals + 1 hostage. Maximum: 1 vehicle + 4 criminals + 2 hostages.</li>
                <li>Police: Minimum: 2 vehicles + 4 officers. Maximum: 4 vehicles + 8 officers.</li>
            </ul>
        </div>

        <div class="rule-box">
            <strong>Fleeca Robbery (Cooldown 1 week)</strong>
            <ul>
                <li>Criminals: Minimum: 1 vehicle + 2 criminals + 2 hostages + 2 pistols. Maximum: 1 vehicle + 4 criminals + 4 hostages.</li>
                <li>Police: Minimum: 3 vehicles + 6 officers. Maximum: 4 vehicles + 8 officers.</li>
            </ul>
        </div>

        <div class="rule-box">
            <strong>Pacific Robbery (Cooldown 1 week)</strong>
            <ul>
                <li>Criminals: Minimum: 2 vehicles + 8 criminals + 3 hostages (all armed). Maximum: 3 vehicles + 10 criminals + 6 hostages (all armed).</li>
                <li>Police: Minimum: 6 vehicles + 12 officers + 2 helicopters (4 officers). Maximum: 9 vehicles + 18 officers + 2 helicopters (4 officers).</li>
            </ul>
        </div>

        <div class="rule-box">
            <strong>FIB Heist</strong>
            <ul>
                <li>Criminals: Minimum: 1 vehicle + 4 criminals + 2 hostages + 2 pistols. Maximum: 2 vehicles + 6 criminals + 4 hostages.</li>
                <li>Police: Minimum: 4 vehicles + 7 officers + 1 helicopter. Maximum: 6 vehicles + 12 officers + 1 helicopter.</li>
            </ul>
        </div>

        <div class="rule-box">
             <strong>LANCEMENT BRAQUAGE</strong> 
        </div>

        <div class="rule-box">
            ALL GANGS: LI KAYLO7O LES BRAQUAGES F DISCORD O KAYKON 3ANDHOM L OK ANAHO APPROUVE, 3ANDKOM MAXIMUM 30MIN BACH TLANCER BRAQUAGE DIALAK SINN POLICE GHADI ANNULIWH ! HADO LES BRAQUAGES CONCERNED: Art Heist, Jewellery Heist, Fleeca Robbery, Paleto Robbery, Pacific Robbery
        </div>

    </div>
     <!-- STREAMER RULES -->
    <div class="section">
        <h2>🚫 Blacklisted Words</h2>

        <div class="rule-box">•The following words are strictly forbidden (IG & OOC):.</div>
        <div class="rule-box">• Cringe.</div>
        <div class="rule-box">• l7ass.</div>
        <div class="rule-box">• 3ebad.</div>
        <div class="rule-box">• l9bar.</div>
        <div class="rule-box">• klawa.</div>
        <div class="rule-box">• Punishment:</div>
        <div class="rule-box">• 1st — Warning
2nd — Ban 48h
Repeated </div>
        

    </div>
    </div>
</div>
 

<footer>
    © 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</footer>
<script src="animations.js"></script>
</body>
</html>