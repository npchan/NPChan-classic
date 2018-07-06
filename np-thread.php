<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Thread Detailed Page (Thread and Replies)
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file shows the thread information (all the replies)
 * Please make sure you edit it according to code on np-board.php
 * to give similar look and functionality. (The inital code is just "copy-pasted")
 * from np-board.php so you will see similar code as np-board.php
*/


// I don't know why would we need this but who cares, lets put it for safety.
// in this threads page, I am not sure from where it will be called so, its a safety
// to check if its defined or not
if(!defined('AUTH')){
	define('AUTH', true);
	require_once "np-header.php";
}

$_has_thread=0;

// redirect to homepage if thread id is missing
if(empty($_GET['id'])){
	header("Location: /");
	die;
}

if(!is_numeric($_GET['id'])){
	// thread ID is always numric so, "safety check"
	// if not matched, show a simple 404
	require_once ("np-error.php");
	die;
}

// putting in variable name
$thread=filter_text($_GET['id']);

// do thread exist?
$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=?");
$stmt->execute([$thread]);
if($stmt->fetchColumn()!=1){
	// thread doesn't exits
	require_once ("np-error.php");
	die;
}

// get board info (we don't rely on "provided" board info, we find the board info from thread id)
$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `id`=?");
$stmt->execute([$thread]);
$thread_info=$stmt->fetch();

// get board name
$p=$thread_info['board'];

// no need for board validation since this is already "known" value

// loading resources
load_language(["basic", "board"]);

// since this page is only shown only via include on np-router, board we get via $p exits.
// no steps taken to verify if board exits

$stmt=$pdo->prepare("SELECT * FROM `boards` WHERE `board`=?");
$stmt->execute([$p]);
$board_info=$stmt->fetch();	// all the information about board

if(empty($board_info)){
	// if board info is empty, considering the board as random board
	$p="b";
	// overwrite board information
	$board_info=[
		"id" => 2,
		"title" => "Random",
		"board" => "b",
		"category" => "Adult",
		"safe" => "N",
		"locked" => "N",
		"require_image" => "N"
	];
}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo "/".$p."/ - ".$board_info['title']." | ".$lang_basic->title ?></title>
	<link rel="shortcut icon" type="image/png" href="/assets/images/icon.png">
	<?php echo load_stylesheet(); ?>
	<!-- SEO Tools and Extra tools for Social Media-->
