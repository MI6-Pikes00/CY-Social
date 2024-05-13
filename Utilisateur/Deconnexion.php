<?php
// Démarre la session
session_start();

// Détruit toutes les données de session
session_destroy();

// Redirige l'utilisateur vers la page de connexion après la déconnexion
header('Location: ../Accueil.php');
exit;
