<?php
    require_once("inc/Players.php"); 
    if(isset($_SESSION["player"])) {
        $player->update_local_data($_SESSION["player"]);
    }
    
    //redirection
    $player->redirect_if_is_connected();
?>

<!DOCTYPE html>
<html lang="fr-FR">
<?php  require_once("inc/head.php"); ?>

    <div class="container">
        <main class="main">
            <a class="left-lien custom-btn" href="index.php">Acceuil</a>
            <section class="content flex-r">
                <form action="inc/b_inscription.php" method="post" class="form">
                    <input type="text" name="login" id="login" class="inp" placeholder="Login" minlength="3" required>
                    <?php echo Model::print_err("loginErr"); ?>
                    
                    <input type="password" name="password" id="password" class="inp" placeholder="Password" minlength="8" required>
                    <?php echo Model::print_err("passwordErr"); ?>

                    <input type="password" name="cPassword" id="cPassword" class="inp" placeholder="confirm password" minlength="8" required>
                    <?php echo Model::print_err("cPasswordErr"); ?>

                    <input type="submit" value="inscription" class='inp'>

                    <p>étez-vous déja inscrit ? <a href="connexion.php">click-ici</a> </p>                
                </form>
            </section>
        </main>
    </div>
</body>
</html>

<?php
    Model::delete_err_session();