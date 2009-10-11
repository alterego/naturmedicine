<?php

class EntryHandler
{
    
    //Datenbank-Objekt    
    private $DB = null;
    
    //Error-Objekt
    public $ErrorObj = null;
    
    //Error-String-Objekt (Enthält allfällige Fehlermeldungen)
    public $ErrorStr = null;    
    
    
    //Globales Datenbank-Objekt anbinden    
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
    }
        
    /**
     * Anzeigen des ausgewählten Datenbankeintrags im Formular 'Neuer Eintrag'
     * 
     * Die ausgelesenen Daten werden in das POST-Array geschrieben, um sie direkt im Formular anzuzeigen. 
     * (Identifikation mittels übergebener 'id' (GET))
     */
    public function showEntry()
    {
        $sql = "SELECT * FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$_GET['editId']."";
        $result = $this->DB->query($sql);
        $arrayResult = $result->fetch_assoc();
        foreach ($arrayResult as $key => $data) {
            
            //Die allfälligen css-Klassen werden für den Tinymce-Editor in Inline-Styles konvertiert.
            $data = $this->sanitizeStringForTinymce($data);
            $_POST[''.$key.''] = $data;
        }
    }    
    
    /**
     * Kürzen der zu langen Strings im Übersichtsfeld
     * 
     * @param string $string: Der allfällig gekürzte String zur Ausgabe im Übersichtsfeld
     */
    public function checkLength($string)
    {
        //Alle Html-Tags entfernen
        $string = preg_replace('!<.*?>!', ' ', $string);
        
        if (strlen($string) > 50) {
            $outputString = substr($string, 0, 49);
            return $outputString.'...';
        }
        else {
            return $string;
        }
    }        
    
    /**
     * Löschen des ausgewählten Datenbankeintrags
     *  
     * Der im Übersichtsbereich ausgewählte Datenbankeintrag wird per übergebener 'id' (GET) gelöscht.
     */
    public function deleteEntry()
    {
        //Wenn ein Bild (Datei) zum Dateneintrag gehört, wird die entsprechende Datei gelöscht.
        if ($GLOBALS['CONTENT']->DBTableName == 'szenisch' OR 
            $GLOBALS['CONTENT']->DBTableName == 'portraet' OR
            $GLOBALS['CONTENT']->DBTableName == 'downloads_fotos' OR
            $GLOBALS['CONTENT']->DBTableName == 'projekte_duo' OR
            $GLOBALS['CONTENT']->DBTableName == 'projekte_publikation' OR
            $GLOBALS['CONTENT']->DBTableName == 'kontakt') {
            $this->deleteImage();
        }   
        
        //Wenn eine Videodatei zum Dateneintrag gehört, wird die entsprechende Datei gelöscht.
        if ($GLOBALS['CONTENT']->DBTableName == 'medien_filme') {
            $this->deleteVideo();
        }         
        
        //Wenn eine Audiodatei zum Dateneintrag gehört, wird die entsprechende Datei gelöscht.
        if ($GLOBALS['CONTENT']->DBTableName == 'medien_klassisch' OR
            $GLOBALS['CONTENT']->DBTableName == 'medien_chanson') {
            $this->deleteAudio();
        }     
        
        //Wenn eine PDF-datei zum Dateneintrag gehört, wird die entsprechende Datei gelöscht.
        if ($GLOBALS['CONTENT']->DBTableName == 'downloads_cv') {
            $this->deleteFile();
        }            
        
        //Der Dateneintrag wird vollständig aus der Datenbank gelöscht.     
        $sql = "DELETE FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$_GET['delId']."";
        $result = $this->DB->query($sql);        
    }
    
    /**
     * Löschen des ausgewählten Datenbankeintrags
     *  
     * Die im Übersichtsbereich ausgewählten Datenbankeinträge werden per übergebener 'ids' (POST) gelöscht.
     */    
    public function deleteEntries()
    {       
        //Für jeden Dateneintrag wird die Löschmethode aufgerufen.
        foreach ($_POST['entries'] as $entry) {
          
            if (is_numeric($entry)) {
            
                //Falls es eine Datei zu löschen gilt, werden je nach Datei (Bild, Audio, Video) 
                //die entsprechenden Methoden zum Löschen der Datei aufgerufen.         
                if ($GLOBALS['CONTENT']->DBTableName == 'szenisch' OR 
                    $GLOBALS['CONTENT']->DBTableName == 'portraet' OR
                    $GLOBALS['CONTENT']->DBTableName == 'downloads_fotos' OR
                    $GLOBALS['CONTENT']->DBTableName == 'projekte_duo' OR
                    $GLOBALS['CONTENT']->DBTableName == 'projekte_publikation' OR                    
                    $GLOBALS['CONTENT']->DBTableName == 'kontakt') {
                    $this->deleteImage($entry);
                }
                if ($GLOBALS['CONTENT']->DBTableName == 'medien_filme') {
                    $this->deleteVideo($entry);
                }
                if ($GLOBALS['CONTENT']->DBTableName == 'medien_klassisch' OR
                    $GLOBALS['CONTENT']->DBTableName == 'medien_chanson') {
                    $this->deleteAudio($entry);
                }     
                
                //Unabhängig von der Dateiart wird der Dateneintrag aus der Datenbank gelöscht.                   
                $sql = "DELETE FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$entry."";
                $result = $this->DB->query($sql);
            } 
        }
    }
        
    /**
     * Hinzufügen von neuen oder veränderten Einträgen in die Datenbank
     *  
     * Zuerst wird entschieden, ob es sich um einen neuen oder einen veränderten Datensatz handelt und
     * anschliessend in die Datenbank geschrieben. 
     */
    public function addEntry()
    {                                                              
        if ($this->checkDataFormat() === false) {
            return false;
        }
        
        $fieldnames    = '';
        $sqlData       = '';                
        $sqlDataUpdate = '';                                    
               
        //Wenn eine Datei hochgeladen wurde, wird überprüft, von welcher Seite sie stammt und dann die entsprechenden Methoden
        //aufgerufen (da unterschiedliche Dateiformate benötigt werden).
        if (isset($_FILES) AND !empty($_FILES)) {  

            //Wenn die Anzahl der hochgeladenen Dateien mehr als 1 beträgt, wird das Original-FILES-Array für den späteren
            //Zugriff in einer anderen Variablen gespeichert. 
            //Die Informationen zu jedem hochgeladenen File werden in ein eigenes Array geschrieben, um die folgenden
            //Funktionen neutral anwenden zu können.                
            $origFilesArray = $_FILES;
                            
            //Für jede hochgeladene Datei werden die Funktionen in der for-Schleife durchgeführt.
            for ($i = 0; $i < count($origFilesArray['file']['tmp_name']); $i++) {                              
               
                if (count($origFilesArray['file']['tmp_name']) > 1) {  
                
                    //Wenn die Datei nicht per POST hochgeladen wurde, wird die Verarbeitung abgebrochen.
                    if (!empty($origFilesArray['file']['tmp_name'][$i]) AND !is_uploaded_file($origFilesArray['file']['tmp_name'][$i])) {
                        return false;
                    }                                                                             
                
                        //Das jeweilige hochgeladene Bild wird in das FILES-Array zur Bearbeitung geschrieben.
                        $_FILES['file']['name']     = $origFilesArray['file']['name'][$i];
                        $_FILES['file']['type']     = $origFilesArray['file']['type'][$i];
                        $_FILES['file']['tmp_name'] = $origFilesArray['file']['tmp_name'][$i];
                        $_FILES['file']['error']    = $origFilesArray['file']['error'][$i];
                        $_FILES['file']['size']     = $origFilesArray['file']['size'][$i];                                                                                                                                                           
                }
                
                else {
                    if (!empty($origFilesArray['file']['tmp_name']) AND !is_uploaded_file($origFilesArray['file']['tmp_name'])) {
                        return false;
                    }
                } 
                
                if (!empty($_FILES['file']['tmp_name'])) {             
    
                    //Falls Leerzeichen im Namen der hochgeladenen Datei vorhanden sind, werden diese mit Unterstrichen ersetzt.
                    $_FILES['file']['name'] = preg_replace('! !', '_', trim($_FILES['file']['name']));            
                    
                    //Wenn es sich um die Seiten 'fotos' oder 'konzerte' handelt, werden die Methoden zur Bildbehandlung verwendet.
                    if ($GLOBALS['CONTENT']->DBTableName == 'szenisch' OR 
                        $GLOBALS['CONTENT']->DBTableName == 'portraet' OR 
                        $GLOBALS['CONTENT']->DBTableName == 'medien_fotos' OR
                        $GLOBALS['CONTENT']->DBTableName == 'downloads_fotos' OR
                        $GLOBALS['CONTENT']->DBTableName == 'projekte_duo' OR
                        $GLOBALS['CONTENT']->DBTableName == 'projekte_publikation' OR                        
                        $GLOBALS['CONTENT']->DBTableName == 'kontakt') {          
                        if ($this->checkImageFile() === false) {
                            return false;
                        }
                        
                        //Die bestehenden Bilder des aktuell zu verändernden Eintrags werden zuerst gelöscht.
                        if (isset($_POST['id']) AND !empty($_POST['id'])) {
                            $this->deleteImage($_POST['id']);
                        }                                                                
                        
                        //Erstellen eines Thumbnails für den Admin-Bereich (Breite 120 Pixel = Standard)
                        $this->createThumbnail('thumbAdmin');
                        
                        //Erstellen eines Thumbnails für die öffentliche Webseite.
                        //Switch-Anweisung zur Auswahl der gewählten Seite
                        switch ($GLOBALS['CONTENT']->DBTableName) {
                          
                            //Falls die Bilder für die Seite 'Fotos - szenisch' wird die Maximalbeite auf 200 Pixel beschränkt.
                            case 'portraet':         //fallthrough                  
                            case 'medien_fotos':                           
                            case 'szenisch':                           
                                
                                $this->createThumbnail('thumb', 140, 'auto');
                        
                            break;                                    
                            
                            //Falls die Bilder für die Seite 'Downloads - fotos' wird die Maximalbeite auf 200 Pixel beschränkt
                            case 'kontakt':

                                $this->createThumbnail('thumb', 160, 'auto');
                        
                            break; 
                                                     
                            //Falls die Bilder für die Seite 'Downloads - fotos', wird zusätzlich das Bild für die lightbox auf eine maximale Breite zugeschnitten
                            case 'downloads_fotos':                           
                                
                                $this->createThumbnail('thumb', 160, 'auto');
                                $this->createThumbnail('lightbox', 900, 'orig');
                        
                            break; 
                            
                            //Falls die Bilder für die Seite 'Downloads - fotos' wird die Maximalbeite auf 200 Pixel beschränkt
                            case 'projekte_duo':     //fallthrough                         
                            case 'projekte_publikation':                           
                                
                                $this->createThumbnail('thumb', 160, 'orig');
                        
                            break;                                                     
                        }
                        
                        $this->addImage();
                        
                        $sqlData       .= PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '".
                                          PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/thumb/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '".
                                          PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/thumbAdmin/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '";                                                                  
                        $sqlDataUpdate .= "filepath = '".PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',
                                          thumbnailpath = '".PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/thumb/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',
                                          admin_thumbnailpath = '".PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/thumbAdmin/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',";                          
                        $fieldnames    .= 'filepath, thumbnailpath, admin_thumbnailpath, '; 
                        
                        //Wenn es um die Seite 'Download - fotos' geht, wird noch ein Eintrag für den Ordner 'lightbox' erstellt (zugeschnittenes Bild mit Maximalbreite)
                        if ($GLOBALS['CONTENT']->DBTableName == 'downloads_fotos') {
                            $sqlData       .= PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/lightbox/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '";
                            $sqlDataUpdate .= "filepath_lightbox = '".PROJECT_IMAGES_PATH."/".$GLOBALS['CONTENT']->DBTableName."/lightbox/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',";
                            $fieldnames    .= 'filepath_lightbox, ';                                                         
                        }                        
                    }            
                    
                    //Wenn es sich um die Seite 'video' handelt, werden die Methoden zur Videobehandlung verwendet.            
                    if ($GLOBALS['CONTENT']->DBTableName == 'medien_filme') {          
                        if ($this->checkVideoFile() === false) {
                            return false;
                        }
                        $this->addVideo();   
                        $sqlData       .= PROJECT_VIDEO_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '";
                        $sqlDataUpdate .= "filepath = '".PROJECT_VIDEO_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',";                          
                        $fieldnames    .= 'filepath, ';                           
                    }
                    
                    //Wenn es sich um die Seite 'tonbeispiele' handelt, werden die Methoden zur Audiobehandlung verwendet.                        
                    if ($GLOBALS['CONTENT']->DBTableName == 'medien_klassisch' OR
                        $GLOBALS['CONTENT']->DBTableName == 'medien_chanson') {          
                        if ($this->checkAudioFile() === false) {
                            return false;
                        }
                        $this->addAudio();
                        $sqlData       .= PROJECT_AUDIO_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '";
                        $sqlDataUpdate .= "filepath = '".PROJECT_AUDIO_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',";                          
                        $fieldnames    .= 'filepath, ';                              
                    }           
                    
                    //Wenn es sich um die Seite 'downloads_cv' handelt, werden die Methoden zur PDF-Behandlung verwendet.                        
                    if ($GLOBALS['CONTENT']->DBTableName == 'downloads_cv') {          
                        
                        //Es ist wird überprüft, ob mehrere Dateien hochgeladen wurden und bei Erfolgsfall der zu prüfende Dateityp ermittelt.
                        if (isset($_POST['filetypes']) AND !empty($_POST['filetypes'])) {
                                                    
                            $unserFiletypes = unserialize($_POST['filetypes']);
                            
                            //Wenn es eine Word-Datei sein soll.
                            if ($unserFiletypes[$i] == 'word') {
                                if ($this->checkWordFile() === false) {
                                return false;
                                }                            
                                $sqlData       .= PROJECT_DOWNLOAD_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '";
                                $sqlDataUpdate .= "filepath_word = '".PROJECT_DOWNLOAD_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',";                          
                                $fieldnames    .= 'filepath_word, ';                            
                            }
                        
                            //Wenn es eine PDF-Datei sein soll.                        
                            if ($unserFiletypes[$i] == 'pdf') {
                                if ($this->checkPdfFile() === false) {
                                return false;
                                }
                                $sqlData       .= PROJECT_DOWNLOAD_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."', '";
                                $sqlDataUpdate .= "filepath_pdf = '".PROJECT_DOWNLOAD_PATH."/".$GLOBALS['CONTENT']->DBTableName."/".$this->DB->MySQLiObj->real_escape_string(trim($_FILES['file']['name']))."',";                          
                                $fieldnames    .= 'filepath_pdf, ';                            
                            }                        
                            $this->addFile();                                                                               
                        }
                    }
                }    
            }//End of for
        }
                
        //Es wird überprüft, ob eine Eintrags-ID vorhanden ist und bei Erfolg der Datensatz
        //in der Datenbank geändert. Ansonsten handelt es sich um einen neuen Eintrag, der in der Datenbank
        //angelegt wird. Eine Meldung informiert über den erfolgreichen oder fehlgeschlagenen Speicherungs-Prozess.
        if (isset($_POST['id']) AND !empty($_POST['id'])) {            
            
            //Wenn es ein Eintrag für die Tabelle 'konzerte' ist, wird das übergebene Datum in das Format 'datetime' konvertiert
            //(für das Ordnen der Einträge) und ein Zeitstempel für das Feld 'pubdate' generiert (für einen korrekten RSS-Feed). 
            if ($GLOBALS['CONTENT']->DBTableName == 'konzerte' AND !isset($_POST['token_sitetitle'])) {
                $dateTime  = new DateTimeFunc();
                $timestamp = $dateTime->makeDatetime($_POST['date'], $_POST['time']);
                $pubDate   = date("D, j M Y H:i:s O");                
                $_POST['timestamp'] = $timestamp;
                $_POST['pubdate']   = $pubDate;
            }            
            
            //Jede POST-Variable wird als Tabellenzellen-Name (Datenbank) für die Schreibaktion verwendet.
            foreach ($_POST as $key => $data) {
                if ($key !== 'submit' AND $key !== 'id' AND $key !== 'token_sitetitle' AND $key !== 'token_newentry' AND $key !== 'filetypes') {
                    $data = $this->sanitizeStringFromTinymce($data);                   
                    $sqlDataUpdate .= $key." = '".$this->DB->MySQLiObj->real_escape_string(trim($data))."',";
                }
            }     
            
            if (isset($_POST['token_sitetitle']) AND !empty($_POST['token_sitetitle'])) {
                $sql = "UPDATE site_titles SET ".$sqlDataUpdate;                                              
            }       
            else {
                $sql = "UPDATE ".$GLOBALS['CONTENT']->DBTableName." SET ".$sqlDataUpdate;                                              
            } 
            
            $sql .= "user = '".$this->DB->MySQLiObj->real_escape_string($_SESSION['username'])."' WHERE id = '".$this->DB->MySQLiObj->real_escape_string($_POST['id'])."'";            
        }
        else {       
            
            //Wenn es ein Eintrag für die Tabelle 'konzerte' ist, wird das übergebene Datum in das Format 'datetime' konvertiert
            //(für das Ordnen der Einträge) und ein Zeitstempel für das Feld 'pubdate' generiert (für einen korrekten RSS-Feed).            
            if ($GLOBALS['CONTENT']->DBTableName == 'konzerte' AND !isset($_POST['token_sitetitle'])) {
                $dateTime  = new DateTimeFunc();
                $timestamp = $dateTime->makeDatetime($_POST['date'], $_POST['time']);                
                $pubDate   = date("D, j M Y H:i:s O");                
                $_POST['timestamp'] = $timestamp;
                $_POST['pubdate']   = $pubDate;
                
                //Tabellenfeld 'sort_id' wird auf 0 gesetzt, damit die SQL-Abfrage weiter unten unabhängig
                //funktioniert.
                $_POST['sort_id'] = '0';                
            }
            
            //Tabellenfeld 'sort_id' auf 1 höher als die höchste sort_id - Nummer setzen.
            else {                                
                
                //Die höchste sort_id - Nummer wird ermittelt, um den neuen Eintrag an die letzte Stelle zu setzen.
                $sql    = "SELECT sort_id FROM ".$GLOBALS['CONTENT']->DBTableName." ORDER BY sort_id DESC";
                $result = $this->DB->query($sql);
                $entry  = $result->fetch_assoc();  
                
                $_POST['sort_id'] = $entry['sort_id'] + 1;                
            }
               
            foreach ($_POST as $key => $data) {
                if ($key !== 'submit' AND $key !== 'id' AND $key !== 'token_sitetitle' AND $key !== 'token_newentry' AND $key !== 'filetypes') {
                    $fieldnames .= $key.', ';   
                    $data        = $this->sanitizeStringFromTinymce($data);                   
                    $sqlData    .= $this->DB->MySQLiObj->real_escape_string(trim($data))."', '";
                }
            }     
            
            if (isset($_POST['token_sitetitle']) AND !empty($_POST['token_sitetitle'])) {
                $sql = "INSERT INTO site_titles (".$fieldnames."user) VALUES('".$sqlData;                        
            }
            else {
                $sql = "INSERT INTO ".$GLOBALS['CONTENT']->DBTableName." (".$fieldnames."user) VALUES('".$sqlData;                                                    
            }
                        
            $sql .= $this->DB->MySQLiObj->real_escape_string($_SESSION['username'])."')";                            
        }

        $result = $this->DB->query($sql);                             
    }     
    
    /**
     * Überprüfung der übergebenen Daten
     * 
     * Die Formularfelder, die ein bestimmtes Format (etwa Datumsangaben) benötigen, werden auf dieses überprüft.
     * Beim Fehlerfall wird eine Meldung generiert.
     */
    private function checkDataFormat()
    {        
        if (isset($_POST['date']) AND !empty($_POST['date'])) {
            if (!preg_match('!^\d{2}\.\d{2}\.\d{4}$!' , trim($_POST['date']))) {
                $this->ErrorStr = "\n".'<p>Das <em>Datum</em> hat nicht das benötigte Format!</p>';                
            }
        }
        if (isset($_POST['time']) AND !empty($_POST['time'])) {            
            if (!preg_match('!^\d{2}:\d{2}$!' , trim($_POST['time']))) {
                $this->ErrorStr .= "\n".'<p>Die <em>Uhrzeit (Konzertbeginn)</em> hat nicht das benötigte Format!</p>';
            }
        }
        if (isset($_POST['hyperlink']) AND !empty($_POST['hyperlink'])) {            
            if (!preg_match('!^http|https!' , trim($_POST['hyperlink']))) {    
                $this->ErrorStr .= "\n".'<p>Der <em>Link (Feld "Hyperlink")</em> hat nicht das benötigte Format!</p>';
            }
            else {
                $_POST['hyperlink'] = filter_var($_POST['hyperlink'], FILTER_SANITIZE_URL);
            }
        }  
        if (isset($_POST['link']) AND !empty($_POST['link'])) {            
            if (!preg_match('!^http|https!' , trim($_POST['link']))) {    
                $this->ErrorStr .= "\n".'<p>Der <em>Link (Feld "Link")</em> hat nicht das benötigte Format!</p>';
            }
            else {
                $_POST['link'] = filter_var($_POST['link'], FILTER_SANITIZE_URL);
            }            
        }              
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }
        return true;
    }
      
      
    private function sanitizeStringFromTinymce($data)
    {
        //Die vom Tiny_mce-Editor generierten Inline-Styles werden durch css-Klassen ersetzt.
        $data = str_replace('style="text-decoration: line-through;"', 'class="line_through"', $data);
        $data = str_replace('style="text-decoration: underline;"', 'class="underline"', $data);
                
        //Die komplizierter zu ersetzenden Inline-Styles für die Textfarbe werden hier ausgelesen und in
        //einer eigenen css-Datei auf ihr Vorhandensein überprüft und gegebenenfalls geschrieben.
        //Wenn im Text-String das Muster 'style="color' vorkommt, wird der folgende Block ausgeführt.
        if (preg_match('%style="color%', $data)) {
            
            //Alle Farben eines solchen Musters werden in ein Array ausgelesen.
            preg_match_all('%#[a-zA-Z0-9]{6}%', $data, $all_colours_tinymce);

            //Das zürückgegebene zweidimensionale Array wird ausgelesen.
            foreach ($all_colours_tinymce as $color_unique_tinymce) {
                foreach ($color_unique_tinymce as $color_unique_css) {
                    
                    //Die Datei 'colors_tinymce.css' wird ausgelesen.
                    $file = file_get_contents("../lib/css/colours_tiny_mce.css");                                             
                    
                    //Wenn bereits eine css-Klasse mit dieser Farbe besteht, wird sie nicht geschrieben (verdoppelt).
                    //Existiert sie noch nicht, wird sie neu angelegt.
                    if (!preg_match("%".$color_unique_css."%", $file)) {
                        $class_color_name = preg_replace('%#%', '_', $color_unique_css);
                                                                     
                        ftp_chmod($GLOBALS['FTP']->FTPConn, 0757, '/var/www/julia/lib/css/colours_tiny_mce.css');            
                        file_put_contents("../lib/css/colours_tiny_mce.css", ".color".$class_color_name."{color:".$color_unique_css.";}\n", FILE_APPEND);                        
                        ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, '/var/www/julia/lib/css/colours_tiny_mce.css');            
                    }
                }
            }
            
            //Die css-Klasse wird anstelle des Inline-Styles in den Textstring geschrieben.
            //Beispiel: <span style="color: #000000;"> wird zu <span class="color_000000">
            foreach ($all_colours_tinymce as $color_unique) {
                foreach ($color_unique as $color_unique_css) { 
                    
                    //Das Rautezeichen wird durch einen Unterstrich ersetzt.
                    $color_css = preg_replace('%#%', '', $color_unique_css);
                    
                    //Der Inline-Style wird durch eine css-Klasse ersetzt.
                    $data      = preg_replace("%style=\"color: ".$color_unique_css.";|style=\"color:".$color_unique_css.";%", "class=\"color_".$color_css."", $data);
                }
            }
        }         
                
        return $data;
    }   
    
    
    private function sanitizeStringForTinymce($data)
    {
        //Die vom Tiny_mce-Editor generierten Inline-Styles werden durch css-Klassen ersetzt.
        $data = str_replace('class="line_through"', 'style="text-decoration: line-through;"', $data);
        $data = str_replace('class="underline"', 'style="text-decoration: underline;"', $data);        
        
        //Die komplizierter zu handhabenden Klassen für die Textfarbe wird hier ersetzt.
        if (preg_match('%class="color%', $data)) {
            $data = preg_replace('%span class=\"color_%', 'span style="color: #', $data);
        }          
        
        return $data;
    }   
      
      
    /**
     * Überprüfung der hochzuladenden Dateien
     * 
     * Die im Formular übermittelten Dateien werden auf ihren Typ und ihre Grösse überprüft und beim Fehlerfall
     * eine Meldung generiert.
     * Erlaubt sind nur die Typen 'jpg', 'gif' und 'png'. Die Höchstgrenze der Dateigrösse liegt bei 2MB (serverseitige Beschränkung).
     */
    private function checkImageFile()
    {
        if ($_FILES['file']['type'] !== 'image/jpeg' AND 
            $_FILES['file']['type'] !== 'image/pjpeg'AND 
            $_FILES['file']['type'] !== 'image/png') {
            $this->ErrorStr = '<p>Die Dateiformate zum Hochladen dürfen nur <em>"jpg"</em> oder <em>"png"</em> sein!</p>';
        }
        if ($_FILES['file']['size'] > '7000000') {
            $this->ErrorStr = '<p>Die Datei zum Hochladen darf höchstens 7MB gross sein!</p>';
        }        
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }
        return true;  
    }
    
    
    /**
     * Überprüfung der hochzuladenden Dateien
     * 
     * Die im Formular übermittelten Dateien werden auf ihren Typ und ihre Grösse überprüft und beim Fehlerfall
     * eine Meldung generiert.
     * Erlaubt sind nur die Typen 'jpg', 'gif' und 'png'. Die Höchstgrenze der Dateigrösse liegt bei 2MB (serverseitige Beschränkung).
     */
    private function checkVideoFile()
    {        
        if ($_FILES['file']['type'] !== 'video/x-flv') {
            $this->ErrorStr = '<p>Das Dateiformat zum Hochladen darf nur <em>"flv"</em> sein!</p>';
        }
        if ($_FILES['file']['size'] > '7000000') {
            $this->ErrorStr = '<p>Die Datei zum Hochladen darf höchstens 7MB gross sein!</p>';
        }        
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }
        return true;  
    }    

    private function checkAudioFile()
    {        
        if ($_FILES['file']['type'] !== 'audio/mpeg3' AND 
            $_FILES['file']['type'] !== 'audio/x-mpeg-3' AND
            $_FILES['file']['type'] !== 'audio/x-mpeg' AND 
            $_FILES['file']['type'] !== 'audio/mpeg') {
            $this->ErrorStr = '<p>Das Dateiformat zum Hochladen darf nur <em>"mp3"</em> sein!</p>';
        }
        if ($_FILES['file']['size'] > '7000000') {
            $this->ErrorStr = '<p>Die Datei zum Hochladen darf höchstens 7MB gross sein!</p>';
        }        
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }
        return true;  
    } 
    
    private function checkWordFile()
    {              
        if ($_FILES['file']['type'] !== 'application/msword' AND
            $_FILES['file']['type'] !== 'application/vnd.oasis.opendocument.text') {
            $this->ErrorStr = '<p>Das Dateiformat zum Hochladen darf nur <em>"doc, odt"</em> sein!</p>';
        }
        if ($_FILES['file']['size'] > '7000000') {
            $this->ErrorStr = '<p>Die Datei zum Hochladen darf höchstens 7MB gross sein!</p>';
        }        
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }
        return true;  
    }     
    
    
    private function checkPdfFile()
    {              
        if ($_FILES['file']['type'] !== 'application/pdf' AND
            $_FILES['file']['type'] !== 'application/x-pdf' AND
            $_FILES['file']['type'] !== 'text/pdf' AND
            $_FILES['file']['type'] !== 'text/x-pdf' AND
            $_FILES['file']['type'] !== 'application/acrobat' AND
            $_FILES['file']['type'] !== 'application/vnd.pdf') {
            $this->ErrorStr = '<p>Das Dateiformat zum Hochladen darf nur <em>"pdf"</em> sein!</p>';
        }
        if ($_FILES['file']['size'] > '7000000') {
            $this->ErrorStr = '<p>Die Datei zum Hochladen darf höchstens 7MB gross sein!</p>';
        }        
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }
        return true;  
    }     


    private function addImage()
    {         
        $uploadDir  = SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/';        
        $uploadFile = $uploadDir.$_FILES['file']['name'];
                            
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName);
        
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $this->ErrorStr = '<p>Die Datei <em>'.$_FILES['file']['name'].'</em> konnte nicht hochgeladen werden!</p>';          
        }      
        
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName);
          
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }                                    
    }
    
    
    private function createThumbnail($uploadPath, $width = 120, $height = 'auto', $formatFocus = 'width')
    {
        $uploadDir  = SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/';        
        $uploadFile = $uploadDir.$_FILES['file']['name'];
        
        $this->checkFileExistence($uploadFile);                         
        
        //Je nach Bildtyp werden die entsprechenden Methoden zur Thumbnail-Erstellung verwendet.
        //Wenn es sich um eine jpeg-Datei handelt.
        if ($_FILES['file']['type'] == 'image/jpeg' OR $_FILES['file']['type'] == 'image/pjpeg') {
            $originalImage = imagecreatefromjpeg($_FILES['file']['tmp_name']);
        }
        
        //Wenn es sich um eine png-Datei handelt.
        if ($_FILES['file']['type'] == 'image/png') {
            $originalImage = imagecreatefrompng($_FILES['file']['tmp_name']);  
        }
        
        list($ow, $oh) = getimagesize($_FILES['file']['tmp_name']);
        
        //Wenn keine Angabe zur Höhe gemacht wurde, das Bild nur auf die vorgegebene Breite skalieren
        if ($height !== 'auto') {
          
            //Überprüfen, welches Format das verkleinerte Bild haben soll, das heisst,
            //ob sich das Berechnen auf die Breite oder Höhe des Bildes bezieht.
            //Wenn auf die Breite (Standard):
            if ($formatFocus == 'width') {              
               
                //Überprüfen, ob die Breite des Originalbildes weniger als die gewünschte Breite beträgt.
                //Wenn ja, werden die Masse des Bildes belassen.           
                if ($ow <= $width) {
                    $width  = $ow;
                    $height = $oh;
                }
                else {
    
                    //Berechnen der Bildmasse ohne Verzerren der Proportionen.
                    $height = $width / $ow * $oh;              
                }                
            }                        
            
            //Wenn auf die Höhe:
            else {
                
                //Überprüfen, ob die Höhe des Originalbildes weniger als die gewünschte Höhe beträgt.
                //Wenn ja, werden die Masse des Bildes belassen.           
                if ($oh <= $height) {
                    $width  = $ow;
                    $height = $oh;
                }
                else {
    
                    //Berechnen der Bildmasse ohne Verzerren der Proportionen.
                    $width = $height / $oh * $ow;              
                }                 
            }
        }
        
        //Das Bild auf das vorgegebene Seitenverhältnis skalieren
        else {

            //Höhe gemäss Seitenverhältnis des Zielbildes festlegen (3:2)
            $height = $width / 1.5;
            
            //Höhe des Originalbildes gemäss Seitenverhältnis des Zielbildes festlegen (Ausschnitt zum Kopieren ohne Verzerren)
            $oh     = $ow / 1.5; 
        }
                                
        $thumbnail = imagecreatetruecolor($width, $height);
        
        //Wenn es sich um eine png-Datei handelt.
        if ($_FILES['file']['type'] == 'image/png') {
            
            //Transparente Farben bei png's erhalten
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);        
        }
               
        imagecopyresampled($thumbnail, $originalImage, 0, 0, 0, 0, $width, $height, $ow, $oh);
        
        //Verzeichnis beschreibbar machen.
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0757, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/'.$uploadPath.'/');            
                        
        //Wenn es sich um eine jpeg-Datei handelt.
        if ($_FILES['file']['type'] == 'image/jpeg' OR $_FILES['file']['type'] == 'image/pjpeg') {
            imagejpeg($thumbnail, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/'.$uploadPath.'/'.$_FILES['file']['name']);
        }            
            
        //Wenn es sich um eine png-Datei handelt.
        if ($_FILES['file']['type'] == 'image/png') {
            imagepng($thumbnail, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/'.$uploadPath.'/'.$_FILES['file']['name']);
        }                     
                        
        //Verzeichnis auf ursprüngliche Zugriffsrechte zurücksetzen.          
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/'.$uploadPath.'/');                        
        
        //Temporäre Ressourcen löschen.
        imagedestroy($originalImage);
        imagedestroy($thumbnail);
    }

    
    private function addVideo()
    { 
        $uploadDir  = SERVER_VIDEO_PATH.$GLOBALS['CONTENT']->DBTableName.'/';
        $uploadFile = $uploadDir.$_FILES['file']['name'];
        
        $uploadFile = $this->checkFileExistence($uploadFile);                
                    
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_VIDEO_PATH.$GLOBALS['CONTENT']->DBTableName);
        
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $this->ErrorStr = '<p>Die Datei <em>'.$_FILES['file']['name'].'</em> konnte nicht hochgeladen werden!</p>';          
        }      
        
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_VIDEO_PATH.$GLOBALS['CONTENT']->DBTableName);
          
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }                                    
    }  
    
    
    private function addAudio()
    { 
        $uploadDir  = SERVER_AUDIO_PATH.$GLOBALS['CONTENT']->DBTableName.'/';
        $uploadFile = $uploadDir.$_FILES['file']['name'];
        
        $uploadFile = $this->checkFileExistence($uploadFile);                
                    
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_AUDIO_PATH.$GLOBALS['CONTENT']->DBTableName);
        
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $this->ErrorStr = '<p>Die Datei <em>'.$_FILES['file']['name'].'</em> konnte nicht hochgeladen werden!</p>';          
        }      
        
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_AUDIO_PATH.$GLOBALS['CONTENT']->DBTableName);
          
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }                                    
    }  
    
    private function addFile()
    { 
        $uploadDir  = SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName.'/';
        $uploadFile = $uploadDir.$_FILES['file']['name'];
        
        $uploadFile = $this->checkFileExistence($uploadFile);                
                    
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName);
        
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $this->ErrorStr = '<p>Die Datei <em>'.$_FILES['file']['name'].'</em> konnte nicht hochgeladen werden!</p>';          
        }      
        
        ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName);
          
        if ($this->ErrorStr !== null) {
            $this->ErrorObj = false;
            
            //Datenbankoperation fand nicht statt; deshalb wird der SQLStatus auf false gesetzt. (Verhindert, 
            //dass das Formular leer angezeigt wird.)
            $this->DB->lastSQLStatus = false;
            return false;
        }                                    
    }            
    
    
    private function deleteImage($entry = null)
    {
        //Dateneintrags-ID in Variable schreiben.
        //Wenn eine GET-Variable 'delId' übergeben wurde, wird sie als Identifikator des Dateneintrags verwendet.
        if (isset($_GET['delId']) AND !empty($_GET['delId'])) {
            $entryId = $_GET['delId'];
        }
        
        //Wenn die Methoden-Variable 'entry' gefüllt ist, wird sie als Identifikator des Dateneintrags verwendet.
        if (!empty($entry)) {
            $entryId = $entry;
        }
        
        //Zuerst wird der Dateipfad aus der Datenbank gelesen und in der Variable 'filepath' gespeichert.
        if ($GLOBALS['CONTENT']->DBTableName == 'downloads_fotos') {
            $sql         = "SELECT filepath, thumbnailpath, admin_thumbnailpath, filepath_lightbox FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$entryId."";            
        }
        else {        
            $sql         = "SELECT filepath, thumbnailpath, admin_thumbnailpath FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$entryId."";
        }
        $result      = $this->DB->query($sql); 
        $arrayResult = $result->fetch_assoc();
        
        //Wenn ein Bild zum Dateneintrag zugeordnet ist, wird dieses gelöscht.
        if (!empty($arrayResult['filepath'])) {        
            $filepath    = explode('/',$arrayResult['filepath']);
            $filename    = array_pop($filepath);   
                 
            //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
            $uploadDir  = SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/';        
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0757, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName);
            
            //Die Bild-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
            if (!unlink($uploadDir.$filename)) {
                $this->ErrorStr = '<p>Die Datei <em>'.$filename.'</em> konnte nicht gelöscht werden!</p>';   
                $this->ErrorObj = false;                   
            }        
        
            //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName);
        
            //Wenn ein Thumbnail dem Dateneintrag zugeordnet ist, wird dieses ebenfalls gelöscht.
            if (!empty($arrayResult['thumbnailpath'])) {          
                     
                //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
                $uploadDir  = SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/thumb/';        
                ftp_chmod($GLOBALS['FTP']->FTPConn, 0757, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/thumb');
                
                //Die Bild-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
                if (!unlink($uploadDir.$filename)) {                  
                }        
            
                //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
                ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/thumb');
            }    
            
            //Wenn ein Thumbnail für den Admin-Bereich dem Dateneintrag zugeordnet ist, wird dieses ebenfalls gelöscht.
            if (!empty($arrayResult['admin_thumbnailpath'])) {          
                     
                //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
                $uploadDir  = SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/thumbAdmin/';        
                ftp_chmod($GLOBALS['FTP']->FTPConn, 0757, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/thumbAdmin');
                
                //Die Bild-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
                if (!unlink($uploadDir.$filename)) {                  
                }        
            
                //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
                ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/thumbAdmin');
            } 
                        
            //Wenn ein Thumbnail für den Admin-Bereich dem Dateneintrag zugeordnet ist, wird dieses ebenfalls gelöscht.
            if ($GLOBALS['CONTENT']->DBTableName == 'downloads_fotos') {
                if (!empty($arrayResult['filepath_lightbox'])) {          
                         
                    //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
                    $uploadDir  = SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/lightbox/';        
                    ftp_chmod($GLOBALS['FTP']->FTPConn, 0757, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/lightbox');
                    
                    //Die Bild-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
                    if (!unlink($uploadDir.$filename)) {                  
                    }        
                
                    //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
                    ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_IMAGES_PATH.$GLOBALS['CONTENT']->DBTableName.'/lightbox');
                } 
            }
        }                  
    }
    
    
    private function deleteVideo($entry = null)
    {
        //Dateneintrags-ID in Variable schreiben.
        //Wenn eine GET-Variable 'delId' übergeben wurde, wird sie als Identifikator des Dateneintrags verwendet.
        if (isset($_GET['delId']) AND !empty($_GET['delId'])) {
            $entryId = $_GET['delId'];
        }
        
        //Wenn die Methoden-Variable 'entry' gefüllt ist, wird sie als Identifikator des Dateneintrags verwendet.
        if (!empty($entry)) {
            $entryId = $entry;
        }
        
        //Zuerst wird der Dateipfad aus der Datenbank gelesen und in der Variable 'filepath' gespeichert.
        $sql         = "SELECT filepath FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$entryId."";
        $result      = $this->DB->query($sql); 
        $arrayResult = $result->fetch_assoc();
        
        //Wenn eine Video-Datei zum Dateieintrag zugeordnet ist, wird diese gelöscht.
        if (!empty($arrayResult['filepath'])) {        
            $filepath    = explode('/',$arrayResult['filepath']);
            $filename    = array_pop($filepath);   
                 
            //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
            $uploadDir  = SERVER_VIDEO_PATH.$GLOBALS['CONTENT']->DBTableName.'/';        
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_VIDEO_PATH.$GLOBALS['CONTENT']->DBTableName);
            
            //Die Video-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
            if (!unlink($uploadDir.$filename)) {
                $this->ErrorStr = '<p>Die Datei <em>'.$filename.'</em> konnte nicht gelöscht werden!</p>';   
                $this->ErrorObj = false;                   
            }        
        
            //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_VIDEO_PATH.$GLOBALS['CONTENT']->DBTableName);
        }
    }
    

    private function deleteAudio($entry = null)
    {
        //Dateneintrags-ID in Variable schreiben.
        //Wenn eine GET-Variable 'delId' übergeben wurde, wird sie als Identifikator des Dateneintrags verwendet.
        if (isset($_GET['delId']) AND !empty($_GET['delId'])) {
            $entryId = $_GET['delId'];
        }
        
        //Wenn die Methoden-Variable 'entry' gefüllt ist, wird sie als Identifikator des Dateneintrags verwendet.
        if (!empty($entry)) {
            $entryId = $entry;
        }
        
        //Zuerst wird der Dateipfad aus der Datenbank gelesen und in der Variable 'filepath' gespeichert.
        $sql         = "SELECT filepath FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$entryId."";
        $result      = $this->DB->query($sql); 
        $arrayResult = $result->fetch_assoc();
        
        //Wenn eine Audiodatei zum Dateieintrag zugeordnet ist, wird diese gelöscht.
        if (!empty($arrayResult['filepath'])) {        
            $filepath    = explode('/',$arrayResult['filepath']);
            $filename    = array_pop($filepath);   
                 
            //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
            $uploadDir  = SERVER_AUDIO_PATH.$GLOBALS['CONTENT']->DBTableName.'/';        
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_AUDIO_PATH.$GLOBALS['CONTENT']->DBTableName);
            
            //Die Audio-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
            if (!unlink($uploadDir.$filename)) {
                $this->ErrorStr = '<p>Die Datei <em>'.$filename.'</em> konnte nicht gelöscht werden!</p>';   
                $this->ErrorObj = false;                   
            }        
        
            //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_AUDIO_PATH.$GLOBALS['CONTENT']->DBTableName);
        }
    }        
    
    private function deleteFile($entry = null)
    {
        //Dateneintrags-ID in Variable schreiben.
        //Wenn eine GET-Variable 'delId' übergeben wurde, wird sie als Identifikator des Dateneintrags verwendet.
        if (isset($_GET['delId']) AND !empty($_GET['delId'])) {
            $entryId = $_GET['delId'];
        }
        
        //Wenn die Methoden-Variable 'entry' gefüllt ist, wird sie als Identifikator des Dateneintrags verwendet.
        if (!empty($entry)) {
            $entryId = $entry;
        }
        
        //Zuerst wird der Dateipfad aus der Datenbank gelesen und in der Variable 'filepath' gespeichert.
        $sql         = "SELECT filepath_word, filepath_pdf FROM ".$GLOBALS['CONTENT']->DBTableName." WHERE id = ".$entryId."";
        $result      = $this->DB->query($sql); 
        $arrayResult = $result->fetch_assoc();
        
        //Wenn eine PDF-datei zum Dateieintrag zugeordnet ist, wird diese gelöscht.
        if (!empty($arrayResult['filepath_word'])) {        
            $filepath    = explode('/',$arrayResult['filepath_word']);
            $filename    = array_pop($filepath);   
                 
            //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
            $uploadDir  = SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName.'/';        
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName);
            
            //Die PDF-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
            if (!unlink($uploadDir.$filename)) {
                $this->ErrorStr = '<p>Die Datei <em>'.$filename.'</em> konnte nicht gelöscht werden!</p>';   
                $this->ErrorObj = false;                   
            }        
        
            //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName);
        }
        
        if (!empty($arrayResult['filepath_pdf'])) {        
            $filepath    = explode('/',$arrayResult['filepath_pdf']);
            $filename    = array_pop($filepath);   
                 
            //Das entsprechende Verzeichnis wird bearbeitbar gemacht.
            $uploadDir  = SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName.'/';        
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0747, SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName);
            
            //Die PDF-Datei wird gelöscht und im Fehlerfall eine Meldung generiert.
            if (!unlink($uploadDir.$filename)) {
                $this->ErrorStr = '<p>Die Datei <em>'.$filename.'</em> konnte nicht gelöscht werden!</p>';   
                $this->ErrorObj = false;                   
            }        
        
            //Das Verzeichnis wird auf die ursprünglichen Zugriffsrechte zurückgesetzt.
            ftp_chmod($GLOBALS['FTP']->FTPConn, 0755, SERVER_DOWNLOAD_PATH.$GLOBALS['CONTENT']->DBTableName);
        }        
    }     
    
    
    /**
     * Überprüfung, ob eine Datei bereits existiert.
     * 
     * Es wird überprüft, ob im jeweiligen Verzeichnis bereits eine Datei mit diesem Namen existiert.
     * Falls ja, wird eine For-Schleife solange durchlaufen, bis ein noch nicht vorhandener Dateinamen
     * entstanden ist. Dabei wird zum Dateinamen ein Unterstrich und jeweils eine um eins höhere Zahl
     * hinzugefügt. (z.B. mein_bild_3.png) 
     * 
     * @param string $file: Das auf die Existenz zu prüfende File.
     */
    private function checkFileExistence($file)
    {        
        //Wenn die Datei nicht existiert, wird sie unverändert zurückgegeben.
        if (!file_exists($file)) {
            return $file;
        }
        
        //Existiert sie, wird sie (ihr Pfad) abgeändert, um sie einer weitergehenden Prüfung zu unterziehen.
        else {                                                 
        
            //Die Dateiendung wird bestimmt und separiert.
            $fileArray  = explode('/', $file);
            $fileEnd    = array_pop($fileArray); 
            $fileArray2 = explode('.', $fileEnd);  
            $fileExt    = array_pop($fileArray2);         
            $file       = $fileArray2[0];
        
            //In der for-Schleife wird zum Dateipfad (vor der Endung) bei jedem Durchgang eine um eins höhere Zahl
            //hinzugefügt und der neue Namen auf seine Existenz überprüft, bis ein Dateinamen gefunden wurde, der 
            //noch nicht existiert.
            for ($i = 2; file_exists($file.'_'.$i.'.'.$fileExt); ++$i) {
            }
            
        //Der Dateipfad wird mit dem neuen Namen wieder zusammengestellt.
        $file =  $file.'_'.$i.'.'.$fileExt; 
                
        //Der Dateipfad wird zusätzlich in das FILES-Array geschrieben, um ihn nachher in die Datenbank schreiben zu können.
        $_FILES['file']['name'] = basename($file);
        
        return $file;            
        }          
    }

}

?>
