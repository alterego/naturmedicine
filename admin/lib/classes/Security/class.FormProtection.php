<?php

class FormProtection
{
  
    public static function generateToken($tokenSesName)
    {
        //Token erstellen mit Zufallszahl und Zeitstempel
        $token = md5(mt_rand().time());
        
        //Generiertes Token in Session schreiben
        $_SESSION[$tokenSesName] = $token;
        return $token;
    }
  
    public static function checkToken($tokenSesName)
    {
        //Abfrage, ob Token gesetzt
        if (isset($_POST[$tokenSesName])) {
            
            //Abfrage, ob das übergebene Token mit dem Token in der Session übereinstimmt
            if (isset($_SESSION[$tokenSesName]) AND $_POST[$tokenSesName] == $_SESSION[$tokenSesName]) {
                
                //Token in Session löschen, damit ein erneutes Ausführen der POST-Anfrage nicht mehr möglich ist.
                $_SESSION[$tokenSesName] = '';
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return 'noToken';
        }
    }
  
}

?>