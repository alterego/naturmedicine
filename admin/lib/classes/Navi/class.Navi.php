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
    private static $MainNaviArray = array(
                                0 => array(
                                    'sitename' => "home",
                                    'linktext' => "About"           
                                    ),
                                1 => array(
                                    'sitename' => "konzerte",
                                    'linktext' => "Konzerte" 
                                    ),
                                2 => array(
                                    'sitename' => "repertoire",
                                    'linktext' => "Repertoire"                    
                                    ),
                                3 => array(
                                    'sitename' => "fotos",
                                    'linktext' => "Fotos"                     
                                    ),
                                4 => array(
                                    'sitename' => "medien",
                                    'linktext' => "Medien"
                                    ),
                                5 => array(
                                    'sitename' => "projekte",
                                    'linktext' => "Projekte"
                                    ),                                    
                                6 => array(
                                    'sitename' => "downloads",
                                    'linktext' => "Downloads"
                                    ),  
                                7 => array(
                                    'sitename' => "kontakt",
                                    'linktext' => "Kontakt"
                                    ),   
                                8 => array(
                                    'sitename' => "links",
                                    'linktext' => "Links"
                                    ),       
                                9 => array(
                                    'sitename' => "excerpts",
                                    'linktext' => "Zitate"
                                    )                                                                     
                                );  
    
    
    /**
     * Die Unternavigations-Links sind in ein Array gesetzt:
     * Für jede Hauptseite existiert darin ein Array mit den entsprechenden Links:
     * Der Schlüssel 'sitename' enthält den internen Bezeichner der Seite.
     * Der Schlüssel 'linktext' enthält den ausgegebenen Linktext.
     */
    public static $SubNaviArray = array(
                                'repertoire' => array(
                                              0 => array(
                                                  'sitename' => "klassisch",
                                                  'linktext' => "klassisch"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "chanson",
                                                  'linktext' => "chanson" 
                                                  )
                                              ),
                                'fotos' => array(
                                              0 => array(
                                                  'sitename' => "szenisch",
                                                  'linktext' => "szenisch"
                                                  ),
                                              1 => array(
                                                  'sitename' => "portraet",
                                                  'linktext' => "portrait"
                                                  )
                                              ),    
                                'medien' => array(
                                              0 => array(
                                                  'sitename' => "medien_musik",
                                                  'linktext' => "musik"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "medien_filme",
                                                  'linktext' => "filme" 
                                                  )
                                              ),
                                'projekte' => array(
                                              0 => array(
                                                  'sitename' => "projekte_duo",
                                                  'linktext' => "duo"           
                                                  ),
                                              1 => array(
                                                  'sitename' => "projekte_publikation",
                                                  'linktext' => "publikation" 
                                                  ),
                                              2 => array(
                                                  'sitename' => "projekte_forschung",
                                                  'linktext' => "forschung" 
                                                  )                                                  
                                              ),                                               
                                'downloads' => array(
                                              0 => array(
                                                  'sitename' => "downloads_fotos",
                                                  'linktext' => "fotos"
                                                  ),
                                              1 => array(
                                                  'sitename' => "downloads_cv",
                                                  'linktext' => "cv"
                                                  )                                                  
                                              )                                              
                                        );     
    
    
    public static $SubSubNaviArray = array(
                                'klassisch' => array(
                                              0 => array(
                                                  'sitename' => "oper",
                                                  'linktext' => "oper"
                                                  ),
                                              1 => array(
                                                  'sitename' => "rollen",
                                                  'linktext' => "rollen"
                                                  ),
                                              2 => array(
                                                  'sitename' => "geistlich",
                                                  'linktext' => "geistliche musik"
                                                  ),
                                              3 => array(
                                                  'sitename' => "liedzyklen",
                                                  'linktext' => "liedzyklen"
                                                  )                                                                                                     
                                              ),      
                                'chanson' => array(
                                              0 => array(
                                                  'sitename' => "franzoesisch",
                                                  'linktext' => "französisch"
                                                  ),
                                              1 => array(
                                                  'sitename' => "deutsch",
                                                  'linktext' => "deutsch"
                                                  ),
                                              2 => array(
                                                  'sitename' => "englisch",
                                                  'linktext' => "englisch"
                                                  )                                                
                                              ),
                                'medien_musik' => array(
                                              0 => array(
                                                  'sitename' => "medien_klassisch",
                                                  'linktext' => "klassisch"
                                                  ),
                                              1 => array(
                                                  'sitename' => "medien_chanson",
                                                  'linktext' => "chanson"
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
                "sitemap"   !== basename($_SERVER['PHP_SELF'],".php") or 
                "links"     !== basename($_SERVER['PHP_SELF'],".php")) {        
                  
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
                            
                        //Wenn keine Unternavigation der Unternavigation existiert.
                        if (!array_key_exists($subnaviRow['sitename'], self::$SubSubNaviArray)) {

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
                        
                        //Wenn eine Unternavigation der Unternavigation existiert.
                        else {           
                                                                            
                           //Inhalt der GET-Variable überprüfen
                            if (isset($_GET['subsubnav'])) {
                                if (Security::checkWhitelistGET($_GET['subsubnav'], 'subnav') === false) {
                                    $_GET['subsubnav'] = self::$SubSubNaviArray[$subnaviRow['sitename']][0]['sitename'];
                                }                             
                            }                            
                           
                            //Wenn die Seite ausgewählt ist.
                            if (isset($_GET['subnav']) AND $subnaviRow['sitename'] == $_GET['subnav']) {                                                 
                                echo '
              <li class="selected"><a class="selected">'.$subnaviRow['linktext'].'</a>
                <ul>';         
                
                                self::$subNav = '<div class="pfeil">&nbsp;&#10174;&nbsp;</div><div class="great">'.$subnaviRow['linktext'].'</div>';
                            }
                            
                            //Wenn die Seite nicht ausgewählt ist.
                            else {
                                echo '
              <li><a>'.$subnaviRow['linktext'].'</a>
                <ul>';
                            }
                                                        
                            //Jeder Unternavigations-Link der Unternavigation wird abgeklärt, ob er ausgewählt ist.
                            foreach (self::$SubSubNaviArray[$subnaviRow['sitename']] as $subsubnaviRow) {   
                                
                                //Wenn die Seite ausgewählt ist.
                                if (isset($_GET['subsubnav']) AND $subsubnaviRow['sitename'] == $_GET['subsubnav']) {                                                 
                                    echo '
                  <li class="selected"><a class="selected">'.$subsubnaviRow['linktext'].'</a></li>';
                  
                                    self::$subSubNav = '<div class="pfeil">&nbsp;&#10174;&nbsp;</div><div class="great">'.$subsubnaviRow['linktext'].'</div>';
                                } 
                                
                                //Wenn die Seite nicht ausgewählt ist.
                                else {    
									echo '
                  <li><a href="'.$mainSite.'?subnav='.$subnaviRow['sitename'].'&subsubnav='.$subsubnaviRow['sitename'].'">'.$subsubnaviRow['linktext'].'</a></li>';
                                }
                            }
                            
                            //Schliessen des "ul" und "li" der Unternavigations-Links der Unternavigation.                            
                            echo '
                </ul>
              </li>';
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
