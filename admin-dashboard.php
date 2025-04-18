<?php
require_once('config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit();
}

// Suppression d'un patient
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM patients WHERE id_patient = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $delete_id);
    if ($stmt->execute()) {
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Erreur lors de la suppression : " . $stmt->error;
    }
}

// Récupérer les informations des patients
$sql = "SELECT id_patient, nom, prenom, age, pathologie, service, motif_hospitalisation, photo, date_sortie FROM patients";
$result = $conn->query($sql);

// Compter les patients hospitalisés
$sql_hospitalises = "SELECT COUNT(*) AS total FROM patients WHERE date_sortie IS NULL OR date_sortie = ''";
$res_hosp = $conn->query($sql_hospitalises);
$total_hospitalises = ($res_hosp && $res_hosp->num_rows > 0) ? $res_hosp->fetch_assoc()['total'] : 0;

// Compter les patients dispensés
$sql_dispenses = "SELECT COUNT(*) AS total FROM patients WHERE date_sortie IS NOT NULL AND date_sortie <> ''";
$res_disp = $conn->query($sql_dispenses);
$total_dispenses = ($res_disp && $res_disp->num_rows > 0) ? $res_disp->fetch_assoc()['total'] : 0;

// Nombre total de patients
$total_patients = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset et base */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e0f7fa; /* Bleu ciel très clair */
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #03a9f4; /* Bleu ciel */
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            border-right: 2px solid #0277bd; /* Bleu plus foncé pour la bordure */
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-nav li {
            margin-bottom: 15px;
        }

        .sidebar-nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar-nav a:hover {
            background-color: #0288d1; /* Bleu légèrement plus foncé au survol */
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-bottom: 2px solid #81d4fa; /* Bordure bleu ciel plus prononcée */
        }

        header h1 {
            margin: 0 0 10px 0;
            color: #03a9f4; /* Bleu ciel */
        }

        header p {
            color: #555;
            margin: 0;
        }

        /* Dashboard Content */
        .dashboard-content {
            display: flex;
            flex-direction: column;
        }

        /* Cartes Info */
        .card-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            text-align: center;
            border: 1px solid #b2ebf2; /* Bordure bleu ciel légère */
        }

        .card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .card p {
            font-size: 1.5rem;
            font-weight: 700;
            color: #03a9f4; /* Bleu ciel */
            margin: 0;
        }

        /* Liste Patients (Cartes) */
        .patient-list {
            margin-bottom: 30px;
        }

        .patient-list h3 {
            margin-bottom: 20px;
            color: #03a9f4; /* Bleu ciel */
            border-bottom: 2px solid #81d4fa; /* Bordure bleu ciel plus prononcée */
            padding-bottom: 5px;
        }

        .patient-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .patient-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            border: 1px solid #b2ebf2; /* Bordure bleu ciel légère */
        }

        .patient-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #03a9f4; /* Bordure bleu ciel autour de la photo */
        }

        .patient-card h4 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .patient-card p {
            margin: 5px 0;
            color: #555;
        }

        /* Tableau Patients */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: 1px solid #b2ebf2; /* Bordure bleu ciel légère */
        }

        .table-container h3 {
            margin-bottom: 20px;
            color: #03a9f4; /* Bleu ciel */
            border-bottom: 2px solid #81d4fa; /* Bordure bleu ciel plus prononcée */
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
            color: #333;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        /* Boutons */
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #03a9f4; /* Bleu ciel */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }

        .btn:hover {
            background-color: #0288d1; /* Bleu légèrement plus foncé au survol */
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .card-container {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                max-height: 200px;
                overflow-y: auto;
            }

            .patient-cards {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>AMSP PANEL</h2>
        </div>
        <ul class="sidebar-nav">
            <li><a href="admin-dashboard.php">Tableau de bord</a></li>
            <li><a href="add-patient.php">Ajouter Patient</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </div>

    <!-- Contenu Principal -->
    <div class="main-content">
        <header>
            <div class="header">
                <h1>Bienvenue dans le tableau de bord</h1>
                <p>Gérez les dossiers patients et autres informations</p>
            </div>
        </header>

        <div class="dashboard-content">

            <!-- Cartes Info -->
            <div class="card-container">
                <div class="card">
                    <h3>Nombre Total de Patients</h3>
                    <p><?php echo $total_patients; ?></p>
                </div>
                <div class="card">
                    <h3>Patients Hospitalisés</h3>
                    <p><?php echo $total_hospitalises; ?></p>
                </div>
                <div class="card">
                    <h3>Patients Dispensés</h3>
                    <p><?php echo $total_dispenses; ?></p>
                </div>
            </div>

            <!-- Liste Patients (Cartes) -->
            <div class="patient-list">
                <h3>Liste des Patients</h3>
                <div class="patient-cards">
                    <?php
                    $result->data_seek(0); // Réinitialise le pointeur du résultat
                    while ($patient = $result->fetch_assoc()) {
                        echo '<div class="patient-card">';
                        echo '<img src="' . htmlspecialchars($patient['photo']) . '" alt="Photo de ' . htmlspecialchars($patient['prenom']) . '" class="patient-photo">';
                        echo '<h4>' . htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) . '</h4>';
                        echo '<p>Age: ' . htmlspecialchars($patient['age']) . ' ans</p>';
                        echo '<p>Pathologie: ' . htmlspecialchars($patient['pathologie']) . '</p>';
                        echo '<p>Service: ' . htmlspecialchars($patient['service']) . '</p>';
                        echo '<p>Motif: ' . htmlspecialchars($patient['motif_hospitalisation']) . '</p>';
                        echo '<a href="edit-patient.php?id=' . htmlspecialchars($patient['id_patient']) . '" class="btn">Modifier</a>';
                        echo '<a href="admin-dashboard.php?delete_id=' . htmlspecialchars($patient['id_patient']) . '" class="btn delete-btn" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce patient ?\');">Supprimer</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Tableau Patients -->
            <div class="table-container">
                <h3>Détails des Patients</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Âge</th>
                            <th>Pathologie</th>
                            <th>Service</th>
                            <th>Motif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result->data_seek(0); // Réinitialise le pointeur du résultat
                        while ($patient = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($patient['id_patient']) . '</td>';
                            echo '<td>' . htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) . '</td>';
                            echo '<td>' . htmlspecialchars($patient['age']) . '</td>';
                            echo '<td>' . htmlspecialchars($patient['pathologie']) . '</td>';
                            echo '<td>' . htmlspecialchars($patient['service']) . '</td>';
                            echo '<td>' . htmlspecialchars($patient['motif_hospitalisation']) . '</td>';
                            echo '<td>';
                            echo '<a href="edit-patient.php?id=' . htmlspecialchars($patient['id_patient']) . '" class="btn">Modifier</a>';
                            echo '<a href="admin-dashboard.php?delete_id=' . htmlspecialchars($patient['id_patient']) . '" class="btn delete-btn" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce patient ?\');">Supprimer</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>
