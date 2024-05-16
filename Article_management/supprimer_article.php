<!-- Ici le php permet de supprimer l'article associé a l'utilisateur -->
<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si les informations de l'utilisateur sont présentes dans la session pour 
// pour créer une "sécurité" des données 
if (isset($_SESSION['user'])) {

    // Récupérer les données de l'utilisateur à partir de la session
    $user_session_info = $_SESSION['user'];

    // Messages d'information dans la console php
    error_log("User récupéré sur Profil_Utilisateur.php");

    //Récupération des données pour effectuer le traitement
    $user_email = $user_email['email'];
    $num_article = $_POST['num_article'];

    // Chemin complet du fichier à supprimer
    $nom_fichier = '../data/' . md5($user_email) . '/article-' . $num_article . '.json';
    echo "Le nom du fichier: $nom_ficher";
    // Vérifie si le fichier existe et le supprime s'il existe
    if (file_exists($nom_fichier)) {
        unlink($nom_fichier);
        echo "suppression réussi"; // Suppression réussie
        return header("Location: ../Utilisateur/Profil_Utilisateur.php");
        
    } else {
        echo "échec, le fichier n'existe pas ";  // Le fichier n'existe pas
        return header("Location: ../Utilisateur/Profil_Utilisateur.php");
    }
} else {
    // Rediriger l'utilisateur vers la page de connexion si les informations de l'utilisateur ne sont pas présentes dans la session
    return header('Location: ../Utilisateur/Connexion.php');
}
?>