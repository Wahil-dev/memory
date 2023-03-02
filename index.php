<?php
    require_once("inc/Players.php"); 
    if(isset($_SESSION["player"])) {
        $player->update_local_data($_SESSION["player"]);

        var_dump($player->get_properties());
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo $player->get_my_rank()." = get_my_rank";
        echo "<br>";
        echo $player->get_best_score()." = get_best_score";
        echo $player->get_score()." = get_score";
        echo "<br>";
        echo $player->get_number_of_click()." = get_click";
        echo "<br>";
        echo $player->set_click()." = set_click";
        echo "<br>";
        echo $player->get_number_of_click()." = get_click";
        echo "<br>";
        echo $player->get_score()." = get_score";
        echo "<br>";
        //echo $player->set_score(6)." = set_score($new_score)";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
    }
    
?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once("inc/header.php"); ?>
    <div class="container">
        <main class="main">
            <section class="content">
                <h1 class="title">Présentation</h1>
                <article class="">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem velit quaerat error esse, optio harum dolor sequi ad expedita aliquid provident sint corrupti debitis accusamus omnis totam quibusdam corporis, consectetur fugiat. Aspernatur ab itaque nesciunt quis officia maiores eum, aperiam libero. Suscipit tempora iste aspernatur necessitatibus quidem non asperiores modi.</p>
                </article>
            </section>
        </main>
    </div>
</body>
</html>