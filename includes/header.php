<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? htmlspecialchars($titre) : 'Application QCM' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/qcm/assets/css/style.css">
</head>
<body class="bg-light">

<header class="bg-dark text-white mb-4">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark container">
        <a class="navbar-brand fw-bold" href="/qcm/index.php">Application QCM</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Afficher le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/qcm/index.php">Accueil</a>
                </li>

                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/qcm/profil.php">Mon espace</a>
                    </li>

                    <?php if (isset($_SESSION['utilisateur_role']) && $_SESSION['utilisateur_role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/qcm/admin/index.php">Administration</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/qcm/deconnexion.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/qcm/inscription.php">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/qcm/connexion.php">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<main class="container bg-white p-4 rounded shadow-sm mb-4">