<?php
require('config.php');

$bgarr = array('images/JD-BG1.jpg', 'images/JD-BG2.jpg', 'images/JD-BG3.jpg', 'images/JD-BG4.jpg', 'images/JD-BG5.jpg'); // array of filenames
$i = rand(0, count($bgarr)-1); // generate random number size of the array, -1 because the first string in an array is array[0]
$selectedBg = $bgarr[$i]; // set variable equal to which random filename was chosen
if($i == 0){
	$fontcolordark = 'true';
}
else{
	$fontcolordark = 'false';
}
echo '	<!DOCTYPE HTML>
		<html>
		<head>
			<link rel="icon" type="image/png" href="favicon.png" />
			<link rel="stylesheet" type="text/css" href="stylesheet.css">
			<meta charset="UTF-8"> 
			<title>JesseDrive | login</title>
			<script src="https://www.google.com/recaptcha/api.js"></script>
		</head>
		<body background="'.$selectedBg.'">
			';
			if($fontcolordark == 'true'){
				echo '<font color="grey"><h1 class="login" id="login-title">JesseDrive</h1></font>';
			}
			else{
				echo '<font color="white"><h1 class="login" id="login-title">JesseDrive</h1></font>';
			}
			echo'	<div id="loginbar">
					<div id="fieldset_container"><center>
						<fieldset id="loginframe">
							<legend>Log in</legend>
								<div id="loginframetext">
								<br>
									<form method="post" action="sessionmgr" id="loginform">
										<center>
										<input type="text" id="ww" name="email" placeholder="E-mailadres"';
											if(isset($_GET['rse'])){
												echo 'value="'.htmlspecialchars($_GET['rse']).'"';
											}
											echo'>
										<input type="password" id="ww" name="password" placeholder="Wachtwoord"><br>
										<input type="submit" id="submit" value="Log in"></center>
									</form>';

									if(isset($_GET['pwf'])){
										if($_GET['pwf'] == '1'){echo '<font color="red" class="login">Fout wachtwoord/email-adres</font>';}
										if($_GET['pwf'] == '13'){echo '<font color="red" class="login">Je account is tijdelijk geschorst.</font>';}
									}
echo   '						</div>
						</fieldset>
				
						<fieldset id="registerframe">
							<legend>Registreer</legend>
							<form method="post" action="register" id="registerform">
								<center>
								<input type="text" id="ww" name="email_reg" placeholder="E-mailadres">
								<input type="text" id="ww" name="email_reg_verify" placeholder="E-mailadres verifiëren" autocomplete="off">
								<input type="password" id="ww" name="password_reg" placeholder="Wachtwoord">
								<input type="password" id="ww" name="password_reg_verify" placeholder="Wachtwoord verifiëren"><br>
								<input type="checkbox" name="eula_chkbx" id="eula_chkbx" value="true"><div id="eula_chkbx_text"> Ik accepteer de <a href="license">gebruikersvoorwaarden</a></div><br>
								<center><div class="g-recaptcha" data-theme="light" data-sitekey="'.$grecaptcha_datakey.'" <!--style="transform:scale(0.9);transform-origin:0;-webkit-transform:scale(0.9);transform:scale(0.9);-webkit-transform-origin:0 0;transform-origin:0 0; 0"--></div></center>
								<br>
								<input type="submit" id="submit" value="Registreer"></center>
							</form>';
							if(isset($_GET['pwf'])){
								echo '<br>';
								if($_GET['pwf'] == '2'){echo '<font color="red" class="login">Gelieve de captcha in te vullen</font>';}
								if($_GET['pwf'] == '3'){echo '<font color="red" class="login">Gelieve een geldig e-mailadres in te vullen</font>';}
								if($_GET['pwf'] == '4'){echo '<font color="red" class="login">Gelieve je e-mailadres nogmaals in te vullen voor verificatie</font>';}
								if($_GET['pwf'] == '5'){echo '<font color="red" class="login">Gelieve een wachtwoord in te vullen</font>';}
								if($_GET['pwf'] == '6'){echo '<font color="red" class="login">Gelieve nogmaals je wachtwoord in te vullen voor verificatie</font>';}
								if($_GET['pwf'] == '7'){echo '<font color="red" class="login">Je twee e-mailadressen komen niet overeen</font>';}
								if($_GET['pwf'] == '8'){echo '<font color="red" class="login">Je twee wachtwoorden komen niet overeen</font>';}
								if($_GET['pwf'] == '9'){echo '<font color="red" class="login">Gelieve de captcha correct in te vullen</font>';}
								if($_GET['pwf'] == '10'){echo '<font color="red" class="login">Gelieve de captcha in te vullen</font>';}
								if($_GET['pwf'] == '11'){echo '<font color="red" class="login">Er is al een account aangemaakt met dit e-mailadres</font>';}
								if($_GET['pwf'] == '12'){echo '<font color="red" class="login">Je moet akkoord gaan met de gebruikersvoorwaarden</font>';}
							}
echo   '					
					</fieldset>
				</center></div>
				</div>	
				<!--<div id="footer_eula"><a href="license.php">Disclaimer/EULA</a></div>-->
		</body>
		</html>';
?>
