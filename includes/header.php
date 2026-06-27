<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Application QCM</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>Application QCM</h1>

    <nav>
        <a href="index.php">Accueil</a>

        <?php if (isset($_SESSION['utilisateur_id'])): ?>
            <a href="deconnexion.php">Déconnexion</a>
        <?php else: ?>
            <a href="inscription.php">Inscription</a>
            <a href="connexion.php">Connexion</a>
        <?php endif; ?>
    </nav>
</header>

<main>