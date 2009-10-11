<?php

class Content
{
    //Datenbank-Objekt
    private $DB = null;
    
    //Die aktuell ausgewählte Hauptseite
    private $SiteName = null;
    
    //Die entsprechende Datenbank-Tabelle
    private $DBTableName = null;
    
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
        $this->SiteName = Navi::$SiteName;                         
               
        //Wenn eine Unternavigation existiert, überprüfen, ob eine Unterseite ausgewählt ist.
        if (isset($_GET['subnav']) AND !empty($_GET['subnav'])) {
          
            //Wenn GET-Variable übergeben, wird diese als Datenbanktabellen-Name verwendet.                
            $this->DBTableName = $_GET['subnav'];
            
            //Wenn eine Unternavigation der Unternavigation existiert, überprüfen, ob eine Unterseite ausgewählt ist.
            if (isset($_GET['subsubnav']) AND !empty($_GET['subsubnav'])) {
              
                //Wenn GET-Variable übergeben, wird diese als Datenbanktabellen-Name verwendet.                
                $this->DBTableName = $_GET['subsubnav'];
            }              
        }                                
        
        //Wenn keine Unternavigation existiert.
        else {
          
            //Wenn keine Unternavigation existiert, wird der Seitenname (Hauptnavigation) als Datenbanktabelle verwendet.
            $this->DBTableName = $this->SiteName;            
        }
    }
    
    
    /**
     * Ausgabe des Inhalts
     * 
     * Die switch-Anweisung überprüft, welche Seite gewählt ist, führt dann die Datenbankanfrage
     * durch und gibt die Datensätze aus.
     */
    public function printContent()
    {                   
        echo '
      <div id="content">'."\n".''; 
        
        //Reihenfolge der Dateneinträge festlegen (Alle ausser 'Konzerte' werden nach 'sort_id' sortiert.)
        if ($this->DBTableName == 'konzerte') {
            
            //Nur diejenigen Konzerteinträge auswählen, die nicht in der Vergangenheit liegen.
            $sql    = "SELECT * FROM ".$this->DBTableName." WHERE timestamp > '".date('Y-m-d H:i:s')."' ORDER BY timestamp";
        }
        else {
                  
            //Unveränderte Datenbankabfrage
            $sql    = "SELECT * FROM ".$this->DBTableName." ORDER BY sort_id";
        }
        $result = $this->DB->query($sql, false);
               
        //Wenn kein Datenbankeintrag vorhanden ist, wird eine Meldung ausgegeben.
        if ($result->num_rows == 0) {
            if ($this->DBTableName !== 'konzerte') {
                echo '
            <div id="columne_l">   
              <div class="strich_extra">
              </div>
              <div class="text">
                <div id="kein_eintrag">
                  <p>Es sind keine Einträge vorhanden.</p>
                </div>
              </div>';
            }
            else {
                echo '
            <div id="columne_l">  
              <div class="strich_extra">
              </div>
              <div class="kein_eintrag"><p>Zur Zeit sind keine Einträge vorhanden.<br /><br />
                Möchten Sie unkompliziert über die kommenden Konzerte informiert werden, können Sie den <a class="bold" href="'.URL_RSS_KONZERTE.'">&#8594; RSS-Feed</a> abonnieren.</p>
              </div>';              
            }
        }
        else { 
        
            echo '
            <div id="columne_l">
              <div class="strich_extra"></div>';
              
            if ($this->SiteName !== 'sitemap') {
                echo '
              <div class="text">'; 
            }              
              
            //Die Einträge ausgeben                           
            switch ($this->DBTableName) {
          
                //Falls die Hauptseite (index)
                case 'home':      //fallthrough
                case 'our_philosophy':   

                    while($entry = $result->fetch_assoc()) {
                        echo '
            <h1>'.$entry['title'].'</h1>
            <p>'.nl2br($entry['text']).'</p>';
                    } 
                
                break;
            
                case 'chiefdoctor':     //fallthrough
                case 'homeopaths': 
                case 'gynecologists':
                case 'therapists':
                case 'neurologists':
                case 'psychologists': 
                case 'administration':   

                    while($entry = $result->fetch_assoc()) {
                        echo '
            <div class="about_us_name">
              <h1>'.nl2br($entry['name']).'</h1>
            </div>
            <div class="about_us_image">
              <img src="'.SERVER_ROOT_PATH.'/'.$entry['img_path'].'" alt="'.nl2br($entry['name']).'" />
            </div>
            <div class="about_us_text">
              <p>'.nl2br($entry['text']).'</p>
              <p>e-mail: ';              
                        echo Email::crypteEmail($entry['email']).'</p>
            </div>';
                    } 
                
                break;                        
               
                //Falls die Seite 'Oper', 'rollen', 'geistlich' oder 'liedzyklen'                
                case 'what_is_homeopathy':  //fallthrough
                case 'lifestyle': 
                case 'when_call_doctor': 
                case 'simple_answers': 
                case 'how_find_doctor': 
                case 'what_to_read': 
                case 'advertisements_patients': 

                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
			        <h1>'.$entry['title'].'</h1>
              <p>'.$entry['text'].'</p>';
                    } 
                
                break;
                
                //Falls die Seite 'französisch', 'deutsch' oder 'englisch'                                
                case 'clinical_discussions':    //fallthrough                                                                        
                case 'coming_seminars':                                         
                case 'past_seminars':                         
                case 'advertisements_doctors':                         

                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
            <div class="eintrag">
              <div class="komponist">'.$entry['composer'].'</div><div class="werke">'.nl2br($entry['pieces']).'</div>
              </div>';
                    } 
                
                break;                                
                
                //Falls die Seite 'szenisch' oder 'portraet'                                
                case 'articles_philosophy':    //fallthrough 
                case 'articles_methodology':                        
                case 'articles_simple_answers' :
                
                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
              <h2 id="article1">'.$entry['author'].'</h2>
              <h1 class="pad_t">'.$entry['title'].'</h1>
              <p>'.$entry['text'].'</p>';
                    } 
                                    
                break;   
                
                //Falls die Seite 'medien_klassisch' oder 'medien_chanson'               
                case 'guidebooks':     //fallthrough           
                case 'for_parents':
                case 'for_specialists': 
               
                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
            <div class="books_title">
              <h1 class="h_books">'.$entry['author'].'</h1>            
              <h2>'.$entry['title'].'</h2>
            </div>
            <div class="books_image">
              <img src="'.SERVER_ROOT_PATH.'/'.$entry['img_path'].'" alt="фото - '.$entry['author'].': '.$entry['title'].'" />
            </div>
            <div class="books_text">
              <p>'.$entry['text'].'</p>
            </div>';
                    } 
                                    
                break;                  
                
                //Falls die Seite 'Medien - Fotos'                
                case 'editions':      //fallthrough
                case 'parent_schools': 
                case 'info_sources': 
                case 'centers_and_doctors':

                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
              <h1>'.$entry['title'].'</h1>                        
              <p>'.$entry['text'].'</p>
              <a href="'.$entry['link'].'">'.$entry['link'].'</a>';
                    } 
                                    
                break;                                                                                                                                                                           
                
                //Falls die Seite 'Kontakt'                
                case 'contact_address': 

                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
          <p>'.$entry['text'].'</p>
          <p>E-mail: '.Email::crypteEmail(FORM_EMAIL).'</p>';
                    } 
                 
                break;  
                
                case 'roadmap': 

                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
          <p>'.$entry['text'].'</p>';
                    }             
                 
                break;                  
                
                //Falls die Seite 'Impressum'                
                case 'impressum': 

                    //Die Einträge ausgeben
                    while($entry = $result->fetch_assoc()) {
                        echo '
                            <p>'.$entry['text'].'</p>';
                    } 
                 
                break;
                
                //Falls die Seite 'Sitemap'                
                case 'sitemap': 

                    //Die Funktion zur Generierung der Sitemap aufrufen
                    $this->printSitemap();
                                     
                break;                                               
            }
            
            if ($this->SiteName !== 'sitemap') {
                echo '
                  </div>'; 
            }                         
        }          
                      
        echo ' 
            </div>
            <div id="columne_r">';
    }
    
    
    //Automatisierte Ausgabe der Sitemap aufgrund der Navi-Arrays der Klasse Navi
    private function printSitemap()
    {
        //Beginn der Hauptliste (ul)
        echo '
        <ul id="sitemap">';
        
        //Die Navi-Arrays in der Klasse 'Navi' werden genutzt.
        //Das Array der Hauptnavigation wird durchlaufen.
        foreach (Navi::$MainNaviArray as $naviRow) {
            
            //Wenn der Hauptlink eine Unternavigation hat:
            if (array_key_exists($naviRow['sitename'], Navi::$SubNaviArray)) {            
                echo '
          <li>'.$naviRow['linktext'].'
            <ul>';
            
                //Das Array der Unternavigation wird durchlaufen.                
                foreach (Navi::$SubNaviArray[$naviRow['sitename']] as $subnaviRow) {                
                
                    //Wenn der Unternavigationslink eine Unternavigation hat:
                    if (array_key_exists($subnaviRow['sitename'], Navi::$SubSubNaviArray)) {                        
                        echo '
                  <li>'.$subnaviRow['linktext'].'
                    <ul>';                        
                    
                        //Das Array der Unter-Unternavigation wird durchlaufen.                
                        foreach (Navi::$SubSubNaviArray[$subnaviRow['sitename']] as $subsubnaviRow) {                            
                            echo '
                      <li><a href="'.SERVER_ROOT_PATH.'/'.$naviRow['sitename'].'/'.$subnaviRow['sitename'].'/'.$subsubnaviRow['sitename'].'/">'.$subsubnaviRow['linktext'].'</a></li>';                             
                        }
                        
                        //Abschluss der Unter-Unternavigations-Liste
                        echo '
                    </ul>
                  </li>';                    
                    }
                    
                    //Wenn keine Unternavigation der Unternavigation existiert:
                    else {                       
                        echo '
                  <li><a href="'.SERVER_ROOT_PATH.'/'.$naviRow['sitename'].'/'.$subnaviRow['sitename'].'/">'.$subnaviRow['linktext'].'</a></li>';                        
                    }
                }
                
                //Abschluss der Unternavigations-Liste
                echo '
            </ul>
          </li>';                 
            }
            
            //Wenn keine Unternavigation existiert:
            else {                
                echo '
          <li><a href="'.SERVER_ROOT_PATH.'/'.$naviRow['sitename'].'/">'.$naviRow['linktext'].'</a></li>';                 
            }          
        }
        
        //Das Array der Footernavigation wird durchlaufen.
        foreach (Navi::$FooNaviArray as $naviRow) {            
            echo '
          <li><a href="'.SERVER_ROOT_PATH.'/'.$naviRow['sitename'].'/">'.$naviRow['linktext'].'</a></li>';                           
        }
                            
        //Abschluss der Hauptliste (ul)
        echo '
        </ul>';        
    }
    
    
    //Ausgabe der Dateigrösse; automatische Umstellung der Masseinheit (Kb oder Mb)
    private function printFileSize($file)
    {
        
        //Wenn die Dateigrösse ein Mb überschreitet, wird in Mb ausgegeben, ansonsten in Kb.
        if (filesize($file) < 1048576) {
            $fileSize  = round((filesize($file ) / 1024)); 
            $fileSize .= ' Kb';           
        }
        else {        
            $fileSize  = round((filesize($file ) / 1048576), 1);
            $fileSize .= ' Mb';  
        }
       
        echo '('.$fileSize.')';
    }                    
  
}

?>