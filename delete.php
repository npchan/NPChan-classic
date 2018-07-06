<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Delete Page
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file deletes the thread if the user is moderator or OP. If it is a thread, it deletes replies too.
*/


define("AUTH", true);
require_once("np-header.php");

// we haven't added any mod protocol yet
$is_mod=false;

if(empty($_GET['id']) && !is_numeric($_GET['id'])){
	header("Location: /");
	die;
}

function show_error_delete($title, $text){
/* This is an ugly way to do things but sorry */
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="robots" content="index, follow"><meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"><meta http-equiv="x-ua-compatible" content="ie=edge"><title>Confirm Delete? | NPChan</title><link rel="shortcut icon" type="image/png" href="/assets/images/icon.png"><style type="text/css">body{background:#ffc7c7;padding:0;margin:0;color:#444} .container{margin:auto; max-width:900px; background:#fff; box-shadow:0 4px 8px 0 rgba(0,0,0,.2),0 6px 20px 0 rgba(0,0,0,.19)} .header-stripe{text-align:center; vertical-align:middle; background-color:#fc5252; padding:10px} .error-title{border-bottom:1px solid #ffd2d2;padding:10px 30px} .error-title h4{font-size:35px;margin:0;padding:0;color:#444} h4{display:inline-block;color:#fff;font-family:serif;text-shadow:.03em .03em 0 hsla(230,40%,50%,1)} .error-msg{padding:20px 30px;background-color:#fff} .error-alt{font-size:20px}.contact{border-top:1px solid #ffd2d2;margin-top:5px;padding:10px;text-align:center}.contact-text{padding-bottom:10px}.contact-text span{border-bottom:3px solid #ffd2d2;padding:0 10px;font-weight:700;text-transform:uppercase} .contact-links{padding-bottom:10px} .contact-links a{color:grey;text-decoration:none} .contact-links a:hover{color:#fc5252} .credits{background:#fc5252;text-align:center;padding:20px;color:#fff} .credits a{color:#f4f4f4;text-decoration:none} .credits a:hover{text-decoration:underline} .logo{height:50px} @media(min-width:600px){ *{font-size:102%} .logo{height:60px}}@media(min-width:900px){ *{font-size:103%} .container{border-radius:5px;margin-top:40px} .logo{height:70px}} .copyright{font-weight:700;margin-bottom:5px}</style></head><body><div class="container"><div class="header-stripe"><a href="/"><img src="/assets/images/error-logo.png" class="logo" alt="NPChan Logo"></a></div><div class="error-title"><h4>'.$title.'</h4></div><div class="error-msg"><div class="error-alt">'.$text.'</div><div class="error-suggest"><p>&larr; <a href="/">Back to Home Page</a></p></div></div><div class="contact"><div class="contact-text"><span>Report Error </span></div><div class="contact-links">Facebook: <a href="http://facebook.com/OfficialNPChan">@OfficialNPChan</a> &middot; Twitter: <a href="http://twitter.com/OfficialNPChan">@OfficialNPChan</a> &middot;
(+977) 98650-36410</div></div><div class="credits"><div class="copyright">&copy; '.date("Y").' - NPChan</div></div></div></body></html>';
die;
}

if(empty($_GET['confirm'])){
	show_error_delete("Confirm Delete?", "<p>Do you really want to delete this post? Deleting this post will delete all replies it had (if this is a thread starter) and also delete all images/videos. Only use this option if your certain of it. There is no going back, this permanently deletes the thread!</p> <p><b><a href=\"/delete.php?id=".$_GET['id']."&confirm=yes\">Yes, Delete It!</a> &nbsp; &middot; &nbsp; <a href=\"/\"><b>Cancel, Keep It!</b></a></p>");
	die;
}

if($_GET['confirm']!="yes"){
	show_error_delete("Confirm Delete?", "<p>Do you really want to delete this post? Deleting this post will delete all replies it had (if this is a thread starter) and also delete all images/videos. Only use this option if your certain of it. There is no going back, this permanently deletes the thread!</p> <p><b><a href=\"/delete.php?id=".$_GET['id']."&confirm=yes\">Yes, Delete It!</a> &nbsp; &middot; &nbsp; <a href=\"/\"><b>Cancel, Keep It!</b></a></p>");
}


// all good, we received the delete request.

$id=(int)$_GET['id'];

$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=?");
$stmt->execute([$id]);
if($stmt->fetchColumn()!=1){
	show_error_delete("404 - Not Found", "<p>This thread doesn't exist in our system. This is already deleted or haven't been posted yet!</p>");
}

// thread exists
// check ownership

$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=? AND `user_id`=?");
$stmt->execute([$id, $user_id]);
if($stmt->fetchColumn()!=1 && !$is_mod){
	show_error_delete("Invalid Original Poster!", "<p>Ops! It looks like you are not the one who posted this thread/reply or you are trying to delete from another browser or device. Please use the same device and browser you used to create this thread/reply to delete it! If you are unable, at least from that same IP address, report this thread, we will delete it!</p>");
}


// ownership is done
// check if it is a thread starter

function delete_post($id){
	global $user_id,$pdo;
	$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `id`=? AND `user_id`=?");
	$stmt->execute([$id, $user_id]);
	$thread_data=$stmt->fetch();

	$files=json_decode($thread_data['files']);
	if($files->original_name!="EMPTY"){
		unlink($files->cache);				// permanently delete cache image
		unlink($files->path.$files->name); 	// permanently delete the image (full sized)
	}

	// delete that thread

	$stmt=$pdo->prepare("DELETE FROM `threads` WHERE `id`=? AND `user_id`=?");	// permanently delete this thread too
	$stmt->execute([$id, $user_id]);

	return true;
}
$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=? AND `user_id`=? AND `thread_start`=?");
$stmt->execute([$id, $user_id, 'Y']);
if($stmt->fetchColumn()!=1){
	delete_post($id);
}
else{
	$stmt=$pdo->prepare("SELECT `id` FROM `threads` WHERE `thread_reply`=?");
	$stmt->execute([$id]);
	foreach($stmt->fetchAll() as $row){
		delete_post($row['id']);
	}

	// all posts deleted? delete original thread too
	delete_post($id);
}

// all deleted! We are good!

show_error_delete("Thread Deleted Successfully!", "<p>This thread has been deleted successfully!</p>");