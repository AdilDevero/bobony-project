<?php
require 'config.php';

$categories = [
    'General Rules',
    'Illegal Rules',
    'Streamer Rules',
    'Heist / Braquages Rules',
    'Blacklisted Words'
];

$rules_by_category = [];
foreach($categories as $cat) {
    $rules_by_category[$cat] = [];
}

$sql = "SELECT * FROM reglements ORDER BY id ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cat = $row['category'];
        if (!isset($rules_by_category[$cat])) {
            $rules_by_category[$cat] = [];
            $categories[] = $cat; // In case there are new categories dynamically added
        }
        $rules_by_category[$cat][] = $row;
    }
}
?>
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
            line-height: 1.6;
        }

        .rule-box:hover {
            transform: translateY(-3px);
            background: #252525;
        }
        
        .rule-box ul {
            margin-top: 10px;
            margin-left: 20px;
        }
        
        .rule-box li {
            margin-bottom: 5px;
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

<div class="container">

    <?php foreach($categories as $cat): ?>
    <?php if(!empty($rules_by_category[$cat])): ?>
    <div class="section">
        <h2><?php echo $cat === 'Blacklisted Words' ? '🚫 ' : ''; ?><?php echo htmlspecialchars($cat); ?></h2>

        <?php foreach($rules_by_category[$cat] as $rule): ?>
            <div class="rule-box">
                <?php 
                $rule_text = htmlspecialchars($rule['rule_text']);
                if (strpos($rule_text, "\n") !== false) {
                    $lines = explode("\n", $rule_text);
                    echo "<strong>" . trim($lines[0]) . "</strong>";
                    if (count($lines) > 1) {
                        echo "<ul>";
                        for($i=1; $i<count($lines); $i++){
                            $l = trim($lines[$i]);
                            if (!empty($l)) {
                                if(str_starts_with($l, '- ')) {
                                    $l = substr($l, 2);
                                }
                                echo "<li>" . $l . "</li>";
                            }
                        }
                        echo "</ul>";
                    }
                } else {
                    echo "• " . $rule_text;
                }
                ?>
                
                <?php if (!empty($rule['ban_time'])): ?>
                    &nbsp;→&nbsp;<span style="color: #ff4757; font-weight: 600;"><?php echo htmlspecialchars($rule['ban_time']); ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>
    <?php endforeach; ?>

</div>

<footer>
    © 2026 Bobony Roleplay - All Rights Reserved Dev by Anass
</footer>
<script src="animations.js"></script>
</body>
</html>