

<?php
/*
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
*/


$uid = strtoupper($_GET['uid']); // Ex: D5D3CF22

try {
    $pdo = new PDO("mysql:host=bdjiqcyfkuixyubw0fdd-mysql.services.clever-cloud.com;dbname=bdjiqcyfkuixyubw0fdd", "up0gqrwfyet1kt3b", "LjMVU9QGJFxqDutI1C7l");

    // Cherche le soignant par UID
    $stmt = $pdo->prepare("SELECT id FROM Soignant WHERE REPLACE(UPPER(uid_badge), ' ', '') = :uid");
    $stmt->execute(['uid' => $uid]);
    $soignant = $stmt->fetch();

    if (!$soignant) {
        echo "NON";
        exit;
    }

    $id = $soignant['id'];
    $jour = ucfirst(strftime('%A')); // Lundi, Mardi, etc.
    $heure = date('H:i:s');

    // Vérifie la disponibilité
    $stmt2 = $pdo->prepare("SELECT * FROM Emploi_Temps WHERE id_soignant = :id AND jour = :jour AND :heure BETWEEN heure_debut AND heure_fin");
    $stmt2->execute(['id' => $id, 'jour' => $jour, 'heure' => $heure]);

    echo $stmt2->fetch() ? "OK" : "NON";
} catch (Exception $e) {
    echo "ERREUR";
}

?>

