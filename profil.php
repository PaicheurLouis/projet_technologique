<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$titre = "Mon espace";
require_once 'includes/header.php';
?>

<h1>Mon espace</h1>

<p>Bienvenue sur votre espace utilisateur.</p>

<p>
    <a href="qcm.php">Lancer un QCM</a>
</p>

<p>
    <a href="historique.php">Voir mon historique</a>
</p>

<?php if (isset($_SESSION['utilisateur_role']) && $_SESSION['utilisateur_role'] === 'admin'): ?>
    <p>
        <a href="admin/index.php">Accéder à l'administration</a>
    </p>
<?php endif; ?>

<p>
    <a href="deconnexion.php">Se déconnecter</a>
</p>

<?php
require_once 'includes/footer.php';
?>