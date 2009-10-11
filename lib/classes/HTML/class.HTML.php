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
    <meta name="keywords" content="клиника Естественная медицина классическая гомеопатия врач Виктор Лунин Москва Россия здоровье лечение психолог мануальный терапевт гомеопат акушер-гинеколог Ганеман" />';      
    
        self::printMetaDescription();
    
        echo '
    <meta name="robots" content="index, follow" />
    <meta name="content-language" content="ru" />
    <meta name="publisher" content="AlterEgoWebDesign Zurich" />
    <meta name="date" content="2009" />    
    
    <link rel="shortcut icon" href="'.SERVER_ROOT_PATH.'/favicon.ico.png" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="RSS" href="'.URL_RSS_KONZERTE.'" />';
    
    $browser = explode('(', $_SERVER['HTTP_USER_AGENT']);        
    if (preg_match('/MSIE 6.0/', $browser[1])) {  
        echo '  
    <link rel="stylesheet" type="text/css" href="'.SERVER_ROOT_PATH.'/lib/css/default_ie6.css" />';
    }
    else {
        echo '
    <link rel="stylesheet" type="text/css" href="'.SERVER_ROOT_PATH.'/lib/css/default.css" />';
    }    
    
    echo '
    <link rel="stylesheet" type="text/css" href="'.SERVER_ROOT_PATH.'/lib/css/colours_tiny_mce.css" />
    <link rel="stylesheet" type="text/css" href="'.SERVER_ROOT_PATH.'/lib/css/lightbox.css" />
    <link rel="stylesheet" type="text/css" href="'.SERVER_ROOT_PATH.'/lib/css/niftyCorners.css" />

    <script src="'.SERVER_ROOT_PATH.'/lib/js/default.js" type="text/javascript"></script>
    
    <script src="'.SERVER_ROOT_PATH.'/lib/js/lightbox/js/prototype.js" type="text/javascript"></script>
    <script src="'.SERVER_ROOT_PATH.'/lib/js/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
    <script src="'.SERVER_ROOT_PATH.'/lib/js/lightbox/js/lightbox.js" type="text/javascript"></script> 
       
    <script src="'.SERVER_ROOT_PATH.'/lib/js/flowplayer-3.1.1.min.js" type="text/javascript"></script> 
    
    <script src="'.SERVER_ROOT_PATH.'/lib/js/niftycube.js" type="text/javascript"></script>

    <script type="text/javascript">
      window.onload=function(){
        Nifty("div.news_t","top");
      }
    </script>    
      
    <!--[if IE 6]>
    <script src="'.SERVER_ROOT_PATH.'/lib/js/DD_belatedPNG.js"></script>
    <script>
        DD_belatedPNG.fix(".png_bg, #navi, #navi_u, #home, #articles, #doctors, #patients, #books, #links, #contact, img");
    </script>
    <![endif]--> ';
    }             
  
  
    /**
     * Html-Body Output
     * 
     * The closing </head>-tag and the beginning Body-part with all invariable sitecode is issued.
     */
    public static function printBody()
    {
        echo '
  </head>
  <body>
  
    <div id="main">
    
      <div id="accesskeys_list">
        <h1>Навигация accesskeys согласно британскому стандарту</h1>
        <p><a accesskey="с" title="Пропуская главное меню к содержанию" href="#content">для содержания используйте с</a></p>
        <p><a accesskey="0" title="Главное меню" href="#navi">для главного меню используйте 0</a></p>
        <p><a accesskey="1" title="Главная страница" href="index.php">для главной страницы используйте 1</a></p>
        <p><a accesskey="3" title="Карта сайта" href="sitemap.php">для карты сайта используйте 3</a></p>
        <p><a accesskey="9" title="Контакт" href="contact.php">для страницы контакта используйте 9</a></p>
      </div>    
    
      <div id="logo_container">
        <div id="logo"><img src="'.SERVER_ROOT_PATH.'/images/logo.png" alt="естественная медицина - путь к вашему здоровью"  />
        </div>
      </div>';
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
                </div>';
        Navi::printFooNavi();
        
        echo '
        </div>   
  </body>
</html>';
    }
    
    
    public static function printMetaDescription()
    {
        if (isset($_GET['sitename']) AND is_string($_GET['sitename'])) {
            $siteName = $_GET['sitename'];
        }
        else {
            $siteName = 'home';
        }
        
        switch ($siteName) {
          
            case 'home':   

                $metaDes = "Клиника «Естественная медицина» является объединением высоко квалифицированных специалистов - гомеопатов, акушеров-геникологов, психологов, неврологов и мануальных терапевтов - под руководством основателя клиники, доктора Виктора Лунина.";                 
                
                break;
                
            case 'patients':   

                $metaDes = "Страница для пациентов и интересующихся вопросами естественной медицины, здорового орбраза жизни, гомеопатии содержащая ответы на часто задаваемые в связи с этим вопросы, общие рекомендации и объявления.";                
                
                break;    
                
            case 'doctors':   

                $metaDes = "Страница содержащая информацию для врачей о предстоящих и прошедщих семинарах, клинических разборах и объявлениях.";
                
                break;  
                
            case 'articles':   

                $metaDes = "Подборка статетей, так или иначе связанных с темами гомеопатия и здоровье, рекомендуемых клиникой «Естественная медицина».";
                
                break;  
                
            case 'books':   

                $metaDes = "Описание книг рекомендуемых нашими специалистами, которые Вы можете преобрести в клинике «Естественная медицина»"; 
                
                break;  
                
            case 'links':   

                $metaDes = "Здесь Вы можете найти линки и краткое описание наших коллег и организаций, занимающихся гомеопатией, рекомендуемых клиникой «Естественная медицина»."; 
                
                break;  
                
            case 'contact':   

                $metaDes = "Здесь Вы можете найти всю необходимую контактную информацию: телефон, e-mail, время работы + схему проезда клиники «Естественная медицина»."; 
                
                break;  
                
            case 'impressum':   

                $metaDes = "Здесь Вы можете найти всю информацию о поддержке Accesskeys и браузеров, ответственных за содержание сайта, а также о соответствии техническим стандартам CSS и XHTML сайта клиники «Естественная медицина»."; 
                
                break;  
                
            case 'sitemap':   

                $metaDes = "Здесь Вы можете посмотреть всю структуру сайта и напрямую перейти на любую страницу сайта клиники «Естественная медицина»."; 
                
                break; 
        }

        echo "
    <meta name=\"description\" content=\"".$metaDes."\" />";   
    }
  
}

?>
