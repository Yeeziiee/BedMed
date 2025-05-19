<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'soignant') {
    header("Location: login.php");
    exit();
}

include '../db/config.php';

if (!isset($_GET['numero'])) {
    echo "Numéro de chambre non spécifié.";
    exit();
}

$numero = $_GET['numero'];

// Récupérer les infos de la chambre
$stmt = $pdo->prepare("SELECT * FROM Chambre WHERE numerochambre = ?");
$stmt->execute([$numero]);
$chambre = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les infos du soignant 
$stmt = $pdo->prepare("SELECT nom, prenom FROM Soignant WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$soignant = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer la liste des patients
$stmt = $pdo->query("SELECT p.id, p.nom, p.prenom, p.age, p.historique, p.motif, p.mobilite, p.date_entree, c.numerochambre AS numerochambre 
                     FROM Patient p
                     LEFT JOIN Chambre c ON p.id_chambre = c.id_chambre");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$chambre) {
    echo "Chambre non trouvée.";
    exit();
}

$temperature = $chambre['temperature'];

if ($temperature > 24) {
    $tempClass = 'temp-red';
} elseif ($temperature >= 23 && $temperature <= 24) {
    $tempClass = 'temp-orange';
} elseif ($temperature >= 20 && $temperature < 23) {
    $tempClass = 'temp-green';
} else {
    $tempClass = 'temp-lightblue';
}

$humidite = $chambre['humidite'];

if ($humidite > 60) {
    $humClass = 'hum-red';
} elseif ($humidite >= 40 && $humidite < 60) {
    $humClass = 'hum-green';
} else {
    $humClass = 'hum-lightblue';
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard soignant <?= htmlspecialchars($numero) ?></title>
    <link rel="stylesheet" href="../css/chambre.css?v=1.0">
    <link rel="stylesheet" href="../css/base.css?v=1.0">
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
                <span class="dashboard-text">Dashboard</span>    
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
            <h1>État de la chambre</h1>
            </div>

            <div class="header-right">
                <a href="logout.php" class="logout-btn" title="Déconnexion">
                    <img src="../assets/image/deco.png" class="deco-img" alt="Déconnexion">
                </a>
            </div>
        </header>

    <div class="chambre-container">
        <h2>Chambre n°<?= htmlspecialchars($numero) ?></h2>
        <p>La chambre est :
            <span style="color: <?= $chambre['disponible'] ? 'green' : 'red' ?>;font-weight: bold;">
                <?= $chambre['disponible'] ? 'Disponible' : 'Occupée' ?>
            </span>
        </p>
        <p> Température : <span class="<?= htmlspecialchars($tempClass) ?>"><?= htmlspecialchars($temperature) ?> °C</span></p>
        <p> Humidité : <span class="<?= htmlspecialchars($humClass) ?>"><?= htmlspecialchars($humidite) ?> %</span></p>
        <a href="patient.php">⬅ Retour au tableau</a>
    </div>
    <script src="../js/darktheme.js"></script>
</body>
</html>
