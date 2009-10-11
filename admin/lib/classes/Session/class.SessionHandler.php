<?php

class SessionHandler
{    
    
    //Lokales Datenbankobjekt
    private $DB = null;
    
    
    /**
     * Konstruktor
     * 
     * Beim Instanzieren des Objekts werden die Methoden des Session-Handlers auf die Methoden dieser Klasse gesetzt
     * und eine Session gestartet.
     */
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
        
        //Den Session-Handler auf die Methoden dieser Klasse setzen.
        session_set_save_handler(array ($this, '_open'),
                                 array ($this, '_close'),
                                 array ($this, '_read'),
                                 array ($this, '_write'),
                                 array ($this, '_destroy'),
                                 array ($this, '_gc'));
         
         //Session starten
         session_start();
         
         //Zuerst Session-Daten speichern, dann Objekte zerstören. (Ab PHP 5.0.5)
         register_shutdown_function('session_write_close');
    }
    
    /**
     * Open-Methode
     *  
     * @param string $path:  PHP regelt die Zuordnung selber (datenbankbasiert, deshalb nicht nötig)
     * @param string $name:  PHP regelt die Zuordnung selber
     */
    public function _open($path, $name)
    {
        $this->_gc(0);                 
        return true;
    }
    
    /**
     * Close-Methode
     * 
     * Der Garbage Collector wird aufgerufen und löscht die abgelaufenen Sessions
     */
    public function _close()
    {
        //Den Garbage Collector aufrufen
        $this->_gc(0);
        return true;
    }
    
    /**
     * Read-Methode 
     * 
     * Liest die Session-Variablen aus der Datenbank
     * 
     * @param string $sesID:  Die aktuelle Session-ID
     */
    public function _read($sesID)
    {                
        $sessionStatement = "SELECT * FROM sessions WHERE id = '".$sesID."'";
        $result           = $this->DB->query($sessionStatement);
        
        if ($result === false) {
            return '';
        }

        $result = $result->fetch_assoc();
        
        if (count($result) > 0) {
            return $result['value'];
        }
        else {
            return '';
        }
    }
    
    /**
     * Write-Methode
     *  
     * Aktualisiert eine Session oder beginnt eine Session und 
     * schreibt die Werte der Session-Variablen in die Datenbank
     *  
     * @param string $sesID:  Die aktuelle Session-ID
     * @param string $data:   Die Session-Variablen und ihre Werte
     */
    public function _write($sesID, $data)
    {             
        //Nur schreiben, wenn Daten übergeben wurden.
        if ($data == null) {                  
            return true;
        }       
                
        //Eine bestehende Sitzung aktualisieren
        $sessionStatement = "UPDATE sessions SET lastUpdated = '".time()."', value = '".$data."' WHERE id = '".$sesID."'";
        $result           = $this->DB->query($sessionStatement);
        
        if ($result === false) {
            return false;
        }
        if ($this->DB->MySQLiObj->affected_rows) {
            
            //Bestehende Session wurde aktualisiert
            return true;
        }
        
        //Die Session existiert noch nicht und wird angelegt.
        $sessionStatement = "INSERT INTO sessions (id, lastUpdated, start, value) VALUES ('".$sesID."', '".time()."', '".time()."', '".$data."')";
        $result = $this->DB->query($sessionStatement);
        
        return $result;
    }
    
    /**
     * Destroy-Methode
     *  
     * Löscht eine komplette Session
     *  
     * @param string $sesID:  Die aktuelle Session-ID
     */
    public function _destroy($sesID)
    {
        $sessionStatement = "DELETE FROM sessions WHERE id = '".$sesID."'";
        $result           = $this->DB->query($sessionStatement);
        
        return true;
    }
    
    /**
     * GC-Methode
     * 
     * Löschen der abgelaufenen Sessions
     * 
     * @param string $life:  
     */
    public function _gc($life)
    {
        //Zeitraum, nach der eine Session als abgelaufen gilt.
        $sessionLife      = strtotime("-15 minutes");
        $sessionStatement = "DELETE FROM sessions WHERE lastUpdated < '".$sessionLife."'";
        $result           = $this->DB->query($sessionStatement);
        
        //Wenn der Löschvorgang erfolgreich war, wird das im Session-Objekt der Klasse Security vermerkt,
        //um eine Meldung beim Login-Fenster ausgegeben zu können.  
        if ($this->DB->MySQLiObj->affected_rows > 0) {
           $GLOBALS['SECURITY']->SessionExpired = true;
        }       
        
        return true;
    }
    
}

?>