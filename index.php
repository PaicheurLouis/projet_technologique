<?php
require_once 'includes/config.php';
?>

<?php include 'includes/header.php'; ?>

<h2>Accueil</h2>

<?php if (isset($_SESSION['utilisateur_id'])): ?>

    <p>
        Bienvenue 
        <?php echo htmlspecialchars($_SESSION['utilisateur_prenom']); ?> 
        <?php echo htmlspecialchars($_SESSION['utilisateur_nom']); ?>.
    </p>

    <p>Vous êtes connecté.</p>

    <a class="bouton" href="qcm.php">Lancer un QCM</a>

<?php else: ?>

    <p>Bienvenue sur l'application de génération de QCM.</p>
    <p>Veuillez vous inscrire ou vous connecter pour commencer.</p>

    <a class="bouton" href="inscription.php">Créer un compte</a>
    <a class="bouton" href="connexion.php">Se connecter</a>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>