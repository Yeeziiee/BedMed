<?php
$uid = $_GET['uid'];
$now = new DateTime();
$jour = $now->format('l'); // ex: "Monday"
$heure = $now->format('H:i:s');

include '../db/config.php';

// Requête exemple à adapter
$stmt = $db->prepare("SELECT * FROM horaires h 
    JOIN utilisateurs u ON h.utilisateur_id = u.id
    WHERE u.uid = ? AND h.jour = ? AND ? BETWEEN h.heure_debut AND h.heure_fin");
$stmt->execute([$uid, $jour, $heure]);

if ($stmt->fetch()) {
    echo "OK";
} else {
    echo "KO";
}
?>
