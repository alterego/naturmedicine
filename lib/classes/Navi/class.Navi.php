<?php

class Navi
{
   
    public static $SiteName = null; 
    
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
    public static $FooNaviArray = array(                               
                                0 => array(
                                    'sitename' => "impressum",
                                    'linktext' => "Информация о странице"
                                    ),  
                                1 => array(
                                    'sitename' => "sitemap",
                                    'linktext' => "Карта сайта"
                                    )                                                                    
                                );                                   

   /**
     * Ausgabe der Hauptnavigation
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
                                  
         //Die übergebene GET-Variable 'sitename' bestimmt den Inhalt der Hauptseite.
         if (isset($_GET['sitename']) AND !empty($_GET['sitename'])) {
            if (Security::checkWhitelistGET($_GET['sitename'], 'subnav') === false) {
                self::$SiteName = 'home';
            }
            else {
                self::$SiteName = basename($_GET['sitename'], ".php");
            }
         }
         else {
            self::$SiteName = 'home';
         }
                                  
        //Wenn nicht eine Seite der Footer-Navigation ausgewählt ist, wird der Hauptnavigations-Link ausgegeben.
        foreach (self::$MainNaviArray as $naviRow) {                        
            if ($naviRow['sitename'] !== $lastLink) {
                echo '
      <li class="norm">';
            } 
            else {
                echo '
      <li>';
            }  
                               
            //Wenn die Seite nicht ausgewählt ist.
            if ($naviRow['sitename'] !== self::$SiteName) {
                echo '<a href="'.SERVER_ROOT_PATH.'/'.$naviRow['sitename'].'/">'.$naviRow['linktext'].'</a></li>';                 
            }
            
            //Wenn die Seite ausgewählt ist.
            else {
                echo '<a class="selected">'.$naviRow['linktext'].'</a></li>';
            }            
        }            
            echo '
        </ul>
      </div>'; 
    }                                    
    
    
    public static function printSubNavi()
    {
        echo '
          <div id="container">';
        
        if ("impressum" !== self::$SiteName AND
            "sitemap"   !== self::$SiteName) {         
        
            echo '          
            <div id="navi_u_box">
              <div id="n_top">
              </div>  
              <div class="navi_u">
                <div id="navi_u">
                  <ul>';
                
           //Inhalt der GET-Variable überprüfen
            if (isset($_GET['subnav'])) {
                if (Security::checkWhitelistGET($_GET['subnav'], 'subnav') === false) {
                    $_GET['subnav'] = self::$SubNaviArray[self::$SiteName][0]['sitename'];
                } 
                else {
                    $_GET['subnav'] = $_GET['subnav'];
                }            
            }  
            else {
                $_GET['subnav'] = self::$SubNaviArray[self::$SiteName][0]['sitename'];
            } 
            
            foreach (self::$SubNaviArray as $key => $data) {
                if ($key == self::$SiteName) {
                    foreach ($data as $subnaviRow) {
                        if ($subnaviRow['sitename'] == $_GET['subnav']) {
                            echo '
                 <li><a class="selected" href="'.SERVER_ROOT_PATH.'/'.self::$SiteName.'/'.$subnaviRow['sitename'].'/">'.$subnaviRow['linktext'].'</a></li>';                    
                        }
                        else {
                            echo '
                 <li><a href="'.SERVER_ROOT_PATH.'/'.self::$SiteName.'/'.$subnaviRow['sitename'].'/">'.$subnaviRow['linktext'].'</a></li>';                                         
                        }
                    }
                }            
            }            
            
            echo '
                  </ul>
                </div>
              </div>
              <div id="n_bottom"><img src="images/navi_b.png" alt="" />
              </div>
            </div>';
        }
    }
         
    
   /**
     * Ausgabe der Footernavigation
     *   
     * Das Array '$FooNaviArray' durchläuft die foreach-Schleife, die überprüft, ob der
     * jeweilige Link mit der ausgewählten Seite übereinstimmt. Bei positivem Ergebnis wird der
     * Link als 'selected' markiert und per CSS hervorgehoben.
     * 
     */    
    public static function printFooNavi()
    {
        echo '
        <div id="footer">
          <span class="foo_l">Естественная Медицина © ';
                
           
        //Jahreszahlen für copyright dynamisch generieren
        $startYear = "2009";
        if($startYear < date("Y")) {
            echo $startYear, date(" - Y");
        }
        else {
            echo $startYear;
        }      
        echo '
          &nbsp;|&nbsp; Тел.: (495) 984-69-77
          </span>
          <div class="foo_r">';        
                                      
        //Ausgabe der einzelnen Links mittels Array $FooNaviArray.                                               
        foreach (self::$FooNaviArray as $naviRow) {
            if ($naviRow['sitename'] !== basename($_SERVER['PHP_SELF'], '.php')) {
                echo '<a href="'.SERVER_ROOT_PATH.'/'.$naviRow['sitename'].'/">'.$naviRow['linktext'].'</a>';         
            }        
            else {
                echo '<a class="selected">'.$naviRow['linktext'].'</a>';               
            }
            if ($naviRow['sitename'] !== 'links') {
                echo '&nbsp;|&nbsp;';
            }              
        }
        echo '
          </div>
        </div>';
    }
    
}    

?>