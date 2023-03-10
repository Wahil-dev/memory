<?php
    require_once("inc/Players.php"); 
    require_once("inc/Card.php");

    if(isset($_SESSION["player"])) {
        $player->re_login();

        //calcul_score_game 
        if(isset($_SESSION["win"])) {
            $player->calcul_score_game($_SESSION["even_number_game"]);
        }
    }
    
?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once("inc/header.php"); ?>
    <div class="container">
        <main class="main">
            <section class="content flex-r">
                <?php if(isset($_SESSION["win"])) :?>
                    <h1>Vous etez gagner !</h1>
                    <div class="result-box">
                        <div><b>Name : </b><?= $player->get_login()?></div>
                        <div><b>Score : </b><?= $player->get_score()?></div>
                        <div><b>Rank : </b><?= $player->get_ranking()?></div>
                        <div><b>Best_score : </b><?= $player->get_best_score()?></div>
                        <div><b>Click : </b><?= $player->get_number_of_click()?></div>
                        <div><b>even_number_game : </b><?= $_SESSION["even_number_game"]?></div>
                    </div>
                    <a href="inc/quit_game.php">nouveau game</a>
                <?php else :?>
                    <?php if(!isset($_SESSION["game_started"])) :?>
                        <form action="inc/b_game.php" method="post">
                            <input type="number" name="even_number_game" id="even_number_game" placeholder="even_number_game" min='2' max='10' class="even_number_game" required>

                            <input type="submit" value="start game">
                        </form>
                    <?php else :?>
                        <form action="inc/quit_game.php" method="post">
                            <input type="submit" name="quit_game" value="quit_game">
                        </form>
                        <?php 
                            Card::create_cards_game();
                            Card::draw_card();

                            if(Card::player_win()) {
                                $_SESSION["win"] = true;
                                header("Location: game_home.php");
                                exit();
                            }
                                                        
                            if(isset($_GET["id"])) {
                                //set_click
                                $player->set_click();
                                $id = $_GET["id"];
                                Card::get_card_clicked($id);
                            }
                        ?>
                    <?php endif?>
                <?php endif ;?>
            </section>
        </main>
    </div>
</body>
</html>