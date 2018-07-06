<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Pages
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file contains the pages of NPChan (about, contact, faq)
 * Editing is on /pages/<PAGE_NAME>.php
 * THERE IS NOTHING TO EDIT HERE
*/

// if page is called directly
if(!defined('AUTH')){
	header("Location: /");
	die;
}

// loading laguage files
load_language(["basic"]);

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo strip_tags($lang_basic->{$p}) ." | ".$lang_basic->title; ?></title>
	<link rel="shortcut icon" type="image/png" href="/assets/images/icon.png">
	<?php echo load_stylesheet(); ?>
	<!-- SEO Tools and Extra tools for Social Media-->
</head>
<body class="page">
<div class="max-container">
	
	<!-- Logo -->
	<div class="logo">
		<a href="/">
			<?php if($user_theme=="Dark"){ ?>
			<img src="/assets/images/logo-inverse.png" height="80px">
			<?php } else { ?>
			<img src="/assets/images/logo.png" height="80px">
			<?php } ?>
		</a>
	</div>

	<!-- Settings -->

	<div class="card <?php echo $p ?>">
		<div class="card-header bg-color blue">
			<span class="card-title"><?php echo strip_tags($lang_basic->{$p}); ?></span>
		</div>
		<?php
		    if(file_exists("pages/".strtolower($p).".php")){ require("pages/".strtolower($p).".php"); }
		    else { echo "<div class=\"page_not_found\">ERROR: Page Not Found!</div>";} 
		?>
	</div>

</div><!-- Max Container Ends Here -->


<!-- Footer -->
	<div class="footer">
		<div class="links">
			<a href="/"><?php echo $lang_basic->home; ?></a> &middot;
			<a href="/about/"><?php echo $lang_basic->about; ?></a> &middot;
			<a href="/contact"><?php echo $lang_basic->contact; ?></a> &middot;
			<a href="/faq/"><?php echo $lang_basic->faq; ?></a> &middot;
			<a href="/donate/"><?php echo $lang_basic->donate; ?></a> &middot;
			<a href="/advertise/"><?php echo $lang_basic->advertise; ?></a> &middot;
			<a href="/rules/"><?php echo $lang_basic->rules; ?></a> &middot;
			<a href="/press/"><?php echo $lang_basic->press; ?></a> &middot;
			<a href="/legal/"><?php echo $lang_basic->legal; ?></a> &middot;
			<a href="/feedback/"><?php echo $lang_basic->feedback; ?></a> &middot;
			<a href="/settings/"><?php echo $lang_basic->settings; ?></a> &middot;
			<a href="/source/"><?php echo $lang_basic->source; ?></a>
		</div>
		<p><b>&copy <?php echo $lang_basic->copyright; ?> <?php echo date("Y"); ?> - <?php echo $lang_basic->title; ?></b></p>
		<p class="copyright-alt"><small><?php echo $lang_basic->copyright_alt; ?></small></p>
	</div>

</body>
</html>