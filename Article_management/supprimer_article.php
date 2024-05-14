<?php
//Récupération des données pour effectuer le traitement
$user_email = $_POST['email'];
$num_article = $_POST['num_article'];

// Chemin complet du fichier à supprimer
$nom_fichier = '../data/' . md5($user_email) . '/article-' . $num_article . '.json';
echo "Le nom du fichier: $nom_ficher";
// Vérifie si le fichier existe et le supprime s'il existe
if (file_exists($nom_fichier)) {
    unlink($nom_fichier);
    echo "suppression réussi"; // Suppression réussie
    header("Location: ../Utilisateur/Profil_Utilisateur.php");
    exit;
} else {
    echo "échec, le fichier n'existe pas ";  // Le fichier n'existe pas
    header("Location: ../Utilisateur/Profil_Utilisateur.php");
    exit;
}
?>
