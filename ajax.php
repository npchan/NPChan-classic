<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Ajax Page
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This page handles all the minor editing tasks as well as
 * display some content (like thread preview)
*/

define("AUTH", true);
require_once("np-header.php");

/* For Homepage Stuffs */

if(!empty($_GET['toggle'])){

	if($_GET['item']=="site_intro"){
		$s_i=1;
		if($user_info['site_intro']){
			$s_i=0;
		}

		$stmt=$pdo->prepare("UPDATE `user_info` SET `site_intro`=? WHERE `id`=?");
		$stmt->execute([$s_i, $user_id]);
	}

	if($_GET['item']=="board_intro"){
		$s_i=1;
		if($user_info['board_intro']){
			$s_i=0;
		}

		$stmt=$pdo->prepare("UPDATE `user_info` SET `board_intro`=? WHERE `id`=?");
		$stmt->execute([$s_i, $user_id]);

	}

	header("Location: /");
	die;
}


if(!empty($_GET['board'])){
	header("Location: ".strtolower(strip_tags($_GET['board'])));
	die;
}

if(!empty($_GET['toggle_nsfw'])){
		$s_i=1;
		if($user_info['nsfw']!="show"){
			$s_i=0;
		}

		$stmt=$pdo->prepare("UPDATE `user_info` SET `nsfw`=? WHERE `id`=?");
		$stmt->execute([$s_i, $user_id]);
		header("Location: /");
		die;
}

/* This area isn't quite ready yet, please wait */
if(!empty($_POST['for']) && $_POST['for']=="thread_preview"){
	if(!empty($_POST['thread_id']) && is_integer($_POST['thread_id'])){

		echo "Thread Preview";
	}
	else{
		echo "Invalid Request!";
	}
	die;
}