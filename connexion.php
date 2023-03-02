<?php
    require_once("inc/Players.php"); 
    $player = Players::get_instance();
    if(isset($_SESSION["player"])) {
        $login = $_SESSION["player"]->login;
        $password = $_SESSION["player"]->password;
        //reconnexion avec les donnes stocker dans la session player quand creer dans la method player->connect() qu'on a appeler la 1er fois sur la page connexion.php 
        $player->connect($login, $password);

    }
    //redirection
    $player->redirect_if_is_connected();
?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once("inc/head.php"); ?>
    <div class="container">
        <main class="main">
            <a class="left-lien custom-btn" href="index.php">Acceuil</a>
            <section class="content flex-r">
                <form action="inc/b_connexion.php" method="post" class="form">
                    <?php echo Model::print_err("identifierErr"); ?>

                    <input type="text" name="login" id="login" class="inp" placeholder="Login" minlength="3" required>
                    <?php echo Model::print_err("loginErr"); ?>
                    
                    <input type="password" name="password" id="password" class="inp" placeholder="Password" minlength="8" required>
                    <?php echo Model::print_err("passwordErr"); ?>

                    <input type="submit" value="connexion" class='inp'>
                    <p>Voulez-vous inscrit ? <a href="inscription.php">click-ici</a> </p>                
                </form>
            </section>
        </main>
    </div>
</body>
</html>

<?php
    Model::delete_err_session();