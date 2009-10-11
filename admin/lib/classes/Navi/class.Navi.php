<?php

class Navi
{
   
    //Aktuelle Seitenname ('basename' ohne Endung)
    public static $siteName = null;
    
    //Allfälliger aktueller Unternavigations-Name
    public static $subNav = null;
    
    //Allfälliger aktueller Unternavigations-Name der Unternavigation.
    public static $subSubNav = null;
    
    
    /**
     * Die Hauptnavigations-Links sind in ein Array gesetzt:
     * Der Schlüssel 'sitename' enthält den internen Bezeichner der Seite.
     * Der Schlüssel 'linktext' enthält den ausgegebenen Linktext.
     */
    public static $MainNaviArray = array(
                                0 => array(
                                    'sitename' => "home",
                                    'linktext' => "о нас"           
                                    ),
                                1 => array(
                                    'sitename' => "patients",
                                    'linktext' => "для пациентов" 
                                    ),
                                2 => array(
                                    'sitename' => "doctors",
                                    'linktext' => "для врачей"                    
                                    ),
                                3 => array(
                                    'sitename' => "articles",
                                    'linktext' => "статьи"                     
                                    ),
                                4 => array(
                                    'sitename' => "books",
                                    'linktext' => "книги"
                                    ),
                                5 => array(
                                    'sitename' => "links",
                                    'linktext' => "наши друзья"
                                    ),                                    
                                6 => array(
                                    'sitename' => "contact",
                                    'linktext' => "контакты"
                                    )                                                                    
                                );  
    
    
    /**
     * Die Unternavigations-Links sind in ein Array gesetzt:
     * Für jede Hauptseite existiert darin ein Array mit den entsprechenden Links:
     * Der Schlüssel 'sitename' enthält den internen Bezeichner der Seite.
     * Der Schlüssel 'linktext' enthält den ausgegebenen Linktext.
     */
    public static $SubNaviArray = array(
                                'home' => array(
                                              0 => array(
                                                  'sitename' => "our_philosophy",
                                                  'linktext' => "наша философия"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "chiefdoctor",
                                                  'linktext' => "главный врач" 
                                                  ),
                                              2 => array(
                                                  'sitename' => "homeopaths",
                                                  'linktext' => "врачи-гомеопаты"           
                                                  ),
                                              3 => array(
                                                  'sitename' => "gynecologists",
                                                  'linktext' => "акушеры-гинекологи" 
                                                  ),
                                              4 => array(
                                                  'sitename' => "therapists",
                                                  'linktext' => "мануальные терапевты"           
                                                  ),
                                              5 => array(
                                                  'sitename' => "neurologists",
                                                  'linktext' => "неврологи" 
                                                  ),
                                              6 => array(
                                                  'sitename' => "psychologists",
                                                  'linktext' => "психологи"           
                                                  ),
                                              7 => array(
                                                  'sitename' => "administration",
                                                  'linktext' => "администрация" 
                                                  )                                                                                                                                                      
                                              ),
                                'patients' => array(
                                              0 => array(
                                                  'sitename' => "what_is_homeopathy",
                                                  'linktext' => "что такое гомеопатия"          
                                                  ),
                                              1 => array(
                                                  'sitename' => "lifestyle",
                                                  'linktext' => "образ жизни" 
                                                  ),
                                              2 => array(
                                                  'sitename' => "when_call_doctor",
                                                  'linktext' => "когда нужио звонить врачу"           
                                                  ),
                                              3 => array(
                                                  'sitename' => "simple_answers",
                                                  'linktext' => "часто задаваемые вопросы" 
                                                  ),
                                              4 => array(
                                                  'sitename' => "how_find_doctor",
                                                  'linktext' => "как найти врача"           
                                                  ),
                                              5 => array(
                                                  'sitename' => "what_to_read",
                                                  'linktext' => "что можно почитать" 
                                                  ),
                                              6 => array(
                                                  'sitename' => "advertisements_patients",
                                                  'linktext' => "объявления"           
                                                  ) 
                                              ),    
                                'doctors' => array(
                                             0 => array(
                                                  'sitename' => "clinical_discussions",
                                                  'linktext' => "клинические разборы"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "coming_seminars",
                                                  'linktext' => "ближайшие семинары" 
                                                  ),
                                              2 => array(
                                                  'sitename' => "past_seminars",
                                                  'linktext' => "прошедшие семинары"           
                                                  ),
                                              3 => array(
                                                  'sitename' => "advertisements_doctors",
                                                  'linktext' => "объявления" 
                                                  )
                                              ),
                                'articles' => array(
                                              0 => array(
                                                  'sitename' => "articles_philosophy",
                                                  'linktext' => "философия"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "articles_methodology",
                                                  'linktext' => "методология" 
                                                  ),
                                              2 => array(
                                                  'sitename' => "articles_simple_answers",
                                                  'linktext' => "просто о сложном"           
                                                  )                                               
                                              ),                                              
                                'books' => array(
                                              0 => array(
                                                  'sitename' => "guidebooks",
                                                  'linktext' => "домашние лечебники"
                                                  ),
                                              1 => array(
                                                  'sitename' => "for_parents",
                                                  'linktext' => "информация для родителей"
                                                  ),
                                              2 => array(
                                                  'sitename' => "for_specialists",
                                                  'linktext' => "для специалистов"           
                                                  )                                                      
                                              ), 
                                'links' => array(
                                              0 => array(
                                                  'sitename' => "editions",
                                                  'linktext' => "издательства"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "parent_schools",
                                                  'linktext' => "родительские школы" 
                                                  ),
                                              2 => array(
                                                  'sitename' => "info_sources",
                                                  'linktext' => "информационные ресурсы"           
                                                  ),
                                              3 => array(
                                                  'sitename' => "centers_and_doctors",
                                                  'linktext' => "центры и врачи"           
                                                  )                                             
                                              ), 
                                'contact' => array(
                                              0 => array(
                                                  'sitename' => "contact_address",
                                                  'linktext' => "контактный адрес"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "roadmap",
                                                  'linktext' => "как проехать" 
                                                  )
                                              )                                                                                                                                          
                                        );    
           
    
    /**
     * Die Footernavigations-Links (wie Impressum, Sitemap etc.) sind in ein Array gesetzt:
     * Der Schlüssel 'sitename' enthält den internen Bezeichner der Seite.
     * Der Schlüssel 'linktext' enthält den ausgegebenen Linktext.
     */                       
    private static $FooNaviArray = array(                               
                                0 => array(
                                    'sitename' => "impressum",
                                    'linktext' => "impressum"
                                    )     
                                );                                   

