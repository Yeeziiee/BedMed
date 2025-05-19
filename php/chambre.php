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
$stmt = $pdo->prepare("SELECT * FROM Chambre WHERE numero = ?");
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chambre <?= htmlspecialchars($numero) ?></title>
    <link rel="stylesheet" href="../css/chambre.css?v=1.0">
    <link rel="stylesheet" href="../css/darktheme.css?v=1.0">
</head>
<body>
    <div class="container">
        <h2>Chambre n°<?= htmlspecialchars($numero) ?></h2>

        <p><strong>Étage :</strong> <?= htmlspecialchars($chambre['etage']) ?></p>
        <p><strong>Nombre de lits :</strong> <?= htmlspecialchars($chambre['nb_lits']) ?></p>
        <p><strong>Type :</strong> <?= htmlspecialchars($chambre['type']) ?></p>

        <a href="dashboard.php">⬅ Retour au tableau</a>
    </div>
</body>
</html>
