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
    <link rel="stylesheet" href="dashboard.css?v=1.0">
    <link rel="stylesheet" href="darktheme.css?v=1.0">
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
            <h1>Dashboard de <?= htmlspecialchars($soignant['prenom']) . ' ' . htmlspecialchars($soignant['nom']) ?></h1>
            </div>

            <div class="header-right">
                <a href="../logout/logout.php" class="logout-btn" title="Déconnexion">
                    <img src="../assets/image/deco.png" class="deco-img" alt="Déconnexion">
                </a>
            </div>
        </header>

        <div class="cards">
            <a href="../emploi_du_temps/emploi2.php" class="card light-grey">
                <h2 class="emploi">Emploi du temps</h2>
                <div class="calendar" id="calendar-buttons"></div>
                <div class="month-year" id="month-year"></div>
                </a>
        

            <a href="../Patients/patient.php" class="card blue">
            <div class="patient-card">
                <h2 class="patient">Patients</h2>
                <img src="../assets/image/patient.png" class="patient-icon" alt="icon patient">
            </div>
            </a>
                <!--<div class="patient-row">
                <div class="patient-entry">
                    <span class="patient-name">P1</span>
                    <span class="cp-bubble">CP1</span>
                </div>
                <div class="patient-entry">
                    <span class="patient-name">P2</span>
                    <span class="cp-bubble">CP1</span>
                </div>
                <div class="patient-entry">
                    <span class="patient-name">P3</span>
                    <span class="cp-bubble">CP1</span>
                </div>
                </div>-->

            <a href="../Alerte/alerte.php" class="card red" title="alerte">
                <span class="alerte">Alerte </span>
                <img src="../assets/image/alerte.png" class="alerte-img" alt="Logo alerte">
            </a>
    </div>
    <script src="../js/darktheme.js"></script>
    <script src="../js/jour.js"></script>
</body>
</html>
