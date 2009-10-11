<?php

//Definition des Systempfades
define('PROJECT_DOCUMENT_ROOT', dirname(__FILE__));

//Projektname
$project = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace("\\", "/", dirname(__FILE__)));

//Protokoll der Verbindung (HTTP oder HTTPS)
(!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off') ? 
    $protocol = 'http://' : $protocol = 'https://';

//PROJECT Pfad (für das Web)
define('PROJECT_HTTP_ROOT', $protocol.$_SERVER['HTTP_HOST'].$project);    

?>