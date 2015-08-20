<?php
require('config.php');
/*DEBUG INFO - disable for normal use*/ //echo 'debug info: '.$_POST['eula_chkbx'];exit;
//Vars
if(isset($_POST['email_reg']) && isset($_POST['email_reg_verify']) && isset($_POST['password_reg']) && isset($_POST['password_reg_verify']) && isset($_POST['g-recaptcha-response']) && isset($_POST['eula_chkbx'])){
	$email = $_POST['email_reg'];
	$emailver = $_POST['email_reg_verify'];
	$pwd = $_POST['password_reg'];
	$pwdver = $_POST['password_reg_verify'];
	$recaptcha=$_POST['g-recaptcha-response'];
}
else {
	if(!isset($_POST['g-recaptcha-response'])){/*echo 'g-recaptcha-response not set';*/header('location:login.php?pwf=2');exit;}
	if(!isset($_POST['email_reg'])){/*echo 'email_reg not set';*/header('location:login.php?pwf=3');exit;}
	if(!isset($_POST['email_reg_verify'])){/*echo 'email_reg_verify not set';*/header('location:login.php?pwf=4');exit;}
	if(!isset($_POST['password_reg'])){/*echo 'password_reg not set';*/header('location:login.php?pwf=5');exit;}
	if(!isset($_POST['password_reg_verify'])){/*echo 'password_reg_verify not set';*/header('location:login.php?pwf=6');exit;}
	if(!isset($_POST['eula_chkbx'])){/*echo 'eula_chkbx not set';*/header('location:login.php?pwf=12');exit;}
	//header('location:login?pwf=6');
}

//Checks en verifies POST input
if($email != $emailver){
	header('location:login?pwf=7');
	exit;
}

if($pwd != $pwdver){
	header('location:login?pwf=8');
	exit;
}

if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
	header('location:login?pwf=3');
	exit;
}
//Checks captcha
if(!empty($recaptcha)){
	$secret = $grecaptcha_privatekey;

	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array('secret' => $secret, 'response' => $recaptcha);

	// use key 'http' even if you send the request to https://...
	$options = array(
    	'http' => array(
        	'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        	'method'  => 'POST',
        	'content' => http_build_query($data),
    	),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if(strpos($result, 'true')){
		//Start of registering process

		//Generates a random salt
		$salt = base64_encode(mcrypt_create_iv(ceil(0.75*$salt_length), MCRYPT_DEV_URANDOM));
		$userhash = crypt($email, $salt);
		while(strpos($salt, '/') !== false || strpos($userhash, '/')){
			$salt = base64_encode(mcrypt_create_iv(ceil(0.75*$salt_length), MCRYPT_DEV_URANDOM));
			$userhash = crypt($email, $salt);
		}



		$pwdhash = crypt($pwd, $salt);

		//Initializes connection to the database
		//All vars have been set in config.php
		$connection = new mysqli($servername, $username, $password, $dbname);

		if ($connection->connect_error) {
			die("JesseDrive kon geen verbinding met de database maken. <br> Error: " . $conn->connect_error);
		}

		$salt_db =  mysqli_real_escape_string($connection, $salt);
		$email_db =  mysqli_real_escape_string($connection, $email);
		$pwd_db =  mysqli_real_escape_string($connection, $pwdhash);

		$email_exists_checker = "SELECT email FROM userdata WHERE email = '$email'";
		$email_exists_result = $connection->query($email_exists_checker);
		if ($email_exists_result->num_rows === 1) {
			header('location:login?pwf=11');
		} 

		else{
			$sql = "INSERT INTO userdata (salt, email, password, status)
			VALUES ('$salt_db', '$email_db', '$pwd_db', 'active')";

			if ($connection->query($sql) === TRUE) {
   				$dir = $serverroot . 'files/'.crypt($email, $salt);
   				mkdir($dir, 0766, true);
   				chmod($dir, 0766);
   				echo 'Account succesfully created!';
   				header('location:login?rse='.$email);
			} 
			else {
    			echo "Error: " . $sql . "<br>" . $connection->error;
			}

			$connection->close();
		}
		
	}
	else{
		header('location:login?pwf=9');
		exit;
	}
}
else{
	header('location:login?pwf=10');
	exit;
}
?>