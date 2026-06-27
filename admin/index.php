<?php
require_once '../includes/config.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

$titre = "Administration";
require_once '../includes/header.php';
?>

<h1>Administration</h1>

<p>Bienvenue dans l'interface administrateur.</p>

<ul>
    <li><a href="utilisateurs.php">Gérer les utilisateurs</a></li>
    <li><a href="questions.php">Gérer les questions</a></li>
</ul>

<?php require_once '../includes/footer.php'; ?>