</head>
<body class="board thread<?php if($board_info['safe']=='N'){echo " nsfw"; } ?>">
<div class="header">
	<div class="top-bar">
		<!-- Float Right -->
		<span class="float-right settings">
			<a href="/settings/" class="settings_button"><?php echo $lang_basic->settings; ?></a>
		</span>

		<!-- Links (For Desktop) -->
		<span class="desktop">
			<?php
				$stmt=$pdo->query("SELECT * FROM `boards` ORDER BY `board` asc");
				$stmt->execute();
				$board_list=$stmt->fetchAll();
				$_q=1;
				foreach($board_list as $row){
					if($_q!=1){ echo ", "; } $_q++;
					echo "<a href=\"/".$row['board']."/\">".$row['board']."</a>";
				}
			?>
		</span>

		<!-- Links (For Mobile) -->
		<span class="mobile">
			<form method="get" action="/ajax.php">
				<select name="board" style="width:250px">
					<?php
						$_q=1;
						foreach($board_list as $row){
							if($_q!=1){ echo ", "; } $_q++;
							echo "<option value=\"/".$row['board']."/\">".$row['board']." -  ".$row['title']."</option>";
						}
					?>
				</select>
				<input type="submit" value="&gt;">
			</form>
		</span>

	</div> <!-- Top Bar Ends Here -->

		<!-- Logo and Board Name -->
	<div class="logo_name">
		<!-- Logo -->
		<?php if(!$user_info['random_image']){ ?>
		<div class="logo">
			<a href="/">
				<?php if($user_theme=="Dark"){ ?>
				<img src="/assets/images/logo-inverse.png" height="80px">
				<?php } else { ?>
				<img src="/assets/images/logo.png" height="80px">
				<?php } ?>
			</a>
		</div>
		<?php } else { ?>
		<div class="logo">
			<a href="/"><img src="/random.php" height="100px"></a>
		</div>
		<?php } ?>
		<!-- Logo Ends Here -->


		<!-- Big Board Name -->
		<div class="board-name">
			<div class="big-name">
				<?php echo "/".$p."/ - ".$board_info['title']; ?>
			</div>
		</div>

		<div class="clear-both"></div>
	</div>
	<!-- Board Name Ends Here -->
	<hr class="hr" />
	<!-- Create New Thread/Reply -->
	<div class="new-thread">
		<div class="show-hide-button">
			<a href="#!" onclick="$('#submit_form').toggle();$.get('/settings.php?toggle_form=true&ajax=true');" class="show-hide-link">
				<?php echo $lang_board->create_new_reply; ?>
			</a>
		</div>



		<!-- Submit Form -->
		<div class="submit_form" id="submit_form"<?php if(!$user_info['thread_box']){echo ' style="display:none";';} ?>>
				<form method="post" action="/post.php" enctype="multipart/form-data">
				<input type="hidden" name="board" value="<?php echo $p; ?>">
				<input type="hidden" name="thread" value=<?php echo $thread; ?>>
				<textarea name="text" placeholder="<?php echo $lang_board->message; ?>"></textarea>
				<input type="file" name="image" placeholder="Upload an Image...">
				<input type="submit" name="submit" value="<?php echo $lang_board->post; ?>">
				<a href="#!" onclick="$('#submit_form').toggle();" class="close_button"><?php echo $lang_board->close; ?></a>
			</form>

      <ul class="posting_info"><?php echo $lang_board->posting_info; ?></ul>
		</div>
		<!-- Submit Form Ends Here -->
	</div>
	<!-- <hr class="hr" /> -->
	<?php /*
	<!-- Blotter/Site News (Not Ready Yet)-->
	<div class="blotter">
  	<div class="blotter-item"<?php if(!$user_info['blotter']){echo ' style="display:none";';} ?>>
      <?php
        $stmt=$pdo->prepare("SELECT * FROM `news` ORDER BY `timestamp` DESC LIMIT 3");
        $stmt->execute([1]);
        foreach($stmt->fetchAll() as $row){
			       "<p>".date("d/m/Y").": ".$row['title']."</p>";
        }
        ?>
		</div>
		<div class="blotter-hide-button">
			<a href="/ajax.php?toggle_blotter=true&board=<?php echo $p; ?>" class="blotter-hide">
				<?php echo $lang_board->blotter_hide; ?>
			</a>
			<a href="/blotter/" class="blotter-all">
				<?php echo $lang_board->blotter_all; ?>
			</a>
		</div>
	</div>
	<!-- Blotter Data Ends Here -->

	*/ ?>
</div>
<!-- Header Ends Here -->
<!-- Board Actions -->
<div class="board-actions">
	<div class="board-actions-inner">
		<!-- Board Actions (links) -->
		<span class="links">
			<a href="/<?php echo $p; ?>/" class="back-home">
				<?php echo $lang_board->back_board; ?>
			</a>
			<a href="#bottom">
				<?php echo $lang_board->bottom; ?>
			</a>
			<a href="/<?php echo $p; ?>/<?php echo $thread; ?>/">
				<?php echo $lang_board->refresh; ?>
			</a>
		</span>
		<!-- Board Actions Ends Here -->

		<!-- Board Sorting Options -->
		<!-- Currently Hidden because feature is not complete yet -->
		<span class="sorting">
			<span class="replies" title="<?php echo $lang_board->t_replies; ?>">R: <?php echo $thread_info['replies']; ?></span>
			<span class="separator"> / </span>
			<span class="images" title="<?php echo $lang_board->t_images; ?>">I: <?php echo $thread_info['images']; ?></span>
			<span class="separator"> / </span>
			<span class="posters" title="<?php echo $lang_board->t_posters; ?>">P: <?php
				// show number of unique posters

					/* I am using this method since GROUP BY didn't worked well on my PC */
					$all_posters=array();
					$posters=0;
					$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `thread_reply`=? OR `id`=?");
					$stmt->execute([$thread, $thread]);
					$fetch_all=$stmt->fetchAll();
					foreach($fetch_all as $ap){
						if(!in_array($ap['user_id'], $all_posters)){
							$posters++;
							$all_posters=array_merge($all_posters, [$ap['user_id']]);
						}
					}

					if($posters==0){
						$posters=1;
					}

				echo $posters;
			?>
			</span>
		</span>
		<!-- Board Sorting Ends Here-->

		<!-- Clearing Floating Area (Required for some themes to fix certain bugs of float:left) -->
		<div class="clear-both"></div>
	</div>

