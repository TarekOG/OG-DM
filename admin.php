<?php
$conn = new mysqli("localhost", "root", "", "medical_db");
$result = $conn->query("SELECT * FROM patients");
?>

<h2>Gestion des patients</h2>

<a class="btn" href="ajouter-patient.php">➕ Ajouter un patient</a>

<table border="1" cellspacing="0" cellpadding="8">
<tr>
  <th>Nom</th><th>Prénom</th><th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['nom'] ?></td>
  <td><?= $row['prenom'] ?></td>
  <td>
    <a href="modifier-patient.php?id=<?= $row['id'] ?>">✏️ Modifier</a> |
    <a href="supprimer-patient.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer ce patient ?')">❌ Supprimer</a>
  </td>
</tr>
<?php endwhile; ?>

</table>
