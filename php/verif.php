<?php
// Récupération de l'UID du badge
$uid = $_GET['uid'] ?? null;

if (!$uid) {
    http_response_code(400);
    echo "Erreur : identifiant manquant";
    exit;
}

// Heure et jour actuels
$now = new DateTime();
$heure = $now->format('H:i:s');

// Traduction des jours en français (car ta base utilise "Lundi", etc.)
$jours = [
    "Monday"    => "Lundi",
    "Tuesday"   => "Mardi",
    "Wednesday" => "Mercredi",
    "Thursday"  => "Jeudi",
    "Friday"    => "Vendredi",
    "Saturday"  => "Samedi",
    "Sunday"    => "Dimanche"
];

$jour_en = $now->format('l');
$jour = $jours[$jour_en] ?? null;

if (!$jour) {
    http_response_code(500);
    echo "Erreur : jour invalide";
    exit;
}

// Connexion à la base
include '../db/config.php'; // contient $pdo

// Requête pour vérifier l'emploi du temps
$stmt = $pdo->prepare("
    SELECT * FROM Emploi_Temps e
    JOIN Soignant s ON e.id_soignant = s.id
    WHERE s.identifiant = ? 
    AND e.jour = ? 
    AND ? BETWEEN e.heure_debut AND e.heure_fin
");

$stmt->execute([$uid, $jour, $heure]);

if ($stmt->fetch()) {
    echo "OK";
} else {
    echo "KO";
}
?>
