<?php
    require_once("Players.php"); 
    if(isset($_SESSION["player"])) {
        $player->update_local_data($_SESSION["player"]);
    }
    //déconnexion
    $player->disconnect();
?>
