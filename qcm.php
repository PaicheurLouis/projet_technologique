<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$requete = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
$questions = $requete->fetchAll();

$_SESSION['qcm_questions'] = [];

foreach ($questions as $question) {
    $_SESSION['qcm_questions'][] = $question['id'];
}
?>

<?php require_once 'includes/header.php'; ?>

<h1>QCM</h1>

<div id="alerte-triche">
    <h2>Attention</h2>
    <p>Vous avez quitté le mode plein écran.</p>
    <p>Pour continuer le QCM, cliquez sur le bouton ci-dessous.</p>

    <button type="button" id="retour-plein-ecran">
        Revenir en plein écran
    </button>
</div>
<?php if (count($questions) < 10): ?>

    <p>Il n'y a pas encore assez de questions dans la base de données.</p>

<?php else: ?>

    <div id="intro-qcm">
        <h2>Prêt à commencer le QCM ?</h2>
        <p>Le QCM doit être lancé en plein écran.</p>

        <button type="button" id="demarrer-qcm">
            Démarrer le QCM
        </button>
    </div>

    <div id="zone-qcm" style="display: none;">

        <p>Répondez aux 10 questions suivantes.</p>

        <form action="resultat.php" method="POST" id="form-qcm">

            <?php foreach ($questions as $index => $question): ?>

                <div class="question-box">
                    <h3>Question <?= $index + 1 ?> : <?= htmlspecialchars($question['question']) ?></h3>

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

            <button type="submit">Valider le QCM</button>

        </form>

    </div>

<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let avertissements = 0;
    let qcmCommence = false;
    let tentativeAnnulee = false;
    let qcmEnPause = false;

    const maxAvertissements = 2;

    const boutonDemarrer = document.getElementById('demarrer-qcm');
    const boutonRetour = document.getElementById('retour-plein-ecran');
    const introQcm = document.getElementById('intro-qcm');
    const zoneQcm = document.getElementById('zone-qcm');
    const formQcm = document.getElementById('form-qcm');
    const alerteTriche = document.getElementById('alerte-triche');

    if (!boutonDemarrer || !boutonRetour || !introQcm || !zoneQcm || !formQcm || !alerteTriche) {
        console.log("Un élément anti-triche est manquant.");
        return;
    }

    function activerPleinEcran() {
        document.documentElement.requestFullscreen();
    }

    boutonDemarrer.addEventListener('click', function () {
        qcmCommence = true;
        qcmEnPause = false;

        activerPleinEcran();

        introQcm.style.display = 'none';
        zoneQcm.style.display = 'block';
        alerteTriche.style.display = 'none';
    });

    boutonRetour.addEventListener('click', function () {
        activerPleinEcran();

        qcmEnPause = false;
        alerteTriche.style.display = 'none';
        zoneQcm.style.display = 'block';
    });

    function declencherAvertissement(raison) {
        if (!qcmCommence || tentativeAnnulee || qcmEnPause) {
            return;
        }

        qcmEnPause = true;
        avertissements++;

        zoneQcm.style.display = 'none';
        alerteTriche.style.display = 'block';

        alert("Attention : " + raison + ". Avertissement " + avertissements + "/" + maxAvertissements);

        if (avertissements >= maxAvertissements) {
            tentativeAnnulee = true;
            alert("Tentative annulée pour suspicion de triche.");
            window.location.href = "profil.php";
        }
    }

    document.addEventListener('fullscreenchange', function () {
        if (qcmCommence && !document.fullscreenElement) {
            declencherAvertissement("vous avez quitté le mode plein écran");
        }
    });

    document.addEventListener('visibilitychange', function () {
        if (qcmCommence && document.hidden) {
            declencherAvertissement("vous avez changé d'onglet ou minimisé la fenêtre");
        }
    });

    document.addEventListener('contextmenu', function (event) {
        if (qcmCommence) {
            event.preventDefault();
        }
    });

    document.addEventListener('copy', function (event) {
        if (qcmCommence) {
            event.preventDefault();
        }
    });

    document.addEventListener('paste', function (event) {
        if (qcmCommence) {
            event.preventDefault();
        }
    });

    document.addEventListener('selectstart', function (event) {
        if (qcmCommence) {
            event.preventDefault();
        }
    });

    formQcm.addEventListener('submit', function (event) {
        if (tentativeAnnulee) {
            event.preventDefault();
            alert("Votre tentative a été annulée.");
        }
    });

});
</script>

<?php require_once 'includes/footer.php'; ?>