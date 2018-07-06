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
 * This file contains all the functions to create new thread and
 * for creating new reply. All upload functions are also here.
 * ------------------------------------------
 * INDEX
 * $_POST['submit']		->		Submit Button Press?
 * $_POST['ajax']		->		Ajax Request? (For Error Response and Success Output)
 * $_POST['text']		->		Message
 * $_POST['image']		->		Image File
 * $_POST['board']		->		Board Link
 * $_POST['thread']		->		Thread Link (For Reply)
 * $_POST['subject']	->		Subject
 * $_POST['options']	->		Post Option
 * $_POST['adult']		->		Is Adult Content?
 * $_POST['spoiler']	->		Is Spoiler Content?
 * -------------------------------------------
 *
*/


define("AUTH", true);
require_once("np-header.php");
$_POST_PAGE=true;	// for error page (just leave this as it is)
load_language("basic");


// is this an ajax request?
$is_ajax=false;
if(!empty($_POST['ajax']) || !empty($_GET['ajax'])){
	$is_ajax=true;
}

// is this an api request?
$is_api=false;
if(!empty($_POST['api']) || !empty($_GET['api'])){
	$is_api=true;
}

// is the request valid?
if(empty($_POST['submit']) && !$is_ajax){
	throw_error("invalid_request");
}

// check if the request is total empty?
if(empty($_POST['board']) && empty($_POST['thread'])){
	throw_error("invalid_request");
}

// no text and image error
if(empty($_POST['text']) && (empty($_FILES['image']) || $_FILES['image']['error']!=0)){
	throw_error("no_text_and_image");
}

$post_reply=false;
if(!empty($_POST['thread'])){
	// check if thread ID is numeric or not
	if(!is_numeric($_POST['thread'])){
		throw_error("thread_not_found");
	}

	// assigning the value
	$thread=filter_text($_POST['thread']);

	// check the thread if exits
	$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=?");
	$stmt->execute([$thread]);
	if($stmt->fetchColumn()!=1){
		throw_error("no_thread_found");
	}

	// so, since the thread exists, check if the thread is OP or a reply?
	$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=? AND `thread_start`='Y'");
	$stmt->execute([$thread]);
	if($stmt->fetchColumn()!=1){
		$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `id`=?");
		$stmt->execute([$thread]);
		$thread_alt=$stmt->fetch();
		$thread=$thread_alt['thread_reply'];
	}

	// good, now we have actual thread ID
	// time to check if its locked or archived

	$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `id`=?");
	$stmt->execute([$thread]);
	$thread_info=$stmt->fetch();

	// archived?
	if($thread_info['archived']=="Y" && !$mod){
		throw_error("thread_archived");
	}

	// locked?
	if($thread_info['locked']=="Y" && !$mod){
		throw_error("thread_locked");
	}

	// get board name
	$board=$thread_info['board'];
	$post_reply=true;
}


// get board name (from URL)
if(!empty($_POST['board']) && empty($board)){
	$board=strtolower(filter_text($_POST['board']));
}

// check board info
$stmt=$pdo->prepare("SELECT count(*) FROM `boards` WHERE `board`=?");
$stmt->execute([$board]);
if($stmt->fetchColumn()!=1){
	throw_error("no_board_found");
}

// get board information
$stmt=$pdo->prepare("SELECT * FROM `boards` WHERE `board`=?");
$stmt->execute([$board]);
$board_info=$stmt->fetchColumn();

// archived?
if($board_info['board']=="Y" && !$mod){
	throw_error("thread_archived");
}

// locked?
if($board_info['board']=="Y" && !$mod){
	throw_error("thread_locked");
}

// do the board "require" image to start thread
if($board_info['require_image']=="Y" && (empty($_FILES['image']) || $_FILES['image']['error']!=0) && !$post_reply){
	throw_error("image_required");
}


// Time Gap Logic
if(country()!="NP" && country()!="Nepal"){
	if($post_reply)
		$time_gap=40;
	else
		$time_gap=150;
}
else{
	if($post_reply)
		$time_gap=15;
	else
		$time_gap=30;
}


// GET TEXT
if(!empty($_POST['text'])){
	$text=filter_text($_POST['text']);

	// no text and image error (again to remove whitespace)
	if(empty($text) && (empty($_FILES['image']) || $_FILES['image']['error']!=0)){
		throw_error("no_text_and_image");
	}

	// check if the reply has already been posted recently to avoid duplicate
	$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `body_raw`=? AND `time_stamp`>? AND (`ip_address`=? OR `user_id`=?)");
	$stmt->execute([$text, time()-60, $user_ip, $user_id]);
	if($stmt->fetchColumn()>=1){
		throw_error("duplicate_text");
	}
}


