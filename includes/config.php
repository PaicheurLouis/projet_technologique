<?php
session_start();

$host = 'localhost';
$dbname = 'qcm_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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



} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>