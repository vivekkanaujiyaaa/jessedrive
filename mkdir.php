<?php
require('config.php');
require('functions.php');
session_start();
if(isset($_SESSION['JDLogin'])){
	$sesexplode = explode(';', $_SESSION['JDLogin']);
	$user = $sesexplode[0];
	$userhash = $sesexplode[1];

	$folder = $_GET['folder'];
	$newfolder = $_GET['dir'];

	$foldersplit = explode('/', $folder);
	if($foldersplit[1] != $userhash){
		HackAttempt($user, ' heeft een hackpoging gedaan door te proberen een map aan te maken tussen de bestanden van een andere gebruiker.');
	}
	elseif($newfolder == '' || $newfolder == '.' || $newfolder == ".."){
		header('location:index?mme');
	}
	else{
		$pathcomp = $folder.$newfolder;
		
		if (!mkdir($pathcomp, 0777, true)) {
			header('location:index?mke');
		}
		else{
			chmod($pathcomp, 0777);
			$headerto = 'location:index.php?folder='.$folder;
			header($headerto);
		}
	}
}
else{
	header('location:login.php');
}
?>