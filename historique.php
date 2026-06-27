<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

$requete = $pdo->prepare("
    SELECT id, score, date_tentative
    FROM tentatives
    WHERE utilisateur_id = ?
    ORDER BY date_tentative DESC
");
$requete->execute([$utilisateur_id]);
$tentatives = $requete->fetchAll();

$requete_moyenne = $pdo->prepare("
    SELECT AVG(score) AS moyenne
    FROM tentatives
    WHERE utilisateur_id = ?
");
$requete_moyenne->execute([$utilisateur_id]);
$resultat_moyenne = $requete_moyenne->fetch();

$moyenne = $resultat_moyenne['moyenne'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h1>Historique de mes QCM</h1>

    <?php if (count($tentatives) === 0): ?>

        <p>Vous n'avez encore réalisé aucun QCM.</p>

    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>Tentative</th>
                    <th>Date</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tentatives as $index => $tentative): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($tentative['date_tentative'])); ?></td>
                        <td><?php echo htmlspecialchars($tentative['score']); ?>/20</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="moyenne">
            Moyenne générale :
            <strong><?php echo round($moyenne, 2); ?>/20</strong>
        </p>

    <?php endif; ?>

    <p>
        <a href="profil.php">Retour au profil</a>
    </p>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>