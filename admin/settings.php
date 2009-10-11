<?php

//Fehlerreporting
error_reporting(E_ALL);

//Server-Pfad zum Hauptverzeichnis der Webseite
define('SERVER_ROOT_PATH', 'https://localhost/julia/');

//Server-Pfad zum Hauptverzeichnis des Adminbereichs
define('SERVER_ADMIN_ROOT_PATH', 'https://localhost/julia/admin');

//Server-Pfad vom Hauptverzeichnis zu den Bildern (Ordner 'images')
define('PROJECT_DOWNLOAD_PATH', 'download');

//Server-Pfad vom Hauptverzeichnis zu den Bildern (Ordner 'images')
define('SERVER_DOWNLOAD_PATH', '/var/www/julia/download/');

//Server-Pfad vom Hauptverzeichnis zu den Bildern (Ordner 'images')
define('PROJECT_IMAGES_PATH', 'images');

//Ganzer Server-Pfad zum Hauptverzeichnis der Bilder (Ordner 'images')
define('SERVER_IMAGES_PATH', '/var/www/julia/images/');

//Server-Pfad zum Hauptverzeichnis der Bilder (Ordner 'images')
define('PROJECT_VIDEO_PATH', 'media/video');

//Server-Pfad zum Hauptverzeichnis der Audio-Dateien (Ordner 'audio')
define('PROJECT_AUDIO_PATH', 'media/audio');

//Ganzer Server-Pfad zum Hauptverzeichnis der Audio-Dateien (Ordner 'audio')
define('SERVER_AUDIO_PATH', '/var/www/julia/media/audio/');

//Ganzer Server-Pfad zum Hauptverzeichnis der Video-Dateien (Ordner 'video')
define('SERVER_VIDEO_PATH', '/var/www/julia/media/video/');

//Datenbankverbindungs-Daten
define('DB_SERVER', "localhost");
define('DB_NAME', "julia");
define('DB_USER', "root");
define('DB_PASSWORD', "rosas");

//FTP-Verbindungs-Daten
define('FTP_SERVER', "127.0.0.1");
define('FTP_USER', "masueger");
define('FTP_PASSWORD', "masueger");

//HTML-TITEL
define('HTML_TITLE', "Julia Schiwowa");

//Whitelist für GET-Variable der Unternavigation
$Whitelist_GET_subnav = array(                           
                           1 => 'oper',
                           2 => 'rollen',
                           3 => 'geistlich',                                                      
                           4 => 'liedzyklen',
                           5 => 'franzoesisch',
                           6 => 'deutsch',
                           7 => 'englisch',                           
                           8 => 'szenisch',
                           9 => 'portraet',
                          10 => 'medien_musik',
                          11 => 'medien_klassisch',
                          12 => 'medien_chanson', 
                          13 => 'medien_filme',
                          14 => 'downloads_fotos',
                          15 => 'downloads_cv',
                          16 => 'klassisch',
                          17 => 'chanson',
                          18 => 'projekte_duo',
                          19 => 'projekte_publikation',
                          20 => 'projekte_forschung',
                          21 => 'klassisch',
                          22 => 'chanson'                          
                       );

//E-Mail-Adresse für Kontaktformulare
define('FORM_EMAIL', 'info@juliaschiwowa.com');

//Salt für die Login-Authentifizierung
define('SALT_LOGIN', '88188');

?>