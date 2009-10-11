<?php

require_once 'common.php';

$GLOBALS['SECURITY']->checkLoginStatus();

HTML::printHeader();
HTML::printBody();

Navi::printMainNavi('reviews');
Navi::printLocation();

$GLOBALS['CONTENT']->selectContent();
$GLOBALS['CONTENT']->printEntries();

HTML::printFooter();

?>