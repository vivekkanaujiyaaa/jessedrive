<?php
/******************************
*                             *
*JesseDrive configuration file*
*    (c) Jesse Geens 2015     *
*Modify all variables here!!  *
*                             *
******************************/

//MySQL Server settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usertbl";

//Server settings
$serverroot = '/var/www/jessedrive/'; //Sets the root directory of JesseDrive
$salt_length = 16; //Sets the length of the random salt
$admin_email = ''; //Admin's email

//Upload settings
$max_user_space_bytes = 16106127360; //Sets the max userspace in bytes (15GB in this case)

//Security settings
$ban = false; //Defines whether a user is banned when a hack attempt is detected (!!warning!! an innocent user can be sent a malicious link this way!)

$grecaptcha_datakey = '';
$grecaptcha_privatekey = '';
?>