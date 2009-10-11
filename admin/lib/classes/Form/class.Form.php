<?php

class Form
{    
    
    //Fehler-Status
    public $ErrorStatus = null;

    //Fehler-Array
    public $ErrorObj = array();
        
    
    
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
    }
      
  
    //Eintragsformular ausgeben
    public function printForm($actScript = null)
    {        
        if (!isset($_POST['email'])) {
            $_POST['email'] = "";
        }
        if (!isset($_POST['text'])) {
            $_POST['text'] = "";
        }           
        
        //Ausgabe des öffnenden Formular-Divs
        echo '
        <div class="strich_k"></div>
        <p class="left">Eine weitere Kontaktmöglichkeit bietet das untenstehende Formular. Für eine eventuelle Rückmeldung tragen Sie Ihre E-Mailadresse ein.</p>
        <div id="container_gastformular">';        
        
        //Überprüfen, ob das Formular abgeschickt wurde
        if (isset($_POST['submit'])) {   

            //Die Methode zur Überprüfung des Formular-Tokens wird aufgerufen und der Rückgabewert gespeichert.
            $resultToken = FormProtection::checkToken();

            //Die Daten stammen nicht aus einem Formular der Webseite
            if ($resultToken === 'noToken') {
                echo '<p class="fehler_text">Bitte benutzen Sie nur Formulare dieser Webseite.</p>';
            } 
            
            //Die Nachricht wurde bereits versandt (Reload)
            else if ($resultToken === false) {
                echo '<p class="bold">Die Nachricht wurde bereits versandt.</p>';
            }
            
            //Die Formularüberprüfung ist ok und die übergebenen Daten werden auf Korrektheit überprüft.
            else {
            
                //Wenn die Datenvalidierung 'true' zurück gibt, sind die Daten in Ordnung und das Mail wird versandt.
                if ($this->checkData() === true) {              
                    
                    //Wenn der Versand erfolgreich war, wird eine bestätigende Meldung ausgegeben.
                    if ($this->sendData() === true) {                
                        echo '<p class="bold">Ihre Nachricht wurde erfolgreich versandt.</p><br />';
                    }
                    
                    //War der Versand nicht möglich, wird eine Fehler-Meldung angezeigt.
                    else {
                        $this->ErrorStatus = true;                                                    
                        $this->ErrorObj[] = 'Ihre Nachricht konnte nicht versandt werden.<br />
                                            Versuchen Sie es später nochmals.</p>';
                    }
                }
    
                //Ausgabe von allfälligen Fehler-Meldungen
                if ($this->ErrorStatus === true) {
                    $this->printErrors();
                }          
            }
        }
                
        //Wenn die Daten erfolgreich versandt wurden, wird das Formular leer angezeigt.
        if ($this->ErrorStatus === false) {
            $_POST['email'] = '';
            $_POST['text']  = '';
        }     
        
        //Token für Formular erstellen
        $token = FormProtection::generateToken();   
            
            //Eigentliche Formular-Ausgabe
            echo '
          <form action="'.$actScript.'" accept-charset="utf-8" method="post">
            <p class="form_text">E-Mail: </p>
            <input class="form_width" type="text" name="email" maxlength="150" value="'.$_POST['email'].'" />
            <p class="form_text">Nachricht: </p>
            <textarea class="form_width" name="text" rows="6">'.$_POST['text'].'</textarea>
            <input class="form_submit" type="submit" name="submit" value="abschicken" />
            <input type="hidden" name="token" value="'.$token.'" />
          </form>
        </div>';              
    } 
    
    
    private function checkData()
    {
        $error = "";
        
        //Wenn aus dem Formularfeld 'E-Mail' Daten übergeben wurden, werden diese auf unerlaubte Zeichen überprüft.
        if (!empty($_POST['email'])) {
           
            //Daten enthalten unerlaubte Zeichen und eine Fehlermeldung wird ausgegeben.
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE)) {
                $this->ErrorObj[] = "Bitte geben Sie eine korrekte E-Mail-Adresse ein.";
                $error = "true";
            }
        }

        //Wenn keine Daten aus dem Formularfeld 'Text' übergeben wurden, wird eine Fehlermeldung ausgegeben.
        if (empty($_POST['text'])) {
            $this->ErrorObj[] = "Bitte geben Sie einen Text ein.";
            $error            = "true";
        }
        
        //Daten wurden übergeben und werden auf unerlaubte Zeichen überprüft.
        else {
          
            //Daten sind in Ordnung
            if ($_POST['text']) {
                
            }
            
            //Daten enthalten unerlaubte Zeichen und eine Fehlermeldung wird ausgegeben.
            else {
                $this->ErrorObj[] = "Das Eingabefeld 'Nachricht' enthält unerlaubte Steuerzeichen.";                
                $error = "true";
            }            
        }

        //Wenn die Daten in Ordnung sind, wird der ErrorStatus auf 'false', ansonsten auf 'true' gesetzt.
        if ($error == "") {
            $this->ErrorStatus = false;
            return true;
        } 
        else {
            $this->ErrorStatus = true;
            return false;
        }  
    }
    
    
    //Fehler ausgeben
    private function printErrors()
    {          
        echo '
        <div id="formular_fehler">
          <p class="fehler_text">Fehler!</p>';   
        foreach ($this->ErrorObj as $error) { 
            echo '          
          <p class="fehler_text">'.$error.'</p>';                  
            }
        echo '
        </div>';
    }
    
    
    private function sendData()
    {
        //Die Daten des Eingabefeldes 'Text' könnten mehr als 70 Zeichen enthalten. (=> wordwrap)
        $nachricht = 'E-Mail-Adresse: '.$_POST['email']."\n".'Nachricht: '.wordwrap($_POST['text'], 70);
        
        //Versand des E-Mails
        $success = mail(FORM_EMAIL, 'Nachricht vom Kontaktformular der Webseite.', $nachricht, 'Content-Type: text/plain; charset=UTF-8');
        
        //Wenn der Versand des E-Mails erfolgreich war wird 'true', ansonsten 'false' zurückgegeben.
        if ($success) {
            return true;
        }
        else {
            return false;
        }
    }    

}

?>