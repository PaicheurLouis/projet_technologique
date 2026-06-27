<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: qcm.php');
    exit;
}

if (!isset($_SESSION['qcm_questions']) || empty($_SESSION['qcm_questions'])) {
    header('Location: qcm.php');
    exit;
}

$reponses_utilisateur = $_POST['reponses'] ?? [];
$ids_questions = $_SESSION['qcm_questions'];

$placeholders = implode(',', array_fill(0, count($ids_questions), '?'));

$requete = $pdo->prepare("SELECT * FROM questions WHERE id IN ($placeholders)");
$requete->execute($ids_questions);
$questions = $requete->fetchAll();

$bonnes_reponses = 0;
$details = [];

foreach ($questions as $question) {
    $id_question = $question['id'];

    $reponse_donnee = isset($reponses_utilisateur[$id_question])
        ? intval($reponses_utilisateur[$id_question])
        : 0;

    $est_correcte = ($reponse_donnee === intval($question['bonne_reponse']));

    if ($est_correcte) {
        $bonnes_reponses++;
    }

    $details[] = [
        'question' => $question,
        'reponse_donnee' => $reponse_donnee,
        'est_correcte' => $est_correcte
    ];
}

$score = $bonnes_reponses * 2;

$requete = $pdo->prepare("INSERT INTO tentatives (utilisateur_id, score) VALUES (?, ?)");
$requete->execute([$_SESSION['utilisateur_id'], $score]);

$tentative_id = $pdo->lastInsertId();

foreach ($details as $detail) {
    $requete = $pdo->prepare("
        INSERT INTO reponses (tentative_id, question_id, reponse_utilisateur, correcte)
        VALUES (?, ?, ?, ?)
    ");

    $requete->execute([
        $tentative_id,
        $detail['question']['id'],
        $detail['reponse_donnee'],
        $detail['est_correcte'] ? 1 : 0
    ]);
}

unset($_SESSION['qcm_questions']);

function afficher_reponse($question, $numero) {
    if ($numero == 1) {
        return $question['reponse1'];
    } elseif ($numero == 2) {
        return $question['reponse2'];
    } elseif ($numero == 3) {
        return $question['reponse3'];
    } elseif ($numero == 4) {
        return $question['reponse4'];
    } else {
        return "Aucune réponse";
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<h1>Résultat du QCM</h1>

<p><strong>Score :</strong> <?= $score ?>/20</p>
<p><strong>Bonnes réponses :</strong> <?= $bonnes_reponses ?>/10</p>

<h2>Correction</h2>

<?php foreach ($details as $detail): ?>

    <?php if (!$detail['est_correcte']): ?>

        <div class="question-box erreur">
            <h3><?= htmlspecialchars($detail['question']['question']) ?></h3>

            <p>
                <strong>Votre réponse :</strong>
                <?= htmlspecialchars(afficher_reponse($detail['question'], $detail['reponse_donnee'])) ?>
            </p>

            <p>
                <strong>Bonne réponse :</strong>
                <?= htmlspecialchars(afficher_reponse($detail['question'], $detail['question']['bonne_reponse'])) ?>
            </p>
        </div>

        <hr>

    <?php endif; ?>

<?php endforeach; ?>

<?php if ($bonnes_reponses === 10): ?>
    <p>Bravo, aucune erreur.</p>
<?php endif; ?>

<p>
    <a href="qcm.php">Refaire un QCM</a>
</p>

<p>
    <a href="profil.php">Retour au profil</a>
</p>

<?php require_once 'includes/footer.php'; ?>