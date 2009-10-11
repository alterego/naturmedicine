<?php

require_once 'common.php';
require_once 'lib/classes/Login/class.Login.php';
require_once 'lib/classes/HTML/class.HTMLLogin.php';

HTMLLogin::printHeader();
HTMLLogin::printBody();

$LOGIN = new Login();
$LOGIN->printLoginForm('checkLogin.php');

HTMLLogin::printFooter();

?>