<?php

require_once 'common.php';
require_once PROJECT_DOCUMENT_ROOT."/lib/classes/DateTime/class.DateTimeFunc.php";

$GLOBALS['SECURITY']->checkLoginStatus();

HTML::printHeader();
HTML::printBody();

Navi::printMainNavi('reviews');
Navi::printLocation();

$GLOBALS['CONTENT']->selectContent();
$GLOBALS['CONTENT']->printEntries();

HTML::printFooter();

?>