<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Board Page
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file will save user settings. (PHP Part)
 *
*/


define("AUTH", true);
require_once("np-header.php");


function display_set_error($msg){
	echo "<head><title>Error!</title></head><body><style>body{background:#f4f4f4; padding:30px}</style><div style=\"background:white;padding:50px 40px;font-size:18px;border:1px solid #ccc;border-radius:3px;max-width:550px;margin: 10px auto auto auto\"><b>".$msg."</b><br/>&rarr; <a href=\"/settings/\">Go to Settings</a></div></body></html>";
	die;
}

// set theme
if(!empty($_GET['set_theme'])){
	if($_GET['set_theme']=="Classic" || $_GET['set_theme']=="Default" || $_GET['set_theme']=="Dark"){
		$stmt=$pdo->prepare("UPDATE `user_info` SET `theme`=? WHERE `id`=?");
		$stmt->execute([$_GET['set_theme'], $user_id]);
		header("Location: /settings/?set=1");
		die;
	}
	else{
		display_set_error("This theme isn't available to use! Please choose another theme!");
	}
}


// set language
if(!empty($_GET['set_lang'])){
        if($_GET['set_lang']=="English" || $_GET['set_lang']=="Nepali"){
                $stmt=$pdo->prepare("UPDATE `user_info` SET `language`=? WHERE `id`=?");
                $stmt->execute([$_GET['set_lang'], $user_id]);
                header("Location: /settings/?set=1");
                die;
        }
        else{
                display_set_error("This language isn't available yet! If you want, you can help us <a href=\"https://npchan.com/source\">Translate</a> it.");
        }
}


if(!empty($_GET['set_random'])){
	if($_GET['set_random']=="yes" || $_GET['set_random']=="no"){

		$_GET['set_random']=="yes" ? $value=true : $value=false;
		$stmt=$pdo->prepare("UPDATE `user_info` SET `random_image`=? WHERE `id`=?");
		$stmt->execute([$value, $user_id]);
		header("Location: /settings/?set=1");
		die;
	}
	else{
		display_set_error("Invalid Request! Please try again!");
	}
	
}

if(!empty($_GET['toggle_form'])){

		$user_info['thread_box'] ? $value=false : $value=true;
		$stmt=$pdo->prepare("UPDATE `user_info` SET `thread_box`=? WHERE `id`=?");
		$stmt->execute([$value, $user_id]);
		if(!empty($_GET['ajax'])){
			echo "DONE";
		} else {
			header("Location: /settings/?set=1");
		}
		die;
}

display_set_error("This feature is either unavailable to set or isn't complete to use it yet!");
die;