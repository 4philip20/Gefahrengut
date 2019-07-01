<?php
// Formular Parameter prüfen und in  Variablen Versorgen.
if (! isset($_GET["vt"])) {$paramVT = '';} else { $paramVT = $_GET["vt"]; };
if (! isset($_GET["l"])) {$paramLang = '';} else { $paramLang = $_GET["l"]; };

require("inc/config.req.php");
include_once ('inc/http_sprache.function.php');
$allowed_langs = array ('de', 'fr', 'it');
$langbrowser = lang_getfrombrowser ($allowed_langs, 'de', null, false);
if($paramLang <> ""){$langbrowser = $paramLang;}
//var_dump($paramLang);
//var_dump ($langbrowser);
//var_dump ($paramVT);
// Variablen zB. f�r Header 
if($langbrowser == "de")
	{
		$htmlTitel = "Gefahrengut";
		$imgBanner = "images/dienstleistungen_de.jpg";
		$imgLogo   = "images/esa_logo_de.jpg";
		$formText01	   = "Gefahrengut";
		$formText02	   = "Bitte geben Sie die Materialnummer ein um das PDF des Gefahrengut Materials zu downloaden";
		$formText02E1	   = "Datei nicht gefunden, bitte erneut versuchen";
		$formText02E2	   = "Nummer ist leer, bitte erneut versuchen";		
	}
	else if($langbrowser == "fr")
	{
		$htmlTitel = "Produits dangereux";
		$imgBanner = "images/dienstleistungen_fr.jpg";
		$imgLogo   = "images/esa_logo_fr.jpg";
		$formText01	   = "Produits dangereux";
		$formText02	   = "Veuillez saisir le numéro de référence pour télécharger le pdf des matériaux composant le produit dangereux.";
		$formText02E1	   = "Fichier introuvable.";
		$formText02E2	   = "Le numéro est vide, veuillez refaire un essai";		
	}
	else if($langbrowser == "it")
	{
		$htmlTitel = "Merci pericolose";
		$imgBanner = "images/dienstleistungen_it.jpg";
		$imgLogo   = "images/esa_logo_it.jpg";
		$formText01	   = "Merci pericolose";
		$formText02	   = "Si prego di inserire il numero di materiale, per scaricare il PDF della merce pericolosa";
		$formText02E1	   = "File non trovato";
		$formText02E2	   = "Numero è vuoto, si prega di riprovare";		
	} 

//  HTML Inhalte Start**********************************
if ($paramVT == ""){
	include("inc/header.inc.php");
	show_FormularSuche ($htmlTitel,$formText01,$formText02, $langbrowser);
	include("inc/footer.inc.php");
} else if ($paramVT == "E1"){
	include("inc/header.inc.php");
	show_FormularError ($htmlTitel,$formText01,$formText02E1, $langbrowser);
	include("inc/footer.inc.php");
} else if ($paramVT == "E2"){
	include("inc/header.inc.php");
	show_FormularError ($htmlTitel,$formText01,$formText02E2, $langbrowser);
	include("inc/footer.inc.php");

} else if ($paramVT == "S"){	
	suche_Download($langbrowser);
}
//  HTML Inhalte Ende ***********************************
?>