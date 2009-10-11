<?php
require_once '../../common.php';

//Wenn in der AJAX-Anfrage der GET-Parameter 'help' auf 'visible' gesetzt ist, wird dies in der individuellen Nutzerkonfiguration (Datenbank)
//vermerkt und die Hilfe eingeblendet. 
if (isset($_GET['help'])) {
    if ($_GET['help'] == 'visible') {    
    
        $sql = "UPDATE user_config SET help = 'visible' WHERE login = '".$_SESSION['username']."'";
        $result = $GLOBALS['DB']->query($sql, false);    
        
        echo '
        <h3>Erste Hilfe<a class="float_r" href="javascript:update(\'scripts/AJAX/hideHelp.php?help=hidden\', \'fixed\');"><img src="images/button_help.png" /></a></h3>
        <div class="e_box">
          <div class="erklaerung">Beim drücken auf das Symbol, geht ein Redaktor auf, in dem man den Eintrag verarbeiten kann.
          </div>
          <div class="but"><img src="images/button_edit.png" alt="" />
          </div>       
        </div>
        <div class="e_box">
          <div class="erklaerung">Beim drücken auf den Symbol, geht ein Redaktor auf, in dem man den Eintrag verarbeiten kann.
          </div>
          <div class="but"><img src="images/button_delete.png" alt="" />
          </div>
        </div>
        <p>Beim drücken auf den Symbol, geht ein Redaktor auf, in dem man den Eintrag verarbeiten kann.Beim drücken auf den Symbol, geht ein Redaktor auf, in dem man den Eintrag verarbeiten kann.Beim drücken auf den Symbol, geht ein Redaktor auf, in dem man den Eintrag verarbeiten kann.Beim drücken auf den Symbol, geht ein Redaktor auf, in dem man den Eintrag verarbeiten kann.</p>';
    }
    
    //Ist der GET-Parameter auf 'hidden' gesetzt, wird dies in der Datenbank vermerkt und die Hilfe ausgeblendet.
    else {
        $sql = "UPDATE user_config SET help = 'hidden' WHERE login = '".$_SESSION['username']."'";
        $result = $GLOBALS['DB']->query($sql, false);
        
        echo '
          <h3>Erste Hilfe<a class="float_r" href="javascript:update(\'scripts/AJAX/hideHelp.php?help=visible\', \'fixed\');"><img src="images/button_help.png" /></a></h3>';
    }  
}

?>