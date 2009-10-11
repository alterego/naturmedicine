<?php

class Form
{    
    
    //Fehler-Status
    public $ErrorStatus = null;

    //Fehler-Array
    public $ErrorObj = array();
    
    //Formulardaten-Objekt
    private $FormDataObj = null;
        
    
    
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
    }          
  
    //Eintragsformular ausgeben
    public function printForm($actScript = null, $formDataArray)
    {        
            
        $this->FormDataObj = $formDataArray;
            
        //Wenn Daten einzelner Formularfelder nicht vorhanden sind, wird die POST-Variable als leer initialisiert,
        //damit sie in der Formularausgabe als leer dargestellt werden.
        foreach($formDataArray as $data) {
            if (!isset($_POST[$data['fieldname']])) {
                $_POST[$data['fieldname']] = '';                
            }
        }                                                          
        
        //Ausgabe des öffnenden Formular-Divs
        echo '
        <div id="container_eingabeformular">';        
        
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
            foreach($formDataArray as $data) {
                $_POST[$data['fieldname']] = '';                
            }   
        }
                           
        //Token für Formular erstellen
        $token = FormProtection::generateToken();   
                        
        //Eigentliche Formular-Ausgabe
        echo '
          <form id="formular" action="'.$actScript.'" accept-charset="utf-8" method="post">
            <table border="0" cellspacing="6">';
            
        foreach($formDataArray as $data) {
            echo '
              <tr> 
                <td align="left">'.$data['title'].'&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>';            
            if ($data['fieldtype'] == 'textarea') {
                echo '<textarea name="'.$data['fieldname'].'" cols="'.$data['cols'].'" rows="'.$data['rows'].'">'.$_POST[$data['fieldname']].'</textarea>';
            }
            if ($data['fieldtype'] == 'text') {
                echo '<input name="'.$data['fieldname'].'" type="'.$data['fieldtype'].'" size="'.$data['size'].'" maxlength="50" value="'.$_POST[$data['fieldname']].'"></td>';
            }            
            echo '
              </tr>';            
        }            
             
        echo ' 
              <tr>
                <td></td>
                <td><input class="form_submit" type="submit" name="submit" value="senden" /></td>
                <td><input type="hidden" name="token" value="'.$token.'" /></td>
              </tr>
            </table>             
          </form>
        </div>';              
    } 
    
    
    private function checkData()
    {
        $error = "";
                
        foreach($this->FormDataObj as $data) {
            
            //Wenn das Formularfeld leer ist wird eine Fehlermeldung ausgegeben.
            if (empty($_POST[$data['fieldname']])) {
                
                $this->ErrorObj[] = $data['emptyerrortext'];
                $error = "true";                 
            }
            else {
                                
                //Wenn die Daten unerlaubte Zeichen enthalten wird eine Fehlermeldung ausgegeben.
                if ($data['validate']) {
                    $this->ErrorObj[] = $data['errortext'];
                    $error = "true";
                }                                                
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
        //Erstellen des SMTP-Transports
        $transport = Swift_SmtpTransport::newInstance('smtp.example.org', 25);
        
        //Swift-Instanz erstellen
        $mailer = Swift_Mailer::newInstance($transport);
        
        $nachricht = '';
         
        foreach ($this->FormDataObj as $data) {
            $nachricht .= wordwrap($data['fieldname'].': '.$_POST[$data['fieldname']]."\n", 70);
        }        
        
        //Mail-Nachricht zusammenstellen
        $message = Swift_Message::newInstance('The subject')
                 ->setFrom(array(FORM_EMAIL => 'Nachricht vom Kontaktformular der Webseite - Kontaktdaten Glanga.'))
                 ->setTo(array(FORM_EMAIL => 'Martin Volken'))
                 ->setBody($nachricht, 'text/plain', '8bit', 'utf-8');  
                 
        //Überprüfen, ob der Mailversand erfolgreich war
        if ($mailer->send($message)) {                        
            return true;
        }
        else {
            return false;
        }                
    }    

}

?>