<?php
require_once '../../common.php';

//Wenn keine ID eines Dateneintrags per GET übermittelt wurde, passiert nichts.
if (isset($_GET['id']) AND is_numeric($_GET['id'])) {        
    $id        = $GLOBALS['DB']->MySQLiObj->real_escape_string($_GET['id']);
    $tablename = $GLOBALS['DB']->MySQLiObj->real_escape_string($_GET['tablename']);

    $sql    = "SELECT * FROM ".$tablename." WHERE id = '".$id."'";
    $result = $GLOBALS['DB']->query($sql, false);
    
    //Die Ursprungsseite wird ausgelesen, um sie für die Links zusammensetzen zu können.
    //Zuerst die allfällige Unternavigation.
    if (isset($_GET['subnav']) AND !empty($_GET['subnav'])) {
        $subnav = 'subnav='.$_GET['subnav'].'&';
    }
    else {
        $subnav = '';
    }
    
    //Dann die allfällige Unternavigation der Unternavigation.
    if (isset($_GET['subsubnav']) AND !empty($_GET['subsubnav'])) {
        $subsubnav = '&subsubnav='.$_GET['subsubnav'].'&';
    }
    else {
        $subsubnav = '';
    }        
            
    //Derr Head-Teil des Eintrags wird ausgegeben.
    echo '
          <div class="eintrag">
            <input type="checkbox" name="entries[]" value="'.$id.'" />
            <div class="data">
              <a href="'.$_GET['siten'].'.php?'.$subnav.$subsubnav.'editId='.$id.'#buttons">';                 
   
    //Wenn kein Datensatz aus der Datenbank geholt werden konnte, existiert dieser nicht und eine Meldung wird ausgegeben.
    if (count($result) == 0) {
        echo 'Der Eintrag mit der id '.$id.' ist nicht vorhanden!</div></div>';
        return false;
    }
    
    $entry = $result->fetch_assoc();
    
    //Ausgabe der einzelnen veränderlichen Daten
    //Um allenfalls zu langen Strings vorzubeugen, werden diese an die Funktion 'checkLength' übergeben und falls nötig gekürzt.
    switch ($tablename) {
  
        //Falls die Hauptseite (index)
        case 'home':                                           
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['title']));                        
            break;
    
        //Falls die Seite 'Konzerte'
        case 'konzerte':     
            echo htmlspecialchars($entry['location']).' | '.htmlspecialchars($entry['date']).', '.htmlspecialchars($entry['time']).' Uhr';                                            
            break;
       
        //Falls die Seite 'Rezitalprogramme'                
        case 'oper':        //fallthrough                                  
        case 'rollen': 
        case 'geistlich': 
        case 'liedzyklen': 
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['composer'].': '.$entry['pieces']));
            break;                   
                
        //Falls die Seite 'Bilder'                                        
        case 'franzoesisch':    //fallthrough                                                       
        case 'deutsch':                                                        
        case 'englisch':                         
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['composer'].': '.$entry['pieces']));
            break;                       
        
        //Falls die Seite 'CD'                                
        case 'szenisch':      //fallthrough                    
        case 'portraet': 
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['title'].' | '.$entry['description']));
            break; 
            
        //Falls die Seite 'medien_klassisch'                                        
        case 'medien_klassisch':    //fallthroug                       
        case 'medien_chanson':                         
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['description']));
            break;                         
        
        //Falls die Seite 'Video'                                
        case 'medien_filme':                         
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['title'].' | '.$entry['description']));
            break;  
            
        //Falls die Seite 'Video'                                
        case 'projekte_duo':        //fallthrough                    
        case 'projekte_publikation':                         
        case 'projekte_forschung':                         
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['text']));
            break;                                                                                                            
        
        //Falls die Seite 'Reviews'                
        case 'downloads_fotos': 
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['description']));
            break;  
            
        //Falls die Seite 'Reviews'                
        case 'downloads_cv': 
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['description']));
            break;                                                                      
       
        //Falls die Seite 'Kontakt'                
        case 'kontakt': 
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['title']));
            break;                           
            
        //Falls die Seite 'Reviews'                
        case 'links': 
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['linktext'].' | '.$entry['hyperlink']));
            break;  
            
        //Falls die Seite 'Reviews'                
        case 'excerpts': 
            echo htmlspecialchars($GLOBALS['EntryHandler']->checkLength($entry['author'].' | '.$entry['text']));
            break;                                                     
    }                

    //Ausgabe des jeweils unveränderlichen abschliessenden Teils (Buttons zum Editieren und Löschen der einzelnen Einträge.)
    echo '
            </a>
          </div>
          <div class="buttons">';
          
    //Wenn die Vorschau geöffnet werden soll, wird ein entsprechender Link ausgegeben.
    if (isset($_GET['preview']) AND $_GET['preview'] == 'close') {
        echo '
            <a href="javascript:update(\'scripts/AJAX/previewEntry.php?'.$subnav.$subsubnav.'id='.$id.'&tablename='.$tablename.'&siten='.$_GET['siten'].'\', \'item_'.$id.'\');"><img src="images/button_preview.png" /></a>';
    }
    else {
        echo '
            <a href="javascript:update(\'scripts/AJAX/previewEntry.php?'.$subnav.$subsubnav.'id='.$id.'&tablename='.$tablename.'&siten='.$_GET['siten'].'&preview=close\', \'item_'.$id.'\');"><img src="images/button_preview_close.png" /></a>';
    }
    
    echo '
              
            <a href="'.$_GET['siten'].'.php?'.$subnav.$subsubnav.'editId='.$id.'#buttons"><img src="images/button_edit.png" /></a>
            <a href="'.$_GET['siten'].'.php?'.$subnav.$subsubnav.'delId='.$id.'"><img src="images/button_delete.png" /></a>                
          </div>              
        </div>';
            
    //Wenn die Vorschau geöffnet werden soll, wird das entsprechende DIV ('preview') mit den Daten ausgegeben.
    if (!isset($_GET['preview']) OR $_GET['preview'] !== 'close') {
        echo '            
        <div id="preview'.$id.'">
          <div class="vorschau">';                    
    
        //Switch-Anweisung zur Auswahl der gewählten Seite
        switch ($tablename) {
          
            //Falls die Hauptseite (index)
            case 'home':                   
    
                echo '
                <p>'.nl2br($entry['title']).'</p><br />
                <p>'.nl2br($entry['text']).'</p>';            
    
                break;
            
            //Falls die Seite 'Konzerte'
            case 'konzerte':                
                
                echo '
                <p>'.$entry['date'].'</p>
                <p>'.$entry['location'].'</p>
                <p>'.$entry['place'].'</p>
                <p>'.$entry['link'].'</p>
                <p>'.$entry['time'].' Uhr</p><br />
                <p>'.nl2br($entry['program']).'</p><br />            
                <p>Beschreibung für das Newsfeld:</p>
                <p>'.nl2br($entry['newstext']).'</p>';
    
                break;
               
            //Falls die Seite 'Rezitalprogramme'                
            case 'oper':        //fallthrough
            case 'rollen': 
            case 'geistlich': 
            case 'liedzyklen': 
    
                echo '
                <p>'.$entry['composer'].'</p><br />
                <p>'.nl2br($entry['pieces']).'</p>';            
    
                break; 
                
            //Falls die Seite 'Tonbeispiele'                                     
            case 'franzoesisch':  //fallthrough
            case 'deutsch': 
            case 'englisch': 
    
                echo '
                <p>'.$entry['composer'].'</p><br />
                <p>'.nl2br($entry['pieces']).'</p>';            
    
                break;                                                   
                        
            //Falls die Seite 'Bilder'                                        
            case 'szenisch':       //fallthrough                  
            case 'portraet':                         
                
                echo '
                <p>'.nl2br($entry['title']).'</p><br />
                <p>'.nl2br($entry['description']).'</p><br />           
                <p>Mit dem Eintrag verbundene Bild-Datei:</p><br />';
                
                if (!empty($entry['filepath'])) {
                  
                    $fileArray = explode('/', $entry['filepath']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <img src="'.SERVER_ROOT_PATH.$entry['admin_thumbnailpath'].'" />
                <p>'.nl2br($file).'</p>';
                }
                else {
                    echo '            
                <div class="hinweis_overview">
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist kein Bild zugeordnet.</p>
                </div>';
                }           
    
                break;                             
                
            //Falls die Seite 'Video'                                
            case 'medien_klassisch':          //fallthrough               
            case 'medien_chanson':                         
                
                echo '
                <p>'.nl2br($entry['description']).'</p><br />
                <p>Mit dem Eintrag verbundene Audio-Datei:</p>';
                
                if (!empty($entry['filepath'])) {
                  
                    $fileArray = explode('/', $entry['filepath']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <p>'.$file.'</p>'; 
                }
                else {
                    echo '            
                <div class="hinweis_overview">                  
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist keine Audio-Datei zugeordnet.</p>
                </div>';
                } 
                         
                break;                             
                
            //Falls die Seite 'Medien - Filme'                                        
            case 'medien_filme':                         
                    
                    echo '
                <p>'.nl2br($entry['title']).'</p><br />
                <p>'.nl2br($entry['description']).'</p><br />
                <p>Mit dem Eintrag verbundene Video-Datei:</p>'; 
     
                if (!empty($entry['filepath'])) {
                  
                    $fileArray = explode('/', $entry['filepath']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <p>'.$file.'</p>'; 
                }
                else {
                    echo '            
                <div class="hinweis_overview">
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist keine Video-Datei zugeordnet.</p>
                </div>';
                } 
                
                    break;     
                    
            //Falls die Seite 'Projekte - Duo'                
            case 'projekte_duo':      //fallthrough
            case 'projekte_publikation': 
    
                    echo '
                <p>'.nl2br($entry['text']).'</p>';
                
                if (!empty($entry['filepath'])) {
                  
                    $fileArray = explode('/', $entry['filepath']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <img src="'.SERVER_ROOT_PATH.$entry['admin_thumbnailpath'].'" />
                <p>'.nl2br($file).'</p>';
                }
                else {
                    echo '            
                <div class="hinweis_overview">
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist kein Bild zugeordnet.</p>
                </div>';
                }           
    
                break;                 
                    
            //Falls die Seite 'Projekte - Forschung'                
            case 'projekte_forschung': 
    
                    echo '
                <p>'.nl2br($entry['text']).'</p>';
                
                    break;                                                 
                
            //Falls die Seite 'Download - Fotos'                
            case 'downloads_fotos': 
    
                echo '
                <p>'.nl2br($entry['description']).'</p><br />           
                <p>Mit dem Eintrag verbundene Bild-Datei:</p><br />';
                
                if (!empty($entry['filepath'])) {
                  
                    $fileArray = explode('/', $entry['filepath']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <img src="'.SERVER_ROOT_PATH.$entry['admin_thumbnailpath'].'" />
                <p>'.nl2br($file).'</p>';
                }
                else {
                    echo '            
                <div class="hinweis_overview">
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist kein Bild zugeordnet.</p>
                </div>';
                }           
    
                break;   
                
            //Falls die Seite 'Kontakt'                
            case 'downloads_cv': 
    
                echo '
                <p>'.nl2br($entry['description']).'</p><br />
                <p>Mit dem Eintrag verbundene Word-Datei:</p>'; 
                
                if (!empty($entry['filepath_word'])) {
                  
                    $fileArray = explode('/', $entry['filepath_word']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <p>'.$file.'</p>'; 
                }
                else {
                    echo '            
                <div class="hinweis_overview">
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist keine Word-Datei zugeordnet.</p>
                </div><br />';
                }  
                
                echo '
                <p>Mit dem Eintrag verbundene PDF-Datei:</p>';              
                
                if (!empty($entry['filepath_pdf'])) {
                  
                    $fileArray = explode('/', $entry['filepath_pdf']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <p>'.$file.'</p>'; 
                }
                else {
                    echo '            
                <div class="hinweis_overview">
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist keine PDF-Datei zugeordnet.</p>
                </div>';
                }                      
    
                break;                       
                
            //Falls die Seite 'Kontakt'                
            case 'kontakt': 
    
                echo '
                <p>'.nl2br($entry['title']).'</p><br />
                <p>Mit dem Eintrag verbundene Bild-Datei:</p><br />';
                
                if (!empty($entry['filepath'])) {
                  
                    $fileArray = explode('/', $entry['filepath']);
                    $file      = array_pop($fileArray);   
                               
                    echo '
                <img src="'.SERVER_ROOT_PATH.$entry['admin_thumbnailpath'].'" />
                <p>'.nl2br($file).'</p>';
                }
                else {
                    echo '            
                <div class="hinweis_overview">                  
                  <p>Hinweis!</p>
                  <p>Dem Dateneintrag ist kein Bild zugeordnet.</p>
                </div>';
                }           
    
                break;     
                
            //Falls die Seite 'Kontakt'                
            case 'links': 
    
                echo '
                <p>'.nl2br($entry['hyperlink']).'</p><br />
                <p>'.nl2br($entry['linktext']).'</p>';
    
                break;  
                
            //Falls die Seite 'Kontakt'                
            case 'excerpts': 
    
                echo '
                <p>'.nl2br($entry['author']).'</p><br />
                <p>'.nl2br($entry['text']).'</p>';
    
                break;                                                                                                                                   
        } 
    
        echo '
            </div>
          </div>';               
    }
}

?>