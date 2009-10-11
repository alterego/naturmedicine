<?php

class RSS
{
  
    private $DB = null;
    
    private $XML = null;
    
    private $Channel = null;
    
    
    
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
        
        //XML-Struktur-Datei einlesen
        $this->XML = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"/>');
        
        //RSS-Header ausgeben
        $this->printHeader();
        
        //RSS-Einträge hinzufügen
        $this->printEntries();
        
        //Ausgeben der gesamten XML-Datei
        echo html_entity_decode($this->XML->asXML(), ENT_COMPAT, "UTF-8");
    }
    
    
    private function printHeader()
    {
        //Channel hinzufügen
        $this->Channel = $this->XML->addChild('channel');
        
        //Feedtype ausgeben
        header("Content-Type: application/rss+xml");
        
        //XML-Struktur erzeugen
        $this->Channel->addChild('title', RSS_TITLE);
        $this->Channel->addChild('link', RSS_LINK);
        $this->Channel->addChild('description', RSS_DESCRIPTION);
        $this->Channel->addChild('language', RSS_LANGUAGE);
        $this->Channel->addChild('lastBuildDate', date("D, j M Y H:i:s O"));        
    }
    
    private function printEntries()
    {
        //Einträge des Feeds auslesen (Datenbanktabelle 'konzerte')
        $sql    = "SELECT * FROM konzerte WHERE timestamp > '".date('Y-m-d H:i:s')."' ORDER BY timestamp"; 
        $result = $this->DB->query($sql, false);       
        
        while ($entry = $result->fetch_assoc()) {
            $item = $this->Channel->addChild('item');
            $item->addChild('title', $entry['location'].', '.$entry['date']);
            $item->addChild('description', $entry['place'].', '.$entry['time'].' Uhr <![CDATA[<br />'.$entry['program'].']]>');            
            $item->addChild('link', SERVER_ROOT_PATH.'/konzerte.php#n'.$entry['id']);
            $item->addChild('pubDate', $entry['pubdate']);
        }
    }
  
}

?>