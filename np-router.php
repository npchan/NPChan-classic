<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Router Page (This file redirects everything to their location (well, most of em'))
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file redirects stuffs around and is the core part of the site.
 * Please edit carefully.
*/

// simply redirect empty requests
if(empty($_GET['page'])){
	header("location: /");
	die;
}

define('AUTH', 'true');
require_once 'np-header.php';
$_POST_PAGE=true;

$p=filter_text($_GET['page']);

// check for board
function is_board($p){
	global $pdo;
  $p=strtolower($p);

  $stmt=$pdo->prepare("SELECT count(*) FROM `boards` WHERE `board`=?");
	$stmt->execute([strtolower($p)]);
	return $stmt->fetchColumn()!=0 ? true: false;
}

function is_page($p){
	global $pdo;
  $p=strtolower($p);
	$page=["about", "contact", "support", "donate", "faq", "advertise", "rules", "terms", "press", "legal", "feedback", "source", "help"];
	if(in_array($p, $page)){
		if($p=="support"){
			$p="donate";
		}
		if($p=="terms"){
			$p="rules";
		}
		if($p=="bug"){
			$p="feedback";
		}
    if($p=="help"){
      $p="faq";
    }
		return $p;
	}

	return false;
}

if(is_page($p)!=false){
	$p=is_page($p);
	require("np-page.php");
	die;
}


/* Some Alternate Board links */

// anime and manga
if($p=="manga" || $p=="anime"){$p="a";}

// comics board
if($p=="comics" || $p=="cartoon" || $p=="comic"){$p="co";}

// nepal board
if($p=="nepal" || $p=="nepali" || $p=="mrr" || $p=="wrr" || $p=="mwrr" || $p=="nrr" || $p=="srr" || $p=="mr" || $p=="general"){$p="np";}

// technology
if($p=="tech" || $p=="hack" || $p=="hacker" || $p=="code" || $p=="gentoo" || $p=="arch" || $p=="linux" || $p=="gnu" || $p=="t" || $p=="technology"){$p="g";}

// art
if($p=="art"){$p="lit";}

// math
if($p=="math" || $p=="science" || $p=="education" || $p=="edu"){$p="sci";}

// seusy stuffs
if($p=="hc" || $p=="sexy" || $p=="sex" || $p=="porn" || $p=="seusy" || $p=="kanda" || $p=="bob" || $p=="vagene"){$p="s";}

// video games
if($p=="vg" || $p=="videogame" || $p=="games"){$p="v";}

// meta board
if($p=="sudo" || $p=="meta" || $p=="helpline"){$p="npchan";}

// automobiles
if($p=="bike" || $p=="car" || $p=="cycle" || $p=="drive" || $p=="ride" || $p=="vehicles"){$p="auto";}

// dyi
if($p=="do" || $p=="creative"){$p="dyi";}

// hentai and adult cartoon/drawings
if($p=="ac" || $p=="hentai" || $p=="adultcartoon" || $p=="adultdrawings" || $p=="ad"){ $p="h";}

// culture and religion
if($p=="religion" || $p=="cult" || $p=="culture"){$p="cr";}

// cam and social
if($p=="cam" || $p=="introduce" || $p=="friends" || $p=="date" || $p=="meetup" || $p=="social"){ $p="soc";}

// politics
if($p=="politics"){$p="pol";}

/* Redirect to Board */
if(is_board($p)){
	  // do we have the P?
  if(!empty($_GET['p'])){
		$b_page=strtolower(trim(strip_tags($_GET['p'])));
	}
	
	require("np-board.php");
	die;
}

/* Redirect to Settings Page  */
if($p=="settings"){
	require("np-settings.php");
	die;
}

if($p=="random"){
	header("Location: /random.php");
	die;
}
/* Redirect to Moderator's Panel */
if($p=="mod" || $p=="admin"){
	header("Location: /mod/index.php");
	die;
}

// if nothing matches right, showing error page
require 'np-error.php';
die;