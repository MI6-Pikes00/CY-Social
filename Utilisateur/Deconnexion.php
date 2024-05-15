<!-- Ici le php sert juste a réinitialiser la session, c'est a dire oublié l'utilisateur qui est connecté -->
<?php
// Démarre la session, récupère les données présente sur le serveur
session_start();

// Vérifie si une session est active
if(isset($_SESSION['user'])) {

    // Messages d'information dans la console php
    error_log("Déconnexion en cours...");

    // Si oui, vide les données de session
    session_unset();

    // Détruit la session
    session_destroy();

    // Messages d'information dans la console php
    error_log("Déconnexion terminé.");

    // Redirige l'utilisateur vers la page de connexion ou une autre page selon vos besoins
    header('Location: ../Accueil.php');
    exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
} else {
    // Si aucune session n'est active, affiche un message d'erreur ou effectue d'autres actions selon vos besoins
    echo "Aucune session active.";
}
?>
