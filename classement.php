<?php
    require_once("inc/Players.php");     
    
    if(isset($_SESSION["player"])) {
        //Pour la reconnexion
        $player->re_login();
    }
?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once("inc/header.php"); ?>
    <div class="container">
        <main class="main">
            <?php $player->display_best_ten_player();?>
        </main>
    </div>
</body>
</html>