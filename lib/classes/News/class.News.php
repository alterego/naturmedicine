<?php

class News
{

    //Datenbankverbindungsobjekt
    private $DB = null;
   
   
    //Globales Datenbankobjekt einbinden
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
    }


    /**
     * Ausgabe der einzelnen News-Einträge (Aktuelle Konzerte)
     * 
     * @param int $numEntries:  Anzahl der anzuzeigenden Einträge
     */
    public function printFieldNews($numEntries)
    {
        echo '
		  <div class="news_t">Aktuell
		  </div>
          <div id="news">';
            
        //Die aktuellsten drei Konzert-Einträge aus Datenbank holen        
        $sql    = "SELECT * FROM konzerte WHERE timestamp > '".date('Y-m-d H:i:s')."' ORDER BY timestamp ASC LIMIT ".$numEntries."";
        $result = $this->DB->query($sql, false);
        
        //Wenn keine kommenden Konzerte in Datenbank, Ausgabe von Alternativ-Text
        if ($result->num_rows == 0) {
            echo '<p class="keine_news">Zur Zeit sind keine Einträge vorhanden.<br /><br />
                 Möchten Sie unkompliziert über die kommenden Konzerte informiert werden, können Sie den <a class="bold" href="'.URL_RSS_KONZERTE.'">&#8594; RSS-Feed</a> abonnieren.</p>';
        }
        else {
          
            //Die Einträge ausgeben
            while($entry = $result->fetch_assoc()) {
                echo '
              <a href="'.SERVER_ROOT_PATH.'/konzerte/#n'.$entry['id'].'">
                <div class="time">'.$entry['date'].'</div><div class="location">'.htmlspecialchars($entry['location']).'</div>
	                <p>'.htmlspecialchars($entry['place']).', '.$entry['time'].'</p>
                  <p>'.htmlspecialchars($entry['newstext']).'</p>

              </a>
              <div class="strich"></div>';
            } 
        }
        echo '
                <div id="rss"><a href="'.URL_RSS_KONZERTE.'">
           <div id="rss_icon"><img src="'.SERVER_ROOT_PATH.'/images/feed_icon.png" /></div>&nbsp;&nbsp;RSS-Feed f&uuml;r Konzerte</a>
        </div>
		  </div>
		  
      <div class="news_b">             
      </div>';
    }
    
}

?>