     /* Ausgabe der Hauptnavigation
     *   
     * Das Array '$MainNaviArray' durchläuft die foreach-Schleife, die überprüft, ob der
     * jeweilige Link mit der ausgewählten Seite übereinstimmt. Bei positivem Ergebnis wird der
     * Link als 'selected' markiert und per CSS hervorgehoben.
     * 
     * @param string $lastLink:  Der letzte Link der Hauptnavigation
     */
    public static function printMainNavi($lastLink)
    {                                    
               echo "\n".'         
      <div id="navi">
        <ul>';   
                    
        //Wenn nicht eine Seite der Footer-Navigation ausgewählt ist, wird der Hauptnavigations-Link ausgegeben.
        foreach (self::$MainNaviArray as $naviRow) {
            if ("impressum" !== basename($_SERVER['PHP_SELF'],".php") or
                "sitemap"   !== basename($_SERVER['PHP_SELF'],".php")) {        
                  
                //Wenn keine Unternavigation existiert.
                if (!array_key_exists($naviRow['sitename'], self::$SubNaviArray)) {
                    if ($naviRow['sitename'] !== 'kontakt') {
                        echo '
          <li class="norm">';
                    } 
                    else {
                        echo '
          <li>';
                    }  
                                   
                    //Wenn die Seite nicht ausgewählt ist.
                    if ($naviRow['sitename'] !== basename($_SERVER['PHP_SELF'],".php")) {
                        echo '<a href="'.$naviRow['sitename'].'.php">'.$naviRow['linktext'].'</a></li>';                 
                    }
                    
                    //Wenn die Seite ausgewählt ist.
                    else {
                        echo '<a class="selected">'.$naviRow['linktext'].'</a></li>';
                        
                        self::$siteName = '<div class="great">'.$naviRow['linktext'].'</div>';
                    }                                                            
                }            
            
                //Wenn eine Unternavigation existiert. 
                else {
                  
                   //Inhalt der GET-Variable überprüfen
                    if (isset($_GET['subnav'])) {
                        if (Security::checkWhitelistGET($_GET['subnav'], 'subnav') === false) {
                            $_GET['subnav'] = self::$SubNaviArray[basename($_SERVER['PHP_SELF'],".php")][0]['sitename'];
                        } 
                    }                                                              
                    
                    if ($naviRow['sitename'] !== 'kontakt') {
                        echo '
          <li class="norm">';
                    } 
                    else {
                        echo '
          <li>';
                    }                     
                    
                    //Wenn die Seite nicht ausgewählt ist.
                    if ($naviRow['sitename'] !== basename($_SERVER['PHP_SELF'],".php")) {
                        echo '<a>'.$naviRow['linktext'].'</a>
            <ul>';                 
                    }
                    
                    //Wenn die Seite ausgewählt ist.
                    else {
                        echo '<a class="selected">'.$naviRow['linktext'].'</a>
            <ul>';
            
                        self::$siteName = '<div class="great">'.$naviRow['linktext'].'</div>';
                    }                                                            
                    
                    //Die jeweilige Hauptseite speichern, um sie bei den Unternavigationslinks einsetzen zu können. 
                    //(wird später überschrieben)
                    $mainSite = $naviRow['sitename'].'.php';                                                                             
                        
                    //Jeder Unternavigations-Link wird abgeklärt.
                    foreach (self::$SubNaviArray[$naviRow['sitename']] as $subnaviRow) {                         
                     
                        //Wenn die Seite ausgewählt ist.
                        if (isset($_GET['subnav']) AND $subnaviRow['sitename'] == $_GET['subnav']) {                                                 
                                    
                            echo '
          <li><a class="selected">'.$subnaviRow['linktext'].'</a></li>';
          
                            self::$subNav = '<div class="pfeil">&nbsp;&#10174;&nbsp;</div><div class="great">'.$subnaviRow['linktext'].'</div>';
                        }
                        
                        //Wenn die Seite nicht ausgewählt ist.
                        else {
                            echo '
          <li><a href="'.$mainSite.'?subnav='.$subnaviRow['sitename'].'">'.$subnaviRow['linktext'].'</a></li>';
                        }                            
                    }
                    
                    //Schliessen des "ul" und "li" der Unternavigations-Links.                            
                    echo '
            </ul>
          </li>'; 
				       }                                         
           }
        }
                                    
        //Abschluss des Divs 'navi'
        echo '
		  <li>
          <a class="logout" href="logout.php">LOGOUT</a>
        </li> 
        </ul>
		</div>
      <div id="navi_location"></div>
      <div id="content">'."\n".'';      
    }          
    
    
    //Ermittelt die aktuelle Situation in der Navigation und gibt diese aus.
    public static function printLocation()
    {                
        echo '
        <div id="navi_location">
          <p class="pfeil">Sie befinden sich hier:&nbsp; '.self::$siteName.self::$subNav.self::$subSubNav.'</p>
        </div>';
    }
      
}

?>
