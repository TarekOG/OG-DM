<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "medical_db");

// Vérifie si l'ID du patient est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête SQL pour récupérer les informations du patient
    $sql = "SELECT nom, prenom, age, pathologie, traitement, photo, date_visite FROM patients WHERE id = $id";
    $result = $conn->query($sql);

    // Vérifie si des résultats sont retournés
    if ($result->num_rows > 0) {
        // Récupérer les données du patient
        $row = $result->fetch_assoc();
    } else {
        // Si aucun patient n'est trouvé, afficher un message d'erreur
        echo "Aucun dossier trouvé pour ce patient.";
        exit;
    }
} else {
    echo "ID du patient manquant.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dossier Médical du Patient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Dossier Médical du Patient</h1>

    <!-- Affichage des données du patient -->
    <?php if (isset($row)): ?>
        <p><strong>Nom :</strong> <?= htmlspecialchars($row['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($row['prenom']) ?></p>
        <p><strong>Âge :</strong> <?= htmlspecialchars($row['age']) ?></p>
        <p><strong>Pathologie :</strong> <?= htmlspecialchars($row['pathologie']) ?></p>
        <p><strong>Traitement :</strong> <?= htmlspecialchars($row['traitement']) ?></p>
        <p><strong>Date de visite :</strong> <?= htmlspecialchars($row['date_visite']) ?></p>
        
        <!-- Afficher la photo du patient -->
        <img src="photos/<?= htmlspecialchars($row['photo']) ?>" alt="Photo du patient" />

    <?php else: ?>
        <p>Aucun dossier trouvé pour ce patient.</p>
    <?php endif; ?>
</div>

</body>
</html>