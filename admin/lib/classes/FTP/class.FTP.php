<?php

class FTP 
{ 

    //Aktueller FTP-Verbindungs-Stream
    public $FTPConn = null;
  
  
    /**
     * Herstellen der FTP-Verbindung beim Instanzieren des Objekts.
     * 
     * @param string $server:    FTP-Server
     * @param string $user:      FTP-Benutzername
     * @param string $password:  FTP-Passwort
     */
    public function __construct($server, $user, $password)
    {
        $ftpConn  = ftp_connect($server);
        
        //Der aktuelle FTP-Verbindungs-Stream wird in die Variable $FTPConn geschrieben,
        //um ständig auf ihn zugreifen zu können.
        $this->FTPConn = $ftpConn;
        
        $loginRes = ftp_login($ftpConn, $user, $password);
        
        if (!$ftpConn or !$loginRes) {
            echo "Could'nt connect with the FTP-Server.";
        }
    }
    
    /**
     * Schliessen der FTP-Verbindung bei Löschen des Objekts
     */
    public function __destruct()
    {
        ftp_close($this->FTPConn);
    }

}

?>