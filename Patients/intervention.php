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

if (isset($_GET['id'])) {
    $id_patient = (int) $_GET['id'];
    // Tu peux utiliser $id_patient pour afficher ou ajouter une intervention
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenu = trim($_POST['contenu'] ?? '');

    if (!empty($_POST['contenu']) && !empty($_POST['date']) && !empty($_POST['heure'])) {
    $contenu = $_POST['contenu'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    $stmt = $pdo->prepare("INSERT INTO Intervention (patient_id, soignant_id, contenu, date, heure_inter) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_patient, $_SESSION['user_id'], $contenu, $date, $heure]);

    header("Location: dashboard.php?ajout=ok");
    exit();
} else {
    $erreur = "Tous les champs sont requis.";
}
}

// Récupérer les infos du patient
$stmt = $pdo->prepare("SELECT nom, prenom FROM Patient WHERE id = ?");
$stmt->execute([$id_patient]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Soignant</title>
    <link rel="stylesheet" href="intervention.css?v=1.0">
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
            <h1>Interventions</h1>
        </div>

        <div class="header-right">
            <a href="../logout/logout.php" class="logout-btn" title="Déconnexion">
                <img src="../assets/image/deco.png" class="deco-img" alt="Déconnexion">
            </a>
        </div>
    </header>
    <div class="container">
        <h2 class="title-container">Intervention pour <?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></h2>

        <?php if (!empty($erreur)): ?>
            <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="post">
            <div>
            <label for ="date" class="date">Date :</label>
            <input type ="date" name="date" required>
            </div>

            <div>
            <label for="heure" class="heure">Heure :</label>
            <input type="time" name="heure" required>
            </div>

            <div>
            <label for="contenu" class="subtitle-container"> Contenu de l'intervention :</label><br>
            <textarea name="contenu" id="contenu" rows="6" required></textarea><br><br>
            </div> 

            <br>

            <button type="submit">Enregistrer</button>
            <a href="patient.php" class="btn-retour">Retour</a>
        </form>
    </div>
<script src="../js/darktheme.js"></script>
</body>
</html>