<?php
    require_once("Players.php");
    require_once("Card.php");

    if(isset($_POST["even_number_game"]) && intval($_POST["even_number_game"])) {
        //number of card created = $even_number_game * 2
        $even_number_game = $_POST["even_number_game"];
        $_SESSION["even_number_game"] = $even_number_game;

        //if isset($_SESSION["game_started"]) on afficher les carte (le partie de jeu) sinon on afficher la form ou on entre le $even_number_game = $_POST["even_number_game"]
        $_SESSION["game_started"] = true;

        header("location: ../game_home.php");
        exit();
    }
