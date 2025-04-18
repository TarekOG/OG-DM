<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once('config.php');

// Démarrer la session
session_start();

// Vérifier si l'administrateur est déjà connecté
if (isset($_SESSION['admin_id'])) {
    echo "Vous êtes déjà connecté. Redirection vers le tableau de bord...";
    header("Location: admin-dashboard.php");  // Rediriger vers le tableau de bord admin si déjà connecté
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];  // Mot de passe en clair

    // Requête pour récupérer l'admin avec l'identifiant
    $sql = "SELECT * FROM admins WHERE username = ?"; 
    $stmt = $conn->prepare($sql); // Utilisation de la connexion $conn
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'admin existe
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        
        // Vérifier si le mot de passe correspond
        if ($admin['password'] === $password) {
            // Lancer la session et rediriger l'admin vers le tableau de bord
            $_SESSION['admin_id'] = $admin['id'];  // Stocker l'id de l'admin dans la session
            var_dump($_SESSION); // Vérifiez si la session est correctement définie
            header("Location: admin-dashboard.php");
            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Formulaire de connexion -->
    <div class="login-container">
        <div class="login-card">
            <h2>Connexion Administrateur</h2>

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

            <form method="POST" action="login-admin.php">
                <div class="input-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn">Se connecter</button>
            </form>

            <p class="redirect-link">
                Vous n'êtes pas administrateur ? <a href="login-patient.php">Accédez à l'espace patient</a>
            </p>
        </div>
    </div>

</body>
</html>
