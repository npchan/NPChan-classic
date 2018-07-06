<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Header File
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * Home Page (Contains Links to All Boards, Trending Threads,
 * A Big Search Form and Links to Alternative Services)
*/

if(!defined("AUTH")){ die; }

require_once("np-config.php");
require_once("np-functions.php");

// some variables for future use
$time=time();
/* User's Information We might need */
$user_ip=GetIP();
// visitor's ip address (for local testing env.)
if($user_ip==""){
    $user_ip="::1";
}
// get visitior's browser Address
$user_browser=filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING);


// great, we got everything here.


/*
 * Function: Create New User Profile
 * ---------------------------------
 * If no user profile is saved on our server, we generate a new one.
 */

function create_new_profile(){
	global $pdo,$time,$user_ip,$user_browser;

	// generates random share_key for future use
	$new_user_id=mt_rand(1, 9).random_code(13).mt_rand(1, 9);
	$user_id=password_hash($new_user_id, PASSWORD_BCRYPT);
	$auto_destruct=time()+(60*60*24*365);	// one year after the creation
	$random_id=random_code(6); // generates random ID
	$stmt=$pdo->prepare("INSERT INTO `user_info`(user_id, last_active, ip_address, trip_code, browser_info) VALUES(?, ?, ?, ?, ?);");
	$stmt->execute([$user_id, $time, $user_ip, $random_id, $user_browser]);
	setcookie("USER_ID", $user_id, $auto_destruct, "/");
	return $user_id;
}


/*
 * AUTO CREATE USER PROFILE
 * -----------------------
 * If there is no user's profile, we generate new one. The above function is called.
*/
if(empty($_COOKIE['USER_ID'])){
	$usr_id_return=create_new_profile();
}

/* IF user exits, verify the Profile & Extract Data */
if(!empty($_COOKIE['USER_ID'])){
	$_usr_id=$_COOKIE['USER_ID'];
	$stmt=$pdo->prepare("SELECT count(*) FROM `user_info` WHERE `user_id`=?;");
	$stmt->execute([$_usr_id]);
	$_usr_id_count=$stmt->fetchColumn();
	if($_usr_id_count!=1){
		setcookie("USER_ID", "", time()- (86400 *365), "/");
		$usr_id_return=create_new_profile();		// create new id again (if not verified)
	}
}
	if(empty($_COOKIE['USER_ID'])){
		$usr_id_c=$usr_id_return;
	}
	else{
		$usr_id_c=$_COOKIE['USER_ID'];
	}
	$stmt=$pdo->prepare("SELECT * FROM `user_info` WHERE `user_id`=?;");
	$stmt->execute([$usr_id_c]);
	$_usr_data=$stmt->fetch();

	// USER_ID
	$user_id=$_usr_data['id'];

	// define user ID
	define('USER_ID', $user_id);

	// user's information in a variable
	$user_info=$_usr_data;


	// basic information return
	$user_lang=$user_info['language'];
	$user_theme=$user_info['theme'];
	$user_nsfw=$user_info['nsfw'];


	// Workaround for bug at user's first visit
	if(empty($user_theme)){
		$user_theme="Default";
	}
	if(empty($user_lang)){
		$user_lang="English";
	}
	
// load functions that are called after we get user's information
require_once("np-functions-end.php");