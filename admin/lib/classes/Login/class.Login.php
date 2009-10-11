<?php

class Login
{
    
    private $DB = null;
    
    
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
    }
    
    /**
     * Ausgabe des Login-Formulars
     *  
     * @param string $checkScript[optional]:  Datei, welche den Benutzer authentifiziert
     */
    public function printLoginForm($checkScript = null)
    {                              
          echo '          
          <form id="form" name="login_form" action="'.$checkScript.'" method="post">';
          
          //Wenn der Benutzer nicht authentifiziert wurde, wird eine Fehlermeldung ausgegeben.
          if (!isset($_GET['login'])) {
              $_GET['login'] = '';
          }         
          if ($_GET['login'] == 'failed') {
              echo '
            <p class="mb error">Fehler!</p>
            <p class="error">Die Authentifizierung hat fehlgeschlagen. Benutzername oder Passwort ist nicht korrekt.</p><br />';
          } 
          if ($_GET['login'] == 'SessionExpired') {
              echo '
            <p class="mb error">Hinweis!</p>
            <p class="error">Die Session war länger als 15 Minuten inaktiv und wurde aus Sicherheitsgründen beendet.</p><br />';
          }           
          
          echo '         
            <p class="m">Login:</p>
            <input type="text" name="login" size="30" maxLength="100"><br />
            <p class="m">Passwort:</p>
            <input type="password" name="password" size="30" maxLength="100">
            <input type="submit" name="doLogin" value="anmelden">
          </form>';
    }
  
    /**
     * Überprüfung der Benutzereingaben
     * 
     * @param string $salt:  "Salz" für das Passwort (muss in DB mitgespeichert worden sein) 
     */
    public function checkLoginData($salt)
    {
        //Keine direkte Abfrage in der Datenbank (Erste Angriffsstelle)
        //Das erste Zeichen der Eingabe verwenden, um eine eingeschränkte Ergebnismenge zu erhalten.
        $firstChar = substr($_POST['login'], 0, 1);
        
        //Ergebnismenge aus der Datenbank holen
        $sql    = "SELECT login, password FROM user WHERE login LIKE '".$this->DB->MySQLiObj->real_escape_string($firstChar)."%'";
        $result = $this->DB->query($sql);        
        
        //Nur die ersten 100 Zeichen der Eingabe verwenden (Gegen Buffer Overflow)
        $login    = trim(substr($_POST['login'], 0, 100));
        $password = trim(substr($_POST['password'], 0, 100));
                        
        while ($data = $result->fetch_assoc()) {        
           
            //Benutzer ist authentifiziert
            if ($login == $data['login'] AND hash('sha256', $password.$salt) == $data['password']) {
                
                //Session_id neu setzen (Gegen Session-Fixation)
                session_regenerate_id();
                
                //Session-Daten speichern
                $_SESSION['username']      = $login;
                $_SESSION['loggedInSince'] = date('d.m.Y H:i', time());
                
                return true;
            }                            
        } 
        
        //Benutzer nicht authentifiziert
        return false;                
    }
  
}

?>