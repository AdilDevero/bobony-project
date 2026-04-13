<?php
require 'config.php';

$rules = [
    // General Rules
    ['General Rules', 'TOXIC RP: Any toxic behavior affecting the player behind the character (especially TREBRIB)', '2 to 4 Days Ban'],
    ['General Rules', 'GIRLS RP: Disrespecting female players', 'PERMA BAN'],
    ['General Rules', 'Need Admin? Use /report or open a ticket with full clip (voice required).', ''],
    ['General Rules', 'Report after 1 week = Cancelled.', ''],
    ['General Rules', 'Fight Cooldown 30min – No Respect CD', '2 Days'],
    ['General Rules', 'Metagaming (Using Discord/Stream info in RP)', '2 Days Min'],
    ['General Rules', 'Powergaming allowed (Semi-Strict RP)', ''],
    ['General Rules', 'Carkill intentional', '4 Days Min'],
    ['General Rules', 'Freekill', '2 to 4 Days'],
    ['General Rules', 'NVL (No Value of Life)', '4 Days Min'],
    ['General Rules', '/Me misuse', '2 Days'],
    ['General Rules', 'Salary Abuse', '7 Days'],
    ['General Rules', 'Acting as Admin in RP', '2 Days Min'],
    ['General Rules', 'ALT to ALT Transfer', 'PERMA BAN'],
    ['General Rules', 'Out of Character abuse', '2 Days Min'],
    ['General Rules', 'Win RP', '2 Days'],
    ['General Rules', 'Refuse RP with Police/Medic', '2 Days'],
    ['General Rules', 'Mass RP limit', '48h Ban'],
    ['General Rules', 'Cohérence RP violation', 'Whitelist Revoked'],
    ['General Rules', 'ALT+F4 during scene', '7 Days'],
    ['General Rules', '2 Jobs', '7 Days'],
    ['General Rules', 'Cancel Animation abuse', '7 Days'],
    ['General Rules', 'Destructive Speech (racism, politics, discrimination)', '2 Weeks to PERMA'],
    
    // Illegal Rules
    ['Illegal Rules', 'Mort RP', 'Must submit Mort RP file'],
    ['Illegal Rules', 'Street Fight: No cooldown in own street, attackers max 20min.', ''],
    ['Illegal Rules', 'Rob Police forbidden (except escaping situation)', '2 Days'],
    ['Illegal Rules', 'Hostage only for robbery (No police kidnapping to free friends)', '48h Ban'],
    ['Illegal Rules', 'Escorting dead players forbidden', '2 Days'],
    ['Illegal Rules', 'No Memory Voice – Cannot recognize someone only by voice.', ''],
    
    // Streamer Rules
    ['Streamer Rules', 'Maintain Positive Image – No negative speech about Bobony RP.', ''],
    ['Streamer Rules', 'Must follow all server rules while streaming.', ''],
    ['Streamer Rules', 'OOC Insults strictly forbidden (no real-life insults, slurs, mocking).', ''],
    ['Streamer Rules', 'Saying “it’s jokes” or “stream content” is NOT an excuse.', ''],
    ['Streamer Rules', 'Violations', '48h Ban to PERMA'],
    
    // Heist / Braquages Rules
    ['Heist / Braquages Rules', "Shop Robbery\n- Criminals: Minimum: 1 vehicle + 2 criminals. Maximum: 1 vehicle + 4 criminals.\n- Once the police pursuit begins, you cannot stop to use repair kits or refuel. You are fully responsible for your vehicle and decisions.\n- At least one criminal must always stay with the hostage. If the hostage is left alone, police are allowed to intervene, and arrest everyone involved.\n- Getting arrested more than once in the same day will increase your fine: 2nd arrest: x2 fine; 3rd arrest: x3 fine (and so on).\n- You must have a weapon (Melee or Pistol) and a real hostage to initiate the robbery.\n- Escape using motorcycles is strictly prohibited (cars only).", ''],
    ['Heist / Braquages Rules', "Laundromat Heist\n- Criminals: Minimum: 1 vehicle + 2 criminals + 1 hostage. Maximum: 1 vehicle + 4 criminals + 2 hostages.\n- Police: Minimum: 2 vehicles + 4 officers. Maximum: 4 vehicles + 8 officers.", ''],
    ['Heist / Braquages Rules', "Cash Exchange Heist\n- Criminals: Minimum: 1 vehicle + 2 criminals + 1 hostage. Maximum: 1 vehicle + 4 criminals + 2 hostages.\n- Police: Minimum: 2 vehicles + 4 officers. Maximum: 4 vehicles + 8 officers.", ''],
    ['Heist / Braquages Rules', "Fleeca Robbery (Cooldown 1 week)\n- Criminals: Minimum: 1 vehicle + 2 criminals + 2 hostages + 2 pistols. Maximum: 1 vehicle + 4 criminals + 4 hostages.\n- Police: Minimum: 3 vehicles + 6 officers. Maximum: 4 vehicles + 8 officers.", ''],
    ['Heist / Braquages Rules', "Pacific Robbery (Cooldown 1 week)\n- Criminals: Minimum: 2 vehicles + 8 criminals + 3 hostages (all armed). Maximum: 3 vehicles + 10 criminals + 6 hostages (all armed).\n- Police: Minimum: 6 vehicles + 12 officers + 2 helicopters (4 officers). Maximum: 9 vehicles + 18 officers + 2 helicopters (4 officers).", ''],
    ['Heist / Braquages Rules', "FIB Heist\n- Criminals: Minimum: 1 vehicle + 4 criminals + 2 hostages + 2 pistols. Maximum: 2 vehicles + 6 criminals + 4 hostages.\n- Police: Minimum: 4 vehicles + 7 officers + 1 helicopter. Maximum: 6 vehicles + 12 officers + 1 helicopter.", ''],
    ['Heist / Braquages Rules', "LANCEMENT BRAQUAGE", ''],
    ['Heist / Braquages Rules', "ALL GANGS: LI KAYLO7O LES BRAQUAGES F DISCORD O KAYKON 3ANDHOM L OK ANAHO APPROUVE, 3ANDKOM MAXIMUM 30MIN BACH TLANCER BRAQUAGE DIALAK SINN POLICE GHADI ANNULIWH ! HADO LES BRAQUAGES CONCERNED: Art Heist, Jewellery Heist, Fleeca Robbery, Paleto Robbery, Pacific Robbery", ''],

    // Blacklisted Words
    ['Blacklisted Words', 'The following words are strictly forbidden (IG & OOC):', ''],
    ['Blacklisted Words', 'Cringe.', ''],
    ['Blacklisted Words', 'l7ass.', ''],
    ['Blacklisted Words', '3ebad.', ''],
    ['Blacklisted Words', 'l9bar.', ''],
    ['Blacklisted Words', 'klawa.', ''],
    ['Blacklisted Words', 'Punishment:', ''],
    ['Blacklisted Words', "1st — Warning\n2nd — Ban 48h\nRepeated", '']
];

// Truncate table to restart fresh if run multiple times
$conn->query("TRUNCATE TABLE reglements");

$stmt = $conn->prepare("INSERT INTO reglements (category, rule_text, ban_time) VALUES (?, ?, ?)");
foreach ($rules as $rule) {
    $stmt->bind_param("sss", $rule[0], $rule[1], $rule[2]);
    $stmt->execute();
}

echo "Rules populated successfully.\n";
?>
