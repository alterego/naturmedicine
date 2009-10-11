<?php

class DateTimeFunc 
{
  
    /**
     * Umwandeln deutschen Datums und Uhrzeit in Format 'datetime' 
     * 
     * @param string $date: Übergebenes deutsches Datum nach folgendem Muster: 24.12.2009
     * @param string $time: Übergebene Uhrzeit nach folgendem Muster: 20:30
     */
    public function makeDatetime($date, $time)
    {
        if ($date == '') {
            $date = '00.00.0000';
        }
        if ($time == '') {
            $time = '00:00';
        }        
        
        $arrayDate = explode('.', trim($date)); //z.B.24.12.2009
        $time      = trim($time).':00'; //z.B.20:30
        $datetime  = $arrayDate[2].'-'.$arrayDate[1].'-'.$arrayDate[0].' '.$time; //2009-12-24 20:30:00 
        return $datetime;       
    }
  
}


?>