<?php
require('config.php');
require('functions.php');
session_start();
if(isset($_SESSION['JDLogin'])){
	$sesexplode = explode(';', $_SESSION['JDLogin']);
	$user = $sesexplode[0];
	$userhash = $sesexplode[1];
	if(isset($_POST['savetxtval']) && isset($_POST['savetxtloc'])){
		$savelocarray = explode('/', $_POST['savetxtloc']);
		$savelochashedemail = $savelocarray[1];
		if($savelochashedemail != $userhash){
			HackAttempt($user, ' heeft een hackpoging gedaan door te proberen tekst te wijzigen van een andere gebruiker.');
		}
		else{
			$file = fopen($_POST['savetxtloc'], "w") or die("Kan bestand niet openen!");
			$txt = $_POST['savetxtval'];
			fwrite($file, $txt);
			fclose($file);
			header('location:index');
		}

	}
	else{
	//header('location:index');
		echo 'POST variabelen niet gevonden!';
	}
}
else{
	header('location:login');
}
?>