<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

if (!isset($_SESSION['qcm_questions']) || empty($_SESSION['qcm_questions'])) {
    header('Location: profil.php');
    exit;
}

$reponses_utilisateur = $_POST['reponses'] ?? [];
$ids_questions = $_SESSION['qcm_questions'];

// Vérification du temps côté serveur
$temps_depasse = isset($_POST['temps_depasse']) && $_POST['temps_depasse'] === '1';

if (isset($_SESSION['qcm_debut'], $_SESSION['qcm_duree_secondes'])) {
    if (time() > $_SESSION['qcm_debut'] + $_SESSION['qcm_duree_secondes'] + 5) {
        $temps_depasse = true;
    }
}

// Récupération des questions du QCM
$placeholders = implode(',', array_fill(0, count($ids_questions), '?'));

$requete = $pdo->prepare("SELECT * FROM questions WHERE id IN ($placeholders)");
$requete->execute($ids_questions);
$questions = $requete->fetchAll();

$score = 0;
$details = [];

foreach ($questions as $question) {
    $id_question = $question['id'];

    $reponse_utilisateur = $reponses_utilisateur[$id_question] ?? null;
    $bonne_reponse = $question['bonne_reponse'];

    $correcte = false;

    if ($reponse_utilisateur !== null && (int) $reponse_utilisateur === (int) $bonne_reponse) {
        $correcte = true;
        $score++;
    }

    $details[] = [
        'question_id' => $id_question,
        'question' => $question['question'],
        'reponse_utilisateur' => $reponse_utilisateur,
        'bonne_reponse' => $bonne_reponse,
        'correcte' => $correcte,
        'reponse1' => $question['reponse1'],
        'reponse2' => $question['reponse2'],
        'reponse3' => $question['reponse3'],
        'reponse4' => $question['reponse4']
    ];
}

// Note sur 20
$note = ($score / count($questions)) * 20;

$requeteTentative = $pdo->prepare("
    INSERT INTO tentatives (utilisateur_id, score, date_tentative)
    VALUES (?, ?, NOW())
");

$requeteTentative->execute([
    $_SESSION['utilisateur_id'],
    $note
]);

$tentative_id = $pdo->lastInsertId();

// Enregistrement des réponses
$requeteReponse = $pdo->prepare("
    INSERT INTO reponses (tentative_id, question_id, reponse_utilisateur, correcte)
    VALUES (?, ?, ?, ?)
");

foreach ($details as $detail) {
    $requeteReponse->execute([
        $tentative_id,
        $detail['question_id'],
        $detail['reponse_utilisateur'],
        $detail['correcte'] ? 1 : 0
    ]);
}

// Nettoyage de la session QCM
unset($_SESSION['qcm_questions'], $_SESSION['qcm_debut'], $_SESSION['qcm_duree_secondes']);

$titre = "Résultat";
require_once 'includes/header.php';
?>

<h1 class="mb-4">Résultat du QCM</h1>

<?php if ($temps_depasse): ?>
    <div class="alert alert-warning">
        Le temps du QCM est écoulé. Les réponses enregistrées ont été corrigées.
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <h2 class="h4">Votre score</h2>

        <p class="fs-5">
            Vous avez obtenu :
            <strong><?= htmlspecialchars($score) ?> / <?= htmlspecialchars(count($questions)) ?></strong>
        </p>

        <p class="fs-5">
            Note :
            <strong><?= htmlspecialchars(number_format($note, 2)) ?> / 20</strong>
        </p>
    </div>
</div>

<h2 class="h4 mb-3">Correction</h2>

<?php foreach ($details as $index => $detail): ?>

    <?php if (!$detail['correcte']): ?>
        <div class="card mb-3 erreur">
            <div class="card-body">
                <h3 class="h5">
                    Question <?= $index + 1 ?> : <?= htmlspecialchars($detail['question']) ?>
                </h3>

                <p>
                    <strong>Votre réponse :</strong>

                    <?php if ($detail['reponse_utilisateur'] === null): ?>
                        Aucune réponse
                    <?php else: ?>
                        <?= htmlspecialchars($detail['reponse' . $detail['reponse_utilisateur']]) ?>
                    <?php endif; ?>
                </p>

                <p>
                    <strong>Bonne réponse :</strong>
                    <?= htmlspecialchars($detail['reponse' . $detail['bonne_reponse']]) ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

<?php endforeach; ?>

<?php
$erreurs = array_filter($details, function ($detail) {
    return !$detail['correcte'];
});
?>

<?php if (empty($erreurs)): ?>
    <div class="alert alert-success">
        Bravo, vous avez répondu correctement à toutes les questions.
    </div>
<?php endif; ?>

<div class="mt-4">
    <a class="btn btn-primary" href="profil.php">Retour à mon espace</a>
    <a class="btn btn-outline-secondary" href="historique.php">Voir mon historique</a>
</div>

<?php require_once 'includes/footer.php'; ?>