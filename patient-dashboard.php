<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login-patient.php');
    exit();
}

$id_patient = (int) $_SESSION['patient_id'];
$conn = new mysqli('localhost', 'root', '', 'medical_db');
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM patients WHERE id_patient = ?");
$stmt->bind_param("i", $id_patient);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header('Location: logout.php');
    exit();
}

$patient = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Chemin par défaut
$default_photo = "uploads/photos/default.png";

// Vérifier si la photo existe et est accessible
if (!empty($patient['photo']) && file_exists($patient['photo'])) {
    $photo_path = $patient['photo'];
} else {
    $photo_path = $default_photo;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Dossier Médical - AMSP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts et icônes -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Reset et base */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e0f7fa; /* Bleu ciel très clair */
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            color: #34495e;
            margin-bottom: 30px;
        }

        .photo {
            text-align: center;
            margin-bottom: 25px;
        }

        .photo img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #dcdde1;
        }

        /* Ajout d'un style pour les sections */
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #f9fbfc;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .section h3 {
            color: #3498db;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 14px 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            background: #f9fbfc;
            color: #555;
            width: 35%;
        }

        .actions {
            margin-top: 30px;
            text-align: center;
        }

        .actions a {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .download {
            background-color: #27ae60;
        }

        .download:hover {
            background-color: #219150;
        }

        .logout {
            background-color: #c0392b;
        }

        .logout:hover {
            background-color: #992d22;
        }

        /* Style pour les informations complémentaires */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .info-grid div {
            padding: 15px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .info-grid strong {
            display: block;
            margin-bottom: 5px;
            color: #3498db;
        }

        @media (max-width: 600px) {
            table,
            th,
            td {
                font-size: 14px;
            }

            .photo img {
                width: 120px;
                height: 120px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bienvenue <?= htmlspecialchars($patient['prenom']) ?> 👋</h2>

    <div class="photo">
        <img src="<?= htmlspecialchars($photo_path) ?>" alt="Photo du patient">
    </div>

    <div class="section">
        <h3>Informations personnelles</h3>
        <table>
            <tr><th>Identifiant</th><td><?= htmlspecialchars($patient['id_patient']) ?></td></tr>
            <tr><th>Nom</th><td><?= htmlspecialchars($patient['nom']) ?></td></tr>
            <tr><th>Prénom</th><td><?= htmlspecialchars($patient['prenom']) ?></td></tr>
            <tr><th>Âge</th><td><?= htmlspecialchars($patient['age']) ?> ans</td></tr>
            <tr><th>Sexe</th><td><?= htmlspecialchars($patient['sexe']) ?></td></tr>
            <tr><th>Date de naissance</th><td><?= htmlspecialchars($patient['date_naissance']) ?></td></tr>
            <tr><th>Lieu de naissance</th><td><?= htmlspecialchars($patient['lieu_naissance']) ?></td></tr>
            <tr><th>Adresse</th><td><?= htmlspecialchars($patient['adresse']) ?></td></tr>
            <tr><th>Téléphone</th><td><?= htmlspecialchars($patient['telephone']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($patient['email']) ?></td></tr>
            <tr><th>Situation familiale</th><td><?= htmlspecialchars($patient['situation_familiale']) ?></td></tr>
            <tr><th>Profession</th><td><?= htmlspecialchars($patient['profession']) ?></td></tr>
            <tr><th>Numéro de sécurité sociale</th><td><?= htmlspecialchars($patient['num_securite_sociale']) ?></td></tr>
        </table>
    </div>

    <div class="section">
        <h3>Informations médicales</h3>
        <table>
            <tr><th>Pathologie</th><td><?= htmlspecialchars($patient['pathologie']) ?></td></tr>
            <tr><th>Traitement</th><td><?= htmlspecialchars($patient['traitement']) ?></td></tr>
            <tr><th>Antécédents médicaux</th><td><?= htmlspecialchars($patient['antecedents_medicaux']) ?></td></tr>
            <tr><th>Taille (m)</th><td><?= htmlspecialchars($patient['taille']) ?></td></tr>
            <tr><th>Poids (kg)</th><td><?= htmlspecialchars($patient['poids']) ?></td></tr>
            <tr><th>Groupe sanguin</th><td><?= htmlspecialchars($patient['groupe_sanguin']) ?></td></tr>
            <tr><th>Traitement chronique</th><td><?= htmlspecialchars($patient['traitement_chronique']) ?></td></tr>
            <tr><th>Date d'entrée</th><td><?= htmlspecialchars($patient['date_entree']) ?></td></tr>
            <tr><th>Date de sortie</th><td><?= htmlspecialchars($patient['date_sortie']) ?></td></tr>
            <tr><th>Numéro de lit</th><td><?= htmlspecialchars($patient['num_lit']) ?></td></tr>
            <tr><th>Service</th><td><?= htmlspecialchars($patient['service']) ?></td></tr>
            <tr><th>Motif d'hospitalisation</th><td><?= htmlspecialchars($patient['motif_hospitalisation']) ?></td></tr>
            <tr><th>Observations</th><td><?= nl2br(htmlspecialchars($patient['observation'])) ?></td></tr>
            <tr><th>Observations médicales</th><td><?= nl2br(htmlspecialchars($patient['observations_medicales'])) ?></td></tr>
        </table>
    </div>

    <div class="actions">
        <a href="download-pdf.php" target="_blank" class="download"><i class="bi bi-file-earmark-pdf-fill"></i> Télécharger PDF</a>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</div>

</body>
</html>
