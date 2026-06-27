<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$requete = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
$questions = $requete->fetchAll();

$_SESSION['qcm_questions'] = [];

foreach ($questions as $question) {
    $_SESSION['qcm_questions'][] = $question['id'];
}
?>

<?php require_once 'includes/header.php'; ?>

<h1>QCM</h1>

<?php if (count($questions) < 10): ?>

    <p>Il n'y a pas encore assez de questions dans la base de données.</p>

<?php else: ?>

    <p>Répondez aux 10 questions suivantes.</p>

    <form method="POST" action="resultats.php">

        <?php foreach ($questions as $index => $question): ?>

            <div class="question-box">
                <h3>Question <?= $index + 1 ?> : <?= htmlspecialchars($question['question']) ?></h3>

                <label>
                    <input type="radio" name="reponses[<?= $question['id'] ?>]" value="1" required>
                    <?= htmlspecialchars($question['reponse1']) ?>
                </label>
                <br>

                <label>
                    <input type="radio" name="reponses[<?= $question['id'] ?>]" value="2">
                    <?= htmlspecialchars($question['reponse2']) ?>
                </label>
                <br>

                <label>
                    <input type="radio" name="reponses[<?= $question['id'] ?>]" value="3">
                    <?= htmlspecialchars($question['reponse3']) ?>
                </label>
                <br>

                <label>
                    <input type="radio" name="reponses[<?= $question['id'] ?>]" value="4">
                    <?= htmlspecialchars($question['reponse4']) ?>
                </label>
            </div>

            <hr>

        <?php endforeach; ?>

        <button type="submit">Valider le QCM</button>

    </form>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>