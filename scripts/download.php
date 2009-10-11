<?php

//Dateien zum Download anbieten.
require_once '../settings.php';
require_once '../lib/classes/Security/class.Security.php';

if (isset($_GET['filepath']) AND !empty($_GET['filepath'])) {
  
    $origSite = $_GET['site'];
    if (isset($_GET['subnav']) AND !empty($_GET['subnav'])) {
      
        Security::checkWhitelistGET($_GET['subnav'], 'subnav');       
      
        $origSite .= '?subnav='.$_GET['subnav'];            
        if (isset($_GET['subsubnav']) AND !empty($_GET['subsubnav'])) {
            
            Security::checkWhitelistGET($_GET['subsubnav'], 'subnav');
            
            $origSite .= '&subsubnav='.$_GET['subsubnav'];
        }        
    }
    
    $file = PROJECT_ROOT_PATH.$_GET['filepath'];
            
    if (file_exists($file)) {
      
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.basename($file));
        
        if (isset($_GET['file']) AND !empty($_GET['file'])) {
            switch ($_GET['file']) {
          
            //Falls ein PDF-File
            case 'pdf':   
                            
                header('Content-Type: application/pdf');               
                
                break;
                
            //Falls ein Word-File
            case 'word':   
                            
                header('Content-Type: application/msword');               
                
                break;                
                                
            //Falls ein Image-File
            case 'img':  

                header('Content-Type: application/image');                                                                
                header('Content-Transfer-Encoding: binary');
               
                break;
                
            //Falls eine Audio-Datei (mp3)
            case 'mp3':   

                header('Content-Type: application/force-download');               
               
                break;                
            }
        }

        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '.filesize($file));
        readfile($file);
        exit;
    }  
    else{
      
        header('Location: '.SERVER_ROOT_PATH.'/'.$origSite);
    }
}

?>