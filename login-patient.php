<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once('config.php');

// Démarrer la session
session_start();

// Vérifier si le patient est déjà connecté
if (isset($_SESSION['patient_id'])) {
    header("Location: patient-dashboard.php");  // Rediriger vers le tableau de bord du patient
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'ID du patient envoyé via le formulaire
    $patient_id = $_POST['patient_id'];

    // Requête pour vérifier l'ID du patient dans la base de données
    $sql = "SELECT * FROM patients WHERE id_patient = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $patient_id); // Bind le paramètre patient_id
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si le patient existe
    if ($result->num_rows == 1) {
        // Lancer la session et rediriger le patient vers son tableau de bord
        $_SESSION['patient_id'] = $patient_id;
        header("Location: patient-dashboard.php");  // Redirection vers le tableau de bord
        exit();
    } else {
        $error = "Identifiant incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Patient</title>
    <link rel="stylesheet" href="s1.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Formulaire de connexion -->
    <div class="login-container">
        <div class="login-card">
            <h2>Connexion Patient</h2>

            <?php
            if (isset($error)) {
                echo "<script>Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '$error',
                    confirmButtonText: 'OK'
                });</script>";
            }
            ?>

            <form method="POST" action="login-patient.php">
                <div class="input-group">
                    <label for="patient_id">ID Patient :</label>
                    <input type="text" id="patient_id" name="patient_id" required>
                </div>

                <button type="submit" class="btn">Se connecter</button>
            </form>

            <p class="redirect-link">
                Vous n'êtes pas un patient ? <a href="login-admin.php">Accédez à l'espace administrateur</a>
            </p>
        </div>
    </div>

</body>
</html>
