<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Index File
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * Home Page (Contains Links to All Boards, Trending Threads)
*/

define("AUTH", true);
require_once("np-header.php");
load_language(["basic", "homepage"]);

?><!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $lang_basic->title." | ".$lang_basic->tagline; ?></title>
	<link rel="shortcut icon" type="image/png" href="/assets/images/icon.png">
	<?php echo load_stylesheet(); ?>
	<meta property="og:type" content="website">
	<meta property="og:title" name="title" content="NPChan | The Nepali Internet">
	<meta property="og:site_name" name="site_name" content="NPChan">
	<meta property="og:description" name="description" content="NPChan is Nepal's first and only anonymous discussion (image)board where anyone can create new post or add new reply without revealing their identity. NPChan doesn't require user registration and will never ask for your personal information.">
	<meta property="og:url" name="url" content="https://npchan.com/">
	<meta property="og:locale" name="locale" content="en_US">
	<meta property="og:image" name="image" content="https://npchan.com/assets/images/npchan.png">
	<meta property="og:image:height" name="image:height" content="516">
	<meta property="og:image:width" name="image:width" content="1000">
	<meta property="og:image:secure_url" name="image:secure_url" content="https://npchan.com/assets/images/npchan.png">
	<meta property="og:image:type" name="image:type" content="image/png">
