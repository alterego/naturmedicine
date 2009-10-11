<?php

class HTMLLogin
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
    
    <link rel="stylesheet" type="text/css" href="lib/css/login.css">
        
    <script src="lib/js/default.js" type="text/javascript"></script>
    <script src="lib/js/form_selectall" type="text/javascript"></script>    
    <!--[if IE 6]>
    <script src="lib/js/DD_belatedPNG.js"></script>
    <script>
        DD_belatedPNG.fix(".png_bg, #column, #rss_icon, #content_b");
    </script>
    <![endif]-->';
    }
  
  
    /**
     * Html-Body Output
     * 
     * The closing </head>-tag and the beginning Body-part with all invariable sitecode is issued.
     */
    public static function printBody($css = null, $withConsole = true)
    {
        echo '
  </head>
  <body onload="self.focus(); document.login_form.login.focus()">  
    
    <div id="content">
        
    <div id="logo">
    </div>'."\n".'';        
    }        
    
             
    /**
     * Html-Footer Output
     * 
     * All closing </div>-tags of the invariable Divs in the Body-part and
     * the closing </body>- and </html>-tag is issued.
     */
    public static function printFooter()
    {
        echo '           
        
        </div>

  </body>
</html>';
    }

}

?>