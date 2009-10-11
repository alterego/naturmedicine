<?php

//Fehlerreporting (für Live-Betrieb auskommentieren)
error_reporting(E_ALL);
//ini_set('display_errors', 0);
//ini_set('log_errors', 1);
//ini_set('error_log', '/home/www/web135/html/julia/log/phpError.txt');

//Server-Pfad zum Hauptverzeichnis der Webseite
define('SERVER_ROOT_PATH', 'http://localhost/naturmedicine');

//Ganzer Server-Pfad zum Hauptverzeichnis der Bilder (Ordner 'images')
define('PROJECT_ROOT_PATH', '/var/www/naturmedicine/');

//URL des RSS-Feeds (Konzerte)
define('URL_RSS_KONZERTE', "http://localhost/naturmedicine/feeds/konzerte_feed_rss.php");

//Konstante Inhalte für den Header des RSS-Feeds (Konzerte)
define('RSS_TITLE', "Естественная медицина - Konzerte-Feed");
define('RSS_LINK', "http://localhost/naturmedicine/konzerte.php");
define('RSS_DESCRIPTION', "Mit diesem Feed werden Sie über die aktuellen Auftritte von Естественная медицина informiert.");
define('RSS_LANGUAGE', "ru-ru");

//Datenbankverbindungs-Daten
define('DB_SERVER', "localhost");
define('DB_NAME', "naturmedicine");
define('DB_USER', "root");
define('DB_PASSWORD', "rosas");

//FTP-Verbindungs-Daten
define('FTP_SERVER', "127.0.0.1");
define('FTP_USER', "masueger");
define('FTP_PASSWORD', "masueger");

//HTML-TITEL
define('HTML_TITLE', "Естественная медицина");

//Whitelist für GET-Variable der Unternavigation
$Whitelist_GET_subnav = array(                           
                           1 => 'philosophy',
                           2 => 'chiefdoctor',
                           3 => 'homeopaths',                                                      
                           4 => 'gynecologists',
                           5 => 'therapists',
                           6 => 'neurologists',
                           7 => 'psychologists',                           
                           8 => 'administration',
                           9 => 'articles_philosophy',
                          10 => 'lifestyle',
                          11 => 'when_call_doctor',
                          12 => 'our_philosophy', 
                          13 => 'how_find_doctor',
                          14 => 'what_to_read',
                          15 => 'advertisements_patients',
                          16 => 'clinical_discussions',
                          17 => 'coming_seminars',
                          18 => 'past_seminars',
                          19 => 'advertisements_doctors',
                          20 => 'what_is_homeopathy',
                          21 => 'articles_methodology',
                          22 => 'articles_simple_answers',
                          23 => 'guidebooks',
                          24 => 'for_parents',
                          25 => 'for_specialists', 
                          26 => 'editions',
                          27 => 'parent_schools',
                          28 => 'info_sources',
                          29 => 'centers_and_doctors',
                          30 => 'contact_address',
                          31 => 'roadmap',                         
                          32 => 'home',
                          33 => 'patients',
                          34 => 'doctors',
                          35 => 'articles',
                          36 => 'books',
                          37 => 'links',
                          38 => 'contact',  
                          39 => 'impressum',
                          40 => 'sitemap'                                                 
                       );

//E-Mail-Adresse für Kontaktformulare
define('FORM_EMAIL', 'contact@naturmedicine.ru');

//Salt für die Login-Authentifizierung
define('SALT_LOGIN', '88188');

?>