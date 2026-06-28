<?php
require_once 'includes/config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS parametres (
            nom VARCHAR(100) PRIMARY KEY,
            valeur VARCHAR(255) NOT NULL
        )
    ");

    $requeteParametre = $pdo->prepare("SELECT valeur FROM parametres WHERE nom = ?");
    $requeteParametre->execute(['duree_qcm']);

    if (!$requeteParametre->fetch()) {
        $insertionParametre = $pdo->prepare("INSERT INTO parametres (nom, valeur) VALUES (?, ?)");
        $insertionParametre->execute(['duree_qcm', '10']);
    }

    $requeteDuree = $pdo->prepare("SELECT valeur FROM parametres WHERE nom = ?");
    $requeteDuree->execute(['duree_qcm']);
    $duree_qcm_minutes = (int) $requeteDuree->fetchColumn();

    if ($duree_qcm_minutes <= 0) {
        $duree_qcm_minutes = 10;
    }

} catch (PDOException $e) {
    $duree_qcm_minutes = 10;
}

// Tirage de 10 questions au hasard
$requete = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
$questions = $requete->fetchAll();

// Enregistrement des questions et du temps dans la session
$_SESSION['qcm_questions'] = [];
$_SESSION['qcm_debut'] = time();
$_SESSION['qcm_duree_secondes'] = $duree_qcm_minutes * 60;

foreach ($questions as $question) {
    $_SESSION['qcm_questions'][] = $question['id'];
}

$titre = "QCM";
require_once 'includes/header.php';
?>

<h1 class="mb-3">QCM</h1>

<div id="alerte-triche" class="alert alert-warning text-center">
    <h2>Attention</h2>
    <p>Vous avez quitté le mode plein écran.</p>
    <p>Pour continuer le QCM, cliquez sur le bouton ci-dessous.</p>

    <button type="button" id="retour-plein-ecran" class="btn btn-warning">
        Revenir en plein écran
    </button>
</div>

<?php if (count($questions) < 10): ?>

    <div class="alert alert-danger">
        Il n'y a pas encore assez de questions dans la base de données.
    </div>

<?php else: ?>

    <div id="intro-qcm" class="text-center py-4">
        <h2>Prêt à commencer le QCM ?</h2>
        <p>Le QCM doit être lancé en plein écran.</p>
        <p>Temps disponible : <strong><?= htmlspecialchars($duree_qcm_minutes) ?> minute(s)</strong>.</p>

        <button type="button" id="demarrer-qcm" class="btn btn-primary btn-lg">
            Démarrer le QCM
        </button>
    </div>

    <div id="zone-qcm" style="display: none;">

        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light border rounded">
            <p class="mb-0">Répondez aux 10 questions suivantes.</p>
            <p class="mb-0 fw-bold">
                Temps restant : <span id="chrono-qcm"></span>
            </p>
        </div>

        <form action="resultat.php" method="POST" id="form-qcm">
            <input type="hidden" name="temps_depasse" id="temps_depasse" value="0">

            <?php foreach ($questions as $index => $question): ?>

                <div class="question-box card mb-3">
                    <div class="card-body">
                        <h3 class="h5 card-title">
                            Question <?= $index + 1 ?> : <?= htmlspecialchars($question['question']) ?>
                        </h3>

                        <?php for ($numero = 1; $numero <= 4; $numero++): ?>
                            <div class="form-check mt-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="reponses[<?= $question['id'] ?>]"
                                    id="q<?= $question['id'] ?>r<?= $numero ?>"
                                    value="<?= $numero ?>"
                                    required
                                >

                                <label class="form-check-label" for="q<?= $question['id'] ?>r<?= $numero ?>">
                                    <?= htmlspecialchars($question['reponse' . $numero]) ?>
                                </label>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

            <?php endforeach; ?>

            <button type="submit" class="btn btn-success">
                Valider le QCM
            </button>
        </form>

    </div>

<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let avertissements = 0;
    let qcmCommence = false;
    let tentativeAnnulee = false;
    let qcmEnPause = false;
    let validationEnCours = false;
    let tempsRestant = <?= (int) $_SESSION['qcm_duree_secondes'] ?>;
    let intervalChrono = null;

    const maxAvertissements = 2;

    const boutonDemarrer = document.getElementById('demarrer-qcm');
    const boutonRetour = document.getElementById('retour-plein-ecran');
    const introQcm = document.getElementById('intro-qcm');
    const zoneQcm = document.getElementById('zone-qcm');
    const formQcm = document.getElementById('form-qcm');
    const alerteTriche = document.getElementById('alerte-triche');
    const chronoQcm = document.getElementById('chrono-qcm');
    const tempsDepasse = document.getElementById('temps_depasse');

    if (!boutonDemarrer || !boutonRetour || !introQcm || !zoneQcm || !formQcm || !alerteTriche || !chronoQcm || !tempsDepasse) {
        return;
    }

    function afficherChrono() {
        const minutes = Math.floor(tempsRestant / 60);
        const secondes = tempsRestant % 60;

        chronoQcm.textContent =
            String(minutes).padStart(2, '0') + ':' + String(secondes).padStart(2, '0');
    }

    function demarrerChrono() {
        afficherChrono();

        intervalChrono = setInterval(function () {
            if (!qcmCommence || qcmEnPause || tentativeAnnulee || validationEnCours) {
                return;
            }

            tempsRestant--;
            afficherChrono();

            if (tempsRestant <= 0) {
                clearInterval(intervalChrono);
                validationEnCours = true;
                tempsDepasse.value = '1';

                alert("Temps écoulé. Le QCM va être validé automatiquement.");
                formQcm.submit();
            }
        }, 1000);
    }

    function activerPleinEcran() {
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        }
    }

    boutonDemarrer.addEventListener('click', function () {
        qcmCommence = true;
        qcmEnPause = false;

        activerPleinEcran();

        introQcm.style.display = 'none';
        zoneQcm.style.display = 'block';
        alerteTriche.style.display = 'none';

        demarrerChrono();
    });

    boutonRetour.addEventListener('click', function () {
        activerPleinEcran();

        qcmEnPause = false;
        alerteTriche.style.display = 'none';
        zoneQcm.style.display = 'block';
    });

    function declencherAvertissement(raison) {
        if (!qcmCommence || tentativeAnnulee || qcmEnPause || validationEnCours) {
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
            window.location.href = "/qcm/profil.php";
        }
    }

    document.addEventListener('fullscreenchange', function () {
        if (qcmCommence && !document.fullscreenElement && !validationEnCours) {
            declencherAvertissement("vous avez quitté le mode plein écran");
        }
    });

    document.addEventListener('visibilitychange', function () {
        if (qcmCommence && document.hidden && !validationEnCours) {
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
            return;
        }

        validationEnCours = true;
        clearInterval(intervalChrono);
    });

});
</script>

<?php require_once 'includes/footer.php'; ?>