<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si les informations de l'utilisateur sont présentes dans la session
if (isset($_SESSION['utilisateur'])) {

    // Récupérer les données de l'utilisateur à partir de la session
    $utilisateur_session_info = $_SESSION['utilisateur'];

    // Messages d'information dans la console php
    error_log("User récupéré sur Profil_Utilisateur.php");
    
    //Récupération des données pour effectuer le traitement
    $utilisateur_email = $utilisateur_session_info['email'];
    $id_article = $_POST['id_article'];

    // Message d'information
    error_log("Récupération des données de l'article en cours...");

    // Chemin du répertoire contenant les dossiers des utilisateurs
    $utilisateurs_chemin = '../data/';

    // Parcours de tous les dossiers d'utilisateurs
    $utilisateurs_fichiers = glob($utilisateurs_chemin . '*', GLOB_ONLYDIR);

    // Liste des types de dossiers à vérifier
    $types_dossiers = ['article-', 'video-', 'citation-'];

    // Fonction pour supprimer un dossier et son contenu
    function supprimer_dossier($dossier) {
        if (is_dir($dossier)) {
            // Supprimer tous les fichiers du dossier
            array_map('unlink', glob("$dossier/*.*"));
            // Supprimer le dossier lui-même
            rmdir($dossier);
        }
    }

    // Parcours de tous les dossiers d'utilisateurs pour trouver et supprimer les articles, citations, et vidéos
    foreach ($utilisateurs_fichiers as $utilisateur_fichier) {
        error_log("fichier :  $utilisateur_fichier.");

        // Parcours des types de dossiers
        foreach ($types_dossiers as $type_dossier) {
            // Chemin complet du fichier JSON de l'article
            $chemin_fichier_json = $utilisateur_fichier . '/' . $type_dossier . $id_article . '/' . $type_dossier . $id_article . '.json';

            // Vérifie si le fichier JSON de l'article existe
            if (file_exists($chemin_fichier_json)) {
                error_log("Le fichier $chemin_fichier_json existe. Suppression en cours...");

                // Supprimer le fichier JSON de l'article
                unlink($chemin_fichier_json);

                // Supprimer les sous-dossiers (images et vidéos)
                supprimer_dossier($utilisateur_fichier . '/' . $type_dossier . $id_article . '/images');
                supprimer_dossier($utilisateur_fichier . '/' . $type_dossier . $id_article . '/videos');

                // Supprimer le dossier de l'article lui-même
                rmdir($utilisateur_fichier . '/' . $type_dossier . $id_article);

                // Affichage d'un message de succès
                error_log("Le dossier $type_dossier$id_article et son contenu ont été supprimés avec succès.");
            } else {
                // Affichage d'un message d'erreur si le fichier JSON de l'article n'existe pas
                error_log("Le fichier $chemin_fichier_json n'existe pas, utilisateur suivant ...");
            }
        }
    }

    // Redirection après la suppression
    header("Location: ../Utilisateur/Profil_Utilisateur.php");
    exit;

} else {
    // Rediriger l'utilisateur vers la page de connexion si les informations de l'utilisateur ne sont pas présentes dans la session
    header('Location: ../Utilisateur/Connexion.php');
    exit;
}
?>

