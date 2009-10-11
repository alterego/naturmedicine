<?php

class Content
{
    //Datenbank-Objekt
    private $DB = null;
    
    //Die aktuell ausgewählte Hauptseite
    private $SiteName = null;
    
    //Subnavi-Handlers
    private $Subnav        = null;
    private $SubnavPlusAmp = '?';       
    
    //Die entsprechende Datenbank-Tabelle
    public $DBTableName = null;
    
    //Status der verschiedenen 'areas' ('open' oder 'close')
    private $AreaEintraege = null;       
    private $AreaNewForm   = null;
    
    //Formular-Status
    private $FormSubmitOk = false;
   
    //Post['id']-Handler; Gibt an, ob die POST-Variable 'id' geleert werden soll.
    private $PostIdObj = null;
        
    
    //Globales Datenbank-Objekt anbinden
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
    }
    
    
    /**
     * Auswahl des Inhalts
     *      
     * Vorabklärungen für die Datenbankanfrage finden statt.
     */
    public function selectContent()
    {
        $this->SiteName = basename($_SERVER['PHP_SELF'], '.php');                                  
                
        //Wenn eine Unternavigation existiert, überprüfen, ob eine Unterseite ausgewählt ist.
        if (isset($_GET['subnav']) AND !empty($_GET['subnav'])) {
          
            //Wenn GET-Variable übergeben, wird diese als Datenbanktabellen-Name verwendet.                
            $this->DBTableName = $_GET['subnav'];
            
            $this->Subnav         = '?subnav='.$_GET['subnav']; 
            $this->SubnavPlusAmp .= 'subnav='.$_GET['subnav'].'&';                          
        } 
        else {                                        
          
            //Wenn keine Unternavigation existiert, wird der Seitenname (Hauptnavigation) als Datenbanktabelle verwendet.
            //Wenn die Index-Seite ausgewählt ist, wird der Name 'home' verwendet ('index' ist ein reserviertes Wort in SQL).
            if ($this->SiteName == 'index') {
                $this->DBTableName = 'home';
            }
            else {
                $this->DBTableName = $this->SiteName;
            }
        }
    }        
    
    
    /**
     * Darstellen der verschiedenen Inhaltsbereiche
     * 
     * Zuerst werden die allfälligen Datenbankoperationen durchgeführt und anschliessend der aktuelle
     * Übersichts- sowie der Formularbereich gezeigt. 
     */
    public function printEntries()
    {                        
        //Die allfälligen Datenbankoperationen werden durchgeführt.
        $this->purchaseDBOperations();                                
        
        //Der benutzerdefinierte Status der verschiedenen 'areas' wird ermittelt und entschieden, welcher angezeigt wird.
        $sql    = "SELECT eintraege,form_new FROM user_config WHERE login = '".$_SESSION['username']."'";
        $result = $this->DB->query($sql);
        $entry  = $result->fetch_assoc(); 
        
        //Speichern in Klassen-Attributen       
        $this->AreaEintraege = $entry['eintraege'];       
        $this->AreaNewForm   = $entry['form_new'];                         
            
        //Der unveränderliche Head des Übersichtsbereichs wird ausgegeben.        
        echo "\n".'
        <div id="area2" class="area">
          <h2>Alle erfassten Einträge <a class="sub" href="javascript:resetForm();">&#8594; Neuen Eintrag hinzufügen</a><a href="javascript:ajaxRequest(\'scripts/AJAX/closeArea.php?area_name=eintraege\');"><div class="closer"><img src="images/button_area_close.png" /></div></a></h2>';

        if ($this->AreaEintraege == 'close') {
            echo '
       <div id="invisible_eintraege" class="invisible">';
        }          
          
        echo '  
          <form id="eintraege" name="eintrag" action="'.htmlspecialchars($_SERVER['PHP_SELF']); echo $this->Subnav.'" method="post">';
                  
        //Die jeweils aktuellen Datenbank-Einträge werden aufgelistet.
        $this->printOverview();
          
        //Der unveränderliche Footer des Übersichtsbereichs mit den Funktionen "Markierfunktionen" und der Löschfunktion für mehrere Einträge wird ausgegeben.
        echo '
            <div id="buttons"> &#8627;
              <input type="button" name="checkall" value="alle markieren" onclick="checkedall(true)" />&nbsp;&nbsp;&#124;&nbsp; 
              <input type="button" name="checkall" value="keine markieren" onclick="checkedall(false)" />&nbsp;&nbsp;&#124;&nbsp;                 
              <input class="submit_button" type="submit" name="delete" value="markierte löschen" onclick="return window.confirm(\'Ausgewählte Einträge löschen?\', title=\'Löschen mehrerer Einträge\');" />
            </div>                
          </form>
        </div>';
        
        if ($this->AreaEintraege == 'close') {
            echo '
       </div>';
        }        
            
       //Javascript (scriptaculous), welches das Ordnen im Übersichtsbereich regelt wird ausgegeben, wenn es nicht die Seite 'konzerte' ist.
        if ($this->DBTableName !== 'konzerte') {
            echo "\n".'          
        <script type="text/javascript">
        Sortable.create("eintraege", {
          onUpdate: function() {
            new Ajax.Request("sortEntries.php", {
              method: "post",
              parameters: { data: Sortable.serialize("eintraege"), tablename: "'.$this->DBTableName.'" }
            });
          }   
        }); 
        </script>';
        }        
                   
        //Der unveränderliche Head des Formular-Bereichs wird ausgegeben. (Je nachdem mit anderer Überschrift.)
        echo "\n".'
        <div id="anker">
        </div>
        
        <div id="area3" class="area">';
        
        echo '
          <h2 class="h">'; if (isset($_POST['id']) AND !empty($_POST['id'])) { echo 
                     'Ausgewählten Eintrag bearbeiten<a class="sub" href="'.htmlspecialchars($_SERVER['PHP_SELF']); echo $this->Subnav.'#area_anker" onclick="resetForm();">&#8594; Neuen Eintrag hinzufügen</a>'; }
                else { echo 'Neuen Eintrag hinzufügen'; } echo '<a href="javascript:ajaxRequest(\'scripts/AJAX/closeArea.php?area_name=form_new\');"><div class="closer"><img src="images/button_area_close.png" /></div></a></h2>';               
          
        if ($this->AreaNewForm == 'close') {
            echo '
       <div id="invisible_form_new" class="invisible">';
        }         
        
        echo '        
          <form id="form_new" name="formnew" action="'.htmlspecialchars($_SERVER['PHP_SELF']); echo $this->Subnav.'" method="post" enctype="multipart/form-data">';
          
        //Wenn ein Fehler bei der Datenvalidierung auftrat, wird hier die Fehlermeldung ausgegeben.
        if ($GLOBALS['EntryHandler']->ErrorObj === false) {
            echo '
            <div id="formular_fehler">
              <p>Fehler!</p>'.
              $GLOBALS['EntryHandler']->ErrorStr.'
            </div>';
        }         
        
        //Das jeweils aktuelle Formular wird ausgegeben.
        $this->printEntryForm();        
                      
        //Token für Formular erstellen
        $token_newentry = FormProtection::generateToken('token_newentry'); 
        
        //Der unveränderliche Footer des Formular-Bereichs wird ausgegeben.
        echo '
            <br />
            <input type="submit" name="submit" value="senden" />            
            <!--<a href="javascript:update(\'scripts/AJAX/showLoader.php\', \'loader_image\');">loader</a>&nbsp;-->            
            <input type="hidden" name="id" value="'; 
                        
        if ($this->PostIdObj !== false) {
            if (isset($_POST['id']) AND !empty($_POST['id'])) { 
                echo $_POST['id']; 
            }
        }
        echo '" />                                 
            <input type="hidden" name="token_newentry" value="'.$token_newentry.'" />
            <div id="loader_image">
            </div>
            <div class="cleaner">
          </div>
	      </form>
	   </div>';        
        
        if ($this->AreaNewForm == 'close') {
            echo '
       </div>';
        }          
    }
    
    
    /**
     * Ausgabe der einzelnen Datensätze im Übersichtsbereich
     * 
     * Die switch-Anweisung überprüft, welche Seite gewählt ist, führt dann die Datenbankanfrage
     * durch und gibt alle entsprechenden Datenbankeinträge aus.
     */
    private function printOverview()
    {                               
        //Reihenfolge der Dateneinträge festlegen (Alle ausser 'Konzerte' werden nach 'sort_id' sortiert.)
        if ($this->DBTableName == 'konzerte') {
            
            //Nur diejenigen Konzerteinträge auswählen, die nicht in der Vergangenheit liegen.
            $sql    = "SELECT * FROM ".$this->DBTableName." WHERE timestamp > '".date('Y-m-d H:i:s')."' ORDER BY timestamp";
        }
        else {
                        
            //Unveränderte Datenbankabfrage
            $sql    = "SELECT * FROM ".$this->DBTableName." ORDER BY sort_id";
        }
        
        $result = $this->DB->query($sql);
       
        //Wenn kein Datenbankeintrag vorhanden ist, wird eine Meldung ausgegeben.
        if ($result->num_rows == 0) {
            echo '
            <div id="kein_eintrag">
              <p>Es sind keine Einträge vorhanden.</p>
            </div>';
        }
        else {
        
            //Initialisierung der Variable für die Background-Farbe der einzelnen Eintragszeilen. 
            $bg = "";
        
            //Ansonsten werden die Einträge ausgegeben.
            //Unveränderter Ausgabeteil
            while($entry = $result->fetch_assoc()) {                              
                
                echo '
          <li id="item_'.$entry['id'].'">
            <div class="eintrag">
              <input type="checkbox" name="entries[]" value="'.$entry['id'].'" />
              <div class="data">
                <a href="'.htmlspecialchars($_SERVER['PHP_SELF']); echo $this->SubnavPlusAmp.'editId='.$entry['id'].'#buttons">';                
        
                //Ausgabe der einzelnen veränderlichen Daten
                //Um allenfalls zu langen Strings vorzubeugen, werden diese an die Funktion 'checkLength' übergeben und falls nötig gekürzt.
                switch ($this->DBTableName) {
              
                //Falls die Hauptseite (index)
                case 'home':      //fallthrough
                case 'our_philosophy':
                                           
                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['title']).' | '.htmlspecialchars($entry['text']));                        
                    break;
            
                //Falls die Seite 'Konzerte'
                case 'chiefdoctor':     //fallthrough
                case 'homeopaths': 
                case 'gynecologists':
                case 'therapists':
                case 'neurologists':
                case 'psychologists': 
                case 'administration': 
    
                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['name']).' | '.htmlspecialchars($entry['text']));                                            
                    break;
               
                //Falls die Seite 'Rezitalprogramme'                
                case 'what_is_homeopathy':  //fallthrough
                case 'lifestyle': 
                case 'when_call_doctor': 
                case 'simple_answers': 
                case 'how_find_doctor': 
                case 'what_to_read': 
                case 'advertisements_patients': 

                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['title'].' | '.$entry['text']));
                    break;                   
                        
                //Falls die Seite 'Bilder'                                        
                case 'clinical_discussions':    //fallthrough                                                                        
                case 'coming_seminars':                                         
                case 'past_seminars':                         
                case 'advertisements_doctors': 
                       
                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['composer'].': '.$entry['pieces']));
                    break;                       
                
                //Falls die Seite 'CD'                                
                case 'articles_philosophy':    //fallthrough 
                case 'articles_methodology':                        
                case 'articles_simple_answers':

                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['author'].' | '.$entry['title'].' | '.$entry['text']));
                    break; 
                    
                //Falls die Seite 'medien_klassisch'                                        
                case 'guidebooks':     //fallthrough           
                case 'for_parents':
                case 'for_specialists':     
                    
                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['author'].' | '.$entry['title'].' | '.$entry['text']));
                    break;                         
                
                //Falls die Seite 'Video'                                
                case 'editions':      //fallthrough
                case 'parent_schools': 
                case 'info_sources': 
                case 'centers_and_doctors':
                         
                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['title'].' | '.$entry['text']));
                    break;  
                    
                //Falls die Seite 'Video'                                
                case 'contact_address':   
                      
                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['text']));
                    break;                                                                                                            
                
                //Falls die Seite 'Reviews'                
                case 'roadmap':

                    echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['text']));
                    break;                                                          
            }                       
            
                //Ausgabe des jeweils unveränderlichen abschliessenden Teils (Buttons zum Editieren und Löschen der einzelnen Einträge.)
                echo '
                </a>
              </div>
              <div class="buttons">
                <a href="javascript:update(\'scripts/AJAX/previewEntry.php'.$this->SubnavPlusAmp.'id='.$entry['id'].'&tablename='.$this->DBTableName.'&siten='.$this->SiteName.'\', \'item_'.$entry['id'].'\');"><img src="images/button_preview.png" /></a>           
                <a href="'.htmlspecialchars($_SERVER['PHP_SELF']); echo $this->SubnavPlusAmp.'editId='.$entry['id'].'#buttons"><img src="images/button_edit.png" /></a>
                <a href="'.htmlspecialchars($_SERVER['PHP_SELF']); echo $this->SubnavPlusAmp.'delId='.$entry['id'].'"><img src="images/button_delete.png" /></a>                
              </div>              
            </div>
            <div id="preview'.$entry['id'].'">                 
            </div>
          </li>';            
            }
        }
    }
  
  
   /**
    * Ausgabe des jeweiligen Formulars im Formularbereichs
    *  
    * Die switch-Anweisung überprüft, welche Seite gewählt ist und zeigt das entsprechende Formular an.
    * Wird ein Datenbankeintrag bearbeitet, wird das Formular mit den Daten angezeigt.
    */
   private function printEntryForm()
   {                               
        //Switch-Anweisung zur Auswahl der gewählten Seite
        switch ($this->DBTableName) {
          
            //Falls die Hauptseite (index)
            case 'home':      //fallthrough
            case 'our_philosophy': 

                echo '
            <p>Titel:</p>                      
            <input type="text" class="formular" name="title" value="'; if ($this->FormSubmitOk === true) { echo $_POST['title']; } echo '" />                      
            <p>Text:</p>                      
            <textarea name="text" class="formular" cols="60" rows="25">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>';

                break;
            
            //Falls die Seite 'Konzerte'
            case 'chiefdoctor':     //fallthrough
            case 'homeopaths': 
            case 'gynecologists':
            case 'therapists':
            case 'neurologists':
            case 'psychologists': 
            case 'administration':                 
                
                echo '
            <p>Name:</p>
            <input type="text" name="name" value="'; if ($this->FormSubmitOk === true) { echo $_POST['name']; } echo '" />
            <p>E-Mail:/p>                      
            <input type="email" class="formular" name="link" value="'; if ($this->FormSubmitOk === true) { echo $_POST['email']; } echo '" />                                            
            <p>Text:</p>                      
            <textarea name="text" class="mceEditor" cols="60" rows="9">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>'; if ($this->FormSubmitOk === true) { echo $_POST['newstext']; } echo '</textarea><br />
            <p>Bild-Datei: <span class="kursiv">(jpg, png)</span></p>                      
            <input type="file" class="formular" name="file" value="" /><br />';
            
            //Wenn eine Datei zum Dateneintrag gehört, wird diese ausgegeben.
            if ($this->FormSubmitOk === true) {            
            
                $fileInfoArr = array(
                                    0 => array(
                                            'filepath'       => $_POST['filepath'],
                                            'thumb_filepath' => $_POST['admin_thumbnailpath'],
                                            'filetype'       => 'Bild-Datei'
                                          )                                     
                                );           
               
                $this->printFileView($fileInfoArr);
            }
            
                break;
               
            //Falls die Seite 'Rezitalprogramme'                
            case 'what_is_homeopathy':  //fallthrough
            case 'lifestyle': 
            case 'when_call_doctor': 
            case 'simple_answers': 
            case 'how_find_doctor': 
            case 'what_to_read': 
            case 'advertisements_patients':

                echo '
            <p>Titel:</p>                      
            <input type="text" class="formular" name="title" value="'; if ($this->FormSubmitOk === true) { echo $_POST['title']; } echo '" />                      
            <p>Text:</p>                      
            <textarea name="text" class="formular" cols="60" rows="25">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>';

                break;     
                
            //Falls die Seite 'Orchesterrepetoire'                                
            case 'clinical_discussions':    //fallthrough                                                                        
            case 'coming_seminars':                                         
            case 'past_seminars':                         
            case 'advertisements_doctors':

                echo '
            <p>Komponist:</p>
            <input type="text" class="formular" name="composer" value="'; if ($this->FormSubmitOk === true) { echo $_POST['composer']; } echo '" />
            <p>Werk(e):</p>                                                       
            <textarea name="pieces" class="formular" cols="60" rows="10">'; if ($this->FormSubmitOk === true) { echo $_POST['pieces']; } echo '</textarea>';                      
            
                break;                 
                
            //Falls die Seite 'Bilder'                                        
            case 'articles_philosophy':    //fallthrough 
            case 'articles_methodology':                        
            case 'articles_simple_answers':                         
                
                echo '
            <p>Author:</p>                      
            <input type="text" class="formular" name="author" value="'; if ($this->FormSubmitOk === true) { echo $_POST['author']; } echo '" />                                  
            <p>Titel:</p>                      
            <input type="text" class="formular" name="title" value="'; if ($this->FormSubmitOk === true) { echo $_POST['title']; } echo '" />                      
            <p>Text:</p>                      
            <textarea name="text" class="formular" cols="60" rows="25">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>';

                break;                                                                          
                
            //Falls die Seite 'Tonbeispiele'                                     
            case 'guidebooks':     //fallthrough           
            case 'for_parents':
            case 'for_specialists':  

                echo '
            <p>Author:</p>                      
            <input type="text" class="formular" name="author" value="'; if ($this->FormSubmitOk === true) { echo $_POST['author']; } echo '" />                                  
            <p>Titel:</p>                      
            <input type="text" class="formular" name="title" value="'; if ($this->FormSubmitOk === true) { echo $_POST['title']; } echo '" />                      
            <p>Text:</p>                      
            <textarea name="text" class="formular" cols="60" rows="25">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>
            <p>Bild-Datei: <span class="kursiv">(jpg,png)</span></p>                      
            <input type="file" class="formular" name="file" value="" /><br />';
            
            //Wenn eine Datei zum Dateneintrag gehört, wird diese ausgegeben.
            if ($this->FormSubmitOk === true) {
            
                $fileInfoArr = array(
                                    0 => array(
                                            'filepath'       => $_POST['filepath'],
                                            'thumb_filepath' => 'none',
                                            'filetype'       => 'Audio-Datei'
                                          )                                     
                                );             
                
                $this->printFileView($fileInfoArr);   
            }
            
                break;                        
                        
            //Falls die Seite 'Bilder'                                        
            case 'editions':      //fallthrough
            case 'parent_schools': 
            case 'info_sources': 
            case 'centers_and_doctors':                         
                
                echo '
            <p>Titel:</p>
            <input type="text" class="formular" name="title" value="'; if ($this->FormSubmitOk === true) { echo $_POST['title']; } echo '" />
            <p>Beschreibung:</p>                      
            <textarea name="text" class="formular" cols="60" rows="9">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>
            <p>Hyperlink: <span class="kursiv">(z.B. http://www.juliaschiwowa.com)</span></p>
            <input type="text" class="formular" name="link" value="'; if ($this->FormSubmitOk === true) { echo $_POST['link']; } echo '" />';
            
                break;  
                
            //Falls die Seite 'Projekte - Duo'                
            case 'contact_address': 

                echo '
            <p>Text:</p>                      
            <textarea name="text" class="formular" cols="60" rows="9">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>';                                 
            
                break;  
                
            //Falls die Seite 'Projekte - Publikation'                
            case 'roadmap': 

                echo '
            <p>Text:</p>                      
            <textarea name="text" class="formular" cols="60" rows="9">'; if ($this->FormSubmitOk === true) { echo $_POST['text']; } echo '</textarea>';                                 
            
                break;                                                                                                                                       
        }
    } 
    
    
    //Gibt eine Datei-Vorschau unter dem Formular aus.
    //Ist dem Eintrag eine Bild-Datei zugeordnet, wird eine Thumbnail-Ansicht mit Lightbox-Link zum Originalbild ausgegeben.
    //Ist einem Eintrag keine Datei zugeordnet, wird ein Hinweis ausgegeben.
    private function printFileView($fileInfoArr)
    {                    
        //Wenn ein Eintrag im Formular ausgegeben wird, wird auch die zugehörige Datei mit Namen angezeigt.
        if ($this->FormSubmitOk === true) { 
        
            echo '            
        <div class="viewimage">';
        
            //Falls mehrere Dateien zu einem Eintrag gehören, werden für jedes die folgenden Funktionen angewandt.
            foreach ($fileInfoArr as $fileInfo) {
        
                echo ' 
              <h3>Mit dem Eintrag verbundene '.$fileInfo['filetype'].':</h3>';
            
                //Wenn eine Datei vorhanden ist.
                if (!empty($fileInfo['filepath'])) {
                
                    //Den Dateinamen bestimmen.
                    $fileArray = explode('/', $fileInfo['filepath']);
                    $fileName  = array_pop($fileArray);                    
                   
                    //Ausgabe des Bildnamens.
                    echo '
                <p class="thumbname">'.$fileName.'</p>';                                    
                   
                    //Wenn ein Thumbnailbild vorhanden ist, wird dieses mit einem Lightbox-Link zum Originalbild ausgegeben.
                    if ($fileInfo['thumb_filepath'] !== 'none') {
                        echo '            
                <div class="thumbname_img">
                  <a href="'.SERVER_ROOT_PATH.$fileInfo['filepath'].'" rel="lightbox"><img src="'.SERVER_ROOT_PATH.$fileInfo['thumb_filepath'].'" /></a>
                </div>';                            
                    }
                }
                                   
                //Wenn dem Dateneintrag kein Bild zugeordnet ist, wird eine entsprechende Meldung ausgegeben. 
                else {
                    echo '
              <div class="hinweis">
                <p>Hinweis!</p>
                <p>Dem Dateneintrag ist keine '.$fileInfo['filetype'].' zugeordnet.</p>
              </div>';
                }                        
            
            //Abschluss foreach
            }
        
        //Abschluss der div-class 'viewimage'
        echo '
          <div class="cleaner">
          </div>
		</div>
		'; 
        }
    } 
    
    
    /**
     * Datenbankoperationen
     * 
     * Hier werden die Datenbankoperationen wie Hinzufügen, Bearbeiten und Löschen von Einträgen geregelt
     * und an die Klasse 'EntryHandler' zur Ausführung weitergeleitet.
     */
    private function purchaseDBOperations()
    {        
        //Wenn ein GET-Parameter 'delId' vorhanden ist, wird der Dateneintrag gelöscht.
        if (isset($_GET['delId']) AND is_numeric($_GET['delId'])) {
                        
            $GLOBALS['EntryHandler']->deleteEntry();                    
        }  
        
        //Wenn das Übersichts-Formular gesendet wurde (nur bei Betätigung des Löschbuttons der Fall), werden alle 
        //gekennzeichneten Dateneinträge auf einmal gelöscht.      
        if (isset($_POST['delete'])) {
            
            $GLOBALS['EntryHandler']->deleteEntries();
        }    
        
        //Wenn das Formular nicht abgeschickt wurde, wird das POST-Array geleert.
        if (!isset($_POST['submit'])) {
            $_POST = '';
        }
        else {                
                        
            //Im Attribut 'FormSubmitOK' wird das Abschicken des Formulars vermerkt.
            $this->FormSubmitOk = true;                                           
            
            //Wenn Daten einzelner Formularfelder nicht vorhanden sind, wird die POST-Variable als leer initialisiert,
            //damit sie in der Formularausgabe als leer dargestellt werden.
            foreach($_POST as $key => $data) {
                if (!isset($data)) {
                    $_POST[$key] = '';
                }
            }
            
            //Wenn der Button "senden" betätigt wurde, werden die Benutzereingaben in die Datenbank geschrieben.
            if ($_POST['submit'] == 'senden') {
                                                            
                //Token spezifizieren, um das richtige Formular zu überprüfen.
                if (isset($_POST['token_sitetitle'])) {
                    $tokenSesName = 'token_sitetitle';
                    $this->FormSubmitOk = false;                                                               
                }
                if (isset($_POST['token_newentry'])) {
                    $tokenSesName = 'token_newentry';
                }                
                                
                //Überprüfen, ob die Daten vom Formular der Seite stammen und noch nicht gesendet wurden. (Reload)
                //Die Methode zur Überprüfung des Formular-Tokens wird aufgerufen und der Rückgabewert gespeichert.
                $resultToken = FormProtection::checkToken($tokenSesName);
    
                //Die Daten stammen nicht aus einem Formular der Webseite
                if ($resultToken === 'noToken') {
                    $GLOBALS['EntryHandler']->ErrorStr = '<p>Bitte benutzen Sie nur Formulare dieser Webseite.</p>';                    
                    $GLOBALS['EntryHandler']->ErrorObj = false;
                    $this->FormSubmitOk = false;                                                                            
                    $this->PostIdObj    = false;                                                                                               
                    return false;
                } 
                
                //Die Nachricht wurde bereits versandt (Reload)
                if ($resultToken === false) {
                    $GLOBALS['EntryHandler']->ErrorStr = '<p>Ihre Daten wurden bereits übertragen.</p>';
                    $GLOBALS['EntryHandler']->ErrorObj = false; 
                    $this->FormSubmitOk = false;                                                        
                    $this->PostIdObj    = false;                                                                                               
                    return false;                    
                }                 
                
                $GLOBALS['EntryHandler']->addEntry();
                
                //Wenn die Datenbankoperation erfolgreich war, wird das Formular "Neuer Eintrag" wieder leer dargestellt. 
                if ($this->DB->lastSQLStatus === true) {
                    $this->FormSubmitOk = false;
                    $this->PostIdObj    = false;                                                                                                  
                }
            }               
        }
        
        //Wenn ein GET-Parameter 'editId' vorhanden ist, wird das Formular mit dem Dateneintrag angezeigt.
        if (isset($_GET['editId']) AND is_numeric($_GET['editId'])) {
                        
            $this->FormSubmitOk = true;
            
            $GLOBALS['EntryHandler']->showEntry();            
        }
    }
  
}

?>