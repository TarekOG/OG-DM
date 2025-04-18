<?php
// config.php
$servername = "localhost";  // Le serveur de base de données
$username = "root";         // Votre utilisateur MySQL
$password = "";             // Votre mot de passe MySQL (vide par défaut sur XAMPP)
$dbname = "medical_db";     // Nom de votre base de données, assurez-vous qu'elle existe

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué: " . $conn->connect_error);
}
?>
