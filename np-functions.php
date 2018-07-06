<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Functions (PHP)
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file contains all the PHP functions.
 * Note: Just to let you know, I have no idea how some of the functions below work.
 * They are here because they just works and I am fine with it.
 */

// prevent direct access to script
if(!defined("AUTH")){die;}


/*
 * Unicodify
 * Copied from vichan
 * Licensed Under MIT
 * Project Upstream URL: https://github.com/vichan-devel/vichan
*/
function unicodify($body) {
    $body = str_replace('...', '&hellip;', $body);
    $body = str_replace('&lt;--', '&larr;', $body);
    $body = str_replace('--&gt;', '&rarr;', $body);
    $body = str_replace('---', '&mdash;', $body);
    $body = str_replace('--', '&ndash;', $body);
    $body = str_replace('<', '&lt;', $body);
    return $body;
}

/*
 * UTF8 to HTML
 * Copied from vichan
 * Licensed Under MIT
 * Project Upstream URL: https://github.com/vichan-devel/vichan
*/
function utf8tohtml($utf8) {
    return htmlspecialchars($utf8, ENT_NOQUOTES, 'UTF-8');
}

/*
 * Sanitize user Input Data
 * Why Required? For future "filtering" if any bugs found. Its easy to edit anything here
 * than to change the whole code
*/
function filter_text($text){
    return trim(strip_tags($text));
}

/* Get Exact User IP (Bypass possible proxies) */
function GetIP(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip){
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}

/*
 * Time Until
 * Copied from vichan
 * Licensed Under MIT
 * Project Upstream URL: https://github.com/vichan-devel/vichan
*/
function until($timestamp) {
	$difference = $timestamp - time();
	switch(TRUE){
	case ($difference < 60):
		return $difference . ' ' . ngettext('second', 'seconds', $difference);
	case ($difference < 3600): //60*60 = 3600
		return ($num = round($difference/(60))) . ' ' . ngettext('minute', 'minutes', $num);
	case ($difference < 86400): //60*60*24 = 86400
		return ($num = round($difference/(3600))) . ' ' . ngettext('hour', 'hours', $num);
	case ($difference < 604800): //60*60*24*7 = 604800
		return ($num = round($difference/(86400))) . ' ' . ngettext('day', 'days', $num);
	case ($difference < 31536000): //60*60*24*365 = 31536000
		return ($num = round($difference/(604800))) . ' ' . ngettext('week', 'weeks', $num);
	default:
		return ($num = round($difference/(31536000))) . ' ' . ngettext('year', 'years', $num);
	}
}

/*
 * Time Ago
 * Copied from vichan
 * Licensed Under MIT
 * Project Upstream URL: https://github.com/vichan-devel/vichan
*/
function ago($timestamp) {
	$difference = time() - $timestamp;
	switch(TRUE){
	case ($difference < 60) :
		return $difference . ' ' . ngettext('second', 'seconds', $difference);
	case ($difference < 3600): //60*60 = 3600
		return ($num = round($difference/(60))) . ' ' . ngettext('minute', 'minutes', $num);
	case ($difference <  86400): //60*60*24 = 86400
		return ($num = round($difference/(3600))) . ' ' . ngettext('hour', 'hours', $num);
	case ($difference < 604800): //60*60*24*7 = 604800
		return ($num = round($difference/(86400))) . ' ' . ngettext('day', 'days', $num);
	case ($difference < 31536000): //60*60*24*365 = 31536000
		return ($num = round($difference/(604800))) . ' ' . ngettext('week', 'weeks', $num);
	default:
		return ($num = round($difference/(31536000))) . ' ' . ngettext('year', 'years', $num);
	}
}

/* File Size Conversion: Copied from Vichan */
function f_size($size){
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	return round($size, 2).$units[$i];
}

/* Random Code Generator */
function random_code($characters) {
      // List of all possible characters
      $possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
      $code = '';
      $i = 0;
      while ($i < $characters) {
         $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
         $i++;
      }
      return trim($code);
}

