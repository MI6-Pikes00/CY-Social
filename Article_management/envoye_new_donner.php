<?php
session_start();
// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['utilisateur'])) {
    // Redirection vers la page de connexion
    header("Location: ../Utilisateur/Connection.php");
    exit;
} else {
    //Récupération des données pour effectuer le traitement
    $utilisateur_email = $_SESSION['utilisateur']['email'];
    $numero_article = $_POST['id_article'];

    // Vérification si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $titre = $_POST['titre'];
        $categorie = $_POST['categorie'];
        $instructions = $_POST['instructions'];


        // Chemin complet du dossier utilisateur
        $dossier_utilisateur = '../data/' . md5($utilisateur_email);

        // Vérifie si le dossier utilisateur existe, sinon le crée
        if (!file_exists($dossier_utilisateur)) {
            mkdir($dossier_utilisateur, 0777, true);
        }

        // Chemin complet du fichier JSON dans le dossier utilisateur
        $nom_fichier = $dossier_utilisateur . '/article-' . $numero_article . "/article-" . $numero_article . '.json';
        // Vérifie si le fichier existe
        if (file_exists($nom_fichier)) {
            // Lit le contenu du fichier JSON
            $contenu = file_get_contents($nom_fichier);

            // Décodage des données JSON
            $article = json_decode($contenu, true);

            $article['titre'] = $titre;
            $article['categorie'] = $categorie;
            $article['instructions'] = $instructions;

            $article_json = json_encode($article);

            // Enregistrement des informations de l'article au format JSON
            file_put_contents($nom_fichier, $article_json);

            // Redirection après soumission de l'article
            header("Location: ../Utilisateur/Profil_Utilisateur.php");
        } else {
            // Le fichier n'existe pas, redirige vers le profil
            return header("Location: ../Utilisateur/Profil_Utilisateur.php");
        }
    }
}
