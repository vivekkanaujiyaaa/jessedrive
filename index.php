 <?php
//Anti-caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 
require('config.php');
require('functions.php');
session_start();
if(isset($_SESSION['JDLogin'])){
	$sesexplode = explode(';', $_SESSION['JDLogin']);
	$user = $sesexplode[0];
	$userhash = $sesexplode[1];
	$filesizetotal = 0;
	if(isset($_GET['folder']) and $_GET['folder'] != '/'){
		$folder = $_GET['folder'];
		$lastchar = substr($folder, -1);
		if($lastchar != '/'){
			$folder = $folder.'/';
		}
		if (strpos($folder,'../') !== false) {
   			$folder = 'files/'.$userhash.'/';
		}
		if (strpos($folder,'./') !== false) {
   			$folder = 'files/'.$userhash.'/';
		}
		if($folder == ''){
			$folder = 'files/'.$userhash.'/';
		}
		if($folder == '/'){
			$folder = 'files/'.$userhash.'/';
		}
		if($folder == 'files/'){
			$folder = 'files/'.$userhash.'/';
		}
	}
	else{
		$folder = 'files/'.$userhash.'/';
	}
	$folder = htmlspecialchars($folder);
	$foldercomp = $serverroot.$folder; //Full path on server	
	//Get the current path
	$folderarr = str_replace('/', ' -> ',$folder);
	$folderarr = str_replace('files -> '.$userhash, 'Home',$folderarr);
	$folderarr = str_replace('-> var -> www -> jessedrive -> ', '', $folderarr);
	$folderarr = substr($folderarr, 0, -3);				
	
	echo '<!DOCTYPE HTML>
		<html>
		<!--
                                          ____.                               .___      .__                                                 
                                         |    | ____   ______ ______ ____   __| _/______|__|__  __ ____                                     
                                         |    |/ __ \ /  ___//  ___// __ \ / __ |\_  __ \  \  \/ // __ \                                    
                                     /\__|    \  ___/ \___ \ \___ \\  ___// /_/ | |  | \/  |\   /\  ___/                                    
                                     \________|\___  >____  >____  >\___  >____ | |__|  |__| \_/  \___  >                                   
                                                   \/     \/     \/     \/     \/                     \/                                    
                                            ___          ___     _______________  ____ .________                                            
                                           /  /   ____   \  \    \_____  \   _  \/_   ||   ____/                                            
                                          /  /  _/ ___\   \  \    /  ____/  /_\  \|   ||____  \                                             
                                         (  (   \  \___    )  )  /       \  \_/   \   |/       \                                            
                                          \  \   \___  >  /  /   \_______ \_____  /___/______  /                                            
                                           \__\      \/  /__/            \/     \/           \/                                             
     __                                                                       _____                       .__.__                            
    |__| ____   ______ ______ ____         ____   ____   ____   ____   ______/ ___ \  ____   _____ _____  |__|  |       ____  ____   _____  
    |  |/ __ \ /  ___//  ___// __ \       / ___\_/ __ \_/ __ \ /    \ /  ___/ / ._\ \/ ___\ /     \\__  \ |  |  |     _/ ___\/  _ \ /     \ 
    |  \  ___/ \___ \ \___ \\  ___/      / /_/  >  ___/\  ___/|   |  \\___ <  \_____/ /_/  >  Y Y  \/ __ \|  |  |__   \  \__(  <_> )  Y Y  \
/\__|  |\___  >____  >____  >\___  > /\  \___  / \___  >\___  >___|  /____  >_____\ \___  /|__|_|  (____  /__|____/ /\ \___  >____/|__|_|  /
\______|    \/     \/     \/     \/  \/ /_____/      \/     \/     \/     \/       /_____/       \/     \/          \/     \/            \/
			
		-->
		<head>
		<link rel="icon" type="image/png" href="favicon.png" />
		<!--VideoJS requirements start here -->
		<link href="http://vjs.zencdn.net/4.8/video-js.css" rel="stylesheet">
		<script src="http://vjs.zencdn.net/4.8/video.js"></script>
		<style type="text/css">
			.vjs-default-skin { color: #e9f00f; }
			.vjs-default-skin .vjs-play-progress,
			.vjs-default-skin .vjs-volume-level { background-color: #e3dd09 }
			.vjs-default-skin .vjs-control-bar { font-size: 60% }
		</style>
		<!--VideoJS requirements end here -->
		<title>JesseDrive</title>
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
		<meta charset="UTF-8">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript" src="js/plupload.full.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.plupload/jquery.ui.plupload.min.js"></script>
		<script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.min.js"></script>
		<script type="text/javascript" src="js/i18n/nl.js"></script>
		</head>
		<body background="images/JD-BG6.jpg">
		<div id="navbar">
		<div id="Title"><center><font color="white" size="4em"><a id="Title" href="index.php">JesseDrive</a></font></center></div>';
	echo '<!--<form action="upload.php?path='.$folder.'" method="post" enctype="multipart/form-data" id="uplform">-->
		<form action="mkdir.php" method="GET" id="mkdirform"><input type="hidden" name="folder" value="'.$folder.'"><input type="text" placeholder="Naam" autocomplete="off" name="dir" id="dir"><input type="submit" value="Maak map aan" id="mkdirbutton"></form>
		<form action="logout.php" id="logoutform"><input type="submit" id="submitlogout" value="Log uit"></form></div>';
	?>
	<div id="plupload_container">		
			<div id="plupload_options">
				<a id="plupload_selector" href="javascript:;">[Selecteer bestanden]</a>
				<a id="plupload_uploader" href="javascript:;">[Upload bestanden]</a>
			</div>
			<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
	<br>
	<script type="text/javascript">
		//Uploader JavaScript, API delivered by plupload (http://www.plupload.com)
		var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'plupload_selector', // you can pass an id...
		container: document.getElementById('plupload_container'), // ... or DOM Element itself
		url : '../upload.php',
		flash_swf_url : '../js/Moxie.swf',
		silverlight_xap_url : '../js/Moxie.xap',
		filters : {
			max_file_size : '100mb'
		},
		multipart_params: {
			folder_param_jd : <?php if($folder != ''){echo '\''.$folder.'\'';}?>,
		},
		init: {
			PostInit: function() {
				document.getElementById('filelist').innerHTML = '';
				document.getElementById('plupload_uploader').onclick = function() {
					uploader.start();
					return false;
				};
			},
			FilesAdded: function(up, files) {
				plupload.each(files, function(file) {
					document.getElementById('filelist').innerHTML += '<div id="' + file.id + '"><div class="plu_filename">' + file.name + ' (' + plupload.formatSize(file.size) + ')</div><div class="plu_filestatus"><b>0%</b></div></div><br>';
				});
			},
			FileUploaded: function(files){
				//make sure you get to see the uploaded file...
				//document.location.reload(true)
				
				var audio = new Audio('jdtone.mp3');
				audio.play();
				var queueval_jd = document.getElementById('filelist').innerHTML.indexOf('<b>0%</b>');
				if(queueval_jd < 0){					
					setTimeout(refresh_jd, 1000);
				}
			},
			UploadProgress: function(up, file) {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = file.percent + "%";
			},
			Error: function(up, err) {
				alert("Er is een fout opgetreden.\nError #" + err.code + ": " + err.message);
			}
		}
		});
		uploader.init();
		
		function refresh_jd(){
			document.location.reload(true);
		}
	</script>
	</div>	
	<?php
	$foldercontent = $foldercomp . "*";
	if (count(glob($foldercontent)) === 0 ){
		echo '<div id="folderarr">'.$folderarr.'</div>';
		echo '<div id="emfl">Lege map</div>';
	}
	else{
		$filecount = '0';
		$handle = opendir($foldercomp);
		while ($file = readdir($handle)){
			$files[] = $file;
			$filecount = $filecount + 1;
		}
		closedir($handle);	
		echo '<div id="folderarr">'.$folderarr.'</div>';
		if(isset($_GET['playvid'])){
			$videotitlearray = explode('/', $_GET['playvid']);
			$videotitle = $videotitlearray[count($videotitlearray)-1];
			$videotitle = str_replace('.mp4', '', $videotitle);
			echo '<div id="videoframe">
			<div id="frametitle">'.ucfirst($videotitle).'</div>
			<div id="frameclosebutton"><a href="index.php?folder='.$folder.'">[X]</a></div>
			<center><video id="video" class="video-js vjs-default-skin" controls
			preload="auto" width="80%" height="80%" poster="images/videoposter.jpg"
			data-setup="{}">
			<source src="'.$_GET['playvid'].'" type="video/mp4">
			<p class="vjs-no-js">Gelieve JavaScript aan te zetten om deze video te bekijken.</p>
			</video></center>
			</div>';
		}
		
		if(isset($_GET['edittext'])){
			$ttearray = explode('/', $_GET['edittext']); //tte = text to edit
			$ttetitle = $ttearray[count($ttearray)-1];
			$ttetitle = str_replace('.txt', '', $ttetitle);
			$sesexplode = explode(';', $_SESSION['JDLogin']);
			$user = $sesexplode[0];
			$userhash = $sesexplode[1];
			if($ttearray[1] == $userhash && strpos($_GET['edittext'], '../') === false){		
				echo '<div id="TEFrame">	<!-- TextEditFrame -->
				<div id="frametitle">'.ucfirst($ttetitle).'</div>
					<div id="frameclosebutton"><a href="index.php?folder='.$folder.'">[X]</a></div>				
					<form action="savetxt.php" id="savetxtform" method="post">
						<input type="hidden" id="savetxtloc" name="savetxtloc" value="'.$_GET['edittext'].'">
						<textarea id="tef_txarea" name="savetxtval">';
						echo file_get_contents($_GET['edittext']);
					echo '</textarea>
						<input type="submit" id="savetxtbutton" value="Opslaan">
					</form>
				</div>	
				</div>';
			}
			else{
				HackAttempt($user, ' heeft een hackpoging gedaan door het proberen te wijzigen van de mapstructuur tot de serverroot.');
			}
		}
		$fileextension = null;
		echo '<center><table id="index_file_table"><div>';
		echo '<tr><td>Naam </td><td> Type</td><td>Grootte</td><td>Opties</td></tr>';
		foreach ($files as $file) {
			$filecomp = $file;
			$filevar = $file;
			$filearray = explode('.', $file);
			$arrsize = count($filearray);
			if (is_dir($serverroot . $folder . $file . '/') == 1){
				$fileextension = 'Map';
				$fileextid = 'Map';
				$filename = implode('.', $filearray);
			}
			elseif($arrsize == '1'){
				$filename = $filearray[0];
				$fileextension = 'Extensieloos bestand';
				$fileextid = 'Extensieloos bestand';
				$filesize =  filesize($serverroot.$folder.$file);
			}
			else{
				$filename = null;
				for($i = 0; $i < $arrsize - 1; $i++){
					$filename = $filename . '.' . $filearray[$i];
				}
				$filename = substr($filename, 1); //Removes the first dot
				$fileextension = $filearray[$arrsize - 1];
				require('filetyperec.php');
				$filesize =  filesize($foldercomp.$file);
			}
			if($file != '.' && $file != '..'){
				if($filevar == $fileextension){
					$fileextension = 'Map';
				}			
				$filesizetotal = null;
				if($fileextension != 'Map'){
					if($fileextid == 'mp4'){
						echo '<tr class="filelisting">
								<td id="flname"><a href="'.htmlspecialchars($folder).htmlspecialchars($file).'" download>'.ucfirst(htmlspecialchars($filename)).'</a></td>
								<td id="fltype">'.$fileextension.'</td>
								<td id="flsize">'.TransformFileSize($filesize).'</td>
								<td id="flopti"><a href="delete?type=nfile&file='.urlencode($folder.$file).'&folder='.$folder.'">Verwijder</a> | <a href="index?folder='.$folder.'&playvid='.$folder.$file.'">Afspelen</a></td>
							</tr>';
					}
					elseif($fileextid == 'txt'){
						echo '<tr class="filelisting">
								<td id="flname"><a href="'.htmlspecialchars($folder).htmlspecialchars($file).'" download>'.ucfirst(htmlspecialchars($filename)).'</a></td>
								<td id="fltype">'.$fileextension.'</td>
								<td id="flsize">'.TransformFileSize($filesize).'</td>
								<td id="flopti"><a href="delete?type=nfile&file='.urlencode($folder.$file).'&folder='.$folder.'">Verwijder</a> | <a href="index?folder='.$folder.'&edittext='.$folder.$file.'">Bewerken</a></td>
							 </tr>';
					}
					else{
						echo '<tr class="filelisting">
								<td id="flname"><a href="'.htmlspecialchars($folder).htmlspecialchars($file).'" download>'.ucfirst(htmlspecialchars($filename)).'</a></td>
								<td id="fltype">'.$fileextension.'</td>
								<td id="flsize">'.TransformFileSize($filesize).'</td>
								<td id="flopti"><a href="delete?type=nfile&file='.urlencode($folder.$file).'&folder='.$folder.'">Verwijder</a></td>
							</tr>';
					}
				}
				elseif($fileextension == 'Map'){
					$size = getDirectorySize($folder.$file);
					echo '<tr class="filelisting">
							<td id="flname"><a href="index?folder='.htmlspecialchars($folder).htmlspecialchars($file).'/'.'">'.ucfirst(htmlspecialchars($filename)).'</a></td>
							<td id="fltype">'.$fileextension.'</td>
							<td id="flsize">'.TransformFileSize($size).'</td>
							<td id="flopti"><a href="delete?type=folder&file='.urlencode($folder.$file).'&folder='.$folder.'">Verwijder</a></td>
						</tr>';
				}
				else{
					echo 'Error!! Some file was skipped. Note to self: Jesse ge suckt man';
				}
			}
		}
	}
		
	
	//Show all errors
	echo '</table></div><!--extra divs to center an absolute div. Absolute div necessary for z-index.-->';
		if(isset($_GET['mke'])){
	      		echo '<div style="position: absolute; left: 50%; top: 300px;"><div id="ue" style="position: relative; left: -50%;">Oh nee! Er is een error opgetreden tijdens het aanmaken van je map.<br>
	      		Gelieve het opnieuw te proberen of contacteer de administrator (Jesse Geens).</div></div>';
		}
		if(isset($_GET['mme'])){
	      		echo '<div style="position: absolute; left: 50%; top: 300px;"><div id="ue" style="position: relative; left: -50%;">Gelieve een geldige naam in te geven voor je map.</div></div>';
		}
		if(isset($_GET['had'])){
	      		echo '<div style="position: absolute; left: 50%; top: 300px;"><div id="ue" style="position: relative; left: -50%;">
						Opgelet! Er is een hackpoging gedetecteerd en weggelogd. <br>
						Dit kan leiden tot een permante ban van de dienst.</div></div>';
		}
		if(isset($_GET['utb']) || getDirectorySize($serverroot.'files/'.$userhash.'/') > $max_user_space_bytes && !isset($_GET['had'])){
	      		echo '<div style="position: absolute; left: 50%; top: 300px;"><div id="ue" style="position: relative; left: -50%;">Het lijkt er op dat je al je opslagruimte gebruikt hebt. <br>
	      		Probeer wat ruimte vrij te maken door bestanden te verwijderen.</div></div>';
		}
	echo '</center><br><br>';
	
	
	//Calculate free and used space
	$fst = getDirectorySize($serverroot.'files/'.$userhash.'/'); //Used space
	$fsf = $max_user_space_bytes - $fst; //Free space
	$fsp = number_format($fst / $max_user_space_bytes * 100, 1); //used space percentage
	$fss = TransformFileSize($fsf).' beschikbaar van '.TransformFileSize($max_user_space_bytes).' ('.$fsp.'% in gebruik)';
	echo '<div id="footer">'.$fss.' | &copy;Jesse Geens - 2014-2015 | Versie 2.0a-alpha (release ClassyPanda)</div>
			</body></html>';
}
else{
	header('location:login');
}
