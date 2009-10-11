<?php

//Basissystem einbinden
require_once 'common.php';

//HTML-Head und Beginn des Body-Teils ausgeben
HTML::printHeader();
HTML::printBody();

//Haupt- und Unternavigation ausgeben
Navi::printMainNavi("kontakt");
Navi::printSubNavi();

//Inhalt der Seite aus Datenbank holen und ausgeben
$Content = new Content();
$Content->selectContent();
$Content->printContent();

//News-Feld mit Ausgabe
//$News = new News();
//$News->printFieldNews("3");

//Abschluss des Body-Teils und Html-Teils ausgeben
HTML::printFooter();

?>