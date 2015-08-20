 <?php
require('config.php');
require('functions.php');
$type = $_GET['type'];
$file = $_GET['file'];
$folder= $_GET['folder'];
session_start();
if(isset($_SESSION['JDLogin'])){
	$sesexplode = explode(';', $_SESSION['JDLogin']);
	$user = $sesexplode[0];
	$userhash = $sesexplode[1];
	
	$fileexplode = explode('/', $file);
	
	if($fileexplode[1] === $userhash){
		if($type == 'nfile'){
			unlink($file);
			header('location:index?folder='.$folder);
		}
		if($type == 'folder'){
		recursiveRemove($file);
		
		header('location:index?folder='.$folder);
		}
	}
	else{
		HackAttempt($user, 'heeft een hackpoging gedaan door het proberen te verwijderen van de bestanden van andere gebruikers.');
	}

}
else{
	header('location:login.php');
}

function recursiveRemove($dir) {
	$structure = glob(rtrim($dir, "/").'/*');
	if (is_array($structure)) {
		foreach($structure as $file) {
			if (is_dir($file)){ recursiveRemove($file);}
			elseif (is_file($file)){ unlink($file);}
		}
	}
	rmdir($dir);
}
?>