</div>
<!-- Board Actions Ended Here -->

<div class="thread_listing">

<?php

$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `board`=? AND `thread_start`='Y' ORDER BY `pinned` DESC,`bumped_on` DESC LIMIT 10");
$stmt->execute([$p]);
$_board=$stmt->fetchAll();


// foreach($thread_info as $row){
$row=$thread_info;
?>
	<div class="post p<?php echo $row['id']; ?>" id="p<?php echo $row['id']; ?>" data-state="shown">
<?php
	// get files information
	$file=json_decode($row['files']);

	// is the file deleted?
	if($file->original_name=="DELETED"){
		echo '<img src="/cdn/images/deleted.png" class="img_deleted">';
	}

	$no_image=false;
	// is the file empty? if not, show them
	if($file->original_name!="EMPTY"){
		// trim filename
		if(strlen($file->original_name)>20){
			$file_name_all=explode(".", $file->original_name);
			$array_size=sizeof($file_name_all)-1;
			$file_name=substr($file->original_name, 0, 17)."...";
			$file_name=$file_name.".".$file_name_all[$array_size];
		}
		else {
			$file_name=$file->original_name;
		}
?>

		<div class="file_info">
			<?php echo $lang_board->file; ?>
			<span class="original_name">
				<a href="/<?php echo $file->path.$file->name; ?>" title="<?php echo $file->original_name; ?>">
					<?php echo $file_name; ?>
				</a>
			</span>
			<span class="file_size">
				(<?php echo f_size($file->file_size) ?>, <?php echo $file->file_height."x".$file->file_width; ?>)
			</span>
		</div>

		<div class="image" id="image_<?php echo $row['id']; ?>">

			<a href="/<?php echo $file->path.$file->name; ?>" title="<?php echo $lang_board->view_full_image; ?>" id="img_link_<?php echo $row['id']; ?>" onclick="toggle_image('<?php echo $row['id']; ?>');" class="full_image">
				<img class="img" id="img_<?php echo $row['id'] ?>" src="/<?php echo $file->cache; ?>" data-cache="<?php echo $file->cache; ?>" data-full="<?php echo $file->path.$file->name; ?>" data-state="thumbnail" data-type="<?php echo $file->type; ?>">


				<?php if($file->type=="video"){ echo '<div style="display:none!important" class="video_holder" id="vid_holder_'.$row['id'].'"><a href="#!" class="video_min" data-for="'.$row['id'].'" onclick="toggle_image(\''.$row['id'].'\')">➖ '.$lang_board->close_video.'</a><video class="img" id="vid_'.$row['id'].'" style="width:100%" controls loop><source src="/'.$file->path.$file->name.'" type="video/'.$file->ext.'">Ops! Your browser can\'t play this video directly. <a href="/'.$file->path.$file->name.'">Download</a></video></div>'; } ?>
			</a>

		</div>
		<?php } else { $no_image=true; } /* END THE loop */ ?>
		<div class="post-header <?php if($no_image){echo "no-img"; } ?>">
			<span class="post-title"><?php echo $row['title']; ?></span>

			<!-- Username -->
			<span class="user-name">
				<?php echo empty($row['username']) ? "Anonymous" : $row['username']; ?>
			</span>

			<!-- Posted Date and Time -->
			<span class="posted_time" title="<?php echo ago($row['time_stamp'])." ".$lang_board->ago; ?>">
				<?php echo date("d/m/Y (D) h:i:s A", $row['time_stamp']); ?>
			</span>

			<?php
				if($row['pinned']=="Y"){
					echo "<span class=\"thread-pinned\" title=\"".$lang_board->pinned."\">📌</span>";
				}
				if($row['locked']=="Y"){
					echo "<span class=\"thread-locked\" title=\"".$lang_board->locked."\">🔒</span>";
				}
			?>

			&middot;

			<!-- Thread ID -->
			<span class="thread-id">No: <a href="#!" onclick="quick_reply('<?php echo $row['id']; ?>');"><?php echo $row['id']; ?></a></span>

			&middot;

			<!-- Quick Reply -->
			<span class="quick_reply_link">
				[<a href="#!" onclick="quick_reply('<?php echo $row['id']; ?>');">
					<?php echo $lang_board->quick_reply; ?>
				</a>]
			</span>

			<span href="post-options">
				 <span class="dr">
				  <a href="#!" onclick="$('#ar<?php echo $row['id']; ?>').toggleClass('rotate');$('#dp_<?php echo $row['id']; ?>').toggleClass('hide');">
				  	<span id="ar<?php echo $row['id']; ?>" class="dropdown_text">▶</span>
				  </a>
				  <span id="dp_<?php echo $row['id']; ?>" class="dropdown-content hide">
				    <a href="#!" onclick="quick_reply('<?php echo $row['id']; ?>');"><?php echo $lang_board->quick_reply; ?></a>
			    <a href="/report.php?id=<?php echo $row['id']; ?>"><?php echo $lang_board->report; ?></a>
			    <a href="/delete.php?id=<?php echo $row['id']; ?>"><?php echo $lang_board->delete; ?></a>
				  </span>
				</span>
			</span>
			<span id="pq_<?php echo $row['id']; ?>" class="post_quotes">
				<?php /*
					   * I disabled this feature for now, because I can't do it via PHP nicely
					   * Will add back later

					$all_threads=array();
					$stmt=$pdo->prepare("SELECT * FROM `cite` WHERE `to`=?");
					$stmt->execute([$row['id']]);
					foreach($stmt->fetchAll() as $citex){
						if(!in_array($citex['by'], $all_threads)){
							$all_threads=array_merge($all_threads, [$citex['by']]);
							$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=? AND `thread_reply`=?");
							$stmt->execute([$citex['by'], $row['id']]);
							if($stmt->fetchColumn()==1){
								echo "<a href=\"/".$row['board']."/".$row['id']."/#".$citex['by']."\" id=\"c".$citex['by']."\" data-id=\"".$citex['by']."\" class=\"cite\" data-board=\"".$row['board']."\" onclick=\"highlight('".$citex['by']."');\">&gt;&gt;".$citex['by']."</a>, ";
							}
						}
					}

					*/
				?>

			</span>
		</div>

		<!-- Post Body -->
		<div class="post-body">
			<?php echo $row['body']; ?>
		</div>

		<!-- Clearing Floating Image -->
		<div class="clear-both"></div>
