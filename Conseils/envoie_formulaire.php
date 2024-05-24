<!-- ICI le php à plusieurs fonction, premièrement il vérifie qu'un utilisateur soit bien connecter puis il permet de soumettre le formulaire qui permet l'enregistrement d'article -->
<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user'])) {
    // Redirection vers la page de connexion
    header("Location: ../Utilisateur/Connection.php");
    exit;
}

// Récupération des informations de l'utilisateur connecté
$user = $_SESSION['user'];
$user_email = $user['email']; // Supposons que l'email soit utilisé comme identifiant de l'utilisateur

// Chemin complet du dossier utilisateur
$dossier_utilisateur = '../data/' . md5($user_email);

// Vérifie si le dossier utilisateur existe, sinon le crée
if (!file_exists($dossier_utilisateur)) {
    header("Location: ../Utilisateur/Connection.php");
    exit;
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_article = $_POST['type'];

    switch ($type_article) {
        case "article":
            // Récupération des données du formulaire
            $titre = $_POST['titre'];
            $categorie = $_POST['categorie'];
            $instructions = $_POST['instructions'];

            // Génération d'un identifiant unique pour l'article
            $num_article = uniqid();

            // Date et heure de création de l'article
            $date_creation = date("Y-m-d H:i:s");

            // Nom de l'auteur de l'article
            $auteur = $user['nom'] . ' ' . $user['prenom'];

            // Chemin complet du dossier de l'article dans le dossier utilisateur
            $dossier_article = $dossier_utilisateur . '/article-' . $num_article;

            // Création du dossier de l'article s'il n'existe pas
            if (!file_exists($dossier_article)) {
                mkdir($dossier_article, 0777, true); // Crée le dossier récursivement avec les permissions appropriées
            }

            // Chemin complet du fichier JSON dans le dossier de l'article
            $nom_fichier = $dossier_article . '/' . "article-" . $num_article . '.json';

            // ------------------------------------------------------------------------------------ Traitement des images ------------------------------------------------------------------------------------
            // Chemin du dossier pour les images de l'article
            $image_dossier_article = $dossier_article . '/images';

            // Création du dossier des images de l'article s'il n'existe pas
            if (!file_exists($image_dossier_article)) {
                mkdir($image_dossier_article, 0777, true); // Crée le dossier récursivement avec les permissions appropriées
            }

            $images_filepaths = array(); // Pour stocker les chemins des images téléchargées

            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $image_nom = basename($_FILES['images']['name'][$key]);
                    $image_tmp = $_FILES['images']['tmp_name'][$key];
                    $image_filepath = $image_dossier_article . '/' . $key . "_" . $image_nom;

                    if (move_uploaded_file($image_tmp, $image_filepath)) {
                        error_log("Le fichier $image_nom a été chargé avec succès.");
                        $images_filepaths[] = $image_filepath;
                    } else {
                        error_log("Erreur lors du chargement du fichier $image_nom.");
                    }
                }
            }

            // ------------------------------------------------------------------------------------ Traitement de la vidéo ------------------------------------------------------------------------------------
            // Chemin du dossier pour les vidéos de l'article
            $video_dossier_article = $dossier_article . '/videos';

            // Création du dossier des vidéos de l'article s'il n'existe pas
            if (!file_exists($video_dossier_article)) {
                mkdir($video_dossier_article, 0777, true); // Crée le dossier récursivement avec les permissions appropriées
            }

            $video_file = ""; // Pour stocker le chemin de la vidéo téléchargée

            if (!empty($_FILES['video']['name'])) {
                $video_nom = basename($_FILES['video']['name']);
                $video_tmp = $_FILES['video']['tmp_name'];
                $video_filepath = $video_dossier_article . '/' . $video_nom;

                // Déplacement du fichier vidéo vers le dossier approprié
                if (move_uploaded_file($video_tmp, $video_filepath)) {
                    error_log("Le fichier vidéo $video_nom a été chargé avec succès.");
                    $video_file = $video_filepath; // Enregistre le chemin de la vidéo
                } else {
                    error_log("Erreur lors du chargement du fichier vidéo $video_nom");
                }
            }

            // Enregistrement des informations de l'article au format JSON
            $article_data = array(
                'type' => $type_article,
                'numero_article' => $num_article,
                'date_creation' => $date_creation,
                'auteur' => $auteur,
                'titre' => $titre,
                'categorie' => $categorie,
                'instructions' => $instructions,
                'images' => $images_filepaths,
                'video' => $video_file
            );

            // Encodage des données de l'article en JSON
            $article_json = json_encode($article_data);

            // Enregistrement du fichier JSON dans le dossier de l'article
            file_put_contents($nom_fichier, $article_json);

            // Redirection après soumission de l'article
            header('Location: ../Utilisateur/Profil_Utilisateur.php');
            exit;
            break;
        case "citation":
            // Récupération des données du formulaire
            $titre = $_POST['titre'];
            $instructions = $_POST['instructions'];

            // Génération d'un identifiant unique pour l'article
            $num_article = uniqid();

            // Date et heure de création de l'article
            $date_creation = date("Y-m-d H:i:s");

            // Nom de l'auteur de l'article
            $auteur = $user['nom'] . ' ' . $user['prenom'];

            // Chemin complet du dossier de l'article dans le dossier utilisateur
            $dossier_article = $dossier_utilisateur . '/citation-' . $num_article;

            // Création du dossier de l'article s'il n'existe pas
            if (!file_exists($dossier_article)) {
                mkdir($dossier_article, 0777, true); // Crée le dossier récursivement avec les permissions appropriées
            }

            // Chemin complet du fichier JSON dans le dossier de l'article
            $nom_fichier = $dossier_article . '/' . "citation-" . $num_article . '.json';

            // Enregistrement des informations de l'article au format JSON
            $article_data = array(
                'type' => $type_article,
                'numero_article' => $num_article,
                'date_creation' => $date_creation,
                'auteur' => $auteur,
                'titre' => $titre,
                'instructions' => $instructions
            );

            // Encodage des données de l'article en JSON
            $article_json = json_encode($article_data);

            // Enregistrement du fichier JSON dans le dossier de l'article
            file_put_contents($nom_fichier, $article_json);

            // Redirection après soumission de l'article
            header('Location: ../Utilisateur/Profil_Utilisateur.php');
            exit;
            break;
        case "video":
            // Récupération des données du formulaire
            $titre = $_POST['titre'];
            $instructions = $_POST['instructions'];

            // Génération d'un identifiant unique pour l'article
            $num_article = uniqid();

            // Date et heure de création de l'article
            $date_creation = date("Y-m-d H:i:s");

            // Nom de l'auteur de l'article
            $auteur = $user['nom'] . ' ' . $user['prenom'];

            // Chemin complet du dossier de l'article dans le dossier utilisateur
            $dossier_article = $dossier_utilisateur . '/video-' . $num_article;

            // Création du dossier de l'article s'il n'existe pas
            if (!file_exists($dossier_article)) {
                mkdir($dossier_article, 0777, true); // Crée le dossier récursivement avec les permissions appropriées
            }

            // Chemin complet du fichier JSON dans le dossier de l'article
            $nom_fichier = $dossier_article . '/' . "video-" . $num_article . '.json';

            // ------------------------------------------------------------------------------------ Traitement de la vidéo ------------------------------------------------------------------------------------
            // Chemin du dossier pour les vidéos de l'article
            $video_dossier_article = $dossier_article . '/videos';

            // Création du dossier des vidéos de l'article s'il n'existe pas
            if (!file_exists($video_dossier_article)) {
                mkdir($video_dossier_article, 0777, true); // Crée le dossier récursivement avec les permissions appropriées
            }

            $video_file = ""; // Pour stocker le chemin de la vidéo téléchargée

            if (!empty($_FILES['video']['name'])) {
                $video_nom = basename($_FILES['video']['name']);
                $video_tmp = $_FILES['video']['tmp_name'];
                $video_filepath = $video_dossier_article . '/' . $video_nom;

                // Déplacement du fichier vidéo vers le dossier approprié
                if (move_uploaded_file($video_tmp, $video_filepath)) {
                    error_log("Le fichier vidéo $video_nom a été chargé avec succès.");
                    $video_file = $video_filepath; // Enregistre le chemin de la vidéo
                } else {
                    error_log("Erreur lors du chargement du fichier vidéo $video_nom");
                }
            }

            // Enregistrement des informations de l'article au format JSON
            $article_data = array(
                'type' => $type_article,
                'numero_article' => $num_article,
                'date_creation' => $date_creation,
                'auteur' => $auteur,
                'titre' => $titre,
                'instructions' => $instructions,
                'video' => $video_file
            );

            // Encodage des données de l'article en JSON
            $article_json = json_encode($article_data);

            // Enregistrement du fichier JSON dans le dossier de l'article
            file_put_contents($nom_fichier, $article_json);

            // Redirection après soumission de l'article
            header('Location: ../Utilisateur/Profil_Utilisateur.php');
            exit;





            break;
        default:
            error_log("pas de catégorie transmise");
    }
}
?>