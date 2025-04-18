<?php
require_once('tcpdf/tcpdf.php');

$nom = $_GET['nom'];
$prenom = $_GET['prenom'];
$age = $_GET['age'];
$pathologie = $_GET['pathologie'];
$traitement = $_GET['traitement'];

// Création du PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 14);

$html = "
<h2>Dossier Médical du Patient</h2>
<p><strong>Nom :</strong> $nom</p>
<p><strong>Prénom :</strong> $prenom</p>
<p><strong>Âge :</strong> $age</p>
<p><strong>Pathologie :</strong> $pathologie</p>
<p><strong>Traitement :</strong> $traitement</p>
";

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('dossier_patient.pdf', 'I');
?>
