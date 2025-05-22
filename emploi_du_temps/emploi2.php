<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'soignant') {
    header("Location: login.php");
    exit();
}
include '../db/config.php';

$stmt = $pdo->prepare("SELECT jour, heure_debut, heure_fin FROM Emploi_Temps WHERE id_soignant = ?");
$stmt->execute([$_SESSION['user_id']]);
$edt = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT nom, prenom FROM Soignant WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$soignant = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Soignant</title>
    <link rel="stylesheet" href="emploi2.css?v=1.0">
    <link rel="stylesheet" href="darktheme.css?v=1.0">
</head>
<body>
    <div class="sidebar">
        <div class="logo-container">
            <img src="../assets/image/bedmed.png" alt="BedMed Logo" class="logo">
            <span class="logo-text">BedMed</span>
        </div>

            <div class="dashboard" id="dashboard">
                <a href="../accueil/dashboard.php" class="dashboard-btn" title="Dashboard">
                    <img src="../assets/image/dashboard3.png" class="dashboard-img" alt="logo dashboard">
                    <span class="dashboard-text">Accueil</span>    
                </a>
            </div>
            
            <div class="settings" id="settings">
                <a href="../logout/logout.php" class="settings-btn" title="settings">
                    <img src="../assets/image/deco.png" class="settings-img" alt="deco settings">
                    <span class="settings-text">Déconnexion</span>
                </a>
            </div>

            <div class="chambre" id="chambre">
                <a href="../chambre/liste_chambre.php" class="chambre-btn" title="chambre">
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
                <h1>Emploi du temps</h1>
            </div>

            <div class="header-right">
                <a href="../logout/logout.php" class="logout-btn" title="Déconnexion">
                    <img src="../assets/image/deco.png" class="deco-img" alt="Déconnexion">
                </a>
            </div>
        </header>
     <!-- ======= EMPLOI DU TEMPS TABLE ======= -->
     <div class="emploi-container">
            <div class="emploi-title">
                <img src="../assets/image/calendar.png" alt="Icon" class="calendaricon">
                <span><?= htmlspecialchars($soignant['prenom']) . ' ' . htmlspecialchars($soignant['nom']) ?></span>
            </div>
            <table class="emploi-table">
                <thead>
                    <tr>
                        <th>JOUR</th>
                        <th>HEURE DE DEBUT</th>
                        <th>HEURE DE FIN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($edt as $ligne): ?>
                        <tr>
                            <td><?= htmlspecialchars($ligne['jour']) ?></td>
                            <td><?= htmlspecialchars(substr($ligne['heure_debut'], 0, 5)) ?></td>
                            <td><?= htmlspecialchars(substr($ligne['heure_fin'], 0, 5)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
        
    <script src="../js/darktheme.js"></script>
</body>
</html>
