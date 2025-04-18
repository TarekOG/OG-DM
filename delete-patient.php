<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit();
}

if (isset($_GET['id_patient']) && !empty($_GET['id_patient'])) {
    $id_patient = $_GET['id_patient'];

    $sql = "DELETE FROM patients WHERE id_patient = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_patient);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?msg=supprimé");
        exit();
    } else {
        echo "Erreur lors de la suppression du patient : " . $stmt->error;
    }
} else {
    echo "Aucun ID de patient fourni.";
}
?>
