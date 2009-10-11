<?php
require_once '../../common.php';

//Wenn in der AJAX-Anfrage gültige GET-Parameter 'area_name' gesetzt sind, wird in der individuellen Nutzerkonfiguration (Datenbank)
//die Einstellungen zur jeweiligen 'area' auf das Gegenteil geändert. ('open' oder 'close')
if (isset($_GET['area_name']) AND 
    $_GET['area_name'] == 'form_title' OR
    $_GET['area_name'] == 'eintraege' OR
    $_GET['area_name'] == 'form_new') {
    
    //Die Area identifizieren
    $areaName = $_GET['area_name'];
    
    $sql    = "SELECT ".$areaName." FROM user_config WHERE login = '".$_SESSION['username']."'";
    $result = $GLOBALS['DB']->query($sql); 
    $entry  = $result->fetch_assoc();  
        
    //In der Datenbank 'user_config' wird die betreffende 'area' entweder auf 'open' oder 'close' gesetzt (Gegenteil des aktuellen Wertes).
    if ($entry[$areaName] == 'open') {        
        $sql = "UPDATE user_config SET ".$areaName." = 'close' WHERE login = '".$_SESSION['username']."'";
        $result = $GLOBALS['DB']->query($sql, false); 
        
        $data = array(
                      'formname' => $areaName,
                      'text'     => 'close'
                  );
        
        header('X-JSON: ('.json_encode($data).')');  
        header('Content-type: application/x-json'); 
        echo json_encode($data);
    }
    else {
        $sql = "UPDATE user_config SET ".$areaName." = 'open' WHERE login = '".$_SESSION['username']."'";
        $result = $GLOBALS['DB']->query($sql, false);     
        
        $data = array(
                      'formname' => $areaName,
                      'text'     => 'open'
                  );
                          
        header('X-JSON: ('.json_encode($data).')');  
        header('Content-type: application/x-json');         
        echo json_encode($data);
    }         
}

?>