/* Image Resize (For Thumbnail Generator)
 * This function manipulate the image.
*/
function img_resize( $tmpname, $size, $save_dir, $save_name ){

	$save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
	$gis = GetImageSize($tmpname);
	$type = $gis[2];
	switch($type){
	    case "1": $imorig = imagecreatefromgif($tmpname); break;
	    case "2": $imorig = imagecreatefromjpeg($tmpname);break;
	    case "3": $imorig = imagecreatefrompng($tmpname); break;
	    default:  $imorig = imagecreatefromjpeg($tmpname);
	}

    $x = imageSX($imorig);
    $y = imageSY($imorig);
    if($gis[0] <= $size){
    	$av = $x;
    	$ah = $y;
    }
    else{
        $yc = $y*1.3333333;
        $d = $x>$yc?$x:$yc;
        $c = $d>$size ? $size/$d : $size;
        $av = $x*$c;
        $ah = $y*$c;
    }
    $im = imagecreate($av, $ah);
    $im = imagecreatetruecolor($av,$ah);
	if (imagecopyresampled($im,$imorig , 0,0,0,0,$av,$ah,$x,$y)){
	    if (imagejpeg($im, $save_dir.$save_name)){
	        return true;
	    }
	    else{
	    	return false;
		}
	}
}

/* Cache Image Generator */
function cache_image($url){
	if(!file_exists($url)){
		return $url;
	}
	/* Get File Information */
	$img=getimagesize($url);

	// convert the image to JPEG first
		//
		// 1. This will save image in temporary directory first
		// 2. The saved image will be compressed later and removed
		// 3. JPEG Image can be compressed efficiently!
		//
		$originalFile=$url;
		$outputFile="cdn/temp/".md5(mt_rand(1,10000)).".jpg";
		if($img['mime']=="image/gif"){ $image= ImageCreateFromGIF($originalFile); }
		elseif($img['mime']=="image/png") { $image= ImageCreateFromPNG($originalFile); }
		else{$image=imageCreateFromJPEG($originalFile); }
			@imageJPEG($image, $outputFile, 30);	// save original file converting to JPEG

		$url=$outputFile;	// replace with converted image


	// calculate aspect ratio
	$aspect_ratio = (float) $img[1] / $img[0];

	$thumb_height = round(160 * $aspect_ratio);
	$thumb_width = 160;

	$output=md5(mt_rand(1, 10000000)-mt_rand(100, 1000)).".jpg";
	// Save File On New Area
	img_resize($outputFile, 160, "cdn/thumbnail/", $output);

	// unlink temporary file
	if(file_exists($outputFile)){
		unlink($outputFile);	// delete temporary cache
	}

	return "cdn/thumbnail/".$output;
}

/*
 * Markdown Editor of Thread Posts and Replies
 * --------------------------------------
 * Just does some formatting and shows to user. Magic happens with JS.
*/

