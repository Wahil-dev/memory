<?php
    require_once("Players.php");
    require_once("Model.php");

    $login = $password = $cPassword = "";
    $loginErr = $passwordErr = $cPasswordErr = "";

    //Login validation
    if(isset($_POST["login"]) && !empty($_POST["login"])) {
        $login = Model::process_input($_POST["login"]);
        if(strlen($_POST["login"]) < 3) {
            $loginErr = "3 caractère au min !";
        } else {
            if($player->is_exist($login)) {
                $loginErr = "Login déja utiliser !";
            }
        }
    } else {
        $loginErr = "login requise !";
    }

    //Password validation
    if(isset($_POST["password"]) && !empty($_POST["password"])) {
        $password = Model::process_input($_POST["password"]);
        if(!Model::is_valid($password)) {
            $passwordErr = "Confirmer que le mote de passe contient :<br> au moins 1 
                caractère en majuscule, en minuscule, un muméro, caractère 
                spéciaux, 8 caractère au min, 255 ou max !";
        }
    } else {
        $passwordErr = "mote de pass requise !";
    }

    //Confirm password validation
    if(isset($_POST["cPassword"]) && !empty($_POST["cPassword"])) {
        $cPassword = Model::process_input($_POST["cPassword"]);
        if($cPassword !== $password) {
            $cPasswordErr = "confirm password not equal password !";
        }
    } else {
        $cPasswordErr = "confirmation mote de pass requise !";
    }

    if(empty($loginErr) && empty($passwordErr) && empty($cPasswordErr)) {
        $player->register($login, $password);
        Model::display_message("vous etés bien inscrit");
        header("refresh: .1; url=../connexion.php");
        exit();
    } else {
        !empty($loginErr) ? $_SESSION["loginErr"] = $loginErr : '';
        !empty($passwordErr) ? $_SESSION["passwordErr"] = $passwordErr  : '';
        !empty($cPasswordErr) ? $_SESSION["cPasswordErr"] = $cPasswordErr : '';

        header("location: ../inscription.php");
        exit();
    }