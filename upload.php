<?php
/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 *
 *File based on example provided by PLUpload, modified by Jesse Geens
 */
// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 
//include JesseDrive configuration file
require('config.php');
require('functions.php');
ini_set('upload_tmp_dir', '/var/phptemp');
$userfolder = '/dev/null';
$targetDir = '/dev/null';


// 5 minutes execution time
@set_time_limit(5 * 60);
// Settings
$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

if(isset($_REQUEST['folder_param_jd'])){
	$userfolder = $_REQUEST['folder_param_jd'];
	$targetDir = $serverroot.$userfolder;
}

session_start();
if(isset($_SESSION['JDLogin'])){
	$sesexplode = explode(';', $_SESSION['JDLogin']);
	$user = $sesexplode[0];
	$userhash = $sesexplode[1];

	$fileexplode = explode('/', $userfolder);
	//echo 'debugmode on - '.$file.';';exit;
	if($fileexplode[1] === $userhash){
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} 
		elseif (isset($_REQUEST["file_name"])) {
			$fileName = $_REQUEST["file_name"];
		}
		elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} 
		else {
			$fileName = uniqid("file_");
		}

		$filePath = $targetDir.$fileName;
		//die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "debug info: '.$folder.';'.$targetDir.';'.$fileName.';'.$filePath.';'.'"}, "id" : "id"}');
		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		// Remove old temp files	
		if(getDirectorySize($serverroot.'files/'.$userhash.'/') > $max_user_space_bytes){
			$fileName = 'file_not_allowed';
			$targetDir = '/dev/null';
			$filePath = '/dev/null';
			exit;
			//This should work but it doesn't :/
		}

		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	
		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		@fclose($out);
		@fclose($in);
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}
		chmod($filePath, 0766);
		// Return Success JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
		
}
else{
	header('location:login.php');
}