</div>

<!--
	******************************** REPLIES ****************************
-->
<?php

$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `thread_reply`=?");
$stmt->execute([$row['id']]);
$total_replies=$stmt->fetchColumn();


$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `thread_reply`=? ORDER BY `time_stamp` ASC");
$stmt->execute([$row['id']]);

$_thread=$stmt->fetchAll();

foreach($_thread as $row2){ ?>
	<div class="reply p<?php echo $row2['id']; ?>" id="p<?php echo $row2['id']; ?>" data-state="shown">
<?php
	// get files information
	$file=json_decode($row2['files']);

	// is the file deleted?
	if($file->original_name=="DELETED"){
		echo '<img src="/cdn/images/deleted.png" class="img_deleted">';
	}

	$no_image=false;

?>
	<!-- HEADER INFO -->
	<div class="post-header no-img">
		<span class="post-title"><?php echo $row2['title']; ?></span>
		<?php /* if(!empty($row['title'])){ echo "▶"; } */ ?>

		<!-- Username -->
		<span class="user-name">
			<?php echo empty($row2['username']) ? "Anonymous" : $row2['username']; ?>
		</span>

		<!-- Posted Date and Time -->
		<span class="posted_time" title="<?php echo ago($row2['time_stamp'])." ".$lang_board->ago; ?>">
			<?php echo date("d/m/Y (D) h:i:s A", $row2['time_stamp']); ?>
		</span>

		&middot;

		<!-- Thread ID -->
		<span class="thread-id">No: <a href="#!" onclick="quick_reply('<?php echo $row2['id']; ?>');"><?php echo $row2['id']; ?></a></span>

		&middot;
		<span href="post-options">
			 <span class="dr">
			  <a href="#!" onclick="$('#ar<?php echo $row2['id']; ?>').toggleClass('rotate');$('#dp_<?php echo $row2['id']; ?>').toggleClass('hide');">
			  	<span id="ar<?php echo $row2['id']; ?>" class="dropdown_text">▶</span>
			  </a>
			  <span id="dp_<?php echo $row2['id']; ?>" class="dropdown-content hide">
			  	<a href="#!" onclick="quick_reply('<?php echo $row2['id']; ?>');"><?php echo $lang_board->quick_reply; ?></a>
			    <a href="/report.php?id=<?php echo $row2['id']; ?>"><?php echo $lang_board->report; ?></a>
			    <a href="/delete.php?id=<?php echo $row2['id']; ?>"><?php echo $lang_board->delete; ?></a>
			  </span>
			</span>
		</span>

		<!-- Post Replies -->
		<span id="pq_<?php echo $row2['id']; ?>" class="post_quotes">
				<?php
					$all_threads=array();
					$stmt=$pdo->prepare("SELECT * FROM `cite` WHERE `to`=?");
					$stmt->execute([$row2['id']]);
					foreach($stmt->fetchAll() as $citex){
						if(!in_array($citex['by'], $all_threads)){
							$all_threads=array_merge($all_threads, [$citex['by']]);
							$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=? AND `thread_reply`=?");
							$stmt->execute([$citex['by'], $row2['id']]);
							if($stmt->fetchColumn()==1){
								echo "<a href=\"/".$row['board']."/".$row['id']."/#".$citex['by']."\" id=\"c".$citex['by']."\" data-id=\"".$citex['by']."\" class=\"cite\" data-board=\"".$row['board']."\" onclick=\"highlight('".$citex['by']."');\">&gt;&gt;".$citex['by']."</a>, ";
							}
						}
					}
				?>

			</span>
	</div>

<?php
	// is the file empty? if not, show them
	if($file->original_name!="EMPTY"){
		// trim filename
		if(strlen($file->original_name)>20){
			$file_name_all=explode(".", $file->original_name);
			$array_size=sizeof($file_name_all)-1;
			$file_name=substr($file->original_name, 0, 17)."...";
			$file_name=$file_name.".".$file_name_all[$array_size];
		}
		else {
			$file_name=$file->original_name;
		}
?>

		<?php if(empty($raw2['body'])){ ?>
			<div class="file_info on-top">
				<?php echo $lang_board->file; ?>
				<span class="original_name">
					<a href="/<?php echo $file->path.$file->name; ?>" title="<?php echo $file->original_name; ?>">
						<?php echo $file_name; ?>
					</a>
				</span>
				<span class="file_size">
					(<?php echo f_size($file->file_size) ?>, <?php echo $file->file_height."x".$file->file_width; ?>)
				</span>
			</div>
		<?php } ?>


		<div class="image<?php if(empty($raw2['body'])){ echo " no-body"; } ?>" id="image_<?php echo $row2['id']; ?>">

			<a href="/<?php echo $file->path.$file->name; ?>" title="<?php echo $lang_board->view_full_image; ?>" id="img_link_<?php echo $row2['id']; ?>" onclick="toggle_image('<?php echo $row2['id']; ?>');"  class="full_image">
				<img class="img" id="img_<?php echo $row2['id'] ?>" src="/<?php echo $file->cache; ?>" data-cache="<?php echo $file->cache; ?>" data-full="<?php echo $file->path.$file->name; ?>" data-state="thumbnail" data-type="<?php echo $file->type; ?>">


				<?php if($file->type=="video"){ echo '<div style="display:none!important" class="video_holder" id="vid_holder_'.$row2['id'].'"><a href="#!" class="video_min" data-for="'.$row2['id'].'" onclick="toggle_image(\''.$row2['id'].'\')">➖ '.$lang_board->close_video.'</a><video class="img" id="vid_'.$row2['id'].'" style="width:100%" controls loop><source src="/'.$file->path.$file->name.'" type="video/'.$file->ext.'">Ops! Your browser can\'t play this video directly. <a href="/'.$file->path.$file->name.'">Download</a></video></div>'; } ?>
			</a>

		</div>
		<?php } else { $no_image=true; } /* END THE loop */ ?>

		<?php if(!empty($raw2['body'])){ ?>
		<div class="file_info">
			<?php echo $lang_board->file; ?>
			<span class="original_name">
				<a href="/<?php echo $file->path.$file->name; ?>" title="<?php echo $file->original_name; ?>">
					<?php echo $file_name; ?>
				</a>
			</span>
			<span class="file_size">
				(<?php echo f_size($file->file_size) ?>, <?php echo $file->file_height."x".$file->file_width; ?>)
			</span>
		</div>
		<?php } ?>

		<!-- Post Body -->
		<div class="post-body">
			<?php echo $row2['body']; ?>
		</div>

		<!-- Clearing Floating Image -->
		<div class="clear-both"></div>
</div>
<?php
	$_has_thread=1;

	} // foreach $_board as $row


