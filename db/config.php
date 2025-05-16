<?php
$host = 'bdjiqcyfkuixyubw0fdd-mysql.services.clever-cloud.com';
$dbname = 'bdjiqcyfkuixyubw0fdd';
$username = 'up0gqrwfyet1kt3b'; // Remplace par ton utilisateur MySQL
$password = 'LjMVU9QGJFxqDutI1C7l'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>