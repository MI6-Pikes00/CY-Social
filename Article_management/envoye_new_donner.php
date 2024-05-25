<?php
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['utilisateur'])) {
    header("Location: ../Utilisateur/Connection.php");
    exit;
}

$utilisateur_email = $_SESSION['utilisateur']['email'];
$numero_article = $_POST['id_article'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $titre = $_POST['titre'];
    $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
    $instructions = $_POST['instructions'];

    // Récupération des commentaires et des notes depuis les champs cachés
    $notes = isset($_POST['notes']) ? $_POST['notes'] : [];
    $commentaires = isset($_POST['commentaires']) ? $_POST['commentaires'] : [];

    // Chemin complet du dossier utilisateur
    $dossier_utilisateur = '../data/' . md5($utilisateur_email);

    if (!file_exists($dossier_utilisateur)) {
        mkdir($dossier_utilisateur, 0777, true);
    }

    // Types d'articles et fichiers JSON associés
    $types_articles = ['article', 'citation', 'video'];
    $found = false;

    foreach ($types_articles as $type) {
        $nom_fichier = $dossier_utilisateur . '/' . $type . '-' . $numero_article . '/' . $type . '-' . $numero_article . '.json';

        if (file_exists($nom_fichier)) {
            $found = true;
            // Lit le contenu du fichier JSON
            $contenu = file_get_contents($nom_fichier);

            // Décodage des données JSON
            $article = json_decode($contenu, true);

            // Mise à jour des champs communs
            $article['titre'] = $titre;
            $article['instructions'] = $instructions;

            if ($type == 'article') {
                $article['categorie'] = $categorie;
                // Gérer les images et les vidéos si présentes
                if (isset($_FILES['images'])) {
                    $article['images'] = [];
                    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                        $file_name = $_FILES['images']['name'][$key];
                        $file_path = $dossier_utilisateur . '/' . $type . '-' . $numero_article . '/images/' . $file_name;
                        move_uploaded_file($tmp_name, $file_path);
                        $article['images'][] = $file_path;
                    }
                }

                if (isset($_FILES['video'])) {
                    $video_name = $_FILES['video']['name'];
                    $video_path = $dossier_utilisateur . '/' . $type . '-' . $numero_article . '/videos/' . $video_name;
                    move_uploaded_file($_FILES['video']['tmp_name'], $video_path);
                    $article['video'] = $video_path;
                }
            } elseif ($type == 'video') {
                if (isset($_FILES['video'])) {
                    $video_name = $_FILES['video']['name'];
                    $video_path = $dossier_utilisateur . '/' . $type . '-' . $numero_article . '/videos/' . $video_name;
                    move_uploaded_file($_FILES['video']['tmp_name'], $video_path);
                    $article['video'] = $video_path;
                }
            } 

            // Mise à jour des commentaires et des notes
            $article['notes'] = $notes;
            $article['commentaires'] = $commentaires;

            // Encodage des données JSON mises à jour
            $article_json = json_encode($article, JSON_PRETTY_PRINT);

            // Enregistrement des informations de l'article au format JSON
            file_put_contents($nom_fichier, $article_json);

            // Redirection après soumission de l'article
            header("Location: ../Utilisateur/Profil_Utilisateur.php");
            exit;
        }
    }

    if (!$found) {
        // Le fichier n'existe pas, redirige vers le profil
        header("Location: ../Utilisateur/Profil_Utilisateur.php");
        exit;
    }
}
