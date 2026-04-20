<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bobony Family | Team</title>

    <link rel="icon" type="img/bbnylogo.png" href="img/bbnylogo.png">

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
            background: #070707;
            color: white;
            overflow-x: hidden;
        }

        /* GRID BACKGROUND */

        body::before {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(255, 0, 0, 0.06)1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 0, 0, 0.06)1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -2;
        }

        body::after {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 20%, rgba(255, 0, 0, 0.15), transparent 70%);
            z-index: -1;
        }

        /* NAVBAR */

        nav {
            position: fixed;
            width: 100%;
            padding: 20px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 0, 0, 0.3);
            z-index: 1000;
        }

        .logo {
            font-weight: 800;
            font-size: 22px;
            color: red;
            letter-spacing: 2px;
        }

        nav ul {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        nav ul li a {
            text-decoration: none;
            color: #ccc;
            transition: .3s;
        }

        nav ul li a:hover {
            color: red
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

        /* HEADER */

        .header {
            padding: 160px 20px 60px;
            text-align: center;
        }

        .header h1 {
            font-size: 45px;
            font-weight: 800;
        }

        .header span {
            color: red
        }

        .header p {
            margin-top: 20px;
            color: #aaa;
            max-width: 700px;
            margin: auto;
        }

        /* CATEGORY */

        .category-title {
            padding: 40px 80px 20px;
            font-size: 28px;
            font-weight: 700;
            color: red;
        }

        /* TEAM GRID */

        .team-container {
            padding: 0 80px 100px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .card {
            background: #111;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255, 0, 0, 0.2);
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-10px);
            border-color: red;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.4);
        }

        .card img {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            margin-bottom: 20px;
            object-fit: cover;
        }

        .card h3 {
            margin-bottom: 5px;
        }

        .role {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .socials {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .socials a {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 1px solid red;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: red;
            transition: .3s;
            font-size: 14px;
        }

        .socials a:hover {
            background: red;
            color: black;
        }

        /* MOBILE */

        @media(max-width:900px) {
            nav {
                padding: 15px 20px;
            }

            .menu-toggle {
                display: block;
                z-index: 1002;
            }

            nav ul {
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

            .team-container {
                padding: 20px;
                gap: 20px;
            }

            .header h1 {
                font-size: 30px;
            }

            .category-title {
                padding: 30px 20px 15px;
                font-size: 24px;
            }

        }
        
        @media(max-width:600px){
            .logo{
                font-size:18px;
            }
            .header {
                padding: 120px 20px 40px;
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
            <li><a href="login.php" style="color:red;">Staff Login</a></li>
            
        </ul>

    </nav>

    <div class="overlay" onclick="toggleMenu()"></div>

    <section class="header">

        <h1>The People Behind <span>Bobony Family</span></h1>

        <p>
            Meet the talented individuals who make Bobony Family possible.
            Our team works together to create the best roleplay experience.
        </p>

    </section>

    <!-- OWNER -->

    <div class="category-title">Owner & Developer</div>

    <section class="team-container">

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/236238100228079618/ef63adea8fa353a19ad10f7c21bad994.webp?size=1024">
            <h3>Cety</h3>
            <div class="role">Owner & Developer</div>
            <div class="socials">
                <a href="https://kick.com/cety01"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/thecety01/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://images-ext-1.discordapp.net/external/5Hd-IxseDSKJPUUvJQTO3ewL3BeM1iT0xNSxZbbBZ8c/https/files.kick.com/images/user/6479354/profile_image/conversion/4c665c92-8d99-46d4-8751-e7968ffc8b73-fullsize.webp">
            <h3>Nahoule82</h3>
            <div class="role">Owner</div>
            <div class="socials">
                <a href="https://kick.com/nahoule82k"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/nahoule82/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/284816730147258368/8f6067e00025b00ed149ec7b4f4d9add.webp?size=1024">
            <h3>vodkafunky1</h3>
            <div class="role">Owner</div>
            <div class="socials">
                <a href="https://kick.com/vodkafunky"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/vfunky1/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/176828432020275201/cf4970ef523f72353105baade8f32311.png?size=4096">
            <h3>Pin4tz</h3>
            <div class="role">Owner</div>
            <div class="socials">
                <a href="https://kick.com/pin4tzinhok"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/pin4tz/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

    </section>

    <!-- ADMIN RP -->

    <div class="category-title">Admin RP</div>

    <section class="team-container">

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/783447910795968512/ed4df167a4f571ae831226156ca415e3.webp?size=1024">
            <h3>$IMO Ornes</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/simo_ornes"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/simo_ornes/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="card">
            <img
                src="https://images-ext-1.discordapp.net/external/MlZAHwCA2eVeoMQH2uejsJ6tQzXltsppudlmhyNF1ic/%3Fsize%3D1024/https/cdn.discordapp.com/avatars/1199778409002315806/f6c6a1ddac6c8f4c64b94404bbd93678.webp?format=webp&width=320&height=320">
            <h3>Lhindi</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/lhindi95"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/boughaleb_95/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>


        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/622427982840987651/db47b7e607d43bbbcaceaf8165b72c76.webp?size=1024">
            <h3>ONIGIRI</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/onigiri78"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/onigiri_78/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/912100387287339008/30e4f68bca9ae571f8e342a0616f19b1.webp?size=1024">
            <h3>24 MOUSSAOUI</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/moussaoui"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/24_moussaoui/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://images-ext-1.discordapp.net/external/tgVSBgIeRrn7hdyRUpvTBqhSghr-HcUbob2zD6CLsFs/%3Fsize%3D1024%26animated%3Dtrue/https/cdn.discordapp.com/avatars/888554205697155093/a_59125327a91ec0fa36da509d88db0153.gif?width=225&height=225">
            <h3>GOATEDNOOBIE</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://www.kick.com/goatednoobie"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/goatednoobie"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/871885765423022151/79c004ed55befb7ebef4bbdc8b24e086.webp?size=1024">
            <h3>RobbaN 𖤐</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/robbangg"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/RobbaNgg"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/459823373682212874/c9d06a34c271717ebb346397323b03ab.webp?size=1024">
            <h3>Reviz</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/reviztv"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://instagram.com/reviztv"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/713130947721166848/8759131f118262404a4aac49f7d8e682.webp?size=1024">
            <h3>IHAB 🅳🆂</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/dsihab"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/ds_ihab"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/620569100342657037/aa67e53e81229f0277171ce29d22e760.png?size=4096">
            <h3>ZAWA9 🌪</h3>
            <div class="role">Admin RP</div>
            <div class="socials">
                <a href="https://kick.com/z4wa9"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/faroukbtlb"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/645050047259607061/0e61f9e463f1a345733771b79eaf0d29.webp?size=1024">
            <h3>Anderson</h3>
            <div class="role">Admin RP</div>
        </div>
        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/423561903986442250/39a37f1c38b0e537408d6e5285b67ffc.webp?size=1024">
            <h3>Lotfi</h3>
            <div class="role">Admin RP</div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/292327894426976256/e5d48c57bbe4e00bc2e2d3f2dfd9a6f2.webp?size=1024">
            <h3>Detect</h3>
            <div class="role">Admin RP</div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/guilds/1167631174588448790/users/528040466231459850/avatars/2948a9a5d82c54cc7df4dcb28e46e46f.webp?size=1024">
            <h3>𝐂𝟎𝐑𝐕𝟏𝐍🐲</h3>
            <div class="role">Admin RP</div>
        </div>

        <div class="card">
            <img
                src="https://images-ext-1.discordapp.net/external/XeT0Rqr5RQ8W1_VT6oPo6pQBWkvH3pz1ujy0la5laIM/%3Fsize%3D1024/https/cdn.discordapp.com/avatars/376748459891359754/e5b4adc89668c4474a249ddba8670fe3.webp?format=webp&width=810&height=810">
            <h3>DAX</h3>
            <div class="role">Admin RP</div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/274829332235419649/65d11b83ba2499d20d9a6595e99ba541.png?size=4096">
            <h3>! ᴘsʏᴄʜᴏsᴋᴜʟʟs 👽</h3>
            <div class="role">Admin RP</div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/198878862157807616/a1631685bb3895cfc53e13b1febec0e0.png?size=4096">
            <h3>Diaye 光</h3>
            <div class="role">Admin RP</div>
        </div>
    </section>

    <!-- HELPER RP -->

    <div class="category-title">Helper RP</div>

    <section class="team-container">

        <div class="card">
            <img
                src="https://images-ext-1.discordapp.net/external/I7rRJoL_DoZT1YeUCbnjruMDRiSeAc3GpAC6IjJVjgE/%3Fsize%3D1024/https/cdn.discordapp.com/avatars/704621230581481502/1247be17484006ef6dca29f6812b61d7.webp?format=webp&width=810&height=810">
            <h3>MHAND</h3>
            <div class="role">Helper RP</div>
            <div class="socials">
                <a href="https://kick.com/mhandq2"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/q2__ilias/"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/785802523364556801/22d73726421f26d96656c2a8d8114f2a.webp?size=1024">
            <h3>OMEGA</h3>
            <div class="role">Helper RP</div>
            <div class="socials">
                <a href="https://kick.com/omegagaming77"><i class="fa-brands fa-kickstarter"></i></a>
                <a href="https://www.instagram.com/omegagaming77"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="card">
            <img
                src="https://images-ext-1.discordapp.net/external/5R0Bimv7hN_F_dikr8TE_YV0_jGBZ60I7pNDFegUSaw/%3Fsize%3D1024/https/cdn.discordapp.com/avatars/1393567405506039900/aaa85028ec8bb3e11fcac6d34118be3e.webp?format=webp&width=320&height=320">
            <h3>evaa</h3>
            <div class="role">Helper RP</div>
        </div>
        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/1048613117044731925/43cf2325357ce95ea034785a2e019700.webp?size=1024">
            <h3>Alvario🐻</h3>
            <div class="role">Helper RP</div>
        </div>
        <div class="card">
            <img
                src="https://images-ext-1.discordapp.net/external/1SAPKE1NpgpLLL1DxVBhrBI3YROjY2F2TOLHBe1uu6s/%3Fsize%3D1024/https/cdn.discordapp.com/avatars/276079396656250882/aaf8d42650f3855abaae29675c95dfe0.webp?format=webp&width=320&height=320">
            <h3>𝓓𝖗Houssam BOUJM3A³⁰⚕ </h3>
            <div class="role">Helper RP</div>
        </div>
        <div class="card">
            <img
                src="https://cdn.discordapp.com/avatars/691745894684426316/94302a932a25d955ce3ccbf656c4b824.png?size=4096">
            <h3>⁴⁸“𝑪𝒐𝒍𝒐𝒏𝒆𝒍”⁵⁴ </h3>
            <div class="role">Helper RP</div>
        </div>
    </section>

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