function markup($text){
	global $pdo,$board,$thread,$post_reply;
    $text = utf8tohtml($text);
    $text = unicodify($text);
    // $text=str_replace("<", "&lt;", $text);
    $text=nl2br($text);
    
    
    // brute force replace >>>/board/ to board links.
    $stmt=$pdo->query("SELECT `board` FROM `boards`");
    $stmt->execute();
    foreach($stmt->fetchAll() as $row){
        $find = "&gt;&gt;&gt;/".$row['board']."/";
		    $replace = "<a href=\"/".$row['board']."/\" id=\"board-link\" data-id=\"".$row['board']."\">x&rarr;xx&rarr;xx&rarr;x/".$row['board']."/</a>";
		    $text = str_replace($find, $replace, $text);
    }

    // suprise meme pages (Just for fun lol)
    foreach(["out", "reddit", "r/eddit", "facebook", "fb"] as $row){
      $find = "&gt;&gt;&gt;/".$row."/";
      $replace = "<a href=\"/".$row."/\" id=\"board-link\" data-id=\"".$row."\">x&rarr;xx&rarr;xx&rarr;x/".$row."/</a>";
      $text = str_replace($find, $replace, $text);
    }


    // check all thread IDs (Cites)
    preg_match_all("/&gt;&gt;(?P<digit>\d+)/", $text, $matches, PREG_SET_ORDER);

	foreach ($matches as $val) {

		$valx=(int)$val[1];		// converting to integer (just for safety)

		// where this cite belongs (is it OP?)
		if($valx==$thread){
			$replace='<a href="/'.$board.'/'.$thread.'/#'.$thread.'" id="c'.$thread.'" class="cite" data-id="'.$thread.'"  data-board="'.$board.'" onclick="highlight(\''.$thread.'\');">x&rarr;xx&rarr;x'.$thread.' (OP)</a>';
		}
		else{
      // not OP? Search Database first
			$stmt=$pdo->prepare("SELECT count(*) FROM `threads` WHERE `id`=?");
			$stmt->execute([$valx]);
			if($stmt->fetchColumn()==1){
				// get where it belongs
				$stmt=$pdo->prepare("SELECT * FROM `threads` WHERE `id`=?");
				$stmt->execute([$valx]);
				$t_data=$stmt->fetch();
				$bo=$t_data['board'];
				if($t_data['thread_start']=="Y"){
					$th=$valx;
				}
				else{
					$th=$t_data['thread_reply'];
				}

				$thx=$valx;

				$replace='<a href="/'.$bo.'/'.$th.'/#'.$thx.'" id="c'.$thx.'" class="cite" data-id="'.$thx.'" data-board="'.$bo.'" onclick="highlight(\''.$thx.'\');">x&rarr;xx&rarr;x'.$thx.'</a>';
			}
			else{
				$th=$val[1];
				$replace='<a href="/go.php?id='.$th.'" id="c'.$th.'" class="cite" data-id="'.$th.'" data-board="NOT_FOUND" onclick="highlight(\''.$th.'\');">x&rarr;xx&rarr;x'.$th.'</a>';
			}
		}

		// get new thread ID
		$stmt=$pdo->query("SELECT `id` FROM `threads` ORDER BY `id` DESC LIMIT 1");
		$stmt->execute();
		$t_idx=$stmt->fetch();
		$c_by=$t_idx['id']+1;

		$stmt=$pdo->prepare("INSERT INTO `cite`(`by`, `to`) VALUES(?, ?)");
		$stmt->execute([$c_by, $valx]);
	    $text=str_replace($val[0], $replace, $text);
	}


	// making greentext
	$text = preg_replace("/^\s*&gt;.*$/m", '<span class="quote">$0</span>', $text);

	// changing back the x&larr;x to &gt;
	$text=str_replace("x&rarr;x", "&gt;", $text);

	return $text;

}

/* Trunctuate Text
 * Source: https://www.thewebtaylor.com/articles/simple-php-truncate-function
*/
function truncate($text, $chars = 120) {
    if(strlen($text) > $chars) {
        $text = $text.' ';
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text ,' '));
        $text = $text."(...)";
    }
    return $text;
}

/*
 * Clear EXIF Data
 * This will clear all EXIF data of image. (Device, GPS Location, Date/Time, Camera Information...)
 * This will also largely reduce the file size since exif data can take significant amount of space.
 * Testing: 12.3MB image reduced to 11.5MB
 * Requires imagick module.
 * Please install php-imagemagik and imagemagik modules (linux only, probably won't work on windows easily)
 *
*/
function clear_exif($url){

  // only run this if the extension is enabled
  if(extension_loaded('imagick')){

    // start the imagick engine
     $img = new Imagick(realpath($url));
     // get icc profiles (Colors, rotation and stuffs (basic ones))
     $profiles = $img->getImageProfiles("icc", true);
     // remove the naughty stuffs
     // this will remove any metadata (device, location, gps or any hidden exif values)
     $img->stripImage();
     // this will add back basic profiles (icc ones) if it had any.
     if(!empty($profiles)) {
         $img->profileImage("icc", $profiles['icc']);
      }
      // finally write the image
      $img->writeImage($url);
  }
}

/*
 * Display Text
 *
*/
function display_text($text, $thread, $board){

  // is this long?
  if(strlen($text)>300){
    $text=truncate($text, 300);
    $text=$text."<div class=\"too-long\">Text too Long! <a href=\"/".$board."/".$thread."/\">Click Here to See More</a>.</div>";
  }



  // returning the value
  return $text;
}

/*
 * Get Video Attributes
 * Height, Width, Length (Time), Codec
 *
*/

