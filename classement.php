<?php
    require_once("inc/Ranking.php"); 
    require_once("inc/Players.php"); 

    $ranking = new Ranking();

    if(isset($_SESSION["player"])) {
        $player->update_local_data($_SESSION["player"]);
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