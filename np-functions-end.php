<?php
/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * Functions (PHP) - End (Which require $user_* data)
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This file contains all the PHP functions.
 * Note: Just to let you know, I have no idea how some of the functions below work.
 * They are here because they just works and I am fine with it.
 */

 // prevent direct access to script
if(!defined("AUTH")){ die; }



/*
 * Function: Switch Language.
 * This will put the data of specific file to specific $lang_* variable.
*/
function switch_lang($row, $data){
	global ${"lang_".$row};	// create a global variable to access it from everywhere
	${"lang_".$row}=$data;	// put data in the variable
}

/*
 * Function: Load Language
 * Loads language parts according what we ask
 * can take array or string (auto determinate)
*/
function load_language($_lang){
	global $user_lang;
	if(is_array($_lang)){
		foreach($_lang as $row){
			$url="assets/language/".$user_lang."/".$row.".json";
			if(file_exists($url)){
				$data=json_decode(file_get_contents($url));
				switch_lang($row, $data);
			}
			else{
				echo "<h1>Failed to load language file! Error: Language File \"".$row.".json"."\" Doesn't Exists!</h1>";
				die;
			}
		}
	}
	else{
		$url="assets/language/".$user_lang."/".$_lang.".json";
			if(file_exists($url)){
				$data=json_decode(file_get_contents($url));
				switch_lang($_lang, $data);
			}
			else{
				echo "<h1>Failed to load language file! Error: Language File \"".$_lang.".json"."\" Doesn't Exists!</h1>";
				die;
			}
	}
}

/*
 * Function: Load Stylesheet
 * This will load the stylesheet file.
 * Outputs HTML
*/

function load_stylesheet(){
	global $user_theme;
	$url = "assets/css/".$user_theme.".css";
	// load CSS Files
	if(!file_exists($url)){
    // do the theme exists? If No then roll back to default
		$url="assets/css/Default.css";
	}

  // echo out html
	echo "<link rel=\"stylesheet\" href=\"/".$url."\" type=\"text/css\">";
}


/*
 * Throw Error (Post Page)
 * Easy Error and Also manages error accordingly if ajax request or api request
*/

function throw_error($type){
	global $user_lang,$lang_error,$lang_basic,$_POST_PAGE,$social,$is_ajax,$is_api;
	// load error files
	load_language(["error", "basic"]);
	$type_alt=$type."_alt";
	property_exists($lang_error, $type) ? $title=$lang_error->{$type} : $title="Error Occured!";
	property_exists($lang_error, $type_alt) ? $alt=$lang_error->{$type_alt} : $alt="An unknown error occured! Please try again! If you repeatedly see this message, please report this error using any of our contact information! <small>#".$type."</small>";
	if($is_ajax){
		echo "<b>".$title."</b><br/>".$alt;
	} elseif($is_api){
		// if this an api request, provide error
		echo '{"raw": "'.$type.'", title": "'.$title.'", "alt": "'.$alt.'"}';
	}
  else{
    require_once "np-error.php";
  }
	die;
}