<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header('Location: login-patient.php');
    exit();
}

require_once('config.php');
require_once('tcpdf/tcpdf.php');

$id_patient = $_SESSION['patient_id'];

// Récupérer les données du patient
$stmt = $conn->prepare("SELECT * FROM patients WHERE id_patient = ?");
$stmt->bind_param("i", $id_patient);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Patient non trouvé.");
}

$patient = $result->fetch_assoc();

// Créer un nouveau PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Infos du PDF
$pdf->SetCreator('AMSP');
$pdf->SetAuthor('Hôpital AMSP');
$pdf->SetTitle('Dossier Médical');
$pdf->SetSubject('Informations médicales du patient');

// Configuration générale du PDF
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

$pdf->AddPage();

// Ajout du logo dans l'en-tête
$pdf->Image('images/logo.png', 10, 10, 30, 30);

// Titre du PDF
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(0, 102, 204);
$pdf->Cell(0, 10, 'Dossier Médical du Patient', 0, 1, 'C');

// Espace
$pdf->Ln(5);

// Photo du patient avec bordure arrondie (si disponible)
if (!empty($patient['photo']) && file_exists('uploads/photos/' . $patient['photo'])) {
    $pdf->Image('uploads/photos/' . $patient['photo'], 150, 15, 30, 30, '', '', '', true, 300, '', false, false, 1);
    $pdf->Ln(35);
}

// Fonction pour créer un tableau
function createDataTable($pdf, $data, $title) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetTextColor(0, 102, 204);
    $pdf->Cell(0, 10, $title, 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);

    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->SetLineWidth(0.2);
    $pdf->SetFont('', 'B');

    // En-têtes du tableau
    $w = array(45, 85); // Largeurs des colonnes
    $pdf->SetFont('helvetica', 'B', 10);

    // Boucle sur les données
    $pdf->SetFont('helvetica', '', 10);
    foreach ($data as $label => $value) {
        $pdf->Cell($w[0], 6, $label . ':', 0, 0, 'L');
        $pdf->Cell($w[1], 6, $value, 0, 1, 'L');
    }

    $pdf->Ln(3);
}

// Préparer les données pour les informations personnelles
$personalData = array(
    'Identifiant' => $patient['id_patient'],
    'Nom' => $patient['nom'],
    'Prénom' => $patient['prenom'],
    'Âge' => $patient['age'] . ' ans',
    'Sexe' => $patient['sexe'],
    'Date de naissance' => $patient['date_naissance'],
    'Lieu de naissance' => $patient['lieu_naissance'],
    'Adresse' => $patient['adresse'],
    'Téléphone' => $patient['telephone'],
    'Email' => $patient['email'],
    'Situation familiale' => $patient['situation_familiale'],
    'Profession' => $patient['profession'],
    'Numéro de sécurité sociale' => $patient['num_securite_sociale']
);

// Préparer les données pour les informations médicales
$medicalData = array(
    'Pathologie' => $patient['pathologie'],
    'Traitement' => $patient['traitement'],
    'Antécédents médicaux' => $patient['antecedents_medicaux'],
    'Taille (m)' => $patient['taille'],
    'Poids (kg)' => $patient['poids'],
    'Groupe sanguin' => $patient['groupe_sanguin'],
    'Traitement chronique' => $patient['traitement_chronique'],
    'Date d\'entrée' => $patient['date_entree'],
    'Date de sortie' => $patient['date_sortie'],
    'Numéro de lit' => $patient['num_lit'],
    'Service' => $patient['service'],
    'Motif d\'hospitalisation' => $patient['motif_hospitalisation'],
    'Observations' => $patient['observation'],
    'Observations médicales' => $patient['observations_medicales']
);

// Créer le tableau des informations personnelles
createDataTable($pdf, $personalData, 'Informations personnelles');

// Créer le tableau des informations médicales
createDataTable($pdf, $medicalData, 'Informations médicales');

// Pied de page personnalisé
$pdf->SetY(-20);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(0, 102, 204);
$pdf->Cell(0, 10, 'Hôpital AMSP | Contact: contact@amsp.com | www.amsp.com', 0, 0, 'C');

// Sortie du PDF
$pdf->Output('dossier_patient_' . $patient['id_patient'] . '.pdf', 'I');
