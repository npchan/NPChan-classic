<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Random Banner Generator
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * Random Banner (At top of boards and threads page) generator.
 * This will redirect to a random image file.
*/
define("AUTH", true);
require_once("np-header.php");

$stmt=$pdo->query("SELECT * FROM `random_image` ORDER BY RAND() LIMIT 1");
$stmt->execute();
$data_fetch=$stmt->fetch();

// redirecting
header("Location: cdn/random/".$data_fetch['url']);
die;