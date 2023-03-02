<?php
    require_once("inc/Players.php"); 

    if(isset($_SESSION["player"])) {
        $login = $_SESSION["player"]->login;
        $password = $_SESSION["player"]->password;
        //reconnexion avec les donnes stocker dans la session player quand creer dans la method player->connect() qu'on a appeler la 1er fois sur la page connexion.php 
        $player->connect($login, $password);

        var_dump($player->get_properties());
        echo "<br>";
        echo "click : ".$player->get_number_of_click();
        echo "<br>";
        echo "<br>";
        echo "score : ".$player->get_score();
        echo "<br>";
        $player->set_click();
        echo "<br>";
        $player->update_score(999);
        echo "score : ".$player->get_score();
        echo "<br>";
        echo "<br>";
        echo "best_score : ".$player->get_best_score();
        echo "<br>";
        echo "<br>";
        echo "rank : ".$player->get_ranking();
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