function _get_video_attributes($video, $ffmpeg="/usr/bin/ffmpeg") {

    $command = $ffmpeg . ' -i ' . $video . ' -vstats 2>&1';
    $output = shell_exec($command);

    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; // or : $regex_sizes = "/Video: ([^\r\n]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; (code from @1owk3y)
    if (preg_match($regex_sizes, $output, $regs)) {
        $codec = $regs [1] ? $regs [1] : null;
        $width = $regs [3] ? $regs [3] : null;
        $height = $regs [4] ? $regs [4] : null;
    }

    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
    if (preg_match($regex_duration, $output, $regs)) {
        $hours = $regs [1] ? $regs [1] : null;
        $mins = $regs [2] ? $regs [2] : null;
        $secs = $regs [3] ? $regs [3] : null;
        $ms = $regs [4] ? $regs [4] : null;
    }
    if(empty($codec)){$codec=NULL;}
    if(empty($width)){$width=NULL;}
    if(empty($height)){$height=NULL;}
    if(empty($hours)){$hours=NULL;}
    if(empty($mins)){$mins=NULL;}
    if(empty($secs)){$secs=NULL;}
    if(empty($ms)){$ms=NULL;}
    return array('codec' => $codec,
        'width' => $width,
        'height' => $height,
        'hours' => $hours,
        'mins' => $mins,
        'secs' => $secs,
        'ms' => $ms
    );
}

/*
 * Generate Video Thumbnail
 * This funtion will generate video thumbnail (Screencap) for certain period of time.
 * Not actually a thumbnail as it will generate full size image.
*/

function video_thumb($video){ 
	$path="cdn/temp/".md5(rand(1,100).mt_rand(1, 500)).".jpg";
	$ffmpeg="/usr/bin/ffmpeg";
	$cmd = "{$ffmpeg} -i {$video} -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg {$path} 2>&1";
	$output=shell_exec($cmd);
	if(file_exists($path)){
    	return $path;
    } else {
    	return "assets/images/video_thumb.png";
    }
}



/*
 * Function: Get User's Country (API)
 * This will fetch the user's API from other properiatory (freemium) services.
 *
*/
function get_country($ip){
	// source 1 GeoPlugin.NET
	$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

	if(property_exists($ipdat, 'geoplugin_countryName')){ 
		$country = $ipdat->geoplugin_countryName;
	}
	else{

		// failover for GeoPlugin.NET
		$ipdat= @json_decode(file_get_contents("http://ip-api.com/json/".$ip));
		if(property_exists($ipdat, 'success')){
			if($ipdat->success=="success"){
				$country=$ipdat->country;
			}
			else{
				// failover for failover of GP.net (ip-api.com)'s failover
				$ipdat= @json_decode(file_get_contents("http://api.ipdata.co/".$ip));
				if(property_exists($ipdat, "country_name")){ 
					$country=$ipdat->country_name;
				}
				else{
					$country="Nepal";
				}    
			}
		} // if for failover for gp (ip-api.com)

		else{
			$country="Nepal";
		} // failover for everything. (IP not verified) 
	} // else for source 1

	return $country;
}

/*
 * Function: Get Country
 * This will return user's country name.
 * If it exists on DB, it pulls from there, else makes a new one.
 * ---------------------------------
 * FAQ:
 * 1. Why track this?
 * - NPChan is meant for Nepalese users. To prevent misuse of NPChan from foreign IP address,
 * some features are limited only to Nepalese IP address only. This help in controlling spam
 * as well as reduce chance of someone uploading illegal content using TOR or VPN
 *
*/

function country(){
	global $pdo,$user_ip;
	if(empty($user_ip)){
		$user_ip=getIP();
	}
	

    if($user_ip=="::1" || $user_ip==""){
		return "Nepal";    // local machine bypass
    }

    // check if user's IP is on DB or not
    $stmt=$pdo->prepare("SELECT count(*) FROM `ip_logs` WHERE `ip_address`=?");
    $stmt->execute([$user_ip]);
    if($stmt->fetchColumn()>0){
        $stmt=$pdo->prepare("SELECT `country` FROM `ip_logs` WHERE `ip_address`=?");
        $stmt->execute([$user_ip]);
        $cont=$stmt->fetch();
        $country=$cont['country'];
    }

    // if we don't have it on database, make it.
    else{
        $country = get_country($user_ip);
		// making it
		$stmt=$pdo->prepare("INSERT INTO `ip_logs`(`ip_address`, `country`, `timestamp`) VALUES(?, ?, ?);");
		$stmt->execute([$user_ip, $country, time()]);
    }

    return $country;
 }