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


$stmt = $pdo->query("
    SELECT c.numerochambre, c.disponible, c.temperature, c.humidite,
           p.nom, p.prenom
    FROM Chambre c
    LEFT JOIN Patient p ON c.id_chambre = p.id_chambre
");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Soignant</title>
    <link rel="stylesheet" href="../css/base.css?v=1.0">
    <link rel="stylesheet" href="../css/liste_chambre.css?v=1.0">
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
                    <img src="../assets/image/dashboard3.png" class="dashboard-img" alt="logo dashboard">
                    <span class="dashboard-text">Accueil</span>    
                </a>
            </div>
            
            <div class="settings" id="settings">
                <a href="logout.php" class="settings-btn" title="settings">
                    <img src="../assets/image/deco.png" class="settings-img" alt="deco settings">
                    <span class="settings-text">Déconnexion</span>
                </a>
            </div>

            <div class="chambre" id="chambre">
                <a href="liste_chambre.php" class="chambre-btn" title="chambre">
                    <img src="../assets/image/chambre3.png" class="chambre-img" alt="icon chambre">
                    <span class="chambre-text">Chambres</span>
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
            <h1>Liste des chambres</h1>
        </div>

        <div class="header-right">
            <form class="search-form" action="search.php" method="GET">
                <input type="text" id="searchInput" name="query" placeholder="Rechercher une chambre...">
            </form>
            <a href="logout.php" class="logout-btn" title="Déconnexion">
                <img src="../assets/image/deco.png" class="deco-img" alt="Déconnexion">
            </a>
        </div>
    </header>
    <div class="chambre-container">
        <div class="chambre-title">
            <img src="../assets/image/user1.png" alt="Icon" class="patient-icon">
            <span><?= htmlspecialchars($soignant['prenom']) . ' ' . htmlspecialchars($soignant['nom']) ?></span>
        </div>
        <table id="chambreTable" class="chambre-table">
            <thead>
                <tr>
                    <th>NUMERO</th>
                    <th>DISPONIBLE</th>
                    <th>TEMPERATURE</th>
                    <th>HUMIDITE</th>
                    <th>NOM</th>
                    <th>PRENOM</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chambres as $ligne): ?>
                    <tr>
                        <td><?= htmlspecialchars($ligne['numerochambre']) ?></td> 
                        <td><?= $ligne['disponible'] ? 'Oui' : 'Non' ?></td>
                        <td><?= htmlspecialchars(substr($ligne['temperature'], 0, 10)) ?></td>
                        <td><?= htmlspecialchars($ligne['humidite']) ?></td>
                        <td><?= $ligne['disponible'] ? '' : htmlspecialchars($ligne['nom']) ?></td>
                        <td><?= $ligne['disponible'] ? '' : htmlspecialchars($ligne['prenom']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../js/darktheme.js"></script>
<script src="../js/search_bar_liste_chambre.js"></script>
</body>
</html>