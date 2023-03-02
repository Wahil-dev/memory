<?php
    require_once("inc/Players.php"); 
    if(isset($_SESSION["player"])) {
        $player->update_local_data($_SESSION["player"]);

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