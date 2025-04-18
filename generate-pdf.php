<?php
session_start();

require_once('tcpdf/tcpdf.php');

if (!isset($_SESSION["patient"])) {
    header("Location: login-patient.php");
    exit();
}

$patient = $_SESSION["patient"];

// Création du PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Ajouter des informations au PDF
$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'Dossier Medical du Patient', 0, 1, 'C');
$pdf->Ln(10);  // Espacement

$pdf->Cell(0, 10, 'ID: ' . $patient['id'], 0, 1);
$pdf->Cell(0, 10, 'Nom: ' . $patient['nom'], 0, 1);
$pdf->Cell(0, 10, 'Prenom: ' . $patient['prenom'], 0, 1);
$pdf->Cell(0, 10, 'Age: ' . $patient['age'], 0, 1);
$pdf->Cell(0, 10, 'Sexe: ' . $patient['sexe'], 0, 1);
$pdf->Cell(0, 10, 'Pathologie: ' . $patient['pathologie'], 0, 1);
$pdf->Cell(0, 10, 'Etat: ' . $patient['etat'], 0, 1);
$pdf->Cell(0, 10, 'Tension artérielle: ' . $patient['tension'], 0, 1);
$pdf->Cell(0, 10, 'Date d\'entrée: ' . $patient['date_entree'], 0, 1);
$pdf->Cell(0, 10, 'Date de sortie: ' . $patient['date_sortie'], 0, 1);
$pdf->Cell(0, 10, 'Numéro de lit: ' . $patient['lit'], 0, 1);
$pdf->Cell(0, 10, 'Service: ' . $patient['service'], 0, 1);
$pdf->Cell(0, 10, 'Motif d\'hospitalisation: ' . $patient['motif'], 0, 1);
$pdf->Cell(0, 10, 'Observation: ' . $patient['observation'], 0, 1);

// Ajouter une photo si elle existe
if ($patient['photo']) {
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Photo:', 0, 1);
    $pdf->Image('uploads/' . $patient['photo'], '', '', 50, 50);
}

$pdf->Output('Dossier_Medical_' . $patient['id'] . '.pdf', 'D');
?>
