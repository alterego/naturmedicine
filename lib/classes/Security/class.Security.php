<?php

class Security
{
  
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
  
}

?>