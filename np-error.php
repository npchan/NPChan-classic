<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Error Page
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * The most flexible error page ever created! (by me)
 *
*/

// if direct access, define auth and include header / language files
if(!defined("AUTH")){
	define("AUTH", true);
	require_once("np-header.php")
}

if(empty($lang_basic))
	load_language("basic");

if(empty($title) && empty($alt) && empty($error))
	$error=404;
else
	$error=NULL;

if(empty($title)){
	$title="404 - Not Found";
}
// if included via files
if(!empty($error) || !empty($_GET['error'])){
	$error=404;
	if(!empty($_GET['error']))
		$error=$_GET['error'];

	if(!in_array($error, [400, 403, 404, 408, 500]))
	$title=$error." - ".$lang_basic->error->{$error}->title;
	$alt=$lang_basic->error->{$error}->alt;
}


?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $title ." | ".$lang_basic->title; ?></title>
	<link rel="shortcut icon" type="image/png" href="/assets/images/icon.png">
	<style type="text/css">
		body{background:#ffc7c7;padding:0;margin:0;color:#444}
		.container{margin:auto;max-width:900px;background:#fff;box-shadow:0 4px 8px 0 rgba(0,0,0,.2),0 6px 20px 0 rgba(0,0,0,.19)}
		.header-stripe{text-align:center;vertical-align:middle;background-color:#fc5252;padding:10px}
		.error-title{border-bottom:1px solid #ffd2d2;padding:10px 30px}
		.error-title h4{font-size:35px;margin:0;padding:0;color:#444}h4{display:inline-block;color:#fff;font-family:serif;text-shadow:.03em .03em 0 hsla(230,40%,50%,1)}
		.error-msg{padding:20px 30px;background-color:#fff}
		.error-alt{font-size:20px}
		.contact{border-top:1px solid #ffd2d2;margin-top:5px;padding:10px;text-align:center}
		.contact-text{padding-bottom:10px}
		.contact-text span{border-bottom:3px solid #ffd2d2;padding:0 10px;font-weight:700;text-transform:uppercase}
		.contact-links{padding-bottom:10px}
		.contact-links a{color:grey;text-decoration:none}
		.contact-links a:hover{color:#fc5252}
		.credits{background:#fc5252;text-align:center;padding:20px;color:#fff}
		.credits a{color:#f4f4f4;text-decoration:none}
		.credits a:hover{text-decoration:underline}
		.logo{height:50px}
		@media(min-width:600px){
			*{font-size:102%}
			.logo{height:60px}
		}
		@media(min-width:900px){
			*{font-size:103%}
			.container{border-radius:5px;margin-top:40px}
			.logo{height:70px}
		}
		.copyright{font-weight:700;margin-bottom:5px}
	</style>

	<!-- SEO Tools and Extra tools for Social Media-->
</head>
<body>
<div class="container">
	<div class="header-stripe">
		<a href="/"><img src="/assets/images/error-logo.png" class="logo" alt="NPChan Logo"></a>
	</div>

	<div class="error-title">
		<h4><?php echo $title ?></h4>
	</div>
	<div class="error-msg">
		<div class="error-alt"><?php echo $alt; ?></div>
		<div class="error-suggest">
				<p><?php echo $lang_basic->error->is_error; ?></p>
		</div>
	</div>
	<div class="contact">
		<div class="contact-text">
			<span><?php echo $lang_basic->error->report_error; ?> </span>
		</div>
		<div class="contact-links">
			Facebook: <a href="http://facebook.com/<?php echo $social['facebook']; ?>">@<?php echo $social['facebook']; ?></a> &middot;
			Twitter: <a href="http://twitter.com/<?php echo $social['twitter']; ?>">@<?php echo $social['twitter']; ?></a> &middot;
			(+977) 98650-36410
		</div>
	</div>
	<div class="credits">
		<div class="copyright">&copy; <?php echo date("Y"); ?> - NPChan</div>
	</div>
</div>

</body>
</html>
