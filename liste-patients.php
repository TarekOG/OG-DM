<?php
session_start();

// Vérification de la session admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login-admin.php');
    exit();
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'medical_db');
if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

// Récupérer tous les patients
$result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
?>
<a href="edit-patient.php?id=<?= $row['id'] ?>" class="btn">Modifier</a>
<a href="delete-patient.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Supprimer ce patient ?');">Supprimer</a>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Patients</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>Liste des Patients</h1>
        <a href="admin-dashboard.php" class="btn">Retour au tableau de bord</a>
        <a href="logout.php" class="logout-button">Se déconnecter</a>
    </header>

    <main>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; background: white; border-radius: 10px;">
            <thead style="background: #0077cc; color: white;">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Âge</th>
                    <th>Sexe</th>
                    <th>Pathologie</th>
                    <th>État</th>
                    <th>Tension</th>
                    <th>Date entrée</th>
                    <th>Date sortie</th>
                    <th>Lit</th>
                    <th>Service</th>
                    <th>Motif</th>
                    <th>Observation</th>
                    <th>Photo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nom']) ?></td>
                        <td><?= htmlspecialchars($row['prenom']) ?></td>
                        <td><?= htmlspecialchars($row['age']) ?></td>
                        <td><?= htmlspecialchars($row['sexe']) ?></td>
                        <td><?= htmlspecialchars($row['pathologie']) ?></td>
                        <td><?= htmlspecialchars($row['etat']) ?></td>
                        <td><?= htmlspecialchars($row['tension']) ?></td>
                        <td><?= htmlspecialchars($row['date_entree']) ?></td>
                        <td><?= htmlspecialchars($row['date_sortie']) ?></td>
                        <td><?= htmlspecialchars($row['lit']) ?></td>
                        <td><?= htmlspecialchars($row['service']) ?></td>
                        <td><?= htmlspecialchars($row['motif']) ?></td>
                        <td><?= htmlspecialchars($row['observation']) ?></td>
                        <td>
                            <?php if ($row['photo']): ?>
                                <img src="uploads/<?= $row['photo'] ?>" alt="Photo" width="60">
                            <?php else: ?>
                                Aucun
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
