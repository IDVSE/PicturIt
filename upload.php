<?php
function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

header("Content-Type: text/json");
$json = array("status"=>"error","message"=>"Unknown","image_url"=>null,"debug"=>"");
if(isset($_GET['file_too_big'])){
	$json['message'] = "That file is too big! Try again with a file under 8MB";
	die(json_encode($json));
}
if(isset($_POST['verify1'])){
	if($_POST['verify1'] != ""){
		$json['message'] = "Sorry! An unknown error happened.";
		// deal with spambot here (as no human would ever fill out an input form that's hidden)
		// Maybe temporarily IP ban if they try too many times?
		die(json_encode($json));
	}
}
if(isset($_POST['verify2'])){
	if($_POST['verify2'] != "swag"){
		$json['message'] = "Sorry! An unknown error happened.";
		// deal with spambot here (as no human would ever replace an input form that's hidden)
		// Maybe temporarily IP ban if they try too many times?
		die(json_encode($json));
	}
}

// add more spam protection here

if(isset($_FILES['userfile'])){
	$basedir = "images/";
	
	// we don't want unwanted image test uploads in the repo, so make the images dir if it isn't already there
	if (!file_exists($basedir)) {
		mkdir($basedir, 0777, true);
	}
	
	if($_FILES['userfile']['error'] != 0){
		$json['message'] = "Sorry! Our servers encountered an error with your upload request. Maybe you didn't send us a file?";
	} else {
		$ext = ".wut";
		$type = exif_imagetype($_FILES['userfile']['tmp_name']);
		if($type==FALSE || $type==0) $type = "Unknown (yet)";
		$json['debug'] .= "Image type:$type\n";
		$proceed = FALSE;
		if($type == 1 || $type == 2 || $type == 3){
			$proceed = TRUE;
			if($type==1) $ext=".gif";
			if($type==2) $ext=".jpg";
			if($type==3) $ext=".png";
			// add more later
		} else {
			try {
				$doc = @simplexml_load_file($_FILES['userfile']['tmp_name']);
				if(is_object($doc) && $doc->getName() == "svg"){
					$json['debug'] .= "Image type: SVG?\n";
					$proceed = TRUE;
					$ext=".svg";
				} else throw new Exception("Not an SVG");
			} catch(Exception $e){
				$json['debug'] .= $e."\n";
				$json['message'] = "Whoops! It seems you didn't upload a (valid) image. Make sure you are uploading a non-corrupted image file (any JPEG, PNG, GIF, or SVG) and try again."; 
			}
		}
		if($proceed){
			$name = "";
			do {
				$name = unique_id(8);
			} while(file_exists($basedir.$name.$ext));
			$json['debug'] .= $name."\n";
			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $basedir.$name.$ext)) {
				$json['status'] = "success";
				$json['message'] = "Your image was uploaded successfully";
				$json['image_url'] = $basedir.$name.$ext;
			} else {
				$json['message'] = "Sorry! A server side error prevented us from uploading your file. Try again later.";
			}
		}
	}
} else $json['message'] = "Whoops! It seems your browser sent an incomplete request. Are you sure you're not hacking?";
$json['var_dump'] = print_r($_FILES, true);
echo json_encode($json);
?>