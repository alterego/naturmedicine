<?php

class Security
{  
  
    //Session-Objekt (wenn Session abgelaufen= true)
    public $SessionExpired = false;
  
  
    /**
     * GET-Variablen-Überprüfung
     * 
     * Überprüft, ob die übergebene GET-Variable in der vordefinierten Whitelist vorhanden ist.
     * Im Erfolgsfall wird die Variable zurückgegeben, im Fehlerfall wird die aktuelle Seite nochmals angezeigt. 
     *  
     * @param string $GETVariable:  Inhalt der übergebenen GET-Variable
     * @param array $array:         Das Array mit den erlaubten Werten
     */
    public static function checkWhitelistGET($GETVariable, $array)
    {        
        if (!in_array($GETVariable, $GLOBALS['Whitelist_GET_'.$array.''], true)) {
            return false;
        }
    }
    
    /**
     * Authentifizierung des Benutzers
     */
    public function checkLoginstatus()
    {
        if (isset($_SESSION['username'])) {

            //Der Benutzer ist authentifiziert.
            return true;
        }
        else if ($this->SessionExpired === true) {          
            
            //Die Session ist abgelaufen und der Benutzer wird auf die Loginseite des Admin-Bereichs weitergeleitet.                
            header('location:'.SERVER_ADMIN_ROOT_PATH.'/index.php?login=SessionExpired');
        }        
        else {
            //Der Benutzer ist nicht authentifiziert und wird auf die Loginseite des Admin-Bereichs weitergeleitet.                          
            header('location:'.SERVER_ADMIN_ROOT_PATH.'/index.php?login=failed');            
        }
    }    
  
}

?>