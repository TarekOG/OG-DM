<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = [];

// Supprimer la session
session_destroy();

// Redirection vers la page d'accueil ou de connexion
header("Location: index.html");
exit();