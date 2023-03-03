<?php
    require_once("inc/Players.php"); 
    require_once("inc/Card.php");

    if(isset($_SESSION["player"])) {
        $player->re_login();
    }
?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once("inc/header.php"); ?>
    <div class="container">
        <main class="main">
            <section class="content flex-r">
                <?php if(!isset($_SESSION["game_started"])) :?>
                    <form action="inc/b_game.php" method="post">
                        <input type="number" name="even_number_game" id="even_number_game" placeholder="even_number_game" min='2' max='12' class="even_number_game" required>

                        <input type="submit" value="start game">
                    </form>
                <?php else :?>
                    <form action="inc/quit_game.php" method="post">
                        <input type="submit" name="quit_game" value="quit_game">
                    </form>
                    <?php 
                        Card::create_cards_game();

                        Card::draw_card();
                        


                        if(isset($_GET["name"])) {
                            $name = $_GET["name"];
                            Card::get_card_clicked($name);
                            header("Location: game_home.php");
                            exit();
                        }

                        foreach(Card::get_list_of_cards() as $card) {
                            echo "<br>";
                            print_r($card);
                        }
                    ?>
                <?php endif?>
            </section>
        </main>
    </div>
</body>
</html>