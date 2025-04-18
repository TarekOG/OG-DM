<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Informations de base
    $id_patient = $_POST['id_patient'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $age = $_POST['age'];
    $sexe = $_POST['sexe'];
    $pathologie = $_POST['pathologie'];
    $traitement = $_POST['traitement'];
    $date_entree = $_POST['date_entree'];
    $date_sortie = $_POST['date_sortie'];
    $num_lit = $_POST['num_lit'];
    $service = $_POST['service'];
    $motif_hospitalisation = $_POST['motif_hospitalisation'];
    $observation = $_POST['observation'];

    // Informations complémentaires (dossier médical)
    $date_naissance = $_POST['date_naissance'];
    $lieu_naissance = $_POST['lieu_naissance'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $situation_familiale = $_POST['situation_familiale'];
    $profession = $_POST['profession'];
    $num_securite_sociale = $_POST['num_securite_sociale'];
    $antecedents_medicaux = $_POST['antecedents_medicaux'];
    $taille = $_POST['taille'];
    $poids = $_POST['poids'];
    $groupe_sanguin = $_POST['groupe_sanguin'];
    $traitement_chronique = $_POST['traitement_chronique'];
    $observations_medicales = $_POST['observations_medicales'];

    // Gestion de la photo
    $photo = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = $_FILES['photo']['name'];
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $upload_dir = 'uploads/photos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $photo_new_name = uniqid('patient_', true) . '.' . pathinfo($photo_name, PATHINFO_EXTENSION);
        $photo_path = $upload_dir . $photo_new_name;
        if (move_uploaded_file($photo_tmp_name, $photo_path)) {
            $photo = $photo_path;
        } else {
            echo "<div class='error-message'>Erreur lors du téléchargement de la photo.</div>";
            exit;
        }
    }

    // Requête SQL pour insérer les données
    $query = "INSERT INTO patients (
                id_patient, nom, prenom, age, sexe, pathologie, traitement, date_entree, date_sortie, num_lit, service,
                motif_hospitalisation, observation, photo, date_naissance, lieu_naissance, adresse, telephone, email,
                situation_familiale, profession, num_securite_sociale, antecedents_medicaux, taille, poids, groupe_sanguin,
                traitement_chronique, observations_medicales
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ississsssssssssssssssssddssss',
            $id_patient, $nom, $prenom, $age, $sexe, $pathologie, $traitement, $date_entree, $date_sortie, $num_lit,
            $service, $motif_hospitalisation, $observation, $photo, $date_naissance, $lieu_naissance, $adresse,
            $telephone, $email, $situation_familiale, $profession, $num_securite_sociale, $antecedents_medicaux,
            $taille, $poids, $groupe_sanguin, $traitement_chronique, $observations_medicales
        );
        if ($stmt->execute()) {
            header("Location: admin-dashboard.php");
            exit;
        } else {
            echo "<div class='error-message'>Erreur lors de l'ajout du patient.</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='error-message'>Erreur lors de la préparation de la requête.</div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Patient</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Styles CSS (identiques à la version précédente) */
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f9ff;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .form-container {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
            font-weight: 700;
        }
        h2 {
            color: #007BFF;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }
        form { width: 100%; }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="tel"],
        input[type="email"],
        select,
        textarea,
        input[type="file"] {
            padding: 12px 15px;
            border: 1.8px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            outline: none;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="tel"]:focus,
        input[type="email"]:focus,
        select:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #007BFF;
            box-shadow: 0 0 6px #007BFFaa;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .form-row .form-group {
            flex: 1;
            min-width: 200px;
        }
        button[type="submit"] {
            width: 100%;
            background-color: #007BFF;
            color: white;
            font-size: 1.1rem;
            padding: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            text-align: center;
            width: 100%;
            padding: 12px;
            background-color: #6c757d;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #5a6268;
        }
        .error-message {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Ajouter un Patient</h1>
        <form method="POST" action="add-patient.php" enctype="multipart/form-data" novalidate>
            <h2>Informations de base</h2>
            <div class="form-group">
                <label for="id_patient">ID Patient</label>
                <input type="text" id="id_patient" name="id_patient" required placeholder="Ex: 12345">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required placeholder="Nom de famille">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required placeholder="Prénom">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Âge</label>
                    <input type="number" id="age" name="age" required min="0" max="150" placeholder="Âge en années">
                </div>
                <div class="form-group">
                    <label for="sexe">Sexe</label>
                    <select id="sexe" name="sexe" required>
                        <option value="" disabled selected>Choisir</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="pathologie">Pathologie</label>
                <input type="text" id="pathologie" name="pathologie" required placeholder="Ex: Diabète, Asthme...">
            </div>
            <div class="form-group">
                <label for="traitement">Traitement</label>
                <input type="text" id="traitement" name="traitement" placeholder="Traitement prescrit">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="date_entree">Date d'entrée</label>
                    <input type="date" id="date_entree" name="date_entree" required>
                </div>
                <div class="form-group">
                    <label for="date_sortie">Date de sortie</label>
                    <input type="date" id="date_sortie" name="date_sortie">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="num_lit">Numéro de lit</label>
                    <input type="number" id="num_lit" name="num_lit" required min="1" placeholder="Numéro du lit">
                </div>
                <div class="form-group">
                    <label for="service">Service</label>
                    <input type="text" id="service" name="service" required placeholder="Ex: Cardiologie">
                </div>
            </div>
            <div class="form-group">
                <label for="motif_hospitalisation">Motif d'hospitalisation</label>
                <input type="text" id="motif_hospitalisation" name="motif_hospitalisation" placeholder="Motif">
            </div>
            <div class="form-group">
                <label for="observation">Observations</label>
                <textarea id="observation" name="observation" rows="3" placeholder="Informations complémentaires..."></textarea>
            </div>

            <h2>Informations complémentaires</h2>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" required>
                </div>
                <div class="form-group">
                    <label for="lieu_naissance">Lieu de naissance</label>
                    <input type="text" id="lieu_naissance" name="lieu_naissance" placeholder="Ville, pays">
                </div>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse complète</label>
                <input type="text" id="adresse" name="adresse" placeholder="Rue, code postal, ville">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" placeholder="Ex: 04 67 46 00 33">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="exemple@domaine.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="situation_familiale">Situation familiale</label>
                    <input type="text" id="situation_familiale" name="situation_familiale" placeholder="Ex: Marié(e), Célibataire">
                </div>
                <div class="form-group">
                    <label for="profession">Profession</label>
                    <input type="text" id="profession" name="profession" placeholder="Ex: Retraité(e), Employé(e)">
                </div>
            </div>

            <div class="form-group">
                <label for="num_securite_sociale">N° Sécurité sociale</label>
                <input type="text" id="num_securite_sociale" name="num_securite_sociale" placeholder="Ex: 2 39 05 34 999 999 / 35">
            </div>

            <div class="form-group">
                <label for="antecedents_medicaux">Antécédents médicaux</label>
                <textarea id="antecedents_medicaux" name="antecedents_medicaux" rows="3" placeholder="Ex: Hypertension, Diabète, Allergies..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="taille">Taille (m)</label>
                    <input type="number" step="0.01" id="taille" name="taille" placeholder="1.65">
                </div>
                <div class="form-group">
                    <label for="poids">Poids (kg)</label>
                    <input type="number" step="0.1" id="poids" name="poids" placeholder="64.0">
                </div>
                <div class="form-group">
                    <label for="groupe_sanguin">Groupe sanguin</label>
                    <input type="text" id="groupe_sanguin" name="groupe_sanguin" placeholder="Ex: O Rh+">
                </div>
            </div>

            <div class="form-group">
                <label for="traitement_chronique">Traitement chronique</label>
                <textarea id="traitement_chronique" name="traitement_chronique" rows="3" placeholder="Ex: ODRIK 2 mg : 1 gélule/jour"></textarea>
            </div>

            <div class="form-group">
                <label for="observations_medicales">Observations médicales détaillées</label>
                <textarea id="observations_medicales" name="observations_medicales" rows="4" placeholder="Notes cliniques, examens, etc."></textarea>
            </div>

            <div class="form-group">
                <label for="photo">Photo du patient</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>

            <button type="submit">Ajouter Patient</button>
            <a href="admin-dashboard.php" class="back-btn">← Retour au Dashboard</a>
        </form>
    </div>
</body>
</html>
