<?php
    require_once("inc/Players.php"); 
    if(isset($_SESSION["player"])) {
        $player->update_local_data($_SESSION["player"]);
    }
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/mQuiry.css">
</head>
<body>
    <header>
        <?php if($player->is_connected()): ?>
            <ul class="menu flex-r">
                <li><a href="index.php"><?php echo $player->get_login()?></a></li>
                <li><a href="index.php">acceuil</a></li>
                <li><a href="connexion.php">game</a></li>
                <li><a href="classement.php">classement</a></li>
                <li><a href="inc/logout.php">déconnexion</a></li>
            </ul>
        <?php else :?>
            <ul class="menu flex-r">
                <li><a href="index.php">acceuil</a></li>
                <li><a href="connexion.php">connexion</a></li>
                <li><a href="inscription.php">inscription</a></li>
                <li><a href="classement.php">classement</a></li>
            </ul>
        <?php endif ?>
    </header>