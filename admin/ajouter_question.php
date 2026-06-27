<?php
require_once '../includes/config.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question']);
    $reponse1 = trim($_POST['reponse1']);
    $reponse2 = trim($_POST['reponse2']);
    $reponse3 = trim($_POST['reponse3']);
    $reponse4 = trim($_POST['reponse4']);
    $bonne_reponse = (int) $_POST['bonne_reponse'];

    if (!empty($question) && !empty($reponse1) && !empty($reponse2) && !empty($reponse3) && !empty($reponse4) && $bonne_reponse >= 1 && $bonne_reponse <= 4) {
        $requete = $pdo->prepare("
            INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $requete->execute([$question, $reponse1, $reponse2, $reponse3, $reponse4, $bonne_reponse]);

        header('Location: questions.php');
        exit;
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

$titre = "Ajouter une question";
require_once '../includes/header.php';
?>

<h1>Ajouter une question</h1>

<p><a href="questions.php">Retour aux questions</a></p>

<?php if (!empty($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <label>Question :</label><br>
    <textarea name="question" required></textarea><br><br>

    <label>Réponse 1 :</label><br>
    <input type="text" name="reponse1" required><br><br>

    <label>Réponse 2 :</label><br>
    <input type="text" name="reponse2" required><br><br>

    <label>Réponse 3 :</label><br>
    <input type="text" name="reponse3" required><br><br>

    <label>Réponse 4 :</label><br>
    <input type="text" name="reponse4" required><br><br>

    <label>Bonne réponse :</label><br>
    <select name="bonne_reponse" required>
        <option value="1">Réponse 1</option>
        <option value="2">Réponse 2</option>
        <option value="3">Réponse 3</option>
        <option value="4">Réponse 4</option>
    </select><br><br>

    <button type="submit">Ajouter</button>
</form>

<?php require_once '../includes/footer.php'; ?>