</head>
<body class="home">
<div class="max-container">
	
	<!-- Logo -->
	<div class="logo">
		<?php if($user_theme=="Dark"){ ?>
		<img src="assets/images/logo-inverse.png" height="80px">
		<?php } else { ?>
		<img src="assets/images/logo.png" height="80px">
		<?php } ?>
	</div>


	<?php if($user_info['site_intro']){ ?>
	<!-- Intro -->
	<div class="card">
		<div class="card-header bg-color red">
			<span class="card-title"><?php echo $lang_homepage->what_is; ?></span>
			<span class="float-right close"><a href="ajax.php?toggle=true&item=site_intro">✖ <?php echo $lang_homepage->close; ?></a></span></div>
		<div class="card-body"><?php echo $lang_homepage->intro; ?></div>
	</div>
	<?php } ?>


	 <!-- Boards List -->
	<div class="card">
    <div class="card-header bg-color blue">
      <span class="card-title"><?php echo $lang_homepage->all_boards; ?></span>
    </div>
    <div class="card-body">
		<div class="np-row board-list">
			<div class="np-col l3 m3 s6">
				<b class="board-intro-title"><?php echo $lang_basic->cat->interests; ?></b><br/>
				<?php
					$stmt=$pdo->query("SELECT * FROM `boards` WHERE `category`='Interests' ORDER BY `board` ASC");
					$stmt->execute();
		 				$board_list_1=$stmt->fetchAll();

					foreach($board_list_1 as $row){
						echo "<a href=\"/".$row['board']."/\" title=\"/".$row['board']."/\">".$row['title']."</a><br/>";
					}
				?>
			</div>
			<div class="np-col l3 m3 s6">
				<b class="board-intro-title"><?php echo $lang_basic->cat->creative; ?></b><br/>
				<?php
					$stmt=$pdo->query("SELECT * FROM `boards` WHERE `category`='Creative' ORDER BY `board` ASC");
					$stmt->execute();
		 				$board_list_1=$stmt->fetchAll();

					foreach($board_list_1 as $row){
						echo "<a href=\"/".$row['board']."/\" title=\"/".$row['board']."/\">".$row['title']."</a><br/>";
					}
				?>
				<br/>
				<b class="board-intro-title"><?php echo $lang_basic->cat->others; ?></b><br/>
				<?php
					$stmt=$pdo->query("SELECT * FROM `boards` WHERE `category`='Others' ORDER BY `board` ASC");
					$stmt->execute();
		 				$board_list_1=$stmt->fetchAll();

					foreach($board_list_1 as $row){
						echo "<a href=\"/".$row['board']."/\" title=\"/".$row['board']."/\">".$row['title']."</a><br/>";
					}
				?>
			</div>
				<div class="np-col l3 m3 s6">
					<b class="board-intro-title"><?php echo $lang_basic->cat->community; ?></b><br/>
					<?php
						$stmt=$pdo->query("SELECT * FROM `boards` WHERE `category`='Community' ORDER BY `board` ASC");
						$stmt->execute();
			 				$board_list_1=$stmt->fetchAll();

						foreach($board_list_1 as $row){
							echo "<a href=\"/".$row['board']."/\" title=\"/".$row['board']."/\">".$row['title']."</a><br/>";
						}
					?>
				</div>
			<div class="np-col l3 m3 s6">
				<div class="np-row">
					<div class="np-col l12 m12 s12">
						<b class="board-intro-title adult-warning"><?php echo $lang_basic->cat->misc; ?></b><br/>
						<?php
							$stmt=$pdo->query("SELECT * FROM `boards` WHERE `category`='Misc.' ORDER BY `board` ASC");
		    				$stmt->execute();
		   	 				$board_list_1=$stmt->fetchAll();

		    				foreach($board_list_1 as $row){
		        				echo "<a href=\"/".$row['board']."/\" title=\"/".$row['board']."/\">".$row['title']."</a><br/>";
		    				}
						?><br/>
					</div>
					<div class="np-col l12 m12 s12">
						<b class="board-intro-title adult-warning"><?php echo $lang_basic->cat->adult; ?></b><br/>
						<?php
							$stmt=$pdo->query("SELECT * FROM `boards` WHERE `category`='Adult' ORDER BY `board` ASC");
		    				$stmt->execute();
		   	 				$board_list_1=$stmt->fetchAll();

		    				foreach($board_list_1 as $row){
		        				echo "<a href=\"/".$row['board']."/\" title=\"/".$row['board']."/\">".$row['title']."</a><br/>";
		    				}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
  	</div>


  	<!-- Board List Ends Here -->


  		<?php if($user_info['board_intro']){ ?>
		<div class="card trending" id="intro_boards">
			<div class="card-header bg-color red">
				<span class="card-title"><?php echo $lang_homepage->intro_boards; ?></span>
				<span class="float-right close"><a href="ajax.php?toggle=true&item=board_intro">✖ <?php echo $lang_homepage->close; ?></a></span></div>
				<div class="card-body">
					<p><?php echo $lang_homepage->intro_boards_alt; ?></p>
				</div>
				<div class="card-footer">
					<div class="table">
	 			 	<div class="table-row">
	 					 <div class="table-cell" width="70px">
							 <img src="/assets/images/cover/Nepal.png" width="60px">
						 </div>
						 <div class="table-cell cell-2">
							 <div><a href="/np/"><b>Nepal</b></a></div>
							 <p><?php echo $lang_homepage->intro_boards_nepal; ?></p>
						 </div>
					 	</div>
					</div>
				</div>


				<div class="card-footer">
					<div class="table">
	 			 	<div class="table-row">
	 					 <div class="table-cell" width="70px">
							 <img src="/assets/images/cover/Truth.jpg" width="60px">
						 </div>
						 <div class="table-cell cell-2">
							 <div><a href="/truth/"><b>Confession</b></a></div>
							 <p><?php echo $lang_homepage->intro_boards_truth; ?></p>
						 </div>
					 	</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="table">
	 			 	<div class="table-row">
	 					 <div class="table-cell" width="70px">
							 <img src="/assets/images/cover/Tech.jpg" width="60px">
						 </div>
						 <div class="table-cell cell-2">
							 <div><a href="/g/"><b>Technology</b></a></div>
							 <p><?php echo $lang_homepage->intro_boards_tech; ?></p>
						 </div>
					 </div>
					</div>
				</div>

				<div class="card-footer">
					<p><?php echo $lang_homepage->quick_start_lang; ?></p>
				</div>
			</div>

	 <?php } ?>
	 <!-- Suggestions Ends Here -->


	 
