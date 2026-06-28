<?php
require_once '../includes/config.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duree_qcm = (int) $_POST['duree_qcm'];

    if ($duree_qcm >= 1 && $duree_qcm <= 60) {
        $requete = $pdo->prepare("UPDATE parametres SET valeur = ? WHERE nom = ?");
        $requete->execute([$duree_qcm, 'duree_qcm']);
        $message = "Durée du QCM mise à jour.";
    } else {
        $message = "La durée doit être comprise entre 1 et 60 minutes.";
    }
}

$requeteDuree = $pdo->prepare("SELECT valeur FROM parametres WHERE nom = ?");
$requeteDuree->execute(['duree_qcm']);
$duree_qcm = (int) $requeteDuree->fetchColumn();

if ($duree_qcm <= 0) {
    $duree_qcm = 10;
}

$titre = "Administration";
require_once '../includes/header.php';
?>

<h1 class="mb-3">Administration</h1>

<p>Bienvenue dans l'interface administrateur.</p>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Gestion</h2>
                <div class="d-grid gap-2">
                    <a class="btn btn-outline-primary" href="utilisateurs.php">Gérer les utilisateurs</a>
                    <a class="btn btn-outline-primary" href="questions.php">Gérer les questions</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Temps du QCM</h2>
                <p>Durée actuelle : <strong><?= htmlspecialchars($duree_qcm) ?> minute(s)</strong></p>

                <form method="POST">
                    <label class="form-label" for="duree_qcm">Durée en minutes</label>
                    <input class="form-control" type="number" id="duree_qcm" name="duree_qcm" min="1" max="60" value="<?= htmlspecialchars($duree_qcm) ?>" required>
                    <button class="btn btn-success mt-3" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>