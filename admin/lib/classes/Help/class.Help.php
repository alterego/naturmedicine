<?php

class Help
{
  
    //Lokales Datenbankobjekt
    private $DB = null;
  
  
    /**
     * Einbinden des globalen Datenbankobjekts
     */
    public function __construct()
    {
        $this->DB = $GLOBALS['DB'];
    }
  
    /**
     * Anzeigemanager des Hilfe-Feldes
     * 
     * Das Hilfe-Feld ist je nach Benutzereinstellung (gemäss Datentabelle 'user_config') aus- oder eingeblendet.    
     */
    public function displayHelp()
    {      
      $sql    = "SELECT help FROM user_config WHERE login = '".$_SESSION['username']."'";
      $result = $this->DB->query($sql, false);
      
      $entry = $result->fetch_assoc();
      
          //Ausgabe des Head-Teils des unveränderlichen Hilfe-Feldes
          echo '
      <div id="fixed">';      
      
      if ($entry['help'] == 'visible') {
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
        else {
            echo '
          <h3>Erste Hilfe<a class="float_r" href="javascript:update(\'scripts/AJAX/hideHelp.php?help=visible\', \'fixed\');"><img src="images/button_help.png" /></a></h3>';
        }        
        
        //Ausgabe des Footer-Teils des unveränderlichen Hilfe-Feldes
        echo '
        </div>';        
    }
  
}

?>