<?php
session_start();
$host = 'bdjiqcyfkuixyubw0fdd-mysql.services.clever-cloud.com'; // ou votre IP de serveur
$dbname = 'bdjiqcyfkuixyubw0fdd';
$username = 'up0gqrwfyet1kt3b'; // Modifier si nécessaire
$password = 'LjMVU9QGJFxqDutI1C7l'; // Modifier si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant'];
    $mot_de_passe = $_POST['password'];

    // Vérifier dans chaque table (Admin, Soignant, Patient)
    $tables = ['Admin', 'Soignant', 'Patient'];
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE identifiant = :identifiant");
        $stmt->execute(['identifiant' => $identifiant]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = strtolower($table); // Sauvegarde du rôle

            // Redirection en fonction du rôle
            if ($table == 'Admin') {
                header("Location: admin_dashboard.php");
            } elseif ($table == 'Soignant') {
                header("Location: ../accueil/dashboard.php");
            } else {
                header("Location: patient_dashboard.php");
            }
            exit();
        }
    }
    echo "<script>alert('Identifiant ou mot de passe incorrect'); window.location.href='login.php';</script>";
}
?>


<!-- login.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/styles.css?v=1.0">
    <link rel="stylesheet" href="../css/login.css?v=1.0">
</head>
<body>
    <div class="login-container">
        <div class="login-image"></div>
        <div class="login-form">
            <h2 id="connexion">Connexion</h2>
            <p id="description">Bienvenue sur notre plateforme. Connectez-vous pour accéder à votre compte.</p>
            <form action="login.php" method="POST">
                <label for="identifiant">Identifiant :</label>
                <input type="text" id="identifiant" name="identifiant" required>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>

