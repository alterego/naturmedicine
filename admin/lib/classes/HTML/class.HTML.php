<?php

class HTML
{      
   
    /**
     * Html-Header Output
     * 
     * The full Head-part until but without the closing </head>-tag is issued.
     */
    public static function printHeader()
    {
        echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

  <head>
    <title>'.HTML_TITLE.'</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">    
        
    <link rel="stylesheet" type="text/css" href="lib/css/default.css">
    <link rel="stylesheet" type="text/css" href="../lib/css/colours_tiny_mce.css">
    <link rel="stylesheet" type="text/css" href="lib/css/niftyCorners.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="lib/css/lightbox.css" media="screen" />    
       
    <script src="lib/js/prototype.js" type="text/javascript"></script>
    <script src="lib/js/scriptaculous.js" type="text/javascript"></script>          
        
     <!-- JS für die Lightbox -->
    <script type="text/javascript" src="extLibs/lightbox/js/prototype.js"></script>
    <script type="text/javascript" src="extLibs/lightbox/js/scriptaculous.js?load=effects,builder"></script>
    <script type="text/javascript" src="extLibs/lightbox/js/lightbox.js"></script>    
    
    <script type="text/javascript" src="lib/js/niftycube.js"></script>
    <script type="text/javascript">
      window.onload=function(){
    	  Nifty("h2", "small top");
    	  Nifty("div.viewimage", "small");
      }
    </script> 
             
    <script src="lib/js/form_selectall.js" type="text/javascript"></script>    
    <!--<script src="lib/js/resetForm.js" type="text/javascript"></script>-->    
    <script src="scripts/AJAX/classes/updateContainer.js" type="text/javascript"></script>    
    <script src="scripts/AJAX/classes/ajaxRequest.js" type="text/javascript"></script> 
    
        <script type="text/javascript">
      resetForm(){
        window.scrollTo(0,120);
      }
    </script>    
    
    <!--[if IE 6]>
    <script src="lib/js/DD_belatedPNG.js"></script>
    <script>
        DD_belatedPNG.fix("img");
    </script>
    <![endif]-->
    
    <script type="text/javascript" src="extLibs/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="extLibs/tiny_mce/tiny_mce_basic_init.js"></script>
    <script type="text/javascript" src="lib/js/tinyMCEsettings.js"></script>';    
    }  
  
    /**
     * Html-Body Output
     * 
     * The closing </head>-tag and the beginning Body-part with all invariable sitecode is issued.
     */
    public static function printBody($css = null)
    {
        echo '
  </head>
  <body>         
    
    <div id="container">';
	  
      //Benutzerdefinierte Ausgabe des Hilfe-Feldes
//      $Help = new Help();
//      $Help->displayHelp(); 
      
    }         
    
             
    /**
     * Html-Footer Output
     * 
     * All closing </div>-tags of the invariable Divs in the Body-part and
     * the closing </body>- and </html>-tag is issued.
     */
    public static function printFooter()
    {
        echo "\n".'           
      </div>
  	
    </div>
      
    <div id="cleaner">
    </div>

  </body>
</html>';
    }  
  
}

?>
