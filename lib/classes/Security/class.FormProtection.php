<?php

class FormProtection
{
  
    public function generateToken()
    {
        //Token erstellen mit Zufallszahl und Zeitstempel
        $token = md5(mt_rand().time());
        
        //Generiertes Token in Session schreiben
        $_SESSION['token'] = $token;
        return $token;
    }
  
    public function checkToken()
    {
        //Abfrage, ob Token gesetzt
        if (isset($_POST['token'])) {
            
            //Abfrage, ob das übergebene Token mit dem Token in der Session übereinstimmt
            if (isset($_SESSION['token']) AND $_POST['token'] == $_SESSION['token']) {
                
                //Token in Session löschen, damit ein erneutes Ausführen der POST-Anfrage nicht mehr möglich ist.
                $_SESSION['token'] = '';
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