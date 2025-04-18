<!-- connexion.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Admin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-container">
    <h2>Connexion Administrateur</h2>
    <form action="connexion.php" method="POST">
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" name="username" required>

      <label for="password">Mot de passe :</label>
      <input type="password" name="password" required>

      <button type="submit" name="submit">Se connecter</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];

      if ($username == 'admin' && $password == 'admin') {
        // Si les identifiants sont corrects, rediriger vers le dossier du patient
        header('Location: dossier-patient.php');
      } else {
        echo '<p style="color: red;">Identifiants incorrects</p>';
      }
    }
    ?>
  </div>
</body>
</html>
