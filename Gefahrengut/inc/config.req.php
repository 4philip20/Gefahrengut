<?php
 $imgBanner = "images/dienstleistungen_de.jpg";
 $imgLogo   = "images/esa_logo_de.jpg";
 $htmlTitel = "";

// Diverse Funktionen und Formulare... 
function show_FormularSuche ($lv_htmlTitel,$lv_formText01,$lv_formText02,$lv_L) {
echo '<!-- Formular1 -->
<!--Formular-->
<form action="'.$_SERVER['PHP_SELF'].'?vt=S&l='.$lv_L.'" method="post" enctype="multipart/form-data" id="MyUploadForm">
  <div class="container">
	<div class="row">
		<h1>'.$lv_formText01.'</h1>
		<div class="mat-input">
		<p class="text">'.$lv_formText02.'</p>
                <div class="mat-input-outer">
                    <input id="Materialnr" name="Materialnummer" type="text"/>
                    <label class=""></label>
                    <div class="border"></div>
                </div>     			
            </div>
	</div>
</div>
	<div class="container">
	<div class="row"><br>
		<button name="button" id="btnDownload" class="button">Dowload</button>
		
	</div>
</div>
</form>';
} 
// *****************************************************
function show_FormularError ($lv_htmlTitel,$lv_formText01,$lv_formText02, $lv_L) {
echo '<!--Formular-->
<form action="'.$_SERVER['PHP_SELF'].'?vt=&l='.$lv_L.'" method="post" enctype="multipart/form-data" id="MyUploadForm">
  <div class="container">
	<div class="row">
		<h1>'.$lv_formText01.'</h1>
		<p class="text">'.$lv_formText02.'</p>
	</div>
  </div>
	<div class="container">
	<div class="row"><br>
		
		<input id="downloadbutton" type="image" src="images/refresh.png"/>
	</div>
</div>
</form>';
} 
// *****************************************************
function suche_Download($lv_L){
$intDebug = false;
	// Verbindungs Variablen
    $ftp_server = "newsletter.esa.ch"; // Address of FTP server.
    $ftp_user_name = "p246178f1";      // Username
    $ftp_user_pass = "EMark1291!";     // Password
	$ftp_path      = "/gg";            // Path auf dem Server wo die Files abgelegt sind
	$ftp_filename  = "";
	$ftp_found     = false;
// Formular Var's in Variable abfüllen
if (! isset($_POST["Materialnummer"])) {$Materialnummer = "";} else {$Materialnummer = trim($_POST["Materialnummer"]); };

if( $Materialnummer != ""){
// Wenn Parameter OK sind (Matnummer nicht leer) FTP Vernindung aufbauen
	if ($intDebug) {echo " DEBUG: Materialnummer ist OK = [$Materialnummer]<br>";}
	// Verbindung aufbauen
	$conn_id = ftp_connect($ftp_server) or die("Keine Verbindung zu Server möglich, versuchen Sie es später.");

	//Anmeldung
	if ($intDebug) {echo " DEBUG: Anmeldung FTP User = [$ftp_user_name], PWD = [$ftp_user_pass]<br>";}
	if (@ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) {
		if ($intDebug) {echo "DEBUG: Angemeldung OK<br>\n"; }
	} else {
		echo "Anmeldung nicht möglich !<br>\n";
	}
//Wenn Verbindung OK Directory lesen oder Prüfen ob File auf dem FTP-Server existiert
	if (ftp_chdir($conn_id, $ftp_path) == true ) {  // Pfad wechsel nach ../gg
		$allFiles = ftp_nlist($conn_id, '.');       // Besorge alle Dateien im aktuellen Verzeichnis & speichere diese unter allFiles
		foreach($allFiles as $ftp_filename)                 //foreach alle dateien in $materialnr.pdf
		{
			if ($intDebug) {echo "DEBUG: Files auf FTP-Server = [$ftp_filename] = [$Materialnummer.pdf]<br>\n"; }
			if($ftp_filename == $Materialnummer . '.pdf' )  // Prüfe ob der aktuelle Dateiname aus der Liste $allFiles dem gesuchten entspricht
			{ // Gefunden
				if ($intDebug) {echo "DEBUG: Files gefunden= [$ftp_filename] = [$Materialnummer.pdf]<br>\n"; }
				$ftp_found = true;
				break;             // Breche die Schleife ab
			} else {
				if ($intDebug) {echo "DEBUG: Files NICHT gefunden= [$ftp_filename] = [$Materialnummer.pdf]<br>\n"; }
				$ftp_found = false;
			}
		}
	} else {
		echo "Path nicht gefunden!<br>\n";
		$ftp_found = false;
	}

	if ($ftp_found and !$intDebug) {	
// File oder Link zum Browser senden
		header("Cache-Control: no-cache, must-revalidate");
		header('Content-Disposition: attachment; filename="'.$ftp_filename.'"');
		// *** Wenn PDF nur im Browser angezeigt werden soll, aber nicht automatisch vom browser Gespeichert wird.
		//header('Location: http://newsletter.esa.ch/gg/'.$ftp_filename );
		
		//*** File fom FTP Server lesen und downloden.
		ob_start();           // Ausgabepufferung aktivieren
			ftp_get($conn_id, 'php://output', $ftp_filename, FTP_BINARY);
 		ob_end_flush();
// FTP Verbindung schliessen
		ftp_close($conn_id);

	} else {
// Leider nicht gefunden
// FTP Verbindung schliessen
		ftp_close($conn_id);

		header('location:'.$_SERVER['PHP_SELF'].'?vt=E1&l='.$lv_L.'');   
	}

	if ($intDebug) {echo "DEBUG: Am schluss der Sucherei<br>\n"; }
}else {
   header('location:'.$_SERVER['PHP_SELF'].'?vt=E2&l='.$lv_L.'');   
} 	
	
}
// ***************************************************** 
 ?>