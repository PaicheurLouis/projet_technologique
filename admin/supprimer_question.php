<?php
require_once '../includes/config.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $requete = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $requete->execute([$id]);
}

header('Location: questions.php');
exit;