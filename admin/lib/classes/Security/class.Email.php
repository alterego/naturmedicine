<?php

class Email 
{
  
    /**
     * Umwandlung der E-Mail-Adresse in ASCII-Zeichen
     * 
     * @param string $mailAdress:  Die umzuwandelnde E-Mail-Adresse
     */
    public function crypteEmail($mailAdress)
    {
        $return = '';
        $len    = strlen($mailAdress);
        for ($x = 0; $x < $len; $x++) {
            $ord = ord(substr($mailAdress, $x, 1));
            $return .= '&#'.$ord.';';
        }
        return $return;
    }

}

?>