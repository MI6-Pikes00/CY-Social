<!-- Ici le php permet de supprimer l'article associé a l'utilisateur -->
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
    $numero_article = $_POST['id_article'];

    // Chemin complet du dossier de l'article à supprimer
    $dossier_article = '../data/' . md5($utilisateur_email) . '/article-' . $numero_article;

    // Vérifie si le dossier de l'article existe et le supprime s'il existe
    if (is_dir($dossier_article)) {
        // Supprimer le fichier JSON de l'article
        $nom_fichier_json = $dossier_article . '/article-' . $numero_article . '.json';
        if (file_exists($nom_fichier_json)) {
            unlink($nom_fichier_json);
        }

        // Supprimer le dossier des images de l'article
        $nom_dossier_img = $dossier_article . '/images';
        if (is_dir($nom_dossier_img)) {
            array_map('unlink', glob("$nom_dossier_img/*.*"));
            rmdir($nom_dossier_img);
        }

        // Supprimer le dossier des vidéos de l'article
        $nom_dossier_vid = $dossier_article . '/videos';
        if (is_dir($nom_dossier_vid)) {
            array_map('unlink', glob("$nom_dossier_vid/*.*"));
            rmdir($nom_dossier_vid);
        }

        // Supprimer le dossier de l'article lui-même
        rmdir($dossier_article);

        // Redirection après la suppression de l'article
        header("Location: ../Utilisateur/Profil_Utilisateur.php");
        exit;
    } else {
        echo "échec, le dossier de l'article n'existe pas ";  // Le dossier de l'article n'existe pas
        return header("Location: ../Utilisateur/Profil_Utilisateur.php");
    }
} else {
    // Rediriger l'utilisateur vers la page de connexion si les informations de l'utilisateur ne sont pas présentes dans la session
    return header('Location: ../Utilisateur/Connexion.php');
}
?>
