<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Settings Page
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file contains the settings for NPChan
 * This is the HTML part, saving settings is at settings.php
*/

// if page is called directly
if(!defined('AUTH')){
	header("Location: /");
	die;
}

// loading laguage files
load_language(["basic", "settings"]);

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $lang_basic->settings." | ".$lang_basic->title; ?></title>
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

		<div class="card settings">
		<div class="card-header bg-color blue">
			<span class="card-title"><?php echo $lang_settings->settings; ?></span>
			</div>
		<div class="card-body"><?php echo $lang_settings->settings_alt; ?></div>
		
		<div class="card-footer" id="1">
			<div class="title">1. <?php echo $lang_settings->language; ?></div>
			<p class="alt"><?php echo $lang_settings->language_alt; ?></p>
			<p>(Translation isn't complete yet, but feel free to try! Please report translation error if you found any!)</p>
			<div class="option">
				<?php
					foreach($ALL_LANGUAGES as $row){
						echo "<div class=\"link\"><a href=\"/settings.php?set_lang=".$row."\">".$row."</a>";
						if($row=="English") { echo " (".$lang_settings->default.")"; }
						if($user_info['language']==$row){echo " (Current)";}
						echo "</div>";
					}
				?>
			</div>
		</div>

		<div class="card-footer" id="2">
			<div class="title">2. <?php echo $lang_settings->theme; ?></div>
			<p class="alt"><?php echo $lang_settings->theme_alt; ?></p>
			<div class="option">
				<?php 
					foreach($ALL_THEMES as $row){
						echo "<div class=\"link\"><a href=\"/settings.php?set_theme=".$row."\">".$row."</a>";
						if($row=="Default") { echo " (".$lang_settings->default.")"; }
						if($user_info['theme']==$row){echo " (Current)";}

						echo "</div>";

					}
				?>
			</div>
		</div>

		<div class="card-footer" id="3">
			<div class="title">3. <?php echo $lang_settings->view_nsfw; ?></div>
			<p class="alt"><?php echo $lang_settings->view_nsfw_alt; ?></p>
			<div class="option">
				<div class="link"><a href="/settings.php?view_nsfw=true"><?php echo $lang_settings->show; ?></a> (<?php echo $lang_settings->default; ?>)</div>
				<div class="link"><a href="/settings.php?view_nsfw=false"><?php echo $lang_settings->hide; ?></a></div>
			</div>
		</div>

		<div class="card-footer" id="4">
			<div class="title">4. <?php echo $lang_settings->random_logo; ?></div>
			<p class="alt"><?php echo $lang_settings->random_logo_alt; ?></p>
			<div class="option">
				<div class="link"><a href="/settings.php?set_random=yes"><?php echo $lang_settings->show; ?></a> (<?php echo $lang_settings->default; ?>)</div>
				<div class="link"><a href="/settings.php?set_random=no"><?php echo $lang_settings->hide; ?></a></div>
			</div>
		</div>


		<div class="card-footer" id="5">
			<div class="title">5. <?php echo $lang_settings->npchan_x; ?></div>
			<p class="alt"><?php echo $lang_settings->npchan_x_alt; ?></p>
			<div class="option">
				<div class="link"><a href="/settings.php?npchan_x=true"><?php echo $lang_settings->show; ?></a> (<?php echo $lang_settings->default; ?>)</div>
				<div class="link"><a href="/settings.php?npchan_x=false"><?php echo $lang_settings->hide; ?></a></div>
			</div>
		</div>

		<div class="card-footer">Settings are under development! Will be completed sortly!</div>

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
