<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'soignant') {
    header("Location: login.php");
    exit();
}
include '../db/config.php';
// Récupérer l'emploi du temps
$stmt = $pdo->prepare("SELECT jour, heure_debut, heure_fin FROM Emploi_Temps WHERE id_soignant = ?");
$stmt->execute([$_SESSION['user_id']]);
$edt = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des patients
$stmt = $pdo->query("SELECT nom, prenom, historique FROM Patient");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les températures et humidités des chambres
$stmt = $pdo->query("SELECT numero, etage, temperature, humidite FROM Chambre");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les informations du soignant connecté
$stmt = $pdo->prepare("SELECT nom, prenom FROM Soignant WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$soignant = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soignant - Dashboard</title>
    <link rel="stylesheet" href="../css/soignant.css?v=1.0">
</head>
<body>
<a href="logout.php" class="btn logout-btn">🚪 Déconnexion</a>
<h1>Bienvenue <?= htmlspecialchars($soignant['prenom']) . ' ' . htmlspecialchars($soignant['nom']) ?></h1>

    <h2>📅 Votre Emploi du Temps</h2>
    <table>
        <tr><th>Jour</th><th>Heure Début</th><th>Heure Fin</th></tr>
        <?php foreach ($edt as $row) { ?>
            <tr><td><?= $row['jour'] ?></td><td><?= $row['heure_debut'] ?></td><td><?= $row['heure_fin'] ?></td></tr>
        <?php } ?>
    </table>

    <h2>👩‍⚕️ Liste des Patients</h2>
    <ul>
        <?php foreach ($patients as $patient) { ?>
            <li><?= $patient['nom'] ?> <?= $patient['prenom'] ?> - <?= $patient['historique'] ?></li>
        <?php } ?>
    </ul>

    <h2>🏥 État des Chambres</h2>
    <table>
        <tr><th>Numéro</th><th>Étage</th><th>Température</th><th>Humidité</th></tr>
        <?php foreach ($chambres as $chambre) { ?>
            <tr><td><?= $chambre['numero'] ?></td><td><?= $chambre['etage'] ?></td><td><?= $chambre['temperature'] ?>°C</td><td><?= $chambre['humidite'] ?>%</td></tr>
        <?php } ?>
    </table>
</body>
</html>