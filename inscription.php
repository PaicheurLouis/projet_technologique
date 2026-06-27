<?php
require_once 'includes/config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($mot_de_passe)) {

        $requete = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $requete->execute([$email]);
        $utilisateur = $requete->fetch();

        if ($utilisateur) {
            $message = "Cet email est déjà utilisé.";
        } else {
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            $requete = $pdo->prepare("
                INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe)
                VALUES (?, ?, ?, ?)
            ");

            $requete->execute([
                $nom,
                $prenom,
                $email,
                $mot_de_passe_hash
            ]);

            $message = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
        }

    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Inscription</h2>

<?php if (!empty($message)): ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nom</label>
    <input type="text" name="nom" required>

    <label>Prénom</label>
    <input type="text" name="prenom" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mot de passe</label>
    <input type="password" name="mot_de_passe" required>

    <button type="submit">S'inscrire</button>
</form>

<?php include 'includes/footer.php'; ?>