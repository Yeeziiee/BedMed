<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'soignant') {
    header("Location: login.php");
    exit();
}
include '../db/config.php';

// Récupérer les infos du soignant 
$stmt = $pdo->prepare("SELECT nom, prenom FROM Soignant WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$soignant = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer la liste des patients
$stmt = $pdo->query("SELECT p.id, p.nom, p.prenom, p.age, p.historique, p.motif, p.mobilite, p.date_entree, c.numerochambre AS numerochambre 
                     FROM Patient p
                     LEFT JOIN Chambre c ON p.id_chambre = c.id_chambre");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Soignant</title>
    <link rel="stylesheet" href="../css/patient.css?v=1.0">
    <link rel="stylesheet" href="../css/darktheme.css?v=1.0">
</head>
<body>
    <div class="sidebar">
        <div class="logo-container">
            <img src="../assets/image/bedmed.png" alt="BedMed Logo" class="logo">
            <span class="logo-text">BedMed</span>
        </div>

        <div class="dashboard" id="dashboard">
            <a href="dashboard.php" class="dashboard-btn" title="Dashboard">
                <img src="../assets/image/dashboard.png" class="dashboard-img" alt="logo dashboard">
                <span class="dashboard-text">Dashboard </span>    
            </a>
        </div>
        
        <div class="settings" id="settings">
            <a href="parametres.php" class="settings-btn" title="settings">
                <img src="../assets/image/settings.png" class="settings-img" alt="logo settings">
                <span class="settings-text">Paramètres </span>
            </a>
        </div>

        <div class="theme-toggle" id="theme-toggle">
            <img src="../assets/image/dark-theme.png" alt="darktheme icon" class="theme">
            <span class="Theme-text">Clair/Sombre </span>
        </div>
</div>

<div class="main-content">
    <header>
        <div class="header-left">
            <h1>Liste des patients</h1>
        </div>

        <div class="header-right">
            <form class="search-form" action="search.php" method="GET">
                <input type="text" id="searchInput" name="query" placeholder="Rechercher un patient...">
            </form>
            <a href="logout.php" class="logout-btn" title="Déconnexion">
                <img src="../assets/image/deco.png" class="deco-img" alt="Déconnexion">
            </a>
        </div>
    </header>
    <div class="patient-container">
        <div class="patient-title">
            <img src="../assets/image/user1.png" alt="Icon" class="patient-icon">
            <span><?= htmlspecialchars($soignant['prenom']) . ' ' . htmlspecialchars($soignant['nom']) ?></span>
        </div>
        <table id="patientTable" class="patient-table">
            <thead>
                <tr>
                    <th>NOM</th>
                    <th>PRENOM</th>
                    <th>AGE</th>
                    <th>CHAMBRE</th>
                    <th>MOTIF D'HOSPITALISATION</th>
                    <th>DATE D'ENTREE</th>
                    <th>HISTORIQUE</th>
                    <th>MOBILITE</th>
                    <th>INTERVENTION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $ligne): ?>
                    <tr>
                        <td><?= htmlspecialchars($ligne['nom']) ?></td>
                        <td><?= htmlspecialchars($ligne['prenom']) ?></td>
                        <td><?= htmlspecialchars($ligne['age']) ?></td>
                        <td><?= htmlspecialchars($ligne['numerochambre']) ?></td>
                        <td><?= htmlspecialchars($ligne['motif']) ?></td>
                        <td><?= htmlspecialchars(substr($ligne['date_entree'], 0, 10)) ?></td>
                        <td><?= htmlspecialchars($ligne['historique']) ?></td>
                        <td><?= htmlspecialchars($ligne['mobilite']) ?></td>
                        <td><a href="intervention.php?id=<?= urlencode($ligne['id']) ?>" class="btn-intervention">Ajouter</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="../js/darktheme.js"></script>
<script src="../js/searchbar.js"></script>
</body>
</html>