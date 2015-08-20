<?php
//Functies door Jesse Geens

function TransformFileSize($filesize){
	if ($filesize >= 1099511627776){
		$filesize = number_format($filesize / 1099511627776, 2) . ' TB';
	}
	elseif ($filesize >= 1073741824){
		$filesize = number_format($filesize / 1073741824, 2) . ' GB';
	}
	elseif ($filesize >= 1048576){
		$filesize = number_format($filesize / 1048576, 2) . ' MB';
	}
	elseif ($filesize >= 1024){
		$filesize = number_format($filesize / 1024, 2) . ' KB';
	}
	elseif ($filesize > 1){
		$filesize = $filesize . ' bytes';
	}
	elseif ($filesize == 1){
		$filesize = $filesize . ' byte';
	}
	else{
		$filesize = '0 bytes';
	}
	return $filesize;
}

function getBrowser(){
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac OS X';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Microsoft Windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
	else{
		$bname = 'Unknown browser (onbekende browser)';
		$ub = 'Unknown browser (onbekende browser)';
	}
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

function HackAttempt($user, $msg){
	require('config.php');
    if($ban){
		echo 'Hackpoging gedetecteerd! Je account wordt op nonactief gezet tot de hackpoging bekeken wordt door een administrator.';
		mail($admin_email, 'Hackpoging', $user.$msg);
		$connection = new mysqli($servername, $username, $password, $dbname);

		if ($connection->connect_error){
				die("JesseDrive kon geen verbinding met de database maken. <br> Error: " . $conn->connect_error);
		}

		$sql = "UPDATE userdata SET STATUS = 'inactive' WHERE email = '$user'";

		$result = $connection->query($sql);
		session_destroy();
		header('location:login?pwf=13');
		exit;
	}
	else{
		header('location:index?had');
		exit;
	}
}

function getDirectorySize($path){
    $io = popen ( '/usr/bin/du -sk ' . $path, 'r' );
    $size = fgets ( $io, 4096);
    $size = substr ( $size, 0, strpos ( $size, "\t" ) );
    pclose ( $io );
    return $size * 1024;
}
?>