<?php
require_once '../includes/config.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

$requete = $pdo->query("SELECT * FROM questions ORDER BY id DESC");
$questions = $requete->fetchAll();

$titre = "Gestion des questions";
require_once '../includes/header.php';
?>

<h1>Gestion des questions</h1>

<p><a href="index.php">Retour à l'administration</a></p>
<p><a href="ajouter_question.php">Ajouter une question</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Question</th>
        <th>Bonne réponse</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($questions as $question): ?>
        <tr>
            <td><?= htmlspecialchars($question['id']) ?></td>
            <td><?= htmlspecialchars($question['question']) ?></td>
            <td><?= htmlspecialchars($question['bonne_reponse']) ?></td>
            <td>
                <a href="modifier_question.php?id=<?= $question['id'] ?>">Modifier</a>
                |
                <a href="supprimer_question.php?id=<?= $question['id'] ?>" onclick="return confirm('Supprimer cette question ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../includes/footer.php'; ?>