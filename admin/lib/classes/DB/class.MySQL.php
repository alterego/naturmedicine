<?php

class MySQL
{
  
    //Datenbankverbindungsobjekt 
    public $MySQLiObj = null;
  
    //Letzte SQL-Abfrage
    public $lastSQLQuery = null;
  
    //Status der letzten Anfrage 
    public $lastSQLStatus = null;
    
  
    /**
     * Beim Instanzieren eines Objekts wird eine Datenbankverbindung hergestellt und die Übertragung auf UTF-8 gesetzt.
     *      
     * @param string $server:    MySQL-Server
     * @param string $user:      MySQL-Benutzername     
     * @param string $password:  MySQL-Passwort
     * @param string $db:        Die auszuwählende Datenbank
     */
    public function __construct($server, $user, $password, $db)
    {
        $this->MySQLiObj = new mysqli($server, $user, $password, $db);
        if (mysqli_connect_errno()) {
            echo "Konnte keine Verbindung zum Datenbank-Server herstellen.";
            trigger_error("MySQL-Connection-Error", E_USER_ERROR);
            die();
        }
        $this->MySQLiObj->query("SET NAMES utf8");
    }
    
    /**
     * Beim Löschen des Objekts wird die Datenbankverbindung geschlossen.
     */
    public function __destruct()
    {
        $this->MySQLiObj->close();
    }

    /**
     * Erweitert die mysqli-Methode 'query' um eine Log-Methode.
     * 
     * @param string $sqlQuery:  SQL-Anfrage
     */
    public function query($sqlQuery, $doLog = true)
    {
        $this->lastSQLQuery = $sqlQuery;
        
        //Wenn nicht explizit verneint, wird die SQL-Aktion ins Log-File geschrieben.
        if ($doLog !== false) {
            $this->doLog($sqlQuery);            
        }
        
        $result = $this->MySQLiObj->query($sqlQuery);
        
        //Fehler im SQL-Query
        if ($result === false) {
            $this->lastSQLStatus = false;
            
//            echo 'Fehler in SQL-Query.';
        }
        
        //SQL-Query erfolgreich
        else {
            $this->lastSQLStatus = true;
        }
        return $result;
    }

    
    public function lastSQLError()
    {
        return $this->MySQLiObj->error;
    }
    
    /**
     * Log-Methode der erweiterten Query-Methode.
     * 
     * Für jeden Monat wird eine Log-Datei erstellt.
     * Jede SQL-Anfrage wird protokolliert mit SQL-Anfrage, Zeitpunkt und Urheberskript.
     * 
     * @param string $sqlQuery:  SQL-Anfrage
     */
    private function doLog($sqlQuery) {
        //Datei zum Schreiben bestimmen
        $logfile = PROJECT_DOCUMENT_ROOT.'/log/DB/dbEvents'.date('m_Y').'.txt';        
        
        //Lokale Variable an globales FTP-Objekt anbinden
        $FTP = $GLOBALS['FTP'];        
        
        ftp_chmod($FTP->FTPConn, 0757, PROJECT_DOCUMENT_ROOT.'/log/DB');              
        
        //String bereitstellen inkl. escapen
        $data = date("d.m.Y H:i:s", time()).' : '.$sqlQuery.' ['.$_SERVER['SCRIPT_FILENAME'].']'."\n";                     
    
        if (!file_put_contents($logfile, $data, FILE_APPEND)) {
            echo "Daten konnten nicht in Datei geschrieben werden.";
        }   
        
        ftp_chmod($FTP->FTPConn, 0750, PROJECT_DOCUMENT_ROOT.'/log/DB');                               
    }
}

?>