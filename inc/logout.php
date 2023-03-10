<?php
    require_once("Players.php"); 
    require_once("Players.php"); 

    if(isset($_SESSION["player"])) {
        $player->re_login();
        //déconnexion
        $player->disconnect();
    }
?>
