<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$_SESSION['questions_qcm'] = array_column($questions, 'id');

include 'includes/header.php';
?>

<h1>QCM</h1>

<?php if (count($questions) < 10): ?>

    <p>Il n’y a pas assez de questions dans la base de données.</p>
    <p>Il faut au moins 10 questions pour lancer un QCM.</p>

<?php else: ?>

    <form action="traitement_qcm.php" method="post">

        <?php foreach ($questions as $index => $question): ?>

            <div class="question-box">
                <h3>Question <?= $index + 1 ?> :</h3>

                <p><?= htmlspecialchars($question['question']) ?></p>

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

        <button type="submit">Valider mes réponses</button>

    </form>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>