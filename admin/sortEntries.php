<?php

require_once 'common.php';       

//Der übergebene Name der aktuell verwendeten Datenbank-Tabelle wird in der Variable gespeichert.
$tableName = $_POST['tablename'];

//Die veränderte Reihenfolge der Datenbankeinträge wird in ein Array umgewandelt.
parse_str($_POST['data']);

//Jeder Datenbankeintrag wird auf die aktuelle Position in der Reihenfolge upgedatet ('sort_id' wird neu gesetzt).
for ($i = 0; $i < count($eintraege); $i++) {
    $sql    = "UPDATE ".$tableName." SET sort_id = '".$i."' WHERE id = '".$eintraege[$i]."'";
    $result = $GLOBALS['DB']->query($sql, true);    
}

?>