<!-- TRENDING THREADS -->
	<div class="card trending">
	 <div class="card-header bg-color blue">
		 <span class="card-title"><?php echo $lang_homepage->trending_threads; ?></span>
		 <span class="float-right close"><?php
		 if($user_nsfw){
			 echo '<a href="ajax.php?toggle_nsfw=hide">'.$lang_homepage->hide_nsfw;
			 $and="";
		 } else{
			 echo '<a href="ajax.php?toggle_nsfw=show">'.$lang_homepage->show_nsfw;
			 $and="AND (`board`!='b' AND `board`!='h' AND `board`!='hm' AND `board`!='s') ";
		 } ?></a>
		</span>
	 </div>
	 <?php
	 	$stmt=$pdo->query("SELECT * FROM `threads` WHERE `thread_start`='Y' ".$and." ORDER BY `thread_reply` DESC,`bumped_on` DESC, `time_stamp` DESC LIMIT 8");
		$stmt->execute();
		foreach($stmt->fetchAll() as $row){
			$is_empty="Nope";

			// get the number of images on the reply
			$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `thread_reply`=? AND `has_image`='Y'");
			$stmt->execute([$row['id']]);
			$total_img_count=$stmt->fetchColumn();
			$image_title="[NO TEXT]";
			$file=json_decode($row['files']);
	 ?>
	 <div class="card-footer">
			 <div class="table">
			 <div class="table-row">
					 <div class="table-cell" width="70px">
						 <?php if($file->original_name!="EMPTY" && $file->original_name!="DELETED"){
								$image_title=$file->original_name;
								$img_url=$file->cache;
							 ?>
						 		<img src="<?php echo $img_url; ?>" width="60px">
					 	 <?php } else { ?>
							 <img src="/assets/images/icon.png" width="60px">
						 <?php } ?>
					 </div>
					 <div class="table-cell cell-2">
						 <div><a href="/<?php echo $row['board']."/".$row['id']; ?>/"><?php
						 if(empty($row['title'])){
							 if(empty($row['body_raw']) || truncate($row['body_raw'], 50, false)=="..."){
								 echo $image_title;
							 }
							 else{
								 echo truncate($row['body_raw'], 50, false);
							 }
						 }
						 elseif(truncate($row['title'])=="..."){
							 echo $image_title;
						 }
						 else{
							 echo truncate($row['title'], 50, false);
						 }
						 ?></a></div>
					   <p><small><b>/<?php echo $row['board']; ?>/</b> - <?php echo $row['replies']; ?> Replies, <?php echo $total_img_count; ?> images, last update: <?php echo ago($row['bumped_on']); ?></small></p>
					 </div>
			 </div>
		 </div>
	 </div>
 <?php } if(empty($is_empty)){ ?>
 	<div class="card-body"><p><?php echo $lang_homepage->empty_trending; ?></p></div>
 <?php } ?>
 </div>



 	<div class="card">
 		<div class="card-header"><h3>NPChan is Updating!</h3></div>
 		<div class="card-body">
 			<p>We are constantly updating NPChan day by day to complete all the missing feature as well as to include the features requested by users. If you experience any problems please visit our <a href="/npchan/">NPChan Meta Board</a> (/npchan/) to report any bug or if for feature request!</p>
 		</div>
 	</div>


 	<!-- Site Stats -->
	<div class="card stats center">
		<div class="card-body">
			<?php
				// calculate total folder size
				function folderSize ($dir){ $size = 0; $f=0; foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) { $size += is_file($each) ? filesize($each) : folderSize($each); $f++; } return ["size"=>$size, "count"=>$f]; }

				// get total number of threads & Last Activity
				$stmt=$pdo->query("SELECT `id`,`time_stamp` FROM `threads` ORDER BY `id` DESC LIMIT 1");
				$stmt->execute();
				$total_thread_count=$stmt->fetch();

				$total_threads=$total_thread_count['id'];

				$last_activity=$total_thread_count['time_stamp'];
				
				$folder_info=folderSize("cdn/images");
				$folder_size=$folder_info['size'];
				$file_count=$folder_info['count'];

				echo "<span class=\"stat\">".$lang_homepage->total_threads." ".$total_threads."</span><span class=\"stat-divider\"> | </span>";
				echo "<span class=\"stat\">".$lang_homepage->last_activity." ".ago($last_activity)." ago</span><span class=\"stat-divider\"> | </span>";
				echo "<span class=\"stat\">".$lang_homepage->file_count." ".$file_count." (".f_size($folder_size).")</span>";

			?>
		</div>
	</div>



	</div> <!-- BODY CONTAINER -->

	<div class="footer">
		<div class="links">
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
