<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

if (!isset($_POST['reponses']) || !isset($_SESSION['questions_qcm'])) {
    header('Location: qcm.php');
    exit();
}

$reponses_utilisateur = $_POST['reponses'];
$questions_ids = $_SESSION['questions_qcm'];

$placeholders = implode(',', array_fill(0, count($questions_ids), '?'));

$stmt = $pdo->prepare("SELECT * FROM questions WHERE id IN ($placeholders)");
$stmt->execute($questions_ids);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bonnes_reponses = 0;
$details = [];

foreach ($questions as $question) {
    $id_question = $question['id'];

    $reponse_utilisateur = isset($reponses_utilisateur[$id_question])
        ? intval($reponses_utilisateur[$id_question])
        : 0;

    $est_correcte = ($reponse_utilisateur === intval($question['bonne_reponse']));

    if ($est_correcte) {
        $bonnes_reponses++;
    }

    $details[] = [
        'question' => $question['question'],
        'reponse_utilisateur' => $reponse_utilisateur,
        'bonne_reponse' => intval($question['bonne_reponse']),
        'reponse1' => $question['reponse1'],
        'reponse2' => $question['reponse2'],
        'reponse3' => $question['reponse3'],
        'reponse4' => $question['reponse4'],
        'correcte' => $est_correcte
    ];
}

$score = ($bonnes_reponses / 10) * 20;

$_SESSION['resultat_qcm'] = [
    'score' => $score,
    'bonnes_reponses' => $bonnes_reponses,
    'details' => $details
];

header('Location: resultat.php');
exit();