if($_has_thread!=1){
	echo "<div class=\"no_reply\">".$lang_board->no_reply."</div>";
 } ?>


</div><!-- Thread Listing Ends Here -->


<!-- FOOTER -->
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



<!-- Quick Reply -->
<div class="quick_reply" id="quickreply">
	<div class="quick_header">
		<?php echo $lang_board->replying_to; ?>
		<span id="qr_id"></span>
		<span class="float-right">
			<a href="#!" onclick="close_qr();">❎</a>
		</span>
	</div>
	<div class="quick_body">
		<form method="post" action="/post.php" enctype="multipart/form-data">
			<input type="hidden" name="thread" value="<?php echo $thread; ?>" id="qr_thread">
			<textarea name="text" placeholder="<?php echo $lang_board->message; ?>" id="qr_text"></textarea>
			<input type="file" name="image" placeholder="Upload an Image..." id="qr_file">
			<div class="np-row">
				<div class="np-col s7">
					<label for="qr_adult"><input type="checkbox" id="qr_adult" name="adult" value="yes" <?php if($board_info['safe']=="N"){echo "checked disabled"; }?>> <?php echo $lang_board->qr_adult; ?></label>
					&nbsp;
					<label for="qr_spoiler"><input type="checkbox" id="qr_spoiler" name="spoiler" value="yes"> <?php echo $lang_board->qr_spoiler; ?></label>
				</div>
				<div class="np-col s5" align="right">
					<input type="submit" name="submit" id="qr_submit" value="<?php echo $lang_board->post; ?>">
				</div>
			</div>
		</form>
	</div>
	<div class="quick_error" id="qerror" style="display:none"></div>
</div>
<div class="float" style="position:fixed;bottom:0;left:0"></div>
<div style="display: none!important"><div class="preview" style="display: none!important"></div></div>
<div id="slide_notify" style="display:none"></div>

<!-- All the JS we need -->
<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/js/boards.js?v=1.1"></script>
</body>
</html>