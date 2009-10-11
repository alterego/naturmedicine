<?php

require_once 'common.php';
require_once 'lib/classes/Login/class.Login.php';

$LOGIN = new Login();
$loginOk = $LOGIN->checkLoginData(SALT_LOGIN);

if ($loginOk === true) {    
    
    //Der Benutzer ist authentifiziert und wird auf die Startseite des Admin-Bereichs weitergeleitet.
    header('location:'.SERVER_ADMIN_ROOT_PATH.'/home.php');
}
else {
    
    //Der Benutzer ist nicht authentifiziert und wird wieder auf die Loginseite des Admin-Bereichs weitergeleitet.    
    header('location:'.SERVER_ADMIN_ROOT_PATH.'/index.php?login=failed');    
}

?>