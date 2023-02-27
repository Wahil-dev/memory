<!DOCTYPE html>
<html lang="fr-FR">
<?php require_once("inc/head.php"); ?>
    <div class="container">
        <main class="main">
            <a class="left-lien custom-btn" href="index.php">Acceuil</a>
            <section class="content flex-r">
                <form action="inc/b_conn.php" method="post" class="form">
                    <input type="text" name="login" id="login" class="inp" placeholder="Login">

                    <input type="password" name="password" id="password" class="inp" placeholder="Password">

                    <input type="submit" value="connexion" class='inp'>

                    <p>Voulez-vous inscrit ? <a href="inscription.php">click-ici</a> </p>                
                </form>
            </section>
        </main>
    </div>
</body>
</html>
