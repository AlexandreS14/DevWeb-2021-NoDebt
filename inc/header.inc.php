<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?= $title; ?></title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
</head>
<body>
<header>
    <a href="index.php" class="logo">NoDebt</a>
    <nav>
        <ul class="menu">
            <li><a href="groupes.php"><i class="fas fa-users"></i>Groupes</a></li>
            <li><a href="contacter.php"><i class="far fa-comment-alt"></i>Contact</a></li>
            <?php
            if (isset($_SESSION['uid'])): ?>
                <li><a href="editerProfil.php"><i class="fas fa-user-circle"></i><?php echo $_SESSION['nom'].' '.$_SESSION['prenom'] ?></a></li>
                <li><a href="inc/deconnexion.inc.php">Se déconnecter</a></li>
            <?php else: ?>
                <li><a href="index.php"><i class="fas fa-sign-in-alt"></i>Se connecter</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>