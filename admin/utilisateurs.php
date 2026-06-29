<?php
require_once '../includes/config.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

if (isset($_GET['bloquer'])) {
    $id = (int) $_GET['bloquer'];

    $requete = $pdo->prepare("UPDATE utilisateurs SET bloque = 1 WHERE id = ?");
    $requete->execute([$id]);

    header('Location: utilisateurs.php');
    exit;
}

if (isset($_GET['debloquer'])) {
    $id = (int) $_GET['debloquer'];

    $requete = $pdo->prepare("UPDATE utilisateurs SET bloque = 0 WHERE id = ?");
    $requete->execute([$id]);

    header('Location: utilisateurs.php');
    exit;
}

if (isset($_GET['supprimer'])) {
    $id = (int) $_GET['supprimer'];

    if ($id !== (int) $_SESSION['utilisateur_id']) {
        $requete = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $requete->execute([$id]);
    }

    header('Location: utilisateurs.php');
    exit;
}

$requete = $pdo->query("SELECT * FROM utilisateurs ORDER BY id DESC");
$utilisateurs = $requete->fetchAll();

$titre = "Gestion des utilisateurs";
require_once '../includes/header.php';
?>

<h1>Gestion des utilisateurs</h1>

<p><a href="index.php">Retour à l'administration</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Bloqué</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($utilisateurs as $utilisateur): ?>
        <tr>
            <td><?= htmlspecialchars($utilisateur['id']) ?></td>
            <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
            <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
            <td><?= htmlspecialchars($utilisateur['email']) ?></td>
            <td><?= htmlspecialchars($utilisateur['role']) ?></td>
            <td><?= $utilisateur['bloque'] ? 'Oui' : 'Non' ?></td>
            <td>
                <?php if ($utilisateur['bloque']): ?>
                    <a href="/qcm/admin/utilisateurs.php?debloquer=<?= $utilisateur['id'] ?>">Débloquer</a>
                <?php else: ?>
                    <a href="/qcm/admin/utilisateurs.php?bloquer=<?= $utilisateur['id'] ?>">Bloquer</a>
                <?php endif; ?>

                |

                <?php if ($utilisateur['id'] != $_SESSION['utilisateur_id']): ?>
                    <a class="btn btn-sm btn-danger" href="/qcm/admin/utilisateurs.php?supprimer=<?= $utilisateur['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                <?php else: ?>
                    Impossible
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../includes/footer.php'; ?>