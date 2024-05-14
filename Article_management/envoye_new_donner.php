<?php
session_start();
//Récupération des données pour effectuer le traitement
$user_email = $_POST['email'];
$num_article = $_POST['num_article'];

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user'])) {
    // Redirection vers la page de connexion
    header("Location: ../Utilisateur/Connection.php");
    exit;
}

$user = $_SESSION['user'];

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $titre = $_POST['titre'];
    $categorie = $_POST['categorie'];
    $instructions = $_POST['instructions'];

    $nom_fichier = '../data/' . md5($user_email) . '/' . "article-" . $num_article . '.json';

    if (file_exists($nom_fichier)) {
        // Date et heure de modification de l'article
        $date_modification = date("Y-m-d H:i:s");

        // Nom de l'auteur de l'article
        $auteur = $user['nom'] . ' ' . $user['prenom'];
        // Traitement de l'image
        $image_nom = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_dossier = "uploads/$user_email/images/";
        move_uploaded_file($image_tmp, $image_dossier . $num_article . "_" . $image_nom);

        // Traitement de la vidéo
        $video_nom = $_FILES['video']['name'];
        $video_tmp = $_FILES['video']['tmp_name'];
        $video_dossier = "uploads/$user_email/videos/";
        move_uploaded_file($video_tmp, $video_dossier . $num_article . "_" . $video_nom);

        // Préparation des données pour le format JSON
        $article_data = array(
            'numero_article' => $num_article,
            'date_creation' => $date_modification,
            'auteur' => $auteur,
            'titre' => $titre,
            'categorie' => $categorie,
            'instructions' => $instructions
        );
        $article_json = json_encode($article_data);
        // Enregistrement des informations de l'article au format JSON
        file_put_contents($nom_fichier, $article_json);

        // Redirection après soumission de l'article pour l'instant juste un message
        echo "Article modifié";
        header("Location: ../Utilisateur/Profil_Utilisateur.php");
    } else {
        echo "Formulaire non existant";
        header("Location: ../Conseils/Formulaire_soumission.php");
    }
}
