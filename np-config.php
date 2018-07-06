<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Configuration Page
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file contains all the configuration of this script.
 * Please edit carefully.
*/

// prevent direct access to script
if(!defined("AUTH")){die;}


// Site information define
define("SITE_NAME", "NPChan");
define("SITE_TAGLINE", "The Nepali Internet!");
define("COPYRIGHT", "NPChan");
define("SITE_URL", "http://localhost/");	// change it to localhost for testing, your site's URL for deployment
define("CURRENT_VERSION", "2.0.9"); 			// script version

// site social media links (Put Username only)
$social=[
	"facebook" => "OfficialNPChan",
	"twitter" => "OfficialNPChan",
	"phone" => "(+977) 98650-36410",
	"mail" => "contact@npchan.com"
];

// Database Information
define("DB_NAME", "npchan");		// database name
define("DB_USER", "root");		// database username
define("DB_PASS", "");		// database password
define("DB_HOST", "localhost");		// database host (usually localhost)

// error reporting off
// error_reporting();

// setting timezone to Nepal (and yes, Kathmandu is spelled wrong, but that's what php wants)
date_default_timezone_set('Asia/Katmandu');


// Alll available themes and Languages
$ALL_THEMES=array("Default", "Classic", "Dark");
$ALL_LANGUAGES=array("English", "Nepali");


// preparing for db connection
$_DB_DSN="mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
$_DB_OPT=[
	PDO::ATTR_ERRMODE				=>	PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE	=>	PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES		=>	false
];

// connecting to database
try{
	$pdo = new PDO($_DB_DSN, DB_USER, DB_PASS, $_DB_OPT);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	// throw error exception
	$error=500;
 	require("np-error.php");
    die;
}
