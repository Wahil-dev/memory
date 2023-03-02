<?php
    require_once("inc/Ranking.php"); 
    require_once("inc/Players.php");     
    
    if(isset($_SESSION["player"])) {
        $login = $_SESSION["player"]->login;
        $password = $_SESSION["player"]->password;
        //reconnexion avec les donnes stocker dans la session player quand creer dans la method player->connect() qu'on a appeler la 1er fois sur la page connexion.php 
        $player->connect($login, $password);

        var_dump($player->get_properties());
    }

?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once("inc/header.php"); ?>
    <div class="container">
        <main class="main">
            <?php $ranking->display_best_ten_player();?>
        </main>
    </div>
</body>
</html>