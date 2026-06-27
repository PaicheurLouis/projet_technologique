<?php
require_once 'includes/config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (!empty($email) && !empty($mot_de_passe)) {

        $requete = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $requete->execute([$email]);
        $utilisateur = $requete->fetch();

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {

            if ($utilisateur['bloque'] == 1) {
                $message = "Votre compte est bloqué.";
            } else {
                $_SESSION['utilisateur_id'] = $utilisateur['id'];
                $_SESSION['utilisateur_nom'] = $utilisateur['nom'];
                $_SESSION['utilisateur_prenom'] = $utilisateur['prenom'];
                $_SESSION['utilisateur_role'] = $utilisateur['role'];

                header("Location: profil.php");
                exit;
            }

        } else {
            $message = "Email ou mot de passe incorrect.";
        }

    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Connexion</h2>

<?php if (!empty($message)): ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mot de passe</label>
    <input type="password" name="mot_de_passe" required>

    <button type="submit">Se connecter</button>
</form>

<?php include 'includes/footer.php'; ?>