// adult content?
if($board_info['safe']=='NO'){ $adult="YES"; } else{ if(empty($_POST['adult'])){ $adult="NO"; } else{ $adult="YES"; }}

// spoiler content?
if(empty($_POST['spoiler'])){ $spoiler="NO"; } else{ $spoiler="YES"; }

// got any post options?
if(empty($_POST['options'])){ $options=""; } else{ $options=trim(strip_tags($_POST['options'])); }

// got any post options?
if(empty($_POST['subject'])){ $title_text=""; } else{ $title_text=trim(strip_tags($_POST['subject'])); }


/* Do we have an Image? */
if(empty($_FILES['image']) || $_FILES['image']['error']!=0){
	$file='{"original_name": "EMPTY"}';
	$has_image=0;
}
else{
	/* Get File Information */
	$file_name=$_FILES['image']['name'];		// original file name
	$file_temp=$_FILES['image']['tmp_name'];	// Temporary File Upload
	$file_mime=$_FILES['image']['type'];		// file type
	$file_size=$_FILES['image']['size'];		// file size

	if($file_mime!="image/png" && $file_mime!="image/gif" && $file_mime!="image/jpeg" && $file_mime!="video/webm" && $file_mime!="video/mpeg" && $file_mime!="video/mp4"){
		throw_error("corrupt_image_file");
	}

	// PHP Bug (No File Name)
	$exp=explode(".", $file_name);
	if(empty($exp[0])){
		throw_error("invalid_image_format");
	}


$is_video=false;
$img_mime=$file_mime;

// get file size & max file size (8MB)
if($file_size==0 || $file_size>=(1024*1024*12)){
	throw_error("large_image_fsize");
}


// is this video? video verification
if($file_mime=="video/mpeg" || $file_mime=="video/mp4" || $file_mime=="video/webm"){

	$is_video=true;

	// get video info
	$mime_type=mime_content_type($file_temp);
	if($mime_type!="video/mp4" && $mime_type!="video/mpeg" && $mime_type!="video/webm"){
		throw_error("corrupt_image_file");
	}

	$video_info=_get_video_attributes($file_temp);
	$video_thumb=video_thumb($file_temp);

	if(empty($video_thumb)){
		$video_thumb="assets/images/video_thumb.png";
	}

	$img_h=$video_info['height'];
	$img_w=$video_info['width'];
	$img_l=$video_info['mins'].":".$video_info['secs'];

	$cache_image=cache_image($video_thumb);
	
	if($video_thumb!="assets/images/video_thumb.png"){ unlink($video_thumb); }

	// we really do not have any verification for video files :/
	// can you add some?


}
// else, run image verification
else{


	// PHP Get Image Size
	if(!getimagesize($file_temp)){
		throw_error("invalid_image_format");
	}

	// Get Image Information
	$image=getimagesize($file_temp);
	$img_mime=$image['mime'];	// additional mime type for image
	$img_h=$image[1];			// height
	$img_w=$image[0];			// width
	$img_l=0;

	// image mime
	if($img_mime!="image/png" && $img_mime!="image/gif" && $img_mime!="image/jpeg"){
		throw_error("invalid_image_format");
	}

	// Minimum Height and Width
	if($img_h<=50 || $img_w<=50){
		throw_error("small_image_size");
	}

	// Is Image Dimension Too Large?
	if($img_h>=10000 || $img_w>=10000){
		throw_error("large_image_size");
	}

} // image validation ends here


	// everything file // generate file_hash
	$file_hash=md5_file($file_temp);
	$hash_hash=md5(mt_rand(1, 10).$file_hash.mt_rand(1, 10));

	// check duplicate post via file hash

	// check for duplicate (of past 60 second)
	$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE (`ip_address`=? OR `user_id`=?) AND `time_stamp`>? AND `has_image`='Y'");
	$stmt->execute([$user_ip, $user_id, time()-60]);
	foreach ($stmt->fetchAll() as $_x_data) {
		$_x_file=json_decode($_x_data['files']);
		if(property_exists($_x_file, "file_hash")){
			if($_x_file->file_hash==$file_hash){
				throw_error("duplicate_image");
			}
		}
	}

	// file_name extension
	$ext2="mp4";
	if($img_mime=="image/png"){$ext=".png";}
	elseif($img_mime=="image/jpeg"){$ext=".jpg";}
	elseif($file_mime=="video/mp4" || $file_mime=="video/mpeg"){$ext=".mp4"; $ext2="mp4";}
	elseif($file_mime=="video/webm"){$ext=".webm"; $ext2="webm";}
	elseif($img_mime=="image/gif"){$ext=".gif";}
	else { echo "NO_EXT"; throw_error("unknown_error"); }

	if(move_uploaded_file($file_temp, "cdn/images/".$hash_hash.$ext)){

		if(!$is_video){ $cache_image=cache_image("cdn/images/".$hash_hash.$ext); }

		// sorry, I haven't figured out any way to make video thumbnail work

		$file_type = $is_video ? "video" : "image";


		// this looks weird formatting, it is to make easy for viewing file info on database
		$file='{
	"original_name": "'.strip_tags($file_name).'",
	"cache": "'.$cache_image.'",
	"path": "cdn/images/",
  	"type": "'.$file_type.'",
	"name": "'.$hash_hash.$ext.'",
	"ext": "'.$ext2.'",
	"file_size": "'.$file_size.'",
	"file_height": "'.$img_h.'",
	"file_width": "'.$img_w.'",
	"file_length": "'.$img_l.'",
	"adult": "'.$adult.'",
	"spoiler": "'.$spoiler.'",
	"uploaded_on": "'.$time.'",
	"uploader_ip": "'.$user_ip.'",
	"browser_info": "'.$user_browser.'",
	"file_hash": "'.$file_hash.'"
}';
	$has_image="YES";

	}
	else{
		throw_error("upload_failed");
	}
}


