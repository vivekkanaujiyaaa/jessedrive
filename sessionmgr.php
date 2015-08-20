<?php
require('config.php');

//JesseDrive login system
//Not secure at all use at own risk

//User-set variables
$email = strip_tags($_POST['email']);
$pwd = strip_tags($_POST['password']);

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
		die("JesseDrive kon geen verbinding met de database maken. <br> Error: " . $conn->connect_error);
	}

//Obtains the salt (which was generated randomly at the registration process)
$sql_query_salt_obtainer = "SELECT salt FROM userdata WHERE email = '$email'";
$saltresult = $connection->query($sql_query_salt_obtainer);
if ($saltresult->num_rows === 1) {
	while($row = $saltresult->fetch_assoc()) {
		$salt = $row['salt'];
	}
} 
else {
	header('location:login?pwf=1');
	exit;
}
$sql_query_status_obtainer = "SELECT status FROM userdata WHERE email = '$email'";
$statusresult = $connection->query($sql_query_status_obtainer);
if ($statusresult->num_rows === 1) {
	while($row = $statusresult->fetch_assoc()) {
		$status = $row['status'];
		if($status != 'active'){
			header('location:login?pwf=13');
			exit;
		}
	}
} 
else{
	echo 'statusresult->num_rows != 1<br><br>Fatale error!!<br> Contacteer de administrator ('.$admin_email.') en vermeld je acties en het tijdstip in de mail.';exit;
}
//Checks if the user-entered password matches an account
$pwdhash = crypt($pwd, $salt);
$sql_query = "SELECT * FROM userdata WHERE email = '$email' and password = '$pwdhash'";
$resultsqlq = $connection->query($sql_query);

if ($resultsqlq->num_rows === 1) {
		echo 'Logged in.';
		while($row = $resultsqlq->fetch_assoc()) {
        	$id = $row['ID'];
        	$email = $row['email'];
        	$pwdverhash = $row['password'];
		}
		session_start();
		ini_set('session.cookie_httponly', true);
		$_SESSION['JDLogin'] = $email.';'.crypt($email, $salt);
		header('location:index');exit;
} 
else {
	header('location:login.php?pwf=1');exit;
}
$connection->close();
?>