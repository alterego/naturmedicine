<?php
header('Content-Type: text/html; charset=utf-8');

#Projektpfade
include(dirname(__FILE__).'/paths.php');

#Datenbanksettings und System-Einstellungen
require_once PROJECT_DOCUMENT_ROOT.'/settings.php';

#Alle Basis-Klassen einbinden
require_once PROJECT_DOCUMENT_ROOT.'/lib/includeAllClasses.php';

#Datenbankobjekt auf Existenz überprüfen und ggf. erstellen
if (!isset($GLOBALS['DB'])) {
    $DB = new MySQL(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
}

#FTP-Objekt auf Existenz überprüfen und ggf. erstellen
if (!isset($GLOBALS['FTP'])) {
    $FTP = new FTP(FTP_SERVER, FTP_USER, FTP_PASSWORD);
}

#Security-Objekt auf Existenz überprüfen und ggf. erstellen
if (!isset($GLOBALS['SECURITY'])) {
    $SECURITY = new Security();
}

#Security-Objekt auf Existenz überprüfen und ggf. erstellen
if (!isset($GLOBALS['CONTENT'])) {
    $CONTENT = new Content();
}

#EntryHandler-Klasse erstellen
$EntryHandler = new EntryHandler();

#Session-Objekt erstellen
new SessionHandler();

?>