// calculate bump time
if($post_reply){

	// calculate bump time
	if(time()> ($thread_info['bumped_on']+$board_info['bumped_time'])){
		$bumped=time();
	}
	else{
		$bumped=$thread_info['bumped_on']+5; // just giving it a 5sec bump boost
	}

	// total replies and images (+1 )
	$total_replies=$thread_info['replies']+1;
	if($has_image=="YES"){
		$total_images=$thread_info['images']+1;
	}
	else{
		$total_images=$thread_info['images'];
	}

	// bump the thread
	$stmt=$pdo->prepare("UPDATE `threads` SET `images`=?,`replies`=?,`bumped_on`=? WHERE `id`=?");
	$stmt->execute([$total_images, $total_replies, $bumped, $thread]);

	$thread_reply_id=$thread;
	$is_thread_start="N";
}
else{
	$is_thread_start="Y";
	$thread_reply_id=0;
}


	// has image? (I know PHP/SQL Trimms the data but still)
	if($has_image=="YES"){
		$has_image=1;
	}
	else{
		$has_image=0;
	}




// Everything Is fine! Image is either Uploaded or Replaced by random image

$stmt=$pdo->prepare("INSERT INTO `threads`(`board`, `body`, `title`, `body_raw`, `time_stamp`, `bumped_on`, `files`, `thread_start`, `ip_address`, `browser_info`, `options`, `has_image`, `replies`, `images`, `thread_reply`, `user_id`) values(:board, :body, :title, :raw, :stamp, :updat, :files, :thread, :ip, :browser, :options, :has_img, :replies, :images, :thread_reply, :user_id);");
$stmt->execute([
	":board"		=>	$board,
	":body" 		=>	markup($text),
	":title"		=>	$title_text,
	":raw" 			=>	$text,
	":stamp"		=>	time(),
	":updat"		=>	time(),
	":files"		=>	$file,
	":thread"		=>	$is_thread_start,
	":ip"			=>	$user_ip,
	":browser"		=>	$user_browser,
	":options"		=>	$options,
	":has_img"		=> 	$has_image,
	":replies"		=>	0,
	":images"		=>	0,
	":thread_reply"	=>	$thread_reply_id,
	":user_id"		=>	$user_id
]);


if($stmt->rowCount()!=1){
	throw_error("unknown_error");
}
else{

	$last_id=$pdo->lastInsertId();

	if($is_ajax){
		echo "done";
		die;
	} elseif($is_api){
		echo '{"raw": "'.$last_id.'", title": "DONE", "alt": "done"}';
	}
	else{
		$url="/".$board."/";
		if($post_reply){
			$url=$url.$thread;
		}
		else{
			$url=$url.$pdo->lastInsertId();
		}
			header("Location: ".$url);
			die;
	}
}